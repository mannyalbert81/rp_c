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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Créditos</a></li>
        <li class="active">Garantía</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Crédito Garante</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        <form id="frm_garantia" action="<?php echo $helper->url("GarantiaCredito","InsertaGarantia"); ?>" method="post" class="col-lg-12 col-md-12 col-xs-12">
             
				<br>			    
							    
		    	 <div class="row">
        		 <input type="hidden" id="id_creditos_garantias" value="0">
           
     	<div class="col-md-2 col-lg-2 col-xs-12">
	         	<div class="form-group">
	         		<label for="id_creditos" class="control-label">Numero Credito :</label>
	         		<input type="text" id="id_creditos" name="id_creditos"  value="" readonly class="form-control">
                         <div id="mensaje_id_creditos" class="errores"></div>
                    
                    </div>
	         	</div>
	         	
	         		<div class="col-md-4 col-lg-4 col-xs-12">
	         	<div class="form-group">
	         		<label for="id_participes" class="control-label">Paticipe :</label>
	         		<input type="text" id="id_participes" name="id_participes"  value="" readonly class="form-control">
                         <div id="mensaje_id_participes" class="errores"></div>
                    
                    </div>
	         	</div>
	        
           <div class="col-xs-12 col-md-3 col-md-3">
            		    <div class="form-group">
            		    					  
                          <label for="id_estado" class="control-label">Estado:</label>
                          <select  class="form-control" id="id_estado" name="id_estado" required>
                          	<option value="0">--Seleccione--</option>
                          </select>                         
                          <div id="mensaje_id_estado" class="errores"></div>
                        </div>
            		  </div>
            		  
	      
  	   	</div>	
						<BR>	          		        
           		<div class="row">
    			    <div class="col-xs-12 col-md-12 col-lg-12 " style="text-align: center; ">
        	   		    <div class="form-group">
    	        <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">GUARDAR</button>
    	        
    	    
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
      			<h3 class="box-title">Listado Créditos Garantias</h3>      			
            </div> 
            <div class="box-body">
    			<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador" name="buscador" onkeyup="consultaGarantia()" placeholder="Buscar.."/>
    			</div>            	
            	<div id="garantia_registrados" ></div>
            </div> 	
      	</div>
      </section> 
    
  
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>

    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
  <script src="view/Credito/js/GarantiaCredito.js?0.18"></script> 

  </body>
</html>   

 