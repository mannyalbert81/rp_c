<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    
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
        
        .codigo {
        width: 15%;
        font-size:32px;
        text-align:center;
        }
        .observacion {
        width: 75%;
        }
        ul{
        list-style-type:none;
          }
      li{
        list-style-type:none;
        }
        
      h3.titulo{
		width: 100%; 
		text-align: center;	
    	}
    	
	.bio-row{
            width: 100%;
            float: left;
            margin-bottom:0px;
            padding: 0 15px;
    }
       
    .bio-row p {
             margin: 0 0 1px;
    }   
        
    .bio-row p span.tab{
    	font-weight: bold;
    	display: inline-block;
    	width: 120px;
    }
    
    .bio-row p span.tab2{
    	font-weight: bold;
    	display: inline-block;
    	width: 130px;
    }
 	  
 	</style>
   
  			        
    </head>
    <body id="cuerpo" class="hold-transition skin-blue fixed sidebar-mini"  >

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
        <li class="active">Buscar Participes</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Busqueda de Participes</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
          <div class="box-body">
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_participe" class="control-label">Cedula:</label>
                		<div id="mensaje_cedula_participe" class="errores"></div>
                		<div class="input-group">
                			<input type="text" value="<?php echo isset( $cedula_participes ) ? $cedula_participes : 0 ; ?>" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_participe" name="cedula_participe" placeholder="C.I." readonly>
                			
            				<span class="input-group-btn" id="buscar_participe_boton">
            				
            			    	<!--<button type="button" class="btn btn-primary" id="buscar_participe" name="buscar_participe" onclick="BuscarParticipe()">
        						<i class="glyphicon glyphicon-search"></i>
        						</button>
        						<button type="button" class="btn btn-danger" id="borrar_cedula" name="borrar_cedula" onclick="BorrarCedula()">
        						<i class="glyphicon glyphicon-arrow-left"></i>
        						</button>-->
        					</span>
        					
        				</div>
                 	</div>
             	</div>
           	</div>
           	
           	<!-- DATOS HIDDEN -->
           	<input type="hidden" id="hdn_id_solicitud" value=" <?php echo isset( $id_solicitud ) ? $id_solicitud : 0 ; ?>">
           	<input type="hidden" id="hdn_cedula_participes" value="<?php echo isset( $cedula_participes ) ? $cedula_participes : 0 ; ?>">
           	<input type="hidden" id="hdn_id_participes" value="0">
           	<!-- TERMINA DATOS HIDDEN -->
           	           	
           	<div class="row">
  			<div class="col-md-12">
          		<div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    	<!-- AQUI PONER LOS TITULOS DE TABS -->
                      <li><a href="#panel_info" data-toggle="tab">Informacion</a></li>
                      <li><a href="#panel_detalle" data-toggle="tab">Detalle Valores</a></li>
                      <li><a href="#panel_capacidad" data-toggle="tab">Capacidad Pago</a></li>
                    </ul>
                    <div class="tab-content">
                    	<!-- AQUI COMIENZA PARA PONER CONTENIDOS DE PANELES -->
                    	<!-- PANEL INDEX -->
                    	<div class="active tab-pane" id="panel_index">
                    		<?php include 'view/principal/html/panelindex.php'; ?>
                    	</div>
                    	<div class="tab-pane" id="panel_info">
                    	
                    		<div id="div_pnl_info_solicitud">
                    			<h3 class="titulo">INFORMACION SOLICITUD</h3>
                    			<div class="box-footer no-padding">
                            	<div class="bio-row"><p><span class="tab">A&Ntilde;O: </span>{ANIO_DESCUENTOS}</p></div>
                            	<div class="bio-row"><p><span class="tab">MES: </span>{MES_DESCUENTOS}</p></div>	
                            	</div>
                    		</div>
                    		<hr>
							<div id="div_pnl_info_participe">
							</div>	
							<hr>
							<div class="row">
								<div id="div_pnl_creditos_renovacion">
								</div>	
							</div>							
							<hr>
							<div id="div_pnl_respuesta_informacion">
							</div>					
							<hr>
							<div id="div_pnl_simulacion_credito">
								<div class="row">		    	 
                		    	 	<div class="col-xs-12 col-md-3 col-lg-3 ">
                            		    <div class="form-group">                            		    					  
                                          <label for="ddl_tipo_creditos" class="control-label">Tipo Creditos:</label>
                                          <select class="form-control" id="ddl_tipo_creditos" name="ddl_tipo_creditos">
                                            <option value="0" >--Seleccione--</option>
                                          </select>                                                              
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-lg-3 ">
                            		    <div class="form-group">                            		    					  
                                          <label for="txt_monto_creditos" class="control-label">Monto Credito:</label>
                                          <input type="number" step="10" id="txt_monto_creditos" class="form-control" value="">                                                                                                      
                                        </div>
                            		  </div>
                            		                              		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 " >
                            		  	<label for="txt_capacidad_pago" class="control-label">Capacidad Pago:</label>
                            		  	<div class="input-group">
                							<input type="text" value="" class="form-control" id="txt_capacidad_pago" readonly>                			
                            				<span class="input-group-btn">
                            					<button type="button" title="Análisis crédito" class="btn bg-olive"  id="btn_capacidad_pago">
                        						 <i class="glyphicon glyphicon-new-window"></i>
                        						</button>
                        					</span>
        					
        								</div>
                            		  </div>  
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 " >
                            		  	<div class="form-group">                            		    					  
                                          <label for="txt_cuotas_creditos" class="control-label">Cuotas Creditos:</label>
                                          <input type="text" id="txt_cuotas_creditos" class="form-control" value="">                                                                                                      
                                        </div>
                            		  </div>                              		  
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 " >
                            		  	<div id="div_capacidad_pago_garante"></div>
                            		  </div>
                            		  
                            		                              		  
                        		  </div>
                        		  
                        		  <div class="row">
                        		  	<div class="col-md-offset-9 col-lg-offset-9 col-xs-12 col-md-3 col-md-3 ">
                            		    <label for="txt_monto_creditos" class="control-label">&nbsp;</label>
                                        <div class="input-group">               			
                            				<span class="input-group-btn">
                            					<button type="button" id="Buscar" name="Buscar" class="btn btn-primary" onclick="GetCuotas()">
                                          			<i class="glyphicon glyphicon-expand"></i> SIMULAR</button>  
                        					</span>        					
        								</div>
                            		  </div>
                        		  </div>
                        		  
                        		  <div id="div_tabla_amortizacion"></div>
                        		  
							</div>	
                    		
                    	</div>
                    	<div class="tab-pane" id="panel_detalle">
                    		
                    		<div id="div_pnl_participe_encontrado"></div>
                    		
                    		<div id="div_pnl_participe_aportes"></div>
                    		
                    		<div class="clearfix"></div>
                    		
                    		<div id="div_pnl_participe_creditos"></div>
                    		
                    	</div>
                    	<div class="tab-pane" id="panel_capacidad">
                    		<div class="form-group">
								<div class="row">
                					<table align="center" class="tablesorter table table-striped table-bordered" style="width: 50%;">
                        				<tr>
                        					<th>SUELDO LIQUIDO:</th>
                        					<td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad" id="txt_sueldo_liquido"></td>
                    					</tr>
                                        <tr>
                                            <th>CUOTA VIGENTE:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_cuota_vigente" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>FONDOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad" id="txt_fondos" ></td>
                                        </tr>
                                        <tr>
                                            <th>DECIMOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad" id="txt_decimos" ></td>
                                        </tr>
                                        <tr >
                                            <th>RANCHO:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad" id="txt_rancho" ></td>
                                        </tr>
                                        <tr >
                                            <th>INGRESOS NOTARIZADOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad" id="txt_ingresos_notarizados" ></td>
                                        </tr>
                                        <tr >
                                            <th>TOTAL INGRESO:</th>
                                            <td><input style="text-align: right" type="number" class="form-control" id="txt_total_ingresos" readonly></td>
                                        </tr>
                                    </table>
                        	<div class="row">
                                 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                                	<div id="btn_boton_capacidad_pago" class="form-group">
                                		<button type="button" id="btn_enviar_capacidad_pago" class="btn btn-primary">
                                		<i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>
                                	</div>
                                 </div>	
                          	
           					</div>
						</div>
							<br>
							</div>	
                    	</div>
                    </div>
                 </div>
             </div>
             </div>
             
           	
           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="participe_encontrado" ></div>
           		</div>
           	</div>
           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="aportes_participe" ></div>
           		</div>
           	</div>
           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="creditos_participe" ></div>
           		</div>
           	</div>
          </div>
        </div>
        
    </div>
    </section>
   </div>
  
 

 <!-- Modal Simulacion Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalSimulacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_simulacion" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Insertar Crédito</h4>
			</div>
			<div class="modal-body">
				
				<div id="info_solicitud"></div>
				<div id="info_participe"></div>	
				
				<div class="form-group">
				
					 
          	 <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<div id="tipo_creditos"></div>
                        <div id="mensaje_tipo_credito" class="errores"></div>
                 	</div>
             	</div>
             	<div id="capacidad_de_pago_participe"></div>
             	<div id="monto_del_credito"></div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
            			<div id="select_cuotas"></div>
                 	</div>
             	</div>
          	</div>
          	
          	 <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
          		
             	</div>
             	<div id="capacidad_pago_garante"></div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             	</div>
          	</div>
          	
          	<div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Buscar" name="Buscar" class="btn btn-primary" onclick="GetCuotas()"><i class="glyphicon glyphicon-expand"></i> SIMULAR</button>
                </div>
             </div>	    
            </div>
            <div id="tabla_amortizacion"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

 

<!-- Modal Inserta Credito -->
 
 <div class="modal fade bs-example" id="myModalInsertar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_insertar" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmar Crédito</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
				 <div id="info_credito_confirmar"></div>
				 <input type="text" class="observacion" maxlength="200" class="form-control" id="observacion_confirmacion" name="observacion_confirmacion" placeholder="Observación Crédito">
				 <br>
          	 <div class="row">
          	 		<div class="form-group" align="center">
          	 		<input type="text" class="codigo" data-inputmask="'mask': '99999'" class="form-control" id="codigo_confirmacion" name="codigo_confirmacion">
                 </div>
              </div>
              <div class="row">
          	 		<div class="form-group" align="center">
          	 		<button type="button" id="registrar_credito" name="registrar_credito" class="btn btn-primary" onclick="RegistrarCredito()"> ACEPTAR</button>
                 </div>
              </div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Analisis Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAnalisis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button id="cerrar_analisis" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Capacidad de pago</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
                		<table align="center" class="tablesorter table table-striped table-bordered" style="width: 50%;">
                        <tr>
                        <th>SUELDO LIQUIDO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="sueldo_liquido" name="sueldo_liquido" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr>
                        <th>CUOTA VIGENTE:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="cuota_vigente" name="cuota_vigente" readonly></td>
                        </tr>
                        <tr>
                        <th>FONDOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="fondos" name="fondos" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr>
                        <th>DECIMOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="decimos" name="decimos" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>RANCHO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="rancho" name="rancho" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>INGRESOS NOTARIZADOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="ingresos_notarizados" name="ingresos_notarizados" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>TOTAL INGRESO:</th>
                        <td id="total_ingreso" align="right" style="padding-right: 35px;"></td>
                        </tr>
                        </table>
                        	<div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div id="boton_capacidad_pago" class="form-group"></div>
             </div>	
                          	
           	</div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>
</div>

<!-- Modal Analisis Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAvaluo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button id="cerrar_avaluo" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Avaluo del Bien</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
                		<table align="center" class="tablesorter table table-striped table-bordered" style="width: 50%;">
                        <tr>
                        <th>AVALUO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="avaluo_bien" name="avaluo_bien" ></td>
                        </tr>
                        <tr>
                        </table>
                        	<div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<button type="button" id="enviar_avaluo_bien" name="enviar_avaluo_bien" class="btn btn-primary" onclick="EnviarAvaluoBien()"><i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>
             </div>	
                          	
           	</div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>
</div>

<!-- Modal Creditos para renovacion -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalCreditosActivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_renovar_credito" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Créditos Activos</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				 <div id="info_participe_creditos_activos"></div>				 
            	<div id="tabla_creditos_activos"></div>
				</div>
				<br>
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
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
   <script src="view/Credito/js/CreditosParticipes.js?0.03"></script> 
   </body>
</html>   