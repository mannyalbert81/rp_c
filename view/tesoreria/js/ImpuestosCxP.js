/***
 * dc 2019-05-06
 * @returns
 */
$(document).ready(function(){
	
	consultaImpuestos();
	
})

/**
 * para autocomplete de plan cuentas
 * dc 2019-05-06
 * @param pagina
 * @returns json
 */
$( "#plan_cuentas" ).autocomplete({

	source: "index.php?controller=Impuestos&action=AutocompletePlanCuentas",
	minLength: 8,
    select: function (event, ui) {
       // Set selection          
       $('#id_plan_cuentas').val(ui.item.id);
       $('#plan_cuentas').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#plan_cuentas').val();            
        return false; 
    } 
}).focusout(function() {
	
	
});

/***
 * function to save impuestos
 * dc 2019-05-06
 * @param event
 * @returns
 */
$("#frm_impuestos").on("submit",function(event){
	
	let _id_plan_cuentas = $("#id_plan_cuentas").val();
	let _nombre_impuestos = $("#nombre_impuestos").val();
	let _valor_pocentaje = $("#porcentaje_impuestos").val();
	let _id_impuestos = $("#id_impuestos").val();
	let _tipo_impuestos = $("#tipo_impuestos").val();
	var parametros = {id_plan_cuentas:_id_plan_cuentas,nombre_impuestos:_nombre_impuestos,porcentaje_impuestos:_valor_pocentaje,
			id_impuestos:_id_impuestos,tipo_impuestos:_tipo_impuestos}
	
	if(_id_plan_cuentas == 0){
		$("#mensaje_plan_cuentas").text("Digite plan Cuentas").fadeIn("Slow");
		return false;
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Impuestos&action=InsertaImpuestos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){		
		
	swal({
  		  title: "MENSAJE",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		
		var err = xhr.responseText
		console.log(err);
		
		swal({
	  		  title: "MENSAJE",
	  		  text: "Error al Insertar Impuesto",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
		
	}).always(function(){
		$("#id_plan_cuentas").val(0);
		document.getElementById("frm_impuestos").reset();	
		consultaImpuestos();
	})

	event.preventDefault()
})

/***
 * function to update 
 * dc 2019-05-06
 * @param id
 * @returns
 */
function editImpuestos(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Impuestos&action=editImpuesto",
		type:"POST",
		dataType:"json",
		data:{id_impuestos:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#id_plan_cuentas").val(array.id_plan_cuentas);	
			$("#plan_cuentas").val(array.codigo_plan_cuentas);
			$("#id_impuestos").val(array.id_impuestos);
			$("#nombre_impuestos").val(array.nombre_impuestos);
			$("#porcentaje_impuestos").val(array.porcentaje_impuestos);
			$("#tipo_impuestos").val(array.tipo_impuestos);
			
			$("html, body").animate({ scrollTop: $(nombre_impuestos).offset().top-120 }, tiempo);			
		}		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaImpuestos();
	})
	
	return false;
	
}

/***
 * function to delete record of Banco's table
 * dc 2019-05-06
 * @param id
 * @returns
 */
function delImpuestos(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Impuestos&action=delImpuesto",
		type:"POST",
		dataType:"json",
		data:{id_impuestos:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "MENSAJE",
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
		consultaImpuestos();
	})
	
	return false;
}


/***
 * busca bancos registrados
 * dc 2019-04-22
 * @param _page
 * @returns
 */
function consultaImpuestos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Impuestos&action=consultaImpuestos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#impuestos_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

$("#plan_cuentas").on("focus",function(){
	$("#mensaje_plan_cuentas").text("").fadeOut("");
})

$("#nombre_impuestos").on("focus",function(){
	$("#mensaje_nombre_impuestos").text("").fadeOut("");
})

$("#nombre_bancos").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


