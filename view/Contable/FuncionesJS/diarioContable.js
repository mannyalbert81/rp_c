$(document).ready(function(){
	
	
})

$( "#codigo_cuenta" ).autocomplete({

	source: "index.php?controller=LibroMayor&action=AutocompleteCodigo",
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
			url:'index.php?controller=LibroMayor&action=AutocompleteCodigo',
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

	source: "index.php?controller=LibroMayor&action=AutocompleteNombre",
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
			url:'index.php?controller=LibroMayor&action=AutocompleteNombre',
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


$('#frm_libro_diarios').on('submit',function(event){

	var parametros = new FormData(this)
	
	parametros.append('action','ajax')
	
	/*parametros.forEach((value,key) => {
      console.log(key+" "+value)
	});*/
	
	
	if(!validafecha()){
		return false
	}
	
	window.open('index.php?controller=LibroDiario&action=diarioContable&peticion=ajax','_blank');
	
	$.ajax({
		url:'index.php?controller=LibroDiario&action=diarioContable', 
		contentType: false, //importante enviar este parametro en false
        processData: false,/*dataType:'json',*/
        type:'POST',
        data:parametros
		}).done(function(respuesta){
			
			//PDFObject.embed(respuesta, "#detalle_diario");		
			//window.open('','_blank');
			
		}).fail(function(){})
		
	event.preventDefault();
})

function validafecha(){
	var desde = $('#desde_diario').val()
	var hasta = $('#hasta_diario').val()
	
	if(desde!='' && hasta!=''){
		if(desde>=hasta){
			$('#mensaje_desde_diario').text('Fecha Ingresada debe ser menor').fadeIn();
			return false
		}
	}
	
	return true
}

$('#frm_libro_diario').on('submit',function(event){

	var parametros = new FormData(this)
	
	parametros.append('action','ajax')
	
	
	if(!validafecha()){
		return false
	}
	
})

$('#desde_diario').on('focus',function(){$('#mensaje_desde_diario').text('').fadeOut();})

$('#btn_export_excel').on('click',function(){
	
	var formulario = $('#frm_libro_diario')
	var parametros = formulario.serialize();
	
	
	$.ajax({
		url:'index.php?controller=LibroDiario&action=dataToExcel',
		type:'POST',
		dataType:'json',
		data:parametros+'&peticion=js'
	}).done(function(data){
		
		
		if(data.cantidad>0){
			
		   var newArr = [];
		  
		   while(data.datos_detalle.length) newArr.push(data.datos_detalle.splice(0,8));
		   
		   for(var i=1; i<newArr.length; i++){
			   
			   if(newArr[i][6]!=" "){
				   newArr[i][6]=parseFloat(newArr[i][6]).toFixed(2);
				   console.log(newArr[i][6]);
			   }
			   if(newArr[i][7]!=" "){
				   newArr[i][7]=parseFloat(newArr[i][7]);
			   }
			   
		   }
		   
		   var dt = new Date();
		   var m=dt.getMonth();
		   m+=1;
		   var y=dt.getFullYear();
		   var d=dt.getDate();
		   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
		   var wb =XLSX.utils.book_new();
		   wb.SheetNames.push("DiarioContable");
		   var ws = XLSX.utils.aoa_to_sheet(newArr);
		   wb.Sheets["DiarioContable"] = ws;
		   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
		   function s2ab(s) { 
	            var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
	            var view = new Uint8Array(buf);  //create uint8array as viewer
	            for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
	            return buf;    
		   }
	       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'DiarioContable'+fecha+'.xlsx');
		}
		 
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText;
		//alert(err)
	})
	
	return false;
	
})

