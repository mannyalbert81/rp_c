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
	public function index2(){
	
		//Creamos el objeto usuario
	
		session_start();
		$this->view_Informativo("G42",array());
	
	}
	public function CargaInformacion()
	{
	    session_start();
	    
	    $G41= new G41Model();
	    $id_usuarios=$_SESSION['id_usuarios'];
	    $mes_reporte=$_POST['mes_reporte']+1;
	   
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
		$where = " anio = '$anio_reporte' AND mes = '$mes_reporte1' AND estado_registro_g41_biess= 'ING'";
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
					$texto .= '<RelacionLaboral>'.$_relacion_laboral_g41_biess.'</RelacionLaboral>';
					$texto .= '<EstadoRegistro>'.$_estado_registro_g41_biess.'</EstadoRegistro>';
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
		
		$nombre_archivo = "17-".$anio_reporte.'-'.$_mes_nombre_archivo."_G41". $newDate_fechaHoy .".xml";
			
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
			
	
		}
			
	

		///g42


	public function CargaInformacionG42()
	{
		session_start();
		 
		$G42= new G42Model();
		$id_usuarios=$_SESSION['id_usuarios'];
		$mes_reporte=$_POST['mes_reporte'];
	
		$anio_reporte=$_POST['anio_reporte'];
		$mes_reporte1=$mes_reporte+1;
		
		
	   $_id_g42_biess ;
	   $_tipo_identificacion_g42_biess ;
	   $_identificacion_g42_biess; 
       $_tipo_prestacion_g42_biess;
       $_estado_participe_cesante_g42_biess; 
       $_estado_participe_jubilado_g42_biess; 
       $_aporte_personal_cesantia_g42_biess = "0.00";
       $_aporte_personal_jubilado_g42_biess = "0.00"; 
       $_aporte_adicional_jubilacion_g42_biess = "0.00";  
       $_aporte_adicional_cesantia_g42_biess = "0.00";
       $_saldo_cuenta_individual_patronal_g42_biess = "0.00";
       $_saldo_cuenta_individual_cesantia_g42_biess = "0.00";
       $_saldo_cuenta_individual_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_personal_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_personal_cesantia_g42_biess = "0.00";
       $_saldo_aporte_adicional_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_adicional_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_patronal_otros_g42_biess= "0.00";
       $_saldo_rendimiento_aporte_personal_jubilacion_g42_biess = "0.00"; 
       $_saldo_rendimiento_aporte_personal_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_aporte_adicional_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_aporte_adicional_jubilacion_g42_biess = "0.00";
       $_retencion_fiscal_g42_biess = "0.00";
       $_fecha_desafiliacion_voluntaria_g42_biess; 
       $_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42 = "0.00"; 
       $_valor_pendiente_pago_desafiliacion_g42_biess = "0.00";
       $_valor_pagado_participe_desafiliado_g42_biess = "0.00";
       $_motivo_liquidacion_g42_biess = "0.00";
       $_fecha_termino_relacion_laboral_g42_biess; 
       $_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 = "0.00"; 
       $_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 = "0.00";
       $_detalle_otros_valores_pagados_y_pendientes_pago_g42_biess = "0.00";
       $_valores_pagados_fondo_g42_biess = "0.00";
       $_valores_pendientes_pago_cuentas_por_pagar_particpe_g42_biess = "0.00"; 
       $_valor_pagado_participe_por_cesantia_g42_biess = "0.00";
       $_valor_pagado_participe_por_jubiliacion_g42_biess = "0.00";
       $_descripcion_otros_conceptos_g42_biess ;
       $_valores_pagados_al_participe_otros_conceptos_g42_biess = "0.00"; 
       
		$i = 0;
		 
		$columnas = "id_g42_biess, tipo_identificacion_g42_biess, identificacion_g42_biess, 
       tipo_prestacion_g42_biess, estado_participe_cesante_g42_biess, 
       estado_participe_jubilado_g42_biess, aporte_personal_cesantia_g42_biess, 
       aporte_personal_jubilado_g42_biess, aporte_adicional_jubilacion_g42_biess, rendimiento_anual_g42_biess,
       aporte_adicional_cesantia_g42_biess, saldo_cuenta_individual_patronal_g42_biess, 
       saldo_cuenta_individual_cesantia_g42_biess, saldo_cuenta_individual_jubilacion_g42_biess, 
       saldo_aporte_personal_jubilacion_g42_biess, saldo_aporte_personal_cesantia_g42_biess, 
       saldo_aporte_adicional_jubilacion_g42_biess, saldo_aporte_adicional_cesantia_g42_biess, 
       saldo_rendimiento_patronal_otros_g42_biess, saldo_rendimiento_aporte_personal_jubilacion_g42_biess, 
       saldo_rendimiento_aporte_personal_cesantia_g42_biess, saldo_rendimiento_aporte_adicional_cesantia_g42_biess, 
       saldo_rendimiento_aporte_adicional_jubilacion_g42_biess, retencion_fiscal_g42_biess, 
       fecha_desafiliacion_voluntaria_g42_biess, monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42, 
       valor_pendiente_pago_desafiliacion_g42_biess, valor_pagado_participe_desafiliado_g42_biess, 
       motivo_liquidacion_g42_biess, fecha_termino_relacion_laboral_g42_biess, 
       saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42, 
       saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42, 
       detalle_otros_valores_pagados_y_pendientes_pago_g42_biess, valores_pagados_al_fondo_g42_biess, 
       valores_pendientes_pago_al_fondo_g42_biess, 
       valor_pagado_participe_por_cesantia_g42_biess, valor_pagado_participe_por_jubiliacion_g42_biess, 
       descripcion_otros_conceptos_g42_biess, valores_pagados_al_participe_otros_conceptos_g42_biess, 
       anio_g42_biess, mes_g42_biess, creado, modificado";
		$tablas = "public.core_g42_biess";
		$where = " anio_g42_biess = '$anio_reporte' AND mes_g42_biess = '$mes_reporte1' ";
		
		
		$id = " id_g42_biess" ;
		 
		$html= "";
		 
		$resultSet=$G42->getCondiciones($columnas, $tablas, $where, $id);
		if ($resultSet !="")
		{
	
			$html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
			$html.= "<thead>";
			$html.= "<tr>";
			$html.='<th style="text-align: center;  font-size: 12px;">Numero Registro</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Tipo Identificación Participe</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Identificación Participe</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Tipo Prestación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Estado del partícipe de Cesantía </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Estado del partícipe de Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Aporte Personal Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Aporte Personal Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Aporte Adicional de Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Aporte Adicional de Cesantía  </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Rendimiento Anual</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Cuenta Individual Patronal</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo de Cuenta individual Cesantía </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo de cuenta individual Jubilación </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Aporte Personal Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Aporte Adicional Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Aporte Adicional Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Rendimiento Patronal / Otros</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Rendimiento Aporte Personal Jubilación </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Rendimiento Aporte Personal Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Rendimiento  Aporte Adicional Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Rendimiento Aporte Adicional Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Retención fiscal</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Fecha de la Desafiliación voluntaria  </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Monto por desafiliación Voluntaria ( Liquidación por Desafiliación )</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valor Pendiente de Pago por desafiliación </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valor Pagado al Participe desafiliado</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Motivo de la Liquidación </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Fecha Término Relación Laboral</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Cuenta Individual / Liquidación de prestación de Cesantía</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Saldo Cuenta Individual /  Liquidación de prestación de Jubilación</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Detalle de Otros Valores pagados al Fondo o/y pendientes de pago </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valores pagados al Fondo </th>';
			$html.='<th style=text-align: center;  font-size: 12px;">Valores pendientes de pago al Fondo ( Cuentas por pagar partícipe)</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valor Pagado Participe por Cesantía </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valor Pagado Participe por Jubilación </th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Descripción Otros conceptos</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">Valores pagados al partícipe (Otros conceptos)</th>';
			
			
			//$html.='<th style="text-align: left;  font-size: 12px;"></th>';
			$html.='</tr>';
			$html.='</thead>';
			$html.='<tbody>';
	
			foreach($resultSet as $res)
			{
				$i ++;
		   
		   
				$html.='<tr>';
				$html.='<td style="font-size: 11px;">'.$i.'</td>';

      
				$html.='<td style="font-size: 11px;">'.$res->tipo_identificacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->identificacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->tipo_prestacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->estado_participe_cesante_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->estado_participe_jubilado_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->aporte_personal_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->aporte_personal_jubilado_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->aporte_adicional_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->aporte_adicional_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_individual_patronal_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_individual_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_individual_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_aporte_personal_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_aporte_personal_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_aporte_adicional_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_aporte_adicional_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_rendimiento_patronal_otros_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_rendimiento_aporte_personal_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_rendimiento_aporte_personal_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_rendimiento_aporte_adicional_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->retencion_fiscal_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_rendimiento_aporte_adicional_jubilacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->fecha_desafiliacion_voluntaria_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valor_pendiente_pago_desafiliacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valor_pagado_participe_desafiliado_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->motivo_liquidacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->fecha_termino_relacion_laboral_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->detalle_otros_valores_pagados_y_pendientes_pago_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valores_pagados_al_fondo_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valores_pendientes_pago_al_fondo_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valor_pagado_participe_por_cesantia_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valor_pagado_participe_por_jubiliacion_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->descripcion_otros_conceptos_g42_biess.'</td>';
				$html.='<td style="font-size: 11px;">'.$res->valores_pagados_al_participe_otros_conceptos_g42_biess.'</td>';
							
		   
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
		
		public function generaG42(){
		
		
			$G42= new G42Model();
			//$id_usuarios=$_SESSION['id_usuarios'];
			$mes_reporte=$_POST['mes_reporte'];
			
			$anio_reporte=$_POST['anio_reporte'];
			$mes_reporte1=$mes_reporte+1;
				
		   $_id_g42_biess ;
	   $_tipo_identificacion_g42_biess ;
	   $_identificacion_g42_biess; 
       $_tipo_prestacion_g42_biess;
       $_estado_participe_cesante_g42_biess; 
       $_estado_participe_jubilado_g42_biess; 
       $_aporte_personal_cesantia_g42_biess = "0.00";
       $_aporte_personal_jubilado_g42_biess = "0.00"; 
       $_aporte_adicional_jubilacion_g42_biess = "0.00";  
       $_aporte_adicional_cesantia_g42_biess = "0.00";
       $_rendimiento_anual_g42_biess = "0.00";
       $_saldo_cuenta_individual_patronal_g42_biess = "0.00";
       $_saldo_cuenta_individual_cesantia_g42_biess = "0.00";
       $_saldo_cuenta_individual_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_personal_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_personal_cesantia_g42_biess = "0.00";
       $_saldo_aporte_adicional_jubilacion_g42_biess = "0.00";
       $_saldo_aporte_adicional_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_patronal_otros_g42_biess= "0.00";
       $_saldo_rendimiento_aporte_personal_jubilacion_g42_biess = "0.00"; 
       $_saldo_rendimiento_aporte_personal_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_aporte_adicional_cesantia_g42_biess = "0.00";
       $_saldo_rendimiento_aporte_adicional_jubilacion_g42_biess = "0.00";
       $_retencion_fiscal_g42_biess = "0.00";
       $_fecha_desafiliacion_voluntaria_g42_biess; 
       $_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42 = "0.00"; 
       $_valor_pendiente_pago_desafiliacion_g42_biess = "0.00";
       $_valor_pagado_participe_desafiliado_g42_biess = "0.00";
       $_motivo_liquidacion_g42_biess = "0.00";
       $_fecha_termino_relacion_laboral_g42_biess; 
       $_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 = "0.00"; 
       $_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 = "0.00";
       $_detalle_otros_valores_pagados_y_pendientes_pago_g42_biess = "0.00";
       $_valores_pagados_fondo_g42_biess = "0.00";
       $_valores_pendientes_pago_cuentas_por_pagar_particpe_g42_biess = "0.00"; 
       $_valor_pagado_participe_por_cesantia_g42_biess = "0.00";
       $_valor_pagado_participe_por_jubiliacion_g42_biess = "0.00";
       $_descripcion_otros_conceptos_g42_biess ;
       $_valores_pagados_al_participe_otros_conceptos_g42_biess = "0.00"; 
       
		$i = 0;
		 
		$columnas = "id_g42_biess, tipo_identificacion_g42_biess, identificacion_g42_biess, 
       tipo_prestacion_g42_biess, estado_participe_cesante_g42_biess, 
       estado_participe_jubilado_g42_biess, aporte_personal_cesantia_g42_biess, 
       aporte_personal_jubilado_g42_biess, aporte_adicional_jubilacion_g42_biess, 
       aporte_adicional_cesantia_g42_biess, rendimiento_anual_g42_biess ,saldo_cuenta_individual_patronal_g42_biess, 
       saldo_cuenta_individual_cesantia_g42_biess, saldo_cuenta_individual_jubilacion_g42_biess, 
       saldo_aporte_personal_jubilacion_g42_biess, saldo_aporte_personal_cesantia_g42_biess, 
       saldo_aporte_adicional_jubilacion_g42_biess, saldo_aporte_adicional_cesantia_g42_biess, 
       saldo_rendimiento_patronal_otros_g42_biess, saldo_rendimiento_aporte_personal_jubilacion_g42_biess, 
       saldo_rendimiento_aporte_personal_cesantia_g42_biess, saldo_rendimiento_aporte_adicional_cesantia_g42_biess, 
       saldo_rendimiento_aporte_adicional_jubilacion_g42_biess, retencion_fiscal_g42_biess, 
       fecha_desafiliacion_voluntaria_g42_biess, monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42, 
       valor_pendiente_pago_desafiliacion_g42_biess, valor_pagado_participe_desafiliado_g42_biess, 
       motivo_liquidacion_g42_biess, fecha_termino_relacion_laboral_g42_biess, 
       saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42, 
       saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42, 
       detalle_otros_valores_pagados_y_pendientes_pago_g42_biess, valores_pagados_al_fondo_g42_biess, 
       valores_pendientes_pago_al_fondo_g42_biess, 
       valor_pagado_participe_por_cesantia_g42_biess, valor_pagado_participe_por_jubiliacion_g42_biess, 
       descripcion_otros_conceptos_g42_biess, valores_pagados_al_participe_otros_conceptos_g42_biess, 
       anio_g42_biess, mes_g42_biess, creado, modificado";
		$tablas = "public.core_g42_biess";
		$where = " anio_g42_biess = '$anio_reporte' AND mes_g42_biess = '$mes_reporte1' ";
		$id = " id_g42_biess" ;
			//validar los campos recibidos para generar diario
		
			$texto = "";
		
			$resultSet=$G42->getCondiciones($columnas, $tablas, $where, $id);
		
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
				$texto .= '<CodigoEstructura>G42</CodigoEstructura>';
				$texto .= '<CodigoEntidad>17</CodigoEntidad>';
				$texto .= '<FechaCorte>'.$newDate_fechacorte.'</FechaCorte>';
				$texto .= '<TotalRegistros>'.$cantidad_lineas.'</TotalRegistros>';
				$texto .= '<Totalparticipescero>0</Totalparticipescero>';
				$texto .= '</DatosEstructura>';
				$texto .= '<Detalle>';
				
				foreach($resultSet as $res)
				{
				
					$i ++;
					
					$_tipo_identificacion_g42_biess 		= $res->tipo_identificacion_g42_biess;
					$_identificacion_g42_biess 				= $res->identificacion_g42_biess;
					$_tipo_prestacion_g42_biess				= $res->tipo_prestacion_g42_biess;
					$_estado_participe_cesante_g42_biess	= $res->estado_participe_cesante_g42_biess;
					$_estado_participe_jubilado_g42_biess	= $res->estado_participe_jubilado_g42_biess;
					$_aporte_personal_cesantia_g42_biess 	= $res->aporte_personal_cesantia_g42_biess;
					$_aporte_personal_jubilado_g42_biess 	= $res->aporte_personal_jubilado_g42_biess;
					$_aporte_adicional_jubilacion_g42_biess = $res->aporte_adicional_jubilacion_g42_biess;
					$_aporte_adicional_cesantia_g42_biess 	= $res->aporte_adicional_cesantia_g42_biess;
					$_rendimiento_anual_g42_biess =  $res->rendimiento_anual_g42_biess;
					$_saldo_cuenta_individual_patronal_g42_biess = $res->saldo_cuenta_individual_patronal_g42_biess;
					$_saldo_cuenta_individual_cesantia_g42_biess = $res->saldo_cuenta_individual_cesantia_g42_biess;
					$_saldo_cuenta_individual_jubilacion_g42_biess = $res->saldo_cuenta_individual_jubilacion_g42_biess;
					$_saldo_aporte_personal_jubilacion_g42_biess =  $res->saldo_aporte_personal_jubilacion_g42_biess;
					$_saldo_aporte_personal_cesantia_g42_biess = $res->saldo_aporte_personal_cesantia_g42_biess;
					$_saldo_aporte_adicional_jubilacion_g42_biess = $res->saldo_aporte_adicional_jubilacion_g42_biess;
					$_saldo_aporte_adicional_cesantia_g42_biess 	= $res->saldo_aporte_adicional_cesantia_g42_biess;
					$_saldo_rendimiento_patronal_otros_g42_biess	= $res->saldo_rendimiento_patronal_otros_g42_biess;
					$_saldo_rendimiento_aporte_personal_jubilacion_g42_biess = $res->saldo_rendimiento_aporte_personal_jubilacion_g42_biess;
					$_saldo_rendimiento_aporte_personal_cesantia_g42_biess = $res->saldo_rendimiento_aporte_personal_cesantia_g42_biess;
					$_saldo_rendimiento_aporte_adicional_cesantia_g42_biess = $res->saldo_rendimiento_aporte_adicional_cesantia_g42_biess;
					$_saldo_rendimiento_aporte_adicional_jubilacion_g42_biess = $res->saldo_rendimiento_aporte_adicional_jubilacion_g42_biess;
					$_retencion_fiscal_g42_biess = $res->retencion_fiscal_g42_biess;
					$_fecha_desafiliacion_voluntaria_g42_biess	= $res->fecha_desafiliacion_voluntaria_g42_biess;
					$_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42 = $res->monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42;
					$_valor_pendiente_pago_desafiliacion_g42_biess = $res->valor_pendiente_pago_desafiliacion_g42_biess;
					$_valor_pagado_participe_desafiliado_g42_biess = $res->valor_pagado_participe_desafiliado_g42_biess;
					$_motivo_liquidacion_g42_biess = $res->motivo_liquidacion_g42_biess;
					$_fecha_termino_relacion_laboral_g42_biess 	= $res->fecha_termino_relacion_laboral_g42_biess;
					$_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 = $res->saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42;
					$_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 = $res->saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42;
					$_detalle_otros_valores_pagados_y_pendientes_pago_g42_biess = $res->detalle_otros_valores_pagados_y_pendientes_pago_g42_biess;
					$_valores_pagados_al_fondo_g42_biess = $res->valores_pagados_al_fondo_g42_biess;
					$_valores_pendientes_pago_al_fondo_g42_biess = $res->valores_pendientes_pago_al_fondo_g42_biess;
					$_valor_pagado_participe_por_cesantia_g42_biess = $res->valor_pagado_participe_por_cesantia_g42_biess;
					$_valor_pagado_participe_por_jubiliacion_g42_biess = $res->valor_pagado_participe_por_jubiliacion_g42_biess;
					$_descripcion_otros_conceptos_g42_biess = $res->descripcion_otros_conceptos_g42_biess;
					$_valores_pagados_al_participe_otros_conceptos_g42_biess = $res->valores_pagados_al_participe_otros_conceptos_g42_biess;
					 
			
					
		
					$texto .= '<Registro NumeroRegistro="'. $i.'">';
					$texto .= '<TipoIdentificacionParticipe>'.$_tipo_identificacion_g42_biess.'</TipoIdentificacionParticipe>';
					$texto .= '<IdentificacionParticipe>'.$_identificacion_g42_biess.'</IdentificacionParticipe>';
					$texto .= '<TipoPrestacion>'.$_tipo_prestacion_g42_biess.'</TipoPrestacion>';
					$texto .= '<EstadoParticipeCES>'.$_estado_participe_cesante_g42_biess.'</EstadoParticipeCES>';
					$texto .= '<EstadoParticipeJUB>'.$_estado_participe_jubilado_g42_biess.'</EstadoParticipeJUB>';
					$texto .= '<AportePersonalCES>'.$_aporte_personal_cesantia_g42_biess.'</AportePersonalCES>';
					$texto .= '<AportePersonalJUB>'.$_aporte_personal_jubilado_g42_biess.'</AportePersonalJUB>';
					$texto .= '<AporteAdicionalJUB>'.$_aporte_adicional_jubilacion_g42_biess.'</AporteAdicionalJUB>';
					$texto .= '<AporteAdicionalCES>'.$_aporte_adicional_cesantia_g42_biess.'</AporteAdicionalCES>';
					$texto .= '<RendimientoAnual>'.$_rendimiento_anual_g42_biess.'</RendimientoAnual>';
					if ($_saldo_cuenta_individual_patronal_g42_biess > 0)
					{
						$texto .= '<SaldoCIPatronal>'.$_saldo_cuenta_individual_patronal_g42_biess.'</SaldoCIPatronal>';
						
					}
					else
					{
						$texto .= '<SaldoCIPatronal>0.0001</SaldoCIPatronal>';
					}
					
					$texto .= '<SaldoCIcesantia>'.$_saldo_cuenta_individual_cesantia_g42_biess.'</SaldoCIcesantia>';
					$texto .= '<SaldoCIjubilacion>'.$_saldo_cuenta_individual_jubilacion_g42_biess.'</SaldoCIjubilacion>';
					$texto .= '<SaldoAportePersonalJub>'.$_saldo_aporte_personal_jubilacion_g42_biess.'</SaldoAportePersonalJub>';
					$texto .= '<SaldoAportePersonalCes>'.$_saldo_aporte_personal_cesantia_g42_biess.'</SaldoAportePersonalCes>';
					$texto .= '<SaldoAporteAdicJub>'.$_saldo_aporte_adicional_jubilacion_g42_biess.'</SaldoAporteAdicJub>';
					
					if ($_saldo_aporte_adicional_cesantia_g42_biess > 0 )
					{
						$texto .= '<SaldoAporteAdicCes>'.$_saldo_aporte_adicional_cesantia_g42_biess.'</SaldoAporteAdicCes>';
						
					}
					else
					{
						$texto .= '<SaldoAporteAdicCes>0.0001</SaldoAporteAdicCes>';
					}
					
					if ($_saldo_rendimiento_patronal_otros_g42_biess > 0)
					{
						$texto .= '<SaldoRendiPatronal>'.$_saldo_rendimiento_patronal_otros_g42_biess.'</SaldoRendiPatronal>';
					}
					else 
					{
						$texto .= '<SaldoRendiPatronal>0.0001</SaldoRendiPatronal>';
						
					}
					
					$texto .= '<SaldoRendiAPjubilacion>'.$_saldo_rendimiento_aporte_personal_jubilacion_g42_biess.'</SaldoRendiAPjubilacion>';
					$texto .= '<SaldoRendiAPcesantia>'.$_saldo_rendimiento_aporte_personal_cesantia_g42_biess.'</SaldoRendiAPcesantia>';
					if ($_saldo_rendimiento_aporte_adicional_cesantia_g42_biess > 0)
					{
						$texto .= '<SaldoRendiAAcesantia>'.$_saldo_rendimiento_aporte_adicional_cesantia_g42_biess.'</SaldoRendiAAcesantia>';
					}
					else
					{
						$texto .= '<SaldoRendiAAcesantia>0.0001</SaldoRendiAAcesantia>';
					}
					
					$texto .= '<SaldoRendiAAjubilacion>'.$_saldo_rendimiento_aporte_adicional_jubilacion_g42_biess.'</SaldoRendiAAjubilacion>';
					$texto .= '<RetencionFiscal>'.$_retencion_fiscal_g42_biess.'</RetencionFiscal>';
					$texto .= '<FechaDesafiliacion>'.$_fecha_desafiliacion_voluntaria_g42_biess.'</FechaDesafiliacion>';
					$texto .= '<MontoDesafiliacionVol>'.$_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42.'</MontoDesafiliacionVol>';
					$texto .= '<ValorPendienteDesaf>'.$_valor_pendiente_pago_desafiliacion_g42_biess.'</ValorPendienteDesaf>';
					$texto .= '<ValorPagPartDesaf>'.$_valor_pagado_participe_desafiliado_g42_biess.'</ValorPagPartDesaf>';
					$texto .= '<MotivoLiquidacion>'.$_motivo_liquidacion_g42_biess.'</MotivoLiquidacion>';
					$texto .= '<FechaTerminoRL>'.$_fecha_termino_relacion_laboral_g42_biess.'</FechaTerminoRL>';
					
					if ($_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 > 0)
					{
						$texto .= '<SaldoCIliqPrestacionCES>'.$_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42.'</SaldoCIliqPrestacionCES>';
					}
					else
					{
						if ( $_estado_participe_cesante_g42_biess == 'PARTICIPEACTIVO' )
						{
							$texto .= '<SaldoCIliqPrestacionCES>0.00</SaldoCIliqPrestacionCES>';
						}
						else
						{
							$texto .= '<SaldoCIliqPrestacionCES>0.0001</SaldoCIliqPrestacionCES>';
						}
						
					}
					
					$texto .= '<SaldoCIliqPrestacionJUB>'.$_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42.'</SaldoCIliqPrestacionJUB>';
					$texto .= '<DetalleOtrosValores>'.$_detalle_otros_valores_pagados_y_pendientes_pago_g42_biess.'</DetalleOtrosValores>';
					$texto .= '<ValoresPagadosFondo>'.$_valores_pagados_al_fondo_g42_biess.'</ValoresPagadosFondo>';
					$texto .= '<ValoresPendientesFondo>'.$_valores_pendientes_pago_al_fondo_g42_biess.'</ValoresPendientesFondo>';
					$texto .= '<ValorPagadoParticipeCES>'.$_valor_pagado_participe_por_cesantia_g42_biess.'</ValorPagadoParticipeCES>';
					$texto .= '<ValorPagadoParticipeJUB>'.$_valor_pagado_participe_por_jubiliacion_g42_biess.'</ValorPagadoParticipeJUB>';
					$texto .= '<DescripOtrosConceptos>'.$_descripcion_otros_conceptos_g42_biess.'</DescripOtrosConceptos>';
					$texto .= '<ValorPagadoParticipeOtros>'.$_valores_pagados_al_participe_otros_conceptos_g42_biess.'</ValorPagadoParticipeOtros>';
					
			
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
		
			$nombre_archivo = "17-".$anio_reporte.'-'.$_mes_nombre_archivo."_G42". $newDate_fechaHoy .".xml";
				
			//CB-AAAA-MM-G41ddmmaaaa.xml
			$ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOS_GENERADOS\\ESTRUCTURAS\\BIESS\\G42\\";
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
				
		
		}
		
		
	
	
}
?>