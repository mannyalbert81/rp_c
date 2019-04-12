$(document).ready( function (){
	load_solicitudes(1);
	$(":input").inputmask();
	getUsuario();
	
	

});



function getUsuario()
{
	$.ajax({
		url:'index.php?controller=PermisosEmpleados&action=getUsuario',
		type:'POST',
		dataType:'json',
		data:{}
	}).done(function(respuesta){
		if(JSON.stringify(respuesta)!='{}'){
			
			$('#nombre_empleados').val(respuesta.nombre_empleados);
			$('#dpto_empleados').val(respuesta.dpto_empleados);
			$('#cargo_empleados').val(respuesta.cargo_empleados);
		}
		
	}).fail( function( xhr , status, error ){
		 var err=xhr.responseText
		console.log(err)
	});
}

function load_solicitudes(pagina){

	   var search=$("#search").val();
	   var idestado=$("#estado_solicitudes").val();
  var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
$("#load_solicitudes").fadeIn('slow');

$.ajax({
          beforeSend: function(objeto){
            $("#load_solicitudes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
          },
          url: 'index.php?controller=PermisosEmpleados&action=consulta_solicitudes&search='+search+'&id_estado='+idestado,
          type: 'POST',
          data: con_datos,
          success: function(x){
            $("#solicitudes_registrados").html(x);
            $("#load_solicitudes").html("");
            $("#tabla_solicitudes").tablesorter(); 
            
          },
         error: function(jqXHR,estado,error){
           $("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
         }
       });


	   }

function validarhora()
{
	var hdesde = $("#hora_desde").val();
	var hhasta = $("#hora_hasta").val();
	var h1 = hdesde.split(":");
	var h2 = hhasta.split(":");
	var date1 = new Date(2000, 0, 1,  h1[0], h1[1]);
	var date2 = new Date(2000, 0, 1, h2[0], h2[1]);
    var diff = date2-date1;
    if(diff <=0)
    	{
    	return false;
    	}
    else
    	{
    	return true;
    	}
}

function TodoElDia()
{

 if (document.getElementById('dia').className == "btn btn-light")
	 {
	 document.getElementById('dia').className = "btn btn-primary";
	 document.getElementById('diaicon').className = "glyphicon glyphicon-check";
	 $.ajax({
		    url: 'index.php?controller=PermisosEmpleados&action=GetHoras',
		    type: 'POST',
		    data: {
		    	   
		    },
		})
		.done(function(x) {
			
			var res = $.parseJSON(x);
			console.log(res);
			$("#hora_desde").val(res[0]['hora_entrada_empleados']);
			$("#hora_hasta").val(res[0]['hora_salida_empleados']);
			
			})
		.fail(function() {
		    console.log("error");
		    	
		});
	 
	 }
 else
	 {
	 document.getElementById('dia').className = "btn btn-light";
	 document.getElementById('diaicon').className = "glyphicon glyphicon-unchecked";
	 $("#hora_desde").val("");
     $("#hora_hasta").val("");
	 }
 
}

function validarfecha(fecha)
{
	var hoy = new Date().getDate();
	var year = new Date().getFullYear();
	var mes = new Date().getMonth()+1;
	var fechael = fecha.split("-");
	if(fechael[0] < year)
		{
		return false;
		}
	else if (fechael[1] < mes)
		{
		return false;
		}
	else if (fechael[2] <= hoy)
	{
		return false;
	}
	else
		{
		return true;
		}
}



function InsertarSolicitud()
{
var fecha = $("#fecha_permiso").val();
var desde = $("#hora_desde").val();
var hasta = $("#hora_hasta").val();
var causa = $("#causa_permiso").val();
var desc = $("#descripcion_causa").val();

if (!validarhora())
	{
	$("#mensaje_hora_desde").text("Hora invalida");
	$("#mensaje_hora_desde").fadeIn("slow");
	$("#mensaje_hora_desde").fadeOut("slow");
	$("#mensaje_hora_hasta").text("Hora invalida");
	$("#mensaje_hora_hasta").fadeIn("slow");
	$("#mensaje_hora_hasta").fadeOut("slow");
	}

if((causa == 6 || causa == 3) && desc == "" )
	{
	$("#mensaje_descripcion_causa").text("Escriba una descripción");
	$("#mensaje_descripcion_causa").fadeIn("slow");
	$("#mensaje_descripcion_causa").fadeOut("slow");
	}

if (desde== "" || desde.includes("_"))
{    	
	$("#mensaje_hora_desde").text("Introduzca hora");
	$("#mensaje_hora_desde").fadeIn("slow");
	$("#mensaje_hora_desde").fadeOut("slow");
}
if (hasta== "" || hasta.includes("_"))
{    	
	$("#mensaje_hora_hasta").text("Introduzca hora");
	$("#mensaje_hora_hasta").fadeIn("slow");
	$("#mensaje_hora_hasta").fadeOut("slow");
}

if (causa== "")
{    	
	$("#mensaje_causa_permiso").text("Seleccione causa");
	$("#mensaje_causa_permiso").fadeIn("slow");
	$("#mensaje_causa_permiso").fadeOut("slow");
}

if (fecha== "" || !validarfecha(fecha))
{    	
	$("#mensaje_fecha_permiso").text("Seleccione fecha");
	$("#mensaje_fecha_permiso").fadeIn("slow");
	$("#mensaje_fecha_permiso").fadeOut("slow");
}

if ( desde!="" && hasta!="" && causa!="" && fecha!="" && !desde.includes("_") && !hasta.includes("_") && validarfecha(fecha) && validarhora())
	{
	
	$.ajax({
	    url: 'index.php?controller=PermisosEmpleados&action=AgregarSolicitud',
	    type: 'POST',
	    data: {
	    	   fecha_solicitud: fecha,
	    	   hora_desde: desde,
	    	   hora_hasta: hasta,
	    	   id_causa: causa,
	    	   descripcion_causa:desc
	    },
	})
	.done(function(x) {
		$("#fecha_permiso").val("");
		$("#hora_desde").val("");
		$("#hora_hasta").val("");
		$("#causa_permiso").val("");
		$("#descripcion_causa").val("");
		console.log(x);
		if (x==1)
			{
			swal({
		  		  title: "Solicitud",
		  		  text: "Solicitud registrada exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
			}
		else
			{
			if (x.includes("Warning"))
			{
			if(x.includes("sin encontrar RETURN"))
				{
				swal({
			  		  title: "Solicitud",
			  		  text: "Ya existe una solicitud para el día indicado",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}else
					{
					swal({
				  		  title: "Solicitud",
				  		  text: "Error al agregar solicitud",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
					}
			
			}
			swal({
		  		  title: "Solicitud",
		  		  text: "Hubo un error al registrar solicitud",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
				
			}
		
		
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Solicitud",
	  		  text: "Hubo un error al registrar solicitud",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	
}

function HabilitarDescripcion()
{
	var causa = $("#causa_permiso").val();
	if (causa != 6 && causa != 3) document.getElementById('descripcion_causa').readOnly = true;
	else document.getElementById('descripcion_causa').readOnly = false;
}

function LimpiarCampos()
{
	$("#cedula_empleado").val("");
	$("#nombre_empleados").val("");
	$("#dpto_empleados").val("");
	$("#fecha_permiso").val("");
	$("#hora_desde").val("");
	$("#hora_hasta").val("");
	$("#causa_permiso").val("");
	$("#descripcion_causa").val("");
	document.getElementById('descripcion_causa').readOnly = true;
	SelecCargo("");
}

function EditarEmpleado(cedula, nombres, cargo, dpto, idgrupo, idestado, idofic)
{
var res = nombres.split(" ")
var nombre = res[0]+" "+res[1];
var apellido = res[2]+" "+res[3];
$("#cedula_empleado").val(cedula);
$("#nombre_empleados").val(nombre);
$("#apellido_empleados").val(apellido);
$("#dpto_empleados").val(dpto);
$("#estado_empleados_reg").val(idestado);
$("#oficina_empleados").val(idofic);
SelecGrupo(idgrupo);
SelecCargo(cargo);
$('html, body').animate({ scrollTop: 0 }, 'fast');
}
