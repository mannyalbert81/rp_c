$(document).ready(function(){
	
	 load_productos(1);
	 $("#minimo_productos").inputmask({
			mask: "9{1,3}", 
			placeholder: "_",
			clearIncomplete: true,
			rightAlign: true
		});
	 
	 $(".cantidades1").inputmask(); 
	
	$("#btExportar").click(function() {

		var table = $('#podructtable').DataTable();
	
		var arreglo_completo = table.rows( {order:'index', search:'applied'} ).data();
		
		var arrayHead=["","Grupos","Codigo","Marca","Nombre","Descripcion","Unidad_de_M","ULT_Precio","",""];
		arreglo_completo.unshift(arrayHead);
		var len = arreglo_completo.length;
		
		for (var i = 1; i < len; i++) {
			
			
			arreglo_completo[i][7]=parseFloat(arreglo_completo[i][7]);
			arreglo_completo[i][8]="";
			arreglo_completo[i][9]="";
				}
		   var dt = new Date();
		   var m=dt.getMonth();
		   m+=1;
		   var y=dt.getFullYear();
		   var d=dt.getDate();
		   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
		   var wb =XLSX.utils.book_new();
		   wb.SheetNames.push("Reporte Productos");
		   var ws = XLSX.utils.aoa_to_sheet(arreglo_completo);
		   wb.Sheets["Reporte Productos"] = ws;
		   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
		   function s2ab(s) { 
	            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	            var view = new Uint8Array(buf);  //create uint8array as viewer
	            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	            return buf;    
		   }
	       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'ReporteProductos'+fecha+'.xlsx'); 
	});
		    
		       
});//docreadyend

$("#Guardar").click(function(event){
	
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;
	
	
	//para bodegas
	let $idBodegas = $("#id_bodegas");    	
	if( $idBodegas.val() == 0 ){
		$idBodegas.notify("Seleccione Bodega",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para grupo
	let $idGrupo = $("#id_grupos");    	
	if( $idGrupo.val() == 0 ){
		$idGrupo.notify("Seleccione Un Grupo",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para codigo
	let $codigoProducto = $("#codigo_productos");    	
	if( $codigoProducto.val().length == 0 || $codigoProducto.val() == '' ){
		$codigoProducto.notify("Codigo No generado",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para marca
	let $marcaProducto = $("#marca_productos");    	
	if( $marcaProducto.val().length == 0 || $marcaProducto.val() == '' ){
		$marcaProducto.notify("Ingrese Marca",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para nombre
	let $nombreProducto = $("#nombre_productos");    	
	if( $nombreProducto.val().length == 0 || $nombreProducto.val() == '' ){
		$nombreProducto.notify("Ingrese Nombre",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para descripcion
	let $descripcionProducto = $("#descripcion_productos");    	
	if( $descripcionProducto.val().length == 0 || $descripcionProducto.val() == '' ){
		$descripcionProducto.notify("Ingrese Descripcion",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para unidad medida
	let $idUnidadMedida = $("#id_unidad_medida");    	
	if( $idUnidadMedida.val() == 0 ){
		$idUnidadMedida.notify("Seleccione Unidad Medida",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para valor precio
	let $ultimoPrecioProducto = $("#ult_precio_productos");     	
	if( $ultimoPrecioProducto.val().length == 0 || $ultimoPrecioProducto.val() == 0.00 ){
		$ultimoPrecioProducto.notify("Ingrese ult precio",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para valor minimo stock
	let $minimoProducto = $("#minimo_productos");     	
	if( $minimoProducto.val().length == 0 || $minimoProducto.val() <= 0 ){
		$minimoProducto.notify("Ingrese Minimo stock",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	}
	
	//para el id de producto
	let $idProductos = $("#id_productos");
	
	
	
	//para datos
	let parametros={			
			id_grupos: $idGrupo.val(),
			id_unidad_medida: $idUnidadMedida.val(),
			codigo_productos: $codigoProducto.val(),
			marca_productos: $marcaProducto.val(),
			nombre_productos: $nombreProducto.val(),
			descripcion_productos: $descripcionProducto.val(),
			ult_precio_productos: $ultimoPrecioProducto.val(),
			id_bodegas:$idBodegas.val(),
			id_productos: $idProductos.val(),
			abreviacion_grupo:'',
			consecutivo_productos:'',
			minimo_productos: $minimoProducto.val()
				
	}
	
	
	//InsertaProductos
	$.ajax({
		url:"index.php?controller=Productos&action=AgregaProductos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(x){
		console.log(x)
		if( x.respuesta == 1 ){    			
			swal({
    			title:"Productos",
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
		
		load_productos(1);
	})
	
	event.preventDefault();
	
	    	
}); 		      
	        

function load_productos(pagina){

	   var search=$("#search_productos").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_productos").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=Productos&action=consulta_productos&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#productos_registrados").html(x);
              $("#load_productos").html("");
              $("#tabla_productos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#productos_registrados").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
           }
         });


	   }

/***
 * funcion para traer consecutivo de producto
 * @returns
 */
$("#id_grupos").on("change",function(){
	getConsecutivoProducto();
})

function getConsecutivoProducto(){
	
	let $ddlGrupo = $("#id_grupos");
	let $codigoProducto = $("#codigo_productos");
	$codigoProducto.val('');
	
	$.ajax({
		url:"index.php?controller=Productos&action=getCodigoProducto",
		type:"POST",
		dataType:"json",
		data:{id_grupos:$ddlGrupo.val()}
	}).done(function(x){
		console.log(x)		
		$codigoProducto.val( x.abreviacion + x.consecutivo);
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		
	})
	
}

/*FUNCIONES PARA LIMPIAR FORMULARIO*/
function limpiarCampos(){
	$("#id_bodegas").val(0);  
	$("#id_grupos").val(0);
	$("#codigo_productos").val("");
	$("#marca_productos").val("").focus();
	$("#nombre_productos").val("");
	$("#descripcion_productos").val("");
	$("#id_unidad_medida").val(0);
	$("#ult_precio_productos").val("");
	$("#minimo_productos").val("");
}
