<?php

class ActivosFijosController extends ControladorBase{
    
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
        
        $activos= null;
        $activos = new EstadoModel();
        $whe_activos = "tabla_estado = 'ACTIVOS'";
        $result_Activos_estados = $activos->getBy($whe_activos);
        
        $usuarios=new UsuariosModel();
        $resultParr=$usuarios->getAll("nombre_usuarios");
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "ActivosFijos";
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
                
                
                $this->view_Contable("ActivosFijos",array(
                    "resultSet"=>$resultSet, 
                    "resultEdit" =>$resultEdit, 
                    "resultOfi"=>$resultOfi, 
                    "resultTipoac"=>$resultTipoac,
                    "result_Activos_estados"=>$result_Activos_estados
                    
                    
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
                
                $_id_activos_fijos = $_POST["id_activos_fijos"];
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
                
                //die('llego');
                
                
                if($_id_activos_fijos > 0){
                    //die('llego');
                    
                    $_depreciacion_mensual_activos_fijos= ((int) $res->valor_activos_fijos)/((int) $res->meses_depreciacion_activos_fijos);
                    $_anio = date("Y", $_fecha_compra_activos_fijos);
                    $_fecha_cierre_anio_activos_fijos=$_anio.'12-31';
                    $diferencia = $_fecha_cierre_anio_activos_fijos->diff($_fecha_compra_activos_fijos);
                    $dias = ( $diferencia->y * 365 ) + $diferencia->d;
                    $_cant_meses_dep_activos_fijos=($dias)/30;
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
                              depreciacion_mensual_activos_fijos = '$_depreciacion_mensual_activos_fijos',
                              cant_meses_dep_activos_fijos      = '$_cant_meses_dep_activos_fijos',
                              fecha_cierre_anio_activos_fijos   = '$_fecha_cierre_anio_activos_fijos'
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
                    //die('llego');
                    $_depreciacion_mensual_activos_fijos= ((int) $_valor_activos_fijos)/((int) $_meses_depreciacion_activos_fijos);
                    
                    $_anio = date("Y", strtotime ($_fecha_compra_activos_fijos));
                  
                    $_fecha_cierre_anio_activos_fijos= new DateTime ($_anio.'12-31');
                    
                    $_fecha_compra_activos_fijos = new DateTime($_fecha_compra_activos_fijos);
                    
                    $diferencia = $_fecha_cierre_anio_activos_fijos->diff($_fecha_compra_activos_fijos);
                    $dias = ( $diferencia->y * 365 ) + $diferencia->d;
                    $_cant_meses_dep_activos_fijos=($dias)/30;
                    $_cant_meses_dep_activos_fijos= round ($_cant_meses_dep_activos_fijos*1);
                    
                    $_fecha_cierre_anio_activos_fijos = $_fecha_cierre_anio_activos_fijos ->format('Y-m-d');
                    $_fecha_compra_activos_fijos = $_fecha_compra_activos_fijos ->format('Y-m-d');
                    
                    
                   
                    
                    $funcion = "ins_activos_fijos";
                    $parametros = "'$_id_oficina', '$_id_tipo_activos_fijos', '$_id_estado', '$_id_usuarios', '$_nombre_activos_fijos', '$_codigo_activos_fijos', '$_fecha_compra_activos_fijos', '$_cantidad_activos_fijos', '$_valor_activos_fijos', '$_meses_depreciacion_activos_fijos', '$_depreciacion_mensual_activos_fijos', '$_cant_meses_dep_activos_fijos', '$_fecha_cierre_anio_activos_fijos'";
                    $activosf->setFuncion($funcion);
                    $activosf->setParametros($parametros);
                    $resultado=$activosf->Insert();
                    
                    
                }
                
            }
            
            $this->redirect("ActivosFijos", "index");
        }
        else
        {
            $this->view_Contable("Error",array(
                "resultado"=>"No tiene Permisos Para Crear Bodegas"
                
            ));
            
            
        }
        
        
        
    }
    
    public function generarReporteID()
    {
        session_start();
        
        $activosf=new ActivosFijosModel();
        /*$oficina=new OficinaModel();        
        $tipoactivos=new TipoActivosModel();
        $estado=new EstadoModel();
        $usuarios=new UsuariosModel();*/
        
        $html="";
        
        $fechaactual = getdate();
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        
        $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
        $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
        $domLogo=$directorio.'/view/images/logo.png';
        $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "ActivosFijos";
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
                                      activos_fijos.modificado,
                                      activos_fijos.cant_meses_dep_activos_fijos, 
                                      activos_fijos.fecha_cierre_anio_activos_fijos
                        
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
                    
                    $resultRep = $activosf->getCondiciones($columnas ,$tablas ,$where, $id);
                    
                    $html.= "<table align='center' style='width: 100%; border:1px black' border=1 cellspacing=0>";
                    $html.= "<tr>";
                    $html.='<th  style="text-align: center; font-size: 25px; ">CAPREMCI</br>';
                    $html.='<p style="text-align: center; font-size: 13px; "> Av. Baquerico Moreno E-9781 y Leonidas Plaza';
                    $html.='</tr>';
                    $html.='</table>';                    
                    
                    
                    if(!empty($resultRep)){
                        
                        foreach ($resultRep as $res)
                        {
                
                            
                            
                       
                        $html.='<table align="center" style="width: 100%; border:1px black solid;margin-top:10px">';
                        $html.='<tr>';
                        $html.='<td colspan="8">&nbsp;</td>';
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td colspan="2" style="text-align: left; font-size: 16px; "><b>Oficina:</b> '.$res->nombre_oficina.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><b>Tipo de Activo:</b> '.$res->nombre_tipo_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><b>Estado:</b> '.$res->nombre_estado.'</td>';
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td colspan="2" style="text-align: left; font-size: 16px; "><br><b>Usuario:</b> '.$res->nombre_usuarios.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Código:</b> '.$res->codigo_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Nombre:</b> '.$res->nombre_activos_fijos.'</td>';
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td colspan="2" style="text-align: left; font-size: 16px; "><br><b>Fecha:</b> '.$res->fecha_compra_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Cantidad de activos:</b> '.$res->cantidad_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Valor activos:</b> '.$res->valor_activos_fijos.'</td>';
                        $html.='</tr>';
                        $html.='<tr>';
                        $html.='<td colspan="5" style="text-align: left; font-size: 16px; "><br><b>Meses de depreciación:</b>'.$res->meses_depreciacion_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Depreciación:</b> '.$res->depreciacion_mensual_activos_fijos.'</p>'.'</td>';
                        $html.='<tr>';
                        $html.='<td colspan="8">&nbsp;</td>';
                        $html.='</tr>';
                        $html.='</tr>';
                       
    
                    }
                    $html.='</table>';
                    }
                    
                    
                    
                    $this->report("ActivosFijos",array( "resultSet"=>$html));
                    die();
                    
                }
                    
                    
                    
                }    
            }
            else
            {
                $this->view_Contable("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Bodegas"
                    
                ));
                
                exit();
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
            
            $this->redirect("ActivosFijos", "index");
            
            
        }
        else
        {
            $this->view_Contable("Error",array(
                "resultado"=>"No tiene Permisos de Borrar Bodegas"
                
            ));
        }
        
    }
    
    public function exportar_activos_fijos(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $usuarios = new UsuariosModel();
        $catalogo = new CatalogoModel();
        $where_to="";
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
                      activos_fijos.modificado";
        $tablas   = "
                      public.activos_fijos,
                      public.oficina,
                      public.tipo_activos_fijos,
                      public.estado,
                      public.usuarios
                    ";
        $where    = " oficina.id_oficina = activos_fijos.id_oficina AND
                      tipo_activos_fijos.id_tipo_activos_fijos = activos_fijos.id_tipo_activos_fijos AND
                      estado.id_estado = activos_fijos.id_estado AND
                      usuarios.id_usuarios = activos_fijos.id_usuarios
                      ";
        $id       = "activos_fijos.id_activos_fijos";
        
        
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
            $resultSet=$usuarios->getCondiciones($columnas, $tablas, $where_to, $id);
            $_respuesta=array();
            
            array_push($_respuesta, 'Oficina', 'Tipo de Activo', 'Estado','Usuario','Código','Nombre','Fecha','Cantidad de activos',
                'Valor activos','Meses de depreciación','Depreciación');
            foreach ($resultSet as $res)
                {
                    array_push($_respuesta, $res->nombre_oficina,$res->nombre_tipo_activos_fijos,$res->nombre_estado,$res->nombre_usuarios,
                        $res->codigo_activos_fijos,$res->nombre_activos_fijos,$res->fecha_compra_activos_fijos,$res->cantidad_activos_fijos,
                        $res->valor_activos_fijos,$res->meses_depreciacion_activos_fijos,$res->depreciacion_mensual_activos_fijos);
                }
            
            
                echo json_encode($_respuesta);
            die();
            
        }
        
        
        
        
    }
    
    
    public function consulta_activos_fijos(){
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $usuarios = new UsuariosModel();
        $catalogo = null; $catalogo = new CatalogoModel();
        $where_to="";
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
                      activos_fijos.modificado,
                      activos_fijos.cant_meses_dep_activos_fijos, 
                      activos_fijos.fecha_cierre_anio_activos_fijos";
        $tablas   = " 
                      public.activos_fijos, 
                      public.oficina, 
                      public.tipo_activos_fijos, 
                      public.estado, 
                      public.usuarios
                    ";
        $where    = " oficina.id_oficina = activos_fijos.id_oficina AND
                      tipo_activos_fijos.id_tipo_activos_fijos = activos_fijos.id_tipo_activos_fijos AND
                      estado.id_estado = activos_fijos.id_estado AND
                      usuarios.id_usuarios = activos_fijos.id_usuarios 
                      ";
        $id       = "activos_fijos.id_activos_fijos";
        
        
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
                $html.= "<table id='tabla_activos_fijos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Tipo de Activo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad de activos</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor activos</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Meses de depreciación</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad Meses</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Depreciación Mensual</th>';
                
                
                if($id_rol==1){
                    
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    
                }
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                $depreciacionmensual=0;
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $depreciacionmensual=((int) $res->valor_activos_fijos)/((int) $res->meses_depreciacion_activos_fijos);
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->codigo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->fecha_compra_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->cantidad_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->valor_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->meses_depreciacion_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->cant_meses_dep_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$depreciacionmensual= number_format($depreciacionmensual, 2, '.', ' ').'</td>';
                    
                    
                  
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ActivosFijos&action=index&id_activos_fijos='.$res->id_activos_fijos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ActivosFijos&action=borrarId&id_activos_fijos='.$res->id_activos_fijos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ActivosFijos&action=generarReporteID&id_activos_fijos='.$res->id_activos_fijos.'" class="btn btn-primary" style="font-size:65%;" target = "blank"><i class="fa fa-file-pdf-o"></i></a></span></td>';
                        
                    }
                    
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
    

    
    
    
}
?>