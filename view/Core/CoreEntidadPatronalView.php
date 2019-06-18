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
        <li class="active">Entidad Patronal</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Entidad</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_entidad" action="<?php echo $helper->url("CoreEntidadPatronal","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
							    
							    
		    	 <div class="row">
        		    <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="nombre_entidad_patronal" class="control-label">Nombre Entidad:</label>
                          <input  type="text" class="form-control" id="nombre_entidad_patronal" name="nombre_entidad_patronal" value=""  placeholder="Nombre Entidad" onkeypress="mayus(this);"/>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_nombre_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="ruc_entidad_patronal" class="control-label">Ruc:</label>
                          <input  type="text" class="form-control" id="ruc_entidad_patronal" name="ruc_entidad_patronal" value=""  placeholder="Ruc" onKeyPress="return soloNumeros(event)"/>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_ruc_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="codigo_entidad_patronal" class="control-label">Código:</label>
                          <input  type="text" class="form-control" id="codigo_entidad_patronal" name="codigo_entidad_patronal" value=""  placeholder="Código"/>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_codigo_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="tipo_entidad_patronal" class="control-label">Tipo de Entidad:</label>
                          <input  type="text" class="form-control" id="tipo_entidad_patronal" name="tipo_entidad_patronal" value=""  placeholder="Tipo" onKeyPress="return soloNumeros(event)"/>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_tipo_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="acronimo_entidad_patronal" class="control-label">Acrónimo:</label>
                          <input  type="text" class="form-control" id="acronimo_entidad_patronal" name="acronimo_entidad_patronal" value=""  placeholder="Acrónimo" onkeypress="mayus(this);"/>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_acronimo_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="direccion_entidad_patronal" class="control-label">Dirección:</label>
                          <textarea  class="form-control" id="direccion_entidad_patronal" name="direccion_entidad_patronal" value=""  placeholder="Dirección" rows="2" cols="50"></textarea>
                          <input type="hidden" name="id_entidad_patronal" id="id_entidad_patronal" value="0" />
                          <div id="mensaje_direccion_entidad_patronal" class="errores"></div>
                          <div id="divLoaderPage" ></div>                     	
                                              
                        </div>
            		  </div>  
                        		 
				    
          	   	</div>	
							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("CoreEntidadPatronal","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
      			<h3 class="box-title">Entidades Registradas</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaEntidadPatronal(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="entidades_registradas" ></div>
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
   <script src="view/Core/js/CoreEntidadPatronal.js?0.5"></script> 
   <script type="text/javascript">
// Solo permite ingresar numeros.
function soloNumeros(e){
	var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57)
}
</script>    
       
 <script type="text/javascript">

 function mayus(e) {

	 e.value = e.value.toUpperCase();
	}
 </script>
 	
	
	
  </body>
</html>   

