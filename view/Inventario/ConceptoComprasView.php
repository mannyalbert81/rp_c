    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
    
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
        <li class="active">Productos</li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Concepto Compras</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
        
        <form action="<?php echo $helper->url("ConceptoCompras","InsertaConceptoCompras"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		  <div class="form-group">
                                  <label for="nombre_concepto_compras" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_concepto_compras" name="nombre_concepto_compras" value="<?php echo $resEdit->nombre_concepto_compras; ?>"  placeholder="Nombre">
                                  <span class="help-block"></span>
                                    </div>
                        		    </div>   
                        	
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                        		  <div class="form-group">
                                  <label for="porcentaje_iva_concepto_compras" class="control-label">Iva</label>
                                  <input type="text" class="form-control" id="porcentaje_iva_concepto_compras" name="porcentaje_iva_concepto_compras" value="<?php echo $resEdit->porcentaje_iva_concepto_compras; ?>"  placeholder="Iva">
                                  <span class="help-block"></span>
                                    </div>
                        		    </div>   
                        		    
                        		    		<div class="col-xs-12 col-md-3 col-md-3 ">
                        		  <div class="form-group">
                                  <label for="porcentaje_renta_concepto_compras" class="control-label">Renta</label>
                                  <input type="text" class="form-control" id="porcentaje_renta_concepto_compras" name="porcentaje_renta_concepto_compras" value="<?php echo $resEdit->porcentaje_renta_concepto_compras; ?>"  placeholder="Renta">
                                  <span class="help-block"></span>
                                    </div>
                        		    </div>   	    
                    	
                        		    </div>
                    		     <?php } } else {?>
								 <div class="row">
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                        	    <div class="form-group">
                                  <label for="nombre_concepto_compras" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_concepto_compras" name="nombre_concepto_compras" value=""  placeholder="Nombre">
                                  <span class="help-block"></span>
            					</div>
	       		    </div>
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                        	    <div class="form-group">
                                  <label for="porcentaje_iva_concepto_compras" class="control-label">Iva</label>
                                  <input type="text" class="form-control" id="porcentaje_iva_concepto_compras" name="porcentaje_iva_concepto_compras" value=""  placeholder="Iva">
                                  <span class="help-block"></span>
            					</div>
	       		    </div>
               
               		<div class="col-xs-12 col-md-3 col-md-3 ">
                        	    <div class="form-group">
                                  <label for="porcentaje_renta_concepto_compras" class="control-label">Renta</label>
                                  <input type="text" class="form-control" id="porcentaje_renta_concepto_compras" name="porcentaje_renta_concepto_compras" value=""  placeholder="Renta">
                                  <span class="help-block"></span>
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
          <h3 class="box-title">Listado de Concepto Compras</h3>
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
                          <th>Nombre</th>
                          <th>Iva</th>
                          <th>Renta</th>
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
            		               <td > <?php echo $res->nombre_concepto_compras; ?>     </td> 
            		               <td > <?php echo $res->porcentaje_iva_concepto_compras; ?>     </td> 
            		               <td > <?php echo $res->porcentaje_renta_concepto_compras; ?>     </td> 
            		                
            		           	   <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("ConceptoCompras","index"); ?>&id_concepto_compras=<?php echo $res->id_concepto_compras; ?>" class="btn btn-warning" style="font-size:65%;" data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("ConceptoCompras","borrarId"); ?>&id_concepto_compras=<?php echo $res->id_concepto_compras; ?>" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
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
		
 	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
	<script src="view/Inventario/js/Bodegas.js?3.1" ></script>
  </body>
</html>   



