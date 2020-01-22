
    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> 
    <link rel="stylesheet" href="view/bootstrap/bower_components/select2/dist/css/select2.min.css">
  
    <?php include("view/modulos/links_css.php"); ?>		
      
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Productos</li>
      </ol>
    </section>


    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Presupuesto</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
          
        
        <form id="frm_presupuestos" action="<?php echo $helper->url("Presupuestos","Index"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        
            <div class="row">
            
            <div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="id_plan_cuentas" class="control-label">Código Plan de Cuentas:</label>
                    	<input type="text" class="form-control" id="id_plan_cuentas" name="id_plan_cuentas" placeholder="Número.." >
                        <div id="mensaje_id_plan_cuentas" class="errores"></div>
                 	</div>
             	</div>
		    <div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_presupuestos_cabeza" class="control-label">Nombre Plan Cuentas:</label>
                    	<input type="text" class="form-control" id="nombre_presupuestos_cabeza" name="nombre_presupuestos_cabeza" placeholder="Nombre.." >
                         <input type="hidden" name="id_plan_cuentas_1" id="id_plan_cuentas_1" />
                        <div id="mensaje_nombre_presupuestos_cabeza" class="errores"></div>
                 	</div>
             	</div>
		    <div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="mes_presupuestos_detalle" class="control-label">Mes:</label>
                    	<select name="mes_presupuestos_detalle" id="mes_presupuestos_detalle"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
                                      <option value="01">Enero</option>
        							  <option value="02">Febrero</option>
        							  <option value="03">Marzo</option>
        							  <option value="04">Abril</option>
        							  <option value="05">Mayo</option>
        							  <option value="06">Junio</option>
        							  <option value="07">Julio</option>
        							  <option value="08">Agosto</option>
        							  <option value="09">septiembre</option>
        							  <option value="10">Octubre</option>
        							  <option value="11">Noviembre</option>
        							  <option value="12">Diciembre</option>
        							  
                        </select> 
                        <div id="mensaje_mes_presupuestos_detalle" class="errores"></div>
                 	</div>
             	</div>
			
			<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="anio_presupuestos_detalle" class="control-label">Año:</label>
                    	<input type="text" class="form-control" id="anio_presupuestos_detalle" name="anio_presupuestos_detalle" placeholder="Año.." value="<?php echo $anio[0]; ?>" readonly="readonly">
                        <div id="mensaje_anio_presupuestos_detalle" class="errores"></div>
                 	</div>
             	</div>
			</div>
			
			
			<div class="row">
			
			
		    <div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="valor_presupuestado_presupuestos_detalle" class="control-label">Valor Presupuestado:</label>
                    	<input type="text" class="form-control" id="valor_presupuestado_presupuestos_detalle" name="valor_presupuestado_presupuestos_detalle" placeholder="valor.." onkeypress="return NumCheck(event, this)">
                        <div id="mensaje_plazo_meses_datos_credito" class="errores"></div>
                 	</div>
             	</div>
		
		    		    
		    <div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="valor_ejecutado_presupuestos_detalle" class="control-label">Valor Ejecutado:</label>
                    	<input type="text" class="form-control" id="valor_ejecutado_presupuestos_detalle" name="valor_ejecutado_presupuestos_detalle"  placeholder="valor.." onkeypress="return NumCheck(event, this)">
                        <div id="mensaje_valor_ejecutado_presupuestos_detalle" class="errores"></div>
                 	</div>
             	</div>
		    
		  	
		    
    		
		</div>
         
		   <br>  
    	    <div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarPresupuestos()">GUARDAR</button>
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
              <h3 class="box-title">Presupuestos Registrados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <br>
              <div class="tab-pane active" id="presupuestos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_buscar_presupuestos" name="search_buscar_presupuestos" onkeyup="load_buscar_presupuestos(1)" placeholder="search.."/>
					</div>
					<div id="load_buscar_presupuestos" ></div>	
					<div id="presupuestos_registrados"></div>	
                
              </div>
                <a href="index.php?controller=Presupuestos&action=reporte_presupuestos" target="_blank"><input type="image" src="view/images/print.png" alt="Submit" width="50" height="34" formtarget="_blank" id="btngenerar" name="btngenerar" class="btn btn-default" title="Reporte Productos"></label></a>
         
            </div>
            </div>
            </section>
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	
	<script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	<script src="view/bootstrap/bower_components/select2/dist/js/select2.full.min.js"></script> 
	<script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script src="view/Contable/FuncionesJS/Presupuestos.js?3.0"></script>
  </body>
</html>   