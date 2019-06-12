$(document).ready(function(){
	
	consultaEmpleo();
	
	
})

/***
 * function to add record into table test_bancos
 * dc 2019-04-22
 * @param event
 * @returns
 */
$("#frm_empleo").on("submit",function(event){
	
	let _nombre_profesion = document.getElementById('nombre_profesion').value;
	var _id_empleo = document.getElementById('id_empleo').value;
	var parametros = {nombre_profesion:_nombre_profesion,id_empleo:_id_empleo}
	
	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CoreEmpleo&action=InsertaEmpleo",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Empleos",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_empleo").val(0);
		document.getElementById("frm_empleo").reset();	
		consultaEmpleo();
	})

	event.preventDefault()
})

/***
 * function to update Table Bancos
 * dc 20119-04-22
 * @param id
 * @returns
 */
function editEmpleo(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEmpleo&action=editEmpleo",
		type:"POST",
		dataType:"json",
		data:{id_empleo:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#nombre_profesion").val(array.nombre_profesion);			
			$("#id_empleo").val(array.id_empleo);
			
			
			$("html, body").animate({ scrollTop: $(nombre_profesion).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaEmpleo();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-04-22
 * @param id
 * @returns
 */
function delEmpleo(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEmpleo&action=delEmpleo",
		type:"POST",
		dataType:"json",
		data:{id_empleo:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Empleos",
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
		consultaEmpleo();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaEmpleo(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CoreEmpleo&action=consultaEmpleo",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#empleo_registrados").html(datos)		
		
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



$("#nombre_profesion").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


