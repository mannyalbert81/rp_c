<?php
 
include('view/mpdf60/mpdf.php'); 

include'../../core/Conectar.php';
$cn = new Conectar();
$conn = $cn->conexion();


$template = file_get_contents('view/reportes/template/Memorandu.html');
$footer = file_get_contents('view/reportes/template/Footer.html');


if(!empty($dicContenido))
{	
	foreach ($dicContenido as $clave=>$valor) {
		$template = str_replace('{'.$clave.'}', $valor, $template);
	}
}


ob_end_clean();

$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';

$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$directorio = $_SERVER ['DOCUMENT_ROOT'].'/webcapremci/memos_generados/';
$filename = $numero_memorando.'.pdf';
$mpdf->Output($directorio.$filename,'F');


	
$data_5 = file_get_contents($directorio.$filename);
$archivo_5 = pg_escape_bytea($data_5);

if(!empty($proceso)){
	if($proceso=="nuevo"){
	
	$sql="INSERT INTO memos_pdf (id_memos_pdf, id_memos_cab, archivo_memos_pdf, id_tipo_memos_pdf) VALUES (DEFAULT,'$id_memos_cab','$archivo_5', '1')";
	$query_new_insert = pg_query($conn,$sql);

	}
	
	if($proceso=="revisado"){
		
		$sql1="UPDATE memos_pdf SET archivo_memos_pdf ='$archivo_5' WHERE id_memos_cab='$id_memos_cab' AND id_tipo_memos_pdf='1'";
		$query_new_update = pg_query($conn,$sql1);
		
	}
	
	
}


$this->redirect("Memos","index");
exit();

?>