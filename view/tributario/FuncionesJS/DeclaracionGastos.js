$(document).ready(function(){ 	 
	  
});
function InsertarDeclaracionGastos(){
	
	
	let _cedula_empleados = document.getElementById('cedula_empleado').value;
	var _id_empleados = document.getElementById('id_empleados').value;
	var _nombre_empleados = document.getElementById('nombre_empleados').value;
	var _tag_103 = document.getElementById('tag_103').value;
	var _tag_104 = document.getElementById('tag_104').value;
	var _tag_105 = document.getElementById('tag_105').value;
	var _tag_106 = document.getElementById('tag_106').value;
	var _tag_107 = document.getElementById('tag_107').value;
	var _tag_108 = document.getElementById('tag_108').value;
	var _tag_109 = document.getElementById('tag_109').value;
	var _tag_110 = document.getElementById('tag_110').value;
	var _tag_111 = document.getElementById('tag_111').value;
	var _ruc = document.getElementById('ruc').value;
	var _razon_social = document.getElementById('razon_social').value;
	var _anio_formulario_107 = document.getElementById('anio_formulario_107').value;
	
	
	if(  _tag_103 == "" ||  _tag_103.length == 0 || isNaN(_tag_103) ){
		 $("html, body").animate({ scrollTop: $(tag_103).offset().top-120 }, 1000);
		 $("#tag_103").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_104  == "" ||  _tag_104 .length == 0 || isNaN(_tag_104) ){
		 $("html, body").animate({ scrollTop: $(tag_103).offset().top-120 }, 1000);
		 $("#tag_104").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_105 == "" ||  _tag_105.length == 0 || isNaN(_tag_105) ){
		 $("html, body").animate({ scrollTop: $(tag_105).offset().top-120 }, 1000);
		 $("#tag_105").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_106 == "" ||  _tag_106.length == 0 || isNaN(_tag_106) ){
		 $("html, body").animate({ scrollTop: $(tag_106).offset().top-120 }, 1000);
		 $("#tag_106").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_107 == "" ||  _tag_107.length == 0 || isNaN( _tag_107) ){
		 $("html, body").animate({ scrollTop: $(tag_107).offset().top-120 }, 1000);
		 $("#tag_107").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_108 == "" ||  _tag_108.length == 0 || isNaN(_tag_108)){
		 $("html, body").animate({ scrollTop: $(tag_108).offset().top-120 }, 1000);
		 $("#tag_108").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_109 == "" ||  _tag_109.length == 0 || isNaN(_tag_109)){
		 $("html, body").animate({ scrollTop: $(tag_109).offset().top-120 }, 1000);
		 $("#tag_109").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_110 == "" ||  _tag_110.length == 0 || isNaN(_tag_110)){
		 $("html, body").animate({ scrollTop: $(tag_110).offset().top-120 }, 1000);
		 $("#tag_110").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _tag_111 == "" ||  _tag_111.length == 0 || isNaN(_tag_111 )){
		 $("html, body").animate({ scrollTop: $(tag_111).offset().top-120 }, 1000);
		 $("#tag_111").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  ruc == "" ||  _tag_111.length == 0 ){
		 $("html, body").animate({ scrollTop: $(tag_111).offset().top-120 }, 1000);
		 $("#tag_111").notify("Digite un valor",{ position:"buttom"}); 
		 return false; 
	 }
	if(  _ruc == "" ||  _ruc.length == 0  ){
		 $("html, body").animate({ scrollTop: $(ruc).offset().top-120 }, 1000);
		 $("#ruc").notify("Digite su número de cédula",{ position:"buttom"}); 
		 return false; 
	 }
	
	if(  _razon_social == "" ||  _razon_social.length == 0 ){
		 $("html, body").animate({ scrollTop: $(razon_social).offset().top-120 }, 1000);
		 $("#razon_social").notify("Digite sus nombres y apellidos",{ position:"buttom"}); 
		 return false; 
	 }
	
	
	var parametros = {
			id_empleados:_id_empleados,
			cedula_empleado:_cedula_empleados,
			nombre_empleados:_nombre_empleados,
			tag_103:_tag_103,tag_104:_tag_104,
			tag_105:_tag_105,tag_106:_tag_106,
			tag_107:_tag_107,tag_108:_tag_108,
			tag_109:_tag_109,tag_110:_tag_110,
			tag_111:_tag_111,ruc:_ruc,
			razon_social:_razon_social, anio_formulario_107:_anio_formulario_107}
	
	
	$.ajax({
		
		beforeSend:function(){},
		url:"index.php?controller=DeclaracionGastos&action=InsertarDeclaracionGastos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.mensaje != undefined && datos.mensaje == 1){
			
			swal({
		  		  title: "Declaracion Gastos",
		  		  text: "Guardado exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		
		  		});
			
		}
			console.log(datos)
	
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
		
	});
	

event.preventDefault()

}





