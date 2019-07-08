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
		    	   id_registro: regid,
		    	   hora_marcacion: hora,
		    	   fecha_marcacion: fecha,
		    	   numero_cedula: cedula,
		    	   tipo_registro: tipor
		    },
		})
		.done(function(x) {
			console.log(x);
			
		})
		.fail(function() {
		    console.log("error");
		});
		}
}