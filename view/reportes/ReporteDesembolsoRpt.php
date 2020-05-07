<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

//echo getcwd().''; //para ver ubicacion de directorio

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');
$template = file_get_contents('view/reportes/template/ReporteDesembolso.html');

if(!empty($datos_cabecera))
{
    
    foreach ($datos_cabecera as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

if(!empty($datos_empresa))
{
    
    foreach ($datos_empresa as $clave=>$valor) {
        $header = str_replace('{'.$clave.'}', $valor, $header);
    }
}



if(!empty($datos_afiliado))
{
    
    foreach ($datos_afiliado as $clave=>$valor) {
        
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
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/reporteDesembolso.css');// la ruta a tu css
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