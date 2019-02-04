    <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
       <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
       
   
 
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
        <li class="active">Productos</li>
      </ol>
    </section>

   <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Registrar Comprobantes</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        
        <div class="box-body">
      
        <form id="form-comprobante-contable" action="<?php echo $helper->url("ComprobanteContable","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
            <br>	
            
           
	         <?php if (!empty($resultSet)) {  foreach($resultSet as $res) {?>
	         <div class="col-lg-12">
	         <div class="col-lg-1">
	         </div>
	         <div class="col-lg-10">
	         <div class="panel panel-info">
	         <div class="panel-heading">
	         <div class="row">
	         <div class="form-group" style="margin-left: 20px">
			                      <label for="nuevo_comprobante" class="control-label"><h4><i class='glyphicon glyphicon-edit'></i>  Nuevo Comprobante N° <?php echo $res->numero_consecutivos; ?></h4></label>
				                  <input type="hidden" class="form-control" id="id_entidades" name="id_entidades" value="<?php echo $res->id_entidades; ?>">
                                 
             <div class="col-md-3 col-lg-3 col-xs-4" style="margin-top: 5px">
					           <select name="id_tipo_comprobantes" id="id_tipo_comprobantes"  class="form-control" readonly>
                                  <?php foreach($resultTipCom as $res) {?>
										<option value="<?php echo $res->id_tipo_comprobantes; ?>" ><?php echo $res->nombre_tipo_comprobantes; ?> </option>
							        <?php } ?>
								   </select> 
                                  <span class="help-block"></span>	
             
             </div>
             
		     <div class="col-md-3 col-lg-3 col-xs-4" style="margin-top: 5px">
		                          <div class="input-group date" id="datetimePicker">
					              <input type="date" class="form-control" id="fecha_ccomprobantes" name="fecha_ccomprobantes" data-date-format="YYYY-MM-DD" value="" placeholder="Inserte Fecha">
                                  <span class="">
                                  <span class=""></span>
                                  </span>
                                 </div>
                                  
		     </div>
		     </div>
		     </div>
	         </div>
	         </div>
	         </div>
	         
	         <div class="col-lg-1">
	         </div>
	         </div>
	         <?php } }else{ ?>
  			 <?php } ?>
  			 
  			  <div class="col-lg-12">
	         <div class="col-lg-1">
	         </div>
	         <div class="col-lg-10">
	         <div class="panel panel-info">
	         <div class="panel-body">
	         <div class="row">
  		     <div class="col-xs-12 col-md-12">
		     <div class="form-group">
		     
                                  <label for="concepto_ccomprobantes" class="control-label">Concepto de Pago: </label>
                                  <input type="text" class="form-control" id="concepto_ccomprobantes" name="concepto_ccomprobantes" value=""  placeholder="Concepto de Pago">
                                  <span class="help-block"></span>
             </div>
		     </div>
		     </div>
  		     <div class="col-md-12">
					<div class="pull-right">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">
						 <span class="glyphicon glyphicon-search"></span> Buscar Cuentas
						</button>
						
					</div>	
			 </div>		
		    
		    
		     </div>                    
			 </div>
			 </div>
  		     <div class="col-lg-1">
	         </div>
             </div>
         	
  			 
         	 
	         <div class="col-lg-12">
	         <div class="col-lg-12">
	         <div class="panel panel-info">
	         <div class="panel-heading">
	         <h4><i class='glyphicon glyphicon-edit'></i> Buscar Cuentas</h4>
	         </div>
	         <div class="panel-body">
  			 <div class="row">
  			 <div class="form-group" style="margin-top:13px">
             <div class="col-xs-2 col-md-2">
             
                                  <label for="id_plan_cuentas" class="control-label" >#Cuenta: </label>
                                  <input type="text" class="form-control" id="id_plan_cuentas" name="id_plan_cuentas" value=""  placeholder="Search">
                                  <input type="hidden" class="form-control" id="plan_cuentas" name="plan_cuentas" value=""  placeholder="Search">
                                  <span class="help-block"></span>
             </div>
             </div>
		     
		     <div class="form-group">
		     <div class="col-xs-3 col-md-3">                     
                                  <label for="nombre_plan_cuentas" class="control-label">Nombre: </label>
                                  <input type="text" class="form-control" id="nombre_plan_cuentas" name="nombre_plan_cuentas" value=""  placeholder="Search">
                                  <span class="help-block"></span>
             </div>
		     </div>
		     
		     <div class="form-group">
             <div class="col-xs-3 col-md-3">
		                          <label for="descripcion_dcomprobantes" class="control-label">Descripción: </label>
                                  <input type="text" class="form-control" id="descripcion_dcomprobantes" name="descripcion_dcomprobantes" value=""  placeholder="">
                                  <span class="help-block"></span>
             </div>
		     </div>
		     
		     <div class="form-group">
             <div class="col-xs-2 col-md-2">
		                          <label for="debe_dcomprobantes" class="control-label">Debe: </label>
                                  <input type="text" class="form-control" id="debe_dcomprobantes" name="debe_dcomprobantes" value="" onkeypress="return numeros(event)"  placeholder=""  onfocus="validardebe(this);">
                                  <span class="help-block"></span>
             </div>
		     </div>
		     <div class="form-group">
             <div class="col-xs-2 col-md-2">
		                          <label for="haber_dcomprobantes" class="control-label">Haber: </label>
                                  <input type="text" class="form-control" id="haber_dcomprobantes" name="haber_dcomprobantes" value="" onkeypress="return numeros(event)"  placeholder="" onfocus="validardebe(this);">
                                  <span class="help-block"></span>
             </div>
		     </div>
		     </div>
		    <div class="row">
		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center;">
		    <div class="form-group">
                  <button type="submit" id="Agregar" name="Agregar" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i></button>
             </div>
		    </div>
		    </div>
		    </div>
	        </div>
	        </div>
	        </div>
	      <?php if(!empty($resultRes))  {?>
	      <div class="col-lg-12">
	       <div class="col-lg-12">
	       <div class="datagrid3"> 
           <table class="table table-hover ">
           <thead>
           <tr>
                    <th style="font-size:100%;">Cuenta</th>
		    		<th style="font-size:100%;">Nombre de la Cuenta</th>
		    		<th style="font-size:100%;">Descripción</th>
		    		<th style="font-size:100%;">Debe</th>
		    		<th style="font-size:100%;">Haber</th>
		    		<th></th>
	 		</tr>
	        </thead>
	           
                <?php if (!empty($resultRes)) {  foreach($resultRes as $res) {?>
	        	
	        <tbody>
	   		<tr>
	   					<td style="font-size:80%;"> <?php echo $res->codigo_plan_cuentas; ?>  </td>
		                <td style="font-size:80%;" > <?php echo $res->nombre_plan_cuentas; ?>     </td> 
		                <td style="font-size:80%;"> <?php echo $res->observacion_temp_comprobantes; ?>     </td>
		                <td style="font-size:80%;"> <?php echo $res->debe_temp_comprobantes; ?>     </td>  
		                <td style="font-size:80%;"> <?php echo $res->haber_temp_comprobantes; ?>     </td>  
			           	<td>   
			               	<div class="right">
			                    <a href="<?php echo $helper->url("ComprobanteContable","index"); ?>&id_temp_comprobantes=<?php echo $res->id_temp_comprobantes; ?>&arrayGet=<?php echo  $array_get ;?>"><i class="glyphicon glyphicon-trash"></i></a>
			                </div>
			            </td>
	   		</tr>
	        </tbody>	
	        <?php } }else{ ?>
            <?php 
		    }
		    ?>
                <tr>
				<td class='text-right' colspan=1>TOTAL $</td>
				<td colspan=2>
				<input type="hidden" class="form-control" id="valor_ccomprobantes" name="valor_ccomprobantes" value="<?php echo $subtotal_debe?>">
                <input type="text" class="form-control" id="valor_letras" name="valor_letras" value="<?php echo $subtotal_debe ? numtoletras ($subtotal_debe) : ''; ?>" readonly>
        		</td>
				<td class='text-left'><?php echo number_format($subtotal_debe,2);?></td>
				<td class='text-left'><?php echo number_format($subtotal_haber,2);?></td>
			</tr>
   	     </div>
	         </div>
	         </div>
	 	  <?php } else {?>
		  <?php } ?>
		 </br>
		 </br>
		 </br>
	      <?php if(!empty($resultRes)&&($subtotal_debe==$subtotal_haber))  {?>
		   <div class="row">
		   <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; margin-top:20px" > 
           <div class="form-group">
            					  <button type="submit" id="Guardar" name="Guardar" onclick="this.form.action='<?php echo $helper->url("ComprobanteContable","InsertaComprobanteContable"); ?>'" class="btn btn-success" >Guardar</button>
           </div>
           </div>
           </div>          
		  <?php } else {?>
		  <?php } ?> 
	        </form>
         </div>
       </div>
    </section>
  </div>
 
 
 
 
 
 
 
 
 <!-- para modales -->
 
 
 
 	<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar Cuentas</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="q" placeholder="Buscar Plan de Cuentas" onkeyup="load_comprobantes(1)">
						</div>
						<button type="button" class="btn btn-default" onclick="load_comprobantes(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
					  </div>
					</form>
					<div id="loader" ></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->
				  </div>
				  <br>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					
				  </div>
				</div>
			  </div>
			</div>
	
 
 
 
 
 
 	<?php include("view/modulos/footer.php"); ?>	

   <div class="control-sidebar-bg"></div>
 </div>
     
   
    <?php include("view/modulos/links_js.php"); ?>
   	 
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      
	<script>
	$(document).ready(function(){ 	 	

		
		       $( "#id_plan_cuentas" ).autocomplete({
      				source: "<?php echo $helper->url("ComprobanteContable","AutocompleteComprobantesCodigo"); ?>",
      				minLength: 1
    			});

				$("#id_plan_cuentas").focusout(function(){
					
    				$.ajax({
    					url:'<?php echo $helper->url("ComprobanteContable","AutocompleteComprobantesDevuelveNombre"); ?>',
    					type:'POST',
    					dataType:'json',
    					data:{codigo_plan_cuentas:$('#id_plan_cuentas').val()}
    				}).done(function(respuesta){

    					$('#nombre_plan_cuentas').val(respuesta.nombre_plan_cuentas);
    					$('#plan_cuentas').val(respuesta.id_plan_cuentas);
    				
        			}).fail(function(respuesta) {

        				
    					    			    
        			  });
    				 
    				
    			});   
		}); 		
    				
     </script>


		<script>
			       	$(document).ready(function(){ 	
						$("#nombre_plan_cuentas").autocomplete({

							
		      				source: "<?php echo $helper->url("ComprobanteContable","AutocompleteComprobantesNombre"); ?>",
		      				minLength: 1
		    			});
		
						$("#nombre_plan_cuentas").focusout(function(){
		    				$.ajax({
		    					url:'<?php echo $helper->url("ComprobanteContable","AutocompleteComprobantesDevuelveCodigo"); ?>',
		    					type:'POST',
		    					dataType:'json',
		    					data:{nombre_plan_cuentas:$('#nombre_plan_cuentas').val()}
		    				}).done(function(respuesta){
		
		    					$('#id_plan_cuentas').val(respuesta.codigo_plan_cuentas);
		    					$('#plan_cuentas').val(respuesta.id_plan_cuentas);
		    				
		        			});
		    				 
		    				
		    			});   
						
		    		});
		
					
		     </script>
		     
		      
      <script type="text/javascript">
		     function validardebe(field) {
				var nombre_elemento = field.id;
				if(nombre_elemento=="debe_dcomprobantes")
				{
					var debe=$("#haber_dcomprobantes").val('');
					
				}else
				{
					var debe=$("#debe_dcomprobantes").val('');
				}
			  }
	 </script>
		     
		     
      <script type="text/javascript">
                $('#myModal').on('show.bs.modal', function (event) {
                	load_comprobantes(1);
                	  var modal = $(this)
                	  modal.find('.modal-title').text('Plan de Cuentas')
                
                	});
                      
   
      
      		
      		
      		
      		 function load_comprobantes(pagina){

		   var search=$("#q").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_bodegas_inactivos").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#loader").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=ComprobanteContable&action=consulta_plan_cuentas&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $(".outer_div").html(x);
	                 $("#loader").html("");
	                 $("#tabla_plan_cuentas").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $(".outer_div").html("Ocurrio un error al cargar la informacion de Bodegas Inactivos..."+estado+"    "+error);
	              }
	            });

		   }
      		
      		 </script>  
      		

	  
   
	 
	
 </body>
</html>