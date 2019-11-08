$(document).ready(function(){ 	 
	  
});

$("#id_modulos").on("change",function(){
	 
	 let moduloId = $(this).val();
	 let objProcesos = $("#id_tipo_procesos");
	 
	 objProcesos.empty();
	 
	 $.ajax({
		 url:"index.php?controller=TributarioGeneraAts&action=consultaTipoProcesos",
		 type:"POST",
		 dataType:"json",
		 data:{id_modulos:moduloId}
	 }).done(function(x){
		// console.log(moduloId)
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
	 
}

)

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
	
	if(!validafecha()){
		$mesProcesos.notify("Fecha Invalida/Fecha Fuera de periodo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	console.log("hola");
	$.ajax({
		 url:"index.php?controller=TributarioGeneraAts&action=totalesAts",
		 type:"POST",
		 dataType:"json",
		 data:{peticion:'totales',id_tipo_procesos:$procesos.val(),anio_procesos: $anioProcesos.val(),mes_procesos: $mesProcesos.val()}
		
	 }).done(function(x){
	
		 console.log(x);
		 if ( x.hasOwnProperty( 'tabladatos' ) || ( x.tabladatos != '' ) ) {
			 $divResultados.html(x.tabladatos);
			 setTableStyle("tbl_detalle_diario");
		 };
		 
		 
		 
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log("ERROR")
		 console.log(x);
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
	//$divResultados.html();
		
	
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
	
	if(!validafecha()){
		$mesProcesos.notify("Fecha Invalida/Fecha Fuera de periodo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	
	$.ajax({
		 url:"index.php?controller=TributarioGeneraAts&action=generaAts",
		 type:"POST",	
		 dataType:"json",
		 data:{peticion:'totales',id_tipo_procesos:$procesos.val(),anio_procesos: $anioProcesos.val(),mes_procesos: $mesProcesos.val()}
		
	 }).done(function(x){
	
		 console.log("Mesaje: " . x);
		
		 
		 
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



