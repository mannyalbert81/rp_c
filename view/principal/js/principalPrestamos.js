$(document).ready(function(){
	
	loadCargaTipoPrestamos();
	loadCargaEstadoPrestamos();
	loadCargaEntidadPatronal();
	
	init();
})

function init(){
	try{
		
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
 * @desc funcion para traer el estado de los participes
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadCargaTipoPrestamos(){	
	
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

function loadCargaEstadoPrestamos(){	
	
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
function loadCargaEntidadPatronal(){	
	
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




