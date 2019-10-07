<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
	    
	<style>
	ul{
        list-style-type:none;
      }
  li{
    list-style-type:none;
    }
    
    

    

    </style>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
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
        <li class="active">Marcaciones</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Registrar/Editar Registros</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_empleado" class="control-label">Cédula:</label>
                    	<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_empleado" name="cedula_empleado" placeholder="Cédula">
                        <div id="mensaje_cedula_empleado" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Empleado:</label>
                    	<input type="text" class="form-control" id="nombres_empleado" name="nombres_empleado" placeholder="Nombres" readonly>
                        <div id="mensaje_nombres_empleado" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="apellido_empleados" class="control-label">Hora:</label>
                    	<input type="text" data-inputmask="'mask': 'h:s:s'" class="form-control" id="hora_marcacion" name="hora_marcacion" placeholder="Hora">
                        <div id="mensaje_hora_marcacion" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Fecha:</label>
                    	<input type="text" data-inputmask="'mask': 'y-m-d'" class="form-control" id="fecha_marcacion" name="fecha_marcacion" placeholder="Fecha">
                        <div id="mensaje_fecha_marcacion" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
          			<label for="tipo_registro" class="control-label">Tipo registro:</label>
          			<select name="tipo_registro" id="tipo_registro"  class="form-control">
                  		<option value="">--Seleccione--</option>
						<option value="Entrada">Entrada</option>
						<option value="Salida Almuerzo">Salida Almuerzo</option>
						<option value="Entrada Almuerzo">Entrada Almuerzo</option>
						<option value="Salida">Salida</option>
    				</select>
    				<div id="mensaje_tipo_registro" class="errores"></div>
          		</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarRegistro()">GUARDAR</button>
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
  			<h3 class="box-title">Marcaciones</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
         	<div class = "row">
         		<div class="col-xs-6 col-md-3 col-lg-3 " style="margin-left:15px;">
        			<div class="input-group">
        				<input type="text" id= "nombre_archivo" class="form-control" placeholder = "Archivo" readonly>
        				<span class="input-group-btn">
                    		<label class="btn btn-primary">
    						<i class="glyphicon glyphicon-plus"></i>
    						<input type="file" id="archivo_registro" style="display: none;">
							</label>
							<button type="button" class="btn btn-success" id="subir_archivo" name="subir_archivo" onclick="SubirArchivo()">
							<i class="glyphicon glyphicon-upload"></i>
							</button>
						</span>
					</div>
        		</div>
        		<div class="col-xs-6 col-md-3 col-lg-3 " style="margin-left:15px;" >
                	<div id="load_boton_notificaciones" ></div>	
        		</div>
        		<div id="mensaje_archivo" class="errores"></div>
			</div>
			<br>
			<div id="cabecera_marcaciones" ></div>
			<div class="pull-right" style="margin-right:15px;">
        		<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_marcaciones(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="periodo_marcaciones" id="periodo_marcaciones"  class="form-control" onchange="load_marcaciones(1)">
                  		<option value="1">Todos los periodos</option>
						<option value="2">Periodo actual</option>
    				</select>
    		</div>
    		<div class="pull-right" style="margin-right:15px;">
        			<select name="estado_registro" id="estado_registro"  class="form-control" onchange="load_marcaciones(1)">
                  		<option value="1">Todos los registros</option>
						<option value="2">Registros en blanco</option>
						<option value="3">Registros completos</option>
    				</select>
    		</div>
    		<div class="pull-right col-xs-4 col-md-2 col-lg-2 " style="margin-left:15px;" >
    			<div class="input-group">
    				<span class="input-group-btn">
    			    	<button type="button" class="btn btn-danger" id="borrar_campo" name="borrar_campo" onclick="LimpiarCedula()">
						<i class="glyphicon glyphicon-erase"></i>
						</button>
					</span>
            		<input type="text" data-inputmask="'mask': '9999999999'" onfocusout="load_marcaciones(1)" class="form-control" id="cedula_empleado1" name="cedula_empleado1" placeholder="Cédula">	
    			</div>
    		</div>
    		
        	<div id="load_marcaciones" ></div>
        	<div id="marcaciones" ></div>
       </div>
  	</div>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Detalles</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">        
        	<div id="load_reporte" ></div>
        	<div id="reporte" ></div>
        	
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
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>  
    <script src="view/Administracion/js/Marcaciones.js?0.34"></script>
	
	
  </body>
</html> 