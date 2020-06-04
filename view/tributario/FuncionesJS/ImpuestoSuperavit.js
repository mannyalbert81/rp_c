 
var array_procesar_personal;
var array_procesar_patronal;
var array_procesar_cesantes;


$(document).ready( function (){
	
	load_personal(1);
	load_patronal(1);
	load_cesantes(1);
		
	array_procesar_personal="";
	array_procesar_patronal="";
	array_procesar_cesantes="";
	
});
  



function load_personal(pagina){

	var search=$("#search_personal").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_registrados").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=TributarioImpuestoSuperavit&action=consulta_superavit_personal&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#personal_registrados").html(x);
              $("#load_registrados").html("");
              $("#tabla_personal").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#personal_registrados").html("Ocurrio un error al cargar la información de Superavit Personal..."+estado+"    "+error);
           }
         });


	   }

function load_patronal(pagina){

	var search=$("#search_patronal").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_patronal_registrados").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_patronal_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=TributarioImpuestoSuperavit&action=consulta_superavit_patronal&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#patronal_registrados").html(x);
              $("#load_patronal_registrados").html("");
              $("#tabla_patronal").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#patronal_registrados").html("Ocurrio un error al cargar la información de Superavit Personal..."+estado+"    "+error);
           }
         });
}


function load_cesantes(pagina){

	var search=$("#search_cesantes").val();
    var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
  $("#load_cesantes_registrados").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_cesantes_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=TributarioImpuestoSuperavit&action=consulta_superavit_cesantes&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#cesantes_registrados").html(x);
              $("#load_cesantes_registrados").html("");
              $("#tabla_cesantes").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#cesantes_registrados").html("Ocurrio un error al cargar la información de Superavit Patronal..."+estado+"    "+error);
           }
         });
}



// para personal

$("#personal_registrados").on("click","#btn_personal",function(event){

	var $div_respuesta = $("#msg_frm_personal"); 
	
	$div_respuesta.text("").removeClass();
	
	$("#mod_personal").on('show.bs.modal',function(e){

		 var modal = $(this)
		 
		
		
		cargar_personal_a_procesar();
		 
	}) 
	
})




function cargar_personal_a_procesar(){
       	 
	var cantidad_personal = $("#mod_cantidad_personal").val();
	
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=cargar_personal_a_procesar",
			type:"POST",
			//dataType:"json",
			data:{cantidad_personal:cantidad_personal}
		}).done(function(x){		
			
		
			x=JSON.parse(x);
			
			// imprimo html
			$("#msg_frm_personal").html(x[1]);
			
			// lleno el array
			array_procesar_personal="";
			array_procesar_personal=x[0];
			
			
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			
		})
	}






function Procesar_Personal(){
	
	if(array_procesar_personal!=""){
		
		var cantidad_personal = $("#mod_cantidad_personal").val();
		
		var parametros = {cantidad_personal:cantidad_personal, array_procesar_personal:array_procesar_personal}

		
		$.ajax({
			beforeSend:function(){
				
				
				swal({
					  title: "Retenciones",
					  text: "Procesando",
					  icon: "view/images/capremci_load.gif",
					  buttons: false,
					  closeModal: false,
					  allowOutsideClick: false
					});
				
			},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=Procesar_Personal",
			type:"POST",
			dataType:"json",
			data:parametros
		}).done(function(x){
			
			if( x.estatus != undefined ){
				
				if( x.estatus == "OK"){
					var stext = x.mensaje;
					if(x.xml != ""){
						stext += x.xml;
					}
					$("#msg_frm_personal").html("");
					$("#msg_frm_personal").html(x.html);
					
					swal({title:"TRANSACCIÓN OK",text:x.mensaje, icon:"success"})
		    		.then((value) => {
		    			
		    			window.location.reload();
		    		});	
									
				}else{
					 $("#msg_frm_personal").html("");
                    $("#msg_frm_personal").html(x.html);
					
                    swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n"+x.mensaje,icon:"error"})
                    .then((value) => {
		    			
		    			window.location.reload();
		    		});
                    
					
					
				}
			
			}
			
			
		}).fail(function(xhr,status,error){
			
			
			let err = xhr.responseText		
			console.log(err);
			if (err.includes("Warning") || err.includes("Notice") || err.includes("Error")){			
				
				swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n",icon:"error"})
                .then((value) => {
	    			
	    			window.location.reload();
	    		});
						
			}
			
			
		})
		
		
		
	}	else{
		
		
		
		swal({title:"ERROR TRANSACCIÓN",text:"NO EXISTE DATOS PARA PROCESAR \n",icon:"error"})
        .then((value) => {
			
			window.location.reload();
		});
		
		
		
	}
	
	
}

//termina personal















//para personal cta desembolsar


$("#patronal_registrados").on("click","#btn_patronal",function(event){

	var $div_respuesta = $("#msg_frm_patronal"); 
	
	$div_respuesta.text("").removeClass();
	  
	$("#mod_patronal").on('show.bs.modal',function(e){

		 var modal = $(this)
		 
		
		
		cargar_patronal_a_procesar();
		 
	}) 
	
})



function cargar_patronal_a_procesar(){
       	 
	var cantidad_patronal = $("#mod_cantidad_patronal").val();
	
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=cargar_patronal_a_procesar",
			type:"POST",
			//dataType:"json",
			data:{cantidad_patronal:cantidad_patronal}
		}).done(function(x){		
			
		
			x=JSON.parse(x);
			
			// imprimo html
			$("#msg_frm_patronal").html(x[1]);
			
			// lleno el array
			array_procesar_patronal="";
			array_procesar_patronal=x[0];
			
			
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			
		})
	}






function Procesar_Patronal(){
	
	if(array_procesar_patronal!=""){
		
	
		var cantidad_patronal = $("#mod_cantidad_patronal").val();
		
		
		var parametros = {cantidad_patronal:cantidad_patronal, array_procesar_patronal:array_procesar_patronal}

		
		$.ajax({
			beforeSend:function(){
				
				
				swal({
					  title: "Retenciones",
					  text: "Procesando",
					  icon: "view/images/capremci_load.gif",
					  buttons: false,
					  closeModal: false,
					  allowOutsideClick: false
					});
				
			},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=Procesar_Patronal",
			type:"POST",
			dataType:"json",
			data:parametros
		}).done(function(x){
			
			if( x.estatus != undefined ){
				
				if( x.estatus == "OK"){
					var stext = x.mensaje;
					if(x.xml != ""){
						stext += x.xml;
					}
					$("#msg_frm_patronal").html("");
					$("#msg_frm_patronal").html(x.html);
					
					swal({title:"TRANSACCIÓN OK",text:x.mensaje, icon:"success"})
		    		.then((value) => {
		    			
		    			window.location.reload();
		    		});	
									
				}else{
					$("#msg_frm_patronal").html("");
                    $("#msg_frm_patronal").html(x.html);
					
                    swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n"+x.mensaje,icon:"error"})
                    .then((value) => {
		    			
		    			window.location.reload();
		    		});
                    
					
					
				}
			
			}
			
			
		}).fail(function(xhr,status,error){
			
			
			let err = xhr.responseText		
			console.log(err);
			if (err.includes("Warning") || err.includes("Notice") || err.includes("Error")){			
				
				swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n",icon:"error"})
                .then((value) => {
	    			
	    			window.location.reload();
	    		});
						
			}
			
			
		})
		
		
		
	}	else{
		
		
		
		swal({title:"ERROR TRANSACCIÓN",text:"NO EXISTE DATOS PARA PROCESAR \n",icon:"error"})
        .then((value) => {
			
			window.location.reload();
		});
		
		
		
	}
	
	
}




//termina personal cta. desembolsar






// empieza patroanal cesantes



$("#cesantes_registrados").on("click","#btn_cesantes",function(event){

	var $div_respuesta = $("#msg_frm_patronal"); 
	
	$div_respuesta.text("").removeClass();
	  
	$("#mod_cesantes").on('show.bs.modal',function(e){

		 var modal = $(this)
		 
		
		
		cargar_cesantes_a_procesar();
		 
	}) 
	
})



function cargar_cesantes_a_procesar(){
     	 
	var cantidad_cesantes = $("#mod_cantidad_cesantes").val();
	
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=cargar_cesantes_a_procesar",
			type:"POST",
			//dataType:"json",
			data:{cantidad_cesantes:cantidad_cesantes}
		}).done(function(x){		
			
		
			x=JSON.parse(x);
			
			// imprimo html
			$("#msg_frm_cesantes").html(x[1]);
			
			// lleno el array
			array_procesar_cesantes="";
			array_procesar_cesantes=x[0];
			
			
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			
		})
	}






function Procesar_Cesantes(){
	
	if(array_procesar_cesantes!=""){
		
	
		var cantidad_cesantes = $("#mod_cantidad_cesantes").val();
		
		
		var parametros = {cantidad_cesantes:cantidad_cesantes, array_procesar_cesantes:array_procesar_cesantes}

		
		$.ajax({
			beforeSend:function(){
				
				
				swal({
					  title: "Retenciones",
					  text: "Procesando",
					  icon: "view/images/capremci_load.gif",
					  buttons: false,
					  closeModal: false,
					  allowOutsideClick: false
					});
				
			},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=Procesar_Cesantes",
			type:"POST",
			dataType:"json",
			data:parametros
		}).done(function(x){
			
			if( x.estatus != undefined ){
				
				if( x.estatus == "OK"){
					var stext = x.mensaje;
					if(x.xml != ""){
						stext += x.xml;
					}
					$("#msg_frm_cesantes").html("");
					$("#msg_frm_cesantes").html(x.html);
					
					swal({title:"TRANSACCIÓN OK",text:x.mensaje, icon:"success"})
		    		.then((value) => {
		    			
		    			window.location.reload();
		    		});	
									
				}else{
					$("#msg_frm_cesantes").html("");
                  $("#msg_frm_cesantes").html(x.html);
					
                  swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n"+x.mensaje,icon:"error"})
                  .then((value) => {
		    			
		    			window.location.reload();
		    		});
                  
					
					
				}
			
			}
			
			
		}).fail(function(xhr,status,error){
			
			
			let err = xhr.responseText		
			console.log(err);
			if (err.includes("Warning") || err.includes("Notice") || err.includes("Error")){			
				
				swal({title:"ERROR TRANSACCIÓN",text:"REVISAR DATOS ENVIADOS \n",icon:"error"})
              .then((value) => {
	    			
	    			window.location.reload();
	    		});
						
			}
			
			
		})
		
		
		
	}	else{
		
		
		
		swal({title:"ERROR TRANSACCIÓN",text:"NO EXISTE DATOS PARA PROCESAR \n",icon:"error"})
      .then((value) => {
			
			window.location.reload();
		});
		
		
		
	}
	
	
}


// termina patronal cesantes

