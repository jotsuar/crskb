$(document).ready(function() {
	var paremetroBuscar 		= get_parameter_name('q');
	if (paremetroBuscar != '') {
		if (validarNumero(paremetroBuscar)) {
			var flujo_id 			= paremetroBuscar;
			var d              		= preload();
			$('.resultadoNovedades').empty();
		    $('.resultadoDatos').empty();
		    $('.resultadoDatos').append(d);
    		$(".allnovedad_"+flujo_id).removeClass("dnone");
			$.post(copy_js.base_url+'ProspectiveUsers'+'/get_etapa',{flujo_id:flujo_id}, function(result){
				$('.resultadoNovedades_'+flujo_id).hide().html(result).fadeIn('slow');
				$('.statushopicon').each(function() {
				    if ($(this).text() == "") {$(this).remove()}
				});
				$('.borradorspan').each(function() {
				    if ($(this).text() == "") {$(".borrador").remove()}
				});
				$('.statushop').each(function() {
				    if ($(this).text() == "") {$(this).remove()}
				});
				$('[data-toggle="tooltip"]').tooltip(); 

				if ($(".statushop").text() == "Pago no aceptado") {$(".statushop").addClass('redback')}
			});
			$.post(copy_js.base_url+'ProspectiveUsers'+'/validateTypeClient',{flujo_id:flujo_id}, function(result){
				if (result > 0) {
					$.post(copy_js.base_url+'ProspectiveUsers/get_data_juridica',{flujo_id:flujo_id}, function(result){
						$('.resultadoDatos_'+flujo_id).html(result);
					});
				} else {
					$.post(copy_js.base_url+'ProspectiveUsers/get_data_natural',{flujo_id:flujo_id}, function(result){
						$('.resultadoDatos_'+flujo_id).html(result);
					});
				}
			});
		}
	}

});

$("body").on('click', '.mostradDatos', function(event) {
    event.preventDefault();
    var idMostrar = "#"+$(this).data("id");
    $(idMostrar).toggle();
});

$("body").on('click', '.terminaFlujo', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	swal({
        title: "¿Deseas terminar el flujo?",
        text: "¿ El flujo se terminará estas seguro ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, continuar flujo",
        confirmButtonText: "Si, terminar",
        closeOnConfirm: false
    },
    function(value){
    	$(".body-loading").show();
		$.post(copy_js.base_url+'ProspectiveUsers/terminar', {
			id
		}, function(data, textStatus, xhr) {
			location.reload();
			$(".body-loading").hide();
		});
	});
});

$("body").on('click', '.bntEliminaFactura', function(event) {
	event.preventDefault();
	var type = $(this).data("type");
	var id   = $(this).data("id");

	swal({
        title: "¿Deseas eliminar la factura?",
        text: "¿Deseas eliminar la factura totalmente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, solo la factura",
        confirmButtonText: "Si, crear cotización",
        closeOnConfirm: false
    },
    function(value){
    	if(value){
    	
    		$(".body-loading").show();
			$.post(copy_js.base_url+'prospective_users/delete_envoice', {
				id, type
			}, function(data, textStatus, xhr) {
				$(".body-loading").hide();
				location.reload();
			});
    	}
    });

});	

$("body").on('click', '.editEnvoice', function(event) {
	event.preventDefault();
	var type = $(this).data("type");
	var id   = $(this).data("id");
	$(".body-loading").show();
	$.get(copy_js.base_url+'prospective_users/edit_envoice/'+id+"/"+type, function(data) {
		$(".body-loading").hide();
		$("#bodyEditEnvoice").html(data);
		$("#modalEditFactura").modal("show");
	});
});	

$("body").on('click', '.rechazarFlujo', function(event) {
	event.preventDefault();
	var id   = $(this).data("id");
	$(".body-loading").show();
	$.get(copy_js.base_url+'prospective_users/reject_flow/'+id, function(data) {
		$(".body-loading").hide();
		$("#rejectBody").html(data);
		$("#ProspectiveUserRejectImage").dropify(optionsDp); 
		$("#rejectFlow").modal("show");
		$("#formReject").parsley();
	});
});

$("body").on('click', '.btnAutomaticFacture', function(event) {
	event.preventDefault();
	var flujo = $(this).data("id");
	
	var quotation = 0;

	
			swal({
		        title: "¿Deseas crear una cotización?",
		        text: "¿ Deseas cargar la factura y crear una cotización final en base a esta ?",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonClass: "btn-danger",
		        cancelButtonText:"No, solo la factura",
		        confirmButtonText: "Si, crear cotización",
		        closeOnConfirm: false
		    },
		    function(value){
		    	if(value){
		    		quotation = 1;
		    	}
		    	$(".body-loading").hide();
	    		$.post(copy_js.base_url+'FlowStages/automatic', {
					flujo, quotation
				}, function(data, textStatus, xhr) {
					$(".body-loading").hide();
					// location.reload();
				});
		    });

	
});

$("body").on('click', '#btnProcesarCambioDolar', function(event) {
	event.preventDefault();
	var trm 		= $("#trmDiaCustom").val() != '' ? $("#trmDiaCustom").val() : $("#trmDia").val();
	var flujo 		= $(this).data("flujo");
	var quotation 	= $(this).data("quotation");
	if(trm == ""){
		message_alert("Por favor selecciona el TRM del cambio.","error");
	}else{
		$(".body-loading").show();
		$.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
			trm,flujo,quotation, calculate: 1
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
					trm,flujo,quotation, calculate: 0
				}, function(data, textStatus, xhr) {
					$(".body-loading").hide();
					location.reload();
				});
		    });
		});
	}
});

$("body").on('click', '.info_bill', function(event) {
    event.preventDefault();
    id = $(this).data("uid");    
    $.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information', {id}, function(data, textStatus, xhr) {
        $("#cuerpoBill").html(data);
        $("#modal_administrar_despachado").modal("hide");
        $("#modalBillInformation").modal("show");
    });
});

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
    var flujo_id  = $("#form_bill #ProspectiveUserId").val();
    $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/save_document",
            type: 'post',
            data: $("#form_bill").serialize(),
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
        	location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujo_id;
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
});


$(".state_import").click(function(event) {
	var id = $(this).data("uid");
	var url = copy_js.base_url+"Products/products_import/"+id;
	window.open(url, "_blank");
});

$("body").on( "click", ".control_flujo h3", function() {
	var flujo_id 		= $(this).data('uid');
	var type_client 	= $(this).data('type');
	var d               = preload();
    $('.resultadoNovedades').empty();
    $('.resultadoDatos').empty();
    $('.resultadoDatos').append(d);
    $(".allnovedad").addClass("dnone");
    $(".allnovedad_"+flujo_id).removeClass("dnone");
	$.post(copy_js.base_url+'ProspectiveUsers'+'/get_etapa',{flujo_id:flujo_id}, function(resultD){  
		$('.resultadoNovedades_'+flujo_id).hide().html(resultD).fadeIn('slow');
		$('.statushopicon').each(function() {
		    if ($(this).text() == "") {$(this).remove()}
		});
		$('.borradorspan').each(function() {
		    if ($(this).text() == "") {$(".borrador").remove()}
		});
		$('.statushop').each(function() {
		    if ($(this).text() == "") {$(this).remove()}
		});
		$('[data-toggle="tooltip"]').tooltip(); 

		if ($(".statushop").text() == "Pago no aceptado") {$(".statushop").addClass('redback')}
	});
	if (type_client > 0) {
		$.post(copy_js.base_url+'ProspectiveUsers/get_data_juridica',{flujo_id:flujo_id}, function(result){
			$('.resultadoDatos_'+flujo_id).html(result);
		});
	} else {
		$.post(copy_js.base_url+'ProspectiveUsers/get_data_natural',{flujo_id:flujo_id}, function(result){
			$('.resultadoDatos_'+flujo_id).html(result);
		});
	}
});

function eventModalFlujo(){
	if ($("#modalFlujo").length) {
		if ($("#modalFlujo").hasClass('show')) {
			$("#modalFlujo").modal("hide");
		}
	}
}

$("body").on('click', '.btn_reasignar_cliente', function(event) {
	event.preventDefault();
	eventModalFlujo();
	var flujo = $(this).data("flujo");
	$.post(copy_js.base_url+"prospective_users/new_customer", {flujo}, function(data, textStatus, xhr) {
		$("#cuerpoReasigna").html(data);
		$("#modalNewCustomerEspecialAsingacion").modal("show");
		setCustomerSelect2(".flujoTiendaClienteEspecial","#modalNewCustomerEspecialAsingacion");
	    
	    setDataClient()
	    $("body").find('.flujoTiendaClienteEspecial').trigger('change');
	    if ($("#hideActual").val() == "LEGAL") {
	    	setTimeout(function() {
	    		$("#modalNewCustomerEspecialAsingacion #contac_id").val($("#hideActual").data("contacto"));
	    	}, 1000);
	    }
	});
});

$("body").on('click', '.btn_change_juridico_cliente', function(event) {
	event.preventDefault();
	eventModalFlujo();
	var cliente = $(this).data("uid");
	var flujo   = $(this).data("flujo");
	$.post(copy_js.base_url+"ProspectiveUsers/change_customer", {flujo,cliente}, function(data, textStatus, xhr) {
		$("#cuerpoCambia").html(data);
		$("#modalChangeNatural").modal("show");
		$("body").find("#changeFormClientData").parsley();
		$("body").on("change","#cuerpoCambia #ClientsNaturalNacional",function(event) {
			if ($(this).val() == "1") {
				$("#cuerpoCambia #ClientsNaturalNit").attr("required","required");
			}else{
				$("#cuerpoCambia #ClientsNaturalNit").removeAttr("required");				
			}
		});
	});
});

$("body").on('submit', '#changeFormClientData', function(event) {
	event.preventDefault();
	var flujoID = $("#ClientsNaturalFlujoId").val();
	$.post(copy_js.base_url+copy_js.controller+"/change_post_customer", $(this).serialize(), function(data, textStatus, xhr) {
			location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujoID;
		/*optional stuff to do after success */
	});
});

$("body").on('click', "#cuerpoReasigna .addNewCustomerProspectiveClienteEspecial", function(event) {
	$("#modalNewCustomerEspecialAsingacion").modal("hide");
});
$("body").on('click', "#cuerpoReasigna #icon_add_legal_contac", function(event) {
	$("#modalNewCustomerEspecialAsingacion").modal("hide");
});

function setDataClient(){
	$("body").on('change', '.flujoTiendaClienteEspecial', function(event) {
		var idContactoDiv = "#"+$(this).data("contacto")
		var empresa_id = $(this).val();

		if(empresa_id.indexOf("_LEGAL") != -1){
			empresa_id = empresa_id.replace("_LEGAL","");
			var servicio_id = 0;
			$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
				$("body").find(idContactoDiv).html(result);
		    });
		}else{
			$("body").find(idContactoDiv).html("");
		}
		
	});
}

$('#btn_filtrosCero').click(function(e){
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});

$(".btn_update_user").click(function(e) {
	var flujo_id 		= $(this).data('uid');
	var asesor 			= $("#user_asignado_"+flujo_id).val();
	if (asesor != "") {
		$(".btn_update_user").hide();
		$.post(copy_js.base_url+copy_js.controller+'/updateuserAsignado',{flujo_id:flujo_id,asesor:asesor}, function(result){
			location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujo_id;
		});
	}
});

$("#texto_busqueda").click(function() {
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});

$(".btn_buscar").click(function() {
	buscadorFiltro();
});

$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
    	buscadorFiltro();
    }
});

function buscadorFiltro(){
	var texto 							= $('#txt_buscador').val();
	var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	if (texto != '') {
		var paremetroGet 				= get_parameter_name('filter');
		if (paremetroGet == '') {
			
			var hrefFinal 			= hrefURL+"?q="+texto;
			
		} else  {
			var paremetroBuscar 		= get_parameter_name('q');
			if (paremetroBuscar != '') {
				var hrefFinal 			= hrefURL+"?filter="+paremetroGet+"&q="+texto;
			} else {
				var hrefFinal		 	= hrefURL+"?q="+texto;
			}
		}
	}else{
		var hrefFinal 			= hrefURL+"?q="+texto;
	}
	location.href 					= hrefFinal;
}

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

$("body").on( "click", ".btnnovedadesetapa", function() {
	var flujo_id 		= $(this).data('uid');
	var etapa 			= $(this).data('etapa');
	$.post(copy_js.base_url+'ProspectiveUsers/add_nota',{flujo_id:flujo_id,etapa:etapa}, function(result){
		$('#modal_form_nota_body').html(result);
		$('#modal_form_nota_flujo').modal('show');
	});
});

$("body").on( "click", "#btn_save_nota", function() {
	var flujo_id 				= $('#ProgresNoteFlujoId').val();
	var etapa 					= $('#ProgresNoteEtapa').val();
	var description 			= $('#ProgresNoteDescription').val();
	if (description != '') {
		$.post(copy_js.base_url+'ProspectiveUsers/saveNotas',{flujo_id:flujo_id,etapa:etapa,description:description}, function(result){
			location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
	} else {
		message_alert("La descripción es requerida","error");
	}
});

//ampliar imagen del pago
$("body").on( "click", ".imgdesc img", function() {
    var imgdescripcion = $(this).attr("src");
    $(".img-product").attr('src',imgdescripcion);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

//ampliar imagen del pago
$("body").on( "click", ".Comprobanteacep", function() {
    var comprobante = $(this).children("img").attr("datacomprobante");
    console.log(comprobante);
    $(".img-product,#img-product").attr('src',comprobante);
    $(".fondo").fadeIn();
    $(".popup2,.popup").fadeIn();
});

$("body").on( "click", ".comprobanteimgTd", function() {
    var comprobante = $(this).children("img").attr("datacomprobantet");
    console.log(comprobante);
    $(".img-product,#img-product").attr('src',comprobante);
    $(".fondo").fadeIn();
    $(".popup2,.popup").fadeIn();
    $("#modal_information").modal("hide");
});

//ampliar imagen de LA GUIA
$("body").on( "click", ".comprobanteguia", function() {
    var guia = $(".comprobanteguia img").attr("dataguia");
    $(".img-product").attr('src',guia);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

$("body").on( "click", ".comentariosnegociado img", function() {
    var imagenbuy = $(this).attr("src");
    $(".img-product").attr('src',imagenbuy);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

$("body").on( "click", ".state_asignado", function() {
	$.post(copy_js.base_url+'FlowStages/state_invalid',{}, function(result){
		$('#modal_big_information_body').html(result);
		$('#modal_big_information_label').text('Procesamiento de flujo a asignado');
		$('#modal_big_information').modal('show');
	});
});

$("#inlineRadio1").click(function(event) {
	$("#FlowStageDescriptionNoContact").attr("required","required");
});

$("#inlineRadio2").click(function(event) {
	$("#FlowStageDescriptionNoContact").attr("required","required");
});

$("body").on( "click", ".state_contactado", function() {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	$.post(copy_js.base_url+'FlowStages/change_contactado',{flujo_id:flujo_id,state:state}, function(result){
		if (copy_js.flujo_asignado == state) {
			$('#modal_contactado_body').html(result);
			$('#modal_contactado_label').text('Procesamiento de flujo a contactado');
			$('#modal_contactado').modal('show');
			$("#FlowStageImage").dropify(optionsDp); 
			$("#form_contactado").parsley();
		} else {
			$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento de flujo a contactado');
			$('#modal_big_information').modal('show');
		}
	});
});
$("body").on( "click", ".btn_guardar_contactado", function() {
	var usuario 		= $('#FlowStageNameUsers').val();
	var name 			= $('#FlowStageName').val();
	var flujo_id 		= $('#FlowStageFlujoId').val();
	var rason 			= $('#FlowStageReason').val();
	var description 	= $('#FlowStageDescription').val();
	var origin 			= $('#FlowStageOrigin').val();

	var validateImage   = true;
	var vidFileLength   = $("#FlowStageImage")[0].files.length;
	var radio_option 	= $("#form_contactado input[type='radio']:checked").val();

	if (origin == "Presencial" || origin == "Llamada" || radio_option == "0") {
		validateImage   = false;
	}else if (vidFileLength > 0) {
		validateImage   = false;
	}


	var descriptionNoContact 	= $("#FlowStageDescriptionNoContact").length ?  $('#FlowStageDescriptionNoContact').val() : null;

	if (radio_option != undefined && rason != '' && origin != '' && description != '' && !validateImage ) {

		if ($("#FlowStageDescriptionNoContact").length && radio_option == "0" && descriptionNoContact == "") {
			message_alert("Todos los campos son requeridos","error");
			return false;
		}
		$('.btn_guardar_contactado').hide();

		var formData            	= new FormData($('#form_contactado')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'FlowStages/saveStateContactado',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
	        }
	    });

		// $.post(copy_js.base_url+'FlowStages/saveStateContactado',{radio_option:radio_option,rason:rason,origin:origin,description:description,usuario:usuario,flujo_id:flujo_id,name:name,descriptionNoContact:descriptionNoContact}, function(result){
		// 	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
		// });
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});

$("body").on( "click", ".state_cotizado", function() {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	$.post(copy_js.base_url+'FlowStages/change_cotizado',{flujo_id:flujo_id,state:state}, function(result){
		if (copy_js.flujo_contactado == state) {
			$('#modal_cotizado_body').html(result);
			$('#modal_cotizado_label').text('Procesamiento de flujo a cotizado');
			$('#modal_cotizado').modal('show');
			$('#FlowStageCopiasEmail').tagsinput();
		} else {
			$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento de flujo a cotizado');
			$('#modal_big_information').modal('show');
		}
	});
});

$("body").on('click', '.borraEmails', function(event) {
	event.preventDefault();
	$('#FlowStageCopiasEmail').tagsinput('removeAll');
});


$("body").on('click', '.changeDescription', function(event) {
	event.preventDefault();
	$("#idToChange").val($(this).data("flujo"));
	$("#descToChange").val($(this).data("description"));
	// $("#descToChange").html($(this).data("description"));
	$("#cambiarDesc").modal("show");
});

$("body").on( "click", ".btn_guardar_cotizado", function() {
	$(".body-loading").show();
	if (!document.getElementById('FlowStageQuotationId')){
		var nameDocument 		= $('#FlowStageNameDocument').val();
		var precio 				= $('#FlowStagePriceQuotation').val();
	} else {
		var nameDocument 		= 'null';
		var precio 				= 'null';
	}
	var radio_option 			= $("#form_cotizado input[type='radio']:checked").val();
	if (nameDocument != '' && precio != '' && radio_option != undefined) {
		$('.btn_guardar_cotizado').hide();
		var formData            	= new FormData($('#form_cotizado')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'FlowStages/saveStateCotizado',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.reload();
	        	// if (result > 10) {
	        	// 	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
	        	// } else {
	        	// 	$(".body-loading").hide();
	        	// 	$('.btn_guardar_cotizado').show();
	        	// 	validate_documento_pdf_message(result);
	        	// }
	        }
	    });
	} else {
		$(".body-loading").hide();
		message_alert("Todos los campos son requeridos","error");
	}
});

$("body").on( "click", ".state_negociado", function() {
	var flujo_id 		= $(this).data('uid');
	var id 			    = $(this).data('id');
	var state 			= $(this).data('state');
	var valid 			= $(this).data('valid');

	if (valid == "1") {
		message_alert("El flujo se encuentra en proceso de validación para cancelación o edición","error");
	}else{
		$.post(copy_js.base_url+'FlowStages/change_negociado',{flujo_id:flujo_id,state:state}, function(result){
			if (copy_js.flujo_cotizado == state) {
				location.href = copy_js.base_url + "orders/add/?flow="+id;
				// $('#modal_negociado_body').html(result);
				// $('#modal_negociado_label').text('Procesamiento de flujo a negociado');
				// $('#FlowStageDescription').summernote({
				//  toolbar: [
				//     ['style', ['bold', 'italic', 'underline', 'clear']],
				//     ['para', ['ul', 'ol', 'paragraph']],
				//     ['misc', ['undo', 'redo','codeview']],
				//     ['link', ['linkDialogShow', 'unlink']]
				//   ],
				//   fontNames: ['Raleway'],
				//   focus: true,
				//   disableResizeEditor: true
				// });
				// $('.note-statusbar').hide(); 
				// $('#modal_negociado').modal('show');
			} else {
				$('#modal_big_information_body').html(result);
				$('#modal_big_information_label').text('Procesamiento de flujo a negociado');
				$('#modal_big_information').modal('show');
			}
		});
	}
});
$("body").on( "click", ".btn_guardar_negociado", function() {
	var description 		= $('#FlowStageDescription').val();
	if (description != '') {
		$('.btn_guardar_negociado').hide();
		var formData            = new FormData($('#form_negociado')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'FlowStages/saveStateNegociado',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	if (result > 10) {
	        		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
	        	} else {
					$('.btn_guardar_negociado').show();
	        		validate_documento_pdf_message(result);
	        	}
	        }
	    });
	} else {
		message_alert("La descripción es requerida","error");
	}
});


$("body").on('submit', '#form_bill', function(event) {
	event.preventDefault();
	var formData            = new FormData($('#form_bill')[0]);
	var bill_value 			= parseFloat($("#ProspectiveUserBillValue").val());
	var bill_value_iva		= parseFloat($("#ProspectiveUserBillValueIva").val());

	console.log(bill_value)
	console.log(bill_value_iva)

	if(parseFloat(bill_value_iva) != parseFloat( (bill_value * 1.19) ) ){
		message_alert("Los valores no coinciden al calcular el 19% del iva","error");
		return false;
	}else{
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
	        		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
	        	} else {
					$("#btnFormSubmit").show();
	        		validate_documento_pdf_message(result);
	        	}
	        }
	    });
	}
});

function validateTypePago(){
	if($("#FlowStagePayment").val() == 'Datáfono'){
		$("#datafonoText").show();
	}else{
		$("#datafonoText").hide();
	}
}

$("body").on('change', '#FlowStagePayment', function(event) {
	validateTypePago();
});

$("body").on( "click", ".state_pagado", function() {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	var valid 			= $(this).data('valid');
	if (valid == "1") {
		message_alert("El flujo se encuentra en proceso de validación para cancelación o edición","error");
	}else{
		$.post(copy_js.base_url+'FlowStages/change_pagado',{flujo_id:flujo_id,state:state}, function(result){
			if (copy_js.flujo_negociado == state) {
				$('#modal_pagado_body').html(result);
				$('#modal_pagado_label').text('Procesamiento de flujo a pagado');
				$('#modal_pagado').modal('show');
			} else {
				$('#modal_big_information_body').html(result);
				$('#modal_big_information_label').text('Procesamiento de flujo a pagado');
				$('#modal_big_information').modal('show');
			}
			validateTypePago();
		});
	}
});
$("body").on( "click", ".btn_guardar_pagado", function() {
	var inicial = true;
	if (!$(".checkPago").length){
		var radio_option 	= 2;
		inicial = false;
	} else {
		var radio_option 	= $("#form_pagado input[type='radio']:checked").val();
	}
	var valorQuotation 		= $('#FlowStageValorQuotation').val();
	var valor 				= $('#FlowStageValor').val();
	var identificator 		= $('#FlowStageIdentificator').val();
	if (radio_option == 3) {
		var medio 			= 'null';
	} else {
		var medio 			= $('#FlowStagePayment').val();
	}

	var validaId 			= false;

	if (radio_option != 3 && radio_option != 5 ) {
		validaId 			= identificator == '' ? true : false;
	}

	if (valor != '' && medio !=  '' && radio_option != undefined && !validaId ) {
		validate_sale 		= validate_price_quotation(valor,valorQuotation,radio_option);
		if (validate_sale > 1) {
			validate_message_sale(validate_sale,radio_option);
			return false;
		} else {
			$(".body-loading").show();
			$('.btn_guardar_pagado').hide();
			var formData            = new FormData($('#form_pagado')[0]);
			$.ajax({
		        type: 'POST',
		        url: copy_js.base_url+'FlowStages/saveStatePagado',
		        data: formData,
		        contentType: false,
		        cache: false,
		        processData:false,
		        success: function(result){
		        	$(".body-loading").hide();

		        	if ($.trim(result) == '10000EC' ) {
		        		$('.btn_guardar_pagado').show();
		        		message_alert("El número de voucher o identificador de transacción ya existe en el sistema",'error');
		        	}else{

			        	if (result > 10) {

			        		if (inicial) {
			        			$("#modal_pagado").modal("hide");
			        			document.getElementById('form_pagado').reset();
			        			gestProducts(result,true);
			        		}else{
		        				location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
			        		}

			        	} else {
			        		$('.btn_guardar_pagado').show();
			        		validate_image_message(result);
			        	}
		        	}
		        }
		    });
		}
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});


function validate_price_quotation(valor,valorQuotation,type_sale){
	var precioSinIva 			= parseFloat(valorQuotation);
	var valorIva 				= $("#FlowStageTotalParaIva").length ? parseFloat( $("#FlowStageTotalParaIva").val() ) : precioSinIva;
	var ivaProducto 			= valorIva * parseInt(copy_js.iva)/100;
	// var retencionProducto 		= precioSinIva * parseFloat(copy_js.valor_retencion)/100;
	// var precioRetencionIva 		= (precioSinIva - parseInt(retencionProducto)) + parseInt(ivaProducto);
	var precioConiva 			= precioSinIva + parseInt(ivaProducto);
	switch (parseInt(type_sale)){
		case 0: // Total con iva
			if(parseFloat(valor) >= parseFloat(precioConiva)) {
				$validate = 1;
			} else {
				$validate = 2;
			}
            break;
        case 1: // Total con retención
			$validate = 1;
            break;
        case 2: 
        case 6: 
        	var valorMin = type_sale == 2 ? $("#abonoLabelDiv").data('minval') : $("#abonoLabelDivNoIva").data('minval');
        	// Abono
        	if(parseFloat(valor) < parseFloat(valorMin) ) {
				$validate = 8;
			}else if(parseFloat(valor) < parseFloat(precioConiva)){
				$validate = 1;
			}else {
				$validate = 4;
			}
            break;
        case 3: // A crédito con iva
        	if(parseFloat(valor) >= parseFloat(precioConiva)) {
				$validate = 1;
			} else {
				$validate = 5;
			}
            break;
        case 4:
        	if(parseFloat(valor) >= precioSinIva) {
				$validate = 1;
			} else {
				$validate = 6;
			}
        	break;
        case 5: // A crédito sin iva
        	if(parseFloat(valor) == precioSinIva) {
				$validate = 1;
			} else {
				$validate = 7;
			}
            break;
	}
	return $validate;
}
$("body").on( "change", "#form_pagado input[type='radio']", function() {
	var radio_option 		= $("#form_pagado input[type='radio']:checked").val();
	var estados_credito 	= ['3','5'];
	if (estados_credito.indexOf(radio_option) != -1) {
		$('.type_payu').hide();
		$('.idVP').hide();
		document.getElementById("labelIdValorPagado").createTextNode = "Por favor ingresa el valor a crédito";
		document.getElementById("FlowStageValor").placeholder = "Por favor ingresa el valor a crédito";
		$.post(copy_js.base_url+'Payments/list_option_payments_credito',{}, function(result){
			$('#FlowStagePayment').empty();
			$('.dias_credito').empty();
			$('#FlowStagePayment').append(result);
			$('.dias_credito').empty();
			var label 			= document.createElement("label");
			label.setAttribute("for","FlowStagePayment");
			labeltext 			= document.createTextNode('Por favor selecciona el número de días de la aprobación del crédito');
			label.appendChild(labeltext);
			$('.dias_credito').append(label);
			var arr = [
			  {val : 1, text: '30 días'},
			  {val : 4, text: '45 días'},
			  {val : 2, text: '60 días'},
			  {val : 3, text: '90 días'}
			];
			var sel = $('<select name="select_dias">').appendTo('.dias_credito');
			$(arr).each(function() {
			 sel.append($("<option>").attr('value',this.val).text(this.text));
			});
			$('.dias_credito').append('<br>');
		});
	} else {
		document.getElementById("labelIdValorPagado").createTextNode = "¿Cuánto dinero pagó el cliente?";
		document.getElementById("FlowStageValor").placeholder = "¿Cuánto dinero pagó el cliente?";
		$.post(copy_js.base_url+'Payments/list_option_payments',{}, function(result){
			$('.dias_credito').empty();
			$('#FlowStagePayment').empty();
			$('#FlowStagePayment').append(result);
			$('.type_payu').show();
			$('.idVP').show();
		});
	}
});

$("body").on( "click", ".state_despachado", function() {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	var stateflow 		= $(this).data('stateflow');
	$.post(copy_js.base_url+'FlowStages/change_despachado',{flujo_id:flujo_id,state:state,stateflow:stateflow}, function(result){
		$('#modal_big_information_body').html(result);
		$('#modal_big_information_label').text('Procesamiento de flujo a despachado');
		$('#modal_big_information').modal('show');
	});
});

$("body").on( "click", ".btn_eliminar_cotizacion", function() {
	var model_id 			= $(this).data('uid');
	var state 				= "0";
	var controlador 		= name_controller();
	swal({
        title: "¿Estas seguro de eliminar la "+controlador+"?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+'ProspectiveUsers/changeStateModel',{model_id:model_id,state:state}, function(result){
	        // location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	        location.reload();
	    });
    });
});

$("body").on( "click", "#btn_cambiar_estado", function() {
	var flujo_id 			= $(this).data('uid');
	var flow_id 			= $(this).data('flowstages');
	var controlador 		= name_controller();
	swal({
        title: "¿Estas seguro de crear una nueva "+controlador+"?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){

  //   	var formData            	= new FormData($('#form_cotizado')[0]);
		// $.ajax({
	 //        type: 'POST',
	 //        url: copy_js.base_url+'FlowStages/saveStateCotizado',
	 //        data: formData,
	 //        contentType: false,
	 //        cache: false,
	 //        processData:false,
	 //        success: function(result){
	 //        	location.reload();
	 //        	// if (result > 10) {
	 //        	// 	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
	 //        	// } else {
	 //        	// 	$(".body-loading").hide();
	 //        	// 	$('.btn_guardar_cotizado').show();
	 //        	// 	validate_documento_pdf_message(result);
	 //        	// }
	 //        }
	 //    });

    	$.post(copy_js.base_url+'FlowStages/updateStateCotizadoContactado',{flujo_id:flujo_id,flow_id:flow_id}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
    });
});

$('body').on("click", ".btn_state_pagado", function(event) {
	var flujo_id 		= $(this).data('uid');
	var tipo_pago 		= $(this).data('pago');
	swal({
        title: "¿Estas seguro que deseas enviar el flujo a verificación de pago?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
    	$.post(copy_js.base_url+'ProspectiveUsers/updateStatePagadoProspective',{flujo_id:flujo_id,tipo_pago:tipo_pago}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
    });
});

$("body").on('click', '.newQuotation', function(event) {
	$("#modal_pagado").modal("hide");
	event.preventDefault();
	var flujo_id 		= $(this).data('uid');
	var flow_stage 		= $(this).data('flow_stage');
	swal({
		title: "¿Estas seguro de crear una nueva cotización?",
		text: "Por favor ingresa el motivo por el crearas una nueva cotización",
		type: "input",
		showCancelButton: true,
		closeOnConfirm: false,
		confirmButtonClass: "btn-danger",
		cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
		inputPlaceholder: "Descripción"
	}, function (inputValue) {
		if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a crear una nueva cotización","error");
            return false;
        }
        $.post(copy_js.base_url+'ProspectiveUsers/create_quotation',{flujo_id:flujo_id,rason:inputValue,flow_stage}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
	});
	/* Act on the event */
});

$('body').on("click", ".btn_cancelar_flujo", function(event) {
	var flujo_id 		= $(this).data('uid');
	swal({
		title: "¿Estas seguro que deseas cancelar el flujo?",
		text: "Por favor ingresa el motivo por el cual vas a cancelar el flujo:",
		type: "input",
		showCancelButton: true,
		closeOnConfirm: false,
		confirmButtonClass: "btn-danger",
		cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
		inputPlaceholder: "Descripción"
	}, function (inputValue) {
		if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a cancelar el flujo","error");
            return false;
        }
        $.post(copy_js.base_url+'ProspectiveUsers/updateStateCanceladoProspective',{flujo_id:flujo_id,rason:inputValue}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
	});
});

$('body').on("click", ".btnRetornoFlujo", function(event) {
	var flujo_id 		= $(this).data('uid');
	var state 			= $(this).data('state');
	swal({
		title: "¿Estas seguro que deseas retornar este flujo para ser cotizado de nuevo?",
		text: "Por favor ingresa el motivo por el cual solicitas retornar el flujo:",
		type: "input",
		showCancelButton: true,
		closeOnConfirm: false,
		confirmButtonClass: "btn-danger",
		cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
		inputPlaceholder: "Descripción"
	}, function (inputValue) {
		if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa el motivo por el cual solicitas retornar el flujo","error");
            return false;
        }
        $.post(copy_js.base_url+'ProspectiveUsers/return_request',{flujo_id:flujo_id,rason:inputValue,state}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
	});
});

$('body').on("click", ".verificar_pago_abono", function(event) {
	var etapa_id 		= $(this).data('etapa');
	var flujo_id 		= $(this).data('uid');
	swal({
        title: "¿Estas seguro de que el cliente ya finalizo los pagos?, contabilidad debe aprobar el pago en su totalidad",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
    	$.post(copy_js.base_url+'FlowStages/verificarContabilidadPagoAbono',{etapa_id:etapa_id,flujo_id:flujo_id}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+flujo_id;
		});
    });
});

$('body').on("keyup", "#FlowStageValor", function(event) {
    var precio              = $('#FlowStageValor').val();


    if(!validateDecimal(precio)){
        event.preventDefault();
         $('#FlowStageValor').val(string_valdate_Number(precio,"."));        
        return false;
    }


    // var precio_final        = number_format(precio);
    $('#FlowStageValor').val(precio);
    setTimeout(function() {
		var valorPago = parseInt($('#FlowStageValor').val());
		var valorDescuento = valorPago*0.03;
		$("#SumaDatafono").html(valorDescuento.toFixed(2))
		$("#FlowStageDiscountDatafono").val(valorDescuento.toFixed(2))
	}, 1000);
}).on('change', '#FlowStageValor', function(event) {

	setTimeout(function() {
		var valorPago = parseInt($('#FlowStageValor').val());
		var valorDescuento = valorPago*0.03;
		$("#SumaDatafono").html(valorDescuento.toFixed(2))
		$("#FlowStageDiscountDatafono").val(valorDescuento.toFixed(2))
	}, 1000);

});;

$("body").on( "click", "#btn_find_existencia_contacto", function() {
	var email 			= $('#ContacsUserEmail').val();
	if (email != '') {
		$.post(copy_js.base_url+'ContacsUsers/validExistencia',{email:email}, function(result){
			if (result == 0) {
	            message_alert("El correo electrónico esta disponible","Bien");
	        } else {
	            message_alert("El correo electrónico ya esta registrado","error");
	        }
		});
	} else {
		message_alert("Por favor ingresa el correo electrónico de la empresa","error");
	}
});

$('body').on("click", "#btn_informacion_despacho", function(event) {
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
$("body").on( "click", "#icon_add", function() {
	var empresa_id 		= $(this).data('bussines');
	$.post(copy_js.base_url+'ContacsUsers/add_user_form',{empresa_id:empresa_id}, function(result){
		$('#modal_form_body').html(result);
		$('#modal_form_label').text('Registrar contacto');
		$('#modal_form').modal('show');
	});
});
$("body").on( "click", ".btn_guardar_form", function() {
	if (!document.getElementById('ContacsUserEmpresaId')){
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
				location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
			});
		} else {
			message_alert("Los campos contacto, telefono, ciudad y dirección son requeridos","error");
		}
	} else {
		var empresa_id 		= $('#ContacsUserEmpresaId').val();
		var name 			= $('#ContacsUserName').val();
		var telephone 		= $('#ContacsUserTelephone').val();
		var cell_phone 		= $('#ContacsUserCellPhone').val();
		var email 			= $('#ContacsUserEmail').val();
		var cityForm 		= $('#cityForm').val();
		if (name != '' && telephone != '' && cell_phone != '' && email != '' && cityForm != '') {
			$('.btn_guardar_form').hide();
			$.post(copy_js.base_url+'ContacsUsers/addUser',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm}, function(result){
				if (result == 0) {
                    $('.btn_guardar_form').show();
                    message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
                } else {
                	 list_contacs_bussines(result);
					$('#modal_form').modal('hide');
					$('.btn_guardar_form').show();
					message_alert("Contacto creado satisfactoriamente","Bien");
                }
			});
		} else {
			message_alert("Todos los campos son requeridos","error");
		}
	}	
});

$('body').on("keyup", "#FlowStagePriceQuotation", function(event) {
    var precio              = $('#FlowStagePriceQuotation').val();
    var precio_final        = number_format(precio);
    $('#FlowStagePriceQuotation').val(precio_final);
});

// $('body').on("keyup", "#ProspectiveUserBillValue", function(event) {
//     var precio              = $('#ProspectiveUserBillValue').val();
//     var precio_final        = number_format(precio);
//     $('#ProspectiveUserBillValue').val(precio_final);
// });

$("body").on( "click", ".reactivar_flujo", function() {
	var flujo_id 		= $(this).data('uid');
	swal({
        title: "¿Estas seguro de volver a activar el flujo?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
		$.post(copy_js.base_url+'ProspectiveUsers/reactivarFlujo',{flujo_id:flujo_id}, function(result){
			location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result;
		});
	});
});

$("body").on('click', '.activar_flujo', function(event) {
	event.preventDefault();
	id = $(this).data("id");
	swal({
        title: "¿Estas seguro de volver a activar el flujo?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
		$.post(copy_js.base_url+'ProspectiveUsers/reloadFlujo',{id}, function(result){
			location.href = copy_js.base_url+'ProspectiveUsers/index?q='+id;
		});
	});
});

function gestProducts(flujo_id, cancelData = false){
	$(".body-loading").show();
	$.post(copy_js.base_url+'FlowStages/administrator_orden',{flujo_id:flujo_id}, function(result){
	$(".body-loading").hide();
		$('#modal_administrar_despachado_body').html(result);
		$('#modal_administrar_despachado_label').text('Gestionar productos para importación');

		if (cancelData) {

			$('#modal_administrar_despachado').modal({
              keyboard: false,
              show: true,
              backdrop: 'static'
            });

		}else{
			$('#modal_administrar_despachado').modal('show');

		}

		setTimeout(function() {
			$(function () {
			  $('[data-toggle="popover"]').popover({
			  	container: 'body',
			  	html: true,
			  })
			})
		}, 1000);
		$(function () {
		  $('[data-toggle="popover"]').popover()
		})
	});
}

$("body").on( "click", ".btn_administrar_orden", function() {
	var flujo_id 		= $(this).data('uid');
	gestProducts(flujo_id)
});

$("body").on('click', '.importDataDeliveryCheck', function(event) {
	var uid = $(this).data("uid");
	var idElement = ".importDeliveryTime_"+uid;
	console.log(idElement);
	var idTextElement = ".deliveryData_"+uid;
	var idTextElementBodega = ".deliveryDataBodega_"+uid;
	// if($("body").find(idElement).length){
	// 	if($(this).prop("checked")){
	//     	$("body").find(idElement).removeAttr('disabled');
	//     	$("body").find(idElement).show();
	//     	$("body").find(idTextElement).hide();
	//     }else{
	//     	$("body").find(idElement).attr('disabled', 'disabled');
	//     	$("body").find(idElement).hide();
	//     	$("body").find(idTextElement).show();
	//     }	
	// }else{
	// 	if($(this).prop("checked")){
	// 		$("body").find(idTextElementBodega).hide();
	// 	}else{
	// 		$("body").find(idTextElementBodega).show();
	// 	}
	// }
	return false;
	
    
});

$("body").on( "click", ".btn_guardar_administrar", function() {
	var formData            = new FormData($('#form_administrar_pedido')[0]);

	$('.btn_guardar_administrar').hide();
	$.ajax({
        type: 'POST',
        url: copy_js.base_url+'FlowStages/saveManageOrden',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
        	console.log(result)
        	$('.btn_guardar_administrar').show();
        	location.reload();
        	// location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
        }
    });
	
	
	
});

$("body").on( "click", ".btn_editar_contacto", function() {
	$('#cityForm2').hide();
	var contact_id 			= $(this).data('uid');
	var flujo_id 			= $(this).data('flujo');
	$.post(copy_js.base_url+'ContacsUsers/edit_user_form',{contact_id:contact_id,flujo_id:flujo_id}, function(result){
       $('#modal_form_body_edit_contacto').html(result);
		$('#modal_form_label_edit_contacto').text('Editar contacto');
		$('#modal_form_edit_contacto').modal('show');
    });
});
$("body").on( "click", ".btn_editar_ciudad_1", function() {
	$('#cityForm2').show();
});
$("body").on( "click", ".btn_guardar_form_edit_contacto", function() {
	var flujo_id			= $('#ContacsUserFlujoId').val();
	var contac_id			= $('#ContacsUserIdContact').val();
	var name 				= $('#ContacsUserName').val();
	var telephone 			= $('#ContacsUserTelephone').val();
	var cell_phone			= $('#ContacsUserCellPhone').val();
	var email				= $('#ContacsUserEmail').val();
	var city 				= $("#cityForm2").length ? $('#cityForm2').val() : $("#ProspectiveUserCityForm2").val();
	if (city == '') {
		city 				= $('#ContacsUserCity').val();
	}
	if (name != '' && city != '' && email != '') {
		$('.btn_guardar_form').hide();
		$.post(copy_js.base_url+'ContacsUsers/editSave',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,contac_id:contac_id},function(result){
			location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujo_id;
		});
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});

var optionsDp = {
    messages: {
        'default': 'Seleccione o arrastre el archivo',
        'replace': 'Seleccione o arrastre el archivo',
        'remove':  'Remover',
        'error':   'Error al cargar el archivo'
    },
    error: {
        'fileSize': 'El archivo supera el tamaño permitido de: ({{ value }}).',
        'fileExtension': 'El formato del archivo seleccionado no es permitido (Permitidos: {{ value }} ).'
    }
};

$("body").on( "click", ".btn_editar_natural", function() {
	$('#cityForm1').hide();
	var cliente_id 			= $(this).data('uid');
	var flujo_id 			= $(this).data('flujo');
	$.post(copy_js.base_url+'ClientsNaturals/edit_flujos',{cliente_id:cliente_id,flujo_id:flujo_id}, function(result){
		$('#modal_form_body_edit_natural').html(result);
		$('#modal_form_label_edit_natural').text('Editar cliente');
		$('#modal_form_edit_natural').modal('show');
		$("#documentoForm1").dropify(optionsDp); 
		$("#documentoForm2").dropify(optionsDp); 
		$("#ClientsNaturalEditFlujosForm").parsley();
	});
});
$("body").on( "click", ".btn_editar_ciudad", function() {
	$('#cityForm1').show();
});
$("body").on( "click", ".btn_guardar_form_edit_natural", function() {
	var flujo_id			= $('#ClientsNaturalFlujoId').val();
	var cliente_id			= $('#ClientsNaturalId').val();
	var name 				= $('#ClientsNaturalName').val();
	var telephone 			= $('#ClientsNaturalTelephone').val();
	var cell_phone			= $('#ClientsNaturalCellPhone').val();
	var email				= $('#ClientsNaturalEmail').val();
	var identification		= $('#ClientsNaturalIdentification').val();
	var city 				= $('#cityForm1').length ? $('#cityForm1').val() : $("#ProspectiveUserCityForm1").val();
	var view 				= $('#ClientsNaturalAction').val();
	if (city == '') {
		city 				= $('#ClientsNaturalCity').val();
	}
	if (name != '' && city != '' && email != '') {
		$('.btn_guardar_form').hide();

		var formData            = new FormData($('#ClientsNaturalEditFlujosForm')[0]);
		$.ajax({
	        type: 'POST',
	        url:  copy_js.base_url+'ClientsNaturals/addSaveFlujos',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	            location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujo_id;
	        }
	    });
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});


$("body").on('click', '.reenviarCot', function(event) {
	event.preventDefault();		
	var id 			= $(this).data("uid");
	var flowstages 	= $(this).data("flowstages");
	$(".body-loading").show();

	$.post(copy_js.base_url+'flow_stages/reenviar',{id,flowstages},function(result){
		location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+id;
	});
});

$("body").on('click', '.sendDataLlc', function(event) {
	event.preventDefault();
	var url = $(this).attr("href");
	$(".body-loading").show();
	$.post(url, {}, function(data, textStatus, xhr) {
		location.reload();
	});
});



function validate_message_sale(estado, radio = 2){
    estado                      = parseInt(estado);
    var tipo                    = "error";
    switch (estado) {
        case 2:
            var mensaje         = "Por favor valida, ya que seleccionaste el tipo de pago de Total con iva, del "+parseInt(copy_js.iva)+"% y el valor ingresado es inferior";
            break;
        case 3:
            var mensaje         = "Por favor valida, ya que seleccionaste el tipo de pago de Total con Retención y el valor ingresado es menor o mayor al total de la cotización menos a la retencion en la fuente equivalente a "+parseFloat(copy_js.valor_retencion)
            break;
        case 4:
            var mensaje         = "Por favor valida, ya que seleccionaste el tipo de pago de Abono y el valor ingresado es mayor o igual al total de la cotización más el iva";
            break;
        case 5:
            var mensaje         = "Por favor valida, ya que seleccionaste el tipo de pago de A crédito con iva y el valor ingresado es menor o mayor al total de la cotización más el iva del "+parseInt(copy_js.iva)+"%";
            break;
        case 6:
        	var mensaje 		= "Por favor valida, ya que seleccionaste el tipo de pago Total sin iva y el valor ingresado es menor al de la cotización";
        	break;
        case 7:
        	var mensaje 		= "Por favor valida, ya que seleccionaste el tipo de pago A crédito sin iva y el valor ingresado es menor al de la cotización";
        	break;
        case 8:
        	var mensaje 		= "Por favor valida, ya que seleccionaste el tipo de pago con abonos y no cumple el valor mínimo de: " + ( radio == 2 ?  $("#abonoLabelDiv").data("minval") : $("#abonoLabelDivNoIva").data("minval") );
        	break;
    }
    message_alert(mensaje,tipo);
}

$("body").on('click', '.detailCliente', function(event) {
	event.preventDefault();
	var anio =  $(this).data("anio");
	var id   =  $(this).data("id");
	$(".body-loading").show();
	$.post(copy_js.base_url+'pages/get_info_detail',{anio,id}, function(result){
		$("#detalleVentas").html("");
		$('#detalleVentas').html(result);
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#modalFacturaDetalle').modal('show');
		$(".body-loading").hide();
		$("#divDetalle").hide();

	});
});

$("body").on('click', '.vewInfoCode', function(event) {
	event.preventDefault();
	var factura = $(this).data("code");
	$("#divListado").hide();
	$("#divDetalle").show();
	$("#facturaDetalleWO").html("");

	const arrText = factura.split(" ");

	var prefijo   = arrText[0];
	var number    = arrText[1];

	$.ajax({
        url: copy_js.base_url+"ProspectiveUsers/get_document",
        type: 'post',
        data: {number,prefijo,nuevo: 1},
        beforeSend: function(){
            $(".body-loading").show();
        }
    })
    .done(function(response) {
        $("#facturaDetalleWO").html(response)
    })
    .fail(function() {
        message_alert("Error al consultar","error");
    })
    .always(function() {
        $(".body-loading").hide();
    });

});

$("body").on('click', '#regresaInfoDetalle', function(event) {
	event.preventDefault();
	$("#divListado").show();
	$("#divDetalle").hide();
});


$(".mostradDatosFact").click(function(event) {
	event.preventDefault();
	$("#bodyDetalleFactura").html("");

	var newid = "#"+$(this).data("id");
	$("#bodyDetalleFactura").html($(newid).html());
	$("#modalFacturaDetalle").modal("show");
});

function list_contacs_bussines(nuevo_id){
	$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
		$('#contac_id').append(result);
		$('#contac_id').val(nuevo_id);
	});
}

function initialize() {
	var input = document.getElementById('cityForm');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);

function initialize1() {
	var input = document.getElementById('cityForm1');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize1);

function initialize2() {
	var input = document.getElementById('cityForm2');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize2);

