$(document).ready( function (){
        		   
        		   load_actividades(1);
        		   
});

$("#buscar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
	    	var desde = $("#desde").val();
	    	var hasta = $("#hasta").val();
	    	
	    	if(desde > hasta){

				$("#mensaje_desde").text("Fecha desde no puede ser mayor a hasta");
	    		$("#mensaje_desde").fadeIn("slow"); //Muestra mensaje de error
	            return false;
	            
			}else 
	    	{
	    		$("#mensaje_desde").fadeOut("slow"); //Muestra mensaje de error
	    		load_actividades(1);
			} 

			if(hasta < desde){

				$("#mensaje_hasta").text("Fecha hasta no puede ser menor a desde");
	    		$("#mensaje_hasta").fadeIn("slow"); //Muestra mensaje de error
	            return false;
	            
			}else 
	    	{
	    		$("#mensaje_hasta").fadeOut("slow"); //Muestra mensaje de error
	    		load_actividades(1);
			} 

		});

$( "#desde" ).focus(function() {
	  $("#mensaje_desde").fadeOut("slow");
  });
	
$( "#hasta" ).focus(function() {
$("#mensaje_hasta").fadeOut("slow");
});

function load_actividades(pagina){

	   var search=$("#search").val();
	   var desde=$("#desde").val();
	   var hasta=$("#hasta").val();
	   var con_datos={
				  action:'ajax',
				  page:pagina,
				  desde:desde,
				  hasta:hasta
				  };
  $("#load_registrados").fadeIn('slow');
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
               },
               url: 'index.php?controller=Actividades&action=search_actividades&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#actividades_registrados").html(x);
               	 $("#tabla_actividades").tablesorter(); 
                 $("#load_registrados").html("");
               },
              error: function(jqXHR,estado,error){
                $("#actividades_registrados").html("Ocurrio un error al cargar la información de activiades..."+estado+"    "+error);
              }
            });
		}