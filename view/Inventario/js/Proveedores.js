$(document).ready(function(){
	
      $(".cantidades1").inputmask();
      cargaBancos();
      cargaTipoProveedores();
      cargaTipoCuentas();
     
});

/**FUNCIONES PARA INICIO DE PAGINA*/
/*
 * FN PARA CARGA DE TIPO PROVEEDOR
 */
function cargaTipoProveedores(){
	
	let $tipoProveedor = $("#id_tipo_proveedores");
	
	$.ajax({
		url:"index.php?controller=Proveedores&action=cargaTipoProveedores",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		
		$tipoProveedor.empty();
		$tipoProveedor.append("<option value='0' >--Seleccione--</option>");
		
		$.each(x.data, function(index, value) {
			$tipoProveedor.append("<option value= " +value.id_tipo_proveedores +" >" + value.nombre_tipo_proveedores  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoProveedor.empty();
		$tipoProveedor.append("<option value='0' >--Seleccione--</option>");
	})
}

/*
 * FN PARA CARGA DE TIPO PROVEEDOR
 */
function cargaBancos(){
	
	let $bancos = $("#id_bancos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=cargaBancos",
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
		$bancos.append("<option value='0' >--Seleccione--</option>");
	})
}


/*
 * FN PARA CARGA DE TIPO CUENTA
 */
function cargaTipoCuentas(){
	
	let $tipoCuentas = $("#id_tipo_cuentas");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=cargaTipoCuentas",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$tipoCuentas.append("<option value= " +value.id_tipo_cuentas +" >" + value.nombre_tipo_cuentas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
	})
}

function ListaProveedores(){
	
	let $tipoCuentas = $("#id_tipo_cuentas");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=ListaProveedores",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$tipoCuentas.append("<option value= " +value.id_tipo_cuentas +" >" + value.nombre_tipo_cuentas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
	})
}

$("#Guardar").click(function() 
		{
	    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

	    	var nombre_proveedores = $("#nombre_proveedores").val();
	    	var identificacion_proveedores = $("#identificacion_proveedores").val();
	    	var contactos_proveedores = $("#contactos_proveedores").val();
	    	var direccion_proveedores = $("#direccion_proveedores").val();
	    	var telefono_proveedores = $("#telefono_proveedores").val();
	    	var email_proveedores = $("#email_proveedores").val();
	    	var fecha_nacimiento_proveedores = $("#fecha_nacimiento_proveedores").val();
	    	var fecha_actual = new Date();
	    	if (nombre_proveedores == 0)
	    	{
		    	
	    		$("#mensaje_nombre_proveedores").text("Introduzca Un Nombre");
	    		$("#mensaje_nombre_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_nombre_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (identificacion_proveedores == "")
	    	{
		    	
	    		$("#mensaje_identificacion_proveedores").text("Introduzca Un Ruc");
	    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (contactos_proveedores == "")
	    	{
		    	
	    		$("#mensaje_contactos_proveedores").text("Introduzca Un Contacto");
	    		$("#mensaje_contactos_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_contactos_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (direccion_proveedores == "")
	    	{
		    	
	    		$("#mensaje_direccion_proveedores").text("Introduzca Una Dirección");
	    		$("#mensaje_direccion_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_direccion_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   

	    	if (telefono_proveedores == "")
	    	{
		    	
	    		$("#mensaje_telefono_proveedores").text("Introduzca Un teléfono");
	    		$("#mensaje_telefono_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_telefono_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}   
	    	if (email_proveedores == "")
	    	{
		    	
	    		$("#mensaje_email_proveedores").text("Introduzca un correo");
	    		$("#mensaje_email_proveedores").fadeIn("slow"); //Muestra mensaje de error
	    		
	            return false;
		    }
	    	else if (regex.test($('#email_proveedores').val().trim()))
	    	{
	    		$("#mensaje_email_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			}
	    	else 
	    	{
	    		$("#mensaje_email_proveedores").text("Introduzca un correo Valido");
	    		$("#mensaje_email_proveedores").fadeIn("slow"); //Muestra mensaje de error
	    		
		            return false;	
		    }
			

	    	if (fecha_nacimiento_proveedores =="")
	    	{
		    	
	    		$("#mensaje_fecha_nacimiento_proveedores").text("Introduzca una fecha ");
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
		    }
	    	else 
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
			} 
	    	
	    	//var fecha=hoyFecha()
	    	if (fecha_nacimiento_proveedores >= hoyFecha())
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").text("La fecha no debe ser mayor o igual a la actual");
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeIn("slow"); //Muestra mensaje de error
	            return false;
	        }
	    	else 
	    	{
	    		$("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow"); //Muestra mensaje de error
	            
	    	}
	    	
		}); 

$( "#nombre_proveedores" ).focus(function() {
	  $("#mensaje_nombre_proveedores").fadeOut("slow");
  });

  $( "#identificacion_proveedores" ).focus(function() {
		  $("#mensaje_identificacion_proveedores").fadeOut("slow");
	    });
  $( "#contactos_proveedores" ).focus(function() {
		  $("#mensaje_contactos_proveedores").fadeOut("slow");
	    });
  $( "#direccion_proveedores" ).focus(function() {
		  $("#mensaje_direccion_proveedores").fadeOut("slow");
	    });
  $( "#telefono_proveedores" ).focus(function() {
		  $("#mensaje_telefono_proveedores").fadeOut("slow");
	    });
  $( "#email_proveedores" ).focus(function() {
		  $("#mensaje_email_proveedores").fadeOut("slow");
	    });
  $( "#fecha_nacimiento_proveedores" ).focus(function() {
		  $("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow");
	    });
  
  function hoyFecha(){
	    var hoy = new Date();
	        var dd = hoy.getDate();
	        var mm = hoy.getMonth()+1;
	        var yyyy = hoy.getFullYear();
	        
	        if (dd < 10) {
	        	  dd = '0' + dd;
	        	} 
	        	if (mm < 10) {
	        	  mm = '0' + mm;
	        	}
	 
	        return yyyy+'-'+mm+'-'+dd;
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