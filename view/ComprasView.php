<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
      
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
   <?php include("view/modulos/links_css.php"); ?>
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
            
            <div class="box-body">
            
                <form action="<?php echo $helper->url("MovimientosInv","InsertarCompra"); ?>" method="post" >
          		 	 
              		 	 <div class="row">
              		 	 
              		 	 	
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="numero_compra" class="control-label">Numero Compra:</label>
                                    <input type="text" class="form-control" id="numero_compra" name="numero_compra" value=""  >
                                    <div id="mensaje_numero_compra" class="errores"></div>
                                 </div>
                             </div> 
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="fecha_compra" class="control-label">Fecha Compra:</label>
                                    <input type="date" class="form-control" id="fecha_compra" name="fecha_compra" value=""  >
                                    <div id="mensaje_numero_compra" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="cantidad_compra" class="control-label">Cantidad:</label>
                                    <input type="text" class="form-control" id="cantidad_compra" name="cantidad_compra" value=""  placeholder="cantidad.." >
                                    <div id="mensaje_cantidad_compra" class="errores"></div>
                                 </div>
                             </div> 
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="importe_compra" class="control-label">Importe:</label>
                                    <input type="text" class="form-control" id="importe_compra" name="importe_compra" value=""  placeholder="importe.." >
                                    <div id="mensaje_importe_compra" class="errores"></div>
                                 </div>
                             </div> 
                             
                          </div>
                          <div class="row">
                          
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
                                    <input type="text" class="form-control" id="numero_autorizacion_factura" name="numero_autorizacion_factura" value=""  placeholder="autorizacion" >
                                    <div id="mensaje_autorizacion_factura" class="errores"></div>
                                 </div>
                             </div> 
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="subtotal_12_compra" class="control-label">Subtotal 12%:</label>
                                    <input type="text" class="form-control" id="subtotal_12_compra" name="subtotal_12_compra" value=""  placeholder="subtotal" >
                                    <div id="mensaje_subtotal_12_compra" class="errores"></div>
                                 </div>
                             </div> 
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="subtotal_0_compra" class="control-label">Subtotal 0%:</label>
                                    <input type="text" class="form-control" id="subtotal_0_compra" name="subtotal_0_compra" value=""  placeholder="" >
                                    <div id="mensaje_subtotal_0_compra" class="errores"></div>
                                 </div>
                             </div> 
                             
                          </div>
                          <div class="row">
                          
                          	<div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="iva_compra" class="control-label">Iva:</label>
                                    <input type="text" class="form-control" id="iva_compra" name="iva_compra" value=""  placeholder="iva" >
                                    <div id="mensaje_iva_compra" class="errores"></div>
                                 </div>
                             </div> 
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="descuento_compra" class="control-label">Descuento:</label>
                                    <input type="text" class="form-control" id="descuento_compra" name="descuento_compra" value=""  placeholder="descuento" >
                                    <div id="mensaje_descuento_compra" class="errores"></div>
                                 </div>
                             </div> 
                              <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="estado_compra" class="control-label">Estado:</label>
                                    <input type="text" class="form-control" id="estado_compra" name="estado_compra" value=""  placeholder="estado" >
                                    <div id="mensaje_estado_compras" class="errores"></div>
                                 </div>
                             </div> 
                          </div>
                      
                     	<div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
            	                  <a class="btn btn-danger" href="<?php  echo $helper->url("MovimientosInv","compras"); ?>">CANCELAR</a>
        	                    </div>
    	        		    </div>
    	        		    
            		    </div>
          		 	
          		 	</form>
          
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
                	<table class="table" id="table_ins_productos">
                     <thead>
                        <tr>
                          <th>Codigo</th>
                          <th>Nombre </th>
                          <th>Cantidad </th>
                          <th>Un. Medida</th>
                        </tr>
                      </thead>
                      <tbody> 
                      <tr>
                      	<td></td> <td></td> <td></td> <td></td>
                      </tr>                  
                        
                      </tbody>
                    </table>
                    <span style="float:right">
                    	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#agregar_nuevo">
                			Agregar Nuevo
              			</button>
              		</span>
                	
                
                </div>
                </div>
            </section>
    		
    		
    		
    		
       <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Orden Compra</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">             
            	<table class="table" id="makeEditable">
                 <thead>
                    <tr>
                      <th></th>
                      <th>Codigo</th>
                      <th>Nombre </th>
                      <th>Cantidad </th>
                      <th>Un. Medida</th>
                    </tr>
                  </thead>
                  <tbody>                   
                    <tr class="active">
                      <td>Active</td>
                      <td>Activeson</td>
                      <td>Active</td>
                      <td>Activeson</td>
                      <td>act@example.com</td>
                    </tr>
                  </tbody>
                </table>
                <span style="float:right"><button id="but_add" class="btn btn-danger">Add New Row</button></span>
            	
            
            </div>
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
    
    
   <?php include("view/modulos/links_js.php"); ?>
   
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="view/bootstrap/otros/uitable/bootstable.js"></script>
  
 
   
   
   <!-- para el autocompletado -->
    
 
 <!-- funciones javascript para la pagina -->
<script type="text/javascript">
$('#agregar_nuevo').on('show.bs.modal', function (event) {
	load_productos(1);
	  var modal = $(this)
	  modal.find('.modal-title').text('Listado Productos')

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

function agregar_producto(id){

	var cantidad=document.getElementById('cantidad_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
	alert('Esto no es un numero');
	document.getElementById('cantidad_'+id).focus();
	return false;
	}
	var $tab_en_edic = $("#table_ins_productos");  //Table to edit
    var $filas = $tab_en_edic.find('tbody tr');
    
    if ($filas.length==0) {
        //No hay filas de datos. Hay que crearlas completas
        var $row = $tab_en_edic.find('thead tr');  //encabezado       
        var $cols = $row.find('th');  //lee campos
        
        //construye html
        var htmlDat = '';
        $cols.each(function() {
            if ($(this).attr('name')=='buttons') {
                //Es columna de botones
                htmlDat = htmlDat + colEdicHtml;  //agrega botones
            } else {
                htmlDat = htmlDat + '<td></td>';
            }
        });
        $tab_en_edic.find('tbody').append('<tr>'+htmlDat+'</tr>');
    } else {
        //Hay otras filas, podemos clonar la última fila, para copiar los botones
        var $ultFila = $tab_en_edic.find('tr:last');
        $ultFila.clone().appendTo($ultFila.parent()); 
        $tab_en_edic.find('tr:last').attr('id','editing'); 
        $ultFila = $tab_en_edic.find('tr:last');
        var $cols = $ultFila.find('td');  //lee campos
        
        
        $cols.each(function() {
            if ($(this).attr('name')=='buttons') {
                //Es columna de botones
            } else {
                var div = '<div style="display: none;"></div>';  //guarda contenido
                var input = '<input class="form-control input-sm"   value="">';

                $(this).html(div + input);  //limpia contenido
            }
        });
         $ultFila.find('td:last').html(saveColHtml);

    }
	
	/*$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=insertar_producto',
        data: "id_productos="+id+"&cantidad="+cantidad,
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	 /* },
        success: function(datos){
    		$("#resultados").html(datos);
    	}
	});*/
	
}
</script>

<script type="text/javascript">
$(document).ready(function(){
	
	$('#makeEditable').SetEditable({
		$addButton: $('#but_add'),
		columnsEd: null,
		onEdit: function() {},
		onDelete: function() {},
		onBeforeDelete: function() {},
		onAdd: function() {}
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
     

             
 	
  </body>
</html>

 