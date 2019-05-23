$(document).ready( function (){
	
	console.log("bonesaw is ready!");
	$('#tabla_cuentas').on('click', 'tr.nivel1',function(){
	   
	    console.log("click");
	});	
});



function BuscarReporte()
{
	$.ajax({
	    url: 'index.php?controller=B17&action=CargarReporte',
	    type: 'POST',
	    data: {
	    	      	   
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#plan_cuentas").html(x);
			//$("#tabla_reporte").tablesorter(); 
			
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al obtener el reporte: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	});
	
}

function BuscarReporte2()
{
	$.ajax({
	    url: 'index.php?controller=B17&action=CargarReporte2',
	    type: 'POST',
	    data: {
	    	      	   
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#plan_cuentas2").html(x);
			//$("#tabla_reporte").tablesorter(); 
			
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al obtener el reporte: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	});
	
}

function ExpandirTabla(clase,idbt,parent)
{
var i;
	var filas = document.getElementsByClassName(clase);
	var filasxcerrar = document.getElementsByTagName("TR");
	var botones = document.getElementsByName("boton");
	for (i = 0; i < filasxcerrar.length; i++) {
		if (filasxcerrar[i].className!=parent && $(filasxcerrar[i]).is(':visible'))
	{
		$(filasxcerrar[i]).slideToggle(200);
	}
	
	}
	
	for (i = 0; i < botones.length; i++) {
		if(botones[i].id!=idbt)
		  botones[i].className="fa fa-plus";
		}
	for (i = 0; i < filas.length; i++) {
	  
	  if (!($(filas[i]).is(':visible'))) $(filas[i]).slideToggle(200);  
	}
	
	if (document.getElementById(idbt).className == "fa fa-minus") document.getElementById(idbt).className = "fa fa-plus";
	else document.getElementById(idbt).className = "fa fa-minus";
	
}

function ExpandirTabla(clase,idbt)
{
	var i;
	var filas = document.getElementsByClassName(clase);
	var filasxcerrar = document.getElementsByTagName("TR");
	var botones = document.getElementsByName("boton");
	var botones1 = document.getElementsByName("boton1");
	var botones2 = document.getElementsByName("boton2");
	for (i = 0; i < filasxcerrar.length; i++) {
		if (filasxcerrar[i].className!="" && $(filasxcerrar[i]).is(':visible'))
	{
		$(filasxcerrar[i]).slideToggle(200);
	}
	
	}
	
	for (i = 0; i < botones.length; i++) {
		if(botones[i].id!=idbt)
		  botones[i].className="fa fa-plus";
		}
	for (i = 0; i < botones1.length; i++) {
		if(botones1[i].id!=idbt)
		  botones1[i].className="fa fa-plus";
		}
	for (i = 0; i < botones2.length; i++) {
		if(botones2[i].id!=idbt)
		  botones2[i].className="fa fa-plus";
		}
	for (i = 0; i < filas.length; i++) {
	  
	  if (!($(filas[i]).is(':visible'))) $(filas[i]).slideToggle(200);  
	}
	
	if (document.getElementById(idbt).className == "fa fa-minus") document.getElementById(idbt).className = "fa fa-plus";
	else document.getElementById(idbt).className = "fa fa-minus";
	
}

function ExpandirTabla2(clase,idbt,parent)
{
	console.log(parent);
	console.log(clase);
	var i;
	var filas = document.getElementsByClassName(clase);
	var filasxcerrar = document.getElementsByTagName("TR");
	var botones = document.getElementsByName("boton1");
	for (i = 0; i < filasxcerrar.length; i++) {
		console.log(filasxcerrar[i].className+"|"+parent)
		if (filasxcerrar[i].className!="" && filasxcerrar[i].className!=parent && $(filasxcerrar[i]).is(':visible'))
	{
		$(filasxcerrar[i]).slideToggle(200);
	}
	
	}
	
	for (i = 0; i < botones.length; i++) {
		if(botones[i].id!=idbt)
		  botones[i].className="fa fa-plus";
		}
	for (i = 0; i < filas.length; i++) {
	  
	  if (!($(filas[i]).is(':visible'))) $(filas[i]).slideToggle(200);  
	}
	
	if (document.getElementById(idbt).className == "fa fa-minus") document.getElementById(idbt).className = "fa fa-plus";
	else document.getElementById(idbt).className = "fa fa-minus";
	
}
function ExpandirTabla3(clase,idbt,parent)
{
	console.log(parent);
	console.log(clase);
	var newStr = parent.substring(0, parent.length-1);
	console.log(newStr+" parent parent");
	var i;
	var filas = document.getElementsByClassName(clase);
	var filasxcerrar = document.getElementsByTagName("TR");
	var botones = document.getElementsByName("boton2");
	for (i = 0; i < filasxcerrar.length; i++) {
		//console.log(filasxcerrar[i].className+"|"+parent)
		if (filasxcerrar[i].className!="" && filasxcerrar[i].className!=parent && filasxcerrar[i].className!=newStr && $(filasxcerrar[i]).is(':visible'))
	{
		$(filasxcerrar[i]).slideToggle(200);
	}
	
	}
	
	for (i = 0; i < botones.length; i++) {
		if(botones[i].id!=idbt)
		  botones[i].className="fa fa-plus";
		}
	for (i = 0; i < filas.length; i++) {
	  
	  if (!($(filas[i]).is(':visible'))) $(filas[i]).slideToggle(200);  
	}
	
	if (document.getElementById(idbt).className == "fa fa-minus") document.getElementById(idbt).className = "fa fa-plus";
	else document.getElementById(idbt).className = "fa fa-minus";
	
}

function GenerarReporte()
{
	console.log("generando reporte");
	$.ajax({
	    url: 'index.php?controller=B17&action=DescargarReporte',
	    type: 'POST',
	    data: {
	    	      	   
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			
			//$("#tabla_reporte").tablesorter(); 
			
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al obtener el reporte: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	});
}