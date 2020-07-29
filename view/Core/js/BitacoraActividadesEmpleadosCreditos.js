$(document).ready(function(){
	consultaBitacoraCreditos();
	
	$("input.seleccionado[type='checkbox']").on( 'change', function() {
		fnValidaEstado(this);
	});
})

$("#Guardar").on("click",function(event){
	
	let _fecha_registro = document.getElementById('fecha_registro').value;
	let _desde = document.getElementById('desde').value;
	let _hasta = document.getElementById('hasta').value;
	var _id_empleados = document.getElementById('id_empleados').value;
	var _id_participes = document.getElementById('hdn_id_participes').value;
	let _creditos = document.getElementById('creditos').value;
	let _cesantia = document.getElementById('cesantia').value;
	let _desafiliacion = document.getElementById('desafiliacion').value;
	let _superavit = document.getElementById('superavit').value;
	let _diferimiento = document.getElementById('diferimiento').value;
	let _refinanciamiento_reestructuracion = document.getElementById('refinanciamiento_reestructuracion').value;
	let _elaboracion_memorando = document.getElementById('elaboracion_memorando').value;
	let _otras_actividades = document.getElementById('otras_actividades').value;
	let _atencion_creditos = document.getElementById('atencion_creditos').value;
	let _entrega_documentos_creditos = document.getElementById('entrega_documentos_creditos').value;
	let _atencion_cesantias = document.getElementById('atencion_cesantias').value;
	let _entrega_documentos_cesantias = document.getElementById('entrega_documentos_cesantias').value;
	let _atencion_desafiliaciones = document.getElementById('atencion_desafiliaciones').value;
	let _entrega_documentos_desafiliaciones = document.getElementById('entrega_documentos_desafiliaciones').value;
	let _atencion_superavit = document.getElementById('atencion_superavit').value;
	let _entrega_documentos_superavit = document.getElementById('entrega_documentos_superavit').value;
	let _atencion_refinanciamiento_reestructuracion = document.getElementById('atencion_refinanciamiento_reestructuracion').value;
	let _entrega_documentos_refinanciamiento_reestructuracion = document.getElementById('entrega_documentos_refinanciamiento_reestructuracion').value;
	let _atencion_diferimiento = document.getElementById('atencion_diferimiento').value;
	let _claves = document.getElementById('claves').value;
	let _consultas_varias = document.getElementById('consultas_varias').value;
	var _id_bitacora_actividades_empleados_creditos = document.getElementById('id_bitacora_actividades_empleados_creditos').value;
	
	var parametros = {fecha_registro:_fecha_registro,
						desde:_desde,
						hasta:_hasta,
						id_empleados:_id_empleados,
						id_participes:_id_participes,
						creditos:_creditos,
						cesantia:_cesantia,
						desafiliacion:_desafiliacion,
						superavit:_superavit,
						diferimiento:_diferimiento,
						refinanciamiento_reestructuracion:_refinanciamiento_reestructuracion,
						elaboracion_memorando:_elaboracion_memorando,
						otras_actividades:_otras_actividades,
						atencion_creditos:_atencion_creditos,
						entrega_documentos_creditos:_entrega_documentos_creditos,
						atencion_cesantias:_atencion_cesantias,
						entrega_documentos_cesantias:_entrega_documentos_cesantias,
						atencion_desafiliaciones:_atencion_desafiliaciones,
						entrega_documentos_desafiliaciones:_entrega_documentos_desafiliaciones,
						atencion_superavit:_atencion_superavit,
						entrega_documentos_superavit:_entrega_documentos_superavit,
						atencion_refinanciamiento_reestructuracion:_atencion_refinanciamiento_reestructuracion,
						entrega_documentos_refinanciamiento_reestructuracion:_entrega_documentos_refinanciamiento_reestructuracion,
						atencion_diferimiento:_atencion_diferimiento,
						claves:_claves,
						consultas_varias:_consultas_varias,
						id_bitacora_actividades_empleados_creditos:_id_bitacora_actividades_empleados_creditos}
	
		
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=InsertaBitacoraCreditos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		
	swal({
  		  title: "Bitacora Ingresada",
  		  text: datos.mensaje,
  		  icon: "success",
  		  button: "Aceptar",
  		});
	
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		$("#id_bitacora_actividades_empleados_creditos").val(0);
		document.getElementById("frm_bitacora_creditos").reset();	
		consultaBitacoraCreditos();
	})

	event.preventDefault()
})


function editBitacoraCreditos(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=editBitacoraCreditos",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_creditos:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];		
			$("#fecha_registro").val(array.fecha_registro);
			$("#desde").val(array.desde);			
			$("#hasta").val(array.hasta);			
			$("#id_empleados").val(array.id_empleados);			
			$("#id_participes").val(array.id_participes);			
			$("#creditos").val(array.creditos);			
			$("#cesantia").val(array.cesantia);			
			$("#desafiliacion").val(array.desafiliacion);			
			$("#superavit").val(array.superavit);			
			$("#diferimiento").val(array.diferimiento);			
			$("#refinanciamiento_reestructuracion").val(array.refinanciamiento_reestructuracion);			
			$("#elaboracion_memorando").val(array.elaboracion_memorando);			
			$("#otras_actividades").val(array.otras_actividades);			
			$("#atencion_creditos").val(array.atencion_creditos);			
			$("#entrega_documentos_creditos").val(array.entrega_documentos_creditos);			
			$("#atencion_cesantias").val(array.atencion_cesantias);			
			$("#entrega_documentos_cesantias").val(array.entrega_documentos_cesantias);			
			$("#atencion_desafiliaciones").val(array.atencion_desafiliaciones);			
			$("#entrega_documentos_desafiliaciones").val(array.entrega_documentos_desafiliaciones);			
			$("#atencion_superavit").val(array.atencion_superavit);			
			$("#entrega_documentos_superavit").val(array.entrega_documentos_superavit);			
			$("#atencion_refinanciamiento_reestructuracion").val(array.atencion_refinanciamiento_reestructuracion);			
			$("#entrega_documentos_refinanciamiento_reestructuracion").val(array.entrega_documentos_refinanciamiento_reestructuracion);			
			$("#atencion_diferimiento").val(array.atencion_diferimiento);			
			$("#claves").val(array.claves);			
			$("#consultas_varias").val(array.consultas_varias);			
			$("#id_bitacora_actividades_empleados_creditos").val(array.id_bitacora_actividades_empleados_creditos);
			$("html, body").animate({ scrollTop: $(fecha_registro).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaBitacoraCreditos();
	})
	
	return false;
	
}

function delBitacoraCreditos(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=delBitacoraCreditos",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_creditos:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Bitacora",
		  		  text: "Registro Eliminado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
					
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaBitacoraCreditos();
	})
	
	return false;
}

function consultaBitacoraCreditos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=consultaBitacoraCreditos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#bitacora_creditos_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#cedula_participes").on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=autocompleteCedulaParticipes",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {
     	       	// Set selection
    			var id_participes	= $("#hdn_id_participes");
    			var cedula_participes	= $("#cedula_participes");
    			var nombre_participes	= $("#nombre_participes");
    			
    			if(ui.item.id == '')
    			{
    				id_participes.val("0");
        			cedula_participes.val("");
        			nombre_participes.val("");
    				return;
    			}
    			
    			id_participes.val(ui.item.id);
    			cedula_participes.val(ui.item.value);
    			nombre_participes.val(ui.item.nombre);
				    			     	     
     	    },
     	   appendTo: "",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null)
     		   {
     			 _elemento.notify("Digite Cedula Valida",{ position:"top center"});
     			 $("#hdn_id_participes").val("0");
     			 _elemento.val('');
     			 $("#nombre_participes").val("");
     			
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
    
})


var fnValidaEstado	= function(a){
	var elemento = $(a);
	if( elemento.is(':checked') ) {
        // Hacer algo si el checkbox ha sido seleccionado
		elemento.val(1);       
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
    	elemento.val(0);        
    }
}

