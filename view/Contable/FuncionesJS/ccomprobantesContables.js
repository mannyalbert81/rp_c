// AQUI VA A CONTENER FUNCIONES AUXILIARES PARA NUEVA INTERFAZ DE COMPROBNATES

$(document).ready(function(){ 	 
	
});

function getTipoComprobantes(){
	
	var ddlTipoComprobante = $("#tipo_compranbante_2"); ddlTipoComprobante.empty();
	
	$.ajax({
		url:"index.php?controller=ComprobanteContable&action=getTipoComprobantes",
		data:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		
		if(x.respuesta != undefined && x.respuesta == 1 ){
			
			ddlTipoComprobante.append('<option value="0">--Seleccione--</option>');
			$.each(x.data,function(index,value){
				ddlTipoComprobante.append('<option value="'+value.id_tipo_comprobantes+'">'+value.nombre_tipo_comprobantes+'</option>');
			})
		} 
		
	}).fail(function(xhr,status,error){
		
	})
}

function catchTipoComprobante(elemento){
	
	var ddlTipoComprobante = $(elemento);
	var nombre_tipo_comprobante = ddlTipoComprobante.find('option:selected').text();
	var id_tipo_comprobante = ddlTipoComprobante.val();
	
	 $.ajax({
         url: 'index.php?controller=ComprobanteContable&action=getNumeroComprobante',
         type: 'POST',
         data: {id_tipo_comprobantes:id_tipo_comprobante},
         dataType:'json',
	 }).done(function(x){
		 
		 if( x.respuesta != undefined && x.respuesta == 1 ){
			 
			 var arrayConsecutivo = x.data;
			 var nombre_comprobante = arrayConsecutivo[0].nombre_tipo_comprobantes;
			 $("#title_comprobantes").text( nombre_comprobante );
			 $("#con_numero_comprobantes").val(arrayConsecutivo[0].numero_consecutivos);
			 
			 validaTipoComprobante(nombre_comprobante);
		 }
		 
	 }).fail(function(xhr,status,error){
		 console.log("ERROR AL CONSULTAR CONSECUTIVO COMPROBANTE")
	 })
	 
	
	
}

function validaTipoComprobante(nombreComprobante){
	
	var link_proveedor = $("#link_proveedor");
	var link_documento = $("#link_documentos");
	
	if( nombreComprobante == "CONTABLE" ){
		
		link_proveedor.addClass( "btn disabled" );
		link_documento.addClass( "btn disabled" );
	}
}