<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    
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
        
        .codigo {
        width: 15%;
        font-size:32px;
        text-align:center;
        }
        .observacion {
        width: 75%;
        }
        ul{
        list-style-type:none;
          }
      li{
        list-style-type:none;
        }
 
     
       
 	  
 	</style>
   <?php include("view/modulos/links_css.php"); ?>
  			        
    </head>
    <body id="cuerpo" class="hold-transition skin-blue fixed sidebar-mini"  >

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
        <li class="active">Buscar Participes</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Consulta y Cálculo de DESAFILIACIÓN</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
          <div class="box-body">
          	<div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Cedula:</label>
                		<div id="mensaje_cedula_participe" class="errores"></div>
                		<div class="input-group">
                			<input type="text" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_participe" name="cedula_participe" placeholder="C.I.">
                			
            				<span class="input-group-btn">
            			    	<button type="button" class="btn btn-primary" id="buscar_participe" name="buscar_participe" onclick="BuscarParticipe()">
        						<i class="glyphicon glyphicon-search"></i>
        						</button>
        						<button type="button" class="btn btn-danger" id="borrar_cedula" name="borrar_cedula" onclick="BorrarCedula()">
        						<i class="glyphicon glyphicon-arrow-left"></i>
        						</button>
        					</span>
        					
        				</div>
                 	</div>
             	</div>
           	</div>
           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="participe_encontrado" ></div>
           		</div>
           	</div>
           	   	<div class="row">
           	  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_fecha_prestaciones" class="control-label">Fecha Prestación:</label>
                          <input  type="date" class="form-control" id="fecha_prestaciones" name="fecha_prestaciones" value="<?php echo date(dd/MM/yyyy);?>"  placeholder="" required/>                         
                          <div id="mensaje_fecha_prestaciones" class="errores"></div>
                        </div>
            	</div>
           	  <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
            		    					  
                          <label for="id_tipo_prestaciones" class="control-label">Tipo Prestación:</label>
                          <select  class="form-control" id="id_tipo_prestaciones" name="id_tipo_prestaciones" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_tipo_pestaciones" class="errores"></div>
                        </div>
            	</div>

           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="aportes_participe" ></div>
           		</div>
           	</div>

           	
           		<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="aportes_participe_patronal" ></div>
           		</div>
           	</div>
           	
           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="creditos_participe" ></div>
           		</div>
           	</div>
           	
           	           	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
           		<div id="tabla_desafiliacion" ></div>
           		</div>
           	</div>
           	
      
           	
             	<div class="row">
           		<div class="col-xs-12 col-md-12 col-lg-12 ">
          	  <a id="link_reporte" onclick="reportePrint(this)" data-participe="0" href="#" class="btn btn-success" style="margin-top: 10px;"><i class=""></i>Reporte</a>
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
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

   <script src="view/Credito/js/BuscarParticipesCesantes.js?1.47"></script> 

   <script src="view/Credito/js/BuscarParticipesCesantes.js?0.70"></script> 

   </body>
</html>   