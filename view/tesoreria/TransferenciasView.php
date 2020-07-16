<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
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
        <li class="active">Transferencias</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Transferencias</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">
  		
  		<div id="divLoaderPage" ></div> 

			<form id="frm_genera_cheque" action="<?php echo $helper->url("GenerarCheque","IndexCheque"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             	
             	<div class="panel panel-warning">
                  <div class="panel-heading"> DATOS ENTIDAD</div>                      
                 </div>
             		    
		    	 <div class="row">
		    	 
		    	    <div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="numero_pago" class=" control-label" >Numero Pago:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="numero_pago" name="numero_pago" value="" >
                    		</div>        			 
            			</div>
            		</div>
		    	 
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="fecha_transferencia" class=" control-label" >Fecha Transaccion:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="fecha_transferencia" name="fecha_transferencia" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>" >
                    		</div>        			 
            			</div>
            		</div> 
		    	
		    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
		    	 		<div class="form-group ">                 			 
            				<label for="nombre_lote" class="control-label" > Identificador Lote:</label>
            				<div class="form-group-sm">                				
                              <input type="text" class="form-control" id="nombre_lote" name="nombre_lote"  autocomplete="off" value="<?php echo $resultset[0]->nombre_lote; ?>" autofocus>  
                              <input type="hidden" id="id_lote" name="id_lote" value="<?php echo $resultset[0]->id_lote; ?>">
                              <input type="hidden" id="id_cuentas_pagar" name="id_cuentas_pagar" value="<?php echo $resultset[0]->id_cuentas_pagar; ?>" >
            				</div>
                					
            			</div>		    	 	
		    	 	</div>
		    	 	
            			<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="total_lote" class=" control-label" >Total Pago:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="total_cuentas_pagar" name="total_cuentas_pagar"  value="<?php echo $resultset[0]->total_cuentas_pagar; ?>" > 
                    		</div>        			 
            			</div>
            		</div> 
            		            		
            		<div class="col-xs-12 col-md-12 col-lg-12">
            			<div class="form-group ">
            				<label for="identificacion_proveedor" class=" control-label" >Descripción:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="descripcion_pago" name="descripcion_pago" value="<?php echo $resultset[0]->descripcion; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            	
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="total_lote" class=" control-label" >Bancos Local:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="id_bancos_local">
                    				<option value="0">--Seleccione--</option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="id_tipo_archivo_pago" class=" control-label" >Tipo Archivo:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="id_tipo_archivo_pago">
                    				<option value="0">--Seleccione--</option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div> 
            		
            	</div>
            	<?php if( isset($resultset[0]->iscredito) ) { ?>
            	<div class= "row">
            		<input type="hidden" id="id_creditos" value="<?php echo $resultset[0]->id_creditos;?>">
            		<div class="col-xs-12 col-md-12 col-lg-12">
            			<h4> Datos credito </h4>
            		</div>
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="numero_creditos" class=" control-label" >Num. Credito:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="numero_creditos" value="<?php echo $resultset[0]->numero_creditos; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="tipo_creditos" class=" control-label" >Tipo. Credito:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="tipo_creditos"  value="<?php echo $resultset[0]->nombre_tipo_creditos; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            	</div>
                <?php }?>
                
                <div class="panel panel-warning">
                  <div class="panel-heading"> BENEFICIARIO/A:   
                      	 <div class="pull-right ">
                            <span >
                                <a onclick="mostrar_datos_garantes(this)" id="" data-id_proveedores="<?php echo $resultset[0]->id_proveedores; ?>" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Editar Datos Garante"> <i class="fa  fa-edit fa-2x fa-fw" aria-hidden="true" ></i>
	                            <!-- <a onclick="mostrar_datos_garantes(this)" id="" data-id_proveedores="<?php echo $resultset[0]->id_proveedores; ?>" data-id_bancos="<?php echo $resultset[0]->id_bancos; ?>" data-id_tipo_cuentas="<?php echo $resultset[0]->id_tipo_cuentas; ?>" href="#" class=" no-padding btn btn-sm btn-default" data-toggle="tooltip" data-placement="right" title="Editar Datos Garante"> <i class="fa  fa-edit fa-2x fa-fw" aria-hidden="true" ></i>
	                            -->
	                           </a>
                            </span>
                            </div>	
                            </div>                                
                </div>
                
                           	
            	<div class="row">	
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="identificacion_proveedor" class=" control-label" >Identificacion:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="identificacion_proveedor" name="identificacion_proveedor" value="<?php echo $resultset[0]->identificacion_proveedores; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="nombre_proveedor" class=" control-label" >Nombre:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $resultset[0]->apellido_beneficiario.' - '. $resultset[0]->nombre_beneficiario;  ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="id_bancos_transferir" class=" control-label" >Transferir A:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="id_bancos_transferir" >
                    				<option value="<?php echo $resultset[0]->id_bancos; ?>"> <?php echo $resultset[0]->nombre_bancos; ?></option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div>             		
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="tipo_cuenta_banco" class=" control-label" >Tipo Cuenta:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="tipo_cuenta_banco" name="tipo_cuenta_banco" value=" <?php echo $resultset[0]->nombre_tipo_cuenta_banco; ?> " >
                				 <input type="hidden" id="id_tipo_cuentas" value="<?php echo $resultset[0]->id_tipo_cuentas; ?>">
                    		</div>        			 
            			</div>
            		</div>   
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="cuenta_banco" class=" control-label" >Cuenta:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="cuenta_banco" name="cuenta_banco" value=" <?php echo $resultset[0]->numero_cuenta_banco; ?> " >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group"> 
      					<div class="checkbox">
                         <label>
                         	<input type="checkbox" value="0" id="chk_pago_parcial_transferencias"> Pago Parcial
                         </label>
                        </div>                       
                    </div>	
  				   </div>
            	   <div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="valor_parcial_transferencias" class=" control-label" >Valor Partcial:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="valor_parcial_transferencias"  value=" <?php echo $resultset[0]->numero_cuenta_banco; ?> " >
                    		</div>        			 
            			</div>
            	   </div>             		   	
            		            						    
          	   	</div>
          	   	
          	   	<div class="row">
          	   		<div class="col-xs-12 col-md-4 col-lg-4" style="text-align: left;">
                    	<div class="form-group">
                    	  <button type="button" id="genera_transferencia" name="genera_transferencia" class="btn btn-success">
                          	<i class="glyphicon glyphicon-plus"></i> Aceptar
                          </button>
                          <button type="button" id="distribucion_transferencia" name="distribucion_transferencia" class="btn btn-success" >
                          	<i class="glyphicon glyphicon-plus"></i> Distribucion
                          </button>                         
                          <a href="<?php echo $helper->url("Pagos","Index"); ?>" class="btn btn-primary">
                          <i class="glyphicon glyphicon-remove"></i> Cancelar</a>
	                    </div>
        		    </div>          	   	
          	   	</div>	
 
           </form>
                      
          </div>
    	</div>
    </section>
    
  </div>
  
  <!-- Para modales -->
  <div class="modal fade" id="mod_distribucion_pago" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">Información</h4>
          </div>
          <div class="modal-body" >
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_distribucion_transferencia" name="frm_distribucion_transferencia">
          	
          	<div class="row">
          		
          		
          		<div class="col-xs-12 col-lg-3 col-md-3">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Identificación:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_identificacion_proveedor" name="mod_identificacion_proveedor" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Nombre:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_nombre_proveedor" name="mod_nombre_proveedor" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Monto:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_total_cuentas_pagar" name="mod_total_cuentas_pagar" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Transferir a:</label>
        				<div class="form-group-sm">  
        					<select class="form-control" id="mod_banco_transferir">
        					
        					</select> 
        				</div>
            						 
        			</div>		    	 	
	    	 	</div> 
	    	 	
	    	 	<div class="col-xs-12 col-lg-6 col-md-6 ">
	    	 		<div class="form-group ">                 			 
        				<label for="mod_descripcion_transferencia" class="control-label" >Descripcion:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_descripcion_transferencia" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div id="divmodcreditos">
	    	 		<div class="col-xs-12 col-lg-3 col-md-3 ">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Num. Credito:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_numero_creditos" name="" value="">
        				</div>
            						 
        			</div>		    	 	
    	    	 	</div> 
    	    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
    	    	 		<div class="form-group ">                 			 
            				<label for="nombre_lote" class="control-label" >Tipo Credito:</label>
            				<div class="form-group-sm">                				
                              <input type="text" style="height:30px"  class=" form-control" id="mod_tipo_creditos" name="" value="">
            				</div>
                						 
            			</div>		    	 	
    	    	 	</div> 
	    	 	</div>  
	    	 	    
          	</div>
          	          	
		  	<div class="box-body">        
				<div id="lista_distribucion_transferencia" ></div>
        	</div>
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" id="btn_distribucion_aceptar" class="btn bg-aqua waves-light" data-dismiss="modal">Aceptar</button>            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
<!-- BEGIN MODAL ACTUALIZA CUENTAS -->
  <div class="modal fade" id="mod_cambia_cuentas" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-md " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-primary color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">EDITAR DATOS</h4>
          </div>
          <div class="modal-body" >
          	<!-- cuerpo modal -->
          	<div class="box box-success">
            <div class="box-header with-border">              
            </div>
            <div class="box-body">
                <!-- comienza el formulario -->
                <form>
                	
                	<div class="form-group">
             
                	<div class="row">        	
        			<div class="col-lg-12 col-md-12 col-xs-12">        		
            			<div class="form-group"> 
                			 <div class="form-group-sm">
                				<label for="cuenta_banco_general" class="col-sm-4 control-label" >Cuenta:</label>
                				<div class="col-sm-8">
                                <input type="hidden" id="id_proveedores_general" name="id_proveedores_general" value="0" >
            					<input type="text" class="form-control mayus" id="cuenta_banco_general" name="cuenta_banco_general" value=" <?php echo $resultset[0]->numero_cuenta_banco; ?> " >
                  			 </div>
                  			 </div>        			 
            			</div>
    				</div>
        		</div>
        	    	
                	<div class="row">        	
        			<div class="col-lg-12 col-md-12 col-xs-12">        		
            			<div class="form-group"> 
                			 <div class="form-group-sm">
                				<label for="id_bancos_general" class="col-sm-4 control-label" >Banco:</label>
                				<div class="col-sm-8">
                                  	<select id="id_bancos_general" name="id_bancos_general" class="form-control">
                                  	<option value="0">--Seleccione--</option>                                  	
                                  	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
        		<div class="row">        	
        			<div class="col-lg-12 col-md-12 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_tipo_cuentas_general" class="col-sm-4 control-label" >Tipo Cuenta:</label>
                				<div class="col-sm-8">
                                  	<select id="id_tipo_cuentas_general" name="id_tipo_cuentas_general" class="form-control">
                                  	<option value="0">--Seleccione--</option>                                  	
                                  	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div> 
                       
                      </div>
                                    
                </form>
              
            </div>
           
          </div>  <!-- /.box-body -->
          
          </div>  <!-- /. termina cuerpo modal -->
          
          <!-- /. footer of modal -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" onclick="editar_cuentas()" class="btn btn-primary">Actualizar</button>
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL ACTUALIZA CUENTAS -->
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script type="text/javascript" src="view/tesoreria/js/Transferencias.js?0.35"></script>

  </body>
</html>   

