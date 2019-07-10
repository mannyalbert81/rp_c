var id_participe;

$(document).ready( function (){
	
	$(":input").inputmask();
		
});

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
		    url: 'index.php?controller=BuscarParticipes&action=BuscarParticipe',
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
		console.log(x);
		$('#aportes_participe').html(x);
		
		
	})
	.fail(function() {
	    console.log("error");
	});
}