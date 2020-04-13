/*** VARIABLES GLOBALES DEL ARCHIVO **/
var OFechaRegistro	= new Date();
var FechaRegistro = OFechaRegistro.getFullYear() +"-"+( OFechaRegistro.getMonth() +1 )+"-"+OFechaRegistro.getDate()
/*** TERMINA VARIABLES GLOBALES DEL ARCHIVO **/

$(document).ready(function(){
	
	console.log("AUI ESATMOS EN LA SUB VENTANA");
	initControles();
	loadDataParticipes();
})

function initControles(){
	
	try {
		var Ofecha = new Date();
		var yearFecha	= Ofecha.getFullYear();
		var mesFecha	= ( Ofecha.getMonth() + 1 );
		var auxmin		= yearFecha - 10;
		var listaMes	= {"1":"ENERO","2":"FEBRERO","3":"MARZO","4":"ABRIL","5":"MAYO","6":"JUNIO","7":"JULIO","8":"AGOSTO","9":"SEPTIEMBRE","10":"OCTUBRE","11":"NOVIEMBRE","12":"DICIEMBRE"}
		
		$("#ddlYear").empty();
		for( var i=auxmin; i<=yearFecha; i++){
			var isselected	= "";
			if( i == yearFecha ){
				var isselected	= "selected";
			}
			$("#ddlYear").append("<option value=\""+i+"\" "+isselected+" >"+i+"</option>");
		}
		
		
		$("#ddlMes").empty();
		for( key in listaMes){
			var isselected	= "";
			if( key == mesFecha ){
				var isselected	= "selected";
				
			}
			$("#ddlMes").append("<option value=\""+key+"\" "+isselected+" >"+listaMes[key]+"</option>");
		}
		
	} catch (e) {
		// TODO: handle exception
		console.log("HAY UN ERROR EN INICIALIZAR CAMPOS DE FECHA");
	}
	
	try {
		
		 $("#imagen_registro").fileinput({			
		 	showPreview: false,
	        showUpload: false,
	        elErrorContainer: '#errorImagen',
	        allowedFileExtensions: ["jpg", "png", "gif"],
	        language: 'esp' 
		 });
		
	} catch (e) {
		// TODO: handle exception
		console.log("ERROR AL IMPLEMENTAR PLUGIN DE FILEUPLOAD");
	}
	
	
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
		url:"index.php?controller=PrincipalBusquedasSocios&action=CargaDatosParticipe",
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
		
		if( x.dataContribucion != undefined && x.dataContribucion != null ){
			
			var rsContribucion	= x.dataContribucion[0];
			
			$("#id_contribucion_categoria").empty();
			$("#id_contribucion_categoria").append("<option value=\"0\"  >--Seleccione--</option>");
			$("#id_contribucion_categoria").append("<option value=\""+rsContribucion.id_contribucion_categoria+"\" selected >"+rsContribucion.nombre_contribucion_categoria+"</option>");
								
		}
		
		if( x.dataTipoAporte != undefined && x.dataTipoAporte != null ){
			
			var rsTipoAporte	= x.dataTipoAporte[0];
			
			$("#ddl_tipo_aporte_registro").empty();
			$("#ddl_tipo_aporte_registro").append("<option value=\"0\"  >--Seleccione--</option>");
			$("#ddl_tipo_aporte_registro").append("<option value=\""+rsTipoAporte.id_contribucion_tipo+"\"  selected >"+rsTipoAporte.nombre_contribucion_tipo+"</option>");
								
		}
		
		if( x.dataContribucionParticipe != undefined && x.dataContribucionParticipe != null ){
					
			var rsContribucionParticipe	= x.dataContribucionParticipe[0];
			
			$("#hdn_id_tipo_aportacion").val( rsContribucionParticipe.id_tipo_aportacion )
			$("#tipo_descuento_registro").val( rsContribucionParticipe.nombre_tipo_aportacion );
			$("#valor_descuento_registro").val( rsContribucionParticipe.valor_contribucion_tipo_participes );
										
		}
		
		if( x.dataTipoIngresos != undefined && x.dataTipoIngresos != null ){
			
			var rsTipoIngresos	=  x.dataTipoIngresos;
			var $ddlTipoIngresos	= $("#ddl_tipo_ingreso");
			$ddlTipoIngresos.empty();
			$ddlTipoIngresos.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipoIngresos,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipoIngresos.append( '<option value="'+value.id_tipo_ingresos_contribucion+'">'+value.nombre_tipo_ingresos_contribucion+'</option>' );
			});
							
		}
			
			
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}

function fnCancelarRegistro(){	
	window.close();
}

function fnCalcularAporte(){
	var lastSueldo	= $("#ultimo_sueldo_registro").val();
	var tipoAporte	= $("#tipo_descuento_registro").val();
	var valorAporte	= $("#valor_descuento_registro").val();
	
	var valorRegistro	= 0.00;
	
	if( tipoAporte == "PORCENTAJE" ){
		valorRegistro	= lastSueldo * valorAporte / 100;
	}else{
		valorRegistro	= valorAporte;
	}
	
	$("#valor_calculado_registro").val( valorRegistro );
	$("#valor_aportar_registro").val( valorRegistro );
	
}

function fnValidaTipoIngreso(){
	
}

function fnIngresarRegistro(){
	
	/** validacion de controles **/
	var inid_participes	= $("#hdnid_participes");
	if( inid_participes.val() == 0 || inid_participes.val() == "" ){
		inid_participes.notify("Participe No Identificado",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var inimagen_registro = $("#imagen_registro");
	if( inimagen_registro[0].files.length == 0){
		inimagen_registro.notify("Ingrese un Imagen",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	var innumero_documento = $("#numero_documento_registro");
	if( innumero_documento.val().length == 0 || innumero_documento.val() == "" ){
		innumero_documento.notify("Ingrese un Imagen",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
		
   
    var params = new FormData();
	
    params.append('id_participes',inid_participes.val());
    params.append('imagen_registro',inimagen_registro[0].files[0]);
    params.append('numero_documento_registro','ajax');
    params.append('id_tipo_aportacion','ajax');
    params.append('id_contribucion_tipo','ajax');
    params.append('ultimo_sueldo_registro','ajax');
    params.append('valor_calculado_registro','ajax');
    params.append('valor_aporte_registro','ajax');
    params.append('observacion_registro','ajax');
    params.append('fecha_contable_registro','ajax');
    params.append('fecha_transaccion_registro','ajax');
    params.append('id_tipo_transaccion','ajax');
    params.append('id_tipo_ingresos_contribucion','ajax');
    params.append('id_bancos_registro','ajax');
    
    params.forEach((value,key) => {
        console.log(key+" "+value)
  	});
	
	$.ajax({
		url:"index.php?controller=PrincipalBusquedasSocios&action=IngresaRegistroAporte",
		dataType:"json",
		type:"POST",
		data: params,
		contentType: false, //importante enviar este parametro en false
        processData: false,  //importante enviar este parametro en false
	}).done( function(x){
		
		console.log(x);
		if( x.dataParticipe != undefined && x.dataParticipe != null ){
			var rsParticipe	= x.dataParticipe[0];
			
			$("#lblIdentificacion").val( rsParticipe.cedula_participes );
			$("#lblNombres").val( rsParticipe.nombre_participes );
			$("#lblApellidos").val( rsParticipe.apellido_participes );
			
			$("#id_entidad_patronal").empty();
			$("#id_entidad_patronal").append("<option value=\"0\"  >--Seleccione--</option>");
			$("#id_entidad_patronal").append("<option value=\""+rsParticipe.id_entidad_patronal+"\" selected >"+rsParticipe.nombre_entidad_patronal+"</option>");
					
		}
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
	
}

