$("body").on( "click", ".requestEditProduct", function(event) {
    event.preventDefault();
    var id        = $(this).data('id');
    swal({
        title: "¿Estas seguro de solicitar la edición de este producto?",
        text: "Por favor ingresa la razón de solicitar la edición para el producto",
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
            message_alert("Por favor ingresa la razón de solicitar el cambio de costo para el producto","error");
            return false;
        }
        var razon = inputValue;
        
        $.post(copy_js.base_url+'Products/changeProduct',{id,razon}, function(result){
            message_alert("Solicitud realizada correctamente a las áreas encargadas","Bien");
        });
    });
});