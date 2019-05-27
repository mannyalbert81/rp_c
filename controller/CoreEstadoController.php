<?php

class CoreEstadoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $estado=new CoreEstadoModel();
	    
	    $columnas = "core_estado.descripcion_estado,
                     core_estatus.nombre_estatus";
	    $tablas   = "public.core_estado,
                     public.core_estatus";
	    $where    = "core_estatus.id_estatus = core_estado.id_estatus";
	    $id       = "core_estado.id_estado";
	    
	    $resultSet = $estado->getCondiciones($columnas ,$tablas ,$where, $id);
	    
	      $estatus = new EstatusModel();
	    $resultEstatu=$estatus->getAll("id_estatus");
		$resultEdit = "";
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $estado = new CoreEstadoModel();
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "CoreEstado";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estado->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_estado"])   )
				{

					$nombre_controladores = "CoreEstado";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $estado->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_estado = $_GET["id_estado"];
						$columnas = "id_estado, descripcion_estado, id_estatus";
						$tablas   = "core_estado";
						$where    = "id_estado = '$_id_estado' "; 
						$id       = "descripcion_estado";
							
						$resultEdit = $estado->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Estado"
					
						));
					
					}
					
				}
				
				$this->view_Core("CoreEstado",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultEstatu" => $resultEstatu
				));
				
			}
			else
			{
			    $this->view_Core("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Estado"
				
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
	
	public function InsertaEstado(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $estado =new CoreEstadoModel();
    		$nombre_controladores = "CoreEstado";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $estado->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		
		
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$estado =new CoreEstadoModel();
		
		
			if (isset ($_POST["descripcion_estado"]) )
			{
				
			    $_descripcion_estado = $_POST["descripcion_estado"];
			    $_id_estatus = $_POST["id_estatus"];
				
				if(isset($_POST["id_estado"])) 
				{
					
				    $_id_estado = $_POST["id_estado"];
					$colval = " descripcion_estado = '$_descripcion_estado', id_estatus = '$_id_estatus'";
					$tabla = "core_estado";
					$where = "id_estado = '$_id_estado'    ";
					
					$resultado=$_id_estado->UpdateBy($colval, $tabla, $where);
					
				}else {
    						
    				$funcion = "ins_core_estado";
    				$parametros = " '$_descripcion_estado', '$_id_estatus' ";
    				$estado->setFuncion($funcion);
    				$estado->setParametros($parametros);
    				$resultado=$estado->Insert();
    					
				}
			 
			}
		
			     $this->redirect("CoreEstado", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar Estado"
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
			if(isset($_GET["id_estado"]))
			{
			    $id_estado=(int)$_GET["id_estado"];
			    $estado =new CoreEstadoModel();
			    $estado->deleteBy("id_estado",$id_estado);
				
			}
			
			$this->redirect("CoreEstado", "index");
			
		}
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>