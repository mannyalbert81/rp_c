$(document).ready(function(){ 	 
	
	load_temp_comprobantes(1);
	searchNumComprobante();
    $('#datos_proveedor').hide();
    $('input[name="debe_dcomprobantes"]').mask("#,##0.00", {reverse: true});
    $('input[name="haber_dcomprobantes"]').mask("#,##0.00", {reverse: true});
    $('input[name="numero_cuenta_banco_ccomprobantes"]').mask("00000000000000000000", {reverse: true});
    $('input[name="numero_cheque_ccomprobantes"]').mask("00000000000000000000", {reverse: true});
    $('input[name="telefono_proveedores"]').mask("0000000000", {reverse: true});
    $('input[name="identificacion_proveedores"]').mask("0000000000", {reverse: true});
    
	
});

/** CONSULTAR NUMERO DE COMPROBANTE **/
function searchNumComprobante(){
	var $_numero_comprobantes	= $("#con_numero_comprobantes");
	
	$.ajax({
		url:"index.php?controller=ComprobanteContable&action=traeNumComprobante",
		dataType:"json",
		type:"POST",
		data:null,
	}).done(function(x){
		if( x.numero_consecutivo != undefined ){
			
			$_numero_comprobantes.val(x.numero_consecutivo);
			
		}
	}).fail(function(xhr,status,error){
		var err = xhr.responseText;
		console.log(err);
	})
}

/** CARGAR TEMPORAL COMPROBANTES REGISTRADOS **/
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

function agregar_temp_comprobantes (){
	
	var plan_cuentas=document.getElementById('plan_cuentas').value;
	var descripcion_dcomprobantes=document.getElementById('descripcion_dcomprobantes').value;
	var debe_dcomprobantes=document.getElementById('debe_dcomprobantes').value;
	var haber_dcomprobantes=document.getElementById('haber_dcomprobantes').value;
	

	var error="FALSE";
	
	if (plan_cuentas == 0){
		
		$("#id_plan_cuentas").notify("Digite una Cuenta Contable",{autoHideDelay:1000,position:"buttom left"});
		error ="TRUE";
		return false;		
    }
		
	if (debe_dcomprobantes == 0.00 && haber_dcomprobantes == 0.00){
    	
		$("#debe_dcomprobantes").notify("Digite Valor de Movimiento",{autoHideDelay:1000,position:"buttom left"});
		error ="TRUE";
		return false;
    }
	
	if(error == "FALSE"){
		
		$.ajax({
            type: "POST",
            url: 'index.php?controller=ComprobanteContable&action=insertar_temp_comprobantes',
            data: "plan_cuentas="+plan_cuentas+"&descripcion_dcomprobantes="+descripcion_dcomprobantes+"&debe_dcomprobantes="+debe_dcomprobantes+"&haber_dcomprobantes="+haber_dcomprobantes,
        	
            success: function(datos){
            	//console.log(datos)
            	limpiar();
            	load_temp_comprobantes(1);
            	
            },
            error: function(xhr,status,error){
            	var err = xhr.responseText;
            	console.log(err)
            }
		});
		
	}
	
	
}
	
// ELIMINAR REGISTRO DE TABLA TEMPORAL	    
function eliminar_temp_comprobantes(id){
	$.ajax({
        type: "POST",
        url: 'index.php?controller=ComprobanteContable&action=eliminar_temp_comprobantes',
        data: "id_temp_comprobantes="+id,
    	 success: function(datos){
    		 	load_temp_comprobantes(1);
    	 }
	});
}




function limpiar() {
	   
	$('#plan_cuentas').val("0");
	$('#id_plan_cuentas').val("");
	$('#nombre_plan_cuentas').val("");
	$('#descripcion_dcomprobantes').val("");
	$('#debe_dcomprobantes').val("0.00");
	$('#haber_dcomprobantes').val("0.00");
  
  }

/** AUTOCOMPLETE DE CODIGO PLAN CUENTAS **/  
function autompleteCodigo(elemento){
	  
	  var _elemento = $(elemento);
	  //console.log("ingreso codigo complete");	  
	  if ( !_elemento.data("autocomplete") ) {
		  
		  _elemento.autocomplete({
	    		minLength: 3,    	    
	    		source:function (request, response) {
	    			$.ajax({
	    				url:"index.php?controller=ComprobanteContable&action=autompleteCodigo",
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
	    			
	    			if(ui.item.id == ''){
	    				 _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left"});
	    				 $('#nombre_plan_cuentas').val('');
	 	    			 $('#plan_cuentas').val(0);
	    				 return;
	    			}
	    			
	    			$('#nombre_plan_cuentas').val(ui.item.nombre);
	    			$('#plan_cuentas').val(ui.item.id);
	    				    			     	     
	     	    },
	     	   appendTo: null,
	     	   change: function(event,ui){
	     		   if(ui.item == null){
	     			   
	     			 _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left"});
	     			 _elemento.val("")
  				 $('#nombre_plan_cuentas').val('');
	    			 $('#plan_cuentas').val(0);	
	 	   			 $('#descripcion_dcomprobantes').val("");
	 	   			 $('#debe_dcomprobantes').val("0.00");
	 	   			 $('#haber_dcomprobantes').val("0.00");		 	   			
	     			
	     		   }
	     	   }
	    	
	    	}).focusout(function() {
	    		
	    	})
	  }
	  
}

/** AUTOCOMPLETE DE NOMBRE PLAN CUENTAS **/  
function autompleteNombre(elemento){
	  
	  var _elemento = $(elemento);
	  //console.log("ingreso codigo complete");	  
	  if ( !_elemento.data("autocomplete") ) {
		  
		  _elemento.autocomplete({
	    		minLength: 3,    	    
	    		source:function (request, response) {
	    			$.ajax({
	    				url:"index.php?controller=ComprobanteContable&action=autompleteNombrePlanCuentas",
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
	    			
	    			if(ui.item.id == ''){
	    				 _elemento.notify("Digite Nombre Cuenta Valido",{ position:"buttom left"});
	    				 $('#id_plan_cuentas').val('');
	 	    			 $('#plan_cuentas').val(0);
	    				 return;
	    			}
	    			
	    			$('#id_plan_cuentas').val(ui.item.codigo);
	    			$('#plan_cuentas').val(ui.item.id);
	    				    			     	     
	     	    },
	     	   appendTo: null,
	     	   change: function(event,ui){
	     		   if(ui.item == null){
	     			   
	     			 _elemento.notify("Digite Nombre Cuenta Valido",{ position:"buttom left"});
	     			 _elemento.val("")
  				 $('#id_plan_cuentas').val('');
	    			 $('#plan_cuentas').val(0);	
	 	   			 $('#descripcion_dcomprobantes').val("");
	 	   			 $('#debe_dcomprobantes').val("0.00");
	 	   			 $('#haber_dcomprobantes').val("0.00");		 	   			
	     			
	     		   }
	     	   }
	    	
	    	}).focusout(function() {
	    		
	    	})
	  }
	  
}

/** VALIDAR QUE VAYA SOLO EL DEBE O SOLO HABER **/
function validardebe(field) {
	var nombre_elemento = field.id;
	if(nombre_elemento=="debe_dcomprobantes"){
		$("#haber_dcomprobantes").val("0.00");
		
	}else{
		$("#debe_dcomprobantes").val("0.00");
	}
	
}

/** Evento cuando se abre el modal **/
$('#myModal').on('show.bs.modal', function (event) {
	load_plan_cuentas(1);
	var modal = $(this)
	modal.find('.modal-title').text('Plan de Cuentas')
  
});

/** Funcion Que carga el plan de ceuntas en modal **/
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

/** FUNCION PARA INSERTADO DE COMPROMBANTES **/
function fnInsComprobante(){
	
	var $_id_tipo_comprobantes  = $("#con_id_tipo_comprobantes"),
		$_fecha_comprobantes	= $("#con_fecha_comprobantes"),
		$_numero_comprobantes	= $("#con_numero_comprobantes"),
		$_documento_comprobantes= $("#con_referencia_doc_comprobantes"),
		$_concepto_comprobantes = $("#con_concepto_comprobantes");
	
	var tiempo = tiempo || 1000;
	
	if( $_id_tipo_comprobantes.val() == 0 ){
		$_id_tipo_comprobantes.notify("seleccione Tipo Comprobante",{ position:"buttom left", autoHideDelay: 2000});
		$("html, body").animate({ scrollTop: $_id_tipo_comprobantes.offset().top-120 }, tiempo);	        
		return false;		
	}
	
	if( $_fecha_comprobantes.val() == "" || $_fecha_comprobantes.val().length == 0 ){
		$_fecha_comprobantes.notify("Fecha no definida",{ position:"buttom left", autoHideDelay: 2000});
		$("html, body").animate({ scrollTop: $_fecha_comprobantes.offset().top-120 }, tiempo);	        
		return false;		
	}
	
	if( $_numero_comprobantes.val() == "" || $_numero_comprobantes.val().length == 0 ){
		$_numero_comprobantes.notify("Num. Comprobante no definido",{ position:"buttom left", autoHideDelay: 2000});
		$("html, body").animate({ scrollTop: $_numero_comprobantes.offset().top-120 }, tiempo);	        
		return false;		
	}
	
	if( $_concepto_comprobantes.val() == "" || $_concepto_comprobantes.val().length == 0 ){
		$_concepto_comprobantes.notify("Digite un Concepto",{ position:"buttom left", autoHideDelay: 2000});
		$("html, body").animate({ scrollTop: $_concepto_comprobantes.offset().top-120 }, tiempo);	        
		return false;		
	}
	
	if(!document.getElementById("valor_total_temp")){
		swal({title: "Movimientos",
	   		  text: "Registre Movimiento",
	   		  icon: "error",
	   		  button: "Aceptar",
	   		})
		return false;		
	}
	
	if(document.getElementById("valor_total_temp").value == 0){
		swal({title: "Movimientos",
	   		  text: "Debe/Haber no Coinciden",
	   		  icon: "warning",
	   		  button: "Aceptar",
	   		});
		return false;
	}
	
	var parametros = {
			 action						: 'ajax',
			 id_tipo_comprobantes 		: $_id_tipo_comprobantes.val(),
			 fecha_ccomprobantes 		: $_fecha_comprobantes.val(),
			 referencia_ccomprobantes 	: $_documento_comprobantes.val(),
			 concepto_ccomprobantes		: $_concepto_comprobantes.val(),
			 valor_letras				: $('#valor_letras').val()
	 }
	 
	 $.ajax({
        url: 'index.php?controller=ComprobanteContable&action=insertacomprobante',
        type: 'POST',
        data: parametros,
        dataType:'json',
        success: function(x){
        	//console.log(x);
        	if( x.respuesta != undefined ){
        		
        		if(x.respuesta == "ERROR"){
        			
        			swal({
                		  title: "COMPROBANTE CONTABLE",
                		  text: x.mensaje,
                		  icon: "warning",
                		  button: "Aceptar",
                		});
        			
        		}else{
        			
        			swal({
              		  title: "COMPROBANTE CONTABLE",
              		  text: "comprobante generado con exito",
              		  icon: "success",
              		  button: "Aceptar",
              		});
        			
        			afterInsertComprobante();
        	       	load_temp_comprobantes(1);
        		}
        		
        	}
       	 
       	 
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
		
}

/** fn para cuando inserte el comprobante **/
function afterInsertComprobante(){
	
	$("#con_id_tipo_comprobantes").val(0);
	$("#con_fecha_comprobantes").val();
	$("#con_numero_comprobantes").val("");
	$("#con_referencia_doc_comprobantes").val("");
	$("#con_concepto_comprobantes").val("");
	
}

function verificaPeriodo(){
	
	var $fecha = $("#con_fecha_comprobantes");
	var tiempo = tiempo || 1000;
	$.ajax({
		url:"index.php?controller=ComprobanteContable&action=jsverificarPeriodo",
		type:"POST",
		dataType:"json",
		data:{con_fecha:$fecha.val()}
	}).done(function(x){
		if( x.respuesta != undefined ){
			
			if(x.respuesta == "ERROR"){
				var mensaje = x.mensaje;
				$fecha.notify(mensaje,{ position:"buttom left", autoHideDelay: 2000});
				$("html, body").animate({ scrollTop: $fecha.offset().top-120 }, tiempo);
				
			}
		}
		
	}).fail(function(xhr,estado,error){
		
		console.log("Error en conexion favor revisar datos enviados");
	})	
	
}

