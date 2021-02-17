<!DOCTYPE HTML>
<html lang="es">
    
    <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">    
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>


    <?php include("view/modulos/links_css.php"); ?>
   
    <style type="text/css">
  	 .letrasize10{
        font-size: 10px;
     }
     .letrasize11{
        font-size: 11px;
     }
     .letrasize12{
        font-size: 12px;
     }
  	</style>
	
	        
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
                <li class="active">Comprobantes</li>
          	</ol>
		</section>   

    	<section class="content">
     		<div class="box box-primary">
     			<div class="box-header">
          			<h3 class="box-title">Buscar Comprobantes</h3>
          			<div class="box-tools pull-right">
            			<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              				<i class="fa fa-minus">
          				</i></button>
            
          			</div>
        		</div>
        
                  
              <div class="box-body">

			 	<div class="row">
								 	  
                    <div class="col-xs-6 col-md-3 col-lg-3" id="div_entidad">
                      	<div class="form-group">
                           	<label for="desde" class="control-label">Entidad:</label>
            			  	<select name="id_entidades" id="id_entidades"  class="form-control" readonly>
                				<?php foreach($resultEnt as $res) {?>
                				<option value="<?php echo $res->id_entidades; ?>"><?php echo $res->nombre_entidades;  ?> </option>
                  			    <?php } ?>
            				</select>
                            <div id="desde" class="errores"></div>
                        </div>
                    </div>  
                               
                    <div class="col-xs-6 col-md-3 col-lg-3">
                  	<div class="form-group">
                   	<label for="desde" class="control-label">Tipo Comprobante:</label>
 				    <select name="id_tipo_comprobantes" id="id_tipo_comprobantes"  class="form-control" >
					<option value="0"><?php echo "--TODOS--";  ?> </option>
					<?php foreach($resultTipCom as $res) {?>
					<option value="<?php echo $res->id_tipo_comprobantes; ?>"><?php echo $res->nombre_tipo_comprobantes;  ?> </option>
        			<?php } ?>
					</select>
					<div id="desde" class="errores"></div>
                    </div>
                    </div> 
                             
                    <div class="col-xs-6 col-md-3 col-lg-3">
                  	<div class="form-group">
                    <label for="desde" class="control-label">Nº Comprobante:</label>
             	  	<input type="text"  name="numero_ccomprobantes" id="numero_ccomprobantes" value="" class="form-control"/> 
			        <div id="desde" class="errores"></div>
                    </div>
              		</div> 
                              		        	
                    <div class="col-xs-6 col-md-3 col-lg-3">
                  	<div class="form-group">
                   	<label for="desde" class="control-label">Desde:</label>
               	  	<input type="text"  name="fecha_desde" id="fecha_desde" value="" class="form-control "/> 
			        <div id="desde" class="errores"></div>
                    </div>
                    </div>
                                    
                    <div class="col-xs-6 col-md-3 col-lg-3 ">
                	<div class="form-group">
                    <label for="hasta" class="control-label">Hasta:</label>
       		  	    <input type="text"  name="fecha_hasta" id="fecha_hasta" value="" class="form-control "/> 
                    <div id="hasta" class="errores"></div>
                    </div>
                    </div>  
                    
                    
                            
    		    </div>	
    		    
    		    <div class="row">
    		    	<div class="col-xs-6 col-md-3 col-lg-3 ">
        			    <div class="form-group">
                        <label for="datos_proveedor" class="control-label">CI/RUC/APELLIDOS/NOMBRES:</label>
           		  	    <input type="text"  name="datos_proveedor" id="datos_proveedor" value="" class="form-control "/> 
                        </div>
                    </div>
		    	</div>   
					   
       			<div class="row">
    			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
        	   		<div class="form-group">
    	            <button type="button" id="buscar" name="buscar" value="Buscar"   class="btn btn-info" ><i class="glyphicon glyphicon-search"></i></button>
            		</div>
        		    </div>
		    	</div>
		    	
		    	<hr>
		    	
		    	<div class="row">
             			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    		<div id="div_comprobantes" class="letrasize11">
    <!--                 		display compact -->
    <!--  table tablesorter table-striped table-bordered nowrap -->
                        		<table id="tblcomprobantes" class="table table-bordered display compact">
                        			<thead>
                        				<tr class="info">
                        					<th>#</th>
                            				<th>Entidad</th>
                            				<th>Tipo</th>
                            				<th>Concepto</th>
                            				<th>Numero Comprobante</th>
                            				<th>Valor</th>
                            				<th>Fecha</th>
                            				<th>Estado</th>
                            				<th>Acciones</th>   
                        				</tr>                    				
                        			</thead>  
                        			<tbody>
                        				
                        			</tbody>                  			
                        			<tfoot>
                        				
                        			</tfoot>
                        		</table>
                    		</div>
             			</div>
             		</div>
                          
                <hr>
                	
              	<div id="comprobantes" ></div>	
				<div id="div_comprobantes"></div>
                      	   
            </div>
        </div>
    </section>
              
    
    
  </div>
  
  <!-- PARA MODALES -->
  
  <!-- BEGIN MODAL VER COMPROBANTES  -->
  <div class="modal fade" id="mod_procesar_comprobante" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-primary color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DETALLES COMPROBANTES</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		<div class="row">
          			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="md_numero_comprobantes" class	="col-sm-4 control-label" >Numero Comprobantes:</label>
                				<div class="col-sm-8">
                                  	<input type="text" id="md_numero_comprobantes" name="md_numero_comprobantes" class="form-control" value="">
                                  	<input type="hidden" id="md_id_comprobantes" value="0">
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
    				<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="md_fecha_comprobantes" class="col-sm-4 control-label" >Fecha Comprobantes:</label>
                				<div class="col-sm-8">
                                  	<input type="text" id="md_fecha_comprobantes" name="md_fecha_comprobantes" class="form-control" value="">
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
          		</div> 
          		<div class="row">
          			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="md_numero_comprobantes" class="col-sm-4 control-label" >Valor:</label>
                				<div class="col-sm-8">
                                  	<input type="text" id="md_valor_comprobantes" name="md_valor_comprobantes" class="form-control" value="">
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
    				
          		</div>  
          		
          		<hr>
          		
          		<div class="row">
          			<div class="col-lg-12 col-md-12 col-xs-12"> 
          				<table id="tbl_detalle_comprobantes" class="table table-striped table-bordered table-sm " cellspacing="0"  width="100%">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Codigo</th>
                              <th>Nombre</th>
                              <th>Descripcion</th>
                              <th>Debe</th>
                              <th>Haber</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </tbody>
                        </table>  
          			</div>
          			
          		</div>
          		
          		<hr>
          		
          		<div class="row">
          			<div class="col-lg-6 col-md-6 col-xs-12 hide" id="md_div_contabilizar">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">                				
                				<div class="col-sm-4">
                                  	<!-- <button type="button" id="btnDistribuir" name="btnDistribuir" class="btn btn-block btn-sm btn-default">DISTRIBUIR</button> -->
                                 </div>
                                 <div class="col-sm-8" >
                                  	<button type="button" id="md_btncontabilizar" name="md_btncontabilizar" onclick="procesarComprobantes()" class="btn btn-block btn-sm btn-default">
                                  		<i class="fa fa-check" aria-hidden="true"></i> CONTABILIZAR
                              		</button>
                                 </div>
                                
                			 </div>        			 
            			</div>
    				</div>
    				<div class="col-lg-6 col-md-6 col-xs-12  hide" id="md_div_anular">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">                				
                				<div class="col-sm-4">
                                  	<!-- <button type="button" id="btnDistribuir" name="btnDistribuir" class="btn btn-block btn-sm btn-default">DISTRIBUIR</button> -->
                                 </div>
                                 <div class="col-sm-8" >
                                  	<button type="button" id="md_btnanular" name="md_btnanular" onclick="anularComprobantes()" class="btn btn-block btn-sm btn-default">
                                  		<i class="fa fa-times" aria-hidden="true"></i> ANULAR
                              		</button>
                                 </div>
                                
                			 </div>        			 
            			</div>
    				</div>
          		</div>
          		        		
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL VER COMPROBANTES -->
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <!--  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <script>
	    webshims.setOptions('forms-ext', {types: 'date'});
		webshims.polyfill('forms forms-ext');
	</script>
    --> 
    
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="view/Contable/FuncionesJS/ProcesarAnularComprobantes.js?0.13"></script>    

	
	
  </body>
</html>   

