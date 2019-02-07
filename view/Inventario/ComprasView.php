<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
      
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
   <?php include("view/modulos/links_css.php"); ?>
   <link href="//oss.maxcdn.com/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css" rel="stylesheet"></link
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  
    
   
  </head>

  <body class="hold-transition skin-blue fixed sidebar-mini">

 <?php  $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
  ?>
    
    
    <div class="wrapper">

  <header class="main-header">
  
      <?php include("view/modulos/logo.php"); ?>
      <?php include("view/modulos/head.php"); ?>	
    
  </header>

   <aside class="main-sidebar">
    <section class="sidebar">
     <?php include("view/modulos/menu_profile.php"); ?>
      <br>
     <?php include("view/modulos/menu.php"); ?>
    </section>
  </aside>

  <div class="content-wrapper">
   		<section class="content-header">
            <h1>
            
            	<small><?php echo $fecha; ?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Usuarios</li>
            </ol>
        </section>
        
        <!-- comienza diseño controles usuario -->
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Registrar Compras</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            <form id="frm_guardacompra" action="<?php echo $helper->url("MovimientosInv","inserta_compras"); ?>" method="post" >
            <div class="box-body">
            
                
          		 	 
              		 	 <div class="row">
              		 	 
              		 	 	<div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="numero_compra" class="control-label">Digite CI/Nombre Proveedor:</label>
                                    <input type="text" class="form-control" id="proveedor" name="proveedor" value=""  >
                                    <input type="hidden" id="id_proveedor" name="id_proveedor" value=""  >
                                    <input type="hidden"  id="cantidad_compra" name="cantidad_compra" value="0"  >
                                    <div id="mensaje_proveedor" class="errores"></div>
                                 </div>
                             </div> 
                             
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group" id="datos_proveedor" style="display:none;">
                                	<label for="numero_compra" class="control-label">Proveedor:</label>
                                    <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value=""  >                                   
                                    <div id="mensaje_proveedor" class="errores"></div>
                                 </div>
                             </div> 
                             
                         </div>
              		 	 
              		 	  <div class="row">	                            
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="fecha_compra" class="control-label">Fecha Compra:</label>
                                    <input type="text" class="form-control" id="fecha_compra" name="fecha_compra" value=""  >
                                    <div id="mensaje_numero_compra" class="errores"></div>
                                 </div>
                             </div>
                             
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="numero_factura_compra" class="control-label">No Factura:</label>
                                    <input type="text" class="form-control" id="numero_factura_compra" name="numero_factura_compra" value=""  placeholder="no. factura.." >
                                    <div id="mensaje_numero_factura" class="errores"></div>
                                 </div>
                             </div> 
                             
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="numero_autorizacion_factura" class="control-label">No Autorización:</label>
                                    <input type="text" class="form-control" id="numero_autorizacion_factura" name="numero_autorizacion_factura" value="" maxlength="50" placeholder="autorizacion" >
                                    <div id="mensaje_autorizacion_factura" class="errores"></div>
                                 </div>
                             </div>
                             
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="estado_compra" class="control-label">Estado:</label>
                                	<select id="estado_compra" name="estado_compra" class="form-control">
                                		<option value="pagada">PAGADA</option>
                                		<option value="pendiente">PENDIENTE</option>
                                	</select>                                    
                                    <div id="mensaje_estado_compras" class="errores"></div>
                                 </div>
                             </div> 
                             
                             
                             
                          </div>
                         
                         
        			</div>
      			</div>
      			
    		</section>
    		
    		<section class="content">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Detalles Compra</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                    
                  </div>
                </div>
                
                <div class="box-body">             
                	
                    <span style="float:right">
                    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#mod_agregar_producto">
                			<span></span>Agregar Producto
              			</button>
                    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#agregar_nuevo">
                			Agregar Nuevo
              			</button>
              		</span>
              	</div>
                	
                 <div class="box-body">
                      <div id="resultados" ></div>
                      
                      <!-- parte inferior de subtotales -->
                      <div class="row pull-left" id="resultados_totales">
                      	
                      </div>
                          
                          <div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" form="frm_guardacompra" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
            	                  <a class="btn btn-danger" href="<?php  echo $helper->url("MovimientosInv","cancelarcompra"); ?>">CANCELAR</a>
        	                    </div>
    	        		    </div>
    	        		    
            		    </div>
                   </div>
                
                </form>
                </div>
            </section>
    		
    		
    		
    		
     
    	
    
  </div>
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- para los modales -->
 
<div class="modal fade" id="agregar_nuevo">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Default Modal</h4>
          </div>
          <div class="modal-body">
          	<div class="pull-right" style="margin-right:15px;">
				<input type="text" value="" class="form-control" id="search_productos" name="search_productos" onkeyup="load_productos(1)" placeholder="search.."/>
			</div>
          	
			<div id="load_productos_registrados" ></div>	
			<div id="productos_registrados"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="mod_agregar_producto">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">AGREGAR PRODUCTO</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_guardar_producto" name="frm_guardar_producto">
          	
          	<div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Grupos</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_id_grupo" name="mod_id_grupo" required>
					<option value="0">-- Selecciona estado --</option>					
				  </select>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Unidad Medida</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_unidad_medida" name="mod_unidad_medida" required>
					<option value="0">-- Selecciona estado --</option>					
				  </select>
				</div>
			  </div>
			  	
			  <div class="form-group">
				<label for="codigo" class="col-sm-3 control-label">Código</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_codigo_producto" name="mod_codigo_producto" placeholder="Código del producto" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Marca</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_marca_producto" name="mod_marca_producto" placeholder="Código del producto" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Nombre</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="mod_nombre_producto" name="mod_nombre_producto" placeholder="Nombre del producto" required maxlength="20" ></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Descripcion</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="mod_descripcion_producto" name="mod_descripcion_producto" placeholder="Descripcion del producto" required maxlength="20" ></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Ult. precio</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_precio_producto" name="mod_precio_producto" placeholder="Precio de venta del producto" maxlength="10" required  title="Ingresa sólo números con 0 ó 2 decimales" />
				  
				</div>
			  </div>
			  
          	</form>
          	<!-- termina el formulario modal productos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" form="frm_guardar_producto" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
     
   <?php include("view/modulos/links_js.php"); ?>
 
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="view/bootstrap/otros/uitable/bootstable.js"></script>
  
  <script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
  
  <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>    
     <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
     <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.numeric.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    
    <script src="//oss.maxcdn.com/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    
    <!-- <script src="view/bootstrap/otros/validate/jquery.validate.js"></script> -->
   
  
   
   <!-- para el autocompletado -->
    
<script type="text/javascript" >
    // cada vez que se cambia el valor del combo
    $(document).ready(function(){
		//swal('hola');
    	load_temp_solicitud(1);
    }); 
</script>
 
 <!-- funciones javascript para la pagina -->
<script type="text/javascript">
$('#agregar_nuevo').on('show.bs.modal', function (event) {
	load_productos(1);
	  var modal = $(this)
	  modal.find('.modal-title').text('Listado Productos')

	});

$('#mod_agregar_producto').on('show.bs.modal', function (event) {
		carga_grupos();
		carga_unidad_medida()
	  var modal = $(this);

	});

function load_productos(pagina){

	var search=$("#search_productos").val();
   
    $("#load_productos_registrados").fadeIn('slow');
    
    $.ajax({
            beforeSend: function(objeto){
              $("#load_productos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=MovimientosInv&action=consulta_productos&search='+search,
            type: 'POST',
            data: {action:'ajax', page:pagina},
            success: function(x){
              $("#productos_registrados").html(x);
              $("#load_productos_registrados").html("");
              $("#tabla_productos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
           }
     });
}



function agregar_producto (id)
{

	var cantidad=document.getElementById('cantidad_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
	alert('Esto no es un numero');
	document.getElementById('cantidad_'+id).focus();
	return false;
	}
	var precio = document.getElementById('pecio_producto_'+id).value;
	if (isNaN(precio))
	{
		document.getElementById('pecio_producto_'+id).focus();
		swal('Esto no es un numero');
	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=insertar_temporal_compras',
        data: "id_productos="+id+"&cantidad="+cantidad+"&precio_u="+precio,
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    		pone_cantidad();
    		carga_resultados_temp();
    	}
	});
}

	function eliminar_producto (id)
{
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=eliminar_producto',
        data: "id_temp_compras="+id,
    	 beforeSend: function(objeto){
    		$("#resultados").html("Mensaje: Cargando...");

    		pone_cantidad();

    		carga_resultados_temp();
    		
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
             beforeSend: function(objeto){
               
             },
             url: 'index.php?controller=MovimientosInv&action=trae_temporal',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados").html(x);
               pone_cantidad();
               $("#tabla_temporal").tablesorter(); 
               carga_resultados_temp();
               
             },
            error: function(jqXHR,estado,error){
              $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });


	   }
	   
function carga_resultados_temp(pagina){
	  
     var con_datos={
				  page:pagina
				  };
   
   $.ajax({
             beforeSend: function(objeto){
               
             },
             url: 'index.php?controller=MovimientosInv&action=resultados_temp',
             type: 'POST',
             data: {},
             success: function(x){
            	 $("#resultados_totales").html();
            	 $("#resultados_totales").html(x);
 				//$("#resultados_totales").append(x);
                 
             },
            error: function(jqXHR,estado,error){
              $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });


}

function carga_grupos(){
	  
    $.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=Grupos&action=carga_grupos',
        type: 'POST',
        data: {},
        dataType:'json',
        success: function(respuesta){
        	$("#mod_id_grupo").empty()
        	$("#mod_id_grupo").append("<option value= \"0\" >--Seleccione--</option>");
        	$.each(respuesta, function(index, value) {
 		 			$("#mod_id_grupo").append("<option value= " +value.id_grupos +" >" + value.nombre_grupos  + "</option>");	
            		 });            
        },
        error: function(jqXHR,estado,error){
         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
        }
    });
    

}

function carga_unidad_medida(){

	  $.ajax({
	        beforeSend: function(objeto){
	          
	        },
	        url: 'index.php?controller=Grupos&action=carga_unidadmedida',
	        type: 'POST',
	        data: {},
	        dataType:'json',
	        success: function(respuesta){
	        	$("#mod_unidad_medida").empty()
	        	$("#mod_unidad_medida").append("<option value= \"0\" >--Seleccione--</option>");
	        	$.each(respuesta, function(index, value) {
			 			$("#mod_unidad_medida").append("<option value= " +value.id_unidad_medida +" >" + value.nombre_unidad_medida  + "</option>");	
	        		 });  
	            
	        },
	       error: function(jqXHR,estado,error){
	         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	       }
	  });


}


function pone_cantidad(){
	//console.log('ingreso');
	//console.log($('#total_query_compras').length);
	if ($('#total_query_compras').length) {
		$('#cantidad_compra').val($('#total_query_compras').val());
	}
}


</script>

<script type="text/javascript">
$(document).ready(function(){

	$( "#proveedor" ).autocomplete({

		source: "<?php echo $helper->url("MovimientosInv","busca_proveedor"); ?>",
		minLength: 4,
        select: function (event, ui) {
           // Set selection          
           $('#id_proveedor').val(ui.item.id);
           $('#proveedor').val(ui.item.value); // save selected id to input
           $('#nombre_proveedor').val(ui.item.nombre);
           $('#datos_proveedor').show();
           //console.log(ui.item.nombre);
           return false;
        },
        focus: function(event, ui) { 
            var text = ui.item.value; 
            $('#proveedor').val();            
            return false; 
        } 
	}).focusout(function() {
		$.ajax({
			url:'<?php echo $helper->url("MovimientosInv","busca_proveedor"); ?>',
			type:'POST',
			dataType:'json',
			data:{term:$('#proveedor').val()}
		}).done(function(respuesta){
			console.log(respuesta[0].id);
			if(respuesta[0].id>0){				
				$('#id_proveedor').val(respuesta[0].id);
	           $('#proveedor').val(respuesta[0].value); // save selected id to input
	           $('#nombre_proveedor').val(respuesta[0].nombre);
	           $('#datos_proveedor').show();
			}else{$('#datos_proveedor').hide(); $('#id_proveedor').val('0');  $('#proveedor').val('').focus();}
		});
	});
});


	
</script>


<script type="text/javascript">


$(document).ready(function(){
	
            var cedula_usuarios = $("#cedula_usuarios").val();

            if(cedula_usuarios>0){

             }else{
       		
			$( "#cedula_usuarios" ).autocomplete({

				source: "<?php echo $helper->url("Usuarios","AutocompleteCedula"); ?>",
  				minLength: 4
			});

			$("#cedula_usuarios").focusout(function(){
				validarcedula();
				$.ajax({
					url:'<?php echo $helper->url("Usuarios","AutocompleteDevuelveNombres"); ?>',
					type:'POST',
					dataType:'json',
					data:{cedula_usuarios:$('#cedula_usuarios').val()}
				}).done(function(respuesta){

					$('#id_usuarios').val(respuesta.id_usuarios);					
					$('#nombre_usuarios').val(respuesta.nombre_usuarios);
					$('#apellidos_usuarios').val(respuesta.apellidos_usuarios);
					$('#usuario_usuarios').val(respuesta.usuario_usuarios);
					$('#fecha_nacimiento_usuarios').val(respuesta.fecha_nacimiento_usuarios);
					$('#celular_usuarios').val(respuesta.celular_usuarios);
					$('#telefono_usuarios').val(respuesta.telefono_usuarios);
					$('#correo_usuarios').val(respuesta.correo_usuarios);					
					$('#codigo_clave').val(respuesta.clave_n_claves);

					if(respuesta.id_rol>0){
						$('#id_rol_principal option[value='+respuesta.id_rol+']').attr('selected','selected');
						}

					if(respuesta.estado_usuarios>0){
						$('#id_estado option[value='+respuesta.estado_usuarios+']').attr('selected','selected');
						}

					if(respuesta.caduca_claves=='t'){
						
						$('#caduca_clave').attr('checked','checked');
					}

					if( typeof respuesta.clave_n_usuarios !== "undefined"){
						$('#clave_usuarios').val(respuesta.clave_n_claves).attr('readonly','readonly');
						$('#clave_usuarios_r').val(respuesta.clave_n_claves).attr('readonly','readonly');
						$('#lbl_cambiar_clave').text("Cambiar Clave:  ");
						$('#cambiar_clave').show();
							
							
						}

					

                    if(respuesta.privilegios.length>0){
                    	 $('#lista_roles').empty();
                    	 $.each(respuesta.privilegios, function(k, v) {
                    		 $('#lista_roles').append("<option value= " +v.id_rol +" >" + v.nombre_rol  + "</option>");
                 		   
                    	});
					}
					
					
					
				
    			}).fail(function(respuesta) {

    				$('#id_usuarios').val("");
					$('#nombre_usuarios').val("");
					$('#apellidos_usuarios').val("");
					$('#usuario_usuarios').val("");
					$('#fecha_nacimiento_usuarios').val("");
					$('#celular_usuarios').val("");
					$('#telefono_usuarios').val("");
					$('#correo_usuarios').val("");
					$('#clave_usuarios').val("");
					$('#clave_usuarios_r').val("");
					    			    
    			  });
				 
				
			});  
            }

            
            
			
		});


 </script>
 
 <script type="text/javascript">

$(document).ready(function(){
/*
	$("#frm_guardacompra").validate({
        event: "blur",
        rules: {'numero_factura_compra': "required"},
        messages: {'numero_factura_compra': "Por favor indica tu nombre",'email': "Por favor, indica una direcci&oacute;n de e-mail v&aacute;lida",'message': "Por favor, dime algo!"},
        debug: true,
        errorElement: "label",
        submitHandler: function(form){
            $("#alert").show();
            $("#alert").html("<img src='images/ajax-loader.gif' style='vertical-align:middle;margin:0 10px 0 0' /><strong>Enviando mensaje...</strong>");
            setTimeout(function() {
                $('#alert').fadeOut('slow');
            }, 5000);
            
            $.ajax({
                type: "POST",
                url:"send.php",
                data: "name="+escape($('#name').val())+"&email="+escape($('#email').val())+"&message="+escape($('#message').val()),
                success: function(msg){
                    $("#alert").html(msg);
                    document.getElementById("name").value="";
                    document.getElementById("email").value="";
                    document.getElementById("message").value="";
                    setTimeout(function() {
                        $('#alert').fadeOut('slow');
                    }, 5000);
 
                }
            });
        }
    });
*/

/*$('#frm_guardacompra').bootstrapValidator({
	 
	 message: 'Este valor no es valido',

	 feedbackIcons: {

		 valid: 'glyphicon glyphicon-ok',

		 invalid: 'glyphicon glyphicon-remove',

		 validating: 'glyphicon glyphicon-refresh'

	 },

	 fields: {

		 numero_factura_compra: {

			 validators: {

				 notEmpty: {

					 message: 'ingrese un numero de factura'

				 }

			 }

		 },

		 password: {

			 validators: {

				 notEmpty: {

					 message: 'La contraseña es requerida'

				 }

			 }

		 }

	 }

});*/


 
 $( "#frm_guardacompra" ).submit(function( event ) {

	var dapaso = true;
	 if($('#cantidad_compra').val()=='' || $('#cantidad_compra').val()==0)
	 {
		
     	swal({
   		  title: "Compras",
   		  text: "No ha ingresado productos a la compra",
   		  icon: "warning",
   		  button: "Aceptar",
   		});
		 dapaso = false;
	 }
	 if(dapaso){		 
		 
    	 var parametros = $(this).serialize();
    	 $.ajax({
    		 beforeSend:function(){},
    		 url:'index.php?controller=MovimientosInv&action=insertacompra',
    		 type:'POST',
    		 data:parametros,
    		 dataType: 'json',
    		 success: function(respuesta){
        		console.log(respuesta);
    			 if(respuesta.success==1){
    				 $("#frm_guardacompra")[0].reset();
 	            		swal({
    	            		  title: "Compra",
    	            		  text: respuesta.mensaje,
    	            		  icon: "success",
    	            		  button: "Aceptar",
    	            		});
    					
    	                }else{
    	                	$("#frm_guardacompra")[0].reset();
    	                	swal({
    	              		  title: "Compra",
    	              		  text: respuesta.mensaje,
    	              		  icon: "warning",
    	              		  button: "Aceptar",
    	              		});
    	             }
    			 load_temp_solicitud(1);
    		 },
    		 error: function(jqXHR,estado,error){
    	         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
    	        }
    	 })
	 }
	 event.preventDefault(); 
	});

 $( "#frm_guardar_producto" ).submit(function( event ) {
	//console.log('ingresa->1\n');
	var parametros = $(this).serialize();	
	$.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=Productos&action=inserta_producto',
        type: 'POST',
        data: parametros,
        dataType:'json',
        success: function(respuesta){

            if(respuesta.success==1){
            	$("#frm_guardar_producto")[0].reset();
            	swal({
            		  title: "Productos",
            		  text: respuesta.mensaje,
            		  icon: "success",
            		  button: "Aceptar",
            		});
				
                }else{
                	$("#frm_guardar_producto")[0].reset();
                	swal({
              		  title: "Productos",
              		  text: respuesta.mensaje,
              		  icon: "warning",
              		  button: "Aceptar",
              		});
                    }
        	     
        },
        error: function(jqXHR,estado,error){
         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
        }
    });
	 
	event.preventDefault();	
	  
	});

 $("#numero_factura_compra").inputmask('999-999-999999999',{placeholder: ""});

 $("#numero_autorizacion_factura").inputmask('9999999999',{placeholder: ""});

 //$("#mod_precio_producto").inputmask('currency',{rightAlign: true  });
 $("#mod_precio_producto").inputmask({
	 alias: "decimal",	
	 digits: 2,
	 digitsOptional: true,
	 groupSeparator: ",",
	 autoGroup:true,
	 placeholder: "",
	 allowMinus: false,
	 integerDigits: '5',
	 defaultValue: "00.00",
	 prefix: "$"
	 });

 /*$("#mod_precio_producto").inputmask({ 
	 alias : "currency", mask : "$ 00.00" 
		 });*/

 $("#fecha_compra").inputmask({
	 alias: "date",
	 yearrange: { 'minyear': '1990','maxyear': '<?php echo date('Y')?>'},	 
	 placeholder: "dd/mm/yyyy"
	 });

 
 })
 </script>



             
 	
  </body>
</html>

 