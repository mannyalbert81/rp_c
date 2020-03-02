<!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <?php include("view/modulos/links_css.php"); ?>		
      
    	
	
		    
	</head>
 
    <body class="hold-transition skin-blue fixed sidebar-mini" ng-app="myApp" ng-controller="myCtrl">
    
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Inventario</a></li>
        <li class="active">Productos</li>
      </ol>
    </section>

        <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Historico de Productos</h3>
          
        </div>
        
        <div class="box-body">
        
        	 <form id="frm_declaracion"  method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
       
        	<div class="row">
    		  
    		   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_productos" class="control-label">Productos:</label>
                          <select  class="form-control" id="id_productos" name="id_productos">
                          	<option value="0">--Seleccione--</option>
                          
                          </select> 
                                                
                          <div id="mensaje_id_productos" class="errores"></div>
                        </div>
            	</div>
                 	 
             	<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_usuarios" class="control-label">Empleados:</label>
                          <select  class="form-control" id="id_usuarios" name="id_usuarios">
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_usuarios" class="errores"></div>
                        </div>
            		  </div> 
            		  
            		  
             	<div class="col-xs-3 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="desde" class="control-label">Desde:</label>
                    	<input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="" placeholder="Razón..">
                    	 <div id="mensaje_fecha_desde" class="errores"></div>
                 	</div>
             	</div> 
             	<div class="col-xs-3 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="hasta" class="control-label">Hasta:</label>
                    	<input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="" placeholder="Razón..">
                    	 <div id="mensaje_fecha_hasta" class="errores"></div>
                 	</div>
             	</div> 
                 
               </div>
               
               
              <div class="row">
    			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
        	   		<div class="form-group">
    	            <button type="button" id="buscar" name="buscar" value="Buscar"   class="btn btn-info" ><i class="glyphicon glyphicon-search"></i></button>
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
              <h3 class="box-title">Historico de los Productos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <br>
              <div class="tab-pane active" id="productos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_buscar_productos" name="search_buscar_productos" onkeyup="load_buscar_productos(1)" placeholder="search.."/>
					</div>
					<div id="load_buscar_productos" ></div>	
					<div id="productos_registrados"></div>	
                
              </div>
                 		  <a href="index.php?controller=HistoricoProducto&action=reporte_stock_productos" target="_blank"><input type="image" src="view/images/print.png" alt="Submit" width="50" height="34" formtarget="_blank" id="btngenerar" name="btngenerar" class="btn btn-default" title="Reporte Productos"></label></a>
         
              
            </div>
            </div>
            </section>
    
     
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    

    

	
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
  	  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>     

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      
      
      
    <script src="view/Contable/FuncionesJS/HistoricoProducto.js?1.3"></script>

    
    
    
    
    
    
	
  </body>
</html>  