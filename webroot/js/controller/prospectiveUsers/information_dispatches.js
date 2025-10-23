
var elementButton;

// $('body').on("click", "#btn_informacion_despacho", function(event) {
// 	$('label[for="cityForm"]').hide();
// 	var flujo_id 		= $(this).data('uid');
// 	$.post(copy_js.base_url+'FlowStages/form_pagado_despachado',{flujo_id:flujo_id}, function(result){
// 		$('#modal_form_body').html(result);
// 		$('#modal_form_label').text('Datos para el despacho');
// 		$('#modal_form').modal('show');
// 		$('#FlowStageCopiasEmail').tagsinput();
// 	});
// });

$("body").on( "click", ".btn_guardar_form", function() {
	var flujo_id 		= $('#FlowStageFlujoId').val();
	var cityForm 		= $('#cityForm').val();
	var address 		= $('#FlowStageAddress').val();
	var contact 		= $('#FlowStageContact').val();
	var information 	= $('#FlowStageInformation').val();
	var flete 			= $('#FlowStageFlete').val();
	var telefono 		= $('#FlowStageTelephone').val();
	var copias 			= $('#FlowStageCopiasEmail').val();
	if (cityForm != '' && address != '' && contact != '' && telefono != '') {
		$('.btn_guardar_form').hide();
		$.post(copy_js.base_url+'FlowStages/saveInformationDelivery',{information:information,flete:flete,cityForm:cityForm,address:address,contact:contact,flujo_id:flujo_id,telefono:telefono,copias:copias}, function(result){
			$('#modal_form').modal('hide');
			location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
		});
	} else {
		message_alert("Los campos contacto, telefono, ciudad y dirección son requeridos","error");
	}
});

function initialize() {
	var input = document.getElementById('cityForm');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);

$('body').on("click", "#btn_informacion_despacho", function(event) {
	elementButton = $(this);
	$('label[for="cityForm"]').hide();
	var flujo_id 		= $(this).data('uid');
	var cliente 		= $(this).data('client');
	var type 			= $(this).data('type');

	$('#despachoDeProductos').modal('show');

	$.post(copy_js.base_url+'FlowStages/get_data_send_products', {flujo_id, cliente,type}, function(data, textStatus, xhr) {
		$("#cuerpoDespacho").html(data);
		$('#despachoDeProductos').modal('show');
		$('#FlowStageCopiasEmail').tagsinput();
		var requiredCheckboxes = $("body").find(".productsEnvioCheck");
		requiredCheckboxes.change(function(){
		    if(requiredCheckboxes.is(':checked')) {
		        requiredCheckboxes.removeAttr('required');
		    } else {
		        requiredCheckboxes.attr('required', 'required');
		    }
		});
	});

	// $.post(copy_js.base_url+'FlowStages/form_pagado_despachado',{flujo_id:flujo_id}, function(result){
	// 	$('#modal_form_body').html(result);
	// 	$('#modal_form_label').text('Datos para el despacho');
	// 	$('#modal_form').modal('show');
	// 	//Retomar
	// 	$('#FlowStageCopiasEmail').tagsinput();
	// });
});

$("body").on('click', '.info_bill', function(event) {
	event.preventDefault();
	id = $(this).data("uid");
	view =  typeof $(this).data("view") != "undefined" ? 1 : null;
	$.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information', {id, view}, function(data, textStatus, xhr) {
		$("#cuerpoBill").html(data);
		$("#modalBillInformation").modal("show")
	});
});


$("body").on('submit', '#form_bill', function(event) {
	event.preventDefault();
	var formData            = new FormData($('#form_bill')[0]);
	$.ajax({
        type: 'POST',
        url: copy_js.base_url+'ProspectiveUsers/saveBillInformation',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
        	console.log(result);
        	if (result > 10) {
        		// location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
        	} else {
				$("#btnFormSubmit").show();
        		validate_documento_pdf_message(result);
        	}
        }
    });
});