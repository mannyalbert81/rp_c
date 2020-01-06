$(document).ready(function(){
	RegistrarDetallePeriodo();
})


function RegistrarDetallePeriodo(){
		
	var $anio_periodo = $("#anio_periodo");
	
	if( $anio_periodo.val() == 0 || $anio_periodo == "" ){
		
		//aqui hacer validaciones de periodo
		return false;
	}
	
	$.ajax({
	    url:"index.php?controller=Periodo&action=RegistrarDetallesPeriodo",
		dataType:"json",
		type:"POST",
		data:{anio_periodo:$anio_periodo.val()},
	}).done(function(x){
		var tblPeriodo = $("#tbl_detalles_periodo");					
		if(x.dataFilas != undefined ){
			tblPeriodo.find("tbody").empty();
			tblPeriodo.find("tbody").append(x.dataFilas);
			
		}
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	});
	
	return false;
} 

function fnAbrirPeriodo(ObjButton){
	
	var $button = $(ObjButton);
	var $identificador = $button.data("periodo_id");
	
	$.ajax({
	    url:"index.php?controller=Periodo&action=OpenPeriodo",
		dataType:"json",
		type:"POST",
		data:{identificador:$identificador},
	}).done(function(x){
		if( x.respuesta != undefined ){
			
			if( x.respuesta == "ERROR"){
				swal({text:x.mensaje,title:"PERIODO",icon:"error"});
			}
			
			if( x.respuesta == "OK"){
				swal({text:x.mensaje,title:"PERIODO",icon:"success"});
			}
		}
		RegistrarDetallePeriodo();
		console.log(x)
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	});
	
}

function fnCerrarPeriodo(ObjButton){
	
	var $button = $(ObjButton);
	var $identificador = $button.data("periodo_id");
	
	$.ajax({
	    url:"index.php?controller=Periodo&action=ClosePeriodo",
		dataType:"json",
		type:"POST",
		data:{identificador:$identificador},
	}).done(function(x){
		if( x.respuesta != undefined ){
			
			if( x.respuesta == "ERROR"){
				swal({text:x.mensaje,title:"PERIODO",icon:"error"});
			}
			
			if( x.respuesta == "OK"){
				swal({text:x.mensaje,title:"PERIODO",icon:"success"});
			}
		}
		RegistrarDetallePeriodo();
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	});
	
}



