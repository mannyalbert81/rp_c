<?php
 
$directorio = $_SERVER ['DOCUMENT_ROOT'];
$dom=$directorio.'/rp_c/view/dompdf/dompdf_config.inc.php';

require_once( $dom);

$html =$resultSet;
 

$dompdf = new DOMPDF();
$dompdf->load_html(utf8_decode($html));
$dompdf->set_base_path("/");
$dompdf->set_paper("A4");
$pdf = $dompdf->render();
$canvas = $dompdf->get_canvas();
$font = Font_Metrics::get_font("helvetica", "bold");
$canvas->page_text(260, 812, "Copyright © 2019", $font, 6, array(0,0,0)); //footer
header("Content-type: application/pdf");
echo $dompdf->output();

?>