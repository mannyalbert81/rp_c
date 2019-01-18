    
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
        <li><a href="<?php echo $helper->url("Consulta de Productos","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Consulta de Productos</li>
      </ol>
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
        
        <form action="<?php echo $helper->url("Productos","consulta"); ?>
 
                       </form>
        
        
       <div class="ibox-content">  
      <div class="table-responsive">
        
		<table  class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Grupos</th>
                          <th>Código</th>
                          <th>Marca</th>
                          <th>Nombre</th>
                           <th>Descripcion</th>
                          <th>Unidad De M.</th>
                          <th>ULT Precio</th>
                          <th>Editar</th>
                          <th>Eliminar</th>

                        </tr>
                      </thead>

                      <tbody>
    					<?php $i=0;?>
    						<?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
    						<?php $i++;?>
            	        		<tr>
            	                   <td > <?php echo $i; ?>  </td>
            		               <td > <?php echo $resGup->nombre_grupos; ?>     </td> 
            		               <td > <?php echo $res->codigo_productos; ?>   </td>
            		               <td > <?php echo $res->marca_productos; ?>   </td>
            		               <td > <?php echo $res->nombre_productos; ?>   </td>
            		               <td > <?php echo $res->descripcion_productos; ?>   </td>
            		               <td > <?php echo $resUni->nombre_unidad_medida; ?>   </td>
            		               <td > <?php echo $res->ult_precio_productos; ?>   </td>
            		              
            		           	   <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("Productos","index"); ?>&id_productos=<?php echo $res->id_productos; ?>" class="btn btn-warning" style="font-size:65%;" data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("Productos","borrarId"); ?>&id_productos=<?php echo $res->id_productos; ?>" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
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

    