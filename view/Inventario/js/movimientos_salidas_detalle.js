$(document).ready(function(){
	
});

function rechazar_producto(id){
	

	let $elementoCantidad = $("#cantidad_producto_"+id);	
	let $rowProducto = $("#tr"+id); 
	let $elementoDisponible = $("#disponible_producto"+id);
	
	if( $rowProducto.data("estado") == "RECHAZADA" ){
		swal({			
			 title:"SOLICITUD",
			 text: "Producto ya se encuentra Anulado en solicitud",
			 icon:"info"
			})
		return false;
	}
	
	if( $rowProducto.data("estado") == "APROBADO" ){
		swal({
			 dangerMode: true,
			 title:"SOLICITUD",
			 text: "Producto no se puede rechazar",
			 icon:"error"
			})
		return false;
	}
	
	let parametros={id_temp_salida:id}
	
	$.ajax({
		url:"index.php?controller=MovimientosInv&action=rechazaproducto",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		
		if(x.respuesta == 1){
			 swal({
				 title:"SOLICITUD",
				 text: x.mensaje,
				 icon:"success"
				})	
		}
        	
		getDetalleSolicitud();
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal({
				 title:"Error",
				 dangerMode: true,
				 text: resmsg,
				 icon:"error"
				})	
			 
		 	}
	})
		
}

function aprobar_producto(id){
	
	let $elementoCantidad = $("#cantidad_producto_"+id);	
	let $rowProducto = $("#tr"+id); 
	let $elementoDisponible = $("#disponible_producto"+id);
	
	if( $rowProducto.data("estado") == "APROBADO" ){
		swal({
			 title:"SOLICITUD",
			 text: "Producto se encuentra Aprobado",
			 icon:"info"
			})
		return false;
	}
	
	if( $rowProducto.data("estado") == "RECHAZADA" ){
		swal({
			 title:"SOLICITUD",
			 text: "Producto ya ha sido rechazado",
			 icon:"info"
			})
		return false;
	}

	//Inicia validacion
	if (isNaN($elementoCantidad.val())){
		 swal({
			 title:"Error",
			 dangerMode: true,
			 text: "ingrese Cantidad Válida",
			 icon:"error"
			})			
    	document.getElementById('cantidad_producto_'+id).focus();
    	return false;
	}
	
	if($elementoCantidad.val()>$elementoDisponible.val()){		
		 swal({
			 title:"Error",
			 dangerMode: true,
			 text: "Cantidad Solicitada excede de la cantidad disponible",
			 icon:"error"
			})			
    	document.getElementById('cantidad_producto_'+id).focus();
    	return false;
	}
	
	if($elementoCantidad.val() <= 0){		
		 swal({
			 title:"Error",
			 dangerMode: true,
			 text: "Cantidad no puede ser igual o menor a cero",
			 icon:"error"
			})			
   	document.getElementById('cantidad_producto_'+id).focus();
   	return false;
	}
	
	let parametros={fila:1,id_temp_salida:id,cantidad:$elementoCantidad.val()}
	
	$.ajax({
		url:"index.php?controller=MovimientosInv&action=apruebaproducto",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if(x.respuesta == 1){
			 swal({
				 title:"SOLICITUD",
				 text: x.mensaje,
				 icon:"success"
				})	
		}
		getDetalleSolicitud();
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal({
				 title:"Error",
				 dangerMode: true,
				 text: resmsg,
				 icon:"error"
				})	
			 
		 	}
	})
	
}

$("button[name='btnIngresarSolicitud']").on("click",function(){
	
	
	$("button[name='btnIngresarSolicitud']").attr("disabled",true);	
	let $boton = $(this);	
	let $TipoSolicitud = $boton.val();
	let $MovimientoId = $("#id_movimiento_solicitud");
	let parametros = {
			peticion:'ajax',
			id_movimiento_solicitud:$MovimientoId.val(),
			tipo_solicitud:$TipoSolicitud,
	}
	
	//validacion cantidad de disponible versus solicitado	
	let divPadre = $("#div_lista_productos");	
	let filas = divPadre.find("table tbody > tr ");	
	let mensaje = "";	
	let error = true;
	
	filas.each(function(){
		
		var disponibleProducto	= $(this).find("input:hidden[name='pdisponible']").val(),
			solicitudProducto	= $(this).find("input:text[name='psolicitud']").val(),
			estadoProducto = $(this).data('estado');		
		
		console.log(isNaN(disponibleProducto))
		console.log(isNaN(solicitudProducto))
		console.log('disponible  -> '+disponibleProducto+' solicitado-> '+solicitudProducto)
		console.log('estado --> '+estadoProducto )
		
		if (solicitudProducto.length == 0 || solicitudProducto == ""){
			error = false; 
			mensaje = "-> Ingrese Una Cantidad";
			return false;
		}

		if( isNaN(disponibleProducto) || isNaN(solicitudProducto) ){
			error = false; 
			mensaje = "-> Revise Que solicita una cantidad valida"
			return false;
	        
		}
		
		if( parseInt(solicitudProducto) >   parseInt(disponibleProducto) ){	
			if(estadoProducto != 'RECHAZADA'){
				error = false; 
				mensaje = "-> Revise Disponibilidad Producto"
				return false;				
			}
			
		}
				
	})
	
	if(!error){
		swal({
			 title:"Error",
			 dangerMode: true,
			 text: mensaje,
			 icon:"error"
			})	
		$("button[name='btnIngresarSolicitud']").attr("disabled",false);	
		return false;
	}
	
	$.ajax({
		url:"index.php?controller=MovimientosInv&action=inserta_salida",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		
		if(x.respuesta == 1){
			 swal({
				 title:"SOLICITUD",closeOnClickOutside: false,
				 text: x.mensaje,
				 icon:"success"
				}).then((value) => {	    			
	    			window.location.reload();
	    		});				
		}
		if(x.respuesta == 0){
			 swal({
				 title:"ERROR SOLICITUD",closeOnClickOutside: false,
				 dangerMode: true,
				 text: x.mensaje,
				 icon:"error"
				})	
			getDetalleSolicitud();
		}
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal({
				 title:"Error",
				 dangerMode: true,
				 text: resmsg,
				 icon:"error"
				})	
			 
		 	}
	}).always(function(){$("button[name='btnIngresarSolicitud']").attr("disabled",false)});
	
	event.preventDefault();	
	
})



$('#frm_agrega_salida').on('submit',function(event){
	
	var parametros = $(this).serialize();	
	
	//console.log('accion=ajax&'+parametros)
	
	var btnform = document.getElementById('btnForm').value
	
	$.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=MovimientosInv&action=inserta_salida',
        type: 'POST',
        data: 'accion=ajax&'+parametros,
        dataType:'json',
        success: function(respuesta){
        	
        	//console.log(respuesta)
        	
        	//$("#frm_guardar_producto")[0].reset();
        	var valrespuesta = respuesta.mensaje.includes("ok")||false
        	
            if(valrespuesta){
            	
            	swal({
            		  title: "Salidas",
            		  text: respuesta.mensaje,
            		  icon: "success",
            		  buttons: {Aceptar: {text: "Aceptar",value: "ok" }}
            		})
            		.then((value) => {
        			  switch (value) {            			  
        			    case "ok":
        			    	window.location="index.php?controller=MovimientosInv&action=indexsalida"
        			      break;            			 
        			  }
            		});
				
                }else{
                	
                	swal({
              		  title: "Salidas",
              		  text: respuesta.mensaje,
              		  icon: "warning",
              		  button: "Aceptar",
              		  closeOnClickOutside: false,
              		});
                }
        	
        	
        		//setTimeout("redireccionarPagina()", 5000);
        
        	     
        },
        error: function(xhr,estado,error){
        	//console.log(xhr.responseText);
			 var err=xhr.responseText
			 
			 swal({
        		  title: "Error",
        		  text: "Error conectar con el Servidor \n "+err,
        		  icon: "error",
        		  button: "Aceptar",
        		});
        }
    });
	
	event.preventDefault();	
})

function redireccionarPagina() {
	window.location="index.php?controller=MovimientosInv&action=indexsalida"
}