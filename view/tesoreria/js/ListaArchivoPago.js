var listaArchivos	= []; // variable que permite el almacenamiento de datos sobre archivo mostrado
var CantidadArchivo	= 0;

$(document).ready(function(){
		
	init();	
	loadBancosLocal();
	loadusuariodepartamento();
	loadTipoArchivo();
	load_archivo_pago();	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	$('#fecha_proceso').inputmask("9999/99/99", {placeholder: '____/__/__',clearIncomplete:true });	
	$("#generar_archivo_pago").attr("disabled",true); //metodo para desabilitar boton de generacion de archivo	
	
	/*** para check en vista de TESORERIA **/
	$("#div_datos_archivos").on( 'change','input:checkbox.chk_pago_seleccionado', function() {
		fnSeleccionaPagosIndividuales(this);
	});	
	
	$("#div_datos_archivos").on( 'change','input:checkbox#chk_pagos_all', function() {
		fnSeleccionaPagos(this);
	});
	
}


/***
 * @desc funcion para traer la chequera de la entidad
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadBancosLocal(){	
	
	var $ddlChequera = $("#id_bancos_local");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaBancosLocal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlChequera.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
			})
		}
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer el tipo archivo 
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadTipoArchivo(){	
	
	var $ddlTipoArchivo = $("#id_tipo_archivo_pago");
	params = {};
	$ddlTipoArchivo.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaTipoArchivo",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsTipoArchivo = x.data;
			$ddlTipoArchivo.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipoArchivo,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipoArchivo.append( '<option value="'+value.id_tipo_pago_archivo+'">'+value.nombre_tipo_pago_archivo+'</option>' );
			})
		}
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer usuarios de departamento de creditos
 * @param none
 * @retuns void
 * @ajax si 
 */
var loadusuariodepartamento	= function(){	
	
	var $ddlusuariodepartamento = $("#ddl_usuario_departamento");
	params = {};
	$ddlusuariodepartamento.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaUsuarioDepartamento",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsUsuarioDepartamento = x.data;
			$ddlusuariodepartamento.append('<option value="0">--Seleccione--</option>' );
			$.each(rsUsuarioDepartamento,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlusuariodepartamento.append( '<option value="'+value.id_usuarios+'" data-oficina="'+value.id_oficina+'" >'+value.apellidos_usuarios +' '+ value.nombre_usuarios + '</option>' );
			})
		}
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function FormularioPost(url,target,params){
	 
	 var form = document.createElement("form");
	 form.setAttribute("id", "fromPost");
     form.setAttribute("method", "post");
     form.setAttribute("action", url);
     form.setAttribute("target", target);

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

/***
 * @desc funcion que permite mostrar los datos 
 * @returns
 */
function buscarDatosArchivoPago(){
	
	var tipoArchivo 	= $("#id_tipo_archivo_pago");
	var fechaProceso	= $("#fecha_proceso");
	var bancoPago		= $("#id_bancos_local");
	
	/** validaciones para busqueda dc 2020/06/10 **/
	if( tipoArchivo.val() == "" || tipoArchivo.val() == "0" )
	{
		tipoArchivo.notify( "Seleccione Tipo Archivo", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( fechaProceso.val().includes("_")  || fechaProceso.val() == "" )
	{
		fechaProceso.notify( "Ingrese Fecha Proceso", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( bancoPago.val() == "" || bancoPago.val() == "0"  )
	{
		bancoPago.notify( "Seleccione Banco ", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	params = {fecha_proceso:fechaProceso.val(), id_tipo_archivo_pago:tipoArchivo.val(), id_bancos:bancoPago.val()};
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=showArchivoPago",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		var tblArchivo = $("#tblListadoArchivoPago");
		tblArchivo.empty();
		if( x.tabla != undefined && x.tabla != null ){	
			
			if( parseInt( x.cantidadDatos) > 0 )
			{
				$("#div_resultados_archivo_pago").removeClass("hidden");					
				tblArchivo.append(x.tabla);
				$("#mod_total_archivo").text(x.cantidadDatos);
				$("#generar_archivo_pago").attr("disabled",false);
				$("#mod_paginacion_archivo").html(x.paginacion); //se establece la paginacion del archivo creado
				
			}else
			{
				$("#div_resultados_archivo_pago").addClass("hidden");
				swal( { text:"No Existen Datos con los parametros solicitados", title:"INFORMACION", icon:"info" } );
			}		
			
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}
 
$("#buscar_archivo_pago").on("click",function(event){
	
	//buscarDatosArchivoPago();
	//load_archivo_pago();
	fncargarBusqueda();
	
})


$("#generar_archivo_pago").on("click",function(event){
	
	fngenerarArchivo();
	
//	var tipoArchivo 	= $("#id_tipo_archivo_pago");
//	var fechaProceso	= $("#fecha_proceso");
//	var bancoPago		= $("#id_bancos_local");
//		
//	/** validaciones para busqueda dc 2020/06/10 **/
//	if( tipoArchivo.val() == "" || tipoArchivo.val() == "0" )
//	{
//		tipoArchivo.notify( "Seleccione Tipo Archivo", { position:"buttom left", autoHideDelay: 2000} );	
//		return false;
//	}
//	
//	if( fechaProceso.val().includes("_")  || fechaProceso.val() == "" )
//	{
//		fechaProceso.notify( "Ingrese Fecha Proceso", { position:"buttom left", autoHideDelay: 2000} );	
//		return false;
//	}
//	
//	if( bancoPago.val() == "" || bancoPago.val() == "0"  )
//	{
//		bancoPago.notify( "Seleccione Banco ", { position:"buttom left", autoHideDelay: 2000} );	
//		return false;
//	}
//	
//	params = {fecha_proceso:fechaProceso.val(), id_tipo_archivo_pago:tipoArchivo.val(), id_bancos:bancoPago.val()};
//	$.ajax({
//		url:"index.php?controller=ArchivoPago&action=GenerarArchivoPago",
//		dataType:"json",
//		type:"POST",
//		data: params
//	}).done( function(x){
//		
//		if( x.estatus != undefined ){
//			
//			if(x.estatus == "OK"){
//				
//				var urlWeb  = "index.php?controller=ArchivoPago&action=DescargarArchivoPago";
//				var starget = "_blank";
//				var sFile   = x.urlFile;
//				var sNomFile= x.nombreFile;
//				var params  = {urlFile:sFile,nombreFile:sNomFile};
//				FormularioPost(urlWeb,starget,params); //este metodo manda ha descragar el archivo generado por el usuario
//				
//				swal( {
//					 title:"ARCHIVO RECAUDACIONES",
//					 dangerMode: false,
//					 text: " Archivo generado ",
//					 icon: "info"
//					})
//				
//			}
//			
//			if(x.estatus == "ERROR"){
//				swal({title:"ARCHIVO ERROR",text:x.mensaje,icon:x.icon});
//			}
//		}
//		
//		//console.log(x);
//	}).fail( function(xhr,status,error){
//		swal( {
//			 title:"ARCHIVO RECAUDACIONES",
//			 dangerMode: true,
//			 text: " Error con el Servidor Llamar al Administrador ",
//			 icon: "error"
//			})
//		console.log(xhr.responseText);
//	})
	
})


/**************************************************** DATATABLE ***************************************************/
//variable de vista
var view	= view || {};
view.fecha_proceso	= $("#fecha_proceso");
view.tipo_archivo	= $("#id_tipo_archivo_pago");
view.id_bancos	= $("#id_bancos_local");
view.id_usuario	= $("#ddl_usuario_departamento");
view.lista_pagos	= [];

//variable para dataTable
var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tbl_archivo_pago';
viewTable.params = { 'input_search': "" };
viewTable.contenedor = $("#div_listado_cuentas_pagar");

viewTable.params	= function(){ 
	var extenddatapost = { 'fecha_proceso': view.fecha_proceso.val(),
			'tipo_archivo': view.tipo_archivo.val(),
			'id_bancos': view.id_bancos.val(),
			'id_usuario': view.id_usuario.val(),
			'id_oficina': view.id_usuario.find('option:selected').data('oficina') ?? 0,
			};
	return extenddatapost;
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

var load_archivo_pago	= function(){
	
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({
	    'processing': true,
	    'serverSide': true,
	    'serverMethod': 'post',
	    'destroy' : true,
	    'ajax': {
	        'url':'index.php?controller=ArchivoPago&action=dtdatosArchivoPago',
	        'data': function ( d ) {
	            return $.extend( {}, d, viewTable.params() );
	            },
            'dataSrc': function ( json ) {            	
            	$("#generar_archivo_pago").attr("disabled",(json.data.length ? false : true));            	
                return json.data;
              }
	    },	
	    'lengthMenu': [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
	    'order': [[ 1, "desc" ]],
	    'columns': [	   
	    	{ data: 'opciones', orderable: false },
	    	{ data: 'numfila', orderable: false },
    		{ data: 'tipo'},
    		{ data: 'fecha'},
    		{ data: 'banco' },
    		{ data: 'tipo_pago'},
    		{ data: 'identificacion' },
    		{ data: 'beneficiario', orderable: false },
    		{ data: 'codigo_banco', orderable: false},
    		{ data: 'valor', orderable: false }
	    ],
	    'columnDefs': [
	        {className: "dt-center", targets:[0] },
	        {sortable: false, targets: [ 0,1,7,8,9 ] }
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

var fncargarBusqueda	= function(){
	
	var in_validacion = true;
	
	/** validaciones para busqueda dc 2020/09/22 **/
	if( view.tipo_archivo.val() == "" || view.tipo_archivo.val() == "0" )
	{
		view.tipo_archivo.notify( "Seleccione Tipo Archivo", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( view.fecha_proceso.val().includes("_")  || view.fecha_proceso.val() == "" )
	{
		view.fecha_proceso.notify( "Ingrese Fecha Proceso", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( view.id_bancos.val() == "" || view.id_bancos.val() == "0"  )
	{
		view.id_bancos.notify( "Seleccione Banco ", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( view.tipo_archivo.find('option:selected').text() == "CREDITOS" && view.id_usuario.val() == "0" )
	{
		view.id_usuario.notify( "Seleccione Usuario ", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	viewTable.tabla.ajax.reload();
			
}

var fnSeleccionaPagos	= function(a){
	var elemento = $(a);
	if( elemento.is(':checked') ) {
        $("input:checkbox.chk_pago_seleccionado").prop("checked", true);
    } else {
        $("input:checkbox.chk_pago_seleccionado").prop("checked", false);
    }
	
}
	
var fnSeleccionaPagosIndividuales	= function(a){
	var elemento = $(a);	
	if( $('input:checkbox.chk_pago_seleccionado').length )
	{
		if ($("input:checkbox.chk_pago_seleccionado").length == $("input:checkbox.chk_pago_seleccionado:checked").length) {  
			$("input:checkbox#chk_pagos_all").prop("checked", true);  
		} else {  
			$("input:checkbox#chk_pagos_all").prop("checked", false);  
		} 
	}else{
		$("input:checkbox#chk_pagos_all").prop("checked", false);  
	}	 	
}

var fngenerarArchivo	= function(a){
	
	swal({
		  title: "Generar Archivo",
		  text: "Continuaremos con la generación del archivo de pago",
		  icon: "info",
		  buttons: true,
	}).then((isConfirm) => {
		
		  if (isConfirm) 
		  {  
			  console.log("continuo con la generación");
			  
			  var listaPagosGenerar = [];
			  var contador = 0;
			  var msgerror = true;

			  $.each( $("input:checkbox.chk_pago_seleccionado"), function( index, valor){

			      var elemento = $(this);
			  	  var item	= {};
			  	  contador++;

			      if( elemento.is(":checked") )
			      {
			    	  item['index']	= contador;
			    	  item['id_archivo_pago']	= elemento.val();
			  		
			    	  listaPagosGenerar.push(item);
			    	  msgerror	= false;
			  		
			    	  if( isNaN(item.id_archivo_pago) || item.id_archivo_pago.length == 0 )
			    	  {
			    		  msgerror	= true;
				  	  }
			      }
			  				      
			  });
			  
			  if( !listaPagosGenerar.length )
			  { 
				  msgerror	= true; 
				  swal({title:'Generación Archivo',text:'No existen elementos seleccionados',dangerMode:true,icon:'warning'}); 
				  
			  }else
			  {		
				  console.log("VARIABLE DE VALIDACION");	 console.log(msgerror);
				  if( !msgerror )
				  {	
					  view.lista_pagos	= listaPagosGenerar;
					  
					  params = { 'lista_archivo_pagos' : JSON.stringify(listaPagosGenerar) };
					  
					  $.ajax({
							url:"index.php?controller=ArchivoPago&action=GenerarArchivoPago",
							dataType:"json",
							type:"POST",
							data: params
						}).done( function(x){
							
							if( x.estatus != undefined ){
								
								if(x.estatus == "OK"){
									
									var urlWeb  = "index.php?controller=ArchivoPago&action=DescargarArchivoPago";
									var starget = "_blank";
									var sFile   = x.urlFile;
									var sNomFile= x.nombreFile;
									var params  = {urlFile:sFile,nombreFile:sNomFile};
									FormularioPost(urlWeb,starget,params); //este metodo manda ha descragar el archivo generado por el usuario
									
									swal( {
										 title:"ARCHIVO RECAUDACIONES",
										 dangerMode: false,
										 text: " Archivo generado ",
										 icon: "info"
										});
									
									viewTable.tabla.ajax.reload(); // AQUI realiza la recarga de del datatable
									
									fnSeleccionaPagosIndividuales('input:checkbox.chk_pago_seleccionado'); // AQUI se realiza validacion de elemnetos check selecionados
								}
								
								if(x.estatus == "ERROR"){
									swal({title:"ARCHIVO ERROR",text:x.mensaje,icon:x.icon});
								}
							}
							
							//console.log(x);
						}).fail( function(xhr,status,error){
							swal( {
								 title:"ARCHIVO RECAUDACIONES",
								 dangerMode: true,
								 text: " Error con el Servidor Llamar al Administrador ",
								 icon: "error"
								})
							console.log(xhr.responseText);
						})
				  }else
				  {
					  swal({title:'Generación Archivo',text:'Revise elementos seleccionados',dangerMode:true,icon:'warning'});
				  }

			  }
			  
		  }else 
		  {
			  console.log("No continuo con la generación de archivo");
		  }
	});
	
	
}

/**************************************************** END DATATABLE ***************************************************/



