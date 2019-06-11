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
//session_start();
//print_r($_SESSION); die();

$num1 = 9.70;
$num2 = 533.33;

$conv1 = number_format($num1, 2, ',', ' ');
$conv2 = number_format($num2, 2, ',', ' ');

//echo  $num1 + $num2;

// '<br>';

//echo  $conv1 + $conv2;

//exit();

$numero = 0.00;

$numero = number_format($numero, 2, ',', ' ');

//echo $numero;

//exit();

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

//echo $htmlTabla;

require dirname(__FILE__).'\core\EntidadBase.php';

$model = new EntidadBase("tes_cuentas_pagar");

$columnas = "codigo_plan_cuentas, nivel_plan_cuentas, saldo_plan_cuentas";
$tablas = " public.plan_cuentas ";
$where = " 1 = 1  AND id_entidades = 1 ";
$id = " codigo_plan_cuentas";

$resultado = $model->getCondiciones($columnas, $tablas, $where, $id);

   //echo balance($resultado,5);

    function re($data) {
       $items = [];
       
       foreach ($data as $node) {
           if (is_array($node)) {
               $items []= '<li>' . re($node) . '</li>';
           } else {
               $items []= '<li>' . $node . '</li>';
           }
       }
       
       return '<ul>' . join('', $items) . '</ul>';
   }
    
   function tieneHijo($nivel, $codigo, $resultado)
   {
       $elementos_codigo=explode(".", $codigo);
       $nivel1=$nivel;
       $nivel1--;
       $verif="";
       for ($i=0; $i<$nivel1; $i++)
       {
           $verif.=$elementos_codigo[$i];
       }
       
       foreach ($resultado as $res)
       {
           $verif1="";
           $elementos1_codigo=explode(".", $res->codigo_plan_cuentas);
           if (sizeof($elementos1_codigo)>=$nivel1)
               for ($i=0; $i<$nivel1; $i++)
               {
                   $verif1.=$elementos1_codigo[$i];
               }
           if ($res->nivel_plan_cuentas==$nivel && $verif==$verif1)
           {
               return true;
           }
       }
       return false;
   }
   
   function Balance($nivel, $resultset, $limit)
   {
       $sumatoria=0;
       $suma=0;
       
       foreach($resultset as $res)
       {
           if ($res->nivel_plan_cuentas == $nivel)
           {
               echo "<h4>".$res->codigo_plan_cuentas." ".$nivel."</h4>";
               
               if($nivel<$limit) $nivel++;
               
               if ($this->tieneHijo($nivel,$res->codigo_plan_cuentas, $resultset)){
                  
                   echo "hijo ".$res->codigo_plan_cuentas;
                   $suma = $this->Balance($nivel, $resultset, $limit);
                   echo $suma;
               }
               
               if($res->nivel_plan_cuentas!=1){
                   
                   $sumatoria+=$res->saldo_plan_cuentas;
               }
               $nivel--;
           }
       }
       return $sumatoria;
   }
   
   //consulta general 
   
   function consultaMain($nivel=1, $codigo='',$entidad = 1){
       
       $modelo = new EntidadBase("tes_cuentas_pagar");
       
       $columnasMain = "codigo_plan_cuentas, nivel_plan_cuentas, saldo_plan_cuentas";
       
       $tablasMain = " public.plan_cuentas";
       
       $whereMain = "nivel_plan_cuentas = $nivel AND id_entidades = $entidad AND codigo_plan_cuentas like '$codigo%'";
       
       $idMain = "codigo_plan_cuentas";
       
       $resultado = $modelo->getCondiciones($columnasMain, $tablasMain, $whereMain, $idMain);
       
       if(!empty($resultado))
           return $resultado;
       else 
           return 0;
       
   }
   
   require dirname(__FILE__).'\core\ModeloBase.php';
   
   function plan_cuentas($data = array()){   
              
       if(empty($data)){
           echo 1;
       }else{
           
           foreach ($data as $res){
               
               $nivel = $res->nivel_plan_cuentas;
               $codigo = $res->codigo_plan_cuentas;
               
               $nivel++;
               
               
           }
           
       }
     
   }
   
   $dulce = array('a' => 'manzana', 'b' => 'banano');
   $frutas = array('dulce' => $dulce, 'acido' => 'limón');
   
   function prueba_imprimir($item, $clave)
   {
       echo "$clave contiene $item\n";
   }
   
   //array_walk_recursive($frutas, 'prueba_imprimir');
   
   function factorial($n){
       if($n==1)
           return 1;
       else
       return $n * factorial($n-1);
   }
   
   //$resultado = factorial(5);
   //echo $resultado
  
   $var = array (1, 2, 5, 8, array(2,3, array(1,2)));
   
   function recorrido($arg){
       $suma = 0;
       foreach ($arg as $key => $value) {
           if (is_array($value)){
               $array2 = $arg[$key];
               $suma = $suma + recorrido($array2);
           }else {
               $suma = $suma + $value;
           }
       }
       return $suma;
   }
   
   $total = recorrido($var);
   //echo $total;
   
   $mmodelo = new ModeloBase();
   
   $res = $mmodelo->enviaquery("select codigo_plan_cuentas from plan_cuentas where i")
   
?>