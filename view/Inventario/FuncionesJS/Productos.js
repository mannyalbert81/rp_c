$(document).ready(function(){
	carga_solicitud();
	carga_producto()

	
});





function carga_productos(pagina){

    var search=$("#buscador_productos").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=Productos&action=consulta_productos',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_productos").html(x);
               $("#load_productos").html("");
               $("#tabla_productos").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}







