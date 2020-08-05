$(document).ready(function(){
	consultaBitacoraTesoreria();
	
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
	let _credito = document.getElementById('credito').value;
	let _prestaciones = document.getElementById('prestaciones').value;
	let _recaudaciones = document.getElementById('recaudaciones').value;
	let _tesoreria = document.getElementById('tesoreria').value;
	let _contabilidad = document.getElementById('contabilidad').value;
	let _auditoria = document.getElementById('auditoria').value;
	let _biess = document.getElementById('biess').value;
	let _sb = document.getElementById('sb').value;
	let _otras_actividades = document.getElementById('otras_actividades').value;
	let _motivo_atencion = document.getElementById('motivo_atencion').value;
	var _id_bitacora_actividades_empleados_tesoreria = document.getElementById('id_bitacora_actividades_empleados_tesoreria').value;
	
	if (_fecha_registro=="" || _fecha_registro.length<1 ){
		$("#fecha_registro").notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000}); return false;
	}
	
	var parametros = {fecha_registro:_fecha_registro,
						desde:_desde,
						hasta:_hasta,
						id_empleados:_id_empleados,
						id_participes:_id_participes,
						credito:_credito,
						prestaciones:_prestaciones,
						recaudaciones:_recaudaciones,
						tesoreria:_tesoreria,
						contabilidad:_contabilidad,
						auditoria:_auditoria,
						biess:_biess,
						sb:_sb,
						otras_actividades:_otras_actividades,
						motivo_atencion:_motivo_atencion,
						id_bitacora_actividades_empleados_tesoreria:_id_bitacora_actividades_empleados_tesoreria}
	
		
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=InsertaBitacoraTesoreria",
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
		consultaBitacoraTesoreria();
	})

	event.preventDefault()
})

var setearElementos	= function(){
	
	document.getElementById("frm_bitacora_tesoreria").reset();
	
	$("#id_bitacora_actividades_empleados_tesoreria").val(0);
	$("#hdn_id_participes").val(0);
	$("#fecha_registro").val("");
	$("#credito").val("");
	$("#prestaciones").val("");
	$("#recaudaciones").val("");
	$("#tesoreria").val("");
	$("#contabilidad").val("");
	$("#auditoria").val("");
	$("#biess").val("");
	$("#sb").val("");
	$("#otras_actividades").val("");
	$("#motivo_atencion").val("");
	
}


function editBitacoraTesoreria(id = 0){
	
	var tiempo = tiempo || 1000;
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=editBitacoraTesoreria",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_tesoreria:id}
	}).done(function(datos){
		
		if(!jQuery.isEmptyObject(datos.data)){
			
			var array = datos.data[0];	
			
			$("#fecha_registro").val(array.fecha_registro);
			$("#desde").val(array.desde);			
			$("#hasta").val(array.hasta);			
			$("#id_empleados").val(array.id_empleados);			
			$("#hdn_id_participes").val(array.id_participes);	
			$("#cedula_participes").val(array.cedula_participes);	
			$("#nombre_participes").val(array.nombres_participes);	
			$("#credito").val(array.credito);			
			$("#prestaciones").val(array.prestaciones);			
			$("#recaudaciones").val(array.recaudaciones);			
			$("#tesoreria").val(array.tesoreria);			
			$("#contabilidad").val(array.contabilidad);			
			$("#auditoria").val(array.auditoria);			
			$("#biess").val(array.biess);			
			$("#sb").val(array.sb);			
			$("#otras_actividades").val(array.otras_actividades);			
			$("#motivo_atencion").val(array.motivo_atencion);			
			$("#id_bitacora_actividades_empleados_tesoreria").val(array.id_bitacora_actividades_empleados_tesoreria);
			$("html, body").animate({ scrollTop: $(fecha_registro).offset().top-120 }, tiempo);			
		}
		
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaBitacoraTesoreria();
	})
	
	return false;
	
}

function delBitacoraTesoreria(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=delBitacoraTesoreria",
		type:"POST",
		dataType:"json",
		data:{id_bitacora_actividades_empleados_tesoreria:id}
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
		consultaBitacoraTesoreria();
	})
	
	return false;
}

function consultaBitacoraTesoreria(_page = 1){
	
	var buscador = $("#buscador").val();
	var fecha_registro_desde = $("#fecha_registro_desde").val();
	var fecha_registro_hasta = $("#fecha_registro_hasta").val();
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=consultaBitacoraTesoreria",
		type:"POST",
		data:{page:_page,search:buscador,fecha_registro_desde:fecha_registro_desde,fecha_registro_hasta:fecha_registro_hasta,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#bitacora_tesoreria_registrados").html(datos)		
		
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
    				url:"index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=autocompleteCedulaParticipes",
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
    form.setAttribute("action", "index.php?controller=BitacoraActividadesEmpleadosTesoreria&action=ReporteBitacoraTesoreria");
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

