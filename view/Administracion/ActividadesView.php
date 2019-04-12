<!DOCTYPE HTML>
<html lang="es">
      <head>
      
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
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
				        
    </head>
    
    
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    

    
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
        <li class="active">Actividades</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     	<div class="box-header">
          <h3 class="box-title">Buscar Actividades</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            		<i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
      <div class="box-body">
		<form id="frm_actividades" action="<?php echo $helper->url("Actvidades","Index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-xs-12">
     	  	<div class="row">                		  	   
                                  	
                <div class="col-xs-12 col-md-3 col-lg-3">
                  	<div class="form-group">
                    	<label for="desde" class="control-label">Desde:</label>
                        <input type="date" class="form-control" id="desde" name="desde" value="" placeholder="desde.."  >
                        <div id="mensaje_desde" class="errores"></div>
                     </div>
          		</div>
          		
                <div class="col-xs-6 col-md-3 col-lg-3 ">
                	<div class="form-group">
                    	<label for="hasta" class="control-label">Hasta:</label>
                        <input type="date" class="form-control" id="hasta" name="hasta" value="" placeholder="hasta.."  >
                        <div id="mensaje_hasta" class="errores"></div>
                     </div>
                 </div> 
                  
            </div>	   
        
			<div id="divLoaderPage" ></div> 
					   
       		<div class="row">
    		    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
    	   		    <div class="form-group">
                      <button type="button" id="buscar" name="buscar" class="btn btn-success">Buscar</button>
                    </div>
    		    </div>
    	    </div>
 
                      
                      
              <div class="pull-right" style="margin-right:11px;">
			<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_actividades(1)" placeholder="search.."/>
			</div>
					
			<div id="actividades_registrados"></div>	
        </form>              
       </div>
      </div>
    </section>
              
    
    
  </div>
  
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	
		
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/Administracion/js/Actividades.js?1.2"></script>  
	
	
  </body>
</html>   

