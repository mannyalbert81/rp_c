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
             	
             	<?php  ?>    
							    
		    	 <div class="row">
		    	 
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
            				<label for="nombre_lote" class="control-label" > Lote:</label>
            				<div class="form-group-sm">                				
                              <input type="text" class="form-control" id="nombre_lote" name="nombre_lote"  autocomplete="off" value="<?php echo $resultSet[0]->nombre_lote; ?>" autofocus>  
                              <input type="hidden" id="id_lote" name="id_lote" value="<?php echo $resultSet[0]->id_lote; ?>">
                              <input type="hidden" id="id_cuentas_pagar" name="id_cuentas_pagar" value="<?php echo $resultSet[0]->id_cuentas_pagar; ?>" >
            				</div>
                						 
            			</div>		    	 	
		    	 	</div>
            		
            		
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
            				<label for="identificacion_proveedor" class=" control-label" >Descripción:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="descripcion_pago" name="descripcion_pago" value="Credito-|||" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="identificacion_proveedor" class=" control-label" >Identificacion Beneficiario:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="identificacion_proveedor" name="identificacion_proveedor" value="<?php echo $resultSet[0]->cedula_participes; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="nombre_proveedor" class=" control-label" >Nombre Beneficiario:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $resultSet[0]->apellido_participes.' - '. $resultSet[0]->nombre_participes;  ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="total_lote" class=" control-label" >Total Pago:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="total_cuentas_pagar" name="total_cuentas_pagar"  value="<?php echo $resultSet[0]->total_cuentas_pagar; ?>" > 
                    		</div>        			 
            			</div>
            		</div> 
            		            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="nombre_cuenta_banco" class=" control-label" >Transferir a:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="nombre_cuenta_banco" name="nombre_cuenta_banco" value="<?php echo $rsSolicitud[0]->nombre_banco_cuenta_bancaria; ?>" >
                    		</div>        			 
            			</div>
            		</div>
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="cuenta_banco" class=" control-label" >Cuenta:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="cuenta_banco" name="cuenta_banco" value=" <?php echo $rsSolicitud[0]->numero_cuenta_cuenta_bancaria; ?> " >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="tipo_cuenta_banco" class=" control-label" >Tipo Cuenta:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="tipo_cuenta_banco" name="tipo_cuenta_banco" value=" <?php echo $rsSolicitud[0]->tipo_cuenta_cuenta_bancaria; ?> " >
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
                          <button type="button" id="distribucion_transferencia" name="distribucion_transferencia" class="btn btn-success" data-toggle="modal" data-target="#mod_distribucion_pago" >
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
                          <input type="text" style="height:30px"  class=" form-control" id="mod_banco_transferir" name="mod_banco_transferir" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>     
          	</div>
             
          	<hr>
          	
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
    

 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script type="text/javascript" src="view/tesoreria/js/Transferencias.js?0.09"></script>

  </body>
</html>   

