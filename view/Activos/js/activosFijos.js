$(document).ready(function(){
	
	consultaActivos();
})

/*
 * fn para poner en mayusculas
 */
 $("input#responsable_activos_fijos").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })

 $("input#nombre_activos_fijos").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })
 
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


$(document).ready(function(){
    
    $("#Guardar").click(function() 
	{
    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

    	var id_oficina = $("#id_oficina").val();
    	var id_tipo_activos_fijos = $("#id_tipo_activos_fijos").val();
    	var id_departamento = $("#id_departamento").val();
    	var id_estado = $("#id_estado").val();
    	var fecha_activos_fijos = $("#fecha_activos_fijos").val();
    	var responsable_activos_fijos = $("#responsable_activos_fijos").val();
    	var nombre_activos_fijos = $("#nombre_activos_fijos").val();
    	var valor_activos_fijos = $("#valor_activos_fijos").val();
    	var imagen_activos_fijos = $("#imagen_activos_fijos").val();
    	
    	if (id_oficina == 0)
    	{
	    	
    		$("#mensaje_id_oficina").text("Introduzca Una Oficina");
    		$("#mensaje_id_oficina").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_oficina").fadeOut("slow"); //Muestra mensaje de error
            
		}   

    	if (id_tipo_activos_fijos == 0)
    	{
	    	
    		$("#mensaje_id_tipo_activos_fijos").text("Introduzca Un Tipo");
    		$("#mensaje_id_tipo_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_tipo_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		} 
    	if (id_departamento == 0)
    	{
	    	
    		$("#mensaje_id_departamento").text("Introduzca Un Departamento");
    		$("#mensaje_id_departamento").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_departamento").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (id_estado == 0)
    	{
	    	
    		$("#mensaje_id_estado").text("Introduzca Un Estado");
    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (fecha_activos_fijos == "")
    	{
	    	
    		$("#mensaje_fecha_activos_fijos").text("Introduzca Una Fecha");
    		$("#mensaje_fecha_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_fecha_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (responsable_activos_fijos == "")
    	{
	    	
    		$("#mensaje_codigo_activos_fijos").text("Introduzca Un Responsable");
    		$("#mensaje_codigo_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_codigo_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (nombre_activos_fijos == "")
    	{
	    	
    		$("#mensaje_nombre_activos_fijos").text("Introduzca Un Nombre");
    		$("#mensaje_nombre_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_nombre_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	if (valor_activos_fijos == "")
    	{
	    	
    		$("#mensaje_valor_activos_fijos").text("Introduzca Un Valor");
    		$("#mensaje_valor_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_valor_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (imagen_activos_fijos == "")
    	{
	    	
    		$("#mensaje_imagen_activos_fijos").text("Introduzca Una Imagen");
    		$("#mensaje_imagen_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_imagen_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	


    	
	}); 


        $( "#id_oficina" ).focus(function() {
		  $("#mensaje_id_oficina").fadeOut("slow");
	    });

        $( "#id_tipo_activos_fijos" ).focus(function() {
			  $("#mensaje_id_tipo_activos_fijos").fadeOut("slow");
		});

        $( "#id_departamento" ).focus(function() {
			  $("#mensaje_id_departamento").fadeOut("slow");
		});
		
        $( "#id_estado" ).focus(function() {
			  $("#mensaje_id_estado").fadeOut("slow");
		});
        $( "#fecha_activos_fijos" ).focus(function() {
			  $("#mensaje_fecha_activos_fijos").fadeOut("slow");
		});
        
        $( "#responsable_activos_fijos" ).focus(function() {
			  $("#mensaje_codigo_activos_fijos").fadeOut("slow");
		});
        $( "#nombre_activos_fijos" ).focus(function() {
			  $("#mensaje_nombre_activos_fijos").fadeOut("slow");
		});
        $( "#valor_activos_fijos" ).focus(function() {
			  $("#mensaje_valor_activos_fijos").fadeOut("slow");
		});
        $( "#imagen_activos_fijos" ).focus(function() {
			  $("#mensaje_imagen_activos_fijos").fadeOut("slow");
		});
    
        
	        	      
		    
}); 

$("#activos_fijos_registrados").on("click",".editaActivo",function(event){
	
	var parametros = {
			peticion : "ajax",
			activoId : $(this).attr("id")
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=ActivosFijos&action=editActivo",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.value == 1){
			
			console.log(datos.data);
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		alert(err);
	})
	
	event.preventDefault()
})


