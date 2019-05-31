<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>

    

</style>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<link rel="stylesheet" href="view/bootstrap/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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
        <li class="active">Cuentas Bancarias</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Registrar Cuenta</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="empleados" class="control-label">Empleados:</label>
                    	<select name="empleados" id="empleados"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <?php  foreach($resultEmp as $res) {?>
									  <option value="<?php echo $res->id_empleados; ?>"><?php echo $res->nombres_empleados; ?> </option>
			        				  <?php } ?>
                        </select> 
                        <div id="mensaje_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="banco_empleados" class="control-label">Nombre del Banco:</label>
                           	<select name="banco_empleados" id="banco_empleados"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <?php  foreach($resultBc as $res) {?>
									  <option value="<?php echo $res->id_bancos; ?>"><?php echo $res->nombre_bancos; ?> </option>
			        				  <?php } ?>
                        </select> 
                        <div id="mensaje_banco_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="tipo_cuenta_empleados" class="control-label">Tipo de cuenta:</label>
                           <select name="tipo_cuenta_empleados" id="tipo_cuenta_empleados"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
									  <option value="CORRIENTE">CORRIENTE</option>
									  <option value="AHORROS">AHORROS</option>
                        </select>
                        <div id="mensaje_tipo_cuenta_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="num_cuenta_empleados" class="control-label">Número de cuenta:</label>
                           	<input type="text"  class="form-control" id="num_cuenta_empleados" name="num_cuenta_empleados" placeholder="Número de cuenta">
                        <div id="mensaje_num_cuenta_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarCuenta()">GUARDAR</button>
                  <button type="button" class="btn btn-danger" id="Cancelar" name="Cancelar" onclick="LimpiarCampos()">CANCELAR</button>
                </div>
             </div>	    
            </div>
          </div>
  	</div>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Listado de Cuentas</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_cuentas(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="turno_empleados" id="estado_empleados"  class="form-control" onchange="load_cuentas(1)">
                  		<?php  foreach($resultEst as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_cuentas" ></div>
        	<div id="cuentas_registradas" ></div>
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
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="view/Administracion/js/CuentasEmpleados.js?0.4"></script>
	
	
  </body>
</html> 