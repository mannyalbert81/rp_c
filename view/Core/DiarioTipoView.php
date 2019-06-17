    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
     
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  	 <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   
 
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
        <li class="active">Diarios Tipo</li>
      </ol>
     </section>
   

     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <i class='glyphicon glyphicon-edit'> Registrar Diarios Tipo</i>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
		      
	           	<div class="col-xs-12 col-lg-4 col-md-4 ">
	   				<div class="form-group">
    	              <label for="id_forma_pago" class="control-label">Tipo Crédito:</label>
    				  <select name="id_forma_pago" id="id_forma_pago"  class="form-control">
    				  <option value="0" selected="selected">--Seleccione--</option>
                         <?php foreach($resulTipoCredito as $res) {?>
    					  <option value="<?php echo $res->id_forma_pago; ?>" ><?php echo $res->nombre_forma_pago; ?> </option>
    					 <?php } ?>
    				   </select> 
                       <div id="mensaje_id_forma_pago" class="errores"></div>
                   </div>
             	</div> 
             
		      <div class="col-xs-12 col-md-4 col-lg-4">
		     <div class="form-group">
                  <label for="concepto_ccomprobantes" class="control-label">Nombre:</label>
                  <input type="text" class="form-control" id="descripcion_dcomprobantes" name="descripcion_dcomprobantes" value=""  placeholder="">
             <div id="mensaje_concepto_ccomprobantes" class="errores"></div> 
             </div> 
		     </div>
	
	       	<div class="col-xs-12 col-lg-4 col-md-4 ">
	   				<div class="form-group">
    	              <label for="id_forma_pago" class="control-label">Estado:</label>
    				  <select name="id_forma_pago" id="id_forma_pago"  class="form-control">
    				  <option value="0" selected="selected">--Seleccione--</option>
                         <?php foreach($resultFormaPago as $res) {?>
    					  <option value="<?php echo $res->id_forma_pago; ?>" ><?php echo $res->nombre_forma_pago; ?> </option>
    					 <?php } ?>
    				   </select> 
                       <div id="mensaje_id_forma_pago" class="errores"></div>
                   </div>
             	</div> 
              
		    
		    
  		     <div class="col-md-12 col-lg-12 col-xs-12">
  		    		<div class="pull-right">
					    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
						 <span class="glyphicon glyphicon-search"></span> Buscar Cuentas
						</button>
						
					</div>	
					
					<div style="text-align: center" > 
  		     			<button type="button" id="btn_inserta_comprobante" name="btn_inserta_comprobante" value="valor"  class="btn btn-success">Guardar Diario</button>
           			</div>
			 </div>	
	       </div>
	         <div class="col-lg-12 col-md-12 col-xs-12">
	         <div class="panel panel-primary">
	         <div class="box-header with-border">
             <i class='glyphicon glyphicon-edit'> Buscar Cuentas </i>
             </div>
	         <div class="panel-body">
  			 <div class="row">
  			 <div class="form-group" style="margin-top:13px">
             <div class="col-xs-2 col-md-2 col-lg-2">
                                  <label for="id_plan_cuentas" class="control-label" >#Cuenta: </label>
                                  <input type="text" class="form-control" id="id_plan_cuentas" name="id_plan_cuentas" value=""  placeholder="Search">
                                  <div id="mensaje_id_plan_cuentas" class="errores"></div>
                                  <input type="hidden" class="form-control" id="plan_cuentas" name="plan_cuentas" value="0"  placeholder="Search">
                                  <span class="help-block"></span>
             </div> 
             </div>
		     
		     <div class="form-group">  
		     <div class="col-xs-3 col-md-3 col-lg-3">                     
                                  <label for="nombre_plan_cuentas" class="control-label">Nombre: </label>
                                  <input type="text" class="form-control" id="nombre_plan_cuentas" name="nombre_plan_cuentas" value=""  placeholder="Search">
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
                                  <input type="text" class="form-control cantidades1" id="debe_dcomprobantes" name="debe_dcomprobantes" placeholder="0.00" value="0.00" readonly>
                                   <div id="mensaje_debe_dcomprobantes" class="errores"></div>
                                 
             </div>
		     </div>
		     
		     
		     <div class="form-group">
             <div class="col-xs-2 col-md-2 col-lg-2">
		                          <label for="haber_dcomprobantes" class="control-label">Haber: </label>
                                   <input type="text" class="form-control cantidades1" id="haber_dcomprobantes" name="haber_dcomprobantes" placeholder="0.00" value="0.00" readonly>
                                   <div id="mensaje_haber_dcomprobantes" class="errores"></div>
             </div>
		     </div>
		     </div>
		     
		     
		    <div class="row">
		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center;">
		    <div class="form-group">
                  <button type="button" onclick="agregar_temp_diario_tipo();" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
		    </div>
		    </div>
		    
		    <div class="row">
		            <div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_temp_diario_tipo" name="search_temp_diario_tipo" onkeyup="load_temp_diario_tipo_registrados(1)" placeholder="search.."/>
					</div>
					<div id="load_temp_diario_tipo_registrados" ></div>	
					<div id="temp_diario_tipo_registrados"></div>
					<div id="mensaje_detalle_dcomprobantes" class="errores"></div>	
		    </div>
		    </div>
	        </div>
	        </div>
	     
	      
		   <div class="row">
		   <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; margin-top:20px" > 
           <div class="form-group">
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
				  <div class="modal-header">
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
					
				  </div>
				<br>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				  </div>
				
				</div>
			  </div>
			</div>
		
	
	
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
     
   
    <?php include("view/modulos/links_js.php"); ?>
    
   
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="view/Core/FuncionesJS/DiarioTipo.js?2.1"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
   

	
 </body>
</html>