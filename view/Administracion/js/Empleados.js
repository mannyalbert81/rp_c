$(document).ready( function (){
	load_empleados(1);
	$(":input").inputmask();
	

});

$( "#cedula_empleado" ).autocomplete({

	source: "index.php?controller=Empleados&action=AutocompleteCedula",
	minLength: 3,
    select: function (event, ui) {
       // Set selection          
       $('#id_usuarios').val(ui.item.id);
       $('#cedula_empleado').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#cedula_empleado').val();            
        return false; 
    } 
}).focusout(function() {
		if(document.getElementById('cedula_empleado').value != ''){
		$.ajax({
			url:'index.php?controller=Empleados&action=AutocompleteCedula',
			type:'POST',
			dataType:'json',
			data:{term:$('#cedula_empleado').val()}
		}).done(function(respuesta){
			if(JSON.stringify(respuesta)!='{}'){
			
							
				$('#nombre_empleados').val(respuesta.nombre_empleados);
				$('#apellido_empleados').val(respuesta.apellidos_empleados);
				$('#dpto_empleados').val(respuesta.dpto_empleados);
				SelecCargo(respuesta.cargo_empleados);
				$("#oficina_empleados").val(respuesta.id_oficina);
				SelecGrupo(respuesta.id_grupo_empleados);
				$('#estado_empleados_reg').val(respuesta.id_estado);
	
			
			}else{ $("#cedula_usuarios").val("");}
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			console.log(err)
		});
	}
	
});


function load_empleados(pagina){

	   var search=$("#search").val();
	   var idestado=$("#estado_empleados").val();
  var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
$("#load_empleados").fadeIn('slow');

$.ajax({
          beforeSend: function(objeto){
            $("#load_empleados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
          },
          url: 'index.php?controller=Empleados&action=consulta_empleados&search='+search+'&id_estado='+idestado,
          type: 'POST',
          data: con_datos,
          success: function(x){
            $("#empleados_registrados").html(x);
            $("#load_empleados").html("");
            $("#tabla_empleados").tablesorter(); 
            
          },
         error: function(jqXHR,estado,error){
           $("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
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

function M13()
{

 if (document.getElementById('13ro').className == "btn btn-light")
	 {
	 document.getElementById('13ro').className = "btn btn-primary";
	 document.getElementById('13roicon').className = "glyphicon glyphicon-check";
	 }
 else
	 {
	 document.getElementById('13ro').className = "btn btn-light";
	 document.getElementById('13roicon').className = "glyphicon glyphicon-unchecked";
	 }
 
}

function M14()
{

 if (document.getElementById('14to').className == "btn btn-light")
	 {
	 document.getElementById('14to').className = "btn btn-primary";
	 document.getElementById('14toicon').className = "glyphicon glyphicon-check";
	 }
 else
	 {
	 document.getElementById('14to').className = "btn btn-light";
	 document.getElementById('14toicon').className = "glyphicon glyphicon-unchecked";
	 }
 
}
function MFR()
{

 if (document.getElementById('fr').className == "btn btn-light")
	 {
	 document.getElementById('fr').className = "btn btn-primary";
	 document.getElementById('fricon').className = "glyphicon glyphicon-check";
	 }
 else
	 {
	 document.getElementById('fr').className = "btn btn-light";
	 document.getElementById('fricon').className = "glyphicon glyphicon-unchecked";
	 }
 
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

function InsertarEmpleado()
{
var ci = $("#cedula_empleado").val();
var nombre = $("#nombre_empleados").val();
var apellido = $("#apellido_empleados").val();
var cargo = $("#cargo_empleados").val();
var dpto = $("#dpto_empleados").val();
var idgrup = $("#turno_empleados").val();
var estado = $("#estado_empleados_reg").val();
var idofic = $("#oficina_empleados").val();
var idmetod = $("#pago_empleados_reg").val();
var nombres = nombre+" "+apellido;
var m13="";
var m14="";
var mfr="";
if (document.getElementById('fr').className == "btn btn-light") mfr='f';
else mfr='t';
if (document.getElementById('13ro').className == "btn btn-light") m13='f';
else m13='t';
if (document.getElementById('14to').className == "btn btn-light") m14='f';
else m14='t';


if(idmetod=="")
	{
	$("#mensaje_pago_empleados").text("Seleccione forma");
	$("#mensaje_pago_empleados").fadeIn("slow");
	$("#mensaje_pago_empleados").fadeOut("slow");
	}
if(idofic=="")
	{
	$("#mensaje_oficina_empleados").text("Seleccione oficina");
	$("#mensaje_oficina_empleados").fadeIn("slow");
	$("#mensaje_oficina_empleados").fadeOut("slow");
	}
if (estado== "")
{    	
	$("#mensaje_estado_empleados").text("Seleccione estado");
	$("#mensaje_estado_empleados").fadeIn("slow");
	$("#mensaje_estado_empleados").fadeOut("slow");
}
if (idgrup== "")
{    	
	$("#mensaje_turno_empleados").text("Seleccione turno");
	$("#mensaje_turno_empleados").fadeIn("slow");
	$("#mensaje_turno_empleados").fadeOut("slow");
}

if (nombre== "")
{    	
	$("#mensaje_nombre_empleados").text("Introduzca nombres");
	$("#mensaje_nombre_empleados").fadeIn("slow");
	$("#mensaje_nombre_empleados").fadeOut("slow");
}

if (apellido== "")
{    	
	$("#mensaje_apellido_empleados").text("Introduzca apellidos");
	$("#mensaje_apellido_empleados").fadeIn("slow");
	$("#mensaje_apellido_empleados").fadeOut("slow");
}

if (cargo== "")
{    	
	$("#mensaje_cargo_empleados").text("Introduzca cargo");
	$("#mensaje_cargo_empleados").fadeIn("slow");
	$("#mensaje_cargo_empleados").fadeOut("slow");
}

if (dpto== "")
{    	
	$("#mensaje_dpto_empleados").text("Introduzca departamento");
	$("#mensaje_dpto_empleados").fadeIn("slow");
	$("#mensaje_dpto_empleados").fadeOut("slow");
}

if (ci== "" || ci.includes("_"))
{    	
	$("#mensaje_cedula_usuarios").text("Introduzca cedula");
	$("#mensaje_cedula_usuarios").fadeIn("slow");
	$("#mensaje_cedula_usuarios").fadeOut("slow");
}

if (ci!="" && cargo!="" && dpto!="" && nombre!="" && apellido!="" && idgrup!="" && estado!="" && !ci.includes("_") && idofic!="" && idmetod!="")
	{
	$.ajax({
	    url: 'index.php?controller=Empleados&action=AgregarEmpleado',
	    type: 'POST',
	    data: {
	    	   numero_cedula: ci,
	    	   cargo: cargo,
	    	   dpto: dpto,
	    	   nombre_empleado: nombres,
	    	   id_grupo:idgrup,
	    	   estado:estado,
	    	   id_oficina:idofic,
	    	   id_metodo:idmetod,
	    	   m_13:m13,
	    	   m_14:m14,
	    	   m_fr:mfr
	    },
	})
	.done(function(x) {
		console.log(x);
		$("#cedula_empleado").val("");
		$("#nombre_empleados").val("");
		$("#apellido_empleados").val("");
		$("#cargo_empleados").val("");
		$("#dpto_empleados").val("");
		$("#turno_empleados").val("");
		$('#estado_empleados_reg').val("");
		$("#oficina_empleados").val("");
		$("#pago_empleados_reg").val("");
		document.getElementById('13ro').className = "btn btn-light";
		 document.getElementById('13roicon').className = "glyphicon glyphicon-unchecked";
		 document.getElementById('14to').className = "btn btn-light";
		 document.getElementById('14toicon').className = "glyphicon glyphicon-unchecked";
		 document.getElementById('fr').className = "btn btn-light";
		 document.getElementById('fricon').className = "glyphicon glyphicon-unchecked";
		if (x==1)
			{
			swal({
		  		  title: "Empleado",
		  		  text: "Empleado registrado exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_empleados(1);
			}
		else
			{
			swal({
		  		  title: "Empleado",
		  		  text: "Hubo un error al registrar al empleado",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
				
			}
		
		
			
	})
	.fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Empleado",
	  		  text: "Hubo un error al registrar al empleado",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	}
	
}

function LimpiarCampos()
{
	document.getElementById('13ro').className = "btn btn-light";
	 document.getElementById('13roicon').className = "glyphicon glyphicon-unchecked";
	 document.getElementById('14to').className = "btn btn-light";
	 document.getElementById('14toicon').className = "glyphicon glyphicon-unchecked";
	 document.getElementById('fr').className = "btn btn-light";
	 document.getElementById('fricon').className = "glyphicon glyphicon-unchecked";
	 
	$("#cedula_empleado").val("");
	$("#nombre_empleados").val("");
	$("#apellido_empleados").val("");
	$("#dpto_empleados").val("");
	$("#estado_empleados_reg").val("");
	$("#oficina_empleados").val("");
    $("#pago_empleados_reg").val("");
	SelecGrupo("");
	SelecCargo("");
}

function EditarEmpleado(cedula, nombres, cargo, dpto, idgrupo, idestado, idofic, idmetod, m14, m13, mfr)
{
	console.log(m14+"=="+m13+"==="+mfr+" estados mensualizacion");
var res = nombres.split(" ")
var nombre = res[0]+" "+res[1];
var apellido = res[2]+" "+res[3];
$("#cedula_empleado").val(cedula);
$("#nombre_empleados").val(nombre);
$("#apellido_empleados").val(apellido);
$("#dpto_empleados").val(dpto);
$("#estado_empleados_reg").val(idestado);
$("#oficina_empleados").val(idofic);
$("#pago_empleados_reg").val(idmetod);
if (m14=='t')
{
document.getElementById('14to').className = "btn btn-primary";
document.getElementById('14toicon').className = "glyphicon glyphicon-check";
}
if (m13 == "t")
{
document.getElementById('13ro').className = "btn btn-primary";
document.getElementById('13roicon').className = "glyphicon glyphicon-check";
}
if (mfr == "t")
{
document.getElementById('fr').className = "btn btn-primary";
document.getElementById('fricon').className = "glyphicon glyphicon-check";
}
SelecGrupo(idgrupo);
SelecCargo(cargo);
$('html, body').animate({ scrollTop: 0 }, 'fast');
}
function EliminarEmpleado(cedula)
{
	$.ajax({
	    url: 'index.php?controller=Empleados&action=EliminarValor',
	    type: 'POST',
	    data: {
	    	   numero_cedula: cedula
	    },
	})
	.done(function(x) {
		load_empleados(1);
		$("#cedula_empleado").val("");
		$("#nombre_empleados").val("");
		$("#apellido_empleados").val("");
		$("#cargo_empleados").val("");
		$("#dpto_empleados").val("");
		$("#turno_empleados").val("");
		swal({
	  		  title: "Empleado",
	  		  text: "Empleado eliminado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
	})
	.fail(function() {
	    console.log("error");
	});		
}
