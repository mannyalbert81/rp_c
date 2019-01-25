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
	    session_start();
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
	
	
	
	public function insertar_temporal_compras(){
	    
	    session_start();
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $producto_id = (isset($_REQUEST['id_productos'])&& $_REQUEST['id_productos'] !=NULL)?$_REQUEST['id_productos']:0;
	    
	    $cantidad = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:0;
	    
	    
	    if($_id_usuarios!='' && $producto_id>0){
	        
	        $_session_id = session_id();
	        
	        //para insertado de temp
	        $temp_compras = new TempComprasModel();
	        $funcion = "ins_temp_compras";
	        $parametros = "'$_id_usuarios',
		    				   '$producto_id',
                               '$cantidad',
                               '$_session_id' ";
	        /*nota estado de temp no esta insertado por el momento*/
	        $temp_compras->setFuncion($funcion);
	        $temp_compras->setParametros($parametros);
	        $resultado=$temp_compras->Insert();
	        
	        $this->trae_temporal($_id_usuarios);
	        
	    }
	}
	
	
	
	public function trae_temporal($id_usuario = null){
	    
	    
	    $page =  (isset($_REQUEST['page'])&& $_REQUEST['page'] !=NULL)?$_REQUEST['page']:1;
	    
	    $id_usuario =  isset($_SESSION['id_usuarios'])?$_SESSION['id_usuarios']:null;
	    
	    if($id_usuario==null){ session_start(); $id_usuario=$_SESSION['id_usuarios'];}
	    
	    
	    
	    if($id_usuario != null)
	    {
	        /* consulta a la BD */
	        
	        $temp_compras = new TempComprasModel();
	        
	        $col_temp=" productos.id_productos,
                    grupos.nombre_grupos,
                    productos.codigo_productos,
                    productos.nombre_productos,
                    temp_compras.id_temp_compras,
                    temp_compras.cantidad_temp_compras";
	        
	        $tab_temp = "public.temp_compras INNER JOIN public.productos ON productos.id_productos = temp_compras.id_productos
                    INNER JOIN  public.grupos ON grupos.id_grupos = productos.id_grupos AND temp_compras.id_usuarios= '$id_usuario'";
	        
	        $where_temp = "1 = 1";
	        
	        
	        $resultSet=$temp_compras->getCantidad("*", $tab_temp, $where_temp);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$temp_compras->getCondicionesPag($col_temp, $tab_temp, $where_temp, "temp_compras.id_temp_compras", $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        $html="";
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query_compras" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:250px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_temporal' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Grupo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Codigo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            
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
	                $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cantidad_temp_compras.'</td>';
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="#" onclick="eliminar_producto('.$res->id_temp_compras.')" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Productos</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	    
	}
	
	/***
	 * mod: compras,
	 * title: para cancelar la accion de compras
	 * return: retorna otra vista 
	 */
	public function cancelarcompra(){
	    
	    session_start();
	    
	    $id_usuario = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	    
	    if($id_usuario>0){
	        
	        $_session_id = session_id();
	        
	        //para eliminado de temp
	        $temp_compras = new TempComprasModel();
	        
	        $where = "id_usuarios = $id_usuario ";
	        $resultado=$temp_compras->deleteById($where);
	        
	        $this->redirect("MovimientosInv","compras");
	    }
	}
	
	/**
	 * mod:compras
	 * title: para isertar compras
	 * retrun: json de respuesta
	 */
	
	public function inserta_compras(){
	    
	    session_start();
	    
	    $id_usuarios = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	    $id_rol = (isset($_SESSION['id_rol']))?$_SESSION['id_rol']:0;
	    
	    $movimientosInvCabeza = new MovimientosInvCabezaModel();
	    
	    /*valores de la vista*/
	    $_numero_compra = (isset($_POST['numero_compra']))?$_POST['numero_compra']:'';
	    $_fecha_compra = (isset($_POST['fecha_compra']))?$_POST['fecha_compra']:'';
	    $_cantidad_compra = (isset($_POST['cantidad_compra']))?$_POST['cantidad_compra']:'';
	    $_importe_compra = (isset($_POST['importe_compra']))?$_POST['importe_compra']:'';
	    $_numero_factura_compra = (isset($_POST['numero_factura_compra']))?$_POST['numero_factura_compra']:'';
	    $_numero_autorizacion_compra = (isset($_POST['numero_autorizacion_factura']))?$_POST['numero_autorizacion_factura']:'';
	    $_subtotal_12_compra = (isset($_POST['subtotal_12_compra']))?$_POST['subtotal_12_compra']:'';
	    $_subtotal_0_compra = (isset($_POST['subtotal_0_compra']))?$_POST['subtotal_0_compra']:'';
	    $_iva_compra = (isset($_POST['iva_compra']))?$_POST['iva_compra']:'';
	    $_descuento_compra = (isset($_POST['descuento_compra']))?$_POST['descuento_compra']:'';
	    $_estado_compra = (isset($_POST['estado_compra']))?$_POST['estado_compra']:0;
	    
	    //$id_rol = (isset($_SESSION['id_rol']))?$_SESSION['id_rol']:0;
	    
	    /*raise*/
	    //id consecutivo consultar ?
	    $_id_consecutivo = 0;
	    //numero movimiento consultar ?
	    $_numero_movimiento = 0;
	    
	    /*para variables de la funcion*/
	    $razon_movimientos="compra de productos";
	    
	    $funcion = "ins_movimientos_inv_cabeza";
	    $parametros = "'$id_usuarios','$_id_consecutivo','$_numero_compra','$razon_movimientos',
                       '$_fecha_compra', '$_cantidad_compra','$_importe_compra','$_numero_factura_compra',
                       '$_numero_autorizacion_compra','$_subtotal_12_compra','$_subtotal_0_compra',
                       '$_iva_compra','$_descuento_compra','$_estado_compra'";
	    
	    /*$movimientosInvCabeza->setFuncion($funcion);
	    $movimientosInvCabeza->setParametros($parametros);
	    $resultset = $movimientosInvCabeza->insert();*/
	    
	    $razon_movimientos="compra de productos";
	    
	    $funcion = "fn_agrega_compra";
	    $parametros = "'$id_usuarios','$_id_consecutivo','$_numero_compra','$razon_movimientos',
                       '$_fecha_compra', '$_cantidad_compra','$_importe_compra','$_numero_factura_compra',
                       '$_numero_autorizacion_compra','$_subtotal_12_compra','$_subtotal_0_compra',
                       '$_iva_compra','$_descuento_compra','$_estado_compra'";
	    
	    $movimientosInvCabeza->setFuncion($funcion);
	    $movimientosInvCabeza->setParametros($parametros);
	    $resultset = $movimientosInvCabeza->llamafuncion();
	    
	    print_r($resultset); 
	    
	}
	
	public function eliminar_producto(){
	    
	    session_start();
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $solicitud_temp_id = (isset($_REQUEST['id_temp_compras'])&& $_REQUEST['id_temp_compras'] !=NULL)?$_REQUEST['id_temp_compras']:0;
	    
	    if($_id_usuarios!='' && $solicitud_temp_id>0){
	        
	        $_session_id = session_id();
	        
	        //para eliminado de temp
	        $temp_compras = new TempComprasModel();
	        
	        $where = "id_usuarios = $_id_usuarios AND id_temp_compras = $solicitud_temp_id ";
	        $resultado=$temp_compras->deleteById($where);
	        
	        $this->trae_temporal($_id_usuarios);
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
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_solicitud(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_solicitud(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_productos_solicitud(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_solicitud(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_solicitud(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_productos_solicitud($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_solicitud(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	
	
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
	
	public function InsertarCompra(){
	    
	    session_start();
	    $resultado = null;
	    $temp_compras = null;
	    $temp_compras =new TempComprasModel();
	    $movimientos_inv_cabeza = null;
	    $movimientos_inventario = new MovimientosInvModel();
	    $movimientos_inv_cabeza = new MovimientosInvCabezaModel();
	    $consecutivos = new ConsecutivosModel();
	    
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        if (isset ($_POST["razon_solicitud"]))
	        {
	            
	            $_id_usuarios   = $_SESSION["id_usuarios"];
	            $_razon_solicitud      = $_POST['razon_solicitud'];
	            
	            date_default_timezone_set('America/Guayaquil');
	            $fechaActual = date('Y-m-d');
	            
	            
	            
	            $resultConsecutivos = $consecutivos->getBy("tipo_documento_consecutivos='SOLICITUD' AND modulo_documento_consecutivos = 'INVENTARIO MATERIALES'");
	            $numero_consecutivos = $resultConsecutivos[0]->numero_consecutivos;
	            $_id_consecutivos = $resultConsecutivos[0]->id_consecutivos;
	            
	            
	            
	            $funcion = "ins_movimientos_inv_cabeza";
	            $parametros = "'$_id_usuarios',
		    				   '$_id_consecutivos',
		    				   '$numero_consecutivos',
		    	               '$_razon_solicitud',
		    	               '$fechaActual',
                                '0',
                                '0','0','0','0','0','0','0','0'";
	            
	            $movimientos_inv_cabeza->setFuncion($funcion);
	            $movimientos_inv_cabeza->setParametros($parametros);
	            $resultadoinsert=$movimientos_inv_cabeza->Insert();
	            
	            
	            
	            
	            
	            $resultInvCabeza = $movimientos_inv_cabeza->getBy("id_usuarios='$_id_usuarios'  AND id_consecutivos='$_id_consecutivos' AND numero_movimientos_inv_cabeza = '$numero_consecutivos'");
	            $id_movimientos_inv_cabeza = $resultInvCabeza[0]->id_movimientos_inv_cabeza;
	            
	            
	            $actualizado = $consecutivos->UpdateBy("numero_consecutivos = numero_consecutivos + 1 ","consecutivos","tipo_documento_consecutivos='SOLICITUD' AND modulo_documento_consecutivos = 'INVENTARIO MATERIALES'");
	            
	            
	            
	            if($id_movimientos_inv_cabeza>0){
	                
	                
	                
	                $col_temp = "temp_solicitud.id_temp_solicitud,
                          temp_solicitud.id_usuario_temp_solicitud,
                          temp_solicitud.id_producto_temp_solicitud,
                          temp_solicitud.cantidad_temp_solicitud,
                          temp_solicitud.sesion_php_temp_solicitud,
                          temp_solicitud.estado_temp_solicitud,
                          temp_solicitud.creado";
	                
	                $tab_temp="public.temp_solicitud";
	                
	                $where_temp="1=1 AND
                                temp_solicitud.id_usuario_temp_solicitud='$_id_usuarios'";
	                
	                $resultTemp = $temp_solicitud->getCondiciones($col_temp,$tab_temp,$where_temp,"temp_solicitud.id_temp_solicitud");
	                
	                if(!empty($resultTemp)){
	                    
	                    $funcion = "ins_movimientos_inv_detalle";
	                    
	                    foreach ($resultTemp as $res){
	                        
	                        $id_producto_temp_solicitud = $res->id_producto_temp_solicitud;
	                        $cantidad_temp_solicitud = $res->cantidad_temp_solicitud;
	                        
	                        
	                        $valor_producto= 0;
	                        $valor_total = 0;
	                        
	                        
	                        $parametros = "'$id_movimientos_inv_cabeza',
		    				   '$id_producto_temp_solicitud',
		    				   '$cantidad_temp_solicitud',
		    	               '$valor_producto',
		    	               '$valor_total'";
	                        
	                        $movimientos_inv_detalle->setFuncion($funcion);
	                        $movimientos_inv_detalle->setParametros($parametros);
	                        $resultado=$movimientos_inv_detalle->Insert();
	                    }
	                    
	                }
	                
	                
	                $where200 = "id_usuario_temp_solicitud='$_id_usuarios'";
	                $resultado=$temp_solicitud->deleteById($where200);
	                
	                
	                
	            }
	            
	            
	            
	            $this->redirect("SolicitudCabeza", "index");
	        }
	        
	    }else{
	        
	        $error = TRUE;
	        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	        
	        $this->view("Login",array(
	            "resultSet"=>"$mensaje", "error"=>$error
	        ));
	        
	        
	        die();
	        
	    }
	}
	
	
	
	
}
?>