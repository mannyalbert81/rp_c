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
	
	
	public function index3(){
	    
	    //Creamos el objeto usuario
	    
	    session_start();
	    $this->view_Informativo("G45",array());
	    
	}
	
	public function index4(){
	    
	    //Creamos el objeto usuario
	    
	    session_start();
	    $this->view_Informativo("G46",array());
	    
	}
	
	
	public function CargaInformacion()
	{
	    session_start();
	    
	    $G41= new G41Model();
	    //$id_usuarios=$_SESSION['id_usuarios'];
	    $mes_reporte=$_POST['mes_reporte'];
	   
	    $anio_reporte=$_POST['anio_reporte'];
	   
	    
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
	    $where = " anio = '$anio_reporte' AND mes = '$mes_reporte' ";
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
			$i = 0;
			
			
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
		
		$mes_reporte=  12;    //$_POST['mes_reporte'];
		
		$anio_reporte=  2020;   //$_POST['anio_reporte'];
		
		 
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
		
		$where = " anio = '$anio_reporte' AND mes = '$mes_reporte' AND estado_registro_g41_biess = 'ING' ";
		//$mes_actual
		$id = " id_g41_biess" ;
		 
		
			//validar los campos recibidos para generar diario
		
		$texto = "";
		
		$resultSet=$G41->getCondiciones($columnas, $tablas, $where, $id);
		
		if(!empty($resultSet)){
		
			
			$fecha =  "01/".$mes_reporte."/".$anio_reporte;
			
		//	$fecha_corte = $G41->ultimo_dia_mes_fecha($fecha);
			$cantidad_lineas = count($resultSet) + 1;
			$anio_mes = $anio_reporte.'-'.$mes_reporte;
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
		$_mes_nombre_archivo = $mes_reporte; 
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
		$where = " anio_g42_biess = '$anio_reporte' AND mes_g42_biess = '$mes_reporte' ";
		
		
		$id = " id_g42_biess" ;
		 
		$html= "";
		 
		$resultSet=$G42->getCondiciones($columnas, $tablas, $where, $id);
		
		if(!empty($resultSet))
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
	
			
			
			
			$i = 0;
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
		 /*
		echo  "Resultado Mes Reportado " . $html;
		die();
		*/	
		
		$respuesta = array();
		$respuesta['tabladatos'] =$html;
		echo json_encode($respuesta);
		 
		die();
		 
		 
	
		 
	}
		
	public function generaG42(){
		
		
			$G42= new G42Model();
			//$id_usuarios=$_SESSION['id_usuarios'];
			$mes_reporte=  12;//     $_POST['mes_reporte'];
			
			$anio_reporte= 2020; //     $_POST['anio_reporte'];
			
			
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
		$where = " anio_g42_biess = '$anio_reporte' AND mes_g42_biess = '$mes_reporte' ";
		$id = " id_g42_biess" ;
			//validar los campos recibidos para generar diario
		
			$texto = "";
		
			$resultSet=$G42->getCondiciones($columnas, $tablas, $where, $id);
		
			if(!empty($resultSet)){
		
		
					
				$fecha =  "01/".$mes_reporte."/".$anio_reporte;
					
				//	$fecha_corte = $G41->ultimo_dia_mes_fecha($fecha);
				$cantidad_lineas = count($resultSet) + 1;
				$anio_mes = $anio_reporte.'-'.$mes_reporte;
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
					
					if ($res->fecha_desafiliacion_voluntaria_g42_biess == "")
					{
					    $_fecha_desafiliacion_voluntaria_g42_biess	= "";
					    
					}
					else 
					{
					    $newDate_fechaemision1 = date("d/m/Y", strtotime($res->fecha_desafiliacion_voluntaria_g42_biess));
					    $_fecha_desafiliacion_voluntaria_g42_biess	= $newDate_fechaemision1;
					    
					}
				
					
					$_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42 = $res->monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42;
					$_valor_pendiente_pago_desafiliacion_g42_biess = $res->valor_pendiente_pago_desafiliacion_g42_biess;
					$_valor_pagado_participe_desafiliado_g42_biess = $res->valor_pagado_participe_desafiliado_g42_biess;
					$_motivo_liquidacion_g42_biess = $res->motivo_liquidacion_g42_biess;
					
					if ( $res->fecha_termino_relacion_laboral_g42_biess != '' )
					{
					    
					    $newDate_fechaemision2 = date("d/m/Y", strtotime($res->fecha_termino_relacion_laboral_g42_biess));
					    $_fecha_termino_relacion_laboral_g42_biess 	= $newDate_fechaemision2;
					}
					else
					{
					    
					    $_fecha_termino_relacion_laboral_g42_biess 	= null;
					    
					}
					
					
					
					if ($res->saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 == 0)
					{
					    $_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 = 0;
					    
					}
					else
					{
					    
					    $_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 = $res->saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42;
					}
					
					
					if ($res->saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 == 0)
					{
					    $_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 = 0;
					    
					}
					else 
					{
					    
					    $_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42 = $res->saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42;
					    
					}
					
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
					$texto .= '<EstadoParticipeJUB>'.$_estado_participe_cesante_g42_biess.'</EstadoParticipeJUB>';
							
					if ($_aporte_personal_cesantia_g42_biess > 0)
					{
					    $texto .= '<AportePersonalCES>'.$_aporte_personal_cesantia_g42_biess.'</AportePersonalCES>';
					    
					}
					else {
					    $texto .= '<AportePersonalCES>'.'0'.'</AportePersonalCES>';
					    
					}
					
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
						$texto .= '<SaldoCIPatronal>0</SaldoCIPatronal>';
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
						$texto .= '<SaldoRendiPatronal>0</SaldoRendiPatronal>';
						
					}
					
					$texto .= '<SaldoRendiAPjubilacion>'.$_saldo_rendimiento_aporte_personal_jubilacion_g42_biess.'</SaldoRendiAPjubilacion>';
					$texto .= '<SaldoRendiAPcesantia>'.$_saldo_rendimiento_aporte_personal_cesantia_g42_biess.'</SaldoRendiAPcesantia>';
					if ($_saldo_rendimiento_aporte_adicional_cesantia_g42_biess > 0)
					{
						$texto .= '<SaldoRendiAAcesantia>'.$_saldo_rendimiento_aporte_adicional_cesantia_g42_biess.'</SaldoRendiAAcesantia>';
					}
					else
					{
						$texto .= '<SaldoRendiAAcesantia>0</SaldoRendiAAcesantia>';
					}
					
					$texto .= '<SaldoRendiAAjubilacion>'.$_saldo_rendimiento_aporte_adicional_jubilacion_g42_biess.'</SaldoRendiAAjubilacion>';
					$texto .= '<RetencionFiscal>'.$_retencion_fiscal_g42_biess.'</RetencionFiscal>';
					$texto .= '<FechaDesafiliacion>'.$_fecha_desafiliacion_voluntaria_g42_biess.'</FechaDesafiliacion>';
					$texto .= '<MontoDesafiliacionVol>'.$_monto_desafiliacion_voluntaria_liquidacion_desafiliacion_g42.'</MontoDesafiliacionVol>';
					$texto .= '<ValorPendienteDesaf>'.$_valor_pendiente_pago_desafiliacion_g42_biess.'</ValorPendienteDesaf>';
					$texto .= '<ValorPagPartDesaf>'.$_valor_pagado_participe_desafiliado_g42_biess.'</ValorPagPartDesaf>';
					$texto .= '<MotivoLiquidacion>'.$_motivo_liquidacion_g42_biess.'</MotivoLiquidacion>';
					
					 
					if ( $_estado_participe_cesante_g42_biess == 'LIQUIDADO' )
					{
					    
					    $texto .= '<FechaTerminoRL>'.$fecha .'</FechaTerminoRL>';
					    
					}
					else
					{
					    $texto .= '<FechaTerminoRL>'.$_fecha_termino_relacion_laboral_g42_biess.'</FechaTerminoRL>';
					    
					}
					
					
					
					if ($_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42 > 0)
					{
						$texto .= '<SaldoCIliqPrestacionCES>'.$_saldo_cuenta_individual_liquidacion_prestacion_cesantia_g42.'</SaldoCIliqPrestacionCES>';
					}
					else
					{
						if ( $_estado_participe_cesante_g42_biess == 'PARTICIPEACTIVO' )
						{
							$texto .= '<SaldoCIliqPrestacionCES>0</SaldoCIliqPrestacionCES>';
						}
						else
						{
							$texto .= '<SaldoCIliqPrestacionCES>0</SaldoCIliqPrestacionCES>';
						}
						
					}
					
					$texto .= '<SaldoCIliqPrestacionJUB>'.$_saldo_cuenta_individual_liquidacion_prestacion_jubilado_g42.'</SaldoCIliqPrestacionJUB>';
					$texto .= '<DetalleOtrosValores>'.$_detalle_otros_valores_pagados_y_pendientes_pago_g42_biess.'</DetalleOtrosValores>';
					$texto .= '<ValoresPagadosFondo>'.$_valores_pagados_al_fondo_g42_biess.'</ValoresPagadosFondo>';
					$texto .= '<ValoresPendientesFondo>'.$_valores_pendientes_pago_al_fondo_g42_biess.'</ValoresPendientesFondo>';
					
					if ($_valor_pagado_participe_por_cesantia_g42_biess < 0 )
					{
					    $texto .= '<ValorPagadoParticipeCES>'.$_valor_pagado_participe_por_cesantia_g42_biess * -1 .'</ValorPagadoParticipeCES>';
					    
					}
					else 
					{
					    $texto .= '<ValorPagadoParticipeCES>'.$_valor_pagado_participe_por_cesantia_g42_biess.'</ValorPagadoParticipeCES>';
					    
					}
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
			$_mes_nombre_archivo = $mes_reporte;
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
			exit;
			
		
		}
		
		

		
		
		
		public function CargaInformacionG45()
		{
		    session_start();
		    
		    $G45= new G45Model();
		    $id_usuarios=$_SESSION['id_usuarios'];
		    $mes_reporte=$_POST['mes_reporte'];
		    
		    $anio_reporte=$_POST['anio_reporte'];
		    
		    
		    
		    
		    $_numero_registro;
		    $_tipo_identificacion_participe;
		    $_identificacion_participe;
		    $_numero_operacion_credito;
		    $_tipo_credito  ;
		    $_estado_operacion;
		    $_numero_operacion_anterior;
		    $_fecha_renovacion_refinanciacion_reestructuracion;
		    $_provincia;
		    $_canton;
		    $_numero_cuotas_credito;
		    $_fecha_concesion;
		    $_fecha_vencimiento;
		    $_monto_credito_concedido;
		    $_tasa_interes_nominal;
		    $_periodo_gracia;
		    $_tea  ;
		    $_periodicidad_pago;
		    $_frecuencia_revision;
		    $_garantes_garantias;
		    $_identificacion_garante_o_codeudor;
		    $_plazo;
		    $_tipo_tabla_amortizacion;
		    $_forma_cobro_credito;
		    $_estado_registro;
		    
		    
		    $i = 0;
		    
		    $columnas = "numero_registro,
                		    tipo_identificacion_participe,
                		    identificacion_participe,
                		    numero_operacion_credito,
                		    tipo_credito  ,
                		    estado_operacion,
                		    numero_operacion_anterior,
                		    fecha_renovacion_refinanciacion_reestructuracion,
                		    provincia,
                		    canton,
                		    numero_cuotas_credito,
                		    fecha_concesion,
                		    fecha_vencimiento,
                		    monto_credito_concedido,
                		    tasa_interes_nominal,
                		    periodo_gracia,
                		    tea  ,
                		    periodicidad_pago,
                		    frecuencia_revision,
                		    garantes_garantias,
                		    identificacion_garante_o_codeudor,
                		    plazo,
                		    tipo_tabla_amortizacion,
                		    forma_cobro_credito,
                		    estado_registro";
		    $tablas = "fc_biess_g45('1900-01-01' ::date , '2019-12-31' :: date )";
		    
		    $html= "";
		    
		    $resultSet=$G45->getCondicionesFunciones($columnas, $tablas);
		    
		    if(!empty($resultSet))
		    {
		        
		        
		        
		        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
		        $html.= "<thead>";
		        $html.= "<tr>";
		        $html.='<th style="text-align: center;  font-size: 12px;">Numero Registro</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tipo Identificación Participe</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Identificación Participe</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;"># Operacion</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tipo  Credito</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Estado Operacion</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;"># Operacion Anterior</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha RRR</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Provincias</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Canton  </th>';
		        $html.='<th style="text-align: center;  font-size: 12px;"># Cuota</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha Concesion</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha Venc </th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Monto Cred Conce </th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tasa Int. N</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Periodo Gracia</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">TEA</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Periocodad Pago</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Frecuencia Revision</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Garantes Garantias</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Identificacion Garante</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Plazo</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tipo Tabla Amort</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Forma Cobro</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Est. Registro</th>';
		        
		        //$html.='<th style="text-align: left;  font-size: 12px;"></th>';
		        $html.='</tr>';
		        $html.='</thead>';
		        $html.='<tbody>';
		        
		        
		        
		        
		        $i = 0;
		        foreach($resultSet as $res)
		        {
		            $i ++;
		            
		            
		            $html.='<tr>';
		            $html.='<td style="font-size: 11px;">'.$i.'</td>';
		            
		            
		            
		            
		            $html.='<td style="font-size: 11px;">'.$res->numero_registro.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tipo_identificacion_participe.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->identificacion_participe.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_operacion_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tipo_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->estado_operacion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_operacion_anterior.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_renovacion_refinanciacion_reestructuracion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->provincia.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->canton.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_cuotas_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_concesion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_vencimiento.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->monto_credito_concedido.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tasa_interes_nominal.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->periodo_gracia.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tea.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->periodicidad_pago.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->frecuencia_revision.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->garantes_garantias.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->identificacion_garante_o_codeudor.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->plazo.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tipo_tabla_amortizacion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->forma_cobro_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->estado_registro.'</td>';
		            
		            $html.='</tr>';
		            
		            
		        }
		        
		        
		        $html.='</tbody>';
		        $html.='</table>';
		        
		        
		        $html.='<div class="table-pagination pull-right">';
		        $html.='</div>';
		        
		        
		        
		        
		    }
		    /*
		     echo  "Resultado Mes Reportado " . $html;
		     die();
		     */
		    
		    $respuesta = array();
		    $respuesta['tabladatos'] =$html;
		    echo json_encode($respuesta);
		    
		    die();
		    
		    
		    
		    
		}
		
		
		
		
		
		
		public function generaG45(){
		    
		    
		    $G45= new G45Model();
		    //$id_usuarios=$_SESSION['id_usuarios'];
		    $mes_reporte=  12;//     $_POST['mes_reporte'];
		    
		    $anio_reporte= 2020; //     $_POST['anio_reporte'];
		    
		    
		    
		    $_numero_registro;
		    $_tipo_identificacion_participe;
		    $_identificacion_participe;
		    $_numero_operacion_credito;
		    $_tipo_credito  ;
		    $_estado_operacion;
		    $_numero_operacion_anterior;
		    $_fecha_renovacion_refinanciacion_reestructuracion;
		    $_provincia;
		    $_canton;
		    $_numero_cuotas_credito;
		    $_fecha_concesion;
		    $_fecha_vencimiento;
		    $_monto_credito_concedido;
		    $_tasa_interes_nominal;
		    $_periodo_gracia;
		    $_tea  ;
		    $_periodicidad_pago;
		    $_frecuencia_revision;
		    $_garantes_garantias;
		    $_identificacion_garante_o_codeudor;
		    $_plazo;
		    $_tipo_tabla_amortizacion;
		    $_forma_cobro_credito;
		    $_estado_registro;
		    
		    
		    $fecha =  "01/".$mes_reporte."/".$anio_reporte;
		    $anio_mes = $anio_reporte.'-'.$mes_reporte;
		    $aux = date('Y-m-d', strtotime("{$anio_mes} + 1 month"));
		    $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
		    $newDate_fechacorte = date("d/m/Y", strtotime($last_day));
		    
		    
		    
		    $i = 0;
		    
		    $columnas = "numero_registro,
                		    tipo_identificacion_participe,
                		    identificacion_participe,
                		    numero_operacion_credito,
                		    tipo_credito  ,
                		    estado_operacion,
                		    numero_operacion_anterior,
                		    fecha_renovacion_refinanciacion_reestructuracion,
                		    provincia,
                		    canton,
                		    numero_cuotas_credito,
                		    fecha_concesion,
                		    fecha_vencimiento,
                		    monto_credito_concedido,
                		    tasa_interes_nominal,
                		    periodo_gracia,
                		    tea  ,
                		    periodicidad_pago,
                		    frecuencia_revision,
                		    garantes_garantias,
                		    identificacion_garante_o_codeudor,
                		    plazo,
                		    tipo_tabla_amortizacion,
                		    forma_cobro_credito,
                		    estado_registro";
		    $tablas = "fc_biess_g45('$fecha' ::date , '$newDate_fechacorte' :: date )";
		    
		    $html= "";
		    
		    $resultSet=$G45->getCondicionesFunciones($columnas, $tablas);
		    $texto ='';
		    
		    if(!empty($resultSet)){
		        
		          
		        
		      
		        
		        
		        
		        //	$fecha_corte = $G41->ultimo_dia_mes_fecha($fecha);
		        $cantidad_lineas = count($resultSet)  + 1;
		        /*
		         $respuesta = array();
		         $respuesta['tabladatos'] =$newDate_fechacorte;
		         echo json_encode($respuesta);
		         
		         die();
		         */
		        
		        
		        
		        $texto .='<?xml version="1.0" encoding="UTF-8"?>';
		        $texto .= '<REGISTROS>';
		        $texto .= '<DatosEstructura>';
		        $texto .= '<CodigoEstructura>G45</CodigoEstructura>';
		        $texto .= '<CodigoEntidad>17</CodigoEntidad>';
		        $texto .= '<FechaCorte>'.$newDate_fechacorte.'</FechaCorte>';
		        $texto .= '<TotalRegistros>'.$cantidad_lineas.'</TotalRegistros>';
		      
		        $texto .= '</DatosEstructura>';
		        $texto .= '<Detalle>';
		        
		        foreach($resultSet as $res)
		        {
		        
		            if ($res->identificacion_participe == '1704259470' || $res->identificacion_participe == '1710230531' )
		            {
		                
		            }
		            else 
		            {
		            $i++;
		            
		              $_numero_registro                     = $i;
		            
		                
    		            $_tipo_identificacion_participe       = $res->tipo_identificacion_participe;
    		            $_identificacion_participe            = $res->identificacion_participe;
    		            $_numero_operacion_credito            = 'CAP' . $res->numero_operacion_credito;
    		            $_tipo_credito                        = $res->tipo_credito;
    		            $_estado_operacion                    = $res->estado_operacion;
    		            if ($res->numero_operacion_anterior !="")
    		            {
    		                $_numero_operacion_anterior           = 'CAP' . $res->numero_operacion_anterior;
    		                
    		            }
    		            else 
    		            {
    		                $_numero_operacion_anterior           = 0;
    		                
    		            }
    		            
    		            if ($_estado_operacion == 'ORIGINAL')
    		            {
    		                $_fecha_renovacion_refinanciacion_reestructuracion = '';
    		                
    		            }
    		            else
    		            {
    		                $_fecha_renovacion_refinanciacion_reestructuracion = date("d/m/Y", strtotime($res->fecha_renovacion_refinanciacion_reestructuracion));
    		                
    		            }
    		          
    		            
    		            $_provincia                           = $res->provincia;
    		            $_canton                              = $res->canton;
    		            
    		            
    		            
    		            switch ($_provincia) {
    		                
    		                case '':
    		                    $_provincia = '17';
    		                    $_canton  = '1701';
    		                    break;
    		                    
    		                    
    		                case '01':
    		                    $_canton  = '0101';
    		                    break;
    		                case '02':
    		                    $_canton  = '0201';
    		                    break;
    		                case '03':
    		                    $_canton  = '0301';
    		                    break;
    		                case '04':
    		                    $_canton  = '0401';
    		                    break;
    		                case '05':
    		                    $_canton  = '0501';
    		                    break;
    		                case '06':
    		                    $_canton  = '0601';
    		                    break;
    		                
    		                    break;
    		                case '07':
    		                    $_canton  = '0701';
    		                    break;
    		                case '08':
    		                    $_canton  = '0801';
    		                    break;
    		                case '09':
    		                    $_canton  = '0901';
    		                    break;
    		                case '10':
    		                    $_canton  = '1001';
    		                    break;
    		                case '11':
    		                    $_canton  = '1101';
    		                    break;
    		                case '12':
    		                    $_canton  = '1201';
    		                    break;
    		                case '13':
    		                    $_canton  = '1301';
    		                    break;
    		                case '14':
    		                    $_canton  = '1401';
    		                    break;
    		                case '15':
    		                    $_canton  = '1501';
    		                    break;
    		                case '16':
    		                    $_canton  = '1601';
    		                    break;
    		                case '17':
    		                    $_canton  = '1701';
    		                    break;
    		                case '18':
    		                    $_canton  = '1801';
    		                    break;
    		                case '19':
    		                    $_canton  = '1901';
    		                    break;
    		                case '20':
    		                    $_canton  = '2001';
    		                    break;
                            case '21':
    		                    $_canton  = '2101';
    		                    break;
    		                case '22':
    		                    $_canton  = '2201';
    		                    break;
    		                case '23':
    		                    $_canton  = '2301';
    		                    break;
    		                    
    		                case '24':
    		                    $_canton  = '2401';
    		                    break;
    		                case '25':
    		                    $_canton  = '2501';
    		                    break;
    		                    
    		            }
    		            
    		            
    		            
    		            
    		            
    		            $_numero_cuotas_credito               = $res->numero_cuotas_credito;
    		            $_fecha_concesion                     = date("d/m/Y", strtotime($res->fecha_concesion));    
    		            $_fecha_vencimiento                   = date("d/m/Y", strtotime($res->fecha_vencimiento));    
    		            $_monto_credito_concedido             = $res->monto_credito_concedido;
    		            $_tasa_interes_nominal                = $res->tasa_interes_nominal;
    		            $_periodo_gracia                      = $res->periodo_gracia;
    		            $_tea                                 = $res->tea;
    		            $_periodicidad_pago                   = $res->periodicidad_pago;
    		            $_frecuencia_revision                 = $res->frecuencia_revision;
    		            $_garantes_garantias                  = $res->garantes_garantias;
    		            $_identificacion_garante_o_codeudor   = $res->identificacion_garante_o_codeudor;
    		            $_plazo                               = $res->plazo;
    		            $_tipo_tabla_amortizacion             = $res->tipo_tabla_amortizacion;
    		            $_forma_cobro_credito                 = $res->forma_cobro_credito;
    		            $_estado_registro                     = $res->estado_registro;
    		            
    		            
    		            
    		            $texto .= '<Registro NumeroRegistro="'. $i.'">';
    		            
    		            
    		           
    		            
    		            $texto .= ' <TipoIdentificacionParticipe>'.$_tipo_identificacion_participe.'</TipoIdentificacionParticipe> ';
    		            $texto .= '<IdentificacionParticipe>'.$_identificacion_participe.'</IdentificacionParticipe>';
    		            $texto .= '<NumeroOperacionCredito>'.$_numero_operacion_credito.'</NumeroOperacionCredito>';
    		            $texto .= '<TipoCredito>'.$_tipo_credito.'</TipoCredito>';
    		            $texto .= '<EstadoOperacion>'.$_estado_operacion.'</EstadoOperacion>';
    		            $texto .= '<NumeroOperacionAnterior>'.$_numero_operacion_anterior.'</NumeroOperacionAnterior>';
    		            $texto .= '<FechaRenovacion>'.$_fecha_renovacion_refinanciacion_reestructuracion.'</FechaRenovacion>';
    		            $texto .= '<Provincia>'.$_provincia.'</Provincia>';
    		            $texto .= '<Canton>'.$_canton.'</Canton>';
    		           
    		            $texto .= '<NumeroCuotasCredito>'.$_numero_cuotas_credito.'</NumeroCuotasCredito>';
    		            $texto .= '<FechaConcesion>'.$_fecha_concesion.'</FechaConcesion>';
    		            $texto .= '<FechaVencimiento>'.$_fecha_vencimiento.'</FechaVencimiento>';
    		            $texto .= '<ValorConcedido>'.$_monto_credito_concedido.'</ValorConcedido>';
    		            $texto .= '<TasaInterezNominal>'.$_tasa_interes_nominal.'</TasaInterezNominal>';
    		           
    		            $texto .= '<PeriodoGracia>'.$_periodo_gracia.'</PeriodoGracia>';
    		            $texto .= '<Tea>'.$_tea.'</Tea>';
    		            $texto .= '<PeriodicidadPago>'.$_periodicidad_pago.'</PeriodicidadPago>';
    		            $texto .= '<FrecuenciaRevision>'.$_frecuencia_revision.'</FrecuenciaRevision>';
    		            $texto .= '<GarantesGarantias>'.$_garantes_garantias.'</GarantesGarantias>';
    		            $texto .= '<IdentificacionGarante>'.$_identificacion_garante_o_codeudor.'</IdentificacionGarante>';
    		            $texto .= '<Plazo>'.$_plazo.'</Plazo>';
    		            $texto .= '<TipoTablaAmortizacion>'.$_tipo_tabla_amortizacion.'</TipoTablaAmortizacion>';
    		            $texto .= '<FormaCobroCredito>'.$_forma_cobro_credito.'</FormaCobroCredito>';
    		            $texto .= '<EstadoRegistro>'.$_estado_registro.'</EstadoRegistro>';
    		         
    		          
    		            $texto .= '</Registro>';
		            }
		            
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
		    $_mes_nombre_archivo = $mes_reporte;
		    if (strlen($mes_reporte1) == 1)
		    {
		        $_mes_nombre_archivo = '0' .$mes_reporte1;
		    }
		    
		    $nombre_archivo = "17-".$anio_reporte.'-'.$_mes_nombre_archivo."_G45". $newDate_fechaHoy .".xml";
		    
		    //CB-AAAA-MM-G41ddmmaaaa.xml
		    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOS_GENERADOS\\ESTRUCTURAS\\BIESS\\G45\\";
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

		
		
		
		
		
		public function CargaInformacionG46()
		{
		    session_start();
		    
		    $G46= new G45Model();
		    $id_usuarios=$_SESSION['id_usuarios'];
		    $mes_reporte=$_POST['mes_reporte'];
		    
		    $anio_reporte=$_POST['anio_reporte'];
		    
		    
		    $_numero_registro ;
		    $_tipo_identificacion_participe ;
		    $_identificacion_participe;
		    $_numero_operacion_de_credito;
		    $_estado_operacion;
		    $_estado_del_prestamo;
		    $_tasa_interes;
		    $_fecha_vencimiento_cuota;
		    $_valor_del_credito_total;
		    $_valor_cuota_mensual_del_capital;
		    $_valor_seguro_desgravamen_mensual;
		    $_valor_interes_mensual;
		    $_fecha_pago;
		    $_numero_cuota_pagada;
		    $_numero_cuota_pendientes_de_pago;
		    $_valor_de_la_cuota_pagado_por_el_participe_mensual;
		    $_valor_de_capital_pagado_por_el_participe_mensual;
		    $_valor_abono_prestamo;
		    $_saldo_de_capital;
		    $_saldo_del_interes;
		    $_monto_total_pagado;
		    $_monto_total_abonado;
		    $_intereses_por_cobrar;
		    $_estado_del_vencimiento_o_liquidacion;
		    $_dias_morosidad;
		    $_cuotas_vencidas;
		    $_interes_sobre_mora;
		    $_monto_vencido_del_credito;
		    $_valor_vencido;
		    $_valor_no_devenga_interes;
		    $_valor_demanda_judicial;
		    $_monto_cartera_castigada;
		    $_provision;
		    $_provision_acumulada;
		    $_fecha_de_cancelacion;
		    $_forma_de_cancelacion;
		    
		    $i = 0;
		    
		    $columnas = "numero_registro
                            tipo_identificacion_participe
                            identificacion_participe
                            numero_operacion_de_credito
                            estado_operacion
                            estado_del_prestamo
                            tasa_interes
                            fecha_vencimiento_cuota
                            valor_del_credito_total
                            valor_cuota_mensual_del_capital
                            valor_seguro_desgravamen_mensual
                            valor_interes_mensual
                            fecha_pago
                            numero_cuota_pagada
                            numero_cuota_pendientes_de_pago
                            valor_de_la_cuota_pagado_por_el_participe_mensual
                            valor_de_capital_pagado_por_el_participe_mensual
                            valor_abono_prestamo
                            saldo_de_capital
                            saldo_del_interes
                            monto_total_pagado
                            monto_total_abonado
                            intereses_por_cobrar
                            estado_del_vencimiento_o_liquidacion
                            dias_morosidad
                            cuotas_vencidas
                            interes_sobre_mora
                            monto_vencido_del_credito
                            valor_vencido
                            valor_no_devenga_interes
                            valor_demanda_judicial
                            monto_cartera_castigada
                            provision
                            provision_acumulada
                            fecha_de_cancelacion
                            forma_de_cancelacion";
		    $tablas = "fc_biess_g46('1900-01-01' ::date , '2019-12-31' :: date )";
		    
		    $html= "";
		    
		    $resultSet=$G46->getCondicionesFunciones($columnas, $tablas);
		    
		    if(!empty($resultSet))
		    {
		        
		        
		        
		        $html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
		        $html.= "<thead>";
		        $html.= "<tr>";
		 
		        $html.='<th style="text-align: center;  font-size: 12px;">Numero Registro</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tipo Identificación Participe</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Identificación Participe</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;"># Operacion</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Estado Operacion</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Estado del Prestamo</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Tasa Interes</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha Vencimiento</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor Credito</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor Cuota</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Seguro Desg</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Interes Mensual</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha Pago </th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Num Cuota Pagada</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Num Cuota Pend</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor Cuota Pagada Men</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor Capital pagadp Men</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor Abono</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Saldo Capital</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Saldo Interes</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Monto Total Pagado</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Monto total Abonado</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Interes por cobrar</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Forma Cobro</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Estado del Vencimiento</th>';
		   
		        $html.='<th style="text-align: center;  font-size: 12px;">Dias Morosidad</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Cuotas Vencidas</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Interes sobre Mora</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Monto Vencido</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor no devenga Int</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Valor demanda judicial</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">monto cartea castigada</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Provision</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Provision Acumulada</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Fecha Cancelada</th>';
		        $html.='<th style="text-align: center;  font-size: 12px;">Forma Canclacion</th>';
		        
		        
		        
		        
		        
		        //$html.='<th style="text-align: left;  font-size: 12px;"></th>';
		        $html.='</tr>';
		        $html.='</thead>';
		        $html.='<tbody>';
		        
		        
		        
		        
		        $i = 0;
		        foreach($resultSet as $res)
		        {
		            $i ++;
		            
		            
		            $html.='<tr>';
		            $html.='<td style="font-size: 11px;">'.$i.'</td>';
		            
		            $html.='<td style="font-size: 11px;">'.$res->numero_registro.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tipo_identificacion_participe.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->identificacion_participe.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_operacion_de_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->estado_operacion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->estado_del_prestamo.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->tasa_interes.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_vencimiento_cuota.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_del_credito_total.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_seguro_desgravamen_mensual.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_interes_mensual.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_pago.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_cuota_pagada.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->numero_cuota_pendientes_de_pago.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_de_la_cuota_pagado_por_el_participe_mensual.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_de_capital_pagado_por_el_participe_mensual.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_abono_prestamo.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->saldo_del_interes.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->monto_total_pagado.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->monto_total_abonado.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->intereses_por_cobrar.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->estado_del_vencimiento_o_liquidacion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->dias_morosidad.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->cuotas_vencidas.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->interes_sobre_mora.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->monto_vencido_del_credito.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_vencido.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_no_devenga_interes.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->valor_demanda_judicial.'</td>';
		            
		            $html.='<td style="font-size: 11px;">'.$res->monto_cartera_castigada.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->provision.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->provision_acumulada.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->fecha_de_cancelacion.'</td>';
		            $html.='<td style="font-size: 11px;">'.$res->forma_de_cancelacion.'</td>';
		            
		            
		            $html.='</tr>';
		            
		            
		        }
		        
		        
		        $html.='</tbody>';
		        $html.='</table>';
		        
		        
		        $html.='<div class="table-pagination pull-right">';
		        $html.='</div>';
		        
		        
		        
		        
		    }
		    /*
		     echo  "Resultado Mes Reportado " . $html;
		     die();
		     */
		    
		    $respuesta = array();
		    $respuesta['tabladatos'] =$html;
		    echo json_encode($respuesta);
		    
		    die();
		    
		    
		    
		    
		}
		
		
		
		
		
		
		public function generaG46(){
		    
		    
		    $G45= new G45Model();
		    //$id_usuarios=$_SESSION['id_usuarios'];
		    $mes_reporte=  12;//     $_POST['mes_reporte'];
		    
		    $anio_reporte= 2020; //     $_POST['anio_reporte'];
		    
		    
		    
		    $_numero_registro ;
		    $_tipo_identificacion_participe ;
		    $_identificacion_participe;
		    $_numero_operacion_de_credito;
		    $_estado_operacion;
		    $_estado_del_prestamo;
		    $_tasa_interes;
		    $_fecha_vencimiento_cuota;
		    $_valor_del_credito_total;
		    $_valor_cuota_mensual_del_capital;
		    $_valor_seguro_desgravamen_mensual;
		    $_valor_interes_mensual;
		    $_fecha_pago;
		    $_numero_cuota_pagada;
		    $_numero_cuota_pendientes_de_pago;
		    $_valor_de_la_cuota_pagado_por_el_participe_mensual;
		    $_valor_de_capital_pagado_por_el_participe_mensual;
		    $_valor_abono_prestamo;
		    $_saldo_de_capital;
		    $_saldo_del_interes;
		    $_monto_total_pagado;
		    $_monto_total_abonado;
		    $_intereses_por_cobrar;
		    $_estado_del_vencimiento_o_liquidacion;
		    $_dias_morosidad;
		    $_cuotas_vencidas;
		    $_interes_sobre_mora;
		    $_monto_vencido_del_credito;
		    $_valor_vencido;
		    $_valor_no_devenga_interes;
		    $_valor_demanda_judicial;
		    $_monto_cartera_castigada;
		    $_provision;
		    $_provision_acumulada;
		    $_fecha_de_cancelacion;
		    $_forma_de_cancelacion;
		    
		    $i = 0;
		    
		    $columnas = "numero_registro
                            tipo_identificacion_participe
                            identificacion_participe
                            numero_operacion_de_credito
                            estado_operacion
                            estado_del_prestamo
                            tasa_interes
                            fecha_vencimiento_cuota
                            valor_del_credito_total
                            valor_cuota_mensual_del_capital
                            valor_seguro_desgravamen_mensual
                            valor_interes_mensual
                            fecha_pago
                            numero_cuota_pagada
                            numero_cuota_pendientes_de_pago
                            valor_de_la_cuota_pagado_por_el_participe_mensual
                            valor_de_capital_pagado_por_el_participe_mensual
                            valor_abono_prestamo
                            saldo_de_capital
                            saldo_del_interes
                            monto_total_pagado
                            monto_total_abonado
                            intereses_por_cobrar
                            estado_del_vencimiento_o_liquidacion
                            dias_morosidad
                            cuotas_vencidas
                            interes_sobre_mora
                            monto_vencido_del_credito
                            valor_vencido
                            valor_no_devenga_interes
                            valor_demanda_judicial
                            monto_cartera_castigada
                            provision
                            provision_acumulada
                            fecha_de_cancelacion
                            forma_de_cancelacion";
		    $tablas = "fc_biess_g46('1900-01-01' ::date , '2019-12-31' :: date )";
		    
		    
		    $html= "";
		    
		    $resultSet=$G45->getCondicionesFunciones($columnas, $tablas);
		    $texto ='';
		    
		    if(!empty($resultSet)){
		        
		        
		        
		        
		        
		        
		        
		        //	$fecha_corte = $G41->ultimo_dia_mes_fecha($fecha);
		        $cantidad_lineas = count($resultSet)  + 1;
		        /*
		         $respuesta = array();
		         $respuesta['tabladatos'] =$newDate_fechacorte;
		         echo json_encode($respuesta);
		         
		         die();
		         */
		        
		        
		        
		        $texto .='<?xml version="1.0" encoding="UTF-8"?>';
		        $texto .= '<REGISTROS>';
		        $texto .= '<DatosEstructura>';
		        $texto .= '<CodigoEstructura>G45</CodigoEstructura>';
		        $texto .= '<CodigoEntidad>17</CodigoEntidad>';
		        $texto .= '<FechaCorte>'.$newDate_fechacorte.'</FechaCorte>';
		        $texto .= '<TotalRegistros>'.$cantidad_lineas.'</TotalRegistros>';
		        
		        $texto .= '</DatosEstructura>';
		        $texto .= '<Detalle>';
		        
		        foreach($resultSet as $res)
		        {
		            
		            if ($res->identificacion_participe == '1704259470' || $res->identificacion_participe == '1710230531' )
		            {
		                
		            }
		            else
		            {
		                $i++;
		                
		                $_numero_registro                     = $i;
		                //$_numero_registro                                 =  $res->numero_registro;
		                $_tipo_identificacion_participe                   =  $res->tipo_identificacion_participe;
		                $_identificacion_participe                        =  $res->identificacion_participe;
		                $_numero_operacion_de_credito                     =  $res->numero_operacion_de_credito;
		                $_estado_operacion                                =  $res->estado_operacion;
		                $_estado_del_prestamo                             =  $res->estado_del_prestamo;
		                $_tasa_interes                                    =  $res->tasa_interes;
		                $_fecha_vencimiento_cuota                         =  date("d/m/Y", strtotime($res->fecha_vencimiento_cuota));
		                $_valor_del_credito_total                         =  $res->valor_del_credito_total;
		                $_valor_cuota_mensual_del_capital                 =  $res->valor_cuota_mensual_del_capital;
		                $_valor_seguro_desgravamen_mensual                =  $res->valor_seguro_desgravamen_mensual;
		                $_valor_interes_mensual                           =  $res->valor_interes_mensual;
		                $_fecha_pago                                      =  date("d/m/Y", strtotime($res->fecha_pago));
		                $_numero_cuota_pagada                             =  $res->numero_cuota_pagada;
		                $_numero_cuota_pendientes_de_pago                     =  $res->numero_cuota_pendientes_de_pago;
		                $_valor_de_la_cuota_pagado_por_el_participe_mensual   =  $res->valor_de_la_cuota_pagado_por_el_participe_mensual;
		                $_valor_de_capital_pagado_por_el_participe_mensual    =  $res->valor_de_capital_pagado_por_el_participe_mensual;
		                $_valor_abono_prestamo                                =  $res->valor_abono_prestamo;
		                $_saldo_de_capital                                    =  $res->saldo_de_capital;
		                $_saldo_del_interes                                   =  $res->saldo_del_interes;
		                $_monto_total_pagado                                  =  $res->monto_total_pagado;
		                $_monto_total_abonado                                 =  $res->monto_total_abonado;
		                $_intereses_por_cobrar                                =  $res->intereses_por_cobrar;
		                $_estado_del_vencimiento_o_liquidacion                =  $res->estado_del_vencimiento_o_liquidacion;
		                $_dias_morosidad                                      =  $res->dias_morosidad;
		                $_cuotas_vencidas                                     =  $res->cuotas_vencidas;
		                $_interes_sobre_mora                                  =  $res->interes_sobre_mora;
		                $_monto_vencido_del_credito                           =  $res->monto_vencido_del_credito;
		                $_valor_vencido                                       =  $res->valor_vencido;
		                $_valor_no_devenga_interes                            =  $res->valor_no_devenga_interes;
		                $_valor_demanda_judicial                              =  $res->valor_demanda_judicial;
		                $_monto_cartera_castigada                             =  $res->monto_cartera_castigada;
		                $_provision                                           =  $res->provision;
		                $_provision_acumulada                                 =  $res->provision_acumulada;
		                $_fecha_de_cancelacion                                =  date("d/m/Y", strtotime($res->fecha_de_cancelacion));
		                $_forma_de_cancelacion                                =  date("d/m/Y", strtotime($res->forma_de_cancelacion));
		                
		                $texto .= '<Registro NumeroRegistro="'. $i.'">';
		                $texto .= '<TipoIdentificacionParticipe>'.$_tipo_identificacion_participe.'</TipoIdentificacionParticipe>';
		                $texto .= '<IdentificacionParticipe>'.$_identificacion_participe.'</IdentificacionParticipe>';
		                $texto .= '<NumeroOpeCred>OP-'.$_numero_operacion_de_credito.'</NumeroOpeCred>';
		                $texto .= '<EstadoOper>'.$_estado_operacion.'</EstadoOper>';
		                $texto .= '<EstadoPresta>'.$_estado_del_prestamo.'</EstadoPresta>';
		                $texto .= '<TasaInteres>'.$_tasa_interes.'</TasaInteres>';
		                $texto .= '<FechaVencCuota>'.$_fecha_vencimiento_cuota.'</FechaVencCuota>';
		                $texto .= '<ValorCreditoTotal>'.$_valor_del_credito_total.'</ValorCreditoTotal>';
		                $texto .= '<ValorCuotaMenCap>'.$_valor_cuota_mensual_del_capital.'</ValorCuotaMenCap>';
		                $texto .= '<ValorSegDesgravamenMen>'.$_valor_seguro_desgravamen_mensual.'</ValorSegDesgravamenMen>';
		                $texto .= '<ValorInteresmensual>'.$_valor_interes_mensual.'</ValorInteresmensual>';
		                $texto .= '<FechaPago>'.$_fecha_pago.'/FechaPago>';
		                $texto .= '<CuotaPagada>'.$_numero_cuota_pagada.'</CuotaPagada>';
		                $texto .= ' <CuotapendientesPago>'.$_numero_cuota_pendientes_de_pago.'</CuotapendientesPago>';
		                $texto .= '<ValorCuotaPagPM>'.$_valor_de_la_cuota_pagado_por_el_participe_mensual.'</ValorCuotaPagPM>';
		                $texto .= '<ValorcapPagPM>'.$_valor_de_capital_pagado_por_el_participe_mensual.'</ValorcapPagPM>';
		                $texto .= '<ValorAbonoPrestamo>'.$_valor_abono_prestamo.'</ValorAbonoPrestamo>';
		                $texto .= '<SaldoCapital>'.$_saldo_de_capital.'</SaldoCapital>';
		                $texto .= '<Saldointeres>'.$_saldo_del_interes.'</Saldointeres>';
		                $texto .= '<MontoTotPagado>'.$_monto_total_pagado.'</MontoTotPagado>';
		                $texto .= '<MontoTotAbonado>'.$_monto_total_abonado.'</MontoTotAbonado>';
		                $texto .= '<Interesescobrar>'.$_intereses_por_cobrar.'</Interesescobrar>';
		                $texto .= '<EstadoVencLiqui>'.$_estado_del_vencimiento_o_liquidacion.'</EstadoVencLiqui>';
		                $texto .= '<DiasMorosidad>'.$_dias_morosidad.'</DiasMorosidad> ';
		                $texto .= ' <Cuotasvencidas>'.$_cuotas_vencidas.'</Cuotasvencidas> ';
		                $texto .= ' <InteresMora>'.$_interes_sobre_mora.'</InteresMora>';
		                $texto .= ' <MontoVencCredito>'.$_monto_vencido_del_credito.'</MontoVencCredito>';
		                $texto .= ' <ValorVencido>'.$_valor_vencido.'</ValorVencido>';
		                $texto .= ' <ValorNoDevengaInteres>'.$_valor_no_devenga_interes.'</ValorNoDevengaInteres>';
		                $texto .= ' <ValorDemandaJudicial>'.$_valor_demanda_judicial.'</ValorDemandaJudicial>';
		                $texto .= ' <Provision>'.$_provision.'</Provision>';
		                $texto .= ' <ProvisionAcum>'.$_provision_acumulada.'</ProvisionAcum>';
		                $texto .= ' <Fechacancelacion>'.$_fecha_de_cancelacion.'</Fechacancelacion> ';
		                $texto .= ' <Formacancelacion>'.$_forma_de_cancelacion.'</Formacancelacion> ';
		                
		                $texto .= '</Registro>';
		            }
		            
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
		    $_mes_nombre_archivo = $mes_reporte;
		    if (strlen($mes_reporte1) == 1)
		    {
		        $_mes_nombre_archivo = '0' .$mes_reporte1;
		    }
		    
		    $nombre_archivo = "17-".$anio_reporte.'-'.$_mes_nombre_archivo."_G46". $newDate_fechaHoy .".xml";
		    
		    //CB-AAAA-MM-G41ddmmaaaa.xml
		    $ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOS_GENERADOS\\ESTRUCTURAS\\BIESS\\G46\\";
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