var listaCuentas = [];	//variable que permite el almacenamiento de datos de distribucion valores
$(document).ready(function(){
		
	init();
	devuelveconsecutivos();
	loadBancosLocal();
	loadTipoArchivo();
	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	$("#genera_transferencia").attr("disabled",true);
	
	var fechaServidor = $("#fechasistema").text();
	
	$("#chk_pago_parcial_transferencias").on( 'change', function() {
		fnValidaPagoParcial(this);
	});	
		
	/*$("#fecha_transferencia").inputmask("datetime",{
	     mask: "y-2-1", 
	     placeholder: "yyyy-mm-dd", 
	     leapday: "-02-29", 
	     separator: "-", 
	     alias: "dd-mm-yyyy",
	     clearIncomplete: true,
		 rightAlign: true,		 
		 yearrange: {
				minyear: 1950,
				maxyear: 2019
			},
		oncomplete:function(e){
			if( (new Date($(this).val()).getTime() != new Date(fechaServidor).getTime()))
		    {
				$(this).notify("Fecha no puede ser Mayor",{ position:"buttom left", autoHideDelay: 2000});
				$(this).val('')
		    }
		}
	});*/
	
	
}

/*******************************************************************************
 * dc 2019-07-08
 */
function devuelveconsecutivos(){
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=DevuelveConsecutivos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		console.log(x);
		$("#numero_pago").val(x.pagos.numero);
	}).fail(function(xhr,status,error){
		$("#numero_pago").val('');
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
		url:"index.php?controller=Transferencias&action=CargaBancosLocal",
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
 * @desc funcion para traer bancos a transferir
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadBancosbeneficiario(){	
	
	var $ddlChequera = $("#id_bancos_local");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=Transferencias&action=CargaBancosLocal",
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



/*******************************************************************************
 * funcion para poner mayusculas
 * 
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});


$("#distribucion_transferencia").on("click",function(event){
	
	//aqui se genera la accion para mostrar el modal
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	let $modal = $("#mod_distribucion_pago"),
		$divResultados = null,
		$descripcion = null;
		$tablaDistribucion = null;
	
	$descripcion = $("#descripcion_pago");
	if($descripcion.val() == ""){
		$descripcion.notify("Ingrese una descripción",{ position:"top center"});
		return false;
	}
	
	$id_bancos_local = $("#id_bancos_local");
	if( $id_bancos_local.val() == undefined ||  $id_bancos_local.val() == null || $id_bancos_local.val() == "0"){
		$id_bancos_local.notify("Seleccione Banco Local",{ position:"top center"});
		swal({"text":"Banco Local no seleecionado. !favor Revisar",icon:"warning"});
		return false;
	}
	
	$id_bancos_tranferir = $("#id_bancos_transferir");
	if( $id_bancos_tranferir.val() == undefined ||  $id_bancos_tranferir.val() == null || $id_bancos_tranferir.val() == ""){
		$id_bancos_tranferir.notify("Ingrese Banco a transferir",{ position:"top center"});
		swal({"text":"banco a transferir no definido. !favor Revisar",icon:"warning"});
		return false;
	}
	
	//$("#mod_banco_transferir")[0].selectedIndex = 0; // quede selecionado el select
	
	$divResultados = $modal.find("#lista_distribucion_transferencia");		
	$divResultados.html('');
	
	$divResultados.html(graficaTablaDistribucion());
	
	$modal.find("#mod_identificacion_proveedor").val($("#identificacion_proveedor").val());
	$modal.find("#mod_total_cuentas_pagar").val($("#total_cuentas_pagar").val());
	$modal.find("#mod_nombre_proveedor").val($("#nombre_proveedor").val());
	$modal.find("#mod_banco_transferir").val($("#nombre_cuenta_banco").val());
	$modal.find("input:text[name='mod_dis_referencia']").val($("#descripcion_pago").val());
	$modal.find("#mod_descripcion_transferencia").val( $("#descripcion_pago").val());
	
	if( $("#id_creditos").val() == undefined ||  $("#id_creditos").val() == null ){
		$("#divmodcreditos").addClass("hidden");
	}else{
		
		$modal.find("#mod_numero_creditos").val( $("#numero_creditos").val());
		$modal.find("#mod_tipo_creditos").val( $("#tipo_creditos").val());
	}
	
	getCuentaBancoPago(); //funcion para graficar la cuenta del banco local selecicionado
	getCuentaBancoPagoProveedor();
	
	$modal.modal("show");
	
	$("#mod_banco_transferir").empty().append( $("#id_bancos_transferir option:selected").clone() ); //pasar datos de select es solo informativo
		
	
})

/**
 * grafica tabla distribucion
 * retorna objeto tabla
 * @returns
 */
function graficaTablaDistribucion(){
	
	var $tablaDistribucion = $('<table border="1" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example"></table>');	
	var $filaHead="<tr>" +
	"<th>#</th>" +
	"<th>Referencia</th>" +
	"<th>Codigo Cuenta</th>" +
	"<th>Nombre</th>" +
	"<th>Tipo</th>" +
	"<th>valor</th>"
	"</tr>";

	$tablaDistribucion.attr("id","tbl_distribucion2");
	$tablaDistribucion.append('<thead></thead> <tbody></tbody');
	$tablaDistribucion.find("> thead").append($filaHead);

	var $filaBody = "";
	for(var i=0; i<2; i++){
	
		$filaBody +="<tr id=\"tr_"+(i+1)+"\">"+
			"<td style=\"font-size: 12px;\" >"+(i+1)+"</td>"+ 
			"<td style=\"font-size: 12px;\" ><input type=\"text\" class=\"form-control input-sm distribucion\" name=\"mod_dis_referencia\" value=\"\"></td>"+
			"<td style=\"font-size: 12px;\" ><input type=\"text\" class=\"form-control input-sm distribucion distribucion_autocomplete\" name=\"mod_dis_codigo\"  value=\"\"></td>"+
			"<td style=\"font-size: 12px;\" ><input type=\"text\" style=\"border: 0;\" class=\"form-control input-sm\" value=\"\" name=\"mod_dis_nombre\">"+
		        "<input type=\"hidden\" name=\"mod_dis_id_plan_cuentas\" value=\"\" ></td>"+
		    "<td style=\"font-size: 12px;\"><select id=\"mod_tipo_pago\" name=\"mod_tipo_pago\" class=\" form-control\" ></select></td>"+
		    "<td style=\"font-size: 12px;\"><span name=\"mod_dis_valor\" class=\"form-control\"></span></td>"+
		    "</tr>"
	}

	$tablaDistribucion.find('> tbody').append($filaBody);
	
	$tablaDistribucion.find("select[name='mod_tipo_pago']").append('<option value="debito" >DEBITO</option><option value="credito" >CREDITO</option>');
	$tablaDistribucion.find("input:text[name='mod_dis_referencia']").append('');
	
	var valor_a_pagar = ( $("#chk_pago_parcial_transferencias").val() == 0 ) ? $("#total_cuentas_pagar").val() : $("#valor_parcial_transferencias").val();
	
	$tablaDistribucion.find("span[name='mod_dis_valor']").text( valor_a_pagar );

	return $tablaDistribucion;
	
}

function getCuentaBancoPago(){
	
	var bancoLocal	= $("#id_bancos_local");
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=getContablePago",
		dataType:"json",
		type:"POST",
		data:{id_bancos: bancoLocal.val()}
	}).done( function(x){
		if( x.estatus != undefined ){
			if( x.estatus == "OK" ){
				
				var tabla = $("#tbl_distribucion2"); // se seleciona la tabla que se grafica en el metodo de ver tabal de distribucion
				var lastRowTable = tabla.find("tr:last-child");
				
				var rsData  = x.data[0];
				
				lastRowTable.find("input:text[name='mod_dis_codigo']").val( rsData.codigo_plan_cuentas);
				lastRowTable.find("input:text[name='mod_dis_nombre']").val( rsData.nombre_plan_cuentas );
				lastRowTable.find("input:hidden[name='mod_dis_id_plan_cuentas']").val( rsData.id_plan_cuentas);
				lastRowTable.find("select[id='mod_tipo_pago']").val("credito");
				
				
			}
		}
	}).fail( function(xhr,status,error){
		
		console.log(xhr.responseText);
	})
	
}
function getCuentaBancoPagoProveedor(){
	
	var idcuentaspagar	= $("#id_cuentas_pagar");
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=getContablePagoProveedor",
		dataType:"json",
		type:"POST",
		data:{ id_cuentas_pagar: idcuentaspagar.val() }
	}).done( function(x){
		if( x.estatus != undefined ){
			if( x.estatus == "OK" ){
				
				var tabla = $("#tbl_distribucion2"); // se seleciona la tabla que se grafica en el metodo de ver tabal de distribucion
				var lastRowTable = tabla.find("tr:first-child");
				
				var rsData  = x.data[0];
				
				lastRowTable.find("input:text[name='mod_dis_codigo']").val( rsData.codigo_plan_cuentas);
				lastRowTable.find("input:text[name='mod_dis_nombre']").val( rsData.nombre_plan_cuentas );
				lastRowTable.find("input:hidden[name='mod_dis_id_plan_cuentas']").val( rsData.id_plan_cuentas);
				lastRowTable.find("select[id='mod_tipo_pago']").val("debito");
				
			}
		}
	}).fail( function(xhr,status,error){
		
		console.log(xhr.responseText);
	})
	
}

$("#genera_transferencia").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	var _fecha_transferencia = $("#fecha_transferencia").val();
	var _total_cuentas_pagar = $("#total_cuentas_pagar").val();
	var _numero_cuenta_banco = $("#cuenta_banco").val();
	var _tipo_cuenta_banco = $("#tipo_cuenta_banco").val();
	var _total_cuentas_pagar = $("#total_cuentas_pagar").val();
	var _nombre_cuenta_banco = $("#nombre_cuenta_banco").val();
	var _id_tipo_cuentas     = $("#id_tipo_cuentas").val();
	var _descripcion_pago     = $("#descripcion_pago").val();
	
	/**dc 2020/07/21  pago parcial**/
	var chk_pago_parcial = $("#chk_pago_parcial_transferencias");
	var pago_parcial = $("#valor_parcial_transferencias");	
	
	//esta variable se declara ala cargar la pagina	
	var arrayCuentas = listaCuentas;
	
	//para insertado de la tabla archivo pago
	var _id_tipo_archivo_pago	= $("#id_tipo_archivo_pago").val();
	
	var isCredito = $("#id_creditos").val() == undefined ? false : true;  // aqui determinar si la transferencia se le hace a un credito
	var banco_local = $("#id_bancos_local");
	var banco_transferir = $("#id_bancos_transferir");
	
	parametros 	= new FormData();	
	parametros.append('lista_distribucion', arrayCuentas);
	parametros.append('id_cuentas_pagar', _id_cuentas_pagar);
	parametros.append('fecha_transferencia', _fecha_transferencia);
	parametros.append('total_cuentas_pagar', _total_cuentas_pagar);
	parametros.append('tipo_cuenta_banco', _tipo_cuenta_banco);
	parametros.append('numero_cuenta_banco', _numero_cuenta_banco);
	parametros.append('id_bancos_local', banco_local.val() );
	parametros.append('id_bancos_transferir', banco_transferir.val() );
	parametros.append('is_credito', isCredito);	
	parametros.append('id_tipo_cuentas', _id_tipo_cuentas);	
	parametros.append('id_tipo_archivo_pago', _id_tipo_archivo_pago);
	parametros.append('descripcion_pago', _descripcion_pago);
	
	/** dc 2020/07/22 **/
	parametros.append('check_pago_parcial', chk_pago_parcial.val() );
	parametros.append('valor_pago_parcial', pago_parcial.val() );
		
	$.ajax({
		url:"index.php?controller=Transferencias&action=GeneraTransferencia",
		type:"POST",
		dataType:"json",
		processData: false, 
		contentType: false,
		data:parametros
	}).done(function(x){
		console.log(x);
		
		if( x.estatus != undefined ){
			if( x.estatus == "OK" ){
				swal({				
					title:"TRANSACION REALIZADA",
					icon:"success",
					text:x.mensaje
				}).then(function(){
					
					window.open("index.php?controller=Pagos&action=Index","_self");
				})
			}
			
			if( x.estatus == "ERROR"){
				swal({				
					title:" ERROR TRANSACION",
					icon:x.icon,
					text:x.mensaje
				})
			}
				
		}
		
		if( x.estatus == undefined ){
			swal({				
				title:"ERROR PROCESO",
				icon:"error",
				dangerMode: true,
				text:"llamar al administrador del sistema"
			})
		}
		
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText;
		
		swal( {
			 title:"CONEXION",
			 dangerMode: true,
			 text:"No se pudo conectar con el servidor! llamar al administrador",
			 icon: "error"
			})
		
		console.log(err)
		
	})
})

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
 

/* VENTANAS MODALES */
// metodo se submit

$("#lista_distribucion_transferencia").on("focus","input.distribucion.distribucion_autocomplete[type=text]",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 3,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=CuentasPagar&action=autompletePlanCuentas",
    				dataType:"json",
    				type:"GET",
    				data:{term:request.term},
    			}).done(function(x){
    				
    				response(x); 
    				
    			}).fail(function(xhr,status,error){
    				var err = xhr.responseText
    				console.log(err)
    			})
    		},
    		select: function (event, ui) {
     	       	// Set selection
    			let fila = _elemento.closest("tr");
    			let in_nombre_plan_cuentas = fila.find("input:text[name='mod_dis_nombre']")
    			let in_id_plan_cuentas = fila.find("input:hidden[name='mod_dis_id_plan_cuentas']")
    			let in_codigo_plan_cuentas = fila.find("input:text[name='mod_dis_codigo']")
    			
    			if(ui.item.id == ''){
    				 _elemento.closest("table").notify("Digite Cod. Cuenta Valido",{ position:"top center"});
    				 in_nombre_plan_cuentas.val('');
    	    		 in_codigo_plan_cuentas.val('');
    	    		 in_id_plan_cuentas.val('');
    				 return;
    			}
    			
    			in_nombre_plan_cuentas.val(ui.item.nombre);
    			in_codigo_plan_cuentas.val(ui.item.value);
    			in_id_plan_cuentas.val(ui.item.id);
    			     	     
     	    },
     	   appendTo: "#mod_distribucion_pago",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null){
     			   
     			 _elemento.closest("tr").find("input:hidden[name='mod_dis_id_plan_cuentas']").val("");
     			 _elemento.closest("table").notify("Digite Cod. Cuenta Valido",{ position:"top center"});
     			_elemento.val('');
     			let fila = _elemento.closest("tr");
    			fila.find("input:text[name='mod_dis_nombre']").val('');
    			fila.find("input:hidden[name='mod_dis_id_plan_cuentas']").val('')
     			 
     		   }
     	   }
    	
    	}).focusout(function() {
    		
    	})
    }
});

//PARA INPUT DE REFERENCIA
/* poner mismo texto a todos */
$("#mod_distribucion_pago").on("keyup","input:text[name='mod_dis_referencia']",function(){
		
	let valorPrincipal = $(this).val();
	
	$("input:text[name='mod_dis_referencia']").each(function(index,value){		
		$(this).val(valorPrincipal);
	})
	
})


//poner el mismo texto a todos 
$("#descripcion_pago").click(function() {
		
    var descripcion_pago = $(this).val();

	var modal	= $("#mod_distribucion_pago");
	modal.find('#mod_descripcion_transferencia').val(descripcion_pago);
	modal.find('#mod_dis_referencia').val(descripcion_pago);
	
    
  });
  
  $("#descripcion_pago").change(function() {
		    
	  var descripcion_pago = $(this).val();

		var modal	= $("#mod_distribucion_pago");
		modal.find('#mod_descripcion_transferencia').val(descripcion_pago);
		modal.find('#mod_dis_referencia').val(descripcion_pago);
        
	    });
	



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





function loadBancosGeneral( a=0 ){	
	
	var $ddlChequera = $("#id_bancos_general");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=Transferencias&action=CargaBancosGeneral",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				if( value.id_bancos == a)
				{
					$ddlChequera.append( '<option value="'+value.id_bancos+'" selected >'+value.nombre_bancos+'</option>' );					
				}else
				{
					$ddlChequera.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
				}
				
			})
		}
		//console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function loadTipoCuentaGeneral( a=0 ){	
	
	var $ddlChequera = $("#id_tipo_cuentas_general");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=Transferencias&action=CargaTipoCuentaGeneral",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				
				if( a == value.id_tipo_cuentas )
				{
					$ddlChequera.append( '<option value="'+value.id_tipo_cuentas+'" selected >'+value.nombre_tipo_cuentas+'</option>' );
				}else
				{
					$ddlChequera.append( '<option value="'+value.id_tipo_cuentas+'">'+value.nombre_tipo_cuentas+'</option>' );
				}
				
			})
		}
		//console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}



var mostrar_datos_garantes = function(a){
	
	
	
	var element = $(a);
	
	if( element.length )
	{			
		var modaledit = $("#mod_cambia_cuentas");	
	
		modaledit.find('#id_proveedores_general').val( element.data().id_proveedores );
		//modaledit.find('#id_bancos_general').val( element.data().id_bancos );
		//modaledit.find('#id_tipo_cuentas_general').val( element.data().id_tipo_cuentas );
		
		var id_bancos_transferir = $("#id_bancos_transferir").val();	
		var tipo_cuenta_banco = $("#id_tipo_cuentas").val();	
		
		//alert(id_bancos_transferir);
		
		//loadBancosGeneral( element.data().id_bancos );
		//loadTipoCuentaGeneral( element.data().id_tipo_cuentas );
		
		loadBancosGeneral(id_bancos_transferir);
		loadTipoCuentaGeneral(tipo_cuenta_banco);
		
		modaledit.modal();
	}	
	
}

var editar_cuentas	= function(){

	var modalEditCuentas	= $("#mod_cambia_cuentas");
	
	var params	= { id_proveedores: modalEditCuentas.find('#id_proveedores_general').val(),
			id_bancos: modalEditCuentas.find('#id_bancos_general').val(), 
			id_tipo_cuentas: modalEditCuentas.find('#id_tipo_cuentas_general').val(), 
			numero_cuenta_bancaria: modalEditCuentas.find('#cuenta_banco_general').val() }
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=EditarCuentasProveedores",
		dataType:"json",
		type:"POST",
		data: params
	}).done(function(x){
		
		swal({title:"OK",text:"Registro Actualizado",icon:"success"});
		
		modalEditCuentas.modal('hide');
		
		//vista
		$("#id_tipo_cuentas").val( modalEditCuentas.find('#id_tipo_cuentas_general').val() );
		$("#cuenta_banco").val( modalEditCuentas.find('#cuenta_banco_general').val() );
		$("#tipo_cuenta_banco").val( modalEditCuentas.find('#id_tipo_cuentas_general option:selected').text() );
		$("#id_bancos_transferir").empty();
		$("#id_bancos_transferir").append( '<option value="'+modalEditCuentas.find('#id_bancos_general').val()+'">'+modalEditCuentas.find('#id_bancos_general option:selected').text()+'</option>' );
		
	}).fail(function( xhr, status, error){
		console.log(xhr.responseText);
		swal({title:"ERROR",text:"Error al actualizar Registro",dangerMode:true,icon:"error"});
	});
}

/************************************************************ CAMBIOS PARA VALOR PACIAL ****************************************/
var fnValidaPagoParcial	= function(a){
	var elemento = $(a);
	if( elemento.is(':checked') ) {
        // Hacer algo si el checkbox ha sido seleccionado
		elemento.val(1);
        $("#valor_parcial_transferencias").attr("readonly",false).val("");
    } else {
        // Hacer algo si el checkbox ha sido deseleccionado
    	elemento.val(0);
        $("#valor_parcial_transferencias").attr("readonly",true).val("0");
    }
}



