	
// INICIALIZAR EL JAVA SCRIPT
  
  $(document).ready(function(){ 	 
	  load_temp_diario_tipo(1);
     
  }); 

  
    
   
   // FUNCIONES USADAS EN TODO EL FORMULARIO COMPROBANTES CONTABLES

        
      function limpiar() {
       
    	$('#plan_cuentas').val("0");
		$('#id_plan_cuentas').val("");
		$('#nombre_plan_cuentas').val("");
		$('#descripcion_dcomprobantes').val("");
		$('#debe_dcomprobantes').val("0.00");
		$('#haber_dcomprobantes').val("0.00");
      
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
      	load_plan_cuentas(1);
      	  var modal = $(this)
      	  modal.find('.modal-title').text('Plan de Cuentas')
      
      	});
            
	
		function load_plan_cuentas(pagina){
		 var search=$("#q").val();
		 var con_datos={
					  action:'ajax',
					  page:pagina
					  };
		$("#load_plan_cuentas").fadeIn('slow');
		$.ajax({
		         beforeSend: function(objeto){
		           $("#load_plan_cuentas").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
		         },
		         url: 'index.php?controller=CoreDiarioTipo&action=consulta_plan_cuentas&search='+search,
		         type: 'POST',
		         data: con_datos,
		         success: function(x){
		           $("#cargar_plan_cuentas").html(x);
		           $("#load_plan_cuentas").html("");
		           $("#tabla_plan_cuentas").tablesorter(); 
		           
		         },
		        error: function(jqXHR,estado,error){
		          $("#cargar_plan_cuentas").html("Ocurrio un error al cargar la información de Plan de Cuentas..."+estado+"    "+error);
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
	 		
	 		
			var plan_cuentas=document.getElementById('plan_cuentas').value;
			var descripcion_dcomprobantes=document.getElementById('descripcion_dcomprobantes').value;
			var debe_dcomprobantes=document.getElementById('debe_dcomprobantes').value;
			var haber_dcomprobantes=document.getElementById('haber_dcomprobantes').value;
			

			var error="TRUE";
			
			if (plan_cuentas == 0)
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
			
			
			if (debe_dcomprobantes > 0.00 && haber_dcomprobantes > 0.00)
	    	{
		    	
	    		$("#mensaje_debe_dcomprobantes").text("Ingrese Valor en Debe o en Haber");
	    		$("#mensaje_debe_dcomprobantes").fadeIn("slow"); //Muestra mensaje de error
	    	   error ="TRUE";
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_debe_dcomprobantes").fadeOut("slow"); //Oculta mensaje de error
	    		error ="FALSE";
			}   
			
			
			
			if(error == "FALSE"){
				
				$.ajax({
		            type: "POST",
		            url: 'index.php?controller=CoreDiarioTipo&action=insertar_temp_diario_tipo',
		            data: "plan_cuentas="+plan_cuentas+"&descripcion_dcomprobantes="+descripcion_dcomprobantes+"&debe_dcomprobantes="+debe_dcomprobantes+"&haber_dcomprobantes="+haber_dcomprobantes,
		        	
		            success: function(datos){
		            	//console.log(datos)
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

 $("#btn_inserta_comprobante" ).on( "click", function() {
	 
	 var id_tipo_comprobantes=document.getElementById('id_tipo_comprobantes').value;
		var fecha_ccomprobantes=document.getElementById('fecha_ccomprobantes').value;
		var concepto_ccomprobantes=document.getElementById('concepto_ccomprobantes').value;
		var tiempo = tiempo || 1000;
		
		if (id_tipo_comprobantes == 0)
		{
	    	
			$("#mensaje_id_tipo_comprobantes").text("Seleccione Tipo");
			$("#mensaje_id_tipo_comprobantes").fadeIn("slow"); //Muestra mensaje de error
			$("html, body").animate({ scrollTop: $(mensaje_id_tipo_comprobantes).offset().top-120 }, tiempo);
	        
			return false;
	    }
		else 
		{
			$("#mensaje_id_tipo_comprobantes").fadeOut("slow"); //Oculta mensaje de error
			
		}
		
		
		if(document.getElementById('nombre_comprobante').value != 'CONTABLE'){
			
			if(document.getElementById('proveedor').value == ''){
				
				$("#mensaje_nombre_proveedores").text("Digite RUC/NOMBRE Proveedor");
				$("#mensaje_nombre_proveedores").fadeIn("slow");
				$("html, body").animate({ scrollTop: $(mensaje_nombre_proveedores).offset().top-150 }, tiempo);
				return false
			}
			
			if(document.getElementById('nombre_proveedor').value == ''){
				
				$("#mensaje_nombre_proveedores").text("Digite RUC/NOMBRE Proveedor");
				$("#mensaje_nombre_proveedores").fadeIn("slow"); 
				$("html, body").animate({ scrollTop: $(mensaje_nombre_proveedores).offset().top-150 }, tiempo);
				return false
			}
			
			if(document.getElementById('retencion_proveedor').value == ''){
				
				$("#mensaje_retencion_ccomprobantes").text("Digite Retencion Proveedor");
				$("#mensaje_retencion_ccomprobantes").fadeIn("slow"); 
				$("html, body").animate({ scrollTop: $(mensaje_retencion_ccomprobantes).offset().top-150 }, tiempo);
				return false
			}
		}
		
		if(document.getElementById('referencia_doc_ccomprobantes').value == ''){
			$("#mensaje_referencia_doc_ccomprobantes").text("Digite Retencion Proveedor");
			$("#mensaje_referencia_doc_ccomprobantes").fadeIn("slow"); 
			$("html, body").animate({ scrollTop: $(mensaje_referencia_doc_ccomprobantes).offset().top-150 }, tiempo);
			return false
		}
				
		if(document.getElementById('numero_cuenta_banco_ccomprobantes').value == ''){
			$("#mensaje_numero_cuenta_banco_ccomprobantes").text("Ingrese Numero de Cuenta");
			$("#mensaje_numero_cuenta_banco_ccomprobantes").fadeIn("slow"); 
			$("html, body").animate({ scrollTop: $(mensaje_numero_cuenta_banco_ccomprobantes).offset().top-150 }, tiempo);
			return false
		}
		
		if(document.getElementById('numero_cheque_ccomprobantes').value == ''){
			$("#mensaje_numero_cheque_ccomprobantes").text("Ingrese Numero de Cheque");
			$("#mensaje_numero_cheque_ccomprobantes").fadeIn("slow"); 
			$("html, body").animate({ scrollTop: $(mensaje_numero_cheque_ccomprobantes).offset().top-150 }, tiempo);
			return false
		}
		
		if(document.getElementById('id_forma_pago').value == 0){
			$("#mensaje_id_forma_pago").text("Seleccione Forma de Pago");
			$("#mensaje_id_forma_pago").fadeIn("slow"); 
			$("html, body").animate({ scrollTop: $(mensaje_id_forma_pago).offset().top-150 }, tiempo);
			return false
		}
		
		if (concepto_ccomprobantes == 0)
		{
	    	
			$("#mensaje_concepto_ccomprobantes").text("Inserte un concepto de pago");
			$("#mensaje_concepto_ccomprobantes").fadeIn("slow");
			$("html, body").animate({ scrollTop: $(mensaje_concepto_ccomprobantes).offset().top-120 }, tiempo);
			return false
	    }
		else 
		{
			$("#mensaje_concepto_ccomprobantes").fadeOut("slow"); //Oculta mensaje de error
			
		}
		
		if(document.getElementById('observaciones_ccomprobantes').value == ''){
			$("#mensaje_observaciones_ccomprobantes").text("Ingrese Observacion");
			$("#mensaje_observaciones_ccomprobantes").fadeIn("slow"); 
			$("html, body").animate({ scrollTop: $(mensaje_observaciones_ccomprobantes).offset().top-150 }, tiempo);
			return false
		}
		
		if(!document.getElementById("valor_total_temp")){
			swal({
		   		  title: "Movimientos",
		   		  text: "Registre Movimiento",
		   		  icon: "error",
		   		  button: "Aceptar",
		   		})
			return false
			
			}
		
		if(document.getElementById("valor_total_temp").value == 0){
			swal({
		   		  title: "Movimientos",
		   		  text: "Debe/Haber no Coinciden",
		   		  icon: "warning",
		   		  button: "Aceptar",
		   		})
			return false
			
			}
	 
	 //toma de parametros
		
	 var parametros = {
			 action						: 'ajax',
			 id_tipo_comprobantes 		: $('#id_tipo_comprobantes').val(),
			 id_proveedores				: $('#id_proveedor').val(),
			 retencion_proveedor 		: $('#retencion_proveedor').val(),
			 fecha_ccomprobantes 		: $('#fecha_ccomprobantes').val(),
			 referencia_ccomprobantes 	: $('#referencia_doc_ccomprobantes').val(),
			 id_forma_pago 				: $('#id_forma_pago').val(),
			 num_cuenta_ccomprobantes	: $('#numero_cuenta_banco_ccomprobantes').val(),
			 num_cheque_ccomprobantes 	: $('#numero_cheque_ccomprobantes').val(),
			 observacion_ccomprobantes 	: $('#observaciones_ccomprobantes').val(),
			 concepto_ccomprobantes		: $('#concepto_ccomprobantes').val(),
			 valor_letras				: $('#valor_letras').val()
	 }
	 
	 $.ajax({
         url: 'index.php?controller=CoreDiarioTipo&action=insertacomprobante',
         type: 'POST',
         data: parametros,
         dataType:'json',
         success: function(x){
        	 setearForm()
        	 swal(x.mensaje);
        	 //console.log(x)
        	 load_temp_comprobantes(1)
         },
         error:function(xhr,estado,error){
        	 var err=xhr.responseText
        	 
        	 swal({
        		  title: "Error",
        		  text: "Error conectar con el Servidor \n "+err,
        		  icon: "error",
        		  button: "Aceptar",
        		});
         }
	 });	

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
	
		    
		    
		    
		    
	