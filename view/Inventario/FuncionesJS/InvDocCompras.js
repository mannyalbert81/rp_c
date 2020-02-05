        	   $(document).ready( function (){
        		   
        		 
        		   load_buscar_compras(1);
        		   
	   			});

        	


	   function load_buscar_compras(pagina){

		   var search=$("#search_buscar_compras").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_buscar_compras").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_buscar_compras").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=InvDocCompras&action=consulta_compras&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#compras_registrados").html(x);
	                 $("#load_buscar_compras").html("");
	                 $("#tabla_compras").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#compras_registrados").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
	              }
	            });


		   }


