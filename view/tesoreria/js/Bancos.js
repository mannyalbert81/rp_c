$(document).ready(function(){
	
	consultaBancos();
	cargaEstado();
	
})

/***
 * function to add record into table test_bancos
 * dc 2019-04-22
 * @param event
 * @returns
 */
$("#frm_bancos").on("submit",function(event){
	
	let _nombre_bancos = document.getElementById('nombre_bancos').value;
	let _id_estado = document.getElementById('id_estado').value;
	var _id_bancos = document.getElementById('id_bancos').value;
	var _codigo_bancos = document.getElementById('codigo_bancos').value;
	var parametros = {nombre_bancos:_nombre_bancos,id_bancos:_id_bancos,id_estado:_id_estado,codigo_bancos:_codigo_bancos}
	
	if(_id_estado == 0){
		$("#mensaje_id_estado").text("Seleccione un Estado").fadeIn("Slow");
		return false;
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Bancos&action=InsertaBancos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Bancos",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_bancos").val(0);
		document.getElementById("frm_bancos").reset();	
		consultaBancos();
	})

	event.preventDefault()
})

/***
 * function to update Table Bancos
 * dc 20119-04-22
 * @param id
 * @returns
 */
function editBanco(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Bancos&action=editBancos",
		type:"POST",
		dataType:"json",
		data:{id_bancos:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#nombre_bancos").val(array.nombre_bancos);			
			$("#id_bancos").val(array.id_bancos);
			$("#id_estado").val(array.id_estado);
			$("#codigo_bancos").val(array.codigo_bancos);
			
			$("html, body").animate({ scrollTop: $(nombre_bancos).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaBancos();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-04-22
 * @param id
 * @returns
 */
function delBanco(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Bancos&action=delBancos",
		type:"POST",
		dataType:"json",
		data:{id_bancos:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Bancos",
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
		consultaBancos();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaBancos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Bancos&action=consultaBancos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#bancos_registrados").html(datos)		
		
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
function cargaEstado(){
	
	let $ddlEstado = $("#id_estado");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Bancos&action=cargaEstadoBancos",
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

$("#nombre_bancos").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


