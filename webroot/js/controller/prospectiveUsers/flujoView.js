$("body").on('click', '.flujoModal', function(event) {
	var flujo_id 		= $(this).data('uid');
	var type_client 	= $(this).data('type');
	var d               = preload();
	event.preventDefault();
	$("#modalFlujo").modal("show");
	$.post(copy_js.base_url+'ProspectiveUsers'+'/get_etapa',{flujo_id:flujo_id,modal:1}, function(resultD){  
		$("#cuerpoModalInicial").hide().html(resultD).fadeIn('slow');
		if (type_client > 0) {
			$.post(copy_js.base_url+'ProspectiveUsers/get_data_juridica',{flujo_id:flujo_id}, function(result){
				$('#resultadoDatos_').html(result);
			});
		} else {
			$.post(copy_js.base_url+'ProspectiveUsers/get_data_natural',{flujo_id:flujo_id}, function(result){
				$('#resultadoDatos_').html(result);
			});
		}
	});
});

$("body").on('click', '.getQuotationId', function(event) {
	event.preventDefault();
	quotation = $(this).data("quotation");
	$.post(copy_js.base_url+'Quotations/view/'+quotation, {modal:1}, function(data, textStatus, xhr) {
		$("#cuerpoModalFlujoCotizacion").html(data);
		$("#modalFlujoCotizacion").modal("show");
	});
});

$("body").on('click', '.getOrderCompra', function(event) {
	event.preventDefault();
	flujo = $(this).data("flujo");
	$.post(copy_js.base_url+'FlowStages/get_order_purchase/', {flujo}, function(data, textStatus, xhr) {
		$("#cuerpoModalFlujoOrdenCompra").html(data);
		$("#modalFlujoOrdenCompra").modal("show");
	});
});
$("body").on('click', '.getPagos', function(event) {
	event.preventDefault();
	flujo = $(this).data("flujo");
	$.post(copy_js.base_url+'FlowStages/get_payments_flow/', {flujo}, function(data, textStatus, xhr) {
		$("#cuerpoModalFlujoPagos").html(data);
		$("#modalFlujoPagos").modal("show");
	});
});



//ampliar imagen del pago
$("body").on( "click", ".Comprobanteacep", function() {
    var comprobante = $(this).children("img").attr("datacomprobante");
    console.log(comprobante);
    $(".img-product,#img-product").attr('src',comprobante);
    $(".fondo").fadeIn();
    $(".popup2,.popup").fadeIn();
    $("#modalFlujoPagos").modal("hide");
});

$("body").on('click', '.cierra', function(event) {
	event.preventDefault();
	$("#modalFlujoPagos").modal("show");
});


$("body").on('change', '#viewMargen', function(event) {
	if ($(this).is(':checked')) {
		$(".valoresTRM").show();
	}else{
		$(".valoresTRM").hide();		
	}
});


$("body").on( "click", ".find_payments_flujo", function() {
	var flujo_id 			= $(this).data('uid');
	$.post(copy_js.base_url+'Payments/payment_history',{flujo_id:flujo_id}, function(result){
		$('#modal_information_body').html(result);
		$('#modal_information_label').text('Datos de los pagos realizados');
		$('#modal_information').modal('show');
	});
});

$("body").on( "click", ".btn_notas_flujo", function() {
	var flujo_id 		= $(this).data('uid');
	$.post(copy_js.base_url+'ProspectiveUsers/list_notas',{flujo_id:flujo_id}, function(result){
		$('#modal_nota_flujo_body').html(result);
		$('#modal_nota_flujo').modal('show');
	});
});