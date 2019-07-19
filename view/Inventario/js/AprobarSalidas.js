$(document).ready( function (){
	getDetalleSolicitud();
});

function getDetalleSolicitud(_page=1){
	
	let $divDetalles = $("#div_lista_productos");
	
	$divDetalles.html("");
	
	//para enviar datos
	let $movimientoId = $("#id_movimiento_solicitud");
	
	let parametros = {page:_page,peticion:"ajax", id_movimientos:$movimientoId.val()}
	
	$.ajax({
		url:"index.php?controller=MovimientosInv&action=GetDetalleSolicitud",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		
		$divDetalles.html(x.tablaHtml);	
		
		generaTabla("tbl_detalles_solicitudes");
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 console.log(resmsg)
		 	}
	})
}

function generaTabla(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		//paging: false,
        pageLength: 10,
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"html5buttons">lfrtipB',      
        buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'Reporte'},
            {extend: 'pdf', title: 'Reporte'},

            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]

    });
}