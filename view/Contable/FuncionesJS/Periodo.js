$(document).ready(function(){
	
	consultaPeriodo();
	cargaEstadoPeriodo();
	cargaTipoCierre();
	
})

/***
 * function to add record into table test_bancos
 * dc 2019-04-22
 * @param event
 * @returns
 */
$("#frm_periodo").on("submit",function(event){
	
	let _year_periodo = document.getElementById('year_periodo').value;
	let _mes_periodo = document.getElementById('mes_periodo').value;
	var _id_tipo_cierre = document.getElementById('id_tipo_cierre').value;
	var _id_estado = document.getElementById('id_estado').value;
	var _id_periodo = document.getElementById('id_periodo').value;
	var parametros = {year_periodo:_year_periodo,mes_periodo:_mes_periodo,id_tipo_cierre:_id_tipo_cierre,id_estado:_id_estado,id_periodo:_id_periodo}
	
	if(_id_tipo_cierre == 0){
		$("#mensaje_id_tipo_cierre").text("Seleccione un Tipo").fadeIn("Slow");
		return false;
	}
	if(_id_estado == 0){
		$("#mensaje_id_estado").text("Seleccione un Estado").fadeIn("Slow");
		return false;
	}

	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Periodo&action=InsertaPeriodo",
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
		$("#id_periodo").val(0);
		document.getElementById("frm_periodo").reset();	
		consultaPeriodo();
	})

	event.preventDefault()
})

$("#btnCerrar").on("click",function(event){
	
	let _year_periodo = document.getElementById('year_periodo').value;
	let _mes_periodo = document.getElementById('mes_periodo').value;
	var _id_tipo_cierre = document.getElementById('id_tipo_cierre').value;
	var _id_estado = document.getElementById('id_estado').value;
	var _id_periodo = document.getElementById('id_periodo').value;
	var parametros = {year_periodo:_year_periodo,mes_periodo:_mes_periodo,id_tipo_cierre:_id_tipo_cierre,id_estado:_id_estado,id_periodo:_id_periodo}
	


	if(_id_periodo == 0){
		$("#mensaje_id_tipo_cierre")
		swal({
		  		  title: "Periodo",
		  		  text: "Seleccione un Periodo",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		;
		return false;
	}


	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Periodo&action=CerrarPeriodo",
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
		$("#id_periodo").val(0);
		document.getElementById("frm_periodo").reset();	
		consultaPeriodo();
	})

	event.preventDefault()
})
$("#btnAbrir").on("click",function(event){
	
	let _year_periodo = document.getElementById('year_periodo').value;
	let _mes_periodo = document.getElementById('mes_periodo').value;
	var _id_tipo_cierre = document.getElementById('id_tipo_cierre').value;
	var _id_estado = document.getElementById('id_estado').value;
	var _id_periodo = document.getElementById('id_periodo').value;
	var parametros = {year_periodo:_year_periodo,mes_periodo:_mes_periodo,id_tipo_cierre:_id_tipo_cierre,id_estado:_id_estado,id_periodo:_id_periodo}
	
	if(_id_tipo_cierre == 0){
		$("#mensaje_id_tipo_cierre").text("Seleccione un Tipo").fadeIn("Slow");
		return false;
	}


	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Periodo&action=AbrirPeriodo",
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
		$("#id_periodo").val(0);
		document.getElementById("frm_periodo").reset();	
		consultaPeriodo();
	})

	event.preventDefault()
})

/***
 * function to update Table Bancos
 * dc 20119-04-22
 * @param id
 * @returns
 */
function editPeriodo(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Periodo&action=editPeriodo",
		type:"POST",
		dataType:"json",
		data:{id_periodo:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
		
			
			var array = datos.data[0];		
			$("#year_periodo").val(array.year_periodo);			
			$("#mes_periodo").val(array.mes_periodo);
			$("#id_tipo_cierre").val(array.id_tipo_cierre);
			$("#id_estado").val(array.id_estado);
			$("#id_periodo").val(array.id_periodo);
			
			$("html, body").animate({ scrollTop: $(year_periodo).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		
		$("#divLoaderPage").removeClass("loader")
		consultaPeriodo();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-04-22
 * @param id
 * @returns
 */
function delPeriodo(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Periodo&action=delPeriodo",
		type:"POST",
		dataType:"json",
		data:{id_periodo:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Periodo",
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
		consultaPeriodo();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaPeriodo(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Periodo&action=consultaPeriodo",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#periodo_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

/***
 * funcion para cargar estado de tes_bancos
 * dc 2019-04-22
 * @returns
 */
function cargaEstadoPeriodo(){
	
	let $ddlEstado = $("#id_estado");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Periodo&action=cargaEstadoPeriodo",
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

$("#year_periodo").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})



function cargaTipoCierre(){
	
	let $ddlTipoCierre = $("#id_tipo_cierre");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Periodo&action=cargaTipoCierre",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlTipoCierre.empty();
		$ddlTipoCierre.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlTipoCierre.append("<option value= " +value.id_tipo_cierre +" >" + value.nombre_tipo_cierre  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlTipoCierre.empty();
	})
	
}

$("#id_tipo_cierre").on("focus",function(){
	$("#mensaje_id_tipo_cierre").text("").fadeOut("");
})

$("#year_periodo").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


