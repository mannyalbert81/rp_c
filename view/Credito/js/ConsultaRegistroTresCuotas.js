$(document).ready(function(){
	
	ConsultaRegistroTresCuotas();
	ConsultaRegistroTresCuotasAprobado();
	ConsultaRegistroTresCuotasNegado();

})

function ConsultaRegistroTresCuotas(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ConsultaRegistroTresCuotas&action=ConsultaRegistroTresCuotas",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_registro_tres_cuotas_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#id_registro_tres_cuotas").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

function ConsultaRegistroTresCuotasAprobado(_page = 1){
	
	var buscador_aprobado = $("#buscador_aprobado").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageAprobado").addClass("loader")},
		url:"index.php?controller=ConsultaRegistroTresCuotas&action=ConsultaRegistroTresCuotasAprobado",
		type:"POST",
		data:{page:_page,search:buscador_aprobado,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_registro_tres_cuotas_aprobado_tbl").html(datos);		
		
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

function ConsultaRegistroTresCuotasNegado(_page = 1){
	
	var buscador_negado = $("#buscador_negado").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPageNegado").addClass("loader")},
		url:"index.php?controller=ConsultaRegistroTresCuotas&action=ConsultaRegistroTresCuotasNegado",
		type:"POST",
		data:{page:_page,search:buscador_negado,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_registro_tres_cuotas_negado_tbl").html(datos);		
		
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
		url:"index.php?controller=ConsultaRegistroTresCuotas&action=AprobarRegistro",
		type:"POST",
		dataType:"json",
		data:{id_registro_tres_cuotas:id}
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
		ConsultaRegistroTresCuotas();
		ConsultaRegistroTresCuotasAprobado();
		ConsultaRegistroTresCuotasNegado();
		
	})
	
	return false;
}

function NegarRegistro(id){
	
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ConsultaRegistroTresCuotas&action=NegarRegistro",
		type:"POST",
		dataType:"json",
		data:{id_registro_tres_cuotas:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "MENSAJE",
		  		  text: "Registro Negado",
		  		  icon: "error",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		ConsultaRegistroTresCuotas();
		ConsultaRegistroTresCuotasAprobado();
		ConsultaRegistroTresCuotasNegado();
		
	})
	
	return false;
}

