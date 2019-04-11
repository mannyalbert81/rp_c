<?php
class DepartamentosAdminController extends ControladorBase{
    
    public function index(){
        session_start();
        $departamentos = new DepartamentoModel();
        $estado = new EstadoModel();
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CARGOS'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones("*", $tablaest, $whereest, $idest);
        
        $tablaest1= "public.estado";
        $whereest1= "estado.tabla_estado='DEPARTAMENTOS'";
        $idest1 = "estado.id_estado";
        $resultEst1 = $estado->getCondiciones("*", $tablaest1, $whereest1, $idest1);
        
        $tabladpto ="public.departamentos INNER JOIN public.estado
                     ON departamentos.id_estado = estado.id_estado";
        $wheredpto = "estado.nombre_estado = 'ACTIVO'";
        $iddpto = "departamentos.nombre_departamento";
        $resultdpto = $departamentos->getCondiciones("*", $tabladpto, $wheredpto, $iddpto);
        $this->view_Administracion("DepartamentosAdmin",array(
            "resultEst"=> $resultEst,
            "resultEst1"=> $resultEst1,
            "resultdpto"=>$resultdpto
        ));
    }
    
    public function GetDptos()
    {
        session_start();
        $departamentos = new DepartamentoModel();
        $tabla= "public.departamentos INNER JOIN public.estado
                 ON departamentos.id_estado = estado.id_estado";
        $where= "estado.nombre_estado='ACTIVO'";
        $id = "departamentos.nombre_departamento";
        
        $resultSet = $departamentos->getCondiciones("*", $tabla, $where, $id);
        
        echo json_encode($resultSet);
    }
    
    public function AgregarDpto()
    {
        session_start();
        $departamentos = new DepartamentoModel();
        $funcion = "ins_departamentos";
        $nombre_dpto= $_POST['nombre_dpto'];
        $parametros = "'$nombre_dpto'";
        $departamentos->setFuncion($funcion);
        $departamentos->setParametros($parametros);
        $resultado=$departamentos->Insert();
        
        echo 1;
    }
    
    public function AgregarCargo()
    {
        session_start();
        
        $cargos = new CargosModel();
        $funcion = "ins_cargo_empleados";
        $nombre_cargo= $_POST['nombre_cargo'];
        $salario_cargo= $_POST['salario_cargo'];
        $id_departamento= $_POST['id_departamento'];
        $id_estado=$_POST['id_estado'];
        $parametros = "'$nombre_cargo',
                        '$id_departamento',
                        '$salario_cargo',
                        '$id_estado'";
        $cargos->setFuncion($funcion);
        $cargos->setParametros($parametros);
        $resultado=$cargos->Insert();
        
        echo 1;
    }
    
    public function EliminarDpto()
    {
        session_start();
        $departamento = new DepartamentoModel();
        $cargo = new CargosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='DEPARTAMENTOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $columnaest1 = "estado.id_estado";
        $tablaest1= "public.estado";
        $whereest1= "estado.tabla_estado='CARGOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest1 = "estado.id_estado";
        $resultEst1 = $estado->getCondiciones($columnaest1, $tablaest1, $whereest1, $idest1);
        
        $id_departamento= $_POST['id_departamento'];
        $where = "id_departamento=".$id_departamento;
        $tabla = "departamentos";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $departamento->UpdateBy($colval, $tabla, $where);
        
        $where = "id_departamento=".$id_departamento;
        $tabla = "cargos_empleados";
        $colval = "id_estado=".$resultEst1[0]->id_estado;
        $cargo->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function EliminarCargo()
    {
        session_start();
        $cargos = new CargosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CARGOS' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $id_cargo= $_POST['id_cargo'];
        $where = "id_cargo=".$id_cargo;
        $tabla = "cargos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $cargos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
    }
    
    public function RestaurarDpto()
    {
        session_start();
        $estado = new EstadoModel();
        $id_departamento = $_POST['id_departamento'];
        $departamentos = new DepartamentoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='DEPARTAMENTOS' AND estado.nombre_estado = 'ACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        
            $where = "id_departamento=".$id_departamento;
            $tabla = "departamentos";
            $colval = "id_estado=".$resultEst[0]->id_estado;
            $departamentos->UpdateBy($colval, $tabla, $where);
            
            echo 1;
        
    }
    
    public function RestaurarCargo()
    {
        session_start();
        $cargos = new CargosModel();
        $estado = new EstadoModel();
        $id_cargo= $_POST['id_cargo'];
        $id_departamento = $_POST['id_departamento'];
        $departamentos = new DepartamentoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CARGOS' AND estado.nombre_estado = 'ACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        $tablad= "public.departamentos INNER JOIN public.estado
                   ON departamentos.id_estado = estado.id_estado";
        $whered= "departamentos.id_departamento=".$id_departamento." AND estado.nombre_estado = 'ACTIVO'";
        $idd = "departamentos.id_departamento";
        $resultD = $departamentos->getCondiciones('*', $tablad, $whered, $idd);
        
        if (!(empty($resultD)))
        {
        $where = "id_cargo=".$id_cargo;
        $tabla = "cargos_empleados";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $cargos->UpdateBy($colval, $tabla, $where);
        
        echo 1;
        }
        else echo 0;
    }
    
    public function EditarDpto()
    {
        session_start();
        $departamento = new DepartamentoModel();
            
        $nombre_departamento= $_POST['nombre_departamento'];
        $nuevo_nombre= $_POST['nuevo_nombre'];
        
        $cols="departamentos.nombre_departamento";
        $whe="departamentos.nombre_departamento='".$nuevo_nombre."'";
        $id="departamentos.nombre_departamento";
        
        $resultSet=$departamento->getCondiciones($cols, "public.departamentos", $whe, $id);
        
        if (empty($resultSet))
        {
        $where = "nombre_departamento='".$nombre_departamento."'";
        $tabla = "departamentos";
        $colval = "nombre_departamento='".$nuevo_nombre."'";
        $departamento->UpdateBy($colval, $tabla, $where);
        
        echo 1;
        }
        else
        {
         echo 0;   
        }
    }
    
    public function consulta_departamentos(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        
        $departamentos = new DepartamentoModel();
        $cargos = new CargosModel();
        $estado = new EstadoModel();
        
        $tablase = "public.estado";
        $wheree= "estado.id_estado=".$id_estado;
        $ide = "estado.id_estado";
        
        $tablasc = "public.cargos_empleados INNER JOIN public.estado
                    ON cargos_empleados.id_estado = estado.id_estado 
                    INNER JOIN public.departamentos
                    ON cargos_empleados.id_departamento = departamentos.id_departamento";
        
        
        $wherec   = "estado.nombre_estado='ACTIVO'";
        
        $idc       = "cargos_empleados.nombre_cargo";
        
        
        $where_to="";
        
        $tablas = "public.departamentos INNER JOIN public.estado
                   ON departamentos.id_estado = estado.id_estado";
        
        
        $where    = "departamentos.id_estado=".$id_estado;
        
        $id       = "departamentos.nombre_departamento";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND departamentos.nombre_departamento ILIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$departamentos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            
            
            $resultSet=$departamentos->getCondiciones('*', $tablas, $where_to, $id);
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$departamentos->getCondicionesPag('*', $tablas, $where_to, $id, $limit);
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
                    $html.= "<table id='tabla_marcaciones' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                    $html.= "<thead>";
                    $html.= "<tr>";
                    $html.='<th style="text-align: left;  font-size: 16px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 16px;">Nombre</th>';
                    $html.='</tr>';
                    $html.='</thead>';
                   
                    
                    
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
                        $html.='<td style="font-size: 14px;">'.$i.'</td>';
                        $html.='<td style="font-size: 14px;">'.$res->nombre_departamento;
                        if ($res->nombre_estado == "ACTIVO")
                        {
                        $html.='<span class="pull-right"><button  type="button" class="btn btn-danger" onclick="EliminarDpto('.$res->id_departamento.')"><i class="glyphicon glyphicon-trash"></i></button></span><span class="pull-right"><button  type="button" class="btn btn-success" data-toggle="modal" data-target="#myModalEdit" onclick="EditarDpto(&quot;'.$res->nombre_departamento.'&quot;)"><i class="glyphicon glyphicon-edit"></i></button></span>';
                        }
                        else
                        {
                        $html.='<span class="pull-right"><button  type="button" class="btn btn-primary" onclick="RestaurarDpto('.$res->id_departamento.')"><i class="glyphicon glyphicon-repeat"></i></button></span>';
                        }
                        $html.='</td>';
                        
                        
                        $html.='</tr>';
                    }
                    $html.='</tbody>';
                    $html.='</table>';
                    $html.='</section></div>';
                    $html.='<div class="table-pagination pull-right">';
                    $html.=''. $this->paginate_departamentos("index.php", $page, $total_pages, $adjacents,"load_departamentos").'';
                    $html.='</div>';
                }
                
            else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay departamentos registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
        
    }
   
    public function paginate_departamentos($reload, $page, $tpages, $adjacents,$funcion='') {
        
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