<?php

class HistoricoProductoController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
              
        $productos= new ProductosModel();
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "HistoricoProducto";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
               
                
                $this->view_Inventario("HistoricoProducto",array(
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
    
    
    
    
    public function index2(){
        
        session_start();
        
        
        if (isset(  $_SESSION['usuario_usuarios']) )
        {
            

            $resultSet="";
 
            $movimientos = new MovimientosInvCabezaModel();
            $nombre_controladores = "HistoricoProducto";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $movimientos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                if(isset($_POST["id_productos"])){
                    
                    
                    $id_productos=$_POST['id_productos'];
                    $id_usuarios=$_POST['id_usuarios'];
                    $fechadesde=$_POST['fecha_desde'];
                    $fechahasta=$_POST['fecha_hasta'];
                    
                    /** se aumenta la busqueda por cedula **/
                  
                    
                    $columnas = " productos.id_productos,
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
                      saldo_productos.saldos_f_saldo_productos,
                      saldo_productos.precio_costo_saldo_productos,
                      saldo_productos.entradas_f_saldo_productos ,
                      saldo_productos.salidas_f_saldo_productos";
                    
                    
                    
                    $tablas=" public.productos,
                      public.grupos,
                      public.unidad_medida,
                     public.saldo_productos";
                    
                    $where="productos.id_unidad_medida = unidad_medida.id_unidad_medida AND
                      grupos.id_grupos = productos.id_grupos AND saldo_productos.id_productos = productos.id_productos";
                    
                    $id="productos.id_productos";
                    
                    
              
                    $where_1 = "";
                    $where_2 = "";
                    $where_4 = "";
                    $where_5 = "";
                    
               
                    
                    if($id_productos!=0){$where_1=" AND productos.id_productos='$id_productos'";}
                    
                    if($id_usuarios!=0){$where_2=" AND usuarios.id_usuarios='$id_usuarios'";}
                    
                    if($fechadesde!="" && $fechahasta!=""){$where_4=" AND  date(ccomprobantes.fecha_ccomprobantes) BETWEEN '$fechadesde' AND '$fechahasta'";}
                    
                    
                    
                    $where_to  = $where . $where_1 . $where_2. $where_4.$where_5;
                    
                    
                    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
                    
                    if($action == 'ajax')
                    {
                        $html="";
                        $resultSet=$movimientos->getCantidad("*", $tablas, $where_to);
                        $cantidadResult=(int)$resultSet[0]->total;
                        
                        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
                        
                        $per_page = 50; //la cantidad de registros que desea mostrar
                        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
                        $offset = ($page - 1) * $per_page;
                        
                        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
                        
                        
                        $resultSet=$movimientos->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
                        
                        $count_query   = $cantidadResult;
                        
                        $total_pages = ceil($cantidadResult/$per_page);
                        
                        if ($cantidadResult>0)
                        {
                            
                            
                            
                            
                            $html.='<div class="pull-left" style="margin-left:15px;">';
                            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                            $html.='</div>';
                            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                            $html.='<section style="height:425px; overflow-y:scroll;">';
                            $html.= "<table id='tabla_comprobantes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                            $html.= "<thead>";
                            $html.= "<tr>";
                            $html.='<th style="text-align: left;  font-size: 12px;">Tipo</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Concepto</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Numero de Comprobante</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Forma de Pago</th>';
                            $html.='<th style="text-align: left;  font-size: 12px;">Reporte</th>';
                            
                            
                            
                            $html.='</tr>';
                            $html.='</thead>';
                            $html.='<tbody>';
                            
                            
                            
                            
                            foreach ($resultSet as $res)
                            {
                                
                                
                                
                                $html.='<tr>';
                                $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_comprobantes.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->concepto_ccomprobantes.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->valor_letras.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->fecha_ccomprobantes.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->numero_ccomprobantes.'</td>';
                                $html.='<td style="font-size: 11px;">'.$res->nombre_forma_pago.'</td>';
                                $html.='<td style="font-size: 11px;"><span class="pull-right"><a href="index.php?controller=ReporteComprobante&action=comprobante_contable_reporte&id_ccomprobantes='.$res->id_ccomprobantes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                                
                                $html.='</tr>';
                                
                                
                                
                            }
                            
                            
                            
                            
                            $html.='</tbody>';
                            $html.='</table>';
                            $html.='</section></div>';
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
                
                
                $this->view_Contable("ReporteComprobante",array(
                    "resultSet"=>$resultSet, "resultTipCom"=> $resultTipCom,
                    "resultEnt"=>$resultEnt
                    
                ));
                
                
            }else{
                
                $this->view_Contable("Error",array(
                    "resultado"=>"No tiene Permisos de Consultar Comprobantes"
                    
                    
                ));
                exit();
            }
            
            
        }
        else
        {
            
            $this->redirect("Usuarios","sesion_caducada");
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
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_comprobantes($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_comprobantes(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    
    
    
    
    
    
    
    public function generar_reporte_productos () {
        
       
        
        session_start();
        
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
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
        $domLogo=$directorio.'/view/images/logoCapremci.png';
        $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="150" height="35">';
        
        
        
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
                   
                    
                    $datos_reporte = array();
                    
                    if(!empty($resultSetDetalle)){
                        
                        $html.='<table class="1"  cellspacing="0" style="width:100px;" border="1" >';
                        $html.= "<tr>";
                        $html.='<th colspan="2" style="text-align: center;  font-size: 12px;">Id</th>';
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
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$i.'</td>';
                            $html.='<td colspan="2" style="text-align: left; font-size: 13px;">'.$res->razon_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->fecha_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
                            $html.='<td colspan="2" class="htexto3">$ '.$res->ult_precio_productos.'</td>';
                            $html.='<td colspan="2" style="text-align: center; font-size: 13px;">'.$res->numero_factura_movimientos_inv_cabeza.'</td>';
                            $html.='</tr>';
                            
                        }
                        $html.='</table>';
                        
                    }
                }
                
                
                $datos_reporte['DETALLE_PRODUCTOS']= $html;
                
                $this->verReporte("ReporteProductos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
               
                die();
                
            }
            
            
        }else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
    }
   
    
    
    public function consulta_productos(){        session_start();
    $id_rol=$_SESSION["id_rol"];
    
    $productos= new ProductosModel();
    
    $where_to="";
    $columnas = " productos.id_productos,
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
                      saldo_productos.saldos_f_saldo_productos,
                      saldo_productos.precio_costo_saldo_productos,
                      saldo_productos.entradas_f_saldo_productos ,
                      saldo_productos.salidas_f_saldo_productos
        
                      ";
    
    $tablas = "  public.productos,
                      public.grupos,
                      public.unidad_medida,
                     public.saldo_productos ";
    
    
    $where    = "productos.id_unidad_medida = unidad_medida.id_unidad_medida AND
                      grupos.id_grupos = productos.id_grupos AND saldo_productos.id_productos = productos.id_productos";
    
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
        $adjacents  = 9; //brecha entre pÃ¡ginas despuÃ©s de varios adyacentes
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
            $html.='<th colspan="2" style=" text-align: center; font-size: 11px;">Código</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Marca</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Nombre</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Descripción</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Entradas</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Salidas</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Disponible</th>';
            
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            
            $i=0;
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<tr >';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->codigo_productos.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->marca_productos.'</td>';
                $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_productos.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->descripcion_productos.'</td>';
                $html.='<td colspan="2" style=" font-size: 11px;"align="center";>'.(int)$res->entradas_f_saldo_productos.'</td>';
                $html.='<td colspan="2" style=" font-size: 11px;"align="center";>'.(int)$res->salidas_f_saldo_productos.'</td>';
                $html.='<td colspan="2" style=" font-size: 11px;"align="center";>'.(int)$res->saldos_f_saldo_productos.'</td>';
                
                $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=HistoricoProducto&action=reporte_movimientos_productos&id_productos='.$res->id_productos.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                
                
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
            $html.='<h4>Aviso!!!</h4> <b>Actualmente No Existen Movimientos de Productos...</b>';
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
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_productos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_productos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_productos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_productos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_productos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_productos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_productos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
   
    public function reporte_stock_productos(){
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
        
        
        $productos=new SaldoProductosModel();
        $datos_reporte = array();
        
        //////retencion detalle
        
        $columnas = "  usuarios.id_usuarios, 
                  usuarios.nombre_usuarios, 
                  usuarios.apellidos_usuarios, 
                  productos.id_productos, 
                  productos.codigo_productos, 
                  productos.nombre_productos, 
                  movimientos_inv_cabeza.numero_movimientos_inv_cabeza, 
                  movimientos_inv_cabeza.razon_movimientos_inv_cabeza, 
                  movimientos_inv_cabeza.cantidad_movimientos_inv_cabeza, 
                  movimientos_inv_cabeza.importe_movimientos_inv_cabeza, 
                  movimientos_inv_detalle.cantidad_movimientos_inv_detalle, 
                  movimientos_inv_detalle.saldo_f_movimientos_inv_detalle, 
                  movimientos_inv_detalle.saldo_v_movimientos_inv_detalle, 
                  movimientos_inv_detalle.valor_movimientos_inv_detalle, 
                  movimientos_inv_cabeza.fecha_movimientos_inv_cabeza, 
                  movimientos_inv_cabeza.estado_movimientos_inv_cabeza,
                  rol.id_rol, 
                  rol.nombre_rol,
                  movimientos_inv_detalle.cantidad_movimientos_inv_detalle";
        
        $tablas = "  public.movimientos_inv_cabeza, 
                      public.movimientos_inv_detalle, 
                      public.productos, 
                      public.usuarios,
                      public.rol";
        $where= " movimientos_inv_cabeza.id_usuarios = usuarios.id_usuarios AND
                  movimientos_inv_detalle.id_movimientos_inv_cabeza = movimientos_inv_cabeza.id_movimientos_inv_cabeza AND
                  productos.id_productos = movimientos_inv_detalle.id_productos AND
                  usuarios.id_rol = rol.id_rol";
        $id="movimientos_inv_cabeza.fecha_movimientos_inv_cabeza";
        
        $productos_detalle = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='';
        
        
        $html.='<table class="12"  border=1>';
        $html.='<tr>';
        $html.='<th width="60px">Fecha</th>';
        $html.='<th width="60px">Nombre</th>';
        $html.='<th width="60px">Producto</th>';
        $html.='<th width="60px">Cantidad Solicitada</th>';
        $html.='<th width="60px">Cantidad Aprobada</th>';
        $html.='<th width="60px">Estado</th>';

        
        $html.='</tr>';
        
        
     
        foreach ($productos_detalle as $res)
        {
            
            
          
            
            $html.='<tr >';
            $html.='<td width="60px">'.$res->fecha_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px">'.$res->nombre_usuarios.' '.$res->apellidos_usuarios.'</td>';
            $html.='<td width="60px">'.$res->nombre_productos.'</td>';
            $html.='<td width="60px" align="center">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px" align="center">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px" align="center">'.$res->estado_movimientos_inv_cabeza.'</td>';
           
           
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        $html.='</table>';
        
        $datos_reporte['DETALLE_PRODUCTOS']= $html;
        
        $this->verReporte("HistoricoProductos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    public function reporte_movimientos_productos(){
        session_start();
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        
        $entidades = new EntidadesModel();
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
        
        
        
        
        
        
        $productos = new ProductosModel();
        $id_productos =  (isset($_REQUEST['id_productos'])&& $_REQUEST['id_productos'] !=NULL)?$_REQUEST['id_productos']:'';
        
        $datos_reporte = array();
        
        $columnas = " productos.id_productos, 
                      productos.codigo_productos, 
                      productos.marca_productos, 
                      productos.nombre_productos, 
                      productos.descripcion_productos, 
                      saldo_productos.entradas_f_saldo_productos, 
                      saldo_productos.entradas_v_saldo_productos, 
                      saldo_productos.salidas_f_saldo_productos, 
                      saldo_productos.salidas_v_saldo_productos, 
                      saldo_productos.saldos_f_saldo_productos, 
                      saldo_productos.saldos_v_saldo_productos";
        
        $tablas = "   public.productos, 
                      public.saldo_productos";
        $where= "saldo_productos.id_productos = productos.id_productos AND  productos.id_productos='$id_productos'";
        $id="productos.id_productos";
        
        $rsdatos = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
     
        $datos_reporte['NOMBRE_PRODUCTOS']=$rsdatos[0]->nombre_productos;
        $datos_reporte['MARCA_PRODUCTOS']=$rsdatos[0]->marca_productos;
        $datos_reporte['CODIGO_PRODUCTO']=$rsdatos[0]->codigo_productos;
        $datos_reporte['SALDO_PRODUCTO']=intval($rsdatos[0]->saldos_f_saldo_productos);
        $datos_reporte['ENTRADAS_PRODUCTO']=intval($rsdatos[0]->entradas_f_saldo_productos);
     
        
        
        $columnas = "u.id_usuarios, 
                  u.nombre_usuarios, 
                  u.apellidos_usuarios, 
                  p.id_productos, 
                  p.codigo_productos, 
                  p.nombre_productos, 
                  m.numero_movimientos_inv_cabeza, 
                  m.razon_movimientos_inv_cabeza, 
                  m.cantidad_movimientos_inv_cabeza, 
                  m.importe_movimientos_inv_cabeza, 
                  md.cantidad_movimientos_inv_detalle, 
                  md.saldo_f_movimientos_inv_detalle, 
                  md.saldo_v_movimientos_inv_detalle, 
                  md.valor_movimientos_inv_detalle, 
                  m.fecha_movimientos_inv_cabeza, 
                  m.estado_movimientos_inv_cabeza,
                  md.cantidad_movimientos_inv_detalle,
		 (select d.nombre_departamento from empleados e inner join departamentos d on e.id_departamento=d.id_departamento where e.numero_cedula_empleados=u.cedula_usuarios) as departamento
 ";
        
        $tablas = "  public.movimientos_inv_cabeza m, 
                  public.movimientos_inv_detalle md, 
                  public.productos p, 
                  public.usuarios u";
        $where= "m.id_usuarios = u.id_usuarios AND
                  md.id_movimientos_inv_cabeza = m.id_movimientos_inv_cabeza AND
                  p.id_productos = md.id_productos AND p.id_productos='$id_productos'";
        $id="movimientos_inv_cabeza.fecha_movimientos_inv_cabeza";
        
        $productos_detalle = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        
        $html='';
        
        
        $html.='<table class="12" style="width:98px;" border=1>';
        $html.='<tr>';
        $html.='<th width="60px">Fecha</th>';
        $html.='<th width="60px">Nombre</th>';
        $html.='<th width="60px">Departamento</th>';
        $html.='<th width="60px">Cantidad Solicitada</th>';
        $html.='<th width="60px">Cantidad Aprobada</th>';
        $html.='<th width="60px">Estado</th>';
        
        
        $html.='</tr>';
        
        
        $suma_solicitada=0;
        $suma_aprobada=0;
        
        foreach ($productos_detalle as $res)
        {
            
            
            $cantidad_movimientos_inv_cabeza = $res->cantidad_movimientos_inv_cabeza;
            
            $html.='<tr >';
            $html.='<td width="60px">'.$res->fecha_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px">'.$res->nombre_usuarios.' '.$res->apellidos_usuarios.'</td>';
            $html.='<td width="60px" align="center">'.$res->departamento.'</td>';
            $html.='<td width="60px" align="center">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px" align="center">'.$res->cantidad_movimientos_inv_cabeza.'</td>';
            $html.='<td width="60px" align="center">'.$res->estado_movimientos_inv_cabeza.'</td>';
            
            $html.='</td>';
            $html.='</tr>';
        
        
            $suma_aprobada = $suma_aprobada+$cantidad_movimientos_inv_cabeza;
            $suma_solicitada = $suma_solicitada+$cantidad_movimientos_inv_cabeza;
            
           
            
            
        
        }
        
        $html.='</table>';
        
        
        
        $html.='<table class="12" style="width:98px;">';
        $html.='<tr>';
        $html.='<th width="60px">&nbsp;</th>';
        $html.='<th width="60px">Subtotal:</th>';
        $html.='<th width="150px">'.$suma_aprobada.'</th>';
        $html.='<th width="60px" align="left">'.$suma_solicitada.'</th>';
        $html.='<th width="90px">&nbsp;</th>';
        $html.='</tr>';
        $html.='</table>';
        
        
        
        $datos_reporte['TABLA_MOVIMIENTOS']= $html;
        
        
        
        
       
        
        
        
        
        $this->verReporte("ReporteHistoricoProductos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte, 'datos_reporte_detall'=>$datos_reporte_detall));
        
        
        
    }
    
    
    
    public function cargaProducto(){
        
        $producto= new ProductosModel();
        
        $columnas="id_productos, nombre_productos";
        $tabla = "productos";
        $where = "1=1";
        $id="nombre_productos";
        $resulset = $producto->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    public function cargaUsuarios(){
        
        $usuarios= new UsuariosModel();
        
        $columnas="id_usuarios, nombre_usuarios, apellidos_usuarios";
        $tabla = "usuarios";
        $where = "1=1";
        $id="nombre_usuarios";
        $resulset = $usuarios->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
}



?>