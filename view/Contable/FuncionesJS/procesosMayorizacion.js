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
	
	let $modulo = $("#id_modulos"),
		$procesos = $("#id_tipo_procesos"),
		$anioProcesos = $("#anio_procesos"),
		$mesProcesos = $("#mes_procesos"),
		$divResultados = $("#div_detalle_procesos");
	$divResultados.html();
	
	if($modulo.val() == '0'){
		$modulo.notify("Seleccione el modulo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	if($procesos.val() == '0'){
		$procesos.notify("Seleccione el proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($anioProcesos.val() == ''){
		$anioProcesos.notify("Digite anio",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($mesProcesos.val() == '0'){
		$mesProcesos.notify("Seleccione mes de proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	$.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=detallesDiarioTipo",
		 type:"POST",
		 dataType:"json",
		 data:{peticion:'simulacion',id_tipo_procesos:$procesos.val(),anio_procesos: $anioProcesos.val(),mes_procesos: $mesProcesos.val()}
	 }).done(function(x){
		 //console.log(x)
		 $divResultados.html(x.tabladatos);
		 setTableStyle("tbl_detalle_diario");
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
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
})

$("#btngenera").on("click",function(event){
	
	let $modulo = $("#id_modulos"),
		$procesos = $("#id_tipo_procesos"),
		$anioProcesos = $("#anio_procesos"),
		$mesProcesos = $("#mes_procesos"),
		$divResultados = $("#div_detalle_procesos");
	$divResultados.html();
	
	if($modulo.val() == '0'){
		$modulo.notify("Seleccione el modulo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	if($procesos.val() == '0'){
		$procesos.notify("Seleccione el proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($anioProcesos.val() == ''){
		$anioProcesos.notify("Digite anio",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($mesProcesos.val() == '0'){
		$mesProcesos.notify("Seleccione mes de proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	swal({
		title: "¿Generar Diario?",
		text: "La siguiente accion no se revertira!",
		icon:"view/images/capremci_load.gif",
		dangerMode:true,
		buttons: {
		    cancel:"Cancelar",
		    continuar: "Continuar",		    
		  },
	}).then((value) => {
		  switch (value) {		 
		    case "cancel":
		      return;
		      break;		 
		    case "continuar":
		    	generaDiario($modulo.val(), $anioProcesos.val(), $mesProcesos.val());
		      break;
		    default:
		      return;
		  }
		});
	
	
	
})

function generaDiario(in_proceso,in_anio,in_mes){
	
	$.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=detallesDiarioTipo",
		 type:"POST",
		 dataType:"json",
		 data:{peticion:"generar",id_tipo_procesos:in_proceso,anio_procesos: in_anio,mes_procesos: in_mes}
	 }).done(function(x){
		 //console.log(x)
		 $divResultados.html(x.tabladatos);
		 setTableStyle("tbl_detalle_diario");
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
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
	
}