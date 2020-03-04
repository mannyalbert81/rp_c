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
  	  <form id="frm_solicitud_cabeza" action="<?php echo $helper->url("MovimientosInv","inserta_solicitud"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
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
              <h3 class="box-title">Generacion Solicitud</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
             <div class="box-body">
             
             	<input type="hidden" name="id_usuario" id="id_usuario" value="<?php if( isset($resultSet) ) { echo $resultSet[0]->id_usuarios; } ?>" />  
             
             	<div id="panelmodal">
          			<div class="row">
          				<div class="col-lg-6 col-md-6 col-xs-12 form-group-sm ">
                            <label for="razon_solicitud" class="col-sm-3 form-label"> Observacion:</label>
                            <div class="col-sm-9">
                              <textarea class="md-textarea form-control" rows="1" id="razon_solicitud" name="razon_solicitud" ></textarea>
                              <div id="mensaje_razon_solicitud" class="errores"></div>
                            </div>
                            
                         </div>
                         <div class="col-lg-6 col-md-6 col-xs-12">
                             <div class="pull-right">          					     
              					<div class="form-group-sm">
              					     
              					     
              					           					
              						 <button type="submit" id="Guardar" name="Guardar" class="btn btn-default">
              						 <i aria-hidden="true" class="fa fa-floppy-o"></i> Guardar Solicitud</button>                  				
                    				 <button type="button" id="btnAgregarProductos" name="btnAgregarProductos" class="btn btn-default" data-toggle="modal" data-target="#mod_listado_productos">
                    				 <i aria-hidden="true" class="fa fa-plus"></i> Agregar Producto a Solicitud</button>
                    				 <button type="button" id="btnestado" onclick="load_estado_productos()" name="btnestado" class="btn btn-default" data-toggle="modal" data-target="#mod_estado">
                    				 <i aria-hidden="true" class="fa fa-bars"></i>Estado Solicitud</button>
                    				 
                        		</div>            			
                      		</div>
                        </div>          				      				
          				
          			 </div> 					
          		</div>
          		
                
                <hr> 	
                
                <div>
                <div id="resultados" ></div>
                </div>	
             	                  
                 
		     </div>
	     	</div>
    		     
    		</section>
    		
            </form>
  		</div>
  
  <?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- PARA MODALES --> 
 <div class="modal fade" id="mod_listado_productos" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Listado Productos</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="" method="post" id="frm_listado_productos" name="frm_listado_productos">
          	
          		<div class="row">
      				<div class=" pull-left " >
      					<div class=" col-lg-12 form-group-sm">
                        	<span class="form-control" id="mod_cantidad_busqueda"><strong>Registros: </strong>0</span>
                        	<input type="hidden" value="" id="total_query" name="total_query"/>
                    	</div>   
                	</div>
      				<div class="pull-right">
      					<div class="col-lg-12 form-group-sm">                    				
            				 <input type="text" class="form-control" id="mod_txtbuscar" name="mod_txtbuscar" value="" >
                		</div>            			
              		</div>
          		</div>  
          		<br/>
          		<div id="div_tabla_productos">
  				</div>
             	<div class="clearfix"></div>		  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
      <!-- Estado de la Solicitud -->
    
 <div class="modal fade" id="mod_estado" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Estado de Las Solicitudes</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="" method="post" id="frm_estado" name="frm_estado">
          	
          		<div class="box-body">

           <br>
              <div class="tab-pane active" id="solicitudes">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_estado_productos" name="search_estado_productos" onkeyup="load_estado_productos(1)" placeholder="search.."/>
					</div>
					<div id="load_estado_productos" ></div>	
					<div id="estado_productos_registrados"></div>	
                
              </div>
              </div>		  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
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
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script type="text/javascript" src="view/Inventario/js/Solicitud.js?0.11"></script>
    	
  </body>
</html>

