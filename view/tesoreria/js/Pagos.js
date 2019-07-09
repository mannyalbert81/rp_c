$(document).ready(function(){
	
	buscaCuentasPagar();	
		
})


/*******
 * funcion para poner mayusculas
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});





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

