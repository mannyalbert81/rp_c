var periodonom=0;

$(document).ready( function (){
	ReporteNomina(1);
		
});


function EditarNomina(nombre, ofic, salario, h50, h100, fonres, s14, s13, ant, aiess, asocap, social, qiess, hiess, dcto, periodo)
{
	$("#nombre_empleados").val(nombre);
	$("#oficina_empleados").val(ofic);
	$("#salario_empleados").val(salario);
	$("#h50_empleados").val(h50);
	$("#h100_empleados").val(h100);
	$("#fondos").val(fonres);
	$("#dec_cuarto_sueldo").val(s14);
	$("#dec_tercero_sueldo").val(s13);
	$("#anticipo_empleados").val(ant);
	$("#apt_iess_empleados").val(aiess);
	$("#asocap_empleados").val(asocap);
	$("#asuntos_empleados").val(social);
	$("#quiro_iess_empleados").val(qiess);
	$("#hipo_iess_empleados").val(hiess);
	$("#dcto_sueldo_empleados").val(dcto);
	periodonom=periodo;
	$('html, body').animate({ scrollTop: 0 }, 'fast');
}

function ActualizarRegistros()
{
	
	var h50 = $("#h50_empleados").val();
	var h100 = $("#h100_empleados").val();
	var fondos = $("#fondos").val();
	var dec4 = $("#dec_cuarto_sueldo").val();
	var dec3 = $("#dec_tercero_sueldo").val();
	var anticipo = $("#anticipo_empleados").val();
	var apt_iess = $("#apt_iess_empleados").val();
	var asocap = $("#asocap_empleados").val();
	var quiro = $("#quiro_iess_empleados").val();
	var hipo = $("#hipo_iess_empleados").val();
	var dcto = $("#dcto_sueldo_empleados").val();
	$.ajax({
	    url: 'index.php?controller=ReporteNomina&action=ActualizarRegistros',
	    type: 'POST',
	    data: {
	    	h50:h50,
	    	h10:h100,
	    	fondos_reserva:fondos,
	    	decimo_cuarto:dec4,
	    	decimo_tercero:dec3,
	    	anticipo_sueldo:anticipo,
	    	aporte_iess:apt_iess,
	    	asocap:asocap,
	    	quiro_iess:quiro,
	    	hipo_iess:hipo,
	    	dcto_sueldo:dcto,
	    	periodo:periodonom
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			
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

function ReporteNomina(pagina)
{
console.log("reporte");
 $.ajax({
	    url: 'index.php?controller=ReporteNomina&action=GetReporte',
	    type: 'POST',
	    data: {
	    	   page:pagina
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#reporte").html(x);
			$("#tabla_reporte").tablesorter(); 
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
