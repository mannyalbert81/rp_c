$(document).ready(function(){

	$("#btExportar").click(function()
					{

				var table = $('#podructtable').DataTable();

				var arreglo_completo = table.rows( {order:'index', search:'applied'} ).data();
				
				var arrayHead=["","Grupos","Codigo","Marca","Nombre","Descripcion","Unidad_de_M","ULT_Precio","",""];
				arreglo_completo.unshift(arrayHead);
				var len = arreglo_completo.length;
				
				for (var i = 1; i < len; i++) {
					
					
					arreglo_completo[i][7]=parseFloat(arreglo_completo[i][7]);
					arreglo_completo[i][8]="";
					arreglo_completo[i][9]="";
						}
				   var dt = new Date();
				   var m=dt.getMonth();
				   m+=1;
				   var y=dt.getFullYear();
				   var d=dt.getDate();
				   var fecha=d.toString()+"/"+m.toString()+"/"+y.toString();
				   var wb =XLSX.utils.book_new();
				   wb.SheetNames.push("Reporte Productos");
				   var ws = XLSX.utils.aoa_to_sheet(arreglo_completo);
				   wb.Sheets["Reporte Productos"] = ws;
				   var wbout = XLSX.write(wb,{bookType:'xlsx', type:'binary'});
				   function s2ab(s) { 
		                var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
		                var view = new Uint8Array(buf);  //create uint8array as viewer
		                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
		                return buf;    
				   }
			       saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'ReporteProductos'+fecha+'.xlsx'); 
				});
		    
		    $("#Guardar").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var id_grupos = $("#id_grupos").val();
		    	var codigo_productos = $("#codigo_productos").val();
		    	var marca_productos = $("#marca_productos").val();
		    	var nombre_productos = $("#nombre_productos").val();
		    	var descripcion_productos = $("#descripcion_productos").val();
		    	var id_unidad_medida = $("#id_unidad_medida").val();
		    	var ult_precio_productos = $("#ult_precio_productos").val();
		    	
		    	
		    	
		    	if (id_grupos == 0)
		    	{
			    	
		    		$("#mensaje_id_grupos").text("Introduzca Un Grupo");
		    		$("#mensaje_id_grupos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_grupos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (codigo_productos == "")
		    	{
			    	
		    		$("#mensaje_codigo_productos").text("Introduzca Un Código");
		    		$("#mensaje_codigo_productos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_codigo_productos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (marca_productos == "")
		    	{
			    	
		    		$("#mensaje_marca_productos").text("Introduzca Una Marca");
		    		$("#mensaje_marca_productos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_marca_productos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (nombre_productos == "")
		    	{
			    	
		    		$("#mensaje_nombre_productos").text("Introduzca Un Nombre");
		    		$("#mensaje_nombre_productos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_productos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (descripcion_productos == "")
		    	{
			    	
		    		$("#mensaje_descripcion_productos").text("Introduzca Una Descripcion");
		    		$("#mensaje_descripcion_productos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_descripcion_productos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (id_unidad_medida == 0)
		    	{
			    	
		    		$("#mensaje_id_unidad_medida").text("Introduzca Una Unidad de Medida");
		    		$("#mensaje_id_unidad_medida").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_id_unidad_medida").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (ult_precio_productos == 0.00)
		    	{
			    	
		    		$("#mensaje_ult_precio_productos").text("Introduzca Un Ultimo Precio");
		    		$("#mensaje_ult_precio_productos").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_ult_precio_productos").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	
				


		    	
			}); 


		        $( "#id_grupos" ).focus(function() {
				  $("#mensaje_id_grupos").fadeOut("slow");
			    });

		        $( "#codigo_productos" ).focus(function() {
					  $("#mensaje_codigo_productos").fadeOut("slow");
				    });
		        $( "#marca_productos" ).focus(function() {
					  $("#mensaje_marca_productos").fadeOut("slow");
				    });
		        $( "#nombre_productos" ).focus(function() {
					  $("#mensaje_nombre_productos").fadeOut("slow");
				    });
		        $( "#descripcion_productos" ).focus(function() {
					  $("#mensaje_descripcion_productos").fadeOut("slow");
				    });
		        $( "#id_unidad_medida" ).focus(function() {
					  $("#mensaje_id_unidad_medida").fadeOut("slow");
				    });
		        $( "#ult_precio_productos" ).focus(function() {
					  $("#mensaje_ult_precio_productos").fadeOut("slow");
				    });
		        		      
		        $(".cantidades1").inputmask();  
		        
});//docreadyend