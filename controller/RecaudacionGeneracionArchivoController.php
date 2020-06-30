<?php
class RecaudacionGeneracionArchivoController extends ControladorBase{

	public function __construct() 
	{
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
	        $_id_descuentos_formatos   = $_POST['id_descuentos_formatos'];
	        
	        if( !empty( error_get_last() ) ){    throw new Exception('Datos enviados no validos para generacion de Archivo'); }
	        
	        //consultar tipo de descuento si es de -- credito o aportes
	        $col001    = " id_descuentos_formatos, nombre_descuentos_formatos, parametro_uno_descuentos_formatos";
	        $tab001    = " public.core_descuentos_formatos ";
	        $whe001    = " entrada_descuentos_formatos = 'f'
    	        AND sp_descuentos_formatos = 'RP' 
                AND id_descuentos_formatos = $_id_descuentos_formatos";
	        
	        $rsConsulta001 = $recaudaciones->getCondicionesSinOrden($col001, $tab001, $whe001, "");
	        
	        
	        if( !empty( $rsConsulta001 ) )
	        {
	            $_formato_recaudacion  = $rsConsulta001[0]->parametro_uno_descuentos_formatos;
	            //formatos vendran de la soguiente manera 
	            //A-> aportes
	            //C-> creditos
	        }else
	        {
	            throw new Exception("Formato descuento no definido");
	        }
	        
	    } catch (Exception $e) {
	        echo '<message>'.$e->getMessage().' <message>';
	        exit();
	    }
	    
	    //validacion de aportes personales
	    if( $_formato_recaudacion == "A"  ){
	        
	        $_participes_sin_aportes   = $this->validaAportesParticipes($_id_entidad_patronal);
	        	        
	        if(!empty($_participes_sin_aportes)){
	            $errorAportes  = array();
	            $errorAportes['mensajeAportes']    = "Necesita Validar la informacion de estos Paricipes";
	            $errorAportes['dataAportes']    = $_participes_sin_aportes;
	            echo json_encode($errorAportes);
	            die();
	        }
	    }
	                  
	    //$_nombre_formato_recaudacion = ($_formato_recaudacion == 1) ? "DESCUENTOS APORTES" : ( ( $_formato_recaudacion == 2 ) ? "DESCUENTOS CREDITOS" : "DESCUENTOS APORTES Y CREDITOS");
	    
	    /** primero validar si el tipo de archivo solicitado esta permitido */
	    try {
	        
	        $auxValidaDatos    = $this->ValidarDatosByFormato( $_id_entidad_patronal, $_anio_recaudacion, $_mes_recaudacion, $_id_descuentos_formatos);
	        
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
	        
	        if( $_formato_recaudacion === "A" )
	        {
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
	            
	            // la cabecera se genera directo cuando es del tipo aportes
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
	            
	            $auxDetalle    = $this->RecaudacionAportes($paramsCab);
	            
	            if( $auxDetalle['error'] ){
	                throw new Exception( $auxDetalle['mensaje'] );
	            }
	            
	        }elseif ( $_formato_recaudacion === "C" )
	        {
	            
	            $paramsCab = array(); //la cebecera se genera al momneto de aceptar el descuentos del credito
	            $paramsCab['id_entidad_patronal']          = $_id_entidad_patronal;
	            $paramsCab['id_descuentos_formatos']       = $_id_descuentos_formatos;
	            $paramsCab['anio_recaudaciones']           = $_anio_recaudacion;
	            $paramsCab['mes_recaudaciones']            = $_mes_recaudacion;
	            
	            $auxDetalle    = $this->RecaudacionCreditos($paramsCab);
	            
	            if( $auxDetalle['error'] )
	            {
	                throw new Exception( $auxDetalle['mensaje'] );
	            }
	            $resp['tipo']   = "C"; //esta variable solo va para del tipo credito s
	            
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
	
	public function ValidarDatosByFormato( int $id_entidad_patronal, int $anio_descuentos, int $mes_descuentos, int $id_descuentos_formatos)
	{
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    $response = array();
	    $rsConsulta1   = array();
	    
	    $col1  = " COUNT(1) cantidad";
	    $tab1  = " public.core_descuentos_registrados_cabeza";
	    $whe1  = " year_descuentos_registrados_cabeza = $anio_descuentos
    	    AND mes_descuentos_registrados_cabeza = $mes_descuentos
    	    AND id_entidad_patronal = $id_entidad_patronal
    	    AND id_descuentos_formatos = $id_descuentos_formatos";
	    
	    $rsConsulta1 = $recaudaciones->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	   	    
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
	
	private function generarCabeceraDescuento( $datosCabecera )
	{
	    $recaudaciones   = new RecaudacionesModel();
	    
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
    	    INNER JOIN core_estado_participes ee ON ee.id_estado_participes = dd.id_estado_participes
            INNER JOIN (
    	    	SELECT c.id_participes 
				FROM core_participes p 
				INNER JOIN core_contribucion c ON p.id_participes=c.id_participes AND c.id_estado_contribucion=1  and c.id_estatus=1 
				WHERE p.id_estatus=1 AND p.id_estado_participes=1 AND p.id_entidad_patronal = $id_entidad_patronal
				GROUP BY c.id_participes
				HAVING SUM(c.valor_personal_contribucion+c.valor_patronal_contribucion) > 0
    	    )ff ON ff.id_participes = dd.id_participes ";
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
	            $_porcentaje   = (float)$res->valor_contribucion_tipo_participes;
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
	    $id_entidad_patronal    = $paramsCab['id_entidad_patronal'];
	    //$id_formatos_descuentos = $paramsCab['id_descuentos_formatos'];
	    $anio_recaudacion       = $paramsCab['anio_recaudaciones'];
	    $mes_recaudacion        = $paramsCab['mes_recaudaciones'];
	    
	    $fecha_recaudacion = $anio_recaudacion."-".$mes_recaudacion."-01";
	    $Ofec_recaudacion  = new DateTime($fecha_recaudacion); 
	    $fecha_recaudacion = $Ofec_recaudacion->format('Y-m-t');
	    	    
	    /*** EJECUCION DE FUNCION QUE REALIZA BUSQUEDA EN INSERTA EN TABLA DE VALORES -- nota validar duracion de respuesta**/
	    
	    $funcion = "fc_descuentos_creditos_distribucion_inicio";	    
	    $parametros = "$id_entidad_patronal,null,'$fecha_recaudacion',$anio_recaudacion,$mes_recaudacion"; 
	    $sqDatos   = $recaudaciones->getconsultaPG($funcion, $parametros);
	   
	    $resultado = $recaudaciones->llamarconsultaPG($sqDatos);
	    
	    if( (int)$resultado[0] != 1 ){
	        return array('error'=>true,'mensaje'=>"ERROR ejecucion FUNCION 'fc_descuentos_creditos_distribucion_inicio'");
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
	            //para pruebas de Datatable --Ori  verDatosDescuentos  Prue MostrarDetallesDescuentos
	            $html.='<span class="pull-right ">
                        <a onclick="verDatosDescuentos(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Ver Detalles">
                        <i class="fa  fa-building-o" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="genArchivoDetallado(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Detallado">
                        <i class="fa fa-files-o text-info" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            $html.='<span class="pull-right ">
                        <a onclick="genArchivoEntidad(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Entidad">
                        <i class="fa fa-file-text-o text-info" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';
	            /*$html.='<span class="pull-right ">
                        <a onclick="ValidarEdicionGenerados(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label " data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-edit text-warning" aria-hidden="true" ></i>
                        </a></span></td>';
	            $html.='<td style="font-size: 18px;">';*/
	            /*$html.='<span class="pull-right ">
                        <a onclick="eliminarRegistro(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
                        href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Eliminar">
                        <i class="fa fa-trash text-danger" aria-hidden="true" ></i>
                        </a></span>';
	            $html.='</td>';*/
	            
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
	
	
	/** BEGIN GENERACION DE ARCHIVOS TXT */
	
	public function genArchivoDetallado(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    try {
	        
	        if( !isset( $_SESSION ) ){
	            session_start();
	        }
	        $usuario_usuarios      = $_SESSION['usuario_usuarios'];
	        
	        /*tomar datos de la vista*/	        	        
	        $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	        $tipo_descuento        = $_POST['tipo_descuento'];	
	        
	        if( error_get_last() ){
	            throw new Exception( "Datos no recibidos" );
	        }
	        
	        /** VALIDACION PARA SABER SI YA EXISTE EL ARCHIVO GENERADO **/
	        $colvalidacion = " ubicacion_descuentos_registrados_archivo, nombre_descuentos_registrados_archivo";
	        $tabvalidacion = " public.core_descuentos_registrados_archivo";
	        $twhevalidacion = " id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $rsValidacion  = $recaudaciones->getCondicionesSinOrden($colvalidacion, $tabvalidacion, $twhevalidacion, "LIMIT 1");
	        
	        if( !empty( $rsValidacion ) ){
	            
	            $ubicacionFile = $rsValidacion[0]->ubicacion_descuentos_registrados_archivo;
	            $nombreFile    = $rsValidacion[0]->nombre_descuentos_registrados_archivo;
	            
	            header("Content-disposition: attachment; filename=$nombreFile");
	            header("Content-type: MIME");
	            ob_clean();
	            flush();
	            readfile($ubicacionFile);
	            exit;
	        }
	        
	        $col1  = " aa.id_descuentos_registrados_cabeza, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza, bb.id_entidad_patronal,
                bb.nombre_entidad_patronal, bb.codigo_entidad_patronal ";
	        $tab1  = " core_descuentos_registrados_cabeza aa
	           INNER JOIN core_entidad_patronal bb	ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	        $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id1   = " aa.id_descuentos_registrados_cabeza ";
	        
	        $rsConsulta1    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        //$id_entidad_patronal       = $rsConsulta1[0]->id_entidad_patronal;
	        //$nombre_entidad_patronal   = $this->limpiarCaracteresEspeciales( $rsConsulta1[0]->nombre_entidad_patronal );
	        $codigo_entidad_patronal   = $rsConsulta1[0]->codigo_entidad_patronal;
	        $anio_descuentos_cabeza    = $rsConsulta1[0]->year_descuentos_registrados_cabeza;
	        $mes_descuentos_cabeza     = $rsConsulta1[0]->mes_descuentos_registrados_cabeza;
	        $id_descuentos_cabeza      = $rsConsulta1[0]->id_descuentos_registrados_cabeza;
	        $id_entidad_patronal       = $rsConsulta1[0]->id_entidad_patronal;	        
	        
	      
	        $rsData    = array();
	        //$nameTipoDescuento = "";
	        if( $tipo_descuento == "1" ){
	            
	            //$nameTipoDescuento = "APORTES";
	            
	            $col1  = " aa.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.aporte_personal_descuentos_registrados_detalle_aportes,
	               COALESCE(aa.valor_usuario_descuentos_registrados_detalle_aportes,0) as valor_usuario_descuentos_registrados_detalle_aportes ";
	            $tab1  = " core_descuentos_registrados_detalle_aportes aa
	               INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	            $whe1  = " COALESCE( aa.valor_usuario_descuentos_registrados_detalle_aportes ) > 0 AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id1   = " aa.id_descuentos_registrados_detalle_aportes ";
	            	            
	            $rsData    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	            
	        }elseif( $tipo_descuento == "2" ){
	            
	            //$nameTipoDescuento = "CREDITOS";
	            /*
	            $col2  = " bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.cuota_descuentos_registrados_detalle_creditos,
                    aa.valor_usuario_descuentos_registrados_detalle_creditos";
	            $tab2  = " core_descuentos_registrados_detalle_creditos aa
    	            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	            $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id2   = " aa.id_descuentos_registrados_detalle_creditos";	            
	            
	            $rsData    = $recaudaciones->getCondiciones($col2, $tab2, $whe2, $id2);
	            */
	            $col2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes,
    	        SUM( COALESCE( aa.mora_descuentos_registrados_detalle_creditos, 0) ) suma_mora ,
    	        SUM( COALESCE(aa.valor_usuario_descuentos_registrados_detalle_creditos,0) ) suma_valor,
                SUM( COALESCE(aa.monto_descuentos_registrados_detalle_creditos,0) ) suma_monto";
	            $tab2  = " core_descuentos_registrados_detalle_creditos aa
	            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes ";
	            $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $gru2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes";
	            $id2   = " bb.cedula_participes ";
	            
	            $rsData   = $recaudaciones->getCondiciones_grupo($col2, $tab2, $whe2, $gru2, $id2);
	           	            
	        }else{
	            throw new Exception( "Tipo de descuento no valido" );
	        }
	        
	        /**generar datos de archivo plano*/
	        $mes_descuentos_cabeza_archivo = str_pad($mes_descuentos_cabeza, 2, "0",STR_PAD_LEFT);
	        //$subnameFile   = "_DET".$nameTipoDescuento;
	        $nombreArchivoBD = date('dmYHms').'_'.$usuario_usuarios.'_'.$id_entidad_patronal;
	        $_TXT_RECAUDACIONES = $this->obtienePath( $nombreArchivoBD, $anio_descuentos_cabeza, $mes_descuentos_cabeza_archivo, "ARCHIVOSENVIAR");            
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
                    $valor_descuento       = $res->suma_monto;                   
                    $total_descuento       = $res->suma_valor;
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
            
            /** se guarda en bd datos de archivo generado **/
            //formato nombre -fechaRegistro->(ddmmyyyyHHmmss),usuario_usuarios,id_entidad_patronal
            $funcion = "core_ins_descuentos_registrados_archivo";
            $parametros    = " $id_descuentos_cabeza, ";
            $parametros    .= "'$nameFileRecaudaciones',";
            $parametros    .= "'$rutaFileRecaudaciones',";
            $parametros    .= "'$numero',";
            $parametros    .= "'$usuario_usuarios',";
            $parametros    .= "'archivo entidad patronal',";
            $parametros    .= "null";
            $spRecaudacionesArchivo    = $recaudaciones->getconsultaPG( $funcion, $parametros );
            $recaudaciones->llamarconsultaPG( $spRecaudacionesArchivo );
            /** termina guardado en bd datos de archivo generado **/
            
            /** MANDAR EL ARCHIVO EN LINEA **/
            $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
            $ubicacion = $ubicacionServer.$rutaFileRecaudaciones;
            
            // Define headers
            header("Content-disposition: attachment; filename=$nameFileRecaudaciones");
            header("Content-type: MIME");
            ob_clean();
            flush();
            readfile($ubicacion);
            exit;
            
	        	        
	    } catch (Exception $e) {
	        $buffer    = error_get_last();
	        echo '<message>'.$e->getMessage().' <--> '.$buffer['message'].' <message>';
	        exit();
	    }
	    	    
	}
	
	public function genArchivoEntidad(){
	    
	    $recaudaciones = new RecaudacionesModel();
	    
	    try {
	        
	        if( !isset( $_SESSION ) ){
	            session_start();
	        }
	        $usuario_usuarios      = $_SESSION['usuario_usuarios'];
	        
	        $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	        $tipo_descuento        = $_POST['tipo_descuento'];	        
	        	        
	        if( error_get_last() ){
	            throw new Exception( "Datos no recibidos" );
	        }
	        
	        /** VALIDACION PARA SABER SI YA EXISTE EL ARCHIVO GENERADO **/
	        $colvalidacion = " ubicacion_descuentos_registrados_archivo, nombre_descuentos_registrados_archivo";
	        $tabvalidacion = " public.core_descuentos_registrados_archivo";
	        $twhevalidacion = " id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $rsValidacion  = $recaudaciones->getCondicionesSinOrden($colvalidacion, $tabvalidacion, $twhevalidacion, "LIMIT 1");
	        
	        if( !empty( $rsValidacion ) ){
	            
	            $ubicacionFile = $rsValidacion[0]->ubicacion_descuentos_registrados_archivo;
	            $nombreFile    = $rsValidacion[0]->nombre_descuentos_registrados_archivo;
	            
	            header("Content-disposition: attachment; filename=$nombreFile");
	            header("Content-type: MIME");
	            ob_clean();
	            flush();
	            readfile($ubicacionFile);
	            exit;
	        }
	        	        
	        $col1  = " aa.id_descuentos_registrados_cabeza, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza, bb.id_entidad_patronal, 
                bb.nombre_entidad_patronal,bb.codigo_entidad_patronal ";
	        $tab1  = " core_descuentos_registrados_cabeza aa
	           INNER JOIN core_entidad_patronal bb	ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	        $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id1   = " aa.id_descuentos_registrados_cabeza ";
	        
	        $rsConsulta1    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        //$id_entidad_patronal       = $rsConsulta1[0]->id_entidad_patronal;
	        //$nombre_entidad_patronal   = $this->limpiarCaracteresEspeciales( $rsConsulta1[0]->nombre_entidad_patronal );
	        $codigo_entidad_patronal   = $rsConsulta1[0]->codigo_entidad_patronal;
	        $anio_descuentos_cabeza    = $rsConsulta1[0]->year_descuentos_registrados_cabeza;
	        $mes_descuentos_cabeza     = $rsConsulta1[0]->mes_descuentos_registrados_cabeza;
	        $id_descuentos_cabeza      = $rsConsulta1[0]->id_descuentos_registrados_cabeza;
	        $id_entidad_patronal       = $rsConsulta1[0]->id_entidad_patronal;
	        	        
	        $rsData    = array();
	        $nameTipoDescuento = "";
	        if( $tipo_descuento == "1" ){
	            
	            $nameTipoDescuento = "APORTES";
	            
	            $col1  = " aa.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, 
                    aa.aporte_personal_descuentos_registrados_detalle_aportes \"valor_descuento\",
	               COALESCE(aa.valor_usuario_descuentos_registrados_detalle_aportes,0) as \"valor_descuento1\" ";	               
	            $tab1  = " core_descuentos_registrados_detalle_aportes aa
	               INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	            $whe1  = " COALESCE( aa.valor_usuario_descuentos_registrados_detalle_aportes ) > 0 AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id1   = " aa.id_descuentos_registrados_detalle_aportes ";
	            
	            $rsData    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	            
	        }elseif( $tipo_descuento == "2" ){
	            
	            $nameTipoDescuento = "CREDITOS";
	            
	            /*
	            $col2  = " bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, 
                    aa.cuota_descuentos_registrados_detalle_creditos \"valor_descuento\",
                    aa.valor_usuario_descuentos_registrados_detalle_creditos \"valor_descuento1\" ";
	            $tab2  = " core_descuentos_registrados_detalle_creditos aa
    	            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
    	            INNER JOIN core_creditos cc ON cc.id_creditos = aa.id_creditos";
	            $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $id2   = " aa.id_descuentos_registrados_detalle_creditos";
	            
	            $rsData    = $recaudaciones->getCondiciones($col2, $tab2, $whe2, $id2);
	            */
	            
	            $col2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes,
    	        SUM( COALESCE(aa.valor_usuario_descuentos_registrados_detalle_creditos,0) ) valor_descuento1,
                SUM( COALESCE(aa.monto_descuentos_registrados_detalle_creditos,0) ) valor_descuento";
	            $tab2  = " core_descuentos_registrados_detalle_creditos aa
	            INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes ";
	            $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            $gru2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes";
	            $id2   = " bb.cedula_participes ";
	            
	            $rsData   = $recaudaciones->getCondiciones_grupo($col2, $tab2, $whe2, $gru2, $id2);
	            
	        }else{
	            throw new Exception( "Tipo de descuento no valido" );
	        }
	        
	        // dc 2020/06/04 en la consulta anterior se genero la columna valor_mora en las dos consultas para que no genere error en la unoa con valor cero 
	        //y en la otra con valores de mora
	        
	        /**generar datos de archivo plano*/
	        $mes_descuentos_cabeza_archivo = str_pad($mes_descuentos_cabeza, 2, "0",STR_PAD_LEFT);
	        //$subnameFile   = "_".$nameTipoDescuento;
	        
	        $nombreArchivoBD = date('dmYHms').'_'.$usuario_usuarios.'_'.$id_entidad_patronal;
	        $_TXT_RECAUDACIONES = $this->obtienePath( $nombreArchivoBD, $anio_descuentos_cabeza, $mes_descuentos_cabeza_archivo, "ARCHIVOSENVIAR");
	        $nameFileRecaudaciones  = $_TXT_RECAUDACIONES['nombre'];
	        $rutaFileRecaudaciones  = $_TXT_RECAUDACIONES['ruta'];
	        
	        /* PARA REALIZAR LOS DETALLES DE ARCHIVO PLANO*/
	        $_cantidad_registros	= sizeof($rsData);
	        $_fecha_achivo	= $this->returnDateLastDay($anio_descuentos_cabeza, $mes_descuentos_cabeza);
	        $_sumatoria_archivo	= 0.00;
	        
	        	        
	        /* para generar grupos */
	        $_grupo_valor_descuento = 0.0;
	        $_ultima_fila = $_cantidad_registros-1;
	        
	        $databody	= "";
	        $numero = 0;	
	        
	        for( $i=0; $i<$_cantidad_registros; $i++){
	            
	            $id_participes         = $rsData[$i]->id_participes;
	            $cedula_participe      = $rsData[$i]->cedula_participes;
	            $apellido_participe    = $rsData[$i]->apellido_participes;
	            $nombre_participe      = $rsData[$i]->nombre_participes;
	            $total_descuento       = $rsData[$i]->valor_descuento1;
	            
	            $_grupo_valor_descuento += (float)$total_descuento;
	            $_sumatoria_archivo += (float)$total_descuento;	            
	            
	            if( $i < $_ultima_fila ){
	                
	                if( $id_participes != $rsData[$i+1]->id_participes){
	                    
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
	        $datahead	= $nameTipoDescuento."\t".$codigo_entidad_patronal."\t".$_fecha_achivo."\t".$numero."\t".$_sumatoria_archivo.PHP_EOL;
	        $datahead	.= 'NUMERO'.";".'CEDULA'.";".'NOMBRE'.";".'TOTAL DESCUENTO'.";".PHP_EOL;
			
			//echo $datahead.$databody;
	        
	        /*** buscar otro metodo para archivos grandes evitar acumulacion memoria al generar todo en una variable */
	        $archivo = fopen($rutaFileRecaudaciones, 'w');
	        fwrite($archivo, $datahead.$databody);
	        fclose($archivo);
	        
	        /** MANDAR EL ARCHIVO EN LINEA **/
	        $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\";
	        $ubicacion = $ubicacionServer.$rutaFileRecaudaciones;
	        
	        /** se guarda en bd datos de archivo generado **/
	        //formato nombre -fechaRegistro->(ddmmyyyyHHmmss),usuario_usuarios,id_entidad_patronal
	        $funcion = "core_ins_descuentos_registrados_archivo";
	        $parametros    = " $id_descuentos_cabeza, ";
	        $parametros    .= "'$nameFileRecaudaciones',";
	        $parametros    .= "'$rutaFileRecaudaciones',";
	        $parametros    .= "'$numero',";
	        $parametros    .= "'$usuario_usuarios',";
	        $parametros    .= "'archivo entidad patronal',";
	        $parametros    .= "null";
	        $spRecaudacionesArchivo    = $recaudaciones->getconsultaPG( $funcion, $parametros );
	        $recaudaciones->llamarconsultaPG( $spRecaudacionesArchivo );
	        /** termina guardado en bd datos de archivo generado **/
	        	        
	        // Define headers
	        header("Content-disposition: attachment; filename=$nameFileRecaudaciones");
	        header("Content-type: MIME");
	        ob_clean();
	        flush();
	        readfile($ubicacion);
	        exit;
	        	        
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
	
	
	public function obtenerDetalleDescuentos()
	{
	    $recaudaciones  = new RecaudacionesModel();
	    
	    /* tomar datos de la web */
	    
	    $id_descuentos_detalle = $_POST['id_descuentos_detalle'];
	    $tipo_descuentos = $_POST['tipo_descuento'];
	    
	    $rsConsulta1   = array();
	    if( $tipo_descuentos == "aportes" )
	    {
	        $columnas1 = 'aa.id_descuentos_registrados_detalle_aportes "id_detalle",
    	    aa.aporte_personal_descuentos_registrados_detalle_aportes "valor_descuento",
    	    aa.valor_usuario_descuentos_registrados_detalle_aportes "valor_descuento1",
    	    bb.id_participes , bb.cedula_participes, bb.apellido_participes, bb.nombre_participes';
	        $tablas1   = "core_descuentos_registrados_detalle_aportes aa
	       INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	        $where1    = " 1 = 1
	       AND aa.id_descuentos_registrados_detalle_aportes =$id_descuentos_detalle";
	        $id1       = "aa.id_descuentos_registrados_detalle_aportes ";
	        
	        $rsConsulta1   = $recaudaciones->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	    }else if( $tipo_descuentos == "creditos" )
	    {
	        $columnas1 = 'aa.id_descuentos_registrados_detalle_creditos "id_detalle",
    	    aa.cuota_descuentos_registrados_detalle_creditos "valor_descuento",
    	    aa.valor_usuario_descuentos_registrados_detalle_creditos "valor_descuento1",
    	    bb.id_participes , bb.cedula_participes, bb.apellido_participes, bb.nombre_participes';
	        $tablas1   = "core_descuentos_registrados_detalle_creditos aa
	       INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	        $where1    = " 1 = 1
	       AND aa.id_descuentos_registrados_detalle_creditos =$id_descuentos_detalle";
	        $id1       = "aa.id_descuentos_registrados_detalle_creditos ";
	        
	        $rsConsulta1   = $recaudaciones->getCondiciones($columnas1, $tablas1, $where1, $id1);
	    }
	    
	    if(empty($rsConsulta1)){
	        
	        echo json_encode(array('data'=>null));
	    }else{
	        echo json_encode(array('data'=>$rsConsulta1));
	    }
	}
	
	public function editAporte(){
	    
	    session_start();
	    $error="";
	    try {
	        
	        $recaudaciones = new RecaudacionesModel();
	        $id_descuentos_detalle   = $_POST['id_descuentos_detalle'];
	        $valor_descuentos        = $_POST['valor_descuentos'];
	        $tipo_descuentos         = $_POST['tipo_descuentos'];
	        
	        $error=error_get_last();
	        if( !empty($error) ){    throw new Exception("Variables no definidas"); }
	        
	        if( $tipo_descuentos == "aportes" )
	        {
	            $colval = " valor_usuario_descuentos_registrados_detalle_aportes = '$valor_descuentos' ";
	            $tabla = " core_descuentos_registrados_detalle_aportes ";
	            $where = " id_descuentos_registrados_detalle_aportes = '$id_descuentos_detalle'";
	            
	            $resultado = $recaudaciones->ActualizarBy($colval, $tabla, $where);
	            
	        }else if( $tipo_descuentos == "creditos" )
	        {
	            $colval = " valor_usuario_descuentos_registrados_detalle_creditos = '$valor_descuentos' ";
	            $tabla = " core_descuentos_registrados_detalle_creditos ";
	            $where = " id_descuentos_registrados_detalle_creditos = '$id_descuentos_detalle'";
	            
	            $resultado = $recaudaciones->ActualizarBy($colval, $tabla, $where);
	        }
	        
	        if( !empty( pg_last_error() ) ){
	            throw new Exception("Actualizacion No realizada");
	        }
	        
	        $resp = array();
	        $resp['estatus']   = "OK";
	        $resp['mensaje']   = " Filas Actualizadas (".$resultado.")";
	                              
	        echo json_encode($resp);	                
	                
	    } catch (Exception $e) {
	        $buffer = error_get_last();
	        echo '<message> Error Recaudacion \n '.$e->getMessage().' -- '.$buffer['message'].'<message>';
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
	    $nArchivo      = $nombreArchivo.".txt";
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
	
	/***
	 * fn para setear las entidades patronales propensa a cambios
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
	    
	    $col1  = " id_descuentos_formatos, nombre_descuentos_formatos ";
	    $tab1  = " public.core_descuentos_formatos ";
	    $whe1  = "entrada_descuentos_formatos = 'f'
        	    AND sp_descuentos_formatos = 'RP'";
	    $id1   = " nombre_descuentos_formatos ";
	    
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
	    
	    //validacion tipo descuento valores viene de vista de listado de filas
	    //1-Aportes 2-Creditos
	    
	    if( $tipo_descuento == "1" ){
	        
	        $columnaValores    = " COALESCE( bb.aporte_personal_descuentos_registrados_detalle_aportes, 0 ) ";
	        $columnaValores1   = " COALESCE( bb.valor_usuario_descuentos_registrados_detalle_aportes, 0 ) ";
	        
	        $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_aportes "id_detalle",
    	        bb.aporte_personal_descuentos_registrados_detalle_aportes "valor_descuento",
    	        bb.valor_usuario_descuentos_registrados_detalle_aportes "valor_descuento1",
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuento",
    	        cc.id_participes , cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	        $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_aportes bb ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes";
	        $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id1   = " bb.id_descuentos_registrados_detalle_aportes ";
	        
	        if( strlen( $busqueda_datos ) > 0 ){
	            $whe1  .= " AND ( cc.cedula_participes ILIKE '%$busqueda_datos%' OR cc.apellido_participes ILIKE '%$busqueda_datos%' OR cc.nombre_participes ILIKE '%$busqueda_datos%')";
	        }
	        
	        //$rsData    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        	        
	    }elseif ($tipo_descuento == "2" ){
	        
	        $columnaValores    = " COALESCE( bb.cuota_descuentos_registrados_detalle_creditos, 0 ) ";
	        $columnaValores1   = " COALESCE( bb.valor_usuario_descuentos_registrados_detalle_creditos, 0 ) ";
	        
	        $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_creditos "id_detalle",
    	        bb.cuota_descuentos_registrados_detalle_creditos "valor_descuento",
    	        bb.valor_usuario_descuentos_registrados_detalle_creditos "valor_descuento1",
                bb.cuota_descuentos_registrados_detalle_creditos,
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuentos",
    	        cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	        $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_creditos bb on bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc on cc.id_participes = bb.id_participes";
	        $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id1   = " bb.id_descuentos_registrados_detalle_creditos ";
	        	        
	        if( strlen( $busqueda_datos ) > 0 ){
	            $whe1  .= " AND ( cc.cedula_participes ILIKE '%$busqueda_datos%' OR cc.apellido_participes ILIKE '%$busqueda_datos%' OR cc.nombre_participes ILIKE '%$busqueda_datos%')";
	        }
	        
	        //$rsData    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	    }else{
	        
	        return json_encode( array( 'error'=>false,'mensaje'=>"tipo descuento no definido" ) );
	    }
	    	    
	    $html = "";
	    $resultSet=$recaudaciones->getCantidad("*", $tab1, $whe1);
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    /* para obtener Sumas*/
	    $rsSumatoria1           = $recaudaciones->getSumaColumna($columnaValores, $tab1, $whe1);
	    $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	    $rsSumatoria2           = $recaudaciones->getSumaColumna($columnaValores1, $tab1, $whe1);	   
	    $_total_archivo_final   = $rsSumatoria2[0]->suma;
	    
	    $per_page = 50; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$recaudaciones->getCondicionesPag($col1, $tab1, $whe1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    $resp = array();
	    
	    if($cantidadResult>0){
	        
	        //validacion para cabeceras
	        if( $tipo_descuento == 1 )
	        {
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.= '<th style="text-align: left;  font-size: 12px;">-</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';	           
	            $html.= '<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	            $html.= '</tr>';
	            $html.= '</thead>';
	            
	            $i=0;
	            foreach ($resultSet as $res){
	                $i++;
	                
	                $descripcion_tipo_descuento    = "APORTES";
	                $_html_boton_editar = '<span class="">
                            <a onclick="editar_descuentos(this)" id="" data-iddescuentos="'.$res->id_detalle.'" data-tipo = "aportes"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span>';
	               	  
	                $html.='<tr>';
	                $html.='<td style="font-size: 18px;">';
	                $html.= $_html_boton_editar;
	                $html.= '</td>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$descripcion_tipo_descuento.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_descuento.'</td>';
	                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_descuento1.'</td>';
	                
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
	            
	        }else if( $tipo_descuento == 2 )
	        {
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.= '<th style="text-align: left;  font-size: 12px;">-</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">#</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Cedula Participe</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Apellidos Participe</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Nombres Participe </th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Ultima Cuota</th>';
	            $html.= '<th style="text-align: left;  font-size: 12px;">Valor Sistema</th>';	            
	            $html.= '<th style="text-align: left;  font-size: 12px;">Valor Archivo</th>';
	            $html.= '</tr>';
	            $html.= '</thead>';
	            
	            $i=0;
	            foreach ($resultSet as $res){
	                $i++;
	                
	                $concepto = ""; 	                
	                $descripcion_tipo_descuento    = "CREDITOS " . $concepto;
	                
	                /*$_html_boton_editar = '<span class="">
                            <a onclick="editar_descuentos(this)" id="" data-iddescuentos="'.$res->id_detalle.'" data-tipo = "creditos"
                            href="#" class="btn btn-sm btn-default label label-warning">
                            <i class="fa fa-edit" aria-hidden="true" ></i>
                            </a></span>';*/
	                $_html_boton_editar = "";
	                
	                $valor_ultima_cuota    = $res->cuota_descuentos_registrados_detalle_creditos; 
	                    
	                $html.='<tr>';
	                $html.='<td style="font-size: 18px;">';
	                $html.= $_html_boton_editar;
	                $html.= '</td>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$descripcion_tipo_descuento.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px; text-align: right; ">'.$valor_ultima_cuota.'</td>';
	                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_descuento.'</td>';	                
	                $html.='<td style="font-size: 11px; text-align: right; ">'.$res->valor_descuento1.'</td>';	                
	                $html.='</tr>';
	            }
	            
	            $html.='</tbody>';
	            /*para totalizar las filas*/
	            $html.='<tfoot>';
	            $html.='<tr>';
	            $html.='<th colspan="6" ></th>';
	            $html.='<th style="text-align: right"; >TOTALES</th>';
	            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_sistema.'</th>';
	            $html.='<th style="text-align: right;  font-size: 12px;">'.$_total_archivo_final.'</th>';
	            $html.='</tr>';
	            $html.='</tfoot>';
	            
	        }else
	        {
	            $html.= '<h3> TIPO DE DESCUENTOS NO IDENTIFICADO </h3>';
	        }	        
	       
	        $resp['tablaHtml'] = $html;
	        $resp['paginacion'] = $recaudaciones->allpaginate("index.php", $page, $total_pages, $adjacents,"CargarDatosDescuentos");
	        $resp['sizeData'] = $cantidadResult;
	        
	        
	    }else{	        
	       
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
	        
	        $resp['tablaHtml'] = $html;
	        //$resp['paginacion'] = $recaudaciones->allpaginate("index.php", $page, $total_pages, $adjacents,"CargarDatosDescuentos");
	        //$resp['sizeData'] = $cantidadResult;
	    }
	    
	    echo json_encode( $resp );	    
	    
	}
	/******* begin dc 2020/04/29 ****/
	
	
	/******* begin dc 2020/04/30 ****/
	public function CargarDatosDescuentosDataTable(){
	    
	    try {
	        ob_start();
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        $page              = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	        $busqueda_datos    = isset($_POST['busqueda']) ? $_POST['busqueda'] : "";
	        $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	        $tipo_descuento        = $_POST['tipo_descuento'];
	        
	        if( $tipo_descuento == "1" ){
	            
	            $columnaValores    = " COALESCE( bb.aporte_personal_descuentos_registrados_detalle_aportes, 0 ) ";
	            $columnaValores1   = " COALESCE( bb.valor_usuario_descuentos_registrados_detalle_aportes, 0 ) ";
	            
	            $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_aportes "id_detalle",
    	        bb.aporte_personal_descuentos_registrados_detalle_aportes "valor_descuento",
    	        bb.valor_usuario_descuentos_registrados_detalle_aportes "valor_descuento1",
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuento",
    	        cc.id_participes , cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	            $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_aportes bb ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes";
	            $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            
	            if ( strlen( $searchDataTable ) > 0 ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	                $whe1 .= " AND (  cc.cedula_participes ILIKE '%" . $searchDataTable . "%' ";
	                $whe1 .= " OR cc.apellido_participes ILIKE '%" .$searchDataTable . "%' ";
	                $whe1 .= " OR cc.nombre_participes ILIKE  '%" . $searchDataTable . "%' )";
	            }
	            
	        }elseif ($tipo_descuento == "2" ){
	            
	            $columnaValores    = " COALESCE( bb.cuota_descuentos_registrados_detalle_creditos, 0 ) ";
	            $columnaValores1   = " COALESCE( bb.cuota_descuentos_registrados_detalle_creditos, 0 ) ";
	            
	            $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_creditos "id_detalle",
    	        bb.cuota_descuentos_registrados_detalle_creditos "valor_descuento",
    	        bb.cuota_descuentos_registrados_detalle_creditos "valor_descuento1",
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuentos",
    	        cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	            $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_creditos bb on bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc on cc.id_participes = bb.id_participes";
	            $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            
	            if ( strlen( $searchDataTable ) > 0 ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	                $whe1 .= " AND (  cc.cedula_participes ILIKE '%" . $searchDataTable . "%' ";
	                $whe1 .= " OR cc.apellido_participes ILIKE '%" .$searchDataTable . "%' ";
	                $whe1 .= " OR cc.nombre_participes ILIKE  '%" . $searchDataTable . "%' )";
	            }
	            
	        }
	        
	        $rsCantidad    = $recaudaciones->getCantidad("*", $tab1, $whe1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /* para obtener Sumas*/
	        $rsSumatoria1           = $recaudaciones->getSumaColumna($columnaValores, $tab1, $whe1);
	        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	        $rsSumatoria2           = $recaudaciones->getSumaColumna($columnaValores1, $tab1, $whe1);
	        $_total_archivo_final   = $rsSumatoria2[0]->suma;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => 'cc.id_participes',
	            1 => 'cc.cedula_participes',
	            2 => 'cc.apellido_participes',
	            3 => 'cc.nombre_participes'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$recaudaciones->getCondicionesSinOrden($col1, $tab1, $whe1, $limit);
	        $total_pages = ceil( $cantidadBusqueda/$per_page );
	        
	        $ql = " SELECT $col1 FROM $tab1 WHERE $whe1  $limit ";
	        
	        $cantidadBusquedaFiltrada = sizeof($resultSet);
	        
	        
	        $cantidadBusquedaFiltrada = 3;
	        
	        $arrayData = array();
	        
	        $arrayData[] = array('opciones'=>"<button>hola</button>",'id_cabeza'=> "30668", 'id_detalle'=> "764720", 'valor_descuento'=> "259.55", 'valor_descuento1'=> "259.55");
	        $arrayData[] = array('opciones'=>"<button>hola</button>",'id_cabeza'=> "30668", 'id_detalle'=> "764722", 'valor_descuento'=> "335.82", 'valor_descuento1'=> "335.82");
	        $arrayData[] = array('opciones'=>"<button>hola</button>",'id_cabeza'=> "30668", 'id_detalle'=> "764720", 'valor_descuento'=> "259.55", 'valor_descuento1'=> "259.55");
	        
	        
	        $respuestakasjkajsd = json_decode('[0: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764719", valor_descuento: "302.24", valor_descuento1: "302.24"}
	    1: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764720", valor_descuento: "259.55", valor_descuento1: "259.55"}
	    2: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764721", valor_descuento: "330.54", valor_descuento1: "330.54"}
	    3: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764722", valor_descuento: "335.82", valor_descuento1: "335.82"}
	    4: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764723", valor_descuento: "395.49", valor_descuento1: "395.49"}
	    5: {opciones:"<button>hola</button>",id_cabeza: "30668", id_detalle: "764724", valor_descuento: "255.35", valor_descuento1: "255.35"}');
	        
	        //$resultSet esta variable le envio como devuelve el entidad base porq contiene el formato solicitado por el plugin
	        //[{"id_cabeza":"30668","id_detalle":"764719","valor_descuento":"302.24"}]
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception();
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	            "recordsFiltered" => intval($cantidadBusquedaFiltrada), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => $arrayData,   // total data array
	            "sql" => $ql
	        );
	        
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => "",
	            "buffer" => error_get_last()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	    
	}
	/******* begin dc 2020/04/29 ****/
	
	/******* begin dc 2020/04/30 ****/
	public function DataTableCargarDatosDetallesDescuentos(){
	    
	    try {
	        ob_start();
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        $page              = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	        $busqueda_datos    = isset($_POST['busqueda']) ? $_POST['busqueda'] : "";
	        $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	        $tipo_descuento        = $_POST['tipo_descuento'];
	        
	        if( $tipo_descuento == "1" ){
	            
	            $columnaValores    = " COALESCE( bb.aporte_personal_descuentos_registrados_detalle_aportes, 0 ) ";
	            $columnaValores1   = " COALESCE( bb.valor_usuario_descuentos_registrados_detalle_aportes, 0 ) ";
	            
	            $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_aportes "id_detalle",
    	        bb.aporte_personal_descuentos_registrados_detalle_aportes "valor_descuento",
    	        bb.valor_usuario_descuentos_registrados_detalle_aportes "valor_descuento1",
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuento",
    	        cc.id_participes , cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	            $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_aportes bb ON bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes";
	            $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            
	            if ( strlen( $searchDataTable ) > 0 ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	                $whe1 .= " AND (  cc.cedula_participes ILIKE '%" . $searchDataTable . "%' ";
	                $whe1 .= " OR cc.apellido_participes ILIKE '%" .$searchDataTable . "%' ";
	                $whe1 .= " OR cc.nombre_participes ILIKE  '%" . $searchDataTable . "%' )";
	            }
	            
	        }elseif ($tipo_descuento == "2" ){
	            
	            $columnaValores    = " COALESCE( bb.cuota_descuentos_registrados_detalle_creditos, 0 ) ";
	            $columnaValores1   = " COALESCE( bb.cuota_descuentos_registrados_detalle_creditos, 0 ) ";
	            
	            $col1  = 'aa.id_descuentos_registrados_cabeza "id_cabeza",
    	        bb.id_descuentos_registrados_detalle_creditos "id_detalle",
    	        bb.cuota_descuentos_registrados_detalle_creditos "valor_descuento",
    	        bb.cuota_descuentos_registrados_detalle_creditos "valor_descuento1",
    	        aa.usuario_descuentos_registrados_cabeza "usuario_descuentos",
    	        cc.id_participes, cc.cedula_participes, cc.apellido_participes, cc.nombre_participes';
	            $tab1  = "core_descuentos_registrados_cabeza aa
    	        INNER JOIN core_descuentos_registrados_detalle_creditos bb on bb.id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza
    	        INNER JOIN core_participes cc on cc.id_participes = bb.id_participes";
	            $whe1  = " 1 = 1
                AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	            
	            if ( strlen( $searchDataTable ) > 0 ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	                $whe1 .= " AND (  cc.cedula_participes ILIKE '%" . $searchDataTable . "%' ";
	                $whe1 .= " OR cc.apellido_participes ILIKE '%" .$searchDataTable . "%' ";
	                $whe1 .= " OR cc.nombre_participes ILIKE  '%" . $searchDataTable . "%' )";
	            }
	            
	        }
	        
	        $rsCantidad    = $recaudaciones->getCantidad("*", $tab1, $whe1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /* para obtener Sumas*/
	        $rsSumatoria1           = $recaudaciones->getSumaColumna($columnaValores, $tab1, $whe1);
	        $_total_archivo_sistema = $rsSumatoria1[0]->suma;
	        $rsSumatoria2           = $recaudaciones->getSumaColumna($columnaValores1, $tab1, $whe1);
	        $_total_archivo_final   = $rsSumatoria2[0]->suma;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => 'cc.id_participes',
	            1 => 'cc.cedula_participes',
	            2 => 'cc.apellido_participes',
	            3 => 'cc.nombre_participes',
	            4 => 'aa.id_descuentos_registrados_cabeza'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $sql = " SELECT $col1 FROM $tab1 WHERE $whe1  $limit ";	
	        
	        $resultSet=$recaudaciones->getCondicionesSinOrden($col1, $tab1, $whe1, $limit);
	        $total_pages = ceil( $cantidadBusqueda/$per_page );
	        
	        $cantidadBusquedaFiltrada = sizeof($resultSet);	        
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception($salida);
	            
	            $json_data = array(
	                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	                "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	                "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
	                "data" => $resultSet,   // total data array
	                "sql" => $sql
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => "",
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	    
	}
	/******* begin dc 2020/05/01 ****/
		
	
	function getProducts($DBconnect)
	{
	    //  echo "test";
	    
	    /* Database connection end */
	    // storing  request (ie, get/post) global array to a variable
	    $requestData = $_REQUEST;
	    $columns = array(
	    // datatable column index  => database column name
	        0 => 'product_name',
	        1 => 'price',
	        2 => 'category'
	    );
	    // getting total number records without any search
	    $sql = "SELECT product_name, price, category ";
	    $sql .= " FROM products";
	    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get products");
	    $totalData = mysqli_num_rows($query);
	    $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
	    $sql = "SELECT product_name, price, category ";
	    $sql .= " FROM products WHERE 1=1";
	    if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	        $sql .= " AND ( product_name LIKE '" . $requestData['search']['value'] . "%' ";
	        $sql .= " OR price LIKE '" . $requestData['search']['value'] . "%' ";
	        $sql .= " OR category LIKE '" . $requestData['search']['value'] . "%' )";
	    }
	    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get products");
	    $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
	    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	    /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length. */
	    $query = mysqli_query($DBconnect, $sql) or die("Mysql Error in getting : get products");
	    
	    $data = array();
	    while ($row = mysqli_fetch_array($query)) {  // preparing an array
	        $nestedData = array();
	        $nestedData[] = $row["product_name"];
	        $nestedData[] = $row["price"];
	        $nestedData[] = $row["category"];
	        
	        $data[] = $nestedData;
	    }
	    $json_data = array(
	        "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	        "recordsTotal" => intval($totalData),  // total number of records
	        "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	        "data" => $data   // total data array
	    );
	    echo json_encode($json_data);  // send data as json format
	    
	}
	
	public function DataTableListarDescuentos(){
	    
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        $id_entidad_patronal   = $_POST['id_entidad_patronal'];
	        $id_descuentos_formatos= $_POST['id_descuentos_formatos'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	        //$_usuario_logueado = $_SESSION['usuario_usuarios'];
	        
	        $columnas1 = " aa.id_descuentos_registrados_cabeza,aa.fecha_proceso_descuentos_registrados_cabeza,bb.id_entidad_patronal,bb.nombre_entidad_patronal,
    	    aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza,aa.usuario_descuentos_registrados_cabeza, DATE(aa.modificado) AS modificado,
    	    (SELECT COUNT(1) FROM core_descuentos_registrados_detalle_aportes where id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza) as \"cantidad_aportes\",
    	    (SELECT COUNT(1) FROM core_descuentos_registrados_detalle_creditos where id_descuentos_registrados_cabeza = aa.id_descuentos_registrados_cabeza) as \"cantidad_creditos\"";
	        $tablas1   = "core_descuentos_registrados_cabeza aa
	        INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	        $where1    = " 1 = 1 ";
	        
	        //aqui poner para filtrar si es por session
	        
	        /* PARA FILTROS DE CONSULTA */
	        $where1 .= " AND bb.id_entidad_patronal = $id_entidad_patronal ";
	        
	        if( (int)$id_descuentos_formatos > 0 ){
	            
	            $where1 .= " AND aa.id_descuentos_formatos = $id_descuentos_formatos ";
	        }
	        	        
	        if( strlen( $searchDataTable ) > 0 ){
	            $where1 .= " AND ( ";
	            $where1 .= " bb.nombre_entidad_patronal ILIKE '%$searchDataTable%' ";
	            $where1 .= " OR TO_CHAR( aa.fecha_proceso_descuentos_registrados_cabeza, 'YYYY-MM-DD' ) ILIKE '%$searchDataTable%'";
	            $where1 .= " ) ";

	        }
	        
	        $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => 'aa.id_descuentos_registrados_cabeza',
	            1 => 'aa.fecha_proceso_descuentos_registrados_cabeza',
	            2 => 'bb.nombre_entidad_patronal',
	            3 => 'aa.id_descuentos_registrados_cabeza',
	            4 => 'aa.id_descuentos_registrados_cabeza',
	            5 => 'aa.year_descuentos_registrados_cabeza',
	            6 => 'aa.mes_descuentos_registrados_cabeza',
	            7 => 'aa.usuario_descuentos_registrados_cabeza',
	            8 => 'aa.modificado',	            
	            9 => 'aa.id_descuentos_registrados_cabeza',
	        );	       
	        	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	        //$sql = "";
	        
	        $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        //$cantidadBusquedaFiltrada = sizeof($resultSet);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++; 
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
	            
	            $opciones = ""; //variable donde guardare los datos creados automaticamente
	            
	            $opciones = '<div class="pull-right ">
                            <span > 
                                <a onclick="verDatosDescuentos(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'" href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Ver Detalles"> <i class="fa  fa-building-o" aria-hidden="true" ></i>
	                           </a>
                            </span>

                            <span >
	                           <a onclick="genArchivoDetallado(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'" href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Detallado"><i class="fa fa-files-o text-info" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            <span>
	                           <a onclick="genArchivoEntidad(this)" id="" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'" href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Archivo Entidad"><i class="fa fa-file-text-o text-info" aria-hidden="true" ></i>
	        </a>
                            </span>
                            <span >
	                           <a onclick="imprimir_reporte_descuentos(this)" data-codtipodescuento="'.$cod_tipo_descuento.'" data-iddescuentos="'.$res->id_descuentos_registrados_cabeza.'" href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Ver Reporte">
                                <i class="fa fa-file-pdf-o text-warning" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
	            
	            
	            
	            /*$html.='<span class="pull-right ">
	             <a onclick="ValidarEdicionGenerados(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
	             href="#" class="btn btn-sm btn-default label " data-toggle="tooltip" data-placement="top" title="Editar">
	             <i class="fa fa-edit text-warning" aria-hidden="true" ></i>
	             </a></span></td>';
	             $html.='<td style="font-size: 18px;">';*/
	            /*$html.='<span class="pull-right ">
	             <a onclick="eliminarRegistro(this)" id="" data-idarchivo="'.$res->id_descuentos_registrados_cabeza.'"
	             href="#" class="btn btn-sm btn-default label" data-toggle="tooltip" data-placement="top" title="Eliminar">
	             <i class="fa fa-trash text-danger" aria-hidden="true" ></i>
	             </a></span>';
	             $html.='</td>';*/
	            
	            /*$html.='<td style="font-size: 18px;">';
	             $html.='<span class="pull-right ">
	             <a onclick="editAporte(this)" id="" data-idarchivo="'.$res->id_archivo_recaudaciones_detalle.'"
	             href="#" class="btn btn-sm btn-default label label-warning">
	             <i class="fa fa-edit" aria-hidden="true" ></i>
	             </a></span></td>';*/
	            	            
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['fecha_descuentos']  = $res->fecha_proceso_descuentos_registrados_cabeza;
	            $dataFila['nombre_entidad_patronal']   = $res->nombre_entidad_patronal;
	            $dataFila['tipo_descuentos']   = $tipo_descuento;
	            $dataFila['cantidad_descuentos']   = $cantidad_descuentos;
	            $dataFila['anio_descuentos']   = $res->year_descuentos_registrados_cabeza;
	            $dataFila['mes_descuentos']    = $this->devuelveMesNombre($res->mes_descuentos_registrados_cabeza);
	            $dataFila['usuario_usuarios']  = $res->usuario_descuentos_registrados_cabeza;
	            $dataFila['modificado']        = $res->modificado;
	            $dataFila['opciones']          = $opciones;
	            //$dataFila['id_cabeza']         = '12345';
	           
	            
	            $data[] = $dataFila;
	        }
	        
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception($salida);
	            
	            $json_data = array(
	                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	                "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	                "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
	                "data" => $data,   // total data array
	                "sql" => $sql
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => "",
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);	    
	    
	}
	
	/** 2020/05/05 cambios **/
	public function generarReporteDescuentos(){
	    
	    session_start();
	    
	    $recaudaciones = new RecaudacionesModel();
	    	    
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
	    	    
	    
	    $dictionay = array();
	    $dictionay['TITULO']   = "REPORTE DE DESCUENTOS ENVIADOS A:";
	    
	    /** BUSCANDO DATOS DE DESCUENTO **/
	    $id_descuentos_cabeza  = $_POST['id_descuentos_cabeza'];
	    $tipo_descuento        = $_POST['tipo_descuento'];
	    
	    $col1  = " aa.id_descuentos_registrados_cabeza, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza, bb.id_entidad_patronal,
                bb.nombre_entidad_patronal, bb.codigo_entidad_patronal ";
	    $tab1  = " core_descuentos_registrados_cabeza aa
	           INNER JOIN core_entidad_patronal bb	ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	    $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	    $id1   = " aa.id_descuentos_registrados_cabeza ";
	    
	    $rsConsulta1 = $recaudaciones->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
	    $anio_descuentos   =  $rsConsulta1[0]-> year_descuentos_registrados_cabeza;
	    $mes_descuentos    =  $rsConsulta1[0]-> mes_descuentos_registrados_cabeza;
	    $nombre_entidad    =  $rsConsulta1[0]-> nombre_entidad_patronal;
	    
	    $dictionay['ANIO_DESCUENTOS']  = $anio_descuentos;
	    $dictionay['MES_DESCUENTOS']   = $this->devuelveMesNombre($mes_descuentos);
	    $dictionay['NOMBRE_ENTIDAD']   = $nombre_entidad;
	    
	    /** PARA GRAFICAR EL DETALLE DE LOS DESCUENTOS **/
	    
	    $html  = "";
	    $rsConsulta2 = array();
	    
	    $sumatoria_total   = 0.00;
	    if( $tipo_descuento == 1 ){
	        
	        $nombre_descuentos = "Aportes Personales";
	        $col2  = " aa.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.aporte_personal_descuentos_registrados_detalle_aportes,
	               COALESCE(aa.valor_usuario_descuentos_registrados_detalle_aportes,0) as valor_usuario_descuentos_registrados_detalle_aportes ";
	        $tab2  = " core_descuentos_registrados_detalle_aportes aa
	               INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	        $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id2   = " aa.id_descuentos_registrados_detalle_aportes ";
	        
	        $rsConsulta2 = $recaudaciones->getCondiciones($col2, $tab2, $whe2, $id2);
	        
	        $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
	        $html.='<tr>';
	        $html.='<th >N°</th>';
	        $html.='<th >Tipo Descuento</th>';
	        $html.='<th >Cedula</th>';
	        $html.='<th >Nombre</th>';
	        $html.='<th >Cuota</th>';
	        $html.='<th >Mes</th>';
	        $html.='</tr>';
	        
	        $index = 0;
	        foreach ( $rsConsulta2 as $res ){
	            
	            $index++;
	            $sumatoria_total   += $res->valor_usuario_descuentos_registrados_detalle_aportes;
	            $html.='<tr>';
	            $html.='<td >'.$index.'</td>';
	            $html.='<td >'.$nombre_descuentos.'</td>';
	            $html.='<td >'.$res->cedula_participes.'</td>';
	            $html.='<td >'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
	            $html.='<td >'.$res->valor_usuario_descuentos_registrados_detalle_aportes.'</td>';
	            $html.='<td >'.$mes_descuentos.'</td>';
	            $html.='</tr>';
	            
	            
	        }
	        
	        $html.='<tr>';
	        $html.='<td colspan="5">TOTAL</td>';
	        $html.='<td >'.$sumatoria_total.'</td>';	        
	        $html.='</tr>';
	        
	        $html  .= "</table>";
	        
	    }elseif( $tipo_descuento == 2 ){
	        
	        $col2  = " bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.cuota_descuentos_registrados_detalle_creditos,
	        cc.id_creditos, cc.numero_creditos, dd.id_tipo_creditos, dd.nombre_tipo_creditos, aa.mora_descuentos_registrados_detalle_creditos";
	        $tab2  = " core_descuentos_registrados_detalle_creditos aa 
                INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
    	        INNER JOIN core_creditos cc ON cc.id_creditos = aa.id_creditos
    	        INNER JOIN core_tipo_creditos dd on dd.id_tipo_creditos = cc.id_tipo_creditos";
	        $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id2   = " aa.id_descuentos_registrados_detalle_creditos";	        
	        	        
	        $rsConsulta2 = $recaudaciones->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	        
	        $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
	        $html.='<tr>';
	        $html.='<th >N°</th>';
	        $html.='<th >Tipo Descuento</th>';
	        $html.='<th >Cedula</th>';
	        $html.='<th >Nombre</th>';
	        $html.='<th >Cuota</th>';
	        $html.='<th >Mora</th>';
	        $html.='<th >Monto</th>';
	        $html.='<th >Mes</th>';
	        $html.='</tr>';
	        
	        $index = 0;
	        foreach ( $rsConsulta2 as $res ){
	            
	            $index++;
	            
	            $cuota     = $res->cuota_descuentos_registrados_detalle_creditos;
	            $mora      = $res->mora_descuentos_registrados_detalle_creditos;
	            $monto     = $cuota + $mora;
	            $sumatoria_total   += $monto;
	            
	            $html.='<tr>';
	            $html.='<td >'.$index.'</td>';
	            $html.='<td >'.$res->nombre_tipo_creditos.'</td>';
	            $html.='<td >'.$res->cedula_participes.'</td>';
	            $html.='<td >'.$res->apellido_participes.' '.$res->nombre_participes.'</td>';
	            $html.='<td >'.$cuota.'</td>';
	            $html.='<td >'.$mora.'</td>';
	            $html.='<td >'.$monto.'</td>';
	            $html.='<td >'.$mes_descuentos.'</td>';
	            $html.='</tr>';
	            
	            
	        }
	        
	        $html.='<tr>';
	        $html.='<td colspan="6">TOTAL</td>';
	        $html.='<td >'.$sumatoria_total.'</td>';
	        $html.='<td ></td>';
	        $html.='</tr>';
	        
	        $html  .= "</table>";
	        
	    }else{
	        $html = "";
	    }
	    
	    $dictionay['DETALLE_DESCUENTOS']   = $html;
	    
	    $this->verReporte( "ReporteDescuentos", array( 'datos_empresa'=>$datos_empresa, 'dictionary'=>$dictionay ) );
	       
	    
	}
	/** end dc 2020/05/05 cambios **/
	
	
	/** 2020/05/06 cambios **/
	public function imprimirReporteDescuentos(){
	    
	    session_start();
	    
	    $recaudaciones = new RecaudacionesModel();
	    
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
	        $datos_empresa['USUARIOEMPRESA']=( isset( $_SESSION['usuario_usuarios'] ) ) ? $_SESSION['usuario_usuarios'] : '';
	    }
	    
	    $dictionary = array();
	    
	    $id_descuentos_cabeza = $_POST['id_descuentos_cabeza'];
	    $tipo_descuento       = $_POST['tipo_descuento'];
	    
	    $col1  = " aa.id_descuentos_registrados_cabeza, aa.year_descuentos_registrados_cabeza, aa.mes_descuentos_registrados_cabeza, bb.id_entidad_patronal,
                bb.nombre_entidad_patronal, bb.codigo_entidad_patronal ";
	    $tab1  = " core_descuentos_registrados_cabeza aa
	           INNER JOIN core_entidad_patronal bb	ON bb.id_entidad_patronal = aa.id_entidad_patronal";
	    $whe1  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	    $id1   = " aa.id_descuentos_registrados_cabeza ";
	    
	    $rsConsulta1    = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	    
	    $nombre_entidad_patronal   = $rsConsulta1[0]->nombre_entidad_patronal;
	    $mes_descuentos            = $rsConsulta1[0]->mes_descuentos_registrados_cabeza;
	    $anio_descuentos           = $rsConsulta1[0]->year_descuentos_registrados_cabeza;
	    
	    $dictionary['TITULO']  = "REPORTE DE DESCUENTOS ENVIADOS A: ";
	    $dictionary['ENTIDAD_PATRONAL']  = $nombre_entidad_patronal;
	    $dictionary['MES_DESCUENTOS']    = $this->devuelveMesNombre( $mes_descuentos );
	    $dictionary['ANIO_DESCUENTOS']   = $anio_descuentos;
	    
	    $html = "";
	    $suma_total = 0.00;
	    
	    $hayDatos  = false;
	    
	    if( $tipo_descuento == 1 ){
	        
	        $col2  = " aa.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes, aa.aporte_personal_descuentos_registrados_detalle_aportes,
	               COALESCE(aa.valor_usuario_descuentos_registrados_detalle_aportes,0) AS valor_usuario_descuentos_registrados_detalle_aportes ";
	        $tab2  = " core_descuentos_registrados_detalle_aportes aa
	               INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes";
	        $whe2  = " COALESCE( aa.valor_usuario_descuentos_registrados_detalle_aportes ) > 0 AND aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $id2   = " aa.id_descuentos_registrados_detalle_aportes ";
	        
	        $rsConsulta2   = $recaudaciones->getCondiciones($col2, $tab2, $whe2, $id2);
	        
	        $hayDatos = ( !empty( $rsConsulta2 ) ) ? true : false;
	        
	        $html.='<table class="1" cellspacing="0" border="1">';
	        $html.='<tr>';
	        $html.='<th >N°</th>';
	        $html.='<th >Tipo Descuento</th>';
	        $html.='<th >Cedula</th>';
	        $html.='<th >Nombre</th>';
	        $html.='<th >Monto</th>';
	        $html.='</tr>';
	        	        
	        $index = 0;
	        $nombre_descuentos = "Aporte Personal";
	        foreach ( $rsConsulta2 as $res ){
	            
	            $valor = $res->valor_usuario_descuentos_registrados_detalle_aportes;
	            $suma_total    += $valor;
	            $index++;
	            $html.='<tr>';
	            $html.='<td >'.$index.'</td>';
	            $html.='<td >'.$nombre_descuentos.'</td>';
	            $html.='<td >'.$res->cedula_participes.'</td>';
	            $html.='<td >'.$res->apellido_participes.'-'.$res->nombre_participes.'</td>';
	            $html.='<td class="decimales" >'.number_format($valor,2,'.',',').'</td>';
	            $html.='</tr>';
	        }
	        
	        $html.='<tr>';
	        $html.='<th colspan="4" class="centrado">TOTAL</th>';
	        $html.='<th class="decimales" >'.number_format($suma_total,2,'.',',').'</th>';
	        $html.='</tr>';
	        
	        $html.='</table>';
	        
	        
	    }elseif( $tipo_descuento == 2 ){
	        
	        $col2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes,
    	        SUM( COALESCE( aa.mora_descuentos_registrados_detalle_creditos, 0) ) suma_mora ,
    	        SUM( COALESCE(aa.valor_usuario_descuentos_registrados_detalle_creditos,0) ) suma_valor";
	        $tab2  = " core_descuentos_registrados_detalle_creditos aa
	           INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes ";
	        $whe2  = " aa.id_descuentos_registrados_cabeza = $id_descuentos_cabeza";
	        $gru2  = " bb.id_entidad_patronal,bb.id_participes, bb.cedula_participes, bb.apellido_participes, bb.nombre_participes";
	        $id2   = " bb.cedula_participes ";
	        
	        $rsConsulta2   = $recaudaciones->getCondiciones_grupo($col2, $tab2, $whe2, $gru2, $id2);
	      	        
	        $hayDatos = ( !empty( $rsConsulta2 ) ) ? true : false;
	        
	        $html.='<table class="1" cellspacing="0"  border="1">';
	        $html.='<tr>';
	        $html.='<th >N°</th>';
	        $html.='<th >Tipo Descuento</th>';
	        $html.='<th >Cedula</th>';
	        $html.='<th >Nombre</th>';
	        $html.='<th >Mora</th>';
	        $html.='<th >Monto</th>';
	        $html.='</tr>';
	        
	        $index = 0;
	        foreach ( $rsConsulta2 as $res ){
	            
	            $cuota = $res->suma_valor;
	            $mora  = $res->suma_mora;
	            $monto = $cuota;
	            $suma_total    += $monto;
	            $index++;
	            
	            $html.='<tr>';
	            $html.='<td >'.$index.'</td>';
	            $html.='<td >'."Descuento Creditos".'</td>';
	            $html.='<td >'.$res->cedula_participes.'</td>';
	            $html.='<td >'.$res->apellido_participes.'-'.$res->nombre_participes.'</td>';
	            $html.='<td class="decimales">'.number_format($mora,2,'.',',').'</td>';
	            $html.='<td class="decimales">'.number_format($monto,2,'.',',').'</td>';
	            $html.='</tr>';
	        }
	        
	        $html.='<tr>';
	        $html.='<th colspan="5" class="centrado">TOTAL</th>';
	        $html.='<th class="decimales">'.number_format($suma_total,2,'.',',').'</th>';
	        $html.='</tr>';
	        
	        $html.='</table>';
	        
	    }else{
	        $html = "";
	    }
	    
	    $textoDataEmpty    = '<h4 class="dataempty"> NO EXISTEN DATOS PARA MOSTRAR </h4>';
	    
	    if( $hayDatos ){
	        
	        $dictionary['DETALLE_DESCUENTOS']   = $html;
	    }else{
	        $dictionary['DETALLE_DESCUENTOS']   = $textoDataEmpty;
	    }
	    	    
	    $this->verReporte( "ReporteDescuentos2", array( 
	        'datos_empresa'=> $datos_empresa,
	        'dictionary'   => $dictionary
           ) 
	    ) ;
	    
	}
	/** end dc 2020/05/06 **/
	
	/** dc 2020/06/22 **/
	public function DataTablePreviewDescuentosCreditos(){
	    
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //variables para enviar a  la vista 
	        $valor_parcial = 0.00;
	        $valor_total   = 0.00;
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	        //$_usuario_logueado = $_SESSION['usuario_usuarios'];
	        
	        $id_entidad_patronal = $_POST['id_entidad_patronal'];
	        $mes_recaudacion     = $_POST['mes_recaudacion'];
	        $anio_recaudacion    = $_POST['anio_recaudacion'];
	        
	        $columnas1 = " aa.id_descuentos_registrados_detalle_valores_creditos, bb.id_entidad_patronal, bb.nombre_entidad_patronal, aa.cedula_participes, aa.nombres_participes,
	           aa.mes_descuento, aa.sueldo_liquido, aa.cuota, aa.mora, aa.total, aa.valor_usuario_valores_creditos as total_usuario, aa.nombre_tipo_creditos, aa.tipo_afiliado";
	        $tablas1   = " core_descuentos_registrados_detalle_valores_creditos aa
	           INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal ";
	        $where1    = " aa.id_entidad_patronal = $id_entidad_patronal 
               AND aa.mes = $mes_recaudacion AND aa.anio = $anio_recaudacion";
	        
	        //aqui la busqueda total sin filtros
	        $colSum    = "COALESCE( SUM( case when valor_usuario_valores_creditos  is null then total else valor_usuario_valores_creditos  end ), 0 ) total_descuento";
	        $rsTotal = $recaudaciones->getCondicionesSinOrden( $colSum, $tablas1, $where1, "");
	        $valor_total   = $rsTotal[0]->total_descuento;
	       	        
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            $where1 .= " AND ( ";
	            $where1 .= " aa.cedula_participes ILIKE '%$searchDataTable%' ";
	            $where1 .= " OR aa.nombres_participes ILIKE '%$searchDataTable%'";
	            $where1 .= " ) ";
	            
	        }
	        
	        $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => '1',
	            1 => '1',
	            2 => '1',
	            3 => '1',
	            4 => '1',
	            5 => '1',
	            6 => '1',
	            7 => '1'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        //para validar que consulte todos
	        $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
	        
	        $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	        //$sql = "";
	        
	        $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        //aqui la busqueda total parcial
	        $colSum    = "COALESCE( SUM( case when valor_usuario_valores_creditos  is null then total else valor_usuario_valores_creditos  end ), 0 ) total_descuento";
	        $rsParcial = $recaudaciones->getCondicionesSinOrden( $colSum, $tablas1, $where1, $limit);
	        $valor_parcial   = $rsParcial[0]->total_descuento;
	        	        
	        //$cantidadBusquedaFiltrada = sizeof($resultSet);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;	            
	            
	            $opciones = ""; //variable donde guardare los datos creados automaticamente
	            
	            $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_valores_descuentos_creditos(this)" id="" data-id_descuento_valor_credito="'.$res->id_descuentos_registrados_detalle_valores_creditos.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Editar Valores"> <i class="fa  fa-edit fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
	            
	            $total_usuario = $res->total_usuario;
	            if( empty($total_usuario) )
	            {
	                $total_usuario = $res->total;
	            }
	          	            
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
	            $dataFila['tipo_afiliado'] = $res->tipo_afiliado;
	            $dataFila['cedula']   = $res->cedula_participes;	           
	            $dataFila['nombre']   = $res->nombres_participes;
	            $dataFila['nombre_credito']    = $res->nombre_tipo_creditos;
	            $dataFila['fecha']    = $res->mes_descuento;
	            $dataFila['sueldo']   = $res->sueldo_liquido;
	            $dataFila['cuota']    = $res->cuota;	            
	            $dataFila['mora']   = $res->mora;
	            $dataFila['total']    = $res->total;
	            $dataFila['total_usuario']    = $total_usuario;
	            $dataFila['opciones'] = $opciones;
	            //$dataFila['id_cabeza']         = '12345';
	           
	            $data[] = $dataFila;
	        }
	        
	        //para valores de pie datetable
	        $totales = array();
	        $totales['parcial']    = $valor_parcial;
	        $totales['total']      = $valor_total;
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception($salida);
	            
	            $json_data = array(
	                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	                "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	                "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
	                "data" => $data,   // total data array
	                "sql" => "",//$sql
	                "totales" => $totales //valores totales
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => $sql,
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	    
	}
	
	/** end dc 2020/06/22 **/
	
	/** dc 2020/06/23 **/
	public function cambiarDescuentosCreditos()
	{	    	    
	    ob_start();
	    
	    try {
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //variables de la vista
	        $id_descuentos_creditos    = $_POST['id_descuentos_creditos'];
	        $valor_descuento       = $_POST['valor_descuento'];
	        
	        $error = error_get_last();
	        if( !empty($error) ){    throw new Exception("Variables no definidas"); }
	        
	        if( !is_numeric( $valor_descuento ) )
	        {
	            throw new Exception(" Valor recibido no es un numero");
	        }
	        
	        $colval = " valor_usuario_valores_creditos = '$valor_descuento' ";
	        $tabla = " public.core_descuentos_registrados_detalle_valores_creditos ";
	        $where = " id_descuentos_registrados_detalle_valores_creditos = '$id_descuentos_creditos'";
	        
	        $resultado = $recaudaciones->ActualizarBy($colval, $tabla, $where);
	        	        
	        if( !empty( pg_last_error() ) ){
	            throw new Exception("Actualizacion No realizada");
	        }
	        
	        $resp = array();
	        $resp['estatus']   = "OK";
	        $resp['mensaje']   = " Filas Actualizadas (".$resultado.")";
	        
	        $buffer    = ob_get_clean();
	        if( !empty( $buffer ) )
	        {
	            throw new Exception("Salida se encuentra lleno");
	        }
	        
	        echo json_encode($resp);
	        
	    } catch (Exception $e) 
	    {
	        $buffer = error_get_last();
	        echo '<message> Error \n '.$e->getMessage().' -- '.$buffer['message'].'<message>';
	    }
	    	    
	}
	/** end dc 2020/06/23 **/
	
	/** dc 2020/06/25 **/
	public function aceptarDescuentosCreditos()
	{
	    ob_start();
	    
	    try {
	        
	        $usuario_usuarios  = "";
	        $id_usuarios       = 'null';
	        $nombreArchivo     = "";
	        if( !isset( $_SESSION ) )
	        {
	            session_start();
	        }
	        
	        $usuario_usuarios  = $_SESSION['usuario_usuarios'];
	        $id_usuarios       = $_SESSION['id_usuarios'];
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //variables de la vista
	        $id_entidad_patronal   = $_POST['id_entidad_patronal'];
	        $id_formato_descuentos = $_POST['id_descuentos_formatos'];
	        $anio_recaudacion      = $_POST['anio_recaudacion'];
	        $mes_recaudacion       = $_POST['mes_recaudacion'];
	        
	        if( !empty( error_get_last() ) )
	        {
	            throw new Exception( error_get_last()['message'] );
	        }
	        	        
	        //empieza transaccionalidad
	        $recaudaciones->beginTran();
	        
	        //para obtener fecha actual
	        $Ofecha_actual = new DateTime();
	        //$fecha->modify('last day of this month');
	        $fecha_actual  = $Ofecha_actual->format('Y-m-d');
	        $fec_dia_actual= $Ofecha_actual->format('d');
	        
	        //para objeto de fecha actual
	        $Ofecha_moras  = new DateTime();
	        
	        //validacion de fecha mora
	        if( (int)$fec_dia_actual > 0 && (int)$fec_dia_actual < 6 )
	        {
	            $fec_mes_moras = (int)$mes_recaudacion - 1;
	            $str_fec_moras = $anio_recaudacion."-".$fec_mes_moras."-01";
	            $Ofecha_moras  = new DateTime($str_fec_moras);
	            
	        }else
	        {
	            $str_fec_moras = $anio_recaudacion."-".$mes_recaudacion."-01";
	            $Ofecha_moras  = new DateTime($str_fec_moras);
	        }
	        
	        //fecha de moras
	        $fecha_moras_porcesar  = $Ofecha_moras->format('Y-m-t');	        
	        
	        //variables de parametros de cabecera
	        $fecha_descuentos  = $fecha_actual;
	        $fecha_proceso     = $fecha_moras_porcesar;
	        $id_tipo_credito   = "null";
	        $observacion_descue= "";
	        $nombreArchivo     = "ArchivoEnviado.txt";
	        $procesado_descuen = "t";
	        $error_desccuentos = "f";
	        
	        /*configurar estructura mes de consulta*/
	        $mes_recaudacion = str_pad( $mes_recaudacion, 2, "0", STR_PAD_LEFT);
	        
	        /** SE REALIZA EL INSERTADO DE LA CABECERA PRIMERO **/
	        $funcion = "core_ins_descuentos_registrados_cabeza";
	        	        
	        //creacion de la variable parametros
	        $parametros = "";
	        $parametros .= $id_entidad_patronal.",";
	        $parametros .= $anio_recaudacion.",";
	        $parametros .= $mes_recaudacion.",";
	        $parametros .= "'".$usuario_usuarios."',";
	        $parametros .= $id_usuarios.",";
	        $parametros .= "'".$fecha_descuentos."',";
	        $parametros .= "'".$nombreArchivo."',";
	        $parametros .= $id_formato_descuentos.",";
	        $parametros .= "'".$procesado_descuen."',";
	        $parametros .= "'".$error_desccuentos."',";
	        $parametros .= $id_tipo_credito.",";
	        $parametros .= "'".$observacion_descue."',";
	        $parametros .= "'".$fecha_proceso."'";
	        
	        // la cabecera se genera directo cuando es del tipo aportes
	        $sqRecaudaciones    = $recaudaciones->getconsultaPG($funcion, $parametros);
	        $resultado  = $recaudaciones->llamarconsultaPG($sqRecaudaciones);
	        
	        $id_descuentos_registrados_cabeza = $resultado[0];
	        
	        if( !empty( error_get_last() ) )
	        {
	            throw new Exception( "Datos 'core_descuentos_cabeza" );
	        }
	        
	      
	        
	        $col1  = "aa.id_entidad_patronal, aa.anio, aa.mes, cc.id_participes, bb.id_tipo_creditos, bb.id_participes, aa.id_creditos, aa.cuota, aa.total, 
                    bb.plazo_creditos, bb.saldo_actual_creditos, aa.mora, aa.mes_descuento, aa.valor_usuario_valores_creditos";
	        $tab1  = "core_descuentos_registrados_detalle_valores_creditos aa
	           INNER JOIN core_creditos bb ON bb.id_creditos = aa.id_creditos
	           INNER JOIN core_participes cc ON cc.cedula_participes = aa.cedula_participes";
	        $whe1  = "anio = $anio_recaudacion
	        and mes	= $mes_recaudacion
	        and bb.id_estatus = 1
	        and cc.id_estatus = 1
	        and aa.id_entidad_patronal = $id_entidad_patronal";
	        $id1   = " aa.id_entidad_patronal";
	       	        
	        $rsConsulta1   = $recaudaciones->getCondiciones($col1, $tab1, $whe1, $id1);
	        
	        if( empty( $rsConsulta1 ) ){
	            return array('error'=>true,'mensaje'=>"Detalle valores creditos No encontrados");
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
	        
	        foreach ( $rsConsulta1 as $res )
	        {   
	            $valor_descuento   = 0;
	            
	            $id_participes = ( !empty( $res->id_participes ) ) ? $res->id_participes : 'null';
	            $id_creditos   = ( !empty( $res->id_creditos ) ) ? $res->id_creditos : 'null';
	            $tipo_descuento = ( !empty( $res->id_tipo_creditos ) ) ? $res->id_tipo_creditos : 0;	            
	            $cuota_descuentos  = ( !empty( $res->cuota ) ) ? $res->cuota : 0;
	            $monto_descuentos  = ( !empty( $res->total ) ) ? $res->total : 0;
	            $plazo_desccuentos = ( !empty( $res->plazo_creditos ) ) ? $res->plazo_creditos : 0;
	            $mora_descuentos   = ( !empty( $res->mora ) ) ? $res->mora : 0;
	            $saldo_descuentos  = ( !empty( $res->saldo_actual_creditos ) ) ? $res->saldo_actual_creditos : 0;
	            $mes_desc_descuentos   = ( !empty( $res->mes_descuento ) ) ? $res->mes_descuento : 0;
	            
	            if( empty( $res->valor_usuario_valores_creditos ) )
	            {
	                $valor_descuento   = $cuota_descuentos;
	            }else
	            {
	                $valor_descuento   = $res->valor_usuario_valores_creditos;
	            }
	                
	            
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
	            $detalle['id_descuentos_formatos']              = $id_formato_descuentos;
	            $detalle['procesado_descuentos']                = $procesados_descuentos;
	            $detalle['saldo_descuentos']                    = $saldo_descuentos;
	            $detalle['mora_descuentos']                     = $mora_descuentos;
	            $detalle['credito_pay_descuentos']              = $credito_pay_descuentos;
	            $detalle['mes_desc_descuentos']                 = $mes_desc_descuentos;
	            $detalle['valor_usuario']                       = $valor_descuento;
	            
	            $parametrosDetalle  = "'".join("','", $detalle)."'";
	            $parametrosDetalle  = str_replace("'null'","null",$parametrosDetalle);
	            $sqDetalle  = $recaudaciones->getconsultaPG($funcionDetalle, $parametrosDetalle);
	            
	            $recaudaciones->llamarconsultaPG($sqDetalle);
	            
	            if( !empty( pg_last_error() ) ){
	                break;
	            }
	            
	        }
	       
	        $resp = array();
	        $resp['estatus']   = "OK";
	        $resp['mensaje']   = "Datos Ingresados";
	        
	        $buffer    = ob_get_clean();
	        if( !empty( $buffer ) )
	        {
	            throw new Exception("Salida se encuentra lleno");
	        }
	        //termina transaccionabilidad
	        $recaudaciones->endTran("COMMIT");
	        echo json_encode($resp);
	        
	    } catch (Exception $e)
	    {
	        ob_get_clean();
	        $buffer = error_get_last();
	        echo '<message> Error  '.$e->getMessage().' <message> -- \n '.$buffer['message'].'';
	    }
	}
	/** end 2020/06/25 **/

	/** dc 2020/06/29 **/
	public function DTArchivoDescuentos(){
	    
	    if( !isset( $_SESSION ) ){
	        session_start();
	    }
	    
	    try {
	        ob_start();
	        
	        $recaudaciones = new RecaudacionesModel();
	        
	        //variables para enviar a  la vista
	        $valor_parcial = 0.00;
	        $valor_total   = 0.00;
	        
	        //dato que viene de parte del plugin DataTable
	        $requestData = $_REQUEST;
	        $searchDataTable   = $requestData['search']['value'];
	        
	        /** buscar por el usuario que se encuentra logueado */
	        //$_usuario_logueado = $_SESSION['usuario_usuarios'];
	        
	        $id_entidad_patronal = $_POST['id_entidad_patronal'];
	        $mes_recaudacion     = $_POST['mes_recaudacion'];
	        $anio_recaudacion    = $_POST['anio_recaudacion'];
	        
	        $columnas1 = " aa.id_descuentos_registrados_detalle_valores_creditos, bb.id_entidad_patronal, bb.nombre_entidad_patronal, aa.cedula_participes, aa.nombres_participes,
	           aa.mes_descuento, aa.sueldo_liquido, aa.cuota, aa.mora, aa.total, aa.valor_usuario_valores_creditos as total_usuario, aa.nombre_tipo_creditos, aa.tipo_afiliado";
	        $tablas1   = " core_descuentos_registrados_detalle_valores_creditos aa
	           INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal ";
	        $where1    = " aa.id_entidad_patronal = $id_entidad_patronal
               AND aa.mes = $mes_recaudacion AND aa.anio = $anio_recaudacion";
	        
	        //aqui la busqueda total sin filtros
	        $colSum    = "COALESCE( SUM( case when valor_usuario_valores_creditos  is null then total else valor_usuario_valores_creditos  end ), 0 ) total_descuento";
	        $rsTotal = $recaudaciones->getCondicionesSinOrden( $colSum, $tablas1, $where1, "");
	        $valor_total   = $rsTotal[0]->total_descuento;
	        
	        /* PARA FILTROS DE CONSULTA */
	        
	        if( strlen( $searchDataTable ) > 0 )
	        {
	            $where1 .= " AND ( ";
	            $where1 .= " aa.cedula_participes ILIKE '%$searchDataTable%' ";
	            $where1 .= " OR aa.nombres_participes ILIKE '%$searchDataTable%'";
	            $where1 .= " ) ";
	            
	        }
	        
	        $rsCantidad    = $recaudaciones->getCantidad("*", $tablas1, $where1);
	        $cantidadBusqueda = (int)$rsCantidad[0]->total;
	        
	        /**PARA ORDENAMIENTO Y  LIMITACIONES DE DATATABLE **/
	        
	        // datatable column index  => database column name estas columas deben en el mismo orden que defines la cabecera de la tabla
	        $columns = array(
	            0 => '1',
	            1 => '1',
	            2 => '1',
	            3 => '1',
	            4 => '1',
	            5 => '1',
	            6 => '1',
	            7 => '1'
	        );
	        
	        $orderby   = $columns[$requestData['order'][0]['column']];
	        $orderdir  = $requestData['order'][0]['dir'];
	        $orderdir  = strtoupper($orderdir);
	        /**PAGINACION QUE VIEN DESDE DATATABLE**/
	        $per_page  = $requestData['length'];
	        $offset    = $requestData['start'];
	        
	        //para validar que consulte todos
	        $per_page  = ( $per_page == "-1" ) ? "ALL" : $per_page;
	        
	        $limit = " ORDER BY $orderby $orderdir LIMIT   $per_page OFFSET '$offset'";
	        
	        $sql = " SELECT $columnas1 FROM $tablas1 WHERE $where1  $limit ";
	        //$sql = "";
	        
	        $resultSet=$recaudaciones->getCondicionesSinOrden($columnas1, $tablas1, $where1, $limit);
	        
	        //aqui la busqueda total parcial
	        $colSum    = "COALESCE( SUM( case when valor_usuario_valores_creditos  is null then total else valor_usuario_valores_creditos  end ), 0 ) total_descuento";
	        $rsParcial = $recaudaciones->getCondicionesSinOrden( $colSum, $tablas1, $where1, $limit);
	        $valor_parcial   = $rsParcial[0]->total_descuento;
	        
	        //$cantidadBusquedaFiltrada = sizeof($resultSet);
	        
	        /** crear el array data que contiene columnas en plugins **/
	        $data = array();
	        $dataFila = array();
	        $columnIndex = 0;
	        foreach ( $resultSet as $res){
	            $columnIndex++;
	            
	            $opciones = ""; //variable donde guardare los datos creados automaticamente
	            
	            $opciones = '<div class="pull-right ">
                            <span >
                                <a onclick="mostrar_valores_descuentos_creditos(this)" id="" data-id_descuento_valor_credito="'.$res->id_descuentos_registrados_detalle_valores_creditos.'" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Editar Valores"> <i class="fa  fa-edit fa-2x fa-fw" aria-hidden="true" ></i>
	                           </a>
                            </span>
                            </div>';
	            
	            $total_usuario = $res->total_usuario;
	            if( empty($total_usuario) )
	            {
	                $total_usuario = $res->total;
	            }
	            
	            $dataFila['numfila'] = $columnIndex;
	            $dataFila['nombre_entidad']  = $res->nombre_entidad_patronal;
	            $dataFila['tipo_afiliado'] = $res->tipo_afiliado;
	            $dataFila['cedula']   = $res->cedula_participes;
	            $dataFila['nombre']   = $res->nombres_participes;
	            $dataFila['nombre_credito']    = $res->nombre_tipo_creditos;
	            $dataFila['fecha']    = $res->mes_descuento;
	            $dataFila['sueldo']   = $res->sueldo_liquido;
	            $dataFila['cuota']    = $res->cuota;
	            $dataFila['mora']   = $res->mora;
	            $dataFila['total']    = $res->total;
	            $dataFila['total_usuario']    = $total_usuario;
	            $dataFila['opciones'] = $opciones;
	            //$dataFila['id_cabeza']         = '12345';
	            
	            $data[] = $dataFila;
	        }
	        
	        //para valores de pie datetable
	        $totales = array();
	        $totales['parcial']    = $valor_parcial;
	        $totales['total']      = $valor_total;
	        
	        $salida = ob_get_clean();
	        
	        if( !empty($salida) )
	            throw new Exception($salida);
	            
	            $json_data = array(
	                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	                "recordsTotal" => intval($cantidadBusqueda),  // total number of records
	                "recordsFiltered" => intval($cantidadBusqueda), // total number of records after searching, if there is no searching then totalFiltered = totalData
	                "data" => $data,   // total data array
	                "sql" => "",//$sql
	                "totales" => $totales //valores totales
	            );
	            
	    } catch (Exception $e) {
	        
	        $json_data = array(
	            "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
	            "recordsTotal" => intval("0"),  // total number of records
	            "recordsFiltered" => intval("0"), // total number of records after searching, if there is no searching then totalFiltered = totalData
	            "data" => array(),   // total data array
	            "sql" => $sql,
	            "buffer" => error_get_last(),
	            "ERRORDATATABLE" => $e->getMessage()
	        );
	    }
	    
	    
	    echo json_encode($json_data);
	    
	}
	
	/** end dc 2020/06/29 **/
	
	public function ver_code()
	{
	    $fecha = new DateTime('2020-6-1');
	    //$fecha->modify('last day of this month');
	    echo $fecha->format('d/m/Y');
	}
	
}
?>

