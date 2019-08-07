<?php
class SimulacionCreditosController extends ControladorBase{
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
    
   public function CreditoParticipe()
   {
   session_start();
   $creditos= new ParticipesModel();
   $cedula_participes = $_POST['cedula_participe'];
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
   $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
   $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
   $where="core_participes.cedula_participes='".$cedula_participes."' AND core_contribucion.id_estatus=1";
   $totalCtaIndividual=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
   $columnas="saldo_actual_creditos";
   $tablas="core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
   $where="core_participes.cedula_participes='".$cedula_participes."' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
   $id="core_creditos.id_participes";
   $saldo_actual_credito=$creditos->getCondiciones($columnas, $tablas, $where, $id);
   
   $columnas="valor_personal_contribucion";
   $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes=core_participes.id_participes";
   $where="cedula_participes='".$cedula_participes."' AND id_estado_contribucion=1 AND core_contribucion.id_estatus=1 AND id_contribucion_tipo=1
    AND fecha_registro_contribucion BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
   $id="fecha_registro_contribucion";
   $aportes=$creditos->getCondiciones($columnas, $tablas, $where, $id);
   $num_aporte=sizeof($aportes);
   if(!(empty($saldo_actual_credito))) $saldo_credito=$saldo_actual_credito[0]->saldo_actual_creditos;
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
   $edad=explode(",",$tiempo);
   $edad=$edad[0];
   $edad=explode(" ", $edad);
   $edad=$edad[0];
   if($disponible>150 && $edad<75 && $num_aporte==3) $solicitud="bg-olive";
   else $solicitud="bg-red";
   $html='<div id="disponible_participe" class="small-box '.$solicitud.'">
   <div class="inner">
   <table width="100%">
    <tr>
   <td width="50%">
    <h4>'.$infoParticipe[0]->nombre_participes.' '.$infoParticipe[0]->apellido_participes.'</h4>
   <h4 id="cedula_credito"> Cédula : '.$infoParticipe[0]->cedula_participes.'</h4>
    <h4>Fecha de nacimiento : '.$infoParticipe[0]->fecha_nacimiento_participes.'</h4>
    <h4>Edad : '.$tiempo.'</h4>  
    <h4 id="monto_disponible"> Disponible : '.$disponible.'</h4>';
    if($num_aporte<3)$html.='<h4 id="aportes_participes">El participe tiene '.$num_aporte.' de los 3 últimos aportes pagados</h4>';
    $html.='</td>
    <td width="50%">
    <div id="info_garante"></div>
    </td>
    </tr>
    </table>
   </div>
   </div>';
   echo $html;
   }
   
   public function BuscarGarante()
   {
       session_start();
       $creditos= new ParticipesModel();
       $cedula_garante = $_POST['cedula_garante'];
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
       $columnas="SUM(valor_personal_contribucion)+SUM(valor_patronal_contribucion) AS total";
       $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_contribucion.id_estatus=1";
       $totalCtaIndividual=$creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
       $columnas="saldo_actual_creditos";
       $tablas="core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
       $where="core_participes.cedula_participes='".$cedula_garante."' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
       $id="core_creditos.id_participes";
       $saldo_actual_credito=$creditos->getCondiciones($columnas, $tablas, $where, $id);
       $columnas="valor_personal_contribucion";
       $tablas="core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes=core_participes.id_participes";
       $where="cedula_participes='".$cedula_garante."' AND id_estado_contribucion=1 AND core_contribucion.id_estatus=1 AND id_contribucion_tipo=1
        AND fecha_registro_contribucion BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
       $id="fecha_registro_contribucion";
       $aportes=$creditos->getCondiciones($columnas, $tablas, $where, $id);
       $num_aporte=sizeof($aportes);
       if(!(empty($saldo_actual_credito))) $saldo_credito=$saldo_actual_credito[0]->saldo_actual_creditos;
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
           $hoy=date("Y-m-d");
           
           $tiempo=$this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
           $edad=explode(",",$tiempo);
           $edad=$edad[0];
           $edad=explode(" ", $edad);
           $edad=$edad[0];
           $html='  <button class="btn btn-default pull-right" onclick="QuitarGarante()"><i class="glyphicon glyphicon-remove"></i></button>
        <h4>'.$infoParticipe[0]->nombre_participes.' '.$infoParticipe[0]->apellido_participes.'(GARANTE)</h4>
   <h4> Cédula : '.$infoParticipe[0]->cedula_participes.'</h4>
    <h4>Fecha de nacimiento : '.$infoParticipe[0]->fecha_nacimiento_participes.'</h4>
    <h4 id="edad_garante">Edad : '.$tiempo.'</h4>
    <h4 id="monto_garante_disponible"> Disponible : '.$disponible.'</h4>';
    if($num_aporte<3)$html.='<h4 id="aportes_garante">El participe tiene '.$num_aporte.' de los 3 últimos aportes pagados</h4>';
           echo $html;
       }
       else echo "Participe no encontrado";
       
       
   }
   
   public function GetCuotas()
   {
       session_start();
       $monto_credito=$_POST['monto_credito'];
       $cuotas = new EstadoModel();
       $tablas="public.core_plazos_creditos";
       $where="1=1";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones("*", $tablas, $where, $id);
       foreach($resultSet as $res)
       {
           if($monto_credito>=$res->minimo_rango_plazos_creditos && $monto_credito<=$res->maximo_rango_plazos_creditos)
           {
               $cuota=$res->cuotas_rango_plazos_creditos;
               
               break;
           }
       }
       $html='<label for="tipo_credito" class="control-label">Número de cuotas:</label>
       <select name="cuotas_credito" id="cuotas_credito"  class="form-control" onchange="SimularCredito()">';
       for($cuota; $cuota>=3; $cuota-=3)
       {
          $html.='<option value="'.$cuota.'">'.$cuota.'</option>';
       }
       
       
       $html.='</select>
       <div id="mensaje_cuotas_credito" class="errores"></div>';
       
       echo $html;
   }
   
   public function SimulacionCredito()
   {
       session_start();
       $monto_credito=$_POST['monto_credito'];
       $tasa_interes=$_POST['tasa_interes'];
       $fecha_corte=$_POST['fecha_corte'];
       $tasa_interes=$tasa_interes/100;
       $cuota=$_POST['plazo_credito'];
       $interes_mensual = $tasa_interes / 12;
       $plazo_dias = $cuota * 30;
     
       $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
       $valor_cuota=round($valor_cuota,2);
       $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
       $html='<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>
            <button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="16%">Saldo Restante</th>
                        <th width="16%">Interes a pagar</th>
                        <th width="16%">Amortización</th>
                        <th width="16%">A Pagar</th>
                        <th width="18%" >Fecha de Pago</th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
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
  //     $diferencia=($resultAmortizacion[$len-1]['pagos']-$resultAmortizacion[$len-1]['interes']);
       
       $resultAmortizacion[$len-1]['amortizacion']=$resultAmortizacion[$len-1]['amortizacion']+$resultAmortizacion[$len-1]['saldo_inicial'];
       $resultAmortizacion[$len-1]['saldo_inicial']=0.00;
    //   $resultAmortizacion[$len-1]['interes']=$diferencia;
       $total=0;
       $total1=0;
       foreach ($resultAmortizacion as $res)
       {
           $html.='<tr>';
           $html.='<td width="4.9%" bgcolor="white"><font color="black">'.$res['pagos_trimestrales'].'</font></td>';
           $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $res['interes']=number_format((float)$res['interes'],2,".",",");
           $html.='<td width="15.6%" bgcolor="white" align="right"><font color="black">'.$res['interes'].'</font></td>';
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".",",");
           $html.='<td width="15.6%" bgcolor="white" align="right"><font color="black">'.$res['amortizacion'].'</font></td>';
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos'];
           $res['pagos']=number_format((float)$res['pagos'],2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['pagos'].'</font></td>';
           $html.='<td width="18%" bgcolor="white" align="center"><font color="black">'.$res['fecha_pago'].'</font></td>';
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
       
       
       $capital = $_capital_prestado_amortizacion_cabeza;
       $inter_ant= $interes_mensual;
       $interes=  $capital * $inter_ant;
       $interes=floor($interes * 100) / 100;
       $amortizacion = $valor_cuota - $interes;
       $saldo_inicial= $capital - $amortizacion;
       $resultAmortizacion=array();
       
       
       for( $i = 0; $i <= $numero_cuotas; $i++) {
           
           if ($i == 0)
           {
               $interes= 0;
               $amortizacion = 0;
               $saldo_inicial= $capital;
               $fecha=strtotime('+0 day',strtotime($fecha_corte));
               $fecha=date('Y-m-d',$fecha);
               $fecha_corte=$fecha;
               $valor = 0;
               $saldo_inicial_ant = $capital;
           }
           else
           {
               $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
               $interes= $saldo_inicial_ant * $inter_ant;
               $interes=floor($interes * 100) / 100;
               $amortizacion = $valor_cuota - $interes;
               
               $saldo_inicial= $saldo_inicial_ant  - $amortizacion;
               
               
               $fecha=strtotime('+1 month',strtotime($fecha_corte));
               $fecha=date('Y-m-d',$fecha);
               $fecha_corte=$fecha;
               $valor = $valor_cuota;
               
               
           }
           $arreglo=array('pagos_trimestrales'=> $i,
               'saldo_inicial'=>$saldo_inicial,
               'interes'=>$interes,
               'amortizacion'=>$amortizacion,
               'pagos'=>$valor,
               'fecha_pago'=>$fecha
           );
           array_push($resultAmortizacion, $arreglo);
       }
       
       return $resultAmortizacion;
   }
   
}


?>