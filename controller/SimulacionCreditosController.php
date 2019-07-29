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
    
    public function cuotaParticipe(){
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];
        
        $this->view_Credito("AnalisisCredito",array(
            "result" => ""
        ));
    }  
    
   public function SimulacionCredito()
   {
       session_start();
       $monto_credito=$_POST['monto_credito'];
       $tasa_interes=$_POST['tasa_interes'];
       $fecha_corte=$_POST['fecha_corte'];
       $tasa_interes=$tasa_interes/100;
       $cuotas = new EstadoModel();
       $tablas="public.core_plazos_creditos";
       $where="1=1";
       $id="core_plazos_creditos.id_plazos_creditos";
       $resultSet=$cuotas->getCondiciones("*", $tablas, $where, $id);
       $cuota=0;
       $dias=0;
       foreach($resultSet as $res)
       {
         if($monto_credito>=$res->minimo_rango_plazos_creditos && $monto_credito<=$res->maximo_rango_plazos_creditos)
         {
             $cuota=$res->cuotas_rango_plazos_creditos;
             
             break;
         }
       }
       $interes_mensual = $tasa_interes / 12;
       $plazo_dias = $cuota * 30;
       
       /*$exponente=(1+($tasa_interes/360*($dias/$cuota)));
       $exponente=pow($exponente,-$cuota);
       $y=(1-$exponente);
       $z=($tasa_interes/360*($dias/$cuota))*$monto_credito;
       $z=round($z,2);
       $x=$z/$y;
       $x=round($x,2);*/
       $valor_cuota =  ($monto_credito * $interes_mensual) /  (1- pow((1+$interes_mensual), -$cuota))  ;
       $valor_cuota=round($valor_cuota,2);
       $resultAmortizacion=$this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
       $html='';
       $html.= "<table id='tabla_reporte' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
       $html.= "<thead>";
       $html.='<tr>';
       $html.='<th>pagos_trimestrales</th>';
       $html.='<th>saldo_inicial</th>';
       $html.='<th>interes</th>';
       $html.='<th>amortizacion</th>';
       $html.='<th>pagos</th>';
       $html.='<th>fecha_pago</th>';
       $html.='</tr>';
       $html.='</thead>';
       $html.='<tbody>';
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
           $html.='<td>'.$res['pagos_trimestrales'].'</td>';
           $res['saldo_inicial']=number_format((float)$res['saldo_inicial'],2,".","");
           $html.='<td>'.$res['saldo_inicial'].'</td>';
           $res['interes']=number_format((float)$res['interes'],2,".","");
           $total+=$res['interes'];
           $html.='<td>'.$res['interes'].'</td>';
           $res['amortizacion']=number_format((float)$res['amortizacion'],2,".","");
           $html.='<td>'.$res['amortizacion'].'</td>';
           $res['pagos']=number_format((float)$res['pagos'],2,".","");
           $total1+=$res['pagos'];
           $html.='<td>'.$res['pagos'].'</td>';
           $html.='<td>'.$res['fecha_pago'].'</td>';
           $html.='</tr>';
           
       }
       $html.='<tr>';
       $html.='<td></td>';
       $html.='<td></td>';
       $html.='<td></td>';
       $html.='<td>'.$total.'</td>';
       $html.='<td>'.$total1.'</td>';
       $num=$total1-$total;
       $html.='<td>'.$num.'</td>';
       $html.='</tr>';
       $html.='</tbody>';
       $html.='</table>';
       echo $html;
   }
   
   /*public function index1(){
       
       
       $interes_mensual = 0;
       $plazo_dias = 0;
       $cant_cuotas = 0;
       $tasa_mora = 0;
       $mora_mensual = 0;
       $valor_cuota = 0;
       
       
       if (isset(  $_SESSION['nombre_usuarios']) )
       {
           
           $nombre_controladores = "SimuladorCredito";
           $id_rol= $_SESSION['id_rol'];
           $resultPer = $permisos_roles->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
           
           if (!empty($resultPer))
           {
               $resultAmortizacion=array();
               $resultDatos=array();
               
               if(isset($_POST["valor_prestamo"])){
                   
                   
                   $interes=0;
                   $total= isset($_POST['valor_prestamo'])?(double)$_POST['valor_prestamo']:2;
                   $porcentaje_capital=isset($_POST['taza_intereses'])?(double)$_POST['taza_intereses']:2;
                   $total_capital=$total-($total*$porcentaje_capital);
                   $fecha_corte=$_POST['fecha_corte'];
                   $fecha_emision='';
                   
                   
                   array_push($resultDatos,array('total'=> $total,'porcentaje_capital'=>$porcentaje_capital,'total_capital'=>$total_capital));
                   
                   
                   //valores
                   $_tasa_interes_amortizacion_cabeza = $_POST['taza_intereses'];
                   $tasa= $_tasa_interes_amortizacion_cabeza;
                   $_capital_prestado_amortizacion_cabeza = $_POST['valor_prestamo'];
                   $_plazo_meses_amortizacion_cabeza = $_POST['cantidad_plazo_meses'];
                   
                   
                   ////resultados
                   $interes_mensual = $tasa / 12;
                   $plazo_dias = $_plazo_meses_amortizacion_cabeza * 30;
                   $cant_cuotas = $_plazo_meses_amortizacion_cabeza;
                   
                   
                   $valor_cuota =  ($_capital_prestado_amortizacion_cabeza * $interes_mensual) /  (1- pow((1+$interes_mensual), -$_plazo_meses_amortizacion_cabeza ))  ;
                   
                   
                   $numero_cuotas=$_POST['cantidad_plazo_meses'];
                   
                   //$resultAmortizacion=$this->tablaAmortizacion($saldo_capital, $numero_cuotas, $fecha_corte, $total );
                   $resultAmortizacion=$this->tablaAmortizacion($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza);
                   
                   
                   
               }
               
               
               
               $this->view("SimuladorCredito",array(
                   "resultInte"=>$resultInte, "resultPlazoMeses"=>$resultPlazoMeses, 'resultDatos'=>$resultDatos,'resultAmortizacion'=>$resultAmortizacion, 'valor_cuota'=>$valor_cuota
               ));
               
               
               
           }
           else
           {
               $this->view("Error",array(
                   "resultado"=>"No tiene Permisos de Acceso a Simulador CrĂ©dito."
                   
               ));
               
               exit();
           }
           
       }
       else{
           
           $this->redirect("Usuarios","sesion_caducada");
           
       }
       
   }*/
   
   
   
   
   
   
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