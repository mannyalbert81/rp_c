<!DOCTYPE HTML>
<html lang="es">
      <head>
        <meta charset="utf-8"/>
        <title>PermisosRoles- Template2018</title>

		   
		<link rel="stylesheet" href="view/css/estilos.css">
		<link rel="stylesheet" href="view/vendors/table-sorter/themes/blue/style.css">
	
	
	
		    <!-- Bootstrap -->
    		<link href="view/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    		<!-- Font Awesome -->
		    <link href="view/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		    <!-- NProgress -->
		    <link href="view/vendors/nprogress/nprogress.css" rel="stylesheet">
		    
		   
		    <!-- Custom Theme Style -->
		    <link href="view/build/css/custom.min.css" rel="stylesheet">
				
			
			<!-- Datatables -->
		    <link href="view/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		    
		   		

			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
			<script type="text/javascript" src="view/vendors/table-sorter/jquery.tablesorter.js"></script> 
        <script src="view/js/jquery.blockUI.js"></script>
       
        <script type="text/javascript">
     
        	   $(document).ready( function (){
        		   pone_espera();
        		   
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

        	   </script>
       
		  
		   <script >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_permisos_rol = $("#nombre_permisos_rol").val();
		    	var id_rol = $("#id_rol").val();
		    	var id_controladores = $("#id_controladores").val();
		    	var ver_permisos_rol = $("#ver_permisos_rol").val();
		    	var editar_permisos_rol = $("#editar_permisos_rol").val();
		    	var borrar_permisos_rol = $("#borrar_permisos_rol").val();
		    	
		    	
		    	
		    	if (nombre_permisos_rol == "")
		    	{
			    	
		    		$("#mensaje_nombre_permisos_rol").text("Introduzca Nombre");
		    		$("#mensaje_nombre_permisos_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_permisos_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}   


		    	if (id_rol == 0)
		    	{
			    	
		    		$("#mensaje_id_rol").text("Seleccione Rol");
		    		$("#mensaje_id_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}  


		    	if (id_controladores == 0)
		    	{
			    	
		    		$("#mensaje_id_controladores").text("Seleccione Controlador");
		    		$("#mensaje_id_controladores").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_controladores").fadeOut("slow"); //Muestra mensaje de error
		            
				}   


		    	if (ver_permisos_rol == 0)
		    	{
			    	
		    		$("#mensaje_ver_permisos_rol").text("Seleccione Permiso");
		    		$("#mensaje_ver_permisos_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_ver_permisos_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (editar_permisos_rol == 0)
		    	{
			    	
		    		$("#mensaje_editar_permisos_rol").text("Seleccione Permiso");
		    		$("#mensaje_editar_permisos_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_editar_permisos_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (borrar_permisos_rol == 0)
		    	{
			    	
		    		$("#mensaje_borrar_permisos_rol").text("Seleccione Permiso");
		    		$("#mensaje_borrar_permisos_rol").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_borrar_permisos_rol").fadeOut("slow"); //Muestra mensaje de error
		            
				}  
								    

			}); 


		        $( "#nombre_permisos_rol" ).focus(function() {
				  $("#mensaje_nombre_permisos_rol").fadeOut("slow");
			    });
		        $( "#id_rol" ).focus(function() {
					  $("#mensaje_id_rol").fadeOut("slow");
				    });
		        $( "#id_controladores" ).focus(function() {
					  $("#mensaje_id_controladores").fadeOut("slow");
				    });
		        $( "#ver_permisos_rol" ).focus(function() {
					  $("#mensaje_ver_permisos_rol").fadeOut("slow");
				    });
		        $( "#editar_permisos_rol" ).focus(function() {
					  $("#mensaje_editar_permisos_rol").fadeOut("slow");
				    });
				
		        $( "#borrar_permisos_rol" ).focus(function() {
					  $("#mensaje_borrar_permisos_rol").fadeOut("slow");
				    });
				
		      
				    
		}); 

	</script>
        
		  
		  
			        
    </head>
    
    
    <body class="nav-md"  >
    
     <?php
        
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        ?>
    
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col  menu_fixed">
          <div class="left_col scroll-view">
            <?php include("view/modulos/logo.php"); ?>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include("view/modulos/menu_profile.php"); ?>
            <!-- /menu profile quick info -->

            <br />
			<?php include("view/modulos/menu.php"); ?>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
		<?php include("view/modulos/head.php"); ?>	
        <!-- /top navigation -->

        <!-- page content -->
		<div class="right_col" role="main">        
            <?php
       $sel_menu = "";
       
    
       if($_SERVER['REQUEST_METHOD']=='POST' )
       {
       	 
       	 
       	$sel_menu=$_POST['criterio'];
       	
       	 
       }
      
	 	?>
    <div class="container">
   <section class="content-header">
         <small><?php echo $fecha; ?></small>
         <ol class=" pull-right breadcrumb">
         <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Permisos Roles</li>
         </ol>
         </section>
  	<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>INSERTAR<small>Permisos Roles</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
			
            <form action="<?php echo $helper->url("PermisosRoles","InsertaPermisosRoles"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		    <div class="col-xs-12 col-md-2 col-md-2 ">
                        		    <div class="form-group">
                                                          <label for="nombre_permisos_rol" class="control-label">Nombres Permiso Rol</label>
                                                          <input type="text" class="form-control" id="nombre_permisos_rol" name="nombre_permisos_rol" value="<?php echo $resEdit->nombre_permisos_rol; ?>"  placeholder="Nombres">
                                                          <input type="hidden" name="id_permisos_rol" id="id_permisos_rol" value="<?php echo $resEdit->id_permisos_rol; ?>" class="form-control"/>
					                                      <div id="mensaje_nombre_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="id_rol" class="control-label">Rol</label>
                                                          <select name="id_rol" id="id_rol"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultRol as $resRol) {?>
				 												<option value="<?php echo $resRol->id_rol; ?>" <?php if ($resRol->id_rol == $resEdit->id_rol )  echo  ' selected="selected" '  ;  ?> ><?php echo $resRol->nombre_rol; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_rol" class="errores"></div>
                                    </div>
                                    </div>
                        			
                        			
                        			<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="id_controladores" class="control-label">Controladores</label>
                                                          <select name="id_controladores" id="id_controladores"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultCon as $resCon) {?>
                        				 						<option value="<?php echo $resCon->id_controladores; ?>" <?php if ($resCon->id_controladores == $resEdit->id_controladores )  echo  ' selected="selected" '  ;  ?> ><?php echo $resCon->nombre_controladores; ?> </option>
                        						            <?php } ?>
                        								    	
                        									</select>
                                                           <div id="mensaje_id_controladores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        			
                        	    	<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="ver_permisos_rol" class="control-label">Ver</label>
                        								  <select name="ver_permisos_rol" id="ver_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                    										<option value="TRUE"  <?php  if ( $resEdit->ver_permisos_rol =='t')  echo ' selected="selected" ' ; ?> >Permitir </option>
                                    						            	<option value="FALSE" <?php  if ( $resEdit->ver_permisos_rol =='f')  echo ' selected="selected" ' ; ?> >Denegar </option>
                                    					   </select>	                                  
                                                           <div id="mensaje_ver_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                        			<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="editar_permisos_rol" class="control-label">Editar</label>
                        								  <select name="editar_permisos_rol" id="editar_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                										<option value="TRUE"  <?php  if ( $resEdit->editar_permisos_rol =='t')  echo ' selected="selected" ' ; ?>>Permitir </option>
                                						            	<option value="FALSE" <?php  if ( $resEdit->editar_permisos_rol =='f')  echo ' selected="selected" ' ; ?>  >Denegar </option>
                                					    </select>	                                  
                                                        <div id="mensaje_editar_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>    
                        		    <div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="borrar_permisos_rol" class="control-label">Borrar</label>
                        								  <select name="borrar_permisos_rol" id="borrar_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                										<option value="TRUE"  <?php  if ( $resEdit->borrar_permisos_rol =='t')  echo ' selected="selected" ' ; ?> >Permitir </option>
                                						            	<option value="FALSE" <?php  if ( $resEdit->borrar_permisos_rol =='f')  echo ' selected="selected" ' ; ?>  >Denegar </option>
                                					    </select>	                                  
                                                        <div id="mensaje_borrar_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                		    
                        		    
                    			</div>
                    			
                                 
                                
                    		     <?php } } else {?>
                    		    
                    		   
								 <div class="row">
                        		    <div class="col-xs-12 col-md-2 col-md-2 ">
                        		    <div class="form-group">
                                                          <label for="nombre_permisos_rol" class="control-label">Nombres Permiso Rol</label>
                                                          <input type="text" class="form-control" id="nombre_permisos_rol" name="nombre_permisos_rol" value=""  placeholder="Nombres">
                                                           <div id="mensaje_nombre_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="id_rol" class="control-label">Rol</label>
                                                          <select name="id_rol" id="id_rol"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultRol as $resRol) {?>
				 												<option value="<?php echo $resRol->id_rol; ?>"  ><?php echo $resRol->nombre_rol; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_rol" class="errores"></div>
                                    </div>
                                    </div>
                        			
                        			
                        			<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="id_controladores" class="control-label">Controladores</label>
                                                          <select name="id_controladores" id="id_controladores"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultCon as $resCon) {?>
                        				 						<option value="<?php echo $resCon->id_controladores; ?>"  ><?php echo $resCon->nombre_controladores; ?> </option>
                        						            <?php } ?>
                        								    	
                        									</select>
                                                            <div id="mensaje_id_controladores" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
									
									
							    	<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="ver_permisos_rol" class="control-label">Ver</label>
                        								  <select name="ver_permisos_rol" id="ver_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                    										<option value="TRUE"   >Permitir </option>
                                    						            	<option value="FALSE"  >Denegar </option>
                                    					   </select>	                                  
                                                            <div id="mensaje_ver_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                        			<div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="editar_permisos_rol" class="control-label">Editar</label>
                        								  <select name="editar_permisos_rol" id="editar_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                										<option value="TRUE"  >Permitir </option>
                                						            	<option value="FALSE"   >Denegar </option>
                                					    </select>	                                  
                                                         <div id="mensaje_editar_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>    
                        		    <div class="col-xs-12 col-md-2 col-md-2">
                        		    <div class="form-group">
                                                          <label for="borrar_permisos_rol" class="control-label">Borrar</label>
                        								  <select name="borrar_permisos_rol" id="borrar_permisos_rol"  class="form-control">
                        								    <option value="0" selected="selected">--Seleccione--</option>
                                										<option value="TRUE"   >Permitir </option>
                                						            	<option value="FALSE"   >Denegar </option>
                                					    </select>	                                  
                                                         <div id="mensaje_borrar_permisos_rol" class="errores"></div>
                                    </div>
                        		    </div>
                		    
										                        		    
                    			
                    			
                    			</div>
                    			
                                 	                    		   
                    		   
            	        		                     	           	
                    		     <?php } ?>
                    		    <br>  
                    		    <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
                                </div>
                    		    </div>
                    		    </div>
                    		      
                    		        
                              
              </form>


                  </div>
                </div>
              </div>
		
      
        <!-- /page content -->
		
		<div class="col-md-12 col-lg-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>LISTADO<small>Permisos Roles</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
					
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre Permisos Rol</th>
                          <th>Nombre Rol</th>
                          <th>Nombre Controlador</th>
                          <th>Ver</th>
                          <th>Editar</th>
                          <th>Borrar</th>
                          
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>

                      <tbody>
    					<?php $i=0;?>
    						<?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
    						<?php $i++;?>
            	        		<tr>
            	                   <td > <?php echo $i; ?>  </td>
            		               <td > <?php echo $res->nombre_permisos_rol; ?>     </td> 
            		               <td > <?php echo $res->nombre_rol; ?>   </td>
            		               <td > <?php echo $res->nombre_controladores; ?>   </td>
            		               <td > <?php if ($res->ver_permisos_rol =="t"){ echo "Si";}else{echo "No";}; ?>  </td>
            		               <td > <?php if ($res->editar_permisos_rol == "t"){ echo "Si";}else{echo "No";}; ?>  </td>
            		               <td > <?php if ($res->borrar_permisos_rol == "t"){ echo "Si";}else{echo "No";}; ?>  </td>
            		           	   <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("PermisosRoles","index"); ?>&id_permisos_rol=<?php echo $res->id_permisos_rol; ?>" class="btn btn-warning" style="font-size:65%;"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("PermisosRoles","borrarId"); ?>&id_permisos_rol=<?php echo $res->id_permisos_rol; ?>" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a>
            			                </div>
            			                
            		               </td>
            		    		</tr>
            		    		
            		    		            		    		
            		    		
            		        <?php } } ?>
                    
    					
    					
    					
    					                    				  	

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
		
      </div>
    </div>

</div>
     <!-- Bootstrap -->
    <script src="view/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    
    
    
    <!-- NProgress -->
    <script src="view/vendors/nprogress/nprogress.js"></script>
   
   
    <!-- Datatables -->
    <script src="view/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    
    
    <script src="view/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="view/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    
    
    
    <!-- Custom Theme Scripts -->
    <script src="view/build/js/custom.min.js"></script>
	
  </body>
</html>   














