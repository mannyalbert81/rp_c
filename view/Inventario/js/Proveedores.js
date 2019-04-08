$(document).ready(function(){
      $(".cantidades1").inputmask();
      load_bodegas_inactivos(1);
	   load_bodegas_activos(1);
      });//docreasyend

$("#Guardar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	    	var nombre_proveedores = $("#nombre_proveedores").val();
	    	var identificacion_proveedores = $("#identificacion_proveedores").val();
	    	var contactos_proveedores = $("#contactos_proveedores").val();
	    	var direccion_proveedores = $("#direccion_proveedores").val();
	    	var telefono_proveedores = $("#telefono_proveedores").val();
	    	var email_proveedores = $("#email_proveedores").val();
	    	var fecha_nacimiento_proveedores = $("#fecha_nacimiento_proveedores").val();
	    	var fecha_actual = new Date();
	    	if (nombre_proveedores == 0)
	    	{
		    	
	    		$("#mensaje_nombre_proveedores").text("Introduzca Un Nombre");
	    		$("#mensaje_nombre_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_nombre_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (identificacion_proveedores == "")
	    	{
		    	
	    		$("#mensaje_identificacion_proveedores").text("Introduzca Un Ruc");
	    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (contactos_proveedores == "")
	    	{
		    	
	    		$("#mensaje_contactos_proveedores").text("Introduzca Un Contacto");
	    		$("#mensaje_contactos_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_contactos_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (direccion_proveedores == "")
	    	{
		    	
	    		$("#mensaje_direccion_proveedores").text("Introduzca Una Dirección");
	    		$("#mensaje_direccion_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_direccion_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (telefono_proveedores == "")
	    	{
		    	
	    		$("#mensaje_telefono_proveedores").text("Introduzca Un teléfono");
	    		$("#mensaje_telefono_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_telefono_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
	    	if (email_proveedores == "")
	    	{
		    	
	    		$("#mensaje_email_proveedores").text("Introduzca un correo");
	    		$("#mensaje_email_proveedores").fadeIn("slow"); //Muestra mensaje de error
	    		
	            return false;
		    }
	    	else if (regex.test($('#email_proveedores').val().trim()))
	    	{
	    		$("#mensaje_email_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}
	    	else 
	    	{
	    		$("#mensaje_email_proveedores").text("Introduzca un correo Valido");
	    		$("#mensaje_email_proveedores").fadeIn("slow"); //Muestra mensaje de error
	    		
		            return false;	
		    }
			

	    	if (fecha_nacimiento_proveedores =="")
	    	{
		    	
	    		$("#mensaje_fecha_nacimiento_proveedores").text("Introduzca una fecha ");
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			} 
	    	
	    	//var fecha=hoyFecha()
	    	if (fecha_nacimiento_proveedores >= hoyFecha())
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").text("La fecha no debe ser mayor o igual a la actual");
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
	        }
	    	else 
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
	    	}
	    	
		}); 

$( "#nombre_proveedores" ).focus(function() {
	  $("#mensaje_nombre_proveedores").fadeOut("slow");
  });

  $( "#identificacion_proveedores" ).focus(function() {
		  $("#mensaje_identificacion_proveedores").fadeOut("slow");
	    });
  $( "#contactos_proveedores" ).focus(function() {
		  $("#mensaje_contactos_proveedores").fadeOut("slow");
	    });
  $( "#direccion_proveedores" ).focus(function() {
		  $("#mensaje_direccion_proveedores").fadeOut("slow");
	    });
  $( "#telefono_proveedores" ).focus(function() {
		  $("#mensaje_telefono_proveedores").fadeOut("slow");
	    });
  $( "#email_proveedores" ).focus(function() {
		  $("#mensaje_email_proveedores").fadeOut("slow");
	    });
  $( "#fecha_nacimiento_proveedores" ).focus(function() {
		  $("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow");
	    });
  
  function hoyFecha(){
	    var hoy = new Date();
	        var dd = hoy.getDate();
	        var mm = hoy.getMonth()+1;
	        var yyyy = hoy.getFullYear();
	        
	        if (dd < 10) {
	        	  dd = '0' + dd;
	        	} 
	        	if (mm < 10) {
	        	  mm = '0' + mm;
	        	}
	 
	        return yyyy+'-'+mm+'-'+dd;
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