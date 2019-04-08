<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
.modal-lg {
    max-width: 20% !important;
}
    

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
        <li class="active">Horarios</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Registrar/Editar Horarios</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="hora_entrada" class="control-label">Entrada:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_entrada" name="hora_entrada" placeholder="Entrada">
                        <div id="mensaje_hora_entrada" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Salida Almuerzo:</label>
                    	<input type="text" data-inputmask="'mask':'h:s:s'" class="form-control" id="hora_salida_almuerzo" name="hora_salida_almuerzo" placeholder="Salida Almuerzo">
                        <div id="mensaje_salida_almuerzo" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="apellido_empleados" class="control-label">Entrada Almuerzo:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_entrada_almuerzo" name="hora_entrada_almuerzo" placeholder="Entrada Almuerzo">
                        <div id="mensaje_entrada_almuerzo" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Salida:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_salida" name="hora_salida" placeholder="Salida">
                        <div id="mensaje_hora_salida" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
             		<div class="form-group">
            			<label for="dpto_empleados" class="control-label">Oficina:</label>	
                    	<select name="oficina_horarios_reg" id="oficina_horarios_reg"  class="form-control" onchange = "SelecGrupo(&quot;&quot;)" >
                        	<option value="" selected="selected">--Seleccione--</option>
    						<?php  foreach($resultOfic as $res) {?>
							<option value="<?php echo $res->id_oficina; ?>"><?php echo $res->nombre_oficina; ?> </option>
			        		<?php } ?>
    					</select>
    					<div id="mensaje_oficina_horaios" class="errores"></div>
                 	</div>
             	</div> 
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             		<label for="dpto_empleados" class="control-label">Grupo:</label>
            		<div class="input-group">
                    	<select name="turno_empleados" id="turno_empleados"  class="form-control" >
                        	<option value="" selected="selected">Seleccione oficina</option>
    					</select>
    					<div id="mensaje_turno_empleados" class="errores"></div>
    					<span class="input-group-btn">
    						<button type="button" id="nuevo_grupo" name="nuevo_grupo" class="btn btn-primary" data-toggle="modal" data-target="#myModal" ><i class='glyphicon glyphicon-plus'></i></button> 
                        </span>	
                        <span class="input-group-btn">
    						<button type="button" id="eliminar_grupo" name="eliminar_grupo" class="btn btn-danger" data-toggle="modal" data-target="#myModalElim" onclick="SelecGrupo(&quot;&quot;)" ><i class='glyphicon glyphicon-trash'></i></button> 
                        </span>			
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
            			<label for="dpto_empleados" class="control-label">Estado:</label>	
                    	<select name="estado_horarios_reg" id="estado_horarios_reg"  class="form-control" >
                        	<option value="" selected="selected">--Seleccione--</option>
    						<?php  foreach($resultEst as $res) {?>
							<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        		<?php } ?>
    					</select>
    					<div id="mensaje_estado_horaios" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="hora_espera" class="control-label">Tiempo de gracia:</label>
                    	<input type="text" data-inputmask="'mask': 's'" class="form-control" id="hora_espera" name="hora_espera" placeholder="Tiempo de gracia">
                        <div id="mensaje_hora_espera" class="errores"></div>
                 	</div>
             	</div>	
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarHorario()">GUARDAR</button>
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
  			<h3 class="box-title">Horarios</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_horarios(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="estado_empleados" id="estado_horarios"  class="form-control" onchange="load_horarios(1)">
                  		<?php  foreach($resultEst as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_horarios" ></div>
        	<div id="horarios" ></div>
        </div> 	
  	</div>
  </section> 
 </div>
 
 <!-- Modal Agregar Grupo -->
 
 <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Grupo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="nuevo_grupo" placeholder="Nuevo Grupo">
				    		<div id="mensaje_grupo_horarios" class="errores"></div>
						</div>
						<button type="button"  class="btn btn-default" onclick="AgregarGrupo()"><span class='glyphicon glyphicon-plus'></span></button>
					</div>
					<div class="row">
						<div class="col-sm-6" style="width: 80%">
							<br>
							<select name="oficina_grupos" id="oficina_grupos"  class="form-control" >
                        	<option value="" selected="selected">--Oficina--</option>
    						<?php  foreach($resultOfic as $res) {?>
							<option value="<?php echo $res->id_oficina; ?>"><?php echo $res->nombre_oficina; ?> </option>
			        		<?php } ?>
    						</select>
    					<div id="mensaje_oficina_ahoraios" class="errores"></div>
						</div>
					</div>		
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

 <!-- Modal Eliminar Grupo -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalElim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Eliminar Grupo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
			    	<div class="col-sm-6" style="width: 80%">
				    	<select name="eliminar_grupo_empleados" id="eliminar_grupo_empleados"  class="form-control" >
                        	<option value="" selected="selected">--Seleccione--</option>
    						<?php  foreach($resultSet as $res) {?>
    						<option value="<?php echo $res->id_grupo_empleados; ?>"><?php echo $res->nombre_grupo_empleados; echo " - "; 
    						echo $res->nombre_oficina?> </option>
    						<?php } ?>
    					</select>
				    	<div id="mensaje_eliminar_grupo_horarios" class="errores"></div>
					</div>
					<button type="button"  class="btn btn-default" onclick="EliminarGrupo()"><span class='glyphicon glyphicon-trash'></span></button>
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
    <script src="view/Administracion/js/Horarios.js?0.13"></script>
	
	
  </body>
</html> 