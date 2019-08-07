<!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <?php include("view/modulos/links_css.php"); ?>		
   
    	
	     <link href="view/reportes/template/all.min.css" rel="stylesheet">
		    
	</head>
 
 
 <style type="text/css">
    .zoom{
        /* Aumentamos la anchura y altura durante 2 segundos */
        transition: width 2s, height 2s, transform 2s;
        -moz-transition: width 2s, height 2s, -moz-transform 2s;
        -webkit-transition: width 2s, height 2s, -webkit-transform 2s;
        -o-transition: width 2s, height 2s,-o-transform 2s;
    }
    .zoom:hover{
        /* tranformamos el elemento al pasar el mouse por encima al doble de
           su tamaño con scale(2). */
        transform : scale(1.4);
        -moz-transform : scale(1.4);      /* Firefox */
        -webkit-transform : scale(1.4);   /* Chrome - Safari */
        -o-transform : scale(1.4);        /* Opera */
        
        
    }
</style>

<style type="text/css"> 
.thumbnail {position: relative; z-index: 0;} 
.thumbnail:hover {background-color: transparent; z-index: 50;} 
.thumbnail span img{ border-width: 0; padding: 2px; } 
.thumbnail:hover span{ visibility: visible; top: 0; left: 10px; } 
.thumbnail span{ 
position: absolute; 
background-color: #FAFAFA; 
-webkit-box-shadow: 0 0 4px 2px #D6D6D6; 
box-shadow: 0 0 5px 2px #D6D6D6; 
padding: 5px; 
left: -100px; 
border: 3px double gray; 
visibility: hidden; 
color: #000; 
text-align:center; 
text-decoration: none; 
} 
</style>


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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Core</a></li>
        <li class="active">Firmas</li>
      </ol>
    </section>

    
 <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Firmas de los Participes</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <br>
              <div class="tab-pane active" id="firmas">
                
					<div class="pull-right" style="margin-right:15px;">
						<input type="text" value="" class="form-control" id="search_firmas_participes" name="search_firmas_participes" onkeyup="load_firmas_participes(1)" placeholder="search.."/>
					</div>
					<div id="load_firmas_participes" ></div>	
					<div id="firmas_registradas"></div>	
                
            
                
              </div>
            </div>
            </div>
            </section>
    
     
    
  </div>
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
        <div id="popUp" class="modal">
                  <span class="close">&times;</span>
                  <img class="modal-content" id="img01">
                  <div id="caption"></div>
        </div>
    
    
    <?php include("view/modulos/links_js.php"); ?>
    
    
    
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
	
    
<script type="text/javascript">

        	   $(document).ready( function (){
        		   
        		 
        		   load_firmas_participes(1);
        		   
	   			});

        	


	   function load_firmas_participes(pagina){

		   var search=$("#search_firmas_participes").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_firmas_participes").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_firmas_participes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreFirmasParticipe&action=consulta_firmas_participes&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#firmas_registradas").html(x);
	                 $("#load_firmas_participes").html("");
	                 $("#tabla_firmas").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#firmas_registradas").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
	              }
	            });


		   }



 </script>
	

<script type="text/javascript">

    
        $("#firmas_registradas").on("click","#myImg",function(){
        console.log('ingreso');
        	let $modal = $("#popUp");
        	let $caption = $("#caption");
        	let $modalImg = $("#img01");
        	 $modal.css({"display":"block"});
        	 console.log($(this).attr("src")); 	
        	$modalImg.attr("src",$(this).attr("src"));
        	 $caption.html($(this).attr("alt"));
        
        })
        $(".close").on("click",function(){
        console.log('ingreso');
        	let $modal = $("#popUp");
        	 $modal.css({"display":"none"});
})
</script>	
    
    
    
    
    
	
  </body>
</html>  