 $(document).ready( function (){
        		   
	 			   load_estados_inactivos(1);
	 			  load_estados_activos(1);
	 			 cargaEstadoEstMarital();
        		   
	   			});
 
 
 $("#frm_estados").on("submit",function(event){
		
		let _nombre_estado_marital = document.getElementById('nombre_estado_marital').value;
		let _id_estado = document.getElementById('id_estado').value;
		var _id_estado_marital = document.getElementById('id_estado_marital').value;
		var parametros = {nombre_estado_marital:_nombre_estado_marital,id_estado_marital:_id_estado_marital,id_estado:_id_estado}
		
		if(_id_estado == 0){
			$("#mensaje_id_estado").text("Seleccione un Estado").fadeIn("Slow");
			return false;
		}
		
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=EstadoMarital&action=InsertaEstadoMarital",
			type:"POST",
			dataType:"json",
			data:parametros
		}).done(function(datos){
			
			
		swal({
	  		  title: "Estado Marital",
	  		  text: datos.mensaje,
	  		  icon: "success",
	  		  button: "Aceptar",
	  		});
		
			
		}).fail(function(xhr,status,error){
			
			var err = xhr.responseText
			console.log(err);
			
		}).always(function(){
			$("#id_estado_marital").val(0);
			document.getElementById("frm_estados").reset();	
			consulta_estados_activos();
		})

		event.preventDefault()
	})
 
 
function consulta_estados_activos(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=EstadoMarital&action=consulta_estados_activos",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		
		$("#load_estados_activos").html(datos)	
		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
} 
 
 function consulta_estados_inactivos(_page = 1){
		
		var buscador = $("#buscador").val();
		$.ajax({
			beforeSend:function(){$("#divLoaderPage").addClass("loader")},
			url:"index.php?controller=EstadoMarital&action=consulta_estados_inactivos",
			type:"POST",
			data:{page:_page,search:buscador,peticion:'ajax'}
		}).done(function(datos){		
			
			$("#load_estados_inactivos").html(datos)	
			
			
		}).fail(function(xhr,status,error){
			
			var err = xhr.responseText
			console.log(err);
			
		}).always(function(){
			
			$("#divLoaderPage").removeClass("loader")
			
		})
		
	}
 



  
  function editEstados(id = 0){
		
		var tiempo = tiempo || 1000;
			
		$.ajax({
			beforeSend:function(){$("#divLoaderPage").addClass("loader")},
			url:"index.php?controller=EstadoMarital&action=editEstados",
			type:"POST",
			dataType:"json",
			data:{id_estado_marital:id}
		}).done(function(datos){
			
			if(!jQuery.isEmptyObject(datos.data)){
				
				var array = datos.data[0];		
				$("#nombre_estado_marital").val(array.nombre_estado_marital);			
				$("#id_estado_marital").val(array.id_estado_marital);
				$("#id_estado").val(array.id_estado);
				
				$("html, body").animate({ scrollTop: $(nombre_estado_marital).offset().top-120 }, tiempo);			
			}
			
			
			
		}).fail(function(xhr,status,error){
			
			var err = xhr.responseText
			console.log(err);
		}).always(function(){
			
			$("#divLoaderPage").removeClass("loader")
			consulta_estados_activos();
			
		})
		
		return false;
		
	}
  


  
  function borrarId(id){
		
		
		$.ajax({
			beforeSend:function(){$("#divLoaderPage").addClass("loader")},
			url:"index.php?controller=EstadoMarital&action=borrarId",
			type:"POST",
			dataType:"json",
			data:{id_estado_marital:id}
		}).done(function(datos){		
			
			if(datos.data > 0){
				
				swal({
			  		  title: "Estado Marital",
			  		  text: "Registro Eliminado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
						
			}
			
			
			
		}).fail(function(xhr,status,error){
			
			var err = xhr.responseText
			console.log(err);
		}).always(function(){
			
			$("#divLoaderPage").removeClass("loader")
			consulta_estados_activos();
		})
		
		return false;
	}

  
  function cargaEstadoEstMarital(){
		
		let $ddlEstado = $("#id_estado");
		
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=EstadoMarital&action=cargaEstadoEstMarital",
			type:"POST",
			dataType:"json",
			data:null
		}).done(function(datos){		
			
			$ddlEstado.empty();
			$ddlEstado.append("<option value='0' >--Seleccione--</option>");
			
			$.each(datos.data, function(index, value) {
				$ddlEstado.append("<option value= " +value.id_estado +" >" + value.nombre_estado  + "</option>");	
	  		});
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			$ddlEstado.empty();
		})
		
	}
  $("#id_estado").on("focus",function(){
		$("#mensaje_id_estado").text("").fadeOut("");
	})

	$("#nombre_estado_marital").on("keyup",function(){
		
		$(this).val($(this).val().toUpperCase());
	})
  
  
function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789";
    especiales = [8,37,39,46];
    tecla_especial = false
    for(var i in especiales){
    if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
     }

function load_estados_activos(pagina){

	   var search=$("#search_activos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_estados_activos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_estados_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=EstadoMarital&action=consulta_estados_activos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#estados_activos_registrados").html(x);
              $("#load_estados_activos").html("");
              $("#tabla_estados_activos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#estados_activos_registrados").html("Ocurrio un error al cargar la informacion de Estados Activos..."+estado+"    "+error);
           }
         });

	   }

function load_estados_inactivos(pagina){

	   var search=$("#search_inactivos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_estados_inactivos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_estados_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=EstadoMarital&action=consulta_estados_inactivos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#estados_inactivos_registrados").html(x);
              $("#load_estados_inactivos").html("");
              $("#tabla_estados_inactivos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#estados_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Estados Inactivos..."+estado+"    "+error);
           }
         });

	   }







