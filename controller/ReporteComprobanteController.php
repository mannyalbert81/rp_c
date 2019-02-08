<?php

class ReporteComprobanteController extends ControladorBase{

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
			$nombre_controladores = "ReporteComprobante";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $permisos_rol->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
				
			    if(isset($_POST["id_entidades"])){
			        
			        
			        $id_entidades=$_POST['id_entidades'];
			        $id_tipo_comprobantes=$_POST['id_tipo_comprobantes'];
			        $numero_ccomprobantes=$_POST['numero_ccomprobantes'];
			        $fechadesde=$_POST['fecha_desde'];
			        $fechahasta=$_POST['fecha_hasta'];
			        
			        
			        
			        
			        $columnas = " ccomprobantes.id_ccomprobantes,
								  tipo_comprobantes.nombre_tipo_comprobantes,
							      tipo_comprobantes.id_tipo_comprobantes,
								  ccomprobantes.concepto_ccomprobantes,
								  usuarios.nombre_usuarios,
							      entidades.id_entidades,
								  entidades.nombre_entidades,
								  ccomprobantes.valor_letras,
								  ccomprobantes.fecha_ccomprobantes,
								  ccomprobantes.numero_ccomprobantes,
								  ccomprobantes.ruc_ccomprobantes,
								  ccomprobantes.nombres_ccomprobantes,
								  ccomprobantes.retencion_ccomprobantes,
								  ccomprobantes.valor_ccomprobantes,
								  ccomprobantes.referencia_doc_ccomprobantes,
								  ccomprobantes.numero_cuenta_banco_ccomprobantes,
								  ccomprobantes.numero_cheque_ccomprobantes,
								  ccomprobantes.observaciones_ccomprobantes,
								  forma_pago.nombre_forma_pago";
			        
			        
			        
			        $tablas=" public.ccomprobantes,
							  public.entidades,
							  public.usuarios,
							  public.tipo_comprobantes,
							  public.forma_pago";
			        
			        $where="ccomprobantes.id_forma_pago = forma_pago.id_forma_pago AND
							  entidades.id_entidades = usuarios.id_entidades AND
							  usuarios.id_usuarios = ccomprobantes.id_usuarios AND
							  tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes AND usuarios.id_usuarios='$_id_usuarios'";
			        
			        $id="ccomprobantes.numero_ccomprobantes";
			        
			        
			        $where_0 = "";
			        $where_1 = "";
			        $where_2 = "";
			        $where_4 = "";
			        
			        
			        
			        
			        if($id_entidades!=0){$where_0=" AND entidades.id_entidades='$id_entidades'";}
			        
			        if($id_tipo_comprobantes!=0){$where_1=" AND tipo_comprobantes.id_tipo_comprobantes='$id_tipo_comprobantes'";}
			        
			        if($numero_ccomprobantes!=""){$where_2=" AND ccomprobantes.numero_ccomprobantes LIKE '%$numero_ccomprobantes%'";}
			   
			        if($fechadesde!="" && $fechahasta!=""){$where_4=" AND  ccomprobantes.fecha_ccomprobantes BETWEEN '$fechadesde' AND '$fechahasta'";}
			        
			        
			        $where_to  = $where . $where_0 . $where_1 . $where_2. $where_4;
			        
			        
			        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
			        
			        if($action == 'ajax')
			        {
			            $html="";
			            $resultSet=$ccomprobantes->getCantidad("*", $tablas, $where_to);
			            $cantidadResult=(int)$resultSet[0]->total;
			            
			            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
			            
			            $per_page = 50; //la cantidad de registros que desea mostrar
			            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
			            $offset = ($page - 1) * $per_page;
			            
			            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
			            
			            
			            $resultSet=$ccomprobantes->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
			            
			            $count_query   = $cantidadResult;
			            
			            $total_pages = ceil($cantidadResult/$per_page);
			            
			            if ($cantidadResult>0)
			            {
			
			                
			                $html.='<div class="pull-left">';
			                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
			                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
			                $html.='</div><br><br>';
			                $html.='<section style="height:250px; overflow-y:scroll;">';
			                $html.='<table class="table table-hover">';
			                $html.='<thead>';
			                $html.='<tr class="info">';
			                $html.='<th>Tipo</th>';
			                $html.='<th>Concepto</th>';
			                $html.='<th>Entidad</th>';
			                $html.='<th>Valor</th>';
			                $html.='<th>Fecha</th>';
			                $html.='<th>Numero de Comprobante</th>';
			                $html.='<th>Forma de Pago</th>';
			                $html.='<th></th>';
			                $html.='</tr>';
			                $html.='</thead>';
			                $html.='<tbody>';
			                
			                foreach ($resultSet as $res)
			                {
			                       
			                    $html.='<tr>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->nombre_tipo_comprobantes.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->concepto_ccomprobantes.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->nombre_entidades.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->valor_letras.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->fecha_ccomprobantes.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->numero_ccomprobantes.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;">'.$res->nombre_forma_pago.'</td>';
			                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=ReporteComprobante&action=generar_reporte_comprobante&id_ccomprobantes='.$res->id_ccomprobantes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
			                    $html.='</tr>';
			                    
			                }
			                
			                $html.='</tbody>';
			                $html.='</table>';
			                $html.='</section>';
			                $html.='<div class="table-pagination pull-right">';
			                $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
			                $html.='</div>';
			                $html.='</section>';
			                
			                
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
			    
					
				$this->view("ReporteComprobante",array(
				    "resultSet"=>$resultSet, "resultTipCom"=> $resultTipCom,
				    "resultEnt"=>$resultEnt
				    
				));
			
			
			}else{
				
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Consultar Comprobantes"
				
					
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
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	public function  generar_reporte_comprobante(){
	    
	    session_start();
	    $ccomprobantes = new CComprobantesModel();
	    $dcomprobantes = new DComprobantesModel();
	    $tipo_comprobantes = new TipoComprobantesModel();
	    $entidades = new EntidadesModel();
	    $tipo_comprobante=new TipoComprobantesModel();
	    
  	    
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
	        
	        
	        if(isset($_GET["id_ccomprobantes"])){
	            
	            
	            $_id_ccomprobantes = $_GET["id_ccomprobantes"];
	            
	            
	            $columnas = " ccomprobantes.id_ccomprobantes,
								  tipo_comprobantes.nombre_tipo_comprobantes,
							      tipo_comprobantes.id_tipo_comprobantes,
								  ccomprobantes.concepto_ccomprobantes,
								  usuarios.nombre_usuarios,
							      entidades.id_entidades,
								  entidades.nombre_entidades,
                                  entidades.direccion_entidades,
								  ccomprobantes.valor_letras,
								  ccomprobantes.fecha_ccomprobantes,
								  ccomprobantes.numero_ccomprobantes,
								  ccomprobantes.ruc_ccomprobantes,
								  ccomprobantes.nombres_ccomprobantes,
								  ccomprobantes.retencion_ccomprobantes,
								  ccomprobantes.valor_ccomprobantes,
								  ccomprobantes.referencia_doc_ccomprobantes,
								  ccomprobantes.numero_cuenta_banco_ccomprobantes,
								  ccomprobantes.numero_cheque_ccomprobantes,
								  ccomprobantes.observaciones_ccomprobantes,
								  forma_pago.nombre_forma_pago";
	            
	            
	            
	            $tablas=" public.ccomprobantes,
							  public.entidades,
							  public.usuarios,
							  public.tipo_comprobantes,
							  public.forma_pago";
	            
	            $where="ccomprobantes.id_forma_pago = forma_pago.id_forma_pago AND
							  entidades.id_entidades = usuarios.id_entidades AND
							  usuarios.id_usuarios = ccomprobantes.id_usuarios AND
							  tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes AND ccomprobantes.id_ccomprobantes='$_id_ccomprobantes'";
	            
	            $id="ccomprobantes.numero_ccomprobantes";
	            
	            
	           
	            
	            $resultSetCabeza=$ccomprobantes->getCondiciones($columnas, $tablas, $where, $id);
	            
	            
	            if(!empty($resultSetCabeza)){
	                
	                
	                $_nombre_tipo_comprobantes     =$resultSetCabeza[0]->nombre_tipo_comprobantes;
	                $_concepto_ccomprobantes     =$resultSetCabeza[0]->concepto_ccomprobantes;
	                $_nombre_usuarios     =$resultSetCabeza[0]->nombre_usuarios;
	                $_nombre_entidades     =$resultSetCabeza[0]->nombre_entidades;
	                $_direccion_entidades     =$resultSetCabeza[0]->direccion_entidades;
	                $_valor_letras     =$resultSetCabeza[0]->valor_letras;
	                $_fecha_ccomprobantes     =$resultSetCabeza[0]->fecha_ccomprobantes;
	                $_numero_ccomprobantes     =$resultSetCabeza[0]->numero_ccomprobantes;
	                $_ruc_ccomprobantes     =$resultSetCabeza[0]->ruc_ccomprobantes;
	                $_nombres_ccomprobantes     =$resultSetCabeza[0]->nombres_ccomprobantes;
	                $_retencion_ccomprobantes     =$resultSetCabeza[0]->retencion_ccomprobantes;
	                $_valor_ccomprobantes     =$resultSetCabeza[0]->valor_ccomprobantes;
	                $_referencia_doc_ccomprobantes     =$resultSetCabeza[0]->referencia_doc_ccomprobantes;
	                $_numero_cuenta_banco_ccomprobantes     =$resultSetCabeza[0]->numero_cuenta_banco_ccomprobantes;
	                $_numero_cheque_ccomprobantes     =$resultSetCabeza[0]->numero_cheque_ccomprobantes;
	                $_observaciones_ccomprobantes     =$resultSetCabeza[0]->observaciones_ccomprobantes;
	                $_nombre_forma_pago     =$resultSetCabeza[0]->nombre_forma_pago;
	                
	                
	                
	                $html.='<p style="text-align: center; font-size: 30px; margin-top:0px;"><b>'.$_nombre_entidades.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 15px; margin-top:0px;"><b>'.direccion_entidades.'</b></p>';
	                
	                $html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
	                $html.='<p style="text-align: right; font-size: 13px;"><b>Fecha Factura:</b> '.$fechaactual.'</p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_nombre_tipo_comprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_concepto_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_nombre_usuarios.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_valor_letras.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_fecha_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_numero_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_ruc_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_nombres_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_retencion_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_valor_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_referencia_doc_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_numero_cuenta_banco_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_numero_cheque_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_observaciones_ccomprobantes.'</b></p>';
	                $html.='<p style="text-align: center; font-size: 16px; margin-top:60px;"><b>Factura No. '.$_nombre_forma_pago.'</b></p>';
	                
	              
	                
	                
	                
	             
	                
	            }
	            
	          
	            
	            $this->report("Comprobante",array( "resultSet"=>$html));
	            die();
	            
	        }
	        
	        
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	    
	    
	    
	}
	}
?>