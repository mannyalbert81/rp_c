$(document).ready(function(){
	

});

function autompleteCodigo(elemento){
	  
	  var _elemento = $(elemento);
	  //console.log("ingreso codigo complete");	  
	  if ( !_elemento.data("autocomplete") ) {
		  
		  _elemento.autocomplete({
	    		minLength: 3,    	    
	    		source:function (request, response) {
	    			$.ajax({
	    				url:"index.php?controller=Presupuestos&action=autompleteCodigo",
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
	    			
	    			if(ui.item.id == ''){
	    				 _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left"});
	    				 $('#id_plan_cuentas').val(0);
	    				 return;
	    			}
	    			
	    			$('#id_plan_cuentas').val(ui.item.id);
	    				    			     	     
	     	    },
	     	   appendTo: null,
	     	   change: function(event,ui){
	     		   if(ui.item == null){
	     			   
	     			 _elemento.notify("Digite Cod. Cuenta Valido",{ position:"buttom left"});
	     			 _elemento.val("")
	     			 $('#id_plan_cuentas').val(0);
	 	   			 $('#nombre_presupuestos').val("");
	 	   			 $('#valor_procesar').val("");
	 	   			 $('#valor_ejecutar').val("");
	    			 			 	   			
	     			
	     		   }
	     	   }
	    	
	    	}).focusout(function() {
	    		
	    	})
	  }
	  
}

function InsertarPresupuestos(){
	
	var _id_plan_cuentas = document.getElementById('id_plan_cuentas').value;
	var _nombre_presupuestos = document.getElementById('nombre_presupuestos').value;
	var _valor_procesado = document.getElementById('valor_procesado').value;
	var _valor_ejecutado = document.getElementById('valor_ejecutado').value;
	
	
	
	var parametros = {
			id_plan_cuentas:_id_plan_cuentas,
			nombre_presupuestos:_nombre_presupuestos,
			valor_procesado:_valor_procesado,
			valor_ejecutado:_valor_ejecutado
	}
	
$.ajax({
		
		beforeSend:function(){},
		url:"index.php?controller=Presupuestos&action=InsertarPresupuestos",
		type:"POST",
		dataType:"json",
		data:parametros
	}).done(function(datos){
		
		if(datos.mensaje != undefined && datos.mensaje == 1){
			
			swal({
		  		  title: "Presupuestos",
		  		  text: "Guardado exitosamente",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		
		  		});
			
		}
			console.log(datos)
	
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log(err);
		
	});
	

event.preventDefault()

}






	

