<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/Retencion.html');

$footer = file_get_contents('view/reportes/template/pieret.html');

if(!empty($datos_reporte))
{	
    foreach ($datos_reporte as $clave=>$valor) {
        //echo $clave; echo "\n";
		$template = str_replace('{'.$clave.'}', $valor, $template);
	}
}

ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);

//echo $template; die();

$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($template);
$mpdf->debug = true;

/** NOTA .. el nombre si esta lleno viene del metodo de enviar correo y si esta vacio es solo para imprimir el pdf en la web **/
if( isset($_nombre_archivo) && $_nombre_archivo != "" ){
    $mpdf->Output($_nombre_archivo);
    return;
}else{
    $mpdf->Output("ComprobanteRetencion.pdf","I");
}


/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
$content = chunk_split(base64_encode($content));
$content = 'data:application/pdf;base64,'.$content;
print_r($content);*/
exit();
?>

