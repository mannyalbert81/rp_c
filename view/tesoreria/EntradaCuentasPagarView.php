    <!DOCTYPE HTML>
	<html lang="es">
    <head>
    
    <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
    <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    
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
 	</style> 	
 	
    <?php include("view/modulos/links_css.php"); ?>	
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   	
	
		    
	</head>
 
    <body class="hold-transition skin-blue fixed sidebar-mini" onbeforeunload="">
    
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Tesoreria</a></li>
        <li class="active">Ingreso Cuentas Pagar</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Cuentas por Pagar</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
        
        <form id="frm_cuentas_pagar" action="<?php echo $helper->url("CuentasPagar","CuentasPagarIndex"); ?>" method="post" enctype="multipart/form-data"  class="form form-horizontal col-lg-12 col-md-12 col-xs-12">
        	
        	<!-- para efecto de pantalla cargando -->
        	<div id="divLoaderPage" ></div>
        	
        	<div class="row">
        	
        		<div class="col-lg-6 col-md-6 col-xs-12">
        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="nombre_lote" class="col-sm-4 control-label" > Id. lote:</label>
            				<div class="col-sm-8">
            				  <div class="input-group ">
                              <input type="text" class="form-control" id="nombre_lote" name="nombre_lote"  autocomplete="off" value="" autofocus>
                              <span class="input-group-btn">
                                <button class="btn btn-default input-sm" type="button" data-toggle="modal" data-target="#mod_lote">
                                <i class="fa fa-arrow-right"></i>
                                </button>
                              </span>
                              <div id="mensaje_id_lote" class="errores"></div>
                            </div>
                            <input type="hidden" id="id_lote" name="id_lote" value="0">
            				</div>
            			 </div>        			 
        			</div>
                 
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="id_tipo_activos_fijos" class="col-sm-4 control-label" > Fecha Doc:</label>
            				<div class="col-sm-8">
            				  <input type="date" class="form-control" id="fecha_cuentas_pagar" name="fecha_cuentas_pagar" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>" >
            				  <div id="mensaje_fecha_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        		</div>
                        	
        		<div class="col-lg-6 col-md-6 col-xs-12">
        			
        			<div class="form-group ">        			
            			<div class="form-group-sm">
            				<label for="num_comprobante" class="col-sm-4 control-label" >Numero Documento:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="num_comprobante" name="num_comprobante" value="" readonly>
            				  <div id="mensaje_num_comprobante" class="errores"></div>
            				  <input type="hidden" name="id_consecutivo" id="id_consecutivo" value="0">
    				  	      <input type="hidden" name="id_cuentas_pagar" id="id_cuentas_pagar" value="0">
            				</div>
            			 </div>
            		 </div>
            		 
            		<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="id_tipo_documento" class="col-sm-4 control-label" > Tipo de Documento:</label>
            				<div class="col-sm-8">
            				  <select id="id_tipo_documento" name="id_tipo_documento" class="form-control">
                              	<option value="0">--SELECCIONE--</option>
                              </select>
            				  <div id="mensaje_id_tipo_documento" class="errores"></div>
            				</div>
            			 </div>
        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="descripcion_cuentas_pagar" class="col-sm-4 control-label" > Descripcion:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="descripcion_cuentas_pagar" name="descripcion_cuentas_pagar" value="" placeholder="Descripcion" autocomplete="off" required>
            				  <div id="mensaje_descripcion_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        		
        		</div>
        		
        		
            </div>
               
               <hr>
               
               <div class="row">
        	
        		<div class="col-lg-6 col-md-6 col-xs-12">
        		
        		    <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="cedula_proveedor" class="col-sm-4 control-label" > CI/RUC Proveedor:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="cedula_proveedor" name="cedula_proveedor" value="" >
			          		  <div id="mensaje_cedula_proveedor" class="errores"></div>
			          		  <input type="hidden" name="id_proveedor" id="id_proveedor" value="">
            				</div>
            			 </div>        			 
        			</div>
        			
        			 <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="nombre_proveedor" class="col-sm-4 control-label" > Titular Proveedor:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value="" readonly>
				  			  <div id="mensaje_nombre_proveedor" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			 <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="cedula_proveedor" class="col-sm-4 control-label" > Email Proveedor:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="email_proveedor" name="email_proveedor" value=""  placeholder="" readonly>
			          		  <div id="mensaje_email_proveedor" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="condiciones_pago_cuentas_pagar" class="col-sm-4 control-label" > Condiciones Pago:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control" id="condiciones_pago_cuentas_pagar" name="condiciones_pago_cuentas_pagar" value="" autocomplete="off"  placeholder="">
			          		  <div id="mensaje_condiciones_pago_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="id_bancos" class="col-sm-4 control-label" > Banco:</label>
            				<div class="col-sm-8">
            				  <select id="id_bancos" name="id_bancos" class="form-control">
                              	<option value="0">--SELECCIONE--</option>
                              </select>
                              <div id="mensaje_id_bancos" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>        			
        			
    		    </div>
        		
        		<div class="col-lg-6 col-md-6 col-xs-12">
    		        
        		        <div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_moneda" class="col-sm-4 control-label" > Moneda:</label>
                				<div class="col-sm-8">
                				  <select id="id_moneda" name="id_moneda" class="form-control">
                                  	<option value="0">--SELECCIONE--</option>
                                  </select>
                                  <div id="mensaje_id_moneda" class="errores"></div>
                				</div>
                			 </div>        			 
            			</div>
            			
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="numero_documento" class="col-sm-4 control-label" > Numero Documento:</label>
                				<div class="col-sm-8">
                				 <input type="text" class="form-control " id="numero_documento" name="numero_documento">                      
                      		     <div id="mensaje_numero_documento" class="errores"></div>
                				</div>
                			 </div>        			 
            			</div>
            			
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="numero_ord_compra" class="col-sm-4 control-label" > Numero Orden Compra:</label>
                				<div class="col-sm-8">
                				 <input type="text" class="form-control " id="numero_ord_compra" name="numero_ord_compra" autocomplete="off">                      
                      			 <div id="mensaje_numero_ord_compra" class="errores"></div>
                				</div>
                			 </div>        			 
            			</div>
            			
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="metodo_envio_cuentas_pagar" class="col-sm-4 control-label" > Metodo Envio:</label>
                				<div class="col-sm-8">
                				 <input type="text" class="form-control " id="metodo_envio_cuentas_pagar" name="metodo_envio_cuentas_pagar" autocomplete="off">                      
                      			 <div id="mensaje_metodo_envio_cuentas_pagar" class="errores"></div>
                				</div>
                			 </div>        			 
            			</div>
            			
            			           			
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_impuestos" class="col-sm-4 control-label" > plan Impuestos:</label>
                				<div class="col-sm-8">
                				 <div class="input-group ">
                                  <input type="text" class="form-control" id="plan_impuesto" readonly>
                                  <span class="input-group-btn">
                                    <button class="btn btn-default input-sm" type="button" data-toggle="modal" data-target="#mod_impuestos" >
                                    <i class="fa fa-arrow-right"></i> 
                                    </button>
                                  </span>
                                  <div id="mensaje_de_prueba" class="errores"></div>
                                </div>
                				</div>
                			 </div>        			 
            			</div>
            			            			    		        
    		      </div>
               
               </div>
    		    
    		       		
    		    <hr>
    		    
    		    <div class="row">
    		        <div class="col-lg-6 col-md-6 col-xs-12">
    		        
    		         <!--  <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="monto_cuentas_pagar" class="col-sm-4 control-label" > Compras:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="monto_cuentas_pagar" name="monto_cuentas_pagar" value=""  placeholder="" >
			          		  <div id="mensaje_numero_movimiento" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			-->
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="monto_cuentas_pagar" class="col-sm-4 control-label" > Compras:</label>
            				<div class="col-sm-8">
            				  <div class="input-group ">
                              <input type="text" class="form-control inputDecimal" id="monto_cuentas_pagar" name="monto_cuentas_pagar" value="">
                              <span class="input-group-btn">
                                <button class="btn btn-danger input-sm" type="button"  id="btn_cambiar_compras">
                                <i class="fa fa-times"></i>
                                </button>
                              </span>                              
                            </div>                            
            				</div>
            			 </div>        			 
        			</div>
        			
        			 <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="desc_comercial_cuentas_pagar" class="col-sm-4 control-label" > Desc Comercial:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="desc_comercial_cuentas_pagar" name="desc_comercial_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_desc_comercial_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="flete_cuentas_pagar" class="col-sm-4 control-label" > Flete:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="flete_cuentas_pagar" name="flete_cuentas_pagar" value=""  placeholder="" >
                              <div id="mensaje_flete_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="miscelaneos_cuentas_pagar" class="col-sm-4 control-label" > Miscelaneos:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="miscelaneos_cuentas_pagar" name="miscelaneos_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_miscelaneos_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="impuesto_cuentas_pagar" class="col-sm-4 control-label" > Impuesto:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="impuesto_cuentas_pagar" name="impuesto_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_impuesto_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="total_cuentas_pagar" class="col-sm-4 control-label" > Total:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="total_cuentas_pagar" name="total_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_total_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			</div>  
    		        
    		       <div class="col-lg-6 col-md-6 col-xs-12">
    		        
    		         <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="monto1099_cuentas_pagar" class="col-sm-4 control-label" > Monto 1099:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="monto1099_cuentas_pagar" name="monto1099_cuentas_pagar" value=""  placeholder="" >
			          		  <div id="mensaje_monto1099_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			 <div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="efectivo_cuentas_pagar" class="col-sm-4 control-label" > Efectivo:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="efectivo_cuentas_pagar" name="efectivo_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_efectivo_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="cheque_cuentas_pagar" class="col-sm-4 control-label" > Cheque:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="cheque_cuentas_pagar" name="cheque_cuentas_pagar" value=""  placeholder="" >
                              <div id="mensaje_cheque_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="tarjeta_credito_cuentas_pagar" class="col-sm-4 control-label" > Tarjeta Credito:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="tarjeta_credito_cuentas_pagar" name="tarjeta_credito_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_tarjeta_credito_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="condonaciones_cuentas_pagar" class="col-sm-4 control-label" > Cond. dsctos. Tomados:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="condonaciones_cuentas_pagar" name="condonaciones_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_condonaciones_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="saldo_cuentas_pagar" class="col-sm-4 control-label" > Saldo Cuenta:</label>
            				<div class="col-sm-8">
            				  <input type="text" class="form-control inputDecimal" id="saldo_cuentas_pagar" name="saldo_cuentas_pagar" value=""  placeholder="" >
                          <div id="mensaje_saldo_cuentas_pagar" class="errores"></div>
            				</div>
            			 </div>        			 
        			</div>
        			
        			</div>  
    		   
    		    </div>
    		    
    		    
             
		    <div class="row">
		    	<div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
		    		<div class="form-group">
              			<button type="submit" id="aplicar" name="aplicar" class="btn btn-default"><i class="fa " aria-hidden="true"></i>Aplicar</button>
              			<button type="button" id="btn_distribucion" name="btn_distribucion"  class="btn btn-default" data-toggle="modal" data-target="#mod_distribucion"><i class="fa " aria-hidden="true" ></i>  Distribucion</button>
              			<button type="button" id="btn_cancelar" name="btn_cancelar"  class="btn btn-default" ><i class="fa " aria-hidden="true" ></i> Cancelar</button>
            		</div>
		    	</div>
		    	
		    	
		    </div>
                    		      
                    		  
        </form>
          
        </div>
        
        
      </div>
    </section>
    
    <!--  <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Cuentas por Pagar</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
            	<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_activos_fijos(1)" placeholder="search.."/>
						
				</div>
				
				<div id="load_activos_fijos" ></div>
				<div id="activos_fijos_registrados"></div>	
            
          
            </div>
        </div>
	</section> -->
            
    
  </div>
 
 <?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- PARA MODALES -->
 
  <div class="modal fade" id="mod_lote" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Entrada de Lotes Ctas. Pagar</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_genera_lote" name="frm_genera_lote">
          	
          	<div class="form-group">
				<label for="mod_nombre_lote" class="col-sm-3 control-label">Lote:</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_nombre_lote" name="mod_nombre_lote"  readonly>
				</div>
			  </div>
			  
			<div class="form-group">
				<label for="mod_descripcion_lote" class="col-sm-3 control-label">Descripcion:</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_descripcion_lote" name="mod_descripcion_lote"  required>
				</div>
			  </div>
			  			  
          	<div class="form-group">
				<label for="mod_id_frecuencia" class="col-sm-3 control-label">Frecuencia:</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_id_frecuencia" name="mod_id_frecuencia" required>
					<option value="1">Uso Unico</option>					
				  </select>
				</div>
			  </div>
			  
			  <div id="msg_frm_lote" class=""></div>
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" form="frm_genera_lote" class="btn btn-primary" id="guardar_datos_lote">Genera Lote</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
  
 
 <div class="modal fade" id="mod_impuestos" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Entrada Impuestos por Pagar</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form form-horizontal" method="post" id="frm_impuestos" name="frm_impuestos">	
          	
          	<div class="form-group">
			  <div class="form-group-sm">
				<label for="codigo" class="col-sm-3 control-label ">Tipo Documento: </label>
				<div class="col-sm-8">
				  <input type="text" class="form-control " id="mod_tipo_documento" name="mod_tipo_documento" readonly>
				</div>
			  </div>
			  
			  <div class="form-group-sm">
				<label for="nombre" class="col-sm-3 control-label ">Numero Documento:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control " id="mod_numero_documento" name="mod_numero_documento" readonly>
				</div>
			  </div>
			  
			  <div class="form-group-sm">
				<label for="nombre" class="col-sm-3 control-label">Monto: </label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_monto_documento" name="mod_monto_documento" readonly>
					<div id="mensaje_mod_monto_documento" class="errores"></div>
				</div>
			  </div>
			  </div><!-- terminacion de div de grupo -->
			  
			  
			   <div class="form-group">
				<label for="mod_id_impuestos" class="col-sm-3 control-label input-sm">Selecione Impuesto:</label>
				<div class="col-sm-8">
					<div class="input-group ">
                     <select class="form-control" id="mod_id_impuestos" name="mod_id_impuestos" required>
    					<option value="0">-- Selecciona estado --</option>					
    				  </select>
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btn_mod_agrega_impuestos">
                        <i class="fa fa-plus" aria-hidden="true"></i> 
                        </button>
                      </span>                      
                    </div>				 
				</div>
			  </div>
			  
			   <div id="msg_frm_impuestos" ></div>
			   <div class="pull-right" style="margin-right:15px;">
			   	<button type="button" id="btn_mostrar_lista_impuestos" class="btn btn-default"><span>Ver Listado</span> <i class="fa fa-search-plus" aria-hidden="true"></i></button>
				</div>
			  	<div class="box-body">
            
    				<div id="impuestos_cuentas_pagar" ></div>            
          
            	</div>
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="mod_distribucion" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:90%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Distribucion Pagos</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_distribucion" name="frm_distribucion">
          	
          	<div class="row">
          		<div class="col-xs-12 col-md-6 col-md-6 ">
          			<div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Proveedor: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_codigo_proveedor" name="mod_codigo_proveedor" readonly>
        				</div>
        			 </div>
        			 <div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Nombre Proveedor: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_nombre_proveedor" name="mod_nombre_proveedor" readonly>
        				</div>
        			 </div>
        			 <div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Moneda: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_nombre_moneda" name="mod_nombre_moneda" readonly>
        				</div>
        			 </div>
          		</div>
          		
          		<div class="col-xs-12 col-md-6 col-md-6 ">
          			<div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Numero Comp: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_num_comprobante" name="mod_num_comprobante" readonly>
        				</div>
        			 </div>
        			 
        			 <div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Tipo Documento: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_dis_tipo_documento" name="mod_dis_tipo_documento" readonly>
        				</div>
        			 </div>
        			 
        			 <div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Monto : </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_monto_compra" name="mod_monto_compra" readonly>
        				</div>
        			 </div>
        			 
        			 <div class="form-group-sm">
        				<label for="codigo" class="col-sm-4 field-sm control-label ">Monto Original: </label>
        				<div class="col-sm-8">
        				  <input type="text" class="form-control " id="mod_monto_compra_original" name="mod_monto_compra_original" readonly>
        				</div>
        			 </div>
          		</div>
          		
          	</div>
          	
          	<div class="row">
          		<div id="msg_frm_distribucion" ></div>
          	</div>
          	
          	<hr>
          	
		  	<div class="box-body">
        
				<div id="distribucion_cuentas_pagar" ></div>            
      
        	</div>
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" id="btn_distribucion_aceptar" class="btn btn-default" >Aceptar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
<?php include("view/modulos/links_js.php"); ?>
<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="view/tesoreria/js/CuentasPagar.js?1.09"></script>

    

</body>
</html>   

 