$(document).ready(function(){
	$(".cantidades1").inputmask();
	devuelveConsecutivoCxP();
	cargaFormasPago();
	cargaBancos();
	consultaActivos();
		
})

function numeros(e){
	  var key = window.event ? e.which : e.keyCode;
	  if (key < 48 || key > 57) {
	    e.preventDefault();
	  }
 }

/***
 * function to upload formas pago
 * dc 2019-04-18
 * @returns
 */
function cargaFormasPago(){
	let $formapago = $("#id_forma_pago");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaFormasPago",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$formapago.empty();
		$formapago.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$formapago.append("<option value= " +value.id_forma_pago +" >" + value.nombre_forma_pago  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$formapago.empty();
	})
}

/***
 * function to upload bancos
 * dc 2019-04-18
 * @returns
 */
function cargaBancos(){
	let $bancos = $("#id_bancos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=cargaBancos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$bancos.empty();
		$bancos.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$bancos.append("<option value= " +value.id_bancos +" >" + value.nombre_bancos  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$bancos.empty();
	})
}

/***
 * function to search proveedores
 * @returns
 */
$( "#cedula_proveedor" ).autocomplete({

	source: "index.php?controller=Proveedores&action=buscaProveedorByCedula",
	minLength: 6,
    select: function (event, ui) {
       // Set selection          
       $('#id_proveedor').val(ui.item.id);
       $('#cedula_proveedor').val(ui.item.value);
       $("#nombre_proveedor").val(ui.item.nombre);
       $("#email_proveedor").val(ui.item.email);
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#cedula_usuarios').val();            
        return false; 
    } 
}).focusout(function() {
	
});

function devuelveConsecutivoCxP(){
	
	let $numeroPago = $("#numero_pago");
	let $idconsecutivo = $("#id_consecutivo");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=CuentasPagar&action=DevuelveConsecutivoCxP",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		let array = datos.data[0];
		
		$numeroPago.val(array.numero_consecutivos);
		$idconsecutivo.val(array.id_consecutivos);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar consecutivos de Cuentas X Pagar');
		
	})
}

/*
 * fn para poner en mayusculas
 */
 $("input#responsable_activos_fijos").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })

 $("input#nombre_activos_fijos").on("keyup", function () {
	 $(this).val($(this).val().toUpperCase());
 })
 
function consultaActivos(page=1){
	
	parametros = {search:'',peticion:'ajax'}
	
	$.ajax({
		beforeSend:function(x){},
		url:"index.php?controller=ActivosFijos&action=cunsultaActivos",
		type:"POST",
		data:parametros,
		dataType:"html"
	}).done(function(data){
		
		$("#activos_fijos_registrados").html(data);
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText;
		
		console.log(err);
	})
}


$(document).ready(function(){
    
    $("#Guardar").click(function() 
	{
    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

    	var id_oficina = $("#id_oficina").val();
    	var id_tipo_activos_fijos = $("#id_tipo_activos_fijos").val();
    	var id_departamento = $("#id_departamento").val();
    	var id_estado = $("#id_estado").val();
    	var fecha_activos_fijos = $("#fecha_activos_fijos").val();
    	var responsable_activos_fijos = $("#responsable_activos_fijos").val();
    	var nombre_activos_fijos = $("#nombre_activos_fijos").val();
    	var valor_activos_fijos = $("#valor_activos_fijos").val();
    	var imagen_activos_fijos = $("#imagen_activos_fijos").val();
    	
    	
    	
    	if (id_oficina == 0)
    	{
	    	
    		$("#mensaje_id_oficina").text("Introduzca Una Oficina");
    		$("#mensaje_id_oficina").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_oficina").fadeOut("slow"); //Muestra mensaje de error
            
		}   

    	if (id_tipo_activos_fijos == 0)
    	{
	    	
    		$("#mensaje_id_tipo_activos_fijos").text("Introduzca Un Tipo");
    		$("#mensaje_id_tipo_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_tipo_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		} 
    	if (id_departamento == 0)
    	{
	    	
    		$("#mensaje_id_departamento").text("Introduzca Un Departamento");
    		$("#mensaje_id_departamento").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_departamento").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (id_estado == 0)
    	{
	    	
    		$("#mensaje_id_estado").text("Introduzca Un Estado");
    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	
    	
    	if (fecha_activos_fijos =="")
    	{
	    	
    		$("#mensaje_fecha_activos_fijos").text("Inserte una Fecha");
    		$("#mensaje_fecha_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_fecha_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (responsable_activos_fijos == "")
    	{
	    	
    		$("#mensaje_codigo_activos_fijos").text("Introduzca Un Responsable");
    		$("#mensaje_codigo_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_codigo_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	if (nombre_activos_fijos == "")
    	{
	    	
    		$("#mensaje_nombre_activos_fijos").text("Introduzca Un Nombre");
    		$("#mensaje_nombre_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_nombre_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	if (valor_activos_fijos == "")
    	{
	    	
    		$("#mensaje_valor_activos_fijos").text("Introduzca Un Valor");
    		$("#mensaje_valor_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_valor_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	var id_activos_fijos = document.getElementById("id_activos_fijos").value
    	
    	if (imagen_activos_fijos == "" && id_activos_fijos == 0)
    	{
	    	
    		$("#mensaje_imagen_activos_fijos").text("Introduzca Una Imagen");
    		$("#mensaje_imagen_activos_fijos").fadeIn("slow"); //Muestra mensaje de error
            return false;
	    }
    	else 
    	{
    		$("#mensaje_imagen_activos_fijos").fadeOut("slow"); //Muestra mensaje de error
            
		}
    	
    	


    	
	}); 


        $( "#id_oficina" ).focus(function() {
		  $("#mensaje_id_oficina").fadeOut("slow");
	    });

        $( "#id_tipo_activos_fijos" ).focus(function() {
			  $("#mensaje_id_tipo_activos_fijos").fadeOut("slow");
		});

        $( "#id_departamento" ).focus(function() {
			  $("#mensaje_id_departamento").fadeOut("slow");
		});
		
        $( "#id_estado" ).focus(function() {
			  $("#mensaje_id_estado").fadeOut("slow");
		});
        $( "#fecha_activos_fijos" ).focus(function() {
			  $("#mensaje_fecha_activos_fijos").fadeOut("slow");
		});
        
        $( "#responsable_activos_fijos" ).focus(function() {
			  $("#mensaje_codigo_activos_fijos").fadeOut("slow");
		});
        $( "#nombre_activos_fijos" ).focus(function() {
			  $("#mensaje_nombre_activos_fijos").fadeOut("slow");
		});
        $( "#valor_activos_fijos" ).focus(function() {
			  $("#mensaje_valor_activos_fijos").fadeOut("slow");
		});
        $( "#imagen_activos_fijos" ).focus(function() {
			  $("#mensaje_imagen_activos_fijos").fadeOut("slow");
		});
    
        
	        	      
		    
}); 

$("#activos_fijos_registrados").on("click",".editaActivo",function(event){
	
	var tiempo = tiempo || 1000;
	
	var parametros = {
			peticion : "ajax",
			activoId : $(this).attr("id")
	}
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ActivosFijos&action=editActivo",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.value == 1){
			
			var array = datos.data[0]
			
			$("#id_activos_fijos").val(array.id_activos_fijos)
			$("#id_oficina").val(array.id_oficina)
			$("#id_tipo_activos_fijos").val(array.id_tipo_activos_fijos)
			$("#id_departamento").val(array.id_departamento)
			$("#id_estado").val(array.id_estado)
			$("#fecha_activos_fijos").val(array.fecha_activos_fijos)
			$("#id_empleados").val(array.id_empleados)
			$("#nombre_activos_fijos").val(array.nombre_activos_fijos)
			$("#valor_activos_fijos").val(array.valor_activos_fijos)
			$("#detalle_activos_fijos").val(array.detalle_activos_fijos)
			
			$("html, body").animate({ scrollTop: $(id_oficina).offset().top-150 }, tiempo);
			
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		alert(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		consultaActivos();
	})
	event.preventDefault()
})


$("#id_departamento").change(function(){
	            // obtenemos el combo de resultado combo 2
	           var $id_empleados_vivienda = $("#id_empleados");
	       	
	            // lo vaciamos
	           var id_departamento_vivienda = $(this).val();
	          
	          
	            if(id_departamento_vivienda != 0)
	            {
		            
	            	$id_empleados_vivienda.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_departamento_vivienda:$(this).val()
	                  };

	            	 $.ajax({
	 	                    beforeSend: function(objeto){
	 	                      /*buscar una funcion de cargando*/
	 	                    },
	 	                    url: 'index.php?controller=ActivosFijos&action=devuelveEmpleado',
	 	                    type: 'POST',
	 	                    data: datos,
	 	                    success: function(resultado){
	 	                    	try {
	 	                    		resultado = resultado.replace('<', "");
		 	                    	resultado = resultado.replace(/\\n/g, "\\n")  
		 	                       .replace(/\\'/g, "\\'")
		 	                       .replace(/\\"/g, '\\"')
		 	                       .replace(/\\&/g, "\\&")
		 	                       .replace(/\\r/g, "\\r")
		 	                       .replace(/\\t/g, "\\t")
		 	                       .replace(/\\b/g, "\\b")
		 	                       .replace(/\\f/g, "\\f");
	                	 	        // remove non-printable and other non-valid JSON chars
	                	 	        resultado = resultado.replace(/[\u0000-\u0019]+/g,"");
	                	 	        
	                                objeto = JSON.parse(resultado);

	                               
	                                if(objeto.length==0)
	         	          		   {
	                                	$id_empleados_vivienda.append("<option value='0' >--Seleccione--</option>");	
	         	             	   }else{
	         	             		 $id_empleados_vivienda.append("<option value='0' >--Seleccione--</option>");
	         	          		 		$.each(objeto, function(index, value) {
	         	          		 		$id_empleados_vivienda.append("<option value= " +value.id_empleados +" >" + value.nombres_empleados  + "</option>");	
	         	                     		 });
	         	             	   }	

	                                
	                            }
	                            catch (error) {
	                                if(error instanceof SyntaxError) {
	                                    let mensaje = error.message;
	                                    console.log('ERROR EN LA SINTAXIS:', mensaje);
	                                } else {
	                                    throw error; // si es otro error, que lo siga lanzando
	                                }
	                            }
	 	                   },
		                   error: function(jqXHR,estado,error){
		                    /*alertar error*/
		                   }
		                 });

	         	  
	         		  
	            }else{
	            	console.log('ERROR EN LA SINTAXIS:', mensaje);
	            	
	            }
	            
			});


$("#frm_activos_fijos").on("submit",function(event){
	
	var parametros = new FormData(this)
	
	parametros.append('action','ajax')
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ActivosFijos&action=insActivos",
		type:"POST",
		dataType:"json",
		contentType: false,
        processData: false,
		data:parametros
	}).done(function(datos){
		
		if(datos.valor == 1){
			
			swal({
      		  title: "Activos Fijos",
      		  text: datos.mensaje,
      		  icon: "success",
      		  button: "Aceptar",
      		});
			
		}
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		alert('Error al insertar Activo')
		swal({
    		  title: "Activos Fijos",
    		  text: "Error al insertar Activo",
    		  icon: "error",
    		  button: "Aceptar",
    		});
		console.log(err)
	}).always(function(){
		$("#divLoaderPage").removeClass("loader")
		document.getElementById("frm_activos_fijos").reset();
		consultaActivos();
	})
	
	event.preventDefault();
})


