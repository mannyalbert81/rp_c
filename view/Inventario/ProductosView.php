    <!DOCTYPE HTML>

	<html lang="es">
    <head>
    <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
    <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
  
    <?php include("view/modulos/links_css.php"); ?>		

	</head>
 
    <body class="hold-transition skin-blue fixed sidebar-mini">
    
     <?php
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $DateString = (string)$fecha;
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
          <h3 class="box-title">Registrar Productos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
          
        <form action="<?php echo $helper->url("Productos","InsertaProductos"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_grupos" class="control-label">Grupos</label>
                                                          <select name="id_grupos" id="id_grupos"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultGrup as $resGup) {?>
				 												<option value="<?php echo $resGup->id_grupos; ?>" <?php if ($resGup->id_grupos == $resEdit->id_grupos )  echo  ' selected="selected" '  ;  ?> ><?php echo $resGup->nombre_grupos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_grupos" class="errores"></div>
                                    </div>
                                    </div>
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="codigo_productos" class="control-label">Código Productos</label>
                                                          <input type="text" class="form-control" id="codigo_productos" name="codigo_productos" value="<?php echo $resEdit->codigo_productos; ?>"  placeholder="Código Productos" readonly onkeypress="return numeros(event)">
                                                          <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                      <div id="mensaje_codigo_productos" class="errores"></div>
                                    </div>
                        		    </div>   
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="marca_productos" class="control-label">Marca Productos</label>
                                                          <input type="text" class="form-control" id="marca_productos" name="marca_productos" value="<?php echo $resEdit->marca_productos; ?>"  placeholder="Marca Productos">
                                                          <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                      <div id="mensaje_marca_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_productos" class="control-label">Nombres Productos</label>
                                                          <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" value="<?php echo $resEdit->nombre_productos; ?>"  placeholder="Nombres Productos">
                                                          <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                      <div id="mensaje_nombre_productos" class="errores"></div>
                                    </div>
                        		    </div>                     			
                        	
                        	    </div>
                    			
                    			
                    			<div class="row">
                    			                   			
                    			<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="descripcion_productos" class="control-label">Descripción Productos</label>
                                                          <input type="text" class="form-control" id="descripcion_productos" name="descripcion_productos" value="<?php echo $resEdit->descripcion_productos; ?>"  placeholder="Descripcion">
                                                          <input type="hidden" name="id_productos" id="id_productos" value="<?php echo $resEdit->id_productos; ?>" class="form-control"/>
					                                      <div id="mensaje_descripcion_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		<div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_unidad_medida" class="control-label">Unidad Medida</label>
                                                          <select name="id_unidad_medida" id="id_unidad_medida"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultUni as $resUni) {?>
				 												<option value="<?php echo $resUni->id_unidad_medida; ?>" <?php if ($resUni->id_unidad_medida == $resEdit->id_unidad_medida )  echo  ' selected="selected" '  ;  ?> ><?php echo $resUni->nombre_unidad_medida; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_unidad_medida" class="errores"></div>
                                    </div>
                                    </div>
      
                        		<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="ult_precio_productos" class="control-label">ULT Precio</label>
                                                      <input type="text" class="form-control cantidades1" id="ult_precio_productos" name="ult_precio_productos" value='<?php echo $resEdit->ult_precio_productos; ?>' 
                                                      data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false">
                                                      <div id="mensaje_ult_precio_productos" class="errores"></div>
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
																<?php foreach($resultGrup as $resGup) {?>
				 												<option value="<?php echo $resGup->id_grupos; ?>"  ><?php echo $resGup->nombre_grupos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_grupos" class="errores"></div>
                                    </div>
                                    </div>
                        			
                        			
                        			<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="codigo_productos" class="control-label">Código Productos</label>
                                                          <input type="text" class="form-control" id="codigo_productos" name="codigo_productos" value=""  placeholder="Código Productos" onkeypress="return numeros(event)">
                                                           <div id="mensaje_codigo_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="marca_productos" class="control-label">Marca Productos</label>
                                                          <input type="text" class="form-control" id="marca_productos" name="marca_productos" value=""  placeholder="Marca Productos">
                                                           <div id="mensaje_marca_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_productos" class="control-label">Nombres Productos</label>
                                                          <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" value=""  placeholder="Nombres Productos">
                                                           <div id="mensaje_nombre_productos" class="errores"></div>
                                    </div>
                        		    </div>
									</div>
									
									<div class="row">
									
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="descripcion_productos" class="control-label">Descripción Productos</label>
                                                          <input type="text" class="form-control" id="descripcion_productos" name="descripcion_productos" value=""  placeholder="Descripción">
                                                           <div id="mensaje_descripcion_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_unidad_medida" class="control-label">Unidad Medida</label>
                                                          <select name="id_unidad_medida" id="id_unidad_medida"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultUni as $resUni) {?>
				 												<option value="<?php echo $resUni->id_unidad_medida; ?>"  ><?php echo $resUni->nombre_unidad_medida; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_unidad_medida" class="errores"></div>
                                    </div>
                                    </div>
								<div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                      <label for="ult_precio_productos" class="control-label">ULT Precio</label>
                                                      <input type="text" class="form-control cantidades1" id="ult_precio_productos" name="ult_precio_productos" value='0.00' 
                                                      data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false">
                                                      <div id="mensaje_ult_precio_productos" class="errores"></div>
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
          <h3 class="box-title">Listado de Productos Registrados</h3>
          <button type="submit" id="btExportar" name="exportar" class="btn btn-info"><i class="fa fa-file-excel-o"></i></button>
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
        
        <table id="podructtable" class="table table-striped table-bordered table-hover dataTables-example">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Grupos</th>
                          <th>Código</th>
                          <th>Marca</th>
                          <th>Nombre</th>
                           <th>Descripción</th>
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
            		               <td > <?php echo $res->nombre_grupos; ?>     </td> 
            		               <td > <?php echo $res->codigo_productos; ?>   </td>
            		               <td > <?php echo $res->marca_productos; ?>   </td>
            		               <td > <?php echo $res->nombre_productos; ?>   </td>
            		               <td > <?php echo $res->descripcion_productos; ?>   </td>
            		               <td > <?php echo $res->nombre_unidad_medida; ?>   </td>
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
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script src="view/Inventario/js/Productos.js?1"></script>  
  </body>
</html>   



