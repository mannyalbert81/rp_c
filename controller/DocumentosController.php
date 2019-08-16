	<?php

    class DocumentosController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index5(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new ControladoresModel();
	        $nombre_controladores = "Documentos";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_GestionDocumental("Documentos",array(
	                ""=>""
	            ));
	            
	        }
	        else
	        {
	            $this->view("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso a consultar una solicitud de prestamo."
	                
	            ));
	            
	        }
	        
	        
	    }
	    else
	    {
	        $error = TRUE;
	        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	        
	        $this->view("Login",array(
	            "resultSet"=>"$mensaje", "error"=>$error
	        ));
	        
	        
	        die();
	        
	    }
	    
	}
	
	public function cargaTipoDocuemtos(){
	    
	    require_once 'core/DB_Documentos.php';
	    $db = new DB_Documentos(); 
	    
	    $query = " SELECT id_tipo_documentos,nombre_tipo_documentos FROM tipo_documentos ORDER BY nombre_tipo_documentos";
	    
	    $resulset = $db->enviaquery($query);
	    
	    if(!empty($resulset) && count($resulset)>0){
	        
	        echo json_encode(array('data'=>$resulset));
	        
	    }
	}
	
	public function searchadminsuper(){
	    
	    session_start();
	    
	    require_once 'core/DB_Documentos.php';
	    $db = new DB_Documentos();
	    
	    
	    $resultTipo= $db->getBy("tipo_documentos"," 1=1 ");
	    
	    $where_to="";
	    $columnas = "documentos_legal.id_documentos_legal,  documentos_legal.fecha_documentos_legal, categorias.nombre_categorias, subcategorias.nombre_subcategorias, tipo_documentos.nombre_tipo_documentos, cliente_proveedor.nombre_cliente_proveedor, carton_documentos.numero_carton_documentos, documentos_legal.paginas_documentos_legal, documentos_legal.fecha_desde_documentos_legal, documentos_legal.fecha_hasta_documentos_legal, documentos_legal.ramo_documentos_legal, documentos_legal.numero_poliza_documentos_legal, documentos_legal.ciudad_emision_documentos_legal, soat.cierre_ventas_soat,   documentos_legal.creado , documentos_legal.monto_documentos_legal , documentos_legal.numero_credito_documentos_legal, documentos_legal.monto_documentos_legal, documentos_legal.valor_documentos_legal  ";
	    $tablas   = "public.documentos_legal, public.categorias, public.subcategorias, public.tipo_documentos, public.carton_documentos, public.cliente_proveedor, public.soat";
	    $where    = "categorias.id_categorias = subcategorias.id_categorias AND subcategorias.id_subcategorias = documentos_legal.id_subcategorias AND tipo_documentos.id_tipo_documentos = documentos_legal.id_tipo_documentos AND carton_documentos.id_carton_documentos = documentos_legal.id_carton_documentos AND cliente_proveedor.id_cliente_proveedor = documentos_legal.id_cliente_proveedor   AND documentos_legal.id_soat = soat.id_soat AND documentos_legal.etapa = 2";
	    $id       = "documentos_legal.id_documentos_legal";
	    
	    
	    //$where_to=$where;
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            $where1=" AND (cliente_proveedor.ruc_cliente_proveedor LIKE '".$search."%' OR cliente_proveedor.nombre_cliente_proveedor LIKE '".$search."%' OR carton_documentos.numero_carton_documentos LIKE '".$search."%' OR documentos_legal.numero_poliza_documentos_legal LIKE '".$search."%' OR documentos_legal.ramo_documentos_legal LIKE '".$search."%' OR documentos_legal.ciudad_emision_documentos_legal LIKE '".$search."%' OR tipo_documentos.nombre_tipo_documentos LIKE '".$search."%' OR documentos_legal.numero_credito_documentos_legal LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        
	        $html="";
	        $resultSet=$db->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$db->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        if ($cantidadResult>0)
	        {
	            
	           
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:350px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_solicitud_prestamos_registrados' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"><b>Id</b></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha del Documento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Categoría</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Subcategoría</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Documentos</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cliente/Proveedor</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cartón Documentos</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Número de Crédito</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Monto Documento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor Documento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha de Subida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Vista</th>';
	            
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$res->id_documentos_legal.'</td>';
	                $html.='<td style="font-size: 11px;">'.date($res->fecha_documentos_legal).'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_categorias.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_subcategorias.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_documentos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_cliente_proveedor.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_carton_documentos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_credito_documentos_legal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->monto_documentos_legal.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->valor_documentos_legal.'</td>';
	                $html.='<td style="font-size: 11px;">'.date($res->creado).'</td>';
	                $html.='<td style="font-size: 11px;">';
	                
	                if ($_SESSION["tipo_usuario"]=="usuario_local") {
	                    $html.='<a href="'.IP_INT . $res->id_documentos_legal.'" class="btn btn-warning" target="blank" style="font-size:90%;">Ver</a>';
	                } else {
	                    $html.=' <a href="'.IP_EXT . $res->id_documentos_legal.'" class="btn btn-warning" target="blank" style="font-size:90%;">Ver</a>';
	                }
	                $html.='</td>';
	                $html.='</tr>';
	            }
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section>';
	            $html.='</div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.= "<br>";
	            $html.= "<br>";
	            $html.= "<br>";
	            $html.='<div class="alert alert-warning alert-dismissable">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> No hay datos para mostrar';
	            $html.='</div>';
	            
	        }
	        
	        echo $html;
	        die();
	        
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
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_documentos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_documentos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_documentos(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_documentos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_documentos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_documentos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_documentos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	


	
		}
	
	
	
	
	?>