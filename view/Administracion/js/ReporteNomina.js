var periodonom=0;
var empleado=0;

$(document).ready( function (){
	ReporteNomina(1);
		
});


function EditarNomina(nombre, ofic, salario, h50, h100, fonres, s14, s13, ant, aiess, asocap, social, qiess, hiess, dcto, periodo,idemp)
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
	empleado=idemp;
	
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
	var salario = $("#salario_empleados").val();
	$.ajax({
	    url: 'index.php?controller=ReporteNomina&action=ActualizarRegistros',
	    type: 'POST',
	    data: {
	    	salario:salario,
	    	h50:h50,
	    	h100:h100,
	    	fondos_reserva:fondos,
	    	decimo_cuarto:dec4,
	    	decimo_tercero:dec3,
	    	anticipo_sueldo:anticipo,
	    	aporte_iess:apt_iess,
	    	asocap:asocap,
	    	quiro_iess:quiro,
	    	hipo_iess:hipo,
	    	dcto_sueldo:dcto,
	    	periodo:periodonom,
	    	id_empleado:empleado
	    },
	})
	.done(function(x) {
		console.log(x);
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
					swal({
				  		  title: "Registro",
				  		  text: "Registro Actualizdo",
				  		  icon: "success",
				  		  button: "Aceptar",
				  		});
					ReporteNomina(1);
					LimpiarCampos();
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
var search=$("#search").val();
var mes = new Date().getMonth();
var year = new Date().getFullYear();
mes--;
mes--;
var diainicio = 22;
var diafinal = 21;
var fechai = diainicio+"/"+mes+"/"+year;
console.log(fechai);
mes++;
if (mes>12){
	mes=1;
	year++;
	var fechaf = diafinal+"/"+mes+"/"+year;
}
else var fechaf = diafinal+"/"+mes+"/"+year;
var periodo=$("#periodo_marcaciones").val();
 $.ajax({
	    url: 'index.php?controller=ReporteNomina&action=GetReporte&search='+search,
	    type: 'POST',
	    data: {
	    	   page:pagina,
	    	   periodo:periodo,
	    	   fechai:fechai,
	    	   fechaf:fechaf
	    	   
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

function LimpiarCampos()
{
	$("#nombre_empleados").val("");
	$("#oficina_empleados").val("");
	$("#salario_empleados").val("");
	$("#h50_empleados").val("");
	$("#h100_empleados").val("");
	$("#fondos").val("");
	$("#dec_cuarto_sueldo").val("");
	$("#dec_tercero_sueldo").val("");
	$("#anticipo_empleados").val("");
	$("#apt_iess_empleados").val("");
	$("#asocap_empleados").val("");
	$("#asuntos_empleados").val("");
	$("#quiro_iess_empleados").val("");
	$("#hipo_iess_empleados").val("");
	$("#dcto_sueldo_empleados").val("");
}

function ImprimirReporte()
{
	var search=$("#search").val();
	var mes = new Date().getMonth();
	var year = new Date().getFullYear();
	mes--;
	mes--;
	var diainicio = 22;
	var diafinal = 21;
	var fechai = diainicio+"/"+mes+"/"+year;
	console.log(fechai);
	mes++;
	if (mes>12){
		mes=1;
		year++;
		var fechaf = diafinal+"/"+mes+"/"+year;
	}
	else var fechaf = diafinal+"/"+mes+"/"+year;
	var fecha = fechai+'-'+fechaf;
	var periodo=$("#periodo_marcaciones").val();
	var enlace = 'index.php?controller=ReporteNomina&action=ImprimirReporte&periodo='+periodo+'&search='+search+'&fecha='+fecha;
	window.open(enlace, '_blank');	
}
