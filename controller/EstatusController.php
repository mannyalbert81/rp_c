<?php

class EstatusController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $estatus = new EstatusModel(); 
	    $resultSet=$estatus->getAll("id_estatus");
		$resultEdit = "";
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $estatus = new EstatusModel();
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "Estatus";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estatus->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_estatus"])   )
				{

					$nombre_controladores = "Estatus";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $estatus->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_estatus = $_GET["id_estatus"];
						$columnas = "id_estatus, nombre_estatus";
						$tablas   = "core_estatus";
						$where    = "id_estatus = '$_id_estatus' "; 
						$id       = "nombre_estatus";
							
						$resultEdit = $estatus->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Estatus"
					
						));
					
					}
					
				}
				
				$this->view_Core("Estatus",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
				));
				
			}
			else
			{
			    $this->view_Core("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Estatus"
				
				));
				
				exit();	
			}
				
		}
		else 
		{
		    $this->redirect("Usuarios","sesion_caducada");
		    
	
	        die();
		}
	
	}
	
	public function InsertaEstatus(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $estatus =new EstatusModel();
    		$nombre_controladores = "Estatus";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $estatus->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		
		
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$estatus =new EstatusModel();
		
		
			if (isset ($_POST["nombre_estatus"]) )
			{
				
			    $_nombre_estatus = $_POST["nombre_estatus"];
				
				if(isset($_POST["id_estatus"])) 
				{
					
				    $_id_estatus = $_POST["id_estatus"];
					$colval = " nombre_estatus = '$_nombre_estatus'   ";
					$tabla = "core_estatus";
					$where = "id_estatus = '$_id_estatus'    ";
					
					$resultado=$_id_estatus->UpdateBy($colval, $tabla, $where);
					
				}else {
    						
    				$funcion = "ins_estatus";
    				$parametros = " '$_nombre_estatus' ";
    				$estatus->setFuncion($funcion);
    				$estatus->setParametros($parametros);
    				$resultado=$estatus->Insert();
    					
				}
			 
			}
		
			     $this->redirect("Estatus", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar Estatus"
			));
		
		}
	
	}
	else{
	    
	    $this->redirect("Usuarios","sesion_caducada");
	    
	}
		
		
	}




	public function borrarId()
	{
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
			if(isset($_GET["id_estatus"]))
			{
			    $id_estatus=(int)$_GET["id_estatus"];
			    $estatus =new EstatusModel();
			    $estatus->deleteBy("id_estatus",$id_estatus);
				
			}
			
			$this->redirect("Estatus", "index");
			
		}
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>