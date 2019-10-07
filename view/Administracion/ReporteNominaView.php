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
        <li class="active">Reporte Nomina</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Editar Nomina</h3>
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
                		<label for="dpto_empleados" class="control-label">Oficina:</label>
                    	<input type="text" class="form-control" id="oficina_empleados" name="oficina_empleados" placeholder="Oficina" readonly>
                        <div id="mensaje_dpto_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
         	<h3 class="box-title">Ingresos</h3>
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Salario:</label>
                    	<input type="text" class="form-control" id="salario_empleados" name="salario_empleados" placeholder="Salario" readonly>
                        <div id="mensaje_salario_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Horas Extra 50%:</label>
                    	<input type="number" step="0.01"  class="form-control" id="h50_empleados" name="h50_empleados" placeholder="Horas Extra 50%">
                        <div id="mensaje_h50_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Horas Extra 100%:</label>
                           	<input type="number" step="0.01"  class="form-control" id="h100_empleados" name="h100_empleados" placeholder="Horas Extra 100%" >
                        <div id="mensaje_h100_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">Fondos de reserva:</label>
                    	<input type="number" step="0.01"  class="form-control" id="fondos" name="fondos" placeholder="Fondos de reserva" readonly>
                        <div id="mensaje_fondos" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">          		
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">14to Sueldo:</label>
                    	<input type="number" step="0.01"  class="form-control" id="dec_cuarto_sueldo" name="dec_cuarto_sueldo" placeholder="14to Sueldo">
                        <div id="mensaje_14" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">13ro Sueldo:</label>
                    	<input type="number" step="0.01"  class="form-control" id="dec_tercero_sueldo" name="dec_tercero_sueldo" placeholder="13ro Sueldo">
                        <div id="mensaje_13" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<h3 class="box-title">Egresos</h3>
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Anticipo:</label>
                    	<input type="number" step="0.01" class="form-control" id="anticipo_empleados" name="anticipo_empleados" placeholder="Anticipo">
                        <div id="mensaje_anticipo_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Aporte IESS:</label>
                    	<input type="number" step="0.01" class="form-control" id="apt_iess_empleados" name="apt_iess_empleados" placeholder="Aporte IESS">
                        <div id="mensaje_apiess_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">ASOCAP:</label>
                           	<input type="number" step="0.01" class="form-control" id="asocap_empleados" name="asocap_empleados" placeholder="ASOCAP">
                        <div id="mensaje_asocap_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Comisión asuntos sociales:</label>
                           	<input type="text" class="form-control" id="asuntos_empleados" name="asuntos_empleados" placeholder="Comisión asuntos sociales" readonly>
                        <div id="mensaje_sociales_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Prestamo Qirografario IESS:</label>
                    	<input type="number" step="0.01" class="form-control" id="quiro_iess_empleados" name="quiro_iess_empleados" placeholder="Prestamo Qirografario IESS">
                        <div id="mensaje_qiess_empleados" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Prestamo Hipotecario IESS:</label>
                    	<input type="number" step="0.01" class="form-control" id="hipo_iess_empleados" name="hipo_iess_empleados" placeholder="Prestamo Hipotecario IESS">
                        <div id="mensaje_hiess_empleados" class="errores"></div>
                 	</div>
                 	
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cargo_empleados" class="control-label">Descuento Sueldo:</label>
                           	<input type="number" step="0.01" class="form-control" id="dcto_sueldo_empleados" name="dcto_sueldo_empleados" placeholder="Descuento Sueldo">
                        <div id="mensaje_dcto_empleados" class="errores"></div>
                 	</div>
             	</div>
          	</div>
          	<div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="ActualizarRegistros()">GUARDAR</button>
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
  			<h3 class="box-title">Reporte Nomina</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
        		<input type="text" value="" class="form-control" id="search" name="search" onkeyup="ReporteNomina(1)" placeholder="Buscar.."/>
			</div>
			
			<div class="pull-right" style="margin-right:15px;">
        			<select name="periodo_marcaciones" id="periodo_marcaciones"  class="form-control" onchange="ReporteNomina(1)">
                  		<option value="P">Periodo actual</option>
          				<?php  foreach($meses as $key=>$res) {?>
						<option value="<?php echo ($key+1); ?>"><?php echo $res; ?> </option>
			        	<?php } ?>
    				</select>
    				<div id="mensaje_mes_marcaciones" class="errores"></div>
        			
    		</div>
    		<div class="pull-right" style="margin-right:15px;">
        			<select name="anio_marcaciones" id="anio_marcaciones"  class="form-control" onchange="ReporteNomina(1)">
                  		<option value="">Seleccione año</option>
          				<?php  foreach($resultAnios as $res) {?>
						<option value="<?php echo $res->anio; ?>"><?php echo $res->anio; ?> </option>
			        	<?php } ?>
    				</select>
    				<div id="mensaje_anio_marcaciones" class="errores"></div>
        			
    		</div>  
    		
			   
        	<div id="load_reporte" ></div>
        	<div id="reporte" ></div>
        	
       </div>
  	</div>
  </section>
  </div>
  
  <!-- Modal reporte individual -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalReporteIndividual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_renovar_credito" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Reporte Individual</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				 <div id="reporte_individual_empleado"></div>				 
            	
				</div>
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
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>  
    <script src="view/Administracion/js/ReporteNomina.js?0.16"></script>
	
	
  </body>
</html> 