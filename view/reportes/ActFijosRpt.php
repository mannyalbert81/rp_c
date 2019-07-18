<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/ActivosFijos.html');
$header = file_get_contents('view/reportes/template/CabeceraFinal.html');



if(!empty($datos_empresa))
{
    
    foreach ($datos_empresa as $clave=>$valor) {
        $header = str_replace('{'.$clave.'}', $valor, $header);
    }
}

$footer = file_get_contents('view/reportes/template/pieret.html');
if(isset($datosActivo)){
    
    foreach ($datosActivo as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

$tablaDepreciacion="";
		

if(isset($rsDatosDepreciacion)){
    
    $tablaDepreciacion = "<table>";	

    $tablaDepreciacion .= "<caption><strong>DEPRECIACION DETALLE</strong></caption>";
    $tablaDepreciacion .= "
                        
                        <tr>
                        <td><strong>AÑO</strong></td>
                        <td><strong>ENERO</strong></td>
                        <td><strong>FEBRERO</strong></td>
                        <td><strong>MARZO</strong></td>
                        <td><strong>ABRIL</strong></td>
                        <td><strong>MAYO</strong></td>
                        <td><strong>JUNIO</strong></td>
                        <td><strong>JULIO</strong></td>
                        <td><strong>AGOSTO</strong></td>
                        <td><strong>SEPTIEMBRE</strong></td>
                        <td><strong>OCTUBRE</strong></td>
                        <td><strong>NOVIEMBRE</strong></td>
                        <td><strong>DICIEMBRE</strong></td>
                        <td align=center><strong>VALOR DEPRECIADO</strong></td>
                        </tr>";
    
   
    
    foreach ($rsDatosDepreciacion as $res) {
        
        $tablaDepreciacion .= "<tr>
                        <td align=center>$res->anio_depreciacion </td>
                        <td align=right>$res->enero_depreciacion</td>
                        <td align=right>$res->febrero_depreciacion</td>
                        <td align=right>$res->marzo_depreciacion</td>
                        <td align=right>$res->abril_depreciacion</td>
                        <td align=right>$res->mayo_depreciacion</td>
                        <td align=right>$res->junio_depreciacion</td>
                        <td align=right>$res->julio_depreciacion</td>
                        <td align=right>$res->agosto_depreciacion</td>
                        <td align=right>$res->septiembre_depreciacion</td>
                        <td align=right>$res->octubre_depreciacion</td>
                        <td align=right>$res->noviembre_depreciacion</td>
                        <td align=right>$res->diciembre_depreciacion</td>
                        <td align=right>$res->saldo_depreciacion</td>                         
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
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/activosFijos.css'); // la ruta a tu css
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
$content = chunk_split(base64_encode($content));
$content = 'data:application/pdf;base64,'.$content;
print_r($content);*/
exit();
?>


