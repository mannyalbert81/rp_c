<?php

class GenerarChequeController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}


	public function index(){
	
		$entidad = new CoreEntidadPatronalModel();
				
		session_start();
		
		if(empty( $_SESSION['usuario_usuarios'] )){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		    
		}else{
		    
		    $nombre_controladores = "GenerarCheque";
		    $id_rol= $_SESSION['id_rol'];
		    $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		    
		    if (empty($resultPer)){
		        
		        $this->view("Error",array(
		            "resultado"=>"No tiene Permisos de Acceso Empleo"
		            
		        ));
		        exit();
		    }	
		    
		    $rsEntidad = $entidad->getBy(" 1 = 1 ");
		    
		    $this->view_tesoreria("GenerarCheque",array(
		        "resultSet"=>$rsEntidad
		        
		    ));
		}
		
	}
	
	public function indexCheque(){
	    
	    session_start();
	    $cuentasPagar = new CuentasPagarModel();
	    
	    if(empty( $_SESSION['usuario_usuarios'] )){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	        
	    }else{
	        
	        $nombre_controladores = "GenerarCheque";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $cuentasPagar->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (empty($resultPer)){
	            
	            $this->view("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso Pagos Cheque"
	                
	            ));
	            exit();
	        }
	       	        
	        if( !isset($_GET['id_cuentas_pagar']) ){
	            // validacion si no hay parametro cuentas por pagar 
	            $this->redirect("Pagos","index");
	            exit();
	        }
	        
	        $_id_cuentas_pagar = $_GET['id_cuentas_pagar'];
	        	        
	        // traer datos de consecutivo PAGO
	        $col1  = " LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') \"secuencial\" ";
	        $tab1  = " public.consecutivos ";
	        $whe1  = " nombre_consecutivos = 'PAGOS' and id_entidades = 1 "; 
	        $id1   = " id_consecutivos ";
	        $rsConsulta1   = $cuentasPagar->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        //traer datos de moneda
	        $col2  = " id_moneda, nombre_moneda, signo_moneda, signo_moneda || nombre_moneda  \"moneda\" ";
	        $tab2  = " tes_moneda";
	        $whe2  = " nombre_moneda = 'DOLAR'";
	        $id2   = " id_moneda";
	        $rsConsulta2 = $cuentasPagar->getCondiciones($col2, $tab2, $whe2, $id2);
	        
	        //para traer cuentas por pagar y proveedores
	        $col3  = "cc.id_lote, cc.nombre_lote, aa.id_cuentas_pagar, aa.numero_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.fecha_cuentas_pagar,";
	        $col3  .="aa.compras_cuentas_pagar, aa.total_cuentas_pagar, bb.id_proveedores, bb.nombre_proveedores, 
                bb.identificacion_proveedores, aa.saldo_cuenta_cuentas_pagar";
	        $tab3  = "tes_cuentas_pagar aa
    	        INNER JOIN proveedores bb ON bb.id_proveedores = aa.id_proveedor
    	        INNER JOIN tes_lote cc ON cc.id_lote = aa.id_lote";
	        $whe3  = " aa.id_cuentas_pagar = $_id_cuentas_pagar ";
	        $id3   = " cc.id_lote";
	        $rsConsulta3 = $cuentasPagar->getCondiciones($col3, $tab3, $whe3, $id3);	   
	        
	       
	        $this->view_tesoreria("GenerarCheque",array(
	            "rsCuentasPagar"=>$rsConsulta3,"rsConsecutivos"=>$rsConsulta1,"rsMoneda"=>$rsConsulta2
	        ));
	    }
	   
	  
	    
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
	
	/***
	 * @desc function para dibujar la chequera de la entidad por medio de request ajax
	 * @param none
	 */
	public function CargaChequera(){
	    
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $col1  = " id_bancos, nombre_bancos, lpad(index_bancos_chequera::text,espacio_bancos_chequera,'0') consecutivo_cheque , abrev_bancos_chequera";
	    $tab1  = " tes_bancos ";
	    $whe1  = " local_bancos = 't' and pago_bancos_chequera = 't' ";
	    $id1   = " nombre_bancos ";
	    $rsConsulta1   = $pagos->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    try {
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        if( !empty($rsConsulta1) ){	            
	            $resp['data'] = $rsConsulta1;	            
	        }else{
	            $resp['data'] = null;	
	        }
	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	    
	}
	
	/***
	 * @desc function para dibujar la chequera de la entidad por medio de request ajax
	 * @param none
	 */
	public function CargaNumChequera(){
	    
	    $pagos = new PagosModel();
	    $resp  = null;
	    
	    $id_bancos = $_POST['id_bancos'];
	    
	    $col1  = " id_bancos, nombre_bancos, lpad(index_bancos_chequera::text,espacio_bancos_chequera,'0') consecutivo_cheque , abrev_bancos_chequera";
	    $tab1  = " tes_bancos ";
	    $whe1  = " id_bancos = $id_bancos ";
	    $id1   = " nombre_bancos ";
	    $rsConsulta1   = $pagos->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    try {
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }
	        
	        if( !empty($rsConsulta1) ){
	            $resp['numchequera'] = $rsConsulta1[0]->consecutivo_cheque;
	        }else{
	            $resp['numchequera'] = null;
	        }
	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; //buscar guardar buffer y guaradr en variable
	        $resp['estatus'] = "ERROR";
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	    
	}
	
	/***
	 * return: json
	 * title: editBancos
	 * fcha: 2019-04-22
	 */
	public function editEntidad(){
	    
	    session_start();
	    $entidad = new CoreEntidadPatronalModel();
	    $nombre_controladores = "CoreEntidadPatronal";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    	     
	    if (!empty($resultPer))
	    {
	        
	        
	        if(isset($_POST["id_entidad_patronal"])){
	            
	            $_id_entidad_patronal = (int)$_POST["id_entidad_patronal"];
	            
	            $query = "SELECT * FROM core_entidad_patronal WHERE id_entidad_patronal = $_id_entidad_patronal";

	            $resultado  = $entidad->enviaquery($query);	            
	           
	            echo json_encode(array('data'=>$resultado));	            
	            
	        }
	       	        
	        
	    }
	    else
	    {
	        echo "Usuario no tiene permisos-Editar";
	    }
	    
	}
	
	public function distribucionCheque(){
	    
	    /* se realiza la distribucion de pago
	     * se consulta diario enlazado
	     * se realiza insercion
	     * se realiza suma de valores
	     */
	    session_start();	    
	    $_id_cuentas_pagar     = $_POST['id_cuentas_pagar'];
	    $_id_bancos            = $_POST['id_bancos'];
	    $_referencia_cheque    = $_POST['referencia_cheque'];
	    
	    //dc 2020/07/22
	    $valor_pago_parcial    = $_POST['valor_pago_parcial'];
	    $check_pago_parcial    = $_POST['check_pago_parcial'];
	        
	    
	    $moduloId = "6";	    
	    $respuesta = array();
	    
	    $CuentasPagar  = new CuentasPagarModel();
	    $PlanCuentas   = new PlanCuentasModel(); 
	    
	    $columnas1 = " id_cuentas_pagar, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, 
            total_cuentas_pagar, saldo_cuenta_cuentas_pagar";
	    $tabla1    = " tes_cuentas_pagar";
	    $where1    = " id_cuentas_pagar = $_id_cuentas_pagar";
	    $id1       = " id_cuentas_pagar ";
	    	  
	    $rsCuentasPagar = $CuentasPagar->getCondiciones($columnas1,$tabla1,$where1,$id1);
	    /*datos de cuentas por pagar*/
	    $_total_cuentas_pagar = $rsCuentasPagar[0]->saldo_cuenta_cuentas_pagar;    
	    
	    // dc 2020/07/22
	    $valor_aplicado_cheque = $_total_cuentas_pagar;
	    if( $check_pago_parcial == "1" ){
	        $valor_aplicado_cheque = $valor_pago_parcial;
	    }
	   
	    /*buscar distribucion de comprobante*/
	    //para buscar el diario
	    $colParametrizacion = "id_parametrizacion_cuentas, tabla_parametrizacion_cuentas, id_plan_cuentas_haber, (
                                select c.id_plan_cuentas from tes_cuentas_pagar p
                                inner join tes_distribucion_cuentas_pagar c on p.id_lote =c.id_lote
                                where p.id_cuentas_pagar=$_id_cuentas_pagar and c.tipo_distribucion_cuentas_pagar = 'PAGO'
                                ) as id_plan_cuentas_debe";
	    $tabParametrizacion = "core_parametrizacion_cuentas";
	    $WheParametrizacion = "id_modulos = $moduloId AND tabla_parametrizacion_cuentas = 'tes_bancos' 
        AND operacion_parametrizacion_cuentas = 'CHEQUE'
        AND id_principal_parametrizacion_cuentas = $_id_bancos";
	    $idParametrizacion = "tabla_parametrizacion_cuentas";
	    
	    //echo "SELECT $colParametrizacion  FROM $tabParametrizacion WHERE $WheParametrizacion ORDER BY $idParametrizacion";
	    //die();
	    
	    $rsParametrizacion = $CuentasPagar -> getCondiciones($colParametrizacion,$tabParametrizacion,$WheParametrizacion,$idParametrizacion);
	    $_lista_id_plan_cuentas = "";
	    if(!empty($rsParametrizacion)){	        
	        $_lista_id_plan_cuentas = "".$rsParametrizacion[0]->id_plan_cuentas_debe.",".$rsParametrizacion[0]->id_plan_cuentas_haber."";
	    }else{
	        echo "<message>Diario Pago no identificado<message>"; exit();
	    }
	    
	    $htmlTabla = "";
	    $nombreTabla = "tbl_diario_pago_cheque";
	    if(!empty($_lista_id_plan_cuentas)){
	        $where2 = " id_plan_cuentas in ( $_lista_id_plan_cuentas )";
	        $rsDiarioPago = $PlanCuentas->getBy($where2);
	        //dibujar tabla de distribucion cheque	        
	        $htmlTabla.= "<table id='$nombreTabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $htmlTabla.= "<thead>";
	        $htmlTabla.= "<tr>";
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Referencia</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Cuenta</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Descripcion</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Debito</th>';
	        $htmlTabla.='<th style="text-align: left;  font-size: 12px;">Credito</th>';
	        
	        $htmlTabla.='</tr>';
	        $htmlTabla.='</thead>';
	        $htmlTabla.='<tbody>';
	        $i=0;
	        $valor_credito = "0,00";
	        $valor_debito = "0,00";
	        
	        if(!empty($rsDiarioPago)){
	            
	            foreach ($rsDiarioPago as $res){
                	                        
                    $i++;
                    $htmlTabla.='<tr>';
                    $htmlTabla.='<td style="font-size: 11px;">'.$i.'</td>';
                    $htmlTabla.='<td style="font-size: 11px;"><span class="">'.$_referencia_cheque.'</span></td>';
                    $htmlTabla.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
                    $htmlTabla.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
                    if( $i == 1 ){
                        $valor_debito = number_format((float)$valor_aplicado_cheque, 2, ',', '.');
                        $valor_credito = "0,00";
                    }
                    if( $i == 2 ){
                        $valor_credito = number_format((float)$valor_aplicado_cheque, 2, ',', '.');
                        $valor_debito = "0,00";
                    }
                    $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_debito.'</td>';
                    $htmlTabla.='<td style="font-size: 11px; text-align:right">'.$valor_credito.'</td>';
                    
                    $htmlTabla.='</tr>';
	               }
              
            }
            
            $htmlTabla.='</tbody>';
            $htmlTabla.='</table>';
	        
	    }
	    
	    $error = pg_last_error();
	    if(!empty($error)) {echo "<message>Error en generacion comprobante<message>"; die();}
	    
	    $respuesta['tabla_datos'] = (!empty($htmlTabla)) ? $htmlTabla : null;
	    $respuesta['nombre_tabla'] = $nombreTabla;
	    
	    echo json_encode($respuesta);	    
	    
	}
		
	public function generaCheque(){
	    
	    session_start();
	    $CuentasPagar = new CuentasPagarModel();
	   
	    try{
	        
	        $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
	        $_id_usuarios = $_SESSION['id_usuarios'];
	        $_id_proveedores = 0;
	        $_numero_cheque = trim($_POST['numero_cheque']);
	        $_fecha_cheque = $_POST['fecha_cheque'];
	        $_observaciones = "PAGO CON CHEQUE";
	        $_concepto_cheque = $_POST['comentario_cheque'];
	        $_id_bancos = $_POST['id_bancos'];	
	        $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	        
	        //dc 2020/07/22
	        $check_pago_parcial   = $_POST['check_pago_parcial'];
	        $valor_pago_parcial   = $_POST['valor_pago_parcial'];
	        
	        $valor_aplicado_cheque = 0;
	        	        	        
	        $respuesta = array();
	        
	        $_array_detalle_comrobantes = array();
	        
	        //empieza el begin
	        $CuentasPagar->beginTran();
	       
	        $queryCxP = "SELECT id_cuentas_pagar, id_proveedor, id_lote, fecha_cuentas_pagar, compras_cuentas_pagar, impuesto_cuentas_pagar, total_cuentas_pagar, saldo_cuenta_cuentas_pagar
                FROM tes_cuentas_pagar
                WHERE id_cuentas_pagar = $_id_cuentas_pagar";
	        
	        $rsCuentasPagar = $CuentasPagar->enviaquery($queryCxP);
	         
	        $_total_cuentas_pagar = $rsCuentasPagar[0]->saldo_cuenta_cuentas_pagar;
	        $_id_proveedores = $rsCuentasPagar[0]->id_proveedor;
	        
	        //dc 2020/07/22
	        $valor_aplicado_cheque  = $_total_cuentas_pagar;
	        if( $check_pago_parcial == "1" ){
	            $valor_aplicado_cheque  = $valor_pago_parcial;
	        }
	        
	        $_total_en_letras = $CuentasPagar->numtoletras($valor_aplicado_cheque);
	        
	        $queryFormaPago = "SELECT * FROM forma_pago WHERE nombre_forma_pago = 'CHEQUE' LIMIT 1";
	        $rsFormaPago = $CuentasPagar->enviaquery($queryFormaPago);	        
	        $_id_forma_pago = $rsFormaPago[0]->id_forma_pago;
	        $_referencia_ccomprobantes = "CHEQUE";
	        
            //aqui genera el comprobante
            $funcion = "ins_ccomprobantes_cheques";
            $parametros = "'$valor_aplicado_cheque',
                           '$_concepto_cheque', 
                           '$_id_usuarios', 
                           '$_total_en_letras', 
                           '$_fecha_cheque', 
                           '$_observaciones',
                           '$_id_forma_pago',
                           '$_referencia_ccomprobantes',
                           '$_numero_cheque',
                           '$_id_proveedores',
                           '$_id_bancos'";
            $consultaPG = $CuentasPagar->getconsultaPG($funcion, $parametros);	            
            $ResultComprobante = $CuentasPagar->llamarconsultaPG($consultaPG);
            $error = "";
            $error = pg_last_error();
            if(!empty($error)){
                throw new Exception("Error ingresando Comprobantes");
            }
            if((int)$ResultComprobante[0] == -1 ){
                throw new Exception(" --REVISAR NUMERO DE CHEQUE-- ");
            }
            
            //obtengo el comprobante generado
            $_id_ccomprobantes = (int)$ResultComprobante[0];
            $moduloId = "6";
            //para buscar el diario
            $colParametrizacion = "id_parametrizacion_cuentas, tabla_parametrizacion_cuentas, id_plan_cuentas_haber, (
                                select c.id_plan_cuentas from tes_cuentas_pagar p
                                inner join tes_distribucion_cuentas_pagar c on p.id_lote =c.id_lote
                                where p.id_cuentas_pagar=$_id_cuentas_pagar and c.tipo_distribucion_cuentas_pagar = 'PAGO'
                                ) as id_plan_cuentas_debe";
            $tabParametrizacion = "core_parametrizacion_cuentas";
            $WheParametrizacion = "id_modulos = $moduloId AND tabla_parametrizacion_cuentas = 'tes_bancos'
        AND operacion_parametrizacion_cuentas = 'CHEQUE'
        AND id_principal_parametrizacion_cuentas = $_id_bancos";
            $idParametrizacion = "tabla_parametrizacion_cuentas";
            
            $rsParametrizacion = $CuentasPagar -> getCondiciones($colParametrizacion,$tabParametrizacion,$WheParametrizacion,$idParametrizacion);
            
            //para generar el array de detalle solo hay dos cuentas
            $fila = array();             
            foreach ($rsParametrizacion as $res){
                $fila = array('id_plan_cuentas'=>$res->id_plan_cuentas_debe,'valor_debe'=>$valor_aplicado_cheque,'valor_haber'=>0.00);
                array_push( $_array_detalle_comrobantes, $fila);
                
            }
            foreach ($rsParametrizacion as $res){
                $fila = array('id_plan_cuentas'=>$res->id_plan_cuentas_haber,'valor_debe'=>0.00,'valor_haber'=>$valor_aplicado_cheque);
                array_push( $_array_detalle_comrobantes, $fila);
            }
            
            //para ingresar el detalle. se realiza con un ciclo
            $_id_plan_cuentas = 0;
            $_debe_comprobante = 0;
            $_haber_comprobante = 0;
            $funcionDet ="ins_dcomprobantes_procesos";
	            
            foreach ($_array_detalle_comrobantes as $res){
	                $_id_plan_cuentas = $res['id_plan_cuentas'];
	                $_debe_comprobante = $res['valor_debe'];
	                $_haber_comprobante = $res['valor_haber'];
	                $paramDet = "'$_id_ccomprobantes','$_id_plan_cuentas','$_concepto_cheque','$_debe_comprobante','$_haber_comprobante'";
	                //query diario
	                $queryDetalle = $CuentasPagar->getconsultaPG($funcionDet, $paramDet);
	                //insert diario
	                $error = "";
	                $ResultDet = $CuentasPagar->llamarconsultaPG($queryDetalle);
	                $error = pg_last_error();
	                if( !empty($error) || (int)$ResultDet[0] <= 0){
	                    throw new Exception("Error ingresando detalle");
	                }
	         }
	            
	            
            //aqui para la mayorizacion
            $funcionMayoriza = "con_ins_mayoriza";
            $parametrosMayoriza = "'$_id_ccomprobantes','$_fecha_cheque'";
            $consultaPG = $CuentasPagar->getconsultaPG($funcionMayoriza, $parametrosMayoriza);
            $ResultMayoriza = $CuentasPagar->llamarconsultaPG($consultaPG);
            $error = "";
            $error = pg_last_error();
            if( !empty($error) || (int)$ResultMayoriza[0] <= 0){
                throw new Exception("Error Mayorizacion");
            }
	            
            //para el cuadre plan_cuentas
            $funcionMayor = "fn_cuadra_plan_cuentas";
            foreach ($_array_detalle_comrobantes as $res){
                $_id_plan_cuentas = $res['id_plan_cuentas'];
                $paramMayorizar = "'$_id_plan_cuentas'";
                //query diario
                $queryDetalle = $CuentasPagar->getconsultaPG($funcionMayor, $paramMayorizar);
                //realizar mayorizacion
                $error = "";
                $ResultMayorizar = $CuentasPagar->llamarconsultaPG($queryDetalle);
                $error = pg_last_error();
                if( !empty($error) || (int)$ResultMayorizar[0] <= 0){
                    throw new Exception("Error Cuadrando comprobantes");
                }
            }            
            
            //aqui ingresar el pago
            //id_creditos ya esta referenciada con la cuenta por pagar
            $_id_creditos = 0;
            $funcionPago = "ins_tes_pagos_cheque";
            $parametrosPago = "'$_id_cuentas_pagar',
            	        '$_id_creditos',
            	        '$_id_proveedores',
            	        '$_id_ccomprobantes',
            	        '$_id_forma_pago',
            	        '$_fecha_cheque',
            	        'CHEQUE',
            	        '$valor_aplicado_cheque',
            	        '$_id_bancos',
            	        '$_usuario_usuarios',
                        '$_numero_cheque'"; 
           
            
            $consultaPago = $CuentasPagar->getconsultaPG($funcionPago, $parametrosPago);
            $ResultadoPago = $CuentasPagar->llamarconsultaPG($consultaPago);
            
            $error = "";
            $error = pg_last_error();
            if(!empty($error) || !is_int((int)$ResultadoPago[0])) throw  new Exception(" Error insercion Pago");
            
            $id_pagos = $ResultadoPago[0]; //se captura el id del pago generado
	        
            //dc 2020/07/22            
            //buscar y actualizar estado de cuentas por pagar
            $saldo_cuentas_pagar = $_total_cuentas_pagar - $valor_aplicado_cheque;
            if( empty($saldo_cuentas_pagar) ){
                $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'APLICADO'";
            }else{
                $queryEstado = "SELECT id_estado FROM estado WHERE tabla_estado='tes_cuentas_pagar' AND nombre_estado = 'PARCIAL'";
            }
            
            /*actualizacion de Cuenta por pagar*/
            //buscar estado de cuentas por pagar
            $rsEstado = $CuentasPagar -> enviaquery($queryEstado);
            $_id_estado = $rsEstado[0]->id_estado;
            $rsActualizacion = $CuentasPagar->ActualizarBy("id_estado = $_id_estado, saldo_cuenta_cuentas_pagar = $saldo_cuentas_pagar", "tes_cuentas_pagar", "id_cuentas_pagar = $_id_cuentas_pagar");            
            
            $respuesta['comprobante']['mensaje']="CHEQUE REGISTRADO CORRECTAMENTE";
            $respuesta['comprobante']['valor'] = 1;
            $respuesta['comprobante']['id_comprobante'] = $_id_ccomprobantes;
            $respuesta['cuentaspagar']['id_cuentas_pagar'] = $_id_cuentas_pagar; 
            $respuesta['pago']['id_pagos'] = $id_pagos;
            
            /*Actualizar Consecutivo Pago*/
            $colvalPago = "numero_consecutivos = lpad((valor_consecutivos+1)::text,espacio_consecutivos,'0'),
                        valor_consecutivos = valor_consecutivos+1";
            $tabPago = "consecutivos";
            $whePago = "id_entidades = 1 and nombre_consecutivos = 'PAGOS'";
            $UpdatePago = $CuentasPagar->ActualizarBy($colvalPago, $tabPago, $whePago); 
            
            
            /*para enviar al celular -- buscar en tabla credito*/
            $colCredito = " 1";
            $tabCredito = " tes_cuentas_pagar aa
                        INNER JOIN ccomprobantes bb ON aa.id_ccomprobantes = bb.id_ccomprobantes
                        INNER JOIN core_creditos cc ON cc.id_ccomprobantes = bb.id_ccomprobantes AND cc.id_estatus = 1";
            $wheCredito = "aa.id_cuentas_pagar = $_id_cuentas_pagar";
            $idCredito  = "aa.id_cuentas_pagar";
            $rsCredito = $CuentasPagar->getCondiciones($colCredito, $tabCredito, $wheCredito, $idCredito);
            
            if(!empty($rsCredito)){
                //si la cuenta por pagar viene de un credito
                /*para enviara a celular*/
                $_codigo_mensajes = "";
                $_id_mensaje_mensajes = "22451";
                /*buscar datos de beneficiario*/
                $colBeneficiario = " aa.id_participes, aa.cedula_participes, aa.nombre_participes, aa.apellido_participes, aa.celular_participes";
                $tabBeneficiario = " core_participes aa
                                INNER JOIN proveedores bb
                                ON aa.cedula_participes = bb.identificacion_proveedores
                                AND aa.id_estatus = 1";
                $wheBeneficiario = "bb.id_proveedores = $_id_proveedores";
                $idBeneficiario  = "aa.cedula_participes";
                $rsBeneficiario = $CuentasPagar->getCondiciones($colBeneficiario, $tabBeneficiario, $wheBeneficiario, $idBeneficiario);
                if(!empty($rsBeneficiario)){
                    $_nombre_participes = $rsBeneficiario[0]->nombre_participes;
                    $_apellidos_participes = $rsBeneficiario[0]->apellido_participes;
                    $_celular_mensaje = "0987474892";//$rsBeneficiario[0]->celular_participes; //cambiar para produccion
                    $_nombres_mensajes = $_nombre_participes." ".$_apellidos_participes;
                    $this->comsumir_mensaje_plus($_celular_mensaje, $_nombres_mensajes, $_codigo_mensajes, $_id_mensaje_mensajes);
                }
            }
           
            $CuentasPagar->endTran("COMMIT");
                      
            echo json_encode($respuesta);
	            
	       
	    }catch (Exception $ex){
	        
	        $CuentasPagar->endTran();
	        echo "<message>Error generando cheque ".$ex->getMessage()."<message>";
	       
	    }
	    
	}
	
	public function generaReporteCheque(){
	    
	    
	    session_start();
	    
	    try{	       
	        
	        $id_pagos  = $_POST['id_pagos'];	        
	        
	        if( !empty( error_get_last() ) )
	            throw new Exception("Datos de inicio no recibidos");
	        
	        if( $id_pagos == "" )
	            throw new Exception("Parametros No Recibidos");
	        
	    }catch (Exception $ex)
	    {	        
	        echo $ex->getMessage();
	        die();
	        
	    }
	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    	    
	    $CuentasPagar = new CuentasPagarModel();
	    
	    if( !empty( $cedula_usuarios ) )
	    {
	        //el usuario se encuentra logueado 
	        
	        /* DATOS CUENTAS PAGAR - PAGOS */
	        $colCuentas = "aa.id_cuentas_pagar, aa.descripcion_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar,
	            cc.nombre_proveedores, bb.fecha_pagos, bb.valor_pagos, bb.id_ccomprobantes";
	        $tabCuentas = "tes_cuentas_pagar aa
    	        INNER JOIN tes_pagos bb ON bb.id_cuentas_pagar = aa.id_cuentas_pagar
    	        INNER JOIN proveedores cc ON cc.id_proveedores = aa.id_proveedor";
	        $wheCuentas = " bb.id_pagos = $id_pagos";
	        $idCuentas = " bb.id_pagos";
	        $rscuentasPagar = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	        	        	        
	        //traer datos de cheque
	        $portadorCheque    = $rscuentasPagar[0]->nombre_proveedores;
	        $valorLetrasCheque = $CuentasPagar->numtoletras($rscuentasPagar[0]->valor_pagos);
	        $valorCheque       = number_format((float)$rscuentasPagar[0]->valor_pagos, 2, '.', ',');
	        $proveedor         = $rscuentasPagar[0]->nombre_proveedores;
	        $id_ccomprobantes  = $rscuentasPagar[0]->id_ccomprobantes;
	        
	        //trabajar con datos de fecha 
	        $fechaRegistro     = $rscuentasPagar[0]->fecha_pagos;	        
	        $fecha = strtotime( $fechaRegistro);
	        $fechaCheque = date('Y',$fecha).' '.strtoupper($meses[date('n',$fecha)-1]).' '.date('d',$fecha);
	        $fechaFooter = date('d',$fecha).' DE '.strtoupper($meses[date('n',$fecha)-1]).' DEL '.date('Y',$fecha);
	        
	        
	        /* DATOS COMPROBANTE*/
	        $colComprobante = "id_ccomprobantes, valor_ccomprobantes, concepto_ccomprobantes, numero_cheque_ccomprobantes,
                                transaccion_ccomprobantes";
	        $tabComprobante = "public.ccomprobantes";
	        $wheComprobante = " id_ccomprobantes = $id_ccomprobantes ";
	        $idComprobante = " id_ccomprobantes";
	        $rsComprobante = $CuentasPagar->getCondiciones($colComprobante, $tabComprobante, $wheComprobante, $idComprobante);
	       
	        //traer detalles cheque
	        $conceptoCheque = $rsComprobante[0]->concepto_ccomprobantes;
	        $numeroCheque = $rsComprobante[0]->numero_cheque_ccomprobantes;
	        $transaccion = $rsComprobante[0]->transaccion_ccomprobantes;
	        
	        
	        /* DATOS CONTABLES */
	        $colCuentas = " dc.id_dcomprobantes, dc.id_plan_cuentas, pc.codigo_plan_cuentas, pc.nombre_plan_cuentas, 
                dc.debe_dcomprobantes, dc.haber_dcomprobantes, pc.nivel_plan_cuentas";
	        $tabCuentas = " dcomprobantes dc
    	        INNER JOIN plan_cuentas pc ON pc.id_plan_cuentas = dc.id_plan_cuentas";
	        $wheCuentas = " dc.id_ccomprobantes = $id_ccomprobantes ";
	        $idCuentas = " id_dcomprobantes";
	        $rsCuentas = $CuentasPagar->getCondiciones($colCuentas, $tabCuentas, $wheCuentas, $idCuentas);
	      
	        //traer detalles contables
	        $htmlTabla = '<table style="width: 100%; margin-top:10px;" border=hidden cellspacing=0>';	      
	        if(!empty($rsCuentas)){
	            $nivelCuenta=0;
	            $codigoCuenta='';
	            $codigoPadre='';
	            $arraycodigo = array();
	            foreach ($rsCuentas as $res){
	                //buscar cuenta nivel mayor
	                $codigoCuenta= $res->codigo_plan_cuentas;
	                $codigoCuenta= trim($codigoCuenta,'.');
	                $nivelCuenta = $res->nivel_plan_cuentas;
	                $arraycodigo = explode('.', $codigoCuenta);
	                for($i=0; $i < (count($arraycodigo)-1); $i++){	                    
                        $codigoPadre.=$arraycodigo[$i].'.';	                    
	                }
	                $nivelCuenta = $nivelCuenta-1;
	                $codigoPadre = trim($codigoPadre,'.');
	                $queryCuentaPadre = "SELECT codigo_plan_cuentas, nombre_plan_cuentas FROM public.plan_cuentas 
                            WHERE codigo_plan_cuentas LIKE '$codigoPadre%' AND nivel_plan_cuentas = '$nivelCuenta'";
	                $rsCuentaPadre = $CuentasPagar -> enviaquery($queryCuentaPadre);
	              
	                if(!empty($rsCuentaPadre)){
	                    $htmlTabla.="<tr>";
	                    $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$rsCuentaPadre[0]->codigo_plan_cuentas.'</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$rsCuentaPadre[0]->nombre_plan_cuentas.'</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	                    $htmlTabla.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	                    $htmlTabla.="</tr>";
	                }
	               
    	            $htmlTabla.="<tr>";
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$res->codigo_plan_cuentas.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; ">'.$res->nombre_plan_cuentas.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">'.number_format( $res->debe_dcomprobantes, 2, ".","").'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">'.number_format( $res->haber_dcomprobantes, 2, ".","").'</td>';
    	            $htmlTabla.="</tr>";
    	            $htmlTabla.="<tr>";
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px;">'.$conceptoCheque.'</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
    	            $htmlTabla.="</tr>";
    	            
    	            $codigoPadre='';
	           }
	        }
	        $htmlTabla .= '</table>';
	        
	        $ciudad="QUITO";
	       
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:250px;font-size: 13px;">'.$portadorCheque.'</td>';
	        $html.='<td style="width:10px;font-size: 13px;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        $html.= "<tr>";
	        $html.='<td style="width:130px;">&nbsp;</td>';
	        $html.='<td style="width:200px;font-size: 13px;">'.$valorLetrasCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.='<td style="width:50px;font-size: 13px;">'.$ciudad.', '.$fechaCheque.'</td>';
	        //$html.='<td style="width:200px;font-size: 13px;">'.$fechaCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$conceptoCheque.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">'.$transaccion.'</td>';
	        $html.='<td style="width:110px;font-size: 11px;"><b>No.</b></td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$numeroCheque.'</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:70px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;">'.$proveedor.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:110px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        $html.='<table>';
	        $html.="<tr>";
	        $html.='<td style="width:50px;">&nbsp;</td>';
	        $html.='<td style="width:300px;font-size: 11px;"> '.$conceptoCheque.'; &nbsp;'.$proveedor.'</td>';
	        $html.='<td style="width:80px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:70px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:10px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.='</table>';
	        
	        //para tabla de detalle de cuentas
	        $html.= $htmlTabla;
	        
	        
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='</table>';
	        $html.="<table style='width: 100%; margin-top:10px;' border=hidden cellspacing=0>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Fecha:&nbsp; &nbsp;</b>'.$fechaFooter.'</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px;">&nbsp;</td>';
	        $html.="</tr>";
	        $html.="<tr>";
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">&nbsp;</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;"><b>Total General:</b></td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$valorCheque.'</td>';
	        $html.='<td style="width:100px;font-size: 11px; text-align: center;">'.$valorCheque.'</td>';
	        $html.="</tr>";
	        
	        $html.="</table>";
	        
	        
	        
	    }
	    	    	    
	    $this->report("Cheque",array( "resultSet"=>$html));
	    die();
	    
	}
	
	public function reporteCheques(){
	    
	    $entidad = new CoreEntidadPatronalModel();
	    
	    session_start();
	    
	    if(empty( $_SESSION)){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        return;
	    }
	    
	    $nombre_controladores = "GenerarCheque";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso Empleo"
	            
	        ));
	        exit();
	    }
	    
	    
	    $this->view_tesoreria("Cheques",array(
	    ));
	    
	}
	
	public function listarCheques(){
	    $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
	    if(!isset($_POST['peticion'])){
	        echo 'sin conexion';
	        return;
	    }
	    
	    $page = (isset($_REQUEST['page']))?isset($_REQUEST['page']):1;
	    
	    $cuentasPagar = new CuentasPagarModel();
	    
	    $columnas = "c.id_ccomprobantes, c.numero_ccomprobantes, tc.nombre_tipo_comprobantes, c.valor_ccomprobantes,
            		c.concepto_ccomprobantes, c.referencia_doc_ccomprobantes, c.numero_cheque_ccomprobantes, c.id_proveedores,
            		p.nombre_proveedores";
	    
	    $tablas =" ccomprobantes c
                INNER JOIN tipo_comprobantes tc
                ON c.id_tipo_comprobantes = tc.id_tipo_comprobantes
                INNER JOIN proveedores p
                ON p.id_proveedores = c.id_proveedores";
	    
	    $where = " 1 = 1
                AND c.tipo_cuenta_ccomprobantes = 'cxp'
                AND c.transaccion_ccomprobantes = 'CHEQUE'";
	    
	    //para los parametros de where
	    if(!empty($busqueda)){
	        
	        $where .= "AND ( c.numero_ccomprobantes LIKE '$busqueda' OR c.numero_cheque_ccomprobantes like '$busqueda%' )";
	    }
	    
	    $id = "c.id_ccomprobantes";
	    
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
	    
	    if( $cantidad > 0 ){
	       
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<section style="height:180px; overflow-y:scroll;">';
	        $html.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">COMPROBANTE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">TIPO COMP.</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">VALOR</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CONCEPTO</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">CHEQUE</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">PROVEEDOR</th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($resultSet as $res)
	        {
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->numero_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_comprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->valor_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->concepto_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->numero_cheque_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
	            $html.='</tr>';
	            
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        $html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"buscaChequesGenerados").'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b> No existen cheques generados. </b>';
	        $html.='</div>';
	        $html.='</div>';
	    }
	    
	    //array de datos
	    $respuesta = array();
	    $respuesta['tabladatos'] = $html;
	    $respuesta['valores'] = array('cantidad'=>$cantidad);
	    echo json_encode($respuesta);
	}
	
	public function comsumir_mensaje_plus($celular, $nombres, $codigo, $id_mensaje){
	    
	    /*si mensaje es para transferencia el id_mensaje = 22443*/
	    //$nombres = poner el nombre unidos por guion bajo
	    //$codigo = # cuenta y banco unidos por guion bajo
	    
	    /*si mensaje es para cheque el id_mensaje = 22451*/	    
	    //--$nombres = poner el nombre unidos por guion bajo
	    //--$codigo = enviar vacio;*/
	    
	    
	    $cadena_recortada ="";
	    $nombres_final="";
	    $mensaje_retorna="";
	    
	    // quito el primero 0
	    $celular_final=ltrim($celular, "0");
	    
	    // relleno espacios en blanco por _
	    $nombres_final= str_replace(' ','_',$nombres);
	    // $nombres_final= str_replace('Ñ','N',$nombres);
	    // genero codigo de verificacion
	    
	    
	    $variables="";
	    $variables.="<pedido>";
	    
	    $variables.="<metodo>SMSEnvio</metodo>";
	    $variables.="<id_cbm>767</id_cbm>";
	    $variables.="<token>yPoJWsNjcThx2o0I</token>";
	    $variables.="<id_transaccion>2002</id_transaccion>";
	    $variables.="<telefono>$celular_final</telefono>";
	    
	    // poner el id_mensaje parametrizado en el sistema
	    
	    $variables.="<id_mensaje>$id_mensaje</id_mensaje>";
	    
	    // poner 1 si va con variables
	    // poner 0 si va sin variables y sin la etiquetas datos
	    $variables.="<dt_variable>1</dt_variable>";
	    $variables.="<datos>";
	    
	    
	    /// el numero de valores va dependiendo del mensaje si usa 1 o 2 variables.
	    $variables.="<valor>$nombres_final</valor>";
	    if (!empty($codigo)){
	        $variables.="<valor>$codigo</valor>";
	    }
	    $variables.="</datos>";
	    $variables.="</pedido>";
	    
	    
	    $SMSPlusUrl = "https://smsplus.net.ec/smsplus/ws/mensajeria.php?xml={$variables}";
	    $ResponseData = file_get_contents($SMSPlusUrl);
	    
	    $xml = simplexml_load_string($ResponseData);
	    
	}
	
}
?>