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
        <?php
         $datosCivil    = $rsCivil;
         $datosGen      = $rsGen;
         ?>
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
                              		<td><input type="text" class="form-control" id="txtIdentificacion"></td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>Nombres:</label></td>
                              		<td><input type="text" class="form-control" id="txtNombres"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Apellidos:</label></td>
                              		<td><input type="text" class="form-control" id="txtApellidos"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Cargo:</label></td>
                              		<td><input type="text" class="form-control" id="txtCargo"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Fecha Nacimiento:</label></td>
                              		<td> 
                                  		<div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input type="text" class="form-control pull-right" id="txtFNacimiento">
                                        </div>
                              		 </td>
                          		</tr> 
                          		<tr>
                              		<td><label>Estado Civil:</label></td>
                              		<td>
                              			<select id="ddlEstadoCivil" class="form-control">
                              				<option value="0">Todas</option>
                              				<?php foreach ($datosCivil as $res) { ?>                              				    
                              				<option value="<?php echo $res->id_estado_civil_participes; ?>"><?php echo $res->nombre_estado_civil_participes; ?></option>    
                              				<?php }?>                              				
                              			</select>
                              			
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Genero:</label></td>
                              		<td>
                              			<select id="ddlGenero" class="form-control">
                              				<option value="0">Todas</option>
                              				<?php foreach ($datosGen as $res) { ?>                              				    
                              				<option value="<?php echo $res->id_genero_participes; ?>"><?php echo $res->nombre_genero_participes; ?></option>    
                              				<?php }?> 
                              			</select>                              			
                              		</td>
                          		</tr> 
                          		<tr>
                              		<td><label>Direccion:</label></td>
                              		<td><input type="text" class="form-control" id="txtDireccion"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Telefono:</label></td>
                              		<td><input type="text" class="form-control" id="txtTelefono"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Fecha Ingreso:</label></td>
                              		<td>
                              			<div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>    
                                          <input type="text" class="form-control pull-right" id="txtFIngreso">                                   
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
                                          <input type="text" class="form-control pull-right" id="txtFBaja">
                                        </div>
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Estado:</label></td>
                              		<td>
                              			<select id="id_estado_participes" class="form-control">
                              				<option value="0">Todas</option>
                              			</select>                              			
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Entidad Patronal:</label></td>
                              		<td>
                              			<select id="id_entidad_patronal" class="form-control">
                              				<option value="0">Todas</option>                              				
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
            				<label for="buscar_archivo_pago" class=" control-label" ></label> 
                    		<div class="form-group-sm">           
                    		  <button type="button" id="btn_principal_busqueda" class="btn btn-info">
                              	<i class="glyphicon glyphicon-search"></i> Buscar
                              </button>
                    		</div>        			 
            			</div>
            		</div> 
            		
            	</div>
            	
            	 <div id="pnlResultados" class="row hidden">
            		<div class="col-xs-12 col-md-9 col-lg-9">
                		<div class="panel panel-default">
                          <div class="panel-heading">
                          	<p>
                          		<a href="<?php echo $helper->url("PrincipalBusquedas","index"); ?>">
                          			<i aria-hidden="true" class="fa fa-mail-reply"></i> Volver
                      			</a>
                  			</p>
                  			<span id="spanCantidad"></span></div>
                            
                          <table id="tblResultadosPrincipal" class="table">   
                          	<thead>                          		                       		
                          	</thead>
                          	<tbody>
                          		<tr>
                              		<td><label>Opciones:</label></td>
                              		<td>
                              			<div class="box box-widget widget-user-2">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-yellow">
                                              <div class="widget-user-image">
                                                <img class="img-circle" src="view/images/user.png" alt="User Avatar">
                                              </div>
                                              <!-- /.widget-user-image -->
                                              <h3 class="widget-user-username">Nadia Carmichael</h3>
                                              <h5 class="widget-user-desc">Lead Developer</h5>
                                            </div>
                                            <div class="box-footer no-padding">
                                            	<h5 class="widget-user-desc">Lead Developer <span class="pull-right badge bg-blue">31</span></h5>
                                            	<h5 class="widget-user-desc">Lead Developer</h5>
                                            	<h5 class="widget-user-desc">Lead Developer</h5>                                              
                                            </div>
                                        </div>
                          			</td>                     		
                          		</tr>
                          		
                          	</tbody>
                          	<tfoot>
                          	</tfoot>                         
                          </table>
                          <div id="mod_paginacion_resultados"></div>
                    	  <div class="clearfix"></div>  
                        </div>
            		</div>
            	</div>
            	
                 
           </form>
                      
          </div>
    	</div>
    </section>
    
  </div>
  
  <!-- Para modales -->
 



   <div class="control-sidebar-bg"></div>

    
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<!-- date-range-picker -->
    <script src="view/bootstrap/bower_components/moment/min/moment.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- js personales -->
	<script type="text/javascript" src="view/principal/js/principalBusqueda.js?0.05"></script>

  </body>
</html>   

