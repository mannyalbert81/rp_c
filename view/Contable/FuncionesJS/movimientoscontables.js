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
    document.body.removeChild(form);
})

$("#div_movimientos").on('click','#genera_excel',function(){
	
	
	var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php?controller=MovimientosContable&action=generaReporteExcel");
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
    
    form.submit();    
    document.body.removeChild(form);
	
})
