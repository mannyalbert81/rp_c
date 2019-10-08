var regid=0;

$(document).ready( function (){
	load_marcaciones(1);
	ReporteNomina();
	$(":input").inputmask();
		
});

$("#archivo_registro").change(function(e){
	var archivo = $("#archivo_registro").val();

	if (archivo=="")
		{
		$("#nombre_archivo").val("");
		}
	else
	{
		var fileName = e.target.files[0].name;
		$("#nombre_archivo").val(fileName);
	}

});

$( "#cedula_empleado" ).autocomplete({

	source: "index.php?controller=Marcaciones&action=AutocompleteCedula",
	minLength: 3,
    select: function (event, ui) {
       // Set selection          
       $('#id_usuarios').val(ui.item.id);
       $('#cedula_empleado').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#cedula_empleado').val();            
        return false; 
    } 
}).focusout(function() {
		if(document.getElementById('cedula_empleado').value != ''){
		$.ajax({
			url:'index.php?controller=Marcaciones&action=AutocompleteCedula',
			type:'POST',
			dataType:'json',
			data:{term:$('#cedula_empleado').val()}
		}).done(function(respuesta){
			if(JSON.stringify(respuesta)!='{}'){
				
				$('#nombres_empleado').val(respuesta.nombres_empleados);
			
			}else{ $("#cedula_usuarios").val("");}
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			console.log(err)
		});
	}
	
});

$( "#cedula_empleado1" ).autocomplete({

	source: "index.php?controller=Marcaciones&action=AutocompleteCedula",
	minLength: 3,
    select: function (event, ui) {
       // Set selection          
       $('#id_usuarios').val(ui.item.id);
       $('#cedula_empleado1').val(ui.item.value); // save selected id to input      
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#cedula_empleado1').val();            
        return false; 
    } 
}).focusout(function() {
		if(document.getElementById('cedula_empleado1').value != ''){
		$.ajax({
			url:'index.php?controller=Marcaciones&action=AutocompleteCedula',
			type:'POST',
			dataType:'json',
			data:{term:$('#cedula_empleado1').val()}
		}).done(function(respuesta){
			if(JSON.stringify(respuesta)!='{}'){
			
			}else{ $("#cedula_usuarios1").val("");}
			
		}).fail( function( xhr , status, error ){
			 var err=xhr.responseText
			console.log(err)
		});
	}
	
});

function LimpiarCedula()
{
	$('#cedula_empleado1').val("");
}

function EditAdvertencias(cedula){
	$('#cedula_empleado1').val(cedula);
	$('#estado_registro').val(2);
	$('#periodo_marcaciones').val(2);
	load_marcaciones(1);
	
	
}

function load_marcaciones(pagina){

	var mes = new Date().getMonth();
	var year = new Date().getFullYear();
	var dia = new Date().getDate();
	var fi="";
	var ff="";
	if (dia<22) mes--;
	if (mes<=9)	fi = year+"-0"+mes+"-22";
	else fi = year+"-"+mes+"-22";
	if (mes==11 && dia >=22)mes=1;
	else mes++;
	if (mes<=9) ff = year+"-0"+mes+"-21";
	else ff = year+"-"+mes+"-21";
    var search=$("#search").val();
    var periodo=$("#periodo_marcaciones").val();
    var ncedula = $("#cedula_empleado1").val();
    if (ncedula.includes("_")) ncedula="";
    var registros=$("#estado_registro").val();
var con_datos={
				  action:'ajax',
				  page:pagina,
				  periodo:periodo,
				  dia_inicio:fi,
				  dia_final:ff,
				  numero_cedula:ncedula,
				  estado_registros:registros
				  };
$.ajax({
    beforeSend: function(objeto){
      $("#load_marcaciones").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
    },
    url: 'index.php?controller=Marcaciones&action=consulta_marcaciones&search='+search,
    type: 'POST',
    data: con_datos,
    success: function(x){
      $("#marcaciones").html(x);
      $("#load_marcaciones").html("");
      $("#tabla_marcaciones").tablesorter(); 
      
    },
   error: function(jqXHR,estado,error){
     $("#marcaciones").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
   }
 });
		  
$("#load_marcaciones").fadeIn('slow');

$.ajax({
    beforeSend: function(objeto){
      $("#load_marcaciones").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
    },
    url: 'index.php?controller=Marcaciones&action=consulta_marcaciones&search='+search,
    type: 'POST',
    data: con_datos,
    success: function(x){
      $("#marcaciones").html(x);
      $("#load_marcaciones").html("");
      $("#tabla_marcaciones").tablesorter(); 
      
    },
   error: function(jqXHR,estado,error){
     $("#horarios").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
   }
 });
MostrarNotificacion();
}

function validarFecha(fecha)
{
	var elem =fecha.split("-");
	var ld = new Date(elem[0],elem[1],0).getDate();
	if (elem[2]>ld)	return false;
	else return true;
}

function InsertarRegistro()
{
	var cedula=$("#cedula_empleado").val();
	var hora=$("#hora_marcacion").val();
	var fecha=$("#fecha_marcacion").val();
	var tipor =$("#tipo_registro").val();
	
	if (tipor=="")
	{
$("#mensaje_tipo_registro").text("Seleccione tipo");
$("#mensaje_tipo_registro").fadeIn("slow");
$("#mensaje_tipo_registro").fadeOut("slow");
	}
	
	if (!(validarFecha(fecha)))
			{
		$("#mensaje_fecha_marcacion").text("Fecha invalida");
		$("#mensaje_fecha_marcacion").fadeIn("slow");
		$("#mensaje_fecha_marcacion").fadeOut("slow");
			}
	if (cedula=="" || cedula.includes("_"))
		{
	$("#mensaje_cedula_empleado").text("Ingrese cédula");
	$("#mensaje_cedula_empleado").fadeIn("slow");
	$("#mensaje_cedula_empleado").fadeOut("slow");
		}
	if (hora=="" || hora.includes("_"))
	{
$("#mensaje_hora_marcacion").text("Ingrese hora");
$("#mensaje_hora_marcacion").fadeIn("slow");
$("#mensaje_hora_marcacion").fadeOut("slow");
	}
	if (fecha=="" || fecha.includes("_"))
	{
$("#mensaje_fecha_marcacion").text("Ingrese fecha");
$("#mensaje_fecha_marcacion").fadeIn("slow");
$("#mensaje_fecha_marcacion").fadeOut("slow");
	}
 if (cedula!="" && hora!="" && fecha!="" &&  !(hora.includes("_")) &&  !(cedula.includes("_"))&&  !(fecha.includes("_")) && validarFecha(fecha)
		 && tipor!="")
 {
	 validarFecha(fecha);
	$.ajax({
		    url: 'index.php?controller=Marcaciones&action=AgregarMarcacion',
		    type: 'POST',
		    data: {
		    	   id_registro: regid,
		    	   hora_marcacion: hora,
		    	   fecha_marcacion: fecha,
		    	   numero_cedula: cedula,
		    	   tipo_registro: tipor
		    },
		})
		.done(function(x) {
			console.log(x);
			if(x==1)
				{
				load_marcaciones(1);
				ReporteNomina()
				swal({
			  		  title: "Registro",
			  		  text: "Registro actualizado",
			  		  icon: "success",
			  		  button: "Aceptar",
			  		});
				$("#cedula_empleado").val("");
				$("#nombres_empleado").val("");
				$("#hora_marcacion").val("");
				$("#fecha_marcacion").val("");
				$("#tipo_registro").val("");
				if(document.getElementById('fecha_marcacion').readOnly)
				 {
				 document.getElementById('fecha_marcacion').readOnly = false;
				 }
				if(document.getElementById('cedula_empleado').readOnly)
				{
				document.getElementById('cedula_empleado').readOnly = false;
				}
				rgedit=0;
				}
			else{
				swal({
			  		  title: "Registro",
			  		  text: "Error al actualizar el registro",
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				$("#cedula_empleado").val("");
				$("#nombres_empleado").val("");
				$("#hora_marcacion").val("");
				$("#fecha_marcacion").val("");
				if(document.getElementById('fecha_marcacion').readOnly)
				 {
				 document.getElementById('fecha_marcacion').readOnly = false;
				 }
				if(document.getElementById('cedula_empleado').readOnly)
				{
				document.getElementById('cedula_empleado').readOnly = false;tipo_registro
				}
				
				
				
				
				rgedit=0;
				
			}
			
		})
		.fail(function() {
		    console.log("error");
		});
 }
 
}

function EditarMarcaciones(idregistro, ncedula,nombres, hora, fecha, tipor)
{
	regid=idregistro;
	$("#cedula_empleado").val(ncedula);
	$("#nombres_empleado").val(nombres);
	$("#hora_marcacion").val(hora);
	$("#fecha_marcacion").val(fecha);
	$("#tipo_registro").val(tipor);
	document.getElementById('fecha_marcacion').readOnly = true;
	document.getElementById('cedula_empleado').readOnly = true;
	$('html, body').animate({ scrollTop: 0 }, 'fast');
}

function LimpiarCampos()
{
	$("#cedula_empleado").val("");
	$("#nombres_empleado").val("");
	$("#hora_marcacion").val("");
	$("#fecha_marcacion").val("");
	$("#tipo_registro").val("");
	
}
function ActualizarRegistros(arr, dcontrol, idofi)
{
	
	var archivo=document.getElementById('archivo_registro').files[0];
	var fileName = archivo.name;
	swal({
		  title: "Actualización de registro",
		  text: "Copiando datos del archivo: "+fileName,
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
	$.ajax({
	    url: 'index.php?controller=Marcaciones&action=ActualizarRegistros',
	    type: 'POST',
	    data: {
	    	   registros: JSON.stringify(arr),
	    	   fecha_inicio: dcontrol[0],
	    	   fecha_final: dcontrol[dcontrol.length-1],
	    	   id_oficina:idofi
	    },
	})
	.done(function(x) {
		
		if (!(x.includes("Warning")))
			{
			load_marcaciones(1);
			ReporteNomina();
			swal({
		  		  title: "Registro",
		  		  text: "Registro actualizado",
		  		  icon: "success",
		  		  button: "Aceptar",
		  		});
			$("#archivo_registro").val("");
			$("#nombre_archivo").val("");
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al realizar la actualizacion: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
		
		
	})
	.fail(function() {
	    console.log("error");
	});
	
} 


function SubirArchivo()
{
	
var arch=$('#nombre_archivo').val();
	
	if (arch=="")
	{
$("#mensaje_archivo").text("Seleccione archivo");
$("#mensaje_archivo").fadeIn("slow");
$("#mensaje_archivo").fadeOut("slow");
	}
	else
		{
	var archivo=document.getElementById('archivo_registro').files[0];
	var fileName = archivo.name;
	var extension = fileName.split(".");
	var l=extension.length;
	if (extension[l-1]=="xlsx")
		{
		swal({
			  title: "Actualización de registros",
			  text: "Revisando datos del archivo: "+fileName,
			  icon: "view/images/capremci_load.gif",
			  buttons: false,
			  closeModal: false,
			  allowOutsideClick: false
			});
			
		  	var arr;
			var reader = new FileReader();
		    reader.onload = function(e) {
		      var data = e.target.result;
		      var workbook = XLSX.read(data, {
		        type: 'binary',
		        raw: 'true'
		      });

		      workbook.SheetNames.forEach(function(sheetName) {
		        // Here is your object
		        var XL_row_object = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
		        var json_object = JSON.stringify(XL_row_object);
		        arr=JSON.parse(json_object);
		        
		        if(arr[0].hasOwnProperty('Cedula') && arr[0].hasOwnProperty('Nombre')&& arr[0].hasOwnProperty('Fecha')
				&& arr[0].hasOwnProperty('Horario')&& arr[0].hasOwnProperty('Inicio')&& arr[0].hasOwnProperty('Salida')
				&& arr[0].hasOwnProperty('Registro Entrada')&& arr[0].hasOwnProperty('Registro Salida'))
		        {
		        	var campos = Object.keys(arr[0]);
		        	var cedulas = [];
		        	var cid=null;
		        	arr.forEach(function (element)
		        			{
		        		if (cid!=element["Cedula"] && element["Cedula"]!=" ")
		        			{
		        			cid=element["Cedula"];
		        			cedulas.push(cid);
		        			}
		        	}		
		        	);
		        	$.ajax({
		    			url:'index.php?controller=Marcaciones&action=GetCedulas',
		    			type:'POST',
		    			dataType:'json',
		    			data:{term:""}
		    		}).done(function(respuesta){
		    				if (respuesta[0]=="OK")
		    				{
		    				var vercid = 0;
		    				var incid = [];
		    				console.log(cedulas);
		    				const primced = respuesta.find(cedula => cedula.numero_cedula_empleados === cedulas[0]);
		    				if (typeof(primced) === "undefined")
		    					{
		    					swal( {
			    					  title: "Error",
			    					  text: "La cédula "+cedulas[0]+" no existe entre los empleados activos",
								      icon: "error",
								     });
		    					$("#archivo_registro").val("");
			    				$("#nombre_archivo").val("");
		    					 return false;
		    					}
		    				var idof = primced["id_oficina"];
		    				var nomofi = primced["nombre_oficina"]; 
		    				respuesta = respuesta.filter(function (value, index, arr)
		    						{
		    					return value['id_oficina'] === idof;
		    						});
		    				
		    				var mismaoficina=true;
		    				for(var i = 0; i<cedulas.length; i++)
		    					{
		    					var cidext = false;
		    					for (var j = 0; j<respuesta.length ; j++)
		    						{
		    						if (cedulas[i] == respuesta[j]['numero_cedula_empleados'])
		    							{
		    							vercid++;
		    							cidext = true;

		    		    				
		    							if (idof != respuesta[j]['id_oficina'])
		    								{
		    								mismaoficina=false;
		    								}
		    							}
		    						}
		    					if (!cidext)
		    						{
		    						incid.push(cedulas[i]);
		    						
		    						}
		    					}
		    				
		    				if (vercid==cedulas.length)
		    					{
		    					 if (vercid == respuesta.length)
		    					 {
		    						 var patt = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
				    					var fechaver = true;
				    					var celdas="|";
				    					var celind=2;
				    					arr.forEach(function (element)
				    		        			{
				    							
				    						     if (!patt.test(element["Fecha"]))
				    						    	 {
				    						    	 fechaver=false;
				    						    	 console.log(element["Fecha"]);
				    						    	 celdas = celdas+" "+celind+" |";
				    						    	 }
				    						     celind++;
				    		        			});
				    					if (fechaver)
				    						{
					    						var controlmes = true;
					    						var mesarchivo = [];
					    						var ctrmes = 0;
					    						var ctryear = 0;
					    						var yeararchivo = [];
					    						var verifper = true;
					    						arr.forEach(function (element)
						    		        			{
						    						     var meses = element["Fecha"].split("/");
						    						     if (meses[1]!=ctrmes && !mesarchivo.includes(meses[1]))
						    		        			{
						    						    	 ctrmes=meses[1];
						    						    	 mesarchivo.push(ctrmes);
						    		        			}
						    						     if (meses[2]!=ctryear && !yeararchivo.includes(meses[2]))
							    		        			{
							    						    	 ctryear=meses[2];
							    						    	 yeararchivo.push(ctryear);
							    		        			}
						    		        			});
					    						
					    						if (yeararchivo.length>1) yeararchivo=yeararchivo[1];
					    						var mes = new Date().getMonth();
					    						mes++;
					    						var year = new Date().getFullYear();
					    						var dia_hoy= new Date().getDate();
					    						var mes_inicio=0;
					    						var mes_fin=0;
					    						var anio_inicio=year;
					    						var anio_fin=year;
					    						console.log(dia_hoy+" hoy")
					    						if(dia_hoy<=21)
													{
													mes_inicio=mes-2;
													mes_fin=mes-1;
													if (mes_inicio<1)
														{
														mes_inicio=12;
														anio_inicio=year-1;
														}
													}
												else
													{
													mes_inicio=mes;
													mes_fin=mes+1;
													if (mes_fin==13)
														{
														mes_fin=1;
														anio_fin=year+1;
														}
													}
					    							
					    						if (mesarchivo.length <=2 && mesarchivo[0]==mes-1 && mesarchivo[1]==mes && yeararchivo==anio_fin)
					    						{
					    							
						    						var ld = new Date(year,mes_inicio,0).getDate();
						    						var diainicio = 22;
						    						var diafinal = 21;
						    						var dcontrol = [];
						    						
						    						for (var i=diainicio; i <= ld; i++)
						    							{
						    							var fechac = i+"/"+mes_inicio+"/"+year;
						    							dcontrol.push(fechac);
						    							}
						    						for (var i=1; i <= diafinal; i++)
					    							{
					    							var fechac = i+"/"+mes_fin+"/"+year;
					    							dcontrol.push(fechac);
					    							}
						    						console.log("fechas de control  "+dcontrol);
						    						var cid = cedulas[0];
						    						var farchivo = [];
						    						var verper = true;
						    						var totalfechas = true;
						    						var multifechas = false;
						    						var fechas_repetidas=[];
						    						var cedula_fecha_repetida='';
						    						var cedprob = [];
						    						for (var i=0; i<arr.length; i++)
						    						{
						    							if (cid==arr[i]["Cedula"] && !farchivo.includes(arr[i]["Fecha"]))
					    			        			{
					    			        			farchivo.push(arr[i]["Fecha"]);
					    			        			}
					    			        		else
					    			        			{
					    			        			if (cid!=arr[i]["Cedula"])
					    			        				{
					    			        				
					    			        				if (farchivo.length != dcontrol.length)
				    			        					{
				    			        				     cedprob.push(arr[i-1]["Cedula"]);
				    			        					}
				    			        				else
				    			        					{
				    			        				farchivo.forEach(function(f)
					    			        					 {
				    			        					 
					    			        				  var fcd = false;
					    			        				 	dcontrol.forEach(function(dc)
					    			        				 			{
					    			        				 			if (f==dc)
					    			        				 				{
					    			        				 				fcd=true;
					    			        				 				}
					    			        				 			});
					    			        				 		if(!fcd)
					    			        				 			{
					    			        				 			verper=false;
					    			        				 			}
					    			        				 		 var contador = 0
						    			        				       for (var k=0; k<arr.length; k++)
						    			        				    	   {
						    			        				    	   if (cid==arr[k]["Cedula"] && f==arr[k]["Fecha"])
						    			        				    		   {
						    			        				    		   contador++;
						    			        				    		   cedula_fecha_repetida=arr[k]["Cedula"];
						    			        				    		   }
						    			        				    	   }
						    			        					   if(contador > 2)
						    			        						   {multifechas=true;
						    			        						   fechas_repetidas.push(cedula_fecha_repetida);
						    			        						   }
						    			        					   
						    			        					   contador=0;
					    			        					 });
				    			        					  
				    			        					}
					    			        				 cid=arr[i]["Cedula"];
					    			        				 
						    			        			 farchivo=[];
						    			        			 i--;
					    			        				}
					    			        			if (i==arr.length-1)
				    			        				{
					    			        				if (farchivo.length != dcontrol.length)
					    			        					{
					    			        				     cedprob.push(arr[i]["Cedula"]);
					    			        					}
					    			        				else
					    			        					{
					    			        				farchivo.forEach(function(f)
						    			        					 {
						    			        				  var fcd = false;
						    			        				 	dcontrol.forEach(function(dc)
						    			        				 			{
						    			        				 			if (f==dc)
						    			        				 				{
						    			        				 				fcd=true;
						    			        				 				}
						    			        				 			});
						    			        				 		if(!fcd)
						    			        				 			{
						    			        				 			verper=false;
						    			        				 			}
						    			        				 		var contador = 0
							    			        				       for (var k=0; k<arr.length; k++)
							    			        				    	   {
							    			        				    	   if (cid==arr[k]["Cedula"] && f==arr[k]["Fecha"])
							    			        				    		   {
							    			        				    		   contador++;
							    			        				    		   }
							    			        				    	   }
							    			        					   if(contador > 2)
							    			        						   {multifechas=true;}
							    			        					   contador=0;
						    			        					 });
					    			        					}
				    			        				}
					    			        				
					    			        			}		
						    						}
						    						if (verper && cedprob.length < 1 && !multifechas)
						    							{
						    							var validarhoras = true;
						    							var celda = 2;
						    							var errhoras = new Array();
						    							arr.forEach(function (a)
						    									{
						    									if (!(typeof a["Registro Salida"] === 'undefined') && a["Registro Salida"]!="") 
						    										{
						    										var patt = /^([01][0-9]|[2][0-3])[:]([0-5][0-9])[:]([0-5][0-9])/;
						    										if (!patt.test(a["Registro Salida"]))
						    											{
						    											validarhoras=false;
						    											errhoras.push(["Salida",celda]);
						    											}
						    										}
						    									if (!(typeof a["Registro Entrada"] === 'undefined') && a["Registro Entrada"]!="") 
					    										{
					    										var patt = /^([01][0-9]|[2][0-3])[:]([0-5][0-9])[:]([0-5][0-9])/;
					    										if (!patt.test(a["Registro Entrada"]))
					    											{
					    											validarhoras=false;
					    											errhoras.push(["Entrada",celda]);
					    											}
					    										}
						    									celda++;
						    									});
						    							if (validarhoras)
						    								{
						    								
						    								swal("Archivo correcto", {
										  					      icon: "success",
										  					      buttons: false,
										  					      timer: 1000
										  					    }).then(function(){
										  					    	
										  					    	ActualizarRegistros(arr, dcontrol, idof);// ===========>EXITO EN LA COMPROBACIÓN
										  					    });
						    								
						    								    
						    								}
						    							else
						    								{
						    								console.log(errhoras);
						    								var errmsg = "Formato invalido en los registros:\n|";
						    														    				                
						    								for (var i=0;i<errhoras.length;i++){
						    									errmsg+=" "+errhoras[i][0]+" celda: "+errhoras[i][1]+" |";
						    									}
						    								$("#archivo_registro").val("");
						    								$("#nombre_archivo").val("");
						    								
						    								swal( {
										    					  title: "Error",
										    					  text: errmsg,
															      icon: "error",
															     });
						    								$("#archivo_registro").val("");
						    								$("#nombre_archivo").val("");
						    								
						    								}
						    							
						    							}
						    						else
						    							{
						    							if (cedprob.length > 0)
						    								{
						    								if (farchivo.length < dcontrol.length)
						    									{
						    									var errmsg = "Faltan fechas en las cédulas:\n|";
							    								cedprob.forEach(function (c){
							    									errmsg=errmsg+" "+c+" |";
							    								});
							    								swal( {
											    					  title: "Error",
											    					  text: errmsg,
																      icon: "error",
																     });
						    									
						    									}
						    								if (farchivo.length > dcontrol.length)
						    									{
						    									var errmsg = "Se han encontrado fechas incorrectas en las cédulas:\n|";
							    								cedprob.forEach(function (c){
							    									errmsg=errmsg+" "+c+" |";
							    								});
							    								swal( {
											    					  title: "Error",
											    					  text: errmsg,
																      icon: "error",
																     });
						    									}
						    									
						    								
						    								$("#archivo_registro").val("");
						    								$("#nombre_archivo").val("");
						    								}
						    							else
						    								
						    								{
						    								if (multifechas)
						    									{
						    									var errmsg = "Se ha encotrado horarios con fechas duplicadas :\n|";
						    									fechas_repetidas.forEach(function (c){
							    									errmsg=errmsg+" "+c+" |";
							    								});
						    									swal( {
											    					  title: "Error",
											    					  text: errmsg,
																      icon: "error",
																     });
						    									$("#archivo_registro").val("");
						    									$("#nombre_archivo").val("");
						    									}
						    								else
						    									{
						    									swal( {
											    					  title: "Error",
											    					  text: "Se ha encotrado fechas fuera del periodo de revisión",
																      icon: "error",
																     });
						    									$("#archivo_registro").val("");
						    									$("#nombre_archivo").val("");
						    									}
						    								
						    								}
						    							
						    							}
					    						}
					    						else
					    							{
					    							if (mesarchivo.length > 2)
					    								{
					    								swal( {
									    					  title: "Error",
									    					  text: "El perodio de revision contiene mas de dos meses",
														      icon: "error",
														     });
					    								$("#archivo_registro").val("");
					    								$("#nombre_archivo").val("");
					    								}
					    							else
					    								{
					    							let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
					    							swal( {
								    					  title: "Error",
								    					  text: "El perodio de revision del archivo es de " + meses[mesarchivo[0]-1]+"-"
								    					  + meses[mesarchivo[1]-1]+" del "+yeararchivo[0],
													      icon: "error",
													     });
					    							$("#archivo_registro").val("");
					    							$("#nombre_archivo").val("");
					    								}
					    							}
				    						
				    						}
				    					else
				    					{
				    						swal( {
						    					  title: "Error",
						    					  text: "Formato incorrecto de fecha en las filas: "+celdas,
											      icon: "error",
											     });
				    						$("#archivo_registro").val("");
				    						$("#nombre_archivo").val("");
				    					}
		    					 }
		    					 else
		    					 {
		    						 
		    						 if(vercid < respuesta.length)
		    							 {
		    						 var cedinex=[];
		    						 for (var i=1; i < respuesta.length ; i++)
		    							 {
		    							 var cidex = false;
		    							 for (var j=0; j <cedulas.length; j++)
		    								 {
		    								 if (respuesta[i]['numero_cedula_empleados']==cedulas[j])
		    									 {
		    									 cidex=true;
		    									 console.log(respuesta[i]['numero_cedula_empleados']);
		    									 }
		    								 }
		    							 if (!cidex)
		    								 {
		    								 cedinex.push(respuesta[i]['numero_cedula_empleados']);
		    								 }
		    							 }
		    						 var errmsg = "Las siguientes cédulas no constan en el archivo: |";
		    						 cedinex.forEach(function (element){
		    							 errmsg = errmsg+" " +element +" |";
		    						 });
		    						 swal( {
				    					  title: "Error",
				    					  text: errmsg,
									      icon: "error",
									     });
		    						 $("#archivo_registro").val("");
		    							$("#nombre_archivo").val("");
		    							}
		    						 else
		    							 {
		    							 if(vercid > respuesta.length)
		    							 {
		    								 swal( {
						    					  title: "Error",
						    					  text: "Se han encontrado cédulas duplicadas",
											      icon: "error",
											     });
		    								 $("#archivo_registro").val("");
		    									$("#nombre_archivo").val("");
		    							 }		    							 
		    							 }
		    					 }

		    					}
		    				else
		    					{
		    					console.log(vercid);
		    					
		    						var errmsg = "Las siguientes cédulas no existen, tienen un formato incorrecto o pertenecen a otra oficina :\n|";
		    						
			    					for (var i=0; i<incid.length ; i++)
			    						{
			    						errmsg=errmsg+" "+incid[i]+" |";
			    						}
			    					errmsg +="\nOficina en revisión: "+nomofi;
			    					swal( {
				    					  title: "Error",
				    					  text: errmsg,
									      icon: "error",
									     });
		    					
		    					
		    					$("#archivo_registro").val("");
		    					$("#nombre_archivo").val("");
		    					}
		    				}
		    			else
		    				{
		    				swal( {
		    					  title: "Error",
		    					  text: respuesta[1],
							      icon: "error",
							      buttons: false,
							      timer: 2000
							    });
		    				$("#archivo_registro").val("");
		    				$("#nombre_archivo").val("");
		    				}
		    			
		    			
		    		}).fail( function( xhr , status, error ){
		    			 var err=xhr.responseText
		    			console.log(err)
		    		});
		        	
		        	
		        }
		        else
		        {
		        	
		        	swal("El archivo no tiene el formato correcto", {
					      icon: "error",
					      buttons: false,
					      timer: 2000
					    });
		        	$("#archivo_registro").val("");
					$("#nombre_archivo").val("");
		        }
		        
		      })

		    };

		    reader.onerror = function(ex) {
		      console.log(ex);
		    };

		    reader.readAsBinaryString(archivo);
			    	
		}
	else
		{
		swal({
	  		  title: "Actualizacion de registro",
	  		  text: "Por favor, seleccione un archivo xlsx",
	  		  icon: "warning",
	  		  button: "Aceptar",
	  		});	
		$("#archivo_registro").val("");
			$("#nombre_archivo").val("");
		}
	}
		
}

function ReporteNomina()
{
	var mes = new Date().getMonth();
	mes++;
	var year = new Date().getFullYear();
	var dia_hoy= new Date().getDate();
	var mes_inicio=0;
	var mes_fin=0;
	var anio_inicio=year;
	var anio_fin=year;
	console.log(dia_hoy+" hoy")
	if(dia_hoy<=21)
		{
		mes_inicio=mes-2;
		mes_fin=mes-1;
		if (mes_inicio<1)
			{
			mes_inicio=12;
			anio_inicio=year-1;
			}
		}
	else
		{
		mes_inicio=mes;
		mes_fin=mes+1;
		if (mes_fin==13)
			{
			mes_fin=1;
			anio_fin=year+1;
			}
		}

 var diainicio = 22;
 var diafinal = 21;
 var fechai = diainicio+"/"+mes_inicio+"/"+year;
 var fechaf = diafinal+"/"+mes_fin+"/"+year;
 $.ajax({
	    url: 'index.php?controller=Marcaciones&action=GetReporte',
	    type: 'POST',
	    data: {
	    	   fecha_inicio:fechai,
	    	   fecha_final:fechaf
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#reporte").html(x);
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al obtener el reporte: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	});
 	
 
}

function MostrarNotificacion()
{
	var mes = new Date().getMonth();
	mes++;
	var year = new Date().getFullYear();
	var dia_hoy= new Date().getDate();
	var mes_inicio=0;
	var mes_fin=0;
	var anio_inicio=year;
	var anio_fin=year;
	console.log(dia_hoy+" hoy")
	if(dia_hoy<=21)
		{
		mes_inicio=mes-2;
		mes_fin=mes-1;
		if (mes_inicio<1)
			{
			mes_inicio=12;
			anio_inicio=year-1;
			}
		}
	else
		{
		mes_inicio=mes;
		mes_fin=mes+1;
		if (mes_fin==13)
			{
			mes_fin=1;
			anio_fin=year+1;
			}
		}
 
 var diainicio = 22;
 var diafinal = 21;
 var fechai = diainicio+"/"+mes_inicio+"/"+year;
 var fechaf = diafinal+"/"+mes_fin+"/"+year;
 
 console.log(fechai+"<=>"+fechaf);
 $.ajax({
	    url: 'index.php?controller=Marcaciones&action=MostrarNotificacion',
	    type: 'POST',
	    data: {
	    	   fecha_inicio:fechai,
	    	   fecha_final:fechaf
	    },
	})
	.done(function(x) {
				if (!(x.includes("Warning")) && !(x.includes("Notice")))
			{
			$("#load_boton_notificaciones").html(x);
			}
		else
			{
			swal({
		  		  title: "Registro",
		  		  text: "Error al obtener el reporte: "+x,
		  		  icon: "warning",
		  		  button: "Aceptar",
		  		});
			}
	})
	.fail(function() {
	    console.log("error");
	});
 	
 
}

function GenerarReporte()
{
	var mes = new Date().getMonth();
	mes++;
	var year = new Date().getFullYear();
	var dia_hoy= new Date().getDate();
	var mes_inicio=0;
	var mes_fin=0;
	var anio_inicio=year;
	var anio_fin=year;
	console.log(dia_hoy+" hoy")
	if(dia_hoy<=21)
		{
		mes_inicio=mes-2;
		mes_fin=mes-1;
		if (mes_inicio<1)
			{
			mes_inicio=12;
			anio_inicio=year-1;
			}
		}
	else
		{
		mes_inicio=mes;
		mes_fin=mes+1;
		if (mes_fin==13)
			{
			mes_fin=1;
			anio_fin=year+1;
			}
		}
	
	var diainicio = 22;
	 var diafinal = 21;
	 
	 var fechai = diainicio+"/"+mes_inicio+"/"+year;
	 
	 var fechaf = diafinal+"/"+mes_fin+"/"+year;
	 
	 console.log(fechai+"<=>"+fechaf);
	 $.ajax({
		    url: 'index.php?controller=Marcaciones&action=SubirReporte',
		    type: 'POST',
		    data: {
		    	   fecha_inicio:fechai,
		    	   fecha_final:fechaf
		    },
		})
		.done(function(x) {
			console.log(x);
					if (!(x.includes("Warning")) && !(x.includes("Notice")) && !(x.includes("Catchable fatal error")))
				{
						swal({
					  		  title: "Registro",
					  		  text: "Reporte de nomina generado",
					  		  icon: "success",
					  		  button: "Aceptar",
					  		});
				}
			else
				{
				swal({
			  		  title: "Registro",
			  		  text: "Error al obtener el reporte: "+x,
			  		  icon: "warning",
			  		  button: "Aceptar",
			  		});
				}
		})
		.fail(function() {
		    console.log("error");
		});
}

