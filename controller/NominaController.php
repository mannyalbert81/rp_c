<?php

class NominaController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $entidad = new CoreEntidadPatronalModel();
        
        session_start();
        
        if(empty( $_SESSION['usuario_usuarios'])){
            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else{
            
            $nombre_controladores = "PagosCXP";
            $id_rol= $_SESSION['id_rol'];
             $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );            
            if (empty($resultPer)){
                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else{
                
                $rsEntidad = $entidad->getBy(" 1 = 1 ");                
                
                $this->view_tesoreria("Pagos",array(
                    "resultSet"=>$rsEntidad
                    
                ));
                exit();                
            }
            
        }
        
        
    }
    
    public function index1(){
        
        $Empleados  = new EmpleadosModel();
        
        session_start();
        
        if(empty( $_SESSION['usuario_usuarios'])){
            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else{
            
            $nombre_controladores = "PagoNomina";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $Empleados->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            if (empty($resultPer)){
                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else{                
               
                $this->view_Contable("PagoNomina",array(
                    
                    
                ));
                exit();
            }
            
        }
        
        
    }
    
    public function DiarioPagoNomina(){
        
       $Empleados = new EmpleadosModel();
       
       $respuesta = array();
       
       /* toma de valores de vista */
       $_anio_proceso   = $_POST['anio_procesos'];
       $_mes_proceso   = $_POST['mes_procesos'];
       
       /* consulta si ya se genero el proceso */
       $columnas1   = " aa.id_historial_diarios_tipo, aa.id_ccomprobantes";
       $tablas1     = " core_historial_diarios_tipo aa
                       INNER JOIN core_tipo_procesos bb
                       ON bb.id_tipo_procesos = aa.id_tipo_procesos";
       $where1      = " upper(bb.nombre_tipo_procesos) = 'PAGO NOMINA'"
                    . " AND aa.anio_historial_diarios_tipo = $_anio_proceso"
                    . " AND aa.mes_historial_diarios_tipo = $_mes_proceso";
       $id1         = "aa.id_historial_diarios_tipo";
       
       $rsConsulta1 = $Empleados->getCondiciones($columnas1, $tablas1, $where1, $id1);
       $_id_comprobantes = 0;
       if( !empty($rsConsulta1) ){
           $_id_comprobantes    = $rsConsulta1[0]->id_ccomprobantes;
           $respuesta['mensaje']= "EXISTE PROCESO";
           $respuesta['id_comprobante']= $_id_comprobantes;
       }else{
           $respuesta['mensaje']= "NO EXISTE PROCESO";
       }
       
       echo json_encode($respuesta);
        
    }
    
    public function graficaDiarioPagoNomina(){
        
        $Empleados = new EmpleadosModel();  
        $respuesta = array(); 
        $Cuentas   = new PlanCuentasModel();
        
        /* variables de sumatoria */
        $_sumaTotalDebito   = 0.00;
        $_sumaTotalCredito  = 0.00;
        
        /* toma de valores de vista */
        $_anio_proceso   = $_POST['anio_procesos'];
        $_mes_proceso   = $_POST['mes_procesos'];
        
        $_periodo   = $this->getPeriodo($_anio_proceso,$_mes_proceso); //metodo que devuelve periodo de remuneracion
        
        /* buscar remuneracion */
        $columnas1  = "sum(dd.salario_cargo) total_salario, sum(aa.horas_ext50) total_extras_50, sum(aa.horas_ext100) total_extras_100,"
                    . "sum(aa.fondos_reserva) fondos_reserva, sum(aa.dec_cuarto_sueldo) decimo_14, sum(aa.dec_tercero_sueldo) decimo_13,"
                    . "sum(aa.anticipo_sueldo) total_anticipo, sum(aa.aporte_iess1) aporte_iess_1,	sum(aa.asocap) total_asocap,"
                    . "sum(aa.prest_quirog_iess) total_prestamos_quirografarios, sum(aa.prest_hipot_iess) total_prestamos_hipotecarios,"
                    . "sum(aa.dcto_salario) total_descuento_salario, sum(aa.comision_asuntos_sociales) total_asuntos_sociales, sum(ee.aporte_iess_2) aporte_iess_2";
        $tablas1    = "public.reporte_nomina_empleados aa"
                    . " INNER JOIN public.empleados bb"
                    . " ON bb.id_empleados = aa.id_empleado"
                    . " INNER JOIN public.oficina cc"
                    . " ON cc.id_oficina = bb.id_oficina"
                    . " INNER JOIN public.cargos_empleados dd"
                    . " ON dd.id_cargo = bb.id_cargo_empleado"
                    . " INNER JOIN public.provisiones_nomina_empleados ee"
                    . " ON ee.id_empleados = bb.id_empleados";
        $where1     = " aa.periodo_registro='$_periodo'"
                    . " AND ee.periodo = '$_periodo'";
        $limit1     = " ";
        
        
        $rsConsulta1    = $Empleados->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit1);        
       
        if( sizeof($rsConsulta1) < 1){
            
            echo "<message>no existe datos en reporte<message>"; die();
        }
        
        /* variable para dibujar la tabla */
        $_divCabecera = '<div class=""> 
                        <div class="pull-right">
                        <button id="btnGenerar" class="btn btn-default" onclick="generaComprobante()"><i class="fa fa-save fa-1x" style="color:#00cc6a" aria-hidden="true" ></i> Generar Comprobante</button>
                        </div>
                        </div>';
        
        // la estructura de la tabla consta de 5 columnas 
        $_html = '';
        $_html .= $_divCabecera;
        $_html .= '<table width="100%"  id="tblDiario" class="table table-striped table-bordered" >';
        $_html .= "<thead>";
        $_html .= "<tr> <td></td> <td> CUENTA </td> <td> NOMBRE </td> <td> DEBITO </td> <td> CREDITO </td></tr>";
        $_html .= "</thead>"; 
        
        /* datos para tabla */
        $_t_saldo       = "";
        $_t_horas_50    = "";
        $_t_horas_100   = "";
        $_f_reserva     = "";
        $_decimo_13     = "";
        $_decimo_14     = "";
        $_t_anticipo    = $rsConsulta1[0]->total_anticipo;
        $_t_aporteIESSP = "";
        $_t_aporteIESS  = "";        
        $_t_asocap      = number_format((float)$rsConsulta1[0]->total_asocap,2,".",",");
        $_t_pres_quiro  = "";
        $_t_pres_hipot  = "";
        $_t_desc_salario= $rsConsulta1[0]->total_descuento_salario;
        $_t_asunt_social= $rsConsulta1[0]->total_asuntos_sociales;

        $_identificador_fila = 0;
        
        $contador = 1; //variable para dibujar contador de lineas
        
        /* para cuentas de plan cuentas */
        //cuentas debe
        /* salario */
        $_htmlBody = "";
        $_filas = "";
        $rsSaldo = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.05'");
        if(!empty($rsSaldo)){
            $_identificador_fila = ((int)$rsSaldo[0]->id_plan_cuentas) > 0 ? (int)$rsSaldo[0]->id_plan_cuentas : 0;
            $_sumaTotalDebito += $rsConsulta1[0]->total_salario;
            $_t_saldo       = number_format((float)$rsConsulta1[0]->total_salario,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsSaldo[0]->codigo_plan_cuentas, $rsSaldo[0]->nombre_plan_cuentas, $_t_saldo);
            
        }
        $rsHorasE = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.10.01'");
        if(!empty($rsHorasE)){            
            if( (float)$rsConsulta1[0]->total_extras_50 > 0 ){ 
                $_identificador_fila = ((int)$rsHorasE[0]->id_plan_cuentas) > 0 ? (int)$rsHorasE[0]->id_plan_cuentas : 0;
                $_sumaTotalDebito += $rsConsulta1[0]->total_extras_50;
                $_t_horas_50    = number_format((float)$rsConsulta1[0]->total_extras_50,2,".",",");
                $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsHorasE[0]->codigo_plan_cuentas, $rsHorasE[0]->nombre_plan_cuentas, $_t_horas_50); 
            }            
            if( (float)$rsConsulta1[0]->total_extras_100 > 0 ){   
                $_identificador_fila = ((int)$rsHorasE[0]->id_plan_cuentas) > 0 ? (int)$rsHorasE[0]->id_plan_cuentas : 0;
                $_sumaTotalDebito += $rsConsulta1[0]->total_extras_100;
                $_t_horas_100   = number_format((float)$rsConsulta1[0]->total_extras_100,2,".",",");
                $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsHorasE[0]->codigo_plan_cuentas, $rsHorasE[0]->nombre_plan_cuentas, $_t_horas_100);                
            }
            
        }
        
        $rsFondoR = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.25'");
        if(!empty($rsFondoR)){
            $_identificador_fila = ((int)$rsFondoR[0]->id_plan_cuentas) > 0 ? (int)$rsFondoR[0]->id_plan_cuentas : 0;
            $_sumaTotalDebito += $rsConsulta1[0]->fondos_reserva;
            $_f_reserva = number_format((float)$rsConsulta1[0]->fondos_reserva,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsFondoR[0]->codigo_plan_cuentas, $rsFondoR[0]->nombre_plan_cuentas, $_f_reserva);
        }
        $rsDecimo13 = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.15.01'");
        if(!empty($rsDecimo13)){
            $_identificador_fila = ((int)$rsDecimo13[0]->id_plan_cuentas) > 0 ? (int)$rsDecimo13[0]->id_plan_cuentas : 0;
            $_sumaTotalDebito += $rsConsulta1[0]->decimo_13;
            $_decimo_13 = number_format((float)$rsConsulta1[0]->decimo_13,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsDecimo13[0]->codigo_plan_cuentas, $rsDecimo13[0]->nombre_plan_cuentas, $_decimo_13);
        }
        $rsDecimo14 = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.15.02'");
        if(!empty($rsDecimo14)){
            $_identificador_fila = ((int)$rsDecimo14[0]->id_plan_cuentas) > 0 ? (int)$rsDecimo14[0]->id_plan_cuentas : 0;
            $_sumaTotalDebito += $rsConsulta1[0]->decimo_14;
            $_decimo_14 = number_format((float)$rsConsulta1[0]->decimo_14,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsDecimo14[0]->codigo_plan_cuentas, $rsDecimo14[0]->nombre_plan_cuentas, $_decimo_14);
        }        
        $rsAportePatronal = $Cuentas->getBy("codigo_plan_cuentas = '4.3.01.20'");
        if(!empty($rsAportePatronal)){
            $_identificador_fila = ((int)$rsAportePatronal[0]->id_plan_cuentas) > 0 ? (int)$rsAportePatronal[0]->id_plan_cuentas : 0;
            $_sumaTotalDebito += $rsConsulta1[0]->aporte_iess_2;
            $_t_aporteIESSP = number_format((float)$rsConsulta1[0]->aporte_iess_2,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "D", $rsAportePatronal[0]->codigo_plan_cuentas, $rsAportePatronal[0]->nombre_plan_cuentas, $_t_aporteIESSP);            
        }

        //cuentas haber        
        $rsAporteIess = $Cuentas->getBy("codigo_plan_cuentas = '2.5.03.06'");
        if(!empty($rsAporteIess)){
            $_identificador_fila = ((int)$rsAporteIess[0]->id_plan_cuentas) > 0 ? (int)$rsAporteIess[0]->id_plan_cuentas : 0;
            $_sumaTotalCredito += $rsConsulta1[0]->aporte_iess_1;
            $_t_aporteIESS = number_format((float)$rsConsulta1[0]->aporte_iess_1,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsAporteIess[0]->codigo_plan_cuentas, $rsAporteIess[0]->nombre_plan_cuentas, $_t_aporteIESS);
        }
        $rsDecimo13Haber = $Cuentas->getBy("codigo_plan_cuentas = '2.5.02.01'");
        if(!empty($rsDecimo13Haber)){
            $_identificador_fila = ((int)$rsDecimo13Haber[0]->id_plan_cuentas) > 0 ? (int)$rsDecimo13Haber[0]->id_plan_cuentas : 0;
            $_decimo_13_haber = 0;
            $_sumaTotalCredito += $_decimo_13_haber;
            /* realizar calculos para decimo tercer sueldo en credito */
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsDecimo13Haber[0]->codigo_plan_cuentas, $rsDecimo13Haber[0]->nombre_plan_cuentas, $_decimo_13_haber);
        }
        $rsDecimo14Haber = $Cuentas->getBy("codigo_plan_cuentas = '2.5.02.02'");
        if(!empty($rsDecimo14Haber)){
            $_identificador_fila = ((int)$rsDecimo14Haber[0]->id_plan_cuentas) > 0 ? (int)$rsDecimo14Haber[0]->id_plan_cuentas : 0;
            $_decimo_14_haber = 0;
            $_sumaTotalCredito += $_decimo_14_haber;
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsDecimo14Haber[0]->codigo_plan_cuentas, $rsDecimo14Haber[0]->nombre_plan_cuentas, $_decimo_14_haber);
        }
        $rsFReservaHaber = $Cuentas->getBy("codigo_plan_cuentas = '2.5.04.01'");
        if(!empty($rsFReservaHaber)){
            $_identificador_fila = ((int)$rsFReservaHaber[0]->id_plan_cuentas) > 0 ? (int)$rsFReservaHaber[0]->id_plan_cuentas : 0;
            $_f_reserva_haber = 0;
            $_sumaTotalCredito += $_f_reserva_haber;
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsFReservaHaber[0]->codigo_plan_cuentas, $rsFReservaHaber[0]->nombre_plan_cuentas, $_f_reserva_haber);            
        }
        $rsQuirografarios = $Cuentas->getBy("codigo_plan_cuentas = '2.5.03.01'");
        if(!empty($rsQuirografarios)){
            $_identificador_fila = ((int)$rsQuirografarios[0]->id_plan_cuentas) > 0 ? (int)$rsQuirografarios[0]->id_plan_cuentas : 0;
            $_sumaTotalCredito += (float)$rsConsulta1[0]->total_prestamos_quirografarios;
            $_t_pres_quiro  = number_format( (float)$rsConsulta1[0]->total_prestamos_quirografarios ,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsQuirografarios[0]->codigo_plan_cuentas, $rsQuirografarios[0]->nombre_plan_cuentas, $_t_pres_quiro);            
        }
        $rsHipotecarios = $Cuentas->getBy("codigo_plan_cuentas = '2.5.03.02'");
        if(!empty($rsHipotecarios)){
            $_identificador_fila = ((int)$rsHipotecarios[0]->id_plan_cuentas) > 0 ? (int)$rsHipotecarios[0]->id_plan_cuentas : 0;
            $_sumaTotalCredito += (float)$rsConsulta1[0]->total_prestamos_hipotecarios;
            $_t_pres_hipot  = number_format((float)$rsConsulta1[0]->total_prestamos_hipotecarios,2,".",",");
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsHipotecarios[0]->codigo_plan_cuentas, $rsHipotecarios[0]->nombre_plan_cuentas, $_t_pres_hipot);            
        }
        $rsAsocap = $Cuentas->getBy("codigo_plan_cuentas = '2.5.90.06'");
        if(!empty($rsAsocap)){
            $_identificador_fila = ((int)$rsAsocap[0]->id_plan_cuentas) > 0 ? (int)$rsAsocap[0]->id_plan_cuentas : 0;
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsAsocap[0]->codigo_plan_cuentas, $rsAsocap[0]->nombre_plan_cuentas, $_t_asocap);            
        }
        
        //buscar los anticipos sueldos
        $_mes_modificado= str_pad($_mes_proceso, 2, 0, STR_PAD_LEFT);
        $fechaAnticipos = $_anio_proceso.$_mes_modificado; //debe estar en formato 'YYYYMM'
        $columnas2  = "  aa.id_empleado,bb.monto_cuota, dd.id_plan_cuentas, ee.codigo_plan_cuentas, ee.nombre_plan_cuentas ";
        $tablas2    = " anticipo_sueldo_empleados aa
                        INNER JOIN cuotas_avances_empleados bb ON bb.id_solicitud = aa.id_anticipo
                        INNER JOIN estado cc ON cc.id_estado = aa.id_estado
                        INNER JOIN empleados_cuentas_contables dd ON dd.id_empleados = aa.id_empleado
                        INNER JOIN plan_cuentas ee ON ee.id_plan_cuentas = dd.id_plan_cuentas";
        $where2     = " cc.nombre_estado = 'APROBADO GERENCIA'
                        AND cc.tabla_estado = 'PERMISO_EMPLEADO'
                        AND to_char(bb.fecha_cuota,'YYYYMM') = '$fechaAnticipos'";
        $id2        = " aa.id_empleado";
        
        $rsConsulta2    = $Empleados->getCondiciones($columnas2, $tablas2, $where2, $id2);
        
        if( !empty( $rsConsulta2 ) ){
            foreach ( $rsConsulta2 as $res ){
                $_identificador_fila = ((int)$res->id_plan_cuentas) > 0 ? (int)$res->id_plan_cuentas : 0;
                $_sumaTotalCredito += (float)$res->monto_cuota;
                $_monto_anticipo    = number_format((float)$res->monto_cuota,2,".",",");
                $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $res->codigo_plan_cuentas, $res->nombre_plan_cuentas, $_monto_anticipo);
            }
        }
        
        //buscar pago a proveedores  --- no implementado
        
        //buscar cuenta de pago nomina x Pagar
        $rsNominaPagar = $Cuentas->getBy("codigo_plan_cuentas = '2.5.01.01'");
        if(!empty($rsNominaPagar)){
            $_identificador_fila = ((int)$rsNominaPagar[0]->id_plan_cuentas) > 0 ? (int)$rsNominaPagar[0]->id_plan_cuentas : 0;
            /* incompleto pero se queda supuesto que es el residuo de sumado el debito con la suma de creditos */
            $_nomina_x_pagar    = $_sumaTotalDebito - $_sumaTotalCredito;
            $_filas .= $this->devuelveFila_tr($_identificador_fila,$contador++, "C", $rsNominaPagar[0]->codigo_plan_cuentas, $rsNominaPagar[0]->nombre_plan_cuentas, $_nomina_x_pagar);
            $_sumaTotalCredito += $_nomina_x_pagar;
        }
        
        //no implementado el trabajar con arrray para las cuentas contables
        //$_cuentas = array('SALARIO'=>'4.3.01.05','HEXTRAS'=>'4.3.01.10.01','FRESERVA'=>'4.3.01.25'); 
        
        if(!empty($_filas)){
            $_htmlBody = "<tbody>".$_filas."</tbody>";
        }
        
        $_sumaTotalDebito   =  number_format((float)$_sumaTotalDebito,2,".",",");
        $_sumaTotalCredito  =  number_format((float)$_sumaTotalCredito,2,".",",");
        $_htmlFoot  = "<tfoot><tr><th colspan=\"3\"></th><th>$_sumaTotalDebito</th><th>$_sumaTotalCredito</th></tr></tfoot>";
        
        $_html .= $_htmlBody;
        $_html .= $_htmlFoot;
        $_html .= "</table>";
        $respuesta['html']  = $_html;
        
        echo json_encode($respuesta);
        
                
    }
    
    public function indexconsulta(){
        
        
        $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
        if(!isset($_POST['peticion'])){
            echo 'sin conexion';
            return;
        }
        
        $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        
        $cuentasPagar = new CuentasPagarModel();
        
        $columnas = "aa.id_cuentas_pagar, aa.descripcion_cuentas_pagar,aa.fecha_cuentas_pagar, aa.origen_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar,
                	bb.id_lote, bb.nombre_lote, cc.id_proveedores,cc.nombre_proveedores, cc.identificacion_proveedores, ee.id_usuarios, ee.nombre_usuarios,
                	ff.id_forma_pago, ff.nombre_forma_pago";
        
        $tablas = "tes_cuentas_pagar aa
                INNER JOIN tes_lote bb
                ON aa.id_lote = bb.id_lote
                INNER JOIN proveedores cc
                ON aa.id_proveedor = cc.id_proveedores
                INNER JOIN estado dd
                ON aa.id_estado = dd.id_estado
                INNER JOIN usuarios ee
                ON bb.id_usuarios = ee.id_usuarios
                LEFT JOIN forma_pago ff
                ON aa.id_forma_pago = ff.id_forma_pago";
        
        $where = " 1=1 AND dd.nombre_estado = 'GENERADO' ";
        
        //para los parametros de where 
        if(!empty($busqueda)){
            
            $where .= "AND ( bb.nombre_lote = '$busqueda' OR cc.identificacion_proveedores like '$busqueda%' )";
        }
        
        $id = "aa.id_cuentas_pagar";
        
        //para obtener cantidad         
        $rsResultado = $cuentasPagar->getCantidad("1", $tablas, $where, $id);        
        
        $cantidad = 0;
        $html = "";
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
            $cantidad = $rsResultado[0]->total;
        }
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet = $cuentasPagar->getCondicionesPag( $columnas, $tablas, $where, $id, $limit);
        
        $tpages = ceil($cantidad/$per_page);
        $_nombre_tabla = "tbl_lista_cuentas_pagar";
        
        if( $cantidad > 0 ){
           
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th >CHEQUE</th>';
            $html.='<th >TRANSFERENCIA</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            
            //print_r($resultSet); die();
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_lote.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->origen_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->descripcion_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_cuentas_pagar.'</td>';
                
                if($res->id_forma_pago == null || $res->id_forma_pago == "" ){
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="fa fa-money"></i></a></td>';
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                }else{
                
                    if($res->nombre_forma_pago != 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="fa fa-money"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                    
                    if($res->nombre_forma_pago == 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                }
                
                $html.='</tr>';
                
            }
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"buscaCuentasPagar").'';
            $html.='</div>';
            
            
            
        }else{
            
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">CHEQUE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">TRANSFERENCIA</th>'; 
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        //array de datos
        $respuesta = array();
        $respuesta['tabla_datos'] = $html;
        $respuesta['valores'] = array('cantidad'=>$cantidad);
        $respuesta['nombre_tabla'] = $_nombre_tabla;
        echo json_encode($respuesta);
    }
    
    public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        for($i=$pmin; $i<=$pmax; $i++) {
            if($i==$page) {
                $out.= "<li class='active'><a>$i</a></li>";
            }else if($i==1) {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    /** BEGIN PARA FUNCIONES DE PAGO DE NOMINA **/
    
    public function getPeriodo($anio=2019, $mes=1){
        
        /* para pruebas */
        /*$anio = $_POST['anio'];
        $mes = $_POST['mes']; */ 
        
        $diainicio = 22;
        $diafinal = 21;
        $fechafinal = "";
        
        $mes--;
        if ($mes==0)
        {
            $mes=12;
            $anio--;
        }
        $fechainicio = $diainicio."/".$mes."/".$anio;
        $mes++;        
        if ($mes>12){
            $mes=1;
            $anio++;
            $fechafinal = $diafinal."/".$mes."/".$anio;
        }else{
            $fechafinal = $diafinal."/".$mes."/".$anio;
        }
        
        $periodoactual = $fechainicio ."-". $fechafinal;
        
        return $periodoactual;
        
    }
    
    private function devuelveFila_tr($identificador=0,$_orden,$_naturaleza,$_codigo,$_nombre,$_monto){
        
        if($_naturaleza == "D"){
            return "<tr id=\"tr_$identificador\"><td>$_orden</td><td>$_codigo</td><td>$_nombre</td><td>$_monto</td><td>0.00</td></tr>";
        }else if( $_naturaleza == "C"){
            return "<tr id=\"tr_$identificador\"><td>$_orden</td><td>$_codigo</td><td>$_nombre</td><td>0.00</td><td>$_monto</td></tr>";
        }else{
            return "<tr><td></td><td></td><td></td><td>0.00</td><td>0.00</td></tr>";
        }
        
    }

    public function GeneraComprobanteNomina(){

        session_start();
        $Empleados = new EmpleadosModel();
        try{
            $Empleados->beginTran();
            /** tomar valores de session */
            $_id_usuarios    = $_SESSION['id_usuarios'];
            $_usuario_usuarios  = $_SESSION['usuario_usuarios'];
            /** obtener valores de la vista */
            $datos_comprobante_nomina = json_decode($_POST['lista_nomina']);
            $sumatoria_comprobante        = $_POST['valor_comprobante'];
            $mesNomina  = (int)$_POST['mes_nomina'];

            /** variables de local */
            $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
            
            //para pruebas
            //echo  "valor total de comprobante --> ".$sumatoria_comprobante;
            //die();

            /** buscar datos para comprobante */
            $_id_entidades              = 1;
            $_id_tipo_comprobantes      = 0;
            $_numero_ccomprobantes      = ""; 
            $_retencion_ccomprobantes   = ""; 
            $_valor_ccomprobantes       = 0;
            $_concepto_ccomprobantes    = "";
            $_valor_letras              = "";
            $_fecha_ccomprobantes       = "";
            $_id_forma_pago             = "";
            $_referencia_doc_ccomprobantes  = "";
            $_observaciones_ccomprobantes   = "";
            $_id_proveedores                = null;
            $_tipo_cuenta_ccomprobantes     = null;
            $_transaccion_ccomprobantes     = "nomina";

            /* consulta consecutivo */
            $columnas1  = " bb.id_tipo_comprobantes, bb.nombre_tipo_comprobantes, aa.id_consecutivos, lpad( (aa.valor_consecutivos)::text, aa.espacio_consecutivos, '0') secuencial";
            $tablas1    = " consecutivos aa
                        inner join tipo_comprobantes bb on bb.id_tipo_comprobantes = aa.id_tipo_comprobantes ";
            $where1     = " bb.nombre_tipo_comprobantes = 'CONTABLE' ";
            $id1        = " bb.id_tipo_comprobantes";

            $rsConsulta1= $Empleados->getCondiciones($columnas1,$tablas1,$where1,$id1);
            $_id_tipo_comprobantes  = $rsConsulta1[0]->id_tipo_comprobantes;
            $_numero_ccomprobantes  = $rsConsulta1[0]->secuencial;
            $_id_consecutivos       = $rsConsulta1[0]->id_consecutivos;

            /* consulta forma de pago */
            $columnas2  = " id_forma_pago, nombre_forma_pago";
            $tablas2    = " public.forma_pago";
            $where2     = " nombre_forma_pago = 'EFECTIVO'";
            $id2        = " id_forma_pago";
            $rsConsulta2= $Empleados->getCondiciones($columnas2,$tablas2,$where2,$id2);
            $_id_forma_pago = $rsConsulta2[0]->id_forma_pago;

            /* actualizacion de consecutivo */
            $_queryActualizacion = "UPDATE consecutivos 
                                    SET numero_consecutivos = lpad((valor_consecutivos+1)::text,espacio_consecutivos,'0'),
                                    valor_consecutivos = valor_consecutivos+1
                                    WHERE id_consecutivos = $_id_consecutivos ";
            $Empleados->executeNonQuery($_queryActualizacion);

            $_valor_ccomprobantes   = $sumatoria_comprobante;
            $_valor_letras  = $Empleados->numtoletras($_valor_ccomprobantes);
            $_concepto_ccomprobantes    = "INGRESO PAGO DE NOMINA";
            $_fecha_ccomprobantes   = date("Y-m-d");
            $_observaciones_ccomprobantes   = "generacion nomina mes-".$meses[$mesNomina-1];

            /** insertado datos de comprobante nomina */
            $_queryInsertadoNomina  = " INSERT INTO public.ccomprobantes(
                id_entidades, id_tipo_comprobantes, numero_ccomprobantes, retencion_ccomprobantes, valor_ccomprobantes,
                concepto_ccomprobantes, id_usuarios, valor_letras, fecha_ccomprobantes, id_forma_pago, referencia_doc_ccomprobantes, 
                observaciones_ccomprobantes, id_proveedores, tipo_cuenta_ccomprobantes, usuario_usuarios, transaccion_ccomprobantes )
                VALUES($_id_entidades,$_id_tipo_comprobantes,'$_numero_ccomprobantes','$_retencion_ccomprobantes','$_valor_ccomprobantes',
                '$_concepto_ccomprobantes','$_id_usuarios','$_valor_letras', '$_fecha_ccomprobantes', '$_id_forma_pago',
                '$_referencia_doc_ccomprobantes','$_observaciones_ccomprobantes',null,null,'$_usuario_usuarios','$_transaccion_ccomprobantes') RETURNING id_ccomprobantes";

            $_id_comprobantes = $Empleados->executeInsertQuery($_queryInsertadoNomina);

            /** recorrido para insertar el detalle de comprobante */
            foreach ($datos_comprobante_nomina as $data){
                /* variables a formatear para insertado */
                $_valor_debe    = 0.00;
                $_valor_haber   = 0.00;
                $_descripcion   = "'nomina mes ".$meses[(int)$mesNomina-1]."'";
                /* estructura de data es definida en la view Nomina.js */
                //item ["id_plan_cuentas"],item ["naturaleza_cuentas"],item ['valor_cuentas'] 
                $_id_plan_cuentas   = $data->id_plan_cuentas;
                if( $data->naturaleza_cuentas == "D" ){
                    $_valor_debe    = $data->valor_cuentas;
                }else if( $data->naturaleza_cuentas == "C"){
                    $_valor_haber   = $data->valor_cuentas;
                }
                $_queryInsertadoDetalle = "INSERT INTO public.dcomprobantes(
                    id_ccomprobantes, numero_dcomprobantes, id_plan_cuentas, descripcion_dcomprobantes, debe_dcomprobantes, haber_dcomprobantes)
                    VALUES($_id_comprobantes, '$_numero_ccomprobantes', $_id_plan_cuentas, $_descripcion, $_valor_debe, $_valor_haber)";	        
	        
                $Empleados->executeNonQuery($_queryInsertadoDetalle);                
               
            }

            /* viene insertado de historial del diario tipo */
            $columnas3  = " id_tipo_procesos,id_modulos";
            $tablas3    = " public.core_tipo_procesos";
            $where3     = " upper(nombre_tipo_procesos) = 'PAGO NOMINA'";
            $id3        = " id_tipo_procesos";
            $rsConsulta3= $Empleados->getCondiciones($columnas3,$tablas3,$where3,$id3);
            $_id_tipo_procesos  = $rsConsulta3[0]->id_tipo_procesos;
            $_id_modulos        = $rsConsulta3[0]->id_modulos;

            /** para insertado de historial */
            $_anio_proceso = date('Y');
            $_funcion   = "ins_core_historial_diario";
            $_parametros= "$_id_tipo_procesos,$_id_modulos,$_id_comprobantes,'$_usuario_usuarios',$_anio_proceso,$mesNomina";
            $_queryFuncion  = $Empleados->getconsultaPG($_funcion,$_parametros);
            $Empleados->llamarconsultaPG($_queryFuncion);

            $error_pg   = pg_last_error();
            $error_php  = error_get_last();
            if( !empty($error_pg) || !empty($error_php) ){
                throw new Exception("error encontrado al insertar comprobante");
            }
            $Empleados->endTran("COMMIT");

            $respuesta  = array();
            $respuesta['mensaje'] = 'OK';

            echo json_encode($respuesta);

            /** la mayorizacion del comprobante no implementado*/
           

        }catch(Exception $ex){
            $Empleados->endTran();
            echo "<message>".$ex->getMessage()."<message>";
            
        }

       
    }
   
    /** TERMINA PARA FUNCIONES DE PAGO DE NOMINA **/

    public function funciones(){
        
        $dato = $_POST['mes'];

        $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');

        echo "mes es ".$meses[(int)$dato-1];

        $_query = "INSERT INTO public.temp_comprobantes
        (id_usuario_registra, id_plan_cuentas, observacion_temp_comprobantes, 
        debe_temp_comprobantes, haber_temp_comprobantes)
        VALUES(15, 885, 'prueba', 0.00, 0.00) RETURNING id_temp_comprobantes";

    $_resultado = $Empleados->executeInsertQuery($_query);

    echo "la respuesta es <br>".$_resultado; die();
    }
    
   
}
    

?>