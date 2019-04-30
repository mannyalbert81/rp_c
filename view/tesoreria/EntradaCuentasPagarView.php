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
 
    <body class="hold-transition skin-blue fixed sidebar-mini" onbeforeunload="return myFunction()">
    
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
        
        <form id="frm_cuentas_pagar" action="<?php echo $helper->url("CuentasPagar","PagosManualesIndex"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        	
        	<div class="row">
    		  
    		   <div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                  <label for="num_comprobante" class="control-label">Numero Comprobante:</label>
                  <input type="text" class="form-control" id="num_comprobante" name="num_comprobante" value="" readonly>
				  <div id="mensaje_num_comprobante" class="errores"></div>
				  <input type="hidden" name="id_consecutivo" id="id_consecutivo" value="0">
				  <input type="hidden" name="id_cuentas_pagar" id="id_cuentas_pagar" value="0">
                </div>
                </div>
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="id_tipo_documento" class="control-label">Tipo de Documento:</label>
                      <select id="id_tipo_documento" name="id_tipo_documento" class="form-control">
                      	<option value="0">--SELECCIONE--</option>
                      </select>
                      <div id="mensaje_id_tipo_documento" class="errores"></div>
                </div>
    		    </div> 
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="descripcion_cuentas_pagar" class="control-label">Descripcion:</label>
                      <input type="text" class="form-control" id="descripcion_cuentas_pagar" name="descripcion_cuentas_pagar" value="" placeholder="Descripcion" required>
                      <div id="mensaje_descripcion_cuentas_pagar" class="errores"></div>
                </div>
    		    </div> 
                
                <div id="divLoaderPage" ></div>
              
				<div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                  <label for="id_tipo_activos_fijos" class="control-label">Fecha Doc:</label>
                  <input type="date" class="form-control" id="fecha_cuentas_pagar" name="fecha_cuentas_pagar" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>" >
                  <div id="mensaje_fecha_cuentas_pagar" class="errores"></div>
                </div>
                </div>  
                
               </div>
    		    
    		   <div class="row">
    		   
    		   	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group ">
    		    	<label for="nombre_lote" class="control-label">Id. lote:</label>
                    <div class="input-group ">
                      <input type="text" class="form-control" id="nombre_lote" name="nombre_lote" value="">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#mod_lote">
                        <i class="fa fa-arrow-right"></i>
                        </button>
                      </span>
                      <div id="mensaje_id_lote" class="errores"></div>
                    </div>
                    <input type="hidden" id="id_lote" name="id_lote" value="0">
                 </div>
                 </div>
    		   
    		    
    		   
    		   <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="cedula_proveedor" class="control-label">CI/RUC Proveedor:</label>
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
    		    
    		    
    		    </div>
    		    
    		  <div class="row">
    		  
    		  	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="condiciones_pago" class="control-label">Condiciones Pago:</label>
                      <input type="text" class="form-control " id="condiciones_pago" name="condiciones_pago">                      
                      <div id="mensaje_condiciones_pago" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="numero_documento" class="control-label">Numero Documento:</label>
                      <input type="text" class="form-control " id="numero_documento" name="numero_documento">                      
                      <div id="mensaje_numero_documento" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="numero_ord_compra" class="control-label">Numero Orden Compra:</label>
                      <input type="text" class="form-control " id="numero_ord_compra" name="numero_ord_compra">                      
                      <div id="mensaje_numero_ord_compra" class="errores"></div>
                </div>
    		    </div>
    		  
    		  	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group ">
                      <label for="id_moneda" class="control-label">Moneda:</label>
                      <select id="id_moneda" name="id_moneda" class="form-control">
                      	<option value="0">--SELECCIONE--</option>
                      </select>
                      <div id="mensaje_id_moneda" class="errores"></div>
                </div>
    		    </div>
    		  
    		    
             </div>             
                          
             <div class="row" >  
             
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="metodo_envio" class="control-label">Metodo Envio:</label>
                      <input type="text" id="metodo_envio" name="metodo_envio" class="form-control" >                    
                      <div id="mensaje_metodo_envio" class="errores"></div>
                </div>
    		    </div>           
             	
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="id_impuestos" class="control-label">plan Impuestos:</label>
                      <input type="text" id="id_impuestos" name="id_impuestos" class="form-control">
                      <input type="hidden" id="cod_id_impuestos" name="cod_id_impuestos">
                      <div id="mensaje_id_impuestos" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group ">
    		    	<label for="id_impuestos" class="control-label">plan Impuestos:</label>
                    <div class="input-group ">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#mod_impuestos">
                        <i class="fa fa-minus"></i> Buscar
                        </button>
                      </span>
                      <div id="mensaje_de_prueba" class="errores"></div>
                    </div>
                    
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
    		    
             </div>
             
             <div class="row">
             
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="numero_movimiento" class="control-label">Compras:</label>
                          <input type="text" class="form-control" id="numero_movimiento" name="numero_movimiento" value=""  placeholder="" readonly>
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>    		    
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="monto_cuantas_pagar" class="control-label">Desc Comercial:</label>
                          <input type="text" class="form-control" id="monto_cuantas_pagar" name="monto_cuantas_pagar" value=""  placeholder="" >
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>  
    		    
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Flete:</label>
                      <input type="text" class="form-control" id="comentario_cuentas_pagar" name="comentario_cuentas_pagar"> 
                      <div id="mensaje_comentario_cuentas_pagar" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Miscelaneos:</label>
                      <input type="text" class="form-control" id="comentario_cuentas_pagar" name="comentario_cuentas_pagar"> 
                      <div id="mensaje_comentario_cuentas_pagar" class="errores"></div>
                </div>
    		    </div>
    		    
             </div>
             
             <div class="row">
             
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="numero_movimiento" class="control-label">Impuesto:</label>
                          <input type="text" class="form-control" id="numero_movimiento" name="numero_movimiento" value=""  placeholder="" readonly>
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>    		    
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="monto_cuantas_pagar" class="control-label">Total:</label>
                          <input type="text" class="form-control" id="monto_cuantas_pagar" name="monto_cuantas_pagar" value=""  placeholder="" >
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>  
    		    
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Monto 1099:</label>
                      <input type="text" class="form-control" id="comentario_cuentas_pagar" name="comentario_cuentas_pagar"> 
                      <div id="mensaje_comentario_cuentas_pagar" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Efectivo:</label>
                      <input type="text" class="form-control" id="comentario_cuentas_pagar" name="comentario_cuentas_pagar"> 
                      <div id="mensaje_comentario_cuentas_pagar" class="errores"></div>
                </div>
    		    </div>
             
             </div>
             
             <div class="row">
             
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="numero_movimiento" class="control-label">Tarjeta Credito:</label>
                          <input type="text" class="form-control" id="numero_movimiento" name="numero_movimiento" value=""  placeholder="" readonly>
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>    		    
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
        		    <div class="form-group">
                          <label for="monto_cuantas_pagar" class="control-label">Cond. dtos. Tomados:</label>
                          <input type="text" class="form-control" id="monto_cuantas_pagar" name="monto_cuantas_pagar" value=""  placeholder="" >
                          <div id="mensaje_numero_movimiento" class="errores"></div>
                    </div>
    		    </div>  
    		    
             	<div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Saldo Cuenta:</label>
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
 
 <!-- PARA MODALES -->
 
  <div class="modal fade" id="mod_lote">
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
			  
          	</form>
          	<!-- termina el formulario modal lote -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" form="frm_genera_lote" class="btn btn-primary" id="guardar_datos">Genera Lote</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
  
 
 <div class="modal fade" id="mod_impuestos">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">AGREGAR PRODUCTO</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form-horizontal" method="post" id="frm_guardar_producto" name="frm_guardar_producto">
          	
          	<div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Grupos:</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_id_grupo" name="mod_id_grupo" required>
					<option value="0">-- Selecciona estado --</option>					
				  </select>
				</div>
			  </div>
			 
			 <div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Bodega:</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_id_bodegas" name="mod_id_bodegas" required>
					<option value="0">-- Selecciona Bodega --</option>					
				  </select>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="estado" class="col-sm-3 control-label">Unidad Medida</label>
				<div class="col-sm-8">
				 <select class="form-control" id="mod_unidad_medida" name="mod_unidad_medida" required>
					<option value="0">-- Selecciona estado --</option>					
				  </select>
				</div>
			  </div>
			  	
			  <div class="form-group">
				<label for="codigo" class="col-sm-3 control-label">Código</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_codigo_producto" name="mod_codigo_producto" placeholder="Código del producto" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Marca</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_marca_producto" name="mod_marca_producto" placeholder="Código del producto" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Nombre</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="mod_nombre_producto" name="mod_nombre_producto" placeholder="Nombre del producto" required maxlength="20" ></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Descripcion</label>
				<div class="col-sm-8">
					<textarea class="form-control" id="mod_descripcion_producto" name="mod_descripcion_producto" placeholder="Descripcion del producto" required maxlength="20" ></textarea>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="nombre" class="col-sm-3 control-label">Ult. precio</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_precio_producto" name="mod_precio_producto" placeholder="Precio de venta del producto" maxlength="10" required  title="Ingresa sólo números con 0 ó 2 decimales" />
				  
				</div>
			  </div>
			  
          	</form>
          	<!-- termina el formulario modal productos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" form="frm_guardar_producto" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
<?php include("view/modulos/links_js.php"); ?>
<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="view/tesoreria/js/CuentasPagar.js?0.0"></script>
	
    <script type="text/javascript" >   
    
    	
    </script> 
    

</body>
</html>   

 