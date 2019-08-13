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
				  };
		  
$("#load_creditos").fadeIn('slow');

$.ajax({
       beforeSend: function(objeto){
         $("#load_creditos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
       },
       url: 'index.php?controller=RevisionCreditos&action=getCreditosRegistrados&search='+search,
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
     		  $("#load_creditos").html('');
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

function load_reportes(pagina){

	  
var con_datos={
				  action:'ajax',
				  page:pagina,
				  };
		  
$("#load_creditos").fadeIn('slow');

$.ajax({
    beforeSend: function(objeto){
      $("#load_creditos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
    },
    url: 'index.php?controller=RevisionCreditos&action=getReportesRegistrados',
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

function GetReportes()
{
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
