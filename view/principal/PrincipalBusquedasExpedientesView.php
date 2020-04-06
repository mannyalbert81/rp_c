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
       	  
 	</style>
   <?php include("view/modulos/links_css.php"); ?>
  			        
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
        <li class="active">Arhivo Pago</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Busqueda General</h3>
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
  		
  		<div id="divLoaderPage" ></div> 

			<form id="frm_principal_busqueda" action="<?php echo $helper->url("PrincipalBusquedas","index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             	             	
				<div id="pnlheaderbuttons">
					<div class="panel panel-default">  
						<div class="btn-group" role="group" aria-label="grupo 1">
                        	<button type="button" id="" class="btn btn-secondary">Consulta General</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="grupo 2">
                        	<button type="button" class="btn btn-secondary">Socios</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="grupo 3">
                        	<button type="button" class="btn btn-secondary">Expedientes/Solicitudes</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="grupo 4">
                        	<button type="button" class="btn btn-secondary">Inversiones</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="grupo 5">
                        	<button type="button" class="btn btn-secondary">Prestamos</button>
                        </div>
                        <div class="btn-group" role="group" aria-label="grupo 6">
                        	<button type="button" class="btn btn-secondary">Supv Desaf/Cesantes</button>
                        </div>  
                           
                	</div>                	
				</div>             	             	
             	             	
                 <div id="pnlBusqueda" class="row">
            		<div class="col-xs-12 col-md-6 col-lg-6">
                		<div class="panel panel-default">
                          <div class="panel-heading">Ingrese Datos</div>
                            
                          <table id="tblBusquedaPrincipal" class="table">   
                          	<thead>                          		                       		
                          	</thead>
                          	<tbody>
                          		<tr>
                              		<td><label>Identificacion:</label></td>
                              		<td><input type="text" class="form-control" id="txtcedulaexpedientes"></td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>Nombres:</label></td>
                              		<td><input type="text" class="form-control" id="txtNombresexpedientes"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Apellidos:</label></td>
                              		<td><input type="text" class="form-control" id="txtApellidosexpedientes"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Cargo:</label></td>
                              		<td><input type="text" class="form-control" id="txtCargoexpedientes"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>N° Solicitud:</label></td>
                              		<td><input type="text" class="form-control" id="txtNumeroSolicitudexpedientes"></td>
                          		</tr>
                          		 
                          		<tr>
                              		<td><label>Fecha Registro:</label></td>
                              		<td>
                              			<div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>    
                                          <input type="date" class="form-control pull-right" id="txtFRegistroexpedientes">                                   
                                        </div>
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Fecha Baja:</label></td>
                              		<td>
                              			<div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input type="date" class="form-control pull-right" id="txtFBajaexpedientes">
                                        </div>
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Estado:</label></td>
                              		<td>
                              		  <select  class="form-control" id="id_estado_participes" name="id_estado_participes">
                                      	<option value="0">--Seleccione--</option>
                                      </select>                               			
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Tipo Liquidación:</label></td>
                              		<td>
                              			<select  class="form-control" id="id_tipo_liquidación" name="id_tipo_liquidación">
                                      	<option value="0">--Seleccione--</option>
                                      </select>                              			
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Entidad Patronal:</label></td>
                              		<td>
                              			<select  class="form-control" id="id_entidad_patronal" name="id_entidad_patronal">
                                      	<option value="0">--Seleccione--</option>
                                      </select>                             			
                              		</td>
                          		</tr>  
                          	</tbody>
                          	<tfoot>
                          	</tfoot>                         
                          </table>
                        </div>
            		</div>
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="btn_principal_busqueda" class=" control-label" ></label> 
                    		<div class="form-group-sm">           
                    		  <button type="button" class="btn btn-primary" id="load_buscar_participe" name="load_buscar_participe">
        						<i class="glyphicon glyphicon-search"></i>
        						</button>
                    		</div>        			 
            			</div>
            		</div> 
            		
            		
            	</div>
            	
         	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="participe_encontrado" ></div>
           			<div id="load1_buscar_participe" ></div>	
					<div id="participes_registrados"></div>	
           		</div>

           	</div>
            	
            	
                 
           </form>
                      
          </div>
    	</div>
    </section>
    
  </div>
  
 

    

 
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
	<script type="text/javascript" src="view/principal/js/principalBusquedaExpedientes.js?1.18"></script>

  </body>
</html>   

