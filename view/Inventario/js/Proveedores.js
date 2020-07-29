$(document).ready(function(){
	
      $(".cantidades1").inputmask();
      ListaProveedores();
      cargaBancos();
      cargaTipoProveedores();
      cargaTipoCuentas();
      init();
	  initControles();
      
      //InsertaProveedores
     
});

function generaTabla(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		paging: false,
		searching: false,
        pageLength: 10,
        responsive: true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"html5buttons">lfrtipB',      
        buttons: [
            
        ]

    });
}

/**FUNCIONES DE INICIO**/
/*
 * Inicio de funciones
 */
function init(){
	
	$("#id_bancos").select2({});
}



function initControles(){
	try {
		
		 $("#imagen_registro").fileinput({			
		 	showPreview: false,
	        showUpload: false,
	        elErrorContainer: '#errorImagen',
	        allowedFileExtensions: ["jpeg","jpg", "png", "gif"],
	        language: 'esp' 
		 });
		
	} catch (e) {
		// TODO: handle exception
		console.log("ERROR AL IMPLEMENTAR PLUGIN DE FILEUPLOAD");
	}
	
}

/**FUNCIONES PARA INICIO DE PAGINA*/
/*
 * FN PARA CARGA DE TIPO PROVEEDOR
 */
function cargaTipoProveedores(){
	
	let $tipoProveedor = $("#id_tipo_proveedores");
	
	$.ajax({
		url:"index.php?controller=Proveedores&action=cargaTipoProveedores",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		
		$tipoProveedor.empty();
		$tipoProveedor.append("<option value='0' >--Seleccione--</option>");
		
		let $hdTipoProveedor = $("#hd_tipo_proveedores").val();	
	   
		$.each(x.data, function(index, value) {
			if($hdTipoProveedor == value.id_tipo_proveedores){
				$tipoProveedor.append("<option value= " +value.id_tipo_proveedores +" selected >" + value.nombre_tipo_proveedores  + "</option>");
			}else{
				$tipoProveedor.append("<option value= " +value.id_tipo_proveedores +" >" + value.nombre_tipo_proveedores  + "</option>");
			}
				
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoProveedor.empty();
		$tipoProveedor.append("<option value='0' >--Seleccione--</option>");
	})
}

/*
 * FN PARA CARGA DE TIPO PROVEEDOR
 */
function cargaBancos(){
	
	let $bancos = $("#id_bancos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=cargaBancos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$bancos.empty();
		$bancos.append("<option value='0' >--Seleccione--</option>");
		let $hdBanco = $("#hd_bancos").val();
		
		$.each(datos.data, function(index, value) {
			if($hdBanco == value.id_bancos){
				$bancos.append("<option value= " +value.id_bancos +" selected >" + value.nombre_bancos  + "</option>");
			}else{
				$bancos.append("<option value= " +value.id_bancos +" >" + value.nombre_bancos  + "</option>");
			}
				
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$bancos.empty();
		$bancos.append("<option value='0' >--Seleccione--</option>");
	})
}


/*
 * FN PARA CARGA DE TIPO CUENTA
 */
function cargaTipoCuentas(){
	
	let $tipoCuentas = $("#id_tipo_cuentas");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=cargaTipoCuentas",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
		let $hdTipoCuenta = $("#hd_tipo_cuenta").val();	
		$.each(datos.data, function(index, value) {
			if($hdTipoCuenta == value.id_tipo_cuentas){
				$tipoCuentas.append("<option value= " +value.id_tipo_cuentas +" selected >" + value.nombre_tipo_cuentas  + "</option>");	
			}else{
				$tipoCuentas.append("<option value= " +value.id_tipo_cuentas +" >" + value.nombre_tipo_cuentas  + "</option>");	
			}
			
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$tipoCuentas.empty();
		$tipoCuentas.append("<option value='0' >--Seleccione--</option>");
	})
}

function ListaProveedores(pagina=1){
	
	let $listaProveedores = $("#tabla_datos_proveedores");
	$listaProveedores.html('');
	let $cantidadrespuesta = $("#cantidad_busqueda");
	let $busqueda = $("#txtbuscar");
	let parametros = {
			page:pagina,
			peticion:'',busqueda:$busqueda.val()
	}
	
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=Proveedores&action=ListaProveedores",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){		
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp; '+ datos.valores.cantidad);
		$listaProveedores.html(datos.tabladatos);
		
		generaTabla("tbl_tabla_proveedores");
		
	}).fail(function(xhr,status,error){
		let err = xhr.responseText;
		console.log(err)
		$cantidadrespuesta.html('<strong>Registros:</strong>&nbsp;  0');
		let _diverror = ' <div class="col-lg-12 col-md-12 col-xs-12"> <div class="alert alert-danger alert-dismissable" style="margin-top:40px;">';
			_diverror +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            _diverror += '<h4>Aviso!!!</h4> <b>Error en conexion a la Base de Datos</b>';
            _diverror += '</div></div>';
            
         $listaProveedores.html(_diverror);
	})
}

/** para funcion de busqueda en txt de busqueda */
$("#txtbuscar").on("keyup",function(){
	ListaProveedores();	
})

$("#GuardarProveedores").on("click",function(event){
	
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
	
	//para nombre proveedor
	let $nombre_proveedores = $("#nombre_proveedores");    	
	if( $nombre_proveedores.val().length == 0 || $nombre_proveedores.val() == '' ){
		$nombre_proveedores.notify("Agregue nombre",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para identificacion proveedor
	let $identificacion_proveedores = $("#identificacion_proveedores");    	
	if( $identificacion_proveedores.val().length == 0 || $identificacion_proveedores.val() == '' ){
		$identificacion_proveedores.notify("Agregue identificacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para contacto proveedor
	let $contactos_proveedores = $("#contactos_proveedores");    	
	if( $contactos_proveedores.val().length == 0 || $contactos_proveedores.val() == '' ){
		$contactos_proveedores.notify("Ingrese un Contacto",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para direccion proveedor
	let $direccion_proveedores = $("#direccion_proveedores");    	
	if( $direccion_proveedores.val().length == 0 || $direccion_proveedores.val() == '' ){
		$direccion_proveedores.notify("Ingrese direccion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para telefono proveedor
	let $telefono_proveedores = $("#telefono_proveedores");    	
	if( $telefono_proveedores.val().length == 0 || $telefono_proveedores.val() == '' ){
		$telefono_proveedores.notify("Ingrese telefono",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para email proveedor
	let $email_proveedores = $("#email_proveedores");    	
	if( $email_proveedores.val().length == 0 || $email_proveedores.val() == '' ){
		$email_proveedores.notify("Ingrese email",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	} 
	if(!regex.test($email_proveedores.val().trim())){
		$email_proveedores.notify("Ingrese email valido",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}	
	
	//para tipo proveedores
	let $tipoProveedores= $("#id_tipo_proveedores");    	
	if( $tipoProveedores.val() == 0 ){
		$tipoProveedores.notify("Seleccione Tipo proveedor",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para bancos
	let $idBancos= $("#id_bancos");    	
	if( $idBancos.val() == 0 ){
		$idBancos.notify("Seleccione Banco",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para tipo cuenta
	let $tipoCuenta= $("#id_tipo_cuentas");    	
	if( $tipoCuenta.val() == 0 ){
		$tipoCuenta.notify("Seleccione Tipo Cuentas",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para numero cuenta
	let $numeroCuenta = $("#numero_cuenta_proveedores");    	
	if( $numeroCuenta.val().length == 0 || $numeroCuenta.val() == '' ){
		$numeroCuenta.notify("Ingrese numero cuenta Proveedores",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	
	var inimagen_registro = $("#imagen_registro");
	if( inimagen_registro[0].files.length == 0){
		inimagen_registro.closest("tr").notify("Ingrese un Imagen",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para proveedores id
	let $idProveedores = $("#id_proveedores"); 
		
	let parametros ={
			id_proveedores: $idProveedores.val(),
			nombre_proveedores: $nombre_proveedores.val(),
			identificacion_proveedores: $identificacion_proveedores.val(),
			contactos_proveedores: $contactos_proveedores.val(),
			direccion_proveedores: $direccion_proveedores.val(),
			telefono_proveedores: $telefono_proveedores.val(),
			email_proveedores: $email_proveedores.val(),
			fecha_nacimiento_proveedores: '',
			id_tipo_proveedores: $tipoProveedores.val(),
			id_bancos: $idBancos.val(),
			id_tipo_cuentas: $tipoCuenta.val(),
			numero_cuenta_proveedores: $numeroCuenta.val(),
			imagen_registro:inimagen_registro[0].files[0];
	}
	
	$.ajax({
		url:"index.php?controller=Proveedores&action=AgregaProveedores",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if( x.respuesta == 1 ){    			
			swal({
    			title:"Grupos",
    			text: x.mensaje,
    			icon:"success"
    		})
		}
		
		limpiarCampos();
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		var mensaje = /<message>(.*?)<message>/.exec(err.replace(/\n/g,"|"))
		 	if( mensaje !== null ){
			 var resmsg = mensaje[1];
			 swal( {
				 title:"Error",
				 dangerMode: true,
				 text: resmsg.replace("|","\n"),
				 icon: "error"
				})
		 	}
	}).always(function(){
		ListaProveedores(1);
	})
	
	
	event.preventDefault();
})


/*FUNCIONES PARA LIMPIAR FORMULARIO*/
function limpiarCampos(){
	
	$("#nombre_proveedores").val("");    	
	$("#identificacion_proveedores").val("");    	
	$("#contactos_proveedores").val("");    	
	$("#direccion_proveedores").val("");    	
	$("#telefono_proveedores").val("");    	
	$("#email_proveedores").val("");    	
	$("#id_tipo_proveedores").val(0);    	
	$("#id_bancos").val(0);
	$('#id_bancos').val(0).trigger('change');
	$("#numero_cuenta_proveedores").val("");    	
	$("#id_tipo_cuentas").val(0);   
	
}

 
  function numeros(e){
      
      key = e.keyCode || e.which;
      tecla = String.fromCharCode(key).toLowerCase();
      letras = "0123456789";
      especiales = [8,37,39,46];
   
      tecla_especial = false
      for(var i in especiales){
      if(key == especiales[i]){
       tecla_especial = true;
       break;
          } 
      }
   
      if(letras.indexOf(tecla)==-1 && !tecla_especial)
          return false;
   }