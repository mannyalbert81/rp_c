$(document).ready(function(){
	
      

     
});


$("#GuardarReclamos").on("click",function(event){
	
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
	
	
	
	//para nombre proveedor
	let $_nombres_form_reclamos = $("#nombres_form_reclamos");    	
	if( $_nombres_form_reclamos.val().length == 0 || $_nombres_form_reclamos.val() == '' ){
		$_nombres_form_reclamos.notify("Agregue nombre",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	let $_apellidos_form_reclamos = $("#apellidos_form_reclamos");    	
	if( $_apellidos_form_reclamos.val().length == 0 || $_apellidos_form_reclamos.val() == '' ){
		$_apellidos_form_reclamos.notify("Agregue apellidos",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	let $_edad_form_reclamos = $("#edad_form_reclamos");    	
	if( $_edad_form_reclamos.val().length == 0 || $_edad_form_reclamos.val() == '' ){
		$_edad_form_reclamos.notify("Agregue edad",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	
	let $_teleono_form_reclamos = $("#teleono_form_reclamos");    	
	if( $_teleono_form_reclamos.val().length == 0 || $_nombres_form_reclamos.val() == '' ){
		$_teleono_form_reclamos.notify("Agregue telefono",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	let $_celular_form_reclamos = $("#celular_form_reclamos");    	
	if( $_celular_form_reclamos.val().length == 0 || $_celular_form_reclamos.val() == '' ){
		$_celular_form_reclamos.notify("Agregue telefono",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	let $_nacionali_form_reclamos = $("#nacionali_form_reclamos");    	
	if( $_nacionali_form_reclamos.val().length == 0 || $_nacionali_form_reclamos.val() == '' ){
		$_nacionali_form_reclamos.notify("Agregue telefono",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	let $_email_form_reclamos = $("#email_form_reclamos");    	
	
	if( $_email_form_reclamos.val().length != 0 || $_email_form_reclamos.val() != '' ){
		
		if(!regex.test($_email_form_reclamos.val().trim())){
			$_email_form_reclamos.notify("Ingrese email valido",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
	}
	
	
	let $_direccion_form_reclamos = $("#direccion_form_reclamos");    	
	if( $_direccion_form_reclamos.val().length == 0 || $_direccion_form_reclamos.val() == '' ){
		$_direccion_form_reclamos.notify("Agregue direccion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	let $_detalle_form_reclamos = $("#detalle_form_reclamos");    	
	if( $_detalle_form_reclamos.val().length == 0 || $_detalle_form_reclamos.val() == '' ){
		$_detalle_form_reclamos.notify("Agregue detalle",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	
	//para proveedores id
	let $id_form_reclamos = $("#id_form_reclamos"); 
	
	var parametros = new FormData();
	
	parametros.append('id_form_reclamos',$id_form_reclamos.val());
    parametros.append('nombres_form_reclamos',$_nombres_form_reclamos.val());
	parametros.append('apellidos_form_reclamos',$_apellidos_form_reclamos.val());
    parametros.append('edad_form_reclamos',$_edad_form_reclamos.val());
	parametros.append('teleono_form_reclamos',$_teleono_form_reclamos.val());
    parametros.append('celular_form_reclamos',$_celular_form_reclamos.val());
	parametros.append('nacionali_form_reclamos',$_nacionali_form_reclamos.val());
    parametros.append('email_form_reclamos',$_email_form_reclamos.val());
	parametros.append('direccion_form_reclamos',$_direccion_form_reclamos.val());
    parametros.append('detalle_form_reclamos',$_detalle_form_reclamos.val());
	
	
	$.ajax({
		url:"index.php?controller=FormularioReclamos&action=AgregaReclamos",
		type:"POST",
		dataType:"json",
		data:parametros,
		contentType: false, //importante enviar este parametro en false
        processData: false,  //importante enviar este parametro en false
	}).done(function(x){
		//console.log(x)
		if( x.respuesta == 1 ){    			
			
			swal({title:"TRANSACCION OK",text:'FORMULARIO',icon:"success",closeOnClickOutside: false}).then( isValidate => { 
				
				let identificador =x.identificador;
    			let urlReporte = "index.php?controller=FormularioReclamos&action=ReporteReclamos&id_form_reclamos="+identificador;
    			window.open(urlReporte,"_blank"); 
				window.location.reload();
				
				 } );				
				 
		}
		
		//limpiarCampos();
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal( {
				 title:"Error",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		 	}
	}).always(function(){
		//ListaProveedores(1);
	})
	
	
	event.preventDefault();
})


/*FUNCIONES PARA LIMPIAR FORMULARIO*/
function limpiarCampos(){
	
	$("#nombre_proveedores").val("");    	
	$("#identificacion_proveedores").val("");    	
	$("#contactos_proveedores").val("");    	
	$("#direccion_proveedores").val("");    	
	$("#telefono_proveedores").val("");    	
	$("#email_proveedores").val("");    	
	$("#id_tipo_proveedores").val(0);  
	$('#id_bancos').val(0).trigger('change');
	$("#numero_cuenta_proveedores").val("");    	
	$("#id_tipo_cuentas").val(0); 
	$("#razon_social_proveedores").val("");
	$('#tipo_identificacion_proveedores option').eq(0).prop('selected', true);
	$("#forma_pago").val("0");
	$("#celular_proveedores").val("");
	$("#imagen_registro").val("");
	
	
}

 
 
  
