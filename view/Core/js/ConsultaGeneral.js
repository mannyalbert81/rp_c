$(document).ready( function (){

   /*load_personal_cta_individual(1);*/
   /*load_patronal_cta_individual(1);*/
   
   mostrar_aportes_personales_cta_indvidual();
   mostrar_aportes_patronales_cta_indvidual();
   
   init_controles();
   
});


function init_controles(){
	try {
		
		$('#pnl_div_aportes_personales').on('change','#id_contribucion_tipo_personales',function(){
			dt_view1.dt_tabla_personales.ajax.reload();			
		});
		
		$('#pnl_div_aportes_patronales').on('change','#id_contribucion_tipo_patronales',function(){
			dt_view1.dt_tabla_patronales.ajax.reload();			
		});
		
		
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { var element=$(this); if( element.attr("href") == "#personales_dt" ){	dt_view1.dt_tabla_personales.ajax.reload(); }else if( element.attr("href") == "#patronales_dt" ){ dt_view1.dt_tabla_patronales.ajax.reload(); } });
		
	} catch (e) {
		// TODO: handle exception
		console.log("ERROR AL IMPLEMENTAR PLUGIN DE FILEUPLOAD");
	}	
}

var fn_listar_contribucion_tipo	= function(a){
	
	var elemento = $('#'+a);
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CoreInformacionParticipes&action=getcontribucion_tipo",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		elemento.empty();
		elemento.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			elemento.append("<option value= " +value.id_contribucion_tipo +" >" + value.nombre_contribucion_tipo  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		elemento.empty();
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


var dt_view1 = dt_view1 || {};

dt_view1.dt_tabla_personales = null;
dt_view1.nombre_tabla_personales = 'tbl_personales';
dt_view1.dt_tabla_patronales = null;
dt_view1.nombre_tabla_patronales = 'tbl_patronales';

dt_view1.params_personales = function(){
	return {id_participes: $("#id_participes").val(),
		id_contribucion_tipo: $('#id_contribucion_tipo_personales').length ? $('#id_contribucion_tipo_personales').val() : 0
	};
}

dt_view1.params_patronales = function(){
	return {id_participes: $("#id_participes").val(),
		id_contribucion_tipo: $('#id_contribucion_tipo_patronales').length ? $('#id_contribucion_tipo_patronales').val() : 0
	};
}

var mostrar_aportes_personales_cta_indvidual = function(){
	
	/*var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};*/
		
	dt_view1.dt_tabla_personales	=  $('#'+dt_view1.nombre_tabla_personales).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=CoreInformacionParticipes&action=dtmostrar_aportes_personales_cta_indvidual',
	        'data': function ( d ) {
	            return $.extend( {}, d, dt_view1.params_personales() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 0, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'anio', orderable: false},
	    		{ data: 'enero', orderable: false},
	    		{ data: 'febrero', orderable: false},
	    		{ data: 'marzo', orderable: false},
	    		{ data: 'abril', orderable: false},
	    		{ data: 'mayo', orderable: false},
	    		{ data: 'junio', orderable: false},
	    		{ data: 'julio', orderable: false},
	    		{ data: 'agosto', orderable: false},
	    		{ data: 'septiembre', orderable: false},
	    		{ data: 'octubre', orderable: false},
	    		{ data: 'noviembre', orderable: false},
	    		{ data: 'diciembre', orderable: false},
	    		{ data: 'acumulado', orderable: false}
	    		/*{ data: 'total', orderable: false}*/
	    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13 ] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        'language':idioma_espanol,
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6' <'tag_personales'> >><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	/*{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }*/
        ],
	 });	
	
	/* PARA DIBUJAR BUSCADOR DE TIPO SELECT */
	$('.tag_personales').html("<select class =\"form-control pull-right col-xs-12 col-md-12\" id =\"id_contribucion_tipo_personales\"  ><option>aportacion personal</option><option>2</option></select>");
	
	/* AQUI PONER EL NOMBRE DE ELEMENTO GENERADO */
	fn_listar_contribucion_tipo("id_contribucion_tipo_personales");
} 


var mostrar_aportes_patronales_cta_indvidual = function(){
	
	/*var dataSend = { id_entidad_patronal:view.id_entidad_patronal.val(),id_descuentos_formatos:view.id_descuentos_formatos.val()};*/

		
	dt_view1.dt_tabla_patronales	=  $('#'+dt_view1.nombre_tabla_patronales).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=CoreInformacionParticipes&action=dtmostrar_aportes_patronales_cta_indvidual',
	        'data': function ( d ) {
	            return $.extend( {}, d, dt_view1.params_patronales() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 0, "desc" ]],
	    'columns': [	    	    
	    		{ data: 'anio', orderable: false},
	    		{ data: 'enero', orderable: false},
	    		{ data: 'febrero', orderable: false},
	    		{ data: 'marzo', orderable: false},
	    		{ data: 'abril', orderable: false},
	    		{ data: 'mayo', orderable: false},
	    		{ data: 'junio', orderable: false},
	    		{ data: 'julio', orderable: false},
	    		{ data: 'agosto', orderable: false},
	    		{ data: 'septiembre', orderable: false},
	    		{ data: 'octubre', orderable: false},
	    		{ data: 'noviembre', orderable: false},
	    		{ data: 'diciembre', orderable: false},
	    		{ data: 'acumulado', orderable: false}
	    		/*{ data: 'total', orderable: false}*/
	    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13 ] }
	      ],
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        'language':idioma_espanol,
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6' <'tag_patronales'> >><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	/*{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }*/
        ],
	 });	
	
	/* PARA DIBUJAR BUSCADOR DE TIPO SELECT */
	$('.tag_patronales').html("<select class =\"form-control pull-right col-xs-12 col-md-12\" id =\"id_contribucion_tipo_patronales\"  ><option>aportacion personal</option><option>2</option></select>");
	
	/* AQUI PONER EL NOMBRE DE ELEMENTO GENERADO */
	fn_listar_contribucion_tipo("id_contribucion_tipo_patronales");
	
} 


