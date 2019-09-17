$(document).ready(function(){
	
	consultaTipoCredito();
	cargaEstadoTipoCredito();
	cargaTipoCredito();
	cargaTipoCreditoRenovacion();
	
})

$("#frm_tipo_credito_renovacion").on("submit",function(event){
	
	var _id_tipo_creditos = document.getElementById('id_tipo_creditos').value;
	let _id_tipo_creditos_a_renovar = document.getElementById('id_tipo_creditos_a_renovar').value;
	var _id_estado = document.getElementById('id_estado').value;
	var _id_tipo_creditos_renovacion = document.getElementById('id_tipo_creditos_renovacion').value;
	var parametros = {id_tipo_creditos:_id_tipo_creditos,id_tipo_creditos_a_renovar:_id_tipo_creditos_a_renovar,id_estado:_id_estado,id_tipo_creditos_renovacion:_id_tipo_creditos_renovacion}
	

	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoCredito&action=InsertaTipoCredito",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "TipoCredito",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_tipo_creditos_renovacion").val(0);
		document.getElementById("frm_tipo_credito_renovacion").reset();	
		consultaTipoCredito();
	})

	event.preventDefault()
})

function editTipoCredito(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoCredito&action=editTipoCredito",
		type:"POST",
		dataType:"json",
		data:{id_tipo_creditos_renovacion:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
		
			
			var array = datos.data[0];		
			$("#id_tipo_creditos").val(array.id_tipo_creditos);			
			$("#id_tipo_creditos_a_renovar").val(array.id_tipo_creditos_a_renovar);
			$("#id_estado").val(array.id_estado);
			$("#id_tipo_creditos_renovacion").val(array.id_tipo_creditos_renovacion);
			
			$("html, body").animate({ scrollTop: $(id_tipo_creditos_a_renovar).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		
		$("#divLoaderPage").removeClass("loader")
		consultaTipoCredito();
	})
	
	return false;
	
}


function delTipoCredito(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoCredito&action=delTipoCredito",
		type:"POST",
		dataType:"json",
		data:{id_tipo_creditos_renovacion:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "TipoCredito",
		  		  text: "Registro Eliminado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaTipoCredito();
	})
	
	return false;
}

function consultaTipoCredito(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoCredito&action=consultaTipoCredito",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#tipo_credito_renovacion_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


function cargaEstadoTipoCredito(){
	
	let $ddlEstado = $("#id_estado");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoCredito&action=cargaEstadoTipoCredito",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlEstado.empty();
		$ddlEstado.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlEstado.append("<option value= " +value.id_estado +" >" + value.nombre_estado  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlEstado.empty();
	})
	
}

$("#id_estado").on("focus",function(){
	$("#mensaje_id_estado").text("").fadeOut("");
})

$("#id_tipo_creditos_a_renovar").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})



function cargaTipoCredito(){
	
	let $ddlTipoCredito = $("#id_tipo_creditos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoCredito&action=cargaTipoCredito",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlTipoCredito.empty();
		$ddlTipoCredito.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlTipoCredito.append("<option value= " +value.id_tipo_creditos +" >" + value.nombre_tipo_creditos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlTipoCredito.empty();
	})
	
}

$("#id_tipo_creditos_renovacion").on("focus",function(){
	$("#mensaje_id_tipo_creditos").text("").fadeOut("");
})

$("#id_tipo_creditos_a_renovar").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

function cargaTipoCreditoRenovacion(){
	
	let $ddlTipoCreditoRenovacion = $("#id_tipo_creditos_a_renovar");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoCredito&action=cargaTipoCreditoRenovacion",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlTipoCreditoRenovacion.empty();
		$ddlTipoCreditoRenovacion.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlTipoCreditoRenovacion.append("<option value= " +value.id_tipo_creditos +" >" + value.nombre_tipo_creditos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlTipoCreditoRenovacion.empty();
	})
	
}

$("#id_tipo_creditos_renovacion").on("focus",function(){
	$("#mensaje_id_tipo_creditos").text("").fadeOut("");
})

$("#id_tipo_creditos_a_renovar").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

