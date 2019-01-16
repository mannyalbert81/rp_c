    
    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
      
    
    	
	<script>
		$(document).ready(function(){
			$("#id_provincias").change(function(){
	            // obtenemos el combo de resultado combo 2
	           var $id_cantones_vivienda = $("#id_cantones");
	       	
	            // lo vaciamos
	           var id_provincias_vivienda = $(this).val();
	          
	          
	            if(id_provincias_vivienda != 0)
	            {
	            	 $id_cantones_vivienda.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_provincias_vivienda:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Bodegas","devuelveCanton"); ?>", datos, function(resultado) {
	          		  if(resultado.length==0)
	          		   {
	          				$id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_cantones_vivienda.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_cantones_vivienda.append("<option value= " +value.id_cantones +" >" + value.nombre_cantones  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');
	            }else{
	            	var id_cantones_vivienda=$("#id_cantones");
	            	id_cantones_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	var id_parroquias_vivienda=$("#id_parroquias");
	            	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            	
	            }
	            
			});
		});
	
       
	</script>
		 
		 
		 
		 
		 
		 
		 <script>
		$(document).ready(function(){
			$("#id_cantones").change(function(){
	            // obtenemos el combo de resultado combo 2
	           var $id_parroquias_vivienda = $("#id_parroquias");
	       	
	            // lo vaciamos
	           var id_cantones_vivienda = $(this).val();
	          
	          
	            if(id_cantones_vivienda != 0)
	            {
	            	 $id_parroquias_vivienda.empty();
	            	
	            	 var datos = {
	                   	   
	            			 id_cantones_vivienda:$(this).val()
	                  };
	             
	            	
	         	   $.post("<?php echo $helper->url("Bodegas","devuelveParroquias"); ?>", datos, function(resultado) {
	          		  if(resultado.length==0)
	          		   {
	          				$id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");	
	             	   }else{
	             		    $id_parroquias_vivienda.append("<option value='0' >--Seleccione--</option>");
	          		 		$.each(resultado, function(index, value) {
	          		 			$id_parroquias_vivienda.append("<option value= " +value.id_parroquias +" >" + value.nombre_parroquias  + "</option>");	
	                     		 });
	             	   }	
	            	      
	         		  }, 'json');
	            }else{
	            	var id_parroquias_vivienda=$("#id_parroquias");
	            	id_parroquias_vivienda.find('option').remove().end().append("<option value='0' >--Seleccione--</option>").val('0');
	            	
	            	
	            	
	            }
	            
			});
		});
	
       
	</script>
		    
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
        <li class="active">Bodegas</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Bodegas</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
          
        
        <form action="<?php echo $helper->url("Bodegas","InsertaBodegas"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		    
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_bodegas" class="control-label">Nombre Productos</label>
                                                          <input type="text" class="form-control" id="nombre_bodegas" name="nombre_bodegas" value="<?php echo $resEdit->nombre_bodegas; ?>"  placeholder="Nombre Bodegas">
                                                          <input type="hidden" name="id_bodegas" id="id_bodegas" value="<?php echo $resEdit->id_bodegas; ?>" class="form-control"/>
					                                      <div id="mensaje_nombre_bodegas" class="errores"></div>
                                    </div>
                        		    </div>
									<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_provincias" class="control-label">Provincia</label>
                                                          <select name="id_provincias" id="id_provincias"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultProv as $resProv) {?>
				 												<option value="<?php echo $resProv->id_provincias; ?>" <?php if ($resProv->id_provincias == $resEdit->id_provincias )  echo  ' selected="selected" '  ;  ?> ><?php echo $resProv->nombre_provincias; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_provincias" class="errores"></div>
                                    </div>
                                    </div>  
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_cantones" class="control-label">Cantón</label>
                                                          <select name="id_cantones" id="id_cantones"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultCant as $resCant) {?>
				 												<option value="<?php echo $resCant->id_cantones; ?>" <?php if ($resCant->id_cantones == $resEdit->id_cantones )  echo  ' selected="selected" '  ;  ?> ><?php echo $resCant->nombre_cantones; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_cantones" class="errores"></div>
                                    </div>
                                    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_parroquias" class="control-label">Parroquias</label>
                                                          <select name="id_parroquias" id="id_parroquias"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultParr as $resParr) {?>
				 												<option value="<?php echo $resParr->id_parroquias; ?>" <?php if ($resParr->id_parroquias == $resEdit->id_parroquias )  echo  ' selected="selected" '  ;  ?> ><?php echo $resParr->nombre_parroquias; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_parroquias" class="errores"></div>
                                    </div>
                                    </div>                   			
                        	
                        	    </div>
                    			
                    			
                    			
                    			                   			
                    			
                        		
                                 
                                
                    		     <?php } } else {?>
                    		    
                    		   
								 <div class="row">
                        		    
                        		    
                        		   <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_bodegas" class="control-label">Nombre Bodegas</label>
                                                          <input type="text" class="form-control" id="nombre_bodegas" name="nombre_bodegas" value=""  placeholder="Nombre Bodegas">
                                                           <div id="mensaje_nombre_bodegas" class="errores"></div>
                                    </div>
                        		    </div>
									
                        			
                        			<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_provincias" class="control-label">Provincia</label>
                                                          <select name="id_provincias" id="id_provincias"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultProv as $resProv) {?>
				 												<option value="<?php echo $resProv->id_provincias; ?>"  ><?php echo $resProv->nombre_provincias; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_provincias" class="errores"></div>
                                    </div>
                                    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_cantones" class="control-label">Cantón</label>
                                                          <select name="id_cantones" id="id_cantones"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultCant as $resCant) {?>
				 												<option value="<?php echo $resCant->id_grupos; ?>"  ><?php echo $resCant->nombre_cantones; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_cantones" class="errores"></div>
                                    </div>
                                    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_parroquias" class="control-label">Parroquias</label>
                                                          <select name="id_parroquias" id="id_parroquias"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultParr as $resParr) {?>
				 												<option value="<?php echo $resParr->nombre_parroquias; ?>"  ><?php echo $resParr->nombre_parroquias; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_parroquias" class="errores"></div>
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
    </section>
    
    
    
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Listado de Bodegas Registrados</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        
       <div class="ibox-content">  
      <div class="table-responsive">
        
        <table  class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Nombre Bodega</th>
                          <th>Provincia</th>
                          <th>Cantón</th>
                          <th>Parroquia</th>
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
            	                   <td > <?php echo $res->nombre_bodegas; ?>   </td>
            		               <td > <?php echo $res->nombre_provincias; ?>   </td>
            		               <td > <?php echo $res->nombre_cantones; ?>   </td>
            		               <td > <?php echo $res->nombre_parroquias; ?>   </td>
            		               
            		               
            		              
            		           	   <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("Bodegas","index"); ?>&id_bodegas=<?php echo $res->id_bodegas; ?>" class="btn btn-warning" style="font-size:65%;" data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("Bodegas","borrarId"); ?>&id_bodegas=<?php echo $res->id_bodegas; ?>" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
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
    
    
            <script>
           // Campos Vacíos
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var nombre_bodegas = $("#nombre_bodegas").val();
		    	var id_provincias = $("#id_provincias").val();
		    	var id_cantones = $("#id_cantones").val();
		    	var id_parroquias = $("#id_parroquias").val();
		    	
		
		    	
		    	
		    	if (nombre_bodegas == "")
		    	{
			    	
		    		$("#mensaje_nombre_bodegas").text("Introduzca Un Nombre");
		    		$("#mensaje_nombre_bodegas").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_bodegas").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (id_provincias == 0)
		    	{
			    	
		    		$("#mensaje_id_provincias").text("Introduzca Una Provincia");
		    		$("#mensaje_id_provincias").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_provincias").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (id_cantones == 0)
		    	{
			    	
		    		$("#mensaje_id_cantones").text("Introduzca Un Cantón");
		    		$("#mensaje_id_cantones").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_cantones").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (id_parroquias == 0)
		    	{
			    	
		    		$("#mensaje_id_parroquias").text("Introduzca Ua Parroquia");
		    		$("#mensaje_id_parroquias").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_parroquias").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
		    	
				


		    	
			}); 


		        $( "#nombre_bodegas" ).focus(function() {
				  $("#mensaje_nombre_bodegas").fadeOut("slow");
			    });

		        $( "#id_provincias" ).focus(function() {
					  $("#mensaje_id_provincias").fadeOut("slow");
				    });
		        $( "#id_cantones" ).focus(function() {
					  $("#mensaje_id_cantones").fadeOut("slow");
				    });
		        $( "#id_parroquias" ).focus(function() {
					  $("#mensaje_id_parroquias").fadeOut("slow");
				    });
	
				    
		}); 

	</script>	
	
	
  </body>
</html>   



