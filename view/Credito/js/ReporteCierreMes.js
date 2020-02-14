$(document).ready(function(){
	
	ConsultaReporteCierreMes();

})

function ConsultaReporteCierreMes(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCierreMes&action=ConsultaReporteCierreMes",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_cierre_mes_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#id_creditos_cierre_mes").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})