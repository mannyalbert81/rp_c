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
                                bodegas.id_bodegas = productos.id_bodegasAND
                                       unidad_medida.id_unidad_medida = productos.id_unidad_medida ";
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
                      bodegas.id_bodegas = productos.id_bodegasAND
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
                
                $mensaje = "";
                
                if(!empty($resultado)){
                    if(is_array($resultado) && count($resultado)>0){
                        
                        if((int)$resultado[0]->ins_productos == 0){
                            $mensaje='{"success":1,"mensaje":"Producto Actualizado correctamente"}';
                        }
                        
                        if((int)$resultado[0]->ins_productos == 1){
                            $mensaje='{success:1,mensaje:"Producto Agregado correctamente"}';
                        }
                        
                    }
                }else{  $mensaje='{success:0,mensaje:"Producto Actualizado correctamente"}';}
               
                echo $mensaje;
            }
            
           
        }
        else
        {
           echo "{success:0,mensaje:\"sin permisos para ingresar un producto \"}";            
            
        }
        
        
    }

    
    
    
}
?>