<?php

class PrincipalPrestamosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $prestamos = new PrincipalPrestamosModel();
	    		
		if( empty( $_SESSION['usuario_usuarios'] ) ){
		    $this->redirect("Usuarios","sesion_caducada");
		    exit();
		}
		
		$nombre_controladores = "admPrestamos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $prestamos->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso"
		        
		    ));
		    exit();
		}
		
	
						
		$this->view_principal("PrincipalPrestamos");		
	
	}
	
	public function CargaTipoPrestamos(){
	    
	    $busquedas = new TipoCreditoModel();
	    $resp  = null;
	    
	    $col1  = " id_tipo_creditos, nombre_tipo_creditos";
	    $tab1  = " public.core_tipo_creditos";
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
	    
	    $busquedas = new TipoCreditoModel();
	    $resp  = null;
	    
	    $col1  = " id_estado_creditos, nombre_estado_creditos";
	    $tab1  = " public.core_estado_creditos";
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
	    
	    $busquedas = new EntidadPatronalParticipesModel();
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
}
	
