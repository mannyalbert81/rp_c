$(document).ready(function(){
	
	ConsultaCandidatos();
	ConsultaCandidatosAprobado();
	ConsultaCandidatosNegado();

})

function ConsultaCandidatos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ConsultaCandidatos&action=ConsultaCandidatos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}



function ConsultaCandidatosAprobado(_page = 1){
	
	var buscador_aprobado = $("#buscador_aprobado").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageAprobado").addClass("loader")},
		url:"index.php?controller=ConsultaCandidatos&action=ConsultaCandidatosAprobado",
		type:"POST",
		data:{page:_page,search:buscador_aprobado,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_aprobado_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageAprobado").removeClass("loader")
		
	})
	
}


$("#id_registro_tres_cuotas").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

function ConsultaCandidatosNegado(_page = 1){
	
	var buscador_negado = $("#buscador_negado").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageNegado").addClass("loader")},
		url:"index.php?controller=ConsultaCandidatos&action=ConsultaCandidatosNegado",
		type:"POST",
		data:{page:_page,search:buscador_negado,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_candidatos_negado_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPageNegado").removeClass("loader")
		
	})
	
}


$("#id_registro_tres_cuotas").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

function AprobarRegistro(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ConsultaCandidatos&action=AprobarRegistro",
		type:"POST",
		dataType:"json",
		data:{id_padron_electoral_representantes:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "MENSAJE",
		  		  text: "Registro Aprobado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		ConsultaCandidatos();
		ConsultaCandidatosAprobado();
		ConsultaCandidatosNegado();
		
	})
	
	return false;
}

function NegarRegistro(id){
	
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ConsultaCandidatos&action=NegarRegistro",
		type:"POST",
		dataType:"json",
		data:{id_padron_electoral_representantes:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "MENSAJE",
		  		  text: "Registro Negado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		ConsultaCandidatos();
		ConsultaCandidatosAprobado();
		ConsultaCandidatosNegado();
		
	})
	
	return false;
}

