   <!DOCTYPE HTML>
	<html lang="es">
    <head>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci - Califica Cartera</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
    <?php include("view/modulos/links_css.php"); ?>		
   <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
   <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
       

   
 
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
        <li class="active">Procesos de Calificacion de Cartera</li>
      </ol>
    </section>

   <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Califica Cartera</h3>          
        </div>
        
           
         <div class="box-body">
         
         <form id="form_movimientos_contable" action="<?php echo $helper->url("TributarioGeneraAts","index"); ?>" method="post" enctype="multipart/form-data" class="col-lg-12">
          
          <div class="row">
          
	         
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="anio_procesos" class="control-label">AÑO :</label>
	         		<input type="number" id="anio_procesos" name="anio_procesos" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>" class="form-control">
                    </div>
	         </div>
	         <div class="col-md-3 col-lg-3 col-xs-12">
	         	<div class="form-group">
	         		<label for="mes_procesos" class="control-label">MES :</label>
                    <select name="mes_procesos" id="mes_procesos"   class="form-control" >                    	
                      	<?php for ( $i=1; $i<=count($meses); $i++){ ?>
                      	<?php if( $i == date('n')){ ?>
                      	<option value="<?php echo $i;?>" selected ><?php echo $meses[$i-1]; ?></option>
                      	<?php }else{?>
                      	<option value="<?php echo $i;?>" ><?php echo $meses[$i-1]; ?></option>
                      	<?php }}?>
					 </select> 
	         	</div>
	         </div>
	      </div>
	      
	      <div class="row">
	      	<div class="col-md-offset-4 col-lg-offset-4 col-md-2 col-lg-2 col-xs-12">
	      		<div class="form-group">
	      			<button type="button" id="btnDetalles" name="btnDetalles" class="btn btn-block btn-default" ><i class="fa fa-hourglass-start" aria-hidden="true"></i>  Procesar</button>   		
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
          <h3 class="box-title">Detalle Calificado</h3>        
        </div>
	    <div class="box-body">
        	<div  ></div>
        	<div id="div_detalle_procesos" style="display: none"  class="tab-pane">
                	
					<div class="row">
             			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    		<div id="div_listado_saldo_cartera" class="letrasize11">
    <!--                 		display compact -->
    <!--  table tablesorter table-striped table-bordered nowrap -->
                        		<table id="tblsaldo_cartera" class="table table-bordered display compact">
                        			<thead>
                        				<tr class="info">
                            				<th>Calificaci&oacute;n</th>
                            				<th>2X1</th>
                            				<th>AP</th>
                            				<th>EMERGENTE</th>
                            				<th>ORDINARIO</th>
                            				<th>REFINANCIAMIENTO</th>
                            				<th>MED</th>
                            				<th>HIPOTECARIO</th>
                            				<th>ATH</th>
                            				<th>TOTAL</th>
                        				</tr>                    				
                        			</thead>  
                        			<tbody>
                        				<tr>
                        					<td>A1</td>
                            				<td>1.203.553</td>
                            				<td>62.212</td>
                            				<td>492.415</td>
                            				<td>21.970.481</td>
                            				<td>1.831</td>
                            				<td>1312.507</td>
                            				<td>1.561.265</td>
                            				<td>96.658</td>
                            				<td>25.700.922</td>
                        				</tr>
                        				<tr>
                        					<td>A2</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                        				</tr>
                        				<tr>
                        					<td>A3</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>28.497</td>
                            				<td>-</td>
                            				<td>28.497</td>
                        				</tr>
                        				<tr>
                        					<td>B1</td>
                            				<td>28.906</td>
                            				<td>-</td>
                            				<td>10.950</td>
                            				<td>353.114</td>
                            				<td>-</td>
                            				<td>16.933</td>
                            				<td>51.084</td>
                            				<td>-</td>
                            				<td>460.987</td>
                        				</tr>
                        				<tr>
                        					<td>B2</td>
                            				<td>16.477</td>
                            				<td>-</td>
                            				<td>10.046</td>
                            				<td>124.385</td>
                            				<td>-</td>
                            				<td>20.468</td>
                            				<td>-</td>
                            				<td>17.550</td>
                            				<td>188.927</td>
                        				</tr>
                        				<tr>
                        					<td>C1</td>
                            				<td>17.501</td>
                            				<td>-</td>
                            				<td>2.418</td>
                            				<td>109.328</td>
                            				<td>-</td>
                            				<td>21.764</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>151.011</td>
                        				</tr>
                        				<tr>
                        					<td>C2</td>
                            				<td>12.673</td>
                            				<td>-</td>
                            				<td>5.428</td>
                            				<td>128.536</td>
                            				<td>-</td>
                            				<td>21.169</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>167.806</td>
                        				</tr>
                        				<tr>
                        					<td>D</td>
                            				<td>21.841</td>
                            				<td>27.751</td>
                            				<td>747</td>
                            				<td>75.028</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>-</td>
                            				<td>125.367</td>
                        				</tr>
                        				<tr>
                        					<td>E</td>
                            				<td>580.864</td>
                            				<td>178.228</td>
                            				<td>12.100</td>
                            				<td>423.734</td>
                            				<td>22.410</td>
                            				<td>5.484</td>
                            				<td>51458</td>
                            				<td>-</td>
                            				<td>1274279</td>
                        				</tr>
                        			</tbody>                  			
                        			<tfoot>
                        				<tr class="success">
                        					<th>TOTAL</th>
                            				<th>1.881.815</th>
                            				<th>268.191</th>
                            				<th>534.106</th>
                            				<th>23.184.607</th>
                            				<th>24.241</th>
                            				<th>398.325</th>
                            				<th>1.692.304</th>
                            				<th>114.208</th>
                            				<th>28.097.796</th>
                        				</tr>
                        			</tfoot>
                        		</table>
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
  <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
  <script src="view/Riesgos/js/CalificaCartera.js?0.05"></script>
  
	
 </body>
</html>