<!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
        
    <?php include("view/modulos/links_css.php"); ?>    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Inventario</a></li>
        <li class="active">Retenciones</li>
      </ol>
    </section>
    
    
    <!-- para efecto de pantalla cargando -->
    <div id="divLoaderPage" ></div>
    
    <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Reporte Cuentas Pagar Aplicadas - Pagos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">            
            	<div class="nav-tabs-custom hidden">
            		<ul class="nav nav-tabs">              
            		</ul>
            
            	<div class="col-md-12 col-lg-12 col-xs-5 hidden">
            		<div class="tab-content">
            		<br>
                  		<div class="tab-pane active" id="retencion">
                    
    					<div class="pull-right" style="margin-right:15px;">
    						<input type="text" value="" class="form-control" id="search_cuentas_pagar_aplicadas" name="search_cuentas_pagar_aplicadas" placeholder="search.."/>
    					</div>
    						
    					<div id="cuentas_pagar_registrados"></div>	
                    
                  		</div>
             
            		</div>
            	</div>
          		</div>
         		
         		<div class="row">
         			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                		<div id="div_listado_cuentas_pagar_aplicadas" class="letrasize11">
<!--                 		display compact -->
<!--  table tablesorter table-striped table-bordered nowrap -->
                    		<table id="tbl_listado_cuentas_pagar_aplicadas" class="table table-bordered display compact">
                    			<thead>
                    				<tr class="danger">
                        				<th>#</th>
                        				<th>Fecha</th>
                        				<th>Usuario</th>
                        				<th>Cedula Beneficiario</th>
                        				<th>Nombre Beneficiario</th>
                        				<th>Metodo Pago</th>
                        				<th>Banco Bneficiario</th>
                        				<th>Valor</th>
                        				<th>Descripci&oacute;n</th>
                        				<th>Opciones</th>
                    				</tr>                    				
                    			</thead>                    			
                    			<tfoot>
                    				<tr>
                    				</tr>
                    			</tfoot>
                    		</table>
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
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>   
    <script src="view/tesoreria/js/CxPAplicadas.js?0.03"></script>       
	
  </body>
</html> 