$(".checkB").click(function(event) {
	validateChecks();
});

function validateChecks(){

	totalCount = 0;
	totalChecks = 0;
	totalValue = 0;
	class_delete = "btnChangePayment";

	$(".checkB").each(function(index, el) {

		if($(this).prop("checked")){
			totalCount+=1;
		}else{
			$("body").find(".check_all").prop("checked",false);
		}
		totalChecks++;
	});

	console.log(totalCount == totalChecks)
	if(totalCount == totalChecks){
		$("body").find(".check_all").prop("checked",true);
	}

	if(totalCount > 0){
		$("body").find("a."+class_delete).show();
	}else{
		$("body").find("a."+class_delete).hide();
		$("body").find(".check_all").prop("checked",false);
	}
}

$(".check_all").change(function(event) {
	
	claseCheck 	= "checkB";
	checkAll 	= $(this).is(":checked") ? true : false;

	if(checkAll){
		$("body").find("."+claseCheck).prop("checked",true);
	}else{
		$("body").find("."+claseCheck).prop("checked",false);
	}
	validateChecks();

});

$(".btnChangePayment").click(function(event) {
	event.preventDefault();

	var RECEIPTS = [];

	$(".checkB").each(function(index, el) {
		if($(this).prop("checked")){
			RECEIPTS.push($(this).val());
		}
	});

	swal({
	    title: "¿Estas seguro de esta solicitud?",
	    text: "¿Deseas marcar estos recibos como pagos?",
	    type: "warning",
	    showCancelButton: true,
	    confirmButtonClass: "btn-danger",
	    cancelButtonText:"No, cancelar",
	    confirmButtonText: "Si, continuar",
	    closeOnConfirm: false
	},
	function(){
		$(".body-loading").show();
		$.post(copy_js.base_url+'receipts/change_states', { receipts: RECEIPTS }, function(data, textStatus, xhr) {
			$("#btn_find_adviser").trigger('click');
		});
	});

});

validateChecks();