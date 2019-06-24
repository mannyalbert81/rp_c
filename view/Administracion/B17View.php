<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
    .scrollable-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
}

	ul{
        list-style-type:none;
      }
  li{
    list-style-type:none;
    }
    
    </style>
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   <?php include("view/modulos/links_css.php"); ?>
   


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
            <li class="active">Formulario B17</li>
          </ol>
        </section>
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Buscar/Generar Formato</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            <div class="box-body">
                <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Mes:</label>
                    	<select name="estado_cargo" id="mes_reporte"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
    								  <?php  foreach($meses as $key=>$res) {?>
									  <option value="<?php echo $key; ?>"><?php echo $res; ?> </option>
			        				  <?php } ?>
    					</select> 
                        <div id="mensaje_mes_balance" class="errores"></div>
                 	</div>
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="dpto_empleados" class="control-label">Año:</label>
              			<input type=number step=1 class="form-control" id="a_reporte" name="a_reporte" value="2019">
                        <div id="mensaje_estado_cargo" class="errores"></div>
                 	</div>
             	</div>
          	</div>
            <div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Buscar" name="Buscar" class="btn btn-primary" onclick="BuscarReporte2()"><i class="glyphicon glyphicon-search"></i> BUSCAR</button>
                </div>
             </div>	    
            </div>
        	</div>
      	</div>
    </section>
    		
    <!-- seccion para el listado de roles -->

            <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Reporte preliminar</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
				<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_departamentos(1)" placeholder="Buscar.."/>
			</div>
			
        	<div id="load_cuentas" ></div>
        	<div id="plan_cuentas2" ></div>
           
         
            </div>
            </div>
            </section>
  		</div>
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
   <?php include("view/modulos/links_js.php"); ?>
   <script src="view/Administracion/js/B17.js?0.12" ></script>
  </body>

</html>


	