<?php

class MovimientosInvController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
	    $movimientos_inventario = new MovimientosInvModel();
					//Conseguimos todos los usuarios
	    $resultSet=array();
				
		$resultEdit = "";

		
		session_start();

	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "MovimientosProductosCabeza";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $movimientos_inventario->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_GET["id_movimientos_productos_cabeza"])   )
				{

					$nombre_controladores = "MovimientosProductosCabeza";
					$id_rol= $_SESSION['id_rol'];
					$resultPer = $movimientos_inventario->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
						
					if (!empty($resultPer))
					{
					
					 

					}
					else
					{
						$this->view("Error",array(
								"resultado"=>"No tiene Permisos de Editar Movimientos Productos Cabeza"
					
						));
					
					
					}
					
				}
		
				
				$this->view("Compras",array(
						
			
				));
		
				
				
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Movimientos Productos Cabeza"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	
	/***
	 * mod: compras
	 * title: inicio de compras
	 */
	
	public function compras(){
	    $this->view("Compras",array(
	        
	        
	    ));
	}
	
	/***
	 * mod: compras
	 * title: traer productos para modal
	 * des: buscar productos en la base
	 */

	public function consulta_productos()
	{
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $productos = null; $productos = new ProductosModel();
	    $where_to="";
	    $columnas = "productos.id_productos,
                      grupos.nombre_grupos,
                      productos.codigo_productos,
                      productos.nombre_productos,
                      unidad_medida.nombre_unidad_medida";
	    
	    $tablas = " public.productos,
                      public.grupos,
                      public.unidad_medida";
	    
	    $where    = "grupos.id_grupos = productos.id_grupos AND
                     unidad_medida.id_unidad_medida = productos.id_unidad_medida";
	    
	    $id       = "productos.nombre_productos";
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            $where1=" AND (productos.nombre_productos LIKE '".$search."%' OR productos.codigo_productos LIKE '".$search."%')";
	            
	            $where_to=$where.$where1;
	            
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        
	        $html="";
	        $resultSet=$productos->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 2; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$productos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:300px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Grupo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Codigo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">U. Medida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody >';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
	                $html.='<td class="col-xs-1"><div class="pull-right">';
	                $html.='<input type="text" class="form-control input-sm"  id="cantidad_'.$res->id_productos.'" value="1"></div></td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="#" onclick="agregar_producto('.$res->id_productos.')" class="btn btn-info" style="font-size:65%;"><i class="glyphicon glyphicon-plus"></i></a></span></td>';
	                
	                
	                $html.='</tr>';
	            }
	            $html.='</tbody>';
	            
	            $html.='</table>';
	            $html.='<table><tr>';
	            $html.='<td colspan="7"><span class="pull-right">';
	            $html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,"load_productos").'';
	            $html.='</span>';
	            $html.='</table></tr>';
	            $html.='</section></div>';
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay productos registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	}
	
	
	/***
	 * mod:compras
	 * title: paginatemultiple
	 * des: devuelve navegacion de paginacion en hatml
	 * @param $reload pagina a recargar
	 * @param $page numero de pagina
	 * @param $tpages cantidad de paginas a mostrar
	 * @param $adjacents brecha entre paginas
	 * @param $funcion que funcion recarga en la vista
	 * @return string navegacion paginacion en hmtl
	 */
	public function paginatemultiple($reload, $page, $tpages, $adjacents,$funcion='') {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	
	
}
?>