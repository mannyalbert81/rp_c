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
 
     
       
 	  
 	</style>
   <?php include("view/modulos/links_css.php"); ?>
  			        
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >

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
  
  <!-- Modal Analisis Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAnalisis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Análisis Crédito</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
          		<div class="col-xs-12 col-md-6 col-lg-6 ">
            		<div class="form-group">
                		<table align="right" class="tablesorter table table-striped table-bordered">
                        <tr>
                        <th>SUELDO LIQUIDO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="sueldo_liquido" name="sueldo_liquido" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr>
                        <th>CUOTA VIGENTE:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="cuota_vigente" name="cuota_vigente" onkeyup="SumaIngresos()"></td>
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
                         <tr>
                        <th bgcolor="#F9E79F">CUOTA MAXIMA:</th>
                        <td bgcolor="#F9E79F" id="cuota_maxima" align="right" style="padding-right: 35px;"></td>
                        </tr>
                         <tr >
                        <th>CUOTA PACTADA:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="cuota_pactada" name="cuota_pactada" onkeyup="SumaIngresos()"></td>
                        </tr>
                        </table>
                 	</div>
             	</div>
             	<div class="col-xs-12 col-md-6 col-lg-6 ">
            		<div class="form-group">
                		<div id="credito_aprobado" class="small-box bg-green">
                            <div class="inner">
                              <h3 id="h3_credito_aprobado">CREDITO ACEPTADO</h3>
                            </div>
                          </div>
                          <div id="variacion_rol" class="small-box bg-green">
                            <div class="inner">
                              <h3 id="h3-variacion_rol"></h3>
                				<h4>VARIACION EN ROL CON NUEVA CUOTA</h4>
                				<h4 id="h3-variacion_rol_estado"></h4>
                              
                            </div>
                            
                          </div>
                          <div id="validacion_rol" class="small-box bg-green">
                            <div class="inner">
                              <h3 id="h3-validacion_rol"></h3>
                              <h4>VALIDACION ROL $150</h4>
                				<h4 id="h3-validacion_rol_estado"></h4>
                            </div>
                            
                          </div>
                          <div id="considerado_ingresos" class="small-box bg-green">
                            <div class="inner">
                              <h3 id="h3-consideracion_rol"></h3>
                				<h4>CONSIDERANDO INGRESOS ADICIONALES >150</h4>
                				<h4 id="h3-consideracion_rol_estado"></h4>
                            </div>
                            
                          </div>
            		</div>
            	</div>
             	
           	</div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

 <!-- Modal Simulacion Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalSimulacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Insertar Crédito</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div id="info_solicitud"></div>
				 <div id="info_participe"></div>				 
          	 <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="tipo_credito" class="control-label">Tipo Crédito:</label>
                    	<select name="tipo_credito" id="tipo_credito"  class="form-control"onchange="TipoCredito()">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <option value=9 >ORDINARIO</option>
                                      <option value=12>EMERGENTE</option>
    					</select> 
                        <div id="mensaje_tipo_credito" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="monto_credito" class="control-label">Monto Crédito:</label>
              			<input type=number step=10 class="form-control" id="monto_credito" name="monto_credito"">
                        <div id="mensaje_monto_credito" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="fecha_permiso" class="control-label">Fecha de Pago:</label>
                    	<input type="date"  class="form-control" id="fecha_corte" name="fecha_corte" placeholder="Fecha">
                        <div id="mensaje_fecha" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
            			<div id="select_cuotas"></div>
                 	</div>
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
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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


  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
   <script src="view/Credito/js/BuscarParticipes.js?0.11"></script> 
   </body>
</html>   