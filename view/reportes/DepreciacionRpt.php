<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$header = file_get_contents('view/reportes/template/CabeceraFinal.html');
$template = file_get_contents('view/reportes/template/Depreciacion.html');



if(!empty($datos_empresa))
{
    
    foreach ($datos_empresa as $clave=>$valor) {
        $header = str_replace('{'.$clave.'}', $valor, $header);
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}


$footer = file_get_contents('view/reportes/template/pieret.html');

$tablaDepreciacion="";
		

if(isset($datosActivo)){
    
    $tablaDepreciacion = "<tbody>";	
    $_id_tipo_activo = 0;
    $_valor_por_depreciar = 0.00;
    $_valor_por_depreciar_imp = ''; 
    
    $_suma_valor_depreciacion = 0.00;
    $_suma_valor_acumulado = 0.00;
    $_suma_valor_activo = 0.00;
    $_suma_valor_anual = 0.00;
    $_suma_valor_x_depreciar = 0.00;
    
    $i = 0;
    
    
    foreach ($datosActivo as $res) {
        
        $_valor_por_depreciar = (double)($res->valor_activos_fijos) - (double)($res->actual);
        
        $valor_libros=($res->valor_activos_fijos - $res->actual);
        
        if($_id_tipo_activo != $res->id_tipo_activos_fijos){
            
            $titulo = $res->nombre_tipo_activos_fijos."( ".$res->meses_tipo_activos_fijos." MESES )";
            
            $tablaDepreciacion .= "<tr class=\"tipocuenta\" >
                        <td colspan=\"25\"  >$titulo</td>                        
                        </tr>";
            
            $_suma_valor_depreciacion = (double)0;
            $_suma_valor_acumulado = 0.00;
            $_suma_valor_activo = 0.00;
            $_suma_valor_anual = 0.00;
            $_suma_valor_x_depreciar =  (double)0;
            
        }
        
        $tablaDepreciacion .= "<tr>
                        <td> $res->codigo_activos_fijos </td>
                        <td>$res->fecha_activos_fijos</td>
                        <td>1</td>
                        <td>$res->nombre_activos_fijos</td>
                        <td class=\"numero\" >$ ".number_format($res->valor_activos_fijos,2,',','.')."</td>
                        <td class=\"numero\" >$ ".number_format($valor_libros,2,',','.')."</td>
                        <td>$res->meses_tipo_activos_fijos</td>
                        <td>&nbsp; &nbsp; &nbsp; $res->diferencia_mes</td>
                        <td class=\"numero\" >".number_format($res->valor_depreciacion,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->acumulada,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->enero,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->febrero,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->marzo,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->abril,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->mayo,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->junio,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->julio,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->agosto,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->septiembre,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->octubre,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->noviembre,2,',','.')."</td>
                        <td class=\"numero\" >".number_format($res->diciembre,2,',','.')."</td>
                        <td class=\"numero\" >$ ".number_format($res->actual,2,',','.')."</td>
                        <td class=\"numero\" >$ ".number_format($_valor_por_depreciar,2,',','.')."</td>                 
                        </tr>";
        
        $_suma_valor_depreciacion += $res->valor_depreciacion;
        $_suma_valor_acumulado += $res->acumulada;
        $_suma_valor_activo += $res->valor_activos_fijos;
        $_suma_valor_anual += $res->actual;
        $_suma_valor_x_depreciar += $_valor_por_depreciar;
        
       
        
       /*para variables cambiantes*/
        $_id_tipo_activo = $res->id_tipo_activos_fijos;
        $i += 1;
        
        if( @$_id_tipo_activo != $datosActivo[$i]->id_tipo_activos_fijos){
            
            
            $tablaDepreciacion .= "<tr >
                        <td colspan=\"4\"></td>
                        <td class=\"numero ul\" >".number_format($_suma_valor_activo,2,',','.')."</td>
                        <td colspan=\"2\" ></td>
                        <td class=\"numero ul\" >".number_format($_suma_valor_depreciacion,2,',','.')."</td>
                        <td class=\"numero ul\" >".number_format($_suma_valor_acumulado,2,',','.')."</td>
                        <td class=\"numero ul\" >".number_format($res->enero,2,',','.')."</td>
                        <td colspan=\"11\"></td>
                        <td class=\"numero ul\" >".number_format($_suma_valor_anual,2,',','.')."</td>
                        <td class=\"numero ul\" >".number_format($_suma_valor_x_depreciar,2,',','.')."</td>
                        </tr>";
            
        }
        
        
    }
    
    $tablaDepreciacion .= "</tbody>" ;
    
    $template = str_replace('{TABLADETALLE}', $tablaDepreciacion, $template);
    
}


//echo $template; die();

ob_end_clean();
//creacion del pdf
//$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
$mpdf=new mPDF('c', 'A4-L');
$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion = true;
$mpdf->charset_in = 'UTF-8';
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLHeader(utf8_encode($header));
$mpdf->SetHTMLFooter($footer);
$stylesheet = file_get_contents('view/reportes/template/activosFijos.css'); // la ruta a tu css
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


