<?php
/*$clave_fecha_hoy = date("Y-m-d");
echo $clave_fecha_hoy;
$clave_fecha_siguiente_mes = date("Y-m-d",strtotime($clave_fecha_hoy."+ 1 month"));
echo $clave_fecha_siguiente_mes;*/

/*for($i=361;$i<=10000;$i++){
    echo'-';echo $i;echo',';
}*/
?>

<?php

//echo round(0.000,2);
session_start();
print_r($_SESSION); die();

$num1 = 9.70;
$num2 = 533.33;

$conv1 = number_format($num1, 2, ',', ' ');
$conv2 = number_format($num2, 2, ',', ' ');

echo  $num1 + $num2;

echo '<br>';

echo  $conv1 + $conv2;

exit();

$numero = 0.00;

$numero = number_format($numero, 2, ',', ' ');

echo $numero;

exit();

$monto = 4000.00;
$tasa = 3.85; //en porcentaje %
$plazo = 24;

$htmlTabla = '<table border="1">';

$htmlTabla.='<tr>';
$htmlTabla.='<td>Mes</td>';
$htmlTabla.='<td>Fecha</td>';
$htmlTabla.='<td>Saldo Inicial</td>';
$htmlTabla.='<td>Interes|</td>';
$htmlTabla.='<td>Amortizacion</td>';
$htmlTabla.='<td>Pago</td>';
$htmlTabla.='<td>Saldo Actual</td>';
$htmlTabla.='</tr>';

/*para operaciones*/
$cuota = 0;
$saldoIni = 0.0;
$saldoFinal = 0.00;
$amortizacion = $monto/$plazo;
$saldoIni = $monto;
$interes = 0.0;
$pago = 0.00;
$saldoFinal = $saldoIni;

for($i = 0; $i < $plazo; $i++){
    
    $cuota = $i+1;
    $interes = $saldoFinal*($tasa/100);
    $saldoFinal = $saldoIni-$amortizacion;    
    $pago = $interes + $amortizacion;
        
    $htmlTabla.='<tr>';
    $htmlTabla.='<td>'.$cuota.'</td>';
    $htmlTabla.='<td>Mes-'.$cuota.'</td>';
    $htmlTabla.='<td>'.round($saldoIni,2).'</td>';
    $htmlTabla.='<td>'.round($interes,2).'</td>';
    $htmlTabla.='<td>'.round($amortizacion,2).'</td>';
    $htmlTabla.='<td>'.round($pago,2).'</td>';
    $htmlTabla.='<td>'.round($saldoFinal,2).'</td>';
    $htmlTabla.='</tr>';
    
    $saldoIni=$saldoFinal;
}

echo $htmlTabla;

?>