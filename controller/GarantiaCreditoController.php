<?php

class GarantiaCreditoController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function index(){
        
        
        
        session_start();
        
        $garantia_credito = new GarantiaCreditoModel();    
        $garantia= null;
        $garantia = new EstadoModel();
        $whe_garantia = "tabla_estado = 'tabla_core_creditos_garantias'";
        $result_garantia_estados = $garantia->getBy($whe_garantia);
        
        
        $resultEdit = "";
        
        $resultSet = null;
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "GarantiaCredito";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $garantia_credito->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_creditos_garantias"])   )
                {
                    
                    
                    
                    $_id_creditos_garantias = $_GET["id_creditos_garantias"];
                    $columnas = " core_creditos_garantias.id_creditos_garantias, 
                                  core_creditos_garantias.id_creditos, 
                                  core_creditos.numero_creditos, 
                                  core_creditos_garantias.id_participes, 
                                  core_participes.apellido_participes, 
                                  core_participes.nombre_participes, 
                                  core_participes.cedula_participes, 
                                  core_creditos_garantias.id_estado, 
                                  estado.nombre_estado, 
                                  core_creditos_garantias.usuario_usuarios
                                    ";
                    
                    $tablas   = " public.core_creditos_garantias, 
                                  public.core_creditos, 
                                  public.core_participes, 
                                  public.estado";
                    $where    = " core_creditos_garantias.id_participes = core_participes.id_participes AND
                                  core_creditos_garantias.id_estado = estado.id_estado AND
                                  core_creditos.id_creditos = core_creditos_garantias.id_creditos   
                                  AND core_creditos_garantias.id_creditos_garantias = '$_id_creditos_garantias'";
                    $id       = "core_creditos_garantias.id_creditos_garantias";
                    
                    $resultEdit = $garantia_credito->getCondiciones($columnas ,$tablas ,$where, $id);
                    
                    
                    
                }
                
                
                $this->view_Credito("GarantiaCredito",array(
                    "resultSet"=>$resultSet,
                    "resultEdit" =>$resultEdit,
                    "result_garantia_estados"=>$result_garantia_estados
                    
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Contable("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Garantia Credito"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    public function consultaGarantia(){
        
        session_start();
        
        $garantia = new GarantiaCreditoModel();
        
 
        $where_to="";
        $columnas = " core_creditos_garantias.id_creditos_garantias, 
                      core_creditos_garantias.id_creditos, 
                      core_creditos.numero_creditos, 
                      core_creditos_garantias.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_creditos_garantias.id_estado, 
                      estado.nombre_estado, 
                      core_creditos_garantias.usuario_usuarios, 
                      core_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos, 
                      core_tipo_creditos.codigo_tipo_creditos, 
                      core_creditos.monto_otorgado_creditos, 
                      core_creditos.saldo_actual_creditos";
        
        $tablas   = " public.core_creditos_garantias, 
                      public.core_creditos, 
                      public.core_participes, 
                      public.estado, 
                      public.core_tipo_creditos";
        $where    = " core_creditos_garantias.id_participes = core_participes.id_participes AND
                      core_creditos_garantias.id_estado = estado.id_estado AND
                      core_creditos.id_creditos = core_creditos_garantias.id_creditos AND
                      core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos
       ";
        $id       = "core_creditos_garantias.id_creditos_garantias";
        
        
        $action = (isset($_REQUEST['peticion'])&& $_REQUEST['peticion'] !=NULL)?$_REQUEST['peticion']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        if($action == 'ajax')
        {
            
            
            if(!empty($search)){
                
                
                $where1=" AND (nombre_participes LIKE '".$search."%' OR apellido_participes LIKE '".$search."%' OR cedula_participes LIKE '".$search."%' OR numero_creditos LIKE '".$search."%')";
                
                $where_to=$where.$where1;
                
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$garantia->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 10; 
            $adjacents  = 9;
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$garantia->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
            $total_pages = ceil($cantidadResult/$per_page);
            
            if($cantidadResult > 0)
            {
                
                $html.='<div class="pull-left" style="margin-left:15px;">';
                $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
                $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
                $html.='</div>';
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<section style="height:400px; overflow-y:scroll;">';
                $html.= "<table class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 15px;">Cedula</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Apellido</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Número de Credito</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Tipo Crédito</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Monto</th>';
                $html.='<th style="text-align: left;  font-size: 15px;">Saldo Actual</th>';
                
                $html.='<th style="text-align: left;  font-size: 15px;">Estado</th>';
     
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                
                $i=0;
                
                foreach ($resultSet as $res)
       
                
                
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 12px;">'.$res->cedula_participes.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->apellido_participes.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->nombre_participes.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->numero_creditos.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->nombre_tipo_creditos.'</td>';
                    $html.='<td style="font-size: 12px; style="text-align: right;">'.$res->monto_otorgado_creditos.'</td>';
                    $html.='<td style="font-size: 12px;" style="text-align: right;">'.$res->saldo_actual_creditos.'</td>';
                    $html.='<td style="font-size: 12px;">'.$res->nombre_estado.'</td>';
                    
                    $html.='<td style="font-size: 18px;">
                            <a onclick="editGarantia('.$res->id_creditos_garantias.')" href="#" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></a></td>';
                    
                    
                    $html.='</tr>';
                }
                
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate("index.php", $page, $total_pages, $adjacents,"consultaGarantia").'';
                $html.='</div>';
                
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay registrados...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            
            echo $html;
            
        }
        
        
    }
    
    
    public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
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
    
    public function editGarantia(){
        
        session_start();
        $garantia = new GarantiaCreditoModel();
        $nombre_controladores = "GarantiaCredito";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $garantia->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (!empty($resultPer))
        {
            
            
            if(isset($_POST["id_creditos_garantias"])){
                
                $id_creditos_garantias = (int)$_POST["id_creditos_garantias"];
                
                $query = "SELECT   core_creditos_garantias.id_creditos_garantias, 
                          core_creditos_garantias.id_creditos, 
                          core_creditos.numero_creditos, 
                          core_creditos_garantias.id_participes, 
                          core_participes.apellido_participes, 
                          core_participes.nombre_participes, 
                          core_participes.cedula_participes, 
                          core_creditos_garantias.id_estado, 
                          estado.nombre_estado, 
                          core_creditos_garantias.usuario_usuarios FROM public.core_creditos_garantias, 
                          public.core_creditos, 
                          public.core_participes, 
                          public.estado WHERE core_creditos_garantias.id_participes = core_participes.id_participes AND
                          core_creditos_garantias.id_estado = estado.id_estado AND
                          core_creditos.id_creditos = core_creditos_garantias.id_creditos AND core_creditos_garantias.id_creditos_garantias = $id_creditos_garantias";
                                        
                $resultado  = $garantia->enviaquery($query);
                
                echo json_encode(array('data'=>$resultado));
                
            }
            
            
        }
        else
        {
            echo "Usuario no tiene permisos-Editar";
        }
        
    }
    
    public function cargaEstadoGarantia(){
        
        $garantia = null;
        $garantia = new GarantiaCreditoModel();
        
        $query = " SELECT id_estado,nombre_estado FROM estado WHERE tabla_estado = 'tabla_core_creditos_garantias' ORDER BY nombre_estado";
        
        $resulset = $garantia->enviaquery($query);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
public function InsertaGarantia(){
    
    session_start();
    
    $garantia = new GarantiaCreditoModel();
    
    $nombre_controladores = "GarantiaCredito";
    $id_rol= $_SESSION['id_rol'];
    $resultPer = $garantia->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
    
    if (!empty($resultPer)){
      
        $_id_creditos_garantias = (isset($_POST["id_creditos_garantias"])) ? $_POST["id_creditos_garantias"] : "0";
        $_id_estado = (isset($_POST["id_estado"])) ? $_POST["id_estado"] : 0 ;
     
    $funcion = "ins_core_creditos_garantias";
    $respuesta = 0 ;
    $mensaje = "";
    
    
    if($_id_creditos_garantias == 0){
    
    
    }elseif ($_id_creditos_garantias > 0){
    
        $parametros = "'$_id_creditos_garantias','$_id_estado'";
        $garantia->setFuncion($funcion);
    $garantia->setParametros($parametros);
    $resultado = $garantia->llamafuncionPG();
    
    if(is_int((int)$resultado[0])){
    $respuesta = $resultado[0];
    $mensaje = "Periodo Actualizado Correctamente";
    }
    
    
    }
    
   
    if(is_int((int)$respuesta)){
    
    echo json_encode(array('respuesta'=>$respuesta,'mensaje'=>$mensaje));
    exit();
    }
    
    echo "Error al Ingresar Credito Garantia";
    exit();
    
    }
    else
    {
    $this->view_Inventario("Error",array(
    "resultado"=>"No tiene Permisos de Insertar Credito Garantia"
    
    ));
    
    
    }
    
    }
    
   
}
?>