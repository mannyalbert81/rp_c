$(document).ready(function(){
	init();
})

function init(){
	consultaArchivos();
	
}

$("#btnGenerar").on("click",function(){
	
	let $entidadPatronal 	= $("#id_entidad_patronal"),
		$anioRecaudacion 	= $("#anio_recaudacion"),
		$mesRecaudacion 	= $("#mes_recaudacion"),
		$formatoRecaudacion	= $("#formato_recaudacion");
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var parametros ={id_entidad_patronal:$entidadPatronal.val(),
			anio_recaudacion:$anioRecaudacion.val(),
			mes_recaudacion:$mesRecaudacion.val(),
			formato_recaudacion:$formatoRecaudacion.val(),
			}   
	
	$.ajax({
		beforeSend:fnBeforeAction('Estamos procesado la informacion'),
		url:"index.php?controller=Recaudacion&action=GenerarRecaudacion",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if(x.respuesta == 1){
			
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "success",
				 timer: 2000,
				 button: false,
				});			
			buscarDatos();
				
		}
		if(x.respuesta == 2){
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "info",
				 timer: 2000,
				 button: false,
				});
				
			buscarDatos();	
		}
		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		swal.close();
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
	
	event.preventDefault();
})

function buscarDatos(){	
	
	/* aqui se va a buscar los datos */
	
	let $entidadPatronal	= $("#id_entidad_patronal"),
	$anioRecaudacion		= $("#anio_recaudacion"),
	$mesRecaudacion			= $("#mes_recaudacion"),
	$formatoRecaudacion		= $("#formato_recaudacion"),
	$busqueda				= $("#txtBuscarDatos");
	
	let _texto_validar = $formatoRecaudacion.val();
	
	switch(_texto_validar) {
	  case '1':
		  buscaAportesParticipes();
	    break;
	  case '2':
		  buscaAportesCreditos();
	    break;
	  default:
		  console.log('default');
	}
	
}

function buscaAportesParticipes(pagina=1){
	
	let $entidadPatronal	= $("#id_entidad_patronal"),
	$anioRecaudacion		= $("#anio_recaudacion"),
	$mesRecaudacion			= $("#mes_recaudacion"),
	$busqueda				= $("#mod_txtBuscarDatos"),
	$modal					= $("#mod_datos_archivo"),
	$cantidadRegistros		= $("#mod_cantidad_registros");
	
	let $divResultados = $("#mod_div_datos_recaudacion");	
	$divResultados.html('');	
	
	var parametros ={
		page:pagina,		
		busqueda:$busqueda.val(),
		id_entidad_patronal:$entidadPatronal.val(),
		anio_recaudaciones:$anioRecaudacion.val(),
		mes_recaudaciones:$mesRecaudacion.val()
		} 
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=ConsultaAportes",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		$cantidadRegistros.text(x.cantidadRegistros);
		$modal.modal('show');
		setStyleTabla("tbl_archivo_recaudaciones");
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
	
}

function buscaAportesCreditos(pagina=1){
	
	let $entidadPatronal	= $("#id_entidad_patronal"),
	$anioRecaudacion		= $("#anio_recaudacion"),
	$mesRecaudacion			= $("#mes_recaudacion"),
	$busqueda				= $("#mod_txtBuscarDatos"),
	$modal					= $("#mod_datos_archivo"),
	$cantidadRegistros		= $("#mod_cantidad_registros");
	
	let $divResultados = $("#mod_div_datos_recaudacion");	
	$divResultados.html('');	
	
	var parametros ={
		page:pagina,		
		busqueda:$busqueda.val(),
		id_entidad_patronal:$entidadPatronal.val(),
		anio_recaudaciones:$anioRecaudacion.val(),
		mes_recaudaciones:$mesRecaudacion.val()
		} 
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=ConsultaAportesCreditos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		$cantidadRegistros.text(x.cantidadRegistros);
		$modal.modal('show');
		setStyleTabla("tbl_archivo_recaudaciones");
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
}



function consultaArchivos( pagina=1,search=""){	
	
	var parametros ={page:pagina,peticion:'ajax',busqueda:search,}
	
	let $divResultados = $("#div_tabla_archivo_txt");
		$divResultados.html('');	
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=ConsultaArchivosGenerados",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		setStyleTabla("tbl_documentos_recaudaciones");
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
	
}



$("#txtBuscarhistorial").on("keyup",function(){
	$valorBuscar = $(this).val();
	consultaArchivos(1,$valorBuscar);
})

function editAporte(ObjLink){
	
	/* fn llamada en lado del controlador */
	/* fn para mostrar la ventana modal para cambiarvalor del archivo */
	
	//ObjLink.preventDefault();	
	let $link = $(ObjLink);
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=BuscarDatosArchivo",
		type:"POST",
		dataType:"json",
		data:{id_archivo_rcaudaciones_detalle:$link.data("idarchivo")}		
	}).done(function(x){
		
		console.log(x)
		mostrarModalCambioValor(x);
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err);
	});
	
	
}

function mostrarModalCambioValor(objJson){
	
	/* el parametro debe ser objeto json
	 * array de nombre es 'rsRecaudaciones' */
	let $modal	= $("#mod_recaudacion"),
		$array	= objJson.rsRecaudaciones[0],
		$tituloModal	= $modal.find('h4.modal-title');
	
	/* parametrizar valores a mostrar en Modal*/
	_formato_archivo	= $array.formato_archivo_recaudaciones
	
	switch(_formato_archivo){
		case 'DESCUENTOS CREDITOS':
			$tituloModal.text('VALORES CREDITOS A CAMBIAR');
		break;
		case 'DESCUENTOS APORTES':
			$tituloModal.text('VALORES APORTES A CAMBIAR');
		default:
			$tituloModal.text('');
	}
	
	$modal.find('#mod_cedula_participes').val($array.cedula_participes);
	$modal.find('#mod_nombres_participes').val($array.nombre_participes);
	$modal.find('#mod_apellidos_participes').val($array.apellido_participes);
	$modal.find('#mod_id_archivo_detalle').val($array.id_archivo_recaudaciones_detalle);
	$modal.find('#mod_valor_sistema').val($array.valor_sistema_archivo_recaudaciones_detalle);
	$modal.find('#mod_valor_edit').val($array.valor_final_archivo_recaudaciones_detalle);
	
	$modal.modal("show");
	
}

$("#btnEditRecaudacion").on("click",function(){
	
	let $miboton = $(this);
		$miboton.attr("disabled",true);
	let $modal = $("#mod_recaudacion");
	
	let $idArchivo = $modal.find('#mod_id_archivo_detalle'),
		$valorNuevo = $modal.find('#mod_valor_edit');	
	
	if(isNaN($valorNuevo.val())){
		$valorNuevo.notify("Ingrese Cantidad Valida",{ position:"buttom left", autoHideDelay: 2000});
		$miboton.attr("disabled",false);
		return false;
	}else{
		if($valorNuevo.val() <= 0){
			$valorNuevo.notify("Cantidad no puede ser igual o menor ",{ position:"buttom left", autoHideDelay: 2000});
			$miboton.attr("disabled",false);
			return false;
		}
	}
		
	var parametros = {id_archivo_recaudaciones_detalle:$idArchivo.val(),valor_final_archivo_recaudaciones_detalle:$valorNuevo.val()}
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=editAporte",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$modal.modal('hide');
		 buscarDatos();
		 console.log('llego');
		swal( {
				 title:"ACTUALIZACION VALOR ARCHIVO",
				 text: x.mensaje,
				 icon: "info"
				})
				
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
	}).always(function(){
		$miboton.attr("disabled",false);
	})
	
})

$("#btnDescargar").on("click",function(event){
	
	swal({
        title: "ARCHIVO RECAUDACION",
        text: "Se procedera a generar el archivo",
        icon: "warning",
        buttons: true,
      })
      .then((willContinue) => {
        if (willContinue) {
        	
        	DescargaArchivo();
        	
        } else {
        	swal.close();
        }
      }); 
	
})

function DescargaArchivo(){
	
	let $entidadPatronal 	= $("#id_entidad_patronal"),
	$anioRecaudaciones 		= $("#anio_recaudacion"),
	$mesRecaudaciones 		= $("#mes_recaudacion"),
	$formatoRecaudacion		= $("#formato_recaudacion");       	
	
	var parametros ={
		id_entidad_patronal:$entidadPatronal.val(),
		anio_recaudaciones:$anioRecaudaciones.val(),
		mes_recaudaciones:$mesRecaudaciones.val(),
		formato_recaudaciones:$formatoRecaudacion.val()
		} 
	

	$.ajax({
		url:"index.php?controller=Recaudacion&action=GeneraArchivo",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)    		
		swal( {
				 title:"RECAUDACIONES",
				 text: "Archivo generado",
				 icon: "success"
				});
		
		consultaArchivos();
				
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
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
	});   	
		
	
}


function verArchivo(linkArchivo){

	//objeto link
	let $link = $(linkArchivo);
	let parametros;
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		parametros = {"id_archivo_recaudaciones":$link.data("idarchivo")}
		
	}else{ return false; }	
	
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=Recaudacion&action=descargarArchivo");
    form.setAttribute("target", "_blank");   
    
    for (var i in parametros) {
        if (parametros.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = parametros[i];
            form.appendChild(input);
        }
    }
    
    document.body.appendChild(form); 
    form.submit();    
    document.body.removeChild(form);
}

function fnBeforeAction(mensaje){
	/*funcion que se ejecuta antes de realizar peticion ajax*/
	swal({
        title: "RECAUDACIONES",
        text: mensaje,
        icon: "view/images/ajax-loader.gif",        
      })
}

$("#btn_reload").on("click",function(){
	$valorBuscar = $("#txtBuscarhistorial").val();
	consultaArchivos(1,$valorBuscar);	
})

function setStyleTabla(ObjTabla){	
        	
	$("#"+ObjTabla).DataTable({
		paging: false,
        scrollX: true,
		searching: false,
        pageLength: 10,
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"html5buttons">lfrtipB',      
        buttons: [ ],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            "infoEmpty": "Mostrando 0 de 0 de 0 Registros",           
            "lengthMenu": "Mostrar _MENU_ Registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }

    });
	
	
 }
