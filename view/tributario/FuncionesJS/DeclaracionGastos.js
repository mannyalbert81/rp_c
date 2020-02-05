$(document).ready(function(){
	//InsertarPresupuestos();


});




function InsertarDeclaracionGastos(){
	
	var _id_empleados = document.getElementById('id_empleados_1').value;
	var _anio_formulario_107 = document.getElementById('anio_formulario_107').value;
	var _ingresos_gravados_empleador = document.getElementById('ingresos_gravados_empleador').value;
	var _ingresos_otros_empleados = document.getElementById('ingresos_otros_empleados').value;
	var _ingresos_proyectados = document.getElementById('ingresos_proyectados').value;
	var _gastos_vivienda = document.getElementById('gastos_vivienda').value;
	var _gastos_educacion = document.getElementById('gastos_educacion').value;
	var _gastos_salud = document.getElementById('gastos_salud').value;
	var _gastos_vestimenta = document.getElementById('gastos_vestimenta').value;
	var _gastos_alimentacion = document.getElementById('gastos_alimentacion').value;
	var _ruc_agente_retencion = document.getElementById('ruc_agente_retencion').value;
	var _razon_social = document.getElementById('razon_social').value;

	
	
	var parametros = {
			id_empleados:_id_empleados,
			anio_formulario_107:_anio_formulario_107,
			ingresos_gravados_empleador:_ingresos_gravados_empleador,
			ingresos_otros_empleados:_ingresos_otros_empleados,
			ingresos_proyectados:_ingresos_proyectados,
			gastos_vivienda:_gastos_vivienda,
			gastos_educacion:_gastos_educacion,
			gastos_salud:_gastos_salud,
			gastos_vestimenta:_gastos_vestimenta,
			gastos_alimentacion:_gastos_alimentacion,
			ruc_agente_retencion:_ruc_agente_retencion,
			razon_social:_razon_social
			
	}
	
$.ajax({
		
		beforeSend:function(){},
		url:"index.php?controller=DeclaracionGastos&action=InsertarDeclaracionGastos", 
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.respuesta != undefined && datos.respuesta == 1){
			
			swal({
		  		  title: "Declaración",
		  		  text: "Guardado exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		
		  		});
			document.getElementById("frm_declaracion_gastos").reset();	
			document.getElementById("frm_declaracion").reset();
		
		}
		console.log(datos)
	
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
		
		
	});


event.preventDefault()

}





	