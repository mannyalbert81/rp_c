<?php

class ContribucionCategoriaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $contribucion_categoria= new ContribucionCategoriaModel(); 
	    $resultSet=$contribucion_categoria->getAll("id_contribucion_categoria");
		$resultEdit = "";
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $contribucion_categoria = new ContribucionCategoriaModel();
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "ContribucionCategoria";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $contribucion_categoria->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_contribucion_categoria"])   )
				{

					$nombre_controladores = "ContribucionCategoria";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $contribucion_categoria->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_contribucion_categoria = $_GET["id_contribucion_categoria"];
						$columnas = "id_contribucion_categoria, nombre_contribucion_categoria";
						$tablas   = "core_contribucion_categoria";
						$where    = "id_contribucion_categoria = '$_id_contribucion_categoria' "; 
						$id       = "nombre_contribucion_categoria";
							
						$resultEdit = $contribucion_categoria->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Contribucion de Categoria"
					
						));
					
					}
					
				}
				
				$this->view_Core("ContribucionCategoria",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
				));
				
			}
			else
			{
			    $this->view_Core("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Contribucion de Categoria"
				
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
	
	public function InsertaContribucionCategoria(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $contribucion_categoria =new ContribucionCategoriaModel();
    		$nombre_controladores = "ContribucionCategoria";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $contribucion_categoria->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		
		
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$contribucion_categoria =new ContribucionCategoriaModel();
		
		
			if (isset ($_POST["nombre_contribucion_categoria"]) )
			{
				
			    $_nombre_contribucion_categoria = $_POST["nombre_contribucion_categoria"];
				
				if(isset($_POST["id_contribucion_categoria"])) 
				{
					
				    $_id_contribucion_categoria = $_POST["id_contribucion_categoria"];
					$colval = " nombre_contribucion_categoria = '$_nombre_contribucion_categoria'   ";
					$tabla = "core_contribucion_categoria";
					$where = "id_contribucion_categoria = '$_id_contribucion_categoria'    ";
					
					$resultado=$contribucion_categoria->UpdateBy($colval, $tabla, $where);
					
				}else {
    						
    				$funcion = "ins_contribucion_categoria";
    				$parametros = " '$_nombre_contribucion_categoria' ";
    				$contribucion_categoria->setFuncion($funcion);
    				$contribucion_categoria->setParametros($parametros);
    				$resultado=$contribucion_categoria->Insert();
    					
				}
			 
			}
		
			     $this->redirect("ContribucionCategoria", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar Contribucion Categoria"
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
			if(isset($_GET["id_contribucion_categoria"]))
			{
			    $id_contribucion_categoria=(int)$_GET["id_contribucion_categoria"];
			    $contribucion_categoria =new ContribucionCategoriaModel();
			    $contribucion_categoria->deleteBy(" id_contribucion_categoria",$id_contribucion_categoria);
				
			}
			
			$this->redirect("ContribucionCategoria", "index");
			
		}
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>