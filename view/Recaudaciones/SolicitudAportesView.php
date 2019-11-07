<!DOCTYPE HTML>
<html lang="es">
      <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="view/bootstrap/otros/css/tablaFixed.css?1"/> 
 	
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
        <li class="active">Solicitud Aportes</li>
      </ol>
    </section>   
                
     <section class="content">
      	<div class="box box-primary">
      		<div class="box-header with-border">
      			<h3 class="box-title">Solicitud Aportes</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="txt_lista_buscador" name="txt_lista_buscador" onkeyup="listaArchivosRecaudacion(1)" placeholder="Buscar.."/>
    			</div> 
    			<div class="clearfix"></div>           	
            	<div class="box-body no-padding">
              		<table id="tbl_solicitudes_aportes" class="tablesorter table table-striped table-bordered dt-responsive nowrap" cellspacing="0"  width="98%">
                      <thead>
                        <tr class="">
                          <th>#</th>
                          <th>Cedula</th>
                          <th>Nombre</th>
                          <th>Apellidos</th>
                          <th>Tipo Solicitud</th>
                          <th>Valor</th>
                          <th>Observacion</th>
                          <th>Fecha Solicitud</th>
                          <th>Estado</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>                  
                      <tbody>
                        <tr>
                          <!--<td style="display:none" >No existe Datos</td>
                           -->
                          <td colspan="11"></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td>
                          <td style="display:none" ></td> 
                          <td style="display:none" ></td>   
                                                  
                        </tr>
                      </tbody>
                    </table>
	            <div id="tbl_solicitudes_aportes_paginacion" class="table-pagination pull-right">
	            </div>  
          	  </div>
          	
          	<div class="clearfix"></div>
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
   <script src="view/Recaudaciones/js/SolicitudAportes.js?0.04"></script> 

  </body>
</html>   

