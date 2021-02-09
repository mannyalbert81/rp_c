<!DOCTYPE html>
<html lang="en">
  <head>
   <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
   <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
    
    
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Califiacion Cartera - Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">  
      
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
                <li class="active">Calificacion Cartera</li>
            </ol>
        </section>
        
      
      <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Calificacion Cartera</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            
            <div class="box-body">
			<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#riesgo1" data-toggle="tab">Calificacion Cartera Detalle</a></li>
              <li><a href="#riesgo2" data-toggle="tab">Saldo Cartera</a></li>
              <li><a href="#riesgo3" data-toggle="tab">Numero Operaciones</a></li>
              <li><a href="#riesgo4" data-toggle="tab">Cobertura</a></li>
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
             
            <br>
              <div class="tab-pane active" id="riesgo1">
              
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_personal" name="search_personal" onkeyup="load_personal(1)" placeholder="search.."/>
					</div>
					<div id="load_riesgo1" ></div>	
					<div id="riesgo1_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="riesgo2">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_patronal" name="search_patronal" onkeyup="load_patronal(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_riesgo2" ></div>	
					<div id="riesgo2_registrados"></div>
                
                
              </div>
              
              
               <div class="tab-pane" id="riesgo3">
                
                    <div class="pull-right" style="margin-right:15px;">
					    <input type="text" value="" class="form-control" id="search_cesantes" name="search_cesantes" onkeyup="load_cesantes(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_riesgo3" ></div>	
					<div id="riesgo3_registrados"></div>
                
                
              </div>
              
              
              <div class="tab-pane" id="riesgo4">
                
                	<div class="form-group row pull-right">
                	  
                	  	<label for="fordesde" class="col-sm-1 col-form-label">Desde: </label>
                        <div class="col-sm-3">
                          <input type="date" value="" class="form-control" id="search_fechadesde_cesantes" name="search_fechadesde_cesantes" onkeyup="load_cesantias_patronales(1)" placeholder="search.."/>
                        </div>
                	  
                	    <label for="forhasta" class="col-sm-1 col-form-label">Hasta: </label>
                        <div class="col-sm-3">
                          <input type="date" value="" class="form-control" id="search_fechahasta_cesantes" name="search_fechahasta_cesantes" onkeyup="load_cesantias_patronales(1)" placeholder="search.."/>
                        </div>
                        
                         <div class="col-sm-3">
                          <input type="text" value="" class="form-control" id="search_cesantes" name="search_cesantes" onkeyup="load_cesantias_patronales(1)" placeholder="search.."/>
                        </div>
                      </div>
                      	
					<div id="load_riesgo4" ></div>	
					<div id="riesgo4_registrados"></div>
                
                
              </div>
             
            </div>
            </div>
          </div>
         
            
            </div>
            </div>
            </section>
            
            
            
            
             <!-- PARA VENTANAS MODALES -->
    
      <div class="modal fade" id="mod_personal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cuenta Individual (Generar Retenciones Imp. Personal)</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_personal" name="frm_personal">
          			  
          	  <div class="form-group">
				<label for="mod_cantidad_personal" class="col-sm-3 control-label"># Procesar:</label>
				<div class="col-sm-4">
				 <select class="form-control" id="mod_cantidad_personal" name="mod_cantidad_personal" onchange="cargar_personal_a_procesar()">
					<option value="1">1</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>	
					<option value="1000" selected="selected">1000</option>				
				  </select>
				</div>
			  </div>
			  
			
			  <div id="msg_frm_personal" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="button" form="frm_personal" class="btn btn-primary" id="guardar_datos" onclick="Procesar_Personal()">Procesar</button>
          </div>
        </div>
      </div>
</div>
            
            
            
    <div class="modal fade" id="mod_patronal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cuenta Desembolsar (Generar Retenciones Imp. Personal)</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_patronal" name="frm_patronal">
          			  
          	  <div class="form-group">
				<label for="mod_cantidad_patronal" class="col-sm-3 control-label"># Procesar:</label>
				<div class="col-sm-4">
				 <select class="form-control" id="mod_cantidad_patronal" name="mod_cantidad_patronal" onchange="cargar_patronal_a_procesar()">
					<option value="1">1</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>	
					<option value="1000" selected="selected">1000</option>				
				  </select>
				</div>
			  </div>
			  
			
			  <div id="msg_frm_patronal" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="button" form="frm_patronal" class="btn btn-primary" id="guardar_datos1" onclick="Procesar_Patronal()">Procesar</button>
          </div>
        </div>
      </div>
</div>
     
     
     
     
     
     
        
    <div class="modal fade" id="mod_cesantes" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cesantes (Generar Retenciones Imp. Patronal)</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_cesantes" name="frm_cesantes">
          			  
          	  <div class="form-group">
				<label for="mod_cantidad_cesantes" class="col-sm-3 control-label"># Procesar:</label>
				<div class="col-sm-4">
				 <select class="form-control" id="mod_cantidad_cesantes" name="mod_cantidad_cesantes" onchange="cargar_cesantes_a_procesar()">
					<option value="1">1</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>	
					<option value="1000" selected="selected">1000</option>				
				  </select>
				</div>
			  </div>
			  
			
			  <div id="msg_frm_cesantes" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="button" form="frm_patronal" class="btn btn-primary" id="guardar_datos2" onclick="Procesar_Cesantes()">Procesar</button>
          </div>
        </div>
      </div>
</div>
             
               
    
    
     <div class="modal fade" id="mod_cesantias_patronales" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cesantías (Generar Retenciones Patronal)</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_cesantes" name="frm_cesantes">
          			  
          	  <div class="form-group">
				<label for="mod_cantidad_cesantias_patronales" class="col-sm-3 control-label"># Procesar:</label>
				<div class="col-sm-4">
				 <select class="form-control" id="mod_cantidad_cesantias_patronales" name="mod_cantidad_cesantias_patronales" onchange="cargar_cesantes_a_procesar()">
					<option value="1">1</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>	
					<option value="1000" selected="selected">1000</option>				
				  </select>
				</div>
			  </div>
			  
			
			  <div id="msg_frm_cesantias_patronales" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="button" form="frm_patronal" class="btn btn-primary" id="guardar_datos2" onclick="Procesar_Cesantias_Patronales()">Procesar</button>
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
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="view/riesgos/js/CalifiacionCartera.js?4.7"></script>         	
  </body>
</html>

 