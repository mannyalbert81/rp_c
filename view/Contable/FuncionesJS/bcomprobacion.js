$(document).ready(function(){
	
	$('#genReporte').tooltip();
	//$('#genReporte').popover();
	
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

function BuscarReporte(){
	 
	var mesbalance = $("#mes_balance").val();
	var aniobalance = $("#anio_balance").val();
	var maxnivel = $("#nivel_balance").val();
	
	if (mesbalance==0)
		{
		$("#mensaje_mes_balance").text("Seleccione mes");
		$("#mensaje_mes_balance").fadeIn("slow");
		$("#mensaje_mes_balance").fadeOut("slow");
		}
	if(aniobalance==0)
		{
		$("#mensaje_anio_balance").text("Seleccione año");
		$("#mensaje_anio_balance").fadeIn("slow");
		$("#mensaje_anio_balance").fadeOut("slow");
		}
	if(mesbalance!=0 && aniobalance!=0) 
	{
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
			console.log(x);
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

/*** cambios 28-11-2019 dc **/

function verReporte(){
	
	let $mesBalance	= $("#mes_balance"),
		$anioBalance= $("#anio_balance"),
		$maxNivel	= $("#nivel_balance");
	
	if ($anioBalance.val()==0){		
		$anioBalance.notify("Seleccione año",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if ($mesBalance.val()==0){		
		$mesBalance.notify("Seleccione mes",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	$.ajax({
	    url: 'index.php?controller=BalanceComprobacion&action=obtenerBalance',
	    type: 'POST',
	    beforeSend:MensajePreliminar(),
	    dataType:"json",
	    data: {
	    	   mes: $mesBalance.val(),
	    	   anio: $anioBalance.val(),
	    	   max_nivel_balance: $maxNivel.val() 
	    },
	})
	.done(function(x) {	
		
		swal("Reporte cargado", {
		      icon: "success",
		      buttons: false,
		      timer: 1000
		    });
		
		$("#pnl_balance").html("");
		$("#pnl_errores").css({"display":"none"});
		$("#lista_cuentas_errores").append("<li></li>");
		$("#cant_errores_balance").text("0"); 
		$("#pnl_descarga").css({"display":"none"});
		
		if( x.balance != undefined ){
			$("#pnl_balance").html(x.balance);
			
			if( x.cantidaderrores != undefined && x.cantidaderrores == 0 ){
				$("#pnl_descarga").css({"display":""});
			}
		}
		
		if( x.errores != undefined && x.errores != "" ){
			
			$("#pnl_errores").css({"display":""});
			$("#lista_cuentas_errores").append(x.errores);
			$("#cant_errores_balance").text(x.cantidaderrores);  
		}
		
	})
	.fail(function(xhr,status, error) {
		swal.close();
	    console.log(xhr.responseText);
	});

	
}

function MensajePreliminar(){
	
	swal({
		  title: "Reporte preliminar",
		  text: "Preparando el reporte preliminar",
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});	
}

function generaReporte(){
	
	let $mesBalance	= $("#mes_balance"),
	$anioBalance= $("#anio_balance"),
	$maxNivel	= $("#nivel_balance");

	if ($anioBalance.val()==0){		
		$anioBalance.notify("Seleccione año",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if ($mesBalance.val()==0){		
		$mesBalance.notify("Seleccione mes",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	
	var parametros = {
			mes: $mesBalance.val(),
    	   anio: $anioBalance.val(),
    	   max_nivel_balance: $maxNivel.val() 
	}
	
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=BalanceComprobacion&action=DescargarReporte");
    form.setAttribute("target", "_blank");   
    
    for (var i in parametros) {
        if (parametros.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = parametros[i];
            form.appendChild(input);
        }
    }
    
    document.body.appendChild(form); 
    form.submit();    
    document.body.removeChild(form);
	
}



