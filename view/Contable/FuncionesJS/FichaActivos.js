$(document).ready(function(){
	
	consultaFicha();
	
})


$("#frm_ficha").on("submit",function(event){
	
	let _fecha_inicio_ficha_mantenimiento = document.getElementById('fecha_inicio_ficha_mantenimiento').value;
	let _danio_ficha_mantenimiento = document.getElementById('danio_ficha_mantenimiento').value;
	let _partes_reemplazado_ficha_mantenimiento = document.getElementById('partes_reemplazado_ficha_mantenimiento').value;
	let _responsable_ficha_mantenimiento = document.getElementById('responsable_ficha_mantenimiento').value;
	let _descripcion_ficha_mantenimiento = document.getElementById('descripcion_ficha_mantenimiento').value;
	var _id_ficha_mantenimiento = document.getElementById('id_ficha_mantenimiento').value;
	var _id_activos_fijos = document.getElementById('id_activos_fijos').value;
	var parametros = {fecha_inicio_ficha_mantenimiento:_fecha_inicio_ficha_mantenimiento,danio_ficha_mantenimiento:_danio_ficha_mantenimiento,partes_reemplazado_ficha_mantenimiento:_partes_reemplazado_ficha_mantenimiento,responsable_ficha_mantenimiento:_responsable_ficha_mantenimiento,descripcion_ficha_mantenimiento:_descripcion_ficha_mantenimiento,id_ficha_mantenimiento:_id_ficha_mantenimiento,id_activos_fijos:_id_activos_fijos}

	
	if(_fecha_inicio_ficha_mantenimiento == ""){
		$("#mensaje_fecha_inicio_ficha_mantenimiento").text("Seleccione una Fecha").fadeIn("Slow");
		return false;
	}
	if(_danio_ficha_mantenimiento == ""){
		$("#mensaje_danio_ficha_mantenimiento").text("Ingrese un Daño").fadeIn("Slow");
		return false;
	}
	if(_partes_reemplazado_ficha_mantenimiento == ""){
		$("#mensaje_partes_reemplazado_ficha_mantenimiento").text("Ingrese").fadeIn("Slow");
		return false;
	}
	if(_responsable_ficha_mantenimiento == ""){
		$("#mensaje_responsable_ficha_mantenimiento").text("Ingrese un responsable").fadeIn("Slow");
		return false;
	}
	if(_descripcion_ficha_mantenimiento == ""){
		$("#mensaje_descripcion_ficha_mantenimiento").text("Ingrese una descripcion").fadeIn("Slow");
		return false;
	}

	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=ActivosFijos&action=InsertaFicha",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Periodo",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_ficha_mantenimiento").val(0);
		document.getElementById("frm_ficha").reset();	
		consultaFicha();
	})

	event.preventDefault()
})


function consultaFicha(_page = 1){
	
	var buscador = $("#buscador").val();
	var _id_activos_fijos = $("#id_activos_fijos").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ActivosFijos&action=consultaFicha",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax', id_activos_fijos:_id_activos_fijos}
	}).done(function(datos){		
		
		$("#ficha_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}
