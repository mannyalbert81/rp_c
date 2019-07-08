var meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
var arrsubida;
var archsubida;
var idbancosubida;
var mes;
var anio;
var noctas;
$(document).ready(function(){
	
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
	MostrarDocumentos(1);
	
})

function VisualizarDocumentos(id_log)
{
	$.ajax({
	    url: 'index.php?controller=EstadosCuenta&action=mostrar_documentos',
	    type: 'POST',
	    data:{
	    	id_log: id_log,
	    	action: 'ajax'
	    },
	})
	.done(function(x) {
		console.log(x)
		var modal = $('#myModalDoc');
		modal.find('#preliminar_archivo').html(x);
		
		
	})
	.fail(function(x) {
	    console.log(x);
	});
console.log(id_log);
$("#myModalDoc").modal();	
}

function VistaPreliminar(arreglo, len, cantc, cantd, banco, periodo)
{
	var lenelements=arreglo[0].length;
	var totalmovimientos=len-2;
	var imagen="";
	arrsubida=arreglo;
	var button="<button type=\"button\" class=\"btn btn-light\" style=\"float: right;margin-bottom: 12px\" id=\"confirmar_archivo\" name=\"confirmar_archivo\" onclick=\"ConfirmarArchivo(arrsubida,0,archsubida)\">CONFIRMAR</button>";
	if(banco=="PICHINCHA") imagen="<img  style=\"width: 100%;  height:50px; width:239px; float:left; margin-bottom: 12px \" align=\"right\" src=\"view/images/banco-pichincha-logo.png\">";
	if(banco=="RUMIÑAHUI") imagen="<img  style=\"width: 100%;  height:75px; width:120px; float:left; margin-bottom: 12px \" align=\"right\" src=\"view/images/bgr-logo.jpg\">";	
	var tablaPreliminar="<h4>"+imagen+"</h4>" + button;
	    tablaPreliminar+="<table align=\"right\" class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>" +
	    				"<tr>" +
	    				"<td align=\"right\" colspan=\"3\" style=\"font-size:20px\"><b>"+periodo+"</b></td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"vertical-align: top; font-size:18px; padding-right: 12px; \" rowspan=\"3\" ><b>Movimientos</b></td>" +
	    				"<td align=\"left\" style=\"font-size:16px\"><b>Crédito:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px\">"+cantc+"</td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"font-size:16px\"><b>Débito:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px\">"+cantd+"</td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"font-size:16px\"><b>Total:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px\">"+totalmovimientos+"</td>" +
	    				"</tr>" +
	    				"</table>";
		tablaPreliminar+="<table id='tabla_cuentas' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
		tablaPreliminar+="<tr>";
		tablaPreliminar+="<th>N</th>";
		for(var i=0; i<lenelements; i++)
			{
			tablaPreliminar+="<th>"+arreglo[0][i]+"</th>";
			}
		tablaPreliminar+="</tr>";
		for(var i=1; i<len-1; i++)
		{
		tablaPreliminar+="<tr>";
		tablaPreliminar+="<td>"+i+"</td>";
			for(var j=0; j<lenelements; j++)
			{
			tablaPreliminar+="<td>"+arreglo[i][j]+"</td>";
			}
			
		tablaPreliminar+="</tr>";
		}
		tablaPreliminar+="</table>";
		var modal = $('#myModalVista');
		modal.find('#preliminar_archivo').html(tablaPreliminar);
	$("#myModalVista").modal()
}

function VistaPreliminarIntl(arreglo, len, cantMC, cantDP, cantMD,cantCO, cant3O, periodo)
{
	var lenelements=arreglo[3].length;
	var totalmovimientos=len-5;
	arrsubida=arreglo;
	var imagen="<img  style=\"width: 100%;  height:50px; width:239px; float:left;margin-bottom: 12px \" align=\"right\" src=\"view/images/banco-internacional-logo.png\">";
	var button="<button type=\"button\" class=\"btn btn-light\" style=\"float: right;margin-bottom: 12px\" id=\"confirmar_archivo\" name=\"confirmar_archivo\" onclick=\"ConfirmarArchivo(arrsubida,1,archsubida)\">CONFIRMAR</button>";
	var tablaPreliminar="<h4 style=\"margin-bottom: 12px \">"+imagen+"</h4>" + button;
	    tablaPreliminar+="<table align=\"right\" class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>" +
	    				"<tr>" +
	    				"<td align=\"right\" colspan=\"7\" style=\"font-size:20px\"><b>"+periodo+"</b></td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"vertical-align: top; font-size:18px; padding-right: 12px; \" rowspan=\"3\" ><b>Movimientos</b></td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Movimientos Crédito:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">"+cantMC+"</td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Mendiante Débito:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">"+cantMD+"</td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Depósitos:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">"+cantDP+"</td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Costos de Operación:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">"+cantCO+"</td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b>Interes:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\">"+cant3O+"</td>" +
	    				"</tr>" +
	    				"<tr>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>" +
	    				"<td align=\"left\" style=\"font-size:16px; padding-right: 12px;\"><b></b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px; padding-right: 12px;\"></td>" +
	    				"<td align=\"left\" style=\"font-size:16px\"><b>Total:</b></td>" +
	    				"<td align=\"right\" style=\"font-size:16px\">"+totalmovimientos+"</td>" +
	    				"</tr>" +
	    				"</table>";
		tablaPreliminar+="<table class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
		tablaPreliminar+="<tr>";
		tablaPreliminar+="<th>N</th>";
		for(var i=0; i<lenelements; i++)
			{
			if(arreglo[3][i]!="") tablaPreliminar+="<th>"+arreglo[3][i]+"</th>";
			}
		tablaPreliminar+="</tr>";
		for(var i=4; i<len-1; i++)
		{
		tablaPreliminar+="<tr>";
		var ind=i-3
		tablaPreliminar+="<td>"+ind+"</td>";
			for(var j=0; j<lenelements; j++)
			{
			 if(arreglo[i][j]!="" || j==4) tablaPreliminar+="<td>"+arreglo[i][j]+"</td>";
			}
			
		tablaPreliminar+="</tr>";
		}
		var modal = $('#myModalVista');
		modal.find('#preliminar_archivo').html(tablaPreliminar);
	$("#myModalVista").modal()
}

function BPichincha(arreglo)
{
	var oficina="SERVICIOS CENTRALE(S)";
	var encontrado=false;
	var cantc=0;
	var cantd=0;
	arreglo=arreglo.replace(/,"/g,'!');
	arreglo = arreglo.replace(/"/g,'');
	arreglo= arreglo.split('\n');
	var len=arreglo.length;
	noctas=len-2;
	for(var i=0; i<len; i++)
		{
		arreglo[i]=arreglo[i].split('!');
		if(arreglo[i][5]==oficina) encontrado=true;
		if (i>0 && i<len-1 && arreglo[i][3]=="C") cantc++;
		if (i>0 && i<len-1 && arreglo[i][3]=="D") cantd++;
		}
	if (!encontrado)
	{
	swal("El archivo no tiene el formato correcto", {
	      icon: "error",
	      buttons: false,
	      timer: 2000
	    });
	}
else
	{
	swal("Archivo aceptado", {
	      icon: "success",
	      buttons: false,
	      timer: 1000
	    });
	var fecha = arreglo[1][0].split('/');
	mes=fecha[1];
	anio=fecha[2];
	var periodo = meses[fecha[1]-1]+" del "+fecha[2];
	VistaPreliminar(arreglo, len, cantc, cantd, "PICHINCHA",periodo);
	}
}

function BRuminahui(arreglo)
{
	var oficina="MATRIZ BANCO RUMINAHUI";
	var encontrado=false;
	var cantc=0;
	var cantd=0;
	arreglo=arreglo.replace(/,"/g,'!');
	arreglo = arreglo.replace(/"/g,'');
	arreglo= arreglo.split('\n');
	var len=arreglo.length;
	noctas=len-2;
	for(var i=0; i<len; i++)
		{
		arreglo[i]=arreglo[i].split('!');
		if(arreglo[i][5]==oficina) encontrado=true;
		if (i>0 && i<len-1 && arreglo[i][3]=="C") cantc++;
		if (i>0 && i<len-1 && arreglo[i][3]=="D") cantd++;
		}
	if (!encontrado)
		{
		swal("El archivo no tiene el formato correcto", {
		      icon: "error",
		      buttons: false,
		      timer: 2000
		    });
		}
	else
		{
		swal("Archivo aceptado", {
  	      icon: "success",
  	      buttons: false,
  	      timer: 1000
  	    });
		var fecha = arreglo[1][0].split('/');
		mes=fecha[1];
		anio=fecha[2];
		var periodo = meses[fecha[1]-1]+" del "+fecha[2];
		VistaPreliminar(arreglo, len, cantc, cantd, "RUMIÑAHUI",periodo);
		}
}


function BInternacional(arreglo)
{
	arreglo= arreglo.split('\n');
	var len=arreglo.length;
	noctas=len-5;
	var encontrado=false;
	
	var contador=0;
	var cantMC=0;
	var cantDP=0;
	var cantMD=0;
	var cantCO=0;
	var cant3O=0;
	for(var i=0; i<len; i++)
		{
		arreglo[i]=arreglo[i].replace(';','');
		arreglo[i]=arreglo[i].split(';');
		if (arreglo[i][1]=="MC") cantMC++;
		if (arreglo[i][1]=="DP") cantDP++;
		if (arreglo[i][1]=="MD") cantMD++;
		if (arreglo[i][1]=="CO") cantCO++;
		if (arreglo[i][1]=="3O") cant3O++;
		}
	var lenelements=arreglo[3].length;
	for(var i=0; i<lenelements; i++)
		{
		if(arreglo[3][i]!="") contador++;
		}
	
	
	if (contador==8) encontrado=true;1
	if(arreglo[0][0]!="RESUMEN ESTADO DE CUENTA" || !encontrado)
		{
		swal("El archivo no tiene el formato correcto", {
		      icon: "error",
		      buttons: false,
		      timer: 2000
		    });
		}
	else
		{
		swal("Archivo aceptado", {
	  	      icon: "success",
	  	      buttons: false,
	  	      timer: 1000
	  	    });
		var fecha = arreglo[4][0].split('-');
		mes=fecha[1];
		anio=fecha[0];
		var periodo = meses[fecha[1]-1]+" del "+fecha[0];
		VistaPreliminarIntl(arreglo, len, cantMC, cantDP, cantMD,cantCO, cant3O, periodo);
		}

}

function SubirArchivo()
{
var idbanco =$('#id_banco').val();
idbancosubida=idbanco;
var arch=$('#nombre_archivo').val();
if(idbanco=="")
	{
	$("#mensaje_nombre_bancos").text("Seleccione banco");
	$("#mensaje_nombre_bancos").fadeIn("slow");
	$("#mensaje_nombre_bancos").fadeOut("slow");
	}
if (arch=="")
{
	swal("Seleccione un archivo", {
	      icon: "warning",
	      buttons: false,
	      timer: 1500
	    });
}
if(idbanco!="" && arch!="")
	{
var archivo=document.getElementById('archivo_registro').files[0];
archsubida=archivo;
console.log(archivo);
var fileName = archivo.name;
var extension = fileName.split(".");
var l=extension.length;
if (extension[l-1]=="csv" && (idbanco==1 || idbanco==2))
	{
	var arr;
	swal({
		  title: "Archivo",
		  text: "Leyendo el archivo: "+fileName,
		  icon: "view/images/capremci_load.gif",
		  buttons: false,
		  closeModal: false,
		  allowOutsideClick: false
		});
		var reader = new FileReader();
		reader.readAsText(archivo, "UTF-8");
		reader.onload = function (evt) {
		arr = evt.target.result;
			switch(idbanco)
	        {
	        case '1':
	        	BPichincha(arr);
	        	break;
	        case '2':
	        	BRuminahui(arr);
	        	break;
	        }
	    }
	    reader.onerror = function (evt) {
	    arr = "error reading file";
	    swal("No se ha podido leer el archivo", {
		      icon: "error",
		      buttons: false,
		      timer: 1500
		    });
	    }
	}
else if(extension[l-1]=="xls" && idbanco==8)
	{
	swal({
		  title: "Archivo",
		  text: "Leyendo el archivo: "+fileName,
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
        var XL_row_object = XLSX.utils.sheet_to_csv(workbook.Sheets[sheetName],{FS:";"});
        var json_object = JSON.stringify(XL_row_object);
        arr=JSON.parse(json_object);
    	BInternacional(arr);
      })
    };

    reader.onerror = function(ex) {
      console.log(ex);
    };

    reader.readAsBinaryString(archivo);
		    	
	}
else
	{
	swal("Elija un archivo con la extension correcta \n *.csv: Pichincha, Rumiñahui\n*.xls: Internacional", {
	      icon: "error",
  		  button: "Aceptar",
	    });
	$("#nombre_archivo").val("");
	}
	}
}

function DatosBase(arr, tipo, idlog)
{
	idbanco=idbancosubida;
	console.log(arr);
	arr=JSON.stringify(arr);
	$.ajax({
	    url: 'index.php?controller=EstadosCuenta&action=SubirDatosBase',
	    type: 'POST',
	    data:{
	    	tipo: tipo,
	    	id_bancos: idbanco,
	    	total_cuentas: noctas,
	    	id_log: idlog,
	    	estado_cuentas: arr	    	
	    },
	})
	.done(function(x) {
		console.log(x);
		
		
		
	})
	.fail(function(x) {
	    console.log(x);
	});
}

function ConfirmarArchivo(arr,tipo, archivo)
{
var data = new FormData();
var modal = $('#myModalVista');
data.append('file', archivo);
data.append('id_banco', idbancosubida);
data.append('mes', mes);
data.append('anio', anio);
//var datos = new FormData(this)
$.ajax({
    url: 'index.php?controller=EstadosCuenta&action=SubirEstadoCuenta',
    type: 'POST',
    contentType: false,
    //cache: false,
    processData:false,
    data: data,
})
.done(function(x) {
	var idlog=x;
	$('#id_banco').val("");
	$('#nombre_archivo').val("");
	$("#archivo_registro").val("");
	modal.modal('hide');
	DatosBase(arr, tipo, idlog);
	
	
	
})
.fail(function(x) {
    console.log(x);
});

}

function MostrarDocumentos(page)
{
 var mes = $('#mes_archivo').val();
 var anio = $('#anio_archivo').val();
 var banco = $('#banco_archivo').val();
 console.log(banco+"-"+mes+"-"+anio);
 $.ajax({
	    url: 'index.php?controller=EstadosCuenta&action=consulta_documentos&page='+page,
	    type: 'POST',
	    data:{
	    	mes_archivo: mes,
	    	anio_archivo: anio,
	    	id_banco: banco,
	    	action: 'ajax'
	    },
	})
	.done(function(x) {
		$('#listado_documentos').html(x);
		
		
	})
	.fail(function(x) {
	    console.log(x);
	});
 
 
}