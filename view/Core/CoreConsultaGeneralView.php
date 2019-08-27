<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style type="text/css">
    
    

#ico{ list-style-image:url(ico.png);}
#ico a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; text-decoration:none; color:#047;}
#ico a:hover{text-decoration:underline; color:#C00;}

    .scrollable-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
}

	ul{
        list-style-type:none;
      }
  li{
    list-style-type:none;
    }
    td.fila {

    width: 100px;

}
    
    
    </style>
    
    
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
            <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Formulario B17</li>
          </ol>
        </section>
        
        <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Consulta General</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            <div class="box-body">
            <?php if ($resultRep !="" ) { foreach($resultRep as $resEdit) {?>
           
            <div class="row">
            <div class="col-xs-6 col-md-6 col-lg-6 ">
            		<div class="form-group">
                		<div class="form-group-sm">
                    				<label for="nombre_entidad_patronal" class="col-sm-4 control-label" > Entidad Patronal:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style=" width:405px; height:20px" readonly="readonly" class="form-control" id="nombre_entidad_patronal" name="nombre_entidad_patronal"  value="<?php echo $resEdit->nombre_entidad_patronal; ?>" >
                    				  <div id="mensaje_nombre_entidad_patronal" class="errores"></div>
                    				</div>
                    			 </div> 
                 	</div>
             	</div>
            </div>

            <div class="row">
        	
                	<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="cedula_participes" class="col-sm-4 control-label" > Identificación:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="cedula_participes" name="cedula_participes"  value="<?php echo $resEdit->cedula_participes; ?>" >
                    				  <input type="hidden" style="height:20px" readonly="readonly" class="form-control" id="id_participes" name="id_participes"  value="<?php echo $resEdit->id_participes; ?>" >
                    				 
                    				  <div id="mensaje_cedula_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
     		         </div>
     		         <div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" > Fecha de Ingreso:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes"  value="<?php echo $resEdit->fecha_ingreso_participes; ?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
     		         </div>
                                	
                		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="ocupacion_participes" class="col-sm-4 control-label" >Cargo:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="ocupacion_participes" name="ocupacion_participes" value="<?php echo $resEdit->ocupacion_participes; ?>" >
                    				  <div id="mensaje_ocupacion_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Tiempo de Aportación:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $tiempo2[0]?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_nacimiento_participes" class="col-sm-4 control-label" >Fecha de Nacimiento:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_nacimiento_participes" name="fecha_nacimiento_participes" value="<?php echo $resEdit->fecha_nacimiento_participes; ?>" >
                    				  <div id="mensaje_fecha_nacimiento_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >N° de Aportes:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $aportes[0]?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Edad:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $tiempo[0]?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Acum.de Aport. Pers. :</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="total" name="total" value="<?php echo number_format($resEdit->total, 2, ",", "."); ?>" >
                    				  <div id="mensaje_total" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="nombre_estado_civil_participes" class="col-sm-4 control-label" >Estado Civil:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="nombre_estado_civil_participes" name="nombre_estado_civil_participes" value="<?php echo $resEdit->nombre_estado_civil_participes; ?>" >
                    				  <div id="mensaje_nombre_estado_civil_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Años de Servicio:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $tiempo2[0]?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Cuenta Individual:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo number_format($resEdit->totalaporte, 2, ",", "."); ?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Credito Activo:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="-" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Estado:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $resEdit->nombre_estado_participes; ?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                		</div>
                 	
        </div>   
        
        
      <div class="row">
      
      <div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="fecha_ingreso_participes" class="col-sm-4 control-label" >Observaciones:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="width:315px; height:50px" readonly="readonly" class="form-control" id="fecha_ingreso_participes" name="fecha_ingreso_participes" value="<?php echo $resEdit->observacion_participes; ?>" >
                    				  <div id="mensaje_fecha_ingreso_participes" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                		</div>
      
      
      
      
      </div>  				
        				         
        			
            
            <?php }}?>
           
            </div>
      	</div>
    </section>
    		
    <!-- seccion para el listado de roles -->


     <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Aportaciones</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="box-body">

           <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#personales" data-toggle="tab">Personales</a></li>
              <li><a href="#patronales" data-toggle="tab">Patronales</a></li>
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
              <div class="tab-pane active" id="personales">
                
					<div class="pull-right" style="margin-right:15px;">
					 					<select name="id_contribucion_tipo" id="id_contribucion_tipo"  onchange="load_personal_cta_individual(1);" class="form-control" >
                                      <option value="0" selected="selected">--TODOS--</option>
    									<?php  foreach($resContriTipo as $res) {?>
    										<option value="<?php echo $res->id_contribucion_tipo; ?>" ><?php echo $res->nombre_contribucion_tipo; ?> </option>
    							        <?php } ?>
    								   </select> 
					</div>
					<div id="load_personales_cta_individual" ></div>	
					<div id="personales_registrados"></div>	
                
              </div>
              
              <div class="tab-pane" id="patronales">
                
                    <div class="pull-right" style="margin-right:15px;">
					<select name="id_contribucion_tipo" id="id_contribucion_tipo"  onchange="load_patronal_cta_individual(1);" class="form-control" >
                                      <option value="0" selected="selected">--TODOS--</option>
    									<?php  foreach($resContriTipo as $res) {?>
    										<option value="<?php echo $res->id_contribucion_tipo; ?>" ><?php echo $res->nombre_contribucion_tipo; ?> </option>
    							        <?php } ?>
    								   </select> 
					</div>
					<div id="load_patronal_cta_individual" ></div>	
					<div id="patronal_registrados"></div>	
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
   <script src="view/Administracion/js/B17.js?0.12" ></script>
   
   	<script type="text/javascript">

        	   $(document).ready( function (){

        		   load_personal_cta_individual(1);
        		   load_patronal_cta_individual(1);
        		   
        		   
	   			});

        	


	   function load_personal_cta_individual(pagina){

		   var id_participes=$("#id_participes").val();
		   var id_contribucion_tipo= $("#id_contribucion_tipo").val();
		  
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_personales_cta_individual").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_personales_cta_individual").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreInformacionParticipes&action=consulta_personal_cta_individual&id_participes='+id_participes+'&id_contribucion_tipo='+id_contribucion_tipo, 
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#personales_registrados").html(x);
	                 $("#load_personales_cta_individual").html("");
	                 $("#tabla_personal_cta_individual").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#personales_registrados").html("Ocurrio un error al cargar la informacion de Aportes Personales..."+estado+"    "+error);
	              }
	            });


		   }

	   function load_patronal_cta_individual(pagina){

		   var id_participes=$("#id_participes").val();
		   var id_contribucion_tipo= $("#id_contribucion_tipo").val();
		  
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_patronal_cta_individual").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_patronal_cta_individual").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=CoreInformacionParticipes&action=consulta_patronal_cta_individual&id_participes='+id_participes+'&id_contribucion_tipo='+id_contribucion_tipo, 
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#patronal_registrados").html(x);
	                 $("#load_patronal_cta_individual").html("");
	                 $("#tabla_patronal_cta_individual").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#patronal_registrados").html("Ocurrio un error al cargar la informacion de Aportes Patronales..."+estado+"    "+error);
	              }
	            });


		   }
	
 </script>
 

   
  </body>

</html>
