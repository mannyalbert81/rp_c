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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Contabilidad</a></li>
        <li class="active">Activos Fijos</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Estados</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
          
        
        <form action="<?php echo $helper->url("Estados","InsertaEstados"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                                <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                                
                                <div class="row">
                        		  
                                  
                        		   
                                    
                                    <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_estado" class="control-label">Nombre del Estado:</label>
                                                          <input type="text" class="form-control" id="nombre_estado" name="nombre_estado" value="<?php echo $resEdit->nombre_estado; ?>"  placeholder="nombre...">
                                                          <input type="hidden" name="id_estado" id="id_estado" value="<?php echo $resEdit->id_estado; ?>" class="form-control"/>
					                                      <div id="mensaje_nombre_estado" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		     <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                       
                                                          <label for="id_modulos" class="control-label">Nombre de la tabla:</label>
                                                          <select name="id_modulos" id="id_modulos"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultMod as $resMod) {?>
				 												<option value="<?php echo $resMod->id_modulos; ?>" <?php if ($resMod->id_modulos == $resEdit->id_modulos )  echo  ' selected="selected" '  ;  ?> ><?php echo $resMod->nombre_modulos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										  <div id="mensaje_id_modulos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                 </div>

                                 
                                
                    		     <?php } } else {?>
                    		    
                    		   
								 <div class="row">
                        		    
                        		   <div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="nombre_estado" class="control-label">Nombre del Estado:</label>
                                                          <input type="text" class="form-control" id="nombre_estado" name="nombre_estado" value=""  placeholder="nombre...">
                                                          <input type="hidden" name="id_estado" id="id_estado" value="0" class="form-control"/>
					                                       <div id="mensaje_nombre_estado" class="errores"></div>
                                    </div>
                        		    </div> 
                                   
                        		    <div class="col-xs-12 col-md-3 col-md-3">
                        		    <div class="form-group">
                                                          <label for="id_modulos" class="control-label">Nombre de la tabla:</label>
                                                          <select name="id_modulos" id="id_modulos"  class="form-control">
                                                            <option value="0" selected="selected">--Seleccione--</option>
																<?php foreach($resultMod as $resMod) {?>
				 												<option value="<?php echo $resMod->id_modulos; ?>"  ><?php echo $resMod->nombre_modulos; ?> </option>
													            <?php } ?>
								    					  </select>
		   		   										   <div id="mensaje_id_modulos" class="errores"></div>
                                    </div>
                                    </div>
                        		   
                        		  </div>
                        		  
                        
									
								<?php } ?>
                    		    <br>  
                    		    <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Guardar</button>
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
              <h3 class="box-title">Listado Estados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
           <div class="nav-tabs-custom">
            
            
            <div class="col-md-5 col-lg-12 col-xs-5">
            <div class="tab-content">
            
            <br>
              <div class="tab-pane active" id="estados">
              
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_estados" name="search_estados" onkeyup="load_estados(1)" placeholder="search.."/>
						
					</div>
					<div id="load_estados" ></div>
					<div id="estados_registrados"></div>	
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

    <script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
	<script src="view/Contable/FuncionesJS/ActivosFijos.js?1"></script> 

    
	
    <script type="text/javascript" >   
    
    	function numeros(e){
    		  var key = window.event ? e.which : e.keyCode;
    		  if (key < 48 || key > 57) {
    		    e.preventDefault();
    		  }
     }
    </script> 
    
    
	<script type="text/javascript">
     
        	   $(document).ready( function (){
        		   
        		   load_estados(1);
        		   
        		   
	   			});
	   			
   		
   	   	}		

        	


	   function load_estados(pagina){

		   var search=$("#search_estados").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_estados").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_estados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Estados&action=consulta_estados&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#estados_registrados").html(x);
	                 $("#load_estados").html("");
	                 $("#tabla_estados").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#estados_registrados").html("Ocurrio un error al cargar la informacion de Estados Activos..."+estado+"    "+error);
	              }
	            });


		   }

 </script>
	
	
	  

  </body>
</html>   

 