<?php

class BuscarProductoController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
        
        $activosfdetalle=new ActivosFijosDetalleModel();
      
       
        $oficina=new OficinaModel();
        $resultOfi=$oficina->getAll("nombre_oficina");
       
        $tipoactivos=new TipoActivosModel();
        $resultTipoac=$tipoactivos->getAll("nombre_tipo_activos_fijos");
        
        
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "ActivosFijosDetalle";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $activosfdetalle->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_activos_fijos_detalle"])   )
                {
                    
                  
                        
                    $_id_activos_fijos = $_GET["id_activos_fijos"];
                        $columnas = "
                                      activos_fijos.id_activos_fijos, 
                                      tipo_activos_fijos.id_tipo_activos_fijos, 
                                      tipo_activos_fijos.nombre_tipo_activos_fijos, 
                                      activos_fijos.nombre_activos_fijos, 
                                      activos_fijos.codigo_activos_fijos, 
                                      activos_fijos.valor_activos_fijos, 
                                      activos_fijos.depreciacion_mensual_activos_fijos, 
                                      activos_fijos_detalle.id_activos_fijos_detalle,
                                      activos_fijos_detalle.anio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_enero_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_febrero_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_marzo_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_abril_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_mayo_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_junio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_julio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_agosto_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_septiembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_octubre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_noviembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_diciembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_depreciacion_acumulada_anio_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_a_depreciar_siguiente_anio_activos_fijos_detalle, 
                                      activos_fijos_detalle.creado, 
                                      activos_fijos_detalle.modificado
                                    
                                    ";
                        
                        $tablas   = " public.activos_fijos, 
                                      public.oficina, 
                                      public.tipo_activos_fijos, 
                                      public.estado, 
                                      public.usuarios";
                        $where    = " public.activos_fijos, 
                                      public.activos_fijos_detalle, 
                                      public.tipo_activos_fijos, 
                                      AND activos_fijos_detalle.id_activos_fijos_detalle, = '$_id_activos_fijos_detalle'";
                        $id       = "activos_fijos.id_activos_fijos";
                        
                        $resultEdit = $activosfdetalle->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view_Inventario("BuscarProductos",array(
                    "resultSet"=>$resultSet, 
                    "resultEdit" =>$resultEdit
                    
                    
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Inventario("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a La depreciación de Activos Fijos"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    

    
    
    public function consulta_activos_fijos_detalle(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $activos_fijos = new ActivosFijosModel();
        $usuarios = new UsuariosModel();
        $activos_detalle = null; $activos_detalle = new ActivosFijosDetalleModel();
        $where_to="";
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
                      productos.modificado
                      
                      ";
        $tablas   = " 
                     public.productos,
                      public.grupos,
                      public.unidad_medida
                    ";
        $where    = "productos.id_unidad_medida = unidad_medida.id_unidad_medida AND
                      grupos.id_grupos = productos.id_grupos ";
        $id       = "productos.id_productos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (productos.nombre_productosLIKE '".$search."%' )";
                
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
                $html.= "<table id='tabla_activos_fijos_detalle' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Grupo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Marca</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Descripción</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Precio</th>';
                $html.='<th style="text-align: center; font-size: 12px;">Unidad Medida</th>';
                
                
                
              
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->codigo_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_grupos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->marca_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->descripcion_productos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->ult_precio_productos.'</td>';
                    $html.='<td style="text-align: center; font-size: 11px;">'.$res->nombre_unidad_medida.'</td>';
                    $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=BuscarProducto&action=generar_reporte_productos&id_productos='.$res->id_productos.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                    
                    
                    
                    
                  
                    
                  
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_activos_fijos("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay Activos fijos registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
        
        
    }
    
    
    public function paginate_activos_fijos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos_detalle($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos_detalle(".($page+1).")'>$nextlabel</a></span></li>";
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
                    
                    $html.='<p style="text-align: left; font-size: 13px; "><b>&nbsp; USUARIO: </b>'.$_nombre_usuarios.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b> PRODUCTO:</b> &nbsp;'.$_nombre_productos.'';
                    
                    $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                    $html.= "<tr>";
                    $html.='<th colspan="12" style="text-align: left; height:30px; font-size: 13px;" ><b>&nbsp;SALDO INICIAL:  &nbsp;'.$_ult_precio_productos.'';
                    $html.="</th>";
                    $html.="</tr>";
                    $html.='</table>';
                    
                    if(!empty($resultSetDetalle)){
                        
                        $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                        
                        $html.= "<tr>";
                        $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Tipo de Movimiento</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Fecha</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Cantidad</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Precio</th>';
                        $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Numero Factura</th>';
                        $html.='</tr>';
                        
                        
                        $i=0;
                        
                        foreach ($resultSetDetalle as $res)
                        {
                            $i++;
                            $html.= "<tr>";
                            $html.='<td style="text-align: center; font-size: 13px;">'.$i.'</td>';
                            $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->razon_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->ult_precio_productos.'</td>';
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
    
   
}
?>