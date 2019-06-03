    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
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
          <h3 class="box-title">Registrar Diarios Tipo</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
          
        
        <form action="<?php echo $helper->url("CoreDiarioTipoCabeza","InsertaCoreDiarioTipoCabeza"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		    
                        		   <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_tipo_credito" class="control-label">Tipo Crédito</label>
                                                          <select name="id_tipo_credito" id="id_tipo_credito"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultTipoCre as $resTipoCre) {?>
				 												<option value="<?php echo $resTipoCre->id_tipo_credito; ?>" <?php if ($resTipoCre->id_tipo_credito == $resEdit->id_tipo_credito)  echo  ' selected="selected" '  ;  ?> ><?php echo $resTipoCre->nombre_tipo_credito; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_tipo_credito" class="errores"></div>
                                    </div>
                                    </div>  
                        		
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		  <div class="form-group">
                                  <label for="nombre_diario_tipo_cabeza" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_diario_tipo_cabeza" name="nombre_diario_tipo_cabeza" value="<?php echo $resEdit->nombre_diario_tipo_cabeza; ?>"  placeholder="Nombre de Diario Tipo">
                                  <div id="mensaje_nombre_diario_tipo_cabeza" class="errores"></div>
                                    </div>
                        		    </div>   
                        		    
                        		   <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_estado" class="control-label">Estado</label>
                                                          <select name="id_estado" id="id_estado"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultEst as $resEst) {?>
				 												<option value="<?php echo $resEst->id_estado; ?>" <?php if ($resEst->id_estado == $resEdit->id_estado)  echo  ' selected="selected" '  ;  ?> ><?php echo $resEst->nombre_estado; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_estado" class="errores"></div>
                                    </div>
                                    </div>
                    	
                        		    </div>
                                 
                                
                    		     <?php } } else {?>
                    		    
                    		   
		                    <div class="row">
		                    
		                    	   <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_tipo_credito" class="control-label">Tipo Crédito</label>
                                                          <select name="id_tipo_credito" id="id_tipo_credito"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultTipoCre as $resTipoCre) {?>
				 												<option value="<?php echo $resTipoCre->id_tipo_credito; ?>" ><?php echo $resTipoCre->nombre_tipo_credito; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_tipo_credito" class="errores"></div>
                                    </div>
                                    </div> 
                                    
		             	<div class="col-xs-12 col-md-3 col-md-3 ">
                        		  <div class="form-group">
                                  <label for="nombre_diario_tipo_cabeza" class="control-label">Nombre</label>
                                  <input type="text" class="form-control" id="nombre_diario_tipo_cabeza" name="nombre_diario_tipo_cabeza" value=""  placeholder="Nombre de Diario Tipo">
                                  <div id="mensaje_nombre_diario_tipo_cabeza" class="errores"></div>
                                    </div>
                        		    </div>   
                               
	       		               
	       		               	   <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_estado" class="control-label">Estado</label>
                                                          <select name="id_estado" id="id_estado"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultEst as $resEst) {?>
				 												<option value="<?php echo $resEst->id_estado; ?>" ><?php echo $resEst->nombre_estado; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_estado" class="errores"></div>
                                    </div>
                                    </div>
	       		               
	       		            </div>
                		    
                    	         	                     	           	
                    		     <?php } ?>
                    		
                    		    <br>  
                    		    <div class="row">
                    		    <div class="col-xs-12 col-md-4 col-lg-4" style="text-align: left;">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class='glyphicon glyphicon-plus'></i> Guardar</button>
                                                      <a href="index.php?controller=CoreDiarioTipoCabeza&action=index" class="btn btn-primary"><i class='glyphicon glyphicon-remove'></i> Cancelar</a>
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
          <h3 class="box-title">Lista De Diarios Tipo</h3>
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
                          <th>Tipo Crédito</th>
                          <th>Nombre</th>
                          <th>Estado</th>
                     
                         
                        </tr>
                      </thead>

                      <tbody>
    					<?php $i=0;?>
    						<?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
    						<?php $i++;?>
            	        		<tr>
            	                   <td > <?php echo $i; ?>  </td>
            		               <td > <?php echo $res->nombre_tipo_credito; ?>     </td> 
            		               <td > <?php echo $res->nombre_diario_tipo_cabeza; ?>     </td> 
            		               <td > <?php echo $res->nombre_estado; ?>     </td> 
            		               <td>
            			           		<div class="right">
            			                    <a href="<?php echo $helper->url("CoreDiarioTipocabeza","index"); ?>&id_diario_tipo_cabeza=<?php echo $res->id_diario_tipo_cabeza; ?>" class="btn btn-warning" style="font-size:65%;" data-toggle="tooltip" title="Editar"><i class='glyphicon glyphicon-edit'></i></a>
            			                </div>
            			            
            			             </td>
            			             <td>   
            			                	<div class="right">
            			                    <a href="<?php echo $helper->url("CoreDiarioTipocabeza","borrarId"); ?>&id_diario_tipo_cabeza=<?php echo $res->id_diario_tipo_cabeza; ?>" class="btn btn-danger" style="font-size:65%;" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
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
             <script>
            $(document).ready(function(){
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var id_tipo_credito = $("#id_tipo_credito").val();
		     	var nombre_diario_tipo_cabeza = $("#nombre_diario_tipo_cabeza").val();
		    	var id_estado = $("#id_estado").val();
					   
				    	
		    	if (id_tipo_credito == 0)
		    	{
		    		$("#mensaje_id_tipo_credito").text("Seleccione un Tipo de Crédito");
		    		$("#mensaje_id_tipo_credito").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_tipo_credito").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		     	if (nombre_diario_tipo_cabeza == "")
		    	{
		    		$("#mensaje_nombre_diario_tipo_cabeza").text("Introduzca Un Nombre");
		    		$("#mensaje_nombre_diario_tipo_cabeza").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_diario_tipo_cabeza").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
		     	if (id_estado == 0)
		    	{
		    		$("#mensaje_id_estado").text("Seleccione Un Estado");
		    		$("#mensaje_id_estado").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_estado").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
		    	
		    	
			}); 


		        $( "#id_tipo_credito" ).focus(function() {
				  $("#mensaje_id_tipo_credito").fadeOut("slow");
			    });
		        $( "#nombre_diario_tipo_cabeza" ).focus(function() {
					  $("#mensaje_nombre_diario_tipo_cabeza").fadeOut("slow");
				    });
		        $( "#id_estado" ).focus(function() {
					  $("#mensaje_id_estado").fadeOut("slow");
				    });
		}); 

	</script>	
	
	
	
  </body>
</html>   



