$(document).ready(function(){
	
	//listaArchivosRecaudacion();
	//consultaCargaRecaudaciones();
	cargaEntidadPatronal();
	BuscarDescuentosFormatos();
	
	//para empezar los datatables
	listar_descuentos_pendientes();
	listar_descuentos_errores();
	listar_descuentos_procesados();
	
	init_controles();	
				
})

function init_controles(){
	try {
		
		 $("#nombre_carga_recaudaciones").fileinput({			
		 	showPreview: false,
	        showUpload: false,
	        elErrorContainer: '#errorImagen',
	        allowedFileExtensions: ["txt"],
	        language: 'esp' 
		 });
		
	} catch (e) {
		// TODO: handle exception
		console.log("ERROR AL IMPLEMENTAR PLUGIN DE FILEUPLOAD");
	}
	
	//iniciar eventos de cambio en select de entidad patronal
	$("#id_entidad_patronal").on("change",function(){
		cambio_entidad_patronal();
	});
}

function cargaEntidadPatronal(){
	
	let $ddlEntidadPatronal = $("#id_entidad_patronal");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CargaRecaudaciones&action=cargaEntidadPatronal",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlEntidadPatronal.empty();
		$ddlEntidadPatronal.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlEntidadPatronal.append("<option value= " +value.id_entidad_patronal +" >" + value.nombre_entidad_patronal  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlEntidadPatronal.empty();
	})
	
}

function BuscarDescuentosFormatos(){	
	
	var $ddlDescuentosFormatos	= $("#id_descuentos_formatos");	
	$ddlDescuentosFormatos.empty();
	$ddlDescuentosFormatos.append("<option value='0' >--Seleccione--</option>");
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=RecepcionArchivosRecaudaciones&action=cargaDescuentosFormatos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		if( datos.data != undefined || datos.data != "" )
		{
			$.each(datos.data, function(index, value) {
				$ddlDescuentosFormatos.append("<option value= " +value.id_descuentos_formatos +" >" + value.nombre_descuentos_formatos  + "</option>");	
	  		});
		}
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
	})
}

$("#id_entidad_patronal").on("focus",function(){
	$("#mensaje_id_entidad_patronal").text("").fadeOut("");
})

$("#id_carga_recaudaciones").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})



function consultaCargaRecaudaciones(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CargaRecaudaciones&action=consultaCargaRecaudaciones",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#carga_recaudaciones_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}





$("#btnGenerar").on("click",function(){
	
	let $entidadPatronal 	= $("#id_entidad_patronal");
	let	$anioCargaRecaudaciones 	= $("#anio_carga_recaudaciones");
	let	$mesCargaRecaudaciones 	= $("#mes_carga_recaudaciones");
	let	$formatoCargaRecaudaciones	= $("#formato_carga_recaudaciones");
	let	$nombreCargaRecaudaciones	= $("#nombre_carga_recaudaciones");
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($formatoCargaRecaudaciones.val() == 0 ){
		$formatoCargaRecaudaciones.notify("Seleccione Formato",{ position:"top left", autoHideDelay: 2000});
		return false;
	}
	
	//validacion campo archivo
	var inarchivo = $("#nombre_carga_recaudaciones");
	if( inarchivo[0].files.length == 0){
		inarchivo.notify("Seleccione un archivo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	 
	
	var parametros = new FormData();
	
	parametros.append('id_entidad_patronal',$entidadPatronal.val());
	parametros.append('anio_carga_recaudaciones',$anioCargaRecaudaciones.val());
	parametros.append('mes_carga_recaudaciones',$mesCargaRecaudaciones.val());
	parametros.append('formato_carga_recaudaciones',$formatoCargaRecaudaciones.val());
	parametros.append('nombre_carga_recaudaciones', $('input[type=file]')[0].files[0]); 
	
	
	$.ajax({
		beforeSend:fnBeforeAction('Estamos procesado la informacion'),
		url:"index.php?controller=CargaRecaudaciones&action=GenerarCargaRecaudaciones",
		type:"POST",
		dataType:"json",
		data:parametros,
		
		 contentType: false, 
         processData: false  
       
	}).done(function(x){
		console.log(x)
		if(x.respuesta == 1){
			
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "success",
				 timer: 2000,
				 button: false,
				});		
			document.getElementById("frm_carga_recaudaciones").reset();	
			
			consultaCargaRecaudaciones();
		
		}
		if(x.respuesta == 2){
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
			document.getElementById("frm_carga_recaudaciones").reset();	
			
			consultaCargaRecaudaciones();
		
		}
		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		swal.close();
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal( {
				 title:"Error",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		 	}
	})	
	
	event.preventDefault();
})

function fnBeforeAction(mensaje){

	swal({
        title: "RECAUDACIONES",
        text: mensaje,
        icon: "view/images/ajax-loader.gif",        
      })
}




function verArchivo(linkArchivo){

	let $link = $(linkArchivo);
	let parametros;
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		parametros = {"id_carga_recaudaciones":$link.data("idarchivo")}
		
	}else{ return false; }	
	
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=CargaRecaudaciones&action=descargarArchivo");
    form.setAttribute("target", "_blank");   
    
    for (var i in parametros) {
        if (parametros.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = parametros[i];
            form.appendChild(input);
        }
    }
    
    document.body.appendChild(form); 
    form.submit();    
    document.body.removeChild(form);
}


/***************************************************************** BEGIN CAMBIOS DC  *****************************************************************************/

function listaArchivosRecaudacion(pagina = 1){
	
	var buscador = $("#txt_lista_buscador").val();
	var $divResultados	= $("#div_lista_archivos_leidos");
	$divResultados.html("");
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=CargaRecaudaciones&action=listaArchivosRecaudacion",
		type:"POST",
		dataType:"json",
		data:{page:pagina,busqueda:buscador}
	}).done(function(x){		
		
		$divResultados.html(x.tablaHtml);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

function uploadFileEntidad(){
	
	let $entidadPatronal 	= $("#id_entidad_patronal");
	let	$anioCargaRecaudaciones 	= $("#anio_carga_recaudaciones");
	let	$mesCargaRecaudaciones 		= $("#mes_carga_recaudaciones");
	let	$nombreCargaRecaudaciones	= $("#nombre_carga_recaudaciones");
	let $id_descuentos_formatos		= $("#id_descuentos_formatos");
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( $id_descuentos_formatos.val() == 0 ){
		$id_descuentos_formatos.notify("Seleccione Formato Descuento",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
		
	//validacion campo archivo
	var inarchivo = $("#nombre_carga_recaudaciones");
	if( inarchivo[0].files.length == 0){
		inarchivo.closest('div.file-input').notify("Seleccione un archivo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
		
	var parametros = new FormData();
	
	parametros.append('id_entidad_patronal',$entidadPatronal.val()); 
	parametros.append('id_descuentos_formatos',$id_descuentos_formatos.val());
	parametros.append('anio_carga_recaudaciones',$anioCargaRecaudaciones.val());
	parametros.append('mes_carga_recaudaciones',$mesCargaRecaudaciones.val());
	parametros.append('nombre_carga_recaudaciones', $('input[type=file]')[0].files[0]); 
	
	
	$.ajax({
		beforeSend:fnBeforeAction('Estamos procesado la informacion'),
		url:"index.php?controller=RecepcionArchivosRecaudaciones&action=cargaArchivoRecaudacion",
		type:"POST",
		dataType:"json",
		data:parametros,		
		contentType: false, 
        processData: false  
       
	}).done(function(x){
		swal.close();
		if( x.dataerror != undefined && x.dataerror != "" ){
			
			let modalErrores = $("#mod_archivo_errores");			
			let arrayErrores = x.dataerror;
			let cantidadRegistros		= arrayErrores.length;
			let tblErrores = $("#tbl_archivo_error");
			//tblErrores.find("#catidad_sin_aportes").text(cantidadRegistros);
			tblErrores.find("tbody").empty();
			$.each( arrayErrores , function(index, value) {
				
				//value error.linea.cantidad vienen del array formado en controlador
				
				let repeticiones = isNaN(value.cantidad) ? 0 : value.cantidad;
				
				let $filaLineas = "<tr><td>" + (index + 1) +"</td><td>" +value.linea +"</td><td>" 
					+value.error +"</td><td>" + repeticiones +"</td></tr>";
				tblErrores.find("tbody").append($filaLineas);	
	  		});
			modalErrores.find('.modal-title').text(x.cabecera);
			modalErrores.modal("show");
			
			setTimeout(function(){ 
				if ( ! $.fn.DataTable.isDataTable( "#tbl_archivo_error" ) ) {
					$('#tbl_archivo_error').DataTable({
					"scrollX": true,
					"scrollY": 200,
					"ordering":false,
					"searching":false,
					"info":false
					})
				}
			},1000);		
			
		
		}
		
		if( x.respuesta != undefined && x.respuesta != ""){
			
			swal( {
				 title:"CARGA ARCHIVO",
				 dangerMode: false,
				 text: "Archivo cargado al servidor",
				 icon: "success"
				});
			
			listaArchivosRecaudacion();
			cleanInputs();			
		}
		//console.log(x);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		swal.close();
		console.log(err)
		if( err != undefined )
		{
			var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal( {
				 title:"Error",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		 	}
		}else
		{
			swal( {
				 title:"Error",
				 dangerMode: true,
				 text: "comunicacion con el Servidor no completada",
				 icon: "error"
				})
		}
		
	})	
	
	event.preventDefault();
	
}

function cleanInputs(){
	
	$("#id_entidad_patronal").val(0);
	$("#formato_carga_recaudaciones").val(0);
	$("#nombre_carga_recaudaciones").val('');
}

function fn_formatoTablaErrores(){
	console.log("inicializar tabla");
	/*$('#tbl_archivo_error').DataTable({
		"scrollX": true,
		"scrollY": 200,
		"ordering":false,
		"searching":false,
		"info":false
		});*/
	$('#tbl_archivo_error').DataTable({
		"scrollX": true,
		"scrollY": 200,
		});
	$('.dataTables_length').addClass('bs-select');
	
}

/*****************************************************************END CAMBIOS DC  *****************************************************************************/


/*** funciones para plugin de datattables ***/
var dt_view1 = dt_view1 || {};

dt_view1.dt_tabla_pendientes = null;
dt_view1.nombre_tabla_pendientes = 'tbl_descuentos_pendientes';
dt_view1.dt_tabla_procesados = null;
dt_view1.nombre_tabla_procesados = 'tbl_descuentos_procesados';
dt_view1.dt_tabla_error = null;
dt_view1.nombre_tabla_error = 'tbl_descuentos_errores';

//setear valores json elementos de la vista
var view	= view || {};
view.id_entidad_patronal	= $("#id_entidad_patronal");
view.id_descuentos_formatos = $("#id_descuentos_formatos");

dt_view1.params	= function(){ 
	var extenddatapost = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};
	return extenddatapost;
};

var idioma_espanol = {
	    "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla &#128543; ",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
}

var listar_descuentos_pendientes = function(){
	
	var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};
		
	dt_view1.dt_tabla_pendientes	=  $('#'+dt_view1.nombre_tabla_pendientes).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=RecepcionArchivosRecaudaciones&action=dtMostrarDescuentosPendientes',
	        'data': function ( d ) {
	            return $.extend( {}, d, dt_view1.params() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 1, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'numfila', orderable: false },
	    		{ data: 'nombre_entidad'},
	    		{ data: 'nombre_usuarios' },
	    		{ data: 'anio_descuentos', orderable: false },
	    		{ data: 'mes_descuentos', orderable: false },
	    		{ data: 'nombre_formato' },
	    		{ data: 'nombre_archivo'},
	    		{ data: 'fecha_descuentos' },
	    		{ data: 'fecha_contable' },
	    		{ data: 'opciones', orderable: false }
	    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,3,4,9 ] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        'language':idioma_espanol
	 });	
	
} 

var listar_descuentos_procesados = function(){
	
	var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};
	
	dt_view1.dt_tabla_procesados	=  $('#'+dt_view1.nombre_tabla_procesados).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=RecepcionArchivosRecaudaciones&action=dtMostrarDescuentosProcesados',
	        'data': function ( d ) {
	            return $.extend( {}, d, dt_view1.params() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'order': [[ 1, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'numfila', orderable: false },
	    		{ data: 'nombre_entidad'},
	    		{ data: 'nombre_usuarios' },
	    		{ data: 'anio_descuentos', orderable: false },
	    		{ data: 'mes_descuentos', orderable: false },
	    		{ data: 'nombre_formato' },
	    		{ data: 'nombre_archivo'},
	    		{ data: 'fecha_descuentos' },
	    		{ data: 'fecha_contable' },
	    		{ data: 'opciones', orderable: false }
	    		
	    ],
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,3,4,9 ] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        'language':idioma_espanol
	 });	
	
} 

var listar_descuentos_errores = function(){
	
	var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};
	
	dt_view1.dt_tabla_error	=  $('#'+dt_view1.nombre_tabla_error).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=RecepcionArchivosRecaudaciones&action=dtMostrarDescuentosError',
	        'data': function ( d ) {
	            return $.extend( {}, d, dt_view1.params() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 1, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'numfila', orderable: false },
	    		{ data: 'nombre_entidad'},
	    		{ data: 'nombre_usuarios' },
	    		{ data: 'anio_descuentos', orderable: false },
	    		{ data: 'mes_descuentos', orderable: false },
	    		{ data: 'nombre_formato' },
	    		{ data: 'nombre_archivo'},
	    		{ data: 'fecha_descuentos' },
	    		{ data: 'fecha_contable' },
	    		{ data: 'opciones', orderable: false }
	    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,3,4,9 ] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        'language':idioma_espanol
	 });	
	
} 

var cambio_entidad_patronal	= function(){
	
	//para empezar los datatables
	dt_view1.dt_tabla_pendientes.ajax.reload();
	dt_view1.dt_tabla_procesados.ajax.reload();
	dt_view1.dt_tabla_error.ajax.reload();
}

var mostrar_detalle	= function(a){
	
	let $link = $(a);	
		
	var id_cabeza_descuentos	= $link.data("id_descuentos_cabeza");
		
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
		
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos
	}
		
	var form = document.createElement("form");
	form.setAttribute("id", "frmVerArchivoTxt");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecepcionArchivosRecaudaciones&action=mostrarArchivoTxt");
    form.setAttribute("target", "_blank");   
    
    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = params[i];
            form.appendChild(input);
        }
    }
        
    document.body.appendChild(form); 
    form.submit();    
    document.body.removeChild(form);
    
}

