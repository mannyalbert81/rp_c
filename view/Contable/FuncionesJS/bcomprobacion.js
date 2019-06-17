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
	var maxnivel = $("#nivel_balance").val();
	
	swal({
		  title: "Reporte preliminar",
		  text: "Preparando el reporte preliminar",
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
	$.ajax({
	    url: 'index.php?controller=BalanceComprobacion&action=GenerarReporte',
	    type: 'POST',
	    data: {
	    	   mes: mesbalance,
	    	   anio: aniobalance,
	    	   max_nivel_balance: maxnivel 
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#plan_cuentas").html(x);
			swal("Reporte cargado", {
			      icon: "success",
			      buttons: false,
			      timer: 1000
			    });
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


