$GLOBAL_id_archivo_recaudaciones = 0;
$GLOBAL_id_descuentos_cabeza = 0;
$GLOBAL_tipo_descuento	= 0;
$(document).ready(function(){
	init();
})

function init(){
	 $("body").tooltip({ selector: '[data-toggle=tooltip]' });
	consultaArchivosRecaudacion();
	cargaEntidadPatronal();
	//consultaArchivos();			
}

function validaCambioMes(){
	
	let ddlMes = $("#mes_recaudacion");
	let ddlEntidadPatronal = $("#id_entidad_patronal");
	if( $("#mes_recaudacion").val() == 0 ){
		ddlEntidadPatronal.attr("disabled","true"); ddlEntidadPatronal.val(0)}else{ddlEntidadPatronal.removeAttr("disabled")}
	
}

function validaTipoArchivo(){
	/* not implement yet */
	let ddlEntidadPatronal = $("#id_entidad_patronal");	
	if(ddlEntidadPatronal.val() == 0){
		ddlEntidadPatronal.notify("Seleccione Una entidad",{ position:"buttom left", autoHideDelay: 2000});
	}else{
		/*matriz a devolver opcion 1 o 2 */
		
	}
} 

function cargaEntidadPatronal(){
	
	var ddlEntidad = $("#id_entidad_patronal");
	
	ddlEntidad.empty().append('<option value="0">--Seleccione--</option>');
	
	fetch('index.php?controller=RecaudacionGeneracionArchivo&action=cargaEntidadPatronal')
	  .then(function(response) {
	    //console.log(response);
	    return response.json();
	  })
	  .then(function(x) {
	    
	    var rsData    = x.data;	    
	    $.each(rsData,function(index,value){
	    	ddlEntidad.append('<option value="'+value.id_entidad_patronal+'">'+value.nombre_entidad_patronal+'</option>')
	    }); 
	        
	    
	  }).catch(()=>console.log('Error en la carga de Entidad Patronal'));
	
	
}

function cargaDescuentosFormatos(obj){
	
	var inid_entidad_patronal	= $(obj).val();
	var ddldescuentos = $("#id_descuentos_formatos");
	console.log(inid_entidad_patronal);
	ddldescuentos.attr("disabled",false);
	ddldescuentos.empty().append('<option value="0">--Seleccione--</option>');
	
	var params = {id_entidad_patronal:inid_entidad_patronal};
	
	$.ajax({
		url:'index.php?controller=RecaudacionGeneracionArchivo&action=cargaFormatoDescuentos',
		type:"POST",
		dataType:"json",
		data: params			
	}).done(function(x){
		var rsData    = x.data;	    
	    $.each(rsData,function(index,value){
	    	ddldescuentos.append('<option value="'+value.id_descuentos_formatos+'">'+value.nombre_descuentos_formatos+'</option>')
	    });
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	});
		
}

function listadocargaDescuentosFormatos(obj){
	
	var inid_entidad_patronal	= $(obj).val();
	var ddldescuentoslistado = $("#ddl_id_descuentos_formatos");
	ddldescuentoslistado.empty().append('<option value="0">--Seleccione--</option>');
	
	var params = {id_entidad_patronal:inid_entidad_patronal};
	
	$.ajax({
		url:'index.php?controller=RecaudacionGeneracionArchivo&action=cargaFormatoDescuentos',
		type:"POST",
		dataType:"json",
		data: params			
	}).done(function(x){
		var rsData    = x.data;	    
	    $.each(rsData,function(index,value){
	    	ddldescuentoslistado.append('<option value="'+value.id_descuentos_formatos+'">'+value.nombre_descuentos_formatos+'</option>')
	    });
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	});
		
}

$("#btnGenerar").on("click",function(){
	
	var $formulario = $("#frm_recaudacion");
	if ( $formulario.data('locked') && $formulario.data('locked') != undefined ){
		console.log("formulario bloaqueado"); return false;
	}
	
	let $entidadPatronal 	= $("#id_entidad_patronal"),
		$anioRecaudacion 	= $("#anio_recaudacion"),
		$mesRecaudacion 	= $("#mes_recaudacion"),
		$DescuentosFormatos	= $("#id_descuentos_formatos"),
		$formatoRecaudacion	= $("#formato_recaudacion");
	
	if($mesRecaudacion.val() == 0 ){
		$mesRecaudacion.notify("Seleccione Periodo A generar",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($entidadPatronal.val() == 0 ){
		$entidadPatronal.notify("Seleccione Entidad Patronal",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($DescuentosFormatos.val() == 0 ){
		$DescuentosFormatos.notify("Seleccione Formato Descuento",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if($formatoRecaudacion.val() == 0 ){
		$formatoRecaudacion.notify("Seleccione formato aportacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var parametros ={id_entidad_patronal:$entidadPatronal.val(),
			id_descuentos_formatos:$DescuentosFormatos.val(),
			anio_recaudacion:$anioRecaudacion.val(),
			mes_recaudacion:$mesRecaudacion.val(),
			formato_recaudacion:$formatoRecaudacion.val(),
			}   
	
	$.ajax({
		beforeSend:function(){ $formulario.data('locked', true); fnBeforeAction('Estamos procesado la informacion') },
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=GenerarRecaudacion",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr,status){ $formulario.data('locked', false); }
	}).done(function(x){
		console.log(x)
		if( x.estatus != undefined && x.estatus !="" ){
			
			swal( {
				 title:"ARCHIVO",
				 text: "Datos Generados, Revisar datos en el listado de archivos Generados ",
				 icon: "success",
				 timer: 2000,
				 button: true,
				});	
			
			//implementar si es necesario el que devuelva el ultimo insertado 			
			let id_archivo = ( x.id_archivo != undefined && x.id_archivo > 0 ) ? x.id_archivo : 0;
			$GLOBAL_id_archivo_recaudaciones=id_archivo;
			//buscarDatosInsertados(1); poner un mensaje de revisar archivos creados
			//buscarDatos();
			consultaArchivosRecaudacion(1);
		}
		
		/** code below is to show error from parcticipes without valor aportes **/
		if( x.mensajeAportes != undefined &&  x.mensajeAportes != "" ){
			
			swal.close();
			let modalAportes = $("#mod_participes_sin_aportes");			
			let arrayAportesIncompletos = x.dataAportes;
			let cantidadRegistros		= arrayAportesIncompletos.length;
			let tblParticipesAportes = $("#tbl_participes_sin_aportacion");
			tblParticipesAportes.find("#catidad_sin_aportes").text(cantidadRegistros);
			tblParticipesAportes.find("tbody").html("");
			$.each( arrayAportesIncompletos , function(index, value) {
				
				let $filaAportes = "<tr><td>" + (index + 1) +"</td><td>" +value.cedula_participes +"</td><td>" 
					+value.nombre_participes +"</td><td>" +value.apellido_participes +"</td></tr>";
				tblParticipesAportes.find("tbody").append($filaAportes);	
	  		});			
			modalAportes.modal("show");
			
			//console.log(arrayAportesIncompletos);
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

function buscarDatosInsertados(pagina=1){
	
	/* se hace una consulta a la variable global la cual debe estar seteada antes de llamar el metodo */
	let $id_archivo = $GLOBAL_id_archivo_recaudaciones;
	
	if($id_archivo <= 0){swal({title:"ERROR ARCHIVO",text:"identificador de tabla (datos recaudacion) no encontrado",dangerMode:true}); return false;}
		
	let $busqueda				= $("#mod_txtBuscarDatos_insertados"),
		$modal					= $("#mod_datos_archivo_insertados"),
		$cantidadRegistros		= $("#mod_cantidad_registros_insertados");
	
	let $divResultados = $("#mod_div_datos_recaudacion_insertados");	
	$divResultados.html('');
	
	var parametros ={
		page:pagina,		
		busqueda:$busqueda.val(),
		id_archivo_recaudaciones:$id_archivo
		} 
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=ConsultaDatosArchivo",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr,status){ toDataTableInsertados(); }
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		$cantidadRegistros.text(x.cantidadRegistros);
		$modal.modal('show');
		
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

function consultaArchivosRecaudacion( pagina=1 ){	
	
	let $divResultados = $("#div_tabla_archivo_txt");
	$divResultados.html('');
	
	let $ddlEntidadBuscar = $("#ddl_id_entidad_patronal"), 
	$txtAnioBuscar = $("#txt_anio_buscar"), 
	$ddlmesBuscar = $("#ddl_mes_buscar"), 
	$ddlid_descuento_formatos = $("#ddl_id_descuentos_formatos");
	
	let valEntidad,valAnio,valMes;
	valEntidad = ($ddlEntidadBuscar.val() == 0 || $ddlEntidadBuscar.val() == undefined ) ? 0 : $ddlEntidadBuscar.val();
	valAnio    = ($txtAnioBuscar.val() == "" || $txtAnioBuscar.val() == undefined ) ? 0 : $txtAnioBuscar.val();
	valMes     = ($ddlmesBuscar.val() == 0 || $ddlmesBuscar.val() == undefined ) ? 0 : $ddlmesBuscar.val();
	valFormatos= ($ddlid_descuento_formatos.val() == 0 || $ddlid_descuento_formatos.val() == undefined ) ? 0 : $ddlid_descuento_formatos.val();
	
	var parametros ={page:pagina,peticion:'ajax',busqueda:"",
			id_entidad_patronal:valEntidad,
			anio_recaudacion:valAnio,
			mes_recaudacion:valMes,
			id_descuentos_formatos:valFormatos}
	
	
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=ConsultaArchivoRecaudaciones",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr,status){
			setStyleTabla("tbl_documentos_recaudaciones");
		}
	}).done(function(x){
		//console.log(x)
		$divResultados.html(x.tablaHtml);	
		
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

function verDatosDescuentos(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	$GLOBAL_id_descuentos_cabeza	= id_cabeza_descuentos;
	$GLOBAL_tipo_descuento		= tipo_descuento;
	
	CargarDatosDescuentos(1);
		
}

function CargarDatosDescuentos(page){
	
	var id_descuentos_cabeza = $GLOBAL_id_descuentos_cabeza;
	var tipo_descuento		= $GLOBAL_tipo_descuento;
	var busqueda			= $("#mod_txtBuscarDatos").val();
		
	var params = {
			"id_descuentos_cabeza":id_descuentos_cabeza,
			"tipo_descuento":tipo_descuento,
			"page":page,
			"busqueda":busqueda
	}
	
	var vtnmodal = $("#mod_datos_archivo");
	var divmodal = $("#mod_div_datos_recaudacion");
	var tblmodal = $("#tbl_archivo_recaudaciones_insertados");
	var pagmodal = $("#mod_paginacion_datos_descuentos");
		
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=CargarDatosDescuentos",
		type:"POST",
		dataType:"json",
		data:params,
		complete:function(xhr,status){ }
	}).done(function(x){
		
		if( x.tablaHtml != undefined && x.tablaHtml != "" ){
			tblmodal.empty();
			tblmodal.append( x.tablaHtml );
			pagmodal.html("");
			pagmodal.html( x.paginacion );
			vtnmodal.modal("show");
		}
						
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
	})
}



function genArchivoDetallado(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
	
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=genArchivoDetallado");
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

function genArchivoEntidad(linkArchivo){
	
	let $link = $(linkArchivo);	
		
	var id_cabeza_descuentos	= $link.data("iddescuentos");
	var tipo_descuento			= $link.data("codtipodescuento"); //aqui viene para definir si es descuentos por aportes o por creditos 
		
	if( id_cabeza_descuentos <= 0 || id_cabeza_descuentos == "" || id_cabeza_descuentos == undefined ){
		return false;
	}	
	if( tipo_descuento <= 0 || tipo_descuento == "" || tipo_descuento == undefined ){
		return false;
	}
	
	var params = {
			"id_descuentos_cabeza":id_cabeza_descuentos,
			"tipo_descuento":tipo_descuento
	}
		
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=RecaudacionGeneracionArchivo&action=genArchivoEntidad");
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
    
    //form.setAttribute("action", "index.php?controller=Recaudacion&action=descargarArchivo");
	
	
	
	
//	let $link = $(linkArchivo);
//	let parametros;
//	
//	if(parseInt($link.data("idarchivo")) > 0){
//		
//		parametros = {"id_archivo_recaudaciones":$link.data("idarchivo")}
//		
//	}else{ return false; }	
//	
//	$.ajax({
//		url:"index.php?controller=Recaudacion&action=genArchivoEntidad",
//		type:"POST",
//		dataType:"json",
//		data:parametros,
//		complete:function(xhr,status){}
//	}).done(function(x){
//		console.log(x)
//		if(x.mensaje != undefined && x.mensaje == "archivo generado" ){
//			
//			swal( {
//				 title:"ARCHIVO RECAUDACIONES",
//				 dangerMode: false,
//				 text: " Archivo generado ",
//				 icon: "info"
//				})
//			
//			var formParametros = {"id_archivo_recaudaciones":$link.data("idarchivo"),"tipo_archivo_recaudaciones":"entidad"};
//			var form = document.createElement("form");
//		    form.setAttribute("method", "post");
//		    form.setAttribute("action", "index.php?controller=Recaudacion&action=descargarArchivo");
//		    form.setAttribute("target", "_blank");   
//		    
//		    for (var i in formParametros) {
//		        if (formParametros.hasOwnProperty(i)) {
//		            var input = document.createElement('input');
//		            input.type = 'hidden';
//		            input.name = i;
//		            input.value = formParametros[i];
//		            form.appendChild(input);
//		        }
//		    }
//		    
//		    document.body.appendChild(form); 
//		    form.submit();    
//		    document.body.removeChild(form);
//			
//		}
//				
//	}).fail(function(xhr,status,error){
//		var err = xhr.responseText
//		console.log(err)
//		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
//		 	if( mensaje !== null ){
//			 var resmsg = mensaje[1];
//			 swal( {
//				 title:"Error",
//				 dangerMode: true,
//				 text: resmsg.replace("|","\n"),
//				 icon: "error"
//				})
//		 	}
//	})
	
	
}

function ValidarEdicionGenerados(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		parametros = {"id_archivo_recaudaciones":$link.data("idarchivo")}
		
	}else{ return false; }	
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=validaDatosGenerados",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr,status){}
	}).done(function(x){
		console.log(x)
		if(x.mensaje != undefined && x.mensaje == "OK" ){
			
			$GLOBAL_id_archivo_recaudaciones=$link.data("idarchivo");
			$("#mod_txtBuscarDatos").val("");
			mostarGenerados();
		}
				
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

function mostarGenerados(pagina=1){	
	
	var id_archivo_recaudaciones = $GLOBAL_id_archivo_recaudaciones; //se toma el dato de la variable global la cual de estar seteada antes de empezar funcion 	
	let $divResultados = $("#mod_div_datos_recaudacion"),
		$modal				= $("#mod_datos_archivo"),
		$busqueda			= $("#mod_txtBuscarDatos"),
		$cantidadRegistros	= $("#mod_cantidad_registros");	
	$divResultados.html('');
	
	console.log("DATOS GLOBAL -->"+id_archivo_recaudaciones);
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=ConsultaDatosEditar",
		type:"POST",
		dataType:"json",
		data:{"id_archivo_recaudaciones":id_archivo_recaudaciones,"page":pagina,"busqueda":$busqueda.val()},
		complete:function(xhr,status){ setStyleTabla("tbl_archivo_recaudaciones"); }
	}).done(function(x){
		console.log("llego aca fn mostrarGenerados");
		console.log(x);
		if(x.tablaHtml != undefined && x.tablaHtml != "" ){
			$divResultados.html(x.tablaHtml);	
			$cantidadRegistros.text(x.cantidadRegistros);
			$modal.modal('show');
		}		
				
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

function eliminarRegistro(linkArchivo){
	
	let $link = $(linkArchivo);
	let parametros;
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		parametros = {"id_archivo_recaudaciones":$link.data("idarchivo")}
		
	}else{ return false; }
	
	swal({
		 title: "Eliminar Registro Seleccionado?", 
		 text: "los datos relacionados a este registro se perderan", 
		 type: "warning",		 
		 closeModal: false,
		 buttons: [
		        'No',
		        'Si,Continuar!'
		      ],
	     /*dangerMode: true,*/
	   }).then((isConfirm) => {
	          if (isConfirm) {
	        	  $.ajax({
			      		url:"index.php?controller=Recaudacion&action=eliminarRegistro",
			      		type:"POST",
			      		dataType:"json",
			      		data:parametros,
			      		complete:function(xhr,status){}
			      	}).done(function(x){
			      		console.log(x)
			      		if(x.mensaje != undefined && x.mensaje == "OK" ){			      			
			      			swal({
			      				 title:"RESPUESTA",
			      				 text: "Archivo de datos de la Entidad Patronal han sido eliminados",
			      				 icon: "info"
			      				})
			      				consultaArchivosRecaudacion(1);
			      		}
			      				
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
	            } else {
	              swal("Datos no eliminados");
	            }
      });

	
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
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=BuscarDatosArchivo",
		type:"POST",
		dataType:"json",
		data:{"id_descuentos_detalle":$link.data("iddescuentos")}		
	}).done(function(x){
		
		if( x.data != undefined && x.data != null ){
			
			var rsdata = x.data[0];
			
			let $modal	= $("#mod_recaudacion"),
			$tituloModal	= $modal.find('h4.modal-title');
		
			$tituloModal.text('VALORES APORTES A CAMBIAR');
			
			$modal.find('#mod_cedula_participes').val( rsdata.cedula_participes);
			$modal.find('#mod_nombres_participes').val( rsdata.nombre_participes);
			$modal.find('#mod_apellidos_participes').val( rsdata.apellido_participes);
			$modal.find('#mod_id_descuentos_detalle').val( rsdata.id_detalle);
			$modal.find('#mod_valor_sistema').val( rsdata.valor_descuento);
			$modal.find('#mod_valor_edit').val( rsdata.valor_descuento1 );
			
			$modal.modal("show");			
			
		}
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err);
	});
	
	
}


$("#btnEditRecaudacion").on("click",function(){
	
	let $miboton = $(this);
		$miboton.attr("disabled",true);
	let $modal = $("#mod_recaudacion");
	
	let $iddescuento = $modal.find('#mod_id_descuentos_detalle'),
		$valorNuevo = $modal.find('#mod_valor_edit');	
	
	if( isNaN( $valorNuevo.val() ) ){
		$valorNuevo.notify("Ingrese Cantidad Valida",{ position:"buttom left", autoHideDelay: 2000});
		$miboton.attr("disabled",false);
		return false;
	}else{
		if( $valorNuevo.val() <= 0 ){
			$valorNuevo.notify("Cantidad no puede ser igual o menor ",{ position:"buttom left", autoHideDelay: 2000});
			$miboton.attr("disabled",false);
			return false;
		}
	}
		
	var parametros = { "id_descuentos_detalle": $iddescuento.val(), "valor_descuentos": $valorNuevo.val()}
	
	$.ajax({
		url:"index.php?controller=RecaudacionGeneracionArchivo&action=editAporte",
		type:"POST",
		dataType:"json",
		data:parametros,
		complete:function(xhr){ $miboton.attr("disabled",false); }
	}).done(function(x){
		
		if( x.estatus != undefined && x.estatus != "" ){
			
			swal( {
				 title:"ACTUALIZACION VALOR",
				 text: x.mensaje,
				 icon: "info"
				});
			$modal.modal('hide');
			CargarDatosDescuentos(1);
			
		}
		
				
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
		beforeSend:fnBeforeAction("Estamos procesando Archivo"),
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


function setStyleTabla(ObjTabla){
	
	//objetoTabla.dataTable().fnDestroy();
	if ( ! $.fn.DataTable.isDataTable( "#"+ObjTabla) ) {
		var objetoTabla = $("#"+ObjTabla);
		objetoTabla.DataTable({
			scrollY: '50vh',
			/*"scrollX": true,*/
			"scrollCollapse": true,
			"ordering":false,
			"paging":false,
			"searching":false,
			"info":false
			});
	}	
			
 }

function toDataTableInsertados(){
	/*verificar el nombre de la tabla a dar formato*/
	if ( ! $.fn.DataTable.isDataTable( "#tbl_archivo_recaudaciones_insertados" ) ) {
		var objetoTabla = $("#tbl_archivo_recaudaciones_insertados");
		objetoTabla.DataTable({
			scrollY: '50vh',
			/*"scrollX": true,*/
			"scrollCollapse": true,
			"ordering":false,
			"paging":false,
			"searching":false,
			"info":false
			});
	}	
}

function toDataTableInsertados(nombreTabla){
	/*verificar el nombre de la tabla a dar formato*/
	var tabla = $("#");
	if ( ! $.fn.DataTable.isDataTable( "#"+ObjTabla) ) {
		var objetoTabla = $("#"+ObjTabla);
		objetoTabla.DataTable({
			scrollY: '50vh',
			/*"scrollX": true,*/
			"scrollCollapse": true,
			"ordering":false,
			"paging":false,
			"searching":false,
			"info":false
			});
	}	
}

function toDataTableInsertados(){
	/*verificar el nombre de la tabla a dar formato*/
	if ( ! $.fn.DataTable.isDataTable( "#tbl_documentos_recaudaciones" ) ) {
		var objetoTabla = $("#tbl_documentos_recaudaciones");
		objetoTabla.DataTable({
			scrollY: '50vh',
			/*"scrollX": true,*/
			"scrollCollapse": true,
			"ordering":false,
			"paging":false,
			"searching":false,
			"info":false
			});
	}	
}
