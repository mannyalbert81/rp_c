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
        <li class="active">Productos</li>
      </ol>
    </section>

   <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Movimientos Contables</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
           
         <div class="box-body">
         
         <form id="form_movimientos_contable" action="<?php echo $helper->url("MovimientosContable","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
          
          <div class="row">
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="anio_movimientos" class="control-label">AÑO :</label>
                    <select name="anio_movimientos" id="anio_movimientos"   class="form-control" >
                        <option value="0" selected="selected">--Seleccione--</option>	
    					<option value="<?php echo date('Y'); ?>" ><?php echo date('Y'); ?></option>		       
					 </select> 
                     <div id="mensaje_anio_balance" class="errores"></div>
	         	</div>
	         </div>
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="mes_movimientos" class="control-label">MES :</label>
                    <select name="mes_movimientos" id="mes_movimientos"   class="form-control" >
                    	<option value="0" selected="selected">--Seleccione--</option>
						<option value="01" >ENERO</option>
    					<option value="02" >FEBRERO</option>
    					<option value="03" >MARZO</option>
    					<option value="04" >ABRIL</option>
    					<option value="05" >MAYO</option>						
    					<option value="06" >JUNIO</option>
    					<option value="07" >JULIO</option>
    					<option value="08" >AGOSTO</option>
    					<option value="09" >SEPTIEMBRE</option>
    					<option value="10" >OCTUBRE</option>
    					<option value="11" >NOVIEMBRE</option>
    					<option value="12" >DICIEMBRE</option>	
					 </select> 
                     <div id="mensaje_mes_balance" class="errores"></div>
	         	</div>
	         </div>
	      </div>
	      
	      <div class="row">
	      	<div class="col-md-offset-5 col-lg-offset-5 col-md-2 col-lg-2 col-xs-12">
	      		<div class="form-group">
	      			<button type="button" id="buscarmovimientos" name="buscarmovimientos" class="btn btn-success" >GENERAR</button>    		
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
          <h3 class="box-title">Movimientos Contable Mes </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>        
         
	    <div class="box-body">
	    	<div id="load_cuentas" ></div>
        	<div id="div_movimientos" ></div>
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
  <script src="view/Contable/FuncionesJS/movimientoscontables.js?0.02"></script>
  
	
 </body>
</html>