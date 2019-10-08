$(document).ready( function (){
	load_solicitudes(1);
	$(":input").inputmask();
	getUsuario();
	var h1ctr;
	var h2ctr;
	sethoras();

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
				  page:pagina,
				  };
		  
$("#load_solicitudes").fadeIn('slow');

$.ajax({
          beforeSend: function(objeto){
            $("#load_solicitudes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
          },
          url: 'index.php?controller=HorasExtrasEmpleados&action=consulta_solicitudes&search='+search+'&id_estado='+idestado,
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

function sethoras()
{
	$.ajax({
	    url: 'index.php?controller=PermisosEmpleados&action=GetHoras',
	    type: 'POST',
	    data: {
	    	   
	    },
	})
	.done(function(x) {
		
		var res = $.parseJSON(x);
		h1ctr=res[0]['hora_entrada_empleados'].split(":");
		h2ctr=res[0]['hora_salida_empleados'].split(":");
		})
	.fail(function() {
	    console.log("error");
	    	
	});	
}

function validardesde()
{
	var hdesde = $("#hora_desde").val();
	var h1 = hdesde.split(":");
	var date1 = new Date(2000, 0, 1,  h1[0], h1[1]);
	var datectr1 = new Date(2000, 0, 1,  h1ctr[0], h1ctr[1]);
	var datectr2 = new Date(2000, 0, 1, h2ctr[0], h2ctr[1]);
	var diffent = date1-datectr1;
	console.log(diffent);
	if (diffent < 0) return false;
	else
		{
		diffent = datectr2 - date1;
		if (diffent < 0)
			{
			return false;
			}
		return true;
		}
}

function validarhasta()
{
	var hdesde = $("#hora_hasta").val();
	var h1 = hdesde.split(":");
	var date1 = new Date(2000, 0, 1,  h1[0], h1[1]);
	var datectr1 = new Date(2000, 0, 1,  h1ctr[0], h1ctr[1]);
	var datectr2 = new Date(2000, 0, 1, h2ctr[0], h2ctr[1]);
	var diffent = date1-datectr1;
	console.log(diffent);
	if (diffent < 0) return false;
	else
		{
		diffent = datectr2 - date1;
		if (diffent < 0)
			{
			return false;
			}
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
			document.getElementById('hora_desde').readOnly = true;
			document.getElementById('hora_hasta').readOnly = true;
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
     document.getElementById('hora_desde').readOnly = false;
		document.getElementById('hora_hasta').readOnly = false;
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
	else if (fechael[1]== mes && fechael[2] <= hoy)
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
	

var fecha = $("#fecha").val();
var hora_fin = $("#hora_salida").val();
var hora_inicio = $("#hora_inicio").val();

console.log(fecha + " fecha");

if (fecha=="")
{
$("#mensaje_fecha").text("Elija fecha");
$("#mensaje_fecha").fadeIn("slow");
$("#mensaje_fecha").fadeOut("slow");
}
if (hora_fin== "" || hora_fin.includes("_"))
{    	
	$("#mensaje_hora_salida").text("Ingrese hora");
	$("#mensaje_hora_salida").fadeIn("slow");
	$("#mensaje_hora_salida").fadeOut("slow");
}
if (hora_inicio== "" || hora_inicio.includes("_"))
{    	
	$("#mensaje_hora_inicio").text("Ingrese hora");
	$("#mensaje_hora_inicio").fadeIn("slow");
	$("#mensaje_hora_inicio").fadeOut("slow");
}

if ( fecha!="" && hora_fin!="" && !hora_inicio.includes("_") && hora_fin!="" && !hora_inicio.includes("_"))
	{
	
	$.ajax({
	    url: 'index.php?controller=HorasExtrasEmpleados&action=AgregarSolicitud',
	    type: 'POST',
	    data: {
	    	   fecha_solicitud: fecha,
	    	   hora_inicio_solicitud: hora_inicio,
	    	   hora_fin_solicitud:hora_fin
	    	   
	    },
	})
	.done(function(x) {
		
		$("#fecha").val("");
		$("#hora_salida").val("");
		
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
	
	
	
	$("#fecha").val("");
	$("#hora_salida").val("");
	
	
}

function Aprobar(idsol,nomest)
{
	
	var url="";
	var msg="";
	if (nomest == "EN REVISION") 
		{
		url = 'index.php?controller=HorasExtrasEmpleados&action=VBSolicitud';
		msg = 'Estado de solicitud cambiado a visto bueno';
		}
	if (nomest == "VISTO BUENO") 
	{
		url = 'index.php?controller=HorasExtrasEmpleados&action=AprobarSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado';
	}
	if (nomest == "APROBADO")
	{
		url = 'index.php?controller=HorasExtrasEmpleados&action=GerenciaSolicitud';
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
	    url: 'index.php?controller=HorasExtrasEmpleados&action=NegarSolicitud',
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