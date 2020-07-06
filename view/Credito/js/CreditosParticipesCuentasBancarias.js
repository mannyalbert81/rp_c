
//(function( param1, param2 ){
//  console.log( param1 ); // Hello World
//  
//})( 'Hello World' ); 

//(function(){
//	obtener_datos_cuentas_bancarias();
//})();

//!function(){ obtener_datos_cuentas_bancarias();  }();

$(document).ready( function (){
	obtener_datos_cuentas_bancarias();
	iniciar_eventos();
	$("#panel_participes_cuentas").hide();
});

//variables de vista
var viewcuentas	= viewcuentas || {};

viewcuentas.id_bancos	= $("#spanel_id_bancos");
viewcuentas.id_tipo_cuentas	= $("#spanel_id_tipo_cuentas");
viewcuentas.numero_cuentas	= $("#spanel_numero_cuentas");
viewcuentas.cuenta_principal= $("#spanel_cuenta_principal");
viewcuentas.id_cuentas_participes	= $("#spanel_id_cuentas_bancos_participe");

var iniciar_eventos	= function(){
	
	$("#btn_guardar_cuentas").on("click",function(){
		
		if( valida_formulario_cuentas() )
		{
			ingresa_cuentas_bancarias();
		}		
		
	})
	
	$("#btn_listar_cuentas").on("click",function(){			
		$("#panel_participes_cuentas").toggle();
		mostrar_cuentas_participes();			
	})
	
}

var obtener_datos_cuentas_bancarias	= function(){
	
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=obtenerDatosCuentasBancos",
		dataType:"json",
		type:"POST",
		data:null
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			var datosbancos = x.rsbancos || [];
			var elementBancos	= $("#spanel_id_bancos");
			elementBancos.empty().append('<option value="0">--Selecione--</option>');
			$.each(datosbancos,function( index, value ){
				elementBancos.append('<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>');
			});
			
			var datostipocuentas = x.rstipocuentas || [];
			var elementTiposCuentas	= $("#spanel_id_tipo_cuentas");
			elementTiposCuentas.empty().append('<option value="0">--Selecione--</option>');
			$.each(datostipocuentas,function( index, value ){
				elementTiposCuentas.append('<option value="'+value.id_tipo_cuentas+'">'+value.nombre_tipo_cuentas+'</option>');
			})			
		}
		
	}).fail( function( xhr, status, error ){
				
		console.log( xhr.responseText );
	})
} 

var ingresa_cuentas_bancarias	= function(){
			
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=ingresaCuentasBancariasParticipe",
		dataType:"json",
		type:"POST",
		data:parametros_cuentas()
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			swal({ title:"PROCESO REALIZADO", text:"Cuenta participe Ingresada", icon:"success" });	
			
			viewcuentas.id_bancos.val("0");
			viewcuentas.id_tipo_cuentas.val("0");
			viewcuentas.numero_cuentas.val("");
			viewcuentas.cuenta_principal.val("0");
			
		}
		
	}).fail( function( xhr, status, error ){
		swal({ title:"ERROR", text:"Cuenta participe no Ingresada", icon:"error", dangerMode:true });
		console.log( xhr.responseText );
	})
	
}

var parametros_cuentas	= function(){	
	return {
		"id_participes": view.hdn_id_participes.val(), //esta variable toma de la vista 
		"id_bancos": viewcuentas.id_bancos.val(),
		"id_tipo_cuentas": viewcuentas.id_tipo_cuentas.val(),
		"numero_participes_cuentas": viewcuentas.numero_cuentas.val(),
		"cuenta_principal": viewcuentas.cuenta_principal.val()
	}
}

var valida_formulario_cuentas	= function(){
	try{
		
		if( viewcuentas.id_bancos.val() == 0 )
		{
			viewcuentas.id_bancos.notify("Seleccione Banco",{ position:"buttom left", autoHideDelay: 2000 });
			throw "bancos";
		}
		
		if( viewcuentas.id_tipo_cuentas.val() == 0 )
		{
			viewcuentas.id_tipo_cuentas.notify("Seleccione Tipo Cuenta",{ position:"buttom left", autoHideDelay: 2000 });
			throw "tipo cuenta";
		}
		
		if( viewcuentas.numero_cuentas.val().length == 0 ||  viewcuentas.numero_cuentas.val() == "" )
		{
			viewcuentas.numero_cuentas.notify("Digite un numero de cuenta",{ position:"buttom left", autoHideDelay: 2000 });
			throw "numero cuentas";
		}
		
		if( viewcuentas.cuenta_principal.val() == 0 )
		{
			viewcuentas.cuenta_principal.notify("Seleccione Principal",{ position:"buttom left", autoHideDelay: 2000 });
			throw "cuenta principal";
		}
		return true;
		
	}catch (e) {
		// TODO: handle exception
		//console.log("error no controlado Cuentas Bancarias");
		return false;
	}
	
}

var mostrar_cuentas_participes	= function(a=1){
			
	var params	= {
			"id_participes": view.hdn_id_participes.val(), //esta variable toma de la vista 
			"search": $("#spanel_buscador_cuentas").val(),
			"page": a,
		}
	
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=listarCuentasBancariasParticipes",
		dataType:"json",
		type:"POST",
		data:params
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			var tablebody	= $("#spanel_tbl_cuentas_bancarias").find('tbody');
			var divpaginacion	= $("#div_cuentas_paginacion"); 
			
			tablebody.empty().append( x.filas );
			divpaginacion.html( x.paginacion );
						
		}
		
	}).fail( function( xhr, status, error ){
		swal({ title:"ERROR", text:"Error al buscar informacion", icon:"error", dangerMode:true });
		console.log( xhr.responseText );
	})
}

var editar_participes_cuentas	= function(a){
	
	var id_participes_cuentas	= a;
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=editarParticipesCuentas",
		dataType:"json",
		type:"POST",
		data:{'id_participes_cuentas':id_participes_cuentas}
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			var y = x.data[0];
			
			viewcuentas.id_bancos.val( y.id_bancos);
			viewcuentas.id_tipo_cuentas.val( y.id_tipo_cuentas );
			viewcuentas.numero_cuentas.val( y.numero_participes_cuentas );
			viewcuentas.cuenta_principal.val( ( ( y.cuenta_principal = 't' ) ? 'true' : 'false' ) );
			
			$('html, body').animate({
			      scrollTop: viewcuentas.id_bancos.offset().top - 250
			    }, 'slow');
			
			swal({ title:"PROCESO REALIZADO", text:"", icon:"success" });
			
			//buscar datos de participes cuentas
			mostrar_cuentas_participes();						
		}
		
	}).fail( function( xhr, status, error ){
		swal({ title:"ERROR", text:"Datos no encontrados", icon:"error", dangerMode:true });
		console.log( xhr.responseText );
	})
}

var eliminar_participes_cuentas	= function(a){
	
	var id_participes_cuentas	= a;
	
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=eliminarParticipesCuentas",
		dataType:"json",
		type:"POST",
		data:{'id_participes_cuentas':id_participes_cuentas}
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			swal({ title:"PROCESO REALIZADO", text:"Datos Eliminados", icon:"success" });	
			
			//buscar datos de participes cuentas
			mostrar_cuentas_participes();	
		}
		
	}).fail( function( xhr, status, error ){
		swal({ title:"ERROR", text:"Error al eliminar datos", icon:"error", dangerMode:true });
		console.log( xhr.responseText );
	})
	
}