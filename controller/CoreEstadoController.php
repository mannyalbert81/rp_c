<?php

class CoreEstadoController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $core_estado=new CoreEstadoModel();
					//Conseguimos todos los usuarios
	    $resultSet=$core_estado->getAll("id_estado");
				
		$resultEdit = "";
		
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "CoreEstado";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $core_estado->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_estado"])   )
				{

					$nombre_controladores = "CoreEstado";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $core_estado->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					    $_id_estado = $_GET["id_estado"];
						$columnas = " core_estado.id_estado, 
                                      core_estado.descripcion_estado, 
                                      core_estado.id_estatus";
						$tablas   = "public.core_estado";
						$where    = "core_estado.id_estado = '$_id_estado' "; 
						$id       = "core_estado.id_estado";
							
						$resultEdit = $core_estado->getCondiciones($columnas ,$tablas ,$where, $id);

					}
					else
					{
					    $this->view_Core("Error",array(
								"resultado"=>"No tiene Permisos de Editar el Core Estado"
					
						));
					
					
					}
					
				}
		
				
				$this->view_core("CoreEstado",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
				
				
			}
			else
			{
			    $this->view_Core("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Core Estado"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaCoreEstado(){
			
		session_start();
		$core_estado=new CoreEstadoModel();
		
		

		$nombre_controladores = "CoreEstado";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $core_estado->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		
		//die("llego");
		
			$resultado = null;
			$core_estado=new CoreEstadoModel();
		
			if (isset ($_POST["descripcion_estado"])   )
			if (isset ($_POST["id_estatus"])   )
			        
			{
				
			    $_descripcion_estado = $_POST["descripcion_estado"];
			    $_id_estatus =  $_POST["id_estatus"];
			    //die("llego");
			    if($_id_estado > 0){
					
					$columnas = " descripcion_estado = '$_descripcion_estado',
                                  id_estatus = '$_id_estatus'";
					$tabla = "  public.grupos";
					$where = "id_estado = '$_id_estado'";
					$resultado=$core_estado->UpdateBy($columnas, $tabla, $where);
					
				}else{
					
					$funcion = "ins_core_estado";
					$parametros = " '$_descripcion_estado', '$_id_estatus'";
					$core_estado->setFuncion($funcion);
					$core_estado->setParametros($parametros);
					$resultado=$core_estado->Insert();
				}
				
				
				
		
			}
			$this->redirect("CoreEstado", "index");

		}
		else
		{
		    $this->view_Core("Error",array(
					"resultado"=>"No tiene Permisos de Insertar Core Estado"
		
			));
		
		
		}
		
	}
	
	public function borrarId()
	{
	    
	    session_start();
	    $core_estado=new CoreEstadoModel();
	    $nombre_controladores = "CoreEstado";
	    $id_rol= $_SESSION['id_rol'];
	    $resultPer = $core_estado->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	    
	    if (!empty($resultPer))
	    {
	        if(isset($_GET["id_estado"]))
	        {
	            $id_estado=(int)$_GET["id_estado"];
	            
	            
	            
	            $core_estado->deleteBy("id_estado",$id_estado);
	            
	        }
	        
	        $this->redirect("CoreEstado", "index");
	        
	        
	    }
	    else
	    {
	        $this->view_Core("Error",array(
	            "resultado"=>"No tiene Permisos de Borrar Core estado"
	            
	        ));
	    }
	    
	}
	
	public function consulta_core_estado_activos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	   
	    $estado = null; $estado = new EstadoModel();
	    $where_to="";
	    $columnas = " core_estado.id_estado                                                                                                                                                                                                   _grupos, 
                      core_estado.descripcion_estado, 
                      core_estado.id_estatus";
	    
	    $tablas = "core_estado.grupos INNER JOIN public.estado ON estado.id_estado = grupos.id_estado AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado ='GRUPOS' 
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