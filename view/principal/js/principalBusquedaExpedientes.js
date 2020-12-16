
$(document).ready(function(){
	
	cargaEntidadPatronal();
	document.getElementById('volver_buscar').style.display = 'none';
	
})


var id_participe;


$('#txtcedulaexpedientes').keypress(function(event){
	  if(event.keyCode == 13){
	    $('#load_buscar_participe').click();
	  }
	});




$("#load_buscar_participe").click(function() {
	
	load_buscar_participe(1);

	
	
});


function cargaEntidadPatronal(){
	
	let $ddlEntidad = $("#id_entidad_patronal");

	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=PrincipalBusquedasExpedientes&action=cargaEntidadPatronal",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlEntidad.empty();
		$ddlEntidad.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$ddlEntidad.append("<option value= " +value.id_entidad_patronal +" >" + value.nombre_entidad_patronal  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlEntidad.empty();
		$ddlEntidad.append("<option value='0' >--Seleccione--</option>");
		
	})
	
}


function load_buscar_participe(pagina){

	   var txtcedulaexpedientes=$("#txtcedulaexpedientes").val();
	   var txtNombresexpedientes=$("#txtNombresexpedientes").val();
	   var txtApellidosexpedientes=$("#txtApellidosexpedientes").val();
	   var txtCargoexpedientes=$("#txtCargoexpedientes").val();
	   var txtNumeroSolicitudexpedientes=$("#txtNumeroSolicitudexpedientes").val();
	   var txtFRegistroexpedientes=$("#txtFRegistroexpedientes").val();
	   var txtFBajaexpedientes=$("#txtFBajaexpedientes").val();
	   var id_estado_participes=$("#id_estado_participes").val();
	   var id_tipo_liquidación=$("#id_tipo_liquidación").val();
	   var id_entidad_patronal=$("#id_entidad_patronal").val();
	   
	   
	   
    var con_datos={
				  action:'ajax',
				  page:pagina,
				  txtcedulaexpedientes:txtcedulaexpedientes,
				  txtNombresexpedientes:txtNombresexpedientes,
				  txtApellidosexpedientes:txtApellidosexpedientes,
				  txtCargoexpedientes:txtCargoexpedientes,
				  txtNumeroSolicitudexpedientes:txtNumeroSolicitudexpedientes,
				  txtFRegistroexpedientes:txtFRegistroexpedientes,
				  txtFBajaexpedientes:txtFBajaexpedientes,
				  id_estado_participes:id_estado_participes,
				  id_tipo_liquidación:id_tipo_liquidación,
				  id_entidad_patronal:id_entidad_patronal
				  
	 };
		  
  $("#load1_buscar_participe").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load1_buscar_participe").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=PrincipalBusquedasExpedientes&action=BuscarParticipe',
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#participes_registrados").html(x);
              $("#load1_buscar_participe").html("");
              $("#tabla_participes").tablesorter();
              document.getElementById('tblBusquedaPrincipalExpedientes').style.display = 'none';
              document.getElementById('volver_buscar').style.display = 'block';
              document.getElementById('load_buscar_participe').style.display = 'none';
            
            },
           error: function(jqXHR,estado,error){
             $("#participes_registrados").html("Ocurrio un error al cargar la información de Participes..."+estado+"    "+error);
           }
         });


   }



function mostrar(){
	document.getElementById('tblBusquedaPrincipalExpedientes').style.display = 'block';
	document.getElementById('volver_buscar').style.display = 'none';
	document.getElementById('load_buscar_participe').style.display = 'block';
	
	}



