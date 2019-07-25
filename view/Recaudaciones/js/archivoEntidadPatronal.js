$(document).ready(function(){
	init();
})

function init(){
	consultaArchivos();
	
}

$("#btnSimular").on("click",function(){
	
	let $entidadPatronal = $("#id_entidad_patronal"),
		$anioRecaudacion = $("#anio_recaudacion"),
		$mesRecaudacion = $("#mes_recaudacion");
	
	var parametros ={id_entidad_patronal:$entidadPatronal.val(),
			anio_recaudacion:$anioRecaudacion.val(),
			mes_recaudacion:$mesRecaudacion.val()
			}   
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=RecaudacionSimular",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if(x.respuesta == 1){
			swal( {
				 title:"ARCHIVO",
				 text: "Revisar los datos de aportacion de los participes",
				 icon: "success"
				})			
		}
		if(x.respuesta == 2){
			swal( {
				 title:"ARCHIVO",
				 text: x.mensaje,
				 icon: "info"
				})			
		}
		
		consultaRecaudaciones(1);
		
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
	
	event.preventDefault();
})

function consultaRecaudaciones( pagina,search=""){
	
	let $entidadPatronal = $("#id_entidad_patronal"),
	$anioRecaudacion = $("#anio_recaudacion"),
	$mesRecaudacion = $("#mes_recaudacion");
	
	let $divResultados = $("#div_tabla_archivo");	
	$divResultados.html('');

	var parametros ={page:pagina,peticion:'ajax',busqueda:search,
		id_entidad_patronal:$entidadPatronal.val(),
		anio_recaudacion:$anioRecaudacion.val(),
		mes_recaudacion:$mesRecaudacion.val()
		} 
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=indexRecaudacionAP",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		generaTabla("tbl_archivo_recaudaciones");
		
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

function consultaArchivos( pagina,search=""){	
	
	var parametros ={page:pagina,peticion:'ajax',busqueda:search,}
	
	let $divResultados = $("#div_tabla_archivo_txt");
		$divResultados.html('');	
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=indexArchivosAP",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$divResultados.html(x.tablaHtml);
		generaTabla("tbl_documentos_recaudaciones");
		
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

function generaTabla(ObjTabla){	
	
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

$("#txtBuscar").on("keyup",function(){
	$valorBuscar = $(this).val();
	consultaRecaudaciones(1,$valorBuscar);
})

$("#txtBuscarhistorial").on("keyup",function(){
	$valorBuscar = $(this).val();
	consultaArchivos(1,$valorBuscar);
})

function editAporte(ObjLink){
	
	//ObjLink.preventDefault();	
	let $link = $(ObjLink),
		$modal = $("#mod_recaudacion");	
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		$modal.find('#mod_metodo_descuento').val($link.data("metodo_descuento"));
		$modal.find('#mod_id_archivo').val($link.data("idarchivo"));
		$modal.find('#mod_valor_sistema').val($link.data("valorinicial"));
		$modal.find('#mod_valor_edit').val($link.data("valorfinal"));		
		$modal.modal("show");
		
	}
	
}

$("#btnEditRecaudacion").on("click",function(){
	
	let $miboton = $(this);
		$miboton.attr("disabled",true);
	let $modal = $("#mod_recaudacion");
	
	let $idArchivo = $modal.find('#mod_id_archivo'),
		$valorNuevo = $modal.find('#mod_valor_edit');	
	
	if(isNaN($valorNuevo.val())){
		$valorNuevo.notify("Ingrese Cantidad Valida",{ position:"buttom left", autoHideDelay: 2000});
		$miboton.attr("disabled",false);
		return false;
	}
		
	var parametros = {id_archivo_recaudaciones:$idArchivo.val(),valor_final_archivo_recaudaciones:$valorNuevo.val()}
	
	$.ajax({
		url:"index.php?controller=Recaudacion&action=editAporte",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		$modal.modal('hide');
		consultaRecaudaciones(1);
		swal( {
				 title:"ACTUALIZACION",
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

$("#btnGenerar").on("click",function(event){
	
	swal({
        title: "ARCHIVO RECAUDACION",
        text: "Se procedera a generar el archivo",
        icon: "warning",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        	
        	let $entidadPatronal = $("#id_entidad_patronal"),
        	$anioRecaudacion = $("#anio_recaudacion"),
        	$mesRecaudacion = $("#mes_recaudacion");       	
        	
        	var parametros ={
        		id_entidad_patronal:$entidadPatronal.val(),
        		anio_recaudacion:$anioRecaudacion.val(),
        		mes_recaudacion:$mesRecaudacion.val()
        		} 
    		
    	
    	$.ajax({
    		url:"index.php?controller=Recaudacion&action=gen1",
    		type:"POST",
    		dataType:"json",
    		data:parametros
    	}).done(function(x){
    		console.log(x)    		
    		swal( {
    				 title:"RECAUDACIONES",
    				 text: "Archivo generado",
    				 icon: "success"
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
    	}).always(function(){ consultaRecaudaciones(1);})    	
        	
        	
        	
        } else {
        	swal({
                title: "ARCHIVO RECAUDACION",
                text: "Cancelacion generacion archivo",
                icon: "view/images/capremci_load.gif",
                dangerMode: true
              })
        }
      }); 
	
})

function verArchivo(linkArchivo){

	//objeto link
	let $link = $(linkArchivo);
	let parametros;
	
	if(parseInt($link.data("idarchivo")) > 0){
		
		parametros = {"id_documentos_recaudaciones":$link.data("idarchivo")}
		
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

$("#btn_reload").on("click",function(){
	$valorBuscar = $("#txtBuscarhistorial").val();
	consultaArchivos(1,$valorBuscar);	
})