var ObtenerAnalisis	= function()
{
	var min_val_rol	= 100, min_val_variacion_rol 	= 80;
	var btnAceptCapacidadPago = $("#btn_aceptar_capacidad_pago");
	//elemntos para resultados
	var ototal	= $("#td_total_ingreso"),
	ocuotamaxima	= $("#td_cuota_maxima"),
	ocuota_pactada	= $("#txt_cuota_pactada");
	
	var val_sueldo_liquido	=$("#txt_sueldo_liquido").val();
	var val_cuota_vigente	=$("#txt_cuota_vigente").val();
	var val_fondos			=$("#txt_fondos").val();
	var val_decimos			=$("#txt_decimos").val();
	var val_rancho			=$("#txt_rancho").val();
	var val_ingresos_notarizados	=$("#txt_ingresos_notarizados").val();
	
	val_sueldo_liquido	= ( val_sueldo_liquido == "" ) ? 0 : val_sueldo_liquido;
	val_cuota_vigente	= ( val_cuota_vigente == "" ) ? 0 : val_cuota_vigente;
	val_fondos			= ( val_fondos == "" ) ? 0 : val_fondos;
	val_decimos			= ( val_decimos == "" ) ? 0 : val_decimos;
	val_rancho			= ( val_rancho == "" ) ? 0 : val_rancho;
	val_ingresos_notarizados	= ( val_ingresos_notarizados == "" ) ? 0 : val_ingresos_notarizados;
		
	var val_total	= parseFloat( val_sueldo_liquido ) + parseFloat( val_cuota_vigente ) + parseFloat( val_fondos ) + parseFloat( val_decimos ) + parseFloat( val_rancho ) + parseFloat( val_ingresos_notarizados );
	val_total		= Math.round( Math.round( val_total * 1000 ) / 10 ) / 100;
	//establecemos el valor total
	ototal.text( val_total ); //es un elemento td
	
	//establecemos el valor de cuota maxima
	var val_cuota_maxima	= val_total/2;
	val_cuota_maxima	= Math.round( Math.round( val_cuota_maxima * 1000 ) / 10 ) / 100 ;
	ocuotamaxima.text( val_cuota_maxima ); // es un elemento td
	
	var val_cuota_pactada	= ( ocuota_pactada.val() == "" ) ? 0 : ocuota_pactada.val();
	val_cuota_pactada	= parseFloat( val_cuota_pactada );
		
	var val_variacion_rol	= val_sueldo_liquido - ( val_cuota_pactada - val_cuota_vigente );
	val_variacion_rol	= Math.round(Math.round( val_variacion_rol * 1000 ) / 10 ) / 100;
	val_variacion_rol	= Math.abs( val_variacion_rol );
	
	var otext_variacion_rol	= $("#h3-variacion_rol");
	otext_variacion_rol.text( val_variacion_rol );
	
	var val_ingresos_adicionales	= val_variacion_rol + val_fondos + val_decimos + val_rancho + val_ingresos_notarizados;
	val_ingresos_adicionales	= Math.round( Math.round( val_ingresos_adicionales * 1000) / 10) / 100;
	
	if( val_cuota_maxima >= val_cuota_pactada)
	{
		label_aprobar_credito();
		
		
		if( val_variacion_rol < min_val_variacion_rol )
		{
			document.getElementById("variacion_rol").classList.remove('bg-green');
			document.getElementById("variacion_rol").classList.add('bg-red');
			document.getElementById("h3-variacion_rol_estado").innerHTML = " ROL MUY BAJO NO PROCEDE CREDITO";
			label_negar_credito();
		}else
		{
			document.getElementById("variacion_rol").classList.remove('bg-red');
			document.getElementById("variacion_rol").classList.add('bg-green');
			document.getElementById("h3-variacion_rol_estado").innerHTML = " ROL ACEPTABLE APLICADA NUEVA CUOTA";
			
			label_aprobar_credito();
			
		}
		
		if(	val_variacion_rol < min_val_rol )
		{
			document.getElementById("validacion_rol").classList.remove('bg-green');
			document.getElementById("validacion_rol").classList.add('bg-yellow');
			document.getElementById("h3-validacion_rol_estado").innerHTML = "CONSIDERAR INGRESOS ADICIONALES NO TIENE 100";
			
		}else
		{
			document.getElementById("validacion_rol").classList.remove('bg-yellow');
			document.getElementById("validacion_rol").classList.add('bg-green');
			document.getElementById("h3-validacion_rol_estado").innerHTML = " PROCEDE CREDITO";
			label_aprobar_credito();
		}
		
		if( val_ingresos_adicionales < min_val_rol )
		{
			document.getElementById("considerado_ingresos").classList.remove('bg-green');
			document.getElementById("considerado_ingresos").classList.add('bg-yellow');
			document.getElementById("h3-consideracion_rol_estado").innerHTML = "CONSIDERAR INGRESOS ADICIONALES NO TIENE 100";
			
		}else
		{
			document.getElementById("considerado_ingresos").classList.remove('bg-yellow');
			document.getElementById("considerado_ingresos").classList.add('bg-green');
			document.getElementById("h3-consideracion_rol_estado").innerHTML = " PROCEDE CREDITO";
			label_aprobar_credito();
		}
		
	}else
	{
		label_negar_credito();
	}
		
}

var label_negar_credito	= function(){
	
	document.getElementById("credito_aprobado").classList.remove('bg-green');
	document.getElementById("credito_aprobado").classList.add('bg-red');
	document.getElementById("h3_credito_aprobado").innerHTML = "CREDITO NEGADO";
	$("#btn_enviar_capacidad_pago").attr("disabled",true);
}

var label_aprobar_credito	= function(){
	
	document.getElementById("credito_aprobado").classList.remove('bg-red');
	document.getElementById("credito_aprobado").classList.add('bg-green');
	document.getElementById("h3_credito_aprobado").innerHTML = "CREDITO ACEPTADO";
	$("#btn_enviar_capacidad_pago").attr("disabled",false);
}
