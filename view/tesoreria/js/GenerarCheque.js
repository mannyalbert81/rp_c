/**VARIABLES DE INICIO**/
var globalObjFecha = new Date(); 
var globalAnio = globalObjFecha.getFullYear();
$(document).ready(function(){
	
	init();
	loadChequera();
	
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
				maxyear: globalAnio
			},
		oncomplete:function(e){
			var lfechaServidor = fechaServidor.split("-");
			var lfechaPago 	= $(this).val().split("-");
			
			if( lfechaServidor[0]+""+lfechaServidor[1] != lfechaPago[0]+""+lfechaPago[1] )
			{
				$(this).notify("Fecha no Valida .. Mes ingresado no valido",{ position:"buttom left", autoHideDelay: 2000});
               $(this).val('');
			}
			
		}
	});
	
	$("#chk_pago_parcial_cheque").on( 'change', function() {
		fnValidaPagoParcial(this);
	});	
	
	$('#valor_parcial_cheque').inputmask({
	  alias: 'numeric', 
	  allowMinus: false,  
	  digits: 2, 
	  max: 999999.99
	});	
	
}

/***
 * @desc funcion para traer la chequera de la entidad
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadChequera(){	
	
	var $ddlChequera = $("#id_bancos");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=CargaChequera",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlChequera.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
			})
		}
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer la chequera de la entidad
 * @param none
 * @retuns void
 * @ajax si 
 */
function validaChequera(){	
	
	var $ddlChequera = $("#id_bancos");
	params = {id_bancos : $ddlChequera.val() };
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=CargaNumChequera",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.numchequera != undefined && x.numchequera != null ){			
			$("#numero_cheque").val( x.numchequera );
		}else{
			$("#numero_cheque").val( x.numchequera );
		}			
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
		$("#numero_cheque").val( "" );
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
	var ofecha_cheque	= $("#fecha_cheque");
	if( ofecha_cheque.val().length == 0 || ofecha_cheque.val() == '' )
	{
		ofecha_cheque.notify("Ingrese fecha de cheque",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if(obj_comentario_cheque.val().length == 0 || obj_comentario_cheque.val() == ''){
		obj_comentario_cheque.notify("Ingrese comentario de cheque",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	let $bancos = $("#id_bancos");
	if( $bancos.val() == "0" ){
		swal({title:"ERROR DISTRIBUCION",icon:"warning",text:"Seleccione Chequera"})
		return false;
	}
	let $referencia_cheque = $("#comentario_cheque");
	let $divResultados = $("#lista_distribucion_cheque");
	$divResultados.html('');
	let $modal = $("#mod_distribucion_pago");
	
	// dc 2020/07/22
	var total_cheque 	= $("#saldo_cheque");
	var valor_cheque_parcial	= $("#valor_parcial_cheque");
	var chk_valor_parcial		= $("#chk_pago_parcial_cheque");
	var valor_a_pagar_cheque 	= total_cheque.val();
	
	if( chk_valor_parcial.val() == "1" ){
		
		if( parseFloat( valor_cheque_parcial.val() ) < 0 ||  valor_cheque_parcial.val().length == 0 || isNaN( valor_cheque_parcial.val() ) ){
			
			swal({title:"ERROR DISTRIBUCION",icon:"warning",text:"Monto parcial no ingresado"});
			return false;
		}
		
		if( parseFloat( valor_cheque_parcial.val() ) > parseFloat( total_cheque.val() ) ){
			swal({title:"ERROR DISTRIBUCION",icon:"warning",text:"Monto parcial supera a valor total"});
			return false;
		}
		
		valor_a_pagar_cheque = valor_cheque_parcial.val();
	}
	
	var parametros = {
			'id_cuentas_pagar':_id_cuentas_pagar,
			'id_bancos':$bancos.val(),
			'referencia_cheque':$referencia_cheque.val(),
			'check_pago_parcial': chk_valor_parcial.val(),
			'valor_pago_parcial': valor_cheque_parcial.val()
			};
	
	
	$("#mod_distribucion_pago").find("#mod_identificacion_proveedor").val($("#identificacion_proveedor").val());
	$("#mod_distribucion_pago").find("#mod_id_moneda").val($("#id_moneda").val());
	$("#mod_distribucion_pago").find("#mod_total_cuentas_pagar").val( valor_a_pagar_cheque );
	$("#mod_distribucion_pago").find("#mod_nombre_proveedor").val($("#nombre_proveedor").val());
	
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=distribucionCheque",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x);
		$divResultados.html(x.tabla_datos);
		
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
		$modal.modal('hide'); 
	})
})

$("#genera_cheque").on("click",function(){	
	
	swal({
		  title: "GENERACION CHEQUE",
		  text: "¿Desea generar el Cheque?.",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		}).then((generar) => {
			
		  if (generar) 
		  {			  
			    var _id_cuentas_pagar = $("#id_cuentas_pagar").val();	
				var _numero_cheque = $("#numero_cheque").val();	
				var _fecha_cheque = $("#fecha_cheque").val();	
				var _comentario_cheque = $("#comentario_cheque").val();
				var _id_bancos = $("#id_bancos").val();
				//dc 2020/07/22
				var check_pago_parcial	= $("#chk_pago_parcial_cheque").val();
				var valor_pago_parcial	= $("#valor_parcial_cheque").val();
				
				var parametros = {
					id_cuentas_pagar:_id_cuentas_pagar,numero_cheque:_numero_cheque,
					fecha_cheque:_fecha_cheque,comentario_cheque:_comentario_cheque,
					id_bancos: _id_bancos,
					'check_pago_parcial' : check_pago_parcial,
					'valor_pago_parcial' : valor_pago_parcial
				}
				
				$.ajax({
					url:"index.php?controller=GenerarCheque&action=generaCheque",
					type:"POST",
					dataType:"json",
					data:parametros
				}).done(function(x){
					
					if(x.comprobante.valor == 1)
					{						
						var pagoId	= x.pago.id_pagos;
						var datosFomulario = { 'id_pagos': pagoId };
						
						swal({
							title:"GENERACION CHEQUE",
							icon:"success",
							text:x.comprobante.mensaje
						}).then(function(){
							
							FormularioPost("index.php?controller=GenerarCheque&action=generaReporteCheque","blank",datosFomulario);
							window.open("index.php?controller=Pagos&action=Index","_self");
							//console.log(datosFomulario);
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
			  
			  
		  }else
		  {
			 //no action in this case
		  }
		});	
	
})

function FormularioPost(url,target,params){
	 
	 var form = document.createElement("form");
	 form.setAttribute("id", "frmchequeReport");
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


/************************************************************ CAMBIOS PARA VALOR PACIAL ****************************************/
var fnValidaPagoParcial	= function(a){
	var elemento = $(a);
	if( elemento.is(':checked') ) {
        // Hacer algo si el checkbox ha sido seleccionado
		elemento.val(1);
        $("#valor_parcial_cheque").attr("readonly",false).val("");
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
    	elemento.val(0);
        $("#valor_parcial_cheque").attr("readonly",true).val("0");
    }
}

