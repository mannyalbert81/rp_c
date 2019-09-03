    <!DOCTYPE HTML>
	<html lang="es">
    <head>
    
    <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
    <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
  
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Contabilidad</a></li>
        <li class="active">Activos Fijos</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Ficha Activos Fijos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        <form id="frm_ficha" action="<?php echo $helper->url("ActivosFijos","InsertaFicha"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
				<br>			    
							    
		    	 <div class="row">
        		 <input type="hidden" id="id_ficha_mantenimiento" value="id_ficha_mantenimiento">
            
                <div class = 'col-xs-12 col-md-3 col-lg-3' style="display: none">
				        <div class='form-group'>
				        <label for='id_activos_fijos' class='control-label'>Activo Fijo</label><br>
				        <input type='text' class='form-control' id='id_activos_fijos' name='id_activos_fijos'value="<?php echo $resultEdit[0]->id_activos_fijos; ?>" readonly>
				        </div>
				        </div> 	
        		
        		<div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="year_periodo" class="control-label">Fecha :</label>
	         		<input type="date" id="fecha_inicio_ficha_mantenimiento" name="fecha_inicio_ficha_mantenimiento"  value="" class="form-control">
                       <div id="mensaje_fecha_inicio_ficha_mantenimiento" class="errores"></div>
                    
                    </div>
	         	</div>
	         	
	         	<div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="danio_ficha_mantenimiento" class="control-label">Daño :</label>
	         		<input type="text" id="danio_ficha_mantenimiento" name="danio_ficha_mantenimiento"  value="" class="form-control">
                         <div id="mensaje_danio_ficha_mantenimiento" class="errores"></div>
                    
                    </div>
	         	</div>
            	
            	<div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="partes_reemplazado_ficha_mantenimiento" class="control-label">Partes :</label>
	         		<input type="text" id="partes_reemplazado_ficha_mantenimiento" name="partes_reemplazado_ficha_mantenimiento"  value="" class="form-control">
                       <div id="mensaje_partes_reemplazado_ficha_mantenimiento" class="errores"></div>
                    
                    </div>
	         	</div>
	         	
	         	<div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="responsable_ficha_mantenimiento" class="control-label">Responsable :</label>
	         		<input type="text" id="responsable_ficha_mantenimiento" name="responsable_ficha_mantenimiento"  value="" class="form-control">
     			 <div id="mensaje_responsable_ficha_mantenimiento" class="errores"></div>
                                     
                    </div>
	         	</div>
	         	
	         	<div class="col-md-8 col-lg-8 col-xs-12">
	         	<div class="form-group">
	         		<label for="descripcion_ficha_mantenimiento" class="control-label">Descripcion :</label>
	         		<input type="text" id="descripcion_ficha_mantenimiento" name="descripcion_ficha_mantenimiento"  value="" class="form-control">
                         <div id="mensaje_descripcion_ficha_mantenimiento" class="errores"></div>
                    
                    </div>
	         	</div>
	      
  	   	</div>	
						<BR>	          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    <div class="form-group">
    	        <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
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
      			<h3 class="box-title">Listado de Fichas</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaFichas(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="ficha_registrados" ></div>
            </div> 	
      	</div>
      </section> 
    
  
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>

    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
  <script src="view/Contable/FuncionesJS/FichaActivos.js?0.6"></script> 

  </body>
</html>   

 