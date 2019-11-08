<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

//echo getcwd().''; //para ver ubicacion de directorio


$template = file_get_contents('view/reportes/template/Firmas.html');


if(!empty($reporteimg))
{
    
    foreach ($reporteimg as $clave=>$valor) {
        echo $clave; echo "\n";
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

$footer = file_get_contents('view/reportes/template/pieret.html');

ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf= new mPDF('utf-8','A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->WriteHTML($template,2);
$mpdf->SetHTMLFooter($footer);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
 $content = chunk_split(base64_encode($content));
 $content = 'data:application/pdf;base64,'.$content;
 print_r($content);*/
exit();
?>