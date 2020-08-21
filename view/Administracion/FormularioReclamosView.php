<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    
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
    
    
      
    
    <div>

  <div>
  
  
  <div class="col-xs-12 col-md-12 col-md-12 ">
  <div class="col-xs-12 col-md-2 col-md-2 ">
  </div>
  
  <div class="col-xs-12 col-md-8 col-md-8 ">
  		 <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Reclamos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        <!-- INPUTS HIDDEN --> 
        <input type="hidden" name="id_form_reclamos" id="id_form_reclamos" value="0" />    
        
        <form action="<?php echo $helper->url("FormularioReclamos","index"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        
    	 <div class="row">
    	 
    	 	<div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="nombres_form_reclamos" class="control-label">Nombres:</label>
                  <input type="text" class="form-control" id="nombres_form_reclamos" name="nombres_form_reclamos" value=""  placeholder="Nombres..">                      
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="apellidos_form_reclamos" class="control-label">Apellidos:</label>
                  <input type="text" class="form-control" id="apellidos_form_reclamos" name="apellidos_form_reclamos" value=""  placeholder="Apellidos..">                      
                </div>
		    </div>
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="edad_form_reclamos" class="control-label">Edad:</label>
                  <input type="number" class="form-control" id="edad_form_reclamos" name="edad_form_reclamos" value=""  placeholder="edad..">                      
                </div>
		    </div>
    	 			
			<div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="teleono_form_reclamos" class="control-label">Teléfono:</label>
                  <input type="number" class="form-control" id="teleono_form_reclamos" name="teleono_form_reclamos" value=""  placeholder="Telefono..">                      
                </div>
		    </div>
		    
		    </div>
		    
		    <div class="row">
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="celular_form_reclamos" class="control-label">Celular:</label>
                  <input type="number" class="form-control" id="celular_form_reclamos" name="celular_form_reclamos" value=""  placeholder="Celular..">
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="nacionali_form_reclamos" class="control-label">Nacionalidad</label>
                  <input type="text" class="form-control" id="nacionali_form_reclamos" name="nacionali_form_reclamos" value=""  placeholder="Nacionalidad..">
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="email_form_reclamos" class="control-label">E-mail</label>
                  <input type="text" class="form-control" id="email_form_reclamos" name="email_form_reclamos" value=""  placeholder="E-mail..">
                </div>
		    </div>
		    
		     <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="direccion_form_reclamos" class="control-label">Dirección Domiciliaria:</label>
                  <input type="text" class="form-control" id="direccion_form_reclamos" name="direccion_form_reclamos" value=""  placeholder="Direccion..">
                </div>
		    </div>
		 	    
		</div>
			
		<div class="row">
			
		    <div class="col-xs-12 col-md-12 col-md-12 ">
    		    <div class="form-group">
                  <label for="detalle_form_reclamos" class="control-label">Detalle Reclamo:</label>
                  <textarea name="textarea" class="form-control" rows="5" cols="50" id="detalle_form_reclamos" name="detalle_form_reclamos" placeholder="Reclamos.." ></textarea>
                 </div>
		    </div>
		    
		</div>
    	
               	                 
		   <br>  
    	    <div class="row">
    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
        		    <div class="form-group">
                      <button type="button" id="GuardarReclamos" name="GuardarReclamos" class="btn btn-success"><i class="glyphicon glyphicon-expand"></i> Generar Reclamo</button>
                </div>
    		    </div>
    	    </div>
         </form>
          
        </div>
      </div>
    </section>
     
  
  </div>
  
  <div class="col-xs-12 col-md-2 col-md-2 ">
  </div>
  </div>
  
  
  
  
  
   </div>
 


  
 
 

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="view/Administracion/js/FormularioReclamos.js?2.1"></script>
   
   </body>
</html>   