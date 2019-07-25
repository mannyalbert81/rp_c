<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    
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

			<form id="frm_recaudacion" action="<?php echo $helper->url("Recaudacion","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             		
             	<div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_entidad_patronal" class="col-sm-4 control-label" >Entidad Patronal:</label>
                				<div class="col-sm-8">
                                  	<select id="id_entidad_patronal" name="id_entidad_patronal" class="form-control">
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
                				<label for="anio_recaudacion" class="col-sm-4 control-label" >Periodo de Fechas:</label>
                				<div class="col-sm-4">
                                  	<input type="number" max="<?php echo date('Y') ?>" class="form-control" id="anio_recaudacion" name="anio_recaudacion"  autocomplete="off" value="<?php echo date('Y') ?>" autofocus>
                                 </div>
                                 <div class="col-sm-4">
                                  	<select id="mes_recaudacion" name="mes_recaudacion" class="form-control">
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
                				<label for="formato_recaudacion" class="col-sm-4 control-label" >Formato:</label>
                				<div class="col-sm-8">
                                  	<select id="formato_recaudacion" name="formato_recaudacion" class="form-control">
                                  	<option value="1" >ENVIO DESCUENTOS APORTE</option>
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
                                  	<select id="tipo_periodo_recaudacion" name="tipo_periodo_recaudacion" class="form-control">
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
                                  	<button type="button" id="btnSimular" name="btnSimular" class="btn btn-block btn-sm btn-default">SIMULAR</button>
                                 </div>
                                 <div class="col-sm-4">
                                  	<button type="button" id="btnGenerar" name="btnGenerar" class="btn btn-block btn-sm btn-default">GENERAR</button>
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
              <h3 class="box-title">Historial archivos Generados</h3>              
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#his_datos" data-toggle="tab"> Datos Archivos</a></li>
              <li><a href="#his_archivos" data-toggle="tab">Archivos txt generados</a></li>
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="his_datos">
                
					<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="txtBuscar" name="txtBuscar"  placeholder="Buscar.."/>
        			</div>           
        			<div class="clearfix" ></div> 	
                	<div id="div_tabla_archivo" ></div>
                
              </div>
              
              <div class="tab-pane" id="his_archivos">
                
                <div class="row">
                	<div class="col-lg-6 col-md-6 col-xs-12">
                	</div>
                	<div class="col-lg-6 col-md-6 col-xs-12">
                		<div class="pull-right" >
                			<input type="text" value="" class="form-control" id="txtBuscarhistorial" name="txtBuscarhistorial"  placeholder="Buscar.."/>
                		</div> 
                		<div class="pull-right" >
                			<button id="btn_reload" class="btn btn-default" ><i class="fa fa-refresh" aria-hidden="true"></i></button>
                		</div>
                		               	
                	</div>
                </div>
                           
    			<div class="clearfix" ></div> 	
            	<div id="div_tabla_archivo_txt" ></div>
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
 
 <div class="modal fade" id="mod_recaudacion" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" style="width:40%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Cambio Valores Aporte</h4>
          </div>
          <div class="modal-body">
          <!-- empieza el formulario modal productos -->
          	<form class="form " method="post" id="frm_edit_recaudacion" name="frm_edit_recaudacion">
          	
          	<div class="row">
          		<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="mod_metodo_descuento" class="col-sm-4 control-label" >Metodo Descuento:</label>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_metodo_descuento" name="mod_metodo_descuento" readonly>
                              	<input type="hidden" class="form-control " id="mod_id_archivo" name="mod_id_archivo" >
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="mod_valor_sistema" class="col-sm-4 control-label" >Valor Descuento Sistema:</label>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_valor_sistema" name="mod_valor_sistema" readonly>
                             </div>
            			 </div>        			 
        			</div>
				</div>
				<div class="col-lg-12 col-md-12 col-xs-12">        		
        			<div class="form-group "> 
            			 <div class="form-group-sm">
            				<label for="formato_recaudacion" class="col-sm-4 control-label" >Valor Descuento: </label>
            				<div class="col-sm-8">
                              	<input type="text" class="form-control " id="mod_valor_edit" name="mod_valor_edit" >
                             </div>
            			 </div>        			 
        			</div>
				</div>
          		
          	</div>
          	
          	<div id="msg_frm_recaudacion" ></div>          	
			  
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
   <script src="view/Recaudaciones/js/archivoEntidadPatronal.js?0.09"></script> 
       
       

 	
	
	
  </body>
</html>   

