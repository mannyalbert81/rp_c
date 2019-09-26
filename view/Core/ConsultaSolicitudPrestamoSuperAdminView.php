<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   <style type="text/css">
 	  .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('view/images/ajax-loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
        }
 	</style> 	
   <?php include("view/modulos/links_css.php"); ?>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   	
	<link href="view/bootstrap/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" /> 
	
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
            <li class="active">Participes</li>
          </ol>
        </section>
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Solicitudes</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
              <section class="content">
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Solicitud de Prestamos Registradas</a></li>
              <li><a href="#desafiliado" data-toggle="tab">Solicitud de Garantías Generadas</a></li>
              <li><a href="#cesante" data-toggle="tab">Solicitud de Desafiliación</a></li>
            
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
				 <div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_solicitud" name="search_solicitud" onkeyup="load_solicitud_prestamos_registrados(1)" placeholder="search.."/>
					</div>
                    	
					<div id="load_registrados" ></div>	
					<div id="solicitud_prestamos_registrados"></div>	
				  
                
              </div>
      
                 <div class="tab-pane" id="desafiliado">
                
                    <div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_garantias" name="search_garantias" onkeyup="load_solicitud_garantias_registrados(1)" placeholder="search.."/>
					</div>
                    	
					<div id="load_garantias_registrados" ></div>	
					<div id="solicitud_garantias_registrados"></div>	
              </div>
              
                     <div class="tab-pane" id="cesante">
                
                    <div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_cesantes" name="search_cesantes" onkeyup="load_solicitud_cesantes_registrados(1)" placeholder="search.."/>
					</div>
                    	
					<div id="load_cesantes_registrados" ></div>	
					<div id="solicitud_cesantes_registrados"></div>	
              </div>
             
             </div>
            </div>
           </div>
         
            </div>
            </div>
            </section>
            <div class="box-body">
            
        	
        

        			</div>
      			</div>
    		</section>
    		
    <!-- seccion para el listado de roles -->
   
  		</div>
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
   <?php include("view/modulos/links_js.php"); ?>
 <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
 <script src="view/Core/js/SolicitudPrestamo.js?3.31" ></script>
 
 </body>
</html>