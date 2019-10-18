<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
     
    <link href="//cdn.datatables.net/fixedheader/2.1.0/css/dataTables.fixedHeader.min.css"/>
    
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
        
      /*.modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }*/
 	  
 	</style>
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
        <li class="active">Archivo de Recaudacion</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Generacion Archivo por Entidad Patronal</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_recaudacion" data-locked="false" action="<?php echo $helper->url("Recaudacion","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
			
			<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="anio_recaudacion" class="col-sm-4 control-label" >Periodo de Fechas:</label>
                				<div class="col-sm-4">
                                  	<input type="number" max="<?php echo date('Y') ?>" class="form-control" id="anio_recaudacion" name="anio_recaudacion"  autocomplete="off" value="<?php echo date('Y') ?>" autofocus>
                                 </div>
                                 <div class="col-sm-4">
                                  	<select id="mes_recaudacion" name="mes_recaudacion" class="form-control" onchange="validaCambioMes()">
                                  	<option value="0">--seleccione--</option>
                                  	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                                  	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>                                  	                                  	
                                  	<?php }?>
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
                				<label for="id_entidad_patronal" class="col-sm-4 control-label" >Entidad Patronal:</label>
                				<div class="col-sm-8">
                                  	<select id="id_entidad_patronal" name="id_entidad_patronal" class="form-control" disabled>
                                  	<option value="0">--Seleccione--</option>
                                  	<?php if(isset($rsEntidadPatronal)){
                                  	    foreach ( $rsEntidadPatronal as $res ){
                                  	        echo '<option value="'.$res->id_entidad_patronal.'">'.$res->nombre_entidad_patronal.'</option>';
                                  	    }
                                  	}?>
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
                				<label for="formato_recaudacion" class="col-sm-4 control-label" >Formato:</label>
                				<div class="col-sm-8">
                                  	<select id="formato_recaudacion" name="formato_recaudacion" class="form-control">
                                  	<option value="0" >--Seleccione--</option>
                                  	<option value="1" >DESCUENTOS APORTES</option>
                                  	<option value="2" >DESCUENTOS CREDITOS</option>
									<option value="3" >DESCUENTOS CREDITOS Y APORTES</option>
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
                				<label for="tipo_periodo_recaudacion" class="col-sm-4 control-label" >Tipo Periodo:</label>
                				<div class="col-sm-8">
                                  	<select id="tipo_periodo_recaudacion" name="tipo_periodo_recaudacion" class="form-control" readonly>
                                  	 <option value="1" >MENSUAL</option>
                                  	</select>
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
                                 <div class="col-sm-4">
                                  	<button type="button" id="btnGenerar" name="btnGenerar" class="btn btn-block btn-sm btn-default">GENERAR</button>
                                 </div>
                                 <!-- <div class="col-sm-4">
                                  	<button type="button" id="btnDescargar" name="btnDescargar" class="btn btn-block btn-sm btn-default">DESCARGAR ARCHIVO</button>
                                 </div>
                                  -->
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
              <h3 class="box-title">Listado Recaudacion Entidades</h3>              
            </div>
            
            <div class="box-body">

           		<div class="row">
           			<div class="col-lg-12 col-md-12 col-xs-12">
               		    <div class="col-lg-3 col-md-3 col-xs-12">
                   		    <div class="" >
            					<!-- aqui poner elemento de cantidad de registros -->
                    		</div>
                    	</div>
               			<div class="col-lg-9 col-md-9 col-xs-12">
                        	
                        	<div class="pull-right" >
                        		<div class="form-group-sm">
                					<select id="ddl_mes_buscar" class="form-control">
                						<option value="0">--Seleccione--</option>
                						<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                                      	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>                                  	                                  	
                                      	<?php }?>            						
                					</select>
            					</div>
                    		</div> 
                    	    <div class="pull-right" >
                    	    	<div class="form-group-sm">
                					<input type="number" value="" max="<?php echo date('Y');?> " class="form-control" id="txt_anio_buscar" placeholder="Ingrese año"/>
                				</div>
                    		</div> 
                    		<div class="pull-right" >
                    			<div class="form-group-sm">
                        			<select id="ddl_id_entidad_patronal" class="form-group-sm form-control ">
                            			<option value="0">--Seleccione--</option>
                                      	<?php if(isset($rsEntidadPatronal)){
                                      	    foreach ( $rsEntidadPatronal as $res ){
                                      	        echo '<option value="'.$res->id_entidad_patronal.'">'.$res->nombre_entidad_patronal.'</option>';
                                      	    }
                                      	}?>
                                    </select>
                                </div>
                    		</div> 
                    		<div class="pull-right" >
                    			<div class="form-group-sm">
                    				<button id="btn_reload" onclick="consultaArchivosRecaudacion(1)" class=" form-control btn btn-default" ><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    			</div>
                    		</div>
                    		
                    		               	
                    	</div>
                	</div>
                	
                	
                </div>
                           
    			<div class="clearfix" ></div> 	
            	<div id="div_tabla_archivo_txt" ></div>
         
            </div>
            </div>
            </section>
    
  </div>
  
 
	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- Para modales -->
  <div class="modal fade" id="mod_datos_archivo" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DATOS ARCHIVO</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body">
          		<div class=" pull-left " >
  					<div class="form-group-sm">
                    	<span class="form-control" id="cantidad_busqueda"><strong>Total Registros: </strong> <span id="mod_cantidad_registros"></span> </span>
                	</div>   
            	</div>
          		<div class="pull-right" >
          			<div class="form-group-sm">
    					<input type="text" value="" class="form-control" onkeyup="buscarDatos()" id="mod_txtBuscarDatos" name="mod_txtBuscarDatos"  placeholder="Buscar.."/>
    				</div>
    			</div>           
    			<div class="clearfix" ></div> 	
            	<div id="mod_div_datos_recaudacion" class="table-responsive"></div>
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>

<!-- BEGIN MODAL PARTICIPES SIN APORTES -->
  <div class="modal fade" id="mod_participes_sin_aportes" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-red color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">PARTICIPES QUE NO TIENEN DEFINIDO EL APORTE</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
            	<div id="mod_div_participes_sin_aportacion" class="table-responsive" style="min-height: 150px; max-height: 450px">
            		<table id="tbl_participes_sin_aportacion" class="table  table-fixed table-sm table-responsive-sm" > <!--   -->
                    	<thead >
                    		<tr>
                    			<th ><p>Registros <span id="catidad_sin_aportes" class="badge bg-red"></span></p> </th>
                    			<th colspan="3"></th>
                    		</tr>
                    	    <tr class="table-secondary" >
                    			<th style="text-align: left;  font-size: 12px;">#</th>
                    			<th style="text-align: left;  font-size: 12px;">No. Identificacion</th>
                    			<th style="text-align: left;  font-size: 12px;">Nombres</th>
                    			<th style="text-align: left;  font-size: 12px;">Apellidos</th>
                    		</tr>
                    	</thead>        
                    	<tbody>
                    	    
                    	</tbody>
                    	<tfoot>
                    	    <tr>
                    			<th colspan="3" ></th>
                    			<th style="text-align: right"></th>
                    	    </tr>
                    	</tfoot>
                    </table>            	
            	</div>
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- BEGIN MODAL PARTICIPES SIN APORTES -->
 
 <div class="modal fade" id="mod_recaudacion" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:40%">
        <div class="modal-content">
          <div class="modal-header bg-orange disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cambio Valores Aporte</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_edit_recaudacion" name="frm_edit_recaudacion">
          	
          	<div class="row">
          		<div class="col-lg-12 col-md-12 col-xs-12">
          			<h5>Datos Participe</h5>
          			<input type="hidden" class="form-control " id="mod_id_archivo_detalle" name="mod_id_archivo_detalle" >
          		</div>
              	<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Cedula:</p>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_cedula_participes" name="mod_cedula_participes" readonly>
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Nombres:</p>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_nombres_participes" name="mod_nombres_participes" readonly>                              	
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">            			 	
            				<p class="text-muted col-sm-4 control-label">Apellidos:</p>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_apellidos_participes" name="mod_apellidos_participes" readonly>
                             </div>
            			 </div>        			 
        			</div>
				</div>
              	<div class="col-lg-12 col-md-12 col-xs-12">        		
    			<div class="form-group "> 
        			 <div class="form-group-sm">
        				<p class="text-muted col-sm-4 control-label">Valor Sistema:</p>
        				<div class="col-sm-8">
                          	<input type="text" class="form-control " id="mod_valor_sistema" name="mod_valor_sistema" readonly>
                         </div>
        			 </div>        			 
    			</div>
    			</div>
    			<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<p class="text-muted col-sm-4 control-label">Nuevo Valor:</p>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_valor_edit" name="mod_valor_edit" >
                             </div>
            			 </div>        			 
        			</div>
    			</div>
          	</div>
          	
          	<div id="msg_frm_recaudacion" ></div> 
          	
          	<div class="clearfix"></div>         	
			  
          	</form>
          	<!-- termina el formulario modal de impuestos -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" id="btnEditRecaudacion" class="btn btn-default" >Aceptar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
    
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/bootstrap/bower_components/select2/dist/js/select2.full.min.js"></script>
   <script src="view/Recaudaciones/js/archivoEntidadPatronal.js?0.55"></script> 
       
       

 	
	
	
  </body>
</html>   

