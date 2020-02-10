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
		
		$cedula_usuario= $_SESSION['cedula_usuarios'];
		
		$_id_empleados=null;
		
		$_columnas = "id_empleados,numero_cedula_empleados";
		$_tablas ="empleados";
		$_where ="numero_cedula_empleados='$cedula_usuario'";
		$_id ="id_empleados";
		
		$_rs_consulta = $bancos->getCondiciones($_columnas, $_tablas, $_where, $_id);
		
		if(!empty($_rs_consulta)){
		    $_id_empleados = $_rs_consulta[0]->id_empleados;
		}
		else{
		    $_id_empleados = 0;
		}
		
		$id_usuarios= $_SESSION['id_usuarios'];
		
		$_columnas = "id_empleados, numero_cedula_empleados, nombres_empleados";
		$_tablas ="empleados";
		$_where ="id_empleados ='$_id_empleados'";
		$_id ="id_empleados";
		
		$_rs_consulta = $bancos->getCondiciones($_columnas, $_tablas, $_where, $_id);
		
		$nombre_controladores = "DeclaracionGastos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $bancos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (empty($resultPer)){
		    
		    $this->view("Error",array(
		        "resultado"=>"No tiene Permisos de Acceso Bancos"
		        
		    ));
		    exit();
		}		
		$this->view_tributario("DeclaracionGastos",array("_rs_consulta"=>$_rs_consulta
		    
		    
		));
	}
	
	public function InsertarDeclaracionGastos(){
	    
	   
	    session_start();
	    
	    $declaraciones = new DeclaracionGastosModel();
	    
	    $nombre_controladores = "DeclaracionGastos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $declaraciones->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    
	    if (!empty($resultPer)){
	        
	        
	        $_id_formulario_107 =(isset($_POST['id_formulario_107'])) ? $_POST['id_formulario_107'] : 0;
	        $_id_empleados =(isset($_POST['id_empleados'])) ? $_POST['id_empleados'] : 0;
	        $_id_estado= 121;
	        $_anio_formulario_107 =(isset($_POST['anio_formulario_107'])) ? $_POST['anio_formulario_107'] : "";
	        $_ingresos_gravados_empleador =(isset($_POST['ingresos_gravados_empleador'])) ? $_POST['ingresos_gravados_empleador'] : "";
	        $_ingresos_otros_empleados =(isset($_POST['ingresos_otros_empleados'])) ? $_POST['ingresos_otros_empleados'] : "";
	        $_ingresos_proyectados =(isset($_POST['ingresos_proyectados'])) ? $_POST['ingresos_proyectados'] : "";
	        $_gastos_vivienda =(isset($_POST['gastos_vivienda'])) ? $_POST['gastos_vivienda'] : "";
	        $_gastos_educacion =(isset($_POST['gastos_educacion'])) ? $_POST['gastos_educacion'] : "";
	        $_gastos_salud =(isset($_POST['gastos_salud'])) ? $_POST['gastos_salud'] : "";
	        $_gastos_vestimenta =(isset($_POST['gastos_vestimenta'])) ? $_POST['gastos_vestimenta'] : "";
	        $_gastos_alimentacion =(isset($_POST['gastos_alimentacion'])) ? $_POST['gastos_alimentacion'] : "";
	        $_total_gastos =$_gastos_vivienda+$_gastos_educacion+$_gastos_salud+$_gastos_vestimenta+$_gastos_alimentacion;
	        $_ruc_agente_retencion =(isset($_POST['ruc_agente_retencion'])) ? $_POST['ruc_agente_retencion'] : "";
	        $_razon_social =(isset($_POST['razon_social'])) ? $_POST['razon_social'] : "";
	        
	       
	        
	        
	        $funcion = "ins_formulario_107";
	        $respuesta = 0 ;
	        $mensaje = "";
	        
	        if($_id_formulario_107 == 0){
	            
	            
	            
	            $parametros = " '$_id_empleados',
                                '$_id_estado',
                                '$_anio_formulario_107',
                                '$_ingresos_gravados_empleador',
                                '$_ingresos_otros_empleados',
                                '$_ingresos_proyectados',
                                '$_gastos_vivienda',
                                '$_gastos_educacion',
                                '$_gastos_salud',
                                '$_gastos_vestimenta',
                                '$_gastos_alimentacion',
                                '$_total_gastos',
                                '$_ruc_agente_retencion',
                                '$_razon_social'
                                 ";
	            
	            //echo $parametros; die();
	            
	            
	            $declaraciones->setFuncion($funcion);
	            $declaraciones->setParametros($parametros);
	            $resultado = $declaraciones->llamafuncionPG();
	            
	            
	            if(is_int((int)$resultado[0])){
	                
	                
	                $respuesta = $resultado[0];
	                $mensaje = "Presupuesto Ingresado Correctamente";
	            }
	            
	            
	            
	        }
	        
	        
	        
	        if((int)$respuesta > 0 ){
	            
	            echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
	            exit();
	        }
	        
	        echo "Error al Ingresar la Solicitud";
	        exit();
	        
	    }
	    else
	    {
	        $this->view_DeclaracionGastos("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Solicitudes"
	            
	        ));
	        
	        
	    }
	    
	    
	    
	}
	
	
	
}
?>