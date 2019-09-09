$(document).ready( function (){
        		   
	load_participes_activos(1);    		   
	load_participes_inactivos(1);
    load_participes_desafiliado(1);
    load_participes_liquidado_cesante(1);
    load_cuentas_activos(1);
    load_contribucion_tipo(1);
        		   
	   			});
  
  function load_participes_activos(pagina){

	   var search=$("#search_activos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_participes_activos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_participes_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Participes&action=consulta_participes_activos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#participes_activos_registrados").html(x);
                $("#load_participes_activos").html("");
                $("#tabla_participes_activos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#participes_activos_registrados").html("Ocurrio un error al cargar la informacion de Participes Activos..."+estado+"    "+error);
             }
           });


	   }
  
  function load_participes_inactivos(pagina){

	   var search=$("#search_inactivos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_participes_inactivos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_participes_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Participes&action=consulta_participes_inactivos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#participes_inactivos_registrados").html(x);
                $("#load_participes_inactivos").html("");
                $("#tabla_participes_inactivos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#participes_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Participes Inactivos..."+estado+"    "+error);
             }
           });
	   }
  
  function load_participes_desafiliado(pagina){

	   var search=$("#search_desafiliado").val();
     var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
   $("#load_participes_desafiliado").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_participes_desafiliado").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Participes&action=consulta_participes_desafiliado&search='+search,
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#participes_desafiliado_registrados").html(x);
               $("#load_participes_desafiliado").html("");
               $("#tabla_participes_desafiliado").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#participes_desafiliado_registrados").html("Ocurrio un error al cargar la informacion de Participes Desafiliado..."+estado+"    "+error);
            }
          });
	   }
  function load_participes_liquidado_cesante(pagina){

	   var search=$("#search_liquidado_cesante").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_participes_liquidado_cesante").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_participes_liquidado_cesante").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Participes&action=consulta_participes_liquidado_cesante&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#participes_liquidado_cesante_registrados").html(x);
              $("#load_participes_liquidado_cesante").html("");
              $("#tabla_participes_liquidado_cesante").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#participes_liquidado_cesante_registrados").html("Ocurrio un error al cargar la informacion de Participes Liquidado Cesante..."+estado+"    "+error);
           }
         });
	   }
  
  function load_cuentas_activos(pagina){

	   var search=$("#txtsearchcuentas").val();
     var con_datos={
				  action:'ajax',
				  id_participes:$("#id_participes").val(),
				  page:pagina
				  };
		 
   $("#load_cuentas_activos").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_cuentas_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Participes&action=consulta_cuentas_activos&search='+search,
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#participes_cuentas_registrados").html(x);
               $("#load_cuentas_activos").html("");
               $("#tabla_cuentas_activos").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#participes_cuentas_registrados").html("Ocurrio un error al cargar la informacion de Cuentas..."+estado+"    "+error);
            }
          });


	   }
 
  $("#Guardar").on("click",function(){
	  
	  let $fecha_ingreso_participes = $("#fecha_ingreso_participes");		
	  let $fecha_defuncion_participes = $("#fecha_defuncion_participes");		
	  let $id_estado_participes = $("#id_estado_participes");		
	  let $id_estatus = $("#id_estatus");		
	  let $fecha_salida_participes = $("#fecha_salida_participes");		
	  let $fecha_numero_orden_participes = $("#fecha_numero_orden_participes");		
			  
	   if( $fecha_ingreso_participes.val().length == 0 || $fecha_ingreso_participes.val() == '' ){
		   $fecha_ingreso_participes.notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $fecha_defuncion_participes.val().length == 0 || $fecha_defuncion_participes.val() == '' ){
		   $fecha_defuncion_participes.notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $id_estado_participes.val().length == '' || $id_estado_participes.val() == 0 ){
		   $id_estado_participes.notify("Ingrese un Estado",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $id_estatus.val().length == '' || $id_estatus.val() == 0 ){
		   $id_estatus.notify("Ingrese un Estatus",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $fecha_salida_participes.val().length == 0 || $fecha_salida_participes.val() == '' ){
		   $fecha_salida_participes.notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $fecha_numero_orden_participes.val().length == 0 || $fecha_numero_orden_participes.val() == '' ){
		   $fecha_numero_orden_participes.notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   
	   let $id_participes = $("#id_participes").val();
	   let $id_entidad_patronal = $("#id_entidad_patronal").val();
	   let $fecha_entrada_patronal_participes = $("#fecha_entrada_patronal_participes").val();
	   let $cedula_participes = $("#cedula_participes").val();
	   let $observacion_participes = $("#observacion_participes").val();
	   let $codigo_alternativo_participes = $("#codigo_alternativo_participes").val();
	   let $apellido_participes = $("#apellido_participes").val();
	   let $nombre_participes = $("#nombre_participes").val();
	   let $fecha_nacimiento_participes = $("#fecha_nacimiento_participes").val();
	   let $id_genero_participes = $("#id_genero_participes").val();
	   let $ocupacion_participes = $("#ocupacion_participes").val();
	   let $id_tipo_instruccion_participes = $("#id_tipo_instruccion_participes").val();
	   let $id_estado_civil_participes = $("#id_estado_civil_participes").val();
	   let $correo_participes = $("#correo_participes").val();
	   let $nombre_conyugue_participes = $("#nombre_conyugue_participes").val(); 
	   let $apellido_esposa_participes = $("#apellido_esposa_participes").val();
	   let $cedula_conyugue_participes = $("#cedula_conyugue_participes").val();
	   let $numero_dependencias_participes = $("#numero_dependencias_participes").val();
	   let $id_ciudades = $("#id_ciudades").val(); 
	   let $direccion_participes = $("#direccion_participes").val();
	   let $telefono_participes = $("#telefono_participes").val();
	   let $celular_participes = $("#celular_participes").val();
	   let $id_distritos = $("#id_distritos").val();
	   let $id_provincia = $("#id_provincias").val();
	   let $parroquia_participes_informacion_adicional = $("#parroquia_participes_informacion_adicional").val();
	   let $sector_participes_informacion_adicional = $("#sector_participes_informacion_adicional").val();
	   let $ciudadela_participes_informacion_adicional = $("#ciudadela_participes_informacion_adicional").val();
	   let $calle_participes_informacion_adicional = $("#calle_participes_informacion_adicional").val();
	   let $numero_calle_participes_informacion_adicional = $("#numero_calle_participes_informacion_adicional").val();
	   let $interseccion_participes_informacion_adicional = $("#interseccion_participes_informacion_adicional").val();
	   let $id_tipo_vivienda = $("#id_tipo_vivienda").val();
	   let $anios_residencia_participes_informacion_adicional = $("#anios_residencia_participes_informacion_adicional").val();
	   let $nombre_propietario_participes_informacion_adicional = $("#nombre_propietario_participes_informacion_adicional").val();
	   let $telefono_propietario_participes_informacion_adicional = $("#telefono_propietario_participes_informacion_adicional").val();
	   let $direccion_referencia_participes_informacion_adicional = $("#direccion_referencia_participes_informacion_adicional").val();
	   let $vivienda_hipotecada_participes_informacion_adicional = $("#vivienda_hipotecada_participes_informacion_adicional").val();
	   let $nombre_una_referencia_participes_informacion_adicional = $("#nombre_una_referencia_participes_informacion_adicional").val();
	   let $id_parentesco = $("#id_parentesco").val();
	   let $telefono_una_referencia_participes_informacion_adicional = $("#telefono_una_referencia_participes_informacion_adicional").val();
	   let $observaciones_participes_informacion_adicional = $("#observaciones_participes_informacion_adicional").val();
	   let $kit_participes_informacion_adicional = $("#kit_participes_informacion_adicional").val();
	   let $contrato_adhesion_participes_informacion_adicional = $("#contrato_adhesion_participes_informacion_adicional").val();

	   let datos = {
			   id_participes : $id_participes,
			   id_entidad_patronal : $id_entidad_patronal,
			   fecha_entrada_patronal_participes : $fecha_entrada_patronal_participes,
			   cedula_participes : $cedula_participes,
			   observacion_participes : $observacion_participes,
			   codigo_alternativo_participes : $codigo_alternativo_participes,
			   apellido_participes : $apellido_participes,
			   nombre_participes : $nombre_participes,
			   fecha_nacimiento_participes : $fecha_nacimiento_participes,
			   id_genero_participes : $id_genero_participes,
			   ocupacion_participes : $ocupacion_participes,
			   id_tipo_instruccion_participes : $id_tipo_instruccion_participes,
			   id_estado_civil_participes : $id_estado_civil_participes,
			   correo_participes : $correo_participes,
			   nombre_conyugue_participes : $nombre_conyugue_participes,
			   apellido_esposa_participes : $apellido_esposa_participes,
			   cedula_conyugue_participes : $cedula_conyugue_participes,
			   numero_dependencias_participes : $numero_dependencias_participes,
			   id_ciudades : $id_ciudades,
			   direccion_participes : $direccion_participes,
			   telefono_participes : $telefono_participes,
			   celular_participes : $celular_participes,
			   fecha_ingreso_participes : $fecha_ingreso_participes.val(),
			   fecha_defuncion_participes : $fecha_defuncion_participes.val(),
			   id_estado_participes : $id_estado_participes.val(),
			   id_estatus : $id_estatus.val(),
			   fecha_salida_participes : $fecha_salida_participes.val(),
			   fecha_numero_orden_participes : $fecha_numero_orden_participes.val(),
			   id_distritos : $id_distritos,
			   id_provincia : $id_provincia,
			   parroquia_participes_informacion_adicional : $parroquia_participes_informacion_adicional,
			   sector_participes_informacion_adicional : $sector_participes_informacion_adicional,
			   ciudadela_participes_informacion_adicional : $ciudadela_participes_informacion_adicional,
			   calle_participes_informacion_adicional : $calle_participes_informacion_adicional,
			   numero_calle_participes_informacion_adicional : $numero_calle_participes_informacion_adicional,
			   interseccion_participes_informacion_adicional : $interseccion_participes_informacion_adicional,
			   id_tipo_vivienda : $id_tipo_vivienda,
			   anios_residencia_participes_informacion_adicional : $anios_residencia_participes_informacion_adicional,
			   nombre_propietario_participes_informacion_adicional : $nombre_propietario_participes_informacion_adicional,
			   telefono_propietario_participes_informacion_adicional : $telefono_propietario_participes_informacion_adicional,
			   direccion_referencia_participes_informacion_adicional : $direccion_referencia_participes_informacion_adicional,
			   vivienda_hipotecada_participes_informacion_adicional : $vivienda_hipotecada_participes_informacion_adicional,
			   nombre_una_referencia_participes_informacion_adicional : $nombre_una_referencia_participes_informacion_adicional,
			   id_parentesco : $id_parentesco,
			   telefono_una_referencia_participes_informacion_adicional : $telefono_una_referencia_participes_informacion_adicional,
			   observaciones_participes_informacion_adicional : $observaciones_participes_informacion_adicional,
			   kit_participes_informacion_adicional : $kit_participes_informacion_adicional,
			   contrato_adhesion_participes_informacion_adicional : $contrato_adhesion_participes_informacion_adicional
				   
	   }
	   console.log(datos);
	   
	   $.ajax({
		   url:"index.php?controller=Participes&action=InsertaParticipes",
		   type:"POST",
		   dataType:"json",
		   data: datos
	   }).done(function(x){
		   console.log(x);
		   if(x.respuesta == 1){
			   swal({
				   title:"Correctamente",
				   text:x.mensaje,
				   icon:"success",				   
			   })
			   
			   load_participes_activos(1)
			   
		   }
	   }).fail(function(xhr,status,error){
		   var err = xhr.responseText 
		   console.log(err)
	   })
	   
	   	
	   
	   return false;
   
  })
  
  $("#Procesar").on("click",function(){
	  
	
	  let $id_participes1 = $("#id_participes");
	  let $id_bancos = $("#id_bancos");		
	  let $id_tipo_cuentas = $("#id_tipo_cuentas");		
	  let $numero_participes_cuentas = $("#numero_participes_cuentas");		
	  let $cuenta_principal = $("#cuenta_principal");		
	  
	  
	   if( $id_bancos.val().length == '' || $id_bancos.val() == 0 ){
		   $id_bancos.notify("Seleccione un Banco",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $id_tipo_cuentas.val().length == '' || $id_tipo_cuentas.val() == 0 ){
		   $id_tipo_cuentas.notify("Seleccione un Tipo",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }  
	   if( $numero_participes_cuentas.val().length == '' || $numero_participes_cuentas.val() == 0 ){
		   $numero_participes_cuentas.notify("Ingrese un número de cuenta",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $cuenta_principal.val().length == '' || $cuenta_principal.val() == 0 ){
		   $cuenta_principal.notify("Seleccione",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   
	   
	  
	   let datos1 = {
			   id_participes : $id_participes1.val(),
			   id_bancos : $id_bancos.val(),
			   id_tipo_cuentas : $id_tipo_cuentas.val(),
			   numero_participes_cuentas : $numero_participes_cuentas.val(),
			   cuenta_principal : $cuenta_principal.val()
				   
	   }
	   console.log(datos1);
	   
	   $.ajax({
		   url:"index.php?controller=Participes&action=InsertaParticipesCuentas",
		   type:"POST",
		   dataType:"json",
		   data: datos1
	   }).done(function(x){
		   console.log(x);
		   if(x.respuesta == 1){
			   swal({
				   title:"Correctamente",
				   text:x.mensaje,
				   icon:"success",				   
			   })
			   
			  load_cuentas_activos(1);  
		   }
	   }).fail(function(xhr,status,error){
		   var err = xhr.responseText
		   console.log(err)
	   })
	   return false;
   
  })
  
  function delCuentas(id){
	
		
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=Participes&action=delCuentas",
		type:"POST",
		dataType:"json",
		data:{id_participes_cuentas:id}
	}).done(function(datos){		
		
		if(datos.data > 0){
			
			swal({
		  		  title: "Cuenta",
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
		load_cuentas_activos();
	})
	
	return false;
}
  

  function editCuentas(id = 0){
  	
  	var tiempo = tiempo || 1000;
  		
  	$.ajax({
  		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
  		url:"index.php?controller=Participes&action=editCuentas",
  		type:"POST",
  		dataType:"json",
  		data:{id_participes_cuentas:id}
  	}).done(function(datos){
  		
  		if(!jQuery.isEmptyObject(datos.data)){
	
  			
  			var array = datos.data[0];		
  			$("#id_participes").val(array.id_participes);			
  			$("#id_bancos").val(array.id_bancos);
  			$("#id_tipo_cuentas").val(array.id_tipo_cuentas);
  			$("#numero_participes_cuentas").val(array.numero_participes_cuentas);
  			
  			var _cuenta_principal = array.cuenta_principal;
  			
  			if (_cuenta_principal == 't'){
  				
  				$("#cuenta_principal").val('TRUE');
  				
  			}else {

  				$("#cuenta_principal").val('FALSE');
  				
  			}
  			
  			
  			$("#id_participes_cuentas").val(array.id_participes_cuentas);
  			
  			$("html, body").animate({ scrollTop: $(id_participes).offset().top-120 }, tiempo);			
  		}
  		
  		
  		
  	}).fail(function(xhr,status,error){
  		
  		var err = xhr.responseText
  		console.log(err);
  	}).always(function(){
  		
  		$("#divLoaderPage").removeClass("loader")
  		load_cuentas_activos();
  	})
  	
  	return false;
  	
  }
  
  function load_contribucion_tipo(pagina){

	   var search=$("#txtsearchcontribuciontipo").val();
    var con_datos={
				  action:'ajax',
				  id_participes:$("#id_participes").val(),
				  page:pagina
				  };
		 
  $("#load_contribucion_tipo").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_contribucion_tipo").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Participes&action=consulta_contribucion_tipo&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#contribucion_tipo_registrados").html(x);
              $("#load_contribucion_tipo").html("");
              $("#tabla_contribucion_tipo").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#contribucion_tipo_registrados").html("Ocurrio un error al cargar la informacion de Contribuciones..."+estado+"    "+error);
           }
         });


	   }

  $("#Generar").on("click",function(){
	  

	  let $id_contribucion_tipo = $("#id_contribucion_tipo");
	  let $id_participes2 = $("#id_participes");		
	  let $id_tipo_aportacion = $("#id_tipo_aportacion");		
	  let $valor_contribucion_tipo_participes = $("#valor_contribucion_tipo_participes");		
	  let $sueldo_liquido_contribucion_tipo_participes = $("#sueldo_liquido_contribucion_tipo_participes");		
	  let $id_estado = $("#id_estado");
	  let $porcentaje_contribucion_tipo_participes = $("#porcentaje_contribucion_tipo_participes");
	  
	   if( $id_contribucion_tipo.val().length == '' || $id_contribucion_tipo.val() == 0 ){
		   $id_contribucion_tipo.notify("Ingrese una Contribución",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $id_tipo_aportacion.val().length == '' || $id_tipo_aportacion.val() == 0 ){
		   $id_tipo_aportacion.notify("Ingrese una Aportación",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $id_estado.val().length == '' || $id_estado.val() == 0 ){
		   $id_estado.notify("Ingrese un Estado",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $valor_contribucion_tipo_participes.val().length == '' || $valor_contribucion_tipo_participes.val() == '' ){
		   $valor_contribucion_tipo_participes.notify("Ingrese un Valor",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $sueldo_liquido_contribucion_tipo_participes.val().length == '' || $sueldo_liquido_contribucion_tipo_participes.val() == 0 ){
		   $sueldo_liquido_contribucion_tipo_participes.notify("Ingrese un Sueldo",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	   if( $porcentaje_contribucion_tipo_participes.val().length == '' || $porcentaje_contribucion_tipo_participes.val() == '' ){
		   $porcentaje_contribucion_tipo_participes.notify("Ingrese un Porcentaje",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	   }
	 
	 
	

	  
	   let datos2 = {
			   id_contribucion_tipo : $id_contribucion_tipo.val(),
			   id_participes : $id_participes2.val(),
			   id_tipo_aportacion : $id_tipo_aportacion.val(),
			   valor_contribucion_tipo_participes : $valor_contribucion_tipo_participes.val(),
			   sueldo_liquido_contribucion_tipo_participes : $sueldo_liquido_contribucion_tipo_participes.val(),
			   id_estado : $id_estado.val(),
			   porcentaje_contribucion_tipo_participes : $porcentaje_contribucion_tipo_participes.val()
				   
	   }
	   // console.log (datos);
	   console.log(datos2);
	   
	   $.ajax({
		   url:"index.php?controller=Participes&action=InsertaContribucionTipo",
		   type:"POST",
		   dataType:"json",
		   data: datos2
	   }).done(function(x){
		   console.log(x);
		   if(x.respuesta == 1){
			   swal({
				   title:"CONTRIBUCIÓN",
				   text:x.mensaje,
				   icon:"success",				   
			   })
			   
			  load_contribucion_tipo(1);  
		   }
	   }).fail(function(xhr,status,error){
		   var err = xhr.responseText
		   console.log(err)
		   
	   }).always(function(){
			$("#id_contribucion_tipo_participes").val(0);
			document.getElementById("frm_participes").reset();	
			load_contribucion_tipo(1);
		})
			event.preventDefault()
			
	   return false;
   
  })

   function editContribucion(id = 0){
  	
  	var tiempo = tiempo || 1000;
  		
  	$.ajax({
  		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
  		url:"index.php?controller=Participes&action=editContribucion",
  		type:"POST",
  		dataType:"json",
  		data:{id_contribucion_tipo_participes:id}
  	}).done(function(datos){
  		
  		if(!jQuery.isEmptyObject(datos.data)){
	
  			var array = datos.data[0];		
  			$("#id_contribucion_tipo").val(array.id_contribucion_tipo);			
  			$("#id_participes").val(array.id_participes);
  			$("#id_tipo_aportacion").val(array.id_tipo_aportacion);
  			$("#valor_contribucion_tipo_participes").val(array.valor_contribucion_tipo_participes);
  			$("#sueldo_liquido_contribucion_tipo_participes").val(array.sueldo_liquido_contribucion_tipo_participes);
  			$("#id_estado").val(array.id_estado);
  			$("#porcentaje_contribucion_tipo_participes").val(array.porcentaje_contribucion_tipo_participes);
  	
  			
  			$("html, body").animate({ scrollTop: $(id_participes).offset().top-120 }, tiempo);			
  		}
  		
  		
  		
  	}).fail(function(xhr,status,error){
  		
  		var err = xhr.responseText
  		console.log(err);
  	}).always(function(){
  		
  		$("#divLoaderPage").removeClass("loader")
  		load_contribucion_tipo(1);
  	})
  	
  	return false;
  	
  }
  
  function delContribucion(id){
		
		
		$.ajax({
			beforeSend:function(){$("#divLoaderPage").addClass("loader")},
			url:"index.php?controller=Participes&action=delContribucion",
			type:"POST",
			dataType:"json",
			data:{id_contribucion_tipo_participes:id}
		}).done(function(datos){		
			
			if(datos.data > 0){
				
				swal({
			  		  title: "CONTRIBUCIÓN",
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
			load_contribucion_tipo(1);
		})
		
		return false;
	}
  
  function cambio(){


	  let $id_tipo_aportacion = $("#id_tipo_aportacion");
	  let $valor_contribucion_tipo_participes = $("#valor_contribucion_tipo_participes");		
	  let $porcentaje_contribucion_tipo_participes = $("#porcentaje_contribucion_tipo_participes");		
	  let $sueldo_liquido_contribucion_tipo_participes = $("#sueldo_liquido_contribucion_tipo_participes");		
	
  }
  
  $("#id_tipo_aportacion").on('change',function(){
	  
	  let $elemento = $(this),
		  $valor_contribucion_tipo_participes = $("#valor_contribucion_tipo_participes"),		
		  $porcentaje_contribucion_tipo_participes = $("#porcentaje_contribucion_tipo_participes"),	
		  $sueldo_liquido_contribucion_tipo_participes = $("#sueldo_liquido_contribucion_tipo_participes");
	  	  $div_porcentaje = $("#div_porcentaje");
	  
	  let _texto = $elemento.find("option:selected").html();
	  
	  console.log(_texto);
	  console.log($elemento.find("option:selected").html());
	  
	  if(_texto.trim() == 'PORCENTAJE'){
		  $div_porcentaje.css({display:'inline'});
		  $valor_contribucion_tipo_participes.attr('readonly',true);
		  $valor_contribucion_tipo_participes.val('0');
			
	  }else if(_texto.trim() == 'VALOR'){
		
		  $div_porcentaje.css({display:'none'});
		  $valor_contribucion_tipo_participes.attr('readonly',false);
		  $porcentaje_contribucion_tipo_participes.val('0');
	 
	  }
	  
  })
  