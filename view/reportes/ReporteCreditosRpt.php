<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

//echo getcwd().''; //para ver ubicacion de directorio

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');
$template = file_get_contents('view/reportes/template/ReporteCreditos.html');

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

if(!empty($datos_reporte))
{
    
    foreach ($datos_reporte as $clave=>$valor) {
        echo $clave; echo "\n";
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

if(!empty($datos_rol_contador))
{
    
    foreach ($datos_rol_contador as $clave=>$valor) {
        echo $clave; echo "\n";
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}
if(!empty($datos_rol_gerente))
{
    
    foreach ($datos_rol_gerente as $clave=>$valor) {
        echo $clave; echo "\n";
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

if(!empty($datos_rol_jcredito))
{
    
    foreach ($datos_rol_jcredito as $clave=>$valor) {
        echo $clave; echo "\n";
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}
$footer = file_get_contents('view/reportes/template/pieret.html');





ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf= new mPDF('c', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/ReporteCreditos.css'); // la ruta a tu css
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