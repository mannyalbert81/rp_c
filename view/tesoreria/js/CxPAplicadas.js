$(document).ready(function(){
	
	
	init();
	//load_retencion(1);
	//cargaCuentasPagar(1);
	load_cuentas_pagar_aplicadas();
		
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * 
 * @returns
 */
function init(){
	
	//mask: "9{1,10}.99",
	
	iniciar_eventos_datatable();	
}

$("#search_cuentas_pagar_aplicadas").on('keyup',function(){
	cargaCuentasPagar();
})

/********************************************************** EMPIEZA PROCEOSOS CON DATATABLE *************************************************/

//variable de vista
var view	= view || {};
view.txt_busqueda	= $("#search_cuentas_pagar_aplicadas");
//variable para dataTable
var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tbl_listado_cuentas_pagar_aplicadas';
viewTable.params = { 'input_search': view.txt_busqueda.val() };
viewTable.contenedor = $("#div_listado_cuentas_pagar_aplicadas");

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

var load_cuentas_pagar_aplicadas	= function(){
	
	var dataSend = { 'input_search': view.txt_busqueda.val() };
	
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=Pagos&action=dtListarCuentasPagarAplicadas',
	        'data': function ( d ) {
	            return $.extend( {}, d, dataSend );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 0, "desc" ]],
	    'columns': [	    	    
	    	{ data: 'numfila', orderable: false },
    		{ data: 'fecha'},
    		{ data: 'usuario' },
    		{ data: 'identificacion_bene'},
    		{ data: 'nombre_bene' },
    		{ data: 'metodo_pago' },
    		{ data: 'banco_bene', orderable: false},
    		{ data: 'valor_pago', orderable: false },
    		{ data: 'descripcion', orderable: false },
    		{ data: 'opciones', orderable: false },
    		{ data: 'cheque', orderable: false }
    		    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,2,6,7,8,9 ] }
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

var iniciar_eventos_datatable = function(){
	
	viewTable.contenedor.tooltip({
	    selector: 'a.showpdf',
	    trigger: 'hover',
	    html: true,
        delay: {"show": 500, "hide": 0},
        placement:"left"
	});
		
}

viewTable.contenedor.on('click','a.showpdf',function(event){
	let enlace = $(this);
	let _url = "index.php?controller=ReporteComprobante&action=comprobante_contable_reporte&id_ccomprobantes="+enlace.data().id;
	
	if ( enlace.data().id ) {
		
		window.open(_url,"_blank");
		
	}
	
	event.preventDefault();
})


viewTable.contenedor.on('click','a.showpdfcheque',function(event){
	
	let enlace = $(this);
	let _url = "index.php?controller=GenerarCheque&action=generaReporteCheque";
	
	
	if ( enlace.data().id_pagos ) {
		
		var params = {
				"id_pagos":enlace.data().id_pagos
		}
					
		var form = document.createElement("form");
	    form.setAttribute("method", "post");
	    form.setAttribute("action", _url);
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
	
	event.preventDefault();
})






