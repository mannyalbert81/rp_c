<!DOCTYPE HTML>
<html lang="es">
      <head>
         
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Capremci</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("view/modulos/links_css.php"); ?>
    <link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    <link rel="stylesheet" href="view/estilos/principal/imagenHover.css">
    <link rel="stylesheet" href="view/credito/html/css/scrolltable.css?0.2">
    
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
        
        .codigo {
        width: 15%;
        font-size:32px;
        text-align:center;
        }
        .observacion {
        width: 75%;
        }
        ul{
        list-style-type:none;
          }
      li{
        list-style-type:none;
        }
        
      div.contenedor-titulo-solicitud{
        width: 100%;
      }  
      
      h3.titulo{ 
		text-align: center;
		margin:0;	
    	}
    	
	.bio-row{
            width: 100%;
            float: left;
            margin-bottom:0px;
            padding: 0 15px;
    }
       
    .bio-row p {
             margin: 0 0 1px;
    }   
        
    .bio-row p span.tab{
    	font-weight: bold;
    	display: inline-block;
    	width: 120px;
    }
    
    .bio-row p span.tab2{
    	font-weight: bold;
    	display: inline-block;
    	width: 130px;
    }
    
    .disabledTab {
        cursor: not-allowed;
    }
    
    li > a[data-toggle="tab"].disabledTab {
        pointer-events: none;
        filter: alpha(opacity=65);
        -webkit-box-shadow: none;
        box-shadow: none;
        opacity: .65;
    } 
    
    #div_pnl_aportes_validacion li{
    padding: 3px 15px;
    }
    
    #div_pnl_aportes_validacion li p{
    margin: 0px;
    }
    
    span.tabulacion{
        display: inline-block;
        width: 150px;
    }
    
    .btn-contenedor{
      width:90px;
      height:100px;
      position:absolute;
      right:0px;
      bottom:0px;
    }
    
    .btn-flotante{
      width:40px;
      height:40px;
      border-radius:100%;
      background:#F44336;
      right:0;
      /*bottom:0;*/
      position:absolute;
      margin-right:16px;
      margin-bottom:16px;
      border:none;
      outline:none;
      color:#FFF;
      font-size:20px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
      transition:.3s;  
    }
    
    span.flotante{
      transition:.5s;  
    }
    
 	</style>
   
  			        
    </head>
    <body id="cuerpo" class="hold-transition skin-blue fixed sidebar-mini"  >

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
        <li class="active">Buscar Participes</li>
      </ol>
    </section>   

    <section class="content">
     <div class="box box-primary">
       <div class="box-header">
          <h3 class="box-title">Ingreso Cr&eacute;ditos </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>          
          </div>
        </div>  
          <div class="box-body">
          	<div class="row hide">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<label for="cedula_participe" class="control-label">Cedula:</label>
                		<div id="mensaje_cedula_participe" class="errores"></div>
                		<div class="input-group">
                			<input type="text" value="<?php echo isset( $cedula_participes ) ? $cedula_participes : 0 ; ?>" data-inputmask="'mask': '9999999999'" class="form-control" id="cedula_participe" name="cedula_participe" placeholder="C.I." readonly>
                			
            				<span class="input-group-btn" id="buscar_participe_boton">
            				
            			    	<!--<button type="button" class="btn btn-primary" id="buscar_participe" name="buscar_participe" onclick="BuscarParticipe()">
        						<i class="glyphicon glyphicon-search"></i>
        						</button>
        						<button type="button" class="btn btn-danger" id="borrar_cedula" name="borrar_cedula" onclick="BorrarCedula()">
        						<i class="glyphicon glyphicon-arrow-left"></i>
        						</button>-->
        					</span>
        					
        				</div>
                 	</div>
             	</div>
           	</div>
           	
           	<!-- DATOS HIDDEN -->
           	<input type="hidden" id="hdn_id_solicitud" value=" <?php echo isset( $id_solicitud ) ? $id_solicitud : 0 ; ?>">
           	<input type="hidden" id="hdn_cedula_participes" value="<?php echo isset( $cedula_participes ) ? $cedula_participes : "" ; ?>">
           	<input type="hidden" id="hdn_id_participes" value="0">
           	<input type="hidden" id="hdn_cedula_garante" value="">
           	<!-- TERMINA DATOS HIDDEN -->
           	           	
           	<div class="row">
  			<div class="col-md-12">
          		<div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    	<!-- AQUI PONER LOS TITULOS DE TABS -->
                      <li><a href="#panel_info" data-toggle="tab" class="disabledTab" >Informacion</a></li>
                      <li><a href="#panel_detalle" data-toggle="tab" class="disabledTab" >Detalle Valores</a></li>
                      <li><a href="#panel_capacidad" data-toggle="tab" class="disabledTab" >Capacidad Pago</a></li>
                      <li><a href="#panel_capacidad_garante" data-toggle="tab" class="disabledTab" >Capacidad Pago Garante</a></li>
                      <li><a href="#panel_cuentas_bancos" data-toggle="tab" class="disabledTab" >Cuentas Bancos</a></li>
                    </ul>
                    <div class="tab-content">
                    
                    	<!-- AQUI COMIENZA PARA PONER CONTENIDOS DE PANELES -->
                    	<!-- PANEL INDEX -->
                    	<div class="active tab-pane" id="panel_index">
                    		<?php include 'view/credito/html/panelindex.php'; ?>
                    	</div>
                    	<div class="tab-pane" id="panel_info">
                    		<!-- AQUI VA LA INFORMACION DE LA SOLICITUD -->
                    		<div id="div_pnl_info_solicitud">
                    			<h3 class="titulo">INFORMACION SOLICITUD</h3>
                    			<div class="box-footer no-padding">
                            	<div class="bio-row"><p><span class="tab">A&Ntilde;O: </span>{ANIO_DESCUENTOS}</p></div>
                            	<div class="bio-row"><p><span class="tab">MES: </span>{MES_DESCUENTOS}</p></div>	
                            	</div>
                    		</div>
                    		<br>
                    		<!-- AQUI VA LA INFORMACION DEL PARTCIPE QUE QUIERE EL CREDITO SOLICITUD -->
							<div id="div_pnl_info_participe">
							</div>	
							
							<!-- AQUI VA INFORMACION DE ERRORES -->
							<div id="div_pnl_respuesta_informacion">
							</div>
							
							<!-- AQUI VA SECCION DE BOTONES QUE MUESTRAN CONTENIDO POR TOGGLE -->
							<div class="row">
							
								<div class="pull-right botones-expandibles">
									
									<button id="btn_mostrar_historial_moras"  data-toggle="collapse" data-target="#div_pnl_historial_moras" title="Historial Moras" class="btn btn-default"  >Historial Moras     		
									<i class="fa   fa-line-chart" aria-hidden="true"></i>						
    								</button>
    																	
									<button id="btn_mostrar_numero_aportes" data-toggle="collapse" title="3 Ultimos Aportes" class="btn  btn-default" data-target="#div_pnl_aportes_validacion" >Aportes     		
									<i class="fa  fa-bar-chart" aria-hidden="true"></i>						
    								</button>
    								
    								<button id="btn_mostrar_creditos_renovacion" class="btn  btn-default" data-toggle="collapse"  data-target="#div_pnl_creditos_renovacion">Renovaci&oacute;n 
    								<span id="lbl_numero_creditos_renovacion" class="badge badge-pill"> 0 </span>
    								</button>
    								
								</div>
								
								<div class="clearfix"></div>
								
								<div id="div_pnl_historial_moras" class="collapse">
                                <!-- Aqui va la informacion de creditos de renovacion -->
                                </div>
                                
                                <div class="clearfix"></div>
                                	
								<div id="div_pnl_aportes_validacion" class="collapse">
                                <!-- Aqui va la informacion de creditos de renovacion -->
                                </div>		
                                
                                <div class="clearfix"></div>					

                                <div id="div_pnl_creditos_renovacion" class="collapse">
                                <!-- Aqui va la informacion de creditos de renovacion -->
                                </div>
                                
                                
							</div>	
														
							<div class="row" style="margin-top: 5px;">
								<div class="box box-default">                                  
                                   <div class="box-body">
                                   		<div class="row">
                                      		<div class="col-xs-12 col-md-12 col-lg-12 ">
                                      			<div class="box-footer no-padding">                                            
                                                    <div class="bio-row"><p><span class="tab2">Monto solicitado</span>: <span id="span_monto_solicitado">0.00</span></p></div>
                                                    <div class="bio-row"><p><span class="tab2">Total a recibir</span>: <span id="span_total_recibir">0.00</span></p></div>                                           
                                                </div>
                                      		</div>
                                  		</div>
                                  	</div>
                          		</div>          		
							</div>
							
							<div id="div_pnl_simulacion_credito">
																
								<div class="row">		    	 
                		    	 	<div class="col-xs-12 col-md-3 col-lg-3 ">
                            		    <div class="form-group">                            		    					  
                                          <label for="ddl_tipo_creditos" class="control-label">Tipo Creditos:</label>
                                          <select class="form-control" id="ddl_tipo_creditos" name="ddl_tipo_creditos">
                                            <option value="0" >--Seleccione--</option>
                                          </select>                                                              
                                        </div>
                            		  </div>
                            		  
                            		  <div class="col-xs-12 col-md-3 col-lg-3 ">
                            		    <div class="form-group">                            		    					  
                                          <label for="ddl_credito_producto" class="control-label">Tipo Producto Creditos:</label>
                                          <select class="form-control" id="ddl_credito_producto" name="ddl_credito_producto">
                                            <option value="0" >--Seleccione--</option>
                                          </select>                                                              
                                        </div>
                            		  </div>
                            		                              		                              		  
                            		  <div class="col-xs-12 col-md-3 col-lg-3 " >
                            		  	<label for="txt_capacidad_pago" class="control-label">Capacidad Pago:</label>
                            		  	<div class="input-group">
                							<input type="text" value="" class="form-control" id="txt_capacidad_pago" readonly>                			
                            				<span class="input-group-btn">
                            					<button type="button" title="Análisis crédito" class="btn bg-olive"  id="btn_capacidad_pago">
                        						 <i class="glyphicon glyphicon-new-window"></i>
                        						</button>
                        					</span>
        					
        								</div>
                            		  </div> 
                            		  
                            		  <div class="col-xs-12 col-md-3 col-lg-3 ">
                            		    <div class="form-group">                            		    					  
                                          <label for="txt_monto_creditos" class="control-label">Monto Credito:</label>
                                          <input type="number" step="10" id="txt_monto_creditos" class="form-control" value="">                                                                                                      
                                        </div>
                            		  </div> 
                            		  
                            		  <div class="col-xs-12 col-md-3 col-lg-3 " >
                            		  	<label for="ddl_numero_cuotas" class="control-label">N&uacute;mero de Cuotas:</label>
                            		  	<div class="input-group">
                							<select id="ddl_numero_cuotas" class="form-control" disabled>
                								<option value="0">--Seleccione--</option>
                							</select>                			
                            				<span class="input-group-btn">
                            					<button type="button" title="Buscar Cuotas" class="btn btn-info"  id="btn_numero_cuotas">
                        						 <i class="glyphicon glyphicon-repeat"></i>
                        						</button>
                        					</span>
        					
        								</div>                            		  	
                            		  </div> 
                            		  
                        		  </div>
                            		  
                        		  <div class="row">                             		  
                            		  <div id="div_capacidad_pago_garante" class="col-xs-12 col-md-3 col-lg-3 " >
                            		  	<label for="txt_capacidad_pago_garante" class="control-label">Capacidad Pago Garante:</label>
                            		  	<div class="input-group">
                							<input type="text" value="" class="form-control" id="txt_capacidad_pago_garante" readonly>                			
                            				<span class="input-group-btn">
                            					<button type="button" title="Análisis crédito" class="btn bg-olive"  id="btn_capacidad_pago_garante">
                        						 <i class="glyphicon glyphicon-new-window"></i>
                        						</button>
                        					</span>
        					
        								</div>                           		  	
                            		  </div>                           		  
                            		                            		  
                        		  </div>
                        		  
                        		  <div class="row">
                        		  	<div class="col-md-offset-6 col-lg-offset-6 col-xs-12 col-md-3 col-md-3 ">
                            		    <label for="txt_monto_creditos" class="control-label">&nbsp;</label>
                                        <div class="input-group">               			
                            				<span class="input-group-btn">
                            					<button type="button" id="btn_generar_simulacion" class="btn btn-primary" >
                                          			<i class="glyphicon glyphicon-expand"></i> SIMULAR</button>  
                        					</span>        					
        								</div>
                            		  </div>
                        		  </div>
                        		  
                        		  <br>
                        		  
                        		  <div id="div_tabla_amortizacion"></div>
                        		  
							</div>	
                    		
                    	</div>
                    	
                    	    <div class="tab-pane" id="panel_detalle">
                    		
                    		<div id="div_pnl_participe_encontrado"></div>
                    		
                    		<div id="div_pnl_participe_aportes"></div>
                    		
                    		<div class="clearfix"></div>
                    		
                    		<div id="div_pnl_participe_creditos"></div>
                    		
                    	</div>
                    	<!-- EMPIEZA PANEL CAPACIDAD PAGO SOLICITANTE -->
                    	<div class="tab-pane" id="panel_capacidad">
                    		<div class="row">
                        		<div class="col-xs-12 col-md-6 col-lg-6 ">
                					<div class="form-group">
                                		<table class="table-bordered" style="width: 100%;">
                                        <tr>
                                        <th>SUELDO LIQUIDO:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_sueldo_liquido" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr>
                                        <th>CUOTA VIGENTE:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_cuota_vigente"  onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr>
                                        <th>FONDOS:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_fondos" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr>
                                        <th>DECIMOS:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_decimos" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr >
                                        <th>RANCHO:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_rancho" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr >
                                        <th>INGRESOS NOTARIZADOS:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_ingresos_notarizados" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        <tr >
                                        <th>TOTAL INGRESO:</th>
                                        <td id="td_total_ingreso" align="right" style="padding-right: 35px;"></td>
                                        </tr>
                                         <tr>
                                        <th bgcolor="#F9E79F">CUOTA MAXIMA:</th>
                                        <td bgcolor="#F9E79F" id="td_cuota_maxima" align="right" style="padding-right: 35px;"></td>
                                        </tr>
                                         <tr >
                                        <th>CUOTA PACTADA:</th>
                                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_cuota_pactada" onkeyup="ObtenerAnalisis()"></td>
                                        </tr>
                                        </table>
                     			</div>
                     			<div class="row">
                    				<div class="col-lg-12 col-md-12 col-xs-12">
                    					<div class="pull-right">
                    						<button type="button" id="btn_enviar_capacidad_pago" class="btn btn-primary" disabled>
                                        		<i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>
                    					</div>
                    				</div>
                				</div>
                 			</div>
                 				<div class="col-xs-12 col-md-6 col-lg-6 ">
                				<div class="form-group">
                    				<div id="credito_aprobado" class="small-box bg-red">
                                		<div class="inner">
                                          <h3 id="h3_credito_aprobado">CREDITO NEGADO</h3>
                                        </div>
                              		</div>
                                    <div id="variacion_rol" class="small-box bg-green">
                                    	<div class="inner">
                                            <h3 id="h3-variacion_rol"></h3>
                            				<h4>VARIACION EN ROL CON NUEVA CUOTA</h4>
                            				<h4 id="h3-variacion_rol_estado"></h4>                                  
                                    	</div>
                                    
                                    </div>
                                    <div id="validacion_rol" class="small-box bg-green">
                                        <div class="inner">
                                          <h3 id="h3-validacion_rol"></h3>
                                          <h4>VALIDACION ROL $100</h4>
                            				<h4 id="h3-validacion_rol_estado"></h4>
                                        </div>
                                    </div>
                                    <div id="considerado_ingresos" class="small-box bg-green">
                                        <div class="inner">
                                          <h3 id="h3-consideracion_rol"></h3>
                            				<h4>CONSIDERANDO INGRESOS ADICIONALES >100</h4>
                            				<h4 id="h3-consideracion_rol_estado"></h4>
                                        </div>
                                        
                                    </div>
                				</div>
                			</div>
                    		</div>
                    	</div>
                    	<!-- EMPIEZA PANEL CAPACIDAD PAGO GARANTE -->
                    	<div class="tab-pane" id="panel_capacidad_garante">
                    		<div class="form-group">
								<div class="row">
                					<table align="center" class="table-bordered" style="width: 50%;">
                        				<tr>
                        					<th>SUELDO LIQUIDO:</th>
                        					<td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad-garante" id="txt_sueldo_liquido_garante"></td>
                    					</tr>
                                        <tr>
                                            <th>CUOTA VIGENTE:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="txt_cuota_vigente_garante" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>FONDOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad-garante" id="txt_fondos_garante" ></td>
                                        </tr>
                                        <tr>
                                            <th>DECIMOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad-garante" id="txt_decimos_garante" ></td>
                                        </tr>
                                        <tr >
                                            <th>RANCHO:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad-garante" id="txt_rancho_garante" ></td>
                                        </tr>
                                        <tr >
                                            <th>INGRESOS NOTARIZADOS:</th>
                                            <td><input style="text-align: right" type="number" step="0.01"  class="form-control suma-capacidad-garante" id="txt_ingresos_notarizados" ></td>
                                        </tr>
                                        <tr >
                                            <th>TOTAL INGRESO:</th>
                                            <td><input style="text-align: right" type="number" class="form-control" id="txt_total_ingresos_garante" readonly></td>
                                        </tr>
                                    </table>
                        	<div class="row">
                                 <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                                	<div class="form-group">
                                		<button type="button" id="btn_enviar_capacidad_pago_garante" class="btn btn-primary">
                                		<i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>
                                	</div>
                                 </div>	
                          	
           					</div>
						</div>
							<br>
							</div>	
                    	</div>
                    	<!-- EMPIEZA PANEL CUENTAS BANCOS -->
        	           	<div class="tab-pane" id="panel_cuentas_bancos">        	           	
        	           		
        	           		<!-- subpanel cuentas -->
                          	<div class="box box-success">
                                <div class="box-header with-border">              
                                </div>
                                <div class="box-body">                                
                                    <!-- emprieza el formulario -->
                                	<form action="">
                                	
                                		<div class="row">
                                			<div class="col-md-3 col-lg-3 col-xs-12">
                                            	<label for="spanel_id_bancos" class="control-label">Bancos:</label>
                                                <select id="spanel_id_bancos"  class="form-control" >
                                                	<option value="0" selected="selected">--Seleccione--</option>            									
                								</select> 
                                            </div>
                                            
                                            <div class="col-md-3 col-lg-3 col-xs-12">
                                              <label for="spanel_id_tipo_cuentas" class="control-label">Tipo Cuentas:</label>
                                              <select id="spanel_id_tipo_cuentas"  class="form-control" >
                                              	<option value="0" selected="selected">--Seleccione--</option>
            								  </select>
                                            </div>
                                                
                                            <div class="col-md-3 col-lg-3 col-xs-12">
                                            	<label for="spanel_numero_cuentas" class="control-label">Número de cuenta</label>
                                                <input type="text" class="form-control" id="spanel_numero_cuentas" value=""  placeholder="Número de cuenta">
                                            </div>
                                             
                                            <div class="col-md-3 col-lg-3 col-xs-12">
                                                 <label for="btn_guardar_cuentas" class="control-label">&nbsp;</label>
                                                 <button type="button" id="btn_guardar_cuentas" class="form-control btn btn-success"> <i class='glyphicon glyphicon-floppy-disk'></i> GUARDAR</button>
                                            </div>
                                      </div>
                                              
                                	</form>
                                                                                                   
                                </div>                               
                              </div>  <!-- /.box-body -->          
          					</div>  <!-- /. subpanel cuentas -->
        	           		
        	           	</div>
        	           	<!-- TERMINA PANEL CUENTAS BANCOS -->
        	           	
                    </div>
                                     	
                 </div>
             </div>
             </div>             
           	
          
       </div> 
    </section>
   </div>
  
 

 <!-- Modal Simulacion Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalSimulacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_simulacion" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Insertar Crédito</h4>
			</div>
			<div class="modal-body">
				
				<div id="info_solicitud"></div>
				<div id="info_participe"></div>	
				
				<div class="form-group">
				
					 
          	 <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
                		<div id="tipo_creditos"></div>
                        <div id="mensaje_tipo_credito" class="errores"></div>
                 	</div>
             	</div>
             	<div id="capacidad_de_pago_participe"></div>
             	<div id="monto_del_credito"></div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
            		<div class="form-group">
            			<div id="select_cuotas"></div>
                 	</div>
             	</div>
          	</div>
          	
          	 <div class="row">
          		<div class="col-xs-6 col-md-3 col-lg-3 ">
          		
             	</div>
             	<div id="capacidad_pago_garante"></div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             	</div>
             	<div class="col-xs-6 col-md-3 col-lg-3 ">
             	</div>
          	</div>
          	
          	<div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div class="form-group">
                  <button type="button" id="Buscar" name="Buscar" class="btn btn-primary" onclick="GetCuotas()"><i class="glyphicon glyphicon-expand"></i> SIMULAR</button>
                </div>
             </div>	    
            </div>
            <div id="tabla_amortizacion"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Inserta Credito -->
 
 <div class="modal fade bs-example" id="myModalInsertar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_insertar" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirmar Crédito</h4>
			</div>
			<div class="modal-body">
				<div class="form-group" align="center">
				 <div id="info_credito_confirmar"></div>
				 <input type="text" class="observacion" maxlength="200" class="form-control" id="observacion_confirmacion" name="observacion_confirmacion" placeholder="Observación Crédito">
				 <br>
          	 <div class="row">
          	 		<div class="form-group" align="center">
          	 		<input type="text" class="codigo" data-inputmask="'mask': '99999'" class="form-control" id="codigo_confirmacion" name="codigo_confirmacion">
                 </div>
              </div>
              <div class="row">
          	 		<div class="form-group" align="center">
          	 		<button type="button" id="registrar_credito" name="registrar_credito" class="btn btn-primary" onclick="RegistrarCredito()"> ACEPTAR</button>
                 </div>
              </div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

<!-- Modal Analisis Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAnalisis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button id="cerrar_analisis" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Capacidad de pago</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
                		<table align="center" class="tablesorter table table-striped table-bordered" style="width: 50%;">
                        <tr>
                        <th>SUELDO LIQUIDO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="sueldo_liquido" name="sueldo_liquido" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr>
                        <th>CUOTA VIGENTE:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="cuota_vigente" name="cuota_vigente" readonly></td>
                        </tr>
                        <tr>
                        <th>FONDOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="fondos" name="fondos" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr>
                        <th>DECIMOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="decimos" name="decimos" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>RANCHO:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="rancho" name="rancho" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>INGRESOS NOTARIZADOS:</th>
                        <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="ingresos_notarizados" name="ingresos_notarizados" onkeyup="SumaIngresos()"></td>
                        </tr>
                        <tr >
                        <th>TOTAL INGRESO:</th>
                        <td id="total_ingreso" align="right" style="padding-right: 35px;"></td>
                        </tr>
                        </table>
                        	<div class="row">
             <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
            	<div id="boton_capacidad_pago" class="form-group"></div>
             </div>	
                          	
           	</div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>
</div>

<!-- Modal Analisis Credito -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalAvaluo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button id="cerrar_avaluo" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Avaluo del Bien</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="row">
            		<table align="center" class="tablesorter table table-striped table-bordered" style="width: 50%;">
                    <tr>
                    <th>AVALUO:</th>
                    <td><input style="text-align: right" type="number" step="0.01"  class="form-control" id="avaluo_bien" name="avaluo_bien" ></td>
                    </tr>
                    <tr>
                    </table>
                	<div class="row">
                     <div class="col-xs-12 col-md-12 col-md-12 " style="margin-top:15px;  text-align: center; ">
                     	<!--  -->
                    	<button type="button" id="enviar_avaluo_bien" name="enviar_avaluo_bien" class="btn btn-primary" onclick="EnviarAvaluoBien()"><i class="glyphicon glyphicon-ok"></i> ACEPTAR</button>
                     </div>	                                  	
                   	</div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>
</div>

<!-- Modal Creditos para renovacion -->
 
 <div class="modal fade bs-example-modal-lg" id="myModalCreditosActivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
	    	<div class="modal-header bg-primary">
	    		<button type="button" id="cerrar_renovar_credito" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Créditos Activos</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				 <div id="info_participe_creditos_activos"></div>				 
            	<div id="tabla_creditos_activos"></div>
				</div>
				<br>
			</div>			
		</div>
	</div>
</div>

 <?php include("view/modulos/footer.php"); ?>	

 <div class="control-sidebar-bg"></div>
 </div>
    
    <?php include("view/modulos/links_js.php"); ?>	

    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="view/bootstrap/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="view/bootstrap/otros/notificaciones/notify.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="view/Credito/js/CreditosParticipes.js?0.66"></script>
    <script src="view/Credito/js/CreditosParicipesAnalisis.js?0.09"></script> 
    <script src="view/Credito/js/CreditosParticipesCuentasBancarias.js?0.06"></script> 
   </body>
</html>   