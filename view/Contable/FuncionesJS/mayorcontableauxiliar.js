$(document).ready(function(){
	
	cargaCuentas();
})


/// desde aqui maycol



function cargaCuentas(){
	
	let $ddlCuentas = $("#codigo_plan_cuentas");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=LibroMayorAuxiliar&action=cargaCuentas",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(datos){		
		
		$ddlCuentas.empty();
		$ddlCuentas.append("<option value='0' >--Seleccione--</option>");
		$.each(datos.data, function(index, value) {
			$ddlCuentas.append("<option value= " +value.codigo_plan_cuentas +" >" + value.nombre_plan_cuentas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$ddlCuentas.empty();
		$ddlCuentas.append("<option value='0' >--Seleccione--</option>");
		
	})
	
	
	
}


function cargaSubCuentas(codigo_plan_cuentas){
	
	let $dllSubCuentas = $("#codigo_sub_plan_cuentas");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=LibroMayorAuxiliar&action=cargaSubCuentas",
		type:"POST",
		dataType:"json",
		data:{codigo_plan_cuentas:codigo_plan_cuentas}
	}).done(function(datos){		
		
		$dllSubCuentas.empty();
		$dllSubCuentas.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$dllSubCuentas.append("<option value= " +value.codigo_plan_cuentas +" >" + value.nombre_plan_cuentas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$dllSubCuentas.empty();
		$dllSubCuentas.append("<option value='0' >--Seleccione--</option>");
		
	})
	
}




function cargaCuentasHijos(codigo_sub_plan_cuentas){
	
	let $dllFiltro = $("#codigo_plan_cuentas_hijos");
	
	$.ajax({
		beforeSend:function(){},
		url:"index.php?controller=LibroMayorAuxiliar&action=cargaCuentasHijos",
		type:"POST",
		dataType:"json",
		data:{codigo_sub_plan_cuentas:codigo_sub_plan_cuentas}
	}).done(function(datos){		
		
		$dllFiltro.empty();
		$dllFiltro.append("<option value='0' >--Seleccione--</option>");
		
		$.each(datos.data, function(index, value) {
			$dllFiltro.append("<option value= " +value.codigo_plan_cuentas +" >" + value.nombre_plan_cuentas  + "</option>");	
  		});
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err)
		$dllFiltro.empty();
		$dllFiltro.append("<option value='0' >--Seleccione--</option>");
		
	})
	
}


// cuando hago clic  
$("#codigo_plan_cuentas").click(function() {
	
  var codigo_plan_cuentas = $(this).val();
  
  let $dllSubCuentas = $("#codigo_sub_plan_cuentas");
  $dllSubCuentas.empty();
  
  let $dllFiltro = $("#codigo_plan_cuentas_hijos");
  $dllFiltro.empty();
  $dllFiltro.append("<option value='0' >--Seleccione--</option>");
	
  cargaSubCuentas(codigo_plan_cuentas);

});


// cuando hago el cambio
$("#codigo_plan_cuentas").change(function() {
	
	 var codigo_plan_cuentas = $(this).val();
	  
	  let $dllSubCuentas = $("#codigo_sub_plan_cuentas");
	  $dllSubCuentas.empty();
	  
	  let $dllFiltro = $("#codigo_plan_cuentas_hijos");
	  $dllFiltro.empty();
	  $dllFiltro.append("<option value='0' >--Seleccione--</option>");
		
	  cargaSubCuentas(codigo_plan_cuentas);

});


//cuando hago clic  
$("#codigo_sub_plan_cuentas").click(function() {
	
  var codigo_sub_plan_cuentas = $(this).val();
  let $dllFiltro = $("#codigo_plan_cuentas_hijos");
  $dllFiltro.empty();
  cargaCuentasHijos(codigo_sub_plan_cuentas);
});


// cuando hago el cambio
$("#codigo_sub_plan_cuentas").change(function() {
	
  var codigo_sub_plan_cuentas = $(this).val();
  let $dllFiltro = $("#codigo_plan_cuentas_hijos");
  $dllFiltro.empty();
  cargaCuentasHijos(codigo_sub_plan_cuentas);

});




// metodo para buscar

$("#btnMayores").on("click",function(event){
	
	let $codigo_plan_cuentas = $("#codigo_plan_cuentas");
	let $codigo_sub_plan_cuentas = $("#codigo_sub_plan_cuentas");
	let $codigo_plan_cuentas_hijos = $("#codigo_plan_cuentas_hijos");
	let $desde_diario = $("#desde_diario");
	let $hasta_diario = $("#hasta_diario");
	
	if($codigo_plan_cuentas.val() == 0){
		
		$codigo_plan_cuentas.notify("Seleccione la cuenta",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	
	}
	
    if($codigo_sub_plan_cuentas.val() == 0){
		
    	$codigo_sub_plan_cuentas.notify("Seleccione la cuenta",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	
	}
	
    
   if($codigo_plan_cuentas_hijos.val() == 0){
		
	   $codigo_plan_cuentas_hijos.notify("Seleccione la cuenta",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	
	}
   
   if($desde_diario.val() == 0){
		
	   $desde_diario.notify("Seleccione Fecha",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	
	}
   
   if($hasta_diario.val() == 0){
		
	   $hasta_diario.notify("Seleccione Fecha",{ position:"buttom left", autoHideDelay: 2000});
		return false;
	
	}
    
    
		load_tabla(1);
	 
})





/// respuesta de busqueda

 function load_tabla(pagina){

	 var search=$("#search").val();
	 var codigo_plan_cuentas=$("#codigo_plan_cuentas").val();
	 var codigo_sub_plan_cuentas=$("#codigo_sub_plan_cuentas").val();
	 var codigo_plan_cuentas_hijos=$("#codigo_plan_cuentas_hijos").val();
	 var desde_diario=$("#desde_diario").val();
	 var hasta_diario=$("#hasta_diario").val();
	 
	 
	 
     var con_datos={
				  action:'ajax',
				  page:pagina,
				  search:search,
				  codigo_plan_cuentas:codigo_plan_cuentas,
				  codigo_sub_plan_cuentas:codigo_sub_plan_cuentas,
				  codigo_plan_cuentas_hijos:codigo_plan_cuentas_hijos,
				  desde_diario:desde_diario,
				  hasta_diario:hasta_diario
				  };
		  
   $("#load_detalle").fadeIn('slow');
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_detalle").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=LibroMayorAuxiliar&action=mayorContableAuxiliar',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#registrados_detalle").html(x);
               $("#load_detalle").html("");
               
             },
            error: function(jqXHR,estado,error){
              $("#registrados_detalle").html("Ocurrio un error al cargar la información de Mayores Auxiliares..."+estado+"    "+error);
            }
          });


	   }


/// termina maycol


$( "#codigo_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayorAuxiliar&action=AutocompleteCodigo",
	minLength: 4,
    select: function (event, ui) {
       // Set selection          
       $('#id_cuenta').val(ui.item.id);
       $('#codigo_cuenta').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#codigo_cuenta').val();            
        return false; 
    } 
}).focusout(function() {
	
	if(document.getElementById('codigo_cuenta').value != ''){
		$.ajax({
			url:'index.php?controller=LibroMayorAuxiliar&action=AutocompleteCodigo',
			type:'POST',
			dataType:'json',
			data:{term:document.getElementById('codigo_cuenta').value}
		}).done(function(respuesta){
			//console.log(respuesta[0].id);
			 if( !$.isEmptyObject(respuesta) && respuesta[0].id>0){
				
				 $('#nombre_cuenta').val(respuesta[0].nombre_cuenta)
				 $('#codigo_cuenta').val(respuesta[0].value)
				 $('#id_cuenta').val(respuesta[0].id)
				 
			}else{ $("#frm_libro_mayors")[0].reset(); }
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			 console.log(err)
			 
		});
	}
	
}).focus(function(){
	$(this).val('')
	$('#nombre_cuenta').val('')
	$('#id_cuenta').val('')
})

$( "#nombre_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayorAuxiliar&action=AutocompleteNombre",
	minLength: 4,
    select: function (event, ui) {
       // Set selection          
       $('#id_cuenta').val(ui.item.id);
       $('#nombre_cuenta').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#nombre_cuenta').val();            
        return false; 
    } 
}).focusout(function() {
	
	if(document.getElementById('nombre_cuenta').value != ''){
		$.ajax({
			url:'index.php?controller=LibroMayorAuxiliar&action=AutocompleteNombre',
			type:'POST',
			dataType:'json',
			data:{term:document.getElementById('nombre_cuenta').value}
		}).done(function(respuesta){
			//console.log(respuesta[0].id);
			 if( !$.isEmptyObject(respuesta) && respuesta[0].id>0){
				
				 $('#nombre_cuenta').val(respuesta[0].nombre_cuenta)
				 $('#codigo_cuenta').val(respuesta[0].value)
				 $('#id_cuenta').val(respuesta[0].id)
				 
			}else{ $("#frm_libro_mayors")[0].reset(); }
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			 console.log(err)
			 
		});
	}
	
}).focus(function(){
	$(this).val('')
	$('#codigo_cuenta').val('')
	$('#id_cuenta').val('')
})







$('#frm_libro_mayor').on('submit',function(event){
	
	var formulario = $(this)
	
	formulario.attr('target','_blank');
	
	formulario.attr('action','index.php?controller=LibroMayor&action=mayorContable');

	//event.preventDefault();
})

