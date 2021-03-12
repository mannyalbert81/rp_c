$(document).ready(function(){ 	 
	   
	load_saldos_bancarios();
	
	cargaCalificaciones();
	cargaCalificacionesRiesgos();
	cargaDenominacionesMonedas();
});

/**VARIABLES DE ARCHIVO**/
var fecha = new Date();
var yearFecha = fecha.getFullYear();
var view = view || {};



	
	view.id_emisor	= $("#id_emisor");
	view.tipo_identificacion_saldos_bancarios_mensuales 				= $("#tipo_identificacion_saldos_bancarios_mensuales");
    view.identificacion_emisor 													= $("#identificacion_emisor");
    view.tipo_cuenta_saldos_bancarios_mensuales 						= $("#tipo_cuenta_saldos_bancarios_mensuales");	
    view.numero_cuenta_saldos_bancarios_mensuales 						= $("#numero_cuenta_saldos_bancarios_mensuales");
    view.cuenta_contable_saldos_bancarios_mensuales	 					= $("#cuenta_contable_saldos_bancarios_mensuales");
    view.id_denominaciones_monedas 										= $("#id_denominaciones_monedas");
    view.valor_moneda_saldos_bancarios_mensuales 						= $("#valor_moneda_saldos_bancarios_mensuales");
    view.valor_libros_saldos_bancarios_mensuales 						= $("#valor_libros_saldos_bancarios_mensuales");
    view.id_calificaciones 												= $("#id_calificaciones");
    view.id_calificaciones_riesgos 										= $("#id_calificaciones_riesgos");
    view.fecha_ult_calificacion_saldos_bancarios_mensuales 				= $("#fecha_ult_calificacion_saldos_bancarios_mensuales");
    view.tasa_interes_saldos_bancarios_mensuales 						= $("#tasa_interes_saldos_bancarios_mensuales");
	view.fecha_corte_saldos_bancarios_mensuales 						= $("#fecha_corte_saldos_bancarios_mensuales");





var cargaCalificaciones	= function (){
	
	let $ddlCalificaciones = view.id_calificaciones;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosBancarios&action=cargaCalificaciones",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCalificaciones.empty();
		$ddlCalificaciones.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlCalificaciones.append("<option value= " +value.id_calificaciones +" >" + value.nombre_calificaciones  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCalificaciones.empty();
	})
	
}




var cargaCalificacionesRiesgos	= function (){
	
	let $ddlCalificacionesRiesgos = view.id_calificaciones_riesgos;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosBancarios&action=cargaCalificacionesRiesgos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCalificacionesRiesgos.empty();
		$ddlCalificacionesRiesgos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlCalificacionesRiesgos.append("<option value= " +value.id_calificaciones_riesgos +" >" + value.nombre_calificaciones_riesgos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCalificacionesRiesgos.empty();
	})
	
}



var cargaDenominacionesMonedas	= function (){
	
	let $ddlDenominacionesMonedas = view.id_denominaciones_monedas;
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=SaldosBancarios&action=cargaDenominacionesMonedas",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlDenominacionesMonedas.empty();
		$ddlDenominacionesMonedas.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlDenominacionesMonedas.append("<option value= " +value.id_denominaciones_monedas +" >" + value.nombre_denominaciones_monedas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlDenominacionesMonedas.empty();
	})
	
}




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
    				url:"index.php?controller=IngresoInversionesG2&action=autompleteEmisores",
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
     			   
     			  view.identificacion_emisor.notify("Digite Identificación correcta",{ position:"top center"});
     			  
     			  view.id_emisor.val('');
     			  view.identificacion_emisor.val('');     			     			 
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
    
});





view.fecha_ult_calificacion_saldos_bancarios_mensuales.inputmask("datetime",{
	mask: "y-2-1", 
	placeholder: "yyyy-mm-dd", 
	leapday: "-02-29", 
	separator: "-", 
	clearIncomplete: true,
	rightAlign: true,		 
	yearrange: {
		minyear: 1950,
		maxyear: yearFecha
	}
});	


view.fecha_corte_saldos_bancarios_mensuales.inputmask("datetime",{
	mask: "y-2-1", 
	placeholder: "yyyy-mm-dd", 
	leapday: "-02-29", 
	separator: "-", 
	clearIncomplete: true,
	rightAlign: true,		 
	yearrange: {
		minyear: 1950,
		maxyear: yearFecha
	}
});	






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


var load_saldos_bancarios	= function(){
	
	viewTabla.tabla	=  $( '#'+viewTabla.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    /*'fixedHeader': true,*/
	    'ajax': {
	        'url':'index.php?controller=SaldosBancarios&action=dtMostrarSaldosBancariosMensuales',
	        'data': function ( d ) {
		
				//console.log(d);
				
		
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
    		{ data: 'tipo_identificacion_saldos_bancarios_mensuales'},
    		{ data: 'ruc_emisores' },
    		{ data: 'nombre_emisores'},
    		{ data: 'tipo_cuenta_saldos_bancarios_mensuales' },
    		{ data: 'numero_cuenta_saldos_bancarios_mensuales' },
    		{ data: 'cuenta_contable_saldos_bancarios_mensuales'},
    		{ data: 'nombre_denominaciones_monedas',  },
    		{ data: 'valor_moneda_saldos_bancarios_mensuales',  },
    		{ data: 'valor_libros_saldos_bancarios_mensuales',  },
    		{ data: 'nombre_calificaciones'},
    		{ data: 'nombre_calificaciones_riesgos' },
    		{ data: 'fecha_ult_calificacion_saldos_bancarios_mensuales'},
    		{ data: 'tasa_interes_saldos_bancarios_mensuales' },
    		{ data: 'fecha_corte_saldos_bancarios_mensuales' },
			{ data: 'opciones', orderable: false }
    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,2,6,7,8] }
	      ],
		/*'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },*/
	    /*autoWidth:true,*//*251518*/
        sScrollY: "600",
        sScrollX: " 100% ",
        sScrollXInner:" 200% ",
        bScrollCollapse: true,
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<' col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }
        ],
        'language':idioma_espanol
	 });
		
}




var fn_insertar_saldos_bancarios	= function(){
	
	let validador = true;
	let parametros	= {};
	
	
	
	
	
	if( view.tipo_identificacion_saldos_bancarios_mensuales.val() == '' ){
		view.tipo_identificacion_saldos_bancarios_mensuales.notify("Selecione Tipo Identificación",{position:"buttom left", autoHideDelay: 2000});
		validador = false;		
		return false;
	}
	
	parametros.tipo_identificacion_saldos_bancarios_mensuales	= view.tipo_identificacion_saldos_bancarios_mensuales.val();
	
	if( view.id_emisor.val() == '' || view.id_emisor.val() == '0' ){
		view.id_emisor.notify("Ingrese Emisor",{position:"buttom left", autoHideDelay: 2000});
		validador = false;		
		return false;
	}
	parametros.id_emisor	= view.id_emisor.val();
	

	
	if( view.tipo_cuenta_saldos_bancarios_mensuales.val() == '' ){
		view.tipo_cuenta_saldos_bancarios_mensuales.notify("Ingrese Tipo de Cuenta",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.tipo_cuenta_saldos_bancarios_mensuales	= view.tipo_cuenta_saldos_bancarios_mensuales.val();
	
	if( view.numero_cuenta_saldos_bancarios_mensuales.val() == '' ){
		view.numero_cuenta_saldos_bancarios_mensuales.notify("Selecciones tipo de cuenta",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.numero_cuenta_saldos_bancarios_mensuales	= view.numero_cuenta_saldos_bancarios_mensuales.val();
	
	if( view.cuenta_contable_saldos_bancarios_mensuales.val() == '' ){
		view.cuenta_contable_saldos_bancarios_mensuales.notify("Ingrese un numero de cuenta",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.cuenta_contable_saldos_bancarios_mensuales	= view.cuenta_contable_saldos_bancarios_mensuales.val();
	
	

	if( view.id_denominaciones_monedas.val() == '' ){
		view.id_denominaciones_monedas.notify("Seleccione Denominacion de Moneda",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.id_denominaciones_monedas	= view.id_denominaciones_monedas.val();



	
	if( view.valor_moneda_saldos_bancarios_mensuales.val() == 0 ){
		view.valor_moneda_saldos_bancarios_mensuales.notify("Ingrese valor moneda",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.valor_moneda_saldos_bancarios_mensuales	= view.valor_moneda_saldos_bancarios_mensuales.val();
		
		
		
	if( view.valor_libros_saldos_bancarios_mensuales.val() == 0 ){
		view.valor_libros_saldos_bancarios_mensuales.notify("Ingrese valor en libros",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.valor_libros_saldos_bancarios_mensuales	= view.valor_libros_saldos_bancarios_mensuales.val();
		
		
		
	if( view.id_calificaciones.val() == '0' ){
		view.id_calificaciones.notify("Selecciones Calificacion",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.id_calificaciones	= view.id_calificaciones.val();
	
	if( view.id_calificaciones_riesgos.val() == '0' ){
		view.id_calificaciones_riesgos.notify("Selecciones calificador de riesgos",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.id_calificaciones_riesgos	= view.id_calificaciones_riesgos.val();
	
	if( view.fecha_ult_calificacion_saldos_bancarios_mensuales.val() == '' ){
		view.fecha_ult_calificacion_saldos_bancarios_mensuales.notify("Ingrese fecha de ultima calificacion",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.fecha_ult_calificacion_saldos_bancarios_mensuales	= view.fecha_ult_calificacion_saldos_bancarios_mensuales.val();
			
	if( view.tasa_interes_saldos_bancarios_mensuales.val() == '0' ){
		view.tasa_interes_saldos_bancarios_mensuales.notify("Ingrese tasa de interes",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.tasa_interes_saldos_bancarios_mensuales	= view.tasa_interes_saldos_bancarios_mensuales.val();
	
	
	if( view.fecha_corte_saldos_bancarios_mensuales.val() == '' ){
		view.fecha_corte_saldos_bancarios_mensuales.notify("Ingrese fecha de corte",{position:"buttom left", autoHideDelay: 2000});
		validador = false;
		return false;
	}
	parametros.fecha_corte_saldos_bancarios_mensuales	= view.fecha_corte_saldos_bancarios_mensuales.val();
	
	
		
	
	if( validador ){
				
		$.ajax({

			url: 'index.php?controller=SaldosBancarios&action=IngresaSaldosBancariosMensuales',
			method: 'POST',
			dataType: 'json',

			data:parametros
		}).done(function(x){
			
			console.log(x)
			
			
			if( x.estatus == 'OK' ){				
				swal({ title:"SALDOS BANCARIOS MENSUALES",
					text:'Inversión Ingresada',
					icon:'success'
				});
				
				
					view.id_emisor.val() = '';
					view.tipo_identificacion_saldos_bancarios_mensuales.val() = '';
				    view.identificacion_emisor.val() = '';
				    view.numero_cuenta_saldos_bancarios_mensuales.val() = '';
				    view.cuenta_contable_saldos_bancarios_mensuales.val() = '';
				    view.valor_moneda_saldos_bancarios_mensuales.val() = '';
				    view.valor_libros_saldos_bancarios_mensuales.val() = '';
				    view.id_calificaciones.val() = '0';
				    view.id_calificaciones_riesgos.val() = '0';
				    view.fecha_ult_calificacion_saldos_bancarios_mensuales.val() = '';
				    view.tasa_interes_saldos_bancarios_mensuales.val() = '';
					
					load_saldos_bancarios();
				
				
			}else{
				swal({ title:"SALDOS BANCARIOS MENSUALES",
					text:'Problemas al ingresar Saldos Bancarios Mensuales',
					icon:'error'
				});
			}
			
			viewTabla.tabla.ajax.reload();
			
		}).fail(function(xhr, status, error){
			//element.html('<span> ERROR al buscar datos encontrados.</span>');
			var error = xhr.responseText;
			console.log(error);
		});
		
	}
	
}

