var numero_credito;

$(document).ready( function (){
	load_creditos(1);
	load_reportes(1);
});

function load_creditos(pagina){

	   var search=$("#search").val();
	   var fecha=$("#fecha_concesion").val();
var con_datos={
				  action:'ajax',
				  page:pagina,
				  fecha_concesion:fecha,
				  search:search
				  };
		  
$("#load_creditos").fadeIn('slow');

$.ajax({
       beforeSend: function(objeto){
         $("#load_creditos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
       },
       url: 'index.php?controller=RevisionCreditos&action=getCreditosRegistrados',
       type: 'POST',
       data: con_datos,
       success: function(x){
    	   x=x.trim();
     	  if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
     		  {
     		  swal({
 		  		  title: "Créditos",
 		  		  text: "Hubo un error cargando los créditos",
 		  		  icon: "warning",
 		  		  button: "Aceptar",
 		  		});
     		  $("#load_creditos").html('');
     		  }
     	 else if (x=="NO MOSTRAR CREDITOS")
 		  {
 		  $("#titulo_box").html("Reportes Aprobados");
 		 $("#search_creditos").html("");
 		$("#input_fecha").html("");
 		var input_fecha='<input type="date"  class="form-control" id="fecha_concesion" name="fecha_concesion" placeholder="Fecha" onchange="load_reportes_aprobados(1)">';
 		$("#input_fecha").html(input_fecha);	
 		load_reportes_aprobados(1);
 		  }
     	  else
     		  {
     		  $("#creditos_registrados").html(x);
               $("#load_creditos").html("");
               $("#tabla_creditos").tablesorter(); 
     		  }
        
         
       },
      error: function(jqXHR,estado,error){
        $("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
      }
    });

}

function load_reportes_aprobados(pagina){
	var fecha_reporte=$("#fecha_concesion").val();
	
	var con_datos={
					  action:'ajax',
					  page:pagina,
					  fecha_reporte:fecha_reporte
					  };
			  
	$("#load_creditos").fadeIn('slow');
	$.ajax({
	    beforeSend: function(objeto){
	      $("#load_creditos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	    },
	    url: 'index.php?controller=RevisionCreditos&action=getReportesAprobados',
	    type: 'POST',
	    data: con_datos,
	    success: function(x){
	    	x=x.trim();
	  	  if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
	  		  {
	  		  swal({
			  		  title: "Créditos",
			  		  text: "Hubo un error cargando los reportes",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
	  		  $("#load_creditos").html('');
	  		  }
	  	  
	  	  else
	  		  {
	  		  $("#creditos_registrados").html(x);
	            $("#load_creditos").html("");
	            $("#tabla_reportes_aprobados").tablesorter(); 
	  		  }
	     
	      
	    },
	   error: function(jqXHR,estado,error){
	     $("#creditos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	   }
	 });

	}

function load_reportes(pagina){
var fecha_reporte=$("#fecha_reportes").val();
var con_datos={
				  action:'ajax',
				  page:pagina,
				  fecha_reporte:fecha_reporte
				  };
		  
$("#load_reportes").fadeIn('slow');

$.ajax({
    beforeSend: function(objeto){
      $("#load_reportes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
    },
    url: 'index.php?controller=RevisionCreditos&action=getReportesRegistrados',
    type: 'POST',
    data: con_datos,
    success: function(x){
    	x=x.trim();
  	  if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error cargando los reportes",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  $("#load_reportes").html('');
  		  }
  	  
  	  else
  		  {
  		  $("#reportes_registrados").html(x);
            $("#load_reportes").html("");
            $("#tabla_reportes").tablesorter(); 
  		  }
     
      
    },
   error: function(jqXHR,estado,error){
     $("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
   }
 });

}

function AgregarReporte(id_credito)
{
	$("#myModalInsertar").modal();
	numero_credito=id_credito;
	GetReportes()
}

function AbrirReporte(id_reporte)
{
	$("#myModalVer").modal();
	console.log(id_reporte);
	GetDatosReporte(id_reporte);
}

function GetDatosReporte(id_reporte)
{
	$("#datos_reporte").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	var con_datos={
			  action:'ajax',
			  id_reporte:id_reporte
			  };

$.ajax({
url: 'index.php?controller=RevisionCreditos&action=getInfoReporte',
type: 'POST',
data: con_datos,
success: function(x){
  if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
	  {
	  swal({
	  		  title: "Créditos",
	  		  text: "Hubo un error cargando los créditos",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
	  }
  else
	  {
	  $("#datos_reporte").html(x);
      $("#tabla_creditos_reporte").tablesorter(); 
	  }


},
error: function(jqXHR,estado,error){
$("#empleados_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
}
});
}


function GetReportes()
{
	$("#select_reportes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=GetReportes',
	    type: 'POST',
	    data: {
	    },
	})
	.done(function(x) {
		$("#select_reportes").html(x);	
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function SubirReporte()
{
	swal({
		  title: "Reporte",
		  text: "Actualizando reporte",
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
	var id_reporte=$("#reportes_creditos").val();
	console.log(id_reporte);
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=GenerarReportes',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte,
	    	id_credito: numero_credito
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error creando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else if (x=="REPORTE CERRADO")
  		  {
  		swal({
	  		  title: "Créditos",
	  		  text: "El Reporte ya se encuentra cerrado",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_insertar').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Crédito insertado en el reporte",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarJefeCreditos(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteCredito',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarJefeRecaudaciones(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteRecaudaciones',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarJefeSistemas(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteSistemas',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarContador(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteContador',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarGerente(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteGerente',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function AprobarTesoreria(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteTesoreria',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	})
	.done(function(x) {
		x=x.trim();
		console.log(x)
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		  {
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		  }
  	  else
  		  {
  		load_creditos(1);
		load_reportes(1);
		$('#cerrar_ver').click();
		swal({
	  		  title: "Créditos",
	  		  text: "Reporte aprobado",
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
  		  }
     
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}