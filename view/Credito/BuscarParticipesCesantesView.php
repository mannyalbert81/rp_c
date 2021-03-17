<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
 
         .form-control {
            border-radius: 5px !important;
        }
        
        /*estilo para una tabla predefinida tblcancelacion*/
       #tblCancelacion td{
            padding: 1px 2px;
            
       }
       #tblCancelacion .form-control{
            padding: 3px 12px;
            height: 25px;
            
       }
       
       .letrasize11{
        font-size: 11px;
       }
 	  
 	</style>
   <?php include("view/modulos/links_css.php"); ?>
  			        
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
          	<h3 class="box-title">Consulta y Cálculo de </span></h3>
          	<div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          	</div> 
          </div>
          
          <div class="box-body">
          
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Cedula:</label>
                		<div id="mensaje_cedula_participe" class="errores"></div>
                		<div class="input-group">
                			<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_participe" name="cedula_participe" placeholder="C.I.">
                			
            				<span class="input-group-btn">
            			    	<button type="button" class="btn btn-primary" id="buscar_participe" name="buscar_participe" onclick="BuscarParticipe()">
        						<i class="glyphicon glyphicon-search"></i>
        						</button>
        						<button type="button" class="btn btn-danger" id="borrar_cedula" name="borrar_cedula" onclick="BorrarCedula()">
        						<i class="glyphicon glyphicon-arrow-left"></i>
        						</button>
        					</span>
        					
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
           			<div id="info_solicitud" ></div>
           		</div>

           	</div>
           	
			<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           			<div id="link_solicitud" ></div>
           		</div>

           	</div>
           	
               	<div class="row">
               	    <div class="col-xs-12 col-md-3 col-md-3 ">
                		    <div class="form-group">
                		    					  
                              <label for="id_fecha_prestaciones" class="control-label">Fecha Prestación:</label>
                              <input  type="date" class="form-control" id="fecha_prestaciones" name="fecha_prestaciones" value="<?php echo date(dd/MM/yyyy);?>"  placeholder="" required/>                         
                            </div>
                	</div>
               	    <div class="col-xs-12 col-md-3 col-md-3 ">
                		    <div class="form-group">
                		    					  
                              <label for="id_tipo_prestaciones" class="control-label">Tipo Prestación:</label>
                              <select  class="form-control" id="id_tipo_prestaciones" name="id_tipo_prestaciones" required>
                              	<option value="0">--Seleccione--</option>
                              </select>                         
                              <div id="mensaje_id_tipo_pestaciones" class="errores"></div>
                            </div>
                	</div>
    
                	 <div class="col-xs-12 col-md-6 col-md-6 ">
                		    <div class="form-group">
                		    					  
                              <label for="id_observacion_prestaciones" class="control-label">Observación:</label>
                              <input  type="text" class="form-control" id="observacion_prestaciones" name="observacion_prestaciones" value=""  placeholder="" required/>	         
                              <div id="divLoaderPage" ></div>                     	
                                                  
                            </div>
                	</div>
                	
                	
                </div>
                
                  <div class="row">	
                    	<div class="col-xs-12 col-md-12 col-md-12" style="text-align: center; ">
                         	<button type="button" onclick="AportesParticipe(this)" id="btn_simular" name="btn_simular" class="btn btn-info"><i class='glyphicon glyphicon-info-sign'></i> Simular</button>
                  	  		
        				</div>
        			</div>
        			
        			<div class="row">
                   		<div class="col-xs-12 col-md-12 col-lg-12 ">
                   		<div id="aportes_participe_registrados" ></div>
                   		</div>
                   	</div>
                   	
                   	<div class="row">
                   		<div class="col-xs-12 col-md-12 col-lg-12 ">
                   		<div id="aportes_participe_patronal" ></div>
                   		</div>
                   	</div>
                   	
                   	<div class="row">
                   		<div class="col-xs-12 col-md-12 col-lg-12 ">
                   		<div id="creditos_participe" ></div>
                   		</div>
                   	</div>
                   	
                   	<div class="row">
                   		<div class="col-xs-12 col-md-12 col-lg-12 ">
                   		<div id="tabla_desafiliacion" ></div>
                   		</div>
                   	</div>
                   	
              
              		<div class="row">
                   		<div class="col-xs-12 col-md-12 col-lg-12 ">
                   		<div id="resultado_guardar" ></div>
                   		</div>
                   	</div>
                   	
               		<div class="row hide" id="dvbtn_opciones">	
                    	<div class="col-xs-12 col-md-12 col-md-12" style="text-align: center; ">
                         	<button type="button" onclick="GuardaDesafiliación(this)" id="btn_guardar" name="btn_guardar" class="btn btn-success"><i class='glyphicon glyphicon-floppy-saved'></i> Guardar</button>
                         	<button type="button" onclick="Cancelar(this)" id="btn_cancelar" name="btn_cancelar" class="btn btn-danger"><i class='glyphicon glyphicon-floppy-remove'></i> Cancelar</button>
                  	  		
        				</div>
        			</div>
						
           	</div>

           	
          </div>
       
    </section>
    
    
    <section class="content">
     	<div id="smartwizard">
            <ul>
                <li><a href="#step-1">Datos Personales<br /><small> </small></a></li>
                <li><a href="#step-2">Creditos<br /><small></small></a></li>
                <li><a href="#step-3">Destinatarios<br /><small></small></a></li>
            </ul>
            
            <div>
                <div id="step-1" class="">
                  <div class="box box-primary">
            		<div class="box-header with-border">
              			<h3 class="box-title">Datos Validacion</h3>
               			<div class="box-tools pull-right"> </div>
              
            		</div>
            
            		<div class="box-body">
            			<div class="row">
            
            				<div class="col-lg-4 col-xs-12 col-md-4">
                            	<div class="form-group">
                            		<label for="identificacion_participe" class="control-label">Cedula:</label>
                            		<div class="input-group">
                            			<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="identificacion_participe" name="identificacion_participe" placeholder="C.I.">                            			
                        				<span class="input-group-btn">
                        			    	<button type="button" class="btn btn-primary" id="btn_buscar_identificacion" name="btn_buscar_identificacion">
                    						<i class="glyphicon glyphicon-search"></i>
                    						</button>
                    						<button type="button" class="btn btn-danger" id="btn_limpiar_identificacion" name="btn_limpiar_identificacion" >
                    						<i class="glyphicon glyphicon-arrow-left"></i>
                    						</button>
                    					</span>
                    					
                    				</div>
                             	</div>
                             	<!-- PARA GUARDAR VALORES PARTICIPE -->
                             	<input type="hidden" value="0" id="id_participes">
               		  		</div>
           		   
           		   			<div class="col-xs-12 col-lg-8 col-md-8 ">
            		    		<div id="dvdatos_participes">
                           			
                          
                        		</div>
            		  		</div>
           		  
           				</div>
                
     				 </div>
     			</div>
 			</div>
           
            <div id="step-2" class="">
                            
				<div class="box box-primary">
            		<div class="box-header">
              			<h3 class="box-title">Revisar Datos Creditos</h3>
              			<div class="box-tools pull-right">
                    		<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      		<i class="fa fa-minus"></i></button>
              			</div>
            		</div>
            
            		<div class="box-body">
            		
            			<div class="row">            				
            				<div class="col-lg-12 col-md-12 col-xs-12">
            				
            				<div class="row">       
            					<!-- PANEL IZQUIERDO -->     				            			
            				    <div class="col-lg-6 col-md-6 col-xs-12">
            				    
            				    	<div class="row">
            				    	<div class="col-xs-6 col-md-4 col-lg-4 ">
                                		<div class="form-group">
                                    		<label for="txt_fecha_prestacion" class="control-label">Fecha Prestacion:</label>
                                    		<div class="input-group">
                                    			<input type="date" class="form-control" id="txt_fecha_prestacion" name="txt_fecha_prestacion">
                                    			
                                				<span class="input-group-btn">
                                			    	<button type="button" class="btn btn-primary" id="btn_buscar_credito" name="btn_buscar_credito">
                            						<i class="glyphicon glyphicon-search"></i>
                            						</button>                            						
                            					</span>
                            					
                            				</div>
                                     	</div>
                                 	</div>
                                 	</div>
                                 	
                                 	
                                 	<div class="row">
                                 		<div class="col-lg-5 col-md-5 col-xs-12">
                        					<div class="form-group">
                                            <label for="numero_creditos" class="control-label">Numero Creditos:</label>
                                            <input type="text" class="form-control" id="numero_creditos" name="numero_creditos" value=""  >
                    			         	</div>
                			         	</div>
                                 	</div>
                                 	
                                 	<div class="row">
                                 		<div class="col-lg-5 col-md-5 col-xs-12">
                        					<div class="form-group">
                                            <label for="fecha_ultimo_pago" class="control-label">Fecha ultimo pago:</label>
                                            <input type="text" class="form-control" id="fecha_ultimo_pago" name="fecha_ultimo_pago" value=""  >
                    			         	</div>
                			         	</div>
                                 	</div>
                                 	
                                 	<div class="row">
                                 		<div class="col-lg-5 col-md-5 col-xs-12">
                        					<div class="form-group">
                                            <label for="txt_estado" class="control-label">Estado:</label>
                                            <input type="text" class="form-control" id="txt_estado" name="txt_estado" value=""  >
                    			         	</div>
                			         	</div>
                                 	</div>
            				    	            				    
            				    </div>		
            					
                 				<!-- PANEL DERECHO -->  
                 				<div class="col-lg-6 col-md-6 col-xs-12">
                 					 <div class="row">
                                 		<div class="col-lg-5 col-md-5 col-xs-12">
                        					<div class="form-group">
                                            <label for="txt_estado" class="control-label">Estado:</label>
                                            <input type="text" class="form-control" id="txt_estado" name="txt_estado" value=""  >
                    			         	</div>
                			         	</div>
                                 	</div>
                                 	
                                 	<!-- validador paso 2 -->
                                 	<input type="hidden" id="hdn_step_dos" value="0">
        
                 				</div>
            					
            				</div> <!-- end fila paneles -->
            				            					            				        				
            				</div> <!-- end division main fila paneles -->
            				
            			</div> <!-- end  main fila paneles -->
           
           
                   
      				</div> <!-- end body boot panel -->
     				</div>
                
                
                </div> <!-- end step 2 wizard -->
                      
            </div> <!-- end contenedor  steps -->
        
        </div> <!-- end wizard -->
        
     </section>
    
    
    
   </div>
 
 	<?php include("view/modulos/footer.php"); ?>
 	
   <div class="control-sidebar-bg"></div>
   
   <!-- MODALS -->
   
   <!-- BEGIN MODAL CANCELACION CREDITOS -->
	<div class="modal fade" id="mod_cancelacion_creditos" data-backdrop="static" data-keyboard="false">
    	<div class="modal-dialog   modal-lg " role="document" >
        	<div class="modal-content">
          		<div class="modal-header bg-primary color-palette">
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            		<h4 class="modal-title" align="center">CANCELACI&Oacute;N DEL PR&Eacute;STAMO</h4>
          		</div>
              	<div class="modal-body" >
              	
              		<div class="row">
              		<div class="col-sm-12 col-md-12 col-lg-12">
              		
              			<div class="panel panel-default">
                      		<div class="panel-heading"> 
                      		
                      			<table id="tblCancelacion" class="table">
                          			<thead>
                      				</thead>
                                  	<tbody>
                                  		<tr>
                                      		<td><label>Cancelacion Total:</label></td>
                                      		<td>
                                          		<div class="form-check">
                                                  <input class="form-check-input" type="checkbox" value="0" id="chk_cancelacion_total">                                                      
                                                </div>
                                  			</td> 
                                  			<td><label>Fecha Cobro:</label></td>  
                                  			<td>
                                  				<input type="text" class="form-control" id="md_fecha_cobro" value="">
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td><label></label></td>
                                      		<td></td> 
                                  			<td><label>Saldo Cta. Individual:</label></td>  
                                  			<td>
                                  				<input type="text" class="form-control" id="md_cuenta_individual" value="">
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td><label>Forma aplicacion Pagos:</label></td>
                                      		<td>
                                      		<select class="form-control" id="md_aplicacion_pagos">
                                  			<option value="0">--Seleccione--</option>
                                  			</select>
                                  			</td> 
                                  			<td><label>Fecha Contable:</label></td>  
                                  			<td>
                                  				<input type="text" class="form-control" id="md_fecha_contable" value="">
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td><label>Valor a Cobrar:</label></td>
                                      		<td>
                                      			<input type="text" class="form-control" id="md_valor_cobrar" value="">
                                  			</td> 
                                  			<td><label></label></td>  
                                  			<td></td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td colspan="4" class="text-center"><label></label></td>                                      		             		
                                  		</tr>
                                  		<tr>
                                      		<td colspan="4" class="text-center"><label>DATOS DEL COBRO</label></td>                                      		             		
                                  		</tr>
                                  		<tr>
                                      		<td><label>Motivos Pago:</label></td>
                                      		<td>
                                      		<select class="form-control" id="md_motivos_pago">
                                  			<option value="0">--Seleccione--</option>
                                  			</select>
                                  			</td> 
                                  			<td><label>Formas Pago:</label></td>  
                                  			<td>
                                  				<select class="form-control" id="md_formas_pago">
                                      			<option value="0">--Seleccione--</option>
                                      			</select>
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td><label>Banco:</label></td>
                                      		<td>
                                      		<select class="form-control" id="md_id_bancos">
                                  			<option value="0">--Seleccione--</option>
                                  			</select>
                                  			</td> 
                                  			<td><label>Cuenta Bancaria:</label></td>  
                                  			<td>
                                  				<select class="form-control" id="md_cuenta_bancaria">
                                      			<option value="0">--Seleccione--</option>
                                      			</select>
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td><label>Referencia/Papeleta/Num. Documento</label></td>
                                      		<td>
                                          		<input type="text" class="form-control" id="md_referencia_pago" value="">
                                  			</td> 
                                  			<td><label>Concepto:</label></td>  
                                  			<td>
                                  				<input type="text" class="form-control" id="md_concepto_pago" value="">
                                  			</td>                  		
                                  		</tr>
                                  		<tr>
                                      		<td colspan="4" class="text-center"><label></label></td>                                      		             		
                                  		</tr>
                                  		<tr>
                                      		<td><label></label></td>
                                      		<td>
                                      			<button type="button" class="btn btn-default" id="md_btnTransacciones" value="transacciones" onclick="fn_insertar_ingreso_bancos()">Transacciones</button>
                                  			</td> 
                                  			<td><label></label></td>  
                                  			<td>
                                  				<button type="button" class="btn btn-default" id="md_btnGuardar" value="registrar" onclick="fn_insertar_ingreso_bancos()">Registrar Pago</button>
                                  			</td>                  		
                                  		</tr>
                                  	</tbody>
                                 </table>
                      		</div>
                  		</div>
              			
              		</div>  
              		</div>  
              	</div>
          
        	</div>
            <!-- /.modal-content -->
      	</div>
      <!-- /.modal-dialog -->
	</div>
	<!-- END MODAL CANCELACION CREDITOS -->
   
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   	<script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

	<script type="text/javascript" src="view/bootstrap/smartwizard/dist/js/jquery.smartWizard.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   	<script src="view/Credito/js/BuscarParticipesCesantes.js?1.56"></script> 
   	<script src="view/Credito/js/PrestacionesParticipes.js?0.00"></script> 
   	<script src="view/Credito/js/wizardPrestacionesParticipes.js?0.00"></script> 
   	

   </body>
</html>   