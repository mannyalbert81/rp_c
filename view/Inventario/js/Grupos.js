$(document).ready( function (){
        		   
        		   load_grupos_inactivos(1);
        		   load_grupos_activos(1);
        		   
	   			});

  $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_grupos = $("#nombre_grupos").val();
		    	if (nombre_grupos == "")
		    	{			    	
		    		$("#mensaje_nombre_grupos").text("Introduzca Un Grupo");
		    		$("#mensaje_nombre_grupos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_grupos").fadeOut("slow"); //Muestra mensaje de error		            
				}   

			});
  
  $( "#nombre_grupos" ).focus(function() {
	  $("#mensaje_nombre_grupos").fadeOut("slow");
    });
  
  function load_grupos_activos(pagina){

	   var search=$("#search_activos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_grupos_activos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_grupos_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Grupos&action=consulta_grupos_activos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#grupos_activos_registrados").html(x);
                $("#load_grupos_activos").html("");
                $("#tabla_grupos_activos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#grupos_activos_registrados").html("Ocurrio un error al cargar la informacion de Grupos Activos..."+estado+"    "+error);
             }
           });


	   }
  
  function load_grupos_inactivos(pagina){

	   var search=$("#search_inactivos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_grupos_inactivos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_grupos_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Grupos&action=consulta_grupos_inactivos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#grupos_inactivos_registrados").html(x);
                $("#load_grupos_inactivos").html("");
                $("#tabla_grupos_inactivos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#grupos_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Grupos Inactivos..."+estado+"    "+error);
             }
           });
	   }
  
  