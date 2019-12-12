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
        <li class="active">Diario Contabl</li>
      </ol>
     </section>
     
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h4 class="text-info">  Registro Comprobantes   </h4>  
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        	<div class="row">
        	
        		<div class="col-xs-12 col-lg-3 col-md-3 ">
	              <label for="id_tipo_comprobantes" class="control-label" >Tipo:</label>
	              <input type="hidden" id="nombre_comprobante" value="" />
				  <select name="con_id_tipo_comprobantes" id="con_id_tipo_comprobantes"  class="form-control" onchange="searchNumComprobante()">
				  <option value="0" selected="selected">--Seleccione--</option>
                     <?php foreach($resultTipCom as $res) {?>
					  <option value="<?php echo $res->id_tipo_comprobantes; ?>" ><?php echo $res->nombre_tipo_comprobantes; ?> </option>
					 <?php } ?>
				   </select> 
                   <div id="mensaje_id_tipo_comprobantes" class="errores"></div>
             	</div>
             	
             	<div class="col-xs-12 col-lg-3 col-md-3 ">
    			   <label for="con_fecha_comprobantes" class="control-label">Fecha:</label>
    			   <input type="date" class="form-control" id="con_fecha_comprobantes" name="con_fecha_comprobantes" 
    			   min="<?php echo date('Y-m-d', mktime(0,0,0, date('m'), date("d", mktime(0,0,0, date('m'), 1, date('Y'))), date('Y'))); ?>" 
    			   max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d');?>"
    			   onfocusout="verificaPeriodo()" >
    			</div>
                 
                 <div id="div_datos" style="display: ;">
                     <div class="col-xs-12 col-lg-3 col-md-3 ">
        			   <label for="con_numero_comprobantes" class="control-label">Número Comprobante:</label>
        			   <input type="text" class="form-control" id="con_numero_comprobantes" name="con_numero_comprobantes" value=""  readonly>
                     </div>
    	         </div>
    	         
    	         <div class="col-xs-12 col-lg-3 col-md-3 ">
        		     <div class="form-group">
                          <label for="con_referencia_doc_comprobantes" class="control-label">Referencia Doc:</label>
                          <input type="text" class="form-control" maxlength="50" id="con_referencia_doc_comprobantes" name="con_referencia_doc_comprobantes" value=""  placeholder="Referencia Comprobantes">
                     </div>
    		     </div>
    	         
             	
        	</div>
        	
        	<div class="row">
        	
        		<div class="col-xs-12 col-md-6 col-lg-6">
        		     <div class="form-group">
                          <label for="con_concepto_comprobantes" class="control-label">Concepto Comprobante:</label>
                          <textarea rows="1"  class="form-control" id="con_concepto_comprobantes"  placeholder="Concepto.." cols="" maxlength="200" ></textarea>
                     </div> 
    		     </div>
        	
        	</div>
        	
        	<div class="row">
        	        	
        		<div class=" col-xs-12 col-md-12 col-lg-12 ">
        			<div class="pull-right">
        				
        				<button type="button" id="btn_ins_comprobante" value="valor"  class="btn btn-default" onclick="fnInsComprobante()">
        				<i class="fa fa-sign-in text-success" aria-hidden="true"></i> Generar Comprobante
        				</button>
    					     
    					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
    					 <span class="glyphicon glyphicon-search"></span> Buscar Cuentas
    					</button>
        			
        			</div>
    				
				</div>
				<div class="clearfix"></div>	
				        	
        	</div>
        	
        	<div class="row">
        		<div class="col-xs-12 col-md-12 col-lg-12">
        		<p class="span">
        		</div>
        	</div>
        	
        	<div class="panel panel-default">
              <div class="panel-heading"> <i class="fa fa-plus text-success" aria-hidden="true"></i> Agregar Cuentas</div>
              <div class="panel-body">
                <div class="row">
          			 <div class="form-group" style="margin-top:13px">
                         <div class="col-xs-2 col-md-2 col-lg-2">
                              <label for="id_plan_cuentas" class="control-label" >#Cuenta: </label>
                              <input type="text" class="form-control" id="id_plan_cuentas" name="id_plan_cuentas" onkeyup="autompleteCodigo(this)" value=""  placeholder="Search">
                              <div id="mensaje_id_plan_cuentas" class="errores"></div>
                              <input type="hidden" class="form-control" id="plan_cuentas" name="plan_cuentas" value="0"  placeholder="Search">
                              <span class="help-block"></span>
                         </div>
                     </div>
    		     
        		     <div class="form-group">  
            		     <div class="col-xs-3 col-md-3 col-lg-3">                     
                              <label for="nombre_plan_cuentas" class="control-label">Nombre: </label>
                              <input type="text" class="form-control" id="nombre_plan_cuentas" name="nombre_plan_cuentas" onkeyup="autompleteNombre(this)"  value=""  placeholder="Search">
                              <span class="help-block"></span>
                         </div>
        		     </div>
    		     
        		     <div class="form-group">
                         <div class="col-xs-3 col-md-3 col-lg-3">
                              <label for="descripcion_dcomprobantes" class="control-label">Descripción: </label>
                              <input type="text" class="form-control" id="descripcion_dcomprobantes" name="descripcion_dcomprobantes" value=""  placeholder="">
                              <span class="help-block"></span>
                         </div>
        		     </div>
    		
    		     
        		     <div class="form-group">
                         <div class="col-xs-2 col-md-2 col-lg-2">
                              <label for="debe_dcomprobantes" class="control-label">Debe: </label>
                              <input type="text" class="form-control cantidades1" id="debe_dcomprobantes" name="debe_dcomprobantes" placeholder="0.00" 
                                    onfocus="validardebe(this);">
                               <div id="mensaje_debe_dcomprobantes" class="errores"></div>
                                             
                         </div>
        		     </div>
    		     
    		     
        		     <div class="form-group">
                         <div class="col-xs-2 col-md-2 col-lg-2">
                           <label for="haber_dcomprobantes" class="control-label">Haber: </label>
                           <input type="text" class="form-control cantidades1" id="haber_dcomprobantes" name="haber_dcomprobantes" placeholder="0.00"
                                 onfocus="validardebe(this);">
                           <div id="mensaje_haber_dcomprobantes" class="errores"></div>
                         </div>
        		     </div>
    		     </div>		     
    		     
    		     <div class="row">
        		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center;">
        		    <div class="form-group">
                          <button type="button" onclick="agregar_temp_comprobantes();" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i></button>
                    </div>
        		    </div>
    		     </div>
    		    
    		     <div class="row">
    		     	<div class="col-xs-12 col-md-12 col-lg-12">
    		     		<div class="pull-right">
    						<input type="text" value="" class="form-control circle" id="search_temp_comprobantes" name="search_temp_comprobantes" onkeyup="load_temp_comprobantes(1)" placeholder="search.."/>
    					</div>
    		     	</div>    	            
    				<div class="clearfix"></div>
    				<div id="load_temp_comprobantes_registrados" ></div>	
    				<div id="temp_comprobantes_registrados"></div>
    				<div id="mensaje_detalle_dcomprobantes" class="errores"></div>	
    		     </div>
              </div>            
            </div>
        	
        
        </div>
      </div>
     </section>
     
	 </div>
	
	 </div>
 
 
 
 <!-- para modales -->
 
 
 
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
	  <div class="modal-header bg-aqua disabled color-palette">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Buscar Cuentas</h4>
	  </div>
	
	  <div class="modal-body">
		
		  <div class="form-group">
			<div class="col-sm-6">
			  <input type="text" class="form-control" id="q" placeholder="Buscar Plan de Cuentas" onkeyup="load_plan_cuentas(1)">
			</div>
			<button type="button"  class="btn btn-default" onclick="load_plan_cuentas(1)"><span class='glyphicon glyphicon-search'></span></button>
		  </div>
		
		<div id="load_plan_cuentas" ></div>
		<div id="cargar_plan_cuentas" ></div>
		
		<div class="clearfix"></div>
		
	  </div>
	<br>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	  </div>
	
	</div>
  </div>
</div>
			
<!-- modal de proveedores -->

 <div class="modal fade bs-example-modal-lg" id="modalproveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Proveedores</h4>
	  </div>
	
	  <div class="modal-body">
	  
	  <form class="form-horizontal" method="post" id="frm_guardar_proveedor" name="frm_guardar_proveedor">

      	
		  <div class="form-group">
			<label for="nombre_proveedores" class="col-sm-3 control-label">Nombre Proveedores: </label>
			<div class="col-sm-8">
			  <input type="text" class="form-control" id="nombre_proveedores" name="nombre_proveedores" value=""  placeholder="Nombre Proveedores" autocomplete="off" />
              <div id="mod_mensaje_nombre_proveedores" class="errores"></div>
			</div>
		  </div>
  
		  <div class="form-group">
			<label for="identificacion_proveedores" class="col-sm-3 control-label">Ruc Proveedores: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" minlength="10" maxlength="13" id="identificacion_proveedores" name="identificacion_proveedores" value=""  placeholder="Ruc Proveedores" >
              <div id="mod_mensaje_identificacion_proveedores" class="errores"></div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label for="contactos_proveedores" class="col-sm-3 control-label">Contactos Proveedores: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="contactos_proveedores" name="contactos_proveedores" value=""  placeholder="Contactos Proveedores">
                <div id="mod_mensaje_contactos_proveedores" class="errores"></div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label for="direccion_proveedores" class="col-sm-3 control-label">Dirección Proveedores: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="direccion_proveedores" name="direccion_proveedores" value=""  placeholder="Dirección Proveedores">
                <div id="mod_mensaje_direccion_proveedores" class="errores"></div>
			</div>
		  </div>
  
		  <div class="form-group">
			<label for="telefono_proveedores" class="col-sm-3 control-label">Teléfono Proveedores: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="telefono_proveedores" minlength="7" maxlength="10" name="telefono_proveedores" value=""  placeholder="Teléfono Proveedores" />
                  <div id="mod_mensaje_telefono_proveedores" class="errores"></div>  
			</div>
		  </div>
		  
		  <div class="form-group">
			<label for="email_proveedores" class="col-sm-3 control-label">Email Proveedores: </label>
			<div class="col-sm-8">
				<input type="email" class="form-control" id="email_proveedores" name="email_proveedores" value=""  placeholder="Email Proveedores" onKeyUp="javascript:validateMail('id_mail')" >
               <div id="mod_mensaje_email_proveedores" class="errores"></div>
			</div>
		  </div>
  
		</form>
		  
</div>

	<br>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		<button type="submit" form="frm_guardar_proveedor" class="btn btn-primary" id="guardar_datos">Guardar 		Datos</button>
	  </div>
	
	</div>
  </div>
</div>
	
	
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
     
   
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/plugins/iCheck/icheck.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="view/Contable/FuncionesJS/con_ccomprobantes.js?0.05"></script> 
    <!-- <script src="view/Contable/FuncionesJS/ComprobanteContable.js?5.0"></script>     -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
   

	
 </body>
</html>