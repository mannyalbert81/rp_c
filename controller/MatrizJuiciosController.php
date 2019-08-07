	<?php

    class MatrizJuiciosController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index(){
	
		    session_start();
			
		    if (isset(  $_SESSION['usuario_usuarios']) )
		    {
		    
		    $id_rol= $_SESSION['id_rol'];
		    
		    if ($id_rol==3){
		    
		    $_id_usuarios= $_SESSION['id_usuarios'];
			$resultSet="";
			$registrosTotales = 0;
			$arraySel = "";
			
			$juicios = new JuiciosModel();
			
			$ciudad = new CiudadModel();
			$columnas = " usuarios.id_ciudad,
					  ciudad.nombre_ciudad,
					  usuarios.nombre_usuarios";
				
			$tablas   = "public.usuarios,
                     public.ciudad";
				
			$where    = "ciudad.id_ciudad = usuarios.id_ciudad AND usuarios.id_usuarios = '$_id_usuarios'";
			$id       = "usuarios.id_ciudad";
			$resultDatos=$ciudad->getCondiciones($columnas ,$tablas ,$where, $id);
			
			$provincias = new ProvinciasModel();
			$resultProv =$provincias->getAll("nombre_provincias");
			
			$estado_procesal = new EstadosProcesalesModel();
			$resultEstadoProcesal =$estado_procesal->getAll("nombre_estados_procesales_juicios");
			
			
				$permisos_rol = new PermisosRolesModel();
				$nombre_controladores = "MatrizJuicios";
				$id_rol= $_SESSION['id_rol'];
				$resultPer = $juicios->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
				if (!empty($resultPer))
				{
						
					if(isset($_POST["juicio_referido_titulo_credito"]))
					{
			
						$juicio_referido_titulo_credito=$_POST['juicio_referido_titulo_credito'];
						$numero_titulo_credito=$_POST['numero_titulo_credito'];
						
						$id_provincias=$_POST['id_provincias'];
						$id_estados_procesales_juicios=$_POST['id_estados_procesales_juicios'];
						
						$identificacion_clientes=$_POST['identificacion_clientes'];
						$identificacion_clientes_1=$_POST['identificacion_clientes_1'];
						$identificacion_clientes_2=$_POST['identificacion_clientes_2'];
						$identificacion_clientes_3=$_POST['identificacion_clientes_3'];
						
						
						$identificacion_garantes=$_POST['identificacion_garantes'];
						$identificacion_garantes_1=$_POST['identificacion_garantes_1'];
						$identificacion_garantes_2=$_POST['identificacion_garantes_2'];
						$identificacion_garantes_3=$_POST['identificacion_garantes_3'];
						
						$columnas = " juicios.id_juicios,
								  juicios.orden,
								  juicios.regional,
								  juicios.juicio_referido_titulo_credito,
								  juicios.year_juicios,
								  clientes.id_clientes,
								  clientes.identificacion_clientes,
								  clientes.nombres_clientes,
								  clientes.nombre_garantes,
								  clientes.identificacion_garantes,
								clientes.identificacion_clientes_1,
								clientes.nombre_clientes_1,
								clientes.identificacion_clientes_2,
								clientes.nombre_clientes_2,
								clientes.identificacion_clientes_3,
								clientes.nombre_clientes_3,
								clientes.identificacion_garantes_1,
								clientes.nombre_garantes_1,
								clientes.identificacion_garantes_2,
								clientes.nombre_garantes_2,
								clientes.identificacion_garantes_3,
								clientes.nombre_garantes_3,
								clientes.correo_clientes,
								clientes.correo_clientes_1,
								clientes.correo_clientes_2,
								clientes.correo_clientes_3,
								clientes.direccion_clientes,
								clientes.direccion_clientes_1,
								clientes.direccion_clientes_2,
								clientes.direccion_clientes_3,
								 clientes.cantidad_clientes,
								  clientes.cantidad_garantes,
								  clientes.sexo_clientes,
								  clientes.sexo_clientes_1,
								  clientes.sexo_clientes_2,
								  clientes.sexo_clientes_3,
								  clientes.sexo_garantes,
								  clientes.sexo_garantes_1,
								  clientes.sexo_garantes_2,
								  clientes.sexo_garantes_3,
								  provincias.id_provincias,
								  provincias.nombre_provincias,
								  titulo_credito.id_titulo_credito,
								  titulo_credito.numero_titulo_credito,
								  juicios.fecha_emision_juicios,
								  juicios.cuantia_inicial,
								  juicios.riesgo_actual,
								  estados_procesales_juicios.id_estados_procesales_juicios,
								  estados_procesales_juicios.nombre_estados_procesales_juicios,
								  juicios.descripcion_estado_procesal,
								  juicios.fecha_ultima_providencia,
								  juicios.estrategia_seguir,
								  juicios.observaciones,
								  asignacion_secretarios_view.id_abogado,
								  asignacion_secretarios_view.impulsores,
								  asignacion_secretarios_view.id_secretario,
								  asignacion_secretarios_view.secretarios,
								  ciudad.id_ciudad,
								  ciudad.nombre_ciudad,
								clientes.correo_garantes_1, 
								  clientes.correo_garantes_2, 
								  clientes.correo_garantes_3, 
								  clientes.correo_garantes_4, 
								  clientes.direccion_garantes_1, 
								  clientes.direccion_garantes_2, 
								  clientes.direccion_garantes_3, 
								  clientes.direccion_garantes_4";
						
						
						$tablas=" public.clientes,
							  public.titulo_credito,
							  public.juicios,
							  public.asignacion_secretarios_view,
							  public.estados_procesales_juicios,
							  public.provincias,
							  public.ciudad";
			
						$where="clientes.id_clientes = titulo_credito.id_clientes AND
						  clientes.id_provincias = provincias.id_provincias AND
						  titulo_credito.id_titulo_credito = juicios.id_titulo_credito AND
						  asignacion_secretarios_view.id_ciudad = ciudad.id_ciudad AND
						  juicios.id_estados_procesales_juicios = estados_procesales_juicios.id_estados_procesales_juicios AND
						  asignacion_secretarios_view.id_abogado = titulo_credito.id_usuarios AND asignacion_secretarios_view.id_abogado='$_id_usuarios'";
							
						$id="juicios.orden";
			
						$where_0 = "";
						$where_1 = "";
						$where_2 = "";
						$where_3 = "";
						$where_4 = "";
						$where_5 = "";
							
						$where_6 = "";
						$where_7 = "";
						$where_8 = "";
						$where_9 = "";
						$where_10 = "";
						$where_11 = "";
						$where_12 = "";
							
							
						if($juicio_referido_titulo_credito!=""){$where_0=" AND juicios.juicio_referido_titulo_credito='$juicio_referido_titulo_credito'";}
						
						if($numero_titulo_credito!=""){$where_1=" AND titulo_credito.numero_titulo_credito='$numero_titulo_credito'";}
							
						if($identificacion_clientes!=""){$where_2=" AND clientes.identificacion_clientes like '$identificacion_clientes'";}
							
						if($id_provincias!=0){$where_3=" AND provincias.id_provincias='$id_provincias'";}
						
						if($id_estados_procesales_juicios!=0){$where_4=" AND estados_procesales_juicios.id_estados_procesales_juicios='$id_estados_procesales_juicios'";}
						
						/*para las fechas*/
						$fechaDesde="";$fechaHasta="";
						if(isset($_POST["fcha_desde"])&&isset($_POST["fcha_hasta"]))
						{
							$fechaDesde=$_POST["fcha_desde"];
							$fechaHasta=$_POST["fcha_hasta"];
							if ($fechaDesde != "" && $fechaHasta != "")
							{
								$where_5 = " AND DATE(juicios.fecha_ultima_providencia) BETWEEN '$fechaDesde' AND '$fechaHasta'  ";
							}
								
							if($fechaDesde != "" && $fechaHasta == ""){
									
								$fechaHasta='2018/12/01';
								$where_5 = " AND DATE(juicios.fecha_ultima_providencia) BETWEEN '$fechaDesde' AND '$fechaHasta'  ";
									
							}
							if($fechaDesde == "" && $fechaHasta != ""){
									
								$fechaDesde='1800/01/01';
								$where_5 = " AND DATE(juicios.fecha_ultima_providencia) BETWEEN '$fechaDesde' AND '$fechaHasta'  ";
									
							}
						}
						
						if($identificacion_clientes_1!=""){$where_6=" AND clientes.identificacion_clientes_1 like'$identificacion_clientes_1'";}
						if($identificacion_clientes_2!=""){$where_7=" AND clientes.identificacion_clientes_2 like '$identificacion_clientes_2'";}
						if($identificacion_clientes_3!=""){$where_8=" AND clientes.identificacion_clientes_3 like '$identificacion_clientes_3'";}
						
						
						if($identificacion_garantes!=""){$where_9=" AND clientes.identificacion_garantes like '$identificacion_garantes'";}
						if($identificacion_garantes_1!=""){$where_10=" AND clientes.identificacion_garantes_1 like '$identificacion_garantes_1'";}
						if($identificacion_garantes_2!=""){$where_11=" AND clientes.identificacion_garantes_2 like '$identificacion_garantes_2'";}
						if($identificacion_garantes_3!=""){$where_12=" AND clientes.identificacion_garantes_3 like '$identificacion_garantes_3'";}
						
						
						
						$where_to  = $where . $where_0 . $where_1 . $where_2 . $where_3 . $where_4.$where_5. $where_6 . $where_7 . $where_8 . $where_9.$where_10. $where_11.$where_12;
						
							
						//comienza paginacion
						
						$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
						
						if($action == 'ajax')
						{
						
							$html="";
							$resultSet=$juicios->getCantidad("*", $tablas, $where_to);
							$cantidadResult=(int)$resultSet[0]->total;
								
							$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
								
							$per_page = 50; //la cantidad de registros que desea mostrar
							$adjacents  = 9; //brecha entre páginas después de varios adyacentes
							$offset = ($page - 1) * $per_page;
								
							$limit = " LIMIT   '$per_page' OFFSET '$offset'";
								
								
							$resultSet=$juicios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
								
							$count_query   = $cantidadResult;
								
							$total_pages = ceil($cantidadResult/$per_page);
								
							
							$providencias = new ProvidenciasModel();
							
							if ($cantidadResult>0)
							{
						
								$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
								$html.='<div class="pull-left">';
								$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
								$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
								$html.='</div></div>';
								$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
								$html.='<section style="height:425px; overflow-y:scroll;">';
								$html.='<table class="table table-hover">';
								$html.='<thead>';
								$html.='<tr class="info">';
								$html.='<th style="text-align: left;  font-size: 10px;"></th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Ord.</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Regional</th>';
								$html.='<th style="text-align: left;  font-size: 10px;"># Juicio</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Año Juicio</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Cliente 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Cliente 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Correo Cliente 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Dirección Cliente 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Cliente 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Cliente 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Correo Cliente 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Dirección Cliente 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Cliente 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Cliente 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Correo Cliente 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Dirección Cliente 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Cliente 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Cliente 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Correo Cliente 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Dirección Cliente 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Garante 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Garante 1</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Garante 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Garante 2</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Garante 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Garante 3</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cedula Garante 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Nombres Garante 4</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Provincia</th>';
								$html.='<th style="text-align: left;  font-size: 10px;"># Operación</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Fecha Auto Pago</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Cuantía Inicial</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Riesgo Actual</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Estado Procesal</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Descripción Etapa Procesal</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Fecha Última Providencia</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Estrategia a Seguir</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Observaciones</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Impulsor</th>';
								$html.='<th style="text-align: left;  font-size: 10px;">Secretario</th>';
								$html.='</tr>';
								$html.='</thead>';
								$html.='<tbody>';
						
									
								
								$i=0;
								
								$resultSet_prov="";
								foreach ($resultSet as $res)
								{
									$i++;
						            $id_juicios=$res->id_juicios;
						            
						            $columnas_prov = "firmado_secretario";
						            $tablas_prov="providencias";
						            $where_prov ="id_juicios ='$id_juicios' AND id_tipo_providencias=1";
						            $id_prov="id_providencias";
						            $resultSet_prov=$providencias->getCondiciones($columnas_prov, $tablas_prov, $where_prov, $id_prov);
						            
						            
						            if(!empty($resultSet_prov)){
						            	
						            	foreach ($resultSet_prov as $res_prov)
						            	{
						            		$firmado_secretario=$res_prov->firmado_secretario;
						            	}
						            }else{
						            	
						            	$firmado_secretario="";
						            }
						            
						           	
						            
						            
						            
						            
									$html.='<tr>';
									if($firmado_secretario=='f'){
										$html.='<td ><img src="view/images/esperar.png" width="20" height="20"></td>';
									}else{
										$html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=MatrizJuicios&action=Imprimir_Providencia_Datos&id_juicios='. $res->id_juicios .'&id_clientes='. $res->id_clientes.'&id_titulo_credito='. $res->id_titulo_credito.'&juicio_referido_titulo_credito='. $res->juicio_referido_titulo_credito.'&numero_titulo_credito='. $res->numero_titulo_credito.'&nombres_clientes='. $res->nombres_clientes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
									
									}	
									
									
									
									$html.='<td style="font-size: 9px;">'.$i.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->regional.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->juicio_referido_titulo_credito.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->year_juicios.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_clientes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombres_clientes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->correo_clientes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->direccion_clientes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_clientes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_clientes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->correo_clientes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->direccion_clientes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_clientes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_clientes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->correo_clientes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->direccion_clientes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_clientes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_clientes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->correo_clientes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->direccion_clientes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_garantes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_garantes.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_garantes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_garantes_1.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_garantes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_garantes_2.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->identificacion_garantes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_garantes_3.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_provincias.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->numero_titulo_credito.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->fecha_emision_juicios.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->cuantia_inicial.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->riesgo_actual.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->nombre_estados_procesales_juicios.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->descripcion_estado_procesal.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->fecha_ultima_providencia.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->estrategia_seguir.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->observaciones.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->impulsores.'</td>';
									$html.='<td style="font-size: 9px;">'.$res->secretarios.'</td>';
									//$html.='<td style="font-size: 15px;"><span class="pull-right"><a href="index.php?controller=MatrizJuicios&action=Imprimir_Providencia&id_juicios='. $res->id_juicios .'&id_clientes='. $res->id_clientes.'&id_titulo_credito='. $res->id_titulo_credito.' " target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
									 
									$html.='</tr>';
						
										
								}
						
								$html.='</tbody>';
								$html.='</table>';
								$html.='</section></div>';
								$html.='<div class="table-pagination pull-right">';
								$html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
								$html.='</div>';
						
									
							}else{
									
								$html.='<div class="alert alert-warning alert-dismissable">';
								$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
								$html.='<h4>Aviso!!!</h4> No hay datos para mostrar';
								$html.='</div>';
									
							}
								
							echo $html;
							die();
								
						}
						
							
						
					
			
					}
					
					
					
			
					$this->view("MatrizJuiciosProvidencias",array(
							"resultSet"=>$resultSet, "resultEstadoProcesal"=>$resultEstadoProcesal, "resultProv"=>$resultProv
			
								
								
					));
			
			
				}
				else
				{
					$this->view("Error",array(
							"resultado"=>"No tiene Permisos de Acceso a Matriz Juicios"
			
					));
			
					exit();
				}
			
			}
					}
			else
			{
				$this->view("Login",array(
						"resultSet"=>""
			
				));
			
			}
			
			
	}
	


	
	public function paginate($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_matriz(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_matriz(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_matriz(1)'>1</a></li>";
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
				$out.= "<li><a href='javascript:void(0);' onclick='load_matriz(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_matriz(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_matriz($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_matriz(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	







	
		}
	
	
	
	
	?>