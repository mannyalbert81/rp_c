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
 
    <body class="hold-transition skin-blue fixed sidebar-mini">
    
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
        <li class="active">Entrada de Pagos Manuales</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Entrada de Pagos Manuales</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
        
        <form id="frm_cuentas_pagar" action="<?php echo $helper->url("ActivosFijos","index1"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        	
        	<div class="row">
    		  
    		   <div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                  <label for="numero_pago" class="control-label">Numero de Pago:</label>
                  <input type="text" class="form-control" id="numero_pago" name="numero_pago" value="" readonly>
				  <div id="mensaje_numero_pago" class="errores"></div>
				  <input type="hidden" name="id_consecutivo" id="id_consecutivo" value="0">
				  <input type="hidden" name="id_cuentas_pagar" id="id_cuentas_pagar" value="0">
                </div>
                </div> 
                
                <div id="divLoaderPage" ></div>
              
				<div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                  <label for="id_tipo_activos_fijos" class="control-label">Fecha:</label>
                  <input type="date" class="form-control" id="fecha_cuentas_pagar" name="fecha_cuentas_pagar" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>" >
                  <div id="mensaje_fecha_cuentas_pagar" class="errores"></div>
                </div>
                </div>  
                
                <div class="col-xs-12 col-lg-3 col-md-3">
    		    <div class="form-group">
                                   
                  <label for="numero_lote" class="control-label">Lote:</label>
                  <input type="text" class="form-control" id="numero_lote" name="numero_lote" value="">
				  <div id="mensaje_numero_lote" class="errores"></div>
                </div>
                </div>  
                
                <div class="col-xs-12 col-md-3 col-md-3">
    		    <div class="form-group">
                                   
                  <label for="total_lote" class="control-label">Total Lote:</label>
                  <input type="text" class="form-control" id="total_lote" name="total_lote" value="">
				  <div id="mensaje_id_estado" class="errores"></div>
                </div>
                </div> 
               </div>
    		    
    		   <div class="row">
    		   
    		   <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="cedula_proveedor" class="control-label">Proveedor:</label>
                      <input type="text" class="form-control" id="cedula_proveedor" name="cedula_proveedor" value="" >
			          <div id="mensaje_cedula_proveedor" class="errores"></div>
			          <input type="hidden" name="id_proveedor" id="id_proveedor" value="0">
                </div>
    		    </div>
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                                   
                  <label for="nombre_proveedor" class="control-label">Titular Proveedor:</label>
                  <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" value="" readonly>
				  <div id="mensaje_nombre_proveedor" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="email_proveedor" class="control-label">Email Proveedor:</label>
                      <input type="text" class="form-control" id="email_proveedor" name="email_proveedor" value=""  placeholder="" readonly>
                      <div id="mensaje_email_proveedor" class="errores"></div>
                </div>
    		    </div>
    		    
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="id_moneda" class="control-label">Moneda:</label>
                      <select id="id_moneda" name="id_moneda" class="form-control">
                      	<option value="0">--SELECCIONE--</option>
                      </select>
                      <div id="mensaje_id_moneda" class="errores"></div>
                </div>
    		    </div>
    		    </div>
    		    
    		  <div class="row">
    		  
    		   <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="id_forma_pago" class="control-label">Forma Pago:</label>
                      <select id="id_forma_pago" name="id_forma_pago" class="form-control">
                      	<option value="0">--SELECCIONE--</option>
                      </select>
                      <div id="mensaje_id_forma_pago" class="errores"></div>
                </div>
    		    </div>
    		    
    		     <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="id_bancos" class="control-label">Banco:</label>
                          <select id="id_bancos" name="id_bancos" class="form-control">
                          	<option value="0">--SELECCIONE--</option>
                          </select>
                          <div id="mensaje_id_bancos" class="errores"></div>
                    </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="numero_movimiento" class="control-label">Numero Movimiento:</label>
                          <input type="text" class="form-control" id="numero_movimiento" name="numero_movimiento" value=""  placeholder="" readonly>
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>    		    
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="monto_cuantas_pagar" class="control-label">Monto:</label>
                          <input type="text" class="form-control" id="monto_cuantas_pagar" name="monto_cuantas_pagar" value=""  placeholder="" >
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>  
    		    
             </div>             
                          
             <div class="row" >
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Comentario:</label>
                      <input type="text" class="form-control" id="comentario_cuentas_pagar" name="comentario_cuentas_pagar"> 
                      <div id="mensaje_comentario_cuentas_pagar" class="errores"></div>
                </div>
    		    </div>
             </div>
             
		    <div class="row">
		    	<div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
		    		<div class="form-group">
              			<button type="submit" id="aplicar" name="aplicar" class="btn btn-default"><i class="fa " aria-hidden="true"></i>Aplicar</button>
              			<button type="submit" id="distribucion" name="distribucion" class="btn btn-default"><i class="fa " aria-hidden="true"></i>Distribucion</button>
            		</div>
		    	</div>
		    	
		    	
		    </div>
                    		      
                    		  
        </form>
          
        </div>
        
        
      </div>
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
            
            	<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_activos_fijos(1)" placeholder="search.."/>
						
				</div>
				
				<div id="load_activos_fijos" ></div>
				<div id="activos_fijos_registrados"></div>	
            
          
            </div>
        </div>
	</section>
            
    
     
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
<?php include("view/modulos/links_js.php"); ?>
<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="view/tesoreria/js/in_pago_manual.js?0.4"></script>
	
    <script type="text/javascript" >   
    
    	
    </script> 
    

</body>
</html>   

 