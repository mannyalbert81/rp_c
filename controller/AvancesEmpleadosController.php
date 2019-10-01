<?php
class AvancesEmpleadosController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $tablaes ="public.estado";
        $wherees = "estado.tabla_estado = 'PERMISO_EMPLEADO'";
        $ides = "estado.id_estado";
        $resultes = $estado->getCondiciones("*", $tablaes, $wherees, $ides);
        
        
        
        
      
        $this->view_Administracion("AvancesEmpleados",array(
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
        
        $where = "empleados.numero_cedula_empleados = '".$cedula_usuario."'";
        
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
    
    public function ValidarCantidad()
    {
        session_start();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados =".$cedula;
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $anio = date("Y");
        $id_empleado = $result[0]->id_empleados;
        $avance = new AnticipoSueldoEmpleadosModel();
        $columnas="COUNT(id_anticipo) as total";
        $where="fecha_anticipo BETWEEN '".$anio."-1-01' AND '".$anio."-12-31' AND id_empleado=".$id_empleado;
        $tablas="anticipo_sueldo_empleados";
        $limit="GROUP BY id_anticipo";
        $resultSet=$avance->getCondicionesSinOrden($columnas, $tablas, $where, $limit);
        
        if (!(empty($resultSet[0]->total))) $total=$resultSet[0]->total;
        else $total=0;
        echo $total;
    }
    
    public function  ValidarMonto()
    {
        session_start();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados =".$cedula;
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $id_empleado = $result[0]->id_empleados;
        $avance = new AnticipoSueldoEmpleadosModel();
        $columnas="(reporte_nomina_empleados.horas_ext50+reporte_nomina_empleados.horas_ext100+
		reporte_nomina_empleados.fondos_reserva+reporte_nomina_empleados.dec_cuarto_sueldo+
		reporte_nomina_empleados.dec_tercero_sueldo+cargos_empleados.salario_cargo)-
		(reporte_nomina_empleados.anticipo_sueldo+reporte_nomina_empleados.aporte_iess1+
		reporte_nomina_empleados.asocap+reporte_nomina_empleados.prest_quirog_iess+
		reporte_nomina_empleados.prest_hipot_iess+reporte_nomina_empleados.dcto_salario+
		reporte_nomina_empleados.comision_asuntos_sociales) as liquido";
        $where="reporte_nomina_empleados.id_empleado =".$id_empleado;
        $tablas="public.reporte_nomina_empleados INNER JOIN public.empleados
	ON reporte_nomina_empleados.id_empleado = empleados.id_empleados
	INNER JOIN public.cargos_empleados
	ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $limit="ORDER BY reporte_nomina_empleados.id_registro DESC LIMIT 2";
        $resultSet=$avance->getCondicionesSinOrden($columnas, $tablas, $where, $limit);
        
        $total=0;
        foreach ($resultSet as $res)
        {
            $total+=$res->liquido;
        }
        
        echo $total;
    }
  
    public function AgregarSolicitud()
    {
        session_start();
        $funcion= "ins_solicitud_avance_empleado";
        $funcion1= "ins_cuotas_avances_empleado";
        $avance = new AnticipoSueldoEmpleadosModel();
        $empleado= new EmpleadosModel();
        $tablas="public.empleados";
        $cedula = $_SESSION['cedula_usuarios'];
        $where = "empleados.numero_cedula_empleados ='".$cedula."'";
        $id = "empleados.id_empleados";
        $result = $empleado->getCondiciones("*", $tablas, $where, $id);
        $id_empleado = $result[0]->id_empleados;
        $fecha_anticipo= $_POST['fecha_anticipo'];
        $fechaelem =  explode("-", $fecha_anticipo);
        if($fechaelem[2]>22)
        {
            $fechaelem[1]++;
         if($fechaelem[1]=="13")
         {
          $fechaelem[1]="1";
          $fechaelem[0]++;
         }
        }  
        $monto_anticipo= $_POST['monto_anticipo'];
        $tiempo_diferido= $_POST['tiempo_diferido'];
        $cuota=$monto_anticipo/$tiempo_diferido;
        $cuota=number_format((float)$cuota, 2, '.', '');
        $saldo=$monto_anticipo;
        for ($i=0; $i<$tiempo_diferido; $i++)
        {
            if($i>0)
            {
            $fechaelem[1]++;
            }
            if($i==$tiempo_diferido-1) $cuota = $saldo;
            $saldo=$saldo-$cuota;
            if ($fechaelem[1]>12)
            {$fechaelem[1]=1;
            $fechaelem[0]++;
            }
            
        }
        
        $fecha_fin_diferido = $fechaelem[0]."-".$fechaelem[1]."-21";
        
        
       $parametros = "'$id_empleado',
                     '$fecha_anticipo',
                     '$fecha_fin_diferido',
                     '$monto_anticipo',
                     '$tiempo_diferido'";
       
        
        $avance->setFuncion($funcion);
        $avance->setParametros($parametros);
        $resultado=$avance->Insert();
        
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
        $wherer = "departamentos.nombre_departamento='".$resultdep[0]->nombre_departamento."' AND cargos_empleados.nombre_cargo ILIKE 'CONTADOR%'";
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
                        anticipo_sueldo_empleados.id_anticipo,
                        anticipo_sueldo_empleados.fecha_anticipo,
                        anticipo_sueldo_empleados.monto_anticipo,
                        anticipo_sueldo_empleados.tiempo_diferido,
                        estado.nombre_estado";
        
        $tablas = "public.anticipo_sueldo_empleados INNER JOIN public.empleados
                   ON anticipo_sueldo_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON anticipo_sueldo_empleados.id_estado = estado.id_estado
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        
        if ($id_estado != "0")
        {   if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = "anticipo_sueldo_empleados.id_estado=".$id_estado." AND empleados.numero_cedula_empleados='".$cedula."'";
            }
            else {
                $where    = "anticipo_sueldo_empleados.id_estado=".$id_estado;
            }
            
        }
        else 
        {
            if ($id_rol != $id_gerente && $id_rol != $id_rh && $id_rol != $id_jefi )
            {
                $where    = " empleados.numero_cedula_empleados='".$cedula."'";
            }
            else {
                $where    = "1=1";
            }
        }
           
        
        $id       = "anticipo_sueldo_empleados.id_anticipo

";
        
        
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
            
            $resultSet=$horas_extras->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.='<th style="text-align: left;  font-size: 15px;">Monto</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Diferido</th>';
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
                    $html.='<td style="font-size: 18px;"><a href="index.php?controller=AvancesEmpleados&action=HojaSolicitud&id_permiso='.$res->id_anticipo.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_cargo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_departamento.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->fecha_anticipo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->monto_anticipo.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->tiempo_diferido.'</td>';
                    $html.='<td style="font-size: 14px;">'.$res->nombre_estado.'</td>';
                    if($id_rol==$id_rh || $id_rol==$id_jefi || $id_rol==$id_gerente)
                    {
                        if ($id_rol==$id_jefi && $res->nombre_estado=="EN REVISION" && $id_dpto_jefe == $res->id_departamento)
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_anticipo.',&quot;'.$res->nombre_estado.'&quot; )"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_anticipo.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                         else if ($id_rol==$id_rh && $res->nombre_estado=="VISTO BUENO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_anticipo.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_anticipo.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';
                        }
                        
                        else if ($id_rol==$id_gerente && $res->nombre_estado=="APROBADO")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="Aprobar('.$res->id_anticipo.',&quot;'.$res->nombre_estado.'&quot;)"><i class="glyphicon glyphicon-ok"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="Negar('.$res->id_anticipo.')"><i class="glyphicon glyphicon-remove"></i></button></span></td>';                            
                        }
                        else if ($id_rol==$id_rh && $res->nombre_estado=="APROBADO GERENCIA")
                        {
                            $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-primary" onclick="Ajustes('.$res->id_anticipo.')"><i class="glyphicon glyphicon-cog"></i></button></span></td>';
                            $html.='<td style="font-size: 18px;"></td>';                            
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
    
    public function PagarAnticipo()
    {
        session_start();
        $rp_capremci=new PlanCuentasModel();
        $id_solicitud=$_POST['id_solicitud'];
        $columnasEst="id_estado";
        $tablasEst="estado";
        $whereEst="tabla_estado='ANTICIPO_EMPLEADOS' AND nombre_estado='PAGADO'";
        $id="id_estado";
        $resultEst=$rp_capremci->getCondiciones($columnasEst, $tablasEst, $whereEst, $id);
        $id_estado=$resultEst[0]->id_estado;
        $rp_capremci->beginTran();
        ob_start();
        $query="UPDATE anticipo_sueldo_empleados
                SET
                id_estado=".$id_estado."
                WHERE id_anticipo=".$id_solicitud;
        $rp_capremci->executeNonQuery($query);
        $errores=ob_get_clean();
        $errores=trim($errores);
        $mensaje="";
        if(empty($errores))
        {
            $rp_capremci->endTran("COMMIT");
            $mensaje="OK";
        }
        else {
            $rp_capremci->endTran("ROLLBACK");
            $mensaje="ERROR".$errores;
        }
      echo $mensaje;  
    }
    
    public function TablaCuotas()
    {
    session_start();
    $id_solicitud=$_POST['id_solicitud'];
    $horas_extras = new SolicitudHorasExtraEmpleadosModel();
    $html='';
    $columnas = "cuotas_avances_empleados.fecha_cuota, cuotas_avances_empleados.monto_cuota, cuotas_avances_empleados.saldo_total_operacion, 
		empleados.nombres_empleados, anticipo_sueldo_empleados.fecha_anticipo, anticipo_sueldo_empleados.monto_anticipo";
    $tablas= "public.cuotas_avances_empleados INNER JOIN public.anticipo_sueldo_empleados
            	ON cuotas_avances_empleados.id_solicitud = anticipo_sueldo_empleados.id_anticipo
            	INNER JOIN public.empleados
            	ON cuotas_avances_empleados.id_empleados = empleados.id_empleados";
    $where= "anticipo_sueldo_empleados.id_anticipo=".$id_solicitud;
    $id = "cuotas_avances_empleados.fecha_cuota";
    $resultSet = $horas_extras->getCondiciones($columnas, $tablas, $where, $id);
    
    $html.= "<table id='tabla_solicitudes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
    $html.= "<tr>";
    $html.='<th colspan="2" style="text-align: left;  font-size: 15px;">Empleado:</th>';
    $html.='<td colspan="2" style="text-align: left;  font-size: 15px;">'.$resultSet[0]->nombres_empleados.'</td>';
    $html.= "</tr>";
    $html.= "<tr>";
    $html.='<th style="text-align: left;  font-size: 15px;">Fecha solicitud:</th>';
    $html.='<td style="text-align: left;  font-size: 15px;">'.$resultSet[0]->fecha_anticipo.'</td>';
    $html.='<th style="text-align: left;  font-size: 15px;">Monto solicitado:</th>';
    $resultSet[0]->monto_anticipo=number_format((float)$resultSet[0]->monto_anticipo, 2,".",",");
    $html.='<td style="text-align: right;  font-size: 15px;">'.$resultSet[0]->monto_anticipo.'</td>';
    $html.='</tr>';
    $html.='</table>';
    $html.= "<table id='tabla_solicitudes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
    $html.= "<thead>";
    $html.= "<tr>";
    $html.='<th style="text-align: left;  font-size: 15px;">Fecha de pago</th>';
    $html.='<th style="text-align: left;  font-size: 15px;">Monto a Pagar</th>';
    $html.='<th style="text-align: left;  font-size: 15px;">Saldo total de la operación</th>';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody>';
    foreach ($resultSet as $res)
    {
        $html.='<tr>';
        $html.='<td style="text-align: center;  font-size: 15px;">'.$res->fecha_cuota.'</td>';
        $res->monto_cuota=number_format((float)$res->monto_cuota, 2,".",",");
        $html.='<td style="text-align: right;  font-size: 15px;">'.$res->monto_cuota.'</td>';
        $res->saldo_total_operacion=number_format((float)$res->saldo_total_operacion, 2,".",",");
        $html.='<td style="text-align: right;  font-size: 15px;">'.$res->saldo_total_operacion.'</td>';
        $html.='</tr>';
    }
    
    $html.='</tbody>';
    $html.='</table>';
    $html.='<div class="pull-right" style="margin-right:15px;">
    <td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-warning" onclick="PagarAnticipo('.$id_solicitud.')"><i class="glyphicon glyphicon-log-in"></i></button></span></td>
    </div>';
     echo $html;
        
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
        
        $where = "id_anticipo=".$id_solicitud;
        $tabla = "anticipo_sueldo_empleados";
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
        
        $where = "id_anticipo=".$id_solicitud;
        $tabla = "anticipo_sueldo_empleados";
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
        
        $where = "id_anticipo=".$id_solicitud;
        $tabla = "anticipo_sueldo_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horas_extras->UpdateBy($colval, $tabla, $where);
            
        $funcion= "ins_cuotas_avances_empleado";
        $avance = new AnticipoSueldoEmpleadosModel();
        $tablas="anticipo_sueldo_empleados,";
        $where="id_anticipo=".$id_solicitud;
        $id="id_anticipo";
        $resultSet=$avance->getCondiciones("*", $tablas, $where, $id);
        $fecha_anticipo= $resultSet[0]->fecha_anticipo;
        $id_empleado = $resultSet[0]->id_empleado;
        $fechaelem =  explode("-", $fecha_anticipo);
        if($fechaelem[2]>22)
        {
            $fechaelem[1]++;
            if($fechaelem[1]=="13")
            {
                $fechaelem[1]="1";
                $fechaelem[0]++;
            }
        }
        $monto_anticipo= $resultSet[0]->monto_anticipo;
        $tiempo_diferido=$resultSet[0]->tiempo_diferido;
        $cuota=$monto_anticipo/$tiempo_diferido;
        $cuota=number_format((float)$cuota, 2, '.', '');
        $saldo=$monto_anticipo;
        for ($i=0; $i<$tiempo_diferido; $i++)
        {
            if($i>0)
            {
                $fechaelem[1]++;
            }
            if($i==$tiempo_diferido-1) $cuota = $saldo;
            $saldo=$saldo-$cuota;
            if ($fechaelem[1]>12)
            {$fechaelem[1]=1;
            $fechaelem[0]++;
            }
            $fecha_cuota=$fechaelem[0]."-".$fechaelem[1]."-21";
            
            $parametros = "'$id_empleado',
                     '$cuota',
                     '$fecha_cuota',
                     '$saldo',
                     '$id_solicitud'";
            
            
            $avance->setFuncion($funcion);
            $avance->setParametros($parametros);
            $resultado=$avance->Insert();
            
        }
                  
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
        
        $where = "id_anticipo=".$id_solicitud;
        $tabla = "anticipo_sueldo_empleados";
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
                        anticipo_sueldo_empleados.fecha_anticipo,
                        anticipo_sueldo_empleados.tiempo_diferido,
                        anticipo_sueldo_empleados.monto_anticipo";
        
        $tablas = "public.anticipo_sueldo_empleados INNER JOIN public.empleados
                   ON anticipo_sueldo_empleados.id_empleado = empleados.id_empleados
                   INNER JOIN public.estado
                   ON anticipo_sueldo_empleados.id_estado = estado.id_estado
                   INNER JOIN public.departamentos
                   ON departamentos.id_departamento = empleados.id_departamento
                   INNER JOIN public.cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $where= "anticipo_sueldo_empleados.id_anticipo=".$id_permiso;
        $id="anticipo_sueldo_empleados.id_anticipo";
        
        $rsdatos = $permisos->getCondiciones($columnas, $tablas, $where, $id);
        echo $rsdatos;
        $datos_reporte['NOMBREEMPLEADO']=$rsdatos[0]->nombres_empleados;
        $datos_reporte['CARGOEMPLEADO']=$rsdatos[0]->nombre_cargo;
        $datos_reporte['DPTOEMPLEADO']=$rsdatos[0]->nombre_departamento;
        $fechaelem = explode("-", $rsdatos[0]->fecha_anticipo);
        $ind = intval($fechaelem[1])-1;
        $datos_reporte['FECHA']=$fechaelem[2]." de ".$meses[$ind]." de ".$fechaelem[0];
        $datos_reporte['MONTO']=$rsdatos[0]->monto_anticipo;
        $diferido=$rsdatos[0]->tiempo_diferido;
        if ($diferido>1)
        {
         $diferido.=" meses";   
        }
        else $diferido.=" mes";
        $datos_reporte['DIFERIDO']=$diferido;
        
                
        $this->verReporte("SolicitudAvance", array('datos_reporte'=>$datos_reporte, 'datos_empresa'=>$datos_empresa));
        
        
            
    }
}

?>