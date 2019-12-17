$(document).ready(function(){
	
	load_productos_solicitud(1);
	load_temp_solicitud(1);
	//buscaProductosSolicitud(1);
	notificacionProductos();
	notificacionSolicitudes();
	load_estado_productos(1);
		
})

$("#btnAgregarProductos").on('click',function(){
	console.log('llego');
	buscaProductosSolicitud(1);
})

$("#mod_txtbuscar").on("keyup",function(){
	
	buscaProductosSolicitud();
	
})

function pone_espera(){

   $.blockUI({	   
		message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
		css: { 
		    border: 'none', 
		    padding: '15px', 
		    backgroundColor: '#000', 
		    '-webkit-border-radius': '10px', 
		    '-moz-border-radius': '10px', 
		    opacity: .5, 
		    color: '#fff',			           
		    }
   });
	
    setTimeout($.unblockUI, 3000); 
    
}

function buscaProductosSolicitud(pagina=1){
	
	let $busqueda = $("#mod_txtbuscar");
	let datos={peticion:'',busqueda:$busqueda.val(),page:pagina};
	let $cantidadrespuesta = $("#mod_cantidad_busqueda");
	let $tablaDatos = $("#div_tabla_productos");	
	
	$tablaDatos.html('');
	
	$.ajax({
		url:"index.php?controller=SolicitudCabeza&action=BuscaProductosSolicitud",
		dataType:"json",
		type:"POST",
		data:datos,
	}).done(function(x){		
		
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp; '+ x.valores.cantidad);
		
		$tablaDatos.html(x.tablaProductos);
		
	}).fail(function(xhr,status,error){
		
		let err = xhr.responseText;
		console.log(err)
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp;  0');
		let _diverror = ' <div class="col-lg-12 col-md-12 col-xs-12"> <div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
			_diverror +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            _diverror += '<h4>Aviso!!!</h4> <b>Error en conexion a la Base de Datos</b>';
            _diverror += '</div></div>';
            
        $tablaDatos.html(_diverror);
	})
}



//load_productos_solicitud(1)

function load_productos_solicitud(pagina){

	 var search=$("#buscador_productos").val();
     var con_datos={
				  action:'ajax',
				  page:pagina,
				  buscador:search
				  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=SolicitudCabeza&action=ajax_trae_productos',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#productos_inventario").html(x);
               $("#load_productos").html("");
               $("#tabla_productos").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });

}

function agregar_producto (id){
	
	var cantidad=document.getElementById('cantidad_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
	alert('Esto no es un numero');
	document.getElementById('cantidad_'+id).focus();
	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=SolicitudCabeza&action=insertar_producto',
        data: "id_productos="+id+"&cantidad="+cantidad,
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    	}
	});
}

function eliminar_producto (id){
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=SolicitudCabeza&action=eliminar_producto',
        data: "id_solicitud="+id,
    	 beforeSend: function(objeto){
    		$("#resultados").html("Mensaje: Cargando...");
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    	}
	});
}


function load_temp_solicitud(pagina){
	  
    var con_datos={
				  page:pagina
				  };
   
   $.ajax({
             beforeSend: function(objeto){},
             url: 'index.php?controller=SolicitudCabeza&action=trae_temporal',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados").html(x);
               $("#tabla_temporal").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

$('#razon_solicitud').on('focus',function(){
	$('#mensaje_razon_solicitud').fadeOut('slow')
})

$('#frm_solicitud_cabeza').submit(function( event ) {

	if(!document.getElementById("total_query_temp_solicitud")){
		swal({
	   		  title: "Solicitud",
	   		  text: "Agregue un registro a la solicitud",
	   		  icon: "error",
	   		  button: "Aceptar",
	   		  closeOnClickOutside:false,
	   		})
		return false
		
		}

		if(document.getElementById('razon_solicitud').value == '')
		{
			$('#razon_solicitud').notify("Ingrese una Observacion",{ position:"buttom left", autoHideDelay: 2000});
			return false
		}

		var parametros = $(this).serialize();

		
       	 $.ajax({
       		 beforeSend:function(){},
       		 url:'index.php?controller=MovimientosInv&action=inserta_solicitud',
       		 type:'POST',
       		 data:parametros+'&peticion=ajax',
       		 dataType: 'json',
       		 success: function(respuesta){
           		console.log(respuesta.mensaje);
       			 if(respuesta.status==1){
       				 $("#frm_solicitud_cabeza")[0].reset();
    	            		swal({
       	            		  title: "Solicitud",
       	            		  text: respuesta.mensaje,
       	            		  icon: "success",
       	            		  button: "Aceptar",
       	            		});
       					
       	                }else{
       	                	$("#frm_solicitud_cabeza")[0].reset();
       	                	swal({
       	              		  title: "Solicitud",
       	              		  text: respuesta.mensaje,
       	              		  icon: "warning",
       	              		  button: "Aceptar",
       	              		});
       	             }
       			load_temp_solicitud(1)
       			notificacionSolicitudes();
       			
       		 },
       		 error: function(jqXHR,estado,error){
       	        
       	        }
       	 })

		event.preventDefault(); 
		})
		
		
function notificacionProductos(){
	
	let $ObjNotificacion = $(".notifications-menu");
	let $cantidadNotificacion = $ObjNotificacion.find("a>span");
	let $ulDetalle = $ObjNotificacion.find("ul.dropdown-menu");	
	
	$.ajax({
		url:"index.php?controller=Productos&action=notificacionProductos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		if( x.respuesta == 1 ){    			
			$ulDetalle.append(x.htmlNotificacion);
			$cantidadNotificacion.text(x.cantidadNotificaciones);
		}
		
		//
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar solicitud .. notificacionProductos')
	})
	
}

function notificacionSolicitudes(){
	
	let $ObjNotificacion = $(".tasks-menu");
	let $cantidadNotificacion = $ObjNotificacion.find("a>span");
	let $ulDetalle = $ObjNotificacion.find("ul.dropdown-menu");	
	
	$.ajax({
		url:"index.php?controller=MovimientosInv&action=notificacionSolicitudes",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		if( x.respuesta == 1 ){    			
			$ulDetalle.append(x.htmlNotificacion);
			$cantidadNotificacion.text(x.cantidadNotificaciones);
		}
		
		//
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar solicitud .. notificacionSolicitudes')
	})
	
}


function load_estado_productos(pagina){

	var search=$("#search_estado_productos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_estado_productos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_estado_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=MovimientosInv&action=consulta_estado_productos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#estado_productos_registrados").html(x);
              $("#load_estado_productos").html("");
              $("#tabla_estado_productos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#estado_productos_registrados").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
           }
         });


	   }

