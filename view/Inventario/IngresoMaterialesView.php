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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
  
    
   
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
        	<form action="">
    		<section class="content">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Ingreso Materiales - FACTURAS</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                    
                  </div>
                </div>
                
                <div class="box-body">             
                	
                    <span style="float:right">
                    	
                    	
              		</span>
              	</div>
                	
                 <div class="box-body">
                 	  <div id="load_comprobantes"></div>
                      <div id="data_comprobantes" ></div>
                      
                      <!-- parte inferior de subtotales -->
                      <div class="row pull-left" id="resultados_totales">
                      	
                      </div>
                          
                         
                   </div>
                
                
                </div>
            </section>
            
            </form>
    		
    		
    		
    		
     
    	
    
  </div>
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
     
   
   <?php include("view/modulos/links_js.php"); ?>
 
  
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="view/bootstrap/otros/uitable/bootstable.js"></script>
  
  <script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
  
  <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>    
     <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
     <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.numeric.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    
    <script src="//oss.maxcdn.com/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    
    <script src="view/Inventario/FuncionesJS/ingresoMateriales.js?1.1"></script>
    
    <!-- <script src="view/bootstrap/otros/validate/jquery.validate.js"></script> -->
     
   
  
             
 	
  </body>
</html>

 