$(document).ready(function(){ 	 
	   
	load_inversiones();
	cargaCalificacionEmisor();
	cargaCalificacionRiesgos();
	cargaTipoInstrumento();
});

var view = view || {};

view.id_emisor	= $("#id_emisor");
view.id_ingreso_inversiones	= $("#id_ingreso_inversiones");
view.tipo_identificacion	= $("#tipo_identificacion");
view.identificacion_emisor	= $("#identificacion_emisor");
view.numero_instrumento	= $("#numero_instrumento");
view.tipo_instrumento	= $("#tipo_instrumento");
view.calificacion_emisor	= $("#calificacion_emisor"); 
view.calificacion_riesgos	= $("#calificadora_riesgo");
view.fecha_emision	= $("#fecha_emision");
view.fecha_compra	= $("#fecha_compra");
view.fecha_vencimiento	= $("#fecha_vencimiento");
view.tasa_nominal 	= $("#tasa_nominal");
view.plazo_pactado 	= $("#plazo_pactado");
view.valor_nominal 	= $("#valor_nominal");
view.numero_acciones 	= $("#numero_acciones");
view.precio_compra 	= $("#precio_compra");
view.valor_compra 	= $("#valor_compra");
view.periodo_pago 	= $("#periodo_pago");
view.amortizacion_capital	= $("#amortizacion_capital");
view.amortizacion_interes 	= $("#amortizacion_interes");
view.base_tasa_capital 	= $("#base_tasa_capital");
view.base_tasa_interes 	= $("#base_tasa_interes");
view.periodo_gracia 	= $("#periodo_gracia");
view.estado_registro 	= $("#estado_registro");



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
			let data	= x[0];
			//$("#lbl_tipo_identificacion").
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
     			   
     			  view.numero_instrumento.notify("Digite Numero Instrumento correcta",{ position:"top center"});
     			  
     			  view.id_ingreso_inversiones.val('0');
     			  view.numero_instrumento.val('');     			     			 
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
view.identificacion_emisor.on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=SaldosInversiones&action=autompleteEmisores",
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
    			
    			view.id_emisor.val(ui.item.id);
    			view.identificacion_emisor.val(ui.item.value);
    			    			     	     
     	    },
     	   //appendTo: "#mod_distribucion",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null){
     			   
     			  view.identificacion.notify("Digite Identificación correcta",{ position:"top center"});
     			  
     			  view.id_emisor.val('');
     			  view.identificacion_emisor.val('');     			     			 
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
    
})


var viewTabla = viewTabla || {};

viewTabla.tabla  = null;
viewTabla.nombre = 'tblinversiones';
viewTabla.contenedor = $("#div_inversiones");
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


var load_inversiones	= function(){
	
	viewTabla.tabla	=  $( '#'+viewTabla.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'fixedHeader': true,
	    'ajax': {
	        'url':'index.php?controller=SaldosInversiones&action=dtMostrarInversiones',
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
    		{ data: 'tipo_renta'},
    		{ data: 'fecha_emision', orderable: false },
    		{ data: 'fecha_compra', orderable: false },
    		{ data: 'fecha_vencimiento', orderable: false },
    		{ data: 'tasa_nominal'},
    		{ data: 'plazo_pactado' },
    		{ data: 'valor_nominal'},
    		{ data: 'numero_acciones' },
    		{ data: 'precio_compra' },
    		{ data: 'valor_compra'},
    		{ data: 'periodo_pago', orderable: false },
    		{ data: 'amortizacion_capital', orderable: false },
    		{ data: 'amortizacion_interes', orderable: false },
    		{ data: 'base_tasa_capital', orderable: false },
    		{ data: 'base_tasa_interes', orderable: false },
    		{ data: 'periodo_gracia', orderable: false },
    		{ data: 'estado_registro', orderable: false },
    		{ data: 'opciones', orderable: false }
    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,2,6,7,8] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }
        ],
        'language':idioma_espanol
	 });
		
}


var fn_insertar_inversiones	= function(){
	
	let validador = true;
	let parametros	= {};
	
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
	parametros.estado_registro	= view.estado_registro.val();	
	
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
			
			console.log(x);
			
		}).fail(function(xhr, status, error){
			//element.html('<span> ERROR al buscar datos encontrados.</span>');
			var error = xhr.responseText;
			console.log(error);
		});
		
	}
	
}

