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
      <h1>
        
        <small><?php echo $fecha; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cuentas Pagar</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     
     	<div class="box-header">
          <h3 class="box-title">Listado Cuentas por Pagar</h3>
          <div class="box-tools pull-right">           
          </div>
        </div>        
                  
  		<div class="box-body">
  		
  			<div id="divLoaderPage" ></div> 
  		
      		<div class="row">
     			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            		<div id="div_listado_cuentas_pagar_pendientes" class="letrasize11">
                		<table id="tbl_listado_cuentas_pagar_pendientes" class="table table-bordered display compact">
                			<thead>
                				<tr class="warning">
                    				<th>#</th>
                    				<th>Lote</th>
                    				<th>Origen</th>
                    				<th>Generado Por</th>
                    				<th>Descripci&oacute;n</th>
                    				<th>Fecha</th>
                    				<th>Beneficiario</th>
                    				<th>Valor Documento</th>
                    				<th>Saldo Documento</th>
                    				<th>Cheque</th>
                    				<th>Transferencia</th>
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
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<script type="text/javascript" src="view/tesoreria/js/Pagos.js?0.7"></script>

  </body>
</html>   

