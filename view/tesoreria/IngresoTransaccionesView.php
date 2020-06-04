<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <?php include("view/modulos/links_css.php"); ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
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
        
      .form-control {
            border-radius: 5px !important;
        }
       
      .tableFixHead { overflow-y: auto; height: 100px; }
      .tableFixHead thead th { position: sticky; top: 0; }
        
        /* Just common table stuff. Really. */
        table  { border-collapse: collapse; width: 100%; }
        th, td { padding: 5px 8px; }
        th     { background:#eee; }
          
 	</style>
   
  			        
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
        <li class="active">Transacciones</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Transacciones Cuentas Pagar</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div> 
                  
  		<div class="box-body">
  		
  		<!-- SECTION PARA ID -->
  		<input type="hidden" id="id_lote" value="">
  		<input type="hidden" id="id_proveedores" value="">
  		<input type="hidden" id="id_consecutivos" value="">
  		<input type="hidden" id="hd_valor_base_compra" value=""><!-- aqui guarda el valor pa validar cambio de valor -->
  		<!-- TERMINA SECTION PARA ID -->
  		
  			<div id="divLoaderPage" ></div>                     	
  		
  			<div class="row">
  			
  				<div class="col-xs-12 col-md-12 col-lg-12">
  				
  					<div class="pull-right">
  						
  						<ul class="nav nav-pills">
  						
  						  <li>
  						  <p id="txtInicio" class="text-justify text-danger mark "> GENERE UN IDENTIFICADOR PARA EMPEZAR</p>
  						  </li>	
  						  <li>
  						  	<button type="button" id="btnLote"  class="btn btn-default" data-toggle="popover"  data-placement="right" data-html='true' data-popover-content="">
  						  	Generar Identificador
  						  	<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i> 
  						  	</button>
                          </li>                          
                          <li>
                          <button id="mdlImpuestosRelacionados" type="button" onclick="verTablaImpuestosRelacionados()" class="btn btn-default">Ver Impuestos <span id="cantidad_impuestos_ins" class="badge label-danger"></span></button>
                          </li>
                          <li>
                          	<!-- en este botn se genera el  -->
                          	<a class="btn btn-default" href="#" title="Cambiar Base Retencion" id="lnk_cambiar_base" onclick="modificar_base_retencion(event)" > Modificar Retencion
                          	<i class="fa fa-edit" aria-hidden="true"></i></a> 
                          </li>
                          <li>
                          	<a class="btn btn-default" href="index.php?controller=TesProveedores&action=index" target="_blank" title="Nuevo Proveedor" >
                          	<i class="fa fa-id-card-o" aria-hidden="true"></i></a> 
                          </li>
                          <li>
                          	<a class="btn btn-default" href="index.php?controller=Impuestos&action=index" target="_blank" title="Nuevo Impuesto" >
                          	<i class="fa fa-usd" aria-hidden="true"></i></a>                          	
                          </li>
                          <!-- <li>
                            <button class="btn" id="btnPopTet"><i></i> Add Proveedor</button>
                          </li>
                          <li>
                          <button class="btn">Right</button>
                          </li>-->
                        </ul>                          						
  						
  					</div>
  					
  				</div>
  			</div>
  			
  			<br>
  			
  			
  			<div class="row">
  			
                 <div class="col-xs-12 col-lg-3 col-md-3 ">
    			   <label for="secuencial_documento" class="control-label">Secuencial Documento:</label>
    			   <input type="text" class="form-control" id="secuencial_documento" name="secuencial_documento" value="" readonly >
                 </div>
                 
                 <div class="col-xs-12 col-lg-3 col-md-3 ">
    			   <label for="fecha_transaccion" class="control-label">Fecha:</label>
    			   <input type="text" class="form-control" id="fecha_transaccion" name="fecha_transaccion" value="<?php echo date('Y-m-d'); ?>"  >
                 </div>
                 
                 <div class="col-xs-12 col-lg-3 col-md-3 ">
    			   <label for="tipo_documento" class="control-label">Tipo Comprobante:</label>
    			   <select id="tipo_documento" class="form-control" ><option value="0">--Seleccione--</option></select>
                 </div>
                 
                 <div class="col-xs-12 col-lg-3 col-md-3 ">
    			   <label for="descripcion_transaccion" class="control-label">Descripcion:</label>
    			   <input type="text" class="form-control" id="descripcion_transaccion" name="descripcion_transaccion" value="" >
                 </div>
             </div>
                 
             <div class="row">   
  				  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">
      					<label for="identificacion_proveedores" class="control-label">Proveedor:</label>
      					 <div class="input-group">  					 	
                          <input type="text" class="form-control" id="identificacion_proveedores" readonly>
                          <span class="input-group-btn">
                            <button class="btn btn-default" onclick="loadProveedores()" type="button" data-toggle="modal" data-target="#mod_proveedores">
                            <i class="fa  fa-binoculars" aria-hidden="true"></i></button>
                          </span>
                        </div>
  					</div>    									 
  				</div>
  				  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="nombre_proveedores" class="control-label">Nombre Proveedor:</label>
                      <input type="text" class="form-control" id="nombre_proveedores" readonly>                      
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="referencia_documento" class="control-label">Ref. Documento:</label>
                      <input type="text" id="referencia_documento" class="form-control">                       
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="numero_autorizacion" class="control-label">Num. Autorizacion :</label>
                      <input type="text" id="numero_autorizacion" class="form-control" value="">
                    </div>	
  				</div>
  				
  			</div>
  			
  			<div class="row ">
  				<div class="col-xs-12 col-md-3 col-md-3"><h4>Valores Documento:</h4></div>
  			</div>
  			<div class="row ">
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="valor_compra_cero" class="control-label"> Sub-Total 0% (Compras):</label>
                      <input type="text" id="valor_compra_cero" onkeyup="pasarTotal()" class="form-control" value="">
                    </div>	
  				</div>
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="valor_compra_iva" class="control-label"> Sub-Total 12% (Compras):</label>
                      <input type="text" id="valor_compra_iva" onkeyup="pasarTotal()" class="form-control" value="">
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">
      					<label for="impuestos_documento" class="control-label">Impuestos:</label>
      					 <div class="input-group">  					 	
                          <input type="text" class="form-control" id="impuestos_documento" readonly>
                          <span class="input-group-btn">
                            <button class="btn btn-default" onclick="verTablaImpuestos()" type="button" >
                            <i class="fa   fa-list-ul" aria-hidden="true"></i></button>
                          </span>
                        </div>
  					</div>    									 
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="valor_total_documento" class="control-label"> Valor Total:</label>
                      <input type="text" id="valor_total_documento" class="form-control" value="">
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group"> 
      					<div class="checkbox">
                         <label>
                         	<input type="checkbox" value="0" id="compra_materiales"> Compra Materiales
                         </label>
                        </div>                       
                    </div>	
  				</div>
  				
  			</div>
  			
  			<div class="row">
  				 <div class="col-xs-12 col-md-12 col-lg-12 " >
  				 	<div id="pnl_datos_retencion"></div>
  				 	<br>
  				 </div>
  			</div>
  			
  			<div class="row">
  				 <div class="col-xs-12 col-md-12 col-lg-12 " >
  				 	<div class="pull-right">
  				 		<button  id="DistribucionTransaccion" onclick="verDistribucion()" class="btn btn-success">DISTRIBUCION</button>
  				 		<button  id="AplicarTransaccion" onclick="IngresarTransaccion()" class="btn btn-success">APLICAR</button>
	                    <a href="<?php echo $helper->url("TesCuentasPagar","Index"); ?>" class="btn btn-danger">CANCELAR</a>
  				 	</div>
  				 </div>
  			</div>
  			<div class="clearfix"></div>
  			  			
  			<br>
  			
			<!-- <div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Detalles de Factura</h3>
					<div class="pull-right">
						<span class="clickable filter" data-toggle="tooltip" title="filter" data-container="body">
							<i class="fa fa-filter" aria-hidden="true"></i>
						</span>
					</div>
				</div>
				<div class="panel-body">
					
				</div>
				
			</div>
			 -->
  			
  
          </div>
    	</div>
    </section>
              
     <section class="content">
      	<div class="box box-primary">
      		<div class="box-header with-border">
      			<h3 class="box-title"></h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaBancos(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="bancos_registrados" ></div>
            </div> 	
      	</div>
      </section> 
    
  </div>
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
  
 <!-- COMIENZA MODALS -->
 
 <!-- BEGIN MODAL Proveedores -->
  <div class="modal fade" id="mod_proveedores" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">Lista de Proveedores</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
            	<div id="mod_div_proveedores" class="table-responsive" style="min-height: 150px; max-height: 450px">
            		<div class="pull-right">
            			<input type="text" id="mod_buscador_proveedores" onkeyup="loadProveedores()" class="form-control">
            		</div>
            		<div class="clearfix"></div>
            		<div class="pull-left">            			
            			<p>Total Registros <span id="mod_total_proveedores" class="badge badge-info"></span></p>
            		</div>
            		<div class="clearfix"></div>
            		<table id="mod_tbl_proveedores" class="table  table-fixed table-sm table-responsive-sm" > <!--   -->
                    	<thead >                    		
                    	    <tr class="table-secondary" >
                    			<th style="text-align: left;  font-size: 10px;">#</th>
                    			<th style="text-align: left;  font-size: 10px;">RUC/CI</th>
                    			<th style="text-align: left;  font-size: 10px;">Razon Social</th>
                    			<th style="text-align: left;  font-size: 10px;">Nombres</th>
                    			<th style="text-align: left;  font-size: 10px;">Dirección</th>
                    			<th style="text-align: left;  font-size: 10px;">Celular</th>
                    			<th style="text-align: left;  font-size: 10px;">..</th>
                    		</tr>
                    	</thead>        
                    	<tbody>
                    	    
                    	</tbody>
                    	<tfoot>
                    	    <tr>
                    			<th colspan="7" ></th>
                    	    </tr>
                    	</tfoot>
                    </table>  
                    <div id="mod_paginacion_proveedores"></div>
                    <div class="clearfix"></div>          	
            	</div>
          	</div>        	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL PROVEEDORES -->

 <!-- BEGIN MODAL IMPUESTOS -->
  <div class="modal fade" id="mod_impuestos" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">IMPUESTOS</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
            	<div id="mod_div_impuestos" >
            		<div class="pull-right">
            			<input type="text" id="mod_buscador_impuestos" onkeyup="loadImpuestos()" class="form-control">
            		</div>
            		<div class="clearfix"></div>
            		<div class="pull-left">
            			<p>Valor Base Compra:&nbsp;<span class="text-warning" id="mod_valor_base_documento" ></span></p>
            			<p>Total Registros <span id="mod_total_impuestos" class="badge bg-info"></span></p>
            		</div>
            		<div class="clearfix"></div>
            		<div id="" class="tableFixHead" style="min-height: 250px; max-height: 450px">
                		<table id="mod_tbl_impuestos" class="table" > <!--   -->
                        	<thead >                        		
                        	    <tr class="table-secondary" >
                        			<th style="text-align: left;  font-size: 12px;">#</th>
                        			<th style="text-align: left;  font-size: 12px;">TIPO</th>
                        			<th style="text-align: left;  font-size: 12px;">IMPUESTO</th>
                        			<th style="text-align: left;  font-size: 12px;">CUENTA</th>
                        			<th style="text-align: left;  font-size: 12px;">NOMBRE</th>
                        			<th style="text-align: left;  font-size: 12px;">..</th>
                        		</tr>
                        	</thead>        
                        	<tbody>
                        	    
                        	</tbody>
                        	<tfoot>
                        	    <tr>
                        			<th colspan="6" ></th>
                        	    </tr>
                        	</tfoot>
                        </table>  
                    </div>
                    <div id="mod_paginacion_impuestos"></div>
                    <div class="clearfix"></div>          	
            	</div>
          	</div>        	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL IMPUESTOS -->

<!-- BEGIN MODAL IMPUESTOS RELACIONADOS -->
  <div class="modal fade" id="mod_impuestos_relacionados" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">IMPUESTOS RELACIONADOS</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
            	<div id="mod_div_impuestos" >
            		<div class="pull-right">
            			<input type="text" id="mod_buscador_impuestos_relacionados" onkeyup="" class="form-control">
            		</div>
            		<div class="clearfix"></div>
            		<div class="pull-left">
            			<p>Total Registros <span id="mod_total_impuestos_relacionados" class="badge bg-info"></span></p>
            		</div>
            		<div class="clearfix"></div>
            		<div id="" class="tableFixHead" style="min-height: 250px; max-height: 450px">
                		<table id="mod_tbl_impuestos_relacionados" class="table" > <!--   -->
                        	<thead >                        		
                        	    <tr class="table-secondary" >
                        			<th style="text-align: left;  font-size: 12px;">#</th>
                        			<th style="text-align: left;  font-size: 12px;">Tipo Imp.</th>
                        			<th style="text-align: left;  font-size: 12px;">Nombre</th>
                        			<th style="text-align: left;  font-size: 12px;">Cuenta</th>
                        			<th style="text-align: left;  font-size: 12px;">Valor Compra</th>
                        			<th style="text-align: left;  font-size: 12px;">Base Imp.</th>
                        			<th style="text-align: left;  font-size: 12px;">Valor Imp.</th>
                        			<th style="text-align: left;  font-size: 1px;">..</th>
                        		</tr>
                        	</thead>        
                        	<tbody>
                        	    
                        	</tbody>
                        	<tfoot>
                        	    <tr >
                        	    <th colspan="8"></th>
                        	    </tr>
                        	</tfoot>
                        </table>  
                    </div>
                    <div id="mod_paginacion_impuestos_relacionados"></div>
                    <div class="clearfix"></div>          	
            	</div>
          	</div>        	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL IMPUESTOS RELACIONADOS -->

<!-- BEGIN MODAL IMPUESTOS RELACIONADOS -->
  <div class="modal fade" id="mod_distribucion" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DISTRIBUCION CUENTAS</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
          		<div class="row">
          			<div class="col-lg-3">
          			<p>Proveedor: <span id="mod_distribucion_proveedor" class="badge bg-info"></span></p>
          			</div>
          			<div class="col-lg-3">
          			<p>Tipo Documento: <span id="mod_tipo_documento_distribucion" class="badge bg-info"></span></p>
          			</div>
          			<div class="col-lg-3">
          			<p>Monto: <span id="mod_monto_distribucion" class="badge bg-info"></span></p>
          			</div>
          			
          		</div>
          		
            	<div id="mod_div_distribucion" >
            		<div class="pull-left">
            			<p>Total Registros: <span id="mod_total_distribucion" class="badge bg-info"></span></p>
            		</div>
            		
            		<div class="pull-right">
            			<button onclick="eliminar_distribucion()" class="btn btn-default btn-sm">
            			<span class=" text-danger fa-stack" >
                          <i class="fa fa-circle-thin fa-stack-2x"></i>
                          <i class="fa fa-close fa-stack-1x"></i>
                        </span>
                        </button>
            			<button onclick="AceptarDistribucion()" class="btn btn-success">ACEPTAR</button>
            		</div>
            		<div class="clearfix"></div>
            		<br>
            		<div id="" class="tableFixHead" style="min-height: 250px; max-height: 450px">
                		<table id="mod_tbl_distribucion" class="table" > <!--   -->
                        	<thead >                        		
                        	    <tr class="table-secondary" >
                        			<th style="text-align: left;  font-size: 13px;">#</th>
                                    <th style="text-align: left;  font-size: 13px;">Referencia</th>
                                    <th style="text-align: left;  font-size: 13px;">Codigo Cuenta</th>
                                    <th style="text-align: left;  font-size: 13px;">Nombre</th>
                                    <th style="text-align: left;  font-size: 13px;">Tipo </th>
                                    <th style="text-align: left;  font-size: 13px;">debito</th>
                                    <th style="text-align: left;  font-size: 13px;">credito</th>
                        		</tr>
                        	</thead>        
                        	<tbody>
                        	    
                        	</tbody>
                        	<tfoot>
                        	    
                        	</tfoot>
                        </table>  
                    </div>
                    <div id="mod_paginacion_distribucion"></div>
                    <div class="clearfix"></div> 
                    <br>         	
            	</div>
          	</div>        	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL IMPUESTOS RELACIONADOS -->
 
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
   <script src="view/tesoreria/js/IngresoTransaciones.js?0.42"></script> 
       
       

 	
	
	
  </body>
</html>   

