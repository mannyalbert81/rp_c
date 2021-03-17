    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
     <link rel="stylesheet" href="view/bootstrap/plugins/iCheck/all.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  	 <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   <style type="text/css">
    .form-control {
        border-radius: 5px; !important;
    }
    .letrasize10{
        font-size: 10px;
     }
     .letrasize11{
        font-size: 11px;
     }
     .letrasize12{
        font-size: 12px;
     }
     
         
   </style>
 
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">inversiones</li>
      </ol>
     </section>
     
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h4 class="text-info">  Ingreso Inversiones </h4>  
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        	<div class="row">
        	
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        			<div class="form-group">
    	              <label for="tipo_identificacion" class="control-label" >Tipo Identificaci&oacute;n:</label>
    				  <select name="tipo_identificacion" id="tipo_identificacion"  class="form-control" >
    				  	<option value="0" selected="selected">--Seleccione--</option>                    
    					<option value="CED" >CED</option>	
    					<option value="RUC" >RUC</option>	
    					<option value="PAS" >PAS</option>					
    				   </select> 
				   </div>
             	</div>
        	
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        			<div class="form-group">
        			   <label for="identificacion_emisor" class="control-label">Identificaci&oacute;n:</label>
        			   <input type="text" class="form-control" id="identificacion_emisor" name="identificacion_emisor" >    			   
    	               <input type="hidden" id="id_emisor" value="0" />
    	            </div>
    			</div>
        	
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        			<div class="form-group">
    	              <label for="numero_instrumento" class="control-label" >N&uacute;mero Instrumento:</label>
    				  <input type="text" class="form-control" id="numero_instrumento" name="numero_instrumento" value="" > 
    				</div>  
             	</div>
             	
             	<div class="col-xs-12 col-lg-3 col-md-3 ">
             		<div class="form-group">
        			   <label for="tipo_instrumento" class="control-label">Tipo Instrumento:</label>
        			   <select name="tipo_instrumento" id="tipo_instrumento"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option> 
    				   </select>
				    </div>
             	</div>
             	
             </div>
             
             <div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="tipo_renta" class="control-label">Tipo Renta:</label>
                           <select name="tipo_renta" id="tipo_renta"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="RENTAFIJA" >Renta Fija</option>	
        					<option value="RENTAVARIABLE" >Renta Variable</option>		
        				   </select> 
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_emision" class="control-label">Fecha Emisi&oacute;n:</label>
                           <input type="text" class="form-control" id="fecha_emision" name="fecha_emision" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_compra" class="control-label">Fecha compra:</label>
                           <input type="text" class="form-control" id="fecha_compra" name="fecha_compra" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_vencimiento" class="control-label">Fecha vencimiento:</label>
                          <input type="text" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	 <div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="tasa_nominal" class="control-label">Tasa Nominal:</label>
                          <input type="text" class="form-control" id="tasa_nominal" name="tasa_nominal" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="plazo_pactado" class="control-label">Plazo Pactado:</label>
                           <input type="text" class="form-control" id="plazo_pactado" name="plazo_pactado" value="" readonly >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_nominal" class="control-label">Valor Nominal:</label>
                           <input type="text" class="form-control" id="valor_nominal" name="valor_nominal" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="numero_acciones" class="control-label">N&uacute;mero acciones compradas:</label>
                          <input type="text" class="form-control" id="numero_acciones" name="numero_acciones" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_compra" class="control-label">Precio Compra acci&oacute;n:</label>
                          <input type="text" class="form-control" id="precio_compra" name="precio_compra" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_compra" class="control-label">Valor Compra:</label>
                           <input type="text" class="form-control" id="valor_compra" name="valor_compra" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="periodo_pago" class="control-label">Periodo Pago:</label>
                           <input type="text" class="form-control" id="periodo_pago" name="periodo_pago" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="amortizacion_capital" class="control-label">Amortizaci&oacute;n Capital:</label>
                          <input type="text" class="form-control" id="amortizacion_capital" name="amortizacion_capital" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="amortizacion_interes" class="control-label">Amortizaci&oacute;n Inter&eacute;s:</label>
                          <input type="text" class="form-control" id="amortizacion_interes" name="amortizacion_interes" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="base_tasa_capital" class="control-label">Base Tasa Capital:</label>
                          <select name="base_tasa_capital" id="base_tasa_capital"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="360" >360</option>	
        					<option value="365" >365</option>		
        				   </select> 
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="base_tasa_interes" class="control-label">Base Tasa Inter&eacute;s:</label>
                          <select name="base_tasa_interes" id="base_tasa_interes"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="360" >360</option>	
        					<option value="365" >365</option>		
        				   </select> 
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="periodo_gracia" class="control-label">Periodo Gracia:</label>
                          <select name="periodo_gracia" id="periodo_gracia"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="t" >SI</option>	
        					<option value="f" >NO</option>		
        				   </select> 
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="estado_registro" class="control-label">Estado Registro:</label>
                          <select name="estado_registro" id="estado_registro"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="I" >I</option>	
        					<option value="A" >A</option>	
        					<option value="E" >E</option>		
        				   </select> 
                     </div>
    		     </div>             	
        	</div>
        	
        	
        	<div class="row">
        	        	
        		<div class=" col-xs-12 col-md-12 col-lg-12 ">
        			<div class="pull-right">
        				
        				<button type="button" id="btn_ingresa_inversiones" value="valor"  class="btn btn-success" onclick="fn_insertar_inversiones()">
        				<i class="fa fa-sign-in text-success" aria-hidden="true"></i> Ingreso Inversiones
        				</button>
    					        			
        			</div>
    				
				</div>
				<div class="clearfix"></div>	
				        	
        	</div>
        	
        	
        	
        	
        
        </div>
      </div>
     </section>
     
     <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h4 class="text-info"> Listado Inversiones </h4>  
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        	<div class="row">
     			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            		<div id="div_inversiones" class="letrasize11">
                    <!--                 		display compact -->
                    <!--  table tablesorter table-striped table-bordered nowrap -->
                		<table id="tblinversiones" class="table table-bordered display compact">
                			<thead>
                				<tr class="info">
                					<th>#</th>
                    				<th>Tipo Identificaci&oacute;n</th>
                    				<th>Identificaci&oacute;n</th>
                    				<th>Nombre Emisor</th>
                    				<th>N&uacute;mero Instrumento</th>
                    				<th>Tipo Instrumento</th>
                    				<th>Tipo Renta</th>
                    				<th>Fecha Emision</th>
                    				<th>Fecha Compra</th>
                    				<th>Fecha Vencimiento</th> 
                    				<th>Tasa Nominal</th>
                    				<th>Plazo Pactado</th>
                    				<th>Valor Nominal</th>
                    				<th>Numero Acciones</th>
                    				<th>Precio Compra</th>
                    				<th>Valor Compra</th>
                    				<th>Periodo Pago</th>
                    				<th>Amortizaci&oacute;n Capital</th>
                    				<th>Amortizaci&oacute;n Inter&eacute;s</th>
                    				<th>Base Tasa Capital</th>
                    				<th>Base Tasa Inter&eacute;s</th>
                    				<th>Periodo Gracia</th>
                    				<th>Estado Registro</th> 
                    				<th>Opciones</th> 
                				</tr>                    				
                			</thead>  
                			<tbody>
                				
                			</tbody>                  			
                			<tfoot>
                				
                			</tfoot>
                		</table>
            		</div>
     			</div>
     		</div>
             		
        </div>
        
       </div>
      </section>
     
	 </div>
	
	 </div>
 
 
 
 <!-- para modales -->
 
 
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
     
   
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/plugins/iCheck/icheck.js"></script>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>    
    <script src="view/inversiones/js/ingreso_inversiones.js?0.15"></script> 
    
    
   

	
 </body>
</html>