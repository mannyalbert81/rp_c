var GLOBALfecha = new Date();
var GLOBALyear = GLOBALfecha.getFullYear();
var GLOBALStringFecha = GLOBALfecha.getDate() + "/" + (GLOBALfecha.getMonth() +1) + "/" + GLOBALfecha.getFullYear();

$(document).ready(function(){
	
	$('#fecha_desde').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
			  'yearrange': { minyear: 1950,
				  			 maxyear: GLOBALyear	
				  			},
  			  'clearIncomplete': true
			});
	
	$('#fecha_hasta').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
		      'yearrange': { minyear: 1950,
			  			 maxyear: GLOBALyear	
			  			},
	  		  'clearIncomplete': true
		});
	
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


$('#frm_libro_mayor').on('submit',function(event){
	
	var validacion  = validarControles();
	
	if(!validacion){
		return false;
	}
	
	var formulario = $(this)
	
	formulario.attr('target','_blank');
	
	formulario.attr('action','index.php?controller=LibroMayor&action=mayorContable');

	//event.preventDefault();
})

function validarControles(){
	
	var $fecha_desde = $("#fecha_desde"),
		$fecha_hasta = $("#fecha_hasta"),
		$id_plan_cuenta = $("#id_cuenta"),
		$proveedor = $("#datos_proveedores");
		
	/** validacion de fechas **/
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length == 0 || $fecha_hasta.val() == "" ) ){
		$fecha_hasta.val(GLOBALStringFecha);
	}
	
	if( ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") && ($fecha_desde.val().length == 0 || $fecha_desde.val() == "") ){
		$fecha_desde.val(GLOBALStringFecha);
	}
	
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") ){

		if ($.datepicker.parseDate('dd/mm/yy', $fecha_desde.val()) > $.datepicker.parseDate('dd/mm/yy', $fecha_hasta.val())) {
			$fecha_desde.notify("Fecha no puede ser mayor",{ 'autoHideDelay':1000,position:"buttom-left"});
			return false;
		}
	}
	
	return true;
}

