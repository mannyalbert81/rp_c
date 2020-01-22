$(document).ready(function(){
	//InsertarPresupuestos();
	load_buscar_presupuestos(1);

});


$( "#id_plan_cuentas" ).autocomplete({
	 source: 'index.php?controller=Presupuestos&action=AutocompleteCodigoCuentas',
	  	minLength: 1
	 });
	                    		
	$("#id_plan_cuentas").focusout(function(){
	                    					
	$.ajax({
	url:'index.php?controller=Presupuestos&action=DevuelveNombreCodigoCuentas',
	type:'POST',
	dataType:'json',
	data:{codigo_plan_cuentas:$('#id_plan_cuentas').val()}
	}).done(function(respuesta){
	                    		
	$('#nombre_presupuestos_cabeza').val(respuesta.nombre_plan_cuentas);
	  
	$('#id_plan_cuentas_1').val(respuesta.id_plan_cuentas);
	
	
	                    					
	}).fail(function(respuesta) {
	                    						  
	$('#id_plan_cuentas').val("");
	$('#nombre_presupuestos_cabeza').val("");
	$('#id_plan_cuentas_1').val("0");
	                    						                    						
	});
	                    					
	});







function InsertarPresupuestos(){
	
	var _id_plan_cuentas = document.getElementById('id_plan_cuentas_1').value;
	var _nombre_presupuestos_cabeza = document.getElementById('nombre_presupuestos_cabeza').value;
	var _mes_presupuestos_detalle = document.getElementById('mes_presupuestos_detalle').value;
	var _anio_presupuestos_detalle = document.getElementById('anio_presupuestos_detalle').value;
	var _valor_presupuestado_presupuestos_detalle = document.getElementById('valor_presupuestado_presupuestos_detalle').value;
	var _valor_ejecutado_presupuestos_detalle = document.getElementById('valor_ejecutado_presupuestos_detalle').value;
	
	
	
	var parametros = {
			id_plan_cuentas:_id_plan_cuentas,
			nombre_presupuestos_cabeza:_nombre_presupuestos_cabeza,
			mes_presupuestos_detalle:_mes_presupuestos_detalle,
			anio_presupuestos_detalle:_anio_presupuestos_detalle,
			valor_presupuestado_presupuestos_detalle:_valor_presupuestado_presupuestos_detalle,
			valor_ejecutado_presupuestos_detalle:_valor_ejecutado_presupuestos_detalle
	}
	
$.ajax({
		
		beforeSend:function(){},
		url:"index.php?controller=Presupuestos&action=InsertarPresupuestos", 
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.respuesta != undefined && datos.respuesta == 1){
			
			swal({
		  		  title: "Presupuestos",
		  		  text: "Guardado exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		
		  		});
			document.getElementById("frm_presupuestos").reset();	
			load_buscar_presupuestos();
		}
		console.log(datos)
	
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
		
		
	});


event.preventDefault()

}


function load_buscar_presupuestos(pagina){

	var search=$("#search_buscar_presupuestos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_buscar_presupuestos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_buscar_presupuestos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Presupuestos&action=consulta_presupuestos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#presupuestos_registrados").html(x);
              $("#load_buscar_presupuestos").html("");
              $("#tabla_presupuestos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#presupuestos_registrados").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
           }
         });


	   }


$("#Guardar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	    	var id_plan_cuentas = $("#id_plan_cuentas").val();
	    	var nombre_presupuestos_cabeza = $("#nombre_presupuestos_cabeza").val();
	    	var mes_presupuestos_detalle = $("#mes_presupuestos_detalle").val();
	    	var anio_presupuestos_detalle = $("#anio_presupuestos_detalle").val();
	    	var valor_presupuestado_presupuestos_detalle = $("#valor_presupuestado_presupuestos_detalle").val();
	    	var valor_ejecutado_presupuestos_detalle = $("#valor_ejecutado_presupuestos_detalle").val();
	    	
	    	if (id_plan_cuentas == 0)
	    	{
		    	
	    		$("#mensaje_id_plan_cuentas").text("Introduzca Un Código");
	    		$("#mensaje_id_plan_cuentas").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_plan_cuentas").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (nombre_presupuestos_cabeza == "")
	    	{
		    	
	    		$("#mensaje_nombre_presupuestos_cabeza").text("Introduzca Un Nombre");
	    		$("#mensaje_nombre_presupuestos_cabeza").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_nombre_presupuestos_cabeza").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (mes_presupuestos_detalle == "")
	    	{
		    	
	    		$("#mensaje_mes_presupuestos_detalle").text("Introduzca Un Mes");
	    		$("#mensaje_mes_presupuestos_detalle").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_mes_presupuestos_detalle").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
	    	
	    	if (anio_presupuestos_detalle == "")
	    	{
		    	
	    		$("#mensaje_anio_presupuestos_detalle").text("Introduzca Un Año");
	    		$("#mensaje_anio_presupuestos_detalle").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_anio_presupuestos_detalle").fadeOut("slow"); //Muestra mensaje de error
	            
			}

	    	if (valor_presupuestado_presupuestos_detalle == "")
	    	{
		    	
	    		$("#mensaje_valor_presupuestado_presupuestos_detalle").text("Introduzca Un Valor");
	    		$("#mensaje_valor_presupuestado_presupuestos_detalle").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_valor_presupuestado_presupuestos_detalle").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (valor_ejecutado_presupuestos_detalle == "")
	    	{
		    	
	    		$("#mensaje_valor_ejecutado_presupuestos_detalle").text("Introduzca Un Valor");
	    		$("#mensaje_valor_ejecutado_presupuestos_detalle").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_valor_ejecutado_presupuestos_detalle").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
		}); 


	        $( "#id_plan_cuentas" ).focus(function() {
			  $("#mensaje_id_plan_cuentas").fadeOut("slow");
		    });

	        $( "#nombre_presupuestos_cabeza" ).focus(function() {
				  $("#mensaje_nombre_presupuestos_cabeza").fadeOut("slow");
			    });
	        $( "#mes_presupuestos_detalle" ).focus(function() {
				  $("#mensaje_mes_presupuestos_detalle").fadeOut("slow");
			    });
	        $( "#anio_presupuestos_detalle" ).focus(function() {
				  $("#mensaje_anio_presupuestos_detalle").fadeOut("slow");
			    });
	        $( "#valor_presupuestado_presupuestos_detalle" ).focus(function() {
				  $("#mensaje_valor_presupuestado_presupuestos_detalle").fadeOut("slow");
			    });
	        $( "#valor_ejecutado_presupuestos_detalle" ).focus(function() {
				  $("#mensaje_valor_ejecutado_presupuestos_detalle").fadeOut("slow");
			    });
	        
	        
	        function NumCheck(e, field) {
	        	  key = e.keyCode ? e.keyCode : e.which
	        	  // backspace
	        	  if (key == 8) return true
	        	  // 0-9
	        	  if (key > 47 && key < 58) {
	        	    if (field.value == "") return true
	        	    regexp = /.[0-9]{2}$/
	        	    return !(regexp.test(field.value))
	        	  }
	        	  // .
	        	  if (key == 46) {
	        	    if (field.value == "") return false
	        	    regexp = /^[0-9]+$/
	        	    return regexp.test(field.value)
	        	  }
	        	  // other key
	        	  return false
	        	 
	        	}



	

