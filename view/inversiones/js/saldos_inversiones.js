$(document).ready(function(){ 	 
	   
	load_saldos_inversiones();
	cargaCalificacionEmisor();
	cargaCalificacionRiesgos();
	//cargaTipoInstrumento();
});

var view = view || {};

view.numero_instrumento	= $("#numero_instrumento");
view.id_ingreso_inversiones	= $("#id_ingreso_inversiones");
view.estado_inversion	= $("#estado_inversion");
view.rango_vencimiento	= $("#rango_vencimiento");
view.valor_contable		= $("#valor_contable");
view.tasa_nominal 	= $("#tasa_nominal");
view.tasa_cupon	= $("#tasa_cupon"); 
view.fecha_ult_cupon	= $("#fecha_ult_cupon"); 
view.precio_compra_porcentaje	= $("#precio_compra_porcentaje"); 
view.valor_efectivo 		= $("#valor_efectivo");
view.rendimiento_porcentaje = $("#rendimiento_porcentaje");
view.precio_anio_renta_fija = $("#precio_anio_renta_fija");
view.interes_acumulado_cobrar	= $("#interes_acumulado_c");
view.monto_generados_interes 	= $("#monto_generados_interes");
view.valor_mercado 	= $("#valor_mercado");
view.numero_acciones_corte 		= $("#numero_acciones_corte");
view.precio_mercado_actual 	= $("#precio_mercado_actual");
view.precio_mercado_hace_anio 	= $("#precio_mercado_hace_anio");
view.dividendo_accion 	= $("#dividendo_accion");
view.codigo_vecto_precio 	= $("#codigo_vecto_precio");
view.calificacion_emisor 		= $("#calificacion_emisor");
view.calificacion_riesgos		= $("#calificadora_riesgo");
view.fecha_ultima_calificacion 	= $("#fecha_ult_calificacion");
view.provision_constituida 	= $("#provision_constituida");
view.estado_vencimiento		= $("#estado_vencimiento");
view.valor_nominal_vencido 	= $("#valor_nominal_vencido");
view.interes_acumulado_cobrar_vencido 	= $("#interes_acumulado_cobrar_vencido");
view.numero_cuotas_vencidas 	= $("#numero_cuotas_vencidas");
view.cuenta_contable_cap_vencido 	= $("#cuenta_contable_cap_vencido");
view.valor_dolares 	= $("#valor_dolares");
view.cuenta_contable_ren_vencido 	= $("#cuenta_contable_ren_vencido");
view.valor_dolares_dos 	= $("#valor_dolares_dos");
view.cuenta_contable_provision_acumulada_capital 	= $("#cuenta_contable_provision_acumulada_capital");
view.valor_dolares_tres 	= $("#valor_dolares_tres");
view.cuenta_contable_provision_acumulada_rendimiento 	= $("#cuenta_contable_provision_acumulada_rendimiento");
view.valor_dolares_cuatro 	= $("#valor_dolares_cuatro");
view.valor_liquidado 	= $("#valor_liquidado");
view.fecha_liquidacion 	= $("#fecha_liquidacion");
view.precio_liquidacion	= $("#precio_liquidacion");
view.valor_liquidacion 	= $("#valor_liquidacion");
view.motivo_liquidacion = $("#motivo_liquidacion");


var cargaCalificacionEmisor	= function (){
	
	let $ddlCalificacionEmisor = view.calificacion_emisor;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosInversiones&action=cargaCalificacionEmisor",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCalificacionEmisor.empty();
		$ddlCalificacionEmisor.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlCalificacionEmisor.append("<option value= " +value.id_calificaciones +" >" + value.descripcion_calificaciones  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCalificacionEmisor.empty();
	})
	
}


var cargaCalificacionRiesgos	= function (){
	
	let $ddlCalificacionRiesgos = view.calificacion_riesgos;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosInversiones&action=cargaCalificacionRiesgos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCalificacionRiesgos.empty();
		$ddlCalificacionRiesgos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlCalificacionRiesgos.append("<option value= " +value.id_calificaciones_riesgos +" >" + value.descripcion_calificaciones_riesgos  + "</option>");	
  		});
		 
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCalificacionRiesgos.empty();
	})
	
}


var cargaTipoInstrumento	= function (){
	
	let $ddltipoInstrumento = view.tipo_instrumento;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosInversiones&action=cargaTipoInstrumento",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddltipoInstrumento.empty();
		$ddltipoInstrumento.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddltipoInstrumento.append("<option value= " +value.id_tipos_instrumentos +" >" + value.nombre_tipos_instrumentos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddltipoInstrumento.empty();
	})
	
}

var cargarDetallesNumeroInstrumento	= function(a){
	
	let id_ingreso_inversiones	= a;
	
	$.ajax({
		url:'index.php?controller=SaldosInversiones&action=verDetallesNumeroInstrumento',
		dataType:'json',
		type:'POST',
		data:{'id_ingresos_inversiones':id_ingreso_inversiones}
		}).done(function(x){
			
			let data	= x.data[0];
			
			$("#div_pnl_info_ingreso_inversiones").removeClass('hide');
			
			$("#lbl_tipo_identificacion").text( data.tipo_identificacion_ingreso_inversiones );
			$("#lbl_identificacion").text( data.ruc_emisores );
			$("#lbl_nombre_emisor").text( data.nombre_emisores );
			$("#lbl_tipo_instrumento").text( data.tipo_identificacion_ingreso_inversiones );
			$("#lbl_tipo_renta").text( data.tipo_renta_ingreso_inversiones );
			$("#lbl_fecha_compra").text( data.fecha_compra_ingreso_inversiones );
			
			/** para colocar valor de compra **/ 
			view.valor_contable.val( data.valor_compra_ingreso_inversiones);
			view.valor_efectivo.val( data.valor_compra_ingreso_inversiones );
			view.valor_mercado.val(data.valor_compra_ingreso_inversiones );
			
			console.log(x)
		}).fail(function(xhr, status, error){
			console.log(xhr.responseText)
		});
	
}

/***
 * @desc fn que permite generar autocomplete numero instrumento
 * @param e
 * @returns
 */
view.numero_instrumento.on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=SaldosInversiones&action=autompleteNumeroInstrumento",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {
     	       	    			
    			if(ui.item.id == ''){
    				view.id_emisor.val('');
        			view.identificacion_emisor.val('');
    				return;
    			}
    			
    			view.id_ingreso_inversiones.val(ui.item.id);
    			view.numero_instrumento.val(ui.item.value);
    			
    			cargarDetallesNumeroInstrumento( ui.item.id );
    			    			     	     
     	    },
     	   //appendTo: "#mod_distribucion",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null){
     			   
     			  view.numero_instrumento.notify("Numero Instrumento no correcto",{ position:"top center"});
     			  
     			  view.id_ingreso_inversiones.val('0');
     			  view.numero_instrumento.val(''); 
     			  $("#div_pnl_info_ingreso_inversiones").addClass('hide');
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
    
})


/***
 * @desc fn que permite generar autocomplete de identificacion emisores
 * @param e
 * @returns
 */
$(".plan_cuentas_ac").on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=SaldosInversiones&action=autompletePlanCuentas",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {
     	       	    			
    			if(ui.item.id == ''){
    				//view.id_emisor.val('');
        			//view.identificacion_emisor.val('');
    				return;
    			}
    			
    			$(this).val(ui.item.value);
    			//view.id_emisor.val(ui.item.id);
    			//view.identificacion_emisor.val();
    			    			     	     
     	    },
     	   //appendTo: "#mod_distribucion",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null){
     			   
     			  $(this).val('');
     			  view.identificacion.notify("Digite Identificación correcta",{ position:"top center"});
     			  
     			  //view.id_emisor.val('');
     			  //view.identificacion_emisor.val('');     			     			 
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
    
})


var viewTabla = viewTabla || {};

viewTabla.tabla  = null;
viewTabla.nombre = 'tblsaldos_inversiones';
viewTabla.contenedor = $("#div_saldos_inversiones");
viewTabla.params	= function(){ 
	var extenddatapost = { };
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


var load_saldos_inversiones	= function(){
	
	viewTabla.tabla	=  $( '#'+viewTabla.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'fixedHeader': true,
	    'ajax': {
	        'url':'index.php?controller=SaldosInversiones&action=dtMostrarSaldosInversiones',
	        'data': function ( d ) {
	            return $.extend( {}, d, viewTabla.params() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 6, "desc" ]],
	    'columns': [	    	    
	    	{ data: 'numfila', orderable: false },
    		{ data: 'tipo_identificacion'},
    		{ data: 'identificacion_emisor' },
    		{ data: 'nombre_emisor'},
    		{ data: 'numero_instrumento' },
    		{ data: 'tipo_instrumento' },
    		{ data: 'estado_inversion'},
    		{ data: 'tipo_renta'},    		
    		{ data: 'fecha_compra', orderable: false },
    		{ data: 'rango_vencimiento', orderable: false },
    		{ data: 'valor_contable'},
    		{ data: 'tasa_nominal' },
    		{ data: 'tasa_cupon'},
    		{ data: 'fecha_ultimo_cupon' },
    		{ data: 'precio_compra' },
    		{ data: 'valor_efectivo'},
    		{ data: 'rendimiento_porcentaje', orderable: false },
    		{ data: 'precio_hace_anio', orderable: false },
    		{ data: 'interes_acumulado', orderable: false },
    		{ data: 'monto_interes_ganados', orderable: false },
    		{ data: 'valor_mercado', orderable: false },
    		{ data: 'numero_acciones_corte', orderable: false },
    		{ data: 'precio_actual_mercado', orderable: false },    		
    		{ data: 'precio_hace_anio_mercado', orderable: false },
    		{ data: 'dividendo_acciones', orderable: false },
    		{ data: 'codigo_vecto_precio', orderable: false },
    		{ data: 'calificacion_emisor', orderable: false },
    		{ data: 'calificacion_riesgos', orderable: false },
    		{ data: 'fecha_ultima_calificacion', orderable: false },
    		{ data: 'provision_constituida', orderable: false },
    		{ data: 'estado_vencimiento', orderable: false },
    		{ data: 'valor_nominal', orderable: false },
    		{ data: 'interes_acumulado_cobrar_vencido', orderable: false },
    		{ data: 'numero_cuotas_vencido', orderable: false },
    		{ data: 'cc_capital_vencido', orderable: false },
    		{ data: 'valor_dolares', orderable: false },
    		{ data: 'cc_rendimiento_vencido', orderable: false },    		
    		{ data: 'valor_dolares_dos', orderable: false },
    		{ data: 'cc_provision_acum_capital', orderable: false },
    		{ data: 'valor_dolares_tres', orderable: false },
    		{ data: 'cc_provision_acum_rendimiento', orderable: false },
    		{ data: 'valor_dolares_cuatro', orderable: false },
    		{ data: 'valor_liquidado', orderable: false },
    		{ data: 'fecha_liquidacion', orderable: false },
    		{ data: 'precio_liquidacion', orderable: false },
    		{ data: 'valor_liquidacion', orderable: false },
    		{ data: 'motivo_liquidacion', orderable: false },    		
    		{ data: 'opciones', orderable: false }
    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,2,6,7,8] }
	      ],
	    sScrollY: "600",
	    sScrollX: " 100% ",
	    sScrollXInner:" 200% ",
	    bScrollCollapse: true,
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }
        ],
        'language':idioma_espanol
	 });
		
}


var fn_insertar_saldos_inversiones	= function(){
	
	let validador = true;
	let parametros	= {};
	/*
	if( view.tipo_identificacion.val() == '0' ){
		view.tipo_identificacion.notify("Selecione Tipo Identificación",{position:"buttom left", autoHideDelay: 2000});
		validador = false;		
		return false;
	}
	parametros.tipo_identificacion	= view.tipo_identificacion.val();
	
	if( view.id_emisor.val() == '' || view.id_emisor.val() == '0' ){
		view.identificacion_emisor.notify("Ingrese Emisor",{position:"buttom left", autoHideDelay: 2000});
		validador = false;		
		return false;
	}
	parametros.id_emisor	= view.id_emisor.val();
	
	if( view.numero_instrumento.val() == '' ){
		view.numero_instrumento.notify("Ingrese Numero Instrumento",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.numero_instrumento	= view.numero_instrumento.val();
	
	if( view.tipo_instrumento.val() == '0' ){
		view.tipo_instrumento.notify("Seleccione Tipo Instrumento",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.id_tipo_instrumento	= view.tipo_instrumento.val();
	
	if( view.tipo_renta.val() == '' ){
		view.tipo_renta.notify("Seleccione Tipo Renta",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.tipo_renta	= view.tipo_renta.val();
	
	if( view.fecha_emision.val() == '' ){
		view.fecha_emision.notify("Ingrese fecha Emision",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.fecha_emision	= view.fecha_emision.val();
	
	if( view.fecha_compra.val() == '' ){
		view.fecha_compra.notify("Ingrese Fecha Compra",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.fecha_compra	= view.fecha_compra.val();
	
	if( view.fecha_vencimiento.val() == '' ){
		view.fecha_vencimiento.notify("Ingrese Fecha Vencimiento",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.fecha_vencimiento	= view.fecha_vencimiento.val();
		
	if( view.tasa_nominal.val() == '' ){
		view.tasa_nominal.notify("Ingrese Tasa Nominal",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.tasa_nominal	= view.tasa_nominal.val();
		
	if( view.plazo_pactado.val() == '' ){
		view.plazo_pactado.notify("Ingrese Plazo Pactado",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.plazo_pactado	= view.plazo_pactado.val();
	
	if( view.valor_nominal.val() == '' ){
		view.valor_nominal.notify("Ingrese Valor Nominal",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.valor_nominal	= view.valor_nominal.val();
	
	if( view.numero_acciones.val() == '' ){
		view.numero_acciones.notify("Ingrese Numero Acciones",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.numero_acciones	= view.numero_acciones.val();
			
	if( view.precio_compra.val() == '' ){
		view.precio_compra.notify("Ingrese Precio Compra",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.precio_compra	= view.precio_compra.val();
	
	if( view.valor_compra.val() == '' ){
		view.valor_compra.notify("Ingrese Valor Compra",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.valor_compra	= view.valor_compra.val();
	
	if( view.periodo_pago.val() == '' ){
		view.periodo_pago.notify("Ingrese Periodo Pago",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.periodo_pago	= view.periodo_pago.val();
		
	if( view.amortizacion_capital.val() == '' ){
		view.amortizacion_capital.notify("Ingrese Amortizacion Capital",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.amortizacion_capital	= view.amortizacion_capital.val();
	
	if( view.amortizacion_interes.val() == '' ){
		view.amortizacion_interes.notify("Ingrese Amortizacion Interes",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.amortizacion_interes	= view.amortizacion_interes.val();
	
	if( view.base_tasa_capital.val() == '0' ){
		view.base_tasa_capital.notify("Seleccione Base Capital",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.base_capital	= view.base_tasa_capital.val();
	
	if( view.base_tasa_interes.val() == '' ){
		view.base_tasa_interes.notify("Seleccione Base Interes",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.base_interes	= view.base_tasa_interes.val();
	
	if( view.periodo_gracia.val() == '0' ){
		view.tipo_instrumento.notify("Seleccione Periodo Gracia",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.periodo_gracia	= view.periodo_gracia.val();
	
	if( view.estado_registro.val() == '' ){
		view.estado_registro.notify("Seleccione Estado Registro",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.estado_registro	= view.estado_registro.val();	*/
		
	parametros.id_ingreso_inversiones = view.id_ingreso_inversiones.val();
	parametros.estado_inversion = view.estado_inversion.val();
	parametros.rango_vencimiento = view.rango_vencimiento.val();
	parametros.valor_contable = view.valor_contable.val();
	parametros.tasa_nominal = view.tasa_nominal.val();
	parametros.tasa_cupon = view.tasa_cupon.val();
	parametros.fecha_ult_cupon = view.fecha_ult_cupon.val();
	parametros.precio_compra_porcentaje = view.precio_compra_porcentaje.val();
	parametros.valor_efectivo = view.valor_efectivo.val();
	parametros.rendimiento_porcentaje = view.rendimiento_porcentaje.val();
	parametros.precio_anio_renta_fija = view.precio_anio_renta_fija.val();
	parametros.interes_acumulado_cobrar = view.interes_acumulado_cobrar.val();
	parametros.monto_generados_interes = view.monto_generados_interes.val();
	parametros.valor_mercado = view.valor_mercado.val();
	parametros.numero_acciones_corte = view.numero_acciones_corte.val();
	parametros.precio_mercado_actual = view.precio_mercado_actual.val();
	parametros.precio_mercado_hace_anio = view.precio_mercado_hace_anio.val();
	parametros.dividendo_accion = view.dividendo_accion.val();
	parametros.codigo_vecto_precio = view.codigo_vecto_precio.val();
	parametros.calificacion_emisor = view.calificacion_emisor.val();
	parametros.calificacion_riesgos = view.calificacion_riesgos.val();
	parametros.fecha_ultima_calificacion = view.fecha_ultima_calificacion.val();
	parametros.provision_constituida = view.provision_constituida.val();
	parametros.estado_vencimiento = view.estado_vencimiento.val();
	parametros.valor_nominal_vencido = view.valor_nominal_vencido.val();
	parametros.interes_acumulado_cobrar_vencido = view.interes_acumulado_cobrar_vencido.val();	
	parametros.numero_cuotas_vencidas = view.numero_cuotas_vencidas.val();
	parametros.cuenta_contable_cap_vencido = view.cuenta_contable_cap_vencido.val();
	parametros.valor_dolares = view.valor_dolares.val();
	parametros.cuenta_contable_ren_vencido = view.cuenta_contable_ren_vencido.val();
	parametros.valor_dolares_dos = view.valor_dolares_dos.val();
	parametros.cuenta_contable_provision_acumulada_capital = view.cuenta_contable_provision_acumulada_capital.val();
	parametros.valor_dolares_tres = view.valor_dolares_tres.val();	
	parametros.cuenta_contable_provision_acumulada_rendimiento = view.cuenta_contable_provision_acumulada_rendimiento.val();
	parametros.valor_dolares_cuatro = view.valor_dolares_cuatro.val();
	parametros.valor_liquidado = view.valor_liquidado.val();
	parametros.fecha_liquidacion = view.fecha_liquidacion.val();
	parametros.precio_liquidacion = view.precio_liquidacion.val();
	parametros.valor_liquidacion = view.valor_liquidacion.val();
	parametros.motivo_liquidacion = view.motivo_liquidacion.val(); 
	 
		
	/** 2do Envio peticion a Servidor **/
	if( validador ){
				
		$.ajax({
			/*beforeSend:modalresumen.fn_loading_open(),*/
			url: 'index.php?controller=SaldosInversiones&action=IngresaSaldosInversiones',
			method: 'POST',
			dataType: 'json',
			/*complete: modalresumen.fn_loading_close(),*/
			data:parametros
		}).done(function(x){
			
			if( x.estatus == 'OK' ){				
				swal({ title:"INVERSIONES",
					text:'Inversión Ingresada',
					icon:'success'
				});
			}else{
				swal({ title:"INVERSIONES",
					text:'Problemas al ingresar inversiones',
					icon:'error'
				});
			}
			console.log(x);
			
		}).fail(function(xhr, status, error){
			//element.html('<span> ERROR al buscar datos encontrados.</span>');
			var error = xhr.responseText;
			console.log(error);
		});
		
	}
	
}

