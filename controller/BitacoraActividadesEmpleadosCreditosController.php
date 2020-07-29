<?php

class BitacoraActividadesEmpleadosCreditosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
        
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
        
        $rsBitacoraCreditos = $bitacora_creditos->getBy(" 1 = 1 ");
        
        
        $this->view_Core("BitacoraActividadesEmpleadosCreditos",array(
            "resultSet"=>$rsBitacoraCreditos
            
        ));
        
        
    }
    
    
    public function InsertaBitacoraCreditos(){
        
        session_start();
        
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
        
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_creditos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            $_fecha_registro = (isset($_POST["fecha_registro"])) ? $_POST["fecha_registro"] : "";
            $_desde = (isset($_POST["desde"])) ? $_POST["desde"] : "";
            $_hasta = (isset($_POST["hasta"])) ? $_POST["hasta"] : "";
            $_id_empleados = (isset($_POST["id_empleados"])) ? $_POST["id_empleados"] : 0;
            $_id_participes = (isset($_POST["id_participes"])) ? $_POST["id_participes"] : 0;
            $_creditos = (isset($_POST["creditos"])) ? $_POST["creditos"] : 0;
            $_cesantia = (isset($_POST["cesantia"])) ? $_POST["cesantia"] : 0;
            $_desafiliacion = (isset($_POST["desafiliacion"])) ? $_POST["desafiliacion"] : 0;
            $_superavit = (isset($_POST["superavit"])) ? $_POST["superavit"] : 0;
            $_diferimiento = (isset($_POST["diferimiento"])) ? $_POST["diferimiento"] : 0;
            $_refinanciamiento_reestructuracion = (isset($_POST["refinanciamiento_reestructuracion"])) ? $_POST["refinanciamiento_reestructuracion"] : 0;
            $_elaboracion_memorando = (isset($_POST["elaboracion_memorando"])) ? $_POST["elaboracion_memorando"] : "";
            $_otras_actividades = (isset($_POST["otras_actividades"])) ? $_POST["otras_actividades"] : "";
            $_atencion_creditos = (isset($_POST["atencion_creditos"])) ? $_POST["atencion_creditos"] : 0;
            $_entrega_documentos_creditos = (isset($_POST["entrega_documentos_creditos"])) ? $_POST["entrega_documentos_creditos"] : 0;
            $_atencion_cesantias = (isset($_POST["atencion_cesantias"])) ? $_POST["atencion_cesantias"] : 0;
            $_entrega_documentos_cesantias = (isset($_POST["entrega_documentos_cesantias"])) ? $_POST["entrega_documentos_cesantias"] : 0;
            $_atencion_desafiliaciones = (isset($_POST["atencion_desafiliaciones"])) ? $_POST["atencion_desafiliaciones"] : 0;
            $_entrega_documentos_desafiliaciones = (isset($_POST["entrega_documentos_desafiliaciones"])) ? $_POST["entrega_documentos_desafiliaciones"] : 0;
            $_atencion_superavit = (isset($_POST["atencion_superavit"])) ? $_POST["atencion_superavit"] : 0;
            $_entrega_documentos_superavit = (isset($_POST["entrega_documentos_superavit"])) ? $_POST["entrega_documentos_superavit"] : 0;
            $_atencion_refinanciamiento_reestructuracion = (isset($_POST["atencion_refinanciamiento_reestructuracion"])) ? $_POST["atencion_refinanciamiento_reestructuracion"] : 0;
            $_entrega_documentos_refinanciamiento_reestructuracion = (isset($_POST["entrega_documentos_refinanciamiento_reestructuracion"])) ? $_POST["entrega_documentos_refinanciamiento_reestructuracion"] : 0;
            $_atencion_diferimiento = (isset($_POST["atencion_diferimiento"])) ? $_POST["atencion_diferimiento"] : 0;
            $_claves = (isset($_POST["claves"])) ? $_POST["claves"] : 0;
            $_consultas_varias = (isset($_POST["consultas_varias"])) ? $_POST["consultas_varias"] : 0;
            $_id_bitacora_actividades_empleados_creditos = (isset($_POST["id_bitacora_actividades_empleados_creditos"])) ? $_POST["id_bitacora_actividades_empleados_creditos"] : 0;
            
          
            
            
            $funcion = "ins_core_bitacora_actividades_empleados_creditos";
            $respuesta = 0 ;
            $mensaje = "";
            
            if($_id_bitacora_actividades_empleados_creditos == 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                               '$_id_participes',
                                $_creditos',
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
                    $mensaje = "Ingresado Correctamente";
                }
                
                
            }elseif ($_id_bitacora_actividades_empleados_creditos > 0){
                
                $parametros = "'$_fecha_registro',
                               '$_desde',
                               '$_hasta',
                               '$_id_empleados',
                               '$_id_participes',
                                $_creditos',
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
        else
        {
            echo 'Revise Permisos ';
        }
        
    }
    
    
    public function editBitacoraCreditos(){
        
        session_start();
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
        $nombre_controladores = "BitacoraActividadesEmpleadosCreditos";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $bitacora_creditos->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_bitacora_actividades_empleados_creditos"])){
                
                $id_bitacora_actividades_empleados_creditos = (int)$_POST["id_bitacora_actividades_empleados_creditos"];
                
                $query = "SELECT * FROM core_bitacora_actividades_empleados_creditos WHERE id_bitacora_actividades_empleados_creditos = $id_bitacora_actividades_empleados_creditos";
                
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
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
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
        $id_rol=$_SESSION["id_rol"];
        
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
        
        $where_to="";
        $columnas ="a.id_bitacora_actividades_empleados_creditos,
                    a.fecha_registro,
                    a.desde,
                    a.hasta,
                    b.id_empleados,
                    b.nombres_empleados,
                    b.numero_cedula_empleados,
                    c.id_participes,
                    c.apellido_participes,
                    c.nombre_participes,
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
                    inner join core_participes c on a.id_participes = c.id_participes";
        $where   = "1 = 1";
        $id      = "a.id_bitacora_actividades_empleados_creditose";
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND exam_nombre ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$bitacora_creditos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$bitacora_creditos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.='<th style="text-align: left;  font-size: 15px;">#</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Empleado</th>';
                
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
                    $html.='<td style="font-size: 14px;">'.$i.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_registro.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    
                    
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
    
    public function cargaBitacoraParticipes(){
        
        $bitacora_creditos = null;
        $bitacora_creditos = new BitacoraActividadesEmpleadosCreditosModel();
        
        $query = "SELECT id_participes, apellido_participes, nombre_participes FROM core_participes WHERE 1=1 ORDER BY apellido_participes";
        
        $resulset = $bitacora_creditos->enviaquery($query);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
}
?>