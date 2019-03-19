<?php

class ProductosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        session_start();
        
        //Creamos el objeto usuario
        $productos=new ProductosModel();
       
        $columnas = "
                                      productos.id_productos,
                                      grupos.id_grupos,
                                      grupos.nombre_grupos,
                                      unidad_medida.id_unidad_medida,
                                      unidad_medida.nombre_unidad_medida,
                                      productos.codigo_productos,
                                      productos.marca_productos,
                                      productos.nombre_productos,
                                      productos.descripcion_productos,
                                      productos.ult_precio_productos,
                                      productos.creado,
                                      productos.modificado,
                                      bodegas.id_bodegas";
                      $tablas   = "   public.productos,
                                      public.grupos,
                                      public.unidad_medida,
                                      public.bodegas";
        $where    = "  grupos.id_grupos = productos.id_grupos AND
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida AND 
                                       bodegas.id_bodegas = productos.id_bodegas";
        $id       = "productos.id_productos";
        
        $resultSet = $productos->getCondiciones($columnas ,$tablas ,$where, $id);
        
        
        
        $grupos=new GruposModel();
        $resultGrup=$grupos->getAll("nombre_grupos");
        
        $unidad=new UnidadModel();
        $resultUni=$unidad->getAll("nombre_unidad_medida");
        
        $resultEdit = "";
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "Productos";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_productos"])   )
                {
                    
                  
                        
                        $_id_productos = $_GET["id_productos"];
                        $columnas = "
                                      productos.id_productos, 
                                      grupos.id_grupos, 
                                      grupos.nombre_grupos, 
                                      unidad_medida.id_unidad_medida, 
                                      unidad_medida.nombre_unidad_medida, 
                                      productos.codigo_productos, 
                                      productos.marca_productos, 
                                      productos.nombre_productos, 
                                      productos.descripcion_productos, 
                                      productos.ult_precio_productos, 
                                      productos.creado, 
                                      productos.modificado,
                                      bodegas.id_bodegas";
                        $tablas   = "   public.productos, 
                                      public.grupos, 
                                      public.unidad_medida,
                                      public.bodegas";
                        $where    = "  grupos.id_grupos = productos.id_grupos AND 
                                       bodegas.id_bodegas = productos.id_bodegasAND
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida AND productos.id_productos = '$_id_productos'";
                        $id       = "productos.id_productos";
                        
                        $resultEdit = $productos->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view_Inventario("Productos",array(
                    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultGrup"=>$resultGrup, "resultUni"=>$resultUni
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Inventario("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Grupos"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    public function InsertaProductos(){
        
        session_start();
        
        $resultado = null;
        $productos=new ProductosModel();
        
        
        $nombre_controladores = "Productos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $productos->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            //_nombre_categorias character varying, _path_categorias character varying
            if (isset ($_POST["codigo_productos"]) && isset ($_POST["id_grupos"]))
            
            {
                //die('llego');
                $_id_grupos = $_POST["id_grupos"];
                $_id_unidad_medida = $_POST["id_unidad_medida"];
                $_codigo_productos = $_POST["codigo_productos"];
                $_marca_productos = $_POST["marca_productos"];
                $_nombre_productos = $_POST["nombre_productos"];
                $_descripcion_productos = $_POST["descripcion_productos"];
                $_ult_precio_productos = $_POST["ult_precio_productos"];
                $_id_bodegas = $_POST["id_bodegas"];
                
                
                
                if($_id_productos > 0){
                    
                    $columnas = " id_grupos = '$_id_grupos',
                              id_unidad_medida = '$_id_unidad_medida',
							  codigo_productos ='$_codigo_productos',
							  marca_productos = '$_marca_productos',
                              nombre_productos = '$_nombre_productos',
							  descripcion_productos = '$_descripcion_productos',
							  ult_precio_productos = '$_ult_precio_productos',
                              id_bodegas = '$_id_bodegas'";
                    $tabla = "public.productos, 
                              public.grupos, 
                              public.unidad_medida,
                              public.bodegas";
                    $where = "grupos.id_grupos = productos.id_grupos AND
                              bodegas.id_bodegas = productos.id_bodegasAND
                              unidad_medida.id_unidad_medida = productos.id_unidad_medida AND productos.id_productos = '$_id_productos'";
                    $resultado=$productos->UpdateBy($columnas, $tabla, $where);
                    
                }else{
                    
                    $funcion = "ins_productos";
                    $parametros = " '$_id_grupos', '$_id_unidad_medida', '$_codigo_productos', '$_marca_productos', '$_nombre_productos', '$_descripcion_productos', '$_ult_precio_productos', '$_id_bodegas'";
                    $productos->setFuncion($funcion);
                    $productos->setParametros($parametros);
                    $resultado=$productos->Insert();
                }
                
            }
            
            $this->redirect("Productos", "index");
        }
        else
        {
            $this->view_Inventario("Error",array(
                "resultado"=>"No tiene Permisos Para Crear Productos"
                
            ));
            
            
        }
        
        
        //          $this->redirect("Productos", "index");
        
        
    }
    
    public function borrarId()
    {
        
        session_start();
        $productos=new ProductosModel();
        $nombre_controladores = "Productos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $productos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            if(isset($_GET["id_productos"]))
            {
                $id_productos=(int)$_GET["id_productos"];
                
                
                
                $productos->deleteBy(" id_productos",$id_productos);
                
                
            }
            
            $this->redirect("Productos", "index");
            
            
        }
        else
        {
            $this->view_Inventario("Error",array(
                "resultado"=>"No tiene Permisos de Borrar Grupos"
                
            ));
        }
        
    }
    
    
    
   // $productos=new ProductosModel();
    
    public function consulta(){
        
        session_start();
        
        $resultEdit = "";
        $productos=new ProductosModel();
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "Productos";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                
                $columnas = "
                                      productos.id_productos,
                                      grupos.id_grupos,
                                      grupos.nombre_grupos,
                                      unidad_medida.id_unidad_medida,
                                      unidad_medida.nombre_unidad_medida,
                                      productos.codigo_productos,
                                      productos.marca_productos,
                                      productos.nombre_productos,
                                      productos.descripcion_productos,
                                      productos.ult_precio_productos,
                                      productos.creado,
                                      productos.modificado,
                                      bodegas.id_bodegas";
                $tablas   = "   public.productos,
                                      public.grupos,
                                      public.unidad_medida,
                                      public.bodegas";
                $where    = "  grupos.id_grupos = productos.id_grupos AND
                                bodegas.id_bodegas = productos.id_bodegas AND
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida";
                $id       = "productos.id_productos";
                
                $resultSet = $productos->getCondiciones($columnas ,$tablas ,$where, $id);
                
                
                
                if (isset ($_GET["id_productos"])   )
                {
                    
                    
                    
                    $_id_productos = $_GET["id_productos"];
                    $columnas1 = " productos.id_productos,
                      productos.codigo_productos,
                      productos.marca_productos,
                      productos.nombre_productos,
                      productos.descripcion_productos,
                      productos.ult_precio_productos,
                      unidad_medida.id_unidad_medida,
                      unidad_medida.nombre_unidad_medida,
                      movimientos_inv_detalle.cantidad_movimientos_inv_detalle,
                      movimientos_inv_detalle.saldo_f_movimientos_inv_detalle,
                      movimientos_inv_detalle.saldo_v_movimientos_inv_detalle,
                      movimientos_inv_cabeza.numero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.razon_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.fecha_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.cantidad_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.importe_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.numero_factura_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.numero_autorizacion_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.subtotal_doce_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.iva_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.subtotal_cero_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.descuento_movimientos_inv_cabeza,
                      movimientos_inv_cabeza.estado_movimientos_inv_cabeza,
                      usuarios.cedula_usuarios,
                      usuarios.nombre_usuarios,
                      usuarios.apellidos_usuarios,
                      usuarios.usuario_usuarios,
                      bodegas.id_bodegas";
                    $tablas1   = " public.productos,
                      public.movimientos_inv_cabeza,
                      public.movimientos_inv_detalle,
                      public.usuarios,
                      public.unidad_medida,
                      public.bodegas";
                    $where1    = "   movimientos_inv_cabeza.id_usuarios = usuarios.id_usuarios AND
                      movimientos_inv_detalle.id_productos = productos.id_productos AND
                      bodegas.id_bodegas = productos.id_bodegas AND
                      movimientos_inv_detalle.id_movimientos_inv_cabeza = movimientos_inv_cabeza.id_movimientos_inv_cabeza AND productos.id_productos='$_id_productos'";
                    $id1       = "productos.id_productos";
                    
                    $resultEdit = $productos->getCondiciones($columnas1 ,$tablas1 ,$where1, $id1);
                    
                    
                    
                }
                
                
                $this->view_Inventario("Consulta_Productos",array(
                    "resultSet"=>$resultSet, "resultEdit"=>$resultEdit
                ));
                
                
                
            }
            else
            {
                $this->view_Inventario("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Consulta Productos"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    /***
     * mod:compras
     * title: inserta_producto
     * ajax: si
     * return: json de insertado
     */    
    public function inserta_producto(){
        
        session_start();
        
        $resultado = null;
        $productos=new ProductosModel();
        
        $nombre_controladores = "Productos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $productos->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
       
        if (!empty($resultPer))
        { 
            
            if (isset ($_POST["mod_codigo_producto"]) && isset ($_POST["mod_id_grupo"]))            
            {
                $_id_grupos = $_POST["mod_id_grupo"];
                $_id_unidad_medida = $_POST["mod_unidad_medida"];
                $_codigo_productos = $_POST["mod_codigo_producto"];
                $_marca_productos = $_POST["mod_marca_producto"];
                $_nombre_productos = $_POST["mod_nombre_producto"];
                $_descripcion_productos = $_POST["mod_descripcion_producto"];
                $_ult_precio_productos = $_POST["mod_precio_producto"];
                $_id_bodegas = $_POST["mod_id_bodegas"];
                
                
                $funcion = "ins_productos";
                $parametros = " '$_id_grupos', '$_id_unidad_medida', '$_codigo_productos', '$_marca_productos', '$_nombre_productos', '$_descripcion_productos', '$_ult_precio_productos', '$_id_bodegas'";
                $productos->setFuncion($funcion);
                $productos->setParametros($parametros);
                
                $resultado=$productos->llamafuncion();
                
                $mensaje = array();
                
                if(!empty($resultado)){
                    if(is_array($resultado) && count($resultado)>0){
                        
                        if((int)$resultado[0]->ins_productos == 0){
                            
                            $mensaje=array("success"=>1,"mensaje"=>"Producto Actualizado correctamente");
                        }
                        
                        if((int)$resultado[0]->ins_productos == 1){
                            $mensaje=array('success'=>1,'mensaje'=>"Producto Agregado correctamente");
                        }
                        
                    }
                }else{  $mensaje='{success:0,mensaje:"Error al registrar producto"}';}
               
                echo json_encode($mensaje);
            }
            
           
        }
        else
        {
           echo "{success:0,mensaje:\"sin permisos para ingresar un producto \"}";            
            
        }
        
        
    }
    
    public function consulta_productos(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $grupos=new GruposModel();
        $unidadmedida=new UnidadModel();
        $productos=new ProductosModel();
        
        
        $this->view_Inventario("Consulta_Productos", array());
        die();
        
        $where_to="";
        $columnas = "
                      productos.id_productos,
                      grupos.id_grupos,
                      grupos.nombre_grupos,
                      productos.codigo_productos,
                      productos.marca_productos,
                      productos.nombre_productos,
                      productos.descripcion_productos,
                      unidad_medida.nombre_unidad_medida,
                      productos.ult_precio_productos,
                      productos.creado,
                      productos.modificado ";
        $tablas   = "
                      public.productos,
                      public.grupos,
                      public.unidad_medida
                    ";
        $where    = " productos.id_unidad_medida = unidad_medida.id_unidad_medida AND
                      grupos.id_grupos = productos.id_grupos
                      ";
        $id       = "productos.id_productos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (productos.nombre_productos LIKE '".$search."%' )";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$productos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
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
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_activos_fijos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Grupo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Marca</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Precio</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Unidad Medida</th>';
                
                
                
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
                    $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->marca_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->descripcion_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->ult_precio_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
                    
                    
                    if($id_rol==1){
                        
                        
                    }
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_productos("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Productos fijos registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
        
        
    }

    public function paginate_productos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='carga_productos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='carga_productos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='carga_productos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    
    
    
    
}
    
    
    

?>