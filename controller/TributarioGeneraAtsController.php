<?php

class TributarioGeneraAtsController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
	
     	$estados=new EstadoModel();     	
					
     	$resultEdit = "";
	
		session_start();
        
	
		if (isset(  $_SESSION['nombre_usuarios']) )
		{

			$nombre_controladores = "ProcesosMayorizacion";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $estados->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
			    $colModulos = " modulos.id_modulos, modulos.nombre_modulos";
			    $tabModulos = " public.modulos
        			    INNER JOIN public.core_tipo_procesos
        			    ON core_tipo_procesos.id_modulos = modulos.id_modulos ";
			    $wheModulos = " 1 = 1 AND modulos.id_modulos = 9 ";
			    $gruModulos = " modulos.id_modulos, modulos.nombre_modulos ";
			    $idModulos = " modulos.nombre_modulos ";
			    
			    
			    $rsModulos = $estados->getCondiciones_grupo($colModulos,$tabModulos,$wheModulos,$gruModulos,$idModulos);
			    
				
				
				$this->view_tributario("GeneraAts",array(
				    "resultEdit" =>$resultEdit,"rsModulos"=>$rsModulos
			
				));
		
				
				
			}
			else
			{
			    $this->view_Contable("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Grupos"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	public function consultaTipoProcesos(){
		 
		$modulos = new ModulosModel();
		 
		$_id_modulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : 0;
		$columnas = "id_tipo_procesos, nombre_tipo_procesos";
		$tablas = "core_tipo_procesos";
		$where = " diarios_tipo_procesos = 'TRUE' AND id_modulos = $_id_modulos ";
		$id = "id_modulos";
		 
		$rsTipoProceso = $modulos->getCondiciones($columnas, $tablas, $where, $id);
		$cantidad = count($rsTipoProceso);
		echo json_encode(array('cantidad'=>$cantidad, 'data'=>$rsTipoProceso));
	}
	
	
	
	public function totalesAts(){
		session_start();
		$id_usuarios = $_SESSION['id_usuarios'];
		$usuario_usuarios = $_SESSION['usuario_usuarios'];
		$Participes = new ParticipesModel();
		$idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
		$anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
		$mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
		$tipoPeticion = (isset($_POST['peticion'])) ? $_POST['peticion'] : "";
		$idModulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : null;
		$mesperiodofiscal = $mesDiario;
		if (strlen($mesDiario) == 1 )
		{
			
			$mesperiodofiscal = '0' .$mesDiario;
		}
		else {
			$mesperiodofiscal = $mesDiario;
		}
		//$mes_letras = date('n')
		
		if(empty($idTipoProcesos)){
			echo '<message>Datos no recibidos<message>';
			return;
		}
		//validar los campos recibidos para generar diario
		$arrayTabla = array();
		$cantidad = 0;
		$columnas = 'tri_retenciones_detalle.impuesto_codigo, 
						  tri_retenciones_detalle.impuesto_codigoretencion,
						  SUM(tri_retenciones_detalle.impuestos_baseimponible) AS impuestos_baseimponible, 
						ROUND(  SUM(tri_retenciones_detalle.impuestos_porcentajeretener)/COUNT(tri_retenciones_detalle.impuestos_porcentajeretener)) AS impuesto_porcentaje , 
						  SUM(tri_retenciones_detalle.impuestos_valorretenido) AS impuestos_valorretenido';
		$tablas = " public.tri_retenciones, 
  						public.tri_retenciones_detalle";
		$where = "tri_retenciones_detalle.id_tri_retenciones = tri_retenciones.id_tri_retenciones
					  AND SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,1,2) = '$mesperiodofiscal'
					  AND SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,4,4) = '$anioDiario'  
					  GROUP BY tri_retenciones_detalle.impuesto_codigo, tri_retenciones_detalle.impuesto_codigoretencion";
			
		$id = "tri_retenciones_detalle.impuesto_codigo,tri_retenciones_detalle.impuesto_codigoretencion";
		$rsHistorial = $Participes->getCondiciones($columnas, $tablas, $where, $id);
		$respuesta = array();
		$respuesta['tabladatos'] =  $this->graficaXML($rsHistorial);
		//$respuesta['tabladatos'] = "PRUEBA";
		echo json_encode($respuesta);
	//	echo json_encode($where);
		die();
			
		
		
	}
	

	public function generaAts(){
		
	
		session_start();
		$id_usuarios = $_SESSION['id_usuarios'];
		$usuario_usuarios = $_SESSION['usuario_usuarios'];
		$Participes = new ParticipesModel();
		$idTipoProcesos = (isset($_POST['id_tipo_procesos'])) ? $_POST['id_tipo_procesos'] : "";
		$anioDiario = (isset($_POST['anio_procesos'])) ? $_POST['anio_procesos'] : "";
		$mesDiario = (isset($_POST['mes_procesos'])) ? $_POST['mes_procesos'] : "";
		$tipoPeticion = (isset($_POST['peticion'])) ? $_POST['peticion'] : "";
		$idModulos = (isset($_POST['id_modulos'])) ? $_POST['id_modulos'] : null;
		$mesperiodofiscal = $mesDiario;
		if (strlen($mesDiario) == 1 )
		{
				
			$mesperiodofiscal = '0' .$mesDiario;
		}
		else {
			$mesperiodofiscal = $mesDiario;
		}
		//$mes_letras = date('n')
	
		if(empty($idTipoProcesos)){
			echo '<message>Datos no recibidos<message>';
			return;
		}
		//validar los campos recibidos para generar diario
		$arrayTabla = array();
		$cantidad = 0;
		$columnas = " tri_retenciones.id_tri_retenciones, 
				tri_retenciones.infotributaria_ambiente, 
				tri_retenciones.infotributaria_tipoemision, 
				tri_retenciones.infotributaria_razonsocial,
				tri_retenciones.infotributaria_nombrecomercial, 
				tri_retenciones.infotributaria_ruc, 
				tri_retenciones.infotributaria_claveacceso,
				tri_retenciones.infotributaria_coddoc, 
				tri_retenciones.infotributaria_ptoemi, 
				tri_retenciones.infotributaria_estab, 
				tri_retenciones.infotributaria_secuencial,
				tri_retenciones.infotributaria_dirmatriz,
				tri_retenciones.infocompretencion_fechaemision, 
				tri_retenciones.infocompretencion_direstablecimiento,
				tri_retenciones.infocompretencion_contribuyenteespecial,
				tri_retenciones.infocompretencion_obligadocontabilidad,
				tri_retenciones.infocompretencion_tipoidentificacionsujetoretenido, 
				tri_retenciones.infocompretencion_razonsocialsujetoretenido,
				tri_retenciones.infocompretencion_identificacionsujetoretenido,
				tri_retenciones.infocompretencion_periodofiscal, 
				tri_retenciones.infoadicional_campoadicional,
				tri_retenciones.infoadicional_campoadicional_dos, 
				tri_retenciones.infoadicional_campoadicional_tres,
				tri_retenciones.creado, 
				tri_retenciones.modificado, 
				tri_retenciones.enviado_correo_electronico, 
				tri_retenciones.fecha_autorizacion,
				substring(tes_cuentas_pagar.numero_documento_cuentas_pagar from 1 for 3) AS  estabretencion_uno, 
  				substring(tes_cuentas_pagar.numero_documento_cuentas_pagar from 5 for 3) AS  ptoemiretencion_uno, 
  				substring(tes_cuentas_pagar.numero_documento_cuentas_pagar from 9 for 10) AS  secretencion_uno, 
  				tes_cuentas_pagar.fecha_cuentas_pagar, 
  				tes_cuentas_pagar.numero_autorizacion_cuentas_pagar
				";
		$tablas = " public.tri_retenciones,
					public.tes_cuentas_pagar";
		$where = "SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,1,2) = '$mesperiodofiscal'
		AND SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,4,4) = '$anioDiario' 
		AND tes_cuentas_pagar.id_lote = tri_retenciones.id_lote ";
			
		$id = "tri_retenciones.creado";
		
		
		$columnas_detalle = "  id_tri_retenciones_detalle, 
				id_tri_retenciones, 
				impuesto_codigo, 
				impuesto_codigoretencion, 
				impuestos_baseimponible, 
				impuestos_porcentajeretener, 
				impuestos_valorretenido, 
				impuestos_coddocsustento, 
				impuestos_numdocsustento, 
				impuesto_fechaemisiondocsustento, 
				impuesto_codigo_dos, 
				creado, 
				modificado";
		$tablas_detalle = " public.tri_retenciones_detalle";
		
		$id_detalle = "creado";
		
		
		
		$rsHistorial = $Participes->getCondiciones($columnas, $tablas, $where, $id);
		//$respuesta = array();
		//$respuesta['tabladatos'] =  $this->graficaXML($rsHistorial);
		//$respuesta['tabladatos'] = "PRUEBA";
		//echo json_encode($respuesta);
		//	echo json_encode($where);
		///die();
			
	
		///VARIABLES DEL DETALLE
		
		$_baseImpGrav = "";
		$_montoIva = "";
		
		$_valRetBien10 ="0.00";
		$_valRetServ20="0.00";
		$_valorRetBienes="0.00";
		$_valRetServ50="0.00";
		$_valorRetServicios="0.00";
		$_valRetServ100="0.00";
		
		$_codRetAir ="0.00";
		$_baseImpAir = "0.00";
		$_porcentajeAir ="0.00";
		$_valRetAir = "0.00";
		
		
		////genera el xml
		
		$texto="";
		 
		
			 
		if(!empty($rsHistorial)){
			 
			 
			$texto .='<?xml version="1.0" encoding="UTF-8"?>';
			$texto .= '<iva>';
			$texto .= '<TipoIDInformante>R</TipoIDInformante>';
			$texto .= '<IdInformante>'.'1791700376001'.'</IdInformante>';
			$texto .= '<razonSocial>'.'FONDO COMPLEMENTARIO PREVISIONAL CERRADO DE CESANTIA DE SERVIDORES Y TRABAJADORES PUBLICOS DE FUERZAS ARMADAS-CAPREMCI'.'</razonSocial>';
			$texto .= '<Anio>'.$anioDiario.'</Anio>';
			$texto .= '<Mes>'.$mesperiodofiscal.'</Mes>';
			$texto .= '<totalVentas>'.'0.00'.'</totalVentas>';
			$texto .= '<codigoOperativo>'.'IVA'.'</codigoOperativo>';
			 
			$texto .= '<compras>';
			 
			echo json_encode("Cabeza");
				
			
			
			foreach ($rsHistorial as $res){
				 
				$texto .= '<detalleCompras>';
				 
				$texto .= '<codSustento>'.'01'.'</codSustento>';
				$texto .= '<tpIdProv>'.$res->infocompretencion_tipoidentificacionsujetoretenido.'</tpIdProv>';
				$texto .= '<idProv>'.$res->infocompretencion_identificacionsujetoretenido.'</idProv>';
				$texto .= '<tipoComprobante>'.'19'.'</tipoComprobante>';
				if ( $res->infocompretencion_tipoidentificacionsujetoretenido == "01" || $res->infocompretencion_tipoidentificacionsujetoretenido == "02" || $res->infocompretencion_tipoidentificacionsujetoretenido == "03")
				{
					$texto .= '<parteRel>'.'NO'.'</parteRel>';
					
				}	
				else
				{
					$texto .= '<parteRel>'.'SI'.'</parteRel>';
				}
				//$_establecimiento = ;
				$originalDate_fechaemision = $res->infocompretencion_fechaemision;
				$newDate_fechaemision = date("d/m/Y", strtotime($originalDate_fechaemision));
				$texto .= '<fechaRegistro>'.$newDate_fechaemision.'</fechaRegistro>';
				$texto .= '<establecimiento>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),1,3).'</establecimiento>';
				$texto .= '<puntoEmision>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),4,3).'</puntoEmision>';
				$texto .= '<secuencial>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),6,9).'</secuencial>';
				$originalDate_fechaemision = $res->infocompretencion_fechaemision;
				$newDate_fechaemision = date("d/m/Y", strtotime($originalDate_fechaemision));
				$texto .= '<fechaEmision>'.$newDate_fechaemision.'</fechaEmision>';
				$texto .= '<autorizacion>'.$res->infotributaria_claveacceso.'</autorizacion>';
				
				
				

				$_estabRetencion_uno  = $res->estabretencion_uno;
				$_ptoemiRetencion_uno  = $res->ptoemiretencion_uno;
				$_secRetencion_uno  = $res->secretencion_uno;
				$originalfecha_cuentas_pagar = $res->fecha_cuentas_pagar;
				$_fecha_cuentas_pagar = date("d/m/Y", strtotime($originalfecha_cuentas_pagar));
				$_numero_autorizacion_cuentas_pagar  = $res->numero_autorizacion_cuentas_pagar;
				
				
				
				$where_detalle = "id_tri_retenciones = '$res->id_tri_retenciones' ";
				$rsDetalle = $Participes->getCondiciones($columnas_detalle, $tablas_detalle, $where_detalle, $id_detalle);
				foreach ($rsDetalle as $resDetalle){
					if ($resDetalle->impuesto_codigo == "1") //es renta
					{
						 $_baseImpGrav =  $resDetalle->impuestos_baseimponible;
						
						 $_codRetAir = $resDetalle->impuesto_codigoretencion;
						 $_baseImpAir = $resDetalle->impuestos_baseimponible;
						 $_porcentajeAir = $resDetalle->impuestos_porcentajeretener;
						 $_valRetAir = $resDetalle->impuestos_valorretenido;
						 	
					}
					else // IVA
					{
						$_montoIva  = $resDetalle->impuestos_baseimponible;
						//$_valRetBien10 = 20;//$resDetalle->impuestos_valorretenido;
						switch ($resDetalle->impuestos_porcentajeretener) {
							case "10.00":
								$_valRetBien10 = $resDetalle->impuestos_valorretenido;
								break;
							case "20.00":
								$_valRetServ20 = $resDetalle->impuestos_valorretenido;
								break;
							case "30.00":
								$_valorRetBienes= $resDetalle->impuestos_valorretenido;
								break;
							case "50.00":
								$_valRetServ50= $resDetalle->impuestos_valorretenido;
								break;
							case "70.00":
								$_valorRetServicios= $resDetalle->impuestos_valorretenido;
								break;
							case "100.00":
								$_valRetServ100= $resDetalle->impuestos_valorretenido;
								break;
						}
					}
				}
				
				$texto .= '<baseNoGraIva>'.'0.00'.'</baseNoGraIva>';
				$texto .= '<baseImponible>'.'0.00'.'</baseImponible>';
				$texto .= '<baseImpGrav>'.$_baseImpGrav.'</baseImpGrav>';
				$texto .= '<baseImpExe>'.'0.00'.'</baseImpExe>';
				$texto .= '<montoIce>'.'0.00'.'</montoIce>';
				$texto .= '<montoIva>'.$_montoIva.'</montoIva>';
					
				$texto .= '<valRetBien10>'.$_valRetBien10.'</valRetBien10>';
				$texto .= '<valRetServ20>'.$_valRetServ20.'</valRetServ20>';
				$texto .= '<valorRetBienes>'.$_valorRetBienes.'</valorRetBienes>';
				$texto .= '<valRetServ50>'.$_valRetServ50.'</valRetServ50>';
				$texto .= '<valorRetServicios>'.$_valorRetServicios.'</valorRetServicios>';
				$texto .= '<valRetServ100>'.$_valRetServ100.'</valRetServ100>';
				$texto .= '<totbasesImpReemb>'.'0'.'</totbasesImpReemb>';
				
				$texto .= '<pagoExterior>';
				$texto .= '<pagoLocExt>'.'01'.'</pagoLocExt>';
				$texto .= '<paisEfecPago>'.'NA'.'</paisEfecPago>';
				$texto .= '<aplicConvDobTrib>'.'NA'.'</aplicConvDobTrib>';
				$texto .= '<pagExtSujRetNorLeg>'.'NA'.'</pagExtSujRetNorLeg>';
				
				$texto .= '</pagoExterior>';
				
				$texto .= '<formasDepago>';
				$texto .= '<formaPago>'.'20'.'</formaPago>';
				$texto .= '</formasDepago>';
				
				$texto .= '<air>';
				$texto .= '<detalleAir>';
				$texto .= '<codRetAir>'.$_codRetAir.'</codRetAir>';
				$texto .= '<baseImpAir>'.$_baseImpAir.'</baseImpAir>';
				$texto .= '<porcentajeAir>'.$_porcentajeAir.'</porcentajeAir>';
				$texto .= '<valRetAir>'.$_valRetAir.'</valRetAir>';
					
				$texto .= '</detalleAir>';
				$texto .= '</air>';
				
				
				
				
				$texto .= '<estabRetencion1>'.$_estabRetencion_uno.'</estabRetencion1>';
				$texto .= '<ptoEmiRetencion1>'.$_ptoemiRetencion_uno.'</ptoEmiRetencion1>';
				$texto .= '<secRetencion1>'.$_secRetencion_uno.'</secRetencion1>';
				$texto .= '<autRetencion1>'.$_numero_autorizacion_cuentas_pagar.'</autRetencion1>';
				$texto .= '<fechaEmiRet1>'.$_fecha_cuentas_pagar.'</fechaEmiRet1>';
		
		
				
				$texto .= '</detalleCompras>';
				
				 
			}
			 
			 
			$texto .= '</compras>';
			 
			 
			 
			$texto .= '</iva>';
			 
			 
			$nombre_archivo = "ATS-".$mesperiodofiscal.$anioDiario.".xml";
			
			$ubicacionServer = $_SERVER['DOCUMENT_ROOT']."\\rp_c\\DOCUMENTOS_GENERADOS\\ATS\\";
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
	
	function devuelveDocumentoFactura($_id_tri_retenciones)
	{
		$Participes = new ParticipesModel();
		
		$_impuestos_numdocsustento = "";
		$columnas_detalle = "  id_tri_retenciones_detalle,
				id_tri_retenciones,
				impuesto_codigo,
				impuesto_codigoretencion,
				impuestos_baseimponible,
				impuestos_porcentajeretener,
				impuestos_valorretenido,
				impuestos_coddocsustento,
				impuestos_numdocsustento,
				impuesto_fechaemisiondocsustento,
				impuesto_codigo_dos,
				creado,
				modificado";
		$tablas_detalle = " public.tri_retenciones_detalle";
		
		$id_detalle = "creado";
		$where_detalle = "id_tri_retenciones = '$_id_tri_retenciones' ";
		$rsDetalle = $Participes->getCondiciones($columnas_detalle, $tablas_detalle, $where_detalle, $id_detalle);
		foreach ($rsDetalle as $resDetalle){
			if ($resDetalle->impuesto_codigo == "1") //es renta
			{
				
				$_impuestos_numdocsustento = $resDetalle->impuestos_numdocsustento;
		
			}
		
		}
		
		return $_impuestos_numdocsustento;
	}
		 
	function graficaXML( $paramArrayDatos){
		 
		$_base_imponible = 0;
		$_valor_retenido = 0;
		
		$_base_imponible_renta = 0;
		$_valor_retenido_renta = 0;
		
		$_base_imponible_iva = 0;
		$_valor_retenido_iva = 0;
		
		$cantidad = sizeof($paramArrayDatos);
		$html = "";
		if( $cantidad > 0 ){
			 
			$html.= "<table id='tbl_detalle_diario' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
			$html.= "<thead>";
			$html.= "<tr>";
			$html.='<th style="text-align: center;  font-size: 12px;"></th>';
			$html.='<th style="text-align: center;  font-size: 12px;">IMPUESTO</th>';
		
			$html.='<th style="text-align: center;  font-size: 12px;">CÓDIGO</th>';
			
			$html.='<th style="text-align: center;  font-size: 12px;">BASE IMPONIBLE</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">PORCENTAJE</th>';
			$html.='<th style="text-align: center;  font-size: 12px;">VALOR RETENIDO</th>';
		
			//$html.='<th style="text-align: left;  font-size: 12px;"></th>';
			$html.='</tr>';
			$html.='</thead>';
			$html.='<tbody>';
			 
			$i=0;
			
			
			foreach ($paramArrayDatos as $res){
				 
				$i++;
				$html.='<tr>';
				$html.='<td style="font-size: 11px;">'.$i.'</td>';
				if ($res->impuesto_codigo == "1")
				{
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