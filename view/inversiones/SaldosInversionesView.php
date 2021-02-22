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
          <h4 class="text-info">  Saldos Inversiones </h4>  
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
                          <label for="estado_inversion" class="control-label">Estado Inversión:</label>
                           <select name="estado_inversion" id="estado_inversion"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="INVVIGENTE" >Inversión Vigente</option>	
        					<option value="INVVENCIDA" >Inversión Vencida</option>
        					<option value="INVVENDIDA" >Inversión Vendida</option>	
        					<option value="INVLIQUIDADA" >Inversión Liquidada</option>		
        				   </select> 
                     </div>
    		     </div>
    	         
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
                          <label for="fecha_compra" class="control-label">Fecha compra:</label>
                           <input type="date" class="form-control" id="fecha_compra" name="fecha_compra" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="rango_vencimiento" class="control-label">Rango Vencimiento:</label>
                          <select name="rango_vencimiento" id="rango_vencimiento"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>                    
        					<option value="DE1A30DIAS" >de 1-30 dias</option>	
        					<option value="DE31A90DIAS" >de 31-90 dias</option>	
        					<option value="DE91A180DIAS" >de 91-180 dias</option>	
        					<option value="DE181A360DIAS" >de 181-360 dias</option>
        					<option value="DEMAS360DIAS" >de mas 360 dias</option>		
        				  </select>
                     </div>
    		     </div>
    		                  	
        	</div>
        	
        	 <div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_contable" class="control-label">Valor contable:</label>
                          <input type="date" class="form-control" id="valor_contable" name="valor_contable" value="" >
                     </div>
    		     </div>
    		     
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="tasa_nominal" class="control-label">Tasa Nominal:</label>
                          <input type="text" class="form-control" id="tasa_nominal" name="tasa_nominal" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="tasa_cupon" class="control-label">Tasa Cupon:</label>
                           <input type="text" class="form-control" id="tasa_cupon" name="tasa_cupon" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_ult_cupon" class="control-label">Fecha Ult. Cupon:</label>
                          <input type="date" class="form-control" id="fecha_ult_cupon" name="fecha_ult_cupon" value="" >
                     </div>
    		     </div>
    		                  	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_compra_porcentaje" class="control-label">Precio Compra Porcentaje:</label>
                          <input type="text" class="form-control" id="precio_compra_porcentaje" name="precio_compra_porcentaje" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_efectivo" class="control-label">Valor Efectivo Compra:</label>
                           <input type="text" class="form-control" id="valor_efectivo" name="valor_efectivo" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="rendimiento_porcentaje" class="control-label">Rendimiento Porcentaje:</label>
                           <input type="text" class="form-control" id="rendimiento_porcentaje" name="rendimiento_porcentaje" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_anio_renta" class="control-label">Precio Hace Año:</label>
                          <input type="text" class="form-control" id="precio_anio_renta" name="precio_anio_renta" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="interes_acumulado_c" class="control-label">Interes Acumulado Cobrar:</label>
                          <input type="text" class="form-control" id="interes_acumulado_c" name="interes_acumulado_c" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="monto_generados" class="control-label">Monto Generados:</label>
                          <input type="text" class="form-control" id="monto_generados" name="monto_generados" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_mercado" class="control-label">Valor Mercado:</label>
                          <input type="text" class="form-control" id="valor_mercado" name="valor_mercado" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="numero_acciones_corte" class="control-label">Numero Acciones Corte:</label>
                          <input type="text" class="form-control" id="numero_acciones_corte" name="numero_acciones_corte" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_mercado_actual" class="control-label">Precio Mercado Actual:</label>
                          <input type="text" class="form-control" id="precio_mercado_actual" name="precio_mercado_actual" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_mercado_hace_anio" class="control-label">Precio Mercado Hace Año:</label>
                          <input type="text" class="form-control" id="precio_mercado_hace_anio" name="precio_mercado_hace_anio" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="dividendo_accion" class="control-label">Dividendo Accion:</label>
                          <input type="text" class="form-control" id="dividendo_accion" name="dividendo_accion" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="codigo_vecto_precio" class="control-label">Codigo Vecto Precio:</label>
                          <input type="text" class="form-control" id="codigo_vecto_precio" name="codigo_vecto_precio" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="calificacion_emisor" class="control-label">Calificaci&oacute;n emisor:</label>
                          <input type="text" class="form-control" id="calificacion_emisor" name="calificacion_emisor" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="calificadora_riesgo" class="control-label">Calificadora Riesgo:</label>
                          <input type="text" class="form-control" id="calificadora_riesgo" name="calificadora_riesgo" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_ult_calificacion" class="control-label">Fecha Ult. Calificaci&oacute;n:</label>
                          <input type="date" class="form-control" id="fecha_ult_calificacion" name="fecha_ult_calificacion" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="provision_constituida" class="control-label">Provisi&oacute;n Constituida:</label>
                          <input type="text" class="form-control" id="provision_constituida" name="provision_constituida" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="estado_vencimiento" class="control-label">Estado Vencimiento:</label>
                          <input type="text" class="form-control" id="estado_vencimiento" name="estado_vencimiento" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_nominal_vencimiento" class="control-label">Valor Nominal Vencimiento:</label>
                          <input type="text" class="form-control" id="valor_nominal_vencimiento" name="valor_nominal_vencimiento" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="interes_acumulado_c" class="control-label">Inter&eacute;s Acumulado Cobrar:</label>
                          <input type="date" class="form-control" id="interes_acumulado_c" name="interes_acumulado_c" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="numero_cuotas_vencidas" class="control-label">Numero Cuotas Vencidas:</label>
                          <input type="text" class="form-control" id="numero_cuotas_vencidas" name="numero_cuotas_vencidas" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="cuenta_contable_cap_vencido" class="control-label">Cuenta Contable Cap:</label>
                          <input type="text" class="form-control" id="cuenta_contable_cap_vencido" name="cuenta_contable_cap_vencido" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_dolares" class="control-label">Valor Usd:</label>
                          <input type="text" class="form-control" id="valor_dolares" name="valor_dolares" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="cuenta_contable_ren_vencido" class="control-label">Cuenta Contable Rend:</label>
                          <input type="date" class="form-control" id="cuenta_contable_ren_vencido" name="cuenta_contable_ren_vencido" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_dolares_dos" class="control-label">Valor Usd:</label>
                          <input type="text" class="form-control" id="valor_dolares_dos" name="valor_dolares_dos" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="cuenta_contable_provision_acumulada_capital" class="control-label">Cuenta Contable Prov Acum. Cap:</label>
                          <input type="text" class="form-control" id="cuenta_contable_provision_acumulada_capital" name="cuenta_contable_provision_acumulada_capital" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_dolares_tres" class="control-label">Valor Usd:</label>
                          <input type="text" class="form-control" id="valor_dolares_tres" name="valor_dolares_tres" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="cuenta_contable_provision_acumulada_rendimiento" class="control-label">Cuenta Contable Prov Acum Rend:</label>
                          <input type="date" class="form-control" id="cuenta_contable_provision_acumulada_rendimiento" name="cuenta_contable_provision_acumulada_rendimiento" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_dolares_cuatro" class="control-label">Valor Usd:</label>
                          <input type="text" class="form-control" id="valor_dolares_cuatro" name="valor_dolares_cuatro" value="" >
                     </div>
    		     </div>
             	
        	</div>
        	
        	<div class="row">
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_liquido_vencido" class="control-label">Valor L&iacute;quido Vencido:</label>
                          <input type="text" class="form-control" id="valor_liquido_vencido" name="valor_liquido_vencido" value="" >
                     </div>
    		     </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_liquida_venta" class="control-label">Fecha L&iacute;quida venta:</label>
                          <input type="date" class="form-control" id="fecha_liquida_venta" name="fecha_liquida_venta" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="precio_liquido_venta" class="control-label">Precio L&iacute;quido venta:</label>
                          <input type="date" class="form-control" id="precio_liquido_venta" name="precio_liquido_venta" value="" >
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_liquido_venta" class="control-label">Valor L&iacute;quido venta:</label>
                          <input type="text" class="form-control" id="valor_liquido_venta" name="valor_liquido_venta" value="" >
                     </div>
    		     </div>
             	
        	</div>        	
        	        	
        	<div class="row">
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="motivo_liquido" class="control-label">Motivo L&iacute;quido:</label>
                          <input type="text" class="form-control" id="motivo_liquido" name="motivo_liquido" value="" >
                     </div>
    		     </div>             	
        	</div>
        	
        	
        	<div class="row">
        	        	
        		<div class=" col-xs-12 col-md-12 col-lg-12 ">
        			<div class="pull-right">
        				
        				<button type="button" id="btn_ingresa_inversiones" value="valor"  class="btn btn-success" onclick="fn_insertar_inversiones()">
        				<i class="fa fa-sign-in text-success" aria-hidden="true"></i> Ingresar Saldos Inversiones
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
          <h4 class="text-info"> Listado Saldos Inversiones </h4>  
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
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>    
    <script src="view/inversiones/js/saldos_inversiones.js?0.08"></script> 
    
    
   

	
 </body>
</html>