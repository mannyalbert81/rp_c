<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <!-- <meta charset="utf-8">  -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        <li class="active">Impuestos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Impuestos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <!-- IDENTIFICADRES -->
        <input type="hidden" value="0" id="id_impuestos" >
        <input type="hidden" name="id_plan_cuentas" id="id_plan_cuentas" value="0" />
                  
  		<div class="box-body">

			<form id="frm_impuestos" action="<?php echo $helper->url("Impuestos","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             	
             	<!-- para el loader de procesos -->
             	<div id="divLoaderPage" ></div>
             	<!-- termina loader --> 
             					    
		    	 <div class="row">
		    	 
		    	 	<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="fuente_impuestos" class="control-label">Fuente Impuestos:</label>
                          <select class="form-control" id="fuente_impuestos" name="fuente_impuestos">
                            <option value="0" >--Seleccione--</option>
                          	<option value="compra" selected >COMPRA</option>
                          	<option value="venta">VENTA</option>
                          </select>
                                              
                        </div>
            		  </div>
		    	 
        		    <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="plan_cuentas" class="control-label">Cuenta - (Plan Cuentas):</label>
                          <input  type="text" class="form-control" id="plan_cuentas" name="plan_cuentas" value=""  placeholder="Ingrese Nombre/codigo Cuenta" />                          
                          <div id="mensaje_plan_cuentas" class="errores"></div>
                                              
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="nombre_impuestos" class="control-label">Nombre Impuestos:</label>
                          <input  type="text" class="form-control" id="nombre_impuestos" name="nombre_impuestos" value=""  placeholder="Digite Nombre" />                                           
                        </div>
            		  </div>
            		  
            		  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="descripcion_impuestos" class="control-label">Descripcion Impuestos:</label>
                          <input  type="text" class="form-control" id="descripcion_impuestos" name="descripcion_impuestos" value=""  placeholder="Ingrese Descripcion" />
                          <div id="mensaje_porcentaje_impuestos" class="errores"></div>
                                               
                        </div>
            		  </div>    	  
            		  
          	   	</div>
          	   	
          	   	<div class="row">
          	   	
          	   		<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="porcentaje_impuestos" class="control-label">Porcentaje Impuestos (%):</label>
                          <input  type="text" class="form-control" id="porcentaje_impuestos" name="porcentaje_impuestos" value=""  placeholder="Ingrese Porcentaje" />
                          <div id="mensaje_porcentaje_impuestos" class="errores"></div>
                                               
                        </div>
            		  </div>    
          	   	
          	   		<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="tipo_impuestos" class="control-label">Tipo Impuestos:</label>
                          <select class="form-control" id="tipo_impuestos" name="tipo_impuestos" onchange="validaTipoImpuesto()">
                          	<option value="iva">IVA</option>
                          	<option value="retencion">RETENCION</option>
                          </select>
                          <div id="mensaje_tipo_impuestos" class="errores"></div>
                                               
                        </div>
            		  </div>
          	   	
          	   		<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">            		    					  
                          	<label for="codigo_impuestos" class="control-label">Impuesto Retener (Tabla 19):</label>
                            <select class="form-control" id="codigo_impuestos" onchange="getCodigoRetencion(this)">
                            	<option value="0">--Seleccione--</option>
                             	<option value="1">RENTA</option>
                              	<option value="2">IVA</option>
                              	<option value="6">ISD</option>
                            </select>                                               
                        </div>
            		  </div>
            		  
            		 <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">            		    					  
                          	<label for="codretencion_impuestos" class="control-label">Codigo Retencion(Tabla 20):</label>
                            <select class="form-control" id="codretencion_impuestos" name="codretencion_impuestos">
                             	<option value="0">--Seleccione--</option>
                            </select>                                               
                        </div>
            		  </div>
          	   		
          	   	</div>	
          	   	

							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="button" id="Guardar" name="Guardar" onclick="AddImpuesto()" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("Impuestos","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
      			<h3 class="box-title">Listado de Impuestos</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaImpuestos(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="impuestos_registrados" ></div>
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
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
   <script src="view/tesoreria/js/Impuestos.js?0.07"></script> 
	
  </body>
</html>   

