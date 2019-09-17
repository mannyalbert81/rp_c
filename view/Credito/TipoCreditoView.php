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
        <li class="active">Crédito</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Tipo Créditos Renovacion</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_tipo_credito_renovacion" action="<?php echo $helper->url("TipoCredito","InsertaTipoCredito"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
							    
							    
		    	 <div class="row">
        		 <input type="hidden" id="id_tipo_creditos_renovacion" value="0">
            		  
      
       		  		  <div class="col-xs-12 col-md-4 col-md-4 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_tipo_creditos" class="control-label">Tipo Creditos:</label>
                          <select  class="form-control" id="id_tipo_creditos" name="id_tipo_creditos" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_tipo_creditos" class="errores"></div>
                        </div>
            		  </div>
            		  
            		    <div class="col-xs-12 col-md-4 col-md-4 ">
            		    <div class="form-group">
            		      <label for="id_tipo_creditos_a_renovar" class="control-label">Tipo Creditos Renovar:</label>
                          <select  class="form-control" id="id_tipo_creditos_a_renovar" name="id_tipo_creditos_a_renovar" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_tipo_creditos_a_renovar" class="errores"></div>
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
						<BR>	          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    <div class="form-group">
    	            	  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	          
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
      			<h3 class="box-title">Listado de Tipo Creditos Renovacion</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaTipoCredito(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="tipo_credito_renovacion_registrados" ></div>
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
   <script src="view/Credito/js/TipoCredito.js?0.11"></script> 
       
  </body>
</html>   

