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



$("#cedula_capremci").on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=Indexacion&action=AutocompleteNumeroCredito",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {	     	       		    			
    			if(ui.item.id == ''){
    				$("#id_capremci").val('');
    				$("#nombres_capremci").val("");
    				_elemento.val("");
    				$("#numero_credito").val("");
    				_elemento.focus();	   
    				 return;
    			}
    			$("#id_capremci").val(ui.item.id);
    			_elemento.val(ui.item.value);
    			$("#nombres_capremci").val(ui.item.nombre);
    			$("#numero_credito").val(ui.item.id);
    						
    			
     	    },
     	   appendTo: null,
     	   change: function(event,ui){	     		   
     		   if(ui.item == null){	 
     				$("#id_capremci").val('');
    				$("#nombres_capremci").val("");
    				$("#numero_credito").val("");
    				_elemento.val("");
     			//_elemento.notify("Cedula no se encuentra registrada",{ position:"buttom left", autoHideDelay: 2000});
     		   }
     	   }
    	
    	})
    }
});

$("#Guardar").click(function() {
	//selecionarTodos();
	
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
			    	
	var id_categorias  = $("#id_categorias").val();
	var id_subcategorias  = $("#id_subcategorias").val();
	var cedula_capremci = $("#cedula_capremci").val();
	var nombres_capremci  = $("#nombres_capremci").val();
	var numero_credito = $("#numero_credito").val();
	
	
	
	if (id_categorias  == 0)
	{    	
		$("#mensaje_id_categorias").text("Seleccione una Categoría");
		$("#mensaje_id_categorias").fadeIn("slow"); //Muestra mensaje de error
        return false
    }    
	
	else
		{
		
		$("#mensaje_id_categorias").fadeOut("slow"); //Muestra mensaje de error
	    	
		
		}
	
	
	if (id_subcategorias  == 0)
	{    	
		$("#mensaje_id_subcategorias").text("Seleccione una Subcategoría");
		$("#mensaje_id_subcategorias").fadeIn("slow"); //Muestra mensaje de error
        return false
    }    
	
	else
		{
		
		$("#mensaje_id_subcategorias").fadeOut("slow"); //Muestra mensaje de error
		}
	
	if (cedula_capremci  == "")
	{    	
		$("#mensaje_cedula_capremci").text("Introduzca una Cédula");
		$("#mensaje_cedula_capremci").fadeIn("slow"); //Muestra mensaje de error
        return false
    }    
	
	else
		{
		
		$("#mensaje_cedula_capremci").fadeOut("slow"); //Muestra mensaje de error
		}
	
	if (nombres_capremci  == "")
	{    	
		$("#mensaje_nombres_capremci").text("Introduzca un nombre");
		$("#mensaje_nombres_capremci").fadeIn("slow"); //Muestra mensaje de error
        return false
    }    
	
	else
		{
		
		$("#mensaje_nombres_capremci").fadeOut("slow"); //Muestra mensaje de error
		}
	if (numero_credito  == "")
	{    	
		$("#mensaje_numero_credito").text("Introduzca un nombre");
		$("#mensaje_numero_credito").fadeIn("slow"); //Muestra mensaje de error
        return false
    }    
	
	else
		{
		
		$("#mensaje_numero_credito").fadeOut("slow"); //Muestra mensaje de error
		}
		
		
                    				
});

 $( "#id_categorias" ).focus(function() {
	  $("#mensaje_id_categorias").fadeOut("slow");
   });
 $( "#id_subcategorias" ).focus(function() {
	  $("#mensaje_id_subcategorias").fadeOut("slow");
  });
 $( "#cedula_capremci" ).focus(function() {
	  $("#mensaje_cedula_capremci").fadeOut("slow");
  });
 $( "#nombres_capremci" ).focus(function() {
	  $("#mensaje_nombres_capremci").fadeOut("slow");
  });
 $( "#numero_credito" ).focus(function() {
	  $("#mensaje_numero_credito").fadeOut("slow");
  });