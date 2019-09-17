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
        <li class="active">Bancos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Carga Recaudaciones</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
  		<div class="box-body">

			<form id="frm_carga_recaudaciones" action="<?php echo $helper->url("CargaRecaudaciones","InsertaCargaRecaudaciones"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
							    
		    	 <div class="row">
        		 <input type="hidden" id="id_carga_recaudaciones" value="0">
            		  
       		  		  <div class="col-xs-12 col-md-4 col-md-4 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_entidad_patronal" class="control-label">Entidad:</label>
                          <select  class="form-control" id="id_entidad_patronal" name="id_entidad_patronal" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_entidad_patronal" class="errores"></div>
                        </div>
            		  </div>
            		  
            		  <div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="year_periodo" class="control-label">AÑO :</label>
	         		<input type="number" id="anio_carga_recaudaciones" name="anio_carga_recaudaciones" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>" class="form-control">
                    </div>
                        <div id="mensaje_anio_carga_recaudaciones" class="errores"></div>
                      </div>
	         
	          		  		  <div class="col-xs-12 col-md-4 col-lg-4 ">
            		    <div class="form-group">
            		    					  
                          <label for="mes_carga_recaudaciones" class="control-label">Mes:</label>
                          <select  class="form-control" id="mes_carga_recaudaciones" name="mes_carga_recaudaciones" required>
                            	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                      	<?php if( $i == date('n')){ ?>
                      	<option value="<?php echo $i;?>" selected ><?php echo $meses[$i-1]; ?></option>
                      	<?php }else{?>
                      	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>
                      	<?php }}?>
                          </select>                         
                          <div id="mensaje_mes_carga_recaudaciones" class="errores"></div>
                        </div>
            		  </div>
            		  
            		  	<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="formato_carga_recaudaciones" class="col-sm-4 control-label" >Formato:</label>
                				<div class="col-sm-8">
                                  	<select id="formato_carga_recaudaciones" name="formato_carga_recaudaciones" class="form-control">
                                  	<option value="1" >DESCUENTOS APORTES</option>
                                  	<option value="2" >DESCUENTOS CREDITOS</option>
                                  	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
            		 
            		 
            		     		  <div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_carga_recaudaciones" class="control-label">Documento :</label>
	         	   <input type="file" name="nombre_carga_recaudaciones" id="nombre_carga_recaudaciones" value=""  class="form-control"/> 
		    </div>
                        <div id="mensaje_nombre_carga_recaudaciones" class="errores"></div>
                      </div>
    			    
          	   	</div>	
						<br>	          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    
    	                 <div class="col-sm-4">
                                  	<button type="button" id="btnGenerar" name="btnGenerar" class="btn btn-block btn-sm btn-default">GENERAR</button>
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
      			<h3 class="box-title">Listado de Cargas</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaCargaRecaudaciones(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="carga_recaudaciones_registrados" ></div>
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
   <script src="view/Recaudaciones/js/CargaRecaudaciones.js?0.4"></script> 

  </body>
</html>   

