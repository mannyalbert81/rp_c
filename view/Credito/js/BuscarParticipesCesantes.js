
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

function InfoParticipe()
{
	var modal = $('#myModalSimulacion');
	var ciparticipe=$('#cedula_participe').val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=CreditoParticipe',
	    type: 'POST',
	    data: {
	    	cedula_participe:ciparticipe
	    },
	})
	.done(function(x) {
		modal.find("#info_participe").html(x);
		var limite=document.getElementById("monto_disponible").innerHTML;
		var elementos=limite.split(" : ");
		limite=elementos[1];
		disponible_participe=limite;
		console.log("disponible participe "+limite);
		var lista=document.getElementById("disponible_participe").classList;
		lista=lista.value;
		if(lista.includes('bg-red'))
			{
			swal({
		  		  title: "Advertencia!",
		  		  text: "El participe no puede acceder a un crédito en este momento",
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		
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
		    $("#link_reporte").data("participe",id_participe);
		    console.log("valor de id -->"+id_participe);
			AportesParticipe(1),
			CreditosActivosParticipe(id_participe,1)
			cargaTipoPrestaciones();

			
		})
		.fail(function() {
		    console.log("error");
		});
		}
}

function AportesParticipe(){
	
	
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=BuscarParticipesCesantes&action=AportesParticipe",
		type:"POST",
		data:{action:'ajax',id_participe:id_participe}
	}).done(function(datos){		
		
		$("#aportes_participe_registrados").html(datos)		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}

function reportePrint(ObjetoLink){
	var $enlace = $(ObjetoLink);
	var id_participe = $enlace.data("participe");
	window.open("index.php?controller=BuscarParticipesCesantes&action=print&id_participes="+id_participe,"_blank");
}


function cargaTipoPrestaciones(){
	
	let $ddlTipoPrestaciones = $("#id_tipo_prestaciones");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=BuscarParticipesCesantes&action=cargaTipoPrestaciones",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		
	
		
		$ddlTipoPrestaciones.empty();
		$ddlTipoPrestaciones.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			
			
			$ddlTipoPrestaciones.append("<option value=' "+value.id_tipo_prestaciones +"' >" + value.nombre_tipo_prestaciones  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlTipoPrestaciones.empty();
	})
	
}

$("#id_tipo_prestaciones").on("focus",function(){
	$("#mensaje_id_tipo_prestaciones").text("").fadeOut("");
})

$("#id_participe").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})



