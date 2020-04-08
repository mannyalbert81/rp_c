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
			$("#id_contribucion_categoria").append("<option value=\""+rsContribucion.id_contribucion_categoria+"\"  >"+rsContribucion.nombre_contribucion_categoria+"</option>");
								
		}
				
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}

