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
 	
 	 	<style type="text/css">
 	  .loader_detalle {
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
        <li><a href="<?php echo $helper->url("ffspUsuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Examen Fisico Regional</li>
      </ol>
    </section>   

   		   <section class="content">
     <div class="box box-primary">
     
                  
  		<div class="box-body">
			<form id="frm_bitacora_creditos" action="<?php echo $helper->url("BitacoraActividadesEmpleadosCreditos","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
           	 <div class="row">
        		      <input type="hidden" name="id_bitacora_actividades_empleados_creditos" id="id_bitacora_actividades_empleados_creditos" value="0" />
        		       <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="fecha_registro" class="control-label">Fecha:</label>
                          <input  type="text" class="form-control" id="fecha_registro" name="fecha_registro" value=""  placeholder="Fecha" required/>
                          <div id="mensaje_fecha_registro" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="desde" class="control-label">Desde:</label>
                          <input  type="text" class="form-control" id="desde" name="desde" value=""  placeholder="Desde" required/>
                          <div id="mensaje_desde" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="hasta" class="control-label">Hasta:</label>
                          <input  type="text" class="form-control" id="hasta" name="hasta" value=""  placeholder="Hasta" required/>
                          <div id="mensaje_hasta" class="errores"></div>
                        </div>
            		  </div>
                   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="id_participes" class="control-label">Participes:</label>
                          <select  class="form-control" id="id_participes" name="id_participes" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_participes" class="errores"></div>
                        </div>
            		  </div>
        		  </div>	
							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("BitacoraActividadesEmpleadosCreditos","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
      			<h3 class="box-title">Listado de Examen</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaExamenFisicoRegional(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="examen_fisico_regional_registrados" ></div>
            </div> 	
      	</div>
      </section> 
    	     </div>
             </div>
         
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/js/BitacoraActividadesEmpleadosCreditos.js?0.2"></script> 
       
       

 	
	
	
  </body>
</html>   

