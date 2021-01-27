<!DOCTYPE html>
<html lang="en">
  <head>
  
  	<title>Capremci</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
   	<?php include("view/modulos/links_css.php"); ?>
   	<link rel="stylesheet" href="view/bootstrap/otros/css/tablaFixed.css?1"/> 
    
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
        .letrasize11{
        font-size: 11px;
       }    
       
       
    </style>
    
    
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
            
            <?php //echo count($resultRep); print_r($resultRep); ?>
            
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
                    				<label for="input001" class="col-sm-4 control-label" >Tiempo de Aportación:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input001" name="input001" value="<?php echo $tiempo2[0]?>" >
                    				  <div id="mensaje_input001" class="errores"></div>
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
                    				<label for="input002" class="col-sm-4 control-label" >N° de Aportes:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input002" name="input002" value="<?php echo $aportes[0]?>" >
                    				  <div id="mensaje_input002" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input003" class="col-sm-4 control-label" >Edad:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input003" name="input003" value="<?php echo $tiempo[0]?>" >
                    				  <div id="mensaje_input003" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input004" class="col-sm-4 control-label" >Acum.de Aport. Pers. :</label>
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
                    				<label for="input005" class="col-sm-4 control-label" >Años de Servicio:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input005" name="input005" value="<?php echo $tiempo2[0]?>" >
                    				  <div id="mensaje_input005" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input006" class="col-sm-4 control-label" >Cuenta Individual:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input006" name="input006" value="<?php echo number_format($resEdit->totalaporte, 2, ",", "."); ?>" >
                    				  <div id="mensaje_input006" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input007" class="col-sm-4 control-label" >Credito Activo:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input007" name="input007" value="-" >
                    				  <div id="mensaje_input007" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                 		</div>
                 		<div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input008" class="col-sm-4 control-label" >Estado:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="height:20px" readonly="readonly" class="form-control" id="input008" name="input008" value="<?php echo $resEdit->nombre_estado_participes; ?>" >
                    				  <div id="mensaje_input008" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                		</div>
                 	
        </div>   
        
        
      <div class="row">
      
      <div class="col-lg-6 col-md-6 col-xs-12">
                			<div class="form-group "> 
                    			 <div class="form-group-sm">
                    				<label for="input009" class="col-sm-4 control-label" >Observaciones:</label>
                    				<div class="col-sm-8">
                    				  <input type="text" style="width:315px; height:50px" readonly="readonly" class="form-control" id="input009" name="input009" value="<?php echo $resEdit->observacion_participes; ?>" >
                    				  <div id="input009" class="errores"></div>
                    				</div>
                    			 </div>        			 
                			</div>
                		</div>
      
      
      
      
      </div>  				
        				         
        			
            
            <?php }}?>
           
            </div>
      	</div>
    </section>
    		

            
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
              <li class="active"><a href="#personales_dt" data-toggle="tab">Personales</a></li>
              <li ><a href="#patronales_dt" data-toggle="tab">Patronales</a></li> 
            </ul>
            
            <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="tab-content">
            <br>
 			 <div class="tab-pane active" id="personales_dt">
                              	
              		<div id="pnl_div_aportes_personales" class="letrasize11">
                		<table id="tbl_personales" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="danger" >
                        	    	<th >A&ntilde;o</th>
                        			<th >Enero</th>
                        			<th >Febrero</th>
                        			<th >marzo</th>
                        			<th >Abril</th>
                        			<th >Mayo</th>
                        			<th >Junio</th>
                        			<th >Julio</th>
                        			<th >Agosto</th>
                        			<th >Septiembre</th>   
                        			<th >Octubre</th>
                        			<th >Noviembre</th>
                        			<th >Diciembre</th>    
                        			<th >Acumulado</th>                  			
                        		</tr>                        		
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="14"></td> 
                    			</tr>
                			</tfoot>
                        </table>            	
                	</div>
          		
            	
          		
		       
              </div>
              
              <div class="tab-pane" id="patronales_dt">
              
              	<div class="box-body no-padding">
          		
              		<div id="pnl_div_aportes_patronales" class="letrasize11">
                		<table id="tbl_patronales" class="table table-striped table-bordered" > <!--   -->
                        	<thead >
                        	    <tr class="danger" >
                        	    	<th >A&ntilde;o</th>
                        			<th >Enero</th>
                        			<th >Febrero</th>
                        			<th >marzo</th>
                        			<th >Abril</th>
                        			<th >Mayo</th>
                        			<th >Junio</th>
                        			<th >Julio</th>
                        			<th >Agosto</th>
                        			<th >Septiembre</th>   
                        			<th >Octubre</th>
                        			<th >Noviembre</th>
                        			<th >Diciembre</th>    
                        			<th >Acumulado</th>                  			
                        		</tr>
                        	</thead>        
                        	<tfoot>
                        		<tr>
                        			<td colspan="10"></td>
                    			</tr>
                			</tfoot>
                        </table>            	
                	</div>          		
            	
          		</div>
                
        			
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
   
   <script src="view/Core/js/ConsultaGeneral.js?2.32"></script> 

 

   
  </body>

</html>
