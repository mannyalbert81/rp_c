<!DOCTYPE html>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
     
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
            <li class="active">Envio Mensajes Texto</li>
          </ol>
        </section>
        
        <section class="content">
        
          <form id="frm_mensajes" action="<?php echo $helper->url("EnvioMensajesTexto","Enviar"); ?>" method="post" enctype="multipart/form-data"  class="form form-horizontal">
  
        
        	<div id="smartwizard">
                <ul>
                    <li><a href="#step-1">Mensaje Texto<br /><small> </small></a></li>
                    <li><a href="#step-2">Destinatarios<br /><small></small></a></li>
                </ul>
            
             <div>
                <div id="step-1" class="">
                  <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Parametrizar Mensaje</h3>
               <div class="box-tools pull-right"> </div>
              
            </div>
            
            <div class="box-body">
            <div class="row">
            
        			<div class="col-lg-6 col-xs-12 col-md-6">
                          <div class="form-group-sm">
                          <label for="" class="control-label">Parámetros:</label>
                          <input type="text" class="form-control" id="txt_var1" name="txt_var1" onkeyup="formato_mensajes()" value=""  placeholder="(Var1)">
                    	  <input type="text" class="form-control" id="txt_var2" name="txt_var2" onkeyup="formato_mensajes()" value=""  placeholder="(Var2)">
                    	  <input type="text" class="form-control" id="txt_var3" name="txt_var3" onkeyup="formato_mensajes()" value=""  placeholder="(Var3)">
                    	  <input type="text" class="form-control" id="txt_var4" name="txt_var4" onkeyup="formato_mensajes()" value=""  placeholder="(Var4)">
                    	</div>
           		   </div>
           		   
           		   <div class="col-xs-12 col-lg-6 col-md-6 ">
            		    <div class="form-group-sm">
                           <label class="control-label"> Caracteres Ingresados: <span  id="caracteres">0</span></label>
                              <textarea class="form-control" id="mensaje" name="mensaje" rows="6" maxlength="150" placeholder="" readonly></textarea>
                           <label class="control-label"> Caracteres Permitidos: <span>150</span></label>
                          
                        </div>
            		  </div>
           		  
           </div>
                
      </div>
     </div>
                </div>
           
                <div id="step-2" class="">
                
                  <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Destinatarios</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
              </div>
            </div>
            
            <div class="box-body">
           
            <div class="col-lg-12 col-md-12 col-xs-12">
            
            <div class="col-lg-6 col-md-6 col-xs-12">
             <div class="row">
            
        			<div class="col-lg-10 col-xs-12 col-md-10">
                          <div class="form-group-sm">
                          <label for="txt_directorio" class="control-label">Directorios:</label>
                          <input type="text" class="form-control" id="txt_directorio" name="txt_directorio" value=""  placeholder="Ingrese Datos Participe">
                    	  <input type="hidden"  id="id_participes" name="id_participes" value="0">
			         	  </div>
           		   </div>
           		   
           		   <div class="col-lg-2 col-xs-12 col-md-2" style="margin-top: 23px;">
           		   		  <span class="input-group-btn">
			         		<button type="button" id="btn_agregar" name="btn_agregar" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>
	                 		<button type="button" id="btn_quitar" name="btn_quitar" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
			        	   </span>
           		   </div>
           </div>
         
	    
	    <div class="row">
              		 <div class="col-lg-12 col-xs-12 col-md-12">
                          <div class="form-group-sm">
                          <label for="participes_to" class="control-label">Participes Agregados:</label>
                          <select id="participes_to" name="participes_to[]" size="4" class="form-control" multiple="multiple">
				          </select>
				          <div id="mensaje_participes_to" class="errores"></div>
                          </div>
                     </div>
        </div>
        
            </div>
           
            <div class="col-lg-6 col-md-6 col-xs-12">
             <div class="row">
            
        			<div class="col-lg-10 col-xs-12 col-md-10">
                          <div class="form-group-sm">
                          <label for="txt_directorio_celular" class="control-label">Directorios Extras:</label>
                          <input type="number" class="form-control" id="txt_directorio_celular" name="txt_directorio_celular" value=""  placeholder="Ingrese Número Celular">
                    	  </div>
           		   </div>
           		   
           		   <div class="col-lg-2 col-xs-12 col-md-2" style="margin-top: 23px;">
           		   		  <span class="input-group-btn">
			         		<button type="button" id="btn_agregar_celular" name="btn_agregar_celular" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>
	                 		<button type="button" id="btn_quitar_celular" name="btn_quitar_celular" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
			        	   </span>
           		   </div>
           </div>
            
            <div class="row">
              		 <div class="col-lg-12 col-xs-12 col-md-12">
                          <div class="form-group-sm">
                          <label for="participes_to_celular" class="control-label">Extras Agregados:</label>
                          <select id="participes_to_celular" name="participes_to_celular[]" size="4" class="form-control" multiple="multiple">
				          </select>
				          <div id="mensaje_participes_to_celular" class="errores"></div>
                          </div>
                     </div>
        </div>
           </div>
           </div>
           
                   
      </div>
     </div>
                
                
                </div>
           
           
            </div>
        
        </div>
        
        </form>
    </section>
        
        
  </div>
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
   <?php include("view/modulos/links_js.php"); ?>
   
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
   <script type="text/javascript" src="view/bootstrap/smartwizard/dist/js/jquery.smartWizard.min.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/Administracion/js/EnviaMensajesTexto.js?1.21"></script> 
   <script type="text/javascript" src="view/Administracion/js/wizardEnviaMensajesTexto.js?0.13"></script>
    	
    	
  
  
  </body>
</html>


       
       
      
 




