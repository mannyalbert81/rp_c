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
        <li class="active">Entidad Patronal</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Generacion Cheque</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">
  		
  		<div id="divLoaderPage" ></div> 

			<form id="frm_genera_cheque" action="<?php echo $helper->url("GenerarCheque","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             	
             	<?php $cuentaspagar = $resultSet[0]; ?>    
							    
		    	 <div class="row">
		    	 
		    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
		    	 		<div class="form-group ">                 			 
            				<label for="nombre_lote" class="control-label" > Id. lote:</label>
            				<div class="form-group-sm">                				
                              <input type="text" class="form-control" id="nombre_lote" name="nombre_lote"  autocomplete="off" value="<?php echo $cuentaspagar->nombre_lote; ?>" autofocus>  
                              <input type="hidden" id="id_lote" name="id_lote" value="<?php echo $cuentaspagar->id_lote; ?>">
            				</div>
                						 
            			</div>		    	 	
		    	 	</div>	
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="total_lote" class=" control-label" >Total Lote:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="total_lote" name="total_lote"  value="<?php echo $cuentaspagar->total_cuentas_pagar; ?>" > 
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="nombre_banco" class=" control-label" >Id. de Chequera:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="nombre_banco" name="nombre_banco" value="<?php echo $cuentaspagar->nombre_bancos; ?>" >
                				 <input type="hidden" class="form-control" id="id_banco" name="id_banco" value="<?php echo $cuentaspagar->id_bancos; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="id_moneda" class=" control-label" >Id. de Moneda:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="id_moneda" name="id_moneda" value="<?php echo $cuentaspagar->moneda; ?>" >
                    		</div>        			 
            			</div>
            		</div>   
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="numero_cheque" class=" control-label" >Número de Cheque:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="numero_cheque" name="numero_cheque" value="" >
                    		</div>        			 
            			</div>
            		</div>  
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="fecha_cheque" class=" control-label" >Fecha de Cheque:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control" id="fecha_cheque" name="fecha_cheque" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>" >
                    		</div>        			 
            			</div>
            		</div>  
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="comentario_cheque" class=" control-label" >Comentario del Cheque:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="comentario_cheque" name="comentario_cheque" value="" >
                    		</div>        			 
            			</div>
            		</div>
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="identificacion_proveedor" class=" control-label" >Identificacion Proveedor:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="identificacion_proveedor" name="identificacion_proveedor" value="<?php echo $cuentaspagar->identificacion_proveedores; ?>" >
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="nombre_proveedor" class=" control-label" >Nombre Proveedor:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $cuentaspagar->nombre_proveedores; ?>" >
                    		</div>        			 
            			</div>
            		</div>
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="comentario_cheque" class=" control-label" >Numero Pago:</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control mayus" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $rsConsecutivos[0]->numero_consecutivos; ?>" >
                    		</div>        			 
            			</div>
            		</div>  
            						    
          	   	</div>
          	   	
          	   	<div class="row">
          	   		<div class="col-xs-12 col-md-4 col-lg-4" style="text-align: left;">
                    	<div class="form-group">
                    	  <button type="submit" id="genera_cheque" name="genera_cheque" class="btn btn-success">
                          	<i class="glyphicon glyphicon-plus"></i> Aceptar
                          </button>
                          <button type="button" id="genera_cheque" name="genera_cheque" class="btn btn-success" data-toggle="modal" data-target="#mod_distribucion_pago" >
                          	<i class="glyphicon glyphicon-plus"></i> Distribucion
                          </button>                         
                          <a href="<?php echo $helper->url("GenerarCheque","Index"); ?>" class="btn btn-primary">
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
      <div class="modal-dialog" style="width:90%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Lotes Cuentas Pagar</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_distribucion" name="frm_distribucion">
          	
          	<div class="row">
          		<div id="mod_msg_lote" ></div>
          	</div>
          	
          	<hr>
          	
		  	<div class="box-body">        
				<div id="lista_lote" ></div>
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
    
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script type="text/javascript" src="view/tesoreria/js/GenerarCheque.js?0.02"></script>

  </body>
</html>   

