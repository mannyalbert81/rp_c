$(document).ready( function (){
	load_solicitudes(1);
	$(":input").inputmask();
	getUsuario();

});



function getUsuario()
{
	$.ajax({
		url:'index.php?controller=VacacionesEmpleados&action=getUsuario',
		type:'POST',
		dataType:'json',
		data:{}
	}).done(function(respuesta){
		console.log(respuesta);
		if(JSON.stringify(respuesta)!='{}'){
			
			$('#nombre_empleados').val(respuesta.nombre_empleados);
			$('#dpto_empleados').val(respuesta.dpto_empleados);
			$('#cargo_empleados').val(respuesta.cargo_empleados);
			$('#dias_vacaciones').val(respuesta.dias_vacaciones_empleados);
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
				  page:pagina,
				  };
		  
$("#load_solicitudes").fadeIn('slow');

$.ajax({
          beforeSend: function(objeto){
            $("#load_solicitudes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
          },
          url: 'index.php?controller=VacacionesEmpleados&action=consulta_solicitudes&search='+search+'&id_estado='+idestado,
          type: 'POST',
          data: con_datos,
          success: function(x){
        	  
        	  if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
    		  {
    		  swal({
		  		  title: "Solicitudes",
		  		  text: "El usuario no es empleado registrado",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
    		  $("#load_solicitudes").html('');
    		  }else
    			  {
    			  $("#solicitudes_registrados").html(x);
    	            $("#load_solicitudes").html("");
    	            $("#tabla_solicitudes").tablesorter(); 
    			  }
           
            
          },
         error: function(jqXHR,estado,error){
           $("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
         }
       });


}

function validarfechas(fecha1,fecha2)
{
	var hoy = new Date().getDate();
	var year = new Date().getFullYear();
	var mes = new Date().getMonth()+1;
	var fechael1 = fecha1.split("-");
	var date1  = new Date(fechael1[0], fechael1[1]-1, fechael1[2]);
	var fechael2 = fecha2.split("-");
	var date2  = new Date(fechael2[0], fechael2[1]-1, fechael2[2]);
	if(fechael1[0] < year)
		{
		return false; 
		}
	else if (fechael1[1] < mes)
		{
		return false;
		}
	else if (fechael1[1]== mes && fechael1[2] <= hoy)
	{
		return false;
	}
	else
		{
		if(fechael2[0] < year)
		{
		return false; 
		}
	else if (fechael2[1] < mes)
		{
		return false;
		}
	else if (fechael2[1]== mes && fechael2[2] <= hoy)
	{
		return false;
	}
	else
		{
		var fecha1ms=date1.getTime();
		var fecha2ms=date2.getTime();
		var diff = fecha2ms-fecha1ms;
		
		if (diff<0)
			{
			return false; 
			}
		else
			{

			return true;
			}
		}
	}
}



function InsertarSolicitud()
{
	
var desde = $("#fecha_desde").val();
var hasta = $("#fecha_hasta").val();

if (hasta=="")
{
$("#mensaje_fecha_hasta").text("Elija fecha");
$("#mensaje_fecha_hasta").fadeIn("slow");
$("#mensaje_fecha_hasta").fadeOut("slow");
}
if (desde== "")
{    	
	$("#mensaje_fecha_desde").text("Elija fecha");
	$("#mensaje_fecha_desde").fadeIn("slow");
	$("#mensaje_fecha_desde").fadeOut("slow");
}
if (!validarfechas(desde, hasta))
	{
	$("#mensaje_fecha_hasta").text("Fecha incorrecta");
	$("#mensaje_fecha_hasta").fadeIn("slow");
	$("#mensaje_fecha_hasta").fadeOut("slow");
	$("#mensaje_fecha_desde").text("Fecha incorrecta");
	$("#mensaje_fecha_desde").fadeIn("slow");
	$("#mensaje_fecha_desde").fadeOut("slow");
	}


if ( desde!="" && hasta!="" && validarfechas(desde, hasta))
	{
	
	$.ajax({
	    url: 'index.php?controller=VacacionesEmpleados&action=AgregarSolicitud',
	    type: 'POST',
	    data: {
	    	   fecha_desde: desde,
	    	   fecha_hasta: hasta
	    	   
	    },
	})
	.done(function(x) {
		
		$("#fecha_desde").val("");
		$("#fecha_hasta").val("");
	
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

function LimpiarCampos()
{
	
	
	
	$("#fecha_desde").val("");
	$("#fecha_hasta").val("");
	
	
}

function Aprobar(idsol,nomest)
{
	
	var url="";
	var msg="";
	if (nomest == "EN REVISION") 
		{
		url = 'index.php?controller=VacacionesEmpleados&action=VBSolicitud';
		msg = 'Estado de solicitud cambiado a visto bueno';
		}
	if (nomest == "VISTO BUENO") 
	{
		url = 'index.php?controller=VacacionesEmpleados&action=AprobarSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado';
	}
	if (nomest == "APROBADO")
	{
		url = 'index.php?controller=VacacionesEmpleados&action=GerenciaSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado gerencia';
	}
	console.log(url);
	$.ajax({
	    url: url,
	    type: 'POST',
	    data: {
	    	   id_solicitud: idsol
	    },
	})
	.done(function(x) {
		
		console.log(x);
		if (x==1)
			{
			swal({
		  		  title: "Solicitud",
		  		  text: msg,
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
			}
		else
			{
			if (x.includes("Warning"))
			{
				swal({
				  		  title: "Solicitud",
				  		  text: "Error al cambiar estado de solicitud",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
			}
			swal({
		  		  title: "Solicitud",
		  		  text: "Error al cambiar estado de solicitud",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
				
			}
		
		
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Solicitud",
	  		  text: "Error al cambiar estado de solicitud",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
}

function Negar(idsol)
{
	$.ajax({
	    url: 'index.php?controller=VacacionesEmpleados&action=NegarSolicitud',
	    type: 'POST',
	    data: {
	    	   id_solicitud: idsol
	    },
	})
	.done(function(x) {
		
		console.log(x);
		if (x==1)
			{
			swal({
		  		  title: "Solicitud",
		  		  text: "Solicitud negada",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
			}
		else
			{
			if (x.includes("Warning"))
			{
				swal({
				  		  title: "Solicitud",
				  		  text: "Error al cambiar estado de solicitud",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
			}
			swal({
		  		  title: "Solicitud",
		  		  text: "Error al cambiar estado de solicitud",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
				
			}
		
		
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Solicitud",
	  		  text: "Error al cambiar estado de solicitud",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
}