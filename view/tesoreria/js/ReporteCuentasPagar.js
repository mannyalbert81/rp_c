$(document).ready(function(){
	
	ConsultaReporteCuentasPagar();

})

function ConsultaReporteCuentasPagar(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCuentasPagar&action=ConsultaReporteCuentasPagar",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_cuantas_pagar_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#id_proveedores").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})


$("#consulta_cuantas_pagar_registrados_tbl").on('click','a.showpdf',function(event){
	let enlace = $(this);
	let _url = "index.php?controller=ReporteCuentasPagar&action=Reporte_Cuentas_Por_Proveedor&id_proveedores="+enlace.data().id;
	
	if ( enlace.data().id ) {
		
		window.open(_url,"_blank");
		
	}
	
	event.preventDefault();
})

