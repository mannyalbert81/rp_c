<?php

class DeclaracionGastosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		$bancos = new BancosModel();
				
		session_start();
		
		if(empty( $_SESSION)){
		    
		    $this->redirect("Usuarios","sesion_caducada");
		    return;
		}
		
		$nombre_controladores = "DeclaracionGastos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $bancos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Bancos"
		        
		    ));
		    exit();
		}		    
			
				
		$this->view_tributario("DeclaracionGastos",array(
		    
		));
			
	
	}
	
	
	
}
?>