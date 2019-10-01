$(document).ready(function(){ 	 
	  
});



function setTableStyle(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		paging: false,
        scrollX:  "100%",
		searching: false,
		fixedHeader: true,
		scrollY: '70vh',
	    scrollCollapse: true,
        pageLength: 10,
        responsive: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
        aoColumnDefs : [ {
            "bSortable" : false,
            "aTargets" : [ "sorting_disabled" ]
        } ],
        bLengthChange: false,
        bSort: false,
        order: []
    });
}

function ChangeCssTable(ObjTabla){
	var objeto = $("#"+ObjTabla);
	objeto.find("tbody tr td").css({
		"height":"6px",
		"padding":"0px",
		"margin":"0px",
	});
	
	$("#"+ObjTabla).on('shown.bs.collapse', function () {
		   $($.fn.dataTable.tables(true)).DataTable()
		      .columns.adjust();
		});
}

$("#verDiario").on("click",function(event){
		
	let $anioProcesos = $("#anio_procesos"),
		$mesProcesos = $("#mes_procesos"),
		$divResultados = $("#div_detalle_procesos");
	
	$divResultados.html();	
	
	if($anioProcesos.val() == ''){
		$anioProcesos.notify("Digite anio",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($mesProcesos.val() == '0'){
		$mesProcesos.notify("Seleccione mes de proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	if(!validafecha()){
		$mesProcesos.notify("Fecha Invalida/Fecha Fuera de periodo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	$.ajax({
		 url:"index.php?controller=Nomina&action=DiarioPagoNomina",
		 type:"POST",
		 dataType:"json",
		 data:{peticion:'simulacion',anio_procesos: $anioProcesos.val(),mes_procesos: $mesProcesos.val()}
	 }).done(function(x){
		 console.log(x)
		 if ( x.hasOwnProperty( 'mensaje' ) || ( x.mensaje != '' ) ) {
			
			 if( x.mensaje == 'EXISTE PROCESO' ){
				 
				 // aqu9i implemtar la graficacion de comprobnates existente
			 }
			 
		 	if( x.mensaje == 'NO EXISTE PROCESO' ){
				 
		 		graficaDiarioPago($anioProcesos.val(),$mesProcesos.val());
			 }
			 
		 };
		
		 
		 
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

function graficaDiarioPago(_anio,_mes){
	
	let $modal = $("#mod_diario"),$divResultados = $("#mod_div_contenido");
		
	$.ajax({
		 url:"index.php?controller=Nomina&action=graficaDiarioPagoNomina",
		 type:"POST",
		 dataType:"json",
		 data:{anio_procesos: _anio,mes_procesos: _mes}
	 }).done(function(x){
		 console.log(x)
		 
		 if ( x.hasOwnProperty( 'html' ) || ( x.html != '' ) ) {
			 
			 $divResultados.html(x.html);
			 
			 $modal.modal('show');
			 
			 setTableStyle("tblDiario");
			 ChangeCssTable("tblDiario");
			 
		 }
		 
		 
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

$("#btngenera").on("click",function(event){
	
	let $modulos = $("#id_modulos"),
		$procesos = $("#id_tipo_procesos"),
		$anioProcesos = $("#anio_procesos"),
		$mesProcesos = $("#mes_procesos"),
		$divResultados = $("#div_detalle_procesos");
	$divResultados.html();
	
	$("#btngenera").attr('disabled',true);
	
	if($modulos.val() == '0'){
		$modulos.notify("Seleccione el modulo",{ position:"buttom left", autoHideDelay: 2000});
		$("#btngenera").attr('disabled',false);
		return false;
	}	
	if($procesos.val() == '0'){
		$procesos.notify("Seleccione el proceso",{ position:"buttom left", autoHideDelay: 2000});
		$("#btngenera").attr('disabled',false);
		return false;
	}
	if($anioProcesos.val() == ''){
		$anioProcesos.notify("Digite anio",{ position:"buttom left", autoHideDelay: 2000});
		$("#btngenera").attr('disabled',false);
		return false;
	}
	if($mesProcesos.val() == '0'){
		$mesProcesos.notify("Seleccione mes de proceso",{ position:"buttom left", autoHideDelay: 2000});
		$("#btngenera").attr('disabled',false);
		return false;
	}
	
	if(!validafecha()){
		$mesProcesos.notify("Fecha Invalida/Fecha Fuera de periodo",{ position:"buttom left", autoHideDelay: 2000});
		$("#btngenera").attr('disabled',false);
		return false;
	}
	
	swal({
		title: "¿Generar Diario?",
		text: "La siguiente accion no se revertira!",
		icon:"view/images/capremci_load.gif",
		dangerMode:true,
		buttons: {
		    Cancelar:"Cancelar",		    
		    continuar: "Continuar",		    
		  },
	}).then((value) => {
		  switch (value) {		 
		    case "Cancelar":
		    	$("#btngenera").attr('disabled',false);
		      break;		 
		    case "continuar":
		    	generaDiario($procesos.val(), $modulos.val(), $anioProcesos.val(), $mesProcesos.val());
		    	$("#btngenera").attr('disabled',false);
		      break;
		    default:
		      return;
		  }
		});
	
	
	
})

function generaDiario(in_proceso,in_modulo,in_anio,in_mes){
		
	$.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=detallesDiarioTipo",
		 type:"POST",
		 dataType:"json",
		 data:{peticion:"generar",id_tipo_procesos:in_proceso,id_modulos:in_modulo,anio_procesos: in_anio,mes_procesos: in_mes}
	 }).done(function(x){
		 console.log(x)		 
		 if( x.valor == 1){
			 resetFields();
			 swal({
				 title:"Procesos Mensuales",
				 text:x.mensaje,
				 icon:"success"
			 })
			 
		 }
		 if( x.valor == 2){
			 swal({
				 title:"Procesos Mensuales",
				 text:"Diario ya Generado revise los detalles en la tabla",
				 icon:"warning"
			 })
			 
			 buscaDiario(x.id_ccomprobantes);
			 
		 }
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

function buscaDiario(in_comprobante){
	
	let $divResultados = $("#div_detalle_procesos");
	$divResultados.html();
	
	$.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=graficaDiarioExistente",
		 type:"POST",
		 dataType:"json",
		 data:{id_ccomprobantes:in_comprobante}
	 }).done(function(x){
		 $divResultados.html(x.tabladatos);
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


function resetFields(){
	$("#id_modulos").val(0);
	$("#id_tipo_procesos").val(0);
}

function validafecha(){
	
	let $anio = $("#anio_procesos"),
		$mes = $("#mes_procesos");	
	 var year = new Date().getFullYear();
     var mes = new Date().getMonth()+1;
     	
	let $fechapeticion = $anio.val()+'-'+$mes.val()+'-'+'01';
	let $fechaServer = year+'-'+mes+'-'+'01';
	
	var datepeticion = new Date($fechapeticion);
	var datesever  = new Date($fechaServer);
	
	 var fecha1ms=datepeticion.getTime();
     var fecha2ms=datesever.getTime();
     var diff = fecha2ms-fecha1ms;
     if (diff < 0){
            return false; 
     }else{
            return true;
     }
	
}

