/** CAMBIOS PARA NUEVOS CAMBIOS **/

$GLOBAL_id_descuentos_cabeza	= 0;
$GLOBAL_tipo_descuento		= 0;

var view 	= view || {};
view.anio_recaudacion	= $("#anio_recaudacion");
view.mes_recaudacion	= $("#mes_recaudacion");
view.id_entidad_patronal	= $("#id_entidad_patronal");
view.id_descuentos_formatos	= $("#id_descuentos_formatos");
view.formato_descuento	= $("#formato_recaudacion");

var dt_view1 = dt_view1 || {};

dt_view1.dt_tabla = null;
dt_view1.nombre_tabla = 'tbl_listado_recaudaciones';
dt_view1.params	= dt_view1.params || {};
dt_view1.params = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()} 

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

$(document).ready(function(){
	
	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
	
	init_controles();
	
	listar_entidad_patronal();
	
	listar_descuentos();
	
});

var init_controles	= function(){
	
	$('body').on('change','#'+view.mes_recaudacion.attr("id"),function(){
		valida_cambio_mes();
	})
	
	$('body').on('change','#'+view.id_entidad_patronal.attr("id"),function(){
		listar_descuentos_formatos();
		listar_descuentos();
	})
	
	$('body').on('change','#'+view.id_descuentos_formatos.attr("id"),function(){
		listar_descuentos();
	})
	
}

var valida_cambio_mes	= function(){
		
	if( view.mes_recaudacion.val() == 0 ){
		view.id_entidad_patronal.attr("disabled","true"); view.id_entidad_patronal.val(0) }else{ view.id_entidad_patronal.removeAttr("disabled")}
	
}

var listar_entidad_patronal = function (){
				
	view.id_entidad_patronal.empty().append('<option value="0">--Seleccione--</option>');
	
	fetch('index.php?controller=RecaudacionGeneracionArchivo&action=cargaEntidadPatronal')
	  .then(function(response) {
	    //console.log(response);
	    return response.json();
	  })
	  .then(function(x) {
	    
	    var rsData    = x.data;	    
	    $.each(rsData,function(index,value){
	    	view.id_entidad_patronal.append('<option value="'+value.id_entidad_patronal+'">'+value.nombre_entidad_patronal+'</option>')
	    }); 
	        
	    
	  }).catch(()=>console.log('Error en la carga de Entidad Patronal'));
			
}

var listar_descuentos_formatos = function(){
		
		var ddldescuentos = view.id_descuentos_formatos;
		ddldescuentos.attr("disabled",false);
		ddldescuentos.empty().append('<option value="0">--Seleccione--</option>');
		
		var params = {id_entidad_patronal:view.id_entidad_patronal.val()};
		
		$.ajax({
			url:'index.php?controller=RecaudacionGeneracionArchivo&action=cargaFormatoDescuentos',
			type:"POST",
			dataType:"json",
			data: params			
		}).done(function(x){
			var rsData    = x.data;	    
		    $.each(rsData,function(index,value){
		    	ddldescuentos.append('<option value="'+value.id_descuentos_formatos+'">'+value.nombre_descuentos_formatos+'</option>')
		    });
		}).fail(function(xhr,status,error){
			console.log(xhr.responseText);
		});
	
}

var listar_descuentos = function(){
		
	var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};
		
	dt_view1.dt_tabla	=  $('#'+dt_view1.nombre_tabla).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=RecaudacionGeneracionArchivo&action=DataTableListarDescuentos',
	        'data': function ( d ) {
	            return $.extend( {}, d, dataSend );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'order': [[ 1, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'numfila', orderable: false },
	    		{ data: 'fecha_descuentos'},
	    		{ data: 'nombre_entidad_patronal' },
	    		{ data: 'tipo_descuentos', orderable: false },
	    		{ data: 'cantidad_descuentos', orderable: false },
	    		{ data: 'anio_descuentos' },
	    		{ data: 'mes_descuentos'},
	    		{ data: 'usuario_usuarios' },
	    		{ data: 'modificado' },
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

function fnBeforeAction(mensaje){
	/*funcion que se ejecuta antes de realizar peticion ajax*/
	swal({
        title: "RECAUDACIONES",
        text: mensaje,
        icon: "view/images/ajax-loader.gif",        
      })
}


$("#btnGenerar").on("click",function(){
	
	var $formulario = $("#frm_recaudacion");
	if ( $formulario.data('locked') && $formulario.data('locked') != undefined ){
		console.log("formulario bloaqueado"); return false;
	}
	
	let $entidadPatronal 	= $("#id_entidad_patronal"),
		$anioRecaudacion 	= $("#anio_recaudacion"),
		$mesRecaudacion 	= $("#mes_recaudacion"),
		$DescuentosFormatos	= $("#id_descuentos_formatos"),
		$formatoRecaudacion	= $("#formato_recaudacion");
	
	if($mesRecaudacion.val() == 0 ){
		$mesRecaudacion.notify("Seleccione Periodo A generar",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($DescuentosFormatos.val() == 0 ){
		$DescuentosFormatos.notify("Seleccione Formato Descuento",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($formatoRecaudacion.val() == 0 ){
		$formatoRecaudacion.notify("Seleccione formato aportacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var parametros ={id_entidad_patronal:$entidadPatronal.val(),
			id_descuentos_formatos:$DescuentosFormatos.val(),
			anio_recaudacion:$anioRecaudacion.val(),
			mes_recaudacion:$mesRecaudacion.val(),
			formato_recaudacion:$formatoRecaudacion.val(),
			}   
	
	$.ajax({
		beforeSend:function(){ $formulario.data('locked', true); fnBeforeAction('Estamos procesado la informacion') },
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=GenerarRecaudacion",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr,status){ $formulario.data('locked', false); }
	}).done(function(x){
		console.log(x)
		if( x.estatus != undefined && x.estatus !="" ){
			
			swal( {
				 title:"ARCHIVO",
				 text: "Datos Generados, Revisar datos en el listado de archivos Generados ",
				 icon: "success",
				 timer: 2000,
				 button: true,
				});	
			
			//implementar si es necesario el que devuelva el ultimo insertado 			
			let id_archivo = ( x.id_archivo != undefined && x.id_archivo > 0 ) ? x.id_archivo : 0;
			$GLOBAL_id_archivo_recaudaciones=id_archivo;
			//buscarDatosInsertados(1); poner un mensaje de revisar archivos creados
			//buscarDatos();
			listar_descuentos();
		}
		
		/** code below is to show error from parcticipes without valor aportes **/
		if( x.mensajeAportes != undefined &&  x.mensajeAportes != "" ){
			
			swal.close();
			let modalAportes = $("#mod_participes_sin_aportes");			
			let arrayAportesIncompletos = x.dataAportes;
			let cantidadRegistros		= arrayAportesIncompletos.length;
			let tblParticipesAportes = $("#tbl_participes_sin_aportacion");
			tblParticipesAportes.find("#catidad_sin_aportes").text(cantidadRegistros);
			tblParticipesAportes.find("tbody").html("");
			$.each( arrayAportesIncompletos , function(index, value) {
				
				let $filaAportes = "<tr><td>" + (index + 1) +"</td><td>" +value.cedula_participes +"</td><td>" 
					+value.nombre_participes +"</td><td>" +value.apellido_participes +"</td></tr>";
				tblParticipesAportes.find("tbody").append($filaAportes);	
	  		});			
			modalAportes.modal("show");
			
			//console.log(arrayAportesIncompletos);
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

function verDatosDescuentos(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	$GLOBAL_id_descuentos_cabeza	= id_cabeza_descuentos;
	$GLOBAL_tipo_descuento		= tipo_descuento;
	
	CargarDatosDescuentos(1);
		
}

function CargarDatosDescuentos(page){
	
	var id_descuentos_cabeza = $GLOBAL_id_descuentos_cabeza;
	var tipo_descuento		= $GLOBAL_tipo_descuento;
	var busqueda			= $("#mod_txtBuscarDatos").val();
		
	var params = {
			"id_descuentos_cabeza":id_descuentos_cabeza,
			"tipo_descuento":tipo_descuento,
			"page":page,
			"busqueda":busqueda
	}
	
	var vtnmodal = $("#mod_datos_archivo");
	var divmodal = $("#mod_div_datos_recaudacion");
	var tblmodal = $("#tbl_archivo_recaudaciones_insertados");
	var pagmodal = $("#mod_paginacion_datos_descuentos");
		
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=CargarDatosDescuentos",
		type:"POST",
		dataType:"json",
		data:params,
		complete:function(xhr,status){ }
	}).done(function(x){
		
		if( x.tablaHtml != undefined && x.tablaHtml != "" ){
			tblmodal.empty();
			tblmodal.append( x.tablaHtml );
			pagmodal.html("");
			pagmodal.html( x.paginacion );
			vtnmodal.modal("show");
		}
						
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
	})
}

function genArchivoEntidad(linkArchivo){
	
	let $link = $(linkArchivo);	
		
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
		
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=genArchivoEntidad");
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

function genArchivoDetallado(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=genArchivoDetallado");
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

var editar_descuentos	= function(a){
	
	let $link = $(a);
	
	var id_detalle		= $link.data("iddescuentos");
	var tipo_descuento	= $link.data("tipo");
	
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=obtenerDetalleDescuentos",
		type:"POST",
		dataType:"json",
		data:{"id_descuentos_detalle":id_detalle, "tipo_descuento": tipo_descuento }		
	}).done(function(x){
		
		if( x.data != undefined && x.data != null ){
			
			var rsdata = x.data[0];
			
			let $modal	= $("#mod_recaudacion"),
			$tituloModal	= $modal.find('h4.modal-title');
		
			$tituloModal.text('VALORES APORTES A CAMBIAR');
			
			//valores hidden	
			$modal.find('#mod_id_descuentos_detalle').val( rsdata.id_detalle);
			$modal.find('#mod_tipo_descuentos').val( tipo_descuento );
			//valores vista usuarios
			$modal.find('#mod_cedula_participes').val( rsdata.cedula_participes);
			$modal.find('#mod_nombres_participes').val( rsdata.nombre_participes);
			$modal.find('#mod_apellidos_participes').val( rsdata.apellido_participes);			
			$modal.find('#mod_valor_sistema').val( rsdata.valor_descuento);
			$modal.find('#mod_valor_edit').val( rsdata.valor_descuento1 );
			
			
			$modal.modal("show");			
			
		}
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err);
	});
}


$("#btnEditRecaudacion").on("click",function(){
	
	let $miboton = $(this);
		$miboton.attr("disabled",true);
	let $modal = $("#mod_recaudacion");
	
	let $iddescuento = $modal.find('#mod_id_descuentos_detalle'),
		$tipo_descuento	= $modal.find('#mod_tipo_descuentos'),
		$valorNuevo = $modal.find('#mod_valor_edit');	
	
	if( isNaN( $valorNuevo.val() ) ){
		$valorNuevo.notify("Ingrese Cantidad Valida",{ position:"buttom left", autoHideDelay: 2000});
		$miboton.attr("disabled",false);
		return false;
	}else{
		if( $valorNuevo.val() < 0 ){
			$valorNuevo.notify("Revisar una cantidad Valida ",{ position:"buttom left", autoHideDelay: 2000});
			$miboton.attr("disabled",false);
			return false;
		}
	}
		
	var parametros = { "id_descuentos_detalle": $iddescuento.val(), "valor_descuentos": $valorNuevo.val(), "tipo_descuentos":$tipo_descuento.val()}
	
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=editAporte",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr){ $miboton.attr("disabled",false); }
	}).done(function(x){
		
		if( x.estatus != undefined && x.estatus != "" ){
			
			swal( {
				 title:"ACTUALIZACION VALOR",
				 text: x.mensaje,
				 icon: "info"
				});
			$modal.modal('hide');
			CargarDatosDescuentos(1);
			
		}
		
				
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
	
})

var generarReporte = function(a){
	
	let $link = $(a);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
	form.setAttribute("id", "frm_reporte_descuentos");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=generarReporteDescuentos");
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


var imprimir_reporte_descuentos = function (a){
	
	let $link = $(a);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
		
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
	form.setAttribute("id", "frm_reporte_descuentos");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=imprimirReporteDescuentos");
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

