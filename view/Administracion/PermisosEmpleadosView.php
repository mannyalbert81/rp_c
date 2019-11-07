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
        <li class="active">Solicitud de permisos</li>
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
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Días disponibles:</label>
                           	<input type="text" class="form-control" id="dias_disponibles" name="dias_disponibles" placeholder="Días disponibles" readonly>
                        <div id="mensaje_dias_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">Fecha:</label>
                    	<input type="date"  class="form-control" id="fecha_permiso" name="fecha_permiso" placeholder="Fecha">
                        <div id="mensaje_fecha_permiso" class="errores"></div>
                 	</div>
             	</div>
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="hora_desde" class="control-label">Desde:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_desde" name="hora_desde" placeholder="Hora">
                        <div id="mensaje_hora_desde" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="hora_hasta" class="control-label">Hasta:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_hasta" name="hora_hasta" placeholder="Hora">
                        <div id="mensaje_hora_hasta" class="errores"></div>
                 	</div>
             	</div>    
             	<div class="col-xs-6 col-md-3 col-lg-3" style="margin-top:25px;">
            		<div class="form-group">
                		<button type="button" id="dia" name="dia" class="btn btn-light" onclick="TodoElDia()"><i id="diaicon" class="glyphicon glyphicon-unchecked"></i>TODO EL DIA</button>
                 	</div>
             	</div>         
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="causa_permiso" class="control-label">Causa:</label>
                    	<select name="causa_permiso" id="causa_permiso"  class="form-control" onchange = "HabilitarDescripcion()">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <?php  foreach($resultcau as $res) {?>
									  <option value="<?php echo $res->id_causa; ?>"><?php echo $res->nombre_causa; ?> </option>
			        				  <?php } ?>
                        </select> 
                        <div id="mensaje_causa_permiso" class="errores"></div>
                 	</div>
             	</div> 
          		<div class="col-xs-9 col-md-9 col-md-9">
          			<div class="form-group">
                		<label for="descripcion_causa" class="control-label">Descripción:</label>
                    	<input type="text"  maxlength="100" class="form-control" id="descripcion_causa" name="descripcion_causa" placeholder="Descripción" readonly>
                        <div id="mensaje_descripcion_causa" class="errores"></div>
                 	</div>
          		</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
            	<input type="hidden" id="valor_editar_permiso" value="0">
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
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- BEGIN MODAL EDICION PERMISOS-->
 
 <div class="modal fade" id="mod_permisos_empleados" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:40%">
        <div class="modal-content">
          <div class="modal-header bg-orange disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Editar Permiso Empleado</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_edit_permiso_empleado" name="frm_edit_permiso_empleado">
          	
          	<div class="row">
          		<div class="col-lg-12 col-md-12 col-xs-12">
          			<h5>Datos Participe</h5>
          			<input type="hidden" class="form-control " id="mod_id_permiso" name="mod_id_permiso" >
          		</div>
              	<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Fecha:</p>
            				<div class="col-sm-8">
                              	<input type="date"  class="form-control" id="mod_fecha_permiso" name="mod_fecha_permiso" placeholder="Fecha">
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Hora Inicio:</p>
            				<div class="col-sm-8">
                              	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="mod_hora_desde" name="mod_hora_desde" placeholder="Hora">                            	
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Hora Fin:</p>
            				<div class="col-sm-8">
                              	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="mod_hora_hasta" name="mod_hora_hasta" placeholder="Hora">
                             </div>
            			 </div>        			 
        			</div>
				</div>
              	<div class="col-lg-12 col-md-12 col-xs-12">        		
    			<div class="form-group "> 
        			 <div class="form-group-sm">
        				<p class="text-muted col-sm-4 control-label"></p>
        				<div class="col-sm-8">
                          	<button type="button" id="mod_dia" name="mod_dia" class="btn btn-light" onclick="mod_TodoElDia()"><i id="mod_diaicon" class="glyphicon glyphicon-unchecked"></i>TODO EL DIA</button>
                         </div>
        			 </div>        			 
    			</div>
    			</div>
    			<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<p class="text-muted col-sm-4 control-label">Causa:</p>
            				<div class="col-sm-8">
                              	<select name="causa_permiso" id="mod_causa_permiso"  class="form-control" onchange = "mod_HabilitarDescripcion()">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <?php  foreach($resultcau as $res) {?>
									  <option value="<?php echo $res->id_causa; ?>"><?php echo $res->nombre_causa; ?> </option>
			        				  <?php } ?>
                        		</select> 
                             </div>
            			 </div>        			 
        			</div>
    			</div>
    			<div class="col-lg-12 col-md-12 col-xs-12">        		
    			<div class="form-group "> 
        			 <div class="form-group-sm">
        				<p class="text-muted col-sm-4 control-label">Descripcion:</p>
        				<div class="col-sm-8">
                          	<input type="text"  maxlength="100" class="form-control" id="mod_descripcion_causa" name="mod_descripcion_causa" placeholder="Descripción" readonly>
                         </div>
        			 </div>        			 
    			</div>
    			</div>
          	</div>
          	
          	<div id="msg_frm_recaudacion" ></div> 
          	
          	<div class="clearfix"></div>         	
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" id="btnEditPermiso" class="btn btn-default" onclick="EditaSolicitud()" >Aceptar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>

<!-- END MODAL EDICION PERMISOS -->
    
    <?php include("view/modulos/links_js.php"); ?>
	
	 
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="view/Administracion/js/PermisosEmpleados.js?0.20"></script>
	
	
  </body>
</html> 