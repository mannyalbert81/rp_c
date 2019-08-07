<!DOCTYPE HTML>
<html lang="es">
      <head>
        <meta charset="utf-8"/>
        <title>Solicitud Prestamo - Capremci</title>

	 
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="view/css/estilos.css">
		<link rel="stylesheet" href="view/vendors/table-sorter/themes/blue/style.css">
	
	
	
		    <!-- Bootstrap -->
    		<link href="view/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    		<!-- Font Awesome -->
		    <link href="view/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		    <!-- NProgress -->
		    <link href="view/vendors/nprogress/nprogress.css" rel="stylesheet">
		    
		   
		    <!-- Custom Theme Style -->
		    <link href="view/build/css/custom.min.css" rel="stylesheet">
				
			
			<!-- Datatables -->
		    <link href="view/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		    
		   		

			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
			<script type="text/javascript" src="view/vendors/table-sorter/jquery.tablesorter.js"></script> 
       		 <script src="view/js/jquery.blockUI.js"></script>
            <script src="view/js/jquery.inputmask.bundle.js"></script>
            
            <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		
			<script>
			    //webshims.activeLang("en");
			    webshims.setOptions('forms-ext', { datepicker: { dateFormat: 'yy/mm/dd' } });
				webshims.polyfill('forms forms-ext');
			</script>
           
           
       		<script src="view/input-mask/jquery.inputmask.js"></script>
			<script src="view/input-mask/jquery.inputmask.date.extensions.js"></script>
			<script src="view/input-mask/jquery.inputmask.extensions.js"></script>
			
			
			
			     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>  
                 <script src="view/js/jquery.js"></script>
		         <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			
        
    
    <script type="text/javascript">
	    function sumar_ingresos (valor) {
	        var total = 0;	
	        valor = parseInt(valor); // Convertir el valor a un entero (número).
	    	
	        //total = document.getElementById('total_ingresos').innerHTML;
	    	
	        // Aquí valido si hay un valor previo, si no hay datos, le pongo un cero "0".
	        total = (total == null || total == undefined || total == "") ? 0 : total;
	    	
	        /* Esta es la suma. */
	        total = (parseInt(total) + parseInt(valor));
	    	
	        // Colocar el resultado de la suma en el control "span".
	        document.getElementById('total_ingresos').innerHTML = total;
	    }
    </script>
    
    
     <script type="text/javascript">
	    function sumar_egresos (valor) {

	        var total = 0;	
	        var id_cantones_vivienda = $("#id_cantones_vivienda");
	        valor = parseInt(valor); 
	        
	        total = (parseInt(total) + parseInt(valor));
	    	
	        // Colocar el resultado de la suma en el control "span".
	        document.getElementById('total_egresos').innerHTML = total;
	    }
    </script>
    
    
	
	<script>
		$(document).ready(function(){

			$("#id_provincias_vivienda").change(function(){
			
	            // obtenemos el combo de resultado combo 2
	           var $id_cantones_vivienda = $("#id_cantones_vivienda");
	       	

	            // lo vaciamos
	           var id_provincias_vivienda = $(this).val();

	          
	          
	            if(id_provincias_vivienda != 0)
	            {
	            	 $id_cantones_vivienda.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_provincias_vivienda:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Afiliacion","devuelveCanton"); ?>", datos, function(resultado) {

	          		  if(resultado.length==0)
	          		   {
	          				$id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_cantones_vivienda.append("<option value= " +value.id_cantones +" >" + value.nombre_cantones  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');


	            }else{

	            	var id_cantones_vivienda=$("#id_cantones_vivienda");
	            	id_cantones_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	var id_parroquias_vivienda=$("#id_parroquias_vivienda");
	            	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            	
	            }
	            

			});
		});
	
       

	</script>
		 
		 
		 
		 
		 
		 
		 <script>
		$(document).ready(function(){

			$("#id_cantones_vivienda").change(function(){

				
	            // obtenemos el combo de resultado combo 2
	           var $id_parroquias_vivienda = $("#id_parroquias_vivienda");
	       	

	            // lo vaciamos
	           var id_cantones_vivienda = $(this).val();

	          
	          
	            if(id_cantones_vivienda != 0)
	            {
	            	 $id_parroquias_vivienda.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_cantones_vivienda:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Afiliacion","devuelveParroquias"); ?>", datos, function(resultado) {

	          		  if(resultado.length==0)
	          		   {
	          				$id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_parroquias_vivienda.append("<option value= " +value.id_parroquias +" >" + value.nombre_parroquias  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');


	            }else{

	            	var id_parroquias_vivienda=$("#id_parroquias_vivienda");
	            	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            }
	            

			});
		});
	
       

	</script>
		    
			
        
        
        
        
        
        
        
        
        
        
        <script>
		$(document).ready(function(){

			$("#id_provincias_asignacion").change(function(){

	            // obtenemos el combo de resultado combo 2
	           var $id_cantones_asignacion = $("#id_cantones_asignacion");
	       	

	            // lo vaciamos
	           var id_provincias_asignacion = $(this).val();

	          
	          
	            if(id_provincias_asignacion != 0)
	            {
	            	 $id_cantones_asignacion.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_provincias_asignacion:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Afiliacion","devuelveCanton"); ?>", datos, function(resultado) {

	          		  if(resultado.length==0)
	          		   {
	          				$id_cantones_asignacion.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_cantones_asignacion.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_cantones_asignacion.append("<option value= " +value.id_cantones +" >" + value.nombre_cantones  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');


	            }else{

	            	var id_cantones_asignacion=$("#id_cantones_asignacion");
	            	id_cantones_asignacion.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	var id_parroquias_asignacion=$("#id_parroquias_asignacion");
	            	id_parroquias_asignacion.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            	
	            }
	            

			});
		});
	
       

	</script>
		 
		 
		 
		 
		 
		 
		 <script>
		$(document).ready(function(){

			$("#id_cantones_asignacion").change(function(){

	            // obtenemos el combo de resultado combo 2
	           var $id_parroquias_asignacion = $("#id_parroquias_asignacion");
	       	

	            // lo vaciamos
	           var id_cantones_asignacion = $(this).val();

	          
	          
	            if(id_cantones_asignacion != 0)
	            {
	            	 $id_parroquias_asignacion.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_cantones_asignacion:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Afiliacion","devuelveParroquias"); ?>", datos, function(resultado) {

	          		  if(resultado.length==0)
	          		   {
	          				$id_parroquias_asignacion.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_parroquias_asignacion.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_parroquias_asignacion.append("<option value= " +value.id_parroquias +" >" + value.nombre_parroquias  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');


	            }else{

	            	var id_parroquias_asignacion=$("#id_parroquias_asignacion");
	            	id_parroquias_asignacion.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            	
	            }
	            

			});
		});
	
       

	</script>
		    
        
        
        
        <script>
        

	       	$(document).ready(function(){


	       		
						$( "#cedula_deudor_a_garantizar" ).autocomplete({
		      				source: "<?php echo $helper->url("SolicitudPrestamo","AutocompleteCedula"); ?>",
		      				minLength: 1
		    			});
		
						$("#cedula_deudor_a_garantizar").focusout(function(){
		    				$.ajax({
		    					url:'<?php echo $helper->url("SolicitudPrestamo","AutocompleteDevuelveNombres"); ?>',
		    					type:'POST',
		    					dataType:'json',
		    					data:{cedula_deudor_a_garantizar:$('#cedula_deudor_a_garantizar').val()}
		    				}).done(function(respuesta){
		
		    					$('#nombre_deudor_a_garantizar').val(respuesta.apellidos_solicitante_datos_personales+' '+respuesta.nombres_solicitante_datos_personales);
		    				
		        			}).fail(function(respuesta) {

		        				$('#cedula_deudor_a_garantizar').val("");
		    					$('#nombre_deudor_a_garantizar').val("");
		    					
		    					
		        			    
		        			  });
		    				 
		    				
		    			});  

						
		    		});
		
	     
		     </script>
        
        
      <script>
		$(document).ready(function(){

		    $fecha=$('#fecha_nacimiento_datos_personales');
		    if ($fecha[0].type!="date"){
		    	$fecha.attr('readonly','readonly');
		    	$fecha.datepicker({
		    		changeMonth: true,
		    		changeYear: true,
		    		dateFormat: "yy-mm-dd",
		    		yearRange: "1944:2018"
		    		});
		    }

		    $fecha=$('#fecha_nacimiento_conyuge');
		    if ($fecha[0].type!="date"){
		    $fecha.attr('readonly','readonly');
		    $fecha.datepicker({
	    		changeMonth: true,
	    		changeYear: true,
	    		dateFormat: "yy-mm-dd",
	    		yearRange: "1900:2018"
	    		});
		    }

		}); 

	</script> 
		
		
		<script type="text/javascript">
      $(document).ready(function(){

          
      $("#tipo_participe_datos_prestamo").click(function() {
			
          var tipo_participe_datos_prestamo = $(this).val();
			
          if(tipo_participe_datos_prestamo == 'Garante' )
          {
       	   $("#div_tipo_participe_datos_prestamo").fadeIn("slow");
          }
       	
          else
          {
        	  if(tipo_participe_datos_prestamo==0){

					   $("#div_tipo_participe_datos_prestamo").fadeOut("slow");
				}else{
					   $("#cedula_deudor_a_garantizar").val("");
		               $("#nombre_deudor_a_garantizar").val("");
		               $("#div_tipo_participe_datos_prestamo").fadeOut("slow");
				}
           	   
          }
         
	    });
	    
	    $("#tipo_participe_datos_prestamo").change(function() {
			
              
              var tipo_participe_datos_prestamo = $(this).val();
				
              
              if(tipo_participe_datos_prestamo == 'Garante')
              {
           	   $("#div_tipo_participe_datos_prestamo").fadeIn("slow");
              }
           	
              else
              {

				    if(tipo_participe_datos_prestamo==0){

						$("#div_tipo_participe_datos_prestamo").fadeOut("slow");
					}else{
						$("#cedula_deudor_a_garantizar").val("");
			               $("#nombre_deudor_a_garantizar").val("");
			               $("#div_tipo_participe_datos_prestamo").fadeOut("slow");
					}

              }
              
              
		    });
	}); 	
	   
      </script>
        
		
		
		
		
		
		        
		        
		       
    <script type="text/javascript">

    
     $(document).ready(function(){

          
      $("#tipo_pago_cuenta_bancaria").click(function() {
			
          var tipo_pago_cuenta_bancaria = $(this).val();
			
          if(tipo_pago_cuenta_bancaria == 'Depósito' || tipo_pago_cuenta_bancaria == 'Retira Cheque' )
          {
       	   $("#div_tipo_pago_cuenta_bancaria").fadeIn("slow");
          }
       	
          else
          {
        	  if(tipo_pago_cuenta_bancaria==0){

					$("#div_tipo_pago_cuenta_bancaria").fadeOut("slow");
				}else{
					  // $("#id_banco_cuenta_bancaria").val("0");
		              // $("#tipo_cuenta_cuenta_bancaria").val("0");
		              // $("#numero_cuenta_cuenta_bancaria").val("");
		           	  // $("#div_tipo_pago_cuenta_bancaria").fadeOut("slow");
				}
           	   
          }
         
	    });
	    
	    $("#tipo_pago_cuenta_bancaria").change(function() {
			
              
              var tipo_pago_cuenta_bancaria = $(this).val();
				
              
              if(tipo_pago_cuenta_bancaria == 'Depósito' || tipo_pago_cuenta_bancaria == 'Retira Cheque')
              {
           	   $("#div_tipo_pago_cuenta_bancaria").fadeIn("slow");
              }
           	
              else
              {

				    if(tipo_pago_cuenta_bancaria==0){

						$("#div_tipo_pago_cuenta_bancaria").fadeOut("slow");
					}else{
						  // $("#id_banco_cuenta_bancaria").val("0");
			              // $("#tipo_cuenta_cuenta_bancaria").val("0");
			              // $("#numero_cuenta_cuenta_bancaria").val("");
			           	  // $("#div_tipo_pago_cuenta_bancaria").fadeOut("slow");
					}

                  
              
              }
              
              
		    });
	}); 	
      
      </script>
        
       
        
        
               
		        
    <script type="text/javascript">
      $(document).ready(function(){
          
      $("#tipo_vivienda").click(function() {
			
          var tipo_vivienda = $(this).val();
			
          if(tipo_vivienda == 'Propia' )
          {
           $("#nombre_propietario_vivienda").val("");
           $("#celular_propietario_vivienda").val("");
       	   $("#div_tipo_vivienda").fadeOut("slow");
          }
       	
          else
          {

			if(tipo_vivienda==0){
			$("#div_tipo_vivienda").fadeOut("slow");
			}else{

			$("#div_tipo_vivienda").fadeIn("slow");
		  }
              
       	   
          }
         
	    });
	    
	    $("#tipo_vivienda").change(function() {
			
              
              var tipo_vivienda = $(this).val();
				
              
              if(tipo_vivienda == 'Propia')
              {
            	  $("#nombre_propietario_vivienda").val("");
                  $("#celular_propietario_vivienda").val("");
           	   $("#div_tipo_vivienda").fadeOut("slow");
              }
           	
              else
              {
            	  if(tipo_vivienda==0){
          			$("#div_tipo_vivienda").fadeOut("slow");
          			}else{

          			$("#div_tipo_vivienda").fadeIn("slow");
          		  }
              }
              
              
		    });
	}); 	
	   
      </script>
        
        
        
        
        
        	        
    <script type="text/javascript">
    $(document).ready(function(){

          
      $("#id_estado_civil_datos_personales").click(function() {
			
          var id_estado_civil_datos_personales = $(this).val();
			
          if(id_estado_civil_datos_personales == 1 || id_estado_civil_datos_personales == 5 || id_estado_civil_datos_personales == 3)
          {
        	  if(id_estado_civil_datos_personales == 0 )
              {
           	   
              }else{
            	  $("#numero_cedula_conyuge").val("");
            	  $("#apellidos_conyuge").val("");
            	  $("#nombres_conyuge").val("");
            	  $("#id_sexo_conyuge").val("0");
            	  $("#fecha_nacimiento_conyuge").val("");
            	  $("#convive_afiliado_conyuge").val("0");
            	  $("#numero_telefonico_conyuge").val("");
            	  $("#actividad_economica_conyuge").val("0");
            	  
              }
          }
       	
         
         
	    });
	    
	    $("#id_estado_civil_datos_personales").change(function() {
			
              
              var id_estado_civil_datos_personales = $(this).val();
				
              
              if(id_estado_civil_datos_personales == 1 || id_estado_civil_datos_personales == 5 || id_estado_civil_datos_personales == 3)
              {
            	  if(id_estado_civil_datos_personales == 0 )
                  {
               	   
                  }else{
                	  $("#numero_cedula_conyuge").val("");
                	  $("#apellidos_conyuge").val("");
                	  $("#nombres_conyuge").val("");
                	  $("#id_sexo_conyuge").val("0");
                	  $("#fecha_nacimiento_conyuge").val("");
                	  $("#convive_afiliado_conyuge").val("0");
                	  $("#numero_telefonico_conyuge").val("");
                	  $("#actividad_economica_conyuge").val("0");
                	  
                  }
              	   
              }
           	
             
              
              
		    });
	}); 	
	   
      </script>
        
        
        
       
      
       <script >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#generar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	  var id_sucursales                                  = $("#id_sucursales").val();
		    	  var tipo_participe_datos_prestamo       			 = $("#tipo_participe_datos_prestamo").val();
		    	  var monto_datos_prestamo        					 = $("#monto_datos_prestamo").val();
		    	  var plazo_datos_prestamo        					 = $("#plazo_datos_prestamo").val();
		    	  var destino_dinero_datos_prestamo        			 = $("#destino_dinero_datos_prestamo").val();
		    	  var id_banco_cuenta_bancaria        				 = $("#id_banco_cuenta_bancaria").val();
		    	  var tipo_cuenta_cuenta_bancaria        			 = $("#tipo_cuenta_cuenta_bancaria").val();
		    	  var numero_cuenta_cuenta_bancaria        			 = $("#numero_cuenta_cuenta_bancaria").val();
		    	  var numero_cedula_datos_personales        		 = $("#numero_cedula_datos_personales").val();
		    	  var apellidos_solicitante_datos_personales         = $("#apellidos_solicitante_datos_personales").val();
		    	  var nombres_solicitante_datos_personales           = $("#nombres_solicitante_datos_personales").val();
		    	  var correo_solicitante_datos_personales            = $("#correo_solicitante_datos_personales").val();
		    	  var id_sexo_datos_personales        				 = $("#id_sexo_datos_personales").val();
		    	  var fecha_nacimiento_datos_personales        		 = $("#fecha_nacimiento_datos_personales").val();
		    	  var id_estado_civil_datos_personales        		 = $("#id_estado_civil_datos_personales").val();
		    	  var separacion_bienes_datos_personales        	 = $("#separacion_bienes_datos_personales").val();
		    	  var cargas_familiares_datos_personales        	 = $("#cargas_familiares_datos_personales").val();
		    	  var numero_hijos_datos_personales        			 = $("#numero_hijos_datos_personales").val();
		    	  var nivel_educativo_datos_personales        		 = $("#nivel_educativo_datos_personales").val();
		    	  var id_provincias_vivienda        				 = $("#id_provincias_vivienda").val();
		    	  var id_cantones_vivienda        					 = $("#id_cantones_vivienda").val();
		    	  var id_parroquias_vivienda        				 = $("#id_parroquias_vivienda").val();
		    	  var barrio_sector_vivienda        				 = $("#barrio_sector_vivienda").val();
		    	  var ciudadela_conjunto_etapa_manzana_vivienda      = $("#ciudadela_conjunto_etapa_manzana_vivienda").val();
		    	  var calle_vivienda        						 = $("#calle_vivienda").val();
		    	  var numero_calle_vivienda        					 = $("#numero_calle_vivienda").val();
		    	  var intersecion_vivienda        					 = $("#intersecion_vivienda").val();
		    	  var tipo_vivienda        							 = $("#tipo_vivienda").val();
		    	  var vivienda_hipotecada_vivienda        			 = $("#vivienda_hipotecada_vivienda").val();
		    	  var tiempo_residencia_vivienda        			 = $("#tiempo_residencia_vivienda").val();
		    	  var nombre_propietario_vivienda        			 = $("#nombre_propietario_vivienda").val();
		    	  var celular_propietario_vivienda        			 = $("#celular_propietario_vivienda").val();
		    	  var referencia_direccion_domicilio_vivienda        = $("#referencia_direccion_domicilio_vivienda").val();
		    	  var numero_casa_solicitante        				 = $("#numero_casa_solicitante").val();
		    	  var numero_celular_solicitante        			 = $("#numero_celular_solicitante").val();
		    	  var numero_trabajo_solicitante        			 = $("#numero_trabajo_solicitante").val();
		    	  var extension_solicitante        					 = $("#extension_solicitante").val();
		    	  var mode_solicitante        						 = $("#mode_solicitante").val();
		    	  var apellidos_referencia_personal        			 = $("#apellidos_referencia_personal").val();
		    	  var nombres_referencia_personal        			 = $("#nombres_referencia_personal").val();
		    	  var relacion_referencia_personal        			 = $("#relacion_referencia_personal").val();
		    	  var numero_telefonico_referencia_personal          = $("#numero_telefonico_referencia_personal").val();
		    	  var apellidos_referencia_familiar        			 = $("#apellidos_referencia_familiar").val();
		    	  var nombres_referencia_familiar        			 = $("#nombres_referencia_familiar").val();
		    	  var parentesco_referencia_familiar        		 = $("#parentesco_referencia_familiar").val();
		    	  var numero_telefonico_referencia_familiar          = $("#numero_telefonico_referencia_familiar").val();
		    	  var id_entidades        							 = $("#id_entidades").val();
		    	  var id_provincias_asignacion        				 = $("#id_provincias_asignacion").val();
		    	  var id_cantones_asignacion        				 = $("#id_cantones_asignacion").val();
		    	  var id_parroquias_asignacion        				 = $("#id_parroquias_asignacion").val();
		    	  var numero_telefonico_datos_laborales        		 = $("#numero_telefonico_datos_laborales").val();
		    	  var interseccion_datos_laborales        			 = $("#interseccion_datos_laborales").val();
		    	  var calle_datos_laborales        					 = $("#calle_datos_laborales").val();
		    	  var cargo_actual_datos_laborales        			 = $("#cargo_actual_datos_laborales").val();
		    	  var sueldo_total_info_economica        			 = $("#sueldo_total_info_economica").val();
		    	  var cuota_prestamo_ordinario_info_economica        = $("#cuota_prestamo_ordinario_info_economica").val();
		    	  var arriendos_info_economica        				 = $("#arriendos_info_economica").val();
		    	  var cuota_prestamo_emergente_info_economica        = $("#cuota_prestamo_emergente_info_economica").val();
		    	  var honorarios_profesionales_info_economica        = $("#honorarios_profesionales_info_economica").val();
		    	  var cuota_otros_prestamos_info_economica           = $("#cuota_otros_prestamos_info_economica").val();
		    	  var comisiones_info_economica        				 = $("#comisiones_info_economica").val();
		    	  var cuota_prestamo_iess_info_economica        	 = $("#cuota_prestamo_iess_info_economica").val();
		    	  var horas_suplementarias_info_economica        	 = $("#horas_suplementarias_info_economica").val();
		    	  var arriendos_egre_info_economica        			 = $("#arriendos_egre_info_economica").val();
		    	  var alimentacion_info_economica        			 = $("#alimentacion_info_economica").val();
		    	  var otros_ingresos_1_info_economica        		 = $("#otros_ingresos_1_info_economica").val();
		    	  var valor_ingresos_1_info_economica        		 = $("#valor_ingresos_1_info_economica").val();
		    	  var estudios_info_economica        				 = $("#estudios_info_economica").val();
		    	  var otros_ingresos_2_info_economica        		 = $("#otros_ingresos_2_info_economica").val();
		    	  var valor_ingresos_2_info_economica        		 = $("#valor_ingresos_2_info_economica").val();
		    	  var pago_servicios_basicos_info_economica          = $("#pago_servicios_basicos_info_economica").val();
		    	  var otros_ingresos_3_info_economica        		 = $("#otros_ingresos_3_info_economica").val();
		    	  var valor_ingresos_3_info_economica        		 = $("#valor_ingresos_3_info_economica").val();
		    	  var pago_tarjetas_credito_info_economica        	 = $("#pago_tarjetas_credito_info_economica").val();
		    	  var otros_ingresos_4_info_economica        		 = $("#otros_ingresos_4_info_economica").val();
		    	  var valor_ingresos_4_info_economica        		 = $("#valor_ingresos_4_info_economica").val();
		    	  var afiliacion_cooperativas_info_economica         = $("#afiliacion_cooperativas_info_economica").val();
		    	  var otros_ingresos_5_info_economica        		 = $("#otros_ingresos_5_info_economica").val();
		    	  var valor_ingresos_5_info_economica       		 = $("#valor_ingresos_5_info_economica").val();
		    	  var ahorro_info_economica        					 = $("#ahorro_info_economica").val();
		    	  var otros_ingresos_6_info_economica        		 = $("#otros_ingresos_6_info_economica").val();
		    	  var valor_ingresos_6_info_economica        		 = $("#valor_ingresos_6_info_economica").val();
		    	  var impuesto_renta_info_economica        			 = $("#impuesto_renta_info_economica").val();
		    	  var otros_ingresos_7_info_economica        		 = $("#otros_ingresos_7_info_economica").val();
		    	  var valor_ingresos_7_info_economica        		 = $("#valor_ingresos_7_info_economica").val();
		    	  var otros_ingresos_8_info_economica        		 = $("#otros_ingresos_8_info_economica").val();
		    	  var valor_ingresos_8_info_economica        		 = $("#valor_ingresos_8_info_economica").val();
		    	  var otros_egresos_1_info_economica        		 = $("#otros_egresos_1_info_economica").val();
		    	  var valor_egresos_1_info_economica        		 = $("#valor_egresos_1_info_economica").val();
		    	  var numero_cedula_conyuge        					 = $("#numero_cedula_conyuge").val();
		    	  var apellidos_conyuge        						 = $("#apellidos_conyuge").val();
		    	  var nombres_conyuge        						 = $("#nombres_conyuge").val();
		    	  var id_sexo_conyuge        						 = $("#id_sexo_conyuge").val();
		    	  var fecha_nacimiento_conyuge        				 = $("#fecha_nacimiento_conyuge").val();
		    	  var convive_afiliado_conyuge        				 = $("#convive_afiliado_conyuge").val();
		    	  var numero_telefonico_conyuge        				 = $("#numero_telefonico_conyuge").val();
		    	  var actividad_economica_conyuge        			 = $("#actividad_economica_conyuge").val();
				  
				  var id_tipo_creditos                               = $("#id_tipo_creditos").val();   
				  var tipo_pago_cuenta_bancaria                      = $("#tipo_pago_cuenta_bancaria").val();     

				  var tiempo = tiempo || 1000;
				  


				  var cedula_deudor_a_garantizar                     = $("#cedula_deudor_a_garantizar").val(); 
				  var nombre_deudor_a_garantizar                     = $("#nombre_deudor_a_garantizar").val(); 



				  var contador=0;
				  
				  

				  if (id_sucursales == 0)
			    	{
				    	
			    		$("#mensaje_id_sucursales").text("Seleccione Sucursal");
			    		$("#mensaje_id_sucursales").fadeIn("slow"); //Muestra mensaje de error
			    		 
			    		 $("html, body").animate({ scrollTop: $(mensaje_id_sucursales).offset().top }, tiempo);
			    		 return false;
			           
				    }
			    	else 
			    	{
			    		$("#mensaje_id_sucursales").fadeOut("slow"); //Muestra mensaje de error
			            
					}  
				  
				  if (id_tipo_creditos == 0)
			    	{
				    	
			    		$("#mensaje_id_tipo_creditos").text("Seleccione Tipo");
			    		$("#mensaje_id_tipo_creditos").fadeIn("slow"); //Muestra mensaje de error
			    		 
			    		 $("html, body").animate({ scrollTop: $(mensaje_id_tipo_creditos).offset().top }, tiempo);
			    		 return false;
			           
				    }
			    	else 
			    	{
			    		$("#mensaje_id_tipo_creditos").fadeOut("slow"); //Muestra mensaje de error
			            
					}    
		    	
		    	
		    	   
				
		    	/*if (monto_datos_prestamo == 0.00)
		    	{
			    	
		    		$("#mensaje_monto_datos_prestamo").text("Introduzca un monto");
		    		$("#mensaje_monto_datos_prestamo").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_monto_datos_prestamo).offset().top }, tiempo);
		             return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_monto_datos_prestamo").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	
		    	if (plazo_datos_prestamo == "")
		    	{
			    	
		    		$("#mensaje_plazo_datos_prestamo").text("Introduzca Plazo");
		    		$("#mensaje_plazo_datos_prestamo").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_plazo_datos_prestamo).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_plazo_datos_prestamo").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
					*/	    	
			
		    	if (destino_dinero_datos_prestamo == 0)
		    	{
		    		
		    		$("#mensaje_destino_dinero_datos_prestamo").text("Seleccione Destino");
		    		$("#mensaje_destino_dinero_datos_prestamo").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_destino_dinero_datos_prestamo).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_destino_dinero_datos_prestamo").fadeOut("slow"); //Muestra mensaje de error
		            
				}



				if (tipo_participe_datos_prestamo == 0)
			    {
				    	
			    		$("#mensaje_tipo_participe_datos_prestamo").text("Seleccione Tipo");
			    		$("#mensaje_tipo_participe_datos_prestamo").fadeIn("slow"); //Muestra mensaje de error
			    		
			    		$("html, body").animate({ scrollTop: $(mensaje_tipo_participe_datos_prestamo).offset().top }, tiempo);
			            return false;
				    }
			    	else 
			    	{
			    		$("#mensaje_tipo_participe_datos_prestamo").fadeOut("slow"); //Muestra mensaje de error
			            
					} 


               if (tipo_participe_datos_prestamo=="Garante"){


            	   if (cedula_deudor_a_garantizar == "")
   			       {
   				    	
   			    		$("#mensaje_cedula_deudor_a_garantizar").text("Ingrese cédula del deudor");
   			    		$("#mensaje_cedula_deudor_a_garantizar").fadeIn("slow"); //Muestra mensaje de error
   			    		
   			    		$("html, body").animate({ scrollTop: $(mensaje_cedula_deudor_a_garantizar).offset().top }, tiempo);
   			            return false;
   				    }
   			    	else 
   			    	{
   			    		$("#mensaje_cedula_deudor_a_garantizar").fadeOut("slow"); //Muestra mensaje de error
   			            
   					} 


            	   if (nombre_deudor_a_garantizar == "")
   			       {
   				    	
   			    		$("#mensaje_nombre_deudor_a_garantizar").text("Ingrese nombre del deudor");
   			    		$("#mensaje_nombre_deudor_a_garantizar").fadeIn("slow"); //Muestra mensaje de error
   			    		
   			    		$("html, body").animate({ scrollTop: $(mensaje_nombre_deudor_a_garantizar).offset().top }, tiempo);
   			            return false;
   				    }
   			    	else 
   			    	{
   			    		$("#mensaje_nombre_deudor_a_garantizar").fadeOut("slow"); //Muestra mensaje de error
   			            
   					} 

            	  
               }else 
		    	{
		    		$("#mensaje_cedula_deudor_a_garantizar").fadeOut("slow"); //Muestra mensaje de error
		    		$("#mensaje_nombre_deudor_a_garantizar").fadeOut("slow"); //Muestra mensaje de error
		            
				} 
				

			    	
		    	if (tipo_pago_cuenta_bancaria==0){
		    		$("#mensaje_tipo_pago_cuenta_bancaria").text("Seleccione Tipo");
		    		$("#mensaje_tipo_pago_cuenta_bancaria").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_tipo_pago_cuenta_bancaria).offset().top }, tiempo);
		            return false;
				}else 
		    	{
		    		$("#mensaje_tipo_pago_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
		            
				}


				 if(tipo_pago_cuenta_bancaria =='Depósito' || tipo_pago_cuenta_bancaria =='Retira Cheque'){


				    	
				    	if (id_banco_cuenta_bancaria == 0 )
				    	{
					    	
				    		$("#mensaje_id_banco_cuenta_bancaria").text("Seleccione Banco");
				    		$("#mensaje_id_banco_cuenta_bancaria").fadeIn("slow"); //Muestra mensaje de error
				            
				            $("html, body").animate({ scrollTop: $(mensaje_id_banco_cuenta_bancaria).offset().top }, tiempo);
				            return false;
					    }
				    	else 
				    	{
				    		$("#mensaje_id_banco_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
				            
						}
		
		
				    	
				    	if (tipo_cuenta_cuenta_bancaria == 0 )
				    	{
					    	
				    		$("#mensaje_tipo_cuenta_cuenta_bancaria").text("Seleccione Tipo Cuenta");
				    		$("#mensaje_tipo_cuenta_cuenta_bancaria").fadeIn("slow"); //Muestra mensaje de error
				           
				            $("html, body").animate({ scrollTop: $(mensaje_tipo_cuenta_cuenta_bancaria).offset().top }, tiempo);
				            return false;
					    }
				    	else 
				    	{
				    		$("#mensaje_tipo_cuenta_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
				            
						}
		
		
				    	if (numero_cuenta_cuenta_bancaria == "" )
				    	{
					    	
				    		$("#mensaje_numero_cuenta_cuenta_bancaria").text("Ingrese # Cuenta");
				    		$("#mensaje_numero_cuenta_cuenta_bancaria").fadeIn("slow"); //Muestra mensaje de error
				            
				            $("html, body").animate({ scrollTop: $(mensaje_numero_cuenta_cuenta_bancaria).offset().top }, tiempo);
				            return false;
					    }
				    	else 
				    	{
				    		$("#mensaje_numero_cuenta_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
				            
						}
				
				}else{
					$("#mensaje_id_banco_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
					$("#mensaje_tipo_cuenta_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
					$("#mensaje_numero_cuenta_cuenta_bancaria").fadeOut("slow"); //Muestra mensaje de error
					}


		    	if (numero_cedula_datos_personales == "" )
		    	{
			    	
		    		$("#mensaje_numero_cedula_datos_personales").text("Ingrese # cedula");
		    		$("#mensaje_numero_cedula_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_cedula_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{

					if(numero_cedula_datos_personales.length==10){

						$("#mensaje_numero_cedula_datos_personales").fadeOut("slow"); //Muestra mensaje de error
					}else{
						
						$("#mensaje_numero_cedula_datos_personales").text("Ingrese 10 dígitos");
			    		$("#mensaje_numero_cedula_datos_personales").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_numero_cedula_datos_personales).offset().top }, tiempo);
			            return false;
					}

			    	
		    		
		            
				}
				
		    	if (apellidos_solicitante_datos_personales == "" )
		    	{
			    	
		    		$("#mensaje_apellidos_solicitante_datos_personales").text("Ingrese Apellidos");
		    		$("#mensaje_apellidos_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_apellidos_solicitante_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		contador=0;
		    		numeroPalabras=0;
		    		contador = apellidos_solicitante_datos_personales.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

					$("#mensaje_apellidos_solicitante_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
					}else{

						$("#mensaje_apellidos_solicitante_datos_personales").text("Ingrese 2 Apellidos");
			    		$("#mensaje_apellidos_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_apellidos_solicitante_datos_personales).offset().top }, tiempo);
			            return false;
					}
		    		
		    		
 
				}
				
		    	if (nombres_solicitante_datos_personales == "" )
		    	{
			    	
		    		$("#mensaje_nombres_solicitante_datos_personales").text("Ingrese Nombres");
		    		$("#mensaje_nombres_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nombres_solicitante_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{


		    		contador=0;
		    		numeroPalabras=0;
		    		contador = nombres_solicitante_datos_personales.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_nombres_solicitante_datos_personales").fadeOut("slow"); //Muestra mensaje de error
			             
					}else{

						$("#mensaje_nombres_solicitante_datos_personales").text("Ingrese 2 Nombres");
			    		$("#mensaje_nombres_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_nombres_solicitante_datos_personales).offset().top }, tiempo);
			            return false;
					}
			    	
		    		
				}


		    	if (correo_solicitante_datos_personales == "")
		    	{
			    	
		    		$("#mensaje_correo_solicitante_datos_personales").text("Introduzca un correo");
		    		$("#mensaje_correo_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_correo_solicitante_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else if (regex.test($('#correo_solicitante_datos_personales').val().trim()))
		    	{
		    		$("#mensaje_correo_solicitante_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	else 
		    	{
		    		$("#mensaje_correo_solicitante_datos_personales").text("Introduzca un correo Valido");
		    		$("#mensaje_correo_solicitante_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_correo_solicitante_datos_personales).offset().top }, tiempo);
		            return false;	
			    }

			    
				
		    	if (id_sexo_datos_personales == 0 )
		    	{
			    	
		    		$("#mensaje_id_sexo_datos_personales").text("Seleccione Género");
		    		$("#mensaje_id_sexo_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_sexo_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_sexo_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
		    	if (fecha_nacimiento_datos_personales == "" )
		    	{
			    	
		    		$("#mensaje_fecha_nacimiento_datos_personales").text("Seleccione Fecha");
		    		$("#mensaje_fecha_nacimiento_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_fecha_nacimiento_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		if (fecha_nacimiento_datos_personales < '1944-01-01' ) {
			    	
		    			$("#mensaje_fecha_nacimiento_datos_personales").text("Año minimo 1944");
			    		$("#mensaje_fecha_nacimiento_datos_personales").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_fecha_nacimiento_datos_personales).offset().top }, tiempo);
			            return false;
		    		}else{
		    			$("#mensaje_fecha_nacimiento_datos_personales").fadeOut("slow"); //Muestra mensaje de error
			    	}
				}

		    	if (id_estado_civil_datos_personales == 0 )
		    	{
			    	
		    		$("#mensaje_id_estado_civil_datos_personales").text("Seleccione Estado Civil");
		    		$("#mensaje_id_estado_civil_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_estado_civil_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_estado_civil_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (separacion_bienes_datos_personales == 0 )
		    	{
			    	
		    		$("#mensaje_separacion_bienes_datos_personales").text("Seleccione Separación de Bienes");
		    		$("#mensaje_separacion_bienes_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_separacion_bienes_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_separacion_bienes_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (cargas_familiares_datos_personales == 0 )
		    	{
			    	
		    		$("#mensaje_cargas_familiares_datos_personales").text("Seleccione Cargas Familiares");
		    		$("#mensaje_cargas_familiares_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_cargas_familiares_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_cargas_familiares_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (numero_hijos_datos_personales == "" )
		    	{
			    	
		    		$("#mensaje_numero_hijos_datos_personales").text("Seleccione # Hijos");
		    		$("#mensaje_numero_hijos_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_hijos_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_hijos_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (nivel_educativo_datos_personales == 0 )
		    	{
			    	
		    		$("#mensaje_nivel_educativo_datos_personales").text("Seleccione Nivel Educativo");
		    		$("#mensaje_nivel_educativo_datos_personales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nivel_educativo_datos_personales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nivel_educativo_datos_personales").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (id_provincias_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_id_provincias_vivienda").text("Seleccione Provincia");
		    		$("#mensaje_id_provincias_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_provincias_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_provincias_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (id_cantones_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_id_cantones_vivienda").text("Seleccione Cantón");
		    		$("#mensaje_id_cantones_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_cantones_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_cantones_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				

		    	if (id_parroquias_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_id_parroquias_vivienda").text("Seleccione Parroquia");
		    		$("#mensaje_id_parroquias_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_parroquias_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_parroquias_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
		    	if (barrio_sector_vivienda == "" )
		    	{
			    	
		    		$("#mensaje_barrio_sector_vivienda").text("Ingrese Barrio");
		    		$("#mensaje_barrio_sector_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_barrio_sector_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_barrio_sector_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				/*
		    	if (ciudadela_conjunto_etapa_manzana_vivienda == "" )
		    	{
			    	
		    		$("#mensaje_ciudadela_conjunto_etapa_manzana_vivienda").text("Ingrese Cuidadela");
		    		$("#mensaje_ciudadela_conjunto_etapa_manzana_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_ciudadela_conjunto_etapa_manzana_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_ciudadela_conjunto_etapa_manzana_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				*/
		    	if (calle_vivienda == "" )
		    	{
			    	
		    		$("#mensaje_calle_vivienda").text("Ingrese Calle");
		    		$("#mensaje_calle_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_calle_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_calle_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}


		    	if (referencia_direccion_domicilio_vivienda == "" )
		    	{
			    	
		    		$("#mensaje_referencia_direccion_domicilio_vivienda").text("Ingrese Referencia Domicilio");
		    		$("#mensaje_referencia_direccion_domicilio_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_referencia_direccion_domicilio_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_referencia_direccion_domicilio_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
				// NO VALIDE
				// numero_calle_vivienda
				// intersecion_vivienda
				
		    	if (tipo_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_tipo_vivienda").text("Seleccione Tipo Vivienda");
		    		$("#mensaje_tipo_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_tipo_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_tipo_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if (vivienda_hipotecada_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_vivienda_hipotecada_vivienda").text("Seleccione");
		    		$("#mensaje_vivienda_hipotecada_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_vivienda_hipotecada_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_vivienda_hipotecada_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
		    	if (tiempo_residencia_vivienda == 0 )
		    	{
			    	
		    		$("#mensaje_tiempo_residencia_vivienda").text("Seleccione Residencia");
		    		$("#mensaje_tiempo_residencia_vivienda").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_tiempo_residencia_vivienda).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_tiempo_residencia_vivienda").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	if (tipo_vivienda != "Propia"){

			    	if (nombre_propietario_vivienda == "" )
			    	{
				    	
			    		$("#mensaje_nombre_propietario_vivienda").text("Ingrese Nombre Propietario");
			    		$("#mensaje_nombre_propietario_vivienda").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_nombre_propietario_vivienda).offset().top }, tiempo);
			            return false;
				    }
			    	else 
			    	{

			    		contador=0;
			    		numeroPalabras=0;
			    		contador = nombre_propietario_vivienda.split(" ");
			    		numeroPalabras = contador.length;
			    		
						if(numeroPalabras==2 || numeroPalabras==4 || numeroPalabras==3){

							$("#mensaje_nombre_propietario_vivienda").fadeOut("slow"); //Muestra mensaje de error
				              
						}else{

							$("#mensaje_nombre_propietario_vivienda").text("Ingrese Nombres y Apellidos Propietario");
				    		$("#mensaje_nombre_propietario_vivienda").fadeIn("slow"); //Muestra mensaje de error
				            
				            $("html, body").animate({ scrollTop: $(mensaje_nombre_propietario_vivienda).offset().top }, tiempo);
				            return false;
						}
			    		
					}


			    	if (celular_propietario_vivienda == "" )
			    	{
				    	
			    		$("#mensaje_celular_propietario_vivienda").text("Ingrese # Celular Propietario");
			    		$("#mensaje_celular_propietario_vivienda").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_celular_propietario_vivienda).offset().top }, tiempo);
			            return false;
				    }
			    	else 
			    	{
			    		$("#mensaje_celular_propietario_vivienda").fadeOut("slow"); //Muestra mensaje de error
			            
					}

		    	}else{

		    		$("#mensaje_nombre_propietario_vivienda").fadeOut("slow"); //Muestra mensaje de error
		    		$("#mensaje_celular_propietario_vivienda").fadeOut("slow"); //Muestra mensaje de error
		              
			    }

				
				
		    	
				
		    	if (numero_casa_solicitante == "" )
		    	{
			    	
		    		$("#mensaje_numero_casa_solicitante").text("Ingrese # telefónico");
		    		$("#mensaje_numero_casa_solicitante").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_casa_solicitante).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_casa_solicitante").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	if (numero_celular_solicitante == "" )
		    	{
			    	
		    		$("#mensaje_numero_celular_solicitante").text("Ingrese # celular");
		    		$("#mensaje_numero_celular_solicitante").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_celular_solicitante).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_celular_solicitante").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
		    	if (numero_trabajo_solicitante == "" )
		    	{
			    	
		    		$("#mensaje_numero_trabajo_solicitante").text("Ingrese # Trabajo");
		    		$("#mensaje_numero_trabajo_solicitante").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_trabajo_solicitante).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_trabajo_solicitante").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                // NO SE VALIDO
                // extension_solicitante
                // mode_solicitante
                
                
                if (apellidos_referencia_personal == "" )
		    	{
			    	
		    		$("#mensaje_apellidos_referencia_personal").text("Ingrese apellidos");
		    		$("#mensaje_apellidos_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_apellidos_referencia_personal).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{


		    		contador=0;
		    		numeroPalabras=0;
		    		contador = apellidos_referencia_personal.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_apellidos_referencia_personal").fadeOut("slow"); //Muestra mensaje de error
				           
			             
					}else{

						$("#mensaje_apellidos_referencia_personal").text("Ingrese 2 apellidos");
			    		$("#mensaje_apellidos_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_apellidos_referencia_personal).offset().top }, tiempo);
			            return false;
					}

			    	
		    		 
				}
                if (nombres_referencia_personal == "" )
		    	{
			    	
		    		$("#mensaje_nombres_referencia_personal").text("Ingrese nombres");
		    		$("#mensaje_nombres_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nombres_referencia_personal).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{


		    		contador=0;
		    		numeroPalabras=0;
		    		contador = nombres_referencia_personal.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_nombres_referencia_personal").fadeOut("slow"); //Muestra mensaje de error
				               
			             
					}else{
						$("#mensaje_nombres_referencia_personal").text("Ingrese 2 nombres");
			    		$("#mensaje_nombres_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_nombres_referencia_personal).offset().top }, tiempo);
			            return false;
					}
			    	
		    		 
				}
                if (relacion_referencia_personal == 0 )
		    	{
			    	
		    		$("#mensaje_relacion_referencia_personal").text("Seleccione Relación");
		    		$("#mensaje_relacion_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_relacion_referencia_personal).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_relacion_referencia_personal").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                if (numero_telefonico_referencia_personal == "" )
		    	{
			    	
		    		$("#mensaje_numero_telefonico_referencia_personal").text("Ingrese # telefónico");
		    		$("#mensaje_numero_telefonico_referencia_personal").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_telefonico_referencia_personal).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_telefonico_referencia_personal").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				
                if (apellidos_referencia_familiar == "" )
		    	{
			    	
		    		$("#mensaje_apellidos_referencia_familiar").text("Ingrese apellidos");
		    		$("#mensaje_apellidos_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_apellidos_referencia_familiar).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{

		    		contador=0;
		    		numeroPalabras=0;
		    		contador = apellidos_referencia_familiar.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_apellidos_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
			                    
			             
					}else{
						$("#mensaje_apellidos_referencia_familiar").text("Ingrese 2 apellidos");
			    		$("#mensaje_apellidos_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_apellidos_referencia_familiar).offset().top }, tiempo);
			            return false;
					}
			    	
		    		
				}
				
                if (nombres_referencia_familiar == "" )
		    	{
			    	
		    		$("#mensaje_nombres_referencia_familiar").text("Ingrese nombres");
		    		$("#mensaje_nombres_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nombres_referencia_familiar).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{


		    		contador=0;
		    		numeroPalabras=0;
		    		contador = nombres_referencia_familiar.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_nombres_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
			                    
			             
					}else{
						$("#mensaje_nombres_referencia_familiar").text("Ingrese 2 nombres");
			    		$("#mensaje_nombres_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_nombres_referencia_familiar).offset().top }, tiempo);
			            return false;
					}
					
		    		
				}
				
                if (parentesco_referencia_familiar == 0 )
		    	{
			    	
		    		$("#mensaje_parentesco_referencia_familiar").text("Seleccione Parentesco");
		    		$("#mensaje_parentesco_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_parentesco_referencia_familiar).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_parentesco_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                if (numero_telefonico_referencia_familiar == "" )
		    	{
			    	
		    		$("#mensaje_numero_telefonico_referencia_familiar").text("Ingrese # telefónico");
		    		$("#mensaje_numero_telefonico_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_telefonico_referencia_familiar).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_telefonico_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
		            
				}



				if(apellidos_referencia_personal==apellidos_referencia_familiar){

					$("#mensaje_apellidos_referencia_familiar").text("Datos existen en ref. personal");
		    		$("#mensaje_apellidos_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_apellidos_referencia_familiar).offset().top }, tiempo);
		            return false;
					
				}else{
					$("#mensaje_apellidos_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
				}


				if(nombres_referencia_personal==nombres_referencia_familiar){

					$("#mensaje_nombres_referencia_familiar").text("Datos existen en ref. personal");
		    		$("#mensaje_nombres_referencia_familiar").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nombres_referencia_familiar).offset().top }, tiempo);
		            return false;
				}else{
					$("#mensaje_nombres_referencia_familiar").fadeOut("slow"); //Muestra mensaje de error
				}
				
				
                if (id_entidades == 0 )
		    	{
			    	
		    		$("#mensaje_id_entidades").text("Selecione Institución");
		    		$("#mensaje_id_entidades").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_entidades).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_entidades").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (id_provincias_asignacion == 0 )
		    	{
			    	
		    		$("#mensaje_id_provincias_asignacion").text("Seleccione Provincia");
		    		$("#mensaje_id_provincias_asignacion").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_provincias_asignacion).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_provincias_asignacion").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                if (id_cantones_asignacion == 0 )
		    	{
			    	
		    		$("#mensaje_id_cantones_asignacion").text("Seleccione Cantón");
		    		$("#mensaje_id_cantones_asignacion").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_cantones_asignacion).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_cantones_asignacion").fadeOut("slow"); //Muestra mensaje de error
		            
				}
                if (id_parroquias_asignacion == 0 )
		    	{
			    	
		    		$("#mensaje_id_parroquias_asignacion").text("Seleccione Parroquia");
		    		$("#mensaje_id_parroquias_asignacion").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_parroquias_asignacion).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_parroquias_asignacion").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				/*
                if (numero_telefonico_datos_laborales == "" )
		    	{
			    	
		    		$("#mensaje_numero_telefonico_datos_laborales").text("Ingrese # telefónico");
		    		$("#mensaje_numero_telefonico_datos_laborales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_telefonico_datos_laborales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_telefonico_datos_laborales").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				*/
				// no se valido
				// interseccion_datos_laborales
				// calle_datos_laborales
				
								
                if (cargo_actual_datos_laborales == "" )
		    	{
			    	
		    		$("#mensaje_cargo_actual_datos_laborales").text("Ingrese Cargo");
		    		$("#mensaje_cargo_actual_datos_laborales").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_cargo_actual_datos_laborales).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_cargo_actual_datos_laborales").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                if (sueldo_total_info_economica == 0.00 )
		    	{
			    	
		    		$("#mensaje_sueldo_total_info_economica").text("Ingrese Sueldo Total");
		    		$("#mensaje_sueldo_total_info_economica").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_sueldo_total_info_economica).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_sueldo_total_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				
                if (otros_ingresos_1_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_1_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_1_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_1_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		           
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_1_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (valor_ingresos_1_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_1_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_1_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_1_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_1_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (otros_ingresos_2_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_2_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_2_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_2_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_2_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_2_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_2_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (valor_ingresos_2_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_2_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_2_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_2_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		           
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_2_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_2_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_2_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (otros_ingresos_3_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_3_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_3_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_3_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_3_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_3_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_3_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (valor_ingresos_3_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_3_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_3_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_3_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_3_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_3_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_3_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}



                if (otros_ingresos_4_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_4_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_4_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_4_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_4_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_4_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_4_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (valor_ingresos_4_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_4_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_4_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_4_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_4_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_4_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_4_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (otros_ingresos_5_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_5_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_5_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_5_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_5_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_5_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_5_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (valor_ingresos_5_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_5_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_5_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_5_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		           
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_5_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_5_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_5_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (otros_ingresos_6_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_6_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_6_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_6_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_6_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_6_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_6_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}



                if (valor_ingresos_6_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_6_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_6_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_6_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		           
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_6_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_6_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_6_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (otros_ingresos_7_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_7_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_7_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_7_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_7_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_7_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_7_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (valor_ingresos_7_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_7_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_7_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_7_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_7_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_7_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_7_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (otros_ingresos_8_info_economica != "" )
		    	{
			    	
                	if (valor_ingresos_8_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_ingresos_8_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_ingresos_8_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_ingresos_8_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_ingresos_8_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_ingresos_8_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (valor_ingresos_8_info_economica > 0.00)
		    	{
			    	
                	if (otros_ingresos_8_info_economica == ""  )
    		    	{
    			    	
    		    		$("#mensaje_otros_ingresos_8_info_economica").text("Ingrese Detalle");
    		    		$("#mensaje_otros_ingresos_8_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_ingresos_8_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_ingresos_8_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_ingresos_8_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				

                if (otros_egresos_1_info_economica != "" )
		    	{
			    	
                	if (valor_egresos_1_info_economica == 0.00 )
    		    	{
    			    	
    		    		$("#mensaje_valor_egresos_1_info_economica").text("Ingrese Valor");
    		    		$("#mensaje_valor_egresos_1_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_valor_egresos_1_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_valor_egresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_valor_egresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}



                if (valor_egresos_1_info_economica  > 0.00 )
		    	{
			    	
                	if ( otros_egresos_1_info_economica == "" )
    		    	{
    			    	
    		    		$("#mensaje_otros_egresos_1_info_economica").text("Ingrese detalle");
    		    		$("#mensaje_otros_egresos_1_info_economica").fadeIn("slow"); //Muestra mensaje de error
    		            
    		            $("html, body").animate({ scrollTop: $(mensaje_otros_egresos_1_info_economica).offset().top }, tiempo);
    		            return false;
    			    }
    		    	else 
    		    	{
    		    		$("#mensaje_otros_egresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
    		            
    				}
			    }
		    	else 
		    	{
		    		$("#mensaje_otros_egresos_1_info_economica").fadeOut("slow"); //Muestra mensaje de error
		            
				}
				

			if(id_estado_civil_datos_personales != 1 && id_estado_civil_datos_personales != 5 && id_estado_civil_datos_personales != 3) {

				
                if (numero_cedula_conyuge == "" )
		    	{
			    	
		    		$("#mensaje_numero_cedula_conyuge").text("Ingrese # cedula");
		    		$("#mensaje_numero_cedula_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_cedula_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{

		    		if(numero_cedula_conyuge.length==10){

						$("#mensaje_numero_cedula_conyuge").fadeOut("slow"); //Muestra mensaje de error
					}else{
						
						$("#mensaje_numero_cedula_conyuge").text("Ingrese 10 dígitos");
			    		$("#mensaje_numero_cedula_conyuge").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_numero_cedula_conyuge).offset().top }, tiempo);
			            return false;
					}
			        
				}

				
                if (apellidos_conyuge == "" )
		    	{
			    	
		    		$("#mensaje_apellidos_conyuge").text("Ingrese apellidos");
		    		$("#mensaje_apellidos_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_apellidos_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{




		    		contador=0;
		    		numeroPalabras=0;
		    		contador = apellidos_conyuge.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_apellidos_conyuge").fadeOut("slow"); //Muestra mensaje de error
			                     
			             
					}else{
						$("#mensaje_apellidos_conyuge").text("Ingrese 2 apellidos");
			    		$("#mensaje_apellidos_conyuge").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_apellidos_conyuge).offset().top }, tiempo);
			            return false;
					}
			    	
		    		
				}
                if (nombres_conyuge == "" )
		    	{
			    	
		    		$("#mensaje_nombres_conyuge").text("Ingrese nombres");
		    		$("#mensaje_nombres_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_nombres_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{



		    		contador=0;
		    		numeroPalabras=0;
		    		contador = nombres_conyuge.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras>0){

						$("#mensaje_nombres_conyuge").fadeOut("slow"); //Muestra mensaje de error
				                     
			             
					}else{
						$("#mensaje_nombres_conyuge").text("Ingrese 2 nombres");
			    		$("#mensaje_nombres_conyuge").fadeIn("slow"); //Muestra mensaje de error
			            
			            $("html, body").animate({ scrollTop: $(mensaje_nombres_conyuge).offset().top }, tiempo);
			            return false;
					}
			    	
		    		 
				}
                if (id_sexo_conyuge == 0 )
		    	{
			    	
		    		$("#mensaje_id_sexo_conyuge").text("Seleccione sexo");
		    		$("#mensaje_id_sexo_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_id_sexo_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_sexo_conyuge").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (fecha_nacimiento_conyuge == "" )
		    	{
			    	
		    		$("#mensaje_fecha_nacimiento_conyuge").text("Seleccione fecha");
		    		$("#mensaje_fecha_nacimiento_conyuge").fadeIn("slow"); //Muestra mensaje de error
		           
		            $("html, body").animate({ scrollTop: $(mensaje_fecha_nacimiento_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_fecha_nacimiento_conyuge").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (convive_afiliado_conyuge == 0 )
		    	{
			    	
		    		$("#mensaje_convive_afiliado_conyuge").text("Seleccione");
		    		$("#mensaje_convive_afiliado_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_convive_afiliado_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_convive_afiliado_conyuge").fadeOut("slow"); //Muestra mensaje de error
		            
				}


                if (numero_telefonico_conyuge == "" )
		    	{
			    	
		    		$("#mensaje_numero_telefonico_conyuge").text("Ingrese # telefónico");
		    		$("#mensaje_numero_telefonico_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_numero_telefonico_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_telefonico_conyuge").fadeOut("slow"); //Muestra mensaje de error
		            
				}

                if (actividad_economica_conyuge == 0 )
		    	{
			    	
		    		$("#mensaje_actividad_economica_conyuge").text("Seleccione");
		    		$("#mensaje_actividad_economica_conyuge").fadeIn("slow"); //Muestra mensaje de error
		            
		            $("html, body").animate({ scrollTop: $(mensaje_actividad_economica_conyuge).offset().top }, tiempo);
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_actividad_economica_conyuge").fadeOut("slow"); //Muestra mensaje de error
		    		
		            
				}


			}else{
				$("#mensaje_numero_cedula_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_apellidos_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_nombres_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_id_sexo_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_fecha_nacimiento_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_convive_afiliado_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_numero_telefonico_conyuge").fadeOut("slow"); //Muestra mensaje de error
				$("#mensaje_actividad_economica_conyuge").fadeOut("slow"); //Muestra mensaje de error




				
				

			}
				
			}); 


		   
		    

		        $( "#id_sucursales" ).focus(function() {
				  $("#mensaje_id_sucursales").fadeOut("slow");
			    });
		        $( "#id_tipo_creditos" ).focus(function() {
				  $("#mensaje_id_tipo_creditos").fadeOut("slow");
			    });
		        $( "#monto_datos_prestamo" ).focus(function() {
					  $("#mensaje_monto_datos_prestamo").fadeOut("slow");
				});
		        $( "#plazo_datos_prestamo" ).focus(function() {
					  $("#mensaje_plazo_datos_prestamo").fadeOut("slow");
				});
		        $( "#destino_dinero_datos_prestamo" ).focus(function() {
					  $("#mensaje_destino_dinero_datos_prestamo").fadeOut("slow");
				});
		        $( "#tipo_participe_datos_prestamo" ).focus(function() {
					  $("#mensaje_tipo_participe_datos_prestamo").fadeOut("slow");
				});
		        $( "#cedula_deudor_a_garantizar" ).focus(function() {
					  $("#mensaje_cedula_deudor_a_garantizar").fadeOut("slow");
				});
		        $( "#nombre_deudor_a_garantizar" ).focus(function() {
					  $("#mensaje_nombre_deudor_a_garantizar").fadeOut("slow");
				});
		        $( "#tipo_pago_cuenta_bancaria" ).focus(function() {
					  $("#mensaje_tipo_pago_cuenta_bancaria").fadeOut("slow");
				});
		        $( "#id_banco_cuenta_bancaria" ).focus(function() {
					  $("#mensaje_id_banco_cuenta_bancaria").fadeOut("slow");
				});
		        $( "#tipo_cuenta_cuenta_bancaria" ).focus(function() {
					  $("#mensaje_tipo_cuenta_cuenta_bancaria").fadeOut("slow");
				});
		        $( "#numero_cuenta_cuenta_bancaria" ).focus(function() {
					  $("#mensaje_numero_cuenta_cuenta_bancaria").fadeOut("slow");
				});
		        $( "#numero_cedula_datos_personales" ).focus(function() {
					  $("#mensaje_numero_cedula_datos_personales").fadeOut("slow");
				});


		        $( "#apellidos_solicitante_datos_personales" ).focus(function() {
					  $("#mensaje_apellidos_solicitante_datos_personales").fadeOut("slow");
				});
		        $( "#nombres_solicitante_datos_personales" ).focus(function() {
					  $("#mensaje_nombres_solicitante_datos_personales").fadeOut("slow");
				});
		        $( "#correo_solicitante_datos_personales" ).focus(function() {
					  $("#mensaje_correo_solicitante_datos_personales").fadeOut("slow");
				});
		        $( "#id_sexo_datos_personales" ).focus(function() {
					  $("#mensaje_id_sexo_datos_personales").fadeOut("slow");
				});

		        
		        $( "#fecha_nacimiento_datos_personales" ).focus(function() {
					  $("#mensaje_fecha_nacimiento_datos_personales").fadeOut("slow");
				});
		        $( "#id_estado_civil_datos_personales" ).focus(function() {
					  $("#mensaje_id_estado_civil_datos_personales").fadeOut("slow");
				});
		        $( "#separacion_bienes_datos_personales" ).focus(function() {
					  $("#mensaje_separacion_bienes_datos_personales").fadeOut("slow");
				});
		        $( "#cargas_familiares_datos_personales" ).focus(function() {
					  $("#mensaje_cargas_familiares_datos_personales").fadeOut("slow");
				});
		        $( "#numero_hijos_datos_personales" ).focus(function() {
					  $("#mensaje_numero_hijos_datos_personales").fadeOut("slow");
				});



		        $( "#nivel_educativo_datos_personales" ).focus(function() {
					  $("#mensaje_nivel_educativo_datos_personales").fadeOut("slow");
				});
		        $( "#id_provincias_vivienda" ).focus(function() {
					  $("#mensaje_id_provincias_vivienda").fadeOut("slow");
				});
		        $( "#id_cantones_vivienda" ).focus(function() {
					  $("#mensaje_id_cantones_vivienda").fadeOut("slow");
				});
		        $( "#id_parroquias_vivienda" ).focus(function() {
					  $("#mensaje_id_parroquias_vivienda").fadeOut("slow");
				});
		        $( "#barrio_sector_vivienda" ).focus(function() {
					  $("#mensaje_barrio_sector_vivienda").fadeOut("slow");
				});
		        $( "#ciudadela_conjunto_etapa_manzana_vivienda" ).focus(function() {
					  $("#mensaje_ciudadela_conjunto_etapa_manzana_vivienda").fadeOut("slow");
				});
		        $( "#calle_vivienda" ).focus(function() {
					  $("#mensaje_calle_vivienda").fadeOut("slow");
				});

		        
			    $( "#numero_calle_vivienda" ).focus(function() {
					  $("#mensaje_numero_calle_vivienda").fadeOut("slow");
				});
		        $( "#intersecion_vivienda" ).focus(function() {
					  $("#mensaje_intersecion_vivienda").fadeOut("slow");
				});
		        $( "#tipo_vivienda" ).focus(function() {
					  $("#mensaje_tipo_vivienda").fadeOut("slow");
				});
		        $( "#vivienda_hipotecada_vivienda" ).focus(function() {
					  $("#mensaje_vivienda_hipotecada_vivienda").fadeOut("slow");
				});
		        $( "#tiempo_residencia_vivienda" ).focus(function() {
					  $("#mensaje_tiempo_residencia_vivienda").fadeOut("slow");
				});
		        $( "#nombre_propietario_vivienda" ).focus(function() {
					  $("#mensaje_nombre_propietario_vivienda").fadeOut("slow");
				});



		        $( "#celular_propietario_vivienda" ).focus(function() {
					  $("#mensaje_celular_propietario_vivienda").fadeOut("slow");
				});
		        $( "#referencia_direccion_domicilio_vivienda" ).focus(function() {
					  $("#mensaje_referencia_direccion_domicilio_vivienda").fadeOut("slow");
				});
		        $( "#numero_casa_solicitante" ).focus(function() {
					  $("#mensaje_numero_casa_solicitante").fadeOut("slow");
				});
		        $( "#numero_celular_solicitante" ).focus(function() {
					  $("#mensaje_numero_celular_solicitante").fadeOut("slow");
				});
		        $( "#numero_trabajo_solicitante" ).focus(function() {
					  $("#mensaje_numero_trabajo_solicitante").fadeOut("slow");
				});
		        $( "#extension_solicitante" ).focus(function() {
					  $("#mensaje_extension_solicitante").fadeOut("slow");
				});
		        $( "#mode_solicitante" ).focus(function() {
					  $("#mensaje_mode_solicitante").fadeOut("slow");
				});
		        $( "#apellidos_referencia_personal" ).focus(function() {
					  $("#mensaje_apellidos_referencia_personal").fadeOut("slow");
				});


		        $( "#nombres_referencia_personal" ).focus(function() {
					  $("#mensaje_nombres_referencia_personal").fadeOut("slow");
				});
		        $( "#relacion_referencia_personal" ).focus(function() {
					  $("#mensaje_relacion_referencia_personal").fadeOut("slow");
				});
		        $( "#numero_telefonico_referencia_personal" ).focus(function() {
					  $("#mensaje_numero_telefonico_referencia_personal").fadeOut("slow");
				});
		        $( "#apellidos_referencia_familiar" ).focus(function() {
					  $("#mensaje_apellidos_referencia_familiar").fadeOut("slow");
				});
		        $( "#nombres_referencia_familiar" ).focus(function() {
					  $("#mensaje_nombres_referencia_familiar").fadeOut("slow");
				});
		        $( "#parentesco_referencia_familiar" ).focus(function() {
					  $("#mensaje_parentesco_referencia_familiar").fadeOut("slow");
				});
		        $( "#numero_telefonico_referencia_familiar" ).focus(function() {
					  $("#mensaje_numero_telefonico_referencia_familiar").fadeOut("slow");
				});





		        $( "#id_entidades" ).focus(function() {
					  $("#mensaje_id_entidades").fadeOut("slow");
				});
		        $( "#id_provincias_asignacion" ).focus(function() {
					  $("#mensaje_id_provincias_asignacion").fadeOut("slow");
				});
		        $( "#id_cantones_asignacion" ).focus(function() {
					  $("#mensaje_id_cantones_asignacion").fadeOut("slow");
				});
		        $( "#id_parroquias_asignacion" ).focus(function() {
					  $("#mensaje_id_parroquias_asignacion").fadeOut("slow");
				});
		        $( "#numero_telefonico_datos_laborales" ).focus(function() {
					  $("#mensaje_numero_telefonico_datos_laborales").fadeOut("slow");
				});

				
		        $( "#interseccion_datos_laborales" ).focus(function() {
					  $("#mensaje_interseccion_datos_laborales").fadeOut("slow");
				});
		        $( "#calle_datos_laborales" ).focus(function() {
					  $("#mensaje_calle_datos_laborales").fadeOut("slow");
				});
		        $( "#cargo_actual_datos_laborales" ).focus(function() {
					  $("#mensaje_cargo_actual_datos_laborales").fadeOut("slow");
				});
		        $( "#sueldo_total_info_economica" ).focus(function() {
					  $("#mensaje_sueldo_total_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_1_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_1_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_2_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_2_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_3_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_3_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_4_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_4_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_5_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_5_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_6_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_6_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_7_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_7_info_economica").fadeOut("slow");
				});
		        $( "#otros_ingresos_8_info_economica" ).focus(function() {
					  $("#mensaje_otros_ingresos_8_info_economica").fadeOut("slow");
				});
		        $( "#otros_egresos_1_info_economica" ).focus(function() {
					  $("#mensaje_otros_egresos_1_info_economica").fadeOut("slow");
				});


		        $( "#valor_ingresos_1_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_1_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_2_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_2_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_3_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_3_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_4_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_4_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_5_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_5_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_6_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_6_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_7_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_7_info_economica").fadeOut("slow");
				});
		        $( "#valor_ingresos_8_info_economica" ).focus(function() {
					  $("#mensaje_valor_ingresos_8_info_economica").fadeOut("slow");
				});

		        $( "#valor_egresos_1_info_economica" ).focus(function() {
					  $("#mensaje_valor_egresos_1_info_economica").fadeOut("slow");
				});


		        $( "#numero_cedula_conyuge" ).focus(function() {
					  $("#mensaje_numero_cedula_conyuge").fadeOut("slow");
				});
		        $( "#apellidos_conyuge" ).focus(function() {
					  $("#mensaje_apellidos_conyuge").fadeOut("slow");
				});
		        $( "#nombres_conyuge" ).focus(function() {
					  $("#mensaje_nombres_conyuge").fadeOut("slow");
				});
		        $( "#id_sexo_conyuge" ).focus(function() {
					  $("#mensaje_id_sexo_conyuge").fadeOut("slow");
				});
		        $( "#fecha_nacimiento_conyuge" ).focus(function() {
					  $("#mensaje_fecha_nacimiento_conyuge").fadeOut("slow");
				});
		        $( "#convive_afiliado_conyuge" ).focus(function() {
					  $("#mensaje_convive_afiliado_conyuge").fadeOut("slow");
				});
		        $( "#numero_telefonico_conyuge" ).focus(function() {
					  $("#mensaje_numero_telefonico_conyuge").fadeOut("slow");
				});
		        $( "#actividad_economica_conyuge" ).focus(function() {
					  $("#mensaje_actividad_economica_conyuge").fadeOut("slow");
				});
		      
				 
		}); 

	</script>
        
       
       
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
	
       
       
        <script type="text/javascript">

            enviando = false; //Obligaremos a entrar el if en el primer submit
            
            function checkSubmit() {
                if (!enviando) {
            		enviando= true;
            		return true;
                } else {
                    //Si llega hasta aca significa que pulsaron 2 veces el boton submit
                   
                  
                    return false;
                }
            }
        
        </script>
       
       
			        
    </head>
    
    
    <body class="nav-md">
    
      <?php
        
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        ?>
    
    
    
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col  menu_fixed">
          <div class="left_col scroll-view">
            <?php include("view/modulos/logo.php"); ?>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include("view/modulos/menu_profile.php"); ?>
            <!-- /menu profile quick info -->

            <br />
			<?php include("view/modulos/menu.php"); ?>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
		<?php include("view/modulos/head.php"); ?>	
        <!-- /top navigation -->

        <!-- page content -->
		<div class="right_col" role="main">        
           
    	<div class="container">
        <section class="content-header">
         <small><?php echo $fecha; ?></small>
         <ol class=" pull-right breadcrumb">
         <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Solicitud Préstamo</li>
         </ol>
         </section>
       
  
		
		<div class="col-md-12 col-lg-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Solicitud<small>Préstamo</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
           
          <form  action="<?php echo $helper->url("SolicitudPrestamo","InsertaSolicitudPrestamo"); ?>" method="post" onsubmit="return checkSubmit();" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
           
      
         <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
         
         
          <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos del Préstamo</h4>
	         </div>
	         <div class="panel-body">
			 
			  <div class="row">
			  
			  					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_sucursales" class="control-label">Sucursal a Tramitar:</label>
                                                       <select name="id_sucursales" id="id_sucursales"  class="form-control" disabled>
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSucursales as $res) {?>
                        										<option value="<?php echo $res->id_sucursales; ?>" <?php if ($res->id_sucursales == $resEdit->id_sucursales ){  echo  ' selected="selected" '  ;}  ?> ><?php echo $res->nombre_sucursales; ?> </option>
                        							        
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sucursales" class="errores"></div>
                                </div>
                                </div>
			  
			  					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_tipo_creditos" class="control-label">Tipo Crédito:</label>
                                                       <select name="id_tipo_creditos" id="id_tipo_creditos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultTipoCredito as $res) {?>
                        										<option value="<?php echo $res->id_tipo_creditos; ?>" <?php if ($res->id_tipo_creditos == $resEdit->id_tipo_creditos ){  echo  ' selected="selected" '  ;}  ?> ><?php echo $res->nombre_tipo_creditos; ?> </option>
                        							        
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_tipo_creditos" class="errores"></div>
                                </div>
                                </div>
			  					
			  					
			  				
			  
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="monto_datos_prestamo" class="control-label">Monto en Dólares:</label>
                                                      <input type="text" class="form-control cantidades1" id="monto_datos_prestamo" name="monto_datos_prestamo" value="<?php echo $resEdit->monto_datos_prestamo; ?>" 
                                                      data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false" readonly>
                                                      <div id="mensaje_monto_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="plazo_datos_prestamo" class="control-label">Plazo en meses:</label>
                                                      <input type="number" class="form-control" id="plazo_datos_prestamo" name="plazo_datos_prestamo" value="<?php echo $resEdit->plazo_datos_prestamo; ?>" placeholder="# meses.." readonly>
                                                      <div id="mensaje_plazo_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="destino_dinero_datos_prestamo" class="control-label">Destino del Dinero:</label>
                                                      <select name="destino_dinero_datos_prestamo" id="destino_dinero_datos_prestamo"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Estudios" <?php if($resEdit->destino_dinero_datos_prestamo == 'Estudios'){echo ' selected="selected" ' ;}else{} ?>>Estudios</option>
                        							  <option value="Vivienda" <?php if($resEdit->destino_dinero_datos_prestamo == 'Vivienda'){echo ' selected="selected" ' ;}else{} ?>>Vivienda</option>
                        							  <option value="Vehículo" <?php if($resEdit->destino_dinero_datos_prestamo == 'Vehículo'){echo ' selected="selected" ' ;}else{} ?>>Vehículo</option>
                        							  <option value="Consumo" <?php if($resEdit->destino_dinero_datos_prestamo == 'Consumo'){echo ' selected="selected" ' ;}else{} ?>>Consumo</option>
                        							  <option value="Otro" <?php if($resEdit->destino_dinero_datos_prestamo == 'Otro'){echo ' selected="selected" ' ;}else{} ?>>Otro</option>
                        							  </select> 
                                                      <div id="mensaje_destino_dinero_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tipo_participe_datos_prestamo" class="control-label">Tipo Participe:</label>
                                                      <input type="hidden" class="form-control" id="id_solicitud_prestamo" name="id_solicitud_prestamo" value="<?php echo $resEdit->id_solicitud_prestamo; ?>">
                                                      <select name="tipo_participe_datos_prestamo" id="tipo_participe_datos_prestamo"  class="form-control" disabled>
                                                      <?php foreach($resultTipoParticipe as $val=>$desc) {?>
					                                         <option value="<?php echo $val ?>" <?php if ($val == $resEdit->tipo_participe_datos_prestamo )  echo  ' selected="selected" '  ;  ?> ><?php echo $desc ?> </option>
					                                  <?php } ?>
                        							  </select> 
                                                      <div id="mensaje_tipo_participe_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
               </div>
			 
  			</div>
  			</div>
  			
  			
  			
  			<div id="div_tipo_participe_datos_prestamo" style="display: none;">
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Deudor a Garantizar</h4>
	         </div>
	         <div class="panel-body">
			 <div class="row">
			  
         	                  
			  				   
			  				    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="cedula_deudor_a_garantizar" class="control-label">Cédula deudor:</label>
                                                      <input type="number" class="form-control" id="cedula_deudor_a_garantizar" name="cedula_deudor_a_garantizar" value="<?php echo $resEdit->cedula_deudor_a_garantizar; ?>" placeholder="# cédula deudor..">
                                                      <div id="mensaje_cedula_deudor_a_garantizar" class="errores"></div>
                                </div>
                                </div>
			  				    
                                <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="nombre_deudor_a_garantizar" class="control-label">Apellidos y Nombres Deudor:</label>
                                                      <input type="text" class="form-control" id="nombre_deudor_a_garantizar" name="nombre_deudor_a_garantizar" value="<?php echo $resEdit->nombre_deudor_a_garantizar; ?>" placeholder="nombre deudor.." readonly>
                                                      <div id="mensaje_nombre_deudor_a_garantizar" class="errores"></div>
                                </div>
                                </div>
                     		  
            </div>
			 
  			</div>
  			</div>
            </div>
           
           
           
  			
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Cuenta Bancaria</h4>
	         </div>
	         <div class="panel-body">
			 
			 
			 <div class="row">
			  
			  					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="tipo_pago_cuenta_bancaria" class="control-label">Tipo Transacción:</label>
                                                      <select name="tipo_pago_cuenta_bancaria" id="tipo_pago_cuenta_bancaria"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Depósito" <?php if($resEdit->tipo_pago_cuenta_bancaria == 'Depósito'){echo ' selected="selected" ' ;}else{} ?>>Depósito</option>
                        							  <option value="Retira Cheque" <?php if($resEdit->tipo_pago_cuenta_bancaria == 'Retira Cheque'){echo ' selected="selected" ' ;}else{} ?>>Retira Cheque</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_pago_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
			  
			  
			                    <div id="div_tipo_pago_cuenta_bancaria" style="display: none;">
			  					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_banco_cuenta_bancaria" class="control-label">Banco:</label>
                                                       <select name="id_banco_cuenta_bancaria" id="id_banco_cuenta_bancaria"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultBancos as $res) {?>
                        										<option value="<?php echo $res->nombre_bancos; ?>" <?php if ($res->nombre_bancos == $resEdit->nombre_banco_cuenta_bancaria ){  echo  ' selected="selected" '  ;}  ?> ><?php echo $res->nombre_bancos; ?> </option>
                        							        
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_banco_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
			        		    
                    		    
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="tipo_cuenta_cuenta_bancaria" class="control-label">Tipo Cuenta:</label>
                                                      <select name="tipo_cuenta_cuenta_bancaria" id="tipo_cuenta_cuenta_bancaria"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Ahorros"   <?php if($resEdit->tipo_cuenta_cuenta_bancaria == 'Ahorros'){echo ' selected="selected" ' ;}else{} ?>>Ahorros</option>
                        							  <option value="Corriente" <?php if($resEdit->tipo_cuenta_cuenta_bancaria == 'Corriente'){echo ' selected="selected" ' ;}else{} ?>>Corriente</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_cuenta_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_cuenta_cuenta_bancaria" class="control-label">Número Cuenta:</label>
                                                      <input type="number" class="form-control" id="numero_cuenta_cuenta_bancaria" name="numero_cuenta_cuenta_bancaria" value="<?php echo $resEdit->numero_cuenta_cuenta_bancaria; ?>" placeholder="# cuenta..">
                                                      <div id="mensaje_numero_cuenta_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
                     		    </div>
         	  </div>
			 
  			</div>
  			</div>
           
        
              <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Personales</h4>
	         </div>
	         <div class="panel-body">
			 
			  <div class="row">
			  
			   					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_cedula_datos_personales" class="control-label">Número Cédula:</label>
                                                     <input type="number" class="form-control" id="numero_cedula_datos_personales" name="numero_cedula_datos_personales" value="<?php echo $resEdit->numero_cedula_datos_personales; ?>" placeholder="cédula..">
                                                      <div id="mensaje_numero_cedula_datos_personales" class="errores"></div>
                                </div>
                                </div>
			  
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_solicitante_datos_personales" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_solicitante_datos_personales" name="apellidos_solicitante_datos_personales" value="<?php echo $resEdit->apellidos_solicitante_datos_personales; ?>" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_solicitante_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_solicitante_datos_personales" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_solicitante_datos_personales" name="nombres_solicitante_datos_personales" value="<?php echo $resEdit->nombres_solicitante_datos_personales; ?>" placeholder="nombres..">
                                                      <div id="mensaje_nombres_solicitante_datos_personales" class="errores"></div>
                                </div>
                                </div>
                    		    
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="correo_solicitante_datos_personales" class="control-label">Correo Electrónico:</label>
                                                      <input type="email" class="form-control" id="correo_solicitante_datos_personales" name="correo_solicitante_datos_personales" value="<?php echo $resEdit->correo_solicitante_datos_personales; ?>" placeholder="correo electrónico..">
                                                      <div id="mensaje_correo_solicitante_datos_personales" class="errores"></div>
                                </div>
             					</div>	
              </div>
              
              <div class="row">
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_sexo_datos_personales" class="control-label">Género:</label>
                                                       <select name="id_sexo_datos_personales" id="id_sexo_datos_personales"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSexo as $res) {?>
                        										<option value="<?php echo $res->id_sexo; ?>" <?php if ($res->id_sexo == $resEdit->id_sexo_datos_personales ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_sexo; ?> </option>
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sexo_datos_personales" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="fecha_nacimiento_datos_personales" class="control-label">Fecha Nacimiento:</label>
                                                      <input type="date" class="form-control" id="fecha_nacimiento_datos_personales" name="fecha_nacimiento_datos_personales"  value="<?php echo $resEdit->fecha_nacimiento_datos_personales; ?>">
                                                      <div id="mensaje_fecha_nacimiento_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_estado_civil_datos_personales" class="control-label">Estado Civil:</label>
                                                      <select name="id_estado_civil_datos_personales" id="id_estado_civil_datos_personales"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultEstado_civil as $res) {?>
                        										<option value="<?php echo $res->id_estado_civil; ?>" <?php if ($res->id_estado_civil == $resEdit->id_estado_civil_datos_personales ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_estado_civil; ?> </option>
                        							        <?php } ?>
                        							  </select> 
                                                      <div id="mensaje_id_estado_civil_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="separacion_bienes_datos_personales" class="control-label">Separación de Bienes:</label>
                                                      <select name="separacion_bienes_datos_personales" id="separacion_bienes_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si" <?php if($resEdit->separacion_bienes_datos_personales == 'Si'){echo ' selected="selected" ' ;}else{} ?>>Si</option>
                        							  <option value="No" <?php if($resEdit->separacion_bienes_datos_personales == 'No'){echo ' selected="selected" ' ;}else{} ?>>No</option>
                        							  </select> 
			                                          <div id="mensaje_separacion_bienes_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="cargas_familiares_datos_personales" class="control-label">Cargas Familiares:</label>
                                                      <select name="cargas_familiares_datos_personales" id="cargas_familiares_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si"  <?php if($resEdit->cargas_familiares_datos_personales == 'Si'){echo ' selected="selected" ' ;}else{} ?>>Si</option>
                        							  <option value="No"  <?php if($resEdit->cargas_familiares_datos_personales == 'No'){echo ' selected="selected" ' ;}else{} ?>>No</option>
                        							  </select> 
			                                          <div id="mensaje_cargas_familiares_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_hijos_datos_personales" class="control-label">Número Hijos:</label>
                                                      <select name="numero_hijos_datos_personales" id="numero_hijos_datos_personales"  class="form-control" >
                                                      <option value="" selected="selected">--Seleccione--</option>
                        							  <option value="0"  <?php if($resEdit->numero_hijos_datos_personales == '0'){echo ' selected="selected" ' ;}else{} ?>>0</option>
                        							  <option value="1"  <?php if($resEdit->numero_hijos_datos_personales == '1'){echo ' selected="selected" ' ;}else{} ?>>1</option>
                        							  <option value="2"  <?php if($resEdit->numero_hijos_datos_personales == '2'){echo ' selected="selected" ' ;}else{} ?>>2</option>
                        							  <option value="3"  <?php if($resEdit->numero_hijos_datos_personales == '3'){echo ' selected="selected" ' ;}else{} ?>>3</option>
                        							  <option value="4"  <?php if($resEdit->numero_hijos_datos_personales == '4'){echo ' selected="selected" ' ;}else{} ?>>4</option>
                        							  <option value="5"  <?php if($resEdit->numero_hijos_datos_personales == '5'){echo ' selected="selected" ' ;}else{} ?>>5</option>
                        							  <option value="6"  <?php if($resEdit->numero_hijos_datos_personales == '6'){echo ' selected="selected" ' ;}else{} ?>>6</option>
                        							  <option value="7"  <?php if($resEdit->numero_hijos_datos_personales == '7'){echo ' selected="selected" ' ;}else{} ?>>7</option>
                        							  <option value="8"  <?php if($resEdit->numero_hijos_datos_personales == '8'){echo ' selected="selected" ' ;}else{} ?>>8</option>
                        							  <option value="9"  <?php if($resEdit->numero_hijos_datos_personales == '9'){echo ' selected="selected" ' ;}else{} ?>>9</option>
                        							  <option value="10" <?php if($resEdit->numero_hijos_datos_personales == '10'){echo ' selected="selected" ' ;}else{} ?>>10</option>
                        							  </select> 
			                                          <div id="mensaje_numero_hijos_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
              </div>
              
              
              
              
              <div class="row">
               					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="nivel_educativo_datos_personales" class="control-label">Nivel Educativo:</label>
                                                      <select name="nivel_educativo_datos_personales" id="nivel_educativo_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Primario"      <?php if($resEdit->nivel_educativo_datos_personales == 'Primario'){echo ' selected="selected" ' ;}else{} ?>>Primario</option>
                        							  <option value="Secundario"    <?php if($resEdit->nivel_educativo_datos_personales == 'Secundario'){echo ' selected="selected" ' ;}else{} ?>>Secundario</option>
                        							  <option value="Técnico"       <?php if($resEdit->nivel_educativo_datos_personales == 'Técnico'){echo ' selected="selected" ' ;}else{} ?>>Técnico</option>
                        							  <option value="Universitario" <?php if($resEdit->nivel_educativo_datos_personales == 'Universitario'){echo ' selected="selected" ' ;}else{} ?>>Universitario</option>
                        							  <option value="Postgrado"     <?php if($resEdit->nivel_educativo_datos_personales == 'Postgrado'){echo ' selected="selected" ' ;}else{} ?>>Postgrado</option>
                        							  </select> 
                                                      <div id="mensaje_nivel_educativo_datos_personales" class="errores"></div>
                                </div>
             					</div>	
             					
             					
              </div>
			 
  			</div>
  			</div>
             
              
              
              
                       
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Datos Exactos Domicilio</h4>
	         </div>
	         <div class="panel-body">
			 <div class="row">
                    		     
             					<div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_provincias_vivienda" class="control-label">Provincia:</label>
                                                          <select name="id_provincias_vivienda" id="id_provincias_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProvincias as $res) {?>
                        										<option value="<?php echo $res->id_provincias; ?>" <?php if ($res->id_provincias == $resEdit->id_provincias_vivienda ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_provincias; ?> </option>
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_provincias_vivienda" class="errores"></div>
                                </div>
                    		    </div>       		    
                    		   
                    		    
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_cantones_vivienda" class="control-label">Cantón:</label>
                                                          <select name="id_cantones_vivienda" id="id_cantones_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                                                            <?php foreach($resultCantones as $res) {?>
                        										<option value="<?php echo $res->id_cantones; ?>"  <?php if ($res->id_cantones == $resEdit->id_cantones_vivienda ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_cantones; ?> </option>
                        							        <?php } ?>
                                                          </select> 
                                                          <div id="mensaje_id_cantones_vivienda" class="errores"></div>
                                </div>
                    		    </div>
                    		   
                    			
                    			<div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_parroquias_vivienda" class="control-label">Parroquia:</label>
                                                          <select name="id_parroquias_vivienda" id="id_parroquias_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  
                        								  <?php foreach($resultParroquias as $res) {?>
                        										<option value="<?php echo $res->id_parroquias; ?>" <?php if ($res->id_parroquias == $resEdit->id_parroquias_vivienda ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_parroquias; ?> </option>
                        							        <?php } ?>
                        							      
                        								  </select> 
                                                          <div id="mensaje_id_parroquias_vivienda" class="errores"></div>
                                </div>
                    		    </div>
                    			
            </div>
            
            
             <div class="row">
                    		    <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="barrio_sector_vivienda" class="control-label">Barrio y/o sector:</label>
                                                      <input type="text" class="form-control" id="barrio_sector_vivienda" name="barrio_sector_vivienda" value="<?php echo $resEdit->barrio_sector_vivienda; ?>" placeholder="nombre barrio..">
                                                      <div id="mensaje_barrio_sector_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="ciudadela_conjunto_etapa_manzana_vivienda" class="control-label">Ciudadela y/o conjunto / Etapa / Manzana:</label>
                                                     <input type="text" class="form-control" id="ciudadela_conjunto_etapa_manzana_vivienda" name="ciudadela_conjunto_etapa_manzana_vivienda" value="<?php echo $resEdit->ciudadela_conjunto_etapa_manzana_vivienda; ?>" placeholder="nombre conjunto..">
                                                      <div id="mensaje_ciudadela_conjunto_etapa_manzana_vivienda" class="errores"></div>
                                </div>
                                </div>
              </div>
            
            
             <div class="row">
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="calle_vivienda" class="control-label">Calle:</label>
                                                      <input type="text" class="form-control" id="calle_vivienda" name="calle_vivienda" value="<?php echo $resEdit->calle_vivienda; ?>" placeholder="nombre calle..">
                                                      <div id="mensaje_calle_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="numero_calle_vivienda" class="control-label">Número Calle:</label>
                                                     <input type="text" class="form-control" id="numero_calle_vivienda" name="numero_calle_vivienda" value="<?php echo $resEdit->numero_calle_vivienda; ?>" placeholder="# número calle..">
                                                      <div id="mensaje_numero_calle_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="intersecion_vivienda" class="control-label">Intersección:</label>
                                                     <input type="text" class="form-control" id="intersecion_vivienda" name="intersecion_vivienda" value="<?php echo $resEdit->intersecion_vivienda; ?>" placeholder="intersección..">
                                                      <div id="mensaje_intersecion_vivienda" class="errores"></div>
                                </div>
                                </div>
              </div>
              
              <div class="row">
                    		    <div class="col-lg-12 col-xs-12 col-md-12">
                    		    <div class="form-group">
                                                      <label for="referencia_direccion_domicilio_vivienda" class="control-label">Referencia de la dirección del domicilio:</label>
                                                      <input type="text" class="form-control" id="referencia_direccion_domicilio_vivienda" name="referencia_direccion_domicilio_vivienda" value="<?php echo $resEdit->referencia_direccion_domicilio_vivienda; ?>" placeholder="referencia..">
                                                      <div id="mensaje_referencia_direccion_domicilio_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		 
              </div>
              
              
              <div class="row">
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tipo_vivienda" class="control-label">Tipo Vivienda:</label>
                                                      <select name="tipo_vivienda" id="tipo_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Propia" 			  <?php if($resEdit->tipo_vivienda == 'Propia'){echo ' selected="selected" ' ;}else{} ?>>Propia</option>
                        							  <option value="Arrendada" 		  <?php if($resEdit->tipo_vivienda == 'Arrendada'){echo ' selected="selected" ' ;}else{} ?>>Arrendada</option>
                        							  <option value="Anticresis" 		  <?php if($resEdit->tipo_vivienda == 'Anticresis'){echo ' selected="selected" ' ;}else{} ?>>Anticresis</option>
                        							  <option value="Vive con Familiares" <?php if($resEdit->tipo_vivienda == 'Vive con Familiares'){echo ' selected="selected" ' ;}else{} ?>>Vive con Familiares</option>
                        							  <option value="Otra" 				  <?php if($resEdit->tipo_vivienda == 'Otra'){echo ' selected="selected" ' ;}else{} ?>>Otra</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="vivienda_hipotecada_vivienda" class="control-label">Vivienda Hipotecada:</label>
                                                      <select name="vivienda_hipotecada_vivienda" id="vivienda_hipotecada_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si" <?php if($resEdit->vivienda_hipotecada_vivienda == 'Si'){echo ' selected="selected" ' ;}else{} ?>>Si</option>
                        							  <option value="No" <?php if($resEdit->vivienda_hipotecada_vivienda == 'No'){echo ' selected="selected" ' ;}else{} ?>>No</option>
                        							  </select> 
                                                      <div id="mensaje_vivienda_hipotecada_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tiempo_residencia_vivienda" class="control-label">Tiempo Residencia:</label>
                                                      <select name="tiempo_residencia_vivienda" id="tiempo_residencia_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="1 año"  <?php if($resEdit->tiempo_residencia_vivienda == '1 año'){echo ' selected="selected" ' ;}else{} ?>>1 año</option>
                        							  <option value="2 años" <?php if($resEdit->tiempo_residencia_vivienda == '2 años'){echo ' selected="selected" ' ;}else{} ?>>2 años</option>
                        							  <option value="3 años" <?php if($resEdit->tiempo_residencia_vivienda == '3 años'){echo ' selected="selected" ' ;}else{} ?>>3 años</option>
                        							  <option value="4 años" <?php if($resEdit->tiempo_residencia_vivienda == '4 años'){echo ' selected="selected" ' ;}else{} ?>>4 años</option>
                        							  <option value="5 años" <?php if($resEdit->tiempo_residencia_vivienda == '5 años'){echo ' selected="selected" ' ;}else{} ?>>5 años</option>
                        							  <option value="6 años" <?php if($resEdit->tiempo_residencia_vivienda == '6 años'){echo ' selected="selected" ' ;}else{} ?>>6 años</option>
                        							  <option value="7 años" <?php if($resEdit->tiempo_residencia_vivienda == '7 años'){echo ' selected="selected" ' ;}else{} ?>>7 años</option>
                        							  <option value="8 años" <?php if($resEdit->tiempo_residencia_vivienda == '8 años'){echo ' selected="selected" ' ;}else{} ?>>8 años</option>
                        							  <option value="9 años" <?php if($resEdit->tiempo_residencia_vivienda == '9 años'){echo ' selected="selected" ' ;}else{} ?>>9 años</option>
                        							  <option value="10 años" <?php if($resEdit->tiempo_residencia_vivienda == '10 años'){echo ' selected="selected" ' ;}else{} ?>>10 años</option>
                        							  <option value="11 años" <?php if($resEdit->tiempo_residencia_vivienda == '11 años'){echo ' selected="selected" ' ;}else{} ?>>11 años</option>
                        							  <option value="12 años" <?php if($resEdit->tiempo_residencia_vivienda == '12 años'){echo ' selected="selected" ' ;}else{} ?>>12 años</option>
                        							  <option value="13 años" <?php if($resEdit->tiempo_residencia_vivienda == '13 años'){echo ' selected="selected" ' ;}else{} ?>>13 años</option>
                        							  <option value="14 años" <?php if($resEdit->tiempo_residencia_vivienda == '14 años'){echo ' selected="selected" ' ;}else{} ?>>14 años</option>
                        							  <option value="15 años" <?php if($resEdit->tiempo_residencia_vivienda == '15 años'){echo ' selected="selected" ' ;}else{} ?>>15 años</option>
                        							  <option value="16 años" <?php if($resEdit->tiempo_residencia_vivienda == '16 años'){echo ' selected="selected" ' ;}else{} ?>>16 años</option>
                        							  <option value="17 años" <?php if($resEdit->tiempo_residencia_vivienda == '17 años'){echo ' selected="selected" ' ;}else{} ?>>17 años</option>
                        							  <option value="18 años" <?php if($resEdit->tiempo_residencia_vivienda == '18 años'){echo ' selected="selected" ' ;}else{} ?>>18 años</option>
                        							  <option value="19 años" <?php if($resEdit->tiempo_residencia_vivienda == '19 años'){echo ' selected="selected" ' ;}else{} ?>>19 años</option>
                        							  <option value="20 años" <?php if($resEdit->tiempo_residencia_vivienda == '21 años'){echo ' selected="selected" ' ;}else{} ?>>20 años</option>
                        							  <option value="21 años" <?php if($resEdit->tiempo_residencia_vivienda == '21 años'){echo ' selected="selected" ' ;}else{} ?>>21 años</option>
                        							  <option value="22 años" <?php if($resEdit->tiempo_residencia_vivienda == '22 años'){echo ' selected="selected" ' ;}else{} ?>>22 años</option>
                        							  <option value="23 años" <?php if($resEdit->tiempo_residencia_vivienda == '23 años'){echo ' selected="selected" ' ;}else{} ?>>23 años</option>
                        							  <option value="24 años" <?php if($resEdit->tiempo_residencia_vivienda == '24 años'){echo ' selected="selected" ' ;}else{} ?>>24 años</option>
                        							  <option value="25 años" <?php if($resEdit->tiempo_residencia_vivienda == '25 años'){echo ' selected="selected" ' ;}else{} ?>>25 años</option>
                        							  <option value="26 años" <?php if($resEdit->tiempo_residencia_vivienda == '26 años'){echo ' selected="selected" ' ;}else{} ?>>26 años</option>
                        							  <option value="27 años" <?php if($resEdit->tiempo_residencia_vivienda == '27 años'){echo ' selected="selected" ' ;}else{} ?>>27 años</option>
                        							  <option value="28 años" <?php if($resEdit->tiempo_residencia_vivienda == '28 años'){echo ' selected="selected" ' ;}else{} ?>>28 años</option>
                        							  <option value="29 años" <?php if($resEdit->tiempo_residencia_vivienda == '29 años'){echo ' selected="selected" ' ;}else{} ?>>29 años</option>
                        							  <option value="30 años" <?php if($resEdit->tiempo_residencia_vivienda == '30 años'){echo ' selected="selected" ' ;}else{} ?>>30 años</option>
                        							  </select> 
                                                      <div id="mensaje_tiempo_residencia_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div id="div_tipo_vivienda" style="display: none;">
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="nombre_propietario_vivienda" class="control-label">Nombre Propietario Vivienda:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombre_propietario_vivienda" name="nombre_propietario_vivienda" value="<?php echo $resEdit->nombre_propietario_vivienda; ?>" placeholder="nombre propietario..">
                                                      <div id="mensaje_nombre_propietario_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="celular_propietario_vivienda" class="control-label">Celular Propietario Vivienda:</label>
                                                      <input type="number" class="form-control" id="celular_propietario_vivienda" name="celular_propietario_vivienda" value="<?php echo $resEdit->celular_propietario_vivienda; ?>" placeholder="# celular propietario..">
                                                      <div id="mensaje_celular_propietario_vivienda" class="errores"></div>
                                </div>
                                </div>
                                </div>
                                
              </div>
              
              
            
          
             
			</div>
  			</div>
           
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Números Telefónicos</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_casa_solicitante" class="control-label">Número Casa:</label>
                                                      <input type="number" class="form-control" id="numero_casa_solicitante" name="numero_casa_solicitante" value="<?php echo $resEdit->numero_casa_solicitante; ?>" placeholder="# casa..">
                                                      <div id="mensaje_numero_casa_solicitante" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_celular_solicitante" class="control-label">Número Celular:</label>
                                                      <input type="number" class="form-control" id="numero_celular_solicitante" name="numero_celular_solicitante" value="<?php echo $resEdit->numero_celular_solicitante; ?>" placeholder="# celular..">
                                                      <div id="mensaje_numero_celular_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_trabajo_solicitante" class="control-label">Número Trabajo:</label>
                                                      <input type="number" class="form-control" id="numero_trabajo_solicitante" name="numero_trabajo_solicitante" value="<?php echo $resEdit->numero_trabajo_solicitante; ?>" placeholder="# trabajo..">
                                                      <div id="mensaje_numero_trabajo_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="extension_solicitante" class="control-label">Extensión:</label>
                                                      <input type="number" class="form-control" id="extension_solicitante" name="extension_solicitante" value="<?php echo $resEdit->extension_solicitante; ?>" placeholder="# extensión..">
                                                      <div id="mensaje_extension_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="mode_solicitante" class="control-label">Mode:</label>
                                                      <input type="number" class="form-control" id="mode_solicitante" name="mode_solicitante" value="<?php echo $resEdit->mode_solicitante; ?>" placeholder="mode..">
                                                      <div id="mensaje_mode_solicitante" class="errores"></div>
                                </div>
            					</div>       		    
             	
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
           
            <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Referencia Personal</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_referencia_personal" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_referencia_personal" name="apellidos_referencia_personal" value="<?php echo $resEdit->apellidos_referencia_personal; ?>" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_referencia_personal" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_referencia_personal" name="nombres_referencia_personal" value="<?php echo $resEdit->nombres_referencia_personal; ?>" placeholder="nombres..">
                                                      <div id="mensaje_nombres_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="relacion_referencia_personal" class="control-label">Relación:</label>
                                                      <select name="relacion_referencia_personal" id="relacion_referencia_personal"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Amigo (a)" 			<?php if($resEdit->relacion_referencia_personal == 'Amigo (a)'){echo ' selected="selected" ' ;}else{} ?>>Amigo (a)</option>
                        							  <option value="Compadre" 				<?php if($resEdit->relacion_referencia_personal == 'Compadre'){echo ' selected="selected" ' ;}else{} ?>>Compadre</option>
                        							  <option value="Comadre" 				<?php if($resEdit->relacion_referencia_personal == 'Comadre'){echo ' selected="selected" ' ;}else{} ?>>Comadre</option>
                        							  <option value="Compañero Laboral (a)" <?php if($resEdit->relacion_referencia_personal == 'Compañero Laboral (a)'){echo ' selected="selected" ' ;}else{} ?>>Compañero Laboral (a)</option>
                        							  <option value="Jéfe (a)" 				<?php if($resEdit->relacion_referencia_personal == 'Jéfe (a)'){echo ' selected="selected" ' ;}else{} ?>>Jéfe (a)</option>
                        							  </select>
                                                      <div id="mensaje_relacion_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_referencia_personal" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_referencia_personal" name="numero_telefonico_referencia_personal" value="<?php echo $resEdit->numero_telefonico_referencia_personal; ?>" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_referencia_personal" class="errores"></div>
                                </div>
            </div>  
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Referencia Familiar (Que no viva con usted)</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
             					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_referencia_familiar" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_referencia_familiar" name="apellidos_referencia_familiar" value="<?php echo $resEdit->apellidos_referencia_familiar; ?>" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_referencia_familiar" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_referencia_familiar" name="nombres_referencia_familiar" value="<?php echo $resEdit->nombres_referencia_familiar; ?>" placeholder="nombres..">
                                                      <div id="mensaje_nombres_referencia_familiar" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="parentesco_referencia_familiar" class="control-label">Parentesco:</label>
                                                      <select name="parentesco_referencia_familiar" id="parentesco_referencia_familiar"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Primo (a)"   <?php if($resEdit->parentesco_referencia_familiar == 'Primo (a)'){echo ' selected="selected" ' ;}else{} ?>>Primo (a)</option>
                        							  <option value="Tío (a)" 	  <?php if($resEdit->parentesco_referencia_familiar == 'Tío (a)'){echo ' selected="selected" ' ;}else{} ?>>Tío (a)</option>
                        							  <option value="Hermano (a)" <?php if($resEdit->parentesco_referencia_familiar == 'Hermano (a)'){echo ' selected="selected" ' ;}else{} ?>>Hermano (a)</option>
                        							  <option value="Sobrino (a)" <?php if($resEdit->parentesco_referencia_familiar == 'Sobrino (a)'){echo ' selected="selected" ' ;}else{} ?>>Sobrino (a)</option>
                        							  <option value="Abuelo (a)"  <?php if($resEdit->parentesco_referencia_familiar == 'Abuelo (a)'){echo ' selected="selected" ' ;}else{} ?>>Abuelo (a)</option>
                        							  <option value="Hijo (a)" 	  <?php if($resEdit->parentesco_referencia_familiar == 'Hijo (a)'){echo ' selected="selected" ' ;}else{} ?>>Hijo (a)</option>
                        							  </select>
                                                      <div id="mensaje_parentesco_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_referencia_familiar" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_referencia_familiar" name="numero_telefonico_referencia_familiar" value="<?php echo $resEdit->numero_telefonico_referencia_familiar; ?>" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Datos Laborales</h4>
	         </div>
	         <div class="panel-body">
			 
			 
			 <div class="row">
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_entidades" class="control-label">Institución o Empresa:</label>
                                                      <select name="id_entidades" id="id_entidades"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultEntidades as $res) {?>
                        										<option value="<?php echo $res->id_entidades; ?>"  <?php if ($res->id_entidades == $resEdit->id_entidades ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_entidades; ?> </option>
                        							        <?php } ?>
                        						      </select> 
                                                      <div id="mensaje_id_entidades" class="errores"></div>
                                </div>
                                </div>
                    		    
             					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_provincias_asignacion" class="control-label">Provincia:</label>
                                                          <select name="id_provincias_asignacion" id="id_provincias_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProvincias as $res) {?>
                        										<option value="<?php echo $res->id_provincias; ?>" <?php if ($res->id_provincias == $resEdit->id_provincias_asignacion ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_provincias; ?> </option>
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_provincias_asignacion" class="errores"></div>
                                </div>
                    		    </div>       		    
                    		   
                    		    
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_cantones_asignacion" class="control-label">Cantón:</label>
                                                          <select name="id_cantones_asignacion" id="id_cantones_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        							      
                        							      <?php foreach($resultCantones as $res) {?>
                        										<option value="<?php echo $res->id_cantones; ?>"  <?php if ($res->id_cantones == $resEdit->id_cantones_asignacion ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_cantones; ?> </option>
                        							        <?php } ?>
                        							     
                        							      </select> 
                                                          <div id="mensaje_id_cantones_asignacion" class="errores"></div>
                                </div>
                    		    </div>
                    		   
                    			
                    			<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_parroquias_asignacion" class="control-label">Parroquia:</label>
                                                          <select name="id_parroquias_asignacion" id="id_parroquias_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  
                        								  <?php foreach($resultParroquias as $res) {?>
                        										<option value="<?php echo $res->id_parroquias; ?>" <?php if ($res->id_parroquias == $resEdit->id_parroquias_asignacion ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_parroquias; ?> </option>
                        							        <?php } ?>
                        							      
                        								  </select> 
                                                          <div id="mensaje_id_parroquias_asignacion" class="errores"></div>
                                </div>
                    		    </div>
                        		
            </div>
			 
			<div class="row"> 
			 	
              					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_datos_laborales" class="control-label">Número:</label>
                                                      <input type="text" class="form-control" id="numero_telefonico_datos_laborales" name="numero_telefonico_datos_laborales" value="<?php echo $resEdit->numero_telefonico_datos_laborales; ?>" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_datos_laborales" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="interseccion_datos_laborales" class="control-label">Intersección:</label>
                                                      <input type="text" class="form-control" id="interseccion_datos_laborales" name="interseccion_datos_laborales" value="<?php echo $resEdit->interseccion_datos_laborales; ?>" placeholder="intersección..">
                                                      <div id="mensaje_interseccion_datos_laborales" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="calle_datos_laborales" class="control-label">Calle:</label>
                                                      <input type="text" class="form-control" id="calle_datos_laborales" name="calle_datos_laborales" value="<?php echo $resEdit->calle_datos_laborales; ?>" placeholder="nombre calle..">
                                                      <div id="mensaje_calle_datos_laborales" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="cargo_actual_datos_laborales" class="control-label">Cargo Actual:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="cargo_actual_datos_laborales" name="cargo_actual_datos_laborales" value="<?php echo $resEdit->cargo_actual_datos_laborales; ?>" placeholder="cargo actual..">
                                                      <div id="mensaje_cargo_actual_datos_laborales" class="errores"></div>
                                </div>
            					</div>         			
                    		    
                    		    
            </div>      		    
			 
			 
  			</div>
  			</div>
  			
  			
  			
  			
  			
  			 <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Información Económica</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
             
            <table class="col-lg-12 col-md-12 col-xs-12 tablesorter table table-striped table-bordered dt-responsive nowrap">
            
            <tr>
            <th><b>INGRESOS MENSUALES</b></th>
            <th><b>VALOR EN DÓLARES</b></th>
            <th><b>GASTOS MENSUALES</b></th>
            <th><b>VALOR EN DÓLARES</b></th>
            </tr>
            
            <tr>
            <td>Sueldo Total</td>
            <td><input type="text" class="form-control cantidades1" id="sueldo_total_info_economica" name="sueldo_total_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->sueldo_total_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_sueldo_total_info_economica" class="errores"></div></td>
            <td>Cuota del Préstamo Ordinario CAPREMCI</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_ordinario_info_economica" name="cuota_prestamo_ordinario_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->cuota_prestamo_ordinario_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Arriendos</td>
            <td><input type="text" class="form-control cantidades1" id="arriendos_info_economica" name="arriendos_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->arriendos_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuota del Préstamo Emergente CAPREMCI</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_emergente_info_economica" name="cuota_prestamo_emergente_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->cuota_prestamo_emergente_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Honorarios Profesionales</td>
            <td><input type="text" class="form-control cantidades1" id="honorarios_profesionales_info_economica" name="honorarios_profesionales_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->honorarios_profesionales_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuotas de Otros Préstamos</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_otros_prestamos_info_economica" name="cuota_otros_prestamos_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->cuota_otros_prestamos_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Comisiones</td>
            <td><input type="text" class="form-control cantidades1" id="comisiones_info_economica" name="comisiones_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->comisiones_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuotas de Prestamos con el IESS</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_iess_info_economica" name="cuota_prestamo_iess_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->cuota_prestamo_iess_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Horas Suplementarias</td>
            <td><input type="text" class="form-control cantidades1" id="horas_suplementarias_info_economica" name="horas_suplementarias_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->horas_suplementarias_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Arriendo</td>
            <td><input type="text" class="form-control cantidades1" id="arriendos_egre_info_economica" name="arriendos_egre_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->arriendos_egre_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td colspan="2" style="text-align: center;"><b>OTROS INGRESOS (detalle)</b></td>
            <td>Alimentación</td>
            <td><input type="text" class="form-control cantidades1" id="alimentacion_info_economica" name="alimentacion_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->alimentacion_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_1_info_economica" name="otros_ingresos_1_info_economica" value="<?php echo $resEdit->otros_ingresos_1_info_economica; ?>" placeholder="nombre ingresos 1.."><div id="mensaje_otros_ingresos_1_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_1_info_economica" name="valor_ingresos_1_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_1_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_1_info_economica" class="errores"></div></td>
            <td>Estudios</td>
            <td><input type="text" class="form-control cantidades1" id="estudios_info_economica" name="estudios_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->estudios_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_2_info_economica" name="otros_ingresos_2_info_economica" value="<?php echo $resEdit->otros_ingresos_2_info_economica; ?>" placeholder="nombre ingresos 2.."><div id="mensaje_otros_ingresos_2_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_2_info_economica" name="valor_ingresos_2_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_2_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_2_info_economica" class="errores"></div></td>
            <td>Pago Servicios Básicos</td>
            <td><input type="text" class="form-control cantidades1" id="pago_servicios_basicos_info_economica" name="pago_servicios_basicos_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->pago_servicios_basicos_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_3_info_economica" name="otros_ingresos_3_info_economica" value="<?php echo $resEdit->otros_ingresos_3_info_economica; ?>" placeholder="nombre ingresos 3.."><div id="mensaje_otros_ingresos_3_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_3_info_economica" name="valor_ingresos_3_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_3_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_3_info_economica" class="errores"></div></td>
            <td>Pago Tarjetas de Crédito</td>
            <td><input type="text" class="form-control cantidades1" id="pago_tarjetas_credito_info_economica" name="pago_tarjetas_credito_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->pago_tarjetas_credito_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_4_info_economica" name="otros_ingresos_4_info_economica" value="<?php echo $resEdit->otros_ingresos_4_info_economica; ?>" placeholder="nombre ingresos 4.."><div id="mensaje_otros_ingresos_4_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_4_info_economica" name="valor_ingresos_4_info_economica" value='<?php echo $resEdit->valor_ingresos_4_info_economica; ?>' onchange="sumar_ingresos(this.value);" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_4_info_economica" class="errores"></div></td>
            <td>Afiliación a Cooperativas</td>
            <td><input type="text" class="form-control cantidades1" id="afiliacion_cooperativas_info_economica" name="afiliacion_cooperativas_info_economica" value='<?php echo $resEdit->afiliacion_cooperativas_info_economica; ?>' onchange="sumar_egresos(this.value);" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_5_info_economica" name="otros_ingresos_5_info_economica" value="<?php echo $resEdit->otros_ingresos_5_info_economica; ?>" placeholder="nombre ingresos 5.."><div id="mensaje_otros_ingresos_5_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_5_info_economica" name="valor_ingresos_5_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_5_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_5_info_economica" class="errores"></div></td>
            <td>Ahorro</td>
            <td><input type="text" class="form-control cantidades1" id="ahorro_info_economica" name="ahorro_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->ahorro_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_6_info_economica" name="otros_ingresos_6_info_economica" value="<?php echo $resEdit->otros_ingresos_6_info_economica; ?>" placeholder="nombre ingresos 6.."><div id="mensaje_otros_ingresos_6_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_6_info_economica" name="valor_ingresos_6_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_6_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_6_info_economica" class="errores"></div></td>
            <td>Impuesto a la Renta</td>
            <td><input type="text" class="form-control cantidades1" id="impuesto_renta_info_economica" name="impuesto_renta_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->impuesto_renta_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_7_info_economica" name="otros_ingresos_7_info_economica" value="<?php echo $resEdit->otros_ingresos_7_info_economica; ?>" placeholder="nombre ingresos 7.."><div id="mensaje_otros_ingresos_7_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_7_info_economica" name="valor_ingresos_7_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_7_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_7_info_economica" class="errores"></div></td>
            <td colspan="2" style="text-align: center;"><b>OTROS GASTOS (detalle)</b></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_8_info_economica" name="otros_ingresos_8_info_economica" value="<?php echo $resEdit->otros_ingresos_8_info_economica; ?>" placeholder="nombre ingresos 8.."><div id="mensaje_otros_ingresos_8_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_8_info_economica" name="valor_ingresos_8_info_economica" onchange="sumar_ingresos(this.value);" value='<?php echo $resEdit->valor_ingresos_8_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_8_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control" id="otros_egresos_1_info_economica" name="otros_egresos_1_info_economica" value="<?php echo $resEdit->otros_egresos_1_info_economica; ?>" placeholder="nombre egresos 1.."><div id="mensaje_otros_egresos_1_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_egresos_1_info_economica" name="valor_egresos_1_info_economica" onchange="sumar_egresos(this.value);" value='<?php echo $resEdit->valor_egresos_1_info_economica; ?>' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_egresos_1_info_economica" class="errores"></div></td>
            </tr>
            
           
            
            
            </table>
             
            </div>
			 
  			</div>
  			</div>
           
           
           
           
            <div class="panel panel-info">
	        <div class="panel-heading">
	        <h4><i class='glyphicon glyphicon-home'></i> Datos del Cónyuge o Pareja</h4>
	        </div>
	        <div class="panel-body">
			 
			<div class="row">
               					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_cedula_conyuge" class="control-label">Número Cédula:</label>
                                                      <input type="text" class="form-control" id="numero_cedula_conyuge" name="numero_cedula_conyuge" value="<?php echo $resEdit->numero_cedula_conyuge; ?>" placeholder="número cédula..">
                                                      <div id="mensaje_numero_cedula_conyuge" class="errores"></div>
                                </div>
            					</div>  
            					
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_conyuge" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_conyuge" name="apellidos_conyuge" value="<?php echo $resEdit->apellidos_conyuge; ?>" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_conyuge" class="errores"></div>
                                </div>
            					</div>   
                    		   
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_conyuge" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_conyuge" name="nombres_conyuge" value="<?php echo $resEdit->nombres_conyuge; ?>" placeholder="nombres..">
                                                      <div id="mensaje_nombres_conyuge" class="errores"></div>
                                </div>
            					</div>  
            
           						
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_sexo_conyuge" class="control-label">Género:</label>
                                                       <select name="id_sexo_conyuge" id="id_sexo_conyuge"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSexo as $res) {?>
                        										<option value="<?php echo $res->id_sexo; ?>" <?php if ($res->id_sexo == $resEdit->id_sexo_conyuge ){  echo  ' selected="selected" '  ;}  ?>><?php echo $res->nombre_sexo; ?> </option>
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sexo_conyuge" class="errores"></div>
                                </div>
                                </div>
             
            </div>   
			 
			 
			<div class="row">
							
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="fecha_nacimiento_conyuge" class="control-label">Fecha Nacimiento:</label>
                                                      <input type="date" class="form-control" id="fecha_nacimiento_conyuge" name="fecha_nacimiento_conyuge" min="1800-01-01" max="<?php echo date('Y-m-d');?>" value="<?php echo $resEdit->fecha_nacimiento_conyuge; ?>" >
                                                      <div id="mensaje_fecha_nacimiento_conyuge" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="convive_afiliado_conyuge" class="control-label">Convive con el Afiliado:</label>
                                                      <select name="convive_afiliado_conyuge" id="convive_afiliado_conyuge"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si" <?php if($resEdit->convive_afiliado_conyuge == 'Si'){echo ' selected="selected" ' ;}else{} ?>>Si</option>
                        							  <option value="No" <?php if($resEdit->convive_afiliado_conyuge == 'No'){echo ' selected="selected" ' ;}else{} ?>>No</option>
                        							  </select>
                                                      <div id="mensaje_convive_afiliado_conyuge" class="errores"></div>
                                </div>
            					</div>  
                                
								
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_conyuge" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_conyuge" name="numero_telefonico_conyuge" value="<?php echo $resEdit->numero_telefonico_conyuge; ?>" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_conyuge" class="errores"></div>
                                </div>
            					</div>  
			
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="actividad_economica_conyuge" class="control-label">Actividad Económica:</label>
                                                      <select name="actividad_economica_conyuge" id="actividad_economica_conyuge"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Ama de Casa" 					<?php if($resEdit->actividad_economica_conyuge == 'Ama de Casa'){echo ' selected="selected" ' ;}else{} ?>>Ama de Casa</option>
                        							  <option value="Empleado Público" 				<?php if($resEdit->actividad_economica_conyuge == 'Empleado Público'){echo ' selected="selected" ' ;}else{} ?>>Empleado Público</option>
                        							  <option value="Libre Ejercicio Profesional"   <?php if($resEdit->actividad_economica_conyuge == 'Libre Ejercicio Profesional'){echo ' selected="selected" ' ;}else{} ?>>Libre Ejercicio Profesional</option>
                        							  <option value="Independiente" 			    <?php if($resEdit->actividad_economica_conyuge == 'Independiente'){echo ' selected="selected" ' ;}else{} ?>>Independiente</option>
                        							  <option value="Jubilado" 						<?php if($resEdit->actividad_economica_conyuge == 'Jubilado'){echo ' selected="selected" ' ;}else{} ?>>Jubilado</option>
                        							  </select>
                                                      <div id="mensaje_actividad_economica_conyuge" class="errores"></div>
                                </div>
            					</div>  
			
			</div>
			 
			 
			 
  			</div>
  			</div>
  			
  			
  			
  			  
			  			<div class="col-lg-12 col-md-12 col-xs-12 " style="text-align: center; margin-top: 10px">
				  		 <button type="submit" id="generar" name="generar" value=""   class="btn btn-success" style="margin-top: 10px;"><i class="glyphicon glyphicon-edit"></i> Actualizar Solicitud</button>         
					    </div>
  			
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
           <?php } } else {?>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos del Préstamo</h4>
	         </div>
	         <div class="panel-body">
			 
			  <div class="row">
			  
			  					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_sucursales" class="control-label">Sucursal a Tramitar:</label>
                                                       <select name="id_sucursales" id="id_sucursales"  class="form-control">
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSucursales as $res) {?>
                        										<option value="<?php echo $res->id_sucursales; ?>" ><?php echo $res->nombre_sucursales; ?> </option>
                        							        
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sucursales" class="errores"></div>
                                </div>
                                </div>
			  
			  					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_tipo_creditos" class="control-label">Tipo Crédito:</label>
                                                       <select name="id_tipo_creditos" id="id_tipo_creditos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultTipoCredito as $res) {?>
                        										<option value="<?php echo $res->id_tipo_creditos; ?>" ><?php echo $res->nombre_tipo_creditos; ?> </option>
                        							        
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_tipo_creditos" class="errores"></div>
                                </div>
                                </div>
			  
			  
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="monto_datos_prestamo" class="control-label">Monto en Dólares:</label>
                                                      <input type="text" class="form-control cantidades1" id="monto_datos_prestamo" name="monto_datos_prestamo" value='0' 
                                                      data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false" readonly>
                                                      <div id="mensaje_monto_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="plazo_datos_prestamo" class="control-label">Plazo en meses:</label>
                                                      <input type="number" class="form-control" id="plazo_datos_prestamo" name="plazo_datos_prestamo" value="0" placeholder="# meses.." readonly>
                                                      <div id="mensaje_plazo_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="destino_dinero_datos_prestamo" class="control-label">Destino del Dinero:</label>
                                                      <select name="destino_dinero_datos_prestamo" id="destino_dinero_datos_prestamo"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Estudios">Estudios</option>
                        							  <option value="Vivienda">Vivienda</option>
                        							  <option value="Vehículo">Vehículo</option>
                        							  <option value="Consumo">Consumo</option>
                        							  <option value="Otro">Otro</option>
                        							  </select> 
                                                      <div id="mensaje_destino_dinero_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tipo_participe_datos_prestamo" class="control-label">Tipo Participe:</label>
                                                      <input type="hidden" class="form-control" id="id_solicitud_prestamo" name="id_solicitud_prestamo" value="0">
                                                      <select name="tipo_participe_datos_prestamo" id="tipo_participe_datos_prestamo"  class="form-control" >
                                                      <?php foreach($resultTipoParticipe as $val=>$desc) {?>
					                                         <option value="<?php echo $val ?>" <?php //if ($resRol->id_rol == $resEdit->id_rol )  echo  ' selected="selected" '  ;  ?> ><?php echo $desc ?> </option>
					                                  <?php } ?>
                        							  </select> 
                                                      <div id="mensaje_tipo_participe_datos_prestamo" class="errores"></div>
                                </div>
                                </div>
                    			
                    		
         	  </div>
         	  
         	  
			  
  			</div>
  			</div>
  			
           
             <div id="div_tipo_participe_datos_prestamo" style="display: none;">
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Deudor a Garantizar</h4>
	         </div>
	         <div class="panel-body">
			 <div class="row">
			  
         	                  
			  				   
			  				    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="cedula_deudor_a_garantizar" class="control-label">Cédula deudor:</label>
                                                      <input type="number" class="form-control" id="cedula_deudor_a_garantizar" name="cedula_deudor_a_garantizar" value="" placeholder="# cédula deudor..">
                                                      <div id="mensaje_cedula_deudor_a_garantizar" class="errores"></div>
                                </div>
                                </div>
			  				    
                                <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="nombre_deudor_a_garantizar" class="control-label">Apellidos y Nombres Deudor:</label>
                                                      <input type="text" class="form-control" id="nombre_deudor_a_garantizar" name="nombre_deudor_a_garantizar" value="" placeholder="nombre deudor.." readonly>
                                                      <div id="mensaje_nombre_deudor_a_garantizar" class="errores"></div>
                                </div>
                                </div>
                     		  
            </div>
			 
  			</div>
  			</div>
            </div>
           
           
           
           
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Cuenta Bancaria</h4>
	         </div>
	         <div class="panel-body">
			 
			  <div class="row">
			  
			   					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="tipo_pago_cuenta_bancaria" class="control-label">Tipo Transacción:</label>
                                                      <select name="tipo_pago_cuenta_bancaria" id="tipo_pago_cuenta_bancaria"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Depósito">Depósito</option>
                        							  <option value="Retira Cheque">Retira Cheque</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_pago_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
			  
			  
			                    <div id="div_tipo_pago_cuenta_bancaria" style="display: none;">
			  					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_banco_cuenta_bancaria" class="control-label">Banco:</label>
                                                       <select name="id_banco_cuenta_bancaria" id="id_banco_cuenta_bancaria"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultBancos as $res) {?>
                        										<option value="<?php echo $res->nombre_bancos; ?>" ><?php echo $res->nombre_bancos; ?> </option>
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_banco_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
			        		    
			        		    
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="tipo_cuenta_cuenta_bancaria" class="control-label">Tipo Cuenta:</label>
                                                      <select name="tipo_cuenta_cuenta_bancaria" id="tipo_cuenta_cuenta_bancaria"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Ahorros">Ahorros</option>
                        							  <option value="Corriente">Corriente</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_cuenta_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_cuenta_cuenta_bancaria" class="control-label">Número Cuenta:</label>
                                                      <input type="number" class="form-control" id="numero_cuenta_cuenta_bancaria" name="numero_cuenta_cuenta_bancaria" value="" placeholder="# cuenta..">
                                                      <div id="mensaje_numero_cuenta_cuenta_bancaria" class="errores"></div>
                                </div>
                                </div>
                     		   </div>
         	  </div>
			 
  			</div>
  			</div>
           
        
              <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-user'></i> Datos Personales</h4>
	         </div>
	         <div class="panel-body">
			 
			  <div class="row">
			  
			   					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_cedula_datos_personales" class="control-label">Número Cédula:</label>
                                                     <input type="number" class="form-control" id="numero_cedula_datos_personales" name="numero_cedula_datos_personales" value="" placeholder="cédula..">
                                                      <div id="mensaje_numero_cedula_datos_personales" class="errores"></div>
                                </div>
                                </div>
			  
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_solicitante_datos_personales" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_solicitante_datos_personales" name="apellidos_solicitante_datos_personales" value="" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_solicitante_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_solicitante_datos_personales" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_solicitante_datos_personales" name="nombres_solicitante_datos_personales" value="" placeholder="nombres..">
                                                      <div id="mensaje_nombres_solicitante_datos_personales" class="errores"></div>
                                </div>
                                </div>
                    		    
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="correo_solicitante_datos_personales" class="control-label">Correo Electrónico:</label>
                                                      <input type="email" class="form-control" id="correo_solicitante_datos_personales" name="correo_solicitante_datos_personales" value="" placeholder="correo electrónico..">
                                                      <div id="mensaje_correo_solicitante_datos_personales" class="errores"></div>
                                </div>
             					</div>	
              </div>
              
              <div class="row">
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_sexo_datos_personales" class="control-label">Género:</label>
                                                       <select name="id_sexo_datos_personales" id="id_sexo_datos_personales"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSexo as $res) {?>
                        										<option value="<?php echo $res->id_sexo; ?>" ><?php echo $res->nombre_sexo; ?> </option>
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sexo_datos_personales" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="fecha_nacimiento_datos_personales" class="control-label">Fecha Nacimiento:</label>
                                                      <input type="date" class="form-control" id="fecha_nacimiento_datos_personales" name="fecha_nacimiento_datos_personales"  value="">
                                                      <div id="mensaje_fecha_nacimiento_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="id_estado_civil_datos_personales" class="control-label">Estado Civil:</label>
                                                      <select name="id_estado_civil_datos_personales" id="id_estado_civil_datos_personales"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultEstado_civil as $res) {?>
                        										<option value="<?php echo $res->id_estado_civil; ?>" ><?php echo $res->nombre_estado_civil; ?> </option>
                        							        <?php } ?>
                        							  </select> 
                                                      <div id="mensaje_id_estado_civil_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="separacion_bienes_datos_personales" class="control-label">Separación de Bienes:</label>
                                                      <select name="separacion_bienes_datos_personales" id="separacion_bienes_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si">Si</option>
                        							  <option value="No">No</option>
                        							  </select> 
			                                          <div id="mensaje_separacion_bienes_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="cargas_familiares_datos_personales" class="control-label">Cargas Familiares:</label>
                                                      <select name="cargas_familiares_datos_personales" id="cargas_familiares_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si">Si</option>
                        							  <option value="No">No</option>
                        							  </select> 
			                                          <div id="mensaje_cargas_familiares_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_hijos_datos_personales" class="control-label">Número Hijos:</label>
                                                      <select name="numero_hijos_datos_personales" id="numero_hijos_datos_personales"  class="form-control" >
                                                      <option value="" selected="selected">--Seleccione--</option>
                        							  <option value="0">0</option>
                        							  <option value="1">1</option>
                        							  <option value="2">2</option>
                        							  <option value="3">3</option>
                        							  <option value="4">4</option>
                        							  <option value="5">5</option>
                        							  <option value="6">6</option>
                        							  <option value="7">7</option>
                        							  <option value="8">8</option>
                        							  <option value="9">9</option>
                        							  <option value="10">10</option>
                        							  </select> 
			                                          <div id="mensaje_numero_hijos_datos_personales" class="errores"></div>
                                </div>
                                </div>
                                
              </div>
              
              
              
              
              <div class="row">
               					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="nivel_educativo_datos_personales" class="control-label">Nivel Educativo:</label>
                                                      <select name="nivel_educativo_datos_personales" id="nivel_educativo_datos_personales"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Primario">Primario</option>
                        							  <option value="Secundario">Secundario</option>
                        							  <option value="Técnico">Técnico</option>
                        							  <option value="Universitario">Universitario</option>
                        							  <option value="Postgrado">Postgrado</option>
                        							  </select> 
                                                      <div id="mensaje_nivel_educativo_datos_personales" class="errores"></div>
                                </div>
             					</div>	
             					
             					
              </div>
			 
  			</div>
  			</div>
             
              
              
              
                       
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Datos Exactos Domicilio</h4>
	         </div>
	         <div class="panel-body">
			 <div class="row">
                    		     
             					<div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_provincias_vivienda" class="control-label">Provincia:</label>
                                                          <select name="id_provincias_vivienda" id="id_provincias_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProvincias as $res) {?>
                        										<option value="<?php echo $res->id_provincias; ?>"><?php echo $res->nombre_provincias; ?> </option>
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_provincias_vivienda" class="errores"></div>
                                </div>
                    		    </div>       		    
                    		   
                    		    
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_cantones_vivienda" class="control-label">Cantón:</label>
                                                          <select name="id_cantones_vivienda" id="id_cantones_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                                                            <?php foreach($resultCantones as $res) {?>
                        										<option value="<?php echo $res->id_cantones; ?>"  ><?php echo $res->nombre_cantones; ?> </option>
                        							        <?php } ?>
                                                          </select> 
                                                          <div id="mensaje_id_cantones_vivienda" class="errores"></div>
                                </div>
                    		    </div>
                    		   
                    			
                    			<div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                          <label for="id_parroquias_vivienda" class="control-label">Parroquia:</label>
                                                          <select name="id_parroquias_vivienda" id="id_parroquias_vivienda"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  
                        								  <?php foreach($resultParroquias as $res) {?>
                        										<option value="<?php echo $res->id_parroquias; ?>" ><?php echo $res->nombre_parroquias; ?> </option>
                        							        <?php } ?>
                        							      
                        								  </select> 
                                                          <div id="mensaje_id_parroquias_vivienda" class="errores"></div>
                                </div>
                    		    </div>
                    			
            </div>
            
            
             <div class="row">
                    		    <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="barrio_sector_vivienda" class="control-label">Barrio y/o sector:</label>
                                                      <input type="text" class="form-control" id="barrio_sector_vivienda" name="barrio_sector_vivienda" value="" placeholder="nombre barrio..">
                                                      <div id="mensaje_barrio_sector_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-6 col-xs-12 col-md-6">
                    		    <div class="form-group">
                                                      <label for="ciudadela_conjunto_etapa_manzana_vivienda" class="control-label">Ciudadela y/o conjunto / Etapa / Manzana:</label>
                                                     <input type="text" class="form-control" id="ciudadela_conjunto_etapa_manzana_vivienda" name="ciudadela_conjunto_etapa_manzana_vivienda" value="" placeholder="nombre conjunto..">
                                                      <div id="mensaje_ciudadela_conjunto_etapa_manzana_vivienda" class="errores"></div>
                                </div>
                                </div>
              </div>
            
            
             <div class="row">
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="calle_vivienda" class="control-label">Calle:</label>
                                                      <input type="text" class="form-control" id="calle_vivienda" name="calle_vivienda" value="" placeholder="nombre calle..">
                                                      <div id="mensaje_calle_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="numero_calle_vivienda" class="control-label">Número Calle:</label>
                                                     <input type="text" class="form-control" id="numero_calle_vivienda" name="numero_calle_vivienda" value="" placeholder="# número calle..">
                                                      <div id="mensaje_numero_calle_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="intersecion_vivienda" class="control-label">Intersección:</label>
                                                     <input type="text" class="form-control" id="intersecion_vivienda" name="intersecion_vivienda" value="" placeholder="intersección..">
                                                      <div id="mensaje_intersecion_vivienda" class="errores"></div>
                                </div>
                                </div>
              </div>
              
             <div class="row">
                    		    <div class="col-lg-12 col-xs-12 col-md-12">
                    		    <div class="form-group">
                                                      <label for="referencia_direccion_domicilio_vivienda" class="control-label">Referencia de la dirección del domicilio:</label>
                                                      <input type="text" class="form-control" id="referencia_direccion_domicilio_vivienda" name="referencia_direccion_domicilio_vivienda" value="" placeholder="referencia..">
                                                      <div id="mensaje_referencia_direccion_domicilio_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		 
              </div>
              
              <div class="row">
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tipo_vivienda" class="control-label">Tipo Vivienda:</label>
                                                      <select name="tipo_vivienda" id="tipo_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Propia">Propia</option>
                        							  <option value="Arrendada">Arrendada</option>
                        							  <option value="Anticresis">Anticresis</option>
                        							  <option value="Vive con Familiares">Vive con Familiares</option>
                        							  <option value="Otra">Otra</option>
                        							  </select> 
                                                      <div id="mensaje_tipo_vivienda" class="errores"></div>
                                </div>
                                </div>
                    		    
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="vivienda_hipotecada_vivienda" class="control-label">Vivienda Hipotecada:</label>
                                                      <select name="vivienda_hipotecada_vivienda" id="vivienda_hipotecada_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si">Si</option>
                        							  <option value="No">No</option>
                        							  </select> 
                                                      <div id="mensaje_vivienda_hipotecada_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="tiempo_residencia_vivienda" class="control-label">Tiempo Residencia:</label>
                                                      <select name="tiempo_residencia_vivienda" id="tiempo_residencia_vivienda"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="1 año">1 año</option>
                        							  <option value="2 años">2 años</option>
                        							  <option value="3 años">3 años</option>
                        							  <option value="4 años">4 años</option>
                        							  <option value="5 años">5 años</option>
                        							  <option value="6 años">6 años</option>
                        							  <option value="7 años">7 años</option>
                        							  <option value="8 años">8 años</option>
                        							  <option value="9 años">9 años</option>
                        							  <option value="10 años">10 años</option>
                        							  <option value="11 años">11 años</option>
                        							  <option value="12 años">12 años</option>
                        							  <option value="13 años">13 años</option>
                        							  <option value="14 años">14 años</option>
                        							  <option value="15 años">15 años</option>
                        							  <option value="16 años">16 años</option>
                        							  <option value="17 años">17 años</option>
                        							  <option value="18 años">18 años</option>
                        							  <option value="19 años">19 años</option>
                        							  <option value="20 años">20 años</option>
                        							  <option value="21 años">21 años</option>
                        							  <option value="22 años">22 años</option>
                        							  <option value="23 años">23 años</option>
                        							  <option value="24 años">24 años</option>
                        							  <option value="25 años">25 años</option>
                        							  <option value="26 años">26 años</option>
                        							  <option value="27 años">27 años</option>
                        							  <option value="28 años">28 años</option>
                        							  <option value="29 años">29 años</option>
                        							  <option value="30 años">30 años</option>
                        							  </select> 
                                                      <div id="mensaje_tiempo_residencia_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div id="div_tipo_vivienda" style="display: none;">
                                <div class="col-lg-4 col-xs-12 col-md-4">
                    		    <div class="form-group">
                                                      <label for="nombre_propietario_vivienda" class="control-label">Nombre Propietario Vivienda:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombre_propietario_vivienda" name="nombre_propietario_vivienda" value="" placeholder="nombre propietario..">
                                                      <div id="mensaje_nombre_propietario_vivienda" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="celular_propietario_vivienda" class="control-label">Celular Propietario Vivienda:</label>
                                                      <input type="number" class="form-control" id="celular_propietario_vivienda" name="celular_propietario_vivienda" value="" placeholder="# celular propietario..">
                                                      <div id="mensaje_celular_propietario_vivienda" class="errores"></div>
                                </div>
                                </div>
                                </div>
                                
              </div>
              
              
          
              
          
             
			</div>
  			</div>
           
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Números Telefónicos</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_casa_solicitante" class="control-label">Número Casa:</label>
                                                      <input type="number" class="form-control" id="numero_casa_solicitante" name="numero_casa_solicitante" value="" placeholder="# casa..">
                                                      <div id="mensaje_numero_casa_solicitante" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_celular_solicitante" class="control-label">Número Celular:</label>
                                                      <input type="number" class="form-control" id="numero_celular_solicitante" name="numero_celular_solicitante" value="" placeholder="# celular..">
                                                      <div id="mensaje_numero_celular_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="numero_trabajo_solicitante" class="control-label">Número Trabajo:</label>
                                                      <input type="number" class="form-control" id="numero_trabajo_solicitante" name="numero_trabajo_solicitante" value="" placeholder="# trabajo..">
                                                      <div id="mensaje_numero_trabajo_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="extension_solicitante" class="control-label">Extensión:</label>
                                                      <input type="number" class="form-control" id="extension_solicitante" name="extension_solicitante" value="" placeholder="# extensión..">
                                                      <div id="mensaje_extension_solicitante" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                      <label for="mode_solicitante" class="control-label">Mode:</label>
                                                      <input type="number" class="form-control" id="mode_solicitante" name="mode_solicitante" value="" placeholder="mode..">
                                                      <div id="mensaje_mode_solicitante" class="errores"></div>
                                </div>
            					</div>       		    
             	
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
           
            <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Referencia Personal</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_referencia_personal" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_referencia_personal" name="apellidos_referencia_personal" value="" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_referencia_personal" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_referencia_personal" name="nombres_referencia_personal" value="" placeholder="nombres..">
                                                      <div id="mensaje_nombres_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="relacion_referencia_personal" class="control-label">Relación:</label>
                                                      <select name="relacion_referencia_personal" id="relacion_referencia_personal"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Amigo (a)">Amigo (a)</option>
                        							  <option value="Compadre">Compadre</option>
                        							  <option value="Comadre">Comadre</option>
                        							  <option value="Compañero Laboral (a)">Compañero Laboral (a)</option>
                        							  <option value="Jéfe (a)">Jéfe (a)</option>
                        							  </select>
                                                      <div id="mensaje_relacion_referencia_personal" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_referencia_personal" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_referencia_personal" name="numero_telefonico_referencia_personal" value="" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_referencia_personal" class="errores"></div>
                                </div>
            </div>  
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Referencia Familiar (Que no viva con usted)</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
             					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_referencia_familiar" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_referencia_familiar" name="apellidos_referencia_familiar" value="" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_referencia_familiar" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_referencia_familiar" name="nombres_referencia_familiar" value="" placeholder="nombres..">
                                                      <div id="mensaje_nombres_referencia_familiar" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="parentesco_referencia_familiar" class="control-label">Parentesco:</label>
                                                      <select name="parentesco_referencia_familiar" id="parentesco_referencia_familiar"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Primo (a)">Primo (a)</option>
                        							  <option value="Tío (a)">Tío (a)</option>
                        							  <option value="Hermano (a)">Hermano (a)</option>
                        							  <option value="Sobrino (a)">Sobrino (a)</option>
                        							  <option value="Abuelo (a)">Abuelo (a)</option>
                        							  <option value="Hijo (a)">Hijo (a)</option>
                        							  </select>
                                                      <div id="mensaje_parentesco_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_referencia_familiar" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_referencia_familiar" name="numero_telefonico_referencia_familiar" value="" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_referencia_familiar" class="errores"></div>
                                </div>
            					</div>  
            	
             
            </div>
			
			 
  			</div>
  			</div>
           
           
           
           
           
           
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Datos Laborales</h4>
	         </div>
	         <div class="panel-body">
			 
			 
			 <div class="row">
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_entidades" class="control-label">Institución o Empresa:</label>
                                                      <select name="id_entidades" id="id_entidades"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultEntidades as $res) {?>
                        										<option value="<?php echo $res->id_entidades; ?>"  ><?php echo $res->nombre_entidades; ?> </option>
                        							        <?php } ?>
                        						      </select> 
                                                      <div id="mensaje_id_entidades" class="errores"></div>
                                </div>
                                </div>
                    		    
             					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_provincias_asignacion" class="control-label">Provincia:</label>
                                                          <select name="id_provincias_asignacion" id="id_provincias_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProvincias as $res) {?>
                        										<option value="<?php echo $res->id_provincias; ?>" ><?php echo $res->nombre_provincias; ?> </option>
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_provincias_asignacion" class="errores"></div>
                                </div>
                    		    </div>       		    
                    		   
                    		    
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_cantones_asignacion" class="control-label">Cantón:</label>
                                                          <select name="id_cantones_asignacion" id="id_cantones_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        							      
                        							      <?php foreach($resultCantones as $res) {?>
                        										<option value="<?php echo $res->id_cantones; ?>"  ><?php echo $res->nombre_cantones; ?> </option>
                        							        <?php } ?>
                        							     
                        							      </select> 
                                                          <div id="mensaje_id_cantones_asignacion" class="errores"></div>
                                </div>
                    		    </div>
                    		   
                    			
                    			<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                          <label for="id_parroquias_asignacion" class="control-label">Parroquia:</label>
                                                          <select name="id_parroquias_asignacion" id="id_parroquias_asignacion"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  
                        								  <?php foreach($resultParroquias as $res) {?>
                        										<option value="<?php echo $res->id_parroquias; ?>" ><?php echo $res->nombre_parroquias; ?> </option>
                        							        <?php } ?>
                        							      
                        								  </select> 
                                                          <div id="mensaje_id_parroquias_asignacion" class="errores"></div>
                                </div>
                    		    </div>
                        		
            </div>
			 
			<div class="row"> 
			 	
              					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_datos_laborales" class="control-label">Número:</label>
                                                      <input type="text" class="form-control" id="numero_telefonico_datos_laborales" name="numero_telefonico_datos_laborales" value="" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_datos_laborales" class="errores"></div>
                                </div>
            					</div>  
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="interseccion_datos_laborales" class="control-label">Intersección:</label>
                                                      <input type="text" class="form-control" id="interseccion_datos_laborales" name="interseccion_datos_laborales" value="" placeholder="intersección..">
                                                      <div id="mensaje_interseccion_datos_laborales" class="errores"></div>
                                </div>
            					</div>   
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="calle_datos_laborales" class="control-label">Calle:</label>
                                                      <input type="text" class="form-control" id="calle_datos_laborales" name="calle_datos_laborales" value="" placeholder="nombre calle..">
                                                      <div id="mensaje_calle_datos_laborales" class="errores"></div>
                                </div>
            					</div>  
            
            
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="cargo_actual_datos_laborales" class="control-label">Cargo Actual:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="cargo_actual_datos_laborales" name="cargo_actual_datos_laborales" value="" placeholder="cargo actual..">
                                                      <div id="mensaje_cargo_actual_datos_laborales" class="errores"></div>
                                </div>
            					</div>         			
                    		    
                    		    
            </div>      		    
			 
			 
  			</div>
  			</div>
  			
  			
  			
  			
  			
  			 <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-home'></i> Información Económica</h4>
	         </div>
	         <div class="panel-body">
			 
			 <div class="row">
                    		   
             
            <table class="col-lg-12 col-md-12 col-xs-12 tablesorter table table-striped table-bordered dt-responsive nowrap">
            
            <tr>
            <th><b>INGRESOS MENSUALES</b></th>
            <th><b>VALOR EN DÓLARES</b></th>
            <th><b>GASTOS MENSUALES</b></th>
            <th><b>VALOR EN DÓLARES</b></th>
            </tr>
            
            <tr>
            <td>Sueldo Total</td>
            <td><input type="text" class="form-control cantidades1" id="sueldo_total_info_economica" name="sueldo_total_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_sueldo_total_info_economica" class="errores"></div></td>
            <td>Cuota del Préstamo Ordinario CAPREMCI</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_ordinario_info_economica" name="cuota_prestamo_ordinario_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Arriendos</td>
            <td><input type="text" class="form-control cantidades1" id="arriendos_info_economica" name="arriendos_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuota del Préstamo Emergente CAPREMCI</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_emergente_info_economica" name="cuota_prestamo_emergente_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Honorarios Profesionales</td>
            <td><input type="text" class="form-control cantidades1" id="honorarios_profesionales_info_economica" name="honorarios_profesionales_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuotas de Otros Préstamos</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_otros_prestamos_info_economica" name="cuota_otros_prestamos_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Comisiones</td>
            <td><input type="text" class="form-control cantidades1" id="comisiones_info_economica" name="comisiones_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Cuotas de Prestamos con el IESS</td>
            <td><input type="text" class="form-control cantidades1" id="cuota_prestamo_iess_info_economica" name="cuota_prestamo_iess_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td>Horas Suplementarias</td>
            <td><input type="text" class="form-control cantidades1" id="horas_suplementarias_info_economica" name="horas_suplementarias_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            <td>Arriendo</td>
            <td><input type="text" class="form-control cantidades1" id="arriendos_egre_info_economica" name="arriendos_egre_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td colspan="2" style="text-align: center;"><b>OTROS INGRESOS (detalle)</b></td>
            <td>Alimentación</td>
            <td><input type="text" class="form-control cantidades1" id="alimentacion_info_economica" name="alimentacion_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_1_info_economica" name="otros_ingresos_1_info_economica" value="" placeholder="nombre ingresos 1.."><div id="mensaje_otros_ingresos_1_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_1_info_economica" name="valor_ingresos_1_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_1_info_economica" class="errores"></div></td>
            <td>Estudios</td>
            <td><input type="text" class="form-control cantidades1" id="estudios_info_economica" name="estudios_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_2_info_economica" name="otros_ingresos_2_info_economica" value="" placeholder="nombre ingresos 2.."><div id="mensaje_otros_ingresos_2_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_2_info_economica" name="valor_ingresos_2_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_2_info_economica" class="errores"></div></td>
            <td>Pago Servicios Básicos</td>
            <td><input type="text" class="form-control cantidades1" id="pago_servicios_basicos_info_economica" name="pago_servicios_basicos_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_3_info_economica" name="otros_ingresos_3_info_economica" value="" placeholder="nombre ingresos 3.."><div id="mensaje_otros_ingresos_3_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_3_info_economica" name="valor_ingresos_3_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_3_info_economica" class="errores"></div></td>
            <td>Pago Tarjetas de Crédito</td>
            <td><input type="text" class="form-control cantidades1" id="pago_tarjetas_credito_info_economica" name="pago_tarjetas_credito_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_4_info_economica" name="otros_ingresos_4_info_economica" value="" placeholder="nombre ingresos 4.."><div id="mensaje_otros_ingresos_4_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_4_info_economica" name="valor_ingresos_4_info_economica" value='0.00' onchange="sumar_ingresos(this.value);" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_4_info_economica" class="errores"></div></td>
            <td>Afiliación a Cooperativas</td>
            <td><input type="text" class="form-control cantidades1" id="afiliacion_cooperativas_info_economica" name="afiliacion_cooperativas_info_economica" value='0.00' onchange="sumar_egresos(this.value);" data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_5_info_economica" name="otros_ingresos_5_info_economica" value="" placeholder="nombre ingresos 5.."><div id="mensaje_otros_ingresos_5_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_5_info_economica" name="valor_ingresos_5_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_5_info_economica" class="errores"></div></td>
            <td>Ahorro</td>
            <td><input type="text" class="form-control cantidades1" id="ahorro_info_economica" name="ahorro_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_6_info_economica" name="otros_ingresos_6_info_economica" value="" placeholder="nombre ingresos 6.."><div id="mensaje_otros_ingresos_6_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_6_info_economica" name="valor_ingresos_6_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_6_info_economica" class="errores"></div></td>
            <td>Impuesto a la Renta</td>
            <td><input type="text" class="form-control cantidades1" id="impuesto_renta_info_economica" name="impuesto_renta_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_7_info_economica" name="otros_ingresos_7_info_economica" value="" placeholder="nombre ingresos 7.."><div id="mensaje_otros_ingresos_7_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_7_info_economica" name="valor_ingresos_7_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_7_info_economica" class="errores"></div></td>
            <td colspan="2" style="text-align: center;"><b>OTROS GASTOS (detalle)</b></td>
            </tr>
            
            <tr>
            <td><input type="text" class="form-control" id="otros_ingresos_8_info_economica" name="otros_ingresos_8_info_economica" value="" placeholder="nombre ingresos 8.."><div id="mensaje_otros_ingresos_8_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_ingresos_8_info_economica" name="valor_ingresos_8_info_economica" onchange="sumar_ingresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_ingresos_8_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control" id="otros_egresos_1_info_economica" name="otros_egresos_1_info_economica" value="" placeholder="nombre egresos 1.."><div id="mensaje_otros_egresos_1_info_economica" class="errores"></div></td>
            <td><input type="text" class="form-control cantidades1" id="valor_egresos_1_info_economica" name="valor_egresos_1_info_economica" onchange="sumar_egresos(this.value);" value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_valor_egresos_1_info_economica" class="errores"></div></td>
            </tr>
            
           
            
            
            </table>
             
            </div>
			 
  			</div>
  			</div>
           
           
           
           
            <div class="panel panel-info">
	        <div class="panel-heading">
	        <h4><i class='glyphicon glyphicon-home'></i> Datos del Cónyuge o Pareja</h4>
	        </div>
	        <div class="panel-body">
			 
			<div class="row">
               					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_cedula_conyuge" class="control-label">Número Cédula:</label>
                                                      <input type="text" class="form-control" id="numero_cedula_conyuge" name="numero_cedula_conyuge" value="" placeholder="número cédula..">
                                                      <div id="mensaje_numero_cedula_conyuge" class="errores"></div>
                                </div>
            					</div>  
            					
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="apellidos_conyuge" class="control-label">Apellidos Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="apellidos_conyuge" name="apellidos_conyuge" value="" placeholder="apellidos..">
                                                      <div id="mensaje_apellidos_conyuge" class="errores"></div>
                                </div>
            					</div>   
                    		   
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="nombres_conyuge" class="control-label">Nombres Completos:</label>
                                                      <input style="text-transform: uppercase;" type="text" class="form-control" id="nombres_conyuge" name="nombres_conyuge" value="" placeholder="nombres..">
                                                      <div id="mensaje_nombres_conyuge" class="errores"></div>
                                </div>
            					</div>  
            
           						
            					<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="id_sexo_conyuge" class="control-label">Género:</label>
                                                       <select name="id_sexo_conyuge" id="id_sexo_conyuge"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultSexo as $res) {?>
                        										<option value="<?php echo $res->id_sexo; ?>" ><?php echo $res->nombre_sexo; ?> </option>
                        							        <?php } ?>
                        							   </select> 
                                                      <div id="mensaje_id_sexo_conyuge" class="errores"></div>
                                </div>
                                </div>
             
            </div>   
			 
			 
			<div class="row">
							
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="fecha_nacimiento_conyuge" class="control-label">Fecha Nacimiento:</label>
                                                      <input type="date" class="form-control" id="fecha_nacimiento_conyuge" name="fecha_nacimiento_conyuge" min="1800-01-01" max="<?php echo date('Y-m-d');?>" value="">
                                                      <div id="mensaje_fecha_nacimiento_conyuge" class="errores"></div>
                                </div>
                                </div>
                                
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="convive_afiliado_conyuge" class="control-label">Convive con el Afiliado:</label>
                                                      <select name="convive_afiliado_conyuge" id="convive_afiliado_conyuge"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Si">Si</option>
                        							  <option value="No">No</option>
                        							  </select>
                                                      <div id="mensaje_convive_afiliado_conyuge" class="errores"></div>
                                </div>
            					</div>  
                                
								
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="numero_telefonico_conyuge" class="control-label">Número Telefónico:</label>
                                                      <input type="number" class="form-control" id="numero_telefonico_conyuge" name="numero_telefonico_conyuge" value="" placeholder="# telefónico..">
                                                      <div id="mensaje_numero_telefonico_conyuge" class="errores"></div>
                                </div>
            					</div>  
			
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="actividad_economica_conyuge" class="control-label">Actividad Económica:</label>
                                                      <select name="actividad_economica_conyuge" id="actividad_economica_conyuge"  class="form-control" >
                                                      <option value="0" selected="selected">--Seleccione--</option>
                        							  <option value="Ama de Casa">Ama de Casa</option>
                        							  <option value="Empleado Público">Empleado Público</option>
                        							  <option value="Libre Ejercicio Profesional">Libre Ejercicio Profesional</option>
                        							  <option value="Independiente">Independiente</option>
                        							  <option value="Jubilado">Jubilado</option>
                        							  </select>
                                                      <div id="mensaje_actividad_economica_conyuge" class="errores"></div>
                                </div>
            					</div>  
			
			</div>
			 
			 
			 
  			</div>
  			</div>
  			
  			
  			
  			  
			  			<div class="col-lg-12 col-md-12 col-xs-12 " style="text-align: center; margin-top: 10px">
				  		 <button type="submit" id="generar" name="generar" value=""   class="btn btn-success" style="margin-top: 10px;"><i class="glyphicon glyphicon-edit"></i> Registrar Solicitud</button>         
					    </div>
  			
  			
           <?php } ?>
      
      
       </form>
           
      
      
      
      
			      </div>
			    </div>

	</div>
	</div>
	</div>
	</div>
	</div>
	    
    
    
  
 
     <!-- Bootstrap -->
    <script src="view/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    
    
    
    <!-- NProgress -->
    <script src="view/vendors/nprogress/nprogress.js"></script>
   
   
    <!-- Datatables -->
    <script src="view/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    
    
    <script src="view/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="view/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    
    
    
    <!-- Custom Theme Scripts -->
    <script src="view/build/js/custom.min.js"></script>
	<script src="view/js/jquery.inputmask.bundle.js"></script>
	<!-- codigo de las funciones -->

	
  </body>
</html>   