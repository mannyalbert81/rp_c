<?php

class ActivosFijosDetalleController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
        
        $activosf=new ActivosFijosModel();
      
       
        $oficina=new OficinaModel();
        $resultOfi=$oficina->getAll("nombre_oficina");
       
        $tipoactivos=new TipoActivosModel();
        $resultTipoac=$tipoactivos->getAll("nombre_tipo_activos_fijos");
        
        $estado=new EstadoModel();
        $resultEst=$estado->getAll("nombre_estado");
        
        $usuarios=new UsuariosModel();
        $resultParr=$usuarios->getAll("nombre_usuarios");
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "ActivosFijosDetalle";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $activosf->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_activos_fijos"])   )
                {
                    
                  
                        
                    $_id_activos_fijos = $_GET["id_activos_fijos"];
                        $columnas = "
                                      activos_fijos.id_activos_fijos, 
                                      oficina.id_oficina, 
                                      oficina.nombre_oficina, 
                                      tipo_activos_fijos.id_tipo_activos_fijos, 
                                      tipo_activos_fijos.nombre_tipo_activos_fijos, 
                                      estado.id_estado, 
                                      estado.nombre_estado, 
                                      usuarios.id_usuarios, 
                                      usuarios.nombre_usuarios, 
                                      activos_fijos.nombre_activos_fijos, 
                                      activos_fijos.codigo_activos_fijos, 
                                      activos_fijos.fecha_compra_activos_fijos, 
                                      activos_fijos.cantidad_activos_fijos, 
                                      activos_fijos.valor_activos_fijos, 
                                      activos_fijos.meses_depreciacion_activos_fijos, 
                                      activos_fijos.depreciacion_mensual_activos_fijos, 
                                      activos_fijos.creado, 
                                      activos_fijos.modificado
                                    
                                    ";
                        
                        $tablas   = " public.activos_fijos, 
                                      public.oficina, 
                                      public.tipo_activos_fijos, 
                                      public.estado, 
                                      public.usuarios";
                        $where    = " oficina.id_oficina = activos_fijos.id_oficina AND
                                      tipo_activos_fijos.id_tipo_activos_fijos = activos_fijos.id_tipo_activos_fijos AND
                                      estado.id_estado = activos_fijos.id_estado AND
                                      usuarios.id_usuarios = activos_fijos.id_usuarios 
                                      AND activos_fijos.id_activos_fijos = '$_id_activos_fijos'";
                        $id       = "activos_fijos.id_activos_fijos";
                        
                        $resultEdit = $activosf->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view_Contable("ActivosFijosDetalle",array(
                    "resultSet"=>$resultSet, 
                    "resultEdit" =>$resultEdit, 
                    "resultOfi"=>$resultOfi, 
                    "resultTipoac"=>$resultTipoac, 
                    "resultEst"=>$resultEst 
                    
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Contable("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Bodegas"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    
    
    public function InsertaActivosFijos(){
        
        session_start();
        
        $resultado = null;
        $activosf=new ActivosFijosModel();
        
        
        $nombre_controladores = "ActivosFijos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $activosf->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            if ( isset ($_POST["nombre_activos_fijos"]))
            
            {
                //die('llego');
                
                
                $_id_oficina = $_POST["id_oficina"];
                $_id_tipo_activos_fijos = $_POST["id_tipo_activos_fijos"];
                $_id_estado = $_POST["id_estado"];
                $_id_usuarios=$_SESSION['id_usuarios'];
                $_nombre_activos_fijos = $_POST["nombre_activos_fijos"];
                $_codigo_activos_fijos = $_POST["codigo_activos_fijos"];
                $_fecha_compra_activos_fijos = $_POST["fecha_compra_activos_fijos"];
                $_cantidad_activos_fijos = $_POST["cantidad_activos_fijos"];
                $_valor_activos_fijos = $_POST["valor_activos_fijos"];
                $_meses_depreciacion_activos_fijos = $_POST["meses_depreciacion_activos_fijos"];
                $_depreciacion_mensual_activos_fijos = $_POST["depreciacion_mensual_activos_fijos"];
                
                //die('llego');
                
                
                if($id_activos_fijos > 0){
                    //die('llego');
                    
                    $columnas = "
                              
							  id_oficina ='$_id_oficina',
							  id_tipo_activos_fijos = '$_id_tipo_activos_fijos',
                              id_estado = '$_id_estado',
                              id_usuarios = '$_id_usuarios',
                              nombre_activos_fijos = '$_nombre_activos_fijos',
                              codigo_activos_fijos = '$_codigo_activos_fijos',
                              fecha_compra_activos_fijos ='$_fecha_compra_activos_fijos',
							  cantidad_activos_fijos = '$_cantidad_activos_fijos',
                              valor_activos_fijos = '$_valor_activos_fijos',
                              meses_depreciacion_activos_fijos = '$_meses_depreciacion_activos_fijos',
                              depreciacion_mensual_activos_fijos = '$_depreciacion_mensual_activos_fijos'
                              
							  
							  ";
                    
                    $tabla = "public.activos_fijos, 
                              public.oficina, 
                              public.tipo_activos_fijos, 
                              public.estado, 
                              public.usuarios";
                    $where = "oficina.id_oficina = activos_fijos.id_oficina AND
                              tipo_activos_fijos.id_tipo_activos_fijos = activos_fijos.id_tipo_activos_fijos AND
                              estado.id_estado = activos_fijos.id_estado AND
                              usuarios.id_usuarios = activos_fijos.id_usuarios 
                              AND activos_fijos.id_activos_fijos = '$_id_activos_fijos'";
                    $resultado=$activosf->UpdateBy($columnas, $tabla, $where);
                    
                }else{
                    
                    $funcion = "ins_activos_fijos";
                    $parametros = "'$_id_oficina', '$_id_tipo_activos_fijos', '$_id_estado', '$_id_usuarios', '$_nombre_activos_fijos', '$_codigo_activos_fijos', '$_fecha_compra_activos_fijos', '$_cantidad_activos_fijos', '$_valor_activos_fijos', '$_meses_depreciacion_activos_fijos', '$_depreciacion_mensual_activos_fijos'";
                    $activosf->setFuncion($funcion);
                    $activosf->setParametros($parametros);
                    $resultado=$activosf->Insert();
                }
                
            }
            
            $this->redirect("ActivosFijosDetalle", "index");
        }
        else
        {
            $this->view_Contable("Error",array(
                "resultado"=>"No tiene Permisos Para Crear Bodegas"
                
            ));
            
            
        }
        
        
        
    }
    
    
    
    
    
    public function borrarId()
    {
        
        session_start();
        $activosf=new ActivosFijosModel();
        $nombre_controladores = "ActivosFijos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $activosf->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            if(isset($_GET["id_activos_fijos"]))
            {
                $id_activos_fijos=(int)$_GET["id_activos_fijos"];
                
             
                
                $activosf->deleteBy(" id_activos_fijos",$id_activos_fijos);
                
            }
            
            $this->redirect("ActivosFijosDetalle", "index");
            
            
        }
        else
        {
            $this->view_Contable("Error",array(
                "resultado"=>"No tiene Permisos de Borrar Bodegas"
                
            ));
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
                      activos_fijos_detalle.id_activos_fijos_detalle, 
                      activos_fijos.id_activos_fijos, 
                      activos_fijos.nombre_activos_fijos, 
                      activos_fijos.codigo_activos_fijos, 
                      activos_fijos.fecha_compra_activos_fijos, 
                      activos_fijos_detalle.anio_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_enero_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_enero_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_enero_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_febrero_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_febrero_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_febrero_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_marzo_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_marzo_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_marzo_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_abril_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_abril_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_abril_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_mayo_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_mayo_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_mayo_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_junio_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_junio_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_junio_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_julio_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_julio_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_julio_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_agosto_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_agosto_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_agosto_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_septiembre_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_septiembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_septiembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_octubre_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_octubre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_octubre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_noviembre_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_noviembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_noviembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_diciembre_depreciacion_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_meses_diciembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.cant_dias_diciembre_reales_depre_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_depreciacion_acumulada_anio_activos_fijos_detalle, 
                      activos_fijos_detalle.valor_a_depreciar_siguiente_anio_activos_fijos_detalle, 
                      activos_fijos_detalle.creado, 
                      activos_fijos_detalle.modificado";
        $tablas   = " 
                     public.activos_fijos, 
                     public.activos_fijos_detalle
                    ";
        $where    = " activos_fijos_detalle.id_activos_fijos = activos_fijos.id_activos_fijos ";
        $id       = "activos_fijos_detalle.id_activos_fijos_detalle ";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (activos_fijos.nombre_activos_fijos LIKE '".$search."%' )";
                
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
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Año Depreciación</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Mes Depreciación</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad en Mes</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad en Días</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor Depreciado</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor Acumulado Anualmente</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor a Depreciación el Año Siguiente</th>';
                
                
              
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->codigo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->fecha_compra_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->anio_depreciacion_activos_fijos_detalle.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->codigo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->fecha_compra_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->valor_diciembre_depreciacion_activos_fijos_detalle.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->valor_depreciacion_acumulada_anio_activos_fijos_detalle.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->valor_a_depreciar_siguiente_anio_activos_fijos_detalle.'</td>';
                    
                    
                    
                  
                    
                  
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
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_activos_fijos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_activos_fijos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    //AUTOCOMPLETAR CODIGO
    
    public function AutocompleteCodigoActivos(){
        
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        $activosf=new ActivosFijosModel();
        $codigo_activos_fijos = $_GET['term'];
        
        $columnas ="  activos_fijos_detalle.id_activos_fijos_detalle, 
                      activos_fijos.id_activos_fijos, 
                      activos_fijos.nombre_activos_fijos, 
                      activos_fijos.codigo_activos_fijos, 
                      activos_fijos.creado, 
                      activos_fijos.modificado";
        $tablas ="public.activos_fijos_detalle, 
                  public.activos_fijos";
        $where ="activos_fijos.codigo_activos_fijos LIKE '$codigo_activos_fijos%'";
        $id ="activos_fijos.codigo_activos_fijos";
        
        
        $resultSet=$activosf->getCondiciones($columnas, $tablas, $where, $id);
        
        
        if(!empty($resultSet)){
            
            foreach ($resultSet as $res){
                
                $_respuesta[] = $res->codigo_activos_fijos;
            }
            echo json_encode($_respuesta);
        }
        
    }
    
    
    //DEVUELVE EL NOMBRE
    
    public function DevuelveNombreActivos(){
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        $activosf=new ActivosFijosModel();
        $codigo_activos_fijos = $_POST['codigo_activos_fijos'];
        
        
        $columnas ="activos_fijos_detalle.id_activos_fijos_detalle, 
                      activos_fijos.id_activos_fijos, 
                      activos_fijos.nombre_activos_fijos, 
                      activos_fijos.codigo_activos_fijos, 
                      activos_fijos.creado, 
                      activos_fijos.modificado";
        $tablas ="public.activos_fijos_detalle, 
                  public.activos_fijos";
        $where ="activos_fijos.codigo_activos_fijos = '$codigo_activos_fijos'";
        $id ="activos_fijos.codigo_activos_fijos";
        
        
        $resultSet=$activosf->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $respuesta = new stdClass();
        
        if(!empty($resultSet)){
            
            $respuesta->nombre_activos_fijos = $resultSet[0]->nombre_activos_fijos;
            $respuesta->codigo_activos_fijos = $resultSet[0]->codigo_activos_fijos;
            
            echo json_encode($respuesta);
        }
        
    }
    
    //AUTOCOMPLETE NOMBRE
    
    public function AutocompleteNombreActivos(){
        
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        $activosf=new ActivosFijosModel();
        $nombre_activos_fijos = $_GET['term'];
        
        $columnas ="activos_fijos_detalle.id_activos_fijos_detalle, 
                      activos_fijos.id_activos_fijos, 
                      activos_fijos.nombre_activos_fijos, 
                      activos_fijos.codigo_activos_fijos, 
                      activos_fijos.creado, 
                      activos_fijos.modificado";
        $tablas =" public.activos_fijos_detalle, 
                  public.activos_fijos";
        $where ="activos_fijos.nombre_activos_fijos ILIKE '$nombre_activos_fijos%'";
        $id ="activos_fijos.nombre_activos_fijos";
        
        
        $resultSet=$activosf->getCondiciones($columnas, $tablas, $where, $id);
        
        
        if(!empty($resultSet)){
            
            foreach ($resultSet as $res){
                
                $_respuesta[] = $res->nombre_activos_fijos;
            }
            echo json_encode($_respuesta);
        }
        
    }
    
  //AUTOCOMPLETE CODIGO
  
    public function DevuelveCodigoActivos(){
        session_start();
        $_id_usuarios= $_SESSION['id_usuarios'];
        $activosf=new ActivosFijosModel();
        $nombre_activos_fijos = $_POST['nombre_activos_fijos'];
        
        
        
        $columnas ="activos_fijos_detalle.id_activos_fijos_detalle, 
                      activos_fijos.id_activos_fijos, 
                      activos_fijos.nombre_activos_fijos, 
                      activos_fijos.codigo_activos_fijos, 
                      activos_fijos.creado, 
                      activos_fijos.modificado";
        $tablas ="public.activos_fijos_detalle, 
                  public.activos_fijos";
        $where ="activos_fijos.nombre_activos_fijos = '$nombre_activos_fijos'";
        $id ="activos_fijos.nombre_activos_fijos";
        
        
        $resultSet=$activosf->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $respuesta = new stdClass();
        
        if(!empty($resultSet)){
            
            
            
            $respuesta->codigo_activos_fijos = $resultSet[0]->codigo_activos_fijos;
            $respuesta->nombre_activos_fijos = $resultSet[0]->nombre_activos_fijos;
            
            
            echo json_encode($respuesta);
        }
        
    }
    
    
}
?>