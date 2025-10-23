$("body").on('click', '.btnChargeFacture', function(event) {
	event.preventDefault();
	var flowId = $(this).data("id");
	$("#listadoFacturasActuales").html("");
	$("#modalIngresoFact").modal("show");
	if (typeof flowId != "undefined") {
		$("#flujoFactBusca").val(flowId);
		$("#buttonBuscaFact").trigger('click');

		setTimeout(function() {
			$(".info_bill2").trigger('click');
		}, 1000);
	}
});

$("body").on('click', '#buttonBuscaFact', function(event) {
	event.preventDefault();
	var id = $("#flujoFactBusca").val();
	if (id != "") {
		$.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information_list', {id,modalIngreso: 2}, function(data, textStatus, xhr) {
	        $("#listadoFacturasActuales").html(data);
	    });
	}else{
		message_alert("Por favor ingresa el Número del flujo","error");
	}
});	

$("body").on('click', '.info_bill2', function(event) {
	event.preventDefault();
	id = $(this).data("uid");    
    bill = $(this).data("bill");
    view =  typeof $(this).data("view") != "undefined" ? 1 : null;
    $.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information', {id, view, bill}, function(data, textStatus, xhr) {
    	$("#divGeneralIngreso").addClass('d-none');
        $("#formularioIngresoFact").html(data);
    });	
});

$("body").on('click', '.btnRC', function(event) {
	event.preventDefault();
	$("#rcAct").html("");
	$("#modalIngrsoRC").modal("show");
});

$("body").on('click', '#buttonBuscaRC', function(event) {
	event.preventDefault();
	var id = $("#flujoFactBuscaRC").val();
	if (id != "") {
		$.get(copy_js.base_url+'receipts/edit', {id}, function(data, textStatus, xhr) {
			$("#rcAct").html(data);
			$("body").find('#ReceiptDateReceipt').attr("type", "date");
			if($("body").find('#ReceiptDateReceipt').val() == ""){

				var today = new Date();
				var dd = String(today.getDate()).padStart(2, '0');
				var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
				var yyyy = today.getFullYear();

				today = yyyy + "-"+ mm + "-"+ dd;
				$("body").find('#ReceiptDateReceipt').val(today)
			}
		});
	}else{
		message_alert("Por favor ingresa el Número del flujo","error");
	}
});	


$("body").on('click', '.quotationWO', function(event) {
	event.preventDefault();

	var id = $(this).data("flujo");
	var flow_qt = $(this).data("qt");

	$.get(copy_js.base_url+'ProspectiveUsers'+'/bill_qt/'+id+'/'+flow_qt, {}, function(data, textStatus, xhr) {
        $("#cuerpoQtFromBill").html(data);
        $("#modal_pagado").modal("hide");
		$("#modalQtFromBill").modal("show");
    });

	
});

$("body").on('click', '.validarFacturaWo2', function(event) {
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
            $(".datosWo2").html(response);
            $(".datosWo2 .validateFinal").hide();
            $(".noShow").show();
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
        
    }

});

$("#messageBoss").click(function(event) {
	event.preventDefault();
	$("#modalMensajeBoss").modal("show");
	$("#bodyMessage").summernote({
	  toolbar: [
	    ['style', ['bold', 'italic', 'underline', 'clear']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['misc', ['undo', 'redo','codeview']],
	    ['link', ['linkDialogShow', 'unlink']]
	  ],
	  fontNames: ['Raleway'],
	  focus: true,
	  height: 200,
	  disableResizeEditor: true,
	  callbacks: {
	    onKeyup: function(e) {
	    }
	  }
	});
});

$("#formMessageBoss").submit(function(event) {
	event.preventDefault();

	if ($('#bodyMessage').summernote('isEmpty')){
	 	message_alert("El mensaje es requerido","error");
	 	return false;
	}
	$(".body-loading").show();
	$.post(copy_js.base_url+"ProspectiveUsers/sendMessageBoss", { message: $('#bodyMessage').summernote('code'), subject: $("#subjectMessage").val() } , function(data, textStatus, xhr) {
		$(".body-loading").hide();
		$("#bodyMessage").val("");
		$("#bodyMessage").summernote('reset');
		$("#subjectMessage").val("");
		message_alert("El mensaje fue enviado correctamente","Bien");
	});
});