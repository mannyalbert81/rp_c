$(document).ready(function(){
	
	$('[data-toggle="tooltip"]').tooltip(); 
	
	buscaComprobante()
});

$("#buscadorComprobante").on("keyup",function(){
	
	buscaComprobante();
});

function buscaComprobante(pagina=1){
	
	var buscador = $("#buscadorComprobante").val();
	
	$.ajax({
		beforeSend:function(obj){
			$("#data_comprobantes").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
		},
		url:'index.php?controller=MovimientosInv&action=buscaFacturas',
		type:'POST',
		data:{page:pagina,search:buscador},
	}).done(function(data){
		$('#data_comprobantes').html(data);
	}).fail(function(xhr,status,error){
		var err = responseText;
		alert(err);
	})
}

$('#data_comprobantes').on('click','[data-op="registrar"]',function(){
	//$(this).data('valor');
	alert($(this).data('valor'))
})

var anio = (new Date).getFullYear();

$("#numero_factura_compra").inputmask('999-999-999999999',{placeholder: ""});

$("#numero_autorizacion_factura").inputmask('9999999999',{placeholder: ""});

$("#mod_precio_producto").inputmask({
	 alias: "decimal",	
	 digits: 2,
	 digitsOptional: true,
	 groupSeparator: ",",
	 autoGroup:true,
	 placeholder: "",
	 allowMinus: false,
	 integerDigits: '5',
	 defaultValue: "00.00",
	 prefix: "$"
	 });


$("#fecha_compra").inputmask({
	 alias: "date",
	 yearrange: { 'minyear': '1990','maxyear': anio },	 
	 placeholder: "dd/mm/yyyy"
	 });



$('#agregar_nuevo').on('show.bs.modal', function (event) {
load_productos(1);
  var modal = $(this)
  modal.find('.modal-title').text('Listado Productos')

});












  
