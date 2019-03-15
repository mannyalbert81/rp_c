<!DOCTYPE HTML>
<html lang="es">
      <head>
      
      <script lang=javascript src="view/Contable/FuncionesJS/xlsx.full.min.js"></script>
      <script lang=javascript src="view/Contable/FuncionesJS/FileSaver.min.js"></script>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    
               	
     		<?php include("view/modulos/links_css.php"); ?>		
     
     
 		  
			        
    </head>
    
    
    <body class="hold-transition skin-blue fixed sidebar-mini"  >
    
        
    
     <?php
        
           $idc=$_SESSION['id_entidades'];       
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $pgurl= $helper->url("PlanCuentas","generar_reporte_Pcuentas");

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
        <li class="active">Consulta Plan Cuentas</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Buscar Actividades</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
                  <div class="box-body">
                  
                                 
                                   

								 	<div class="row">
								 	
								 	<form id ="my_form" action= "<?php echo $pgurl;?>" method="POST" target="_blank">
								 	  
                                   <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                                          <label for="codigo_plan_cuentas" class="control-label">Código:</label>
                                          <input type="text" class="form-control" id="codigo_plan_cuentas" name="codigo_plan_cuentas" value=""  placeholder="Código">
                                          <div id="mensaje_codigo_plan_cuentas" class="errores"></div> 
                                     <input type="hidden" class="form-control" id="plan_cuentas" name="plan_cuentas" value="0"  placeholder="Search">
                                  <span class="help-block"></span>
 									</div>
                        		     </div>
                        		     
                        		     <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                                          <label for="nombre_plan_cuentas" class="control-label">Nombre:</label>
                                          <input type="text" class=" form-control" id="nombre_plan_cuentas" name="nombre_plan_cuentas" value=""  placeholder="Nombre">
                                           <div id="mensaje_nombre_plan_cuentas" class="errores"></div> 
                                           <input type="hidden" class="form-control" id="plan_cuentas2" name="plan_cuentas2" value="0"  placeholder="Search">
                                  <span class="help-block"></span>
                                     </div>
                        		     </div>
                        		     
                        		     
                        		     
                        		      <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                        		     <label for="nivel_plan_cuentas" class="control-label">Nivel de cuenta:</label>
                        		     <select name="nivel_plan_cuentas" id="nivel_plan_cuentas"  class="form-control" >
                                      <option value="" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultSet as $res) {?>
    										<option value="<?php echo $res->nivel_plan_cuentas; ?>"><?php echo $res->nivel_plan_cuentas; ?></option>
    							        <?php } ?>
    								   </select> 
                                          <div id="mensaje_nivel_plan_cuentas" class="errores"></div> 
                                     </div>
                        		     </div>
                        		     
                        		     
                        		     
                        		      <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                        		     <label for="nivel_plan_cuentas" class="control-label">N de cuenta:</label>
                        		     <select name="n_plan_cuentas" id="n_plan_cuentas"  class="form-control" >
                                      <option value="" selected="selected">--Seleccione--</option>
    									<?php  foreach($resultSet2 as $res) {?>
    										<option value="<?php echo $res->n_plan_cuentas; ?>"><?php echo $res->n_plan_cuentas; ?></option>
    							        <?php } ?>
    								   </select> 
                                          <div id="mensaje_nivel_plan_cuentas" class="errores"></div> 
                                     </div>
                        		     </div>
 
                           			<div class="row">
                    			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
 		                	   		<div class="form-group">
        				            <button type="button" id="buscar" name="buscar" value="Buscar"   class="btn btn-info" ><i class="glyphicon glyphicon-search"></i></button>
                                     <a class="btn btn-danger" onclick="document.getElementById('my_form').submit();"><i class="fa fa-file-pdf-o"></i></a>
                                     <button type="button" id="exportar" name="exportar" value="Exportar"   class="btn btn-success" ><i class="fa fa-file-excel-o"></i></button>
        	               
		                    		</div>
            	        		    </div>
                    		    	</div>
                    		    	
                    		    	</form>
                    		    	
                    		    	
   
									
								   <div id="comprobantes"></div><!-- Carga gif animado -->
									<div id="div_comprobantes"></div>
					    			     
                      
                      
         	         </div>
            </div>
        </section>
              
    
    
  </div>
  
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      
    <script src="view/Contable/FuncionesJS/PlanCuentas.js?1"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
    
    
    
	

	
	
  </body>
</html>   

