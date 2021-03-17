<?php
class BuscarParticipesCesantesController extends ControladorBase{
    
	public $_var_id_solicitud;
	
	
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
        
        $_var_id_solicitud = $id_solicitud;
        
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
        $respuesta= array();
        
        $columnas = "solicitud_prestaciones.fecha_presentacion,
        			bancos.nombre_bancos, 
	  				solicitud_prestaciones.tipo_cuenta_bancaria, 
	  				solicitud_prestaciones.numero_cuenta_bancaria, 
	  				solicitud_prestaciones.identificador_consecutivos";
        
        $tablas   = "public.bancos, 
  					public.solicitud_prestaciones";
        
        $where    = "solicitud_prestaciones.id_bancos = bancos.id_bancos
  						AND id_solicitud_prestaciones =".$id_solicitud;
        
        $resultSet=$db->getCondiciones($columnas, $tablas, $where);
        
        $html='<div id="info_solicitud_participe" class="small-box bg-teal">
               <div class="inner">
              <table width="100%">
              <tr>
              <td colspan="4" align="center">
                <font size="4"><b>INFORMACIÓN SOLICITUD<b></font>
              </td>
              </tr>
              <tr>
              <td width="30%">
                <font size="4">Tipo de Prestación: '.'DESAFILIACIÓN'.'</font>
              </td>
              <td width="30%">
                
                <font size="4">Fecha Presentación : '.$resultSet[0]->fecha_presentacion.'</font>			
              </td>
              <td width="30%">
                <font size="4">Nombre Banco : '.$resultSet[0]->nombre_bancos.'</font>		
                
              </td>
              <tr>
              
              <td width="30%">
               	 <font size="4">Tipo de  Cuenta : '.$resultSet[0]->tipo_cuenta_bancaria.'</font> 
                 
                 
               </td>
              
              <td width="30%">
                
                <font size="4">Número Cuenta : '.$resultSet[0]->numero_cuenta_bancaria.'</font>
              </td> 		
              
              </tr>
                </table>
               </div>
               </div>';
        
        
        //echo $html;
        
        
        array_push($respuesta, $html);
        array_push($respuesta, $id_solicitud);
        
        
        
        echo json_encode($respuesta);
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
        
        
        echo json_encode($respuesta);
    }
    
 
    
    public function AportesParticipe()
    {
    	
        session_start();
        
        $_fecha_concesion_creditos = "";
        
        $html="";
        $_id_creditos=0;
        $_id_tipo_creditos=0;
        $total_saldo=0;
        $total_descuentos=0;
        $total_recibir=0;
        $total_pagar=0;
        $id_participe= $_POST['id_participe'];
        $fecha_prestaciones = $_POST['fecha_prestaciones'];
        $_id_tipo_prestaciones = $_POST['id_tipo_prestaciones'];
        $participes= new ParticipesModel();
    
        
        
        if ($_id_tipo_prestaciones == 2)
        {            
           
		        $columnas="COALESCE(sum(c.valor_personal_contribucion+ c.valor_patronal_contribucion),0) aporte_personal_100, 
							(coalesce(sum(c.valor_personal_contribucion),0)/2) aporte_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=5 and c1.id_estatus=1) as retroactivo_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=5 and c2.id_estatus=1) as retroactivo_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=7 and c1.id_estatus=1) as excedente_por_aporte_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=7 and c2.id_estatus=1) as excedente_por_aporte_personal_50,
		        			(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=9 and c1.id_estatus=1) as interes_por_aporte_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=9 and c2.id_estatus=1) as interes_por_aporte_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=10 and c1.id_estatus=1) as impuesto_ir_superavit_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=10 and c2.id_estatus=1) as impuesto_ir_superavit_personal_50,
							(select COALESCE(sum(c3.valor_personal_contribucion),0)  from core_contribucion c3 where c3.id_participes=".$id_participe." and c3.id_contribucion_tipo=50 and c3.id_estatus=1) as superavit_aporte_personal_100,
							(select (coalesce(sum(c4.valor_personal_contribucion),0)/2)  from core_contribucion c4 where c4.id_participes=".$id_participe." and c4.id_contribucion_tipo=50 and c4.id_estatus=1) as superavit_aporte_personal_50,
							(select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_desde from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1 order by id_contribucion asc limit 1),
							(select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_hasta from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1  order by id_contribucion DESC limit 1)";
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
		                
		                    $Total50PersonalMasRendimientos =  $res->aporte_personal_50+$res->retroactivo_personal_50+$res->excedente_por_aporte_personal_50+$res->interes_por_aporte_personal_50+$res->impuesto_ir_superavit_personal_50+$res->superavit_aporte_personal_50;
		                    
		                    
		                    
		                    
		                    $_imposiciones_desde=$res->imposicion_desde;
		                    $_imposiciones_hasta=$res->imposicion_hasta;
		                    $_aporte_personal_50=number_format((float)$res->aporte_personal_50, 2, ',', '.');
		                    $_retroactivo_personal_50=number_format((float)$res->retroactivo_personal_50, 2, ',', '.');
		                    $_excedente_por_aporte_personal_50=number_format((float)$res->excedente_por_aporte_personal_50, 2, ',', '.');
		                    $_interes_por_aporte_personal_50=number_format((float)$res->interes_por_aporte_personal_50, 2, ',', '.');
		                    $_impuesto_ir_superavit_personal_50=number_format((float)$res->impuesto_ir_superavit_personal_50, 2, ',', '.');
		                    $_superavit_aporte_personal_50=number_format((float)$res->superavit_aporte_personal_50, 2, ',', '.');
		                    
		                    
		                }
		                
		                $html='<div >
		                    <div >
		                    <h3 class="box-title"><b>SIMULACIÓN: CÁLCULO DE DESAFILIACIÓN</b></h3>
		                    </div>
		                    <div >
		                    <div >
		                    <h5><b>IMPOSICIONES DESDE:</b> '.$_imposiciones_desde.'<b> HASTA:</b> '.$_imposiciones_hasta.'</h5>
		                    </div>
		                    <div style="align-content: center;">
		                    <table  border="1" width="70%">
		                    <tr >
		                    <th></th>
		                    </tr>
		                    <tr>
		                    	<td width="70%" >50% del Aporte Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"  width="30%"><span id="lblAportePersonal"> $ '.$_aporte_personal_50.'</span></td>
		                    </tr>
		                    <tr>
		                    	<td width="70%" >50% del Retroactivo Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"  width="30%"><span id="lblRetroactivoPersonal"> $ '.$_retroactivo_personal_50.'</span></td>
		                    </tr>
		                    <tr>
		                    	<td width="70%" >50% del Excedente por Aporte Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"  width="30%"><span id="lblExcedenteAportePersonal"> $ '.$_excedente_por_aporte_personal_50.'</span></td>
		                    </tr>
		                    <tr>
		                    	<td width="70%" >50% del Interés por Aporte Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"  width="30%"><span id="lblInteresAportePersonal"> $ '.$_interes_por_aporte_personal_50.'</span></td>
		                    </tr>			
		                    
		                    <tr>
		                    	<td >50% del Impuesto IR Superavit Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"><span id="lblImpuestoPersonal"> $ '.$_impuesto_ir_superavit_personal_50.'</span></td>
		                    </tr>
		                    <tr >
		                    	<td >50% del Superavit por aporte Personal (Res. N° SBS-2013-504)</td>
		                    	<td style="text-align: right;"><span id="lblSuperavitAportePersonal"> $ '.$_superavit_aporte_personal_50.'</span></td>
		                    </tr>
		                    
		                    
		                    <tr >
		                    	<th >TOTAL 50% PERSONAL + RENDIMIENTOS PRESTACIÓN</th>
		                    	<td style="text-align: right;"><span id="lblTotalSuma"><b> $ '.number_format((float)$Total50PersonalMasRendimientos, 2, ',', '.').'</b></span></td>
		                    </tr>
		                </table>
		                </div>
		                    </div>
		               ';
		                
		                
		                #CONSULTO LOS CREDITOS DE LOS PARTICIPES
		                $columnas="bb.fecha_concesion_creditos, bb.id_creditos,bb.id_tipo_creditos, tc.nombre_tipo_creditos, tc.codigo_tipo_creditos";
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
		                 
		                    $html.=' <div >
					                    <h5 ><b>DESCUENTOS</b></h5>
					                 </div>
		                    		
		                    		<table border="1" width="70%"> 
		                    			';
		                    
		                    foreach($resultCreditos as $res)
		                    {
		                        
		                        // capturo el id de los creditos que tiene el participe
		                        $_id_creditos=$res->id_creditos;
		                        $_id_tipo_creditos=$res->id_tipo_creditos;
		                        $_nombre_tipo_creditos=$res->nombre_tipo_creditos.' #'.$res->id_creditos;
		                        $_fecha_concesion_creditos = $res->fecha_concesion_creditos;
		                        
		                       $total_saldo=   $participes->devuelve_saldo_capital($_id_creditos) ; //$this->Buscar_Cuotas_Actuales($_id_creditos);
		                       $total_mora=   $this->devuelve_saldo_mora($_id_creditos) ; //$this->Buscar_Cuotas_Actuales($_id_creditos);
		                       $saldo_interes = $this->devuelve_saldo_interes($_id_creditos);
		                	   $dias_interes =  $this->devuelve_interes_por_dias($_id_creditos);
		                       $saldo_seguros = $this->devuelve_saldo_seguro_desgravamen_incendio($_id_creditos);	       
		               		        
		                       $total_saldo_credito = $total_saldo +
		                       $total_mora  +
		                       $saldo_interes +
		                       $dias_interes +
		                       $saldo_seguros	;
		                        
		                       
		                       $html.='';
		                       
		                       
		                        
		                        
		                       $html.='<tr>
		                       			<td width="70%">'.$_nombre_tipo_creditos.'</td>
		                       			<td style="text-align: right;" width="30%"><span id="lblCreditoOrdinario"> $ '.number_format((float)$total_saldo_credito, 2, ',', '.').'</span></td>
		                       		  </tr>';
		                        
		                        
		                       $total_descuentos=$total_descuentos + $total_saldo + 
		                       					 $total_mora  + 
		                       					 $saldo_interes +
		                       					 $dias_interes + 
		                       					 $saldo_seguros	;
		                       
		                       
		                /*
		                       					 echo "Capital: " . $total_saldo . '   |||    ';
		                       echo "Mora: " . $total_mora . '   |||    ';
		                       echo "Saldo Intereses: " . $saldo_interes . '   |||    ';
		                       echo "dias Intereses: " . $dias_interes . '   |||    ';
		                       echo "Seguros: " . $saldo_seguros;
		                       
		                  */            
		                       
		                       					 
		                       					 
		                               
		                    }
		                }
							
		                    
		
		                
		                $total_recibir=$Total50PersonalMasRendimientos-$total_descuentos;
		                  
		                $html.='  
		                			<tr>
		                       			<th >TOTAL DESCUENTOS</th>
		                    			<td style="text-align: right;"><span id="lblTotalDescuentos"><b> $ '.number_format((float)$total_descuentos, 2, ',', '.').'</b></span></td>
		                    		</tr>
		                    	</table>
		                    
		                    	<div >
					              <h5 ><b>RECIBIR</b></h5>
					            </div>
		                    		
		                    	<table border="1" width="70%">
		                    	
		                    		<tr>
		                    			<th width="70%">TOTAL A RECIBIR</th>
		                    			<td style="text-align: right;" width="30%"> <font color="black"><span id="lblTotalRecibir"><b> $ '.number_format((float)$total_recibir, 2, ',', '.').'</b></span></font></td>
		                    		</tr>
		                    	</table>
		                    	</div>';
		                
		            
		                
		                
		                if ($total_recibir<0) ///no se puede desafiliar 
		                
		                {
		                    
		                    $total_pagar=$total_recibir*(-1);
		                    
		                    $html.='<div class="box box-solid bg-red" style = "margin-top:20px">
		                    <div class="box-header with-border">
		                    	<h3 class="box-title"><b>ALERTAS</b></h3>
		                    </div>
		                    
		                    <div>
		                    	<h4>
		                    		Estimado participe para poder acceder a la desafiliacion debe cubrir el monto adeudado en sus créditos a la fecha con el valor de: $ ' .number_format((float)$total_pagar, 2, ',', '.').
		                    	'</h4>
		                    </div>';
		                    
		                    
		               }
		               else   /// puede desafiliarse
		               {
		               	
		               	
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
        }else if( $_id_tipo_prestaciones == 1 ){
                       
            $html = $this->ValoresCesantia($id_participe);
            
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
                 $_interes_anterior= $res->interes_tabla_amortizacion;
                 
                   
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
 
    

    public function Buscar_Primera_Cuota($_id_creditos)
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
    
    
    
    
    	
    	$columnas="t.fecha_tabla_amortizacion, t.numero_pago_tabla_amortizacion, t.id_estado_tabla_amortizacion, t.balance_tabla_amortizacion";
    	$tablas="core_tabla_amortizacion t";
    	$where="t.id_creditos='$_id_creditos' ";
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
     
    public function Buscar_Estado_Credito($_id_creditos)
    {
    	
    	$estado_del_proceso ="";
    	
    	$participe= new ParticipesModel();
    	$anio_actual=date("Y");
    	$mes_actual=date("m");
    
    	$_id_estado_tabla_amortizacion=0;
    	$_balance_tabla_amortizacion=0;
    	$total_anterior=0;
    
    
    	$_fecha_tabla_amortizacion="";
    	$_fecha_tabla_amortizacion_anterior="";
    	$_fecha_tabla_amortizacion_adelantada="";
    
    
    
    	$_fecha_ultimo_pago_parcial = "";
    	$_fecha_ultimo_pago_cancelado = "";
    	$_fecha_ultimo_pago_activo = "";
    	 
    	//$fecha_cuota_actual = $participe->ultimo_dia_mes_actual();
    	$fecha_cuota_actual = date("d/m/Y", strtotime($participe->ultimo_dia_mes_actual()));
    	$fecha_cuota_actual2 = $participe->ultimo_dia_mes_actual();
    	$columnas=" core_tabla_amortizacion.id_tabla_amortizacion, 
				  core_tabla_amortizacion.id_creditos, 
				  core_tabla_amortizacion.fecha_tabla_amortizacion, 
				  core_tabla_amortizacion.numero_pago_tabla_amortizacion, 
				  core_tabla_amortizacion.capital_tabla_amortizacion, 
				  core_tabla_amortizacion.dividendo_tabla_amortizacion, 
				  core_tabla_amortizacion.interes_tabla_amortizacion, 
				  core_tabla_amortizacion.total_valor_tabla_amortizacion, 
				  core_tabla_amortizacion.balance_tabla_amortizacion, 
				  core_tabla_amortizacion.total_balance_tabla_amortizacion, 
				  core_tabla_amortizacion.provision_tabla_amortizacion, 
				  core_tabla_amortizacion.mora_tabla_amortizacion, 
				  core_tabla_amortizacion.id_estado_tabla_amortizacion, 
				  core_tabla_amortizacion.id_estatus, 
				  core_tabla_amortizacion.porcentaje_interes_tabla_amortizacion, 
				  core_tabla_amortizacion.numero_dias_tabla_amortizacion, 
				  core_tabla_amortizacion.fecha_servidor_tabla_amortizacion, 
				  core_tabla_amortizacion.seguro_incendios_tabla_amortizacion, 
				  core_tabla_amortizacion.seguro_desgravamen_tabla_amortizacion";
    	$tablas="core_tabla_amortizacion";
    	$where="id_creditos='$_id_creditos' AND  id_estatus = 1 ";
    	$id="numero_pago_tabla_amortizacion";
    	$resultSet=$participe->getCondiciones($columnas, $tablas, $where, $id);
    
    	if(!empty($resultSet)){
    
    		foreach ($resultSet as $res){
    
    
    			$_id_estado_tabla_amortizacion= $res->id_estado_tabla_amortizacion;
    
    			// verifico que la ultimo cuota esta cancelada
    			if($_id_estado_tabla_amortizacion==1) //PAGO PARCIAL
    			{
    				$_fecha_ultimo_pago_parcial = date("d/m/Y", strtotime($res->fecha_tabla_amortizacion));
    				$_fecha_ultimo_pago_parcial2 = $res->fecha_tabla_amortizacion;
    			}
    			if($_id_estado_tabla_amortizacion==2) //PAGO CANCELADO
    			{
    				$_fecha_ultimo_pago_cancelado = date("d/m/Y", strtotime($res->fecha_tabla_amortizacion));
    				$_fecha_ultimo_pago_cancelado2 = $res->fecha_tabla_amortizacion;
    			}
    			if($_id_estado_tabla_amortizacion==3) //PAGO ACTIVO
    			{
    				$_fecha_ultimo_pago_activo = date("d/m/Y", strtotime($res->fecha_tabla_amortizacion));
    				
    			} 
    			
    			/*echo "Estado: " . $res->id_estado_tabla_amortizacion;
    			echo "Fecha: " . $res->fecha_tabla_amortizacion;
    			*/
    		}
    		
    		
    		
    		
    		//   1 - Mora; 
    		//   2 - Adelantado
    		//   3 - Parcial
    		//   4 - Al dia
    		
    		if ($_fecha_ultimo_pago_cancelado != "" )
    		{
    			
    			$_fecha1 = new DateTime($_fecha_ultimo_pago_cancelado2);
    			$_fecha2 = new DateTime($fecha_cuota_actual2);
    			$interval=$_fecha2->diff($_fecha1);
    			$intervalMeses=$interval->format('%a');
    			$meses_dif = round($intervalMeses/30 , 2);
    			
    		
    			if ($meses_dif < 2) //al dia
    			{
    				$estado_del_proceso = 4; 
    				
    				if ($_fecha_ultimo_pago_parcial != "" )
    				{
    					$_fecha3 = new DateTime($_fecha_ultimo_pago_parcial2);
    					$_fecha4 = new DateTime($fecha_cuota_actual2);
    					if ($_fecha3 > $_fecha4)
    					{
    						$estado_del_proceso = 3;
    					}
    				}
    				
    			}
    			if ($meses_dif < 0) //en adelantado
    			{
    				$estado_del_proceso = 2;
    			}
    			if ($meses_dif > 2) //en mora
    			{
    				$estado_del_proceso = 1;
    			}
    			 
    			
    			
    		}
    		
    		
 
    		
    		
    		return  $estado_del_proceso;
    		
    		
    	
    			
    	}else{
    
    		// para los vencidos
    
    
    
    	}
    
    
    
    }
    
    public function at_interes_tabla_amortizacion($_id_creditos, $_fecha_ultima_cuota_cancelada)
    {
    	 
    	$estado_del_proceso ="";
    	 
    	$participe= new ParticipesModel();
    	$anio_actual=date("Y");
    	$mes_actual=date("m");
    	$_at_interes_tabla_amortizacion = 0;
    	$_at_mora_tabla_amortizacion;
    	
    	$_id_tabla_amortizacion;
    	$_id_creditos;
    	$_fecha_tabla_amortizacion;
    	$_numero_pago_tabla_amortizacion;
    	
    	$_seguro_incendios_tabla_amortizacion;
    	$_seguro_desgravamen_tabla_amortizacion;
    
  
    
    			
    	//$fecha_cuota_actual = $participe->ultimo_dia_mes_actual();
    	$fecha_cuota_actual = date("d/m/Y", strtotime($participe->ultimo_dia_mes_actual()));
    	$fecha_cuota_actual2 = $participe->ultimo_dia_mes_actual();
    	$columnas=" id_tabla_amortizacion,
			    	id_creditos,
			    	fecha_tabla_amortizacion,
			    	numero_pago_tabla_amortizacion,
			    	interes_tabla_amortizacion,
			    	mora_tabla_amortizacion
				  	seguro_incendios_tabla_amortizacion,
				  	seguro_desgravamen_tabla_amortizacion";
    	$tablas="core_tabla_amortizacion";
    	$where="id_creditos='$_id_creditos' AND  id_estatus = 1 AND fecha_tabla_amortizacion <='$_fecha_ultima_cuota_cancelada' AND core_tabla_amortizacion.id_estado_tabla_amortizacion = '3'  ";
    	$id="numero_pago_tabla_amortizacion";
    	$resultSet=$participe->getCondiciones($columnas, $tablas, $where, $id);
    
    	if(!empty($resultSet)){
    
    		foreach ($resultSet as $res){
    
    
    			$_id_estado_tabla_amortizacion= $res->id_estado_tabla_amortizacion;
    
    			
    			$_id_tabla_amortizacion = $res->id_tabla_amortizacion;
    			$_at_interes_tabla_amortizacion = $_at_interes_tabla_amortizacion + $res->interes_tabla_amortizacion;
    			$_at_mora_tabla_amortizacion	= $_at_mora_tabla_amortizacion	+ $res->mora_tabla_amortizacion;
    			
    		}
    
    		return  $_at_interes_tabla_amortizacion;
    
    
    		 
    		 
    	}else{
    
    		// para los vencidos
    
    
    
    	}
    
    
    
    }
    
    
    public function Buscar_fecha_Ultimo_Pago($_id_creditos)
    {
    	 
    	$estado_del_proceso ="";
    	 
    	$participe= new ParticipesModel();
    	$anio_actual=date("Y");
    	$mes_actual=date("m");
    
    	$_id_estado_tabla_amortizacion=0;
    	$_balance_tabla_amortizacion=0;
    	$total_anterior=0;
    
    
    
    	
    	
    	$_fecha_ultimo_pago_cancelado = "";
    	
    
    	//$fecha_cuota_actual = $participe->ultimo_dia_mes_actual();
    	$fecha_cuota_actual = date("d/m/Y", strtotime($participe->ultimo_dia_mes_actual()));
    	 
    	$columnas=" core_tabla_amortizacion.id_tabla_amortizacion,
				  core_tabla_amortizacion.id_creditos,
				  core_tabla_amortizacion.fecha_tabla_amortizacion,
				  ";
    	$tablas="core_tabla_amortizacion";
    	$where="id_creditos='$_id_creditos' AND  id_estatus = 1 ";
    	$id="numero_pago_tabla_amortizacion";
    	$resultSet=$participe->getCondicionesDesc($columnas, $tablas, $where, $id);
    
    	if(!empty($resultSet)){
    
    		foreach ($resultSet as $res){
    
    
    			$_id_estado_tabla_amortizacion= $res->id_estado_tabla_amortizacion;
    
    			// verifico que la ultimo cuota esta cancelada
    			
    			if($_id_estado_tabla_amortizacion==2) //PAGO CANCELADO
    			{
    				$_fecha_ultimo_pago_cancelado = date("d/m/Y", strtotime($res->fecha_tabla_amortizacion));
    			}
    			 
    		}
    
    		//   1 - Mora;
    		//   2 - Adelantado
    		//   3 - Parcial
    		//   4 - Al dia
    		
    		
    
    
    		 
    		 
    	}else{
    
    		// para los vencidos
    
    
    
    	}
    
    	return  $_fecha_ultimo_pago_cancelado;
    
    }
    
    
    
    public function devuelve_tasa_credito($id_creditos){
    
    	$creditos=new CoreCreditoModel();
    	$tasa_interes_credito=0;
    
    	$columnas_pag="interes_creditos";
    	$tablas_pag="public.core_creditos";
    	$where_pag="id_creditos='$id_creditos' ";
    
    	$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
    
    
    	if(!empty($resultPagos)){
    		 
    		 
    		$tasa_interes_credito=$resultPagos[0]->interes_creditos;
    		 
    		 
    	}
    
    
    
    	return $tasa_interes_credito;
    
    
    
    }
    
    
    public function devuelve_saldo_capital($id_creditos){
    
    	$creditos=new CoreCreditoModel();
    	$saldo_credito=0;
    	 
    	$columnas_pag="coalesce(sum(tap.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
    	$tablas_pag="core_creditos c
                        inner join core_tabla_amortizacion at on c.id_creditos=at.id_creditos
                        inner join core_tabla_amortizacion_pagos tap on at.id_tabla_amortizacion=tap.id_tabla_amortizacion
                        inner join core_tabla_amortizacion_parametrizacion tapa on tap.id_tabla_amortizacion_parametrizacion=tapa.id_tabla_amortizacion_parametrizacion";
    	$where_pag="c.id_creditos='$id_creditos' and c.id_estatus=1 and at.id_estatus=1 and tapa.tipo_tabla_amortizacion_parametrizacion=0";
    	 
    	$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
    	 
    	 
    	if(!empty($resultPagos)){
    		 
    		 
    		$saldo_credito=$resultPagos[0]->saldo;
    		 
    		 
    	}
    	 
    	 
    	 
    	return $saldo_credito;
    	 
    	 
    	 
    }
    
    
    public function devuelve_saldo_mora($id_creditos){
    
    	$creditos=new CoreCreditoModel();
    	$saldo_credito=0;
    
    	$columnas_pag="coalesce(sum(tap.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
    	$tablas_pag="core_creditos c
                        inner join core_tabla_amortizacion at on c.id_creditos=at.id_creditos
                        inner join core_tabla_amortizacion_pagos tap on at.id_tabla_amortizacion=tap.id_tabla_amortizacion
                        inner join core_tabla_amortizacion_parametrizacion tapa on tap.id_tabla_amortizacion_parametrizacion=tapa.id_tabla_amortizacion_parametrizacion";
    	$where_pag="c.id_creditos='$id_creditos' and c.id_estatus=1 and at.id_estatus=1 and tapa.tipo_tabla_amortizacion_parametrizacion=7";
    
    	$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
    
    
    	if(!empty($resultPagos)){
    		 
    		 
    		$saldo_credito=$resultPagos[0]->saldo;
    		 
    		 
    	}
    
    
    
    	return $saldo_credito;
    
    
    
    }
    
    

    public function devuelve_saldo_seguro_desgravamen_incendio($id_creditos){
    
    	$fecha_prestaciones = $_POST['fecha_prestaciones'];
    	
    	
    	 
    	$time_fecha_prestaciones = strtotime($fecha_prestaciones);
    	 
    	$month = date('m', $time_fecha_prestaciones);
    	$year = date('Y',$time_fecha_prestaciones);
    	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
    	 
    		$ultimo_dia_mes_proceso =  date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    	 
    	
    	
    	$creditos=new CoreCreditoModel();
    	$saldo_credito=0;
    
    	
    	$columnas_pag="coalesce(sum(core_tabla_amortizacion_pagos.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
    	$tablas_pag="public.core_tabla_amortizacion_pagos, 
					  public.core_tabla_amortizacion,
					  core_tabla_amortizacion_parametrizacion,
					  core_estado_tabla_amortizacion";
    	$where_pag = "core_estado_tabla_amortizacion.id_estado_tabla_amortizacion=core_tabla_amortizacion.id_estado_tabla_amortizacion
  AND core_tabla_amortizacion.id_tabla_amortizacion = core_tabla_amortizacion_pagos.id_tabla_amortizacion
  AND core_tabla_amortizacion_pagos.id_tabla_amortizacion_parametrizacion=core_tabla_amortizacion_parametrizacion.id_tabla_amortizacion_parametrizacion
  AND core_tabla_amortizacion_parametrizacion.tipo_tabla_amortizacion_parametrizacion=8
   AND  core_tabla_amortizacion.id_creditos = '$id_creditos'
   AND to_char(core_tabla_amortizacion.fecha_tabla_amortizacion, 'YYYY-MM-DD')<='$ultimo_dia_mes_proceso' 
   AND core_tabla_amortizacion.id_estatus=1";
    
    	$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
    
    
    	if(!empty($resultPagos)){
    		 
    		 
    		$saldo_credito=$resultPagos[0]->saldo;
    		 
    		 
    	}
    
    
    
    	return $saldo_credito;
    
    
    
    }
    
    public function devuelve_saldo_interes($id_creditos){
    
    	$creditos=new CoreCreditoModel();
    	$saldo_credito=0;
    	$fecha_prestaciones = $_POST['fecha_prestaciones'];
    	
    	$time_fecha_prestaciones = strtotime($fecha_prestaciones);
    	
    	$month = date('m', $time_fecha_prestaciones);
    	$year = date('Y',$time_fecha_prestaciones);
    	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
    	
    	$ultimo_dia_mes_proceso =  date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    	
    		$columnas_pag="coalesce(sum(core_tabla_amortizacion_pagos.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
    	$tablas_pag="public.core_tabla_amortizacion_pagos, 
					  public.core_tabla_amortizacion,
					  core_tabla_amortizacion_parametrizacion,
					  core_estado_tabla_amortizacion";
    	$where_pag = "core_estado_tabla_amortizacion.id_estado_tabla_amortizacion=core_tabla_amortizacion.id_estado_tabla_amortizacion
  AND core_tabla_amortizacion.id_tabla_amortizacion = core_tabla_amortizacion_pagos.id_tabla_amortizacion
  AND core_tabla_amortizacion_pagos.id_tabla_amortizacion_parametrizacion=core_tabla_amortizacion_parametrizacion.id_tabla_amortizacion_parametrizacion
  AND core_tabla_amortizacion_parametrizacion.tipo_tabla_amortizacion_parametrizacion=1
   AND  core_tabla_amortizacion.id_creditos = '$id_creditos'
   AND to_char(core_tabla_amortizacion.fecha_tabla_amortizacion, 'YYYY-MM-DD')<'$ultimo_dia_mes_proceso' 
   AND core_tabla_amortizacion.id_estatus=1";
    	
    	
    	
    	$resultPagos=$creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
    
    
    	if(!empty($resultPagos)){
    		 
    		 
    		$saldo_credito=$resultPagos[0]->saldo;
    		 
    		 
    	}
    
    	
    	
    	return $saldo_credito;
    
    
    
    }
        
    
    
    public function devuelve_interes_por_dias($_id_creditos){
    
    	$fecha_prestaciones = $_POST['fecha_prestaciones'];
    	 
    	$creditos=new CoreCreditoModel();
    	$_interes_ordinario=0;
    		
    
    	$_saldo_capital = $creditos->devuelve_saldo_capital($_id_creditos) ;
    	$_tasa_interes = $this->devuelve_tasa_credito($_id_creditos);
    	$_dias = date("d", strtotime($fecha_prestaciones));
    	//	$fecha_cuota_actual =
    	$_interes_ordinario = $creditos->devuelve_interes_ord_x_capital($_dias, $_saldo_capital, $_tasa_interes);
    	 
    
    	return $_interes_ordinario;
    
    
    
    }
    
    public function devuelve_sustento_solicitud($_id_solicitud){
    
    	require_once 'core/DB_Functions.php';
    	$db = new DB_Functions();
    	 
    	
    	
    	$_sustento_solicitud = "";
    	$columnas = "solicitud_prestaciones.fecha_presentacion,
        			bancos.nombre_bancos,
	  				solicitud_prestaciones.tipo_cuenta_bancaria,
	  				solicitud_prestaciones.numero_cuenta_bancaria,
	  				solicitud_prestaciones.identificador_consecutivos";
    	
    	$tablas   = "public.bancos,
  					public.solicitud_prestaciones";
    	
    	$where    = "solicitud_prestaciones.id_bancos = bancos.id_bancos
  						AND id_solicitud_prestaciones =".$_id_solicitud;
    	
    	$id = "solicitud_prestaciones.fecha_presentacion";
    	
    	$resultSet=$db->getCondiciones($columnas, $tablas, $where, $id);
    	
    	$_sustento_solicitud = $resultSet[0]->nombre_bancos.' ' .$resultSet[0]->tipo_cuenta_bancaria. ' ' .$resultSet[0]->numero_cuenta_bancaria ; 
        
    	
    		 
    	return $_sustento_solicitud;
    
    
    
    }
    
    
    
    public function devuelve_fecha_aprobacion_solicitud($_id_solicitud){
    
    	require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
     
        
    	$_fecha_solicitud = "";
    	$columnas = "
	  				solicitud_prestaciones.fecha_aprobacion";
    	 
    	$tablas   = "public.bancos,
  					public.solicitud_prestaciones";
    	 
    	$where    = "solicitud_prestaciones.id_bancos = bancos.id_bancos
  						AND id_solicitud_prestaciones =".$_id_solicitud;
    	$id = "solicitud_prestaciones.fecha_presentacion";
    	
    	
    	$resultSet=$db->getCondiciones($columnas, $tablas, $where, $id);
    	$_fecha_solicitud = $resultSet[0]->fecha_aprobacion ;
    	
    	
    	if ($_fecha_solicitud !="")
    	{
    		
    	}
    	else
    	{
    		$_fecha_solicitud = date("yy-m-d H:i:s");
    	}
    	
    	return $_fecha_solicitud;
    
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
    
 
    
    
    
    ////DESAFILIACION
    

    public function GuardaDesafiliacion()
    {
    	 
    	session_start();
    
    	echo "DESAFILIACION";
    	
    	$_fecha_concesion_creditos = "";
    
    	$html="";
    	$_id_creditos=0;
    	$_id_tipo_creditos=0;
    	$total_saldo=0;
    	$total_descuentos=0;
    	$total_recibir=0;
    	$total_pagar=0;
    	$id_participe= $_POST['id_participe'];
        $_id_solicitud=$_POST['id_solicitud'];
    	$fecha_prestaciones = $_POST['fecha_prestaciones'];
    	$_observacion_prestaciones = $_POST['observacion_prestaciones'];
    	$_id_tipo_prestaciones = $_POST['id_tipo_prestaciones'];
    	
    	
    	$participes= new ParticipesModel();
    
    
    	if ($_id_tipo_prestaciones == 2)
    	{
    		$columnas="
    				COALESCE(count(c.valor_personal_contribucion),0) cantidad_imposiciones,
    				COALESCE(sum(c.valor_personal_contribucion),0) aporte_personal_100,
							(coalesce(sum(c.valor_personal_contribucion),0)/2) aporte_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=5 and c1.id_estatus=1) as retroactivo_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=5 and c2.id_estatus=1) as retroactivo_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=7 and c1.id_estatus=1) as excedente_por_aporte_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=7 and c2.id_estatus=1) as excedente_por_aporte_personal_50,
		        			(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=9 and c1.id_estatus=1) as interes_por_aporte_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=9 and c2.id_estatus=1) as interes_por_aporte_personal_50,
							(select COALESCE(sum(c1.valor_personal_contribucion),0)  from core_contribucion c1 where c1.id_participes=".$id_participe." and c1.id_contribucion_tipo=10 and c1.id_estatus=1) as impuesto_ir_superavit_personal_100,
							(select (coalesce(sum(c2.valor_personal_contribucion),0)/2)  from core_contribucion c2 where c2.id_participes=".$id_participe." and c2.id_contribucion_tipo=10 and c2.id_estatus=1) as impuesto_ir_superavit_personal_50,
							(select COALESCE(sum(c3.valor_personal_contribucion),0)  from core_contribucion c3 where c3.id_participes=".$id_participe." and c3.id_contribucion_tipo=50 and c3.id_estatus=1) as superavit_aporte_personal_100,
							(select (coalesce(sum(c4.valor_personal_contribucion),0)/2)  from core_contribucion c4 where c4.id_participes=".$id_participe." and c4.id_contribucion_tipo=50 and c4.id_estatus=1) as superavit_aporte_personal_50,
							(select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_desde from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1 order by id_contribucion asc limit 1),
							(select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_hasta from core_contribucion c5 where c5.id_participes=".$id_participe." and c5.id_estatus=1  order by id_contribucion DESC limit 1)";
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
    
    					$Total50PersonalMasRendimientos =  $res->aporte_personal_50+$res->retroactivo_personal_50+$res->excedente_por_aporte_personal_50+$res->interes_por_aporte_personal_50+$res->impuesto_ir_superavit_personal_50+$res->superavit_aporte_personal_50;
    
    
    
    					$_cantidad_imposiciones =$res->cantidad_imposiciones;
    					$_imposiciones_desde=$res->imposicion_desde;
    					$_imposiciones_hasta=$res->imposicion_hasta;
    					$_aporte_personal_50=number_format((float)$res->aporte_personal_50, 2, ',', '.');
    					$_retroactivo_personal_50=number_format((float)$res->retroactivo_personal_50, 2, ',', '.');
    					$_excedente_por_aporte_personal_50=number_format((float)$res->excedente_por_aporte_personal_50, 2, ',', '.');
    					$_interes_por_aporte_personal_50=number_format((float)$res->interes_por_aporte_personal_50, 2, ',', '.');
    					$_impuesto_ir_superavit_personal_50=number_format((float)$res->impuesto_ir_superavit_personal_50, 2, ',', '.');
    					$_superavit_aporte_personal_50=number_format((float)$res->superavit_aporte_personal_50, 2, ',', '.');
    
    
    				}
    				
    
    				// CONSULTO LOS CREDITOS DE LOS PARTICIPES
    				$columnas="bb.fecha_concesion_creditos, bb.id_creditos,bb.id_tipo_creditos, tc.nombre_tipo_creditos, tc.codigo_tipo_creditos";
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
    					 
    					foreach($resultCreditos as $res)
    					{
    
    						// capturo el id de los creditos que tiene el participe
    						$_id_creditos=$res->id_creditos;
    						$_id_tipo_creditos=$res->id_tipo_creditos;
    						$_nombre_tipo_creditos=$res->nombre_tipo_creditos.' #'.$res->id_creditos;
    						$_fecha_concesion_creditos = $res->fecha_concesion_creditos;
    
    						$total_saldo=   $participes->devuelve_saldo_capital($_id_creditos) ; //$this->Buscar_Cuotas_Actuales($_id_creditos);
    						$total_mora=   $this->devuelve_saldo_mora($_id_creditos) ; //$this->Buscar_Cuotas_Actuales($_id_creditos);
    						$saldo_interes = $this->devuelve_saldo_interes($_id_creditos);
    						$dias_interes =  $this->devuelve_interes_por_dias($_id_creditos);
    						$saldo_seguros = $this->devuelve_saldo_seguro_desgravamen_incendio($_id_creditos);
    						 
    						$total_saldo_credito = $total_saldo +
    						$total_mora  +
    						$saldo_interes +
    						$dias_interes +
    						$saldo_seguros	;
    
    						 
    				
    
    						$total_descuentos=$total_descuentos + $total_saldo +
    						$total_mora  +
    						$saldo_interes +
    						$dias_interes +
    						$saldo_seguros	;
    						 
    						 
    					}
    				}
    					
    				$total_recibir=$Total50PersonalMasRendimientos-$total_descuentos;
    				if ($total_recibir<0) ///no se puede desafiliar
    
    				{
    
    					$total_pagar=$total_recibir*(-1);
    
    				
    				}
    				else   /// puede desafiliarse
    				{
    					
    					$_id_participes = $id_participe;
    					$_valor_neto_pagar_liquidacion_cabeza = $total_recibir;
    					$_cantidad_aportaciones_liquidacion_cabeza = $_cantidad_imposiciones;
    					$_id_status = 2; //inactivo;
    					$_observacion_liquidacion_cabeza = $_observacion_prestaciones;
    					$_id_tipo_prestaciones_g = $_id_tipo_prestaciones;
    					$_id_estado_prestaciones = 1; //Registrado ;
    					$_sustento_liquidacion_cabeza = $this->devuelve_sustento_solicitud($_id_solicitud);
    					$_carpeta_numero_liquidacion_cabeza = "00". $this->DevuelveConsecutivo();
    					$_file_number_liquidacion_cabeza =  $this->DevuelveConsecutivo();
    					$_fecha_entrada_carpeta_liquidacion_cabeza    = $this->devuelve_fecha_aprobacion_solicitud($_id_solicitud);		
    					$_user_name = $_SESSION["usuario_usuarios"];
    					$_id_usuarios = $_SESSION["id_usuarios"];
    					$_fecha_pago_carpeta_liquidacion_cabeza = "1901-01-01 00:00:01";
    					$_numero_documento_liquidacion_cabeza = 0;
    					$_fecha_entrada_liquidacion_cabeza = date("yy-m-d H:i:s");
    					$_numero_carpeta_liquidacion_cabeza = 0;
    					$_fecha_salida_liquidacion_cabeza = "1901-01-01 00:00:01";
    					$_id_liquidaciones_historico = 0;
    						
    					
    					  $resInsert = $this->InsertaLiquidacionCabeza($_id_participes,
    					
    							$_valor_neto_pagar_liquidacion_cabeza,
    							$_cantidad_aportaciones_liquidacion_cabeza,
    							$_id_status,
    							$_observacion_liquidacion_cabeza,
    							$_id_tipo_prestaciones,
    							$_id_estado_prestaciones,
    							$_sustento_liquidacion_cabeza,
    							$_carpeta_numero_liquidacion_cabeza,
    							$_file_number_liquidacion_cabeza,
    							$_fecha_entrada_carpeta_liquidacion_cabeza,
    							$_user_name,
    							$_id_usuarios,
    							$_fecha_pago_carpeta_liquidacion_cabeza,
    							$_numero_documento_liquidacion_cabeza,
    							$_fecha_entrada_liquidacion_cabeza,
    							$_numero_carpeta_liquidacion_cabeza,
    							$_fecha_salida_liquidacion_cabeza,
    							$_id_liquidaciones_historico);
    					
    				}
    
    
    			}else{
    			
    				////si valor
    				
    			}
    			 
    			 
    		}
    	}
    
    
    }
    
    
    public function InsertaLiquidacionCabeza($_id_participes,
    							$_valor_neto_pagar_liquidacion_cabeza,
    							$_cantidad_aportaciones_liquidacion_cabeza,
    							$_id_status,
    							$_observacion_liquidacion_cabeza,
    							$_id_tipo_prestaciones,
    							$_id_estado_prestaciones,
    							$_sustento_liquidacion_cabeza,
    							$_carpeta_numero_liquidacion_cabeza,
    							$_file_number_liquidacion_cabeza,
    							$_fecha_entrada_carpeta_liquidacion_cabeza,
    							$_user_name,
    							$_id_usuarios,
    							$_fecha_pago_carpeta_liquidacion_cabeza,
    							$_numero_documento_liquidacion_cabeza,
    							$_fecha_entrada_liquidacion_cabeza,
    							$_numero_carpeta_liquidacion_cabeza,
    							$_fecha_salida_liquidacion_cabeza,
    							$_id_liquidaciones_historico){
    		
    	
    	$resultado = null;
    	$liquidacion_cabeza=new LiquidacionCabezaModel();
    	$_array_roles=array();
    
    	if (isset(  $_SESSION['nombre_usuarios']) )
    	{
    
    
    		
    				$funcion = "ins_core_liquidacion_cabeza";
    				$parametros = "'$_id_participes',
    							'$_valor_neto_pagar_liquidacion_cabeza',
    							'$_cantidad_aportaciones_liquidacion_cabeza',
    							'$_id_status',
    							'$_observacion_liquidacion_cabeza',
    							'$_id_tipo_prestaciones',
    							'$_id_estado_prestaciones',
    							'$_sustento_liquidacion_cabeza',
    							'$_carpeta_numero_liquidacion_cabeza',
    							'$_file_number_liquidacion_cabeza',
    							'$_fecha_entrada_carpeta_liquidacion_cabeza',
    							'$_user_name',
    							'$_id_usuarios',
    							'$_fecha_pago_carpeta_liquidacion_cabeza',
    							'$_numero_documento_liquidacion_cabeza',
    							'$_fecha_entrada_liquidacion_cabeza',
    							'$_numero_carpeta_liquidacion_cabeza',
    							'$_fecha_salida_liquidacion_cabeza',
    							'$_id_liquidaciones_historico' ";
    				$liquidacion_cabeza->setFuncion($funcion);
    				$liquidacion_cabeza->setParametros($parametros);
    				
    
    				$resultado=$liquidacion_cabeza->llamafuncion();
    
    				$respuesta = '';
    
    				if(!empty($resultado) && count($resultado)){
    
    					foreach ($resultado[0] as $k => $v)
    					{
    						$respuesta=$v;
    					}
    
    					if (strpos($respuesta, 'OK') !== false) {
    
    						echo json_encode(array('success'=>1,'mensaje'=>$respuesta));
    					}else{
    						echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
    					}
    
    				}
    
    	}else{
    
    		echo json_encode(array('success'=>0,'mensaje'=>'Session Caducada vuelva a Ingresar'));
    
    	}
    
    }
    
    
    
    public function DevuelveConsecutivo()
    {
    
    	
    	$creditos= new CreditosModel();
    	$_valor_consecutivos = 0;
    		$columnas="id_consecutivos, id_entidades,  numero_consecutivos,
    		id_tipo_comprobantes, creado, modificado, sufijo_consecutivos,
    		espacio_consecutivos, valor_consecutivos ";
    		$tablas=" public.consecutivos";
    		$where="nombre_consecutivos = 'PRESTACIONES' ";
    		$id="id_consecutivos";
    
    		$resultSet=$creditos->getCondiciones($columnas, $tablas, $where, $id);
    			if(!empty($resultSet)){
    
    				foreach ($resultSet as $res)
    				{
    
    					$_valor_consecutivos =  $res->valor_consecutivos;
    				}
    		}
    	return $_valor_consecutivos;      
    
    }
    
    
    
    
    public function EleccionesParticipe()
    {
    	
    	$cedula=$_GET['cedula'];
    
    	
    	
    	$participes= new ParticipesModel();
    	$respuesta= array();
    
    	
    	
    	$_apellido_participes;
    	$_nombre_participes;
    	$_cedula_participes;
    	$_nombre_entidad_patronal;
    	$_acronimo_entidad_patronal;
    	$_codigo_entidad_patronal;
    	$_lugar_votacion_padron_electroal;
    	$_nombre_provincias;
    	$_tipo_padron_electoral;
    	$_id_genero_participes;
    	$_id_participes;
    	
    	
    	if(isset($_GET['cedula'])   && !empty(['cedula']))
    	{
    		$callBack = $_GET['jsoncallback'];
    		$columnas="core_participes.apellido_participes,
    			  core_participes.id_genero_participes,
				  core_participes.nombre_participes,
				  core_participes.cedula_participes,
				  core_entidad_patronal.nombre_entidad_patronal,
				  core_entidad_patronal.acronimo_entidad_patronal,
				  core_entidad_patronal.codigo_entidad_patronal,
				  padron_electroal.lugar_votacion_padron_electroal,
				  core_provincias.nombre_provincias,
    			  padron_electroal.tipo_padron_electoral,
    				core_participes.id_participes";
    		$tablas="public.core_participes,
				  public.core_entidad_patronal,
				  public.padron_electroal,
				  public.core_provincias,
				  public.core_participes_informacion_adicional";
    		
    		$where="  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
  					core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
  					core_participes.id_participes = padron_electroal.id_participes AND
  					core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
  					core_participes.cedula_participes='".$cedula."'";
    		
    		$id="padron_electroal.tipo_padron_electoral";
    		
    		 
    		$resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
    		
    		if(!(empty($resultSet)))
    		{
    			header('Content-Type: text/html; charset=UTF-8');
    			
    			$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    			$html.='<div class="card-body text-primary"> ';
    	
    			foreach ($resultSet as $res)
    			{
    				$_id_genero_participes = $res->id_genero_participes;
    				$_apellido_participes = 	$res->apellido_participes;
    				$_nombre_participes = 	$res->nombre_participes;
    				$_cedula_participes = 	$res->cedula_participes;
    				$_nombre_entidad_patronal = 	$res->nombre_entidad_patronal;
    				$_acronimo_entidad_patronal = 	$res->acronimo_entidad_patronal;
    				$_codigo_entidad_patronal = 	$res->codigo_entidad_patronal;
    				$_lugar_votacion_padron_electroal = 	$res->lugar_votacion_padron_electroal;
    				$_nombre_provincias = 	$res->nombre_provincias;
    				$_tipo_padron_electoral = 	$res->tipo_padron_electoral;
    				$_id_participes = $res->id_participes;
    				
    				///inserto la consulta
    				$funcion = "ins_padron_electoral_consultas";
    				$parametros = "'$_id_participes' , '$_cedula_participes' ";
    				$participes->setFuncion($funcion);
    				
    				
    				$participes->setParametros($parametros);
    				/*
    				
    				if ($_tipo_padron_electoral == "1")
    				{
    			
    					if ($_id_genero_participes = "1")
    					{
    						$html.='<p class="card-text">Estimado <b>'.$_apellido_participes. ' '.$_nombre_participes.
    						'</b>, su Entidad Patronal es <b>' . $_nombre_entidad_patronal.'</b>, la dirección registrada es en la provincia de <b>' .
    						$_nombre_provincias . '</b> y su lugar de votación es en <b>'. $_lugar_votacion_padron_electroal
    						 .' </b></p>' ;
    							
    					}
    					else
    					{
    						$html.='<p class="card-text">Estimada <b>'.$_apellido_participes. ' '.$_nombre_participes.
    						'</b>, su Entidad Patronal es <b>' . $_nombre_entidad_patronal.'</b>, la dirección registrada es en la provincia de <b>' .
    						$_nombre_provincias . '</b> y su lugar de votación es en <b>'. $_lugar_votacion_padron_electroal
    						 .' </b></p>' ;
    			
    							
    					}
    			
    				}
    				 
    				*/
    				
    				if ($_tipo_padron_electoral == "2")
    				{
    					//$html.='<h6 class="card-title"><b>!!! USTED PUEDE CALIFICARSE PARA REPRESENTANTE !!!</b></h6> ';
    					if ($_id_genero_participes = "1")
    					{
    						$html.='<p class="card-text">Queremos informarle que Usted cumple con todos los requisitos para calificarse como <b>REPRESENTANTE</b>
    							, para recibir la información necesaria debe contactarse al correo electrónico <b>elecciones@capremci.com.ec</b></p>' ;
    			
    					}
    					else
    					{
    						$html.='<p class="card-text">Queremos informarle que Usted cumple con todos los requisitos para calificarse como <b>REPRESENTANTE</b>
    							, para recibir la información necesaria debe contactarse al correo electrónico <b>elecciones@capremci.com.ec</b> </p>' ;
    			
    					}
    					$html.='<a href="#mostrarmodaleleccionesRegistro" data-dismiss="modal" data-toggle="modal"  id="btnRegistro" name="btnRegistro" class="btn btn-primary">Iniciar Proceso de Registro</a>';
    				}
    				
    				if ($_tipo_padron_electoral == "3")
    				{
    					//$html.='<h6 class="card-title"><b>!!! USTED PUEDE CALIFICARSE PARA REPRESENTANTE !!!</b></h6> ';
    					if ($_id_genero_participes = "1")
    					{
    						$html.='<p class="card-text">Estimado Representante Usted no puede postularse para candidato en cumplimiento a lo dispuesto en el numeral 21.2. del Artículo 20 de la Resolución No. 280-2016-F, el cual establece:</p>' ;
    						$html.='<p class="card-text">“<b>...21.2.</b> Los representantes con sus respectivos suplentes, serán elegidos por períodos de hasta dos (2) años, podrán ser reelegidos luego de transcurrido un período y po una sola vez más...”</p>' ;
    						
    					}
    					else
    					{
    						$html.='<p class="card-text">Estimada Representante Usted no puede postularse para candidato en cumplimiento a lo dispuesto en el numeral 21.2. del Artículo 20 de la Resolución No. 280-2016-F, el cual establece:</p>' ;
    						$html.='<p class="card-text">“<b>...21.2.</b> Los representantes con sus respectivos suplentes, serán elegidos por períodos de hasta dos (2) años, podrán ser reelegidos luego de transcurrido un período y po una sola vez más...”</p>' ;
    						 
    					}
    					
    				}
    		        
    				$html.='</div>';
    				
    		
    			}
    			
    			
    			
    			try
    			{
    				//$res = "Entre";
    				$resultado=$participes->llamafuncion();
    				$res = "Entre";
    			
    			}
    			catch (Exception $e)
    			{
    				$res =  "Captured Error: " . $e->getMessage();
    			}
    			
    			$respuesta = json_encode($html);
    				
    			echo $callBack."(".$respuesta.");";
    			
    		}
    		else
    		{
    			$html='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    			$html.='<div class="card-body text-danger"> ';
    			$html.='<p class="card-text">Estimado Usted no tiene capacidad de elegir en cumplimiento a lo dispuesto en el Artículo 5 del Reglamento de Elecciones, el cual establece:</p>' ;
    			$html.='<br>';
    			$html.='<p class="card-text">“<b>...Art. 5.</b>  Es partícipe con capacidad de elegir, quien, a la fecha de la convocatoria a la elección de Representantes, acredite al menos seis (6) aportaciones personales mensuales consecutivas en su cuenta individual...”</p>' ;
    			$html.='</div> ';
    			
    			$html.='<a href="http://turnosparticipes.capremci.com.ec"    id="btnTurnos" name="btnTurnos" class="btn btn-primary">Solicitar Turno En Línea</a>';
    			$respuesta = json_encode($html);
    			echo $callBack."(".$respuesta.");";
    			 
    			
    		}
    		
    		
    		
    	}
    		
    
    }
    

    

    public function BuscaRepresentante()
    {
    	 
    	$cedula=$_GET['cedula'];
    
    	 
    	 
    	$participes= new ParticipesModel();
    	$respuesta= array();
    
    	 
    	 
    	$_apellido_participes;
    	$_nombre_participes;
    	$_cedula_participes;
    	$_nombre_entidad_patronal;
    	$_acronimo_entidad_patronal;
    	$_codigo_entidad_patronal;
    	$_lugar_votacion_padron_electroal;
    	$_nombre_provincias;
    	$_tipo_padron_electoral;
    	$_id_genero_participes;
    	$_id_participes;
    	$_correo_participes;
    	 
    	 
    	if(isset($_GET['cedula'])   && !empty(['cedula']))
    	{
    		$callBack = $_GET['jsoncallback'];
    		$columnas="core_participes.apellido_participes,
    			  core_participes.id_genero_participes,
				  core_participes.nombre_participes,
				  core_participes.cedula_participes,
				  core_entidad_patronal.nombre_entidad_patronal,
				  core_entidad_patronal.acronimo_entidad_patronal,
				  core_entidad_patronal.codigo_entidad_patronal,
				  padron_electroal.lugar_votacion_padron_electroal,
				  core_provincias.nombre_provincias,
    			  padron_electroal.tipo_padron_electoral,
    				core_participes.id_participes,
    				core_participes.correo_participes";
    		$tablas="public.core_participes,
				  public.core_entidad_patronal,
				  public.padron_electroal,
				  public.core_provincias,
				  public.core_participes_informacion_adicional";
    
    		$where="  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
  					core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
  					core_participes.id_participes = padron_electroal.id_participes AND
  					core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
  					core_participes.cedula_participes='".$cedula."' AND tipo_padron_electoral='2' ";
    
    		$id="padron_electroal.tipo_padron_electoral";
    
    		 
    		$resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
    
    		if(!(empty($resultSet)))
    		{
    			 
    			$html='';
    			
    			$i = 0; 
    			foreach ($resultSet as $res)
    			{
    				$i++;
    				$_id_genero_participes = $res->id_genero_participes;
    				$_apellido_participes = 	$res->apellido_participes;
    				$_nombre_participes = 	$res->nombre_participes;
    				$_cedula_participes = 	$res->cedula_participes;
    				$_nombre_entidad_patronal = 	$res->nombre_entidad_patronal;
    				$_acronimo_entidad_patronal = 	$res->acronimo_entidad_patronal;
    				$_codigo_entidad_patronal = 	$res->codigo_entidad_patronal;
    				$_lugar_votacion_padron_electroal = 	$res->lugar_votacion_padron_electroal;
    				$_nombre_provincias = 	$res->nombre_provincias;
    				$_tipo_padron_electoral = 	$res->tipo_padron_electoral;
    				$_id_participes = $res->id_participes;
    				$_correo_participes = $res->correo_participes;
    				
    
    				$html.='<div class="form-row">';
    				$html.='<div class="form-group col-md-7" id="div_nombres_rep" >';
    				$html.='<label for="inputNombre"><b>Nombres y Apellidos</b></label>';
    				$html.='<input type="text" value="'.$_nombre_participes. ' '. $_apellido_participes .' " class="form-control" id="reg_nombres_representante" name="reg_nombres_representante"  placeholder="...nombres...">';
    				$html.='<input type="hidden" value="'.$_id_participes .' " class="form-control" id="reg_id_representante" name="reg_id_representante"  >';
    				$html.='</div>';
    				
    				
    				$html.='<div class="form-group col-md-5" id="div_email_rep" >';
    				$html.='<label for="inputCedula"><b>Correo Electrónico</b></label>';
    				$html.='<input type="email" value="' .$_correo_participes . ' " class="form-control" id="reg_email_representante" name="reg_email_representante"  placeholder="...email...">';
    				$html.='</div>';
    				 
    				$html.='</div>';
    
    				//array_push($respuesta, $html);
    				//array_push($respuesta, $resultSet[0]->id_participes);
    					
    					
    
    			}
    			 
    			
    			 
    			$respuesta = json_encode($html);
    
    			echo $callBack."(".$respuesta.");";
    			 
    		}
    		else 
    		{
    			$html = "";
    			$html.='<div class="form-row">';
    			$html.='<div class="form-group col-md-12" id="div_nombres_rep" >';
    			$html.='<span class="La cédula ingresada no corresponde a un partícipe calificado para ser representante">Danger</span>';
    			$html.='</div>';
    			 
    			$respuesta = json_encode($html);
    			
    			echo $callBack."(".$respuesta.");";
    			
    		}
    		
    
    
    	}
    
    
    }
    
    

    
    public function BuscaSuplente()
    {
    
    	$cedula=$_GET['cedula'];
    
    
    
    	$participes= new ParticipesModel();
    	$respuesta= array();
    
    
    
    	$_apellido_participes;
    	$_nombre_participes;
    	$_cedula_participes;
    	$_nombre_entidad_patronal;
    	$_acronimo_entidad_patronal;
    	$_codigo_entidad_patronal;
    	$_lugar_votacion_padron_electroal;
    	$_nombre_provincias;
    	$_tipo_padron_electoral;
    	$_id_genero_participes;
    	$_id_participes;
    	$_correo_participes;
    
    
    	if(isset($_GET['cedula'])   && !empty(['cedula']))
    	{
    		$callBack = $_GET['jsoncallback'];
    		$columnas="core_participes.apellido_participes,
    			  core_participes.id_genero_participes,
				  core_participes.nombre_participes,
				  core_participes.cedula_participes,
				  core_entidad_patronal.nombre_entidad_patronal,
				  core_entidad_patronal.acronimo_entidad_patronal,
				  core_entidad_patronal.codigo_entidad_patronal,
				  padron_electroal.lugar_votacion_padron_electroal,
				  core_provincias.nombre_provincias,
    			  padron_electroal.tipo_padron_electoral,
    				core_participes.id_participes,
    				core_participes.correo_participes";
    		$tablas="public.core_participes,
				  public.core_entidad_patronal,
				  public.padron_electroal,
				  public.core_provincias,
				  public.core_participes_informacion_adicional";
    
    		$where="  core_entidad_patronal.id_entidad_patronal = core_participes.id_entidad_patronal AND
  					core_provincias.id_provincias = core_participes_informacion_adicional.id_provincias AND
  					core_participes.id_participes = padron_electroal.id_participes AND
  					core_participes_informacion_adicional.id_participes = core_participes.id_participes AND
  					core_participes.cedula_participes='".$cedula."' AND tipo_padron_electoral='2' ";
    
    		$id="padron_electroal.tipo_padron_electoral";
    
    		 
    		$resultSet=$participes->getCondiciones($columnas, $tablas, $where, $id);
    
    		if(!(empty($resultSet)))
    		{
    
    			$html='';
    			 
    			$i = 0;
    			foreach ($resultSet as $res)
    			{
    				$i++;
    				$_id_genero_participes = $res->id_genero_participes;
    				$_apellido_participes = 	$res->apellido_participes;
    				$_nombre_participes = 	$res->nombre_participes;
    				$_cedula_participes = 	$res->cedula_participes;
    				$_nombre_entidad_patronal = 	$res->nombre_entidad_patronal;
    				$_acronimo_entidad_patronal = 	$res->acronimo_entidad_patronal;
    				$_codigo_entidad_patronal = 	$res->codigo_entidad_patronal;
    				$_lugar_votacion_padron_electroal = 	$res->lugar_votacion_padron_electroal;
    				$_nombre_provincias = 	$res->nombre_provincias;
    				$_tipo_padron_electoral = 	$res->tipo_padron_electoral;
    				$_id_participes = $res->id_participes;
    				$_correo_participes = $res->correo_participes;
    
    
    				$html.='<div class="form-row">';
    				$html.='<div class="form-group col-md-7" id="div_nombres_rep" >';
    				$html.='<label for="inputNombre"><b>Nombres y Apellidos</b></label>';
    				$html.='<input type="text" value="'.$_nombre_participes. ' '. $_apellido_participes .' " class="form-control" id="reg_nombres_suplente" name="reg_nombres_suplente"  placeholder="...nombres...">';
    				$html.='<input type="hidden" value="'.$_id_participes .' " class="form-control" id="reg_id_suplente" name="reg_id_suplente"  >';
    				$html.='</div>';
    					
    					
    
    
    				$html.='<div class="form-group col-md-5" id="div_email_rep" >';
    				$html.='<label for="inputCedula"><b>Correo Electrónico</b></label>';
    				$html.='<input type="email" value="' .$_correo_participes . ' " class="form-control" id="reg_email_suplente" name="reg_email_suplente"  placeholder="...email...">';
    				$html.='</div>';
    					
    
    
    				$html.='</div>';
    
    				//array_push($respuesta, $html);
    				//array_push($respuesta, $resultSet[0]->id_participes);
    					
    					
    
    			}
    
    			 
    
    			$respuesta = json_encode($html);
    
    			echo $callBack."(".$respuesta.");";
    
    		
    		}
    		else
    		{
    				$html='';
    				$html.='<div class="form-row">';
    				$html.='<div class="form-group col-md-12" id="div_error_rep" >';
    				$html.='<span class="label label-danger">La cédula ingresada no corresponde a un partícipe calificado para ser representante</span>';
    				$html.='</div>';
    	
    
    			$respuesta = json_encode($html);
    			 
    			echo $callBack."(".$respuesta.");";
    			 
    		}
    
    
    	}
    
    
    }
    

    
    
    
    public function RegistraRepresentante()
    {
    
    	$callBack = $_POST['jsoncallback'];
    	$_id_representante =(isset($_POST['id_representante'])) ? $_POST['id_representante'] : 0;
    	//$_id_suplente =(isset($_POST['id_suplente'])) ? $_POST['id_suplente'] : 0;
    
    	$_correo_representante =(isset($_POST['correo_representante'])) ? $_POST['correo_representante'] : 0;
    	//$_correo_suplente =(isset($_POST['correo_suplente'])) ? $_POST['correo_suplente'] : 0;
    	
    	$participes= new ParticipesModel();
    	$respuesta= array();
    	
    	
    	$res = "Sin Ejecutar";
    	
    	
    	if (isset($_FILES['foto_representante']['tmp_name'])!="")
    	{
    	
    		$directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/imagenes_representantes/';
    		 
    		$nombre = $_FILES['foto_representante']['name'];
    		$tipo = $_FILES['foto_representante']['type'];
    		$tamano = $_FILES['foto_representante']['size'];
    		move_uploaded_file($_FILES['foto_representante']['tmp_name'],$directorio.$nombre);
    		$data = file_get_contents($directorio.$nombre);
    		$_escritura_foto_representante = pg_escape_bytea($data);
    		 
    	}
    	/*
		if (isset($_FILES['foto_suplente']['tmp_name'])!="")
    	{
    		 
    		$directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/imagenes_representantes/';
    		$nombre = $_FILES['foto_suplente']['name'];
    		$tipo = $_FILES['foto_suplente']['type'];
    		$tamano = $_FILES['foto_suplente']['size'];
    		move_uploaded_file($_FILES['foto_suplente']['tmp_name'],$directorio.$nombre);
    		$data = file_get_contents($directorio.$nombre);
    		$_escritura_foto_suplente = pg_escape_bytea($data);
    		 
    	}
    	 */
    	
    	if($_id_representante > 0  )
    	{
    		$res = "Ejecutando";

    		$funcion = "ins_padron_electoral_representantes";
    		$parametros = "'$_id_representante' , '$_escritura_foto_representante',  '$_correo_representante' ";
    		$participes->setFuncion($funcion);
    		
    		
    		
    		$participes->setParametros($parametros);
    		
    		
    		try
    		{
    			//$res = "Entre";
    			$resultado=$participes->llamafuncion();
    			$res = "Entre";
    			 
    		}
    		catch (Exception $e)
    		{
    			$res =  "Captured Error: " . $e->getMessage();
    		}
    		 
    	}
    	
    	

    	$respuesta = json_encode($res);
    	echo $callBack."(".$respuesta.");";
    	die();
    	 
    	 
    
    }
    
    /*
	 
    public function RegistraRepresentante()
    {
    
    	$callBack = $_POST['jsoncallback'];
    	$_id_representante =(isset($_POST['id_representante'])) ? $_POST['id_representante'] : 0;
    	$_id_suplente =(isset($_POST['id_suplente'])) ? $_POST['id_suplente'] : 0;
    
    	$_correo_representante =(isset($_POST['correo_representante'])) ? $_POST['correo_representante'] : 0;
    	$_correo_suplente =(isset($_POST['correo_suplente'])) ? $_POST['correo_suplente'] : 0;
    	
    	$participes= new ParticipesModel();
    	$respuesta= array();
    	
    	
    	$res = "Sin Ejecutar";
    	
    	
    	if (isset($_FILES['foto_representante']['tmp_name'])!="")
    	{
    	
    		$directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/imagenes_representantes/';
    		 
    		$nombre = $_FILES['foto_representante']['name'];
    		$tipo = $_FILES['foto_representante']['type'];
    		$tamano = $_FILES['foto_representante']['size'];
    		move_uploaded_file($_FILES['foto_representante']['tmp_name'],$directorio.$nombre);
    		$data = file_get_contents($directorio.$nombre);
    		$_escritura_foto_representante = pg_escape_bytea($data);
    		 
    	}
    	if (isset($_FILES['foto_suplente']['tmp_name'])!="")
    	{
    		 
    		$directorio = $_SERVER['DOCUMENT_ROOT'].'/rp_c/imagenes_representantes/';
    		$nombre = $_FILES['foto_suplente']['name'];
    		$tipo = $_FILES['foto_suplente']['type'];
    		$tamano = $_FILES['foto_suplente']['size'];
    		move_uploaded_file($_FILES['foto_suplente']['tmp_name'],$directorio.$nombre);
    		$data = file_get_contents($directorio.$nombre);
    		$_escritura_foto_suplente = pg_escape_bytea($data);
    		 
    	}
    	 
    	
    	if($_id_representante > 0 && $_id_suplente > 0 )
    	{
    		$res = "Ejecutando";

    		$funcion = "ins_padron_electoral_representantes";
    		$parametros = "'$_id_representante' , '$_id_suplente', '$_escritura_foto_representante', '$_escritura_foto_suplente', '$_correo_representante', '$_correo_suplente' ";
    		$participes->setFuncion($funcion);
    		
    		
    		
    		$participes->setParametros($parametros);
    		
    		
    		try
    		{
    			//$res = "Entre";
    			$resultado=$participes->llamafuncion();
    			$res = "Entre";
    			 
    		}
    		catch (Exception $e)
    		{
    			$res =  "Captured Error: " . $e->getMessage();
    		}
    		 
    	}
    	
    	

    	$respuesta = json_encode($res);
    	echo $callBack."(".$respuesta.");";
    	die();
    	 
    	 
    
    }
    
	
	*/
    
    public function ValoresCesantia($id = null){
        
        $html = "";
        
        if( $id == null ) return "<error>";
        
        $id_participe   = $id;
        $cesantia   = new CesantiasModel();
        
        $col1   = " coalesce( sum(valor_personal_contribucion), 0 ) as aporte_personal,
            ( select coalesce( sum(valor_personal_contribucion), 0 )
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 9 ) as interes_personal,
            ( select coalesce( sum(valor_personal_contribucion), 0 )
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 7 ) as exedente_personal,
			( select coalesce( sum(valor_personal_contribucion), 0 )
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 50 ) as superavit_personal,
            ( select coalesce( sum(valor_personal_contribucion), 0 )  
        	from core_contribucion
        	where id_estatus = 1 
        	and id_participes = $id_participe and id_contribucion_tipo = 10 ) as ir_superavit_personal,
            ( select coalesce( sum(valor_personal_contribucion), 0 )
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 5 ) as retroactivo_personal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 ) 
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 3 ) as aporte_patronal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 ) 
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 2 ) as interes_patronal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 ) 
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 8 ) as exedente_patronal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 ) 
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 49 ) as superavit_patronal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 ) 
        	from core_contribucion
        	where id_estatus = 1 
        	and id_participes = $id_participe and id_contribucion_tipo = 12 ) as ir_superavit_patronal,
            ( select coalesce( sum(valor_patronal_contribucion), 0 )
            from core_contribucion
            where id_estatus = 1
            and id_participes = $id_participe and id_contribucion_tipo = 6 ) as retroactivo_patronal,
            (select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_desde
            from core_contribucion c5
            where c5.id_participes= $id_participe and c5.id_estatus=1 order by id_contribucion asc limit 1),
            (select to_char(c5.fecha_registro_contribucion,'TMMONTH/YYYY') imposicion_hasta
            from core_contribucion c5
            where c5.id_participes= $id_participe and c5.id_estatus=1  order by id_contribucion desc limit 1)";							
        $tab1   = "core_contribucion c inner join  core_participes p on c.id_participes=p.id_participes";
        $whe1   = "p.id_participes=".$id_participe." and c.id_estatus=1 and c.id_contribucion_tipo=1";
        $id1    = " 1";
        
        $rsCesantias = $cesantia->getCondiciones($col1, $tab1, $whe1, $id1);
                
        if(empty($rsCesantias))  return "";
            
        $SumaValores    = 0;
        $valor_superavit_patronal   = 0;
        foreach ($rsCesantias as $res)
        {
            
            $SumaValores =  (($res->aporte_personal)/2) + (($res->interes_personal)/2) + (($res->exedente_personal)/2) + (($res->superavit_personal)/2);
            
            $_imposiciones_desde=$res->imposicion_desde;
            $_imposiciones_hasta=$res->imposicion_hasta;
            
            $aporte_personal    = ($res->aporte_personal);
            $aporte_personal_format    = number_format( (float) ($aporte_personal) , 2, ',', '.');
            $retroactivo_personal   = $res->retroactivo_personal;
            $retroactivo_personal_format    = number_format((float) ($retroactivo_personal), 2, ',', '.');
            $excedente_por_aporte_personal  = $res->exedente_personal;
            $excedente_por_aporte_personal_format  = number_format((float) ($excedente_por_aporte_personal), 2, ',', '.');
            $interes_por_aporte_personal    = $res->interes_personal;
            $interes_por_aporte_personal_format    = number_format((float) ($interes_por_aporte_personal), 2, ',', '.');
            //$impuesto_superavit_personal=number_format((float)$res->ir_superavit_personal, 2, ',', '.');
            $superavit_aporte_personal  = ($res->superavit_personal) - abs($res->ir_superavit_personal);
            $superavit_aporte_personal_format  = number_format((float) ( $superavit_aporte_personal ), 2, ',', '.');
            
            $SumaValoresPersonales =  $aporte_personal + $retroactivo_personal + $excedente_por_aporte_personal + $interes_por_aporte_personal + $superavit_aporte_personal;
            
            $aporte_patronal    = ($res->aporte_patronal);
            $retroactivo_patronal   = $res->retroactivo_patronal;
            $excedente_por_aporte_patronal  = ($res->exedente_patronal);
            $interes_por_aporte_patronal    = ($res->interes_patronal);
            $superavit_aporte_patronal  = ($res->superavit_patronal);
            $aporte_patronal_format    = number_format( (float) ($aporte_patronal) , 2, ',', '.');            
            $retroactivo_patronal_format   = number_format((float) $retroactivo_patronal, 2, ',', '.');            
            $excedente_por_aporte_patronal_format  = number_format((float) ($excedente_por_aporte_patronal), 2, ',', '.');            
            $interes_por_aporte_patronal_format    = number_format((float) ($interes_por_aporte_patronal), 2, ',', '.');            
            $superavit_aporte_patronal_format  = number_format((float) ($superavit_aporte_patronal), 2, ',', '.');
            
            $SumaValoresPatronales =  $aporte_patronal + $retroactivo_patronal + $excedente_por_aporte_patronal + $interes_por_aporte_patronal + $superavit_aporte_patronal;
            $valor_superavit_patronal   = ($res->superavit_patronal);
            $total_prestacion   = $SumaValoresPersonales + $SumaValoresPatronales;
            
            $SumaValores = $total_prestacion;
        }
            
            $html='<div >
                <div >
                <h3 class="box-title"><b>SIMULACIÓN: CÁLCULO DE CESANTIA</b></h3>
                </div>
                <div >
                <div >
                <h5><b>IMPOSICIONES DESDE:</b> '.$_imposiciones_desde.'<b> HASTA:</b> '.$_imposiciones_hasta.'</h5>
                </div>
                <div style="align-content: center;">
                <table  border="1" width="70%">
                <tr >
                <th></th>
                </tr>';
                        
            if( $aporte_personal > 0 ){                
                $html .=' <tr>
                    	<td width="70%" >Aporte Personal</td>
                    	<td style="text-align: right;"  width="30%"><span id="lblAportePersonal"> $ '.$aporte_personal_format.'</span></td>
                    </tr>';
            }
            
            if( $retroactivo_personal > 0 ){
                $html .=' <tr>
                    	<td width="70%" >Retroactivo Personal</td>
                    	<td style="text-align: right;"  width="30%"><span id="lblAportePersonal"> $ '.$retroactivo_personal_format.'</span></td>
                    </tr>';
            }
            
            if( $excedente_por_aporte_personal > 0 ){
                $html .=' <tr>
                    	<td width="70%" >Excedente por Aporte Personal</td>
                    	<td style="text-align: right;"  width="30%"><span id="lblAportePersonal"> $ '.$excedente_por_aporte_personal_format.'</span></td>
                    </tr>';
            }
            
            if( $interes_por_aporte_personal > 0 ){
                $html .=' <tr>
                	<td width="70%" >Interés Aporte Personal </td>
                	<td style="text-align: right;"  width="30%"><span id="lblInteresAportePersonal"> $ '.$interes_por_aporte_personal_format.'</span></td>
                </tr>';
            }
            
            if( $superavit_aporte_personal > 0 ){
                $html .='  <tr >
                	<td >Superavit por Aporte Personal</td>
                	<td style="text-align: right;"><span id="lblSuperavitAportePersonal"> $ '.$superavit_aporte_personal_format.'</span></td>
                </tr>';
            }
                             
            $html .='<tr>
            	<th >Total  Aporte Personal</th>
            	<td style="text-align: right;"><span id="lblTotalSuma"><b> $ '.number_format((float)$SumaValoresPersonales, 2, ',', '.').'</b></span></td>
            </tr>';
            
            if( $aporte_patronal > 0 ){
                $html .='<tr>
                	<td width="70%" >Aporte Patronal</td>
                	<td style="text-align: right;"  width="30%"><span id="lblAportePersonal"> $ '.$aporte_patronal_format.'</span></td>
                </tr>';
            }
            
            if( $retroactivo_patronal > 0 ){
                $html .=' <tr>
                	<td width="70%" >Retroactivo Patronal </td>
                	<td style="text-align: right;"  width="30%"><span id="lblExcedenteAportePersonal"> $ '.$retroactivo_patronal_format.'</span></td>
                </tr>';
            }
            
            if( $excedente_por_aporte_patronal > 0 ){
                $html .=' <tr>
                	<td width="70%" >Excedente por Aporte Patronal </td>
                	<td style="text-align: right;"  width="30%"><span id="lblExcedenteAportePersonal"> $ '.$excedente_por_aporte_patronal_format.'</span></td>
                </tr>';
            }
            
            if( $interes_por_aporte_patronal > 0 ){
                $html .=' <tr>
                	<td width="70%" >Interés Aporte Patronal </td>
                	<td style="text-align: right;"  width="30%"><span id="lblInteresAportePersonal"> $ '.$interes_por_aporte_patronal_format.'</span></td>
                </tr>';
            }
            
            if( $superavit_aporte_patronal > 0 ){
                $html .=' <tr >
                	<td >Superavit por Aporte Patronal</td>
                	<td style="text-align: right;"><span id="lblSuperavitAportePersonal"> $ '.$superavit_aporte_patronal_format.'</span></td>
                </tr>';
            }
            
           $html .='<tr >
                	<th >Total  Aporte Patronal</th>
                	<td style="text-align: right;"><span id="lblTotalSuma"><b> $ '.number_format((float)$SumaValoresPatronales, 2, ',', '.').'</b></span></td>
                </tr>
                <tr >
                	<th >TOTAL PRESTACION</th>
                	<td style="text-align: right;"><span id="lblTotalSuma"><b> $ '.number_format((float)$total_prestacion, 2, ',', '.').'</b></span></td>
                </tr>
                </table>
                </div>
                </div>';
            
            #Ingresar a ver descuentos
            $html.=' <div > <h5 ><b>DESCUENTOS</b></h5> </div>';
            
            #VALORES DESCUENTOS
            $totalDescuentos    = 0;
            
            if( $valor_superavit_patronal > 0 ){
                
                $impuesto_2_superavit_patronal  = $valor_superavit_patronal * (2/100);
                
                $html   .= '<table border="1" width="70%"> ';
                $html   .= '<tr>
                   			<td width="70%">Impuesto 2.00% Superavit Patronal</td>
                   			<td style="text-align: right;" width="30%"><span id="lblCreditoOrdinario"> $ -'.number_format((float)$impuesto_2_superavit_patronal, 2, ',', '.').'</span></td>
                   		  </tr>';
                $html   .= '</table>';
                
                $totalDescuentos    += $impuesto_2_superavit_patronal;
            }
            
            
            
            #Consulto creditos            
            $col2  = " aa.id_creditos, aa.id_tipo_creditos, bb.nombre_tipo_creditos";
            $tab2  = " public.core_creditos aa INNER JOIN public.core_tipo_creditos bb ON  bb.id_tipo_creditos = aa.id_tipo_creditos";
            $whe2  = " aa.id_estado_creditos = 4 AND aa.id_estatus = 1 AND aa.id_participes = $id_participe"; 
            $rs_Consulta2  = $cesantia->getCondicionesSinOrden($col2, $tab2, $whe2, "");
            
            $html_creditos  = "";
            $valor_total_creditos   = 0;
            
            if( !empty($rs_Consulta2) ){
                
               
                $html   .= '<table border="1" width="70%">'; 
                
                $arrayRespuestaCreditos = $this->obtenerCreditosParticipe($id_participe);
                
                if( !empty( $arrayRespuestaCreditos ) ){
                    
                    $numero_creditos    = $arrayRespuestaCreditos['numcreditos'];
                    $valor_total_creditos   = $arrayRespuestaCreditos['valcreditos'];
                    $html_creditos      = $arrayRespuestaCreditos['html'];
                    
                    $html.='<tr>
                   			<td width="70%"> Total Creditos ('.$numero_creditos.')</td>
                   			<td style="text-align: right;" width="30%"><span id="lblCreditoOrdinario"> $ -'.number_format((float)$valor_total_creditos, 2, ',', '.').'</span></td>
                   		  </tr>';                    
                    
                }
                
                $totalDescuentos += $valor_total_creditos;
                
               
                //foreach ( $rs_Consulta2 as $res ){
                    
                    //$nombre_tipo_creditos   = $res->nombre_tipo_creditos;
                    //$fecha_reporte  = date('Y-m-d');
                    //$id_creditos    = $res->id_creditos;
                    //$paramsQuery   = " $id_creditos, '$fecha_reporte', 1000000, '1' ";
                    
                    //$col3  = " bb.descripcion_tabla_amortizacion_parametrizacion,id_tabla_amortizacion_parametrizacion_out, valor_out  ";
                   // $tab3  = " fc_simular_pago_credito_por_fecha($paramsQuery) aa
                    //INNER JOIN core_tabla_amortizacion_parametrizacion bb ON bb.id_tabla_amortizacion_parametrizacion = aa.id_tabla_amortizacion_parametrizacion_out";
                    //$whe3  = " 1 = 1 ";
                    
                    //$totalparcial    = 0;
                    //$rs_Consulta3  = $cesantia->getCondicionesSinOrden($col3, $tab3, $whe3, "");
                    
                    //foreach ( $rs_Consulta3 as $res3 ){
                    //    $totalparcial   += $res3->valor_out;
                    //}
                    
                    
                    
                    //$totalDescuentos    += $totalparcial;
                    
                    #para traer detalles de credito
                    //$html_creditos  = $this->obtenerCreditosParticipe($id_participe);
                //}
                
                $html.='<tr>
                          <td ></td>
	                      <td style="text-align: right;" ><button id="btn_pago_creditos" onclick="fn_cancelacion_prestamo()" class=" btn btn-info"><i class="fa fa-external-link-square"></i>  Pago Creditos</button></td>
               		  </tr>';
                
                $total_recibir  = $SumaValores - $totalDescuentos;
                                
                $html.='<tr>
		                      <th >TOTAL DESCUENTOS</th>
		                      <td style="text-align: right;"><span id="lblTotalDescuentos"><b> $ '.number_format((float)$totalDescuentos, 2, ',', '.').'</b></span></td>
		                </tr>
		               </table>		                    			    
                    	<div >
			              <h5 ><b>RECIBIR</b></h5>
			            </div>		                    			    
		                <table border="1" width="70%">
		                  <tr>
                			<th width="70%">TOTAL A RECIBIR</th>
                			<td style="text-align: right;" width="30%"> <font color="black"><span id="lblTotalRecibir"><b> $ '.number_format((float)$total_recibir, 2, ',', '.').'</b></span></font></td>
                		  </tr>
		                 </table>
		                </div>';
                
                if( !empty($html_creditos) ){
                    
                    $html.= $html_creditos;
                    
                }
                
                if($total_recibir < 0 ){
                    
                    $total_pagar=$total_recibir*(-1);
                    
                    $html.='<div class="box box-solid bg-red" style = "margin-top:20px">
		                    <div class="box-header with-border">
		                    	<h3 class="box-title"><b>ALERTAS</b></h3>
		                    </div>                        
		                    <div>
		                    	<h4>
		                    		Estimado participe para poder acceder a la desafiliacion debe cubrir el monto adeudado en sus créditos a la fecha con el valor de: $ ' .number_format((float)$total_pagar, 2, ',', '.').
		                    		'</h4>
		                    </div>';
                    
                }
                
                
            }
               
        
        return $html;
    }
    
    
    /************************************************************************************************************************************************************/
    public function ObtenerDatosParticipe()
    {
        session_start();
        $cedula=$_POST['identificacion'];
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
               
        
        if( !(empty($resultSet) ) ){ 
            
            if($resultSet[0]->nombre_genero_participes == "HOMBRE"){
                $icon='<i class="fa fa-male fa-3x" style="float: left;"></i>';
            }else{
                $icon='<i class="fa fa-female fa-3x" style="float: left;"></i>';
            }
       
        
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
            
                $respuesta['html']  = $html;
                $respuesta['id_participes']  = $resultSet[0]->id_participes;
                
                echo json_encode($respuesta);
                
        }else{
            
            echo "<error>No existen datos<error>";
        }
        
        
    }
    
    /**
     * *
     * fn para obtener listado de creditos a renovar
     * dc 2021-03-16
     */
    public function obtenerCreditosParticipe( $identificador = null, $fecha = null)
    {
        //session_start();
        ob_start();
        $id_participe = $identificador;
        $fecha  = (is_null($fecha)) ? date('Y-m-d') : $fecha ;                 
        $rp_capremci = new ParticipesModel();
        
        $respuesta  = array();
        
        $total = 0.00;
        $html = '
        <br>
        <div class="letrasize11">
        <table width="70%" border="1" class="table-condensed" >
        <tr>
        <th colspan="9" style="text-align:center">VALORES CREDITOS ADEUDADOS PARTICIPE</th>
        </tr>
        <tr>
        <th >№ DE PRESTAMO</th>
        <th >FECHA DE PRESTAMO</th>
        <th >MONTO CREDITO</th>
        <th >CAPITAL</th>
        <th >INTERES</th>
        <th >INT. POR MORA</th>
        <th >OTROS</th>
        <th>TIPO CREDITO</th>
        <th >SALDO TOTAL</th>
        </tr>';
        
        
       
        $columnas = 'aa.id_creditos, aa.numero_creditos, aa.fecha_concesion_creditos, cc.nombre_tipo_creditos, aa.monto_otorgado_creditos, aa.saldo_actual_creditos,
            aa.interes_creditos, dd.nombre_estado_creditos';
        $tablas = 'public.core_creditos aa
            INNER JOIN public.core_participes bb ON bb.id_participes = aa.id_participes
            INNER JOIN public.core_tipo_creditos cc ON cc.id_tipo_creditos = aa.id_tipo_creditos
            INNER JOIN public.core_estado_creditos dd ON dd.id_estado_creditos = aa.id_estado_creditos';
        
        $where = " upper(dd.nombre_estado_creditos) = 'ACTIVO' AND aa.id_participes=" . $id_participe . " AND aa.id_estatus=1 ";
        
        
        $rsCreditos = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $count_creditos_renovar = 0;
        $valcreditos    = 0;
        
        foreach ($rsCreditos as $res1) {
            $count_creditos_renovar ++;
            $total += $res1->saldo_actual_creditos;
            //$saldo = number_format((float) $res1->saldo_actual_creditos, 2, '.', '');
            $valor_parcial  = 0;
            
            #PARA TRAER VALORES TOTALES
            //tipo pago 1 total -- 0 parcial
            $tipo_pago  = 1;
            $id_creditos    = $res1->id_creditos;
            $col1   = 'SUM(CASE WHEN bb.tipo_tabla_amortizacion_parametrizacion = 0 THEN aa.valor_out ELSE 0 END) as capital,
                SUM(CASE WHEN bb.tipo_tabla_amortizacion_parametrizacion = 1 THEN aa.valor_out ELSE 0 END) as interes,
                SUM(CASE WHEN bb.tipo_tabla_amortizacion_parametrizacion = 7 THEN aa.valor_out ELSE 0 END) as mora,
                SUM(CASE WHEN bb.tipo_tabla_amortizacion_parametrizacion = 8 THEN aa.valor_out ELSE 0 END) as otros';
            $tab1   = "fc_simular_pago_credito_por_fecha('$id_creditos','$fecha',1000,'$tipo_pago') aa
                INNER JOIN core_tabla_amortizacion_parametrizacion bb ON bb.id_tabla_amortizacion_parametrizacion = aa.id_tabla_amortizacion_parametrizacion_out";            
            $whe1   = " 1 = 1 ";
            
            $rsDetalles = $rp_capremci->getCondicionesSinOrden($col1, $tab1, $whe1, "");
            
            //print_r( pg_last_error());
            //die('error');
           
            
            
            $capital    = $interes  = $mora = $otros    = 0;
            
            if( !empty($rsDetalles) ){
                
                $capital    = $rsDetalles[0]->capital;
                $interes    = $rsDetalles[0]->interes;
                $mora       = $rsDetalles[0]->mora;
                $otros      = $rsDetalles[0]->otros;
                               
            }          
            
            $valor_parcial = $capital + $interes + $mora + $otros;
            
            $html .= '<tr>
             <td >' . $res1->numero_creditos . '</font></td>
             <td >' . $res1->fecha_concesion_creditos . '</font></td>
             <td >' . $res1->monto_otorgado_creditos . '</font></td>
             <td >' . $capital . '</font></td>
             <td >' . $interes . '</font></td>
             <td >' . $mora . '</font></td>
             <td >' . $otros . '</font></td>
             <td>' . $res1->nombre_tipo_creditos . '</td>
             <td align="right" class="ls_saldo_credito">' . $valor_parcial . '</font></td>
            </tr>';
            
            $valcreditos += $valor_parcial;
        }        
        
        $total = number_format((float) $valcreditos, 2, '.', '');
        $html .= '<tr>
        <th ></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th >Total:</th>
        <td align="right" id="total_saldo_renovar">' . $total . '</td>
        </tr>';
        
        $html .= '</table> </div>';
        
        $respuesta['html']  = $html;
        $respuesta['numcreditos']  = $count_creditos_renovar;
        $respuesta['valcreditos']  = $valcreditos;
                
        $salida = ob_get_clean();
        if (! empty($salida)) {
            $respuesta = array();
            
        }
        
        return $respuesta;
    }
    
    public function validarDatosCreditos(){
        
        session_start();
        $prestaciones  = new PrestacionesModel();
        
        $respuesta  = array();
        
        try {
            
            $id_participe   = $_POST['id_participes'];
            
            if( !empty(error_get_last()) ) throw new Exception('parametro no definido');
            
            $col1   = "";
            $tab1   = "";
            $whe1   = "";
            $id1    = "";

        } catch (Exception $e) {
        }
        
    }
    
    
}


?>