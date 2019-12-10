
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
			
			<div class="col-xs-6 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="nombre" class="control-label">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" 
                  value=""  placeholder="Nombre ">
                  <input type="hidden" name="" id="" value="" class="form-control"/>
                </div>
		    </div>
		    <div class="col-xs-6 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="valor_procesado" class="control-label">Valor procesado</label>
                  <input type="number" class="form-control" id="valor_procesado" name="valor_procesado" 
                  value=""  placeholder="valor_procesado" onKeyPress="return numeros(event)">
                </div>
		    </div>
		</div>
		
		    <div class="row">
		    		    
		    <div class="col-xs-6 col-md-3 col-md-3 ">
    		    <div class="form-group">
                  <label for="valor_ejecutado" class="control-label">Valor ejecutado</label>
                  <input type="number" class="form-control" id="valor_ejecutado" name="valor_ejecutado" 
                  value=""  placeholder="valor_ejecutado">
                </div>
		    </div>		    
    		<div class="col-xs-6 col-md-3 col-lg-3">
                  <div class="form-group">
                  <label for="id_plan_cuentas" class="control-label">Estado:</label>
                  <select name="plan_cuentas" id="plan_cuentas"  class="form-control" >
                  <option value="0" selected="selected">--Seleccione--</option>
    									
  				 </select> 
                 <div id="mensaje_plan_cuentas" class="errores"></div>
                 </div>
            </div>
		</div>
         
		   <br>  
    	    <div class="row">
           	 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="">GUARDAR</button>
                  <button type="button" class="btn btn-danger" id="Cancelar" name="Cancelar" onclick="">CANCELAR</button>
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
  			<h3 class="box-title">Listado de Presupuestos</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_empleados(1)" placeholder="Buscar.."/>
			</div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="" id="estado_empleados"  class="form-control" onchange="load_empleados(1)">
                  		<?php  foreach($resultEst as $res) {?>
						<option value="<?php echo $res->id_estado; ?>"><?php echo $res->nombre_estado; ?> </option>
			        	<?php } ?>
    				</select>
    		</div>
        	<div id="load_empleados" ></div>
        	<div id="empleados_registrados" ></div>
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
	<script src="view/Contable/FuncionesJS/Presupuestos.js?0.00"></script>
  </body>
</html>   