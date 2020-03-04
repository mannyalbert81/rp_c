
var GLOBALfecha = new Date();
var GLOBALyear = GLOBALfecha.getFullYear();
var GLOBALStringFecha = GLOBALfecha.getDate() + "/" + (GLOBALfecha.getMonth() +1) + "/" + GLOBALfecha.getFullYear();

document.write(GLOBALStringFecha);



$(document).ready(function(){
	

	cargaProducto();
	 cargaUsuarios();

	
	$('#fecha_desde').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
			  'yearrange': { minyear: 1950,
				  			 maxyear: GLOBALyear	
				  			},
  			  'clearIncomplete': true
			});
	
	$('#fecha_hasta').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
		      'yearrange': { minyear: 1950,
			  			 maxyear: GLOBALyear	
			  			},
	  		  'clearIncomplete': true
		});
	
	
	 $("#buscar").click(function(){

		
		 load_buscar_productos(1);
			
	});
});




function load_buscar_productos(pagina){
	
	var search=$("#search_buscar_productos").val();
	var validacionFecha = validarControles();
	console.log(validacionFecha);
	if(!validacionFecha){
		return false;
	}
	
	//iniciar variables
	 var con_id_productos=$("#id_productos").val();
	 var con_id_usuarios=$("#id_usuarios").val();
	 var con_fecha_desde=$("#fecha_desde").val();
	 var con_fecha_hasta=$("#fecha_hasta").val();


	  var con_datos={
			  id_productos:con_id_productos,
			  id_usuarios:con_id_usuarios,
			  fecha_desde:con_fecha_desde,
			  fecha_hasta:con_fecha_hasta,
			  action:'ajax',
			  page:pagina
			  };
	

	$("#load_buscar_productos").fadeIn('slow');
	$.ajax({
		url: 'index.php?controller=HistoricoProducto&action=index2&search='+search,
        type : "POST",
        async: true,			
		data: con_datos,
		 beforeSend: function(objeto){
		   $("#load_buscar_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            
		},
		success:function(data){
		
		     $("#productos_registrados").html(data);
             $("#load_buscar_productos").html("");
             $("#tabla_productos").tablesorter(); 
			
		}
	})
}

function validarControles(){
	
	console.log("INICIO DE FUNCION validarControles");
	
	var $fecha_desde = $("#fecha_desde"),
		$fecha_hasta = $("#fecha_hasta");
	
		
	/** validacion de fechas **/
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length == 0 || $fecha_hasta.val() == "" ) ){
		$fecha_hasta.val(GLOBALStringFecha);
	}
	
	if( ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") && ($fecha_desde.val().length == 0 || $fecha_desde.val() == "") ){
		$fecha_desde.val(GLOBALStringFecha);
	}
	
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") ){

		if ($.datepicker.parseDate('dd/mm/yy', $fecha_desde.val()) > $.datepicker.parseDate('dd/mm/yy', $fecha_hasta.val())) {
			$fecha_desde.notify("Fecha no puede ser mayor",{ 'autoHideDelay':1000,position:"buttom-left"});
			console.log("llego aca")
			return false;
		}
	}
	
	return true;
}



	   
	   
	   function cargaProducto(){
			
			let $ddlProductos = $("#id_productos");

			
			$.ajax({
				beforeSend:function(){},
				url:"index.php?controller=HistoricoProducto&action=cargaProducto",
				type:"POST",
				dataType:"json",
				data:null
			}).done(function(datos){		
				
				$ddlProductos.empty();
				$ddlProductos.append("<option value='0' >--Seleccione--</option>");
				
				$.each(datos.data, function(index, value) {
					$ddlProductos.append("<option value= " +value.id_productos +" >" + value.nombre_productos  + "</option>");	
		  		});
				
			}).fail(function(xhr,status,error){
				var err = xhr.responseText
				console.log(err)
				$ddlProductos.empty();
				$ddlProductos.append("<option value='0' >--Seleccione--</option>");
				
			})
			
		}
	   
	   function cargaUsuarios(){
			
			let $ddlUsuarios = $("#id_usuarios");

			
			$.ajax({
				beforeSend:function(){},
				url:"index.php?controller=HistoricoProducto&action=cargaUsuarios",
				type:"POST",
				dataType:"json",
				data:null
			}).done(function(datos){		
				
				$ddlUsuarios.empty();
				$ddlUsuarios.append("<option value='0' >--Seleccione--</option>");
				
				$.each(datos.data, function(index, value) {
					$ddlUsuarios.append("<option value= " +value.id_usuarios +" >" + value.nombre_usuarios +"&nbsp;"+ value.apellidos_usuarios  + "</option>" );	
		  		});
				
			}).fail(function(xhr,status,error){
				var err = xhr.responseText
				console.log(err)
				$ddlUsuarios.empty();
				$ddlUsuarios.append("<option value='0' >--Seleccione--</option>");
				
			})
			
		}
	   
	   

