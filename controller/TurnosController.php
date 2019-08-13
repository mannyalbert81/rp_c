	<?php

    class TurnosController extends ControladorBase{
	public function __construct() {
		parent::__construct();
		
	}
	
	public function index5(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        $controladores = new ControladoresModel();
	        $nombre_controladores = "Turnos";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $controladores->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            
	            
	            
	            $this->view_Turnos("Turnos",array(
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
	public function searchadminsuper(){
	    
	    session_start();
	    
	    require_once 'core/DB_Turnos.php';
	    $db = new DB_Turnos();
	    

	    $where_to="";
	    
	    $columnas = "turnos_tramites.numero_turnos_tramites,
	    afiliado.cedula_afiliado,
	    afiliado.apellidos_afiliado,
	    afiliado.nombres_afiliado,
	    turnos_tramites.id_turnos_tramites,
	    turnos_tramites.creado,
	    turnos_tramites.modificado,
	    estado.nombre_estado,
	    departamentos.nombre_departamentos,
	    empleados.nombres_empleados,
	    empleados.apellidos_empleados,
	    tramites_departamentos.nombre_tramites_departamentos";
	    
	    $tablas=" public.turnos_tramites,
	    public.estado,
	    public.afiliado,
	    public.empleados,
	    public.departamentos,
	    public.tramites_departamentos";
	    
	    $where="estado.id_estado = turnos_tramites.id_estado AND
	    afiliado.id_afiliado = turnos_tramites.id_afiliado AND
	    empleados.id_empleados = turnos_tramites.id_empleados AND
	    departamentos.id_departamentos = turnos_tramites.id_departamentos AND
	    tramites_departamentos.id_tramites_departamentos = turnos_tramites.id_tramites_departamentos";
	    
	    $id="turnos_tramites.creado ";
	    
	    
	    //$where_to=$where;
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (turnos_tramites.numero_turnos_tramites LIKE '".$search."%' OR afiliado.cedula_afiliado LIKE '".$search."%' OR afiliado.apellidos_afiliado LIKE '".$search."%' OR afiliado.nombres_afiliado LIKE '".$search."%'  OR empleados.apellidos_empleados LIKE '".$search."%' OR  tramites_departamentos.nombre_tramites_departamentos LIKE '".$search."%')";
	            
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
	            $html.='<th style="text-align: left;  font-size: 12px;">Ord.</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Trámite</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Departamento</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Tipo Tramite</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Apellido Participe</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Atendió</th>';
	          
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_turnos_tramites.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->creado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_departamentos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_tramites_departamentos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombres_afiliado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellidos_afiliado.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombres_empleados.'</td>';
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
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_turnos(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_turnos(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_turnos(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_turnos(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_turnos(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_turnos($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_turnos(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	

		}
	
	
	
	
	?>