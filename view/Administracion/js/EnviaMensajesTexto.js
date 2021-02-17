$(document).ready(function(){

	formato_mensajes();
}); 
	




$("#txt_directorio").on("focus",function(e) {
	
	let _elemento = $(this);
	
    if ( !_elemento.data("autocomplete") ) {
    	    	
    	_elemento.autocomplete({
    		minLength: 2,    	    
    		source:function (request, response) {
    			$.ajax({
    				url:"index.php?controller=EnvioMensajesTexto&action=autocompleteCedulaParticipes",
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
    			var txt_directorio	= $("#txt_directorio");
    			var id_participes	= $("#id_participes");
    			
    			
    			
    			if(ui.item.id == '')
    			{
    				txt_directorio.val("");
    				id_participes.val("0");
        			return;
    			}
    			
    			id_participes.val(ui.item.id);
    			txt_directorio.val(ui.item.value);
    			    			     	     
     	    },
     	   appendTo: "",
     	   change: function(event,ui){
     		   
     		   if(ui.item == null)
     		   {
     			 //_elemento.notify("Digite Cedula Valida",{ position:"top center"});
     			 $("#txt_directorio").val("");
     			 _elemento.val('');
     			 $("#id_participes").val("0");
     			
     		   }
     	   }
    	
    	}).focusout(function(event,ui) {
    		  
  		   
    	})
    }
    
})





$("#btn_agregar").click(function() {

    var nombre_participes = $("#txt_directorio").val();
    var id_participes = $("#id_participes").val();
	var $participes_to = $("#participes_to");
  
   
   if (id_participes > 0)
   {
	   var contar = $("#participes_to option").length;
	   
    if(contar==0){

		 $participes_to.append("<option value= " +id_participes +" >" + nombre_participes  + "</option>");
		 $("#txt_directorio").val('');
		 $("#id_participes").val(0);
    	
    	 
	}else{

		 var array1='';
		 var existe = false;
	     
	    
       
	     $("#participes_to option").each(function(){
		        array1 = $(this).val();
		        if($("#id_participes").val() == array1){
		        	existe =true;
		        }
		 });

		if(existe==false){
			$participes_to.append("<option value= " +id_participes +" >" + nombre_participes  + "</option>");
			 $("#txt_directorio").val('');
			 $("#id_participes").val(0);
		}else{
			 $("#txt_directorio").val('');
			 $("#id_participes").val(0);
			
			 $("#txt_directorio").notify("Ya se encuentra agregado en la lista",{ position:"buttom left", autoHideDelay: 2000});
			 return false;

		}
	}				     
	   	
   }else{
   
		$("#txt_directorio").val('');
		$("#id_participes").val(0);
		$("#txt_directorio").notify("Ingrese Cédula",{ position:"buttom left", autoHideDelay: 2000});
		return false;

	 
	   
   }
             
  });




$('#btn_quitar').click(function() { 

    var contar_to = $("#participes_to option:selected").length;

		
	if(contar_to==0){

	$("#participes_to").notify("Seleccione para Quitar",{ position:"buttom left", autoHideDelay: 2000});
	return false;

	}else{

	 !$('#participes_to option:selected').remove();
	
	}

	 });
		


function formato_mensajes(){

	
 var var1=$('#txt_var1').val();
 var var2=$('#txt_var2').val();
 var var3=$('#txt_var3').val();
 var var4=$('#txt_var4').val();
 
 if(var1.length==0){
	 
	 var1="(x)";
 }
 
if(var2.length==0){
	 
	 var2="(x)";
 }

if(var3.length==0){
	 
	 var3="(x)";
}

if(var4.length==0){
	 
	 var4="(x)";
}

 var formato = `CAPREMCI informa que ${var1} de ${var2} a ${var3} en el ${var4}`;


$('#mensaje').val(formato);

caracteres();
}



function caracteres(){

 var contador=0;
 var textarea=$('#mensaje').val();


 if(textarea.length>=0){
	 
	 contador=textarea.length;
	 $('#caracteres').html(contador);
	 
	 if(contador > 150){
		 
		 $("#mensaje").notify("Solo puede agregar 150 caracteres.",{ position:"buttom left", autoHideDelay: 2000});
			return false;
	 }
	 
 }
 

}





$("#frm_mensajes").on("submit",function(event){

 var participes_to= $("#participes_to").val();
 var contar_to = $("#participes_to option").length;
 var contar_to_celular = $("#participes_to_celular option").length;

 
 if(contar_to == 0 && contar_to_celular == 0){
	   $("#participes_to").notify("Ingrese Destinatarios o Extras",{ position:"buttom left", autoHideDelay: 2000});
		return false;
 }
 $('#participes_to option').prop('selected', 'selected'); 
 $('#participes_to_celular option').prop('selected', 'selected'); 
	
 
var parametros = $(this).serialize();

swal({
	  title: "Procesando Envios",
	  text: "Cargando Respuestas",
	  icon: "view/images/capremci_load.gif",
	  buttons: false,
	  closeModal: false,
	  allowOutsideClick: false
	});

$.ajax({
	beforeSend:null,
	url:"index.php?controller=EnvioMensajesTexto&action=Enviar",
	type:"POST",
	dataType:"json",
	data:parametros
}).done(function(x){
	
	if(x.error != ''){
		
		swal({text: x.error,
	  		  icon: "error",
	  		  button: "Aceptar",
	  		  dangerMode: true
	  		});
	}
	
	if(x.hasOwnProperty('respuesta')){
			
	swal({title:"Resúmen de Proceso Generado:",text:x.mensaje,icon:"success"})
  		.then((value) => {
  			$('#smartwizard').smartWizard("reset");
  			window.location.reload();
  		});
			
			
	}
	
	console.log(x);
	
}).fail(function(xhr,status,error){
	
	let err = xhr.responseText
	
	console.log(err);
})



event.preventDefault()
})







$("#frm_mensajes").on("click","#btn_cancelar",function(event){

	let botonMain = $(this);
	
	botonMain.attr('disabled',true);
	

swal("¿Esta seguro de Cancelar?", {
	 title:"Petición",
	 icon:"info", 
	 dangerMode: true,
	 text:"Se cancelará todo los datos ingresados",
	  buttons: {
	    cancelar: "Cancelar",
	    aceptar: "Aceptar",
	  },
	})
	.then((value) => {
	  switch (value) {
	 
	    case "cancelar":
	      return;
	    case "aceptar":		      
	    	
	    	botonMain.attr('disabled',false);
  		swal({title:"Petición Cancelada",text:"",icon:"info", dangerMode:true})
  		.then((value) => {
  		  window.open("index.php?controller=EnvioMensajesTexto&action=index","_self")
  
	  });
	}
	  
	});
})


/////para enviar a celulares extras



$("#btn_agregar_celular").click(function() {

    var nombre_participes_celular = $("#txt_directorio_celular").val();
    var $participes_to_celular = $("#participes_to_celular");
  
   
   if (nombre_participes_celular.length >= 10)
   {
	   var contar = $("#participes_to_celular option").length;
	   
    if(contar==0){

    	$participes_to_celular.append("<option value= " +nombre_participes_celular +" >" + nombre_participes_celular  + "</option>");
		 $("#txt_directorio_celular").val('');
		
    	 
	}else{

		 var array1='';
		 var existe = false;
	     
	    
       
	     $("#participes_to_celular option").each(function(){
		        array1 = $(this).val();
		        if($("#txt_directorio_celular").val() == array1){
		        	existe =true;
		        }
		 });

		if(existe==false){
			$participes_to_celular.append("<option value= " +nombre_participes_celular +" >" + nombre_participes_celular  + "</option>");
			 $("#txt_directorio_celular").val('');
			 
		}else{
			 $("#txt_directorio_celular").val('');
			
			 $("#txt_directorio_celular").notify("Ya se encuentra agregado en la lista",{ position:"buttom left", autoHideDelay: 2000});
			 return false;

		}
	}				     
	   	
   }else{
   
		$("#txt_directorio_celular").val('');
	
		$("#txt_directorio_celular").notify("Ingrese 10 Dígitos",{ position:"buttom left", autoHideDelay: 2000});
		return false;

	 
	   
   }
             
  });





$('#btn_quitar_celular').click(function() { 

    var contar_to = $("#participes_to_celular option:selected").length;

		
	if(contar_to==0){

	$("#participes_to_celular").notify("Seleccione para Quitar",{ position:"buttom left", autoHideDelay: 2000});
	return false;

	}else{

	 !$('#participes_to_celular option:selected').remove();
	
	}

	 });
		




