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
                                      activos_fijos.modificado,
                                      activos_fijos.cant_meses_dep_activos_fijos, 
                                      activos_fijos.fecha_cierre_anio_activos_fijos, 
                                      activos_fijos.imagen_activos_fijos, 
                                      activos_fijos.color_activos_fijos, 
                                      activos_fijos.material_activos_fijos, 
                                      activos_fijos.dimension_activos_fijos
                                    
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
        
        //die('llego');
        if (!empty($resultPer))
        {
            //die('llego');
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
                $_color_activos_fijos = $_POST["color_activos_fijos"];
                $_material_activos_fijos = $_POST["material_activos_fijos"];
                $_dimension_activos_fijos = $_POST["dimension_activos_fijos"];
                
                
                //die('llego');
                
                
                $imagen_activos='';
                
                
                if ($_FILES['imagen_activos_fijos']['tmp_name']!="")
                {
                    $directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/Imagenes_activos/';
                    
                    $nombre = $_FILES['imagen_activos_fijos']['name'];
                    $tipo = $_FILES['imagen_activos_fijos']['type'];
                    $tamano = $_FILES['imagen_activos_fijos']['size'];
                    
                    move_uploaded_file($_FILES['imagen_activos_fijos']['tmp_name'],$directorio.$nombre);
                    $data = file_get_contents($directorio.$nombre);
                    $imagen_activos = pg_escape_bytea($data);
                    
                }else{
                    
                    $directorio = dirname(__FILE__).'\..\view\images\nofoto.png';
                    
                    if( is_file( $directorio )){
                        $data = file_get_contents($directorio);
                        $imagen_activos = pg_escape_bytea($data);
                    }
                    
                }
                
                
                
                if($_id_activos_fijos > 0){
                    //die('llego');
                    
                    $_depreciacion_mensual_activos_fijos= ((int) $_valor_activos_fijos)/((int) $_meses_depreciacion_activos_fijos);
                    $_anio = explode("-", $_fecha_compra_activos_fijos);
                    $_anio1= $_anio[0];
                    //die($_anio1);
                    $_fecha_cierre_anio_activos_fijos=(int) $_anio1.'-12-31';
                    
                    $fecha1 = new DateTime($_fecha_cierre_anio_activos_fijos);
                    $fecha2 = new DateTime($_fecha_compra_activos_fijos);
                    $diferencia = $fecha2->diff($fecha1);
                    
                    $dias_diferencia = $diferencia ->days;
                    $años = $diferencia ->y;
                    
                    $dias = ((int) $años * 365 ) + ((int) $dias_diferencia);
                    $_cant_meses_dep_activos=($dias)/30;
                    
                    $_cant_meses_dep_activos_fijos =((int)$_cant_meses_dep_activos);
                    
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
                              fecha_cierre_anio_activos_fijos   = '$_fecha_cierre_anio_activos_fijos',
                              imagen_activos_fijos      = '$imagen_activos',
                              color_activos_fijos   = '$_color_activos_fijos',
                              material_activos_fijos   = '$_material_activos_fijos',
                              dimension_activos_fijos   = '$_dimension_activos_fijos'
                              ";
                    
                    $tabla = "public.activos_fijos";
                    $where = "activos_fijos.id_activos_fijos = '$_id_activos_fijos'";
                    $resultado=$activosf->UpdateBy($columnas, $tabla, $where);
                    
                }else{
                   
                    $_depreciacion_mensual_activos_fijos= ((int) $_valor_activos_fijos)/((int) $_meses_depreciacion_activos_fijos);
                    
                    $_anio = explode("-", $_fecha_compra_activos_fijos);
                    $_anio1= $_anio[0];
                    //die($_anio1);
                    $_fecha_cierre_anio_activos_fijos=(int) $_anio1.'-12-31';
                    
                    $fecha1 = new DateTime($_fecha_cierre_anio_activos_fijos);
                    $fecha2 = new DateTime($_fecha_compra_activos_fijos);
                    $diferencia = $fecha2->diff($fecha1);
                    
                    $dias_diferencia = $diferencia ->days;
                    $años = $diferencia ->y;
                    
                    $dias = ((int) $años * 365 ) + ((int) $dias_diferencia);
                    $_cant_meses_dep_activos=($dias)/30;
                    
                    $_cant_meses_dep_activos_fijos =((int)$_cant_meses_dep_activos);
                    
                    $_fecha_cierre_anio_activos_fijos = $_fecha_cierre_anio_activos_fijos ->format('Y-m-d');
                    $_fecha_compra_activos_fijos = $_fecha_compra_activos_fijos ->format('Y-m-d');
                    
                  
                        
                   
                    
                    $funcion = "ins_activos_fijos";
                    $parametros = "'$_id_oficina',
                                   '$_id_tipo_activos_fijos',
                                   '$_id_estado', 
                                   '$_id_usuarios',
                                   '$_nombre_activos_fijos',
                                   '$_codigo_activos_fijos',
                                   '$_fecha_compra_activos_fijos',
                                   '$_cantidad_activos_fijos',
                                   '$_valor_activos_fijos',
                                   '$_meses_depreciacion_activos_fijos',
                                   '$_depreciacion_mensual_activos_fijos',
                                   '$_cant_meses_dep_activos_fijos',
                                   '$_fecha_cierre_anio_activos_fijos',
                                   '$imagen_activos',
                                   '$_color_activos_fijos',
                                   '$_material_activos_fijos',
                                   '$_dimension_activos_fijos'";
                    $activosf->setFuncion($funcion);
                    $activosf->setParametros($parametros);
                    $resultado=$activosf->Insert();
                    
                    //print_r($resultado);
                    //die('llego');
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
                        $html.='<td colspan="5" style="text-align: left; font-size: 16px; "><br><b>Meses de depreciación:&nbsp;</b>'.$res->meses_depreciacion_activos_fijos.'</td>';
                        $html.='<td colspan="3" style="text-align: left; font-size: 16px; "><br><b>Depreciación Mensual:</b> '.$res->depreciacion_mensual_activos_fijos.'</p>'.'</td>';
                        $html.='<tr>';
                        $html.='<td colspan="8">&nbsp;</td>';
                        $html.='</tr>';
                        $html.='</tr>';
                        $html.='</table>';
                        
                        
                        $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                        $html.= "<tr>";
                        $html.='<th colspan="2" style="text-align: center; font-size: 25px;">Depreciación Mensual</th>';
                        $html.='</tr>';
                       
                        
                        if(!empty($resultRep)){
                            
                            $html.= "<table style='width: 100%; margin-top:10px;' border=1 cellspacing=0>";
                            
                            $html.= "<tr>";
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Enero</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Febrero</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Marzo</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Abril</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Mayo</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Junio</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Julio</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Agosto</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Septiembre</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Octubre</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Noviembre</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">Diciembre</th>';
                            $html.='<th colspan="2" style="text-align: center; font-size: 13px;">D.Acumulada</th>';
                            
                            $html.='</tr>';
                            
                            
                            
                            foreach ($resultRep as $res)
                            {
                               
                                
                            }
                            $html.='</table>';
                            
                            
                            
                        }
                        
                       
    
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
                      activos_fijos.fecha_cierre_anio_activos_fijos,
                      activos_fijos.imagen_activos_fijos, 
                      activos_fijos.color_activos_fijos, 
                      activos_fijos.material_activos_fijos, 
                      activos_fijos.dimension_activos_fijos,
                      color_activos_fijos,
                      material_activos_fijos,
                      dimension_activos_fijos ";
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
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Tipo&nbsp;de&nbsp;Activo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Estado&nbsp;del&nbsp;Activo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Fecha&nbsp;de&nbsp;Ingreso&nbsp;</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad&nbsp;de&nbsp;activos</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor&nbsp;</th>';
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
                    $html.='<td style="font-size: 11px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_activos_fijos.'&id_nombre=id_activos_fijos&tabla=activos_fijos&campo=imagen_activos_fijos" width="80" height="60"></td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->codigo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->nombre_estado.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->fecha_compra_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->cantidad_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->valor_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->meses_depreciacion_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->cant_meses_dep_activos_fijos.'</td>';
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
    
    public function paginate($reload, $page, $tpages, $adjacents , $function='') {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$function(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$function(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$function(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='$function(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$function(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$function($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$function(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    /*dc 20190402 */
    public function index1(){
        
        session_start();
        
        $oficina = new OficinaModel();        
        $resultOfi = $oficina->getBy("1=1");
        
        $tipo_activo = new TipoActivosModel();
        $resultTipoac = $tipo_activo->getBy("1=1");
        
        $EstadosActivo = new EstadoModel();
        $rsEstadoAct = $EstadosActivo->getBy("tabla_estado = 'ACTIVOS' ");   
        
        $departamento = new DepartamentosModel();
        $rsDepartamento = $departamento -> getBy(" id_estado = ( SELECT id_estado FROM estado WHERE tabla_estado = 'DEPARTAMENTOS' AND nombre_estado = 'ACTIVO')");
        
        $Empleados = new EmpleadosModel();
        $resultEmp = $Empleados->getBy("1=1");
        
        $TAG = new RfidTagModel();
        $resultTag = $TAG->getBy("1=1");
               
        $this->view_Activos('ActivosFijos', array('resultOfi'=>$resultOfi,'resultTipoac'=>$resultTipoac,'rsEstadoAct'=>$rsEstadoAct,'rsDepartamento'=>$rsDepartamento,'resultEmp'=>$resultEmp,'resultTag'=>$resultTag));
    }
    
    public function insActivos(){
        
        session_start();
        
        $activos = new ActivosFijosModel();
        
        $id_usuario = (isset($_SESSION['id_usuarios'])) ? $_SESSION['id_usuarios'] : 0 ;
        
        $_id_oficina = (isset($_POST['id_oficina'])) ? $_POST['id_oficina'] : 0;
        $_id_tipo_activo = (isset($_POST['id_tipo_activos_fijos'])) ? $_POST['id_tipo_activos_fijos'] : 0;
        $_id_estado = (isset($_POST['id_estado'])) ? $_POST['id_estado'] : 0;
        $_id_empleados = (isset($_POST['id_empleados'])) ? $_POST['id_empleados'] : 0;
        $_nombre_activo = (isset($_POST['nombre_activos_fijos'])) ? $_POST['nombre_activos_fijos'] : 0;
        $_fecha_activo = (isset($_POST['fecha_activos_fijos'])) ? $_POST['fecha_activos_fijos'] : 0;
        $_detalles_activo = (isset($_POST['detalle_activos_fijos'])) ? $_POST['detalle_activos_fijos'] : 0;
        $_valor_activo = (isset($_POST['valor_activos_fijos'])) ? $_POST['valor_activos_fijos'] : 0;
        $_id_rfid_tag = (isset($_POST['id_rfid_tag'])) ? $_POST['id_rfid_tag'] : 0;
        
        //para saber si existe actualizacion de activo
        $_id_activo_fijo = (isset($_POST['id_activos_fijos'])) ? $_POST['id_activos_fijos'] : 0;
        
        $_codigo_activo = "";
        
        //para la imagen de activos
        $_imagen_activos = ''; 
        
        if ($_FILES['imagen_activos_fijos']['tmp_name']!="")
        {
            $directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/fotografias_usuarios/';
            
            $nombre = $_FILES['imagen_activos_fijos']['name'];
            //$tipo = $_FILES['imagen_activos_fijos']['type'];
            //$tamano = $_FILES['imagen_activos_fijos']['size'];
            
            move_uploaded_file($_FILES['imagen_activos_fijos']['tmp_name'],$directorio.$nombre);
            $data = file_get_contents($directorio.$nombre);
            $_imagen_activos = pg_escape_bytea($data);
            
        }else{
            
            $directorio = dirname(__FILE__).'\..\view\images\nodisponible.jpg';
            
            if( is_file( $directorio )){
                $data = file_get_contents($directorio);
                $_imagen_activos = pg_escape_bytea($data);
            }
            
        }
        
            //para determinar los parametros que se envian a la 
            //funcion del postgres
            
            $funcion = "ins_activosfijos";
                        
            if($_id_activo_fijo > 0 ){
                
                $operacion = 1;
                //ingresa a actualizacion del activo
                $parametros = "'$id_usuario',
                           '$_id_oficina',
	    				   '$_id_tipo_activo',
                           '$_id_estado',
                           '$_id_empleados',
	    	               '$_nombre_activo',
	    	               '$_codigo_activo',
	    	               '$_fecha_activo',
	    	               '$_detalles_activo',
	    	               '$_imagen_activos',
                           '$_valor_activo',
                           '$_id_rfid_tag',
                           '$_id_activo_fijo'";
                
                $activos->setFuncion($funcion);
                $activos->setParametros($parametros);                
               
                $resultado=$activos->llamafuncion();
                
                $respuesta = "";
                $valor = 0;
                
                if(!empty($resultado) && count($resultado) > 0){
                    
                    foreach ($resultado[0] as $k => $v){
                        $respuesta = $v;
                        $respuesta = "Activo Actualizado";
                        $valor = 1;
                    }
                }                
                
                //paea depreciacion
                $funcionDepreciacion = "fn_act_depreciacion";
                $fecha_hoy = date('Y-m-d');                
                $parametrosDepreciacion = "'$_id_activo_fijo','$fecha_hoy'";                
                $queryDepreciacion = "SELECT ". $funcionDepreciacion." ( ".$parametrosDepreciacion." ) ";
                $resultadoDepreciacion = $activos->llamarconsultaPG($queryDepreciacion);
                
                if(strpos($resultadoDepreciacion[0], "CORRECTAMENTE") === false){
                    
                    echo "depreciacion ha fallado"; die();
                }
                
                echo json_encode(array( 'valor'=>$valor,'mensaje'=>$respuesta));
                
                
            }elseif($_id_activo_fijo == 0){
                
                //ingresa a actualizacion del activo
                $parametros = "'$id_usuario',
                           '$_id_oficina',
	    				   '$_id_tipo_activo',
                           '$_id_estado',
                           '$_id_empleados',
	    	               '$_nombre_activo',
	    	               '$_codigo_activo',
	    	               '$_fecha_activo',
	    	               '$_detalles_activo',
	    	               '$_imagen_activos',
                           '$_valor_activo',
                           '$_id_rfid_tag',
                           '0'";
                
                $activos->setFuncion($funcion);
                $activos->setParametros($parametros);
                
                $resultado=$activos->llamafuncion();
                
                $respuesta = "";
                
                if(!empty($resultado) && count($resultado) > 0){
                    
                    foreach ($resultado[0] as $k => $v){
                        $respuesta = $v;
                    }
                }
                
                if( $respuesta > 0 ){
                    
                    $depreciacion = new DepreciacionModel();
                    
                    $id_act_fijo = $respuesta;
                    
                    $funcion = "fn_act_depreciacion";
                    $fecha_hoy = date('Y-m-d');
                    
                    $parametros = "'$id_act_fijo',
                           '$fecha_hoy'";
                    
                    //print_r($parametros); die();
                    $depreciacion->setFuncion($funcion);
                    $depreciacion->setParametros($parametros);
                    
                    $resultadoDepreciacion = $depreciacion->llamafuncion();
                    
                }
                
                if(!empty($resultadoDepreciacion) && count($resultadoDepreciacion)> 0){
                    
                    foreach ($resultadoDepreciacion[0] as $k => $v){
                        $respuesta = $v;
                    }
                }
                
                $valor = ($respuesta=="")?0:1;
                
                echo json_encode(array( 'valor'=>$valor,'mensaje'=>$respuesta));
                
            }
       
        
    }
    
    public function cunsultaActivos(){
        
        session_start();
        
        $usuarios = new UsuariosModel();
        
        $id_rol = $_SESSION['id_rol'];
        
        //$activos = new ActivosFijosModel();
        
        $where_to="";
        
        $columnas = "*";
        
        $tablas   = "vw_activos_fijos";
        
        $where    = " 1 = 1 ";
        
        $id       = "id_activos_fijos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (nombre_activos_fijos LIKE '".$search."%' OR nombres_empleados LIKE '".$search."%' OR nombre_oficina LIKE '".$search."%')";
                
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
            $total_pages = ceil($cantidadResult/$per_page);            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Usuario</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Código</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Tipo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Fecha Ingreso</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Responsable</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">TAG</th>';
                
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
                    $html.='<td style="font-size: 11px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_activos_fijos.'&id_nombre=id_activos_fijos&tabla=act_activos_fijos&campo=imagen_activos_fijos" width="80" height="60"></td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                    $html.='<td style="font-size: 11px;">00'.$res->codigo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px;"align="right">'.$res->valor_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->nombre_estado.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->fecha_activos_fijos.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 11px; text-align: center;">'.$res->numero_rfid_tag.'</td>';
                    
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a target="_blank" href="index.php?controller=ActivosFijos&action=verDepreciacionInd&id_activos_fijos='.$res->id_activos_fijos.'" class="" style="font-size:65%;"><i class="fa fa-sitemap"></i> Ver Depreciacion</a></span></td>';
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a id="'.$res->id_activos_fijos.'" href="#" target="blank" class="editaActivo" style="font-size:65%;" ><i class="fa fa-pencil-square-o"></i> Editar </a></span></td>';
                        
                    }
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_act_fijos("index.php", $page, $total_pages, $adjacents," ").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b> Datos No Encontrados</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    
    public function paginate_act_fijos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='consultaActivos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='consultaActivos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='consultaActivos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='consultaActivos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='consultaActivos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='consultaActivos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='consultaActivos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    
    public function verDepreciacionInd(){
        session_start();
        
        $anio = date('Y');
        
        $entidades = new EntidadesModel();
        $activos = new ActivosFijosModel();
        
        $_id_activo_fijo = $_GET['id_activos_fijos'];
        
        $datosActivo = array();
        
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
        
        /*para tomar datos de activo*/
        
        $queryAcivo = "SELECT 
                        ofi.id_oficina,
                        ofi.nombre_oficina,
                        ta.id_tipo_activos_fijos,
                        ta.nombre_tipo_activos_fijos,
                    	ta.meses_tipo_activos_fijos,
                        es.id_estado,
                        es.nombre_estado,
                        ac.id_activos_fijos,                        
                        ac.nombre_activos_fijos,
                        ac.codigo_activos_fijos,
                        ac.fecha_activos_fijos,
                        ac.detalle_activos_fijos,
                        ac.imagen_activos_fijos,
                        ROUND(ac.valor_activos_fijos,2) valor_activos_fijos ,
                        dep.nombre_departamento,
                        emp.nombres_empleados
                       FROM act_activos_fijos ac
                         JOIN tipo_activos_fijos ta ON  ta.id_tipo_activos_fijos = ac.id_tipo_activos_fijos
                         JOIN estado es ON es.id_estado = ac.id_estado
                         JOIN oficina ofi ON ofi.id_oficina = ac.id_oficina
                         JOIN empleados emp ON emp.id_empleados = ac.id_empleados
                         JOIN departamentos dep ON dep.id_departamento = emp.id_departamento
                      WHERE 1 = 1 AND ac.id_activos_fijos = $_id_activo_fijo ";
        
        /*OBTIENE RS DE CONSULTA*/
        $rsDatosActivo = $activos->enviaquery($queryAcivo);
        
        //PARA VER EL ESTADO DEL ACTIVO 
        $queryEstado = "SELECT estado_depreciacion 
                        FROM act_depreciacion 
                        WHERE id_activos_fijos = $_id_activo_fijo 
                        ORDER BY anio_depreciacion DESC LIMIT 1";
        
        $rsEstadoActivo = $activos->enviaquery($queryEstado);
        
        $estadoActivo = ($rsEstadoActivo[0]->estado_depreciacion == "t")?"ACTIVO DEPRECIADO":"";
        
        //TRABAJAR CON RESULTADO DE CONSULTA
        if(!empty($rsDatosActivo) && count($rsDatosActivo)>0){
            //LLENAR ARRAY QUE VA HTML CON SUS NOMBRES
            $datosActivo['GRUPOACTIVO']=$rsDatosActivo[0]->nombre_tipo_activos_fijos." ( ".$rsDatosActivo[0]->meses_tipo_activos_fijos." MESES )";
            $datosActivo['ESTADOACTIVO']=$rsDatosActivo[0]->nombre_estado;
            $datosActivo['CODIGOACTIVO']=$rsDatosActivo[0]->codigo_activos_fijos;
            $datosActivo['CANTIDADACTIVO']=count($rsDatosActivo);
            $datosActivo['NOMBREACTIVO']=$rsDatosActivo[0]->nombre_activos_fijos;
            $datosActivo['FECHAACTIVO'] = $rsDatosActivo[0]->fecha_activos_fijos;
            $datosActivo['VALORACTIVO'] = $rsDatosActivo[0]->valor_activos_fijos;
            $datosActivo['DESCACTIVO'] = $rsDatosActivo[0]->detalle_activos_fijos;
            $datosActivo['UBIACTIVO'] = $rsDatosActivo[0]->nombre_departamento; /*despues de creacion tabla departamento*/
            $datosActivo['RESPACTIVO'] = $rsDatosActivo[0]->nombre_empleados;
            $datosActivo['IDACTIVO'] = $rsDatosActivo[0]->id_activos_fijos;
            $datosActivo['ISDEPRECIADO'] = $estadoActivo;
        }
        
        /*para obtener la depreciacion del activo*/
        $queryDepreciacion = "SELECT  id_activos_fijos ,
        anio_depreciacion ,ROUND(enero_depreciacion,2) enero_depreciacion ,
        ROUND(febrero_depreciacion,2) febrero_depreciacion,ROUND(marzo_depreciacion,2) marzo_depreciacion,
        ROUND(abril_depreciacion,2) abril_depreciacion,ROUND(mayo_depreciacion,2) mayo_depreciacion,
        ROUND(junio_depreciacion,2) junio_depreciacion,ROUND(julio_depreciacion,2) julio_depreciacion,
        ROUND(agosto_depreciacion,2) agosto_depreciacion,ROUND(septiembre_depreciacion,2) septiembre_depreciacion,
        ROUND(octubre_depreciacion,2) octubre_depreciacion,ROUND(noviembre_depreciacion,2) noviembre_depreciacion,
        ROUND(diciembre_depreciacion,2) diciembre_depreciacion,ROUND(total_depreciacion,2) total_depreciacion,
        ROUND(valor_depreciacion,2) valor_depreciacion,ROUND(saldo_depreciacion,2) saldo_depreciacion,
        estado_depreciacion
        FROM act_depreciacion WHERE id_activos_fijos = $_id_activo_fijo ";
        
        /*OBTIENE RS DE CONSULTA*/
        $rsDatosDepreciacion = $activos->enviaquery($queryDepreciacion);
        
        //die();
        $this->verReporte("ActFijos", array('datos_empresa'=>$datos_empresa,'datosActivo' => $datosActivo,'rsDatosDepreciacion'=>$rsDatosDepreciacion));
    }
    
    public function editActivo(){
        
        if(!isset($_POST['peticion']) ){ 
            
            echo "peticion fallida";
            return;
        }
        
        if(!isset($_POST['activoId']) ){
            
            echo "parametros incorrectos";
            return;
        }
        
        
        $activos = new ActivosFijosModel();
        
        $_id_activo_fijo =  $_POST['activoId'];
                
        $queryAcivo = "SELECT
                        ofi.id_oficina,
                        ofi.nombre_oficina,
                        ta.id_tipo_activos_fijos,
                        ta.nombre_tipo_activos_fijos,
                        ta.meses_tipo_activos_fijos,
                        es.id_estado,
                        es.nombre_estado,
                        ac.id_activos_fijos,
                        ac.nombre_activos_fijos,
                        ac.codigo_activos_fijos,
                        ac.fecha_activos_fijos,
                        ac.detalle_activos_fijos,
                        ROUND(ac.valor_activos_fijos,2) valor_activos_fijos,
                        emp.nombres_empleados,
                        emp.id_empleados,
                        dep.id_departamento,
                        dep.nombre_departamento,
                        rf.id_rfid_tag,
                        rf.numero_rfid_tag
                        
                        FROM act_activos_fijos ac
                        JOIN tipo_activos_fijos ta ON  ta.id_tipo_activos_fijos = ac.id_tipo_activos_fijos
                        JOIN estado es ON es.id_estado = ac.id_estado
                        JOIN oficina ofi ON ofi.id_oficina = ac.id_oficina
                        JOIN empleados emp ON emp.id_empleados = ac.id_empleados
                        JOIN departamentos dep ON dep.id_departamento = emp.id_departamento
                        JOIN rfid_tag rf ON rf.id_rfid_tag = ac.id_rfid_tag
                        
                        WHERE 1 = 1 AND ac.id_activos_fijos = $_id_activo_fijo ";
        
        /*OBTIENE RS DE CONSULTA*/
        $rsDatosActivo = $activos->enviaquery($queryAcivo);             
        
        /*parametros de salida*/
        $outActivo = null;
        $respuesta = 0;
        
        if(!empty($rsDatosActivo) && count($rsDatosActivo)>0){
            
            $outActivo = $rsDatosActivo;
            $respuesta = 1;
        }
       
        echo json_encode(array('value' => $respuesta,'mensaje' => 'Exito','data'=>$outActivo));
    }
    
    
    public function depreciacionActivosIndex(){
        
        //dcarrillo 2019-04-09
        
        /* para validacion de acceso al preoceso */
        
        /*-----*/
        
        session_start();
        
        $this->view_Activos("DepreciacionActivos", array());
        
       
    }
    
    public function depreciacionAnual(){
        
       session_start();
       
       $depreciacion = new DepreciacionModel();
       
       $anio = date('Y');
       
       /*buscar anio en depreciacion historial*/
       
       $queryDepreciacion = "SELECT 1 AS RESULTADO FROM act_depreciacion WHERE anio_depreciacion = $anio LIMIT 1";
       
       $rsDepreciacion = $depreciacion->enviaquery($queryDepreciacion); 
       
       $salida = 0; 
       
       if(empty($rsDepreciacion) && count($rsDepreciacion)==0){
           
           $funcion = "fn_act_depreciacion_general";
           $parametros = "$anio";
           $depreciacion->setFuncion($funcion);
           $depreciacion->setParametros($parametros);
           $resultado = $depreciacion->llamafuncion();
           
           if(!empty($resultado) && count($resultado) > 0){
               
               foreach ($resultado[0] as $k => $v ){
                   $salida = $v;
               }
               
               if($salida == 1){
                   
                   echo json_encode(array('value' => 1 , 'mensaje' => "DEPRECIACION REALIZADA / REVISAR REPORTE DE ACTIVOS"));
                   
               }
           }
           
       }else{
           
           echo json_encode(array('value' => 2, 'mensaje' => "AÑO SOLICITADO YA SE ENCUENTRA INGRESADO AL SISTEMA / REVISAR REPORTE DE ACTIVOS"));
       }
       
       
    }
    
    public function VerDepreciacion(){
        session_start();
        $estado = new EstadoModel();
        $nombre_controladores = "ActivosFijos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado->getPermisosVer("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
           $this->view_Activos('DepreciacionGeneral', array());
        }
    }
    
    public function DepreciacionGeneral(){
        
        session_start();
        
        $anio = (isset($_POST['anio_depreciacion'])) ? $_POST['anio_depreciacion'] : date('Y');
        
        $entidades = new EntidadesModel();
        $activos = new ActivosFijosModel();
        
        $datosActivo = array();
        
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
            $datos_empresa['APERIODO']=$anio;
            $datos_empresa['APERIODOANT']=$anio-1;
        }
        
        /*para datos de activos*/
        
        $columnasActivos = " ac.id_tipo_activos_fijos, ac.codigo_activos_fijos, ac.nombre_activos_fijos, ac.fecha_activos_fijos, ac.detalle_activos_fijos,
				ac.imagen_activos_fijos, ROUND(ac.valor_activos_fijos,2) valor_activos_fijos,
				ofi.nombre_oficina,ofi.id_oficina,ta.id_tipo_activos_fijos,ta.nombre_tipo_activos_fijos,ta.meses_tipo_activos_fijos,
				emp.id_empleados,emp.nombres_empleados, dep.nombre_departamento,vde.*";

        $tablasActivos = " act_activos_fijos ac
                INNER JOIN oficina ofi
                ON ac.id_oficina = ofi.id_oficina
                INNER JOIN tipo_activos_fijos ta
                ON ta.id_tipo_activos_fijos = ac.id_tipo_activos_fijos
                INNER JOIN empleados emp
                ON emp.id_empleados = ac.id_empleados
                INNER JOIN departamentos dep
                ON dep.id_departamento = emp.id_departamento
                INNER JOIN vw_depreciacion vde
                ON vde.id_activos_fijos = ac.id_activos_fijos";
        
        $whereActivos = " 1 = 1 AND vde.anio_depreciacion = $anio ";
        
        $orderActivos = " ac.id_tipo_activos_fijos";
        
        $datosActivo = $activos->getCondiciones($columnasActivos, $tablasActivos, $whereActivos, $orderActivos);
        
        $this->verReporte("Depreciacion", array('datos_empresa'=>$datos_empresa,'datosActivo' => $datosActivo));
    }
    
    
    public function devuelveEmpleado()
    {
        session_start();
        $resultEmp = array();
        
        
        if(isset($_POST["id_departamento_vivienda"]))
        {
            
            $id_departamento =(int)$_POST["id_departamento_vivienda"];
            
            $empleados=new EmpleadosModel();
            
            $resultEmp = $empleados->getCondiciones("id_empleados,nombres_empleados"," public.empleados ", "id_departamento = '$id_departamento'  ","nombres_empleados");
            
        }
        
        if(isset($_POST["id_departamento_asignacion"]))
        {
            
            $id_departamento=(int)$_POST["id_departamento_asignacion"];
            
            $empleados=new EmpleadosModel();
            
            $resultEmp = $empleados->getBy(" id_departamento = '$id_departamento'  ");
            
            
        }
        
        echo json_encode($resultEmp);
        
    }
    
    public function VerResumen(){
        
        session_start();
        $estado = new EstadoModel();
        $nombre_controladores = "ActivosFijos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado->getPermisosVer("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            $this->view_Activos('ResumenActivos', array());
        }
        
    }
    
    public function ResumenActivos(){
        
        session_start();
        
        $anio = (isset($_POST['anio_depreciacion'])) ? $_POST['anio_depreciacion'] : date('Y');
        
        $entidades = new EntidadesModel();
        $activos = new ActivosFijosModel();
        
        $datosActivo = array();
        
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
            $datos_empresa['APERIODO']=$anio;
            $datos_empresa['APERIODOANT']=$anio-1;
        }
        
        /*para datos de activos*/
        
        $columnasActivos = " ac.id_tipo_activos_fijos, ac.codigo_activos_fijos, ac.nombre_activos_fijos, ac.fecha_activos_fijos, ac.detalle_activos_fijos,
				ac.imagen_activos_fijos, ROUND(ac.valor_activos_fijos,2) valor_activos_fijos,
				ofi.nombre_oficina,ofi.id_oficina,ta.id_tipo_activos_fijos,ta.nombre_tipo_activos_fijos,ta.meses_tipo_activos_fijos,
				emp.id_empleados,emp.nombres_empleados, dep.nombre_departamento,vde.*";
        
        $tablasActivos = " act_activos_fijos ac
                INNER JOIN oficina ofi
                ON ac.id_oficina = ofi.id_oficina
                INNER JOIN tipo_activos_fijos ta
                ON ta.id_tipo_activos_fijos = ac.id_tipo_activos_fijos
                INNER JOIN empleados emp
                ON emp.id_empleados = ac.id_empleados
                INNER JOIN departamentos dep
                ON dep.id_departamento = emp.id_departamento
                INNER JOIN vw_depreciacion vde
                ON vde.id_activos_fijos = ac.id_activos_fijos";
        
        $whereActivos = " 1 = 1 AND vde.anio_depreciacion = $anio ";
        
        $orderActivos = " ac.id_tipo_activos_fijos";
        
        $datosActivo = $activos->getCondiciones($columnasActivos, $tablasActivos, $whereActivos, $orderActivos);
        
        $this->verReporte("Depreciacion", array('datos_empresa'=>$datos_empresa,'datosActivo' => $datosActivo));
    }
     
    
    
}
?>