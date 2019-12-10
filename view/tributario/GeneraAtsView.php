   <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci - Genera ATS</title>
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
          <h3 class="box-title">Genera ATS</h3>          
        </div>
        
           
         <div class="box-body">
         
         <form id="form_movimientos_contable" action="<?php echo $helper->url("TributarioGeneraAts","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
          
          <div class="row">
          
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="id_modulos" class="control-label">Modulo :</label>
                    <select name="id_modulos" id="id_modulos"   class="form-control" >
                        <option value="0" selected="selected">--Seleccione--</option>
                        <?php if(isset($rsModulos)){
                            foreach ($rsModulos as $res){?>
                        <option value="<?php echo $res->id_modulos;?>" ><?php echo $res->nombre_modulos;?></option>    
                         <?php }}?>   						       
					 </select> 
	         	</div>
	         </div>
	         
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="id_tipo_procesos" class="control-label">Proceso:</label>
                    <select name="id_tipo_procesos" id="id_tipo_procesos"   class="form-control" >
                        <option value="0" selected="selected">--Seleccione--</option>                       					       
					 </select> 
	         	</div>
	         </div>
	         
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="anio_procesos" class="control-label">AÑO :</label>
	         		<input type="number" id="anio_procesos" name="anio_procesos" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>" class="form-control">
                    </div>
	         </div>
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="mes_procesos" class="control-label">MES :</label>
                    <select name="mes_procesos" id="mes_procesos"   class="form-control" >                    	
                      	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                      	<?php if( $i == date('n')){ ?>
                      	<option value="<?php echo $i;?>" selected ><?php echo $meses[$i-1]; ?></option>
                      	<?php }else{?>
                      	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>
                      	<?php }}?>
					 </select> 
	         	</div>
	         </div>
	      </div>
	      
	      <div class="row">
	      	<div class="col-md-offset-4 col-lg-offset-4 col-md-2 col-lg-2 col-xs-12">
	      		<div class="form-group">
	      			<button type="button" id="btnDetalles" name="btnDetalles" class="btn btn-block btn-default" ><i class="fa fa-desktop" aria-hidden="true"></i> Ver Detalle</button>   		
	      		</div>
	      	</div>
	      	<div class="col-md-2 col-lg-2 col-xs-12">
	      		<div class="form-group">
	      			<button type="button" id="btngenera" name="btngenera" class="btn btn-block btn-success" > <i class="fa fa-check" aria-hidden="true"></i> GENERAR XML</button>    		
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
          <h3 class="box-title">Detalles</h3>        
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
  <script src="view/tributario/FuncionesJS/generaAts.js?0.02"></script>
  
	
 </body>
</html>