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
            
            
            $where  .=" AND numero_documento_liquidacion_cabeza = ".$txtNumeroSolicitudexpedientes." ";
            
        
        }
        
        if(!empty($txtFRegistroexpedientes)){
            
            
            $where  .=" AND fecha_entrada_liquidacion_cabeza = '$txtFRegistroexpedientes' ";
            
            
        }
        
        
        if(!empty($txtFBajaexpedientes)){
            
            
            $where  .=" AND fecha_salida_liquidacion_cabeza = '$txtFBajaexpedientes' ";
            
            
        }
        
        
        if($id_estado_participes>0){
            
            
            $where  .=" AND core_estado_prestaciones.id_estado_prestaciones = '$id_estado_participes' ";
            

            
            
        }
        
        if($id_tipo_liquidación>0){
            
            
            $where  .=" AND core_tipo_prestaciones.id_tipo_prestaciones = '$id_tipo_liquidación' ";
            
            
        }
        
        if($id_entidad_patronal>0){
            
            
            $where  .=" AND core_entidad_patronal.id_entidad_patronal = '$id_entidad_patronal' ";
            
            
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
            $html.='<th colspan="2" style=" text-align: center; font-size: 11px;"></th>';
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
                
//                 $html.='<td style="font-size: 15px;"><span class="pull-right">';
//                 $html.='<button id="btndetalesoli"  name="detallesoli" class="btn btn-success" type="button"';
//                 $html.='    data-toggle="modal" data-target="#mod_detallesoli"  title="detallesoli" style="font-size:65%;">';
//                 $html.='    <i class="glyphicon glyphicon-edit"></i></button></span></td>';

                
                $html.='<td style="font-size: 15px;"><span class="pull-right">';
                $html.='<button id="btndetalesoli" value="'.$res->id_participes.'" name="detallesoli" class="btn btn-success" type="button" onclick="mostrarDatosjs(this)">';
                $html.='    <i class="glyphicon glyphicon-edit"></i></button></span></td>';
               
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->cedula_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->fecha_ingreso_participes.'</td>';
                $html.='<td colspan="2" style="text-align: left; font-size: 11px;">'.$res->nombre_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->apellido_participes.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->numero_documento_liquidacion_cabeza.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->valor_neto_pagar_liquidacion_cabeza.'</td>';
                $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->nombre_estado_prestaciones.'</td>';
                
                $html.='<td style="color:#000000;font-size:80%;"><span class="pull-right"><a href="index.php?controller=PrincipalBusquedasExpedientes&action=reporte_liquidaciones&id_participes='.$res->id_participes.'" target="_blank"><i class="glyphicon glyphicon-print"></i></a></span></td>';
                
                
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
            $html.='<h4>Aviso!!!</h4> <b>Actualmente No Existen Liquidaciones Registradas...</b>';
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
    
    
    
    
    
    public function reporte_liquidaciones(){
        
        session_start();
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
            //llenar nombres con variables que va en html de reporte
            $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
            $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
            $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
            $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
            $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
            $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
        }
        
        //NOTICE DATA
        $datos_cabecera = array();
        $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
        $datos_cabecera['FECHA'] = date('Y/m/d');
        $datos_cabecera['HORA'] = date('h:i:s');
        
        
        
        
        $participes= new ParticipesModel();
        $creditos= new CreditosModel();
        $id_participes =  (isset($_REQUEST['id_participes'])&& $_REQUEST['id_participes'] !=NULL)?$_REQUEST['id_participes']:'';
        
        
        $datos_afiliado = array();
        
        $columnas = " core_participes.id_participes, 
                      core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_participes.fecha_nacimiento_participes, 
                      core_participes.direccion_participes, 
                      core_participes.telefono_participes, 
                      core_participes.celular_participes, 
                      core_tipo_prestaciones.id_tipo_prestaciones, 
                      core_tipo_prestaciones.nombre_tipo_prestaciones, 
                      core_estado_prestaciones.id_estado_prestaciones, 
                      core_estado_prestaciones.nombre_estado_prestaciones, 
                      core_entidad_patronal.id_entidad_patronal, 
                      core_entidad_patronal.nombre_entidad_patronal, 
                      core_liquidacion_cabeza.fecha_pago_carpeta_liquidacion_cabeza, 
                      core_liquidacion_cabeza.numero_carpeta_liquidacion_cabeza, 
                      core_participes.correo_participes, 
                      core_liquidacion_cabeza.id_liquidacion_cabeza, 
                      core_liquidacion_cabeza.valor_neto_pagar_liquidacion_cabeza, 
                      core_liquidacion_cabeza.observacion_liquidacion_cabeza, 
                      core_liquidacion_cabeza.sustento_liquidacion_cabeza, 
                      core_liquidacion_cabeza.carpeta_numero_liquidacion_cabeza, 
                      core_liquidacion_cabeza.file_number_liquidacion_cabeza,
                      core_liquidacion_forma_pago.tipo_movimiento, 
                      core_liquidacion_forma_pago.nombre_banco_liquidacion_forma_pago, 
                      core_liquidacion_forma_pago.numero_cuenta_liquidacion_forma_pago, 
                      core_liquidacion_forma_pago.valor_liquidacion_forma_pago";
        
        $tablas = "   public.core_liquidacion_cabeza, 
                      public.core_participes,
                      core_liquidacion_forma_pago, 
                      public.core_tipo_prestaciones, 
                      public.core_estado_prestaciones, 
                      public.core_entidad_patronal";
        $where= "     core_liquidacion_cabeza.id_participes = core_participes.id_participes AND
                      core_liquidacion_forma_pago.id_liquidacion_cabeza = core_liquidacion_cabeza.id_liquidacion_cabeza AND 
                      core_tipo_prestaciones.id_tipo_prestaciones = core_liquidacion_cabeza.id_tipo_prestaciones AND
                      core_estado_prestaciones.id_estado_prestaciones = core_liquidacion_cabeza.id_estado_prestaciones AND
                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_participes.id_participes ='$id_participes'";
        $id="core_participes.id_participes";
        
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $datos_afiliado['APELLIDO_PARTICIPE']=$rsdatos[0]->apellido_participes;
        $datos_afiliado['NOMBRE_PARTICIPE']=$rsdatos[0]->nombre_participes;
        $datos_afiliado['CEDULA_PARTICIPE']=$rsdatos[0]->cedula_participes;
        $datos_afiliado['NOMBRE_ENTIDAD']=$rsdatos[0]->nombre_entidad_patronal;
        $datos_afiliado['OBS_LIQUIDACION']=$rsdatos[0]->observacion_liquidacion_cabeza;
        $datos_afiliado['FECHA_NACIMIENTO']=$rsdatos[0]->fecha_nacimiento_participes;
        $datos_afiliado['DIRECCION_PARTICIPE']=$rsdatos[0]->direccion_participes;
        $datos_afiliado['TELEFONO_PARTICIPE']=$rsdatos[0]->telefono_participes;
        $datos_afiliado['CELULAR_PARTICIPE']=$rsdatos[0]->celular_participes;
        $datos_afiliado['NOMBRE_TIPO']=$rsdatos[0]->nombre_tipo_prestaciones;
        $datos_afiliado['NOMBRE_ESTADO']=$rsdatos[0]->nombre_estado_prestaciones;
        $datos_afiliado['FECHA_PAGO']=$rsdatos[0]->fecha_pago_carpeta_liquidacion_cabeza;
        $datos_afiliado['NUMERO_CARPETA']=$rsdatos[0]->numero_carpeta_liquidacion_cabeza;
        $datos_afiliado['CORREO_PARTICIPES']=$rsdatos[0]->correo_participes;
        $datos_afiliado['BANCO_PARTICIPES']=$rsdatos[0]->nombre_banco_liquidacion_forma_pago;
        $datos_afiliado['NUMERO_CUENTA_PARTICIPES']=$rsdatos[0]->numero_cuenta_liquidacion_forma_pago;

        
    
 
        //////retencion detalle
       
        $columnas = " core_participes.apellido_participes, 
                      core_participes.nombre_participes, 
                      core_participes.cedula_participes, 
                      core_creditos.numero_creditos, 
                      core_creditos.plazo_creditos, 
                      core_creditos.monto_neto_entregado_creditos, 
                      core_creditos.numero_solicitud_creditos, 
                      core_tipo_creditos.id_tipo_creditos, 
                      core_tipo_creditos.nombre_tipo_creditos, 
                      core_creditos.monto_otorgado_creditos, 
                      core_estado_creditos.id_estado_creditos, 
                      core_estado_creditos.nombre_estado_creditos,
                        
                      core_creditos.fecha_concesion_creditos
";
        
        $tablas = "public.core_creditos, 
                  public.core_participes, 
                  public.core_tipo_creditos, 
                  public.core_estado_creditos";
        $where= " core_participes.id_participes = core_creditos.id_participes AND
                  core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                  core_estado_creditos.id_estado_creditos = core_creditos.id_estado_creditos AND core_participes.id_participes ='$id_participes'";
        $id="core_participes.nombre_participes";
        
        $creditos_detalle = $creditos->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        
        
        $html='';
        
        
        $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
        $html.='<tr>';
        $html.='<th>#</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Credito Número</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Valor</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Fecha</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Tipo Credito</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Estado</th>';
        $html.='<th style="text-align: center; font-size: 11px;">Garantizado por:</th>';
        $html.='</tr>';
        
        
       
        
        foreach ($creditos_detalle as $res)
        {
            
            
       
             
                $html.='<tr>';
                $html.='<td style="font-size: 11px;"align="center"></td>';
                $html.='<td style="font-size: 11px;"align="center">'.$res->numero_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.number_format($res->monto_neto_entregado_creditos, 2, ",", ".").'</td>';
                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.$res->nombre_tipo_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.$res->fecha_concesion_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;"align="right">'.$res->nombre_estado_creditos.'</td>';
                $html.='<td style="text-align: center; font-size: 11px;"align="right"></td>';
                
                
                $html.='</td>';
                $html.='</tr>';
            
            
            
            
        }
        
    
        $html.='</table>';
        
        $datos_afiliado['DETALLE_CREDITOS_CESANTES']= $html;
        
        $tipo=$rsdatos[0]->id_tipo_prestaciones;
        
        if($tipo==1){
            
            $this->verReporte("ReporteCesantia", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_afiliado'=>$datos_afiliado));
            
        }
        if($tipo==2){
            
            $this->verReporte("ReporteDesafiliacionPrestacion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_afiliado'=>$datos_afiliado));
            
        }
        
        if($tipo==4){
            
            $this->verReporte("ReporteDesembolso", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_afiliado'=>$datos_afiliado));
            
        }
          
            
            
    }
    
 
    
    
    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%y Años, %m Meses, %d Dias' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    
    
    public function mostrarDetalleSolicitud(){
        
       
        session_start();
        
        $participes = new CoreInformacionParticipesModel();
       
        
        $id_participes =  (isset($_REQUEST['id_participes'])&& $_REQUEST['id_participes'] !=NULL)?$_REQUEST['id_participes']:0;
        
       
        
        $columnas = "
                              core_participes_informacion_adicional.id_participes,
                              core_participes.apellido_participes,
                              core_participes.nombre_participes,
                              core_participes.cedula_participes,
                              core_participes.direccion_participes,
                              core_participes.telefono_participes,
                              core_participes.celular_participes,
                              core_participes.fecha_ingreso_participes,
                              core_participes.ocupacion_participes,
                              core_participes.fecha_nacimiento_participes,
                              core_estado_civil_participes.nombre_estado_civil_participes,
                              core_participes.fecha_defuncion_participes,
                              core_participes.correo_participes,
                              core_entidad_patronal.nombre_entidad_patronal,
                              core_participes.nombre_conyugue_participes,
                              core_participes.apellido_esposa_participes,
                              core_participes.cedula_conyugue_participes,
                              core_participes.numero_dependencias_participes,
                              core_participes.observacion_participes,
                              core_distritos.id_distritos,
                              core_distritos.nombre_distritos,
                              core_provincias.id_provincias,
                              core_provincias.nombre_provincias,
                              core_participes_informacion_adicional.parroquia_participes_informacion_adicional,
                              core_participes_informacion_adicional.anios_residencia_participes_informacion_adicional,
                              core_participes_informacion_adicional.nombre_propietario_participes_informacion_adicional,
                              core_participes_informacion_adicional.telefono_propietario_participes_informacion_adicional,
                              core_participes_informacion_adicional.direccion_referencia_participes_informacion_adicional,
                              core_participes_informacion_adicional.vivienda_hipotecada_participes_informacion_adicional,
                              core_participes_informacion_adicional.nombre_una_referencia_participes_informacion_adicional,
                              core_parentesco.id_parentesco,
                              core_parentesco.nombre_parentesco,
                              core_participes_informacion_adicional.telefono_una_referencia_participes_informacion_adicional,
                              core_participes_informacion_adicional.contrato_adhesion_participes_informacion_adicional,
                              core_estado_participes.nombre_estado_participes,
                              core_participes_informacion_adicional.observaciones_participes_informacion_adicional,
                             (select sum(c1.valor_personal_contribucion)
                            	from core_contribucion c1 where id_participes = '$id_participes' and id_estatus=1 limit 1
                            ) as \"total\",
                               (select sum(c1.valor_personal_contribucion)+sum(c1.valor_patronal_contribucion)
                            	from core_contribucion c1 where id_participes = '$id_participes' and id_estatus=1 limit 1
                            ) as \"totalaporte\"
                            
                                    ";
        
        $tablas   = "
                              public.core_participes,
                              public.core_participes_informacion_adicional,
                              public.core_distritos,
                              public.core_provincias,
                              public.core_entidad_patronal,
                              public.core_parentesco,
                              public.core_estado_civil_participes,
                              public.core_estado_participes
            
                              ";
        $where    = " core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
                                  core_distritos.id_distritos = core_participes_informacion_adicional.id_distritos AND
                                  core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
                                  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
                                  core_parentesco.id_parentesco = core_participes_informacion_adicional.id_parentesco
                                  AND core_estado_civil_participes.id_estado_civil_participes = core_participes.id_estado_civil_participes
                                  AND core_estado_participes.id_estado_participes = core_participes.id_estado_participes
                                  AND core_participes.id_participes = '$id_participes'
                                   ";
        $id       = "core_participes.id_participes";
        
        
        $resultRep = $participes->getCondiciones($columnas ,$tablas ,$where, $id);
        
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion, valor_patronal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participes." AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportes=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
      
        $last=sizeof($resultAportes);
        $fecha_primer=$resultAportes[0]->fecha_registro_contribucion;
        $fecha_ultimo=$resultAportes[$last-1]->fecha_registro_contribucion;
        $fecha_primer=substr($fecha_primer,0,10);
        $fecha_ultimo=substr($fecha_ultimo,0,10);
        $tiempoaporte=$this->dateDifference($fecha_primer, $fecha_ultimo);
        $last=sizeof($resultAportes);
        
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participes." AND core_contribucion.id_contribucion_tipo=1
                AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportesPersonales=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $personales=sizeof($resultAportesPersonales);
        
        
       
        $hoy=date("Y-m-d");
        
        $tiempo_edad=$this->dateDifference($resultRep[0]->fecha_nacimiento_participes, $hoy);
        
   
        
     
      
        $nombre=$resultRep[0]->nombre_participes;
        $apellido=$resultRep[0]->apellido_participes;
        $identificacion=$resultRep[0]->cedula_participes;
        $fechaingreso=$resultRep[0]->fecha_ingreso_participes;
        $cargo=$resultRep[0]->ocupacion_participes;
        $tiempoaportacion=$tiempoaporte;
        $fechadenacimiento=$resultRep[0]->fecha_nacimiento_participes;
        $numeroaportes=$personales;
        $edad=$tiempo_edad;
        $acumuladpersonales=number_format($resultRep[0]->total, 2, ",", ".");
        $sexo=$resultRep[0]->ocupacion_participes;
        $entidadpatronal=$resultRep[0]->nombre_entidad_patronal;
        $estadocivil=$resultRep[0]->nombre_estado_civil_participes;
        $añosservicio=$tiempoaporte;
        $cuentaindividual=number_format($resultRep[0]->totalaporte, 2, ",", ".");
        $creditoactivo=$resultRep[0]->ocupacion_participes;
        $observaciones=$resultRep[0]->observacion_participes;
        
        
        $columnas = " core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      core_participes.fecha_nacimiento_participes,
                      core_participes.direccion_participes,
                      core_participes.telefono_participes,
                      core_participes.celular_participes,
                      core_tipo_prestaciones.id_tipo_prestaciones,
                      core_tipo_prestaciones.nombre_tipo_prestaciones,
                      core_estado_prestaciones.id_estado_prestaciones,
                      core_estado_prestaciones.nombre_estado_prestaciones,
                      core_entidad_patronal.id_entidad_patronal,
                      core_entidad_patronal.nombre_entidad_patronal,
                      core_liquidacion_cabeza.fecha_pago_carpeta_liquidacion_cabeza,
                      core_liquidacion_cabeza.numero_carpeta_liquidacion_cabeza,
                      core_liquidacion_cabeza.numero_documento_liquidacion_cabeza,
                      core_participes.correo_participes,
                      core_liquidacion_cabeza.id_liquidacion_cabeza,
                      core_liquidacion_cabeza.valor_neto_pagar_liquidacion_cabeza,
                      core_liquidacion_cabeza.observacion_liquidacion_cabeza,
                      core_liquidacion_cabeza.sustento_liquidacion_cabeza,
                      core_liquidacion_cabeza.carpeta_numero_liquidacion_cabeza,
                      core_liquidacion_cabeza.file_number_liquidacion_cabeza,
                      core_liquidacion_forma_pago.tipo_movimiento,
                      core_liquidacion_forma_pago.nombre_banco_liquidacion_forma_pago,
                      core_liquidacion_forma_pago.numero_cuenta_liquidacion_forma_pago,
                      core_liquidacion_forma_pago.valor_liquidacion_forma_pago";
        
        $tablas = "   public.core_liquidacion_cabeza,
                      public.core_participes,
                      core_liquidacion_forma_pago,
                      public.core_tipo_prestaciones,
                      public.core_estado_prestaciones,
                      public.core_entidad_patronal";
        $where= "     core_liquidacion_cabeza.id_participes = core_participes.id_participes AND
                      core_liquidacion_forma_pago.id_liquidacion_cabeza = core_liquidacion_cabeza.id_liquidacion_cabeza AND
                      core_tipo_prestaciones.id_tipo_prestaciones = core_liquidacion_cabeza.id_tipo_prestaciones AND
                      core_estado_prestaciones.id_estado_prestaciones = core_liquidacion_cabeza.id_estado_prestaciones AND
                      core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND core_participes.id_participes ='$id_participes'";
        $id="core_participes.id_participes";
        
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        $tipoliquidacion=$rsdatos[0]->nombre_tipo_prestaciones;
        $numerosoicitud=$rsdatos[0]->numero_documento_liquidacion_cabeza;
        $numerocarpeta=$rsdatos[0]->carpeta_numero_liquidacion_cabeza;
        $banco=$rsdatos[0]->nombre_banco_liquidacion_forma_pago;
        $numerocuenta=$rsdatos[0]->numero_cuenta_liquidacion_forma_pago;
        
        
       
        
        
        $html='';
      // $entidad="Desembolso por cesantía";
        
        
        
        $html.='<table style="width:70%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;" ;><strong>Nombre del solicitante:</strong></th>';
        $html.='<th style="text-align: left; font-size: 25px;">'.$nombre.'&nbsp;'.$apellido.'</th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;" width="161px";><strong>Típo de Liquidación:</strong></th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$tipoliquidacion.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        //$html.='<br>';
        $html.='<table  style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Identificación:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$identificacion.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Fecha de Ingreso:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$fechaingreso.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Cargo:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$cargo.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;"><strong>Tiempo de Aportación:</strong></th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$tiempoaportacion.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Fecha de Nacimiento:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$fechadenacimiento.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">N°de Aportes:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$numeroaportes.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Edad:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$edad.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Acumulado de Aportes Personales:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$acumuladpersonales.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Sexo:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$sexo.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Entidad Patronal:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$entidadpatronal.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Estado Civil:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$estadocivil.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Años de Servicio:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$añosservicio.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Cuenta Individual:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$cuentaindividual.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Credito Activo:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="-"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Fecha de Cesantìa:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input  style="height:20px" type="text" class="form-control" readonly="readonly"  value="-"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Número de Solicitud:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$numerosoicitud.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Número de Carpeta:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input  style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$numerocarpeta.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Usuario:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="-"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;" width="161px";>Observaciones:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:40px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        
        $html.='<table  style="width:100%;" border="1">';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px; background-color: #A2B2D6;" width="161px";>Reteción de Garantía:</th>';
       // $html.='<th style="text-align: left; font-size: 11px;"><input style="height:40px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Retener Garaía:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Valor a Retener:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        $html.='<table  style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;" width="161px";>Observaciones:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:40px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%;" border="1">';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px; background-color: #A2B2D6;" width="161px";>Formna de Pago:</th>';
        // $html.='<th style="text-align: left; font-size: 11px;"><input style="height:40px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%; border-collapse: separate; border-spacing: 10px 5px;" >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Forma de Pago:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">&nbsp;</th>';
        $html.='<th style="text-align: left; font-size: 11px;">&nbsp;</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Banco:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$banco.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Naturaleza de la cuenta:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$numerocuenta.'"></imput></th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Número Cuenta:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input style="height:20px" type="text" class="form-control" readonly="readonly"  value="'.$numerocuenta.'"></imput></th>';
        $html.='<th style="text-align: left; font-size: 16px;">Crear Cuenta:</th>';
        $html.='<th style="text-align: left; font-size: 11px;"><input  type="button" class="btn btn-info" onclick="CrearCuentaBancos(this)" value="Crear Cuenta"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%;" border="1">';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px; background-color: #A2B2D6;" width="161px";>Requisitos:</th>';
        // $html.='<th style="text-align: left; font-size: 11px;"><input style="height:40px" type="text" class="form-control" readonly="readonly"  value="'.$observaciones.'"></imput></th>';
        $html.='</tr>';
        $html.='</table>';
        
        $html.='<table  style="width:100%;" border="1";  >';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">Requisito:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Tipode Requisito:</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">COPIA COLOR DE LA CÈDULA:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">COPIA COLOR DE LA PAPELETA DE VOTACIÓN:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">ROL DE PÁGO:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">CARTA A CAPREMCI POR DESAFILIACIÓN:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">TIEMPO DE SERVICIO DE LA EP:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">CERTIFICACIÓN BANCARIA:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">FORMULARIO DE SOLICITUD PRESTACIONES LLENO:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: left; font-size: 16px;">PLANILLA DE LUZ OPCIONAL:</th>';
        $html.='<th style="text-align: left; font-size: 16px;">Opcional</th>';
        $html.='</tr>';
        $html.='</table>';
        
        echo $html;
        
        
    }
    
    public function CrearCuentaBancos() {
        $html="hola";
        
        echo $html;
    }
    

    
}


?>