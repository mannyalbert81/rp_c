 
var array_procesar_personal;
var array_procesar_patronal;
var array_procesar_cesantes;
var array_procesar_cesantias_patronales;


$(document).ready( function (){
	
	load_saldo_cartera();
	load_operaciones();
		
	
	load_personal(1);
	load_patronal(1);
	load_cesantes(1);
	load_cesantias_patronales(1);
		
	array_procesar_personal="";
	array_procesar_patronal="";
	array_procesar_cesantes="";
	array_procesar_cesantias_patronales="";
	
});
  

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { 
	var element=$(this); 
	if( element.attr("href") == "#riesgo1" ){	
		//viewTable.tabla.columns.adjust().draw();
	}else if( element.attr("href") == "#riesgo2" ){ 
		viewTable.tabla.columns.adjust().draw()
	}else if( element.attr("href") == "#riesgo3" ){ 
		viewTable_operaciones.tabla.columns.adjust().draw()
	} 
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


	
function load_cesantias_patronales(pagina){

	var search=$("#search_cesantes").val();
    var search_fechadesde=$("#search_fechadesde_cesantes").val();
	var search_fechahasta=$("#search_fechahasta_cesantes").val();

	
    var con_datos={
				  action:'ajax',
				  page:pagina,
				  search:search,
		  		  search_fechadesde:search_fechadesde,
				  search_fechahasta:search_fechahasta	
				  };
		  
  $("#load_cesantias_patronales_registrados").fadeIn('slow');
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_cesantias_patronales_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=TributarioImpuestoSuperavit&action=consulta_cesantias_patronales&search='+search,
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#cesantias_patronales_registrados").html(x);
              $("#load_cesantias_patronales_registrados").html("");
              $("#tabla_cesantias_patronales").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#cesantias_patronales_registrados").html("Ocurrio un error al cargar la información de Superavit Patronal..."+estado+"    "+error);
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






///cesantias Manuel



$("#cesantias_patronales_registrados").on("click","#btn_cesantias_patronales",function(event){

	
	var $div_respuesta = $("#msg_frm_cesantias_patronales"); 
	
	$div_respuesta.text("").removeClass();
	  
	$("#mod_cesantias_patronales").on('show.bs.modal',function(e){

		 var modal = $(this)
		 
		
		
		cargar_cesantias_patronales_a_procesar();
		 
	}) 
	
})



function cargar_cesantias_patronales_a_procesar(){
     	 
	var cantidad_cesantias_patronales = $("#mod_cantidad_cesantias_patronales").val();
	
	var search_fechadesde=$("#search_fechadesde_cesantes").val();
	var search_fechahasta=$("#search_fechahasta_cesantes").val();

	
	
		$.ajax({
			beforeSend:function(){},
			url:"index.php?controller=TributarioImpuestoSuperavit&action=cargar_cesantias_patronales_a_procesar",
			type:"POST",
			//dataType:"json",
			data:{cantidad_cesantias_patronales:cantidad_cesantias_patronales,
				  search_fechadesde:search_fechadesde,
				  search_fechahasta:search_fechahasta 
			}
		}).done(function(x){		
			
			
			
			x=JSON.parse(x);
			
			// imprimo html
			$("#msg_frm_cesantias_patronales").html(x[1]);
			
			// lleno el array
			array_procesar_cesantias_patronales="";
			array_procesar_cesantias_patronales=x[0];
		
			
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err)
			
		})
	}





function Procesar_Cesantias_Patronales(){
	
	
	
	if(array_procesar_cesantias_patronales !=""){
		
		
	
		var cantidad_cesantias_patronales = $("#mod_cantidad_cesantias_patronales").val();
		var  search_fechadesde=$("#search_fechadesde_cesantes").val();
    	var search_fechahasta=$("#search_fechahasta_cesantes").val();
	
			
		var parametros = {cantidad_cesantias_patronales:cantidad_cesantias_patronales, array_procesar_cesantias_patronales:array_procesar_cesantias_patronales, search_fechadesde:search_fechadesde, search_fechahasta:search_fechahasta  }

		
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
			url:"index.php?controller=TributarioImpuestoSuperavit&action=Procesar_Cesantias_Patronales",
			type:"POST",
			dataType:"json",
			data:parametros
		}).done(function(x){
			/*
			if( x.estatus == "PRUEBA" )
			{
				console.log('Hola');
				console.log(x.html);
			}
			*/
			if( x.estatus != undefined ){
				
				if( x.estatus == "OK"){
					var stext = x.mensaje;
					if(x.xml != ""){
						stext += x.xml;
					}
					$("#msg_frm_cesantias_patronales").html("");
					$("#msg_frm_cesantias_patronales").html(x.html);
					
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



/********************************************************************* CAMBIOS DANNY **************************************************************/
//variable de vista
var view	= view || {};
//variable para dataTable
var viewTable = viewTable || {};

viewTable.tabla  = null;
viewTable.nombre = 'tblsaldo_cartera';
viewTable.params = { 'input_search': '' };
viewTable.contenedor = $("#div_listado_cuentas_pagar_aplicadas");


var idioma_espanol = {
	    "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla &#128543; ",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
}

var load_saldo_cartera	= function(){	
	
	viewTable.tabla	=  $( '#'+viewTable.nombre ).DataTable({	    
	    'destroy' : true,	    
	    'lengthMenu': [ [ 10, 25, 50, -1], [ 10, 25, 50, "All"] ],
	    'order': [[ 0, "asc" ]],	    
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        "columnDefs": [
            { className: "dt-body-right", targets: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ] },
            { className: "dt-body-center", targets: [ 0 ] }
          ],
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	/*{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }*/
        ],
        'language':idioma_espanol
	 });
		
}

var viewTable_operaciones = viewTable_operaciones || {};
viewTable_operaciones.tabla  = null;
viewTable_operaciones.nombre = 'tbloperaciones';

var load_operaciones	= function(){	
	
	viewTable_operaciones.tabla	=  $( '#'+viewTable_operaciones.nombre ).DataTable({	    
	    'destroy' : true,	    
	    'lengthMenu': [ [ 10, 25, 50, -1], [ 10, 25, 50, "All"] ],
	    'order': [[ 0, "asc" ]],	    
		'scrollY': "80vh",
        'scrollCollapse':true,
        'fixedHeader': {
            header: true,
            footer: true
        },
        "columnDefs": [
            { className: "dt-body-right", targets: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ] },
            { className: "dt-body-center", targets: [ 0 ] }
          ],
        dom: "<'row'<'col-sm-6'<'box-tools pull-right'B>>><'row'<'col-sm-6'l><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'<'#colvis'>p>>",
        buttons: [
        	/*{ "extend": 'excelHtml5',  "titleAttr": 'Excel', "text":'<span class="fa fa-file-excel-o fa-2x fa-fw"></span>',"className": 'no-padding btn btn-default btn-sm' }*/
        ],
        'language':idioma_espanol
	 });
		
}


