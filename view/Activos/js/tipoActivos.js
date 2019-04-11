$(document).ready(function(){

})

$("#frm_depreciacion").on("submit",function(event){
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=ActivosFijos&action=depreciacionAnual",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){
		
		if(datos.value == 1){
			swal({
		  		  title: "Depreciacion",
		  		  text: datos.mensaje,
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
		}
		
		if(datos.value == 2 ){
			swal({
		  		  title: "Depreciacion",
		  		  text: datos.mensaje,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
		}
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
	})
	
	event.preventDefault()
})


