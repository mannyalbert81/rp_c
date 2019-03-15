 $(document).ready( function (){
        		   pone_espera();
        		   
	   			});

        	   function pone_espera(){

        		   $.blockUI({ 
        				message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
        				css: { 
        		            border: 'none', 
        		            padding: '15px', 
        		            backgroundColor: '#000', 
        		            '-webkit-border-radius': '10px', 
        		            '-moz-border-radius': '10px', 
        		            opacity: .5, 
        		            color: '#fff',
        		           
        	        		}
        	    });
            	
		        setTimeout($.unblockUI, 3000); 
		        
        	   }
        	   
   $("#Guardar").click(function() 
{
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	var cedula_usuarios = $("#cedula_usuarios").val();
	var nombre_usuarios = $("#nombre_usuarios").val();
	//var usuario_usuario = $("#usuario_usuario").val();
	var clave_usuarios = $("#clave_usuarios").val();
	var cclave_usuarios = $("#clave_usuarios_r").val();
	var celular_usuarios = $("#celular_usuarios").val();
	var correo_usuarios  = $("#correo_usuarios").val();
	var id_rol  = $("#id_rol").val();
	var id_estado  = $("#id_estado").val();
	
	
	if (cedula_usuarios == "")
	{
    	
		$("#mensaje_cedula_usuarios").text("Introduzca Identificación");
		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
        
	}    
	
	if (nombre_usuarios == "")
	{
    	
		$("#mensaje_nombre_usuarios").text("Introduzca un Nombre");
		$("#mensaje_nombre_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_nombre_usuarios").fadeOut("slow"); //Muestra mensaje de error
        
	}
	/*
	if (usuario_usuario == "")
	{
    	
		$("#mensaje_usuario_usuario").text("Introduzca un Usuario");
		$("#mensaje_usuario_usuario").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_usuario_usuario").fadeOut("slow"); //Muestra mensaje de error
        
	}   
			    	
*/
	if (clave_usuarios == "")
	{
		
		$("#mensaje_clave_usuarios").text("Introduzca una Clave");
		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }else if (clave_usuarios.length<4){
    	$("#mensaje_clave_usuarios").text("Introduzca minimo 4 números");
		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
	}else if (clave_usuarios.length>4){
    	$("#mensaje_clave_usuarios").text("Introduzca máximo 4 números");
		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
	}
	else 
	{
		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
        
	}
	

	if (cclave_usuarios == "")
	{
		
		$("#mensaje_clave_usuarios_r").text("Introduzca una Clave");
		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
        
	}
	
	if (clave_usuarios != cclave_usuarios)
	{
    	
		$("#mensaje_clave_usuarios_r").text("Claves no Coinciden");
		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else
	{
		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
        
	}	
	

	//los telefonos
	
	if (celular_usuarios == "" )
	{
    	
		$("#mensaje_celular_usuarios").text("Ingrese un Celular");
		$("#mensaje_celular_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_celular_usuarios").fadeOut("slow"); //Muestra mensaje de error
        
	}

	// correos
	
	if (correo_usuarios == "")
	{
    	
		$("#mensaje_correo_usuarios").text("Introduzca un correo");
		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else if (regex.test($('#correo_usuarios').val().trim()))
	{
		$("#mensaje_correo_usuarios").fadeOut("slow"); //Muestra mensaje de error
        
	}
	else 
	{
		$("#mensaje_correo_usuarios").text("Introduzca un correo Valido");
		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
        return false;	
    }

	
	if (id_rol == 0 )
	{
    	
		$("#mensaje_id_rol").text("Seleccione");
		$("#mensaje_id_rol").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_id_rol").fadeOut("slow"); //Muestra mensaje de error
        
	}



	if (id_estado == 0 )
	{
    	
		$("#mensaje_id_estado").text("Seleccione");
		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
        return false;
    }
	else 
	{
		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
        
	}
					    

});
   
   $( "#cedula_usuarios" ).focus(function() {
		  $("#mensaje_cedula_usuarios").fadeOut("slow");
	    });
		
		$( "#nombre_usuarios" ).focus(function() {
			$("#mensaje_nombre_usuarios").fadeOut("slow");
		});
		/*$( "#usuario_usuario" ).focus(function() {
			$("#mensaje_usuario_usuario").fadeOut("slow");
		});
		*/
		$( "#clave_usuarios" ).focus(function() {
			$("#mensaje_clave_usuarios").fadeOut("slow");
		});
		$( "#clave_usuarios_r" ).focus(function() {
			$("#mensaje_clave_usuarios_r").fadeOut("slow");
		});
		
		$( "#celular_usuarios" ).focus(function() {
			$("#mensaje_celular_usuarios").fadeOut("slow");
		});
		
		$( "#correo_usuarios" ).focus(function() {
			$("#mensaje_correo_usuarios").fadeOut("slow");
		});
	
		$( "#id_rol" ).focus(function() {
			$("#mensaje_id_rol").fadeOut("slow");
		});

		$( "#id_estado" ).focus(function() {
			$("#mensaje_id_estado").fadeOut("slow");
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
