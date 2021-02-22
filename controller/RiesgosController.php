<?php
class RiesgosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    
    public function indexCalificacion(){
        
        session_start();
        
        if (isset($_SESSION['id_usuarios']) )
        {
            
            $usuarios = new UsuariosModel();
            
            $nombre_controladores = "Riesgos";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $usuarios->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                $this->view_riesgos("CalificacionCartera",array(
                    ""=>""
                    
                ));
                
            }
            else
            {
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Riesgos"
                    
                ));
                
            }
            
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
    
    
    public function index(){
    
    session_start();
	
	if (isset($_SESSION['id_usuarios']) )
	{
		
		$usuarios = new UsuariosModel();

		$nombre_controladores = "Riesgos";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $usuarios->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		   	
		    $this->view_riesgos("CalificaCartera",array(
				    ""=>""
			
				));
			
		}
		else
		{
		    $this->view("Error",array(
					"resultado"=>"No tiene Permisos de Acceso a Riesgos"
		
			));
		
		}
		
	
    	}
    	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
		
	}
	
	
	
	
	
	
	
	
	
	public function procesar_calificacion(){
	
	    session_start();
	    $id_usuarios = $_SESSION['id_usuarios'];
	    $usuario_usuarios = $_SESSION['usuario_usuarios'];
	    $riesgos = new RiesgosModel();
	    
	    $anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
	    $mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
	    
	    $mesperiodofiscal = $mesDiario;
	    if (strlen($mesDiario) == 1 )
	    {
	        
	        $mesperiodofiscal = '0' .$mesDiario;
	    }
	    else {
	        $mesperiodofiscal = $mesDiario;
	    }
	    //$mes_letras = date('n')
	   
	    
	    //validar los campos recibidos para generar diario
	    $arrayTabla = array();
	    $cantidad = 0;
	    $columnas = 'crc.id_rpt_creditos,   crc.id_creditos ,ctc.tipo_operacion , 
                        crc.estadosbs_rpt_creditos, 
                        crc.dias_vencidos_rpt_creditos , 
                        crc.estado_rpt_creditos ';
	    $tablas = "core_rpt_creditos crc, core_tipo_creditos ctc";
	    $where = "crc.mes_rpt_creditos  = 10 and crc.anio_rpt_creditos = 2020
                    and crc.id_tipo_creditos = ctc.id_tipo_creditos 
                    and crc.estado_rpt_creditos = 'Activo' ";
	    
	    $id = "crc.id_creditos";
	    $rsRiesgos = $riesgos->getCondiciones($columnas, $tablas, $where, $id);
	    $respuesta = array();
	    
	    $cantidadResult=count($rsRiesgos);
	    $_id_rpt_creditos;
	    $_id_creditos;
	    $_tipo_operacion;
	    $_estadosbs_rpt_creditos;
	    $_dias_vencidos_rpt_creditos;
	    $_estado_rpt_creditos;
	    
	    $_calificacion_credito = "";
	    
	    
	    if ($cantidadResult  > 0)
	    {
	        $i=0;
	        foreach ($rsRiesgos as $res)
	        {
	            
	            $i++;
	          
	            $_id_rpt_creditos                  = $res->id_rpt_creditos;
	            $_id_creditos                      = $res->id_creditos;
	            $_tipo_operacion                   = $res->tipo_operacion;
	            $_estadosbs_rpt_creditos           =  $res->estadosbs_rpt_creditos       ;
	            $_dias_vencidos_rpt_creditos       = $res->dias_vencidos_rpt_creditos;
	            $_estado_rpt_creditos              = $res->estado_rpt_creditos    ;
	            
	             
	            if ($_estadosbs_rpt_creditos == 'VENCIDO')
	            {
	                ///calificar
	                
	                $columnasCAL = 'calificacion_creditos_calificaciones ';
	                $tablasCAL = " core_creditos_calificaciones ";
	                $whereCAL = "tipo_operacion = '$_tipo_operacion' and $_dias_vencidos_rpt_creditos    >= dias_mora_min_creditos_calificaciones and $_dias_vencidos_rpt_creditos <= dias_mora_max_creditos_calificaciones ";
	                
	     
	                $idCAL = "calificacion_creditos_calificaciones";
	                $rsRiesgosCAL = $riesgos->getCondiciones($columnasCAL, $tablasCAL, $whereCAL, $idCAL);
	                $cantidadResultCAL=count($rsRiesgosCAL);
	                
	                if ($cantidadResultCAL  > 0)
	                {
	                    
	                    foreach ($rsRiesgosCAL as $res)
	                    {
	                        $_calificacion_credito         = $res->calificacion_creditos_calificaciones;
	                 
	                        
	                    }
	                    
	                    
	                }
	                
	                   
	            }
	            else 
	            {
	                ///ok
	                $_dias_vencidos_rpt_creditos = 0;
	                $_calificacion_credito = 'A1';
	                
	                
	            }
	            
	           
	            
	           ///ACTUALIZAMOS
	           
	            $colval = "calificación_riesgos_rpt_creditos ='$_calificacion_credito' ";
	            $tabla = "core_rpt_creditos";
	            $where = "id_rpt_creditos = '$_id_rpt_creditos'";
	            
	            $resultado=$riesgos->UpdateBy($colval, $tabla, $where);
	            
	            
	            
	            
	        }
	        
	        
	    }
	        
	    
	    
	    $respuesta['tabladatos'] =  $this->graficaRespuesta($resultado);
	    //$respuesta['tabladatos'] = "PRUEBA";
	    echo json_encode($respuesta);
	    //	echo json_encode($where);
	    die();
	    
	
	
	}
	
	
<<<<<<< HEAD
	
	function graficaRespuesta( $paramArrayDatos, $mes, $year){
	    
	  
	    $columnas = 'crc.id_rpt_creditos,   crc.id_creditos ,ctc.tipo_operacion ,
                        crc.estadosbs_rpt_creditos,
                        crc.dias_vencidos_rpt_creditos ,
                        crc.estado_rpt_creditos ';
	    $tablas = "core_rpt_creditos crc, core_tipo_creditos ctc";
	    $where = "crc.mes_rpt_creditos  = $mes and crc.anio_rpt_creditos = $year
                    and crc.id_tipo_creditos = ctc.id_tipo_creditos
                    and crc.estado_rpt_creditos = 'Activo' ";
	    
	    $id = "crc.id_creditos";
	    $rsRiesgos = $riesgos->getCondiciones($columnas, $tablas, $where, $id);
	    
	    
	    
	    
	    
	    $_saldo_mes_1 = 0;
	    $_saldo_mes_3 = 0;
	    $_saldo_mes_3 = 0;
	    $_saldo_mes_4 = 0;
	    $_saldo_mes_5 = 0;
	    $_saldo_mes_6 = 0;
	    $_saldo_mes_7 = 0;
	    $_saldo_mes_8 = 0;
	   
	    
	    
	    $cantidad = sizeof($paramArrayDatos);
	    $html = "";
	    if( $cantidad > 0 ){
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: center;  font-size: 12px;">CALIFICACION</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito 2 x1</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito Emergente</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PH-Crédito Hipotecario</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito Ordinario</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Acuerdo de Pago</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Refinanciamiento</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Acta Transaccional</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PH-Acta Transaccional Hipotecario.</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">TOTAL</th>';
	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'A1'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  ' .'1 203 553'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$ 62 212'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'49 2415'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.'21 970 481'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  '.'1 813'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'31 2507'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$  '.'1 561 265'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'99 658'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$  '.'25 700 922'.'</td>';
	        $html.='</tr>';
	        
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'A2'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  ' .''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$ '.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$  '.''.'</td>';
	        $html.='</tr>';
	        
	        
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'A3'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  ' .''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$ '.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$ 28 497'.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$ 28 497'.''.'</td>';
	        $html.='</tr>';
	        
            
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'B1'.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  29 906' .''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$ '.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  10 950'.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">' .'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$ 28 497'.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.''.'</td>';
	        $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.'$ 28 497'.''.'</td>';
	        $html.='</tr>';
=======

	function graficaRespuesta( $paramArrayDatos, $mes){
	    
	    $_base_imponible = 0;
	    $_valor_retenido = 0;
	    
	    $_base_imponible_renta = 0;
	    $_valor_retenido_renta = 0;
	    
	    $_base_imponible_iva = 0;
	    $_valor_retenido_iva = 0;
	    
	    
	    $_saldo_mes_1 = 0;
	    $_saldo_mes_3 = 0;
	    $_saldo_mes_3 = 0;
	    $_saldo_mes_4 = 0;
	    $_saldo_mes_5 = 0;
	    $_saldo_mes_6 = 0;
	    $_saldo_mes_7 = 0;
	    $_saldo_mes_8 = 0;
	   
	    
	    
	    $cantidad = sizeof($paramArrayDatos);
	    $html = "";
	    if( $cantidad > 0 ){
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        $html.='<th style="text-align: center;  font-size: 12px;">CALIFICACION</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito 2 x1</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito Emergente</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PH-Crédito Hipotecario</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Crédito Ordinario</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Acuerdo de Pago</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Refinanciamiento</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PQ-Acta Transaccional</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">PH-Acta Transaccional Hipotecario.</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">TOTAL</th>';
	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        
	        $i=0;
	        
	        
	        foreach ($paramArrayDatos as $res){
	            
	            $i++;
	            $html.='<tr>';
	            $html.='<td style="font-size: 11px;">'.$i.'</td>';
	            if ($res->id_tipo_creditos == 1 )
	            {
	                switch ($mes) {
	                    case 1:
	                        if($res->calificación_riesgos_rpt_creditos == "A1")
	                        {
	                            $_saldo_mes_1_A1 =  $_saldo_mes_1_A1  + $res->sal_ene_rpt_creditos ;
	                            
	                        }
	                        
	                        
	                        break;
	                    case 2:
	                        $_saldo_mes_1 =  $_saldo_mes_1  +$res->sal_feb_rpt_creditos ;
	                        break;
	                    case 3:
	                        $_saldo_mes_1 =  $_saldo_mes_1  +$res->sal_mar_rpt_creditos ;
	                        break;
	                    case 4:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_abr_rpt_creditos ;
	                        break;
	                    case 5:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_may_rpt_creditos ;
	                        break;
	                    case 6:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_jun_rpt_creditos ;
	                        break;
	                    case 7:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_jul_rpt_creditos ;
	                        break;
	                    case 8:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_ago_rpt_creditos ;
	                        break;
	                    case 9:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_sep_rpt_creditos ;
	                        break;
	                    case 10:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_otc_rpt_creditos ;
	                        break;
	                    case 11:
	                        $_saldo_mes_1 = $_saldo_mes_1  +  $res->sal_nov_rpt_creditos ;
	                        break;
	                    case 12:
	                        $_saldo_mes_1 =  $_saldo_mes_1  + $res->sal_dic_rpt_creditos ;
	                        break;
	                }
	                
	                
	                
	                $html.='<td style="font-size: 11px;  text-align: center;">'.'RENTA'.'</td>';
	                $_base_imponible_renta = $_base_imponible_renta +  $res->impuestos_baseimponible;
	                $_valor_retenido_renta =  $_valor_retenido_renta +  $res->impuestos_valorretenido;
	                
	            }
	            else
	            {
	                $html.='<td style="font-size: 11px;  text-align: center;">'.'IVA'.'</td>';
	                $_base_imponible_iva = $_base_imponible_iva +  $res->impuestos_baseimponible;
	                $_valor_retenido_iva =  $_valor_retenido_iva +  $res->impuestos_valorretenido;
	                
	            }
	            
	            $html.='<td style="font-size: 12px; text-align: center;">'.$res->impuesto_codigoretencion.'</td>';
	            $html.='<td style="font-size: 12px; text-align: right;">' .'$  ' .$res->impuestos_baseimponible.'</td>';
	            $html.='<td style="font-size: 12px; text-align: right;">'.$res->impuesto_porcentaje.'</td>';
	            $html.='<td style="font-size: 12px; text-align: right;">'.'$  '.$res->impuestos_valorretenido.'</td>';
	            $_base_imponible = $_base_imponible +  $res->impuestos_baseimponible;
	            $_valor_retenido =  $_valor_retenido +  $res->impuestos_valorretenido;
	            
	            $html.='</tr>';
	            
	            
	        }

>>>>>>> branch 'master' of https://github.com/mannyalbert81/rp_c.git
	        
	        
	        $html.='</tbody>';
	        $html.='</table>';
	        //$html.=$paramArrayDatos;
	        
	        $html.='<div class="table-pagination pull-right">';
	        $html.='</div>';
	        
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "POR CONCEPTO DE RENTA";
	        $html.= "<tr>";
	        $html.='<th style="text-align: center;  font-size: 12px;">BASE IMPONIBLE</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">VALOR RETENIDO</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='<tr>';
	        
	        
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_base_imponible_renta.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_valor_retenido_renta.'</td>';
	        $html.='</tr>';
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "POR CONCEPTO DE IVA";
	        $html.= "<tr>";
	        $html.='<th style="text-align: center;  font-size: 12px;">BASE IMPONIBLE</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">VALOR RETENIDO</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_base_imponible_iva.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_valor_retenido_iva.'</td>';
	        $html.='</tr>';
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "TOTALES";
	        $html.= "<tr>";
	        $html.='<th style="text-align: center;  font-size: 12px;">BASE IMPONIBLE</th>';
	        $html.='<th style="text-align: center;  font-size: 12px;">VALOR RETENIDO</th>';
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='<tbody>';
	        $html.='<tr>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_base_imponible.'</td>';
	        $html.='<td style="font-size: 12px; text-align: center;">'.'$  '.$_valor_retenido.'</td>';
	        $html.='</tr>';
	        $html.='</tbody>';
	        $html.='</table>';
	        
	        
	    }else{
	        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	        $html.= "<thead>";
	        $html.= "<tr>";
	        
	        $html.='</tr>';
	        $html.='</thead>';
	        $html.='</table>';
	    }
	    
	    return $html;
	    
	    
	}
	
	
	




















	
	
}


?>
