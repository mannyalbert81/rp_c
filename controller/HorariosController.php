<?php
class HorariosController extends ControladorBase{
    public function index(){
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $estado = new EstadoModel();
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='HORARIOS'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones("*", $tablaest, $whereest, $idest);
        
        $tablaofic= "public.oficina";
        $whereofic= "1=1";
        $idofic = "oficina.id_oficina";
        $resultOfic = $estado->getCondiciones("*", $tablaofic, $whereofic, $idofic);
        
        $tabla= "public.grupo_empleados INNER JOIN public.estado
                 ON grupo_empleados.id_estado_grupo_empleados = estado.id_estado
                 INNER JOIN public.oficina ON oficina.id_oficina = grupo_empleados.id_oficina";
        $where= "estado.nombre_estado='ACTIVO'";
        $id = "grupo_empleados.id_grupo_empleados";
        
        $resultSet = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        $this->view_Administracion("Horarios",array(
            "resultSet"=>$resultSet,
            "resultEst"=>$resultEst,
            "resultOfic"=>$resultOfic
        ));
    }
    
    public function GetGrupos()
    {
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $tabla= "public.grupo_empleados INNER JOIN public.estado
                 ON grupo_empleados.id_estado_grupo_empleados = estado.id_estado
                 INNER JOIN public.oficina ON oficina.id_oficina = grupo_empleados.id_oficina";
        $where= "estado.nombre_estado='ACTIVO'";
        $id = "grupo_empleados.id_oficina";
        
        $resultSet = $grupo_empleados->getCondiciones("*", $tabla, $where, $id);
        
        echo json_encode($resultSet);
    }
    public function EliminarGrupo()
    {
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='GRUPO_EMPLEADOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $id_grupo = $_POST['id_grupo'];
        $where = "id_grupo_empleados=".$id_grupo;
        $tabla = "grupo_empleados";
        $colval = "id_estado_grupo_empleados=".$resultEst[0]->id_estado;
        $grupo_empleados->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function AgregarGrupo()
    {
        session_start();
        $grupo_empleados = new GrupoEmpleadosModel();
        $funcion = "ins_grupo_empleados";
        $nombre_grupo= $_POST['nombre_grupo'];
        $id_oficina = $_POST['id_oficina'];
        $parametros = "'$nombre_grupo',
                        '$id_oficina'";
        $grupo_empleados->setFuncion($funcion);
        $grupo_empleados->setParametros($parametros);
        $resultado=$grupo_empleados->Insert();
        
        $tablas =   "public.grupo_empleados";
        $where = "grupo_empleados.nombre_grupo_empleados='".$nombre_grupo."'";
        
        $id="grupo_empleados.id_grupo_empleados";
        
        $resultSet = $grupo_empleados->getCondiciones("*", $tablas, $where, $id);
        
        echo json_encode($resultSet[0]); 
    }
    
    public function AgregarHorario()
    {
        session_start();
        
        $existe = 0;
        $horario = new HorariosEmpleadosModel();
        $funcion = "ins_horario_empleados";
        $hora_entrada = $_POST['hora_entrada'];
        $hora_salida = $_POST['hora_salida'];
        $hora_salida_almuerzo = $_POST['hora_salida_almuerzo'];
        $hora_entrada_almuerzo = $_POST['hora_entrada_almuerzo'];
        $id_grupo = $_POST['id_grupo'];
        $id_estado = $_POST['id_estado_f'];
        $tiempo_gracia= $_POST['tiempo_gracia'];
        $id_oficina = $_POST['id_oficina'];
        $columnas = " horarios_empleados.hora_entrada_empleados,
                      horarios_empleados.hora_salida_almuerzo_empleados,
                      horarios_empleados.hora_entrada_almuerzo_empleados,
                      horarios_empleados.hora_salida_empleados,
                      horarios_empleados.tiempo_gracia_empleados,
                      horarios_empleados.id_grupo_empleados,
                      horarios_empleados.id_oficina,
                      estado.nombre_estado
                      ";
        
        $tablas = "public.horarios_empleados INNER JOIN public.estado
                   ON horarios_empleados.id_estado = estado.id_estado";
        
        
        $where    = "1=1";
        
        $id       = "horarios_empleados.id_horarios_empleados";
        
        $resultSet=$horario->getCondiciones($columnas, $tablas, $where, $id);
        $idactivo =$horario->getCondiciones("estado.id_estado", "public.estado", "estado.tabla_estado='HORARIOS' AND estado.nombre_estado='ACTIVO'", "estado.id_estado");
        
        foreach ($resultSet as $res)
            {
                if($res->nombre_estado == "ACTIVO" || $idactivo==$id_estado )
                {
                if ($res->hora_entrada_empleados == $hora_entrada 
                    && $res->hora_salida_almuerzo_empleados == $hora_salida_almuerzo
                    && $res->hora_entrada_almuerzo_empleados == $hora_entrada_almuerzo
                    && $res->hora_salida_empleados == $hora_salida
                    && $res->tiempo_gracia_empleados == $tiempo_gracia
                    && $res->id_grupo_empleados != $id_grupo
                    && $res->id_oficina == $id_oficina)
                {
                  $existe = 1;  
                }
                }
            }
            
        if ($existe == 0)
        {
        
        $parametros = "'$hora_entrada',
                       '$hora_salida_almuerzo',
                       '$hora_entrada_almuerzo',
                       '$hora_salida',
                       '$id_grupo',
                        '$id_oficina',
                       '$id_estado',
                        '$tiempo_gracia'";
        $horario->setFuncion($funcion);
        $horario->setParametros($parametros);
        $resultado=$horario->Insert();
        
        echo $existe;
        }
        else 
        {
         echo $existe;   
        }
    }
    
    public function EliminarHorario()
    {
        session_start();
        $horario = new HorariosEmpleadosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='HORARIOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $id_grupo = $_POST['id_grupo'];
        $where = "id_grupo_empleados=".$id_grupo;
        $tabla = "horarios_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $horario->UpdateBy($colval, $tabla, $where);
        
        echo 1;
        
        
    }
    
    public function consulta_horarios(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        $horarios = new HorariosEmpleadosModel();
        $where_to="";
        $columnas = " horarios_empleados.hora_entrada_empleados,
                      horarios_empleados.hora_salida_almuerzo_empleados,
                      horarios_empleados.hora_entrada_almuerzo_empleados,
                      horarios_empleados.hora_salida_empleados,
                      horarios_empleados.tiempo_gracia_empleados,  
                      grupo_empleados.nombre_grupo_empleados,
                      horarios_empleados.id_grupo_empleados,
                      estado.nombre_estado,
                      horarios_empleados.id_estado,
                      horarios_empleados.id_oficina,
                      oficina.nombre_oficina";
        
        $tablas = "public.horarios_empleados INNER JOIN public.grupo_empleados
                   ON grupo_empleados.id_grupo_empleados = horarios_empleados.id_grupo_empleados
                   INNER JOIN public.estado 
                   ON horarios_empleados.id_estado = estado.id_estado
                   INNER JOIN public.oficina
                   ON horarios_empleados.id_oficina = oficina.id_oficina";
        
        
        $where    = "horarios_empleados.id_estado=".$id_estado;
        
        $id       = "horarios_empleados.id_oficina";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (CAST(horarios_empleados.hora_entrada_empleados AS TEXT) LIKE '".$search."%' OR CAST(horarios_empleados.hora_salida_empleados AS TEXT) LIKE '".$search."%' OR CAST(horarios_empleados.hora_entrada_almuerzo_empleados AS TEXT) LIKE '".$search."%' OR CAST(horarios_empleados.hora_salida_almuerzo_empleados AS TEXT) LIKE '".$search."%' 
                OR grupo_empleados.nombre_grupo_empleados ILIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$horarios->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$horarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            
            if($cantidadResult>0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_horarios' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 16px;"></th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Entrada</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Salida Almuerzo</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Entrada Almuerzo</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Salida</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Tiempo Gracia</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Grupo</th>';
                $html.='<th style="text-align: left;  font-size: 16px;">Oficina</th>';
                
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
                    $html.='<td style="font-size: 15px;">'.$i.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->hora_entrada_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->hora_salida_almuerzo_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->hora_entrada_almuerzo_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->hora_salida_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->tiempo_gracia_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->nombre_grupo_empleados.'</td>';
                    $html.='<td style="font-size: 15px;">'.$res->nombre_oficina.'</td>';
                    if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-success" onclick="EditarHorarios(&quot;'.$res->hora_entrada_empleados.'&quot;,&quot;'.$res->hora_salida_almuerzo_empleados.'&quot;,&quot;'.$res->hora_entrada_almuerzo_empleados.'&quot;,&quot;'.$res->hora_salida_empleados.'&quot;,'.$res->id_grupo_empleados.','.$res->id_estado.','.$res->id_oficina.',&quot;'.$res->tiempo_gracia_empleados.'&quot;)"><i class="glyphicon glyphicon-edit"></i></button></span></td>';
                       if($res->nombre_estado=="ACTIVO")
                       {
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><button  type="button" class="btn btn-danger" onclick="EliminarHorarios('.$res->id_grupo_empleados.')"><i class="glyphicon glyphicon-trash"></i></button></span></td>';
                       }
                    }
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_horarios("index.php", $page, $total_pages, $adjacents,"load_horarios").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay horarios registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
    
    public function paginate_horarios($reload, $page, $tpages, $adjacents,$funcion='') {
        
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
}
?>