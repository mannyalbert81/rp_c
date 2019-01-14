<?php

class SolicitudCabezaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $solicitud_cabeza=new SolicitudCabezaModel();
	    $productos=new ProductosModel();
					//Conseguimos todos los usuarios
	    $resultSet=$solicitud_cabeza->getAll("id_solicitud_cabeza");
	    $resultProdu=$productos->getAll("id_productos");
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

		$nombre_controladores = "SolicitudCabeza";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $solicitud_cabeza->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{ 
				if (isset ($_GET["id_solicitud_cabeza"])   )
				{

					$nombre_controladores = "SolicitudCabeza";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $solicitud_cabeza->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_productos = $_GET["id_productos"];
						$columnas = " id_grupos,
                                     codigo_productos,
                                     marca_productos,
                                     nombre_productos,
                                     descripcion_productos,
                                    unidad_medida_productos,
                                     ult_precio_productos ";
						$tablas   = "productos";
						$where    = "id_productos = '$_id_productos' "; 
						$id       = "codigo_productos";
							
						$resultEdit = $productos->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Solicitud Cabeza"
					
						));
					
					
					}
					
				}
		
				
				$this->view("SolicitudCabeza",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultProdu" =>$resultProdu,
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Solicitud Cabeza"
				
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
			$this->redirect("SolicitudCabeza", "index");

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