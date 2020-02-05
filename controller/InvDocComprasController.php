<?php

class InvDocComprasController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
              
        $productos= new ProductosModel();
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "InvDocCompras";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $productos->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                
                
                $this->view_Inventario("InvDocCompras",array(
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
    
    
    
    public function consulta_compras(){        session_start();
    $id_rol=$_SESSION["id_rol"];
    
    $compras= new ComprasModel();
    
    $where_to="";
    $columnas = " ccomprobantes.numero_ccomprobantes, 
                  ccomprobantes.fecha_ccomprobantes, 
                  ccomprobantes.valor_ccomprobantes, 
                  inv_documento_compras.valor_documento_compras, 
                  estado.id_estado, 
                  estado.nombre_estado, 
                  inv_documento_compras.valor_impuesto_documento_compras
                      ";
    
    $tablas = "public.inv_documento_compras, 
              public.estado, 
              public.ccomprobantes";
    
    
    $where    = "inv_documento_compras.id_estado = estado.id_estado AND
  inv_documento_compras.id_ccomprobantes = ccomprobantes.id_ccomprobantes";
    
    $id       = "ccomprobantes.fecha_ccomprobantes";
    
    
    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
    
    
    if($action == 'ajax')
    {
        
        
        if(!empty($search)){
            
            
            $where1=" AND (numero_ccomprobantes LIKE '".$search."%' )";
            
            $where_to=$where.$where1;
        }else{
            
            $where_to=$where;
            
        }
        
        $html="";
        $resultSet=$compras->getCantidad("*", $tablas, $where_to);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre pÃ¡ginas despuÃ©s de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$compras->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
            $html.= "<table id='tabla_compras' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th colspan="2" style=" text-align: center; font-size: 11px;"></th>';
            $html.='<th colspan="2" style=" text-align: center; font-size: 11px;">Fecha</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Número Comprobante</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Valor Comprobante</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Valor de la compra</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Estado</th>';
            
            
            
            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            
            $i=0;
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<tr >';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$i.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->fecha_ccomprobantes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->numero_ccomprobantes.'</td>';
                $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->valor_ccomprobantes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->valor_documento_compras.'</td>';
                $html.='<td colspan="2" style=" font-size: 11px;"align="center";>'.(int)$res->nombre_estado.'</td>';
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_compras("index.php", $page, $total_pages, $adjacents).'';
            $html.='</div>';
            
            
            
        }else{
            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente No Existen Compras..</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        die();
        
    }
    }
    
    
    
    public function paginate_compras($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_compras(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_compras(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_compras(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_compras(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_compras(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_compras($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_compras(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
   

    

    
}
?>