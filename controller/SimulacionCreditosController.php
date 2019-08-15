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
    <h4 id="nombre_participe_credito">'.$infoParticipe[0]->nombre_participes.' '.$infoParticipe[0]->apellido_participes.'</h4>
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
       $fecha_corte=date('Y-m-d');
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
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
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
    //   $resultAmortizacion[$len-1]['interes']=$diferencia;*/
    
       $total=0;
       $total1=0;
       $total_capital=0;
       $total_desg=0;
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
     
       }
      
       foreach ($resultAmortizacion as $res)
       {
           if($res['pagos_trimestrales']!=0)
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
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['desgravamen'].'</font></td>';
           $cuota_pagar=number_format((float)$cuota_pagar,2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$cuota_pagar.'</font></td>';
           $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".",",");
           $html.='<td width="15.4%" bgcolor="white" align="right"><font color="black">'.$res['saldo_inicial'].'</font></td>';
           $html.='</tr>';
           }
           
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
       
       for( $i = 0; $i <= $numero_cuotas; $i++) {
           
           if ($i == 0)
           {
               $interes= 0;
               $amortizacion = 0;
               $saldo_inicial= $capital;
               $fecha=strtotime('+0 day',strtotime($fecha_corte));
               $elementos_fecha=explode("-", $fecha_corte);
               $lastday = date('t',strtotime($fecha));
               $diferencia_dias=$lastday-$elementos_fecha[2];
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha=date('Y-m-d',strtotime($fecha_ultimo_dia));
               $fecha_corte=$fecha;
               $valor = 0;
               $desgravamen=0;
               $saldo_inicial_ant = $capital;
           }
           else
           {
               $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
               $interes= $saldo_inicial_ant * $inter_ant;
               $interes=floor($interes * 100) / 100;
               $amortizacion = $valor_cuota - $interes;
               if($i==1)
               {
                $interes_concesion=$interes_diario*$diferencia_dias*$capital;
                $interes_concesion=round($interes_concesion,2);
                $interes+=$interes_concesion;
               }
               
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
               $fecha=strtotime('+0 day',strtotime($fecha_corte));
               $fecha=date('Y-m-d',$fecha);
               $elementos_fecha=explode("-", $fecha);
               $lastday = date('t',strtotime($fecha));
               $fecha_ultimo_dia=$elementos_fecha[0]."-".$elementos_fecha[1]."-".$lastday;
               $fecha=date('Y-m-d',strtotime($fecha_ultimo_dia));
               $fecha_corte=$fecha;
               $valor = $valor_cuota;
               
               
           }
           $arreglo=array('pagos_trimestrales'=> $i,
               'saldo_inicial'=>$saldo_inicial,
               'interes'=>$interes,
               'amortizacion'=>$amortizacion,
               'pagos'=>$valor,
               'desgravamen'=>$desgravamen,
               'fecha_pago'=>$fecha
           );
           array_push($resultAmortizacion, $arreglo);
       }
       
       return $resultAmortizacion;
   }
   
   public function SubirInformacionCredito()
   {
       session_start();
       $mensage="";
       $respuesta=true;
       $credito=new CoreTipoCreditoModel();
       $usuario=$_SESSION['usuario_usuarios'];
       $monto_credito=$_POST['monto_credito'];
       $tasa_interes=$_POST['tasa_interes'];
       $fecha_pago=$_POST['fecha_pago'];
       $interes_credito=$tasa_interes;
       $id_tipo_creditos=0;
       if($tasa_interes==9) $id_tipo_creditos=4; //quemado cambiar por bd
       else $id_tipo_creditos=2;
       $tasa_interes=$tasa_interes/100;
       $cuota=$_POST['cuota_credito'];
       $cedula_participe=$_POST['cedula_participe'];
       $observacion_credito=$_POST['observacion_credito'];
       $id_solicitud=$_POST['id_solicitud'];
       
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
       
       //cambiar numero de credito por numero solicitud
       $credito->beginTran();
       $funcion = "ins_core_creditos";       
       $parametros="'$numero_credito',
                     '$numero_credito',
                     '$id_participe',
                     '$monto_credito',
                     '$monto_credito',
                     '$hoy',
                     2,
                     '$cuota',
                     '$monto_credito',
                     '$id_tipo_creditos',
                     '$numero_credito', 
                     '$observacion_credito',
                     1,
                     '$usuario',
                     '$interes_credito',
                     '$hoy'";
       $credito->setFuncion($funcion);
       $credito->setParametros($parametros);
       $resultado=$credito->Insert();
       
       if($resultado=="Insertado Correctamente")
       {
           $interes_mensual = $tasa_interes / 12;
           $plazo_dias = $cuota * 30;
           
           $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
           $valor_cuota=round($valor_cuota,2);
           $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes);
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
           $funcion = "ins_core_tabla_amortizacion";
           foreach ($resultAmortizacion as $res)
           {
               
              $fecha_pago=$res['fecha_pago'];              
               $num_cuota=$res['pagos_trimestrales'];
               $amortizacion=$res['amortizacion'];
               $intereses=$res['interes'];
               $saldo_inicial=$res['saldo_inicial'];
               $desgravamen=$res['desgravamen'];
               $dividendo=$res['pagos'];
               $total_valor=$amortizacion+$intereses+$desgravamen;
               $parametros="'$numero_credito',
                     '$fecha_pago',
                     '$num_cuota',
                     '$amortizacion',
                     '$intereses',
                     '$dividendo',
                     '$saldo_inicial',
                     '$desgravamen',
                     '$total_valor',
                     3,
                     1,
                     '$tasa_interes',
                     '$hoy'";
               $credito->setFuncion($funcion);
               $credito->setParametros($parametros);
              $resultado=$credito->Insert();
               if($resultado!="Insertado Correctamente")
               {
                   $credito->endTran('ROLLBACK');
                   $respuesta=false;
                   $mensage="ERROR";
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
                       $credito->endTran('COMMIT');
                       $mensage="OK";
                   }
                   else {
                       $credito->endTran('ROLLBACK');
                       $mensage="ERROR";
                   }
                   
               }
               else
               {
                   echo "solicitud no aceptada";
                   $credito->endTran('ROLLBACK');
                   $mensage="ERROR";
               }
               
    
           }
       }
       else
       {
           $credito->endTran('ROLLBACK');
           $mensage="ERROR";
       }
      
       echo $mensage;
       
   }
   
   public function ActualizarSolicitud($id_solicitud, $monto, $plazo, $id_credito)
   {
       ob_start();
       require_once 'core/DB_Functions.php';
       $db = new DB_Functions();
       $colval="id_estado_tramites=2, monto_datos_prestamo=".$monto.", plazo_datos_prestamo=".$plazo.", identificador_consecutivos=".$id_credito;
       $tabla="solicitud_prestamo";
       $where="id_solicitud_prestamo=".$id_solicitud;
       $db->ActualizarBy($colval, $tabla, $where);
       
       return ob_get_clean();
   }
   
   public function genera_codigo(){
       
       $cadena = "1234567890";
       $longitudCadena=strlen($cadena);
       $codigo = "";
       $longitudPass=5;
       for($i=1 ; $i<=$longitudPass ; $i++){
           $pos=rand(0,$longitudCadena-1);
           $codigo .= substr($cadena,$pos,1);
       }
       
       echo $codigo;
   }
   
   
}


?>