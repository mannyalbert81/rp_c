$(document).ready(function(){
	
})

$("#buscarmovimientos").on('click',function(){
	
	let _anio_movimientos = $('#anio_movimientos').val();
	let _mes_movimientos = $('#mes_movimientos').val();
	$("#div_movimientos").html('');
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=MovimientosContable&action=generaReporte",
		type:"POST",
		dataType:"json",
		data:{mes_movimientos:_mes_movimientos,anio_movimientos:_anio_movimientos}
	}).done(function(x){
	
		let respuesta = "";
		
		if( x.hasOwnProperty('error') && x.error != '' ){
			
			respuesta += x.error
			
		}
		if( x.hasOwnProperty('tabla_error') && x.tabla_error != '' ){
			
			respuesta += x.tabla_error
			
		}
		
		$("#div_movimientos").html(respuesta);
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err);
	})
})

$("#div_movimientos").on('click','#genera_pdf',function(){
	
	//swal({text:"llego"});
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=MovimientosContable&action=generaReportepdf");
    form.setAttribute("target", "_blank");
    
    //tomo datos que van por post
    let _anio_movimiento = $("#anio_movimientos").val();
    let _mes_movimiento = $("#mes_movimientos").val();
    
    //genera datos pa enviar por post
    var params = { "anio_movimientos":_anio_movimiento,"mes_movimientos" : _mes_movimiento };
    
    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = params[i];
            form.appendChild(input);
        }
    }
    
    document.body.appendChild(form);
    
    //note I am using a post.htm page since I did not want to make double request to the page 
   //it might have some Page_Load call which might screw things up.
    //window.open("post.htm", name, windowoption);
    
    form.submit();    
    //document.body.removeChild(form);
})

$("#div_movimientos").on('click','#genera_excel',function(){
	
	var users ="activos";
	//tomo datos que van por post
    let _anio_movimiento = $("#anio_movimientos").val();
    let _mes_movimiento = $("#mes_movimientos").val();
    let _nombre_arhivo = "MovimientosContables_"+_anio_movimiento+_mes_movimiento;
    
	var con_datos={	mes_movimientos:_mes_movimiento,
					anio_movimientos:_anio_movimiento
				  };
	
	$.ajax({
			url:'index.php?controller=MovimientosContable&action=generaReporteXls',
	        type : "POST",
	        dataType : "json",			
			data: con_datos
		}).done( function( xls ){
				
			console.log(xls);
			var newArr = [];
			var cabecera = ['A1','B1','C1','D1','E1'];
			
			newArr.push(xls.cabecera);			
			var i = 0;
			while(i < xls.detalle.length ){ newArr.push(xls.detalle[i]); i++;}
			
			var wb =XLSX.utils.book_new();
			wb.SheetNames.push("MovimientosContables");
			var ws = XLSX.utils.aoa_to_sheet(newArr);
			
			var stilo = {
				    fill: { bgColor: {rgb:  "FFFFAA000"}}, 
				    font: { family: "Arial", sz: 18, bold: true, color: { rgb: "#FF444444"}}
				};
			
			$.each(cabecera,function(i,v){
				let label = v;
				ws[label].s =  stilo;
			})
			
			/*$.each(cabecera,function(i,v){
				let label = v;
				console.log(ws[label])
			})*/
			
			wb.Sheets["MovimientosContables"] = ws;
			
			var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary',cellStyles:true});
			
			function s2ab(s) { 
	            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	            var view = new Uint8Array(buf);  //create uint8array as viewer
	            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	            return buf;    
			}
			
	       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), _nombre_arhivo+'.xlsx');
		  
		}).fail(function(xhr,status,error){
			let err = xhr.responseText
			console.log(err)
		});
	
   
})

$("#div_movimientosASSAD").on('click','#genera_excel',function(){
	
	var users ="activos";
	//tomo datos que van por post
    let _anio_movimiento = $("#anio_movimientos").val();
    let _mes_movimiento = $("#mes_movimientos").val();
    let _nombre_arhivo = "MovimientosContables_"+_anio_movimiento+_mes_movimiento;
    
	var con_datos={	mes_movimientos:_mes_movimiento,
					anio_movimientos:_anio_movimiento
				  };
	
	$.ajax({
			url:'index.php?controller=MovimientosContable&action=generaReporteXls',
	        type : "POST",
	        dataType : "json",			
			data: con_datos
		}).done( function( xls ){
				console.log(xls)
			
			var newArr = [];
				//['A1','B1','C1','D1','E1']
			var cabecera = [['A1'],['B1'],['C1'],['D1'],['E1']];
			
			newArr.push(xls.cabecera);
			
			var ws = XLSX.utils.aoa_to_sheet(cabecera);
			XLSX.utils.sheet_add_json(ws, xls.detalle, {});
									
			var wb =XLSX.utils.book_new();
			wb.SheetNames.push("MovimientosContables");			
			wb.Sheets["MovimientosContables"] = ws;
			var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary',cellStyles:true});
			
			function s2ab(s) { 
	            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	            var view = new Uint8Array(buf);  //create uint8array as viewer
	            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	            return buf;    
			}
			
	       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), _nombre_arhivo+'.xlsx');
		  
		}).fail(function(xhr,status,error){
			let err = xhr.responseText
			console.log(err)
		});
	
   
})

$("#div_movimientosold").on('click','#genera_excel',function(){
	
	var users ="activos";
	//tomo datos que van por post
    let _anio_movimiento = $("#anio_movimientos").val();
    let _mes_movimiento = $("#mes_movimientos").val();
    let _nombre_arhivo = "MovimientosContables_"+_anio_movimiento+_mes_movimiento;
    
	var con_datos={	mes_movimientos:_mes_movimiento,
					anio_movimientos:_anio_movimiento
				  };
	
	$.ajax({
			url:'index.php?controller=MovimientosContable&action=generaReporteXls',
	        type : "POST",
	        dataType : "json",			
			data: con_datos
		}).done( function( xls ){
				
			console.log(xls);
			var newArr = [];
			var cabecera = ['A1','B1','C1','D1','E1'];
			
			newArr.push(xls.cabecera);			
			var i = 0;
			while(i < xls.detalle.length ){ newArr.push(xls.detalle[i]); i++;}
			
			var wb =XLSX.utils.book_new();
			wb.SheetNames.push("MovimientosContables");
			var ws = XLSX.utils.aoa_to_sheet(newArr);
			
			var stilo = {
				    fill: { bgColor: {rgb:  "FFFFAA000"}}, 
				    font: { family: "Arial", sz: 18, bold: true, color: { rgb: "#FF444444"}}
				};
			
			$.each(cabecera,function(i,v){
				let label = v;
				ws[label].s =  stilo;
			})
			
			/*$.each(cabecera,function(i,v){
				let label = v;
				console.log(ws[label])
			})*/
			
			wb.Sheets["MovimientosContables"] = ws;
			
			var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary',cellStyles:true});
			
			function s2ab(s) { 
	            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	            var view = new Uint8Array(buf);  //create uint8array as viewer
	            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	            return buf;    
			}
			
	       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), _nombre_arhivo+'.xlsx');
		  
		}).fail(function(xhr,status,error){
			let err = xhr.responseText
			console.log(err)
		});
	
   
})

function genXLS(){
	
	 var createXLSLFormatObj = [];
	 
     /* XLS Head Columns */
     var xlsHeader = ["1", "2","3","4","5"];

     /* XLS Rows Data */
     var xlsRows = [
    	 ["1.4.01.05.08.", "Por Cobrar Banco Pichincha", "26.789,91", "0,00", "26.789,91"],
    	 ["1.4.01.05.09.", "Por Cobrar Banco Ruminahui", "23.077,74", "0,00", "23.077,74"],
    	 ["1.4.01.05.12.", "Por Cobrar Banco Solidario", "9.062,75", "0,00", "9.062,75"],
    	 ["1.4.01.05.14.", "Por Cobrar Banco de Loja", "3.445,76", "0,00", "3.445,76"],
    	 ["1.4.01.05.26.", "Por Cobrar Produbanco Grupo Promerica", "106.562,68", "0,00", "106.562,68"],
    	 ["1.4.01.05.27.", "Por Cobrar Bco. Bolivariano", "80.933,22", "0,00", "80.933,22"],
    	 ["1.4.01.05.29.", "Por Cobrar Mutualista Pichincha", "9.493,85", "0,00", "9.493,85"],
    	 ["1.4.01.05.31.", "Por Cobrar Coop. Tulcán Ltda.", "4.287,51", "0,00", "4.287,51"],
    	 ["1.4.01.05.32.", "Por Cobrar Coop. Ahorro y Crédito JEP", "54.391,76", "0,00", "54.39"],
     ];


     createXLSLFormatObj.push(xlsHeader);
     $.each(xlsRows, function(index, value) {
         var innerRowData = [];
         //$("tbody").append('<tr><td>' + value + '</td><td>' + value.FullName + '</td></tr>');
         $.each(value, function(ind, val) {

             innerRowData.push(val);
         });
         createXLSLFormatObj.push(innerRowData);
     });


     /* File Name */
     var filename = "FreakyJSON_To_XLS.xlsx";

     /* Sheet Name */
     var ws_name = "FreakySheet";

     if (typeof console !== 'undefined') console.log(new Date());
     
     var wb = XLSX.utils.book_new(),
         ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
     
     //ws.write('C1', 'Cost', bold)
     
     
     var range = XLSX.utils.decode_range(ws['!ref']);
		for (let rowNum = range.s.r; rowNum <= range.e.r; rowNum++) {
		    // Example: Get second cell in each row, i.e. Column "B"
		    const secondCell = ws[XLSX.utils.encode_cell({r: rowNum, c: 1})];
		    // NOTE: secondCell is undefined if it does not exist (i.e. if its empty)
		    console.log(secondCell); // secondCell.v contains the value, i.e. string or number
		}

     /* Add worksheet to workbook */
     XLSX.utils.book_append_sheet(wb, ws, ws_name);

     /* Write workbook and Download */
     if (typeof console !== 'undefined') console.log(new Date());
     XLSX.writeFile(wb, filename);
     if (typeof console !== 'undefined') console.log(new Date());
}







