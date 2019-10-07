<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>

    .reportes {
        width: 45%;
        }
         .observacion {
        width: 75%;
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
    
    <body id="cuerpo" class="hold-transition skin-blue fixed sidebar-mini">
    
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
        <li class="active">Revisión Créditos</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Reportes de Crédito</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              </div>
         </div>
         <div class="box-body">
         <div class="pull-right" style="margin-right:15px;">
                    	<input type="date"  class="form-control" id="fecha_reportes" name="fecha_concesion" placeholder="Fecha" onchange="load_reportes(1);">
                        <div id="mensaje_fecha" class="errores"></div>
    		</div>
          	<div class="row">
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		
                 	</div>
             	</div>
            </div>
            <div id="load_reportes" ></div>
        	<div id="reportes_registrados" ></div>
          </div>
  	</div>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 id="titulo_box" class="box-title">Listado de Créditos</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div id="search_creditos" class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_creditos(1)" placeholder="Buscar.."/>
			</div>
			<div id="input_fecha"class="pull-right" style="margin-right:15px;">
                    	<input type="date"  class="form-control" id="fecha_concesion" name="fecha_concesion" placeholder="Fecha" onchange="load_creditos(1)">
                        <div id="mensaje_fecha" class="errores"></div>
    		</div>
        	<div id="load_creditos" ></div>
        	<div id="creditos_registrados" ></div>
        </div> 	
  	</div>
  </section> 
 </div>
 
 <!-- Modal Inserta Reporte -->
 
 <div class="modal fade bs-example" id="myModalInsertar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_insertar" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Seleccionar Reporte</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
          	 		<div class="row">
          	 		<div class="form-group" align="center">
          	 		<div id="select_reportes"></div>
                 </div>
              </div>
              <div class="row">
          	 		<div class="form-group" align="center">
          	 		<button type="button" id="registrar_credito" name="registrar_credito" class="btn btn-primary" onclick="SubirReporte()"> AGREGAR</button>
                 </div>
              </div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>



<!-- Modal Ver Reporte -->
 
 <div class="modal fade bs-example" id="myModalVer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_ver" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Contenidos del reporte</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
          	 		<div class="row">
          	 		<div class="form-group" align="center">
          	 		<div id="datos_reporte"></div>
                 </div>
              </div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Ver Comprobantes -->
 
 <div class="modal fade bs-example" id="myModalComprobantes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_comprobantes" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Comprobantes</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
          	 		<div class="row">
          	 		<div class="form-group" align="center">
          	 		<div id="datos_comprobante"></div>
                 </div>
              </div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Observacion Creditos -->
 
 <div class="modal fade bs-example" id="myModalObservacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_observacion" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Contenidos del reporte</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
          	 		<div class="row">
          	 		<div class="form-group" align="center">
          	 		<input type="text" class="observacion" maxlength="200" class="form-control" id="observacion_credito" name="observacion_credito" placeholder="Observación Crédito">
                 	<div id="mensaje_observacion_credito" class="errores"></div>
                 	<div class="row">
          	 		<div class="form-group" align="center">
          	 		<button type="button" id="registrar_observacion" name="registrar_observacion" class="btn btn-primary" onclick="ContinuaNegar()"> CONTINUAR</button>
                 	</div>
              		</div>
                 </div>
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
    <script src="view/Credito/js/RevisionCreditos.js?0.19"></script>
	
	
  </body>
</html> 