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
            <div class="box-header">
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
                         	<div class="col-xs-6 col-md-4 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="cedula_usuarios" class="control-label">Cedula:</label>
                                    <input type="text" class="form-control" id="cedula_usuarios" name="cedula_usuarios" value="<?php echo $resEdit->cedula_usuarios; ?>"  placeholder="ci-ruc.." readonly>
                                    <input type="hidden" class="form-control" id="id_usuarios" name="id_usuarios" value="<?php echo $resEdit->id_usuarios; ?>" >
                                    <div id="mensaje_cedula_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-4 col-lg-3">
                             	<div class="form-group">
                                	 <label for="nombre_usuarios" class="control-label">Nombres:</label>
                                      <input type="text" class="form-control" id="nombre_usuarios" name="nombre_usuarios" value="<?php echo $resEdit->nombre_usuarios; ?>" placeholder="nombres..">
                                      <div id="mensaje_nombre_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-4 col-lg-3">
                             	<div class="form-group">
                                	 <label for="apellido_usuarios" class="control-label">Apellidos:</label>
                                      <input type="text" class="form-control" id="apellido_usuarios" name="apellido_usuarios" value="<?php echo $resEdit->apellidos_usuarios; ?>" placeholder="nombres..">
                                      <div id="mensaje_apellido_usuarios" class="errores"></div>
                                 </div>
                             </div>
                          </div>
                          <div class="row">
                          	<div class="col-xs-6 col-md-4 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="usuario_usuarios" class="control-label">Usuario:</label>
                                    <input type="text" class="form-control" id="usuario_usuarios" name="usuario_usuarios" value="<?php echo $resEdit->usuario_usuarios; ?>"  placeholder="usuario..." >
                                    <div id="usuario_usuarios" class="errores"></div>
                                 </div>
                             </div> 
                         	<div class="col-xs-6 col-md-4 col-lg-3 ">
                            	<div class="form-group">
                                	<label for="fecha_nacimiento_usuarios" class="control-label">Fecha Nacimiento:</label>
                                    <input type="text" class="form-control" id="fecha_nacimiento_usuarios" name="fecha_nacimiento_usuarios" value="<?php echo $resEdit->fecha_nacimiento_usuarios; ?>"  placeholder="fecha nacimiento" >
                                    <div id="fecha_nacimiento_usuarios" class="errores"></div>
                                 </div>
                             </div>                             
                          </div>
                          <div class="row">
                          	<div class="col-xs-6 col-md-4 col-lg-3">
                        		<div class="form-group">
                                  <label for="clave_usuarios" class="control-label">Password:</label>
                                  <input type="password" class="form-control" id="clave_usuarios" name="clave_usuarios" value="<?php echo $resEdit->pass_sistemas_usuarios; ?>" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                  <div id="mensaje_clave_usuarios" class="errores"></div>
                                </div>
                        	</div>
                        		    
                		    <div class="col-lg-3 col-xs-6 col-md-4">
                		    <div class="form-group">
                                  <label for="clave_usuarios_r" class="control-label">Repita Password:</label>
                                  <input type="password" class="form-control" id="clave_usuarios_r" name="clave_usuarios_r" value="<?php echo $resEdit->pass_sistemas_usuarios; ?>" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                  <div id="mensaje_clave_usuarios_r" class="errores"></div>
                            </div>
                            </div>
                          </div>
                          
                          <div class="row">
                		       <div class="col-lg-3 col-xs-6 col-md-4">
                        		    <div class="form-group">
                                          <label for="telefono_usuarios" class="control-label">Teléfono:</label>
                                          <input type="text" class="form-control" id="telefono_usuarios" name="telefono_usuarios" value="<?php echo $resEdit->telefono_usuarios; ?>"  placeholder="teléfono..">
                                          <div id="mensaje_telefono_usuarios" class="errores"></div>
                                    </div>
                        	    </div>
                    			<div class="col-lg-3 col-xs-6 col-md-4">
                        		    <div class="form-group">
                                          <label for="celular_usuarios" class="control-label">Celular:</label>
                                          <input type="text" class="form-control" id="celular_usuarios" name="celular_usuarios" value="<?php echo $resEdit->celular_usuarios; ?>"  placeholder="celular..">
                                          <div id="mensaje_celular_usuarios" class="errores"></div>
                                    </div>
                                </div>
                    		    <div class="col-lg-6 col-xs-12 col-md-4">
                        		    <div class="form-group">
                                          <label for="correo_usuarios" class="control-label">Correo:</label>
                                          <input type="email" class="form-control" id="correo_usuarios" name="correo_usuarios" value="<?php echo $resEdit->correo_usuarios; ?>" placeholder="email..">
                                          <div id="mensaje_correo_usuarios" class="errores"></div>
                                    </div>
                    		    </div>
                        	</div>
                        	
                        	<div class="row">
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                        		    <div class="form-group">
                                          <label for="fotografia_usuarios" class="control-label">Fotografía:</label>
                                          <input type="file" class="form-control" id="fotografia_usuarios" name="fotografia_usuarios" value="">
                                          <div id="mensaje_usuario" class="errores"></div>
                                    </div>
                    		    </div>
                    		</div>
                    		
                    		<div class="row">                    		    
                        		<div class="col-xs-12 col-lg-4 col-md-4">
                        		   <div class="form-group">
                                      <label for="id_rol" class="control-label">Roles:</label>
                                      <select name="id_rol" id="id_rol"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" <?php if ($res->id_rol == $resEdit->id_rol )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                                    
                                 <div class="col-xs-12 col-md-4 col-lg-4">
                        		   <div class="form-group">
                                      <label for="privilegios_usuario" class="control-label">Listado Roles:</label>
                                      <select name="privilegios_usuario" id="privilegios_usuario" multiple="multiple"  class="form-control" >
    									
    								   </select> 
                                      <div id="privilegios_usuario" class="errores"></div>
                                    </div>
                                  </div>
                                
                              </div>
                              
                              <div class="row">
                              	<div class="col-xs-12 col-md-4 col-lg-4">
                        		   <div class="form-group">
                                      <label for="id_estado" class="control-label">Estado:</label>
                                      <select name="id_estado" id="id_estado"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEst as $res) {?>
    										<option value="<?php echo $res->id_estado; ?>" <?php if ($res->id_estado == $resEdit->id_estado )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_estado; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estados" class="errores"></div>
                                    </div>
                                  </div>
                              </div>
                                
                      <?php } } else {?>                		    
                      	  <div class="row">
                		  	<div class="col-xs-6 col-md-4 col-lg-3 ">
                    			<div class="form-group">
                                    <label for="cedula_usuarios" class="control-label">Cedula:</label>
                                    <input type="text" class="form-control" id="cedula_usuarios" name="cedula_usuarios" value=""  placeholder="ci-ruc.." >
                                    <div id="mensaje_cedula_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-4 col-lg-3">
                             	<div class="form-group">
                                	 <label for="nombre_usuarios" class="control-label">Nombres:</label>
                                      <input type="text" class="form-control" id="nombre_usuarios" name="nombre_usuarios" value="" placeholder="nombres..">
                                      <div id="mensaje_nombre_usuarios" class="errores"></div>
                                 </div>
                             </div>
                             <div class="col-xs-6 col-md-4 col-lg-3">
                             	<div class="form-group">
                                	 <label for="apellido_usuarios" class="control-label">Apellidos:</label>
                                      <input type="text" class="form-control" id="apellido_usuarios" name="apellido_usuarios" value="" placeholder="nombres..">
                                      <div id="mensaje_apellido_usuarios" class="errores"></div>
                                 </div>
                             </div>
                            </div>
                            
                             <div class="row">
                             	<div class="col-xs-6 col-md-4 col-lg-3 ">
                                	<div class="form-group">
                                    	<label for="usuario_usuarios" class="control-label">Usuario:</label>
                                        <input type="text" class="form-control" id="usuario_usuarios" name="usuario_usuarios" value=""  placeholder="usuario..." >
                                        <div id="usuario_usuarios" class="errores"></div>
                                     </div>
                                 </div> 
                             	<div class="col-xs-6 col-md-4 col-lg-3 ">
                                	<div class="form-group">
                                    	<label for="fecha_nacimiento_usuarios" class="control-label">Fecha Nacimiento:</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento_usuarios" name="fecha_nacimiento_usuarios" value=""  >
                                        <div id="fecha_nacimiento_usuarios" class="errores"></div>
                                     </div>
                                 </div>                             
                              </div>
                            
                            <div class="row">
                          	<div class="col-xs-6 col-md-4 col-lg-3">
                        		<div class="form-group">
                                  <label for="clave_usuarios" class="control-label">Password:</label>
                                  <input type="password" class="form-control" id="clave_usuarios" name="clave_usuarios" value="" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                  <div id="mensaje_clave_usuarios" class="errores"></div>
                                </div>
                        	</div>
                        		    
                		    <div class="col-lg-3 col-xs-6 col-md-4">
                		    <div class="form-group">
                                  <label for="clave_usuarios_r" class="control-label">Repita Password:</label>
                                  <input type="password" class="form-control" id="clave_usuarios_r" name="clave_usuarios_r" value="" placeholder="(solo números..)" maxlength="4" onkeypress="return numeros(event)">
                                  <div id="mensaje_clave_usuarios_r" class="errores"></div>
                            </div>
                            </div>
                          </div>
                          
                          <div class="row">
                		       <div class="col-lg-3 col-xs-6 col-md-4">
                        		    <div class="form-group">
                                          <label for="telefono_usuarios" class="control-label">Teléfono:</label>
                                          <input type="text" class="form-control" id="telefono_usuarios" name="telefono_usuarios" value=""  placeholder="teléfono..">
                                          <div id="mensaje_telefono_usuarios" class="errores"></div>
                                    </div>
                        	    </div>
                    			<div class="col-lg-3 col-xs-6 col-md-4">
                        		    <div class="form-group">
                                          <label for="celular_usuarios" class="control-label">Celular:</label>
                                          <input type="text" class="form-control" id="celular_usuarios" name="celular_usuarios" value=""  placeholder="celular..">
                                          <div id="mensaje_celular_usuarios" class="errores"></div>
                                    </div>
                                </div>
                    		    <div class="col-lg-6 col-xs-12 col-md-4">
                        		    <div class="form-group">
                                          <label for="correo_usuarios" class="control-label">Correo:</label>
                                          <input type="email" class="form-control" id="correo_usuarios" name="correo_usuarios" value="" placeholder="email..">
                                          <div id="mensaje_correo_usuarios" class="errores"></div>
                                    </div>
                    		    </div>
                        	</div>
                        	
                        	<div class="row">
                    		    <div class="col-lg-4 col-xs-12 col-md-4">
                        		    <div class="form-group">
                                          <label for="fotografia_usuarios" class="control-label">Fotografía:</label>
                                          <input type="file" class="form-control" id="fotografia_usuarios" name="fotografia_usuarios" value="">
                                          <div id="mensaje_usuario" class="errores"></div>
                                    </div>
                    		    </div>
                    		</div>
                    		
                    		<div class="row">                    		    
                        		<div class="col-xs-12 col-lg-4 col-md-4">
                        		   <div class="form-group">
                                      <label for="id_rol" class="control-label">Rol:</label>
                                      <select name="id_rol" id="id_rol"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_rols" class="errores"></div>
                                    </div>
                                 </div>
                                    
                                 <div class="col-xs-12 col-md-4 col-lg-4">
                        		   <div class="form-group">
                                      <label for="privilegios_usuario" class="control-label">Listado Roles:</label>
                                      <select name="privilegios_usuario" id="privilegios_usuario" multiple="multiple"  class="form-control" >
                                      <?php foreach($resultRol as $res) {?>
    										<option value="<?php echo $res->id_rol; ?>" ><?php echo $res->nombre_rol; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="privilegios_usuario" class="errores"></div>
                                    </div>
                                  </div>
                                
                              </div>
                              
                              <div class="row">
                              	<div class="col-xs-12 col-md-4 col-lg-4">
                        		   <div class="form-group">
                                      <label for="id_estado" class="control-label">Estado:</label>
                                      <select name="id_estado" id="id_estado"  class="form-control" >
                                      <option value="0" selected="selected">--Seleccione--</option>
    									<?php foreach($resultEst as $res) {?>
    										<option value="<?php echo $res->id_estado; ?>" <?php if ($res->id_estado == $resEdit->id_estado )  echo  ' selected="selected" '  ;  ?> ><?php echo $res->nombre_estado; ?> </option>
    							        <?php } ?>
    								   </select> 
                                      <div id="mensaje_id_estados" class="errores"></div>
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
    		</section>
    		
    		<!-- para el listado de usuarios -->
    		<section class="content">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Listado de Usuarios</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                  </div>
                </div>
        
        		<div class="box-body">
                  <div class="ibox-content">
                  	
                  	<div class="x_content">
					
					<div class="pull-right" style="margin-right:11px;">
						<input type="text" value="" class="form-control" id="search" name="search" onkeyup="load_usuarios(1)" placeholder="search.."/>
					</div>
					<div id="load_registrados" ></div>	
					<div id="users_registrados"></div>	
                  
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
	   pone_espera();
	   load_usuarios(1);
	});

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
               url: 'index.php?controller=Usuarios&action=index10&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#users_registrados").html(x);
               	 $("#tabla_usuarios").tablesorter(); 
                 $("#load_registrados").html("");
               },
              error: function(jqXHR,estado,error){
                $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
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

		    	
		    	if (id_rol == 0 )
		    	{
			    	
		    		$("#mensaje_id_rol").text("Seleccione");
		    		$("#mensaje_id_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}



		    	if (id_estado == 0 )
		    	{
			    	
		    		$("#mensaje_id_estado").text("Seleccione");
		    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    					    

			}); 


		        $( "#cedula_usuarios" ).focus(function() {
				  $("#mensaje_cedula_usuarios").fadeOut("slow");
			    });
				
				$( "#nombre_usuarios" ).focus(function() {
					$("#mensaje_nombre_usuarios").fadeOut("slow");
    			});
				/*$( "#usuario_usuario" ).focus(function() {
					$("#mensaje_usuario_usuario").fadeOut("slow");
    			});*/
    			
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
			
				$( "#id_rol" ).focus(function() {
					$("#mensaje_id_rol").fadeOut("slow");
    			});

				$( "#id_estado" ).focus(function() {
					$("#mensaje_id_estado").fadeOut("slow");
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
 	
  </body>
</html>

 