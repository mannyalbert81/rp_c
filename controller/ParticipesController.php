<?php

class ParticipesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	$participes=new ParticipesModel();
		$resultSet=$participes->getAll("id_participes");
		
		$resultEdit = "";
	
		$ciudades_participes=new CiudadesModel();
		$resultCiudades=$ciudades_participes->getAll("nombre_ciudades");
		
		$estado_participes=new EstadoParticipesModel();
		$resultEstado=$estado_participes->getAll("nombre_estado_participes");
		
		$estatus_participes=new EstatusModel();
		$resultEstatus=$estatus_participes->getAll("nombre_estatus");
		
		$genero_participes=new GeneroParticipesModel();
		$resultGenero=$genero_participes->getAll("nombre_genero_participes");
		
		$estado_civil_participes=new EstadoCivilParticipesModel();
		$resultEstadoCivil=$estado_civil_participes->getAll("nombre_estado_civil_participes");
	
		$entidad_patronal_participes=new EntidadPatronalParticipesModel();
		$resultEntidadPatronal=$entidad_patronal_participes->getAll("nombre_entidad_patronal");
		
		$tipo_instruccion_participes=new TipoInstruccionParticipesModel();
		$resultTipoInstrccion=$tipo_instruccion_participes->getAll("nombre_tipo_instruccion_participes");
		
		$distritos=new DistritosModel();
		$resultDistritos=$distritos->getAll("nombre_distritos");
		
		$provincias=new ProvinciasModel();
		$resultProvincias=$provincias->getAll("nombre_provincias");
		
		$tipo_vivienda=new TipoViviendaModel();
		$resultTipovivienda=$tipo_vivienda->getAll("nombre_tipo_vivienda");
		
		$parentesco=new ParentescoModel();
		$resultParentesco=$parentesco->getAll("nombre_parentesco");
	
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "Participes";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $participes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_participes"])   )
				{

					$nombre_controladores = "Participes";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $participes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_participes = $_GET["id_participes"];
						$columnas = " core_participes.id_participes, 
                                      core_participes.id_ciudades, 
                                      core_ciudades.nombre_ciudades, 
                                      core_participes.apellido_participes, 
                                      core_participes.nombre_participes, 
                                      core_participes.cedula_participes, 
                                      DATE (core_participes.fecha_nacimiento_participes) AS fecha_nacimiento_participes, 
                                      core_participes.telefono_participes, 
                                      core_participes.direccion_participes, 
                                      core_participes.celular_participes, 
                                      DATE (core_participes.fecha_ingreso_participes) AS fecha_ingreso_participes, 
                                      DATE (core_participes.fecha_defuncion_participes) AS fecha_defuncion_participes, 
                                      core_participes.id_estado_participes, 
                                      core_estado_participes.nombre_estado_participes, 
                                      core_participes.id_estatus, 
                                      core_estatus.nombre_estatus, 
                                      DATE (core_participes.fecha_salida_participes) AS fecha_salida_participes, 
                                      core_participes.id_genero_participes, 
                                      core_genero_participes.nombre_genero_participes, 
                                      core_participes.id_estado_civil_participes, 
                                      core_estado_civil_participes.nombre_estado_civil_participes, 
                                      core_participes.observacion_participes, 
                                      core_participes.correo_participes, 
                                      core_participes.id_entidad_patronal, 
                                      core_entidad_patronal.nombre_entidad_patronal, 
                                      DATE(core_participes.fecha_entrada_patronal_participes) AS fecha_entrada_patronal_participes, 
                                      core_participes.ocupacion_participes, 
                                      core_participes.id_tipo_instruccion_participes, 
                                      core_tipo_instruccion_participes.nombre_tipo_instruccion_participes, 
                                      core_participes.nombre_conyugue_participes, 
                                      core_participes.apellido_esposa_participes, 
                                      core_participes.cedula_conyugue_participes, 
                                      core_participes.numero_dependencias_participes, 
                                      core_participes.codigo_alternativo_participes, 
                                      DATE (core_participes.fecha_numero_orden_participes) AS fecha_numero_orden_participes, 
                                      core_participes_informacion_adicional.id_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.id_distritos, 
                                      core_distritos.nombre_distritos, 
                                      core_participes_informacion_adicional.id_provincias, 
                                      core_provincias.nombre_provincias, 
                                      core_participes_informacion_adicional.parroquia_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.sector_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.ciudadela_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.calle_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.numero_calle_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.interseccion_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.id_tipo_vivienda, 
                                      core_tipo_vivienda.nombre_tipo_vivienda, 
                                      core_participes_informacion_adicional.anios_residencia_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.nombre_propietario_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.telefono_propietario_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.direccion_referencia_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.vivienda_hipotecada_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.nombre_una_referencia_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.id_parentesco, 
                                      core_parentesco.nombre_parentesco, 
                                      core_participes_informacion_adicional.telefono_una_referencia_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.observaciones_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.kit_participes_informacion_adicional, 
                                      core_participes_informacion_adicional.contrato_adhesion_participes_informacion_adicional";
						$tablas   =  "public.core_participes, 
                                      public.core_participes_informacion_adicional, 
                                      public.core_ciudades, 
                                      public.core_distritos, 
                                      public.core_estado_participes, 
                                      public.core_estatus, 
                                      public.core_genero_participes, 
                                      public.core_estado_civil_participes, 
                                      public.core_entidad_patronal, 
                                      public.core_tipo_instruccion_participes, 
                                      public.core_provincias, 
                                      public.core_tipo_vivienda, 
                                      public.core_parentesco";
						$where    =  "core_participes.id_participes = core_participes_informacion_adicional.id_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_ciudades AND
                                      core_distritos.id_distritos = core_participes_informacion_adicional.id_distritos AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
                                      core_tipo_instruccion_participes.id_tipo_instruccion_participes = core_participes.id_tipo_instruccion_participes AND
                                      core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
                                      core_tipo_vivienda.id_tipo_vivienda = core_participes_informacion_adicional.id_tipo_vivienda AND
                                      core_parentesco.id_parentesco = core_participes_informacion_adicional.id_parentesco
                                      AND core_participes.id_participes = '$_id_participes'"; 
						$id       = "core_participes.id_participes";
							
						$resultEdit = $participes->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
					    $this->view_Core("Error",array(
								"resultado"=>"No tiene Permisos de Editar Participes"
					
						));
					
					
					}
					
				}
		
				
				$this->view_Core("Participes",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultCiudades" =>$resultCiudades, "resultEstado" =>$resultEstado, "resultEstatus" =>$resultEstatus,
				    "resultGenero" =>$resultGenero, "resultEstadoCivil" =>$resultEstadoCivil, "resultEntidadPatronal" =>$resultEntidadPatronal, "resultTipoInstrccion" =>$resultTipoInstrccion, 
				    "resultDistritos" =>$resultDistritos, "resultProvincias" =>$resultProvincias, "resultTipovivienda" => $resultTipovivienda, "resultParentesco" => $resultParentesco
			
				));
		
				
				
			}
			else
			{
			    $this->view_Core("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Participes"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaParticipes(){
			
		session_start();
		$participes=new ParticipesModel();
		$nombre_controladores = "Participes";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $participes->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
			$resultado = null;
			$participes=new ParticipesModel();
		
			if (isset ($_POST["cedula_participes"])   )
			{
			    
			    $_id_participes = $_POST["id_participes"];
			    $_id_ciudades =  $_POST["id_ciudades"];
			    $_apellido_participes = $_POST["apellido_participes"];
			    $_nombre_participes = $_POST["nombre_participes"];
			    $_cedula_participes = $_POST["cedula_participes"];
			    $_fecha_nacimiento_participes = $_POST["fecha_nacimiento_participes"];
			    $_direccion_participes = $_POST["direccion_participes"];
			    $_telefono_participes = $_POST["telefono_participes"];
			    $_celular_participes = $_POST["celular_participes"];
			    $_fecha_ingreso_participes = $_POST["fecha_ingreso_participes"];
			    $_fecha_defuncion_participes = $_POST["fecha_defuncion_participes"];
			    $_id_estado_participes = $_POST["id_estado_participes"];
			    $_id_estatus = $_POST["id_estatus"];
			    $_id_genero_participes = $_POST["id_genero_participes"];
			    $_fecha_salida_participes = $_POST["fecha_salida_participes"];
			    $_id_estado_civil_participes = $_POST["id_estado_civil_participes"];
			    $_observacion_participes = $_POST["observacion_participes"];
			    $_correo_participes = $_POST["correo_participes"];
			    $_id_entidad_patronal = $_POST["id_entidad_patronal"];
			    $_fecha_entrada_patronal_participes = $_POST["fecha_entrada_patronal_participes"];
			    $_ocupacion_participes = $_POST["ocupacion_participes"];
			    $_id_tipo_instruccion_participes = $_POST["id_tipo_instruccion_participes"];
			    $_nombre_conyugue_participes = $_POST["nombre_conyugue_participes"];
			    $_apellido_esposa_participes = $_POST["apellido_esposa_participes"];
			    $_cedula_conyugue_participes = $_POST["cedula_conyugue_participes"];
			    $_numero_dependencias_participes = $_POST["numero_dependencias_participes"];
			    $_codigo_alternativo_participes = $_POST["codigo_alternativo_participes"];
			    $_fecha_numero_orden_participes = $_POST["fecha_numero_orden_participes"];
			    $_id_distritos = $_POST["id_distritos"];
			    $_id_provincia = $_POST["id_provincia"];
			    $_parroquia_participes_informacion_adicional = $_POST["parroquia_participes_informacion_adicional"];
			    $_sector_participes_informacion_adicional = $_POST["sector_participes_informacion_adicional"];
			    $_ciudadela_participes_informacion_adicional = $_POST["ciudadela_participes_informacion_adicional"];
			    $_calle_participes_informacion_adicional = $_POST["calle_participes_informacion_adicional"];
			    $_numero_calle_participes_informacion_adicional = $_POST["numero_calle_participes_informacion_adicional"];
			    $_interseccion_participes_informacion_adicional = $_POST["interseccion_participes_informacion_adicional"];
			    $_id_tipo_vivienda = $_POST["id_tipo_vivienda"];
			    $_anios_residencia_participes_informacion_adicional = $_POST["anios_residencia_participes_informacion_adicional"];
			    $_nombre_propietario_participes_informacion_adicional = $_POST["nombre_propietario_participes_informacion_adicional"];
			    $_telefono_propietario_participes_informacion_adicional = $_POST["telefono_propietario_participes_informacion_adicional"];
			    $_direccion_referencia_participes_informacion_adicional = $_POST["direccion_referencia_participes_informacion_adicional"];
			    $_vivienda_hipotecada_participes_informacion_adicional = $_POST["vivienda_hipotecada_participes_informacion_adicional"];
			    $_nombre_una_referencia_participes_informacion_adicional = $_POST["nombre_una_referencia_participes_informacion_adicional"];
			    $_id_parentesco = $_POST["id_parentesco"];
			    $_telefono_una_referencia_participes_informacion_adicional = $_POST["telefono_una_referencia_participes_informacion_adicional"];
			    $_observaciones_participes_informacion_adicional = $_POST["observaciones_participes_informacion_adicional"];
			    $_kit_participes_informacion_adicional = $_POST["kit_participes_informacion_adicional"];
			    $_contrato_adhesion_participes_informacion_adicional = $_POST["contrato_adhesion_participes_informacion_adicional"];
			    
			    //print_r($_POST); die();
			
				    
					$funcion = "ins_core_participes";
					
					$parametros = " '$_id_ciudades',
                                    '$_apellido_participes',
                                    '$_nombre_participes',
                                    '$_cedula_participes',
                                    '$_fecha_nacimiento_participes',
                                    '$_direccion_participes',
                                    '$_telefono_participes',
                                    '$_celular_participes',
                                    '$_fecha_ingreso_participes',
                                    '$_fecha_defuncion_participes',
                                    '$_id_estado_participes',
                                    '$_id_estatus',
                                    '$_fecha_salida_participes',                                   
                                    '$_id_genero_participes',
                                    '$_id_estado_civil_participes',
                                    '$_observacion_participes',
                                    '$_correo_participes',
                                    '$_id_entidad_patronal',
                                    '$_fecha_entrada_patronal_participes',
                                    '$_ocupacion_participes',
                                    '$_id_tipo_instruccion_participes',
                                    '$_nombre_conyugue_participes',
                                    '$_apellido_esposa_participes',
                                    '$_cedula_conyugue_participes',
                                    '$_numero_dependencias_participes',
                                    '$_codigo_alternativo_participes',
                                    '$_fecha_numero_orden_participes',
			                        '$_id_distritos',
                					'$_id_provincia',
                					'$_parroquia_participes_informacion_adicional',
                					'$_sector_participes_informacion_adicional',
                					'$_ciudadela_participes_informacion_adicional',
                					'$_calle_participes_informacion_adicional',
                					'$_numero_calle_participes_informacion_adicional',
                					'$_interseccion_participes_informacion_adicional',
                					'$_id_tipo_vivienda',
                					'$_anios_residencia_participes_informacion_adicional',
                					'$_nombre_propietario_participes_informacion_adicional',
                					'$_telefono_propietario_participes_informacion_adicional',
                					'$_direccion_referencia_participes_informacion_adicional',
                					'$_vivienda_hipotecada_participes_informacion_adicional',
                					'$_nombre_una_referencia_participes_informacion_adicional',
                					'$_id_parentesco',
                					'$_telefono_una_referencia_participes_informacion_adicional',
                					'$_observaciones_participes_informacion_adicional',
                					'$_kit_participes_informacion_adicional',
                					'$_contrato_adhesion_participes_informacion_adicional',
                                    '$_id_participes'";
					
					$participes->setFuncion($funcion);
					$participes->setParametros($parametros);
					$resultado=$participes->llamafuncionPG();
					
					$error = pg_last_error();
					if(!empty($error)){
					    
					    echo json_encode(array("respuesta"=>1,"mensaje"=>"lo que sea"));
					    die();
					}
				    
					$mensaje = $resultado[0] == 1 ? "INGRESADO" :  ($resultado[0] == 0 ? "ACTUALIZADO" : "ERROR");
					echo json_encode(array("respuesta"=>1,"mensaje"=>$mensaje));
					die();
							
		
			}
			echo 'redireccion';
		

		}
		else
		{
		    echo 'no tiene permisos';
		  
		
		}
		
	}
	
	public function InsertaCuentas(){
	    
	    session_start();
	    $participes=new ParticipesModel();
	    $nombre_controladores = "Participes";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $participes->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        
	        $resultado = null;
	        $participes=new ParticipesModel();
	        
	        if (isset ($_POST["cedula_participes"])   )
	        {
	            
	            

	            $_id_participes = $_POST["id_participes"];
	            $_id_bancos = $_POST["id_bancos"];
	            $_numero_participes_cuentas = $_POST["numero_participes_cuentas"];
	            $_id_tipo_cuentas = $_POST["id_tipo_cuentas"];
	            $_cuenta_principal = $_POST["cuenta_principal"];
	           
	            
	            if($_id_participes > 0){
	                
	                $columnas =    "id_ciudades = '$_id_bancos',
                                    apellido_participes = '$_numero_participes_cuentas',
                                    nombre_participes = '$_id_tipo_cuentas',
                                    cedula_participes = '$_cuenta_principal'";
	                
	                $tabla = "  public.core_participes_cuentas, 
                                  public.core_participes, 
                                  public.tes_bancos, 
                                  public.core_tipo_cuentas";
	                
	                $where = "core_participes.id_participes = core_participes_cuentas.id_participes AND
                              tes_bancos.id_bancos = core_participes_cuentas.id_bancos AND
                              core_tipo_cuentas.id_tipo_cuentas = core_participes_cuentas.id_tipo_cuentas
                              AND core_participes.id_participes = '$_id_participes'";
	                
	                $resultado=$participes->UpdateBy($columnas, $tabla, $where);
	                
	            }else{
	                
	                $funcion = "ins_core_participes_cuentas";
	                
	                $parametros = " '$_id_bancos',
                                    '$_numero_participes_cuentas',
                                    '$_id_tipo_cuentas',
                                    '$_cuenta_principal'";
	                
	                $participes->setFuncion($funcion);
	                $participes->setParametros($parametros);
	                $resultado=$participes->Insert();
	                
	                $error = pg_last_error();
	                if(empty($error)){
	                    
	                    echo json_encode(array("respuesta"=>1,"mensaje"=>"lo que sea"));
	                    die();
	                }
	                
	            }
	            
	            
	        }
	        echo 'redireccion';
	        
	        
	    }
	    else
	    {
	        echo 'no tiene permisos';
	        
	        
	    }
	    
	}
	
	
	
	public function borrarId()
	{
	    
	    session_start();
	    $participes=new ParticipesModel();
	    $nombre_controladores = "Participes";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $participes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        if(isset($_GET["id_participes"]))
	        {
	            $id_participes=(int)$_GET["id_participes"];
	            
	            
	            
	            $participes->deleteBy("id_participes",$id_participes);
	            
	        }
	        
	        $this->redirect("Participes", "index");
	        
	        
	    }
	    else
	    {
	        $this->view_Inventario("Error",array(
	            "resultado"=>"No tiene Permisos de Borrar Participes"
	            
	        ));
	    }
	    
	}
	
	public function consulta_participes_activos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	   
	    $estado_participes = null; $estado_participes = new EstadoParticipesModel();
	    $where_to="";
	    $columnas = "core_participes.id_participes, 
                                      core_ciudades.id_ciudades, 
                                      core_ciudades.nombre_ciudades, 
                                      core_participes.apellido_participes, 
                                      core_participes.nombre_participes, 
                                      core_participes.cedula_participes, 
                                      core_participes.fecha_nacimiento_participes, 
                                      core_participes.direccion_participes, 
                                      core_participes.telefono_participes, 
                                      core_participes.celular_participes, 
                                      core_participes.fecha_ingreso_participes, 
                                      core_participes.fecha_defuncion_participes, 
                                      core_estado_participes.id_estado_participes, 
                                      core_estado_participes.nombre_estado_participes, 
                                      core_estatus.id_estatus, 
                                      core_estatus.nombre_estatus, 
                                      core_participes.fecha_salida_participes, 
                                      core_genero_participes.id_genero_participes, 
                                      core_genero_participes.nombre_genero_participes, 
                                      core_estado_civil_participes.id_estado_civil_participes, 
                                      core_estado_civil_participes.nombre_estado_civil_participes, 
                                      core_participes.observacion_participes, 
                                      core_participes.correo_participes, 
                                      core_entidad_patronal.id_entidad_patronal, 
                                      core_entidad_patronal.nombre_entidad_patronal, 
                                      core_participes.fecha_entrada_patronal_participes, 
                                      core_participes.ocupacion_participes, 
                                      core_tipo_instruccion_participes.id_tipo_instruccion_participes, 
                                      core_tipo_instruccion_participes.nombre_tipo_instruccion_participes, 
                                      core_participes.nombre_conyugue_participes, 
                                      core_participes.apellido_esposa_participes, 
                                      core_participes.cedula_conyugue_participes, 
                                      core_participes.numero_dependencias_participes, 
                                      core_participes.codigo_alternativo_participes, 
                                      core_participes.fecha_numero_orden_participes, 
                                      core_participes.creado, 
                                      core_participes.modificado";
	    
	    $tablas = "public.core_participes, 
                                      public.core_ciudades, 
                                      public.core_estado_participes, 
                                      public.core_estatus, 
                                      public.core_genero_participes, 
                                      public.core_estado_civil_participes, 
                                      public.core_entidad_patronal, 
                                      public.core_tipo_instruccion_participes";
	    
	    
	    $where    = "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_ciudades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_estado_participes.nombre_estado_participes = 'Activo'";
	    
	    $id       = "core_participes.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	  
	        $whereestado = "nombre_estado_participes = 'Activo'";
	        $resultEstado = $estado_participes->getCondiciones('nombre_estado_participes' ,'public.core_estado_participes' , $whereestado , 'nombre_estado_participes');
	        
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_participes.nombre_participes LIKE '".$search."%' OR core_participes.cedula_participes LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; 
	        $adjacents  = 9; 
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_participes_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nacimiento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Direeción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Telefono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Defunción </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estatus</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Genero</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Civil</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Observación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Entrada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ocupación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Instrucción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Dependencias</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Orden</th>';
	            
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_ciudades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_nacimiento_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->direccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_defuncion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estatus.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_salida_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_genero_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_civil_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->observacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_entrada_patronal_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->ocupacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_instruccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_esposa_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_dependencias_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_alternativo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_numero_orden_participes.'</td>';
	                
	                
	                
	                
	             
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=index&id_participes='.$res->id_participes.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=borrarId&id_participes='.$res->id_participes.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_participes_activos("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	}
	
	public function consulta_participes_inactivos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    
	    $estado_participes = null; $estado_participes = new EstadoParticipesModel();
	    $where_to="";
	    $columnas = "core_participes.id_participes,
                                      core_ciudades.id_ciudades,
                                      core_ciudades.nombre_ciudades,
                                      core_participes.apellido_participes,
                                      core_participes.nombre_participes,
                                      core_participes.cedula_participes,
                                      core_participes.fecha_nacimiento_participes,
                                      core_participes.direccion_participes,
                                      core_participes.telefono_participes,
                                      core_participes.celular_participes,
                                      core_participes.fecha_ingreso_participes,
                                      core_participes.fecha_defuncion_participes,
                                      core_estado_participes.id_estado_participes,
                                      core_estado_participes.nombre_estado_participes,
                                      core_estatus.id_estatus,
                                      core_estatus.nombre_estatus,
                                      core_participes.fecha_salida_participes,
                                      core_genero_participes.id_genero_participes,
                                      core_genero_participes.nombre_genero_participes,
                                      core_estado_civil_participes.id_estado_civil_participes,
                                      core_estado_civil_participes.nombre_estado_civil_participes,
                                      core_participes.observacion_participes,
                                      core_participes.correo_participes,
                                      core_entidad_patronal.id_entidad_patronal,
                                      core_entidad_patronal.nombre_entidad_patronal,
                                      core_participes.fecha_entrada_patronal_participes,
                                      core_participes.ocupacion_participes,
                                      core_tipo_instruccion_participes.id_tipo_instruccion_participes,
                                      core_tipo_instruccion_participes.nombre_tipo_instruccion_participes,
                                      core_participes.nombre_conyugue_participes,
                                      core_participes.apellido_esposa_participes,
                                      core_participes.cedula_conyugue_participes,
                                      core_participes.numero_dependencias_participes,
                                      core_participes.codigo_alternativo_participes,
                                      core_participes.fecha_numero_orden_participes,
                                      core_participes.creado,
                                      core_participes.modificado";
	    
	    $tablas = "public.core_participes,
                                      public.core_ciudades,
                                      public.core_estado_participes,
                                      public.core_estatus,
                                      public.core_genero_participes,
                                      public.core_estado_civil_participes,
                                      public.core_entidad_patronal,
                                      public.core_tipo_instruccion_participes";
	    
	    
	    $where    = "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_ciudades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_estado_participes.nombre_estado_participes = 'Inactivo'";
	    
	    $id       = "core_participes.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	    
	        $whereestado = "nombre_estado_participes = 'Inactivo'";
	        $resultEstado = $estado_participes->getCondiciones('nombre_estado_participes' ,'public.core_estado_participes' , $whereestado , 'nombre_estado_participes');
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_participes.nombre_participes LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_participes_inactivos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nacimiento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Direeción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Telefono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Defunción </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estatus</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Genero</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Civil</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Observación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Entrada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ocupación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Instrucción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Dependencias</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Orden</th>';
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_ciudades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_nacimiento_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->direccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_defuncion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estatus.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_salida_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_genero_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_civil_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->observacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_entrada_patronal_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->ocupacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_instruccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_esposa_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_dependencias_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_alternativo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_numero_orden_participes.'</td>';
	                
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=index&id_participes='.$res->id_participes.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=borrarId&id_participes='.$res->id_participes.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_participes_inactivos("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	}
	
	public function consulta_participes_desafiliado(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    
	    $estado_participes = null; $estado_participes = new EstadoParticipesModel();
	    $where_to="";
	    $columnas = "core_participes.id_participes,
                                      core_ciudades.id_ciudades,
                                      core_ciudades.nombre_ciudades,
                                      core_participes.apellido_participes,
                                      core_participes.nombre_participes,
                                      core_participes.cedula_participes,
                                      core_participes.fecha_nacimiento_participes,
                                      core_participes.direccion_participes,
                                      core_participes.telefono_participes,
                                      core_participes.celular_participes,
                                      core_participes.fecha_ingreso_participes,
                                      core_participes.fecha_defuncion_participes,
                                      core_estado_participes.id_estado_participes,
                                      core_estado_participes.nombre_estado_participes,
                                      core_estatus.id_estatus,
                                      core_estatus.nombre_estatus,
                                      core_participes.fecha_salida_participes,
                                      core_genero_participes.id_genero_participes,
                                      core_genero_participes.nombre_genero_participes,
                                      core_estado_civil_participes.id_estado_civil_participes,
                                      core_estado_civil_participes.nombre_estado_civil_participes,
                                      core_participes.observacion_participes,
                                      core_participes.correo_participes,
                                      core_entidad_patronal.id_entidad_patronal,
                                      core_entidad_patronal.nombre_entidad_patronal,
                                      core_participes.fecha_entrada_patronal_participes,
                                      core_participes.ocupacion_participes,
                                      core_tipo_instruccion_participes.id_tipo_instruccion_participes,
                                      core_tipo_instruccion_participes.nombre_tipo_instruccion_participes,
                                      core_participes.nombre_conyugue_participes,
                                      core_participes.apellido_esposa_participes,
                                      core_participes.cedula_conyugue_participes,
                                      core_participes.numero_dependencias_participes,
                                      core_participes.codigo_alternativo_participes,
                                      core_participes.fecha_numero_orden_participes,
                                      core_participes.creado,
                                      core_participes.modificado";
	    
	    $tablas = "public.core_participes,
                                      public.core_ciudades,
                                      public.core_estado_participes,
                                      public.core_estatus,
                                      public.core_genero_participes,
                                      public.core_estado_civil_participes,
                                      public.core_entidad_patronal,
                                      public.core_tipo_instruccion_participes";
	    
	    
	    $where    = "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_ciudades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_estado_participes.nombre_estado_participes = 'Desafiliado'";
	    
	    $id       = "core_participes.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        $whereestado = "nombre_estado_participes = 'Desafiliado'";
	        $resultEstado = $estado_participes->getCondiciones('nombre_estado_participes' ,'public.core_estado_participes' , $whereestado , 'nombre_estado_participes');
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_participes.nombre_participes LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_participes_desafiliado' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nacimiento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Direeción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Telefono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Defunción </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estatus</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Genero</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Civil</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Observación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Entrada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ocupación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Instrucción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Dependencias</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Orden</th>';
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_ciudades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_nacimiento_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->direccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_defuncion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estatus.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_salida_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_genero_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_civil_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->observacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_entrada_patronal_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->ocupacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_instruccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_esposa_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_dependencias_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_alternativo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_numero_orden_participes.'</td>';
	                
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=index&id_participes='.$res->id_participes.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=borrarId&id_participes='.$res->id_participes.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_participes_desafiliado("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	}
	
	public function consulta_participes_liquidado_cesante(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    
	    $estado_participes = null; $estado_participes = new EstadoParticipesModel();
	    $where_to="";
	    $columnas = "core_participes.id_participes,
                                      core_ciudades.id_ciudades,
                                      core_ciudades.nombre_ciudades,
                                      core_participes.apellido_participes,
                                      core_participes.nombre_participes,
                                      core_participes.cedula_participes,
                                      core_participes.fecha_nacimiento_participes,
                                      core_participes.direccion_participes,
                                      core_participes.telefono_participes,
                                      core_participes.celular_participes,
                                      core_participes.fecha_ingreso_participes,
                                      core_participes.fecha_defuncion_participes,
                                      core_estado_participes.id_estado_participes,
                                      core_estado_participes.nombre_estado_participes,
                                      core_estatus.id_estatus,
                                      core_estatus.nombre_estatus,
                                      core_participes.fecha_salida_participes,
                                      core_genero_participes.id_genero_participes,
                                      core_genero_participes.nombre_genero_participes,
                                      core_estado_civil_participes.id_estado_civil_participes,
                                      core_estado_civil_participes.nombre_estado_civil_participes,
                                      core_participes.observacion_participes,
                                      core_participes.correo_participes,
                                      core_entidad_patronal.id_entidad_patronal,
                                      core_entidad_patronal.nombre_entidad_patronal,
                                      core_participes.fecha_entrada_patronal_participes,
                                      core_participes.ocupacion_participes,
                                      core_tipo_instruccion_participes.id_tipo_instruccion_participes,
                                      core_tipo_instruccion_participes.nombre_tipo_instruccion_participes,
                                      core_participes.nombre_conyugue_participes,
                                      core_participes.apellido_esposa_participes,
                                      core_participes.cedula_conyugue_participes,
                                      core_participes.numero_dependencias_participes,
                                      core_participes.codigo_alternativo_participes,
                                      core_participes.fecha_numero_orden_participes,
                                      core_participes.creado,
                                      core_participes.modificado";
	    
	    $tablas = "public.core_participes,
                                      public.core_ciudades,
                                      public.core_estado_participes,
                                      public.core_estatus,
                                      public.core_genero_participes,
                                      public.core_estado_civil_participes,
                                      public.core_entidad_patronal,
                                      public.core_tipo_instruccion_participes";
	    
	    
	    $where    = "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_ciudades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_estado_participes.nombre_estado_participes = 'Liquidado Cesante'";
	    
	    $id       = "core_participes.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        $whereestado = "nombre_estado_participes = 'Liquidado Cesante'";
	        $resultEstado = $estado_participes->getCondiciones('nombre_estado_participes' ,'public.core_estado_participes' , $whereestado , 'nombre_estado_participes');
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (core_participes.nombre_participes LIKE '".$search."%' )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_participes_liquidado_cesante' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cuidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nacimiento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Direeción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Telefono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ingreso</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Defunción </th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estatus</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Genero</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Civil</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Observación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Entrada</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Ocupación</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Instrucción</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Conyugue</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Dependencias</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Orden</th>';
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_ciudades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_nacimiento_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->direccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_defuncion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estatus.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_salida_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_genero_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado_civil_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->observacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_entrada_patronal_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->ocupacion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_instruccion_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellido_esposa_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_conyugue_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_dependencias_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_alternativo_participes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_numero_orden_participes.'</td>';
	                
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=index&id_participes='.$res->id_participes.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Participes&action=borrarId&id_participes='.$res->id_participes.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_participes_liquidado_cesante("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	}
	
	
	
	
	public function paginate_participes_activos($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_activos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_activos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_activos(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_activos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_activos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_activos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_activos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	public function paginate_participes_inactivos($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_inactivos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_inactivos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_inactivos(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_inactivos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_inactivos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_inactivos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_inactivos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	public function paginate_participes_desafiliado($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_desafiliado(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_desafiliado(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_desafiliado(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_desafiliado(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_desafiliado(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_desafiliado($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_desafiliado(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	public function paginate_participes_liquidado_cesante($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_participes_liquidado_cesante(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	/**
	 * mod: compras
	 * title: carga_grupos
	 * ajax: si
	 */
	
	public function carga_grupos(){
	    
	    $grupos = null;
	    $grupos = new GruposModel();
	    
	    $resulset = $grupos->getAll("id_grupos");
	    
	    if(!empty($resulset)){
	        if(is_array($resulset) && count($resulset)>0){
	            echo json_encode($resulset);
	        }
	    }
	}
	
	
	
	/**
	 * mod: compras
	 * title: carga_unidadmedida
	 * ajax: si
	 */
	
	public function carga_unidadmedida(){
	    
	    $grupos = null;
	    $grupos = new GruposModel();
	    
	    $resulset = $grupos->getCondiciones("*","public.unidad_medida","1=1","id_unidad_medida");
	    
	    if(!empty($resulset)){
	        if(is_array($resulset) && count($resulset)>0){
	            echo json_encode($resulset);
	        }
	    }
	}
	
}
?>