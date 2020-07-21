/*** VARIABLES GLOBALES DEL ARCHIVO **/
var OFechaRegistro	= new Date();
var FechaRegistro = OFechaRegistro.getFullYear() +"-"+( ( "0" + ( OFechaRegistro.getMonth() + 1 ) ).slice(-2) ) + "-"+(("0"+(OFechaRegistro.getDate() )).slice(-2));
/*** TERMINA VARIABLES GLOBALES DEL ARCHIVO **/

$(document).ready(function(){
	
	initControles();
	loadTipoCredito();
	loadDataParticipes();
})

function initControles(){
	
	try {
		
		var Ofecha = new Date();
		var yearFecha	= Ofecha.getFullYear();
		var mesFecha	= ( Ofecha.getMonth() + 1 );
		var auxmin		= yearFecha - 10;
		var listaMes	= {"1":"ENERO","2":"FEBRERO","3":"MARZO","4":"ABRIL","5":"MAYO","6":"JUNIO","7":"JULIO","8":"AGOSTO","9":"SEPTIEMBRE","10":"OCTUBRE","11":"NOVIEMBRE","12":"DICIEMBRE"}
				
	} catch (e) {
		// TODO: handle exception
		console.log("HAY UN ERROR EN INICIALIZAR CAMPOS DE FECHA");
	}
	
	
	
}

/***
 * @desc funcion para traer datos bancos locales
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadTipoCredito(){
	
	var ddltipoCredito	= $("#ddl_tipo_credito");	 
	
    var params	= {};
	
	$.ajax({
		url:"index.php?controller=PrincipalBusquedasCreditos&action=obtenerTipoCredito",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		if( x.data != undefined && x.data != "" ){
			
			var rsTipoCredito	= x.data;
			
			ddltipoCredito.empty();
			ddltipoCredito.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipoCredito,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				ddltipoCredito.append( '<option value="'+value.codigo_tipo_creditos+'">'+value.nombre_tipo_creditos+'</option>' );
			});
					
		}
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
		ddltipoCredito.empty();
		ddltipoCredito.append('<option value="0">--Seleccione--</option>' );
	})
	
}

/***
 * @desc funcion para traer datos del participe selecionado
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadDataParticipes(){
	
	var hdnid_participes = $("#hdnid_participes");
	
	document.getElementById("hdnid_participes").value = window.opener.document.getElementById("hdnid_participes_padre").value; 
	
    var params	= {id_participes:hdnid_participes.val()}
	
	$.ajax({
		url:"index.php?controller=PrincipalBusquedasCreditos&action=CargaDatosParticipe",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		if( x.dataParticipe != undefined && x.dataParticipe != null ){
			var rsParticipe	= x.dataParticipe[0];
			
			$("#lblIdentificacion").val( rsParticipe.cedula_participes );
			$("#lblNombres").val( rsParticipe.nombre_participes );
			$("#lblApellidos").val( rsParticipe.apellido_participes );
			
			$("#id_entidad_patronal").empty();
			$("#id_entidad_patronal").append("<option value=\"0\"  >--Seleccione--</option>");
			$("#id_entidad_patronal").append("<option value=\""+rsParticipe.id_entidad_patronal+"\" selected >"+rsParticipe.nombre_entidad_patronal+"</option>");
					
		}
		
		if( x.cuentaIndividual != undefined && x.cuentaIndividual != null ){
			
			var rsCuentaIndividual	= x.cuentaIndividual[0];
			
			$("#txt_cuenta_individual").val( roundNumber( rsCuentaIndividual.total,2) ); 
		}
		
		if( x.saldoCreditos != undefined && x.saldoCreditos != null ){
			
			var rsSaldoCreditos	= x.saldoCreditos[0];			
			$("#txt_saldo_creditos").val( roundNumber( rsSaldoCreditos.total,2) );
			
		}
		
		if( $("#txt_cuenta_individual").val() != "" && $("#txt_saldo_creditos").val() != "" ){
			
			var saldoDisponible = roundNumber( ( $("#txt_cuenta_individual").val() - $("#txt_saldo_creditos").val() ),2 );
			$("#txt_saldo_disponible").val( saldoDisponible );
		}
		
		if( x.numeroAportes != undefined  ){
					
			var numeroAportes	= x.numeroAportes;
			
			$("#txt_cantidad_aportes").val( numeroAportes );		
										
		}		
		
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}

function roundNumber(value, decimals) {
	  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function pad(input,length,padding){
	var str = input + "";
	return ( length <= str.length ) ? str : pad( padding + str, length,padding);
}

function fnCancelarRegistro(){	
	window.close();
}

var redondeo_valores	= function( a ){
	//esta funcion pasa como parametro un elemto si el elemento existe cambia sus valores
	var element	= $(a);
	var valor_element	= 0;
	var residuo	= 0;
	//valido que exista el elemento
	if( element.length ){
		
		valor_element	= element.val();
		residuo	= valor_element%10;		
		valor_element	= valor_element - residuo;
		
		if( valor_element != 0 ){
			element.val( valor_element );
		}		
	}
	
}

var fnBuscarInformacion	= function(){
	
	var tipo_credito	= $("#ddl_tipo_credito");
	var monto_creditos = $("#txt_monto_creditos");
	redondeo_valores(monto_creditos); //hago redondeo de valores
	var saldo_disponible	= $("#txt_saldo_disponible");
	
	if( tipo_credito.val() == "0" ){
		tipo_credito.notify("Seleccione Tipo Credito",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	} 
	
	if( monto_creditos.val() == "" ){
		monto_creditos.notify("Ingrese un monto",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( tipo_credito.val() == "EME" && monto_creditos.val() > 7000 ){		
		monto_creditos.notify("Monto supera permitido Tipo Credito",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if( parseFloat( monto_creditos.val() ) > parseFloat( saldo_disponible.val() ) ){
		monto_creditos.notify("Monto supera saldo disponible",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var params = { 'id_participes': $("#hdnid_participes").val(), 
			'tipo_creditos': tipo_credito.val(), 
			'monto_credito': monto_creditos.val() };
	
	$.ajax({
		url: "index.php?controller=PrincipalBusquedasCreditos&action=buscarPlazoCuotas",
		dataType: "json",
		type: "POST",
		data: params
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{				
			if( x.cuotas != undefined )
			{			
				var ddlPlazoCuota	= $("#ddl_plazo_permitidos");
				var cuotas = x.cuotas;
				ddlPlazoCuota.empty();
				ddlPlazoCuota.attr("disabled",false);
				var strplazo = "";
				var valCuota = 0;
				$.each( cuotas, function(i,value){
					strplazo = pad( value.plazo, 2, '0'); //para obtener mismo logitud de caracteres
					valCuota = parseFloat( value.valor ).toFixed(2); //para tener valores redondeados
					ddlPlazoCuota.append('<option value="'+value.plazo+'"> plazo:'+strplazo+'  | cuota: '+valCuota+'</option>');
				})
			}
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}








