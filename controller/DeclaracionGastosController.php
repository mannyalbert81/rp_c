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
	    
	    $empleados = new EmpleadosModel();
	    
	    $nombre_controladores = "DeclaracionGastos";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $empleados->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	        
	        if(!empty($resultPer)){
	            
	            $_fecha_mes=date('m');
	            if($_fecha_mes != 12){
	                
	                echo "Periodo no abierto para formulario de declaración de gastos";
	                exit();
	            }
	            
	        $_id_empleados = (isset($_POST["id_empleados"])) ? $_POST["id_empleados"] :0 ;
	        $_cedula_empleado = (isset($_POST["cedula_empleado"])) ? $_POST["cedula_empleado"] : "";
	        $_nombre_empleados = (isset($_POST["nombre_empleados"])) ? $_POST["nombre_empleados"] : 0 ;
	        $_tag_103 = (isset($_POST["tag_103"])) ? $_POST["tag_103"] : 0 ;
	        $_tag_104 = (isset($_POST["tag_104"])) ? $_POST["tag_104"] : 0 ;
	        $_tag_105 = (isset($_POST["tag_105"])) ? $_POST["tag_105"] : 0 ;
	        $_tag_106 = (isset($_POST["tag_106"])) ? $_POST["tag_106"] : 0 ;
	        $_tag_107 = (isset($_POST["tag_107"])) ? $_POST["tag_107"] : 0 ;
	        $_tag_108 = (isset($_POST["tag_108"])) ? $_POST["tag_108"] : 0 ;
	        $_tag_109 = (isset($_POST["tag_109"])) ? $_POST["tag_109"] : 0 ;
	        $_tag_110 = (isset($_POST["tag_110"])) ? $_POST["tag_110"] : 0 ;
	        $_tag_111 = (isset($_POST["tag_111"])) ? $_POST["tag_111"] : 0 ;
	        $_ruc = (isset($_POST["ruc"])) ? $_POST["ruc"] : 0 ;
	        $_razon_social = (isset($_POST["razon_social"])) ? $_POST["razon_social"] : 0 ;
	        $_anio_formulario_107 = (isset($_POST["anio_formulario_107"])) ? $_POST["anio_formulario_107"] : 0 ;
	        
	      
	      
	      $_id_estado =null;
	      $_columnas = "id_estado, nombre_estado";
	      $_tablas ="estado";
	      $_where ="tabla_estado='tri_formulario_107' AND nombre_estado='ACTIVO'";
	      $_id ="id_estado";
	         
	      
	      $_rs_consulta = $empleados->getCondiciones($_columnas, $_tablas, $_where, $_id);
	      if(!empty($_rs_consulta)){
	          $_id_estado = $_rs_consulta[0]->id_estado;
	      }
	      else{
	          echo "no se encontro estado";
	          exit();
	      }
	      
	      $_fecha_anio=date('Y');
	      $_fecha_mes=date('m');
	      
	      $_columnas = "id_empleados, tag_103_formulario_107,tag_104_formulario_107, tag_105_formulario_107, anio_formulario_107";
	      $_tablas ="tri_formulario_107";
	      $_where ="id_empleados='$_id_empleados' AND anio_formulario_107='$_fecha_anio'";
	      $_id ="id_empleados";
	      
	      $_rs_consulta = $empleados->getCondiciones($_columnas, $_tablas, $_where, $_id);
	      
	      if(!empty($_rs_consulta)){
	          echo "Usted ya ingreso";
	          exit();
	      }
	      else{
	      
	      $funcion = "ins_formulario_107";
	      $parametros = " '$_id_empleados','$_tag_103','$_tag_104', '$_tag_105','$_tag_106',
                            '$_tag_107','$_tag_108', '$_tag_109','$_tag_110','$_tag_111','$_id_estado', '$_anio_formulario_107'";
	      $consulta = $empleados->getconsultaPG($funcion, $parametros);
	      
	      $ResultDeclaracionGastos = $empleados->llamarconsultaPG($consulta);
	      $error = pg_last_error();
	      
	      if(!empty($error) ){    echo "Declaracion de gastos no ingresada"; exit();}
	      
	      
	      
	      $respuesta=array();
	      $respuesta['mensaje']=1;
	      $respuesta['respuesta']='';
	      
	      echo json_encode($respuesta);
	      
	      }
	    }
	}
	
	
	
}
?>