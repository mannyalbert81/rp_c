<!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    
    <?php include("view/modulos/links_css.php"); ?>		
      
    	
	
		    
	</head>
 
    <body class="hold-transition skin-blue fixed sidebar-mini" ng-app="myApp" ng-controller="myCtrl">
    
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
        <li class="active">Detalle de Activos</li>
      </ol>
    </section>



    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Depreciación de Activos</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        
        <div class="box-body">
          
        <form action="<?php echo $helper->url("ActivosFijosDetalle","Depreciar"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                               
								 <div class="row">
								
								 <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                                          <label for="codigo_activos_fijos" class="control-label">Código:</label>
                                          <input type="text" class="form-control" id="codigo_activos_fijos" name="codigo_activos_fijos" value=""  placeholder="Código">
                                          <div id="mensaje_codigo_activos_fijos" class="errores"></div> 
                                          <input type="hidden" class="form-control" id="id_activos_fijos" name="id_activos_fijos" value="0" >
                                  <span class="help-block"></span>
 									</div>
                        		     </div>
                        		     
                        		     <div class="col-xs-12 col-md-2 col-lg-2">
                        		     <div class="form-group">
                                          <label for="nombre_activos_fijos" class="control-label">Nombre Activos Fijos:</label>
                                          <input type="text" class=" form-control" id="nombre_activos_fijos" name="nombre_activos_fijos" value=""  placeholder="Nombre">
                                           <div id="mensaje_nombre_activos_fijos" class="errores"></div> 
                                           <input type="hidden" class="form-control" id="activos_fijos2" name="activos_fijos2" value="0"  placeholder="Search">
                                  <span class="help-block"></span>
                                     </div>
                        		     </div>
                                    
									<div class="col-xs-12 col-md-3 col-md-3 ">
                        		    <div class="form-group">
                                                          <label for="anio_depreciacion_activos_fijos_detalle" class="control-label">Año:</label>
                                                          <select id="anio_depreciacion_activos_fijos_detalle" name="anio_depreciacion_activos_fijos_detalle" class="form-control" ng-model="year" class="form-control" ng-options="y for y in years"></select>
                                                           <div id="mensaje_anio_depreciacion_activos_fijos_detalle" class="errores"></div>
                                    </div>
                        		    </div> 
                        		    
                        		    
									
									 
                        		     <?php  ?>
  
   									
   	         	                     	           	
                    		     
                    		    <br>  
                    		    <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; ">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success">Calcular</button>
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
              <h3 class="box-title">Listado Activos Fijos Depreciados</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">
            
           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activos" data-toggle="tab"> Activos Depreciados</a></li>
              
            </ul>
            
            <div class="col-md-5 col-lg-12 col-xs-5">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_activos" name="search_activos" onkeyup="load_activos_fijos_detalle(1)" placeholder="search.."/>
					</div>
					<div id="load_activos_fijos_detalle" ></div>	
					<div id="activos_fijos_registrados_detalle"></div>	
                
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
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
	
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
        		   
        		   load_activos_fijos_detalle(1);
        		   
        		   
	   			});

        	


	   function load_activos_fijos_detalle(pagina){

		   var search=$("#search_activos").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_activos_fijos_detalle").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_activos_fijos_detalle").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=ActivosFijosDetalle&action=consulta_activos_fijos_detalle&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#activos_fijos_registrados_detalle").html(x);
	                 $("#load_activos_fijos_detalle").html("");
	                 $("#tabla_activos_fijos_detalle").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#activos_fijos_registrados_detalle").html("Ocurrio un error al cargar la informacion de Detalle Activos..."+estado+"    "+error);
	              }
	            });


		   }

 </script>
	
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
	
	<script>
      var app = angular.module('myApp', []);
      app.controller('myCtrl', function($scope, $http) {
        $scope.years = [];
        $scope.year = new Date().getFullYear();
        $scope.months = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"]
        $scope.month = $scope.months[new Date().getMonth()];
        
        for (var i = 0; i < 1; i++) {
            $scope.years.push($scope.year-i);
            //console.log($scope.year-i);
        }  
    })
    </script>  


	  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      
    <script src="view/Contable/FuncionesJS/ActivosFijosD.js?1.0"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
    
    
    
    
    
	
  </body>
</html>  