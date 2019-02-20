$(document).ready(function(){
	
	load_planes_cuenta	(1);

});

$("#buscar").click(function(){
	load_planes_cuenta	(1);
		});

$("#exportar").click(function(){
	
	get_data_for_xls();


});

	function get_data_for_xls()
	{
		 var codigo_plan_cuentas=$("#codigo_plan_cuentas").val();
		 var nombre_cuenta=$("#nombre_plan_cuentas").val();
		 var nivel_cuenta=$("#nivel_plan_cuentas").val();
		 var n_cuenta=$("#n_plan_cuentas").val()
		 
		 var con_datos={
			  codigo_plan_cuentas:codigo_plan_cuentas,
			  nombre_cuenta:nombre_cuenta,
			  nivel_cuenta:nivel_cuenta,
			  n_cuenta:n_cuenta,				  
			  action:'ajax'
			  };
		 
		 $.ajax({
				url:'index.php?controller=PlanCuentas&action=generar_Excel_planCuentas',
	            type : "POST",
	            async: true,			
				data: con_datos,
				success:function(data){
								
			  var array = JSON.parse(data);
			   var newArr = [];
			   while(array.length) newArr.push(array.splice(0,3));
			   for (var i=1; i<newArr.length; i++)
				   {
				   newArr[i][2]=parseFloat(newArr[i][2]);//newArr[i][2].replace(".", ",");
				   }
			   var dt = new Date();
			   var m=dt.getMonth();
			   m+=1;
			   var y=dt.getFullYear();
			   var d=dt.getDate();
			   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
			   var wb =XLSX.utils.book_new();
			   wb.SheetNames.push("Reporte Plan Cuentas");
			   var ws = XLSX.utils.aoa_to_sheet(newArr);
			   wb.Sheets["Reporte Plan Cuentas"] = ws;
			   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
			   function s2ab(s) { 
	                var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	                var view = new Uint8Array(buf);  //create uint8array as viewer
	                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	                return buf;    
			   }
		       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'ReporteCuentas'+fecha+'.xlsx');

			   
	    	  
				}
			});
		 
		 
	}
	
	function load_planes_cuenta(pagina){
		
		//iniciar variables
		 var codigo_plan_cuentas=$("#codigo_plan_cuentas").val();
		 var nombre_cuenta=$("#nombre_plan_cuentas").val();
		 var nivel_cuenta=$("#nivel_plan_cuentas").val();
		 var n_cuenta=$("#n_plan_cuentas").val();
	
		
		  var con_datos={
				  codigo_plan_cuentas:codigo_plan_cuentas,
				  nombre_cuenta:nombre_cuenta,
				  nivel_cuenta:nivel_cuenta,
				  n_cuenta:n_cuenta,				  
				  action:'ajax',
				  page:pagina
				  };


		
		$.ajax({
			url:'index.php?controller=PlanCuentas&action=Consulta',
            type : "POST",
            async: true,			
			data: con_datos,
			 beforeSend: function(objeto){
			$("#comprobantes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
			},
			success:function(data){
				$("#div_comprobantes").html(data);
				$("#comprobantes").html("");
				$("#tabla_plan_cuentas").tablesorter(); 
			}
		})
	}








$( "#codigo_plan_cuentas" ).autocomplete({
 source: 'index.php?controller=PlanCuentas&action=AutocompleteCodigoCuentas',
  	minLength: 1
 });
                    		
$("#codigo_plan_cuentas").focusout(function(){
                    					
$.ajax({
url:'index.php?controller=PlanCuentas&action=AutocompleteCodigoDevuelveNombre',
type:'POST',
dataType:'json',
data:{codigo_plan_cuentas:$('#codigo_plan_cuentas').val()}
}).done(function(respuesta){
                    		
$('#nombre_plan_cuentas').val(respuesta.nombre_plan_cuentas);
                    						
                    					
}).fail(function(respuesta) {
                    						  
$('#codigo_plan_cuentas').val("");
$('#nombre_plan_cuentas').val("");
$('#nivel_plan_cuentas').val("");
$('#n_plan_cuentas').val("");
                    						                    						
});
                    					
});
                    				
$( "#nombre_plan_cuentas" ).autocomplete({
		source: 'index.php?controller=PlanCuentas&action=AutocompleteNombreCuentas',
		minLength: 1
});

$("#nombre_plan_cuentas").focusout(function(){
	
	$.ajax({
		url:'index.php?controller=PlanCuentas&action=AutocompleteNombreDevuelveCodigo',
		type:'POST',
		dataType:'json',
		data:{nombre_plan_cuentas:$('#nombre_plan_cuentas').val()}
	}).done(function(respuesta){

		
		$('#codigo_plan_cuentas').val(respuesta.codigo_plan_cuentas);
		
		
	
	}).fail(function(respuesta) {
		
		$('#codigo_plan_cuentas').val("");
		$('#nombre_plan_cuentas').val("");
		$('#nivel_plan_cuentas').val("");
		$('#n_plan_cuentas').val("");
		                    						
	});
	
});   