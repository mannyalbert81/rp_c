$(document).ready(function(){
	
	consultaFormasPago();
	
})

/***
 * function to add record into table test_bancos
 * dc 2019-04-22
 * @param event
 * @returns
 */
$("#frm_formas_pago").on("submit",function(event){
	
	let _nombre_forma_pago = document.getElementById('nombre_forma_pago').value;
	let _id_forma_pago = document.getElementById('id_forma_pago').value;
	var parametros = {nombre_forma_pago:_nombre_forma_pago,id_forma_pago:_id_forma_pago}
	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=FormasPago&action=InsertaFormasPago",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Formas Pago",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		document.getElementById("frm_formas_pago").reset();	
		consultaFormasPago();
	})

	event.preventDefault()
})

/***
 * function to update Table Bancos
 * dc 20119-04-22
 * @param id
 * @returns
 */
function editFormasPago(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=FormasPago&action=editFormasPago",
		type:"POST",
		dataType:"json",
		data:{id_forma_pago:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#nombre_forma_pago").val(array.nombre_forma_pago);			
			$("#id_forma_pago").val(array.id_forma_pago);
			
			$("html, body").animate({ scrollTop: $(nombre_forma_pago).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaFormasPago();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-04-22
 * @param id
 * @returns
 */
function delFormasPago(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=FormasPago&action=delFormasPago",
		type:"POST",
		dataType:"json",
		data:{id_forma_pago:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Formas Pago",
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
		consultaFormasPago();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaFormasPago(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=FormasPago&action=consultaFormasPago",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#formasPago_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#nombre_forma_pago").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


