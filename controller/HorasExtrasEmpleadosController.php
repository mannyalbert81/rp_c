 <?php
class HorasExtrasEmpleadosController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $tablaes ="public.estado";
        $wherees = "estado.tabla_estado = 'PERMISO_EMPLEADO'";
        $ides = "estado.id_estado";
        $resultes = $estado->getCondiciones("*", $tablaes, $wherees, $ides);
        
        
        
        
      
        $this->view_Administracion("HorasExtrasEmpleados",array(
            "resultes" => $resultes
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
        $funcion= "ins_solicitud_hora_extra_empleado";
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados ='".$cedula."'";
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $id_empleado = $result[0]->id_empleados;
        $fecha_solicitud= $_POST['fecha_solicitud'];
        $hora_inicio_solicitud= $_POST['hora_inicio_solicitud'];
        $hora_fin_solicitud=$_POST['hora_fin_solicitud'];
        
        
        $parametros = "'$id_empleado',
                     '$fecha_solicitud',
                     '$hora_inicio_solicitud',
                     '$hora_fin_solicitud'";
       
        
        $horas_extras->setFuncion($funcion);
        $horas_extras->setParametros($parametros);
        $resultado=$horas_extras->Insert();
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
                       
        
        $where = "empleados.numero_cedula_empleados = '$cedula_usuario'";
        
        $resultSet=$horarios->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
                
        echo json_encode($resultSet);
    }
    
    public function consulta_solicitudes(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $cedula =$_SESSION["cedula_usuarios"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        $id_jefi=0;
        $id_rh=0;
        
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $rol = new RolesModel();
        $departamento = new DepartamentoModel();
        
        $tablar = "public.rol";
        $wherer = "rol.nombre_rol='Gerente'";
        $idr = "rol.id_rol";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_gerente = $resultr[0]->id_rol;
        if ($id_rol != $id_gerente)
        {
        $wherer = "rol.nombre_rol ILIKE '%Jefe de RR.HH'";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_rh = $resultr[0]->id_rol;
        
        $columnadep = "departamentos.nombre_departamento";
        $tablasdep = "public.departamentos INNER JOIN public.cargos_empleados
                      ON departamentos.id_departamento = cargos_empleados.id_departamento
                      INNER JOIN public.empleados
                      ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $wheredep= "empleados.numero_cedula_empleados ='".$cedula."'";
        $iddep = "departamentos.id_departamento";
        $resultdep = $departamento->getCondiciones($columnadep, $tablasdep, $wheredep, $iddep);
        
        $tablar = "usuarios INNER JOIN empleados
                        ON usuarios.cedula_usuarios = empleados.numero_cedula_empleados
                        INNER JOIN departamentos 
                        ON departamentos.id_departamento = empleados.id_departamento
                        INNER JOIN cargos_empleados
                        ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $wherer = "departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."' AND (cargos_empleados.nombre_cargo ILIKE 'CONTADOR%' OR cargos_empleados.nombre_cargo ILIKE 'JEFE%')";
        $idr = "usuarios.id_rol";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        if (empty($resultr))
        {
            $wherer = "cargos_empleados.nombre_cargo ILIKE 'CONTADOR%'";
            $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        }
        $id_jefi = $resultr[0]->id_rol;
        $id_dpto_jefe = $resultr[0]->id_departamento;
        }
        $where_to="";
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                        departamentos.id_departamento,
                        solicitud_horas_extras_empleados.id_solicitud,
                        solicitud_horas_extras_empleados.fecha_solicitud,
                        solicitud_horas_extras_empleados.hora_inicio_solicitud,
                        solicitud_horas_extras_empleados.hora_fin_solicitud,
                        estado.nombre_estado";
        
        $tablas = "public.solicitud_horas_extras_empleados INNER JOIN public.empleados
                   ON solicitud_horas_extras_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON solicitud_horas_extras_empleados.id_estado = estado.id_estado
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        if ($id_estado != "0")
        {   if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
        {
            $where    = "solicitud_horas_extras_empleados.id_estado=".$id_estado." AND empleados.numero_cedula_empleados='".$cedula."'";
        }
        else {
            $where    = "solicitud_horas_extras_empleados.id_estado=".$id_estado;
            
            if($id_rol == $id_jefi && $id_rh!=$id_jefi)
            {
                $where.=" AND departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."'";
            }
        }
        
        }
        else
        {
            if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = " empleados.numero_cedula_empleados='".$cedula."'";
            }
            else {
                if($id_rol == $id_jefi && $id_rh!=$id_jefi)
                {
                    $where="departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."'";
                }
                else       $where    = "1=1";
            }
        }
           
        
        $id       = "solicitud_horas_extras_empleados.fecha_solicitud";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND cargos_empleados.nombre_cargo  ILIKE '".$search."%' OR empleados.nombres_empleados ILIKE '".$search."%' OR departamentos.nombre_departamento ILIKE '".$search."%'";

                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$horas_extras->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$horas_extras->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
                $html.='<th style="text-align: left;  font-size: 15px;"></th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Cargo</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Departamento</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Hora de inicio</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Hora de salida</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
                
                if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                {
                    
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
                    $html.='<td style="font-size: 18px;"><a href="index.php?controller=HorasExtrasEmpleados&action=HojaSolicitud&id_permiso='.$res->id_solicitud.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_inicio_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_fin_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                    
                    $tablar = "usuarios INNER JOIN empleados
                        ON usuarios.cedula_usuarios = empleados.numero_cedula_empleados
                        INNER JOIN departamentos
                        ON departamentos.id_departamento = empleados.id_departamento
                        INNER JOIN cargos_empleados
                        ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
                    $wherer = "departamentos.nombre_departamento='".$res->nombre_departamento."' AND (cargos_empleados.nombre_cargo ILIKE 'CONTADOR%' OR cargos_empleados.nombre_cargo ILIKE 'JEFE%')";
                    $idr = "usuarios.id_rol";
                    $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
                    if(empty($resultr)) $tiene_jefe=false;
                    else $tiene_jefe=true;
                    
                    if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                    {
                        
                        if ($id_rol==$id_rh  && $res->nombre_estado=="EN REVISION" && !$tiene_jefe )
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        else if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_solicitud.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_solicitud.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                         else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_solicitud.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_solicitud.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                        else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_solicitud.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_solicitud.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';                            
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
    
    public function VBSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'VISTO BUENO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_solicitud=".$id_solicitud;
        $tabla = "solicitud_horas_extras_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horas_extras->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function AprobarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_solicitud=".$id_solicitud;
        $tabla = "solicitud_horas_extras_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horas_extras->UpdateBy($colval, $tabla, $where);
        
        
        echo 1;
    }
    public function GerenciaSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO GERENCIA'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_solicitud=".$id_solicitud;
        $tabla = "solicitud_horas_extras_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horas_extras->UpdateBy($colval, $tabla, $where);
     
     echo 1;
    }
    
    public function NegarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $horas_extras = new SolicitudHorasExtraEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'NEGADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_solicitud=".$id_solicitud;
        $tabla = "solicitud_horas_extras_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horas_extras->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function HojaSolicitud()
    {
        session_start();
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
        
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        $permisos = new PermisosEmpleadosModel();
        $id_permiso =  (isset($_REQUEST['id_permiso'])&& $_REQUEST['id_permiso'] !=NULL)?$_REQUEST['id_permiso']:'';
        
        $datos_reporte = array();
        
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                        solicitud_horas_extras_empleados.fecha_solicitud,
                        solicitud_horas_extras_empleados.hora_inicio_solicitud,
                        solicitud_horas_extras_empleados.hora_fin_solicitud";
        
        $tablas = "public.solicitud_horas_extras_empleados INNER JOIN public.empleados
                   ON solicitud_horas_extras_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON solicitud_horas_extras_empleados.id_estado = estado.id_estado
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $where= "solicitud_horas_extras_empleados.id_solicitud=".$id_permiso;
        $id="solicitud_horas_extras_empleados.id_solicitud";
        
        $rsdatos = $permisos->getCondiciones($columnas, $tablas, $where, $id);
        echo $rsdatos;
        $datos_reporte['NOMBREEMPLEADO']=$rsdatos[0]->nombres_empleados;
        $datos_reporte['CARGOEMPLEADO']=$rsdatos[0]->nombre_cargo;
        $datos_reporte['DPTOEMPLEADO']=$rsdatos[0]->nombre_departamento;
        $fechaelem = explode("-", $rsdatos[0]->fecha_solicitud);
        $ind = intval($fechaelem[1])-1;
        $datos_reporte['FECHASOLICITUD']=$fechaelem[2]." de ".$meses[$ind]." de ".$fechaelem[0];
        $datos_reporte['HORAINICIOSOLICITUD']=$rsdatos[0]->hora_inicio_solicitud;
        $datos_reporte['HORAFINSOLICITUD']=$rsdatos[0]->hora_fin_solicitud;
        
                
        $this->verReporte("SolicitudHoraExtra", array('datos_reporte'=>$datos_reporte,'datos_empresa'=>$datos_empresa));
        
        
            
    }
}

?>