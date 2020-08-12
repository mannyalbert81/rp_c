<?php
class MarcacionesController extends ControladorBase{
    public function index(){
        session_start();
        $this->view_Administracion("Marcaciones",array(
            "resultSet"=>""           
        ));
    }
    
    public function MostrarNotificacion()
    {
       $html='';
       session_start();
       $marcacion = new RegistroRelojEmpleadosModel();
       $fecha_inicio = $_POST['fecha_inicio'];
       $fecha_final = $_POST['fecha_final'];
       
       $columnas="empleados.nombres_empleados,
                     empleados.numero_cedula_empleados,
                     registro_reloj_empleados.hora_marcacion_empleados,
                     registro_reloj_empleados.fecha_marcacion_empleados,
                     registro_reloj_empleados.tipo_registro_empleados,
                     oficina.nombre_oficina,
                     empleados.id_grupo_empleados";
       $tablas= "public.empleados INNER JOIN public.registro_reloj_empleados
                  ON empleados.id_empleados = registro_reloj_empleados.id_empleados
                  INNER JOIN public.oficina
                  ON empleados.id_oficina = oficina.id_oficina";
       $where="fecha_marcacion_empleados BETWEEN '".$this->FormatoFecha($fecha_inicio)."'
                AND '".$this->FormatoFecha($fecha_final)."'";
       $id = "empleados.numero_cedula_empleados,registro_reloj_empleados.fecha_marcacion_empleados, registro_reloj_empleados.hora_marcacion_empleados";
       
       $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
       
       $horarios = new HorariosEmpleadosModel();
       $columnas="horarios_empleados.hora_entrada_empleados,
        horarios_empleados.hora_salida_almuerzo_empleados,
        horarios_empleados.hora_entrada_almuerzo_empleados,
        horarios_empleados.hora_salida_empleados,
        horarios_empleados.id_grupo_empleados,
        horarios_empleados.tiempo_gracia_empleados,
        horarios_empleados.id_oficina";
       
       $empleados = new EmpleadosModel();
       
       $tablas = "public.empleados INNER JOIN public.estado
                   ON empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON empleados.id_oficina = oficina.id_oficina";
       $where = "estado.nombre_estado='ACTIVO'";
       
       $id = "empleados.id_empleados";
       
       $resultEmp = $empleados->getCondiciones("*", $tablas, $where, $id);
       
       $userarray= [];
       
       
       $numregistros=0;
       
       $advertencias=0;
       
       $currentdate=0;
       
       $html="";
       
       if (!(empty($resultSet)))
       {
           
           foreach($resultEmp as $emp)
           {
               $currentdate=0;
               $numregistros=0;
               foreach($resultSet as $res)
               {
                   
                   if($res->numero_cedula_empleados == $emp->numero_cedula_empleados)
                   {
                       
                       if ($currentdate!= $res->fecha_marcacion_empleados)
                       {
                           
                           if($numregistros>0 && $numregistros<4)
                           {
                               $advertencias++;
                           }
                           
                           $numregistros=0;
                           $currentdate= $res->fecha_marcacion_empleados;
                           
                       }
                       
                       if (!(empty($res->hora_marcacion_empleados)))
                       {
                           $numregistros++;
                       }
                   }  
               }
               if($advertencias>0)
               {
                   $itemb = $emp->numero_cedula_empleados.'|'.$emp->nombres_empleados.'|'.$advertencias;
                array_push($userarray,$itemb);
               }
               $advertencias=0;
            }
            $usu="";
            if(sizeof($userarray)>1)
            {
             $usu="usuarios";   
            }
            else
            {
             $usu="usuario"; 
            }
            
            
            if(sizeof($userarray)>0)
            {
                $html.='<li class="dropdown messages-menu">';
                $html.='<button type="button" class="btn btn-warning" data-toggle="dropdown">';
                $html.='<i class="fa fa-user-o"></i>';
                $html.='</button>';
                $html.='<span class="label label-danger">'.sizeof($userarray).'</span>';
                $html.='<ul class="dropdown-menu">';
                $html.='<li  class="header">Hay '.sizeof($userarray).' '.$usu.' con advertencias.</li>';
                $html.='<li>';
                $html.= '<table style = "width:100%; border-collapse: collapse;" border="1">';
                $html.='<tbody>';
                foreach ($userarray as $us)
                {
                    
                    $datos= explode("|", $us);
                    $html.='<tr height = "25">';
                    $html.='<td bgcolor="#F5F5F5" style="font-size: 16px; text-align:center;"><a href="javascript:EditAdvertencias(&quot;'.$datos[0].'&quot;)"><b>'.$datos[1].'<b></a></td>';
                    $html.='<td width="25" bgcolor="EC2E2E" style="font-size: 16px; text-align:center;" valign="top"><font color="#FFFFFF"><b>'.$datos[2].'<b></font></td>';
                    $html.='</tr>';
                   
                }
                $html.='</tbody>';
                $html.='</table>';
                
                $html.='</li>';
                
                echo $html;
            }
            else
            {
                $html.='<li class="dropdown messages-menu">';
                $html.='<button type="button" class="btn btn-success" data-toggle="dropdown">';
                $html.='<i class="fa fa-user-o"></i>';
                $html.='</button>';
                $html.='<ul class="dropdown-menu">';
                $html.='<li class="header">No hay advertencias</li>';
                $html.='</ul>';
                $html.='</li>';
                
                echo $html;
            }
       }
      
       echo $this->FormatoFecha($fecha_inicio)."<=>".$this->FormatoFecha($fecha_final);
    }
    
    public function AgregarMarcacion()
    {
        session_start();
        $marcacion = new RegistroRelojEmpleadosModel();
        $funcion = "ins_marcacion_empleado";
        $hora_marcacion = $_POST['hora_marcacion'];
        $fecha_marcacion = $_POST['fecha_marcacion'];
        $cedula_empleado = $_POST['numero_cedula'];
        $id_registro = $_POST['id_registro'];
        $tipo_registro = $_POST['tipo_registro'];
        
        $columnas = "empleados.id_empleados";
        
        $tablas = "public.empleados";
        
        
        $where    = "empleados.numero_cedula_empleados=".$cedula_empleado;
        
        $id       = "empleados.id_empleados";
        
        $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
               
        $id_empleado = (string)$resultSet[0]->id_empleados;
            
            $parametros = "'$id_empleado',
                       '$hora_marcacion',
                       '$fecha_marcacion',
                       '$id_registro',
                       '$tipo_registro'";
            $marcacion->setFuncion($funcion);
            $marcacion->setParametros($parametros);
            $resultado=$marcacion->Insert();
            
            echo 1;
            
               
    }
    
    public function FormatoFecha($fecha)
    {
     $datos= explode("/", $fecha);
     if($datos[1]<10)
     {
         $datos[1]="0".$datos[1];
     }
     return $datos[2]."-".$datos[1]."-".$datos[0];
    }
    
    public function SubirReporte()
    {
        session_start();
        $reportenomina= new ReporteNominaEmpleadosModel();
        $marcacion = new RegistroRelojEmpleadosModel();
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        
        $columnasPago="estado.id_estado";
        
        $tablasPago="public.estado";
        
        $wherePago="estado.nombre_estado='PAGADO' AND estado.tabla_estado='ANTICIPO_EMPLEADOS'";
        
        $idPago="estado.id_estado";
        
        $resultPago=$marcacion->getCondiciones($columnasPago, $tablasPago, $wherePago, $idPago);
        
        $columnas="empleados.nombres_empleados,
                     empleados.numero_cedula_empleados,
                     registro_reloj_empleados.hora_marcacion_empleados,
                     registro_reloj_empleados.fecha_marcacion_empleados,
                     registro_reloj_empleados.tipo_registro_empleados,
                     oficina.nombre_oficina,
                     empleados.id_grupo_empleados";
        $tablas= "public.empleados INNER JOIN public.registro_reloj_empleados
                  ON empleados.id_empleados = registro_reloj_empleados.id_empleados
                  INNER JOIN public.oficina
                  ON empleados.id_oficina = oficina.id_oficina";
        $where="fecha_marcacion_empleados BETWEEN '".$this->FormatoFecha($fecha_inicio)."'
                AND '".$this->FormatoFecha($fecha_final)."'";
        $id = "empleados.numero_cedula_empleados,registro_reloj_empleados.fecha_marcacion_empleados, registro_reloj_empleados.hora_marcacion_empleados";
        
        $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnasper="empleados.numero_cedula_empleados, permisos_empleados.fecha_solicitud,
	                  permisos_empleados.hora_desde, permisos_empleados.hora_hasta";
        
        $tablasper= "public.empleados INNER JOIN public.permisos_empleados
                  ON empleados.id_empleados = permisos_empleados.id_empleado
                    INNER JOIN public.estado
				  ON permisos_empleados.id_estado=estado.id_estado";
        $whereper="permisos_empleados.fecha_solicitud BETWEEN '".$this->FormatoFecha($fecha_inicio)."'
                AND '".$this->FormatoFecha($fecha_final)."' AND estado.nombre_estado='APROBADO GERENCIA' OR estado.nombre_estado='SIN CERTIFICADO'";
        $idper = "empleados.numero_cedula_empleados";
        
        $resultPer=$marcacion->getCondiciones($columnasper, $tablasper, $whereper, $idper);
        
        
        $columnasav="empleados.numero_cedula_empleados, anticipo_sueldo_empleados.fecha_anticipo,
                	 anticipo_sueldo_empleados.tiempo_diferido, anticipo_sueldo_empleados.monto_anticipo,
                	 anticipo_sueldo_empleados.fecha_fin_diferido,
                     anticipo_sueldo_empleados.id_anticipo";
        
        $tablasav= "anticipo_sueldo_empleados INNER JOIN empleados
	                ON anticipo_sueldo_empleados.id_empleado = empleados.id_empleados
	                INNER JOIN estado
	                ON anticipo_sueldo_empleados.id_estado = estado.id_estado";
        
        $whereav="estado.nombre_estado = 'APROBADO GERENCIA'";
        
        $idav = "anticipo_sueldo_empleados.id_anticipo";
        
        $resultAv=$marcacion->getCondiciones($columnasav, $tablasav, $whereav, $idav);
        
        $columnasext="empleados.numero_cedula_empleados,
                       empleados.id_grupo_empleados,
                       solicitud_horas_extras_empleados.fecha_solicitud,
	                   solicitud_horas_extras_empleados.hora_inicio_solicitud,
                        solicitud_horas_extras_empleados.hora_fin_solicitud";
        
        $tablasext= "solicitud_horas_extras_empleados INNER JOIN empleados
                	   ON solicitud_horas_extras_empleados.id_empleado = empleados.id_empleados
                	   INNER JOIN estado
                	   ON solicitud_horas_extras_empleados.id_estado = estado.id_estado";
        
        $whereext="solicitud_horas_extras_empleados.fecha_solicitud
            
 BETWEEN '".$this->FormatoFecha($fecha_inicio)."'
                AND '".$this->FormatoFecha($fecha_final)."' AND estado.nombre_estado='APROBADO GERENCIA'";
        
        $idext = "solicitud_horas_extras_empleados.id_solicitud";
        
        $resultExt=$marcacion->getCondiciones($columnasext, $tablasext, $whereext, $idext);
        
        $horarios = new HorariosEmpleadosModel();
        $columnas="horarios_empleados.hora_entrada_empleados,
        horarios_empleados.hora_salida_almuerzo_empleados,
        horarios_empleados.hora_entrada_almuerzo_empleados,
        horarios_empleados.hora_salida_empleados,
        horarios_empleados.id_grupo_empleados,
        horarios_empleados.tiempo_gracia_empleados,
        horarios_empleados.id_oficina";
        
        $tablas= "public.horarios_empleados INNER JOIN public.estado
                   ON horarios_empleados.id_estado = estado.id_estado";
        $where="estado.nombre_estado='ACTIVO'";
        $id = "horarios_empleados.id_horarios_empleados";
        
        $resultHor=$horarios->getCondiciones($columnas, $tablas, $where, $id);
        
        $empleados = new EmpleadosModel();
        
        $tablas = "public.descuentos_salarios_empleados";
        $where = "1=1";
        
        $id = "descuentos_salarios_empleados.id_descuento";
        
        $resultDSE= $empleados->getCondiciones("*", $tablas, $where, $id);
        
        $tablas = "public.empleados INNER JOIN public.estado
                   ON empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON empleados.id_oficina = oficina.id_oficina
                   INNER JOIN cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $where = "estado.nombre_estado='ACTIVO'";
        
        $id = "empleados.id_empleados";
        
        $resultEmp = $empleados->getCondiciones("*", $tablas, $where, $id);        
        
        $numregistros=0;
        
        $diastrabajo=0;
        
        $numdiassintrabajo=0;
        
        $advertencias=0;
        
        $hent=0;
        
        $hsal=0;
        
        $currentdate=0;
        
        $horasextra50=0;
        
        $horasextra100=0;
        
        $html="";
        
        if (!(empty($resultSet)))
        {
            foreach($resultEmp as $emp)
            {
                $salario=$emp->salario_cargo;
                $salariodia=$salario/30;
                $salariohora=$salariodia/8;
                $salariomin=$salariohora/60;
                $tatraso=0;
                $tdescuento=0;
                $dctosalario=0;
                $dctoavance=0;
                $numregistros=0;
                
                $diastrabajo=0;
                
                $numdiassintrabajo=0;
                
                $advertencias=0;
                
                $hent=0;
                
                $hsal=0;
                
                $currentdate=0;
                
                $horasextra50=0;
                
                $horasextra100=0;
                foreach($resultSet as $res)
                {
                    $dayOfWeek = date("D", strtotime($currentdate));
                    
                    if ($res->tipo_registro_empleados== "Entrada") $hent=$res->hora_marcacion_empleados;
                    
                    if ($res->tipo_registro_empleados== "Salida") $hsal=$res->hora_marcacion_empleados;
                    
                    if($res->numero_cedula_empleados == $emp->numero_cedula_empleados)
                    {
                        
                        if ($currentdate!= $res->fecha_marcacion_empleados)
                        {
                            
                            if($numregistros>0 && $numregistros<4)
                            {
                                $advertencias++;
                            }
                            if ($numregistros==0 && ($dayOfWeek!="Sat" && $dayOfWeek!="Sun") && $currentdate !=0)
                            {
                                $numdiassintrabajo++;
                            }
                            $numregistros=0;
                            $currentdate= $res->fecha_marcacion_empleados;
                            
                        }
                        
                        if (!(empty($res->hora_marcacion_empleados)))
                        {
                            $numregistros++;
                        }
                        if ($numregistros==4)
                        {
                            if ($dayOfWeek!="Sat" && $dayOfWeek!="Sun")
                            {
                                $diastrabajo++;
                            }
                        }
                        
                        foreach ($resultHor as $hor)
                        {
                            if ($res->id_grupo_empleados== $hor->id_grupo_empleados && $res->tipo_registro_empleados=="Entrada"
                                && !(empty($res->hora_marcacion_empleados)))
                            {
                                $horactr=$hor->hora_entrada_empleados;
                                
                                $horaentrada=$res->hora_marcacion_empleados;
                                $to_time = strtotime($horaentrada);
                                $from_time = strtotime("+".$hor->tiempo_gracia_empleados." minutes", strtotime($horactr));
                                
                                $diferenci= round((($to_time - $from_time) / 60),0, PHP_ROUND_HALF_DOWN);
                                
                                
                                if ($diferenci>0)
                                {
                                    $tatraso=$tatraso+$diferenci;
                                }
                            }
                            
                            if ($res->id_grupo_empleados== $hor->id_grupo_empleados && $res->tipo_registro_empleados=="Salida"
                                && !(empty($res->hora_marcacion_empleados)))
                            {
                                $horactr=$hor->hora_salida_empleados;
                                $horasalida=$res->hora_marcacion_empleados;
                                $to_time = strtotime($horasalida);
                                $from_time = strtotime($horactr);
                                
                                $diferenci= intval((($to_time - $from_time) / 60));
                                if ($diferenci<0)
                                {
                                    $tdescuento=$tdescuento+abs($diferenci);
                                }
                            }
                            
                            if (!(empty($resultExt)))
                            {
                               
                                foreach ($resultExt as $ext)
                                {
                                    if ($res->tipo_registro_empleados == "Entrada") $horaentradafinde=$res->hora_marcacion_empleados;
                                    if($ext->numero_cedula_empleados == $emp->numero_cedula_empleados)
                                    {
                                        if($ext->fecha_solicitud == $res->fecha_marcacion_empleados && $res->tipo_registro_empleados=="Salida")
                                        {
                                            
                                            if ($res->id_grupo_empleados== $hor->id_grupo_empleados)
                                            {
                                                $dayOfWeek = date("D", strtotime($ext->fecha_solicitud));
                                                
                                                if($dayOfWeek != "Sat" && $dayOfWeek != "Sun")
                                                {
                                                    $desde=$ext->hora_inicio_solicitud;
                                                    $hasta=$ext->hora_fin_solicitud;
                                                    $to_time = strtotime($hasta);
                                                    $from_time = strtotime($desde);
                                                    
                                                    $diferenci= intval((($to_time - $from_time) / 60));
                                                    if($diferenci > 0)
                                                    {
                                                        $horasextra50=$horasextra50+($diferenci*($salariomin/2));
                                                    }
                                                    else
                                                    {
                                                        $desde=$ext->hora_inicio_solicitud;
                                                        $hasta="23:59:59";
                                                        $to_time = strtotime($hasta);
                                                        $from_time = strtotime($desde);
                                                        $dif= intval((($to_time - $from_time) / 60));
                                                        $horasextra50=$horasextra50+($dif*($salariomin/2));
                                                        
                                                        $desde="00:00:00";
                                                        $hasta=$ext->hora_fin_solicitud;
                                                        $to_time = strtotime($hasta);
                                                        $from_time = strtotime($desde);
                                                        $dif= intval((($to_time - $from_time) / 60));
                                                        $horasextra100=$horasextra100+($dif*$salariomin);
                                                    }
                                                }
                                                else
                                                {
                                                    $desde=$ext->hora_inicio_solicitud;
                                                    $hasta=$ext->hora_fin_solicitud;
                                                    $to_time = strtotime($hasta);
                                                    $from_time = strtotime($desde);
                                                    
                                                    $diferenci= intval((($to_time - $from_time) / 60));
                                                    if($diferenci > 0)
                                                    {
                                                        $horasextra100=$horasextra100+($diferenci*($salariomin));
                                                    }
                                                    else
                                                    {   echo "despues de las 12";
                                                    $desde=$ext->hora_inicio_solicitud;
                                                    $hasta="23:59:59";
                                                    $to_time = strtotime($hasta);
                                                    $from_time = strtotime($desde);
                                                    $dif= intval((($to_time - $from_time) / 60));
                                                    $horasextra100=$horasextra100+($dif*($salariomin));
                                                    
                                                    $desde="00:00:00";
                                                    $hasta=$ext->hora_fin_solicitud;
                                                    $to_time = strtotime($hasta);
                                                    $from_time = strtotime($desde);
                                                    $dif= intval((($to_time - $from_time) / 60));
                                                    $horasextra100=$horasextra100+($dif*$salariomin);
                                                    }
                                                }
                                                echo $horasextra100."\n";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    
                }
                if (!(empty($resultPer)))
                {
                    foreach ($resultPer as $per)
                    {
                        if($per->numero_cedula_empleados == $emp->numero_cedula_empleados)
                        {
                            $horahasta=$per->hora_hasta;
                            $horadesde=$per->hora_desde;
                            $to_time = strtotime($horahasta);
                            $from_time = strtotime($horadesde);
                            
                            $diferenci= intval((($to_time - $from_time) / 60));
                            if ($diferenci>0)
                            {
                                $dctosalario = $dctosalario + ($diferenci*$salariomin);
                            }
                        }
                    }
                }
                if(!(empty($resultAv)))
                {
                    foreach ($resultAv as $av)
                    {
                        if ($av->numero_cedula_empleados == $emp->numero_cedula_empleados)
                        {
                            $diafindiferido = date("Y-m-d", strtotime($av->fecha_fin_diferido));
                            $fecha_pago=$fecha_final;
                            $elem=explode("/", $fecha_pago);
                            $fecha_pago=$elem[2]."-".$elem[1]."-".$elem[0];
                            $diafinperiodo = date("Y-m-d", strtotime($fecha_pago));
                            
                            $columnasav="cuotas_avances_empleados.monto_cuota";
                            
                            $tablasav= "public.cuotas_avances_empleados INNER JOIN public.anticipo_sueldo_empleados
                                    	ON cuotas_avances_empleados.id_solicitud = anticipo_sueldo_empleados.id_anticipo
                                    	INNER JOIN public.estado
                                    	ON anticipo_sueldo_empleados.id_estado = estado.id_estado";
                            
                            $whereav="cuotas_avances_empleados.fecha_cuota='".$diafinperiodo."' AND cuotas_avances_empleados.id_empleados=".$emp->id_empleados."
                                        AND estado.nombre_estado = 'APROBADO GERENCIA'";
                            
                            
                            $idav = "anticipo_sueldo_empleados.id_anticipo";
                            
                            $cuota=$marcacion->getCondiciones($columnasav, $tablasav, $whereav, $idav);
                            
                            if (!(empty($cuota[0]->monto_cuota))) $cuotaapagar=$cuota[0]->monto_cuota;//revisar cuotas
                            else $cuotaapagar=0;
                            
                            if($diafindiferido>$diafinperiodo)
                            {
                                $dctoavance=$cuotaapagar;
                            }
                            else if ($diafindiferido==$diafinperiodo)
                            {
                                $dctoavance=$cuotaapagar;
                                session_start();
                                $id_solicitud=$av->id_anticipo;
                                $horas_extras = new SolicitudHorasExtraEmpleadosModel();
                                
                                $where = "id_anticipo=".$id_solicitud;
                                $tabla = "anticipo_sueldo_empleados";
                                $colval = "id_estado=".$resultPago[0]->id_estado;
                                $horas_extras->UpdateBy($colval, $tabla, $where);
                            }
                        }
                    }
                }
                $dec_tercero=($salario+$horasextra50+$horasextra100)/12;
                $dec_cuarto=(394/12);
                $dec_tercero=number_format((float)$dec_tercero, 2, '.', '');
                $dec_cuarto=number_format((float)$dec_cuarto, 2, '.', '');
                $dctosalarioatr = $tatraso*$salariomin;
                $dctosalariodcto= $tdescuento*$salariomin;
                $dctosalario=$dctosalarioatr+$dctosalariodcto;
                $dctosalariofalta=$numdiassintrabajo*8*60*$salariomin;
                $dctosalario=0.00;
                $horasextra50=number_format((float)$horasextra50, 2, '.', '');
                $horasextra100=number_format((float)$horasextra100, 2, '.', '');
                $fondosreserva=($emp->salario_cargo+$horasextra50+$horasextra100)*0.0833;
                $fondosreserva=number_format((float)$fondosreserva, 2, '.', '');
                $aporteiess1=($emp->salario_cargo+$horasextra50+$horasextra100)*($resultDSE[0]->descuento_iess1*0.01);
                $aporteiess1=number_format((float)$aporteiess1, 2, '.', '');
                $aporteiess2=($emp->salario_cargo+$horasextra50+$horasextra100)*($resultDSE[0]->descuento_iess2*0.01);
                $aporteiess2=number_format((float)$aporteiess2, 2, '.', '');
                
                if($emp->mensualizado_decimo_tercero_empleados=='t')
                {
                    $sueldo13=$dec_tercero;
                }
                else {
                    $sueldo13=0.00;
                }
                if($emp->mensualizado_decimo_cuarto_empleados=='t')
                {
                    $sueldo14=$dec_cuarto;
                }
                else {
                    $sueldo14=0.00;
                }
                if($emp->mensualizado_fondos_de_reserva_empleados=='t')
                {
                    $fondos_de_reserva=$fondosreserva;
                }
                else {
                    $fondos_de_reserva=0.00;
                }
                
               
                $asocap=0.00;
                $quiroiess=0.00;
                $hipoiess=0.00;
                $periodo=$fecha_inicio."-".$fecha_final;
                $asuntos_sociales=$resultDSE[0]->asuntos_sociales;
                $funcion = "ins_reporte_nomina_empleado";
                $parametros = "'$emp->id_empleados',
                                '$horasextra50',
                                '$horasextra100',
                                '$fondos_de_reserva',
                                '$sueldo14',
                                '$sueldo13',
                                '$dctoavance',
                                '$aporteiess1',
                                '$asocap',
                                '$quiroiess',
                                '$hipoiess',
                                '$dctosalario',
                                '$asuntos_sociales',
                                '$periodo'";
                $reportenomina->setFuncion($funcion);
                $reportenomina->setParametros($parametros);
                $resultado=$reportenomina->Insert();
                
                $funcion = "ins_provisiones_nomina_empleado";
                $parametros = "'$emp->id_empleados',
                                '$fondosreserva',
                                '$dec_tercero',
                                '$dec_cuarto',
                                '$aporteiess2',
                                '$periodo',
                                '$emp->mensualizado_decimo_tercero_empleados',
                                '$emp->mensualizado_decimo_cuarto_empleados',
                                '$emp->mensualizado_fondos_de_reserva_empleados'
                                ";
                $reportenomina->setFuncion($funcion);
                $reportenomina->setParametros($parametros);
                $resultado=$reportenomina->Insert();
                
                $diastrabajo=0;
                $numdiassintrabajo=0;
                $advertencias=0;
                $horasextra50=0;
                $horasextra100=0;
            }
        }
    }
    
    
    public function GetReporte()
    {
        session_start();
        $marcacion = new RegistroRelojEmpleadosModel();
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        
        
        $columnas="empleados.nombres_empleados,
                     empleados.numero_cedula_empleados,
                     registro_reloj_empleados.hora_marcacion_empleados,
                     registro_reloj_empleados.fecha_marcacion_empleados,
                     registro_reloj_empleados.tipo_registro_empleados,
                     oficina.nombre_oficina,
                     empleados.id_grupo_empleados";
        $tablas= "public.empleados INNER JOIN public.registro_reloj_empleados
                  ON empleados.id_empleados = registro_reloj_empleados.id_empleados
                  INNER JOIN public.oficina
                  ON empleados.id_oficina = oficina.id_oficina";
        $where="fecha_marcacion_empleados BETWEEN '".$this->FormatoFecha($fecha_inicio)."'
                AND '".$this->FormatoFecha($fecha_final)."'";
        $id = "empleados.numero_cedula_empleados,registro_reloj_empleados.fecha_marcacion_empleados, registro_reloj_empleados.hora_marcacion_empleados";
        
        $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
        
        $horarios = new HorariosEmpleadosModel();
        $columnas="horarios_empleados.hora_entrada_empleados,
        horarios_empleados.hora_salida_almuerzo_empleados,
        horarios_empleados.hora_entrada_almuerzo_empleados,
        horarios_empleados.hora_salida_empleados,
        horarios_empleados.id_grupo_empleados,
        horarios_empleados.tiempo_gracia_empleados,
        horarios_empleados.id_oficina";
        
        $tablas= "public.horarios_empleados INNER JOIN public.estado
                   ON horarios_empleados.id_estado = estado.id_estado";
        $where="estado.nombre_estado='ACTIVO'";
        $id = "horarios_empleados.id_horarios_empleados";
        
        $resultHor=$horarios->getCondiciones($columnas, $tablas, $where, $id);
        
        $empleados = new EmpleadosModel();
        
        $tablas = "public.empleados INNER JOIN public.estado
                   ON empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON empleados.id_oficina = oficina.id_oficina
                   INNER JOIN cargos_empleados
                   ON empleados.id_cargo_empleado = cargos_empleados.id_cargo";
        $where = "estado.nombre_estado='ACTIVO'";
        
        $id = "empleados.id_empleados";
        
        $resultEmp = $empleados->getCondiciones("*", $tablas, $where, $id);        
        
        $numregistros=0;
        
        $horastrabajo=0;
        
        $numdiassintrabajo=0;
        
        $numdiastrabajo=0;
        
        $advertencias=0;
        
        $hent=0;
        
        $hsal=0;
        
        $currentdate=0;
        
        $html="";
        
        if (!(empty($resultSet)))
        {
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:425px; overflow-y:scroll;">';
            $html.= "<table id='tabla_marcaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 16px;"></th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Empleado</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Oficina</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Trabajado</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Faltas(días)</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Advertencias</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Atraso</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Tiempo Dcto</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $i=0;
        foreach($resultEmp as $emp)
        {
        $tatraso=0;
        $textra=0;
        $textrac=0;
        $tdescuento=0;
        
        $numregistros=0;
        
        $numdiassintrabajo=0;
        
        $numdiastrabajo=0;
        
        $currentdate=0;
        foreach($resultSet as $res)
        {   
            $dayOfWeek = date("D", strtotime($currentdate));
           // echo "<h4>".$dayOfWeek."|".$res->fecha_marcacion_empleados."|".$currentdate."|".$res->numero_cedula_empleados."</h4>";
            
            if ($res->tipo_registro_empleados== "Entrada") $hent=$res->hora_marcacion_empleados;
            
            if ($res->tipo_registro_empleados== "Salida") $hsal=$res->hora_marcacion_empleados;
            
            if($res->numero_cedula_empleados == $emp->numero_cedula_empleados)
            {
                
                if ($currentdate!= $res->fecha_marcacion_empleados)
                {
                   
                    if($numregistros>0 && $numregistros<4)
                    {
                        
                        $advertencias++;
                    }
                    if ($numregistros==0 && ($dayOfWeek!="Sat" && $dayOfWeek!="Sun") && $currentdate !=0)
                    {
                        
                        $numdiassintrabajo++;
                    }
                    $numregistros=0;
                    $currentdate= $res->fecha_marcacion_empleados;
                   
                    }
                
                if (!(empty($res->hora_marcacion_empleados)))
                {
                    $numregistros++;
                }
                if ($numregistros==4)
                {
                    $numdiastrabajo++;
                    if ($dayOfWeek!="Sat" && $dayOfWeek!="Sun")
                    {
                        $to_time = strtotime($hsal);
                        $from_time = strtotime($hent);
                        $diferenci= round((($to_time - $from_time) / 60),0, PHP_ROUND_HALF_DOWN);
                        
                        if ($diferenci>0)
                        {
                            $horastrabajo=$horastrabajo+$diferenci;
                        }
                    }
                    else
                    {
                        $to_time = strtotime($hsal);
                        $from_time = strtotime($hent);
                        $diferenci= round((($to_time - $from_time) / 60),0, PHP_ROUND_HALF_DOWN);
                        $textrac=$textrac+$diferenci;
                    }
                }
                
                
                foreach ($resultHor as $hor)
                {
                    if ($res->id_grupo_empleados== $hor->id_grupo_empleados && $res->tipo_registro_empleados=="Entrada" 
                        && !(empty($res->hora_marcacion_empleados)))
                    {
                        $horactr=$hor->hora_entrada_empleados;
                        
                        $horaentrada=$res->hora_marcacion_empleados;
                        $to_time = strtotime($horaentrada);
                        $from_time = strtotime("+".$hor->tiempo_gracia_empleados." minutes", strtotime($horactr));

                        $diferenci= round((($to_time - $from_time) / 60),0, PHP_ROUND_HALF_DOWN);

                        if ($diferenci>0)
                        {
                            $tatraso=$tatraso+$diferenci;
                        }
                    }
                    
                    if ($res->id_grupo_empleados== $hor->id_grupo_empleados && $res->tipo_registro_empleados=="Salida"
                        && !(empty($res->hora_marcacion_empleados)))
                    {
                        $horactr=$hor->hora_salida_empleados;
                        $to_time = strtotime("2008-12-13 10:42:00");
                        $horasalida=$res->hora_marcacion_empleados;
                        $to_time = strtotime($horasalida);
                        $from_time = strtotime($horactr);
                        
                        $diferenci= intval((($to_time - $from_time) / 60));
                        if ($diferenci>0)
                        {
                            $textra=$textra+$diferenci;
                        }
                        else
                        {
                         $tdescuento=$tdescuento+abs($diferenci);   
                        }
                    }
                }
                
            }
            
            
            $horasatraso = intval(($tatraso / 60));
            $horasatraso .= "h".$tatraso%60;
            $horasdcto = intval(($tdescuento / 60));
            $horasdcto .= "h".$tdescuento%60; 
             
       
       }
       $horastrabajo = intval(($horastrabajo / 60));
       $horastrabajo .= "h".$horastrabajo%60;
       
       $i++;
       $html.='<tr>';
       $html.='<td style="font-size: 15px;">'.$i.'</td>';
       $html.='<td style="font-size: 15px;">'.$emp->nombres_empleados.'</td>';
       $html.='<td style="font-size: 15px;">'.$emp->nombre_oficina.'</td>';
       $html.='<td style="font-size: 15px;">'.$numdiastrabajo.'</td>';
       $html.='<td style="font-size: 15px;">'.$numdiassintrabajo.'</td>';
       $html.='<td style="font-size: 15px;">'.$advertencias.'</td>';
       $html.='<td style="font-size: 15px;">'.$horasatraso.'</td>';
       $html.='<td style="font-size: 15px;">'.$horasdcto.'</td>';
       $html.='</tr>';
       
       $horastrabajo=0;
       
       $numdiassintrabajo=0;
       
       $numdiastrabajo=0;
       
       $advertencias=0;
        

     }
     $html.='</tbody>';
     $html.='</table>';
     $html.='</section></div>';
     $html.='<div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
     <div class="form-group">
     <button type="button" id="GenReport" name="GenReport" class="btn btn-primary" onclick="GenerarReporte()">Generar Reporte</button>
     </div>
     </div>';
     
    }
    else {
        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj para el periodo actual...</b>';
        $html.='</div>';
        $html.='</div>';
    }
    echo $html;
   }
    
    public function ActualizarRegistros()
    {
        session_start();
        $marcacion = new RegistroRelojEmpleadosModel();
        $funcion = "ins_marcacion_empleado";
        $registros = $_POST['registros'];
        $registros_array = json_decode($registros, true);
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $id_oficina = $_POST['id_oficina'];
        $id_registro = 0;

        $eliminar=$marcacion->deleteByWhere("fecha_marcacion_empleados BETWEEN '".$this->FormatoFecha($fecha_inicio)."' 
        AND '".$this->FormatoFecha($fecha_final)."' AND id_empleados IN (SELECT id_empleados FROM empleados WHERE id_oficina=".$id_oficina.")");
        $columnas = "empleados.id_empleados, empleados.numero_cedula_empleados";
        
        $tablas = "public.empleados INNER JOIN public.estado
                   ON empleados.id_estado=estado.id_estado";
        
        $where    = "1=1 AND nombre_estado='ACTIVO'";
        
        $id       = "empleados.id_empleados";
        
        $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
        
        foreach ($registros_array as $res)
        {
          $id_empleado=0;
          foreach ($resultSet as $eid)
          {
              
              if($res["Cedula"]==$eid->numero_cedula_empleados)
              {
                  
               $id_empleado=$eid->id_empleados;   
              }
          }
          $fecha_marcacion=$this->FormatoFecha($res["Fecha"]);
          if (!(array_key_exists("Registro Entrada",$res)) || empty($res["Registro Entrada"]) )
          {
              if($res["Horario"]=="MAÑANA" || $res["Horario"]=="Mañana")
              {
              $parametros = $id_empleado.",NULL,'".$fecha_marcacion."',".$id_registro.",'Entrada'";
              }
              else
              {
              $parametros = $id_empleado.",NULL,'".$fecha_marcacion."',".$id_registro.",'Entrada Almuerzo'";
              }
          }
          else 
          {
              if($res["Horario"]=="MAÑANA" || $res["Horario"]=="Mañana")
              {
             $hora_marcacion=$res["Registro Entrada"];
          $parametros = "'$id_empleado',
                       '$hora_marcacion',
                       '$fecha_marcacion',
                       '$id_registro',
                        'Entrada'";
              }
              else 
              {
                  $hora_marcacion=$res["Registro Entrada"];
                  $parametros = "'$id_empleado',
                       '$hora_marcacion',
                       '$fecha_marcacion',
                       '$id_registro',
                        'Entrada Almuerzo'";
              }
          }
          
          
          $marcacion->setFuncion($funcion);
          $marcacion->setParametros($parametros);
          $resultado=$marcacion->Insert();
          
          if (!(array_key_exists("Registro Salida",$res))|| empty($res["Registro Salida"]) )
          {
              
              if($res["Horario"]=="MAÑANA" || $res["Horario"]=="Mañana")
              {
                  $parametros = $id_empleado.",NULL,'".$fecha_marcacion."',".$id_registro.",'Salida Almuerzo'";
              }
              else
              {
                  $parametros = $id_empleado.",NULL,'".$fecha_marcacion."',".$id_registro.",'Salida'";
              }
          }
          else
          {
              if($res["Horario"]=="MAÑANA" || $res["Horario"]=="Mañana")
              {
                  $hora_marcacion=$res["Registro Salida"];
                  $parametros = "'$id_empleado',
                       '$hora_marcacion',
                       '$fecha_marcacion',
                       '$id_registro',
                        'Salida Almuerzo'";
              }
              else
              {
                  $hora_marcacion=$res["Registro Salida"];
                  $parametros = "'$id_empleado',
                       '$hora_marcacion',
                       '$fecha_marcacion',
                       '$id_registro',
                        'Salida'";
              }
          }
          
          $marcacion->setFuncion($funcion);
          $marcacion->setParametros($parametros);
          $resultado=$marcacion->Insert();
          
        }
    
    }
    public function consulta_marcaciones(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $periodo = $_POST["periodo"];
        $dia_inicio =$_POST['dia_inicio'];
        $dia_final = $_POST['dia_final'];
        $numero_cedula = $_POST['numero_cedula'];
        $estado_registros= $_POST['estado_registros'];
        $dias = array("Do","Lu","Ma","Mi","Ju","Vi","Sa");
        $registro_reloj = new RegistroRelojEmpleadosModel();
        $where_to="";
        $columnas = "empleados.nombres_empleados,
                     empleados.numero_cedula_empleados,
                     registro_reloj_empleados.hora_marcacion_empleados,
                     registro_reloj_empleados.fecha_marcacion_empleados,
                     registro_reloj_empleados.id_registro,
                     registro_reloj_empleados.tipo_registro_empleados,
                     oficina.nombre_oficina";
        
        $tablas = "public.registro_reloj_empleados INNER JOIN public.empleados
                   ON registro_reloj_empleados.id_empleados = empleados.id_empleados
                   INNER JOIN public.oficina
                   ON oficina.id_oficina = empleados.id_oficina";
        
        
        $where    = "1=1";
       
        if ($periodo==2)
        {
            $where.= " AND registro_reloj_empleados.fecha_marcacion_empleados BETWEEN '".$dia_inicio."' AND '".$dia_final."'";
        }
        if (!(empty($numero_cedula)))
        {
            $where.= " AND empleados.numero_cedula_empleados ='".$numero_cedula."'";
        }
        if($estado_registros==2)
        {
            $where.=" AND registro_reloj_empleados.hora_marcacion_empleados IS NULL";
        }
        if($estado_registros==3)
        {
            $where.=" AND registro_reloj_empleados.hora_marcacion_empleados IS NOT NULL";
        }
        $id       = "empleados.numero_cedula_empleados,registro_reloj_empleados.fecha_marcacion_empleados, registro_reloj_empleados.hora_marcacion_empleados";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (CAST(registro_reloj_empleados.hora_marcacion_empleados AS TEXT) LIKE '".$search."%' OR CAST(registro_reloj_empleados.fecha_marcacion_empleados AS TEXT) LIKE '".$search."%' OR CAST(empleados.numero_cedula_empleados AS TEXT) LIKE '".$search."%'
                OR empleados.nombres_empleados ILIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$registro_reloj->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$registro_reloj->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_marcaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 16px;"></th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Oficina</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Empleado</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Cédula</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Hora</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Fecha</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Tipo</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Día</th>';
                
               
                
                if($id_rol==1){
                    
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
                    $html.='<td style="font-size: 15px;">'.$i.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->nombres_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->numero_cedula_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->hora_marcacion_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->fecha_marcacion_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->tipo_registro_empleados.'</td>';                    
                    $dayOfWeek = date("w", strtotime($res->fecha_marcacion_empleados));
                    $html.='<td style="font-size: 15px;">'.$dias[$dayOfWeek].'</td>';
                    
                    
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="EditarMarcaciones('.$res->id_registro.','.$res->numero_cedula_empleados.',&quot;'.$res->nombres_empleados.'&quot;,&quot;'.$res->hora_marcacion_empleados.'&quot;,&quot;'.$res->fecha_marcacion_empleados.'&quot,&quot;'.$res->tipo_registro_empleados.'&quot)"><i class="glyphicon glyphicon-edit"></i></button></span></td>';
                        
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_marcaciones("index.php", $page, $total_pages, $adjacents,"load_marcaciones").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    public function paginate_marcaciones($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
    
    public function AutocompleteCedula(){
        
        $empleados = new EmpleadosModel();
        
        if(isset($_GET['term'])){
            
            $cedula_empleado = $_GET['term'];
            
            $resultSet=$empleados->getBy("empleados.numero_cedula_empleados LIKE '$cedula_empleado%'");
            
            $respuesta = array();
            
            if(!empty($resultSet)){
                
                if(count($resultSet)>0){
                    
                    foreach ($resultSet as $res){
                        
                        $_cls_usuarios = new stdClass;
                        $_cls_usuarios->value=$res->numero_cedula_empleados;
                        $nombres= (string)$res->nombres_empleados;
                        $nombresep = explode(" ", $nombres);
                        $_cls_usuarios->label=$res->numero_cedula_empleados.' - '.$nombresep[0].' '.$nombresep[2];
                        $_cls_usuarios->nombre=$nombresep[0].' '.$nombresep[1];
                        
                        $respuesta[] = $_cls_usuarios;
                    }
                    
                    echo json_encode($respuesta);
                }
                
            }else{
                echo '[{"id":0,"value":"sin datos"}]';
            }
            
        }else{
            
            $cedula_usuarios = (isset($_POST['term']))?$_POST['term']:'';
            
            $columna = "empleados.numero_cedula_empleados,
					  empleados.nombres_empleados";
            
            $tablas = "public.empleados INNER JOIN public.estado
                       ON empleados.id_estado = estado.id_estado";
            
            $where = "empleados.numero_cedula_empleados = $cedula_usuarios AND estado.nombre_estado='ACTIVO'";
            
            $resultSet=$empleados->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
            
            $respuesta = new stdClass();
            
            if(!empty($resultSet)){
                
                $respuesta->numero_cedula_empleados = $resultSet[0]->numero_cedula_empleados;
                $respuesta->nombres_empleados = $resultSet[0]->nombres_empleados;
                
                
            }
            
            echo json_encode($respuesta);
            
        }
        
    }
    
    public function GetCedulas()
    {
        $empleados = new EmpleadosModel();
        $columna = "empleados.numero_cedula_empleados, empleados.id_oficina, oficina.nombre_oficina";
        
        $tablas = "public.empleados INNER JOIN public.estado
                   ON empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON empleados.id_oficina = oficina.id_oficina";
        
        $where = "estado.nombre_estado='ACTIVO'";
        
        $resultSet=$empleados->getCondiciones($columna,$tablas,$where,"empleados.numero_cedula_empleados");
        
        $respuesta = [];
        
        if(!empty($resultSet) && count($resultSet)){
            
            array_push($respuesta,"OK");
            
            foreach ($resultSet as $v)
            {
                array_push($respuesta,$v);
            }
            echo json_encode($respuesta);
            }else{
                array_push($respuesta, "error", "Hubo un problema obteniendo los datos");
                echo json_encode($respuesta);
        }
        
        
    }
}
?>