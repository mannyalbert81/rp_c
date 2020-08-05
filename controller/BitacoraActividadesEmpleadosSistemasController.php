<?php

class BitacoraActividadesEmpleadosSistemasController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bitacora_sistemas = new CreditosModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "BitacoraActividadesEmpleadosSistemas";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_sistemas->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
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
        $rsEmpleados    = $bitacora_sistemas->getCondicionesSinOrden( $col1, $tab1, $whe1, "");
        $desde = '8:00:00';
        $hasta = '16:45:00';
        
        
        $this->view_Core("BitacoraActividadesEmpleadosSistemas",array(
            "resultSet"=>"", "rsEmpleados"=>$rsEmpleados, "desde"=>$desde, "hasta"=>$hasta
            
        ));
        
        
    }
    
    
    public function InsertaBitacoraCreditos(){
         session_start();
        
         $bitacora_sistemas = new CreditosModel();
           
            $_fecha_registro = (isset($_POST["fecha_registro"])) ? $_POST["fecha_registro"] : "" ;
            $_desde = (isset($_POST["desde"])) ? $_POST["desde"] : "" ;
            $_hasta = (isset($_POST["hasta"])) ? $_POST["hasta"] : "" ;
            $_id_empleados = (isset($_POST["id_empleados"])) ? $_POST["id_empleados"] : 0 ;
            $_id_participes = (isset($_POST["id_participes"]) && $_POST["id_participes"]>0) ? $_POST["id_participes"] : 'null' ;
            $_credito = (isset($_POST["credito"])) ? $_POST["credito"] : "" ;
            $_prestaciones = (isset($_POST["prestaciones"])) ? $_POST["prestaciones"] : "" ;
            $_recaudaciones = (isset($_POST["recaudaciones"])) ? $_POST["recaudaciones"] : "" ;
            $_tesoreria = (isset($_POST["tesoreria"])) ? $_POST["tesoreria"] : "" ;
            $_contabilidad = (isset($_POST["contabilidad"])) ? $_POST["contabilidad"] : "" ;
            $_auditoria = (isset($_POST["auditoria"])) ? $_POST["auditoria"] : "" ;
            $_sistemas = (isset($_POST["sistemas"])) ? $_POST["sistemas"] : "" ;
            $_otras_actividades = (isset($_POST["otras_actividades"])) ? $_POST["otras_actividades"] : "" ;
            $_motivo_atencion = (isset($_POST["motivo_atencion"])) ? $_POST["motivo_atencion"] : "" ;
            $_id_bitacora_actividades_empleados_sistemas = (isset($_POST["id_bitacora_actividades_empleados_sistemas"])) ? $_POST["id_bitacora_actividades_empleados_sistemas"] : 0 ;
            
            $funcion = "ins_core_bitacora_actividades_empleados_sistemas";
            $respuesta = 0 ;
            $mensaje = "";
            
             
            if($_id_bitacora_actividades_empleados_sistemas == 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_credito',
                               '$_prestaciones',
                               '$_recaudaciones',
                               '$_tesoreria',
                               '$_contabilidad',
                               '$_auditoria',
                               '$_sistemas',
                               '$_otras_actividades',
                               '$_motivo_atencion',
                               '$_id_bitacora_actividades_empleados_sistemas'";
                                $bitacora_sistemas->setFuncion($funcion);
                                $bitacora_sistemas->setParametros($parametros);
                
                //echo "SELECT ". $funcion." ( ".$parametros." )"; die();
                                $resultado = $bitacora_sistemas->llamafuncionPG();
                
                if(is_int((int)$resultado[0])){
                    $respuesta = $resultado[0];
                    $mensaje = "Ingresado Correctamente";
                }
                
              
                
            }elseif ($_id_bitacora_actividades_empleados_sistemas > 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                                $_id_participes,
                               '$_credito',
                               '$_prestaciones',
                               '$_recaudaciones',
                               '$_tesoreria',
                               '$_contabilidad',
                               '$_auditoria',
                               '$_sistemas',
                               '$_otras_actividades',
                               '$_motivo_atencion',
                               '$_id_bitacora_actividades_empleados_sistemas'";
                                $bitacora_sistemas->setFuncion($funcion);
                                $bitacora_sistemas->setParametros($parametros);
                                $resultado = $bitacora_sistemas->llamafuncionPG();
                
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
    
    
    public function editBitacoraSistemas(){
        
        session_start();
        $bitacora_sistemas = new CreditosModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosSistemas";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_sistemas->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_bitacora_actividades_empleados_sistemas"])){
                
                $id_bitacora_actividades_empleados_sistemas = (int)$_POST["id_bitacora_actividades_empleados_sistemas"];
                
                $query = "SELECT a.*, b.cedula_participes, b.nombres_participes FROM core_bitacora_actividades_empleados_sistemas a
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )b  on  b.id_participes=a.id_participes
                WHERE a.id_bitacora_actividades_empleados_sistemas = $id_bitacora_actividades_empleados_sistemas";
                
                $resultado  = $bitacora_sistemas->enviaquery($query);
                
                echo json_encode(array('data'=>$resultado));
                
            }
            
            
        }
        else
        {
            echo "No Tiene Permisos Editar";
        }
        
    }
    
    
    public function delBitacoraSistemas(){
        
        session_start();
        $bitacora_sistemas = new BitacoraSistemasModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_sistemas->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            if(isset($_POST["id_bitacora_actividades_empleados_sistemas"])){
                
                $id_bitacora_actividades_empleados_sistemas = (int)$_POST["id_bitacora_actividades_empleados_sistemas"];
                
                $resultado  = $bitacora_sistemas->eliminarBy("id_bitacora_actividades_empleados_sistemas ",$id_bitacora_actividades_empleados_sistemas);
                
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
    
    
    public function consultaBitacoraSistemas(){
        
        session_start();
         
        $bitacora_sistemas = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $columnas ="a.id_bitacora_actividades_empleados_sistemas,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.credito,
                    a.prestaciones,
                    a.recaudaciones,
                    a.tesoreria,
                    a.contabilidad,
                    a.auditoria,
                    a.sistemas,
                    a.otras_actividades,
                    a.motivo_atencion";
        $tablas  = "core_bitacora_actividades_empleados_sistemas a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_sistemas";

        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        $fecha_registro_desde =  (isset($_REQUEST['fecha_registro_desde'])&& $_REQUEST['fecha_registro_desde'] !=NULL)?$_REQUEST['fecha_registro_desde']:'';
        $fecha_registro_hasta =  (isset($_REQUEST['fecha_registro_hasta'])&& $_REQUEST['fecha_registro_hasta'] !=NULL)?$_REQUEST['fecha_registro_hasta']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                $where.=" AND (c.cedula_participes ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%' OR a.motivo_atencion ILIKE '".$search."%')";
            }
            
            if(!empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' ";
            }
            if(!empty($search) && !empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' AND (c.cedula_participes ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%' OR a.motivo_atencion ILIKE '".$search."%')";
            }
            
            
            
            
            $where_to=$where;
            $html="";
            $resultSet=$bitacora_sistemas->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$bitacora_sistemas->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
                $html.='<th style="text-align: center;  font-size: 10px;">Crédito</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Prestaciones</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Recaudaciones</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Tesorería</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Contabilidad</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Auditoría</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Sistemas</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Cédula</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Participes</th>';
                $html.='<th style="text-align: center;  font-size: 10px;">Motivo de Atención</th>';
                
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
                    $html.='<tr>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$i.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->fecha_registro.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->desde.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->hasta.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->credito.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->prestaciones.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->recaudaciones.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->tesoreria.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->contabilidad.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->auditoria.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->sistemas.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                    $html.='<td style="text-align: center; font-size: 10px;">'.$res->motivo_atencion.'</td>';
                    
                    
                    
                    /*comentario up */
                    
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editBitacoraSistemas('.$res->id_bitacora_actividades_empleados_sistemas.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    $html.='<td style="font-size: 18px;">
                            <a onclick="delBitacoraSistemas('.$res->id_bitacora_actividades_empleados_sistemas.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaBitacoraSistemas").'';
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
    
    public function ReporteBitacoraSistemas(){
        
        session_start();
        
        $bitacora_sistemas = new CreditosModel();
        
        $cedula_usuarios = $_SESSION['cedula_usuarios'];
        
        $where_to="";
        $datos_reporte = array();
        $columnas ="a.id_bitacora_actividades_empleados_sistemas,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    a.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    a.id_participes,
                    c.nombres_participes,
                    c.cedula_participes,
                    a.credito,
                    a.prestaciones,
                    a.recaudaciones,
                    a.tesoreria,
                    a.contabilidad,
                    a.auditoria,
                    a.sistemas  ,
                    a.otras_actividades,
                    a.motivo_atencion
                    d.nombre_cargo";
        $tablas  = "core_bitacora_actividades_empleados_sistemas a
                    inner join empleados b on a.id_empleados = b.id_empleados
                    left join (
                select p.id_participes, p.cedula_participes, p.apellido_participes || ' ' || p.nombre_participes as  nombres_participes
                from core_participes p where 1=1
                )c  on  c.id_participes=a.id_participes
                inner join cargos_empleados d on b.id_cargo_empleado = d.id_cargo";
        $where   = "1 = 1 and b.numero_cedula_empleados = '$cedula_usuarios'";
        $id      = "a.id_bitacora_actividades_empleados_sistemas";
        
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        $fecha_registro_desde =  (isset($_REQUEST['fecha_registro_desde'])&& $_REQUEST['fecha_registro_desde'] !=NULL)?$_REQUEST['fecha_registro_desde']:'';
        $fecha_registro_hasta =  (isset($_REQUEST['fecha_registro_hasta'])&& $_REQUEST['fecha_registro_hasta'] !=NULL)?$_REQUEST['fecha_registro_hasta']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                $where.=" AND (c.cedula_participes ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%' OR a.motivo_atencion ILIKE '".$search."%')";
            }
            if(!empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' ";
            }
            if(!empty($search) && !empty($fecha_registro_desde) &&  !empty($fecha_registro_hasta)){
                $where.=" AND date(a.fecha_registro) between '$fecha_registro_desde' and '$fecha_registro_hasta' AND (c.cedula_participes ILIKE '".$search."%' OR a.otras_actividades ILIKE '".$search."%' OR a.motivo_atencion ILIKE '".$search."%')";
            }
            
            $where_to=$where;
            $bitacora_detalle=$bitacora_sistemas->getCondiciones($columnas, $tablas, $where_to, $id );
            
            //var_dump($resultSet); die();
            
          
            
            $html='';
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1">';
            $html.='<tr class="1">';
            $html.='<th style="text-align: center;  font-size: 10px;">#</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Fecha</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Desde</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Hasta</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Cédula</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Participes</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Crédito</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Prestaciones</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Recaudaciones</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Tesorería</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Contabilidad</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Auditoría</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Sistemas</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Otras Actividades</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Cédula</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Participes</th>';
            $html.='<th style="text-align: center;  font-size: 10px;">Motivo de Atención</th>';
            $html.='</tr>';
            
            
            $i=0;
            foreach ($bitacora_detalle as $res)
            {
                
                $i++;
                $html.='<tr>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$i.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->fecha_registro.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->desde.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->hasta.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->credito.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->prestaciones.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->recaudaciones.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->tesoreria.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->contabilidad.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->auditoria.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->sistemas.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->otras_actividades.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->nombres_participes.'</td>';
                $html.='<td style="text-align: center; font-size: 10px;">'.$res->motivo_atencion.'</td>';
                $html.='</tr>';
            }
            
            $html.='</table>';
            $datos_reporte['DETALLE']= $html;
            $datos_reporte['nombres_empleados']=$bitacora_detalle[0]->nombres_empleados;
            $datos_reporte['nombre_cargo']=$bitacora_detalle[0]->nombre_cargo;
            
            $datos_reporte['fecha_reg']=$bitacora_detalle[0]->fecha_registro;
            $datos_reporte['desde_reg']=$bitacora_detalle[0]->desde;
            $datos_reporte['hasta_reg']=$bitacora_detalle[0]->hasta;
            
            
          
            
            
            $this->verReporte("ReporteBitacoraSistemas", array('datos_reporte'=>$datos_reporte ));
        
            
        }
        
        
    }
    
}
?>