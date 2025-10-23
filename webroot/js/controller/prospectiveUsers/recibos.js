$("body").on('submit', '#ReceiptEditForm,#ReceiptEditRecipeForm', function(event) {
	event.preventDefault();
	id = $("body").find('#ReceiptProspectiveUserId').val();
	var formData = $(this).serialize();
	if( $("body").find('#ReceiptTotalIva').val() != "" ){
		$(".body-loading").show();
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'receipts/edit?id='+id,
	        data: formData,
	        success: function(result){
	        	$(".body-loading").hide();

	        	if (typeof RECIBO_HISTORY != 'undefined') {
	        		$("#cuerpoRecibo").html("");
	        		$("#recibodeCaja").modal("hide");
	        		message_alert("Recibo guardado correctamente","Bien");
	        	}else{
	        		location.reload();
	        	}

	        },error: function(){
	        	message_alert("Error al guardar","error");
	        }
	    });
	}else{
		message_alert("Debe ingresar un valor","error");
	}
	
});

function getFlowPicker(id,dropDown = null,multiple = false){
    var options = {
        placeholder: "Buscar flujo",
        minimumInputLength: 5,
        multiple: multiple,
        language: "es",
        ajax: {
            url: copy_js.base_url+"prospective_users/get_flow_data",
            dataType: 'json',
             data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;

              return {
                results: data.items,
                // pagination: {
                //   more: (params.page * 30) < data.total_count
                // }
              };
            },
        }
    };

    if (dropDown != null) {
        options["dropdownParent"] = $(dropDown);
    }

    $("body").find(id).select2(options).on("select2:select", function (e) {  $("#buttonSearchList").trigger('click');  });
}

if ($("#flujoFactBuscaListReceipt").length) {
	getFlowPicker("#flujoFactBuscaListReceipt");
}

$("body").on('click', '.nuevoReciboBtn', function(event) {
	event.preventDefault();
	
	var id = $(this).data("recibo");
	$("#cuerpoRecibo").html("");
	$("#recibodeCaja h5.modal-title").html("Ingreso de información de recibo de caja");
	$.get(copy_js.base_url+'receipts/edit', {id}, function(data, textStatus, xhr) {
		$("#cuerpoRecibo").html(data);
		$("body").find('#ReceiptDateReceipt').attr("type", "date");
		if($("body").find('#ReceiptDateReceipt').val() == ""){

			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			today = yyyy + "-"+ mm + "-"+ dd;
			$("body").find('#ReceiptDateReceipt').val(today)
		}
		$("#recibodeCaja").modal("show");
	});
});

$("body").on('click', '#validarValor', function(event) {
	event.preventDefault();
	var code = $("#ReceiptCode").val();
	if (code != "") {
		$("#loaderKebco").show();
		$.post(copy_js.base_url+'receipts'+'/get_info_wo', {code}, function(data, textStatus, xhr) {
	        const response = JSON.parse(data);
	        $("#loaderKebco").hide();
	        if (response.hasOwnProperty("Total")) {
	        	$("#ReceiptTotal").val(response.Total);
	        	$("#ReceiptDateReceipt").val(response.Fecha);
	        	$("#ReceiptTotal").trigger("change");
	        }else{
	        	message_alert("No se encontró información sobre este recibo","error");
	        }
	        console.log(response)
	    });
	}else{
		message_alert("Por favor ingresa el número o código del recibo ","error");
	}
});

$("body").on('click', '.btnEditRecipe', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$("#recibodeCaja h5.modal-title").html("Edición de información de recibo de caja");
	$.get(copy_js.base_url+'receipts/edit_recipe', {id}, function(data, textStatus, xhr) {
		console.log(data);
		$("#cuerpoRecibo").html(data);
		$("body").find('#ReceiptDateReceipt').attr("type", "date");
		if($("body").find('#ReceiptDateReceipt').val() == ""){

			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			today = yyyy + "-"+ mm + "-"+ dd;
			$("body").find('#ReceiptDateReceipt').val(today)
		}
		$("#recibodeCaja").modal("show");
	});
});

$("body").on('change', '#ReceiptTotal', function(event) {
	if($(this).val() != "" && $(this).val() != "0"){
		var total = parseFloat($(this).val()/1.19);
		$("body").find("#ReceiptTotalIva").val( total.toFixed(0) );
	}
});

$("body").on('click', '#buttonSearchList', function(event) {
	event.preventDefault();
	var id = $("#flujoFactBuscaListReceipt").val();
	if (id != "") {
		$.post(copy_js.base_url+'ProspectiveUsers'+'/info_receipts', {id}, function(data, textStatus, xhr) {
	        $("#listadoRecibosActuales").html(data);
	    });
	}else{
		message_alert("Por favor ingresa el Número del flujo","error");
	}
});	