$(document).ready(function(){
	
	cargaCategoria();
	
})

function cargaCategoria(){
	
	let $ddlCategorias = $("#id_categorias");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Indexacion&action=cargaCategoria",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCategorias.empty();
		$ddlCategorias.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlCategorias.append("<option value= " +value.id_categorias +" >" + value.nombre_categorias  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCategorias.empty();
		$ddlCategorias.append("<option value='0' >--Seleccione--</option>");
		
	})
	
}


function cargaSubCategoria(id_categorias){
	
	let $dllSubCategorias = $("#id_subcategorias");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Indexacion&action=cargaSubCategoria",
		type:"POST",
		dataType:"json",
		data:{id_categorias:id_categorias}
	}).done(function(datos){		
		
		$dllSubCategorias.empty();
		$dllSubCategorias.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$dllSubCategorias.append("<option value= " +value.id_subcategorias +" >" + value.nombre_subcategorias  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$dllSubCategorias.empty();
		$dllSubCategorias.append("<option value='0' >--Seleccione--</option>");
		
	})
	
}


  
$("#id_categorias").click(function() {
	
  var id_categorias = $(this).val();
  let $dllSubCategorias = $("#id_subcategorias");
  $dllSubCategorias.empty();
  cargaSubCategoria(id_categorias);
 
});



$("#id_categorias").change(function() {
	
      
      var id_categorias = $(this).val();
      let $dllSubCategorias = $("#id_subcategorias");
      $dllSubCategorias.empty();
      cargaSubCategoria(id_categorias);
      
      
    });




$( "#numero_credito" ).autocomplete({
 source: 'index.php?controller=Indexacion&action=AutocompleteCedula',
  	minLength: 1
 });
                    		
$("#numero_credito").focusout(function(){
                    					
$.ajax({
url:'index.php?controller=Indexacion&action=DevuelveNombre',
type:'POST',
dataType:'json',
data:{numero_credito:$('#numero_credito').val()}
}).done(function(respuesta){
                    		
	$('#nombres_capremci').val(respuesta.nombres_capremci);
	$('#numero_credito').val(respuesta.numero_credito);
	$('#id_capremci').val(respuesta.id_capremci)
                    						
                    					
}).fail(function(respuesta) {
                    						  
$('#nombres_capremci').val("");
$('#numero_credito').val("");
			                    						
});
                    					
});
                    				
