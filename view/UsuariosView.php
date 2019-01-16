<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    
    
   <?php include("view/modulos/links_css.php"); ?>
   
  </head>

  <body class="hold-transition skin-blue fixed sidebar-mini">

 <?php  $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
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
                <li class="active">Usuarios</li>
            </ol>
        </section>
        
        <!-- comienza diseño controles usuario -->
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Registrar Usuarios</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
                <form action="<?php echo $helper->url("Usuarios","InsertaUsuarios"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12 col-md-12 col-xs-12">
          		 	  <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
              		 	 <div class="row">
                         	<div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="cedula_usuarios" class="control-label">Cedula:</label>
                                    <input type="text" class="form-control" id="cedula_usuarios" name="cedula_usuarios" value="<?php echo $resEdit->cedula_usuarios; ?>"  placeholder="ci-ruc.." readonly>
                                    <input type="hidden" class="form-control" id="id_usuarios" name="id_usuarios" value="<?php echo $resEdit->id_usuarios; ?>" >
                                    <div id="mensaje_cedula_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3">
                             	<div class="form-group">
                                	 <label for="nombre_usuarios" class="control-label">Nombres:</label>
                                      <input type="text" class="form-control" id="nombre_usuarios" name="nombre_usuarios" value="<?php echo $resEdit->nombre_usuarios; ?>" placeholder="nombres..">
                                      <div id="mensaje_nombre_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3">
                             	<div class="form-group">
                                	 <label for="apellidos_usuarios" class="control-label">Apellidos:</label>
                                      <input type="text" class="form-control" id="apellidos_usuarios" name="apellidos_usuarios" value="<?php echo $resEdit->apellidos_usuarios; ?>" placeholder="nombres..">
                                      <div id="mensaje_apellido_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="usuario_usuarios" class="control-label">Usuario:</label>
                                    <input type="text" class="form-control" id="usuario_usuarios" name="usuario_usuarios" value="<?php echo $resEdit->usuario_usuarios; ?>"  placeholder="usuario..." >
                                    <div id="usuario_usuarios" class="errores"></div>
                                 </div>
                             </div> 
                          </div>
                          <div class="row">
                          
                          	<div class="col-xs-6 col-md-3 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="fecha_nacimiento_usuarios" class="control-label">Fecha Nacimiento:</label>
                                    <input type="text" class="form-control" id="fecha_nacimiento_usuarios" name="fecha_nacimiento_usuarios" value="<?php echo $resEdit->fecha_nacimiento_usuarios; ?>"  placeholder="fecha nacimiento" >
                                    <div id="fecha_nacimiento_usuarios" class="errores"></div>
                                 </div>
                             </div> 
                             
                             <div class="col-lg-3 col-xs-6 col-md-3">
                    		    <div class="form-group">
                                      <label for="celular_usuarios" class="control-label">Celular:</label>
                                      <input type="text" class="form-control" id="celular_usuarios" name="celular_usuarios" value="<?php echo $resEdit->celular_usuarios; ?>"  placeholder="celular..">
                                      <div id="mensaje_celular_usuarios" class="errores"></div>
                                </div>
                             </div>
                             
                             <div class="col-lg-3 col-xs-6 col-md-3">
                    		    <div class="form-group">
                                      <label for="telefono_usuarios" class="control-label">Teléfono:</label>
                                      <input type="text" class="form-control" id="telefono_usuarios" name="telefono_usuarios" value="<?php echo $resEdit->telefono_usuarios; ?>"  placeholder="teléfono..">
                                      <div id="mensaje_telefono_usuarios" class="errores"></div>
                                </div>
                    	    </div>
                    	    
                    	    <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                          <label for="correo_usuarios" class="control-label">Correo:</label>
                                          <input type="email" class="form-control" id="correo_usuarios" name="correo_usuarios" value="<?php echo $resEdit->correo_usuarios; ?>" placeholder="email..">
                                          <div id="mensaje_correo_usuarios" class="errores"></div>
                                    </div>
                    		    </div>
                                
                        	                            
                          </div>
                          
                          <div class="row">
                		       
                		       <div class="col-xs-6 col-md-3 col-lg-3">
                        		<div class="form-group">
                                  <label for="clave_usuarios" class="control-label">Password:</label>
                                  <input type="password" class="form-control caducaclave" id="clave_usuarios" name="clave_usuarios" value="<?php echo $resEdit->clave_n_claves; ?>" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)" readonly>
                                  <div id="mensaje_clave_usuarios" class="errores"></div>
                                </div>
                            	</div>
                            		    
                    		    <div class="col-lg-3 col-xs-6 col-md-3">
                    		    <div class="form-group">
                                      <label for="clave_usuarios_r" class="control-label">Repita Password:</label>
                                      <input type="password" class="form-control" id="clave_usuarios_r" name="clave_usuarios_r" value="<?php echo $resEdit->clave_n_claves; ?>" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)" readonly>
                                      <div id="mensaje_clave_usuarios_r" class="errores"></div>
                                </div>
                                </div>
                    			
                    		    
                    		    <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado" class="control-label">Estado:</label>
                                      <select name="id_estado" id="id_estado"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php  foreach($result_catalogo_usuario as $res) {?>
    										<option value="<?php echo $res->valor_catalogo; ?>" <?php if ($res->valor_catalogo == $resEdit->estado_usuarios )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_catalogo; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estados" class="errores"></div>
                                    </div>
                                  </div>
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                          <label for="fotografia_usuarios" class="control-label">Fotografía:</label>
                                          <input type="file" class="form-control" id="fotografia_usuarios" name="fotografia_usuarios" value="">
                                          <div id="mensaje_usuario" class="errores"></div>
                                    </div>
                    		    </div>
                        	</div>
                        	
                        	
                    		
                    		<div class="row"> 
                    		
                    			<div class="col-xs-12 col-lg-3 col-md-3">
                        		   <div class="form-group">
                                      <label for="id_rol_principal" class="control-label">Rol Principal:</label>
                                      <select name="id_rol_principal" id="id_rol_principal"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" <?php if ($res->id_rol == $resEdit->id_rol )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div> 
                                 
                                 <div class="col-xs-12 col-lg-3 col-md-3">                                  
                        		   <div class="form-group">
                        		   <br>
                                      <label for="cambiar_clave" class="control-label">Cambiar Clave: </label> &nbsp;&nbsp;
                                      <input type="checkbox"  id="cambiar_clave" name="cambiar_clave" value="1"   /> <br>
                                      <label for="caduca_clave" class="control-label">Caduca  Clave: </label> &nbsp;&nbsp; &nbsp;
                                      <input type="checkbox"  id="caduca_clave" name="caduca_clave" value="1" <?php  if($resEdit->caduca_claves=='t'){echo 'checked="checked" ';} ?>  />
                                    </div>
                                 </div> 
                                 
                              </div>
                              
                              <div class="row">
                        		<div class="col-xs-12 col-lg-5 col-md-5">
                        		   <div class="form-group">
                                      <label for="id_rol" class="control-label">Roles Disponibles</label>
                                      <select name="id_rol" id="id_rol" multiple="multiple" class="form-control" >
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-xs-12 col-lg-2 col-md-2">
                                 	<table class="table table-bordered" style="text-align: center;">
                                        
                                        <tr>                                          
                                          <td>
                                            <div class="btn-group-vertical">
                                              <button id="link_agregar_rol" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-right"></i></button>
                                              <button id="link_agregar_roles" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-double-right"></i></button>
                                              <button id="link_eliminar_rol" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-left"></i></button>
                                              <button id="link_eliminar_roles" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-double-left"></i></button>
                                            </div>
                                          </td>
                                         </tr>
                                      </table>
                                 	 
                                 </div>
                                 
                                 <div class="col-xs-12 col-lg-5 col-md-5">
                        		   <div class="form-group">
                                      <label for="id_rol_principal" class="control-label">Usuario tiene Rol(es):</label>
                                      <select name="lista_roles[]" id="lista_roles" multiple="multiple" class="form-control" >
                                      <?php foreach($result_privilegios as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" <?php echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                        	
                        	</div>
                                
                      <?php } } else {?>                		    
                      	  <div class="row">
                		  	<div class="col-xs-6 col-md-3 col-lg-3 ">
                    			<div class="form-group">
                                    <label for="cedula_usuarios" class="control-label">Cedula:</label>
                                    <input type="text" class="form-control" id="cedula_usuarios" name="cedula_usuarios" value=""  placeholder="ci-ruc.." >
                                     <input type="hidden" class="form-control" id="id_usuarios" name="id_usuarios" value="0" >
                                    <div id="mensaje_cedula_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3">
                             	<div class="form-group">
                                	 <label for="nombre_usuarios" class="control-label">Nombres:</label>
                                      <input type="text" class="form-control" id="nombre_usuarios" name="nombre_usuarios" value="" placeholder="nombres..">
                                      <div id="mensaje_nombre_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3">
                             	<div class="form-group">
                                	 <label for="apellidos_usuarios" class="control-label">Apellidos:</label>
                                      <input type="text" class="form-control" id="apellidos_usuarios" name="apellidos_usuarios" value="" placeholder="apellidos..">
                                      <div id="mensaje_apellido_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-3 col-lg-3 ">
                                	<div class="form-group">
                                    	<label for="fecha_nacimiento_usuarios" class="control-label">Fecha Nacimiento:</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento_usuarios" name="fecha_nacimiento_usuarios" value=""  >
                                        <div id="fecha_nacimiento_usuarios" class="errores"></div>
                                     </div>
                               </div>  
                            </div>
                            
                             <div class="row">
                             	<div class="col-xs-6 col-md-3 col-lg-3 ">
                                	<div class="form-group">
                                    	<label for="usuario_usuarios" class="control-label">Usuario:</label>
                                        <input type="text" class="form-control" id="usuario_usuarios" name="usuario_usuarios" value=""  placeholder="usuario..." >
                                        <div id="usuario_usuarios" class="errores"></div>
                                     </div>
                                 </div> 
                                 
                                 <div class="col-lg-3 col-xs-6 col-md-3">
                        		    <div class="form-group">
                                          <label for="celular_usuarios" class="control-label">Celular:</label>
                                          <input type="text" class="form-control" id="celular_usuarios" name="celular_usuarios" value=""  placeholder="celular..">
                                          <div id="mensaje_celular_usuarios" class="errores"></div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-6 col-md-3">
                        		    <div class="form-group">
                                          <label for="telefono_usuarios" class="control-label">Teléfono:</label>
                                          <input type="text" class="form-control" id="telefono_usuarios" name="telefono_usuarios" value=""  placeholder="teléfono..">
                                          <div id="mensaje_telefono_usuarios" class="errores"></div>
                                    </div>
                        	    </div>
                        	    
                    		    <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                          <label for="correo_usuarios" class="control-label">Correo:</label>
                                          <input type="email" class="form-control" id="correo_usuarios" name="correo_usuarios" value="" placeholder="email..">
                                          <div id="mensaje_correo_usuarios" class="errores"></div>
                                    </div>
                    		    </div>
                                
                             	                            
                              </div>
                            
                          
                          <div class="row">                		      
                    			
                    		    <div class="col-xs-6 col-md-3 col-lg-3">
                            		<div class="form-group">
                                      <label for="clave_usuarios" class="control-label">Password:</label>
                                      <input type="password" class="form-control" id="clave_usuarios" name="clave_usuarios" value="" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                      <div id="mensaje_clave_usuarios" class="errores"></div>
                                    </div>
                            	</div>
                            	
                            	<div class="col-lg-3 col-xs-6 col-md-3">
                        		    <div class="form-group">
                                          <label for="clave_usuarios_r" class="control-label">Repita Password:</label>
                                          <input type="password" class="form-control" id="clave_usuarios_r" name="clave_usuarios_r" value="" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                          <div id="mensaje_clave_usuarios_r" class="errores"></div>
                                    </div>
                                </div>
                                
                    		    <div class="col-xs-12 col-md-3 col-lg-3">
                        		   <div class="form-group">
                                      <label for="id_estado" class="control-label">Estado:</label>
                                      <select name="id_estado" id="id_estado"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($result_catalogo_usuario as $res) {?>
    										<option value="<?php echo $res->valor_catalogo; ?>" ><?php echo $res->nombre_catalogo; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estados" class="errores"></div>
                                    </div>
                                  </div>
                                  
                                  <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                          <label for="fotografia_usuarios" class="control-label">Fotografía:</label>
                                          <input type="file" class="form-control" id="fotografia_usuarios" name="fotografia_usuarios" value="">
                                          <div id="mensaje_usuario" class="errores"></div>
                                    </div>
                    		    </div>
                        	</div>
                        	
                        	<div class="row"> 
                    		    		    
                        		<div class="col-xs-12 col-lg-3 col-md-3">
                        		   <div class="form-group">
                                      <label for="id_rol_principal" class="control-label">Rol Principal:</label>
                                      <select name="id_rol_principal" id="id_rol_principal"   class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-xs-12 col-lg-3 col-md-3">                                  
                        		   <div class="form-group">
                        		   <br>
                                      <label for="id_rol_principal" class="control-label">Caduca Clave: </label> &nbsp;&nbsp;
                                      <input type="checkbox" id="caduca_clave" name="caduca_clave" value=""  />
                                    </div>
                                 </div> 
                                 
                             </div>
                        	
                        	<div class="row">
                        		<div class="col-xs-12 col-lg-5 col-md-5">
                        		   <div class="form-group">
                                      <label for="id_rol_principal" class="control-label">Roles Disponibles</label>
                                      <select name="id_rol" id="id_rol" multiple="multiple" class="form-control" >
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-xs-12 col-lg-2 col-md-2">
                                 	<table class="table table-bordered" style="text-align: center;">
                                        
                                        <tr>                                          
                                          <td>
                                            <div class="btn-group-vertical">
                                              <button id="link_agregar_rol" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-right"></i></button>
                                              <button id="link_agregar_roles" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-double-right"></i></button>
                                              <button id="link_eliminar_rol" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-left"></i></button>
                                              <button id="link_eliminar_roles" type="button" class="btn btn-default"><i class="fa fa-fw fa-angle-double-left"></i></button>
                                            </div>
                                          </td>
                                         </tr>
                                      </table>
                                 	 
                                 </div>
                                 
                                 <div class="col-xs-12 col-lg-5 col-md-5">
                        		   <div class="form-group">
                                      <label for="id_rol_principal" class="control-label">Usuario tiene Rol(es):</label>
                                      <select name="lista_roles[]" id="lista_roles" multiple="multiple" class="form-control" >
                                      
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                        	
                        	</div>
                        	
                        	
                    		            
                     <?php } ?>
                     	<div class="row">
            			    <div class="col-xs-12 col-md-4 col-md-4 " style="margin-top:15px;  text-align: center; ">
                	   		    <div class="form-group">
            	                  <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
        	                    </div>
    	        		    </div>
            		    </div>
          		 	
          		 	</form>
          
        			</div>
      			</div>
      			
      			<div id="resultadosjq">
      			
      			</div>
    		</section>
    		
    		
    		
    		
       <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Listado Usuarios</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
            
            
            
            
           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab">Usuarios Activos</a></li>
              <li><a href="#inactivos" data-toggle="tab">Usuarios Inactivos</a></li>
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_usuarios(1)" placeholder="search.."/>
					</div>
					<div id="load_registrados" ></div>	
					<div id="users_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="inactivos">
                
                    <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="search_inactivos" name="search_inactivos" onkeyup="load_usuarios_inactivos(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_inactivos_registrados" ></div>	
					<div id="users_inactivos_registrados"></div>
                
                
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
   
 
 <!-- funciones javascript para la pagina -->
 
  <script type="text/javascript">
     
   $(document).ready( function (){
	   /*pone_espera();*/
	   load_usuarios(1);
	   load_usuarios_inactivos(1);

	   /*para manejo de multiples roles*/
	    /**$("#link_agregar_rol").click(function() {
			return !($('#id_rol option:selected').clone()).appendTo('#lista_roles'); 
	    });*/
	    $('#link_agregar_rol').click(function() { 
	        copiarOpcion($('#id_rol option:selected').clone(), "#lista_roles");
	    });

	    $('#link_agregar_roles').click(function() { 
	        $('#id_rol option').each(function() {
	            copiarOpcion($(this).clone(), "#lista_roles");
	        }); 
	    });

	    $('#link_eliminar_rol').click(function() { 
	        $('#lista_roles option:selected').remove(); 
	    });

	    $('#link_eliminar_roles').click(function() { 
	        $('#lista_roles option').each(function() {
	            $(this).remove(); 
	        }); 
	    });

	    $('#Guardar').click(function(){
	    	selecionarTodos();
	    	//return false;
		});

	    $(".caducaclave").blur(function(){
			var clave = $("#clave_usuarios").val();
			var _id_usuarios = $("#id_usuarios").val();

			if($('#cambiar_clave').is(':checked')){
    			$.ajax({
    	            beforeSend: function(objeto){
    	              $("#resultadosjq").html('...');
    	            },
    	            url: 'index.php?controller=Usuarios&action=ajax_caducaclave',
    	            type: 'POST',
    	            data: {clave_usuarios:clave,id_usuarios:_id_usuarios},
    	            success: function(x){
    	             if(x.trim()!=""){
    	            	 	$("#mensaje_clave_usuarios").text(x);
    			    		$("#mensaje_clave_usuarios").fadeIn("slow");
        	            	 $("#clave_usuarios").val("");
        	            	 $("#clave_usuarios_r").val("");
    	                 }
    	            },
    	           error: function(jqXHR,estado,error){
    	             $("#resultadosjq").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
    	           }
    	         });
			}
    	        
	   });

		$('#cambiar_clave').change(
			    function(){
			        if (this.checked) {

				           $('#clave_usuarios').removeAttr("readonly");
				           $('#clave_usuarios_r').removeAttr("readonly");
				           $('#clave_usuarios').val("");
				           $('#clave_usuarios_r').val("");
			        }else{
			        	$('#clave_usuarios').attr("readonly","readonly");
				        $('#clave_usuarios_r').attr("readonly","readonly");
				        }
			    });

		$("#cedula_usuarios").blur(function(){
			var _cedula = $("#cedula_usuarios").val();
			var _id_usuarios = $("#id_usuarios").val();

			if($("#id_usuarios").val()=="0"){
				$.ajax({
    	            beforeSend: function(objeto){
    	              $("#resultadosjq").html('...');
    	            },
    	            url: 'index.php?controller=Usuarios&action=ajax_validacedula',
    	            type: 'POST',
    	            data: {cedula:_cedula},
    	            success: function(x){
    	             if(x.trim()!=""){
    	            	 	$("#mensaje_cedula_usuarios").text(x);
    			    		$("#mensaje_cedula_usuarios").fadeIn("slow");
    	                 }
    	            },
    	           error: function(jqXHR,estado,error){
    	             $("#resultadosjq").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
    	           }
    	         });
			}
			  
	   });
	    
	});

    function copiarOpcion(opcion, destino) {
        var valor = $(opcion).val();
        if (($(destino + " option[value=" + valor + "] ").length == 0) && valor != 0 ) {
            $(opcion).appendTo(destino);
        }
    }

    function selecionarTodos(){
    	$("#lista_roles option").each(function(){
	      $(this).attr("selected", true);
		 });
     }
    

   function pone_espera(){

	   $.blockUI({ 
			message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
			css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff',
	           
        		}
    });
	
    setTimeout($.unblockUI, 3000); 
    
   }

        	   
   function load_usuarios(pagina){

	   var search=$("#search").val();
       var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
     $("#load_registrados").fadeIn('slow');
     
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
               },
               url: 'index.php?controller=Usuarios&action=consulta_usuarios_activos&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#users_registrados").html(x);
                 $("#load_registrados").html("");
                 $("#tabla_usuarios").tablesorter(); 
                 
               },
              error: function(jqXHR,estado,error){
                $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
              }
            });


	   }

   function load_usuarios_inactivos(pagina){

	   var search=$("#search").val();
       var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
     $("#load_inactivos_registrados").fadeIn('slow');
     
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_inactivos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
               },
               url: 'index.php?controller=Usuarios&action=consulta_usuarios_inactivos&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#users_inactivos_registrados").html(x);
                 $("#load_inactivos_registrados").html("");
                 $("#tabla_usuarios_inactivos").tablesorter(); 
                 
               },
              error: function(jqXHR,estado,error){
                $("#users_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
              }
            });


	   }

  

   
</script>
        
        
         <script type="text/javascript" >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    $("#Cancelar").click(function() 
			{
			 $("#cedula_usuarios").val("");
		     $("#nombre_usuarios").val("");
		     $("#clave_usuarios").val("");
		     $("#clave_usuarios_r").val("");
		     $("#telefono_usuarios").val("");
		     $("#celular_usuarios").val("");
		     $("#correo_usuarios").val("");
		     $("#id_rol").val("");
		     $("#id_estado").val("");
		     $("#fotografia_usuarios").val("");
		     $("#id_usuarios").val("");
		     
		    }); 
		    }); 
			</script>
        
        
        
        
         
        <script  type="text/javascript">
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){

		    	/*swal(
		    			  'The Internet?',
		    			  'That thing is still around?',
		    			  'question'
		    			);*/
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var cedula_usuarios = $("#cedula_usuarios").val();
		    	var nombre_usuarios = $("#nombre_usuarios").val();
		    	//var usuario_usuario = $("#usuario_usuario").val();
		    	var clave_usuarios = $("#clave_usuarios").val();
		    	var cclave_usuarios = $("#clave_usuarios_r").val();
		    	var celular_usuarios = $("#celular_usuarios").val();
		    	var correo_usuarios  = $("#correo_usuarios").val();
		    	var id_rol  = $("#id_rol").val();
		    	var id_estado  = $("#id_estado").val();
		    	
		    	
		    	if (cedula_usuarios == "")
		    	{
			    	
		    		$("#mensaje_cedula_usuarios").text("Introduzca Identificación");
		    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}    
				
		    	if (nombre_usuarios == "")
		    	{
			    	
		    		$("#mensaje_nombre_usuarios").text("Introduzca un Nombre");
		    		$("#mensaje_nombre_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	
		    	/*if (usuario_usuario == "")
		    	{
			    	
		    		$("#mensaje_usuario_usuario").text("Introduzca un Usuario");
		    		$("#mensaje_usuario_usuario").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_usuario_usuario").fadeOut("slow"); //Muestra mensaje de error
		            
				}   */
						    	
			
		    	if (clave_usuarios == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }else if (clave_usuarios.length<4){
			    	$("#mensaje_clave_usuarios").text("Introduzca minimo 4 números");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
				}else if (clave_usuarios.length>4){
			    	$("#mensaje_clave_usuarios").text("Introduzca máximo 4 números");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
				}
		    	else 
		    	{
		    		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	

		    	if (cclave_usuarios == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios_r").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
		            
				}
		    	
		    	if (clave_usuarios != cclave_usuarios)
		    	{
			    	
		    		$("#mensaje_clave_usuarios_r").text("Claves no Coinciden");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
			        
		    	}	
				

				//los telefonos
		    	
		    	if (celular_usuarios == "" )
		    	{
			    	
		    		$("#mensaje_celular_usuarios").text("Ingrese un Celular");
		    		$("#mensaje_celular_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_celular_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				// correos
				
		    	if (correo_usuarios == "")
		    	{
			    	
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else if (regex.test($('#correo_usuarios').val().trim()))
		    	{
		    		$("#mensaje_correo_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	else 
		    	{
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo Valido");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;	
			    }

		    	
		    
		    					    

			}); 


		        $( "#cedula_usuarios" ).focus(function() {
				  $("#mensaje_cedula_usuarios").fadeOut("slow");
			    });
				
				$( "#nombre_usuarios" ).focus(function() {
					$("#mensaje_nombre_usuarios").fadeOut("slow");
    			});
				
    			
				$( "#clave_usuarios" ).focus(function() {
					$("#mensaje_clave_usuarios").fadeOut("slow");
    			});
				$( "#clave_usuarios_r" ).focus(function() {
					$("#mensaje_clave_usuarios_r").fadeOut("slow");
    			});
				
				$( "#celular_usuarios" ).focus(function() {
					$("#mensaje_celular_usuarios").fadeOut("slow");
    			});
				
				$( "#correo_usuarios" ).focus(function() {
					$("#mensaje_correo_usuarios").fadeOut("slow");
    			});
			
				
		      
				    
		}); 

	</script>
        
        
        
        
    <script type="text/javascript" >   
    function numeros(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = "0123456789";
    especiales = [8,37,39,46];
 
    tecla_especial = false
    for(var i in especiales){
    if(key == especiales[i]){
     tecla_especial = true;
     break;
        } 
    }
 
    if(letras.indexOf(tecla)==-1 && !tecla_especial)
        return false;
     }
    </script> 
    
    <script type="text/javascript">
    var interval, mouseMove;

    $(document).mousemove(function(){
        //Establezco la última fecha cuando moví el cursor
        mouseMove = new Date();
        /* Llamo a esta función para que ejecute una acción pasado x tiempo
         después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
        inactividad(function(){
        	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
        }, 600);
      });

    $(document).scroll(function(){
        //Establezco la última fecha cuando moví el cursor
        mouseMove = new Date();
        /* Llamo a esta función para que ejecute una acción pasado x tiempo
         después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
        inactividad(function(){
        	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
        }, 600);
      });

      $(document).keydown(function(){
          //Establezco la última fecha cuando moví el cursor
          mouseMove = new Date();
          /* Llamo a esta función para que ejecute una acción pasado x tiempo
           después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
          inactividad(function(){
          	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
          }, 600);
        });

     

      /* Función creada para ejecutar una acción (callback), al pasar x segundos 
         (seconds) de haber dejado de mover el cursor */
      var inactividad = function(callback, seconds){
        //Elimino el intervalo para que no se ejecuten varias instancias
        clearInterval(interval);
        //Creo el intervalo
        interval = setInterval(function(){
           //Hora actual
           var now = new Date();
           //Diferencia entre la hora actual y la última vez que se movió el cursor
           var diff = (now.getTime()-mouseMove.getTime())/1000;
           //Si la diferencia es mayor o igual al tiempo que pasastes por parámetro
           if(diff >= seconds){
            //Borro el intervalo
            clearInterval(interval);
            //Ejecuto la función que será llamada al pasar el tiempo de inactividad
            callback();          
           }
        }, 200);
      }
    </script>
 	
  </body>
</html>

 