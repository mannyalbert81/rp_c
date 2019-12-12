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
        <li class="active">Bancos</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Registrar Bancos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">
  			<div id="divLoaderPage" ></div>                     	
  		
  			<div class="row">
  			
  				<div class="col-xs-12 col-md-12 col-lg-12">
  				
  					<div class="pull-right">
  						
  						<ul class="nav nav-pills">
                          <li>
                            <button class="btn"><i></i> Add Proveedor</button>
                          </li>
                          <li>
                          <button class="btn"><i></i> Hola </button>
                          </li>
                          <li>
                          <button class="btn">Right</button>
                          </li>
                        </ul>                          						
  						
  					</div>
  					
  				</div>
  			</div>
  			
  			<br>
  			
  			<div class="row">
  				
  				  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">
      					<label for="nombre_bancos" class="control-label">Proveedor:</label>
      					 <div class="input-group">  					 	
                          <input type="text" class="form-control">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-toggle="modal" data-target="#mod_proveedores">
                            <i class="fa  fa-binoculars" aria-hidden="true"></i></button>
                          </span>
                        </div>
  					</div>    									 
  				</div>
  				  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="nombre_bancos" class="control-label">Comprobante:</label>
                      <select class="form-control" id="tipo_documento">
                      	<option value="0">Factura</option>
                      </select>                          
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="nombre_bancos" class="control-label">Ref. Documento:</label>
                      <input type="text" id="referencia_documento" class="form-control">                       
                    </div>	
  				</div>
  				
  				<div class="col-xs-12 col-md-3 col-md-3">
  					<div class="form-group">  
                      <label for="nombre_bancos" class="control-label">Fecha:</label>
                      <input type="text" id="nombre_proveedor" class="form-control" value="<?php echo date('d/m/Y')?>">
                    </div>	
  				</div>
  				
  			</div>
  			
  			<div class="row">
  				 <div class="col-xs-12 col-md-12 col-lg-12 " >
  				 	<div class="pull-right">
  				 		<button  id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
	                    <a href="<?php echo $helper->url("Compras","Index"); ?>" class="btn btn-danger">CANCELAR</a>
  				 	</div>
  				 </div>
  			</div>
  			<div class="clearfix"></div>
  			<br>
  			
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Detalles de Factura</h3>
					<div class="pull-right">
						<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
							<i class="fa fa-filter" aria-hidden="true"></i>
						</span>
					</div>
				</div>
				<div class="panel-body">
					
				</div>
				<table class="table table-hover" id="tbl_compras">
					<thead>
						<tr>
          					<th>#</th>
          					<th>Codigo</th>
          					<th>Descripcion</th>
          					<th>Cantidad</th>
          					<th>Precio</th>
          					<th>Precio Total</th>
						</tr>
					</thead>
					<tbody>
						<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
					</tbody>
					<tfoot>
						<tr>
						<td colspan="2">							
							<span><i class="fa fa-pencil" aria-hidden="true"></i></span>
							<span><i class="fa fa-trash" aria-hidden="true"></i></span>
						</td>
						<td colspan="4"></td>
						</tr>
					</tfoot>
				</table>
			</div>
  			
  			<div class="row">
			    <div class="col-xs-12 col-md-4 col-lg-4 " style="text-align: center; ">
    	   		    <div class="form-group">
	                  
                    </div>
                    
    		    </div>        		    
    		</div>
  			
  
          </div>
    	</div>
    </section>
              
     <section class="content">
      	<div class="box box-primary">
      		<div class="box-header with-border">
      			<h3 class="box-title">Listado de Bancos</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaBancos(1)" placeholder="Buscar.."/>
    			</div>            	
            	<div id="bancos_registrados" ></div>
            </div> 	
      	</div>
      </section> 
    
  </div>
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
 
 <!-- COMIENZA MODALS -->
 
 <!-- BEGIN MODAL PARTICIPES SIN APORTES -->
  <div class="modal fade" id="mod_proveedores" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog   modal-lg " role="document" >
        <div class="modal-content">
          <div class="modal-header bg-red color-palette">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center">Lista de Proveedores</h4>
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
                    			<th style="text-align: left;  font-size: 12px;">RUC/CI</th>
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
 
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/tesoreria/js/compras.js?0.8"></script> 
       
       

 	
	
	
  </body>
</html>   

