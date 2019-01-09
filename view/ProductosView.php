<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
 
   <?php include("view/modulos/links_css.php"); ?>
   

        
			        
    </head>
    
    
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    
       <script>
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_controladores = $("#nombre_controladores").val();
		    	
		    	
		    	
		    	if (nombre_controladores == "")
		    	{
			    	
		    		$("#mensaje_nombres").text("Introduzca Un Controlador");
		    		$("#mensaje_nombres").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombres").fadeOut("slow"); //Muestra mensaje de error
		            
				}   


		    	
			}); 


		        $( "##mensaje_nombres" ).focus(function() {
				  $("##mensaje_nombres").fadeOut("slow");
			    });
		        		      
				    
		}); 

	</script>
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
        <li class="active">Productos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Productos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
                  <div class="box-body">

						<form action="<?php echo $helper->url("Productos","InsertaProductos"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
                              <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
            
             						 <div class="row">
             						 
             						<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_grupos" class="control-label">Grupos</label>
                                                          <select name="id_grupos" id="id_grupos"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultRol as $resRol) {?>
				 												<option value="<?php echo $resRol->id_grupos; ?>" <?php if ($resRol->id_grupos == $resEdit->id_grupos )  echo  ' selected="selected" '  ;  ?> ><?php echo $resRol->nombre_grupos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_grupos" class="errores"></div>
                                    </div>
                                    </div>
             						 
                        		    	<div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		   						 
                                                              <label for="codigo_productos" class="control-label">Codigo Producto</label>
                                                              <input type="text" class="form-control" id="codigo_productos" name="codigo_productos" value="<?php echo $resEdit->codigo_productos; ?>"  placeholder="Codigo Productos" required/>
                                                               <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                          <div id="mensaje_codigo_productos" class="errores"></div>
					                                          				                                          
                            								
                            					                                          
                                        </div>
                            		  </div>
                            		  
                            		    <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		   						 
                                                              <label for="marca_productos" class="control-label">Marca Productos</label>
                                                              <input type="text" class="form-control" id="marca_productos" name="marca_productos" value="<?php echo $resEdit->marca_productos; ?>"  placeholder="Marca Productos" required/>
                                                               <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                          <div id="mensaje_marca_productos" class="errores"></div>
					                                          				                                          
                            								
                            					                                          
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		   						 
                                                              <label for="nombre_productos" class="control-label">Nombre Productos</label>
                                                              <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" value="<?php echo $resEdit->nombre_productos; ?>"  placeholder="Nombre Productos" required/>
                                                               <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                          <div id="mensaje_nombre_productos" class="errores"></div>
					                                          				                                          
                            								
                            					                                          
                                        </div>
                            		  </div>
                            		  </div>
                            		  
                            		 <div class="row"> 
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		   						 
                                                              <label for="descripcion_productos" class="control-label">Descripcion Productos</label>
                                                              <input type="text" class="form-control" id="descripcion_productos" name="descripcion_productos" value="<?php echo $resEdit->descripcion_productos; ?>"  placeholder="Descripcion Productos" required/>
                                                               <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                          <div id="mensaje_descripcion_productos" class="errores"></div>
					                                          				                                          
                            								
                            					                                          
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		   						 
                                                              <label for="unidad_medida_productos" class="control-label">Unidad Medida Productos</label>
                                                              <input type="text" class="form-control" id="unidad_medida_productos" name="unidad_medida_productos" value="<?php echo $resEdit->unidad_medida_productos; ?>"  placeholder="Unidad Medida Productos" required/>
                                                               <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                          <div id="mensaje_unidad_medida_productos" class="errores"></div>
					                                          				                                          
                            								
                            					                                          
                                        </div>
                            		  </div>
                            		  

                        			</div>	
                        		
            
            
							    
							     <?php } } else {?>
							    
							    
							    
							    	 <div class="row">
							    	 
							    	<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_grupos" class="control-label">Grupos</label>
                                                          <select name="id_grupos" id="id_grupos"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultRol as $resRol) {?>
				 												<option value="<?php echo $resRol->id_grupos; ?>"  ><?php echo $resRol->nombre_grupos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_grupos" class="errores"></div>
                                    </div>
                                    </div>
							    	 
                        		        <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		    					  
                                                              <label for="codigo_productos" class="control-label">Código Productos</label>
                                                              <input  type="text" class="form-control" id="codigo_productos" name="codigo_productos" value=""  placeholder="Codigo Productos" required/>
                                                              <div id="mensaje_codigo_productos" class="errores"></div>
                                                              	
                                                              
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		    					  
                                                              <label for="marca_productos" class="control-label">Marca Productos</label>
                                                              <input  type="text" class="form-control" id="marca_productos" name="marca_productos" value=""  placeholder="Marca Productos" required/>
                                                              <div id="mensaje_marca_productos" class="errores"></div>
                                                              	
                                                              
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		    					  
                                                              <label for="nombre_productos" class="control-label">Marca Productos</label>
                                                              <input  type="text" class="form-control" id="nombre_productos" name="nombre_productos" value=""  placeholder="Nombre Productos" required/>
                                                              <div id="mensaje_nombre_productos" class="errores"></div>
                                                              	
                                                              
                                        </div>
                            		  </div>
                            		  <div class="row">
                            		  <div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		    					  
                                                              <label for="descripcion_productos" class="control-label">Descripcion Productos</label>
                                                              <input  type="text" class="form-control" id="descripcion_productos" name="descripcion_productos" value=""  placeholder="Descripcion Productos" required/>
                                                              <div id="mensaje_descripcion_productos" class="errores"></div>
                                                              	
                                                              
                                        </div>
                            		  </div>
                        				
                        			
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                            		    <div class="form-group">
                            		    					  
                                                              <label for="unidad_medida_productos" class="control-label">Unidad Medida Productos</label>
                                                              <input  type="text" class="form-control" id="unidad_medida_productos" name="unidad_medida_productos" value=""  placeholder="Unidad Medida Productos" required/>
                                                              <div id="mensaje_unidad_medida_productos" class="errores"></div>
                                                              	
                                                              
                                        </div>
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
              
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Listado de Controladores Registrados</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
        
        
       <div class="ibox-content">  
      <div class="table-responsive">
        
		<table  class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre Controlador</th>
                          <th>Editar</th>
                          <th>Borrar</th>
                        </tr>
                      </thead>


                      <tbody>
                      <?php $i=0;?>
    						<?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
    						<?php $i++;?>
            	        		<tr>
            	                   <td > <?php echo $i; ?>  </td>
            		               <td > <?php echo $res->nombre_controladores; ?>     </td> 
            		               <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("Controladores","index"); ?>&id_controladores=<?php echo $res->id_controladores; ?>" class="btn btn-warning" style="font-size:65%;"data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("Controladores","borrarId"); ?>&id_controladores=<?php echo $res->id_controladores; ?>" class="btn btn-danger" style="font-size:65%;"data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
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
        </section>
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

       
       

 	
	
	
  </body>
</html>   

