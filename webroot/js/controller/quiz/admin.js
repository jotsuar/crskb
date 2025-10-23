$("body").on( "click", ".deleteAnswer", function(event) {
    event.preventDefault();
    var deleteAtr = $(this).parent("li");
    var url = $(this).attr("href");
    swal({
        title: "¿Estas seguro borrar esta respuesta?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true
    },
    function(){
        $.post(url,{}, function(result){
            deleteAtr.remove();
        });
    });
});


$("body").on( "click", ".deleteQuestion", function(event) {
    event.preventDefault();
    var deleteAtr = "#"+$(this).data("id");
    deleteAtr = $(deleteAtr);
    var url = $(this).attr("href");
    swal({
        title: "¿Estas seguro borrar esta pregunta?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true
    },
    function(){
        $.post(url,{}, function(result){
            deleteAtr.remove();
        });
    });
});


$("body").on( "click", ".editQuestion", function(event) {
    event.preventDefault();
    var url = $(this).attr("href");
    $.get(url,{}, function(result){
        $("#modalEditarPregunta").modal("show");
        $("#cuerpoPregunta").html(result)
    });
});


$("body").on( "click", ".editAnswer", function(event) {
    event.preventDefault();
    var url = $(this).attr("href");
    $.get(url,{}, function(result){
        $("#modalEditarRespuesta").modal("show");
        $("#cuerpoRespuesta").html(result)
    });
});

