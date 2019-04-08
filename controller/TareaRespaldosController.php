<?php 


class TareaRespaldosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
        session_start();
        $this->view_Administracion("TareaRespaldos",array(
            "resultSet"=>""));
        /*$html="";
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $tarea_respaldos = new TareaRespaldosModel();
            
            $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
            
            $columnas = "tareas_respaldos.id_tareas_respaldos,
                     tareas_respaldos.id_base_datos,
                     base_datos.nombre_base_datos,
                     tareas_respaldos.path_tareas_respaldos,
                     tareas_respaldos.hora_tareas_respaldos";
            $tablas =   "public.tareas_respaldos INNER JOIN public.base_datos
                     ON tareas_respaldos.id_base_datos = base_datos.id_base_datos";
            $where = "1=1";
            
            $id="tareas_respaldos.id_base_datos";
            
            $resultSet = $tarea_respaldos->getCondiciones($columnas, $tablas, $where, $id);
            
            $this->view_Administracion("TareaRespaldos",array(
                "resultSet"=>$resultSet
            ));
            
            if($action == 'ajax')
            {
                
                
                echo "sssss";
                
                
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:425px; overflow-y:scroll;">';
                $html.= "<table id='tabla_tareas_respaldos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;">ID Tarea</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">ID BD</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre BD</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Ubicación Archivo</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Hora Planificada</th>';
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                foreach ($resultSet as $res)
                {
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px;">'.$res->id_tareas_respaldos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->id_base_datos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_base_datos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->path_tareas_respaldos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->hora_tareas_respaldos.'</td>';
                    $html.='</tr>';
                }
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                
                
                
                echo $html;
                die();
            }
                
            }**/
           
        }
       
    
    
        
        
        
        
        
        
        
 
    
    
    
}



/*class TareaRespaldosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        session_start();
        /*$tarea_respaldos = new TareaRespaldosModel();
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        
        if($action == 'ajax')
        {
        
        $columnas = "tareas_respaldos.id_tareas_respaldos,
                     tareas_respaldos.id_base_datos,
                     base_datos.nombre_base_datos,
                     tareas_respaldos.path_tareas_respaldos,
                     tareas_respaldos.hora_tareas_respaldos";
        $tablas =   "public.tareas_respaldos INNER JOIN public.base_datos
                     ON tareas_respaldos.id_base_datos = base_datos.id_base_datos";
        $where = "1=1";
        
        $id="tareas_respaldos.id_base_datos";
        
        $resultset = $tarea_respaldos->getCondiciones($columnas, $tablas, $where, $id);
        
        $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
        $html.='<section style="height:425px; overflow-y:scroll;">';
        $html.= "<table id='tabla_tareas_respaldos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
        $html.= "<thead>";
        $html.= "<tr>";
        $html.='<th style="text-align: left;  font-size: 12px;">ID Tarea</th>';
        $html.='<th style="text-align: left;  font-size: 12px;">ID BD</th>';
        $html.='<th style="text-align: left;  font-size: 12px;">Nombre BD</th>';
        $html.='<th style="text-align: left;  font-size: 12px;">Ubicación Archivo</th>';
        $html.='<th style="text-align: left;  font-size: 12px;">Hora Planificada</th>';
        $html.='</tr>';
        $html.='</thead>';
        $html.='<tbody>';
        
        foreach ($resultSet as $res)
        {
            $html.='<tr>';
            $html.='<td style="font-size: 11px;">'.$res->id_tareas_respaldos.'</td>';
            $html.='<td style="font-size: 11px;">'.$res->id_base_datos.'</td>';
            $html.='<td style="font-size: 11px;">'.$res->nombre_base_datos.'</td>';
            $html.='<td style="font-size: 11px;">'.$res->path_tareas_respaldos.'</td>';
            $html.='<td style="font-size: 11px;">'.$res->hora_tareas_respaldos.'</td>';
            $html.='</tr>';
        }
       
        $html.='</tbody>';
        $html.='</table>';
        $html.='</section></div>';
        
        echo $html;
        die();
        }
    }
}*/

?>