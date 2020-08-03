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
        <li class="active">Recaudaciones</li>
      </ol>
    </section>   

   		   <section class="content">
     <div class="box box-primary">
     
           	<div class="box-header with-border">
      			<h3 class="box-title">Datos Principales</h3>      			
            </div>        
  		<div class="box-body">
  		
			<form id="frm_bitacora_recaudaciones" action="<?php echo $helper->url("BitacoraActividadesEmpleadosRecaudaciones","index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
           	 <div class="row">
        		      <input type="hidden" name="id_bitacora_actividades_empleados_recaudaciones" id="id_bitacora_actividades_empleados_recaudaciones" value="0" />
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
            	  
        		<div class="box box-warning">
           	<div class="box-header with-border">
      			<h3 class="box-title">Revision de Documentos</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
        	  		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="cesantia" name="cesantia" value="0"> Cesantía </strong>
                          <div id="mensaje_cesantia" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="desafiliacion" name="desafiliacion" value="0"> Desafiliación </strong>
                          <div id="mensaje_desafiliacion" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="creditos_en_mora" name="creditos_en_mora" value="0"> Creditos en Mora </strong>
                          <div id="mensaje_creditos_en_mora" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="aportes" name="aportes" value="0"> Aportes </strong>
                          <div id="mensaje_aportes" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="diferimiento" name="diferimiento" value="0"> Diferimiento </strong>
                          <div id="mensaje_diferimiento" class="errores"></div>
                        </div>
            		  </div>
         		  </div>	
			      </div>
    	</div>
    		<div class="box box-default">
           	<div class="box-header with-border">
      			<h3 class="box-title">Trabajo Ejecutado</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
        	  		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="moras" name="moras" value="0"> Moras </strong>
                          <div id="mensaje_moras" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="credito" name="credito" value="0"> Crédito </strong>
                          <div id="mensaje_credito" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="aporte" name="aporte" value="0"> Aporte </strong>
                          <div id="mensaje_aporte" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="envio_archivo_entidad_patronal" name="envio_archivo_entidad_patronal" value="0"> Envio Archivo Entidad Patronal </strong>
                          <div id="mensaje_envio_archivo_entidad_patronal" class="errores"></div>
                        </div>
            		  </div>
            		      <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="recepcion_archivo_entidad_patronal" name="recepcion_archivo_entidad_patronal" value="0"> Recepción Archivo Entidad Patronal </strong>
                          <div id="mensaje_diferimiento" class="errores"></div>
                        </div>
            		  </div>
            		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="carga_archivo_banco" name="carga_archivo_banco" value="0"> Carga Archivo Banco </strong>
                          <div id="mensaje_carga_archivo_banco" class="errores"></div>
                        </div>
            		  </div>  
            		  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="carga_archivo_sistema" name="carga_archivo_sistema" value="0"> Carga Archivo Sistema </strong>
                          <div id="mensaje_carga_archivo_sistema" class="errores"></div>
                        </div>
            		  </div>
            		  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="registro_depositos_manuales" name="registro_depositos_manuales" value="0"> Registro Depositos Manuales </strong>
                          <div id="mensaje_registro_depositos_manuales" class="errores"></div>
                        </div>
            		  </div>
            		  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="identificacion_dsc" name="identificacion_dsc" value="0"> Identificación DSC </strong>
                          <div id="mensaje_identificacion_dsc" class="errores"></div>
                        </div>
            		  </div>
         		  </div>	
			      </div>
    	</div>
    		<div class="box box-danger">
           	<div class="box-header with-border">
      			<h3 class="box-title">Datos</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
        	  			  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="elaboracion_memorando" class="control-label">Elaboración Memorando de:</label>.
                            <input  type="text" class="form-control" id="elaboracion_memorando" name="elaboracion_memorando" value=""  placeholder="Memorando" required />
                        </div>
            		  </div>
            		  	  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <label for="otras_actividades_desarrolladas" class="control-label">Otras Actividades Desarrolladas:</label>.
                            <input  type="text" class="form-control" id="otras_actividades_desarrolladas" name="otras_actividades_desarrolladas" value=""  placeholder="Actividades" required />
                        </div>
            		  </div>
        		  </div>	
			      </div>
    	</div>
    	
    		<div class="box box-success">
           	<div class="box-header with-border">
      			<h3 class="box-title">Atención Participes</h3>      			
            </div>        
  		<div class="box-body">
  		   	 <div class="row">
        	  		    <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_cesantias" name="atencion_cesantias" value="0"> Cesantías</strong>
                          <div id="mensaje_atencion_cesantias" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_desafiliaciones" name="atencion_desafiliaciones" value="0"> Desafiliaciones</strong>
                          <div id="mensaje_atencion_desafiliaciones" class="errores"></div>
                        </div>
            		  </div>
            		     <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_creditos_en_mora" name="atencion_creditos_en_mora" value="0"> Créditos en Mora</strong>
                          <div id="mensaje_atencion_cesantias" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_aportes" name="atencion_aportes" value="0"> Aportes</strong>
                          <div id="mensaje_atencion_aportes" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_diferimiento" name="atencion_diferimiento" value="0"> Diferimiento</strong>
                          <div id="mensaje_atencion_diferimiento" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="atencion_refinanciamiento_reestructuracion" name="atencion_refinanciamiento_reestructuracion" value="0"> Refinanciamiento y Reestructuracion</strong>
                          <div id="mensaje_atencion_refinanciamiento_reestructuracion" class="errores"></div>
                        </div>
            		  </div>
            		   <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="claves" name="claves" value="0"> Claves</strong>
                          <div id="mensaje_claves" class="errores"></div>
                        </div>
            		  </div>
            		  <div class="col-xs-12 col-md-6 col-md-6 ">
            		    <div class="form-group">
                          <strong><input class="seleccionado" type="checkbox" id="consultas_varias" name="consultas_varias" value="0"> Consultas Varias</strong>
                          <div id="mensaje_consultas_varias" class="errores"></div>
                        </div>
            		  </div>
            		  
        		  </div>	
			      </div>
    	</div>
            	  
							          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    <div class="form-group">
    	                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	                  <a href="<?php echo $helper->url("BitacoraActividadesEmpleadosRecaudaciones","Index"); ?>" class="btn btn-danger">CANCELAR</a>
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
			<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaBitacoraCreditos(1)" placeholder="Buscar.."/>
			</div> 
    		<div class="col-xs-12 col-md-4 col-lg-4 ">
			<input type="date" value="" class="form-control" id="fecha_registro_desde" name="fecha_registro_desde" onchange="consultaBitacoraRecaudaciones(1)" placeholder="Buscar.."/>
			</div> 
    		<div class="col-xs-12 col-md-4 col-lg-4 ">
			<input type="date" value="" class="form-control" id="fecha_registro_hasta" name="fecha_registro_hasta" onchange="consultaBitacoraRecaudaciones(1)" placeholder="Buscar.."/>
			</div>
			<div class="col-xs-12 col-md-1 col-lg-1 ">
				<button onclick="fnMostrarReporte()" id="btnReporte" class="btn btn-default no-padding"><input type="image" src="view/images/pdf.png" alt="Submit" width="50" height="34" formtarget="_blank" class="btn btn-default" title="Reporte Selección"></button>
		   	</div> 
    	 	</div> 
    		</div>            	
            <div id="bitacora_recaudaciones_registrados" ></div>
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
   <script src="view/Core/js/BitacoraActividadesEmpleadosRecaudaciones.js?0.04"></script> 
       
       

 	
	
	
  </body>
</html>   

