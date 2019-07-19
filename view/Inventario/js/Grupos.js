$(document).ready( function (){
        		   
   load_grupos_inactivos(1);
   load_grupos_activos(1);
   
});

  $("#Guardar").click(function(event){
    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

    	var $nombre_grupos = $("#nombre_grupos").val();
    	if( $nombre_grupos == "" ){
    		$("#nombre_grupos").notify("Introduzca Nombre Grupo",{ position:"buttom left", autoHideDelay: 2000});
    		return false;
    	}
    	
    	//para abrviacion
    	let $abreviacion = $("#abreviacion_grupos");    	
    	if( $abreviacion.val().length == 0 || $abreviacion.val() == '' ){
    		$abreviacion.notify("Agregue abreviacion",{ position:"buttom left", autoHideDelay: 2000});
    		return false;
    	}
    	
    	//para plan cuentas
    	let $codigoPlanCuentas = $("#codigo_plan_cuentas"); 
    	let $idPlanCuentas = $("#id_plan_cuentas");
    	if( $codigoPlanCuentas.val().length == 0 || $codigoPlanCuentas.val() == '' ){
    		$codigoPlanCuentas.notify("Ingrese Codigo ",{ position:"buttom left", autoHideDelay: 2000});
    		return false;
    	}
    	
    	//para estado de grupo
    	let $estado = $("#id_estado");    	
    	if( $estado.val() == 0 ){
    		$estado.notify("Agregue abreviacion",{ position:"buttom left", autoHideDelay: 2000});
    		return false;
    	}
    	//para datos de grupos
    	let $id_grupos = $("#id_grupos"); 
    	
    	//datos a formulario
    	let parametros={nombre_grupos:$nombre_grupos,id_grupos:$id_grupos.val(),id_estado:$estado.val(),abreviacion_grupos:$abreviacion.val(),
    			id_plan_cuentas:$idPlanCuentas.val()}
    	//InsertaGrupos
    	$.ajax({
    		url:"index.php?controller=Grupos&action=AgregaGrupos",
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
    		load_grupos_activos(1);
    		load_grupos_inactivos(1);
    	})
    	
    	
    	event.preventDefault();
	});
  
  $( "#nombre_grupos" ).focus(function() {
	  $("#mensaje_nombre_grupos").fadeOut("slow");
    });
  
$("#abreviacion_grupos" ).on("keyup",function() {
	$(this).val($(this).val().toUpperCase());
});
  
  function load_grupos_activos(pagina){

	   var search=$("#search_activos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_grupos_activos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_grupos_activos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Grupos&action=consulta_grupos_activos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#grupos_activos_registrados").html(x);
                $("#load_grupos_activos").html("");
                $("#tabla_grupos_activos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#grupos_activos_registrados").html("Ocurrio un error al cargar la informacion de Grupos Activos..."+estado+"    "+error);
             }
           });


	   }
  
  function load_grupos_inactivos(pagina){

	   var search=$("#search_inactivos").val();
      var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
    $("#load_grupos_inactivos").fadeIn('slow');
    
    $.ajax({
              beforeSend: function(objeto){
                $("#load_grupos_inactivos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
              },
              url: 'index.php?controller=Grupos&action=consulta_grupos_inactivos&search='+search,
              type: 'POST',
              data: con_datos,
              success: function(x){
                $("#grupos_inactivos_registrados").html(x);
                $("#load_grupos_inactivos").html("");
                $("#tabla_grupos_inactivos").tablesorter(); 
                
              },
             error: function(jqXHR,estado,error){
               $("#grupos_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Grupos Inactivos..."+estado+"    "+error);
             }
           });
	   }
  
  /*FUNCIONES PARA AUTOMCOMPLETE*/
  $("#codigo_plan_cuentas").on("focus",function(e) {
		
		let _elemento = $(this);
		
	    if ( !_elemento.data("autocomplete") ) {
	    	    	
	    	_elemento.autocomplete({
	    		minLength: 3,    	    
	    		source:function (request, response) {
	    			$.ajax({
	    				url:"index.php?controller=CuentasPagar&action=autompletePlanCuentas",
	    				dataType:"json",
	    				type:"GET",
	    				data:{term:request.term},
	    			}).done(function(x){
	    				
	    				response(x); 
	    				
	    			}).fail(function(xhr,status,error){
	    				var err = xhr.responseText
	    				console.log(err)
	    			})
	    		},
	    		select: function (event, ui) {
	     	       	// Set selection
	    			let $id_planCuentas = $("#id_plan_cuentas");     			
	    			
	    			if(ui.item.id == ''){
	    				 _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left", autoHideDelay: 2000});
	    				 _elemento.val("");	    				 
	    				 return;
	    			}
	    			
	    			$id_planCuentas.val(ui.item.id);
	    			     	     
	     	    },
	     	    appendTo:null,
	     	   change: function(event,ui){
	     		   
	     		   if(ui.item == null){
	     			   _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left", autoHideDelay: 2000});
	     			   $("#id_plan_cuentas").val(""); 
	     			   _elemento.val("");	     			 
	     			 
	     		   }
	     	   }
	    	
	    	}).focusout(function() {
	    		
	    	})
	    }
	});
  
  /*FUNCIONES PARA LIMPIAR FORMULARIO*/
  function limpiarCampos(){
	  $("#nombre_grupos").val("").focus();
	  $("#id_grupos").val("");
	  $("#abreviacion_grupos").val("");
	  $("#id_estado").val(0);	
	  $("#codigo_plan_cuentas").val(""); 
  }
  
  
  