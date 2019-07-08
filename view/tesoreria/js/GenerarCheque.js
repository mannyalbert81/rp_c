$(document).ready(function(){
	
<<<<<<< HEAD
	
	init();
	
	/* para carga de listados */
	// consultaActivos();
	cargaModImpuestos();
		
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * 
 * @returns
 */
function init(){
	
	// $(".inputDecimal").val('0.00');
	
	/* para ver clase de errores, cambiar stilo cuando son de grupo */	
	$("div.input-group").children("div.errores").css({"margin-top":"-10px","margin-left":"0px","margin-right":"0px"});
	$(".field-sm").css({"font-size":"12px"});
	
	$("#impuestos_cuentas_pagar").hide();
	
	var fechaServidor = $("#fechasistema").text();
		
	$("#fecha_cheque").inputmask("datetime",{
	     mask: "y-2-1", 
	     placeholder: "yyyy-mm-dd", 
	     leapday: "-02-29", 
	     separator: "-", 
	     alias: "dd-mm-yyyy",
	     clearIncomplete: true,
		 rightAlign: true,		 
		 yearrange: {
				minyear: 1950,
				maxyear: 2019
			},
		oncomplete:function(e){
			if( (new Date($(this).val()).getTime() > new Date(fechaServidor).getTime()))
		    {
				$(this).notify("Fecha no puede ser Mayor",{ position:"buttom left", autoHideDelay: 2000});
				$(this).val('')
		    }
		}
	});
	
	
}

/*******************************************************************************
 * funcion para poner mayusculas
 * 
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});


function numeros(e){
	  var key = window.event ? e.which : e.keyCode;
	  if (key < 48 || key > 57) {
	    e.preventDefault();
	  }
 }


$("#distribucion_cheque").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	$("#lista_distribucion_cheque").html('');
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=distribucionCheque",
		type:"POST",
		dataType:"json",
		data:{id_cuentas_pagar:_id_cuentas_pagar}
	}).done(function(x){
		$("#lista_distribucion_cheque").html(x.tabla);
		console.log(x);
	}).fail(function(xhr, status, error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		if( mensaje !== null ){
			var resmsg = mensaje[1]
			swal( {
				 title:"Generar Cheque",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		}
		 
	})
})

$("#genera_cheque").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	
	var parametros = {
		id_cuentas_pagar:_id_cuentas_pagar
	}
	
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=generaCheque",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		if( mensaje !== null ){
			var resmsg = mensaje[1]
			swal( {
				 title:"Generar Cheque",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		}
	})
})

 /* PARA VENTANAS MODALES */
 /***************************************************************************
	 * funcion abre modal para generacion de lote
	 * 
	 * @param event
	 * @returns
	 */
 $('#mod_lote').on('show.bs.modal', function (event) {
	 	 
 }); 
 
 
 /* PARA SUBMIT DE MODALES */
 


/* PARA LISTA EN MODALES */
/* Listar impuestos aplicados */


/*******************************************************************************
 * dc 2019-05-06
 * 
 * @returns
 */
function cargaModImpuestos(){

	let $modimpuestos = $("#mod_id_impuestos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaModImpuestos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$modimpuestos.empty();
		$modimpuestos.append("<option value = \"0\" >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$modimpuestos.append("<option value= " +value.id_impuestos +" >" + value.nombre_impuestos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$modimpuestos.empty();
		$modimpuestos.append("<option value = \"0\" >--Seleccione--</option>");
	})
}

/*******************************************************************************
 * dc 2019-05-07 desc para cargar impuestos en cuentas por cobrar
 * 
 * @returns
 */
function modListaImpuestosCxP(_page = 1){
	
	let _id_lote = $("#id_lote").val();
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=modListaImpuestosCxP",
		type:"POST",
		data:{peticion:'ajax',id_lote:_id_lote,page:_page}
	}).done(function(respuesta){
		
		$("#impuestos_cuentas_pagar").html(respuesta);
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err)		
		
	}).always(function(){
		
	})
	
	
}


/* PARA ACTIVAR BTN DISTRIBUCION */
/* cuando se haga click en boton btn_distribucion */

/*******************************************************************************
 * dc 2019-05-12
 * 
 * @returns
 */
$("#frm_cuentas_pagar").on("click","#btn_distribucion",function(event){
	
	console.log("llego");
	// aqui genera la distribucion de los pagos
	var $respuesta_distribucion = generaDistribucion();
	
	if(!$respuesta_distribucion){		
		return false;
	}
	
	resultadosCompra();
	ListaDistribucion();
		
})


/* PARA MODAL DE DISTRIBUCION */
// metodo se submit
$("#btn_distribucion_aceptars").on("click",function(){
	
	let divPadre = $("#distribucion_cuentas_pagar");	
	let filas = divPadre.find("table tbody > tr ");	
	let data = [];	
	let error = true;
	
	filas.each(function(){
		
		var _id_distribucion	= $(this).attr("id").split('_')[1],
			_desc_distribucion	= $(this).find("input:text[name='mod_dis_referencia']").val(),
			_id_plan_cuentas 	= $(this).find("input:hidden[name='mod_dis_id_plan_cuentas']").val();

		item = {};
	
		if(!isNaN(_id_distribucion)){
		
	        item ["id_distribucion"] 		= _id_distribucion;
	        item ["referencia_distribucion"]= _desc_distribucion;
	        item ['id_plan_cuentas'] 		= _id_plan_cuentas;
	        
	        data.push(item);
		}else{			
			error = false; return false;
		}
		
		if(isNaN(_id_plan_cuentas) || _id_plan_cuentas.length == 0 ){
			divPadre.find("table").notify("Cuentas Faltantes",{ position:"top center"});
			error = false; return false;
		}
				
	})
	
	// validar datos antes de enviar al controlador
	
	if(!error){	return false;}
	
	parametros 	= new FormData();
	arrayDatos 	= JSON.stringify(data); 
	parametros.append('lista_distribucion', arrayDatos);
 
	$.ajax({
		data: parametros,
		type: 'POST',
		url : "index.php?controller=CuentasPagar&action=InsertaDistribucion",
		processData: false, 
		contentType: false,
		dataType: "json"
	}).done(function(a){
		
		if(a.respuesta){
			
			 $("#mod_distribucion").modal('hide');
			// ocultar modal padre
			 swal({text: "Distribucion Realizada",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
		}
		
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText		
		console.log(err)
		
	})
	
	// console.log(data);
	
})

// PARA INPUT DE REFERENCIA
/* poner mismo texto a todos */
$("#distribucion_cuentas_pagar").on("keyup","input:text[name='mod_dis_referencia']",function(){
		
	let valorPrincipal = $(this).val();
	
	$("input:text[name='mod_dis_referencia']").each(function(index,value){		
		$(this).val(valorPrincipal);
	})
	
})



/* PARA DIV CON MENSAJES DE ERROR */
/* SE ACTIVAN AL ENFOCAR EN INPUT RELACIONADO */

$("#nombre_lote").on("focus",function(){
	$("#mensaje_id_lote").fadeOut().text("");
})

$("#mod_monto_documento").on("focus",function(){
	$("#mensaje_mod_monto_documento").fadeOut().text("");
})

$("#btn_mostrar_lista_impuestos").on("click",function(){	
	
	if($(this).find("i").hasClass("fa fa-search-plus")){
		
		$(this).find("span").text("Ocultar lista");
		$(this).find("i").removeClass().addClass("fa fa-search-minus");	
	}else{
		$(this).find("span").text("Ver lista");
		$(this).find("i").removeClass().addClass("fa fa-search-plus");
	}
	
	$("#impuestos_cuentas_pagar").toggle("slow");
	
})

function generaMensaje(mensaje,clase){
	let $div = $("<div></div>");
	let $btnClose = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
	'<span aria-hidden="true">&times;</span></button>';
	$div.text(mensaje);
	$div.addClass(clase);
	$div.append($btnClose);
	return $div;
	
	
}


// PARA EL SUBMIT DE GUARDADO PRINCIPAL
/* guardar cuentas por pagar */

/*******************************************************************************
 * dc 2019-05-17
 */
$("#frm_cuentas_pagar").on("submit",function(event){
	
	if($("#id_lote").val().length == 0 && !isNaN($("#id_lote").val())){
		$("#nombre_lote").notify("Debe ingresar el lote",{position:"button"});
		$("html, body").animate({ scrollTop: $(nombre_lote).offset().top-120 }, 1000);
		return false;
	}
		
	var parametros = $(this).serialize();
	
	$.ajax({
		beforeSend:null,
		url:"index.php?controller=CuentasPagar&action=InsertCuentasPagar",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		
		if(x.error != ''){
			
			swal({text: x.error,
		  		  icon: "error",
		  		  button: "Aceptar",
		  		  dangerMode: true
		  		});
		}
		
		if(x.hasOwnProperty('respuesta')){
			
			swal({title:"",text:x.mensaje,icon:"success"})
    		.then((value) => {
    			let loteUrl = $("#id_lote").val();
    			let urlReporte = "index.php?controller=CuentasPagar&action=Reporte_Cuentas_Por_Pagar&id_lote="+loteUrl;
    			window.open(urlReporte,"_blank");    			
    			$('#smartwizard').smartWizard("reset");
    			window.location.reload();
    		});
			
		}
		
		console.log(x);
		
	}).fail(function(xhr,status,error){
		
		let err = xhr.responseText
		
		console.log(err);
	})
	
	
	
	event.preventDefault()
})

=======
	init();
	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	$("#impuestos_cuentas_pagar").hide();
	$("#genera_cheque").attr("disabled",true);
	
	var fechaServidor = $("#fechasistema").text();
		
	$("#fecha_cheque").inputmask("datetime",{
	     mask: "y-2-1", 
	     placeholder: "yyyy-mm-dd", 
	     leapday: "-02-29", 
	     separator: "-", 
	     alias: "dd-mm-yyyy",
	     clearIncomplete: true,
		 rightAlign: true,		 
		 yearrange: {
				minyear: 1950,
				maxyear: 2019
			},
		oncomplete:function(e){
			if( (new Date($(this).val()).getTime() != new Date(fechaServidor).getTime()))
		    {
				$(this).notify("Fecha no puede ser Mayor",{ position:"buttom left", autoHideDelay: 2000});
				$(this).val('')
		    }
		}
	});
	
	
}

/*******************************************************************************
 * funcion para poner mayusculas
 * 
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});


$("#distribucion_cheque").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	var obj_comentario_cheque = $("#comentario_cheque");
	if(obj_comentario_cheque.val().length == 0 || obj_comentario_cheque.val() == ''){
		obj_comentario_cheque.notify("Ingrese comentario de pago",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	$("#mod_distribucion_pago").find("#mod_identificacion_proveedor").val($("#identificacion_proveedor").val());
	$("#mod_distribucion_pago").find("#mod_id_moneda").val($("#id_moneda").val());
	$("#mod_distribucion_pago").find("#mod_total_cuentas_pagar").val($("#total_lote").val());
	$("#mod_distribucion_pago").find("#mod_nombre_proveedor").val($("#nombre_proveedor").val());
	
	$("#lista_distribucion_cheque").html('');
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=distribucionCheque",
		type:"POST",
		dataType:"json",
		data:{id_cuentas_pagar:_id_cuentas_pagar}
	}).done(function(x){
		$("#lista_distribucion_cheque").html(x.tabla);
		console.log(x);
		$("#lista_distribucion_cheque").find("input[name='mod_dis_referencia']").val($("#comentario_cheque").val()); 
	}).fail(function(xhr, status, error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		if( mensaje !== null ){
			var resmsg = mensaje[1]
			swal( {
				 title:"Generar Cheque",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		}
		 
	})
})

$("#genera_cheque").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();	
	var _numero_cheque = $("#numero_cheque").val();	
	var _fecha_cheque = $("#fecha_cheque").val();	
	var _comentario_cheque = $("#comentario_cheque").val();
	var _id_bancos = $("#id_bancos").val();
	
	var parametros = {
		id_cuentas_pagar:_id_cuentas_pagar,numero_cheque:_numero_cheque,
		fecha_cheque:_fecha_cheque,comentario_cheque:_comentario_cheque,
		id_bancos: _id_bancos
	}
	
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=generaCheque",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x);
		if(x.comprobante.valor == 1){
			
			var cuentas_pagar_id = x.cuentaspagar.id_cuentas_pagar;
			var comprobante_id = x.comprobante.id_comprobante;
			var datosFomulario = {id_comprobante:comprobante_id,id_cuentas_pagar:cuentas_pagar_id}
		    
			
			swal({
				title:"GENERACION CHEQUE",
				icon:"success",
				text:x.comprobante.mensaje
			}).then(function(){
				FormularioPost("index.php?controller=GenerarCheque&action=generaReporteCheque","blank",datosFomulario);
				window.open("index.php?controller=Pagos&action=Index","_self");
			})
			
		}
		if(x.comprobante.valor == -1){
			swal({
				title:"GENERACION CHEQUE",
				icon:"error",
				text:x.comprobante.mensaje
			})
		}
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		if( mensaje !== null ){
			var resmsg = mensaje[1]
			swal( {
				 title:"Generar Cheque",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		}
	})
})

function FormularioPost(url,target,params){
	 
	 var form = document.createElement("form");
	 form.setAttribute("id", target);
     form.setAttribute("method", "post");
     form.setAttribute("action", url);
     form.setAttribute("target", target);

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
 

/* VENTANAS MODALES */
// metodo se submit
$("#btn_distribucion_aceptar").on("click",function(){
	
	$("#genera_cheque").attr("disabled",false);
	
})
>>>>>>> branch 'master' of https://github.com/mannyalbert81/rp_c.git

