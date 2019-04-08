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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Contabilidad</a></li>
        <li class="active">Activos Fijos</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Activos Fijos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
        
        <form action="<?php echo $helper->url("ActivosFijos","insActivos"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        	
        	<div class="row">
    		  
    		   <div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                      <label for="id_oficina" class="control-label">Oficina:</label>
                      <select name="id_oficina" id="id_oficina"  class="form-control">
                        <option value="0" selected="selected">--Seleccione--</option>
							<?php foreach($resultOfi as $res) {?>
							<option value="<?php echo $res->id_oficina; ?>" ><?php echo $res->nombre_oficina; ?> </option>
				            <?php } ?>
					  </select>
					  <div id="mensaje_id_oficina" class="errores"></div>
                </div>
                </div> 
              
				<div class="col-xs-12 col-md-3 col-lg-3">
    		    <div class="form-group">
                                   
                  <label for="id_tipo_activos_fijos" class="control-label">Tipo Activos Fijos:</label>
                  <select name="id_tipo_activos_fijos" id="id_tipo_activos_fijos"  class="form-control">
                    <option value="0" selected="selected">--Seleccione--</option>
						<?php foreach($resultTipoac as $res) {?>
						<option value="<?php echo $res->id_tipo_activos_fijos; ?>"> <?php echo $res->nombre_tipo_activos_fijos; ?> </option>
			            <?php } ?>
				  </select>
				  <div id="mensaje_id_tipo_activos_fijos" class="errores"></div>
                </div>
                </div>  
                
                <div class="col-xs-12 col-lg-3 col-md-3">
    		    <div class="form-group">
                                   
                  <label for="id_departamento" class="control-label">Departamento:</label>
                  <select name="id_departamento" id="id_departamento"  class="form-control">
                    <option value="0" selected="selected">--Seleccione--</option>
						<?php foreach($rsDepartamento as $res) {?>
						<option value="<?php echo $res->id_departamento; ?>"><?php echo $res->nombre_departamento; ?> </option>
			            <?php } ?>
				  </select>
				  <div id="mensaje_id_departamento" class="errores"></div>
                </div>
                </div>  
                
                <div class="col-xs-12 col-md-3 col-md-3">
    		    <div class="form-group">
                                   
                  <label for="id_estado" class="control-label">Estado:</label>
                  <select name="id_estado" id="id_estado"  class="form-control">
                    <option value="0" selected="selected">--Seleccione--</option>
						<?php foreach($rsEstadoAct as $res) {?>
						<option value="<?php echo $res->id_estado; ?>" ><?php echo $res->nombre_estado; ?> </option>
			            <?php } ?>
				  </select>
				  <div id="mensaje_id_estado" class="errores"></div>
                </div>
                </div> 
               </div>
    		    
    		   <div class="row">
    		   
    		   <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Fecha Compra:</label>
                      <input type="text" class="form-control" id="fecha_activos_fijos" name="fecha_activos_fijos" value="<?php echo date('Y-m-d');?>" >
                      <div id="mensaje_fecha_activos_fijos" class="errores"></div>
                </div>
    		    </div>
                
                <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="responsable_activos_fijos" class="control-label">Responsable:</label>
                      <input type="text" class="form-control" id="responsable_activos_fijos" name="responsable_activos_fijos" value=""  placeholder="Responsable">
                      <input type="hidden" name="id_activos_fijos" id="id_activos_fijos" value="" class="form-control"/>
                      <div id="mensaje_codigo_activos_fijos" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="nombre_activos_fijos" class="control-label">Nombre Activo:</label>
                      <input type="text" class="form-control" id="nombre_activos_fijos" name="nombre_activos_fijos" value=""  placeholder="Nombre...">
                      <div id="mensaje_nombre_activos_fijos" class="errores"></div>
                </div>
    		    </div>
    		    
    		    
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="valor_activos_fijos" class="control-label">Valor Activo:</label>
                      <input type="text" class="form-control" id="valor_activos_fijos" name="valor_activos_fijos" value="" >
                      <div id="mensaje_valor_activos_fijos" class="errores"></div>
                </div>
    		    </div>
    		    
    		    <div class="col-lg-3 col-xs-12 col-md-3">
    		    <div class="form-group">
                  <label for="imagen_activos_fijos" class="control-label">Imagen Activos:</label>
                  <input type="file" class="form-control" id="imagen_activos_fijos" name="imagen_activos_fijos" value="">
                  <div id="mensaje_imagen_activos_fijos" class="errores"></div>
                </div>
    		    </div> 
    		    
    		    
                
             </div>
             
             <hr>
             
             <div class="row" >
             	<div class="col-xs-12 col-md-12 col-lg-12 ">
    		    <div class="form-group">
                      <label for="fecha_activos_fijos" class="control-label">Detalles Activo:</label>
                      <textarea class="form-control" id="detalle_activos_fijos" name="detalle_activos_fijos"  rows="2" cols=""></textarea>
                      <div id="mensaje_detalle_activos_fijos" class="errores"></div>
                </div>
    		    </div>
             </div>
             
		    <div class="row">
		    	<div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
		    		<div class="form-group">
              			<button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class="fa " aria-hidden="true"></i>Registrar Activo</button>
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
              <h3 class="box-title">Listado Activos Fijos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Activos Fijos</a></li>
              
            </ul>
            
            <div class="col-md-5 col-lg-12 col-xs-5">
            <div class="tab-content">
            
            <br>
              <div class="tab-pane active" id="activos">
              
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_activos_fijos(1)" placeholder="search.."/>
						
					</div>
					<div id="load_activos_fijos" ></div>
					<div id="activos_fijos_registrados"></div>	
                <button type="button" id="exportar" name="exportar" value="Exportar"   class="btn btn-primary" ><i class="fa fa-file-excel-o"></i></button>
               </div>
    		 </div>
            </div>
          </div>
            </div>
            </div>
            </section>
            
    
     
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    
    <script type="text/javascript" src="view/Activos/js/activosFijos.js?1.0"></script>
	
    <script type="text/javascript" >   
    
    	function numeros(e){
    		  var key = window.event ? e.which : e.keyCode;
    		  if (key < 48 || key > 57) {
    		    e.preventDefault();
    		  }
     }
    </script> 
    
   
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
	  

	function mayus(e) {

    var tecla=e.value;
    var tecla2=tecla.toUpperCase();
    
    alert(tecla2);
}  
	
  </body>
</html>   

 