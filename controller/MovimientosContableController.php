<?php

class MovimientosContableController extends ControladorBase{
    
	public function __construct() {
		parent::__construct();
	}

	public function index(){
	    
	    session_start();
	    $cuentas_pagar = new CuentasPagarModel();
	    
	    if( !isset($_SESSION['id_usuarios']) ){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    $_id_rol = $_SESSION['id_rol'];
	    
	    $nombre_controladores = "ReporteMovimientos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $cuentas_pagar->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if( empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso a Movimientos Productos Cabeza"	            
	        ));
	        
	        exit();	
	    }
	    
	    $this->view_Contable('MovimientosContable',array());
	}
	
	public function generaReporte(){
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : null);
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    $_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //variable viene de vista
	    $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	    
	    $query = "SELECT pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, 
                    CASE WHEN ini.saldo_ini_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas ELSE  ini.saldo_ini_mayor END ,
                    CASE WHEN mov.movimiento ISNULL THEN
                        0.00 ELSE  mov.movimiento end,
                    CASE WHEN fin.saldo_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas else  fin.saldo_mayor end 
                FROM plan_cuentas pc
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_ini_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, MIN(creado) as fecha
                		FROM con_mayor
                		WHERE TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'		
                		GROUP BY id_plan_cuentas
                		) AS aa
                	ON aa.id_plan_cuentas = cm.id_plan_cuentas
                	AND aa.fecha = cm.creado
                	) AS ini
                ON ini.id_plan_cuentas = pc.id_plan_cuentas 
                LEFT JOIN (
                	SELECT id_plan_cuentas, (SUM(debe_mayor) - SUM(haber_mayor)) AS movimiento
                	FROM con_mayor
                	WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                	GROUP BY id_plan_cuentas
                	) AS mov
                ON mov.id_plan_cuentas = pc.id_plan_cuentas 
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, max(creado) AS fecha
                		FROM con_mayor
                		WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                		ON aa.id_plan_cuentas = cm.id_plan_cuentas
                		AND aa.fecha = cm.creado
                	) AS fin
                ON fin.id_plan_cuentas = pc.id_plan_cuentas 
                WHERE 1 = 1
                AND pc.nivel_plan_cuentas > 2
                ORDER BY pc.codigo_plan_cuentas";
	    
	    $rs_movimientos = $planCuentas->enviaquery($query);
	    
	    //variables para dibujar en vista
	    $datos_tabla = "";
	    $cuentaserror = array();
	    $color_error = "";
	    
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<tr  bgcolor="">';
	        $datos_tabla.='<th bgcolor="" width="10%"  style="text-align: center; ">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	       
	        
	       	        
	        foreach ( $rs_movimientos as $res){
	            
	            $color_error = "";
	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = $res->saldo_ini_mayor;
	            $_movimientos = $res->movimiento;
	            $_saldo_final = $res->saldo_mayor;
	           
	            
	            $array_fila_error = array();
	            
	            if( ($_saldo_inicial + $_movimientos ) !=  $_saldo_final ){
	                
	                $array_fila_error = array('id_plan_cuentas'=>$_id_plan_cuentas, 'codigo_plan_cuentas'=>$_codigo_plan_cuentas);
	                array_push($cuentaserror, $array_fila_error);
	                $color_error = "red";
	                
	                $datos_tabla.='<tr  bgcolor="">';
	                $datos_tabla.='<td bgcolor="" width="10%"  style="text-align: left; ">'.$_codigo_plan_cuentas.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left; ">'.$_nombre_plan_cuentas.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_saldo_inicial.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_movimientos.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; color:red; ">'.$_saldo_final.'</td>';
	                $datos_tabla.='</tr>';
	            }
	            
	            
	        }
	        
	        $datos_tabla.= "</table>";
	    }  
	    
	    //para pruebas
	    $cuentaserror = array();
	    
	    $pluralmensaje = "";
	    $cantidad_errores = count($cuentaserror);
	    $datos_error ="";
	    
	    if( $cantidad_errores > 0 ){
	        
	        if( $cantidad_errores == 1 ){
	            $pluralmensaje = "cuenta";
	        }else{
	            $pluralmensaje = "cuentas";
	        }
	        
	        $datos_error.='<div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
	        $datos_error.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $datos_error.='<h4>Aviso!!!</h4> <b>Actualmente se ha encontrado errores en el reporte de Movimientos !Revisar!</b>';
	        $datos_error.='<h5> Hay '.$cantidad_errores.' '.$pluralmensaje.' con valores erroneos </h5> ';
	        $datos_error.='</div>';
	        
	         
	    }
	    	   
	    //echo json_encode(array('error'=>utf8_encode($datos_error),'tabla_error'=>utf8_encode($datos_tabla)));
	    
	    //para dibujar cuando no hay error
	    if( empty($cuentaserror) ){
	        
	        $datos_error.='<div class="alert alert-success alert-dismissable" style="margin-top:40px;">';
	        $datos_error.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $datos_error.='<b>Proceda con la generacion del archivo Pdf </b>&nbsp; &nbsp; &nbsp;';
	        $datos_error.='<button type="button" id="genera_pdf" class="btn btn-sm btn-info"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i> Generar PDF</button>';
	        $datos_error.='&nbsp; &nbsp; &nbsp;';
	        $datos_error.='<button type="button" id="genera_excel" class="btn btn-sm btn-info"> <i class="fa fa-file-excel-o" aria-hidden="true" ></i> Generar EXCEL</button>';
	        $datos_error.='</div>';
	        
	        $datos_tabla = "";
	    }
	    
	    echo json_encode(array('error'=>$datos_error,'tabla_error'=>$datos_tabla));
	    
	}
	
	public function GENERAR_REPORTE(){
	    session_start();
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : '01');
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    
	    //aun no implementado
	    //$_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //variable viene de vista
	    $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	    
	    $query = "SELECT pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas,
                    CASE WHEN ini.saldo_ini_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas ELSE  ini.saldo_ini_mayor END ,
                    CASE WHEN mov.movimiento ISNULL THEN
                        0.00 ELSE  mov.movimiento end,
                    CASE WHEN fin.saldo_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas else  fin.saldo_mayor end
                FROM plan_cuentas pc
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_ini_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, MIN(creado) as fecha
                		FROM con_mayor
                		WHERE TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                	ON aa.id_plan_cuentas = cm.id_plan_cuentas
                	AND aa.fecha = cm.creado
                	) AS ini
                ON ini.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT id_plan_cuentas, (SUM(debe_mayor) - SUM(haber_mayor)) AS movimiento
                	FROM con_mayor
                	WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                	GROUP BY id_plan_cuentas
                	) AS mov
                ON mov.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, max(creado) AS fecha
                		FROM con_mayor
                		WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                		ON aa.id_plan_cuentas = cm.id_plan_cuentas
                		AND aa.fecha = cm.creado
                	) AS fin
                ON fin.id_plan_cuentas = pc.id_plan_cuentas
                WHERE 1 = 1
                AND pc.nivel_plan_cuentas > 2
                ORDER BY pc.codigo_plan_cuentas";
	    
	    $rs_movimientos = $planCuentas->enviaquery($query);
	    
	    //variables para dibujar en vista
	    $datos_tabla = "";
	    $tabla_detalle = array();
	    $cuentaserror = array();
	    $color_error = "";
	    
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla= "<table id='tabla_cuentas' style='border-collapse: collapse' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<tr  bgcolor="">';
	        $datos_tabla.='<th bgcolor="" width="10%"  style="text-align: center; ">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	        
	        
	        foreach ( $rs_movimientos as $res){
	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
	            $datos_tabla.='<tr  bgcolor="">';
	            $datos_tabla.='<td bgcolor="" width="10%" style="text-align: left; ">'.$_codigo_plan_cuentas.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left; ">'.$_nombre_plan_cuentas.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_saldo_inicial.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_movimientos.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_saldo_final.'</td>';
	            $datos_tabla.='</tr>';
	            
	            
	        }
	        
	        
	        $datos_tabla.= "</table>";
	   
	    }
	    
	    $tabla_detalle = $datos_tabla;
	    
	    $this->verReporte("MovimientosContables", array('datos_empresa'=>$datos_empresa,'tabla_detalle'=>$tabla_detalle));
	    
	  
	}
	public function generaReportepdf(){
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : '01');
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    
	    //aun no implementado
	    //$_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //variable viene de vista
	    $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	    
	    $query = "SELECT pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas,
                    CASE WHEN ini.saldo_ini_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas ELSE  ini.saldo_ini_mayor END ,
                    CASE WHEN mov.movimiento ISNULL THEN
                        0.00 ELSE  mov.movimiento end,
                    CASE WHEN fin.saldo_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas else  fin.saldo_mayor end
                FROM plan_cuentas pc
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_ini_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, MIN(creado) as fecha
                		FROM con_mayor
                		WHERE TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                	ON aa.id_plan_cuentas = cm.id_plan_cuentas
                	AND aa.fecha = cm.creado
                	) AS ini
                ON ini.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT id_plan_cuentas, (SUM(debe_mayor) - SUM(haber_mayor)) AS movimiento
                	FROM con_mayor
                	WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                	GROUP BY id_plan_cuentas
                	) AS mov
                ON mov.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, max(creado) AS fecha
                		FROM con_mayor
                		WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                		ON aa.id_plan_cuentas = cm.id_plan_cuentas
                		AND aa.fecha = cm.creado
                	) AS fin
                ON fin.id_plan_cuentas = pc.id_plan_cuentas
                WHERE 1 = 1
                AND pc.nivel_plan_cuentas > 2
                ORDER BY pc.codigo_plan_cuentas";
	    
	    $rs_movimientos = $planCuentas->enviaquery($query);
	    
	    //variables para dibujar en vista
	    $datos_tabla = "";
	    $cuentaserror = array();
	    $color_error = "";
	    
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla= "<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<tr  bgcolor="">';
	        $datos_tabla.='<th bgcolor="" width="10%"  style="text-align: center; ">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center; ">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	        
	        
	        foreach ( $rs_movimientos as $res){	            
	         
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
                $datos_tabla.='<tr  bgcolor="">';
                $datos_tabla.='<td bgcolor="" width="10%" style="text-align: left; ">'.$_codigo_plan_cuentas.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left; ">'.$_nombre_plan_cuentas.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_saldo_inicial.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_movimientos.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; ">'.$_saldo_final.'</td>';
                $datos_tabla.='</tr>';
	           
	            
	        }	        
	        
	        $datos_tabla.= "</table>";
	    }
	    
	    echo $datos_tabla;
	}
	
	
	
	public function generaReporteXls(){
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : null);
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    //no implementado
	    //$_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $nombreArchivo = "MovimientosContables_".$_anio_movimientos.$_mes_movimientos;
	    header('Content-Type: application/vnd.ms-excel');
	    //header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
	    header('Content-Disposition: attachment; filename='.$nombreArchivo.'.xls');
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //variable viene de vista
	    $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	    
	    $query = "SELECT pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas,
                    CASE WHEN ini.saldo_ini_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas ELSE  ini.saldo_ini_mayor END ,
                    CASE WHEN mov.movimiento ISNULL THEN
                        0.00 ELSE  mov.movimiento end,
                    CASE WHEN fin.saldo_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas else  fin.saldo_mayor end
                FROM plan_cuentas pc
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_ini_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, MIN(creado) as fecha
                		FROM con_mayor
                		WHERE TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                	ON aa.id_plan_cuentas = cm.id_plan_cuentas
                	AND aa.fecha = cm.creado
                	) AS ini
                ON ini.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT id_plan_cuentas, (SUM(debe_mayor) - SUM(haber_mayor)) AS movimiento
                	FROM con_mayor
                	WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                	GROUP BY id_plan_cuentas
                	) AS mov
                ON mov.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, max(creado) AS fecha
                		FROM con_mayor
                		WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                		ON aa.id_plan_cuentas = cm.id_plan_cuentas
                		AND aa.fecha = cm.creado
                	) AS fin
                ON fin.id_plan_cuentas = pc.id_plan_cuentas
                WHERE 1 = 1
                AND pc.nivel_plan_cuentas > 2
                ORDER BY pc.codigo_plan_cuentas";
	    
	    $rs_movimientos = $planCuentas->enviaquery($query);
	    
	    //variables para dibujar en vista
	    $datos_tabla = "";
	    
	    $arrayCabecera = array('CUENTA CONTABLE','DETALLE', 'SALDO INICIAL', 'MOV MES', 'SALDO FINAL');
	    $arraydetalle = array();
	    array_push($arraydetalle,$arrayCabecera);
	    
	    if( !empty($rs_movimientos) ){
	        
	        foreach ( $rs_movimientos as $res){
	            	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
	            array_push($arraydetalle, $_codigo_plan_cuentas, $_nombre_plan_cuentas, $_saldo_inicial,
	                $_movimientos, $_saldo_final);
	            
	            
	        }
	        
	        
	    }
	    
	    echo json_encode(array('cabecera'=>$arrayCabecera,'detalle'=>$arraydetalle));
	    
	    
	}
	
	
	public function generaReporteExcel(){
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : null);
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    //no implementado
	    //$_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $nombreArchivo = "MovimientosContables_".$_anio_movimientos.$_mes_movimientos;
	    header('Content-Type: application/vnd.ms-excel');
	    //header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
	    header('Content-Disposition: attachment; filename='.$nombreArchivo.'.xls');
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    //variable viene de vista
	    $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	    
	    $query = "SELECT pc.id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas,
                    CASE WHEN ini.saldo_ini_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas ELSE  ini.saldo_ini_mayor END ,
                    CASE WHEN mov.movimiento ISNULL THEN
                        0.00 ELSE  mov.movimiento end,
                    CASE WHEN fin.saldo_mayor ISNULL THEN
                        pc.saldo_fin_plan_cuentas else  fin.saldo_mayor end
                FROM plan_cuentas pc
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_ini_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, MIN(creado) as fecha
                		FROM con_mayor
                		WHERE TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                	ON aa.id_plan_cuentas = cm.id_plan_cuentas
                	AND aa.fecha = cm.creado
                	) AS ini
                ON ini.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT id_plan_cuentas, (SUM(debe_mayor) - SUM(haber_mayor)) AS movimiento
                	FROM con_mayor
                	WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                	GROUP BY id_plan_cuentas
                	) AS mov
                ON mov.id_plan_cuentas = pc.id_plan_cuentas
                LEFT JOIN (
                	SELECT cm.id_plan_cuentas, saldo_mayor
                	FROM con_mayor cm
                	INNER JOIN (
                		SELECT id_plan_cuentas, max(creado) AS fecha
                		FROM con_mayor
                		WHERE to_char(fecha_mayor,'YYYY-MM') = '$fecha'
                		GROUP BY id_plan_cuentas
                		) AS aa
                		ON aa.id_plan_cuentas = cm.id_plan_cuentas
                		AND aa.fecha = cm.creado
                	) AS fin
                ON fin.id_plan_cuentas = pc.id_plan_cuentas
                WHERE 1 = 1
                AND pc.nivel_plan_cuentas > 2
                ORDER BY pc.codigo_plan_cuentas";
	    
	    $rs_movimientos = $planCuentas->enviaquery($query);
	    
	    //variables para dibujar en vista
	    $datos_tabla = "";	   	    
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla= '<table border="1">';
	        $datos_tabla.='<tr style="background-color:yellow;">';
	        $datos_tabla.='<th style="text-align: center; ">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th style="text-align: center; ">DETALLE</th>';
	        $datos_tabla.='<th style="text-align: center; ">SALDO INICIAL</th>';
	        $datos_tabla.='<th style="text-align: center; ">MOV MES</th>';
	        $datos_tabla.='<th style="text-align: center; ">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	        
	        
	        foreach ( $rs_movimientos as $res){
	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
	            $datos_tabla.='<tr  bgcolor="">';
	            $datos_tabla.='<td style="text-align: left; ">'.$_codigo_plan_cuentas.'</td>';
	            $datos_tabla.='<td width:100% style="text-align: left; ">'.$_nombre_plan_cuentas.'</td>';
	            $datos_tabla.='<td style="text-align: right; ">'.$_saldo_inicial.'</td>';
	            $datos_tabla.='<td style="text-align: right; ">'.$_movimientos.'</td>';
	            $datos_tabla.='<td style="text-align: right; ">'.$_saldo_final.'</td>';
	            $datos_tabla.='</tr>';
	            
	            
	        }
	        
	        $datos_tabla.= "</table>";
	    }
	    
	    echo utf8_decode($datos_tabla);
	    
	    
	}
	
	
	public function generaReporteconArray(){
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : null);
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    $_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    $planCuentas = new PlanCuentasModel();
	    
	    $columnas_pcuentas = "id_plan_cuentas, codigo_plan_cuentas, nombre_plan_cuentas, saldo_fin_plan_cuentas ";
	    $tablas_pcuentas = " public.plan_cuentas";
	    $where_pcuentas = " 1 = 1  AND nivel_plan_cuentas > 3 ";
	    $id_pcuentas = "codigo_plan_cuentas";
	    
	    $rs_plan_cuentas = $planCuentas->getCondiciones($columnas_pcuentas, $tablas_pcuentas, $where_pcuentas, $id_pcuentas);
	    
	    if( !empty($rs_plan_cuentas) ){
	        
	        //variables para guardar datos de consulta
	        $_codigo_plan_cuentas = "";
	        $_id_plan_cuentas = "";
	        $_nombre_plan_cuentas = "";
	        
	        //variable viene de vista
	        $fecha = $_anio_movimientos.'-'.$_mes_movimientos;
	        
	        $columnas_mayor = "id_mayor, id_plan_cuentas, debe_mayor, haber_mayor, saldo_ini_mayor, saldo_mayor,creado";
	        $tablas_mayor = " public.con_mayor";
	        $where_mayor = " 1 = 1  AND TO_CHAR(fecha_mayor,'YYYY-MM') = '$fecha'";
	        $id_mayor = "id_plan_cuentas, creado";
	        
	        $rs_mayor = $planCuentas->getCondiciones($columnas_mayor, $tablas_mayor, $where_mayor, $id_mayor);
	        
	        //echo $columnas_mayor ."-". $tablas_mayor." - ". $where_mayor." - ". $id_mayor."<br>"; exit();
	        
	        //variables de operacion
	        $sumadebe = 0.00;
	        $sumahaber = 0.00;
	        $movimiento_mes = 0.00;
	        
	        //variables para dibujar
	        $saldo_ini_cuenta = 0.00;
	        $saldo_fin_cuenta = 0.00;
	        
	        //variables contador
	        $_contador_mayor = 0;
	        $_cantidad_mayor = count($rs_mayor);
	        
	        
	        if(!empty($rs_mayor)){
	            
	            foreach ($rs_plan_cuentas as $res){
	                
	                //asignacion de variables de consulta
	                $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	                $_id_plan_cuentas = $res->id_plan_cuentas;
	                $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	                
	                $array_mayor = null;
	                
	                foreach ($rs_mayor as $resmayor){
	                    
	                    //asignacion de variables de consulta mayor	                   
	                    $_id_plan_cuentas_mayor = $resmayor->id_plan_cuentas;
	                    
	                    $_contador_mayor ++;
	                    
	                    if( $_id_plan_cuentas == $_id_plan_cuentas_mayor){
	                        
	                        $array_mayor[] = $resmayor; 
	                       
	                    }
	                    
	                }//end foreach de mayor
	                
	                if( !is_null($array_mayor)){
	                    
	                    foreach ($array_mayor as $resfinal){
	                        
	                        if ($resfinal === end($array_mayor)) {
	                            $saldo_fin_cuenta = $resfinal->saldo_mayor;
	                           
	                        }
	                        
	                        if ($resfinal === reset($array_mayor)) {	                            
	                            $saldo_ini_cuenta = $resfinal->saldo_ini_mayor;	                            
	                        }
	                        
	                        $sumadebe += $resfinal->debe_mayor;
	                        $sumahaber += $resfinal->haber_mayor;
	                        
	                    }
	                    
	                    $movimiento_mes = $sumadebe - $sumahaber;
	                    
	                    if( ($saldo_ini_cuenta + $movimiento_mes) !== $saldo_fin_cuenta){
	                        echo "error en cuenta -> ".$_codigo_plan_cuentas;
	                    }
	                    
	                    echo $_id_plan_cuentas;
	                    echo " * ";
	                    echo $_codigo_plan_cuentas;
	                    echo " * ";
	                    echo $_nombre_plan_cuentas;
	                    echo " * ";
	                    echo $saldo_ini_cuenta;
	                    echo " * ";
	                    echo $movimiento_mes;
	                    echo " * ";
	                    echo $saldo_fin_cuenta;
	                    echo " * "; echo "<br>";
	                    
	                }
	                
	                $array_mayor = null;
	                
	                $saldo_ini_cuenta = $movimiento_mes = $saldo_fin_cuenta = $sumadebe = $sumahaber = 0.00;
	               	                
	            }
	            
	        }//fin end if de $rs_mayor
	          
	    }
	}
	
	public function index2(){
	    	    echo '<html><head></head><body>1</body></html>';
	    	    print_r($_POST);
	}

	public function index1(){
	
    	//Creamos el objeto usuario
        $movimientos_inventario = new MovimientosInvModel();
    	//Conseguimos todos los usuarios
		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "MovimientosProductosCabeza";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $movimientos_inventario->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_movimientos_productos_cabeza"])   )
				{

					$nombre_controladores = "MovimientosProductosCabeza";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $movimientos_inventario->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					 

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Movimientos Productos Cabeza"
					
						));
					
					
					}
					
				}
		
				
				$this->view("Compras",array(
						
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Movimientos Productos Cabeza"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	
	public function paginatemultiple($reload, $page, $tpages, $adjacents,$funcion='') {
	    
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
	
}



?>