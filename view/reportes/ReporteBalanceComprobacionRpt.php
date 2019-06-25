<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');

if(!empty($datos_empresa)){
    
    foreach ($datos_empresa as $clave=>$valor) {
        
        $header = str_replace('{'.$clave.'}', $valor, $header);
    }
}


$template = file_get_contents('view/reportes/template/ReporteBalanceComprobacion.html');

$template2 = file_get_contents('view/reportes/template/ReporteBalanceComprobacion.html');
if(!empty($datos_tabla)){
    
    $template = str_replace('{REPORTE}', $datos_tabla, $template);
    
}

if(!empty($datos_tabla2)){
    
    $template2 = str_replace('{REPORTE}', $datos_tabla2, $template2);
    
}

$footer = file_get_contents('view/reportes/template/pieret.html');

ob_end_clean();

$mpdf= new mPDF('utf-8','A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($template);
$mpdf->AddPage();
$mpdf->WriteHTML($template2);
$mpdf->debug = true;

$mpdf->Output();

exit();
?>