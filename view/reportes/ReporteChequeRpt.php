<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/ReporteCheque.html');


if(!empty($datos_reporte))
{
    
    foreach ($datos_reporte as $clave=>$valor) {
        
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}








ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf= new mPDF('utf-8','A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$stylesheet = file_get_contents('view/reportes/template/reporteCheque.css');// la ruta a tu css
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($template,2);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
 $content = chunk_split(base64_encode($content));
 $content = 'data:application/pdf;base64,'.$content;
 print_r($content);*/
exit();
?>