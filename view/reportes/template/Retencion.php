<!DOCTYPE html>
<html>
 <head>
  	<meta charset="utf-8"/>
  	<title>Comprobante Contable</title>
  <style>
  	
  	tr.filaoculta{
  	
  	display:none;
  	
  	}
  
	div.izq{
	left:0px;  margin-top:-20px; width:49%; /*border: 1px solid blue;*/ float:left;
	}
	div.izq1{
	left:0px;  margin-top:-20px; width:100%; border: 1px solid black; float:left;
	border-radius: 5px;
	}
	div.der{
	right:0px; margin-top:-10px; width:49%; border: 1px solid black; float:left;
	border-radius: 5px;
	}
	#divSerp{
	 width:100%; /*border: 1px solid red;*/ padding:0px 5px;
	}
	.prueba{/* border:1px dashed green;*/}
	td.infotd {
	border:hidden;
	}
	table.info{
	border: 1px solid black;
	}
	footer
	{
    margin-top:50px;
    width:100%;
    height:200px;
    background-color:red;
	}
  </style>
  
  </head>
  <body>
  
	
	<div style="width:100%">
	<div class="izq">
	<div id="divSerp" style="{tiali}">
			<div align="center" style="margin-top:10px; color:#000000; font-family:verdana; font-size:75%; width:100%;">
				<strong> <img  style="width: 100%; height:171px; width:300px;"  src="view/images/Logo-Capremci-v-200.jpg" ></strong>
			</div>
	
	<br>
	<br>
	
	<div class="izq1">
	    
	    
		<div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:20%; width:100%;">
			<table>
	        <tr>
	        <td style="width:300px;font-size: 10px;" align="center"><strong>{RAZONSOCIAL}</strong></td>
	        

	        </tr>
	        </table>
	        
			</div>
		</div>
	
		<div>
			<div style="color:#000000; font-family: verdana; font-size:20%; width:100%;">
			<table>
	        <tr>
	        	<td style="width:50px;font-size: 10px;"><strong>Dirección Matriz:</strong></td>
	        

	        </tr>
	        <tr>
	        	<td style="width:250px;font-size: 10px;">{DIRMATRIZ}</td>
	        </tr>
	        </table>
	        
			</div>
		</div>
	
		<div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:20%; width:100%;">
			<table>
	        <tr>
	        <td style="width:50px;font-size: 10px;"><strong>Dirección Sucursal:</strong></td>
	        
	        </tr>
	        <tr>
	        	<td style="width:250px;font-size: 10px;">{DIRESTABLECIMIENTO}</td>
	        	
	        </tr>
	        </table>
	        
			</div>
		</div>
		<br>
		<div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:20%; width:100%;">
			<table>
	        <tr>
	        <td style="width:200px;font-size: 10px;"><strong>Contribuyente Especial Nro</strong></td>
	        <td style="width:50px;font-size: 10px;">&nbsp; &nbsp;{CONTESPECIAL}</td>

	        </tr>
	        </table>
	        
			</div>
		</div>
		<div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:20%; width:100%;">
			<table>
	        <tr>
	        <td style="width:200px;font-size: 10px;"><strong>OBLIGADO A LLEVAR CONTABILIDAD</strong></td>
	        <td style="width:50px;font-size: 10px;">&nbsp; &nbsp;{OBCONTABILIDAD}</td>

	        </tr>
	        </table>
	        
			</div>
		</div>
	</div>


			
			</div>
		
	</div>
	
	<div class="der">
	    <br> 
	    <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:100%; width:100%;">
				<strong>RUC:</strong>&nbsp; &nbsp;{RUC}
			</div>
		</div>
		<br> 
		<div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:120%; width:100%;">
				<strong> COMPROBANTE DE RETENCIÓN </strong>
			</div>
			
		</div>
		<br> 
		 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:100%; width:100%;">
				<strong>No. </strong>001-001-{SECUENCIAL}
			</div>
		</div>
		 <br>
	 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:110%; width:100%;">
				<strong>NÚMERO DE AUTORIZACIÓN: </strong>
			</div>
			<div style="color:#010a01; font-family: verdana; font-size:80%; width:100%;">
			<div>
				<p style="text-align:justify;">{CLAVEACCESO}</p>
			</div>
			
			</div>
		</div>
	<br> 
		 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:75%; width:100%;">
				FECHA Y HORA DE <br>
				AUTORIZACIÓN:&nbsp; &nbsp; &nbsp;{FECAUTORIZACION}
			</div>
		</div>	
	
	<br> 
		 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:100%; width:100%;">
				<strong>AMBIENTE:</strong>&nbsp; &nbsp;{AMBIENTE}
			</div>
    </div>
    
   <br> 
		 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:100%; width:100%;">
				<strong>EMISIÓN:</strong>&nbsp; &nbsp;{EMISION}
			</div>
    </div>
     	 <div id="divSerp"  style="{admi}">
			<div style="color:#000000; font-family: verdana; font-size:110%; width:100%;">
			<br>
			
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;	CLAVE DE ACCESO
			</div>
			<div style="color:#010a01; font-family: verdana; font-size:80%; width:100%;">
			<img  src="{IMGBARCODE}">
			</div>
		</div>	
	
	
	
    </div>
	
	</div>
	
	<br>
	
	<div style="width:98%; float:left;" >
		<table class="info" style="width:100%;">
		<tr>
		 <td class="infotd" style="font-size: 70%; width:50px; padding: 5px;" >
		 <strong> Razón Social/Nombres y Apellidos: </strong>
		 </td>
		 <td class="infotd" style="font-size: 70%; width:150px; padding: 5px;">
		 {RAZONSOCIALRETENIDO}
		 </td>
		 <td class="infotd" style="font-size: 70%; width:30px; padding: 5px;" align="right">
		 <strong> Identificación: </strong>
		 </td>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;">
		 {IDENTIFICACION}
		 </td>
        </tr>
        <tr>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;" >
		 <strong> Fecha Emisión:&nbsp; &nbsp; </strong>{FECHAEMISION}
		 </td>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;">
		 &nbsp;
		 </td>
	    </tr>
        </table>
	</div>
	<br>
	
	
	<div>
	  <table class="info" style="width:98%;" border=1 >
	  <tr>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Comprobante</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Número</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Fecha Emisión</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Ejercicio Fiscal</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Base Imponible para la Retención</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Impuesto</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Porcentaje Retención</th>
	  <th colspan="2" style="text-align: center; font-size: 11px;">Valor Retenido</th>
	  </tr>
	   <?php foreach($retencion_detalle as $res) {?>
	        		<tr class="filaoculta" >
	                   <td> <?php echo $res->id_carton_impreso; ?>  </td>
		               <td> <?php echo $res->numero_carton_impreso; ?>     </td> 
		               
		                  <td colspan="2" style="text-align: center; font-size: 13px;">{CODSUSTENTO}</td>
					      <td colspan="2" style="text-align: center; font-size: 13px;">{NUMDOCSUST}</td>
					      <td colspan="2" style="text-align: center; font-size: 13px;">{FECHEMDOCSUST}</td>
					      <td colspan="2" style="text-align: center; font-size: 13px;">{PERIODOFISCAL}</td>
					      <td colspan="2" style="text-align: center; font-size: 13px;" align="right"><?php echo $res->impuestos_baseimponible; ?> </td>
					      <td colspan="2" style="text-align: center; font-size: 13px;" align="left"><?php echo $res->impuesto_codigoretencion; ?></td>
					      <td colspan="2" style="text-align: center; font-size: 13px;" align="right"><?php echo $res->impuestos_porcentajeretener; ?></td>
					      <td colspan="2" style="text-align: center; font-size: 13px;" align="right"><?php echo $res->impuestos_valorretenido; ?></td>
		               
		               <td>
			           		<div class="right">
			                  
			                </div>
			            
			             </td>
			             
		    		</tr>
	    <?php } ?>
		        
	  
     
	  </table>
	</div>
	
	
	
	<br>
	<br>
	<br>
	<br>
	<div style="width:98%; float:left;" >
		<table class="info" style="width:50%;">
		<tr>
		 <td class="infotd" style="font-size: 100%; width:50px; padding: 5px;" >
		 <strong> Información Adicional </strong>
		 </td>
		 
        </tr>
        <tr>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;" >
		 <strong> Dirección:&nbsp; &nbsp; </strong>{CAMPADICIONAL}
		 </td>
		 </tr>
		 <tr>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;" >
		 <strong> Teléfono:&nbsp; &nbsp; </strong>{CAMPADICIONALDOS}
		 </td>
		 </tr>
		 <tr>
		 <td class="infotd" style="font-size: 70%; width:20px; padding: 5px;" >
		 <strong>E-mail:&nbsp; &nbsp; </strong>{CAMPADICIONALTRES}
		 </td>
		 </tr>
		 <br>
		 <br>
        </table>
        
	</div>
	
	 
  </body>
  </html>