  $(document).ready( function (){
        		   
        		   load_activos_fijos(1);
        		   $(".cantidades1").inputmask();
        		   });
  
  $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var id_oficina = $("#id_oficina").val();
		    	var id_tipo_activos_fijos = $("#id_tipo_activos_fijos").val();
		    	var id_estado = $("#id_estado").val();
		    	var id_usuarios = $("#id_usuarios").val();
		    	var nombre_activos_fijos = $("#nombre_activos_fijos").val();
		    	var codigo_activos_fijos = $("#codigo_activos_fijos").val();
		    	var cantidad_activos_fijos = $("#cantidad_activos_fijos").val();
		    	var valor_activos_fijos = $("#valor_activos_fijos").val();
		    	var meses_depreciacion_activos_fijos = $("#meses_depreciacion_activos_fijos").val();
		    	var depreciacion_mensual_activos_fijos = $("#depreciacion_mensual_activos_fijos").val();
		    	
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
		    	if (codigo_activos_fijos == "")
		    	{
			    	
		    		$("#mensaje_codigo_activos_fijos").text("Introduzca Un Código");
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
				 
		    	if (cantidad_activos_fijos == "")
		    	{
			    	
		    		$("#mensaje_cantidad_activos_fijos").text("Introduzca Una Cantidad");
		    		$("#mensaje_cantidad_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_cantidad_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
		            
				} 
		    	 
		    	 
		    	if (valor_activos_fijos == 0.00)
		    	{
			    	
		    		$("#mensaje_valor_activos_fijos").text("Introduzca Un Valor");
		    		$("#mensaje_valor_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
		            
				} 
		    	if (meses_depreciacion_activos_fijos == "")
		    	{
			    	
		    		$("#mensaje_meses_depreciacion_activos_fijos").text("Introduzca la cantidad de meses");
		    		$("#mensaje_meses_depreciacion_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_meses_depreciacion_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
		            
				}  


		    	if (depreciacion_mensual_activos_fijos == 0.00)
		    	{
			    	
		    		$("#mensaje_depreciacion_mensual_activos_fijos").text("Introduzca Un Valor");
		    		$("#mensaje_depreciacion_mensual_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_depreciacion_mensual_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
		            
				} 

		    	if (id_estado == 0)
		    	{
			    	
		    		$("#mensaje_id_estado").text("Introduzca un estado");
		    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
		            
				}  
		
			});
  
  $( "#id_oficina" ).focus(function() {
	  $("#mensaje_id_oficina").fadeOut("slow");
    });

    $( "#id_tipo_activos_fijos" ).focus(function() {
		  $("#mensaje_id_tipo_activos_fijos").fadeOut("slow");
	});

    $( "#codigo_activos_fijos" ).focus(function() {
		  $("#mensaje_codigo_activos_fijos").fadeOut("slow");
	});
	
    $( "#nombre_activos_fijos" ).focus(function() {
		  $("#mensaje_nombre_activos_fijos").fadeOut("slow");
	});

    $( "#cantidad_activos_fijos" ).focus(function() {
		  $("#mensaje_cantidad_activos_fijos").fadeOut("slow");
	});

    $( "#valor_activos_fijos" ).focus(function() {
		  $("#mensaje_valor_activos_fijos").fadeOut("slow");
	});

    $( "#meses_depreciacion_activos_fijos" ).focus(function() {
		  $("#mensaje_meses_depreciacion_activos_fijos").fadeOut("slow");
	});

    $( "#depreciacion_mensual_activos_fijos" ).focus(function() {
		  $("#mensaje_depreciacion_mensual_activos_fijos").fadeOut("slow");
	});

    $( "#id_estado" ).focus(function() {
		  $("#mensaje_id_estado").fadeOut("slow");
	});

function numeros(e){
    		  var key = window.event ? e.which : e.keyCode;
    		  if (key < 48 || key > 57) {
    		    e.preventDefault();
    		  }
     }
function load_activos_fijos(pagina){

	   var search=$("#search_activos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_activos_fijos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_activos_fijos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=ActivosFijos&action=consulta_activos_fijos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#activos_fijos_registrados").html(x);
              $("#load_activos_fijos").html("");
              $("#tabla_activos_fijos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#activos_fijos_registrados").html("Ocurrio un error al cargar la informacion de Bodegas Activos..."+estado+"    "+error);
           }
         });

	   }