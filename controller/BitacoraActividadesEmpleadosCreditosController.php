<?php

class BitacoraActividadesEmpleadosCreditosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bitacora_creditos = new CreditosModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_creditos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
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
        $rsEmpleados    = $bitacora_creditos->getCondicionesSinOrden( $col1, $tab1, $whe1, "");
        $desde = '8:00:00';
        $hasta = '16:45:00';
        
        
        $this->view_Core("BitacoraActividadesEmpleadosCreditos",array(
            "resultSet"=>"", "rsEmpleados"=>$rsEmpleados, "desde"=>$desde, "hasta"=>$hasta
            
        ));
        
        
    }
    
    
    public function InsertaBitacoraCreditos(){
         session_start();
        
        $bitacora_creditos = new CreditosModel();
           
            $_fecha_registro = (isset($_POST["fecha_registro"])) ? $_POST["fecha_registro"] : "" ;
            $_desde = (isset($_POST["desde"])) ? $_POST["desde"] : "" ;
            $_hasta = (isset($_POST["hasta"])) ? $_POST["hasta"] : "" ;
            $_id_empleados = (isset($_POST["id_empleados"])) ? $_POST["id_empleados"] : 0 ;
            $_id_participes = (isset($_POST["id_participes"]) && $_POST["id_participes"]>0) ? $_POST["id_participes"] : 'null' ;
            $_creditos = (isset($_POST["creditos"])) ? $_POST["creditos"] : 0 ;
            $_cesantia = (isset($_POST["cesantia"])) ? $_POST["cesantia"] : 0 ;
            $_desafiliacion = (isset($_POST["desafiliacion"])) ? $_POST["desafiliacion"] : 0 ;
            $_superavit = (isset($_POST["superavit"])) ? $_POST["superavit"] : 0 ;
            $_diferimiento = (isset($_POST["diferimiento"])) ? $_POST["diferimiento"] : 0 ;
            $_refinanciamiento_reestructuracion = (isset($_POST["refinanciamiento_reestructuracion"])) ? $_POST["refinanciamiento_reestructuracion"] : 0 ;
            $_elaboracion_memorando = (isset($_POST["elaboracion_memorando"])) ? $_POST["elaboracion_memorando"] : "" ;
            $_otras_actividades = (isset($_POST["otras_actividades"])) ? $_POST["otras_actividades"] : "" ;
            $_atencion_creditos = (isset($_POST["atencion_creditos"])) ? $_POST["atencion_creditos"] : 0 ;
            $_entrega_documentos_creditos = (isset($_POST["entrega_documentos_creditos"])) ? $_POST["entrega_documentos_creditos"] : 0 ;
            $_atencion_cesantias = (isset($_POST["atencion_cesantias"])) ? $_POST["atencion_cesantias"] : 0 ;
            $_entrega_documentos_cesantias = (isset($_POST["entrega_documentos_cesantias"])) ? $_POST["entrega_documentos_cesantias"] : 0 ;
            $_atencion_desafiliaciones = (isset($_POST["atencion_desafiliaciones"])) ? $_POST["atencion_desafiliaciones"] : 0 ;
            $_entrega_documentos_desafiliaciones = (isset($_POST["entrega_documentos_desafiliaciones"])) ? $_POST["entrega_documentos_desafiliaciones"] : 0 ;
            $_atencion_superavit = (isset($_POST["atencion_superavit"])) ? $_POST["atencion_superavit"] : 0 ;
            $_entrega_documentos_superavit = (isset($_POST["entrega_documentos_superavit"])) ? $_POST["entrega_documentos_superavit"] : 0 ;
            $_atencion_refinanciamiento_reestructuracion = (isset($_POST["atencion_refinanciamiento_reestructuracion"])) ? $_POST["atencion_refinanciamiento_reestructuracion"] : 0 ;
            $_entrega_documentos_refinanciamiento_reestructuracion = (isset($_POST["entrega_documentos_refinanciamiento_reestructuracion"])) ? $_POST["entrega_documentos_refinanciamiento_reestructuracion"] : 0 ;
            $_atencion_diferimiento = (isset($_POST["atencion_diferimiento"])) ? $_POST["atencion_diferimiento"] : 0 ;
            $_claves = (isset($_POST["claves"])) ? $_POST["claves"] : 0 ;
            $_consultas_varias = (isset($_POST["consultas_varias"])) ? $_POST["consultas_varias"] : 0 ;
            $_id_bitacora_actividades_empleados_creditos = (isset($_POST["id_bitacora_actividades_empleados_creditos"])) ? $_POST["id_bitacora_actividades_empleados_creditos"] : 0 ;
            
            $funcion = "ins_core_bitacora_actividades_empleados_creditos";
            $respuesta = 0 ;
            $mensaje = "";
            
            $_creditos = ( $_creditos =="1" ) ? "t" : "f";
            $_cesantia = ( $_cesantia =="1" ) ? "t" : "f";
            $_desafiliacion = ( $_desafiliacion =="1" ) ? "t" : "f";
            $_superavit = ( $_superavit =="1" ) ? "t" : "f";
            $_diferimiento = ( $_diferimiento =="1" ) ? "t" : "f";
            $_refinanciamiento_reestructuracion = ( $_refinanciamiento_reestructuracion =="1" ) ? "t" : "f";
            $_atencion_creditos = ( $_atencion_creditos =="1" ) ? "t" : "f";
            $_entrega_documentos_creditos = ( $_entrega_documentos_creditos =="1" ) ? "t" : "f";
            $_atencion_cesantias = ( $_atencion_cesantias =="1" ) ? "t" : "f";
            $_entrega_documentos_cesantias = ( $_entrega_documentos_cesantias =="1" ) ? "t" : "f";
            $_atencion_desafiliaciones = ( $_atencion_desafiliaciones =="1" ) ? "t" : "f";
            $_entrega_documentos_desafiliaciones = ( $_entrega_documentos_desafiliaciones =="1" ) ? "t" : "f";
            $_atencion_superavit = ( $_atencion_superavit =="1" ) ? "t" : "f";
            $_entrega_documentos_superavit = ( $_entrega_documentos_superavit =="1" ) ? "t" : "f";
            $_atencion_refinanciamiento_reestructuracion = ( $_atencion_refinanciamiento_reestructuracion =="1" ) ? "t" : "f";
            $_entrega_documentos_refinanciamiento_reestructuracion = ( $_entrega_documentos_refinanciamiento_reestructuracion =="1" ) ? "t" : "f";
            $_atencion_diferimiento = ( $_atencion_diferimiento =="1" ) ? "t" : "f";
            $_claves = ( $_claves =="1" ) ? "t" : "f";
            $_consultas_varias = ( $_consultas_varias =="1" ) ? "t" : "f";
            
            if($_id_bitacora_actividades_empleados_creditos == 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_creditos',
                               '$_cesantia',
                               '$_desafiliacion',
                               '$_superavit',
                               '$_diferimiento',
                               '$_refinanciamiento_reestructuracion',
                               '$_elaboracion_memorando',
                               '$_otras_actividades',
                               '$_atencion_creditos',
                               '$_entrega_documentos_creditos',
                               '$_atencion_cesantias',
                               '$_entrega_documentos_cesantias',
                               '$_atencion_desafiliaciones',
                               '$_entrega_documentos_desafiliaciones',
                               '$_atencion_superavit',
                               '$_entrega_documentos_superavit',
                               '$_atencion_refinanciamiento_reestructuracion',
                               '$_entrega_documentos_refinanciamiento_reestructuracion',
                               '$_atencion_diferimiento',
                               '$_claves',
                               '$_consultas_varias',
                               '$_id_bitacora_actividades_empleados_creditos'";
                $bitacora_creditos->setFuncion($funcion);
                $bitacora_creditos->setParametros($parametros);
                
                //echo "SELECT ". $funcion." ( ".$parametros." )"; die();
                $resultado = $bitacora_creditos->llamafuncionPG();
                
                if(is_int((int)$resultado[0])){
                    $respuesta = $resultado[0];
                    $mensaje = "Ingresado Correctamente";
                }
                
              
                
            }elseif ($_id_bitacora_actividades_empleados_creditos > 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_creditos',
                               '$_cesantia',
                               '$_desafiliacion',
                               '$_superavit',
                               '$_diferimiento',
                               '$_refinanciamiento_reestructuracion',
                               '$_elaboracion_memorando',
                               '$_otras_actividades',
                               '$_atencion_creditos',
                               '$_entrega_documentos_creditos',
                               '$_atencion_cesantias',
                               '$_entrega_documentos_cesantias',
                               '$_atencion_desafiliaciones',
                               '$_entrega_documentos_desafiliaciones',
                               '$_atencion_superavit',
                               '$_entrega_documentos_superavit',
                               '$_atencion_refinanciamiento_reestructuracion',
                               '$_entrega_documentos_refinanciamiento_reestructuracion',
                               '$_atencion_diferimiento',
                               '$_claves',
                               '$_consultas_varias',
                               '$_id_bitacora_actividades_empleados_creditos'";
                $bitacora_creditos->setFuncion($funcion);
                $bitacora_creditos->setParametros($parametros);
                $resultado = $bitacora_creditos->llamafuncionPG();
                
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
    
    
    public function editBitacoraCreditos(){
        
        session_start();
        $bitacora_creditos = new CreditosModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_creditos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_bitacora_actividades_empleados_creditos"])){
                
                $id_bitacora_actividades_empleados_creditos = (int)$_POST["id_bitacora_actividades_empleados_creditos"];
                
                $query = "SELECT a.*, b.cedula_participes, b.nombres_participes FROM core_bitacora_actividades_empleados_creditos a
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )b  on  b.id_participes=a.id_participes
                WHERE a.id_bitacora_actividades_empleados_creditos = $id_bitacora_actividades_empleados_creditos";
                
                $resultado  = $bitacora_creditos->enviaquery($query);
                
                echo json_encode(array('data'=>$resultado));
                
            }
            
            
        }
        else
        {
            echo "No Tiene Permisos Editar";
        }
        
    }
    
    
    public function delBitacoraCreditos(){
        
        session_start();
        $bitacora_creditos = new BitacoraCreditosModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_creditos->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            if(isset($_POST["id_bitacora_actividades_empleados_creditos"])){
                
                $id_bitacora_actividades_empleados_creditos = (int)$_POST["id_bitacora_actividades_empleados_creditos"];
                
                $resultado  = $bitacora_creditos->eliminarBy("id_bitacora_actividades_empleados_creditos ",$id_bitacora_actividades_empleados_creditos);
                
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
    
    
    public function consultaBitacoraCreditos(){
        
        session_start();
         
        $bitacora_creditos = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $columnas ="a.id_bitacora_actividades_empleados_creditos,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.creditos,
                    a.cesantia,
                    a.desafiliacion,
                    a.superavit,
                    a.diferimiento,
                    a.refinanciamiento_reestructuracion,
                    a.elaboracion_memorando,
                    a.otras_actividades,
                    a.atencion_creditos,
                    a.entrega_documentos_creditos,
                    a.atencion_cesantias,
                    a.entrega_documentos_cesantias,
                    a.atencion_desafiliaciones,
                    a.entrega_documentos_desafiliaciones,
                    a.atencion_superavit,
                    a.entrega_documentos_superavit,
                    a.atencion_refinanciamiento_reestructuracion,
                    a.entrega_documentos_refinanciamiento_reestructuracion,
                    a.atencion_diferimiento,
                    a.claves,
                    a.consultas_varias";
        $tablas  = "core_bitacora_actividades_empleados_creditos a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_creditos";

        
        
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
            $resultSet=$bitacora_creditos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$bitacora_creditos->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
                $html.='<th style="text-align: center;  font-size: 10px;">Créditos</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Cesantía</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Desafiliación</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Superavit</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Diferimiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Refinanciamiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Momorando</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Créditos</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Documentos Créditos</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Cesantías</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Documentos Cesantías</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Desafiliaciones</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Documentos Desafiliaciones</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Superavit</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Documentos Superavit</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Refinanciamiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Documentos Refinanciamiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Atención Diferimiento</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Claves</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Consultas</th>';
                
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
                    $creditos   = ( $res->creditos == "t") ? "X" : "";
                    $cesantia   = ( $res->cesantia == "t") ? "X" : "";
                    $desafiliacion   = ( $res->desafiliacion == "t") ? "X" : "";
                    $superavit   = ( $res->superavit == "t") ? "X" : "";
                    $diferimiento   = ( $res->diferimiento == "t") ? "X" : "";
                    $refinanciamiento_reestructuracion   = ( $res->refinanciamiento_reestructuracion == "t") ? "X" : "";
                    $atencion_creditos   = ( $res->atencion_creditos == "t") ? "X" : "";
                    $entrega_documentos_creditos   = ( $res->entrega_documentos_creditos == "t") ? "X" : "";
                    $atencion_cesantias   = ( $res->atencion_cesantias == "t") ? "X" : "";
                    $entrega_documentos_cesantias   = ( $res->entrega_documentos_cesantias == "t") ? "X" : "";
                    $atencion_desafiliaciones   = ( $res->atencion_desafiliaciones == "t") ? "X" : "";
                    $entrega_documentos_desafiliaciones   = ( $res->entrega_documentos_desafiliaciones == "t") ? "X" : "";
                    $atencion_superavit   = ( $res->atencion_superavit == "t") ? "X" : "";
                    $entrega_documentos_superavit   = ( $res->entrega_documentos_superavit == "t") ? "X" : "";
                    $atencion_refinanciamiento_reestructuracion   = ( $res->atencion_refinanciamiento_reestructuracion == "t") ? "X" : "";
                    $entrega_documentos_refinanciamiento_reestructuracion   = ( $res->entrega_documentos_refinanciamiento_reestructuracion == "t") ? "X" : "";
                    $atencion_diferimiento   = ( $res->atencion_diferimiento == "t") ? "X" : "";
                    $claves   = ( $res->claves == "t") ? "X" : "";
                    $consultas_varias   = ( $res->consultas_varias == "t") ? "X" : "";
                    
                    $html.='<tr>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$i.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->fecha_registro.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->desde.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->hasta.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$creditos.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$cesantia.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$desafiliacion.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$superavit.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$diferimiento.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$refinanciamiento_reestructuracion.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->elaboracion_memorando.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_creditos.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$entrega_documentos_creditos.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_cesantias.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$entrega_documentos_cesantias.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_desafiliaciones.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$entrega_documentos_desafiliaciones.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_superavit.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$entrega_documentos_superavit.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_refinanciamiento_reestructuracion.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$entrega_documentos_refinanciamiento_reestructuracion.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$atencion_diferimiento.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$claves.'</td>';
                    $html.='<td style="text-align: center; font-size: 20px;">'.$consultas_varias.'</td>';
                     
                    
                    
                    /*comentario up */
                    
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editBitacoraCreditos('.$res->id_bitacora_actividades_empleados_creditos.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delBitacoraCreditos('.$res->id_bitacora_actividades_empleados_creditos.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaBitacoraCreditos").'';
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
    
    public function ReporteBitacoraCreditos(){
        
        session_start();
        
        $bitacora_creditos = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $datos_reporte = array();
        $columnas ="a.id_bitacora_actividades_empleados_creditos,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.creditos,
                    a.cesantia,
                    a.desafiliacion,
                    a.superavit,
                    a.diferimiento,
                    a.refinanciamiento_reestructuracion,
                    a.elaboracion_memorando,
                    a.otras_actividades,
                    a.atencion_creditos,
                    a.entrega_documentos_creditos,
                    a.atencion_cesantias,
                    a.entrega_documentos_cesantias,
                    a.atencion_desafiliaciones,
                    a.entrega_documentos_desafiliaciones,
                    a.atencion_superavit,
                    a.entrega_documentos_superavit,
                    a.atencion_refinanciamiento_reestructuracion,
                    a.entrega_documentos_refinanciamiento_reestructuracion,
                    a.atencion_diferimiento,
                    a.claves,
                    a.consultas_varias,
                    d.nombre_cargo";
        $tablas  = "core_bitacora_actividades_empleados_creditos a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes
                inner join cargos_empleados d on b.id_cargo_empleado = d.id_cargo";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_creditos";
        
        
        
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
            $html.='<th style="text-align: center;  font-size: 10px;">Créditos</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Cesantía</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Desafiliación</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Superavit</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Diferimiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Refinanciamiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Momorando</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Créditos</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Documentos Créditos</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Cesantías</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Documentos Cesantías</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Desafiliaciones</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Documentos Desafiliaciones</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Superavit</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Documentos Superavit</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Refinanciamiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Documentos Refinanciamiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Atención Diferimiento</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Claves</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Consultas</th>';
            $html.='</tr>';
            
            
            $i=0;
            foreach ($bitacora_detalle as $res)
            {
                
                $i++;
                $creditos   = ( $res->creditos == "t") ? "X" : "";
                $cesantia   = ( $res->cesantia == "t") ? "X" : "";
                $desafiliacion   = ( $res->desafiliacion == "t") ? "X" : "";
                $superavit   = ( $res->superavit == "t") ? "X" : "";
                $diferimiento   = ( $res->diferimiento == "t") ? "X" : "";
                $refinanciamiento_reestructuracion   = ( $res->refinanciamiento_reestructuracion == "t") ? "X" : "";
                $atencion_creditos   = ( $res->atencion_creditos == "t") ? "X" : "";
                $entrega_documentos_creditos   = ( $res->entrega_documentos_creditos == "t") ? "X" : "";
                $atencion_cesantias   = ( $res->atencion_cesantias == "t") ? "X" : "";
                $entrega_documentos_cesantias   = ( $res->entrega_documentos_cesantias == "t") ? "X" : "";
                $atencion_desafiliaciones   = ( $res->atencion_desafiliaciones == "t") ? "X" : "";
                $entrega_documentos_desafiliaciones   = ( $res->entrega_documentos_desafiliaciones == "t") ? "X" : "";
                $atencion_superavit   = ( $res->atencion_superavit == "t") ? "X" : "";
                $entrega_documentos_superavit   = ( $res->entrega_documentos_superavit == "t") ? "X" : "";
                $atencion_refinanciamiento_reestructuracion   = ( $res->atencion_refinanciamiento_reestructuracion == "t") ? "X" : "";
                $entrega_documentos_refinanciamiento_reestructuracion   = ( $res->entrega_documentos_refinanciamiento_reestructuracion == "t") ? "X" : "";
                $atencion_diferimiento   = ( $res->atencion_diferimiento == "t") ? "X" : "";
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
                $html.='<td style="text-align: center; font-size: 10px;">'.$creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$cesantia.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$desafiliacion.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$superavit.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$diferimiento.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$refinanciamiento_reestructuracion.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->elaboracion_memorando.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$entrega_documentos_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_cesantias.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$entrega_documentos_cesantias.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_desafiliaciones.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$entrega_documentos_desafiliaciones.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_superavit.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$entrega_documentos_superavit.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_refinanciamiento_reestructuracion.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$entrega_documentos_refinanciamiento_reestructuracion.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$atencion_diferimiento.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$claves.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$consultas_varias.'</td>';
                $html.='</tr>';
            }
            
            $html.='</table>';
            $datos_reporte['DETALLE']= $html;
            $datos_reporte['nombres_empleados']=$bitacora_detalle[0]->nombres_empleados;
            $datos_reporte['nombre_cargo']=$bitacora_detalle[0]->nombre_cargo;
            
            $datos_reporte['fecha_reg']=$bitacora_detalle[0]->fecha_registro;
            $datos_reporte['desde_reg']=$bitacora_detalle[0]->desde;
            $datos_reporte['hasta_reg']=$bitacora_detalle[0]->hasta;
            
            
            
          /*  $datos_reporte['fecha_registro']=$resultSet[0]->fecha_registro;
            $datos_reporte['desde']=$resultSet[0]->desde;
            $datos_reporte['hasta']=$resultSet[0]->hasta;
            $datos_reporte['nombres_empleados']=$resultSet[0]->nombres_empleados;
            $datos_reporte['cedula_participes']=$resultSet[0]->nombres_participes;
            $datos_reporte['creditos']=$resultSet[0]->creditos;
            $datos_reporte['cesantia']=$resultSet[0]->cesantia;
            $datos_reporte['desafiliacion']=$resultSet[0]->desafiliacion;
            $datos_reporte['superavit']=$resultSet[0]->superavit;
            $datos_reporte['diferimiento']=$resultSet[0]->diferimiento;
            $datos_reporte['refinanciamiento_reestructuracion']=$resultSet[0]->refinanciamiento_reestructuracion;
            $datos_reporte['elaboracion_memorando']=$resultSet[0]->elaboracion_memorando;
            $datos_reporte['otras_actividades']=$resultSet[0]->otras_actividades;
            $datos_reporte['atencion_creditos']=$resultSet[0]->atencion_creditos;
            $datos_reporte['entrega_documentos_creditos']=$resultSet[0]->entrega_documentos_creditos;
            $datos_reporte['atencion_cesantias']=$resultSet[0]->atencion_cesantias;
            $datos_reporte['entrega_documentos_cesantias']=$resultSet[0]->entrega_documentos_cesantias;
            $datos_reporte['atencion_desafiliaciones']=$resultSet[0]->atencion_desafiliaciones;
            $datos_reporte['entrega_documentos_desafiliaciones']=$resultSet[0]->entrega_documentos_desafiliaciones;
            $datos_reporte['atencion_superavit']=$resultSet[0]->atencion_superavit;
            $datos_reporte['entrega_documentos_superavit']=$resultSet[0]->entrega_documentos_superavit;
            $datos_reporte['atencion_refinanciamiento_reestructuracion']=$resultSet[0]->atencion_refinanciamiento_reestructuracion;
            $datos_reporte['entrega_documentos_refinanciamiento_reestructuracion']=$resultSet[0]->entrega_documentos_refinanciamiento_reestructuracion;
            $datos_reporte['atencion_diferimiento']=$resultSet[0]->atencion_diferimiento;
            $datos_reporte['claves']=$resultSet[0]->claves;
            $datos_reporte['consultas_varias']=$resultSet[0]->consultas_varias;
            $datos_reporte['nombre_cargo']=$resultSet[0]->nombre_cargo;*/
            
            
            
            $this->verReporte("ReporteBitacoraCreditos", array('datos_reporte'=>$datos_reporte ));
        
            
        }
        
        
    }
    
}
?>