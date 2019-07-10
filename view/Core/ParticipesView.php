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
                            <li><a href="#step-4">Información adicional del Socio <br /><small></small></a></li>
                     
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
                                        <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
        					            <div id="mensaje_cedula_participes" class="errores"></div>
                                     </div>
                                  </div>
                              	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="observacion_participes" class="control-label">Observación</label>
                                    <input type="text" class="form-control" id="observacion_participes" name="observacion_participes" value="<?php echo $resEdit->observacion_participes; ?>"  placeholder="Observación">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_observacion_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                       	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="codigo_alternativo_participes" class="control-label">Código Alternativo</label>
                                    <input type="text" class="form-control" id="codigo_alternativo_participes" name="codigo_alternativo_participes" value="<?php echo $resEdit->codigo_alternativo_participes; ?>"  placeholder="Código Alternativo">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_codigo_alternativo_participes" class="errores"></div>
                                 </div>
                                </div>
                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="apellido_participes" class="control-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellido_participes" name="apellido_participes" value="<?php echo $resEdit->apellido_participes; ?>"  placeholder="Apellidos">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_apellido_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                        	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="nombre_participes" class="control-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_participes" name="nombre_participes" value="<?php echo $resEdit->nombre_participes; ?>"  placeholder="Nombres">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_nombre_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_nacimiento_participes" class="control-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento_participes" name="fecha_nacimiento_participes" value="<?php echo $resEdit->fecha_nacimiento_participes; ?>"  placeholder="Fecha Nacimiento">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_ocupacion_participes" class="errores"></div>
                                 </div>
                                </div>
                                                                                          <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_tipo_instruccion_participes" class="control-label">Instrucción:</label>
                                      <select name="id_tipo_instruccion_participes" id="id_tipo_instruccion_participes"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultTipoInstrccion as $res) {?>
    										<option value="<?php echo $res->id_entidad_patronal; ?>" <?php if ($res->id_tipo_instruccion_participes == $resEdit->id_tipo_instruccion_participes )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_tipo_instruccion_participes; ?> </option>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_nombre_conyugue_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                        	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="apellido_esposa_participes" class="control-label">Apellido Conyugue</label>
                                    <input type="text" class="form-control" id="apellido_esposa_participes" name="apellido_esposa_participes" value="<?php echo $resEdit->apellido_esposa_participes; ?>"  placeholder="Apellido Conyugue">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_apellido_esposa_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="cedula_conyugue_participes" class="control-label">Cédula Conyugue</label>
                                    <input type="text" class="form-control" id="cedula_conyugue_participes" name="cedula_conyugue_participes" value="<?php echo $resEdit->cedula_conyugue_participes; ?>"  placeholder="Cedula Conyugue">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_cedula_conyugue_participes" class="errores"></div>
                                 </div>
                                </div>
                                
                                                                                                    	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="numero_dependencias_participes" class="control-label">Numero de cargas familiares</label>
                                    <input type="text" class="form-control" id="numero_dependencias_participes" name="numero_dependencias_participes" value="<?php echo $resEdit->numero_dependencias_participes; ?>"  placeholder="Numero Dependencias">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_direccion_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                             	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="celular_participes" class="control-label">Teléfono</label>
                                    <input type="text" class="form-control" id="celular_participes" name="celular_participes" value="<?php echo $resEdit->celular_participes; ?>"  placeholder="Celular">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_telefono_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                  	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="telefono_participes" class="control-label">Celular</label>
                                    <input type="text" class="form-control" id="telefono_participes" name="telefono_participes" value="<?php echo $resEdit->telefono_participes; ?>"  placeholder="Teléfono">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                                 </div>
                                  </div>
                                  
                                            	<div class="col-xs-12 col-lg-3 col-md-3 ">
                            	<div class="form-group">
                                	<label for="fecha_defuncion_participes" class="control-label">Fecha Defunción</label>
                                    <input type="date" class="form-control" id="fecha_defuncion_participes" name="fecha_defuncion_participes" value="<?php echo $resEdit->fecha_defuncion_participes; ?>"  placeholder="Fecha Defunción">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
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
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_fecha_salida_participes" class="errores"></div>
                                 </div>
                                  </div>
                                         	<div class="col-xs-12 col-lg-3 col-md-3 ">  
                                 	<div class="form-group">
                                	<label for="fecha_numero_orden_participes" class="control-label">Fecha Número Orden</label>
                                    <input type="date" class="form-control" id="fecha_numero_orden_participes" name="fecha_numero_orden_participes" value="<?php echo $resEdit->fecha_numero_orden_participes; ?>"  placeholder="Fecha Número Orden">
                                    <input type="hidden" name="id_participes" id="id_participes" value="<?php echo $resEdit->id_participes; ?>" class="form-control"/>
    					            <div id="mensaje_fecha_numero_orden_participes" class="errores"></div>
                                 </div>
                                </div>
                              
                                  
                	   
                </div>
               <div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Actualizar</button>
        	                      <a href="index.php?controller=Participes&action=index" class="btn btn-primary"><i class='glyphicon glyphicon-remove'></i> Cancelar</a>
        	                    </div>
    	        		    </div>
            		    </div>
                 </div>
                  </div>
                  </div>                  </div>
                   </div>
                      <?php } } else {?>                		    
                      	
                   <div id="smartwizard">
              		  <ul>
                        <li><a href="#step-1">Información del Socio <br /><small> </small></a></li>
                        <li><a href="#step-2">Datos del Cónyuge<br /><small></small></a></li>
                        <li><a href="#step-3">Datos Domiciliarios<br /><small></small></a></li>
                        <li><a href="#step-4">Información adicional del Socio <br /><small></small></a></li>
                 
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_fecha_entrada_patronal_participes" class="errores"></div>
                                 </div>
                             </div>  
                             
                                       
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="cedula_participes" class="control-label">Cedula</label>
                                  <input type="text" class="form-control" id="cedula_participes" name="cedula_participes" value=""  placeholder="Cedula">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_cedula_participes" class="errores"></div>
                                 </div>
                             </div>
        		   
        		                           <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="observacion_participes" class="control-label">Observación</label>
                                  <input type="text" class="form-control" id="observacion_participes" name="observacion_participes" value=""  placeholder="Observación">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_observacion_participes" class="errores"></div>
                                 </div>
                             </div>  
                                                                      <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="codigo_alternativo_participes" class="control-label">Código Alternativo</label>
                                  <input type="text" class="form-control" id="codigo_alternativo_participes" name="codigo_alternativo_participes" value=""  placeholder="Código Alternativo">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_codigo_alternativo_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                               	<div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="apellido_participes" class="control-label">Apellido</label>
                                  <input type="text" class="form-control" id="apellido_participes" name="apellido_participes" value=""  placeholder="Apellido">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_apellido_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                 <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="nombre_participes" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_participes" name="nombre_participes" value=""  placeholder="Nombres">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_nombre_participes" class="errores"></div>
                                 </div>
                             </div>
                                   <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_nacimiento_participes" class="control-label">Fecha Nacimiento</label>
                                  <input type="date" class="form-control" id="fecha_nacimiento_participes" name="fecha_nacimiento_participes" value=""  placeholder="Fecha Nacimiento">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_nombre_conyugue_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="apellido_esposa_participes" class="control-label">Apellido Conyugue</label>
                                  <input type="text" class="form-control" id="apellido_esposa_participes" name="apellido_esposa_participes" value=""  placeholder="Apellido Conyugue">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_apellido_esposa_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                               <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="cedula_conyugue_participes" class="control-label">Cedula Conyugue</label>
                                  <input type="text" class="form-control" id="cedula_conyugue_participes" name="cedula_conyugue_participes" value=""  placeholder="Cedula Conyugue">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_cedula_conyugue_participes" class="errores"></div>
                                 </div>
                             </div> 
                             
                                                           <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="numero_dependencias_participes" class="control-label">Número Dependencias</label>
                                  <input type="text" class="form-control" id="numero_dependencias_participes" name="numero_dependencias_participes" value=""  placeholder="Número Dependencias">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_cedula_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                  <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="telefono_participes" class="control-label">Teléfono</label>
                                  <input type="text" class="form-control" id="telefono_participes" name="telefono_participes" value=""  placeholder="Teléfono">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_telefono_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                         <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="celular_participes" class="control-label">Celular</label>
                                  <input type="text" class="form-control" id="celular_participes" name="celular_participes" value=""  placeholder="Celular">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                                               <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_defuncion_participes" class="control-label">Fecha Defunción</label>
                                  <input type="date" class="form-control" id="fecha_defuncion_participes" name="fecha_defuncion_participes" value=""  placeholder="Fecha Defunción">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
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
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_fecha_salida_participes" class="errores"></div>
                                 </div>
                             </div>
                             
                                                                       <div class="col-xs-12 col-lg-3 col-md-3 ">
                    			<div class="form-group">
                                  <label for="fecha_numero_orden_participes" class="control-label">Fecha Número Orden</label>
                                  <input type="date" class="form-control" id="fecha_numero_orden_participes" name="fecha_numero_orden_participes" value=""  placeholder="Fecha Número Orden">
                                   <input type="hidden" name="id_participes" id="id_participes" value="" class="form-control"/>
                                  <div id="mensaje_fecha_numero_orden_participes" class="errores"></div>
                                 </div>
                             </div> 
                    		                       
                	
                      </div>
                      <div class="row">
            			    <div class="col-xs-12 col-md-12 col-md-12" style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Guardar</button>
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
 <script type="text/javascript" src="view/Core/js/wizardParticipes.js?0.22"></script>
 <script src="view/Core/js/Participes.js?3.1" ></script>
 <script type="text/javascript">

        	   $(document).ready( function (){
        		   
        		   load_participes_inactivos(1);
        		   load_participes_activos(1);
        		   
	   			});

        	


	   function load_participes_activos(pagina){

		   var search=$("#search_activos").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_participes_activos").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_participes_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Participes&action=consulta_participes_activos&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#participes_activos_registrados").html(x);
	                 $("#load_participes_activos").html("");
	                 $("#tabla_participes_activos").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#participes_activos_registrados").html("Ocurrio un error al cargar la informacion de Participes Activos..."+estado+"    "+error);
	              }
	            });


		   }

	   function load_participes_inactivos(pagina){

		   var search=$("#search_inactivos").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_participes_inactivos").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_participes_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Participes&action=consulta_participes_inactivos&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#participes_inactivos_registrados").html(x);
	                 $("#load_participes_inactivos").html("");
	                 $("#tabla_participes_inactivos").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#participes_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Participes Inactivos..."+estado+"    "+error);
	              }
	            });


		   };

	  
	
       	        	   

 </script>
 
  </body>

</html>



	

