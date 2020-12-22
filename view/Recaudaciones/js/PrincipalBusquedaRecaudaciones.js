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

/**============================================= MODAL ========================================**/

var viewmodal = viewmodal || {};

viewmodal.




