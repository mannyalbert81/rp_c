<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	
    
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
        <li class="active">Periodo Contable</li>
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
        	         		<label for="anio_periodo" class="control-label">AÑO PERIODO:</label>
        	         		<select id="anio_periodo" class="form-control">
        	         			<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
        	         		</select>	         		
                        </div>
        	         </div> 
            		  
          	   	</div>
          	   	
          	   	<div class="row">
          	   		<div class="col-xs-12 col-md-12 col-md-12 ">
          	   			<div class="pull-right">
          	   				<button id="" type="button" class="btn btn-default" onclick="RegistrarDetallePeriodo(event)"><i class="fa  fa-mail-forward"></i> Registrar</button>
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
      			<h3 class="box-title">Detalles del Periodo</h3>      			
            </div> 
            <div class="box-body">
                       
            
            	<div class="box-body no-padding">
              		<table id="tbl_detalles_periodo" class="table table-striped table-bordered table-sm " cellspacing="0"  width="100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>AÑO</th>
                          <th>MES</th>
                          <th>ESTADO</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>  
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
   <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
   <script src="view/Contable/FuncionesJS/AperturaMes.js?0.04"></script> 
       
       

 	
	
	
  </body>
</html>   

