    <!DOCTYPE HTML>
	<html lang="es">
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i>Core</a></li>
        <li class="active">Estado Marital</li>
      </ol>
    </section>

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Estado Marital</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_estados" action="<?php echo $helper->url("EstadoMarital","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
							    
							    
		    	 <div class="row">
        		    <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="nombre_estado_marital" class="control-label">Nombre del Estado:</label>
                          <input  type="text" class="form-control" id="nombre_estado_marital" name="nombre_estado_marital" value=""  placeholder="Estado Marital" required/>
                          <input type="hidden" name="id_estado_marital" id="id_estado_marital" value="0" />
                          <div id="mensaje_nombre_estado_marital" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_estado" class="control-label">Estado:</label>
                          <select  class="form-control" id="id_estado" name="id_estado" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_estado" class="errores"></div>
                        </div>
            		  </div>
                        		 
				    
          	   	</div>	
							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("EstadoMarital","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
              <h3 class="box-title">Listado Estados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Estados Activos</a></li>
              <li><a href="#inactivos" data-toggle="tab">Estados Inactivos</a></li>
            </ul>
            
            <div class="col-md-5 col-lg-12 col-xs-5">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_estados_activos(1)" placeholder="search.."/>
					</div>
					<div id="load_estados_activos" ></div>	
					<div id="estados_activos_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="inactivos">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_inactivos" name="search_inactivos" onkeyup="load_estados_inactivos(1)" placeholder="search.."/>
					</div>
					<div id="load_estados_inactivos" ></div>	
					<div id="estados_inactivos_registrados"></div>
                
              </div>
             
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
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="view/Core/js/Estados.js?3.2" ></script>
  </body>
</html>   

 