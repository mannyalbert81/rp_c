$(document).ready(function(){ 	 
	  
});

$("#id_modulos").on("change",function(){
	 
	 let moduloId = $(this).val();
	 let objProcesos = $("#id_tipo_procesos");
	 
	 objProcesos.empty();
	 
	 $.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=consultaTipoProcesos",
		 type:"POST",
		 dataType:"json",
		 data:{id_modulos:moduloId}
	 }).done(function(x){
		 //console.log(x)
		 objProcesos.append('<option value="0">--Seleccione--</option>');
		 if(x.cantidad > 0){			 
			 $.each(x.data,function(index,value){
				 objProcesos.append('<option value="'+value.id_tipo_procesos+'">'+value.nombre_tipo_procesos+'</option>');
			 })
		 }
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
	 })
	 
})

function setTableStyle(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		paging: false,
        scrollX: false,
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
        }

    });
}

$("#btnDetalles").on("click",function(event){
	
	let $procesos = $("#id_tipo_procesos"),
		$divResultados = $("#div_detalle_procesos");
	$divResultados.html();
	
	$.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=detallesDiarioTipo",
		 type:"POST",
		 dataType:"json",
		 data:{id_tipo_procesos:$procesos.val()}
	 }).done(function(x){
		 //console.log(x)
		 $divResultados.html(x.tabladatos);
		 setTableStyle("tbl_detalle_diario");
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
	 })
})