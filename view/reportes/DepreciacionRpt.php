<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/Depreciacion.html');

$footer = file_get_contents('view/reportes/template/pieficha.html');


if(!empty($datos_empresa)){
    
    foreach ($datos_empresa as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

if(isset($datosActivo)){
    
    foreach ($datosActivo as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

$tablaDepreciacion="";
		

if(isset($rsDatosDepreciacion)){
    
    $tablaDepreciacion = "<table>";	

    $tablaDepreciacion .= "<caption>DEPRECIACION DETALLE</caption>";
    
    $tablaDepreciacion .= "<tr>
                        <td>AÑO</td>
                        <td>ENERO</td>
                        <td>FEBRERO</td>
                        <td>MARZO</td>
                        <td>ABRIL</td>
                        <td>MAYO</td>
                        <td>JUNIO</td>
                        <td>JULIO</td>
                        <td>AGOSTO</td>
                        <td>SEPTIEMBRE</td>
                        <td>OCTUBRE</td>
                        <td>NOVIEMBRE</td>
                        <td>DICIEMBRE</td>
                        <td>VALOR DEPRECIADO</td>
                        </tr>";
    
   
    
    foreach ($rsDatosDepreciacion as $res) {
        
        $tablaDepreciacion .= "<tr>
                        <td> $res->anio_depreciacion </td>
                        <td>$res->enero_depreciacion</td>
                        <td>$res->febrero_depreciacion</td>
                        <td>$res->marzo_depreciacion</td>
                        <td>$res->abril_depreciacion</td>
                        <td>$res->mayo_depreciacion</td>
                        <td>$res->junio_depreciacion</td>
                        <td>$res->julio_depreciacion</td>
                        <td>$res->agosto_depreciacion</td>
                        <td>$res->septiembre_depreciacion</td>
                        <td>$res->octubre_depreciacion</td>
                        <td>$res->noviembre_depreciacion</td>
                        <td>$res->diciembre_depreciacion</td>
                        <td>$res->saldo_depreciacion</td>                         
                        </tr>";
        
        
    }
    
    $tablaDepreciacion .= "</table>" ;
    
    $template = str_replace('{TABLADEPRECIACION}', $tablaDepreciacion, $template);
    
}




$anio = date('Y');

$template = str_replace('{APERIODO}', $anio, $template);


//echo $template; die();

ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
$content = chunk_split(base64_encode($content));
$content = 'data:application/pdf;base64,'.$content;
print_r($content);*/
exit();
?>


