$(document).ready(function(){
	
	ConsultaReporteCierreCreditos();

})

function ConsultaReporteCierreCreditos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCierreCreditos&action=ConsultaReporteCierreCreditos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_cierre_creditos_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#id_creditos_cierre_creditos").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})




