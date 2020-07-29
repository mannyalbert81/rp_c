
//(function( param1, param2 ){
//  console.log( param1 ); // Hello World
//  
//})( 'Hello World' ); 

//(function(){
//	obtener_datos_cuentas_bancarias();
//})();

//!function(){ obtener_datos_cuentas_bancarias();  }();

$(document).ready( function (){
	iniciar_eventos_cuentas();	
	inhabilitar_elementos_panel_cuentas();
});

//variables de vista
var viewcuentas	= viewcuentas || {};

viewcuentas.id_bancos	= $("#spanel_id_bancos");
viewcuentas.id_tipo_cuentas	= $("#spanel_id_tipo_cuentas");
viewcuentas.numero_cuentas	= $("#spanel_numero_cuentas");

var iniciar_eventos_cuentas	= function(){
	
	$("#btn_guardar_cuentas").on("click",function(){
		
		if( valida_formulario_cuentas() )
		{
			ingresa_cuentas_bancarias_solicitud();
		}		
		
	})
	//ele evento se enlaza a un elemento creado desde controlador	
	$("body").on("click","#btn_cambiar_datos_cuentas_solicitud",function(){
		
		$('.nav-tabs a[href="#panel_cuentas_bancos"]').tab('show'); //navegar en tab bootstrap a la pagina principal		
		habilitar_elementos_panel_cuentas();
		obtener_datos_cuentas_bancarias_solicitud();
		
	});
	
}

var inhabilitar_elementos_panel_cuentas	= function(){
	
	viewcuentas.id_bancos.attr('disabled',true);
	viewcuentas.id_tipo_cuentas.attr('disabled',true);
	viewcuentas.numero_cuentas.attr('disabled',true);
}

var habilitar_elementos_panel_cuentas	= function(){
	
	viewcuentas.id_bancos.attr('disabled',false);
	viewcuentas.id_tipo_cuentas.attr('disabled',false);
	viewcuentas.numero_cuentas.attr('disabled',false);
}

var obtener_datos_cuentas_bancarias_solicitud	= function(){
	
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=obtenerDatosCuentasBancosSolicitud",
		dataType:"json",
		type:"POST",
		data:{ 'id_solicitud': view.hdn_id_solicitud.val() }
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			if( x.tipo_pago == "transferencia" )
			{
				var validadores	= x.validadores;
				var id_banco_selecionado = ( validadores.id_bancos == undefined ) ? 0 : validadores.id_bancos ;
				var datosbancos = x.databancos || [];
				viewcuentas.id_bancos.empty().append('<option value="0">--Selecione--</option>');
				$.each(datosbancos,function( index, value ){
					if( id_banco_selecionado == value.id_bancos )
					{
						viewcuentas.id_bancos.append('<option value="'+value.id_bancos+' " selected >'+value.nombre_bancos+'</option>');
					}else
					{
						viewcuentas.id_bancos.append('<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>');
					}					
				});
				
				var datostipocuentas = x.datatipocuentas || [];
				var id_tipo_cuenta_selecionado = ( validadores.id_tipo_cuentas == undefined ) ? 0 : validadores.id_tipo_cuentas ;
				viewcuentas.id_tipo_cuentas.empty().append('<option value="0">--Selecione--</option>');
				$.each(datostipocuentas,function( index, value ){
					if( id_tipo_cuenta_selecionado == value.id_tipo_cuentas )
					{
						viewcuentas.id_tipo_cuentas.append('<option value="'+value.id_tipo_cuentas+'" selected>'+value.nombre_tipo_cuentas+'</option>');
					}else
					{
						viewcuentas.id_tipo_cuentas.append('<option value="'+value.id_tipo_cuentas+'">'+value.nombre_tipo_cuentas+'</option>');
					}
					
				});	
				
				var numero_cuenta = ( validadores.numero_cuenta == undefined ) ? "" : validadores.numero_cuenta ;
				viewcuentas.numero_cuentas.val( numero_cuenta );
			}else
			{
				viewcuentas.id_bancos.attr('disabled',true);
				viewcuentas.id_tipo_cuentas.attr('disabled',true);
				viewcuentas.numero_cuentas.attr('disabled',true);
				$("#btn_guardar_cuentas").attr('disabled',true);
				
				swal({title:"INFORMACION", text:"Tipo de pago selecionado por participe Cheque", icon:"info"});
				
				$('.nav-tabs a[href="#panel_info"]').tab('show');
			}				
		}
		
	}).fail( function( xhr, status, error ){
				
		console.log( xhr.responseText );
	})
} 

var ingresa_cuentas_bancarias_solicitud	= function(){
			
	$.ajax({
		url:"index.php?controller=CreditosParticipes&action=ingresaCuentasBancariasSolicitud",
		dataType:"json",
		type:"POST",
		data:parametros_cuentas_solicitud()
	}).done( function(x){
		
		if( x.estatus != undefined && x.estatus == "OK" )
		{
			swal({ title:"PROCESO REALIZADO", text:"Datos actualizados", icon:"success" });	
			
			viewcuentas.id_bancos.val("0");
			viewcuentas.id_tipo_cuentas.val("0");
			viewcuentas.numero_cuentas.val("");	
			
			inhabilitar_elementos_panel_cuentas(); //inhabilitar los elementos en el panel de cuentas 
			
			$('.nav-tabs a[href="#panel_info"]').tab('show');
			
			try {
				obtener_informacion_solicitud();
			} catch (e) {
				// TODO: handle exception
				console.log('funccion no encontrada \n'+e) 
			}
			
		}
		
	}).fail( function( xhr, status, error ){
		swal({ title:"ERROR", text:" No se actualizo los datos", icon:"error", dangerMode:true });
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

var parametros_cuentas_solicitud	= function(){	
	return {
		"id_solicitud": view.hdn_id_solicitud.val(), //esta variable toma de la vista --solicitud
		"nombre_bancos": viewcuentas.id_bancos.find('option:selected').text(),
		"nombre_tipo_cuentas": viewcuentas.id_tipo_cuentas.val(),
		"numero_cuentas": viewcuentas.numero_cuentas.val()
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
		return true;
		
	}catch (e) {
		// TODO: handle exception
		//console.log("error no controlado Cuentas Bancarias");
		return false;
	}
	
}

