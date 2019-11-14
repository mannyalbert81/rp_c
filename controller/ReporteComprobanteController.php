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
		    
		    $ccomprobantes = new CComprobantesModel();
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
			        
			        /** se aumenta la busqueda por cedula **/
			        $_datos_provedor = (isset($_POST['datos_proveedor'])) ? $_POST['datos_proveedor'] : "";
			         
			        
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
							  tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes";
			        
			        $id="ccomprobantes.numero_ccomprobantes";
			        
			        
			        $where_0 = "";
			        $where_1 = "";
			        $where_2 = "";
			        $where_4 = "";
			        $where_5 = "";
			        
			        
		            if($id_entidades!=0){$where_0=" AND entidades.id_entidades='$id_entidades'";}
			        
			        if($id_tipo_comprobantes!=0){$where_1=" AND tipo_comprobantes.id_tipo_comprobantes='$id_tipo_comprobantes'";}
			        
			        if($numero_ccomprobantes!=""){$where_2=" AND ccomprobantes.numero_ccomprobantes LIKE '%$numero_ccomprobantes%'";}
			   
			        if($fechadesde!="" && $fechahasta!=""){$where_4=" AND  date(ccomprobantes.fecha_ccomprobantes) BETWEEN '$fechadesde' AND '$fechahasta'";}
			        
			        if( strlen($_datos_provedor ) > 0 ){
			            $where_5 = " AND ccomprobantes.id_proveedores in ( ".
			                       "     SELECT id_proveedores FROM proveedores ".
			                             "WHERE identificacion_proveedores = '$_datos_provedor' OR nombre_proveedores = '$_datos_provedor' )";
			        }
			        
			        
			        $where_to  = $where . $where_0 . $where_1 . $where_2. $where_4.$where_5;
			        
			        
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
			            
			            
			            $resultSet=$ccomprobantes->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
			            
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
			                    $html.='<td style="font-size: 11px;"><span class="pull-right"><a href="index.php?controller=ReporteComprobante&action=comprobante_contable_reporte&id_ccomprobantes='.$res->id_ccomprobantes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
			                    
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
	    
	    /** validaciones para sesion **/	    
	    if(empty($cedula_usuarios)){
	        $this->redirect("Usuarios","sesion_caducada");
	    }
	    
	    if( !isset($_GET["id_ccomprobantes"])){
	        
	        echo "DATOS NO RECIBIDOS, IDENTIFICADOR DE COMPROBANTE NO RECONOCIDO"; exit();
	    }
	    
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
                              proveedores.nombre_proveedores";
	    
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
	    
	    $rsCabeza=$ccomprobantes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    if( empty($rsCabeza) ){
	        
	        echo "NO EXISTEN DATOS DE COMPROBANTE"; exit();
	    }
	    
	    $_id_ccomprobantes                     =$rsCabeza[0]->id_ccomprobantes;
	    $_nombre_tipo_comprobantes             =$rsCabeza[0]->nombre_tipo_comprobantes;
	    $_concepto_ccomprobantes               =$rsCabeza[0]->concepto_ccomprobantes;
	    $_nombre_usuarios                      =$rsCabeza[0]->nombre_usuarios;
	    $_nombre_entidades                     =$rsCabeza[0]->nombre_entidades;
	    $_direccion_entidades                  =$rsCabeza[0]->direccion_entidades;
	    $_telefono_entidades                   =$rsCabeza[0]->telefono_entidades;
	    $_ruc_entidades                        =$rsCabeza[0]->ruc_entidades;
	    $_valor_letras                         =$rsCabeza[0]->valor_letras;
	    $_fecha_ccomprobantes                  =$rsCabeza[0]->fecha_ccomprobantes;
	    $_numero_ccomprobantes                 =$rsCabeza[0]->numero_ccomprobantes;
	    $_ruc_ccomprobantes                    =$rsCabeza[0]->ruc_ccomprobantes;
	    $_nombres_ccomprobantes                =$rsCabeza[0]->nombres_ccomprobantes;
	    $_retencion_ccomprobantes              =$rsCabeza[0]->retencion_ccomprobantes;
	    $_valor_ccomprobantes                  =$rsCabeza[0]->valor_ccomprobantes;
	    $_referencia_doc_ccomprobantes         =$rsCabeza[0]->referencia_doc_ccomprobantes;
	    $_numero_cuenta_banco_ccomprobantes    =$rsCabeza[0]->numero_cuenta_banco_ccomprobantes;
	    $_numero_cheque_ccomprobantes          =$rsCabeza[0]->numero_cheque_ccomprobantes;
	    $_observaciones_ccomprobantes          =$rsCabeza[0]->observaciones_ccomprobantes;
	    $_nombre_forma_pago                    =$rsCabeza[0]->nombre_forma_pago;
	    $_nombre_proveedores                   =$rsCabeza[0]->nombre_proveedores;
	    $_descripcion_dcomprobantes            =$rsCabeza[0]->descripcion_dcomprobantes;
	    
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
	    $html.='<td colspan="12" style="text-align: left; height:30px; font-size: 10px;" ><b>CONCEPTO: </b>'.$_concepto_ccomprobantes.'</td>';
	    $html.="</tr>";
	    
	    if(!empty($resultSetDetalle)){
	        
	        
	        $html.= "<tr>";
	        $html.='<th colspan="3" style="text-align: center; font-size: 11px;">Código</th>';
	        $html.='<th colspan="3" style="text-align: center; font-size: 11px;">Cuenta</th>';
	        $html.='<th colspan="3" style="text-align: center; font-size: 11px;">Debe</th>';
	        $html.='<th colspan="3" style="text-align: center; font-size: 11px;">Haber</th>';
	        $html.='</tr>';
	        
	        $i=0; $valor_total_vista=0; $valor_total_vista1=0;
	        
	        
	        foreach ($resultSetDetalle as $res){
	            
	            $valor_total_db=$res->debe_dcomprobantes;
	            $valor_total_vista=$valor_total_vista+$valor_total_db;
	            $valor_total_db1=$res->haber_dcomprobantes;
	            $valor_total_vista1=$valor_total_vista1+$valor_total_db1;
	            
	            $html.= "<tr>";
	            
	            $html.='<td colspan="3" style="text-align: left; font-size: 10px;">'.$res->codigo_plan_cuentas.'</td>';
	            $html.='<td colspan="3" style="text-align: left; font-size: 10px;">'.$res->nombre_plan_cuentas.'</td>';
	            $html.='<td colspan="3" style="text-align: right; font-size: 10px;">'.number_format($res->debe_dcomprobantes, 2, '.', ',').'</td>';
	            $html.='<td colspan="3" style="text-align: right; font-size: 10px;">'.number_format($res->haber_dcomprobantes, 2, '.', ',').'</td>';
	            $html.='</tr>';
	            $valor_total_db=0;
	            $valor_total_db1=0;
	            
	        }
	        $valor_total_vista = $valor_total_vista= number_format($valor_total_vista, 2, '.', ',');
	        $valor_total_vista1 = $valor_total_vista1= number_format($valor_total_vista1, 2, '.', ',');
	        
	        $html.= "<tr>";
	        $html.='<td colspan="3" style="text-align: left; font-size: 10px;"></td>';
	        $html.='<td colspan="3" style="text-align: left; font-size: 10px;"></td>';
	        $html.='<td colspan="3" style="text-align: right; font-size: 10px;">'.$valor_total_vista.'</td>';
	        $html.='<td colspan="3" style="text-align: right; font-size: 10px;">'.$valor_total_vista1.'</td>';
	        $html.='</tr>';
	        
	        
	        $html.='</table>';
	        
	        
	        
	        
	        
	        
	        $html.="<table style='width: 100%; margin-top:50px;' border=1 cellspacing=0>";
	        $html.='<tr>';
	        $html.='<th colspan="4" style="text-align:center; font-size: 11px;">Elaborado por:</th>';
	        $html.='<th colspan="4" style="text-align:center; font-size: 11px;">Es Conforme:</th>';
	        $html.='<th colspan="2" style="text-align:center; font-size: 11px;">Visto Bueno:</th>';
	        $html.='<th colspan="2" style="text-align:center; font-size: 11px;">Recibi Conforme:</th>';
	        $html.='</tr>';
	        $html.='<tr>';
	        $html.='<td colspan="4" style="text-align:center; font-size: 10px; height:70px;" valign="bottom;">'.$_nombre_usuarios.'</td>';
	        $html.='<td colspan="4" style="text-align:center; font-size: 10px; height:70px;" valign="bottom;">CONTADOR</td>';
	        $html.='<td colspan="2" style="text-align:center; font-size: 10px; height:70px;" valign="bottom;">GERENTE</td>';
	        $html.='<td colspan="2" style="text-align:center; font-size: 10px; height:70px;" valign="bottom;">---------------------------</td>';
	        
	        $html.='</tr>';
	        $html.='</table>';
	        
	    }
	    
	    $this->report("Comprobante",array( "resultSet"=>$html));
	    die();
	    
	    
	}
	
	public function  comprobante_contable_reporte(){
	    
	    session_start();
	    $entidades = new EntidadesModel();
	    $ccomprobantes = new CComprobantesModel();
	    $dcomprobantes = new DComprobantesModel();
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
	    
	   
	    $_id_ccomprobantes =  (isset($_REQUEST['id_ccomprobantes'])&& $_REQUEST['id_ccomprobantes'] !=NULL)?$_REQUEST['id_ccomprobantes']:'';
	    
	    
	    $datos_reporte = array();
	    
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
                      ccomprobantes.usuario_usuarios,
                      ccomprobantes.id_modulos,
                      dcomprobantes.descripcion_dcomprobantes,
					  forma_pago.nombre_forma_pago,
                      proveedores.id_proveedores,
                      proveedores.nombre_proveedores,
                      proveedores.identificacion_proveedores";
	    
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
	    
	    $rsdatos = $ccomprobantes->getCondiciones($columnas, $tablas, $where, $id);
	    
	    $datos_reporte['IDCCOMPROBANTE']=$rsdatos[0]->id_ccomprobantes;
	    $datos_reporte['NOMBRETIPOCOMPROBANTE']=$rsdatos[0]->nombre_tipo_comprobantes;
	    $datos_reporte['CONCEPTOCOMPROBANTE']=$rsdatos[0]->concepto_ccomprobantes;
	    $datos_reporte['USUARIO']=$rsdatos[0]->nombre_usuarios;
	    $datos_reporte['NOMBREENTIDADES']=$rsdatos[0]->nombre_entidades;
	    $datos_reporte['DIRECCIONENTIDAD']=$rsdatos[0]->direccion_entidades;
	    $datos_reporte['TELEFONOENTIDAD']=$rsdatos[0]->telefono_entidades;
	    $datos_reporte['RUCENTIDAD']=$rsdatos[0]->ruc_entidades;
	    $datos_reporte['VALORLETRAS']=$rsdatos[0]->valor_letras;
	    $datos_reporte['FECHACOMPROBANTE']=$rsdatos[0]->fecha_ccomprobantes;
	    $datos_reporte['NUMEROCOMPROBANTE']=$rsdatos[0]->numero_ccomprobantes;
	    $datos_reporte['RUCCOMPROBANTE']=$rsdatos[0]->ruc_ccomprobantes;
	    $datos_reporte['NOMBRECOMPROBANTE']=$rsdatos[0]->nombres_ccomprobantes;
	    $datos_reporte['RETENCIONCOMPROBANTE']=$rsdatos[0]->retencion_ccomprobantes;
	    $datos_reporte['VALORCOMPROBANTE'] = number_format($rsdatos[0]->valor_ccomprobantes, 2, '.', ',');
	    $datos_reporte['REFERENCIADOCCOMP']=$rsdatos[0]->referencia_doc_ccomprobantes;
	    $datos_reporte['NUMEROCUENTA']=$rsdatos[0]->numero_cuenta_banco_ccomprobantes;
	    $datos_reporte['NUMEROCHEQUE']=$rsdatos[0]->numero_cheque_ccomprobantes;
	    $datos_reporte['OBSERVACIONES']=$rsdatos[0]->observaciones_ccomprobantes;
	    $datos_reporte['FORMADEPAGO']=$rsdatos[0]->nombre_forma_pago;
	    $datos_reporte['PROVEEDORES']=$rsdatos[0]->nombre_proveedores;
	    $datos_reporte['DESCDCOMPROBANTE']=$rsdatos[0]->descripcion_dcomprobantes;
	    $datos_reporte['NOMBRETIPOCOMPROBANTE']= $rsdatos[0]->nombre_tipo_comprobantes;
	    $datos_reporte['GENERADOCOMPROBANTE']  = $rsdatos[0]->usuario_usuarios;
	    $datos_reporte['MODULO']  = "";
	    $datos_reporte['CONCEPTOEXTCOMPROBANTE']  = "";
	    
	    if( !empty( $datos_reporte['OBSERVACIONES'] ) ){
	        $datos_reporte['OBSERVACIONES'] = "<br>".$datos_reporte['OBSERVACIONES'];
	    }
	    
	    
	    $_id_modulos = $rsdatos[0]->id_modulos;	
	    
	    /** para el modulo */
	    if( !empty( trim($_id_modulos) ) ){
	        
	        $columnas1 = " *";
	        $tablas1   = " modulos";
	        $where1    = " id_modulos = $_id_modulos";
	        $id1       = " id_modulos";	   
	        
	        $rsConsulta1   = $ccomprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);
	        
	        if( !empty($rsConsulta1) ){
	            $datos_reporte['MODULO']  = substr($rsConsulta1[0]->nombre_modulos,0,3).'_';
	        }
	        	        
	    }
	    
	    /** para proveedores **/
	    $_nombre_proveedor = $rsdatos[0]->nombre_proveedores;
	    $_identificacion_proveedor = $rsdatos[0]->identificacion_proveedores;
	    
	    /** para concepto extendido **/
	    $columnas2 = " aa.numero_creditos, aa.fecha_concesion_creditos, aa.plazo_creditos";
	    $tablas2   = " core_creditos aa
	               INNER JOIN ccomprobantes bb on bb.id_ccomprobantes = aa.id_ccomprobantes";
	    $where2    = " bb.id_ccomprobantes = '$_id_ccomprobantes'";
	    $id2       = " bb.id_ccomprobantes";
	    $rsConsulta2   = $ccomprobantes->getCondiciones($columnas2, $tablas2, $where2, $id2);
	   
	    if( !empty( $rsConsulta2 ) ){
	        
	        $datos_reporte['CONCEPTOEXTCOMPROBANTE']  = "<br>"." Numero de credito : ".$rsConsulta2[0]->numero_creditos.
	        ", Fecha de credito otorgado:".$rsConsulta2[0]->fecha_concesion_creditos.
	        ", Con plazo de ".$rsConsulta2[0]->plazo_creditos." cuotas.";
	    }
	   
	    
	    $columnas1 = " plan_cuentas.id_plan_cuentas,
                    plan_cuentas.nombre_plan_cuentas,
                    plan_cuentas.codigo_plan_cuentas,                    
                    plan_cuentas.mayor_auxiliar,
                    dcomprobantes.descripcion_dcomprobantes,
                    dcomprobantes.debe_dcomprobantes,
                    dcomprobantes.haber_dcomprobantes,
                    dcomprobantes.numero_dcomprobantes";
	    
	    $tablas1   = " public.dcomprobantes,
                        public.plan_cuentas";
	    $where1    = "plan_cuentas.id_plan_cuentas = dcomprobantes.id_plan_cuentas AND dcomprobantes.id_ccomprobantes='$_id_ccomprobantes' ";
	    
	    $id1       = "dcomprobantes.id_dcomprobantes";
	    
	    
	    $resultSetDetalle=$dcomprobantes->getCondiciones($columnas1, $tablas1, $where1, $id1);	    
	    
	    $html='';
	    
	    
	    if(!empty($resultSetDetalle)){
	        
	        $html.='<table class="1" >';	       
	        $html.= "<tr>";
	        $html.='<th class="ancho" style="text-align: center; font-size: 11px;">Código</th>';
	        $html.='<th class="ancho" style="text-align: center; font-size: 11px;">Cuenta</th>';
	        $html.='<th class="ancho" style="text-align: center; font-size: 11px;">Debe</th>';
	        $html.='<th class="ancho" style="text-align: center; font-size: 11px;">Haber</th>';
	        $html.='</tr>';
	        
	         $valor_total_vista=0; $valor_total_vista1=0;	        
	        
	        foreach ($resultSetDetalle as $res){
	            
	            //$_id_plan_cuentas = $res->id_plan_cuentas;
	            $_tiene_Auxiliar = $res->mayor_auxiliar;
	            $valor_total_db=$res->debe_dcomprobantes;
	            $valor_total_vista=$valor_total_vista+$valor_total_db;
	            $valor_total_db1=$res->haber_dcomprobantes;
	            $valor_total_vista1=$valor_total_vista1+$valor_total_db1;
	            
	            $html.= "<tr>";
	            
	            $html.='<td class="ancho" style=" text-align: left; font-size: 10px;">'.$res->codigo_plan_cuentas.'</td>';
	            $html.='<td class="ancho" style=" text-align: left; font-size: 10px;">'.$res->nombre_plan_cuentas.'</td>';
	            $html.='<td class="ancho" style=" font-size: 10px;" align="right">$ '.number_format($res->debe_dcomprobantes, 2, '.', ',').'</td>';
	            $html.='<td class="ancho" style=" font-size: 10px;" align="right">$ '.number_format($res->haber_dcomprobantes, 2, '.', ',').'</td>';
	            $html.='</tr>';
	            
	            /** para fila auxiliar mayores **/	            
	            if( $_tiene_Auxiliar == 't' ){
	                
	                $html.= "<tr>";	                
	                $html.='<td class="ancho auxiliar" > &nbsp;&nbsp;&nbsp;&nbsp;'.trim($res->codigo_plan_cuentas,'.').'.'.$_identificacion_proveedor.'</td>';
	                $html.='<td class="ancho auxiliar" > &nbsp;&nbsp;&nbsp;&nbsp;'.$_nombre_proveedor.'</td>';
	                $html.='<td class="ancho auxiliar" ></td>';
	                $html.='<td class="ancho auxiliar" ></td>';
	                $html.='</tr>';
	            }
	            
	            $valor_total_db=0;
	            $valor_total_db1=0;
	            
	        }
	        $valor_total_vista = $valor_total_vista= number_format($valor_total_vista, 2, '.', ',');
	        $valor_total_vista1 = $valor_total_vista1= number_format($valor_total_vista1, 2, '.', ',');
	        
	        $html.= "<tr>";
	        $html.='<td class="ancho" style="text-align: left; font-size: 10px;"></td>';
	        $html.='<td class="ancho" style="text-align: left; font-size: 10px;"></td>';
	        $html.='<td class="bordesup" style="text-align: right; font-size: 10px;"align="right">$ '.$valor_total_vista.'</td>';
	        $html.='<td class="bordesup" style="text-align: right; font-size: 10px;"align="right">$ '.$valor_total_vista1.'</td>';
	        $html.='</tr>';
	        
	       
	       
	        
	    }
	    
	   
	    $html.='</table>';
	    
	    $datos_reporte['DETALLE_COMPROBANTE']= $html;	    
	    
	    $this->verReporte("ComprobanteContableReporte", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
	    
	    
	}
	
	
	}
?>