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
    		  <h3 class="box-title">Registro manual de aportes</h3>
    		</div>
    		<div class="box-body">
  		  		
          		<div class="row">
          			<!-- Este div es para mostrar datos del participe -->
          			<div class="col-sm-6 col-md-6 col-lg-6">
          				<div class="panel panel-default">
                          <div class="panel-heading">
                          	<p>
                          		<a href="#">
                          			<i aria-hidden="true" class="fa fa-mail-reply"></i> Volver
                      			</a>
                  			</p>
                  			
                          <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		<tr>
                              		<td><label>Identificacion:</label></td>
                              		<td><input type="text" class="form-control" id="lblIdentificacion"></td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>Nombres:</label></td>
                              		<td><input type="text" class="form-control" id="lblNombres"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Apellidos:</label></td>
                              		<td><input type="text" class="form-control" id="lblApellidos"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Entidad Patronal:</label></td>
                              		<td>
                              			<select class="form-control" id="id_entidad_patronal">
                              			<option value="">--Seleccione--</option>
                              			</select>
                          			</td>
                          		</tr> 
                          		<tr>
                              		<td><label>Categoria Aporte:</label></td>
                              		<td>
                              			<select class="form-control" id="id_contribucion_categoria">
                              			<option value="">--Seleccione--</option>
                              			</select>
                          			</td>
                          		</tr> 
                          		<tr>
                              		<td><label>A&ntilde;O:</label></td>
                              		<td>
                              			<select id="ddlYear" class="form-control">                              				                            				
                              			</select>                              			
                              		</td>
                          		</tr>
                          		<tr>
                              		<td><label>Mes:</label></td>
                              		<td>
                              			<select id="ddlMes" class="form-control">                              				                            				
                              			</select>
                              		</td>
                          		</tr>
                          	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                          
                          <div id="mod_paginacion_datos_participe"></div>
                    	  <div class="clearfix"></div>  
                        </div>
          				</div>          			
          			</div>
          			
          			<div class="col-sm-6 col-md-6 col-lg-6">
          				<!-- ESTA TABLA SE LLENA CON PROCESO DE JS PARA DATOS DE REGISTO -->  
                          <table id="tbldatosRegistro" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		<tr>
                              		<td><label>Identificacion:</label></td>
                              		<td><input type="text" class="form-control" id="lblidentificacion"></td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>Nombres:</label></td>
                              		<td><input type="text" class="form-control" id="lblnombres"></td>
                          		</tr>
                          		<tr>
                              		<td><label>Apellidos:</label></td>
                              		<td><input type="text" class="form-control" id="txtApellidos"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Entidad Patronal:</label></td>
                              		<td>
                              			<select class="form-control" id="id_entidad_patronal">
                              			<option value="">--Seleccione--</option>
                              			</select>
                          			</td>
                          		</tr> 
                          		<tr>
                              		<td><label>Categoria Aporte:</label></td>
                              		<td>
                              			<select class="form-control" id="id_tipo_aporte">
                              			<option value="">--Seleccione--</option>
                              			</select>
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
                          	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
          			</div>
          			
          		</div>
          	</div>
		</div>
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
                  
  		<div class="box-body">
  		
  		
  		<div class="row">
  			<div class="col-md-12">
          		<div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    	<!-- AQUI PONER LOS TITULOS DE TABS -->
                      <li><a href="#panel_1" data-toggle="tab">Consulta General</a></li>
                      <li><a href="#panel_2" data-toggle="tab">Socios</a></li>
                      <li><a href="#panel_3" data-toggle="tab">Expedientes/Solicitudes</a></li>
                      <li><a href="#panel_4" data-toggle="tab">Inversiones</a></li>
                      <li><a href="#panel_5" data-toggle="tab">Prestamos</a></li>
                      <li><a href="#panel_6" data-toggle="tab">Supv. Afiliados/Cesantes</a></li>
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
                    		
                    		<form id="frm_busqueda_principal" action="<?php echo $helper->url("PrincipalBusquedas","index"); ?>" method="post" >
                    		             	             	
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
            				<label for="btn_principal_busqueda" class=" control-label" ></label> 
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
                                      
                                      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                                      <table id="tblResultadosPrincipal" class="table table-responsive">
                                      </table>
                                      
                                      <div id="mod_paginacion_resultados"></div>
                                	  <div class="clearfix"></div>  
                                    </div>
                        		</div>
                        	</div>            	
                 
           					</form>
                    		
                    	</div>
                    	<!-- END PANEL 2 --Socios-- -->
                    	
                    	<!-- PANEL 3 --Solicitudes-- -->
                    	<div class="tab-pane" id="panel_3">
                    		
                    	</div>
                    	<!-- END PANEL 3 --Solicitudes-- -->
                    	
                    	<!-- PANEL 4 --Inversiones-- -->
                    	<div class="tab-pane" id="panel_4">
                    		
                    	</div>
                    	<!-- END PANEL 4 --Inversiones-- -->
                    	
                    	<!-- PANEL 5 --Prestamos-- -->
                    	<div class="tab-pane" id="panel_5">
                    		
                    	</div>
                    	<!-- END PANEL 5 --Prestamos-- -->
                    	
                    	<!-- PANEL 6 --Superavit-- -->
                    	<div class="tab-pane" id="panel_6">
                    		
                    	</div>
                    	<!-- END PANEL 6 --Superavit-- -->
                                                     
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
  

    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<!-- date-range-picker -->
    <script src="view/bootstrap/bower_components/moment/min/moment.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- js personales -->
    <script type="text/javascript" src="view/principal/js/vtnRegistroAportes.js?0.01"></script>

  </body>
</html>   

