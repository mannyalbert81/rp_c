$(document).ready(function(){
	
	buscaCuentasPagar();	
		
})

/***
 * funcion para setear el estilo de las tablas
 */
function setTableStyle(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		paging: false,
        scrollX: true,
		searching: false,
        pageLength: 10,
        rowHeight: 'auto',
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"html5buttons">lfrtipB',
        buttons: [ ],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            "infoEmpty": "Mostrando 0 de 0 de 0 Registros",           
            "lengthMenu": "Mostrar _MENU_ Registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        
    });
}


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
	let datos={peticion:'',busqueda:_busqueda,page:pagina};
	let cantidadrespuesta = $("#cantidad_busqueda");
	let $divResultados = $("#div_lista_cuentas_pagar");
	
	$divResultados.html('');
	
	$.ajax({
		url:"index.php?controller=Pagos&action=indexconsulta",
		dataType:"json",
		type:"POST",
		data:datos,
	}).done(function(x){		
		
		cantidadrespuesta.html('<strong>Registros:</strong>&nbsp; '+ x.valores.cantidad);
		$divResultados.html(x.tabla_datos);
		setTableStyle(x.nombre_tabla);
		
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err)
		cantidadrespuesta.html('<strong>Registros:</strong>&nbsp;  0');
		let _diverror = ' <div class="col-lg-12 col-md-12 col-xs-12"> <div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
			_diverror +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            _diverror += '<h4>Aviso!!!</h4> <b>Error en conexion a la Base de Datos</b>';
            _diverror += '</div></div>';
            
        $divResultados.html(_diverror);
	})
}

function verificaMetodoPago(obj){
	
	$.ajax({
		url:"index.php?controller=Pagos&action=validaMetodoPago",
		dataType:"json",
		type:"POST",
		data:{},
	}).done(function(x){		
		
		console.log(x)
		
		if(x.respuesta == 'ok'){
			window.open("","_blank")
		}
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
	 	if( mensaje !== null ){
		 var resmsg = mensaje[1];
		 swal( {
			 title:"Error",
			 dangerMode: true,
			 text: resmsg.replace("|","\n"),
			 icon: "error"
			})
	 	}
	})
	
	
	console.log(obj);
	//
	//index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'
	
	return false;
}

