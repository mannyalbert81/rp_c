<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
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
   <?php include("view/modulos/links_css.php"); ?>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   	
	<link href="view/bootstrap/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" /> 
	
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
            <li class="active">Participes</li>
          </ol>
        </section>
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Registrar Participes</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
                <form id="frm_participes" action="<?php echo $helper->url("Participes","InsertaParticipes"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
          		 	 <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
              		 
              		 <div id="smartwizard">
                  		  <ul>
                            <li><a href="#step-1">Información del Socio <br /><small> </small></a></li>
                            <li><a href="#step-2">Datos del Cónyuge<br /><small></small></a></li>
                            <li><a href="#step-3">Datos Domiciliarios<br /><small></small></a></li>
                            <li><a href="#step-4">Información Adicional<br /><small></small></a></li>
                            <li><a href="#step-5">Información Extra<br /><small></small></a></li>
                            <li><a href="#step-6">Cuenta Bancaria <br /><small></small></a></li>
                            <li><a href="#step-7">Contribución<br /><small></small></a></li>
                     
                        </ul>		
           			
           			<div>
                	<div id="step-1" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
                    		   <div class="col-xs-12 col-md-3 col-lg-3">
                                    		   <div class="form-group">
                                      <label for="id_entidad_patronal" class="control-label">Entidad Patronal:</label>
                                      <select name="id_entidad_patronal" id="id_entidad_patronal"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultEntidadPatronal as $res) {?>
    										<option value="<?php echo $res->id_entidad_patronal; ?>" <?php if ($res->id_entidad_patronal == $resEdit->id_entidad_patronal)  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_entidad_patronal; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_entidad_patronal" class="errores"></div>
                                    </div>
                                  </div>
                                  
                              	<div class="col-xs-12 col-lg-3 col-md-3 ">
                                	<div class="form-group">
                                    	<label for="fecha_entrada_patronal_participes" class="control-label">Fecha Entrada Patronal</label>
                                        <input type="date" class="form-control" id="fecha_entrada_patronal_participes" name="fecha_entrada_patronal_participes" value="<?php echo $resEdit->fecha_entrada_patronal_participes; ?>"  placeholder="Fecha">
                                        <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
        					            <div id="mensaje_fecha_entrada_patronal_participes" class="errores"></div>
                                     </div>
                                  </div>
                              	<div class="col-xs-12 col-lg-3 col-md-3 ">
                                	<div class="form-group">
                                    	<label for="cedula_participes" class="control-label">Cedula</label>
                                        <input type="text" class="form-control" id="cedula_participes" name="cedula_participes" value="<?php echo $resEdit->cedula_participes ; ?>"  placeholder="Cedula">
                                        <div id="mensaje_cedula_participes" class="errores"></div>
                                     </div>
                                  </div>
                              	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="observacion_participes" class="control-label">Observación</label>
                                    <input type="text" class="form-control" id="observacion_participes" name="observacion_participes" value="<?php echo $resEdit->observacion_participes; ?>"  placeholder="Observación">
                                    <div id="mensaje_observacion_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                       	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="codigo_alternativo_participes" class="control-label">Código Alternativo</label>
                                    <input type="text" class="form-control" id="codigo_alternativo_participes" name="codigo_alternativo_participes" value="<?php echo $resEdit->codigo_alternativo_participes; ?>"  placeholder="Código Alternativo">
                                    <div id="mensaje_codigo_alternativo_participes" class="errores"></div>
                                 </div>
                                </div>
                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="apellido_participes" class="control-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellido_participes" name="apellido_participes" value="<?php echo $resEdit->apellido_participes; ?>"  placeholder="Apellidos">
                                    <div id="mensaje_apellido_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                        	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="nombre_participes" class="control-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_participes" name="nombre_participes" value="<?php echo $resEdit->nombre_participes; ?>"  placeholder="Nombres">
                                    <div id="mensaje_nombre_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_nacimiento_participes" class="control-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento_participes" name="fecha_nacimiento_participes" value="<?php echo $resEdit->fecha_nacimiento_participes; ?>"  placeholder="Fecha Nacimiento">
                                    <div id="mensaje_fecha_nacimiento_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                           <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_genero_participes" class="control-label">Genero:</label>
                                      <select name="id_genero_participes" id="id_genero_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultGenero as $res) {?>
    										<option value="<?php echo $res->id_genero_participes; ?>" <?php if ($res->id_genero_participes == $resEdit->id_genero_participes )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_genero_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_genero_participes" class="errores"></div>
                                    </div>
                                  </div>
                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="ocupacion_participes" class="control-label">Ocupación</label>
                                    <input type="text" class="form-control" id="ocupacion_participes" name="ocupacion_participes" value="<?php echo $resEdit->ocupacion_participes; ?>"  placeholder="Ocupación">
                                    <div id="mensaje_ocupacion_participes" class="errores"></div>
                                 </div>
                                </div>
                                                                                          <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_tipo_instruccion_participes" class="control-label">Instrucción:</label>
                                      <select name="id_tipo_instruccion_participes" id="id_tipo_instruccion_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultTipoInstrccion as $res) {?>
    										<option value="<?php echo $res->id_tipo_instruccion_participes; ?>" <?php if ($res->id_tipo_instruccion_participes == $resEdit->id_tipo_instruccion_participes )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_tipo_instruccion_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_instruccion_participes" class="errores"></div>
                                    </div>
                                     </div>
                                                                  <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado_civil_participes" class="control-label">Estado Civil:</label>
                                      <select name="id_estado_civil_participes" id="id_estado_civil_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultEstadoCivil as $res) {?>
    										<option value="<?php echo $res->id_estado_civil_participes; ?>" <?php if ($res->id_estado_civil_participes == $resEdit->id_estado_civil_participes )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_estado_civil_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estado_civil_participes" class="errores"></div>
                                    </div>
                                  </div>
                                                          	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="correo_participes" class="control-label">Correo</label>
                                    <input type="text" class="form-control" id="correo_participes" name="correo_participes" value="<?php echo $resEdit->correo_participes; ?>"  placeholder="Correo">
                                    <div id="mensaje_correo_participes" class="errores"></div>
                                 </div>
                                  </div>
                         
                			
                	
                	
                		</div>
                 	</div>
                  	</div>
                  </div>
                    <div id="step-2" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                                  
                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="nombre_conyugue_participes" class="control-label">Nombre Conyugue</label>
                                    <input type="text" class="form-control" id="nombre_conyugue_participes" name="nombre_conyugue_participes" value="<?php echo $resEdit->nombre_conyugue_participes; ?>"  placeholder="Nombre Conyugue">
                                    <div id="mensaje_nombre_conyugue_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                        	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="apellido_esposa_participes" class="control-label">Apellido Conyugue</label>
                                    <input type="text" class="form-control" id="apellido_esposa_participes" name="apellido_esposa_participes" value="<?php echo $resEdit->apellido_esposa_participes; ?>"  placeholder="Apellido Conyugue">
                                    <div id="mensaje_apellido_esposa_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="cedula_conyugue_participes" class="control-label">Cédula Conyugue</label>
                                    <input type="text" class="form-control" id="cedula_conyugue_participes" name="cedula_conyugue_participes" value="<?php echo $resEdit->cedula_conyugue_participes; ?>"  placeholder="Cedula Conyugue">
                                   <div id="mensaje_cedula_conyugue_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                                                                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="numero_dependencias_participes" class="control-label">Numero de cargas familiares</label>
                                    <input type="text" class="form-control" id="numero_dependencias_participes" name="numero_dependencias_participes" value="<?php echo $resEdit->numero_dependencias_participes; ?>"  placeholder="Numero Dependencias">
                                    <div id="mensaje_numero_dependencias_participes" class="errores"></div>
                                 </div>
                                </div>
                                      
                         
                			
                	
                	
                </div>
                 </div>
                  </div>
                  </div>
                   <div id="step-3" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                             <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_ciudades" class="control-label">Cuidad:</label>
                                      <select name="id_ciudades" id="id_ciudades"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultCiudades as $res) {?>
    										<option value="<?php echo $res->id_ciudades; ?>" <?php if ($res->id_ciudades == $resEdit->id_ciudades )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_ciudades; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_ciudades" class="errores"></div>
                                    </div>
                                  </div>       
                    		
                	 
                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="direccion_participes" class="control-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion_participes" name="direccion_participes" value="<?php echo $resEdit->direccion_participes; ?>"  placeholder="Dirección">
                                    <div id="mensaje_direccion_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                             	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="celular_participes" class="control-label">Teléfono</label>
                                    <input type="text" class="form-control" id="celular_participes" name="celular_participes" value="<?php echo $resEdit->celular_participes; ?>"  placeholder="Celular">
                                    <div id="mensaje_telefono_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="telefono_participes" class="control-label">Celular</label>
                                    <input type="text" class="form-control" id="telefono_participes" name="telefono_participes" value="<?php echo $resEdit->telefono_participes; ?>"  placeholder="Teléfono">
                                    <div id="mensaje_celular_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                	
                </div>
                 </div>
                  </div>
                  </div>
                       <div id="step-4" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                           
                                           	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_ingreso_participes" class="control-label">Fecha Ingreso</label>
                                    <input type="date" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $resEdit->fecha_ingreso_participes; ?>"  placeholder="Fecha Ingreso">
                                    <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                            	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_defuncion_participes" class="control-label">Fecha Defunción</label>
                                    <input type="date" class="form-control" id="fecha_defuncion_participes" name="fecha_defuncion_participes" value="<?php echo $resEdit->fecha_defuncion_participes; ?>"  placeholder="Fecha Defunción">
                                    <div id="mensaje_fecha_defuncion_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                        <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado_participes" class="control-label">Estado Participes:</label>
                                      <select name="id_estado_participes" id="id_estado_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultEstado as $res) {?>
    										<option value="<?php echo $res->id_estado_participes; ?>" <?php if ($res->id_estado_participes == $resEdit->id_estado_participes )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_estado_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estado_participes" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                        <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estatus" class="control-label">Estatus:</label>
                                      <select name="id_estatus" id="id_estatus"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultEstatus as $res) {?>
    										<option value="<?php echo $res->id_estatus; ?>" <?php if ($res->id_estatus == $resEdit->id_estatus )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_estatus; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estatus" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                                 	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_salida_participes" class="control-label">Fecha Salida</label>
                                    <input type="date" class="form-control" id="fecha_salida_participes" name="fecha_salida_participes" value="<?php echo $resEdit->fecha_salida_participes; ?>"  placeholder="Fecha Salida">
                                    <div id="mensaje_fecha_salida_participes" class="errores"></div>
                                 </div>
                                  </div>
                                         	<div class="col-xs-12 col-lg-3 col-md-3 ">  
                                 	<div class="form-group">
                                	<label for="fecha_numero_orden_participes" class="control-label">Fecha Número Orden</label>
                                    <input type="date" class="form-control" id="fecha_numero_orden_participes" name="fecha_numero_orden_participes" value="<?php echo $resEdit->fecha_numero_orden_participes; ?>"  placeholder="Fecha Número Orden">
                                    <div id="mensaje_fecha_numero_orden_participes" class="errores"></div>
                                 </div>
                                </div>
                              
                                  
                	   
                </div>
     
                 </div>
                  </div>
                  </div>
                              <div id="step-5" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
        		      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_distritos" class="control-label">Distritos:</label>
                                      <select name="id_distritos" id="id_distritos"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultDistritos as $res) {?>
    										<option value="<?php echo $res->id_distritos; ?>" <?php if ($res->id_distritos == $resEdit->id_distritos )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_distritos; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_distritos" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                  		      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_provincias" class="control-label">Provincias:</label>
                                      <select name="id_provincias" id="id_provincias"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultProvincias as $res) {?>
    										<option value="<?php echo $res->id_provincias; ?>" <?php if ($res->id_provincias == $resEdit->id_provincias )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_provincias; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_provincias" class="errores"></div>
                                    </div>
                                  </div>
                              
                             
                                     	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="parroquia_participes_informacion_adicional" class="control-label">Parroquia</label>
                                    <input type="text" class="form-control" id="parroquia_participes_informacion_adicional" name="parroquia_participes_informacion_adicional" value="<?php echo $resEdit->parroquia_participes_informacion_adicional; ?>"  placeholder="Parroquia">
                                    <div id="mensaje_parroquia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>     

                                     	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="sector_participes_informacion_adicional" class="control-label">Sector</label>
                                    <input type="text" class="form-control" id="sector_participes_informacion_adicional" name="sector_participes_informacion_adicional" value="<?php echo $resEdit->sector_participes_informacion_adicional; ?>"  placeholder="Sector">
                                    <div id="mensaje_sector_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>   
                                       
                                	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="ciudadela_participes_informacion_adicional" class="control-label">Ciudadela</label>
                                    <input type="text" class="form-control" id="ciudadela_participes_informacion_adicional" name="ciudadela_participes_informacion_adicional" value="<?php echo $resEdit->ciudadela_participes_informacion_adicional; ?>"  placeholder="Ciudadela">
                                    <div id="mensaje_ciudadela_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                  
                                     	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="calle_participes_informacion_adicional" class="control-label">Calle</label>
                                    <input type="text" class="form-control" id="calle_participes_informacion_adicional" name="calle_participes_informacion_adicional" value="<?php echo $resEdit->calle_participes_informacion_adicional; ?>"  placeholder="Calle">
                                    <div id="mensaje_calle_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                          
                 			  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="numero_calle_participes_informacion_adicional" class="control-label">Número de calle</label>
                                    <input type="text" class="form-control" id="numero_calle_participes_informacion_adicional" name="numero_calle_participes_informacion_adicional" value="<?php echo $resEdit->numero_calle_participes_informacion_adicional; ?>"  placeholder="Número de calle">
                                    <div id="mensaje_numero_calle_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                  	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="interseccion_participes_informacion_adicional" class="control-label">Intersección</label>
                                    <input type="text" class="form-control" id="interseccion_participes_informacion_adicional" name="interseccion_participes_informacion_adicional" value="<?php echo $resEdit->interseccion_participes_informacion_adicional; ?>"  placeholder="Intersección">
                                    <div id="mensaje_interseccion_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>  
                   
                    		           
                    		  		      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_tipo_vivienda" class="control-label">Tipo Vivienda:</label>
                                      <select name="id_tipo_vivienda" id="id_tipo_vivienda"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultTipovivienda as $res) {?>
    										<option value="<?php echo $res->id_tipo_vivienda; ?>" <?php if ($res->id_tipo_vivienda == $resEdit->id_tipo_vivienda )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_tipo_vivienda; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_vivienda" class="errores"></div>
                                    </div>
                                  </div>  
                                  
                             
                                  	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="anios_residencia_participes_informacion_adicional" class="control-label">Años de residencia</label>
                                    <input type="text" class="form-control" id="anios_residencia_participes_informacion_adicional" name="anios_residencia_participes_informacion_adicional" value="<?php echo $resEdit->anios_residencia_participes_informacion_adicional; ?>"  placeholder="Años de residencia">
                                    <div id="mensaje_anios_residencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                   
                                 	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="nombre_propietario_participes_informacion_adicional" class="control-label">Nombre propietario</label>
                                    <input type="text" class="form-control" id="nombre_propietario_participes_informacion_adicional" name="nombre_propietario_participes_informacion_adicional" value="<?php echo $resEdit->nombre_propietario_participes_informacion_adicional; ?>"  placeholder="Nombre">
                                    <div id="mensaje_nombre_propietario_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>  
               
                                 	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="telefono_propietario_participes_informacion_adicional" class="control-label">Teléfono propietario</label>
                                    <input type="text" class="form-control" id="telefono_propietario_participes_informacion_adicional" name="telefono_propietario_participes_informacion_adicional" value="<?php echo $resEdit->telefono_propietario_participes_informacion_adicional; ?>"  placeholder="Teléfono">
                                    <div id="mensaje_telefono_propietario_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>  
                                   	
                             
                             	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="direccion_referencia_participes_informacion_adicional" class="control-label">Dirección referencia</label>
                                    <input type="text" class="form-control" id="direccion_referencia_participes_informacion_adicional" name="direccion_referencia_participes_informacion_adicional" value="<?php echo $resEdit->direccion_referencia_participes_informacion_adicional; ?>"  placeholder="Dirección">
                                    <div id="mensaje_direccion_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                            
                             	  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="vivienda_hipotecada_participes_informacion_adicional" class="control-label">Vivienda Hipotecada</label>
                                    <input type="text" class="form-control" id="vivienda_hipotecada_participes_informacion_adicional" name="vivienda_hipotecada_participes_informacion_adicional" value="<?php echo $resEdit->vivienda_hipotecada_participes_informacion_adicional; ?>"  placeholder="Vivienda">
                                    <div id="mensaje_vivienda_hipotecada_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                  
                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="nombre_una_referencia_participes_informacion_adicional" class="control-label">Nombre referencia</label>
                                    <input type="text" class="form-control" id="nombre_una_referencia_participes_informacion_adicional" name="nombre_una_referencia_participes_informacion_adicional" value="<?php echo $resEdit->nombre_una_referencia_participes_informacion_adicional; ?>"  placeholder="Nombre">
                                    <div id="mensaje_nombre_una_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                  
                                  	      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_parentesco" class="control-label">Parentesco:</label>
                                      <select name="id_parentesco" id="id_parentesco"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultParentesco as $res) {?>
    										<option value="<?php echo $res->id_parentesco; ?>" <?php if ($res->id_parentesco == $resEdit->id_parentesco )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_parentesco; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_parentesco" class="errores"></div>
                                    </div>
                                  </div>  
                                  
                                               	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="telefono_una_referencia_participes_informacion_adicional" class="control-label">Teléfono referencia</label>
                                    <input type="text" class="form-control" id="telefono_una_referencia_participes_informacion_adicional" name="telefono_una_referencia_participes_informacion_adicional" value="<?php echo $resEdit->telefono_una_referencia_participes_informacion_adicional; ?>"  placeholder="Teléfono">
                                    <div id="mensaje_telefono_una_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                                   	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="observaciones_participes_informacion_adicional" class="control-label">Observación</label>
                                    <input type="text" class="form-control" id="observaciones_participes_informacion_adicional" name="observaciones_participes_informacion_adicional" value="<?php echo $resEdit->observaciones_participes_informacion_adicional; ?>"  placeholder="Observación">
                                    <div id="mensaje_observaciones_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                  
                                                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="kit_participes_informacion_adicional" class="control-label">Kit</label>
                                    <input type="text" class="form-control" id="kit_participes_informacion_adicional" name="kit_participes_informacion_adicional" value="<?php echo $resEdit->kit_participes_informacion_adicional; ?>"  placeholder="Kit">
                                    <div id="mensaje_kit_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                  
                                                                     	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="contrato_adhesion_participes_informacion_adicional" class="control-label">Contrato de adhesión</label>
                                    <input type="text" class="form-control" id="contrato_adhesion_participes_informacion_adicional" name="contrato_adhesion_participes_informacion_adicional" value="<?php echo $resEdit->contrato_adhesion_participes_informacion_adicional; ?>"  placeholder="Contrato">
                                    <div id="mensaje_contrato_adhesion_participes_informacion_adicional" class="errores"></div>
                                 </div>
                                  </div> 
                                        
                	
                      </div>
                      <div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Actualizar</button>
        	                      <a href="index.php?controller=Participes&action=index" class="btn btn-primary"><i class='glyphicon glyphicon-remove'></i> Cancelar</a>
        	                    </div>
    	        		    </div>
            		    </div>
                 </div>
                  </div>
                  </div> 
                                       <div id="step-6" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                         <div class="row">
                                    <div class="col-md-12">
                                    <div class="panel panel-default">
  								   <div class="panel-body">
                        		   <div class="col-md-3 col-lg-3 col-xs-12">
                                      <label for="id_bancos" class="control-label">Bancos:</label>
                                      <select name="id_bancos" id="id_bancos"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultBancos as $res) {?>
    										<option value="<?php echo $res->id_bancos; ?>" ><?php echo $res->nombre_bancos; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_bancos" class="errores"></div>
                                    </div>
                                    
                                                             
                                <div class="col-md-3 col-lg-3 col-xs-12">
                                      <label for="id_tipo_cuentas" class="control-label">Tipo Cuentas:</label>
                                      <select name="id_tipo_cuentas" id="id_tipo_cuentas"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultTipoCuentas as $res) {?>
    										<option value="<?php echo $res->id_tipo_cuentas; ?>" ><?php echo $res->nombre_tipo_cuentas; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_cuentas" class="errores"></div>
                                    </div>
                           
                                
                                 <div class="col-md-3 col-lg-3 col-xs-12">
                                	<label for="numero_participes_cuentas" class="control-label">Número de cuenta</label>
                                    <input type="text" class="form-control" id="numero_participes_cuentas" name="numero_participes_cuentas" value=""  placeholder="Número de cuenta">
                                    <div id="mensaje_numero_participes_cuentas" class="errores"></div>
                                 </div>
                             
                               	 <div class="col-md-3 col-lg-3 col-xs-12">
                                      <label for="cuenta_principal" class="control-label">Cuenta Principal:</label>
                                      <select name="cuenta_principal" id="cuenta_principal"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    								<option value="TRUE">SI</option>
			  						<option value="FALSE">NO</option>
    								   </select> 
                                      <div id="mensaje_id_tipo_cuentas" class="errores"></div>
                                    </div>
                                    
        
                                  
                     <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                 	   		    <div class="form-group">
            	                  <button type="button" id="Procesar" name="Procesar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Guardar</button>
        	          
         </div>
        	                     </div>
        	                           </div>
        	                     
        	                     </div>
        	                     </div>
    	        			       <div class="col-md-12">
    	        			       
    	        			              <div class="panel panel-default">
  								   <div class="panel-body">
                        	
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="txtsearchcuentas" name="txtsearchcuentas" onkeyup="load_cuentas_activos(1)" placeholder="search.."/>
					</div>
							<div id="load_cuentas_activos" ></div>	
					<div id="participes_cuentas_registrados"></div>	
                
                
              </div>
                  </div>
            </div>      
              
                 </div>
                 </div>      
              
                 </div>
                 
                 
                  </div>
                  </div>
                  
                                  </div>
                                  
					<div id="step-7" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                         <div class="row">
                         		   <div class="panel-body">
                        		<div class="col-md-3 col-lg-3 col-xs-12">
	              					<label for="id_contribucion_tipo" class="control-label">Tipo Contribución</label>
                                      <select name="id_contribucion_tipo" id="id_contribucion_tipo"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultContribucion as $res) {?>
    										<option value="<?php echo $res->id_contribucion_tipo; ?>" ><?php echo $res->nombre_contribucion_tipo; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_contribucion_tipo" class="errores"></div>
                                    </div>
                                    
					         	<div class="col-md-3 col-lg-3 col-xs-12">
	              					<label for="id_tipo_aportacion" class="control-label">Tipo Aportación</label>
                                      <select name="id_tipo_aportacion" id="id_tipo_aportacion"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultTipoAportacion as $res) {?>
    										<option value="<?php echo $res->id_tipo_aportacion; ?>" ><?php echo $res->nombre_tipo_aportacion; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_aportacion" class="errores"></div>
                                    </div>
                            
                                	<div class="col-md-2 col-lg-2 col-xs-12">
	              					<label for="id_estado" class="control-label">Estado</label>
                                      <select name="id_estado" id="id_estado"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEstadoContribucion as $res) {?>
    										<option value="<?php echo $res->id_estado; ?>" ><?php echo $res->nombre_estado; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estado" class="errores"></div>
                                    </div>
                        	<div class="col-md-2 col-lg-2 col-xs-12">
	              		        	<label for="valor_contribucion_tipo_participes" class="control-label">Valor</label>
                                    <input type="text" class="form-control" id="valor_contribucion_tipo_participes" name="valor_contribucion_tipo_participes" value=""  placeholder="$">
                                    <div id="mensaje_valor_contribucion_tipo_participes" class="errores"></div>
                                 </div>
                             
                                   	<div class="col-md-2 col-lg-2 col-xs-12">
	              		        	<label for="sueldo_liquido_contribucion_tipo_participes" class="control-label">Sueldo</label>
                                    <input type="text" class="form-control" id="sueldo_liquido_contribucion_tipo_participes" name="sueldo_liquido_contribucion_tipo_participes" value=""  placeholder="$">
                                    <div id="mensaje_sueldo_liquido_contribucion_tipo_participes" class="errores"></div>
                                 </div>
                                 
                                
                                    
                             	<div id="div_porcentaje" class="col-md-3 col-lg-3 col-xs-12">
	              		        	<label for="porcentaje_contribucion_tipo_participes" class="control-label">Porcentaje</label>
                                    <input type="text" class="form-control" id="porcentaje_contribucion_tipo_participes" name="porcentaje_contribucion_tipo_participes" value=""  placeholder="%">
                                    <div id="mensaje_porcentaje_contribucion_tipo_participes" class="errores"></div>
                                 </div>
        
                                  
                     <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                 	              <button type="button" id="Generar" name="Generar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Guardar</button>
        	                     </div>
        	                           </div>
        	                     
        	         		       <div class="col-xs-12 col-md-12 col-md-12">
    	        			       
    	        			              <div class="panel panel-default">
  								   <div class="panel-body">
                        	
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="txtsearchcontribuciontipo" name="txtsearchcuentas" onkeyup="load_contribucion_tipo(1)" placeholder="search.."/>
					</div>
							<div id="load_contribucion_tipo" ></div>	
							<div id="contribucion_tipo_registrados"></div>	
                
                
              </div>
                  </div>
            </div>      
              
                 </div>
                 </div>      
              
                 </div>
                 
                 
                  </div>
                  </div>
                  
                                  </div>
                   </div>
                      <?php } } else {?>                		    
                      	
                   <div id="smartwizard">
              		  <ul>
                        <li><a href="#step-1">Información del Socio <br /><small> </small></a></li>
                        <li><a href="#step-2">Datos del Cónyuge<br /><small></small></a></li>
                        <li><a href="#step-3">Datos Domiciliarios<br /><small></small></a></li>
                        <li><a href="#step-4">Información Extra Socio <br /><small></small></a></li>
                        <li><a href="#step-5">Información Adicional del Socio <br /><small></small></a></li>
                 
                    </ul>
           	<div>
                <div id="step-1" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
                    	
                    	             <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_entidad_patronal" class="control-label">Entidad Patronal:</label>
                                      <select name="id_entidad_patronal" id="id_entidad_patronal"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEntidadPatronal as $res) {?>
    										<option value="<?php echo $res->id_entidad_patronal; ?>" ><?php echo $res->nombre_entidad_patronal; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_entidad_patronal" class="errores"></div>
                                    </div>
                                  </div>
                                                                      <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_entrada_patronal_participes" class="control-label">Fecha Entrada Patronal</label>
                                  <input type="date" class="form-control" id="fecha_entrada_patronal_participes" name="fecha_entrada_patronal_participes" value=""  placeholder="Fecha">
                                  <input type="hidden" name="id_participes" id="id_participes" value="0" class="form-control"/>
        					      <div id="mensaje_fecha_entrada_patronal_participes" class="errores"></div>
                                 </div>
                             </div>  
                             
                                       
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="cedula_participes" class="control-label">Cedula</label>
                                  <input type="text" class="form-control" id="cedula_participes" name="cedula_participes" value=""  placeholder="Cedula">
                                  <div id="mensaje_cedula_participes" class="errores"></div>
                                 </div>
                             </div>
        		   
        		                           <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="observacion_participes" class="control-label">Observación</label>
                                  <input type="text" class="form-control" id="observacion_participes" name="observacion_participes" value=""  placeholder="Observación">
                                  <div id="mensaje_observacion_participes" class="errores"></div>
                                 </div>
                             </div>  
                                                                      <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="codigo_alternativo_participes" class="control-label">Código Alternativo</label>
                                  <input type="text" class="form-control" id="codigo_alternativo_participes" name="codigo_alternativo_participes" value=""  placeholder="Código Alternativo">
                                  <div id="mensaje_codigo_alternativo_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                               	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="apellido_participes" class="control-label">Apellido</label>
                                  <input type="text" class="form-control" id="apellido_participes" name="apellido_participes" value=""  placeholder="Apellido">
                                  <div id="mensaje_apellido_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                 <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="nombre_participes" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_participes" name="nombre_participes" value=""  placeholder="Nombres">
                                  <div id="mensaje_nombre_participes" class="errores"></div>
                                 </div>
                             </div>
                                   <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_nacimiento_participes" class="control-label">Fecha Nacimiento</label>
                                  <input type="date" class="form-control" id="fecha_nacimiento_participes" name="fecha_nacimiento_participes" value=""  placeholder="Fecha Nacimiento">
                                  <div id="mensaje_fecha_nacimiento_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                               <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_genero_participes" class="control-label">Genero:</label>
                                      <select name="id_genero_participes" id="id_genero_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultGenero as $res) {?>
    										<option value="<?php echo $res->id_genero_participes; ?>" ><?php echo $res->nombre_genero_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_genero_participes" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                    <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="ocupacion_participes" class="control-label">Ocupación</label>
                                  <input type="text" class="form-control" id="ocupacion_participes" name="ocupacion_participes" value=""  placeholder="Ocupación">
                                  <div id="mensaje_correo_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                           
                                     <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_tipo_instruccion_participes" class="control-label">Instrucción:</label>
                                      <select name="id_tipo_instruccion_participes" id="id_tipo_instruccion_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultTipoInstrccion as $res) {?>
    										<option value="<?php echo $res->id_tipo_instruccion_participes; ?>" ><?php echo $res->nombre_tipo_instruccion_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_instruccion_participes" class="errores"></div>
                                    </div>
                                  </div>  
                          
                               <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado_civil_participes" class="control-label">Estado Civil:</label>
                                      <select name="id_estado_civil_participes" id="id_estado_civil_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEstadoCivil as $res) {?>
    										<option value="<?php echo $res->id_estado_civil_participes; ?>" ><?php echo $res->nombre_estado_civil_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estado_civil_participes" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                                                 <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="correo_participes" class="control-label">Correo</label>
                                  <input type="text" class="form-control" id="correo_participes" name="correo_participes" value=""  placeholder="Correo">
                                  <div id="mensaje_correo_participes" class="errores"></div>
                                 </div>
                             </div>  
                                  
                   
                              
                	 
                </div>
                 </div>
                  </div>
                  </div>
                    <div id="step-2" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 			
                	  <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="nombre_conyugue_participes" class="control-label">Nombre Conyugue</label>
                                  <input type="text" class="form-control" id="nombre_conyugue_participes" name="nombre_conyugue_participes" value=""  placeholder="Nombre Conyugue">
                                  <div id="mensaje_nombre_conyugue_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="apellido_esposa_participes" class="control-label">Apellido Conyugue</label>
                                  <input type="text" class="form-control" id="apellido_esposa_participes" name="apellido_esposa_participes" value=""  placeholder="Apellido Conyugue">
                                  <div id="mensaje_apellido_esposa_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                               <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="cedula_conyugue_participes" class="control-label">Cedula Conyugue</label>
                                  <input type="text" class="form-control" id="cedula_conyugue_participes" name="cedula_conyugue_participes" value=""  placeholder="Cedula Conyugue">
                                  <div id="mensaje_cedula_conyugue_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                                           <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="numero_dependencias_participes" class="control-label">Número Dependencias</label>
                                  <input type="text" class="form-control" id="numero_dependencias_participes" name="numero_dependencias_participes" value=""  placeholder="Número Dependencias">
                                  <div id="mensaje_numero_dependencias_participes" class="errores"></div>
                                 </div>
                             </div> 
                	
                </div>
                 </div>
                  </div>
                  </div>
                   <div id="step-3" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_ciudades" class="control-label">Cuidad:</label>
                                      <select name="id_ciudades" id="id_ciudades"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultCiudades as $res) {?>
    										<option value="<?php echo $res->id_ciudades; ?>" ><?php echo $res->nombre_ciudades; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_ciudades" class="errores"></div>
                                    </div>
                                  </div>
                                  
                              <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="direccion_participes" class="control-label">Dirección</label>
                                  <input type="text" class="form-control" id="direccion_participes" name="direccion_participes" value=""  placeholder="Dirección">
                                  <div id="mensaje_cedula_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                  <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="telefono_participes" class="control-label">Teléfono</label>
                                  <input type="text" class="form-control" id="telefono_participes" name="telefono_participes" value=""  placeholder="Teléfono">
                                  <div id="mensaje_telefono_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="celular_participes" class="control-label">Celular</label>
                                  <input type="text" class="form-control" id="celular_participes" name="celular_participes" value=""  placeholder="Celular">
                                  <div id="mensaje_celular_participes" class="errores"></div>
                                 </div>
                             </div>      
                	
                </div>
                 </div>
                  </div>
                  </div>
                       <div id="step-4" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                           
                               <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_ingreso_participes" class="control-label">Fecha Ingreso</label>
                                  <input type="date" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value=""  placeholder="Fecha Ingreso">
                                  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                                               <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_defuncion_participes" class="control-label">Fecha Defunción</label>
                                  <input type="date" class="form-control" id="fecha_defuncion_participes" name="fecha_defuncion_participes" value=""  placeholder="Fecha Defunción">
                                  <div id="mensaje_fecha_defuncion_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                           <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado_participes" class="control-label">Estado Participes:</label>
                                      <select name="id_estado_participes" id="id_estado_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEstado as $res) {?>
    										<option value="<?php echo $res->id_estado_participes; ?>" ><?php echo $res->nombre_estado_participes; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estado_participes" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                   <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estatus" class="control-label">Estatus:</label>
                                      <select name="id_estatus" id="id_estatus"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEstatus as $res) {?>
    										<option value="<?php echo $res->id_estatus; ?>" ><?php echo $res->nombre_estatus; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estatus" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                 <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_salida_participes" class="control-label">Fecha Salida</label>
                                  <input type="date" class="form-control" id="fecha_salida_participes" name="fecha_salida_participes" value=""  placeholder="Fecha Salida">
                                  <div id="mensaje_fecha_salida_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_numero_orden_participes" class="control-label">Fecha Número Orden</label>
                                  <input type="date" class="form-control" id="fecha_numero_orden_participes" name="fecha_numero_orden_participes" value=""  placeholder="Fecha Número Orden">
                                  <div id="mensaje_fecha_numero_orden_participes" class="errores"></div>
                                 </div>
                             </div> 
                    		                       
                	
                      </div>
                    
                 </div>
                  </div>
                  </div>
                  
                                   <div id="step-5" class="">
                
                	<div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title"></h3>
                          <div class="box-tools pull-right"> </div>
                        </div>
                    
                    <div class="box-body">
                    	<div class="row">
        		 
                           
                                <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_distritos" class="control-label">Distritos:</label>
                                      <select name="id_distritos" id="id_distritos"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultDistritos as $res) {?>
    										<option value="<?php echo $res->id_distritos; ?>" ><?php echo $res->nombre_distritos; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_distritos" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                      <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_provincias" class="control-label">Provincias:</label>
                                      <select name="id_provincias" id="id_provincias"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultProvincias as $res) {?>
    										<option value="<?php echo $res->id_provincias; ?>" ><?php echo $res->nombre_provincias; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_provincias" class="errores"></div>
                                    </div>
                                  </div>
                                      
                        
                          
                                  
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="parroquia_participes_informacion_adicional" class="control-label">Parroquia:</label>
                                  <input type="text" class="form-control" id="parroquia_participes_informacion_adicional" name="parroquia_participes_informacion_adicional" value=""  placeholder="Parroquia">
                                  <div id="mensaje_parroquia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>
                             
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="sector_participes_informacion_adicional" class="control-label">Sector:</label>
                                  <input type="text" class="form-control" id="sector_participes_informacion_adicional" name="sector_participes_informacion_adicional" value=""  placeholder="Sector">
                                  <div id="mensaje_sector_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>
                             
                           
                             
                                                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="ciudadela_participes_informacion_adicional" class="control-label">Ciudadela</label>
                                  <input type="text" class="form-control" id="ciudadela_participes_informacion_adicional" name="ciudadela_participes_informacion_adicional" value=""  placeholder="Ciudadela">
                                  <div id="mensaje_ciudadela_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                                                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="calle_participes_informacion_adicional" class="control-label">Calle</label>
                                  <input type="text" class="form-control" id="calle_participes_informacion_adicional" name="calle_participes_informacion_adicional" value=""  placeholder="Calle">
                                  <div id="mensaje_calle_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                                                                            <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="numero_calle_participes_informacion_adicional" class="control-label">Número de Calle</label>
                                  <input type="text" class="form-control" id="numero_calle_participes_informacion_adicional" name="numero_calle_participes_informacion_adicional" value=""  placeholder="N° de Calle">
                                  <div id="mensaje_numero_calle_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                             
                                                                           <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="interseccion_participes_informacion_adicional" class="control-label">Intersección</label>
                                  <input type="text" class="form-control" id="interseccion_participes_informacion_adicional" name="interseccion_participes_informacion_adicional" value=""  placeholder="Intersección">
                                  <div id="mensaje_interseccion_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                    		           
                    		             <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_tipo_vivienda" class="control-label">Tipo Vivienda:</label>
                                      <select name="id_tipo_vivienda" id="id_tipo_vivienda"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultTipovivienda as $res) {?>
    										<option value="<?php echo $res->id_tipo_vivienda; ?>" ><?php echo $res->nombre_tipo_vivienda; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_tipo_vivienda" class="errores"></div>
                                    </div>
                                  </div>   
                                  
                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for=anios_residencia_participes_informacion_adicional class="control-label">Años Residencia</label>
                                  <input type="text" class="form-control" id="anios_residencia_participes_informacion_adicional" name="anios_residencia_participes_informacion_adicional" value=""  placeholder="Años Residencia">
                                  <div id="mensaje_anios_residencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>          
                	
                	      <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="nombre_propietario_participes_informacion_adicional" class="control-label">Nombre Propietario</label>
                                  <input type="text" class="form-control" id="nombre_propietario_participes_informacion_adicional" name="nombre_propietario_participes_informacion_adicional" value=""  placeholder="Nombre">
                                  <div id="mensaje_nombre_propietario_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>          
                	
                	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="telefono_propietario_participes_informacion_adicional" class="control-label">Teléfono Propietario</label>
                                  <input type="text" class="form-control" id="telefono_propietario_participes_informacion_adicional" name="telefono_propietario_participes_informacion_adicional" value=""  placeholder="Teléfono">
                                  <div id="mensaje_telefono_propietario_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>       
                             
                              	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="direccion_referencia_participes_informacion_adicional" class="control-label">Dirección Referencia</label>
                                  <input type="text" class="form-control" id="direccion_referencia_participes_informacion_adicional" name="direccion_referencia_participes_informacion_adicional" value=""  placeholder="Dirección">
                                  <div id="mensaje_direccion_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>      
                             
                             
                                                        	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="vivienda_hipotecada_participes_informacion_adicional" class="control-label">Vivienda Hipotecada</label>
                                  <input type="text" class="form-control" id="vivienda_hipotecada_participes_informacion_adicional" name="vivienda_hipotecada_participes_informacion_adicional" value=""  placeholder="Vivienda Hipotecada">
                                  <div id="mensaje_vivienda_hipotecada_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                                                           	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="nombre_una_referencia_participes_informacion_adicional" class="control-label">Nombre Referencia</label>
                                  <input type="text" class="form-control" id="nombre_una_referencia_participes_informacion_adicional" name="nombre_una_referencia_participes_informacion_adicional" value=""  placeholder="Referencia">
                                  <div id="mensaje_nombre_una_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div> 
                             
                             	             <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_parentesco" class="control-label">Parentesco:</label>
                                      <select name="id_parentesco" id="id_parentesco"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultParentesco as $res) {?>
    										<option value="<?php echo $res->id_parentesco; ?>" ><?php echo $res->nombre_parentesco; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_parentesco" class="errores"></div>
                                    </div>
                                  </div>       
                                  
                                                                             	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="telefono_una_referencia_participes_informacion_adicional" class="control-label">Teléfono una Referencia</label>
                                  <input type="text" class="form-control" id="telefono_una_referencia_participes_informacion_adicional" name="telefono_una_referencia_participes_informacion_adicional" value=""  placeholder="Teléfono Referencia">
                                  <div id="mensaje_telefono_una_referencia_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>   
                             
                                                                                	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="observaciones_participes_informacion_adicional" class="control-label">Observaciones</label>
                                  <input type="text" class="form-control" id="observaciones_participes_informacion_adicional" name="observaciones_participes_informacion_adicional" value=""  placeholder="Observaciones">
                                  <div id="mensaje_observaciones_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>     
                             
                                                                                                	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="kit_participes_informacion_adicional" class="control-label">Kit Participes</label>
                                  <input type="text" class="form-control" id="kit_participes_informacion_adicional" name="kit_participes_informacion_adicional" value=""  placeholder="Kit">
                                  <div id="mensaje_kit_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>    
                             
                                                                             	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="contrato_adhesion_participes_informacion_adicional" class="control-label">Contrato Adhesión</label>
                                  <input type="text" class="form-control" id="contrato_adhesion_participes_informacion_adicional" name="contrato_adhesion_participes_informacion_adicional" value=""  placeholder="Contrato de Adhesión">
                                  <div id="mensaje_contrato_adhesion_participes_informacion_adicional" class="errores"></div>
                                 </div>
                             </div>
                                   
                	
                      </div>
               <div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="button" id="Guardar" name="Guardar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Guardar</button>
        	                      <a href="index.php?controller=Participes&action=index" class="btn btn-primary"><i class='glyphicon glyphicon-remove'></i> Cancelar</a>
        	                    </div>
    	        		    </div>
            		    </div>
                 </div>
                  </div>
                  </div>
                  
                      
                  </div>
                   
              		 </div>
                      	 
                             
                                     
                     <?php } ?>
                     	
                     	
          		 	
          		 	</form>
          
        			</div>
      			</div>
    		</section>
    		
    <!-- seccion para el listado de roles -->
      <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Listado Participes</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Participes Activos</a></li>
            <!--  <li><a href="#inactivos" data-toggle="tab">Participes Inactivos</a></li> --> 
              <li><a href="#desafiliado" data-toggle="tab">Participes Desafiliado</a></li>
              <li><a href="#liquidado_cesante" data-toggle="tab">Participes Liquidado Cesante</a></li>
         
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_participes_activos(1)" placeholder="search.."/>
					</div>
					<div id="load_participes_activos" ></div>	
					<div id="participes_activos_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="inactivos">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_inactivos" name="search_inactivos" onkeyup="load_participes_inactivos(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_participes_inactivos" ></div>	
					<div id="participes_inactivos_registrados"></div>
              </div>
      
                
                 <div class="tab-pane" id="desafiliado">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_desafiliado" name="search_desafiliado" onkeyup="load_participes_desafiliado(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_participes_desafiliado" ></div>	
					<div id="participes_desafiliado_registrados"></div>
              </div>
              
              <div class="tab-pane" id="liquidado_cesante">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_liquidado_cesante" name="search_liquidado_cesante" onkeyup="load_participes_liquidado_cesante(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_participes_liquidado_cesante" ></div>	
					<div id="participes_liquidado_cesante_registrados"></div>
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
    <script type="text/javascript" src="view/bootstrap/smartwizard/dist/js/jquery.smartWizard.min.js"></script>
 <script type="text/javascript" src="view/Core/js/wizardParticipes.js?0.26"></script>
 <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
 <script src="view/Core/js/Participes.js?3.60"></script>
 
 </body>
</html>