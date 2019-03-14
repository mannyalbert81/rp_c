 <!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
          <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>  
     
 		<script>
		    webshims.setOptions('forms-ext', {types: 'date'});
			webshims.polyfill('forms forms-ext');
		</script>
		
         	<script>
			$(document).ready(function(){
			$("#div_entidad").hide()
			$("#fecha_hasta").change(function(){
				 var startDate = new Date($('#fecha_desde').val());

                 var endDate = new Date($('#fecha_hasta').val());

                 if (startDate > endDate){
 
                    $("#mensaje_fecha_hasta").text("Fecha desde no debe ser mayor ");
		    		$("#mensaje_fecha_hasta").fadeIn("slow"); //Muestra mensaje de error  
		    		$("#fecha_hasta").val("");

                        }
				});

			 $( "#fecha_hasta" ).focus(function() {
				  $("#mensaje_fecha_hasta").fadeOut("slow");
			   });
			});
    	    </script>
        
    <script type="text/javascript">
	$(document).ready(function(){
		$("#buscar").click(function(){

			load_mayor(1);
			
			});
	});

	
	function load_mayor(pagina){
		
		//iniciar variables
		 var con_id_entidades=$("#id_entidades").val();
		 var con_id_tipo_comprobantes=$("#id_tipo_comprobantes").val();
		 var codigo_plan_cuentas=$("#codigo_plan_cuentas").val();
		 var con_fecha_desde=$("#fecha_desde").val();
		 var con_fecha_hasta=$("#fecha_hasta").val();

		  var con_datos={
				  id_entidades:con_id_entidades,
				  id_tipo_comprobantes:con_id_tipo_comprobantes,
				  codigo_plan_cuentas:codigo_plan_cuentas,
				  fecha_desde:con_fecha_desde,
				  fecha_hasta:con_fecha_hasta,
				  action:'ajax',
				  page:pagina
				  };


		$("#mayor").fadeIn('slow');
		$.ajax({
			url:"<?php echo $helper->url("ReporteMayor","index");?>",
            type : "POST",
            async: true,			
			data: con_datos,
			 beforeSend: function(objeto){
			   $("#mayor").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	            
			},
			success:function(data){
			
			     $("#div_mayor").html(data);
                 $("#mayor").html("");
                 $("#tabla_mayor").tablesorter(); 
				
			}
		})
	}
	
	</script>

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
        <li class="active">Mayores</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
     <div class="box-header">
          <h3 class="box-title">Buscar Mayores</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
                  
                  <div class="box-body">

								 	<div class="row">
								 	  
                                    <div class="col-xs-6 col-md-3 col-lg-3" id="div_entidad">
                                  	<div class="form-group">
                                   	<label for="id_entidades" class="control-label">Entidad:</label>
                 				  	<select name="id_entidades" id="id_entidades"  class="form-control" readonly>
			  						<?php foreach($resultEnt as $res) {?>
									<option value="<?php echo $res->id_entidades; ?>"><?php echo $res->nombre_entidades;  ?> </option>
			          			    <?php } ?>
									</select>
                                    <div id="desde" class="errores"></div>
                                    </div>
                                    </div>  
                               
                                    <div class="col-xs-6 col-md-3 col-lg-3">
                                  	<div class="form-group">
                                   	<label for="id_tipo_comprobantes" class="control-label">Tipo Comprobante:</label>
                 				    <select name="id_tipo_comprobantes" id="id_tipo_comprobantes"  class="form-control" >
			  						<option value="0"><?php echo "--TODOS--";  ?> </option>
									<?php foreach($resultTipCom as $res) {?>
									<option value="<?php echo $res->id_tipo_comprobantes; ?>"><?php echo $res->nombre_tipo_comprobantes;  ?> </option>
			            			<?php } ?>
									</select>
									<div id="desde" class="errores"></div>
                                    </div>
                                    </div> 
                             
                                    <div class="col-xs-6 col-md-3 col-lg-3">
                                  	<div class="form-group">
                                    <label for="codigo_plan_cuentas" class="control-label">Nº Cuenta:</label>
                             	  	<input type="text"  name="codigo_plan_cuentas" id="codigo_plan_cuentas" value="" class="form-control"/> 
    						        <div id="desde" class="errores"></div>
                                    </div>
                              		</div> 
                              		        	
                                    <div class="col-xs-6 col-md-3 col-lg-3">
                                  	<div class="form-group">
                                   	<label for="desde" class="control-label">Desde:</label>
                               	  	<input type="date"  name="fecha_desde" id="fecha_desde" value="" class="form-control "/> 
							        <div id="desde" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-xs-6 col-md-3 col-lg-3 ">
                                	<div class="form-group">
                                    <label for="hasta" class="control-label">Hasta:</label>
                       		  	    <input type="date"  name="fecha_hasta" id="fecha_hasta" value="" class="form-control "/> 
	                                <div id="hasta" class="errores"></div>
                                    </div>
                                    </div>  
                            
                        		    </div>	   
					   
                           			<div class="row">
                    			    <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
 		                	   		<div class="form-group">
        				            <button type="button" id="buscar" name="buscar" value="Buscar"   class="btn btn-info" ><i class="glyphicon glyphicon-search"></i></button>
		                    		</div>
            	        		    </div>
                    		    	</div>
    
   			
   							
                      	
                      	
                      	<div id="mayor" ></div>	
					<div id="div_mayor"></div>
                      	   
            </div>
        </section>
              
    
    
  </div>
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>
	

	
	
  </body>
</html>   

