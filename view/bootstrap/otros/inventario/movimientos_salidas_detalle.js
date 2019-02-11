$(document).ready(function(){
	
});

function rechazar_producto(id){
	
	var cantidad=document.getElementById('cantidad_producto_'+id).value;
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=rechazaproducto',
        data: "id_temp_salida="+id,
        dataType:'json',
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
        	swal(datos.mensaje);
    	}
	});
}

function aprobar_producto(id){

	var cantidad=document.getElementById('cantidad_producto_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
		swal('no es cantidad')
    	document.getElementById('cantidad_producto_'+id).focus();
    	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=apruebaproducto',
        data: "fila=1&id_temp_salida="+id+"&cantidad="+cantidad,
        dataType:'json',
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		swal(datos.mensaje);
    	}
	});
}