var id_participe;
var disponible_participe;
var solicitud;
var modal=0;
var garante_seleccionado=false;
var ci_garante="";
var renovacion_credito=false;
var capacidad_pago_garante_suficiente=false;
var sin_solicitud=false;
var avaluo_bien_sin_solicitud=0;



$(document).ready( function (){
	
	// ESTABLESCO LA MASCARA AL CAMPO CEDULA PARTICIPE
	$(":input").inputmask();
	
	// CARGO LOS TIPOS DE CREDITO AL COMBO BOX
	GetTipoCreditos();
	
		
});




// FUNCION PARA LLENAR EL COMBO BOX TIPO DE CREDITOS

function GetTipoCreditos()
{
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=getTipoCredito',
	    type: 'POST',
	    data: null,
	})
	.done(function(x) {
		// IMPRIMO EN LA VISTA
		$('#tipo_creditos').html(x);
		
		// CAPTURO EL NOMBRE DEL CREDITO DE LA SOLICITUD
		SetTipoCreditos();
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}


// FUNCION PARA CAPTURAR NOMBRE DEL CREDITO DE LA SOLICITUD
function SetTipoCreditos()
{
	
	// CAPTURAR NOMBRE DEL CREDITO DE LA SOLICITUD
	var tipo_credito_solicitud=$("#tipo_credito_solicitud").html();
	
	// EXTRAIGO NOMBRE DEL CREDITO DE LA SOLICITUD
	tipo_credito_solicitud=tipo_credito_solicitud.split(" : ");
	tipo_credito_solicitud=tipo_credito_solicitud[1];
	
	
	// AUTOSELECCIONO EL TIPO DE CREDITO QUE BIENE DE LA SOLICITUD EN EL COMBO BOX
	
	switch (tipo_credito_solicitud)
	{
		case "ORDINARIO":
		$("#tipo_credito").val("ORD");
		break;
		case "EMERGENTE":
			$("#tipo_credito").val("EME");
			break;
		case "HIPOTECARIO":
			$("#tipo_credito").val("PH");
			break;
			
		case "REFINANCIAMIENTO":
			$("#tipo_credito").val("RF");
			break;	
			
		case "2x1":
			$("#tipo_credito").val("2x1");
			break;	
			
		case "ACUERDO PAGO":
			$("#tipo_credito").val("AP");
			break;
		
	}

	// EMPIEZA LA VALIDACION DEL CREDITO
	TipoCredito();
	
	

}
	
	
// EXECUTO BOTON BUSCAR PARTICIPE AL APLASTAR LA TECLA INTRO 

$('#cedula_participe').keypress(function(event){
	  if(event.keyCode == 13){
	    $('#buscar_participe').click();
	  }
	});



function Iniciar(cedula,id_solicitud){
	
	
	InfoSolicitud(cedula,id_solicitud);
	
	setTimeout(function(){
		// CARGO INFORMACION DEL PARTICIPE
		SetTipoCreditos();
		
	},500);
	
	
}	





// PRIMERO LLAMO LA FUNCION INFO SOLICITUD PARA INICIALIARLO

function InfoSolicitud(cedula,id_solicitud)
{
	
	//LLENO EN EL TXT LA CEDULA DEL PARTICIPE QUE VA GENERAR EL CREDITO
	$('#cedula_participe').val(cedula);
	
	
	var boton_buscar_participe='<button type="button" class="btn btn-primary" id="buscar_participe" name="buscar_participe" onclick="Iniciar('+cedula+', '+id_solicitud+')">'+
								'<i class="glyphicon glyphicon-search"></i>'+
								'</button>';
								
	
	// MUESTRO EN LA VISTA EL BOTON CAPACIDAD DE PAGO	
	$("#buscar_participe_boton").html(boton_buscar_participe);
	

	
	
	
	// EXECUTO METODO QUE CARGA INFORMACION BASICA DEL PARTICIPE
	BuscarParticipe();
	
	
	// INICIALIZO EL SIMULADOR DE CREDITO
	SimulacionCredito();
	
	
	
	
	// CONSULTO Y TRAIGO LA INFORMACION DE LA SOLICITUD PARA IMPRIMIR EN MODAL
	solicitud=id_solicitud;
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=InfoSolicitud',
	    type: 'POST',
	    data: {
	    	id_solicitud:id_solicitud
	    },
	})
	.done(function(x) {
		
		// cargo la informacion de la solicitud en el modal
		$("#info_solicitud").html(x);
		
		
	
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}







function BuscarParticipe()
{
	// CAPTUTO CEDULA DEL PARTICIPE DEL TXT
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
				// cargos tabla de informacion basica de los participes
				$('#participe_encontrado').html(y[0]);
			     id_participe=y[1];
			    
			     
			    // cargo tabla de aportes de los participes
				AportesParticipe(id_participe, 1)
				
				//cargo tabla de creditos de los paticipes
				CreditosActivosParticipe(id_participe, 1)
				
			})
			.fail(function() {
			    console.log("error");
			});
		}
}






//cargo tabla de aportes de los participes
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




//cargo tabla de creditos de los paticipes
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




function SimulacionCredito()
{
	$("#myModalSimulacion").modal();
	
	
	// executo el metodo luego de 1 segundo para que primero se termine de abrir el modal
	
	//setTimeout(function(){
		// CARGO INFORMACION DEL PARTICIPE
		 InfoParticipe();
		
	//},1000);

}


$("#myModalSimulacion").on("hidden.bs.modal", function () {
	$("#monto_credito").val("");
	$("#tipo_credito").val("");
	$("#fecha_corte").val("");
	$("#select_cuotas").html("");
	$("#tabla_amortizacion").html("");
	$("#info_solicitud").html("");
	$("#capacidad_de_pago_participe").html("");
	$("#capacidad_pago_garante").html("");
	if (modal==0) document.getElementById("cuerpo").classList.remove('modal-open');
	
});





//METODO PARA INFORMACION DEL PARTICIPE

function InfoParticipe()
{
	

	// CONSULTO INFORMACION BASICA DEL PARTICIPE Y LA PONGO EN EL MODAL
	
	// INSTANCIO MI MODAL ABIERTO PARA PODER USARLO
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
		
		// CARGO INFORMACION DEL PARTICIPE EN MODAL
		modal.find("#info_participe").html(x);
		
		// CAPTURO EL MONTO CUENTA INDIVIDUAL
		var limite=document.getElementById("cuenta_individual").innerHTML;
		
		// EXTRAIGO EL MONTO
		var elementos=limite.split(" : ");
		limite=elementos[1];
		disponible_participe=limite;
		console.log("Cta Individual: "+limite);
		
		// CAPTURO EL ESTYLO DE LA CLASE PARA VERIFICAR SI ACCEDE O NO AL CREDITO
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





// FUNCION PARA PARAMETRIZACION DE SIMULACION EL CREDITO
function TipoCredito()
{   
	
	// CAPTURO EL TIPO DE CREDITO EN LA VARIABLE INTERES
	var interes=$("#tipo_credito").val();
	console.log(interes+"===>TIPO CREDITO");
	
	
	
	// HTML DEL INPUNT MONTO
	var select_monto='<div class="col-xs-6 col-md-3 col-lg-3 ">'+
            		'<div class="form-group">'+
                		'<label for="monto_credito" class="control-label">Monto Crédito:</label>'+
              			'<input type=number step=10 class="form-control" id="monto_credito" name="monto_credito"">'+
                        '<div id="mensaje_monto_credito" class="errores"></div>'+
                 	'</div>'+
             	'</div>';
	
	// MUESTRO EN LA VISTA EL MONTO
	$("#monto_del_credito").html(select_monto);
	// VACIO COMBO BOX DE LAS CUOTAS
	$("#select_cuotas").html("");
	// VACIO MONTO DEL CREDITO
	$("#monto_credito").val("");
	// VACIO TABLA DE AMORTIZACION
	$("#tabla_amortizacion").html("");
	
	
	// VACIO CAPACIDAD DE PAGO DEL GARANTE
	$("#capacidad_pago_garante").html("");
	
	renovacion_credito=false;
	garante_seleccionado=false;
	ci_garante="";
	
	
	
	// CAPACIDAD DE PAGO DEL PARTICIPE
	if(interes!="")
		{
		
		// SI BIENE EL TIPO DE CREDITO ASIGNO VALOR AL BOTON CAPACIDAD DE PAGO PARTICIPE PARA NUEVO MODAL CAPCAIDAD D PAGO PARTICIPE 
		
		var boton='<div class="col-xs-6 col-md-3 col-lg-3 text-center">'+
		'<div class="form-group">'+
    		'<label for="monto_credito" class="control-label">Capacidad de pago Participe:</label>'+
    		'<button align="center" class="btn bg-olive" title="Análisis crédito"  onclick="AnalisisCreditoParticipe()"><i class="glyphicon glyphicon-new-window"></i></button>'+
  			'<div id="mensaje_sueldo_participe" class="errores"></div></div></div>';
		
		// MUESTRO EN LA VISTA EL BOTON CAPACIDAD DE PAGO	
		$("#capacidad_de_pago_participe").html(boton);
		
		}
	else
		{
		
		// VACIO TODA LA PARAMETRIZACION DEL CREDITO SI NO HAY NINGUN TIPO DE CREDITO
		
		$("#capacidad_de_pago_participe").html("");
		$("#select_cuotas").html("");
		$("#monto_credito").val("");
		$("#monto_del_credito").html("");
		}
	
	
	
	// HTML PARA ASIGNAR GARANTE
	
	var bci="<label for=\"cedula_garante\" class=\"control-label\">Añadir garante:</label>" +
			"<div class=\"input-group\">"
          +"<input type=\"text\" data-inputmask=\"'mask': '9999999999'\" class=\"form-control\" id=\"cedula_garante\" name=\"cedula_garante\" placeholder=\"C.I.\">"
          +"<div id=\"mensaje_cedula_garante\" class=\"errores\"></div>"
		  +"<span class=\"input-group-btn\">"      			
          +"<button type=\"button\" class=\"btn btn-primary\" id=\"buscar_garante\" name=\"buscar_garante\" onclick=\"BuscarGarante()\">"
          +"<i class=\"glyphicon glyphicon-plus\"></i>"
          +"</button>"
          +"<button type=\"button\" class=\"btn btn-danger\" id=\"borrar_cedula\" name=\"borrar_cedula\" onclick=\"BorrarCedulaGarante()\">"
          +"<i class=\"glyphicon glyphicon-arrow-left\"></i>"
          +"</button>"
          +"</span>"
          +"</div>";
	
	
	
	// LA VARIABLE INTERES CONTIENE EL NOMBRE DEL TIPO DE CREDITO SELECCIONADO EN EL COMBO BOX
	if(interes=="ORD")
		{
		
		// VACIO LOS CREDITOS A RENOVAR
		$('#info_credito_renovar').html("");
		// MUESTRO LA INFORMACION PARA ASIGNAR UN GARANTE 
		$('#info_garante').html(bci);
		
		// AGREGO LIBRERIA INPUT MASK PARA CEDULA GARANTES
		$(":input").inputmask();
		$('#cedula_garante').keypress(function(event){
			  if(event.keyCode == 13){
				  console.log("garante")
			    $('#buscar_garante').click();
			  }
			});
		
		}
	else if(interes=="PH")
		{
		// CREO HTML PARA VALIDACION DEL CREDITO HIPOTECARIO
		
		var tipo_credito_hipotecario="<label for=\"cedula_garante\" class=\"control-label\">Modalidad:</label>" +
		"<select name=\"tipo_credito_hipotecario\" id=\"tipo_credito_hipotecario\"  class=\"form-control\" onchange=\"ModalidadCreditoHP()\">"+
		"<option value=\"\" selected=\"selected\">--Seleccione--</option>"+
		"<option value=\"1\" >COMPRA DE BIEN O TERRENO</option>"+
		"<option value=\"2\" >MEJORAS Y/O REPAROS</option>"
		"<div id=\"mensaje_tipo_hipotecario\" class=\"errores\"></div>";
		// MUESTRO HTML EN LA VISTA EN LA PARTE DEL GARANTE
		$('#info_garante').html(tipo_credito_hipotecario);
		}
	else
		{
		
		// CREDITO EMERGENTE
		
		
		
		$('#info_credito_renovar').html("");
		$('#info_garante').html("");
		
		//var monto="Cta Individual : "+disponible_participe;
		
		if (disponible_participe < 150)
		{
		document.getElementById("disponible_participe").classList.remove('bg-olive');
		document.getElementById("disponible_participe").classList.add('bg-red');
		}
		//$("#cuenta_individual").html(monto);
		}
	
}



	
	
	// METODO ANALISIS CAPACIDAD DE PAGO
	
	function AnalisisCreditoParticipe()
	{
		
		
		$("#select_cuotas").html("");
		// VACIO MONTO DEL CREDITO
		$("#monto_credito").val("");
		// VACIO TABLA DE AMORTIZACION
		$("#tabla_amortizacion").html("");
		
		
		// ABRO MI MODAL DEL ANALISI
		
		$("#myModalAnalisis").modal();
		
		// MANDO GIF DE CARGA DEL MODAL
		swal({
			  icon: "view/images/capremci_load.gif",
			  buttons: false,
			  closeModal: false,
			  allowOutsideClick: false
			});
		
		// ASIGNO ATRIBUTOS AL BOTON ENVIAR CAPACIDAD DE PAGO
		
		var boton_enviar='<button type="button" id="enviar_capacidad_pago" name="enviar_capacidad_pago" class="btn btn-primary" onclick="EnviarCapacidadPagoParticipe()"><i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>'
			$("#boton_capacidad_pago").html(boton_enviar);
		
		// CAPTURO EL TIPO DE CREDITO SELECCIONADO EN MI COMBO BOX
		var interes=$("#tipo_credito").val();
		var tipo_credito="";
		
		tipo_credito=interes;
		console.log(tipo_credito);
		
		
		// CONSULTO EL VALOR MENSUAL DE CUOTAS DE CREDITOS QUE TIENE EL PARTICIPE
		var ciparticipe=$('#cedula_participe').val();
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=cuotaParticipe',
		    type: 'POST',
		    data: {
		    	cedula_participe:ciparticipe,
		    	tipo_credito:tipo_credito
		    },
		})
		.done(function(x) {
			x=x.trim();
			console.log("cuota :"+x);
			
			// PASO VALOR CUOTA A METODO PARA MOSTRAR EN LA VISTA
			CuotaVigente(x);
			
			swal({
				  text:" ",
			      icon: "success",
			      buttons: false,
			      timer: 1000
			    });
			
			
			
			
			if(x!=0)
			{
			
				// SI ENTRA AQUI ES XQ TIENE CREDITOS PARA RENOVAR
				
				renovacion_credito=true;
				
				
				var limite=document.getElementById("cuenta_individual").innerHTML;
				var elementos=limite.split(" : ");
				limite=elementos[1];
				//var lista=document.getElementById("disponible_participe").classList;
				
				$("#monto_credito").val(limite);
				
				
				
				// METODO PARA CAPTURAR LOS CREDITOS A RENOVAR
				SeleccionarCreditoRenovacion();
				
				
			}
				
			
			
			// IMPRIMI TRUE O FALSO SI TIENE CREDITOS PARA RENOVAR
			console.log(renovacion_credito);
			
		})
		.fail(function() {
		    console.log("error");
		});
		
	}

	
	//MODAL ANALISIS DE CAPACIDAD DE PAGO
	
	$("#myModalAnalisis").on("hidden.bs.modal", function () {
		
		var modal = $('#myModalAnalisis');
		modal.find("#sueldo_liquido").val("");
		modal.find("#cuota_vigente").val("");
		modal.find("#fondos").val("");
		modal.find("#decimos").val("");
		modal.find("#rancho").val("");
		modal.find("#ingresos_notarizados").val("");
		document.getElementById("cuerpo").classList.add('modal-open');
		console.log("analisis closed");
	});
	
	
	// PASO EL VALOR DE LA CUOTA AL MODAL Y LO IMPRIMO

	function CuotaVigente(cuota_credito)
	{
		var modal = $('#myModalAnalisis');
		modal.find("#cuota_vigente").val(cuota_credito);
		SumaIngresos();
	}

	
	// SUMO TODOS LOS INGRESOS

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

	}
	
	
	// METODO AL DAR CLIC EN  ACPETAR CAPACIDAD DE PAGO
	function EnviarCapacidadPagoParticipe()
	{
		
		// CAPTURO EL TOTAL DE INGRESOS MENSUALES
		
		var total_ingresos=$("#total_ingreso").html();
		console.log(total_ingresos);
		
		
		
		var capacidad_pago='<div class="col-xs-6 col-md-3 col-lg-3 text-center">'+
		'<div class="form-group">'+
		'<label for="monto_credito" class="control-label">Capacidad de pago Participe:</label>'+
		'<div id="mensaje_sueldo_participe" class="errores"></div>'+
		'<div class="input-group">'+
		'<input type=number step=1 class="form-control" id="sueldo_participe" name="sueldo_participe" style="background-color: #FFFFF;" readonly>'
		 +'<span class="input-group-btn">'      			
	     +'<button type="button" class="btn bg-olive" id="nueva_capacidad_pago" name="nueva_capacidad_pago" onclick="AnalisisCreditoParticipe()">'
	     +'<i class="glyphicon glyphicon-refresh"></i>'
	     +'</button>'
	     +'</span>'+
	     '</div>'+
		'</div></div>';
		
		// MUESTRO EL HTML PARA INGRESO DE CAPACIDAD DE PAGO DEL PARTICIPE
		$("#capacidad_de_pago_participe").html(capacidad_pago);
		// MUESTRO EL TOTAL DE INGRESOS
		$("#sueldo_participe").val(total_ingresos);
		// CIERRO EL MODAL CE CAPACIDAD DE PAGO
		$("#cerrar_analisis").click();
		
	}
	
	
	
	// METODO PARA CAPTURAR LOS CREDITOS A RENOVAR
	function SeleccionarCreditoRenovacion()
	{
		var interes=$('#tipo_credito').val();
		//$('#cerrar_renovar_credito').click();
		console.log(id_participe+"===id_participe");
		
		
		// consulto creditos a renovar
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=GetInfoCreditoRenovar',
		    type: 'POST',
		    data: {
		    	   id_participe: id_participe,
		    	   tipo_creditos: interes
		    },
		})
		.done(function(x) {
			
			// IMPRIMO EN LA VISTA LOS CREDITOS A RENOVAR
			$('#info_credito_renovar').html(x);
			
			
		})
		.fail(function() {
		    console.log("error");
		});
	}
	
	
	
	
	
	
	
	
	
/// BUSCO EL MAXIMO DE CUOTAS

	function GetCuotas()
	{
		var garante_pago=true;
		var ciparticipe=$('#cedula_participe').val();
		var monto=$("#monto_credito").val();
		var sueldo_participe=$("#sueldo_participe").val();

		
		// redondeo el valor de 10 en 10
		Redondeo(monto);
		
		monto=$("#monto_credito").val();
		var interes=$("#tipo_credito").val();
		var limite="";
		
		
		if(interes=="PH")
		{
				limite=$("#cuenta_individual2").text();
				//limite= document.getElementById("cuenta_individual2").innerHTML;	
		}
		else
		{
			if(renovacion_credito==true)
				{
				
				
				limite=$("#cuenta_individual").text();
				
				
				}
			else
				{
				limite=$("#cuenta_individual").text();
				
			    }
			
			
		}		
		
		// EXTRAIGO EL VALOR
		var elementos=limite.split(" : ");
		limite=elementos[1];
		
		
		
		var total_saldo_renovar=$("#total_saldo_renovar").text();
		
		
		
		if(interes=="EME"){
			
			if (typeof total_saldo_renovar == "undefined"){
				
				total_saldo_renovar=0;
			}
			 
			
			
			
			var total_saldo_creditos=$("#capitaL_creditos").text();
			var elementos=total_saldo_creditos.split(" : ");
			total_saldo_creditos=elementos[1];
		
			
			total_saldo_creditos=parseFloat(total_saldo_creditos)-parseFloat(total_saldo_renovar);
			limite=parseFloat(limite)-parseFloat(total_saldo_creditos);
			limite=Math.round(Math.round(limite * 1000) / 10) / 100;
			
			
			
			
		}
		
		
		
		
		
		// EXTRAIGO LAS CLASES PARA VER SI HAY ERROR
		var lista=document.getElementById("disponible_participe").classList;
		lista=lista.value;
		
		
		
		// inicio validaciones
		
		if(interes==""){
			$("#mensaje_tipo_credito").notify("Seleccione Tipo Crédito",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		}
		
		if(sueldo_participe===undefined) {
			
			sueldo_participe="";
		}
		
		if(sueldo_participe==""){
			$("#mensaje_sueldo_participe").notify("Ingrese Capacidad de Pago",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		}
		
		
		// PARA CREDITOS CON GARANTE
		
		if(garante_seleccionado==true)
		{
			
			
			
			// CAPTURO EL LIITE AL QUE TIENE EL GARANTE
			var limite_garante=$("#monto_garante_disponible").text();
            var elementos1=limite_garante.split(" : ");
			limite_garante=elementos1[1];
			
			// SUMO EL LIMITE DEL GARANTE MAS EL DEL DEUDOR
			limite=parseFloat(limite)+parseFloat(limite_garante);
			
			
			// CAPTURO EL SUELDO DEL GARANTE
			var sueldo_garante=$("#sueldo_garante").val();

			if(sueldo_garante===undefined) {
				
				sueldo_garante="";
			}
			if(sueldo_garante=="")
			{
			garante_pago=false;	
			
			$("#mensaje_sueldo_garante").notify("Ingrese Capacidad de Pago Garante",{ position:"buttom left", autoHideDelay: 2000});
			return false;
			
			}
		}
		
		// TERMINA PARA CREDITOS CON GARANTE
		
		
		
		
		if(monto==""){
			$("#mensaje_monto_credito").notify("Ingrese Monto",{ position:"buttom left", autoHideDelay: 2000});
				return false;
		}
		
		if(parseFloat(monto)<150){
			$("#mensaje_monto_credito").notify("Monto debe ser mayor o igual a $150",{ position:"buttom left", autoHideDelay: 2000});
			$("#monto_credito").val(150);
				return false;
		}
		
		
		
		if(parseFloat(monto)>parseFloat(limite)){
			$("#mensaje_monto_credito").notify("Monto máximo $"+limite,{ position:"buttom left", autoHideDelay: 2000});
			$("#monto_credito").val(limite);
				return false;
		}
		
		
		var total_saldo_renovar=$("#total_saldo_renovar").text();
		
		
		if (typeof total_saldo_renovar == "undefined"){
			
			total_saldo_renovar=150;
			
			if(parseFloat(monto) < parseFloat(total_saldo_renovar)){
				
				
				$("#mensaje_monto_credito").notify("Monto mínimo $ "+total_saldo_renovar,{ position:"buttom left", autoHideDelay: 2000});
				$("#monto_credito").val(total_saldo_renovar);
				return false;
			}
			
			
		}else{
			
			
			if(parseFloat(monto) < parseFloat(total_saldo_renovar)){
			
				var residuo=total_saldo_renovar%10;
				total_saldo_renovar=parseFloat(total_saldo_renovar)-parseFloat(residuo);	
				total_saldo_renovar=total_saldo_renovar+10;
				
				$("#mensaje_monto_credito").notify("Monto mínimo $ "+total_saldo_renovar,{ position:"buttom left", autoHideDelay: 2000});
				$("#monto_credito").val(total_saldo_renovar);
				return false;
			}
		}
		
		
		
		
		
		
		// para el emergente
		if(interes=="EME" && parseFloat(monto)>7000){
			$("#mensaje_monto_credito").notify("Monto máximo crédito Emergente es $7000",{ position:"buttom left", autoHideDelay: 2000});
			$("#monto_credito").val(7000);
				return false;
		}
		
		
		
		console.log(sueldo_participe+"===>sueldo participe");
		console.log("LIMITE CREDITO "+limite);
		
		
		
		
		
		if(monto!="" && parseFloat(monto)>=150 && parseFloat(monto)<=parseFloat(limite) && interes!="" && garante_pago && sueldo_participe!="")
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
				
					swal({
						  title: "Simulación de Crédito",
						  text: "Cargando tabla de amortización",
						  icon: "view/images/capremci_load.gif",
						  buttons: false,
						  closeModal: false,
						  allowOutsideClick: false
						});
					
					
					
					// SI NO TIENE GARANTE
					if (garante_seleccionado==false)
						{
						
						console.log("sin garantias");
						
						$.ajax({
						    url: 'index.php?controller=SimulacionCreditos&action=GetCuotas',
						    type: 'POST',
						    data: {
						    	monto_credito:monto,
						    	cedula_participe:ciparticipe,
						    	sueldo_participe:sueldo_participe,
						    	tipo_credito:interes
						    	
						    },
						})
						.done(function(x) {
							
							
							console.log(x);
							
							// converto en array json
							x=JSON.parse(x);
							
							
							// imprimo el maximo y minimo de cuotas para el credito
							$("#select_cuotas").html(x[1]);
							
							// imprimo el valor maximo a adquirir el credito
							$("#monto_credito").val(x[0]);
							
							
							// genero tabla de amortizacion
							SimularCredito();
							
							
						})
						.fail(function() {
						    console.log("error");
						});
					
						
						}
					
					else
						{
					
						
						// SI TIENE GARANTE
						console.log("con garantias");
						
						$.ajax({
						    url: 'index.php?controller=SimulacionCreditos&action=GetCuotasGarante',
						    type: 'POST',
						    data: {
						    	monto_credito:monto,
						    	cedula_participe:ciparticipe,
						    	sueldo_participe:sueldo_participe,
						    	tipo_credito:interes,
						    	cedula_garante:ci_garante,
						    	sueldo_garante:sueldo_garante
						    	
						    },
						})
						.done(function(x) {
							
							// converto en array json
							x=JSON.parse(x);
							
							// imprimo el maximo y minimo de cuotas para el credito
							$("#select_cuotas").html(x[1]);
							
							// imprimo el valor maximo a adquirir el credito
							$("#monto_credito").val(x[0]);
							
							
							// si el pago del garante es 0
							if(x[2]==0)
							{
								document.getElementById("sueldo_garante").style= "background-color: #F5B7B1";
							}
							else
								{
								document.getElementById("sueldo_garante").style= "background-color: #82E0AA";
								capacidad_pago_garante_suficiente=true;
							}
							
							SimularCredito();
							
							
						})
						.fail(function() {
						    console.log("error");
						});
						}
					
					
					
					
				
				}
			
			}
		
	}

	
	// REDONDE CANTIDADES DE 10 EN 10

	function Redondeo(monto)
	{
		
		monto=$("#monto_credito").val();
		
		if(monto==""){
			
			monto =0;
		}
		
		var residuo=monto%10;
		
		monto=monto-residuo;
			
		
		if(monto==0){
			
			
		}else{
			
		$("#monto_credito").val(monto);
		}
	}
	
	
	// genero tabla de amortizacion

	function SimularCredito()
	{
		var monto=$("#monto_credito").val();
		var interes=$("#tipo_credito").val();
		
		
		//para simulador
		if(sin_solicitud==true) solicitud=0;
		
		var cuota_credito=$("#cuotas_credito").val();
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=SimulacionCredito',
		    type: 'POST',
		    data: {
		    	monto_credito:monto,
		    	tipo_credito:interes,
		    	plazo_credito:cuota_credito,
		    	//para ver si va o no a renovar
		    	renovacion_credito:renovacion_credito,
		    	id_solicitud:solicitud,
		    	avaluo_bien:avaluo_bien_sin_solicitud
		    },
		})
		.done(function(x) {
			$("#tabla_amortizacion").html(x);
			
			
			if(garante_seleccionado==true)
				{
				var cuota=$('#cuota_a_pagar2').html();
				var desgravamen=$('#desgravamen2').html();
				
				var sueldo_garante=$('#sueldo_garante').val();
				sueldo_garante=sueldo_garante/2;
				cuota=cuota.replace(",", "");
				desgravamen=desgravamen.replace(",", "");
				cuota=parseFloat(cuota)-parseFloat(desgravamen);
				
				if(parseFloat(cuota)>parseFloat(sueldo_garante))
				{
						document.getElementById("sueldo_garante").style= "background-color: #F5B7B1";
				}
				else
				{
						document.getElementById("sueldo_garante").style= "background-color: #82E0AA";
				}
					
				}
			
			    swal("Tabla cargada", {
			      icon: "success",
			      buttons: false,
			      timer: 1000
			    });
			
		})
		.fail(function() {
		    console.log("error");
		});
	}


	
	
	


$("#myModalCreditosActivos").on("hidden.bs.modal", function () {
	
document.getElementById("cuerpo").classList.add('modal-open');
console.log("CERRADO CREDITOS");
var credito_renovar=$('#info_credito_renovar').html();
if(credito_renovar=="")
	{
	$("#tipo_credito").val("");
	}
	
});






$("#myModalAvaluo").on("hidden.bs.modal", function () {
	
    $("#avaluo_bien").val("");
	document.getElementById("cuerpo").classList.add('modal-open');
});



$("#myModalInsertar").on("hidden.bs.modal", function () {
	var modal = $('#myModalInsertar');
	modal.find("#observacion_confirmacion").val("");
	modal.find("#codigo_confirmacion").val("");
	document.getElementById("cuerpo").classList.add('modal-open');
	
});




function AgregarGaranteRenovacion()
{
	var bci="<label for=\"cedula_garante\" class=\"control-label\">Añadir garante:</label>" +
	
	"<div class=\"input-group\">"
  +"<input type=\"text\" data-inputmask=\"'mask': '9999999999'\" class=\"form-control\" id=\"cedula_garante\" name=\"cedula_garante\" placeholder=\"C.I.\">"
  +"<div id=\"mensaje_cedula_garante\" class=\"errores\"></div>" 
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
	$(":input").inputmask();
	$('#cedula_garante').keypress(function(event){
		  if(event.keyCode == 13){
			  console.log("garante")
		    $('#buscar_garante').click();
		  }
		});
}

function BorrarCedula()
{
	$('#cedula_participe').val("");
}

function BorrarCedulaGarante()
{
	$('#cedula_garante').val("");
}





// sellecion para credito hipotecario
function ModalidadCreditoHP()
{
	
	
	// detecto que el participe tenga registrada una solicitud
	if(!sin_solicitud)
		{
		var tipo_ph=$("#tipo_credito_hipotecario").val();
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=GetAvaluoHipotecario',
		    type: 'POST',
		    data: {
		    	id_solicitud: solicitud,
		    	tipo_credito_hipotecario: tipo_ph
		    },
		})
		.done(function(x) {
			$('#info_garante').html(x);
			
			
		})
		.fail(function() {
		    console.log("error");
		});
		}
	else
		{
		$("#myModalAvaluo").modal();
		}
	
}

function GetCreditosActivos(id)
{
	console.log("CreditosActivos==>"+id);
	$('#tabla_creditos_activos').html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=CreditosActivosParticipeRenovacion',
	    type: 'POST',
	    data: {
	    	   id_participe: id,
	    	   page: 1
	    },
	})
	.done(function(x) {
		$('#tabla_creditos_activos').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}



function SimulacionCreditoSinSolicitud()
{
	$("#myModalSimulacion").modal();
	//InfoParticipe();
	sin_solicitud=true;
}

function RenovacionCredito()
{
	renovacion_credito=true;
	$("#myModalSimulacion").modal();
	//InfoParticipe();
}

function CambiarCreditoRenovacion()
{
	$('#info_garante').html("");
	$("#myModalCreditosActivos").modal();
	GetCreditosActivos(id_participe);
}

function QuitarCreditoRenovacion()
{
	$('#info_credito_renovar').html("");
	$("#tipo_credito").val("");	
}

function QuitarGarante()
{
	
	TipoCredito();
}




function EnviarAvaluoBien()
{
	var tipo_ph=$("#tipo_credito_hipotecario").val();
	var avaluo_bien = $("#avaluo_bien").val();
	avaluo_bien_sin_solicitud=avaluo_bien;
	var monto_maximo=0;
	 
         if(tipo_ph==1)
         {
             monto_maximo=parseFloat(avaluo_bien)*0.8;
             if(monto_maximo>100000) monto_maximo=100000;
         }
         else
         {
             monto_maximo=parseFloat(avaluo_bien)*0.5;
             if(monto_maximo>45000) monto_maximo=45000;
         }
         
	var html='<table>'+
        '<tr>'+
        '<td><font size="3">Avalúo del bien : '+avaluo_bien+'</font></td>'+
        '</tr>'+
        '<tr>'+
        '<td><font size="3" id="cuenta_individual2">Monto máximo a recibir : '+monto_maximo+'</font></td>'+
        '</tr>'+
        '<tr>'+
        '<td>'+
        '<span class="input-group-btn">'+
        '<button  type="button" class="btn bg-olive" title="Cambiar Modalidad" onclick="TipoCredito()"><i class="glyphicon glyphicon-refresh"></i></button>'+
        '</span>'+
        '</td>'+
        '</tr>'+
        '</table>';	
	
	
	$('#info_garante').html(html);
	$("#cerrar_avaluo").click();
}



function EnviarCapacidadPagoGarante()
{
	var total_ingresos=$("#total_ingreso").html();
	console.log(total_ingresos);
	var capacidad_pago='<div class="col-xs-6 col-md-3 col-lg-3 text-center">'+
	'<div class="form-group">'+
	'<label for="monto_credito" class="control-label">Capacidad de pago Garante:</label>'+
	'<div id="mensaje_sueldo_garante" class="errores"></div>'+
	'<div class="input-group">'+
	'<input type=number step=1 class="form-control" id="sueldo_garante" name="sueldo_garante" style="background-color: #FFFFF;" readonly>'
	 +'<span class="input-group-btn">'      			
     +'<button type="button" class="btn bg-olive" id="nueva_capacidad_pago" name="nueva_capacidad_pago" onclick="AnalisisCreditoGarante()">'
     +'<i class="glyphicon glyphicon-refresh"></i>'
     +'</button>'
     +'</span>'+
     '</div>'+
	'</div></div>';
	$("#capacidad_pago_garante").html(capacidad_pago);
	$("#sueldo_garante").val(total_ingresos);
	$("#cerrar_analisis").click();
}


function AnalisisCreditoGarante()
{

	$("#select_cuotas").html("");
	// VACIO TABLA DE AMORTIZACION
	$("#tabla_amortizacion").html("");
	
	
	$("#myModalAnalisis").modal();
	swal({
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
	var boton_enviar='<button type="button" id="enviar_capacidad_pago_garante" name="enviar_capacidad_pago_garante" class="btn btn-primary" onclick="EnviarCapacidadPagoGarante()"><i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>'
		$("#boton_capacidad_pago").html(boton_enviar);
		
	var ciparticipe=$('#cedula_participe').val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=cuotaGarante',
	    type: 'POST',
	    data: {
	    	cedula_participe:ci_garante
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log("cuota :"+x);
		CuotaVigente(x);
		swal({
			text:" ",
	      icon: "success",
	      buttons: false,
	      timer: 1000
	    });
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}



function BuscarGarante()
{
	var ciparticipe=$('#cedula_garante').val();
	ci_garante=ciparticipe;
	var cicredito=$('#cedula_credito').html();
	cicredito=cicredito.split(" : ");
	cicredito=cicredito[1];	
	
	
	
	if(ciparticipe=="" || ciparticipe.includes('_'))
		{
		
		$("#mensaje_cedula_garante").notify("Ingrese Cedula del Garante",{ position:"buttom left", autoHideDelay: 2000});
		return false;
		
		
		}
	else
		{
	    if (ciparticipe==cicredito)
	    {
	    	$("#mensaje_cedula_garante").notify("Cedula no Válida",{ position:"buttom left", autoHideDelay: 2000});
			$('#cedula_garante').val("");
	    	return false;
	    	
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
				
				x=x.trim();
				// si respuesta es Participe no encontrado pasa al caso contrario
				if(!(x.includes("Participe no encontrado")))
				{
					
				// si el garante se encuntra activo en otro credito	
					if(x=="Garante no disponible")
					{
						swal({
					  		  title: "Advertencia!",
					  		  text: "El participe ya es garante activo",
					  		  icon: "warning",
					  		  button: "Aceptar",
					  		});
					}
					else
					{
						// imprimo la informacion del garante
						$("#info_garante").html(x);
						
						// capturo la edad del participe
						var edad_garante=$("#edad_garante").html();
						edad_garante=edad_garante.split(" : ");
						edad_garante=edad_garante[1].split(", ");
						edad_garante=edad_garante[0].split(" ");
						edad_garante=edad_garante[0];	
						console.log(edad_garante);
						
						// capturo la cuenta individual del participe
						var limite=document.getElementById("cuenta_individual").innerHTML;
						var elementos=limite.split(" : ");
						limite=elementos[1];
						
						// captuto el disponible del garante
						var limite_garante=document.getElementById("monto_garante_disponible").innerHTML;
						elementos=limite_garante.split(" : ");
						limite_garante=elementos[1];
						console.log(limite_garante);
						
						// sumo los montos
						var limite_total=parseFloat(limite_garante)+parseFloat(limite);
						limite_total=limite_total.toString()
						elementos=limite_total.split(".");
						elementos[1]=elementos[1].substring(0, 2);
						
						// total limite para decimales con punto
						limite_total=elementos[0]+"."+elementos[1];
						
						
						// verifico si los dos tienen los 3 ultimos aportes
						var aportes=document.getElementById("aportes_participes");
						var aportes_garante=document.getElementById("aportes_garante");
						
						
						
						// si tienen los aportes doy paso al credito on garantia
						if (limite_total>=150 && edad_garante<75 && aportes==null && aportes_garante==null)
						{
							document.getElementById("disponible_participe").classList.remove('bg-red');
							document.getElementById("disponible_participe").classList.add('bg-olive');
							garante_seleccionado=true;
							var pago_garante='<div class="col-xs-6 col-md-3 col-lg-3 text-center">'+
							'<div class="form-group">'+
					    		'<label for="monto_credito" class="control-label">Capacidad de pago Garante:</label>'+
					    		'<button align="center" class="btn bg-olive" title="Análisis crédito"  onclick="AnalisisCreditoGarante()"><i class="glyphicon glyphicon-new-window"></i></button>'+
					  			'<!--<input type=number step=1 class="form-control" id="sueldo_participe" name="sueldo_participe" style="background-color: #FFFFF;">  -->'+
					  			'<div id="mensaje_sueldo_garante" class="errores"></div></div></div>';
							
							$("#capacidad_pago_garante").html(pago_garante);
						}
						
						if(edad_garante<75 && aportes_garante!=null)
						{
							document.getElementById("disponible_participe").classList.remove('bg-olive');
							document.getElementById("disponible_participe").classList.add('bg-red');
							swal({
						  		  title: "Advertencia!",
						  		  text: "El participe no cumple las condiciones para ser garante",
						  		  icon: "warning",
						  		  button: "Aceptar",
						  		});
						}
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



function ConfirmarCodigo()
{
	$("#info_credito_confirmar").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	var monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var cuota_credito=$("#cuotas_credito").val();
	var ciparticipe=$('#cedula_participe').val();
	var nombre_participe=$("#nombre_participe_credito").html();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=genera_codigo',
	    type: 'POST',
	    data: {
	    	tipo_credito:interes
	    },
	})
	.done(function(x) {
		console.log(x);
		x=JSON.parse(x);
		var informacion="<h3>Se procedera a generar un crédito para "+nombre_participe+"</h3>" +
				"<h3>Con cédula de identidad número "+ciparticipe+"</h3>" +
				"<h3>Por el monto de "+monto+" USD</h3>" +
				"<h3>A un plazo de "+cuota_credito+" meses con interes del "+x[1]+"%</h3>" +
				"<h3>Para confirmar ingrese el siguiente código</h3>" +
				"<h2 id=\"codigo_generado\">"+x[0]+"</h2>";
		$("#info_credito_confirmar").html(informacion);	
	})
	.fail(function() {
	    console.log("error");
	});
}

function GuardarCredito()
{
console.log("Guardar Credito");
if(garante_seleccionado)
	{
	console.log(capacidad_pago_garante_suficiente+"=====>boolean");
	if (capacidad_pago_garante_suficiente)
	{
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
	else{
		swal({
			  title: "Advertencia!",
			  text: "El garante no tiene la capacidad de pago suficiente",
			  icon: "warning",
			  button: "Aceptar",
			});
	}
	}
else
	{
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

}

function SubirInformacionCredito()  //proceso para los registros del credito
{
	var monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var cuota_credito=$("#cuotas_credito").val();
	var ciparticipe=$('#cedula_participe').val();
	var observacion=$('#observacion_confirmacion').val();
	id_solicitud=solicitud;
	swal({
		  title: "Crédito",
		  text: "Registrando crédito",
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
	if (!renovacion_credito)
		{
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=SubirInformacionCredito',
		    type: 'POST',
		    data: {
		    	monto_credito: monto,
		    	tipo_credito: interes,
		    	fecha_pago: fecha_corte,
		    	cuota_credito: cuota_credito,
		    	cedula_participe: ciparticipe,
		    	observacion_credito: observacion,
		    	id_solicitud:id_solicitud,
		    	con_garante:garante_seleccionado,
		    	cedula_garante:ci_garante
		    	
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
			  		}).then((value) => {
			  			window.open('index.php?controller=SolicitudPrestamo&action=index5', '_self');
						 });
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
	else
		{
		$.ajax({
		    url: 'index.php?controller=SimulacionCreditos&action=SubirInformacionRenovacionCredito',
		    type: 'POST',
		    data: {
		    	monto_credito: monto,
		    	tipo_credito: interes,
		    	fecha_pago: fecha_corte,
		    	cuota_credito: cuota_credito,
		    	cedula_participe: ciparticipe,
		    	observacion_credito: observacion,
		    	id_solicitud:id_solicitud,
		    	con_garante:garante_seleccionado,
		    	cedula_garante:ci_garante
		    	
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
			  		}).then((value) => {
			  			window.open('index.php?controller=SolicitudPrestamo&action=index5', '_self');
						 });
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
	 	SubirInformacionCredito();
	 }
 else
	 {
	 swal("Código incorrecto");
	 }
}