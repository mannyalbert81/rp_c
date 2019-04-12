<?php
class CausasPermisosController extends ControladorBase{
    
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CAUSA'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones("*", $tablaest, $whereest, $idest);
        
       
        $this->view_Administracion("CausasPermisos",array(
            "resultEst"=> $resultEst
        ));
    }
    
   
    public function AgregarCausa()
    {
        session_start();
        $causa = new CausasPermisosModel();
        $funcion = "ins_causa_permisos";
        $nombre_causa= $_POST['nombre_causa'];
        $parametros = "'$nombre_causa'";
        $causa->setFuncion($funcion);
        $causa->setParametros($parametros);
        $resultado=$causa->Insert();
        
        echo 1;
    }
     
    public function EliminarCausa()
    {
        session_start();
        $causa = new CausasPermisosModel();
        $estado = new EstadoModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CAUSA' AND estado.nombre_estado = 'INACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
               
        $id_causa= $_POST['id_causa'];
        $where = "id_causa=".$id_causa;
        $tabla = "causas_permisos";
        $colval = "id_estado=".$resultEst[0]->id_estado;
        $causa->UpdateBy($colval, $tabla, $where);
              
        echo 1;
    }
       
    public function RestaurarCausa()
    {
        session_start();
        $estado = new EstadoModel();
        $id_causa = $_POST['id_causa'];
        $causas = new CausasPermisosModel();
        $columnaest = "estado.id_estado";
        $tablaest= "public.estado";
        $whereest= "estado.tabla_estado='CAUSA' AND estado.nombre_estado = 'ACTIVO'";
        $idest = "estado.id_estado";
        $resultEst = $estado->getCondiciones($columnaest, $tablaest, $whereest, $idest);
        
        
            $where = "id_causa=".$id_causa;
            $tabla = "causas_permisos";
            $colval = "id_estado=".$resultEst[0]->id_estado;
            $causas->UpdateBy($colval, $tabla, $where);
            
            echo 1;
        
    }
    
    public function EditarCausa()
    {
        session_start();
        $causa = new CausasPermisosModel();
            
        $nombre_causa= $_POST['nombre_causa'];
        $nuevo_nombre= $_POST['nuevo_nombre'];
        
        $cols="causas_permisos.nombre_causa";
        $whe="causas_permisos.nombre_causa='".$nuevo_nombre."'";
        $id="causas_permisos.nombre_causa";
        
        $resultSet=$causa->getCondiciones($cols, "public.causas_permisos", $whe, $id);
        
        if (empty($resultSet))
        {
        $where = "nombre_causa='".$nombre_causa."'";
        $tabla = "causas_permisos";
        $colval = "nombre_causa='".$nuevo_nombre."'";
        $causa->UpdateBy($colval, $tabla, $where);
        
        echo 1;
        }
        else
        {
         echo 0;   
        }
    }
    
    public function consulta_causas(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $id_estado = (isset($_REQUEST['id_estado'])&& $_REQUEST['id_estado'] !=NULL)?$_REQUEST['id_estado']:'';
        
        $causas = new CausasPermisosModel();
        
        $where_to="";
        
        $tablas = "public.causas_permisos INNER JOIN public.estado
                   ON causas_permisos.id_estado = estado.id_estado";
        
        
        $where    = "causas_permisos.id_estado=".$id_estado;
        
        $id       = "causas_permisos.id_causa";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND causas_permisos.nombre_causa ILIKE '".$search."%'";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$causas->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            
            
            $resultSet=$causas->getCondiciones('*', $tablas, $where_to, $id);
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$causas->getCondicionesPag('*', $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
           
            
              
            
            if($cantidadResult>0)
            {
                
                    
                    $html.='<div class="pull-left" style="margin-left:15px;">';
                    $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                    $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                    $html.='</div>';
                    $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                    $html.='<section style="height:425px; overflow-y:scroll;">';
                    $html.= "<table id='tabla_causas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
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
                        $html.='<td style="font-size: 14px;">'.$res->nombre_causa;
                        if ($res->nombre_estado == "ACTIVO")
                        {
                        $html.='<span class="pull-right"><button  type="button" class="btn btn-danger" onclick="EliminarCausa('.$res->id_causa.')"><i class="glyphicon glyphicon-trash"></i></button></span><span class="pull-right"><button  type="button" class="btn btn-success" data-toggle="modal" data-target="#myModalEdit" onclick="EditarCausa(&quot;'.$res->nombre_causa.'&quot;)"><i class="glyphicon glyphicon-edit"></i></button></span>';
                        }
                        else
                        {
                        $html.='<span class="pull-right"><button  type="button" class="btn btn-primary" onclick="RestaurarCausa('.$res->id_causa.')"><i class="glyphicon glyphicon-repeat"></i></button></span>';
                        }
                        $html.='</td>';
                        
                        
                        $html.='</tr>';
                    }
                    $html.='</tbody>';
                    $html.='</table>';
                    $html.='</section></div>';
                    $html.='<div class="table-pagination pull-right">';
                    $html.=''. $this->paginate_causas("index.php", $page, $total_pages, $adjacents,"load_causas").'';
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
   
    public function paginate_causas($reload, $page, $tpages, $adjacents,$funcion='') {
        
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