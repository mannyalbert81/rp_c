$(document).ready( function (){
});

function Redondeo(monto)
{
	monto=$("#monto_credito").val();
	var residuo=monto%10;
	if (residuo>=5 && monto>100)
		{
		monto=parseFloat(monto)+parseFloat(10-residuo);
		}
	else
		{
		monto=parseFloat(monto)-parseFloat(residuo);
		}
		
	$("#monto_credito").val(monto);
	
}

function SimularCredito()
{
	var monto=$("#monto_credito").val();
	Redondeo(monto);
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=SimulacionCredito',
	    type: 'POST',
	    data: {
	    	monto_credito:monto,
	    	tasa_interes:interes,
	    	fecha_corte:fecha_corte
	    },
	})
	.done(function(x) {
		console.log(x);
		$("#tabla_amortizacion").html(x);
		
	})
	.fail(function() {
	    console.log("error");
	});
}