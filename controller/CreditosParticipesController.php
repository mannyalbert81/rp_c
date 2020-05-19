<?php
class CreditosParticipesController extends ControladorBase{
    
    public function index(){
        
        session_start();
        
        if( !isset( $_SESSION['id_usuarios'] ) ){
            $this->redirect("Usuarios","sesion_caducada");
        }
        
        $id_solicitud   = isset( $_POST['id_solicitud'] ) ? $_POST['id_solicitud'] : 0;
        $cedula_participes  = isset( $_POST['cedula_participes'] ) ? $_POST['cedula_participes'] : "";
        
        $this->view_Credito( "CreditosParticipes", array( "id_solicitud" => $id_solicitud, "cedula_participes" => $cedula_participes ) );        
        
    }
    
    
    
    public function index001(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("BuscarParticipes",array(
            "result" => ""
        ));
    }
    
    public function index1()
    {
        session_start();
        
      
        if(isset($_SESSION['id_usuarios']) ){
           
            if(isset($_GET["cedula_participe"]) && isset($_GET["id_solicitud"])){
                
                
                $cedula_participe=$_GET['cedula_participe'];
                $id_solicitud=$_GET['id_solicitud'];
                
                
                $this->view_Credito("BuscarParticipes",array("result" => ""));
                
                // EXECUTO METODO InfoSolicitud DE JAVA SCRIPT BuscarParticipes
                echo '<script type="text/javascript">','InfoSolicitud("'.$cedula_participe.'", '.$id_solicitud.');','</script>';
                
            }
             
        }else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
      
    }
    
    
    public function InfoSolicitud(){
        
        session_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions(); 
        $response   = array();
        
        try {
            ob_start();
            $id_solicitud   = $_POST['id_solicitud'];
            
            $columnas = " solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos";
            
            $tablas   = "public.solicitud_prestamo INNER JOIN public.tipo_creditos
                     ON solicitud_prestamo.id_tipo_creditos=tipo_creditos.id_tipo_creditos";
            
            $where    = "solicitud_prestamo.id_solicitud_prestamo=".$id_solicitud;
            
            $resultSet=$db->getCondiciones($columnas, $tablas, $where);
            
            $data   = array();
            $data['nombre_tipo_credito_solicitud'] = $resultSet[0]->nombre_tipo_creditos;
            
            $html='<div id="info_participe_solicitud" class="row bg-teal">
                    <h3 class="titulo">Información de Solicitud</h3>
                    <div class="col-md-6 col-md-12">
                    <div class="box-footer no-padding bg-teal">
                        <div class="bio-row"><p><span class="tab">Tipo Crédito </span>: '.$data['nombre_tipo_credito_solicitud'].'</p></div>
                        <div class="bio-row"><p><span class="tab">Nombre Banco </span>: '.$resultSet[0]->nombre_banco_cuenta_bancaria.'</p></div>
                        <div class="bio-row"><p><span class="tab">Número Cuenta </span>: '.$resultSet[0]->numero_cuenta_cuenta_bancaria.'</p></div>
                    </div>
                    </div>
                    <div class="col-md-6 col-md-12">
                    <div class="box-footer no-padding bg-teal">
                        <div class="bio-row"><p><span class="tab">Destino Dinero</span>: '.$resultSet[0]->destino_dinero_datos_prestamo.'</p></div>
                        <div class="bio-row"><p><span class="tab">Tipo Cuenta </span>: '.$resultSet[0]->tipo_cuenta_cuenta_bancaria.'</p></div>
                        <div class="bio-row"><p><span class="tab">Tipo de Pago </span>: '.$resultSet[0]->tipo_pago_cuenta_bancaria.'</p></div>
                    </div>
                    </div>
                 </div>';  
            
            $salida = ob_get_clean();
            if( !empty( $salida ) ){ throw new Exception(""); }
            
            $response['html'] = $html;
            $response['data'] = $data;
            
            
        } catch (Exception $e) {
            $html = '<h3 class="titulo">DATOS NO ENCONTRADOS</h3>';
            $response['html'] = $html;
            $response['mensaje'] = "Causa problable solicitud no encoontrada";
            $response['buffer']  = error_get_last();
        }
                
        echo json_encode($response);
        
    }
    
    
    
    public function InfoSolicitud001()
    {
        session_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        
        
        // ESTE ES EL METODO PARA CARGAR EL MODAL DE INFORMACION DE LA SOLICITUD
        
        $id_solicitud= (isset($_POST['id_solicitud'])) ? $_POST['id_solicitud'] : 0;
        
       
        if($id_solicitud>0){
        
        $columnas = " solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos";
        
        $tablas   = "public.solicitud_prestamo INNER JOIN public.tipo_creditos
                     ON solicitud_prestamo.id_tipo_creditos=tipo_creditos.id_tipo_creditos";
        
        $where    = "solicitud_prestamo.id_solicitud_prestamo=".$id_solicitud;
        
        $resultSet=$db->getCondiciones($columnas, $tablas, $where);
        
        $html='<div id="info_solicitud_participe" class="small-box bg-teal">
               <div class="inner">
              <table width="100%">
              <tr>
              <td colspan="2" align="center">
                <font size="4"><b>Información de Solicitud<b></font>
              </td>
              </tr>
              <tr>
              <td width="50%">
                <font size="3" id="tipo_credito_solicitud">Tipo Crédito : '.$resultSet[0]->nombre_tipo_creditos.'</font>
              </td>
              <td width="50%">
                <font size="3">Destino Dinero : '.$resultSet[0]->destino_dinero_datos_prestamo.'</font>
              </td>
              <tr>
              <td width="50%">
                <font size="3">Nombre Banco : '.$resultSet[0]->nombre_banco_cuenta_bancaria.'</font>
              </td>
              <td width="50%">
                <font size="3">Tipo Cuenta : '.$resultSet[0]->tipo_cuenta_cuenta_bancaria.'</font>
               </td>
              <tr>
              <td width="50%">
                <font size="3">Número Cuenta : '.$resultSet[0]->numero_cuenta_cuenta_bancaria.'</font>
               </td>
              <td width="50%">
                <font size="3">Tipo de Pago: '.$resultSet[0]->tipo_pago_cuenta_bancaria.'</font>
                </td>
                </tr>
                </table>
               </div>
               </div>';
        
        echo $html;
        
        }else{
            
            
            echo "NO VINO EL ID SOLICITUD POR POST";
            
        }
        
    }
    
    public function BuscarParticipe(){
        
        session_start();
        
        $participes= new ParticipesModel();
        
        $html="";        
        $icon="";
        $response   = array();
        
        ob_start();
        
        // METODO PARA CARGAR INFORMACION BASICA DEL PARTICIPE
        
        $cedula = ( isset($_POST['cedula'])) ? $_POST['cedula'] : '';
        $id_participes = 0;
        
        $columnas=" core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
                    core_participes.apellido_participes, core_participes.ocupacion_participes,
                    core_participes.cedula_participes, core_entidad_patronal.nombre_entidad_patronal,
                    core_participes.telefono_participes, core_participes.direccion_participes,
                    core_estado_civil_participes.nombre_estado_civil_participes, core_genero_participes.nombre_genero_participes,
                    core_participes.id_participes";
        $tablas=" public.core_participes
                    INNER JOIN public.core_estado_participes ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes
                    INNER JOIN core_entidad_patronal ON core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                    INNER JOIN core_estado_civil_participes ON core_participes.id_estado_civil_participes=core_estado_civil_participes.id_estado_civil_participes
                    INNER JOIN core_genero_participes ON core_genero_participes.id_genero_participes = core_participes.id_genero_participes";
        
        $where=" core_participes.cedula_participes='$cedula'";
        
        $id="core_participes.id_participes";
        
        $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $id_participes = $resultSet[0]->id_participes;
        
        $icon = ( $resultSet[0]->nombre_genero_participes == "HOMBRE" ) ? '<i class="fa fa-male fa-3x" style="float: left;"></i>' : '<i class="fa fa-female fa-3x" style="float: left;"></i>';
        
        $html.='<div class="box box-widget widget-user-2">';
        $html.='<button class="btn btn-default pull-right" title="Simulación crédito"  onclick="SimulacionCreditoSinSolicitud()"><i class="fa fa-bank"></i></button>';
        $html.='<div class="widget-user-header bg-olive">'.$icon;
        $html.='<h3 class="widget-user-username">'.$resultSet[0]->nombre_participes.' '.$resultSet[0]->apellido_participes.'</h3>';
        $html.='<h5 class="widget-user-desc">Estado: '.$resultSet[0]->nombre_estado_participes.'</h5>';
        $html.='<h5 class="widget-user-desc">CI: '.$resultSet[0]->cedula_participes.'</h5>';
        $html.='</div>';
        $html.='<div class="box-footer no-padding">';
        $html.='<ul class="nav nav-stacked">';
        $html.='<table align="right" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example">';
        $html.='<tr>';
        $html.='<th>Cargo:</th>';
        $html.='<td>'.$resultSet[0]->ocupacion_participes.'</td>';
        $html.='<th>Entidad Patronal:</th>';
        $html.='<td>'.$resultSet[0]->nombre_entidad_patronal.'</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th>Teléfono:</th>';
        $html.='<td>'.$resultSet[0]->telefono_participes.'</td>';
        $html.='<th>Estado Civil:</th>';
        $html.='<td>'.$resultSet[0]->nombre_estado_civil_participes.'</td>';
        $html.='</tr>';
        $html.='<tr >';
        $html.='<th>Dirección:</th>';
        $html.='<td colspan="3">'.$resultSet[0]->direccion_participes.'</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</ul>';
        $html.='</div>';
        $html.='</div>';
        
        $salida = ob_get_clean();
        
        if( !empty( $salida) ){            
            
            $html ='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>No se ha encontrado participes con número de cédula '.$cedula.'</b>';
            $html.='</div>';
            
            $response['html']   = $html;
            $response['id_participes']   = 0;
            $response['estatus']   = "ERROR";
            
        }else{
            $response['html']   = $html;
            $response['id_participes']   = $id_participes;
            $response['estatus']   = "OK";
        }
        
        echo json_encode( $response );
    }
    
    
    
    
    public function BuscarParticipe001()
    {
        session_start();
       
        $html="";
        $participes= new ParticipesModel();
        $icon="";
        $respuesta= array();
        
        // METODO PARA CARGAR INFORMACION BASICA DEL PARTICIPE 
        
        $cedula=(isset($_POST['cedula'])) ? $_POST['cedula'] : '';
        
        if(!empty($cedula)){
        
        $columnas="core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
                    core_participes.apellido_participes, core_participes.ocupacion_participes,
                    core_participes.cedula_participes, core_entidad_patronal.nombre_entidad_patronal,
                    core_participes.telefono_participes, core_participes.direccion_participes,
                    core_estado_civil_participes.nombre_estado_civil_participes, core_genero_participes.nombre_genero_participes,
                    core_participes.id_participes";
        $tablas="public.core_participes INNER JOIN public.core_estado_participes
                    ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes
                    INNER JOIN core_entidad_patronal
                    ON core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                    INNER JOIN core_estado_civil_participes
                    ON core_participes.id_estado_civil_participes=core_estado_civil_participes.id_estado_civil_participes
                    INNER JOIN core_genero_participes
                    ON core_genero_participes.id_genero_participes = core_participes.id_genero_participes";
    
        $where="core_participes.cedula_participes='$cedula'";
        
        $id="core_participes.id_participes";
        
        $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        
        
        if(!(empty($resultSet)))
        {if($resultSet[0]->nombre_genero_participes == "HOMBRE") $icon='<i class="fa fa-male fa-3x" style="float: left;"></i>';
        else $icon='<i class="fa fa-female fa-3x" style="float: left;"></i>';
       
        
        $html.='
        <div class="box box-widget widget-user-2">';
         $html.='<button class="btn btn-default pull-right" title="Simulación crédito"  onclick="SimulacionCreditoSinSolicitud()"><i class="fa fa-bank"></i></button>';
        $html.='<div class="widget-user-header bg-olive">'
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
        <th>Entidad Patronal:</th>
        <td>'.$resultSet[0]->nombre_entidad_patronal.'</td>
        </tr>
        <tr>
        <th>Teléfono:</th>
        <td>'.$resultSet[0]->telefono_participes.'</td>
        <th>Estado Civil:</th>
        <td>'.$resultSet[0]->nombre_estado_civil_participes.'</td>
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
        else
        {
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>No se ha encontrado participes con número de cédula '.$cedula.'</b>';
            $html.='</div>';
            
            array_push($respuesta, $html);
            array_push($respuesta, 0);
        }
        
        
        
        echo json_encode($respuesta);
        
        }
    }
    
    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%y Años, %m Meses' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    
    
    public function AportesParticipe(){
        
        session_start();
        $id_participe=$_POST['id_participe'];
        $html="";
        $participes= new ParticipesModel();
        $total=0;
        $response   = array();
        ob_start();
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participe." AND core_contribucion.id_contribucion_tipo=1
                AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportesPersonales=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion, valor_patronal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participe." AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportes=$participes->getCondiciones($columnas, $tablas, $where, $id);
        if(!(empty($resultAportes)))
        {
            foreach($resultAportes as $res)
            {
                if($res->valor_personal_contribucion!=0)
                {
                    $total+=$res->valor_personal_contribucion;
                    
                }
                else
                {
                    $total+=$res->valor_patronal_contribucion;
                }
            }
            
            $personales=sizeof($resultAportesPersonales);
            $last=sizeof($resultAportes);
            $fecha_primer=$resultAportes[0]->fecha_registro_contribucion;
            $fecha_ultimo=$resultAportes[$last-1]->fecha_registro_contribucion;
            $fecha_primer=substr($fecha_primer,0,10);
            $fecha_ultimo=substr($fecha_ultimo,0,10);
            $tiempo=$this->dateDifference($fecha_primer, $fecha_ultimo);
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            $resultSet=$participes->getCantidad("*", $tablas, $where);
            $cantidadResult=(int)$resultSet[0]->total;
            $per_page = 20; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultAportes=$participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last=sizeof($resultAportes);
            
            $total_pages = ceil($cantidadResult/$per_page);
            
            $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Aportaciones</h3>
            <h4 class="widget-user-desc"><b>Tiempo de Aportes:</b> '.$tiempo.'</h4>
            <h4 class="widget-user-desc"><b>Número de Aportaciones Personales mensuales:</b> '.$personales.'</h4>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="10%">№</th>
                        <th width="29%">FECHA DE APORTACION</th>
                        <th width="28%">TIPO DE APORTE</th>
                        <th width="29%">TOTAL</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for($i=$last-1; $i>=0; $i--)
            {
                $index=($i+($last-1)*($page-1))+1;
                if($resultAportes[$i]->valor_personal_contribucion!=0)
                {
                    $fecha=substr($resultAportes[$i]->fecha_registro_contribucion,0,10);
                    $monto=number_format((float)$resultAportes[$i]->valor_personal_contribucion, 2, ',', '.');
                    $html.='<tr>
                                 <td bgcolor="white" width="10%"><font color="black">'.$index.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$fecha.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$resultAportes[$i]->nombre_contribucion_tipo.'</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">'.$monto.'</font></td>
                                </tr>';
                }
                else
                {
                    $fecha=substr($resultAportes[$i]->fecha_registro_contribucion,0,10);
                    $monto=number_format((float)$resultAportes[$i]->valor_patronal_contribucion, 2, ',', '.');
                    $html.='<tr>
                                 <td bgcolor="white"  width="10%"><font color="black">'.$index.'</font></td>
                                 <td bgcolor="white"  width="30%"><font color="black">'.$fecha.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$resultAportes[$i]->nombre_contribucion_tipo.'</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">'.$monto.'</font></td>
                                </tr>';
                }
                
                
            }
            $total=number_format((float)$total, 2, ',', '.');
            $html.='</table>
                   </div>
                    <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th class="text-right">Acumulado Total de Aportes: '.$total.'</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $participes->allpaginate("index.php", $page, $total_pages, $adjacents,"buscar_aportes_participe").'';
            $html.='</div>
                    </div>';
            
        }
        else
        {
            $html.='<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>El participe no tiene aportaciones</b>';
            $html.='</div>';
            
        }
        
        $salida = ob_get_clean();
        if( !empty( $salida ) ){
             echo "Error en la generacion de tabla de aportes participe";
        }else{
            $response['html']   = $html;
            echo json_encode($response);
        }
        
        
    }
    
    
    
    public function AportesParticipe001()
    {
        session_start();
        $id_participe=$_POST['id_participe'];
        $html="";
        $participes= new ParticipesModel();
        $total=0;
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participe." AND core_contribucion.id_contribucion_tipo=1 
                AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportesPersonales=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas="fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion, valor_patronal_contribucion";
        $tablas="core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where="core_contribucion.id_participes=".$id_participe." AND core_contribucion.id_estatus=1";
        $id="fecha_registro_contribucion";
        
        $resultAportes=$participes->getCondiciones($columnas, $tablas, $where, $id);
        if(!(empty($resultAportes)))
        {
            foreach($resultAportes as $res)
            {
                if($res->valor_personal_contribucion!=0)
                {
                    $total+=$res->valor_personal_contribucion;
                    
                }
                else
                {
                    $total+=$res->valor_patronal_contribucion;
                }
            }
            
            $personales=sizeof($resultAportesPersonales);
            $last=sizeof($resultAportes);
            $fecha_primer=$resultAportes[0]->fecha_registro_contribucion;
            $fecha_ultimo=$resultAportes[$last-1]->fecha_registro_contribucion;
            $fecha_primer=substr($fecha_primer,0,10);
            $fecha_ultimo=substr($fecha_ultimo,0,10);
            $tiempo=$this->dateDifference($fecha_primer, $fecha_ultimo);
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            $resultSet=$participes->getCantidad("*", $tablas, $where);
            $cantidadResult=(int)$resultSet[0]->total;
            $per_page = 20; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultAportes=$participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last=sizeof($resultAportes);
            
            $total_pages = ceil($cantidadResult/$per_page);
                        
            $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Aportaciones</h3>
            <h4 class="widget-user-desc"><b>Tiempo de Aportes:</b> '.$tiempo.'</h4>
            <h4 class="widget-user-desc"><b>Número de Aportaciones Personales mensuales:</b> '.$personales.'</h4>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="10%">№</th>
                        <th width="29%">FECHA DE APORTACION</th>
                        <th width="28%">TIPO DE APORTE</th>
                        <th width="29%">TOTAL</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
                       for($i=$last-1; $i>=0; $i--)
                       {    
                           $index=($i+($last-1)*($page-1))+1;
                           if($resultAportes[$i]->valor_personal_contribucion!=0)
                           {
                               $fecha=substr($resultAportes[$i]->fecha_registro_contribucion,0,10);
                               $monto=number_format((float)$resultAportes[$i]->valor_personal_contribucion, 2, ',', '.');
                               $html.='<tr>
                                 <td bgcolor="white" width="10%"><font color="black">'.$index.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$fecha.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$resultAportes[$i]->nombre_contribucion_tipo.'</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">'.$monto.'</font></td>
                                </tr>';
                           }
                           else
                           {
                               $fecha=substr($resultAportes[$i]->fecha_registro_contribucion,0,10);
                               $monto=number_format((float)$resultAportes[$i]->valor_patronal_contribucion, 2, ',', '.');
                               $html.='<tr>
                                 <td bgcolor="white"  width="10%"><font color="black">'.$index.'</font></td>
                                 <td bgcolor="white"  width="30%"><font color="black">'.$fecha.'</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">'.$resultAportes[$i]->nombre_contribucion_tipo.'</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">'.$monto.'</font></td>
                                </tr>';
                           }
                           
                          
                       }
                       $total=number_format((float)$total, 2, ',', '.');
                     $html.='</table>  
                   </div>
                    <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th class="text-right">Acumulado Total de Aportes: '.$total.'</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>';
                     $html.='<div class="table-pagination pull-right">';
                     $html.=''. $this->paginate_aportes("index.php", $page, $total_pages, $adjacents,$id_participe,"AportesParticipe").'';
                     $html.='</div>
                    </div>';
            
            
            echo $html;
            
        }
        else
        {
            $html.='<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>El participe no tiene aportaciones</b>';
            $html.='</div>';
            echo $html;
        }
         
        
    }
    
    public function paginate_aportes($reload, $page, $tpages, $adjacents,$id_participe,$funcion='') {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,$tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function CreditosActivosParticipe()
    {
        session_start();
        $id_participe=$_POST['id_participe'];
        $html="";
        $participes= new ParticipesModel();
        $total=0;
        
        ob_start();
        $response   = array();
        
        $columnas="core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos, 
            		core_estado_creditos.nombre_estado_creditos";
        $tablas="public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
        $where="core_creditos.id_participes=".$id_participe." AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
        $id="core_creditos.fecha_concesion_creditos";
       
        $resultCreditos=$participes->getCondiciones($columnas, $tablas, $where, $id);
        if(!(empty($resultCreditos)))
        {
                     
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            $resultSet=$participes->getCantidad("*", $tablas, $where);
            $cantidadResult=(int)$resultSet[0]->total;
            $per_page = 20; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultCreditos=$participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last=sizeof($resultCreditos);
            
            $total_pages = ceil($cantidadResult/$per_page);
            
            $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Historial Prestamos</h3>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="2%">№</th>
                        <th width="4%">№ DE PRESTAMO</th>
                        <th width="15%">FECHA DE PRESTAMO</th>
                        <th width="15%">TIPO DE PRESTAMO</th>
                        <th width="14%">MONTO</th>
                        <th width="14%">SALDO CAPITAL</th>
                        <th width="14%">SALDO INTERES</th>
                        <th width="14%">ESTADO</th>
                        <th width="4%"></th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for($i=$last-1; $i>=0; $i--)
            {
                $index=($i+($last-1)*($page-1))+1;
                $monto=number_format((float)$resultCreditos[$i]->monto_otorgado_creditos, 2, ',', '.');
                $saldo=number_format((float)$resultCreditos[$i]->saldo_actual_creditos, 2, ',', '.');
                $saldo_int=number_format((float)$resultCreditos[$i]->interes_creditos, 2, ',', '.');
                $html.='<tr>
                        <td bgcolor="white" width="2%"><font color="black">'.$index.'</font></td>
                         <td bgcolor="white" width="6.5%"><font color="black">'.$resultCreditos[$i]->numero_creditos.'</font></td>
                         <td bgcolor="white" width="15%"><font color="black">'.$resultCreditos[$i]->fecha_concesion_creditos.'</font></td>
                        <td bgcolor="white" width="15%"><font color="black">'.$resultCreditos[$i]->nombre_tipo_creditos.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$monto.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$saldo.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$saldo_int.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$resultCreditos[$i]->nombre_estado_creditos.'</font></td>
                        <td bgcolor="white" width="3.5%"><font color="black">';
                $html.='<li class="dropdown messages-menu">';
                $html.='<button type="button" class="btn bg-olive" data-toggle="dropdown">';
                $html.='<i class="fa fa-reorder"></i>';
                $html.='</button>';
                $html.='<ul class="dropdown-menu">';
                $html.='<li>';
                $html.= '<table style = "width:100%; border-collapse: collapse;" border="1">';
                $html.='<tbody>';
                $html.='<tr height = "25">';
                $html.='<td><a class="btn bg-olive" title="Pagaré" href="index.php?controller=TablaAmortizacion&action=ReportePagare&id_creditos='.$resultCreditos[$i]->id_creditos.'" role="button" target="_blank"><i class="glyphicon glyphicon-list"></i></a></font></td>';
                 $html.='</tr>';
                $html.='<tr height = "25">';
                $html.='<td><a class="btn bg-olive" title="Tabla Amortización" href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos='.$resultCreditos[$i]->id_creditos.'" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
                $html.='</tr>';
                $hoy=date("Y-m-d");
                $columnas="id_estado_tabla_amortizacion";
                $tablas="core_tabla_amortizacion INNER JOIN core_creditos
                        ON core_tabla_amortizacion.id_creditos = core_creditos.id_creditos
                        INNER JOIN core_estado_creditos
                        ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
                $where="core_tabla_amortizacion.id_creditos=".$resultCreditos[$i]->id_creditos." AND core_tabla_amortizacion.id_estatus=1 AND fecha_tabla_amortizacion BETWEEN '".$resultCreditos[$i]->fecha_concesion_creditos."' AND '".$hoy."'
                        AND nombre_estado_creditos='Activo'";
                $resultCreditosActivos=$participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
                if(!(empty($resultCreditosActivos)))
                {
                    $cuotas_pagadas=sizeof($resultCreditosActivos);
                    $mora=false;
                    foreach ($resultCreditosActivos as $res)
                    {
                        if ($res->id_estado_tabla_amortizacion!=2) $mora=true;
                    }
                    if($cuotas_pagadas>=6 && $mora==false)
                    {
                        $html.='<tr height = "25">';
                        $html.='<td><button class="btn bg-olive" title="Renovación de crédito"  onclick="RenovacionCredito()"><i class="glyphicon glyphicon-refresh"></i></button></td>';
                        $html.='</tr>';
                    }
                    
                }
                    $html.='</tbody>';
                $html.='</table>';
                $html.='</li>';
                        
                        
                        $html.='</td>
                        </tr>';
              
               
            }
            $total=number_format((float)$total, 2, ',', '.');
            $html.='</table>
                   </div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate_creditos("index.php", $page, $total_pages, $adjacents,$id_participe,"CreditosActivosParticipe").'';
            $html.='</div>
                    </div>';
        }
        else
        {
            $html.='<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>El participe no tiene creditos activos</b>';
            $html.='</div>';
        }
        
        $salida = ob_get_clean();
        if( !empty( $salida ) ){
            echo "Error en la generacion de tabla de aportes participe";
        }else{
            $response['html']   = $html;
            echo json_encode($response);
        }
        
        
    }
    
   
    
    public function paginate_creditos($reload, $page, $tpages, $adjacents,$id_participe,$funcion='') {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>1</a></li>";
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
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,$tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".$id_participe.",".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function verFechas(){
        
        $mes=date('m');
        $anio=date('Y');
        //CAMBIO TEMPORAL PARA PRUEBAS
        //$mes=10;
        
        $mes_fin=$mes-1;
        
        if ($mes_fin == 0){
            $anio_fin = $anio - 1;
            $mes_fin = 12;
            
        }else{
            $anio_fin = $anio;
            $mes_fin =  $mes_fin;
            
        }
        
        $mes_ini=$mes-3;
        if ($mes_ini < 1){
            $anio_ini = $anio - 1;
            $mes_ini += 12;
            
        }else{
            $anio_ini = $anio;
            $mes_ini =  $mes_ini;
            
        }
        
        $dia= date("d",(mktime(0,0,0,$mes_fin+1,1,$anio_fin)-1));
        $fecha_inicio=$anio_ini."-".str_pad($mes_ini,2,'0',STR_PAD_LEFT)."-01";
        $fecha_fin=$anio_fin."-".str_pad($mes_fin,2,'0',STR_PAD_LEFT)."-".$dia;
        
        echo $fecha_inicio; echo "<br>"; echo $fecha_fin;
        
    }
    
    
}


?>