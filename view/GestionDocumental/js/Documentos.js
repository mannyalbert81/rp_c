$(document).ready(function(){
	
	load_documentos();
	
})
	   function load_documentos(pagina){


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
           	               url: 'index.php?controller=Documentos&action=searchadminsuper&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#solicitud_prestamos_registrados").html(x);
           	               	 $("#tabla_solicitud_prestamos_registrados").tablesorter(); 
           	                 $("#load_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#solicitud_prestamos_registrados").html("Ocurrio un error al cargar la informacion de solicitud de prestamos generadas..."+estado+"    "+error);
           	              }
           	            });


           		   }

function cargaTipoDocuemtos(){
	
	let $tipoDocumento = $("#id_tipo_documentos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Documentos&action=cargaTipoDocuemtos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tipoDocumento.empty();
		$tipoDocumento.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$tipoDocumento.append("<option value= " +value.id_tipo_documentos +" >" + value.nombre_tipo_documentos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoDocumento.empty();
	})
}
	
