var id_participe;


$('#cedula_participe').keypress(function(event){
	  if(event.keyCode == 13){
	    $('#buscar_participe').click();
	  }
	});
function BorrarCedula()
{
	$('#cedula_participe').val("");
}
function InfoSolicitud(cedula,id_solicitud)
{
	$('#cedula_participe').val(cedula);
	BuscarParticipe();
	SimulacionCredito();
	solicitud=id_solicitud;
	$.ajax({
	    url: 'index.php?controller=BuscarParticipesCesantes&action=InfoSolicitud',
	    type: 'POST',
	    data: {
	    	id_solicitud:id_solicitud
	    },
	})
	.done(function(x) {
		$("#info_solicitud").html(x);
	})
	.fail(function() {
	    console.log("error");
	});
	
}

function BuscarParticipe()
{
	var ciparticipe=$('#cedula_participe').val();
	
	if(ciparticipe=="" || ciparticipe.includes('_'))
		{
		$("#mensaje_cedula_participe").text("Ingrese cédula");
		$("#mensaje_cedula_participe").fadeIn("slow");
		$("#mensaje_cedula_participe").fadeOut("slow");
		}
	else
		{
		console.log(ciparticipe);
		$.ajax({
		    url: 'index.php?controller=BuscarParticipesCesantes&action=BuscarParticipe',
		    type: 'POST',
		    data: {
		    	   cedula: ciparticipe
		    },
		})
		.done(function(x) {
			var y=$.parseJSON(x);
			console.log(y);
			$('#participe_encontrado').html(y[0]);
		     id_participe=y[1];
			AportesParticipe(id_participe, 1)
			CreditosActivosParticipe(id_participe, 1)
			
		})
		.fail(function() {
		    console.log("error");
		});
		}
}

function AportesParticipe(id, page)
{
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=AportesParticipe',
	    type: 'POST',
	    data: {
	    	   id_participe: id,
	    	   page: page
	    },
	})
	.done(function(x) {
		$('#aportes_participe').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function CreditosActivosParticipe(id, page)
{
	$.ajax({
	    url: 'index.php?controller=BuscarParticipes&action=CreditosActivosParticipe',
	    type: 'POST',
	    data: {
	    	   id_participe: id,
	    	   page: page
	    },
	})
	.done(function(x) {
		$('#creditos_participe').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}

