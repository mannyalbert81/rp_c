<?php
class CargarParticipesController extends ControladorBase{
    public function index(){session_start();
    $estado = new EstadoModel();
    $id_rol = $_SESSION['id_rol'];
    
    $cedula = array();
    
    $cedula_usuarios = $_SESSION['cedula_usuarios'];
    array_push($cedula, $cedula_usuarios);
    
    $this->view_Credito("CargarParticipes",array(
        "result" => "", "cedula"=>$cedula
    ));}
    
    public function index1()
    {
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        $cedula_participe=$_GET['cedula_participe'];
        $id_solicitud=$_GET['id_solicitud'];
        
        $this->view_Credito("CargarParticipes",array(
            "result" => ""
            ));
        echo '<script type="text/javascript">',
        'InfoSolicitud("'.$cedula_participe.'", '.$id_solicitud.');',
        '</script>'
        ;
    }
    
    
    
    public function InfoSolicitud()
    {
        session_start();
        $id_solicitud=$_POST['id_solicitud'];
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        
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
    }
    
    public function BuscarParticipe()
    {
        session_start();
        $cedula=$_SESSION['cedula_usuarios'];
        $html="";
        $participes= new ParticipesModel();
        $icon="";
        $respuesta= array();
        
        $mes=date('m');
        $anio=date('Y');
        $mes_fin=--$mes;
        if($mes_fin==0)
        {
            $mes_fin=12;
            $anio--;
        }
        $mes_inicio=$mes-2;
        if($mes_inicio<1)
        {
            $mes_inicio+=12;
            $anio--;
        }
        $fecha_inicio=$anio."-".$mes_inicio."-01";
        $fecha_fin=$anio."-".$mes_fin."-01";
        $saldo_credito=0;
        $saldo_cta_individual=0;
        
        $columnas="core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos";
        $tablas="public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos
                INNER JOIN core_participes
                ON core_creditos.id_participes = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula."' AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'";
        $id="core_creditos.fecha_concesion_creditos";
        $Creditos_activos=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $num_creditos=sizeof($Creditos_activos);
        $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
        $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula."' AND core_contribucion.id_estatus=1";
        $totalCtaIndividual=$participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $columnas="SUM(saldo_actual_creditos) AS total";
        $tablas="core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula."' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
        $saldo_actual_credito=$participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $columnas="valor_personal_contribucion";
        $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes=core_participes.id_participes";
        $where="cedula_participes='".$cedula."' AND id_estado_contribucion=1 AND core_contribucion.id_estatus=1 AND id_contribucion_tipo=1
    AND fecha_registro_contribucion BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
        $id="fecha_registro_contribucion";
        $aportes=$participes->getCondiciones($columnas, $tablas, $where, $id);
        $num_aporte=sizeof($aportes);
        if(!(empty($saldo_actual_credito))) $saldo_credito=$saldo_actual_credito[0]->total;
        if(!(empty($totalCtaIndividual))) $saldo_cta_individual=$totalCtaIndividual[0]->total;
        if($saldo_cta_individual=="") $saldo_cta_individual=0;
        $disponible=$saldo_cta_individual-$saldo_credito;
        
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
    
        $where="core_participes.cedula_participes='".$cedula."'";
        
        $id="core_participes.id_participes";
        
        $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas="public.core_participes";
        
        $where="core_participes.cedula_participes='".$cedula."'";
        
        $id="core_participes.id_participes";
        
        $infoParticipe=$participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $hoy=date("Y-m-d");
        
        $tiempo=$this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
        
        if(!(empty($resultSet)))
        {if($resultSet[0]->nombre_genero_participes == "HOMBRE") $icon='<i class="fa fa-male fa-3x" style="float: left;"></i>';
        else $icon='<i class="fa fa-female fa-3x" style="float: left;"></i>';
        
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
        $saldo_cta_individual=number_format((float)$saldo_cta_individual, 2, '.', '');
        $html.='
        <div class="box box-widget widget-user-2">';
        // $html.='<button class="btn btn-default pull-right" title="Simulación crédito"  onclick="SimulacionCreditoSinSolicitud()"><i class="fa fa-bank"></i></button>';
        $html.='<div class="widget-user-header bg-olive">'
            .$icon.
            '<h3 class="widget-user-username">'.$resultSet[0]->nombre_participes.' '.$resultSet[0]->apellido_participes.'</h3>
        
        <h5 class="widget-user-desc">CI: '.$resultSet[0]->cedula_participes.'</h5>
        
        </div>
        <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        <table align="right" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example">
        <tr>
        <th>Fecha de nacimiento:</th>
        <td>'.$infoParticipe[0]->fecha_nacimiento_participes.'</td>
        <th>Edad:</th>
        <td>'.$tiempo.'</td>
        </tr>
        <tr>
        <th>Cuenta Individual:</th>
        <td>'.$saldo_cta_individual.'</td>
        <th>Capital de créditos:</th>
        <td>'.$saldo_credito.'</td>
        </tr>
        <tr >
        <th>Disponible:</th>
        <td colspan="3">'.$disponible.'</td>
        </tr>
        </table>
        </ul>
        </div>
        </div>
        <div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Buscar" name="Buscar" class="btn btn-primary" onclick="SimulacionCreditosPaso1()"><i class="glyphicon glyphicon-expand"></i> SIMULAR</button>
                </div>
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
    
    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%y Años, %m Meses' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    
    
    
    public function AportesParticipe()
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
        
        $columnas="core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos, 
            		core_estado_creditos.nombre_estado_creditos";
        $tablas="public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
        $where="core_creditos.id_participes=".$id_participe." AND core_creditos.id_estatus=1";
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
            
            
            echo $html;
            
        }
        else
        {
            $html.='<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>El participe no tiene creditos activos</b>';
            $html.='</div>';
            echo $html;
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
    
    public function CreditoParticipe()
    {
        session_start();
        $creditos= new ParticipesModel();
        $cedula_participes = $_SESSION['cedula_usuarios'];// controlar los aportes hasta agosto
        //$mes=date('m');
        $mes=8;
        $anio=date('Y');
        $mes_fin=--$mes;
        if($mes_fin==0)
        {
            $mes_fin=12;
            $anio--;
        }
        $mes_inicio=$mes-2;
        if($mes_inicio<1)
        {
            $mes_inicio+=12;
            $anio--;
        }
        $fecha_inicio=$anio."-".$mes_inicio."-01";
        $fecha_fin=$anio."-".$mes_fin."-01";
        $saldo_credito=0;
        $saldo_cta_individual=0;
        
        
        $columnas="core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos";
        $tablas="public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos
                INNER JOIN core_participes
                ON core_creditos.id_participes = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula_participes."' AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'";
        $id="core_creditos.fecha_concesion_creditos";
        $Creditos_activos=$creditos->getCondiciones($columnas, $tablas, $where, $id);
        
        $num_creditos=sizeof($Creditos_activos);
        $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
        $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula_participes."' AND core_contribucion.id_estatus=1";
        $totalCtaIndividual=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $columnas="SUM(saldo_actual_creditos) AS total";
        $tablas="core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
        $where="core_participes.cedula_participes='".$cedula_participes."' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
        $saldo_actual_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $columnas="valor_personal_contribucion";
        $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes=core_participes.id_participes";
        $where="cedula_participes='".$cedula_participes."' AND id_estado_contribucion=1 AND core_contribucion.id_estatus=1 AND id_contribucion_tipo=1
    AND fecha_registro_contribucion BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
        $id="fecha_registro_contribucion";
        $aportes=$creditos->getCondiciones($columnas, $tablas, $where, $id);
        $num_aporte=sizeof($aportes);
        if(!(empty($saldo_actual_credito))) $saldo_credito=$saldo_actual_credito[0]->total;
        if(!(empty($totalCtaIndividual))) $saldo_cta_individual=$totalCtaIndividual[0]->total;
        if($saldo_cta_individual=="") $saldo_cta_individual=0;
        $disponible=$saldo_cta_individual-$saldo_credito;
        
        $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas="public.core_participes";
        
        $where="core_participes.cedula_participes='".$cedula_participes."'";
        
        $id="core_participes.id_participes";
        
        $infoParticipe=$creditos->getCondiciones($columnas, $tablas, $where, $id);
        
        $hoy=date("Y-m-d");
        
        $tiempo=$this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
        $dias_hasta=$this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
        $dias_75=365*75;
        $diferencia_dias=$dias_75-$dias_hasta;
        $diferencia_dias=$diferencia_dias/30;
        $diferencia_dias=floor($diferencia_dias * 1) / 1;
        $edad=explode(",",$tiempo);
        $edad=$edad[0];
        $edad=explode(" ", $edad);
        $edad=$edad[0];
        $saldo_cta_individual=number_format((float)$saldo_cta_individual, 2, '.', '');
        if($disponible>150 && $edad<75 && $num_aporte==3) $solicitud="bg-olive";
        else $solicitud="bg-red";
        $html='<div id="disponible_participe" class="small-box '.$solicitud.'">
   <div class="inner">
   <table width="100%">
   <td>
    <table>
    <tr>
   <td width="50%"><font size="3" id="nombre_participe_credito">'.$infoParticipe[0]->nombre_participes.' '.$infoParticipe[0]->apellido_participes.'&nbsp</font></td>
   <td id="cedula_credito"><font size="3"> Cédula : '.$infoParticipe[0]->cedula_participes.'</font></td>
    </tr>
    <tr>
    <td colspan="2"><font size="3">Fecha de nacimiento : '.$infoParticipe[0]->fecha_nacimiento_participes.'</font></td>
    </tr>
    <tr>
    <td colspan="2"><font size="3">Edad : '.$tiempo.'</font></td>
    </tr>
    <tr>
    <td ><font size="3" id="monto_disponible"> Cta Individual : '.$saldo_cta_individual.'</font></td>
    </tr>
    <tr>
    <td ><font size="3"> Capital de créditos : '.$saldo_credito.'</font></td>
    </tr>
    <tr>
    <td ><font size="3" id="monto_disponible1"> Disponible : '.$disponible.'</font></td>
    </tr>';
        if($num_aporte<3)$html.='<td colspan="2" ><font size="3" id="aportes_participes">El participe tiene '.$num_aporte.' de los 3 últimos aportes pagados</font></td>';
        $html.='</td>
    </table>
    <td width="50%">
    <div id="info_garante"></div>
    </td>
    </tr>
    </table>
    <div id="info_credito_renovar"></div>
   </div>
   </div>';
        echo $html;
    }
    
    public function dateDifference1($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    

    
    public function IngresarSiCuotas(){
        
        $participes = new ParticipesModel();
        $cedula = $_GET['cedula'];
        
        
        
        $col1 = " id_participes,cedula_participes, nombre_participes, apellido_participes ";
        $tab1 = " public.core_participes";
        $whe1 = " id_estatus = 1
        AND cedula_participes = '$cedula'";
        
        $rsConsulta1 = $participes->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        $id_participes = $rsConsulta1[0]->id_participes;
        $nombre_participes =  $rsConsulta1[0]->nombre_participes;
        $cedula_participes  = $rsConsulta1[0]->cedula_participes;
        
        //buscra creditos
        $col2 = " aa.id_creditos,aa.monto_otorgado_creditos,aa.numero_creditos ";
        $tab2 = "core_creditos aa
        INNER JOIN core_estado_creditos bb ON bb.id_estado_creditos = aa.id_estado_creditos";
        $whe2 = " aa.id_estatus = 1
        AND aa.id_participes = $id_participes
        AND upper(bb.nombre_estado_creditos) = 'ACTIVO'";
        $id2  = " aa.monto_otorgado_creditos ";
        
        $rsConsulta2 = $participes->getCondicionesDesc($col2, $tab2, $whe2, $id2);
        $id_creditos = $rsConsulta2[0]->id_creditos; //con eto se toma el credito con mayor valor
        $numero_creditos = $rsConsulta2[0]->numero_creditos; //aqui va el cambio
        $pdf_registro_tres  = 'null';
        $id_estado_registro_tres_cuotas = '1'; //aqui va el cambio
        
        //viene insertado en la tabla
        $funcion = "ins_registro_tres_cuotas";
        $parametros = "$id_participes,'$nombre_participes','$cedula_participes',$id_creditos,'$numero_creditos',$pdf_registro_tres, $id_estado_registro_tres_cuotas";
        $sqCuotas   = $participes->getconsultaPG($funcion, $parametros);
        
        $resultado = $participes->llamarconsultaPG($sqCuotas);
        
        /*echo $sqCuotas,'**********************';
        
        var_dump(error_get_last()); die();
        var_dump(pg_last_error()); die();*/
        
        //aqui seteas valores del pdf que vas a guardar
        $nombre_pdf = "pdf".$cedula_participes.date('Ymd');
        $ubicacion  = "DOCUMENTOS_GENERADOS/pdf_cuotas_creditos/";
        $pdfReporte = $ubicacion.$nombre_pdf. ".PDF";
        
        
        
        // aqui haces la consulta para mostrar en el reporte
        
        $datosReporte = array();
        
        $fechaactual = getdate();
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        
        $columnas = " registro_tres_cuotas.id_registro_tres_cuotas,
                      core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      registro_tres_cuotas.nombre_participes,
                      registro_tres_cuotas.cedula_participes,
                      core_creditos.id_creditos,
                      core_creditos.numero_creditos,
                      registro_tres_cuotas.numero_creditos,
                      registro_tres_cuotas.pdf_registro_tres_cuotas,
                      registro_tres_cuotas.creado,
                      registro_tres_cuotas.modificado,
                      core_tipo_creditos.id_tipo_creditos,
                      core_tipo_creditos.nombre_tipo_creditos";
        
        $tablas = "public.registro_tres_cuotas,
                  public.core_participes,
                  public.core_creditos,
                  public.core_tipo_creditos";
        $where= "registro_tres_cuotas.id_participes = core_participes.id_participes AND
                 core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                 registro_tres_cuotas.id_creditos = core_creditos.id_creditos AND registro_tres_cuotas.cedula_participes = '$cedula'";
        $id="core_participes.id_participes";
        
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        
        $datosReporte['FECHA']=$fechaactual;
        $datosReporte['NUMEROCREDITO']=$rsdatos[0]->numero_creditos;
        $datosReporte['NOMBRE']=$rsdatos[0]->nombre_participes;
        $datosReporte['APELLIDO']=$rsdatos[0]->apellido_participes;
        $datosReporte['CEDULA']=$rsdatos[0]->cedula_participes;
        $datosReporte['NOMBRETIPOCREDITOS']=$rsdatos[0]->nombre_tipo_creditos;
        
        // revisate la parte de retencion controller ahi esta lo que toca hacer
        // buscas el pdf creado y le conviertes en bytea
        //crea y descarga
        $this->verReporte("PDFCUOTAS", array('datosReporte'=>$datosReporte,'nombreReporte'=>$pdfReporte ));
        
        //aqui tomas el pdf creado
        $data = file_get_contents($pdfReporte);
        $byteaPdf = pg_escape_bytea($data);
        
        
        //actualizas la base
        $colPdf = " pdf_registro_tres_cuotas = '$byteaPdf', acepto_participe_segunda_etapa = 'TRUE'";
        $tabPdf = " registro_tres_cuotas";
        $whePdf = " id_participes = $id_participes AND id_creditos = $id_creditos";
        
        $actualizado = $participes->ActualizarBy($colPdf, $tabPdf, $whePdf);
        
        
        //abrir en linea
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=documento.pdf");
        readfile($pdfReporte);
        
        
    }
    
    
    public function  propaganda_diferir_cuotas(){
        
        $registro = new RegistroModel();
        $cedula = $_GET['cedula'];
        
        if(isset( $_GET['cedula']) && !empty($_GET['cedula']))
        {
            $callBack = $_GET['jsoncallback'];
            $columnas_1="id_registro_tres_cuotas,cedula_participes, numero_creditos, pdf_registro_tres_cuotas, mensaje_modal, procesada_tabla_registro_tres_cuotas, puede_diferir_segunda_etapa, acepto_participe_segunda_etapa";
            $tablas_1="public.registro_tres_cuotas";
            $where_1="cedula_participes = '$cedula'";
            $id_1= "registro_tres_cuotas.id_registro_tres_cuotas";
            $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
            
            if( empty($resultUsu) ){
                //aqui imprimir modal 
                $respuesta	= json_encode( array('respuesta'=>"NO") );
            }else{
                
                $puede_diferir_segunda_etapa = $resultUsu[0]->puede_diferir_segunda_etapa;
                $mensaje_modal = $resultUsu[0]->mensaje_modal;
                $acepto_participe_segunda_etapa = $resultUsu[0]->acepto_participe_segunda_etapa;
                
                
                if($acepto_participe_segunda_etapa =="f"){
                
                if($puede_diferir_segunda_etapa=="t"){
           
                   
                    if( !empty( $mensaje_modal ) ){
                        $respuesta	= json_encode( array('respuesta'=>"SI",'mensaje_modal' => $mensaje_modal, 'puede_diferir'=>'SI') );
                       
                    }else{
                        $respuesta	= json_encode( array('respuesta'=>"NO") );
                    }
                    
                }else{
                    
                    if( !empty( $mensaje_modal ) ){
                        $respuesta	= json_encode( array('respuesta'=>"SI",'mensaje_modal' => $mensaje_modal, 'puede_diferir'=>'NO') );
                        
                    }else{
                        $respuesta	= json_encode( array('respuesta'=>"NO") );
                    }
                    
                    
                }
                
                
                }else{
                    
                    $respuesta	= json_encode( array('respuesta'=>"NONO") );
                    
                    
                }
               
                
                
                
                
                
                
                
            }                      
            
            echo $callBack."(".$respuesta.");";
            
        }
        
    }
    
    
    public function validar_diferidos()
    {
        $registro = new RegistroModel();
        $cedula = $_GET['cedula'];
        
        if(isset( $_GET['cedula']) && !empty($_GET['cedula']))
        {
            $resp   = array();
            $callBack = $_GET['jsoncallback'];
            $columnas_1=" c.id_creditos, p.id_participes";
            $tablas_1="core_creditos c
            inner join core_participes p on p.id_participes =c.id_participes";
            $where_1="p.cedula_participes = '$cedula' and p.id_estatus =1 and c.id_estatus =1 and c.id_estado_creditos =4";
            $id_1= "c.id_creditos";
            $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
            
            if( !empty( $resultUsu ) )
            {
                $columnas_1="id_registro_tres_cuotas,cedula_participes, numero_creditos, pdf_registro_tres_cuotas, mensaje_modal, procesada_tabla_registro_tres_cuotas, puede_diferir_segunda_etapa, acepto_participe_segunda_etapa";
                $tablas_1="public.registro_tres_cuotas";
                $where_1="cedula_participes = '$cedula'";
                $id_1= "registro_tres_cuotas.id_registro_tres_cuotas ";
                $resultCuotas=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
                
                if( empty( $resultCuotas ) )
                {
                    $resp['estatus'] = "OK";
                    $resp['modal'] = "SI";
                }else
                {
                    $columnas_1="MAX (id_registro_tres_cuotas)";
                    $tablas_1="public.registro_tres_cuotas";
                    $where_1="cedula_participes = '$cedula' AND id_estado_registro_tres_cuotas = 3";
                    $id_1= "registro_tres_cuotas.id_registro_tres_cuotas ";
                    $resultCuotas2 =$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
                    
                    if( !empty( $resultCuotas2 ) ){
                        $resp['estatus'] = "OK";
                        $resp['modal'] = "SI";
                    }else
                    {
                        $resp['estatus'] = "OK";
                        $resp['modal'] = "NO";
                    }                    
                    
                }
                
            }else
            {
                $resp['estatus'] = "NO";
                $resp['modal'] = "NO";
            }
            
        }else
        {
            $resp['estatus'] = "ERROR";
            $resp['modal'] = "NO";
        }
        
        $respuesta	= json_encode( $resp );
        echo $callBack."(".$respuesta.");";
    }
    
    public function validar_representantes()
    
    {
        $registro = new RegistroModel();
        $cedula = $_GET['cedula'];
        
       // echo ("llego aqui");
        
        
        if(isset( $_GET['cedula']) && !empty($_GET['cedula']))
        {
            $resp   = array();
            $callBack = $_GET['jsoncallback'];
           
            $columnas_1="p.id_padron_electoral_representantes, p.id_representante";
            $tablas_1="padron_electoral_representantes p inner join core_participes a on p.id_representante = a.id_participes";
            $where_1="a.cedula_participes='$cedula' and p.acepto_representante_padron_electoral_representantes=0";
            $id_1= "p.id_padron_electoral_representantes";
            $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
            
            if( !empty( $resultUsu ) )
            {
               // $resp['id_representante'] = $resultUsu->id_padron_electoral_representantes;
                $resp['modal'] = "SI";
              
            }else
            {
               // $resp['id_representante'] =0;
                $resp['modal'] = "NO";
            }
            
        }else
        {
           // $resp['id_representante'] =0;
            $resp['modal'] = "NO";
        }
        
        $respuesta	= json_encode( $resp );
        echo $callBack."(".$respuesta.");";
    }
    

    public function actualizar_candidatos()
    {
        $registro = new PagosModel();
        
        $cedula = $_POST['cedula'];
        $estado = $_POST['estado'];
        $callBack = $_POST['jsoncallback'];
        
        $columnas_1="p.id_padron_electoral_representantes, p.id_representante";
        $tablas_1="padron_electoral_representantes p inner join core_participes a on p.id_representante = a.id_participes";
        $where_1="a.cedula_participes='$cedula' and p.acepto_representante_padron_electoral_representantes=0";
        $id_1= "p.id_padron_electoral_representantes";
        $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
        
        //echo ($cedula);
        
        if( !empty( $resultUsu ) )
        {
            $_id= $resultUsu[0]->id_padron_electoral_representantes;
            $val    = " acepto_representante_padron_electoral_representantes= '$estado'";
            $tab    = " padron_electoral_representantes ";
            $whe    = " id_padron_electoral_representantes = '$_id'";
            
            $resultado = $registro->ActualizarBy($val, $tab, $whe);
            
        }
        
        if( !empty( pg_last_error() ) ){
            throw new Exception("Actualizacion No realizada");
        }
        
        $resp = array();
        $resp['estatus']   = "OK";
        $resp['mensaje']   = " Filas Actualizadas (".$resultado.")";
        
        echo json_encode( $resp );
        //echo $callBack."(".$respuesta.");";
    }
    
    public function validar_suplentes()
    
    {
        $registro = new RegistroModel();
        $cedula = $_GET['cedula'];
        
        // echo ("llego aqui");
        
        
        if(isset( $_GET['cedula']) && !empty($_GET['cedula']))
        {
            $resp   = array();
            $callBack = $_GET['jsoncallback'];
            
            $columnas_1="p.id_padron_electoral_representantes, p.id_suplente";
            $tablas_1="padron_electoral_representantes p inner join core_participes a on p.id_suplente = a.id_participes";
            $where_1="a.cedula_participes='$cedula' and p.acepto_suplente_padron_electoral_representantes=0";
            $id_1= "p.id_padron_electoral_representantes";
            $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
            
            if( !empty( $resultUsu ) )
            {
                // $resp['id_representante'] = $resultUsu->id_padron_electoral_representantes;
                $resp['modal'] = "SI";
                
            }else
            {
                // $resp['id_representante'] =0;
                $resp['modal'] = "NO";
            }
            
        }else
        {
            // $resp['id_representante'] =0;
            $resp['modal'] = "NO";
        }
        
        $respuesta	= json_encode( $resp );
        echo $callBack."(".$respuesta.");";
    }
    
    public function actualizar_suplentes()
    {
        
        $registro = new PagosModel();
        
        $cedula = $_POST['cedula'];
        $estado = $_POST['estado'];
        $callBack = $_POST['jsoncallback'];
        
        $columnas_1="p.id_padron_electoral_representantes, p.id_suplente";
        $tablas_1="padron_electoral_representantes p inner join core_participes a on p.id_suplente = a.id_participes";
        $where_1="a.cedula_participes='$cedula' and p.acepto_suplente_padron_electoral_representantes=0";
        $id_1= "p.id_padron_electoral_representantes";
        $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
        
        //echo ($cedula);
        
        if( !empty( $resultUsu ) )
        {
            $_id= $resultUsu[0]->id_padron_electoral_representantes;
            $val    = " acepto_suplente_padron_electoral_representantes= '$estado'";
            $tab    = " padron_electoral_representantes ";
            $whe    = " id_padron_electoral_representantes = '$_id'";
            
            $resultado = $registro->ActualizarBy($val, $tab, $whe);
            
        }
        
        if( !empty( pg_last_error() ) ){
            throw new Exception("Actualizacion No realizada");
        }
        
        $resp = array();
        $resp['estatus']   = "OK";
        $resp['mensaje']   = " Filas Actualizadas (".$resultado.")";
        
        echo json_encode( $resp );
        //echo $callBack."(".$respuesta.");";
    }
    
    
    public function  propaganda_diferir_cuotas_ini(){
        
        $registro = new RegistroModel();
        $cedula = $_GET['cedula'];
        
        if(isset( $_GET['cedula']) && !empty($_GET['cedula']))
        {
            $callBack = $_GET['jsoncallback'];
            $columnas_1=" c.id_creditos, p.id_participes";
            $tablas_1="core_creditos c
            inner join core_participes p on p.id_participes =c.id_participes";
            $where_1="p.cedula_participes = '$cedula' and p.id_estatus =1 and c.id_estatus =1 and c.id_estado_creditos =4";
            $id_1= "c.id_creditos";
            $resultUsu=$registro->getCondiciones($columnas_1, $tablas_1, $where_1, $id_1);
            
            
            
            
            if(!empty($resultUsu) && count($resultUsu)>0){
                
                $respuesta	= json_encode( array('respuesta'=>"SI") );
                
                
            }else{
                
                $respuesta	= json_encode( array('respuesta'=>"NO") );
                
            }
            
            echo $callBack."(".$respuesta.");";
            
        }
        
    }
 
    
    
    public function ReporteCertificado(){
        session_start();
        $participes = new ParticipesModel();
        $id_participes =  (isset($_REQUEST['id_participes'])&& $_REQUEST['id_participes'] !=NULL)?$_REQUEST['id_participes']:'';
        
        $datos_reporte = array();
        $columnas = " registro_tres_cuotas.id_registro_tres_cuotas,
                      core_participes.id_participes,
                      core_participes.apellido_participes,
                      core_participes.nombre_participes,
                      core_participes.cedula_participes,
                      registro_tres_cuotas.nombre_participes,
                      registro_tres_cuotas.cedula_participes,
                      core_creditos.id_creditos,
                      core_creditos.numero_creditos,
                      registro_tres_cuotas.numero_creditos,
                      registro_tres_cuotas.pdf_registro_tres_cuotas,
                      registro_tres_cuotas.creado,
                      registro_tres_cuotas.modificado,
                      core_tipo_creditos.id_tipo_creditos,
                      core_tipo_creditos.nombre_tipo_creditos";
        
        $tablas = "public.registro_tres_cuotas,
                  public.core_participes,
                  public.core_creditos,
                  public.core_tipo_creditos";
        $where= "registro_tres_cuotas.id_participes = core_participes.id_participes AND
                 core_tipo_creditos.id_tipo_creditos = core_creditos.id_tipo_creditos AND
                 registro_tres_cuotas.id_creditos = core_creditos.id_creditos AND registro_tres_cuotas.id_participes = '$id_participes'";
        $id="core_participes.id_participes";
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->nombre_participes;
        $datos_reporte['APELLIDO_PARTICIPES']=$rsdatos[0]->apellido_participes;
        $datos_reporte['CEDULA_PARTICIPES']=$rsdatos[0]->cedula_participes;
        
        
        $this->verReporte("ReporteCertificado", array('datos_reporte'=>$datos_reporte ));
        
        
        
    }
    
    public function ReporteCandidatos(){
        session_start();
        $participes = new ParticipesModel();
        $id_padron_electoral_representantes =  (isset($_REQUEST['id_padron_electoral_representantes'])&& $_REQUEST['id_padron_electoral_representantes'] !=NULL)?$_REQUEST['id_padron_electoral_representantes']:'';
        
        $datos_reporte = array();
        $columnas= "a.id_padron_electoral_representantes,
                    b.apellido_participes,
                    b.cedula_participes,
                    b.nombre_participes,
                    c.apellido_participes as apellido_suplente,
                    c.nombre_participes as nombre_suplente,
                    c.cedula_participes as cedula_suplente,
                    a.foto_representante,
                    a.foto_suplente,
                    a.correo_representante,
                    a.correo_suplente,
                    case when a.acepto_representante_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_representante_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_representante,
                    case when a.acepto_suplente_padron_electoral_representantes=0 THEN 'Pendiente' when a.acepto_suplente_padron_electoral_representantes=1 THEN 'Acepto' else 'Rechazo' end acepto_suplente,
                    d.nombre_entidad_patronal,
                    f.nombre_provincias,
                    g.nombre_ciudades";
        
        $tablas =  "padron_electoral_representantes a
                    inner join core_participes b on a.id_representante = b.id_participes and b.id_estatus = 1
                    inner join core_entidad_patronal d on d.id_entidad_patronal = b.id_entidad_patronal
                    inner join core_participes_informacion_adicional e on b.id_participes = e.id_participes
                    inner join core_provincias f on e.id_provincias = f.id_provincias
                    inner join core_ciudades g on e.id_ciudades = g.id_ciudades
                    left join
                	(
                	select b1.cedula_participes, b1.apellido_participes, b1.nombre_participes, a1.id_padron_electoral_representantes
                	 from padron_electoral_representantes a1
                 	 inner join core_participes b1 on a1.id_suplente = b1.id_participes and b1.id_estatus = 1
                  	)c on a.id_padron_electoral_representantes = c.id_padron_electoral_representantes";
        
        $where = "1=1 and a.id_padron_electoral_representantes = $id_padron_electoral_representantes";
        
        $id = "a.id_padron_electoral_representantes";
        $rsdatos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        
        $datos_reporte['ENTIDAD_PATRONAL']=$rsdatos[0]->nombre_entidad_patronal;
        $datos_reporte['NOMBRE_PROVINCIAS']=$rsdatos[0]->nombre_provincias;
        $datos_reporte['NOMBRE_CUIDADES']=$rsdatos[0]->nombre_ciudades;
        $datos_reporte['APELLIDO_PARTICIPES']=$rsdatos[0]->apellido_participes;
        $datos_reporte['NOMBRE_PARTICIPES']=$rsdatos[0]->nombre_participes;
        $datos_reporte['CEDULA_PARTICIPES']=$rsdatos[0]->cedula_participes;
        $datos_reporte['CORREO_PARTICIPES']=$rsdatos[0]->correo_representante;
        $datos_reporte['APELLIDO_SUPLENTE']=$rsdatos[0]->apellido_suplente;
        $datos_reporte['NOMBRE_SUPLENTE']=$rsdatos[0]->nombre_suplente;
        $datos_reporte['CEDULA_SUPLENTE']=$rsdatos[0]->cedula_suplente;
        $datos_reporte['CORREO_SUPLENTE']=$rsdatos[0]->correo_suplente;
        
        $foto_suplente='<img src="view/Administracion/DevuelveImagenView.php?id_valor='.$rsdatos[0]->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_suplente" width="100" height="80">';
        $datos_reporte['FOTO_SUPLENTE']=$foto_suplente;
        
        $foto_representante='<img src="view/Administracion/DevuelveImagenView.php?id_valor='.$rsdatos[0]->id_padron_electoral_representantes.'&id_nombre=id_padron_electoral_representantes&tabla=padron_electoral_representantes&campo=foto_representante" width="100" height="80">';
        $datos_reporte['FOTO_REPRESENTANTE']=$foto_representante;
        
        
       
        
        
        $this->verReporte("ReporteCandidatos", array('datos_reporte'=>$datos_reporte ));
        
        
        
    }
}


?>