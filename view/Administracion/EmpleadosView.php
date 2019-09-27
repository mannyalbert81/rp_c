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
  			<h3 class="box-title">Registrar Empleados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Cedula:</label>
                    	<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_empleado" name="cedula_empleado" placeholder="C.I.">
                        <div id="mensaje_cedula_usuarios" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Nombres:</label>
                    	<input type="text" class="form-control" id="nombre_empleados" name="nombre_empleados" placeholder="Nombres">
                        <div id="mensaje_nombre_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="apellido_empleados" class="control-label">Apellidos:</label>
                    	<input type="text" class="form-control" id="apellido_empleados" name="apellido_empleados" placeholder="Apellidos">
                        <div id="mensaje_apellido_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Departamento:</label>
                    	<select name="dpto_empleados" id="dpto_empleados"  class="form-control" onchange = "SelecCargo(&quot;&quot;)">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <?php  foreach($resultdpto as $res) {?>
									  <option value="<?php echo $res->id_departamento; ?>"><?php echo $res->nombre_departamento; ?> </option>
			        				  <?php } ?>
                        </select> 
                        <div id="mensaje_dpto_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Cargo:</label>
                           	<select name="cargo_empleados" id="cargo_empleados"  class="form-control">
                             <option value="" selected="selected">Seleccione departamento</option>
    					</select>
                        <div id="mensaje_cargo_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Oficina:</label>
                    	<select name="oficina_empleados" id="oficina_empleados"  class="form-control" onchange = "SelecGrupo(&quot;&quot;)">
                                      <option value="" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultOfic as $res) {?>
    										<option value="<?php echo $res->id_oficina; ?>"><?php echo $res->nombre_oficina; ?> </option>
    							        <?php } ?>
    					</select> 
                        <div id="mensaje_oficina_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Turno:</label>
                    	<select name="turno_empleados" id="turno_empleados"  class="form-control">
                             <option value="" selected="selected">Seleccione oficina</option>
    					</select> 
                        <div id="mensaje_turno_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Estado:</label>
                    	<select name="estado_empleados_reg" id="estado_empleados_reg"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
    								  <?php  foreach($resultEst as $res) {?>
									  <option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        				  <?php } ?>
    					</select> 
                        <div id="mensaje_estado_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Forma de pago:</label>
                    	<select name="pago_empleados_reg" id="pago_empleados_reg"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
    								  <?php  foreach($resultMet as $res) {?>
									  <option value="<?php echo $res->id_metodo_pago; ?>"><?php echo $res->nombre_metodo_pago; ?> </option>
			        				  <?php } ?>
    					</select> 
                        <div id="mensaje_pago_empleados" class="errores"></div>
                 	</div>
             	</div>
             		<div class="col-xs-6 col-md-3 col-lg-3" style="margin-top:25px;">
            		<div class="form-group">
                		<button type="button" id="13ro" name="dia" class="btn btn-light" onclick="M13()"><i id="13roicon" class="glyphicon glyphicon-unchecked"></i>MENSALIZUAR 13RO</button>
                 	</div>
             	</div>
             		<div class="col-xs-6 col-md-3 col-lg-3" style="margin-top:25px;">
            		<div class="form-group">
                		<button type="button" id="14to" name="dia" class="btn btn-light" onclick="M14()"><i id="14toicon" class="glyphicon glyphicon-unchecked"></i>MENSALIZUAR 14TO</button>
                 	</div>
             	</div> 
             		<div class="col-xs-6 col-md-3 col-lg-3" style="margin-top:25px;">
            		<div class="form-group">
                		<button type="button" id="fr" name="dia" class="btn btn-light" onclick="MFR()"><i id="fricon" class="glyphicon glyphicon-unchecked"></i>MENSALIZUAR F. DE RESERVA</button>
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
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Listado de Empleados</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_empleados(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="turno_empleados" id="estado_empleados"  class="form-control" onchange="load_empleados(1)">
                  		<?php  foreach($resultEst as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_empleados" ></div>
        	<div id="empleados_registrados" ></div>
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