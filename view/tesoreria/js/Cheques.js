$(document).ready(function(){
	
	buscaChequesGenerados();	
	
})


/*******
 * funcion para poner mayusculas
 * @returns
 */
$("input.mayus").on("keyup",function(){
	$(this).val($(this).val().toUpperCase());
});


$("#txtbuscar").on("keyup",function(){
	
	buscaChequesGenerados();
	
})

function buscaChequesGenerados(pagina=1){
	
	let $busqueda = $("#txtbuscar");
	let datos={peticion:'',busqueda:$busqueda.val()};
	let $cantidadrespuesta = $("#cantidad_busqueda");	
	let $tablaDatos = $("#tabla_datos_cheques");
	
	$.ajax({
		url:"index.php?controller=GenerarCheque&action=listarCheques",
		dataType:"json",
		type:"POST",
		data:datos,
	}).done(function(x){		
		
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp; '+ x.valores.cantidad);
		
		$tablaDatos.html(x.tabladatos);
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err)
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp;  0');
		let _diverror = ' <div class="col-lg-12 col-md-12 col-xs-12"> <div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
			_diverror +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            _diverror += '<h4>Aviso!!!</h4> <b>Error en conexion a la Base de Datos</b>';
            _diverror += '</div></div>';
            
            $tablaDatos.html(_diverror);
	})
}


