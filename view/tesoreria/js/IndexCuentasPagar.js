$(document).ready(function(){
	
	
	init();
	load_retencion(1);
	cargaCuentasPagar(1);
	
		
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * 
 * @returns
 */
function init(){
	
	
	//mask: "9{1,10}.99",
	$("#impuestos_cuentas_pagar").hide();
	
	
	
}

/*******************************************************************************
 * function para mostrar las lista Cuentas Pagar
 * 
 * @returns
 */
function cargaCuentasPagar( pagina=1){
	
	let _buscador = $("#search_cuentas_pagar").val();
	
	$.ajax({
		beforeSend:function(x){ $("#divLoaderPage").addClass("loader") },
		url:"index.php?controller=CuentasPagar&action=ListaCuentasPagar",
		type:"POST",
		dataType:"html",
		data:{peticion:'ajax',search:_buscador,page:pagina}
	}).done(function(datos){		
		
		$("#cuentas_pagar_registrados").html(datos)
		
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		
	}).always(function(){
		$("#divLoaderPage").removeClass("loader");
	})
}

$("#cuentas_pagar_registrados").on('click','a.showpdf',function(event){
	let enlace = $(this);
	let _url = "index.php?controller=CuentasPagar&action=Reporte_Cuentas_Por_Pagar&id_cuentas_pagar="+enlace.data().id;
	
	if ( enlace.data().id ) {
		
		window.open(_url,"_blank");
		
	}
	
	event.preventDefault();
})
 

/* PARA DIV CON MENSAJES DE ERROR */
/* SE ACTIVAN AL ENFOCAR EN INPUT RELACIONADO */


 function load_retencion(pagina){

		   var search=$("#search_retencion").val();
	       var con_datos={
					  action:'ajax',
					  page:pagina
					  };
			  
	     $("#load_retencion").fadeIn('slow');
	     
	     $.ajax({
	               beforeSend: function(objeto){
	                 $("#load_retencion").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
	               },
	               url: 'index.php?controller=Retencion&action=consulta_retencion&search='+search,
	               type: 'POST',
	               data: con_datos,
	               success: function(x){
	                 $("#retencion_registrados_detalle").html(x);
	                 $("#load_retencion").html("");
	                 $("#tabla_retencion").tablesorter(); 
	                 
	               },
	              error: function(jqXHR,estado,error){
	                $("#retencion_registrados_detalle").html("Ocurrio un error al cargar la informacion de Detalle Retenciones..."+estado+"    "+error);
	              }
	            });

  }

$("#search_cuentas_pagar").on('keyup',function(){
	cargaCuentasPagar();
})






