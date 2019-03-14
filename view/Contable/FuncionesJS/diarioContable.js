$(document).ready(function(){
	
	
})

$( "#codigo_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayor&action=AutocompleteCodigo",
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
			url:'index.php?controller=LibroMayor&action=AutocompleteCodigo',
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

	source: "index.php?controller=LibroMayor&action=AutocompleteNombre",
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
			url:'index.php?controller=LibroMayor&action=AutocompleteNombre',
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


$('#frm_libro_diario').on('submit',function(event){

	var parametros = new FormData(this)
	
	parametros.append('action','ajax')
	
	parametros.forEach((value,key) => {
      console.log(key+" "+value)
	});
	
	
	if(!validafecha()){
		return false
	}
	
	
	$.ajax({
		url:'index.php?controller=LibroDiario&action=diarioContable', 
		contentType: false, //importante enviar este parametro en false
        processData: false,/*dataType:'json',*/
        type:'POST',
        data:parametros
		}).done(function(respuesta){
			
			PDFObject.embed(respuesta, "#detalle_diario");
			
			//PDFObject.embed("view/reportes/ejemplo.pdf", "#verpdf");
			//console.log(respuesta);
			$('#pdf_respuesta').data = 'data:application/pdf;base64,'+respuesta;
			
			//PDFObject.embed(respuesta, "#detalle_diario");
			
			//$('#detalle_diario').html(respuesta) 
			
		}).fail(function(){})
		
	event.preventDefault();
})

function validafecha(){
	var desde = $('#desde_diario').val()
	var hasta = $('#hasta_diario').val()
	
	if(desde!='' && hasta!=''){
		if(desde>=hasta){
			$('#mensaje_desde_diario').text('Fecha Ingresada debe ser menor').fadeIn();
			return false
		}
	}
	
	return true
}

$('#desde_diario').on('focus',function(){$('#mensaje_desde_diario').text('').fadeOut();})

