$(document).ready( function (){

   load_personal_cta_individual(1);
   load_patronal_cta_individual(1);
   
   mostrar_aportes_personales_cta_indvidual();
   mostrar_aportes_patronales_cta_indvidual();
});

        	


	   function load_personal_cta_individual(pagina){

		   var id_participes=$("#id_participes").val();
		   var id_contribucion_tipo= $("#id_contribucion_tipo").val();
		  
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_personales_cta_individual").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_personales_cta_individual").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreInformacionParticipes&action=consulta_personal_cta_individual&id_participes='+id_participes+'&id_contribucion_tipo='+id_contribucion_tipo, 
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#personales_registrados").html(x);
	                 $("#load_personales_cta_individual").html("");
	                 $("#tabla_personal_cta_individual").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#personales_registrados").html("Ocurrio un error al cargar la informacion de Aportes Personales..."+estado+"    "+error);
	              }
	            });


		   }

	   function load_patronal_cta_individual(pagina){

		   var id_participes=$("#id_participes").val();
		   var id_contribucion_tipo= $("#id_contribucion_tipo").val();
		  
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_patronal_cta_individual").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_patronal_cta_individual").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreInformacionParticipes&action=consulta_patronal_cta_individual&id_participes='+id_participes+'&id_contribucion_tipo='+id_contribucion_tipo, 
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#patronal_registrados").html(x);
	                 $("#load_patronal_cta_individual").html("");
	                 $("#tabla_patronal_cta_individual").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#patronal_registrados").html("Ocurrio un error al cargar la informacion de Aportes Patronales..."+estado+"    "+error);
	              }
	            });


		   }





function init_controles(){
	try {
		
		 $("#nombre_carga_recaudaciones").fileinput({			
		 	showPreview: false,
	        showUpload: false,
	        elErrorContainer: '#errorImagen',
	        allowedFileExtensions: ["txt"],
	        language: 'esp' 
		 });
		 
		 /*$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { var element=$(this); if( element.attr("href") == "#pendientes" ){	dt_view1.dt_tabla_pendientes.ajax.reload(); }else if( element.attr("href") == "#procesados" ){ dt_view1.dt_tabla_procesados.ajax.reload(); }else if( element.attr("href") == "#negados" ){ dt_view1.dt_tabla_error.ajax.reload(); } });*/
		
	} catch (e) {
		// TODO: handle exception
		console.log("ERROR AL IMPLEMENTAR PLUGIN DE FILEUPLOAD");
	}
	
	//iniciar eventos de cambio en select de entidad patronal
	$("#id_entidad_patronal").on("change",function(){
		cambio_entidad_patronal();
	});
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

dt_view1.params = function(){
	return {id_participes: $("#id_participes").val(),
		id_contribucion_tipo: $("#id_contribucion_tipo").val()};
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
	            return $.extend( {}, d, dt_view1.params() );
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
        'language':idioma_espanol
	 });	
	
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
	            return $.extend( {}, d, dt_view1.params() );
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
        'language':idioma_espanol
	 });	
	
} 


