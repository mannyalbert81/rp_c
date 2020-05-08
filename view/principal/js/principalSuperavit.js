$(document).ready(function(){
	

})


function fnVolverFormularioSuperavit(){
	
	var pnlResultadosSuperavit = $("#pnlResultadosSuperavit");
	pnlResultadosSuperavit.addClass("hidden"); //ocultar el panel de resultado mediante clase de bootstrap
	pnlResultadosSuperavit.find("table").empty();
	$("#hdnid_participes_padre_superavit").val("0");
	$("#pnlBusquedaSuperavit").removeClass("hidden"); //ocultar el panel de resultado mediante clase de bootstrap
	
}
function loadBusquedaSuperavit( pagina ){
	
	var incedula 	= $("#txtCedulaSuperavit");
	var incodigo	= $("#txtCodigoSuperavit");

	
	var params	= {
			page: pagina,
			cedula:incedula.val(),
			codigo: incodigo.val()
	};
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		complete:function(){$("#divLoaderPage").removeClass("loader")},
		url:"index.php?controller=PrincipalSuperavit&action=CargaSuperavit",
		dataType:"json",
		type:"POST",
		data:params
	}).done(function(x){
		$("#tblResultadosPrincipalSuperavit").empty();
		if( x.tabla != undefined ){
			
			if( x.tabla != "" ){
				$("#pnlBusquedaSuperavit").addClass("hidden");
				$("#pnlResultadosSuperavit").removeClass("hidden");
				$("#tblResultadosPrincipalSuperavit").append(x.tabla);
				$("#spanCantidadSuperavit").text("Se encontraon ( "+x.cantidadDatos+" ) registro/s" );
				$("#mod_paginacion_resultados_superavit").html(x.paginacion);
			}
		}
		
		console.log(x);
		
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

$("#btn_principal_superavit").on("click",function(){
	
	loadBusquedaSuperavit(1);	
	
})


function fnRegistro(btn){
	
	var btnObjeto = $(btn);
	var valor = btnObjeto.val();
	
	var vtnX	=  screen.width;
	var vtnY	= screen.height;
	vtnX = (vtnX*7)/10;
	vtnY = (vtnY/4)*3;
	var vtnurl 	= "index.php?controller=PrincipalSuperavit&action=index";
	var vtnoptions 	= "location=0,top=50,left=50,toolbar=0,menubar=0,titlebar=0,resizable=1,width="+vtnX+",height="+vtnY;
	var vtnId	= "vtnAporte";
	
	$("#hdnid_participes_padre_superavit").val(valor);
	var vtnAporte = window.open(vtnurl,vtnId,vtnoptions);
	
}

