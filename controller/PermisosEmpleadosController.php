<?php
class PermisosEmpleadosController extends ControladorBase{
    public function index(){
        session_start();
        $causas = new CausasPermisosModel();
        $estado = new EstadoModel();
        
        $tablaes ="public.estado";
        $wherees = "estado.tabla_estado = 'PERMISO_EMPLEADO'";
        $ides = "estado.id_estado";
        $resultes = $estado->getCondiciones("*", $tablaes, $wherees, $ides);
        
        $tablacau ="public.causas_permisos";
        $wherecau = "1=1";
        $idcau = "causas_permisos.id_causa";
        $resultcau = $causas->getCondiciones("*", $tablacau, $wherecau, $idcau);
      
        $this->view_Administracion("PermisosEmpleados",array(
            "resultcau"=>$resultcau,
            "resultes" => $resultes,
            "cedula_usuario" => $cedula_usuario
        ));
    }
    
    public function getUsuario()
    {
        session_start();
        $empleados = new EmpleadosModel();
        $cedula_usuario = $_SESSION["cedula_usuarios"];
        $columna = "empleados.numero_cedula_empleados,
					  empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento";
        
        $tablas = "public.empleados INNER JOIN public.departamentos
                       ON empleados.id_departamento=departamentos.id_departamento
                       INNER JOIN public.cargos_empleados
                       ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        $where = "empleados.numero_cedula_empleados = $cedula_usuario";
        
        $resultSet=$empleados->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
        
        $respuesta = new stdClass();
        
        if(!empty($resultSet)){
            
            $respuesta->numero_cedula_empleados = $resultSet[0]->numero_cedula_empleados;
            $nombres = (string)$resultSet[0]->nombres_empleados;
            $nombresep = explode(" ", $nombres);
            $respuesta->nombre_empleados = $nombresep[0].' '.$nombresep[2];
            $respuesta->cargo_empleados = $resultSet[0]->nombre_cargo;
            $respuesta->dpto_empleados = $resultSet[0]->nombre_departamento;
        }
        
        echo json_encode($respuesta);
        
    }
  
    public function AgregarSolicitud()
    {
        session_start();
        $funcion= "ins_solicitud_empleado";
        $permisos_empleados = new PermisosEmpleadosModel();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados =".$cedula;
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $id_empleado = $result[0]->id_empleados;
        $fecha_solicitud = $_POST['fecha_solicitud'];
        $hora_desde= $_POST['hora_desde'];
        $hora_hasta= $_POST['hora_hasta'];
        $id_causa= $_POST['id_causa'];
        $descripcion_causa= $_POST['descripcion_causa'];
        if (!(empty($descripcion_causa)))
        {
        $parametros = "'$id_empleado',
                     '$fecha_solicitud',
                     '$hora_desde',
                     '$hora_hasta',
                     '$id_causa',
                     '$descripcion_causa'";
        }
        else
        {
            $parametros = "'$id_empleado',
                     '$fecha_solicitud',
                     '$hora_desde',
                     '$hora_hasta',
                     '$id_causa',
                     NULL";
        }
        $permisos_empleados->setFuncion($funcion);
        $permisos_empleados->setParametros($parametros);
        $resultado=$permisos_empleados->Insert();
        echo 1;
    }
    
    public function GetHoras()
    {
        session_start();
        $horarios = new HorariosEmpleadosModel();
        $cedula_usuario = $_SESSION["cedula_usuarios"];
        $columna = "horarios_empleados.hora_entrada_empleados,
                    horarios_empleados.hora_salida_empleados";
        
        $tablas = "public.horarios_empleados INNER JOIN public.empleados
                       ON empleados.id_grupo_empleados=horarios_empleados.id_grupo_empleados";
                       
        
        $where = "empleados.numero_cedula_empleados = $cedula_usuario";
        
        $resultSet=$horarios->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
                
        echo json_encode($resultSet);
    }
    
    public function consulta_solicitudes(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        
        $permisos_empleados = new PermisosEmpleadosModel();
        
        $where_to="";
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                      permisos_empleados.fecha_solicitud,
                        permisos_empleados.hora_desde,
                        permisos_empleados.hora_hasta,
                        causas_permisos.nombre_causa,
                        permisos_empleados.descripcion_causa,
                        estado.nombre_estado";
        
        $tablas = "public.permisos_empleados INNER JOIN public.empleados
                   ON permisos_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON permisos_empleados.id_estado = estado.id_estado
                   INNER JOIN public.causas_permisos
                   ON permisos_empleados.id_causa = causas_permisos.id_causa
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        if ($id_estado != "0")
        {   
            $where    = "permisos_empleados.id_estado=".$id_estado;
        }
        else $where    ="1=1";
        
        $id       = "permisos_empleados.id_permisos_empleados";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND cargos_empleados.nombre_cargo  ILIKE '".$search."%' OR empleados.nombres_empleados ILIKE '".$search."%' OR causas_permisos.nombre_causa ILIKE'".$search."%' OR departamentos.nombre_departamento ILIKE '".$search."%'";

                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$permisos_empleados->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$permisos_empleados->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $count_query   = $cantidadResult;
            $total_pages = ceil($cantidadResult/$per_page);
            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:570px; overflow-y:scroll;">';
                $html.= "<table id='tabla_solicitudes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cargo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Departamento</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Desde</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Hasta</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Causa</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Descripción</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
                
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
                    $html.='<td style="font-size: 14px;">'.$i.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_desde.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_hasta.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->descripcion_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick=""><i class="glyphicon glyphicon-edit"></i></button></span></td>';
                        if($res->nombre_estado=="ACTIVO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick=""><i class="glyphicon glyphicon-trash"></i></button></span></td>';
                        }
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_solicitudes("index.php", $page, $total_pages, $adjacents,"load_solicitudes").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay solicitudes registradas...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    public function paginate_solicitudes($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
    
    
    
    public function EliminarValor()
    {
        session_start();
        $empleados = new EmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='EMPLEADOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $numero_cedula = $_POST['numero_cedula'];
        $where = "numero_cedula_empleados=".$numero_cedula;
        $tabla = "empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $empleados->UpdateBy($colval, $tabla, $where);
    }
    
}

?>