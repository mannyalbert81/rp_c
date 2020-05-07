$(document).ready(function(){
	
	ConsultaDocumentosHipotecarios(1);

})

function ConsultaDocumentosHipotecarios(_page){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCierreCreditos&action=ConsultaDocumentosHipotecarios",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#consulta_cierre_creditos_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


	



	

