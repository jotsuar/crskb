$("body").on('submit', '#form_despachado_full', function(event) {
	event.preventDefault();
	var valueFlete = $("body").find('#FlowStageFlete').val();
	if($("body").find('.direccionesCliente').length || valueFlete == "Tienda"){
		$("body").find("#FlowStageConveyor").removeAttr("disabled");
		var formData            = new FormData($('#form_despachado_full')[0]);
		$(".body-loading").show();
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'FlowStages/saveDespachoDatos',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	console.log(result);
	        	$(".body-loading").hide();
	        	if (result == 1) {

	        		$.post(copy_js.base_url+'prospective_users/updateStateFinishProspective',{flujo_id: $("#FlowStageProspectiveUserId").val() }, function(result){
	        			location.reload();
			        });

	        	} else {
	        		$('.btn_guardar_despachado').show();
	        		validate_image_message(result);
	        	}
	        }
	    });
	}else{
		message_alert("Debe agregar una dirección de envío.","error");
	}
});
$("body").on( "click", ".radiodiv", function() {
     $(this).find(".direccionesCliente").prop("checked", true);
     $(this).addClass("activeaddress");
    $(".radiodiv").not(this).removeClass("activeaddress");
});

$("body").on('change', '#FlowStageFlete', function(event) {
	event.preventDefault();
	
	var valueFlete = $(this).val();

	if(valueFlete == "Tienda"){
		$("body").find("#FlowStageCopiasEmail").val("");
		$("body").find("#FlowStageCopiasEmail").attr("value","");
		$(".bootstrap-tagsinput>.label>span").click();
		$("body").find(".bootstrap-tagsinput>input,#FlowStageCopiasEmail").attr("readonly","readonly");
		$("body").find("#FlowStageNumber").val("0");
		$("body").find("#FlowStageNumber").attr("readonly","readonly");
		$("body").find("#FlowStageConveyor").val("Entrega en Oficina");
		$("body").find("#FlowStageConveyor").attr("disabled","disabled");
		// $("body").find("#inlineRadio2").prop("checked",true);
		// $("body").find("#inlineRadio1").prop("checked",false);
		// $("body").find("#inlineRadio1").prop("disabled","disabled");
	}else{
		$("body").find(".bootstrap-tagsinput>input,#FlowStageCopiasEmail").removeAttr("readonly");
		$("body").find("#FlowStageNumber").val("");
		$("body").find("#FlowStageNumber").removeAttr("readonly");
		$("body").find("#FlowStageConveyor").val("Entrega en Oficina");
		$("body").find("#FlowStageConveyor").removeAttr("disabled");
		$("body").find("#FlowStageConveyor").val("");
		// $("body").find("#inlineRadio2").removeAttr("checked");
		// $("body").find("#inlineRadio2").prop("checked",false);
		// $("body").find("#inlineRadio1").prop("checked",false);
		// $("body").find("#inlineRadio1").removeAttr("disabled");
	}

});