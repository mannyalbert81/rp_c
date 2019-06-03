 <?php

class CoreDiarioTipoCabezaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
	    session_start();
	    
	    $core_diario_tipo_cabeza= new CoreDiarioTipoCabezaModel();
	    $columnas =  "core_diario_tipo_cabeza.id_diario_tipo_cabeza, 
                      core_tipo_credito.nombre_tipo_credito, 
                      core_tipo_credito.codigo_tipo_credito, 
                      core_diario_tipo_cabeza.nombre_diario_tipo_cabeza, 
                      estado.nombre_estado";
	    $tablas   =  "public.core_diario_tipo_cabeza, 
                      public.core_tipo_credito, 
                      public.estado";
	    $where    = "core_diario_tipo_cabeza.id_tipo_credito = core_tipo_credito.id_tipo_credito AND
                     estado.id_estado = core_diario_tipo_cabeza.id_estado";
	    $id       = "core_diario_tipo_cabeza.id_diario_tipo_cabeza";
	    $resultSet = $core_diario_tipo_cabeza->getCondiciones($columnas ,$tablas ,$where, $id);
	    
	    $estado= null;
	    $estado = new EstadoModel();
	    $whe_estado = "tabla_estado = 'core_diario_tipo_cabeza'";
	    $resultEst = $estado->getBy($whe_estado);
	    
	    $tipo_credito = new CoreTipoCreditoModel();
	    $resultTipoCre = $tipo_credito ->getAll("id_tipo_credito");
	    
	    $resultEdit = null;
	    
	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    $core_diario_tipo_cabeza= new CoreDiarioTipoCabezaModel();
			
		    $permisos_rol = new CoreDiarioTipoCabezaModel();
			$nombre_controladores = "CoreDiarioTipoCabeza";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $core_diario_tipo_cabeza->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_diario_tipo_cabeza"])   )
				{

					$nombre_controladores = "CoreDiarioTipoCabeza";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $core_diario_tipo_cabeza->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_diario_tipo_cabeza = $_GET["id_diario_tipo_cabeza"];
						$columnas = " id_diario_tipo_cabeza, id_tipo_credito, nombre_diario_tipo_cabeza, id_estado";
						$tablas   = "core_diario_tipo_cabeza";
						$where    = "id_diario_tipo_cabeza = '$_id_diario_tipo_cabeza' "; 
						$id       = "nombre_diario_tipo_cabeza";
							
						$resultEdit = $core_diario_tipo_cabeza->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar"
					
						));
					
					}
					
				}
				
				$this->view_Contable("CoreDiarioTipoCabeza",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultEst" =>$resultEst, "resultTipoCre" =>$resultTipoCre
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
	
	public function InsertaCoreDiarioTipoCabeza(){
			
		session_start();
		
		if (isset($_SESSION['nombre_usuarios']) )
		{
		
		    $core_diario_tipo_cabeza=new CoreDiarioTipoCabezaModel();
    		$nombre_controladores = "CoreDiarioTipoCabeza";
    		$id_rol= $_SESSION['id_rol'];
    		$resultPer = $core_diario_tipo_cabeza->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    		
		
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$core_diario_tipo_cabeza=new CoreDiarioTipoCabezaModel();
		
		
			if (isset ($_POST["nombre_diario_tipo_cabeza"]) )
			{
			    $_id_tipo_credito = $_POST["id_tipo_credito"];
			    $_nombre_diario_tipo_cabeza = $_POST["nombre_diario_tipo_cabeza"];
			    $_id_estado = $_POST["id_estado"];
				
				if(isset($_POST["id_diario_tipo_cabeza"])) 
				{
					
				    $_id_diario_tipo_cabeza = $_POST["id_diario_tipo_cabeza"];
					$colval = "id_tipo_credito = '$_id_tipo_credito', nombre_diario_tipo_cabeza = '$_nombre_diario_tipo_cabeza', id_estado = '$_id_estado'";
					$tabla = "core_diario_tipo_cabeza";
					$where = "id_diario_tipo_cabeza = '$_id_diario_tipo_cabeza'    ";
					
					$resultado=$core_diario_tipo_cabeza->UpdateBy($colval, $tabla, $where);
					
				}else {
    						
    				$funcion = "ins_core_diario_tipo_cabeza";
    				$parametros = "'$_id_tipo_credito', '$_nombre_diario_tipo_cabeza', '$_id_estado'";
    				$core_diario_tipo_cabeza->setFuncion($funcion);
    				$core_diario_tipo_cabeza->setParametros($parametros);
    				$resultado=$core_diario_tipo_cabeza->Insert();
    					
				}
			 
			}
		
			     $this->redirect("CoreDiarioTipoCabeza", "index");

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
			if(isset($_GET["id_diario_tipo_cabeza"]))
			{
			    $id_diario_tipo_cabeza=(int)$_GET["id_diario_tipo_cabeza"];
			    $core_diario_tipo_cabeza=new CoreDiarioTipoCabezaModel();
				$core_diario_tipo_cabeza->deleteBy(" id_diario_tipo_cabeza",$id_diario_tipo_cabeza);
				
			}
			
			$this->redirect("CoreDiarioTipoCabeza", "index");
			
		}
		else
		{
		    $this->redirect("Usuarios","sesion_caducada");
		}
				
	}
	
	
}
?>