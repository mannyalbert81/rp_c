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

function generaTabla(ObjTabla){	
	
	$("#"+ObjTabla).DataTable({
		paging: false,
		searching: false,
		scrollX: true,
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

/**FUNCIONES PARA INICIO DE PAGINA*/
/*
 * FN PARA CARGA DE TIPO PROVEEDOR
 */
function cargaTipoProveedores(){
	
	let $tipoProveedor = $("#id_tipo_proveedores");
	
	$.ajax({
		url:"index.php?controller=TesProveedores&action=cargaTipoProveedores",
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
		url:"index.php?controller=TesProveedores&action=cargaBancos",
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
		url:"index.php?controller=TesProveedores&action=cargaTipoCuentas",
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
		url:"index.php?controller=TesProveedores&action=ListaProveedores",
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

/***
 * @desc funcion para validar la forma de pago
 * @returns void
 */
function validaFormaPago(){
	$forma_pago = $("#forma_pago");
	if( $forma_pago.val()=="transferencia" ){
		$("#id_bancos").attr('disabled',false);
		$("#id_tipo_cuentas").attr('disabled',false);
		$("#numero_cuenta_proveedores").attr('disabled',false);
	}else{
		$("#id_bancos").attr('disabled',true);
		$("#id_tipo_cuentas").attr('disabled',true);
		$("#numero_cuenta_proveedores").attr('disabled',true);
	}
}

/** para funcion de busqueda en txt de busqueda */
$("#txtbuscar").on("keyup",function(){
	ListaProveedores();	
})

$("#GuardarProveedores").on("click",function(event){
	
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
	
	//para tipo Indentificacion SRI
	let $tipoIdentificacion = $("#tipo_identificacion_proveedores");    	
	if( $tipoIdentificacion.val() == '0' || $tipoIdentificacion.val() == null || $tipoIdentificacion.val() == ''){
		$tipoIdentificacion.notify("Seleccione Tipo Identificacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para tipo proveedores
	let $tipoProveedores= $("#id_tipo_proveedores");    	
	if( $tipoProveedores.val() == 0 ){
		$tipoProveedores.notify("Seleccione Tipo proveedor",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para identificacion proveedor
	let $identificacion_proveedores = $("#identificacion_proveedores");    	
	if( $identificacion_proveedores.val().length == 0 || $identificacion_proveedores.val() == '' ){
		$identificacion_proveedores.notify("Agregue identificacion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para nombre proveedor
	let $nombre_proveedores = $("#nombre_proveedores");    	
	if( $nombre_proveedores.val().length == 0 || $nombre_proveedores.val() == '' ){
		$nombre_proveedores.notify("Agregue nombre",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para razon social
	let $razon_social = $("#razon_social_proveedores");    	
	if( $tipoIdentificacion.val() == "04" && $razon_social.val() == "" ){
		$razon_social.notify("Digite Razon Social",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para contacto proveedor
	let $contactos_proveedores = $("#contactos_proveedores");    	
	/*if( $contactos_proveedores.val().length == 0 || $contactos_proveedores.val() == '' ){
		$contactos_proveedores.notify("Ingrese un Contacto",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}*/
	
	//para direccion proveedor
	let $direccion_proveedores = $("#direccion_proveedores");    	
	if( $direccion_proveedores.val().length == 0 || $direccion_proveedores.val() == '' ){
		$direccion_proveedores.notify("Ingrese direccion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para telefono y celular proveedor
	let $telefono_proveedores = $("#telefono_proveedores");   
	let $celular_proveedores = $("#celular_proveedores");   
	if( ( $telefono_proveedores.val().length == 0 || $telefono_proveedores.val() == '' ) && ( $celular_proveedores.val().length == 0 || $celular_proveedores.val() == '' )  ){
		$telefono_proveedores.notify("Ingrese telefono",{ position:"buttom left", autoHideDelay: 2000});
		$celular_proveedores.notify("Ingrese Celular",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para email proveedor
	let $email_proveedores = $("#email_proveedores");    	
	/*if( $email_proveedores.val().length == 0 || $email_proveedores.val() == '' ){
		$email_proveedores.notify("Ingrese email",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	} */
	
	if( $email_proveedores.val().length != 0 || $email_proveedores.val() != '' ){
		
		if(!regex.test($email_proveedores.val().trim())){
			$email_proveedores.notify("Ingrese email valido",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
	}
	
	//para datos bancarios del proveedor
	$forma_pago = $("#forma_pago");
	let $idBancos= $("#id_bancos");
	let $tipoCuenta= $("#id_tipo_cuentas"); 
	let $numeroCuenta = $("#numero_cuenta_proveedores"); 
	if( $forma_pago.val() == "transferencia"){
		
		//para bancos   	
		if( $idBancos.val() == 0 || $idBancos.val() == null || $idBancos.val() == "" ){
			$idBancos.notify("Seleccione Banco",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}	
		
		//para tipo cuenta		   	
		if( $tipoCuenta.val() == 0 ){
			$tipoCuenta.notify("Seleccione Tipo Cuentas",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
		
		//para numero cuenta		   	
		if( $numeroCuenta.val().length == 0 || $numeroCuenta.val() == '' ){
			$numeroCuenta.notify("Ingrese numero cuenta Proveedores",{ position:"buttom left", autoHideDelay: 2000});
			return false;
		}
		
	}else if( $forma_pago.val() == "0"){
		$forma_pago.notify("Seleccione Forma Pago",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
		
    var inimagen_registro = $("#imagen_registro");
	//if( inimagen_registro[0].files.length == 0){
		//inimagen_registro.closest("tr").notify("Ingrese un Imagen",{ position:"buttom left", autoHideDelay: 2000});
		//return false;
	//}
	
	
	//para proveedores id
	let $idProveedores = $("#id_proveedores"); 
	
	var parametros = new FormData();
	
	parametros.append('id_proveedores',$idProveedores.val());
    parametros.append('nombre_proveedores',$nombre_proveedores.val());
	parametros.append('identificacion_proveedores',$identificacion_proveedores.val());
    parametros.append('contactos_proveedores',$contactos_proveedores.val());
	parametros.append('direccion_proveedores',$direccion_proveedores.val());
    parametros.append('telefono_proveedores',$telefono_proveedores.val());
	parametros.append('email_proveedores',$email_proveedores.val());
    parametros.append('fecha_nacimiento_proveedores','');
	parametros.append('id_tipo_proveedores',$tipoProveedores.val());
	parametros.append('id_bancos',$idBancos.val());
    parametros.append('id_tipo_cuentas',$tipoCuenta.val());
	parametros.append('numero_cuenta_proveedores',$numeroCuenta.val());
	parametros.append('razon_social_proveedores',$razon_social.val());
	parametros.append('tipo_identificacion',$tipoIdentificacion.val());
	parametros.append('forma_pago',$forma_pago.val());
	parametros.append('imagen_registro',inimagen_registro[0].files[0]);	
	
	$.ajax({
		url:"index.php?controller=TesProveedores&action=AgregaProveedores",
		type:"POST",
		dataType:"json",
		data:parametros,
		contentType: false, //importante enviar este parametro en false
        processData: false,  //importante enviar este parametro en false
	}).done(function(x){
		console.log(x)
		if( x.respuesta == 1 ){    			
			swal({
    			title:"Proveedores",
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
	$('#id_bancos').val(0).trigger('change');
	$("#numero_cuenta_proveedores").val("");    	
	$("#id_tipo_cuentas").val(0); 
	$("#razon_social_proveedores").val("");
	$('#tipo_identificacion_proveedores option').eq(0).prop('selected', true);
	$("#forma_pago").val("0");
	$("#celular_proveedores").val("");
	$("#imagen_registro").val("");
	
	
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
  
  function editarProveedores(identificador){
	  
	  var proveedorId = identificador;
	  
	  $.ajax({
		  url:"index.php?controller=TesProveedores&action=datosProveedoresEditar",
		  dataType:"json",
		  type:"POST",
		  data:{id_proveedores:proveedorId}
	  }).done(function(x){
		  
		  if( x.datos != undefined ){
			  
			  if( x.datos != null ){
				  
				var rsProveedor = x.datos;
				  
				$("#id_proveedores").val( rsProveedor.id_proveedores );    	
			    $("#nombre_proveedores").val( rsProveedor.nombre_proveedores );    	
				$("#identificacion_proveedores").val( rsProveedor.identificacion_proveedores );    	
				$("#contactos_proveedores").val( rsProveedor.contactos_proveedores );    	
				$("#direccion_proveedores").val( rsProveedor.direccion_proveedores );    	
				$("#telefono_proveedores").val( rsProveedor.telefono_proveedores );    	
				$("#email_proveedores").val( rsProveedor.email_proveedores );    	
				$("#id_tipo_proveedores").val( rsProveedor.id_tipo_proveedores ); 
				$('#id_bancos').val( rsProveedor.id_bancos ).trigger('change');
				$("#numero_cuenta_proveedores").val( rsProveedor.numero_cuenta_proveedores );    	
				$("#id_tipo_cuentas").val( rsProveedor.id_tipo_cuentas ); 
				$("#razon_social_proveedores").val( rsProveedor.razon_social_proveedores );
				$('#tipo_identificacion_proveedores').val( rsProveedor.tipo_identificacion_proveedores );
				
				var nbancos = $('#id_bancos').val(), ntipocuenta = $("#id_tipo_cuentas").val(), ncuenta = $("#numero_cuenta_proveedores").val();
				
				if( nbancos != null || ntipocuenta != null ||  ncuenta != "" ){	
					$("#forma_pago").val("transferencia");					
				}else{
					$("#forma_pago").val("0");					
				}
				validaFormaPago();
				
				swal({
					title:"PROVEEDORES",
					text:"Puede Editar Datos Proveedor",
					icon:"info"
				});
				
			  }else{
				  swal({
					  title:"ERROR",
					  text: "No se puede consultar proveedor",
					  icon: "warning"
				  })
			  }
			  
		  }else{
			  swal({
				  title:"ERROR",
				  text: "No se puede consultar proveedor",
				  icon: "warning"
			  })
		  }
		  
	  }).fail(function(xhr,status,error){
		  		  
		  swal({
			  title:"ERROR",
			  text: "BD No se puede consultar proveedor",
			  icon: "error"
		  })
		  
	  })
	  
  }
  
