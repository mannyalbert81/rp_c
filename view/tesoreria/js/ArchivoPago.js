var listaCuentas = []; // variable que permite el almacenamiento de datos de distribucion valores
$(document).ready(function(){
		
	init();	
	loadBancosLocal();
	loadTipoArchivo();
	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	//$("#genera_transferencia").attr("disabled",true);
	$('#fecha_proceso').inputmask("99/99/9999", {placeholder: 'DD/MM/YYYY',clearIncomplete:true });
	
}


/***
 * @desc funcion para traer la chequera de la entidad
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadBancosLocal(){	
	
	var $ddlChequera = $("#id_bancos_local");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaBancosLocal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlChequera.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
			})
		}
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer el tipo archivo 
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadTipoArchivo(){	
	
	var $ddlTipoArchivo = $("#id_tipo_archivo_pago");
	params = {};
	$ddlTipoArchivo.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaTipoArchivo",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsTipoArchivo = x.data;
			$ddlTipoArchivo.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipoArchivo,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipoArchivo.append( '<option value="'+value.id_tipo_pago_archivo+'">'+value.nombre_tipo_pago_archivo+'</option>' );
			})
		}
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function FormularioPost(url,target,params){
	 
	 var form = document.createElement("form");
	 form.setAttribute("id", target);
     form.setAttribute("method", "post");
     form.setAttribute("action", url);
     form.setAttribute("target", target);

     for (var i in params) {
         if (params.hasOwnProperty(i)) {
             var input = document.createElement('input');
             input.type = 'hidden';
             input.name = i;
             input.value = params[i];
             form.appendChild(input);
         }
     }
     
     document.body.appendChild(form);
     
     form.submit();
     
     document.body.removeChild(form);
}
 
$("#buscar_archivo_pago").on("click",function(event){
	
	/* aqui va a buscar los datos de acuerdo al tipo de archivo */
	//validacion campo tipo archivo
	var in_id_tipo_archivo_pago = $("#id_tipo_archivo_pago");
	if( in_id_tipo_archivo_pago.val() == "0"){
		in_id_tipo_archivo_pago.notify("Selecione Tipo Archivo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion campo fecha
	var in_fecha_proceso = $("#fecha_proceso");
	if( in_fecha_proceso.val() == "" ){
		in_fecha_proceso.notify("Ingrese una Fecha",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	//validacion campo id bancos
	var in_id_bancos_local = $("#id_bancos_local");
	/*if( in_id_bancos_local.val() == "0"){
		in_id_bancos_local.notify("Selecione Banco",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}*/
	
	var param = {id_tipo_archivo_pago:in_id_tipo_archivo_pago.val(),fecha_proceso:in_fecha_proceso.val(),id_bancos:in_id_bancos_local.val()};
	
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=buscarDatosArchivo",
		type:"POST",
		dataType:"json",
		data:param
	}).done(function(x){
		console.log(x);
		if( x.estatus != undefined ){
			$("#tblArchivoPago").empty();
			if( x.estatus == "OK"){				
				$("#div_resultados_archivo_pago").removeClass("hidden");
				$("#tblArchivoPago").append(x.tabla);
			}
			
			if( x.estatus == "ERROR" ){
				$("#div_resultados_archivo_pago").addClass("hidden");
				swal({title:"Datos",text:x.mensaje,icon:x.icon});
			}
		}
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
		swal({title:"ERROR",text:"problemas con la conexion! llamar al Administrador Sistema",icon:"error"})
	})
	
})




$("#btn_distribucion_aceptar").on("click",function(event){
	
	
	let divPadre = $("#lista_distribucion_transferencia");	
	let filas = divPadre.find("table tbody > tr ");	
	let error = true;
	let data = [];
	
	divPadre.find("input:text[name='mod_dis_referencia']").each(function(index,value){		
		if($(this).val() == ''){
			divPadre.find("table").notify("Ingrese un referencia",{ position:"top center"});
			error = false;
			return;
		}
	})
	
	if(!error){	return false;}
	
	filas.each(function(){
			
			var _id_distribucion	= $(this).attr("id").split('_')[1],
				_desc_distribucion	= $(this).find("input:text[name='mod_dis_referencia']").val(),
				_id_plan_cuentas 	= $(this).find("input:hidden[name='mod_dis_id_plan_cuentas']").val(),
				_tipo_pago		 	= $(this).find("select[name='mod_tipo_pago']").val();
	
			item = {};
		
			if(!isNaN(_id_distribucion)){
			
		        item ["id_distribucion"] 		= _id_distribucion;
		        item ["referencia_distribucion"]= _desc_distribucion;
		        item ['id_plan_cuentas'] 		= _id_plan_cuentas;
		        item ['tipo_pago'] 				= _tipo_pago;
		        
		        data.push(item);
			}else{			
				error = false; return false;
			}
			
			if(isNaN(_id_plan_cuentas) || _id_plan_cuentas.length == 0 ){
				divPadre.find("table").notify("Cuentas Faltantes",{ position:"top center"});
				error = false; return false;
			}
					
		})
	var arrayToCount = data;
	var _debito=0,_credito=0;
	for (i = 0; i < arrayToCount.length; i++){
		if(arrayToCount[i].tipo_pago == "debito"){
			_debito += 1;
	    }
		if(arrayToCount[i].tipo_pago == "credito"){
			_credito += 1;
	    }
	}
	if(_debito != _credito){
		divPadre.find("table").notify("error en distribucion",{ position:"top center"});
		error = false;
		_debito=0,_credito=0;
	}
	if(!error){	return false;}	
	listaCuentas = JSON.stringify(data);
	
	$("#genera_transferencia").attr("disabled",false);
})



