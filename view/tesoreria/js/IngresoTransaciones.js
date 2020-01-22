$(document).ready(function(){
	
	getSecuencialDocumento();
	getTipoDocumento();
	ValidarControles();
	setearFecha();
		
	$("#btnLote").popover({
	    html: true, 
	    placement:"right",
		content: function () {		    
		    return popOverHtml();
		}
	});
	
	
})

function setearFecha(){
	
	var fecha = new Date();
	var yearFecha = fecha.getFullYear();
	
	$("#fecha_transaccion").inputmask("datetime",{
		mask: "y-2-1", 
	     placeholder: "yyyy-mm-dd", 
	     leapday: "-02-29", 
	     separator: "-", 
	     clearIncomplete: true,
		 rightAlign: true,		 
		 yearrange: {
				minyear: 1950,
				maxyear: yearFecha
			},
		oncomplete:function(e){
			if( ( new Date( $(this).val() ).getTime() > new Date( fecha ).getTime())){
				$(this).notify("Fecha no puede ser Mayor",{ position:"buttom left", autoHideDelay: 2000});
				$(this).val('')
		    }
		}
	});	
	
}

function ValidarControles(){
	$("#referencia_documento").inputmask({
		mask: "999-999-9{1,8}", 
		placeholder: "_",
		clearIncomplete: true,
		rightAlign: true
	});
}

function getSecuencialDocumento(){
	
	$.ajax({
		url:"index.php?controller=TesCuentasPagar&action=getSecuencialDocumento",
		type:"POST",
		dataType:"json",
		data:null
	}).done( function(x){
		var rsConsecutivos = x.data;
		var filaConsecutivos = rsConsecutivos[0];
		$("#id_consecutivos").val(filaConsecutivos.id_consecutivos);
		$("#secuencial_documento").val(filaConsecutivos.secuencial);
		
	})
}

function getTipoDocumento(){
	
	var $ddlTipoDocumento = $("#tipo_documento");
	$.ajax({
		url:"index.php?controller=TesCuentasPagar&action=getTipoDocumento",
		type:"POST",
		dataType:"json",
		data:null
	}).done( function(x){
		var rsTipoDocumento = x.data;
		$ddlTipoDocumento.empty();
		$ddlTipoDocumento.append("<option value=\"0\">--Seleccione--</option>");
		$.each(rsTipoDocumento,function(index,value){
			$ddlTipoDocumento.append("<option value=\""+value.id_tipo_documento+"\">"+value.nombre_tipo_documento+"</option>");
		})				
	})
}

function popOverHtml(){
	//console.log("ingresa fn devuelve string");
	var $objeto = '<form id="frm_lote" class="form-inline" role="form">'
					+'<div class="form-group">' 
					+'<input type="text" class="" id="nombre_lote"  placeholder="Nombre lote" />'
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
	if( $("#id_lote").val() != "" || $("#id_lote").val().length > 0 || $("#id_lote").val() > 0 ){
		$("#btnLote").notify("Lote Generado Revisar",{className:'warn',position:"buttom-left", autoHideDelay: 2000});
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
				$("#btnLote").notify("Lote Generado",{className:'success',position:"buttom-left", autoHideDelay: 2000});
				$("#id_lote").val(x.id_lote);				
			}else{
				$("#btnLote").notify(x.mensaje,{className:'error',position:"buttom-left", autoHideDelay: 2000});
			}
		}
		$("#btnLote").popover('hide');
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
		swal({title:"ERROR",text:"Hemos encontrado un error con el servidor",icon:"error"})<
		$("#btnLote").popover('hide');
	})
}

function loadProveedores(pagina = 1){
	
	var $buscador = $("#mod_buscador_proveedores");
	
	$.ajax({
		url:"index.php?controller=TesCuentasPagar&action=buscaProveedores",
		dataType:"json",
		type:"POST",
		data:{page:pagina,buscador:$buscador.val()}
	}).done(function(x){
		
		var $tabla = $("#mod_tbl_proveedores");
		$tabla.find("tbody").empty();
		$tabla.find("tbody").append(x.filas);
		var $registros = $("#mod_total_proveedores");
		$registros.text(x.cantidadDatos);
		var $divPaginacion = $("#mod_paginacion_proveedores");
		$divPaginacion.html(x.paginacion);		
		
	}).fail(function(xhr,status,eror){
		console.log(xhr.responseText);
	})
	
}

function SelecionarProveedor(element){
	
	var $boton = $(element);
	var $modProveedores = $("#mod_proveedores");
	var $id_proveedor = $boton.val();
	
	$.ajax({
		url:"index.php?controller=TesCuentasPagar&action=SelecionarProveedor",
		type:"POST",
		dataType:"json",
		data:{id_proveedores:$id_proveedor}
	}).done(function(x){
		var rsProveedor = x.data;
		var arrayproveedor = rsProveedor[0];
		$("#id_proveedores").val(arrayproveedor.id_proveedores);
		$("#identificacion_proveedores").val(arrayproveedor.identificacion_proveedores);
		$("#nombre_proveedores").val(arrayproveedor.nombre_proveedores);
		$modProveedores.modal("hide");		
		//$("#id_proveedores").val( rsProveedor[0] )
	}).fail(function(xhr, status, error){
		console.log(xhr.responseText)
	})
	//console.log($buttom.val());
}

function loadImpuestos(){
	
}
