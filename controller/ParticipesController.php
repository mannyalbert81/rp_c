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
	
		$cuidades_participes=new CiudadesModel();
		$resultCuidades=$cuidades_participes->getAll("nombre_ciudades");
		
		$estado_participes=new EstadoParticipesModel();
		$resultEstado=$estado_participes->getAll("nombre_estado_participes");
		
		$estatus_participes=new EstatusModel();
		$resultEstatus=$estatus_participes->getAll("nombre_estatus");
		
		$genero_participes=new GeneroParticipesModel();
		$resultGenero=$genero_participes->getAll("nombre_genero_participes");
		
		$estado_civil_participes=new EstadoCivilParticipesModel();
		$resultEstadoCivil=$estado_civil_participes->getAll("nombre_estado_civill_participes");
	
		$entidad_patronal_participes=new EntidadPatronalParticipesModel();
		$resultEntidadPatronal=$entidad_patronal_participes->getAll("nombre_entidad_patronal");
		
		$tipo_instruccion_participes=new TipoInstruccionParticipesModel();
		$resultTipoInstrccion=$tipo_instruccion_participes->getAll("nombre_tipo_instruccion_participes");
	
		
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
                                      core_estado_civill_participes.id_estado_civill_participes, 
                                      core_estado_civill_participes.nombre_estado_civill_participes, 
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
						$tablas   =  "public.core_participes, 
                                      public.core_ciudades, 
                                      public.core_estado_participes, 
                                      public.core_estatus, 
                                      public.core_genero_participes, 
                                      public.core_estado_civill_participes, 
                                      public.core_entidad_patronal, 
                                      public.core_tipo_instruccion_participes";
						$where    =  "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_cuidades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civill_participes.id_estado_civill_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal
                                      AND core_participes.id_participes = '$_id_participes'"; 
						$id       = "grupos.id_grupos";
							
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
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultCuidades" =>$resultCuidades, "resultEstado" =>$resultEstado, "resultEstatus" =>$resultEstatus,
				    "resultGenero" =>$resultGenero, "resultEstadoCivil" =>$resultEstadoCivil, "resultEntidadPatronal" =>$resultEntidadPatronal, "resultTipoInstrccion" =>$resultTipoInstrccion,
			
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
		$resultPer = $participes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		//die("llego");
		
			$resultado = null;
			$participes=new ParticipesModel();
		
			if (isset ($_POST["cedula_participes"])   )
			{
			    
			    $_id_participes = $_POST["id_participes"];
			    $_id_cuidades =  $_POST["id_cuidades"];
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
			    
			    
			    
			    //die("llego");
			    if($_id_participes > 0){
					
					$columnas =    "id_participes = '$_id_participes',
                                    id_cuidades = '$_id_cuidades',
                                    apellido_participes = '$_apellido_participes',
                                    nombre_participes = '$_nombre_participes',
                                    cedula_participes = '$_cedula_participes',
                                    fecha_nacimiento_participes = '$_fecha_nacimiento_participes',
                                    direccion_participes = '$_direccion_participes',
                                    telefono_participes = '$_telefono_participes',
                                    celular_participes = '$_celular_participes',
                                    fecha_ingreso_participes = '$_fecha_ingreso_participes',
                                    fecha_defuncion_participes = '$_fecha_defuncion_participes',
                                    id_estado_participes = '$_id_estado_participes',
                                    id_estatus = '$_id_estatus',
                                    id_genero_participes = '$_id_genero_participes',
                                    fecha_salida_participes = '$_fecha_salida_participes',
                                    id_estado_civil_participes = '$_id_estado_civil_participes',
                                    observacion_participes = '$_observacion_participes',
                                    correo_participes = '$_correo_participes',
                                    id_entidad_patronal = '$_id_entidad_patronal',
                                    fecha_entrada_patronal_participes = '$_fecha_entrada_patronal_participes',
                                    ocupacion_participes = '$_ocupacion_participes',
                                    id_tipo_instruccion_participes = '$_id_tipo_instruccion_participes',
                                    nombre_conyugue_participes = '$_nombre_conyugue_participes',
                                    apellido_esposa_participes = '$_apellido_esposa_participes',
                                    cedula_conyugue_participes = '$_cedula_conyugue_participes',
                                    numero_dependencias_participes = '$_numero_dependencias_participes',
                                    codigo_alternativo_participes = '$_codigo_alternativo_participes',
                                    fecha_numero_orden_participes = '$_fecha_numero_orden_participes'";
					        $tabla = "public.core_participes, 
                                      public.core_ciudades, 
                                      public.core_estado_participes, 
                                      public.core_estatus, 
                                      public.core_genero_participes, 
                                      public.core_estado_civill_participes, 
                                      public.core_entidad_patronal, 
                                      public.core_tipo_instruccion_participes";
					$where = "core_participes.id_tipo_instruccion_participes = core_tipo_instruccion_participes.id_tipo_instruccion_participes AND
                                      core_ciudades.id_ciudades = core_participes.id_cuidades AND
                                      core_estado_participes.id_estado_participes = core_participes.id_estado_participes AND
                                      core_estatus.id_estatus = core_participes.id_estatus AND
                                      core_genero_participes.id_genero_participes = core_participes.id_genero_participes AND
                                      core_estado_civill_participes.id_estado_civill_participes = core_participes.id_estado_civil_participes AND
                                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal
                                      AND core_participes.id_participes = '$_id_participes'";
					$resultado=$participes->UpdateBy($columnas, $tabla, $where);
					
				}else{
				    $_id_participes = $_POST["id_participes"];
				    $_id_cuidades =  $_POST["id_cuidades"];
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
				    
				    
					$funcion = "ins_core_participes";
					$parametros = " '$_id_participes',
                                    '$_id_cuidades',
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
                                    '$_id_genero_participes',
                                    '$_fecha_salida_participes',
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
                                    '$_fecha_numero_orden_participes'";
					$participes->setFuncion($funcion);
					$participes->setParametros($parametros);
					$resultado=$participes->Insert();
				}
				
				
				
		
			}
			$this->redirect("Participes", "index");

		}
		else
		{
		    $this->view_Inventario("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Participes"
		
			));
		
		
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
	    $columnas = " core_participes.id_participes, 
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
                                      core_estado_civill_participes.id_estado_civill_participes, 
                                      core_estado_civill_participes.nombre_estado_civill_participes, 
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
                                      public.core_estado_civill_participes, 
                                      public.core_entidad_patronal, 
                                      public.core_tipo_instruccion_participes 
                    ";
	    
	    
	    $where    = " 1=1";
	    
	    $id       = "core_participes.id_participes";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        //estado_usuario
	        $whereestado = "tabla_estado='GRUPOS'";
	        $resultEstado = $estado->getCondiciones('nombre_estado' ,'public.estado' , $whereestado , 'tabla_estado');
	        
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (grupos.nombre_grupos LIKE '".$search."%' )";
	            
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
	            $html.= "<table id='tabla_grupos_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            
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
	                $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
	                
	             
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Grupos&action=index&id_grupos='.$res->id_grupos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Grupos&action=borrarId&id_grupos='.$res->id_grupos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_grupos_activos("index.php", $page, $total_pages, $adjacents).'';
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
	
	public function consulta_grupos_inactivos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    
	    $estado = null; $estado = new EstadoModel();
	    $where_to="";
	    $columnas = " grupos.id_grupos,
                      grupos.nombre_grupos,
                      estado.id_estado,
                      estado.nombre_estado,
                      estado.tabla_estado,
                      grupos.creado,
                      grupos.modificado";
	    
	    $tablas = "public.grupos INNER JOIN public.estado ON estado.id_estado=grupos.id_estado
                   AND estado.nombre_estado='INACTIVO' AND estado.tabla_estado ='GRUPOS'
                    ";
	    
	    
	    $where    = " 1=1";
	    
	    $id       = "grupos.id_grupos";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        //estado_usuario
	        $whereestado = "tabla_estado='GRUPOS'";
	        $resultEstado = $estado->getCondiciones('nombre_estado' ,'public.estado' , $whereestado , 'tabla_estado');
	        
	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (grupos.nombre_grupos LIKE '".$search."%' )";
	            
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
	            $html.= "<table id='tabla_grupos_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            
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
	                $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
	                
	                
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Grupos&action=index&id_grupos='.$res->id_grupos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Grupos&action=borrarId&id_grupos='.$res->id_grupos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_grupos_activos("index.php", $page, $total_pages, $adjacents).'';
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
	
	
	
	
	public function paginate_grupos_activos($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_activos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_activos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_activos(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_activos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_activos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_activos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_activos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	
	
	
	
	public function paginate_grupos_inactivos($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_inactivos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_inactivos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_inactivos(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_inactivos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_inactivos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_grupos_inactivos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_grupos_inactivos(".($page+1).")'>$nextlabel</a></span></li>";
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