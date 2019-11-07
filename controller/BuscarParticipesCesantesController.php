<?php
class BuscarParticipesCesantesController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("BuscarParticipesCesantes",array(
            "result" => ""
        ));
    }
    
    
    
    public function index1()
    {
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        $cedula_participe=$_GET['cedula_participe'];
        $id_solicitud=$_GET['id_solicitud'];
        
        $this->view_Credito("BuscarParticipesCesantes",array(
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
                <font size="3">Tipo Crédito : '.$resultSet[0]->nombre_tipo_creditos.'</font>
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
        
        //
    }
    
    public function BuscarParticipe()
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
        
        $html.='
        <div class="box box-widget widget-user-2">';
        if(!(empty($resultCreditos))) $html.='';
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
    
 
    
    public function AportesParticipe()
    {
        session_start();
        
        $html="";
        $_id_creditos=0;
        $total_saldo=0;
        $total_descuentos=0;
        $total_recibir=0;
        $total_pagar=0;
        $id_participe=$_POST['id_participe'];
        $participes= new ParticipesModel();
    
        $columnas="COALESCE(sum(c.valor_personal_contribucion),0) aporte_personal_100, (coalesce(sum(c.valor_personal_contribucion),0)/2) aporte_personal_50,
                    (select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=10 and c1.id_estatus=1) as impuesto_ir_superavit_personal_100,
                    (select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=10 and c2.id_estatus=1) as impuesto_ir_superavit_personal_50,
                    (select COALESCE(sum(c3.valor_personal_contribucion),0)  from core_contribucion c3 where c3.id_participes=".$id_participe." and c3.id_contribucion_tipo=50 and c3.id_estatus=1) as superavit_aporte_personal_100,
                    (select (coalesce(sum(c4.valor_personal_contribucion),0)/2)  from core_contribucion c4 where c4.id_participes=".$id_participe." and c4.id_contribucion_tipo=50 and c4.id_estatus=1) as superavit_aporte_personal_50,
                    (select min(to_char(c5.fecha_registro_contribucion, 'TMMONTH/YYYY')) imposicion_desde from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1),
                    (select max(to_char(c5.fecha_registro_contribucion, 'TMMONTH/YYYY')) imposicion_hasta from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1)
                    ";
        $tablas="core_contribucion c inner join  core_participes p on c.id_participes=p.id_participes";
        $where="p.id_participes=".$id_participe." and c.id_estatus=1 and c.id_contribucion_tipo=1";
        $id="aporte_personal_100";
        

        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
       
        if($action == 'ajax')
        {
            
       
            $resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
            
            
            if(!empty($resultSet)){
        
                foreach ($resultSet as $res)
                {
                
                    $Total50PersonalMasRendimientos =  $res->aporte_personal_50+$res->impuesto_ir_superavit_personal_50+$res->superavit_aporte_personal_50;
                    
                    $_imposiciones_desde=$res->imposicion_desde;
                    $_imposiciones_hasta=$res->imposicion_hasta;
                    $_aporte_personal_50=number_format((float)$res->aporte_personal_50, 2, ',', '.');
                    $_impuesto_ir_superavit_personal_50=number_format((float)$res->impuesto_ir_superavit_personal_50, 2, ',', '.');
                    $_superavit_aporte_personal_50=number_format((float)$res->superavit_aporte_personal_50, 2, ',', '.');
                    
                    
                }
                
                
                
                
                
                $html='<div class="box box-solid bg-olive">
                    <div class="box-header with-border">
                    <h3 class="box-title"><b>CÁLCULO DE DESAFILIACIÓN</b></h3>
                    </div>
                    <div class="box box-solid bg-navy">
                    <div class="box-header with-border">
                    <h5 class=""><b>IMPOSICIONES DESDE:</b> '.$_imposiciones_desde.'<b> HASTA:</b> '.$_imposiciones_hasta.'</h5>
                    </div>
                    <table border="1" width="100%">
                    <tr style="color:white;" class="bg-aqua">
                    <th width="10%"></th>
                    </tr>
                    <tr style="color:white;" class="bg-aqua">
                    <th width="10%">50% del Aporte Personal (Res. N° SBS-2013-504)</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblAportePersonal">'.$_aporte_personal_50.'</span></font></td>
                    </tr>
                    <tr style="color:white;" class="bg-aqua">
                    <th width="10%">50% del Impuesto IR Superavit Personal (Res. N° SBS-2013-504)</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblImpuestoPersonal">'.$_impuesto_ir_superavit_personal_50.'</span></font></td>
                    </tr>
                    <tr style="color:white;" class="bg-aqua">
                    <th width="10%">50% del Superavit por aporte Personal (Res. N° SBS-2013-504)</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblSuperavitAportePersonal">'.$_superavit_aporte_personal_50.'</span></font></td>
                    </tr>
                    <tr style="color:white;" class="bg-red">
                    <th width="10%">TOTAL 50% PERSONAL + RENDIMIENTOS PRESTACIÓN</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblTotalSuma"><b>'.number_format((float)$Total50PersonalMasRendimientos, 2, ',', '.').'</b></span></font></td>
                    </tr>';
                
               
                
                
                // CONSULTO LOS CREDITOS DE LOS PARTICIPES
                $columnas="bb.id_creditos,bb.id_tipo_creditos, tc.nombre_tipo_creditos, tc.codigo_tipo_creditos";
                $tablas="core_participes aa
                inner join core_creditos bb on bb.id_participes = aa.id_participes
                inner join core_estado_participes cc on cc.id_estado_participes = aa.id_estado_participes
                inner join core_estado_creditos dd on dd.id_estado_creditos = bb.id_estado_creditos
                inner join core_tipo_creditos tc on bb.id_tipo_creditos=tc.id_tipo_creditos";
                $where=" aa.id_estatus = 1
                and upper(cc.nombre_estado_participes) = 'ACTIVO'
                and upper(dd.nombre_estado_creditos) = 'ACTIVO'
                and aa.id_participes =".$id_participe."";
                $id="bb.id_creditos";
                
                $resultCreditos=$participes->getCondiciones($columnas, $tablas, $where, $id);
                
                if(!(empty($resultCreditos)))
                {
                 
                    $html.='<tr style="color:white;" class="bg-olive">
                    <th width="10%">DESCUENTOS</th>
                    <td box box-solid bg-olive"  width="10%"><font color="black"><span id="lblCreditoOrdinario"></span></font></td>
                    </tr>';
                    
                    foreach($resultCreditos as $res)
                    {
                        
                        // capturo el id de los creditos que tiene el participe
                        $_id_creditos=$res->id_creditos;
                        $_nombre_tipo_creditos=$res->nombre_tipo_creditos.' #'.$res->id_creditos;
                        
                       $total_saldo= $this->Buscar_Cuotas_Actuales($_id_creditos);
                       $total_interes= $this->Buscar_Interes($_id_creditos);
                       
                       
                       $html.=' <tr style="color:white;" class="bg-aqua">
                       <th width="10%">'.$_nombre_tipo_creditos.'</th>
                       <td bgcolor="white"  width="10%"><font color="black"><span id="lblCreditoOrdinario">'.number_format((float)$total_saldo, 2, ',', '.').'</span></font></td>
                       </tr>';
                       
                       
                       $total_descuentos=$total_descuentos+$total_saldo;
                        
                    }
                }

                    

                
                $total_recibir=$Total50PersonalMasRendimientos-$total_descuentos;
                  
                $html.=' <tr style="color:white;" class="bg-red">
                    <th width="10%">TOTAL DESCUENTOS</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblTotalDescuentos"><b>'.number_format((float)$total_descuentos, 2, ',', '.').'</b></span></font></td>
                    </tr>
                    <tr style="color:white;" class="bg-black">
                    <th width="10%">TOTAL A RECIBIR</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblTotalRecibir"><b>'.number_format((float)$total_recibir, 2, ',', '.').'</b></span></font></td>
                    </tr>
                    </table>
                    </div></div>';
                
            
                
                
                if ($total_recibir<0) {
                    
                    $total_pagar=$total_recibir*(-1);
                    
                    $html.='<div class="box box-solid bg-aqua" style = "margin-top:20px">
                    <div class="box-header with-border">
                    <h3 class="box-title"><b>ALERTAS</b></h3>
                    </div>
                    <table border="1" width="100%">
                    <tr style="color:white;" class="bg-white">
                    <th width="10%">Estimado participe para poder acceder a la desafiliacion debe cubrir el monto adeudado en sus créditos a la fecha con el valor de:</th>
                    <td bgcolor="white"  width="10%"><font color="black"><span id="lblTotalDescuentos"><b>'.number_format((float)$total_pagar, 2, ',', '.').'</b></span></font></td>
                    </tr>
                    </table>
                    </div>';
                    
                    
               } 
                
                
            }else{
                $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
                $html.='<div class="alert alert-info alert-dismissable" style="margin-top:40px;">';
                $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html.='<h4>Aviso!!!</h4> <b>Actualmente no puede desafiliarse...</b>';
                $html.='</div>';
                $html.='</div>';
            }
            
            echo $html;
            
        }
        
        
    }
    
    public function Buscar_Interes($_id_creditos)
    {
        $participe= new ParticipesModel();
        $anio_actual=date("Y");
        $mes_actual=date("m");
        
        $_id_estado_tabla_amortizacion=0;
        $_balance_tabla_amortizacion=0;
        $total_anterior=0;
        
        
        $_fecha_tabla_amortizacion="";
        $_fecha_tabla_amortizacion_anterior="";
        $_fecha_tabla_amortizacion_adelantada="";
        
            $year_buscar = $anio_actual;
            $mes_buscar = $mes_actual;

        
        $mes_buscar=str_pad($mes_buscar,2,'0',STR_PAD_LEFT);
        $dia= date("d",(mktime(0,0,0,$mes_buscar+1,1,$year_buscar)-1));
        $_fecha_tabla_amortizacion=$year_buscar."-".str_pad($mes_buscar,2,'0',STR_PAD_LEFT)."-".$dia;
        
        
        
        
        
        $columnas="t.fecha_tabla_amortizacion, t.numero_pago_tabla_amortizacion, t.id_estado_tabla_amortizacion, t.balance_tabla_amortizacion, t.interes_tabla_amortizacion";
        $tablas="core_tabla_amortizacion t";
        $where="t.id_creditos='$_id_creditos' and t.id_estatus=1 and to_char(t.fecha_tabla_amortizacion, 'YYYY')='$year_buscar' and to_char(t.fecha_tabla_amortizacion, 'MM')=LPAD('$mes_buscar',2,'0')";
        $id="t.numero_pago_tabla_amortizacion";
        $resultSet=$participe->getCondiciones($columnas, $tablas, $where, $id);
        
        if(!empty($resultSet)){
            
            foreach ($resultSet as $res){
                
                
                $_id_estado_tabla_amortizacion= $res->id_estado_tabla_amortizacion;
                $_interes_tabla_amortizacion= $res->interes_tabla_amortizacion;
                
                
                
                // verifico que la ultimo cuota esta cancelada
                if($_id_estado_tabla_amortizacion==2){
                    
                    $columnas_adelantados="t.fecha_tabla_amortizacion as fecha_tabla_amortizacion_adelantada, balance_tabla_amortizacion";
                    $tablas_adelantados="core_tabla_amortizacion t";
                    $where_adelantados="t.id_creditos='$_id_creditos' and t.id_estatus=1 and t.id_estado_tabla_amortizacion=2";
                    $id_adelantados="t.fecha_tabla_amortizacion";
                    $limit_1="1";
                    $resultSetAdelantados=$participe->getCondicionesDescLimit($columnas_adelantados, $tablas_adelantados, $where_adelantados, $id_adelantados, $limit_1);
                    
                    
                    
                    if(!empty($resultSetAdelantados)){
                        
                        foreach ($resultSetAdelantados as $resA) {
                            
                            $_fecha_tabla_amortizacion_adelantada=$resA->fecha_tabla_amortizacion_adelantada;
                            
                            
                            if($_fecha_tabla_amortizacion_adelantada==$_fecha_tabla_amortizacion){
                                
                                $_balance_tabla_amortizacion= $res->balance_tabla_amortizacion;
                                
                                
                            }else{
                                
                                $_balance_tabla_amortizacion= $resA->balance_tabla_amortizacion;
                                
                            }
                            
                            
                            
                        }
                        
                    }
                    
                    
                }else{
                    
                    
                    
                    
                }
                
                
                $_total_saldo_actual=$_balance_tabla_amortizacion+$total_anterior;
                
                return  $_total_saldo_actual;
                
            }
            
            
        }else{
            
            // para los vencidos
            
            
            
        }
        
        
        
    }

    public function Buscar_Cuotas_Actuales($_id_creditos)
    {
        $participe= new ParticipesModel();
        $anio_actual=date("Y");
        $mes_actual=date("m");
        
        $_id_estado_tabla_amortizacion=0;
        $_balance_tabla_amortizacion=0;
        $total_anterior=0;
        
        
        $_fecha_tabla_amortizacion="";
        $_fecha_tabla_amortizacion_anterior="";
        $_fecha_tabla_amortizacion_adelantada="";
        
        
        
        
        if ($mes_actual == 01 || $mes_actual == 1)
        {
            $year_buscar = $anio_actual - 1;
            $mes_buscar = 12;
            
        }
        else
        {
            $year_buscar = $anio_actual;
            $mes_buscar = $mes_actual - 1;
            
        }
        
        $mes_buscar=str_pad($mes_buscar,2,'0',STR_PAD_LEFT);
        $dia= date("d",(mktime(0,0,0,$mes_buscar+1,1,$year_buscar)-1));
        $_fecha_tabla_amortizacion=$year_buscar."-".str_pad($mes_buscar,2,'0',STR_PAD_LEFT)."-".$dia;
        
        
        
        
        
        $columnas="t.fecha_tabla_amortizacion, t.numero_pago_tabla_amortizacion, t.id_estado_tabla_amortizacion, t.balance_tabla_amortizacion";
        $tablas="core_tabla_amortizacion t";
        $where="t.id_creditos='$_id_creditos' and t.id_estatus=1 and to_char(t.fecha_tabla_amortizacion, 'YYYY')='$year_buscar' and to_char(t.fecha_tabla_amortizacion, 'MM')=LPAD('$mes_buscar',2,'0')";
        $id="t.numero_pago_tabla_amortizacion";
        $resultSet=$participe->getCondiciones($columnas, $tablas, $where, $id);
        
        if(!empty($resultSet)){
            
            foreach ($resultSet as $res){
                
                
                $_id_estado_tabla_amortizacion= $res->id_estado_tabla_amortizacion;
                
                // verifico que la ultimo cuota esta cancelada
                if($_id_estado_tabla_amortizacion==2){
                    
                    $columnas_adelantados="t.fecha_tabla_amortizacion as fecha_tabla_amortizacion_adelantada, balance_tabla_amortizacion";
                    $tablas_adelantados="core_tabla_amortizacion t";
                    $where_adelantados="t.id_creditos='$_id_creditos' and t.id_estatus=1 and t.id_estado_tabla_amortizacion=2";
                    $id_adelantados="t.fecha_tabla_amortizacion";
                    $limit_1="1";
                    $resultSetAdelantados=$participe->getCondicionesDescLimit($columnas_adelantados, $tablas_adelantados, $where_adelantados, $id_adelantados, $limit_1);
                    
                    
                    
                    if(!empty($resultSetAdelantados)){
                        
                        foreach ($resultSetAdelantados as $resA) {
                         
                            $_fecha_tabla_amortizacion_adelantada=$resA->fecha_tabla_amortizacion_adelantada;
                            
                            
                            if($_fecha_tabla_amortizacion_adelantada==$_fecha_tabla_amortizacion){
                                
                                $_balance_tabla_amortizacion= $res->balance_tabla_amortizacion;
                                
                                
                              }else{
                                
                                $_balance_tabla_amortizacion= $resA->balance_tabla_amortizacion;
                                
                             }
                            
                            
                            
                        }
                        
                    }
                    
                    
                }else{
                    
                    
                    $columnas_1="t.fecha_tabla_amortizacion as fecha_tabla_amortizacion_anterior, balance_tabla_amortizacion";
                    $tablas_1="core_tabla_amortizacion t";
                    $where_1="t.id_creditos='$_id_creditos' and t.id_estatus=1 and t.id_estado_tabla_amortizacion=2";
                    $id_1="t.fecha_tabla_amortizacion";
                    $limit_1="1";
                    $resultSet1=$participe->getCondicionesDescLimit($columnas_1, $tablas_1, $where_1, $id_1, $limit_1);
                    
                    
                    if(!empty($resultSet1)){
                        
                        foreach ($resultSet1 as $res1)
                        {
                        
                            $_fecha_tabla_amortizacion_anterior=$res1->fecha_tabla_amortizacion_anterior;
                            $_balance_tabla_amortizacion=$res1->balance_tabla_amortizacion;
                            
                            
                            $columnas_2="coalesce(sum(t.total_balance_tabla_amortizacion),0) as total";
                            $tablas_2="core_tabla_amortizacion t";
                            $where_2="t.id_creditos='$_id_creditos' and t.id_estatus=1 and t.id_estado_tabla_amortizacion<>2 and date(t.fecha_tabla_amortizacion) between '$_fecha_tabla_amortizacion_anterior' and '$_fecha_tabla_amortizacion'";
                           
                            $resultSet2=$participe->getCondicionesSinOrden($columnas_2, $tablas_2, $where_2, "");
                            
                            
                            
                            if(!empty($resultSet2)){
                                
                                
                                foreach ($resultSet2 as $res2) {
                                 
                                    $total_anterior=$res2->total;
                                     
                                }
                                
                            }
                           
                            
                        }
                        
                    }
                    
                    
                    
                }
                
              
                $_total_saldo_actual=$_balance_tabla_amortizacion+$total_anterior;
                
                return  $_total_saldo_actual;
                
            }
            
            
        }else{
            
            // para los vencidos
            
            
            
        }
     
        
       
    }
 
    

    
    
    
    
    
    
 
    

   
    public function print()
    {
        
        session_start();
        $entidades = new EntidadesModel();
        $paticipes = new ParticipesModel();
        
        $html="";
        $id_usuarios = $_SESSION["id_usuarios"];
        $fechaactual = getdate();
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        
        $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/rp_c';
        $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
        $domLogo=$directorio.'/view/images/logo.png';
        $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
        
        $valor_total_vista1 = 0;
        
        
        if(!empty($id_usuarios)){
            
            
            if(isset($_GET["id_participes"])){
                
                
                $_id_participes = $_GET["id_participes"];
                
                
                $columnas="core_participes.id_participes, core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
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
                
                $where="core_participes.id_participes='".$_id_participes."'";
                
                $id="core_participes.id_participes";
                
                $resultSetCabeza=$paticipes->getCondiciones($columnas, $tablas, $where, $id);
                
                
                
                if(!empty($resultSetCabeza)){
                    
                    
                    
                    
                    $_id_participes  =$resultSetCabeza[0]->id_participes;
                    $_nombre_estado_participes     =$resultSetCabeza[0]->nombre_estado_participes;
                    $_nombre_estado_participes     =$resultSetCabeza[0]->nombre_participes;
                    $_celular_participes           =$resultSetCabeza[0]->celular_participes;
                    $_cedula_participes            =$resultSetCabeza[0]->cedula_participes;
                    $_apellido_participes          =$resultSetCabeza[0]->apellido_participes;
                    
                    
                    
                    $html.= '<table style="width:100%;" border=1 cellspacing=0.0001 >';
                    $html.= '<tr >';
                    $html.= '<td style="background-repeat: no-repeat;	background-size: 10% 50%;	background-image: url(http://192.168.1.231/rp_c/view/images/Logo-Capremci-h-170.jpg);
                                        background-position: 0% 100%;	font-size: 10px; padding: 10px; 	text-align:center;" class="central" colspan="2">';
                    $html.= '<strong>';
                    $html.= 'CAPREMCI'.'<br>';
                    $html.= 'Av. Baquerizo Moreno E-9781 y Leonidas Plaza'.'<br>';
                    $html.= '023828870'.'';
                    $html.= '</strong>';
                    $html.= '</td>';
                    $html.= '</tr>';
                    
                    $html.= '</table>';
                    
                    $html.= '<br>';
                    
                    $html.='<table style="width: 100%;"  border=0 cellspacing=0.0001 >';
                    $html.='<tr>';
                    $html.='<th colspan="12" style="text-align:center; font-size: 13px;"><b>ACTA DE LIQUIDACIÓN DE CUENTA INDIVIDUAL<br>DESAFILIACIÓN VOLUNTARIA<b></th>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p>En la ciudad de Quito hoy 15 de Julio del 2019, de manera libre y voluntaria
                                                                                              comparecen por una parte el F.C.P.C DE CESANTÍA DE SERVIDORES Y TRABAJADORES
                                                                                              PÚBLICOS DE LAS FUERZAS ARMADAS CAPREMCI, legalmente representada por quien suscribe
                                                                                              la presente acta, en calidad de "FONDO"; y, por otra, el señor/a '.$_apellido_participes.' '.$_nombre_estado_participes.'
                                                                                              , en calidad de PARTICIPE, con el objeto de suscribir la presente
                                                                                             Acta de Liquidación de la cuenta individual, al tenor de las siguientes Cláusulas:</p></td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>PRIMERA</b>.- La relación de Afilicación, lícita y personal entre los comparecientes
                                                                                                               concluye en la presente fecha, de mutuo acuerdo o por fallecimiento del
                                                                                                               titular, de conformidad con las normas previstas en el Estatuto del Fondo
                                                                                                               y sus reglamentos.</p></td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>SEGUNDA</b>.- En este acto se precede a realizar la liquidación del 50% del aporte personal,
                                                                                                               más rendimientos del PARTICIPE, con las siguientes cantidades:</p></td>';
                    
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<th colspan="12" style="text-align:justify; font-size: 11px;"><br><b>PRESTACIONES.- CARPETA  N.</b></th>';
                    $html.='</tr>';
                    
                    
                    $html.='<tr>';
                    $html.='<td colspan="6" style="font-size: 11px;"><br><b>IMPOSICIONES DESDE</b></td>';
                    $html.='<td colspan="6" style="font-size: 11px;"><b>HASTA</b></td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;">50% del Aporte Personal (Res. N°SBS-2013-504)</td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;">50% del Impuesto IR Superavit Personal (Res. N°SBS-2013-504)</td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;">50% del Superavit por Aporte Personal (Res. N°SBS-2013-504)</td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;"><b>TOTAL 50% PERSONAL + RENDIMIENTOS PRESTACIÓN:</b></td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;"><br><b>DESCUENTOS</b></td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;">PQ-Crédito Ordinario al Jun 30 2019</td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;">PQ-Crédito Emergente al Jul 11 2019</td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;"><b>TOTAL DESCUENTOS:</b></th>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </th>';
                    $html.='</tr>';
                    
                    
                    $html.='<tr>';
                    $html.='<td colspan="7" style="font-size: 11px;"><br><b>TOTAL A RECIBIR:</b></td>';
                    $html.='<td colspan="5" style="font-size: 11px;">   </td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="6" style="font-size: 11px;"><b>(Son:  )</b></td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p>De acuerdo con lo que establece el Art.53 de la Resolución 280-2016-F, de 07 de septiembre
                                                                                              de 2016, emitida por la Junta de Política y Regulación Monetaria y Financiera, se tiene que la
                                                                                              cuenta individual de cada participe se encuentra constituida por el aporte personal y sus rendimientos;
                                                                                              el aporte adicional, de ser el caso y sus rendimientos; y, el aporte patronal y sus rendimientos,
                                                                                              de ser el caso los cuales constituyen un pasivo del patrimonio autónomo de fondo.
                                                                                              El resultado anual que genere la administración del Fondo Complementario Previsional Cerrado,
                                                                                              de acuerdo a las políticas de administración e inversión, será distribuido proporcionalmente
                                                                                              a cada cuenta individual de los partícipes, en función de lo acumulado y de la fecha de aportación</p></td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>TERCERA</b>.- El aporte patronal del afiliado a la presente fecha, asciende a 1.905,43, y el 50%
                                                                                                               de su aporte personal más rendimientos, a la presente fecha asciende a 1.073,73, los
                                                                                                               mismos que se le serán restituidos, sumandos los rendimientos generados hasta la
                                                                                                               fecha de devolución, una vez que el PARTICIPE quede efectivamente CESANTE de la
                                                                                                               Entidad Patronal para la cual labora actualmente, mientras tantos dichos valores
                                                                                                               quedarán registrados contablemente en una cuenta de pasivo a nombre del participe.</p></td>';
                    
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>CUARTA</b>.- El/la señores '.$_apellido_participes.' '.$_nombre_estado_participes.' deja(n) expresa constancia que recibe el valor
                                                                                                              mencionado en la claúsula segunda a su satisfación y que con dicha cantidad se encuentran
                                                                                                              completamente pagados sus beneficios y/o prestaciones y de conformidad con la norma vigente
                                                                                                              y que, por tanto, nada tiene que reclamar y por ningún concepto en contra del Fondo, sus
                                                                                                              Directivos y/o Funcionarios, a excepción del 50% del aporte personal más rendimientos y el
                                                                                                              aporte patronal más sus rendimientos, que serán restituidos de conformidad con la cláusula tercera.</p></d>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>QUINTA.- EFECTO DEL ACTA DE LIQUIDACIÓN</b>.- Expresamente los comparecientes declaran que con la suscripción de la
                                                                                                          presente Acta de Liquidación no pretenden irrogar ni irrogan ningún tipo de perjuicio a terceros, por lo que
                                                                                                          suscriben el presente Instrumento sobre la buena fé de las estipulaciones que han acordado entre las mismas.
                                                                                                          De conformidad con lo establecido en los artículos 2348, 2362 del Código Civil, los comparecientes acuerdan
                                                                                                          terminar la relación jurídica y darle la calidad a la presente acta de sentencia ejecutoriada, pasada por la
                                                                                                          autoridad de cosa juzgada en última instancia.</p></d>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="text-align:justify; font-size: 11px;"><p><b>SEXTA.- ACEPTACIÓN Y RATIFICACIÓN</b>.- Las partes aceptan en todos sus términos y se ratifican en las estipulaciones
                                                                                                         y declaraciones contenidas en las cláusulas precedentes, en fé de lo cual, suscriben el presente instrumento
                                                                                                         en tres ejemplares de igual tenor y valor.</p></d>';
                    
                    $html.='<br>';
                    $html.='<br>';
                    $html.='<br>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="6" style="font-size: 11px;">________________________________</td>';
                    $html.='<td colspan="6" style="text-align:center; font-size: 10px;">___________________________________</td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="6" style="font-size: 11px;"> '.$_apellido_participes.''.$_nombre_estado_participes.'</td>';
                    $html.='<td colspan="6" style="text-align:center; font-size: 11px;">ING. STEPHANY ZURITA CEDEÑO</td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="6" style="font-size: 11px;"> AFILIADO (A)/ BENEFICIARIO (A)</td>';
                    $html.='<td colspan="6" style="text-align:center; font-size: 11px;">REPRESENTANTE LEGAL CAPREMCI</td>';
                    $html.='</tr>';
                    
                    $html.='<tr>';
                    $html.='<td colspan="12" style="font-size: 11px;">C.C: '.$_cedula_participes.'  </td>';
                    $html.='</tr>';
                    
                    $html.='</table>';
                    
                    
                }
                
                
                $this->report("BuscarParticipesCesantes",array( "resultSet"=>$html));
                die();
                
            }
            
            
            
            
        }else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
        
        
        
        
    }
    
    public function cargaTipoPrestaciones(){
        
        $tipo_prestaciones = null;
        $tipo_prestaciones = new TipoPrestacionesModel();
        
        $query = "SELECT id_tipo_prestaciones, nombre_tipo_prestaciones FROM core_tipo_prestaciones WHERE 1 = 1";
        
        $resulset = $tipo_prestaciones->enviaquery($query);
        
        if(!empty($resulset) && count($resulset)>0){
            
            echo json_encode(array('data'=>$resulset));
            
        }
    }
    
 
}


?>