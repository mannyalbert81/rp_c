<?php

class MovimientosInvController extends ControladorBase{
    
	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
    	//Creamos el objeto usuario
        $movimientos_inventario = new MovimientosInvModel();
    	//Conseguimos todos los usuarios
		
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
	   
	    $this->view_Inventario("Compras",array(
	        
	    ));
	}
	
	/***
	 * mod: compras
	 * title: traer productos para modal
	 * ajax: si
	 * fn_ajax: load_productos
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
                      unidad_medida.nombre_unidad_medida,
                      productos.ult_precio_productos";
	    
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
	        
	        $per_page = 5; //la cantidad de registros que desea mostrar
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
	            $html.='<th style="text-align: left;  font-size: 12px;">U. Medida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Precio U.</th>';	            
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
	                $html.='<td style="font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
	                $html.='<td class="col-xs-1"><div class="pull-right">';
	                $html.='<input type="text" class="form-control input-sm"  id="cantidad_'.$res->id_productos.'" value="1"></div></td>';
	                $html.='<td class="col-xs-2"><div class="pull-right">';
	                $html.='<input type="text" class="form-control input-sm"  id="pecio_producto_'.$res->id_productos.'" value="'.$res->ult_precio_productos.'"></div></td>';
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
	    
	    $precio_unitario = (isset($_REQUEST['precio_u'])&& $_REQUEST['precio_u'] !=NULL)?$_REQUEST['precio_u']:0;
	    
	    
	    if($_id_usuarios!='' && $producto_id>0){
	        
	        $_session_id = session_id();
	        
	        //para insertado de temp
	        $temp_compras = new TempComprasModel();
	        $funcion = "ins_temp_compras";
	        $parametros = "'$_id_usuarios',
		    				   '$producto_id',
                               '$cantidad',
                               '$_session_id',
                               '$precio_unitario' ";
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
                    temp_compras.cantidad_temp_compras,
                    temp_compras.precio_u_temp_compras,
                    temp_compras.total_temp_compras";
	        
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
	            $html.='<th style="text-align: left;  font-size: 12px;">P. Unitario</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">P. Total</th>';
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
	                $html.='<td style="font-size: 11px;">'.$res->precio_u_temp_compras.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->total_temp_compras.'</td>';
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
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Productos</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	    
	    
	}
	/**
	 * mod: compras
	 * title: resultados_temp
	 * ajax: si 
	 * fn_ajax carga_resultados_temp
	 * 
	 */
	public function resultados_temp(){
	    
	    session_start();
	    
	    $id_usuario = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	    
	    if($id_usuario>0){
	        
	        $_session_id = session_id();
	        
	        //para eliminado de temp
	        $temp_compras = new TempComprasModel();
	        
	        $sql_query = "SELECT SUM(total_temp_compras) as \"subtotal12\" ,0.00 as \"subtotal0\", 
                        SUM(total_temp_compras) AS \"subtotal\", 0.00 AS \"descuento\", 
                        TRUNC(sum(total_temp_compras)* 0.12,2) AS \"iva\"";
	        
	        $sql_query.=" FROM public.temp_compras";	        
	       
	        $sql_query .= " WHERE id_usuarios = $id_usuario ";
	        
	        $resultado=$temp_compras->enviaquery($sql_query);
	        
	        //print_r($resultado);
	        
	        $htmlsubtotales="";
	        if(!empty($resultado)){
	            if(is_array($resultado)){
	                if(count($resultado)>0){
	                    
	                    $clasecolumnas='class="col-lg-2 col-md-2"';
	                    $claseinput = 'class="form-control"';
	                    foreach ($resultado as $res){
	                        
	                        $htmlsubtotales = '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_subtotal12" class="control-label">Subtotal 12:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_subtotal12" id="rs_subtotal12" type="text" value="'.$res->subtotal12.'" readonly/>';
	                        $htmlsubtotales .= '</div>';
	                        $htmlsubtotales .= '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_subtotal0" class="control-label">Subtotal 0:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_subtotal0" id="rs_subtotal0" type="text" value="'.$res->subtotal0.'" readonly />';
	                        $htmlsubtotales .= '</div>';
	                        $htmlsubtotales .= '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_subtotal" class="control-label">Subtotal:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_subtotal" id="rs_subtotal" type="text" value="'.$res->subtotal.'" readonly />';
	                        $htmlsubtotales .= '</div>';
	                        $htmlsubtotales .= '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_descuento" class="control-label">Descuento:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_descuento" id="rs_descuento" type="text" value="'.$res->descuento.'" readonly />';
	                        $htmlsubtotales .= '</div>';
	                        $htmlsubtotales .= '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_iva" class="control-label">I.V.A 12:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_iva" id="rs_iva" type="text" value="'.$res->iva.'"  readonly />';
	                        $htmlsubtotales .= '</div>';
	                        $htmlsubtotales .= '<div '.$clasecolumnas.'>';
	                        $htmlsubtotales .= '<label for="rs_total" class="control-label">Total:</label>';
	                        $htmlsubtotales .= '<input '.$claseinput.' name="rs_total" id="rs_total" type="text" value="'.($res->subtotal+$res->iva).'" readonly />';
	                        $htmlsubtotales .= '</div>';
	                    }
	                    
	                    
	                    //echo json_encode($resultado);
	                    
	                }
	            }
	            
	            echo $htmlsubtotales;
	        }
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
	        
	        $this->redirect("MovimientosInv","IngresoMateriales");
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
	    
	    // se valida por cantidad si tiene en la tabla temp_compras
	    if($_cantidad_compra>0){
	        
	        
	        
	    }
	    
	    /*raise*/
	    //id consecutivo consultar ?
	    $_id_consecutivo = 0;
	    //numero movimiento consultar ?
	    $_numero_movimiento = 0;
	    
	    /*para variables de la funcion*/	   
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
	    
	    if(!empty($resultset)){
	        echo "es array";
	    }else{
	        echo "no es array";
	    }
	    
	}
	
	/**
	 * mod: compras
	 * title: insertacompra
	 * ajax: si 
	 * desc: insertar por ajax medinate una funcion desde la pg
	 * return: json
	 */
	public function insertacompra(){
	    
	    //inicia session
	    session_start();
	    //creacion de objeto de modelo
	    $movimientos_inventario = new MovimientosInvCabezaModel();
	    
	    $resultSet=array();
	    
	    $validacion = false;
	   
	    $id_rol= $_SESSION['id_rol'];
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $nombre_controladores = "MovimientosProductosCabeza";	        
	        $resultPer = $movimientos_inventario->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            $validacion = true;
	        
	        }
	    }
	    
	    if(!isset($_POST['action']) && $_POST['action']!='')
	    {
	        return ;
	    }
	    
	    if($validacion){
	        
	        $id_usuarios = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	        
	        /*valores de la vista*/	       
	        $_fecha_compra = (isset($_POST['fecha_compra']))?$_POST['fecha_compra']:'';
	        $_numero_factura_compra = (isset($_POST['numero_factura_compra']))?$_POST['numero_factura_compra']:'';
	        $_numero_autorizacion_compra = (isset($_POST['numero_autorizacion_factura']))?$_POST['numero_autorizacion_factura']:'';
	        $_id_proveedores = (isset($_POST['id_proveedor']))?$_POST['id_proveedor']:0;
	        $_cantidad_compra = (isset($_POST['cantidad_compra']))?$_POST['cantidad_compra']:0;
	        
	        
	        //raise --verificar como es el ingreso de del estado a la compra 
	        $_estado_compra = (isset($_POST['estado_compra']))?$_POST['estado_compra']:'';
	        
	       
	        //se valida si hay productos en temp
	        if($_cantidad_compra>0){
	            
	            /*para variables de la funcion*/
	            $razon_movimientos="compra de productos";
	            
	            $funcion = "fn_agrega_compra";
	           
	            $parametros = "'$id_usuarios','$_id_proveedores','$_fecha_compra','$_numero_factura_compra',
                       '$_numero_autorizacion_compra','$_estado_compra'";
	            
	            $movimientos_inventario->setFuncion($funcion);
	            $movimientos_inventario->setParametros($parametros);
	            
	            $resultset = $movimientos_inventario->llamafuncion();
	            
	             
	            if(!empty($resultset)){
	                if(is_array($resultset) && count($resultset)>0){
	                    
	                    if((int)$resultset[0]->fn_agrega_compra >0 ){
	                       echo json_encode(array('success'=>1,'mensaje'=>'Compra Realizada'));
	                    }else if((int)$resultset[0]->fn_agrega_compra == 0){
	                        echo json_encode(array('success'=>0,'mensaje'=>'Error en la compra'));
	                    }
	                    
	                }
	            }
	            
	        }
	        
	    }
	    
	  
	}
	
	/**
	 * mod: compras
	 * title: busca proveedores por medio de autocomplete
	 * ret: dato json
	 */
	public function busca_proveedor(){
	    
	    $movimientosInvCabeza = new MovimientosInvCabezaModel();
	    
	    $columnas = "proveedores.id_proveedores, 
                  proveedores.nombre_proveedores, 
                  proveedores.identificacion_proveedores";
	    
	   
	    
	    $_busqueda = (isset($_REQUEST['term'])&& $_REQUEST['term'] !=NULL)?$_REQUEST['term']:'';
	    
	    if($_busqueda!=''){
	        
	        $where = " (proveedores.nombre_proveedores LIKE  '$_busqueda%' OR  proveedores.identificacion_proveedores LIKE  '$_busqueda%')";
	        
	        $resultset = $movimientosInvCabeza->getCondiciones($columnas,"public.proveedores",$where,"proveedores.nombre_proveedores");
	        
	        $respuesta = array();
	        
	        if(!empty($resultset)){
	            
	            if(count($resultset)>0){
	                
	                foreach ($resultset as $res){
	                    $_cproveedor = new stdClass;
	                    $_cproveedor->id=$res->id_proveedores;
	                    $_cproveedor->value=$res->identificacion_proveedores;
	                    $_cproveedor->label=$res->identificacion_proveedores.', '.$res->nombre_proveedores;
	                    $_cproveedor->nombre=$res->nombre_proveedores;
	                    
	                   /* $_cproveedor=array('id'=>$res->id_proveedores,
	                                   'value'=>$res->identificacion_proveedores,
	                                   'label'=>$res->identificacion_proveedores.', '.$res->nombre_proveedores,
	                                   'nombre'=>$res->nombre_proveedores);*/
	                    
	                    $respuesta[] = $_cproveedor;
	                }	                
	                
	                echo json_encode($respuesta);
	            }
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	        
	    }else{
	       // $respuesta = array(array('id'=>0,'value'=>'no-llego'));
	        echo '[{"id":0,"value":"sin datos"}]';
	    }
	    
	    ///echo json_encode($respuesta);
	    
	    
	    //
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
	

	
	/**
	 * mod: salidas
	 * title: indexsalida
	 * desc: redirecciona a la vista de salidas
	 * return: void
	 */	
	public function indexsalida(){
	    
	    session_start();
	    $this->view_Inventario("SalidasProductos",array());
	    
	}
	
	/**
	 * mod: salidas
	 * title: carga_solicitud
	 * desc: busca la solicitudes pendientes
	 * return: html
	 */
	public function carga_solicitud(){
	    
	    session_start();
	    
	    $salidas = null; $salidas = new MovimientosInvCabezaModel();
	    
	    /*variables que vienes de peticion ajax*/
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['buscador'])&& $_REQUEST['buscador'] !=NULL)?$_REQUEST['buscador']:'';
	    $page =  (isset($_REQUEST['page'])&& $_REQUEST['page'] !=NULL)?$_REQUEST['page']:1;
	    
	    if($action == 'ajax')
	    {
	        /* consulta a la BD */
	        
	        $col_salidas="movimientos_inv_cabeza.id_movimientos_inv_cabeza, 
                      usuarios.nombre_usuarios, 
                      usuarios.id_usuarios, 
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza, 
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza, 
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	        
	        $tab_salidas = "public.movimientos_inv_cabeza, 
                      public.usuarios, 
                      public.consecutivos";
	        
	        $where_salidas = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SOLICITUD' 
                      AND estado_movimientos_inv_cabeza='PENDIENTE'";
	        
	        
	        if(!empty($search)){
	            
	            $where_busqueda=" AND (usuarios.nombre_usuarios LIKE '".$search."%' OR CAST(movimientos_inv_cabeza.numero_movimientos_inv_cabeza AS VARCHAR) LIKE '".$search."%')";
	            
	            $where_salidas.=$where_busqueda;
	        }
	        
	        $resultSet=$salidas->getCantidad("*", $tab_salidas, $where_salidas);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$salidas->getCondicionesPag($col_salidas, $tab_salidas, $where_salidas, "movimientos_inv_cabeza.id_movimientos_inv_cabeza", $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        $html="";
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:180px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_salidas' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Solicitante</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">No. Solicitud</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Solicitud</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            //$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=MovimientosInv&action=versolicitud&id_movimiento='.$res->id_movimientos_inv_cabeza.'"  class="btn bg-gray-active" title="ver solicitud" style="font-size:65%;"><i class="fa fa-folder-open "></i>Ver</a></span></td>';
	                
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,'carga_solicitud').'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Solicitudes Pendientes</b>';
	            $html.='</div>';
	            $html.='</div>';
	            
	        }
	        
	        
	        echo $html;
	        
	    }
	}
	
	/**
	 * mod: salidas
	 * title: carga_solicitud_entregada
	 * desc: busca la solicitudes rechazadas
	 * return: html
	 */
	public function carga_solicitud_entregada(){
	    
	    session_start();
	    
	    $salidas = null; $salidas = new MovimientosInvCabezaModel();
	    
	    /*variables que vienes de peticion ajax*/
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['buscador'])&& $_REQUEST['buscador'] !=NULL)?$_REQUEST['buscador']:'';
	    $page =  (isset($_REQUEST['page'])&& $_REQUEST['page'] !=NULL)?$_REQUEST['page']:1;
	    
	    if($action == 'ajax')
	    {
	        /* consulta a la BD */
	        
	        $col_salidas="movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                      usuarios.nombre_usuarios,
                      usuarios.id_usuarios,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	        
	        $tab_salidas = "public.movimientos_inv_cabeza,
                      public.usuarios,
                      public.consecutivos";
	        
	        $where_salidas = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SALIDA'
                      AND estado_movimientos_inv_cabeza='APROBADA'";
	        
	        
	        if(!empty($search)){
	            
	            $where_busqueda=" AND (usuarios.nombre_usuarios LIKE '".$search."%' OR CAST(movimientos_inv_cabeza.numero_movimientos_inv_cabeza AS VARCHAR) LIKE '".$search."%')";
	            
	            $where_salidas.=$where_busqueda;
	        }
	        
	        $resultSet=$salidas->getCantidad("*", $tab_salidas, $where_salidas);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$salidas->getCondicionesPag($col_salidas, $tab_salidas, $where_salidas, "movimientos_inv_cabeza.id_movimientos_inv_cabeza", $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        $html="";
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:180px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_salidas' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Aprobado Por</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">No Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=MovimientosInv&action=reporte_solicitud_aprobada&id_movimientos_inv_cabeza='.$res->id_movimientos_inv_cabeza.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,'carga_solicitud_entregada').'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Solicitud Entregada</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	}
	
	/**
	 * mod: salidas
	 * title: carga_solicitud_rechazada
	 * desc: busca la solicitudes rechazada
	 * return: html
	 */
	public function carga_solicitud_rechazada(){
	    
	    session_start();
	    
	    $salidas = null; $salidas = new MovimientosInvCabezaModel();
	    
	    /*variables que vienes de peticion ajax*/
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['buscador'])&& $_REQUEST['buscador'] !=NULL)?$_REQUEST['buscador']:'';
	    $page =  (isset($_REQUEST['page'])&& $_REQUEST['page'] !=NULL)?$_REQUEST['page']:1;
	    
	    if($action == 'ajax')
	    {
	        /* consulta a la BD */
	        
	        $col_salidas="movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                      usuarios.nombre_usuarios,
                      usuarios.id_usuarios,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	        
	        $tab_salidas = "public.movimientos_inv_cabeza,
                      public.usuarios,
                      public.consecutivos";
	        
	        $where_salidas = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SALIDA'
                      AND estado_movimientos_inv_cabeza='RECHAZADA'";
	        
	        
	        if(!empty($search)){
	            
	            $where_busqueda=" AND (usuarios.nombre_usuarios LIKE '".$search."%' OR CAST(movimientos_inv_cabeza.numero_movimientos_inv_cabeza AS VARCHAR ) LIKE '".$search."%')";
	            
	            $where_salidas.=$where_busqueda;
	        }
	        
	        $resultSet=$salidas->getCantidad("*", $tab_salidas, $where_salidas);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$salidas->getCondicionesPag($col_salidas, $tab_salidas, $where_salidas, "movimientos_inv_cabeza.id_movimientos_inv_cabeza", $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        $html="";
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:11px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:180px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_salidas_rechazada' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Encargado</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">No Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Salida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	           
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->estado_movimientos_inv_cabeza.'</td>';
	                $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=MovimientosInv&action=reporte_solicitud_rechazada&id_movimientos_inv_cabeza='.$res->id_movimientos_inv_cabeza.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,'carga_solicitud').'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Solicitud Rechazada</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        
	    }
	}
	
	/**
	 * mod: salidas
	 * title: versolicitud
	 * desc: aprobar/rechazar solicitudes
	 * return: void::view
	 * update 2019-07-16
	 */
	public function versolicitud(){
	    
	    session_start();
	    
	    $salidas = null; $salidas= new MovimientosInvCabezaModel();
	    
	    $id_movimiento = (isset($_REQUEST['id_movimiento']))?$_REQUEST['id_movimiento']:0;
	    
	    if($id_movimiento>0){
	        
	        $col_solicitud="movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                      usuarios.nombre_usuarios,
                      usuarios.id_usuarios,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	        
	        $tab_solicitud = "public.movimientos_inv_cabeza,
                      public.usuarios,
                      public.consecutivos";
	        
	        $where_solicitud = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SOLICITUD'
                      AND movimientos_inv_cabeza.id_movimientos_inv_cabeza=$id_movimiento";
	        
	        $resultsolicitud = $salidas->getCondiciones($col_solicitud,$tab_solicitud,$where_solicitud,"id_movimientos_inv_cabeza");
	        
	        $temp_salida = null; $temp_salida = new InvTempSalidaModel();
	        
	        $funcion = "fn_agrega_salida";
	        $parametros = "'$id_movimiento'";
	        
	        $temp_salida->setFuncion($funcion);
	        
	        $temp_salida->setParametros($parametros);
	        
	        $resultado = $temp_salida->llamafuncion();
	        
	        $insertado =0;
	        
	        if(!empty($resultado)){
	            if(is_array($resultado) && count($resultado)>0){
	                
	                $insertado = (int)$resultado[0]->fn_agrega_salida;
	                
	            }
	        }
	        
	        if($insertado>0){
	            
	            $col_detalle="grupos.nombre_grupos, 
                      grupos.id_grupos, 
                      unidad_medida.nombre_unidad_medida, 
                      productos.codigo_productos, 
                      productos.marca_productos, 
                      productos.nombre_productos, 
                      productos.ult_precio_productos, 
                      inv_temp_salida.cantidad_temp_salida, 
                      inv_temp_salida.id_temp_salida, 
                      inv_temp_salida.id_movimientos_inv_cabeza,
                      (SELECT saldos_f_saldo_productos FROM saldo_productos
				        WHERE id_productos = productos.id_productos) \"disponible\"";
	            
	            $tab_detalle = "public.inv_temp_salida, 
                      public.productos, 
                      public.grupos, 
                      public.unidad_medida";
	            
	            $where_detalle = "productos.id_productos = inv_temp_salida.id_productos AND
                      grupos.id_grupos = productos.id_grupos AND
                      unidad_medida.id_unidad_medida = productos.id_unidad_medida
                      AND inv_temp_salida.id_movimientos_inv_cabeza = $id_movimiento";
	            
	            $resultdetalle = $salidas->getCondiciones($col_detalle,$tab_detalle,$where_detalle,"productos.nombre_productos");
	            
	            
	            $this->view_Inventario('AprobarSalidas',array(
	                'resultsolicitud'=>$resultsolicitud,'resultdetalle'=>$resultdetalle
	            ));
	            
	        }else{
	            
	            $this->redirect('MovimientosInv','indexsalida');
	            
	        }
	        
	        
	       
	    }else{
	        
	        $this->redirect('MovimientosInv','indexsalida');	        
	        
	    }
	    
	   
	}
	
	public function GetDetalleSolicitud(){
	    
	    $Movimientos = new MovimientosInvModel();
	    $error = "";
	    $respuesta = array();
	    
	    try{
	        
	        $id_movimiento = $_POST['id_movimientos'];
	        $error = error_get_last();
	        
	        if( !empty($error) )
	            throw new Exception("Viariables no Definidas ".$error['message']);
	            
	        
	        $columnas ="grupos.nombre_grupos,
                      grupos.id_grupos,
                      unidad_medida.nombre_unidad_medida,
                      productos.codigo_productos,
                      productos.marca_productos,
                      productos.nombre_productos,
                      productos.ult_precio_productos,
                      inv_temp_salida.cantidad_temp_salida,
                      inv_temp_salida.id_temp_salida,
                      inv_temp_salida.id_movimientos_inv_cabeza,
                      inv_temp_salida.estado_temp_salida,
                      (SELECT saldos_f_saldo_productos FROM saldo_productos
				        WHERE id_productos = productos.id_productos) \"disponible\"";
	        
	        $tablas = "public.inv_temp_salida,
                      public.productos,
                      public.grupos,
                      public.unidad_medida";
	        
	        $where = "productos.id_productos = inv_temp_salida.id_productos AND
                      grupos.id_grupos = productos.id_grupos AND
                      unidad_medida.id_unidad_medida = productos.id_unidad_medida
                      AND inv_temp_salida.id_movimientos_inv_cabeza = $id_movimiento";
	        
	        $id = "productos.nombre_productos";	        
	        
	        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
	        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	        
	        $html="";
	        
	        if($action == 'ajax'){
	            
	            if(!empty($search)){	                
	                
	                $where1=" ";
	                
	                $where_to=$where.$where1;
	            }else{
	                
	                $where_to=$where;
	                
	            }
	            
	            
	            $resultSet=$Movimientos->getCantidad("*", $tablas, $where_to);
	            $cantidadResult=(int)$resultSet[0]->total;
	            
	            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	            
	            $per_page = 10; //la cantidad de registros que desea mostrar
	            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	            $offset = ($page - 1) * $per_page;	            
	            $limit = " LIMIT   '$per_page' OFFSET '$offset'";	            
	            $resultSet=$Movimientos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);	            
	            $total_pages = ceil($cantidadResult/$per_page);
	            
	            
	            if($cantidadResult>0){
	                
	                //$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	                //$html.='<section style="height:180px; overflow-y:scroll;">';
	                $html.= "<table id='tbl_detalles_solicitudes' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	                $html.= "<thead>";
	                $html.= "<tr>";
	                $html.='<th style="text-align: left;  font-size: 12px;">#</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Grupos</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Disponible</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">U.M.</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Precio</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Aprobar</th>';
	                $html.='<th style="text-align: left;  font-size: 12px;">Rechazar</th>';
	            	                
	                $html.='</tr>';
	                $html.='</thead>';
	                $html.='<tbody>';
	                
	                //para trabajar con filas aprobadas pendientes y rechazadas
	                $bgColor="";
	                $dataEstado="";
	                	                
	                $i=0;
	                
	                foreach ($resultSet as $res){
	                    
	                    $dataEstado = $res->estado_temp_salida;
	                    $bgColor ="";
	                    
	                    if($res->estado_temp_salida == 'APROBADO'){
	                        $bgColor = "#ABEBC6";	                        
	                    }
	                    if($res->estado_temp_salida == 'RECHAZADA'){
	                        $bgColor = "#F5B7B1";
	                    }
	                    
	                    $disponible = (is_null( $res->disponible)) ? 0 : (int)$res->disponible; 
	                    
	                    $i++;
	                    $html.='<tr style="background-color:'.$bgColor.'"  data-estado="'.$dataEstado.'" id="tr'.$res->id_temp_salida.'" >';
	                    $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                    $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
	                    $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';	                    
	                    $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
	                    $html.='<td style="font-size: 11px;">'.$disponible.'</td>';
	                    $html .= '<td style="font-size: 12px;"><input type="text" name="psolicitud" style="border: 0; height:22px;" class="form-control input-sm" value="' . $res->cantidad_temp_salida . '" id="cantidad_producto_'.$res->id_temp_salida.'">
                                <input type="hidden" name="pdisponible" id="disponible_producto'.$res->id_temp_salida.'" value="' .$disponible . '" ></td>';
	                    $html.='<td style="font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
	                    $html.='<td style="font-size: 11px;">'.$res->ult_precio_productos.'</td>';
	                    $html.='<td style="font-size: 18px;">
                            <a  onclick="aprobar_producto('.$res->id_temp_salida.')" href="#" class="btn-sm btn-success" style="font-size:65%;"data-toggle="tooltip" title="APROBAR"><i class="fa fa-check-square-o" aria-hidden="true"></i></a></td>';
	                    $html.='<td style="font-size: 18px;">
                            <a onclick="rechazar_producto('.$res->id_temp_salida.')"   href="#" class="btn-sm btn-danger" style="font-size:65%;"data-toggle="tooltip" title="RECHAZAR"><i class="fa fa-remove" aria-hidden="true" ></i></a></td>';
	                   	                   
	                    $html.='</tr>';
	                    	                   
	                }
	                
	                $html.='</tbody>';
	                $html.='</table>';
	                //$html.='</section></div>';
	                ////$html.='<div class="table-pagination pull-right">';
	                //$html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,"").'';
	                //$html.='</div>';
	                
	                
	                
	            }else{
	                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	                $html.='<h4>Aviso!!!</h4> <b> Sin Detalles </b>';
	                $html.='</div>';
	                $html.='</div>';
	            }
	        }
	        
	        $respuesta['tablaHtml']=$html;
	        
	        echo json_encode($respuesta);	        
	        
	    }catch (Exception $ex){
	        
	        echo "<message> Error Grupos. \n". $ex->getMessage()." <message>";
	    }
	}
	
	/***
	 * mod: salidas
	 * title: apruebaproducto
	 * ajax: si
	 * desc: aprueba cantidad de productos de solicitud
	 * return: json
	 */
	public function apruebaproducto(){	   
        
        $fila = (isset($_REQUEST['fila'])&& $_REQUEST['fila'] !=NULL)?$_REQUEST['fila']:0;
        
        if($fila>0){
            
            $id_temp_salida = (isset($_REQUEST['id_temp_salida'])&& $_REQUEST['id_temp_salida'] !=NULL)?$_REQUEST['id_temp_salida']:0;
            
            $cantidad = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:0;
            
            if($id_temp_salida>0){
                
                $error = "";
                $respuesta = array();
                
                try{
                    //para actualizacion de temp
                    $temp_salida = new InvTempSalidaModel();                    
                    $funcion = "fn_inv_movimiento_salidas";
                    $parametros = "$id_temp_salida,$cantidad";
                    
                    $temp_salida->setFuncion($funcion);
                    $temp_salida->setParametros($parametros);
                    
                    $resultado=$temp_salida->llamafuncionPG();
                    
                    $error = error_get_last();                    
                                        
                    if( !empty($error) || is_null($resultado))
                        throw new Exception("Error Funcion ".$error['message']);
                    
                    
                    $respuesta['respuesta']=1;
                    $respuesta['mensaje']=$resultado[0];
                                        
                    echo json_encode($respuesta);
                    
                }catch (Exception $ex){
                    
                    echo 'Error Solicitud \n '.$ex->getMessage().'';
                }
                
                
                
            }else{
                echo '<message>Producto No Seleccionado <message>';
            }
            
            
        }else{
            
            echo '<message>Seleccione una fila<message>';
        }
        
	   
	}
	
	/***
	 * mod: salidas
	 * title: rechazaproducto
	 * ajax: si
	 * desc: aprueba cantidad de productos de solicitud
	 * return: json
	 * update 2019-07-17
	 */
	public function rechazaproducto(){
	    
	    
        $id_temp_salida = (isset($_REQUEST['id_temp_salida'])&& $_REQUEST['id_temp_salida'] !=NULL)?$_REQUEST['id_temp_salida']:0;
         
        if($id_temp_salida>0){
            
            $respuesta = array();
            
            try{
                
                //para actualizacion de temp
                $temp_salida = new InvTempSalidaModel();
                
                $funcion = "fn_inv_movimiento_salidas_r";
                $parametros = "$id_temp_salida";
                
                $temp_salida->setFuncion($funcion);
                $temp_salida->setParametros($parametros);
                
                $resultado=$temp_salida->llamafuncionPG();
                
                $error = error_get_last();
                
                if( !empty($error) || is_null($resultado))
                    throw new Exception("Error Funcion ".$error['message']);
                
                $respuesta['respuesta'] = 1;
                $respuesta['mensaje'] = $resultado[0];                
               
                echo json_encode($respuesta);
                
                
            }catch (Exception $ex){
                echo '<message>Error Solicitud \n'.$ex->getMessage().'<message>';
            }
            
           
            
        }else{
            
            echo '<message>Producto no definido<message>';
        }
	    
	}
	
	
	/***
	 * mod: salida
	 * title: inserta_salida
	 * desc: inserta un nuevo movimiento
	 * return: redirecciona a una vista
	 */
	public function inserta_salida(){
	    
	    //inicia session
	    session_start();
	    //creacion de objeto de modelo
	    $movimientos_inventario = new MovimientosInvCabezaModel();
	    
	    $respuesta=array();
	    
	    $id_rol= $_SESSION['id_rol'];
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $nombre_controladores = "MovimientosProductosCabeza";
	        $resultPer = $movimientos_inventario->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (empty($resultPer)){
	            
	            echo '<message> No tiene permisos <message>';
	            die();
	            
	        }
	    }
	    
	    if(!isset( $_POST['peticion']) || $_POST['peticion'] !='ajax')
	    {
	       echo '<message> Variable no definida  <message>';
	       die();
	       
	    }
	    
	    $error = "";
	    
	    try{
	        
	        $id_usuarios = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	        
	        $_id_movimiento_solicitud = $_POST['id_movimiento_solicitud'];
	        $_tipo_solicitud = $_POST['tipo_solicitud'];
	        
	        //opciones es -> 'APROBAR' 'ANULAR'
	        
	        $error = error_get_last();
	        
	        if( !empty($error) )
	            throw new Exception("Viariables no Definidas ".$error['message']);
	        
            $_fecha_salida = date('Y-m-d');
	        $funcion = "fn_agrega_movimiento_salida";
	        $parametros = "'$_tipo_solicitud','$_id_movimiento_solicitud','$id_usuarios','$_fecha_salida'";
	            
            $movimientos_inventario->setFuncion($funcion);            
            $movimientos_inventario->setParametros($parametros);            
            $resultado = $movimientos_inventario->llamafuncionPG();
            
            $error = error_get_last();
            
            //var_dump($resultado) ; die();
            
            if(is_null($resultado) || !empty($error))
                throw new Exception("Error Funcion ".$error['message']);
            
            $respuestaBd = $resultado[0];
            
            $respuesta['respuesta'] = 1;
            $respuesta['mensaje'] = $respuestaBd;
            
            if (strpos($respuestaBd, 'ERROR') !== false) {
                $respuesta['respuesta'] = 0;                
            }            
            
            echo json_encode($respuesta);	        
	        
	    }catch (Exception $ex){
	        
	        echo '<message> Error Solicitud \n '.$ex->getMessage().'<message>';
	    }
	    	    
	}
	

	/***
	 * mod: solicitudes
	 * title: index_solicitudes
	 * desc: redrecciona a la pagina de solicitudes
	 * return: void 
	 */
	public function index_solicitudes(){
	    
	    //Creamos el objeto usuario
	    $solicitud_cabeza=new SolicitudCabezaModel();
	    $productos=new ProductosModel();
	    $usuarios = null; $usuarios= new UsuariosModel();
	    
	    $resultSet=null;
	    $resultEdit = "";
	    
	    session_start();
	    
	    
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $nombre_controladores = "SolicitudCabeza";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $solicitud_cabeza->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            $resultProdu=null;
	            
	            if (isset ($_GET["id_solicitud_cabeza"])   )
	            {
	                
	                
	                $nombre_controladores = "SolicitudCabeza";
	                $id_rol= $_SESSION['id_rol'];
	                $resultPer = $solicitud_cabeza->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	                
	                if (!empty($resultPer))
	                {
	                    
	                    $_id_productos = $_GET["id_productos"];
	                    $columnas = " id_grupos,
                                     codigo_productos,
                                     marca_productos,
                                     nombre_productos,
                                     descripcion_productos,
                                    unidad_medida_productos,
                                     ult_precio_productos ";
	                    $tablas   = "productos";
	                    $where    = "id_productos = '$_id_productos' ";
	                    $id       = "codigo_productos";
	                    
	                    $resultEdit = $productos->getCondiciones($columnas ,$tablas ,$where, $id);
	                    
	                }
	                else
	                {
	                    $this->view_Inventario("Error",array(
	                        "resultado"=>"No tiene Permisos de Editar Solicitudes"
	                        
	                    ));
	                    
	                    
	                }
	                
	            }else{
	                $_id_usuarios = $_SESSION['id_usuarios'];
	                $resultSet = $usuarios->getBy("id_usuarios = $_id_usuarios");
	            }
	            
	            
	            
	            $this->view_Inventario("Solicitud",array(
	                "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultProdu" =>$resultProdu,
	                
	            ));
	            
	            
	            
	        }
	        else
	        {
	            $this->view_Inventario("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso a Solicitud Cabeza"
	                
	            ));
	            
	            exit();
	        }
	        
	    }
	    else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	}
	
	/***
	 * mod: solicitudes
	 * title: inserta_solicitud
	 * desc: redrecciona a la pagina de solicitudes
	 * return: void/json
	 */
	
	public function reporte_solicitud_aprobada(){
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
	
	
	public function reporte_solicitud_rechazada(){
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
                      AND estado_movimientos_inv_cabeza='RECHAZADA' AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$id_movimientos_inv_cabeza'";
	    
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
	
	
	
	
	public function  generar_reporte_solicitud_aprobada(){
	    
	    session_start();
	    
	    $salidas = new MovimientosInvCabezaModel();
	    $salidas_detalle = new MovimientosInvDetalleModel();
	    
	    
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
	        
	        
	        if(isset($_GET["id_movimientos_inv_cabeza"])){
	            
	            
	            $_id_movimientos_inv_cabeza = $_GET["id_movimientos_inv_cabeza"];
	            
	            
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
                      AND estado_movimientos_inv_cabeza='APROBADA' AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$_id_movimientos_inv_cabeza'";
	            
	            $id="movimientos_inv_cabeza.numero_movimientos_inv_cabeza";
	            
	            $resultSetCabeza=$salidas->getCondiciones($columnas, $tablas, $where, $id);
	            
	            
	            if(!empty($resultSetCabeza)){
	                
	                
	                $_id_movimientos_inv_cabeza     =$resultSetCabeza[0]->id_movimientos_inv_cabeza;
	                $_nombre_usuarios     =$resultSetCabeza[0]->nombre_usuarios;
	                $_numero_movimientos_inv_cabeza     =$resultSetCabeza[0]->numero_movimientos_inv_cabeza;
	                $_fecha_movimientos_inv_cabeza     =$resultSetCabeza[0]->fecha_movimientos_inv_cabeza;
	                $_estado_movimientos_inv_cabeza    =$resultSetCabeza[0]->estado_movimientos_inv_cabeza;
	                $_razon_movimientos_inv_cabeza    =$resultSetCabeza[0]->razon_movimientos_inv_cabeza;
	                $_modificado    =  date("Y-m-d", strtotime($resultSetCabeza[0]->modificado));
	         
	                $columnas1 = "  movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                	                productos.codigo_productos,
                	                productos.nombre_productos,
                	                grupos.id_grupos,
                	                grupos.nombre_grupos,
                	                movimientos_inv_detalle.cantidad_movimientos_inv_detalle,
                	                movimientos_inv_detalle.saldo_f_movimientos_inv_detalle,
                	                movimientos_inv_detalle.saldo_v_movimientos_inv_detalle";
	                
	                $tablas1   = "public.movimientos_inv_detalle,
            	                  public.movimientos_inv_cabeza,
            	                  public.grupos,
            	                  public.productos";
	                
	                
	                
	                $where1    = "movimientos_inv_cabeza.id_movimientos_inv_cabeza = movimientos_inv_detalle.id_movimientos_inv_cabeza AND
                                  productos.id_productos = movimientos_inv_detalle.id_productos AND grupos.id_grupos  = productos.id_grupos AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$_id_movimientos_inv_cabeza'  ";
	                
	                $id1       = "movimientos_inv_cabeza.id_movimientos_inv_cabeza";
	                
	                $resultSetDetalle=$salidas_detalle->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th style="text-align: center; font-size: 25px; ">CAPREMCI</br>';
	                $html.='<p style="text-align: center; font-size: 13px; "> Av. Baquerico Moreno E-9781 y Leonidas Plaza';
	                $html.='<p style="text-align: center; font-size: 18px; ">Solicitud N°: &nbsp;'.$_numero_movimientos_inv_cabeza.'</br>';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp;  &nbsp;  &nbsp; &nbsp; Fecha de solicitud: &nbsp; &nbsp; &nbsp; '.$_fecha_movimientos_inv_cabeza.'';
	                $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; ESTADO: '.$_estado_movimientos_inv_cabeza.'&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fecha de aprobación:&nbsp; '.$_modificado.'';
	                $html.='</tr>';
	                $html.='</table>';
	                
	                $html.='<p style="text-align: left; font-size: 13px; "><b>&nbsp; NOMBRE Solicitante: </b>'.$_nombre_usuarios.'';
	                
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th colspan="12" style="text-align: left; height:30px; font-size: 13px;" ><b>&nbsp;CONCEPTO:  &nbsp;'.$_razon_movimientos_inv_cabeza.'';
	                $html.="</th>";
	                $html.="</tr>";
	                $html.='</table>';
	                
	                if(!empty($resultSetDetalle)){
	                    
	                    $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                    
	                    $html.= "<tr>";
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Código</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Grupo</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Nombre Producto</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Cantidad</th>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    foreach ($resultSetDetalle as $res)
	                    {
	                        $html.= "<tr>";	                        
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->codigo_productos.'</td>';
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->nombre_grupos.'</td>';
	                        $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->nombre_productos.'</td>';
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->cantidad_movimientos_inv_detalle.'</td>';
	                        $html.='</tr>';
	                        
	                    }
	                    $html.='</table>';
	                    
	                    
	                    
	                }
	                
	                
	                
	            }
	            
	            
	            
	            $this->report("SolicitudAprobada",array( "resultSet"=>$html));
	            die();
	            
	        }
	        
	        
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	}
	
	
	public function  generar_reporte_solicitud_rechazada(){
	    
	    session_start();
	    
	    $salidas = new MovimientosInvCabezaModel();
	    $salidas_detalle = new MovimientosInvDetalleModel();
	    
	    
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
	        
	        
	        if(isset($_GET["id_movimientos_inv_cabeza"])){
	            
	            
	            $_id_movimientos_inv_cabeza = $_GET["id_movimientos_inv_cabeza"];
	            
	            
	            $columnas="movimientos_inv_cabeza.id_movimientos_inv_cabeza,
                      usuarios.nombre_usuarios,
                      usuarios.id_usuarios,
                      movimientos_inv_cabeza.razon_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza";
	            
	            $tablas = "public.movimientos_inv_cabeza,
                      public.usuarios,
                      public.consecutivos";
	            
	            $where = "usuarios.id_usuarios = movimientos_inv_cabeza.id_usuarios AND
                      consecutivos.id_consecutivos = movimientos_inv_cabeza.id_consecutivos
                      AND nombre_consecutivos='SALIDA'
                      AND estado_movimientos_inv_cabeza='RECHAZADA'AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$_id_movimientos_inv_cabeza'";
	            
	            $id="movimientos_inv_cabeza.numero_movimientos_inv_cabeza";
	            
	            $resultSetCabeza=$salidas->getCondiciones($columnas, $tablas, $where, $id);
	            
	            $html='';
	            $_numero_movimientos_inv_cabeza='';
	            $_estado_movimientos_inv_cabeza='';
	            $_fecha_movimientos_inv_cabeza='';
	            
	            $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: center; font-size: 25px; ">CAPREMCI</br>';
	            $html.='<p style="text-align: center; font-size: 13px; "> Av. Baquerico Moreno E-9781 y Leonidas Plaza';
	            $html.='<p style="text-align: center; font-size: 18px; ">Solicitud N°: &nbsp;'.$_numero_movimientos_inv_cabeza.'</br>';
	            $html.='<p style="text-align: left; font-size: 13px; ">  &nbsp; ESTADO: '.$_estado_movimientos_inv_cabeza.'&nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Fecha: '.$_fecha_movimientos_inv_cabeza.'';
	            $html.='</tr>';
	            
	            $html.='</table>';
	            
	            
	            if(!empty($resultSetCabeza) && count($resultSetCabeza)>0){
	                
	                
	                
	                $_id_movimientos_inv_cabeza            =   $resultSetCabeza[0]->id_movimientos_inv_cabeza;
	                $_nombre_usuarios                      =   $resultSetCabeza[0]->nombre_usuarios;
	                $_numero_movimientos_inv_cabeza        =   $resultSetCabeza[0]->numero_movimientos_inv_cabeza;
	                $_fecha_movimientos_inv_cabeza         =   $resultSetCabeza[0]->fecha_movimientos_inv_cabeza;
	                $_estado_movimientos_inv_cabeza        =   $resultSetCabeza[0]->estado_movimientos_inv_cabeza;
	                $_razon_movimientos_inv_cabeza         =   $resultSetCabeza[0]->razon_movimientos_inv_cabeza;
	                
	                $columnas1 = "  movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                	                productos.codigo_productos,
                	                productos.nombre_productos,
                	                grupos.id_grupos,
                	                grupos.nombre_grupos,
                	                movimientos_inv_detalle.cantidad_movimientos_inv_detalle,
                	                movimientos_inv_detalle.saldo_f_movimientos_inv_detalle,
                	                movimientos_inv_detalle.saldo_v_movimientos_inv_detalle";
	                
	                $tablas1   = "public.movimientos_inv_detalle,
            	                  public.movimientos_inv_cabeza,
            	                  public.grupos,
            	                  public.productos";
	                
	                
	                
	                $where1    = "movimientos_inv_cabeza.id_movimientos_inv_cabeza = movimientos_inv_detalle.id_movimientos_inv_cabeza AND
                                  productos.id_productos = movimientos_inv_detalle.id_productos AND grupos.id_grupos  = productos.id_grupos AND movimientos_inv_cabeza.id_movimientos_inv_cabeza='$_id_movimientos_inv_cabeza'  ";
	                
	                $id1       = "movimientos_inv_cabeza.id_movimientos_inv_cabeza";
	                
	                $resultSetDetalle=$salidas_detalle->getCondiciones($columnas1, $tablas1, $where1, $id1);
	                
	                //comienza dibujar pdf
	             
	                
	                
	                $html.='<p style="text-align: left; font-size: 13px; "><b>&nbsp; NOMBRE: </b>'.$_nombre_usuarios.'';
	                
	                $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                $html.= "<tr>";
	                $html.='<th colspan="12" style="text-align: left; height:30px; font-size: 13px;" ><b>&nbsp;CONCEPTO:  &nbsp;'.$_razon_movimientos_inv_cabeza.'';
	                $html.="</th>";
	                $html.="</tr>";
	                $html.='</table>';
	                
	                if(!empty($resultSetDetalle)){
	                    
	                    $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
	                    
	                    $html.= "<tr>";
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Código</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Grupos</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Nombre Producto</th>';
	                    $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Cantidad</th>';
	                    $html.='</tr>';
	                    
	                    
	                    
	                    foreach ($resultSetDetalle as $res)
	                    {
	                        $html.= "<tr>";
	                        
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->codigo_productos.'</td>';
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->nombre_grupos.'</td>';
	                        $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->nombre_productos.'</td>';
	                        $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->cantidad_movimientos_inv_detalle.'</td>';
	                        $html.='</tr>';
	                        
	                    }
	                    $html.='</table>';
	                    
	                    
	                    
	                }
	                
	                
	                
	            }
	            
	           
	            $this->report("SolicitudRechazada",array( "resultSet"=>$html));
	            die();
	            
	        }
	        
	        
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	    
	    
	    
	}
	
	
	
	public function inserta_solicitud(){
	    
	    session_start();
	    $resultado = null;
	    $temp_solicitud = null;
	    $temp_solicitud =new TempSolicitudModel();
	    $movimientos_inv_cabeza = null;
	    $movimientos_inv_cabeza = new MovimientosInvCabezaModel();
	    $movimientos_inv_detalle = null;
	    $movimientos_inv_detalle = new MovimientosInvDetalleModel();
	    $consecutivos = new ConsecutivosModel();
	    
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        if (isset ($_POST["razon_solicitud"]))
	        {
	            
	            $_id_usuarios      = $_SESSION["id_usuarios"];
	            $_razon_solicitud  = $_POST['razon_solicitud'];
	            $_estado_solicitud = 'PENDIENTE';
	            
	            date_default_timezone_set('America/Guayaquil');
	            $fechaActual = date('Y-m-d');
	            
	            $funcion = 'fn_agrega_movimiento_solicitud';
	            $parametros = "'$_id_usuarios',
		    	               '$_razon_solicitud',
		    	               '$fechaActual',
                               '$_estado_solicitud'";
	            
	            
	            $movimientos_inv_cabeza->setFuncion($funcion);
	            $movimientos_inv_cabeza->setParametros($parametros);
	            $resultadoinsert=$movimientos_inv_cabeza->llamafuncion();
	            
	            //para solicitud por ajax
	            
	            if(isset($_POST['peticion']) && $_POST['peticion'] == 'ajax' ){
	                
	                if(!empty($resultadoinsert)){
	                    
	                    if($resultadoinsert[0]->fn_agrega_movimiento_solicitud>0)
	                    {
	                       echo json_encode(array('status'=>'1','mensaje'=>'Solicitud Ingresada'));
	                    }else{
	                        echo json_encode(array('status'=>'0','mensaje'=>'Solicitud Rechazada'));
	                    }
	                }
	               
	            }else{
	                
	                $this->redirect("SolicitudCabeza", "index");
	            }
	            
	            
	          }
	            
	           
	      
	    }else{
	        
	        $error = TRUE;
	        $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	        
	        $this->view_Inventario("Login",array(
	            "resultSet"=>"$mensaje", "error"=>$error
	        ));
	        
	        
	        die();
	        
	    }
	}
	
	/***
	 * mod: salidas
	 * title: generarptsalida
	 * return: void::pdf
	 */
	public function generaRptSalidas(){
	    
	       
	    require dirname(__FILE__).'\..\view\fpdf\pdfInventario.php';	
	    
	    $pdf = new pdf();
	    $pdf->AddPage('P','A4');
	    $pdf->setFont('Arial','B',14);
	    $pdf->Cell(0,5,'Listado de salidas Aprobadas',1);
	    $pdf->SetAutoPageBreak(true, 20);
	    
	    $pdf->AddPage('P','A4');
	    
	    
	    $pdf->Output();
	}
	
	
	/***
	 * mod: ingreso Materiales
	 * title: IngresoMateriales
	 * return: void::view
	 */
	public function IngresoMateriales(){
	    
	    session_start();
	    //parametros
	    $this->view_Inventario('IngresoMateriales', array());
	}
	
	/***
	 * mod: ingreso materiales
	 * title: buscaFacturas
	 * return: void::html
	 */
	public function buscaFacturas(){
	    /*dc 2019-03-21*/
	    if(isset($_POST['peticion'])){
	        echo 'sin conexion';
	        return;
	    }
	    
	    $page = (isset($_REQUEST['page']))?isset($_REQUEST['page']):1;
	    
	    $movimientos = new MovimientosInvModel();
	    
	    /*consulta que busca la relacion comprobantes con movimientos*/
	    /*si existe en movimientos no puede volverse a buscar*/
	    $query = "SELECT 
            ccomprobantes.id_ccomprobantes,ccomprobantes.fecha_ccomprobantes,ccomprobantes.id_tipo_comprobantes,
            tipo_comprobantes.nombre_tipo_comprobantes,ccomprobantes.numero_ccomprobantes,
            ccomprobantes.referencia_doc_ccomprobantes,ccomprobantes.concepto_ccomprobantes,
            ccomprobantes.valor_ccomprobantes
            FROM ccomprobantes
            INNER JOIN tipo_comprobantes
            ON tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes
            AND tipo_comprobantes.nombre_tipo_comprobantes = 'EGRESOS'
            LEFT JOIN movimientos_inv_cabeza
            ON movimientos_inv_cabeza.id_ccomprobantes = ccomprobantes.id_ccomprobantes
            WHERE movimientos_inv_cabeza.id_ccomprobantes IS NULL";
	    
	    $buscador = (isset($_POST['search']))?$_POST['search']:"";
	    
	    if($buscador != ""){
	        
	        
	        $query = "SELECT
            ccomprobantes.id_ccomprobantes,ccomprobantes.fecha_ccomprobantes,ccomprobantes.id_tipo_comprobantes,
            tipo_comprobantes.nombre_tipo_comprobantes,ccomprobantes.numero_ccomprobantes,
            ccomprobantes.referencia_doc_ccomprobantes,ccomprobantes.concepto_ccomprobantes,
            ccomprobantes.valor_ccomprobantes
            FROM ccomprobantes
            INNER JOIN tipo_comprobantes
            ON tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes
            AND tipo_comprobantes.nombre_tipo_comprobantes = 'EGRESOS'
            LEFT JOIN movimientos_inv_cabeza
            ON movimientos_inv_cabeza.id_ccomprobantes = ccomprobantes.id_ccomprobantes
            WHERE movimientos_inv_cabeza.id_ccomprobantes IS NULL 
            AND ccomprobantes.referencia_doc_ccomprobantes ILIKE '%".$_POST['search']."%'";
	        
	    }

	    $rsResultado = $movimientos->enviaquery($query);
	    
	    $cantidad = 0;
	    $html = "";
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
	        $cantidad = count($rsResultado);	        
	    }
	    
	    $query .= " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet = $movimientos->enviaquery($query);
	    
	    $total_pages = ceil($cantidad/$per_page);
	    
	    if($cantidad>0)
	    {
	        
	        $html.='<div class="pull-left" style="margin-left:11px;">';
	        $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidad.'</span>';
	        $html.='<input type="hidden" value="'.$cantidad.'" id="total_query" name="total_query"/>' ;
	        $html.='</div>';
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<section style="height:300px; overflow-y:scroll;">';
	        $html.= "<table id='tabla_salidas_rechazada' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Num Comprobante</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Referencia (FACTURA)</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">...</th>';	    
	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        foreach ($resultSet as $res)
	        {
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->fecha_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->numero_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->referencia_doc_ccomprobantes.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->concepto_ccomprobantes.'</td>';
	            $html.='<td align="right" style="font-size: 11px;"> $'.$res->valor_ccomprobantes.'</td>';	            
	            $html.='<td style="color:#000000;"><span class="pull-right"><a data-toggle="tooltip" title="Registrar Factura" href="index.php?controller=MovimientosInv&action=registrarFactura&codigo='.$res->id_ccomprobantes.'" ><i class="fa fa-chain-broken" aria-hidden="true"></i></a></span></td>';
	            $html.='</tr>';
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        $html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginatemultiple("index.php", $page, $total_pages, $adjacents,'buscaFacturas').'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	        $html.='<h4>Aviso!!!</h4> <b> SIN FACTURAS A REGISTRAR </b>';
	        $html.='</div>';
	        $html.='</div>';
	    }
	    
	    echo $html;
	    
	}
	
	/***
	 * mod: registro Materiales
	 * title: registrarFactura
	 * return void:view
	 */
	public function registrarFactura(){
	    
	    session_start();
	    
	    $registro = null; $registro= new MovimientosInvCabezaModel();
	    
	    $id_comprobante = (isset($_REQUEST['codigo']) && $_REQUEST['codigo'] != null)?$_REQUEST['codigo']:0;
	    
	    
	    if($id_comprobante>0){
	        
	        $columas_comp = "ccomprobantes.id_ccomprobantes,ccomprobantes.fecha_ccomprobantes,ccomprobantes.id_tipo_comprobantes,
        	        tipo_comprobantes.nombre_tipo_comprobantes,ccomprobantes.numero_ccomprobantes,
        	        ccomprobantes.referencia_doc_ccomprobantes,ccomprobantes.concepto_ccomprobantes,
        	        ccomprobantes.valor_ccomprobantes";
	        
	        $tablas_comp = "ccomprobantes
        	        INNER JOIN tipo_comprobantes
        	        ON tipo_comprobantes.id_tipo_comprobantes = ccomprobantes.id_tipo_comprobantes
        	        AND tipo_comprobantes.nombre_tipo_comprobantes = 'EGRESOS'";
	        
	        $where_comp = "ccomprobantes.id_ccomprobantes=$id_comprobante";
	        
	        
	        $rscomprobante = $registro->getCondiciones($columas_comp,$tablas_comp,$where_comp,"ccomprobantes.id_ccomprobantes");
	        
	       
	        
	            
	            $this->view_Inventario('RegistrarFactura',array(
	                'rscomprobante'=>$rscomprobante
	            ));
	         
	        
	    }else{
	        
	        $this->redirect('MovimientosInv','IngresoMateriales');
	        
	    }
	}
	
	/***
	 * mod: ingreso materiales
	 * title: insertaDetalleFactura
	 * return: json
	 */
	public function insertaDetalleFactura(){
	    
	    session_start();
	    
	    $_id_usuarios = $_SESSION['id_usuarios'];
	    
	    $producto_id = (isset($_REQUEST['id_productos'])&& $_REQUEST['id_productos'] !=NULL)?$_REQUEST['id_productos']:0;
	    
	    $cantidad = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:0;
	    
	    $precio_unitario = (isset($_REQUEST['precio_u'])&& $_REQUEST['precio_u'] !=NULL)?$_REQUEST['precio_u']:0;
	    
	    $comprobante_id = (isset($_REQUEST['comprobante_id'])&& $_REQUEST['comprobante_id'] !=NULL)?$_REQUEST['comprobante_id']:0;
	    
	    
	    if($_id_usuarios!='' && $producto_id>0){
	        
	        //$_session_id = session_id();
	        
	        //para insertado de temp
	        $tempFactura = new TempFacturaModel();
	        
	        $funcion = "ins_temp_factura";
	        
	        /*para implementarlo luego*/
	        $descuento = 0.00;	        
	        
	        $parametros = "'$_id_usuarios',
	    				   '$producto_id',
                           '$comprobante_id',
                           '$precio_unitario',
                            '$descuento',
                           '$cantidad'";
	        
	        //print_r($parametros); die();
	        /*nota estado de temp no esta insertado por el momento*/
	        $tempFactura->setFuncion($funcion);
	        $tempFactura->setParametros($parametros);
	        
	        $resultado=$tempFactura->llamafuncion();
	        
	        $respuesta = 0;
	        
	        if(!empty($resultado) && count($resultado)>0){	            
	            
	            foreach ($resultado[0] as $k=>$v){
	                $respuesta=$v;
	            }
	        }
	        
	        echo  json_encode(array('mensaje'=>$respuesta));
	        
	    }
	    
	}
	
	/***
	 * mod: ingresoMateriales
	 * title: traerfacturas
	 * return: view::html
	 */
	public function detalleFactura(){
	    
	    session_start();
	    
	    $id_usuarios = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
	    
	    $comprobante_id = (isset($_POST['comprobante_id']))?$_POST['comprobante_id']:0;
	    
	    $page = (isset($_POST['page']))?$_POST['page']:0;
	    
	    $tempFactura = new TempFacturaModel();
	    
	    $col_temp = 'con_temp_factura.id_temp_factura,
    	    con_temp_factura.id_ccomprobantes,
    	    usuarios.id_usuarios,
    	    usuarios.nombre_usuarios,
    	    productos.codigo_productos,
    	    productos.nombre_productos,
    	    con_temp_factura.cantidad_temp_factura,
    	    con_temp_factura.precio_temp_factura,
    	    (con_temp_factura.precio_temp_factura * con_temp_factura.cantidad_temp_factura) "temp_total"';
	    
	    $tab_temp = 'con_temp_factura
    	    INNER JOIN productos
    	    ON productos.id_productos = con_temp_factura.id_productos
    	    INNER join usuarios
    	    ON usuarios.id_usuarios = con_temp_factura.id_usuarios';
	    
	    $where_temp = "usuarios.id_usuarios = $id_usuarios
	        and con_temp_factura.id_ccomprobantes = $comprobante_id";
	    
	    
	    $resultSet=$tempFactura->getCantidad("*", $tab_temp, $where_temp);
	    
	    $cantidadResult=(int)$resultSet[0]->total;
	    
	    $per_page = 10; //la cantidad de registros que desea mostrar
	    $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	    $offset = ($page - 1) * $per_page;
	    
	    $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	    
	    $resultSet=$tempFactura->getCondicionesPag($col_temp, $tab_temp, $where_temp, "con_temp_factura.id_temp_factura", $limit);
	    
	    $total_pages = ceil($cantidadResult/$per_page);
	    
	    /*para enviar el total de la sumatoria del temporal*/
	    
	    $querysuma = "SELECT SUM(cantidad_temp_factura * precio_temp_factura) \"total\"
                        FROM con_temp_factura
                        WHERE id_usuarios = $id_usuarios
                        AND id_ccomprobantes = $comprobante_id";
	        
	    $rsSumaDetalle = $tempFactura->enviaquery($querysuma);
	    
	    $sumaDetalle = 0.00;
	    	    
	    if(!empty($rsSumaDetalle) && count($rsSumaDetalle)>0){
	        
	        $sumaDetalle = $rsSumaDetalle[0]->total;
	    }
	    
	    $html="";
	    if($cantidadResult>0)
	    {
	        
	        $html.='<div class="pull-left" style="margin-left:11px;">';
	        $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	        $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query_factura" name="total_query"/>' ;
	        $html.='<input type="hidden" value="'.$sumaDetalle.'" id="total_suma_detalle" name="total_query"/>' ;
	        $html.='</div>';
	        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	        $html.='<section style="height:250px; overflow-y:scroll;">';
	        $html.= "<table id='tabla_temporal' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Codigo</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">P. Unitario</th>';
	        $html.='<th style="text-align: left;  font-size: 12px;">P. Total</th>';
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
	            $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->cantidad_temp_factura.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->precio_temp_factura.'</td>';
	            $html.='<td style="font-size: 11px;">'.$res->temp_total.'</td>';
	            $html.='<td style="font-size: 18px;" align="right" ><a href="#" onclick="eliminar_producto('.$res->id_temp_factura.')" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></td>';
	            
	            $html.='</tr>';
	        }
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        $html.='</section></div>';
	        $html.='<div class="table-pagination pull-right">';
	        $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents).'';
	        $html.='</div>';
	        
	        
	        
	    }else{
	            
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Sin Resultados Productos</b>';
	            $html.='</div>';
	            $html.='</div>';
	        
	    }
	    
	    
	    echo $html;	    
	
	}
	
	/***
	 * mod: ingresoMateriales
	 * title: buscarProductosRegistroMaterial
	 * return: view::html
	 * desc: busca productos para ingresar en temp_factura
	 */
	public function buscarProductosRegistroMaterial(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $productos = null; $productos = new ProductosModel();
	    $where_to="";
	    $columnas = "productos.id_productos,
                      grupos.nombre_grupos,
                      productos.codigo_productos,
                      productos.nombre_productos,
                      unidad_medida.nombre_unidad_medida,
                      productos.ult_precio_productos";
	    
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
	        
	        $per_page = 5; //la cantidad de registros que desea mostrar
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
	            $html.='<th style="text-align: left;  font-size: 12px;">U. Medida</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Precio U.</th>';
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
	                $html.='<td style="font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
	                $html.='<td class="col-xs-1"><div class="pull-right">';
	                $html.='<input type="text" class="form-control input-sm"  id="cantidad_'.$res->id_productos.'" value="1"></div></td>';
	                $html.='<td class="col-xs-2"><div class="pull-right">';
	                $html.='<input type="text" class="form-control input-sm"  id="pecio_producto_'.$res->id_productos.'" value="'.$res->ult_precio_productos.'"></div></td>';
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="#" onclick="add_producto('.$res->id_productos.')" class="btn btn-info" style="font-size:65%;"><i class="glyphicon glyphicon-plus"></i></a></span></td>';
	                
	                
	                
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
	 * mod: ingresoMateriales
	 * title: buscarProductosRegistroMaterial
	 * return: view::html
	 * desc: busca productos para ingresar en temp_factura
	 */
	public function eliminaTempFactura(){
	    
	    session_start();
	    
	    $_id_usuarios = (isset($_SESSION['id_usuarios'])&& $_SESSION['id_usuarios'] !=NULL)?$_SESSION['id_usuarios']:0; 
	    
	    $_id_temp_factura = (isset($_REQUEST['id_temp_factura'])&& $_REQUEST['id_temp_factura'] !=NULL)?$_REQUEST['id_temp_factura']:0;
	    
	    if($_id_usuarios >0 && $_id_temp_factura>0){
	        
	        //$_session_id = session_id();
	        
	        //para eliminado de temp
	        $tempFactura= new TempFacturaModel();
	        
	        $where = "id_usuarios = $_id_usuarios AND id_temp_factura = $_id_temp_factura ";
	        $resultado=$tempFactura->deleteById($where);
	        
	        $respuesta=0;
	        
	        if(!empty($resultado) && count($resultado)>0){
	            foreach ($resultado[0] as $k=>$v){
	                $respuesta=$v;
	            }
	        }
	        
	        echo $respuesta;
	    }
	}
	
	public function RegistraFactura(){
	    
	    //inicia session
	    session_start();
	    //creacion de objeto de modelo
	    $movimientos_inventario = new MovimientosInvCabezaModel();	    
	  
	    $id_rol= $_SESSION['id_rol'];
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $nombre_controladores = "MovimientosProductosCabeza";
	        $resultPer = $movimientos_inventario->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (empty($resultPer) || count($resultPer) < 1)
	        {	            
	            echo 'sin permisos';
	            return;
	            
	        }
	    }
	    
	    if(!isset($_POST['peticion']) && $_POST['peticion']!='')
	    {
	        echo 'peticion no soportada';
	        return ;
	    }
	    
	   
	        
        $id_usuarios = (isset($_SESSION['id_usuarios']))?$_SESSION['id_usuarios']:0;
        
        /*valores de la vista*/
        $_fecha_compra = (isset($_POST['fecha_compra'])) ? $_POST['fecha_compra'] : '';
        $_id_comprobantes = (isset($_POST['comprobante_id'])) ? $_POST['comprobante_id'] : 0;
        $_cantidad_compra = (isset($_POST['cantidad_factura'])) ? $_POST['cantidad_factura'] : 0;
        
       
	        
	        //se valida si hay productos en temp
        if($_cantidad_compra>0){
            
            $funcion = "fn_ingresa_factura";
            
            $parametros = "'$id_usuarios','$_id_comprobantes','$_fecha_compra'";            
           
            $movimientos_inventario->setFuncion($funcion);
            $movimientos_inventario->setParametros($parametros);
            
            $resultset = $movimientos_inventario->llamafuncion();
            $respuesta = 0;
            
            if(!empty($resultset) && count($resultset)>0){
                
                foreach ($resultset[0] as $k => $v){
                    $respuesta = $v;
                }
                
            }
            
            echo json_encode(array('mensaje'=>$respuesta));
        
        }
	    
	}
	
	public function notificacionSolicitudes(){
	    
	    $Movimientos = new MovimientosInvModel();
	    
	    $respuesta = array();
	    
	    $query = "SELECT id_movimientos_inv_cabeza
                    FROM movimientos_inv_cabeza
                    WHERE fecha_movimientos_inv_cabeza='2019-07-19' AND razon_movimientos_inv_cabeza ILIKE 'PETICION%'
                    ORDER BY id_movimientos_inv_cabeza
                  ";
	    
	    $rsSolicitudes = $Movimientos->enviaquery($query);
	    
	    $cabeceraNotificacion = '';
	    $detalleNotificacion = '';
	    
	    if(!empty($rsSolicitudes)){
	        
	        $cantidadNotificacion = count($rsSolicitudes);
	        
	        $cabeceraNotificacion = '<li class="header">Tiene '.$cantidadNotificacion.' Solicitudes por Revisar</li>';
	        
	        
	        $html = $cabeceraNotificacion.$detalleNotificacion;
	        
	        $respuesta['respuesta']=1;
	        $respuesta['htmlNotificacion']=$html;
	        $respuesta['cantidadNotificaciones']=$cantidadNotificacion;
	        
	    }
	    
	    echo json_encode($respuesta);
	    
	}
	
}



?>