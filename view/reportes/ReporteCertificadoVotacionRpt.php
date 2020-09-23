<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';

$template = file_get_contents('view/reportes/template/ReporteCertificadoVotacion.html');

$footer = file_get_contents('view/reportes/template/pieret.html');



if(!empty($datos_reporte))

{

    foreach ($datos_reporte as $clave=>$valor) {

         $template = str_replace('{'.$clave.'}', $valor, $template);

    }

}



if(!empty($datos))

{

    

    foreach ($datos as $clave=>$valor) {

        echo $clave; echo "\n";

        $template = str_replace('{'.$clave.'}', $valor, $template);

    }

}



ob_end_clean();

$mpdf=new mPDF();

$mpdf->SetDisplayMode('fullpage');

$mpdf->allow_charset_conversion = true;

$mpdf->charset_in = 'UTF-8';

$mpdf->setAutoTopMargin = 'stretch';

$mpdf->setAutoBottomMargin = 'stretch';

$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($template);

$mpdf->debug = true;

$mpdf->Output("Certificado Votación.pdf","I");



exit();

?>



