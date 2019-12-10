
var GLOBALfecha = new Date();
var GLOBALyear = GLOBALfecha.getFullYear();
var GLOBALStringFecha = GLOBALfecha.getDate() + "/" + (GLOBALfecha.getMonth() +1) + "/" + GLOBALfecha.getFullYear();

$(document).ready(function(){
	$("#div_entidad").hide();
	
	$('#fecha_desde').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
			  'yearrange': { minyear: 1950,
				  			 maxyear: GLOBALyear	
				  			},
  			  'clearIncomplete': true
			});
	
	$('#fecha_hasta').inputmask('dd/mm/yyyy', 
			{ 'placeholder': 'dd/mm/yyyy', 
		      'yearrange': { minyear: 1950,
			  			 maxyear: GLOBALyear	
			  			},
	  		  'clearIncomplete': true
		});
	
	
	 $("#buscar").click(function(){

			load_comprobantes(1);
			
	});
});

function load_comprobantes(pagina){
	
	var validacionFecha = validarControles();
	console.log(validacionFecha);
	if(!validacionFecha){
		return false;
	}
	
	//iniciar variables
	 var con_id_entidades=$("#id_entidades").val();
	 var con_id_tipo_comprobantes=$("#id_tipo_comprobantes").val();
	 var con_numero_ccomprobantes=$("#numero_ccomprobantes").val();
	 var con_referencia_doc_ccomprobantes=$("#referencia_doc_ccomprobantes").val();
	 var con_fecha_desde=$("#fecha_desde").val();
	 var con_fecha_hasta=$("#fecha_hasta").val();
	 var con_datos_proveedores = $("#datos_proveedor").val();

	  var con_datos={
			  id_entidades:con_id_entidades,
			  id_tipo_comprobantes:con_id_tipo_comprobantes,
			  numero_ccomprobantes:con_numero_ccomprobantes,
			  referencia_doc_ccomprobantes:con_referencia_doc_ccomprobantes,
			  fecha_desde:con_fecha_desde,
			  fecha_hasta:con_fecha_hasta,
			  datos_proveedor:con_datos_proveedores,
			  action:'ajax',
			  page:pagina
			  };


	$("#comprobantes").fadeIn('slow');
	$.ajax({
		url: "index.php?controller=ReporteComprobante&action=index",
        type : "POST",
        async: true,			
		data: con_datos,
		 beforeSend: function(objeto){
		   $("#comprobantes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            
		},
		success:function(data){
		
		     $("#div_comprobantes").html(data);
             $("#comprobantes").html("");
             $("#tabla_comprobantes").tablesorter(); 
			
		}
	})
}

function validarControles(){
	
	console.log("INICIO DE FUNCION validarControles");
	
	var $fecha_desde = $("#fecha_desde"),
		$fecha_hasta = $("#fecha_hasta"),
		$proveedor = $("#datos_proveedores");
		
	/** validacion de fechas **/
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length == 0 || $fecha_hasta.val() == "" ) ){
		$fecha_hasta.val(GLOBALStringFecha);
	}
	
	if( ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") && ($fecha_desde.val().length == 0 || $fecha_desde.val() == "") ){
		$fecha_desde.val(GLOBALStringFecha);
	}
	
	if( ($fecha_desde.val().length > 0 || $fecha_desde.val() != "") && ($fecha_hasta.val().length > 0 || $fecha_hasta.val() != "") ){

		if ($.datepicker.parseDate('dd/mm/yy', $fecha_desde.val()) > $.datepicker.parseDate('dd/mm/yy', $fecha_hasta.val())) {
			$fecha_desde.notify("Fecha no puede ser mayor",{ 'autoHideDelay':1000,position:"buttom-left"});
			console.log("llego aca")
			return false;
		}
	}
	
	return true;
}