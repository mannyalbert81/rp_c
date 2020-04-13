/*** VARIABLES GLOBALES DEL ARCHIVO **/
var OFechaRegistro	= new Date();
var FechaRegistro = OFechaRegistro.getFullYear() +"-"+( OFechaRegistro.getMonth() +1 )+"-"+OFechaRegistro.getDate()
/*** TERMINA VARIABLES GLOBALES DEL ARCHIVO **/

$(document).ready(function(){
	
	console.log("AUI ESATMOS EN LA SUB VENTANA");
	initControles();
	loadBancosLocales();
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

/***
 * @desc funcion para traer datos bancos locales
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadBancosLocales(){
	
	var ddlBancosContribucion	= $("#ddl_banco_registro");	 
	
    var params	= {};
	
	$.ajax({
		url:"index.php?controller=PrincipalBusquedasSocios&action=CargaBancosLocal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		if( x.data != undefined && x.data != "" ){
			
			var rsBancos	= x.data;
			
			ddlBancosContribucion.empty();
			ddlBancosContribucion.append('<option value="0">--Seleccione--</option>' );
			$.each(rsBancos,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				ddlBancosContribucion.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
			});
					
		}
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
		ddlBancosContribucion.empty();
		ddlBancosContribucion.append('<option value="0">--Seleccione--</option>' );
	})
	
}

function fnIngresarRegistro(){
	
	/** validacion de controles **/
	//validacion del participe
	var inid_participes	= $("#hdnid_participes");
	if( inid_participes.val() == 0 || inid_participes.val() == "" ){
		swal({title:"ERROR",text:"Datos Partcipe No validos",icon:"warning"});
		return false;
	}
	//validacion campo imagen obligatorio 
	var inimagen_registro = $("#imagen_registro");
	if( inimagen_registro[0].files.length == 0){
		inimagen_registro.closest("tr").notify("Ingrese un Imagen",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion de numero documento 
	var innumero_documento = $("#numero_documento_registro");
	if( innumero_documento.val().length == 0 || innumero_documento.val() == "" ){
		innumero_documento.closest("tr").notify("Ingrese numero documento",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion de tipo aporte registro
	var intipo_aporte = $("#ddl_tipo_aporte_registro");
	if( intipo_aporte.val() == 0 ){
		intipo_aporte.closest("tr").notify("Seleccione tipo aporte",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion datos descuento
	var incontribucion_tipo = $("#hdn_id_tipo_aportacion");
	if( incontribucion_tipo.val() == 0 ){
		incontribucion_tipo.closest("tr").notify("Datos Descuentos No definidos",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion ultimo sueldo
	var inultimo_sueldo = $("#ultimo_sueldo_registro");
	if( inultimo_sueldo.val().length == 0 || inultimo_sueldo.val() == "" ){
		inultimo_sueldo.closest("tr").notify("Ingrese datos de ultimo sueldo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion valor aportar
	var invalor_calculado 	= $("#valor_calculado_registro");
	var invalor_aportar 	= $("#valor_aportar_registro");
	if( invalor_aportar.val().length == 0 || invalor_aportar.val() == "" ){
		invalor_aportar.closest("tr").notify("Valor aportar no definido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion observacion
	var inobservacion_registro 	= $("#observacion_registro");
	/*if( inobservacion_registro.val().length == 0 || inobservacion_registro.val() == "" ){
		inobservacion_registro.closest("tr").notify("Valor aportar no definido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}*/
	//validacion fecha contable
	var infecha_contable	= $("#fecha_contable_registro");
	if( infecha_contable.val().length == 0 || infecha_contable.val() == "" ){
		infecha_contable.closest("tr").notify("Fecha contable no definida",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion fecha transaccion
	var infecha_transaccion	= $("#fecha_transaccion_registro");
	if( infecha_transaccion.val().length == 0 || infecha_transaccion.val() == "" ){
		infecha_transaccion.closest("tr").notify("Fecha transaccion no definida",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion de tipo aporte registro
	var intipo_transaccion = $("#ddl_tipo_transaccion");
	/*if( intipo_transaccion.val() == 0 ){
		intipo_transaccion.closest("tr").notify("Seleccione tipo transaccion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}*/
	//validacion de tipo aporte registro
	var intipo_ingreso = $("#ddl_tipo_ingreso");
	if( intipo_ingreso.val() == 0 ){
		intipo_ingreso.closest("tr").notify("Seleccione tipo ingreso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion de tipo aporte registro
	var inbanco_registro = $("#ddl_banco_registro");
	if( intipo_ingreso.val() != 2 ){
		//validacion de banco registro
		if( inbanco_registro.val() == 0 ){
			inbanco_registro.closest("tr").notify("Seleccione banco registro",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
	}
	/**datos de formulario cabeza**/	
	inid_entidad_patronal = $("#id_entidad_patronal");
   
    var params = new FormData();
	
    params.append('id_participes',inid_participes.val());
    params.append('imagen_registro',inimagen_registro[0].files[0]);
    params.append('numero_documento_registro',innumero_documento.val());
    params.append('id_tipo_aportacion',intipo_aporte.val());
    params.append('id_contribucion_tipo',incontribucion_tipo.val());
    params.append('ultimo_sueldo_registro',inultimo_sueldo.val());
    params.append('valor_calculado_registro',invalor_calculado.val());
    params.append('valor_aporte_registro',invalor_aportar.val());
    params.append('observacion_registro',inobservacion_registro.val());
    params.append('fecha_contable_registro',infecha_contable.val());
    params.append('fecha_transaccion_registro',infecha_transaccion.val());
    params.append('id_tipo_transaccion',intipo_transaccion.val());
    params.append('id_tipo_ingresos_contribucion',intipo_ingreso.val());
    params.append('id_bancos_registro',inbanco_registro.val()); 
    params.append('id_entidad_patronal',inid_entidad_patronal.val());
        
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
					
		}
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
	
}

