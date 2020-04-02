/***
 * dc 2019-05-06
 * @returns
 */
$(document).ready(function(){
	
	consultaImpuestos();
	validaTipoImpuesto();
	
})

/**
 * para autocomplete de plan cuentas
 * dc 2019-05-06
 * @param pagina
 * @returns json
 */
$( "#plan_cuentas" ).autocomplete({
	source: "index.php?controller=Impuestos&action=AutocompletePlanCuentas",
	minLength: 3,
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
 * @desc funcion para validar el event de tipo de impuesto
 * @returns void
 */
function validaTipoImpuesto(){
	var $tipoImpuesto 		= $("#tipo_impuestos");
	var $codigoImpuestos 		= $("#codigo_impuestos");
	var $codRetencionImpuesto 	= $("#codretencion_impuestos");
	if( $tipoImpuesto.val() == "iva" ){
		$codigoImpuestos.attr("disabled",true);
		$codRetencionImpuesto.attr("disabled",true);
		$codigoImpuestos.val("0");
		$codRetencionImpuesto.val("0");
	}else{
		$codigoImpuestos.attr("disabled",false);
		$codRetencionImpuesto.attr("disabled",false);
	}
}


/***
 * @desc funcion para cargar archivo json de codigo de retenciones
 * @param valor de retencion impuestos
 * @var retencion_impuesto
 */
function getCodigoRetencion(objeto){
	
	var $codigoImpuesto 	= $(objeto);
	var $tipoImpuesto 		= $("#tipo_impuestos");
	var $codRetencionImpuesto 	= $("#codretencion_impuestos");
	// !!nota si cambia los values/text del select tambien deben cambiar el archivo json
	var nombreRetencion 	= $codigoImpuesto.find('option:selected').text().toLowerCase(); 	
	
	if( $tipoImpuesto.val() == "retencion" ){
		
		$.getJSON( "view/tesoreria/archivos/impuestos3.json", function( data ) {
			
			  $codRetencionImpuesto.empty();
			  var dataSelect = data[nombreRetencion];
			  var items = [];
			  items.push( "<option value='0'>--Seleccione--</option>" );
			  $.each( dataSelect, function( index, value ) {		
				  var nombreImp = value.nombre;
				  console.log( nombreImp );				  
				  items.push( "<option value='" + value.valor + "'>"+ value.valor + " <--> " + nombreImp + "</option>" );			    
			  });
			 
			  $codRetencionImpuesto.append(items.join(""));
			  
		});
		
	}
}

/***
 * @desc fuction to validate campos
 * @params none
 * @returns bool 
 */
function ValidateFormImpuestos(){
	if( $("fuente_impuestos").val() == "0" ){
		$("fuente_impuestos").notify("Seleccione Fuente Impuestos",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validar campo de cuenta
	$codCuenta = $("#plan_cuentas");
	$id_planCuenta = $("#id_plan_cuentas");
	if( $codCuenta.val() == "" || $codCuenta.val().length == 0 || $id_planCuenta.val() == "0" || $id_planCuenta.val() == "" ){
		$codCuenta.notify("Cuenta Relacionda no identificada",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validar campo nombre Impuestos
	$nombreImpuestos = $("#nombre_impuestos");
	if( $nombreImpuestos.val() == "" || $nombreImpuestos.val().length == 0 ){
		$nombreImpuestos.notify("Ingrese Nombre Impuesto",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	//validar campo descripcion Impuestos
	$descripcionImpuestos = $("#descripcion_impuestos");
	if( $descripcionImpuestos.val() == "" || $descripcionImpuestos.val().length == 0 ){
		$descripcionImpuestos.notify("Ingrese Descripcion Impuesto",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validar campo porcentaje Impuestos
	var expresion = /^([0-9]{1,3})$/;
	$porcentajeImpuestos = $("#porcentaje_impuestos");
	if( !expresion.exec( $porcentajeImpuestos.val() ) ){
		$porcentajeImpuestos.notify("Porcentaje de impuesto no válido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if( $porcentajeImpuestos.val() > 100 ){
		$porcentajeImpuestos.notify("Valor Porcentaje no válido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validar retenciones
	$tipoImpuesto = $("#tipo_impuestos");	
	$codigoImpuestos = $("#codigo_impuestos");
	$codretencionImpuestos = $("#codretencion_impuestos");
	if( $tipoImpuesto.val() == "retencion" ){	
		
		if( $codigoImpuestos.val() == "0" ){
			$codigoImpuestos.notify(" Seleccione Fuente Retencion",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
		if( $codretencionImpuestos.val() == "0" ){
			$codretencionImpuestos.notify(" Seleccione Codigo Retencion ",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}		
				
	}else{
		if( $codretencionImpuestos.val() != "0" || $codigoImpuestos.val() != "0" ){
			$tipoImpuesto.notify(" Seleccione Tipo Impuesto",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
	}
	
	return true;
}

/***
 * function to save tax
 * dc 2020-02-20
 * @parm none
 * @return event
 * @desc realiza insercion por medio de metodo ajax
 */
function AddImpuesto(){
	
	var ValidacionForm = ValidateFormImpuestos();
	console.log(ValidacionForm);
	if(!ValidacionForm){
		//swal({title:"Datos",text:"Revise Valores Ingresados",icon:"info"});
		return false;
	}
	
	var codigo_impuestos
	
	param = {
			id_impuestos:$("#id_impuestos").val(),
			fuente_impuestos:$("#fuente_impuestos").val(),
			id_plan_cuentas:$("#id_plan_cuentas").val(),
			nombre_impuestos:$("#nombre_impuestos").val(),
			descripcion_impuestos:$("#descripcion_impuestos").val(),
			porcentaje_impuestos:$("#porcentaje_impuestos").val(),
			tipo_impuestos:$("#tipo_impuestos").val(),
			codigo_impuestos:$("#codigo_impuestos").val(),
			codretencion_impuestos:$("#codretencion_impuestos").val(),
			codigo_texto_impuestos:$("#codigo_impuestos").find('option:selected').text()
			};	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Impuestos&action=InsertaImpuestos",
		type:"POST",
		dataType:"json",
		data:param
	}).done(function(datos){		
		
	   swal({
  		  title: "MENSAJE",
  		  text: datos.mensaje,
  		  icon: datos.icon,
  		  button: "Aceptar",
  		});
	   limpiarForm();
		
	}).fail(function(xhr,status,error){
		
		
		var err = xhr.responseText
		console.log(err);
		
		swal({
	  		  title: "MENSAJE",
	  		  text: "Error al Insertar Impuesto",
	  		  icon: "error",
	  		  button: "Aceptar",
	  		});
		
	}).always(function(){	
		consultaImpuestos();
	})
	
}


/***
 * function to save impuestos
 * dc 2019-05-06
 * @param event
 * @returns
 */
$("#frm_impuestoseeee").on("submit",function(event){
	
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
			$("#descripcion_impuestos").val(array.descripcion_impuestos);
			$("#fuente_impuestos").val(array.fuente_impuestos);
			
			//validacion de pocentaje 
			var valorPorcentaje = array.porcentaje_impuestos;
			valorPorcentaje	= Math.abs( Math.trunc(valorPorcentaje) );
			 
			$("#porcentaje_impuestos").val(valorPorcentaje);
			
			var tipo_impuestos = array.tipo_impuestos;
			if( tipo_impuestos.includes('ret') ){
				$("#tipo_impuestos").val("retencion");
				$("#codigo_impuestos").val(array.codigo_impuestos);				
				validaTipoImpuesto();
				getCodigoRetencion($("#codigo_impuestos"));
			}else{
				$("#tipo_impuestos").val("iva");
				$("#codigo_impuestos").val("0");
				$("#codretencion_impuestos").val("0");
			}
			
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


/***
 * @desc funcion para limpiar el formulario 
 */
function limpiarForm(){
	
	$("#id_impuestos").val("0");
	$("#id_plan_cuentas").val("0");
	$("#fuente_impuestos").val("compra");
	$("#plan_cuentas").val("");
	$("#nombre_impuestos").val("");
	$("#descripcion_impuestos").val("");
	$("#porcentaje_impuestos").val("");
	$("#tipo_impuestos").val("iva");
	validaTipoImpuesto();
          	   	
}

