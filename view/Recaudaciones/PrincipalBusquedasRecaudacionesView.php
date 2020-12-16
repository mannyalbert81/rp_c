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
          <h3 class="box-title">Ingreso Bancos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
  		<div class="box-body">

			<form id="frm_principal_busqueda_recaudaciones" action="<?php echo $helper->url("PrincipalBusquedaRecaudaciones","InsertaIngresoBancos"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
							    
		  	<input type="hidden" id="id_ingreso_bancos_cabeza" value="0">
		    		  
            <div class="row">        	
        			<div class="col-lg-6 col-md-6 col-xs-12">        		
            			<div class="form-group "> 
                			 <div class="form-group-sm">
                				<label for="id_entidad_patronal" class="col-sm-4 control-label" >Entidad Patronal:</label>
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
                				<label for="anio_ingreso_bancos_cabeza" class="col-sm-4 control-label" >Año y Mes:</label>
                				<div class="col-sm-4">
                                  	<input type="number" max="<?php echo date('Y') ?>" class="form-control" id="anio_ingreso_bancos_cabeza" name="anio_ingreso_bancos_cabeza"  autocomplete="off" value="<?php echo date('Y') ?>" autofocus>
                                 </div>
                                 <div class="col-sm-4">
                                  	<select id="mes_ingreso_bancos_cabeza" name="mes_ingreso_bancos_cabeza" class="form-control">
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
              <li class="active"><a href="#resultados" data-toggle="tab">Resultados</a></li>
           
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
 			 <div class="tab-pane active" id=resultados>
                
              	
              		<div id="pnl_div_ingreso_bancos" class="letrasize11">
                		<table id="tbl_ingreso_bancos" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="danger" >
                        	    	<th >#</th>
                        			<th >Mes</th>
                        			<th >Valor</th>
                        			<th >Diario</th>  
                        			<th >Diario</th>                   			
                        		</tr>                        		
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="5"></td> 
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
            </section>
                

      
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 

    
    <?php include("view/modulos/links_js.php"); ?>
	
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
   <!-- FILE UPLOAD -->
   <script src="view/bootstrap/plugins/bootstrap_fileinput_v5.0.8-4/js/fileinput.min.js"></script>
   <script src="view/Recaudaciones/js/PrincipalBusquedaRecaudaciones.js?0.04"></script> 

  </body>
</html>   

