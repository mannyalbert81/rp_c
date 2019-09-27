    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
       
   
 
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
        <li class="active">Procesos Mayorizacion</li>
      </ol>
    </section>

   <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">CUENTA CONTABLE ANTICIPOS</h3>          
        </div>
        
           
         <div class="box-body">
         
         <form id="form_empleados_cuentas" action="<?php echo $helper->url("ProcesosMayorizacion","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
          
          <div class="row">  
          
              <div class="col-xs-6 col-md-3 col-lg-3 ">
        		<div class="form-group">
        			<input type="hidden" id="id_empleados" name="id_empleados"  value="" class="form-control">        		
            		<label for="ddlempleados" class="control-label">Seleccione Empleado:</label>
            		<select id="ddlempleados" class="form-control" onchange="BuscaEmpleado(this)">
            			<option value="0">--SELECCIONE--</option>
            		</select>        		
             	</div>
              </div>
          
	      </div>
	      
	      <div class="row">
	      	<h4 class="col-md-12 text-center">Datos Empleado</h4>
	      	<div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_empleados" class="control-label">Cedula:</label>
	         		<input type="text" id="cedula_empleado" name="cedula_empleado"  value="" class="form-control" readonly>
	         		<input type="hidden" id="id_empleados" name="id_empleados"  value="" class="form-control">
                    </div>
	         </div> 
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_empleados" class="control-label">Nombre:</label>
	         		<input type="text" id="nombres_empleado" name="nombres_empleado"  value="" class="form-control" readonly>
                    </div>
	         </div> 	         
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_empleados" class="control-label">Cargo:</label>
	         		<input type="text" id="cargo_empleado" name="cargo_empleado"  value="" class="form-control" readonly>
                    </div>
	         </div>   
	      	
	      </div>
	      
	      <div class="row">
	      	<h4 class="col-md-12 text-center">Datos Cuenta Contable</h4>
	      	<div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_empleados" class="control-label">Cuenta Base:</label>
	         		<input type="text" id="cuenta_base" name="cuenta_base"  value="1.4.03.10" class="form-control" readonly>
                    </div>
	         </div> 
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="nombre_empleados" class="control-label">Cuenta Anticipo Empleado:</label>
	         		<input type="text" id="cuenta_contable" name="cuenta_contable"  value="" class="form-control" readonly>
                    </div>
	         </div> 
	      </div>	      
	      
	      <div class="row">	      	
	      	<div class="pull-right ">
	      		<div class="form-group">
  			    	<button type="button" id="btngenera" name="btngenera" class="btn btn-block btn-success" onclick="generarCuentaContable()"> <i class="fa fa-check" aria-hidden="true"></i> GENERAR CONTABLE</button>
	      	    </div>	 
	      	</div>
	      </div>
            
		 </form>
	 </div>
	      
	   
        
       </div>
    </section>
    
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"></h3>        
        </div>
	    <div class="box-body">
        	<div id="div_detalle_procesos" ></div>
	    </div>
	   </div>
    </section>
        
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
  <?php include("view/modulos/links_js.php"); ?>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
  <script src="view/Contable/FuncionesJS/AnticipoCuentas.js?0.05"></script>
  
	
 </body>
</html>