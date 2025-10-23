$(".solicita").click(function(event) {
    event.preventDefault();

    var valor        = $(this).data('total');
    var url_ls       = $(this).attr('href');
    
    swal({
        title: "Solicitud manual de unidades por Reorder",
        text: "Por favor ingresa la cantidad a solicitar",
        type: "input",
        inputType: "number",
        inputValue: valor,
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "Descripción de la razón"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la cantidad a solicitar","error");
            return false;
        }
        var total = inputValue;
        
        $.post(url_ls,{total}, function(result){
            message_alert("Solicitud rechazo enviado correctamente","Bien");
            // location.reload()
        });
    });

});


function load_data_search(){
    $.post(copy_js.base_url+'Products/paintData',{remarketing: 2,referencias}, function(result){
        var dataController = $.parseJSON(result);
        $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            maxItem: 10,
            order: "asc",
            hint: true,
            maxItemPerGroup: 5,
            backdrop: {
                "background-color": "#fff"
            },
            group: {
                template: "{{group}}"
            },
            emptyTemplate: 'No se encuentran resultados: "{{query}}"',
            source: {
                Nombre: {
                    display: "name",
                    data: dataController
                },
                Parte: {
                    display:"part_number",
                    data: dataController
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    
                    var url = rootUrl+"products/edit/" + item.id + "/1/1"
                    window.open(url, '_blank').focus();

                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                }
            },
            debug: true
        });
    });
}

$('.test-popup-link').magnificPopup({
  type: 'image'
  // other options
});

load_data_search();