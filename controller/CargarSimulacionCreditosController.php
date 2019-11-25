<?php
class CargarSimulacionCreditosController extends ControladorBase{
    public function index(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("SimulacionCreditos",array(
            "result" => ""
        ));
    }
    
    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%y Años, %m Meses, %d Dias' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    
    public function dateDifference1($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
        
    }
    
    public function getTipoCredito()
    {
        session_start();
        $rp_capremci= new PlanCuentasModel();
        $columnas="codigo_tipo_creditos, nombre_tipo_creditos";
        $tablas="core_tipo_creditos INNER JOIN estado
                ON core_tipo_creditos.id_estado = estado.id_estado";
        $where="id_estatus=1 AND nombre_estado='ACTIVO'";
        $id="id_tipo_creditos";
        $resultSet=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='<label for="tipo_credito" class="control-label">Seleccione el Tipo de Crédito:</label>
        <select name="tipo_credito" id="tipo_credito"  class="form-control"onchange="TipoCredito()">
        <option value="" selected="selected">--Seleccione--</option>';
        foreach ($resultSet as $res)
        {
            $html.='<option value="'.$res->codigo_tipo_creditos.'" >'.$res->nombre_tipo_creditos.'</option>';
        }
        
        $html.='</select>'; 
        
        echo $html;
    }
    
    public function getTipoCredito1()
    {
        session_start();
        $rp_capremci= new PlanCuentasModel();
        $columnas="codigo_tipo_creditos, nombre_tipo_creditos";
        $tablas="core_tipo_creditos INNER JOIN estado
                ON core_tipo_creditos.id_estado = estado.id_estado";
        $where="id_estatus=1 AND nombre_estado='ACTIVO'";
        $id="id_tipo_creditos";
        $resultSet=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='<label for="tipo_credito" class="control-label">Tipo Créditos:</label>
        <select name="tipo_credito" id="tipo_credito"  class="form-control">
        <option value="" selected="selected">--Seleccione--</option>';
        foreach ($resultSet as $res)
        {
            $html.='<option value="'.$res->codigo_tipo_creditos.'" >'.$res->nombre_tipo_creditos.'</option>';
        }
        
        $html.='</select>';
        
        echo $html;
    }
    
    public function GetInfoCreditoRenovar()
    {
        session_start();
        $id_participe=$_POST['id_participe'];
        $tipo_credito=$_POST['tipo_creditos'];
        $rp_capremci= new ParticipesModel();
        $total=0;
        $html='
        <table width="100%">
        <tr>
        <th colspan="4" style="text-align:center">CREDITOS A RENOVAR</th>
        </tr>
        <tr>
        <th >№ DE PRESTAMO</th>
        <th >FECHA DE PRESTAMO</th>
        <th>TIPO CREDITO</th>
        <th >SALDO CAPITAL</th>
        </tr>';
        
        $columnas="id_tipo_creditos_a_renovar";
        $tablas="core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
        $where="codigo_tipo_creditos='".$tipo_credito."'";
        $id_creditos_renovar=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        foreach($id_creditos_renovar as $res)
        {
            $columnas='core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos';
            $tablas='public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos';
            
            $where="core_creditos.id_participes=".$id_participe." AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'
                    AND core_creditos.id_tipo_creditos=".$res->id_tipo_creditos_a_renovar;
            
            $id_credito=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
            
            foreach ($id_credito as $res1)
            {
                $total+=$res1->saldo_actual_creditos;
                $saldo=number_format((float)$res1->saldo_actual_creditos, 2, '.', '');
                $html.='<tr>
                 <td >'.$res1->numero_creditos.'</font></td>
                 <td >'.$res1->fecha_concesion_creditos.'</font></td>
                 <td>'.$res1->nombre_tipo_creditos.'</td>
                <td align="right" id="saldo_credito_a_renovar">'.$saldo.'</font></td>
                </tr>';
            }
            
        }
       $total=number_format((float)$total, 2, '.', '');
        $html.='<tr>
        <th ></th>
        <th ></th>
        <th >Total:</th>
        <td align="right">'.$total.'</td>
        </tr>';
       
       

        $html.='</table>';
        
        echo $html;
        
        
    }
    
    public function CreditosActivosParticipeRenovacion()
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
        $where="core_creditos.id_participes=".$id_participe." AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'";
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
            
            $html='<div class="box box-solid bg-light-blue-active">
            <div class="box-header with-border">
            <h3 class="box-title">Historial Prestamos</h3>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-light-blue-active">
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
                         <td bgcolor="white" width="8%"><font color="black">'.$resultCreditos[$i]->numero_creditos.'</font></td>
                         <td bgcolor="white" width="15%"><font color="black">'.$resultCreditos[$i]->fecha_concesion_creditos.'</font></td>
                        <td bgcolor="white" width="15%"><font color="black">'.$resultCreditos[$i]->nombre_tipo_creditos.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$monto.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$saldo.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$saldo_int.'</font></td>
                        <td bgcolor="white" width="14%"><font color="black">'.$resultCreditos[$i]->nombre_estado_creditos.'</font></td>
                        <td bgcolor="white" width="3%"><font color="black">';
                
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
                    if($cuotas_pagadas>=6 /*&& $mora==false*/)
                    {
                        $html.='<button type="button" class="btn bg-light-blue-active" title="Seleccionar crédito" onclick="SeleccionarCreditoRenovacion('.$resultCreditos[$i]->id_creditos.')">';
                        $html.='<i class="fa  fa-check"></i>';
                        $html.='</button>';
                    }
                    else
                    {
                        $html.='<button type="button" class="btn bg-light-blue-active" title="Crédito no elegible" disabled>';
                        $html.='<i class="fa  fa-close"></i>';
                        $html.='</button>';
                        
                    }
                    
                }
                
                
                /* $hoy=date("Y-m-d");
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
                 */
                
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
            $html.='<div class="alert alert-warning alert-dismissable bg-light-blue-active" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b>El participe no tiene creditos activos</b>';
            $html.='</div>';
            echo $html;
        }
        
        
    }
    
   public function CreditoParticipe()
   {
   session_start();
   $creditos= new ParticipesModel();
   $cedula_participes = $_POST['cedula_participe'];// controlar los aportes hasta agosto
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
   
   public function BuscarGarante()
   {
       session_start();
       $creditos= new ParticipesModel();
       $cedula_garante = $_POST['cedula_garante'];
       $mes=8;//$mes=date('m'); //mirar aportes hasta agosto
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
       $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
       $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_contribucion.id_estatus=1";
       $totalCtaIndividual=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $columnas="SUM(saldo_actual_creditos) AS total";
       $tablas="core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";$id="core_creditos.id_participes";
       $saldo_actual_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $columnas="valor_personal_contribucion";
       $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes=core_participes.id_participes";
       $where="cedula_participes='".$cedula_garante."' AND id_estado_contribucion=1 AND core_contribucion.id_estatus=1 AND id_contribucion_tipo=1
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
       
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_participes.id_estado_participes=1";
       
       $id="core_participes.id_participes";
       
       $infoParticipe=$creditos->getCondiciones($columnas, $tablas, $where, $id);
       
       if (!(empty($infoParticipe)))
       {
           
           $columnas="id_creditos_garantias";
           
           $tablas="core_creditos_garantias INNER JOIN core_participes
                    ON core_creditos_garantias.id_participes = core_participes.id_participes
                    INNER JOIN estado
                    ON core_creditos_garantias.id_estado=estado.id_estado";
           
           $where="core_participes.cedula_participes='".$cedula_garante."' AND estado.nombre_estado='ACTIVO'";
           
           $id="id_creditos_garantias";
           
           $Garantias=$creditos->getCondiciones($columnas, $tablas, $where, $id);
           
           if(empty($Garantias))
           {
               $saldo_cta_individual=number_format((float)$saldo_cta_individual, 2, '.', '');
               $hoy=date("Y-m-d");
               
               $tiempo=$this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
               $edad=explode(",",$tiempo);
               $edad=$edad[0];
               $edad=explode(" ", $edad);
               $edad=$edad[0];
               $html='  <button type="button"  class="close pull-right" onclick="QuitarGarante()" aria-label="Quitar garante"><span aria-hidden="true">&times;</span></button>

        <table>
        <tr>
        <td><font size="3">'.$infoParticipe[0]->nombre_participes.' '.$infoParticipe[0]->apellido_participes.'(GARANTE)</font></td>
        <td><font size="3"> Cédula : '.$infoParticipe[0]->cedula_participes.'</font></td>
        </tr>
        <tr>
        <td colspan="2"><font size="3">Fecha de nacimiento : '.$infoParticipe[0]->fecha_nacimiento_participes.'</font></td>
        </tr>
        <tr>
        <td colspan="2" ><font size="3" id="edad_garante">Edad : '.$tiempo.'</font></td>
        </tr>
        <tr>
        <td colspan="2" ><font size="3"> Cta Individual : '.$saldo_cta_individual.'</font></td>
        </tr>
        <tr>
        <td colspan="2" ><font size="3"> Capital de créditos : '.$saldo_credito.'</font></td>
        </tr>
        <tr>
        <td colspan="2" ><font size="3" id="monto_garante_disponible"> Disponible : '.$disponible.'</font></td>
        </tr>';
               if($num_aporte<3)$html.='<tr><td ><font id="aportes_garante" size="3">El participe tiene '.$num_aporte.' de los 3 últimos aportes pagados</font></td></tr></table>';
               echo $html;
           }
           else
           {
               echo "Garante no disponible";
           }
           
          
       }
       else echo "Participe no encontrado";
       
       
   }
   
   public function GetCuotas()
   {
       session_start();
       $cuotas = new EstadoModel();
       $monto_credito=$_POST['monto_credito'];
       $cedula_participes=$_POST['cedula_participe'];
       $sueldo_partcipe=$_POST['sueldo_participe'];
       $tipo_credito=$_POST['tipo_credito'];
       
       
       
       
       
       
      $columnas="   core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_participes."'";
       
       $id="core_participes.id_participes";
       
       $infoParticipe=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
        
       $hoy=date("Y-m-d");
       
       $dias_hasta=$this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
       $dias_75=365*75;
       $diferencia_dias=$dias_75-$dias_hasta;
       $diferencia_dias=$diferencia_dias/30;
       $diferencia_dias=floor($diferencia_dias * 1) / 1;
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       
       
       $sueldo_partcipe=$sueldo_partcipe/2;
       
       $columnas="cuotas_rango_plazos_creditos";
       $tablas="public.core_plazos_creditos";
       $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
       
       
       $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
       $valor_cuota=round($valor_cuota,2);
       
       if ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
       {
           
           while ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
           {
               $monto_credito-=10;
               $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
               $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
               $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
               
               $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
               $valor_cuota=round($valor_cuota,2);
           }
       }
       
       if($tipo_credito!="PH")
       {
           if($cuota>84) $cuota=84;
       }
      
       $html='<label for="tipo_credito" class="control-label">Número de cuotas:</label>
       <select name="cuotas_credito" id="cuotas_credito"  class="form-control" onchange="SimularCredito()">';
       for($cuota; $cuota>=3; $cuota-=3)
       {

           $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
           $valor_cuota=round($valor_cuota,2);
           if($cuota<=$diferencia_dias && $valor_cuota<=$sueldo_partcipe) $html.='<option value="'.$cuota.'">'.$cuota.'</option>';
       }
       
       
       $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
       
       
       $resultado=array();
       
       array_push($resultado, $monto_credito,$html);
       
       echo json_encode($resultado);
   }
   
   
   
   public function GetCuotas1()
   {
       session_start();
       $cuotas = new EstadoModel();
       $monto_credito=$_POST['monto_credito'];
       $cedula_participes=$_SESSION['cedula_usuarios'];
       $sueldo_partcipe=$_POST['sueldo_participe'];
       $tipo_credito=$_POST['tipo_credito'];
       
       
       
       
       
       
       $columnas="   core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_participes."'";
       
       $id="core_participes.id_participes";
       
       $infoParticipe=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       
       $hoy=date("Y-m-d");
       
       $dias_hasta=$this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
       $dias_75=365*75;
       $diferencia_dias=$dias_75-$dias_hasta;
       $diferencia_dias=$diferencia_dias/30;
       $diferencia_dias=floor($diferencia_dias * 1) / 1;
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       
       
       $sueldo_partcipe=$sueldo_partcipe/2;
       
       $columnas="cuotas_rango_plazos_creditos";
       $tablas="public.core_plazos_creditos";
       $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
       
       
       $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
       $valor_cuota=round($valor_cuota,2);
       
       if ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
       {
           
          
           
           
           while ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
           {
               $monto_credito-=10;
               $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
               $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
               $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
               
               $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
               $valor_cuota=round($valor_cuota,2);
           }
       }
       
       if($tipo_credito!="PH")
       {
           if($cuota>84) $cuota=84;
       }
       
       $html='<label for="tipo_credito" class="control-label">Número de cuotas:</label>
       <select name="cuotas_credito" id="cuotas_credito"  class="form-control" onchange="SimularCredito()">';
       for($cuota; $cuota>=3; $cuota-=3)
       {
           
           $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
           $valor_cuota=round($valor_cuota,2);
           if($cuota<=$diferencia_dias && $valor_cuota<=$sueldo_partcipe) $html.='<option value="'.$cuota.'">'.$cuota.'</option>';
       }
       
       
       $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
       
       
       $resultado=array();
       
       array_push($resultado, $monto_credito,$html);
       
       echo json_encode($resultado);
   }
   
   public function GetCuotasGarante()
   {
       session_start();
       $cuotas = new EstadoModel();
       $monto_credito=$_POST['monto_credito'];
       $cedula_participes=$_POST['cedula_participe'];
       $cedula_garante=$_POST['cedula_garante'];
       $sueldo_partcipe=$_POST['sueldo_participe'];
       $sueldo_garante=$_POST['sueldo_garante'];
       $tipo_credito=$_POST['tipo_credito'];
         
       
       $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_participes."'";
       
       $id="core_participes.id_participes";
       
       $infoParticipe=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       
       $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_participes.id_estado_participes=1";
       
       $id="core_participes.id_participes";
       
       $infoGarante=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       
       $hoy=date("Y-m-d");
       
       $dias_hasta=$this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
       $dias_hasta_garante=$this->dateDifference1($infoGarante[0]->fecha_nacimiento_participes, $hoy);
       
       $dias_75=365*75;
       
       $diferencia_dias=$dias_75-$dias_hasta;
       $diferencia_dias_garante=$dias_75-$dias_hasta_garante;
       
       $diferencia_dias=$diferencia_dias/30;       
       $diferencia_dias_garante=$diferencia_dias_garante/30;
       
       $diferencia_dias=floor($diferencia_dias * 1) / 1;
       $diferencia_dias_garante=floor($diferencia_dias_garante * 1) / 1;
       if($diferencia_dias_garante<$diferencia_dias) $diferencia_dias=$diferencia_dias_garante;
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       
       $pago_garante=1;
       
       $sueldo_partcipe=$sueldo_partcipe/2;
       $sueldo_garante=$sueldo_garante/2;
       
       $columnas="cuotas_rango_plazos_creditos";
       $tablas="public.core_plazos_creditos";
       $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
       
       $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
       $valor_cuota=round($valor_cuota,2);
       
       
       if ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
       {
           while ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
           {
               $monto_credito-=10;
               $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
               $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
               $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
               
               $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
               $valor_cuota=round($valor_cuota,2);
           }
       }
       
       if ($valor_cuota>$sueldo_garante) $pago_garante=0;
       
       
       $html='<label for="tipo_credito" class="control-label">Número de cuotas:</label>
       <select name="cuotas_credito" id="cuotas_credito"  class="form-control" onchange="SimularCredito()">';
       for($cuota; $cuota>=3; $cuota-=3)
       {
           $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
           $valor_cuota=round($valor_cuota,2);
           
           if($cuota<=$diferencia_dias && $valor_cuota<=$sueldo_partcipe) $html.='<option value="'.$cuota.'">'.$cuota.'</option>';
       }
       
       
       
       $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
       
       $resultado=array();
       
       array_push($resultado, $monto_credito,$html,$pago_garante);
       
       echo json_encode($resultado);
   }
   
   public function GetCuotasGarante1()
   {
       session_start();
       $cuotas = new EstadoModel();
       $monto_credito=$_POST['monto_credito'];
       $cedula_participes=$_SESSION['cedula_usuarios'];
       $cedula_garante=$_POST['cedula_garante'];
       $sueldo_partcipe=$_POST['sueldo_participe'];
       $sueldo_garante=$_POST['sueldo_garante'];
       $tipo_credito=$_POST['tipo_credito'];
       
       
       $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_participes."'";
       
       $id="core_participes.id_participes";
       
       $infoParticipe=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       
       $columnas="      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
       $tablas="public.core_participes";
       
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_participes.id_estado_participes=1";
       
       $id="core_participes.id_participes";
       
       $infoGarante=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       
       $hoy=date("Y-m-d");
       
       $dias_hasta=$this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
       $dias_hasta_garante=$this->dateDifference1($infoGarante[0]->fecha_nacimiento_participes, $hoy);
       
       $dias_75=365*75;
       
       $diferencia_dias=$dias_75-$dias_hasta;
       $diferencia_dias_garante=$dias_75-$dias_hasta_garante;
       
       $diferencia_dias=$diferencia_dias/30;
       $diferencia_dias_garante=$diferencia_dias_garante/30;
       
       $diferencia_dias=floor($diferencia_dias * 1) / 1;
       $diferencia_dias_garante=floor($diferencia_dias_garante * 1) / 1;
       if($diferencia_dias_garante<$diferencia_dias) $diferencia_dias=$diferencia_dias_garante;
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       
       $pago_garante=1;
       
       $sueldo_partcipe=$sueldo_partcipe/2;
       $sueldo_garante=$sueldo_garante/2;
       
       $columnas="cuotas_rango_plazos_creditos";
       $tablas="public.core_plazos_creditos";
       $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
       $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
       
       $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
       $valor_cuota=round($valor_cuota,2);
       
       
       if ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
       {
           while ($valor_cuota>$sueldo_partcipe || $cuota>$diferencia_dias)
           {
               $monto_credito-=10;
               $where=$monto_credito.">=minimo_rango_plazos_creditos AND ".$monto_credito." <= maximo_rango_plazos_creditos";
               $resultSet=$cuotas->getCondiciones($columnas, $tablas, $where, $id);
               $cuota=$resultSet[0]->cuotas_rango_plazos_creditos;
               
               $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
               $valor_cuota=round($valor_cuota,2);
           }
       }
       
       if ($valor_cuota>$sueldo_garante) $pago_garante=0;
       
       
       $html='<label for="tipo_credito" class="control-label">Número de cuotas:</label>
       <select name="cuotas_credito" id="cuotas_credito"  class="form-control" onchange="SimularCredito()">';
       for($cuota; $cuota>=3; $cuota-=3)
       {
           $valor_cuota =  ($monto_credito * $interes_mensual)/(1- pow((1+$interes_mensual), -$cuota));
           $valor_cuota=round($valor_cuota,2);
           
           if($cuota<=$diferencia_dias && $valor_cuota<=$sueldo_partcipe) $html.='<option value="'.$cuota.'">'.$cuota.'</option>';
       }
       
       
       
       $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
       
       $resultado=array();
       
       array_push($resultado, $monto_credito,$html,$pago_garante);
       
       echo json_encode($resultado);
   }
  
   
   public function SimulacionCredito()
   {
       session_start();
       $cuotas= new PlanCuentasModel();
       $monto_credito=$_POST['monto_credito'];
       $id_solicitud=$_POST['id_solicitud'];
       $fecha_corte=date('Y-m-d');
       if($id_solicitud==0) $avaluo_bien=$_POST['avaluo_bien'];
       else $avaluo_bien=0;
       $cuota=$_POST['plazo_credito'];
       $tipo_credito=$_POST['tipo_credito'];
       $renovacion_credito=$_POST['renovacion_credito'];
       
       if($tipo_credito=="PH" && $id_solicitud!=0)
       {
           $columnas="valor_avaluo_core_documentos_hipotecario";
           $tablas="core_documentos_hipotecario";
           $where="id_solicitud_credito=".$id_solicitud;
           $avaluo_credito=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $avaluo_credito=$avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
       }
       else $avaluo_credito=$avaluo_bien;
       
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       $plazo_dias = $cuota * 30;
     
       $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
       $valor_cuota=round($valor_cuota,2);
       
       if ($tipo_credito=="PH")
       {
           if($renovacion_credito=="true")
           {
               $resultAmortizacion=$this->tablaAmortizacionRenovacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
           }
           else
           {
               $resultAmortizacion=$this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
           }
       }
       else
       {
           if($renovacion_credito=="true")
           {
               $resultAmortizacion=$this->tablaAmortizacionRenovacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
           }
           else
           {
               $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
           }
       }
       
      
      
      if($tipo_credito=="PH")
      {
          $html='<div class="si">
            	
            	 <button  type="button" id="pdf" name="pdf" class="btn btn-primary" onclick="GenerarPdf()"><i class="glyphicon glyphicon-save-file"></i> EXPORTAR A PDF</button>
                 
            	</div>

            <br>
            <div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
            if($id_solicitud!=0)    $html.='<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
            $html.='</div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="15%">Fecha</th>
                        <th width="13%">Capital</th>
                        <th width="13%">Interes</th>
                        <th width="13%">Seg. Desgravamen</th>
                        <th width="13%">Seg. Incendio</th>
                        <th width="13%">Cuota</th>
                        <th width="13%">Saldo</th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
      }
      else
      {
          $html='
            <div class="si">
            	
            	 <button  type="button" id="pdf" name="pdf" class="btn btn-primary" onclick="GenerarPdf()"><i class="glyphicon glyphicon-save-file"></i> EXPORTAR A PDF</button>
              
            	</div>
             <br>
            <div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
          if($id_solicitud!=0) $html.='<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
          $html.= '</div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="18%" >Fecha</th>
                        <th width="15%">Capital</th>
                        <th width="15%">Interes</th>
                        <th width="15%">Seg. Desgravamen</th>
                        <th width="15%">Cuota</th>
                        <th width="15%">Saldo</th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
      }
       
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
       $total_incendio=0;
       
       foreach ($resultAmortizacion as $res)
       {
           
           $res['desgravamen']=number_format((float)$res['desgravamen'],2,".","");
           $total_desg+=$res['desgravamen'];
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];           
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $total_capital+=$res['amortizacion'];
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos'];
           if($tipo_credito=="PH")
           {
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".","");
               $total_incendio+=$res['seguro_incendios'];
           }
           
           
                     
       }
       $total=round($total,2);
       $total1=round($total1,2);
       $num=$monto_credito-($total1-$total);
       $num=round($num,2);
       $len=sizeof($resultAmortizacion);
      $res['amortizacion']=round($res['amortizacion'],2);
       $res['interes']=round($res['interes'],2);
       $res['pagos']=round($res['pagos'],2);
       
       $resultAmortizacion[$len-1]['pagos']=$resultAmortizacion[$len-1]['pagos']+$resultAmortizacion[$len-1]['saldo_inicial'];
        $diferencia=($resultAmortizacion[$len-1]['pagos']-$resultAmortizacion[$len-1]['interes']);
       
    $resultAmortizacion[$len-1]['amortizacion']=$resultAmortizacion[$len-1]['amortizacion']+$resultAmortizacion[$len-1]['saldo_inicial'];
      $resultAmortizacion[$len-1]['saldo_inicial']=0.00;
    //$resultAmortizacion[$len-1]['interes']=$diferencia;
    
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
       $total_incendio=0;
       foreach ($resultAmortizacion as $res)
       {
           
           $res['desgravamen']=number_format((float)$res['desgravamen'],2,".","");
           $total_desg+=$res['desgravamen'];
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $total_capital+=$res['amortizacion'];
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos']+$res['desgravamen'];
           
           if($tipo_credito=="PH")
           {
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".","");
               $total_incendio+=$res['seguro_incendios'];
           }
     
       }
      
       
       
       
       if($tipo_credito=="PH")
       {
           foreach ($resultAmortizacion as $res)
           {
               /*<th width="5%">Cuota</th>
               <th width="15%" >Fecha</th>
               <th width="13%">Capital</th>
               <th width="13%">Interes</th>
               <th width="13%">Seg. Desgravamen</th>
               <th width="13%">Seg. Incendio</th>
               <th width="13%">Cuota</th>
               <th width="13%">Saldo</th>
               <th width="2%"></th>*/
               
               $html.='<tr>';
               $html.='<td width="5%" bgcolor="white"><font color="black">'.$res['pagos_trimestrales'].'</font></td>';
               $html.='<td width="15%" bgcolor="white" align="center"><font color="black">'.$res['fecha_pago'].'</font></td>';
               $res['amortizacion']=number_format((float)$res['amortizacion'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['amortizacion'].'</font></td>';
               $res['interes']=number_format((float)$res['interes'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['interes'].'</font></td>';
               $cuota_pagar=$res['desgravamen']+$res['pagos'];
               $res['desgravamen']=number_format((float)$res['desgravamen'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="desgravamen'.$res['pagos_trimestrales'].'">'.$res['desgravamen'].'</font></td>';
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio'.$res['pagos_trimestrales'].'">'.$res['seguro_incendios'].'</font></td>';
               $cuota_pagar=number_format((float)$cuota_pagar,2,".",",");
               $html.='<td  width="13.2%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar'.$res['pagos_trimestrales'].'">'.$cuota_pagar.'</font></td>';
               $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
               $html.='</tr>';
           
           
       }
       
       $html.='<tr>';
       $html.='<td width="5%" bgcolor="white"><font color="black"></font></td>';
       $html.='<td width="15%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
       $total_capital=number_format((float)$total_capital,2,".",",");
       $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total_capital.'</font></td>';
       $total=number_format((float)$total,2,".",",");
       $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total.'</font></td>';
       $total_desg=number_format((float)$total_desg,2,".",",");
       $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total_desg.'</font></td>';
       $total_incendio=number_format((float)$total_incendio,2,".",",");
       $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio'.$res['pagos_trimestrales'].'">'.$total_incendio.'</font></td>';
       $total1=number_format((float)$total1,2,".",",");
       $html.='<td width="13.2%" bgcolor="white" align="right"><font color="black">'.$total1.'</font></td>';
       $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black"></font></td>';
       $html.='</tr>';
       }
       else
       {
           foreach ($resultAmortizacion as $res)
           {
               
               $html.='<tr>';
               $html.='<td width="5%" bgcolor="white"><font color="black">'.$res['pagos_trimestrales'].'</font></td>';
               $html.='<td width="18%" bgcolor="white" align="center"><font color="black">'.$res['fecha_pago'].'</font></td>';
               $res['amortizacion']=number_format((float)$res['amortizacion'],2,".",",");
               $html.='<td width="15.2%" bgcolor="white" align="right"><font color="black">'.$res['amortizacion'].'</font></td>';
               $res['interes']=number_format((float)$res['interes'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['interes'].'</font></td>';
               $cuota_pagar=$res['desgravamen']+$res['pagos'];
               $res['desgravamen']=number_format((float)$res['desgravamen'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black" id="desgravamen'.$res['pagos_trimestrales'].'">'.$res['desgravamen'].'</font></td>';
               $cuota_pagar=number_format((float)$cuota_pagar,2,".",",");
               $html.='<td  width="15.4%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar'.$res['pagos_trimestrales'].'">'.$cuota_pagar.'</font></td>';
               $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
               $html.='</tr>';
               
               
           }
           
           $html.='<tr>';
           $html.='<td width="5%" bgcolor="white"><font color="black"></font></td>';
           $html.='<td width="18%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
           $total_capital=number_format((float)$total_capital,2,".",",");
           $html.='<td width="15.2%" bgcolor="white" align="right"><font color="black">'.$total_capital.'</font></td>';
           $total=number_format((float)$total,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total.'</font></td>';
           $total_desg=number_format((float)$total_desg,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total_desg.'</font></td>';
           $total1=number_format((float)$total1,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total1.'</font></td>';
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black"></font></td>';
           $html.='</tr>';
       }
       
       
      
       
       $html.='</table>
              </div>';
       echo $html;
   }
   
     
   public function tablaAmortizacion($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza )
   {
       //array donde guardar tabla amortizacion
       $resultAmortizacion=array();
       $rp_capremci= new PlanCuentasModel();
       
       $columnas="expresion_formulas";
       $tablas="core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
       $where="descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
       $formula_seguro_desgravamen=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $formula_seguro_desgravamen=$formula_seguro_desgravamen[0]->expresion_formulas;
       
       $formato_fecha='Y-m-d';
       $capital = $_capital_prestado_amortizacion_cabeza;
       $inter_ant= $interes_mensual;
       $interes_diario=$inter_ant/30;
                                                                                                                                                                                                                                                                   $interes=  $capital * $inter_ant;
                                                                                                                                                                                                                                                                   $interes=floor($interes * 100) / 100;
       $amortizacion = $valor_cuota - $interes;
       $saldo_inicial= $capital - $amortizacion;
       $desgravamen=eval("return($formula_seguro_desgravamen);");
       $desgravamen=floor($desgravamen * 100) / 100;
       $resultAmortizacion=array();
       $interes_concesion=0;
       $diferencia_dias=0;
       
       $interes= 0;
       $amortizacion = 0;
       $saldo_inicial= $capital;
       $fecha=new DateTime($fecha_corte);
       $elementos_fecha=explode("-", $fecha_corte);
       $lastday = $fecha->format('Y-m-t');
       $lastday = explode("-", $lastday);
       $lastday=$lastday[2];
       $diferencia_dias=$lastday-$elementos_fecha[2];
       $dia_actual=$elementos_fecha[2];
       $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
       $fecha= new DateTime($fecha_ultimo_dia);
       $fecha=$fecha->format($formato_fecha);
       $fecha_corte=$fecha;
       $valor = 0;
       $desgravamen=0;
       $saldo_inicial_ant = $capital;
       
       for( $i = 1; $i <= $numero_cuotas+1; $i++) {
           
              
               if($i==1)
               {
                  
                   $elementos_fecha_corte=explode("-", $fecha_corte);
                   $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
                   $fecha=new DateTime($fecha_corte);
                   $elementos_fecha=explode("-", $fecha_corte);
                   $lastday = $fecha->format('Y-m-t');
                   $lastday = explode("-", $lastday);
                   $lastday=$lastday[2];
                   $diferencia_dias=$lastday-$dia_actual;
                   $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
                   $fecha= new DateTime($fecha_ultimo_dia);
                   $fecha=$fecha->format($formato_fecha);
                   $fecha_corte=$fecha;
                $interes_concesion=$interes_diario*$diferencia_dias*$capital;
                $interes_concesion=round($interes_concesion,2);
                $interes=$interes_concesion;
               }
               if($i!=1)
               {
                   $interes_concesion=0;
                   $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
                   $interes= $saldo_inicial_ant * $inter_ant;
                   $interes=floor($interes * 100) / 100;
                   $amortizacion = $valor_cuota - $interes;
                   
                   $desgravamen=eval("return($formula_seguro_desgravamen);");
                   $desgravamen=floor($desgravamen * 100) / 100;
                   $saldo_inicial= $saldo_inicial_ant  - $amortizacion;
                   $elementos_fecha_corte=explode("-", $fecha_corte);
                   $elementos_fecha_corte[1]++;
                   $elementos_fecha_corte[2]=15;
                   if($elementos_fecha_corte[1]>12)
                   {
                       $elementos_fecha_corte[1]=1;
                       $elementos_fecha_corte[0]++;
                   }
                   $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
                   $fecha=new DateTime($fecha_corte);
                   $elementos_fecha=explode("-", $fecha_corte);
                   $lastday = $fecha->format('Y-m-t');
                   $lastday = explode("-", $lastday);
                   $lastday=$lastday[2];
                   $diferencia_dias=$lastday-$elementos_fecha[2];
                   $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
                   $fecha= new DateTime($fecha_ultimo_dia);
                   $fecha=$fecha->format($formato_fecha);
                   $fecha_corte=$fecha;
                   $valor = $valor_cuota;
               }
               
               
               
               $arreglo=array('pagos_trimestrales'=> $i,
                   'saldo_inicial'=>$saldo_inicial,
                   'interes'=>$interes,
                   'amortizacion'=>$amortizacion,
                   'pagos'=>$valor,
                   'desgravamen'=>$desgravamen,
                   'fecha_pago'=>$fecha,
                   'interes_concesion'=>$interes_concesion
               );
          
          
           array_push($resultAmortizacion, $arreglo);
       }
       
       return $resultAmortizacion;
   }
   
   public function tablaAmortizacionHipotecario($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza, $avaluo_bien )
   {
       //array donde guardar tabla amortizacion
       $resultAmortizacion=array();
       $formato_fecha='Y-m-d';
       
       $rp_capremci= new PlanCuentasModel();
       $columnas="expresion_formulas";
       $tablas="core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
       $where="descripcion_formulas='seguro_de_incendios' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
       $formula_seguro_incendios=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $formula_seguro_incendios=$formula_seguro_incendios[0]->expresion_formulas;
       
       $columnas="expresion_formulas";
       $tablas="core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
       $where="descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
       $formula_seguro_desgravamen=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $formula_seguro_desgravamen=$formula_seguro_desgravamen[0]->expresion_formulas;
       
       $capital = $_capital_prestado_amortizacion_cabeza;
       $inter_ant= $interes_mensual;
       $interes_diario=$inter_ant/30;
       $interes=  $capital * $inter_ant;
       $interes=floor($interes * 100) / 100;
       $amortizacion = $valor_cuota - $interes;
       $saldo_inicial= $capital - $amortizacion;
       $desgravamen=eval("return($formula_seguro_desgravamen);");
       $desgravamen=floor($desgravamen * 100) / 100;
       $resultAmortizacion=array();
       $interes_concesion=0;
       $diferencia_dias=0;
       
       $interes= 0;
       $amortizacion = 0;
       $saldo_inicial= $capital;
       $fecha=new DateTime($fecha_corte);
       $elementos_fecha=explode("-", $fecha_corte);
       $lastday = $fecha->format('Y-m-t');
       $lastday = explode("-", $lastday);
       $lastday=$lastday[2];
       $diferencia_dias=$lastday-$elementos_fecha[2];
       $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
       $fecha= new DateTime($fecha_ultimo_dia);
       $fecha=$fecha->format($formato_fecha);
       $fecha_corte=$fecha;
       $valor = 0;
       $desgravamen=0;
       $saldo_inicial_ant = $capital;
       
       for( $i = 1; $i <= $numero_cuotas+1; $i++) {
           
           if($i==1)
           {
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $fecha=date('Y-m-d',$fecha);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $fecha=$fecha->format($formato_fecha);
               $elementos_fecha=explode("-", $fecha);
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
               $interes_concesion=$interes_diario*$diferencia_dias*$capital;
               $interes_concesion=round($interes_concesion,2);
               $interes=$interes_concesion;
           }
           if($i!=1)
           {
               $interes_concesion=0;
               $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
               $interes= $saldo_inicial_ant * $inter_ant;
               $interes=floor($interes * 100) / 100;
               $amortizacion = $valor_cuota - $interes;
               
               $desgravamen=eval("return($formula_seguro_desgravamen);");
               $desgravamen=floor($desgravamen * 100) / 100;
               $saldo_inicial= $saldo_inicial_ant  - $amortizacion;
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $elementos_fecha_corte[1]++;
               $elementos_fecha_corte[2]=15;
               if($elementos_fecha_corte[1]>12)
               {
                   $elementos_fecha_corte[1]=1;
                   $elementos_fecha_corte[0]++;
               }
               
                             
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $fecha=$fecha->format($formato_fecha);
               $elementos_fecha=explode("-", $fecha);
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
               $seguro_incendios=eval("return($formula_seguro_incendios);");
               $valor = $valor_cuota;
           }
           
           
           
           $arreglo=array('pagos_trimestrales'=> $i,
               'saldo_inicial'=>$saldo_inicial,
               'interes'=>$interes,
               'amortizacion'=>$amortizacion,
               'pagos'=>$valor,
               'desgravamen'=>$desgravamen,
               'fecha_pago'=>$fecha,
               'interes_concesion'=>$interes_concesion,
               'seguro_incendios'=>$seguro_incendios
           );
           
           
           array_push($resultAmortizacion, $arreglo);
       }
       
       return $resultAmortizacion;
   }
   
   public function tablaAmortizacionRenovacion($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza )
   {
       //array donde guardar tabla amortizacion
       $resultAmortizacion=array();
       
       $formato_fecha='Y-m-d';
       $capital = $_capital_prestado_amortizacion_cabeza;
       $inter_ant= $interes_mensual;
       $interes_diario=$inter_ant/30;
       $interes=  $capital * $inter_ant;
       $interes=floor($interes * 100) / 100;
       $amortizacion = $valor_cuota - $interes;
       $saldo_inicial= $capital - $amortizacion;
       $desgravamen=((0.16/1000)*$saldo_inicial)*1.04;
       $desgravamen=floor($desgravamen * 100) / 100;
       $resultAmortizacion=array();
       $interes_concesion=0;
       $diferencia_dias=0;
              
       $interes= 0;
       $amortizacion = 0;
       $saldo_inicial= $capital;
       $fecha=new DateTime($fecha_corte);
       $lastday = $fecha->format('Y-m-t');
       $elementos_fecha=explode("-", $fecha_corte);
       $lastday = explode("-", $lastday);
       $lastday=$lastday[2];
       $diferencia_dias=$lastday-$elementos_fecha[2];
       $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
       $fecha= new DateTime($fecha_ultimo_dia);
       $fecha=$fecha->format($formato_fecha);
       $fecha_corte=$fecha;
       $valor = 0;
       $desgravamen=0;
       $saldo_inicial_ant = $capital;
       
       for( $i = 0; $i <= $numero_cuotas; $i++) {
          
           
           if($i==0)
           {
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $fecha=$fecha->format($formato_fecha);
               $elementos_fecha=explode("-", $fecha);
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
               $interes_concesion=$interes_diario*$diferencia_dias*$capital;
               $interes_concesion=round($interes_concesion,2);
           }
           if($i!=0)
           {
               $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
               $interes= $saldo_inicial_ant * $inter_ant;
               $interes=floor($interes * 100) / 100;
               if($i==1) $interes+=$interes_concesion;
               $amortizacion = $valor_cuota - $interes;
               
               $desgravamen=((0.16/1000)*$saldo_inicial)*1.04;
               $desgravamen=floor($desgravamen * 100) / 100;
               $saldo_inicial= $saldo_inicial_ant  - $amortizacion;
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $elementos_fecha_corte[1]++;
               $elementos_fecha_corte[2]=15;
               if($elementos_fecha_corte[1]>12)
               {
                   $elementos_fecha_corte[1]=1;
                   $elementos_fecha_corte[0]++;
               }
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $fecha=$fecha->format($formato_fecha);
               $elementos_fecha=explode("-", $fecha);
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
               if($i==1) $valor=$valor_cuota+$interes_concesion;
               else $valor = $valor_cuota;
           }
           
           
           
           if ($i!=0)
           {
               $arreglo=array('pagos_trimestrales'=> $i,
                   'saldo_inicial'=>$saldo_inicial,
                   'interes'=>$interes,
                   'amortizacion'=>$amortizacion,
                   'pagos'=>$valor,
                   'desgravamen'=>$desgravamen,
                   'fecha_pago'=>$fecha,
                   'interes_concesion'=>$interes_concesion
               );
               
               
               array_push($resultAmortizacion, $arreglo);
           }
          
       }
       
       return $resultAmortizacion;
   }
   
   public function tablaAmortizacionRenovacionHipotecario($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza, $avaluo_bien)
   {
       //array donde guardar tabla amortizacion
       $resultAmortizacion=array();
       
       $rp_capremci= new PlanCuentasModel();
       $columnas="expresion_formulas";
       $tablas="core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
       $where="descripcion_formulas='seguro_de_incendios' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
       $formula_seguro_incendios=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $formula_seguro_incendios=$formula_seguro_incendios[0]->expresion_formulas;
       
       $columnas="expresion_formulas";
       $tablas="core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
       $where="descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
       $formula_seguro_desgravamen=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $formula_seguro_desgravamen=$formula_seguro_desgravamen[0]->expresion_formulas;
       
       $formato_fecha='Y-m-d';
       $capital = $_capital_prestado_amortizacion_cabeza;
       $inter_ant= $interes_mensual;
       $interes_diario=$inter_ant/30;
       $interes=  $capital * $inter_ant;
       $interes=floor($interes * 100) / 100;
       $amortizacion = $valor_cuota - $interes;
       $saldo_inicial= $capital - $amortizacion;
       $desgravamen=eval("return ($formula_seguro_desgravamen);");
       $desgravamen=floor($desgravamen * 100) / 100;
       $resultAmortizacion=array();
       $interes_concesion=0;
       $diferencia_dias=0;
       
       $interes= 0;
       $amortizacion = 0;
       $saldo_inicial= $capital;
       $fecha=new DateTime($fecha_corte);
       $elementos_fecha=explode("-", $fecha_corte);
       $lastday = $fecha->format('Y-m-t');
       $lastday = explode("-", $lastday);
       $lastday=$lastday[2];
       $diferencia_dias=$lastday-$elementos_fecha[2];
       $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
       $fecha= new DateTime($fecha_ultimo_dia);
       
       $fecha_corte=$fecha->format($formato_fecha);
       $valor = 0;
       $desgravamen=0;
       $saldo_inicial_ant = $capital;
       
       for( $i = 0; $i <= $numero_cuotas; $i++) {
           
           if($i==0)
           {
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $elementos_fecha=explode("-", $fecha_corte);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $diferencia_dias=$lastday-$elementos_fecha[2];
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
               $interes_concesion=$interes_diario*$diferencia_dias*$capital;
               $seguro_incendios=((($avaluo_bien * 0.0015)/365) * $lastday) * 1.04 * 1.12;
               $interes_concesion=round($interes_concesion,2);
           }
           if($i!=0)
           {
               $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
               $interes= $saldo_inicial_ant * $inter_ant;
               $interes=floor($interes * 100) / 100;
               if($i==1) $interes+=$interes_concesion;
               $amortizacion = $valor_cuota - $interes;
                       
               $desgravamen=eval("return ($formula_seguro_desgravamen);");
               $desgravamen=floor($desgravamen * 100) / 100;
               $saldo_inicial= $saldo_inicial_ant  - $amortizacion;
               $elementos_fecha_corte=explode("-", $fecha_corte);
               $elementos_fecha_corte[1]++;
               $elementos_fecha_corte[2]=15;
               if($elementos_fecha_corte[1]>12)
               {
                   $elementos_fecha_corte[1]=1;
                   $elementos_fecha_corte[0]++;
               }
               $fecha_corte=$elementos_fecha_corte[0]."-".$elementos_fecha_corte[1]."-".$elementos_fecha_corte[2];
               $fecha=new DateTime($fecha_corte);
               $elementos_fecha=explode("-", $fecha_corte);
               $lastday = $fecha->format('Y-m-t');
               $lastday = explode("-", $lastday);
               $lastday=$lastday[2];
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha= new DateTime($fecha_ultimo_dia);
               $fecha=$fecha->format($formato_fecha);
               $fecha_corte=$fecha;
                
               $seguro_incendios= eval("return ($formula_seguro_incendios);");
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               
               if($i==1) $valor=$valor_cuota+$interes_concesion;
               else $valor = $valor_cuota;
           }
           
           
           
           if ($i!=0)
           {
               $arreglo=array('pagos_trimestrales'=> $i,
                   'saldo_inicial'=>$saldo_inicial,
                   'interes'=>$interes,
                   'amortizacion'=>$amortizacion,
                   'pagos'=>$valor,
                   'desgravamen'=>$desgravamen,
                   'fecha_pago'=>$fecha,
                   'interes_concesion'=>$interes_concesion,
                   'seguro_incendios'=>$seguro_incendios
               );
               
               
               array_push($resultAmortizacion, $arreglo);
           }
           
       }
       
       return $resultAmortizacion;
   }
   
   public function DesgloseTablaAmortizacion($id_tabla_amortizacion, $tipo_credito)
   {
       $rp_capremci=new PlanCuentasModel();
       $columnas="*";
       $tablas="core_tabla_amortizacion";
       $where="id_tabla_amortizacion=".$id_tabla_amortizacion;
       $datos_tabla_amortizacion=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       
       $columnas="id_tabla_amortizacion_parametrizacion";
       $tablas="core_tabla_amortizacion_parametrizacion INNER JOIN core_tipo_creditos
                    ON core_tabla_amortizacion_parametrizacion.id_tipo_creditos=core_tipo_creditos.id_tipo_creditos";
       $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=0 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
       $id_capital=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_capital=$id_capital[0]->id_tabla_amortizacion_parametrizacion;
       
       $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_capital, $id_tabla_amortizacion, '".$datos_tabla_amortizacion[0]->capital_tabla_amortizacion."',
                            '".$datos_tabla_amortizacion[0]->capital_tabla_amortizacion."', 1)";
       $rp_capremci->executeNonQuery($query);
       
       $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=1 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
       $id_interes=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_interes=$id_interes[0]->id_tabla_amortizacion_parametrizacion;
       
       $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_interes, $id_tabla_amortizacion, '".$datos_tabla_amortizacion[0]->interes_tabla_amortizacion."',
                            '".$datos_tabla_amortizacion[0]->interes_tabla_amortizacion."', 1)";
       $rp_capremci->executeNonQuery($query);
       
       $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=7 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
       $id_mora=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_mora=$id_mora[0]->id_tabla_amortizacion_parametrizacion;
       
       $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_mora, $id_tabla_amortizacion, '0.00',
                            '0.00', 1)";
       $rp_capremci->executeNonQuery($query);
      
       if($tipo_credito!='PH')
       {
                      
           $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=8 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
           $id_desgravamen=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $id_desgravamen=$id_desgravamen[0]->id_tabla_amortizacion_parametrizacion;
           
           $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_desgravamen, $id_tabla_amortizacion, '".$datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion."',
                            '".$datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion."', 1)";
           $rp_capremci->executeNonQuery($query);
           
       }
       else
       {
           
           $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=9 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
           $id_desgravamen=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $id_desgravamen=$id_desgravamen[0]->id_tabla_amortizacion_parametrizacion;
           
           $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_desgravamen, $id_tabla_amortizacion, '".$datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion."',
                            '".$datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion."', 1)";
           $rp_capremci->executeNonQuery($query);
           
           $where="codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=8 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
           $id_incendios=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $id_incendios=$id_incendios[0]->id_tabla_amortizacion_parametrizacion;
           
           $query="INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_incendios, $id_tabla_amortizacion, '".$datos_tabla_amortizacion[0]->seguro_incendios_tabla_amortizacion."',
                            '".$datos_tabla_amortizacion[0]->seguro_incendios_tabla_amortizacion."', 1)";
           $rp_capremci->executeNonQuery($query);
       }
      
       
   }
   
   public function SubirInformacionCredito()
   {
       session_start();
       ob_start();
       $mensage="";
       $respuesta=true;
       $credito=new CoreTipoCreditoModel();
       $usuario=$_SESSION['usuario_usuarios'];
       $monto_credito=$_POST['monto_credito'];
       $tipo_credito=$_POST['tipo_credito'];
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $resultSet=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       
       $columnas="id_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $id_tipo_creditos=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_tipo_creditos=$id_tipo_creditos[0]->id_tipo_creditos;
       
       
       $con_garante=$_POST['con_garante'];
       
       if($con_garante) $id_garante=$_POST['cedula_garante'];
              
       $fecha_pago=date("Y-m-d");
       $interes_credito=$tasa_interes;
       
       $tasa_interes=$tasa_interes/100;
       $cuota=$_POST['cuota_credito'];
       $cedula_participe=$_POST['cedula_participe'];
       $observacion_credito=$_POST['observacion_credito'];
       $id_solicitud=$_POST['id_solicitud'];
       $interes_consecion=0;
       
       if($tipo_credito=="PH")
       {
           $columnas="valor_avaluo_core_documentos_hipotecario";
           $tablas="core_documentos_hipotecario";
           $where="id_solicitud_credito=".$id_solicitud;
           $avaluo_credito=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $avaluo_credito=$avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
       }
       
       $columnas="id_participes";
       $tablas="core_participes";
       $where="cedula_participes='".$cedula_participe."'";
       
       $id_participe=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_participe=$id_participe[0]->id_participes;
       
       $columnas="numero_consecutivos";
       $tablas="consecutivos";
       $where="nombre_consecutivos='CREDITO'";
       
       $numero_credito=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $numero_credito=$numero_credito[0]->numero_consecutivos;
       $numero_credito++;
       $hoy=date("Y-m-d");
       
       $columnas="id_estado";
       $tablas="estado";
       $where="tabla_estado='tabla_core_creditos_garantias' AND nombre_estado='ACTIVO'";
       
       $id_estado=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_estado=$id_estado[0]->id_estado;
       
       //cambiar numero de credito por numero solicitud
       $credito->beginTran();
       
       $interes_mensual = $tasa_interes / 12;
           $plazo_dias = $cuota * 30;
           
           $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
           $valor_cuota=round($valor_cuota,2);
           if($tipo_credito=="PH") $resultAmortizacion=$this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes, $avaluo_credito);
           else $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes);
           $total=0;
           $total1=0;
           foreach ($resultAmortizacion as $res)
           {
               
               $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".","");
               $res['interes']=number_format((float)$res['interes'],2,".","");
               $total+=$res['interes'];
               $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
               $res['pagos']=number_format((float)$res['pagos'],2,".","");
               $total1+=$res['pagos'];
               
               
           }
           $total=round($total,2);
           $total1=round($total1,2);
           $num=$monto_credito-($total1-$total);
           $num=round($num,2);
           $len=sizeof($resultAmortizacion);
           $res['amortizacion']=round($res['amortizacion'],2);
           $res['interes']=round($res['interes'],2);
           $res['pagos']=round($res['pagos'],2);
            $resultAmortizacion[$len-1]['pagos']=$resultAmortizacion[$len-1]['pagos']+$num;
           $resultAmortizacion[$len-1]['amortizacion']=$resultAmortizacion[$len-1]['amortizacion']+$resultAmortizacion[$len-1]['saldo_inicial'];
           $resultAmortizacion[$len-1]['saldo_inicial']=0.00;
           $total=0;
           $total1=0;
           
           foreach ($resultAmortizacion as $res)
           {
               if($res['interes_concesion']!=0)
               {
                   $interes_consecion=$res['interes_concesion'];
                   
                   $monto_neto= $monto_credito-$interes_consecion;
                   $funcion = "ins_core_creditos";
                   $parametros=$numero_credito.',
                     \''.$numero_credito.'\',
                     '.$id_participe.',
                     \''.$monto_credito.'\',
                     \''.$monto_credito.'\',
                     \''.$hoy.'\',
                     2,
                     '.$cuota.',
                     \''.$monto_neto.'\',
                     '.$id_tipo_creditos.',
                     \''.$numero_credito.'\',
                     \''.$observacion_credito.'\',
                     1,
                     \''.$usuario.'\',
                     \''.$interes_credito.'\',
                     \''.$hoy.'\'';
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $queryInsert=$credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
                   $resultado=$credito->llamarconsultaPG($queryInsert);
                   
                   $id_creditos = $resultado[0];
                                     
                   $query="UPDATE core_creditos
                            SET cuota_creditos='$valor_cuota'
                            WHERE id_creditos=$id_creditos";
                    $credito->executeNonQuery($query);
                   
                   $fecha_pago=$res['fecha_pago'];
                   $num_cuota=$res['pagos_trimestrales'];
                   $amortizacion=$res['amortizacion'];
                   $intereses=$res['interes'];
                   $saldo_inicial=$res['saldo_inicial'];
                   $desgravamen=$res['desgravamen'];
                   if ($tipo_credito=="PH") $incendios=$res['seguro_incendios'];
                   $dividendo=$res['pagos'];
                   $total_valor=$amortizacion+$intereses+$desgravamen;
                   $funcion = "ins_core_tabla_amortizacion";
                   if($tipo_credito!="PH")
                   {
                       $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                      0,
                     '$desgravamen',
                     null,
                     '$total_valor',
                     2,
                     1,
                     '$tasa_interes',
                     '$hoy'";
                   }
                   else
                   {
                       $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     0,
                     '$desgravamen',
                     '$incendios',
                     '$total_valor',
                     2,
                     1,
                     '$tasa_interes',
                     '$hoy'";
                   }
                   
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $queryInsert=$credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
                   $resultado_id_tabla=$credito->llamarconsultaPG($queryInsert);
                   
                   $id_tabla_amortizacion = $resultado_id_tabla[0];
                   
                   $this->DesgloseTablaAmortizacion($id_tabla_amortizacion, $tipo_credito);
                   
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $resultado=$credito->Insert();
                   
                   $funcion = "ins_core_tabla_amortizacion_historico";
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $resultado=$credito->Insert();
                   
                   $funcion = "ins_core_tabla_amortizacion_pagare";
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $resultado=$credito->Insert();
                   
                   
                   
                   if($con_garante=="true")
                   {
                       $columnas="id_participes";
                       $tablas="core_participes";
                       $where="cedula_participes='".$id_garante."'";
                       
                       $id_garante=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
                       $id_garante=$id_garante[0]->id_participes;
                       
                       $query="INSERT INTO core_creditos_garantias
                   (id_creditos, id_participes, id_estado, usuario_usuarios)
                   VALUES(".$id_creditos.", ".$id_garante.", ".$id_estado.", '".$usuario."')";
                       
                       $insert=$credito->executeNonQuery($query);
                   }
                   
                   $errores_credito=ob_get_clean();
                   $errores_credito=trim($errores_credito);
                   
                   if(empty($errores_credito))
                   {
                       $query="INSERT INTO core_creditos_retenciones
                            (monto_creditos_retenciones, id_creditos)
                            VALUES
                            (".$res['interes_concesion'].", ".$id_creditos.")";
                   $insert=$credito->executeNonQuery($query);
                       
                   }
                   else
                   {
                       $credito->endTran('ROLLBACK');
                       $respuesta=false;
                       $mensage="ERROR Credito".$errores_credito."--".$num_cuota;
                       break;
                   }   
               }
               else {
                   $fecha_pago=$res['fecha_pago'];
                   $num_cuota=$res['pagos_trimestrales'];
                   $amortizacion=$res['amortizacion'];
                   $intereses=$res['interes'];
                   $saldo_inicial=$res['saldo_inicial'];
                   $desgravamen=$res['desgravamen'];
                   if ($tipo_credito=="PH") $incendios=$res['seguro_incendios'];
                   $dividendo=$res['pagos'];
                   $total_valor=$amortizacion+$intereses+$desgravamen;
                   $funcion = "ins_core_tabla_amortizacion";
                   if($tipo_credito!="PH")
                   {
                       $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     '$total_valor',
                     '$desgravamen',
                     null,
                     '$total_valor',
                     3,
                     1,
                     '$tasa_interes',
                     '$hoy'";
                   }
                   else
                   {
                       $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     '$total_valor',
                     '$desgravamen',
                     '$incendios',
                     '$total_valor',
                     3,
                     1,
                     '$tasa_interes',
                     '$hoy'";
                   }
                   
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $queryInsert=$credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
                   $resultado_id_tabla=$credito->llamarconsultaPG($queryInsert);
                   
                   $id_tabla_amortizacion = $resultado_id_tabla[0];
                   
                   $this->DesgloseTablaAmortizacion($id_tabla_amortizacion, $tipo_credito);
                   
                   
                   
                   $funcion = "ins_core_tabla_amortizacion_historico";
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $resultado=$credito->Insert();
                   
                   $funcion = "ins_core_tabla_amortizacion_pagare";
                   $credito->setFuncion($funcion);
                   $credito->setParametros($parametros);
                   $resultado=$credito->Insert();
                   $errores=ob_get_clean();
                   $errores=trim($errores);
                   if(!(empty($errores)))
                   {
                       $credito->endTran('ROLLBACK');
                       $respuesta=false;
                       $mensage="ERROR credito".$errores."==".$num_cuota;
                       break;
                   }
                   
               }
           }
               if($respuesta)
               {
                   $actualizacion_solicitud=$this->ActualizarSolicitud($id_solicitud, $monto_credito, $cuota, $numero_credito);
                   $plan_cuentas=new PlanCuentasModel();
                   $colval="numero_consecutivos=".$numero_credito;
                   $tabla="consecutivos";
                   $where="nombre_consecutivos='CREDITO'";
                   $actualizacion_solicitud= trim($actualizacion_solicitud);
                   if(empty($actualizacion_solicitud))
                   {
                       ob_start();
                       $plan_cuentas->ActualizarBy($colval, $tabla, $where);
                       $actualizacion_consecutivo=ob_get_clean();
                       $actualizacion_consecutivo= trim($actualizacion_consecutivo);
                       if(empty($actualizacion_consecutivo))
                       {
                           $actualizar_cuentas=$this->ActualizarCuentasParticipes($id_solicitud, $numero_credito);
                           if(empty($actualizar_cuentas))
                           {
                               
                               $actualizar_info_participes=$this->ActualizarInfoParticipe($cedula_participe, $id_solicitud);
                               if(empty($actualizar_info_participes))
                               {
                                   $credito->endTran('COMMIT');
                                   
                                   $mensage="OK";
                               }
                               else
                               {
                                   $credito->endTran('ROLLBACK');
                                   $mensage="ERROR 1 ".$actualizar_info_participes;
                               }
                               
                           }
                           else
                           {
                               $credito->endTran('ROLLBACK');
                               $mensage="ERROR 2 ".$actualizar_cuentas." ".$id_solicitud." ".$numero_credito;
                           }
                           
                       }
                       else {
                           $credito->endTran('ROLLBACK');
                           $mensage="ERROR 3 ".$actualizacion_consecutivo;
                       }
                       
                   }
                   else
                   {
                       echo "solicitud no aceptada";
                       $credito->endTran('ROLLBACK');
                       $mensage="ERROR 4 ".$actualizacion_solicitud;
                   }
                   
               }
              
       echo $mensage;
   }
   
   public function SubirInformacionRenovacionCredito()
   {
       session_start();
       ob_start();
       $mensage="";
       $total_retencion=0;
       $respuesta=true;
       $credito=new CoreTipoCreditoModel();
       $usuario=$_SESSION['usuario_usuarios'];
       $monto_credito=$_POST['monto_credito'];
       $tipo_credito=$_POST['tipo_credito'];
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $resultSet=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       
       $columnas="id_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $id_tipo_creditos=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_tipo_creditos=$id_tipo_creditos[0]->id_tipo_creditos;
       
       $con_garante=$_POST['con_garante'];
       
       if($con_garante) $id_garante=$_POST['cedula_garante'];
       
       $fecha_pago=date("Y-m-d");
       $interes_credito=$tasa_interes;
       
       $tasa_interes=$tasa_interes/100;
       $cuota=$_POST['cuota_credito'];
       $cedula_participe=$_POST['cedula_participe'];
       $observacion_credito=$_POST['observacion_credito'];
       $id_solicitud=$_POST['id_solicitud'];
       $interes_consecion=0;
       
       if($tipo_credito=="PH")
       {
           $columnas="valor_avaluo_core_documentos_hipotecario";
           $tablas="core_documentos_hipotecario";
           $where="id_solicitud_credito=".$id_solicitud;
           $avaluo_credito=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $avaluo_credito=$avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
       }
       
       $columnas="id_participes";
       $tablas="core_participes";
       $where="cedula_participes='".$cedula_participe."'";
       
       $id_participe=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_participe=$id_participe[0]->id_participes;
       
       $columnas="id_estado_creditos";
       $tablas="core_estado_creditos";
       $where="nombre_estado_creditos='En proceso de Renovacion'";
       
       $id_estado_renovacion=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_estado_renovacion=$id_estado_renovacion[0]->id_estado_creditos;
       
       $columnas="numero_consecutivos";
       $tablas="consecutivos";
       $where="nombre_consecutivos='CREDITO'";
       
       $numero_credito=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $numero_credito=$numero_credito[0]->numero_consecutivos;
       $numero_credito++;
       $hoy=date("Y-m-d");
       
       $columnas="id_estado";
       $tablas="estado";
       $where="tabla_estado='tabla_core_creditos_garantias' AND nombre_estado='ACTIVO'";
       
       $id_estado=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $id_estado=$id_estado[0]->id_estado;
       
       $credito->beginTran();
       
       $interes_mensual = $tasa_interes / 12;
       $plazo_dias = $cuota * 30;
       
       $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
       $valor_cuota=round($valor_cuota,2);
       
       if ($tipo_credito=="PH") $resultAmortizacion=$this->tablaAmortizacionRenovacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes, $avaluo_credito);
       else $resultAmortizacion=$this->tablaAmortizacionRenovacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes);
       $total=0;
       $total1=0;
       foreach ($resultAmortizacion as $res)
       {
           
           $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".","");
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos'];
           
           
       }
       $total=round($total,2);
       $total1=round($total1,2);
       $num=$monto_credito-($total1-$total);
       $num=round($num,2);
       $len=sizeof($resultAmortizacion);
       $res['amortizacion']=round($res['amortizacion'],2);
       $res['interes']=round($res['interes'],2);
       $res['pagos']=round($res['pagos'],2);
       $resultAmortizacion[$len-1]['pagos']=$resultAmortizacion[$len-1]['pagos']+$num;
       $resultAmortizacion[$len-1]['amortizacion']=$resultAmortizacion[$len-1]['amortizacion']+$resultAmortizacion[$len-1]['saldo_inicial'];
       $resultAmortizacion[$len-1]['saldo_inicial']=0.00;
       $total=0;
       $total1=0;
       
       
           
           $monto_neto= $monto_credito-$interes_consecion;
           $funcion = "ins_core_creditos";
           $parametros=$numero_credito.',
                     \''.$numero_credito.'\',
                     '.$id_participe.',
                     \''.$monto_credito.'\',
                     \''.$monto_credito.'\',
                     \''.$hoy.'\',
                     2,
                     '.$cuota.',
                     0,
                     '.$id_tipo_creditos.',
                     \''.$numero_credito.'\',
                     \''.$observacion_credito.'\',
                     1,
                     \''.$usuario.'\',
                     \''.$interes_credito.'\',
                     \''.$hoy.'\'';
           $credito->setFuncion($funcion);
           $credito->setParametros($parametros);
           $queryInsert=$credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
           $resultado=$credito->llamarconsultaPG($queryInsert);
           
           $id_creditos = $resultado[0];
           
           $query="UPDATE core_creditos
                            SET cuota_creditos='$valor_cuota'
                            WHERE id_creditos=$id_creditos";
           $credito->executeNonQuery($query);
           
           $columnas="id_tipo_creditos_a_renovar";
           $tablas="core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
           $where="codigo_tipo_creditos='".$tipo_credito."'";
           $id_creditos_renovar=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
           
           foreach($id_creditos_renovar as $res)
           {
               $columnas='core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos';
               $tablas='public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos';
               
               $where="core_creditos.id_participes=".$id_participe." AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'
                    AND core_creditos.id_tipo_creditos=".$res->id_tipo_creditos_a_renovar;
               
               $id_credito=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
               
               foreach ($id_credito as $res1)
               {
                   $total_retencion+=$res1->saldo_actual_creditos;
                   $desgravamen=((0.16/1000)*$res1->saldo_actual_creditos)*1.04;
                   $desgravamen=number_format((float)$desgravamen,2,".","");
                   $total_retencion+=$desgravamen;
                   $query="INSERT INTO core_creditos_renovaciones
                            (id_creditos_renovado, id_creditos_nuevo, saldo_credito_renovado_creditos_renovaciones, seguro_desgravamen_creditos_renovaciones)
                            VALUES (".$res1->id_creditos.",".$id_creditos.",".$res1->saldo_actual_creditos.",".$desgravamen.")";
                   $insert=$credito->executeNonQuery($query);
                   
                   $query="UPDATE core_creditos
                            SET id_estado_creditos=".$id_estado_renovacion."
                            WHERE id_creditos=".$res1->id_creditos;
                   $insert=$credito->executeNonQuery($query);
               }
           }
           $total_retencion=number_format((float)$total_retencion,2,".","");
           
           $query="INSERT INTO core_creditos_retenciones
                            (monto_creditos_retenciones, id_creditos)
                            VALUES
                            (".$total_retencion.", ".$id_creditos.")";
           
           $insert=$credito->executeNonQuery($query);
           
           $monto_neto=$monto_credito-$total_retencion;
           
           $query="UPDATE core_creditos
                   SET monto_neto_entregado_creditos='".$monto_neto."'
                   WHERE id_creditos=".$id_creditos;
           
           $insert=$credito->executeNonQuery($query);
           
           if($con_garante=="true")
           {
               $columnas="id_participes";
               $tablas="core_participes";
               $where="cedula_participes='".$id_garante."'";
               
               $id_garante=$credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
               $id_garante=$id_garante[0]->id_participes;
               
               $query="INSERT INTO core_creditos_garantias
                   (id_creditos, id_participes, id_estado, usuario_usuarios)
                   VALUES(".$id_creditos.", ".$id_garante.", ".$id_estado.", '".$usuario."')";
               
               $insert=$credito->executeNonQuery($query);
           }
           
           foreach ($resultAmortizacion as $res)
           {
                              
               $fecha_pago=$res['fecha_pago'];
               $num_cuota=$res['pagos_trimestrales'];
               $amortizacion=$res['amortizacion'];
               $intereses=$res['interes'];
               $saldo_inicial=$res['saldo_inicial'];
               $desgravamen=$res['desgravamen'];
               if ($tipo_credito=="PH") $incendios=$res['seguro_incendios'];
               $dividendo=$res['pagos'];
               $total_valor=$amortizacion+$intereses+$desgravamen;
               $funcion = "ins_core_tabla_amortizacion";
               if($tipo_credito!="PH")
               {
                   $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     '$total_valor',
                     '$desgravamen',
                     null,
                     '$total_valor',
                     3,
                     1,
                     '$tasa_interes',
                     '$hoy'";
               }
               else
               {
                   $parametros="'$id_creditos',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     '$total_valor',
                     '$desgravamen',
                     '$incendios',
                     '$total_valor',
                     3,
                     1,
                     '$tasa_interes',
                     '$hoy'";
               }
               $credito->setFuncion($funcion);
               $credito->setParametros($parametros);
               $queryInsert=$credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
               $resultado_id_tabla=$credito->llamarconsultaPG($queryInsert);
               
               $id_tabla_amortizacion = $resultado_id_tabla[0];
               
               $this->DesgloseTablaAmortizacion($id_tabla_amortizacion, $tipo_credito);
               
               $funcion = "ins_core_tabla_amortizacion_historico";
               $credito->setFuncion($funcion);
               $credito->setParametros($parametros);
               $resultado=$credito->Insert();
               
               $funcion = "ins_core_tabla_amortizacion_pagare";
               $credito->setFuncion($funcion);
               $credito->setParametros($parametros);
               $resultado=$credito->Insert();
               $errores=ob_get_clean();
               $errores=trim($errores);
               if(!(empty($errores)))
               {
                   $credito->endTran('ROLLBACK');
                   $respuesta=false;
                   $mensage="ERROR".$errores;
                   break;
               }
               
           }
           if($respuesta)
           {
               $actualizacion_solicitud=$this->ActualizarSolicitud($id_solicitud, $monto_credito, $cuota, $numero_credito);
               $plan_cuentas=new PlanCuentasModel();
               $colval="numero_consecutivos=".$numero_credito;
               $tabla="consecutivos";
               $where="nombre_consecutivos='CREDITO'";
               $actualizacion_solicitud= trim($actualizacion_solicitud);
               if(empty($actualizacion_solicitud))
               {
                   ob_start();
                   $plan_cuentas->ActualizarBy($colval, $tabla, $where);
                   $actualizacion_consecutivo=ob_get_clean();
                   $actualizacion_consecutivo= trim($actualizacion_consecutivo);
                   if(empty($actualizacion_consecutivo))
                   {
                       $actualizar_cuentas=$this->ActualizarCuentasParticipes($id_solicitud, $numero_credito);
                       if(empty($actualizar_cuentas))
                       {
                           
                           $actualizar_info_participes=$this->ActualizarInfoParticipe($cedula_participe, $id_solicitud);
                           if(empty($actualizar_info_participes))
                           {
                               $credito->endTran('COMMIT');
                               
                               $mensage="OK";
                           }
                           else
                           {
                               $credito->endTran('ROLLBACK');
                               $mensage="ERROR 1 ".$actualizar_info_participes;
                           }
                           
                       }
                       else
                       {
                           $credito->endTran('ROLLBACK');
                           $mensage="ERROR 2 ".$actualizar_cuentas." ".$id_solicitud." ".$numero_credito;
                       }
                       
                   }
                   else {
                       $credito->endTran('ROLLBACK');
                       $mensage="ERROR 3 ".$actualizacion_consecutivo;
                   }
                   
               }
               else
               {
                   echo "solicitud no aceptada";
                   $credito->endTran('ROLLBACK');
                   $mensage="ERROR 4 ".$actualizacion_solicitud;
               }
               
               
           }
       echo $mensage;
   }
   
   
   
   public function ActualizarInfoParticipe($cedula_participe, $id_solicitud)
   {
       require_once 'core/DB_Functions.php';
       ob_start();
       $db = new DB_Functions();
       $reporte=new PlanCuentasModel();
       $columnas = " solicitud_prestamo.correo_solicitante_datos_personales,
					  solicitud_prestamo.fecha_nacimiento_datos_personales,
					  solicitud_prestamo.id_estado_civil_datos_personales,
					  solicitud_prestamo.separacion_bienes_datos_personales,
					  solicitud_prestamo.cargas_familiares_datos_personales,
                      solicitud_prestamo.numero_hijos_datos_personales,
					  solicitud_prestamo.nivel_educativo_datos_personales,
					  solicitud_prestamo.id_provincias_vivienda,
					  solicitud_prestamo.id_cantones_vivienda,
					  solicitud_prestamo.id_parroquias_vivienda,
                      solicitud_prestamo.barrio_sector_vivienda,
					  solicitud_prestamo.ciudadela_conjunto_etapa_manzana_vivienda,
					  solicitud_prestamo.calle_vivienda,
					  solicitud_prestamo.numero_calle_vivienda,
					  solicitud_prestamo.intersecion_vivienda,
                      solicitud_prestamo.tipo_vivienda,
					  solicitud_prestamo.vivienda_hipotecada_vivienda,
					  solicitud_prestamo.tiempo_residencia_vivienda,
					  solicitud_prestamo.nombre_propietario_vivienda,
					  solicitud_prestamo.celular_propietario_vivienda,
                      solicitud_prestamo.referencia_direccion_domicilio_vivienda,
					  solicitud_prestamo.numero_casa_solicitante,
					  solicitud_prestamo.numero_celular_solicitante,
					  solicitud_prestamo.numero_trabajo_solicitante,
                      solicitud_prestamo.extension_solicitante,
					  solicitud_prestamo.apellidos_referencia_personal,
					  solicitud_prestamo.nombres_referencia_personal,
					  solicitud_prestamo.relacion_referencia_personal,
					  solicitud_prestamo.numero_telefonico_referencia_personal,
					  solicitud_prestamo.apellidos_referencia_familiar,
					  solicitud_prestamo.nombres_referencia_familiar,
					  solicitud_prestamo.parentesco_referencia_familiar,
					  solicitud_prestamo.numero_telefonico_referencia_familiar,
					  solicitud_prestamo.id_provincias_asignacion,
					  solicitud_prestamo.id_cantones_asignacion,
					  solicitud_prestamo.id_parroquias_asignacion,
					  solicitud_prestamo.numero_telefonico_datos_laborales,
					  solicitud_prestamo.interseccion_datos_laborales,
					  solicitud_prestamo.calle_datos_laborales,
					  solicitud_prestamo.cargo_actual_datos_laborales,
					  solicitud_prestamo.sueldo_total_info_economica,
                      solicitud_prestamo.tipo_pago_cuenta_bancaria,
                      solicitud_prestamo.nombres_conyuge,
                      solicitud_prestamo.apellidos_conyuge,
                      solicitud_prestamo.numero_cedula_conyuge,
                      solicitud_prestamo.numero_hijos_datos_personales,
                      solicitud_prestamo.tiempo_residencia_vivienda,
					  solicitud_prestamo.nombre_propietario_vivienda,
					  solicitud_prestamo.celular_propietario_vivienda,
					  solicitud_prestamo.referencia_direccion_domicilio_vivienda,
                      solicitud_prestamo.nombres_referencia_personal,
					  solicitud_prestamo.numero_telefonico_referencia_personal";
       
       $tablas   = "public.solicitud_prestamo";
       
       $where    = "solicitud_prestamo.id_solicitud_prestamo=".$id_solicitud;
       
       $resultSet=$db->getCondiciones($columnas, $tablas, $where);
       
       $columnas="id_participes";
       $tablas="core_participes";
       $where="cedula_participes='".$cedula_participe."'";
       $id="id_participes";
       $id_participes=$reporte->getCondiciones($columnas, $tablas, $where, $id);
       $id_participes=$id_participes[0]->id_participes;
       
       $direccion_participe=$resultSet[0]->barrio_sector_vivienda." ".$resultSet[0]->ciudadela_conjunto_etapa_manzana_vivienda." ".$resultSet[0]->calle_vivienda." ";
       $direccion_participe.=$resultSet[0]->numero_calle_vivienda." ".$resultSet[0]->intersecion_vivienda;
       
       $nombre_conyuge="N/A";
       if(!(empty($resultSet[0]->nombres_conyuge))) $nombre_conyuge=$resultSet[0]->nombres_conyuge;
       $apellido_conyuge="N/A";
       if(!(empty($resultSet[0]->apellidos_conyuge))) $apellido_conyuge=$resultSet[0]->apellidos_conyuge;
       $cedula_conyuge="N/A";
       if(!(empty($resultSet[0]->numero_cedula_conyuge))) $cedula_conyuge=$resultSet[0]->numero_cedula_conyuge;
       
       $where = "id_participes=".$id_participes;
       $tabla = "core_participes";
       $colval = "fecha_nacimiento_participes='".$resultSet[0]->fecha_nacimiento_datos_personales."',
                   direccion_participes='".$direccion_participe."',
                   telefono_participes='".$resultSet[0]->numero_casa_solicitante."',
                   celular_participes='".$resultSet[0]->numero_celular_solicitante."',
                   ocupacion_participes='".$resultSet[0]->cargo_actual_datos_laborales."',
                   nombre_conyugue_participes='".$nombre_conyuge."',
                   apellido_esposa_participes='".$apellido_conyuge."',
                   cedula_conyugue_participes='".$cedula_conyuge."',
                   numero_dependencias_participes=".$resultSet[0]->numero_hijos_datos_personales;
       $reporte->UpdateBy($colval, $tabla, $where);
       
       
       $columnas = "nombre_parroquias";
       $tablas   = "public.parroquias";
       
       $where    = "id_parroquias=".$resultSet[0]->id_parroquias_vivienda;
       
       $nombre_parroquias=$db->getCondiciones($columnas, $tablas, $where);
       $nombre_parroquias=$nombre_parroquias[0]->nombre_parroquias;
       
       $anios_residencia=$resultSet[0]->tiempo_residencia_vivienda;
       $anios_residencia=explode(" ", $anios_residencia);
       $anios_residencia=$anios_residencia[0];
       
       $where = "id_participes=".$id_participes;
       $tabla = "core_participes_informacion_adicional";
       $colval = "parroquia_participes_informacion_adicional='".$nombre_parroquias."',
                    sector_participes_informacion_adicional='".$resultSet[0]->barrio_sector_vivienda."',
                    ciudadela_participes_informacion_adicional='".$resultSet[0]->ciudadela_conjunto_etapa_manzana_vivienda."',
                    calle_participes_informacion_adicional='".$resultSet[0]->calle_vivienda."',
                    numero_calle_participes_informacion_adicional='".$resultSet[0]->numero_calle_vivienda."',
                    interseccion_participes_informacion_adicional='".$resultSet[0]->intersecion_vivienda."',
                    anios_residencia_participes_informacion_adicional='".$anios_residencia."',
                    nombre_propietario_participes_informacion_adicional='".$resultSet[0]->nombre_propietario_vivienda."',
                    telefono_propietario_participes_informacion_adicional='".$resultSet[0]->celular_propietario_vivienda."',
                    direccion_referencia_participes_informacion_adicional='".$resultSet[0]->referencia_direccion_domicilio_vivienda."',
                    nombre_una_referencia_participes_informacion_adicional='".$resultSet[0]->nombres_referencia_personal."',
                    telefono_una_referencia_participes_informacion_adicional='".$resultSet[0]->numero_telefonico_referencia_personal."'";
       $reporte->UpdateBy($colval, $tabla, $where);
     
       
       $errores_actualizacion=ob_get_clean();
       $errores_actualizacion=trim($errores_actualizacion);
       return $errores_actualizacion;
   }
   
   public function ActualizarCuentasParticipes($id_solicitud, $numero_creditos)
   {
       ob_start();
       $usuario_usuarios=$_SESSION['usuario_usuarios'];
       require_once 'core/DB_Functions.php';
       $db = new DB_Functions();
       $rp_capremci=new PlanCuentasModel();
       
       
       $columnas="		  solicitud_prestamo.nombre_banco_cuenta_bancaria,
						  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
						  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
						  solicitud_prestamo.numero_cedula_datos_personales,
                      solicitud_prestamo.tipo_pago_cuenta_bancaria";
       $tablas=" public.solicitud_prestamo";
       $where= "solicitud_prestamo.id_solicitud_prestamo='$id_solicitud'";
       $id="solicitud_prestamo.id_solicitud_prestamo";
       
       $resultSoli=$db->getCondicionesDesc($columnas, $tablas, $where, $id);
       if (!(empty($resultSoli)))
       {
           if($resultSoli[0]->tipo_pago_cuenta_bancaria=="Depósito")
           {
               
               
               $nombre_banco=$resultSoli[0]->nombre_banco_cuenta_bancaria;
               
               $columnas="bankid";
               $tablas="bancos";
               $where= "nombre_bancos='".$nombre_banco."'";
               $id="bankid";
               $id_banco=$db->getCondicionesDesc($columnas, $tablas, $where, $id);
               $id_banco=$id_banco[0]->bankid;
               
               
               
               $numero_cuenta=$resultSoli[0]->numero_cuenta_cuenta_bancaria;
               $numero_cedula=$resultSoli[0]->numero_cedula_datos_personales;
               $tipo_cuenta=$resultSoli[0]->tipo_cuenta_cuenta_bancaria;
               
               $columnas="id_participes";
               $tablas=" public.core_participes";
               $where= "cedula_participes='$numero_cedula'";
               $id="id_participes";
               
               $id_participes=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
               $id_participes=$id_participes[0]->id_participes;
               
               if($tipo_cuenta=='Ahorros')
               {
                   $tipo_cuenta='AHORROS';
               }
               else
               {
                   $tipo_cuenta='CORRIENTE';
               }
               
               $ip_adress=$_SERVER['REMOTE_ADDR'];
               $columnas="id_tipo_cuentas";
               $tablas=" public.core_tipo_cuentas";
               $where= "nombre_tipo_cuentas='$tipo_cuenta'";
               $id="id_tipo_cuentas";
               
               $id_tipo_cuentas=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
               $id_tipo_cuentas=$id_tipo_cuentas[0]->id_tipo_cuentas;
               
               $funcion = "ins_core_participes_cuentas";
               /*$parametros="'$id_participes',
                     '$id_banco',
                     '$numero_cuenta',
                     '$id_tipo_cuentas',
                     1,
                     'true',
                     '$usuario_usuarios',
                        '$ip_adress'";*/
               
               $parametros=$id_participes.', '.$id_banco.', \''.$numero_cuenta.'\',';
               $parametros.=$id_tipo_cuentas.',\'true\' ,\''.$usuario_usuarios.'\',\''.$ip_adress.'\'';
               $rp_capremci->setFuncion($funcion);
               $rp_capremci->setParametros($parametros);
               $resultado=$rp_capremci->Insert();
               
               $columnas="id_forma_pago";
               $tablas=" public.forma_pago";
               $where= "nombre_forma_pago='TRANSFERENCIA'";
               $id="id_forma_pago";
               
               $id_forma_pagos=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
               $id_forma_pagos=$id_forma_pagos[0]->id_forma_pago;
               
               $where = "numero_creditos='".$numero_creditos."'";
               $tabla = "core_creditos";
               $colval = "id_forma_pago=".$id_forma_pagos;
               $rp_capremci->UpdateBy($colval, $tabla, $where);
           }
           else {
               $columnas="id_forma_pago";
               $tablas=" public.forma_pago";
               $where= "nombre_forma_pago='CHEQUE'";
               $id="id_forma_pago";
               
               $id_forma_pagos=$rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
               $id_forma_pagos=$id_forma_pagos[0]->id_forma_pago;
               
               $where = "numero_creditos='".$numero_creditos."'";
               $tabla = "core_creditos";
               $colval = "id_forma_pago=".$id_forma_pagos;
               $rp_capremci->UpdateBy($colval, $tabla, $where);
           }
           
           $errores_cuentas=ob_get_clean();
           $errores_cuentas=trim($errores_cuentas);
           
       }
       
       else 
       {
           $errores_cuentas="NO SE PUDO CONSEGUIR LA INFO";
       }
       
       
       
      
       
       
       return $errores_cuentas;       
   }
   
   public function GetAvaluoHipotecario()
   {
       session_start();
       $rp_capremci= new PlanCuentasModel();
       $monto_maximo=0;
       $id_solicitud=$_POST['id_solicitud'];
       $tipo_credito_hipotecario=$_POST['tipo_credito_hipotecario'];
       $columnas="valor_avaluo_core_documentos_hipotecario";
       $tablas="core_documentos_hipotecario";
       $where="id_solicitud_credito=".$id_solicitud;
       $avaluo_credito=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       if(sizeof($avaluo_credito)>0)
       {
           $avaluo_credito=$avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
           if($tipo_credito_hipotecario==1)
           {
               $monto_maximo=$avaluo_credito*0.8;
               if($monto_maximo>100000) $monto_maximo=100000;
           }
           else
           {
               $monto_maximo=$avaluo_credito*0.5;
               if($monto_maximo>45000) $monto_maximo=45000;
           }
           $avaluo_credito=number_format((float)$avaluo_credito,2,".","");
           $monto_maximo=number_format((float)$monto_maximo,2,".","");
           $html='<table>
        <tr>
        <td><font size="3">Avalúo del bien : '.$avaluo_credito.'</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : '.$monto_maximo.'</font></td>
        </tr>
        <tr>
        <td>
        <span class="input-group-btn">
        <button  type="button" class="btn bg-olive" title="Cambiar Modalidad" onclick="TipoCredito()"><i class="glyphicon glyphicon-refresh"></i></button>
        <button  type="button" class="btn bg-olive" title="Escrituras" onclick="TipoCredito()"><i class="glyphicon glyphicon-book"></i></button>
        <button  type="button" class="btn bg-olive" title="Certificado" onclick="TipoCredito()"><i class="glyphicon glyphicon-check"></i></button>
        <button  type="button" class="btn bg-olive" title="Impuestos" onclick="TipoCredito()"><i class="fa fa-black-tie"></i></button>
        <button  type="button" class="btn bg-olive" title="Avaluo" onclick="TipoCredito()"><i class="glyphicon glyphicon-usd"></i></button>
        </span>
        </td>
        </tr>
        </table>';
       }
       else {
           $html='<table>
        <tr>
        <td><font size="3">No hay avaluo registrado</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : 0.00</font></td>
        </tr>
        
        </table>';
       }
       
       
       echo $html;
   }
   
   public function cuotaParticipe(){
       session_start();
       $creditos = new EstadoModel();
       $cedula_participe=$_POST['cedula_participe'];
       $tipo_credito=$_POST['tipo_credito'];
       $cuota_total=0;
       $columnas="id_tipo_creditos_a_renovar";
       $tablas="core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $id_creditos_renovar=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       
       foreach($id_creditos_renovar as $res)
       {
           $columnas='monto_otorgado_creditos, plazo_creditos, interes_creditos';
           $tablas='core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes = core_participes.id_participes';
               
               $where="core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
            AND core_creditos.id_estatus=1 AND id_tipo_creditos=".$res->id_tipo_creditos_a_renovar;
           
           $id_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
           
           foreach ($id_credito as $res1)
           {
               $tasa_interes=$res1->interes_creditos;
               $tasa_interes=$tasa_interes/100;
               $interes_mensual = $tasa_interes / 12;
               $valor_cuota =  ($res1->monto_otorgado_creditos * $interes_mensual) /  (1- pow((1+$interes_mensual), -$res1->plazo_creditos))  ;
               $valor_cuota=round($valor_cuota,2);
               $cuota_total+=$valor_cuota;
           }
           
       }
       echo $cuota_total;
   }
   
   public function cuotaParticipe1(){
       session_start();
       $creditos = new EstadoModel();
       $cedula_participe=$_SESSION['cedula_usuarios'];
       $tipo_credito=$_POST['tipo_credito'];
       $cuota_total=0;
       $columnas="id_tipo_creditos_a_renovar";
       $tablas="core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       $id_creditos_renovar=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       
       foreach($id_creditos_renovar as $res)
       {
           $columnas='monto_otorgado_creditos, plazo_creditos, interes_creditos';
           $tablas='core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes = core_participes.id_participes';
           
           $where="core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
            AND core_creditos.id_estatus=1 AND id_tipo_creditos=".$res->id_tipo_creditos_a_renovar;
           
           $id_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
           
           foreach ($id_credito as $res1)
           {
               $tasa_interes=$res1->interes_creditos;
               $tasa_interes=$tasa_interes/100;
               $interes_mensual = $tasa_interes / 12;
               $valor_cuota =  ($res1->monto_otorgado_creditos * $interes_mensual) /  (1- pow((1+$interes_mensual), -$res1->plazo_creditos))  ;
               $valor_cuota=round($valor_cuota,2);
               $cuota_total+=$valor_cuota;
           }
           
       }
       echo $cuota_total;
   }
   
   public function cuotaGarante(){
       session_start();
       $creditos = new EstadoModel();
       $cedula_participe=$_POST['cedula_participe'];
       $cuota_total=0;
       $columnas='monto_otorgado_creditos, plazo_creditos, interes_creditos';
       $tablas='core_creditos INNER JOIN core_participes
        ON core_creditos.id_participes = core_participes.id_participes
        INNER JOIN core_tipo_creditos
        ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos';
      
           $where="core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
        AND core_creditos.id_estatus=1";
      
       $id_credito=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       
       
       foreach ($id_credito as $res)
       {
           $tasa_interes=$res->interes_creditos;
           $tasa_interes=$tasa_interes/100;
           $interes_mensual = $tasa_interes / 12;
           $valor_cuota =  ($res->monto_otorgado_creditos * $interes_mensual) /  (1- pow((1+$interes_mensual), -$res->plazo_creditos))  ;
           $valor_cuota=round($valor_cuota,2);
           $cuota_total+=$valor_cuota;
       }
       
       
       
       echo $cuota_total*-1;
   }    
   
   public function ActualizarSolicitud($id_solicitud, $monto, $plazo, $id_credito)
   {
       ob_start();
       require_once 'core/DB_Functions.php';
       $db = new DB_Functions();
       $colval="id_estado_tramites=2, monto_datos_prestamo=".$monto.", plazo_datos_prestamo=".$plazo.", numero_creditos=".$id_credito;
       $tabla="solicitud_prestamo";
       $where="id_solicitud_prestamo=".$id_solicitud;
       $db->ActualizarBy($colval, $tabla, $where);
       
       return ob_get_clean();
   }
   
   public function genera_codigo(){
       
       session_start();
       $rp_capremci=new PlanCuentasModel();
       $tipo_creditos=$_POST['tipo_credito'];
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_creditos."'";
       
       $resultSet=$rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $interes_credito=$resultSet[0]->interes_tipo_creditos;
       
       $cadena = "1234567890"; 
       $longitudCadena=strlen($cadena);
       $codigo = "";
       $longitudPass=5;
       for($i=1 ; $i<=$longitudPass ; $i++){
           $pos=rand(0,$longitudCadena-1);
           $codigo .= substr($cadena,$pos,1);
       }
       $resultado=array();
       array_push($resultado, $codigo, $interes_credito);
       echo json_encode($resultado);
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
   
   
   public function ReporteSimulacionCredito()
   {
       session_start();
       $cuotas= new PlanCuentasModel();
       $monto_credito=$_POST['monto_credito'];
       $entidades = new EntidadesModel();
       $id_solicitud=$_POST['id_solicitud'];
       $fecha_corte=date('Y-m-d');
       if($id_solicitud==0) $avaluo_bien=$_POST['avaluo_bien'];
       else $avaluo_bien=0;
       $cuota=$_POST['plazo_credito'];
       $tipo_credito=$_POST['tipo_credito'];
       $renovacion_credito=$_POST['renovacion_credito'];
       
       
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
       
       
       
       
       $rptamortizacion= array();
       
       
       if($tipo_credito=="PH" && $id_solicitud!=0)
       {
           $columnas="valor_avaluo_core_documentos_hipotecario";
           $tablas="core_documentos_hipotecario";
           $where="id_solicitud_credito=".$id_solicitud;
           $avaluo_credito=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
           $avaluo_credito=$avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
       }
       else $avaluo_credito=$avaluo_bien;
       
       
       $columnas="interes_tipo_creditos";
       $tablas="core_tipo_creditos";
       $where="codigo_tipo_creditos='".$tipo_credito."'";
       
       $resultSet=$cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $tasa_interes=$resultSet[0]->interes_tipo_creditos;
       $tasa_interes=$tasa_interes/100;
       $interes_mensual = $tasa_interes / 12;
       $plazo_dias = $cuota * 30;
       
       $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
       $valor_cuota=round($valor_cuota,2);
       
       if ($tipo_credito=="PH")
       {
           if($renovacion_credito=="true")
           {
               $resultAmortizacion=$this->tablaAmortizacionRenovacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
           }
           else
           {
               $resultAmortizacion=$this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
           }
       }
       else
       {
           if($renovacion_credito=="true")
           {
               $resultAmortizacion=$this->tablaAmortizacionRenovacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
           }
           else
           {
               $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
           }
       }
       
       
       
       if($tipo_credito=="PH")
       {
           $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
           if($id_solicitud!=0)    $html.='<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
           $html.='</div>
             <table class="12" border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="15%" >Fecha</th>
                        <th width="13%">Capital</th>
                        <th width="13%">Interes</th>
                        <th width="13%">Seg. Desgravamen</th>
                        <th width="13%">Seg. Incendio</th>
                        <th width="13%">Cuota</th>
                        <th width="13%">Saldo</th>
                        
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table class="12" border="1" width="100%">';
       }
       else
       {
           $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
           if($id_solicitud!=0) $html.='<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
           $html.= '</div>
             <table class="12" border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="18%" >Fecha</th>
                        <th width="15%">Capital</th>
                        <th width="15%">Interes</th>
                        <th width="15%">Seg. Desgravamen</th>
                        <th width="15%">Cuota</th>
                        <th width="15%">Saldo</th>
                       
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table class="12" border="1" width="100%">';
       }
       
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
       $total_incendio=0;
       
       foreach ($resultAmortizacion as $res)
       {
           
           $res['desgravamen']=number_format((float)$res['desgravamen'],2,".","");
           $total_desg+=$res['desgravamen'];
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $total_capital+=$res['amortizacion'];
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos'];
           if($tipo_credito=="PH")
           {
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".","");
               $total_incendio+=$res['seguro_incendios'];
           }
           
           
           
       }
       $total=round($total,2);
       $total1=round($total1,2);
       $num=$monto_credito-($total1-$total);
       $num=round($num,2);
       $len=sizeof($resultAmortizacion);
       $res['amortizacion']=round($res['amortizacion'],2);
       $res['interes']=round($res['interes'],2);
       $res['pagos']=round($res['pagos'],2);
       
       $resultAmortizacion[$len-1]['pagos']=$resultAmortizacion[$len-1]['pagos']+$resultAmortizacion[$len-1]['saldo_inicial'];
       $diferencia=($resultAmortizacion[$len-1]['pagos']-$resultAmortizacion[$len-1]['interes']);
       
       $resultAmortizacion[$len-1]['amortizacion']=$resultAmortizacion[$len-1]['amortizacion']+$resultAmortizacion[$len-1]['saldo_inicial'];
       $resultAmortizacion[$len-1]['saldo_inicial']=0.00;
       //$resultAmortizacion[$len-1]['interes']=$diferencia;
       
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
       $total_incendio=0;
       foreach ($resultAmortizacion as $res)
       {
           
           $res['desgravamen']=number_format((float)$res['desgravamen'],2,".","");
           $total_desg+=$res['desgravamen'];
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $total_capital+=$res['amortizacion'];
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos']+$res['desgravamen'];
           
           if($tipo_credito=="PH")
           {
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".","");
               $total_incendio+=$res['seguro_incendios'];
           }
           
       }
       
       
       
       
       if($tipo_credito=="PH")
       {
           foreach ($resultAmortizacion as $res)
           {
               /*<th width="5%">Cuota</th>
                <th width="15%" >Fecha</th>
                <th width="13%">Capital</th>
                <th width="13%">Interes</th>
                <th width="13%">Seg. Desgravamen</th>
                <th width="13%">Seg. Incendio</th>
                <th width="13%">Cuota</th>
                <th width="13%">Saldo</th>
                <th width="2%"></th>*/
               
               $html.='<tr>';
               $html.='<td width="5%" bgcolor="white"><font color="black">'.$res['pagos_trimestrales'].'</font></td>';
               $html.='<td width="15%" bgcolor="white" align="center"><font color="black">'.$res['fecha_pago'].'</font></td>';
               $res['amortizacion']=number_format((float)$res['amortizacion'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['amortizacion'].'</font></td>';
               $res['interes']=number_format((float)$res['interes'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['interes'].'</font></td>';
               $cuota_pagar=$res['desgravamen']+$res['pagos'];
               $res['desgravamen']=number_format((float)$res['desgravamen'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="desgravamen'.$res['pagos_trimestrales'].'">'.$res['desgravamen'].'</font></td>';
               $res['seguro_incendios']=number_format((float)$res['seguro_incendios'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio'.$res['pagos_trimestrales'].'">'.$res['seguro_incendios'].'</font></td>';
               $cuota_pagar=number_format((float)$cuota_pagar,2,".",",");
               $html.='<td  width="13.2%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar'.$res['pagos_trimestrales'].'">'.$cuota_pagar.'</font></td>';
               $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
               $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
               $html.='</tr>';
               
               
           }
           
           $html.='<tr>';
           $html.='<td width="5%" bgcolor="white"><font color="black"></font></td>';
           $html.='<td width="15%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
           $total_capital=number_format((float)$total_capital,2,".",",");
           $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total_capital.'</font></td>';
           $total=number_format((float)$total,2,".",",");
           $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total.'</font></td>';
           $total_desg=number_format((float)$total_desg,2,".",",");
           $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black">'.$total_desg.'</font></td>';
           $total_incendio=number_format((float)$total_incendio,2,".",",");
           $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio'.$res['pagos_trimestrales'].'">'.$total_incendio.'</font></td>';
           $total1=number_format((float)$total1,2,".",",");
           $html.='<td width="13.2%" bgcolor="white" align="right"><font color="black">'.$total1.'</font></td>';
           $html.='<td width="13.4%" bgcolor="white" align="right"><font color="black"></font></td>';
           $html.='</tr>';
       }
       else
       {
           foreach ($resultAmortizacion as $res)
           {
               
               $html.='<tr>';
               $html.='<td width="7.2%" bgcolor="white"><font color="black">'.$res['pagos_trimestrales'].'</font></td>';
               $html.='<td width="18.3%" bgcolor="white" align="center"><font color="black">'.$res['fecha_pago'].'</font></td>';
               $res['amortizacion']=number_format((float)$res['amortizacion'],2,".",",");
               $html.='<td width="15.3%" bgcolor="white" align="right"><font color="black">'.$res['amortizacion'].'</font></td>';
               $res['interes']=number_format((float)$res['interes'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['interes'].'</font></td>';
               $cuota_pagar=$res['desgravamen']+$res['pagos'];
               $res['desgravamen']=number_format((float)$res['desgravamen'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black" id="desgravamen'.$res['pagos_trimestrales'].'">'.$res['desgravamen'].'</font></td>';
               $cuota_pagar=number_format((float)$cuota_pagar,2,".",",");
               $html.='<td  width="15.4%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar'.$res['pagos_trimestrales'].'">'.$cuota_pagar.'</font></td>';
               $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
               $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
               $html.='</tr>';
               
               
           }
           
           $html.='<tr>';
           $html.='<td width="5%" bgcolor="white"><font color="black"></font></td>';
           $html.='<td width="18%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
           $total_capital=number_format((float)$total_capital,2,".",",");
           $html.='<td width="15.2%" bgcolor="white" align="right"><font color="black">'.$total_capital.'</font></td>';
           $total=number_format((float)$total,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total.'</font></td>';
           $total_desg=number_format((float)$total_desg,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total_desg.'</font></td>';
           $total1=number_format((float)$total1,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$total1.'</font></td>';
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black"></font></td>';
           $html.='</tr>';
       }
       
       
       
       
       $html.='</table>
              </div>';
       
       $rptamortizacion['DETALLE']= $html;
       
       $this->verReporte("ReporteSimulador", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'rptamortizacion'=>$rptamortizacion));
       
   }
   
   
   
   
   
}


?>