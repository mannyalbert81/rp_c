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
	    $resultPer = $cuentas_pagar->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$_id_rol' " );
	    
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
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : '01');
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
	        $datos_tabla.='<th bgcolor="" width="10%"  style="text-align: center;">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO FINAL</th>';
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
	                $datos_tabla.='<td bgcolor="" width="10%" style="text-align: left;">'.$_codigo_plan_cuentas.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left;">'.$_nombre_plan_cuentas.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right;">'.$_saldo_inicial.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right;">'.$_movimientos.'</td>';
	                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: right; color:red;">'.$_saldo_final.'</td>';
	                $datos_tabla.='</tr>';
	            }
	            
	            
	        }
	        
	        $datos_tabla.= "</table>";
	    }  
	    
	    //para pruebas
	    //$cuentaserror = array();
	    
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
	        	       
	        $datos_error.='<h4>Proceda con la generacion del archivo.</h4>';
	        $datos_error.='<div class="btn-toolbar" role="toolbar">';
	        $datos_error.='<button type="button" id="visualizarArchivo" class="btn btn-lg bg-olive"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i> Visualizacion Datos</button>';
	        $datos_error.='<button type="button" id="genera_pdf" class="btn btn-lg bg-orange"> <i class="fa fa-file-pdf-o" aria-hidden="true" ></i> Generar Formato PDF</button>';
	        $datos_error.='<button type="button" id="genera_excel" class="btn btn-lg btn-info"> <i class="fa fa-file-excel-o" aria-hidden="true" ></i> Generar Formato EXCEL</button>';
	        $datos_error.='</div>';
	        $datos_tabla = "";
	    }
	    
	    echo json_encode(array('error'=>$datos_error,'tabla_error'=>$datos_tabla));
	    
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
	        $datos_tabla.='<th bgcolor="" width="10%" style="text-align: center;">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	        
	        
	        foreach ( $rs_movimientos as $res){	            
	         
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
                $datos_tabla.='<tr bgcolor="">';
                $datos_tabla.='<td bgcolor="" width="10%" style="text-align: left;">'.$_codigo_plan_cuentas.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left;">'.$_nombre_plan_cuentas.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" class="numero">$ '.$_saldo_inicial.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" class="numero">$ '.$_movimientos.'</td>';
                $datos_tabla.='<td bgcolor="" width="20%" class="numero">$ '.$_saldo_final.'</td>';
                $datos_tabla.='</tr>';
	           
	            
	        }	        
	        
	        $datos_tabla.= "</table>";
	    }
	    
	    $entidades = new EntidadesModel();
	    
	    $datos_empresa = array();
	    $datos_detalle = array();
	    
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
	    
	    $datos_detalle = $datos_tabla;   
	    
	    $this->verReporte("MovimientosContables", array('datos_empresa'=>$datos_empresa,'datos_detalle'=>$datos_detalle));
	}
	
	public function VisualizaDatos(){
	    
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
	    $_nombre_tabla = "tbl_movimientos";
	    
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $datos_tabla.='<thead>';
	        $datos_tabla.='<tr  bgcolor="">';
	        $datos_tabla.='<th bgcolor="" width="10%"  style="text-align: center;">CUENTA CONTABLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">DETALLE</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO INICIAL</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">MOV MES</th>';
	        $datos_tabla.='<th bgcolor="" width="20%" style="text-align: center;">SALDO FINAL</th>';
	        $datos_tabla.='</tr>';
	        $datos_tabla.='</thead>';
	        $datos_tabla.='<tbody>';
	        
	        //variables de totales 
	        $_total_saldo_i = "0";
	        $_total_saldo_f = "0";
	        $_total_saldo_mov = "0";
	        
	        foreach ( $rs_movimientos as $res){
	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_total_saldo_i = $_total_saldo_i + (float)$res->saldo_ini_mayor;
	            $_total_saldo_mov = $_total_saldo_mov + (float)$res->movimiento;
	            $_total_saldo_f = $_total_saldo_f + (float)$res->saldo_mayor;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
	            $datos_tabla.='<tr  bgcolor="">';
	            $datos_tabla.='<td bgcolor="" width="10%" style="text-align: left; ">'.$_codigo_plan_cuentas.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" style="text-align: left; ">'.$_nombre_plan_cuentas.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" class="numero">'.$_saldo_inicial.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" class="numero">'.$_movimientos.'</td>';
	            $datos_tabla.='<td bgcolor="" width="20%" class="numero">'.$_saldo_final.'</td>';
	            $datos_tabla.='</tr>';
	            
	            
	        }
	        $datos_tabla.='</tbody>';
	        $datos_tabla.='<tfoot>';
	        $datos_tabla.='<tr>';
	        $datos_tabla.='<th></th>';
	        $datos_tabla.='<th width="20%" style="text-align: right;">TOTALES</th>';
	        $datos_tabla.='<th width="20%" style="text-align: right;">'.number_format($_total_saldo_i, 2, ',', '.').'</th>';
	        $datos_tabla.='<th width="20%" style="text-align: right;">'.number_format($_total_saldo_mov, 2, ',', '.').'</th>';
	        $datos_tabla.='<th width="20%" style="text-align: right;">'.number_format($_total_saldo_f, 2, ',', '.').'</th>';
	        $datos_tabla.='</tr>';
	        $datos_tabla.='</tfoot>';
	        $datos_tabla.='</tbody>';
	        $datos_tabla.= "</table>";
	    }
	    
	    $entidades = new EntidadesModel();
	    
	    
	    
	    $datos_empresa = array();
	    $datos_detalle = array();
	    
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
	    	    
	    echo json_encode(array("tabla_datos"=>$datos_tabla,"nombre_tabla"=>$_nombre_tabla));
	}
	
	public function generaReporteExcel(){
	    
	    //notice.. cualquier cambio realizado en metodo principal "generaReporte"
	    //debe reflejar tambien en este metodo en cuanto a consulta a la base de datos 
	    //o validacion de parametros de filtros
	    
	    session_start();
	    
	    //datos de post
	    $_mes_movimientos = (isset($_POST['mes_movimientos']) ? $_POST['mes_movimientos'] : '01');
	    $_anio_movimientos = (isset($_POST['anio_movimientos']) ? $_POST['anio_movimientos'] : date('Y'));
	    //no implementado
	    //$_nivel_pcuentas = (isset($_POST['nivel_plan_cuentas']) ? $_POST['nivel_plan_cuentas'] : null);
	    
	    //$nombreArchivo = "MovimientosContables_".$_anio_movimientos.$_mes_movimientos;
	    
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
	    
	    
	    $arrayCabecera = array('CUENTA CONTABLE','DETALLE', 'SALDO INICIAL', 'MOV MES', 'SALDO FINAL');
	    $arraydetalle = array();
	    $arrayfila = array();
	    //array_push($arraydetalle,$arrayCabecera);
	    
	    $datos_tabla = "";
	    $datos_tabla .= "<table border=1>";
	    $datos_tabla .= "<thead>";
	    $datos_tabla .= "<tr >";
	    $datos_tabla .= "<th bgcolor=\"yellow\">CUENTA CONTABLE</th>";
	    $datos_tabla .= "<th bgcolor=\"yellow\">DETALLE</th>";
	    $datos_tabla .= "<th bgcolor=\"yellow\">SALDO INICIAL</th>";
	    $datos_tabla .= "<th bgcolor=\"yellow\">MOV MES</th>";
	    $datos_tabla .= "<th bgcolor=\"yellow\">SALDO FINAL</th>";
	    $datos_tabla .= "</tr>";
	    $datos_tabla .= "</thead>";
	    
	    if( !empty($rs_movimientos) ){
	        
	        $datos_tabla .= "<tbody>";
	        
	        foreach ( $rs_movimientos as $res){
	            
	            //variable para imprimir
	            $_id_plan_cuentas = $res->id_plan_cuentas;
	            $_codigo_plan_cuentas = $res->codigo_plan_cuentas;
	            $_nombre_plan_cuentas = $res->nombre_plan_cuentas;
	            $_saldo_inicial = number_format((float)$res->saldo_ini_mayor, 2, ',', '.');
	            $_movimientos = number_format((float)$res->movimiento, 2, ',', '.');
	            $_saldo_final = number_format((float)$res->saldo_mayor, 2, ',', '.');
	            
	            $arrayfila = array( $_codigo_plan_cuentas, $_nombre_plan_cuentas, $_saldo_inicial,$_movimientos, $_saldo_final);
	            
	            
	            $datos_tabla .= "<tr>";
	            $datos_tabla .= "<td>$_codigo_plan_cuentas</td>";
	            $datos_tabla .= "<td>$_nombre_plan_cuentas</td>";
	            $datos_tabla .= "<td>$_saldo_inicial</td>";
	            $datos_tabla .= "<td>$_movimientos</td>";
	            $datos_tabla .= "<td >$_saldo_final</td>";
	            $datos_tabla .= "</tr>";
	            //array_push($arraydetalle,$arrayfila);
	            
	        }
	        
	        $datos_tabla .= "</tbody>";
	        
	    }
	    
	    $datos_tabla .= "</table>";
	    
	    //para generar archivo excel
	    $filename = "MovimientosContables_".$fecha;
	    header("Content-Type: application/vnd.ms-excel");
	    
	    header("Content-Disposition: attachment; filename=".$filename.".xls");
	    
	    echo utf8_decode($datos_tabla);
	    
	}

	
}



?>