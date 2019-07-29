<?php

class ProductosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        session_start();
        
        $Bodegas = new BodegasModel();
        $rsBodegas = $Bodegas->getBy(" 1 = 1 ");
        
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
                      productos.minimo_productos,
                      productos.creado,
                      productos.modificado";
        
        $tablas   = "   public.productos,
                                      public.grupos,
                                      public.unidad_medida";
        
        $where    = "  grupos.id_grupos = productos.id_grupos AND
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida";
        
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
                                      productos.id_bodegas,
                                      productos.minimo_productos, 
                                      productos.creado, 
                                      productos.modificado";
                        $tablas   = "   public.productos, 
                                      public.grupos, 
                                      public.unidad_medida";
                        $where    = "  grupos.id_grupos = productos.id_grupos AND 
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida AND productos.id_productos = '$_id_productos'";
                        $id       = "productos.id_productos";
                        
                        $resultEdit = $productos->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view_Inventario("Productos",array(
                    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultGrup"=>$resultGrup, "resultUni"=>$resultUni, "rsBodegas"=>$rsBodegas
                    
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
        

        
    }
    
    public function AgregaProductos(){
        
        session_start();
        
        $resultado = null;
        $productos=new ProductosModel();
        $respuesta = array();
        
        
        $nombre_controladores = "Productos";
        $id_rol= $_SESSION['id_rol']; 
        $resultPer = $productos->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            try {
                
                $error="";
                
                $_id_grupos = $_POST["id_grupos"];
                $_id_unidad_medida = $_POST["id_unidad_medida"];
                $_codigo_productos = $_POST["codigo_productos"];
                $_marca_productos = $_POST["marca_productos"];
                $_nombre_productos = $_POST["nombre_productos"];
                $_descripcion_productos = $_POST["descripcion_productos"];
                $_ult_precio_productos = $_POST["ult_precio_productos"];
                $_id_bodegas = $_POST["id_bodegas"];
                $_id_productos = $_POST["id_productos"];
                $_abreviacion_grupos =  $_POST["abreviacion_grupo"];
                $_consecutivo_productos = $_POST["consecutivo_productos"];
                $_minimo_productos = $_POST["minimo_productos"];
                
                $error = error_get_last();
                
                if( !empty($error) )
                    throw new Exception("Viariables no Definidas ".$error['message']);
                
                if( $_id_productos > 0 ){
                    
                                       
                    $columnas = " id_grupos = '$_id_grupos',
                              id_unidad_medida = '$_id_unidad_medida',
							  codigo_productos ='$_codigo_productos',
							  marca_productos = '$_marca_productos',
                              nombre_productos = '$_nombre_productos',
							  descripcion_productos = '$_descripcion_productos',
							  ult_precio_productos = '$_ult_precio_productos',
                              id_bodegas = '$_id_bodegas',
                              minimo_productos = '$_minimo_productos'";
                    
                    $tabla = " public.productos";
                    
                    $where = " productos.id_productos = '$_id_productos'";
                    
                    $resultado=$productos->ActualizarBy($columnas, $tabla, $where);
                    
                    if( (int)$resultado < 0 )
                        throw new Exception("Error Actualizar Datos");
                        
                    $respuesta['respuesta'] = 1;
                    $respuesta['mensaje'] = "Producto Actualizado";
                    
                }else{
                    
                    $funcion = "ins_productos";
                    $parametros = " '$_id_grupos',
                                    '$_id_unidad_medida', 
                                    '$_codigo_productos', 
                                    '$_marca_productos', 
                                    '$_nombre_productos', 
                                    '$_descripcion_productos', 
                                    '$_ult_precio_productos', 
                                    '$_id_bodegas',
                                    '$_abreviacion_grupos',
                                    '$_consecutivo_productos',
                                    '$_minimo_productos'";
                    $productos->setFuncion($funcion);
                    $productos->setParametros($parametros);
                    
                    $resultado=$productos->llamafuncionPG();
                    
                    if( is_null($resultado) )
                        throw new Exception("Error Insertar Datos");
                        
                    $respuesta['respuesta'] = 1;
                    $respuesta['mensaje'] = "Producto Insertado";
                    
                }
                
                echo json_encode($respuesta);
                
            } catch (Exception $Ex) {
                echo "<message> Error Productos. \n". $Ex->getMessage()." <message>";
            }
            
            
          
        }else{
            
            echo "<message> No tiene permisos para Insertar Productos <message>";
           
        }
        
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
                $_abreviacion_productos = "";
                $_consecutivo = "";
                $_minimo_productos = $_POST["mod_minimo_productos"];
                
                $funcion = "ins_productos";
                $parametros = " '$_id_grupos', 
                                '$_id_unidad_medida', 
                                '$_codigo_productos', 
                                '$_marca_productos', 
                                '$_nombre_productos', 
                                '$_descripcion_productos', 
                                '$_ult_precio_productos', 
                                '$_id_bodegas',
                                '$_abreviacion_productos',
                                '$_consecutivo',
                                '$_minimo_productos'";
                $productos->setFuncion($funcion);
                $productos->setParametros($parametros);
                                          
                $mensaje = array();
                $resultado=$productos->llamafuncionPG();
                
                if(is_null($resultado))
                    $mensaje='{success:0,mensaje:"Error al registrar producto"}';
                
                if((int)$resultado[0] == 0){                    
                    $mensaje=array("success"=>1,"mensaje"=>"Producto Actualizado correctamente");
                }
                
                if((int)$resultado[0] == 1){
                    $mensaje=array('success'=>1,'mensaje'=>"Producto Agregado correctamente");
                }
                
               
                echo json_encode($mensaje);
            }
            
           
        }
        else
        {
           echo "{success:0,mensaje:\"sin permisos para ingresar un producto \"}";            
            
        }
        
        
    }
    
    
    public function indexProductos(){
        
        session_start();
        //parametros
        $this->view_Inventario('Consulta_Productos', array());
    }
    
    
    public function consulta_productos(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $productos= new ProductosModel();
        
        $where_to="";
        $columnas = "   productos.id_productos, 
                      grupos.id_grupos, 
                      grupos.nombre_grupos, 
                      unidad_medida.id_unidad_medida, 
                      unidad_medida.nombre_unidad_medida, 
                      productos.codigo_productos, 
                      productos.marca_productos, 
                      productos.nombre_productos, 
                      productos.descripcion_productos, 
                      productos.ult_precio_productos";
        
        $tablas = "  public.productos, 
                      public.grupos, 
                      public.unidad_medida ";
        
        
        $where    = "   grupos.id_grupos = productos.id_grupos AND
  unidad_medida.id_unidad_medida = productos.id_unidad_medida";
        
        $id       = " productos.id_productos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
          
            
            if(!empty($search)){
                
                
                $where1=" AND (nombre_productos LIKE '".$search."%' )";
                
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
                $html.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Grupo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Marca</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
                $html.='<th style="text-align: left;  font-size: 12px;"align="center">Ultimo Precio</th>';
                $html.='<th style="text-align: left;  font-size: 12px;"align="center">Unidad de Medida</th>';
                
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
                    $html.='<td style="font-size: 11px;"align="right">'.$res->ult_precio_productos.'</td>';
                    $html.='<td style="font-size: 11px;"align="center">'.$res->nombre_unidad_medida.'</td>';
                    
                    
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Productos&action=index&id_productos='.$res->id_productos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Productos&action=borrarId&id_productos='.$res->id_productos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
                        
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
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Productos registrados...</b>';
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
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos(1)'>$i</a></li>";
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
    
    
    
    public function generar_reporte_productos () {
        
        
        session_start();
        
        $grupos=new GruposModel;
        $usuario = new UsuariosModel();
        $unidadmedida= new UnidadModel();
        $productos=new ProductosModel();
        $movdetalle=new MovimientosInvDetalleModel();
        $movcabeza= new MovimientosInvCabezaModel();
        
        
        
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
            
            
            if(isset($_GET["id_productos"])){
                
                
                $_id_productos = $_GET["id_productos"];
                
                
                $columnas="productos.id_productos,
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
                
                $tablas = "public.productos,
                      public.movimientos_inv_cabeza,
                      public.movimientos_inv_detalle,
                      public.usuarios,
                      public.unidad_medida,
                      public.bodegas";
                
                $where = "movimientos_inv_cabeza.id_usuarios = usuarios.id_usuarios AND
                      movimientos_inv_detalle.id_productos = productos.id_productos AND
                      bodegas.id_bodegas = productos.id_bodegas AND
                      movimientos_inv_detalle.id_movimientos_inv_cabeza = movimientos_inv_cabeza.id_movimientos_inv_cabeza AND productos.id_productos='$_id_productos'";
                
                $id="productos.id_productos";
                
                $resultSetCabeza=$movdetalle->getCondiciones($columnas, $tablas, $where, $id);
                
                
                if(!empty($resultSetCabeza)){
                    
                    
                    $_id_productos    =$resultSetCabeza[0]->id_productos;
                    $_nombre_productos     =$resultSetCabeza[0]->nombre_productos;
                    $_fecha_movimientos_inv_cabeza    =$resultSetCabeza[0]->fecha_movimientos_inv_cabeza;
                    $_cantidad_movimientos_inv_detalle     =$resultSetCabeza[0]->cantidad_movimientos_inv_detalle;
                    $_ult_precio_productos    =$resultSetCabeza[0]->ult_precio_productos;
                    $_importe_movimientos_inv_cabeza    =$resultSetCabeza[0]->importe_movimientos_inv_cabeza;
                    $_numero_movimientos_inv_cabeza    =$resultSetCabeza[0]->numero_movimientos_inv_cabeza;
                    $_nombre_usuarios    =$resultSetCabeza[0]->nombre_usuarios;
                    $_codigo_productos    =$resultSetCabeza[0]->codigo_productos;
                    
                    $columnas1 = "  productos.id_productos,
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
                    
                    $tablas1   = "public.productos,
                      public.movimientos_inv_cabeza,
                      public.movimientos_inv_detalle,
                      public.usuarios,
                      public.unidad_medida,
                      public.bodegas";
                    
                    
                    
                    $where1    = "movimientos_inv_cabeza.id_usuarios = usuarios.id_usuarios AND
                      movimientos_inv_detalle.id_productos = productos.id_productos AND
                      bodegas.id_bodegas = productos.id_bodegas AND
                      movimientos_inv_detalle.id_movimientos_inv_cabeza = movimientos_inv_cabeza.id_movimientos_inv_cabeza AND productos.id_productos='$_id_productos'";
                    
                    $id1       = "productos.id_productos";
                    
                    $resultSetDetalle=$movdetalle->getCondiciones($columnas1, $tablas1, $where1, $id1);
                    
                    
                    $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                    $html.= "<tr>";
                    $html.='<th style="text-align: center; font-size: 25px; ">CAPREMCI</br>';
                    $html.='<p style="text-align: center; font-size: 13px; "> Av. Baquerico Moreno E-9781 y Leonidas Plaza';
                    $html.='<p style="text-align: left; font-size: 13px; "> &nbsp; &nbsp;Código: &nbsp; '.$_numero_movimientos_inv_cabeza.' &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp; &nbsp; Fecha de Compra:  &nbsp; '.$_fecha_movimientos_inv_cabeza.'';
                    $html.='</tr>';
                    $html.='</table>';
                    
                    $html.='<p style="text-align: left; font-size: 13px; "><b>&nbsp; USUARIO: </b>'.$_nombre_usuarios.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b> PRODUCTO:</b> &nbsp;'.$_nombre_productos.'';
                    
                    $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                    $html.= "<tr>";
                    $html.='<th colspan="12" style="text-align: left; height:30px; font-size: 13px;" ><b>&nbsp;SALDO INICIAL:  &nbsp;'.$_ult_precio_productos.'';
                    $html.="</th>";
                    $html.="</tr>";
                    $html.='</table>';
                    
                    if(!empty($resultSetDetalle)){
                        
                        $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                        
                        $html.= "<tr>";
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Tipo de Movimiento</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Fecha</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Cantidad</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Precio</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Importe</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Numero Factura</th>';
                        $html.='</tr>';
                        
                        
                        
                        foreach ($resultSetDetalle as $res)
                        {
                            $html.= "<tr>";
                            
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->razon_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->ult_precio_productos.'</td>';
                            $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->importe_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->numero_factura_movimientos_inv_cabeza.'</td>';
                            $html.='</tr>';
                            
                        }
                        $html.='</table>';
                        
                        
                        
                    }
                    
                    
                    
                }
                
                
                
                $this->report("ProductosReporte",array( "resultSet"=>$html));
                die();
                
            }
            
            
            
            
        }else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
        
        
        
        
    }
    
    public function getCodigoProducto(){
        
        $Grupos = new GruposModel();
        $id_grupos = $_POST['id_grupos'];
        $resultGrupos = $Grupos->getBy("id_grupos = $id_grupos");
        
        $queryConsecutivos = "SELECT LPAD(valor_consecutivos::TEXT,espacio_consecutivos,'0') numero_consecutivos 
                            FROM consecutivos WHERE nombre_consecutivos = 'PRODUCTOS'";
        
        $resultConsecutivos = $Grupos->enviaquery($queryConsecutivos);
        
        $respuesta = array();
        
        if( !empty($resultGrupos) && !empty($resultConsecutivos) ){
            
            $respuesta['abreviacion'] = $resultGrupos[0]->abreviacion_grupos;
            $respuesta['consecutivo'] = $resultConsecutivos[0]->numero_consecutivos;
        }
        
        echo json_encode($respuesta);
        
    }

    public function notificacionProductos(){
        
        $Productos = new ProductosModel();
        
        $respuesta = array();
        
        $query = "SELECT p.id_productos,p.nombre_productos,p.codigo_productos,p.minimo_productos,s.saldos_f_saldo_productos
                FROM productos p
                INNER JOIN saldo_productos s
                ON p.id_productos = s.id_productos
                WHERE  1 = 1
                AND p.minimo_productos > s.saldos_f_saldo_productos ";
        
        $rsProductos = $Productos->enviaquery($query);
        
        $cabeceraNotificacion = '';
        $detalleNotificacion = '';
        
        if(!empty($rsProductos)){
            
            $cantidadNotificacion = count($rsProductos);
            
            $cabeceraNotificacion = '<li class="header">Tiene '.$cantidadNotificacion.' Productos por Revisar</li>';
            $detalleNotificacion .= '<li>';
            $detalleNotificacion .= '<ul class="menu">';            
            foreach ($rsProductos as $res){
                $detalleNotificacion .='<li>';
                $detalleNotificacion .='<a href="#">';
                $detalleNotificacion .='<i class="fa fa-shopping-cart text-red"></i>';
                $detalleNotificacion .='Producto '.$res->codigo_productos.' disponible '.(int)$res->saldos_f_saldo_productos.' Requerido '.(int) $res->minimo_productos.'';
                $detalleNotificacion .='</a>';
                $detalleNotificacion .='</li>';                
            }
            $detalleNotificacion .= '</ul>';
            $detalleNotificacion .= '</li>';
            
            $html = $cabeceraNotificacion.$detalleNotificacion;
            
            $respuesta['respuesta']=1;
            $respuesta['htmlNotificacion']=$html;
            $respuesta['cantidadNotificaciones']=$cantidadNotificacion;
            
        }
        
        echo json_encode($respuesta);
        
    }
    
    
}
    
    
    

?>