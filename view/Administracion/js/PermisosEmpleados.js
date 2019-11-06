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
			$('#dias_disponibles').val(respuesta.dias_vacaciones_empleados);
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
          url: 'index.php?controller=PermisosEmpleados&action=consulta_solicitudes&search='+search+'&id_estado='+idestado,
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
        		  }
        	  else
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
	var datectr1 = new Date(2000, 0, 1,  h1ctr[0], h1ctr[1]); //aqui toma hora de control de entrada
	var datectr2 = new Date(2000, 0, 1, h2ctr[0], h2ctr[1]);  //aqui toma hora de control de salida
	var diffent = ((date1-datectr1)/1000); //validacion presentada en minutos
	console.log(diffent);
	if (diffent < 0) return false; //si el resultado es mayor a cero la hora esta pasado las horas de ingreso
	else{
		diffent = datectr2 - date1; 
		if (diffent < 0){ //valida que no este fuera de rango de salida 
			return false;
		}
		return true;
	}
}

function validarhasta(){
	// se realiza misma validacion de desde
	var hhasta = $("#hora_hasta").val();
	var h1 = hhasta.split(":");
	var date1 = new Date(2000, 0, 1,  h1[0], h1[1]);
	var datectr1 = new Date(2000, 0, 1,  h1ctr[0], h1ctr[1]); //aqui toma hora de control de entrada
	var datectr2 = new Date(2000, 0, 1, h2ctr[0], h2ctr[1]); //aqui toma hora de control de salida
	var diffent = ((date1-datectr1)/1000); //validacion presentada en minutos
	console.log(diffent);
	if (diffent < 0) return false;
	else{
		diffent = datectr2 - date1;
		if (diffent < 0){ //valida que no este fuera de rango de salida 
			return false;
		}
		return true;
	}
}


function TodoElDia(){

 if (document.getElementById('dia').className == "btn btn-light"){
	 
	 document.getElementById('dia').className = "btn btn-primary";
	 document.getElementById('diaicon').className = "glyphicon glyphicon-check";
	 $.ajax({
		    url: 'index.php?controller=PermisosEmpleados&action=GetHoras',
		    type: 'POST',
		    data: {
		    	   
		    },
		}).done(function(x) {
			
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
	 
	 }else{
		 
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
	if(fechael[0] < year){
		return false;
	}else if (fechael[1] < mes){
		return false;
	}else if (fechael[1]== mes && fechael[2] <= hoy){
		return false;
	}else{
		return true;
	}
}



function InsertarSolicitud(){
	
console.log( validardesde());	

var fecha = $("#fecha_permiso").val();
var desde = $("#hora_desde").val();
var hasta = $("#hora_hasta").val();
var causa = $("#causa_permiso").val();
var desc = $("#descripcion_causa").val();

/** validacion de campos vacios */
if (fecha=="" || fecha.length<1 ){
	$("#fecha_permiso").notify("Digite una fecha",{ position:"buttom left", autoHideDelay: 2000}); return false;
}

if ( !validarfecha(fecha) ){
	$("#fecha_permiso").notify("Fecha no puede ser igual o menor a la presente",{ position:"buttom left", autoHideDelay: 2000}); return false;
}

if (desde== "" || desde.includes("_")){ $("#hora_desde").notify("Digite una Hora inicio",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if(!validardesde() && desde!=""){ $("#hora_desde").notify("Hora inicio fuera de rango ",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if (hasta== "" || hasta.includes("_")){ $("#hora_hasta").notify("Digite una Hora inicio",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if(!validarhasta() && hasta!=""){ $("#hora_hasta").notify("Hora fin fuera de rango ",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if (!validardesde() ){ $("#hora_desde").notify("Hora inicio no definida ",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if( causa == "" ){ $("#causa_permiso").notify("seleccione permiso",{ position:"buttom left", autoHideDelay: 2000}); return false; }

if( (causa == 6 || causa == 3) && desc == "" ){ $("#descripcion_causa").notify("Ingrese una descripcion",{ position:"buttom left", autoHideDelay: 2000}); return false; }


	$.ajax({
	    url: 'index.php?controller=PermisosEmpleados&action=AgregarSolicitud',
	    type: 'POST',
	    data: {fecha_solicitud: fecha,
	    	   hora_desde: desde,
	    	   hora_hasta: hasta,
	    	   id_causa: causa,
	    	   descripcion_causa:desc,
	    	   id_permiso_editar:$("#valor_editar_permiso").val()},
	})
	.done(function(x) {
		$("#fecha_permiso").val("");
		$("#hora_desde").val("");
		$("#hora_hasta").val("");
		$("#causa_permiso").val("");
		$("#descripcion_causa").val("");
		document.getElementById('descripcion_causa').readOnly = true;
		document.getElementById('dia').className = "btn btn-light";
		document.getElementById('diaicon').className = "glyphicon glyphicon-unchecked";
		document.getElementById('hora_desde').readOnly = false;
	    document.getElementById('hora_hasta').readOnly = false;
	    $("#valor_editar_permiso").val("0");
		console.log(x);
		if (x==1){
			swal({
		  		  title: "Solicitud",
		  		  text: "Solicitud registrada exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
		}else if(x.trim()=="E001"){
			swal({
		  		  title: "Solicitud",
		  		  text: "Ya se encuentra registrada una solicitud a la fecha",
		  		  icon: "info",
		  		  dangerMode:true,
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
		}else if(x.trim()==2){
			swal({
		  		  title: "Solicitud",
		  		  text: "Solicitud ha sido actualizada",
		  		  icon: "info",
		  		  dangerMode:false,
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
		}else{
			if (x.includes("Warning") || x.includes("Notice") || x.includes("Error")){
				if(x.includes("sin encontrar RETURN")){
					swal({
			  		  title: "Solicitud",
			  		  text: "Ya existe una solicitud para el día indicado",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}else{
					swal({
				  		  title: "Solicitud",
				  		  text: "Error al agregar solicitud",
				  		  icon: "warning",
				  		  button: "Aceptar",
				  		});
				}			
			}
		}
			
	}).fail(function() {
	    console.log("error");
	    swal({
	  		  title: "Solicitud",
	  		  text: "Hubo un error al registrar solicitud",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	});
	
	
}

function HabilitarDescripcion()
{
	var causa = $("#causa_permiso").val();
	if (causa != 6 && causa != 3) document.getElementById('descripcion_causa').readOnly = true;
	else document.getElementById('descripcion_causa').readOnly = false;
}

function LimpiarCampos()
{
	
	
	$("#fecha_permiso").val("");
	$("#hora_desde").val("");
	$("#hora_hasta").val("");
	$("#causa_permiso").val("");
	$("#descripcion_causa").val("");
	document.getElementById('descripcion_causa').readOnly = true;
	 document.getElementById('dia').className = "btn btn-light";
	 document.getElementById('diaicon').className = "glyphicon glyphicon-unchecked";
     document.getElementById('hora_desde').readOnly = false;
		document.getElementById('hora_hasta').readOnly = false;
	$("#valor_editar_permiso").val("0");
	
}

function Aprobar(idsol,nomest)
{
	
	var url="";
	var msg="";
	if (nomest == "EN REVISION") 
		{
		url = 'index.php?controller=PermisosEmpleados&action=VBSolicitud';
		msg = 'Estado de solicitud cambiado a visto bueno';
		}
	if (nomest == "VISTO BUENO") 
	{
		url = 'index.php?controller=PermisosEmpleados&action=AprobarSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado';
	}
	if (nomest == "APROBADO")
	{
		url = 'index.php?controller=PermisosEmpleados&action=GerenciaSolicitud';
		msg = 'Estado de solicitud cambiado a aprobado gerencia';
	}
	if (nomest == "APROBADO GERENCIA")
	{
		url = 'index.php?controller=PermisosEmpleados&action=CertificadoMedico';
		msg = 'Certificado médico presentado';
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
			if (x.includes("Warning") || x.includes("Notice") || x.includes("Error"))
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
	    url: 'index.php?controller=PermisosEmpleados&action=NegarSolicitud',
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
			if (x.includes("Warning") || x.includes("Notice") || x.includes("Error"))
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

function SinCertificado(idsol)
{
	$.ajax({
	    url: 'index.php?controller=PermisosEmpleados&action=SinCertificadoMedico',
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
		  		  text: "Certificado médico no fue presentado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
				load_solicitudes(1);
			}
		else
			{
			if (x.includes("Warning") || x.includes("Notice") || x.includes("Error"))
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

function CambiarPermiso(_id_empleado_permiso){
	
	//console.log("permiso es --> "+_id_empleado_permiso);
	
	$("html, body").animate({ scrollTop: $(nombre_empleados).offset().top-120 }, 1000);
	
	$.ajax({
	    url: 'index.php?controller=PermisosEmpleados&action=BuscaPermisoEditar',
	    type: 'POST',
	    dataType:"json",
	    data: {
	    	id_empleado_permiso: _id_empleado_permiso
	    },
	})
	.done(function(x) {
		
		if( x.data != undefined && x.data != null){
			
			var rsConsulta = x.data;
			$("#valor_editar_permiso").val(_id_empleado_permiso);
			$("#fecha_permiso").val(rsConsulta[0].fecha_solicitud);
			$("#hora_desde").val(rsConsulta[0].hora_desde);
			$("#hora_hasta").val(rsConsulta[0].hora_hasta);
			$("#causa_permiso").val(rsConsulta[0].id_causa);
			$("#descripcion_causa").val(rsConsulta[0].descripcion_causa);
			if(rsConsulta[0].descripcion_causa == ""){
				document.getElementById('descripcion_causa').readOnly = true;
			}			
			document.getElementById('dia').className = "btn btn-light";
			document.getElementById('diaicon').className = "glyphicon glyphicon-unchecked";
			document.getElementById('hora_desde').readOnly = false;
		    document.getElementById('hora_hasta').readOnly = false;
			
			
		}
			
	})
	.fail(function(xhr,status,error) {
		
		var err =  xhr.responseText; console.log(err);
	    
	});
	
	/*var $modalPermisos = $("#mod_permisos_empleados");
	
	$modalPermisos.modal();*/
	
}
