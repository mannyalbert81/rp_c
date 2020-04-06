$(document).ready(function(){
	
	console.log("AUI ESATMOS EN LA SUB VENTANA");
	loadEntidadPatronal();
})

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
		url:"index.php?controller=PrincipalBusquedas&action=CargaEntidadPatronal",
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

function loadParticipes(){
	
	var id_participes = $("#hdnid_participes");
	var $tblparticipe = $("#id_entidad_patronal");
	
	$.ajax({
		url:"index.php?controller=PrincipalBusquedas&action=CargaEntidadPatronal",
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

