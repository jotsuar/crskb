$("body").on('click', '.generateTransit', function(event) {
    event.preventDefault();
    var id = $(this).data("id");

    swal({
        title: "¿Estas seguro de indicar que ya se entrego/envio el producto?",
        text: "Por favor ingresa una nota de entrega",
        type: "input",
        input: "textarea",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Nota de entrega"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la nota de entrega","error");
            return false;
        }
        var razon = inputValue;
        $(".body-loading").show();        
        $.post(copy_js.base_url+'transits/change',{id:id,razon}, function(result){ 
            location.reload();
        });

    });

   
});