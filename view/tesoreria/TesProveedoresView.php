<!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> 
    <link rel="stylesheet" href="view/bootstrap/bower_components/select2/dist/css/select2.min.css">
  
    <?php include("view/modulos/links_css.php"); ?>		
      
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
        <li class="active">Productos</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Proveedores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        <!-- INPUTS HIDDEN --> 
        <input type="hidden" name="id_proveedores" id="id_proveedores" value="" />    
        
        <form action="<?php echo $helper->url("Proveedores","index"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        
    	 <div class="row">
    	 
    	 	<div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="tipo_identificacion_proveedores" class="control-label">Tipo Identificación (SRI) </label>
                  <select class="form-control" id="tipo_identificacion_proveedores" name="tipo_identificacion_proveedores" >
                  	<option value="0">--Seleccione--</option>
                  	<option value="04">RUC</option>
                  	<option value="05">CEDULA</option>
                  	<option value="06">PASAPORTE</option>
                  	<option value="07">VENTA A CONSUMIDOR FINAL*</option>
                  	<option value="08">IDENTIFICACION DELEXTERIOR*</option>
                  </select>
                </div>                
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="id_tipo_proveedor" class="control-label">Tipo Proveedores</label>
                  <select class="form-control" id="id_tipo_proveedores" name="id_tipo_proveedores" >
                  <option value="0">--SELECCIONE--</option>
                  </select>                                                             
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="identificacion_proveedores" class="control-label">CI / Ruc Proveedores</label>
                  <input type="number" class="form-control" id="identificacion_proveedores" name="identificacion_proveedores" value=""  placeholder="ruc.." onKeyPress="return numeros(event)">
                </div>
		    </div>
    	 			
			<div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="nombre_proveedores" class="control-label">Nombre Proveedores</label>
                  <input type="text" class="form-control" id="nombre_proveedores" name="nombre_proveedores" value=""  placeholder="Nombre Proveedores">                      
                </div>
		    </div>
		    
		    </div>
		    
		    <div class="row">
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="razon_social_proveedores" class="control-label">Razon Social</label>
                  <input type="text" class="form-control" id="razon_social_proveedores" name="razon_social_proveedores" value=""  placeholder="Razon Social">
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="contactos_proveedores" class="control-label">Contactos Proveedores</label>
                  <input type="text" class="form-control" id="contactos_proveedores" name="contactos_proveedores" value=""  placeholder="">
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="direccion_proveedores" class="control-label">Dirección Proveedores</label>
                  <input type="text" class="form-control" id="direccion_proveedores" name="direccion_proveedores" value=""  placeholder="Dirección Proveedores">
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="telefono_proveedores" class="control-label">Teléfono Proveedores</label>
                  <input type="number" class="form-control" id="telefono_proveedores" minlength="7" maxlength="7" name="telefono_proveedores" value=""  placeholder="" onKeyPress="return numeros(event)">
                </div>
		    </div>
    		    
		</div>
			
		<div class="row">
			
			<div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="celular_proveedores" class="control-label">Celular Proveedores</label>
                  <input type="number" class="form-control" id="celular_proveedores" minlength="10" maxlength="10" value=""  placeholder="" ">
                </div>
		    </div>
    			
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="email_proveedores" class="control-label">Email Proveedores</label>
                  <input type="text" class="form-control" id="email_proveedores" name="email_proveedores" value=""  placeholder="" >                                       
                </div>
		    </div>
		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="forma_pago" class="control-label">Forma Pago:</label>
                  <select class="form-control" id="forma_pago" name="forma_pago" onchange="validaFormaPago()" >
                    <option value="0">--SELECCIONE--</option>
                  	<option value="cheque">CHEQUE</option>
                  	<option value="transferencia">TRANSFERENCIA</option>
                  </select>                                       
                </div>
		    </div>
		    
		</div>
    	<div class="row">
    		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="id_bancos" class="control-label">Banco:</label>
                  <select class="form-control" id="id_bancos" name="id_bancos" disabled >
                  	<option value="0">--SELECCIONE--</option>
                  </select>
                   <div id="mensaje_bancos" class="errores"></div>
                                       
                </div>
		    </div>
    		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="id_tipo_cuenta" class="control-label">Tipo Cuenta:</label>
                  <select class="form-control" id="id_tipo_cuentas" name="id_tipo_cuentas" disabled>
                  	<option value="0">--SELECCIONE--</option>
                  </select>
                  <div id="mensaje_tipo_cuenta" class="errores"></div>
                                       
                </div>
		    </div>
    		    
		    <div class="col-xs-12 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="numero_cuenta_proveedores" class="control-label">Numero Cuenta:</label>
                  <input type="text" class="form-control" id="numero_cuenta_proveedores" name="numero_cuenta_proveedores" value=""  placeholder="Numero Cuenta" disabled >
                  <div id="mensaje_numero_cuenta" class="errores"></div>
                                       
                </div>
		    </div>    		    
    		    
			</div>
               	                 
		   <br>  
    	    <div class="row">
    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
        		    <div class="form-group">
                      <button type="button" id="GuardarProveedores" name="GuardarProveedores" class="btn btn-success">Guardar</button>
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
          <h3 class="box-title">Listado de Proveedores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
         <div id="lista_proveedores">
  			<div class="row">
  				<div class=" pull-left " >
  					<div class=" col-lg-12 form-group-sm">
                    	<span class="form-control" id="cantidad_busqueda"><strong>Registros: </strong>0</span>
                    	<input type="hidden" value="" id="total_query" name="total_query"/>
                	</div>   
            	</div>
  				<div class="pull-right">
  					<div class=" col-lg-12 form-group-sm">                    				
        				 <input type="text" class="form-control" id="txtbuscar" name="txtbuscar" value="" >
            		</div>            			
          		</div>
  			</div>  
  			<br>			
  			<div id="tabla_datos_proveedores">
  			</div>
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
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="view/bootstrap/bower_components/select2/dist/js/select2.full.min.js"></script> 
	<script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script src="view/tesoreria/js/TesProveedores.js?0.03" ></script>
  </body>
</html>   



