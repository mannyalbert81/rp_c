<!DOCTYPE HTML>
<html lang="es">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Capremci</title>
<meta
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
	name="viewport">
	<link rel="icon" type="image/png"	href="view/bootstrap/otros/login/images/icons/favicon.ico" />
    <?php include("view/modulos/links_css.php"); ?>
    


<style type="text/css">
.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('view/images/ajax-loader.gif') 50% 50% no-repeat
		rgb(249, 249, 249);
	opacity: .8;
}
/*estilo para una tabla predefinida*/
#tblBusquedaPrincipal.table>tbody>tr>td {
	padding: 4px 12px !important;
}

.form-control {
	border-radius: 5px !important;
}

#tblBusquedaPrincipal .form-control {
	padding: 3px 12px !important;
	height: 25px;
}

.box-footer .widget-user-desc {
	margin-left: 15px !important;
}

/*estilo para una tabla predefinida tbldatosParticipe*/
#tbldatosParticipe td {
	padding: 1px 2px;
}

#tbldatosParticipe .form-control {
	padding: 3px 12px;
	height: 25px;
}

/*estilo para una tabla predefinida tbldatosRegistro*/
#tbldatosRegistro td {
	padding: 1px 2px;
}

#tbldatosRegistro .form-control {
	padding: 3px 12px;
	height: 25px;
}

/*estilo para una tabla predefinida tbldatosAportes*/
#tbldatosAportes td {
	padding: 1px 2px;
}

#tbldatosAportes .form-control {
	padding: 3px 12px;
	height: 25px;
}


/** para cambiar color en borde superior de nav actives **/
.nav-tabs-custom>.nav-tabs>li.active {
	border-top-color: #f39c12;
}

/** para cambiar dimension de btn de file upload **/
#tbldatosRegistro .btn {
	padding: 2px 12px;
}


</style>


</head>
<body class="hold-transition skin-blue fixed sidebar-mini">

	<div class=" no-padding">

		<!-- DATOS DEL CONTROLADOR -->
     <?php

    ?>
  <!-- FIN DATOS CONTROLADOR -->

		<!-- HIDDENs vista -->
		<input type="hidden" value="0" id="hdnid_participes">
		<!-- end HIDDENs vista -->

		<!-- para el loader de procesos -->
		<div id="divLoaderPage"></div>
		<!-- termina loader -->

		<section class="content">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Registro manual de aportes</h3>
				</div>
				<div class="box-body">

					<div class="row">
						<!-- Este div es para mostrar datos del participe -->
						<div class="col-sm-6 col-md-6 col-lg-6">
<!-- 							<div class="panel panel-default"> -->
<!-- 								<div class="panel-heading"> -->
								
									<!-- ESTA TABLA SE LLENA CON PROCESO DE JS -->
									<table id="tbldatosParticipe" class="table">
										<thead>
										</thead>
										<tbody>
											<tr>
												<td><label>Identificacion:</label></td>
												<td><input type="text" class="form-control"
													id="lblIdentificacion"></td>
											</tr>
											<tr>
												<td><label>Nombres:</label></td>
												<td><input type="text" class="form-control" id="lblNombres"></td>
											</tr>
											<tr>
												<td><label>Apellidos:</label></td>
												<td><input type="text" class="form-control"
													id="lblApellidos"></td>
											</tr>
											<tr>
												<td><label>Entidad Patronal:</label></td>
												<td><select class="form-control" id="id_entidad_patronal">
														<option value="">--Seleccione--</option>
												</select></td>
											</tr>
										</tbody>
										<tfoot>
										</tfoot>
									</table>

<!-- 								</div> -->
								<!-- //end panel head -->
<!-- 							</div> -->
						</div>

						<div class="col-sm-6 col-md-6 col-lg-6">
							<!-- ESTA TABLA SE LLENA CON PROCESO DE JS PARA DATOS DE REGISTO -->
							<table id="tbldatosRegistro" class="table">
								<thead>
								</thead>
								<tbody>

									<tr>
										<td><label>Cuenta Individual:</label></td>
										<td><input type="text" class="form-control"
											id="txt_cuenta_individual" value="" readonly></td>
									</tr>
									<tr>
										<td><label>Saldo Capital Cr&eacute;ditos:</label></td>
										<td><input type="text" class="form-control"
											id="txt_saldo_creditos" value="" readonly></td>
									</tr>
									<tr>
										<td><label>Saldo Disponible:</label></td>
										<td><input type="text" class="form-control"
											id="txt_saldo_disponible" readonly></td>
									</tr>
									<tr>
										<td><label>Cantidad Aportes:</label></td>
										<td><input type="text" class="form-control"
											id="txt_cantidad_aportes" readonly></td>
									</tr>
									<tr>
										<td><label>Observaci&oacute;n:</label></td>
										<td><textarea rows="1" cols="" id="observacion_registro"
												class="form-control"></textarea></td>
									</tr>									
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					</div>
					
					<div class="row" style="margin-top: -30px;">
						<div class="pad margin no-print">
                          <div class="callout callout-info" style="margin-bottom: 0!important;">
                            <p><span style="font-weight: 600; line-height: 1.1; color: inherit; font-size: 18px;">
                            <i class="fa fa-info"></i> Note: </span>Los Valores Calculados y Obtenidos Son Referenciales.</p>                                
                          </div>
                        </div>
					</div>

					<div class="row">
						
						<div class="col-xs-3 col-md-3 col-lg-3 ">
							<div class="form-group">
								<label for="ddl_tipo_credito" class="control-label">Tipo
									Credito:</label> 
								<select class="form-control" id="ddl_tipo_credito">
								</select>
							</div>
						</div>
					
						<div class="col-xs-3 col-md-3 col-lg-3 ">
							<div class="form-group">
								<label for="txt_monto_creditos" class="control-label">Monto
									Credito:</label> <input type="number" step="10"
									id="txt_monto_creditos" class="form-control" value="">
							</div>
						</div>

						<div class="col-xs-4 col-md-4 col-lg-4 ">
							<div class="form-group">
								<label for="txt_monto_creditos" class="control-label">&nbsp;</label>
								<div class="text-left">
									<button type="button" class=" btn btn-primary" onclick="fnBuscarInformacion()" >CALCULAR</button>
									<button type="button" class="btn btn-danger" id="btnCancelar"
										value="cancelar" onclick="fnCancelarRegistro()">CANCELAR</button>
								</div>

							</div>
						</div>

					</div>
					
					<div class="row">
						
						<div class="col-xs-3 col-md-3 col-lg-3 ">
							<div class="form-group">
								<label for="ddl_plazo_permitidos" class="control-label">Plazo - Cuotas Credito:</label> 
								<select class="form-control" id="ddl_plazo_permitidos" size="8" multiple data-max-options="1">
								</select>
							</div>
						</div>
						
					</div>

				</div>
			</div>
		</section>

	</div>
      
    <?php include("view/modulos/links_js.php"); ?>
    <script src="view/bootstrap/bower_components/inputmask/dist/jquery.inputmask.bundle.js"></script>
	<script src="view/bootstrap/bower_components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="view/bootstrap/otros/notificaciones/notify.js"></script>
	<!-- date-range-picker -->
	<script src="view/bootstrap/bower_components/moment/min/moment.min.js"></script>
	<script src="view/bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>	
	<!-- js personales -->
	<script type="text/javascript" src="view/principal/js/vtnDatosCreditos.js?0.05"></script>

</body>
</html>

