<?php
class RevisionCreditosController extends ControladorBase{
    public function index(){
        session_start();
      
        $this->view_Credito("RevisionCreditos",array(
        ));
    }
    
    public function getCreditosRegistrados()
    {
        session_start();
        $id_rol=$_SESSION["id_rol"];
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $creditos=new PlanCuentasModel();
        if ($id_rol==58)
        {
        $columnas="core_creditos.numero_creditos,core_participes.cedula_participes, core_participes.apellido_participes, core_participes.nombre_participes,
                    core_creditos.monto_otorgado_creditos, core_creditos.plazo_creditos,
                    core_tipo_creditos.nombre_tipo_creditos, oficina.nombre_oficina";
        $tablas="core_creditos INNER JOIN core_estado_creditos
                ON core_creditos.id_estado_creditos=core_estado_creditos.id_estado_creditos
                INNER JOIN core_participes
                ON core_participes.id_participes = core_creditos.id_participes
                INNER JOIN core_tipo_creditos
                ON core_tipo_creditos.id_tipo_creditos=core_creditos.id_tipo_creditos
                INNER JOIN usuarios
                ON core_creditos.receptor_solicitud_creditos = usuarios.usuario_usuarios
                INNER JOIN oficina
                ON oficina.id_oficina=usuarios.id_oficina";
        $where="core_estado_creditos.id_estado_creditos=1 AND core_creditos.incluido_reporte_creditos IS NULL";
        $id="core_creditos.numero_creditos";
        $html="";
        $resultSet=$creditos->getCantidad("*", $tablas, $where);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$creditos->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
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
            $html.= "<table id='tabla_creditos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 15px;">Crédito</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Cédula</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Apellidos</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Nombres</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Monto</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Plazo</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Tipo</th>';
            $html.='<th style="text-align: left;  font-size: 15px;">Ciudad</th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            $html.='<th style="text-align: left;  font-size: 15px;"></th>';
            
            /*if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
            {
                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
            }*/
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            foreach ($resultSet as $res)
            {
                
                    $columnas="id_solicitud_prestamo";
                    $tabla="solicitud_prestamo";
                    $where="identificador_consecutivos='".$res->numero_creditos."'";
                $id_solicitud=$db->getCondiciones($columnas, $tabla, $where);
                $id_solicitud=$id_solicitud[0]->id_solicitud_prestamo;
           
               
                $html.='<tr>';
                $html.='<td style="font-size: 14px;">'.$res->numero_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->cedula_participes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->apellido_participes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_participes.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->monto_otorgado_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->plazo_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_tipo_creditos.'</td>';
                $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                $html.='<td style="font-size: 14px;"><span class="pull-right"><a href="index.php?controller=SolicitudPrestamo&action=print&id_solicitud_prestamo='.$id_solicitud.'" target="_blank" class="btn btn-warning" title="Imprimir"><i class="glyphicon glyphicon-file"></i></a></span></td>';
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" onclick="AgregarReporte('.$res->numero_creditos.')"><i class="glyphicon glyphicon-plus"></i></button></span></td>';
                $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->numero_creditos.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                }
                /*if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                {
                    if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                    
                    else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                    
                    else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                    else if ($id_rol==$id_rh && $res->nombre_estado=="APROBADO GERENCIA" && $res->nombre_causa=="Enfermedad")
                    {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="SinCertificado('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                    }
                }*/
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,"load_creditos").'';
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
        
    }
    
    public function getReportesRegistrados()
    {
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $reportes=new PlanCuentasModel();
        if ($id_rol==58)
        {
            $columnas="id_creditos_trabajados_cabeza, anio_creditos_trabajados_cabeza, mes_creditos_trabajados_cabeza,
                         dia_creditos_trabajados_cabeza, oficina.nombre_oficina, estado.nombre_estado";
            $tablas="core_creditos_trabajados_cabeza INNER JOIN oficina
            		ON oficina.id_oficina=core_creditos_trabajados_cabeza.id_oficina
            		INNER JOIN estado
            		ON estado.id_estado=core_creditos_trabajados_cabeza.id_estado_creditos_trabajados_cabeza";
            $where="1=1";
            $id="id_creditos_trabajados_cabeza";
            
            $html="";
            $resultSet=$reportes->getCantidad("*", $tablas, $where);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$reportes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $count_query   = $cantidadResult;
            $total_pages = ceil($cantidadResult/$per_page);
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:300px; overflow-y:scroll;">';
                $html.= "<table id='tabla_reportes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';               
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                foreach ($resultSet as $res)
                {
                    
                                      
                    $fecha=$res->dia_creditos_trabajados_cabeza."/".$res->mes_creditos_trabajados_cabeza."/".$res->anio_creditos_trabajados_cabeza;
                    $html.='<tr>';
                    $html.='<td style="font-size: 14px;">'.$fecha.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                }
                /*if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                 {
                 if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                 {
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                 }
                 
                 else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                 {
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                 }
                 
                 else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                 {
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                 }
                 else if ($id_rol==$id_rh && $res->nombre_estado=="APROBADO GERENCIA" && $res->nombre_causa=="Enfermedad")
                 {
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                 $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="SinCertificado('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                 }
                 }*/
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,"load_reportes").'';
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
        
    }
    
    public function GetReportes()
    {
        session_start();
        
        $reportes = new PlanCuentasModel();
        $tablas="public.core_creditos_trabajados_cabeza";
        $where="1=1";
        $id="core_creditos_trabajados_cabeza.id_creditos_trabajados_cabeza";
        $resultSet=$reportes->getCondiciones("*", $tablas, $where, $id);
        
        $html='<label for="tipo_credito" class="control-label">Seleccionar Reporte:</label>
       <select class="reportes" name="reportes_creditos" id="reportes_creditos"  class="form-control">';
        $html.='<option value="0">Reporte con fecha actual</option>';
        if (!(empty($resultSet)))
        {
            foreach($resultSet as $res)
            {
                $fecha=$res->dia_creditos_trabajados_cabeza."/".$res->mes_creditos_trabajados_cabeza."/".$res->anio_creditos_trabajados_cabeza;
                $html.='<option value="'.$res->id_creditos_trabajados_cabeza.'">'.$fecha.'</option>';
            }
        }
        $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
        
        echo $html;
    }
    
    public function GenerarReportes()
    {
        session_start();
        
        $reportes = new PlanCuentasModel();
        $id_reporte=$_POST['id_reporte'];
        $id_credito=$_POST['id_credito'];
        $id_usuarios=$_SESSION['id_usuarios'];
        $usuario_usuarios=$_SESSION['usuario_usuarios'];
        $columnas="id_oficina";
        $tablas="usuarios";
        $where="id_usuarios=".$id_usuarios;
        $id="id_oficina";
        $id_oficina=$reportes->getCondiciones($columnas, $tablas, $where, $id);
        $id_oficina=$id_oficina[0]->id_oficina;        
        if ($id_reporte==0)
        {
          $dia= date('d');
          $mes= date('m');
          $year= date('Y');
          $columnas="id_creditos_trabajados_cabeza";
          $tablas="core_creditos_trabajados_cabeza";
          $where="anio_creditos_trabajados_cabeza = ".$year."
                	AND mes_creditos_trabajados_cabeza = ".$mes."
                	AND dia_creditos_trabajados_cabeza = ".$dia;
          $id="id_creditos_trabajados_cabeza";
          $resultRpts=$reportes->getCondiciones($columnas, $tablas, $where, $id);
          if (empty($resultRpts))
          {
              
              $funcion= "ins_core_creditos_trabajados_cabeza";
              $parametros = "'$id_usuarios',
                     '$usuario_usuarios',
                     '$id_oficina',
                     '$mes',
                     '$year',
                     '$dia',
                     91";
             
              $reportes->setFuncion($funcion);
              $reportes->setParametros($parametros);
              $resultado=$reportes->Insert();
          }
          else
          {
              $id_reporte=$resultRpts[0]->id_creditos_trabajados_cabeza;
             $inserta_credito=$this->AddCreditosToReport($id_reporte, $id_credito);
             $inserta_credito=trim($inserta_credito);
             $where = "id_creditos=".$id_credito;
             $tabla = "core_creditos";
             $colval = "incluido_reporte_creditos=1";
             if ($inserta_credito=="") $reportes->UpdateBy($colval, $tabla, $where);
             
          }
        }
        
    }
    
    public function AddCreditosToReport($id_reporte, $id_credito)
    {
        $reportes = new PlanCuentasModel();
        $funcion= "ins_core_creditos_trabajados_detalle";
        $parametros = "'$id_reporte',
                     '$id_credito',
                      91";
        
        $reportes->setFuncion($funcion);
        $reportes->setParametros($parametros);
        $resultado=$reportes->Insert();
        
        return ob_get_clean();
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
        $cedula =$_SESSION["cedula_usuarios"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        $id_jefi=0;
        $id_rh=0;
        
        $permisos_empleados = new PermisosEmpleadosModel();
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
        $wheredep= "empleados.numero_cedula_empleados =".$cedula;
        $iddep = "departamentos.id_departamento";
        $resultdep = $departamento->getCondiciones($columnadep, $tablasdep, $wheredep, $iddep);
        
        $tablar = "usuarios INNER JOIN empleados
                        ON usuarios.cedula_usuarios = CAST (empleados.numero_cedula_empleados AS TEXT)
                        INNER JOIN departamentos 
                        ON departamentos.id_departamento = empleados.id_departamento
                        INNER JOIN cargos_empleados
                        ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $wherer = "departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."' AND cargos_empleados.nombre_cargo ILIKE 'Jefe%'";
        $idr = "usuarios.id_rol";
        $resultr = $rol->getCondiciones("*", $tablar, $wherer, $idr);
        $id_jefi = $resultr[0]->id_rol;
        $id_dpto_jefe = $resultr[0]->id_departamento;
        }
        $where_to="";
        $columnas = " empleados.nombres_empleados,
                      cargos_empleados.nombre_cargo,
                      departamentos.nombre_departamento,
                        departamentos.id_departamento,
                        permisos_empleados.id_permisos_empleados,
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
        {   if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = "permisos_empleados.id_estado=".$id_estado." AND empleados.numero_cedula_empleados=".$cedula;
            }
            else {
                $where    = "permisos_empleados.id_estado=".$id_estado;
            }
            
        }
        else 
        {
            if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = " empleados.numero_cedula_empleados=".$cedula;
            }
            else {
                $where    = "1=1";
            }
        }
           
        
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
                    $html.='<td style="font-size: 18px;"><a href="index.php?controller=PermisosEmpleados&action=HojaPermiso&id_permiso='.$res->id_permisos_empleados.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_solicitud.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_desde.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->hora_hasta.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->descripcion_causa.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                    if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                    {
                        if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                         else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                        else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';                            
                        }
                        else if ($id_rol==$id_rh && $res->nombre_estado=="APROBADO GERENCIA" && $res->nombre_causa=="Enfermedad")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_permisos_empleados.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="SinCertificado('.$res->id_permisos_empleados.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
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
    
    public function paginate_creditos($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'VISTO BUENO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function AprobarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        
        echo 1;
    }
    public function GerenciaSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'APROBADO GERENCIA'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
     
     echo 1;
    }
    
    public function CertificadoMedico()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'CERTIFICADO PRESENTADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function SinCertificadoMedico()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'SIN CERTIFICADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function NegarSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        $permisos = new PermisosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='PERMISO_EMPLEADO' AND estado.nombre_estado = 'NEGADO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $where = "id_permisos_empleados=".$id_solicitud;
        $tabla = "permisos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $permisos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function HojaPermiso()
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
                      permisos_empleados.fecha_solicitud,
                        permisos_empleados.hora_desde,
                        permisos_empleados.hora_hasta,
                        causas_permisos.nombre_causa,
                        permisos_empleados.descripcion_causa";
        
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
        $where= "permisos_empleados.id_permisos_empleados=".$id_permiso;
        $id="permisos_empleados.id_permisos_empleados";
        
        $rsdatos = $permisos->getCondiciones($columnas, $tablas, $where, $id);
        echo $rsdatos;
        $datos_reporte['NOMBREEMPLEADO']=$rsdatos[0]->nombres_empleados;
        $datos_reporte['CARGOEMPLEADO']=$rsdatos[0]->nombre_cargo;
        $datos_reporte['DPTOEMPLEADO']=$rsdatos[0]->nombre_departamento;
        $fechaelem = explode("-", $rsdatos[0]->fecha_solicitud);
        $ind = intval($fechaelem[1])-1;
        $datos_reporte['FECHASOLICITUD']=$fechaelem[2]." de ".$meses[$ind]." de ".$fechaelem[0];
        $datos_reporte['HORADESDE']=$rsdatos[0]->hora_desde;
        $datos_reporte['HORAHASTA']=$rsdatos[0]->hora_hasta;
        $datos_reporte['CAUSAPERMISO']=$rsdatos[0]->nombre_causa;
        if (!(empty($rsdatos[0]->descripcion_causa)))
        {
            $datos_reporte['DESCRIPCION']="Motivo: ".$rsdatos[0]->descripcion_causa;
        }
        else $datos_reporte['DESCRIPCION']="";
        
        $this->verReporte("HojaPermiso", array('datos_reporte'=>$datos_reporte,'datos_empresa'=>$datos_empresa));
        
        
            
    }
}

?>