<?php

class PagoAportesController extends ControladorBase{

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
						
		$this->view_principal("PrincipalPagoAportes",$datos);		
	
	}
	
	public function cargaDatosParticipe(){
	    
	    $resp = null;
	    $participes = new ParticipesModel();
	    
	    $id_participes = $_POST['id_participes'];
	    
	    $col1  = "";
	}
	
	
	
	/************************************************************** FUNCIONES AUXILIARES DEL CONTROLADOR *************************************/
	
	/************************************************************** TERMINA FUNCIONES AUXILIARES DEL CONTROLADOR *****************************/
	
}
?>