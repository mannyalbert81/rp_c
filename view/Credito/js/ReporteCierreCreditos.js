$(document).ready(function(){
	
	ConsultaReporteCierreCreditos(1);

})

function ConsultaReporteCierreCreditos(_page){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCierreCreditos&action=ConsultaReporteCierreCreditos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_cierre_creditos_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}



	var id = 0;
	
	$("#consulta_cierre_creditos_registrados_tbl").on("click","#btn_abrir",function(event){

		var $div_respuesta = $("#msg_frm_reasignar"); $div_respuesta.text("").removeClass();
	    
		id = $(this).data().id;
		
		$("#mod_reasignar").on('show.bs.modal',function(e){

			 var modal = $(this)
			 modal.find('#mod_id_core_documentos_hipotecario').val(id);
			
			
		}) 
		
	})

	





	$("#frm_reasignar").on("submit",function(event){



		let $mod_id_core_documentos_hipotecario = $('#mod_id_core_documentos_hipotecario').val();
		let $archivo_escritura_core_documentos_hipotecario = $('#archivo_escritura_core_documentos_hipotecario').val();
		let $archivo_cretificado_core_documentos_hipotecario = $('#archivo_cretificado_core_documentos_hipotecario').val();
		let $archivo_impuesto_core_documentos_hipotecario= $('#archivo_impuesto_core_documentos_hipotecario').val();
		let $archivo_avaluo_core_documentos_hipotecario = $('#archivo_avaluo_core_documentos_hipotecario').val();
		
		
		if($mod_id_core_documentos_hipotecario > 0) {  
			
        } else {  

        	swal("Alerta!", "Seleccione Solicitud", "error")
            return false;
        		
        } 

		
        if($archivo_escritura_core_documentos_hipotecario == 0 && $archivo_cretificado_core_documentos_hipotecario == 0 && $archivo_impuesto_core_documentos_hipotecario == 0 && $archivo_avaluo_core_documentos_hipotecario == 0) {  
			
        	swal("Alerta!", "Seleccione al menos un documento", "error")
            return false;
        } 
		
		
		

		var parametros = new FormData();
		
		parametros.append('id_core_documentos_hipotecario',$mod_id_core_documentos_hipotecario);
		parametros.append('archivo_escritura_core_documentos_hipotecario',  $('#archivo_escritura_core_documentos_hipotecario')[0].files[0]); 
		parametros.append('archivo_cretificado_core_documentos_hipotecario',  $('#archivo_cretificado_core_documentos_hipotecario')[0].files[0]); 
		parametros.append('archivo_impuesto_core_documentos_hipotecario',  $('#archivo_impuesto_core_documentos_hipotecario')[0].files[0]); 
		parametros.append('archivo_avaluo_core_documentos_hipotecario',  $('#archivo_avaluo_core_documentos_hipotecario')[0].files[0]); 
		

		var $div_respuesta = $("#msg_frm_reasignar"); $div_respuesta.text("").removeClass();
			
			
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=ReporteCierreCreditos&action=ActualizarSolicitud",
			type:"POST",
			dataType:"json",
			data:parametros,
			
			 contentType: false, 
	         processData: false 
		}).done(function(respuesta){
					
			
			
			
			if(respuesta.valor > 0){
				
				
				$("#msg_frm_reasignar").text("Documentos Actualizado Correctamente").addClass("alert alert-success");

				ConsultaReporteCierreCreditos(1);
      		     
		    }
			
			
		}).fail(function(xhr,status,error){
			
			var err = xhr.responseText
			console.log(err);
			
			$div_respuesta.text("Error al actualizar documentos en la solicitud").addClass("alert alert-warning");
			
		}).always(function(){
					
		})
		
		event.preventDefault();
	})
	
	

