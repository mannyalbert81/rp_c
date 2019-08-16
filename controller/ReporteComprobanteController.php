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
			   
			        if($fechadesde!="" && $fechahasta!=""){$where_4=" AND  date(ccomprobantes.fecha_ccomprobantes) BETWEEN '$fechadesde' AND '$fechahasta'";}
			        
			        
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
			
			                
			                
			                
			                $html.='<div class="pull-left" style="margin-left:15px;">';
			                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
			                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
			                $html.='</div>';
			                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
			                $html.='<section style="height:425px; overflow-y:scroll;">';
			                $html.= "<table id='tabla_comprobantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
			                $html.= "<thead>";
			                $html.= "<tr>";
			                $html.='<th style="text-align: left;  font-size: 12px;">Tipo</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Numero de Comprobante</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Forma de Pago</th>';
			                $html.='<th style="text-align: left;  font-size: 12px;">Reporte</th>';
			                
			              
			                
			                $html.='</tr>';
			                $html.='</thead>';
			                $html.='<tbody>';
			                
			                
			              
			                
			                       foreach ($resultSet as $res)
			                {
			                       
			                        
			                    
			                    $html.='<tr>';
			                    $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_comprobantes.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->concepto_ccomprobantes.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->valor_letras.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->fecha_ccomprobantes.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->numero_ccomprobantes.'</td>';
			                    $html.='<td style="font-size: 11px;">'.$res->nombre_forma_pago.'</td>';
			                    $html.='<td style="font-size: 11px;"><span class="pull-right"><a href="index.php?controller=ReporteComprobante&action=generar_reporte_comprobante&id_ccomprobantes='.$res->id_ccomprobantes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
			                    
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
			    
					
			    $this->view_Contable("ReporteComprobante",array(
				    "resultSet"=>$resultSet, "resultTipCom"=> $resultTipCom,
				    "resultEnt"=>$resultEnt
				    
				));
			
			
			}else{
				
			    $this->view_Contable("Error",array(
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
	
	
	
	public function reporte_comprobante(){
	    
	    
	    session_start();
	    $entidades = new EntidadesModel();
	    //PARA OBTENER DATOS DE LA EMPRESA
	    $datos_empresa = array();
	    $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
	    
	    if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
	        //llenar nombres con variables que va en html de reporte
	        $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
	        $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
	        $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
	        $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
	        $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
	        $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
	    }
	    
	    //NOTICE DATA
	    $datos_cabecera = array();
	    $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
	    $datos_cabecera['FECHA'] = date('Y/m/d');
	    $datos_cabecera['HORA'] = date('h:i:s');
	    
	    $salidas = new MovimientosInvCabezaModel();
	    $salidas_detalle = new MovimientosInvDetalleModel();
	    $id_movimientos_inv_cabeza=  (isset($_REQUEST['id_movimientos_inv_cabeza'])&& $_REQUEST['id_movimientos_inv_cabeza'] !=NULL)?$_REQUEST['id_movimientos_inv_cabeza']:'';
	    
	    
	    $datos_reporte = array();
	    
	    $columnas="movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                      usuarios.nombre_usuarios,
                      usuarios.id_usuarios,
                      movimientos_inv_cabeza.razon_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.modificado,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	    
	    $tablas = "public.movimientos_inv_cabeza,
                      public.usuarios,
                      public.consecutivos";
	    
	    $where = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SALIDA'
                      AND estado_movimientos_inv_cabeza='APROBADA' AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$id_movimientos_inv_cabeza'";
	    
	    $id="movimientos_inv_cabeza.numero_movimientos_inv_cabeza";
	    
	    $rsdatos = $salidas->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $datos_reporte['USUARIOS']=$rsdatos[0]->nombre_usuarios;
	    $datos_reporte['FECHAMOV']=$rsdatos[0]->fecha_movimientos_inv_cabeza;
	    $datos_reporte['ESTADO']=$rsdatos[0]->estado_movimientos_inv_cabeza;
	    
	    
	    
	    
	    
	    //////retencion detalle
	    
	    $columnas = "movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                    movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
	                productos.codigo_productos,
	                productos.nombre_productos,
	                grupos.id_grupos,
	                grupos.nombre_grupos,
	                movimientos_inv_detalle.cantidad_movimientos_inv_detalle,
	                movimientos_inv_detalle.saldo_f_movimientos_inv_detalle,
	                movimientos_inv_detalle.saldo_v_movimientos_inv_detalle";
	    
	    $tablas = "public.movimientos_inv_detalle,
                  public.movimientos_inv_cabeza,
                  public.grupos,
                  public.productos";
	    $where= " movimientos_inv_cabeza.id_movimientos_inv_cabeza = movimientos_inv_detalle.id_movimientos_inv_cabeza AND
                  productos.id_productos = movimientos_inv_detalle.id_productos AND grupos.id_grupos  = productos.id_grupos AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$id_movimientos_inv_cabeza' ";
	    $id="movimientos_inv_cabeza.id_movimientos_inv_cabeza";
	    
	    $resultSetDetalle = $salidas_detalle->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    
	    
	    $html='';
	    
	    
	    $html.= "<table style='width: 100px; margin-top:10px;' border=1 cellspacing=0>";
	    
	    $html.= "<tr>";
	    $html.='<th style="text-align: left;  font-size: 12px;"width="50">#</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 13px;"width="80"px>Código</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 13px;"width="200">Grupo</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 13px;"width="200">Nombre Producto</th>';
	    $html.='<th colspan="2" style="text-align: center; font-size: 13px;"width="100">Cantidad</th>';
	    $html.='</tr>';
	    
	    
	    $i=0;
	    
	    foreach ($resultSetDetalle as $res)
	    {
	        
	        
	        $i++;
	        $html.='<tr >';
	        $html.='<td style="font-size: 11px;"width="50" align="center" >'.$i.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 11px;"width="80" align="center">'.$res->codigo_productos.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 11px;"width="200">'.$res->nombre_grupos.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 11px;"width="200">'.$res->nombre_productos.'</td>';
	        $html.='<td colspan="2" style="text-align: center; font-size: 11px;"width="100" align="center">'.$res->cantidad_movimientos_inv_detalle.'</td>';
	        
	        
	        $html.='</td>';
	        $html.='</tr>';
	    }
	    
	    $html.='</table>';
	    
	    
	    
	    $datos_reporte['DETALLE_MOVIMIENTOS']= $html;
	    
	    
	    
	    
	    
	    $this->verReporte("DetalleSolicitudAprobada", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
	    
	    
	    
	    
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
	    
	    $valor_total_vista1 = 0;
	    
	    
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
                              entidades.telefono_entidades,
                              entidades.ruc_entidades,
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
                              dcomprobantes.descripcion_dcomprobantes,
							  forma_pago.nombre_forma_pago,
                              proveedores.nombre_proveedores
		        ";
	            
	          $tablas=" public.ccomprobantes,
						  public.entidades,
						  public.usuarios,
						  public.tipo_comprobantes,
						  public.forma_pago,
                          public.dcomprobantes,
                          public.proveedores";
            
	          $where="ccomprobantes.id_forma_pago = forma_pago.id_forma_pago AND
					  entidades.id_entidades = usuarios.id_entidades AND
					  usuarios.id_usuarios = ccomprobantes.id_usuarios AND
                      ccomprobantes.id_proveedores = proveedores.id_proveedores AND
                      dcomprobantes.id_ccomprobantes = ccomprobantes.id_ccomprobantes AND
					  tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes AND ccomprobantes.id_ccomprobantes='$_id_ccomprobantes'";
        
	            $id="ccomprobantes.numero_ccomprobantes";
	            
	            $resultSetCabeza=$ccomprobantes->getCondiciones($columnas, $tablas, $where, $id);
	           
	            if(!empty($resultSetCabeza)){
	                
	                $_nombre_tipo_comprobantes     =$resultSetCabeza[0]->nombre_tipo_comprobantes;
	                $_concepto_ccomprobantes     =$resultSetCabeza[0]->concepto_ccomprobantes;
	                $_nombre_usuarios     =$resultSetCabeza[0]->nombre_usuarios;
	                $_nombre_entidades     =$resultSetCabeza[0]->nombre_entidades;
	                $_direccion_entidades     =$resultSetCabeza[0]->direccion_entidades;
	                $_telefono_entidades     =$resultSetCabeza[0]->telefono_entidades;
	                $_ruc_entidades     =$resultSetCabeza[0]->ruc_entidades;
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
	                $_nombre_proveedores     =$resultSetCabeza[0]->nombre_proveedores;
	                $_descripcion_dcomprobantes     =$resultSetCabeza[0]->descripcion_dcomprobantes;
	                
	                //echo getcwd(); die();
	                //echo ''.getcwd().'\\view\\images\\Logo-Capremci-h-170.jpg'; die();
	                
	                $columnas1 = "plan_cuentas.nombre_plan_cuentas,
                                  plan_cuentas.codigo_plan_cuentas,
                                  dcomprobantes.descripcion_dcomprobantes, 
                                  dcomprobantes.debe_dcomprobantes, 
                                  dcomprobantes.haber_dcomprobantes, 
                                  dcomprobantes.numero_dcomprobantes";
                                	                
	                $tablas1   = "   public.dcomprobantes, 
                                     public.plan_cuentas";
	                $where1    = "plan_cuentas.id_plan_cuentas = dcomprobantes.id_plan_cuentas AND dcomprobantes.id_ccomprobantes='$_id_ccomprobantes' ";
	           
	                $id1       = "dcomprobantes.id_dcomprobantes";
	                
	                
	                $resultSetDetalle=$dcomprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                $html.= '<table style="width:100%;" class="headertable">';
	                $html.= '<tr >';
	                $html.= '<td style="background-repeat: no-repeat;	background-size: 10% 100%;	background-image: url(http://192.168.1.231/rp_c/view/images/Logo-Capremci-h-170.jpg); 
                                        background-position: 0% 100%;	font-size: 11px; padding: 0px; 	text-align:center;" class="central" colspan="2">';
	                $html.= '<strong>';
	                $html.= $_nombre_entidades.'<br>';
	                $html.= $_direccion_entidades.'<br>';
	                $html.= $_telefono_entidades.'';
	                $html.= '</strong>';
	                $html.= '</td>';
	                $html.= '</tr>';
	                $html.= '<tr>';
	                $html.= '<td class="htexto1" style="font-size: 10px;  padding: 5px; text-align:left;width: 65%;" >';
	                $html.= '<p>';
	                $html.= '<strong>Ruc: </strong> '.$_ruc_entidades.'<br>';
	                $html.= '<strong>Usuario: </strong>'.$_SESSION['usuario_usuarios'];
	                $html.= '</p>';
	                $html.= '</td>';
	                $html.= '<td class="htexto2" style="font-size: 10px;  padding: 5px; text-align:left; width: 33%;" >';
	                $html.= '<p>';
	                $html.= '<strong>Fecha de Impresión: </strong> '.date('Y-m-d').'<br>';
	                $html.= '<span><strong>Hoja: </strong> 1 </span>';
	                $html.= '</p>';
	                $html.= '</td>';	                
	                $html.= '</tr>';
	                $html.= '</table>';	
	               
	                $html.= "<table style='width: 100%; margin-top:10px;' border=0 cellspacing=0>";
	                $html.= "<tr><td>Datos Factura:</td></tr>";
	                $html.= "<tr>";
	                $html.='<td style="text-align: left; font-size: 12px; ">';	                
	                $html.='&nbsp;Fecha Factura: '.$_fecha_ccomprobantes.'</td>';
	                $html.='</tr>';
	                $html.= "<tr>";
	                $html.='<td style="text-align: left; font-size: 12px; ">';
	                $html.='&nbsp;Nombre: '.$_nombre_proveedores.'</td>';
	                $html.='</tr>';
	                $html.= "<tr>";
	                $html.='<td style="text-align: left; font-size: 12px; ">';
	                $html.='&nbsp;Retencion: '.$_retencion_ccomprobantes.'</td>';
	                $html.='</tr>';
	                $html.= "<tr>";
	                $html.='<td style="text-align: left; font-size: 12px; ">';
	                $html.='&nbsp;La cantidad de: '.$_valor_ccomprobantes.'</td>';
	                $html.='</tr>';	                
	                $html.='</table>';	
	                
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th colspan="12" style="text-align: left; height:30px; font-size: 13px;" ><b>&nbsp;CONCEPTO: </b>'.$_concepto_ccomprobantes.'';
	                $html.="</tr>";
	                
	                if(!empty($resultSetDetalle)){
	                  
	                    $html.= "<tr>";
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Centro</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Cuenta</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Descripción</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">NIM</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Debe</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Haber</th>';
	                    $html.='</tr>';
	                    
	                    $i=0; $valor_total_db1=0; $valor_total_vista=0;
	                    
	                    
                    foreach ($resultSetDetalle as $res){
                        
                       $valor_total_db=$res->debe_dcomprobantes;
                       $valor_total_vista=$valor_total_vista+$valor_total_db;
                       $valor_total_db1=$res->haber_dcomprobantes;
                       $valor_total_vista1=$valor_total_vista1+$valor_total_db1;
	                    
                       $html.= "<tr>";
	                 
    	                $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->descripcion_dcomprobantes.'</td>';
    	                $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->codigo_plan_cuentas.'</td>';
    	                $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->descripcion_dcomprobantes.'</td>';
    	                $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->descripcion_dcomprobantes.'</td>';
    	                $html.='<td colspan="2" style="text-align: right; font-size: 13px;">'.$res->debe_dcomprobantes.'</td>';
    	                $html.='<td colspan="2" style="text-align: right; font-size: 13px;">'.$res->haber_dcomprobantes.'</td>';
    	                $html.='</tr>';
    	                $valor_total_db=0;
    	                $valor_total_db1=0;
	                
	                }
	             
	                    $valor_total_vista1 = $valor_total_vista= number_format($valor_total_vista, 2, '.', ',');	 
	                    
    	                $html.='</table>';
    	                $html.='<p style="text-align: left; font-size: 13px;"><b>&nbsp; PICHINCHA CH Nº: </b>'.$_numero_cheque_ccomprobantes.' &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>COTZ:</b> '.$_retencion_ccomprobantes.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>TOTAL:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$valor_total_vista.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$valor_total_vista1.'';
    	                $html.="<table style='width: 100%; margin-top:50px;' border=1 cellspacing=0>";
    	                $html.='<tr>';
    	                $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Elaborado por:</th>';
    	                $html.='<th colspan="4" style="text-align:center; font-size: 13px;">Es Conforme:</th>';
    	                $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Visto Bueno:</th>';
    	                $html.='<th colspan="2" style="text-align:center; font-size: 13px;">Recibi Conforme:</th>';
    	                $html.='</tr>';
    	                $html.='<tr>';
    	                $html.='<td colspan="4" style="text-align:center; font-size: 13px; height:70px;" valign="bottom;">'.$_nombre_usuarios.'</td>';
    	                $html.='<td colspan="4" style="text-align:center; font-size: 13px; height:70px;" valign="bottom;">CONTADOR</td>';
    	                $html.='<td colspan="2" style="text-align:center; font-size: 13px; height:70px;" valign="bottom;">GERENTE</td>';
    	                $html.='<td colspan="2" style="text-align:center; font-size: 13px; height:70px;" valign="bottom;">---------------------------</td>';
    	                
    	                $html.='</tr>';
    	                $html.='</table>';
	                
	                }
	                
	                
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