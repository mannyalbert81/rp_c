<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    
    
   <?php include("view/modulos/links_css.php"); ?>
   
  </head>

  <body class="hold-transition skin-blue fixed sidebar-mini">   
  <?php
        
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        $fecha_solicitud = date("Y-m-d");
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
            <li class="active">Solicitud</li>
          </ol>
        </section>
        
            		
    		<!-- seccion para ver division de vista -->
          <section class="content">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> Solicitudes </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                
              </div>
            </div>
            
            <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Solicitud Pendiente</a></li>
              <li><a href="#tab_2" data-toggle="tab">Solicitud Entregada</a></li>
              <li><a href="#tab_3" data-toggle="tab">Solicitud Rechazada</a></li>
              
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-th"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud" name="buscador_solicitud" onkeyup="carga_solicitud(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud" ></div>	
        				<div id="resultados_solicitud"></div>
                        </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud_entregada" name="buscador_solicitud_entregada" onkeyup="carga_solicitud_entregada(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud_entregada" ></div>	
        				<div id="resultados_solicitud_entregada"></div>
                        </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <div class="box-body">
                          <div class="pull-right" style="margin-right:11px;">
        					<input type="text" value="" class="form-control" id="buscador_solicitud_rechazada" name="buscador_solicitud_rechazada" onkeyup="carga_solicitud_rechazada(1)" placeholder="search.."/>
        				</div>
        				<div id="load_solicitud_rechazada" ></div>	
        				<div id="resultados_solicitud_rechazada"></div>
                        </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
            
            <div class="box-body">
            	<div class="ibox-content">
                      	
                  <div class="x_content">
                  
                  	
                  	
    		</section>
    		
            
            
  		</div>
  
  
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
    
    
   <?php include("view/modulos/links_js.php"); ?>
    	
  </body>
</html>

<!-- script pagina anterior -->
<script type="text/javascript">
     
        	   $(document).ready( function (){
        		   //pone_espera();
        		   
	   			});

        	   function pone_espera(){

        		   $.blockUI({ 
        				message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
        				css: { 
        		            border: 'none', 
        		            padding: '15px', 
        		            backgroundColor: '#000', 
        		            '-webkit-border-radius': '10px', 
        		            '-moz-border-radius': '10px', 
        		            opacity: .5, 
        		            color: '#fff',
        		           
        	        		}
        	    });
            	
		        setTimeout($.unblockUI, 3000); 
		        
        	   }

 </script>
 <<script type="text/javascript">
$(document).ready(function(){
	carga_solicitud();
	carga_solicitud_entregada()
	carga_solicitud_rechazada()
	
});

function carga_solicitud(pagina){

    var search=$("#buscador_solicitud").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud").html(x);
               $("#load_solicitud").html("");
               $("#tabla_salidas").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_entregada(pagina){

    var search=$("#buscador_solicitud_entregada").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_entregada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_entregada").html(x);
               $("#load_solicitud_entregada").html("");
               $("#tabla_salidas_entregada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_rechazada(pagina){

    var search=$("#buscador_solicitud_rechazada").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_rechazada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_rechazada").html(x);
               $("#load_solicitud_rechazada").html("");
               $("#tabla_salidas_rechazada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

 	

 	function load_productos_solicitud(pagina){

 	   var search=$("#buscador_productos").val();
        var con_datos={
 				  action:'ajax',
 				  page:pagina,
 				  buscador:search
 				  };
      
      $.ajax({
                beforeSend: function(objeto){
                  $("#load_productos").fadeIn('slow');
                  $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
                },
                url: 'index.php?controller=SolicitudCabeza&action=ajax_trae_productos',
                type: 'POST',
                data: con_datos,
                success: function(x){
                  $("#productos_inventario").html(x);
                  $("#load_productos").html("");
                  $("#tabla_productos").tablesorter(); 
                  
                },
               error: function(jqXHR,estado,error){
                 $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
               }
             });


 	   }

 	
 	function agregar_producto (id)
	{
		var cantidad=document.getElementById('cantidad_'+id).value;
		//Inicia validacion
		if (isNaN(cantidad))
		{
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
		}
		
		$.ajax({
            type: "POST",
            url: 'index.php?controller=SolicitudCabeza&action=insertar_producto',
            data: "id_productos="+id+"&cantidad="+cantidad,
        	 beforeSend: function(objeto){
        		/*$("#resultados").html("Mensaje: Cargando...");*/
        	  },
            success: function(datos){
        		$("#resultados").html(datos);
        	}
		});
	}

 	function eliminar_producto (id)
	{
		
		$.ajax({
            type: "POST",
            url: 'index.php?controller=SolicitudCabeza&action=eliminar_producto',
            data: "id_solicitud="+id,
        	 beforeSend: function(objeto){
        		$("#resultados").html("Mensaje: Cargando...");
        	  },
            success: function(datos){
        		$("#resultados").html(datos);
        	}
		});
	}

 	function load_temp_solicitud(pagina){
  	  
         var con_datos={
  				  page:pagina
  				  };
       
       $.ajax({
                 beforeSend: function(objeto){
                   
                 },
                 url: 'index.php?controller=SolicitudCabeza&action=trae_temporal',
                 type: 'POST',
                 data: con_datos,
                 success: function(x){
                   $("#resultados").html(x);
                   $("#tabla_temporal").tablesorter(); 
                   
                 },
                error: function(jqXHR,estado,error){
                  $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
                }
              });


  	   }

 	

 	
 	
</script>
       
       
      
 




