var id_plan_cuentas=0;
var codigo_cuentas="";
var id_entidades=0;
var id_modenas=0;
var n_plan_cuentas="";
var id_centro_costos=0;
var nivel_plan_cuentas=0;

$(document).ready(function(){
	
	load_planes_cuenta();
	$(":input").inputmask();

});

function load_planes_cuenta()
{
	console.log("Ready");
	$.ajax({
	    url: 'index.php?controller=PlanCuentas&action=TablaPlanCuentas',
	    type: 'POST',
	    data: {
	    	      	   
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#tabla_plan_cuentas").html(x);
			
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

function ExpandirTabla(clase,idbt)
{
	var i;
	var filas = document.getElementsByClassName(clase);
	var filasxcerrar = document.getElementsByTagName("TR");
	var boton = document.getElementById(idbt);
	var botones = document.getElementsByName("boton");
	
	var len=filas[0].className.length;
	var lenb=boton.id.length;
	console.log(lenb);
	for (i = 0; i < filasxcerrar.length; i++) {
		if (filasxcerrar[i].className.length>=len && $(filasxcerrar[i]).is(':visible') && filasxcerrar[i].className!=clase) 
		{
			$(filasxcerrar[i]).slideToggle(200);
		}
		
	}
	
	for (i = 0; i < botones.length; i++) {
		if(botones[i].id!=idbt && botones[i].id.length>=lenb)
		  botones[i].className="fa fa-angle-double-right";
		}
	
	for (i = 0; i < filas.length; i++) {
	  
			$(filas[i]).slideToggle(200);
	}
	
	if (document.getElementById(idbt).className == "fa fa-angle-double-down") document.getElementById(idbt).className = "fa fa-angle-double-right";
	else document.getElementById(idbt).className = "fa fa-angle-double-down";
	
}

function EditarCuenta(idcuentas, codigocuentas, nombrecuentas)
{
 id_plan_cuentas=idcuentas;
 var modal = $('#myModalEdit');
 var texto = codigocuentas+" -> "+nombrecuentas
  modal.find('#antiguo_nombre').val(texto);
 modal.find('#nuevo_nombre').val("");
}

function EditarNombreCuenta()
{
	var modal = $('#myModalEdit');
	var nuevoNombre= modal.find('#nuevo_nombre').val();
	var nombre= modal.find('#antiguo_nombre').val();
	console.log(nombre);
	if(nuevoNombre=="" ||  nombre==nuevoNombre)
		{
		modal.find("#mensaje_agregar_nombre").text("Escibra el nombre de la cuenta");
		modal.find("#mensaje_agregar_nombre").fadeIn("slow");
		modal.find("#mensaje_agregar_nombre").fadeOut("slow");
		}
	else
		{
		$.ajax({
		    url: 'index.php?controller=PlanCuentas&action=EditarNombreCuenta',
		    type: 'POST',
		    data: {
		    	id_plan_cuentas:id_plan_cuentas,
		    	nombre_plan_cuentas:nuevoNombre		    	
		    },
		})
		.done(function(x) {
					if (!(x.includes("Warning")) && !(x.includes("Notice")))
				{
						
						load_planes_cuenta();
						id_plan_cuentas=0;
						modal.modal('hide');
						swal({
					  		  title: "Plan Cuentas",
					  		  text: "Nombre de la cuenta editado!",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
				}
			else
				{
				swal({
			  		  title: "Plan Cuentas",
			  		  text: "Error al editar el nombre de la cuenta: "+x,
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

function AgregarCuenta(codigocuentas, identidades, idmodenas, nplancuentas, idcentrocostos, nivelplancuentas)
{
	console.log(codigocuentas+" "+identidades+" "+idmodenas+" "+nplancuentas+" "+idcentrocostos+" "+nivelplancuentas);
	codigo_cuentas=codigocuentas;
	id_entidades=identidades;
	id_modenas=idmodenas;
	n_plan_cuentas=nplancuentas;
	id_centro_costos=idcentrocostos;
	nivel_plan_cuentas=nivelplancuentas;
}

function AgregarNuevaCuenta()
{
	var modal = $('#myModalAgregar');
	var nuevoCodigo= modal.find('#nuevo_codigo').val();
	var nuevoNombre= modal.find('#nuevo_nombre').val();
	var elementos =codigo_cuentas.split(".");
	if(nuevoCodigo=="" || nuevoCodigo.includes("_"))
	{
	modal.find("#mensaje_agregar_codigo").text("Ingrese codigo correcto");
	modal.find("#mensaje_agregar_codigo").fadeIn("slow");
	modal.find("#mensaje_agregar_codigo").fadeOut("slow");
	}
	else
		{
		if(elementos.length > nivel_plan_cuentas)
		{
		nuevoCodigo=codigo_cuentas+nuevoCodigo;
		}
	else
		{
		nuevoCodigo=codigo_cuentas+"."+nuevoCodigo;
		}
	console.log(nuevoCodigo);
		}
	console.log(elementos);
	
	if(nuevoNombre=="")
		{
		modal.find("#mensaje_agregar_nombre").text("Escibra el nombre de la cuenta");
		modal.find("#mensaje_agregar_nombre").fadeIn("slow");
		modal.find("#mensaje_agregar_nombre").fadeOut("slow");
		}
	if (nuevoNombre!="" && nuevoCodigo!="" && !(nuevoCodigo.includes("_")))
		{
		nivel_plan_cuentas++;
		$.ajax({
		    url: 'index.php?controller=PlanCuentas&action=AgregarNuevaCuenta',
		    type: 'POST',
		    data: {
		    	codigo_plan_cuentas:nuevoCodigo,
		    	nombre_plan_cuentas:nuevoNombre,
		    	id_entidades:id_entidades,
		    	id_modenas:id_modenas,
		    	n_plan_cuentas:n_plan_cuentas,
		    	id_centro_costos:id_centro_costos,
		    	nivel_plan_cuentas:nivel_plan_cuentas
		    	
		    },
		})
		.done(function(x) {
					if (!(x.includes("Warning")) && !(x.includes("Notice")))
				{
						
						load_planes_cuenta();
						id_plan_cuentas=0;
						modal.find('#nuevo_codigo').val("");
						modal.find('#nuevo_nombre').val("");
						modal.modal('hide');
						swal({
					  		  title: "Plan Cuentas",
					  		  text: "Cuenta creada!",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
				}
			else
				{
				swal({
			  		  title: "Plan Cuentas",
			  		  text: "Error al editar el nombre de la cuenta: "+x,
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