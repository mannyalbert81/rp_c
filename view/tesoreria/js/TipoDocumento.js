$(document).ready(function(){
	
	consultaTipoDocumento();
	
})

$("#frm_tipo_documento").on("submit",function(event){
	
	let _nombre_tipo_documento = document.getElementById('nombre_tipo_documento').value;
	let _abrev_tipo_documento = document.getElementById('abrev_tipo_documento').value;
	let _id_tipo_documento = document.getElementById('id_tipo_documento').value;
	
	var parametros = {nombre_tipo_documento:_nombre_tipo_documento,abrev_tipo_documento:_abrev_tipo_documento,id_tipo_documento:_id_tipo_documento}
	
	if(_abrev_tipo_documento.trim().length == 0){
		
		$("#mensaje_abrev_tipo_documento").text("Ingrese dato solicitado").fadeIn("slow");
		return false;
	}
	
	if(_nombre_tipo_documento.trim().length == 0){
		
		$("#mensaje_nombre_tipo_documento").text("Ingrese dato solicitado").fadeIn("slow");
		return false;
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoDocumento&action=InsertaTipoDocumento",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Tipo Documento",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#id_tipo_documento").val(0);
		document.getElementById("frm_tipo_documento").reset();	
		consultaTipoDocumento();
	})

	event.preventDefault()
})

/***
 * function to update Table main
 * dc 2019-04-24
 * @param id
 * @returns
 */
function editTipoDocumento(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoDocumento&action=editTipoDocumento",
		type:"POST",
		dataType:"json",
		data:{id_tipo_documento:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#abrev_tipo_documento").val(array.abreviacion_tipo_documento);			
			$("#nombre_tipo_documento").val(array.nombre_tipo_documento);
			$("#id_tipo_documento").val(array.id_tipo_documento);
			
			$("html, body").animate({ scrollTop: $(abrev_tipo_documento).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaTipoDocumento();
	})
	
	return false;
	
}

/***
 * function to delete record of table
 * dc 2019-04-24
 * @param id
 * @returns
 */
function delTipoDocumento(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoDocumento&action=delTipoDocumento",
		type:"POST",
		dataType:"json",
		data:{id_tipo_documento:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Tipo Documento",
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
		consultaTipoDocumento();
	})
	
	return false;
}


/***
 * busca Tipo Documento registrados
 * dc 2019-04-24
 * @param _page
 * @returns
 */
function consultaTipoDocumento(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=TipoDocumento&action=consultaTipoDocumento",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#tipoDocumento_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}



$("#abrev_tipo_documento").on("focus",function(){
	$("#mensaje_abrev_tipo_documento").text("").fadeOut("");
})

$("#nombre_tipo_documento").on("focus",function(){
	$("#mensaje_nombre_tipo_documento").text("").fadeOut("");
})

$("#nombre_tipo_documento").on("keyup",function(){
	if($(this).val().trim().length==1){$(this).val($(this).val().toUpperCase());}
})


