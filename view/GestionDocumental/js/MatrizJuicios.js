$(document).ready(function(){
	
	load_matriz();
	load_matriz_doc();
	
})
	   function load_matriz(pagina){


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
           	               url: 'index.php?controller=MatrizJuicios&action=searchadminsuper&search='+search,
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

function load_matriz_doc(pagina){


	   var search=$("#search_solicitud_doc").val();
    
	   var con_datos={
				  action:'ajax',
				  page:pagina
				  };
  $("#load_registrados_doc").fadeIn('slow');
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_registrados_doc").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
               },
               url: 'index.php?controller=MatrizJuicios&action=searchadminsuperDoc&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#solicitud_prestamos_registrados_doc").html(x);
               	 //$("#tabla_solicitud_prestamos_registrados_doc").tablesorter(); 
                 $("#load_registrados_doc").html("");
               },
              error: function(jqXHR,estado,error){
                $("#solicitud_prestamos_registrados_doc").html("Ocurrio un error al cargar la informacion de solicitud de prestamos generadas..."+estado+"    "+error);
              }
            });


	   }

	
