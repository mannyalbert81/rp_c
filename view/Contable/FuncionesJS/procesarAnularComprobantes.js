
var GLOBALfecha = new Date();
var GLOBALyear = GLOBALfecha.getFullYear();
var GLOBALStringFecha = GLOBALfecha.getDate() + "/" + (GLOBALfecha.getMonth() +1) + "/" + GLOBALfecha.getFullYear();

$(document).ready(function(){
	$("#div_entidad").hide();
	
	$('#fecha_desde').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
			  'yearrange': { minyear: 1950,
				  			 maxyear: GLOBALyear	
				  			},
  			  'clearIncomplete': true
			});
	
	$('#fecha_hasta').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
		      'yearrange': { minyear: 1950,
			  			 maxyear: GLOBALyear	
			  			},
	  		  'clearIncomplete': true
		});
	
	
	 $("#buscar").click(function(){

			//load_comprobantes(1);
			
			load_comprobantes_procesar();
			
	});
});

function load_comprobantes(pagina){
	
	var validacionFecha = validarControles();
	console.log(validacionFecha);
	if(!validacionFecha){
		return false;
	}
	
	//iniciar variables
	 var con_id_entidades=$("#id_entidades").val();
	 var con_id_tipo_comprobantes=$("#id_tipo_comprobantes").val();
	 var con_numero_ccomprobantes=$("#numero_ccomprobantes").val();
	 var con_referencia_doc_ccomprobantes=$("#referencia_doc_ccomprobantes").val();
	 var con_fecha_desde=$("#fecha_desde").val();
	 var con_fecha_hasta=$("#fecha_hasta").val();
	 var con_datos_proveedores = $("#datos_proveedor").val();

	  var con_datos={
			  id_entidades:con_id_entidades,
			  id_tipo_comprobantes:con_id_tipo_comprobantes,
			  numero_ccomprobantes:con_numero_ccomprobantes,
			  referencia_doc_ccomprobantes:con_referencia_doc_ccomprobantes,
			  fecha_desde:con_fecha_desde,
			  fecha_hasta:con_fecha_hasta,
			  datos_proveedor:con_datos_proveedores,
			  action:'ajax',
			  page:pagina
			  };


	$("#comprobantes").fadeIn('slow');
	$.ajax({
		url: "index.php?controller=ReporteComprobante&action=index",
        type : "POST",
        async: true,			
		data: con_datos,
		 beforeSend: function(objeto){
		   $("#comprobantes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            
		},
		success:function(data){
		
		     $("#div_comprobantes").html(data);
             $("#comprobantes").html("");
             $("#tabla_comprobantes").tablesorter(); 
			
		}
	})
}

function validarControles(){
	
	console.log("INICIO DE FUNCION validarControles");
	
	var $fecha_desde = $("#fecha_desde"),
		$fecha_hasta = $("#fecha_hasta"),
		$proveedor = $("#datos_proveedores");
		
	/** validacion de fechas **/
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length == 0 || $fecha_hasta.val() == "" ) ){
		$fecha_hasta.val(GLOBALStringFecha);
	}
	
	if( ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") && ($fecha_desde.val().length == 0 || $fecha_desde.val() == "") ){
		$fecha_desde.val(GLOBALStringFecha);
	}
	
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") ){

		if ($.datepicker.parseDate('dd/mm/yy', $fecha_desde.val()) > $.datepicker.parseDate('dd/mm/yy', $fecha_hasta.val())) {
			$fecha_desde.notify("Fecha no puede ser mayor",{ 'autoHideDelay':1000,position:"buttom-left"});
			console.log("llego aca")
			return false;
		}
	}
	
	return true;
}

var view = view || {};
view.entidad = $("#id_entidades");
view.tipo_comprobantes = $("#id_tipo_comprobantes");
view.numero_comprobantes = $("#numero_ccomprobantes");
view.fecha_desde = $("#fecha_desde");
view.fecha_hasta = $("#fecha_hasta"); 
view.proveedor = $("#datos_proveedor");

var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tblcomprobantes';
viewTable.contenedor = $("#div_comprobantes");
viewTable.params	= function(){ 
	var extenddatapost = { 'id_entidades': view.entidad.val(),
			'id_tipo_comprobantes': view.tipo_comprobantes.val(), 
			'numero_ccomprobantes': view.numero_comprobantes.val(),
			'fecha_desde': view.fecha_desde.val(),
			'fecha_hasta': view.fecha_hasta.val(),
			'proveedor': view.proveedor.val()};
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

var load_comprobantes_procesar	= function(){
			
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=ProcesarAnularComprobantes&action=dtMostrarComprobantes',
	        'data': function ( d ) {
	            return $.extend( {}, d, viewTable.params() );
	            },
            'dataSrc': function ( json ) {                
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 6, "desc" ]],
	    'columns': [	    	    
	    	{ data: 'numfila', orderable: false },
    		{ data: 'nombre_entidad'},
    		{ data: 'tipo' },
    		{ data: 'concepto'},
    		{ data: 'numero' },
    		{ data: 'valor' },
    		{ data: 'fecha'},
    		{ data: 'estado', orderable: false },
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
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }
        ],
        'language':idioma_espanol
	 });
		
}

//dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",

var viewmodal = viewmodal || {};
viewmodal.ventana		= $("#mod_procesar_comprobante");
viewmodal.id_comprobantes	= $("#md_id_comprobantes");
viewmodal.numero_comprobantes	= $("#md_numero_comprobantes");
viewmodal.fecha_comprobantes	= $("#md_fecha_comprobantes");
viewmodal.valor_comprobantes	= $("#md_valor_comprobantes");
viewmodal.tabla_comprobantes	= $("#tbl_detalle_comprobantes"); 
viewmodal.dv_contabilizar	= $("#md_div_contabilizar");
viewmodal.dv_anular		= $("#md_div_anular");

var proceso_contabilizar = function(a){
	
	
	var element = $(a);
	
	let $link = $(a);	
	
	var id_comprobantes	= $link.data("id_comprobantes");
		
	if( id_comprobantes <= 0 || id_comprobantes == "" || id_comprobantes == undefined ){
		return false;
	}	
	
	viewmodal.dv_contabilizar.addClass('hide');
	viewmodal.dv_anular.addClass('hide');
	
	if( element.length )
	{	
		$.ajax({
			url:"index.php?controller=ProcesarAnularComprobantes&action=obtenerdetallescomprobantes",
			dataType:"json",
			type:"POST",
			data:{ 'id_comprobantes': id_comprobantes }
		}).done(function(x){
			
			var estado = x.estatus;
			
			if( estado == 1 ){
				
				var cabecera = x.cabecera;
				
				viewmodal.id_comprobantes.val( id_comprobantes );
				viewmodal.numero_comprobantes.val( cabecera[0].numero_ccomprobantes );
				viewmodal.fecha_comprobantes.val( cabecera[0].fecha_ccomprobantes );
				viewmodal.valor_comprobantes.val( cabecera[0].valor_ccomprobantes );
				
				/** para el detalle **/
				viewmodal.tabla_comprobantes.find('tbody').html('');
				var detalle = x.detalle;
				$.each( detalle , function(index, row ) {
					
					var htmlTags = '<tr>'+
			        '<td>' + (index + 1 ) + '</td>'+
			        '<td>' + row.codigo_plan_cuentas + '</td>'+
			        '<td>' + row.nombre_plan_cuentas + '</td>'+
			        '<td>' + row.descripcion_dcomprobantes + '</td>'+
			        '<td>' + row.debe_dcomprobantes + '</td>'+
			        '<td>' + row.haber_dcomprobantes + '</td>'+
			      '</tr>';
								      
					viewmodal.tabla_comprobantes.find('tbody').append(htmlTags);
				});
				
				viewmodal.dv_contabilizar.removeClass('hide');
				
				viewmodal.ventana.modal();
								
			}else{
				
				swal( {
					 title:"RESPUESTA",
					 text: x.mensaje,
					 icon: "info",
					 timer: 2000,
					 button: false,
					});
			}
				
			
		}).fail(function(xhr, status, error ){
			
			swal( {
				 title:"ERROR",
				 text: 'error al conectar con el servidor',
				 icon: "error",
				 timer: 2000,
				 button: false,
				});
			
		});
				
	}
	
}
	
	var proceso_anular = function(a){
		
		
		var element = $(a);
		
		let $link = $(a);	
		
		var id_comprobantes	= $link.data("id_comprobantes");
			
		if( id_comprobantes <= 0 || id_comprobantes == "" || id_comprobantes == undefined ){
			return false;
		}	
		
		viewmodal.dv_contabilizar.addClass('hide');
		viewmodal.dv_anular.addClass('hide');
		
		if( element.length )
		{	
			$.ajax({
				url:"index.php?controller=ProcesarAnularComprobantes&action=obtenerdetallescomprobantes",
				dataType:"json",
				type:"POST",
				data:{ 'id_comprobantes': id_comprobantes }
			}).done(function(x){
				
				var estado = x.estatus;
				
				if( estado == 1 ){
					
					var cabecera = x.cabecera;
					
					viewmodal.id_comprobantes.val( id_comprobantes );
					viewmodal.numero_comprobantes.val( cabecera[0].numero_ccomprobantes );
					viewmodal.fecha_comprobantes.val( cabecera[0].fecha_ccomprobantes );
					viewmodal.valor_comprobantes.val( cabecera[0].valor_ccomprobantes );
					
					/** para el detalle **/
					viewmodal.tabla_comprobantes.find('tbody').html('');
					var detalle = x.detalle;
					$.each( detalle , function(index, row ) {
						
						var htmlTags = '<tr>'+
				        '<td>' + (index + 1 ) + '</td>'+
				        '<td>' + row.codigo_plan_cuentas + '</td>'+
				        '<td>' + row.nombre_plan_cuentas + '</td>'+
				        '<td>' + row.descripcion_dcomprobantes + '</td>'+
				        '<td>' + row.debe_dcomprobantes + '</td>'+
				        '<td>' + row.haber_dcomprobantes + '</td>'+
				      '</tr>';
									      
						viewmodal.tabla_comprobantes.find('tbody').append(htmlTags);
					});
					
					viewmodal.dv_anular.removeClass('hide');
					
					viewmodal.ventana.modal();
									
				}else{
					
					swal( {
						 title:"RESPUESTA",
						 text: x.mensaje,
						 icon: "info",
						 timer: 2000,
						 button: false,
						});
				}
					
				
			}).fail(function(xhr, status, error ){
				
				swal( {
					 title:"ERROR",
					 text: 'error al conectar con el servidor',
					 icon: "error",
					 timer: 2000,
					 button: false,
					});
				
			});
					
	}
}
	
	var procesarComprobantes	= function(){
		
		var identificador	= viewmodal.id_comprobantes.val();
		
		if( identificador <= 0 || identificador == "" || identificador == undefined ){
			return false;
		}
		
		$.ajax({
			url:"index.php?controller=ProcesarAnularComprobantes&action=ProcesaComprobantes",
			dataType:"json",
			type:"POST",
			data:{ 'id_comprobantes': identificador }
		}).done(function(x){
			
			var estado = x.estatus;
			
			if( estado == 1 ){
				
				swal( {
					 title:"INFORMACION",
					 text: "comprobante contabilizado",
					 icon: "info",
					 timer: 2000,
					 button: false,
					});
				
				viewmodal.ventana.modal('hide');
				$("#buscar").click();
								
			}else{
				
				swal( {
				 title:"INFORMACION",
				 text: "comprobante no contabilizado",
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
			}
				
			
		}).fail(function(xhr, status, error ){
			
			swal( {
				 title:"ERROR",
				 text: 'error al conectar con el servidor',
				 icon: "error",
				 timer: 2000,
				 button: false,
				});
			
		});
		
	}
	
    var procesarComprobantes	= function(){
		
		var identificador	= viewmodal.id_comprobantes.val();
		
		if( identificador <= 0 || identificador == "" || identificador == undefined ){
			return false;
		}
		
		$.ajax({
			url:"index.php?controller=ProcesarAnularComprobantes&action=ProcesaComprobantes",
			dataType:"json",
			type:"POST",
			data:{ 'id_comprobantes': identificador }
		}).done(function(x){
			
			var estado = x.estatus;
			
			if( estado == 1 ){
				
				swal( {
					 title:"INFORMACION",
					 text: "comprobante contabilizado",
					 icon: "info",
					 timer: 2000,
					 button: false,
					});
				
				viewmodal.ventana.modal('hide');
				$("#buscar").click();
								
			}else{
				
				swal( {
				 title:"INFORMACION",
				 text: "comprobante no contabilizado",
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
			}
				
			
		}).fail(function(xhr, status, error ){
			
			swal( {
				 title:"ERROR",
				 text: 'error al conectar con el servidor',
				 icon: "error",
				 timer: 2000,
				 button: false,
				});
			
		});
		
	}  
	
    var anularComprobantes	= function(){
		
		var identificador	= viewmodal.id_comprobantes.val();
		
		if( identificador <= 0 || identificador == "" || identificador == undefined ){
			return false;
		}
		
		$.ajax({
			url:"index.php?controller=ProcesarAnularComprobantes&action=AnulaComprobantes",
			dataType:"json",
			type:"POST",
			data:{ 'id_comprobantes': identificador }
		}).done(function(x){
			
			var estado = x.estatus;
			
			if( estado == 1 ){
				
				swal( {
					 title:"INFORMACION",
					 text: "comprobante contabilizado",
					 icon: "info",
					 timer: 2000,
					 button: false,
					});
				
				viewmodal.ventana.modal('hide');
				$("#buscar").click();
								
			}else{
				
				swal( {
				 title:"INFORMACION",
				 text: "comprobante no contabilizado",
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
			}
				
			
		}).fail(function(xhr, status, error ){
			
			swal( {
				 title:"ERROR",
				 text: 'error al conectar con el servidor',
				 icon: "error",
				 timer: 2000,
				 button: false,
				});
			
		});
		
	}
	
