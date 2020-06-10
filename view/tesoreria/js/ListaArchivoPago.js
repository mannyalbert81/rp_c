var listaArchivos = []; // variable que permite el almacenamiento de datos sobre archivo mostrado
var CantidadArchivo = 0;
$(document).ready(function(){
		
	init();	
	loadBancosLocal();
	loadTipoArchivo();
	
})

/*******************************************************************************
 * funcion para iniciar el formulario
 * dc 2019-07-03
 * @returns
 */
function init(){	
	
	//$("#genera_transferencia").attr("disabled",true);
	$('#fecha_proceso').inputmask("9999/99/99", {placeholder: '____/__/__',clearIncomplete:true });
	
	$("#generar_archivo_pago").attr("disabled",true); //metodo para desabilitar boton de generacion de archivo
	
}


/***
 * @desc funcion para traer la chequera de la entidad
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadBancosLocal(){	
	
	var $ddlChequera = $("#id_bancos_local");
	params = {};
	$ddlChequera.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaBancosLocal",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsChequera = x.data;
			$ddlChequera.append('<option value="0">--Seleccione--</option>' );
			$.each(rsChequera,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlChequera.append( '<option value="'+value.id_bancos+'">'+value.nombre_bancos+'</option>' );
			})
		}
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

/***
 * @desc funcion para traer el tipo archivo 
 * @param none
 * @retuns void
 * @ajax si 
 */
function loadTipoArchivo(){	
	
	var $ddlTipoArchivo = $("#id_tipo_archivo_pago");
	params = {};
	$ddlTipoArchivo.empty();
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=CargaTipoArchivo",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		if( x.data != undefined && x.data != null ){
			var rsTipoArchivo = x.data;
			$ddlTipoArchivo.append('<option value="0">--Seleccione--</option>' );
			$.each(rsTipoArchivo,function(index, value){
				//console.log('index -->'+index+'   Value ---> '+value.id_bancos);
				$ddlTipoArchivo.append( '<option value="'+value.id_tipo_pago_archivo+'">'+value.nombre_tipo_pago_archivo+'</option>' );
			})
		}
		console.log(x);
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
}

function FormularioPost(url,target,params){
	 
	 var form = document.createElement("form");
	 form.setAttribute("id", "fromPost");
     form.setAttribute("method", "post");
     form.setAttribute("action", url);
     form.setAttribute("target", target);

     for (var i in params) {
         if (params.hasOwnProperty(i)) {
             var input = document.createElement('input');
             input.type = 'hidden';
             input.name = i;
             input.value = params[i];
             form.appendChild(input);
         }
     }
     
     document.body.appendChild(form);
     
     form.submit();
     
     document.body.removeChild(form);
}

/***
 * @desc funcion que permite mostrar los datos 
 * @returns
 */
function buscarDatosArchivoPago(){
	
	var tipoArchivo 	= $("#id_tipo_archivo_pago");
	var fechaProceso	= $("#fecha_proceso");
	var bancoPago		= $("#id_bancos_local");
	
	/** validaciones para busqueda dc 2020/06/10 **/
	if( tipoArchivo.val() == "" || tipoArchivo.val() == "0" )
	{
		tipoArchivo.notify( "Seleccione Tipo Archivo", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( fechaProceso.val().includes("_")  || fechaProceso.val() == "" )
	{
		fechaProceso.notify( "Ingrese Fecha Proceso", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( bancoPago.val() == "" || bancoPago.val() == "0"  )
	{
		bancoPago.notify( "Seleccione Banco ", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	params = {fecha_proceso:fechaProceso.val(), id_tipo_archivo_pago:tipoArchivo.val(), id_bancos:bancoPago.val()};
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=showArchivoPago",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		var tblArchivo = $("#tblListadoArchivoPago");
		tblArchivo.empty();
		if( x.tabla != undefined && x.tabla != null ){	
			
			if( parseInt( x.cantidadDatos) > 0 )
			{
				$("#div_resultados_archivo_pago").removeClass("hidden");					
				tblArchivo.append(x.tabla);
				$("#mod_total_archivo").text(x.cantidadDatos);
				$("#generar_archivo_pago").attr("disabled",false);
				$("#mod_paginacion_archivo").html(x.paginacion); //se establece la paginacion del archivo creado
				
			}else
			{
				$("#div_resultados_archivo_pago").addClass("hidden");
			}		
			
		}
		
	}).fail( function(xhr,status,error){
		console.log(xhr.responseText);
	})
	
}
 
$("#buscar_archivo_pago").on("click",function(event){
	
	buscarDatosArchivoPago();
	
})


$("#generar_archivo_pago").on("click",function(event){
	
	var tipoArchivo 	= $("#id_tipo_archivo_pago");
	var fechaProceso	= $("#fecha_proceso");
	var bancoPago		= $("#id_bancos_local");
		
	/** validaciones para busqueda dc 2020/06/10 **/
	if( tipoArchivo.val() == "" || tipoArchivo.val() == "0" )
	{
		tipoArchivo.notify( "Seleccione Tipo Archivo", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( fechaProceso.val().includes("_")  || fechaProceso.val() == "" )
	{
		fechaProceso.notify( "Ingrese Fecha Proceso", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	if( bancoPago.val() == "" || bancoPago.val() == "0"  )
	{
		bancoPago.notify( "Seleccione Banco ", { position:"buttom left", autoHideDelay: 2000} );	
		return false;
	}
	
	params = {fecha_proceso:fechaProceso.val(), id_tipo_archivo_pago:tipoArchivo.val(), id_bancos:bancoPago.val()};
	$.ajax({
		url:"index.php?controller=ArchivoPago&action=GenerarArchivoPago",
		dataType:"json",
		type:"POST",
		data: params
	}).done( function(x){
		
		if( x.estatus != undefined ){
			
			if(x.estatus == "OK"){
				
				var urlWeb  = "index.php?controller=ArchivoPago&action=DescargarArchivoPago";
				var starget = "_blank";
				var sFile   = x.urlFile;
				var sNomFile= x.nombreFile;
				var params  = {urlFile:sFile,nombreFile:sNomFile};
				FormularioPost(urlWeb,starget,params); //este metodo manda ha descragar el archivo generado por el usuario
				
				swal( {
					 title:"ARCHIVO RECAUDACIONES",
					 dangerMode: false,
					 text: " Archivo generado ",
					 icon: "info"
					})
				
			}
			
			if(x.estatus == "ERROR"){
				swal({title:"ARCHIVO ERROR",text:x.mensaje,icon:x.icon});
			}
		}
		
		//console.log(x);
	}).fail( function(xhr,status,error){
		swal( {
			 title:"ARCHIVO RECAUDACIONES",
			 dangerMode: true,
			 text: " Error con el Servidor Llamar al Administrador ",
			 icon: "error"
			})
		console.log(xhr.responseText);
	})
	
})



