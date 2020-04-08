$(document).ready(function(){
	
	loadEstadoParticipes();
	loadEntidadPatronal();
	
	init();
})

function init(){
	try{
		
		//$('#txtFNacimiento').daterangepicker();
		//$('#txtFIngreso').daterangepicker();
		//$('#txtFBaja').daterangepicker();
		
		$('#txtFNacimiento').daterangepicker({
			  autoUpdateInput: false
			}, function(start_date, end_date) {
			  $('#txtFNacimiento').val(start_date.format('YYYY/MM/DD')+' - '+end_date.format('YYYY/MM/DD'));
			});
		
		$('#txtFIngreso').daterangepicker({
			  autoUpdateInput: false
			}, function(start_date, end_date) {
			  $('#txtFIngreso').val(start_date.format('YYYY/MM/DD')+' - '+end_date.format('YYYY/MM/DD'));
			});
		
		$('#txtFBaja').daterangepicker({
			  autoUpdateInput: false
			}, function(start_date, end_date) {
			  $('#txtFBaja').val(start_date.format('YYYY/MM/DD')+' - '+end_date.format('YYYY/MM/DD'));
			});
		
		$("#pnlResultados").addClass("hidden");
		
	}catch (e) {
		// TODO: handle exception
		console.log(e);
	}	
}

/***
 * @desc funcion para traer el estado de los participes
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadEstadoParticipes(){	
	
	var $ddlEstado = $("#id_estado_participes");
	params = {};
	$ddlEstado.empty();
	$.ajax({
		url:"index.php?controller=PrincipalBusquedas&action=CargaEstadoParticipes",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsEstado	= x.data;
			$ddlEstado.append('<option value="0">--Seleccione--</option>' );
			$.each(rsEstado,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlEstado.append( '<option value="'+value.id_estado_participes+'">'+value.nombre_estado_participes+'</option>' );
			})			
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer entidades patronales
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadEntidadPatronal(){	
	
	var $ddlEntidad = $("#id_entidad_patronal");
	params = {};
	$ddlEntidad.empty();
	$.ajax({
		url:"index.php?controller=PrincipalBusquedas&action=CargaEntidadPatronal",
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

function loadBusquedaPrincipal( pagina ){
	
	var inidentificacion 	= $("#txtIdentificacion");
	var innombres			= $("#txtNombres");
	var inapellidos			= $("#txtApellidos");
	var incargo				= $("#txtCargo");
	var infnacimiento		= $("#txtFNacimiento");
	var inecivil			= $("#ddlEstadoCivil");
	var ingenero			= $("#ddlGenero");
	var indireccion			= $("#txtDireccion");
	var intelefono			= $("#txtTelefono");
	var infingreso			= $("#txtFIngreso");
	var infbaja				= $("#txtFBaja");
	var inestado			= $("#id_estado_participes");
	var inentidadpatronal	= $("#id_entidad_patronal");
	
	var params	= {
			page: pagina,
			identificacion:inidentificacion.val(),
			nombres: innombres.val(),
			apellidos: inapellidos.val(),
			cargo: incargo.val(),
			fnacimiento: infnacimiento.val(),
			estado_civil: inecivil.val(),
			genero: ingenero.val(),
			direccion: indireccion.val(),
			telefono: intelefono.val(),
			fingreso: infingreso.val(),
			fbaja: infbaja.val(),
			id_estado_participes: inestado.val(),
			id_entidad_patronal: inentidadpatronal.val()
	};
	
	$.ajax({
		beforeSend:function(){$("#divLoaderPage").addClass("loader")},
		complete:function(){$("#divLoaderPage").removeClass("loader")},
		url:"index.php?controller=PrincipalBusquedas&action=CargaBusqueda",
		dataType:"json",
		type:"POST",
		data:params
	}).done(function(x){
		$("#tblResultadosPrincipal").empty();
		if( x.tabla != undefined ){
			
			if( x.tabla != "" ){
				$("#pnlBusqueda").addClass("hidden");
				$("#pnlResultados").removeClass("hidden");
				$("#tblResultadosPrincipal").append(x.tabla);
				$("#spanCantidad").text("Se encontraon ( "+x.cantidadDatos+" ) socio/s" );
				$("#mod_paginacion_resultados").html(x.paginacion);
			}
		}
		
		console.log(x);
		
	}).fail(function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

$("#btn_principal_busqueda").on("click",function(){
	
	loadBusquedaPrincipal(1);	
	
})

function fnRegistroAportesManuel(btn){
	
	var btnObjeto = $(btn);
	var valor = btnObjeto.val();
	
	var vtnX	=  screen.width;
	var vtnY	= screen.height;
	vtnX = (vtnX*7)/10;
	vtnY = (vtnY/4)*3;
	var vtnurl 	= "index.php?controller=PrincipalBusquedasSocios&action=index";
	var vtnoptions 	= "location=0,top=50,left=50,toolbar=0,menubar=0,titlebar=0,resizable=1,width="+vtnX+",height="+vtnY;
	var vtnId	= "vtnAporte";
	
	$("#hdnid_participes_padre").val(valor);
	var vtnAporte = window.open(vtnurl,vtnId,vtnoptions);
	
}
