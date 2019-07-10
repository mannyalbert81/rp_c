<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    
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
        <li class="active">Estados Cuenta</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Subir Estado Cuenta</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
  		<div class="box-body">
			<form id="frm_bancos" action="<?php echo $helper->url("Bancos","Index"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             <div class = "row">
             	<div class="col-xs-12 col-md-3 col-md-3 ">
            		    <div class="form-group">
                          <label for="nombre_bancos" class="control-label">Nombre Bancos:</label>
                          <select name="estado_cargo" id="id_banco"  class="form-control">
                                      <option value="" selected="selected">--Seleccione--</option>
    								  <?php  foreach($resultBancos as $res) {?>
									  <option value="<?php echo $res->id_bancos; ?>"><?php echo $res->nombre_bancos; ?> </option>
			        				  <?php } ?>
    						</select> 
                          <div id="mensaje_nombre_bancos" class="errores"></div>                                              
                        </div>
            		  </div>
         		<div class="col-xs-12 col-md-3 col-lg-3 " style="margin-left:15px;">
         			<label for="nombre_bancos" class="control-label">Archivo Estado Cuentas:</label>
         			
        			<div class="input-group">
        				<input type="text" id= "nombre_archivo" class="form-control" placeholder = "Archivo" readonly>
        				<span class="input-group-btn">
                    		<label class="btn btn-primary">
    						<i class="glyphicon glyphicon-plus"></i>
    						<input type="file" id="archivo_registro" style="display: none;">
							</label>
							<button type="button" class="btn btn-success" id="subir_archivo" name="subir_archivo" onclick="SubirArchivo()">
							<i class="glyphicon glyphicon-upload"></i>
							</button>
							
						</span>
					</div>
					<div id="mensaje_archivo" class="errores"></div> 
					
        		</div>
        		
        		
			</div>	
						                             
           </form>
                      
          </div>
    	</div>
    </section>
              
 <section class="content">
  	<div class="box box-primary">
  		<div class="box-header with-border">
  			<h3 class="box-title">Listado de documentos</h3>
  			<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div> 
        <div class="box-body">
         	<div class = "row">
        		<div class="col-xs-6 col-md-3 col-lg-3 " style="margin-left:15px;" >
                	<div id="load_boton_notificaciones" ></div>	
        		</div>
        		<div id="mensaje_archivo" class="errores"></div>
			</div>
			<br>
			<div id="cabecera_marcaciones" ></div>
			<div class="pull-right" style="margin-right:15px;">
        			<select name="anio_archivo" id="anio_archivo"  class="form-control" onchange="MostrarDocumentos(1)">
                  		<option value="0">Todos los años</option>
                  		<?php  foreach($resultAnios as $res) {?>
									  <option value="<?php echo $res->anio_archivo_estado_cuenta ?>"><?php echo $res->anio_archivo_estado_cuenta; ?> </option>
			        				  <?php } ?>
    				</select>
    		</div>
				<div class="pull-right" style="margin-right:15px;">
        			<select name="mes_archivo" id="mes_archivo"  class="form-control" onchange="MostrarDocumentos(1)">
                  		<option value="mes">Todos los meses</option>
						<?php  foreach($meses as $res=>$key) {?>
									  <option value="<?php echo $res ?>"><?php echo $key; ?> </option>
			        				  <?php } ?>
    				</select>
    		</div>
    		
			<div class="pull-right" style="margin-right:15px;">
        			<select name="banco_archivo" id="banco_archivo"  class="form-control" onchange="MostrarDocumentos(1)">
                  		<option value="0">Todos los bancos</option>
						  <?php  foreach($resultBancos as $res) {?>
									  <option value="<?php echo $res->id_bancos; ?>"><?php echo $res->nombre_bancos; ?> </option>
			        				  <?php } ?>
    				</select>
    		</div>
    	
        	<div id="listado_documentos" ></div>
       </div>
  	</div>
  </section>
    
  </div>
  
  <!-- Modal VistaPreliminar -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalVista" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Vista Preliminar Archivo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div id="preliminar_archivo"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

  <!-- Modal VistaDocs -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalDoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Vista Archivo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div id="preliminar_archivo"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
   <script src="view/tesoreria/js/EstadosCuenta.js?0.10"></script> 
   </body>
</html>   

