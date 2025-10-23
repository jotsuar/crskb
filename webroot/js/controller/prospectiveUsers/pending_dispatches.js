$("body").on( "click", ".state_despachado", function() {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	var stateflow 		= $(this).data('stateflow');
	$.post(copy_js.base_url+'FlowStages/change_despachado',{flujo_id:flujo_id,state:state,stateflow:stateflow}, function(result){
		$('#modal_despachado_body').html(result);
		$('#modal_despachado_label').text('Procesamiento de flujo a despachado');
		$('#modal_despachado').modal('show');
		if (state != 2) {
			message_alert("Por favor primero gestiona la orden, por si existen productos con importación","error");
		}
	});
});
$("body").on( "click", ".btn_guardar_despachado", function() {
	var number 					= $('#FlowStageNumber').val();
	var radio_option 			= $("#form_despachado input[type='radio']:checked").val();
	if (number != '' && radio_option != undefined) {
		$('.btn_guardar_despachado').hide();
		var formData            = new FormData($('#form_despachado')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'FlowStages/saveStateDespachado',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	if (result == 1) {
                    $.post(copy_js.base_url+'prospective_users/updateStateFinishProspective',{flujo_id: $("#FlowStageFlujoId").val() }, function(result){
                        location.reload();
                    });
	        	} else {
	        		$('.btn_guardar_despachado').show();
	        		validate_image_message(result);
	        	}
	        }
	    });
	} else {
		message_alert("El número de guía es obligatorio y por favor selecciona si se le envia el correo al cliente","error");
	}
});

$(".find_payments_flujo").click(function() {
    var flujo_id            = $(this).data('uid');
    $.post(copy_js.base_url+'Payments/payment_history',{flujo_id:flujo_id}, function(result){
        $('#modal_information_body').html(result);
        $('#modal_information_label').text('Datos de los pagos realizados');
        $('#modal_information').modal('show');
    });
});


$("body").on('click', '.info_bill', function(event) {
	event.preventDefault();
	id = $(this).data("uid");
	bill = $(this).data("bill");
	view =  typeof $(this).data("view") != "undefined" ? 1 : null;
	$.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information', {id, view, bill}, function(data, textStatus, xhr) {
		$("#cuerpoBill").html(data);
		$("#modalBillInformation").modal("show")
	});
});

$("body").on('submit', '#form_bill', function(event) {
    event.preventDefault();
    return false;
    $(".body-loading").show();
    var formData            = new FormData($('#form_bill')[0]);

    marcadoMarcar = 0;
    totalProductos = 0;

    $("body").find('.bodega_inventario').each(function(index, el) {
        if($(this).prop('checked')){
            marcadoMarcar++;
        }
    });

    $("body").find('.cantidadProducto').each(function(index, el) {
        totalProductos++;
    });

    if(marcadoMarcar == 0 && $(".contentchecksend").length){
        message_alert("No es posible procesar la información, ya que existen productos sin inventario total para descontar.","error");
        $(".body-loading").hide();
        return false;
    }

    $.ajax({
        type: 'POST',
        url: copy_js.base_url+'ProspectiveUsers/saveBillInformation',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            if (result > 10) {
                location.reload();
                // window.open(copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result);
            } else {
                $(".body-loading").hide();
                $("#btnFormSubmit").show();
                validate_documento_pdf_message(result);
            }
        }
    });
});


$("body").on('click', '.mostradDatos', function(event) {
    event.preventDefault();
    var idMostrar = "#"+$(this).data("id");
    $(idMostrar).toggle();
});


$("body").on('click', '.validarFacturaWo', function(event) {
    event.preventDefault();
    var number = $("#ProspectiveUserBillCode").val();
    var prefijo = $("#ProspectiveUserBillPrefijo").val();

    if (number == "" || prefijo == "") {
         message_alert("El código y el prefijo son requeridos","error");
    }else{
        $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/get_document",
            type: 'post',
            data: {number,prefijo},
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            $(".datosWo").html(response)
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
        
    }

});


$("body").on('click', '.validateFinal', function(event) {
    event.preventDefault();
    $("#ProspectiveUserBillCode").attr("readonly","readonly");
    $("#ProspectiveUserBillPrefijo").attr("readonly","readonly");
    var number = $("#ProspectiveUserBillCode").val();
    var prefijo = $("#ProspectiveUserBillPrefijo").val();
    $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/save_document",
            type: 'post',
            data: $("#form_bill").serialize(),
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            // $(".datosWo").html(response)
            location.reload();
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
});

/******** Descuento factura *****/

$("body").on('click', '.bodega_inventario', function(event) {
	id = $(this).data("id");
	if($(this).hasClass('bodega_med')){
		var divId = ".deliveryDataBodega_"+id+" .bodega_bog";
		$("body").find(divId).prop('checked', false);
	}else{
		var divId = ".deliveryDataBodega_"+id+" .bodega_med";
		$("body").find(divId).prop('checked', false);
	}

});


$("body").on('click', '.selectBog', function(event) {
	$("body").find(".bodega_bog").each(function(index, el) {
		if (!$(this).attr("disabled")) {
			$(this).trigger('click')
		}
	});
});

$("body").on('click', '.selectMed', function(event) {
	$("body").find(".bodega_med").each(function(index, el) {
		if (!$(this).attr("disabled")) {
			$(this).trigger('click')
		}
	});
});

$("body").on('click', '.listFact', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information_list', {id}, function(data, textStatus, xhr) {
		$("#cuerpoBillList").html(data);
		$("#modalBillList").modal("show")
	});
});

/*****Cambio trm ***/

$("body").on('change', '#ProspectiveUserChangeTrm', function(event) {
    var valorTrm = $(this).val();
    if(valorTrm == "0"){
        $(".cambioTrm").hide();
    }else{
        $(".cambioTrm").show();        
    }
});

$("body").on('click', '#btnProcesarCambioDolarTrm', function(event) {
    event.preventDefault();
    var trm         = $("#trmDiaCambio").val();
    var flujo       = $(this).data("flujo");
    var quotation   = $(this).data("quotation");
    if(trm == ""){
        message_alert("Por favor selecciona el TRM del cambio.","error");
    }else{
        $(".body-loading").show();
        $.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
            trm,flujo,quotation, calculate: 2
        }, function(data, textStatus, xhr) {
            $(".body-loading").hide();
            swal({
                title: "¿Estas seguro procesar el cambio?",
                text: "¿El costo final de la cotización será: $"+ data +" COP, deseas continuar ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonText:"No, corregir TRM",
                confirmButtonText: "Si, realizar cambio",
                closeOnConfirm: false
            },
            function(){
                $(".body-loading").show();
                $.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
                    trm,flujo,quotation, calculate: 0, new_calculate: 1
                }, function(data, textStatus, xhr) {
                    $(".body-loading").hide();
                    
                });
            });
        });
    }
});


$("body").on('click', '.factLater', function(event) {
    var valor = $(this).val();
    var checkValue = "input.checkedData_"+valor;
    console.log(valor)
    console.log(checkValue)
    if($(this).is(":checked")){
        $("body").find(checkValue).prop('checked', false)
        $("body").find(checkValue).attr('disabled',"disabled");
        console.log("chekc")
    }else{
        console.log("noCheck")
        $("body").find(checkValue).prop('checked', true);
        $("body").find(checkValue).removeAttr('disabled');
    }
});