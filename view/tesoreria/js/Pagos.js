/*** Variables de Pagina - Globales ***/
var view	= view || {};
//variable para dataTable
var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tbl_listado_cuentas_pagar_pendientes';
viewTable.contenedor = $("#div_listado_cuentas_pagar_pendientes");

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
	
	load_cuentas_pagar_pendientes();
		
});

var load_cuentas_pagar_pendientes	= function(){
	
	var dataSend = { };
	
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=Pagos&action=dtListarCuentasPagarPendientes',
	        'data': function ( d ) {
	            return $.extend( {}, d, dataSend );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 1, "desc" ]],
	    'columns': [	    	    
	    	{ data: 'numfila', orderable: false },
    		{ data: 'lote', },
    		{ data: 'origen', orderable: false },
    		{ data: 'generado_por'},
    		{ data: 'descripcion', orderable: false},
    		{ data: 'fecha' },
    		{ data: 'beneficiario'},
    		{ data: 'valor_documento', },
    		{ data: 'saldo_documento', orderable: false },
    		{ data: 'cheque', orderable: false },
    		{ data: 'transferencia', orderable: false }    		    		
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,2,4,8,9,10] }
	      ],
	    'scrollX': "100%",
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [],
        'language':idioma_espanol
	 });
		
}

// dom : "B<'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
// dom : 'Blfrtip'
//dom : "B<'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
// <div class="">
//buttons: [
    //'copy', 'csv', 'excel', 'pdf', 'print'
	//{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' },
	//{ "extend": 'pdfHtml5', "titleAttr": 'PDF', "text":'<span class="fa fa-file-pdf-o fa-2x fa-fw"></span>',"className": ' no-padding btn btn-default btn-sm' }
//],

function verificaMetodoPago(obj){
	
	$.ajax({
		url:"index.php?controller=Pagos&action=validaMetodoPago",
		dataType:"json",
		type:"POST",
		data:{},
	}).done(function(x){		
		
		console.log(x)
		
		if(x.respuesta == 'ok'){
			window.open("","_blank")
		}
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
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
	
	console.log(obj);
	//
	//index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'
	
	return false;
}

