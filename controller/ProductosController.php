<?php

class ProductosController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $productos=new ProductosModel();
					//Conseguimos todos los usuarios
	    $resultSet=$productos->getAll("id_productos");
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Productos";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
						
				
				$this->view("Productos",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Productos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaProductos(){
			
		session_start();
		$productos=new ProductosModel();

		$nombre_controladores = "Productos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $productos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		
		
			$resultado = null;
			$productos=new ProductosModel();
		
			if (isset ($_POST["codigo_productos"])   )
			{
				
			    $_codigo_productos = $_POST["codigo_productos"];
			    $_id_productos =  $_POST["id_productos"];
			    $_nombre_productos =  $_POST["nombre_productos"];
			    $_descripcion_productos =  $_POST["descripcion_productos"];
			    $_unidad_medida_productos =  $_POST["unidad_medida_productos"];
			    $_ult_precio_productos =  $_POST["ult_precio_productos"];
				
			    if($_id_productos > 0){
					
					$columnas = " codigo_productos = '$_codigo_productos', 
                                  id_productos = '$_id_productos',
                                  nombre_productos = '$_nombre_productos',
                                  descripcion_productos = '$_descripcion_productos',
                                  unidad_medida_productos = '$_unidad_medida_productos',
                                  ult_precio_productos = '$_ult_precio_productos'

                                   ";
					$tabla = "public.productos, public.grupos";
					$where = "grupos.id_grupos = productos.id_grupos";
					$resultado=$productos->UpdateBy($columnas, $tabla, $where);
					
				}else{
					
					$funcion = "ins_productos";
					$parametros = " '$_codigo_productos'";
					$productos->setFuncion($funcion);
					$productos->setParametros($parametros);
					$resultado=$productos->Insert();
				}
				
				
				
		
			}
			$this->redirect("Productos", "index");

		}
		else
		{
			$this->view("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Productos"
		
			));
		
		
		}
		
	}
	
	public function borrarId()
	{

		session_start();
		$productos=new ProductosModel();
		$nombre_controladores = "Productos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $productos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
			if(isset($_GET["id_productos"]))
			{
			    $id_productos=(int)$_GET["id_productos"];
		
				
				
			    $productos->deleteBy(" id_productos",$id_productos);
				
				
			}
			
			$this->redirect("Productos", "index");
			
			
		}
		else
		{
			$this->view("Error",array(
				"resultado"=>"No tiene Permisos de Borrar Productos"
			
			));
		}
				
	}
	
	

	
	
}
?>