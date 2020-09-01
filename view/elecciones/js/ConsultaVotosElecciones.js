$(document).ready(function(){
	
	ConsultaCandidatosFuerzaAerea();
	ConsultaCandidatosComandoConjunto();
	ConsultaCandidatosMinisterioDefensa();
	ConsultaCandidatosFuerzaNaval();
	ConsultaCandidatosFuerzaTerrestre();

})

function ConsultaCandidatosFuerzaAerea(_page = 1){
	
	var buscador_fuerza_aerea = $("#buscador_fuerza_aerea").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageFuerzaAerea").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaCandidatosFuerzaAerea",
		type:"POST",
		data:{page:_page,search:buscador_fuerza_aerea,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_fuerza_aerea_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageFuerzaAerea").removeClass("loader")
		
	})
	
}

function ConsultaCandidatosComandoConjunto(_page = 1){
	
	var buscador_comando_conjunto = $("#buscador_comando_conjunto").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageComandoConjunto").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaCandidatosComandoConjunto",
		type:"POST",
		data:{page:_page,search:buscador_comando_conjunto,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_comando_conjunto_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageComandoConjunto").removeClass("loader")
		
	})
	
}

function ConsultaCandidatosMinisterioDefensa(_page = 1){
	
	var buscador_ministerio_defensa = $("#buscador_ministerio_defensa").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageMinisterioDefensa").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaCandidatosMinisterioDefensa",
		type:"POST",
		data:{page:_page,search:buscador_ministerio_defensa,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_ministerio_defensa_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageMinisterioDefensa").removeClass("loader")
		
	})
	
}

function ConsultaCandidatosFuerzaNaval(_page = 1){
	
	var buscador_fuerza_naval = $("#buscador_fuerza_naval").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageFuerzaNaval").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaCandidatosFuerzaNaval",
		type:"POST",
		data:{page:_page,search:buscador_fuerza_naval,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_fuerza_naval_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageFuerzaNaval").removeClass("loader")
		
	})
	
}

function ConsultaCandidatosFuerzaTerrestre(_page = 1){
	
	var buscador_fuerza_terrestre = $("#buscador_fuerza_terrestre").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageFuerzaTerrestre").addClass("loader")},
		url:"index.php?controller=ConsultaVotosElecciones&action=ConsultaCandidatosFuerzaTerrestre",
		type:"POST",
		data:{page:_page,search:buscador_fuerza_terrestre,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_fuerza_terrestre_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageFuerzaTerrestre").removeClass("loader")
		
	})
	
}