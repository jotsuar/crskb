$("body").on('click', '.getCuentaCobro', function(event) {
	event.preventDefault();
	$.post(copy_js.base_url+'prospective_users/cuenta_cobro', {}, function(data, textStatus, xhr) {
		$("#bodyCuentaCobro").html(data);
		$("#modalCuentaCobro").modal("show")
	});
});

$("body").on('click', '.gestionarCuenta', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$.get(copy_js.base_url+'accounts/change/'+id,{}, function(result){
        $("#bodyCambioEstado").html(result);
        $("#modalCambioEstado").modal("show");

        if ($("#AccountDocument").length) {
            $("#AccountDocument").dropify(OPTIONSDP);
        }
    });
});

$("body").on('click', '.rechazarCuenta', function(event) {
	event.preventDefault();
	var id = $(this).data("id");

	swal({
        title: "¿Estas seguro de rechazar la cuenta de cobro?",
        text: "Por favor ingresa la razón del rechazo",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "Descripción de la razón"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la razón del rechazo","error");
            return false;
        }
        var razon = inputValue;
        $(".body-loading").show();
      
        var tab = 0;
        $.post(copy_js.base_url+'accounts/reject',{id,razon}, function(result){
            location.reload();
        });
    });

});