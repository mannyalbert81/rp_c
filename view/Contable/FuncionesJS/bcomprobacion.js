$(document).ready(function(){
	periodoactual();
})

/***
 * consulta periodo actual de la entidad
 * @returns 
 * json
 */
function periodoactual(){
	$.ajax({
		url:'index.php?controller=BalanceComprobacion&action=buscaperiodo',
		type:'POST',
		dataType:'json',
		data:{term:$('#cedula_usuarios').val()}
	}).done(function(respuesta){		
		
		if(respuesta.mensaje == '1'){
			var datos = respuesta.datos[0];
			$('#anio_balance').append($('<option>', {value:datos.anio_periodo, text:datos.anio_periodo,selected:"selected"}));
			$('#estado_balance').val(datos.nombre_estado)
			
			 $("#mes_balance option").each(function(){
			        if ($(this).val() == datos.mes_periodo ){        
			        	$(this).attr('selected', 'selected')
			        }
			     });
		}
		
	}).fail( function( xhr , status, error ){
		 var err=xhr.responseText
		console.log(err)
	});
}

function BuscarReporte()
{
	var mesbalance = $("#mes_balance").val();
	var aniobalance = $("#anio_balance").val();
	$.ajax({
	    url: 'index.php?controller=BalanceComprobacion&action=GenerarReporte',
	    type: 'POST',
	    data: {
	    	   mes: mesbalance,
	    	   anio: aniobalance
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


