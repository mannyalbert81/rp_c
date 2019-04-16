<?php

include dirname(__FILE__).'\..\..\view\mpdf\mpdf.php';
 
//echo getcwd().''; //para ver ubicacion de directorio

$template = file_get_contents('view/reportes/template/Depreciacion.html');

$footer = file_get_contents('view/reportes/template/pieficha.html');


if(!empty($datos_empresa)){
    
    foreach ($datos_empresa as $clave=>$valor) {
        $template = str_replace('{'.$clave.'}', $valor, $template);
    }
}



$tablaDepreciacion="";
		

if(isset($datosActivo)){
    
    $tablaDepreciacion = "<tbody>";	
    $_id_tipo_activo = 0;
    $_valor_por_depreciar = 0.00;
    
    $_suma_valor_depreciacion = 0.00;
    $_suma_valor_acumulado = 0.00;
    $_suma_valor_activo = 0.00;
    $_suma_valor_anual = 0.00;
    $_suma_valor_x_depreciar = 0.00;
    
    
    foreach ($datosActivo as $res) {
        
        $_valor_por_depreciar = number_format((double)($res->valor_activos_fijos) - (double)($res->actual), 2, ",", ".");
        
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
                        <td>$res->detalle_activos_fijos</td>
                        <td>$res->nombre_departamento</td>
                        <td>$res->nombres_empleados</td>
                        <td class=\"numero\" >$res->valor_activos_fijos</td>
                        <td>$res->meses_tipo_activos_fijos</td>
                        <td>$res->diferencia_mes</td>
                        <td class=\"numero\" >$res->valor_depreciacion</td>
                        <td class=\"numero\" >$res->acumulada</td>
                        <td class=\"numero\" >$res->enero</td>
                        <td class=\"numero\" >$res->febrero</td>
                        <td class=\"numero\" >$res->marzo</td>
                        <td class=\"numero\" >$res->abril</td>
                        <td class=\"numero\" >$res->mayo</td>
                        <td class=\"numero\" >$res->junio</td>
                        <td class=\"numero\" >$res->julio</td>
                        <td class=\"numero\" >$res->agosto</td>
                        <td class=\"numero\" >$res->septiembre</td>
                        <td class=\"numero\" >$res->octubre</td>
                        <td class=\"numero\" >$res->noviembre</td>
                        <td class=\"numero\" >$res->diciembre</td>
                        <td class=\"numero\" >$res->actual</td>
                        <td class=\"numero\" >$_valor_por_depreciar</td>                 
                        </tr>";
        
        $_suma_valor_depreciacion += $res->valor_depreciacion;
        $_suma_valor_acumulado += $res->acumulada;
        $_suma_valor_activo += $res->valor_activos_fijos;
        $_suma_valor_anual += $res->actual;
        $_suma_valor_x_depreciar += number_format($_valor_por_depreciar, 2, ",", ".");
        
        if($_id_tipo_activo != $res->id_tipo_activos_fijos){

            
            $tablaDepreciacion .= "<tr >
                        <td colspan=\"6\"></td>                        
                        <td class=\"numero ul\" >$_suma_valor_activo</td>
                        <td colspan=\"2\" ></td>
                        <td class=\"numero ul\" >$_suma_valor_depreciacion</td>
                        <td class=\"numero ul\" >$_suma_valor_acumulado</td>
                        <td class=\"numero ul\" >$res->enero</td>
                        <td colspan=\"11\"></td>
                        <td class=\"numero ul\" >$_suma_valor_anual</td>
                        <td class=\"numero ul\" >$_suma_valor_x_depreciar</td>
                        </tr>";
            
        }
        
       /*para variables cambiantes*/
        $_id_tipo_activo = $res->id_tipo_activos_fijos;
        
        
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


