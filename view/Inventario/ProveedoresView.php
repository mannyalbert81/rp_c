    
    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
      
    
	
    
	</head>
 
    <body class="hold-transition skin-blue fixed sidebar-mini">
    
     <?php
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
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
        <li class="active">Productos</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Proveedores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
          
        
        <form action="<?php echo $helper->url("Proveedores","InsertaProveedores"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		    
                        		    
                        		
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_proveedores" class="control-label">Nombre Proveedores</label>
                                                          <input type="text" class="form-control" id="nombre_proveedores" name="nombre_proveedores" value="<?php echo $resEdit->nombre_proveedores; ?>"  placeholder="Nombre Proveedores" >
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_nombre_proveedores" class="errores"></div>
                                    </div>
                        		    </div>   
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="identificacion_proveedores" class="control-label">Ruc Proveedores</label>
                                                          <input type="text" class="form-control" id="identificacion_proveedores" name="identificacion_proveedores" value="<?php echo $resEdit->identificacion_proveedores; ?>"  placeholder="Ruc Proveedores" onKeyPress="return numeros(event)">
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_identificacion_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="contactos_proveedores" class="control-label">Contactos Proveedores</label>
                                                          <input type="text" class="form-control" id="contactos_proveedores" name="contactos_proveedores" value="<?php echo $resEdit->contactos_proveedores; ?>"  placeholder="Contactos Proveedores">
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_contactos_proveedores" class="errores"></div>
                                    </div>
                        		    </div>    
                        		    
                        		     <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="direccion_proveedores" class="control-label">Dirección Proveedores</label>
                                                          <input type="text" class="form-control" id="direccion_proveedores" name="direccion_proveedores" value="<?php echo $resEdit->direccion_proveedores; ?>"  placeholder="Dirección Proveedores">
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_direccion_proveedores" class="errores"></div>
                                    </div>
                        		    </div>                     			
                        	
                        	    </div>
                    			
                    			
                    			<div class="row">
                    			                   			
                    			    
                        		<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                        <label>Telefono:</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                          </div>
                                          <input type="text" class="form-control" size="7" maxlength="7" id="telefono_proveedores" name="telefono_proveedores" value="<?php echo $resEdit->telefono_proveedores; ?>"  data-inputmask='"mask": "(99) 9999-999"' data-mask onKeyPress="return numeros(event)" onkeypress="return aceptNum(event)" onpaste="return false;">
                                        </div>
                                        <!-- /.input group -->
                                  </div>
                                    </div>
                        		    
                        		    
                        		    <div class="col-lg-3 col-xs-12 col-md-3">
                        		     <div class="form-group">
                                                <label for="email_proveedores" class="control-label">Email Proveedores</label>
                                                          <input type="email" class="form-control" id="email_proveedores" name="email_proveedores" value="<?php echo $resEdit->email_proveedores; ?>"  placeholder="Email Proveedores" >
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_email_proveedores" class="errores"></div>
                                  
                                    </div>
                                 
                        		    
                    		        </div>
                    		         <div class="col-lg-3 col-xs-12 col-md-3">
                        		     <div class="form-group">
                                                <label for="fecha_nacimiento_proveedores" class="control-label">Fecha Nacimiento Proveedores</label>
                                                          <input type="text" class="form-control" id="fecha_nacimiento_proveedores" name="fecha_nacimiento_proveedores" value="<?php echo $resEdit->fecha_nacimiento_proveedores; ?>"  placeholder="Fecha Proveedores">
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="<?php echo $resEdit->id_proveedores; ?>" class="form-control"/>
					                                      <div id="mensaje_fecha_nacimiento_proveedores" class="errores"></div>
                                  
                                    </div>
                                 
                        		    
                    		        </div>
                        		    
                        	
                    	
                    	
                    	
                        		    </div>
                    			
                                 
                                
                    		     <?php } } else {?>
                    		    
                    		   
								 <div class="row">
                        		    
                        		    
                        		 		
                        			
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_proveedores" class="control-label">Nombre Proveedores</label>
                                                          <input type="text" class="form-control" id="nombre_proveedores" name="nombre_proveedores" value=""  placeholder="Nombre Proveedores">
                                                          <input type="hidden" name="id_proveedores" id="id_proveedores" value="0" class="form-control"/>
					        
                                                           <div id="mensaje_nombre_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="identificacion_proveedores" class="control-label">Ruc Proveedores</label>
                                                          <input type="text" class="form-control" id="identificacion_proveedores" name="identificacion_proveedores" value=""  placeholder="Ruc Proveedores" onKeyPress="return numeros(event)">
                                                           <div id="mensaje_identificacion_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="contactos_proveedores" class="control-label">Contactos Proveedores</label>
                                                          <input type="text" class="form-control" id="contactos_proveedores" name="contactos_proveedores" value=""  placeholder="Contactos Proveedores">
                                                           <div id="mensaje_contactos_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		       <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="direccion_proveedores" class="control-label">Dirección Proveedores</label>
                                                          <input type="text" class="form-control" id="direccion_proveedores" name="direccion_proveedores" value=""  placeholder="Dirección Proveedores">
                                                           <div id="mensaje_direccion_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		
                		    
									</div>
									
									<div class="row">
									
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                        <label>Telefono:</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                          </div>
                                          <input type="text" class="form-control" id="telefono_proveedores" name="telefono_proveedores" size="7" maxlength="7" value=""  data-inputmask='"mask": "(99) 9999-999"' data-mask  onKeyPress="return numeros(event)">
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                        		    </div>
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="email_proveedores" class="control-label">Email Proveedores</label>
                                                          <input type="email" class="form-control" id="email_proveedores" name="email_proveedores" value=""  placeholder="Email Proveedores" >
                                                           <div id="mensaje_email_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		     <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="fecha_nacimiento_proveedores" class="control-label">Fecha Proveedores</label>
                                                          <input type="date" class="form-control" id="fecha_nacimiento_proveedores" name="fecha_nacimiento_proveedores" value=""  placeholder="Fecha Proveedores">
                                                           <div id="mensaje_fecha_nacimiento_proveedores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    
                        		    
                        		 
                        			
									
									
									
									</div>
                		    
                    	
                    			
                                 	                     	           	
                    		     <?php } ?>
                    		    <br>  
                    		    <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
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
          <h3 class="box-title">Listado de Proveedores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        
       <div class="ibox-content">  
      <div class="table-responsive">
        
      
      
  <table  class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre</th>
                          <th>Identificación</th>
                          <th>Contactos</th>
                          <th>Dirección</th>
                          <th>Teléfono</th>
                          <th>Email</th>
                          <th>Fecha</th>
                          <th></th>
                          <th></th>
                         
                        </tr>
                      </thead>

                      <tbody>
    					<?php $i=0;?>
    						<?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
    						<?php $i++;?>
            	        		<tr>
            	                   <td > <?php echo $i; ?>  </td>
            		               <td > <?php echo $res->nombre_proveedores; ?>     </td> 
            		               <td > <?php echo $res->identificacion_proveedores; ?>   </td>
            		               <td > <?php echo $res->contactos_proveedores; ?>   </td>
            		               <td > <?php echo $res->direccion_proveedores; ?>   </td>
            		               <td > <?php echo $res->telefono_proveedores; ?>   </td>
            		               <td > <?php echo $res->email_proveedores; ?>   </td>
            		               <td > <?php echo $res->fecha_nacimiento_proveedores; ?>   </td>
            		              
            		           	   <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("Proveedores","index"); ?>&id_proveedores=<?php echo $res->id_proveedores; ?>" class="btn btn-warning" style="font-size:65%;" data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("Proveedores","borrarId"); ?>&id_proveedores=<?php echo $res->id_proveedores; ?>" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
            			                </div>
            			                
            		               </td>
            		    		</tr>
            		    		
            		        <?php } } ?>
                    	
    					                    				  	

                      </tbody>
                    </table>
                    
    
       
        </div>
         </div>
        
        
        </div>
        </div>
        </section>
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	
	
    
           
	
	
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
	  
	  
    
           <script>
           // Campos Vacíos
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_proveedores = $("#nombre_proveedores").val();
		    	var identificacion_proveedores = $("#identificacion_proveedores").val();
		    	var contactos_proveedores = $("#contactos_proveedores").val();
		    	var direccion_proveedores = $("#direccion_proveedores").val();
		    	var telefono_proveedores = $("#telefono_proveedores").val();
		    	var email_proveedores = $("#email_proveedores").val();
		    	var fecha_nacimiento_proveedores = $("#fecha_nacimiento_proveedores").val();
		    	
		    	
		    	
		    	if (nombre_proveedores == 0)
		    	{
			    	
		    		$("#mensaje_nombre_proveedores").text("Introduzca Un Nombre");
		    		$("#mensaje_nombre_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (identificacion_proveedores == "")
		    	{
			    	
		    		$("#mensaje_identificacion_proveedores").text("Introduzca Un Ruc");
		    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (contactos_proveedores == "")
		    	{
			    	
		    		$("#mensaje_contactos_proveedores").text("Introduzca Un Contacto");
		    		$("#mensaje_contactos_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_contactos_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (direccion_proveedores == "")
		    	{
			    	
		    		$("#mensaje_direccion_proveedores").text("Introduzca Una Dirección");
		    		$("#mensaje_direccion_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_direccion_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (telefono_proveedores == "")
		    	{
			    	
		    		$("#mensaje_telefono_proveedores").text("Introduzca Un teléfono");
		    		$("#mensaje_telefono_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_telefono_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
		    	if (email_proveedores == "")
		    	{
			    	
		    		$("#mensaje_email_proveedores").text("Introduzca Un Email");
		    		$("#mensaje_email_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_email_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (fecha_nacimiento_proveedores == "")
		    	{
			    	
		    		$("#mensaje_fecha_nacimiento_proveedores").text("Introduzca Una Fecha");
		    		$("#mensaje_fecha_nacimiento_proveedores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	

		    	
				


		    	
			}); 


		        $( "#nombre_proveedores" ).focus(function() {
				  $("#mensaje_nombre_proveedores").fadeOut("slow");
			    });

		        $( "#identificacion_proveedores" ).focus(function() {
					  $("#mensaje_identificacion_proveedores").fadeOut("slow");
				    });
		        $( "#contactos_proveedores" ).focus(function() {
					  $("#mensaje_contactos_proveedores").fadeOut("slow");
				    });
		        $( "#direccion_proveedores" ).focus(function() {
					  $("#mensaje_direccion_proveedores").fadeOut("slow");
				    });
		        $( "#telefono_proveedores" ).focus(function() {
					  $("#mensaje_telefono_proveedores").fadeOut("slow");
				    });
		        $( "#email_proveedores" ).focus(function() {
					  $("#mensaje_email_proveedores").fadeOut("slow");
				    });
		        $( "#fecha_nacimiento_proveedores" ).focus(function() {
					  $("#mensaje_fecha_nacimiento_proveedores").fadeOut("slow");
				    });
		        		      

			    
		}); 

	</script>	
	
		<script type="text/javascript">
     
        	   $(document).ready( function (){
        		   
        		   load_bodegas_inactivos(1);
        		   load_bodegas_activos(1);
        		   
	   			});

        	


	   function load_bodegas_activos(pagina){

		   var search=$("#search_activos").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_bodegas_activos").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_bodegas_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Bodegas&action=consulta_bodegas_activos&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#bodegas_activos_registrados").html(x);
	                 $("#load_bodegas_activos").html("");
	                 $("#tabla_bodegas_activos").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#bodegas_activos_registrados").html("Ocurrio un error al cargar la informacion de Bodegas Activos..."+estado+"    "+error);
	              }
	            });


		   }

	   function load_bodegas_inactivos(pagina){

		   var search=$("#search_inactivos").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_bodegas_inactivos").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_bodegas_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Bodegas&action=consulta_bodegas_inactivos&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#bodegas_inactivos_registrados").html(x);
	                 $("#load_bodegas_inactivos").html("");
	                 $("#tabla_bodegas_inactivos").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#bodegas_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Bodegas Inactivos..."+estado+"    "+error);
	              }
	            });


		   }

	  
        	        	   

 </script>
 
     <script type="text/javascript" >   
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
    </script>
    
    <script>
var nav4 = window.Event ? true : false;
function aceptNum(evt){
var key = nav4 ? evt.which : evt.keyCode;
return (key <= 13 || (key>= 48 && key <= 57));
}
</script>

<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
	  
        <script >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{


				
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var pat= /^(?=.*\d)(?=.*[a-záéíóúüñ]).*[A-ZÁÉÍÓÚÜÑ]/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_proveedores = $("#nombre_proveedores").val();
		    	var identificacion_proveedores = $("#identificacion_proveedores").val();
		    	//var usuario_usuario = $("#usuario_usuario").val();
		    	var contactos_proveedores = $("#contactos_proveedores").val();
		    	var direccion_proveedores = $("#direccion_proveedores").val();
		    	var telefono_proveedores = $("#telefono_proveedores").val();
		    	var email_proveedores  = $("#email_proveedores").val();
		    	var fecha_nacimiento_proveedores  = $("#fecha_nacimiento_proveedores").val();
		    	



		    	  var numeros="0123456789";
		    	  var mayusculas="QWERTYUIOPASDFGHJKLZXCVBNMÑ"
			      var minusculas = "abcdefghijklmnopqrstuvwxyz";
		    	  var num=false;
		    	  var may=false;
		    	  var min=false;

		    	  
		    	 


		    	
		    	var contador=0;
		    	var tiempo = tiempo || 1000;
		    	 




		    	var suma = 0;      
		        var residuo = 0;      
		        var pri = false;      
		        var pub = false;            
		        var nat = false;      
		        var numeroProvincias = 22;                  
		        var modulo = 11;
		                    
		        /* Verifico que el campo no contenga letras */                  
		        var ok=1;


		        for (i=0; i<identificacion_proveedores.length && ok==1 ; i++){
		            var n = parseInt(identificacion_proveedores.charAt(i));
		            if (isNaN(n)) ok=0;
		         }


		        /* Los primeros dos digitos corresponden al codigo de la provincia */
		        provincia = identificacion_proveedores.substr(0,2);


		        /* Aqui almacenamos los digitos de la cedula en variables. */
		        d1  = identificacion_proveedores.substr(0,1);         
		        d2  = identificacion_proveedores.substr(1,1);         
		        d3  = identificacion_proveedores.substr(2,1);         
		        d4  = identificacion_proveedores.substr(3,1);         
		        d5  = identificacion_proveedores.substr(4,1);         
		        d6  = identificacion_proveedores.substr(5,1);         
		        d7  = identificacion_proveedores.substr(6,1);         
		        d8  = identificacion_proveedores.substr(7,1);         
		        d9  = identificacion_proveedores.substr(8,1);         
		        d10 = identificacion_proveedores.substr(9,1);                
		           
		        /* El tercer digito es: */                           
		        /* 9 para sociedades privadas y extranjeros   */         
		        /* 6 para sociedades publicas */         
		        /* menor que 6 (0,1,2,3,4,5) para personas naturales */ 





		        /* Solo para personas naturales (modulo 10) */         
		        if (d3 < 6){           
		           nat = true;            
		           p1 = d1 * 2;  if (p1 >= 10) p1 -= 9;
		           p2 = d2 * 1;  if (p2 >= 10) p2 -= 9;
		           p3 = d3 * 2;  if (p3 >= 10) p3 -= 9;
		           p4 = d4 * 1;  if (p4 >= 10) p4 -= 9;
		           p5 = d5 * 2;  if (p5 >= 10) p5 -= 9;
		           p6 = d6 * 1;  if (p6 >= 10) p6 -= 9; 
		           p7 = d7 * 2;  if (p7 >= 10) p7 -= 9;
		           p8 = d8 * 1;  if (p8 >= 10) p8 -= 9;
		           p9 = d9 * 2;  if (p9 >= 10) p9 -= 9;             
		           modulo = 10;
		        }         
		        /* Solo para sociedades publicas (modulo 11) */                  
		        /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
		        else if(d3 == 6){           
		           pub = true;             
		           p1 = d1 * 3;
		           p2 = d2 * 2;
		           p3 = d3 * 7;
		           p4 = d4 * 6;
		           p5 = d5 * 5;
		           p6 = d6 * 4;
		           p7 = d7 * 3;
		           p8 = d8 * 2;            
		           p9 = 0;            
		        }         
		           
		        /* Solo para entidades privadas (modulo 11) */         
		        else if(d3 == 9) {           
		           pri = true;                                   
		           p1 = d1 * 4;
		           p2 = d2 * 3;
		           p3 = d3 * 2;
		           p4 = d4 * 7;
		           p5 = d5 * 6;
		           p6 = d6 * 5;
		           p7 = d7 * 4;
		           p8 = d8 * 3;
		           p9 = d9 * 2;            
		        }
		                  
		        suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;                
		        residuo = suma % modulo;                                         
		        /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
		        digitoVerificador = residuo==0 ? 0: modulo - residuo; 




		       

		    	
		    	if (identificacion_proveedores == "")
		    	{
			    	
		    		$("#mensaje_identificacion_proveedores").text("Introduzca Identificación");
		    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error

		    		$("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
			        return false;
			    }
		    	else 
		    	{


		    		 if (ok==0){
						 $("#mensaje_identificacion_proveedores").text("Ingrese solo números");
				    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
				           
				            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
				            return false;
				      }else{

							$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
					
					  }
					

			    	
		    		if(identificacion_proveedores.length<10){

						
						$("#mensaje_identificacion_proveedores").text("Ingrese al menos 10 dígitos");
			    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
			            return false;
						}else{
						
							$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
							
					}


		    		if (provincia < 1 || provincia > numeroProvincias){           
						$("#mensaje_identificacion_proveedores").text("El código de la provincia (dos primeros dígitos) es inválido");
			    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
			            return false;

				      }else{

				    		$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
							
					  }


		    		if (d3==7 || d3==8){           

						$("#mensaje_identificacion_proveedores").text("El tercer dígito ingresado es inválido");
			    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
			            return false;
				      }
					else{

						$("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
						
						}




		    		if (pub==true){      


				         /* El ruc de las empresas del sector publico terminan con 0001*/         
			         if ( identificacion_proveedores.substr(9,4) != '0001' ){                    

			        	 $("#mensaje_identificacion_proveedores").text("El ruc de la empresa del sector público debe terminar con 0001");
				    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
				           
				            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
				            return false;

				     }else{
				    	 $("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
					}
					       
				         if (digitoVerificador != d9){                          
								$("#mensaje_identificacion_proveedores").text("El ruc de la empresa del sector público es incorrecto.");
					    		$("#mensaje_identificacion_proveedores").fadeIn("slow"); //Muestra mensaje de error
					           
					            $("html, body").animate({ scrollTop: $(mensaje_identificacion_proveedores).offset().top }, tiempo);
					            return false;
					           
				         } else{
				        	 $("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
								
					     }                 

				 }else{

		        	 $("#mensaje_identificacion_proveedores").fadeOut("slow"); //Muestra mensaje de error
		     }

			               

			       if(pri == true){    
			    	   if ( cedula_usuarios.substr(10,3) != '001' ){   

			    		   $("#mensaje_cedula_usuarios").text("El ruc de la empresa del sector privado debe terminar con 001");
				    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
				           
				            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
				            return false;
				                             
				            
				         }else{
				        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
								
					         }
				              
				         if (digitoVerificador != d10){                          

				        	 $("#mensaje_cedula_usuarios").text("El ruc de la empresa del sector privado es incorrecto");
					    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
					           
					            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
					            return false;

					     } else{
				        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
								
				         }        
				         
				      } else{

				        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
				     }


				if(nat == true){         

					if (cedula_usuarios.length >10 && cedula_usuarios.substr(10,3) != '001' ){                    
			         
			            $("#mensaje_cedula_usuarios").text("El ruc de la persona natural debe terminar con 001.");
			    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
			            return false;
			            
			         }else{

			        	 if(cedula_usuarios.length >13){
			        		 $("#mensaje_cedula_usuarios").text("El ruc de la persona natural es incorrecto.");
					    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
					           
					            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
					            return false;

				        	 }else{
				         
			        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
				        	 }

				         }

					
			         if (digitoVerificador != d10){    

			        	 if(cedula_usuarios.length >10){
			        		 $("#mensaje_cedula_usuarios").text("El ruc de la persona natural es incorrecto.");
					    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
					           
					            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
					            return false;

				        	 }else{
				         
			        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
				        	 }


			        	 if(cedula_usuarios.length <11){
			        		 $("#mensaje_cedula_usuarios").text("El número de cédula de la persona natural es incorrecto.");
					    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
					           
					            $("html, body").animate({ scrollTop: $(mensaje_cedula_usuarios).offset().top }, tiempo);
					            return false;

				        	 }else{
				         
			        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
				        	 }


				       
			         }else{

				        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
				     }  


				}else{

		        	 $("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
		     }
			
					
		            
				}    
			
		    	if (nombre_usuarios == "")
		    	{
			    	
		    		$("#mensaje_nombre_usuarios").text("Introduzca un Nombre");
		    		$("#mensaje_nombre_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_nombre_usuarios).offset().top }, tiempo);
			        
			            return false;
			    }
		    	else 
		    	{

		    		contador=0;
		    		numeroPalabras=0;
		    		contador = nombre_usuarios.split(" ");
		    		numeroPalabras = contador.length;
		    		
					if(numeroPalabras==2 || numeroPalabras==3 || numeroPalabras==4){

						$("#mensaje_nombre_usuarios").fadeOut("slow"); //Muestra mensaje de error
				                     
			             
					}else{
						$("#mensaje_nombre_usuarios").text("Introduzca Nombres y Apellidos");
			    		$("#mensaje_nombre_usuarios").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_nombre_usuarios).offset().top }, tiempo);
			            return false;
					}
			    	
		    		
		            
				}
		    			    	
		    
		    	if (clave_usuarios == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_clave_usuarios).offset().top }, tiempo);
				       
			            return false;
			    }else 
		    	{
		    		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}


			     if (clave_usuarios.length<8){
			    	$("#mensaje_clave_usuarios").text("Introduzca minimo 8 caracteres");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_clave_usuarios).offset().top }, tiempo);
				    
		            return false;
				}else 
		    	{
		    		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				 if (clave_usuarios.length>15){
			    	$("#mensaje_clave_usuarios").text("Introduzca máximo 15 caracteres");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_clave_usuarios).offset().top }, tiempo);
					   
		            return false;
				}else 
		    	{
		    		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				 

			
					
				

		    	if (cclave_usuarios == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios_r").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_clave_usuarios_r).offset().top }, tiempo);
					
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
		            
				}
		    	
		    	if (clave_usuarios != cclave_usuarios)
		    	{
			    	
		    		$("#mensaje_clave_usuarios_r").text("Claves no Coinciden");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_clave_usuarios_r).offset().top }, tiempo);
					
		            return false;
			    }
		    	else
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
			        
		    	}	
				

				//los telefonos
		    	
		    	if (celular_usuarios == "" )
		    	{
			    	
		    		$("#mensaje_celular_usuarios").text("Ingrese un Celular");
		    		$("#mensaje_celular_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_celular_usuarios).offset().top }, tiempo);
					
			            return false;
			    }
		    	else 
		    	{


		    		if(celular_usuarios.length==10){

						$("#mensaje_celular_usuarios").fadeOut("slow"); //Muestra mensaje de error
					}else{
						
						$("#mensaje_celular_usuarios").text("Ingrese 10 dígitos");
			    		$("#mensaje_celular_usuarios").fadeIn("slow"); //Muestra mensaje de error
			           
			            $("html, body").animate({ scrollTop: $(mensaje_celular_usuarios).offset().top }, tiempo);
			            return false;
					}

			    	
		    		
				}

				// correos
				
		    	if (correo_usuarios == "")
		    	{
			    	
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_correo_usuarios).offset().top }, tiempo);
					
		            return false;
			    }
		    	else if (regex.test($('#correo_usuarios').val().trim()))
		    	{
		    		$("#mensaje_correo_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	else 
		    	{
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo Valido");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_correo_usuarios).offset().top }, tiempo);
					
			            return false;	
			    }

		    	
		    	if (id_rol == 0 )
		    	{
			    	
		    		$("#mensaje_id_rol").text("Seleccione");
		    		$("#mensaje_id_rol").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_id_rol).offset().top }, tiempo);
					
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}



		    	if (id_estado == 0 )
		    	{
			    	
		    		$("#mensaje_id_estado").text("Seleccione");
		    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
		    		$("html, body").animate({ scrollTop: $(mensaje_id_estado).offset().top }, tiempo);
					
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    				    

			}); 


		        $( "#cedula_usuarios" ).focus(function() {
				  $("#mensaje_cedula_usuarios").fadeOut("slow");
			    });
				
				$( "#nombre_usuarios" ).focus(function() {
					$("#mensaje_nombre_usuarios").fadeOut("slow");
    			});
				
    			
				$( "#clave_usuarios" ).focus(function() {
					$("#mensaje_clave_usuarios").fadeOut("slow");
    			});
				$( "#clave_usuarios_r" ).focus(function() {
					$("#mensaje_clave_usuarios_r").fadeOut("slow");
    			});
				
				$( "#celular_usuarios" ).focus(function() {
					$("#mensaje_celular_usuarios").fadeOut("slow");
    			});
				
				$( "#correo_usuarios" ).focus(function() {
					$("#mensaje_correo_usuarios").fadeOut("slow");
    			});
			
				$( "#id_rol" ).focus(function() {
					$("#mensaje_id_rol").fadeOut("slow");
    			});

				$( "#id_estado" ).focus(function() {
					$("#mensaje_id_estado").fadeOut("slow");
    			});
				
		      
				    
		}); 

	</script>
        
   
 
	
  </body>
</html>   



