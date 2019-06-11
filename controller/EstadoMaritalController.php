<?php

class EstadoMaritalController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        $estado_marital=new EstadoMaritalModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "EstadoMarital";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado_marital->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso a Estados Marital"
                
            ));
            exit();
        }
        
        $rsEstados = $estado_marital->getBy(" 1 = 1 ");
        
        
        $this->view_Core("EstadoMarital",array(
            "resultSet"=>$rsEstados
            
        ));
        
        
    }
    
    
    
    public function InsertaEstadoMarital(){
        
        session_start();
        
        $estado_marital=new EstadoMaritalModel();
        
        $nombre_controladores = "EstadoMarital";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado_marital->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
            $_nombre_estado_marital = (isset($_POST["nombre_estado_marital"])) ? $_POST["nombre_estado_marital"] : "";
            $_id_estado_marital = (isset($_POST["id_estado_marital"])) ? $_POST["id_estado_marital"] : 0 ;
            
            /*si es insertado enviar en cero el id_banco a la funcion*/
            $funcion = "ins_core_estado_marital";
            $respuesta = 0 ;
            $mensaje = "";
            
            if($_id_estado_marital == 0){
                
                $parametros = " '$_id_estado', '$_nombre_estado_marital'";
                $estado_marital->setFuncion($funcion);
                $estado_marital->setParametros($parametros);
                $resultado = $estado_marital->llamafuncion();
                
                if(!empty($resultado) && count($resultado) > 0 ){
                    
                    foreach ( $resultado[0] as $k => $v){
                        
                        $respuesta = $v;
                    }
                    
                    $mensaje = "Estado Ingresado Correctamente";
                    
                }
            }elseif ($_id_estado_marital > 0){
                
                $parametros = " '$_id_estado', '$_nombre_estado_marital'";
                $estado_marital->setFuncion($funcion);
                $estado_marital->setParametros($parametros);
                $resultado = $estado_marital->llamafuncion();
                
                if(!empty($resultado) && count($resultado) > 0 ){
                    
                    foreach ( $resultado[0] as $k => $v){
                        
                        $respuesta = $v;
                    }
                    
                    $mensaje = "Estado Actualizado Correctamente";
                    
                }
            }
            
            
            if($respuesta > 0 ){
                
                echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
                exit();
            }
            
            echo "Error al Ingresar Estado";
            exit();
            
        }
        else
        {
            $this->view_Inventario("Error",array(
                "resultado"=>"No tiene Permisos de Insertar Estados"
                
            ));
            
            
        }
        
    }
    
    public function borrarId()
    {
        
        session_start();
        $estado_marital=new EstadoMaritalModel();
        $nombre_controladores = "EstadoMarital";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado_marital->getPermisosBorrar("  controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer)){
            
            if(isset($_POST["id_estado_marital"])){
                
                $id_estado_marital = (int)$_POST["id_estado_marital"];
                
                $resultado  = $estado_marital->eliminarBy(" id_estado_marital ",$id_estado_marital);
                
                if( $resultado > 0 ){
                    
                    echo json_encode(array('data'=>$resultado));
                    
                }else{
                    
                    echo $resultado;
                }
                
                
                
            }
            
            
        }else{
            
            echo "Usuario no tiene permisos-Eliminar";
        }
        
        
        
    }
    
    
    public function editEstados(){
        
        session_start();
        $estado_marital=new EstadoMaritalModel();
        $nombre_controladores = "EstadoMarital";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $estado_marital->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_estado_marital"])){
                
                $id_estado_marital = (int)$_POST["id_estado_marital"];
                
                $query = "SELECT * FROM core_estado_marital WHERE id_estado_marital = $id_estado_marital";
                
                $resultado  = $estado_marital->enviaquery($query);
                
                echo json_encode(array('data'=>$resultado));
                
            }
            
            
        }
        else
        {
            echo "Usuario no tiene permisos-Editar";
        }
        
    }
    
    
       
    
    public function consulta_estados_activos(){
        
        
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $usuarios = new UsuariosModel();
        $estado = null; $estado = new EstadoModel();
        $where_to="";
        $columnas = "
                          core_estado_marital.id_estado_marital, 
                          estado.id_estado, 
                          estado.nombre_estado, 
                          estado.tabla_estado, 
                          core_estado_marital.nombre_estado_marital, 
                          core_estado_marital.creado, 
                          core_estado_marital.modificado";
        $tablas   = "     public.core_estado_marital, 
                          public.estado";
        $where    = "     estado.id_estado = core_estado_marital.id_estado AND
                          public.estado.tabla_estado = 'core_estado_marital' 
                          AND public.estado.nombre_estado='ACTIVO' ";
        $id       = "core_estado_marital.id_estado_marital";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            //estado_usuario
            $whereestado = "tabla_estado='core_estado_marital'";
            $resultEstado = $estado->getCondiciones('nombre_estado' ,'public.estado' , $whereestado , 'tabla_estado');
            
            
            
            
            if(!empty($search)){
                
                
                $where1=" AND (core_estado_marital.nombre_estado_marital LIKE '".$search."%' )";
                
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
                $html.= "<table id='tabla_estados_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre Estado</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
                
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
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado_marital.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
                    
                    
                    
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;">
                            <a onclick="editEstados('.$res->id_estado_marital.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                        $html.='<td style="font-size: 18px;">
                            <a onclick="borrarId('.$res->id_estado_marital.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                        
                    }
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_estados_activos("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    
        
        
    }
    
    public function cargaEstadoEstMarital(){
        
        $estado_marital = null;
        $estado_marital=new EstadoMaritalModel();
        
        $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'core_estado_marital' ORDER BY nombre_estado";
        
        $resulset = $estado_marital->enviaquery($query);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    
    
    public function consulta_estados_inactivos(){
        
        
        
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        
        $usuarios = new UsuariosModel();
        $estado = null; $estado = new EstadoModel();
        $where_to="";
        $columnas = "
                          core_estado_marital.id_estado_marital,
                          estado.id_estado,
                          estado.nombre_estado,
                          estado.tabla_estado,
                          core_estado_marital.nombre_estado_marital,
                          core_estado_marital.creado,
                          core_estado_marital.modificado";
        $tablas   = "     public.core_estado_marital,
                          public.estado";
        $where    = "     estado.id_estado = core_estado_marital.id_estado AND
                          public.estado.tabla_estado = 'core_estado_marital'
                          AND public.estado.nombre_estado='INACTIVO' ";
        $id       = "core_estado_marital.id_estado_marital";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            //estado_usuario
            $whereestado = "tabla_estado='core_estado_marital'";
            $resultEstado = $estado->getCondiciones('nombre_estado' ,'public.estado' , $whereestado , 'tabla_estado');
            
            
            
            
            if(!empty($search)){
                
                
                $where1=" AND (core_estado_marital.nombre_estado_marital LIKE '".$search."%' )";
                
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
                $html.= "<table id='tabla_estados_activos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre Estado</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
                
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
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado_marital.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
                    
                    
                    
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;">
                            <a onclick="editEstados('.$res->id_estado_marital.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                        $html.='<td style="font-size: 18px;">
                            <a onclick="borrarId('.$res->id_estado_marital.')"   href="#" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a></td>';
                        
                    }
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_estados_inactivos("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
        
        
        
    }
    
    
    
    
    public function paginate_estados_activos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_activos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_activos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_estados_activos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_estados_activos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_estados_activos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_estados_activos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_activos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    
    
    
    
    public function paginate_estados_inactivos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_inactivos(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_inactivos(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_estados_inactivos(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_estados_inactivos(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_estados_inactivos(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_estados_inactivos($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_estados_inactivos(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    /**
     * mod: compras
     * title: carga_bodegas
     * ajax: si
     */
    
    
    

  
    
    
    
    
    
    
    
}
?>