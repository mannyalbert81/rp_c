 $(document).ready( function (){
	 /*pone_espera();*/
	   load_usuarios(1);
	   load_usuarios_inactivos(1);
	   var ct="Usuarios Activos";
	   
	   $(".cantidades1").inputmask();
	   
	   $('.nav-tabs a').on('shown.bs.tab', function(e){
       var currentTab = $(e.target).text();
       ct=currentTab;
       //console.log(currentTab);
       });

	   $('[data-mask]').inputmask();

	   /*para manejo de multiples roles*/
	    /**$("#link_agregar_rol").click(function() {
			return !($('#id_rol option:selected').clone()).appendTo('#lista_roles'); 
	    });*/
	    $('#link_agregar_rol').click(function() { 
	        copiarOpcion($('#id_rol option:selected').clone(), "#lista_roles");
	    });
	    
	    var cedula_usuarios = $("#cedula_usuarios").val();

      if(cedula_usuarios>0){

       }else{
 		
		$( "#cedula_usuarios" ).autocomplete({

			source: 'index.php?controller=Usuarios&action=AutocompleteCedula',
				minLength: 4
		});
       }

		$("#cedula_usuarios").focusout(function(){
			validarcedula();
			$.ajax({
				url:'index.php?controller=Usuarios&action=AutocompleteDevuelveNombres',
				type:'POST',
				dataType:'json',
				data:{cedula_usuarios:$('#cedula_usuarios').val()}
			}).done(function(respuesta){

				$('#id_usuarios').val(respuesta.id_usuarios);					
				$('#nombre_usuarios').val(respuesta.nombre_usuarios);
				$('#apellidos_usuarios').val(respuesta.apellidos_usuarios);
				$('#usuario_usuarios').val(respuesta.usuario_usuarios);
				$('#fecha_nacimiento_usuarios').val(respuesta.fecha_nacimiento_usuarios);
				$('#celular_usuarios').val(respuesta.celular_usuarios);
				$('#telefono_usuarios').val(respuesta.telefono_usuarios);
				$('#correo_usuarios').val(respuesta.correo_usuarios);					
				$('#codigo_clave').val(respuesta.clave_n_claves);

				if(respuesta.id_rol>0){
					$('#id_rol_principal option[value='+respuesta.id_rol+']').attr('selected','selected');
					}

				if(respuesta.estado_usuarios>0){
					$('#id_estado option[value='+respuesta.estado_usuarios+']').attr('selected','selected');
					}

				if(respuesta.caduca_claves=='t'){
					
					$('#caduca_clave').attr('checked','checked');
				}

				if( typeof respuesta.clave_n_usuarios !== "undefined"){
					$('#clave_usuarios').val(respuesta.clave_n_claves).attr('readonly','readonly');
					$('#clave_usuarios_r').val(respuesta.clave_n_claves).attr('readonly','readonly');
					$('#lbl_cambiar_clave').text("Cambiar Clave:  ");
					$('#cambiar_clave').show();
						
						
					}
				if(respuesta.privilegios.length>0){
              	 $('#lista_roles').empty();
              	 $.each(respuesta.privilegios, function(k, v) {
              		 $('#lista_roles').append("<option value= " +v.id_rol +" >" + v.nombre_rol  + "</option>");
           		   
              	});
				}
			}).fail(function(respuesta) {

				$('#id_usuarios').val("");
				$('#nombre_usuarios').val("");
				$('#apellidos_usuarios').val("");
				$('#usuario_usuarios').val("");
				$('#fecha_nacimiento_usuarios').val("");
				$('#celular_usuarios').val("");
				$('#telefono_usuarios').val("");
				$('#correo_usuarios').val("");
				$('#clave_usuarios').val("");
				$('#clave_usuarios_r').val("");
				    			    
			  });

		});  
    
		 $( "#cedula_usuarios" ).focus(function() {
			  $("#mensaje_cedula_usuarios").fadeOut("slow");
		    });
			
			$( "#nombre_usuarios" ).focus(function() {
				$("#mensaje_nombre_usuarios").fadeOut("slow");
			});

			$( "#apellidos_usuarios" ).focus(function() {
				$("#mensaje_apellido_usuarios").fadeOut("slow");
			});

			$( "#fecha_nacimiento_usuarios" ).focus(function() {
				$("#mensaje_fecha_nacimiento_usuarios").fadeOut("slow");
			});
			
			$( "#clave_usuarios" ).focus(function() {
				$("#mensaje_clave_usuarios").fadeOut("slow");
			});
			$( "#clave_usuarios_r" ).focus(function() {
				$("#mensaje_clave_usuarios_r").fadeOut("slow");
			});
			
			$( "#celular_usuarios" ).focus(function() {
				$("#mensaje_celular_usuarios").fadeOut("slow");
			});
			
			$( "#correo_usuarios" ).focus(function() {
				$("#mensaje_correo_usuarios").fadeOut("slow");
			});
			
			$("#id_rol_principal").focus(function() {
				$("#mensaje_id_rol_principal").fadeOut("slow"); 
			});

	    
	    
	    $("#Cancelar").click(function() 
				{
				 $("#cedula_usuarios").val("");
			     $("#nombre_usuarios").val("");
			     $("#clave_usuarios").val("");
			     $("#clave_usuarios_r").val("");
			     $("#telefono_usuarios").val("");
			     $("#celular_usuarios").val("");
			     $("#correo_usuarios").val("");
			     $("#id_rol").val("");
			     $("#id_estado").val("");
			     $("#fotografia_usuarios").val("");
			     $("#id_usuarios").val("");
			     
			    }); 

	    $('#link_agregar_roles').click(function() { 
	        $('#id_rol option').each(function() {
	            copiarOpcion($(this).clone(), "#lista_roles");
	        }); 
	    });

	    $('#link_eliminar_rol').click(function() { 
	        $('#lista_roles option:selected').remove(); 
	    });

	    $('#link_eliminar_roles').click(function() { 
	        $('#lista_roles option').each(function() {
	            $(this).remove(); 
	        }); 
	    });

	    $('#id_rol_principal').change(function() { 
	    	copiarOpcion($('#id_rol_principal option:selected').clone(), "#lista_roles");
	    });

	   

	    $(".caducaclave").blur(function(){
			var clave = $("#clave_usuarios").val();
			var _id_usuarios = $("#id_usuarios").val();

			if($('#cambiar_clave').is(':checked')){
			$.ajax({
	            beforeSend: function(objeto){
	              $("#resultadosjq").html('...');
	            },
	            url: 'index.php?controller=Usuarios&action=ajax_caducaclave',
	            type: 'POST',
	            data: {clave_usuarios:clave,id_usuarios:_id_usuarios},
	            success: function(x){
	             if(x.trim()!=""){
	            	 	$("#mensaje_clave_usuarios").text(x);
			    		$("#mensaje_clave_usuarios").fadeIn("slow");
    	            	 $("#clave_usuarios").val("");
    	            	 $("#clave_usuarios_r").val("");
	                 }
	            },
	           error: function(jqXHR,estado,error){
	             $("#resultadosjq").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	           }
	         });
			}
	        
	   });

		$('#cambiar_clave').change(
			    function(){				    
			        if (this.checked) {

				           $('#clave_usuarios').removeAttr("readonly");
				           $('#clave_usuarios_r').removeAttr("readonly");
				           $('#clave_usuarios').val("");
				           $('#clave_usuarios_r').val("");
			        }else{
			        	$('#clave_usuarios').attr("readonly","readonly");
				        $('#clave_usuarios_r').attr("readonly","readonly");
				        $('#clave_usuarios').val($('#codigo_clave').val());
				        $('#clave_usuarios_r').val($('#codigo_clave').val());
				        }
			    });
		});//docreadyend
 
 $("#btExportar").click(function()
			{
	
	 get_data_for_xls();
 	
			});
 

 
 

 
 
 function get_data_for_xls()
 {
	 var activeTab = $('.nav-tabs .active').text();
	 var search=$("#search").val();
	 	
	 	
			if (activeTab == "Usuarios Activos")
			{
				var users ="activos";
				var con_datos={
						  search:search,
						  users:users,
						  action:'ajax'
						  };
				$.ajax({
					url:'index.php?controller=Usuarios&action=Exportar_usuariosExcel',
			        type : "POST",
			        async: true,			
					data: con_datos,
					success:function(data){
						
							
						if(data.length>3)
						   {
				  var array = JSON.parse(data);
				  var newArr = [];
				   while(array.length) newArr.push(array.splice(0,7));
				   console.log(newArr);
				   
				   var dt = new Date();
				   var m=dt.getMonth();
				   m+=1;
				   var y=dt.getFullYear();
				   var d=dt.getDate();
				   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
				   var wb =XLSX.utils.book_new();
				   wb.SheetNames.push("Reporte Usuarios Activos");
				   var ws = XLSX.utils.aoa_to_sheet(newArr);
				   wb.Sheets["Reporte Usuarios Activos"] = ws;
				   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
				   function s2ab(s) { 
			            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
			            var view = new Uint8Array(buf);  //create uint8array as viewer
			            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
			            return buf;    
				   }
			       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'ReporteUsuariosActivos'+fecha+'.xlsx');
					   }
				   else{
					   alert("No hay información para descargar");
				   }
					}
				});
				}
			else
			{
				var users ="inactivos";
				var con_datos={
						  search:search,
						  users:users,
						  action:'ajax'
						  };
				$.ajax({
					url:'index.php?controller=Usuarios&action=Exportar_usuariosExcel',
			        type : "POST",
			        async: true,			
					data: con_datos,
					success:function(data){
						
							
						if(data.length>3)
						   {
				  var array = JSON.parse(data);
				  var newArr = [];
				   while(array.length) newArr.push(array.splice(0,7));
				   console.log(newArr);
				   
				   var dt = new Date();
				   var m=dt.getMonth();
				   m+=1;
				   var y=dt.getFullYear();
				   var d=dt.getDate();
				   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
				   var wb =XLSX.utils.book_new();
				   wb.SheetNames.push("Reporte Usuarios Inactivos");
				   var ws = XLSX.utils.aoa_to_sheet(newArr);
				   wb.Sheets["Reporte Usuarios Inactivos"] = ws;
				   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
				   function s2ab(s) { 
			            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
			            var view = new Uint8Array(buf);  //create uint8array as viewer
			            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
			            return buf;    
				   }
			       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'ReporteUsuariosInactivos'+fecha+'.xlsx');
					   }
				   else{
					   alert("No hay información para descargar");
				   }
					}
				});
				
				
				
				}
 }

 function copiarOpcion(opcion, destino) {
      var valor = $(opcion).val();
      if (($(destino + " option[value=" + valor + "] ").length == 0) && valor != 0 ) {
          $(opcion).appendTo(destino);
      }
  }

  function selecionarTodos(){
  	$("#lista_roles option").each(function(){
	      $(this).attr("selected", true);
		 });
   }
  

 function pone_espera(){

	   $.blockUI({ 
			message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
			css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff',
	           
      		}
  });
	
  setTimeout($.unblockUI, 3000); 
  
 }

      	   
 function load_usuarios(pagina){

	   var search=$("#search").val();
     var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
   $("#load_registrados").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Usuarios&action=consulta_usuarios_activos&search='+search,
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#users_registrados").html(x);
               $("#load_registrados").html("");
               $("#tabla_usuarios").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });


	   }

 function load_usuarios_inactivos(pagina){

	   var search=$("#search_inactivos").val();
     var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
   $("#load_inactivos_registrados").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_inactivos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Usuarios&action=consulta_usuarios_inactivos&search='+search,
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#users_inactivos_registrados").html(x);
               $("#load_inactivos_registrados").html("");
               $("#tabla_usuarios_inactivos").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#users_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

 function validarcedula() {
     var cad = document.getElementById("cedula_usuarios").value.trim();
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
     	  $("#cedula_usuarios").val(cad);
       }else{
     	  $("#mensaje_cedula_usuarios").text("Introduzca Identificación Valida");
	    	$("#mensaje_cedula_usuarios").fadeIn("slow");
     	  document.getElementById("cedula_usuarios").focus();
     	  $("#cedula_usuarios").val("");
     	  
       }
     }
   }
 
 function numeros(e){
     
     key = e.keyCode || e.which;
     tecla = String.fromCharCode(key).toLowerCase();
     letras = "0123456789";
     especiales = [8,37,39,46];
  
     tecla_especial = false
     for(var i in especiales){
     if(key == especiales[i]){
      tecla_especial = true;
      break;
         } 
     }
  
     if(letras.indexOf(tecla)==-1 && !tecla_especial)
         return false;
  }
 
 var interval, mouseMove;

 $(document).mousemove(function(){
     //Establezco la última fecha cuando moví el cursor
     mouseMove = new Date();
     /* Llamo a esta función para que ejecute una acción pasado x tiempo
      después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
     inactividad(function(){
     	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
     }, 600);
   });

 $(document).scroll(function(){
     //Establezco la última fecha cuando moví el cursor
     mouseMove = new Date();
     /* Llamo a esta función para que ejecute una acción pasado x tiempo
      después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
     inactividad(function(){
     	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
     }, 600);
   });

   $(document).keydown(function(){
       //Establezco la última fecha cuando moví el cursor
       mouseMove = new Date();
       /* Llamo a esta función para que ejecute una acción pasado x tiempo
        después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
       inactividad(function(){
       	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
       }, 600);
     });

  

   /* Función creada para ejecutar una acción (callback), al pasar x segundos 
      (seconds) de haber dejado de mover el cursor */
   var inactividad = function(callback, seconds){
     //Elimino el intervalo para que no se ejecuten varias instancias
     clearInterval(interval);
     //Creo el intervalo
     interval = setInterval(function(){
        //Hora actual
        var now = new Date();
        //Diferencia entre la hora actual y la última vez que se movió el cursor
        var diff = (now.getTime()-mouseMove.getTime())/1000;
        //Si la diferencia es mayor o igual al tiempo que pasastes por parámetro
        if(diff >= seconds){
         //Borro el intervalo
         clearInterval(interval);
         //Ejecuto la función que será llamada al pasar el tiempo de inactividad
         callback();          
        }
     }, 200);
   }
   
   
 
	   
	   
  
 