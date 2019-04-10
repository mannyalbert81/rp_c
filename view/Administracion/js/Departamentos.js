var ideditdpto = 0; 

$(document).ready( function (){
	load_departamentos();	
});

function load_departamentos(){

	   var search=$("#search").val();
	   var idestado=$("#estado_dpto").val();
	  
var con_datos={
				  action:'ajax'
				  };
		  
$("#load_dptos").fadeIn('slow');

$.ajax({
       beforeSend: function(objeto){
         $("#load_dptos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
       },
       url: 'index.php?controller=Departamentos&action=consulta_departamentos&search='+search+'&id_estado='+idestado,
       type: 'POST',
       data: con_datos,
       success: function(x){
         $("#dptos_registrados").html(x);
         $("#load_dptos").html(""); 
         
       },
      error: function(jqXHR,estado,error){
        $("#dptos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
      }
    });
}

function AgregarDpto()
{
	var modal = $('#myModal');
	var dpto = modal.find('#nuevo_dpto').val();
	if (dpto!="")
	{
    
	$.ajax({
	    url: 'index.php?controller=Departamentos&action=AgregarDpto',
	    type: 'POST',
	    data: {
	    		nombre_dpto: dpto
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x.includes("Warning") || x.includes("Notice"))
			{
			
			if(x.includes("sin encontrar RETURN"))
			{
			swal({
		  		  title: "Departamento",
		  		  text: "Departamento ya existe",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}else
				{
				swal({
			  		  title: "Departamento",
			  		  text: "Error al agregar departamento",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}
			}
		else
			{
			modal.modal('hide');
			if (x==1)
				{
				swal({
			  		  title: "Departamento",
			  		  text: "Departamento creado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
				load_departamentos(1);
				SelecDpto();
				}	
			}
	})
	.fail(function() {
	    console.log("error");
	    swal({
	    	title: "Departamento",
	  		  text: "Error al agregar departamento",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	else
	{
		if(dpto=="")
			{
			$("#mensaje_agregar_dpto").text("Ingrese nombre del departamento");
			$("#mensaje_agregar_dpto").fadeIn("slow");
			$("#mensaje_agregar_dpto").fadeOut("slow");
			}
		
	}
	modal.find('#nuevo_dpto').val("");
}

function SelecDpto()
{
	var modal = $('#myModalElim');
	$.ajax({
	    url: 'index.php?controller=Departamentos&action=GetDptos',
	    type: 'POST',
	    data: {   
	    },
	})
	.done(function(x) {
			var grupos = JSON.parse(x);
			
		
			$('#dpto_empleados').empty().append('<option value="" selected="selected">--Seleccione--</option>');
			for (var i = 0 ; i<grupos.length ; i++)
				{
				var opt = "<option value=\"";
					opt += grupos[i]["id_departamento"];
					opt += "\" >" + grupos[i]["nombre_departamento"]+"</option>";
					$('#dpto_empleados').append(opt);
								
				}
			modal.find('#eliminar_dpto_empleados').empty().append('<option value="" selected="selected">--Seleccione--</option>');
			
			for (var i = 0 ; i<grupos.length ; i++)
			{
				opt="<option value=\""+grupos[i]["id_departamento"]+"\">";
				opt +=grupos[i]["nombre_departamento"]+"</option>";
				modal.find('#eliminar_dpto_empleados').append(opt);
			}
			})
	.fail(function() {
	    console.log("error");
	    
	});
	
}

function EliminarDpto()
{
	var modal = $('#myModalElim');
	var iddpto = modal.find('#eliminar_dpto_empleados').val();
	if (iddpto!="" )
	{
    
	$.ajax({
	    url: 'index.php?controller=Departamentos&action=EliminarDpto',
	    type: 'POST',
	    data: {
	    	   id_departamento: iddpto
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			modal.modal('hide');
					swal({
			  		  title: "Departamento",
			  		  text: "Departamento eliminado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					SelecDpto();
					load_departamentos(1);
					
			}
		else
			{
			swal({
		  		  title: "Departamento",
			  		  text: "Error al eliminar departamento",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Departamento",
	  		  text: "Error al eliminar departamento",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	else
	{
		$("#mensaje_eliminar_dpto_horarios").text("Seleccione nombre del departamento");
		$("#mensaje_eliminar_dpto_horarios").fadeIn("slow");
		$("#mensaje_eliminar_dpto_horarios").fadeOut("slow");
	}
	modal.find('#nuevo_grupo').val("");
}


function EditarDpto(nombdpto)
{
	
	if (typeof nombdpto !== 'undefined')
		{
		var modal = $('#myModalEdit');
		modal.find('#nuevo_dpto').val("");
		modal.find('#antiguo_dpto').val(nombdpto);
		}
	else
		{
		var modal = $('#myModalEdit');
		var antnom = modal.find('#antiguo_dpto').val();
		var nuevonom = modal.find('#nuevo_dpto').val();
		if (nuevonom != "")
			{
			console.log("cambiar nombre "+antnom+"->"+nuevonom );
			$.ajax({
			    url: 'index.php?controller=Departamentos&action=EditarDpto',
			    type: 'POST',
			    data: {
			    	 nombre_departamento: antnom,
			         nuevo_nombre:nuevonom
			    },
			})
			.done(function(x) {
				console.log(x);
				if (x==1)
					{
					modal.modal('hide');
							swal({
					  		  title: "Departamento",
					  		  text: "Nombre del departamento editado",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
							SelecDpto();
							load_departamentos(1);
							
							
					}
				else
					{
					if (x==0)
						{
						swal({
					  		  title: "Departamento",
						  		  text: "El nombre del departamento ya esta registrado",
						  		  icon: "warning",
						  		  button: "Aceptar",
						  		});
						}
					else
						{
						swal({
					  		  title: "Departamento",
						  		  text: "Error al editar departamento",
						  		  icon: "warning",
						  		  button: "Aceptar",
						  		});
						}
					
					}
				
			})
			.fail(function() {
			    console.log("error");
			    swal({
			  		  title: "Departamento",
			  		  text: "Error al eliminar departamento",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			});
			}
		else
			{
			modal.find("#mensaje_agregar_dpto").text("Introduzca nombre del departamento");
			modal.find("#mensaje_agregar_dpto").fadeIn("slow");
			modal.find("#mensaje_agregar_dpto").fadeOut("slow");
			}
		}
	}

function InsertarCargo()
{
	var dpto = $("#dpto_empleados").val();
	var cargo = $("#cargo_empleados").val();
	var salario = $("#salario_empleados").val();
	var estado = $("#estado_cargo").val();
	
	if(dpto=="")
		{
		$("#mensaje_dpto_empleados").text("Seleccione el departamento");
		$("#mensaje_dpto_empleados").fadeIn("slow");
		$("#mensaje_dpto_empleados").fadeOut("slow");
		}
	if(cargo=="")
	{
	$("#mensaje_cargo_empleados").text("Introduzca nombre del cargo");
	$("#mensaje_cargo_empleados").fadeIn("slow");
	$("#mensaje_cargo_empleados").fadeOut("slow");
	}
	if(salario=="")
	{
	$("#mensaje_salario_empleados").text("Introduzca el salario");
	$("#mensaje_salario_empleados").fadeIn("slow");
	$("#mensaje_salario_empleados").fadeOut("slow");
	}
	if(estado=="")
	{
	$("#mensaje_estado_cargo").text("Seleccione el estado");
	$("#mensaje_estado_cargo").fadeIn("slow");
	$("#mensaje_estado_cargo").fadeOut("slow");
	}
	
	if (dpto!="" && cargo!="" && salario!="" && estado!="")
		{
		$.ajax({
		    url: 'index.php?controller=Departamentos&action=AgregarCargo',
		    type: 'POST',
		    data: {
		    		nombre_cargo: cargo,
		    		salario_cargo: salario,
		    		id_departamento: dpto,
		    		id_estado: estado
		    },
		})
		.done(function(x) {
			console.log(x);
			if (x.includes("Warning") || x.includes("Notice"))
				{
				
				if(x.includes("sin encontrar RETURN"))
				{
				swal({
			  		  title: "Cargo",
			  		  text: "Cargo ya existe",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}else
					{
					swal({
				  		  title: "Cargo",
				  		  text: "Error al agregar cargo",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
					}
				}
			else
				{
				if (x==1)
					{
					swal({
				  		  title: "Cargo",
				  		  text: "Cargo añadido",
				  		  icon: "success",
				  		  button: "Aceptar",
				  		});
					load_departamentos();
					$("#dpto_empleados").val("");
					$("#cargo_empleados").val("");
					$("#salario_empleados").val("");
					$("#estado_cargo").val("");
					}	
				}
		})
		.fail(function() {
		    console.log("error");
		    swal({
		    	title: "Cargo",
		  		  text: "Error al añadir cargo",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		});
		}
}

function EditarCargo(ncargo,salario,iddpto,idestado)
{
	console.log(ncargo+" "+salario+" "+iddpto+" "+idestado);
	$("#dpto_empleados").val(iddpto);
	$("#cargo_empleados").val(ncargo);
	$("#salario_empleados").val(salario);
	$("#estado_cargo").val(idestado);
	
	$('html, body').animate({ scrollTop: 0 }, 'fast')
}

function LimpiarCampos()
{
	$("#dpto_empleados").val("");
	$("#cargo_empleados").val("");
	$("#salario_empleados").val("");
	$("#estado_cargo").val("");
}

function EliminarCargo(idcargo)
{
	
	 $.ajax({
	    url: 'index.php?controller=Departamentos&action=EliminarCargo',
	    type: 'POST',
	    data: {
	    	   id_cargo: idcargo
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			swal({
			  		  title: "Cargo",
			  		  text: "Cargo eliminado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					load_departamentos(1);
					
			}
		else
			{
			swal({
		  		  title: "Cargo",
			  		  text: "Error al eliminar cargo",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Cargo",
	  		  text: "Error al eliminar cargo",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
}

function RestaurarDpto(iddpto)
{
	$.ajax({
	    url: 'index.php?controller=Departamentos&action=RestaurarDpto',
	    type: 'POST',
	    data: {
	    	   id_departamento: iddpto
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			swal({
			  		  title: "Departamento",
			  		  text: "Departamento restaurado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					load_departamentos(1);
					
			}
		else
			{
			
				swal({
			  		  title: "Departameto",
				  		  text: "Error al restaurar departamento",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
				
		
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Departamento",
	  		  text: "Error al restaurar departamento",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});	
}

function RestaurarCargo(idcargo, iddpto)
{

	 $.ajax({
	    url: 'index.php?controller=Departamentos&action=RestaurarCargo',
	    type: 'POST',
	    data: {
	    	   id_cargo: idcargo,
	    	   id_departamento: iddpto
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			swal({
			  		  title: "Cargo",
			  		  text: "Cargo restaurado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					load_departamentos(1);
					
			}
		else
			{
			if (x==0)
				{
				swal({
			  		  title: "Cargo",
				  		  text: "El departamento del cargo no existe",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
				}
			else
				{
				swal({
			  		  title: "Cargo",
				  		  text: "Error al restaurar cargo",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
				}
		
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Cargo",
	  		  text: "Error al restaurar cargo",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
}