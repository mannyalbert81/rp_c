$(document).ready(function(){
	
	/* para ver clase de errores, cambiar stilo cuando son de grupo*/	
	$("div.input-group").children("div.errores").css({"margin-top":"-10px","margin-left":"0px","margin-right":"0px"});
	$(".field-sm").css({"font-size":"12px"});
	
	$(".cantidades1").inputmask();
	devuelveConsecutivoCxP();
	cargaTipoDocumento();
	cargaFormasPago();
	cargaBancos();
	cargaMoneda();
	
	/*para carga de modales*/
	cargaFrecuenciaLote();
	
	/*para carga de listados*/
	consultaActivos();
	cargaModImpuestos();
	
	/*$("#nombre_lote").notify(
			  "I'm to the right of this box", 
			  { position:"buttom" }
			);
	*/	
		
})

function numeros(e){
	  var key = window.event ? e.which : e.keyCode;
	  if (key < 48 || key > 57) {
	    e.preventDefault();
	  }
 }

/***
 * function to upload formas pago
 * dc 2019-04-18
 * @returns
 */
function cargaFormasPago(){
	
	let $formapago = $("#id_forma_pago");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaFormasPago",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$formapago.empty();
		$formapago.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$formapago.append("<option value= " +value.id_forma_pago +" >" + value.nombre_forma_pago  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$formapago.empty();
	})
}

/***
 * function to upload formas pago
 * dc 2019-04-18
 * @returns
 */
function cargaTipoDocumento(){
	
	let $tipoDocumento = $("#id_tipo_documento");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaTipoDocumento",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tipoDocumento.empty();
		$tipoDocumento.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$tipoDocumento.append("<option value= " +value.id_tipo_documento +" >" + value.nombre_tipo_documento  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoDocumento.empty();
	})
}


/***
 * function to upload bancos
 * dc 2019-04-18
 * @returns
 */
function cargaBancos(){
	let $bancos = $("#id_bancos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaBancos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$bancos.empty();
		$bancos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$bancos.append("<option value= " +value.id_bancos +" >" + value.nombre_bancos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$bancos.empty();
	})
}

/***
 * function to listar Moneda
 * dc 2019-04-18
 * @returns
 */
function cargaMoneda(){
	let $moneda = $("#id_moneda");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaMoneda",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$moneda.empty();
		
		$.each(datos.data, function(index, value) {
			$moneda.append("<option value= " +value.id_moneda +" >" + value.signo_moneda+"-"+value.nombre_moneda  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$moneda.empty();
	})
}

/***
 * function to search proveedores
 * @returns
 */
$( "#cedula_proveedor" ).autocomplete({

	source: "index.php?controller=Proveedores&action=buscaProveedorByCedula",
	minLength: 6,
    select: function (event, ui) {
       // Set selection          
       $('#id_proveedor').val(ui.item.id);
       $('#cedula_proveedor').val(ui.item.value);
       $("#nombre_proveedor").val(ui.item.nombre);
       $("#email_proveedor").val(ui.item.email);
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#cedula_usuarios').val();            
        return false; 
    } 
}).focusout(function() {
	
});

function devuelveConsecutivoCxP(){
	
	let $numeroComprobante = $("#num_comprobante");
	let $idconsecutivo = $("#id_consecutivo");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=DevuelveConsecutivoCxP",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		let array = datos.data[0];
		
		$numeroComprobante.val(array.numero_consecutivos);
		$idconsecutivo.val(array.id_consecutivos);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar consecutivos de Cuentas X Pagar');
		
	})
}

/*
 * fn para poner en mayusculas
 */
 $("input#nombre_lote").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })

 $("input#nombre_activos_fijos").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })
 
 /* PARA LISTADO DE DATOS*/
 function consultaActivos(page=1){
	
	parametros = {search:'',peticion:'ajax'}
	
	$.ajax({
		beforeSend:function(x){},
		url:"index.php?controller=ActivosFijos&action=cunsultaActivos",
		type:"POST",
		data:parametros,
		dataType:"html"
	}).done(function(data){
		
		$("#activos_fijos_registrados").html(data);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText;
		
		console.log(err);
	})
}
 
 /* PARA EVITAR SOBRECARGA DE PAGINA */
 
 /*$(window).on('beforeunload', function(){
	  return "Good Bye";
	});*/
 /*function myFunction() {
	  return "Write something clever here...";
	}*/

 /*PARA VENTANAS MODALES*/
  
 $('#mod_lote').on('show.bs.modal', function (event) {
	 
	 $("#mod_descripcion_lote").val("");
	 let $id_lote = $("#id_lote").val();
	 if( $id_lote > 0 ){ $("#nombre_lote").notify("Lote ya Generado",{ position:"buttom left"}); return false; }
	 let nombreLote = $("#nombre_lote").val();	 
	 if(nombreLote.length == 0){ $("#nombre_lote").notify("ingrese nombre lote",{ position:"buttom left"});  return false;}
	 var modal = $(this)
	 modal.find('#mod_nombre_lote').val($("#nombre_lote").val())

 });
 
 $('#mod_impuestos').on('show.bs.modal', function (event) {
	 
	 let _monto_cuentas_pagar = $("#monto_cuentas_pagar").val();
	 let $id_lote = $("#id_lote").val();
	 if( $id_lote == 0 || $id_lote.length == 0 || isNaN($id_lote) ){ $("#nombre_lote").notify("Lote No generado",{ position:"buttom"}); return false; }
	 if( isNaN(_monto_cuentas_pagar) || _monto_cuentas_pagar == "" || _monto_cuentas_pagar.length == 0){
		 swal({text: "ingrese un monto (base de compra)",
	  		  icon: "info",
	  		  button: "Aceptar",
	  		});
		 return false;
	 }
	 
	 var modal = $(this);
	 
	 modal.find('#mod_monto_documento').val(_monto_cuentas_pagar);

	 });
 
 $('#mod_distribucion').on('show.bs.modal', function (event) {
	 
	 let modal = $(this);
	 
 });

 /***
  * dc 2019-04-29
  * para carga de frecuencia en lote 
  * @returns
  */
 function cargaFrecuenciaLote(){
		let $frecuencia = $("#mod_id_frecuencia");
		
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=CuentasPagar&action=cargaFrecuenciaLote",
			type:"POST",
			dataType:"json",
			data:null
		}).done(function(datos){		
			
			$frecuencia.empty();
			
			$.each(datos.data, function(index, value) {
				$frecuencia.append("<option value= " +value.id_frecuencia_lote +" >" + value.nombre_frecuencia_lote  + "</option>");	
	  		});
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			$moneda.empty();
		})
	}

 /*PARA SUBMIT DE MODALES*/
 
 /***
  * dc 2019-04-29
  * formulario de lote
  */
$("#frm_genera_lote").on("submit",function(event){
	
	let $id_lote = $("#id_lote").val();
	let $nombre_lote = $("#mod_nombre_lote").val();
	let $id_frecuencia = $("#mod_id_frecuencia").val();
	let $descripcion_lote = $("#mod_descripcion_lote").val();
	
	var parametros = {id_lote:$id_lote,nombre_lote:$nombre_lote,decripcion_lote:$descripcion_lote,id_frecuencia:$id_frecuencia}
	
	var $div_respuesta = $("#msg_frm_lote"); $div_respuesta.text("").removeClass();
	
	if($id_lote > 0){ $div_respuesta.html("<strong>¡Cuidado!<strong> Lote ya esta Generado").addClass("alert alert-warning"); return false;}	
		
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=generaLote",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(respuesta){
				
		if(respuesta.valor > 0){			
			$("#id_lote").val(respuesta.valor);
			$("#msg_frm_lote").text("Lote Generado").addClass("alert alert-success");
		}
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
		$("#id_lote").val("0");
		$div_respuesta.text("Error al generar Lote").addClass("alert alert-warning");
		
	}).always(function(){
				
	})
	
	event.preventDefault();
})

/***
 * dc 2019-05-06
 * desc: agregar impuestos a la cuenta por pagar
 */
$("#btn_mod_agrega_impuestos").on("click",function(event){
	
	let _base_impuestos_cxp = $("#mod_monto_documento").val();
	let _id_impuestos = $("#mod_id_impuestos").val();
	let _id_lote = $("#id_lote").val();
	let _mod_base_impuestos = $("#mod_monto_documento").val();
	
	if(_id_lote == 0){
		$("#nombre_lote").notify("Lote No generado",{ position:"buttom"})
		return null;
	}
	
	if(_id_impuestos == 0){
		alert('Seleccione impuesto')
		return null;
	}
	
	if(_mod_base_impuestos.length == 0 || _mod_base_impuestos == "" || isNaN(_mod_base_impuestos) ){
		$("#mensaje_mod_monto_documento").text("Ingrese un valor").fadeIn("slow");
		return null;
	}
	
	var parametros = {base_impuestos:_base_impuestos_cxp, id_impuestos:_id_impuestos, id_lote:_id_lote}
	
	$("#msg_frm_impuestos").html("");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=ModAgregaImpuestos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(respuesta){	
		
		if(respuesta.respuesta == 1){
			
			var $divMensaje = generaMensaje(respuesta.mensaje,"alert alert-success");
			$("#msg_frm_impuestos").append($divMensaje);
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
			modListaImpuestosCxP()
	})
	
	//console.log('click en guardar impuesto')
	event.preventDefault();
	
})

/* PARA LISTA EN MODALES*/
/*Listar impuestos aplicados*/

/***
 *dc 2019-05-02
 *funcion listar impuestos aplicados a cuentas por pagar 
 * @returns
 */
function load_impuestos_cpagar(page=1){
	
	let parametros = null;
	
	$.ajax({
		sendBefore: function(){},
		url:"index.php?controller=CuentasPagar&action=listarImpuestos",
		data:parametros
	}).done(function(respuesta){
		$("#impuestos_cuentas_pagar").html(respuesta);
	})
	
}

/***
 * dc 2019-05-06
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

/***
 * dc 2019-05-07
 * desc para cargar impuestos en cuentas por cobrar
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

/***
 * dc 2019-05-07
 * desc: elimina registro de los impuestos agregados a las ctas. pagar.
 * @returns
 */
function delImpuestosCxP(id){
	
	$("#msg_frm_impuestos").html("");
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CuentasPagar&action=modDelImpuestosCxP",
		type:"POST",
		dataType:"json",
		data:{id_cuentas_pagar_impuestos:id}
	}).done(function(datos){		
		
		var $divMensaje = generaMensaje("Registro Eliminado","alert alert-warning");
		
		if(datos.data > 0){
			
			$("#msg_frm_impuestos").append($divMensaje);
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		modListaImpuestosCxP();
	})
	
	return false;
	
}

/*	PARA ACTIVAR BTN DISTRIBUCION */
/* cuando se haga click en boton btn_distribucion */

/***
 * funcion que envia datos para realizar la funcion de distribucion
 * @returns
 */
function generaDistribucion(){
	var $respuesta = false;
	
	var $lote_num = $("#id_lote").val();
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=generaDistribucion",
		type: "POST",
		dataType: "json",
		async: false,
		data: {id_lote:$lote_num}
	}).done(function(respuesta){
		
		$respuesta = true;
		
	}).fail(function(xhr, status, error){
		var err = xhr.responseText
		console.log(err);
		
	})
	
	return $respuesta;
} 

/***
 * funcion que envia datos para realizar consulta de distribucion
 * @returns
 */
function ListaDistribucion( _page = 1){
	var $respuesta = false;
	
	var $lote_num = $("#id_lote").val();
	
	var $divtabla = $("#distribucion_cuentas_pagar")
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=listaDistribucion",
		type: "POST",
		dataType: "html",
		data: {peticion:"ajax", search:"",id_lote:$lote_num,page:_page}
	}).done(function(respuesta){
		
		$divtabla.html(respuesta);
		
	}).fail(function(xhr, status, error){
		var err = xhr.responseText
		console.log(err);
		
	})
	
	return $respuesta;
}

/****
 * dc 2019-05-12
 * @returns
 */
$("#btn_distribucion").on("click",function(event){
	
	//aqui genera la distribucion de los pagos
	var $respuesta_distribucion = generaDistribucion();
	
	if(!$respuesta_distribucion){		
		return false;
	}
	
	ListaDistribucion();
		
})

//PARA EVENTO KEYPRESS 
/*para input con clase distribucion*/

$("#distribucion_cuentas_pagar").on("focus","input.distribucion.distribucion_autocomplete[type=text]",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 6,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=CuentasPagar&action=autompletePlanCuentas",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {
     	       	// Set selection  
    			let fila = _elemento.closest("tr");
    			let in_nombre_plan_cuentas = fila.find("input:text[name='mod_dis_nombre']")
    			let in_id_plan_cuentas = fila.find("input:hidden[name='mod_dis_id_plan_cuentas']")
    			let in_codigo_plan_cuentas = fila.find("input:text[name='mod_dis_codigo']")
    			
    			if(ui.item.id == ''){
    				 _elemento.closest("table").notify("Digite Cod. Cuenta Valido",{ position:"top center"});
    				 return;
    			}
    			
    			in_nombre_plan_cuentas.val(ui.item.nombre);
    			in_codigo_plan_cuentas.val(ui.item.value);
    			in_id_plan_cuentas.val(ui.item.id);
    			     	     
     	    },
     	   appendTo: "#mod_distribucion",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null){
     			   
     			 _elemento.closest("tr").find("input:hidden[name='mod_dis_id_plan_cuentas']").val("");
     			 _elemento.closest("table").notify("Digite Cod. Cuenta Valido",{ position:"top center"});    			
     			 
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
});

/* PARA MODAL DE DISTRIBUCION */
//metodo se submit
$("#btn_distribucion_aceptar").on("click",function(){
	
	let divPadre = $("#distribucion_cuentas_pagar");
	
	let filas = divPadre.find("table tbody > tr ");
	
	let data = [];
	
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
		}		
	})
	
	//validar datos antes de enviar al controlador
	
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
		console.log(a)
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText
		
		console.log(err)
		
	})
	
	console.log(data);
	
})

//PARA INPUT DE REFERENCIA
/*poner mismo texto a todos*/
$("#distribucion_cuentas_pagar").on("keyup","input:text[name='mod_dis_referencia']",function(){
		
	let valorPrincipal = $(this).val();
	
	$("input:text[name='mod_dis_referencia']").each(function(index,value){		
		$(this).val(valorPrincipal);
	})
	
})



/*PARA DIV CON MENSAJES DE ERROR*/
/*SE ACTIVAN AL ENFOCAR EN INPUT RELACIONADO*/

$("#nombre_lote").on("focus",function(){
	$("#mensaje_id_lote").fadeOut().text("");
})

$("#mod_monto_documento").on("focus",function(){
	$("#mensaje_mod_monto_documento").fadeOut().text("");
})

$("#btn_mostrar_lista_impuestos").on("click",function(){ $("#impuestos_cuentas_pagar").toggle("slow");})

function generaMensaje(mensaje,clase){
	let $div = $("<div></div>");
	let $btnClose = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
	'<span aria-hidden="true">&times;</span></button>';
	$div.text(mensaje);
	$div.addClass(clase);
	$div.append($btnClose);
	return $div;
}
