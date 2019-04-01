<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    
    
    
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
            
            <form id="frm_agrega_salida" action="<?php echo $helper->url("MovimientosInv","inserta_salida"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
            
            	<input type="hidden"  value="<?php echo $resultsolicitud[0]->id_movimientos_inv_cabeza; ?>" id="id_movimiento_solicitud"  name="id_movimiento_solicitud" />
            	<input type="hidden"  value="APROBAR" id="btnForm"  name="btnForm" />
            	
                  <div class="row">
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="nombre_usuario" class="control-label">Usuario Solicitante:</label>
                            <input type="text" readonly="readonly" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo $resultsolicitud[0]->nombre_usuarios; ?>"  >
                            <div id="mensaje_nombre_usuario" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="numero_solicitud" class="control-label">No. Solicitud:</label>
                            <input type="text" readonly="readonly" class="form-control" id="numero_solicitud" name="numero_solicitud" value="<?php echo $resultsolicitud[0]->numero_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_numero_solicitud" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="fecha_solicitud" class="control-label">fecha Solicitud:</label>
                            <input type="text" readonly="readonly" class="form-control" id="fecha_solicitud" name="fecha_solicitud" value="<?php echo $resultsolicitud[0]->fecha_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_nombre_usuario" class="errores"></div>
                         </div>
                  	</div>
                  	
                  	<div class="col-md-3 col-lg-3">
                  		<div class="form-group">
                        	<label for="estado_solicitud" class="control-label">Estado Solicitud:</label>
                            <input type="text" readonly="readonly"  class="form-control" id="estado_solicitud" name="estado_solicitud" value="<?php echo $resultsolicitud[0]->estado_movimientos_inv_cabeza; ?>"  >
                            <div id="mensaje_estado_solicitud" class="errores"></div>
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
                      <h3 class="box-title">Listado de Productos Solicitados</h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                          <i class="fa fa-minus"></i></button>
                        
                    </div>
                    
                    <div class="box-body">
                    
                    <hr>
                     <div class="row">
                         	<div class="col-md-12 col-lg-12 col-xs-12">
                         		<div class="form-group">
                                    <div class="pull-right">
                                        <button type="submit" form="frm_agrega_salida" id="btn_aprobar" name="btn_aprobar" value="APROBAR" class="enviarsalida  btn btn-default"> <i class=" fa fa-cloud-upload" aria-hidden="true"></i>  &nbsp; APROBAR</button>
                                    	<button type="submit" form="frm_agrega_salida" id="btn_rechazar" name="btn_rechazar" value="REPROBAR" class="enviarsalida  btn btn-danger"> <i class="fa  fa-eraser" aria-hidden="true"></i> CANCELAR</button>
                                    </div>
                               </div>	
    						</div>
                         </div>
                    <hr>
                    
                   <div class="ibox-content">  
                  <div class="table-responsive">
                  
                    <table  class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Grupos</th>
                          <th>Código</th>
                          <th>Nombre</th>
                          <th>Disponible</th>
                          <th>Cantidad</th>
                          <th>U.M.</th>
                          <th>Precio</th>
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
            		               <td > <?php $disponible = (is_null( $res->disponible)) ? 0 : (int)$res->disponible; echo $disponible; ?>   </td>
            		               <td class="col-xs-1 col-md-1 col-lg-1" > <input type="text" class="form-control" value="<?php echo $res->cantidad_temp_salida; ?>" id="cantidad_producto_<?php echo $res->id_temp_salida;?>" />    </td>
            		               <td > <?php echo $res->nombre_unidad_medida; ?>   </td>
            		               <td > <?php echo $res->ult_precio_productos; ?>   </td>
            		              
            		           	   <td>
            			           		<div class="right">
            			                    <a href="#"  onclick="aprobar_producto(<?php echo $res->id_temp_salida; ?>)" class="btn btn-success" style="font-size:65%;" data-toggle="tooltip" title="Aprobar"><i class='fa fa-check-square-o'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="#" onclick="rechazar_producto(<?php echo $res->id_temp_salida; ?>)" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Rechazar"><i class="fa fa-remove"></i></a>
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
            	</div>        
            </section>
    	
            
  		</div>
  
  
 
    <?php include("view/modulos/footer.php"); ?>	
    
    <div class="control-sidebar-bg"></div>
    </div>
    
    
   <?php include("view/modulos/links_js.php"); ?>
  <script src="view/Inventario/FuncionesJS/movimientos_salidas_detalle.js?1.2" ></script>
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
 
      
 




