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
    <input type="hidden" value="0" id="hdnid_participes_prestamos">
    <input type="hidden" value="0" id="hdnid_creditos">
  <!-- end HIDDENs vista -->
      <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Registros</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#prestamos" data-toggle="tab">Préstamos</a></li>
              <li><a href="#impresiones" data-toggle="tab">Impresiones</a></li> 
              <li><a href="#cobros" data-toggle="tab">Cobros</a></li> 
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
 			 <div class="tab-pane active" id="prestamos">
 			 	<div class="col-sm-12 col-md-12 col-lg-12">
 			 	
          				<div class="panel panel-info">
                           <div class="panel-heading" >Datos Principales</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		<tr>
                              		<td><label>Identificacion:</label></td>
                              		<td><input type="text" class="form-control" id="lblIdentificacion" value="" readonly></td>                     		
                          		</tr>
                          		<tr>
                              		<td><label>Nombres:</label></td>
                              		<td><input type="text" class="form-control" id="lblNombres" value="" readonly></td>
                          		</tr>
                          		<tr>
                              		<td><label>Apellidos:</label></td>
                              		<td><input type="text" class="form-control" id="lblApellidos" value="" readonly></td>
                          		</tr> 
                          		<tr>
                              		<td><label>Entidad Patronal:</label></td>
                              		<td>
                              			<select class="form-control" id="id_entidad_patronal" value="" readonly></select>
                          			</td>
                          		</tr> 
                                 	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>        
          				
          					<div class="panel panel-info">
                           <div class="panel-heading" >Flujo de Prestamo</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		
                    		</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>    			
          				
          				<div class="panel panel-info">
                           <div class="panel-heading" >Información para tabla amortización</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		
                    		</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>    		
          				
          				<div class="panel panel-info">
                           <div class="panel-heading" >Ingreso de Requisitos</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<tbody>
                    <tr>
					<th colspan=1>Número de Crédito:</TH>
					<th colspan=3>
					<input type="text" class="form-control" id="lblNumeroCredito" value="" readonly>
					</th>
					</tr>
					<tr>
					<th>Estado</th>
					<th><input type="text" class="form-control" id="lblEstadoCredito" value="" readonly></th>
					<th>Receptor de la Solicitud:</th>
					<th><input type="text" class="form-control" id="lblReceptorSolicitud" value="" readonly></th> 
					</tr>
					<tr>
					<th>Fecha de Solicitud:</th>
					<th><input type="text" class="form-control" id="lblFechaConsecion" value="" readonly></th>
					<th>Año y Mes Activo:</th>
					<th><input type="text" class="form-control" id="lblvfdgdfNombres" value="" readonly></th> 
					</tr>
					 <tr>
					<th colspan=1>Tipo de Préstamo:</TH>
					<th colspan=3>
					<select class="form-control" id="id_tipo_creditos" value="" readonly></select>
					</th>
					</tr>
					<tr>
					<th>Plazo Maximo (meses):</th>
					<th><input type="text" class="form-control" id="lblPlazoMaximo" value="" readonly></th>
					<th>Número de Aportaciones (#):</th>
					<th><input type="text" class="form-control" id="lblNumeroAportaciones" value="" readonly></th> 
					</tr>
					<tr>
					<th>Encaje Minimo (%):</th>
					<th><input type="text" class="form-control" id="lblEncajeMinimo" value="" readonly></th>
					<th>Interés Mensual (#):</th>
					<th><input type="text" class="form-control" id="lblInteresMensual" value="" readonly></th> 
					</tr>
					<tr>
					<th>Cuenta Individual:</th>
					<th><input type="text" class="form-control" id="lblCuentaIndividual" value="" readonly></th>
					<th>Monto Minimo de Préstamo($):</th>
					<th><input type="text" class="form-control" id="lblMontoMinimoPrestamo" value="" readonly></th> 
					</tr>
					<tr>
					<th>Monto Maximo de Préstamo ($):</th>
					<th><input type="text" class="form-control" id="lblMontoMaximoPrestamo" value="" readonly></th>
					<th>Necesita Garante:</th>
					<th><input type="text" class="form-control" id="lblNecesitaGarante" value="" readonly></th> 
					</tr>
					 
					    	</tbody>
                            </table>
                         	<div class="panel panel-success">
                           <div class="panel-heading" >Información del Garante</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		 <tr>
            					<th colspan=1>Identificacion:</TH>
            					<th colspan=3>
            					<input type="text" class="form-control" id="lbfsdflNombres" value="" readonly>
            					</th>
            					</tr>
                          		<tr>
            					<th>Apellidos:</th>
            					<th><input type="text" class="form-control" id="lblgdfgfNombres" value="" readonly></th>
            					<th>Nombres:</th>
            					<th><input type="text" class="form-control" id="lblfdfgdfNombres" value="" readonly></th> 
            					</tr>
                          	
                                 	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>  
          				<div class="panel panel-success">
                           <div class="panel-heading" >Información para la Tabla de Amortización</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          		<tr>
            					<th>Monto ($):</th>
            					<th><input type="text" class="form-control" id="lblMonto" value="" readonly></th>
            					<th>Plazo Producto (#):</th>
            					<th><input type="text" class="form-control" id="lblPlazoProducto" value="" readonly></th> 
            					</tr>
            						<tr>
            					<th>Monto Entregado ($):</th>
            					<th><input type="text" class="form-control" id="lblMontoEntregado" value="" readonly></th>
            					<th>Valor Primera Cuota ($):</th>
            					<th><input type="text" class="form-control" id="lblValorPrimeraCuota" value="" readonly></th> 
            					</tr>
            						<tr>
            					<th>Saldo de Capital ($):</th>
            					<th><input type="text" class="form-control" id="lblSaldoCapital" value="" readonly></th>
            					<th>Tipo de Cuota :</th>
            					<th><input type="text" class="form-control" id="lblTipoCuota" value="" readonly></th> 
            					</tr>
            						<tr>
            					<th>Plazo Ingresado (#):</th>
            					<th><input type="text" class="form-control" id="lblPlazoIngresado" value="" readonly></th>
            					<th>Monto Ingresado ($):</th>
            					<th><input type="text" class="form-control" id="lblMontoIngresado" value="" readonly></th> 
            					</tr>
                          	
                                 	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div> 
          			
          			  <div class="panel panel-success">
                           <div class="panel-heading" >Tabla Amortizacion</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          
                          		<tr>
                          	<th>
            					 <div class="tab-pane active" id="pendientes">
                            
            				<div class="pull-right" style="margin-right:15px;">
            					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="TablaAmortizacion(1)" placeholder="Buscar.."/>
                			</div>  
                			
                    			<div id="TablaAmortizacion"></div>  
                    	    	<div id="tabla_amortizacion" ></div>
                    	    	<div id="divLoaderPage" ></div>
				  
                
              </div>         	</th>
            					</tr>
                                 	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>   	
          				            
                      <div class="panel panel-">
                           <div class="panel-heading" >Reportes</div>
                          <div class="panel-body">    
                  	      <!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->  
                          <table id="tbldatosParticipe" class="table">
                          	<thead>
                          	</thead>
                          	<tbody>
                          
                          		<tr>
                          	
            						<th><a class="btn btn-success" onclick="generar_tabla_amortizacion(this)" title="Tabla Amortización" href="#" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i> Imprimir Tabla de Amortización</a></th>
	               
            					</tr>
                                 	</tbody>
                          	<tfoot>
                          	</tfoot>
                          </table>
                    	</div>
          				</div>        
                    	</div>
                    
          				</div>    			
          			</div>
 		       </div>
              <div class="tab-pane" id="impresiones">
              
              </div>
              <div class="tab-pane" id="cobros">
              
              </div>
           
              
             </div>
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
     <script type="text/javascript" src="view/principal/js/RegistroPrestamos.js?0.10"></script>
  

  </body>
</html>   

