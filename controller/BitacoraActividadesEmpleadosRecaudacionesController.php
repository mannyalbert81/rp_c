<?php

class BitacoraActividadesEmpleadosRecaudacionesController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bitacora_recaudaciones = new CreditosModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "BitacoraActividadesEmpleadosRecaudaciones";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_recaudaciones->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso"
                
            ));
            exit();
        }
        
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $col1 =" id_empleados, nombres_empleados";
        $tab1 = " empleados";
        $whe1 = " numero_cedula_empleados = '$cedula_usuarios'";
        $rsEmpleados    = $bitacora_recaudaciones->getCondicionesSinOrden( $col1, $tab1, $whe1, "");
        $desde = '8:00:00';
        $hasta = '16:45:00';
        
        
        $this->view_Core("BitacoraActividadesEmpleadosRecaudaciones",array(
            "resultSet"=>"", "rsEmpleados"=>$rsEmpleados, "desde"=>$desde, "hasta"=>$hasta
            
        ));
        
        
    }
    
    
    public function InsertaBitacoraRecaudaciones(){
         session_start();
        
         $bitacora_recaudaciones = new CreditosModel();
           
            $_fecha_registro = (isset($_POST["fecha_registro"])) ? $_POST["fecha_registro"] : "" ;
            $_desde = (isset($_POST["desde"])) ? $_POST["desde"] : "" ;
            $_hasta = (isset($_POST["hasta"])) ? $_POST["hasta"] : "" ;
            $_id_empleados = (isset($_POST["id_empleados"])) ? $_POST["id_empleados"] : 0 ;
            $_id_participes = (isset($_POST["id_participes"]) && $_POST["id_participes"]>0) ? $_POST["id_participes"] : 'null' ;
            $_cesantia = (isset($_POST["cesantia"])) ? $_POST["cesantia"] : 0 ;
            $_desafiliacion = (isset($_POST["desafiliacion"])) ? $_POST["desafiliacion"] : 0 ;
            $_creditos_en_mora = (isset($_POST["creditos_en_mora"])) ? $_POST["creditos_en_mora"] : 0 ;
            $_aportes = (isset($_POST["aportes"])) ? $_POST["aportes"] : 0 ;
            $_diferimiento = (isset($_POST["diferimiento"])) ? $_POST["diferimiento"] : 0 ;
            $_moras = (isset($_POST["moras"])) ? $_POST["moras"] : 0 ;
            $_credito = (isset($_POST["credito"])) ? $_POST["credito"] : 0 ;
            $_aporte = (isset($_POST["aporte"])) ? $_POST["aporte"] : 0 ;
            $_envio_archivo_entidad_patronal = (isset($_POST["envio_archivo_entidad_patronal"])) ? $_POST["envio_archivo_entidad_patronal"] : 0 ;
            $_recepcion_archivo_entidad_patronal = (isset($_POST["recepcion_archivo_entidad_patronal"])) ? $_POST["recepcion_archivo_entidad_patronal"] : 0 ;
            $_carga_archivo_banco = (isset($_POST["carga_archivo_banco"])) ? $_POST["carga_archivo_banco"] : 0 ;
            $_carga_archivo_sistema = (isset($_POST["carga_archivo_sistema"])) ? $_POST["carga_archivo_sistema"] : 0 ;
            $_registro_depositos_manuales = (isset($_POST["registro_depositos_manuales"])) ? $_POST["registro_depositos_manuales"] : 0 ;
            $_identificacion_dsc = (isset($_POST["identificacion_dsc"])) ? $_POST["identificacion_dsc"] : 0 ;
            $_elaboracion_memorando = (isset($_POST["elaboracion_memorando"])) ? $_POST["elaboracion_memorando"] : "" ;
            $_otras_actividades_desarrolladas = (isset($_POST["otras_actividades_desarrolladas"])) ? $_POST["otras_actividades_desarrolladas"] : "" ;
            $_atencion_cesantias = (isset($_POST["atencion_cesantias"])) ? $_POST["atencion_cesantias"] : 0 ;
            $_atencion_desafiliaciones = (isset($_POST["atencion_desafiliaciones"])) ? $_POST["atencion_desafiliaciones"] : 0 ;
            $_atencion_creditos_en_mora = (isset($_POST["atencion_creditos_en_mora"])) ? $_POST["atencion_creditos_en_mora"] : 0 ;
            $_atencion_aportes = (isset($_POST["atencion_aportes"])) ? $_POST["atencion_aportes"] : 0 ;
            $_atencion_diferimiento = (isset($_POST["atencion_diferimiento"])) ? $_POST["atencion_diferimiento"] : 0 ;
            $_atencion_refinanciamiento_reestructuracion = (isset($_POST["atencion_refinanciamiento_reestructuracion"])) ? $_POST["atencion_refinanciamiento_reestructuracion"] : 0 ;
            $_claves = (isset($_POST["claves"])) ? $_POST["claves"] : 0 ;
            $_consultas_varias = (isset($_POST["consultas_varias"])) ? $_POST["consultas_varias"] : 0 ;
            $_id_bitacora_actividades_empleados_recaudaciones = (isset($_POST["id_bitacora_actividades_empleados_recaudaciones"])) ? $_POST["id_bitacora_actividades_empleados_recaudaciones"] : 0 ;
               
            $funcion = "ins_core_bitacora_actividades_empleados_recaudaciones";
            $respuesta = 0 ;
            $mensaje = "";
            
            $_cesantia = ( $_cesantia =="1" ) ? "t" : "f";
            $_desafiliacion = ( $_desafiliacion =="1" ) ? "t" : "f";
            $_creditos_en_mora = ( $_creditos_en_mora =="1" ) ? "t" : "f";
            $_aportes = ( $_aportes =="1" ) ? "t" : "f";
            $_diferimiento = ( $_diferimiento =="1" ) ? "t" : "f";
            $_moras = ( $_moras =="1" ) ? "t" : "f";
            $_credito = ( $_credito =="1" ) ? "t" : "f";
            $_aporte = ( $_aporte =="1" ) ? "t" : "f";
            $_envio_archivo_entidad_patronal = ( $_envio_archivo_entidad_patronal =="1" ) ? "t" : "f";
            $_recepcion_archivo_entidad_patronal = ( $_recepcion_archivo_entidad_patronal =="1" ) ? "t" : "f";
            $_carga_archivo_banco = ( $_carga_archivo_banco =="1" ) ? "t" : "f";
            $_carga_archivo_sistema = ( $_carga_archivo_sistema =="1" ) ? "t" : "f";
            $_registro_depositos_manuales = ( $_registro_depositos_manuales =="1" ) ? "t" : "f";
            $_identificacion_dsc = ( $_identificacion_dsc =="1" ) ? "t" : "f";
            $_atencion_cesantias = ( $_atencion_cesantias =="1" ) ? "t" : "f";
            $_atencion_desafiliaciones = ( $_atencion_desafiliaciones =="1" ) ? "t" : "f";
            $_atencion_creditos_en_mora = ( $_atencion_creditos_en_mora =="1" ) ? "t" : "f";
            $_atencion_aportes = ( $_atencion_aportes =="1" ) ? "t" : "f";
            $_atencion_diferimiento = ( $_atencion_diferimiento =="1" ) ? "t" : "f";
            $_atencion_refinanciamiento_reestructuracion = ( $_atencion_refinanciamiento_reestructuracion =="1" ) ? "t" : "f";
            $_claves = ( $_claves =="1" ) ? "t" : "f";
            $_consultas_varias = ( $_consultas_varias =="1" ) ? "t" : "f";
            
            if($_id_bitacora_actividades_empleados_recaudaciones == 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_cesantia',
                               '$_desafiliacion',
                               '$_creditos_en_mora',
                               '$_aportes',
                               '$_diferimiento',
                               '$_moras',
                               '$_credito',
                               '$_aporte',
                               '$_envio_archivo_entidad_patronal',
                               '$_recepcion_archivo_entidad_patronal',
                               '$_carga_archivo_banco',
                               '$_carga_archivo_sistema',
                               '$_registro_depositos_manuales',
                               '$_identificacion_dsc',
                               '$_elaboracion_memorando',
                               '$_otras_actividades_desarrolladas',
                               '$_atencion_cesantias',
                               '$_atencion_desafiliaciones',
                               '$_atencion_creditos_en_mora',
                               '$_atencion_aportes',
                               '$_atencion_diferimiento',
                               '$_atencion_refinanciamiento_reestructuracion',
                               '$_claves',
                               '$_consultas_varias',
                               '$_id_bitacora_actividades_empleados_recaudaciones'";
                    $bitacora_recaudaciones->setFuncion($funcion);
                    $bitacora_recaudaciones->setParametros($parametros);
                
                //echo "SELECT ". $funcion." ( ".$parametros." )"; die();
                    $resultado = $bitacora_recaudaciones->llamafuncionPG();
                
                if(is_int((int)$resultado[0])){
                    $respuesta = $resultado[0];
                    $mensaje = "Ingresado Correctamente";
                }
                
              
                
            }elseif ($_id_bitacora_actividades_empleados_recaudaciones > 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_cesantia',
                               '$_desafiliacion',
                               '$_creditos_en_mora',
                               '$_aportes',
                               '$_diferimiento',
                               '$_moras',
                               '$_credito',
                               '$_aporte',
                               '$_envio_archivo_entidad_patronal',
                               '$_recepcion_archivo_entidad_patronal',
                               '$_carga_archivo_banco',
                               '$_carga_archivo_sistema',
                               '$_registro_depositos_manuales',
                               '$_identificacion_dsc',
                               '$_elaboracion_memorando',
                               '$_otras_actividades_desarrolladas',
                               '$_atencion_cesantias',
                               '$_atencion_desafiliaciones',
                               '$_atencion_creditos_en_mora',
                               '$_atencion_aportes',
                               '$_atencion_diferimiento',
                               '$_atencion_refinanciamiento_reestructuracion',
                               '$_claves',
                               '$_consultas_varias',
                               '$_id_bitacora_actividades_empleados_recaudaciones'";
                      $bitacora_recaudaciones->setFuncion($funcion);
                      $bitacora_recaudaciones->setParametros($parametros);
                      $resultado = $bitacora_recaudaciones->llamafuncionPG();
                
                if(is_int((int)$resultado[0])){
                    $respuesta = $resultado[0];
                    $mensaje = "Actualizado Correctamente";
                }
                
                
            }
            
            
            
            if((int)$respuesta > 0 ){
                
                echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
                exit();
            }
            
            echo "Error al Ingresar";
            exit();
            
       
        
    }
    
    
    public function editBitacoraRecaudaciones(){
        
        session_start();
        $bitacora_recaudaciones = new CreditosModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosRecaudaciones";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_recaudaciones->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_bitacora_actividades_empleados_recaudaciones"])){
                
                $id_bitacora_actividades_empleados_recaudaciones = (int)$_POST["id_bitacora_actividades_empleados_recaudaciones"];
                
                $query = "SELECT a.*, b.cedula_participes, b.nombres_participes FROM core_bitacora_actividades_empleados_recaudaciones a
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )b  on  b.id_participes=a.id_participes
                WHERE a.id_bitacora_actividades_empleados_recaudaciones = $id_bitacora_actividades_empleados_recaudaciones";
                
                $resultado  = $bitacora_recaudaciones->enviaquery($query);
                
                echo json_encode(array('data'=>$resultado));
                
            }
            
            
        }
        else
        {
            echo "No Tiene Permisos Editar";
        }
        
    }
    
    
    public function delBitacoraRecaudaciones(){
        
        session_start();
        $bitacora_recaudaciones = new BitacoraRecaudacionesModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosRecaudaciones";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_recaudaciones->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            if(isset($_POST["id_bitacora_actividades_empleados_recaudaciones"])){
                
                $id_bitacora_actividades_empleados_recaudaciones = (int)$_POST["id_bitacora_actividades_empleados_recaudaciones"];
                
                $resultado  = $bitacora_recaudaciones->eliminarBy("id_bitacora_actividades_empleados_recaudaciones ",$id_bitacora_actividades_empleados_recaudaciones);
                
                if( $resultado > 0 ){
                    
                    echo json_encode(array('data'=>$resultado));
                    
                }else{
                    
                    echo $resultado;
                }
                
                
                
            }
            
            
        }else{
            
            echo "No Tiene Permisos Eliminar";
        }
        
        
        
    }
    
    
    public function consultaBitacoraRecaudaciones(){
        
        session_start();
         
        $bitacora_recaudaciones = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $columnas ="a.id_bitacora_actividades_empleados_recaudaciones,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.cesantia,
                    a.desafiliacion,
                    a.creditos_en_mora,
                    a.aportes,
                    a.diferimiento,
                    a.moras,
                    a.credito,
                    a.aporte,
                    a.envio_archivo_entidad_patronal,
                    a.recepcion_archivo_entidad_patronal,
                    a.carga_archivo_banco,
                    a.carga_archivo_sistema,
                    a.registro_depositos_manuales,
                    a.identificacion_dsc,
                    a.elaboracion_memorando,
                    a.otras_actividades_desarrolladas,
                    a.atencion_cesantias,
                    a.atencion_desafiliaciones,
                    a.atencion_creditos_en_mora,
                    a.atencion_aportes,
                    a.atencion_diferimiento,
                    a.atencion_refinanciamiento_reestructuracion,
                    a.claves,
                    a.consultas_varias";
        $tablas  = "core_bitacora_actividades_empleados_recaudaciones a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_recaudaciones";

        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        $fecha_registro_desde =  (isset($_REQUEST['fecha_registro_desde'])&& $_REQUEST['fecha_registro_desde'] !=NULL)?$_REQUEST['fecha_registro_desde']:'';
        $fecha_registro_hasta =  (isset($_REQUEST['fecha_registro_hasta'])&& $_REQUEST['fecha_registro_hasta'] !=NULL)?$_REQUEST['fecha_registro_hasta']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                $where.=" AND (c.cedula_participes ILIKE '".$search."%' OR a.elaboracion_memorando ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%')";
            }
            
            if(!empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' ";
            }
            if(!empty($search) && !empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' AND (c.cedula_participes ILIKE '".$search."%' OR a.elaboracion_memorando ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%')";
            }
            
            
            
            
            $where_to=$where;
            $html="";
            $resultSet=$bitacora_recaudaciones->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$bitacora_recaudaciones->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table id='tabla_bitacora_creditos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: center;  font-size: 10px;">#</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Fecha</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Desde</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Hasta</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Cédula</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Participes</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Revisión Cesantía</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Revisión Desafiliación</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Revisión Créditos en Mora</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Revisión Aportes</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Revisión Diferimiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Moras</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Crédito</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Aporte</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Envio Arch. Entidad</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Recepción Arch. Entidad</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Carga Arch. Banco</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Carga Arch. Sistema</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Depositos Manuales</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Identificación DSC</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Memorando</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Cesantías</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Desafiliaciones</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Créditos Mora</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Aportes</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Diferimiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Refinanciamiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Claves</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Consultas Varias</th>';
                
           
                
                /*para administracion definir administrador MenuOperaciones Edit - Eliminar*/
                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
             
                foreach ($resultSet as $res)
                {
                    
                    $i++;
                    $cesantia   = ( $res->cesantia == "t") ? "X" : "";
                    $desafiliacion   = ( $res->desafiliacion == "t") ? "X" : "";
                    $creditos_en_mora   = ( $res->creditos_en_mora == "t") ? "X" : "";
                    $aportes   = ( $res->aportes == "t") ? "X" : "";
                    $diferimiento   = ( $res->diferimiento == "t") ? "X" : "";
                    $moras   = ( $res->moras == "t") ? "X" : "";
                    $credito   = ( $res->credito == "t") ? "X" : "";
                    $aporte   = ( $res->aporte == "t") ? "X" : "";
                    $envio_archivo_entidad_patronal   = ( $res->envio_archivo_entidad_patronal == "t") ? "X" : "";
                    $recepcion_archivo_entidad_patronal   = ( $res->recepcion_archivo_entidad_patronal == "t") ? "X" : "";
                    $carga_archivo_banco   = ( $res->carga_archivo_banco == "t") ? "X" : "";
                    $carga_archivo_sistema   = ( $res->carga_archivo_sistema == "t") ? "X" : "";
                    $registro_depositos_manuales   = ( $res->registro_depositos_manuales == "t") ? "X" : "";
                    $identificacion_dsc   = ( $res->identificacion_dsc == "t") ? "X" : "";
                    $atencion_cesantias   = ( $res->atencion_cesantias == "t") ? "X" : "";
                    $atencion_desafiliaciones   = ( $res->atencion_desafiliaciones == "t") ? "X" : "";
                    $atencion_creditos_en_mora   = ( $res->atencion_creditos_en_mora == "t") ? "X" : "";
                    $atencion_aportes   = ( $res->atencion_aportes == "t") ? "X" : "";
                    $atencion_diferimiento   = ( $res->atencion_diferimiento == "t") ? "X" : "";
                    $atencion_refinanciamiento_reestructuracion   = ( $res->atencion_refinanciamiento_reestructuracion == "t") ? "X" : "";
                    $claves   = ( $res->claves == "t") ? "X" : "";
                    $consultas_varias   = ( $res->consultas_varias == "t") ? "X" : "";
            
                    $html.='<tr>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$i.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->fecha_registro.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->desde.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->hasta.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$cesantia.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$desafiliacion.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$creditos_en_mora.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$aportes.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$diferimiento.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$moras.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$credito.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$aporte.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$envio_archivo_entidad_patronal.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$recepcion_archivo_entidad_patronal.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$carga_archivo_banco.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$carga_archivo_sistema.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$registro_depositos_manuales.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$identificacion_dsc.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->elaboracion_memorando.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades_desarrolladas.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_cesantias.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_desafiliaciones.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_creditos_en_mora.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_aportes.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_diferimiento.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_refinanciamiento_reestructuracion.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$claves.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$consultas_varias.'</td>';
                     
                    
                    
                    /*comentario up */
                    
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editBitacoraRecaudaciones('.$res->id_bitacora_actividades_empleados_recaudaciones.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delBitacoraRecaudaciones('.$res->id_bitacora_actividades_empleados_recaudaciones.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaBitacoraRecaudaciones").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
        
    }
    public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
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
    
   
    public function autocompleteCedulaParticipes(){
        
        $planCuentas = new PlanCuentasModel();
        
        if(isset($_GET['term'])){
            
            $codigo_plan_cuentas = $_GET['term'];
            
            $columnas = " id_participes, cedula_participes, nombre_participes, apellido_participes";
            $tablas = " public.core_participes";
            $where = " cedula_participes ILIKE '$codigo_plan_cuentas%' ";
            $id = " cedula_participes ";
            $limit = "LIMIT 10";
                       
            $rsPlanCuentas = $planCuentas->getCondicionesPag($columnas,$tablas,$where,$id,$limit);
            
            $respuesta = array();
            
            if(!empty($rsPlanCuentas) ){
                
                foreach ($rsPlanCuentas as $res){
                    
                    $_cls_plan_cuentas = new stdClass;
                    $_cls_plan_cuentas->id = $res->id_participes;
                    $_cls_plan_cuentas->value = $res->cedula_participes;
                    $_cls_plan_cuentas->label = $res->cedula_participes.' | '.$res->apellido_participes.' - '.$res->nombre_participes;
                    $_cls_plan_cuentas->nombre = $res->apellido_participes.' - '.$res->nombre_participes;
                    
                    $respuesta[] = $_cls_plan_cuentas;
                }
                
                echo json_encode($respuesta);
                
            }else{
                
                echo '[{"id":"","value":"Participe No Encontrado"}]';
            }
            
        }
    }
    
    public function ReporteBitacoraRecaudaciones(){
        
        session_start();
        
        $bitacora_creditos = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $datos_reporte = array();
        
        $columnas ="a.id_bitacora_actividades_empleados_recaudaciones,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.cesantia,
                    a.desafiliacion,
                    a.creditos_en_mora,
                    a.aportes,
                    a.diferimiento,
                    a.moras,
                    a.credito,
                    a.aporte,
                    a.envio_archivo_entidad_patronal,
                    a.recepcion_archivo_entidad_patronal,
                    a.carga_archivo_banco,
                    a.carga_archivo_sistema,
                    a.registro_depositos_manuales,
                    a.identificacion_dsc,
                    a.elaboracion_memorando,
                    a.otras_actividades_desarrolladas,
                    a.atencion_cesantias,
                    a.atencion_desafiliaciones,
                    a.atencion_creditos_en_mora,
                    a.atencion_aportes,
                    a.atencion_diferimiento,
                    a.atencion_refinanciamiento_reestructuracion,
                    a.claves,
                    a.consultas_varias,
                    d.nombre_cargo";
        $tablas  = "core_bitacora_actividades_empleados_recaudaciones a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes
                inner join cargos_empleados d on b.id_cargo_empleado = d.id_cargo";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_recaudaciones";
        
       
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        $fecha_registro_desde =  (isset($_REQUEST['fecha_registro_desde'])&& $_REQUEST['fecha_registro_desde'] !=NULL)?$_REQUEST['fecha_registro_desde']:'';
        $fecha_registro_hasta =  (isset($_REQUEST['fecha_registro_hasta'])&& $_REQUEST['fecha_registro_hasta'] !=NULL)?$_REQUEST['fecha_registro_hasta']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                $where.=" AND (c.cedula_participes ILIKE '".$search."%' OR a.elaboracion_memorando ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%')";
            }
            if(!empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' ";
            }
            if(!empty($search) && !empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' AND (c.cedula_participes ILIKE '".$search."%' OR a.elaboracion_memorando ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%')";
            }
            
            $where_to=$where;
            $bitacora_detalle=$bitacora_creditos->getCondiciones($columnas, $tablas, $where_to, $id );
            
            //var_dump($resultSet); die();
            
          
            
            $html='';
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1">';
            $html.='<tr class="1">';
            $html.='<th style="text-align: center;  font-size: 10px;">#</th>';
            /*
            $html.='<th style="text-align: center;  font-size: 10px;">Fecha</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Desde</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Hasta</th>';
            */
            
            $html.='<th style="text-align: center;  font-size: 10px;">Cédula</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Participes</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Revisión Cesantía</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Revisión Desafiliación</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Revisión Créditos en Mora</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Revisión Aportes</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Revisión Diferimiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Moras</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Crédito</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Trabajo Aporte</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Envio Arch. Entidad</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Recepción Arch. Entidad</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Carga Arch. Banco</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Carga Arch. Sistema</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Depositos Manuales</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Identificación DSC</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Memorando</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Cesantías</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Desafiliaciones</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Créditos Mora</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Aportes</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Diferimiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Refinanciamiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Claves</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Consultas Varias</th>';
            $html.='</tr>';
            
            
            $i=0;
            foreach ($bitacora_detalle as $res)
            {
                
                $i++;
                $cesantia   = ( $res->cesantia == "t") ? "X" : "";
                $desafiliacion   = ( $res->desafiliacion == "t") ? "X" : "";
                $creditos_en_mora   = ( $res->creditos_en_mora == "t") ? "X" : "";
                $aportes   = ( $res->aportes == "t") ? "X" : "";
                $diferimiento   = ( $res->diferimiento == "t") ? "X" : "";
                $moras   = ( $res->moras == "t") ? "X" : "";
                $credito   = ( $res->credito == "t") ? "X" : "";
                $aporte   = ( $res->aporte == "t") ? "X" : "";
                $envio_archivo_entidad_patronal   = ( $res->envio_archivo_entidad_patronal == "t") ? "X" : "";
                $recepcion_archivo_entidad_patronal   = ( $res->recepcion_archivo_entidad_patronal == "t") ? "X" : "";
                $carga_archivo_banco   = ( $res->carga_archivo_banco == "t") ? "X" : "";
                $carga_archivo_sistema   = ( $res->carga_archivo_sistema == "t") ? "X" : "";
                $registro_depositos_manuales   = ( $res->registro_depositos_manuales == "t") ? "X" : "";
                $identificacion_dsc   = ( $res->identificacion_dsc == "t") ? "X" : "";
                $atencion_cesantias   = ( $res->atencion_cesantias == "t") ? "X" : "";
                $atencion_desafiliaciones   = ( $res->atencion_desafiliaciones == "t") ? "X" : "";
                $atencion_creditos_en_mora   = ( $res->atencion_creditos_en_mora == "t") ? "X" : "";
                $atencion_aportes   = ( $res->atencion_aportes == "t") ? "X" : "";
                $atencion_diferimiento   = ( $res->atencion_diferimiento == "t") ? "X" : "";
                $atencion_refinanciamiento_reestructuracion   = ( $res->atencion_refinanciamiento_reestructuracion == "t") ? "X" : "";
                $claves   = ( $res->claves == "t") ? "X" : "";
                $consultas_varias   = ( $res->consultas_varias == "t") ? "X" : "";
                
                
                $html.='<tr>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$i.'</td>';
                /*
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->fecha_registro.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->desde.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->hasta.'</td>';
                */
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$cesantia.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$desafiliacion.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$creditos_en_mora.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$aportes.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$diferimiento.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$moras.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$credito.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$aporte.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$envio_archivo_entidad_patronal.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$recepcion_archivo_entidad_patronal.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$carga_archivo_banco.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$carga_archivo_sistema.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$registro_depositos_manuales.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$identificacion_dsc.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->elaboracion_memorando.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades_desarrolladas.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_cesantias.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_desafiliaciones.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_creditos_en_mora.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_aportes.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_diferimiento.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_refinanciamiento_reestructuracion.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$claves.'</td>';
                $html.='<td style="text-align: center; font-size: 20px;">'.$consultas_varias.'</td>';
                
                
                $html.='</tr>';
            }
            
            $html.='</table>';
            $datos_reporte['DETALLE']= $html;
            $datos_reporte['nombres_empleados']=$bitacora_detalle[0]->nombres_empleados;
            $datos_reporte['nombre_cargo']=$bitacora_detalle[0]->nombre_cargo;
            
            $datos_reporte['fecha_reg']=$bitacora_detalle[0]->fecha_registro;
            $datos_reporte['desde_reg']=$bitacora_detalle[0]->desde;
            $datos_reporte['hasta_reg']=$bitacora_detalle[0]->hasta;
            
            
     
            
            $this->verReporte("ReporteBitacoraRecaudaciones", array('datos_reporte'=>$datos_reporte ));
        
            
        }
        
        
    }
    
}
?>