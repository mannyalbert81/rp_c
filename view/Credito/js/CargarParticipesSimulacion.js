/********* js de vista creditos ******/
var view	= view || {};
view.hdn_id_solicitud	 	= $("#hdn_id_solicitud");
view.hdn_cedula_participes 	= $("#hdn_cedula_participes");
view.hdn_id_participes	 	= $("#hdn_id_participes");
view.hdn_cedula_garante	 	= $("#hdn_cedula_garante");
view.cedula_participes 		= $("#cedula_participe");
view.html_boton_buscar_participe= $("#buscar_participe_boton");
view.boton_buscar_participe		= $("#buscar_participe") || null;
view.tipo_creditos			= $("#ddl_tipo_creditos"); 
view.monto_creditos			= $("#txt_monto_creditos");
view.cuota_creditos			= $("#txt_cuotas_creditos"); 
view.btn_capacidad_pago		= $("#btn_capacidad_pago");
view.capacidad_pago			= $("#txt_capacidad_pago"); 
view.btn_numero_cuotas		= $("#btn_numero_cuotas");
view.capacidad_pago_garante			= $("#txt_capacidad_pago_garante"); 
view.numero_cuotas			= $("#ddl_numero_cuotas"); 
view.btn_generar_simulacion	= $("#btn_generar_simulacion");
view.creditos_productos		= $("#ddl_credito_producto");

/** para valores de tab de capacidad de pago **/
view.sueldo_liquido		= $("#txt_sueldo_liquido");
view.cuota_vigente		= $("#txt_cuota_vigente");
view.valor_fondos		= $("#txt_fondos");
view.valor_decimos		= $("#txt_decimos");
view.valor_rancho		= $("#txt_rancho");
view.ingresos_notarizados		= $("#txt_ingresos_notarizados");
view.total_ingresos		= $("#txt_cuota_pactada");
view.btn_enviar_capacidad_pago	= $("#btn_enviar_capacidad_pago");

/** para valores de tab de capacidad de pago garante **/
view.sueldo_liquido_garante		= $("#txt_sueldo_liquido_garante");
view.cuota_vigente_garante		= $("#txt_cuota_vigente_garante");
view.valor_fondos_garante		= $("#txt_fondos_garante");
view.valor_decimos_garante		= $("#txt_decimos_garante");
view.valor_rancho_garante		= $("#txt_rancho");
view.ingresos_notarizados_garante		= $("#txt_ingresos_notarizados_garante");
view.total_ingresos_garante		= $("#txt_total_ingresos_garante");

/** para valores globales **/
view.global_hay_renovacion	= false;
view.global_hay_garantes	= false;
view.global_hay_solicitud	= false; //SINTAXERROR
view.global_capacidad_pago_garante_suficiente	= false; //SINTAXERROR
view.page_load	= false; //SINTAXERROR
view.global_avaluo_sin_solicitud	= 0; //SINTAXERROR
view.global_hay_reafiliacion	= false;

/** para valores de Solicitud **/
var dataSolicitud	= dataSolicitud || {};

/** para valores de Garantes **/
var dataGarantes	= dataGarantes	|| {};

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

/*** CAMBIOS PARA SIMULACION WEB CAPREMCI ***/
view.hdn_id_solicitud.val(1);	

$(document).ready( function (){
	 

	   
	view.page_load	= true; //VALIDAR ESTA CARGADA
		
	//valida que no haya cuotas en mora por parte del participe
	iniciar_datos_solicitud();
	
	//iniciar eventos de elementos de la vista
	iniciar_eventos_controles();
	
	//iniciar atributos de ciertos elementos en la vista
	iniciar_elementos();
	 
	
		
});



let iniciar_datos_solicitud	= async() => {
	
	try
	{
		//validamos los datos de la vista 
		if( view.hdn_cedula_participes.val() == "" || !view.hdn_cedula_participes.val().length ) throw new Error("SWAL CEDULA NO IDENTIFICADA");
		
		//buscar los tipos de creditos
		let resp = await obtener_tipo_creditos();
							
		//validamos requisitos de Solictud para proceder al credito
		//--si tiene moras
		let misCabeceras = {'Content-Type':"application/json"};
		let data = { 'cedula_participes' : view.cedula_participes.val() };
		data = JSON.stringify(data);
		let miInit = { method: 'POST',
			   headers: misCabeceras,
			   mode: 'cors',
			   cache: 'default',
			   body: data};
		let response	= await fetch('index.php?controller=CargarParticipes&action=validarDatosSolicitud',miInit)
		let result		= await response.json();
		respuesta		= result;
		
		if( respuesta.estatus != undefined && respuesta.estatus == "OK" )
		{
			//agregamos elementos a la vista
			view.html_boton_buscar_participe.html("");
			view.html_boton_buscar_participe.html( '<button type="button" class="btn btn-primary" id="buscar_participe" ><i class="glyphicon glyphicon-search"></i></button>' );
			//establecemos cedula del participe en la vista
			view.cedula_participes.val( view.hdn_cedula_participes.val() ) ;
			//agregamos elemnto al modelo de la pagina
			view.boton_buscar_participe	= $("#buscar_participe");		
			//ENLAZAR EVENTO BUSCAR PARTICIPE CON CLICK
			$('body').on('click',"#buscar_participe",function(){				
				//buscar_datos_creditos();
				obtener_info_participes();
			});	
			
			//ACTIVAR BOTON
			$("#buscar_participe").click();
			
			//ACTIVAR mensaje de valores no son reales
			activar_mensaje_texto_informacion(" Estimado partícipe los valores obtenidos en el simulador son referenciales, la información obtenida  no implica una pre aprobación de crédito o una solicitud formal del mismo ");
			
		}else
		{
			var mensaje = respuesta.mensaje || "ERROR AL PROCESAR LOS DATOS";
			swal({title:"ERROR",text:mensaje,icon:"error",dangerMode:true});
		}
					
	}catch(err)
	{
		console.log(err);
		if( err.message.includes("SWAL") )
		{
			var regex 	= /swal/gi;
			var mensaje = err.message.replace(regex,'') + " \n PROCESO TERMINADO ";
			swal({title:"ERROR",text:mensaje,icon:"error",dangerMode:true});
		}else
		{
			swal({title:"ERROR",text:"Error de sintaxis --> "+err.message, icon:"error",dangerMode:true});
		}		
	}
	
} 

var iniciar_elementos	= function(){
	
	// ESTABLESCO LA MASCARA AL CAMPO CEDULA PARTICIPE
	$(":input").inputmask();
	
	view.btn_generar_simulacion.attr("disabled",true);
	
}

var activar_mensaje_texto	= function(a){
	
	//a es un mensaje
	a	= a || "";
	var divmensaje	= $("#div_pnl_respuesta_informacion");
	var alertHtml = '<div class="alert alert-warning alert-dismissible fade in">'+
    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
    '<strong>Advertencia!</strong>'+a+'</div>';
	
	if( divmensaje.html() == "" )
	{
		divmensaje.html( alertHtml );
	}else
	{
		divmensaje.append( alertHtml );
	}
	
}

var activar_mensaje_texto_informacion	= function(a){
	
	//a es un mensaje
	a	= a || "";
	var divmensaje	= $("#div_pnl_respuesta_informacion");
	var alertHtml = '<div class="alert alert-danger alert-dismissible fade in">'+
    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
    '<strong>Advertencia!</strong>'+a+'</div>';
	
	if( divmensaje.html() == "" )
	{
		divmensaje.html( alertHtml );
	}else
	{
		divmensaje.append( alertHtml );
	}
	
}

var obtener_tipo_creditos	= function(){
	//obtiene datos del tipo de creditos que tiene la entidad
	view.tipo_creditos.empty();
	$.ajax({
		url:'index.php?controller=CargarParticipes&action=obtenerTipoCredito',
		dataType:'json',
		Type:'POST',
		data:null
	}).done(function(x){
		if( x.estatus != undefined && x.estatus == "OK" ){
			datos	= x.data || [];
			view.tipo_creditos.append('<option value="0">--Selecione--</option>');
			$.each(datos,function( index, value ){
				view.tipo_creditos.append('<option value="'+value.codigo_tipo_creditos+'">'+value.nombre_tipo_creditos+'</option>');
			})
		}
		
	}).fail( function( xhr,status,error ){
		view.tipo_creditos.append('<option value="0">--Selecione--</option>');
	})
}

var setValTipoCreditos = function(a){
	//se setea el tipo de credito que solicito en la solicitud de credito
	try{
		a	= a || "";
		switch (a) {
		case "ORDINARIO":
			view.tipo_creditos.val("ORD");
		break;
		case "EMERGENTE":
			view.tipo_creditos.val("EME");
		break;
		case "HIPOTECARIO":
			view.tipo_creditos.val("PH");
		break;				
		case "REFINANCIAMIENTO":
			view.tipo_creditos.val("RF");
		break;					
		case "2x1":
			view.tipo_creditos.val("2x1");
		break;
		case "ACUERDO PAGO":
			view.tipo_creditos.val("AP");
		break;
		default:
			view.tipo_creditos.val('0');
		break;
		}
		
	
	}catch(err){
		console.error('ERROR AL IDENTIFICAR TIPO DE CREDITO DE LA SOLICITUD REALIZADA');
		console.error(err);
		view.tipo_creditos.val('0');
	}
}

var iniciar_eventos_controles	= function(){
	
	//$('[data-toggle="popover"]').popover();
	
	
	
	$("#btn_mostrar_numero_aportes").on('click',function(e){
		//$(this).off(e);
		//obtener_aportes_lista_validacion();
		//obtener_aportes_validacion(this);
	});
	
	$("#div_pnl_aportes_validacion").on('show.bs.collapse',function(){
		obtener_aportes_lista_validacion();
	});
		
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {	    
		//realizar desplazamiento
		/*var href = $(this).attr('href');
	    $('html, body').animate({
	      scrollTop: $(href).offset().top
	    }, 'slow');
	    e.preventDefault();*/
	});
	
	$('body').on('click','#btn_capacidad_pago',function(){
		//$('.nav-tabs a[href="#panel_capacidad"]').tab('show'); //vavegar en tab bootstrap		
		analisis_capacidad_pago();
	});
	
	$('#cedula_participe').keypress(function(event){
		  if(event.keyCode == 13){
			  view.boton_buscar_participe.click();
		  }
	});
	
	//se enlaza evento key up a input que este dentro de un table ... capacidad de pago 
	// 2020-06-15 se realiza cona archivo externo
	$('table :input.suma-capacidad').on('keyup',function(){
		//if( isNaN( parseFloat( $(this).val() ) ) ){ $(this).val("");} //validacion de cada una analizar 
		//sumar_ingresos_capacidad_pago();
	})
	
	//se enlaza evento key up a input que este dentro de un table ... capacidad de pago garante
	$('table :input.suma-capacidad-garante').on('keyup',function(){ 
		sumar_ingresos_capacidad_pago_garante();
	})
	
	$('#btn_enviar_capacidad_pago').on('click', function(){
		enviar_capacidad_pago();
	})
	
	$('body').on('click','#btn_enviar_capacidad_pago_garante', function(){
		evt_enviar_capacidad_pago_garante();
	})
		
	view.btn_numero_cuotas.on('click', function(){
		
		if( validar_valores_simulacion() )
		{
			buscar_numero_cuotas();
		}
		
	})
	
	$( '#'+view.tipo_creditos.attr("id") ).on('change',function(){
		iniciar_datos_simulacion();
	});
	
	$( "#"+view.btn_generar_simulacion.attr("id") ).on('click',function(){
		
		if( validar_valores_simulacion() )
		{
			if( view.numero_cuotas.val() > 0 )
			{
				//REALIZAR una suma de Valor Solicitado contra saldos de creditos
				try{
					var valor_texto = $("span:contains('Capital de créditos')").first().parent().text().split(':')[1];
					var valor_saldo_creditos	= parseFloat(valor_texto);
					var valor_monto_solicitado	= parseFloat(view.monto_creditos.val());
					var valor_recibir	= valor_monto_solicitado - valor_saldo_creditos;
					
					
					//ESTABLECER valores en elemento span
					$("span#span_monto_solicitado").text( roundNumber(valor_monto_solicitado ,2).toFixed(2) ); 
					$("span#span_total_recibir").text( roundNumber(valor_recibir,2) );
					
				}catch(e){}
				
				simular_credito_amortizacion();
			}else
			{
				view.numero_cuotas.notify("Numero de Cuotas No definido",{ position:"buttom left", autoHideDelay: 2000});
			}
			
		}
		
	})
		
	//ESTE EVENTE PErmITE JUGAR CON LOS BOTONES DE COLLAPSED
	$(".botones-expandibles button[data-toggle='collapse']").on('click',function(){
		var tag = $(this);
        var expanded = ( tag.attr("aria-expanded") == undefined ) ? "false" : tag.attr("aria-expanded");
	    if( expanded == "true" )
	    { 
	    	tag.removeClass('btn-info').addClass('btn-default');
	    	
	    }else
	    {
	    	tag.removeClass('btn-default').addClass('btn-info');
	    }
	});
	
	//EVENTO QUE PERMITE VALIDAR CAMBIO DE TIPO PRODUCTO
	$("#ddl_credito_producto").on("change",function(){
		change_tipo_producto_creditos( $(this) );
	})

}

var obtener_info_participes = function(){
		
	if( view.cedula_participes.val() == "" || view.cedula_participes.val().includes('_') ){
		view.cedula_participes.notify("Cedula No Identificado",{ position:"buttom left", autoHideDelay: 2000});
	}else{
		
		//console.log("Cedula Participe -->"+ view.cedula_participes.val());
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=BuscarParticipe',
		    type: 'POST',
		    dataType:'json',
		    data: {
		    	   'cedula': view.cedula_participes.val()
		    },
		})
		.done(function(x) {
			
			// cargos tabla de informacion basica de los participes
			if( x.estatus != undefined && x.estatus == 'OK' ){
				
				$('#div_pnl_participe_encontrado').html( x.html );
				
				view.hdn_id_participes.val( x.id_participes );
				
				// cargo tabla de aportes de los participes
				buscar_aportes_participe();
				
				//cargo tabla de creditos de los paticipes
				buscar_creditos_participe();
				
				//cargar informacion de la solicitud a procesar
				//obtener_informacion_solicitud();
				
				//cargar informacion crediticia con relacion a la solicitud
				obtener_informacion_participe();				
			}
						
		})
		.fail(function() {
		    console.log("error");
		});
	}
}

var buscar_aportes_participe	= function( a){
	
	if( view.hdn_id_participes.val() == undefined ||  view.hdn_id_participes.val() == "0" || view.hdn_id_participes.val() == "" ){
		console.log("REVISAR DATOS DE ENVIO PARA OBTENER DATOS APORTES PARTICIPE");
		return false;
	}
	a = a || 1;
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=AportesParticipe',
	    type: 'POST',
	    dataType:'json',
	    data: {
	    	   id_participe: view.hdn_id_participes.val(),
	    	   page: a
	    },
	}).done(function(x) {
		
		$('#div_pnl_participe_aportes').html( x.html );		
		
	}).fail(function() {
	    console.log("error");
	});
}

var buscar_creditos_participe	= function(a){
	
	if( view.hdn_id_participes.val() == undefined ||  view.hdn_id_participes.val() == "0" || view.hdn_id_participes.val() == "" ){
		console.log("REVISAR DATOS DE ENVIO PARA OBTENER DATOS CREDITOS PARTICIPE");
		return false;
	}
	a = a || 1;
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=CreditosActivosParticipe',
	    type: 'POST',
	    dataType:'json',
	    data: {
	    	   id_participe: view.hdn_id_participes.val(),
	    	   page: a
	    },
	}).done(function(x) {
		
		$('#div_pnl_participe_creditos').html( x.html );		
		
	}).fail(function() {
	    console.log("error");
	});
	
}

var obtener_informacion_solicitud 	= function(){
	
	if( view.hdn_id_solicitud.val() == undefined ||  view.hdn_id_solicitud.val() == "0" || view.hdn_id_solicitud.val() == "" ){
		console.log("REVISAR DATOS DE ENVIO PARA OBTENER DATOS DE SOLICITUD DE CREDITO");
		return false;
	}
	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=InfoSolicitud',
	    type: 'POST',
	    dataType:'json',
	    data: {
	    	id_solicitud:view.hdn_id_solicitud.val()
	    },
	})
	.done(function(x) {
		
		// cargo la informacion de la solicitud en el modal
		$("#div_pnl_info_solicitud").html( x.html );
		
		$(".nav-tabs a[data-toggle=tab]").removeClass("disabledTab"); //habilitar los tab de creditos
		$('.nav-tabs a[href="#panel_info"]').tab('show'); //navegar en tab bootstrap a la pagina principal
		
		//para determinar el tipo de credito solicitado
		try{
			var varTipoCreditos	= x.data.nombre_tipo_credito_solicitud;		
			setValTipoCreditos(varTipoCreditos);			
		}catch(e){console.log('ERROR AL OBTENER TIPO CREDITO DESDE SERVIDOR');}
		
	})
	.fail(function() {
	    console.log("error");
	});
	
}

var obtener_informacion_participe	= function(){
	
	//BUSCAR INFORMACION PARA CREDITOS	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=InformacionCrediticiaParticipe',
	    type: 'POST',
	    dataType:'json',
	    data: {
	    	cedula_participe:view.cedula_participes.val(), 'id_solicitud':view.hdn_id_solicitud.val(), 'id_participes':view.hdn_id_participes.val()
	 	    },
	}).done(function(x) {
		
		//CARGAR INFORMACION DE PARTCIPE CON RELACION A LA SOLICITUD DE CREDITO
		$("#div_pnl_info_participe").html( x.html );
		
		if( x.data != undefined && x.data != null ){
						
			dataSolicitud = x.data;
			
			if( !dataSolicitud.estado_solicitud ){
				
				//mostrar mensaje de Erroress
				dataSolicitud.mensaje_solicitud = dataSolicitud.mensaje_solicitud || 'El participe no puede acceder a un crédito en este momento';
				activar_mensaje_texto( dataSolicitud.mensaje_solicitud );				
			}else
			{
				$(".nav-tabs a[data-toggle=tab]").removeClass("disabledTab"); //habilitar los tab de creditos
				$('.nav-tabs a[href="#panel_info"]').tab('show');
			}
						
			//iniciar_datos_simulacion();
			
			obtener_historial_moras();
			
		}	
		
		
	}).fail(function() {
	    console.log("error");
	});
	
}

var iniciar_datos_simulacion = function(){
	
	//setear valores globales
	view.global_hay_garantes	= false;
	view.global_hay_renovacion	= false;
	dataGarantes	= {};
	
	//validar cuenta individual
	var cuenta_individual	= dataSolicitud.cuenta_individual || 0;
	
	var valor_tipo_credito	= view.tipo_creditos.val();
	console.log("INICIANDO .. start..simulacion");
	console.log("valor tipo credito --> " + valor_tipo_credito);	
	view.cuota_creditos.val("");
	console.log("...vaciamos cuota creditos");
	view.monto_creditos.val("");
	console.log("...vaciamos monto creditos");	
	$("#div_tabla_amortizacion").html("");
	console.log("..vaciamos div con tabla de amortizacion");	
	$("#div_capacidad_pago_garante").addClass("hidden");
	console.log("..vaciamos div con capacidad pago garantes");
	
	if( valor_tipo_credito != "0" ){
		
		//se activa el btn para capacidad de pago
		view.btn_capacidad_pago.attr("disabled",false);
		view.monto_creditos.attr("readonly",false);  
		view.btn_numero_cuotas.attr("disabled",false);
		
		//BUSCAR SI TIENE CREDITOS A RENOVAR
		obtener_creditos_renovacion();	
		
		//BUSCAR TIPO PRODUCTO POR TIPO CREDITO
		obtener_tipo_producto_creditos();
		
		if( valor_tipo_credito == "ORD" )
		{	
			//do stuff
		}else if( valor_tipo_credito == "PH" ){
			//do stuff			
		}else if( valor_tipo_credito == "EME" ){
			//do stuff
		}else
		{			
			console.info("TIPO DE CREDITO NO PARAMETRIZADO");
			view.tipo_creditos.notify( "Tipo Credito no Definido" ,{ position:"buttom left", autoHideDelay: 2000});			
		}
			
		
	}else{
		
		view.monto_creditos.val("");
		view.cuota_creditos.val("");
		//desabilitamos el boton de capacidad de pago
		view.btn_capacidad_pago.attr("disabled",true);
		view.monto_creditos.attr("readonly",true);  
		view.btn_numero_cuotas.attr("disabled",true);
		
		if( view.page_load )
		{
			console.log("..inicializacion de simulacion fallida");
			swal({title:"ERROR DE CARGA", text:"Tuvimos problemas al cargar la informacion por favor Recargue la pagina",icon:"error",dangerMode:true});
		}else
		{
			view.tipo_creditos.notify( "Debe seleccionar un tipo de crédito" ,{ position:"buttom left", autoHideDelay: 2000});	
		}		
		
	}//termina else -- if
	
}

var obtener_tipo_producto_creditos	= function(){
	
	var codigo_tipo_creditos	= view.tipo_creditos.val();
	var tipo_productos	= $("#ddl_credito_producto");
	tipo_productos.empty();
	$.ajax({
		url:"index.php?controller=CargarParticipes&action=cargarProductoCredito",
		type:"POST",
		dataType:"json",
		data:{ 'codigo_tipo_creditos': codigo_tipo_creditos }
	}).done( function(x){
		var data	= x;
		tipo_productos.append('<option value="0">--Seleccione--</option>');
		$.each(data,function(i, v){
			tipo_productos.append('<option value="'+v.id_creditos_productos+'">'+v.nombre_creditos_productos+'</option>');
		});		
	}).fail( function(xhr, status, error){
		console.log(xhr.responseText);
		tipo_productos.append('<option value="0">--Seleccione--</option>');
	})
}

var change_tipo_producto_creditos	= function(a){	
	var elemento	= $(a);
	if( elemento.length ){
		var textoElemento	= elemento.find('option:selected').text();
		var panel	= $("#div_info_garante");
		var textoHtml	= "";
		if( textoElemento == "Sin Garante" || textoElemento == "Unico" ){
			textoHtml	= "";
			panel.html( textoHtml ); //panel queda vacio
		}else if( textoElemento == "Con Garante"){
			textoHtml	= "<label for=\"txt_cedula_garante\" class=\"control-label\">Añadir garante:</label>" +
			"<div class=\"input-group\">"+
			"<input type=\"text\" data-inputmask=\"'mask': '9999999999'\" class=\"form-control\" id=\"txt_cedula_garante\"  placeholder=\"C.I.\">"+			
			"<span class=\"input-group-btn\">"+
			"<button type=\"button\" class=\"btn btn-primary\" id=\"btn_buscar_garante\" >"+
			"<i class=\"glyphicon glyphicon-plus\"></i>"+
			"</button>"+
			"<button type=\"button\" class=\"btn btn-danger\" id=\"btn_borrar_cedula\" >"+
			"<i class=\"glyphicon glyphicon-arrow-left\"></i>"+
			"</button>"+
			"</span>"+
			"</div>";
			
			// MUESTRO LA INFORMACION PARA ASIGNAR UN GARANTE 
			panel.html( textoHtml );
			
			//AGREGAR EVENTO A BOTONES EN PANEL GARANTE
			$('body').one('click','#btn_buscar_garante',function(){				
				buscar_garante();
			});
			
			$('body').off('click','#btn_borrar_cedula').on('click','#btn_borrar_cedula',function(){
				$('#txt_cedula_garante').val("");
			});
			
			// AGREGO EVENTOS AL INPUT GARANTES
			$("#txt_cedula_garante").inputmask();			
			$('#txt_cedula_garante').keypress( function( event ){
				if(event.keyCode == 13){
					$('#btn_buscar_garante').click();
				}
			});
		}else if( textoElemento == "Para Terreno" || textoElemento == "Para Vivienda" ){
			var html_credito_hipotecario = "<label for=\"ddl_tipo_credito_hipotecario\" class=\"control-label\">Modalidad:</label>" +
			"<select id=\"ddl_tipo_credito_hipotecario\"  class=\"form-control\" >"+
			"<option value=\"\" selected=\"selected\">--Seleccione--</option>"+
			"<option value=\"1\" >COMPRA DE BIEN O TERRENO</option>"+
			"<option value=\"2\" >MEJORAS Y/O REPAROS</option></div>";
			// MUESTRO HTML EN LA VISTA EN LA PARTE DEL GARANTE
			$('#div_info_garante').html( html_credito_hipotecario );
			
			//onchange=\"ModalidadCreditoHP()\"
			//ENLAZAR EVENTO A INPUT CREADO
			$("#ddl_tipo_credito_hipotecario").on('change',function(){
				obtener_valores_hipotecario( this );
			});
			
			$("#ddl_tipo_credito_hipotecario").on('change',function(){
				obtener_valores_hipotecario( this );
			});
		}else{
			textoHtml	= "";
			panel.html( textoHtml ); //panel queda vacio
		}
	}
}

var analisis_capacidad_pago	= function(){
	
	//iniciamos inputs
	view.cuota_creditos.val("");
	view.monto_creditos.val("");
	$("#div_tabla_amortizacion").html("");
	
	//navego al tab de analisis de credito
	$('.nav-tabs a[href="#panel_capacidad"]').tab('show'); 
	
	//onclick="EnviarCapacidadPagoParticipe()"
		
	//iniciamos variables
	var valor_tipo_credito	= view.tipo_creditos.val();
	var cedula_participe	= view.cedula_participes.val();
	
	if( valor_tipo_credito == "0" ||  valor_tipo_credito == "" || valor_tipo_credito == null || cedula_participe == null || cedula_participe == "" ){
		console.log("Error al buscar capacidad de pago.. revisar variables");
		return false;
	}
	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=obtenerCuotaParticipe',
	    type: 'POST',
	    dataType: 'json',
	    data: {
	    	'cedula_participe':cedula_participe,
	    	'tipo_credito':valor_tipo_credito
	    },
	}).done(function(x) {
		
		if( x.estatus != undefined && x.estatus == "OK" ) {
			
			var valorCuota	= x.cuota_creditos; 
			view.cuota_vigente.val(valorCuota);
			
			//llamar a funcion que suma valores de capacidad de pago dc 2020-06-15
			//sumar_ingresos_capacidad_pago();
			ObtenerAnalisis(); //llama funcion de archivo externo
			
			if( valorCuota != 0 ){
				
				view.global_hay_renovacion = true;
				var cuentaIndividual 	= dataSolicitud.cuenta_individual || 0.00; //obtener valor de cuenta individual
				view.monto_creditos.val( cuentaIndividual );
											
			}
			
		}
		//ver variable bolean to know list of Credits' renovation
		console.log("VARIABLE bolean DE RENOVACION CREDITOS --> "+ view.global_hay_renovacion );
		
	})
	.fail(function() {
	    console.log("error");
	});	
		
}

var sumar_ingresos_capacidad_pago	= function(){
	
	try{
		
		var total = isNaN( parseFloat( view.sueldo_liquido.val() ) ) ? 0 : parseFloat( view.sueldo_liquido.val() );
		total += isNaN( parseFloat( view.cuota_vigente.val() ) ) ? 0 : parseFloat( view.cuota_vigente.val() );  
		total += isNaN( parseFloat( view.valor_fondos.val() ) ) ? 0 : parseFloat( view.valor_fondos.val() ); 
		total += isNaN( parseFloat( view.valor_decimos.val() ) ) ? 0 : parseFloat( view.valor_decimos.val() );
		total += isNaN( parseFloat( view.valor_rancho.val() ) ) ? 0 : parseFloat( view.valor_rancho.val() ); 
		total += isNaN( parseFloat( view.ingresos_notarizados.val() ) ) ? 0 : parseFloat( view.ingresos_notarizados.val() ); 
		
		total	= Math.round( Math.round( total * 1000 ) / 10 ) / 100;
		view.total_ingresos.val( total );
		
	}catch( err ){
		console.log("ERROR AL SUMAR .. la capacidad de pago de participe.. ")
	}	
	
}

// funcion que trae datos de creditos a renovar
var obtener_creditos_renovacion	= function(){
	
	var valor_tipo_creditos	= view.tipo_creditos.val();
	var valor_id_participes = view.hdn_id_participes.val() || 0;	
	console.log("empezamos a traer creditos para renovacion..");
	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=obtenerCreditosRenovar',
	    type: 'POST',
	    dataType: 'json',
	    data: {
	    	   id_participe: valor_id_participes,
	    	   tipo_creditos: valor_tipo_creditos
	    },
	}).done(function(x) {		
		// IMPRIMO EN LA VISTA LOS CREDITOS A RENOVAR
		$('#div_pnl_creditos_renovacion').html( x.html );
		
		var cantidad_creditos = ( x.cantidad != undefined ) ? x.cantidad : 0;
		
		//ESTABLECER valor en el texto informativo de participe
		if( $("span:contains('Capital de créditos')").length )
		{
			var span_copia	= $("span:contains('Capital de créditos')").first();
			var p_padre		= span_copia.parent();
			p_padre.empty();
			p_padre.html(span_copia);
			p_padre.find('span').after(": " + ( x.total_creditos == undefined ? 0 : x.total_creditos ) );
			
		}
		
		$("#lbl_numero_creditos_renovacion").text( cantidad_creditos );
		
	}).fail(function() {
	    console.log("error");
	});
	
}

var enviar_capacidad_pago = function(){
		
	/** dc 2020-06-15 **/
	var valor_total_ingresos	= view.total_ingresos.val();
	
	if( isNaN(  parseFloat( valor_total_ingresos ) ) ){
		console.log("VALOR CAPACIDAD PAGO NO DEFINIDO");
		view.total_ingresos.closest('tr').notify("Ingrese cuota que desea pagar ",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//aqui generaba un elemnto de id -->  sueldo_participe
	view.capacidad_pago.val( valor_total_ingresos );
	
	view.monto_creditos.val( dataSolicitud.cuenta_individual );
	
	//enves de cerrar modal como el codigo original se navega a la tab num 1
	//$("#cerrar_analisis").click();
	$('.nav-tabs a[href="#panel_info"]').tab('show'); //navegar a la pantalla de informacion en tab navs principal
	
}

/****************	PARA TRATAR VALORES DE GARANTES *******************/

var buscar_garante	= function(){
	
	//variables globales
	view.global_hay_garantes	= false;
	
	//validar cedula_garante
	var element_cedula_garante = $("#txt_cedula_garante");
	view.hdn_cedula_garante.val("");
	
	//valida que exita el elemento
	if( !element_cedula_garante.length ){
		swal({title:"DISEÑO",text:"Elemento No definido",icon:"warning"});
		return false;
	}
	//valida valores de elemeto de cedula
	if( element_cedula_garante.val() == "" || element_cedula_garante.val().includes('_') ){		
		$("#mensaje_cedula_garante").notify("Ingrese Cedula del Garante",{ position:"buttom left", autoHideDelay: 2000});
		return false;		
	}
	
	//validacion para cedula de participe
	if( !view.cedula_participes.val().length ){
		swal({title:"CEDULA",text:"Cedula Participe no definido",icon:"warning"});
		return false;
	}
	//validacion de cedula no sea igual al garante
	if( view.cedula_participes.val() == element_cedula_garante.val() ){		
		element_cedula_garante.notify("Cedula no Válida para ser garante",{ position:"buttom left", autoHideDelay: 2000});
		element_cedula_garante.val("");
    	return false;
	}
	
	//si pasa la validacion 
	view.hdn_cedula_garante.val( element_cedula_garante.val() ); //se guarda la cedula del garante en un hidden
	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=ObtenerInformacionGarante',
	    type: 'POST',
	    dataType: 'json',
	    data: {
	    	   cedula_garante: element_cedula_garante.val()
	    },
	}).done(function(x) {
		
		var garanteHtml	= ( typeof x.html == "undefined" ) ? "" : x.html;
		
		$("#div_info_garante").html( garanteHtml ); //colocar informacion del garante solicitado
		
		if( x.estatus != undefined && x.estatus == "OK" ){
						
			view.global_hay_garantes	= true;
			
			//se inicializa valores de garante
			var data	= x.data;	
			
			//datos de participe
			var limite_participe	= dataSolicitud.cuenta_individual;
			var aportes_participe	= dataSolicitud.aportes_participe;
			
			//datos de garante	
			dataGarantes	= data; //establecemos valores de garante
			var limite_garante	= data.disponible_garante;
			var edad_garante	= data.edad;			
			var limite_total	= parseFloat( limite_garante ) + parseFloat( limite_participe );
			var aportes_garante = data.aporte_garante;	
			
			// si tienen los aportes doy paso al credito on garantia
			if ( limite_total >= 150 && edad_garante < 75 && aportes_garante >= 3 && aportes_participe >= 3 )
			{				
				//aqui buscar cambiar el estado de solicitud
				//document.getElementById("disponible_participe").classList.remove('bg-red');
				//document.getElementById("disponible_participe").classList.add('bg-olive');
				view.global_hay_garantes	= true;
				/*var html_capacidad_pago_garante	='<div class="form-group">'+
							'<label for="monto_credito" class="control-label">Capacidad de pago Garante:</label>'+
							'<button align="center" class="btn bg-olive" title="Análisis crédito"  onclick="AnalisisCreditoGarante()"><i class="glyphicon glyphicon-new-window"></i></button>'+
							'<!--<input type=number step=1 class="form-control" id="txt_capacidad_pago_garante" style="background-color: #FFFFF;">  -->'+
							'<div id="mensaje_sueldo_garante" class="errores"></div></div>';*/
				
				$("#div_capacidad_pago_garante").removeClass('hidden');
				
				//RELACIONAR EVENTO CON EL BOTON CAPACIDAD PAGO GARANTE
				$('body').one('click','#btn_capacidad_pago_garante',function(){
					//console.log("AGREGAR EVENTO BOTON CAPACIDAD PAGO GARANTE");
					analisis_capacidad_pago_garante();
				})
				
			}
			
			if( edad_garante >= 75 || aportes_garante < 3 ){
				
				//aqui buscar cambiar el estado de solicitud
				//document.getElementById("disponible_participe").classList.remove('bg-olive');
				//document.getElementById("disponible_participe").classList.add('bg-red');
				
				//SINTAXERROR --buscar para poner en mensaje de alert bootstrap
				swal({
			  		  title: "Advertencia!",
			  		  text: "El participe no cumple las condiciones para ser garante",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
				
		}else{
			
			if( garanteHtml.includes( "Garante no disponible" ) ){
				swal({
			  		  title: "Advertencia!",
			  		  text: "El participe ya es garante activo",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
			
			if( garanteHtml.includes( "Participe no encontrado" ) ){
				swal({
			  		  title: "Advertencia!",
			  		  text: "Participe no está registrado o no se encuentra activo",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
			
			var boton_flotante = '<div class="btn-contenedor">'+
			'<button class="btn-flotante" id="btn_cambiar_garante" title="Quitar garante" >'+
            '<span class="flotante">&times;</span>'+
            '</button>'+
            '</div> ';
			$("#div_info_garante").css({'height':$("#info_participe_solicitud").height() - 100});
			$("#div_info_garante").append(boton_flotante);
		}	
		
		//boton para cambiar garante ..se genera un evento para el boton que se dibuja en el controlador
		$('body').one('click','#btn_cambiar_garante', function(){			
			iniciar_datos_simulacion();
			change_tipo_producto_creditos( "#ddl_credito_producto" );
		})
		
	}).fail(function() {
	    console.log("error");
	});
		
}

var analisis_capacidad_pago_garante	= function(){
	
	//iniciamos inputs
	view.cuota_creditos.val("");
	view.monto_creditos.val("");
	$("#div_tabla_amortizacion").html("");
	
		
	//EnviarCapacidadPagoGarante()
	
	//navego al tab de analisis de credito garante
	$('.nav-tabs a[href="#panel_capacidad_garante"]').tab('show');
	
	//iniciamos variables
	var valor_cedula_garante	= view.hdn_cedula_garante.val();
	
	if(  valor_cedula_garante == null || valor_cedula_garante == "" ){
		console.error("Error al buscar capacidad de pago garante.. revisar variables");
		return false;
	}
		
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=obtenerCuotaGarante',
	    type: 'POST',
	    dataType: 'json',
	    data: {
	    	cedula_participe:valor_cedula_garante
	    },
	}).done( function(x) {
		var valor_cuota = 0.00;
		if( x.estatus != undefined && x.estatus == "OK" ){
			valor_cuota = x.cuota_total;			
		}
		
		if( x.estatus != undefined && x.estatus == "ERROR" ){
			console.error("ERROR al buscar cuota del garante revisar metodo de busqueda");
			valor_cuota	= 0.00;
		}
		
		$("#txt_cuota_vigente_garante").val(valor_cuota);
		sumar_ingresos_capacidad_pago_garante();
				
	}).fail(function() {
	    console.log("error");
	});
	
}

var sumar_ingresos_capacidad_pago_garante	= function(){
	
	try{
		
		var total = isNaN( parseFloat( view.sueldo_liquido_garante.val() ) ) ? 0 : parseFloat( view.sueldo_liquido_garante.val() );
		total += isNaN( parseFloat( view.cuota_vigente_garante.val() ) ) ? 0 : parseFloat( view.cuota_vigente_garante.val() );  
		total += isNaN( parseFloat( view.valor_fondos_garante.val() ) ) ? 0 : parseFloat( view.valor_fondos_garante.val() ); 
		total += isNaN( parseFloat( view.valor_decimos_garante.val() ) ) ? 0 : parseFloat( view.valor_decimos_garante.val() );
		total += isNaN( parseFloat( view.valor_rancho_garante.val() ) ) ? 0 : parseFloat( view.valor_rancho_garante.val() ); 
		total += isNaN( parseFloat( view.ingresos_notarizados_garante.val() ) ) ? 0 : parseFloat( view.ingresos_notarizados_garante.val() ); 
		
		total	= Math.round( Math.round( total * 1000 ) / 10 ) / 100;
		view.total_ingresos_garante.val( total );
		
	}catch( err ){
		console.log("ERROR AL SUMAR .. la capacidad de pago de garante.. ")
	}	
	
}

//cunado cambie el codigo regresar a nombre_funcion
var evt_enviar_capacidad_pago_garante	= function(){
	
	//obtener total ingresos garante
	var total_garante	= view.total_ingresos_garante.val();	
	view.capacidad_pago_garante.val( total_garante );
	
	//paso valores de cuenta individual
	view.monto_creditos.val( dataSolicitud.cuenta_individual );
	
	$( "#btn_capacidad_pago_garante" ).find('i').removeClass().addClass('glyphicon glyphicon-refresh');	
	
	$('.nav-tabs a[href="#panel_info"]').tab('show');
}

/**************** TERMINA	PARA TRATAR VALORES DE GARANTES *******************/

/**************** EMPIEZA PARA TRATAR VALORES DE HIPOTECARIO *****************/

var obtener_valores_hipotecario	= function (a){
	
	let elemento	= $(a) || null;
	
	if( !elemento.length )
	{
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=obtenerAvaluoHipotecario',
		    type: 'POST',
		    data: {
		    	id_solicitud: 1,
		    	tipo_credito_hipotecario: elemento.val()
		    },
		}).done(function(x) {
			$('#div_info_garante').html( x );			
		}).fail(function() {
		    console.log("error");
		});

	}else
	{
		$("#myModalAvaluo").modal();
	}
	
}

var enviar_avaluo_bien	= function(){
	
	var tipo_avaluo = $("#ddl_tipo_credito_hipotecario");
	var valor_bien	= $("#avaluo_bien");	
	view.global_avaluo_sin_solicitud = valor_bien.val(); //establecer valor en variable global
	var monto_maximo	= 0;
	
	if( tipo_avaluo.val() == 1 )
	{
		monto_maximo	= parseFloat( valor_bien.val() ) * 0.80;
		monto_maximo	= ( monto_maximo > 100000 ) ? 100000 : monto_maximo;
	}else
	{
		monto_maximo	= parseFloat( valor_bien.val() ) * 0.50;
		monto_maximo	= ( monto_maximo > 45000 ) ? 45000 : monto_maximo;
	}
	
	var tablehtml	= '<table>'+
					'<tr> <td>Avalúo del bien: '+ valor_bien.val() +'</td></tr>'+
					'<tr> <td id="cuenta_individual2" >Monto máximo a recibir: '+ monto_maximo +'</td></tr>'+
					'<tr> <td ><span class="input-group-btn">'+
					'<button  type="button" class="btn bg-olive" title="Cambiar Modalidad" onclick="TipoCredito()"><i class="glyphicon glyphicon-refresh"></i></button>'+
					'</span> </td> </tr>'+
				'</table>';	
	
	$('#div_info_garante').html( tablehtml );
	
	$("#myModalAvaluo").modal("hide");
	
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

/**************** TERMINA PARA TRATAR VALORES DE HIPOTECARIO *****************/

var redondeo_valores	= function( a ){
	//esta funcion pasa como parametro un elemto si el elemento existe cambia sus valores
	var element	= $(a);
	var valor_element	= 0;
	var residuo	= 0;
	//valido que exista el elemento
	if( element.length ){
		
		valor_element	= element.val();
		residuo	= valor_element%10;		
		valor_element	= valor_element - residuo;
		
		if( valor_element != 0 ){
			element.val( valor_element );
		}		
	}
	
}

/***
 * funcion que permite validacion de datos antes de ser procesados para generar simulacion
 * @author dc
 * retorna bolean false;
 */
var validar_valores_simulacion	= function(){
	
	var sueldo_participe= view.capacidad_pago.val();
	var valor_tipo_creditos	= view.tipo_creditos.val();
	var limite_credito	= 0;
	var total_saldo_renovar	= 0;
	
	//busco datos en variable general de solicitud dataSolicitud que se llena al llamar datos informacion participe
	//var mivar = ( typeof dataSolicitud.hola == "undefined" ) ? true : dataSolicitud.hola;
	if( !dataSolicitud.estado_solicitud ){
		swal({title:"ERROR CREDITO", text:"Participe no puede acceder a credito en este momento",icon:"error",dangerMode:true});
		return false;
	}
		
	if( valor_tipo_creditos == "0" || valor_tipo_creditos == "" ){
		view.tipo_creditos.notify( "Seleccione Tipo Crédito" ,{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( isNaN( parseFloat( sueldo_participe ) ) || sueldo_participe == ""){
		view.capacidad_pago.notify("Ingrese Capacidad de Pago",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//tomar valor de saldo a renovar
	total_saldo_renovar	= ( $("#total_saldo_renovar").text() == "" ) ? 0 : $("#total_saldo_renovar").text();
	
	//validacion de monto 
	//redondeo valores de elemento
	redondeo_valores( view.monto_creditos );
	
	if( !view.monto_creditos.val().length || view.monto_creditos.val() == "" )
	{
		view.monto_creditos.notify("Monto De credito no Definido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( parseFloat( view.monto_creditos.val() ) < 150 )
	{
		view.monto_creditos.notify("Monto debe ser mayor o igual a $150",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( total_saldo_renovar > 0 &&  parseFloat( view.monto_creditos.val() ) < total_saldo_renovar )
	{
		view.monto_creditos.notify("Monto no valido ",{ position:"buttom left", autoHideDelay: 2000});
		swal({title:"VALORES",text:"Saldo de creditos no debe superar al monto de credito Solicitado. Revise los valores",icon:"warning"});
		return false;
	}
	
	if( valor_tipo_creditos == "PH"){
		limite_credito	= $("#cuenta_individual2").text(); //esta variable esta por definir  ...SINTAXERROR
	}else{		
		//validacion de variable renovacion credito se elimina porq hace lo mismo of code below
		limite_credito	= dataSolicitud.cuenta_individual;
	}
	
	if( valor_tipo_creditos == "EME" )
	{
		var saldo_total_creditos	= dataSolicitud.capital_creditos;
		
		saldo_total_creditos	= parseFloat( saldo_total_creditos ) - parseFloat( total_saldo_renovar );
		
		limite_credito	= parseFloat( limite_credito ) - parseFloat( saldo_total_creditos );
		
		limite_credito	= Math.round( Math.round( limite_credito * 1000 ) / 10 ) / 100;
	}
	
	if( !view.global_hay_garantes ){
		
		//comienza validacion para trabajar con creditos sin garante
		
		if( valor_tipo_creditos == "EME" && parseFloat( view.monto_creditos.val() ) > 7000 )
		{
			view.monto_creditos.notify("Monto máximo crédito Emergente es $7000",{ position:"buttom left", autoHideDelay: 2000});
			
			return false;
		}
		
		if( parseFloat( view.monto_creditos.val() ) > parseFloat( limite_credito ) )
		{
			view.monto_creditos.notify("Monto máximo $"+limite_credito,{ position:"buttom left", autoHideDelay: 2000});
			view.monto_creditos.val( limite_credito );
			return false;
		}
						
		
	}else
	{
		
		if( view.capacidad_pago_garante.val() == "" || !view.capacidad_pago_garante.val().length ){
			
			view.capacidad_pago_garante.notify("Ingrese Capacidad de Pago Garante",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}

		var limite_garante = ( dataGarantes.disponible_garante == undefined ) ? 0 : dataGarantes.disponible_garante;
		limite_credito	= parseFloat( limite_credito ) + parseFloat( limite_garante );
		
		if( parseFloat( view.monto_creditos.val() ) > parseFloat( limite_credito ) )
		{
			view.monto_creditos.notify("Monto máximo $"+limite_credito,{ position:"buttom left", autoHideDelay: 2000});
			view.monto_creditos.val( limite_credito );
			return false;
		}	
		
	}	
	
	return true;	
}

var buscar_numero_cuotas	= function(){	
		
	if( !view.global_hay_garantes )
	{
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=obtenerCuotas',
		    type: 'POST',
		    dataType: 'json',
		    data: {
		    	monto_credito: view.monto_creditos.val(),
		    	cedula_participe: view.cedula_participes.val(),
		    	sueldo_participe: view.capacidad_pago.val(),
		    	tipo_credito: view.tipo_creditos.val(),		    	
		    },
		    beforeSend: function(){ $("#divLoaderPage").addClass("loader") },
		    complete: function(){ $("#divLoaderPage").removeClass("loader") },
		}).done(function(x) {
			
			if( x.estatus != undefined && x.estatus == "OK" )
			{				
				if( x.cuotas != undefined )
				{					
					var cuotas = x.cuotas;
					view.numero_cuotas.empty();
					view.numero_cuotas.attr("disabled",false);
					$.each( cuotas, function(i,value){
						view.numero_cuotas.append('<option value="'+value.plazo+'"> plazo:'+value.plazo+' | cuota: '+value.valor+'</option>');
					})
				}
				
				var monto_validado = ( x.monto != undefined ) ? x.monto : 0;
				view.monto_creditos.val( monto_validado );
				if( monto_validado >= 150)				{
					
					swal({title:"INFORMACION",text:"PUEDE CONTINUAR CON LA SIMULACION DEL CREDITO"});
					view.btn_generar_simulacion.attr("disabled",false);
					
				}else{
					swal({title:"ADVERTENCIA!",text:"REVISE LOS DATOS. Cuota y plazo obtenidos de acuerdo a los datos enviados",icon:"warning",dangerMode:true});
				}
				
				//mandar la funcion de simulacion credito SINTAXERROR				
				$("#div_tabla_amortizacion").html( "" );
				
			}
			
			if( x.estatus != undefined && x.estatus == "ERROR" )
			{				
				view.numero_cuotas.append('<option value="0">--Seleccione--</option>');
				console.error('Error al obtener cuotas para el credito');
			}
						
		}).fail(function() {
		    console.log("error");
		});
		
	}else
	{
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=obtenerCuotasGarante',
		    type: 'POST',
		    dataType: 'json',
		    data: {
		    	monto_credito: view.monto_creditos.val(),
		    	cedula_participe: view.cedula_participes.val(),
		    	sueldo_participe: view.capacidad_pago.val(),
		    	tipo_credito: view.tipo_creditos.val(),
		    	cedula_garante: view.hdn_cedula_garante.val(),
		    	sueldo_garante:view.capacidad_pago_garante.val()
		    	
		    },
		}).done(function(x) {
			
						
			if( x.estatus != undefined && x.estatus == "OK" )
			{	
				if( x.cuotas != undefined )
				{					
					var cuotas = x.cuotas;
					view.numero_cuotas.empty();
					view.numero_cuotas.attr("disabled",false);
					$.each( cuotas, function(i,value){
						view.numero_cuotas.append('<option value="'+value.plazo+'"> plazo:'+value.plazo+' | cuota: '+value.valor+'</option>');
					})
				}
				
				var monto_validado = ( x.monto != undefined ) ? x.monto : 0;
				view.monto_creditos.val( monto_validado );
				
				if( x.pago_garante != undefined && x.pago_garante >= 0 )
				{					
					view.capacidad_pago_garante.css({'background-color':"#fff"});
					view.global_capacidad_pago_garante_suficiente	=	true;
					
				}else
				{					
					view.capacidad_pago_garante.css({'background-color':"#F5B7B1"});					
					activar_mensaje_texto("Capacidad de pago no es Suficiente"); //SINTAXERROR
				}
				
				if( monto_validado >= 150)
				{					
					swal({title:"INFORMACION",text:"PUEDE CONTINUAR CON LA SIMULACION DEL CREDITO"});
					view.btn_generar_simulacion.attr("disabled",false);					
				}else{
					swal({title:"ADVERTENCIA!",text:"REVISE LOS DATOS. Cuota y plazo obtenidos de acuerdo a los datos enviados",icon:"warning",dangerMode:true});
				}
				
				$("#div_tabla_amortizacion").html( "" );
								
			}
			
			if( x.estatus != undefined && x.estatus == "ERROR" )
			{				
				view.numero_cuotas.append('<option value="0">--Seleccione--</option>');
				console.error('Error al obtener cuotas para el credito con garante');
			}
					
			
		}).fail(function() {
		    console.log("error");
		});
	}
		
}

var simular_credito_amortizacion = function(){	
	
	var valor_monto	= view.monto_creditos.val();
	var valor_tipo_creditos = view.tipo_creditos.val();
	var valor_cuotas		= view.numero_cuotas.val();
	var id_solicitud		= view.hdn_id_solicitud.val();
	
	//busco variable global
	view.global_hay_solicitud	= ( sin_solicitud ) ? 0 : 1;
	
	//SINTAXERROR --
	//'renovacion_credito':view.global_hay_renovacion, 
	//'id_solicitud':view.hdn_id_solicitud,
	//'avaluo_bien':view.global_avaluo_sin_solicitud
	
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=obtenerSimulacionCredito',
	    type: 'POST',
	    dataType: 'json',
	    data: {
	    	'monto_credito':valor_monto,
	    	'tipo_credito':valor_tipo_creditos,
	    	'plazo_credito':valor_cuotas,
	    	'renovacion_credito':view.global_hay_renovacion, 
	    	'id_solicitud':id_solicitud,
	    	'avaluo_bien':view.global_avaluo_sin_solicitud
	    },
	}).done(function(x) {
		
		$("#div_tabla_amortizacion").html( "" );
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			$("#div_tabla_amortizacion").html( x.html );
			
			if( view.global_hay_garantes )
			{
				var cuota_numero_dos	= 0;
				var seguro_desgravamen_dos	= 0;
				
				//validacion para buscar segunda fila sexta columna de tabla amortizacion generada --valor cuota
				if( $("#div_tabla_amortizacion").find('table:nth-child(1) tr:nth-child(2) td:nth-child(6)').length )
				{ 
					var texto_cuota = $("#div_tabla_amortizacion").find('table:nth-child(1) tr:nth-child(2) td:nth-child(6)').text();
					cuota_numero_dos = texto_cuota.replace(",", "");
				}
				
				//validacion para buscar segunda fila quinta columna de tabla amortizacion generada --valor desgravamen
				if( $("#div_tabla_amortizacion").find('table:nth-child(1) tr:nth-child(2) td:nth-child(5)').length )
				{ 
					var texto_seguro_desgravamen = $("#div_tabla_amortizacion").find('table:nth-child(1) tr:nth-child(2) td:nth-child(6)').text();
					seguro_desgravamen_dos 		= texto_seguro_desgravamen.replace(",", "");
				}
				
				if( parseFloat( cuota_numero_dos ) > parseFloat( view.capacidad_pago_garante.val() ) )
				{					 
					 view.capacidad_pago_garante.css({'background-color': "#F5B7B1"})
					 activar_mensaje_texto(" Capacidad Pago Garantes No cubre cuota de Amortizacion"); //SINTAXERROR
				}else
				{
					view.capacidad_pago_garante.css({'background-color':"#fff"});
				}
			}//end if( view.global_hay_garantes )
			
			swal( {title:"INFORMACION",text:"Tabla Amortizacion cargada",icon:"info",buttons:false,timer:1000} );
			
			//ENLAZAR EVENTO DE BOTON DE GUARDAR SIMULACION TABLA AMORTIZACION
			$("#btn_guardar_simulacion_credito").on('click',function(){
				validar_valores_guardar_credito();
			});
			
			$("#btn_imprimir_simulacion_credito").on('click',function(){
				imprimir_tabla_amortizacion();
			});
						
		}else
		{
			swal( {title:"ALERTA",text:"Error al cargar Tabla Amortizacion ",icon:"warning",buttons:false,timer:1000} );
		}			
				
	}).fail(function() {
	    console.log("error");
	});	
	
}

/**** EMPEZAMOS CON LA INSERSION DE CREDITOS *****/ 
var agregar_evento_guardar_credito	= function(){
	
	var boton_credito = $("#div_tabla_amortizacion").find('h3.box-title + button');
	if( boton_credito.length ){

		boton_credito.attr('onclick','').unbind('click');
		boton_credito.click(function(){
			//SINTAXERROR
			validar_valores_guardar_credito();
		})
	}
	
	var boton_confirmar_codigo	= $("#registrar_credito");
	if( boton_confirmar_codigo.length ){

		boton_confirmar_codigo.attr('onclick','').unbind('click');
		boton_confirmar_codigo.click(function(){
			//SINTAXERROR
			validar_codigo_generado();
		})
	}
	
}

var validar_valores_guardar_credito	= function(){
	
	console.info("COMENZO FUNCION ABRIR MODAL");
	
	if( view.global_hay_garantes )
	{
		if( view.global_capacidad_pago_garante_suficiente )
		{
			swal({title: "Advertencia!",
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
				    	mostrar_verificacion_codigo();
				      break;
				 
				    default:
				      swal("Crédito no registrado");
				  }
				});
		}else
		{
			swal({title: "Advertencia!",
				  text: "El garante no tiene la capacidad de pago suficiente",
				  icon: "error",
				  button: "Aceptar",
				});
		}
	}else
	{
		swal({title: "Advertencia!",
			  text: "Se precedera con el registro del crédito",
			  icon: "warning",
			  buttons: {
			    cancel: "Cancelar",
			    aceptar: {
			      text: "Aceptar",
			      value: "aceptar",
			    }
			  },
			}).then((value) => {
			  switch (value) {			 
			    case "aceptar":
			    	mostrar_verificacion_codigo();
			      break;
			 
			    default:
			      swal("Crédito no registrado");
			  }
			});
	}	
	
}

var mostrar_verificacion_codigo	= function(){
	
	//BUSCAR BOTON ACEPTAR LA SIMULACION TABLA AMORTIZACION 
	var boton_confirmar_codigo	= $("#registrar_credito");
	if( boton_confirmar_codigo.length ){

		boton_confirmar_codigo.attr('onclick','').unbind('click');
		boton_confirmar_codigo.on('click',function(){
			console.info("INICIA LA VALIDACION DE MODAL PARA INSERTAR EL CREDITO");
			validar_codigo_generado();
		});
	}
	
	var elementmodal = $('#myModalInsertar');
	elementmodal.find("#observacion_confirmacion").val("");
	elementmodal.find("#codigo_confirmacion").val("");
	$("#info_credito_confirmar").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	var nombre_participe=$("#nombre_participe_credito").html();
	$.ajax({
	    url: 'index.php?controller=CargarParticipes&action=genera_codigo',
	    type: 'POST',
	    data: {
	    	tipo_credito: view.tipo_creditos.val()
	    },
	}).done(function(x) {
		console.log(x);
		x=JSON.parse(x);
		var informacion="<h3>Se procedera a generar un crédito para "+dataSolicitud.nombre_participe_credito+"</h3>" +
				"<h3>Con cédula de identidad número "+view.cedula_participes.val()+"</h3>" +
				"<h3>Por el monto de "+view.monto_creditos.val()+" USD</h3>" +
				"<h3>A un plazo de "+view.numero_cuotas.val()+" meses con interes del "+x[1]+"%</h3>" +
				"<h3>Para confirmar ingrese el siguiente código</h3>" +
				"<h2 id=\"codigo_generado\">"+x[0]+"</h2>";
		$("#info_credito_confirmar").html(informacion);	
	}).fail(function() {
	    console.log("error");
	});	
	
	//se abre modal de Insersion de datos
	elementmodal.modal('show');
}

var validar_codigo_generado	= function(){
	
	console.error("SE CAMBIO LOS METOSDOS SUBIR LA INFORMACION");
	
	var codigo_generado		= $("#codigo_generado").html();
	var codigo_insertado	= $("#codigo_confirmacion").val();
	if( codigo_insertado == "" || codigo_insertado.includes("_"))
	{
		swal("Inserte código");
	}else if( codigo_insertado!="" && !(codigo_insertado.includes("_")) && codigo_insertado==codigo_generado )
	{
		registrar_credito_nuevo();
	}else
	{
		swal("Código incorrecto");
	}
}

var registrar_credito_nuevo	= function(){
	
	var monto_credito	= view.monto_creditos.val();
	var valor_tipo_creditos	= view.tipo_creditos.val();
	var fecha_corte		= $("#fecha_corte").val() || obtener_fecha_actual(); //SINTAXERROR
	var cuota_credito	= view.numero_cuotas.val();
	var ciparticipe		= view.cedula_participes.val();
	var observacion		= $('#observacion_confirmacion').val();
	var cigarante		= view.hdn_cedula_garante.val();
	//id_solicitud	=solicitud;
	id_solicitud		= view.hdn_id_solicitud.val();
	
	swal({title: "Crédito",text: "Registrando crédito",icon: "view/images/capremci_load.gif",buttons: false,closeModal: false,allowOutsideClick: false});	
	
	if( !view.global_hay_renovacion )
	{
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=InsertarSimulacionCredito',
		    type: 'POST',
		    data: {
		    	'id_creditos_productos': view.creditos_productos.val(),
		    	monto_credito: monto_credito,
		    	tipo_credito: valor_tipo_creditos,
		    	fecha_pago: fecha_corte,
		    	cuota_credito: cuota_credito,
		    	cedula_participe: ciparticipe,
		    	observacion_credito: observacion,
		    	'id_solicitud':id_solicitud,
		    	con_garante: view.global_hay_garantes,
		    	cedula_garante:cigarante
		    	
		    },
		}).done(function(x) {
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
			  			//window.open('index.php?controller=SolicitudPrestamo&action=index5', '_self');
			  			window.open('index.php?controller=PrincipalBusquedas&action=index', '_self');
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
		}).fail(function() {
		    console.log("error");
		});
	}else
	{
		
		var params = {
			'id_creditos_productos': view.creditos_productos.val(),
	    	'monto_credito': monto_credito,
	    	'tipo_credito': valor_tipo_creditos,
	    	'fecha_pago': fecha_corte,
	    	'cuota_credito': cuota_credito,
	    	'cedula_participe': ciparticipe,
	    	'observacion_credito': observacion,
	    	'id_solicitud':id_solicitud,
	    	'con_garante': view.global_hay_garantes,
	    	'cedula_garante':cigarante	    	
	    };
		
		$.ajax({
		    url: 'index.php?controller=CargarParticipes&action=insertarRenovacionCredito',
		    type: 'POST',
		    data: params,
		}).done(function(x) {
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
		  			//window.open('index.php?controller=SolicitudPrestamo&action=index5', '_self');
		  			window.open('index.php?controller=PrincipalBusquedas&action=index', '_self');
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
		}).fail(function() {
		    console.log("error");
		});
		
	}
	
}

var obtener_aportes_validacion	= function(a){
	
	var element = $(a);
	
	if( element.length ){			
		
		element.popover({
		    html: true,
		    placement:'auto',
		    trigger: 'hover',
		    content: 'Cargando..'
		  });
		
		$.ajax({
			url: 'index.php?controller=CargarParticipes&action=obtenerAportesValidacion',
			method: 'POST',
			dataType: 'json',
			async: false,
			data:{ id_participe : view.hdn_id_participes.val() }
		}).done(function(x){
			element.data('bs.popover').options.content = x.html;			
		}).fail(function(xhr, status, error){
			element.data('bs.popover').options.content = "<span> Datos No encontrados.</span>";
		});
		
		element.popover('show');
		
	}
}

var obtener_aportes_lista_validacion	= function(){
	
	var element = $("#div_pnl_aportes_validacion");
	if( element.length ){	
		
		element.html('<div class="text-center"><img src="view/images/ajax-loader.gif"> Cargando...</div>');
		
		$.ajax({
			url: 'index.php?controller=CargarParticipes&action=obtenerAportesValidacion',
			method: 'POST',
			dataType: 'json',
			data:{ id_participe : view.hdn_id_participes.val() }
		}).done(function(x){
			element.html(x.html);		
		}).fail(function(xhr, status, error){
			element.html('<span> Datos No encontrados.</span>');
		});				
	}
}

var obtener_historial_moras	= function(){
	
	var element = $("#div_pnl_historial_moras");
	if( element.length ){	
		
		element.html('<div class="text-center"><img src="view/images/ajax-loader.gif"> Cargando...</div>');
		
		$.ajax({
			url: 'index.php?controller=CargarParticipes&action=obtenerHistorialMoras',
			method: 'POST',
			dataType: 'json',
			data:{ id_participe : view.hdn_id_participes.val() }
		}).done(function(x){
			element.html(x.html);		
		}).fail(function(xhr, status, error){
			element.html('<span> ERROR al buscar datos encontrados.</span>');
		});
		
		//element.collapse("show")
				
	}
}

var obtener_fecha_actual	= function(){	
	var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var output = d.getFullYear() + '-' + ( ( ''+ month ).length < 2 ? '0' : '' ) + month + '-' + ( (''+day).length < 2 ? '0' : '' ) + day;
	return output;
}

var imprimir_tabla_amortizacion	= function(){
	
	var DataDetalle = $("#div_tabla_amortizacion").find('table:nth-child(1)');
	var arrayData = [];
	var arrayFila = [];
	if( DataDetalle.length )
	{
	    $.each(DataDetalle.find('tbody tr'),function(i,v){
	        var fila = $(this);
	        $.each(fila.find('td'),function(ia,va){
	            var columna    = $(this);
	            arrayFila.push( columna.text())
	        })
	        arrayData.push(arrayFila);
	        arrayFila=[];
	        
	    })
	    
	    var params = { "datos_tabla":JSON.stringify(arrayData), 
	    	"tipo_creditos":view.tipo_creditos.find("option:selected").text(), 
	    	"monto_creditos":view.monto_creditos.val() }
		
		var form = document.createElement("form");
		form.setAttribute("id", "frm_reporte_simulacion");
	    form.setAttribute("method", "post");
	    form.setAttribute("action", "index.php?controller=CargarParticipes&action=imprimirSimulacionCredito");
	    form.setAttribute("target", "_blank");   
	    
	    for (var i in params) {
	        if (params.hasOwnProperty(i)) {
	            var input = document.createElement('input');
	            input.type = 'hidden';
	            input.name = i;
	            input.value = params[i];
	            form.appendChild(input);
	        }
	    }
	        
	    document.body.appendChild(form); 
	    form.submit();    
	    document.body.removeChild(form);	    
	} 	
}

function roundNumber(value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}


/**** TERMINAMOS CON LA INSERSION DE CREDITOS *****/ 

/*** VIDEO ***/

function video(){

    imgficha = 'view/images/SIMULADOR.mp4';

$("#reproducir_video").attr({'src':imgficha});
	
	
}


function scroll(){



$('html, body, #mostrarmodalIntro').animate({scrollTop: 30 }, "slow"); 

//$("#reproducir_video").contents().find("video").trigger('play');


}


$("#btn_video").on('click',function(e){

     $("#mostrarmodalIntro").modal("show"); 

   //setTimeout(function(){ video(); }, 1000);
   //setTimeout(function(){ scroll(); }, 2500);


});

/*** END VIDEO ***/

