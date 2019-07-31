$(document).ready( function (){
        		   
	load_participes_activos(1);    		   
	load_participes_inactivos(1);
    load_participes_desafiliado(1);
    load_participes_liquidado_cesante(1);
    load_cuentas_activos(1);
        		   
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
	  
	
	  let $id_participes1 = $("#id_participes").val();
	  let $id_bancos = $("#id_bancos").val();		
	  let $id_tipo_cuentas = $("#id_tipo_cuentas").val();		
	  let $numero_participes_cuentas = $("#numero_participes_cuentas").val();		
	  let $cuenta_principal = $("#cuenta_principal").val();		
	   
	   let datos1 = {
			   id_participes : $id_participes1,
			   id_bancos : $id_bancos,
			   id_tipo_cuentas : $id_tipo_cuentas,
			   numero_participes_cuentas : $numero_participes_cuentas,
			   cuenta_principal : $cuenta_principal
				   
	   }
	   // console.log (datos);
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