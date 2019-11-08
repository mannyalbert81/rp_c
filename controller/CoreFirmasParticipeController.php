<?php

class CoreFirmasParticipeController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
              
        $firmas= new CoreFirmasParticipeModel();
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "CoreFirmasParticipe";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $firmas->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                $this->view_Core("CoreFirmasParticipe",array(
                    "resultSet"=>$resultSet, 
                    "resultEdit" =>$resultEdit
                    
                    
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Inventario("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a La depreciación de Activos Fijos"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    
    
    public function consulta_firmas_participes(){
        
        session_start();
        
        $id_rol=$_SESSION["id_rol"];
        
        $firmas= new CoreFirmasParticipeModel();
        
        $where_to="";
        $columnas = " core_firmas_participes.id_firmas_participes, 
                      core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_participes.celular_participes, 
                      core_participes.correo_participes, 
                      core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_firmas_participes.firma_firmas_participes, 
                      core_firmas_participes.path_archivo_firmas_participes, 
                      core_firmas_participes.creado, 
                      core_firmas_participes.modificado 
                        
                      ";
        
        $tablas = "   public.core_firmas_participes, 
                      public.core_participes, 
                      public.core_entidad_patronal";
        
        
        $where    = " core_participes.id_participes = core_firmas_participes.id_participes AND
                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal";
        
        $id       = "core_firmas_participes.id_firmas_participes";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (nombre_participes LIKE '".$search."%' OR cedula_participes LIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$firmas->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$firmas->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.= "<table id='tabla_firmas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;"></th>';
                $html.='<th style="text-align: center; font-size: 11px;"></th>';
                $html.='<th colspan="2" style="text-align: center; font-size:11px;">Nombre</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Apellido</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Cédula</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Teléfono</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Correo</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Entidad Patronal</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Creado</th>';
                $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Modificado</th>';

                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
                {
                    

                    
                    $i++;
                    $html.='<tr>';
                    $html.='<tr >';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;"><a target="_blank" href="index.php?controller=CoreFirmasParticipe&action=verDoc&documento='.urlencode($res->path_archivo_firmas_participes).' "><i class="glyphicon glyphicon-print"></i></a></td>';
                    $html.='<td colspan="1" style="text-align: left; font-size: 11px;"><img class="zoom" id="myImg"src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_firmas_participes.'&id_nombre=id_firmas_participes&tabla=core_firmas_participes&campo=firma_firmas_participes" alt="" width="80" height="60"></td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_participes.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->apellido_participes.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->cedula_participes.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->celular_participes.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->correo_participes.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_entidad_patronal.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->creado.'</td>';
                    $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->modificado.'</td>';
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_firmas("index.php", $page, $total_pages, $adjacents).'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registros ;(...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            die();
            
        }
    }
    
    
    
    public function paginate_firmas($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_firmas_participes(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_firmas_participes(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_firmas_participes(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_firmas_participes(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_firmas_participes(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_firmas_participes($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_firmas_participes(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function verDoc()
    {
        session_start();
        
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        if (isset($_SESSION['id_usuarios']) )
        {
           
            $documento = urldecode($_GET['documento']);
            $dat = base64_encode(file_get_contents($documento));
            
            
            $reporteimg= array(); 
            
            
            //die ($documento);
            
            $src = 'data:'.mime_content_type($documento).';base64,'.$dat;
            
            //echo "<center><img src=\"$src\" width=80%;></img></center>";
            
            //$src="\\192.168.1.128\capremci\INDEX\VERIFICACION FIRMAS\ENTRADA\sysadminBatch4641230719_005.png";
            
            $reporteimg['REPORTE_IMAGEN']= $src;
            
            $this->verReporte("Firmas", array('reporteimg'=>$reporteimg));
            
            
        }
    }
    
   
    
}
?>