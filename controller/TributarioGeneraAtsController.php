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
					  GROUP BY tri_retenciones_detalle.impuesto_codigo, tri_retenciones_detalle.impuesto_codigoretencion
					  ";
			
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
		$columnas = "  id_tri_retenciones, 
				infotributaria_ambiente, 
				infotributaria_tipoemision, 
				infotributaria_razonsocial,
				infotributaria_nombrecomercial, 
				infotributaria_ruc, 
				infotributaria_claveacceso,
				infotributaria_coddoc, 
				infotributaria_ptoemi, 
				infotributaria_estab, 
				infotributaria_secuencial,
				infotributaria_dirmatriz,
				infocompretencion_fechaemision, 
				infocompretencion_direstablecimiento,
				infocompretencion_contribuyenteespecial,
				infocompretencion_obligadocontabilidad,
				infocompretencion_tipoidentificacionsujetoretenido, 
				infocompretencion_razonsocialsujetoretenido,
				infocompretencion_identificacionsujetoretenido,
				infocompretencion_periodofiscal, 
				infoadicional_campoadicional,
				infoadicional_campoadicional_dos, 
				infoadicional_campoadicional_tres,
				creado, 
				modificado, 
				enviado_correo_electronico, 
				fecha_autorizacion";
		$tablas = " public.tri_retenciones";
		$where = "SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,1,2) = '$mesperiodofiscal'
		AND SUBSTRING ( tri_retenciones.infocompretencion_periodofiscal,4,4) = '$anioDiario' ";
			
		$id = "creado";
		
		
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
		
		$_valRetBien10;
		$_valRetServ20;
		$_valorRetBienes;
		$_valRetServ50;
		$_valorRetServicios;
		$_valRetServ100;
		
		$_codRetAir ;
		$_baseImpAir = "0.00";
		$_porcentajeAir = "0.00";
		$_valRetAir = "0.00";
		
		
		////genera el xml
		
		$texto="";
		 
		
			 
		if(!empty($rsHistorial)){
			 
			 
			$texto .='<?xml version="1.0" encoding="UTF-8"?>';
			$texto .= '<iva>';
			$texto .= '<TipoIDInformante>R</TipoIDInformante>';
			$texto .= '<IdInformante>1791700376001</IdInformante>';
			$texto .= '<razonSocial>1791700376001</razonSocial>';
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
				$texto .= '<fechaRegistro>'.$res->infocompretencion_fechaemision.'</fechaRegistro>';
				$texto .= '<establecimiento>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),1,3).'</establecimiento>';
				$texto .= '<puntoEmision>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),4,3).'</puntoEmision>';
				$texto .= '<secuencial>'.substr( $this->devuelveDocumentoFactura($res->id_tri_retenciones),6,9).'</secuencial>';
				$texto .= '<fechaEmision>'.$res->infocompretencion_fechaemision.'</fechaEmision>';
				$texto .= '<autorizacion>'.$res->infotributaria_claveacceso.'</autorizacion>';
				
				$where_detalle = "id_tri_retenciones) = '$res->id_tri_retenciones' ";
				$rsDetalle = $Participes->getCondiciones($columnas_detalle, $tablas_detalle, $where_detalle, $id_detalle);
				foreach ($rsDetalle as $resDetalle){
					if ($resDetalle->impuesto_codigo == "1") //es renta
					{
						 $_baseImpGrav =  $resDetalle->impuestos_baseimponible;
						
						 $_codRetAir = $res->impuesto_codigoretencion;
						 $_baseImpAir = $resDetalle->impuestos_baseimponible;
						 $_porcentajeAir = $resDetalle->impuestos_porcentajeretener;
						 $_valRetAir = $resDetalle->impuestos_valorretenido;
						 	
					}
					else // IVA
					{
						$_montoIva  = $resDetalle->impuestos_baseimponible;
						
						switch ($resDetalle->impuestos_porcentajeretener) {
							case 10:
								$_valRetBien10;
								break;
							case 20:
								$_valRetServ20;
								break;
							case 30:
								$_valorRetBienes;
								break;
							case 50:
								$_valRetServ50;
								break;
							case 70:
								$_valorRetServicios;
								break;
							case 2:
								$_valRetServ100;
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
				$texto .= '<pagoLocExt>'.'NA'.'</pagoLocExt>';
				$texto .= '<paisEfecPago>'.'NA'.'</paisEfecPago>';
				$texto .= '<aplicConvDobTrib>'.'NA'.'</aplicConvDobTrib>';
				$texto .= '<pagExtSujRetNorLeg>'.'NA'.'</pagExtSujRetNorLeg>';
				$texto .= '<pagExtSujRetNorLeg>'.'NA'.'</pagExtSujRetNorLeg>';
				$texto .= '</pagoExterior>';
				
				$texto .= '<formasDepago>';
				$texto .= '<formaPago>'.'00'.'</formaPago>';
				$texto .= '</formasDepago>';
				
				$texto .= '<air>';
				$texto .= '<detalleAir>';
				$texto .= '<codRetAir>'.$_valRetServ100.'</valRetServ100>';
			
				
				$texto .= '</detalleAir>';
				$texto .= '</air>';
		
				$texto .= '</detalleCompras>';
				
				 
			}
			 
			 
			$texto .= '</compras>';
			 
			 
			 
			$texto .= '</iva>';
			 
			 
		
			 
			 
			$textoXML = mb_convert_encoding($texto, "UTF-8");
			 
			// Grabamos el XML en el servidor como un fichero plano, para
			// poder ser leido por otra aplicación.
			$gestor = fopen("C:\ATS\ATS".$mesperiodofiscal.$anioDiario.".xml", 'w');
			fwrite($gestor, $textoXML);
			fclose($gestor);
			 
			 
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
		$where_detalle = "id_tri_retenciones) = '$_id_tri_retenciones' ";
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