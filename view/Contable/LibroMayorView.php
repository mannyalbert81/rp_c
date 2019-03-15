<!DOCTYPE html>
<html lang="en">
  <head>
   <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
      <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
    
    
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
      
      
   <?php include("view/modulos/links_css.php"); ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="view/bootstrap/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    
   
  </head>

  <body class="hold-transition skin-blue fixed sidebar-mini">

 <?php  $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $DateString = (string)$fecha;
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
                <li class="active">Usuarios</li>
            </ol>
        </section>
        
        <!-- comienza diseño controles usuario -->
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Mayor Contable</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
                <form id="frm_libro_mayor" action="<?php echo $helper->url("LibroMayor","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-xs-12">
                
                	<div class="row">
                     	<div class="col-xs-6 col-md-3 col-lg-3 ">
                        	<div class="form-group">
                            	<label for="anio_l_mayor" class="control-label">Año:</label>                            	
                                <input type="number" max="<?php echo date('Y')?>" min="2000" class="form-control" id="anio_l_mayor" name="anio_l_mayor" value=""  >                                
                                <div id="mensaje_anio_lmayor" class="errores"></div>
                             </div>
                         </div>
                         
                         <div class="col-xs-6 col-md-3 col-lg-3 ">
                        	<div class="form-group">
                            	<label for="mes_l_mayor" class="control-label">Mes:</label>
                            	<select id="mes_l_mayor" name="mes_l_mayor" class="form-control">
                            		<option value="0">--SELECCIONE--</option>
                            		<option value="1">ENERO</option>
                            		<option value="2">FEBRERO</option>
                            		<option value="3">MARZO</option>
                            		<option value="4">ABRIL</option>
                            		<option value="5">MAYO</option>
                            		<option value="6">JUNIO</option>
                            		<option value="7">JULIO</option>
                            		<option value="8">AGOSTO</option>
                            		<option value="9">SEPTIEMBRE</option>
                            		<option value="10">OCTUBRE</option>
                            		<option value="11">NOVIEMBRE</option>
                            		<option value="12">DICIEMBRE</option>
                            	</select>                                                               
                                <div id="mensaje_mes_lmayor" class="errores"></div>
                             </div>
                         </div>
                         
                         <div class="col-xs-6 col-md-3 col-lg-3 ">
                        	<div class="form-group">
                            	<label for="codigo_cuenta" class="control-label">Codigo Cuenta:</label>
                                <input type="text" class="form-control" id="codigo_cuenta" name="codigo_cuenta" value=""  >                                
                                <div id="mensaje_codigo_cuenta" class="errores"></div>
                             </div>
                         </div>
                         
                         <div class="col-xs-6 col-md-3 col-lg-3 ">
                        	<div class="form-group">
                            	<label for="nombre_cuenta" class="control-label">Nombre Cuenta:</label>
                                <input type="text" class="form-control" id="nombre_cuenta" name="nombre_cuenta" value=""  >
                                <input type="hidden" class="form-control" id="id_cuenta" name="id_cuenta" value=""  >                                
                                <div id="mensaje_nombre_cuenta" class="errores"></div>
                             </div>
                         </div>                         
                         
                     </div>
                	
                        
                     	<div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GENERAR MAYORES</button>
            	                  <a class="btn btn-danger" href="<?php  echo $helper->url("LibroMayor","index"); ?>">CANCELAR</a>
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
              <h3 class="box-title">Listado Usuarios</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
			<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Usuarios Activos</a></li>
              <li><a href="#inactivos" data-toggle="tab">Usuarios Inactivos</a></li>
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
             
            <br>
              <div class="tab-pane active" id="activos">
              
                
					<div class="pull-right" style="margin-right:15px;">
					
						<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_usuarios(1)" placeholder="search.."/>
					</div>
					<div id="load_registrados" ></div>	
					<div id="users_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="inactivos">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_inactivos" name="search_inactivos" onkeyup="load_usuarios_inactivos(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_inactivos_registrados" ></div>	
					<div id="users_inactivos_registrados"></div>
                
                
              </div>
             
              <button type="submit" id="btExportar" name="exportar" class="btn btn-info">Exportar</button>
            </div>
            </div>
          </div>
         
            
            </div>
            </div>
            </section>
  </div>
 	<?php include("view/modulos/footer.php"); ?>	
   <div class="control-sidebar-bg"></div>
 </div>
   <?php include("view/modulos/links_js.php"); ?> 
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="view/Contable/FUNCIONESJS/lmayor.js?1.5"></script>         	
  </body>
</html>

 