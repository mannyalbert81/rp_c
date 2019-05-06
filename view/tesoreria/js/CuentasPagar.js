$(document).ready(function(){
	
	/* para ver clase de errores, cambiar stilo cuando son de grupo*/	
	$("div.input-group").children("div.errores").css({"margin-top":"-10px","margin-left":"0px"});
	
	$(".cantidades1").inputmask();
	devuelveConsecutivoCxP();
	cargaFormasPago();
	cargaBancos();
	cargaMoneda();
	
	/*para carga de modales*/
	cargaFrecuenciaLote();
	
	/*para carga de listados*/
	consultaActivos();
		
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
	
	let $numeroPago = $("#numero_pago");
	let $idconsecutivo = $("#id_consecutivo");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=DevuelveConsecutivoCxP",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		let array = datos.data[0];
		
		$numeroPago.val(array.numero_consecutivos);
		$idconsecutivo.val(array.id_consecutivos);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar consecutivos de Cuentas X Pagar');
		
	})
}

/*
 * fn para poner en mayusculas
 */
 $("input#responsable_activos_fijos").on("keyup", function () {
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
	 if( $id_lote > 0 ){ $("#mensaje_id_lote").text("Lote Generado").fadeIn("slow"); return false; }
	 let nombreLote = $("#nombre_lote").val();	 
	 if(nombreLote.length == 0){ $("#mensaje_id_lote").text("ingrese nombre lote").fadeIn("slow"); return false;}
	 var modal = $(this)
	 modal.find('#mod_nombre_lote').val($("#nombre_lote").val())

 });
 
 $('#mod_impuestos').on('show.bs.modal', function (event) {
	 load_impuestos_cpagar(1);
	   var modal = $(this)

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


/*PARA DIV CON MENSAJES DE ERROR*/
/*SE ACTIVAN AL ENFOCAR EN INPUT RELACIONADO*/

$("#nombre_lote").on("focus",function(){
	$("#mensaje_id_lote").fadeOut().text("");
})
