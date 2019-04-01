<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/MayorContable.html');

//$template = file_get_contents('template/DiarioContable.html');

//para la numeracion de pagina
$footer = file_get_contents('view/reportes/template/pieficha.html');
//$footer = file_get_contents('template/pieficha.html');
//$template = str_replace('{detalle}', $detalle, $template);
//cuando ya viene el diccionario de datos
if(!empty($dicContenido))
{
	
	foreach ($dicContenido as $clave=>$valor) {
		$template = str_replace('{'.$clave.'}', $valor, $template);
	}
}

if(!empty($datos_empresa)){
    
    foreach ($datos_empresa as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}

$tablaMayor = "<table border=1 >";
$tablaMayor .= "<tr><td>COLUMNA1</td><td>COLUMNA2</td><td>COLUMNA3</td><td>COLUMNA4</td></tr>";

if(!empty($datos_detalle)){
    
    /*variable para agrupacion*/
    $anio_detalle = 0;
    $mes_detalle = 0;
    $id_cuenta_detalle = 0;
    $comprobante_detalle = 0;
    
    foreach ($datos_detalle as $res){        
   
        if($res->id_ccomprobantes!=$id_cuenta_detalle){
            
            if($i!=1){
                $tablahtml.='<tr><td colspan="8" class="inferior">&nbsp;</td></tr>';
            }
            
            $id_cuenta_detalle = $res->id_plan_cuentas;
            
            $tablaMayor .= "<tr>";
            $tablaMayor .= "<td>Codigo:</td>";
            $tablaMayor .= "<td>$res->codigo_plan_cuentas</td>";
            $tablaMayor .= "<td>Cuenta:</td>";
            $tablaMayor .= "<td>$res->nombre_plan_cuentas</td>";
            $tablaMayor .= "</tr>";
            
        }
    }
    
}
$tablaMayor .= "</table>";

print_r($datos_detalle); die('llego');

$template = str_replace('{TABLAMAYOR}', $tablaMayor, $template);


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
            $tablahtml.='<td class="numero">'.$res->debe_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero"> '.$res->haber_dcomprobantes.'</td>';
            $tablahtml.='</tr>';
            
        }else{
            
            $tablahtml.='<tr>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td class="centrado">-</td>';
            $tablahtml.='<td>'.$res->codigo_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->nombre_plan_cuentas.'</td>';
            $tablahtml.='<td>'.$res->descripcion_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero">'.$res->debe_dcomprobantes.'</td>';
            $tablahtml.='<td class="numero"> '.$res->haber_dcomprobantes.'</td>';
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
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($template);
$mpdf->debug = true;
$mpdf->Output();
/*$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email
$content = chunk_split(base64_encode($content));
$content = 'data:application/pdf;base64,'.$content;
print_r($content);*/
exit();
?>


