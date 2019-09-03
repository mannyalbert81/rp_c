$(document).ready(function(){
	
	consultaGarantia();
	cargaEstadoGarantia();
	
})


$("#frm_garantia").on("submit",function(event){
	
	var _id_estado = document.getElementById('id_estado').value;
	var _id_creditos_garantias = document.getElementById('id_creditos_garantias').value;
	var parametros = {id_estado:_id_estado,id_creditos_garantias:_id_creditos_garantias}
	

	
	
	if(_id_creditos_garantias == 0){
		swal({
		  		  title: "Periodo",
		  		  text: "Seleccione un Garante",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		
		return false;
	}

	if(_id_estado == 0){
		$("#mensaje_id_estado").text("Seleccione un Estado").fadeIn("Slow");
		return false;
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=GarantiaCredito&action=InsertaGarantia",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Creditos Garantia",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		
		swal({
	  		  title: "Creditos Garantia",
	  		  text: err,
	  		  icon: "error",
	  		  button: "Aceptar",
	  		});
		
		console.log(err);
		
	}).always(function(){
		$("#id_creditos_garantias").val(0);
		document.getElementById("frm_garantia").reset();	
		consultaGarantia();
	})

	event.preventDefault()
})


function consultaGarantia(_page = 1){
	
	var buscador = $("#buscador").val();
	var _id_creditos_garantias = $("#id_creditos_garantias").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=GarantiaCredito&action=consultaGarantia",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax', id_creditos_garantias :_id_creditos_garantias}
	}).done(function(datos){		
		
		$("#garantia_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

function cargaEstadoGarantia(){
	
	let $ddlEstado = $("#id_estado");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=GarantiaCredito&action=cargaEstadoGarantia",
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

$("#id_creditos_garantias").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


function editGarantia(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
	beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=GarantiaCredito&action=editGarantia",
		type:"POST",
		dataType:"json",
		data:{id_creditos_garantias:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
		
			
			var array = datos.data[0];		
			$("#id_creditos").val(array.numero_creditos);			
			$("#id_participes").val(array.apellido_participes +" "+ array.nombre_participes);
			$("#id_estado").val(array.id_estado);
			$("#id_creditos_garantias").val(array.id_creditos_garantias);
			
			$("html, body").animate({ scrollTop: $(id_creditos_garantias).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		
		$("#divLoaderPage").removeClass("loader")
		consultaGarantia();
	})
	
	return false;
	
}


