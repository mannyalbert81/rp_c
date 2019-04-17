 $(document).ready( function (){
        		   
        		   load_bodegas_inactivos(1);
        		   load_bodegas_activos(1);
        		   
	   			});


$("#Guardar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	    	var nombre_bodegas = $("#nombre_bodegas").val();
	    	var id_estado = $("#id_estado").val();
	    	var id_provincias = $("#id_provincias").val();
	    	var id_cantones = $("#id_cantones").val();
	    	var id_parroquias = $("#id_parroquias").val();

	    	if (nombre_bodegas == "")
	    	{
		    	
	    		$("#mensaje_nombre_bodegas").text("Introduzca Un Nombre");
	    		$("#mensaje_nombre_bodegas").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_nombre_bodegas").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (id_estado == 0)
	    	{
		    	
	    		$("#mensaje_id_estados").text("Introduzca Un Estado");
	    		$("#mensaje_id_estados").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_estados").fadeOut("slow"); //Muestra mensaje de error
	            
			}

	    	if (id_provincias == 0)
	    	{
		    	
	    		$("#mensaje_id_provincias").text("Introduzca Una Provincia");
	    		$("#mensaje_id_provincias").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_provincias").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (id_cantones == 0)
	    	{
		    	
	    		$("#mensaje_id_cantones").text("Introduzca Un Cantón");
	    		$("#mensaje_id_cantones").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_cantones").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (id_parroquias == 0)
	    	{
		    	
	    		$("#mensaje_id_parroquias").text("Introduzca Ua Parroquia");
	    		$("#mensaje_id_parroquias").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_parroquias").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

		}); 

$( "#nombre_bodegas" ).focus(function() {
	  $("#mensaje_nombre_bodegas").fadeOut("slow");
  });

  $( "#id_estado" ).focus(function() {
		  $("#mensaje_id_estados").fadeOut("slow");
	    });
  
  $( "#id_provincias" ).focus(function() {
		  $("#mensaje_id_provincias").fadeOut("slow");
	    });
  $( "#id_cantones" ).focus(function() {
		  $("#mensaje_id_cantones").fadeOut("slow");
	    });
  $( "#id_parroquias" ).focus(function() {
		  $("#mensaje_id_parroquias").fadeOut("slow");
	    });

function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789";
    especiales = [8,37,39,46];
    tecla_especial = false
    for(var i in especiales){
    if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
     }

function load_bodegas_activos(pagina){

	   var search=$("#search_activos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_bodegas_activos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_bodegas_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Bodegas&action=consulta_bodegas_activos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#bodegas_activos_registrados").html(x);
              $("#load_bodegas_activos").html("");
              $("#tabla_bodegas_activos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#bodegas_activos_registrados").html("Ocurrio un error al cargar la informacion de Bodegas Activos..."+estado+"    "+error);
           }
         });

	   }

function load_bodegas_inactivos(pagina){

	   var search=$("#search_inactivos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_bodegas_inactivos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_bodegas_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Bodegas&action=consulta_bodegas_inactivos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#bodegas_inactivos_registrados").html(x);
              $("#load_bodegas_inactivos").html("");
              $("#tabla_bodegas_inactivos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#bodegas_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Bodegas Inactivos..."+estado+"    "+error);
           }
         });

	   }



$("#id_provincias").change(function(){
    // obtenemos el combo de resultado combo 2
   var $id_cantones_vivienda = $("#id_cantones");
	
    // lo vaciamos
   var id_provincias_vivienda = $(this).val();
  
  
    if(id_provincias_vivienda != 0)
    {
        
    	$id_cantones_vivienda.empty();
    	
    	 var datos = {
           	   
    			 id_provincias_vivienda:$(this).val()
          };

    	 $.ajax({
                 beforeSend: function(objeto){
                   /*buscar una funcion de cargando*/
                 },
                 url: 'index.php?controller=Bodegas&action=devuelveCanton',
                 type: 'POST',
                 data: datos,
                 success: function(resultado){
                 	try {
                 		resultado = resultado.replace('<', "");
	                    	resultado = resultado.replace(/\\n/g, "\\n")  
	                       .replace(/\\'/g, "\\'")
	                       .replace(/\\"/g, '\\"')
	                       .replace(/\\&/g, "\\&")
	                       .replace(/\\r/g, "\\r")
	                       .replace(/\\t/g, "\\t")
	                       .replace(/\\b/g, "\\b")
	                       .replace(/\\f/g, "\\f");
        	 	        // remove non-printable and other non-valid JSON chars
        	 	        resultado = resultado.replace(/[\u0000-\u0019]+/g,"");
        	 	        
                        objeto = JSON.parse(resultado);

                       
                        if(objeto.length==0)
 	          		   {
                        	$id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");	
 	             	   }else{
 	             		 $id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");
 	          		 		$.each(objeto, function(index, value) {
 	          		 		$id_cantones_vivienda.append("<option value= " +value.id_cantones +" >" + value.nombre_cantones  + "</option>");	
 	                     		 });
 	             	   }	

                        
                    }
                    catch (error) {
                        if(error instanceof SyntaxError) {
                            let mensaje = error.message;
                            console.log('ERROR EN LA SINTAXIS:', mensaje);
                        } else {
                            throw error; // si es otro error, que lo siga lanzando
                        }
                    }
                },
               error: function(jqXHR,estado,error){
                /*alertar error*/
               }
             });

 	  
 		  
    }else{
    	var id_cantones_vivienda=$("#id_cantones");
    	id_cantones_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
    	var id_parroquias_vivienda=$("#id_parroquias");
    	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
    }
    
});


$("#id_cantones").change(function(){
    // obtenemos el combo de resultado combo 2
   var $id_parroquias_vivienda = $("#id_parroquias");
	
    // lo vaciamos
   var id_cantones_vivienda = $(this).val();
  
  
    if(id_cantones_vivienda != 0)
    {
        
    	$id_parroquias_vivienda.empty();
    	
    	 var datos = {
           	   
    			 id_cantones_vivienda:$(this).val()
          };

    	 $.ajax({
                 beforeSend: function(objeto){
                   /*buscar una funcion de cargando*/
                 },
                 url: 'index.php?controller=Bodegas&action=devuelveParroquias',
                 type: 'POST',
                 data: datos,
                 success: function(resultado){
                 	try {
                 		resultado = resultado.replace('<', "");
	                    	resultado = resultado.replace(/\\n/g, "\\n")  
	                       .replace(/\\'/g, "\\'")
	                       .replace(/\\"/g, '\\"')
	                       .replace(/\\&/g, "\\&")
	                       .replace(/\\r/g, "\\r")
	                       .replace(/\\t/g, "\\t")
	                       .replace(/\\b/g, "\\b")
	                       .replace(/\\f/g, "\\f");
        	 	        // remove non-printable and other non-valid JSON chars
        	 	        resultado = resultado.replace(/[\u0000-\u0019]+/g,"");
        	 	        
                        objeto = JSON.parse(resultado);

                       
                        if(objeto.length==0)
 	          		   {
                        	$id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");	
 	             	   }else{
 	             		 $id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");
 	          		 		$.each(objeto, function(index, value) {
 	          		 		$id_parroquias_vivienda.append("<option value= " +value.id_parroquias +" >" + value.nombre_parroquias  + "</option>");	
 	                     		 });
 	             	   }	

                        
                    }
                    catch (error) {
                        if(error instanceof SyntaxError) {
                            let mensaje = error.message;
                            console.log('ERROR EN LA SINTAXIS:', mensaje);
                        } else {
                            throw error; // si es otro error, que lo siga lanzando
                        }
                    }
                },
               error: function(jqXHR,estado,error){
                /*alertar error*/
               }
             });

 	  
 		  
    }else{
    	var id_parroquias_vivienda=$("#id_parroquias");
    	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
    }
    
});





