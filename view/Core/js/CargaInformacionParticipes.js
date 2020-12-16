  $(document).ready( function (){
        		   
        		   load_informacionparticipes(1);
				   
	   			});

        	


	   function load_informacionparticipes(pagina){

		   var search=$("#search_informacionparticipes").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_informacionparticipes").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_informacionparticipes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreInformacionParticipes&action=consulta_informacion_participes&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#participes_registrados_detalle").html(x);
	                 $("#load_informacionparticipes").html("");
	                 $("#tabla_informacionparticipes").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#participes_registrados_detalle").html("Ocurrio un error al cargar la informacion de Detalle Participes..."+estado+"    "+error);
	              }
	            });


		   }



