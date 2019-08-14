var id_participe;
var disponible_participe;
var solicitud;

$(document).ready( function (){
	
	$(":input").inputmask();
		
});

$('#cedula_participe').keypress(function(event){
	  if(event.keyCode == 13){
	    $('#buscar_participe').click();
	  }
	});




$("#myModalSimulacion").on("hidden.bs.modal", function () {
	$("#monto_credito").val("");
	$("#tipo_credito").val("");
	$("#fecha_corte").val("");
	$("#select_cuotas").html("");
	$("#tabla_amortizacion").html("");
	$("#info_solicitud").html("");
	
});
$("#myModalAnalisis").on("hidden.bs.modal", function () {
	var modal = $('#myModalAnalisis');
	modal.find("#sueldo_liquido").val("");
	modal.find("#cuota_vigente").val("");
	modal.find("#fondos").val("");
	modal.find("#decimos").val("");
	modal.find("#rancho").val("");
	modal.find("#ingresos_notarizados").val("");
	
});
$("#myModalAnalisis").on("hidden.bs.modal", function () {
	var modal = $('#myModalAnalisis');
	modal.find("#sueldo_liquido").val("");
	modal.find("#cuota_vigente").val("");
	modal.find("#fondos").val("");
	modal.find("#decimos").val("");
	modal.find("#rancho").val("");
	modal.find("#ingresos_notarizados").val("");
	
});

function BorrarCedula()
{
	$('#cedula_participe').val("");
}

function BorrarCedulaGarante()
{
	$('#cedula_garante').val("");
}

function TipoCredito()
{   
	var interes=$("#tipo_credito").val();
	var bci="<label for=\"cedula_garante\" class=\"control-label\">Añadir garante:</label>" +
			"<div id=\"mensaje_cedula_garante\" class=\"errores\"></div>" +
			"<div class=\"input-group\">"
          +"<input type=\"text\" data-inputmask=\"'mask': '9999999999'\" class=\"form-control\" id=\"cedula_garante\" name=\"cedula_garante\" placeholder=\"C.I.\">"
          +"<span class=\"input-group-btn\">"      			
          +"<button type=\"button\" class=\"btn btn-primary\" id=\"buscar_garante\" name=\"buscar_garante\" onclick=\"BuscarGarante()\">"
          +"<i class=\"glyphicon glyphicon-plus\"></i>"
          +"</button>"
          +"<button type=\"button\" class=\"btn btn-danger\" id=\"borrar_cedula\" name=\"borrar_cedula\" onclick=\"BorrarCedulaGarante()\">"
          +"<i class=\"glyphicon glyphicon-arrow-left\"></i>"
          +"</button>"
          +"</span>"
          +"</div>";
	if(interes==9)
		{
		$('#info_garante').html(bci);
		$(":input").inputmask();
		$('#cedula_garante').keypress(function(event){
			  if(event.keyCode == 13){
				  console.log("garante")
			    $('#buscar_garante').click();
			  }
			});
		}
	else
		{
		$('#info_garante').html("");
		var monto="Disponible : "+disponible_participe;
		if (disponible_participe < 150)
		{
		document.getElementById("disponible_participe").classList.remove('bg-olive');
		document.getElementById("disponible_participe").classList.add('bg-red');
		}
		$("#monto_disponible").html(monto);
		}
}

function SimulacionCredito()
{
	$("#myModalSimulacion").modal();
	InfoParticipe();
}

function QuitarGarante()
{
	var bci="<label for=\"cedula_garante\" class=\"control-label\">Añadir garante:</label>" +
	"<div id=\"mensaje_cedula_garante\" class=\"errores\"></div>" +
	"<div class=\"input-group\">"
  +"<input type=\"text\" data-inputmask=\"'mask': '9999999999'\" class=\"form-control\" id=\"cedula_garante\" name=\"cedula_garante\" placeholder=\"C.I.\">"
  +"<span class=\"input-group-btn\">"      			
  +"<button type=\"button\" class=\"btn btn-primary\" id=\"buscar_garante\" name=\"buscar_garante\" onclick=\"BuscarGarante()\">"
  +"<i class=\"glyphicon glyphicon-plus\"></i>"
  +"</button>"
  +"<button type=\"button\" class=\"btn btn-danger\" id=\"borrar_cedula\" name=\"borrar_cedula\" onclick=\"BorrarCedulaGarante()\">"
  +"<i class=\"glyphicon glyphicon-arrow-left\"></i>"
  +"</button>"
  +"</span>"
  +"</div>";
	$('#info_garante').html(bci);
	var monto="Disponible : "+disponible_participe;
	var aportes=document.getElementById("aportes_participes");
	if (disponible_participe < 150 && aportes!=null)
	{
	document.getElementById("disponible_participe").classList.remove('bg-olive');
	document.getElementById("disponible_participe").classList.add('bg-red');
	}
	else
		{
		document.getElementById("disponible_participe").classList.remove('bg-red');
		document.getElementById("disponible_participe").classList.add('bg-olive');
		}
	$("#monto_disponible").html(monto);
}

function InfoParticipe()
{
	var modal = $('#myModalSimulacion');
	var ciparticipe=$('#cedula_participe').val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=CreditoParticipe',
	    type: 'POST',
	    data: {
	    	cedula_participe:ciparticipe
	    },
	})
	.done(function(x) {
		modal.find("#info_participe").html(x);
		var limite=document.getElementById("monto_disponible").innerHTML;
		var elementos=limite.split(" : ");
		limite=elementos[1];
		disponible_participe=limite;
		console.log("disponible participe "+limite);
		var lista=document.getElementById("disponible_participe").classList;
		lista=lista.value;
		if(lista.includes('bg-red'))
			{
			swal({
		  		  title: "Advertencia!",
		  		  text: "El participe no puede acceder a un crédito en este momento",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function Redondeo(monto)
{
	monto=$("#monto_credito").val();
	var residuo=monto%10;
	if (residuo>=5 && monto>100)
		{
		monto=parseFloat(monto)+parseFloat(10-residuo);
		}
	else
		{
		monto=parseFloat(monto)-parseFloat(residuo);
		}
		
	$("#monto_credito").val(monto);
	
}

function SimularCredito()
{
	var monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var cuota_credito=$("#cuotas_credito").val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=SimulacionCredito',
	    type: 'POST',
	    data: {
	    	monto_credito:monto,
	    	tasa_interes:interes,
	    	fecha_corte:fecha_corte,
	    	plazo_credito:cuota_credito
	    },
	})
	.done(function(x) {
		$("#tabla_amortizacion").html(x);
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function GetCuotas()
{
	var monto=$("#monto_credito").val();
	Redondeo(monto);
	monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var limite=document.getElementById("monto_disponible").innerHTML;
	var elementos=limite.split(" : ");
	var lista=document.getElementById("disponible_participe").classList;
	lista=lista.value;
	limite=elementos[1];
	disponible_participes=limite;
	console.log(limite+" "+monto);
	if(monto=="" || parseFloat(monto)<150 || parseFloat(monto)>parseFloat(limite) )
		{
		$("#mensaje_monto_credito").text("Monto no valido");
		$("#mensaje_monto_credito").fadeIn("slow");
		$("#mensaje_monto_credito").fadeOut("slow");
		}
	console.log(interes+" interes")
	if(interes=="12" && parseFloat(monto)>7000)
	{
		$("#mensaje_monto_credito").text("Monto no valido");
		$("#mensaje_monto_credito").fadeIn("slow");
		$("#mensaje_monto_credito").fadeOut("slow");
		}
	
	if(fecha_corte=="")
		{
		$("#mensaje_fecha").text("Escoja una fecha");
		$("#mensaje_fecha").fadeIn("slow");
		$("#mensaje_fecha").fadeOut("slow");
		}
	if(interes=="")
	{
	$("#mensaje_tipo_credito").text("Escoja una fecha");
	$("#mensaje_tipo_credito").fadeIn("slow");
	$("#mensaje_tipo_credito").fadeOut("slow");
	}
	if(monto!="" && parseFloat(monto)>150 && parseFloat(monto)<parseFloat(limite) && interes!="" && fecha_corte!="")
		{
		if(lista.includes('bg-red'))
			{
			swal({
		  		  title: "Advertencia!",
		  		  text: "El participe no puede acceder a un crédito en este momento",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		else
			{
			if(interes=="12" && parseFloat(monto)>7000)
				{
				
				}
			else
				{
				$.ajax({
				    url: 'index.php?controller=SimulacionCreditos&action=GetCuotas',
				    type: 'POST',
				    data: {
				    	monto_credito:monto
				    },
				})
				.done(function(x) {
					$("#select_cuotas").html(x);
					SimularCredito();
					
					
				})
				.fail(function() {
				    console.log("error");
				});
				}
				
				
			
			}
		
		}
	
}

function CuotaVigente(cuota_credito)
{
	var modal = $('#myModalAnalisis');
	modal.find("#cuota_vigente").val(cuota_credito);
	SumaIngresos();
}

function SumaIngresos()
{
	var modal = $('#myModalAnalisis');
	var sueldo_liquido=modal.find("#sueldo_liquido").val();
	var cuota_vigente=modal.find("#cuota_vigente").val();
	var fondos=modal.find("#fondos").val();
	var decimos=modal.find("#decimos").val();
	var rancho=modal.find("#rancho").val();
	var ingresos_notarizados=modal.find("#ingresos_notarizados").val();
	if (sueldo_liquido=="") sueldo_liquido=0;
	if (cuota_vigente=="") cuota_vigente=0;
	if (fondos=="") fondos=0;
	if (decimos=="") decimos=0;
	if (rancho=="") rancho=0;
	if (ingresos_notarizados=="") ingresos_notarizados=0;
	
	var total=parseFloat(sueldo_liquido)+parseFloat(cuota_vigente)+parseFloat(fondos)+parseFloat(decimos)+parseFloat(rancho)+parseFloat(ingresos_notarizados);
	total=Math.round(Math.round(total * 1000) / 10) / 100;
	modal.find("#total_ingreso").html(total);
	var cuota_maxima=total/2;
	cuota_maxima=Math.round(Math.round(cuota_maxima * 1000) / 10) / 100
	modal.find("#cuota_maxima").html(cuota_maxima);
	
	var cuota_pactada=modal.find("#cuota_pactada").val();
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

function AnalisisCredito()
{
	$("#myModalAnalisis").modal();
	var ciparticipe=$('#cedula_participe').val();
	$.ajax({
	    url: 'index.php?controller=AnalisisCreditos&action=cuotaParticipe',
	    type: 'POST',
	    data: {
	    	cedula_participe:ciparticipe
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log("cuota :"+x);
		CuotaVigente(x);
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}

function InfoSolicitud(cedula,id_solicitud)
{
	$('#cedula_participe').val(cedula);
	BuscarParticipe();
	SimulacionCredito();
	solicitud=id_solicitud;
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=InfoSolicitud',
	    type: 'POST',
	    data: {
	    	id_solicitud:id_solicitud
	    },
	})
	.done(function(x) {
		$("#info_solicitud").html(x);
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}

function BuscarParticipe()
{
	var ciparticipe=$('#cedula_participe').val();
	
	if(ciparticipe=="" || ciparticipe.includes('_'))
		{
		$("#mensaje_cedula_participe").text("Ingrese cédula");
		$("#mensaje_cedula_participe").fadeIn("slow");
		$("#mensaje_cedula_participe").fadeOut("slow");
		}
	else
		{
		console.log(ciparticipe);
		$.ajax({
		    url: 'index.php?controller=BuscarParticipes&action=BuscarParticipe',
		    type: 'POST',
		    data: {
		    	   cedula: ciparticipe
		    },
		})
		.done(function(x) {
			var y=$.parseJSON(x);
			console.log(y);
			$('#participe_encontrado').html(y[0]);
		     id_participe=y[1];
			AportesParticipe(id_participe, 1)
			CreditosActivosParticipe(id_participe, 1)
			
		})
		.fail(function() {
		    console.log("error");
		});
		}
}

function BuscarGarante()
{
	var ciparticipe=$('#cedula_garante').val();
	var cicredito=$('#cedula_credito').html();
	cicredito=cicredito.split(" : ");
	cicredito=cicredito[1];	
	if(ciparticipe=="" || ciparticipe.includes('_'))
		{
		$("#mensaje_cedula_garante").text("Ingrese cédula");
		$("#mensaje_cedula_garante").fadeIn("slow");
		$("#mensaje_cedula_garante").fadeOut("slow");
		}
	else
		{
	    if (ciparticipe==cicredito)
	    {
	    	swal({
		  		  title: "Advertencia!",
		  		  text: "Numero de cédula invalido",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
	    }
	    else
	    	{
	    	$.ajax({
			    url: 'index.php?controller=SimulacionCreditos&action=BuscarGarante',
			    type: 'POST',
			    data: {
			    	   cedula_garante: ciparticipe
			    },
			})
			.done(function(x) {
				if(!(x.includes("Participe no encontrado")))
					{
					$("#info_garante").html(x);
					var edad_garante=$("#edad_garante").html();
					edad_garante=edad_garante.split(" : ");
					edad_garante=edad_garante[1].split(", ");
					edad_garante=edad_garante[0].split(" ");
					edad_garante=edad_garante[0];	
					console.log(edad_garante);
					var limite=document.getElementById("monto_disponible").innerHTML;
					var elementos=limite.split(" : ");
					limite=elementos[1];
					var limite_garante=document.getElementById("monto_garante_disponible").innerHTML;
					elementos=limite_garante.split(" : ");
					limite_garante=elementos[1];
					console.log(limite_garante);
					var limite_total=parseFloat(limite_garante)+parseFloat(limite);
					var nuevo_monto="Disponible Total : "+limite_total;
					$("#monto_disponible").html(nuevo_monto);
					var aportes=document.getElementById("aportes_participes");
					var aportes_garante=document.getElementById("aportes_garante");
					console.log(aportes);
					if (limite_total>150 && edad_garante<75 && aportes==null && aportes_garante==null)
						{
						document.getElementById("disponible_participe").classList.remove('bg-red');
						document.getElementById("disponible_participe").classList.add('bg-olive');
						}
					if(edad_garante<75 && aportes_garante!=null)
					{
						document.getElementById("disponible_participe").classList.remove('bg-olive');
						document.getElementById("disponible_participe").classList.add('bg-red');
						swal({
					  		  title: "Advertencia!",
					  		  text: "El participe no cumple las condiciones\npara ser garante",
					  		  icon: "warning",
					  		  button: "Aceptar",
					  		});
					}
				}
				else
					{
					swal({
				  		  title: "Advertencia!",
				  		  text: "Participe no está registrado o no se encuentra activo",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
					}
			})
			.fail(function() {
			    console.log("error");
			});
	    	}
		
		
		}
}

function AportesParticipe(id, page)
{
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=AportesParticipe',
	    type: 'POST',
	    data: {
	    	   id_participe: id,
	    	   page: page
	    },
	})
	.done(function(x) {
		$('#aportes_participe').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function CreditosActivosParticipe(id, page)
{
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=CreditosActivosParticipe',
	    type: 'POST',
	    data: {
	    	   id_participe: id,
	    	   page: page
	    },
	})
	.done(function(x) {
		$('#creditos_participe').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function ConfirmarCodigo()
{
	var monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var cuota_credito=$("#cuotas_credito").val();
	var ciparticipe=$('#cedula_participe').val();
	var nombre_participe=$("#nombre_participe_credito").html();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=genera_codigo',
	    type: 'POST',
	    data: {
	    },
	})
	.done(function(x) {
		x=x.trim();
		var informacion="<h3>Se procedera a generar un crédito para "+nombre_participe+"</h3>" +
				"<h3>Con cédula de identidad número "+ciparticipe+"</h3>" +
				"<h3>Por el monto de "+monto+" USD</h3>" +
				"<h3>A un plazo de "+cuota_credito+" meses con interes del "+interes+"%</h3>" +
				"<h3>Con fecha de pago "+fecha_corte+"</h3>" +
				"<h3>Para confirmar ingrese el siguiente código</h3>" +
				"<h2 id=\"codigo_generado\">"+x+"</h2>";
		$("#info_credito_confirmar").html(informacion);	
	})
	.fail(function() {
	    console.log("error");
	});
	var informacion="<h3></h3>"
}

function GuardarCredito()
{
console.log("Guardar Credito");
swal({
	title: "Advertencia!",
	  text: "Se precedera con el registro del crédito",
	  icon: "warning",
	  buttons: {
	    cancel: "Cancelar",
	    aceptar: {
	      text: "Aceptar",
	      value: "aceptar",
	    }
	  },
	})
	.then((value) => {
	  switch (value) {
	 
	    case "aceptar":
	    	ConfirmarCodigo();
	    	$("#myModalInsertar").modal();
	      break;
	 
	    default:
	      swal("Crédito no registrado");
	  }
	});
}

function SubirInformacionCredito()
{
	var monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var cuota_credito=$("#cuotas_credito").val();
	var ciparticipe=$('#cedula_participe').val();
	var observacion=$('#observacion_confirmacion').val();
	id_solicitud=solicitud;
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=SubirInformacionCredito',
	    type: 'POST',
	    data: {
	    	monto_credito: monto,
	    	tasa_interes: interes,
	    	fecha_pago: fecha_corte,
	    	cuota_credito: cuota_credito,
	    	cedula_participe: ciparticipe,
	    	observacion_credito: observacion,
	    	id_solicitud:id_solicitud
	    },
	})
	.done(function(x) {
		console.log(x);
		x=x.trim();
		if(x=="OK")
			{
			swal({
		  		  title: "Crédito Registrado",
		  		  text: "La solicitud de crédito ha sido procesada",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
			 $('#cerrar_simulacion').click();
			 $('#cerrar_insertar').click();
			 CreditosActivosParticipe(id_participe, 1);
			 
			}
		else
			{
			swal({
		  		  title: "Advertencia!",
		  		  text: "Hubo un error en el proceso de la solicitud",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			
			}
	})
	.fail(function() {
	    console.log("error");
	});
	
	
}

function RegistrarCredito()
{
 var codigo_generado=$("#codigo_generado").html();
 var codigo_insertado=$("#codigo_confirmacion").val();
 if(codigo_insertado=="" || codigo_insertado.includes("_"))
	 {
	 swal("Inserte código");
	 }
 else if(codigo_insertado!="" && !(codigo_insertado.includes("_")) && codigo_insertado==codigo_generado)
	 {
	 	SubirInformacionCredito()
	 }
 else
	 {
	 swal("Código incorrecto");
	 }
}