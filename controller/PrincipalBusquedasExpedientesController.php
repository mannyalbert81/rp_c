<?php
class PrincipalBusquedasExpedientesController extends ControladorBase{
    
    
    
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_principal("PrincipalBusquedasExpedientes",array(
            "result" => ""
        ));
    }
    
    
    public function cargaEstadoLiquidacion(){
        
        $estadosliquidacion= new EstadosCoreLiquidacionModel();
        
        $columnas="id_estado_prestaciones, nombre_estado_prestaciones";
        $tabla = "core_estado_prestaciones";
        $where = "1=1";
        $id="nombre_estado_prestaciones";
        $resulset = $estadosliquidacion->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    public function cargaTipoLiquidacion(){
        
        $tipoliquidacion= new TipoCoreLiquidacionModel();
        
        $columnas="id_tipo_prestaciones, nombre_tipo_prestaciones";
        $tabla = "core_tipo_prestaciones";
        $where = "1=1";
        $id="nombre_tipo_prestaciones";
        $resulset = $tipoliquidacion->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    public function cargaEntidadPatronal(){
        
        $entidad= new CoreEntidadPatronalModel();
        
        $columnas="id_entidad_patronal, nombre_entidad_patronal";
        $tabla = "core_entidad_patronal";
        $where = "1=1";
        $id="nombre_entidad_patronal";
        $resulset = $entidad->getCondiciones($columnas,$tabla,$where,$id);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
    
    
 
    public function BuscarParticipe()
    {    
    session_start();
    
    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    $cedula = (isset($_REQUEST['txtcedulaexpedientes'])&& $_REQUEST['txtcedulaexpedientes'] !=NULL)?$_REQUEST['txtcedulaexpedientes']:'';
    $txtNombresexpedientes = (isset($_REQUEST['txtNombresexpedientes'])&& $_REQUEST['txtNombresexpedientes'] !=NULL)?$_REQUEST['txtNombresexpedientes']:'';
    $txtApellidosexpedientes = (isset($_REQUEST['txtApellidosexpedientes'])&& $_REQUEST['txtApellidosexpedientes'] !=NULL)?$_REQUEST['txtApellidosexpedientes']:'';
    $txtCargoexpedientes = (isset($_REQUEST['txtCargoexpedientes'])&& $_REQUEST['txtCargoexpedientes'] !=NULL)?$_REQUEST['txtCargoexpedientes']:'';
    $txtNumeroSolicitudexpedientes = (isset($_REQUEST['txtNumeroSolicitudexpedientes'])&& $_REQUEST['txtNumeroSolicitudexpedientes'] !=NULL)?$_REQUEST['txtNumeroSolicitudexpedientes']:'';
    $txtFRegistroexpedientes = (isset($_REQUEST['txtcedulaexptxtFRegistroexpedientesedientes'])&& $_REQUEST['txtFRegistroexpedientes'] !=NULL)?$_REQUEST['txtFRegistroexpedientes']:'';
    $txtFBajaexpedientes = (isset($_REQUEST['txtFBajaexpedientes'])&& $_REQUEST['txtFBajaexpedientes'] !=NULL)?$_REQUEST['txtFBajaexpedientes']:'';
    $id_estado_participes = (isset($_REQUEST['id_estado_participes'])&& $_REQUEST['id_estado_participes'] !=0)?$_REQUEST['id_estado_participes']:'0';
    $id_tipo_liquidación = (isset($_REQUEST['id_tipo_liquidación'])&& $_REQUEST['id_tipo_liquidación'] !=0)?$_REQUEST['id_tipo_liquidación']:'0';
    $id_entidad_patronal= (isset($_REQUEST['id_entidad_patronal'])&& $_REQUEST['id_entidad_patronal'] !=0)?$_REQUEST['id_entidad_patronal']:'0';
    
    
    
    
    $participes= new ParticipesModel();
    
    $where_to="";
    $columnas = " core_liquidacion_cabeza.id_liquidacion_cabeza, 
                  core_participes.id_participes, 
                  core_participes.apellido_participes, 
                  core_participes.nombre_participes, 
                  core_participes.cedula_participes, 
                  core_participes.fecha_nacimiento_participes, 
                  core_participes.fecha_ingreso_participes, 
                  core_participes.fecha_defuncion_participes, 
                  core_participes.fecha_salida_participes, 
                  core_participes.fecha_entrada_patronal_participes, 
                  core_liquidacion_cabeza.valor_neto_pagar_liquidacion_cabeza, 
                  core_liquidacion_cabeza.cantidad_aportaciones_liquidacion_cabeza, 
                  core_estatus.id_estatus, 
                  core_estatus.nombre_estatus, 
                  core_tipo_prestaciones.id_tipo_prestaciones, 
                  core_tipo_prestaciones.nombre_tipo_prestaciones, 
                  core_estado_prestaciones.id_estado_prestaciones, 
                  core_estado_prestaciones.nombre_estado_prestaciones, 
                  core_liquidacion_cabeza.sustento_liquidacion_cabeza, 
                  core_liquidacion_cabeza.carpeta_numero_liquidacion_cabeza, 
                  core_liquidacion_cabeza.file_number_liquidacion_cabeza, 
                  core_liquidacion_cabeza.fecha_entrada_carpeta_liquidacion_cabeza, 
                  core_liquidacion_cabeza.fecha_pago_carpeta_liquidacion_cabeza, 
                  core_liquidacion_cabeza.numero_documento_liquidacion_cabeza, 
                  core_liquidacion_cabeza.fecha_entrada_liquidacion_cabeza, 
                  core_liquidacion_cabeza.numero_carpeta_liquidacion_cabeza, 
                  core_liquidacion_cabeza.fecha_salida_liquidacion_cabeza, 
                  core_liquidaciones_historico.id_liquidaciones_historico, 
                  core_liquidaciones_historico.fecha_registro_liquidaciones_historico, 
                  core_liquidaciones_historico.observaciones_liquidacion_historico, 
                  core_participes.ocupacion_participes, 
                  core_entidad_patronal.id_entidad_patronal, 
                  core_entidad_patronal.nombre_entidad_patronal
        
                      ";
    
    $tablas = "   public.core_liquidacion_cabeza, 
                  public.core_participes, 
                  public.core_estatus, 
                  public.core_tipo_prestaciones, 
                  public.core_estado_prestaciones, 
                  public.core_liquidaciones_historico, 
                  public.core_entidad_patronal";
    
    
    $where    = "   core_liquidacion_cabeza.id_participes = core_participes.id_participes AND
  core_liquidacion_cabeza.id_status = core_estatus.id_estatus AND
  core_liquidacion_cabeza.id_estado_prestaciones = core_estado_prestaciones.id_estado_prestaciones AND
  core_liquidacion_cabeza.id_tipo_prestaciones = core_tipo_prestaciones.id_tipo_prestaciones AND
  core_liquidacion_cabeza.id_liquidaciones_historico = core_liquidaciones_historico.id_liquidaciones_historico AND
  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal";
    
    $id       = " core_participes.id_participes";
    
    
    
    if($action == 'ajax')
    {
        
        
       
        
        
        if(!empty($cedula)){
            
            
            $where  .=" AND cedula_participes LIKE '%".$cedula."%' ";
            
            
        }
        
        
        
        
        if(!empty($txtNombresexpedientes)){
            
            
            $where  .=" AND nombre_participes ILIKE '%".$txtNombresexpedientes."%' ";
            
            
        }
        
        
        
        if(!empty($txtApellidosexpedientes)){
            
            
            $where  .=" AND apellido_participes ILIKE '%".$txtApellidosexpedientes."%' ";
            
            
        }
        
        if(!empty($txtCargoexpedientes)){
            
            
            $where  .=" AND ocupacion_participes ILIKE '%".$txtCargoexpedientes."%' ";
            
            
        }
        
      
        
        if(!empty($txtNumeroSolicitudexpedientes)){
            
            
            $where  .=" AND numero_documento_liquidacion_cabeza LIKE '%".$txtNumeroSolicitudexpedientes."%' ";
            
            
        }
        
        if(!empty($txtFRegistroexpedientes)){
            
            
            $where  .=" AND fecha_entrada_liquidacion_cabeza = '$txtFRegistroexpedientes' ";
            
            
        }
        
        
        if(!empty($txtFBajaexpedientes)){
            
            
            $where  .=" AND fecha_salida_liquidacion_cabeza = '$txtFBajaexpedientes' ";
            
            
        }
        
        
        if($id_estado_participes>0){
            
            
            $where  .=" AND id_estado_prestaciones = '$id_estado_participes' ";
            
            
        }
        
        if($id_tipo_liquidación>0){
            
            
            $where  .=" AND id_tipo_prestaciones = '$id_tipo_liquidación' ";
            
            
        }
        
        if($id_entidad_patronal>0){
            
            
            $where  .=" AND id_entidad_patronal = '$id_entidad_patronal' ";
            
            
        }
        
        
        $where_to=$where;
        
        $html="";
        $resultSet=$participes->getCantidad("*", $tablas, $where_to);
        $cantidadResult=(int)$resultSet[0]->total;
        
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre pÃ¡ginas despuÃ©s de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet=$participes->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
            $html.= "<table id='tabla_participes' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th colspan="2" style=" text-align: center; font-size: 11px;">Tipo</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Fecha Ingreso</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Nombre</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Apellido</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">N° Documento</th>';
            $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Valora Entregar</th>';
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
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
                $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->nombre_estado_participes.'</td>';
                
               
                
                $html.='</tr>';
            }
            
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_participes("index.php", $page, $total_pages, $adjacents).'';
            $html.='</div>';
            
            
            
        }else{
            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>Actualmente No Existen Movimientos de Productos...</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        
        echo $html;
        die();
        
    }
    }
    
    
    
    public function paginate_participes($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_participe(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_participe(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_participe(1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_participe(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_participe(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_buscar_participe($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_buscar_participe(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    
    public function BuscarParticipe2()
    {
        session_start();
        $cedula=$_POST['cedula'];
        $html="";
        $participes= new ParticipesModel();
        $icon="";
        $respuesta= array();
        
        $columnas="core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
                    core_participes.fecha_nacimiento_participes,
                    core_participes.apellido_participes, core_participes.ocupacion_participes,
                    core_participes.cedula_participes, core_entidad_patronal.nombre_entidad_patronal,
                    core_participes.telefono_participes, core_participes.direccion_participes,
                    core_estado_civil_participes.nombre_estado_civil_participes, core_genero_participes.nombre_genero_participes,
                    DATE (core_participes.fecha_ingreso_participes)fecha_ingreso_participes, core_participes.celular_participes,
                    core_participes.id_participes";
        $tablas="public.core_participes INNER JOIN public.core_estado_participes
                    ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes
                    INNER JOIN core_entidad_patronal
                    ON core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                    INNER JOIN core_estado_civil_participes
                    ON core_participes.id_estado_civil_participes=core_estado_civil_participes.id_estado_civil_participes
                    INNER JOIN core_genero_participes
                    ON core_genero_participes.id_genero_participes = core_participes.id_genero_participes";
        
        $where="core_participes.cedula_participes='".$cedula."'";
        
        $id="core_participes.id_participes";
        
        $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        if(!(empty($resultSet)))
        {if($resultSet[0]->nombre_genero_participes == "HOMBRE") $icon='<i class="fa fa-male fa-3x" style="float: left;"></i>';
        else $icon='<i class="fa fa-female fa-3x" style="float: left;"></i>';
        /*
         $columnas="core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
         core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
         core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
         core_estado_creditos.nombre_estado_creditos";
         $tablas="public.core_creditos INNER JOIN public.core_tipo_creditos
         ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
         INNER JOIN public.core_estado_creditos
         ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
         $where="core_creditos.id_participes=".$resultSet[0]->id_participes." AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
         $id="core_creditos.fecha_concesion_creditos";
         
         $resultCreditos=$participes->getCondiciones($columnas, $tablas, $where, $id);
         */
        
        
        $html.='
        <div class="box box-widget widget-user-2">';
        //  if(!(empty($resultCreditos)))
        
        $html.='';
        $html.='<div class="widget-user-header bg-aqua">'
            .$icon.
            '<h3 class="widget-user-username">'.$resultSet[0]->nombre_participes.' '.$resultSet[0]->apellido_participes.'</h3>
                
         <h5 class="widget-user-desc">Estado: '.$resultSet[0]->nombre_estado_participes.'</h5>
        <h5 class="widget-user-desc">CI: '.$resultSet[0]->cedula_participes.'</h5>
            
        </div>
        <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        <table align="right" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example">
        <tr>
        <th>Cargo:</th>
        <td>'.$resultSet[0]->ocupacion_participes.'</td>
        <th>Fecha Ingreso:</th>
        <td>'.$resultSet[0]->fecha_ingreso_participes.'</td>
        </tr>
        <tr>
        <th>Estado Civil:</th>
        <td>'.$resultSet[0]->nombre_estado_civil_participes.'</td>
        <th>Fecha Nacimiento:</th>
        <td>'.$resultSet[0]->fecha_nacimiento_participes.'</td>
        </tr>
        <tr>
        <th>Sexo:</th>
        <td>'.$resultSet[0]->nombre_genero_participes.'</td>
        <th>Entidad Patronal:</th>
        <td>'.$resultSet[0]->nombre_entidad_patronal.'</td>
        </tr>
        <tr>
        <th>Telèfono:</th>
        <td>'.$resultSet[0]->telefono_participes.'</td>
        <th>Celular:</th>
        <td>'.$resultSet[0]->celular_participes.'</td>
        </tr>
        <tr >
        <th>Dirección:</th>
        <td colspan="3">'.$resultSet[0]->direccion_participes.'</td>
            
        </tr>
        </table>
        </ul>
        </div>
        </div>';
            
            array_push($respuesta, $html);
            array_push($respuesta, $resultSet[0]->id_participes);
        }
        /*
         else
         {
         $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
         $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
         $html.='<h4>Aviso!!!</h4> <b>No se ha encontrado participes con número de cédula '.$cedula.'</b>';
         $html.='</div>';
         
         array_push($respuesta, $html);
         array_push($respuesta, 0);
         }
         */
        
        
        echo json_encode($respuesta);
    }
    
    
}


?>