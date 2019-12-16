$(document).ready(function(){
	
	/*$("[data-toggle=popover]").popover({
	    html: true, 
		content: function() {
	          return $('#popover-content').html();
	        }
	});*/
	
	$("#btnShowLote").popover({
	    html: true, 
		content: function() {
			  //$('#popover-content').removeClass( "hide" );
			console.log("ingresa fn popover");
			//$('#popover-content').html("")
	          return $('#popover-content-lote').html();
	        }
	});
	
})

function setPopOverLote(){	
	 $('#popover-content-lote').html(popOverHtml);
}

function popOverHtml(){
	console.log("ingresa fn devuelve string");
	var $objeto = '<form id="frm_lote" class="form-inline" role="form">'
					+'<div class="form-group">' 
					+'<input type="text" class="" id="nombre_lote"    placeholder="Nombre lote" />'
					+'<button type="button" id="btnLote" class="btn btn-primary btn-xs" onclick="popGeneraLote()">Registrar</button>'
					+'</div>'
					+'</form>';
	return $objeto;
}

function popGeneraLote(){
	//se toma el input que forma dentro popover como se dibuja se crean dos en la vista.
	var $nombre_lote = $('#frm_lote input[id=nombre_lote]');
	console.log($nombre_lote);
	if( $nombre_lote.val() == "" || $nombre_lote.val().length == 0){
		$nombre_lote.notify("Ingrese nombre lote",{position:"buttom-left", autoHideDelay: 2000});
		return false;
	}
	$.ajax({
		url:"index.php?controller=TesCuentasPagar&action=RegistrarLote",
		type:"POST",
		dataType:"json",
		data:{nombre_lote:$nombre_lote.val()}
	}).done(function(x){
		if(x.respuesta != undefined){
			if( x.respuesta == "OK" ){
				/** aqui habilitar todos los controles **/
			}
		}
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
}
