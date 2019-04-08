$(document).ready( function (){
	load_horarios(1);
	$(":input").inputmask();
	
	
});

function load_horarios(pagina){

	   var search=$("#search").val();
	   var idestado=$("#estado_horarios").val();
var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
$("#load_horarios").fadeIn('slow');

$.ajax({
       beforeSend: function(objeto){
         $("#load_horarios").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
       },
       url: 'index.php?controller=Horarios&action=consulta_horarios&search='+search+'&id_estado='+idestado,
       type: 'POST',
       data: con_datos,
       success: function(x){
         $("#horarios").html(x);
         $("#load_horarios").html("");
         $("#tabla_horarios").tablesorter(); 
         
       },
      error: function(jqXHR,estado,error){
        $("#horarios").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
      }
    });
}

function EliminarGrupo()
{
	var modal = $('#myModalElim');
	var idgrupo = modal.find('#eliminar_grupo_empleados').val();
	console.log(idgrupo);
	if (idgrupo!="" )
	{
    
	$.ajax({
	    url: 'index.php?controller=Horarios&action=EliminarGrupo',
	    type: 'POST',
	    data: {
	    	   id_grupo: idgrupo
	    },
	})
	.done(function(x) {
		console.log(x);
		if (x==1)
			{
			modal.modal('hide');
					swal({
			  		  title: "Grupo",
			  		  text: "Grupo eliminado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
					$('#turno_empleados').empty().append('<option value="" selected="selected">Seleccione oficina</option>');
					
			}
		else
			{
			swal({
		  		  title: "Grupo",
			  		  text: "Error al eliminar grupo",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
			
			}
		
		
		
		
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Grupo",
	  		  text: "Error al agregar grupo",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	else
	{
		$("#mensaje_eliminar_grupo_horarios").text("Seleccione nombre del grupo");
		$("#mensaje_eliminar_grupo_horarios").fadeIn("slow");
		$("#mensaje_eliminar_grupo_horarios").fadeOut("slow");
	}
	modal.find('#nuevo_grupo').val("");
}

function SelecGrupo(idgrupo)
{
	var oficina = $("#oficina_horarios_reg").val();
	var modal = $('#myModalElim');
	$.ajax({
	    url: 'index.php?controller=Horarios&action=GetGrupos',
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
			
			modal.find('#eliminar_grupo_empleados').empty().append('<option value="" selected="selected">--Seleccione--</option>');
			
			for (var i = 0 ; i<grupos.length ; i++)
			{
				opt="<option value=\""+grupos[i]["id_grupo_empleados"]+"\">";
				opt +=grupos[i]["nombre_grupo_empleados"]+" - "+ grupos[i]["nombre_oficina"]+"</option>";
				modal.find('#eliminar_grupo_empleados').append(opt);
			}
				
	})
	.fail(function() {
	    console.log("error");
	    
	});
	
}

function AgregarGrupo()
{
	var modal = $('#myModal');
	var grupo = modal.find('#nuevo_grupo').val();
	var idofic = modal.find('#oficina_grupos').val();
	if (grupo!="" && idofic !="")
	{
    
	$.ajax({
	    url: 'index.php?controller=Horarios&action=AgregarGrupo',
	    type: 'POST',
	    data: {
	    	   nombre_grupo: grupo,
	    	   id_oficina: idofic
	    },
	})
	.done(function(x) {
		if (x.includes("Warning"))
			{
			if(x.includes("sin encontrar RETURN"))
				{
				swal({
			  		  title: "Grupo",
			  		  text: "Grupo ya existe",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}else
					{
					swal({
				  		  title: "Grupo",
				  		  text: "Error al agregar grupo",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
					}
			
			}
		else
			{
			modal.modal('hide');
			var arr = JSON.parse(x);
			if (grupo == arr.nombre_grupo_empleados)
				{
				swal({
			  		  title: "Grupo",
			  		  text: "Grupo creado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
				$('#turno_empleados').empty().append('<option value="" selected="selected">Seleccione oficina</option>');
				}
			
			}
		
		
		
		
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Grupo",
	  		  text: "Error al agregar grupo",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	else
	{
		if(grupo=="")
			{
			$("#mensaje_grupo_horarios").text("Ingrese nombre del grupo");
			$("#mensaje_grupo_horarios").fadeIn("slow");
			$("#mensaje_grupo_horarios").fadeOut("slow");
			}
		if(idofic=="")
			{
			$("#mensaje_oficina_ahoraios").text("Seleccione la oficina");
			$("#mensaje_oficina_ahoraios").fadeIn("slow");
			$("#mensaje_oficina_ahoraios").fadeOut("slow");
			}
		
	}
	modal.find('#nuevo_grupo').val("");
	modal.find('#oficina_grupos').val("");
}


function InsertarHorario()
{
  var idgrupo = $("#turno_empleados").val();
  var entrada = $("#hora_entrada").val();
  var salida  = $("#hora_salida").val();
  var salidaal = $("#hora_salida_almuerzo").val();
  var entradaal = $("#hora_entrada_almuerzo").val();
  var idestado = $("#estado_horarios_reg").val();
  var idofic = $("#oficina_horarios_reg").val();
  var gracia = $("#hora_espera").val();
 
  if (idofic== "")
	{    	
		$("#mensaje_oficina_horaios").text("Seleccione Oficina");
		$("#mensaje_oficina_horaios").fadeIn("slow");
		$("#mensaje_oficina_horaios").fadeOut("slow");
 }
  if (gracia== "" || gracia.includes("_"))
	{    	
		$("#mensaje_hora_espera").text("Introduzca tiempo de gracia");
		$("#mensaje_hora_espera").fadeIn("slow");
		$("#mensaje_hora_espera").fadeOut("slow");
   }
  if (idestado== "")
	{    	
		$("#mensaje_estado_horaios").text("Seleccione horario");
		$("#mensaje_estado_horaios").fadeIn("slow");
		$("#mensaje_estado_horaios").fadeOut("slow");
}
  if (entrada== "" || entrada.includes("_"))
	{    	
		$("#mensaje_hora_entrada").text("Introduzca hora");
		$("#mensaje_hora_entrada").fadeIn("slow");
		$("#mensaje_hora_entrada").fadeOut("slow");
  }
  if (salida== "" || salida.includes("_"))
	{    	
		$("#mensaje_hora_salida").text("Introduzca hora");
		$("#mensaje_hora_salida").fadeIn("slow");
		$("#mensaje_hora_salida").fadeOut("slow");
}  
  if (entradaal== "" || entradaal.includes("_"))
	{    	
		$("#mensaje_entrada_almuerzo").text("Introduzca hora");
		$("#mensaje_entrada_almuerzo").fadeIn("slow");
		$("#mensaje_entrada_almuerzo").fadeOut("slow");
}  
  
  if (salidaal== "" || salidaal.includes("_"))
	{    	
		$("#mensaje_salida_almuerzo").text("Introduzca hora");
		$("#mensaje_salida_almuerzo").fadeIn("slow");
		$("#mensaje_salida_almuerzo").fadeOut("slow");
} 
  
  if (idgrupo== "")
	{    	
		$("#mensaje_turno_empleados").text("Seleccione grupo");
		$("#mensaje_turno_empleados").fadeIn("slow");
		$("#mensaje_turno_empleados").fadeOut("slow");
}  
  if (idgrupo!="" && entrada!=""  && salida!="" && salidaal!="" && entradaal!="" && !(salidaal.includes("_"))
		  &&  !(entradaal.includes("_")) && !(salida.includes("_")) && !(entrada.includes("_")) && !(gracia.includes("_")))
	{
	$.ajax({
	    url: 'index.php?controller=Horarios&action=AgregarHorario',
	    type: 'POST',
	    data: {
	    	   id_grupo: idgrupo,
	    	   hora_entrada: entrada,
	    	   hora_salida: salida,
	    	   hora_salida_almuerzo: salidaal,
	    	   hora_entrada_almuerzo: entradaal,
	    	   id_oficina:idofic,
	    	   id_estado_f:idestado,
	    	   tiempo_gracia:gracia
	    },
	})
	.done(function(x) {
		console.log(x);
		if(x==1)
			{
			swal({
		  		  title: "Horario",
		  		  text: "El horario ya esta registrado en otro grupo",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		else
			{
		load_horarios(1);
		swal({
	  		  title: "Horario",
	  		  text: "Horario registrado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
		$("#turno_empleados").val("");
		$("#hora_entrada").val("");
		$("#hora_salida").val("");
		$("#hora_salida_almuerzo").val("");
		$("#hora_entrada_almuerzo").val("");
		$("#estado_horarios_reg").val("");
		$("#hora_espera").val("");
			}
	})
	.fail(function() {
	    console.log("error");
	});
	
	}
	

}
function EditarHorarios(entrada, salidaal, entradaal, salida, idgrupo, idestado, idoficina, gracia)
{

	$("#oficina_horarios_reg").val(idoficina);
	SelecGrupo(idgrupo);
	
	$("#hora_entrada").val(entrada);
	$("#hora_salida").val(salida);
	$("#hora_salida_almuerzo").val(salidaal);
	$("#hora_entrada_almuerzo").val(entradaal);
	$("#estado_horarios_reg").val(idestado);
	$("#hora_espera").val(gracia);
	
	$('html, body').animate({ scrollTop: 0 }, 'fast');
}

function EliminarHorarios(idgrupo)
{
	$.ajax({
	    url: 'index.php?controller=Horarios&action=EliminarHorario',
	    type: 'POST',
	    data: {
	    	   id_grupo: idgrupo
	    },
	})
	.done(function(x) {
		if (x==1)
			{
			swal({
		  		  title: "Horario",
		  		  text: "Horario eliminado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
			load_horarios(1);
			}
		else
			{
			swal({
		  		  title: "Horario",
		  		  text: "Error al eliminar horario",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Horario",
	  		  text: "Error al eliminar horario",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});

}

function LimpiarCampos()
{
	$('#turno_empleados').empty().append('<option value="" selected="selected">Seleccione oficina</option>');
	$("#hora_entrada").val("");
	$("#hora_salida").val("");
	$("#hora_salida_almuerzo").val("");
	$("#hora_entrada_almuerzo").val("");
	$("#estado_horarios_reg").val("");
	$("#oficina_horarios_reg").val("");
	
}