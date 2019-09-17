$(document).ready(function(){
	
	consultaCargaRecaudaciones();
	cargaEntidadPatronal();

})

function cargaEntidadPatronal(){
	
	let $ddlEntidadPatronal = $("#id_entidad_patronal");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CargaRecaudaciones&action=cargaEntidadPatronal",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlEntidadPatronal.empty();
		$ddlEntidadPatronal.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlEntidadPatronal.append("<option value= " +value.id_entidad_patronal +" >" + value.nombre_entidad_patronal  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlEntidadPatronal.empty();
	})
	
}

$("#id_entidad_patronal").on("focus",function(){
	$("#mensaje_id_entidad_patronal").text("").fadeOut("");
})

$("#id_carga_recaudaciones").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})



function consultaCargaRecaudaciones(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CargaRecaudaciones&action=consultaCargaRecaudaciones",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#carga_recaudaciones_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}





$("#btnGenerar").on("click",function(){
	
	let $entidadPatronal 	= $("#id_entidad_patronal"),
		$anioCargaRecaudaciones 	= $("#anio_carga_recaudaciones"),
		$mesCargaRecaudaciones 	= $("#mes_carga_recaudaciones"),
		$formatoCargaRecaudaciones	= $("#formato_carga_recaudaciones");
		$nombreCargaRecaudaciones	= $("#nombre_carga_recaudaciones");
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var parametros ={id_entidad_patronal:$entidadPatronal.val(),
			anio_carga_recaudaciones:$anioCargaRecaudaciones.val(),
			mes_carga_recaudaciones:$mesCargaRecaudaciones.val(),
			formato_carga_recaudaciones:$formatoCargaRecaudaciones.val(),
			nombre_carga_recaudaciones:$nombreCargaRecaudaciones.val(),
					
	}   
	
	$.ajax({
		beforeSend:fnBeforeAction('Estamos procesado la informacion'),
		url:"index.php?controller=CargaRecaudaciones&action=GenerarCargaRecaudaciones",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if(x.respuesta == 1){
			
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "success",
				 timer: 2000,
				 button: false,
				});			
				
		}
		if(x.respuesta == 2){
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
				
		}
		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		swal.close();
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
	})	
	
	event.preventDefault();
})

function fnBeforeAction(mensaje){
	/*funcion que se ejecuta antes de realizar peticion ajax*/
	swal({
        title: "RECAUDACIONES",
        text: mensaje,
        icon: "view/images/ajax-loader.gif",        
      })
}