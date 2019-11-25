$(document).ready(function(){
	
	
})

$( "#codigo_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayorAuxiliar&action=AutocompleteCodigo",
	minLength: 4,
    select: function (event, ui) {
       // Set selection          
       $('#id_cuenta').val(ui.item.id);
       $('#codigo_cuenta').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#codigo_cuenta').val();            
        return false; 
    } 
}).focusout(function() {
	
	if(document.getElementById('codigo_cuenta').value != ''){
		$.ajax({
			url:'index.php?controller=LibroMayorAuxiliar&action=AutocompleteCodigo',
			type:'POST',
			dataType:'json',
			data:{term:document.getElementById('codigo_cuenta').value}
		}).done(function(respuesta){
			//console.log(respuesta[0].id);
			 if( !$.isEmptyObject(respuesta) && respuesta[0].id>0){
				
				 $('#nombre_cuenta').val(respuesta[0].nombre_cuenta)
				 $('#codigo_cuenta').val(respuesta[0].value)
				 $('#id_cuenta').val(respuesta[0].id)
				 
			}else{ $("#frm_libro_mayors")[0].reset(); }
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			 console.log(err)
			 
		});
	}
	
}).focus(function(){
	$(this).val('')
	$('#nombre_cuenta').val('')
	$('#id_cuenta').val('')
})

$( "#nombre_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayorAuxiliar&action=AutocompleteNombre",
	minLength: 4,
    select: function (event, ui) {
       // Set selection          
       $('#id_cuenta').val(ui.item.id);
       $('#nombre_cuenta').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#nombre_cuenta').val();            
        return false; 
    } 
}).focusout(function() {
	
	if(document.getElementById('nombre_cuenta').value != ''){
		$.ajax({
			url:'index.php?controller=LibroMayorAuxiliar&action=AutocompleteNombre',
			type:'POST',
			dataType:'json',
			data:{term:document.getElementById('nombre_cuenta').value}
		}).done(function(respuesta){
			//console.log(respuesta[0].id);
			 if( !$.isEmptyObject(respuesta) && respuesta[0].id>0){
				
				 $('#nombre_cuenta').val(respuesta[0].nombre_cuenta)
				 $('#codigo_cuenta').val(respuesta[0].value)
				 $('#id_cuenta').val(respuesta[0].id)
				 
			}else{ $("#frm_libro_mayors")[0].reset(); }
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			 console.log(err)
			 
		});
	}
	
}).focus(function(){
	$(this).val('')
	$('#codigo_cuenta').val('')
	$('#id_cuenta').val('')
})

$("#btnMayores").on("click",function(event){
	

	let $codigo_cuenta = $("#codigo_cuenta");
	//$divResultados.html();

	
	
	if($codigo_cuenta.val() == ''){
		$codigo_cuenta.notify("Seleccione la cuenta",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	 

	var pagina = 1;
	 var search=$("#buscador").val();
  
	 var con_datos={
				  action:'ajax',
				  page:pagina,
				  search:search,
				  codigo_cuenta:$codigo_cuenta.val()
				  };
     
     console.log("Mesaje cuenta: " + $codigo_cuenta.val() );
     
    // return false;
     
	 $.ajax({
         beforeSend: function(objeto){
           $("#load_detalle").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
         },
         url:"index.php?controller=LibroMayorAuxiliar&action=mayorContableAuxiliar",
         type: 'POST',
         data: con_datos,
         success: function(x){
           $("#registrados_detalle").html(x);
           $("#load_detalle").html("");
           //$("#tabla_retencion").tablesorter(); 
           
         },
        error: function(jqXHR,estado,error){
          $("#registrados_detalle").html("Ocurrio un error al cargar la informacion de Detalle Retenciones..."+estado+"    "+error);
        }
      });
	

 
})

$('#frm_libro_mayor').on('submit',function(event){
	
	var formulario = $(this)
	
	formulario.attr('target','_blank');
	
	formulario.attr('action','index.php?controller=LibroMayor&action=mayorContable');

	//event.preventDefault();
})

