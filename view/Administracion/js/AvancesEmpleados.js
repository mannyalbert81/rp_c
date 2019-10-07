
$(document).ready( function (){
	load_solicitudes(1);
	$(":input").inputmask();
	getUsuario();
	var h1ctr;
	var h2ctr;

});



function getUsuario()
{
	$.ajax({
		url:'index.php?controller=AvancesEmpleados&action=getUsuario',
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
          url: 'index.php?controller=AvancesEmpleados&action=consulta_solicitudes&search='+search+'&id_estado='+idestado,
          type: 'POST',
          data: con_datos,
          success: function(x){
        	  console.log(x);
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
		return true;
	}
	else
		{
		return true;
		}
}

function GetMax()
{
	$.ajax({
	    url: 'index.php?controller=AvancesEmpleados&action=ValidarMonto',
	    type: 'POST',
	    data: {
	    	  },
	})
	.done(function(x) {
		console.log(x);
		swal({
			  title: "Solicitud",
			  text: "Monto excede la cantidad permitida \n Monto máximo: "+x.trim(),
			  icon: "warning",
			  button: "Aceptar",
			});
		
	})
	.fail(function() {
	    console.log("error");
	   
	});
	}

function InsertarSolicitud()
{

var fecha = $("#fecha").val();
var monto = $("#monto_avance").val();
var diferido = $("#cuotas").val();

if (fecha=="" )
{
$("#mensaje_fecha").text("Elija fecha");
$("#mensaje_fecha").fadeIn("slow");
$("#mensaje_fecha").fadeOut("slow");
}
if (monto== "" || monto.includes("-"))
{    	
	$("#mensaje_monto_avance").text("Ingrese monto");
	$("#mensaje_monto_avance").fadeIn("slow");
	$("#mensaje_monto_avance").fadeOut("slow");
}
if(diferido>12)
	{
	$("#mensaje_cuotas").text("Numero invalido");
	$("#mensaje_cuotas").fadeIn("slow");
	$("#mensaje_cuotas").fadeOut("slow");
	}
if (diferido=="" || diferido=="0")
{
	diferido=1;
}
console.log(diferido);

if ( fecha!="" && monto!="" && diferido!="" && monto>0 && diferido<12)
	{
	
	$.ajax({
	    url: 'index.php?controller=AvancesEmpleados&action=ValidarMonto',
	    type: 'POST',
	    data: {
	    	  },
	})
	.done(function(x) {
		x.trim();
		x=parseFloat(x);
		monto=parseFloat(monto);
		if (monto>x) GetMax();
		else 
		{
			$.ajax({
			    url: 'index.php?controller=AvancesEmpleados&action=ValidarCantidad',
			    type: 'POST',
			    data: {
			    	  },
			})
			.done(function(x) {
				if (x>2)
					{
					swal({
						  title: "Solicitud",
						  text: "El usuario exedio la cantidad de avances anuales",
						  icon: "warning",
						  button: "Aceptar",
						});
					}
				else
					{
					
					$.ajax({
					    url: 'index.php?controller=AvancesEmpleados&action=AgregarSolicitud',
					    type: 'POST',
					    data: {
					    	   fecha_anticipo: fecha,
					    	   monto_anticipo: monto,
					    	   tiempo_diferido: diferido
					    	   
					    },
					})
					.done(function(x) {
						
						$("#fecha").val("");
						$("#monto_avance").val("");
						$("#cuotas").val("");	
						console.log(fecha)
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
							  		  text: "Ya existe una solicitud en proceso de revision o por pagar",
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
				
				
			})
			.fail(function() {
			    console.log("error");
			   
			});	
			
		}
		
	})
	.fail(function() {
	    console.log("error");
	   
	});
	
	
	
	}
}

function LimpiarCampos()
{
	$("#fecha").val("");
	$("#monto_avance").val("");
	$("#cuotas").val("");	
}

function AprobarContador(nomest,url,msg,idsol)
{
	$.ajax({
	    url: 'index.php?controller=AvancesEmpleados&action=ValidarCuentaContable',
	    type: 'POST',
	    data: {
	    	   id_solicitud:idsol
	    	   
	    },
	})
	.done(function(x) {
		
		x=x.trim();
		if(x==1)
			{
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
		else
			{
			swal({
				title: "Advertencia!",
				  text: "El empleado no tiene una cuenta contable registrada",
				  icon: "warning",
				  buttons: {
				    cancel: "Cancelar",
				    aceptar: {
				      text: "Registrar cuenta",
				      value: "aceptar",
				    }
				  },
				})
				.then((value) => {
				  switch (value) {
				 
				    case "aceptar":
				    	window.open('index.php?controller=Empleados&action=index1', '_self');
				      break;
				 
				    default:
				      swal("No se realizaron cambios a la solicitud");
				  }
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

function Aprobar(idsol,nomest)
{
	
	var url="";
	var msg="";
	if (nomest == "EN REVISION") 
		{
		url = 'index.php?controller=AvancesEmpleados&action=VBSolicitud';
		msg = 'Estado de solicitud cambiado a visto bueno';
		}
	if (nomest == "VISTO BUENO") 
	{
		url = 'index.php?controller=AvancesEmpleados&action=AprobarSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado';
		AprobarContador(nomest, url, msg, idsol);
		console.log("contador");
		return 0;
		
		
	}
	if (nomest == "APROBADO")
	{
		url = 'index.php?controller=AvancesEmpleados&action=GerenciaSolicitud';
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
	    url: 'index.php?controller=AvancesEmpleados&action=NegarSolicitud',
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

function Ajustes(id_solicitud)
{
	var modal = $('#myModalAjustes');
	$.ajax({
	    url: 'index.php?controller=AvancesEmpleados&action=TablaCuotas',
	    type: 'POST',
	    data: {
	    	   id_solicitud: id_solicitud
	    },
	})
	.done(function(x) {
		
		console.log(x);
		if (x.includes("Warning") || x.includes("Error") || x.includes("Notice"))
			{
				swal({
				  		  title: "Solicitud",
				  		  text: "Error al cargar la informacion",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
			}
		else 
		{
		
			
			var modal = $('#myModalAjustes');
		modal.find('#preliminar_archivo').html(x);
		$("#myModalAjustes").modal();
		}
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Solicitud",
	  		  text: "Error al cargar la informacion",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
}

function PagarAnticipo(id_solicitud)
{
	swal({
		  title: "Cancelar Pago de anticipo",
		  text: "Se procedera a cancelar el anticipo del empleado",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			  $.ajax({
				    url: 'index.php?controller=AvancesEmpleados&action=PagarAnticipo',
				    type: 'POST',
				    data: {
				    	   id_solicitud: id_solicitud
				    },
				})
				.done(function(x) {
					
					console.log(x);
					if (x!="OK")
						{
							swal({
							  		  title: "Solicitud",
							  		  text: "Error al cargar la informacion",
							  		  icon: "warning",
							  		  button: "Aceptar",
							  		});
						}
					else 
					{
						swal({
					  		  title: "Solicitud",
					  		  text: "Anticipo cancelado",
					  		  icon: "warning",
					  		  button: "Aceptar",
					  		});
					}
						
				})
				.fail(function() {
				});
			  
		    
		  } else {
		    swal("No se realizaron cambios!");
		  }
		});	
}