<!DOCTYPE html>
<html lang="en">
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
        $fecha_solicitud = date("Y-m-d");
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
            <li class="active">Grupos</li>
          </ol>
        </section>
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Solicitud Inventario</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
                <form action="<?php echo $helper->url("SolicitudCabeza","InsertaGrupos"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
          		 	 <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
              		 	 <div class="row">
                         	<div class="col-xs-10 col-md-6 col-lg-6 ">
                            	<div class="form-group">
                                	<label for="nombre_usuario" class="control-label">Usuario</label>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $resEdit->nombre_grupos; ?>"  placeholder="Nombre Grupos">
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $resEdit->id_grupos; ?>" class="form-control"/>
    					            <div id="mensaje_nombre_grupos" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-2 col-md-2 col-lg-2 offset-md-4 offset-lg-4">
                            	<div class="form-group">
                                	<label for="fecha_solicitud" class="control-label">Fecha</label>
                                    <input type="text" name="fecha_solicitud" id="fecha_solicitud" value="<?php echo $resEdit->id_grupos; ?>" class="form-control"/>
    					            <div id="mensaje_fecha_solicitud" class="errores"></div>
                                 </div>
                             </div>
                             
                          </div>
                          <div class="row">
                          	<div class="col-xs-12 col-md-6 col-md-6 ">
                            	<div class="form-group">
                                	<label for="razon_solicitud">Razon:</label>
  									<textarea class="form-control" rows="5" id="razon_solicitud" name="razon_solicitud" ></textarea>
                                	<div id="mensaje_razon_solicitud" class="errores"></div>
                                 </div>
                             </div>
                          </div>
                      <?php } } else {?>
                      
                      <?php if(!empty($resultSet)){ foreach ($resultSet as $res){?>                		    
                      	  <div class="row">
                		  	<div class="col-xs-10 col-md-6 col-lg-6 ">
                            	<div class="form-group">
                                	<label for="nombre_usuario" class="control-label">Usuario</label>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $res->usuario_usuarios; ?>" readonly />
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $res->id_usuarios; ?>" class="form-control"/>
    					            <div id="mensaje_nombre_grupos" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-2 col-md-2  offset-md-4 col-lg-2  offset-lg-4">
                            	<div class="form-group">
                                	<label for="fecha_solicitud" class="control-label">Fecha</label>
                                    <input type="date" name="fecha_solicitud" id="fecha_solicitud" value="<?php echo $fecha_solicitud; ?>" class="form-control" readonly />
    					            <div id="mensaje_fecha_solicitud" class="errores"></div>
                                 </div>
                             </div>
                             
                          </div>
                          <div class="row">
                          	<div class="col-xs-12 col-md-6 col-lg-6 ">
                            	<!--  <div class="form-group">-->
                            		<div class="md-form">
      									<i class="fas fa-pencil-alt prefix"></i>
                                		<label for="razon_solicitud">Razon:</label>
      									<textarea class="md-textarea form-control" rows="5" id="razon_solicitud" name="razon_solicitud" ></textarea>
                                    	<div id="mensaje_razon_solicitud" class="errores"></div>
                                    </div>
                                 <!-- </div> -->
                             </div>
                          </div>  
                    		            
                     <?php }}} ?>
                     	<div class="row">
            			    <div class="col-xs-12 col-md-4 col-md-4 " style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
        	                    </div>
    	        		    </div>
            		    </div>
          		 	
          		 	</form>
          
        			</div>
      			</div>
    		</section>
    		
    		
    		
    <!-- seccion para el listado de Productos -->
   
      <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Listado de Productos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
        	<div class="ibox-content">
                  	
              <div class="x_content">
				
				<div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="buscador_productos" name="buscador_productos" onkeyup="load_productos_solicitud(1)" placeholder="search.."/>
				</div>
				<div id="load_productos" ></div>	
				<div id="productos_inventario"></div>	
              
              </div>  
                  	
     		  </div>
        </div>
        </div>
        </section>
        
        <!-- ver resultados -->
        	<section class="content">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Listado de Productos - Solicitud</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                    
                  </div>
                </div>
                
                <div class="box-body">
                	<div class="ibox-content">
                          	
                      <div class="x_content">
        				<div id="resultados" ></div>	
        				
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
    	
  </body>
</html>

<!-- script pagina anterior -->
<script type="text/javascript">
     
        	   $(document).ready( function (){
        		   //pone_espera();
        		   
	   			});

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

 </script>
 <<script type="text/javascript">
 	$(document).ready(function(){
 		load_productos_solicitud(1);
 		load_temp_solicitud(1);
 		
 	});

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

 	function eliminar_producto (id)
	{
		
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
                 beforeSend: function(objeto){
                   
                 },
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

 	
 	
</script>
       
       
      
 




