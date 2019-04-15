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
                    $html.='<td bgcolor="#F5F5F5" style="font-size: 16px; text-align:center;"><a href="javascript:EditAdvertencias('.$datos[0].')"><b>'.$datos[1].'<b></a></td>';
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
                   ON empleados.id_oficina = oficina.id_oficina";
        $where = "estado.nombre_estado='ACTIVO'";
        
        $id = "empleados.id_empleados";
        
        $resultEmp = $empleados->getCondiciones("*", $tablas, $where, $id);
        
        $reportearray= new ArrayObject();
        
        
        $numregistros=0;
        
        $horastrabajo=0;
        
        $numdiassintrabajo=0;
        
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
            $html.='<th style="text-align: left;  font-size: 16px;"><button type="button" class="btn btn-success" id="subir_reporte" name="subir_reporte" onclick="SubirReporte()">
					<i class="glyphicon glyphicon-upload"></i>
					</button></th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Empleado</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Oficina</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Trabajado</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Faltas(días)</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Advertencias</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Atraso</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Tiempo Extra</th>';
            $html.='<th style="text-align: left;  font-size: 16px;">Tiempo Dcto</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $i=0;
        foreach($resultEmp as $emp)
        {
        $tatraso=0;
        $textra=0;
        $tdescuento=0;
        foreach($resultSet as $res)
        {   
            $dayOfWeek = date("D", strtotime($res->fecha_marcacion_empleados));
            
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
                    $to_time = strtotime($hsal);
                    $from_time = strtotime($hent);
                    $diferenci= round((($to_time - $from_time) / 60),0, PHP_ROUND_HALF_DOWN);
                    
                    if ($diferenci>0)
                    {
                        $horastrabajo=$horastrabajo+$diferenci;
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
            $horasextra = intval(($textra / 60));
            $horasextra .= "h".$textra%60; 
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
       $html.='<td style="font-size: 15px;">'.$horastrabajo.'</td>';
       $html.='<td style="font-size: 15px;">'.$numdiassintrabajo.'</td>';
       $html.='<td style="font-size: 15px;">'.$advertencias.'</td>';
       $html.='<td style="font-size: 15px;">'.$horasatraso.'</td>';
       $html.='<td style="font-size: 15px;">'.$horasextra.'</td>';
       $html.='<td style="font-size: 15px;">'.$horasdcto.'</td>';
       $html.='</tr>';
       
       $horastrabajo=0;
       
       $numdiassintrabajo=0;
       
       $advertencias=0;
        

     }
     $html.='</tbody>';
     $html.='</table>';
     $html.='</section></div>';
     
     echo $html;
    }
    else {
        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
        $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros de reloj para el periodo actual...</b>';
        $html.='</div>';
        $html.='</div>';
    }
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
        
        $tablas = "public.empleados";
        
        $where    = "1=1 AND id_estado=13";
        
        $id       = "empleados.id_empleados";
        
        $resultSet=$marcacion->getCondiciones($columnas, $tablas, $where, $id);
        
        foreach ($registros_array as $res)
        {
          $id_empleado=0;
          foreach ($resultSet as $eid)
          {
              if($res["Cédula"]==$eid->numero_cedula_empleados)
              {
               $id_empleado=$eid->id_empleados;   
              }
          }
          $fecha_marcacion=$this->FormatoFecha($res["Fecha"]);
          if (!(array_key_exists("Registro Entrada",$res)) || empty($res["Registro Entrada"]) )
          {
              if($res["Horario"]=="MAÑANA")
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
              if($res["Horario"]=="MAÑANA")
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
              
              if($res["Horario"]=="MAÑANA")
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
              if($res["Horario"]=="MAÑANA")
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
            $where.= " AND empleados.numero_cedula_empleados =".$numero_cedula;
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
            
            $resultSet=$empleados->getBy("CAST(empleados.numero_cedula_empleados AS TEXT) LIKE '$cedula_empleado%'");
            
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