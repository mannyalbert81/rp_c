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
          <h3 class="box-title">Registrar Periodos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_periodo" action="<?php echo $helper->url("Periodo","InsertaPeriodo"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
							    
							    
		    	 <div class="row">
        		 <input type="hidden" id="id_periodo" value="0">
            		  
        <div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="anio_periodo" class="control-label">AÑO :</label>
	         		<input type="number" id="anio_periodo" name="anio_periodo" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>" class="form-control">
                    </div>
	         </div>
            		  
            		  		  <div class="col-xs-12 col-md-4 col-md-4 ">
            		    <div class="form-group">
            		    					  
                          <label for="mes_periodo" class="control-label">Mes:</label>
                          <select  class="form-control" id="mes_periodo" name="mes_periodo" required>
                            	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                      	<?php if( $i == date('n')){ ?>
                      	<option value="<?php echo $i;?>" selected ><?php echo $meses[$i-1]; ?></option>
                      	<?php }else{?>
                      	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>
                      	<?php }}?>
                          </select>                         
                          <div id="mensaje_mes_periodo" class="errores"></div>
                        </div>
            		  </div>
            		  
            		  		  <div class="col-xs-12 col-md-4 col-md-4 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_tipo_cierre" class="control-label">Tipo Cierre:</label>
                          <select  class="form-control" id="id_tipo_cierre" name="id_tipo_cierre" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_tipo_cierre" class="errores"></div>
                        </div>
            		  </div>
            		  
            		  		  <div class="col-xs-12 col-md-3 col-md-3 " style="display: none">
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
    	                  <button type="button" id="btnAbrir" name="btnAbrir" class="btn btn-success">Abrir</button>
    	                  <button type="button" id="btnCerrar" name="btnCerrar" class="btn btn-danger">Cerrar</button>
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
      			<h3 class="box-title">Listado de Periodos</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaPeriodo(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="periodo_registrados" ></div>
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
   <script src="view/Contable/FuncionesJS/Periodo.js?0.29"></script> 
       
       

 	
	
	
  </body>
</html>   

