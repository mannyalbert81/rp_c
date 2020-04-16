$(document).ready(function(){
	
	loadTipoPrestamos();
	loadEstadoPrestamos();
	loadEntidadPatronal();
	
	init();
})

function init(){
	try{
		
		//$('#txtFNacimiento').daterangepicker();
		//$('#txtFIngreso').daterangepicker();
		//$('#txtFBaja').daterangepicker();
		
		$('#txtFSolicitud').daterangepicker({
			  autoUpdateInput: false
			}, function(start_date, end_date) {
			  $('#txtFSolicitud').val(start_date.format('YYYY/MM/DD')+' - '+end_date.format('YYYY/MM/DD'));
			});
		
		
		$("#pnlResultados").addClass("hidden");
		
	}catch (e) {
		// TODO: handle exception
		console.log(e);
	}	
}
/***
 * @desc funcion para traer el tipo de prestamos
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadTipoPrestamos(){	
	
	var $ddlTipo = $("#id_tipo_creditos");
	params = {};
	$ddlTipo.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaTipoPrestamos",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsTipo	= x.data;
			$ddlTipo.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipo,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipo.append( '<option value="'+value.id_tipo_creditos+'">'+value.nombre_tipo_creditos+'</option>' );
			})
					
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}
/***
 * @desc funcion para traer el estado de los participes
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadEstadoPrestamos(){	
	
	var $ddlEstado = $("#id_estado_creditos");
	params = {};
	$ddlEstado.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaEstadoPrestamos",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsEstado	= x.data;
			$ddlEstado.append('<option value="0">--Seleccione--</option>' );
			$.each(rsEstado,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlEstado.append( '<option value="'+value.id_estado_creditos+'">'+value.nombre_estado_creditos+'</option>' );
			})			
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}
/***
 * @desc funcion para traer entidades patronales
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadEntidadPatronal(){	
	
	var $ddlEntidad = $("#id_entidad_patronal");
	params = {};
	$ddlEntidad.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaEntidadPatronal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsEntidad	= x.data;
			$ddlEntidad.append('<option value="0">--Seleccione--</option>' );
			$.each(rsEntidad,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlEntidad.append( '<option value="'+value.id_entidad_patronal+'">'+value.nombre_entidad_patronal+'</option>' );
			})
					
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function loadBusquedaPrestamos( pagina ){
	
	var incedula 	= $("#txtCedula");
	var innombre			= $("#txtNombre");
	var inapellido			= $("#txtApellido");
	var intipocreditos			= $("#id_tipo_creditos");
	var infsolicitud		= $("#txtFSolicitud");
	var inestadocreditos			= $("#id_estado_creditos");
	var inentidadpatronal	= $("#id_entidad_patronal");
	
	var params	= {
			page: pagina,
			cedula:incedula.val(),
			nombre: innombre.val(),
			apellido: inapellido.val(),
			id_tipo_creditos: intipocreditos.val(),
			fsolicitud: infsolicitud.val(),
			id_estado_creditos: inestadocreditos.val(),
			id_entidad_patronal: inentidadpatronal.val()
	};
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		complete:function(){$("#divLoaderPage").removeClass("loader")},
		url:"index.php?controller=PrincipalPrestamos&action=CargaPrestamos",
		dataType:"json",
		type:"POST",
		data:params
	}).done(function(x){
		$("#tblResultadosPrincipalPrestamos").empty();
		if( x.tabla != undefined ){
			
			if( x.tabla != "" ){
				$("#pnlBusquedaPrestamos").addClass("hidden");
				$("#pnlResultadosPrestamos").removeClass("hidden");
				$("#tblResultadosPrincipalPrestamos").append(x.tabla);
				$("#spanCantidadPrestamos").text("Se encontraon ( "+x.cantidadDatos+" ) socio/s" );
				$("#mod_paginacion_resultados_prestamos").html(x.paginacion);
			}
		}
		
		console.log(x);
		
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

$("#btn_principal_prestamos").on("click",function(){
	
	loadBusquedaPrestamos(1);	
	
})

