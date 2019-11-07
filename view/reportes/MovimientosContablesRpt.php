<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');
$template = file_get_contents('view/reportes/template/MovimientosContables.html');

if(!empty($datos_empresa))
{
    
    foreach ($datos_empresa as $clave=>$valor) {
        $header = str_replace('{'.$clave.'}', $valor, $header);
    }
}

$footer = file_get_contents('view/reportes/template/pieret.html');

if(!empty($datos_detalle)){
    
    $template = str_replace('{TABLADETALLE}', $datos_detalle, $template);
    
}

ob_end_clean();

$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/MovimientosContables.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$mpdf->Output();
exit();
?>



