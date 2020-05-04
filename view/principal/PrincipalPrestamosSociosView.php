<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="stylesheet" href="view/bootstrap/plugins/bootstrap_fileinput_v5.0.8-4/css/fileinput.min.css">
    <link rel="stylesheet" href="view/estilos/principal/imagenHover.css">
    
    
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
        
       /*estilo para una tabla predefinida tbldatosParticipe*/
       #tbldatosParticipe td{
            padding: 1px 2px;
            
       }
       #tbldatosParticipe .form-control{
            padding: 3px 12px;
            height: 25px;
            
       } 
       
       /*estilo para una tabla predefinida tbldatosRegistro*/
       #tbldatosRegistro td{
            padding: 1px 2px;
            
       }
       #tbldatosRegistro .form-control{
            padding: 3px 12px;
            height: 25px;
            
       }
       
       /*estilo para una tabla predefinida tbldatosAportes*/
       #tbldatosAportes td{
            padding: 1px 2px;
            
       }
       #tbldatosAportes .form-control{
            padding: 3px 12px;
            height: 25px;
            
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
       
        
        /** para cambiar color en borde superior de nav actives **/
        .nav-tabs-custom > .nav-tabs > li.active {
            border-top-color: #f39c12;
        }
        
        /** para cambiar dimension de btn de file upload **/
        #tbldatosRegistro .btn {
            padding: 2px 12px;
        }
               	  
 	</style>
   
  			        
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    
  <div class=" no-padding">
  
  <!-- DATOS DEL CONTROLADOR -->
     <?php
 
     ?>
  <!-- FIN DATOS CONTROLADOR -->
  
  <!-- HIDDENs vista -->
    <input type="hidden" value="0" id="hdnid_participes">
  <!-- end HIDDENs vista -->
  
  <!-- para el loader de procesos -->
	<div id="divLoaderPage" ></div>
  <!-- termina loader --> 
  
  	<section class="content">
  		<div class="box box-primary">
    		<div class="box-header">
    		  <h3 class="box-title">Datos Prestamo</h3>
    		</div>
    	
		</div>
  	</section>
  
    
    
  </div>
  

    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<!-- date-range-picker -->
    <script src="view/bootstrap/bower_components/moment/min/moment.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- FILE UPLOAD -->
    <script src="view/bootstrap/plugins/bootstrap_fileinput_v5.0.8-4/js/fileinput.min.js?01"></script>
    <!-- js personales -->
    <script type="text/javascript" src="view/principal/js/vtnRegistroAportes.js?0.08"></script>
    
    

  </body>
</html>   

