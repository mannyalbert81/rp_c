$(document).ready( function (){
 SumaIngresos();
});

function SumaIngresos()
{
	var sueldo_liquido=$("#sueldo_liquido").val();
	var cuota_vigente=$("#cuota_vigente").val();
	var fondos=$("#fondos").val();
	var decimos=$("#decimos").val();
	var rancho=$("#rancho").val();
	var ingresos_notarizados=$("#ingresos_notarizados").val();
	if (sueldo_liquido=="") sueldo_liquido=0;
	if (cuota_vigente=="") cuota_vigente=0;
	if (fondos=="") fondos=0;
	if (decimos=="") decimos=0;
	if (rancho=="") rancho=0;
	if (ingresos_notarizados=="") ingresos_notarizados=0;
	
	var total=parseFloat(sueldo_liquido)+parseFloat(cuota_vigente)+parseFloat(fondos)+parseFloat(decimos)+parseFloat(rancho)+parseFloat(ingresos_notarizados);
	total=Math.round(Math.round(total * 1000) / 10) / 100;
	document.getElementById("total_ingreso").innerHTML = total;
	var cuota_maxima=total/2;
	cuota_maxima=Math.round(Math.round(cuota_maxima * 1000) / 10) / 100
	document.getElementById("cuota_maxima").innerHTML = cuota_maxima;
	
	var cuota_pactada=$("#cuota_pactada").val();
	if (cuota_pactada=="") cuota_pactada=0;
	cuota_pactada=parseFloat(cuota_pactada);

	if(cuota_maxima>=cuota_pactada)
		{
		document.getElementById("credito_aprobado").classList.remove('bg-red');
		document.getElementById("credito_aprobado").classList.add('bg-green');
		document.getElementById("h3_credito_aprobado").innerHTML = "CREDITO ACEPTADO";
		}
	else
		{
		document.getElementById("credito_aprobado").classList.remove('bg-green');
		document.getElementById("credito_aprobado").classList.add('bg-red');
		document.getElementById("h3_credito_aprobado").innerHTML = "CREDITO NEGADO";
		}
	var variacion_rol=sueldo_liquido-(cuota_pactada-cuota_vigente);
	variacion_rol=Math.round(Math.round(variacion_rol * 1000) / 10) / 100
	variacion_rol=Math.abs(variacion_rol)
	document.getElementById("h3-variacion_rol").innerHTML = variacion_rol;
	
	if(variacion_rol<80)
	{
		document.getElementById("variacion_rol").classList.remove('bg-green');
		document.getElementById("variacion_rol").classList.add('bg-red');
		document.getElementById("h3-variacion_rol_estado").innerHTML = " ROL MUY BAJO NO PROCEDE CREDITO";
	}
else
	{
	document.getElementById("variacion_rol").classList.remove('bg-red');
	document.getElementById("variacion_rol").classList.add('bg-green');
	document.getElementById("h3-variacion_rol_estado").innerHTML = " ROL ACEPTABLE APLICADA NUEVA CUOTA";
	}
	
	if(variacion_rol<150)
	{
		document.getElementById("validacion_rol").classList.remove('bg-green');
		document.getElementById("validacion_rol").classList.add('bg-yellow');
		document.getElementById("h3-validacion_rol_estado").innerHTML = "CONSIDERAR INGRESOS ADICIONALES NO TIENE 150";
	}
else
	{
	document.getElementById("validacion_rol").classList.remove('bg-yellow');
	document.getElementById("validacion_rol").classList.add('bg-green');
	document.getElementById("h3-validacion_rol_estado").innerHTML = " PROCEDE CREDITO";
	}
	
	var ingresos_adicionales=variacion_rol+fondos+decimos+rancho+ingresos_notarizados;
	ingresos_adicionales=Math.round(Math.round(ingresos_adicionales * 1000) / 10) / 100
	console.log(ingresos_adicionales)
	if(ingresos_adicionales<150)
	{
		document.getElementById("considerado_ingresos").classList.remove('bg-green');
		document.getElementById("considerado_ingresos").classList.add('bg-yellow');
		document.getElementById("h3-consideracion_rol_estado").innerHTML = "CONSIDERAR INGRESOS ADICIONALES NO TIENE 150";
	}
else
	{
	document.getElementById("considerado_ingresos").classList.remove('bg-yellow');
	document.getElementById("considerado_ingresos").classList.add('bg-green');
	document.getElementById("h3-consideracion_rol_estado").innerHTML = " PROCEDE CREDITO";
	}
	
	
}