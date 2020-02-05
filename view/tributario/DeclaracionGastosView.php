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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Declaraciones</a></li>
        <li class="active">Formulario</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">FORMULARIO 107 SRI-GP</h3>
          
        </div>
        
        <div class="box-body">
        
        	
        	<div class="row">
    		  
    		    <div class="col-xs-12 col-md-3 col-lg-3 ">
    		    <div class="form-group">
                      <label for="anio_formulario_107" class="control-label">Año:</label>
                      <input type="text" class="form-control" id="anio_formulario_107" name="anio_formulario_107" placeholder="" readonly value="<?php echo date('Y');?>">
                      <div id="mensaje_anio_formulario_107" class="errores"></div>
                </div>
    		    </div>
                
               
              
				<div class="col-xs-3 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_usuarios" class="control-label">Cedula:</label>
                    	<input type="text"  class="form-control" id="cedula_empleado" name="cedula_empleado" value="<?php echo $_rs_consulta[0]->numero_cedula_empleados;?>" placeholder="C.I.">
                       <input type="hidden" id="id_empleados_1" name="id_empleados_1" value="<?php echo $_rs_consulta[0]->id_empleados;?>">
                        <div id="mensaje_cedula_usuarios" class="errores"></div>
                 	</div>
                 	</div>
                 	 
             	<div class="col-xs-3 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="nombre_empleados" class="control-label">Apellidos y Nombres Completos:</label>
                    	<input type="text" class="form-control" id="nombre_empleados" name="nombre_empleados" value="<?php echo  $_rs_consulta[0]->nombres_empleados;?>" placeholder="Nombres y Apellidos">
                    	 <div id="mensaje_nombre_empleados" class="errores"></div>
                 	</div>
             	</div> 
                 
               </div>
                      		  
          
        </div>
        
        
      </div>
    </section>
    
     <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">DECLARACIÓN DE GASTOS PERSONALES A SER UTILIZADOS POR EL EMPLEADOR EN EL CASO DE INGRESOS EN RELACIÓN DE DEPENDENCIA</h3>
         
        </div>
        
        <div class="box-body">
        
        <form id="frm_declaracion_gastos"  method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
        	
        	   <div class="row">
                    		   
             
            <table class="col-lg-12 col-md-12 col-xs-12 tablesorter table table-striped table-bordered dt-responsive nowrap">
            
            
            
            
             <tr>
            <td style="text-align: center;"><b>Ingresos Grabados Proyectados</b></td>
            <td style="text-align: center;"><b>Valor en Dólares</b></td>
            <td style="text-align: center;"><b>Gastos Proyectados</b></td>
            <td style="text-align: center;"><b>Valor en Dólares</b></td>
            </tr>
            
            <tr>
            <td>Total Ingresos Gravados con este Empleador</td>
            <td><input type="text" class="form-control cantidades1" id="ingresos_gravados_empleador" name="ingresos_gravados_empleador"  value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_ingresos_gravados_empleador" class="errores"></div></td>
            <td>Gastos de Vivienda</td>
            <td><input type="text" class="form-control cantidades1" id="gastos_vivienda" name="gastos_vivienda" value='0.00'  data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_gastos_vivienda" class="errores"></div></td>
            </tr>
            
            <tr>
            <td>Total Ingresos con otros Empleados</td>
            <td><input type="text" class="form-control cantidades1" id="ingresos_otros_empleados" name="ingresos_otros_empleados"  value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_ingresos_otros_empleados" class="errores"></div></td>
            <td>Gastos de Educación, Arte y Cultura</td>
            <td><input type="text" class="form-control cantidades1" id="gastos_educacion" name="gastos_educacion" value='0.00'  data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_gastos_educacion" class="errores"></div></td>
            </tr>
            
            <tr>
            <td>Total Ingresos Proyectados</td>
            <td><input type="text" class="form-control cantidades1" id="ingresos_proyectados" name="ingresos_proyectados" value='0.00'  data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_ingresos_proyectados" class="errores"></div></td>
            <td>Gastos de Salud</td>
            <td><input type="text" class="form-control cantidades1" id="gastos_salud" name="gastos_salud" value='0.00'  data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_gastos_salud" class="errores"></div></td>
            </tr>
            
            <tr>
            <td></td>
            <td></td>
            <td>Gastos de Vestimenta</td>
            <td><input type="text" class="form-control cantidades1" id="gastos_vestimenta" name="gastos_vestimenta"  value='0.00' data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_gastos_vestimenta" class="errores"></div></td>
            </tr>
            
            <tr>
            <td></td>
            <td></td>
            <td>Gastos de Alimentación</td>
            <td><input type="text" class="form-control cantidades1" id="gastos_alimentacion" name="gastos_alimentacion" value='0.00'  data-inputmask="'alias': 'numeric', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"><div id="mensaje_gastos_alimentacion" class="errores"></div></td>
            </tr>
            
            </table>
             
            </div>
    		    
 		
		      
                    		  
        </form>
          
        </div>
        
        
      </div>
    </section>
    
    
        <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">IDENTIFICACIÓN DEL AGENTE DE RETENCIÓN</h3>
          
        </div>
        
        <div class="box-body">
        
        	 <form id="frm_declaracion"  method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
       
        	<div class="row">
    		  
    		   <div class="col-xs-3 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="ruc_agente_retencion" class="control-label">Ruc:</label>
                    	<input type="text"  class="form-control" id="ruc_agente_retencion" name="ruc_agente_retencion" value="" placeholder="Ruc..">
                        <div id="mensaje_ruc_agente_retencion" class="errores"></div>
                 	</div>
                 	</div>
                 	 
             	<div class="col-xs-3 col-md-6 col-lg-6 ">
            		<div class="form-group">
                		<label for="razon_social" class="control-label">Razón Social, Denominación o Apellidos y Nombres:</label>
                    	<input type="text" class="form-control" id="razon_social" name="razon_social" value="" placeholder="Razón..">
                    	 <div id="mensaje_razon_social" class="errores"></div>
                 	</div>
             	</div> 
                 
               </div>
               
               
               	<div class="row">
		    	<div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
		    		<div class="form-group">
              			 <button type="button" id="Guardar" name="Guardar" class="btn btn-success" onclick="InsertarDeclaracionGastos()">GUARDAR</button>
                </div>
		    	</div>
		    	
		    	
		    </div>
               
            </form>          		  
          
        </div>
        
        
      </div>
    </section>
            
    
     
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>    
    <script type="text/javascript" src="view/tributario/FuncionesJS/DeclaracionGastos.js?3.4"></script>
	
    <script type="text/javascript" >   
    
    	function numeros(e){
    		  var key = window.event ? e.which : e.keyCode;
    		  if (key < 48 || key > 57) {
    		    e.preventDefault();
    		  }
     }
    </script> 
 
    
  

<script>
    $(document).ready(function(){
    	$(".cantidades1").inputmask();
    });
</script>
</body>
</html>
