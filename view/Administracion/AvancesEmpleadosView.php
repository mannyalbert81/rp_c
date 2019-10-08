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
        <li class="active">Solicitud avance de sueldo</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Registrar Solicitud</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Nombres:</label>
                    	<input type="text" class="form-control" id="nombre_empleados" name="nombre_empleados" placeholder="Nombres" readonly>
                        <div id="mensaje_nombre_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Departamento:</label>
                    	<input type="text" class="form-control" id="dpto_empleados" name="dpto_empleados" placeholder="Departamento" readonly>
                        <div id="mensaje_dpto_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Cargo:</label>
                           	<input type="text" class="form-control" id="cargo_empleados" name="cargo_empleados" placeholder="Cargo" readonly>
                        <div id="mensaje_cargo_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">Fecha:</label>
                    	<input type="date"  class="form-control" id="fecha" name="fecha" placeholder="Fecha">
                        <div id="mensaje_fecha" class="errores"></div>
                 	</div>
             	</div>
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="monto_avance" class="control-label">Monto avance:</label>
                    	<input type="number" step=0.01 class="form-control" id="monto_avance" name="monto_avance" placeholder="Monto avance">
                        <div id="mensaje_monto_avance" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cuotas" class="control-label">Cuotas:</label>
                    	<input type="number" step=1 class="form-control" id="cuotas" name="cuotas" placeholder="Cuotas">
                        <div id="mensaje_cuotas" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarSolicitud()">GUARDAR</button>
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
  			<h3 class="box-title">Listado de Solicitudes</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_solicitudes(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="turno_empleados" id="estado_solicitudes"  class="form-control" onchange="load_solicitudes(1)">
        				<option value="0" selected="selected">TODOS</option>
                  		<?php  foreach($resultes as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_solicitudes" ></div>
        	<div id="solicitudes_registrados" ></div>
        </div> 	
  	</div>
  </section> 
 </div>
 
 <!-- Modal VistaPreliminar -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAjustes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Avance</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div id="preliminar_archivo"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
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
    <script src="view/Administracion/js/AvancesEmpleados.js?0.21"></script>
	
	
  </body>
</html>