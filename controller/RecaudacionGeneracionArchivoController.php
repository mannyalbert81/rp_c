<?php
class RecaudacionGeneracionArchivoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}
	
	private $_nombre_formato="";
	private $_nombre_creditos_formato  = "DESCUENTOS CREDITOS";
	private $_nombre_aportes_formato   = "DESCUENTOS APORTES";
	private $_nombre_combinado_formato = "DESCUENTOS APORTES Y CREDITOS";	  
    
	public function index(){
	
	    session_start();
		
     	$EntidadPatronal = new EntidadPatronalParticipesModel();
     		
		if( isset(  $_SESSION['nombre_usuarios'] ) ){

			$nombre_controladores = "GenArchRecaudacion";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer)){
			    
			    $queryEntidad = "SELECT * FROM core_entidad_patronal ORDER BY nombre_entidad_patronal";			    
			    $rsEntidadPatronal = $EntidadPatronal->enviaquery($queryEntidad);
			
			    $this->view_Recaudaciones("GeneracionArchivo",array(
			        'rsEntidadPatronal' => $rsEntidadPatronal
			    ));
				
			}else{
			    
			    $this->view("Error",array(
			        "resultado"=>"No tiene Permisos de Acceso a Grupos"
			        
			    ));
			    
			    exit();				    
			}
				
		}else{
       	
		    $this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function GenerarRecaudacion(){
	     
	    $recaudaciones     = new RecaudacionesModel();
	    session_start();
	    
	    /* variables locales */
	    $_nombre_formato_recaudacion = "";
	    
	    /*variables de la vista*/
	    $_id_entidad_patronal  = 0;
	    $_anio_recaudacion     = 0;
	    $_mes_recaudacion      = 0;
	    $_formato_recaudacion  = 0;
	    
	    /** validar las variables que llegan de la vista */
	    try {
	        //variables de session 
	        $usuario_usuarios  = $_SESSION['usuario_usuarios'];
	        $id_usuarios       = $_SESSION['id_usuarios'];
	        
	        $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	        $_anio_recaudacion     = $_POST['anio_recaudacion'];
	        $_mes_recaudacion      = $_POST['mes_recaudacion'];
	        $_formato_recaudacion  = $_POST['formato_recaudacion'];
	        $_id_descuentos_formatos   = $_POST['id_descuentos_formatos'];
	        
	        if( !empty( error_get_last() ) ){    throw new Exception('Datos enviados no validos para generacion de Archivo'); }
	        
	    } catch (Exception $e) {
	        echo '<message>'.$e->getMessage().' <message>';
	        exit();
	    }
	    
	    //validacion de aportes personales
	    if( $_formato_recaudacion == 1 || $_formato_recaudacion == 3 ){
	        
	        $_participes_sin_aportes   = $this->validaAportesParticipes($_id_entidad_patronal);
	        	        
	        if(!empty($_participes_sin_aportes)){
	            $errorAportes  = array();
	            $errorAportes['mensajeAportes']    = "Necesita Validar la informacion de estos Paricipes";
	            $errorAportes['dataAportes']    = $_participes_sin_aportes;
	            echo json_encode($errorAportes);
	            die();
	        }
	    }
	                  
	    $_nombre_formato_recaudacion = ($_formato_recaudacion == 1) ? "DESCUENTOS APORTES" : ( ( $_formato_recaudacion == 2 ) ? "DESCUENTOS CREDITOS" : "DESCUENTOS APORTES Y CREDITOS");
	    
	    /** primero validar si el tipo de archivo solicitado esta permitido */
	    try {
	        
	        $auxValidaDatos    = $this->ValidarDatosByFormato( $_formato_recaudacion, $_id_entidad_patronal, $_anio_recaudacion, $_mes_recaudacion, $_id_descuentos_formatos);
	        
	        if( $auxValidaDatos['error'] ){
	            throw new Exception($auxValidaDatos['mensaje']);
	        }
	               
	        
	    } catch (Exception $ex) {
	        
	        echo '<message>'.$ex->getMessage().' <message>';
	        exit();
	    }    
	   
	    try{
	        
	        $resp  = array();
	        
	        $recaudaciones->beginTran();
	        	        
	        /*configurar estructura mes de consulta*/
	        $_mes_recaudacion = str_pad($_mes_recaudacion, 2, "0", STR_PAD_LEFT);   
	        
	        /** SE REALIZA EL INSERTADO DE LA CABECERA PRIMERO **/
	        $funcion = "core_ins_descuentos_registrados_cabeza";
	        
	        $formatoFecha      = $_anio_recaudacion."-".$_mes_recaudacion."-01";
	        $Ofecha = new DateTime($formatoFecha);
	        $Ofecha->modify('last day of this month');	               
	        $fecha_descuentos  = $Ofecha->format('Y-m-d');
	        $fecha_proceso     = date('Y-m-d H:i:s');
	        $id_tipo_credito   = "null";
	        $observacion_descue= "";	        
	        $nombreArchivo     = "ArchivoEnviado.txt";
	        $procesado_descuen = "t";
	        $error_desccuentos = "f";
	        
	        //creacion de la variable parametros
	        $parametros = "";
	        $parametros .= $_id_entidad_patronal.",";
	        $parametros .= $_anio_recaudacion.",";
	        $parametros .= $_mes_recaudacion.",";
	        $parametros .= "'".$usuario_usuarios."',";
	        $parametros .= $id_usuarios.",";
	        $parametros .= "'".$fecha_descuentos."',";
	        $parametros .= "'".$nombreArchivo."',";
	        $parametros .= $_id_descuentos_formatos.",";
	        $parametros .= "'".$procesado_descuen."',";
	        $parametros .= "'".$error_desccuentos."',";
	        $parametros .= $id_tipo_credito.",";
	        $parametros .= "'".$observacion_descue."',";
	        $parametros .= "'".$fecha_proceso."'";
	        
	        $sqRecaudaciones    = $recaudaciones->getconsultaPG($funcion, $parametros);
	        $resultado  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
	        
	        $id_descuentos_registrados_cabeza = $resultado[0];
	        
	        //$errorProceso = false; //variable pasar por referencia 
	        $paramsCab = array();
	        $paramsCab['id_descuentos_registrados_cabeza'] = $id_descuentos_registrados_cabeza;
	        $paramsCab['id_entidad_patronal']          = $_id_entidad_patronal;
	        $paramsCab['id_descuentos_formatos']       = $_id_descuentos_formatos;
	        $paramsCab['anio_recaudaciones']           = $_anio_recaudacion;
	        $paramsCab['mes_recaudaciones']            = $_mes_recaudacion;
	        
	        if( (int)$_formato_recaudacion === 1 ){
	            
	            $auxDetalle    = $this->RecaudacionAportes($paramsCab);
	            
	            if( $auxDetalle['error'] ){
	                throw new Exception( $auxDetalle['mensaje'] );
	            }
	            
	        }elseif ( (int)$_formato_recaudacion === 2 ){
	            
	            $auxDetalle    = $this->RecaudacionCreditos($paramsCab);
	            
	            if( $auxDetalle['error'] ){
	                throw new Exception( $auxDetalle['mensaje'] );
	            }
	        }else{
	            throw new Exception( "Datos no generados \n Tipo Descuento no encontrado" );
	        }	        	        
	        
	        $resp['estatus']   = "OK";
	        $resp['icon']      = "success";
	        $resp['mensaje']   = "Datos generados";
            $recaudaciones->endTran('COMMIT');
            echo json_encode($resp);
	                
	    } catch (Exception $ex) {
	        $recaudaciones->endTran();
	        echo '<message> Error Archivo Recaudacion '.$ex->getMessage().' <message>';
	    }	    
	    
	}
	
	public function ValidarDatosByFormato(int $formato, int $id_entidad_patronal, int $anio_descuentos, int $mes_descuentos, int $id_descuentos_formatos){
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    $response = array();
	    $rsConsulta1   = array();
	    
	    if( $formato == 1 ){
	        //son para aportes personales
	        $columnas1 = " COUNT(1) cantidad ";
	        $tablas1   = " public.core_descuentos_registrados_detalle_aportes ";
	        $where1    = " year_descuentos_registrados_detalle_aportes = $anio_descuentos
    	    AND mes_descuentos_registrados_detalle_aportes = $mes_descuentos
    	    AND id_entidad_patronal = $id_entidad_patronal 
            AND id_descuentos_formatos = $id_descuentos_formatos ";
	        $rsConsulta1 = $recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, "");
	        
	    }elseif ($formato == 2){
	        
	        $columnas2 = " COUNT(1) cantidad ";
	        $tablas2   = " public.core_descuentos_registrados_detalle_creditos ";
	        $where2    = " year_descuentos_registrados_detalle_creditos = $anio_descuentos
    	    AND mes_descuentos_registrados_detalle_creditos = $mes_descuentos
    	    AND id_entidad_patronal = $id_entidad_patronal 
            AND id_descuentos_formatos = $id_descuentos_formatos ";
	        $rsConsulta1 = $recaudaciones->getCondicionesSinOrden($columnas2, $tablas2, $where2, "");
	        
	    }else{
	        $response['error']  = true;
	    }
	    
	    $error = error_get_last();
	    if( !empty( $error ) ){
	        $response['error']  = true;
	        $response['mensaje']= $error['message'];
	    }
	    
	    if( (int)$rsConsulta1[0]->cantidad > 0 ){
	        $response['error']  = true;
	        $response['mensaje']= 'ya existen datos con los valores recibidos';
	    }else{
	        $response['error']  = false;
	    }
	    
	    return $response;
	    
	}
	
	public function RecaudacionAportes( array $paramsCab){
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    $response  = array();
	    
	    $id_descuentos_registrados_cabeza      = $paramsCab['id_descuentos_registrados_cabeza'];
	    $id_entidad_patronal    = $paramsCab['id_entidad_patronal'];
	    $id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
	    $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
	    $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
	    
	    $columnas1 = "aa.id_contribucion_tipo_participes, aa.valor_contribucion_tipo_participes,aa.sueldo_liquido_contribucion_tipo_participes, 
   	        aa.porcentaje_contribucion_tipo_participes, bb.id_contribucion_tipo, bb.nombre_contribucion_tipo,cc.id_tipo_aportacion, cc.nombre_tipo_aportacion, 
            dd.cedula_participes, dd.id_participes, dd.apellido_participes, dd.nombre_participes";
	    $tablas1   = "core_contribucion_tipo_participes aa
    	    INNER JOIN core_contribucion_tipo bb    ON bb.id_contribucion_tipo = aa.id_contribucion_tipo
    	    INNER JOIN core_tipo_aportacion cc    ON cc.id_tipo_aportacion = aa.id_tipo_aportacion
    	    INNER JOIN core_participes dd    ON dd.id_participes = aa.id_participes
    	    INNER JOIN core_estado_participes ee ON ee.id_estado_participes = dd.id_estado_participes";
	    $where1    = "bb.nombre_contribucion_tipo = 'Aporte Personal'
	        AND dd.id_estatus = 1
	        AND dd.id_entidad_patronal = $id_entidad_patronal
	        AND UPPER(ee.nombre_estado_participes) = 'ACTIVO'";
	    $id1       = "dd.id_participes";	
	    
	    $rsConsulta1 = $recaudaciones->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if( empty( $rsConsulta1 )  ){ return array( 'error'=>true, 'mensaje'=>"Data se encuentra vacia"); }
	    
	    
	    /** formato standar revisar definicion code ant 'linea'=>1,'cedula'=>'00000000000','valor'=>0.00,'id_participes'=>1 **/
	    $detalle    = array();
	    $funcionDetalle = "core_ins_descuentos_registrados_detalle_aportes";
	    
	    foreach ( $rsConsulta1 as $res ){
	        
	        $_valor_sistema = 0.00;
	        $_valor_final   = 0.00;
	        
	        /* validar tipo contribucion participe */
	        if( strtoupper($res->nombre_tipo_aportacion) == "VALOR"){
	            $_valor_sistema    = $res->valor_contribucion_tipo_participes;
	            $_valor_final      = $res->valor_contribucion_tipo_participes;
	        }
	        
	        if( strtoupper($res->nombre_tipo_aportacion) == "PORCENTAJE"){
	            $_sueldo   = (float)$res->sueldo_liquido_contribucion_tipo_participes;
	            $_porcentaje   = (float)$res->porcentaje_contribucion_tipo_participes;
	            $_valor_base   = ($_sueldo * $_porcentaje)/100;
	            $_valor_sistema = $_valor_base;
	            $_valor_final   = $_valor_base;
	        }
	        
	        $detalle['id_descuentos_registrados_cabeza']    = $id_descuentos_registrados_cabeza;
	        $detalle['id_entidad_patronal']                 = $id_entidad_patronal;
	        $detalle['anio_descuentos']                     = $anio_recaudacion;
	        $detalle['mes_descuentos']                      = $mes_recaudacion;
	        $detalle['id_participes']                       = $res->id_participes;
	        $detalle['aporte_personal']                     = $_valor_sistema;
	        $detalle['aporte_patronal']                     = "0.00";
	        $detalle['rmu_descuentos']                      = "0.00";
	        $detalle['liquido_descuentos']                  = "0.00";
	        $detalle['multas_descuentos']                   = "0.00";
	        $detalle['antiguedad_descuentos']               = "0.00";
	        $detalle['alta_descuentos']                     = "t";
	        $detalle['id_descuentos_formatos']              = $id_formatos_descuentos;
	        $detalle['procesado_descuentos']                = "t";
	        $detalle['saldo_descuentos']                    = "0.00";
	        $detalle['valor_usuario']                       = $_valor_final;
	        
	        $parametrosDetalle  = "'".join("','", $detalle)."'";
	        $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
	        
	        $recaudaciones->llamarconsultaPG($sqDetalle);
	        
	        if( !empty( pg_last_error() ) ){
	            break;
	        }
	        
	    }
	    
	    $error = error_get_last();
	    if( !empty( $error ) ){
	        $response['error']     = true;
	        $response['mensaje']   = $error['message'];
	        return $response;
	    }
	    
	    $response['error']     = false;
	    $response['mensaje']   = "";
	    
	    return $response;
	   
	}
	
	public function RecaudacionCreditos( array $paramsCab){
	    
	    if( !isset( $_SESSION ) ){ session_start();	}
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    $response  = array();
	    
	    $id_descuentos_registrados_cabeza      = $paramsCab['id_descuentos_registrados_cabeza'];
	    $id_entidad_patronal    = $paramsCab['id_entidad_patronal'];
	    $id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
	    $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
	    $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
	    
	    /*** EJECUCION DE FUNCION QUE REALIZA BUSQUEDA EN INSERTA EN TABLA DE VALORES **/
	    
	    $funcion = "fn_genera_detalle_valores_descuentos_creditos";	    
	    $parametros = "$id_entidad_patronal,$id_formatos_descuentos,$anio_recaudacion,$mes_recaudacion";
	    $sqDatos   = $recaudaciones->getconsultaPG($funcion, $parametros);
	    
	    $resultado = $recaudaciones->llamarconsultaPG($sqDatos);
	    
	    if( (int)$resultado[0] != 1 ){
	        return array('error'=>true,'mensaje'=>"ejecucion funcion datos creditos fallida");
	    }
	    
	    $col1  = "aa.id_participes, aa.id_creditos, aa.valor_cuota_descuentos_registrados_detalle_valores_creditos,cc.plazo_creditos, 
            cc.saldo_actual_creditos,bb.mora_tabla_amortizacion";
	    $tab1  = "core_descuentos_registrados_detalle_valores_creditos aa
    	    INNER JOIN core_tabla_amortizacion bb ON bb.id_tabla_amortizacion = aa.id_tabla_amortizacion
    	    INNER JOIN core_creditos cc ON cc.id_creditos = aa.id_creditos";
	    $whe1  = "aa.id_entidad_patronal = $id_entidad_patronal
            AND aa.id_descuentos_formatos = $id_formatos_descuentos
            AND aa.anio_descuentos_registrados_detalle_valores_creditos = $anio_recaudacion
            AND aa.mes_descuentos_registrados_detalle_valores_creditos	= $mes_recaudacion";
	    $id1   = " aa.id_participes";
	    	    
	    $rsConsulta1   = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    if( empty( $rsConsulta1 ) ){
	        return array('error'=>true,'mensaje'=>"extraccion datos descuentos fallida");
	    }
	    
	    $detalle   = array();
	    
	    $mes_desc_descuentos   = "null";
	    $credito_pay_descuentos= "0";
	    $tipo_descuento        = "0";
	    $procesados_descuentos = "t";
	    $alta_descuentos       = "t";
	    $monto_descuentos      = "0";
	    
	    $funcionDetalle    = "core_ins_descuentos_registrados_detalle_creditos";
	    $parametrosDetalle = "";
	    
	    foreach ( $rsConsulta1 as $res ){
	        
	        $id_participes = ( !empty( $res->id_participes ) ) ? $res->id_participes : 'null';
	        $id_creditos   = ( !empty( $res->id_creditos ) ) ? $res->id_creditos : 'null';
	        $cuota_descuentos  = ( !empty( $res->valor_cuota_descuentos_registrados_detalle_valores_creditos ) ) ? $res->valor_cuota_descuentos_registrados_detalle_valores_creditos : 0;
	        $plazo_desccuentos = ( !empty( $res->plazo_creditos ) ) ? $res->plazo_creditos : 0;
	        $mora_descuentos   = ( !empty( $res->mora_tabla_amortizacion ) ) ? $res->mora_tabla_amortizacion : 0;
	        $saldo_descuentos  = ( !empty( $res->saldo_actual_creditos ) ) ? $res->saldo_actual_creditos : 0;
	        
	        $detalle['id_descuentos_registrados_cabeza']    = $id_descuentos_registrados_cabeza;
	        $detalle['id_entidad_patronal']                 = $id_entidad_patronal;
	        $detalle['anio_descuentos']                     = $anio_recaudacion;
	        $detalle['mes_descuentos']                      = $mes_recaudacion;
	        $detalle['id_tipo_descuento']                   = $tipo_descuento;
	        $detalle['id_participes']                       = $id_participes; 
	        $detalle['id_creditos']                         = $id_creditos;
	        $detalle['cuota_descuentos']                    = $cuota_descuentos;
	        $detalle['monto_descuentos']                    = $monto_descuentos;
	        $detalle['plazo_descuentos']                    = $plazo_desccuentos;
	        $detalle['alta_descuentos']                     = $alta_descuentos;
	        $detalle['id_descuentos_formatos']              = $id_formatos_descuentos;
	        $detalle['procesado_descuentos']                = $procesados_descuentos;
	        $detalle['saldo_descuentos']                    = $saldo_descuentos;
	        $detalle['mora_descuentos']                     = $mora_descuentos;//-
	        $detalle['credito_pay_descuentos']              = $credito_pay_descuentos;
	        $detalle['mes_desc_descuentos']                 = $mes_desc_descuentos;
	        
	        $parametrosDetalle  = "'".join("','", $detalle)."'";
	        $parametrosDetalle  = str_replace("'null'","null",$parametrosDetalle);
	        $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
	        
	        $recaudaciones->llamarconsultaPG($sqDetalle);
	        
	        if( !empty( pg_last_error() ) ){
	            break;
	        }
	        
	    }
	    
	    $error = error_get_last();
	    if( !empty( $error ) ){
	        $response['error']     = true;
	        $response['mensaje']   = $error['message'];
	        return $response;
	    }
	    
	    $response['error']     = false;
	    $response['mensaje']   = "";
	    
	    return $response;
	    
	}
	
	public function RecaudacionCreditos2( $_id_entidad_patronal, $_anio, $_mes){
	    
	    if(!isset($_SESSION)){
	        session_start();
		}
		
		/** variable para tomar listado de participes */
		$_array_participes = array();
	    
	    $_fecha_buscar = $_anio.$_mes;
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    $formato_archivo_recaudaciones = "DESCUENTOS CREDITOS";
	    
	    $columnas1 = "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,
            	    bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
            	    cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
	    $tablas1   = "core_tabla_amortizacion aa
            	    INNER JOIN core_creditos bb   ON bb.id_creditos = aa.id_creditos
            	    INNER JOIN core_participes cc  ON cc.id_participes = bb.id_participes
            	    INNER JOIN core_estado_creditos dd  ON dd.id_estado_creditos = bb.id_estado_creditos";
	    $where1    = "aa.id_estatus = 1
            	    AND bb.id_estatus = 1
                    AND cc.id_estatus = 1 
            	    AND aa.id_estado_tabla_amortizacion <> 2
                    AND bb.id_estado_creditos = 4
                    AND cc.id_entidad_patronal = $_id_entidad_patronal 
            	    AND TO_CHAR(aa.fecha_tabla_amortizacion,'YYYYMM') = '$_fecha_buscar'
            	    AND dd.nombre_estado_creditos = 'Activo'";
	    $id1       = "cc.id_participes, aa.id_tabla_amortizacion";
	    	    
	    $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    //echo $_id_entidad_patronal;
	    
	    /** buscar cuotas en mora de los participes */	    
	    $columnas2 = "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,aa.mora_tabla_amortizacion,"
    	    ." bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,"
    	    ." cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
	    $tablas2   = "core_tabla_amortizacion aa"
    	    ." INNER JOIN core_creditos bb ON bb.id_creditos = aa.id_creditos"
    	    ." INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes and cc.id_estatus = bb.id_estatus"
    	    ." INNER JOIN core_estado_creditos dd ON dd.id_estado_creditos = bb.id_estado_creditos";
	    $where2    = "bb.id_estatus = 1"
            ." AND upper(dd.nombre_estado_creditos) = 'ACTIVO'"
	        ." AND coalesce(aa.mora_tabla_amortizacion,0) > 0"
	        ." AND aa.id_estado_tabla_amortizacion <> 2"
	        ." AND cc.id_entidad_patronal in ($_id_entidad_patronal)"
	        ." AND to_char(aa.fecha_tabla_amortizacion,'YYYYMM') < '$_fecha_buscar'";
	    $id2       = " cc.id_participes,bb.id_creditos,aa.id_tabla_amortizacion ";
	    
        $rsConsulta2 = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
        
        /** devuelve cero cuando no hay recaudacion creditos */ 
        if( sizeof($rsConsulta1) <= 0 && sizeof($rsConsulta2) <= 0){
            return 0;
        }
	    
        $funcionArchivo    = "core_ins_core_archivo_recaudaciones";
        $parametrosArchivo = "'$_anio','$_mes','$_id_entidad_patronal',null,null,'$formato_archivo_recaudaciones','$_usuario_usuarios'";
        
        $queryFuncion  = $Contribucion->getconsultaPG($funcionArchivo, $parametrosArchivo);
        $Resultado1    = $Contribucion->llamarconsultaPG($queryFuncion);
        
        $error = "";
        $error = pg_last_error();
        if( !empty($error) ){ throw new Exception(' Recaudacion Creditos en la funcion de insertado'); }
        
        $_id_archivo_recaudaciones  = $Resultado1[0];
        
        /** INSERTAR DETALLE DE CUOTAS A RECAUDAR */
        $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
        $parametrosDetalle = "";        
        foreach ($rsConsulta1 as $res){
        
            $_id_participes = $res->id_participes;
            $_id_creditos   = $res->id_creditos;
            $_valor_sistema = $res->total_valor_tabla_amortizacion;
            $_valor_final   = $res->total_valor_tabla_amortizacion;
            
            $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes','$_id_creditos','$_valor_sistema','$_valor_final','CUOTA MENSUAL CRE[.$_id_creditos.]',''";
            $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
            $Contribucion->llamarconsultaPG($queryFuncion);
            
            $error = pg_last_error();
            if( !empty($error) ){ break; throw new Exception('Recaudacion cuota creditos Error en la funcion de insertado detalle');}
			
			/** para almacenar en un array lista de participes */
			array_push($_array_participes,$res->id_participes);	
		}
		
		/** GENERAR RECAUDACION CUOTAS VENCIDAS */
		$funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
		$parametrosDetalle = "";
		foreach ($rsConsulta2 as $res){
		    
		    $_id_participes = $res->id_participes;
		    $_id_creditos   = $res->id_creditos;
		    $_valor_sistema = $res->total_valor_tabla_amortizacion;
		    $_valor_final   = $res->total_valor_tabla_amortizacion;
		    $_valor_mora    = $res->mora_tabla_amortizacion;
		    $_valor_sumado  = $_valor_sistema + $_valor_mora;
		    
		    $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes','$_id_creditos','$_valor_sumado','$_valor_sumado','CUOTA MENSUAL VENCIDA ',''";
		    $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
		    $Contribucion->llamarconsultaPG($queryFuncion);
		    
		    $error = pg_last_error();
		    if( !empty($error) ){ break; throw new Exception('Recaudacion cuota vencida creditos Error en la funcion de insertado detalle');}		    
		   
		}

		/** BEGIN PRUEBAS MULTIPLE DE ARRAY LISTA  */	
		/** EMPIEZA RECAUDACION DE GARANTIZADOS */
		$_lista_string_participes = implode( "," ,$_array_participes);
		$_lista_string_participes = empty($_lista_string_participes) ? 0 : $_lista_string_participes;

		$columnas3	= "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,aa.mora_tabla_amortizacion,
    		bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
    		cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes, ee.id_participes \"id_participes_garante\"";
		$tablas3	= "core_tabla_amortizacion aa
    		inner join core_creditos bb on bb.id_creditos = aa.id_creditos
    		inner join core_participes cc on cc.id_participes = bb.id_participes and cc.id_estatus = bb.id_estatus
    		inner join core_estado_creditos dd on dd.id_estado_creditos = bb.id_estado_creditos
    		inner join core_creditos_garantias ee on ee.id_creditos = bb.id_creditos";
		$where3		= "bb.id_estatus = 1
    		and upper(dd.nombre_estado_creditos) = 'ACTIVO'
    		and coalesce(aa.mora_tabla_amortizacion,0) > 0 
    		and aa.id_estado_tabla_amortizacion <> 2
    		and ee.id_participes in ($_lista_string_participes)
    		and to_char(aa.fecha_tabla_amortizacion,'YYYYMM') <= '$_fecha_buscar'";  
		$id3		= "aa.id_tabla_amortizacion";
		$rsConsulta3= $Contribucion->getCondiciones($columnas3,$tablas3,$where3,$id3);

		if(!empty($rsConsulta3)){
			/** los valores aqui a procesar son de creditos en los que el participe esta como garante */			
			$funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
			$parametrosDetalle = "";
			foreach( $rsConsulta3 as $res3){
                
			    $_id_participes_garante = $res3->id_participes_garante;
				$_id_creditos_garantizado   = $res3->id_creditos;
				$_valor_sistema_garantizado	= (float)$res3->total_valor_tabla_amortizacion + (float)$res->mora_tabla_amortizacion;
				$_valor_final_garantizado   = $_valor_sistema_garantizado;
				$_descripcion_garantizado	= 'CUOTA MENSUAL GARANTIZADO ['.$res3->cedula_participes.']';
				$_concepto_gar		= "";
				$parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes_garante','$_id_creditos_garantizado','$_valor_sistema_garantizado','$_valor_final_garantizado',$_descripcion_garantizado,$_concepto_gar";
				$queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
				$Contribucion->llamarconsultaPG($queryFuncion);
				
				$error = pg_last_error();
				if( !empty($error) ){ break; throw new Exception('Recaudacion cuota vencida garantizado. Error en la funcion de insertado detalle');}

			}

		}

		/* para buscar valores anteriores de credito*/

		
		/** END PRUEBAS MULTIPLE DE ARRAY LISTA  */
		
		/** recorrido para buscar moras de sus garantizados */
		/*foreach ($rsConsulta1 as $res){

			$_id_participe_garante = $res->id_participes;

			$columnas2	= "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,aa.mora_tabla_amortizacion,
			bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
			cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
			$tablas2	= "core_tabla_amortizacion aa
			inner join core_creditos bb on bb.id_creditos = aa.id_creditos
			inner join core_participes cc on cc.id_participes = bb.id_participes and cc.id_estatus = bb.id_estatus
			inner join core_estado_creditos dd on dd.id_estado_creditos = bb.id_estado_creditos
			inner join core_creditos_garantias ee on ee.id_creditos = bb.id_creditos";
			$where2		= "bb.id_estatus = 1
			and upper(dd.nombre_estado_creditos) = 'ACTIVO'
			and coalesce(aa.mora_tabla_amortizacion,0) > 0 
			and aa.id_estado_tabla_amortizacion <> 2
			and ee.id_participes = $_id_participe_garante
			and to_char(aa.fecha_tabla_amortizacion,'YYYYMM') <= '201910'"; 
			$id2		= "aa.id_tabla_amortizacion";
			$rsConsulta2= $Contribucion->getCondiciones($columnas2,$tablas2,$where2,$id2);

			if(!empty($rsConsulta2)){
				/** los valores aqui a procesar son de creditos en los que el paricipes esta como garante */
				/*$_id_participes_garantizados = 0;
				$funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
        		$parametrosDetalle = "";
				foreach( $rsConsulta2 as $res2){

					$_id_participes_gar = $res2->id_participes;
					$_id_creditos_gar   = $res2->id_creditos;
					$_valor_sistema_gar = $res2->total_valor_tabla_amortizacion;
					$_valor_final_gar   = $res2->total_valor_tabla_amortizacion;
					$_descripcion_gar	= 'CUOTA MENSUAL GARANTIZADO ['.$res2->cedula_participes.']';
					$_concepto_gar		= "";
					$parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participe_garante','$_id_creditos_gar','$_valor_sistema','$_valor_final',$_descripcion_gar,$_concepto_gar";
					$queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
					$Contribucion->llamarconsultaPG($queryFuncion);
					
					$error = pg_last_error();
					if( !empty($error) ){ break; throw new Exception('Error en la funcion de insertado detalle');}


				}

			}
	     
		}*/
	    
        return $_id_archivo_recaudaciones;
        
	}
	
	public function RecaudacionCombinada( $_id_entidad_patronal, $_anio, $_mes ){
	    //permite la creacion de un archivo donde constan de tanto aportes como de cuota de creditos
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    /** variable para tomar listado de participes */
	    $_array_participes = array();
	    
	    $_fecha_buscar = $_anio.$_mes;
	    $_usuario_usuarios = $_SESSION['usuario_usuarios'];
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    $formato_archivo_recaudaciones = "DESCUENTOS APORTES Y CREDITOS";
	    
	    /* c001 consulta de aportes */
	    $columnas1 = "aa.id_contribucion_tipo_participes, aa.valor_contribucion_tipo_participes,aa.sueldo_liquido_contribucion_tipo_participes,
                    aa.porcentaje_contribucion_tipo_participes, bb.id_contribucion_tipo, bb.nombre_contribucion_tipo,
	               cc.id_tipo_aportacion, cc.nombre_tipo_aportacion, dd.cedula_participes, dd.id_participes, dd.apellido_participes, dd.nombre_participes";
	    $tablas1   = "core_contribucion_tipo_participes aa
            	    INNER JOIN core_contribucion_tipo bb
            	    ON bb.id_contribucion_tipo = aa.id_contribucion_tipo
            	    INNER JOIN core_tipo_aportacion cc
            	    ON cc.id_tipo_aportacion = aa.id_tipo_aportacion
            	    INNER JOIN core_participes dd
            	    ON dd.id_participes = aa.id_participes
            	    INNER JOIN estado ee
            	    ON ee.id_estado = aa.id_estado";
	    $where1    = "bb.nombre_contribucion_tipo = 'Aporte Personal'
                    AND dd.id_estatus = 1
            	    AND ee.nombre_estado = 'ACTIVO'
            	    AND dd.id_entidad_patronal = '$_id_entidad_patronal'";
	    $id1       = "dd.id_participes";
	    $rsConsulta1 = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    /* c001 termina consulta de aportes */
	    
	    /* c002 consulta de cuotas creditos */
	    $columnas2 = "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,
            	    bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
            	    cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
	    $tablas2   = "core_tabla_amortizacion aa
            	    INNER JOIN core_creditos bb
            	    ON bb.id_creditos = aa.id_creditos
            	    INNER JOIN core_participes cc
            	    ON cc.id_participes = bb.id_participes
            	    INNER JOIN core_estado_creditos dd
            	    ON dd.id_estado_creditos = bb.id_estado_creditos";
	    $where2    = "aa.id_estatus = 1
            	    AND bb.id_estatus = 1
                    AND cc.id_estatus = 1
            	    AND aa.id_estado_tabla_amortizacion <> 2
                    AND bb.id_estado_creditos = 4
                    AND cc.id_entidad_patronal = $_id_entidad_patronal
            	    AND TO_CHAR(aa.fecha_tabla_amortizacion,'YYYYMM') = '$_fecha_buscar'
            	    AND dd.nombre_estado_creditos = 'Activo'";
	    $id2       = "cc.id_participes, aa.id_tabla_amortizacion";	    
	    $rsConsulta2 = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	    /* c002 termina consulta de cuotas creditos */
	    
	    /* c003 comienza conculta cuotas credito vencidas */
	    /** buscar cuotas en mora de los participes */
	    $columnas3 = "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,aa.mora_tabla_amortizacion,"
	        ." bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,"
            ." cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes";
        $tablas3   = "core_tabla_amortizacion aa"
            ." INNER JOIN core_creditos bb ON bb.id_creditos = aa.id_creditos"
            ." INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes and cc.id_estatus = bb.id_estatus"
            ." INNER JOIN core_estado_creditos dd ON dd.id_estado_creditos = bb.id_estado_creditos";
        $where3    = "bb.id_estatus = 1"
            ." AND upper(dd.nombre_estado_creditos) = 'ACTIVO'"
            ." AND coalesce(aa.mora_tabla_amortizacion,0) > 0"
            ." AND aa.id_estado_tabla_amortizacion <> 2"
            ." AND cc.id_entidad_patronal in ($_id_entidad_patronal)"
            ." AND to_char(aa.fecha_tabla_amortizacion,'YYYYMM') < '$_fecha_buscar'";
        $id3       = " cc.id_participes,bb.id_creditos,aa.id_tabla_amortizacion ";                                    
        $rsConsulta3 = $Contribucion->getCondiciones($columnas3, $tablas3, $where3, $id3);
	    /* c003 temina consulta cuotas credito vencidas */
        
        /** validacion para archivo vacio */
        if(sizeof($rsConsulta1) <= 0 && sizeof($rsConsulta2) <= 0 && sizeof($rsConsulta3) <= 0 ){
            return 0;
        }
	    
	    $funcionArchivo    = "core_ins_core_archivo_recaudaciones";
	    $parametrosArchivo = "'$_anio','$_mes','$_id_entidad_patronal',null,null,'$formato_archivo_recaudaciones','$_usuario_usuarios'";
	    
	    $queryFuncion  = $Contribucion->getconsultaPG($funcionArchivo, $parametrosArchivo);
	    $Resultado1    = $Contribucion->llamarconsultaPG($queryFuncion);
	    
	    $error = "";
	    $error = pg_last_error();
	    if( !empty($error) ){ throw new Exception('Error en la funcion de insertado');}
	    
	    $_id_archivo_recaudaciones  = $Resultado1[0];
	    
	    $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
	    $parametrosDetalle = "";
	    
	    /** RECORRIDO DE APORTES DE LOS PARTICIPES */
	    foreach ($rsConsulta1 as $res){
	        
	        $_id_participes = $res->id_participes;	        
	        $_valor_sistema = 0.00;
	        $_valor_final   = 0.00;
	        
	        /* validar tipo contribucion participe */
	        if( strtoupper($res->nombre_tipo_aportacion) == "VALOR"){
	            $_valor_sistema    = $res->valor_contribucion_tipo_participes;
	            $_valor_final      = $res->valor_contribucion_tipo_participes;
	        }
	        
	        if( strtoupper($res->nombre_tipo_aportacion) == "PORCENTAJE"){
	            $_sueldo   = (float)$res->sueldo_liquido_contribucion_tipo_participes;
	            $_porcentaje   = (float)$res->porcentaje_contribucion_tipo_participes;
	            $_valor_base   = ($_sueldo * $_porcentaje)/100;
	            $_valor_sistema = $_valor_base;
	            $_valor_final   = $_valor_base;
	        }
	        
	        $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes',null,'$_valor_sistema','$_valor_final','APORTES PERSONALES',''";
	        $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
	        $Contribucion->llamarconsultaPG($queryFuncion);
	        
	        $error = pg_last_error();
	        if( !empty($error) ){ break; throw new Exception('Error en la funcion de insertado detalle');}
	        
	    }
	    
        
        /** INSERTAR DETALLE DE CUOTAS A RECAUDAR */
        $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
        $parametrosDetalle = "";
        foreach ($rsConsulta2 as $res){
            
            $_id_participes = $res->id_participes;
            $_id_creditos   = $res->id_creditos;
            $_valor_sistema = $res->total_valor_tabla_amortizacion;
            $_valor_final   = $res->total_valor_tabla_amortizacion;
            
            $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes','$_id_creditos','$_valor_sistema','$_valor_final','CUOTA MENSUAL CRE[.$_id_creditos.]',''";
            $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
            $Contribucion->llamarconsultaPG($queryFuncion);
            
            $error = pg_last_error();
            if( !empty($error) ){ break; throw new Exception('Recaudacion cuota creditos Error en la funcion de insertado detalle');}
            
            /** para almacenar en un array lista de participes */
            array_push($_array_participes,$res->id_participes);
        }
	                                        
        /** GENERAR RECAUDACION CUOTAS VENCIDAS */
        $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
        $parametrosDetalle = "";
        foreach ($rsConsulta3 as $res){
            
            $_id_participes = $res->id_participes;
            $_id_creditos   = $res->id_creditos;
            $_valor_sistema = $res->total_valor_tabla_amortizacion;
            $_valor_final   = $res->total_valor_tabla_amortizacion;
            $_valor_mora    = $res->mora_tabla_amortizacion;
            $_valor_sumado  = $_valor_sistema + $_valor_mora;
            
            $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes','$_id_creditos','$_valor_sumado','$_valor_sumado','CUOTA MENSUAL VENCIDA ',''";
            $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
            $Contribucion->llamarconsultaPG($queryFuncion);
            
            $error = pg_last_error();
            if( !empty($error) ){ break; throw new Exception('Recaudacion cuota vencida creditos Error en la funcion de insertado detalle');}
            
        }
	                                        
        /** BEGIN PRUEBAS MULTIPLE DE ARRAY LISTA  */
        /** EMPIEZA RECAUDACION DE GARANTIZADOS */
        $_lista_string_participes = implode( "," ,$_array_participes);
        $_lista_string_participes = empty($_lista_string_participes) ? 0 : $_lista_string_participes;
	                                        
        $columnas4	= "aa.id_tabla_amortizacion,aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion,aa.mora_tabla_amortizacion,
    		bb.id_creditos, bb.numero_creditos, bb.id_tipo_creditos, bb.fecha_concesion_creditos,
    		cc.id_participes, cc.cedula_participes, cc.nombre_participes, cc.apellido_participes, ee.id_participes \"id_participes_garante\"";
        $tablas4	= "core_tabla_amortizacion aa
    		inner join core_creditos bb on bb.id_creditos = aa.id_creditos
    		inner join core_participes cc on cc.id_participes = bb.id_participes and cc.id_estatus = bb.id_estatus
    		inner join core_estado_creditos dd on dd.id_estado_creditos = bb.id_estado_creditos
    		inner join core_creditos_garantias ee on ee.id_creditos = bb.id_creditos";
        $where4		= "bb.id_estatus = 1
    		and upper(dd.nombre_estado_creditos) = 'ACTIVO'
    		and coalesce(aa.mora_tabla_amortizacion,0) > 0
    		and aa.id_estado_tabla_amortizacion <> 2
    		and ee.id_participes in ($_lista_string_participes)
    		and to_char(aa.fecha_tabla_amortizacion,'YYYYMM') <= '$_fecha_buscar'";
        $id4		= "aa.id_tabla_amortizacion";
        $rsConsulta4= $Contribucion->getCondiciones($columnas4,$tablas4,$where4,$id4);
        
        if(!empty($rsConsulta4)){
            /** los valores aqui a procesar son de creditos en los que el participe esta como garante */
            $funcionDetalle = "core_ins_core_archivo_recaudaciones_detalle";
            $parametrosDetalle = "";
            foreach( $rsConsulta4 as $res4){
                
                $_id_participes_garante = $res4->id_participes_garante;
                $_id_creditos_garantizado   = $res4->id_creditos;
                $_valor_sistema_garantizado	= (float)$res4->total_valor_tabla_amortizacion + (float)$res->mora_tabla_amortizacion;
                $_valor_final_garantizado   = $_valor_sistema_garantizado;
                $_descripcion_garantizado	= 'CUOTA MENSUAL GARANTIZADO ['.$res4->cedula_participes.']';
                $_concepto_gar		= "";
                $parametrosDetalle  = "'$_id_archivo_recaudaciones','$_id_participes_garante','$_id_creditos_garantizado','$_valor_sistema_garantizado','$_valor_final_garantizado',$_descripcion_garantizado,$_concepto_gar";
                $queryFuncion   = $Contribucion->getconsultaPG($funcionDetalle, $parametrosDetalle);
                $Contribucion->llamarconsultaPG($queryFuncion);
                
                $error = pg_last_error();
                if( !empty($error) ){ break; throw new Exception('Recaudacion cuota vencida garantizado. Error en la funcion de insertado detalle');}
                
            }
            
        }
	    
	    return $_id_archivo_recaudaciones;
	    
	    
	}
	
	public function ConsultaDatosArchivo(){	    
	        
        $Contribucion  = new CoreContribucionModel();
        /*toma de variables*/
        $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        $_busqueda_datos_generados = $_POST['busqueda'];
        $_id_archivo_recaudaciones = isset($_POST['id_archivo_recaudaciones']) ? $_POST['id_archivo_recaudaciones'] : 0;
        
        if( $_id_archivo_recaudaciones == 0 ){
            echo "<message> Archivo no Identificado, datos no se pueden mostrar <message>";
            exit();
        }  
        
        $columnas1 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
        	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
               aa.id_archivo_recaudaciones_detalle, aa.id_creditos, aa.descripcion_archivo_recaudaciones_detalle";
        $tablas1    = "core_archivo_recaudaciones_detalle aa
        	   INNER JOIN core_archivo_recaudaciones bb
        	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
        	   INNER JOIN core_participes cc
        	   ON cc.id_participes = aa.id_participes";
        $where1     = "cc.id_estatus = 1
        	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
        $id1        = "cc.id_participes";
        
        
        if( strlen($_busqueda_datos_generados) > 0 ){
            // metodos de busqueda
            $where1 .= " AND ( cc.cedula_participes ILIKE '$_busqueda_datos_generados%' )";
        }
        
        //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
        
        $html = "";
        $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
        $cantidadResult=(int)$resultSet[0]->total;
        
        /* para obtener Sumas*/
        $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas1, $where1);
        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
        $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas1, $where1);
        $_total_archivo_final   = $rsSumatoria2[0]->suma;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Contribucion->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        if($cantidadResult>0){
            
            $html.= "<table id='tbl_archivo_recaudaciones_insertados' class='table tablesorter table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;" colspan="8">'.$resultSet[0]->formato_archivo_recaudaciones.'</th>';
            $html.='</tr>';
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;">-</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            foreach ($resultSet as $res){
                $i++;
                
                /* se realiza una validacion : si el id de credito es null se activa para editar la fila*/
                $_html_boton_editar = "-";
                if( empty($res->id_creditos) ){
                    $_html_boton_editar = '<span class="">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span>';  
                }
                
                $html.='<tr>';
                $html.='<td style="font-size: 18px;">';
                $html.= $_html_boton_editar;
                $html.= '</td>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->descripcion_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
                
                $html.='</tr>';
            }
            
            $html.='</tbody>';
            /*para totalizar las filas*/
            $html.='<tfoot>';
            $html.='<tr>';
            $html.='<th colspan="5" ></th>';
            $html.='<th style="text-align: right"; >TOTALES</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
            $html.='</tr>';
            $html.='</tfoot>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscarDatosInsertados").'';
            $html.='</div>';
            
        }else{
            
            $html.= "<table id='tbl_archivo_recaudaciones_insertados' class='table tablesorter  table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	        
	    
	}
	
	public function ConsultaArchivoRecaudaciones(){
	    
	    $Contribucion = new CoreContribucionModel();
	    
	    if(!isset($_SESSION)){
	        session_start();
	    }
	    
	    /** buscar por el usuario que se encuentra logueado */
	    $_usuario_logueado = $_SESSION['usuario_usuarios'];
	    
	    /** datos de la vista */
	    $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $_busqueda             = $_POST['busqueda'];
	    $_anio_recaudaciones   = $_POST['anio_recaudacion'];
	    $_mes_recaudaciones    = $_POST['mes_recaudacion'];
	    $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	    $_id_descuentos_format = $_POST['id_descuentos_formatos'];
	    
	    
	    $columnas1 = " aa.id_descuentos_registrados_cabeza,aa.fecha_proceso_descuentos_registrados_cabeza,bb.id_entidad_patronal,bb.nombre_entidad_patronal,
    	    aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza,aa.usuario_descuentos_registrados_cabeza,aa.modificado,
    	    (SELECT COUNT(1) FROM core_descuentos_registrados_detalle_aportes where id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza) as \"cantidad_aportes\",
    	    (SELECT COUNT(1) FROM core_descuentos_registrados_detalle_creditos where id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza) as \"cantidad_creditos\"";
	    $tablas1   = "core_descuentos_registrados_cabeza aa
	       INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	    $where1    = " 1 = 1 ";
	    $id1       = " aa.fecha_proceso_descuentos_registrados_cabeza ";
	    
	    //aqui poner para filtrar si es por session
	   	    
	    /* PARA FILTROS DE CONSULTA */
	    if( $_id_entidad_patronal > 0 ){
	        $where1 .= " AND bb.id_entidad_patronal = $_id_entidad_patronal ";
	    }
	    if( $_anio_recaudaciones > 0 ){
	        $where1 .= " AND aa.year_descuentos_registrados_cabeza = $_anio_recaudaciones ";
	    }
	    if( $_mes_recaudaciones > 0 ){
	        $where1 .= " AND aa.mes_descuentos_registrados_cabeza = $_mes_recaudaciones ";
	    }
	    if( $_id_descuentos_format > 0 ){
	        $where1 .= " AND aa.id_descuentos_formatos = $_id_descuentos_format ";
	    }
	   
	    
	    $html = "";
	    $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$Contribucion->getCondicionesPagDesc($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    if($cantidadResult>0){
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Entidad Patronal</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Recaudacion</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Anio</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Modificado</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;" colspan="4" >Opciones</th>';	       
	        $html.='</tr>';
	        $html.='</thead>';
	        
	        $html.='<tbody>';
	        
	        $i=0;
	        foreach ($resultSet as $res){
	            $i++;
	            
	            // variable para uso en ciclo
	            $tipo_descuento = "";
	            $cod_tipo_descuento    = 0;
	            
	            if( $res->cantidad_aportes > 0 ){
	                $tipo_descuento = "APORTES";
	                $cod_tipo_descuento    = 1;
	            }elseif ( $res->cantidad_creditos > 0 ){
	                $tipo_descuento = "CREDITOS";
	                $cod_tipo_descuento    = 2;
	            }else{
	                $tipo_descuento = "N/D";
	                $cod_tipo_descuento    = 0;
	            }
	            
	            $cantidad_descuentos = ((int)$res->cantidad_aportes) + ((int)$res->cantidad_creditos);
	           	            
	            
	            $html.='<tr>';
	           
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->fecha_proceso_descuentos_registrados_cabeza.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	            $html.='<td style="font-size: 11px;">'.$tipo_descuento.'</td>';
	            $html.='<td style="font-size: 11px;">'.$cantidad_descuentos.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->year_descuentos_registrados_cabeza.'</td>';
	            $html.='<td style="font-size: 11px;">'.$this->devuelveMesNombre($res->mes_descuentos_registrados_cabeza).'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->usuario_descuentos_registrados_cabeza.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->modificado.'</td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="verDatosDescuentos(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Ver Detalles">
                        <i class="fa  fa-building-o" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="genArchivoDetallado(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Detallado">
                        <i class="fa fa-files-o text-info" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="genArchivoEntidad(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Entidad">
                        <i class="fa fa-file-text-o text-info" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="ValidarEdicionGenerados(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label " data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-edit text-warning" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="eliminarRegistro(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Eliminar">
                        <i class="fa fa-trash text-danger" aria-hidden="true" ></i>
                        </a></span>';
	            $html.='</td>';
	            
	            /*$html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                                <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                                href="#" class="btn btn-sm btn-default label label-warning">
                                <i class="fa fa-edit" aria-hidden="true" ></i>
                                </a></span></td>';*/
	            
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        /*para totalizar las filas*/
	        /*$html.='<tfoot>';
	        $html.='<tr>';
	        $html.='<th colspan="10" ></th>';
	        $html.='<th style="text-align: right"; >TOTALES</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.'SUMA'.'</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.'SUMA'.'</th>';
	        $html.='</tr>';
	        $html.='</tfoot>';*/
	        $html.='</table>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaArchivosRecaudacion").'';
	        $html.='</div>';
	        
	    }else{
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Entidad Patronal</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Tipo Recaudacion</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Anio</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Mes</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Modificado</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;" colspan="4" >Opciones</th>';
	        /*$html.='<th style="text-align: left;  font-size: 12px;">-</th>';
	         $html.='<th style="text-align: left;  font-size: 12px;">-</th>';
	         $html.='<th style="text-align: left;  font-size: 12px;">-</th>';*/
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='</tbody>';
	        $html.='</table>';
	    }
	    
	    $error = error_get_last();
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    if( !empty( $error )  ){
	        echo "ERROR EN GRAFICAION TABLA";
	        echo "\n",$error['message'];
	        return;
	    }
	    
	    echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	    
	}
	
	public function validaDatosGenerados(){
	    
	    /* este metodo solo da luz verde para continuar o  te detiene en el proceso */
	    
	    if(!isset($_SESSION)){
	        
	        session_start();
	    }
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    $Contribucion    = new CoreContribucionModel();
	    $nombre_controladores = "EditarArchRecaudacion";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if ( empty($resultPer) ){
	       echo "<message> Usuario no tiene permisos para editar archivo <message>";
	       exit();
	    }	    
	    
	    /* tomar datos de la vista */
	    $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	    
	    $columnas1 = " id_entidad_patronal, nombre_entidad_archivo_recaudaciones, ruta_entidad_archivo_recaudaciones, generado_entidad_archivo_recaudaciones,
            formato_archivo_recaudaciones";
	    $tablas1   = " public.core_archivo_recaudaciones ";
	    $where1    = " id_archivo_recaudaciones  = $_id_archivo_recaudaciones ";
	    $id1       = " id_archivo_recaudaciones";
	    $rsConsulta1   = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    /* tomo datos de la consulta */
	    $_generado_archivo = $rsConsulta1[0]->generado_entidad_archivo_recaudaciones;
	    //$_nombre_formato_archivo   = $rsConsulta1[0]->formato_archivo_recaudaciones;
	    
	    if( $_generado_archivo == "t"){
	        echo "<message> Datos no disponibles para Editar. Razon: Archivo generado para Entidad <message>";
	        exit();
	    }
	    
	    echo json_encode(array("mensaje"=>"OK"));
	    
	}
	
	public function ConsultaDatosEditar(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    /*toma de variables*/
	    $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $_busqueda_datos_generados = $_POST['busqueda'];
	    $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	    
	    	    
	    $columnas1 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
            	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
                   aa.id_archivo_recaudaciones_detalle, aa.id_creditos, aa.descripcion_archivo_recaudaciones_detalle";	    
	    $tablas1    = "core_archivo_recaudaciones_detalle aa
            	   INNER JOIN core_archivo_recaudaciones bb
            	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	   INNER JOIN core_participes cc
            	   ON cc.id_participes = aa.id_participes";	    
	    $where1     = "cc.id_estatus = 1
            	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";	    
	    $id1        = "cc.id_participes";
	    
	    
	    if( strlen($_busqueda_datos_generados) > 0 ){
	        // metodos de busqueda
	        $where1 .= " AND ( cc.cedula_participes ILIKE '$_busqueda_datos_generados%' )";
	    }
	    
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	    
	    $html = "";
	    $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    /* para obtener Sumas*/
	    $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas1, $where1);
	    $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	    $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas1, $where1);
	    $_total_archivo_final   = $rsSumatoria2[0]->suma;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$Contribucion->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    //echo $cantidadResult;
	    
	    if($cantidadResult>0){
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='table tablesorter  table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;" colspan="8">'.$resultSet[0]->formato_archivo_recaudaciones.'</th>';	       
	        $html.='</tr>';
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;">-</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        foreach ($resultSet as $res){
	            $i++;
	            
	            /* se realiza una validacion : si el id de credito es null se activa para editar la fila*/
	            $_html_boton_editar = "-";
	            if( empty($res->id_creditos) ){
	                $_html_boton_editar = '<span class="">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span>';
	            }
	            
	            $html.='<tr>';
	            $html.='<td style="font-size: 18px;">';
	            $html.=$_html_boton_editar;
	            $html.= '</td>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->descripcion_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
	            
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        /*para totalizar las filas*/
	        $html.='<tfoot>';
	        $html.='<tr>';
	        $html.='<th colspan="5" ></th>';
	        $html.='<th style="text-align: right"; >TOTALES</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
	        $html.='</tr>';
	        $html.='</tfoot>';
	        $html.='</table>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"mostarGenerados").'';
	        $html.='</div>';
	        
	    }else{
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='table tablesorter  table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='</tbody>';
	        $html.='</table>';
	    }
	    
	    echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	}
	
	public function eliminarRegistro(){
	    
	    if(!isset($_SESSION)){
	        
	        session_start();
	    }
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    $Contribucion    = new CoreContribucionModel();
	    $nombre_controladores = "EliminarArchRecaudacion";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $EntidadPatronal->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if ( empty($resultPer) ){
	        echo "<message> Usuario no tiene permisos para Eliminar registro <message>";
	        exit();
	    }
	    
	    /* tomar datos de la vista */
	    $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	    
	    $columnas1 = " id_estatus = 2";
	    $tablas1   = " public.core_archivo_recaudaciones ";
	    $where1    = " id_archivo_recaudaciones  = $_id_archivo_recaudaciones ";
	    $resultado = $Contribucion->ActualizarBy($columnas1, $tablas1, $where1);
	    
	    if( (int)$resultado == -1){
	        
            echo "<message> Problemas al eliminar registro <message>";
            exit();	        
	    }	    
	   
	    echo json_encode(array("mensaje"=>"OK"));
	    
	}
	
	public function ConsultaAportes(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    /*toma de variables*/
	    $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $_busqueda             = $_POST['busqueda'];
	    $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	    $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	    $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	    	    
	    
	    $_nombre_formato_recaudacion = "DESCUENTOS APORTES";
	    $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones";
	    $tablas1   = "core_archivo_recaudaciones";
	    $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudaciones
                    AND mes_archivo_recaudaciones = $_mes_recaudaciones
                    AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion' ";
	    $id1       = "id_archivo_recaudaciones";
	    
	    $rsConsulta1    = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    $_id_archivo_recaudaciones  = $rsConsulta1[0]->id_archivo_recaudaciones;
	    
	    
	    $columnas2 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
            	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
                   aa.id_archivo_recaudaciones_detalle";
	    
	    $tablas2    = "core_archivo_recaudaciones_detalle aa
            	   INNER JOIN core_archivo_recaudaciones bb
            	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	   INNER JOIN core_participes cc
            	   ON cc.id_participes = aa.id_participes";
	    
	    $where2     = "cc.id_estatus = 1
            	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	    
	    $id2        = "cc.id_participes";
	    
	    
	    if(!empty($_busqueda)){
	        // metodos de busqueda
	        $where2 .= " AND ( cc.cedula_participes ILIKE '$_busqueda%' )";
	    }
	    
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	    
	    $html = "";
	    $resultSet=$Contribucion->getCantidad("*", $tablas2, $where2);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    /* para obtener Sumas*/
	    $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas2, $where2);
	    $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	    $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas2, $where2);
	    $_total_archivo_final   = $rsSumatoria2[0]->suma;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$Contribucion->getCondicionesPag($columnas2, $tablas2, $where2, $id2, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    if($cantidadResult>0){
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='table table-hover'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        $_tipo_recaudacion    = "";
	        foreach ($resultSet as $res){
	            $i++;
	            
	            $_tipo_recaudacion  = "Aportes Personales";
	            
	            $html.='<tr>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                                <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                                href="#" class="btn btn-sm btn-default label label-warning">
                                <i class="fa fa-edit" aria-hidden="true" ></i>
                                </a></span></td>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
	            $html.='<td style="font-size: 11px;">'.$_tipo_recaudacion.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
	            
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        /*para totalizar las filas*/
	        $html.='<tfoot>';
	        $html.='<tr>';
	        $html.='<th colspan="7" ></th>';
	        $html.='<th style="text-align: right"; >TOTALES</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
	        $html.='</tr>';
	        $html.='</tfoot>';
	        $html.='</table>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscaAportesCreditos").'';
	        $html.='</div>';
	        
	    }else{
	        
	        $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='</tbody>';
	        $html.='</table>';
	    }
	    
	    echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	}
	
	public function ConsultaAportesCreditos(){
	    
	   $Contribucion  = new CoreContribucionModel();
	   /*toma de variables*/
	   $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	   $_busqueda             = $_POST['busqueda'];
	   $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	   $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	   $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	   
	   
	   $_nombre_formato_recaudacion = "DESCUENTOS CREDITOS";
	   $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones";
	   $tablas1   = "core_archivo_recaudaciones";
	   $where1    = "id_entidad_patronal = $_id_entidad_patronal AND anio_archivo_recaudaciones = $_anio_recaudaciones
                    AND mes_archivo_recaudaciones = $_mes_recaudaciones 
                    AND formato_archivo_recaudaciones = '$_nombre_formato_recaudacion' ";
	   $id1       = "id_archivo_recaudaciones";
	   
	   $rsConsulta1    = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);	   
	   $_id_archivo_recaudaciones  = $rsConsulta1[0]->id_archivo_recaudaciones;
	   
	   
	   $columnas2 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
            	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
            	   dd.id_creditos, dd.monto_neto_entregado_creditos, ee.id_tipo_creditos, ee.nombre_tipo_creditos,
                   aa.id_archivo_recaudaciones_detalle";
	   
	   $tablas2    = "core_archivo_recaudaciones_detalle aa
            	   INNER JOIN core_archivo_recaudaciones bb
            	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	   INNER JOIN core_participes cc
            	   ON cc.id_participes = aa.id_participes
            	   INNER JOIN core_creditos dd
            	   ON dd.id_creditos = aa.id_creditos
            	   INNER JOIN core_tipo_creditos ee
            	   ON ee.id_tipo_creditos = dd.id_tipo_creditos";
	   
	   $where2     = "cc.id_estatus = 1
            	   AND dd.id_estatus = 1
            	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	   
	   $id2        = "cc.id_participes";
	   	   
	   
	   if(!empty($_busqueda)){
	       // metodos de busqueda
	       $where2 .= " AND ( cc.cedula_participes ILIKE '$_busqueda%' )";
	   }
	   
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	   
        $html = "";
        $resultSet=$Contribucion->getCantidad("*", $tablas2, $where2);
        $cantidadResult=(int)$resultSet[0]->total;
        
        /* para obtener Sumas*/
        $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas2, $where2);
        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
        $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas2, $where2);
        $_total_archivo_final   = $rsSumatoria2[0]->suma;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Contribucion->getCondicionesPag($columnas2, $tablas2, $where2, $id2, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        if($cantidadResult>0){
            
            //table table-border table-striped mb-0
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example' cellspacing='0'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            $_tipo_recaudacion    = "";
            foreach ($resultSet as $res){
                $i++;
                
                $_tipo_recaudacion  = "CREDITO - ".$res->nombre_tipo_creditos;
                
                $html.='<tr>';
                $html.='<td style="font-size: 18px;">';
                $html.='<span class="pull-right ">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span></td>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
                $html.='<td style="font-size: 11px;">'.$_tipo_recaudacion.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
                
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            /*para totalizar las filas*/
            $html.='<tfoot>';
            $html.='<tr>';
            $html.='<th colspan="7" ></th>';
            $html.='<th style="text-align: right"; >TOTALES</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
            $html.='</tr>';
            $html.='</tfoot>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscaAportesCreditos").'';
            $html.='</div>';
            
        }else{
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	}

	public function ConsultarAportesGeneral(){
		$Contribucion  = new CoreContribucionModel();
	   /*toma de variables*/
	   $page                  = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	   $_busqueda             = $_POST['busqueda'];
	   $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	   $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	   $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	   
		$columnas1 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
			bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
			aa.id_creditos, aa.id_archivo_recaudaciones_detalle, aa.descripcion_archivo_recaudaciones_detalle";
		$tablas1   = "core_archivo_recaudaciones_detalle aa
			inner join core_archivo_recaudaciones bb on bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
			inner join core_participes cc on cc.id_participes = aa.id_participes";
		$where1    = " 1=1 AND cc.id_estatus = 1 AND bb.id_entidad_patronal = $_id_entidad_patronal 
			AND bb.anio_archivo_recaudaciones = $_anio_recaudaciones
			AND bb.mes_archivo_recaudaciones = $_mes_recaudaciones ";
		$id1       = "aa.id_archivo_recaudaciones";
		
		$rsConsulta1    = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);	   
		$_id_archivo_recaudaciones  = $rsConsulta1[0]->id_archivo_recaudaciones;	  
	    	   
	   
	   if(strlen($_busqueda) > 0 ){
	       // metodos de busqueda
	       $where1 .= " AND ( cc.cedula_participes ILIKE '$_busqueda%' )";
	   }
	   	   
        $html = "";
        $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
        $cantidadResult=(int)$resultSet[0]->total;
        
        /* para obtener Sumas*/
        $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas1, $where1);
        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
        $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas1, $where1);
        $_total_archivo_final   = $rsSumatoria2[0]->suma;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$Contribucion->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
        $total_pages = ceil($cantidadResult/$per_page);
        
        if($cantidadResult>0){
            
            //table table-border table-striped mb-0
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='table tablesorter table-striped table-bordered dt-responsive nowrap ' cellspacing='0'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
			$html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
			$html.='<th style="text-align: left;  font-size: 12px;">Descripcion</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            $_tipo_recaudacion    = "";
            foreach ($resultSet as $res){
                $i++;
                
                $_tipo_recaudacion  = (empty($res->id_creditos)) ? "Aportes Personales" : "NUM CREDITO - ".$res->id_creditos;
                
                $html.='<tr>';
                $html.='<td style="font-size: 18px;">';
                $html.='<span class="pull-right ">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span></td>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
				$html.='<td style="font-size: 11px;">'.$_tipo_recaudacion.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->descripcion_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
                
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            /*para totalizar las filas*/
            $html.='<tfoot>';
            $html.='<tr>';
            $html.='<th colspan="8" ></th>';
            $html.='<th style="text-align: right"; >TOTALES</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
            $html.='</tr>';
            $html.='</tfoot>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscaAportesCreditos").'';
            $html.='</div>';
            
        }else{
            
            $html.= "<table id='tbl_archivo_recaudaciones' class='table tablesorter table-striped table-bordered dt-responsive nowrap '>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Descuento</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Metodo Descuento</th>';
			$html.='<th style="text-align: left;  font-size: 12px;">Descripcion</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	}		
	
	/** BEGIN GENERACION DE ARCHIVOS TXT */
	
	public function genArchivoDetallado(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    try {
	        
	        /*tomar datos de la vista*/
	        
	        $_mes_recaudaciones   = 0;
	        $_anio_recaudaciones  = 0;	        
	        $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	        $tipo_descuento        = $_POST['tipo_descuento'];	
	        
	        if( error_get_last() ){
	            throw new Exception( "Datos no recibidos" );
	        }
	        
	        $col1  = " aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza, bb.id_entidad_patronal, bb.nombre_entidad_patronal, 
                bb.codigo_entidad_patronal ";
	        $tab1  = " core_descuentos_registrados_cabeza aa
	           INNER JOIN core_entidad_patronal bb	ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	        $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id1   = " aa.id_descuentos_registrados_cabeza ";
	        
	        $rsConsulta1    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        //$id_entidad_patronal       = $rsConsulta1[0]->id_entidad_patronal;
	        $nombre_entidad_patronal   = $this->limpiarCaracteresEspeciales( $rsConsulta1[0]->nombre_entidad_patronal );
	        $codigo_entidad_patronal   = $rsConsulta1[0]->codigo_entidad_patronal;
	        $anio_descuentos_cabeza    = $rsConsulta1[0]->year_descuentos_registrados_cabeza;
	        $mes_descuentos_cabeza     = $rsConsulta1[0]->mes_descuentos_registrados_cabeza;
	        
	        
	      
	        $rsData    = array();
	        $nameTipoDescuento = "";
	        if( $tipo_descuento == "1" ){
	            
	            $nameTipoDescuento = "APORTES";
	            
	            $col1  = " aa.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.aporte_personal_descuentos_registrados_detalle_aportes,
	               COALESCE(aa.valor_usuario_descuentos_registrados_detalle_aportes,0) as valor_usuario_descuentos_registrados_detalle_aportes ";
	            $tab1  = " core_descuentos_registrados_detalle_aportes aa
	               INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	            $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id1   = " aa.id_descuentos_registrados_detalle_aportes ";
	            	            
	            $rsData    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	            
	        }elseif( $tipo_descuento == "2" ){
	            
	            $nameTipoDescuento = "CREDITOS";
	            
	            $col2  = " bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.cuota_descuentos_registrados_detalle_creditos,
	               cc.id_creditos, cc.numero_creditos";
	            $tab2  = " core_descuentos_registrados_detalle_creditos aa
    	            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
    	            INNER JOIN core_creditos cc ON cc.id_creditos = aa.id_creditos";
	            $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id2   = " aa.id_descuentos_registrados_detalle_creditos";
	            
	            $rsData    = $recaudaciones->getCondiciones($col2, $tab2, $whe2, $id2);
	           	            
	        }else{
	            throw new Exception( "Tipo de descuento no valido" );
	        }
	        
	        /**generar datos de archivo plano*/
	        $mes_descuentos_cabeza_archivo = str_pad($mes_descuentos_cabeza, 2, "0",STR_PAD_LEFT);
	        $subnameFile   = "_DET".$nameTipoDescuento;
	        $_TXT_RECAUDACIONES = $this->obtienePath( $nombre_entidad_patronal.$subnameFile, $anio_descuentos_cabeza, $mes_descuentos_cabeza_archivo, "ARCHIVOSENVIAR");            
            $nameFileRecaudaciones  = $_TXT_RECAUDACIONES['nombre'];
            $rutaFileRecaudaciones  = $_TXT_RECAUDACIONES['ruta'];
            
            /* PARA REALIZAR LOS DETALLES DE ARCHIVO PLANO*/
            $_cantidad_registros	= sizeof($rsData);
            $_fecha_achivo	= $this->returnDateLastDay($anio_descuentos_cabeza, $mes_descuentos_cabeza);
            $_sumatoria_archivo	= 0.00;
            
            $databody	= "";
            $numero = 0;
            foreach( $rsData as $res){
                
                $numero += 1;                
                 
                $cedula_participe      = $res->cedula_participes;
                $apellido_participe    = $res->apellido_participes;
                $nombre_participe      = $res->nombre_participes;      
                
                if( $tipo_descuento == "1"){
                    $tipo_contribucion     = "APORTES";
                    $valor_descuento       = $res->aporte_personal_descuentos_registrados_detalle_aportes;
                    $total_descuento       = $res->valor_usuario_descuentos_registrados_detalle_aportes;
                    $concepto_recaudacion  = "DESCUENTOS APORTES";
                    
                }elseif( $tipo_descuento == "2" ){
                    $tipo_contribucion     = "CREDITOS";
                    $valor_descuento       = $res->cuota_descuentos_registrados_detalle_creditos;
                    $total_descuento       = $valor_descuento;
                    $concepto_recaudacion  = "DESCUENTOS CREDITOS";
                }
                
                $_sumatoria_archivo += $total_descuento; //variable para obtener la suma
                
                $databody.=$numero.";".$concepto_recaudacion.";".$cedula_participe.";".$apellido_participe." ".$nombre_participe.";";
                $databody.=$valor_descuento.";".$total_descuento.";".PHP_EOL;
                
            }
            
            /* estructurar el archivo */
            $datahead	= $tipo_contribucion."\t".$codigo_entidad_patronal."\t".$_fecha_achivo."\t".$_cantidad_registros."\t".$_sumatoria_archivo.PHP_EOL;
            $datahead	.= 'NUMERO'.";".'CONCEPTO'.";".'CEDULA'.";".'NOMBRE'.";".'DESCUENTO'.";".'TOTAL DESCUENTO'.";".PHP_EOL;
            
            /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */ 
            $archivo = fopen($rutaFileRecaudaciones, 'w');
            fwrite($archivo, $datahead.$databody);
            fclose($archivo);
            
            if(!empty(error_get_last())){
                throw new Exception('Archivo no generado');
            }
            
            echo json_encode(array('estatus'=>"OK",'mensaje'=>'Archivo TXT generado'));
            exit();
            
	        	        
	    } catch (Exception $e) {
	        $buffer    = error_get_last();
	        echo '<message>'.$e->getMessage().' <--> '.$buffer['message'].' <message>';
	        exit();
	    }
	    	    
	}
	
	public function genArchivoEntidad(){
	    
	    $Contribucion = new CoreContribucionModel();
	    
	    try {
	        
	        /*tomar datos de la vista*/
	        $_id_entidad_patronal = 0;
	        $_mes_recaudaciones   = 0;
	        $_anio_recaudaciones  = 0;
	        $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	        $_subnombre_archivo = "";
	        $_formato_archivo_recaudaciones = "";
	        
	        $columnas1 = " formato_archivo_recaudaciones, id_entidad_patronal, anio_archivo_recaudaciones, mes_archivo_recaudaciones";
	        $tablas1   = " public.core_archivo_recaudaciones";
	        $where1    = " id_archivo_recaudaciones = $_id_archivo_recaudaciones ";
	        $id1       = " id_archivo_recaudaciones";
	        $rsConsulta1   = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        /* tomar datos de consulta1 */
	        $_id_entidad_patronal = $rsConsulta1[0]->id_entidad_patronal;
	        $_anio_recaudaciones    = $rsConsulta1[0]->anio_archivo_recaudaciones;
	        $_mes_recaudaciones     = $rsConsulta1[0]->mes_archivo_recaudaciones;
	        $_formato_archivo_recaudaciones = $rsConsulta1[0]->formato_archivo_recaudaciones;
	        $_subnombre_archivo = "_ENT".$this->returnSubfijoFormato( $_formato_archivo_recaudaciones );
	        
	        /**DATOS DE ENTIDAD PATRONAL*/
	        /* buscar nombre entidad patronal */
	        $columnas2 = "id_entidad_patronal, nombre_entidad_patronal, codigo_entidad_patronal";
	        $tablas2   = "core_entidad_patronal";
	        $where2    = "id_entidad_patronal = $_id_entidad_patronal";
	        $id2       = "id_entidad_patronal";
	        $rsConsulta2   = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);
	        $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta2[0]->nombre_entidad_patronal);
	        $_codigo_entidad_patronal  = $rsConsulta2[0]->codigo_entidad_patronal;
	        
	        if( empty( $rsConsulta1) ){ echo "archivo vacio"; }
	        
	        // generar archivo txt
	        $_TXT_RECAUDACIONES = $this->obtienePath($_nombre_entidad_patronal.$_subnombre_archivo, $_anio_recaudaciones, $_mes_recaudaciones, "ARCHIVOSENVIAR");
	        $_nombre_archivo_recaudaciones = $_TXT_RECAUDACIONES['nombre'];
	        $_ruta_archivo_recaudaciones   = $_TXT_RECAUDACIONES['ruta'];
	        
	        $columnas3 = "bb.formato_archivo_recaudaciones, aa.descripcion_archivo_recaudaciones_detalle, cc.id_participes, cc.cedula_participes, cc.apellido_participes,
        	    cc.nombre_participes, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
        	    bb.anio_archivo_recaudaciones, bb.mes_archivo_recaudaciones, dd.id_creditos, dd.numero_creditos";
	        $tablas3   = "core_archivo_recaudaciones_detalle aa
        	    INNER JOIN core_archivo_recaudaciones bb ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
        	    INNER JOIN core_participes cc ON cc.id_participes = aa.id_participes
        	    LEFT JOIN core_creditos dd ON dd.id_creditos = aa.id_creditos";
	        $where3    = "cc.id_estatus = 1
        	    AND dd.id_estatus = 1
        	    AND bb.id_archivo_recaudaciones IN ($_id_archivo_recaudaciones) ";
	        $id3       = "cc.id_participes";
	        $rsConsulta3 = $Contribucion->getCondiciones($columnas3, $tablas3, $where3, $id3);
	        
	        /* aqui hacer calculos para sumatorias y numero de lineas */
	        $_cantidad_registros	= sizeof($rsConsulta3);
	        $_fecha_achivo	= $this->returnDateLastDay($_anio_recaudaciones, $_mes_recaudaciones);
	        $_sumatoria_archivo	= 0.00;
	        
	        /* para generar grupos */
	        $_grupo_valor_descuento = 0.0;
	        $_ultima_fila = $_cantidad_registros-1;
	        
	        $databody	= "";
	        $numero = 0;
			$tipo_contribucion  = "";
	        for( $i=0; $i<$_cantidad_registros; $i++){
	            	            
	            $id_participes         = $rsConsulta3[$i]->id_participes;
	            $tipo_contribucion     = $rsConsulta3[$i]->formato_archivo_recaudaciones;
	            $cedula_participe      = $rsConsulta3[$i]->cedula_participes;
	            $apellido_participe    = $rsConsulta3[$i]->apellido_participes;
	            $nombre_participe      = $rsConsulta3[$i]->nombre_participes;
	            $total_descuento       = $rsConsulta3[$i]->valor_final_archivo_recaudaciones_detalle;
	            
	            $_grupo_valor_descuento += (float)$total_descuento;
	            $_sumatoria_archivo += (float)$total_descuento;	            
	            
	            if( $i < $_ultima_fila ){
	                
	                if( $id_participes != $rsConsulta3[$i+1]->id_participes){
	                    
	                    $numero++;
	                    $databody.=  $numero.";".trim($cedula_participe).";".trim($apellido_participe)." ".trim($nombre_participe).";".$_grupo_valor_descuento.PHP_EOL;
	                    $_grupo_valor_descuento=0.0;
	                }
	                
	            }
	            if( $i == $_ultima_fila ){	                
	                $numero++;
                    $databody.=  $numero.";".trim($cedula_participe).";".trim($apellido_participe)." ".trim($nombre_participe).";".$_grupo_valor_descuento.PHP_EOL;
                    $_grupo_valor_descuento=0.0;
	                
	            }
	            
	            
	        }
	        
	        /* estructurar el archivo */
	        $datahead	= $tipo_contribucion."\t".$_codigo_entidad_patronal."\t".$_fecha_achivo."\t".$numero."\t".$_sumatoria_archivo.PHP_EOL;
	        $datahead	.= 'NUMERO'.";".'CEDULA'.";".'NOMBRE'.";".'TOTAL DESCUENTO'.";".PHP_EOL;
			
			//echo $datahead.$databody;
	        
	        /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
	        $archivo = fopen($_ruta_archivo_recaudaciones, 'w');
	        fwrite($archivo, $datahead.$databody);
	        fclose($archivo);
	        
	        $error = error_get_last();
	        if(!empty($error)){
	            throw new Exception('Archivo no generado');
	        }
	        
	        //para actualizacion del archivo
	        $actColumnas = "generado_entidad_archivo_recaudaciones = 't',
					ruta_entidad_archivo_recaudaciones = '$_ruta_archivo_recaudaciones',
					nombre_entidad_archivo_recaudaciones = '$_nombre_archivo_recaudaciones'";
	        $actTablas = "core_archivo_recaudaciones";
	        $actWhere = "id_archivo_recaudaciones = $_id_archivo_recaudaciones ";
	        
	        $Contribucion->ActualizarBy($actColumnas, $actTablas, $actWhere);
	        
	        echo json_encode(array('mensaje'=>'archivo generado'));
	        exit();
	        
	    } catch (Exception $e) {
	        echo '<message>'.$e->getMessage().' <message>';
	        exit();
	    }
	    
	}
	
	public function descargarArchivo(){
	    
	    $_id_archivo_recaudaciones = $_POST['id_archivo_recaudaciones'];
	    $_tipo_archivo_recaudaciones   = $_POST['tipo_archivo_recaudaciones'];
		
	    $Participes = new ParticipesModel();
	    
	    /* consulta para traer datos del archivo de recaudacones*/
	    $columnas1 = "id_archivo_recaudaciones, nombre_archivo_recaudaciones, ruta_archivo_recaudaciones,nombre_entidad_archivo_recaudaciones, ruta_entidad_archivo_recaudaciones";
	    $tablas1   = "core_archivo_recaudaciones";
	    $where1 = " id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	    $id1 = "id_archivo_recaudaciones";
	    
	    $rsConsulta1 = $Participes->getCondiciones($columnas1,$tablas1,$where1,$id1);
	    
	    $nombre_archivo    = "";
	    $ruta_archivo      = "";
		
		//if(!empty($rsConsulta1)) echo " la informacion esta lleno";
	    
	    if( $_tipo_archivo_recaudaciones  == "detalle" ){
	        $nombre_archivo    = $rsConsulta1[0]->nombre_archivo_recaudaciones;
	        $ruta_archivo      = $rsConsulta1[0]->ruta_archivo_recaudaciones;
	    }else{
	        $nombre_archivo    = $rsConsulta1[0]->nombre_entidad_archivo_recaudaciones;
	        $ruta_archivo      = $rsConsulta1[0]->ruta_entidad_archivo_recaudaciones;
	    }	   
	    
		//print_r($rsConsulta1);
		//echo "\n";
	    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	    $ubicacion = $ubicacionServer.$ruta_archivo;
	    
	    
	    // Define headers
	    header("Content-disposition: attachment; filename=$nombre_archivo");
	    header("Content-type: MIME");
	    ob_clean();
	    flush();
	    // Read the file
		//echo $ubicacion;
		//print_r($_POST);
		//echo  "******llego--",$_tipo_archivo_recaudaciones,"***" ;
		//echo "parametro id ---",$_id_archivo_recaudaciones,"**";
	    readfile($ubicacion);
	    exit;
	    
	}
	
	/** END GENERACION DE ARCHIVOS TXT */
	
	public function BuscarDatosArchivo(){
	    
	    $Contribucion  = new CoreContribucionModel();
	    
	    /* tomar datos de la web */
	    
	    $_id_archivo_recaudaciones_detalle = $_POST['id_archivo_rcaudaciones_detalle'];
	    
	    $columnas1 = "aa.id_archivo_recaudaciones_detalle, aa.valor_sistema_archivo_recaudaciones_detalle, 
                    aa.valor_final_archivo_recaudaciones_detalle, bb.formato_archivo_recaudaciones, cc.cedula_participes, 
                    cc.nombre_participes, cc.apellido_participes";
	    $tablas1   = "core_archivo_recaudaciones_detalle aa
            	    INNER JOIN core_archivo_recaudaciones bb
            	    ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
            	    INNER JOIN core_participes cc
            	    ON cc.id_participes = aa.id_participes";
	    $where1    = "cc.id_estatus = 1
	                AND aa.id_archivo_recaudaciones_detalle = '$_id_archivo_recaudaciones_detalle'";
	    $id1       = "aa.id_archivo_recaudaciones_detalle ";
	    
	    $rsConsulta1   = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    
	    if(empty($rsConsulta1)){
	        
	        echo json_encode(array('rsRecaudaciones'=>null));
	    }else{
	        echo json_encode(array('rsRecaudaciones'=>$rsConsulta1));
	    }
	}
	
	public function editAporte(){
	    
	    session_start();
	    $error="";
	    $respuesta=array();
	    try {
	        
	        $Participes = new ParticipesModel();
	        $_id_archivo_recaudacion_detalle   = $_POST['id_archivo_recaudaciones_detalle'];
	        $_valor_archivo_recaudacion        = $_POST['valor_final_archivo_recaudaciones_detalle'];
	        
	        $error=error_get_last();
	        if(!empty($error)){    throw new Exception("Variables no definidas"); }
	        
	        $columnas1 = " bb.generado_archivo_recaudaciones, bb.formato_archivo_recaudaciones";
	        $tablas1   = " core_archivo_recaudaciones_detalle aa
            	        INNER JOIN core_archivo_recaudaciones bb
            	        ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones";
	        $where1    = " aa.id_archivo_recaudaciones_detalle = $_id_archivo_recaudacion_detalle ";
	        $id1       = " aa.id_archivo_recaudaciones_detalle";
	        
	        $rsConsulta1   = $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);	
	            
            $error=pg_last_error();
            if(!empty($error)){ throw new Exception("Fila no reconocida"); }
            
            if( $rsConsulta1[0]->generado_archivo_recaudaciones == 't' ){ throw new Exception("No se puede modificar archivo ya generado"); }
                
            if( sizeof($rsConsulta1) > 0 ){
                
                $colval = "valor_final_archivo_recaudaciones_detalle = '$_valor_archivo_recaudacion' ";
                $tabla = "core_archivo_recaudaciones_detalle";
                $where = "id_archivo_recaudaciones_detalle = '$_id_archivo_recaudacion_detalle'";
                
                $resultado = $Participes->ActualizarBy($colval, $tabla, $where);
                                
                if((int)$resultado < 0){throw new Exception('Error Actualizar Fila Seleccionada');}
                    
                $respuesta['respuesta']=1;
                $respuesta['mensaje']="Valor Aporte Actualizado";
                    
            }else{
                
                $respuesta['respuesta']=1;
                $respuesta['mensaje'] = "Archivo generado no puede modificar el archivo";
            }
            
            
            echo json_encode($respuesta);
	                
	                
	    } catch (Exception $e) {
	        echo '<message> Error Recaudacion \n '.$e->getMessage().'<message>';
	    }
	}
	
	public function GeneraArchivo(){
	    
		$Contribucion  = new CoreContribucionModel();
		$_subnombre_archivo	= ""; //variable para distiguir nombre de archivo txt
		
		
	    
	    try {
	        
	        $_id_entidad_patronal  = $_POST['id_entidad_patronal'];
	        $_mes_recaudaciones    = $_POST['mes_recaudaciones'];
	        $_anio_recaudaciones   = $_POST['anio_recaudaciones'];
	        $_formato_recaudacion  = $_POST['formato_recaudaciones'];
	        
	        $error = error_get_last();
	        if(!empty($error)){ throw new Exception('Variables no definidos');}
	        
	        /**DATOS DE ENTIDAD PATRONAL*/
	        /* buscar nombre entidad patronal */
	        $columnas1 = "id_entidad_patronal, nombre_entidad_patronal, codigo_entidad_patronal";
	        $tablas1   = "core_entidad_patronal";
	        $where1    = "id_entidad_patronal = $_id_entidad_patronal";
	        $id1       = "id_entidad_patronal";
	        $rsConsulta1   = $Contribucion->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        $_nombre_entidad_patronal  = $this->limpiarCaracteresEspeciales($rsConsulta1[0]->nombre_entidad_patronal);
	        $_codigo_entidad_patronal  = $rsConsulta1[0]->codigo_entidad_patronal;
	       
	        	        
	        /*configurar estructura mes de consulta*/
	        $_mes_recaudaciones = str_pad($_mes_recaudaciones, 2, "0", STR_PAD_LEFT);
	        
	        $_nombre_formato_recaudacion = ($_formato_recaudacion == 1) ? "DESCUENTOS APORTES" : (($_formato_recaudacion == 2) ? "DESCUENTOS CREDITOS" : "DESCUENTOS APORTES Y CREDITOS");
	        
	        $_nombre_formato_recaudacion = "";
	        //diferenciar el tipo de recaudacion que va a realizar
	        switch ( $_formato_recaudacion ){
	            
	            case '1':
	                //para cuando sea para cuenta individual
					$_nombre_formato_recaudacion = "DESCUENTOS APORTES";
					$_subnombre_archivo	="aportes";	                
	                break;
	            case '2':
	                /*para realizar recaudacion por creditos*/	                
					$_nombre_formato_recaudacion = "DESCUENTOS CREDITOS";
					$_subnombre_archivo	="creditos";
					break;
				case '3':
					/*para realizar recaudacion general*/	                
					$_nombre_formato_recaudacion = "";
					$_subnombre_archivo	="aporte_creditos";
					break;
	            default:
	                $_nombre_formato_recaudacion = "DEFAULT";//se trata para que no encuentre datos
	                break;
			}
			
			/** validacion si es general */
			if($_nombre_formato_recaudacion == ""){
				/** variables */
				$_array_id_archivo_recaudaciones = array();
				/*consulta para agrupar sus dos aportes*/
				$_query_consulta1	= "SELECT id_archivo_recaudaciones
					FROM core_archivo_recaudaciones
					WHERE id_entidad_patronal = $_id_entidad_patronal
					AND anio_archivo_recaudaciones = $_anio_recaudaciones
					AND mes_archivo_recaudaciones = $_mes_recaudaciones
					GROUP BY id_archivo_recaudaciones;";
				
				$rsConsultaQuery1 = $Contribucion->enviaquery($_query_consulta1);
				if(!empty($rsConsultaQuery1)){
					foreach($rsConsultaQuery1 as $res){
						array_push($_array_id_archivo_recaudaciones,$res->id_archivo_recaudaciones);
					}
				}
				
				// generar archivo txt 
				$_TXT_RECAUDACIONES = $this->obtienePath($_nombre_entidad_patronal.$_subnombre_archivo, $_anio_recaudaciones, $_mes_recaudaciones, "ARCHIVOSENVIAR");
				$_nombre_archivo_recaudaciones = $_TXT_RECAUDACIONES['nombre'];
				$_ruta_archivo_recaudaciones   = $_TXT_RECAUDACIONES['ruta'];

				$_lista_id_archivo_recaudaciones = implode(",",$_array_id_archivo_recaudaciones); //se genera una cadena para pasar a la consulta

				/*buscar datos de vista para generar el archivo*/
				$columnas2 = "id_archivo_recaudaciones,id_participes, formato_archivo_recaudaciones, cedula_participes, nombre_participes, apellido_participes,
				valor_recaudaciones, sueldo_liquido_contribucion_tipo_participes, anio_archivo_recaudaciones, mes_archivo_recaudaciones";
				$tablas2   = "public.vw_archivo_recaudaciones";
				$where2    = "id_archivo_recaudaciones in ($_lista_id_archivo_recaudaciones) ";
				$id2       = "id_participes";
				$rsConsulta2 = $Contribucion->getCondiciones($columnas2, $tablas2, $where2, $id2);

				/* aqui hacer calculos para sumatorias y numero de lineas */
				$_cantidad_registros	= sizeof($rsConsulta2);
				$_fecha_achivo	= date('30/m/Y');
				$_sumatoria_archivo	= 0.00;
			
				$databody	= "";
				$numero = 0;
				foreach($rsConsulta2 as $res){
					$numero += 1;
					$tipo_contribucion     = $res->formato_archivo_recaudaciones;
					$cedula_participe      =  $res->cedula_participes;
					$apellido_participe    =  $res->apellido_participes;
					$nombre_participe      =  $res->nombre_participes;
					$sueldo_participe      =  $res->sueldo_liquido_contribucion_tipo_participes;
					$valor_descuento       =  $res->valor_recaudaciones;
					$total_descuento       =  $res->valor_recaudaciones;
					$anio_recaudacion      =  $res->anio_archivo_recaudaciones;
					$mes_recaudacion       =  $res->mes_archivo_recaudaciones;

					$_sumatoria_archivo += $total_descuento; //variable para obtener la suma
					
					$databody.=$numero.";".$tipo_contribucion.";".$cedula_participe.";".$apellido_participe." ".$nombre_participe.";";
					$databody.=$sueldo_participe.";".$valor_descuento.";".$total_descuento.";".$anio_recaudacion.";".$mes_recaudacion.PHP_EOL;
					
				}
				
				/* estructurar el archivo */
				$datahead	= "RECAUDACION\t".$_codigo_entidad."\t".$_fecha_achivo."\t".$_cantidad_registros."\t".$_sumatoria_archivo.PHP_EOL;
				$datahead	.= 'NUMERO'.";".'TIPO DESCUENTO'.";".'CEDULA'.";".'NOMBRE'.";".'SUELDO LIQUIDO'.";".'DESCUENTO'.";".'TOTAL'.";".'AÑO DESCUENTO'.";".'MES DESCUENTO'.PHP_EOL;

				/*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
				$archivo = fopen($_ruta_archivo_recaudaciones, 'w');
				fwrite($archivo, $datahead.$databody);
				fclose($archivo);
				
				$error = error_get_last();
				if(!empty($error)){
					throw new Exception('Archivo no generado');
				}
				echo json_encode(array('mensaje'=>'archivo generado'));
				exit();

			}else{

				// generar archivo txt 
				$_TXT_RECAUDACIONES = $this->obtienePath($_nombre_entidad_patronal.$_subnombre_archivo, $_anio_recaudaciones, $_mes_recaudaciones, "ARCHIVOSENVIAR");
				$_nombre_archivo_recaudaciones = $_TXT_RECAUDACIONES['nombre'];
				$_ruta_archivo_recaudaciones   = $_TXT_RECAUDACIONES['ruta'];

				$columnas3	= "id_archivo_recaudaciones,generado_archivo_recaudaciones";
				$tablas3	= " core_archivo_recaudaciones";
				$where3		= " id_entidad_patronal = $_id_entidad_patronal
					AND anio_archivo_recaudaciones = $_anio_recaudaciones
					AND mes_archivo_recaudaciones = $_mes_recaudaciones";
				$id3		= " id_archivo_recaudaciones";
				$rsConsulta3= $Contribucion->getCondiciones($columnas3,$tablas3,$where3,$id3);
				$_id_archivo_recaudaciones = $rsConsulta3[0]->id_archivo_recaudaciones;

				/*buscar datos de vista para generar el archivo*/
				$columnas4 = "id_archivo_recaudaciones,id_participes, formato_archivo_recaudaciones, cedula_participes, nombre_participes, apellido_participes,
				valor_recaudaciones, sueldo_liquido_contribucion_tipo_participes, anio_archivo_recaudaciones, mes_archivo_recaudaciones";
				$tablas4   = "public.vw_archivo_recaudaciones";
				$where4    = "id_archivo_recaudaciones in ($_id_archivo_recaudaciones) ";
				$id4       = "id_participes";
				$rsConsulta4 = $Contribucion->getCondiciones($columnas4, $tablas4, $where4, $id4);

				/* aqui hacer calculos para sumatorias y numero de lineas */
				$_cantidad_registros	= sizeof($rsConsulta4);
				$_fecha_achivo	= date('30/m/Y');
				$_sumatoria_archivo	= 0.00;
			
				$databody	= "";
				$numero = 0;
				foreach($rsConsulta4 as $res){
					$numero += 1;
					$tipo_contribucion     = $res->formato_archivo_recaudaciones;
					$cedula_participe      =  $res->cedula_participes;
					$apellido_participe    =  $res->apellido_participes;
					$nombre_participe      =  $res->nombre_participes;
					$sueldo_participe      =  $res->sueldo_liquido_contribucion_tipo_participes;
					$valor_descuento       =  $res->valor_recaudaciones;
					$total_descuento       =  $res->valor_recaudaciones;
					$anio_recaudacion      =  $res->anio_archivo_recaudaciones;
					$mes_recaudacion       =  $res->mes_archivo_recaudaciones;

					$_sumatoria_archivo += $total_descuento; //variable para obtener la suma
					
					$databody.=$numero.";".$tipo_contribucion.";".$cedula_participe.";".$apellido_participe." ".$nombre_participe.";";
					$databody.=$sueldo_participe.";".$valor_descuento.";".$total_descuento.";".$anio_recaudacion.";".$mes_recaudacion.PHP_EOL;
					
				}
				
				/* estructurar el archivo */
				$datahead	= "RECAUDACION\t".$_codigo_entidad."\t".$_fecha_achivo."\t".$_cantidad_registros."\t".$_sumatoria_archivo.PHP_EOL;
				$datahead	.= 'NUMERO'.";".'TIPO DESCUENTO'.";".'CEDULA'.";".'NOMBRE'.";".'SUELDO LIQUIDO'.";".'DESCUENTO'.";".'TOTAL'.";".'AÑO DESCUENTO'.";".'MES DESCUENTO'.PHP_EOL;

				/*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
				$archivo = fopen($_ruta_archivo_recaudaciones, 'w');
				fwrite($archivo, $datahead.$databody);
				fclose($archivo);
				
				$error = error_get_last();
				if(!empty($error)){
					throw new Exception('Archivo no generado');
				}

				//para actualizacion
				$actColumnas = "generado_archivo_recaudaciones = 't', 
					ruta_archivo_recaudaciones = '$_ruta_archivo_recaudaciones',
					nombre_archivo_recaudaciones = '$_nombre_archivo_recaudaciones'";
				$actTablas = "core_archivo_recaudaciones";
				$actWhere = "id_archivo_recaudaciones = $_id_archivo_recaudaciones ";
				
				$Contribucion->ActualizarBy($actColumnas, $actTablas, $actWhere);

				echo json_encode(array('mensaje'=>'archivo generado'));
				exit();

			}
	        	        
	    } catch (Exception $e) {
	        
	        echo '<message>'.$e->getMessage().'<message>';
	    }
	    
	    
	}
	
	public function ConsultaArchivosGenerados(){
	    
	    $EntidadPatronal = new EntidadPatronalParticipesModel();
	    
	    $columnas = " id_archivo_recaudaciones,
                    formato_archivo_recaudaciones,
                    nombre_archivo_recaudaciones,
                    ruta_archivo_recaudaciones,
                    usuario_usuarios,
                    date(creado) creado,
                    date(modificado) modificado";
	    
	    $tablas = " public.core_archivo_recaudaciones";
	    
	    $where    = " 1 = 1 AND generado_archivo_recaudaciones = true ";
	    
	    $id = "creado";
	    
	    $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	    $search =  (isset($_REQUEST['busqueda'])&& $_REQUEST['busqueda'] !=NULL)?$_REQUEST['busqueda']:'';
	    
	    if($action == 'ajax'){
	        
	        if(!empty($search)){
	            $where1=" AND ( nombre_archivo_recaudaciones ILIKE '%".$search."%' )";
	            $where_to=$where.$where1;
	        }else{
	            $where_to=$where;
	        }
	        
	        $html = "";
	        $resultSet=$EntidadPatronal->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$EntidadPatronal->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if($cantidadResult>0){
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='table table-striped table-bordered'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res){
	                $i++;
	                $ruta = '..'.substr($res->ruta_archivo_recaudaciones, 0);
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->formato_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_archivo_recaudaciones.'</td>';
	                $html.='<td style="font-size: 11px;">'.$ruta.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->usuario_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->creado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->modificado.'</td>';
	                $html.='<td style="font-size: 18px;">';
	                $html.='<span class="pull-right ">
                                    <a onclick="verArchivo(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones.'"
                                    href="#" class="btn btn-sm btn-default label label-info">
                                    <i class="fa  fa-file-text" aria-hidden="true" ></i>
                                    </a></span></td>';
	                $html.='</tr>';
	               
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaArchivos").'';
	            $html.='</div>';
	            
	        }else{
	            
	            $html.= "<table id='tbl_documentos_recaudaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Formato</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ruta</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">creado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">modificado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	        }
	        
	        echo json_encode(array('tablaHtml'=>$html));
	    }
	    
	}
	
	
		
	//para paginacion
	public function paginate($reload, $page, $tpages, $adjacents, $funcion="") {
	    
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
		
	/**
	 * funcion que devuele array con el nombre y la ruta de archivo
	 * @param int $anioArchivo
	 * @param int $mesArchivo
	 */
	private function obtienePath($nombreArchivo,$anioArchivo,$mesArchivo,$folder){
	    
	    $respuesta     = array();
	    $nArchivo      = $nombreArchivo.$mesArchivo.$anioArchivo.".txt";
	    $carpeta_base      = 'view\\Recaudaciones\\documentos\\'.$folder.'\\';
	    $_carpeta_buscar   = $carpeta_base.$anioArchivo;
	    $file_buscar       = "";
	    if( file_exists($_carpeta_buscar)){
	        
	        $_carpeta_buscar   = $carpeta_base.$anioArchivo."\\".$mesArchivo;
	        if( file_exists($_carpeta_buscar)){
	            
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	            
	        }else{
	            
	            mkdir($_carpeta_buscar, 0777, true);
	            $file_buscar = $_carpeta_buscar."\\".$nArchivo;
	            
	        }
	        
	    }else{
	        
	        mkdir($_carpeta_buscar."\\".$mesArchivo, 0777, true);
	        $file_buscar = $_carpeta_buscar."\\".$mesArchivo."\\".$nArchivo;
	    }
	    
	    $respuesta['nombre']   = $nArchivo;
	    $respuesta['ruta']     = $file_buscar;
	    
	    return $respuesta;
	}
	
	function limpiarCaracteresEspeciales($string ){
	    $string = htmlentities($string);
	    $string = preg_replace('/\&(.)[^;]*;/', '', $string);
	    return $string;
	}
	
	function verPath(){
	    echo $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	    echo date('Y-m-t');
	}
	
	/***
	 * fn para setear las entidades patronales preopensa a cambios
	 * @throws Exception
	 */
	function setEntidadPatronal(){
	    
	    $Empleados = new EmpleadosModel();
	    
	    try {
	        
	        $Empleados->beginTran();
	        
	        $columnas1 = "*";
	        $tablas1   = "public.core_entidad_patronal";
	        $where1    = "1=1";
	        $id1       = "id_entidad_patronal";
	        $rsConsulta1  = $Empleados->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        foreach ( $rsConsulta1 as $res){
	            
	            $id_entidad_patronal   = $res->id_entidad_patronal;
	            
	            $columnas2 = "*";
	            $tablas2   = "public.consecutivos";
	            $where2    = "1=1 AND nombre_consecutivos = 'CODENTIDADPATRONAL'";
	            $id2       = "id_consecutivos";
	            $rsConsulta2  = $Empleados->getCondiciones($columnas2, $tablas2, $where2, $id2);
	            $valorconsetivos = $rsConsulta2[0]->valor_consecutivos;
	            $id_consecutivos = $rsConsulta2[0]->id_consecutivos;
	            
	            /*actualizar la entidad Patronal*/
	            $queryActualizacion = "UPDATE public.core_entidad_patronal SET codigo_entidad_patronal = $valorconsetivos WHERE id_entidad_patronal = $id_entidad_patronal";
	            $Empleados->executeNonQuery($queryActualizacion);

	            /* actualiza consecutivos */
	            /* actualizacion de consecutivo */
	            $_queryActualizacion = "UPDATE consecutivos
                                    SET numero_consecutivos = lpad((valor_consecutivos+1)::text,espacio_consecutivos,'0'),
                                    valor_consecutivos = valor_consecutivos+1
                                    WHERE id_consecutivos = $id_consecutivos ";
	            $Empleados->executeNonQuery($_queryActualizacion);
	            
	        }
	        
	        $error_php = error_get_last();
	        $error_pg  = pg_last_error();
	        if(!empty($error_php) || !empty($error_pg)){
	            throw new Exception("se genereo error");
	        }
	        
	        $Empleados->endTran("COMMIT");
	        
	        
	    } catch (Exception $e) {
	        
	        echo "hubo un error";
	        $Empleados->endTran();
	        
	    }
	    
	    
	}
	
	
	/** BEGIN FUNCIONES DE VALIDACIONES DEL CONTROLADOR */
	private function validaAportesParticipes($id_entidad_patronal){
	    $Participes    = new ParticipesModel();
	    
	    $columnas1 = " aa.id_participes, aa.cedula_participes, aa.nombre_participes, aa.apellido_participes";
	    $tablas1   = " core_participes aa
    	    INNER JOIN core_estado_participes bb ON bb.id_estado_participes = aa.id_estado_participes
    	    INNER JOIN (
                SELECT cc1.id_participes
                FROM core_contribucion_tipo_participes cc1
                INNER JOIN core_contribucion_tipo cc2 on cc2.id_contribucion_tipo = cc1.id_contribucion_tipo
                WHERE UPPER(cc2.nombre_contribucion_tipo) = 'APORTE PERSONAL'
                AND (cc1.sueldo_liquido_contribucion_tipo_participes IS NULL OR cc1.valor_contribucion_tipo_participes IS NULL)                	      
    	        ) cc ON cc.id_participes = aa.id_participes";
	    $where1    = " aa.id_estatus = 1
	        AND UPPER( bb.nombre_estado_participes ) = 'ACTIVO'
	        AND aa.id_entidad_patronal = $id_entidad_patronal ";
	    $id1       = "aa.id_participes";	    
	   	    
	    $rsConsulta1= $Participes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    	   	    
	    if( !empty($rsConsulta1)) return $rsConsulta1;
	    
	    return null;
	    
	}
	/** END FUNCIONES DE VALIDACIONES DEL CONTROLADOR */
	
	/** BEGIN FUNCIONES UTILITARIAS PARA LA CLASE */
	private function devuelveMesNombre($_mes){
	    
	    $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
	    $_intMes = (int)$_mes;
	    return $meses[$_intMes-1];
	    
	}
	
	private function returnDateLastDay($_anio, $_mes){
	    
	    $strmes    = str_pad($_mes, 2, "0", STR_PAD_LEFT);	    
	    $strfecha  = $_anio."-".$strmes."-"."1";	    
	    $fecha=new DateTime($strfecha);
	    $lastday = $fecha->format('Y-m-t');
	    $lastday = explode("-", $lastday);
	    $lastday=$lastday[2];
	    
	    return $lastday."/".$strmes."/".$_anio;
	    
	}
	
	private function returnSubfijoFormato( $_nombre_formato ){
	    switch ($_nombre_formato){
	        case "DESCUENTOS APORTES": return "_DA"; break;
	        case "DESCUENTOS CREDITOS": return "_DC"; break;
	        case "DESCUENTOS APORTES Y CREDITOS": return "_DAC"; break;
	    }
	}
	/** END FUNCIONES UTILITARIAS PARA LA CLASE */
	
	public function fn_prueba(){
	    
	    $parametrosDetalle  = "'10','1','hoal','null','null',null,'hola'";
	    echo $parametrosDetalle,'<br>';
	    $parametrosDetalle  = str_replace("'null'","null",$parametrosDetalle);
	    echo $parametrosDetalle,'<br>';
	    
	    echo date('Y-m-d H:i:s');
	    
	    $fecha = new DateTime('2020-04-02');
	    $fecha->modify('last day of this month');
	    echo $fecha->format('d/m/Y');
	    
	    echo "<h3>Postincremento</h3>";
	    $a = 5;
	    echo "--".($a+1)."--";
	    echo "Debe ser 5: " . $a++ . "<br />\n";
	    echo "Debe ser 6: " . $a . "<br />\n";
	    
	    echo "<h3>Preincremento</h3>";
	    $a = 5;
	    echo "Debe ser 6: " . ++$a . "<br />\n";
	    echo "Debe ser 6: " . $a . "<br />\n";
	    
	    echo "<h3>Postdecremento</h3>";
	    $a = 5;
	    echo "Debe ser 5: " . $a-- . "<br />\n";
	    echo "Debe ser 4: " . $a . "<br />\n";
	    
	    echo "<h3>Predecremento</h3>";
	    $a = 5;
	    echo "Debe ser 4: " . --$a . "<br />\n";
	    echo "Debe ser 4: " . $a . "<br />\n";
	}
	
	/***********************************************************************************************************************************************************/
	/***** begin dc 2020/04/22 ******/ 
	public function cargaEntidadPatronal(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    $resp  = null;
	    
	    $col1  = " id_entidad_patronal, nombre_entidad_patronal ";
	    $tab1  = " public.core_entidad_patronal ";
	    $whe1  = " 1 = 1 ";
	    $id1   = " nombre_entidad_patronal ";
	    
	    $rsConsulta1   = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    $resp['estatus']   = "OK";
	    $resp['data']      = $rsConsulta1;
	    
	    echo json_encode($resp);	    
	     
	}
	
	public function cargaFormatoDescuentos(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    $resp  = null;
	    
	    $id_entidad_patronal = $_POST['id_entidad_patronal'];	    
	    
	    $col1  = " id_descuentos_formatos,nombre_descuentos_formatos ";
	    $tab1  = " public.core_descuentos_formatos ";
	    $whe1  = " id_entidad_patronal = $id_entidad_patronal";
	    $id1   = " id_descuentos_formatos ";
	    $rsConsulta1   = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    if( !empty( pg_last_error() )  || !empty( error_get_last() ) ){
	        
	        $error = error_get_last();
	        error_clear_last();	
	        if (ob_get_contents()) ob_end_clean();
	        
	        echo "ERROR ENCONTRADO \n";
	        var_dump($error);
	        
	        return;
	    }
	    
	    $resp['data'] = ( !empty( $rsConsulta1 ) ) ? $rsConsulta1 : null;
	    	    
	    echo json_encode($resp);
	}
	/***** end dc 2020/04/22 ******/ 
	
	/******* begin dc 2020/04/29 ****/
	public function CargarDatosDescuentos(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    /*toma de variables*/
	    $page              = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    $busqueda_datos    = $_POST['busqueda'];
	    $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	    $tipo_descuento        = $_POST['tipo_descuento'];
	    
	    if( !empty( error_get_last() ) ){
	        echo "<message> Variables no definidas <message>";
	        exit();
	    }
	    	    
	    if( $tipo_descuento == "1" ){
	        
	        $col1  = "";
	        $tab1  = "";
	        $whe1  = "";
	        $id1   = "";
	        
	    }elseif ($tipo_descuento == "2" ){
	        
	    }else{
	        
	    }
	    
	   	    
	    /*$columnas1 = "aa.id_archivo_recaudaciones, aa.valor_sistema_archivo_recaudaciones_detalle, aa.valor_final_archivo_recaudaciones_detalle,
        	   bb.formato_archivo_recaudaciones, bb.usuario_usuarios, cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes,
               aa.id_archivo_recaudaciones_detalle, aa.id_creditos, aa.descripcion_archivo_recaudaciones_detalle";
	    $tablas1    = "core_archivo_recaudaciones_detalle aa
        	   INNER JOIN core_archivo_recaudaciones bb
        	   ON bb.id_archivo_recaudaciones = aa.id_archivo_recaudaciones
        	   INNER JOIN core_participes cc
        	   ON cc.id_participes = aa.id_participes";
	    $where1     = "cc.id_estatus = 1
        	   AND aa.id_archivo_recaudaciones = $_id_archivo_recaudaciones";
	    $id1        = "cc.id_participes";*/
	    
	    
	    if( strlen($_busqueda_datos_generados) > 0 ){
	        // metodos de busqueda
	        $where1 .= " AND ( cc.cedula_participes ILIKE '$_busqueda_datos_generados%' )";
	    }
	    
	    //echo $columnas2, $tablas2, $where2, $id2, '1','<br>'; die();
	    
	    $html = "";
	    $resultSet=$Contribucion->getCantidad("*", $tablas1, $where1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    /* para obtener Sumas*/
	    $rsSumatoria1           = $Contribucion->getSumaColumna("aa.valor_sistema_archivo_recaudaciones_detalle", $tablas1, $where1);
	    $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	    $rsSumatoria2           = $Contribucion->getSumaColumna("aa.valor_final_archivo_recaudaciones_detalle", $tablas1, $where1);
	    $_total_archivo_final   = $rsSumatoria2[0]->suma;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$Contribucion->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    if($cantidadResult>0){
	        
	        $html.= "<table id='tbl_archivo_recaudaciones_insertados' class='table tablesorter table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;" colspan="8">'.$resultSet[0]->formato_archivo_recaudaciones.'</th>';
	        $html.='</tr>';
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;">-</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        foreach ($resultSet as $res){
	            $i++;
	            
	            /* se realiza una validacion : si el id de credito es null se activa para editar la fila*/
	            $_html_boton_editar = "-";
	            if( empty($res->id_creditos) ){
	                $_html_boton_editar = '<span class="">
                            <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span>';
	            }
	            
	            $html.='<tr>';
	            $html.='<td style="font-size: 18px;">';
	            $html.= $_html_boton_editar;
	            $html.= '</td>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->descripcion_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_sistema_archivo_recaudaciones_detalle.'</td>';
	            $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_final_archivo_recaudaciones_detalle.'</td>';
	            
	            $html.='</tr>';
	        }
	        
	        $html.='</tbody>';
	        /*para totalizar las filas*/
	        $html.='<tfoot>';
	        $html.='<tr>';
	        $html.='<th colspan="5" ></th>';
	        $html.='<th style="text-align: right"; >TOTALES</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
	        $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
	        $html.='</tr>';
	        $html.='</tfoot>';
	        $html.='</table>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"buscarDatosInsertados").'';
	        $html.='</div>';
	        
	    }else{
	        
	        $html.= "<table id='tbl_archivo_recaudaciones_insertados' class='table tablesorter  table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='</tbody>';
	        $html.='</table>';
	    }
	    
	    echo json_encode(array('tablaHtml'=>$html,'cantidadRegistros'=>$cantidadResult));
	    
	    
	}
	/******* begin dc 2020/04/29 ****/
		
}
?>