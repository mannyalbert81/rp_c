$(document).ready(function(){

})

$("#frm_tipo_activos").on("submit",function(event){
	
	var _nombre_tipo_activo = document.getElementById('nombre_tipo_activo').value;
	var _meses_tipo_activo = document.getElementById('meses_tipo_activo').value;
	var _id_tipo_activo = document.getElementById('id_tipo_activo').value;
	var parametros = {nombre_tipo_activo:_nombre_tipo_activo,meses_tipo_activo:_meses_tipo_activo,id_tipo_activo:_id_tipo_activo}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoActivos&action=InsertaTiposActivos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Depreciacion",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	})
	
	event.preventDefault()
})

function editTipo(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=TipoActivos&action=editTipoActivo",
		type:"POST",
		dataType:"json",
		data:{id_tipo_activo:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];
			console.log(array)					
			$("#nombre_tipo_activo").val(array.nombre_tipo_activos_fijos);
			$("#meses_tipo_activo").val(array.meses_tipo_activos_fijos);
			$("#id_tipo_activo").val(array.id_tipo_activos_fijos);
			
			$("html, body").animate({ scrollTop: $(nombre_tipo_activo).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	})
	
	return false;
	
}


