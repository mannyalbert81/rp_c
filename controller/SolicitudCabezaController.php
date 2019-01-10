<?php

class SolicitudCabezaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $solicitud_detalle=new SolicitudDetalleModel();
					//Conseguimos todos los usuarios
	    $resultSet=$solicitud_detalle->getAll("id_solicitud_detalle");
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "SolicitudDetalle";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $solicitud_detalle->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_solicitud_detalle"])   )
				{

					$nombre_controladores = "SolicitudDetalle";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $solicitud_detalle->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_solicitud_detalle = $_GET["id_solicitud_detalle"];
						$columnas = " id_solicitud_detalle, cantidad_solicitud_detalle, id_solicitud_cabeza ";
						$tablas   = "solicitud_detalle";
						$where    = "id_solicitud_detalle = '$_id_solicitud_detalle' "; 
						$id       = "id_solicitud_detalle";
							
						$resultEdit = $solicitud_detalle->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{   
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Solicitud Detalle"
					
						));
					
					
					}
					
				}
		
				
				$this->view("SolicitudDetalle",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Solicitud Detalle"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaSolicitudDetalle(){
			
		session_start();
		$solicitud_detalle=new SolicitudDetalleModel();

		$nombre_controladores = "SolicitudDetalle";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $solicitud_detalle->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$solicitud_detalle=new SolicitudDetalleModel();
		
			if (isset ($_POST["cantidad_solicitud_detalle"])   )
			{
				
			    $_cantidad_solicitud_detalle = $_POST["cantidad_solicitud_detalle"];
			    $_id_solicitud_detalle =  $_POST["id_solicitud_detalle"];
				
			    if($_id_solicitud_detalle > 0){
					
					$columnas = " cantidad_solicitud_detalle = '$_cantidad_solicitud_detalle',
                    id_solicitud_cabeza = '$_id_solicitud_cabeza'";
					$tabla = "solicitud_detalle";
					$where = "id_solicitud_detalle = '$_id_solicitud_detalle'";
					$resultado=$solicitud_detalle->UpdateBy($columnas, $tabla, $where);
					die ();
					
				}else{
					
					$funcion = "ins_solicitud_detalle";
					$parametros = " '$_cantidad_solicitud_detalle', '$_id_solicitud_cabeza'";
					$solicitud_detalle->setFuncion($funcion);
					$solicitud_detalle->setParametros($parametros);
					$resultado=$solicitud_detalle->Insert();
				}
				
				
				
		
			}
			$this->redirect("SolicitudDetalle", "index");

		}
		else
		{
			$this->view("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Solicitud Detalle"
		
			));
		
		
		}
		
	}
	
	public function borrarId()
	{

		session_start();
		$solicitud_detalle=new SolicitudDetalleModel();
		$nombre_controladores = "SolicitudDetalle";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $solicitud_detalle->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
			if(isset($_GET["id_solicitud_detalle"]))
			{
			    $id_solicitud_detalle=(int)$_GET["id_solicitud_detalle"];
		
				
				
			    $solicitud_detalle->deleteBy(" id_solicitud_detalle",$id_solicitud_detalle);
				
				
			}
			
			$this->redirect("SolicitudDetalle", "index");
			
			
		}
		else
		{
			$this->view("Error",array(
				"resultado"=>"No tiene Permisos de Borrar Solicitud Detalle"
			
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