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
	
	if (_fecha_registro=="" || _fecha_registro.length<1 ){
		$("#fecha_registro").notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000}); return false;
	}
	
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
		
		setearElementos();	
		consultaBitacoraCreditos();
	})

	event.preventDefault()
})

var setearElementos	= function(){
	
	document.getElementById("frm_bitacora_creditos").reset();
	
	$("#id_bitacora_actividades_empleados_creditos").val(0);
	$("#hdn_id_participes").val(0);
	$("#fecha_registro").val("");
	$("#creditos").val("0");
	$("#cesantia").val("0");
	$("#desafiliacion").val("0");
	$("#superavit").val("0");
	$("#diferimiento").val("0");
	$("#refinanciamiento_reestructuracion").val("0");
	$("#elaboracion_memorando").val("");
	$("#otras_actividades").val("");
	$("#atencion_creditos").val("0");
	$("#entrega_documentos_creditos").val("0");
	$("#atencion_cesantias").val("0");
	$("#entrega_documentos_cesantias").val("0");
	$("#atencion_desafiliaciones").val("0");
	$("#entrega_documentos_desafiliaciones").val("0");
	$("#atencion_superavit").val("0");
	$("#entrega_documentos_superavit").val("0");
	$("#atencion_refinanciamiento_reestructuracion").val("0");
	$("#entrega_documentos_refinanciamiento_reestructuracion").val("0");
	$("#atencion_diferimiento").val("0");
	$("#claves").val("0");
	$("#consultas_varias").val("0");
	
}


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
			
			var valor_creditos = ( array.creditos == 't' ) ? "1" : "0";
			var valor_cesantia = ( array.cesantia == 't' ) ? "1" : "0";
			var valor_desafiliacion = ( array.desafiliacion == 't' ) ? "1" : "0";
			var valor_superavit = ( array.superavit == 't' ) ? "1" : "0";
			var valor_diferimiento = ( array.diferimiento == 't' ) ? "1" : "0";
			var valor_refinanciamiento_reestructuracion = ( array.refinanciamiento_reestructuracion == 't' ) ? "1" : "0";
			var valor_atencion_creditos = ( array.atencion_creditos == 't' ) ? "1" : "0";
			var valor_entrega_documentos_creditos = ( array.entrega_documentos_creditos == 't' ) ? "1" : "0";
			var valor_atencion_cesantias = ( array.atencion_cesantias == 't' ) ? "1" : "0";
			var valor_entrega_documentos_cesantias = ( array.entrega_documentos_cesantias == 't' ) ? "1" : "0";
			var valor_atencion_desafiliaciones = ( array.atencion_desafiliaciones == 't' ) ? "1" : "0";
			var valor_entrega_documentos_desafiliaciones = ( array.entrega_documentos_desafiliaciones == 't' ) ? "1" : "0";
			var valor_atencion_superavit = ( array.atencion_superavit == 't' ) ? "1" : "0";
			var valor_entrega_documentos_superavit = ( array.entrega_documentos_superavit == 't' ) ? "1" : "0";
			var valor_atencion_refinanciamiento_reestructuracion = ( array.atencion_refinanciamiento_reestructuracion == 't' ) ? "1" : "0";
			var valor_entrega_documentos_refinanciamiento_reestructuracion = ( array.entrega_documentos_refinanciamiento_reestructuracion == 't' ) ? "1" : "0";
			var valor_atencion_diferimiento = ( array.atencion_diferimiento == 't' ) ? "1" : "0";
			var valor_claves = ( array.claves == 't' ) ? "1" : "0";
			var valor_consultas_varias = ( array.consultas_varias == 't' ) ? "1" : "0";
			
			if(valor_creditos==1){
				
				$("#creditos").val(valor_creditos);
				$("#creditos").prop("checked",true);
				
			}else{
				$("#creditos").val(valor_creditos);
				
			}
			if(valor_cesantia==1){
				
				$("#cesantia").val(valor_cesantia);
				$("#cesantia").prop("checked",true);
				
			}else{
				$("#cesantia").val(valor_cesantia);
				
			}
			if(valor_desafiliacion==1){
				
				$("#desafiliacion").val(valor_desafiliacion);
				$("#desafiliacion").prop("checked",true);
				
			}else{
				$("#desafiliacion").val(valor_desafiliacion);
				
			}
			if(valor_superavit==1){
				
				$("#superavit").val(valor_superavit);
				$("#superavit").prop("checked",true);
				
			}else{
				$("#superavit").val(valor_superavit);
				
			}
			if(valor_diferimiento==1){
				
				$("#diferimiento").val(valor_diferimiento);
				$("#diferimiento").prop("checked",true);
				
			}else{
				$("#diferimiento").val(valor_diferimiento);
				
			}
			if(valor_refinanciamiento_reestructuracion==1){
				
				$("#refinanciamiento_reestructuracion").val(valor_refinanciamiento_reestructuracion);
				$("#refinanciamiento_reestructuracion").prop("checked",true);
				
			}else{
				$("#refinanciamiento_reestructuracion").val(valor_refinanciamiento_reestructuracion);
				
			}
			if(valor_atencion_creditos==1){
				
				$("#atencion_creditos").val(valor_atencion_creditos);
				$("#atencion_creditos").prop("checked",true);
				
			}else{
				$("#atencion_creditos").val(valor_atencion_creditos);
				
			}
			if(valor_entrega_documentos_creditos==1){
				
				$("#entrega_documentos_creditos").val(valor_entrega_documentos_creditos);
				$("#entrega_documentos_creditos").prop("checked",true);
				
			}else{
				$("#entrega_documentos_creditos").val(valor_entrega_documentos_creditos);
				
			}
			if(valor_atencion_cesantias==1){
				
				$("#atencion_cesantias").val(valor_atencion_cesantias);
				$("#atencion_cesantias").prop("checked",true);
				
			}else{
				$("#atencion_cesantias").val(valor_atencion_cesantias);
				
			}
			if(valor_entrega_documentos_cesantias==1){
				
				$("#entrega_documentos_cesantias").val(valor_entrega_documentos_cesantias);
				$("#entrega_documentos_cesantias").prop("checked",true);
				
			}else{
				$("#entrega_documentos_cesantias").val(valor_entrega_documentos_cesantias);
				
			}
			if(valor_atencion_desafiliaciones==1){
				
				$("#atencion_desafiliaciones").val(valor_atencion_desafiliaciones);
				$("#atencion_desafiliaciones").prop("checked",true);
				
			}else{
				$("#atencion_desafiliaciones").val(valor_atencion_desafiliaciones);
				
			}
			if(valor_entrega_documentos_desafiliaciones==1){
				
				$("#entrega_documentos_desafiliaciones").val(valor_entrega_documentos_desafiliaciones);
				$("#entrega_documentos_desafiliaciones").prop("checked",true);
				
			}else{
				$("#entrega_documentos_desafiliaciones").val(valor_entrega_documentos_desafiliaciones);
				
			}
			if(valor_atencion_superavit==1){
				
				$("#atencion_superavit").val(valor_atencion_superavit);
				$("#atencion_superavit").prop("checked",true);
				
			}else{
				$("#atencion_superavit").val(valor_atencion_superavit);
				
			}
			if(valor_entrega_documentos_superavit==1){
				
				$("#entrega_documentos_superavit").val(valor_entrega_documentos_superavit);
				$("#entrega_documentos_superavit").prop("checked",true);
				
			}else{
				$("#entrega_documentos_superavit").val(valor_entrega_documentos_superavit);
				
			}
			if(valor_atencion_refinanciamiento_reestructuracion==1){
				
				$("#atencion_refinanciamiento_reestructuracion").val(valor_atencion_refinanciamiento_reestructuracion);
				$("#atencion_refinanciamiento_reestructuracion").prop("checked",true);
				
			}else{
				$("#atencion_refinanciamiento_reestructuracion").val(valor_atencion_refinanciamiento_reestructuracion);
				
			}
			if(valor_entrega_documentos_refinanciamiento_reestructuracion==1){
				
				$("#entrega_documentos_refinanciamiento_reestructuracion").val(valor_entrega_documentos_refinanciamiento_reestructuracion);
				$("#entrega_documentos_refinanciamiento_reestructuracion").prop("checked",true);
				
			}else{
				$("#entrega_documentos_refinanciamiento_reestructuracion").val(valor_entrega_documentos_refinanciamiento_reestructuracion);
				
			}
			if(valor_atencion_diferimiento==1){
				
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				$("#atencion_diferimiento").prop("checked",true);
				
			}else{
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				
			}
			if(valor_claves==1){
				
				$("#claves").val(valor_claves);
				$("#claves").prop("checked",true);
				
			}else{
				$("#claves").val(valor_claves);
				
			}
			if(valor_consultas_varias==1){
				
				$("#consultas_varias").val(valor_consultas_varias);
				$("#consultas_varias").prop("checked",true);
				
			}else{
				$("#consultas_varias").val(valor_consultas_varias);
				
			}
			
			
			$("#fecha_registro").val(array.fecha_registro);
			$("#desde").val(array.desde);			
			$("#hasta").val(array.hasta);			
			$("#id_empleados").val(array.id_empleados);			
			$("#hdn_id_participes").val(array.id_participes);	
			$("#cedula_participes").val(array.cedula_participes);	
			$("#nombre_participes").val(array.nombres_participes);	
			$("#elaboracion_memorando").val(array.elaboracion_memorando);			
			$("#otras_actividades").val(array.otras_actividades);			
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
	var fecha_registro_desde = $("#fecha_registro_desde").val();
	var fecha_registro_hasta = $("#fecha_registro_hasta").val();
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosCreditos&action=consultaBitacoraCreditos",
		type:"POST",
		data:{page:_page,search:buscador,fecha_registro_desde:fecha_registro_desde,fecha_registro_hasta:fecha_registro_hasta,peticion:'ajax'}
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
     			 //_elemento.notify("Digite Cedula Valida",{ position:"top center"});
     			 $("#hdn_id_participes").val("0");
     			 _elemento.val('');
     			 $("#nombre_participes").val("");
     			
     		   }
     	   }
    	
    	}).focusout(function(event,ui) {
    		  
  		   
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

var fnMostrarReporte	= function(){
	
	var buscador = $("#buscador").val();
	var fecha_registro_desde = $("#fecha_registro_desde").val();
	var fecha_registro_hasta = $("#fecha_registro_hasta").val();
	
	var params = {
		page:1,search:buscador,fecha_registro_desde:fecha_registro_desde,fecha_registro_hasta:fecha_registro_hasta,peticion:'ajax'
	}
		
	var form = document.createElement("form");
	form.setAttribute("id", "frmReporte001");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=BitacoraActividadesEmpleadosCreditos&action=ReporteBitacoraCreditos");
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

