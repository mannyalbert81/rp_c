$(document).ready(function(){
	consultaBitacoraRecaudaciones();
	
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
	let _cesantia = document.getElementById('cesantia').value;
	let _desafiliacion = document.getElementById('desafiliacion').value;
	let _creditos_en_mora = document.getElementById('creditos_en_mora').value;
	let _aportes = document.getElementById('aportes').value;
	let _diferimiento = document.getElementById('diferimiento').value;
	let _moras = document.getElementById('moras').value;
	let _credito = document.getElementById('credito').value;
	let _aporte = document.getElementById('aporte').value;
	let _envio_archivo_entidad_patronal = document.getElementById('envio_archivo_entidad_patronal').value;
	let _recepcion_archivo_entidad_patronal = document.getElementById('recepcion_archivo_entidad_patronal').value;
	let _carga_archivo_banco = document.getElementById('carga_archivo_banco').value;
	let _carga_archivo_sistema = document.getElementById('carga_archivo_sistema').value;
	let _registro_depositos_manuales = document.getElementById('registro_depositos_manuales').value;
	let _identificacion_dsc = document.getElementById('identificacion_dsc').value;
	let _elaboracion_memorando = document.getElementById('elaboracion_memorando').value;
	let _otras_actividades_desarrolladas = document.getElementById('otras_actividades_desarrolladas').value;
	let _atencion_cesantias = document.getElementById('atencion_cesantias').value;
	let _atencion_desafiliaciones = document.getElementById('atencion_desafiliaciones').value;
	let _atencion_creditos_en_mora = document.getElementById('atencion_creditos_en_mora').value;
	let _atencion_aportes = document.getElementById('atencion_aportes').value;
	let _atencion_diferimiento = document.getElementById('atencion_diferimiento').value;
	let _atencion_refinanciamiento_reestructuracion = document.getElementById('atencion_refinanciamiento_reestructuracion').value;
	let _claves = document.getElementById('claves').value;
	let _consultas_varias = document.getElementById('consultas_varias').value;
	var _id_bitacora_actividades_empleados_recaudaciones = document.getElementById('id_bitacora_actividades_empleados_recaudaciones').value;

	if (_fecha_registro=="" || _fecha_registro.length<1 ){
		$("#fecha_registro").notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000}); return false;
	}
	
	var parametros = {fecha_registro:_fecha_registro,
						desde:_desde,
						hasta:_hasta,
						id_empleados:_id_empleados,
						id_participes:_id_participes,
						cesantia:_cesantia,
						desafiliacion:_desafiliacion,
						creditos_en_mora:_creditos_en_mora,
						aportes:_aportes,
						diferimiento:_diferimiento,
						moras:_moras,
						credito:_credito,
						aporte:_aporte,
						envio_archivo_entidad_patronal:_envio_archivo_entidad_patronal,
						recepcion_archivo_entidad_patronal:_recepcion_archivo_entidad_patronal,
						carga_archivo_banco:_carga_archivo_banco,
						carga_archivo_sistema:_carga_archivo_sistema,
						registro_depositos_manuales:_registro_depositos_manuales,
						identificacion_dsc:_identificacion_dsc,
						elaboracion_memorando:_elaboracion_memorando,
						otras_actividades_desarrolladas:_otras_actividades_desarrolladas,
						atencion_cesantias:_atencion_cesantias,
						atencion_desafiliaciones:_atencion_desafiliaciones,
						atencion_creditos_en_mora:_atencion_creditos_en_mora,
						atencion_aportes:_atencion_aportes,
						atencion_diferimiento:_atencion_diferimiento,
						atencion_refinanciamiento_reestructuracion:_atencion_refinanciamiento_reestructuracion,
						claves:_claves,
						consultas_varias:_consultas_varias,
						id_bitacora_actividades_empleados_recaudaciones:_id_bitacora_actividades_empleados_recaudaciones}
	
		
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=InsertaBitacoraRecaudaciones",
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
		consultaBitacoraRecaudaciones();
	})

	event.preventDefault()
})

var setearElementos	= function(){
	
	document.getElementById("frm_bitacora_recaudaciones").reset();
	$("#id_bitacora_actividades_empleados_recaudaciones").val(0);
	$("#hdn_id_participes").val(0);
	$("#fecha_registro").val("");
	$("#cesantia").val("0");
	$("#desafiliacion").val("0");
	$("#creditos_en_mora").val("0");
	$("#aportes").val("0");
	$("#diferimiento").val("0");
	$("#moras").val("0");
	$("#credito").val("0");
	$("#aporte").val("0");
	$("#envio_archivo_entidad_patronal").val("0");
	$("#recepcion_archivo_entidad_patronal").val("0");
	$("#carga_archivo_banco").val("0");
	$("#carga_archivo_sistema").val("0");
	$("#registro_depositos_manuales").val("0");
	$("#identificacion_dsc").val("0");
	$("#elaboracion_memorando").val("");
	$("#otras_actividades_desarrolladas").val("");
	$("#atencion_cesantias").val("0");
	$("#atencion_diferimiento").val("0");
	$("#atencion_desafiliaciones").val("0");
	$("#atencion_creditos_en_mora").val("0");
	$("#atencion_aportes").val("0");
	$("#atencion_diferimiento").val("0");
	$("#atencion_refinanciamiento_reestructuracion").val("0");
	$("#claves").val("0");
	$("#consultas_varias").val("0");
	
}


function editBitacoraRecaudaciones(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=editBitacoraRecaudaciones",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_recaudaciones:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];	
			
			var valor_cesantia = ( array.cesantia == 't' ) ? "1" : "0";
			var valor_desafiliacion = ( array.desafiliacion == 't' ) ? "1" : "0";
			var valor_creditos_en_mora = ( array.creditos_en_mora == 't' ) ? "1" : "0";
			var valor_aportes = ( array.aportes == 't' ) ? "1" : "0";
			var valor_diferimiento = ( array.diferimiento == 't' ) ? "1" : "0";
			var valor_moras = ( array.moras == 't' ) ? "1" : "0";
			var valor_credito = ( array.credito == 't' ) ? "1" : "0";
			var valor_aporte = ( array.aporte == 't' ) ? "1" : "0";
			var valor_envio_archivo_entidad_patronal = ( array.envio_archivo_entidad_patronal == 't' ) ? "1" : "0";
			var valor_recepcion_archivo_entidad_patronal = ( array.recepcion_archivo_entidad_patronal == 't' ) ? "1" : "0";
			var valor_carga_archivo_banco = ( array.carga_archivo_banco == 't' ) ? "1" : "0";
			var valor_carga_archivo_sistema = ( array.carga_archivo_sistema == 't' ) ? "1" : "0";
			var valor_registro_depositos_manuales = ( array.registro_depositos_manuales == 't' ) ? "1" : "0";
			var valor_identificacion_dsc = ( array.identificacion_dsc == 't' ) ? "1" : "0";
			var valor_atencion_cesantias = ( array.atencion_cesantias == 't' ) ? "1" : "0";
			var valor_atencion_diferimiento = ( array.atencion_diferimiento == 't' ) ? "1" : "0";
			var valor_atencion_desafiliaciones = ( array.atencion_desafiliaciones == 't' ) ? "1" : "0";
			var valor_atencion_creditos_en_mora = ( array.atencion_creditos_en_mora == 't' ) ? "1" : "0";
			var valor_atencion_aportes = ( array.atencion_aportes == 't' ) ? "1" : "0";
			var valor_atencion_diferimiento = ( array.atencion_diferimiento == 't' ) ? "1" : "0";
			var valor_atencion_refinanciamiento_reestructuracion = ( array.atencion_refinanciamiento_reestructuracion == 't' ) ? "1" : "0";
			var valor_claves = ( array.claves == 't' ) ? "1" : "0";
			var valor_consultas_varias = ( array.consultas_varias == 't' ) ? "1" : "0";
				
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
			if(valor_creditos_en_mora==1){
				
				$("#creditos_en_mora").val(valor_creditos_en_mora);
				$("#creditos_en_mora").prop("checked",true);
				
			}else{
				$("#creditos_en_mora").val(valor_creditos_en_mora);
				
			}
			if(valor_aportes==1){
				
				$("#aportes").val(valor_aportes);
				$("#aportes").prop("checked",true);
				
			}else{
				$("#aportes").val(valor_aportes);
				
			}
			if(valor_diferimiento==1){
				
				$("#diferimiento").val(valor_diferimiento);
				$("#diferimiento").prop("checked",true);
				
			}else{
				$("#diferimiento").val(valor_diferimiento);
				
			}
			if(valor_moras==1){
				
				$("#moras").val(valor_moras);
				$("#moras").prop("checked",true);
				
			}else{
				$("#moras").val(valor_moras);
				
			}
			if(valor_credito==1){
				
				$("#credito").val(valor_credito);
				$("#credito").prop("checked",true);
				
			}else{
				$("#credito").val(valor_credito);
				
			}
			if(valor_aporte==1){
				
				$("#aporte").val(valor_aporte);
				$("#aporte").prop("checked",true);
				
			}else{
				$("#aporte").val(valor_aporte);
				
			}
			if(valor_envio_archivo_entidad_patronal==1){
				
				$("#envio_archivo_entidad_patronal").val(valor_envio_archivo_entidad_patronal);
				$("#envio_archivo_entidad_patronal").prop("checked",true);
				
			}else{
				$("#envio_archivo_entidad_patronal").val(valor_envio_archivo_entidad_patronal);
				
			}
			if(valor_recepcion_archivo_entidad_patronal==1){
				
				$("#recepcion_archivo_entidad_patronal").val(valor_recepcion_archivo_entidad_patronal);
				$("#recepcion_archivo_entidad_patronal").prop("checked",true);
				
			}else{
				$("#recepcion_archivo_entidad_patronal").val(valor_recepcion_archivo_entidad_patronal);
				
			}
			if(valor_carga_archivo_banco==1){
				
				$("#carga_archivo_banco").val(valor_carga_archivo_banco);
				$("#carga_archivo_banco").prop("checked",true);
				
			}else{
				$("#carga_archivo_banco").val(valor_carga_archivo_banco);
				
			}
			if(valor_carga_archivo_sistema==1){
				
				$("#carga_archivo_sistema").val(valor_carga_archivo_sistema);
				$("#carga_archivo_sistema").prop("checked",true);
				
			}else{
				$("#carga_archivo_sistema").val(valor_carga_archivo_sistema);
				
			}
			if(valor_registro_depositos_manuales==1){
				
				$("#registro_depositos_manuales").val(valor_registro_depositos_manuales);
				$("#registro_depositos_manuales").prop("checked",true);
				
			}else{
				$("#registro_depositos_manuales").val(valor_registro_depositos_manuales);
				
			}
			if(valor_identificacion_dsc==1){
				
				$("#identificacion_dsc").val(valor_identificacion_dsc);
				$("#identificacion_dsc").prop("checked",true);
				
			}else{
				$("#identificacion_dsc").val(valor_identificacion_dsc);
				
			}
			if(valor_atencion_cesantias==1){
				
				$("#atencion_cesantias").val(valor_atencion_cesantias);
				$("#atencion_cesantias").prop("checked",true);
				
			}else{
				$("#atencion_cesantias").val(valor_atencion_cesantias);
				
			}
			if(valor_atencion_diferimiento==1){
				
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				$("#atencion_diferimiento").prop("checked",true);
				
			}else{
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				
			}
			if(valor_atencion_desafiliaciones==1){
				
				$("#atencion_desafiliaciones").val(valor_atencion_desafiliaciones);
				$("#atencion_desafiliaciones").prop("checked",true);
				
			}else{
				$("#atencion_desafiliaciones").val(valor_atencion_desafiliaciones);
				
			}
			if(valor_atencion_creditos_en_mora==1){
				
				$("#atencion_creditos_en_mora").val(valor_atencion_creditos_en_mora);
				$("#atencion_creditos_en_mora").prop("checked",true);
				
			}else{
				$("#atencion_creditos_en_mora").val(valor_atencion_creditos_en_mora);
				
			}
			if(valor_atencion_aportes==1){
				
				$("#atencion_aportes").val(valor_atencion_aportes);
				$("#atencion_aportes").prop("checked",true);
				
			}else{
				$("#atencion_aportes").val(valor_atencion_aportes);
				
			}
if(valor_atencion_diferimiento==1){
				
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				$("#atencion_diferimiento").prop("checked",true);
				
			}else{
				$("#atencion_diferimiento").val(valor_atencion_diferimiento);
				
			}
			if(valor_atencion_refinanciamiento_reestructuracion==1){
				
				$("#atencion_refinanciamiento_reestructuracion").val(valor_atencion_refinanciamiento_reestructuracion);
				$("#atencion_refinanciamiento_reestructuracion").prop("checked",true);
				
			}else{
				$("#atencion_refinanciamiento_reestructuracion").val(valor_atencion_refinanciamiento_reestructuracion);
				
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
			$("#otras_actividades_desarrolladas").val(array.otras_actividades_desarrolladas);			
			$("#id_bitacora_actividades_empleados_recaudaciones").val(array.id_bitacora_actividades_empleados_recaudaciones);
			$("html, body").animate({ scrollTop: $(fecha_registro).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaBitacoraRecaudaciones();
	})
	
	return false;
	
}

function delBitacoraRecaudaciones(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=delBitacoraRecaudaciones",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_recaudaciones:id}
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
		consultaBitacoraRecaudaciones();
	})
	
	return false;
}

function consultaBitacoraRecaudaciones(_page = 1){
	
	var buscador = $("#buscador").val();
	var fecha_registro_desde = $("#fecha_registro_desde").val();
	var fecha_registro_hasta = $("#fecha_registro_hasta").val();
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=consultaBitacoraRecaudaciones",
		type:"POST",
		data:{page:_page,search:buscador,fecha_registro_desde:fecha_registro_desde,fecha_registro_hasta:fecha_registro_hasta,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#bitacora_recaudaciones_registrados").html(datos)		
		
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
    				url:"index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=autocompleteCedulaParticipes",
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
    form.setAttribute("action", "index.php?controller=BitacoraActividadesEmpleadosRecaudaciones&action=ReporteBitacoraRecaudaciones");
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

