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
        <li class="active">Controladores</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Controladores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
                  
                  <div class="box-body">

						<form action="<?php echo $helper->url("Controladores","InsertaControladores"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
                              <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
            
             						 <div class="row">
                        		    <div class="col-xs-12 col-md-4 col-md-4 ">
                            		    <div class="form-group">
                                                              <label for="nombre_controladores" class="control-label">Nombres Controladores</label>
                                                              <input type="text" class="form-control" id="nombre_controladores" name="nombre_controladores" value="<?php echo $resEdit->nombre_controladores; ?>"  placeholder="Nombre Controlador">
                                                               <input type="hidden" name="id_controladores" id="id_controladores" value="<?php echo $resEdit->id_controladores; ?>" class="form-control"/>
					                                          <div id="mensaje_nombres" class="errores"></div>
                                        </div>
                            		  </div>
                        			</div>	
            
            
							    
							     <?php } } else {?>
							    
							    
							    
							    	 <div class="row">
                        		    <div class="col-xs-12 col-md-4 col-md-4 ">
                            		    <div class="form-group">
                                                              <label for="nombre_controladores" class="control-label">Nombres Controladores</label>
                                                              <input type="text" class="form-control" id="nombre_controladores" name="nombre_controladores" value=""  placeholder="Nombre Controlador">
                                                              <div id="mensaje_nombres" class="errores"></div>
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
              
              
              
        
      <section class="content">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Listado de Controladores Registrados</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
                    
					
                    <table id="example1" class="table table-bordered table-striped">
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
            			                    <a href="<?php echo $helper->url("Controladores","index"); ?>&id_controladores=<?php echo $res->id_controladores; ?>" class="btn btn-warning" style="font-size:65%;"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("Controladores","borrarId"); ?>&id_controladores=<?php echo $res->id_controladores; ?>" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a>
            			                </div>
            			              
            		               </td>
            		    		</tr>
            		        <?php } } ?>
                    
                    </tbody>
                    </table>
		</div>
        </div>
        </section>
            
              
              
		
       
      </div>
    </section>
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    
    
    
    
        <?php include("view/modulos/links_js.php"); ?>
	
	
  </body>
</html>   




