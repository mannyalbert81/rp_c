<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
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
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">   	
	<link href="view/bootstrap/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" /> 
	
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
            <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Participes</li>
          </ol>
        </section>
        
       <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Turnos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <form  action="<?php echo $helper->url("Turnos","index"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                               
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-edit'></i> Datos Participe</h4>
	         </div>
	         <div class="panel-body">
                               
                              <div class="col-lg-2 col-xs-12 col-md-2">
                    		  <div class="form-group">
                                                  <label for="cedula_afiliado" class="control-label">Cedula:</label>
                                                  <input type="hidden" class="form-control" id="id_afiliado" name="id_afiliado" value="0" >
                                                  <input type="number" class="form-control" id="cedula_afiliado" name="cedula_afiliado" value=""  placeholder="cedula..">
                                                  <div id="mensaje_cedula_afiliado" class="errores"></div>
                              </div>
                              </div>
                                 
                                    
                               <div class="col-lg-2 col-xs-12 col-md-2">
                    		   <div class="form-group">
                                                  <label for="apellidos_afiliado" class="control-label">Apellidos:</label>
                                                  <input type="text" class="form-control" id="apellidos_afiliado" name="apellidos_afiliado" value=""  placeholder="apellidos.." readonly>
                                                  <div id="mensaje_apellidos_afiliado" class="errores"></div>
                                </div>
                                </div>
                                
                               <div class="col-lg-2 col-xs-12 col-md-2">
                    		   <div class="form-group">
                                                  <label for="nombres_afiliado" class="control-label">Nombres:</label>
                                                  <input type="text" class="form-control" id="nombres_afiliado" name="nombres_afiliado" value=""  placeholder="nombres.." readonly>
                                                  <div id="mensaje_nombres_afiliado" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                  <label for="entidad_patronal_afiliado" class="control-label">Entidad Patronal:</label>
                                                  <input type="text" class="form-control" id="entidad_patronal_afiliado" name="entidad_patronal_afiliado" value=""  placeholder="entidad patronal.." readonly>
                                                  <div id="mensaje_entidad_patronal_afiliado" class="errores"></div>
                                </div>
                                </div>
                                
                                <div class="col-lg-3 col-xs-12 col-md-3">
                    		    <div class="form-group">
                                                  <label for="estado_afiliado" class="control-label">Estado:</label>
                                                  <input type="text" class="form-control" id="estado_afiliado" name="estado_afiliado" value=""  placeholder="estado.." readonly>
                                                  <div id="mensaje_estado_afiliado" class="errores"></div>
                                </div>
                                </div>
                                
                 </div>
                 </div>               
                                
                 
             <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-edit'></i> Asignación Departamento</h4>
	         </div>
	         <div class="panel-body">
                               
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                          <label for="id_departamentos" class="control-label">Departamentos:</label>
                                                          <select name="id_departamentos" id="id_departamentos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultDepa as $res) {?>
                        										<option value="<?php echo $res->id_departamentos; ?>"><?php echo $res->nombre_departamentos; ?> </option>
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_departamentos" class="errores"></div>
                                </div>
                    		    </div>
                                
                                
                                <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                          <label for="id_tramites_departamentos" class="control-label">Trámite a Realizar:</label>
                                                          <select name="id_tramites_departamentos" id="id_tramites_departamentos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  </select> 
                                                          <div id="mensaje_id_tramites_departamentos" class="errores"></div>
                                </div>
                    		    </div>
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                          <label for="id_empleados" class="control-label">Funcionario:</label>
                                                          <select name="id_empleados" id="id_empleados"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        								  </select> 
                                                          <div id="mensaje_id_empleados" class="errores"></div>
                                </div>
                    		    </div>
                    		    
                    		    <div class="col-lg-2 col-xs-12 col-md-2">
                    		    <div class="form-group">
                                                  <label for="numero_turnos_tramites" class="control-label">Número Turno:</label>
                                                  <input type="text" class="form-control" id="numero_turnos_tramites" name="numero_turnos_tramites" value=""  placeholder="número turno.." >
                                                  <div id="mensaje_numero_turnos_tramites" class="errores"></div>
                                </div>
                                </div>
                    		    
                                
              </div>
              </div>                       
                   	 
                   	            <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; margin-top:20px">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"> Generar</i></button>
                                					  <button type="button" id="Cancelar" name="Cancelar" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-remove"> Cancelar</i></button>
                                </div>
                    		    </div>
                    		    </div>     
                  
              </form>
  			
            </div>
            </section>
   
  		</div>
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
   <?php include("view/modulos/links_js.php"); ?>
 <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
 <script src="view/Turnos/js/Turnos.js?3.29" ></script>
 
 </body>
</html>