<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <?php include("view/modulos/links_css.php"); ?>    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <!-- <link href="//cdn.datatables.net/fixedheader/2.1.0/css/dataTables.fixedHeader.min.css"/>    
    <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.20/integration/font-awesome/dataTables.fontAwesome.css"/> -->    
        
 	<style type="text/css">
 	  .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('view/images/ajax-loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
        } 
       .letrasize10{
        font-size: 10px;
       }
       .letrasize11{
        font-size: 11px;
       }
       .letrasize12{
        font-size: 12px;
       }
       .tooltip[aria-hidden=false] {
        opacity: 1;
       }	  
 	</style>   
  			        
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    <span id="fechasistema"><?php echo date('Y-m-d');?></span>

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
      <h1>	<small><?php echo $fecha; ?></small> </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Arhivo Pago</li>
      </ol>
     </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Generacion Archivo Pago</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">
  		
  		<div id="divLoaderPage" ></div> 

			<form id="frm_genera_archivo_pago" action="<?php echo $helper->url("ArchivoPago","index2"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             	
		    	 <div class="row">
		    	 
		    	 	<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="id_tipo_archivo_pago" class=" control-label" >Tipo Archivo:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="id_tipo_archivo_pago">
                    				<option value="0">--Seleccione--</option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div> 
		    	 
		    	    <div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="fecha_proceso" class=" control-label" >Fecha Proceso (YYYY/MM/DD):</label> 
                    		<div class="form-group-sm">                    				
                				 <input type="text" class="form-control text-right" id="fecha_proceso" name="fecha_proceso" max="<?php echo date('Y/m/d'); ?>" value="<?php echo date('Y/m/d');?>" >
                    		</div>    			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="id_bancos_local" class=" control-label" >Banco Local:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="id_bancos_local">
                    				<option value="0">--Seleccione--</option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div> 
            		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="ddl_usuario_departamento" class=" control-label" >Usuario:</label> 
                    		<div class="form-group-sm">           
                    			<select class="form-control" id="ddl_usuario_departamento">
                    				<option value="0">--Seleccione--</option>
                    			</select> 
                    		</div>        			 
            			</div>
            		</div> 
            		
<!--             		<div class="col-xs-12 col-md-3 col-lg-3"> -->
<!--             			<div class="form-group "> -->
<!--             				<label for="tipo_pago_archivo" class=" control-label" >Tipo Pago:</label>  -->
<!--                     		<div class="form-group-sm">            -->
<!--                     			<select class="form-control" id="tipo_pago_archivo"> -->
<!--                     				<option value="0">--Seleccione--</option> -->
<!--                     				<option value="d">Directa</option> -->
<!--                     				<option value="i">Interbancaria</option> -->
<!--                     			</select>  -->
<!--                     		</div>        			  -->
<!--             			</div> -->
<!--             		</div>  -->
        		
        		</div>
        		
        		<div class="row">    
        			
        			<div class="col-xs-12 col-md-9 col-lg-9">
        			</div>
        			        		
            		<div class="col-xs-12 col-md-3 col-lg-3">
            			<div class="form-group ">
            				<label for="buscar_archivo_pago" class=" control-label" ></label> 
                    		<div class="form-group-sm">           
                    		  <button type="button" id="buscar_archivo_pago" name="buscar_archivo_pago" class="btn btn-success">
                              	<i class="glyphicon glyphicon-plus"></i> Buscar
                              </button>
                              
                              <button type="button" id="generar_archivo_pago" class="btn btn-success">
                              	<i aria-hidden="true" class="fa fa-cloud-download"></i> Generar
                              </button>
                    		</div>        			 
            			</div>
            		</div> 
            		
            	</div>
            	
            	<div class="row hidden" id="div_resultados_archivo_pago">
            		<div class="col-xs-12 col-md-12 col-lg-12">
                		<div class="panel panel-default">
                          <div class="panel-heading">Lista de Datos</div>
                            <div class="pull-left">            			
                    			<p class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Registros <span id="mod_total_archivo" class="badge badge-info"></span></p>
                    		</div>
                    		<div class="clearfix"></div>
                          <table id="tblListadoArchivoPago" class="table">                            
                          </table>
                          <div id="mod_paginacion_archivo"></div>
                    	  <div class="clearfix"></div>  
                        </div>
            		</div>
            		
            	</div>
            	
            	<div class="row">
            		<div class="col-xs-12 col-md-12 col-lg-12">
                		<div class="panel panel-default">
                        	<div class="panel-heading">Datos Encontrados</div>
                            
                    		<div class="panel-body">
                    			<div id="div_datos_archivos" class="letrasize11">
            						<table id="tbl_archivo_pago" class="table table-bordered display compact">
            							<thead>
            								<tr>
            	    							<th><input type="checkbox" id="chk_pagos_all"></th>
                                        	    <th>#</th>
                                        	    <th>Tipo</th>
                                        	    <th>Fecha</th>
                                        	    <th>Banco Beneficiario</th>
                                        	    <th>Tipo Pago</th>
                                        	    <th>Identificacion</th>
                                        	    <th>Beneficiario</th>
                                        	    <th>Cod. Banco</th>
                                        	    <th>Valor</th>
                                    	    </tr>
            	    					</thead>
            	    					<tbody>
            	    						
            	    					</tbody>
            	    					<tfoot>
            	    					</tfoot>
            						</table>
        						</div>  
                    		</div> <!-- AQUI TERMINA BODY DE PANEL -->                 		
                    		                        
                        </div>
            		</div>
            	</div>
            	 
           </form>
                      
          </div>
    	</div>
    </section>
    
  </div>
  
  <!-- Para modales -->
  <div class="modal fade" id="mod_distribucion_pago" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">Información</h4>
          </div>
          <div class="modal-body" >
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_distribucion_transferencia" name="frm_distribucion_transferencia">
          	
          	<div class="row">
          		
          		
          		<div class="col-xs-12 col-lg-3 col-md-3">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Identificación:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_identificacion_proveedor" name="mod_identificacion_proveedor" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Nombre:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_nombre_proveedor" name="mod_nombre_proveedor" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Monto:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_total_cuentas_pagar" name="mod_total_cuentas_pagar" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	<div class="col-xs-12 col-lg-3 col-md-3 ">
	    	 		<div class="form-group ">                 			 
        				<label for="nombre_lote" class="control-label" >Transferir a:</label>
        				<div class="form-group-sm">  
        					<select class="form-control" id="mod_banco_transferir">
        					
        					</select> 
        				</div>
            						 
        			</div>		    	 	
	    	 	</div> 
	    	 	
	    	 	<div class="col-xs-12 col-lg-6 col-md-6 ">
	    	 		<div class="form-group ">                 			 
        				<label for="mod_descripcion_transferencia" class="control-label" >Descripcion:</label>
        				<div class="form-group-sm">                				
                          <input type="text" style="height:30px"  class=" form-control" id="mod_descripcion_transferencia" value="">
        				</div>
            						 
        			</div>		    	 	
	    	 	</div>
	    	 	
	    	 	
	    	 	    
          	</div>
          	          	
		  	<div class="box-body">        
				<div id="lista_distribucion_transferencia" ></div>
				
				
        	</div>
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" id="btn_distribucion_aceptar" class="btn bg-aqua waves-light" data-dismiss="modal">Aceptar</button>            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    

 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
    <script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script type="text/javascript" src="view/tesoreria/js/ListaArchivoPago.js?0.20"></script>

  </body>
</html>   

