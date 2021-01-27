<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <style type="text/css">
        .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('view/images/ajax-loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
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
    
    
      <section class="content">
      
             <div class="col-md-12">

          <!-- Profile Image -->
          <div class="box box-success">
            <div class="box-body box-profile">
   			<div align="center">  
  			<img  src="view/images/Logo-Capremci-h-200.jpg" width="300" height="60"></img>
  			</div>
              <h3 class="profile-username text-center">JUNTA GENERAL ELECTORAL</h3>
			  <h2 class="profile-username text-center">CERTIFICADO VOTACIÓN</h2>
	  		  <p class="text-muted text-center">ELECCIONES REPRESENTANTES ASAMBLEA 2020-2022</p>
	  		  
	  		  <h3 class="profile-username text-center">CERTIFICADO: <?php echo $rsdatos[0]->consecutivo;?></h3><br><br><br>
	  	      
	  	       <b class="text-muted text-left">Cédula: <a class="pull-right"><?php echo $rsdatos[0]->cedula_participes;?></a></b><br>
	  	       <b class="text-muted text-left">Apellidos y Nombres: <a class="pull-right"><?php echo $rsdatos[0]->apellido_participes;?> <?php echo $rsdatos[0]->nombre_participes;?></a></b><br>
	  	       <b class="text-muted text-left">Entidad Mayor Patronal: <a class="pull-right"><?php echo $rsdatos[0]->nombre_entidad_mayor_patronal;?></a></b><br>
	  	       <b class="text-muted text-left">Entidad Patronal: <a class="pull-right"><?php echo $rsdatos[0]->nombre_entidad_patronal;?></a></b><br>
	  	       <b class="text-muted text-left">Transacción N°: <a> <?php echo $rsdatos[0]->id_padron_electoral_representantes;?></a></b><br>
	  	       <br>
	  	     	<div align="center">  
  			  	<img  src="view/images/presidente_junta.jpg" width="200" height="60">
	  	   		</div>
	  	   		<br>
               <a style = "margin-top: 25px;" href="http://186.4.157.125/webcapremci/" class="btn btn-success btn-block"><b>CERTIFICADO VÁLIDO</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
         
          <!-- /.box -->
        </div>
      
      </section>

  
	
  </body>
</html> 