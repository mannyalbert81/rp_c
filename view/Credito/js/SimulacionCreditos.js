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
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	var cuota_credito=$("#cuotas_credito").val();
	$.ajax({
	    url: 'index.php?controller=SimulacionCreditos&action=SimulacionCredito',
	    type: 'POST',
	    data: {
	    	monto_credito:monto,
	    	tasa_interes:interes,
	    	fecha_corte:fecha_corte,
	    	plazo_credito:cuota_credito
	    },
	})
	.done(function(x) {
		$("#tabla_amortizacion").html(x);
		
	})
	.fail(function() {
	    console.log("error");
	});
}

function GetCuotas()
{
	var monto=$("#monto_credito").val();
	Redondeo(monto);
	monto=$("#monto_credito").val();
	var interes=$("#tipo_credito").val();
	var fecha_corte=$("#fecha_corte").val();
	
	if(monto=="" || parseFloat(monto)<150)
		{
		$("#mensaje_monto_credito").text("Monto no valido");
		$("#mensaje_monto_credito").fadeIn("slow");
		$("#mensaje_monto_credito").fadeOut("slow");
		}
	
	if(monto>7000 && interes==12)
	{
	$("#mensaje_monto_credito").text("Monto no valido");
	$("#mensaje_monto_credito").fadeIn("slow");
	$("#mensaje_monto_credito").fadeOut("slow");
	}
	
	if(fecha_corte=="")
		{
		$("#mensaje_fecha").text("Escoja una fecha");
		$("#mensaje_fecha").fadeIn("slow");
		$("#mensaje_fecha").fadeOut("slow");
		}
	if(interes=="")
	{
	$("#mensaje_tipo_credito").text("Escoja una fecha");
	$("#mensaje_tipo_credito").fadeIn("slow");
	$("#mensaje_tipo_credito").fadeOut("slow");
	}
	if(monto!="" && monto>150 && interes!="" && fecha_corte!="")
		{
		if (interes==12 && monto>7000)
			{}
		else
			{
			$.ajax({
			    url: 'index.php?controller=SimulacionCreditos&action=GetCuotas',
			    type: 'POST',
			    data: {
			    	monto_credito:monto
			    },
			})
			.done(function(x) {
				$("#select_cuotas").html(x);
				SimularCredito();
				
				
			})
			.fail(function() {
			    console.log("error");
			});
			}
		
		}
	
}