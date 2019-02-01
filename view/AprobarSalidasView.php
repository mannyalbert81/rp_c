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
            <li class="active">Solicitud</li>
          </ol>
        </section>
        
         	
    		<!-- seccion para ver division de vista -->
          <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Aprobar/Rechazar Solicitudes </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            	<div class="ibox-content">
                      	
                  <div class="x_content">
                  
                  <div class="row">
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="nombre_usuario" class="control-label">Usuario Solicitante:</label>
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $resultsolicitud[0]->nombre_usuarios; ?>"  >
                            <div id="mensaje_nombre_usuario" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="numero_solicitud" class="control-label">No. Solicitud:</label>
                            <input type="text" class="form-control" id="numero_solicitud" name="numero_solicitud" value="<?php echo $resultsolicitud[0]->numero_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_numero_solicitud" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="fecha_solicitud" class="control-label">fecha Solicitud:</label>
                            <input type="text" class="form-control" id="fecha_solicitud" name="fecha_solicitud" value="<?php echo $resultsolicitud[0]->fecha_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_nombre_usuario" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="estado_solicitud" class="control-label">Estado Solicitud:</label>
                            <input type="text" class="form-control" id="estado_solicitud" name="estado_solicitud" value="<?php echo $resultsolicitud[0]->estado_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_estado_solicitud" class="errores"></div>
                         </div>
                  	</div>
                  	
                  </div>
                  	
    		</section>
    		
    		   <section class="content">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Listado de Productos Solicitados</h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                          <i class="fa fa-minus"></i></button>
                        
                    </div>
                    
                    <div class="box-body">
                    
                    
                   <div class="ibox-content">  
                  <div class="table-responsive">
                  
                    <table  class="table table-striped table-bordered table-hover dataTables-example">
                                  <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Grupos</th>
                                      <th>Código</th>
                                      <th>Nombre</th>
                                       <th>Descripcion</th>
                                      <th>Unidad De M.</th>
                                      <th>ULT Precio</th>
                                      <th>Aprobar</th>
                                      <th>Rechazar</th>
            
                                    </tr>
                                  </thead>
            
                                  <tbody>
                					<?php $i=0;?>
                						<?php if (!empty($resultdetalle)) {  foreach($resultdetalle as $res) {?>
                						<?php $i++;?>
                        	        		<tr>
                        	                   <td > <?php echo $i; ?>  </td>
                        		               <td > <?php echo $res->nombre_grupos; ?>     </td> 
                        		               <td > <?php echo $res->codigo_productos; ?>   </td>
                        		               <td > <?php echo $res->nombre_productos; ?>   </td>
                        		               <td class="col-xs-1 col-md-1 col-lg-1" > <input type="text" class="form-control" value="<?php echo $res->cantidad_movimientos_inv_detalle; ?>" id="cantidad_producto_<?php echo $res->id_productos;?>" />    </td>
                        		               <td > <?php echo $res->nombre_unidad_medida; ?>   </td>
                        		               <td > <?php echo $res->ult_precio_productos; ?>   </td>
                        		              
                        		           	   <td>
                        			           		<div class="right">
                        			                    <a href="#"  onclick="aprobar_producto(<?php echo $res->id_productos; ?>)" class="btn btn-success" style="font-size:65%;" data-toggle="tooltip" title="Aprobar"><i class='fa fa-check-square-o'></i></a>
                        			                </div>
                        			            
                        			             </td>
                        			             <td>   
                        			                	<div class="right">
                        			                    <a href="#" onclick="rechazar_producto(<?php echo $res->id_productos; ?>)" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Rechazar"><i class="fa fa-remove"></i></a>
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
    		
            <section class="content">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"></h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                    
                  </div>
                </div>
                
                <div class="box-body">
                <form id="frm_solicitud_cabeza" action="<?php echo $helper->url("SolicitudCabeza","inserta_solicitud"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                 <?php if(!empty($resultSet)){ foreach ($resultSet as $res){?>                		    
                      	  <div class="row">
                		  	<div class="col-xs-10 col-md-6 col-lg-6 ">
                            	<div class="form-group">
                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $res->id_usuarios; ?>" class="form-control"/>
    					            <div id="mensaje_nombre_grupos" class="errores"></div>
                                 </div>
                             </div>
                             
                          </div>
                         
                    		            
                     <?php }}?>
            	 	<div class="row">
                      	<div class="col-xs-12 col-md-12 col-lg-12 ">
                        	<!--  <div class="form-group">-->
                        		<div class="md-form">
  									<i class="fas fa-pencil-alt prefix"></i>
                            		<label for="razon_solicitud">Observacion:</label>
  									<textarea class="md-textarea form-control" rows="1" id="razon_solicitud" name="razon_solicitud" ></textarea>
                                	<div id="mensaje_razon_solicitud" class="errores"></div>
                                </div>
                             <!-- </div> -->
                         </div>
                     </div> 
                    <div class="row">
        			    <div class="col-xs-12 col-md-2 col-md-2 " >
            	   		    <div class="form-group">
            	   		    	<label for="Guardar">&nbsp;</label>
        	                  <button type="submit" id="Guardar" name="Guardar" class="form-control btn btn-success">Guardar</button>
    	                    </div>
	        		    </div>
        		    </div>
                </form>
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
	carga_solicitud();
	carga_solicitud_entregada()
	carga_solicitud_rechazada()
	
});

function carga_solicitud(pagina){

    var search=$("#buscador_solicitud").val();
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
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud").html(x);
               $("#load_solicitud").html("");
               $("#tabla_salidas").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_entregada(pagina){

    var search=$("#buscador_solicitud_entregada").val();
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
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_entregada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_entregada").html(x);
               $("#load_solicitud_entregada").html("");
               $("#tabla_salidas_entregada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_rechazada(pagina){

    var search=$("#buscador_solicitud_rechazada").val();
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
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_rechazada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_rechazada").html(x);
               $("#load_solicitud_rechazada").html("");
               $("#tabla_salidas_rechazada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

 	

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
       
       
      
 




