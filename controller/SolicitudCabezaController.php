<?php

class SolicitudCabezaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $solicitud_cabeza=new SolicitudCabezaModel();
					//Conseguimos todos los usuarios
     	$resultSet=$grupos->getAll("id_grupos");
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Grupos";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $grupos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_grupos"])   )
				{

					$nombre_controladores = "Grupos";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $grupos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_grupos = $_GET["id_grupos"];
						$columnas = " id_grupos, nombre_grupos ";
						$tablas   = "grupos";
						$where    = "id_grupos = '$_id_grupos' "; 
						$id       = "nombre_grupos";
							
						$resultEdit = $grupos->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Grupos"
					
						));
					
					
					}
					
				}
		
				
				$this->view("Grupos",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Grupos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaGrupos(){
			
		session_start();
		$grupos=new GruposModel();

		$nombre_controladores = "Grupos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $grupos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$grupos=new GruposModel();
		
			if (isset ($_POST["nombre_grupos"])   )
			{
				
			    $_nombre_grupos = $_POST["nombre_grupos"];
			    $_id_grupos =  $_POST["id_grupos"];
				
			    if($_id_grupos > 0){
					
					$columnas = " nombre_grupos = '$_nombre_grupos'";
					$tabla = "grupos";
					$where = "id_grupos = '$_id_grupos'";
					$resultado=$grupos->UpdateBy($columnas, $tabla, $where);
					
				}else{
					
					$funcion = "ins_grupos";
					$parametros = " '$_nombre_grupos'";
					$grupos->setFuncion($funcion);
					$grupos->setParametros($parametros);
					$resultado=$grupos->Insert();
				}
				
				
				
		
			}
			$this->redirect("Grupos", "index");

		}
		else
		{
			$this->view("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Grupos"
		
			));
		
		
		}
		
	}
	
	public function borrarId()
	{

		session_start();
		$grupos=new GruposModel();
		$nombre_controladores = "Grupos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $grupos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
			if(isset($_GET["id_grupos"]))
			{
			    $id_grupos=(int)$_GET["id_grupos"];
		
				
				
			    $grupos->deleteBy(" id_grupos",$id_grupos);
				
				
			}
			
			$this->redirect("Grupos", "index");
			
			
		}
		else
		{
			$this->view("Error",array(
				"resultado"=>"No tiene Permisos de Borrar Grupos"
			
			));
		}
				
	}
	
	
	public function Reporte(){
	
		//Creamos el objeto usuario
	    $grupos=new GruposModel();
		//Conseguimos todos los usuarios
		
	
	
		session_start();
	
	
		if (isset(  $_SESSION['usuario']) )
		{
		    $resultRep = $grupos->getByPDF("id_grupos, nombre_grupos", " nombre_grupos != '' ");
			$this->report("Grupos",array(	"resultRep"=>$resultRep));
	
		}
					
	
	}
	
	
	
}
?>