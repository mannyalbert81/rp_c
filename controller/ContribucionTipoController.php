<?php

class ContribucionTipoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    
	    $contribucion_tipo=new ContribucionTipoModel();
	    
	    $columnas = " contribucion_tipo.id_contribucion_tipo,
                                       contribucion_categoria.nombre_contribucion_categoria, 
                                      contribucion_tipo.nombre_contribucion_tipo, 
                                      estado.nombre_estado, 
                                      estatus.nombre_estatus";
	    $tablas   = "    public.contribucion_categoria, 
                          public.contribucion_tipo, 
                          public.estatus, 
                          public.estado";
	    $where    = "  contribucion_categoria.id_contribucion_categoria = contribucion_tipo.id_contribucion_categoria AND
                      contribucion_tipo.id_estatus = estatus.id_estatus AND
                      estado.id_estado = contribucion_tipo.id_estado";
	    $id       = "contribucion_tipo.id_contribucion_tipo";
	    
	    $resultSet = $contribucion_tipo->getCondiciones($columnas ,$tablas ,$where, $id);
	    
	    
	    
	  	$resultEdit = "";
		
		$contribucion_categoria=new ContribucionCategoriaModel();
		$resultCat=$contribucion_categoria->getAll("nombre_contribucion_categoria");
		
		$estado = new EstadoModel();
		$whe_estado = "tabla_estado = 'contribucion_tipo'";
		$resultEst = $estado->getBy($whe_estado);
		
		$estatus=new EstatusModel();
		$resultEsta=$estatus->getAll("nombre_estatus");
		
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $contribucion_tipo = new ContribucionTipoModel();
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "ContribucionTipo";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $contribucion_tipo->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_contribucion_tipo"])   )
				{

					$nombre_controladores = "ContribucionTipo";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $contribucion_tipo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_contribucion_tipo = $_GET["id_contribucion_tipo"];
						$columnas = "id_contribucion_tipo, id_contribucion_categoria, nombre_contribucion_tipo, id_estado, id_estatus";
						$tablas   = "contribucion_tipo";
						$where    = "id_contribucion_tipo = '$_id_contribucion_tipo' "; 
						$id       = "nombre_contribucion_tipo";
							
						$resultEdit = $contribucion_tipo->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Contribucion Tipo"
					
						));
					
					}
					
				}
				
				$this->view_Contable("ContribucionTipo",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultCat"=>$resultCat, "resultEst"=>$resultEst, "resultEsta"=>$resultEsta
				));
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Contribucion Tipo"
				
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
	
	
	public function InsertaContribucionTipo(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $contribucion_tipo =new ContribucionTipoModel();
    		$nombre_controladores = "ContribucionTipo";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $contribucion_tipo->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		

		if (!empty($resultPer))
		{
		
			$resultado = null;
			$contribucion_tipo =new ContribucionTipoModel();
		
		
			if (isset ($_POST["nombre_contribucion_tipo"]) )
			{
			 
			    $_id_contribucion_categoria = $_POST["id_contribucion_categoria"];
			    $_nombre_contribucion_tipo = $_POST["nombre_contribucion_tipo"];
			    $_id_estado = $_POST["id_estado"];
			    $_id_estatus = $_POST["id_estatus"];
			    
				if(isset($_POST["id_contribucion_tipo"])) 
				{
					
				    $_id_contribucion_tipo = $_POST["id_contribucion_tipo"];
					$colval = "id_contribucion_categoria = '$_id_contribucion_categoria', nombre_contribucion_tipo = '$_nombre_contribucion_tipo', id_estado = '$_id_estado', id_estatus = '$_id_estatus'";
					$tabla = "contribucion_tipo";
					$where = "id_contribucion_tipo = '$_id_contribucion_tipo'    ";
					
					$resultado=$contribucion_tipo->UpdateBy($colval, $tabla, $where);
					
				}else {
    			  	$funcion = "ins_contribucion_tipo";
    				$parametros = " '$_id_contribucion_categoria','$_nombre_contribucion_tipo','$_id_estado','$_id_estatus' ";
    				$contribucion_tipo->setFuncion($funcion);
    				$contribucion_tipo->setParametros($parametros);
    				$resultado=$contribucion_tipo->Insert();
    					
				}
			 
			}
		
			     $this->redirect("ContribucionTipo", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar Contribucion Tipo"
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
			if(isset($_GET["id_contribucion_tipo"]))
			{
			    $id_contribucion_tipo=(int)$_GET["id_contribucion_tipo"];
			    $contribucion_tipo =new ContribucionTipoModel();
			    $contribucion_tipo->deleteBy(" id_contribucion_tipo",$id_contribucion_tipo);
				
			}
			
			$this->redirect("ContribucionTipo", "index");
			
		} 
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>