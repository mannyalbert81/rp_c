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
          <h3 class="box-title">Balance Comprobacion</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
           
         <div class="box-body">
         
         <form id="form_balance_comprobacion" action="<?php echo $helper->url("BalanceComprobacion","generarbalance"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
          
          <div class="row">
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="anio_balance" class="control-label">AÑO :</label>
                    <select name="anio_balance" id="anio_balance"   class="form-control" >
                        <option value="0" selected="selected">--Seleccione--</option>	
    					<!-- VALIDAR PA MOSTRAR ANIO DEL SERVIDOR -->		       
					 </select> 
                     <div id="mensaje_anio_balance" class="errores"></div>
	         	</div>
	         </div>
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="mes_balance" class="control-label">MES :</label>
                    <select name="mes_balance" id="mes_balance"   class="form-control" >
                    	<option value="0" selected="selected">--Seleccione--</option>
						<option value="1" >ENERO</option>
    					<option value="2" >FEBRERO</option>
    					<option value="3" >MARZO</option>
    					<option value="4" >ABRIL</option>
    					<option value="5" >MAYO</option>						
    					<option value="6" >JUNIO</option>
    					<option value="7" >JULIO</option>
    					<option value="8" >AGOSTO</option>
    					<option value="9" >SEPTIEMBRE</option>
    					<option value="10" >OCTUBRE</option>
    					<option value="11" >NOVIEMBRE</option>
    					<option value="12" >DICIEMBRE</option>	
					 </select> 
                     <div id="mensaje_mes_balance" class="errores"></div>
	         	</div>
	         </div>
	         
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="estado_balance" class="control-label">Estado :</label>
                    <input type="text" name="estado_balance" id="estado_balance"  class="form-control"  placeholder="Estado Balance" readonly>                        
                     <div id="mensaje_anio_balance" class="errores"></div>
	         	</div>
	         </div>
	         
	      </div>
	      
	      <div class="row">
	      	<div class="col-md-offset-5 col-lg-offset-5 col-md-2 col-lg-2 col-xs-12">
	      		<div class="form-group">
	      			<input type="submit" value="Generar" class="form-control btn btn-default"/>	      		
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
          <h3 class="box-title">Balance </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>        
         
	    <div class="box-body">
	    	<table id="tabla_balance_comprobacion">
	    	
	    	</table>
	    </div>
        
       </div>
    </section>
  </div>
 
 <!-- para modales -->
 
 
 
 	<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar Cuentas</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="q" placeholder="Buscar Plan de Cuentas" onkeyup="load_comprobantes(1)">
						</div>
						<button type="button" class="btn btn-default" onclick="load_comprobantes(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
					  </div>
					</form>
					<div id="loader" ></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->
				  </div>
				  <br>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					
				  </div>
				</div>
			  </div>
			</div>
	
 
 
 
 
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
     
   
    <?php include("view/modulos/links_js.php"); ?>
   	 
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="view/Contable/FuncionesJS/bcomprobacion.js?1.3"></script>   
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
	
 </body>
</html>