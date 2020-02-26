var listaCuentas = [];
$(document).ready(function(){
		
	init();
	devuelveconsecutivos();
	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	$("#genera_transferencia").attr("disabled",true);
	
	var fechaServidor = $("#fechasistema").text();
		
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
		$descripcion.notify("Ingrese una descripcion",{ position:"top center"});
		return false;
	}
	
	$divResultados = $modal.find("#lista_distribucion_transferencia");		
	$divResultados.html('');
	
	$divResultados.html(graficaTablaDistribucion());
	
	$modal.find("#mod_identificacion_proveedor").val($("#identificacion_proveedor").val());
	$modal.find("#mod_total_cuentas_pagar").val($("#total_cuentas_pagar").val());
	$modal.find("#mod_nombre_proveedor").val($("#nombre_proveedor").val());
	$modal.find("#mod_banco_transferir").val($("#nombre_cuenta_banco").val());
	$modal.find("input:text[name='mod_dis_referencia']").val($("#descripcion_pago").val());
	$modal.find("span[name='mod_dis_valor']").text($("#total_cuentas_pagar").val());
	
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

	return $tablaDistribucion;
	
}

$("#genera_transferencia").on("click",function(){
	
	var _id_cuentas_pagar = $("#id_cuentas_pagar").val();
	var _fecha_transferencia = $("#fecha_transferencia").val();
	var _total_cuentas_pagar = $("#total_cuentas_pagar").val();
	var _numero_cuenta_banco = $("#cuenta_banco").val();
	var _tipo_cuenta_banco = $("#tipo_cuenta_banco").val();
	var _total_cuentas_pagar = $("#total_cuentas_pagar").val();
	var _nombre_cuenta_banco = $("#nombre_cuenta_banco").val();
	
	//esta variable se declara ala cargar la pagina
	//listaCuentas
	console.log(listaCuentas);
	var arrayCuentas = listaCuentas;
	
	parametros 	= new FormData();	
	parametros.append('lista_distribucion', arrayCuentas);
	parametros.append('id_cuentas_pagar', _id_cuentas_pagar);
	parametros.append('fecha_transferencia', _fecha_transferencia);
	parametros.append('total_cuentas_pagar', _total_cuentas_pagar);
	parametros.append('tipo_cuenta_banco', _tipo_cuenta_banco);
	parametros.append('nombre_cuenta_banco', _nombre_cuenta_banco);
	parametros.append('numero_cuenta_banco', _numero_cuenta_banco);
	
	
	$.ajax({
		url:"index.php?controller=Transferencias&action=GeneraTransferencia",
		type:"POST",
		dataType:"json",
		processData: false, 
		contentType: false,
		data:parametros
	}).done(function(x){
		console.log(x);
		
		if(x.respuesta == 1){
			
			swal({				
				title:"TRANSACION REALIZADA",
				icon:"success",
				text:x.mensaje
			}).then(function(){
				
				window.open("index.php?controller=Pagos&action=Index","_self");
			})
		}
		
	}).fail(function(xhr, status, error){
		
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		if( mensaje !== null ){
			var resmsg = mensaje[1]
			swal( {
				 title:"Tansferencia",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		}
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



