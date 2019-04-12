var ideditdpto = 0; 

$(document).ready( function (){
	load_causas(1);	
});

function load_causas(pagina){

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
       url: 'index.php?controller=CausasPermisos&action=consulta_causas&search='+search+'&id_estado='+idestado,
       type: 'POST',
       data: con_datos,
       success: function(x){
         $("#causas_registradas").html(x);
         $("#load_causas").html("");
         $("#tabla_causas").tablesorter();
         
       },
      error: function(jqXHR,estado,error){
        $("#dptos_registrados").html("Ocurrio un error al cargar la informacion de causas..."+estado+"    "+error);
      }
    });
}

function AgregarCausa()
{
	var causa = $('#causa_permiso').val();
	if (causa!="")
	{
    
	$.ajax({
	    url: 'index.php?controller=CausasPermisos&action=AgregarCausa',
	    type: 'POST',
	    data: {
	    		nombre_causa: causa
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x.includes("Warning") || x.includes("Notice"))
			{
			
			if(x.includes("sin encontrar RETURN"))
			{
			swal({
		  		  title: "Causa",
		  		  text: "Causa ya existe",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}else
				{
				swal({
			  		  title: "Causa",
			  		  text: "Error al agregar causa",
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
			  		  title: "Causa",
			  		  text: "Causa creada",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
				load_causas(1);
				$('#causa_permiso').val("");
	
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
			$("#mensaje_causa_permiso").text("Ingrese nombre de la causa");
			$("#mensaje_causa_permiso").fadeIn("slow");
			$("#mensaje_causa_permiso").fadeOut("slow");

	}
	
}

function EliminarCausa(idcausa)
{
	   
	$.ajax({
	    url: 'index.php?controller=CausasPermisos&action=EliminarCausa',
	    type: 'POST',
	    data: {
	    	   id_causa: idcausa
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
					swal({
			  		  title: "Causa",
			  		  text: "Causa eliminada",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					load_causas(1);
					
			}
		else
			{
			swal({
		  		  title: "Causa",
			  		  text: "Error al eliminar causa",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Causa",
	  		  text: "Error al eliminar causa",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	
}


function EditarCausa(nombcausa)
{
	
	if (typeof nombcausa !== 'undefined')
		{
		var modal = $('#myModalEdit');
		modal.find('#nueva_causa').val("");
		modal.find('#antigua_causa').val(nombcausa);
		}
	else
		{
		var modal = $('#myModalEdit');
		var antnom = modal.find('#antigua_causa').val();
		var nuevonom = modal.find('#nueva_causa').val();
		if (nuevonom != "")
			{
			console.log("cambiar nombre "+antnom+"->"+nuevonom );
			$.ajax({
			    url: 'index.php?controller=CausasPermisos&action=EditarCausa',
			    type: 'POST',
			    data: {
			    	 nombre_causa: antnom,
			         nuevo_nombre:nuevonom
			    },
			})
			.done(function(x) {
				console.log(x);
				if (x==1)
					{
					modal.modal('hide');
							swal({
					  		  title: "Causa",
					  		  text: "Nombre de la causa editado",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
							load_causas(1);
							
							
					}
				else
					{
					if (x==0)
						{
						swal({
					  		  title: "Causa",
						  		  text: "El nombre de la causa ya esta registrado",
						  		  icon: "warning",
						  		  button: "Aceptar",
						  		});
						}
					else
						{
						swal({
					  		  title: "Causa",
						  		  text: "Error al editar causa",
						  		  icon: "warning",
						  		  button: "Aceptar",
						  		});
						}
					
					}
				
			})
			.fail(function() {
			    console.log("error");
			    swal({
			  		  title: "Causa",
				  		  text: "Error al editar causa",
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





function LimpiarCampos()
{
	$("#causa_permiso").val("");
	
}

function RestaurarCausa(idcausa)
{
	$.ajax({
	    url: 'index.php?controller=CausasPermisos&action=RestaurarCausa',
	    type: 'POST',
	    data: {
	    	   id_causa: idcausa
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			swal({
			  		  title: "Causa",
			  		  text: "Causa restaurada",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					load_causas(1);
					
			}
		else
			{
			
				swal({
			  		  title: "Causa",
				  		  text: "Error al restaurar causa",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
				
		
			}
		})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Causa",
	  		  text: "Error al restaurar causa",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});	
}