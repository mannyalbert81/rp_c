	
// INICIALIZAR EL JAVA SCRIPT
  
  $(document).ready(function(){ 	 
	  load_temp_diario_tipo(1);
	  load_diarios_tipo(1);
  }); 

  
    
   
   // FUNCIONES USADAS EN TODO EL FORMULARIO COMPROBANTES CONTABLES

        
      function limpiar() {
       
    	$('#plan_cuentas').val("0");
		$('#id_plan_cuentas').val("");
		$('#nombre_plan_cuentas').val("");
		$('#descripcion_dcomprobantes').val("");
		$('#debe_dcomprobantes').val("0.00");
		$('#haber_dcomprobantes').val("0.00");
		$('input:radio[name="destino_diario"]').prop('checked', false);
      
      }
      
      
   
   // AUTOCOMPLETE CODIGO PLAN CUENTAS
	  
	       $( "#id_plan_cuentas" ).autocomplete({
					source: 'index.php?controller=CoreDiarioTipo&action=AutocompleteComprobantesCodigo',
					minLength: 1
			});
	
			$("#id_plan_cuentas").focusout(function(){
				
				$.ajax({
					url:'index.php?controller=CoreDiarioTipo&action=AutocompleteComprobantesDevuelveNombre',
					type:'POST',
					dataType:'json',
					data:{codigo_plan_cuentas:$('#id_plan_cuentas').val()}
				}).done(function(respuesta){
	
					$('#nombre_plan_cuentas').val(respuesta.nombre_plan_cuentas);
					$('#plan_cuentas').val(respuesta.id_plan_cuentas);
				
				}).fail(function(respuesta) {
					  
					$('#plan_cuentas').val("0");
					$('#id_plan_cuentas').val("");
					$('#nombre_plan_cuentas').val("");
					$('#descripcion_dcomprobantes').val("");
					$('#debe_dcomprobantes').val("0.00");
					$('#haber_dcomprobantes').val("0.00");
					
				});
				
			});   
	 	



    // AUTOCOMPLETE NOMBRE PLAN CUENTAS
   
		
			$("#nombre_plan_cuentas").autocomplete({
					source: 'index.php?controller=CoreDiarioTipo&action=AutocompleteComprobantesNombre',
					minLength: 1
			});
	
			$("#nombre_plan_cuentas").focusout(function(){
				$.ajax({
					url:'index.php?controller=CoreDiarioTipo&action=AutocompleteComprobantesDevuelveCodigo',
					type:'POST',
					dataType:'json',
					data:{nombre_plan_cuentas:$('#nombre_plan_cuentas').val()}
				}).done(function(respuesta){
	
					$('#id_plan_cuentas').val(respuesta.codigo_plan_cuentas);
					$('#plan_cuentas').val(respuesta.id_plan_cuentas);
				
				}).fail(function(respuesta) {
					$('#plan_cuentas').val("0");
					$('#id_plan_cuentas').val("");
					$('#nombre_plan_cuentas').val("");
					$('#descripcion_dcomprobantes').val("");
					$('#debe_dcomprobantes').val("0.00");
					$('#haber_dcomprobantes').val("0.00");
					
				});
				 
				
			});   
			
	
	
	  // PARA CARGAR CONSULTA PLAN DE CUENTAS AL MODAL
	  
	   $('#myModal').on('show.bs.modal', function (event) {
		  load_diarios_tipo(1);
      	  var modal = $(this)
      	  modal.find('.modal-title').text('Buscar Diarios Tipo')
      
      	});
            
	
		function load_diarios_tipo(pagina){
		 var search=$("#q").val();
		 var con_datos={
					  action:'ajax',
					  page:pagina
					  };
		$("#load_diarios_tipo").fadeIn('slow');
		$.ajax({
		         beforeSend: function(objeto){
		           $("#load_diarios_tipo").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
		         },
		         url: 'index.php?controller=CoreDiarioTipo&action=consulta_diarios_tipo&search='+search,
		         type: 'POST',
		         data: con_datos,
		         success: function(x){
		           $("#cargar_diarios_tipo").html(x);
		           $("#load_diarios_tipo").html("");
		           $("#tabla_diarios_tipo").tablesorter(); 
		           
		         },
		        error: function(jqXHR,estado,error){
		          $("#cargar_diarios_tipo").html("Ocurrio un error al cargar la información de Diarios Tipo..."+estado+"    "+error);
		        }
		      });
		
		 }
	
	
	
	   // CARGAR TEMPORAL COMPROBANTES REGISTRADOS
	
	    function load_temp_diario_tipo(pagina){
	         
           	var search=$("#search_temp_diario_tipo").val();
           
            $("#load_temp_diario_tipo_registrados").fadeIn('slow');
            
            $.ajax({
                    beforeSend: function(objeto){
                      $("#load_temp_diario_tipo_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
                    },
                    url: 'index.php?controller=CoreDiarioTipo&action=consulta_temp_diario_tipo&search='+search,
                    type: 'POST',
                    data: {action:'ajax', page:pagina},
                    success: function(x){
                      $("#temp_diario_tipo_registrados").html(x);
                      $("#load_temp_diario_tipo_registrados").html("");
                      $("#tabla_temp_diario_tipo_registrados").tablesorter(); 
                      
                    },
                   error: function(jqXHR,estado,error){
                     $("#temp_diario_tipo_registrados").html("Ocurrio un error al cargar la información de Cuentas Registradas..."+estado+"    "+error);
                   }
             });
        }

	// AGREGAR REGISTRO DE TABLA TEMPORAL
	    

	 	function agregar_temp_diario_tipo()
		{
	 		
	 		
			var _plan_cuentas=document.getElementById('plan_cuentas').value;
			var _descripcion_dcomprobantes=document.getElementById('descripcion_dcomprobantes').value;
			
			var error="TRUE";
			
			if (_plan_cuentas == 0)
	    	{
		    	
	    		$("#mensaje_id_plan_cuentas").text("Seleccione Cuenta");
	    		$("#mensaje_id_plan_cuentas").fadeIn("slow"); //Muestra mensaje de error
	            
	    		error ="TRUE";
	    		return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_id_plan_cuentas").fadeOut("slow"); //Oculta mensaje de error
	    		error ="FALSE";
			}   			
			
			if (! $('input:radio[name="destino_diario"]').is(':checked')) {
				$("#mensaje_destino_diario").text("Seleccione destino cuenta").fadeIn("slow");
	    	    error ="TRUE";
	            return false;
			}
			
			var _destino_diario = $('input:radio[name="destino_diario"]:checked').val();
			
			
			var parametros = {
					plan_cuentas:_plan_cuentas,
					descripcion_dcomprobantes:_descripcion_dcomprobantes,
					debe_dcomprobantes:"0.00",
					haber_dcomprobantes:"0.00",
					destino_diario: _destino_diario
			}
			
			if(error == "FALSE"){
				
				$.ajax({
		            type: "POST",
		            url: 'index.php?controller=CoreDiarioTipo&action=insertar_temp_diario_tipo',
		            data: parametros,
		            dataType: "json",
		            success: function(datos){
		            	
		            	limpiar();
		            	load_temp_diario_tipo(1);
		            	
		            },
		            error: function(xhr,status,error){
		            	var err = xhr.responseText;
		            	console.log(err)
		            }
				});
				
			}
			
			
		}
	 	
	 	
	 	 $( "#id_plan_cuentas" ).focus(function() {
			  $("#mensaje_id_plan_cuentas").fadeOut("slow");
		  });
	 	
	 	 $( "#debe_dcomprobantes" ).focus(function() {
			  $("#mensaje_debe_dcomprobantes").fadeOut("slow");
		  });
	 	 
	 	$('input[name="destino_diario"]').focus(function() {
			  $("#mensaje_destino_diario").fadeOut("slow");
		  });
	
	// ELIMINAR REGISTRO DE TABLA TEMPORAL
	    
	    function eliminar_temp_diario_tipo(id)
		{
			$.ajax({
	            type: "POST",
	            url: 'index.php?controller=CoreDiarioTipo&action=eliminar_temp_diario_tipo',
	            data: "id_temp_diario_tipo="+id,
	        	 success: function(datos){
	        		 load_temp_diario_tipo(1);
	        	 }
			});
		}
	
	    
	    
	  // PARA CONSULTAR NUMERO DE COMPROBANTES
	    
	    
       function load_consecutivo_comprobantes(id_tipo_comprobantes){
	     
    	   $.ajax({
                    url: 'index.php?controller=CoreDiarioTipo&action=consulta_consecutivos',
                    type: 'POST',
                    data: {action:'ajax', id_tipo_comprobantes:id_tipo_comprobantes},
                    dataType:'json',
                    success: function(x){

                      $("#numero_ccomprobantes").val(x.numero);
                      if(x.nombre == 'CONTABLE'){
                    	  $('#id_proveedor').val('0')
                    	  $('#nombre_proveedor').val('').attr("readonly","readonly")
                    	  $('#proveedor').val('').attr("readonly","readonly")
                    	  $('#retencion_proveedor').val('').attr("readonly","readonly")
                    	  $('#nombre_comprobante').val('CONTABLE')
                    	  $('.clsproveedor').hide();
                    	  
                      }else{
                    	  $('#id_proveedor').val('0')
                    	  $('#nombre_proveedor').val('').removeAttr("readonly")
                    	  $('#proveedor').val('').removeAttr("readonly")
                    	  $('#retencion_proveedor').val('').removeAttr("readonly")
                    	  $('#nombre_comprobante').val('')
                    	  $('.clsproveedor').show();
                      }
                      
                    }
             });
        }
	    


$("#id_tipo_comprobantes").change(function() {
	  
      var id_tipo_comprobantes = $(this).val();
		
      if(id_tipo_comprobantes > 0)
      {
       load_consecutivo_comprobantes(id_tipo_comprobantes);
   	   $("#div_datos").fadeIn("slow");
      }
      else
      {
   	   $("#div_datos").fadeOut("slow");
      }
      
});



$( "#id_tipo_comprobantes" ).focus(function() {
	  $("#mensaje_id_tipo_comprobantes").fadeOut("slow");
});

$( "#concepto_ccomprobantes" ).focus(function() {
	  $("#mensaje_concepto_ccomprobantes").fadeOut("slow");
});

$( "#proveedor" ).focus(function() {
	  $("#mensaje_nombre_proveedores").fadeOut("slow");
});

$( "#nombre_proveedor" ).focus(function() {
	  $("#mensaje_nombre_proveedores").fadeOut("slow");
});

$( "#retencion_proveedor" ).focus(function() {
	  $("#mensaje_retencion_ccomprobantes").fadeOut("slow");
});

$( "#referencia_doc_ccomprobantes" ).focus(function() {
	  $("#mensaje_referencia_doc_ccomprobantes").fadeOut("slow");
});

$( "#id_forma_pago" ).focus(function() {
	  $("#mensaje_id_forma_pago").fadeOut("slow");
});

$( "#numero_cuenta_banco_ccomprobantes" ).focus(function() {
	  $("#mensaje_numero_cuenta_banco_ccomprobantes").fadeOut("slow");
});

$( "#numero_cheque_ccomprobantes" ).focus(function() {
	  $("#mensaje_numero_cheque_ccomprobantes").fadeOut("slow");
});

$( "#observaciones_ccomprobantes" ).focus(function() {
	  $("#mensaje_observaciones_ccomprobantes").fadeOut("slow");
})


// INSERTAR COMPROBANTES PROCESO FINAL

 $("#btn_inserta_diario" ).on( "click", function() {
	 
	 var _id_modulos = $("#id_modulos").val();
	 var _id_tipo_proceso = $("#id_tipo_procesos").val();
	 var _descripcion_diario = $("#descripcion_diario_tipo").val();
	 var _id_estado = $("#id_estado").val();
	 
	 if( _id_modulos == 0){
		 
		 $("#id_modulos").notify("Seleccione modulo",{ position:"buttom left", autoHideDelay: 2000});
		 return false;
	 }
	 
	 if( _id_tipo_proceso == 0){
		 
		 $("#id_tipo_procesos").notify("Seleccione tipo proceso",{ position:"buttom left", autoHideDelay: 2000});
		 return false;
	 }
	 
	 if( _descripcion_diario == ''){
		 
		 $("#descripcion_diario_tipo").notify("Ingrese Descripcion",{ position:"buttom left", autoHideDelay: 2000});
		 return false;
	 }
	 
	 if( _id_estado == 0){
		 
		 $("#id_estado").notify("Seleccione estado",{ position:"buttom left", autoHideDelay: 2000});
		 return false;
	 }
	 
	 var nFilas = $("#tabla_temp_diario_tipo_registrados tr").length;
		
	if(nFilas < 3){
		//validacion que haya filas de debe y haber
		swal( {
			 title:"Detalle Diario Tipo",
			 dangerMode: true,
			 text: "ingrese cuentas en detalle",
			 icon: "error"
			}
		)
		return false;
	}
	 
			
	 var parametros = {
			 id_modulos: _id_modulos,
			 id_tipo_procesos: _id_tipo_proceso,
			 descripcion_diario: _descripcion_diario,
			 id_estado:_id_estado			
	 }
	 
	 $.ajax({
		 url: 'index.php?controller=CoreDiarioTipo&action=insertDiarioTipo',
         type: 'POST',
         data: parametros,
         dataType:'json'
	 }).done(function(x){
		 swal({title:"Diario Tipo",text:x.mensaje,icon:"success"})
 		.then((value) => { 			
 			window.location.reload();
 		});
	 }).fail(function(xhr,status,error){
		 var err = xhr.responseText
		 console.log(err)
		 var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal( {
				 title:"Detalle Diario Tipo",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		 }
	 })
	 
});
 
 

 function validarcedula(ced) {
     var cad = document.getElementById(ced).value.trim();
     var total = 0;
     var longitud = cad.length;
     var longcheck = longitud - 1;

     if (cad !== "" && longitud === 10){
       for(i = 0; i < longcheck; i++){
         if (i%2 === 0) {
           var aux = cad.charAt(i) * 2;
           if (aux > 9) aux -= 9;
           total += aux;
         } else {
           total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
         }
       }

       total = total % 10 ? 10 - total % 10 : 0;

       if (cad.charAt(longitud-1) == total) {
     	  $(ced).val(cad);
     	  return true;
       }else{
			  document.getElementById(ced).focus();
     	  $(ced).val("");
     	  return false;
       }
     }
   }
 
 function setearForm(){
	
	 $('#id_tipo_comprobantes').val(0)
	 $('#id_proveedor').val('0')
	 $('#retencion_proveedor').val('')
	 $('#referencia_doc_ccomprobantes').val('')
	 $('#id_forma_pago').val(0)
	 $('#numero_cuenta_banco_ccomprobantes').val('')
	 $('#numero_cheque_ccomprobantes').val('')
	 $('#observaciones_ccomprobantes').val('')
	 $('#concepto_ccomprobantes').val('')
 }
 
 
 $('#nombre_proveedores').focus(function(){
	 $("#mod_mensaje_nombre_proveedores").fadeOut("slow");
 })
 
  $('#identificacion_proveedores').focus(function(){
	 $("#mod_mensaje_identificacion_proveedores").fadeOut("slow");
 })
 
  $('#contactos_proveedores').focus(function(){
	 $("#mod_mensaje_contactos_proveedores").fadeOut("slow");
 })
 
  $('#direccion_proveedores').focus(function(){
	 $("#mod_mensaje_direccion_proveedores").fadeOut("slow");
 })
 
  $('#telefono_proveedores').focus(function(){
	 $("#mod_mensaje_telefono_proveedores").fadeOut("slow");
 })

   $('#email_proveedores').focus(function(){
	 $("#mod_mensaje_email_proveedores").fadeOut("slow");
 })
 
 //PARA LA CABECERA
 
 $("#id_modulos").on("change",function(){
	 
	 let moduloId = $(this).val();
	 let objProcesos = $("#id_tipo_procesos");
	 
	 objProcesos.empty();
	 
	 $.ajax({
		 url:"index.php?controller=CoreDiarioTipo&action=consultaTipoProcesos",
		 type:"POST",
		 dataType:"json",
		 data:{id_modulos:moduloId}
	 }).done(function(x){
		 //console.log(x)
		 objProcesos.append('<option value="0">--Seleccione--</option>');
		 if(x.cantidad > 0){			 
			 $.each(x.data,function(index,value){
				 objProcesos.append('<option value="'+value.id_tipo_procesos+'">'+value.nombre_tipo_procesos+'</option>');
			 })
		 }
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
	 })
	 
 })
		    
		    
		    
		    
	