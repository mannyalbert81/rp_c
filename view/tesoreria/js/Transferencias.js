$(document).ready(function(){
	
	init();
	devuelveconsecutivos();
	
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
 * dc 2019-07-08
 */
function devuelveconsecutivos(){
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=DevuelveConsecutivos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		console.log(x);
		$("#numero_pago").val(x.pagos.numero);
	}).fail(function(xhr,status,error){
		$("#numero_pago").val('');
	})
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

