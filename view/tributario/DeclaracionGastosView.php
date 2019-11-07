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
        <li class="active">Empleados</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">DECLARACIÓN DE GASTOS PERSONALES A SER UTILIZADOS POR EL EMPLEADOR EN EL CASO DE INGRESOS EN RELACIÓN DE DEPENDENCIA</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="table">
         <div class="row">
          		<div class="col-xs-12 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Información / Identificación del empleado contribuyente a ser llenado por el empleado</label>
                    </div>
             	</div>
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">101</label>
                   </div>
             	</div>
             	<div class="col-xs-4 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Cedula:</label>
                    	<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_empleado" name="cedula_empleado" placeholder="C.I.">
                        <div id="mensaje_cedula_usuarios" class="errores"></div>
                 	</div>
                 	</div>
                 	
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">102</label>
                   </div>
             	</div> 
             	<div class="col-xs-4 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Nombres:</label>
                    	<input type="text" class="form-control" id="nombre_empleados" name="nombre_empleados" placeholder="Nombres">
                        <div id="mensaje_nombre_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-4 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="apellido_empleados" class="control-label">Apellidos:</label>
                    	<input type="text" class="form-control" id="apellido_empleados" name="apellido_empleados" placeholder="Apellidos">
                        <div id="mensaje_apellido_empleados" class="errores"></div>
                 	</div>
             	</div>
             	</div>
             	
          	<div class="row">
          		<div class="col-xs-12 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">INGRESOS GRAVADOS PROYECTADOS (sin decimotercera y decimocuarta remuneració)(ver Nota 1)</label>
                   </div>
             	</div>
             	</div>
             	
             	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="" class="control-label">(+) TOTAL INGRESOS GRAVADOS CON ESTE EMPLEADOR (con el empleador que más ingresos perciba)</label>
             </div>
             	</div>
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">103</label>
                   </div>
             	</div> 
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">USD$</label>
                    	<input type="text" class="form-control" id="" name="" placeholder="">
                   </div>
             	</div> 
             	</div> 
             	
             	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="" class="control-label">(+) TOTAL INGRESOS CON OTROS EMPLEADOS (en caso de haberlos)</label>
             </div>
             	</div>
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">104</label>
                   </div>
             	</div> 
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">USD$</label>
                    	<input type="text" class="form-control" id="" name="" placeholder="">
                   </div>
             	</div> 
             	</div> 
             	
             	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="" class="control-label">(=) TOTAL INGRESOS PROYECTADOS</label>
             </div>
             	</div>
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">105</label>
                   </div>
             	</div> 
             	<div class="col-xs-1 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="" class="control-label">USD$</label>
                    	<input type="text" class="form-control" id="" name="" placeholder="">
                   </div>
             	</div> 
             	</div>
             	
             	<div class="row">
          		<div class="col-xs-12 col-md-3 col-lg-3">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">GASTOS PROYECTADOS</label>
                   </div>
             	</div>
             	</div> 
             	
             	
             	
             	
             	
             	
             	
             	
  
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarEmpleado()">GUARDAR</button>
                  <button type="button" class="btn btn-danger" id="Cancelar" name="Cancelar" onclick="LimpiarCampos()">CANCELAR</button>
                </div>
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
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="view/Administracion/js/Empleados.js?0.12"></script>
	
	
  </body>
</html> 