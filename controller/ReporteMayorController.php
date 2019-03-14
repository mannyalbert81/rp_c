<?php

class ReporteMayorController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}


	public function index(){
	
		session_start();
		
		
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
		    
		    $_id_usuarios= $_SESSION['id_usuarios'];
		    
		    $resultSet="";
		    $registrosTotales = 0;
		    $arraySel = "";
		    
		    $ccomprobantes = new CComprobantesModel();
		    $dcomprobantes = new DComprobantesModel();
		    $tipo_comprobantes = new TipoComprobantesModel();
		    $entidades = new EntidadesModel();
		    $mayor = new MayorModel();
		    $tipo_comprobante=new TipoComprobantesModel();
		  
		    $resultTipCom = $tipo_comprobante->getAll("nombre_tipo_comprobantes");
		
		    $columnas_enc = "entidades.id_entidades,
  							entidades.nombre_entidades";
		    $tablas_enc ="public.usuarios,
						  public.entidades";
		    $where_enc ="entidades.id_entidades = usuarios.id_entidades AND usuarios.id_usuarios='$_id_usuarios'";
		    $id_enc="entidades.nombre_entidades";
		    $resultEnt=$entidades->getCondiciones($columnas_enc ,$tablas_enc ,$where_enc, $id_enc);
		    
		    
				
		    $permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "ReporteMayor";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $permisos_rol->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
				
			    if(isset($_POST["id_entidades"])){
			        
			        
			        $id_entidades=$_POST['id_entidades'];
			        $id_tipo_comprobantes=$_POST['id_tipo_comprobantes'];
			        $codigo_plan_cuentas=$_POST['codigo_plan_cuentas'];
			        $fechadesde=$_POST['fecha_desde'];
			        $fechahasta=$_POST['fecha_hasta'];
			        
			        
			        
			        
			        $columnas = " con_mayor.fecha_mayor, 
                                  con_mayor.haber_mayor, 
                                  con_mayor.debe_mayor, 
                                  plan_cuentas.codigo_plan_cuentas, 
                                  plan_cuentas.nombre_plan_cuentas, 
                                  con_mayor.saldo_mayor, 
                                  con_mayor.saldo_ini_mayor, 
                                  con_mayor.creado, 
                                  con_mayor.modificado, 
                                  plan_cuentas.n_plan_cuentas, 
                                  plan_cuentas.t_plan_cuentas, 
                                  plan_cuentas.nivel_plan_cuentas, 
                                  plan_cuentas.fecha_ini_plan_cuentas, 
                                  plan_cuentas.saldo_plan_cuentas, 
                                  plan_cuentas.fecha_fin_plan_cuentas, 
                                  plan_cuentas.saldo_fin_plan_cuentas, 
                                  con_mayor.id_mayor, 
                                  entidades.ruc_entidades, 
                                  entidades.nombre_entidades, 
                                  ccomprobantes.numero_ccomprobantes, 
                                  ccomprobantes.ruc_ccomprobantes, 
                                  ccomprobantes.nombres_ccomprobantes, 
                                  tipo_comprobantes.nombre_tipo_comprobantes, 
                                  usuarios.nombre_usuarios, 
                                  usuarios.apellidos_usuarios,
                                  ccomprobantes.concepto_ccomprobantes";
                                    			        
			        
			        
			        $tablas="     public.con_mayor, 
                                  public.plan_cuentas, 
                                  public.entidades, 
                                  public.ccomprobantes, 
                                  public.tipo_comprobantes, 
                                  public.usuarios";
			        
			        $where="    plan_cuentas.id_plan_cuentas = con_mayor.id_plan_cuentas AND
                                  entidades.id_entidades = plan_cuentas.id_entidades AND
                                  ccomprobantes.id_ccomprobantes = con_mayor.id_ccomprobantes AND
                                  tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes AND
                                  usuarios.id_usuarios = '$_id_usuarios'";
                                			        
			        $id="con_mayor.id_mayor";
			        
			        
			        $where_0 = "";
			        $where_1 = "";
			        $where_2 = "";
			        $where_4 = "";
			        
			        
		            if($id_entidades!=0){$where_0=" AND entidades.id_entidades='$id_entidades'";}
			        
			        if($id_tipo_comprobantes!=0){$where_1=" AND tipo_comprobantes.id_tipo_comprobantes='$id_tipo_comprobantes'";}
			        
			        if($codigo_plan_cuentas!=""){$where_2=" AND plan_cuentas.codigo_plan_cuentas LIKE '%$codigo_plan_cuentas%'";}
			   
			        if($fechadesde!="" && $fechahasta!=""){$where_4=" AND  date(con_mayor.fecha_mayor) BETWEEN '$fechadesde' AND '$fechahasta'";}
			        
			        
			        $where_to  = $where . $where_0 . $where_2 . $where_1 . $where_4;
			        
			        
			        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
			        
			        if($action == 'ajax')
			        {
			            $html="";
			            $resultSet=$mayor->getCantidad("*", $tablas, $where_to);
			            $cantidadResult=(int)$resultSet[0]->total;
			            
			            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
			            
			            $per_page = 50; //la cantidad de registros que desea mostrar
			            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
			            $offset = ($page - 1) * $per_page;
			            
			            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
			            
			            
			            $resultSet=$mayor->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
			            
			            $count_query   = $cantidadResult;
			            
			            $total_pages = ceil($cantidadResult/$per_page);
			            
			            if ($cantidadResult>0)
			            {
			
			                
			                
			                
			                $html.='<div class="pull-left" style="margin-left:15px;">';
			                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
			                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
			                $html.='</div>';
			                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
			                $html.='<section style="height:425px; overflow-y:scroll;">';
			                $html.= "<table id='tabla_mayor' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
			                $html.= "<thead>";
			                $html.= "<tr>";
			                $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Codigo Cuenta</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Saldo Inicial</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Debe</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Haber</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Reporte</th>';
			                
			              
			                
			                $html.='</tr>';
			                $html.='</thead>';
			                $html.='<tbody>';
			                
			                
			              
			                
			                       foreach ($resultSet as $res)
			                {
			                       
			                        
			                    
			                    $html.='<tr>';
			                    $html.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->codigo_plan_cuentas.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->nombre_plan_cuentas.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->concepto_ccomprobantes.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->saldo_ini_mayor.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->debe_mayor.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->haber_mayor.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->fecha_mayor.'</td>';
			                    $html.='<td style="font-size: 11px;"><span class="pull-right"><a href="index.php?controller=ReporteMayor&action=generar_reporte_mayor&id_mayor='.$res->id_mayor.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
			                    
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
			    
					
			    $this->view_Contable("ReporteMayor",array(
				    "resultSet"=>$resultSet, "resultTipCom"=> $resultTipCom,
				    "resultEnt"=>$resultEnt
				    
				));
			
			
			}else{
				
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Consultar Mayor"
				
					
				));
				exit();
			}
			
			
		}
		else
		{
	
		    $this->redirect("Usuarios","sesion_caducada");
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
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_mayor(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_mayor(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_mayor(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_mayor(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_mayor(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_mayor($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_mayor(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	public function  generar_reporte_mayor(){
	    
	    session_start();
	    $ccomprobantes = new CComprobantesModel();
	    $dcomprobantes = new DComprobantesModel();
	    $tipo_comprobantes = new TipoComprobantesModel();
	    $entidades = new EntidadesModel();
	    $tipo_comprobante=new TipoComprobantesModel();
	    $mayor = new MayorModel();
	    
  	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/logo.png';
	    $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
	    
	    
	    
	    if(!empty($cedula_usuarios)){
	        
	        
	        if(!isset($_POST['action'])){
	            
	            echo 'sin datos';
	            return;
	        }
	        
	        
	        
	        $id_plan_cuentas = (isset($_POST['id_cuenta']))?$_POST['id_cuenta']:'0';
	        
	        $columna = 'SELECT id_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas';
	        
	        $tabla = ' FROM vw_diario_contable';
	        
	        $where = ' WHERE 1=1';
	        
	        $grupo = ' GROUP BY id_plan_cuentas,codigo_plan_cuentas,nombre_plan_cuentas';
	        
	        $orden = ' ORDER BY codigo_plan_cuentas';
	        
	        $where = ($id_plan_cuentas>0)?$where.' AND id_plan_cuentas='.$id_plan_cuentas:$where;
	        
	        $query=$columna.$tabla.$where.$grupo.$orden;
	        
	        $result = $mayor->enviaquery($query);
	        
	        $html='';
	        
	        if(!empty($result) && count($result)>0){
	            
	            $html.='<table class="table">';
	            
	            for($i=0;$i<count($result);$i++){
	                
	                $codigo = $result[$i]->id_plan_cuentas;
	                
	                $html.='<tr style="font-weight:bold; text-transform: uppercase;" class="active">';
	                $html.='<td>';
	                $html.= $result[$i]->codigo_plan_cuentas;
	                $html.='</td>';
	                $html.='<td colspan="3">';
	                $html.= $result[$i]->nombre_plan_cuentas;
	                $html.='</td>';
	                $html.='</tr>';
	                
	                $query="SELECT * FROM vw_diario_contable WHERE id_plan_cuentas = $codigo ORDER BY codigo_plan_cuentas";
	                
	                $resultdetalle = $mayor->enviaquery($query);
	                
	                if(!empty($resultdetalle) && count($result)>0){
	                    
	                    $j = 0;
	                    foreach ($resultdetalle as $res){
	                        
	                        $j+=1;
	                        $html.='<tr>';
	                        $html.='<td>';
	                        $html.= $j;
	                        $html.='</td>';
	                        $html.='<td>';
	                        $html.= $res->fecha_mayor;
	                        $html.='</td>';
	                        $html.='<td>';
	                        $html.= $res->sumadebe;
	                        $html.='</td>';
	                        $html.='<td>';
	                        $html.= $res->sumahaber;
	                        $html.='</td>';
	                        $html.='</tr>';
	                        
	                    }
	                }
	                
	                
	            }
	            
	            $html.='</table>';
	        }
	        
	        
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	    
	    
	    
	}
	
	
	}
?>