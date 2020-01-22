$(document).ready(function(){ 	 
	
	modCargaModulos();
});

function modCargaModulos(){
	 
	let $ddlModModulos = $("#mod_id_modulos");
	 $.ajax({
		 url:"index.php?controller=ProcesosMayorizacion&action=getModulosModal",
		 type:"POST",
		 dataType:"json",
		 data:null
	 }).done(function(x){
		 //console.log(x)
		 if( x.dataModulos != undefined ){
			 $ddlModModulos.empty();
			 let aModulos = x.dataModulos;
			 $ddlModModulos.append("<option value=\"0\">--Seleccione--</option>")
			 $.each(aModulos,function(i,v){
				 $ddlModModulos.append("<option value=\""+v.id_modulos+"\">"+v.nombre_modulos+"</option>");
			 })
		 }
		 
		 
	 }).fail(function(xhr,status,error){
		 let err = xhr.responseText;
		 console.log(err);
	 })
	 
}

function fnLoadProcesos(pagina=1){
	
	let $mod_modulo,$mod_anio,$mod_mes;
	
	$mod_modulo = $("#mod_id_modulos");
	$mod_anio	= $("#mod_anio_procesos");
	$mod_mes	= $("#mod_meses_procesos");
	
	parametros = {
			id_modulos:$mod_modulo.val(),
			anio_procesos:$mod_anio.val(),
			page:pagina
			}	
	
	$.ajax({
		url:"index.php?controller=ProcesosMayorizacion&action=listaProcesosMensuales",
		dataType:"json",
		type:"POST",
		data:parametros
	}).done(function(x){
		//console.log(x);
		$("#mod_tbl_procesos").find("tbody").empty();
		$("#mod_tbl_procesos").find("tbody").append(x.dataFilas);
		
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}


