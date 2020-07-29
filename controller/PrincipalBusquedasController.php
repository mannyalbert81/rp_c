<?php

class PrincipalBusquedasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
		echo "llego"; 
		die("llego");	    
	    $busquedas = new PrincipalBusquedasModel();
	    		
		if( empty( $_SESSION['usuario_usuarios'] ) ){
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}

		$nombre_controladores = "admBusqueda";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
		        
		    ));
		    exit();
		}
		
		//buscar el estado civil
		$colCivil    = " id_estado_civil_participes,nombre_estado_civil_participes ";
		$tabCivil    = " public.core_estado_civil_participes ";
		$wheCivil    = " 1 = 1";
		$rsCivil     = $busquedas->getCondicionesSinOrden($colCivil, $tabCivil, $wheCivil, "");
		
		//buscar el Genero
		$colGen    = " id_genero_participes, nombre_genero_participes ";
		$tabGen    = " public.core_genero_participes ";
		$wheGen    = " 1 = 1";
		$rsGen     = $busquedas->getCondicionesSinOrden($colGen, $tabGen, $wheGen, "");
		
		$datos = null;
		$datos['rsCivil'] = $rsCivil;
		$datos['rsGen']   = $rsGen;
						
		$this->view_principal("PrincipalBusquedas",$datos);		
	
	}
	
	public function CargaEstadoParticipes(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    $col1  = " id_estado_participes, nombre_estado_participes";
	    $tab1  = " public.core_estado_participes ";
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
	
	public function CargaBusqueda(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;	    	    
	    
	    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	    
	    //varaibles de parametros de busqueda
	    $identificacion    = ( isset( $_POST['identificacion'] ) ) ? $_POST['identificacion'] : "";
	    $nombres           = ( isset( $_POST['nombres'] ) ) ? $_POST['nombres'] : "";
	    $apellidos         = ( isset( $_POST['apellidos'] ) ) ? $_POST['apellidos'] : "";
	    $cargo             = ( isset( $_POST['cargo'] ) ) ? $_POST['cargo'] : "";
	    $fNacimiento       = ( isset( $_POST['fnacimiento'] ) ) ? $_POST['fnacimiento'] : "";
	    $estadoCivil       = ( isset( $_POST['estado_civil'] ) ) ? $_POST['estado_civil'] : "0";
	    $genero            = ( isset( $_POST['genero'] ) ) ? $_POST['genero'] : "0";
	    $direccion         = ( isset( $_POST['direccion'] ) ) ? $_POST['direccion'] : "";
	    $telefono          = ( isset( $_POST['telefono'] ) ) ? $_POST['telefono'] : "";
	    $fIngreso          = ( isset( $_POST['fingreso'] ) ) ? $_POST['fingreso'] : "";
	    $fBaja             = ( isset( $_POST['fbaja'] ) ) ? $_POST['fbaja'] : "";
	    $idEstadoParticipes= ( isset( $_POST['id_estado_participes'] ) ) ? $_POST['id_estado_participes'] : "0";
	    $idEntidadPatronal = ( isset( $_POST['id_entidad_patronal'] ) ) ? $_POST['id_entidad_patronal'] : "0";
	    
	    $columnas1 = " aa.id_participes, aa.cedula_participes, aa.apellido_participes, aa.nombre_participes, aa.ocupacion_participes, aa.fecha_nacimiento_participes, 
            aa.fecha_ingreso_participes, aa.fecha_salida_participes,bb.nombre_estado_participes, aa.observacion_participes";
	    $tablas1   = " public.core_participes aa
            INNER JOIN public.core_estado_participes bb ON bb.id_estado_participes = aa.id_estado_participes";
	    $where1    = " aa.id_estatus = 1 ";	
	    $id1       = " aa.apellido_participes";
	   
	    if( !empty($estadoCivil) ){
            
	        $where1    .= " AND aa.id_estado_civil_participes = $estadoCivil ";	       
	    }
	    
	    if( !empty($genero) ){
	        
	        $where1    .= " AND aa.id_genero_participes = $genero ";	        
	    }
	    if( !empty($idEstadoParticipes) ){
	        
	        $where1    .= " AND aa.id_estado_participes = $idEstadoParticipes ";
	    }
	    
	    if( !empty($idEntidadPatronal) ){
	        
	        $where1    .= " AND aa.id_entidad_patronal = $idEntidadPatronal ";
	    }
	    
	    if( strlen( trim( $identificacion ) ) > 0 ){
	        
	        $where1   .= " AND aa.cedula_participes LIKE '$identificacion%' "; 
	    }
	    
	    if( strlen( trim( $nombres ) ) > 0 ){
	        
	        $where1    .= " AND aa.nombre_participes ILIKE '%$nombres%' ";
	    }
	    
	    if( strlen( trim( $apellidos ) ) > 0 ){
	        
	        $where1    .= " AND aa.apellido_participes ILIKE '%$apellidos%' ";
	    }
	    
	    if( strlen( trim( $cargo ) ) > 0 ){
	        
	        $where1    .= " AND aa.ocupacion_participes ILIKE '%$cargo%' ";
	    }
	    
	    if( strlen( trim( $direccion ) ) > 0 ){
	        
	        $where1    .= " AND aa.direccion_participes ILIKE '%$direccion%' ";
	    }
	    
	    if( strlen( trim( $telefono ) ) > 0 ){
	        
	        $where1    .= " AND aa.telefono_participes ILIKE '%$telefono%' ";
	    }
	    
	    /** para fechas **/
	    $ArrayFNacimiento = $this->devuelveFecha( $fNacimiento );
	    if( !empty( $ArrayFNacimiento ) ){
	        $where1    .= " AND aa.fecha_nacimiento_participes BETWEEN '".$ArrayFNacimiento['fechaini']."' AND '".$ArrayFNacimiento['fechafin']."' ";
	    }
	    
	    $ArrayFIngreso = $this->devuelveFecha( $fIngreso );
	    if( !empty( $ArrayFIngreso ) ){
	        $where1    .= " AND aa.fecha_nacimiento_participes BETWEEN '".$ArrayFIngreso['fechaini']."' AND '".$ArrayFIngreso['fechafin']."' ";
	    }
	    
	    $ArrayFBaja = $this->devuelveFecha( $fBaja );
	    if( !empty( $ArrayFBaja ) ){
	        $where1    .= " AND aa.fecha_nacimiento_participes BETWEEN '".$ArrayFBaja['fechaini']."' AND '".$ArrayFBaja['fechafin']."' ";
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
	    $htmlHead .= "<th>#</th>";
	    $htmlHead .= "<th><label>Opciones:</label></th>";
	    $htmlHead .= "<th><label>Datos:</label></th>";
	    $htmlHead .= "</tr>";
	    $htmlHead .= "</thead>";
	    
	    //para los datos de la tabla
	    $htmlBody = "<tbody>";
	    foreach ($resultSet as $res){
	        
	        //variables a utilizar
	        $i++;
	        
	        $fIngreso      =  $this->pgFormatFecha($res->fecha_ingreso_participes);
	        $fSalida       =  $this->pgFormatFecha($res->fecha_salida_participes);
	        $fLiquidacion  =  $this->pgFormatFecha("");
	        
	        //proceso de busqueda de creditos 
	        $auxCredito    = $this->getDataCredit($res->id_participes);
	        
	        //proceso de busqueda de garantias
	        $auxGarantias  = $this->getDataGarantias($res->id_participes);
	        
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
            $opcionesTd .= "<button type=\"button\" value=\"".$res->id_participes."\" onclick=\"fnRegistroAportesManuel(this)\" class=\"btn btn-default\"><i class=\"fa fa-edit\"></i></button>";
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
	        $htmlBody    .= "<h5 class=\"widget-user-desc\">".$res->ocupacion_participes."</h5>";
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
	    $htmlPaginacion .= ''. $busquedas->allpaginate("index.php", $page, $total_pages, $adjacents,"loadBusquedaPrincipal").'';
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
	
	/***
	 * 
	 * @param integer $id_participes
	 * @return NULL|array 
	 * @desc funcion que permite obtener valores de credito del participe
	 */
	function getDataCredit($id_participes){
	    $resp = null;
	    $participes    = new ParticipesModel();
	    
	    try {
	        
	        $col1  = " id_creditos, monto_otorgado_creditos, saldo_actual_creditos";
	        $tab1  = " public.core_creditos aa 
                INNER JOIN public.core_estado_creditos bb ON bb.id_estado_creditos = aa.id_estado_creditos ";
	        $whe1  = " UPPER( bb.nombre_estado_creditos ) = 'ACTIVO' 
                AND aa.id_participes = $id_participes";
	        
	        $rsConsulta1   = $participes->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $arrayCreditos = null;
	        
	        if( !empty($rsConsulta1) ){
	            
	            $resp['iscredito'] = true;
	            
	            $totalCredito  = 0.00;
	            $totalSaldo    = 0.00;
	            $id_creditos   = 0;
	            
	            
	            foreach ($rsConsulta1 as $res ) {
	                
	                if( (int)$res->id_creditos !== $id_creditos ){
	                    $id_creditos   = $res->id_creditos;
	                    $arrayCreditos[]   = $id_creditos;
	                }
	                    
	                $totalCredito  += (double)$res->monto_otorgado_creditos;
	                $totalSaldo    += (double)$res->saldo_actual_creditos;
	                
	                
	            }
	            
	            $resp['tcredito'] = number_format($totalCredito,2,".",""); 
	            $resp['tsaldo']   = number_format($totalSaldo,2,".",""); 
	            
	        }else{
	            $resp['iscredito'] = false;
	        }
	        
	        
	        if( $resp['iscredito'] ){
	            
	            $strListCreditos   = implode(",", $arrayCreditos);
	            
	            $anioBuscar= date('Y');
	            $mesBuscar = date('m');
	            $col2  = " COUNT(1) \"cantidad\" , SUM(mora_tabla_amortizacion) \"sumamora\" ";
	            $tab2  = " public.core_tabla_amortizacion ";
	            $whe2  = " id_estatus = 1
    	        AND id_estado_tabla_amortizacion <> 2
    	        AND EXTRACT(YEAR FROM fecha_tabla_amortizacion ) <= '$anioBuscar'
    	        AND EXTRACT(MONTH FROM fecha_tabla_amortizacion ) < '$mesBuscar'
    	        AND id_creditos in ($strListCreditos) ";
	            $gru2  = " fecha_tabla_amortizacion ";
	            $id2   = " fecha_tabla_amortizacion ";
	            
	            $rsConsulta2   = $participes->getCondiciones_grupo($col2, $tab2, $whe2, $gru2, $id2);
	            
	            if( !empty($rsConsulta2) ){
	                
	                $resp['nummora']   = $rsConsulta2[0]->cantidad;
	            }
	        }
	        	        	        
	    } catch (Exception $e) {
	        error_clear_last();
	        return null;
	    }
	    
	    return $resp;
	}
	
	function getDataGarantias($id_participes){
	    
	    $resp = null;
	    $participes    = new ParticipesModel();
	    
	    try {
	        
	        $col1  = " aa.id_participes";
	        $tab1  = " public.core_creditos_garantias aa
	        INNER JOIN public.estado bb on bb.id_estado = aa.id_estado ";
	        $whe1  = " UPPER( bb.nombre_estado ) = 'ACTIVO'
                AND aa.id_participes = $id_participes";
	        
	        $rsConsulta1   = $participes->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        //$arrayGarantias = null;
	        
	        if( !empty($rsConsulta1) ){
	            
	            $resp['isgarantias'] = false;
	            
	            //completar la operacion de busqueda
	        }
	        
	    } catch (Exception $e) {
	        error_clear_last();
	        return null;
	    }
	    
	    return $resp;
	}
	/************************************************************** TERMINA FUNCIONES AUXILIARES DEL CONTROLADOR *****************************/
	
}
?>