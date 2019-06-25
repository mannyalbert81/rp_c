<?php

class TablaAmortizacionController extends ControladorBase{
    
	public function __construct() {
		parent::__construct();
	}

	public function index(){
	    
	    session_start();
	    $cuentas_pagar = new CuentasPagarModel();
	    
	    if( !isset($_SESSION['id_usuarios']) ){
	        
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $_id_rol = $_SESSION['id_rol'];
	    $nombre_controladores = "ReporteMovimientos";
	    $resultPer = $cuentas_pagar->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$_id_rol' " );
	    
	    if( empty($resultPer)){
	        
	        $this->view("Error",array(
	            "resultado"=>"No tiene Permisos de Acceso a Movimientos Productos Cabeza"	            
	        ));
	        
	        exit();	
	    }
	    
	    $this->view_Contable('MovimientosContable',array());
	}
	

	
	public function GENERAR_REPORTE(){
	    session_start();
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $datos = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	        $datos['USU']="sadgasddddddddddddddddddddddd";
	    }
	    
	    $this->verReporte("TablaAmortizacion", array('datos_empresa'=>$datos_empresa, 'datos'=>$datos));
	    
	  
	}

	

	
	
}



?>