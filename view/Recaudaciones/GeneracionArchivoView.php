<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">     
    <link href="//cdn.datatables.net/fixedheader/2.1.0/css/dataTables.fixedHeader.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.20/integration/font-awesome/dataTables.fontAwesome.css"/>
    
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
        
       .letrasize11{
        font-size: 11px;
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
        <li class="active">Archivo de Recaudacion</li>
      </ol>
    </section>   
    
    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Generacion Archivo Recaudacion</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>            
          </div>
        </div>
        
                  
  		<div class="box-body">

			<form id="frm_recaudacion" data-locked="false" action="<?php echo $helper->url("RecaudacionGeneracionArchivo","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
			
			<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="anio_recaudacion" class="col-sm-4 control-label" >Periodo de Fechas:</label>
                				<div class="col-sm-4">
                                  	<input type="number" max="<?php echo date('Y') ?>" class="form-control" id="anio_recaudacion" name="anio_recaudacion"  autocomplete="off" value="<?php echo date('Y') ?>" autofocus>
                                 </div>
                                 <div class="col-sm-4">
                                  	<select id="mes_recaudacion" name="mes_recaudacion" class="form-control">
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
                                  	<select id="id_entidad_patronal" name="id_entidad_patronal" class="form-control"  disabled>
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
                				<label for="id_descuentos_formatos" class="col-sm-4 control-label" >Formato Descuentos:</label>
                				<div class="col-sm-8">
                                  	<select id="id_descuentos_formatos" name="id_descuentos_formatos" class="form-control"  disabled>
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
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i>
                </button>            
              </div>          
            </div>
            
            <div class="box-body">

           		<div class="row">
           			<div class="col-lg-12 col-md-12 col-xs-12">
               		    <div class="col-lg-1 col-md-1 col-xs-12">
                   		    <div class="" >
            					<!-- aqui poner elemento de cantidad de registros -->
                    		</div>
                    	</div>               			
                	</div>
                </div>
                
                <div class="clearfix" ></div> 	
            	<div id="div_listado_recaudaciones" >
            		<table id="tbl_listado_recaudaciones" class="table tablesorter table-striped table-bordered dt-responsive nowrap">
            			<thead>
            				<tr class="danger">
                				<th >#</th>
                				<th>Fecha</th>
                				<th>Entidad Patronal</th>
                				<th>Recaudacion</th>
                				<th>Cantidad</th>
                				<th>A&ntilde;o</th>
                				<th>Mes</th>
                				<th>Usuario</th>
                				<th>Modificado</th>
                				<th>Opciones</th>
            				</tr>
            			</thead>
            			<tfoot>
            				<tr>
            				</tr>
            			</tfoot>
            		</table>
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
 
 <!-- BEGIN PARA DATOS DE ARCHIVOS INSERTADOS -->
  <div class="modal fade" id="mod_datos_archivo_insertados" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-aqua disabled color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DATOS ARCHIVO INSERTADOS</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body">
          		<div class=" pull-left " >
  					<div class="form-group-sm">
                    	<span class="form-control" id="cantidad_busqueda"><strong>Total Registros: </strong> <span id="mod_cantidad_registros_insertados"></span> </span>
                	</div>   
            	</div>
          		<div class="pull-right" >
          			<div class="form-group-sm">
    					<input type="text" value="" class="form-control" onkeyup="buscarDatosInsertados()" id="mod_txtBuscarDatos_insertados" name="mod_txtBuscarDatos_insertados"  placeholder="Buscar.."/>
    				</div>
    			</div>           
    			<div class="clearfix" ></div> 	
            	<div id="mod_div_datos_recaudacion_insertados" class="table-responsive"></div>
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
 <!-- END PARA DATOS DE ARCHIVOS INSERTADOS -->
 
 <!-- BEGIN PARA DATOS DE ARCHIVOS LISTADOS -->
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
    					<input type="text" value="" class="form-control" onkeyup="CargarDatosDescuentos(1)" id="mod_txtBuscarDatos"  placeholder="Buscar.."/>
    				</div>
    			</div>           
    			<div class="clearfix" ></div> 	
            	<div id="mod_div_datos_recaudacion" class="table-responsive" >
            		<table id='tbl_archivo_recaudaciones_insertados' class='table table-striped table-bordered'>
            			<thead><tr><th>colum1</th></tr></thead>
            			<tbody><tr><td>detalle1</td></tr></tbody>
            		</table>
            		<div id="mod_paginacion_datos_descuentos"></div>
            	</div>
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END PARA DATOS DE ARCHIVOS LISTADOS -->

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
<!-- END MODAL PARTICIPES SIN APORTES -->
 
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
          			<input type="hidden" id="mod_id_descuentos_detalle" >
          			<input type="hidden" id="mod_tipo_descuentos" >
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

<!-- BEGIN MODAL DESCUENTOS CREDITOS -->
  <div class="modal fade" id="modal_preview_creditos" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-primary color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DATOS DESCUENTOS CREDITOS</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		<div class="row">
          			<div class="col-sm-12">
          				<div class="pull-right">
          					<button class="btn btn-success" onclick="aceptar_descuentos_creditos()">	<i aria-hidden="true" class="fa fa-pencil"></i> GUARDAR DESCUENTO</button>
          				</div>
          			</div>
          		</div>
          		<br>
              	<div id="mod_div_preview_descuentos_creditos" class="letrasize11">
                		<table id="tbl_preview_descuentos_creditos" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="warning" >
                        	    	<th >-</th>
                        	    	<th >#</th>
                        			<th >Entidad</th>
                        			<th >Tipo</th>
                        			<th >Cedula</th>
                        			<th >Nombre</th>
                        			<th >Nombre Credito</th>
                        			<th >Mes Descuento</th>
                        			<th >Sueldo</th>
                        			<th >Cuota</th>
                        			<th >Mora</th>
                        			<th >Total</th>
                        			<th >Total Envio</th>                        			
                        		</tr>
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="9">TOTALES ..</td> 
                        			<td >PARCIAL</td>
                        			<td >..</td> 
                        			<td >TOTAL</td> 
                        			<td >..</td>
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
<!-- END MODAL DESCUENTOS CREDITOS SIN APORTES -->

<!-- BEGIN MODAL PRUEBA DATATABLE -->
  <div class="modal fade" id="mod_prueba_datos" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-red color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">DATOS PRUEBAS CON DATATABLE</h4>
          </div>
          <div class="modal-body" >
          	<div class="box-body no-padding">
          		
            	
          	</div>
          	
          
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL MODAL PRUEBA DATATABLE -->

<!-- BEGIN MODAL CAMBIAR DESCUENTOS CREDITOS -->
  <div class="modal fade" id="mod_cambia_desc_creditos" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-sm " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-primary color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">EDITAR DESCUENTOS CREDITOS</h4>
          </div>
          <div class="modal-body" >
          	<!-- cuerpo modal -->
          	<div class="box box-success">
            <div class="box-header with-border">              
            </div>
            <div class="box-body">
                <!-- comienza el formulario -->
                <form>
                	<input type="hidden" id="mod_id_descuentos_creditos" value="0">
                	<div class="form-group">
                	
                        <label for="mod_cedula_descuentos_creditos" class="col-form-label">Cedula:</label>
                        <input type="text" class="form-control" id="mod_cedula_descuentos_creditos">
                        <label for="mod_nombres_descuentos_creditos" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="mod_nombres_descuentos_creditos">
                        <label for="mod_valor_descuentos_creditos" class="col-form-label">Valor Sietema:</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control text-right" id="mod_valor_descuentos_creditos">
                          </div>
                        <label for="mod_nuevo_valor_descuentos_creditos" class="col-form-label">Nuevo Valor:</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control text-right" id="mod_nuevo_valor_descuentos_creditos">
                          </div>
                      </div>
                                    
                </form>
              
            </div>
           
          </div>  <!-- /.box-body -->
          
          </div>  <!-- /. termina cuerpo modal -->
          
          <!-- /. footer of modal -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" onclick="editar_valores_descuentos_creditos()" class="btn btn-primary">Aceptar</button>
          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
</div>
<!-- END MODAL MODAL CAMBIAR DESCUENTOS CREDITOS -->
    
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <script src="view/bootstrap/bower_components/select2/dist/js/select2.full.min.js"></script>
   <script src="view/Recaudaciones/js/GeneracionArchivo.js?0.29"></script> 
       
	
  </body>
</html>   

