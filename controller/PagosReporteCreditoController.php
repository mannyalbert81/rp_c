<?php

class PagosReporteCreditoController extends ControladorBase{

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
	    
	    $nombre_controladores = "PagosReporteCredito";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $busquedas->getPermisosVer(" controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    if (empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso al proceso de Generar el Archivo de pago"
	            
	        ));
	        exit();
	    }
	    
	   
	    
	    $this->view_tesoreria("PagosReporteCredito");
	    
	}
	

	/************************************************************** TERMINA FUNCIONES AUXILIARES DEL CONTROLADOR *****************************/
	
}
?>