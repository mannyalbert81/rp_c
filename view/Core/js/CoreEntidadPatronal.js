$(document).ready(function(){
	
	consultaEntidad();
	
	
})

/***
 * function to add record into table test_bancos
 * dc 2019-04-22
 * @param event
 * @returns
 */
$("#frm_entidad").on("submit",function(event){
	
	let _nombre_entidad_patronal = document.getElementById('nombre_entidad_patronal').value;
	let _ruc_entidad_patronal = document.getElementById('ruc_entidad_patronal').value;
	let _codigo_entidad_patronal = document.getElementById('codigo_entidad_patronal').value;
	let _tipo_entidad_patronal = document.getElementById('tipo_entidad_patronal').value;
	let _acronimo_entidad_patronal = document.getElementById('acronimo_entidad_patronal').value;
	let _direccion_entidad_patronal = document.getElementById('direccion_entidad_patronal').value;
	var _id_entidad_patronal = document.getElementById('id_entidad_patronal').value;
	var parametros = {nombre_entidad_patronal:_nombre_entidad_patronal,ruc_entidad_patronal:_ruc_entidad_patronal,codigo_entidad_patronal:_codigo_entidad_patronal,tipo_entidad_patronal:_tipo_entidad_patronal,acronimo_entidad_patronal:_acronimo_entidad_patronal,direccion_entidad_patronal:_direccion_entidad_patronal,id_entidad_patronal:_id_entidad_patronal}
	
	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CoreEntidadPatronal&action=InsertaEntidad",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Entidad",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_entidad_patronal").val(0);
		document.getElementById("frm_entidad").reset();	
		consultaEntidad();
	})

	event.preventDefault()
})

/***
 * function to update Table Bancos
 * dc 20119-04-22
 * @param id
 * @returns
 */
function editEntidad(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEntidadPatronal&action=editEntidad",
		type:"POST",
		dataType:"json",
		data:{id_entidad_patronal:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#nombre_entidad_patronal").val(array.nombre_entidad_patronal);
			$("#ruc_entidad_patronal").val(array.ruc_entidad_patronal);
			$("#codigo_entidad_patronal").val(array.codigo_entidad_patronal);
			$("#tipo_entidad_patronal").val(array.tipo_entidad_patronal);
			$("#acronimo_entidad_patronal").val(array.acronimo_entidad_patronal);
			$("#direccion_entidad_patronal").val(array.direccion_entidad_patronal);
			$("#id_entidad_patronal").val(array.id_entidad_patronal);
			
			
			$("html, body").animate({ scrollTop: $(nombre_entidad_patronal).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaEntidad();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-04-22
 * @param id
 * @returns
 */
function delEntidad(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEntidadPatronal&action=delEntidad",
		type:"POST",
		dataType:"json",
		data:{id_entidad_patronal:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Entidad",
		  		  text: "Registro Eliminado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaEntidad();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaEntidad(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEntidadPatronal&action=consultaEntidad",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#entidades_registradas").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

/***
 * funcion para cargar estado de tes_bancos
 * dc 2019-04-22
 * @returns
 */

$("#Guardar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	    	var nombre_entidad_patronal = $("#nombre_entidad_patronal").val();
	    	var ruc_entidad_patronal = $("#ruc_entidad_patronal").val();
	    	var codigo_entidad_patronal = $("#codigo_entidad_patronal").val();
	    	var tipo_entidad_patronal = $("#tipo_entidad_patronal").val();
	    	var acronimo_entidad_patronal = $("#acronimo_entidad_patronal").val();
	    	var direccion_entidad_patronal = $("#direccion_entidad_patronal").val();
	    	
	    	if (nombre_entidad_patronal == "")
	    	{
		    	
	    		$("#mensaje_nombre_entidad_patronal").text("Introduzca Un Nombre");
	    		$("#mensaje_nombre_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_nombre_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (ruc_entidad_patronal == "")
	    	{
		    	
	    		$("#mensaje_ruc_entidad_patronal").text("Introduzca Un Ruc");
	    		$("#mensaje_ruc_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_ruc_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (codigo_entidad_patronal == "")
	    	{
		    	
	    		$("#mensaje_codigo_entidad_patronal").text("Introduzca Una Entidad");
	    		$("#mensaje_codigo_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_codigo_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
	    	
	    	if (tipo_entidad_patronal == "")
	    	{
		    	
	    		$("#mensaje_tipo_entidad_patronal").text("Introduzca Un Tipo");
	    		$("#mensaje_tipo_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_tipo_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}

	    	if (acronimo_entidad_patronal == "")
	    	{
		    	
	    		$("#mensaje_acronimo_entidad_patronal").text("Introduzca Un Acrónimo");
	    		$("#mensaje_acronimo_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_acronimo_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (direccion_entidad_patronal == 0)
	    	{
		    	
	    		$("#mensaje_direccion_entidad_patronal").text("Introduzca Una Unidad Dirección");
	    		$("#mensaje_direccion_entidad_patronal").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_direccion_entidad_patronal").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
		}); 


	        $( "#nombre_entidad_patronal" ).focus(function() {
			  $("#mensaje_nombre_entidad_patronal").fadeOut("slow");
		    });

	        $( "#ruc_entidad_patronal" ).focus(function() {
				  $("#mensaje_ruc_entidad_patronal").fadeOut("slow");
			    });
	        $( "#codigo_entidad_patronal" ).focus(function() {
				  $("#mensaje_codigo_entidad_patronal").fadeOut("slow");
			    });
	        $( "#tipo_entidad_patronal" ).focus(function() {
				  $("#mensaje_tipo_entidad_patronal").fadeOut("slow");
			    });
	        $( "#acronimo_entidad_patronal" ).focus(function() {
				  $("#mensaje_acronimo_entidad_patronal").fadeOut("slow");
			    });
	        $( "#direccion_entidad_patronal" ).focus(function() {
				  $("#mensaje_direccion_entidad_patronal").fadeOut("slow");
			    });
	        
	        




