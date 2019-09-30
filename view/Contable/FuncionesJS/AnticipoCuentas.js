$(document).ready(function(){ 	 
	getEmpleados();
});

function getEmpleados(){
	
	let $ddlEmpleados = $("#ddlempleados");
	
	$.ajax({
		 url:"index.php?controller=Empleados&action=ListarEmpleadosCuentas",
		 type:"POST",
		 dataType:"json",
		 data:null
	 }).done(function(x){
		 //console.log(x)		 
		 $ddlEmpleados.empty();
		 $ddlEmpleados.append("<option value=\"0\">--SELECCIONE--</option>");
		 if ( x.hasOwnProperty( 'datos' ) || ( x.datos != '' ) ) {
			 $.each(x.datos,function(index, value){
				 $ddlEmpleados.append("<option value=\""+value.id_empleados+"\">"+value.nombres_empleados+"</option>");
			 })
		 }		 
		 
	 }).fail(function(xhr,status,error){
		 console.log(xhr.responseText);
		 $ddlEmpleados.empty();
		 $ddlEmpleados.append("<option value=\"0\">--SELECCIONE--</option>");
	 })
	
}

function BuscaEmpleado(Objeto){
		
	let $elemento = $(Objeto);
	
	let $cedula	=$("#cedula_empleado"), 
		$nombres=$("#nombres_empleado"),
		$cargo	=$("#cargo_empleado"),
		$codigo_cuenta = $("#cuenta_base"),
		$codigo_cuenta_empleado = $("#cuenta_contable");
	
	$.ajax({
		 url:"index.php?controller=Empleados&action=BuscarEmpleadoCuentas",
		 type:"POST",
		 dataType:"json",
		 data:{id_empleados: $elemento.val(),codigo_cuenta:$codigo_cuenta.val()}
	 }).done(function(x){
		 console.log(x)		 
		 if ( x.empleado !== undefined ) {
			 
			 if(x.empleado.length >0){
				 
				 $cedula.val(x.empleado[0].numero_cedula_empleados);
				 $nombres.val(x.empleado[0].nombres_empleados);
				 $cargo.val(x.empleado[0].nombre_cargo);
				 
			 }else{
				 swal( {
					 title:"INFORMACION",
					 text: "EMPLEADO NO ENCONTRADO",
					 icon: "info"
					}) 
			 }
			 
			 if (  x.cuenta !== undefined ) {
	 			 
				 $codigo_cuenta_empleado.val(x.cuenta.codigo);
				
			 }			 
		 }
		 
		 if ( x.existe !== undefined ) {
			 
			 if(x.existe == 'EXISTE'){
				 
				 swal( {
					 title:"INFORMACION",
					 text: "empleado selecionado ya tiene una cuenta contable asignada ",
					 icon: "info"
					}) 
				 
			 }
			 
			 limpiaCampos();
			 
		 }
		 
	 }).fail(function(xhr,status,error){
		 console.log(xhr.responseText)
		 swal( {
			 title:"Error",
			 dangerMode: true,
			 text: "PROBLEMAS CON EL SERVIDOR",
			 icon: "error"
			})
		 
	 })
	
}

function generarCuentaContable(){
	
	let $empleado = $("#ddlempleados"),
		$cuenta	  = $("#cuenta_contable");
	
	if( $empleado.val() == 0 ){
		
		$empleado.notify("Seleccione un empleado",{ position:"buttom left", autoHideDelay: 2000});		
		return false;
		
	}
	
	if( $cuenta.val().trim() == '' ){
		
		$cuenta.notify("No se determino cuenta contable para empleado",{ position:"buttom left", autoHideDelay: 2000});		
		return false;
		
	}
	
	$.ajax({
		 url:"index.php?controller=Empleados&action=IngresarCuenta",
		 type:"POST",
		 dataType:"json",
		 data:{id_empleados: $empleado.val(),cuenta_registrar:$cuenta.val()}
	 }).done(function(x){
		
		 swal( {
			 title:"EXITO",
			 text: "Cuenta Contable Generada",
			 icon: "success"
			});
		 
		 limpiaCampos();
		 
	 }).fail(function(xhr,status,error){
		 console.log(xhr.responseText)
		 swal( {
			 title:"Error",
			 dangerMode: true,
			 text: "al generar cuenta contable",
			 icon: "error"
			})
		 
	 })
	 
	 return false;
}

function limpiaCampos(){
	
	$("#cuenta_contable").val("");
	$("#cargo_empleado").val("");
	$("#nombres_empleado").val("");
	$("#cedula_empleado").val("");
	$("#ddlempleados").val(0);

}
