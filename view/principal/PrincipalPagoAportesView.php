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
    		  <h3 class="box-title">Registro manual de aportes</h3>
    		</div>
    		<div class="box-body">
  		  		
          		<div class="row">
          			<!-- Este div es para mostrar datos del participe -->
          			<div class="col-sm-6 col-md-6 col-lg-6">
          				<div class="panel panel-default">
                          <div class="panel-heading">                         	
                  			
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
                              		<td><label>A&ntilde;o:</label></td>
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
                              		<td><label>Archivo:</label></td>
                              		<td>
										<input id="imagen_registro" type="file" class="form-control" > 
										<div id="errorImagen"></div>                            		
                                    </td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>N° Documento:</label></td>
                              		<td><input type="text" class="form-control" id="numero_documento_registro" value=""></td>
                          		</tr>
                          		<tr>
                              		<td><label>Tipo Aporte:</label></td>
                              		<td>
                              			<select class="form-control" id="ddl_tipo_aporte_registro">
                              			<option value="0">--Seleccione--</option>
                              			</select>
                          			</td>                              		
                          		</tr>                          		
                          		<tr>
                              		<td><label>Descuento por:</label></td>
                              		<td>
                              			<input type="hidden" id="hdn_id_tipo_aportacion" value="0">
                              			<div class="row">
                                          <div class="col-xs-8 col-md-8 col-lg-8">
                                          	<input type="text" class="form-control" id="tipo_descuento_registro" value="" readonly>
                                      	  </div>
                                          <div class="col-xs-4 col-md-4 col-lg-4">
                                          	<input type="text" class="form-control" id="valor_descuento_registro" value="" readonly>
                                          </div>
                                        </div>                             			
                              		</td>
                          		</tr> 
                          		<tr>
                              		<td><label>Ultimo Sueldo:</label></td>
                              		<td><input type="text" class="form-control" id="ultimo_sueldo_registro" onkeyup="fnCalcularAporte()" ></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Valor Calculado:</label></td>
                              		<td><input type="text" class="form-control" id="valor_calculado_registro" readonly></td>
                          		</tr>
                          		<tr>
                              		<td><label>Valor a Aportar:</label></td>
                              		<td><input type="text" class="form-control" id="valor_aportar_registro"></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Observaci&oacute;n:</label></td>
                              		<td><textarea rows="1" cols="" id="observacion_registro" class="form-control"></textarea></td>
                          		</tr>
                          		<tr>
                              		<td><label>Fecha Transaccion:</label></td>
                              		<td><input type="text" class="form-control" id="fecha_transaccion_registro" value="<?php echo date('Y-m-d');?>" ></td>
                          		</tr>
                          		<tr>
                              		<td><label>Fecha Contable:</label></td>
                              		<td>
                              			<input type="text" class="form-control" id="fecha_contable_registro" value="<?php echo date('Y-m-d');?>" data-fechaperiodo="">
                              			
                          			</td>
                          		</tr>                          		
                          		<tr>
                              		<td><label>Tipo de Transaccion:</label></td>
                              		<td>
                              			<select class="form-control" id="ddl_tipo_transaccion">
                              			<option value="0">--Seleccione--</option>
                              			<option value="1" selected >GESTION DE APORTES</option>
                              			</select>
                          			</td>                              		
                          		</tr> 
                          		<tr>
                              		<td><label>Tipo de Ingreso:</label></td>
                              		<td>
                              			<select class="form-control" id="ddl_tipo_ingreso" onchange="fnValidaTipoIngreso()">
                              			<option value="0">--Seleccione--</option>
                              			</select>
                          			</td>                              		
                          		</tr> 
                          		<tr>
                              		<td><label>Banco:</label></td>
                              		<td>
                              			<select class="form-control" id="ddl_banco_registro">
                              			<option value="0">--Seleccione--</option>
                              			</select>
                          			</td>                              		
                          		</tr>
                          		<tr>
                              		<td><label></label></td>
                              		<td>
                              			<button type="button" class="btn btn-success" id="btnGuardar" value="guardar" onclick="fnIngresarRegistro()">Guardar</button>
                              			<button type="button" class="btn btn-danger" id="btnCancelar" value="cancelar" onclick="fnCancelarRegistro()" >Cancelar</button>
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
    <script type="text/javascript" src="view/principal/js/vtnRegistroAportes.js?0.07"></script>
    
    

  </body>
</html>   

