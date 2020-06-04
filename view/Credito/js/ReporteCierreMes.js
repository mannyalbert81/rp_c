$(document).ready(function(){
	
	ConsultaReporteCierreMes();

})

function ConsultaReporteCierreMes(_page = 1){
	
	var buscador = $("#buscador").val();
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		url:"index.php?controller=ReporteCierreMes&action=ConsultaReporteCierreMes",
		type:"POST",
		data:{page:_page,search:buscador,peticion:'ajax'}
	}).done(function(datos){		
		console.log(datos);
		$("#consulta_cierre_mes_registrados_tbl").html(datos);		
		
	}).fail(function(xhr,status,error){
		
		var err = xhr.responseText
		console.log(err);
		
	}).always(function(){
		
		$("#divLoaderPage").removeClass("loader")
		
	})
	
}


$("#id_creditos_cierre_mes").on("keyup",function(){
	
	$(this).val($(this).val().toUpperCase());
})

 $("#btExportar").click(function(){
	
	 get_data_for_xls();
	
});

function get_data_for_xls()
{
	 var activeTab = $('.nav-tabs .active').text();
	 var search=$("#search").val();
	 	
	 
				var users ="activos";
				var con_datos={
						  search:search,
						  users:users,
						  action:'ajax'
						  };
				$.ajax({
					url:'index.php?controller=ReporteCierreMes&action=Exportar_usuariosExcel',
			        type : "POST",
			        async: true,			
					data: con_datos,
					success:function(data){
						
							
						if(data.length>3)
						   {
				  var array = JSON.parse(data);
				  var newArr = [];
				   while(array.length) newArr.push(array.splice(0,9));
				   console.log(newArr);
				   
				   var dt = new Date();
				   var m=dt.getMonth();
				   m+=1;
				   var y=dt.getFullYear();
				   var d=dt.getDate();
				   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
				   var wb =XLSX.utils.book_new();
				   wb.SheetNames.push("Reporte Creditos en Mora");
				   var ws = XLSX.utils.aoa_to_sheet(newArr);
				   wb.Sheets["Reporte Creditos en Mora"] = ws;
				   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
				   function s2ab(s) { 
			            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
			            var view = new Uint8Array(buf);  //create uint8array as viewer
			            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
			            return buf;    
				   }
			       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Reporte Creditos en Mora.xlsx');
					   }
				   else{
					   alert("No hay información para descargar");
				   }
					}
				});
				
}

