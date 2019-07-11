 <?php

class CoreTipoCreditoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $core_tipo_credito= new CoreTipoCreditoModel();
	    $columnas = "core_tipo_creditos.nombre_tipo_creditos, 
                     core_tipo_creditos.codigo_tipo_creditos, 
                     estado.nombre_estado,
                     core_tipo_creditos.id_tipo_creditos";
	    $tablas   = "public.estado, 
                     public.core_tipo_creditos";
	    $where    = "core_tipo_creditos.id_estado = estado.id_estado";
	    $id       = "core_tipo_creditos.id_tipo_creditos";
	    $resultSet = $core_tipo_credito->getCondiciones($columnas ,$tablas ,$where, $id);
	    
	    $estado= null;
	    $estado = new EstadoModel();
	    $whe_estado = "tabla_estado = 'core_tipo_creditos'";
	    $resultEst = $estado->getBy($whe_estado);
	    
	    $resultEdit = null;
	    
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $core_tipo_credito= new CoreTipoCreditoModel();
			
		    $nombre_controladores = "CoreTipoCredito";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $core_tipo_credito->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_tipo_credito"])   )
				{

					$nombre_controladores = "CoreTipoCredito";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $core_tipo_credito->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_tipo_credito = $_GET["id_tipo_credito"];
						$columnas = " id_tipo_creditos, nombre_tipo_creditos, codigo_tipo_creditos, id_estado";
						$tablas   = "core_tipo_creditos";
						$where    = "id_tipo_creditos = '$_id_tipo_credito' "; 
						$id       = "nombre_tipo_creditos";
							
						$resultEdit = $core_tipo_credito->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar"
					
						));
					
					}
					
				}
				
				$this->view_Contable("CoreTipoCredito",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultEst" =>$resultEst
				));
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso"
				
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
	
	public function InsertaCoreTipoCredito(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $core_tipo_credito=new CoreTipoCreditoModel();
    		$nombre_controladores = "CoreTipoCredito";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $core_tipo_credito->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		
		
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$core_tipo_credito=new CoreTipoCreditoModel();
		
		
			if (isset ($_POST["nombre_tipo_credito"]) )
			{
				
			    $_nombre_tipo_credito = $_POST["nombre_tipo_credito"];
			    $_codigo_tipo_credito = $_POST["codigo_tipo_credito"];
			    $_id_estado = $_POST["id_estado"];
				
				if(isset($_POST["id_tipo_credito"])) 
				{
					
				    $_id_tipo_credito = $_POST["id_tipo_credito"];
					$colval = " nombre_tipo_credito = '$_nombre_tipo_credito', codigo_tipo_credito = '$_codigo_tipo_credito', id_estado = '$_id_estado'";
					$tabla = "core_tipo_credito";
					$where = "id_tipo_credito = '$_id_tipo_credito'    ";
					
					$resultado=$core_tipo_credito->UpdateBy($colval, $tabla, $where);
					
				}else {
    						
    				$funcion = "ins_core_tipo_credito";
    				$parametros = " '$_nombre_tipo_credito', '$_codigo_tipo_credito', '$_id_estado'";
    				$core_tipo_credito->setFuncion($funcion);
    				$core_tipo_credito->setParametros($parametros);
    				$resultado=$core_tipo_credito->Insert();
    					
				}
			 
			}
		
			     $this->redirect("CoreTipoCredito", "index");

		}
		else
		{
			$this->view("Error",array(
					
					"resultado"=>"No tiene Permisos de Insertar"
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
			if(isset($_GET["id_tipo_credito"]))
			{
			    $id_tipo_credito=(int)$_GET["id_tipo_credito"];
				$core_tipo_credito=new CoreTipoCreditoModel();
				$core_tipo_credito->deleteBy(" id_tipo_credito",$id_tipo_credito);
				
			}
			
			$this->redirect("CoreTipoCredito", "index");
			
		}
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>