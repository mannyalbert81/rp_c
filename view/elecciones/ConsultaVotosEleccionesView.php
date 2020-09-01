<!DOCTYPE HTML>
	<html lang="es">
    <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
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
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<link rel="stylesheet" href="view/bootstrap/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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
        <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Search</li>
    </ol>
  </section>
  
      <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Fuerzas</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#fuerza_aerea" data-toggle="tab">FUERZA AÉREA</a></li>
              <li><a href="#comando_conjunto" data-toggle="tab">COMANDO CONJUNTO</a></li> 
              <li><a href="#ministerio_defensa" data-toggle="tab">MINISTERIO DE DEFENSA</a></li> 
              <li><a href="#fuerza_naval" data-toggle="tab">FUERZA NAVAL</a></li> 
              <li><a href="#fuerza_terrestre" data-toggle="tab">FUERZA TERRESTRE</a></li> 
            
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
 			 <div class="tab-pane active" id="fuerza_aerea">
                
				<div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador_fuerza_aerea" name="buscador_fuerza_aerea" onkeyup="ConsultaCandidatosFuerzaAerea(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaCandidatosFuerzaAerea"></div>  
    	    	<div id="consulta_candidatos_fuerza_aerea_tbl" ></div>
    	    	<div id="divLoaderPageFuerzaAerea" ></div>
				  
                
              </div>
              
              <div class="tab-pane" id="comando_conjunto">
                
                 <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador_comando_conjunto" name="buscador_comando_conjunto" onkeyup="ConsultaCandidatosComandoConjunto(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaCandidatosComandoConjunto"></div>  
    	    	<div id="consulta_candidatos_comando_conjunto_tbl" ></div>
    	    	<div id="divLoaderPageComandoConjunto" ></div>	
					
              </div>
              
               <div class="tab-pane" id="ministerio_defensa">
                
                 <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador_ministerio_defensa" name="buscador_ministerio_defensa" onkeyup="ConsultaCandidatosMinisterioDefensa(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaCandidatosMinisterioDefensa"></div>  
    	    	<div id="consulta_candidatos_ministerio_defensa_tbl" ></div>
    	    	<div id="divLoaderPageMinisterioDefensa" ></div>	
					
              </div>
              <div class="tab-pane" id="fuerza_naval">
                
                 <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador_fuerza_naval" name="buscador_fuerza_naval" onkeyup="ConsultaCandidatosFuerzaNaval(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaCandidatosFuerzaNaval"></div>  
    	    	<div id="consulta_candidatos_fuerza_naval_tbl" ></div>
    	    	<div id="divLoaderPageFuerzaNaval" ></div>	
					
              </div>
              <div class="tab-pane" id="fuerza_terrestre">
                
                 <div class="pull-right" style="margin-right:15px;">
					<input type="text" value="" class="form-control" id="buscador_fuerza_terrestre" name="buscador_fuerza_terrestre" onkeyup="ConsultaCandidatosFuerzaTerrestre(1)" placeholder="Buscar.."/>
    			</div>  
    			
    			<div id="ConsultaCandidatosFuerzaTerrestre"></div>  
    	    	<div id="consulta_candidatos_fuerza_terrestre_tbl" ></div>
    	    	<div id="divLoaderPageFuerzaTerrestre" ></div>	
					
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
	
	 
   <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="view/elecciones/js/ConsultaVotosElecciones.js?0.2"></script>
	
	
  </body>
</html> 