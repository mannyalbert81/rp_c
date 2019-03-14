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
              <h3 class="box-title"> Solicitudes </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Solicitud Pendiente</a></li>
              <li><a href="#tab_2" data-toggle="tab">Solicitud Entregada</a></li>
              <li><a href="#tab_3" data-toggle="tab">Solicitud Rechazada</a></li>
              
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-th"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud" name="buscador_solicitud" onkeyup="carga_solicitud(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud" ></div>	
        				<div id="resultados_solicitud"></div>
                        </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud_entregada" name="buscador_solicitud_entregada" onkeyup="carga_solicitud_entregada(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud_entregada" ></div>	
        				<div id="resultados_solicitud_entregada"></div>
        				
                        </div>
                               
                      
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud_rechazada" name="buscador_solicitud_rechazada" onkeyup="carga_solicitud_rechazada(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud_rechazada" ></div>	
        				<div id="resultados_solicitud_rechazada"></div>
        				
        				
                        </div>
                                
                      
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
            
            <div class="box-body">
            	<div class="ibox-content">
                      	
                  <div class="x_content">
                  
                  	
                  	
    		</section>
    		
            
            
  		</div>
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    
   <?php include("view/modulos/links_js.php"); ?>
  
  <script type="text/javascript" src="view/bootstrap/otros/inventario/movimientos_salidas.js" ></script>	
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
 
 




