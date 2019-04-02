<<?php

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
	        
	        if(isset($_GET["id_mayor"])){
	            
	            
	            $_id_mayor = $_GET["id_mayor"];
	            
	            
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
                                  entidades.telefono_entidades,
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
                                  con_mayor.id_mayor = '$_id_mayor'";
	            
	            $id="con_mayor.id_mayor";
	            
	            $resultSetCabeza=$mayor->getCondiciones($columnas, $tablas, $where, $id);
	            
	            if(!empty($resultSetCabeza)){
	                
	                
	                $_fecha_mayor     =$resultSetCabeza[0]->fecha_mayor;
	                $_haber_mayor     =$resultSetCabeza[0]->haber_mayor;
	                $_debe_mayor     =$resultSetCabeza[0]->debe_mayor;
	                $_codigo_plan_cuentas     =$resultSetCabeza[0]->codigo_plan_cuentas;
	                $_nombre_plan_cuentas     =$resultSetCabeza[0]->nombre_plan_cuentas;
	                $_saldo_mayor     =$resultSetCabeza[0]->saldo_mayor;
	                $_saldo_ini_mayor     =$resultSetCabeza[0]->saldo_ini_mayor;
	                $_creado     =$resultSetCabeza[0]->creado;
	                $_modificado     =$resultSetCabeza[0]->modificado;
	                $_n_plan_cuentas     =$resultSetCabeza[0]->n_plan_cuentas;
	                $_t_plan_cuentas     =$resultSetCabeza[0]->t_plan_cuentas;
	                $_nivel_plan_cuentas     =$resultSetCabeza[0]->nivel_plan_cuentas;
	                $_fecha_ini_plan_cuentas     =$resultSetCabeza[0]->fecha_ini_plan_cuentas;
	                $_saldo_plan_cuentas     =$resultSetCabeza[0]->saldo_plan_cuentas;
	                $_fecha_fin_plan_cuentas     =$resultSetCabeza[0]->fecha_fin_plan_cuentas;
	                $_saldo_fin_plan_cuentas     =$resultSetCabeza[0]->saldo_fin_plan_cuentas;
	                $_id_mayor     =$resultSetCabeza[0]->id_mayor;
	                $_ruc_entidades     =$resultSetCabeza[0]->ruc_entidades;
	                $_nombre_entidades     =$resultSetCabeza[0]->nombre_entidades;
	                $_numero_ccomprobantes     =$resultSetCabeza[0]->numero_ccomprobantes;
	                $_nombre_tipo_comprobantes     =$resultSetCabeza[0]->nombre_tipo_comprobantes;
	                $_nombre_usuarios     =$resultSetCabeza[0]->nombre_usuarios;
	                $_apellidos_usuarios     =$resultSetCabeza[0]->apellidos_usuarios;
	                $_concepto_ccomprobantes     =$resultSetCabeza[0]->concepto_ccomprobantes;
	                $_telefono_entidades     =$resultSetCabeza[0]->telefono_entidades;
	                $_fecha_hoy =date('Y-m-d');
	                 
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th style="text-align: center; font-size: 25px; "><b>'.$_nombre_entidades.'</b></br>';
	                $html.='<p style="text-align: center; font-size: 18px; ">DETALLADO TOTAL</p>';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; Ruc: '.$_ruc_entidades.'</p>';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; Desde: </p>';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; Hasta: </p>';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; Fecha: '.$_fecha_hoy.'</p>';
	                $html.='</tr>';
	                $html.='</table>';
	          
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th style="text-align: left; font-size: 13px; "><b>&nbsp; Número de cuenta: </b>'.$_codigo_plan_cuentas.'';
	                $html.='<th style="text-align: left; font-size: 13px;" ><b>&nbsp; Nombre de cuenta: </b>'.$_nombre_plan_cuentas.'';
	                $html.='<th style="text-align: left; font-size: 13px;" ><b>&nbsp; Saldo Anterior: </b>'.$_saldo_ini_mayor.'';
	                
	                $html.="</tr>";
	                
	           
	                    $html.='</table>';
	                    $html.="<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                    $html.='<tr>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">FECHA:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">DOC:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">NOMBRE:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">DESCRIPCION:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">FACTURA:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">CHEQUE:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">DEBE:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">HABER:</th>';
	                    $html.='<th colspan="1" style="text-align:center; font-size: 13px;">SALDO:</th>';
	                    
	                    
	                 
	                    
	                    $html.='</tr>';
	                    $html.='<tr>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_fecha_mayor.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_numero_ccomprobantes.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_nombre_usuarios.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_concepto_ccomprobantes.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; "></td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; "></td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_debe_mayor.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_haber_mayor.'</td>';
	                    $html.='<td colspan="1" style="text-align:center; font-size: 13px; ">'.$_saldo_mayor.'</td>';
	                    $html.='<p style="text-align: left; font-size: 13px;">&nbsp;&nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>TOTAL:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	                    
	                   
	                    
	                    $html.='</tr>';
	                    $html.='</table>';
	                    
	           
	                
	                
	            }
	            
	            
	            
	            $this->report("Mayor",array( "resultSet"=>$html));
	            die();
	            
	        }
	        
	        
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	    
	    
	    
	}
	

    	}
?>