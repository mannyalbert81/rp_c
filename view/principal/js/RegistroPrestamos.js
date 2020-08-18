

$(document).ready(function(){
	inicializarPagina();
	mostrarSaldosCredito();
	loadDataParticipesPrestamos();	
})

function inicializarPagina(){
	//error SINTAXERROR
	//identificador de credito esta como hdnid_participes_prestamos
	//tomamos datos de la ventana padre
	document.getElementById("hdnid_participes_prestamos").value = window.opener.document.getElementById("hdnid_participes_padre_prestamos").value; 
	
} 

function loadDataParticipesPrestamos(){	

	var hdnid_participes_prestamos = $("#hdnid_participes_prestamos");	
    var params	= {id_creditos:hdnid_participes_prestamos.val()}
	
	$.ajax({
		url:"index.php?controller=PrincipalPrestamosSocios&action=CargaDatosParticipePrestamos",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		if( x.dataParticipePrestamos != undefined && x.dataParticipePrestamos != null ){
			var rsParticipePrestamos	= x.dataParticipePrestamos[0];
			
			$("#lblIdentificacion").val( rsParticipePrestamos.cedula_participes );
			$("#lblNombres").val( rsParticipePrestamos.nombre_participes );
			$("#lblApellidos").val( rsParticipePrestamos.apellido_participes );
			$("#id_entidad_patronal").append("<option value=\""+rsParticipePrestamos.id_entidad_patronal+"\" selected >"+rsParticipePrestamos.nombre_entidad_patronal+"</option>");
			$("#lblNumeroCredito").val( rsParticipePrestamos.numero_creditos );
			$("#lblEstadoCredito").val( rsParticipePrestamos.nombre_estado_creditos );
			$("#lblReceptorSolicitud").val( rsParticipePrestamos.receptor_solicitud_creditos );
			$("#lblFechaConsecion").val( rsParticipePrestamos.fecha_concesion_creditos );
			$("#id_tipo_creditos").append("<option value=\""+rsParticipePrestamos.id_tipo_creditos+"\" selected >"+rsParticipePrestamos.nombre_tipo_creditos+"</option>");
			$("#lblMonto").val( rsParticipePrestamos.monto_otorgado_creditos );
			$("#lblPlazoProducto").val( rsParticipePrestamos.plazo_creditos );
			$("#lblMontoEntregado").val( rsParticipePrestamos.monto_neto_entregado_creditos );
			$("#lblTipoCuota").val( rsParticipePrestamos.cuota_creditos );
			$("#lblPlazoIngresado").val( rsParticipePrestamos.plazo_creditos );
			$("#lblMontoIngresado").val( rsParticipePrestamos.monto_otorgado_creditos );
			$("#lblPlazoMaximo").val( rsParticipePrestamos.plazo_maximo_tipo_creditos );
			$("#lblInteresMensual").val( rsParticipePrestamos.interes_tipo_creditos );
			$("#lblGaranteIdentificacion").val( rsParticipePrestamos.cedula_participes_garantes );
			$("#lblGaranteApellidos").val( rsParticipePrestamos.apellido_participes_garantes );
			$("#lblGaranteNombres").val( rsParticipePrestamos.nombre_participes_garantes );
			
			$("#hdnid_creditos").val( rsParticipePrestamos.id_creditos );
			TablaAmortizacion();
			Transacciones();		
		}
	
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}

function fnRegistro(btn){
	
	var btnObjeto = $(btn);
	var valor = btnObjeto.val();
	
	var vtnX	=  screen.width;
	var vtnY	= screen.height;
	vtnX = (vtnX*7)/10;
	vtnY = (vtnY/4)*3;
	var vtnurl 	= "index.php?controller=PrincipalPrestamosSocios&action=index";
	var vtnoptions 	= "location=0,top=50,left=50,toolbar=0,menubar=0,titlebar=0,resizable=1,width="+vtnX+",height="+vtnY;
	var vtnId	= "vtnAporte";
	
	$("#hdnid_participes_padre_prestamos").val(valor);
	var vtnAporte = window.open(vtnurl,vtnId,vtnoptions);
	
}


function TablaAmortizacion(_page = 1){
	
	var id = $("#hdnid_creditos").val();
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=PrincipalPrestamosSocios&action=TablaAmortizacion",
		type:"POST",
		data:{
			id_creditos: id,
		    page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#tabla_amortizacion").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}
function Transacciones(_page = 1){
	
	var id = $("#hdnid_creditos").val();
	var buscador1 = $("#buscador1").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage1").addClass("loader")},
		url:"index.php?controller=PrincipalPrestamosSocios&action=Transacciones",
		type:"POST",
		data:{
			id_creditos: id,
		    page:_page,search:buscador1,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#transacciones").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage1").removeClass("loader")
		
	})
	
}


var generar_tabla_amortizacion = function(obj){

	var elemento = $(obj);
	var id_creditos	= $("#hdnid_creditos").val();
	var url 	 = "index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos="+id_creditos;
	
	elemento.attr('href',url);
	return true;
}
var generar_pagare = function(obj){
	
	var elemento = $(obj);
	var id_creditos	= $("#hdnid_creditos").val();
	var url 	 = "index.php?controller=PrincipalPrestamosSocios&action=ReportePagare&id_creditos="+id_creditos;
	
	elemento.attr('href',url);
	return true;
}
var generar_recibo = function(obj){
	
	var elemento = $(obj);
	var id_creditos	= $("#hdnid_creditos").val();
	var url 	 = "index.php?controller=PrincipalPrestamosSocios&action=ReporteRecibo&id_creditos="+id_creditos;
	
	elemento.attr('href',url);
	return true;
}
var generar_pagare_cobros = function(obj){
	
	var elemento = $(obj);
	var id_creditos	= $("#hdnid_creditos").val();
	var url 	 = "index.php?controller=TablaAmortizacion&action=ReportePagare&id_creditos="+id_creditos;
	
	elemento.attr('href',url);
	return true;
}

/* dc 2020-08-18 */
var mostrarSaldosCredito	= function(){
	
	var id_creditos	= $("#hdnid_participes_prestamos");
	var panel_saldos= $("#pnl_saldos_creditos");
	var panel_detalles	= $("#div_detalle_saldos");
	
	$.ajax({
		url:"index.php?controller=PrincipalPrestamosSocios&action=ObtenerSaldosCredito",
		beforeSend:function(){  
			panel_detalles.html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
			},
		dataType:"json",
		type:"POST",
		data:{ 'id_creditos': id_creditos.val(), 'fecha_reporte':'2020-08-18'}
	}).done(function(x){
		if( x.estatus != undefined && x.estatus == "OK" ){
			panel_detalles.html( x.html );
			panel_saldos.removeClass("hidden");
		}else{
			panel_detalles.html( x.html );
			panel_saldos.removeClass("hidden");
		}
	}).fail(function(xhr, status, error){
		panel_detalles.html( "" );
		panel_saldos.addClass("hidden");
	})
	
}
/* end dc 2020-08-18 */



