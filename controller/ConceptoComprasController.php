<?php

class ConceptoComprasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $concepto_compras= new ConceptoComprasModel(); 
		
	   //Conseguimos todos los usuarios
     	$resultSet=$concepto_compras->getAll("id_concepto_compras");
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $concepto_compras= new ConceptoComprasModel();
			
		
			
			
			$permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "ConceptoCompras";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $concepto_compras->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_concepto_compras"])   )
				{

					$nombre_controladores = "ConceptoCompras";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $concepto_compras->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_concepto_compras = $_GET["id_concepto_compras"];
						$columnas = "nombre_concepto_compras, porcentaje_iva_concepto_compras, porcentaje_renta_concepto_compras";
						$tablas   = "concepto_compras";
						$where    = "id_concepto_compras = '$_id_concepto_compras' "; 
						$id       = "nombre_concepto_compras";
							
						$resultEdit = $concepto_compras->getCondiciones($columnas ,$tablas ,$where, $id);

						$traza=new TrazasModel();
						$_nombre_controlador = "ConceptoCompras";
						$_accion_trazas  = "Editar";
						$_parametros_trazas = $_id_concepto_compras;
						$resultado = $traza->AuditoriaControladores($_accion_trazas, $_parametros_trazas, $_nombre_controlador);
					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Concepto de Compras"
					
						));
					
					
					}
					
				}
		
				
				$this->view_Inventario("ConceptoCompras",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
			    $this->view_Inventario("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Concepto Compras"
				
				));
				
				exit();	
			}
				
		}
		else 
		{
		    $this->view_Inventario("ErrorSesion",array(
						"resultSet"=>""
			
				));
		
		}
	
	}
	
	public function InsertaConceptoCompras(){
	    
	    session_start();
	    $concepto_compras=new ConceptoComprasModel();
	    
	    $nombre_controladores = "ConceptoCompras";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $concepto_compras->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        
	        
	        
	        $resultado = null;
	        $concepto_compras=new ConceptoComprasModel();
	        
	        if (isset ($_POST["nombre_concepto_compras"])   )
	        {
	            $_nombre_concepto_compras =  $_POST["nombre_concepto_compras"];
	            $_porcentaje_iva_concepto_compras = $_POST["porcentaje_iva_concepto_compras"];
	            $_porcentaje_renta_concepto_compras = $_POST["porcentaje_renta_concepto_compras"];
	           
	            
	            
	            if($_id_concepto_compras > 0){
	                
	                $columnas = " nombre_concepto_compras = '$_nombre_concepto_compras',
                                  porcentaje_iva_concepto_compras = '$_porcentaje_iva_concepto_compras',
                                  porcentaje_renta_concepto_compras = '$_porcentaje_renta_concepto_compras'";
	                $tabla = "concepto_compras";
	                $where = "id_concepto_compras = '$_id_concepto_compras'";
	                $resultado=$concepto_compras->UpdateBy($columnas, $tabla, $where);
	                
	            }else{
	                
	                $funcion = "ins_concepto_compras";
	                $parametros = " '$_nombre_concepto_compras','$_porcentaje_iva_concepto_compras','$_porcentaje_renta_concepto_compras'";
	                $concepto_compras->setFuncion($funcion);
	                $concepto_compras->setParametros($parametros);
	                $resultado=$concepto_compras->Insert();
	            }
	            
	            
	            
	            
	        }
	        $this->redirect("ConceptoCompras", "index");
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Insertar Concepto de Compras"
	            
	        ));
	        
	        
	    }
	    
	}


	public function borrarId()
	{
	    
	    session_start();
	    $concepto_compras=new ConceptoComprasModel();
	    $nombre_controladores = "ConceptoCompras";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $concepto_compras->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        if(isset($_GET["id_concepto_compras"]))
	        {
	            $id_concepto_compras=(int)$_GET["id_concepto_compras"];
	            
	            
	            
	            $concepto_compras->deleteBy("id_concepto_compras",$id_concepto_compras);
	            
	            
	        }
	        
	        $this->redirect("ConceptoCompras", "index");
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Borrar Concepto de Compras"
	            
	        ));
	    }
	    
	}
	
	
	
	
}
?>