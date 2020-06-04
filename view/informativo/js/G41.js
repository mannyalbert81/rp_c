

$("#btnDetalles").on("click",function(event){
	

	let $mes=$('#mes_reporte');
	let $anio=$('#a_reporte');
	
	let $mes_actual = parseInt($mes.val()) + parseInt(1);
	
	$divResultados = $("#div_estructura");
	$divResultados.html();
	
	if($anio.val() == ''){
		$anioProcesos.notify("Digite Año",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($mes_actual == '0'){
		$mesProcesos.notify("Seleccione Mes de Proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	

	 
	console.log("Mes: " + $mes_actual); 
	 
	
	 $.ajax({
			
		 url: 'index.php?controller=EstructurasBiess&action=CargaInformacion',
		    type: 'POST',
		    dataType:"json",
		    data: {
		    	 mes_reporte:$mes_actual,
		    	 anio_reporte:$anio.val()	    	      	   
		    }
	
	})
	.done(function(x) {
		
		 console.log(x);
		 if ( x.hasOwnProperty( 'tabladatos' ) || ( x.tabladatos != '' ) ) {
			 $divResultados.html(x.tabladatos);
			 setTableStyle("tbl_detalle_diario");
		 };
		 
		
	}).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
	 })
	 
})



$("#btngenera").on("click",function(event){
	


	let $mes=$('#mes_reporte');
	let $anio=$('#a_reporte');

	let $mes_actual = parseInt($mes.val()) + parseInt(1);

	
	if($anio.val() == ''){
		$anioProcesos.notify("Digite Año",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	if($mes_actual == '0'){
		$mesProcesos.notify("Seleccione Mes de Proceso",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	 swal("Generando XML", {
	      icon: "success",
	      buttons: false,
	      timer: 8000
	    });
	 
	$.ajax({
		 url: 'index.php?controller=EstructurasBiess&action=generaG41',
		    type: 'POST',
		    dataType:"json",
		    data: {
		    	 mes_reporte:$mes_actual,
		    	 anio_reporte:$anio.val()	    	      	   
		    }
	 }).done(function(x){
	
		 
		 console.log("Mesaje: " + x.tabladatos);
		
		
		 
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
