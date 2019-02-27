

$( "#codigo_activos_fijos" ).autocomplete({
 source: 'index.php?controller=ActivosFijosDetalle&action=AutocompleteCodigoActivos',
  	minLength: 1
 });
                    		
$("#codigo_activos_fijos").focusout(function(){
                    					
$.ajax({
url:'index.php?controller=ActivosFijosDetalle&action=DevuelveNombreActivos',
type:'POST',
dataType:'json',
data:{codigo_activos_fijos:$('#codigo_activos_fijos').val()}
}).done(function(respuesta){
                    		
	$('#nombre_activos_fijos').val(respuesta.nombre_activos_fijos);
	$('#codigo_activos_fijos').val(respuesta.codigo_activos_fijos);
                    						
                    					
}).fail(function(respuesta) {
                    						  
$('#nombre_activos_fijos').val("");
$('#codigo_activos_fijos').val("");
			                    						
});
                    					
});
                    				
$( "#nombre_activos_fijos" ).autocomplete({
		source: 'index.php?controller=ActivosFijosDetalle&action=AutocompleteNombreActivos',
		minLength: 1
});

$("#nombre_activos_fijos").focusout(function(){
	
	$.ajax({
		url:'index.php?controller=ActivosFijosDetalle&action=DevuelveCodigoActivos',
		type:'POST',
		dataType:'json',
		data:{nombre_activos_fijos:$('#nombre_activos_fijos').val()}
	}).done(function(respuesta){

		$('#codigo_activos_fijos').val(respuesta.codigo_activos_fijos);
		$('#nombre_activos_fijos').val(respuesta.nombre_activos_fijos);
		
		
		
	
	}).fail(function(respuesta) {
		
		$('#nombre_activos_fijos').val("");
		$('#codigo_activos_fijos').val("");
					                    	
		                    						
	});
	
});   