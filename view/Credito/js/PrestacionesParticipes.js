$(document).ready(function(){
	
})


var view = view || {};

view.identificacion_participe 	= $("#identificacion_participe");
view.id_participes	= $("#id_participes");
view.btn_buscar_participe	= $("#btn_buscar_identificacion"); 
view.btn_limpiar_participe	= $("#btn_limpiar_identificacion"); 
view.btn_buscar_credito	= $("#btn_buscar_credito");

/*** FUNCIONES VIEW ***/
view.fecha_actual	= function(){	
	var f = new Date();
	fecha = f.getFullYear() + "-" + ("00" + (f.getMonth() +1) ).slice(-2) + "-" + ("00" + f.getDate() ).slice(-2) ;	
	return fecha;
}


/***** VARIABLES MODAL ***/
var mdcancelacion	= mdcancelacion || {};
mdcancelacion.fecha_cobro	= $("#md_fecha_cobro");

/**EVENT CLICK PARA BUSCAR DATOS PARTICIPE**/
view.btn_buscar_participe.on('click',function(){
	
	if( view.identificacion_participe.val() == "" || view.identificacion_participe.val().length < 10 ){ return false;}
	
	$.ajax({
		url:"index.php?controller=BuscarParticipesCesantes&action=ObtenerDatosParticipe",
		dataType:"json",
		type:"POST",
		data:{'identificacion':view.identificacion_participe.val()}
	}).done(function(x){
		
		let in_html = x.html;
		let in_id_paticipes = x.id_participes;
		view.id_participes.val(in_id_paticipes);
		
		$('#dvdatos_participes').html( in_html );
		
		swal({title:"Continuar",text:"Datos Participe econtrados por favor Continue",icon:"success"});
		
	}).fail(function(xhr,status,error){
		
		console.log( xhr.responseText );
		
	})
	
})

/**EVENT KEY PRESS PARA BUSCAR DATOS PARTICIPE**/
view.identificacion_participe.on('keypress', function(e){
	
	if( e.keyCode == 13 ){
		view.btn_buscar_participe.click();
	}	
	
});

/**EVENT CLICK PARA LIMPIAR DATOS PARTICIPE**/
view.btn_limpiar_participe.on('click',function(){
	view.identificacion_participe.val('');
	$('#dvdatos_participes').html( '' );
	view.id_participes.val(0);
});

/**EVENT CLICK PARA BUSCAR DATOS CREDITOS**/
view.btn_limpiar_participe.on('click',function(){
	view.identificacion_participe.val('');
	$('#dvdatos_participes').html( '' );
	view.id_participes.val(0);
})



/******** PARA ELEMENTOS Y FUNCIONES CREADAS DESDE VISTA *********************/
var fn_cancelacion_prestamo = function(){
	
	mdcancelacion.fecha_cobro.val( view.fecha_actual() );
	$("#mod_cancelacion_creditos").modal();
}


