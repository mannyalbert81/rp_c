$(document).ready(function(){
	periodoactual();
})

/***
 * consulta periodo actual de la entidad
 * @returns 
 * json
 */
function periodoactual(){
	$.ajax({
		url:'index.php?controller=BalanceComprobacion&action=buscaperiodo',
		type:'POST',
		dataType:'json',
		data:{term:$('#cedula_usuarios').val()}
	}).done(function(respuesta){		
		
		if(respuesta.mensaje == '1'){
			var datos = respuesta.datos[0];
			$('#anio_balance').append($('<option>', {value:datos.anio_periodo, text:datos.anio_periodo,selected:"selected"}));
			$('#estado_balance').val(datos.nombre_estado)
			
			 $("#mes_balance option").each(function(){
			        if ($(this).val() == datos.mes_periodo ){        
			        	$(this).attr('selected', 'selected')
			        }
			     });
		}
		
	}).fail( function( xhr , status, error ){
		 var err=xhr.responseText
		console.log(err)
	});
}



$('#form_balance_comprobacion').on('submit',function(event){
	
	var parametros = new FormData(this)	
	parametros.append('ajax','1');
	
	$.ajax({
			url:'index.php?controller=BalanceComprobacion&action=generarbalance',
			type:'POST',
			dataType:'json',
			data:parametros,
			contentType: false, 
	        processData: false, 
		}).done(function(respuesta){			

			
			if(respuesta.mensaje == 1 && !$.isEmptyObject(respuesta)){
				
				$('#tabla_balance_comprobacion ').empty();
				
				$("#tabla_balance_comprobacion").addClass('table');
				
				//codigos style="font-weight: bold;"
				                                                                                                                                                                                                                                                
				$("#tabla_balance_comprobacion").append('<thead><tr >'+
						'<th> </td>'+
						'<th align="center">CODIGO</td>' + 
					    '<th align="center">CUENTA</td>' + 
					    '<th>SUMA DEBE </td>'+
					    '<th>SUMA HABER </td>'+
					    '<th>SALDO ACREEDOR </td>'+
					    '<th>SALDO DEUDOR </td><tr></thead>');
				
				var $tbody = $('<tbody></tbody>');
				
				var nivel_cuenta = 0;
				
			    for (i = 0; i < respuesta.detalle.length; i++){
			    	
			    	nivel_cuenta = respuesta.detalle[i].nivel_plan_cuentas;
			    	
			    	var $fila = $('<tr></tr>');
			    	
			    	switch(nivel_cuenta){
			    	case '1':			    		
			    		$fila.append('<td align="left" ><a class="nivel1"  href="#">+</a></td>')
			    		break;
			    	case '2':
			    		$fila.addClass('nivel2 hide');
			    		break;
			    	case '3':
			    		$fila.addClass('nivel3 hide');
			    		break;			    	
			    	case '4':
			    		$fila.addClass('nivel4 hide');
			    		break;
			    	case '4':
			    		$fila.addClass('nivel5 hide');
			    		break;
		    		default:
		    			$fila.addClass('hide');
		    			$fila.append('<td align="left"></a></td>')
		    		
			    	}
			    	
			    	$fila.data('codigo', respuesta.detalle[i].codigo_plan_cuentas);
			    	
			    	$fila.append(
							'<td align="left" style="dislay: none;">'+respuesta.detalle[i].codigo_plan_cuentas+'</td>'+
						    '<td align="left" style="dislay: none;">'+respuesta.detalle[i].nombre_plan_cuentas+'</td>'+
						    '<td align="right" style="dislay: none;">'+respuesta.detalle[i].suma_debe_dbalance_comprobacion+'</td>'+
						    '<td align="right" style="dislay: none;">'+respuesta.detalle[i].suma_haber_dbalance_comprobacion+'</td>'+
						    '<td align="right" style="dislay: none;">'+respuesta.detalle[i].saldo_acreedor_dbalance_comprobacion+'</td>'+
						    '<td align="right" style="dislay: none;">'+respuesta.detalle[i].saldo_deudor_dbalance_comprobacion+'</td>')
			    	
			    	
			    	$tbody.append($fila);
			    	
			    }
			    	
			    	
			    
			    
			    $("#tabla_balance_comprobacion").append($tbody);
					    
			    $("#tabla_balance_comprobacion").append(
						'<tr>' + 
						'<td colspan="3" align="right">TOTAL</td>'+
					    '<td align="right" >'+respuesta.totales.totaldebe+'</td>'+
					    '<td align="right" >'+respuesta.totales.totalhaber+'</td>'+
					    '<td align="right" >'+respuesta.totales.saldoa+'</td>'+
					    '<td align="right" >'+respuesta.totales.saldod+'</td></tr>'); 
			}
			
			//console.log(respuesta);
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			console.log(err)
		});
	
	
	
	event.preventDefault()
})

$('.nivel1').on('click',function(e){alert('hola'); e.preventDefault()})

