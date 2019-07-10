$(document).ready( function (){
        		   
	load_participes_activos(1);    		   
	load_participes_inactivos(1);
    load_participes_desafiliado(1);
    load_participes_liquidado_cesante(1);
        		   
	   			});

  $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_participes = $("#nombre_participes").val();
		    	if (nombre_participes == "")
		    	{			    	
		    		$("#mensaje_nombre_participes").text("Introduzca Un Participes");
		    		$("#mensaje_nombre_participes").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_participes").fadeOut("slow"); //Muestra mensaje de error		            
				}   

			});
  
  $( "#nombre_participes" ).focus(function() {
	  $("#mensaje_nombre_participes").fadeOut("slow");
    });
  
  function load_participes_activos(pagina){

	   var search=$("#search_activos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_participes_activos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_participes_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Participes&action=consulta_participes_activos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#participes_activos_registrados").html(x);
                $("#load_participes_activos").html("");
                $("#tabla_participes_activos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#participes_activos_registrados").html("Ocurrio un error al cargar la informacion de Participes Activos..."+estado+"    "+error);
             }
           });


	   }
  
  function load_participes_inactivos(pagina){

	   var search=$("#search_inactivos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_participes_inactivos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_participes_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Participes&action=consulta_participes_inactivos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#participes_inactivos_registrados").html(x);
                $("#load_participes_inactivos").html("");
                $("#tabla_participes_inactivos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#participes_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Participes Inactivos..."+estado+"    "+error);
             }
           });
	   }
  
  function load_participes_desafiliado(pagina){

	   var search=$("#search_desafiliado").val();
     var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
   $("#load_participes_desafiliado").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_participes_desafiliado").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Participes&action=consulta_participes_desafiliado&search='+search,
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#participes_desafiliado_registrados").html(x);
               $("#load_participes_desafiliado").html("");
               $("#tabla_participes_desafiliado").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#participes_desafiliado_registrados").html("Ocurrio un error al cargar la informacion de Participes Desafiliado..."+estado+"    "+error);
            }
          });
	   }
  function load_participes_liquidado_cesante(pagina){

	   var search=$("#search_liquidado_cesante").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_participes_liquidado_cesante").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_participes_liquidado_cesante").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Participes&action=consulta_participes_liquidado_cesante&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#participes_liquidado_cesante_registrados").html(x);
              $("#load_participes_liquidado_cesante").html("");
              $("#tabla_participes_liquidado_cesante").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#participes_liquidado_cesante_registrados").html("Ocurrio un error al cargar la informacion de Participes Liquidado Cesante..."+estado+"    "+error);
           }
         });
	   }
  
  
  
  