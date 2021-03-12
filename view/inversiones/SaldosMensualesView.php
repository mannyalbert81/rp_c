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
        <li class="active">Saldos Bancarios Mensuales</li>
      </ol>
     </section>
     
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h4 class="text-info">  Ingreso Saldos Bancarios Mensuales </h4>  
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
    				  <select name="tipo_identificacion_saldos_bancarios_mensuales" id="tipo_identificacion_saldos_bancarios_mensuales"  class="form-control" >
    				  	<option value="0" >--Seleccione--</option>                    
    					<option value="CED" >CED</option>	
    					<option value="RUC" selected="selected">RUC</option>	
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
        			   <label for="tipo_cuenta" class="control-label">Tipo Cuenta:</label>
        			   <select name="tipo_cuenta_saldos_bancarios_mensuales" id="tipo_cuenta_saldos_bancarios_mensuales"  class="form-control" >
        				  	<option value="0" >--Seleccione--</option>
        				    <option value="CORRIENTE" selected="selected">CORRIENTE</option>	
    						<option value="AHORRO" >AHORRO</option>	
    				   </select>
				    </div>
             	</div>
             	
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
        			<div class="form-group">
    	              <label for="numero_cuenta_saldos_bancarios_mensuales" class="control-label" >N&uacute;mero Cuenta:</label>
    				  <input type="text" class="form-control" id="numero_cuenta_saldos_bancarios_mensuales" name="numero_cuenta_saldos_bancarios_mensuales" value="11020504" > 
    				</div>  
             	</div>
             	
             	
             </div>
             
             
             
             
             <div class="row">
    	     
    	     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="cuenta_contable_cap_vencido" class="control-label">Cuenta Contable</label>
                          <input type="text" class="form-control" id="cuenta_contable_saldos_bancarios_mensuales" name="cuenta_contable_saldos_bancarios_mensuales" value="" >
                     </div>
    		  </div>
    	      <div class="col-xs-12 col-lg-3 col-md-3 ">
             		<div class="form-group">
        			   <label for="tipo_cuenta" class="control-label">Denominacion Moneda:</label>
        			   <select name="id_denominaciones_monedas" id="id_denominaciones_monedas"  class="form-control" >
        				  	<option value="0" selected="selected">--Seleccione--</option>
        				    
    				   </select>
				    </div>
             	</div>   
    	     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_moneda_denominacion" class="control-label">Valor Moneda</label>
                          <input type="text" class="form-control" id="valor_moneda_saldos_bancarios_mensuales" name="valor_moneda_saldos_bancarios_mensuales" value="" >
                     </div>
    		  </div>
    	     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="valor_libros_dolares" class="control-label">Valor Libros Dolares</label>
                          <input type="text" class="form-control" id="valor_libros_saldos_bancarios_mensuales" name="valor_libros_saldos_bancarios_mensuales" value="" >
                     </div>
    		  </div>
    	      
    	     
    	     
    	     
    	     
    	     </div>
             
             
             
             
             
             <div class="row">
             
             	<div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="calificacion_emisor" class="control-label">Calificaci&oacute;n emisor:</label>
                          <select name="id_calificaciones" id="id_calificaciones"  class="form-control" >        				  				
        				  </select>
                     </div>
    		     </div>
    		     
    		     <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="calificadora_riesgo" class="control-label">Calificadora Riesgo:</label>                          
                          <select name="id_calificaciones_riesgos" id="id_calificaciones_riesgos"  class="form-control" >        				  										
        				  </select>
                     </div>
    		     </div>
             	
             	<div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_ult_calificacion_saldos_bancarios_mensuales" class="control-label">Fecha Ult. Calificacion:</label>
                           <input type="date" class="form-control" id="fecha_ult_calificacion_saldos_bancarios_mensuales" name="fecha_ult_calificacion_saldos_bancarios_mensuales" value="" >
                     </div>
    		     </div>
    			
    			  <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="base_tasa_interes" class="control-label">Tasa Inter&eacute;s:</label>
                          <input type="text" class="form-control" id="tasa_interes_saldos_bancarios_mensuales" name="tasa_interes_saldos_bancarios_mensuales" value="" >
  
                     </div>
    		     </div>
    		     
    		     
             
             </div>
             
            
             <div class="row">
    	    
    	    		<div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="fecha_compra" class="control-label">Fecha corte:</label>
                           <input type="date" class="form-control" id="fecha_corte_saldos_bancarios_mensuales" name="fecha_corte_saldos_bancarios_mensuales" value="" >
                     </div>
    		     </div>
    		
    	    </div> 
             
            
                	
        	
        	<div class="row">
        	        	
        		<div class=" col-xs-12 col-md-12 col-lg-12 ">
        			<div class="pull-right">
        				
        				<button type="button" id="btn_ingresa_inversiones" value="valor"  class="btn btn-success" onclick="fn_insertar_saldos_bancarios()">
        				<i class="fa fa-sign-in text-success" aria-hidden="true"></i> Ingreso Saldo Bancario
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
                    				<th>Tipo Cuenta</th>
                    				<th>Numero Cuenta</th>
                    				<th>Cuenta Contable</th>
                    				<th>Denominacion Moneda</th>
                    				<th>Valor Moneda</th>
                    				<th>Valor Libros</th> 
                    				<th>Calificacion</th>
                    				<th>Calificador Riesgos</th>
                    				<th>Fecha Ult. Calif.</th>
                    				<th>Tasa Interes</th>
                    				<th>Fecha Corte</th>
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
    <script src="view/inversiones/js/saldos_bancarios_mensuales.js?0.31"></script> 
    
    
   

	
 </body>
</html>