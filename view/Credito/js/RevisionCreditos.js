var numero_credito = 0;
var idreporte = 0;

$(document).ready( function (){
	load_creditos(1);
	load_reportes(1);
	
	/*** para check en vista de TESORERIA **/
	$("#myModalVer").on( 'change','.chk_credito_seleccionado', function() {
		fnSeleccionaCreditosIndividuales(this);
	});	
	
	$("#myModalVer").on( 'change','#chk_creditos_all', function() {
		fnSeleccionaCreditos(this);
	});	
	
});

$("#myModalObservacion").on("hidden.bs.modal", function () {
	
	document.getElementById("cuerpo").classList.add('modal-open');
	
});
$("#myModalComprobantes").on("hidden.bs.modal", function () {
	
	document.getElementById("cuerpo").classList.add('modal-open');
	$("#datos_comprobante").html('');
	
});
$('#observacion_credito').keypress(function(event){
	  if(event.keyCode == 13){
	    $('#registrar_observacion').click();
	  }
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
    	   console.log(x);
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
        
         
       },error: function(jqXHR,estado,error){
    	   
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
	      
	    },error: function(jqXHR,estado,error){
	     $("#creditos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	    }
	});
}

function load_reportes(pagina){
	
	var fecha_reporte=$("#fecha_reportes").val();
	var con_datos={ action:'ajax', page:pagina, fecha_reporte:fecha_reporte };
		  
	$("#load_reportes").fadeIn('slow');

	$.ajax({
		beforeSend: function(objeto){
			//$("#load_reportes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
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
			$("#load_reportes").html('');
			$("#reportes_registrados").html("Error Conexion Servidor..."+estado+"    "+error);
		}
	});
}

function AgregarReporte(id_credito)
{
	$("#myModalInsertar").modal();
	numero_credito=id_credito;
	GetReportes();
}

function AbrirReporte(id_reporte)
{
	$("#myModalVer").modal();
	console.log("CARGADO REPORTE /. -. \. " + id_reporte);
	GetDatosReporte(id_reporte,0);
}

/** dc 2020/09/10 **/
function AbrirReporteListado(id_reporte)
{
	$("#myModalVer").modal();
	console.log("CARGADO REPORTE /. -. \. " + id_reporte);
	GetDatosReporte(id_reporte,1);
}
/** end dc 2020/09/10 **/


function GetDatosReporte(id_reporte, detalle = 0 )
{
	console.log("cargando reporte")
	$("#datos_reporte").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	var params	= { action:'ajax', 'id_reporte':id_reporte, 'detalle': detalle };
	
	$.ajax({
		url:"index.php?controller=RevisionCreditos&action=getInfoReporte",
		type:"POST",
		data: params
	}).done(function(x){
		
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
		{
			swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error cargando los créditos",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		}else
		{
			$("#datos_reporte").html(x);
		    //$("#tabla_creditos_reporte").tablesorter(); 
		}
		
	}).fail(function(xhr, status, error){
		$("#datos_reporte").html("ERROR Conexion Servidor..."+estado+"    "+error);
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
	}).done(function(x) {
		$("#select_reportes").html(x);	
		
	}).fail(function() {
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
	
	//validacion de reportes
	try
	{
		$.ajax({
		    url: 'index.php?controller=RevisionCreditos&action=buscarReportesRevision',
		    type: 'POST',
		    dataType:'json',
		    data: null
		})
		.done(function(x) {
			
			if( x[0] != undefined && x[0] == "EXISTE" )
			{
				swal({title:"Reporte Créditos",text:"Existe un reporte en estado 'DEVUELTO A REVISIÓN' con le fecha seleccionada , se recomienda revisar el reporte ", icon:"info",dangerMode:true});
				
			}else
			{
				$.ajax({
				    url: 'index.php?controller=RevisionCreditos&action=GenerarReportes',
				    type: 'POST',
				    data: {
				    	id_reporte: id_reporte,
				    	id_credito: numero_credito
				    },
				}).done(function(x) {
					x=x.trim();
					
					if( x == '' || x.includes("Notice") || x.includes("Warning") || x.includes("Error"))
					{
						swal({
				  		  	title: "Créditos",
			  		  		text: "Hubo un error creando el reporte",
			  		  		icon: "warning",
			  		  		button: "Aceptar",
				  			});
						
					}else if (x=="REPORTE CERRADO")
			  		{
						swal({
							title: "Créditos",
				  		  	text: "El Reporte ya se encuentra cerrado",
				  		  	icon: "warning",
				  		  	button: "Aceptar",
				  			});
			  		}else
					{
			  			if( x.includes("OK") )
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
		  				}else
	  					{
		  					swal({
					  		  	title: "Créditos",
				  		  		text: "Revisar Buffer del metodo",
				  		  		icon: "info",
				  		  		button: "Aceptar",
					  			});
	  					}
				  		
					}
			     
				}).fail(function() {
				    console.log("error");
				});
			}
			
		})
		.fail(function(xhr, status, error){
			swal({title:"ERROR",text:"existe error de conexión con el servidor", icon:"error",dangerMode:true});
		});
									
	}catch(err)
	{
		swal({title:"ERROR",text:"Error de sintaxis --> "+err.message, icon:"error",dangerMode:true});		
	}
	
	
}

function AprobarJefeCreditos(id_reporte)
{
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteCredito',
	    type: 'POST',
	    data: {
	    	id_reporte: id_reporte
	    },
	}).done(function(x) {
		x=x.trim();		
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
  		{
  		  swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error aprobando el reporte",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		}else
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
		
	}).fail(function() {
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
  		load_reportes_aprobados(1);
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
  		load_reportes_aprobados(1);
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
		console.log(x);
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
  		load_reportes_aprobados(1);
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
  		load_reportes_aprobados(1);
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
	swal({
		  title: "Aprobar Reporte",
		  text: "Continuaremos con la activación del crédito. Recomendamos revisar los valores",
		  icon: "info",
		  buttons: true,
	}).then((isConfirm) => {
		
		  if (isConfirm) 
		  {  
			  console.log("continuo con la aprobación de créditos");
			  var listaCreditosAprobar = [];
			  var contador = 0;
			  var msgerror = true;

			  $.each( $(".chk_credito_seleccionado"), function( index, valor){

			      var elemento = $(this);
			  	  var item	= {};
			  	  contador++;

			      if( elemento.is(":checked") )
			      {
			    	  item['index']	= contador;
			    	  item['id_reporte']	= id_reporte;
			    	  item['id_creditos']	= elemento.val();
			  		
			    	  listaCreditosAprobar.push(item);
			    	  msgerror	= false;
			  		
			    	  if( isNaN(item.id_creditos) || item.id_creditos.length == 0 )
			    	  {
			    		  msgerror	= true;
				  	  }
			      }
			  				      
			  });
			
			  if( !msgerror )
			  {				  
				  $.ajax({
					    url: 'index.php?controller=RevisionCreditos&action=AprobarReporteTesoreria',
					    type: 'POST',
					    data: { "id_reporte": id_reporte, "listacreditos":JSON.stringify(listaCreditosAprobar) },
					}).done(function(x) {
						x=x.trim();
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
				  	  		load_reportes_aprobados(1);
				  	  		load_reportes(1);
				  	  		$('#cerrar_ver').click();
				  	  		swal({
					  		  title: "Créditos",
					  		  text: "Reporte aprobado",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
				  	  	}
						
					}).fail(function() {
					    console.log("error");
					});
			  }else
			  {
				  swal({title:'Reporte Créditos',text:'Revise créditos seleccionados',dangerMode:true,icon:'warning'});
			  }
			  
		  }else 
		  {
			  console.log("No continuo con la aprobación de créditos");
		  }
	});
	
	
	
}

var ActivarReporte	= function(a){
	
	var reporteId	= a;
	
	$.ajax({
		url:"index.php?controller=RevisionCreditos&action=activarReporteCreditos",
		type:"POST",
		dataType:"json",
		data:{ "id_reporte":reporteId}
	})
	.done(function(x){
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			$('#cerrar_ver').click();		
			load_creditos(1);
			load_reportes(1);
			swal({title:"Reporte Créditos", text:"Reporte Abierto", icon:"info"});
			
		}else
		{
			swal({title:"Reporte Créditos", text:x.mensaje, icon:"info", dangerMode:true});
		}
		
	})
	.fail(function(xhr, status, error ){
		console.error(xhr.responseText);
		swal({title:"Reporte Créditos", text:"Error Conexión Servidor", icon:"info", dangerMode:true});
	})
	
	swal("seguimiento #001");
}


function Negar(id_reporte, id_creditos)
{
	idreporte=id_reporte;
	numero_credito=id_creditos;
	$("#myModalObservacion").modal();
}

function ContinuaNegar()
{
	var observacion_credito	= $('#observacion_credito');
	
	if( observacion_credito.val() == "" )
	{
		observacion_credito.notify("Ingrese una Observacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	$('#cerrar_observacion').click();
	$('#cuerpo').removeClass('modal-open');
	
	swal({
		  title: "Anular Crédito",
		  text: "Aviso, al realizar la siguiente acción se anulará todos los créditos pertenecientes al reporte",
		  icon: "info",
		  buttons: true,
		  closeOnClickOutside: false,
		  closeOnEsc: false,
	})
	.then((isConfirm) => {
		
		if (isConfirm) 
		{
			$.ajax({
			    url: 'index.php?controller=RevisionCreditos&action=NegarCredito',
			    type: 'POST',
			    data: {
			    	'id_reporte': idreporte,
			    	'numero_credito': numero_credito,
			    	'observacion_credito': observacion_credito.val()
			    },
			}).done(function(x) {

				observacion_credito.val("");
				
				if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
			  	{
					swal( { title: "Créditos", text: "Hubo un error cambiando el estado del reporte", icon: "warning", button: "Aceptar" });
			  	}
			  	else
			  	{
					load_reportes(1);
					$('#cerrar_ver').click();
					swal({
				  		  title: "Créditos",
				  		  text: "Reporte devuelto a revisión",
				  		  icon: "success",
				  		  button: "Aceptar",
				  		});
			  	}
			     
			}).fail(function() {
			    console.log("error");
			});	
		   
		}else 
		{
			console.log("No continuo con la aprobación de créditos");
		}
	});
			
}

function Quitar(id_reporte, id_creditos)
{		
	$.ajax({
		url: 'index.php?controller=RevisionCreditos&action=QuitarCredito',
		type: 'POST',
		data: { "id_reporte": id_reporte, "numero_credito": id_creditos },
	}).done(function(x) {
		x=x.trim();		
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
		{
			swal({
				title: "Créditos",
				text: "Hubo un error cambiando el estado del credito",
				icon: "warning",
				button: "Aceptar",
	  		});
		}else
		{
			load_creditos(1);
			load_reportes(1);
			$('#cerrar_ver').click();
			swal({
				title: "Créditos",
				text: "Crédito retirado del reporte",
				icon: "success",
				button: "Aceptar",
			});
		}
	}).fail(function() {
		console.log("error");
	});
}

var DevolverRevision = function(a,b){
	
	var vid_reporte = a; var vid_creditos = b; 
	
	$.ajax({
		url:"index.php?controller=RevisionCreditos&action=devolverRevisionListado",
		type:"POST",
		/*dataType:""*/
		data:{"id_creditos":vid_creditos, "id_reporte":vid_reporte}
	}).done(function(x){		
		x=x.trim();
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
		{
			swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error cambiando el estado del credito",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		}else
		{
			load_creditos(1);
			load_reportes(1);
			$('#cerrar_ver').click();
			swal({
		  		  title: "Créditos",
		  		  text: "Crédito retirado del reporte",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
		}
		
	}).fail(function(xhr,status,error){
		swal({
			title:"REPORTE CRÉDITOS",
			text:"Hubo error al realizar proceso",
			icon:"success",
			button: "Aceptar"
		})
	})
	
}

/** dc 2020/09/08 **/
var AnularCredito	= function(a){
	
	var pid_creditos	= a;
	
	if( !isNaN(pid_creditos) && pid_creditos > 0 )
	{
		$.ajax({
			url:"index.php?controller=RevisionCreditos&action=anularCredito",
			dataType:"json",
			type:"POST",
			data:{"id_creditos":pid_creditos}
		})
		.done(function(x){
			if( x.estatus != undefined && x.estatus == "OK" )
			{
				load_creditos(1);
				load_reportes(1);
				swal({ title:"CRÉDITO ANULADO", icon:"success", text:"Se procedió con la anulación del Crédito", });
			}else
			{
				swal({ title:"CRÉDITO ANULADO", icon:"warning", text:"Error \n "+x.mensaje, });
			}
		})
		.fail(function(xhr, status, error){
			swal({ title:"ERROR", icon:"error", text:"Error conexión con servidor.", dangerMode:true });
		});
	}
}
/** end dc 2020/09/08 **/

function MostrarComprobantes(id_comprobantes)
{
	
	$("#datos_comprobante").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	$("#myModalComprobantes").modal();
	$.ajax({
	    url: 'index.php?controller=RevisionCreditos&action=MostrarComprobantes',
	    type: 'POST',
	    data: {
	    	id_ccomprobantes: id_comprobantes,
	    },
	}).done(function(x) {
		x=x.trim();
		if (x.includes("Notice") || x.includes("Warning") || x.includes("Error") || x.includes("ERROR"))
		{
			$('#cerrar_comprobantes').click();
			swal({
		  		  title: "Créditos",
		  		  text: "Hubo un error cargando los comprobantes",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
  		 }else
  		 {  		
  		  $("#datos_comprobante").html(x);
  		 }
     
	}).fail(function() {
	    console.log("error");
	});
	
}

function ImprimirReporte(id_reporte)
{
	console.log(id_reporte);
}

var fnSeleccionaCreditos	= function(a){
	var elemento = $(a);
	if( elemento.is(':checked') ) {
        $(".chk_credito_seleccionado").prop("checked", true);
    } else {
        $(".chk_credito_seleccionado").prop("checked", false);
    }
	
}
	
var fnSeleccionaCreditosIndividuales	= function(a){
	var elemento = $(a);		
	if ($(".chk_credito_seleccionado").length == $(".chk_credito_seleccionado:checked").length) {  
		$("#chk_creditos_all").prop("checked", true);  
	} else {  
		$("#chk_creditos_all").prop("checked", false);  
	}  
	
}


