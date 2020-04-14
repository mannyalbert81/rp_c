<?php

class PrincipalPrestamosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();

	    $busquedas = new PrincipalPrestamosModel();
	    		
		if( empty( $_SESSION['usuario_usuarios'] ) ){
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		$nombre_controladores = "admPrestamos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso"
		        
		    ));
		    exit();
		}
	
						
		$this->view_principal("PrincipalBusquedas");		
	
	}
	public function CargaTipoPrestamos(){
	    
	    $busquedas = new TipoCreditoModel();
	    $resp  = null;
	    
	    $col1  = " id_tipo_creditos, nombre_tipo_creditos";
	    $tab1  = " public.core_tipo_creditos ";
	    $whe1  = " 1 = 1 ";
	    $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
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
	
	public function CargaEstadoPrestamos(){
	    
	    $busquedas = new EstadoCreditoModel();
	    $resp  = null;
	    
	    $col1  = " id_estado_creditos, nombre_estado_creditos";
	    $tab1  = " public.core_estado_creditos ";
	    $whe1  = " 1 = 1 ";
	    $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    
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
	
	public function CargaEntidadPatronal(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    $col1  = " id_entidad_patronal, nombre_entidad_patronal";
	    $tab1  = " public.core_entidad_patronal ";
	    $whe1  = " 1 = 1 ";
	    $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	    	    
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
	
	public function CargaPrestamos(){
	    
	    $busquedas = new PrincipalPrestamosModel();
	    $resp  = null;
	    
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    
	    //varaibles de parametros de busqueda
	    $cedula    = ( isset( $_POST['cedula'] ) ) ? $_POST['cedula'] : "";
	    $nombre           = ( isset( $_POST['nombre'] ) ) ? $_POST['nombre'] : "";
	    $apellido         = ( isset( $_POST['apellido'] ) ) ? $_POST['apellido'] : "";
	    $idTipoCreditos= ( isset( $_POST['id_tipo_creditos'] ) ) ? $_POST['id_tipo_creditos'] : "0";
	    $fsolicitud       = ( isset( $_POST['fsolicitud'] ) ) ? $_POST['fsolicitud'] : "";
	    $idEstadoCreditos= ( isset( $_POST['id_estado_creditos'] ) ) ? $_POST['id_estado_creditos'] : "0";
	    $idEntidadPatronal = ( isset( $_POST['id_entidad_patronal'] ) ) ? $_POST['id_entidad_patronal'] : "0";
	    
        	    $columnas1 = "core_creditos.id_creditos, 
          core_creditos.numero_creditos, 
          core_participes.id_participes, 
          core_participes.nombre_participes, 
          core_participes.apellido_participes, 
          core_participes.cedula_participes, 
          core_participes.fecha_nacimiento_participes, 
          core_participes.direccion_participes, 
          core_participes.telefono_participes, 
          core_participes.celular_participes, 
          core_entidad_patronal.id_entidad_patronal, 
          core_entidad_patronal.nombre_entidad_patronal, 
          core_creditos.monto_otorgado_creditos, 
          core_creditos.saldo_actual_creditos, 
          core_creditos.fecha_concesion_creditos, 
          core_tipo_creditos.id_tipo_creditos, 
          core_tipo_creditos.nombre_tipo_creditos, 
          core_estado_creditos.id_estado_creditos, 
          core_estado_creditos.nombre_estado_creditos, 
          core_creditos.plazo_creditos, 
          core_creditos.monto_neto_entregado_creditos";
        	    $tablas1   = " public.core_creditos, 
          public.core_participes, 
          public.core_tipo_creditos, 
          public.core_estado_creditos, 
          public.core_entidad_patronal";
        	    $where1    = "core_participes.id_participes = core_creditos.id_participes AND
          core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
          core_estado_creditos.id_estado_creditos = core_creditos.id_estado_creditos AND
          core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal";
	    $id1       = "core_creditos.fecha_concesion_creditos";
	    
	  
	    if( !empty($idTipoCreditos) ){
	        
	        $where1    .= " AND core_tipo_creditos.id_tipo_creditos = $idTipoCreditos";
	    }
	    
	    if( !empty($idEstadoCreditos) ){
	        
	        $where1    .= " AND core_estado_creditos.id_estado_creditos = $idEstadoCreditos ";
	    }
	    
	  
	    if( strlen( trim( $cedula ) ) > 0 ){
	        
	        $where1   .= " AND core_participes.cedula_participes LIKE '$cedula%' ";
	    }
	    
	    if( strlen( trim( $nombre ) ) > 0 ){
	        
	        $where1    .= " AND core_participes.nombre_participes ILIKE '%$nombre%' ";
	    }
	    
	    if( strlen( trim( $apellido ) ) > 0 ){
	        
	        $where1    .= " AND core_participes.apellido_participes ILIKE '%$apellido%' ";
	    }
	    
	    /** para fechas **/
	    $ArrayFsolicitud = $this->devuelveFecha( $fsolicitud);
	    if( !empty( $ArrayFsolicitud ) ){
	        $where1    .= " AND core_creditos.fecha_concesion_creditos BETWEEN '".$ArrayFsolicitud['fechaini']."' AND '".$ArrayFsolicitud['fechafin']."' ";
	    }
	   
	    /** termina para fechas **/
	    
	    $colCantidad = " COUNT(1) AS cantidad " ;
	    $resultSet = $busquedas->getCondicionesSinOrden( $colCantidad , $tablas1, $where1,"");
	    $cantidadResult=(int)$resultSet[0]->cantidad;
	    
	    $per_page = 5; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $busquedas->getCondicionesPag($columnas1, $tablas1, $where1, $id1, $limit);
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    $error = error_get_last();
	    if( !empty($error) ){
	        echo $error['message'];
	        exit();
	    }
	    
	    $htmlTr = "";
	    $i = 0;
	    //$estiloConfigProveedores = ""; // variable donde se guarda estilo css para indicar al usuario que debe configurar datos de proveedor
	    
	    $htmlHead = "";
	    $htmlHead .= "<thead>";
	    $htmlHead .= "<tr>";
	    $htmlHead .= "<th>#</td>";
	    $htmlHead .= "<th><label>Opciones:</label></td>";
	    $htmlHead .= "<th><label>Datos:</label></td>";
	    $htmlHead .= "</tr>";
	    $htmlHead .= "</thead>";
	    
	    //para los datos de la tabla
	    $htmlBody = "<tbody>";
	    foreach ($resultSet as $res){
	        
	        //variables a utilizar
	        $i++;

	        //proceso de generacion de opciones
	        $opcionesTd  = "";
	        
	        /*
	         $opcionesTd .= " <ul class=\"list-inline\">";
	         $opcionesTd .= " <li><a href=\"#\" class=\"link-black text-sm\"><i class=\"fa fa-share margin-r-5\"></i> Share</a></li>";
	         $opcionesTd .= " <li><a href=\"#\" class=\"link-black text-sm\"><i class=\"fa fa-thumbs-o-up margin-r-5\"></i> Like</a>";
	         $opcionesTd .= " </li>";
	         $opcionesTd .= " <li class=\"pull-right\">";
	         $opcionesTd .= " <a href=\"#\" class=\"link-black text-sm\"><i class=\"fa fa-comments-o margin-r-5\"></i> Comments";
	         $opcionesTd .= " (5)</a></li>";
	         $opcionesTd .= " </ul>";
	         */
	        
	        $opcionesTd .= "<div class=\"btn-group\">";
	        $opcionesTd .= "<button type=\"button\" value=\"".$res->id_participes."\" onclick=\"fnRegistroAportesManuel()\" class=\"btn btn-default\"><i class=\"fa fa-edit\"></i></button>";
	        $opcionesTd .= "</div>";
	        
	        
	        /*
	         *  <a class="btn btn-app">
	         <i class="fa fa-edit"></i> Edit
	         </a>
	         */
	        
	        
	        $htmlBody    .= "<tr>";
	        $htmlBody    .= "<td>".$i."</td>";
	        $htmlBody    .= "<td class=\"col-md-2 col-lg-2\" >".$opcionesTd."</td>";
	        $htmlBody    .= "<td>";
	        $htmlBody    .= "<div class=\"box box-widget widget-user-2\">";
	        //<!-- Add the bg color to the header using any of the bg-* classes -->
	        $htmlBody    .= "<div class=\"widget-user-header bg-yellow\">";
	        $htmlBody    .= "<div class=\"widget-user-image\">";
	        $htmlBody    .= "<img class=\"img-circle\" src=\"view/images/user.png\" alt=\"User Avatar\">";
	        $htmlBody    .= "</div>";
	        //<!-- /.widget-user-image -->
	        $htmlBody    .= "<h3 class=\"widget-user-username\">".$res->apellido_participes." ".$res->nombre_participes."</h3>";
	        $htmlBody    .= "<h5 class=\"widget-user-desc\">".$res->cedula_participes."</h5>";
	        $htmlBody    .= "<h5 class=\"widget-user-desc\">".$res->cedula_participes."</h5>";
	        $htmlBody    .= "</div>";
	        $htmlBody    .= "<div class=\"box-footer no-padding\">";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Fecha Nacimiento:</span>".$res->fecha_nacimiento_participes."</p></div>";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Fecha Ingreso:</span>".$fIngreso."</p></div>";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Fecha Salida:</span>".$fSalida."</p></div>";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Fecha Liquidacion:</span>".$fLiquidacion."</p></div>";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Estado:</span>".strtoupper( $res->nombre_estado_participes )."</p></div>";
	        
	        if( !empty($auxCredito) && $auxCredito['iscredito'] ){
	            $htmlBody    .= "<div class=\"bio-row\"><p><span>Creditos:</span>"."SI"."</p></div>";
	            $htmlBody    .= "<div class=\"bio-row\"><p><span>Monto Creditos:</span>".$auxCredito['tcredito']."</p></div>";
	            $htmlBody    .= "<div class=\"bio-row\"><p><span>Saldo Creditos:</span>".$auxCredito['tsaldo']."</p></div>";
	        }
	        $moraCreditos = ( array_key_exists('nummora', $auxCredito) ? $auxCredito['nummora'] : 0 );
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Moras Creditos:</span>".$moraCreditos."</p></div>";
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Contratos de Adhesion:</span>"." "."</p></div>";
	        
	        $observacion   = ( is_array($auxGarantias) && array_key_exists('isgarantias', $auxGarantias) && $auxGarantias['isgarantias']  ) ? "hay Garantias" : $res->observacion_participes;
	        $htmlBody    .= "<div class=\"bio-row\"><p><span>Observaciones:</span>".$observacion."</p></div>";
	        $htmlBody    .= "</div>";
	        $htmlBody    .= "</td>";
	        $htmlBody    .= "</tr>";
	        
	        
	    }
	    
	    $htmlBody .= "</tbody>";
	    
	    $htmlFoot = "<tfoot>";
	    $htmlFoot .= "<tr>";
	    $htmlFoot .= "<th colspan=\"3\"></th>";
	    $htmlFoot .= "</tr>";
	    $htmlFoot .= "</tfoot>";
	    
	    $resp['tabla'] = $htmlHead.$htmlBody.$htmlFoot;
	    
	    $resp['filas'] = $htmlTr;
	    
	    $htmlPaginacion  = '<div class="table-pagination pull-right">';
	    $htmlPaginacion .= ''. $busquedas->allpaginate("index.php", $page, $total_pages, $adjacents,"loadBusquedaPrestamos").'';
	    $htmlPaginacion .= '</div>';
	    
	    $resp['paginacion'] = $htmlPaginacion;
	    $resp['cantidadDatos'] = $cantidadResult;
	    
	    echo json_encode( $resp );
	}
	
	
	/************************************************************** FUNCIONES AUXILIARES DEL CONTROLADOR *************************************/
	/***
	 *
	 * @param string $fecha
	 * @return array con fechaini fecha inicio -- fechafin fecha fin de busqueda
	 * @exception si hay error se enviar null
	 */
	function devuelveFecha($fecha){
	    
	    $resp = null;
	    $afecha  = explode("-", $fecha);
	    if( sizeof( $afecha ) != 2 ){
	        return null;
	    }
	    
	    $afechaini = explode("/", trim( $afecha[0] ) );
	    $afechafin = explode("/", trim( $afecha[1] ) );
	    
	    if( sizeof( $afechaini ) != 3 || sizeof( $afechafin ) != 3 ){
	        return null;
	    }
	    
	    try {
	        
	        $dateini = new DateTime( trim( $afecha[0] ) );
	        $datefin = new DateTime( trim( $afecha[1] ) );
	        
	        if( !empty( error_get_last() ) ){
	            echo 'llego aqui';
	            throw new Exception();
	        }
	        
	        $resp['fechaini'] = $dateini->format('Y-m-d');
	        $resp['fechafin'] = $datefin->format('Y-m-d');
	        
	    } catch (Exception $e) {
	        error_clear_last();
	        $resp = null;
	    }
	    
	    return $resp;
	    
	}
	
	/***
	 * @desc funcion para dar un formato a la fecha que viene de Bd
	 * @param $fechaString
	 * @param $formato
	 * @exception return vacio
	 */
	function pgFormatFecha($StringFecha,$FormatFecha = "Y-m-d"){
	    $strFecha = "";
	    try {
	        $ObjetoFecha = new DateTime($StringFecha);
	        $strFecha = $ObjetoFecha->format($FormatFecha);
	    } catch (Exception $e) {
	        error_clear_last(); //limpia el error generado
	        $strFecha;
	    }
	    return $strFecha;
	}
}
?>
