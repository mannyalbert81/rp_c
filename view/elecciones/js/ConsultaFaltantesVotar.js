$(document).ready(function(){
	
	ConsultaVotosRealizados();
})




function ConsultaVotosRealizados(_page = 1){
	
	var buscador_votos_realizados = $("#buscador_votos_realizados").val();
	$.ajax({
		//beforeSend:function(){$("#divLoaderPageVotosRealizados").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaVotosRealizados",
		type:"POST",
		data:{page:_page,search:buscador_votos_realizados,peticion:'ajax'}
	}).done(function(datos){		
		//console.log(datos);
		$("#consulta_votos_realizados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		//console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageVotosRealizados").removeClass("loader")
		
	})
	
}

var generar_votos_realizados = function(obj){
	
	var elemento = $(obj);
	var url 	 = "index.php?controller=ConsultaVotosElecciones&action=ReporteConsultaVotosRealizados";
	elemento.attr('href',url);
	return true;
}

setInterval(ConsultaVotosRealizados, 10000);