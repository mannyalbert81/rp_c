	
  // INICIALIZAR EL JAVA SCRIPT
	  
      $(document).ready(function(){ 	 
	     load_temp_comprobantes(1);
	     $('#datos_proveedor').hide();
	     
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
					source: 'index.php?controller=ComprobanteContable&action=AutocompleteComprobantesCodigo',
					minLength: 1
			});
	
			$("#id_plan_cuentas").focusout(function(){
				
				$.ajax({
					url:'index.php?controller=ComprobanteContable&action=AutocompleteComprobantesDevuelveNombre',
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
					source: 'index.php?controller=ComprobanteContable&action=AutocompleteComprobantesNombre',
					minLength: 1
			});
	
			$("#nombre_plan_cuentas").focusout(function(){
				$.ajax({
					url:'index.php?controller=ComprobanteContable&action=AutocompleteComprobantesDevuelveCodigo',
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
			
	

	
	
	// VALIDAR QUE VAYA SOLO EL DEBE O SOLO HABER
	
	  function validardebe(field) {
			var nombre_elemento = field.id;
			if(nombre_elemento=="debe_dcomprobantes")
			{
				$("#haber_dcomprobantes").val("0.00");
				
			}else
			{
				$("#debe_dcomprobantes").val("0.00");
			}
		  }
	
	
	
	
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
		         url: 'index.php?controller=ComprobanteContable&action=consulta_plan_cuentas&search='+search,
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
	
	    function load_temp_comprobantes(pagina){
	        
           	var search=$("#search_temp_comprobantes").val();
           
            $("#load_temp_comprobantes_registrados").fadeIn('slow');
            
            $.ajax({
                    beforeSend: function(objeto){
                      $("#load_temp_comprobantes_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
                    },
                    url: 'index.php?controller=ComprobanteContable&action=consulta_temp_comprobantes&search='+search,
                    type: 'POST',
                    data: {action:'ajax', page:pagina},
                    success: function(x){
                      $("#temp_comprobantes_registrados").html(x);
                      $("#load_temp_comprobantes_registrados").html("");
                      $("#tabla_temp_comprobantes").tablesorter(); 
                      
                    },
                   error: function(jqXHR,estado,error){
                     $("#temp_comprobantes_registrados").html("Ocurrio un error al cargar la información de Cuentas Registradas..."+estado+"    "+error);
                   }
             });
        }

	// AGREGAR REGISTRO DE TABLA TEMPORAL
	    

	 	function agregar_temp_comprobantes ()
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
			
			
			if (debe_dcomprobantes == 0.00 && haber_dcomprobantes == 0.00)
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
		            url: 'index.php?controller=ComprobanteContable&action=insertar_temp_comprobantes',
		            data: "plan_cuentas="+plan_cuentas+"&descripcion_dcomprobantes="+descripcion_dcomprobantes+"&debe_dcomprobantes="+debe_dcomprobantes+"&haber_dcomprobantes="+haber_dcomprobantes,
		        	
		            success: function(datos){
		            	limpiar();
		            	load_temp_comprobantes(1);
		            	
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
	    
	    function eliminar_temp_comprobantes(id)
		{
			$.ajax({
	            type: "POST",
	            url: 'index.php?controller=ComprobanteContable&action=eliminar_temp_comprobantes',
	            data: "id_temp_comprobantes="+id,
	        	 success: function(datos){
	        		 	load_temp_comprobantes(1);
	        	 }
			});
		}
	
	    
	    
	  // PARA CONSULTAR NUMERO DE COMPROBANTES
	    
	    
       function load_consecutivo_comprobantes(id_tipo_comprobantes){
	     
    	   $.ajax({
                    url: 'index.php?controller=ComprobanteContable&action=consulta_consecutivos',
                    type: 'POST',
                    data: {action:'ajax', id_tipo_comprobantes:id_tipo_comprobantes},
                    success: function(x){
                      $("#numero_ccomprobantes").val(x);
                      
                    }
             });
        }
	    
	    
// PARA HABILITAR NUMERO DE COMPROBANTES    

$("#id_tipo_comprobantes").click(function() {

  var id_tipo_comprobantes = $(this).val();
	
  if(id_tipo_comprobantes > 0 )
  {
   load_consecutivo_comprobantes(id_tipo_comprobantes);
   $("#div_datos").fadeIn("slow");
   
  }
  else
  {
   $("#div_datos").fadeOut("slow");
  }
 
});

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

	      
		    
		
		    
		 // INSERTAR COMPROBANTES PROCESO FINAL
		       
			function insertar_comprobantes()
			{
					
				var id_tipo_comprobantes=document.getElementById('id_tipo_comprobantes').value;
				var fecha_ccomprobantes=document.getElementById('fecha_ccomprobantes').value;
				var concepto_ccomprobantes=document.getElementById('concepto_ccomprobantes').value;
				var tiempo = tiempo || 1000;
				var error="TRUE";
				
				if (id_tipo_comprobantes == 0)
		    	{
			    	
		    		$("#mensaje_id_tipo_comprobantes").text("Seleccione Tipo");
		    		$("#mensaje_id_tipo_comprobantes").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_id_tipo_comprobantes).offset().top-120 }, tiempo);
			         
		    		error ="TRUE";
		    		return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_tipo_comprobantes").fadeOut("slow"); //Oculta mensaje de error
		    		error ="FALSE";
				}
				
								
							
			}
		  
		      
			 $( "#id_tipo_comprobantes" ).focus(function() {
				  $("#mensaje_id_tipo_comprobantes").fadeOut("slow");
			  });
		      
//PARA EL INSERTADO DE COMPROBANTE

 $("#btn_inserta_comprobante" ).on( "click", function() {
	  //toma de parametros
	 
	 var parametros = {
			 action						: 'ajax',
			 id_tipo_comprobantes 		: $('#id_tipo_comprobantes').val(),
			 ruc_ccomprobantes 			: $('#identificacion_proveedores').val(),
			 nombres_ccomprobantes		: $('#nombre_proveedores').val(),
			 retencion_ccomprobantes	: $('#retencion_ccomprobantes').val(),
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
         url: 'index.php?controller=ComprobanteContable&action=insertacomprobante',
         type: 'POST',
         data: parametros,
         success: function(x){
           console.log(x)
           
         }
	 });
});
 
 /***
  * autocompelte proveedores
  */
 $( "#proveedor" ).autocomplete({

		source: 'index.php?controller=MovimientosInv&action=busca_proveedor',
		minLength: 4,
     select: function (event, ui) {
        // Set selection          
        $('#id_proveedor').val(ui.item.id);
        $('#proveedor').val(ui.item.value); // save selected id to input
        $('#nombre_proveedor').val(ui.item.nombre);
        $('#datos_proveedor').show();
        //console.log(ui.item.nombre);
        return false;
     },
     focus: function(event, ui) { 
         var text = ui.item.value; 
         $('#proveedor').val();            
         return false; 
     } 
	}).focusout(function() {
		$.ajax({
			url:'index.php?controller=MovimientosInv&action=busca_proveedor',
			type:'POST',
			dataType:'json',
			data:{term:$('#proveedor').val()}
		}).done(function(respuesta){
			console.log(respuesta[0].id);
			if(respuesta[0].id>0){				
				$('#id_proveedor').val(respuesta[0].id);
	           $('#proveedor').val(respuesta[0].value); // save selected id to input
	           $('#nombre_proveedor').val(respuesta[0].nombre);
	           $('#datos_proveedor').show();
			}else{$('#datos_proveedor').hide(); $('#id_proveedor').val('0');  }
		});
	});
  
//PARA CARGAR CONSULTA PLAN DE CUENTAS AL MODAL
 
 $('#modalproveedor').on('show.bs.modal', function (event) {
	load_plan_cuentas(1);
	  var modal = $(this)
	  modal.find('.modal-title').text('PROVEEDORES')

	});
 
 $( "#frm_guardar_proveedor" ).submit(function( event ) {
		//console.log('ingresa->1\n');
		var parametros = $(this).serialize();	
		$.ajax({
	        beforeSend: function(objeto){
	          
	        },
	        url: 'index.php?controller=Proveedores&action=ins_proveedor',
	        type: 'POST',
	        data: parametros,
	        dataType:'json',
	        success: function(respuesta){
	        	
	            if(respuesta.success==1){
	            	$("#frm_guardar_proveedor")[0].reset();
	            	swal({
	            		  title: "Proveedores",
	            		  text: respuesta.mensaje,
	            		  icon: "success",
	            		  button: "Aceptar",
	            		});
					
	                }else{
	                	$("#frm_guardar_proveedor")[0].reset();
	                	swal({
	              		  title: "Proveedores",
	              		  text: respuesta.mensaje,
	              		  icon: "warning",
	              		  button: "Aceptar",
	              		});
	                    }
	        	     
	        },
	        error: function(jqXHR,estado,error){
	         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	        }
	    });
		 
		event.preventDefault();	
		  
});
		      
		  
		    
		    
		    
		    
	