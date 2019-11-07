<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');

$template = file_get_contents('view/reportes/template/DiarioContable.html');

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


//$template = file_get_contents('template/DiarioContable.html');

//para la numeracion de pagina
$footer = file_get_contents('view/reportes/template/pieret.html');
//$footer = file_get_contents('template/pieficha.html');
//$template = str_replace('{detalle}', $detalle, $template);
//cuando ya viene el diccionario de datos
if(!empty($dicContenido))
{
	
	foreach ($dicContenido as $clave=>$valor) {
		$template = str_replace('{'.$clave.'}', $valor, $template);
	}
}





if(!empty($datos_detalle)){
    
    $tablahtml = '';
    $i=0;
    $tmparray=$datos_detalle;
    $iTmp=0;
    $variable=0;
    
    foreach ($datos_detalle as $res){
        $i+=1;
        
        if($res->id_ccomprobantes!=$variable){
            if($i!=1){
                $tablahtml.='<tr><td colspan="8" class="inferior">&nbsp;</td></tr>';
            }
            
            $variable=$res->id_ccomprobantes;
           
            $iTmp +=1;
            $tablahtml.='<tr>';
            $tablahtml.='<td>'.$iTmp.'</td>';
            $tablahtml.='<td>'.$res->fecha_ccomprobantes.'</td>';
            $tablahtml.='<td>'.$res->tipo_comprobantes.' - '.$res->numero_ccomprobantes.'</td>';
            $tablahtml.='<td>'.$res->codigo_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->nombre_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->descripcion_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero">$ '.$res->debe_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero">$ '.$res->haber_dcomprobantes.'</td>';
            $tablahtml.='</tr>';
            
        }else{
            
            $tablahtml.='<tr>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td>'.$res->codigo_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->nombre_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->descripcion_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero">$ '.$res->debe_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero">$ '.$res->haber_dcomprobantes.'</td>';
            $tablahtml.='</tr>';
           
        }
        
    }
    
    $template = str_replace('{TABLADETALLE}', $tablahtml, $template);
    
}

//echo $template; die();

ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf=new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/diarioContable.css');// la ruta a tu css
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
$content = chunk_split(base64_encode($content));
$content = 'data:application/pdf;base64,'.$content;
print_r($content);*/
exit();
?>


