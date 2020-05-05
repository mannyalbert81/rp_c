$(document).ready(function(){
	
	loadTipoPrestamos();
	loadEstadoPrestamos();
	loadEntidadPatronal();
	
	init();
})

function init(){
	try{
		$('#txtFSolicitud').daterangepicker({
			  autoUpdateInput: false
			}, function(start_date, end_date) {
			  $('#txtFSolicitud').val(start_date.format('YYYY/MM/DD')+' - '+end_date.format('YYYY/MM/DD'));
			});
		
		
		$("#pnlResultadosPrestamos").addClass("hidden");
		
	}catch (e) {
		console.log(e);
	}	
}

function loadTipoPrestamos(){	
	
	var $ddlTipo = $("#id_tipo_creditos");
	params = {};
	$ddlTipo.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaTipoPrestamos",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsTipo	= x.data;
			$ddlTipo.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipo,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipo.append( '<option value="'+value.id_tipo_creditos+'">'+value.nombre_tipo_creditos+'</option>' );
			})
					
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function loadEstadoPrestamos(){	
	
	var $ddlEstado = $("#id_estado_creditos");
	params = {};
	$ddlEstado.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaEstadoPrestamos",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsEstado	= x.data;
			$ddlEstado.append('<option value="0">--Seleccione--</option>' );
			$.each(rsEstado,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlEstado.append( '<option value="'+value.id_estado_creditos+'">'+value.nombre_estado_creditos+'</option>' );
			})			
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function loadEntidadPatronal(){	
	
	var $ddlEntidad = $("#id_entidad_patronal");
	params = {};
	$ddlEntidad.empty();
	$.ajax({
		url:"index.php?controller=PrincipalPrestamos&action=CargaEntidadPatronal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsEntidad	= x.data;
			$ddlEntidad.append('<option value="0">--Seleccione--</option>' );
			$.each(rsEntidad,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlEntidad.append( '<option value="'+value.id_entidad_patronal+'">'+value.nombre_entidad_patronal+'</option>' );
			})
					
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function fnVolverFormularioPrestamos(){
	
	var pnlResultadosPrestamos = $("#pnlResultadosPrestamos");
	pnlResultadosPrestamos.addClass("hidden"); //ocultar el panel de resultado mediante clase de bootstrap
	pnlResultadosPrestamos.find("table").empty();
	$("#hdnid_participes_padre_prestamos").val("0");
	$("#pnlBusquedaPrestamos").removeClass("hidden"); //ocultar el panel de resultado mediante clase de bootstrap
	
}
function loadBusquedaPrestamos( pagina ){
	
	var incedula 	= $("#txtCedula");
	var innombre			= $("#txtNombre");
	var inapellido			= $("#txtApellido");
	var intipocreditos			= $("#id_tipo_creditos");
	var infsolicitud		= $("#txtFSolicitud");
	var inestadocreditos			= $("#id_estado_creditos");
	var inentidadpatronal	= $("#id_entidad_patronal");
	
	var params	= {
			page: pagina,
			cedula:incedula.val(),
			nombre: innombre.val(),
			apellido: inapellido.val(),
			id_tipo_creditos: intipocreditos.val(),
			fsolicitud: infsolicitud.val(),
			id_estado_creditos: inestadocreditos.val(),
			id_entidad_patronal: inentidadpatronal.val()
	};
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		complete:function(){$("#divLoaderPage").removeClass("loader")},
		url:"index.php?controller=PrincipalPrestamos&action=CargaPrestamos",
		dataType:"json",
		type:"POST",
		data:params
	}).done(function(x){
		$("#tblResultadosPrincipalPrestamos").empty();
		if( x.tabla != undefined ){
			
			if( x.tabla != "" ){
				$("#pnlBusquedaPrestamos").addClass("hidden");
				$("#pnlResultadosPrestamos").removeClass("hidden");
				$("#tblResultadosPrincipalPrestamos").append(x.tabla);
				$("#spanCantidadPrestamos").text("Se encontraon ( "+x.cantidadDatos+" ) registro/s" );
				$("#mod_paginacion_resultados_prestamos").html(x.paginacion);
			}
		}
		
		console.log(x);
		
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

$("#btn_principal_prestamos").on("click",function(){
	
	loadBusquedaPrestamos(1);	
	
})


function fnRegistro(btn){
	
	var btnObjeto = $(btn);
	var valor = btnObjeto.val();
	
	var vtnX	=  screen.width;
	var vtnY	= screen.height;
	vtnX = (vtnX*7)/10;
	vtnY = (vtnY/4)*3;
	var vtnurl 	= "index.php?controller=PrincipalPrestamosSocios&action=index";
	var vtnoptions 	= "location=0,top=50,left=50,toolbar=0,menubar=0,titlebar=0,resizable=1,width="+vtnX+",height="+vtnY;
	var vtnId	= "vtnAporte";
	
	$("#hdnid_participes_padre_prestamos").val(valor);
	var vtnAporte = window.open(vtnurl,vtnId,vtnoptions);
	
}

