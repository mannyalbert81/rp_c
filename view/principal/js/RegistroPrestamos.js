$(document).ready(function(){
	
	loadDataParticipesPrestamos();
})

function loadDataParticipesPrestamos(){
	
	var hdnid_participes_prestamos = $("#hdnid_participes_prestamos");
	
	document.getElementById("hdnid_participes_prestamos").value = window.opener.document.getElementById("hdnid_participes_padre_prestamos").value; 
	
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
			$("#hdnid_creditos").val( rsParticipePrestamos.id_creditos );
					
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

var generar_tabla_amortizacion = function(obj){
	
	var elemento = $(obj);
	var id_creditos	= $("#hdnid_creditos").val();
	var url 	 = "index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos="+id_creditos;
	
	elemento.attr('href',url);
	return true;
}
