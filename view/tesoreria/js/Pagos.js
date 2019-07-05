$(document).ready(function(){
	
	buscaCuentasPagar();	
		
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * 
 * @returns
 */
function init(){
	
	//$(".inputDecimal").val('0.00');
	
	/* para ver clase de errores, cambiar stilo cuando son de grupo */	
	$("div.input-group").children("div.errores").css({"margin-top":"-10px","margin-left":"0px","margin-right":"0px"});
	$(".field-sm").css({"font-size":"12px"});
	
	$("#impuestos_cuentas_pagar").hide();
	
	var fechaServidor = $("#fechasistema").text();
		
	$("#fecha_cheque").inputmask("datetime",{
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
			if( (new Date($(this).val()).getTime() > new Date(fechaServidor).getTime()))
		    {
				$(this).notify("Fecha no puede ser Mayor",{ position:"buttom left", autoHideDelay: 2000});
				$(this).val('')
		    }
		}
	});
	
	
	
}

/*******
 * funcion para poner mayusculas
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});


function numeros(e){
	  var key = window.event ? e.which : e.keyCode;
	  if (key < 48 || key > 57) {
	    e.preventDefault();
	  }
 }

$("#txtbuscarProveedor").on("keyup",function(){
	
	buscaCuentasPagar();
	
})

function buscaCuentasPagar(pagina=1){
	
	let _busqueda = $("#txtbuscarProveedor").val();
	let datos={peticion:'',busqueda:_busqueda};
	let cantidadrespuesta = $("#cantidad_busqueda");
	
	$("#tabla_cuentas_pagar").html('');
	
	$.ajax({
		url:"index.php?controller=Pagos&action=indexconsulta",
		dataType:"json",
		type:"POST",
		data:datos,
	}).done(function(x){		
		
		cantidadrespuesta.html('<strong>Registros:</strong>&nbsp; '+ x.valores.cantidad);
		
		$("#tabla_cuentas_pagar").html(x.html);
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err)
		cantidadrespuesta.html('<strong>Registros:</strong>&nbsp;  0');
		let _diverror = ' <div class="col-lg-12 col-md-12 col-xs-12"> <div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
			_diverror +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            _diverror += '<h4>Aviso!!!</h4> <b>Error en conexion a la Base de Datos</b>';
            _diverror += '</div></div>';
            
		$("#tabla_cuentas_pagar").html(_diverror);
	})
}
