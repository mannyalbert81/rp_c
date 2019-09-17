	   $(document).ready( function (){
        		
        		   load_solicitud_prestamos_registrados(1);
        		   load_solicitud_garantias_registrados(1);
	   			});

        	   

        	   
        	   function load_solicitud_prestamos_registrados(pagina){


        		   var search=$("#search_solicitud").val();
                   
        		   var con_datos={
        					  action:'ajax',
        					  page:pagina
        					  };
                 $("#load_registrados").fadeIn('slow');
           	     $.ajax({
           	               beforeSend: function(objeto){
           	                 $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
           	               },
           	               url: 'index.php?controller=SolicitudPrestamo&action=searchadminsuper_deudor&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#solicitud_prestamos_registrados").html(x);
           	               	 //$("#tabla_solicitud_prestamos_registrados").tablesorter(); 
           	                 $("#load_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#solicitud_prestamos_registrados").html("Ocurrio un error al cargar la informacion de solicitud de prestamos generadas..."+estado+"    "+error);
           	              }
           	            });


           		   }



        	   function load_solicitud_garantias_registrados(pagina){
        		   var search=$("#search_garantias").val();
        		   var con_datos={
        					  action:'ajax',
        					  page:pagina
        					  };
                 $("#load_garantias_registrados").fadeIn('slow');
           	     $.ajax({
           	               beforeSend: function(objeto){
           	                 $("#load_garantias_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
           	               },
           	               url: 'index.php?controller=SolicitudPrestamo&action=searchadminsuper_garantes&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#solicitud_garantias_registrados").html(x);
           	               	 //$("#tabla_solicitud_prestamos_registrados").tablesorter(); 
           	                 $("#load_garantias_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#solicitud_garantias_registrados").html("Ocurrio un error al cargar la informacion de solicitud de garantías generadas..."+estado+"    "+error);
           	              }
           	            });


           		   }
        	   
        	   function EnviarInfo(cedula,id_solicitud)
        	   {
        		  console.log(cedula+"-"+id_solicitud); 
        		  window.open('index.php?controller=BuscarParticipes&action=index1&cedula_participe='+cedula+'&id_solicitud='+id_solicitud, '_self');
        	   }
