<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
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
 	  
 	</style>
 	
 	 	<style type="text/css">
 	  .loader_detalle {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('view/images/ajax-loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
        }
 	  
 	</style>
   
  			        
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >

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
        <li><a href="<?php echo $helper->url("ffspUsuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sistemas</li>
      </ol>
    </section>   

   		   <section class="content">
     <div class="box box-primary">
     
           	<div class="box-header with-border">
      			<h3 class="box-title">Datos Principales</h3>      			
            </div>        
  		<div class="box-body">
  		
			<form id="frm_bitacora_sistemas" action="<?php echo $helper->url("BitacoraActividadesEmpleadosSistemas","index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
           	 <div class="row">
        		      <input type="hidden" name="id_bitacora_actividades_empleados_sistemas" id="id_bitacora_actividades_empleados_sistemas" value="0" />
        		      <div class="col-xs-12 col-md-3 col-md-3">
            		    <div class="form-group">
                          <label for="id_empleados" class="control-label">Empleado:</label>
                          <select  class="form-control" id="id_empleados" name="id_empleados" required readonly>
                          	<option value="<?php echo $rsEmpleados[0]->id_empleados; ?>"><?php echo $rsEmpleados[0]->nombres_empleados; ?></option>
                          </select>                         
                          <div id="mensaje_id_empleados" class="errores"></div>
                        </div>
            		  </div>
        		       <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="fecha_registro" class="control-label">Fecha:</label>
                          <input  type="date" class="form-control" id="fecha_registro" name="fecha_registro" value=""  placeholder="Fecha" required/>
                          <div id="mensaje_fecha_registro" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-3 col-md-3">
            		    <div class="form-group">
                          <label for="desde" class="control-label">Desde:</label>
                          <input  type="text" class="form-control" id="desde" name="desde" value="<?php echo $desde;?>"  placeholder="Desde" required readonly/>
                          <div id="mensaje_desde" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="hasta" class="control-label">Hasta:</label>
                          <input  type="text" class="form-control" id="hasta" name="hasta" value="<?php echo $hasta;?>"  placeholder="Hasta" required readonly/>
                          <div id="mensaje_hasta" class="errores"></div>
                        </div>
            		  </div>
            		  
                 
            	  
        		<div class="box box-warning">
           	<div class="box-header with-border">
      			<h3 class="box-title">Actividades</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
        	  		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="credito" class="control-label">Crédito:</label>.
                            <input  type="text" class="form-control" id="credito" name="credito" value=""  placeholder="Crédito" required />
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="prestaciones" class="control-label">Prestaciones:</label>.
                            <input  type="text" class="form-control" id="prestaciones" name="prestaciones" value=""  placeholder="Prestaciones" required />
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="recaudaciones" class="control-label">Recaudaciones:</label>.
                            <input  type="text" class="form-control" id="recaudaciones" name="recaudaciones" value=""  placeholder="Recaudaciones" required />
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="tesoreria" class="control-label">Tesorería:</label>.
                            <input  type="text" class="form-control" id="tesoreria" name="tesoreria" value=""  placeholder="Tesorería" required />
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="contabilidad" class="control-label">Contabilidad:</label>.
                            <input  type="text" class="form-control" id="prestaciones" name="contabilidad" value=""  placeholder="Contabilidad" required />
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="auditoria" class="control-label">Auditoría:</label>.
                            <input  type="text" class="form-control" id="auditoria" name="auditoria" value=""  placeholder="Auditoría" required />
                        </div>
            		  </div>
            		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="sistemas" class="control-label">Sistemas:</label>.
                            <input  type="text" class="form-control" id="sistemas" name="auditoria" value=""  placeholder="Sistemas" required />
                        </div>
            		  </div>
            		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="otras_actividades" class="control-label">Otras Actividades:</label>.
                            <input  type="text" class="form-control" id="otras_actividades" name="otras_actividades" value=""  placeholder="Otras Actividades" required />
                        </div>
            		  </div>
            		 
			      </div>
    	</div>
    	</div>
    		<div class="box box-danger">
           	<div class="box-header with-border">
      			<h3 class="box-title">Atención Participes</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
  		   	   <div class="col-xs-12 col-md-6 col-md-6">
            		    <div class="form-group">
            		    	<input  type="hidden"  id="hdn_id_participes" value="0" />
                          <label for="cedula_participes" class="control-label">Cedula Participes:</label>
                          <input  type="text" class="form-control" id="cedula_participes" name="cedula_participes" value=""  placeholder="Cedula" required />
                            
                        </div>
            		  </div>
            		  <div class="col-xs-12 col-md-6 col-md-6">
            		    <div class="form-group">
                          <label for="nombre_participes" class="control-label">Nombre Participes:</label>.
                            <input  type="text" class="form-control" id="nombre_participes" name="nombre_participes" value=""  placeholder="Nombre" required />
                        </div>
            		  </div>
            		</div>	
        	  		   <div class="col-xs-12 col-md-12 col-md-12 ">
            		    <div class="form-group">
                          <label for="motivo_atencion" class="control-label">Motivo de Atención:</label>.
                            <input  type="text" class="form-control" id="motivo_atencion" name="motivo_atencion" value=""  placeholder="Motivo de Atención:" required />
                        </div>
            		  </div>
        		  </div>	
			      </div>
    	</div>
   
							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("BitacoraActividadesEmpleadosSistemas","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
      			<h3 class="box-title">Bitacoras</h3>      			
            </div> 
            <div class="box-body">
           	<div class="pull-right" style="margin-right:10px;">
           	<div class="col-xs-12 col-md-12 col-lg-12 ">
			<div class="col-xs-12 col-md-3 col-lg-3 ">
			<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaBitacoraSistemas(1)" placeholder="Buscar.."/>
			</div> 
    		<div class="col-xs-12 col-md-4 col-lg-4 ">
			<input type="date" value="" class="form-control" id="fecha_registro_desde" name="fecha_registro_desde" onchange="consultaBitacoraSistemas(1)" placeholder="Buscar.."/>
			</div> 
    		<div class="col-xs-12 col-md-4 col-lg-4 ">
			<input type="date" value="" class="form-control" id="fecha_registro_hasta" name="fecha_registro_hasta" onchange="consultaBitacoraSistemas(1)" placeholder="Buscar.."/>
			</div>
			<div class="col-xs-12 col-md-1 col-lg-1 ">
				<button onclick="fnMostrarReporte()" id="btnReporte" class="btn btn-default no-padding"><input type="image" src="view/images/pdf.png" alt="Submit" width="50" height="34" formtarget="_blank" class="btn btn-default" title="Reporte Selección"></button>
		   	</div> 
    	 	</div> 
    		</div>            	
            <div id="bitacora_creditos_registrados" ></div>
            <div id="divLoaderPage" ></div>                     	
            </div> 	
          
      	</div>
      </section> 
    	     </div>
             </div>
         
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script> 
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/Core/js/BitacoraActividadesEmpleadosSistemas.js?0.01"></script> 
       
       

 	
	
	
  </body>
</html>   

