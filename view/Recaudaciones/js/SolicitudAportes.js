$(document).ready(function(){
	
	/*if ( ! $.fn.DataTable.isDataTable( "#tbl_solicitudes_aportes" ) ) {
		$('#tbl_solicitudes_aportes').DataTable({
		"scrollX": true,
		"scrollY": 200,
		"ordering":false,
		"searching":false,
		"info":false,
		"paging":false
		})
	}*/
	
	loadSolicitudAportes();
		
})

function loadSolicitudAportes(page = 1){
	
	var parametros={
			pagina:page,
			buscador:""
	}
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SolicitudAportes&action=LoadSolicitudesAportes",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){		
		
		console.log(x);
		
		if( x.respuesta != undefined && x.respuesta != "" ){
						
			let htmlFilas = x.dataFilas;
			let tblDatos = $("#tbl_solicitudes_aportes");
			//tblErrores.find("#catidad_sin_aportes").text(cantidadRegistros);
			tblDatos.find("tbody").empty();
			tblDatos.find("tbody").append(htmlFilas);
			
			let paginacion = $("#tbl_solicitudes_aportes_paginacion");
			paginacion.append(x.paginacion);
			
		}		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		
	})
	
	
}

function AprobarRecaudaciones(link){
	var elemento = $(link);
	
	if(parseInt(elemento.data("identificador"))  < 1 ){
		
		return false;		
	}

	parametros = {"id_solicitud":elemento.data("identificador"), departamento:"recaudaciones"}
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=SolicitudAportes&action=AprobarSolicitud",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){		
		
		console.log(x);
		if( x.respuesta != undefined ){
			
			if( x.respuesta == 1){
				
				swal({
					title:"Aprobacion Solicitud Aportes",
					text:"Solicitud Aprobada",
					icon:"success"
				})
				
				loadSolicitudAportes();
			}
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	})
		
}

function AprobarGerencia(link){
	var elemento = $(link);
	
	if(parseInt(elemento.data("identificador"))  < 1 ){
		
		return false;		
	}

	parametros = {"id_solicitud":elemento.data("identificador"), departamento:"gerencia"}
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=SolicitudAportes&action=AprobarSolicitud",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){		
		
		console.log(x);
		if( x.respuesta != undefined ){
			
			if( x.respuesta == 1){
				
				swal({
					title:"Aprobacion Solicitud Aportes",
					text:"Solicitud Aprobada",
					icon:"success"
				})
				
				loadSolicitudAportes();
			}
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	})
		
}

function AnularRecaudaciones(link){
	var elemento = $(link);
	
	if(parseInt(elemento.data("identificador"))  < 1 ){
		
		return false;		
	}

	parametros = {"id_solicitud":elemento.data("identificador"), departamento:"recaudaciones"}
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=SolicitudAportes&action=NegarSolicitud",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){		
		
		console.log(x);
		if( x.respuesta != undefined ){
			
			if( x.respuesta == 1){
				
				swal({
					title:"Anulacion Solicitud Aportes",
					text:"Solicitud Anulada",
					icon:"error"
				})
				
				loadSolicitudAportes();
			}
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	})
		
}

function AnularGerencia(link){
	var elemento = $(link);
	
	if(parseInt(elemento.data("identificador"))  < 1 ){
		
		return false;		
	}

	parametros = {"id_solicitud":elemento.data("identificador"), departamento:"gerencia"}
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=SolicitudAportes&action=NegarSolicitud",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){		
		
		console.log(x);
		if( x.respuesta != undefined ){
			
			if( x.respuesta == 1){
				
				swal({
					title:"Anulacion Solicitud Aportes",
					text:"Solicitud Anulada",
					icon:"error"
				})
				
				loadSolicitudAportes();
			}
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	})
		
}


$("#id_carga_recaudaciones").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


function fnBeforeAction(mensaje){

	swal({
        title: "RECAUDACIONES",
        text: mensaje,
        icon: "view/images/ajax-loader.gif",        
      })
}
