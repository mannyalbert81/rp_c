$(document).ready(function(){
	
	
	init();
	//load_retencion(1);
	//cargaCuentasPagar(1);
	load_cuentas_pagar();
		
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * 
 * @returns
 */
function init(){
	
	//mask: "9{1,10}.99",
	$("#impuestos_cuentas_pagar").hide();
	iniciar_eventos_datatable();	
}

/*******************************************************************************
 * function para mostrar las lista Cuentas Pagar
 * 
 * @returns
 */
function cargaCuentasPagar( pagina=1){
	
	let _buscador = $("#search_cuentas_pagar").val();
	
	$.ajax({
		beforeSend:function(x){ $("#divLoaderPage").addClass("loader") },
		url:"index.php?controller=TesCuentasPagar&action=ListaCuentasPagar",
		type:"POST",
		dataType:"html",
		data:{peticion:'ajax',search:_buscador,page:pagina}
	}).done(function(datos){		
		
		$("#cuentas_pagar_registrados").html(datos)
		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		
	}).always(function(){
		$("#divLoaderPage").removeClass("loader");
	})
}

$("#cuentas_pagar_registrados").on('click','a.showpdf',function(event){
	let enlace = $(this);
	let _url = "index.php?controller=TesCuentasPagar&action=RptCuentasPagar&id_cuentas_pagar="+enlace.data().id;
	
	if ( enlace.data().id ) {
		
		window.open(_url,"_blank");
		
	}
	
	event.preventDefault();
})
 

/* PARA DIV CON MENSAJES DE ERROR */
/* SE ACTIVAN AL ENFOCAR EN INPUT RELACIONADO */


 function load_retencion(pagina){

		   var search=$("#search_retencion").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_retencion").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_retencion").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Retencion&action=consulta_retencion&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#retencion_registrados_detalle").html(x);
	                 $("#load_retencion").html("");
	                 $("#tabla_retencion").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#retencion_registrados_detalle").html("Ocurrio un error al cargar la informacion de Detalle Retenciones..."+estado+"    "+error);
	              }
	            });

  }

$("#search_cuentas_pagar").on('keyup',function(){
	cargaCuentasPagar();
})

/********************************************************** EMPIEZA PROCEOSOS CON DATATABLE *************************************************/

//variable de vista
var view	= view || {};
view.txt_busqueda	= $("#search_cuentas_pagar");
//variable para dataTable
var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tbl_listado_cuentas_pagar';
viewTable.params = { 'input_search': view.txt_busqueda.val() };
viewTable.contenedor = $("#div_listado_cuentas_pagar");

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

var load_cuentas_pagar	= function(){
	
	var dataSend = { 'input_search': view.txt_busqueda.val() };
	
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=TesReporteCuentasPagar&action=dtListarCuentasPagar',
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
    		{ data: 'fecha'},
    		{ data: 'cedula_proveedor' },
    		{ data: 'nombre_proveedor'},
    		{ data: 'numero_documento' },
    		{ data: 'valor_documento', orderable: false },
    		{ data: 'descripcion', orderable: false},
    		{ data: 'opciones', orderable: false }
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,5,6,7 ] }
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
	let _url = "index.php?controller=TesCuentasPagar&action=RptCuentasPagar&id_cuentas_pagar="+enlace.data().id;
	
	if ( enlace.data().id ) {
		
		window.open(_url,"_blank");
		
	}
	
	event.preventDefault();
})






