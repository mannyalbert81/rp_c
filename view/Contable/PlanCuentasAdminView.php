<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
.modal-lg {
    max-width: 30% !important;
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
        <li class="active">Administración Plan Cuentas</li>
    </ol>
  </section>
  <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Plan Cuentas</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
        	<div id="load_empleados" ></div>
        	<div id="tabla_plan_cuentas" ></div>
        </div> 	
  	</div>
  </section> 
 </div>
 
 <!-- Modal Editar Dpto -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Cuenta</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="antiguo_nombre" readonly>
				    		<br>
						</div>
					</div>	
					<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="nuevo_nombre" placeholder="Nuevo nombre cuenta">
				    		<div id="mensaje_agregar_nombre" class="errores"></div>
						</div>
						<button type="button"  class="btn btn-default" onclick="EditarNombreCuenta()"><span class='glyphicon glyphicon-ok'></span></button>
					</div>		
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Agregar Dpto -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAgregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Cuenta</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" data-inputmask="'mask': '99'" class="form-control" id="nuevo_codigo" placeholder="Codigo cuenta">
				    		<div id="mensaje_agregar_codigo" class="errores"></div>
				    		<br>
						</div>
					</div>	
					<div class="row">
			    		<div class="col-sm-6" style="width: 80%">
				    		<input type="text" class="form-control" id="nuevo_nombre" placeholder="Nombre cuenta">
				    		<div id="mensaje_agregar_nombre" class="errores"></div>
						</div>
						<button type="button"  class="btn btn-default" onclick="AgregarNuevaCuenta()"><span class='glyphicon glyphicon-ok'></span></button>
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
    <script src="view/Contable/FuncionesJS/PlanCuentasAdmin.js?0.8"></script>
	
	
  </body>
</html> 