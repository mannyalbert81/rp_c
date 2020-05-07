<!DOCTYPE html>
<html lang="en">
  <head>
  
   <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
   <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
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
   <?php include("view/modulos/links_css.php"); ?>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   	
	<link href="view/bootstrap/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" /> 
	
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
            <li class="active">Documentos Hipotecario</li>
          </ol>
        </section>
        
         <section class="content">
      	<div class="box box-primary">
      		<div class="box-header with-border">
      			<h3 class="box-title">Documentos Hipotecario</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="ConsultaReporteCierreCreditos(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaReporteCierreCreditos"></div>  
    	    	<div id="consulta_cierre_creditos_registrados_tbl" ></div>
    	    	<div id="divLoaderPage" ></div>
    
            </div> 	
            
           
      	</div>
      </section> 
   
   
   
   
    <!-- PARA VENTANAS MODALES -->
    
      <div class="modal fade" id="mod_reasignar" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Actualizar Documentos Solicitud</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_reasignar" name="frm_reasignar">
          	
          	  <div class="form-group">
				<label for="mod_cedu" class="col-sm-3 control-label">Escritura:</label>
				<div class="col-sm-8">
				  <input type="hidden" class="form-control" id="mod_id_core_documentos_hipotecario" name="mod_id_core_documentos_hipotecario"  readonly>
				  	   <input accept="pdf" type="file" name="archivo_escritura_core_documentos_hipotecario" id="archivo_escritura_core_documentos_hipotecario" value=""  class="form-control"/>  
				  
				</div>
			  </div>
			  
			  
			 <div class="form-group">
				<label for="mod_cedu" class="col-sm-3 control-label">Certificado:</label>
				<div class="col-sm-8">
				  	   <input accept="pdf" type="file" name="archivo_cretificado_core_documentos_hipotecario" id="archivo_cretificado_core_documentos_hipotecario" value=""  class="form-control"/>  
				  
				</div>
			  </div>
			  
			 
			   <div class="form-group">
				<label for="mod_cedu" class="col-sm-3 control-label">Impuesto:</label>
				<div class="col-sm-8">
				  	   <input accept="pdf" type="file" name="archivo_impuesto_core_documentos_hipotecario" id="archivo_impuesto_core_documentos_hipotecario" value=""  class="form-control"/>  
				  
				</div>
			  </div>
			  
			  
			  
			   <div class="form-group">
				<label for="mod_cedu" class="col-sm-3 control-label">Avaluo:</label>
				<div class="col-sm-8">
				  	   <input accept="pdf" type="file" name="archivo_avaluo_core_documentos_hipotecario" id="archivo_avaluo_core_documentos_hipotecario" value=""  class="form-control"/>  
				  
				</div>
			  </div>
			  
			
			  
			  <div id="msg_frm_reasignar" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" form="frm_reasignar" class="btn btn-primary" id="guardar_datos">Actualizar Documentos</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
   
   
   
   
  		</div>
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
   <?php include("view/modulos/links_js.php"); ?>
 <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
 <script src="view/Credito/js/ReporteCierreCreditos.js?1" ></script>
 
 </body>
</html>