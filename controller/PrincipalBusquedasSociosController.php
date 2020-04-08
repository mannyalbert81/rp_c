<?php

class PrincipalBusquedasSociosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
	    session_start();
	    
	    $busquedas = new PrincipalBusquedasModel();
	    
	    if( empty( $_SESSION['usuario_usuarios'] ) ){
	        $this->redirect("Usuarios","sesion_caducada");
	        exit();
	    }
	    
	    $nombre_controladores = "PrincipalBusquedaSocios";
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
	    
	    $this->view_principal("PrincipalPagoAportes",$datos);
	
	}
	
	public function CargaDatosParticipe(){
	    
	    $busquedas = new PrincipalBusquedasModel();
	    $resp  = null;
	    
	    try {
	        
	        $id_participes = $_POST['id_participes'];
	        
	        if( !empty( error_get_last() ) ){ throw new Exception("Variable no recibida"); }
	        
	        $col1  = " aa.id_participes ,aa.cedula_participes ,aa.nombre_participes ,aa.apellido_participes ,bb.id_entidad_patronal ,bb.nombre_entidad_patronal";
	        $tab1  = " core_participes aa
	        INNER JOIN core_entidad_patronal bb ON bb.id_entidad_patronal = aa.id_entidad_patronal ";
	        $whe1  = " aa.id_participes = $id_participes ";
	        $rsConsulta1   = $busquedas->getCondicionesSinOrden($col1, $tab1, $whe1, "");
	        
	        $resp['dataParticipe'] = ( empty($rsConsulta1) ) ? null : $rsConsulta1;
	        
	        $col2  = " id_contribucion_categoria ,nombre_contribucion_categoria ";
	        $tab2  = " public.core_contribucion_categoria ";
	        $whe2  = " nombre_contribucion_categoria = 'APORTES PERSONALES' ";
	        $rsConsulta2   = $busquedas->getCondicionesSinOrden($col2, $tab2, $whe2, "");
	        
	        $resp['dataContribucion'] = ( empty($rsConsulta2) ) ? null : $rsConsulta2;
	        
	        $error_pg = pg_last_error();
	        if( !empty($error_pg) ){
	            throw new Exception( $error_pg );
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
?>