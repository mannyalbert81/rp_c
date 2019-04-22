$(document).ready( function (){
	load_cuentas(1);
	$(":input").inputmask();
	

});

function load_cuentas(pagina){

	   var search=$("#search").val();
	   var idestado=$("#estado_empleados").val();
  var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
$("#load_cuentas").fadeIn('slow');

$.ajax({
          beforeSend: function(objeto){
            $("#load_empleados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
          },
          url: 'index.php?controller=CuentasEmpleados&action=consulta_cuentas&search='+search+'&id_estado='+idestado,
          type: 'POST',
          data: con_datos,
          success: function(x){
            $("#cuentas_registradas").html(x);
            $("#load_cuentas").html("");
            $("#tabla_cuentas").tablesorter(); 
            
          },
         error: function(jqXHR,estado,error){
           $("#cuentas_registradas").html("Ocurrio un error al cargar la informacion de cuentas..."+estado+"    "+error);
         }
       });


	   }

function SelecGrupo(idgrupo)
{
	var oficina = $("#oficina_empleados").val();
		$.ajax({
	    url: 'index.php?controller=Empleados&action=GetGrupos',
	    type: 'POST',
	    data: {   
	    },
	})
	.done(function(x) {
			var grupos = JSON.parse(x);
			if (oficina=="")
			{
			$('#turno_empleados').empty().append('<option value="" selected="selected">Seleccione oficina</option>');
			}
		else
			{
			$('#turno_empleados').empty().append('<option value="" selected="selected">--Seleccione--</option>');
			for (var i = 0 ; i<grupos.length ; i++)
				{
				var opt = "<option value=\"";
				if (grupos[i]["id_oficina"]==oficina) 
					{
					opt += grupos[i]["id_grupo_empleados"];
					opt += "\" >" + grupos[i]["nombre_grupo_empleados"]+"</option>";
					$('#turno_empleados').append(opt);
					$('#turno_empleados').val(idgrupo);
					}
				}
			}
		
	})
	.fail(function() {
	    console.log("error");
	    
	});
	
}

function SelecCargo(idcargo)
{
	var dpto = $("#dpto_empleados").val();
		$.ajax({
	    url: 'index.php?controller=Empleados&action=GetCargos',
	    type: 'POST',
	    data: {   
	    },
	})
	.done(function(x) {
			var grupos = JSON.parse(x);
			if (dpto=="")
			{
			$('#cargo_empleados').empty().append('<option value="" selected="selected">Seleccione departamento</option>');
			}
		else
			{
			$('#cargo_empleados').empty().append('<option value="" selected="selected">--Seleccione--</option>');
			for (var i = 0 ; i<grupos.length ; i++)
				{
				var opt = "<option value=\"";
				if (grupos[i]["id_departamento"]==dpto) 
					{
					opt += grupos[i]["id_cargo"];
					opt += "\" >" + grupos[i]["nombre_cargo"]+"</option>";
					$('#cargo_empleados').append(opt);
					$('#cargo_empleados').val(idcargo);
					}
				}
			}
		
	})
	.fail(function() {
	    console.log("error");
	    
	});
	
}

function InsertarCuenta()
{
var idemp = $("#empleados").val();
var nombre = $("#banco_empleados").val();
var tipocta = $("#tipo_cuenta_empleados").val();
var numcta = $("#num_cuenta_empleados").val();

console.log(idemp);
console.log(nombre);
console.log(tipocta);
console.log(numcta);

if(idemp=="")
	{
	$("#mensaje_empleados").text("Seleccione empleado");
	$("#mensaje_empleados").fadeIn("slow");
	$("#mensaje_empleados").fadeOut("slow");
	}
if (nombre== "")
{    	
	$("#mensaje_banco_empleados").text("Introduzca nombre del banco");
	$("#mensaje_banco_empleados").fadeIn("slow");
	$("#mensaje_banco_empleados").fadeOut("slow");
}
if (tipocta== "")
{    	
	$("#mensaje_tipo_cuenta_empleados").text("Introduzca tipo cuenta");
	$("#mensaje_tipo_cuenta_empleados").fadeIn("slow");
	$("#mensaje_tipo_cuenta_empleados").fadeOut("slow");
}

if (numcta== "")
{    	
	$("#mensaje_num_cuenta_empleados").text("Introduzca numero de cuenta");
	$("#mensaje_num_cuenta_empleados").fadeIn("slow");
	$("#mensaje_num_cuenta_empleados").fadeOut("slow");
}
if (idemp!="" && tipocta!="" && numcta!="" && nombre!="")
	{
	$.ajax({
	    url: 'index.php?controller=CuentasEmpleados&action=AgregarCuenta',
	    type: 'POST',
	    data: {
	    	   id_empleado: idemp,
	    	   nombre_banco: nombre,
	    	   tipo_cuenta: tipocta,
	    	   numero_cuenta: numcta
	    },
	})
	.done(function(x) {
		console.log(x);
		$("#empleados").val("");
		$("#banco_empleados").val("");
		$("#tipo_cuenta_empleados").val("");
		$("#num_cuenta_empleados").val("");
		if (x==1)
			{
			swal({
		  		  title: "Cuenta Bancaria",
		  		  text: "Cuenta registrada exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_cuentas(1);
			}
		else
			{
			swal({
		  		  title: "Cuenta Bancaria",
		  		  text: "Hubo un error al registrar la cuenta",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
				
			}
		
		
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Cuenta Bancaria",
	  		  text: "Hubo un error al registrar la cuenta",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	
}

function LimpiarCampos()
{
	$("#empleados").val("");
	$("#banco_empleados").val("");
	$("#tipo_cuenta_empleados").val("");
	$("#num_cuenta_empleados").val("");
}	

function EditarCuenta(idemp, nombre, tipocta, numcta)
{
	console.log(idemp);
	console.log(nombre);
	console.log(tipocta);
	console.log(numcta);
	
	$("#empleados").val(idemp);
	$("#banco_empleados").val(nombre);
	$("#tipo_cuenta_empleados").val(tipocta);
	$("#num_cuenta_empleados").val(numcta);
$('html, body').animate({ scrollTop: 0 }, 'fast');
}

function EliminarCuenta(idcta)
{
	$.ajax({
	    url: 'index.php?controller=CuentasEmpleados&action=EliminarValor',
	    type: 'POST',
	    data: {
	    	   id_cuenta: idcta
	    },
	})
	.done(function(x) {
		
		$("#empleados").val("");
		$("#banco_empleados").val("");
		$("#tipo_cuenta_empleados").val("");
		$("#num_cuenta_empleados").val("");
		if(x==1)
			{
			load_cuentas(1);
			swal({
		  		  title: "Cuenta Bancaria",
		  		  text: "Cuenta eliminada",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
			}
		else
			{
			swal({
		  		  title: "Cuenta Bancaria",
		  		  text: "Error al eliminar cuenta",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Cuenta Bancaria",
	  		  text: "Error al eliminar cuenta",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});		
}
