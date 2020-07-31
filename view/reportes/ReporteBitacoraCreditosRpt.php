<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

$template = file_get_contents('view/reportes/template/ReporteBitacoraCreditos.html');


if(!empty($datos_reporte))
{
    
    foreach ($datos_reporte as $clave=>$valor) {
        
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

$footer = file_get_contents('view/reportes/template/pieret.html');

ob_end_clean();
$mpdf= new mPDF('utf-8','A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/ReporteBitacoraCreditos.css');// la ruta a tu css
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($template,2);
$mpdf->debug = true;
$mpdf->Output("Bitacora.pdf","I");
exit();
?>