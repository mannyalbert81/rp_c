<?php

class TipoComprobantesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	$tipo_comprobantes= new TipoComprobantesModel(); 
		
	   //Conseguimos todos los usuarios
		$resultSet=$tipo_comprobantes->getAll("id_tipo_comprobantes");
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
			$tipo_comprobantes= new TipoComprobantesModel();
			
		
			
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "TipoComprobantes";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $tipo_comprobantes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_tipo_comprobantes"])   )
				{

					$nombre_controladores = "TipoComprobantes";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $tipo_comprobantes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
						$_id_tipo_comprobantes = $_GET["id_tipo_comprobantes"];
						$columnas = " id_tipo_comprobantes, nombre_tipo_comprobantes";
						$tablas   = "tipo_comprobantes";
						$where    = "id_tipo_comprobantes = '$_id_tipo_comprobantes' "; 
						$id       = "nombre_tipo_comprobantes";
							
						$resultEdit = $tipo_comprobantes->getCondiciones($columnas ,$tablas ,$where, $id);

						$traza=new TrazasModel();
						$_nombre_controlador = "Tipo Comprobantes";
						$_accion_trazas  = "Editar";
						$_parametros_trazas = $_id_tipo_comprobantes;
						$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Tipos de Comprobantes"
					
						));
					
					
					}
					
				}
		
				
				$this->view("TipoComprobantes",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Tipos de Comprobantes"
				
				));
				
				exit();	
			}
				
		}
		else 
		{
				$this->view("ErrorSesion",array(
						"resultSet"=>""
			
				));
		
		}
	
	}
	
	public function InsertaTipoComprobantes(){
			
		session_start();

		
		$tipo_comprobantes=new TipoComprobantesModel();
		$nombre_controladores = "TipoComprobantes";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $tipo_comprobantes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		
		
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$tipo_comprobantes=new TipoComprobantesModel();
		
		
			if (isset ($_POST["nombre_tipo_comprobantes"]) )
				
			{
				
				$_nombre_tipo_comprobantes = $_POST["nombre_tipo_comprobantes"];
				
				if(isset($_POST["id_tipo_comprobantes"])) 
				{
					
					$_id_tipo_comprobantes = $_POST["id_tipo_comprobantes"];
					$colval = " nombre_tipo_comprobantes = '$_nombre_tipo_comprobantes'   ";
					$tabla = "tipo_comprobantes";
					$where = "id_tipo_comprobantes = '$_id_tipo_comprobantes'    ";
					
					$resultado=$tipo_comprobantes->UpdateBy($colval, $tabla, $where);
					
				}else {
					
			

				
				$funcion = "ins_tipo_comprobantes";
				
				$parametros = " '$_nombre_tipo_comprobantes' ";
					
				$tipo_comprobantes->setFuncion($funcion);
		
				$tipo_comprobantes->setParametros($parametros);
		
		
				$resultado=$tipo_comprobantes->Insert();
				
				$traza=new TrazasModel();
				$_nombre_controlador = "Tipo Comprobantes";
				$_accion_trazas  = "Guardar";
				$_parametros_trazas = $_nombre_tipo_comprobantes;
				$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
				
				}
			 
			 
		
			}
			$this->redirect("tipo_comprobantes", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar Tipos de Comprobantes"
		
			));
		
		
		}
	

		$tipo_comprobantes=new TipoComprobantesModel();

		$nombre_controladores = "TipoComprobantes";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $tipo_comprobantes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
		
		
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$tipo_comprobantes=new TipoComprobantesModel();
		
			
			
			if (isset ($_POST["nombre_tipo_comprobantes"]) )
				
			{
				$_nombre_tipo_comprobantes = $_POST["nombre_tipo_comprobantes"];
				
				if(isset($_POST["id_tipo_comprobantes"]))
				{
				$_id_tipo_comprobantes = $_POST["id_tipo_comprobantes"];
				$colval = " nombre_tipo_comprobantes = '$_nombre_tipo_comprobantes'   ";
				$tabla = "tipo_comprobantes";
				$where = "id_tipo_comprobantes = '$_id_tipo_comprobantes'    ";
					
				$resultado=$tipo_comprobantes->UpdateBy($colval, $tabla, $where);
					
				}else {
				
			
				$funcion = "ins_tipo_comprobantes";
				
				$parametros = " '$_nombre_tipo_comprobantes'  ";
					
				$tipo_comprobantes->setFuncion($funcion);
		
				$tipo_comprobantes->setParametros($parametros);
		
		
				$resultado=$tipo_comprobantes->Insert();
			 }
		
			}
			$this->redirect("TipoComprobantes", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar tipos de Comprobantes"
		
			));
		
		
		}
		
	}




	public function borrarId()
	{

		session_start();
		
		$permisos_rol=new PermisosRolesModel();
		$nombre_controladores = "Roles";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $permisos_rol->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
			if(isset($_GET["id_tipo_comprobantes"]))
			{
				$id_tipo_comprobantes=(int)$_GET["id_tipo_comprobantes"];
				
				$tipo_comprobantes=new TipoComprobantesModel();
				
				$tipo_comprobantes->deleteBy(" id_tipo_comprobantes",$id_tipo_comprobantes);
				
				$traza=new TrazasModel();
				$_nombre_controlador = "Tipo Comprobantes";
				$_accion_trazas  = "Borrar";
				$_parametros_trazas = $id_tipo_comprobantes;
				$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
			}
			
			$this->redirect("TipoComprobantes", "index");
			
			
		}
		else
		{
			$this->view("Error",array(
				"resultado"=>"No tiene Permisos de Borrar Tipos de Comprobantes"
			
			));
		}
				
	}
	
	
	public function Reporte(){
	
		//Creamos el objeto usuario
		$tipo_comprobantes=new TipoComprobantesModel();
		//Conseguimos todos los usuarios
		
	
	
		session_start();
	
	
		if (isset(  $_SESSION['usuario']) )
		{
			$resultRep = $roles->getByPDF("id_rol, nombre_rol", " nombre_rol != '' ");
			$this->report("TipoComprobantes",array(	"resultRep"=>$resultRep));
	
		}
					
	
	}
	
	
	
}
?>