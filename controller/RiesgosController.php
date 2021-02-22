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
