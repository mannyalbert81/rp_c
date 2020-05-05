<?php

class PrincipalPrestamosSociosController extends ControladorBase{

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
	    
	    $nombre_controladores = "PrincipalPrestamosSocios";
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
	    
	    $this->view_principal("PrincipalPrestamosSocios",$datos);
	    
	
	}
	
	public function CargaDatosParticipePrestamos(){
	    
	    $busquedas = new PrincipalPrestamosModel();
	    
	    $resp  = null;
	    
	    try {
	        
	        $id_creditos = $_POST['id_creditos'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = "core_creditos.id_creditos, 
                      core_creditos.numero_creditos,
                      core_creditos.fecha_concesion_creditos,
                      core_creditos.monto_otorgado_creditos,
                      core_creditos.plazo_creditos,
                      core_creditos.monto_neto_entregado_creditos,
                      core_creditos.cuota_creditos,
                      core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_entidad_patronal.ruc_entidad_patronal, 
                      core_entidad_patronal.tipo_entidad_patronal,
                      core_estado_creditos.id_estado_creditos, 
                      core_estado_creditos.nombre_estado_creditos,
                      core_tipo_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos,
                      core_tipo_creditos.interes_tipo_creditos,
                      core_tipo_creditos.plazo_maximo_tipo_creditos";
	        $tab1  = " public.core_creditos, 
                        public.core_participes, 
                        public.core_entidad_patronal,
                        public.core_estado_creditos,
                        public.core_tipo_creditos";
	        $whe1  = " core_participes.id_participes = core_creditos.id_participes AND
                        core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND 
                        core_estado_creditos.id_estado_creditos = core_creditos.id_estado_creditos AND 
                        core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                        core_creditos.id_creditos = $id_creditos ";
	        $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $resp['dataParticipePrestamos'] = ( empty($rsConsulta1) ) ? null : $rsConsulta1;
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
	        }        
	        	        
	    } catch (Exception $e) {
	        $buffer =  error_get_last();
	        $resp['icon'] = isset($resp['icon']) ? $resp['icon'] : "error";
	        $resp['mensaje'] = $e->getMessage();
	        $resp['msgServer'] = $buffer; 
	        $resp['estatus'] = "ERROR";
	    }
	    
	    error_clear_last();
	    if (ob_get_contents()) ob_end_clean();
	    
	    echo json_encode($resp);
	}
	

	
}
?>