<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="view/estilos/principal/imagenHover.css">
    <?php include("view/modulos/links_css.php"); ?>
    
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
       /*estilo para una tabla predefinida*/  
       #tblBusquedaPrincipal.table>tbody>tr>td{
            padding: 4px 12px !important;
       }
       .form-control {
            border-radius: 5px !important;
        }
       #tblBusquedaPrincipal .form-control{
            padding: 3px 12px !important;
            height: 25px;
            
       }
       .box-footer .widget-user-desc {
          margin-left: 15px !important;
        }
       
       /* estilo para textos en mostrar detalles */
       .bio-row{
            width: 95%;
            float: left;
            margin-bottom:0px;
            padding: 0 15px;
       }
       
       .bio-row p {
                margin: 0 0 1px;
       }
       
       .bio-row p span {
            font-weight: bold;
            width: 200px;
            display: inline-block;
       } 
       
       /* estilos personalizados para botones */
       .btn-secondary {
          color: #fff;
          background-color: #6c757d;
          border-color: #6c757d;
        }
        
        .btn.btn-secondary:hover {
          color: #fff;
          background-color: #5a6268;
          border-color: #545b62;
        }
        
        .btn-secondary:focus, .btn-secondary.focus {
          box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
        }
        
        .btn-secondary.disabled, .btn-secondary:disabled {
          color: #fff;
          background-color: #6c757d;
          border-color: #6c757d;
        }
        
        .btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active {
          color: #fff;
          background-color: #545b62;
          border-color: #4e555b;
        }
        
        .btn-secondary:not(:disabled):not(.disabled):active:focus, .btn-secondary:not(:disabled):not(.disabled).active:focus,
        .show > .btn-secondary.dropdown-toggle:focus {
          box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
        }
        
        /** para cambiar color en borde superior de nav actives **/
        .nav-tabs-custom > .nav-tabs > li.active {
            border-top-color: #f39c12;
        }
               	  
 	</style>
   
  			        
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    <span id="fechasistema"><?php echo date('Y-m-d');?></span>

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
        <li class="active">Pagos Reporte Credito</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Pagos Reporte Credito</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <!-- DATOS DEL CONTROLADOR -->
       
        <!-- FIN DATOS CONTROLADOR -->
        
        <!-- para el loader de procesos -->
 		<div id="divLoaderPage" ></div>
     	<!-- termina loader --> 
                  
  		<div class="box-body">
  		
  		
  		<div class="row">
  			<div class="col-md-12">
          		<div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    	<!-- AQUI PONER LOS TITULOS DE TABS -->
                      <li><a href="#panel_1" data-toggle="tab">Pagos 1</a></li>
                      <li><a href="#panel_2" data-toggle="tab">Pagos 2</a></li>
                      <li><a href="#panel_3" data-toggle="tab">Pagos 3</a></li>
                      
                    </ul>
                    <div class="tab-content">
                    	<!-- AQUI COMIENZA PARA PONER CONTENIDOS DE PANELES -->
                    	<!-- PANEL INDEX -->
                    	<div class="active tab-pane" id="panel_index">
                    		<?php include 'view/principal/html/panelindex.php'; ?>
                    	</div>
                    	<!-- END PANEL INDEX -->
                    	
                    	<!-- PANEL 1 --Consulta general-- -->
                    	<div class="tab-pane" id="panel_1">
                    		
                    	</div>
                    	<!-- END PANEL 1 --Consulta general-- -->
                    	
                    	<!-- PANEL 2 --Socios-- -->
                    	<div class="tab-pane" id="panel_2">
                    		
                    		
                    		
                    	</div>
                    	<!-- END PANEL 2 --Socios-- -->
                    	
                    	<!-- PANEL 3 --Solicitudes-- -->
                    	<div class="tab-pane" id="panel_3">
                    	
                    	
                    		
                    	</div>
                    	<!-- END PANEL 3 --Solicitudes-- -->
                    	
                    	
                                                     
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- /.nav-tabs-custom -->
                </div>
          	</div>
                      
          </div>
          
    	</div>
    	
    </section>
    
  </div>
  
 
  
  <!-- Para modales -->
 


 
 <?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<!-- date-range-picker -->
    <script src="view/bootstrap/bower_components/moment/min/moment.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- js personales -->
	<script type="text/javascript" src="view/tesoreria/js/pagosReporteCredito.js?0.1"></script>

	
  </body>
</html>   

