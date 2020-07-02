<!DOCTYPE HTML>
<html lang="es">
      <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="stylesheet" href="view/bootstrap/otros/css/tablaFixed.css?1"/> 
    <link rel="stylesheet" href="view/bootstrap/plugins/bootstrap_fileinput_v5.0.8-4/css/fileinput.min.css">
  			        
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
        <li class="active">Bancos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Recepcion de Archivos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
  		<div class="box-body">

			<form id="frm_carga_recaudaciones" action="<?php echo $helper->url("RecepcionArchivosRecaudaciones","InsertaRecepcionArchivosRecaudaciones"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
							    
		  	<input type="hidden" id="id_carga_recaudaciones" value="0">
            		  
            <div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_entidad_patronal" class="col-sm-4 control-label" >Entidad:</label>
                				<div class="col-sm-8">
                                  	<select id="id_entidad_patronal" name="id_entidad_patronal" class="form-control" >
                              	<option value="0">--Seleccione--</option>
                              	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
        		
        		<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_descuentos_formatos" class	="col-sm-4 control-label" >Desc. Formato:</label>
                				<div class="col-sm-8">
                                  	<select id="id_descuentos_formatos" name="id_descuentos_formatos" class="form-control">
                                  		<option value="0">--Seleccione--</option>
                                  	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
        		
        		<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="anio_carga_recaudaciones" class="col-sm-4 control-label" >Fechas:</label>
                				<div class="col-sm-4">
                                  	<input type="number" max="<?php echo date('Y') ?>" class="form-control" id="anio_carga_recaudaciones" name="anio_carga_recaudaciones"  autocomplete="off" value="<?php echo date('Y') ?>" autofocus>
                                 </div>
                                 <div class="col-sm-4">
                                  	<select id="mes_carga_recaudaciones" name="mes_carga_recaudaciones" class="form-control">
                                  	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                                  	<?php if( $i == date('n')){ ?>
                                  	<option value="<?php echo $i;?>" selected ><?php echo $meses[$i-1]; ?></option>
                                  	<?php }else{?>
                                  	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>
                                  	<?php }}?>
                                  	</select>
                                 </div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
            		              		  
           
        		
        		<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="nombre_carga_recaudaciones" class="col-sm-4 control-label" >Archivo:</label>
                				<div class="col-sm-8">                					
                             	   <input accept="text/plain" type="file" name="nombre_carga_recaudaciones" id="nombre_carga_recaudaciones" value=""  class="form-control"/>
                             	   <div id="errorImagen"></div>      
                             	</div>
                			 </div>        			 
            			</div>
    				</div>
        		</div>
            		 
      
    			    
      <hr style="border-color:#FFFFFF;">
        		
        		<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">                				
                				<div class="col-sm-4">
                                  	<!-- <button type="button" id="btnDistribuir" name="btnDistribuir" class="btn btn-block btn-sm btn-default">DISTRIBUIR</button> -->
                                 </div>
                                 <div class="col-sm-8" >
                                  	<button type="button" id="btnSubirArchivo" name="btnSubirArchivo" onclick="uploadFileEntidad()" class="btn btn-block btn-sm btn-default">
                                  		<i class="fa fa-cloud-upload" aria-hidden="true"></i> CARGAR ARCHIVO
                              		</button>
                                 </div>
                                
                			 </div>        			 
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
              <h3 class="box-title">Registros</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#pendientes" data-toggle="tab">Pendientes</a></li>
              <li><a href="#procesados" data-toggle="tab">Procesados</a></li> 
              <li><a href="#negados" data-toggle="tab">Negados</a></li> 
            
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
 			 <div class="tab-pane active" id="pendientes">
                
              	
              		<div id="pnl_div_descuentos_pendientes" class="letrasize11">
                		<table id="tbl_descuentos_pendientes" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="warning" >
                        	    	<th >#</th>
                        			<th >Entidad</th>
                        			<th >Usuario</th>
                        			<th >A&ntilde;o</th>
                        			<th >Mes</th>
                        			<th >Tipo Descuentos</th>
                        			<th >Nombre Archivo</th>
                        			<th >Fec. Descuentos</th>
                        			<th >Fec. Contable</th>
                        			<th >Opciones</th>                        			
                        		</tr>                        		
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="10"></td> 
                    			</tr>
                			</tfoot>
                        </table>            	
                	</div>
          		
            	
          		
		       
              </div>
              
              <div class="tab-pane" id="procesados">
              
              	<div class="box-body no-padding">
          		
              		<div id="pnl_div_descuentos_procesados" class="letrasize11">
                		<table id="tbl_descuentos_procesados" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="warning" >
                        	    	<th >#</th>
                        			<th >Entidad</th>
                        			<th >Usuario</th>
                        			<th >A&ntilde;o</th>
                        			<th >Mes</th>
                        			<th >Tipo Descuentos</th>
                        			<th >Nombre Archivo</th>
                        			<th >Fec. Descuentos</th>
                        			<th >Fec. Contable</th>
                        			<th >Opciones</th>                       			
                        		</tr>
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="10"></td>
                    			</tr>
                			</tfoot>
                        </table>            	
                	</div>          		
            	
          		</div>
                
        			
              </div>
                            
              <div class="tab-pane" id="negados">
              
              	<div class="box-body no-padding">
          		
              		<div id="pnl_div_descuentos_errores" class="letrasize11">
                		<table id="tbl_descuentos_errores" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="warning" >
                        	    	<th >#</th>
                        			<th >Entidad</th>
                        			<th >Usuario</th>
                        			<th >A&ntilde;o</th>
                        			<th >Mes</th>
                        			<th >Tipo Descuentos</th>
                        			<th >Nombre Archivo</th>
                        			<th >Fec. Descuentos</th>
                        			<th >Fec. Contable</th>
                        			<th >Opciones</th>                       			
                        		</tr>
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="10"></td>
                    			</tr>
                			</tfoot>
                        </table>            	
                	</div>
          		            	
          		</div>                
             			
              </div>
              
             </div>
            </div>
           </div>
         
            </div>
            </div>
            </section>
                

      
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- BEGIN MODAL ERRORES CARGA  -->
  <div class="modal fade" id="mod_archivo_errores" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-red color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center"></h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		<table id="tbl_archivo_error" class="table table-striped table-bordered table-sm " cellspacing="0"  width="100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Linea</th>
                      <th>Error</th>
                      <th>Cantidad</th>
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
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL ERRORES CARGA -->
    
    <?php include("view/modulos/links_js.php"); ?>
	
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <!-- FILE UPLOAD -->
   <script src="view/bootstrap/plugins/bootstrap_fileinput_v5.0.8-4/js/fileinput.min.js"></script>
   <script src="view/Recaudaciones/js/RecepcionArchivosRecaudaciones.js?0.5"></script> 

  </body>
</html>   

