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
        <li class="active">Departamentos</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Registrar/Editar Departamentos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             		<label for="dpto_empleados" class="control-label">Departamento:</label>
            		<div class="input-group">
                    	<select name="dpto_empleados" id="dpto_empleados"  class="form-control" >
                        	<option value="" selected="selected">--Seleccione--</option>
                        	<?php  foreach($resultdpto as $res) {?>
									  <option value="<?php echo $res->id_departamento; ?>"><?php echo $res->nombre_departamento; ?> </option>
			        				  <?php } ?>
                        	
    					</select>
    					<div id="mensaje_dpto_empleados" class="errores"></div>
    					<span class="input-group-btn">
    						<button type="button" id="nuevo_grupo" name="nuevo_grupo" class="btn btn-primary" data-toggle="modal" data-target="#myModal" ><i class='glyphicon glyphicon-plus'></i></button> 
                        </span>	
                        <span class="input-group-btn">
    						<button type="button" id="eliminar_grupo" name="eliminar_grupo" class="btn btn-danger" data-toggle="modal" data-target="#myModalElim" onclick="SelecDpto()" ><i class='glyphicon glyphicon-trash'></i></button> 
                        </span>			
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<br>
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
          			<div class="form-group">
          				<label for="cargo_empleados" class="control-label">Cargo:</label>
          				<input type="text"  class="form-control" id="cargo_empleados" name="cargo_empleados" placeholder="Cargo">
                    	<div id="mensaje_cargo_empleados" class="errores"></div>
          			</div>
          		</div>
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
              		<label for="cargo_empleados" class="control-label">Salario:</label>
              		<input type=number step=0.01 class="form-control" id="salario_empleados" name="salario_empleados" placeholder="Salario">
              		<div id="mensaje_salario_empleados" class="errores"></div>
          		</div>
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Estado:</label>
                    	<select name="estado_cargo" id="estado_cargo"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
    								  <?php  foreach($resultEst as $res) {?>
									  <option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        				  <?php } ?>
    					</select> 
                        <div id="mensaje_estado_cargo" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarCargo()">GUARDAR</button>
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
  			<h3 class="box-title">Listado de Departamentos</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_departamentos(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="turno_empleados" id="estado_dpto"  class="form-control" onchange="load_departamentos(1)">
                  		<?php  foreach($resultEst1 as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_dptos" ></div>
        	<div id="dptos_registrados" ></div>
        </div> 	
  	</div>
  </section> 
 </div>
 
 <!-- Modal Agregar Dpto -->
 
 <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Departamento</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="nuevo_dpto" placeholder="Nuevo Departamento">
				    		<div id="mensaje_agregar_dpto" class="errores"></div>
						</div>
						<button type="button"  class="btn btn-default" onclick="AgregarDpto()"><span class='glyphicon glyphicon-plus'></span></button>
					</div>		
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

 <!-- Modal Eliminar Dpto -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalElim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Eliminar Departamento</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
			    	<div class="col-sm-6" style="width: 80%">
				    	<select name="eliminar_dpto_empleados" id="eliminar_dpto_empleados"  class="form-control" >
                        	<option value="" selected="selected">--Seleccione--</option>
    						<?php  foreach($resultdpto as $res) {?>
									  <option value="<?php echo $res->id_departamento; ?>"><?php echo $res->nombre_departamento; ?> </option>
			        				  <?php } ?>
    					</select>
				    	<div id="mensaje_eliminar_dpto_horarios" class="errores"></div>
					</div>
					<button type="button"  class="btn btn-default" onclick="EliminarDpto()"><span class='glyphicon glyphicon-trash'></span></button>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Editar Dpto -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Departamento</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="antiguo_dpto" readonly>
				    		<br>
						</div>
					</div>	
					<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="nuevo_dpto" placeholder="Nuevo nombre dpto">
				    		<div id="mensaje_agregar_dpto" class="errores"></div>
						</div>
						<button type="button"  class="btn btn-default" onclick="EditarDpto()"><span class='glyphicon glyphicon-ok'></span></button>
					</div>		
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
    <script src="view/Administracion/js/Departamentos.js?0.2"></script>
	
	
  </body>
</html> 