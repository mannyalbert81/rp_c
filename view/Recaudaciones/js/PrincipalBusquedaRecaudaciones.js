/**VARIABLES DE DOCUMENTO**/
var dt_view1 = dt_view1 || {};

dt_view1.dt_ingreso_bancos = null;
dt_view1.nombre_ingreso_bancos = 'tbl_ingreso_bancos';

//setear valores json elementos de la vista
var view	= view || {};
view.id_entidad_patronal	= $("#id_entidad_patronal");
view.id_cabeza_descuentos = 0;
view.anio_busqueda	= $("#anio_ingreso_bancos_cabeza");
view.mes_busqueda	= $("#mes_ingreso_bancos_cabeza");

/*** funciones para plugin de datattables ***/
dt_view1.params	= function(){ 
	var extenddatapost = { id_entidad_patronal:view.id_entidad_patronal.val(),
			anio_ingreso_bancos_cabeza:view.anio_busqueda.val(),
			mes_ingreso_bancos_cabeza:view.mes_busqueda.val()
			};
	return extenddatapost;
};

$(document).ready(function(){
	
	cargaEntidadPatronal();
	
	init_controles();
	
	listar_ingreso_bancos();	
		
})

var init_controles	= function(){
	
	//iniciar eventos de cambio en select de entidad patronal
	$(".cls_interaccion_elementos").on("change",function(){
		interacion_elementos();
	});
}

var interacion_elementos	= function(){
	
	//para empezar los datatables
	dt_view1.dt_ingreso_bancos.ajax.reload();
	
	//para validar que solo sea cuando esten llenos los elementos
	if( view.id_entidad_patronal.val() != 0 && view.anio_busqueda.val().length >= 4 && view.mes_busqueda.val() != 0 )
	{
		validarZeroFilasdt();
	}
	
}

var roundNumber	= function (value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function cargaEntidadPatronal(){
	
	let $ddlEntidadPatronal = view.id_entidad_patronal;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=PrincipalBusquedasRecaudaciones&action=cargaEntidadPatronal",
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





var listar_ingreso_bancos = function(){
	
	dt_view1.dt_ingreso_bancos	=  $('#'+dt_view1.nombre_ingreso_bancos).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=PrincipalBusquedasRecaudaciones&action=dtIngresoBancos',
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
	    		{ data: 'mes_ingreso_bancos_cabeza', orderable: false },
	    		{ data: 'valor_ingreso_bancos_cabeza' },
	    		{ data: 'diario_ingreso_bancos_cabeza'},
	    		{ data: 'opciones', orderable: false }	    		
	    ],
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'columnDefs': [
	        /*{className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,3 ] }*/
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

/***
 * @author dc
 * @fecha 2020-12-17
 */
var validarZeroFilasdt	= function(){
	
	dt_view1.dt_ingreso_bancos.on('draw', function () {                  
	    if ( dt_view1.dt_ingreso_bancos.settings()[0]._iRecordsTotal === 0){
	    	/** Graficamos el boton de agregar nuevo **/
	    	$("#pnl_nuevo").removeClass('hide');
	    }else{
	    	/** Borramos el boton de agregar nuevo **/
	    	$("#pnl_nuevo").addClass('hide');
	    }	     
	});
}

var mostrar_detalle_modal = function(a){
	
	
	var element = $(a);
	
	let $link = $(a);	
	
	var id_ingreso_bancos_cabeza	= $link.data("id_ingreso_bancos_cabeza");
		
	if( id_ingreso_bancos_cabeza <= 0 || id_ingreso_bancos_cabeza == "" || id_ingreso_bancos_cabeza == undefined ){
		return false;
	}	
		
	view.id_ingreso_bancos_cabeza = id_ingreso_bancos_cabeza;
	
	if( element.length )
	{			
		$("#hdnid_ingreso_bancos_cabeza").val(id_ingreso_bancos_cabeza);
		var modaledit = $("#mod_mostrar_detalle");	
		modaledit.modal();
		listar_detalle_modal();
		
	}	
	
}

/** ============================================ OPERAR MODALES ===============================**/
$(document).on('hidden.bs.modal', function (event) {
    if ($('.modal:visible').length) {
      $('body').addClass('modal-open');
    }
});

/**============================================= MODAL ========================================**/

var viewmodal = viewmodal || {};

viewmodal.pnl_modal = $("#mod_ingreso_bancos");

viewmodal.id_diario_ib	= $("#id_diario_ib");
viewmodal.id_bancos_ib	= $("#id_bancos_ib");
viewmodal.anio_cabeza_ib	= $("#anio_cabeza_ib");
viewmodal.mes_cabeza_ib		= $("#mes_cabeza_ib");
viewmodal.rdtipo_bancos		= $("#rdtipo_bancos");
viewmodal.rdtipo_sconcepto	= $("#rdtipo_sconcepto");
viewmodal.fecha_deposito_ib	= $("#fecha_deposito_ib");
viewmodal.fecha_contable_ib	= $("#fecha_contable_ib");
viewmodal.referencia_ib	= $("#referencia_ib");
viewmodal.valor_ib		= $("#valor_ib");
viewmodal.rd_indebidos	= $("#rd_indebidos");
viewmodal.rd_creditos	= $("#rd_creditos");
viewmodal.rd_aportes	= $("#rd_aportes");
viewmodal.razon_ib		= $("#razon_ib");
viewmodal.id_tipo_creditos_ib	= $("#id_tipo_creditos_ib");
viewmodal.valor_desglose_ib		= $("#valor_desglose_ib");
viewmodal.valor_total_ib		= $("#valor_total_ib");
viewmodal.valor_diferencia_ib	= $("#valor_diferencia_ib");
viewmodal.descripcion_ib		= $("#descripcion_ib");
viewmodal.tbldetalle	= $("#detalle_pagos_ingresados");
viewmodal.dtdetalle	= null;

/*** Para guardar valores de tabla en linea ***/
viewmodal.data_table	= [];

/*** ===================== FUNCIONES ================= **/
viewmodal.fn_init_controles	= function(){
	
	/*** ====== inicializar fechas ========= */	
	viewmodal.fecha_deposito_ib.datepicker('destroy');
	viewmodal.fecha_deposito_ib.attr('readonly',true);
	viewmodal.fecha_deposito_ib.datepicker({
		autoclose: true,
	    clearBtn: true,
	    format:'yyyy/mm/dd',
	    beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }
	});
	
	viewmodal.fecha_contable_ib.datepicker('destroy');
	viewmodal.fecha_contable_ib.attr('readonly',true);
	viewmodal.fecha_contable_ib.datepicker({
		autoclose: true,
	    clearBtn: true,
	    format:'yyyy/mm/dd',
	    beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }
	});
	
	/*** para valores mes-anio pago - pagina principal **/
	viewmodal.anio_cabeza_ib.attr('readonly',true);
	viewmodal.anio_cabeza_ib.val( view.anio_busqueda.val() );
	viewmodal.mes_cabeza_ib.attr('disabled',true);
	viewmodal.mes_cabeza_ib.val( view.mes_busqueda.val() );
	
	/**INICIAR DATATABLE EN MODAL**/
	viewmodal.fn_mostrar_detalle_pagos_ingresados();
	
	/** PARA INICIAR VALORES DE TIPO INGRESO **/
	viewmodal.rdtipo_bancos.attr('disabled',true);	
	viewmodal.rdtipo_sconcepto.attr('disabled',true);
	viewmodal.rdtipo_bancos.prop('checked',true);
	
	viewmodal.id_diario_ib.attr('readonly',true); /** modal diario es solo readonly **/
	
};

viewmodal.fn_carga_bancos	= function(){
	
	let $bancos = viewmodal.id_bancos_ib;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=PrincipalBusquedasRecaudaciones&action=cargaBancosLocales",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$bancos.empty();
		$bancos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$bancos.append("<option value= " +value.id_bancos +" >" + value.nombre_bancos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$bancos.empty();
	})
	
}

viewmodal.fn_tipo_creditos	= function(){
	
	let $tcreditos = viewmodal.id_tipo_creditos_ib;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=PrincipalBusquedasRecaudaciones&action=cargaTiposCreditos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tcreditos.empty();
		$tcreditos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$tcreditos.append("<option value= " +value.id_tipo_creditos +" >" + value.nombre_tipo_creditos  + "</option>");	
  		}); 
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tcreditos.empty();
	})
	
}

viewmodal.fn_mostrar_detalle_pagos_ingresados	= function(){
	
	viewmodal.dtdetalle	= viewmodal.tbldetalle.DataTable({
		destroy:true,
		dom:'<"top"l>rt<"bottom"ip><"clear">',
		data:viewmodal.data_table,
		columns:[{data:"index"},
		{data:"nombre_identificador"},
		{data:"razon"},
		{data:"valor"}],
		/*columnDefs: [{sortable: false, targets: [0,1,2,3] }],*/
		/*columnDefs:{ targets: 'no-sort', orderable: false },*/
		/*order:[]*/
	});	
	
}

//viewmodal.dtdetalle.clear();
//viewmodal.dtdetalle.draw();
//viewmodal.dtdetalle.rows.add(viewmodal.data_table).draw();

$("input[name='tipo_pago']").on('change',function(){

	var element = $(this);	
	if( element.val() == 'creditos' && element.is(':checked') )
	{
		viewmodal.fn_tipo_creditos();
		viewmodal.id_tipo_creditos_ib.attr('disabled',false);
	}else
	{
		viewmodal.id_tipo_creditos_ib.attr('disabled',true);
		viewmodal.id_tipo_creditos_ib.val(0);
	}	
	
});

viewmodal.fn_actualizar_valor_pago	= function(a){
	let valor_actual	= isNaN(  parseFloat(viewmodal.valor_ib.val()) ) ? 0 : parseFloat(viewmodal.valor_ib.val());
	let valor_actualizar = parseFloat(viewmodal.valor_ib.val()) + parseFloat(a);
	viewmodal.valor_ib.val( valor_actualizar );	
}


/*** ======================== END FUNCIONES ====================== **/

var fn_agregar_detalle_ingreso_banco	= function(){
	
	let nombre_identificacion	= $("input[name='tipo_pago']:checked").val();
	let identificacion	= '';
	
	if( tipo_pago = 'creditos'){
		identificacion	= viewmodal.id_tipo_creditos_ib.val();
		nombre_identificacion	= viewmodal.id_tipo_creditos_ib.find('option:selected').text();
	}else if( tipo_pago = 'aportes' ){
		identificacion	= -1;
	}else{
		identificacion	= -2;
	}
	
	let razon 	= viewmodal.razon_ib.val();
	let valor	= viewmodal.valor_desglose_ib.val();
	
	viewmodal.data_table.push({
		comprobante_contable: "0",
		identificador: identificacion,
		index: viewmodal.data_table.length,
		nombre_contable: "-",
		nombre_identificador: nombre_identificacion,
		razon: razon,
		valor: valor
	});
	
	viewmodal.dtdetalle.clear();
	viewmodal.dtdetalle.draw();
	viewmodal.dtdetalle.rows.add(viewmodal.data_table).draw();
	
	/** actualizar valor total detalle **/
	fn_sumar_totales_detalle_pago();
	
	/** para limpiar controles al ingresar detalle **/
	$.each( $("input[name='tipo_pago']:checked"), function(index,radio){ $(this).prop('checked',false); } );
	viewmodal.id_tipo_creditos_ib.val(0);
	viewmodal.razon_ib.val('');
	viewmodal.valor_desglose_ib.val('');
}

var fn_sumar_totales_detalle_pago	= function(){
	
	let sumaTotal = 0.00;
	$.each( viewmodal.data_table, function(index,object){
		sumaTotal    += isNaN(object.valor) ? 0 : parseFloat(object.valor);
	})
	viewmodal.valor_total_ib.val( roundNumber(sumaTotal,2) );
}

var fn_validar_detalle_pagos	= function(){
	if( Math.abs(viewmodal.valor_ib.val() - viewmodal.valor_total_ib.val() ) > 0 ){		
		viewmodal.valor_diferencia_ib.val( Math.abs(viewmodal.valor_ib.val() - viewmodal.valor_total_ib.val()) );
		viewmodal.valor_diferencia_ib.notify("Existe Diferencia de valores",{ position:"buttom left", autoHideDelay: 2000});
	}	
}

var fn_iniciar_modal	= function(){
	
	viewmodal.pnl_modal.modal();	
	viewmodal.fn_init_controles();
	viewmodal.fn_carga_bancos();
		
}

var fn_insertar_ingreso_bancos	= function(){
	
	let validador = true;
	
	/** 1ro validacion de elementos**/
	if( viewmodal.id_bancos_ib.val() == 0 ){
		viewmodal.id_bancos_ib.closest("tr").notify("Selecione Banco",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	if( viewmodal.fecha_deposito_ib.val() == '' ){
		viewmodal.fecha_deposito_ib.closest("tr").notify("Ingrese Fecha deposito",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	if( viewmodal.fecha_contable_ib.val() == '' ){
		viewmodal.fecha_contable_ib.closest("tr").notify("Ingrese Fecha deposito",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	if( viewmodal.referencia_ib.val() == '' ){
		viewmodal.referencia_ib.closest("tr").notify("Ingrese Referencia",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	if( viewmodal.valor_ib.val() == '' ){
		viewmodal.valor_ib.closest("tr").notify("Ingrese Valor",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	fn_validar_detalle_pagos();
	
	if( viewmodal.valor_diferencia_ib.val() > 0 ){
		viewmodal.valor_diferencia_ib.notify("Existe Diferencia",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	} 
	
	if( viewmodal.descripcion_ib.val() == '' ){
		viewmodal.descripcion_ib.closest("tr").notify("Ingrese Descripcion",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	
	/** 2do Envio peticion a Servidor **/
	if( validador ){
		
		let dataString = JSON.stringify(viewmodal.data_table)
		
		let dataparametros = { 
			'id_entidad_patronal': view.id_entidad_patronal.val(),
			'id_comprobantes': viewmodal.id_diario_ib.val(),
			'id_bancos': viewmodal.id_bancos_ib.val(),
			'anio': viewmodal.anio_cabeza_ib.val(),
			'mes': viewmodal.mes_cabeza_ib.val(),
			'fecha_deposito': viewmodal.fecha_deposito_ib.val(),
			'fecha_contable': viewmodal.fecha_contable_ib.val(),
			'referencia': viewmodal.referencia_ib.val(),
			'valor': viewmodal.valor_ib.val(),
			'descripcion': viewmodal.descripcion_ib.val(),
			'diferencia': viewmodal.valor_diferencia_ib.val(),
			'detalle': dataString
		};		
		
		$.ajax({
			/*beforeSend:modalresumen.fn_loading_open(),*/
			url: 'index.php?controller=PrincipalBusquedasRecaudaciones&action=insertar_valores_ingreso_bancos',
			method: 'POST',
			dataType: 'json',
			/*complete: modalresumen.fn_loading_close(),*/
			data:dataparametros
		}).done(function(x){
			
			console.log(x);
			
		}).fail(function(xhr, status, error){
			//element.html('<span> ERROR al buscar datos encontrados.</span>');
		});
		
	}
	
}


/***=================================================================================================================
 * 									MODAL RESUMEN
 ====================================================================================================================*/
var modalresumen	= modalresumen || {};

modalresumen.pnl_modal	= $("#mod_resumen");
modalresumen.id_entidad_patronal	= $("#id_entidad_patronal_resumen");
modalresumen.anio	= $("#anio_resumen");
modalresumen.mes	= $("#mes_resumen");
modalresumen.tabla	= $("#tbldetalleResumen");
modalresumen.div_btn_valores	= $("#dv_btn_valores_encontrados");

/**====== funciones internas modal resumen ========*/
modalresumen.fn_init_controles	= function(){
	
	modalresumen.id_entidad_patronal.empty().append(view.id_entidad_patronal.find('option:selected').clone());	
	modalresumen.anio.val(view.anio_busqueda.val());
	modalresumen.mes.empty().append(view.mes_busqueda.find('option:selected').clone());
		
}

modalresumen.cargar_entidad_patronal	= function(){
	
	modalresumen.id_entidad_patronal.val(view.id_entidad_patronal);
	
}

modalresumen.fn_loading_open	= function(){
	$("#dv_loading").html('<div class="text-center"><img src="view/images/ajax-loader.gif"> Cargando...</div>');
}

modalresumen.fn_loading_close	= function(){
	$("#dv_loading").html('');
}

modalresumen.seleccionar_valores_contable	= function(){
	
	let filas_contable = {};
	viewmodal.data_table	= [];
	
	try{
		filas_contable 	= modalresumen.tabla.find('tbody tr[name="data"]');
		$.each(filas_contable, function(index, fila){
			
			let tcolumna	= $(this).children('td').first();
						
			let objeto = { "index":index,
					"identificador": tcolumna.data('identificador'),
					"nombre_identificador": tcolumna.data('nombre_identificador'),
					"razon": tcolumna.data('razon'), 
					"valor": tcolumna.data('valor'), 
					"nombre_contable": tcolumna.data('nombre_contable'),
					"comprobante_contable": tcolumna.data('comprobanteid')
						};
			viewmodal.data_table.push(objeto); 
			
			//console.log('AQUI VA OBJETO');  //**NOTICE
			//console.log(viewmodal.data_table);   //**NOTICE
			
		});		
		
		if( viewmodal.data_table.length ){
			
			viewmodal.dtdetalle.clear();
			viewmodal.dtdetalle.draw();
			viewmodal.dtdetalle.rows.add(viewmodal.data_table).draw();
		}
		
	}catch (err) {
		// TODO: handle exception
		viewmodal.data_table	= [];
		swal({title:"ERROR",text:"Error de sintaxis --> "+err.message, icon:"error",dangerMode:true});
	}
	
	
}


/**====== funciones modal resumen ========*/

var fn_open_resumen	= function(){
	modalresumen.pnl_modal.modal();
	modalresumen.fn_init_controles();
}

var fn_buscar_descuento_contable_ini	= function(){
	
	var element = $("#div_pnl_historial_moras");
	if( element.length ){	
		
		element.html('<div class="text-center"><img src="view/images/ajax-loader.gif"> Cargando...</div>');
		
		$.ajax({
			beforeSend: modalresumen.fn_loading_open(),
			complete: modalresumen.fn_loading_close(),
			url: 'index.php?controller=PrincipalBusquedasRecaudaciones&action=obtenerResumenDescuentosContable',
			method: 'POST',
			dataType: 'json',
			data:{ id_entidad_patronal : modalresumen.id_entidad_patronal.val(),
				mes: modalresumen.mes.val(),
				anio: modalresumen.anio.val()}
		}).done(function(x){
			element.html(x.html);		
		}).fail(function(xhr, status, error){
			element.html('<span> ERROR al buscar datos encontrados.</span>');
		});
		
		//element.collapse("show")
				
	}
}

var fn_buscar_descuento_contable	= function(){
	
	var element = modalresumen.tabla;
	if( element.length ){	
				
		$.ajax({
			beforeSend:modalresumen.fn_loading_open(),
			url: 'index.php?controller=PrincipalBusquedasRecaudaciones&action=getResumenDescuentosContable',
			method: 'POST',
			dataType: 'json',
			complete: modalresumen.fn_loading_close(),
			data:{ id_entidad_patronal : modalresumen.id_entidad_patronal.val(),
				mes: modalresumen.mes.val(),
				anio: modalresumen.anio.val()}
		}).done(function(x){
			
			element.find('tbody').html('');
			
			let hay_datos	= false;
			if( x.aportes.length ){
			
				hay_datos	= true;
				/** PARA AGREGAR TABLA EN LINEA **/
				
				element.find('tbody').append('<tr class="bg-red" ><td colspan="3">Resumen Carga Aportes</td></tr>');
				//let contador	= 0; 
				$.each( x.aportes, function(index,fila){
					index = index + 1;
					let nfila = $('<tr></tr>');
					let ncolumna = $('<td></td>');
										
					ncolumna.data('comprobanteid', fila.id_ccomprobantes );
					ncolumna.data('identificador', '-1' );
					ncolumna.data('nombre_identificador', 'aportes');
					ncolumna.data('razon', '' );
					ncolumna.data('valor', fila.valor );
					ncolumna.data('nombre_contable', fila.codigo_plan_cuentas+' - '+fila.nombre_plan_cuentas );
					
					nfila.attr({name:'data'});
					ncolumna.text(index);
					nfila.append( ncolumna );					
					nfila.append('<td>'+fila.codigo_plan_cuentas+' - '+fila.nombre_plan_cuentas+'</td>');
					nfila.append('<td>'+fila.valor+'</td>');
					element.find('tbody').append(nfila);
				})
			}
			
			if( x.creditos.length ){
				
				hay_datos	= true;
				
				element.find('tbody').append('<tr class="bg-red"><td colspan="3">Resumen Carga Aportes</td></tr>');
				//let contador	= 0; 
				$.each( x.creditos, function(index,fila){
					index = index + 1;
					let nfila = $('<tr></tr>');
					let ncolumna = $('<td></td>');
					
					ncolumna.data('comprobanteid', fila.id_ccomprobantes );
					ncolumna.data('identificador', fila.id_tipo_creditos ); 
					ncolumna.data('nombre_identificador', fila.nombre_plan_cuentas);
					ncolumna.data('razon', '' );
					ncolumna.data('valor', fila.valor );
					ncolumna.data('nombre_contable', fila.codigo_plan_cuentas+' - '+fila.nombre_plan_cuentas );
					ncolumna.text();
					
					nfila.attr({name:'data'});
					ncolumna.text(index);
					nfila.append( ncolumna );
					nfila.append('<td>'+fila.codigo_plan_cuentas+' - '+fila.nombre_plan_cuentas+'</td>');
					nfila.append('<td>'+fila.valor+'</td>');
					element.find('tbody').append(nfila);
				})
			}
			
			if(hay_datos){
				modalresumen.div_btn_valores.removeClass('hide');
			}
			
			
		}).fail(function(xhr, status, error){
			//element.html('<span> ERROR al buscar datos encontrados.</span>');
		});
		
		//element.collapse("show")
				
	}
}










