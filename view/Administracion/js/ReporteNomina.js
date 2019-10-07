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
	if(empleado!=0)
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
		var sociales= $("#asuntos_empleados").val();
		var reserva= $("#fondos").val();
		if(h50=="")
			{
			$("#mensaje_h50_empleados").text("Ingrese valor");
			$("#mensaje_h50_empleados").fadeIn("slow");
			$("#mensaje_h50_empleados").fadeOut("slow");
			}
		if(h100=="")
		{
		$("#mensaje_h100_empleados").text("Ingrese valor");
		$("#mensaje_h100_empleados").fadeIn("slow");
		$("#mensaje_h100_empleados").fadeOut("slow");
		}
		if(dec4=="")
		{
		$("#mensaje_14").text("Ingrese valor");
		$("#mensaje_14").fadeIn("slow");
		$("#mensaje_14").fadeOut("slow");
		}
		if(dec3=="")
		{
		$("#mensaje_13").text("Ingrese valor");
		$("#mensaje_13").fadeIn("slow");
		$("#mensaje_13").fadeOut("slow");
		}
		if(anticipo=="")
		{
			$("#mensaje_anticipo_empleados").text("Ingrese valor");
			$("#mensaje_anticipo_empleados").fadeIn("slow");
			$("#mensaje_anticipo_empleados").fadeOut("slow");
		}
		if(apt_iess=="")
		{
			$("#mensaje_apiess_empleados").text("Ingrese valor");
			$("#mensaje_apiess_empleados").fadeIn("slow");
			$("#mensaje_apiess_empleados").fadeOut("slow");
		}
		if(asocap=="")
		{
			$("#mensaje_asocap_empleados").text("Ingrese valor");
			$("#mensaje_asocap_empleados").fadeIn("slow");
			$("#mensaje_asocap_empleados").fadeOut("slow");
		}
		if(quiro=="")
		{
			$("#mensaje_qiess_empleados").text("Ingrese valor");
			$("#mensaje_qiess_empleados").fadeIn("slow");
			$("#mensaje_qiess_empleados").fadeOut("slow");
		}
		if(hipo=="")
		{
			$("#mensaje_hiess_empleados").text("Ingrese valor");
			$("#mensaje_hiess_empleados").fadeIn("slow");
			$("#mensaje_hiess_empleados").fadeOut("slow");
		}
		if(dcto=="")
		{
			$("#mensaje_dcto_empleados").text("Ingrese valor");
			$("#mensaje_dcto_empleados").fadeIn("slow");
			$("#mensaje_dcto_empleados").fadeOut("slow");
		}
		if(h50!="" && h100!="" &&
		   dec4!="" && dec3!="" &&
		   anticipo!="" && apt_iess!="" &&
		   asocap!="" && quiro!="" && hipo!="" &&
		   dcto!="")
			{
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
			    	reserva:reserva,
			    	aporte_iess:apt_iess,
			    	asocap:asocap,
			    	quiro_iess:quiro,
			    	hipo_iess:hipo,
			    	dcto_sueldo:dcto,
			    	periodo:periodonom,
			    	id_empleado:empleado,
			    	sociales:sociales
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
							empleado=0;
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
		
		}
	else
		{
		swal({
	  		  title: "Registro",
	  		  text: "Seleccione un registro para editar",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
		}
	
}

function Periodos()
{
	$.ajax({
	    url: 'index.php?controller=ReporteNomina&action=index1',
	    type: 'POST',
	    data: {
	    	
	    },
	})
	.done(function(x) {
		console.log(x+"res");
				
	})
	.fail(function() {
	    console.log("error");
	});
}

function ReporteNomina(pagina)
{
var rep=true;	
console.log("reporte");
var search=$("#search").val();
var anio=$("#anio_marcaciones").val();
var periodo=$("#periodo_marcaciones").val();
if(anio=="")
	{
	
	if(periodo!="P")
		{
		$("#mensaje_anio_marcaciones").text("Seleccione anio");
		$("#mensaje_anio_marcaciones").fadeIn("slow");
		$("#mensaje_anio_marcaciones").fadeOut("slow");
		rep=false;
		}
	}
else
	{
	if(periodo=="P")
	{
	$("#mensaje_mes_marcaciones").text("Seleccione mes");
	$("#mensaje_mes_marcaciones").fadeIn("slow");
	$("#mensaje_mes_marcaciones").fadeOut("slow");
	rep=false
	}
	}
if(rep)
	{
	if (periodo=="P")
	{
		var mes = new Date().getMonth();
		mes++;
		var year = new Date().getFullYear();
		var dia_hoy= new Date().getDate();
		var mes_inicio=0;
		var mes_fin=0;
		var anio_inicio=year;
		var anio_fin=year;
		console.log(dia_hoy+" hoy")
		if(dia_hoy<=21)
		{
		mes_inicio=mes-2;
		mes_fin=mes-1;
		if (mes_inicio<1)
			{
			mes_inicio=12;
			anio_inicio=year-1;
			}
		}
	else
		{
		mes_inicio=mes;
		mes_fin=mes+1;
		if (mes_fin==13)
			{
			mes_fin=1;
			anio_fin=year+1;
			}
		}
		
		var diainicio = 22;
		 var diafinal = 21;
	
	var fechai = diainicio+"/"+mes_inicio+"/"+year;
	var fechaf = diafinal+"/"+mes_fin+"/"+year;
	
	periodo=fechai+"-"+fechaf;
	console.log(periodo)
	}
	else
	{
		var mes = periodo;
		var year = anio;
		mes--;
		if (mes==0)
			{
			mes=12;
			year--;
			}
		var diainicio = 22;
		var diafinal = 21;
		var fechai = diainicio+"/"+mes+"/"+year;
		mes++;
		if (mes>12){
			mes=1;
			year++;
			var fechaf = diafinal+"/"+mes+"/"+year;
		}
		else var fechaf = diafinal+"/"+mes+"/"+year;
		
		periodo=fechai+"-"+fechaf;
		console.log(periodo)	
	}
 $.ajax({
	    url: 'index.php?controller=ReporteNomina&action=GetReporte&search='+search,
	    type: 'POST',
	    data: {
	    	   page:pagina,
	    	   periodo:periodo	    	   
	    },
	})
	.done(function(x) {
		console.log(x);
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
	var periodo=$("#periodo_marcaciones").val();
	var anio=$("#anio_marcaciones").val();
	if (periodo=="P")
	{
	var mes = new Date().getMonth();
	var year = new Date().getFullYear();
	mes--;
	if (mes==0)
		{
		mes=12;
		year--;
		}
	var diainicio = 22;
	var diafinal = 21;
	var fechai = diainicio+"/"+mes+"/"+year;
	mes++;
	if (mes>12){
		mes=1;
		year++;
		var fechaf = diafinal+"/"+mes+"/"+year;
	}
	else var fechaf = diafinal+"/"+mes+"/"+year;
	
	periodo=fechai+"-"+fechaf;
	console.log(periodo)
	}
	else
	{
		var mes = periodo;
		var year = anio;
		mes--;
		if (mes==0)
			{
			mes=12;
			year--;
			}
		var diainicio = 22;
		var diafinal = 21;
		var fechai = diainicio+"/"+mes+"/"+year;
		mes++;
		if (mes>12){
			mes=1;
			year++;
			var fechaf = diafinal+"/"+mes+"/"+year;
		}
		else var fechaf = diafinal+"/"+mes+"/"+year;
		
		periodo=fechai+"-"+fechaf;
		console.log(periodo)	
	}
		console.log(periodo);
	var enlace = 'index.php?controller=ReporteNomina&action=ImprimirReporte&periodo='+periodo+'&search='+search;
	window.open(enlace, '_blank');	
}


function VerReporteIndividual(idregistro)
{
	console.log(idregistro);
	$("#myModalReporteIndividual").modal();
	$('#reporte_individual_empleado').html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	 $.ajax({
		    url: 'index.php?controller=ReporteNomina&action=VerReporteIndividual',
		    type: 'POST',
		    data: {
		    	id_registro:idregistro
		    },
		})
		.done(function(x) {
			console.log(x+'resultado');
					if (!(x.includes("Warning")) && !(x.includes("Notice")))
				{
				$("#reporte_individual_empleado").html(x);
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


function ImprimirReporteIndividual(idregistro)
{
	console.log(idregistro);
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
	var enlace = 'index.php?controller=ReporteNomina&action=ImprimirReporteIndividual&id_registro='+idregistro;
	window.open(enlace, '_blank');	
}
