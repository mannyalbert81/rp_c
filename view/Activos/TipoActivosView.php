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
        <li class="active">Controladores</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Tipo Activos Fijos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_tipo_activos" action="<?php echo $helper->url("Controladores","InsertaControladores"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
              <?php if (isset($resultEdit)) { foreach($resultEdit as $resEdit) {?>
            
			 	<div class="row">
			 	
        		    <div class="col-xs-12 col-md-3 col-md-3 ">
        		    
            		    <div class="form-group">
            		   						 
                          <label for="nombre_tipo_activo" class="control-label"> Nombres Tipo Activo:</label>
                          <input type="text" class="form-control" id="nombre_tipo_activo" name="nombre_tipo_activo" value="<?php echo $resEdit->nombre_controladores; ?>"  placeholder="Nombre Tipo Activo" required/>
                          <input type="hidden" name="id_tipo_activo" id="id_tipo_activo" value="<?php echo $resEdit->id_controladores; ?>"/>
                          <div id="mensaje_nombre_tipo_activo" class="errores"></div>
	                        	                                          
                        </div>
                        
        		  </div>
        		  
    			    <div class="col-xs-12 col-md-3 col-md-3">
        		    	<div class="form-group">
                                       
                          <label for="meses_tipo_activo" class="control-label">Meses Depreciacion:</label>
                          <input type="number" min="1" class="form-control" id="meses_tipo_activo" name="meses_tipo_activo" value="<?php echo $resEdit->nombre_controladores; ?>"  placeholder="Meses" required/>
                          <div id="mensaje_meses_tipo_activo" class="errores"></div>
                    	</div>
                    </div>
                        		
        		</div>	
         
							    
			     <?php } } else {?>
							    
							    
		    	 <div class="row">
        		    <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="nombre_tipo_activo" class="control-label">Nombres Tipo Activo:</label>
                          <input  type="text" class="form-control" id="nombre_tipo_activo" name="nombre_tipo_activo" value=""  placeholder="Nombre Controlador" required/>
                          <input type="hidden" name="id_tipo_activo" id="id_tipo_activo" value="0" />
                          <div id="mensaje_nombre_tipo_activo" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
                        		 
				    <div class="col-xs-12 col-md-3 col-md-3">
            		    <div class="form-group">
                          <label for="meses_tipo_activo" class="control-label">Meses Depreciacion:</label>
                          <input type="number" min="1" class="form-control" id="meses_tipo_activo" name="meses_tipo_activo" value=""  placeholder="Meses" required/>
                          <div id="mensaje_meses_tipo_activo" class="errores"></div>
                        </div>
                    </div>
          	   	</div>	
							   
				      				               	
			     <?php } ?>
					                		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
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
      			<h3 class="box-title">Listado de Tipos Activos</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaTipoActivo(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="tipos_activos_registrados" ></div>
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
   <script src="view/Activos/js/TipoActivos.js?0.8"></script> 
       
       

 	
	
	
  </body>
</html>   

