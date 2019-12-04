<?php

class EstructurasBiessController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	
		session_start();
        
	
	
				
				$this->view_Informativo("G41",array());
			
		
	}
	
	public function CargaInformacion()
	{
	    session_start();
	    
	    $G41= new G41Model();
	    $id_usuarios=$_SESSION['id_usuarios'];
	    $mes_reporte=$_POST['mes_reporte'];
	   
	    $anio_reporte=$_POST['anio_reporte'];
	    $mes_reporte1=$mes_reporte+1;
	    
	    $_tipo_identificacion_g41_biess = ""; 
	    $_identificacion_participe_g41_biess= "";
	    $_correo_participe_g41_biess = "";
	    $_nombre_participe_g41_biess = "";
	    $_sexo_participe_g41_biess = "";
	    $_estado_civil_g41_biess = "";
	    $_fecha_ingreso_participe_g41_biess = ""; 
	    $_tipo_registro_aporte_g41_biess = "";
	    $_base_calculo_aportacion_g41_biess = "";
	    $_relacion_laboral_g41_biess = "";
	    $_estado_registro_g41_biess = "";
	    $_tipo_aportacion_g41_biess = "";
	    $_anio = ""; 
	    $_mes = "";
	    
	    $i = 0;
	    
	    $columnas = "id_g41_biess, numero_registros_g41_biess, tipo_identificacion_g41_biess, 
				       identificacion_participe_g41_biess, correo_participe_g41_biess, 
				       nombre_participe_g41_biess, sexo_participe_g41_biess, estado_civil_g41_biess, 
				       fecha_ingreso_participe_g41_biess, tipo_registro_aporte_g41_biess, 
				       base_calculo_aportacion_g41_biess, relacion_laboral_g41_biess, 
				       estado_registro_g41_biess, tipo_aportacion_g41_biess";
	    $tablas = "public.core_g41_biess";
	    $where = " anio = '$anio_reporte' AND mes = '$mes_reporte1' ";
	    $id = " id_g41_biess" ;
	    
	    $html= "";
	    
	    $resultSet=$G41->getCondiciones($columnas, $tablas, $where, $id);
	    if ($resultSet !="")
	    {

	    	$html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	    	$html.= "<thead>";
	    	$html.= "<tr>";
	    	$html.='<th style="text-align: center;  font-size: 12px;">Numero Registro</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Tipo Identificación Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Identificación Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Correo Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Nombre del partícipe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Sexo Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Estado Civil Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Fecha Ingreso Participe</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Tipo de registro de aporte</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Base Calculo Aportación</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Relación Laboral</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Estado Registro</th>';
	    	$html.='<th style="text-align: center;  font-size: 12px;">Tipo Prestación</th>';
	    	
	    	//$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    	$html.='</tr>';
	    	$html.='</thead>';
	    	$html.='<tbody>';
		
	    	foreach($resultSet as $res)
	    	{
	    		$i ++;
	    		

	    		$_tipo_identificacion_g41_biess = $res->tipo_identificacion_g41_biess;
	    		$_identificacion_participe_g41_biess = $res->identificacion_participe_g41_biess;
	    		$_correo_participe_g41_biess = $res->correo_participe_g41_biess;
	    		$_nombre_participe_g41_biess = $res->nombre_participe_g41_biess;
	    		$_sexo_participe_g41_biess = $res->sexo_participe_g41_biess;
	    		$_estado_civil_g41_biess = $res->estado_civil_g41_biess;
	    		$_fecha_ingreso_participe_g41_biess = $res->fecha_ingreso_participe_g41_biess;
	    		$_tipo_registro_aporte_g41_biess = $res->tipo_registro_aporte_g41_biess;
	    		$_base_calculo_aportacion_g41_biess = $res->base_calculo_aportacion_g41_biess;
	    		$_relacion_laboral_g41_biess = $res->relacion_laboral_g41_biess;
	    		$_estado_registro_g41_biess = $res->estado_registro_g41_biess;
	    		$_tipo_aportacion_g41_biess = $res->tipo_aportacion_g41_biess;
	    		
	    		$html.='<tr>';
	    		$html.='<td style="font-size: 11px;">'.$i.'</td>';
	    		
	    		$html.='<td style="font-size: 11px;">'.$_tipo_identificacion_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_identificacion_participe_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_correo_participe_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_nombre_participe_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_sexo_participe_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_estado_civil_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_fecha_ingreso_participe_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_tipo_registro_aporte_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_base_calculo_aportacion_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_relacion_laboral_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_estado_registro_g41_biess.'</td>';
	    		$html.='<td style="font-size: 11px;">'.$_tipo_aportacion_g41_biess.'</td>';

	 
	    		
	    		$html.='</tr>';
	    		 
	    		
	    	}
	    	
	    	$html.='</tbody>';
	    	$html.='</table>';
	    
	    		
	    	$html.='<div class="table-pagination pull-right">';
	    	$html.='</div>';
	    	
	    	
	    	
	    	
	    }
	    
	    $respuesta = array();
	    $respuesta['tabladatos'] =$html;
	    echo json_encode($respuesta);
	    
	    die();
	    
	    

	    
	}
	public function generaG41(){
	
		
		
		$G41= new G41Model();
		
		$mes_reporte=$_POST['mes_reporte'];
		
		$anio_reporte=$_POST['anio_reporte'];
		$mes_reporte1=$mes_reporte+1;
		 
		$_tipo_identificacion_g41_biess = "";
		$_identificacion_participe_g41_biess= "";
		$_correo_participe_g41_biess = "";
		$_nombre_participe_g41_biess = "";
		$_sexo_participe_g41_biess = "";
		$_estado_civil_g41_biess = "";
		$_fecha_ingreso_participe_g41_biess = "";
		$_tipo_registro_aporte_g41_biess = "";
		$_base_calculo_aportacion_g41_biess = "";
		$_relacion_laboral_g41_biess = "";
		$_estado_registro_g41_biess = "";
		$_tipo_aportacion_g41_biess = "";
		$_anio = "";
		$_mes = "";
		 
		$i = 0;
		 
		$columnas = "id_g41_biess, numero_registros_g41_biess, tipo_identificacion_g41_biess,
				       identificacion_participe_g41_biess, correo_participe_g41_biess,
				       nombre_participe_g41_biess, sexo_participe_g41_biess, estado_civil_g41_biess,
				       fecha_ingreso_participe_g41_biess, tipo_registro_aporte_g41_biess,
				       base_calculo_aportacion_g41_biess, relacion_laboral_g41_biess,
				       estado_registro_g41_biess, tipo_aportacion_g41_biess";
		$tablas = "public.core_g41_biess";
		$where = " anio = '$anio_reporte' AND mes = '$mes_reporte1' ";
		$id = " id_g41_biess" ;
		 
		
			//validar los campos recibidos para generar diario
		
		$texto = "";
		
		$resultSet=$G41->getCondiciones($columnas, $tablas, $where, $id);
		
		if(!empty($resultSet)){
		
		
			
			$fecha =  "01/".$mes_reporte1."/".$anio_reporte;
			
		//	$fecha_corte = $G41->ultimo_dia_mes_fecha($fecha);
			$cantidad_lineas = count($resultSet) + 1;
			$anio_mes = $anio_reporte.'-'.$mes_reporte1;
			$aux = date('Y-m-d', strtotime("{$anio_mes} + 1 month"));
			$last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
			$newDate_fechacorte = date("d/m/Y", strtotime($last_day));
			/*
			$respuesta = array();
			 $respuesta['tabladatos'] =$newDate_fechacorte;
			 echo json_encode($respuesta);
			
			 die();
			 */
			
			$texto .='<?xml version="1.0" encoding="UTF-8"?>';
				$texto .= '<REGISTROS>';
					$texto .= '<DatosEstructura>';
						$texto .= '<CodigoEstructura>G41</CodigoEstructura>';
						$texto .= '<CodigoEntidad>17</CodigoEntidad>';
						$texto .= '<FechaCorte>'.$newDate_fechacorte.'</FechaCorte>';
						$texto .= '<TotalRegistros>'.$cantidad_lineas.'</TotalRegistros>';
					$texto .= '</DatosEstructura>';
					$texto .= '<Detalle>';
			foreach($resultSet as $res)
			{
				

			
				
				$i ++;
				$_tipo_identificacion_g41_biess = $res->tipo_identificacion_g41_biess;
				$_identificacion_participe_g41_biess = $res->identificacion_participe_g41_biess;
				$_correo_participe_g41_biess = $res->correo_participe_g41_biess;
				$_nombre_participe_g41_biess = $res->nombre_participe_g41_biess;
				$_sexo_participe_g41_biess = $res->sexo_participe_g41_biess;
				$_estado_civil_g41_biess = $res->estado_civil_g41_biess;
				$_fecha_ingreso_participe_g41_biess = $res->fecha_ingreso_participe_g41_biess;
				$_tipo_registro_aporte_g41_biess = $res->tipo_registro_aporte_g41_biess;
				$_base_calculo_aportacion_g41_biess = $res->base_calculo_aportacion_g41_biess;
				$_relacion_laboral_g41_biess = $res->relacion_laboral_g41_biess;
				$_estado_registro_g41_biess = $res->estado_registro_g41_biess;
				$_tipo_aportacion_g41_biess = $res->tipo_aportacion_g41_biess;
				
				$texto .= '<Registro NumeroRegistro="'. $i.'">';
					$texto .= '<TipoIdentificacionParticipe>'.$_tipo_identificacion_g41_biess.'</TipoIdentificacionParticipe>';
					$texto .= '<IdentificacionParticipe>'.$_identificacion_participe_g41_biess.'</IdentificacionParticipe>';
					$texto .= '<CorreoElectronico>'.$_correo_participe_g41_biess.'</CorreoElectronico>';
					$texto .= '<NombreParticipe>'.$_nombre_participe_g41_biess.'</NombreParticipe>';
					$texto .= '<SexoParticipe>'.$_sexo_participe_g41_biess.'</SexoParticipe>';
					$texto .= '<EstadoCivilParticipe>'.$_estado_civil_g41_biess.'</EstadoCivilParticipe>';
					$newDate_fechaemision = date("d/m/Y", strtotime($_fecha_ingreso_participe_g41_biess));
					$texto .= '<FechaIngresoParticipe>'.$newDate_fechaemision.'</FechaIngresoParticipe>';
					$texto .= '<TipoRegistro>'.$_tipo_registro_aporte_g41_biess.'</TipoRegistro>';
					$texto .= '<BaseCalculoAportacion>'.$_base_calculo_aportacion_g41_biess.'</BaseCalculoAportacion>';
					$texto .= '<EstadoRegistro>'.$_relacion_laboral_g41_biess.'</EstadoRegistro>';
					$texto .= '<RelacionLaboral>'.$_estado_registro_g41_biess.'</RelacionLaboral>';
					$texto .= '<TipoPrestacion>'.$_tipo_aportacion_g41_biess.'</TipoPrestacion>';
				$texto .= '</Registro>';
			}
		
			$texto .= '</Detalle>';
			$texto .= '</REGISTROS>';
		
		
		}
		/*
		$respuesta = array();
		$respuesta['tabladatos'] ="Hola";
		echo json_encode($respuesta);
		
		die();
		*/
		
		$fecha_hoy = getdate();
		$newDate_fechaHoy = date("dmY");
		$_mes_nombre_archivo = $mes_reporte1; 
		if (strlen($mes_reporte1) == 1)
		{
			$_mes_nombre_archivo = '0' .$mes_reporte1;   
		}
		
		$nombre_archivo = "17-".$anio_reporte.$_mes_nombre_archivo."-G41". $newDate_fechaHoy .".xml";
			
			//CB-AAAA-MM-G41ddmmaaaa.xml 
		$ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOS_GENERADOS\\ESTRUCTURAS\\BIESS\\G41\\";
		$ubicacion = $ubicacionServer.$nombre_archivo;
	
	
		$textoXML = mb_convert_encoding($texto, "UTF-8");
	
			// Grabamos el XML en el servidor como un fichero plano, para
			// poder ser leido por otra aplicación.
		$gestor = fopen($ubicacionServer.$nombre_archivo, 'w');
		fwrite($gestor, $textoXML);
		fclose($gestor);
	
		
		header("Content-disposition: attachment; filename=$nombre_archivo");
		header("Content-type: MIME");
		ob_clean();
		flush();
		// Read the file
		//echo $ubicacion;
		//print_r($_POST);
		//echo  "******llego--",$_tipo_archivo_recaudaciones,"***" ;
		//echo "parametro id ---",$_id_archivo_recaudaciones,"**";
		readfile($ubicacion);
		exit;
		
				
	
		}
			
	
	
	
	
}
?>