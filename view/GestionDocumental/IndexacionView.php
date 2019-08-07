<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
 	
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
        <li class="active">Bancos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Bancos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_bancos" action="<?php echo $helper->url("Indexacion","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
							    
							    
		    	 <div class="row">
        			  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_categorias" class="control-label">Categoría:</label>
                          <select  class="form-control" id="id_categorias" name="id_categorias">
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_categorias" class="errores"></div>
                        </div>
            		  </div>
            		  
            		   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_subcategorias" class="control-label">Subcategoría:</label>
                          <select  class="form-control" id="id_subcategorias" name="id_subcategorias" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_subcategorias" class="errores"></div>
                        </div>
            		  </div>
            		  
            		<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		      <label for="nombre_bancos" class="control-label">Numero de Credito:</label>
                          <input  type="text" class="form-control" id="nombre_bancos" name="nombre_bancos" value=""  placeholder="Nombre Bancos" required/>
                          <input type="hidden" name="id_bancos" id="id_bancos" value="0" />
                          <div id="mensaje_nombre_bancos" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		 <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		      <label for="nombre_bancos" class="control-label">Nombre:</label>
                          <input  type="text" class="form-control" id="nombre_bancos" name="nombre_bancos" value=""  placeholder="Nombre Bancos" required/>
                          <input type="hidden" name="id_bancos" id="id_bancos" value="0" />
                          <div id="mensaje_nombre_bancos" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		 <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		      <label for="nombre_bancos" class="control-label">Cédula:</label>
                          <input  type="text" class="form-control" id="nombre_bancos" name="nombre_bancos" value=""  placeholder="Nombre Bancos" required/>
                          <input type="hidden" name="id_bancos" id="id_bancos" value="0" />
                          <div id="mensaje_nombre_bancos" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
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
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/GestionDocumental/js/GestionDocumental.js?0.3"></script> 
       
       

 	
	
	
  </body>
</html>   

