$(".btn_servicio_flujo").click(function(e) {
    var service_id      = $(this).data('uid');
    swal({
        title: "¿Estas seguro que deseas generar un flujo?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
    	$.post(copy_js.base_url+copy_js.controller+'/saveFlujoForService',{service_id:service_id}, function(result){
    		location.href = copy_js.base_url+copy_js.controller+'/flujos';
		});
    });
});

$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
        buscadorFiltro();
    }
});

$(".btn_buscar").click(function() {
    buscadorFiltro();
});

function buscadorFiltro(){
    var texto                       = $('#txt_buscador').val();
    var hrefURL                     = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
    var hrefFinal                   = hrefURL+"?q="+texto;
    location.href                   = hrefFinal;
}

$("#texto_busqueda").click(function() {
    location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});

$("#clientes").select2();
$("#brand").select2();
$("#asesor").select2();

$("#muestra").click(function(event) {
    validateShowFields();
});

function validateShowFields(){
    var mod = $("#muestra").data("mod");
    if (mod == "0") {
        $("#muestra").children('.fa-plus').addClass('d-none');
        $("#muestra").children('.fa-minus').removeClass('d-none');
        $("#muestra").data('mod', '1');
        $("#principalBusqueda").show();
    }else{
        $("#muestra").children('.fa-plus').removeClass('d-none');
        $("#muestra").children('.fa-minus').addClass('d-none');
        $("#muestra").data('mod', '0');
        $("#principalBusqueda").hide();
    }
}

$("body").on('click', '.sendMessageCustomer', function(event) {
    event.preventDefault();
    var url = $(this).attr("href");
    $.get(url, function(data) {
        $("#cuerpoEnvioSend").html(data);
        $('#TechnicalServiceMessage').summernote({
            height: 200
        });
        $("#modalEnvio").modal("show");
        $("#form_serviceSend").parsley();
    });
});

$("body").on('submit', '#form_serviceSend', function(event) {
    event.preventDefault();
    var urlSend = $(this).attr("action");
    $.post(urlSend, $(this).serialize(), function(data, textStatus, xhr) {
        message_alert("Mensaje enviado al cliente","Bien");
        $("#cuerpoEnvioSend").html("");
        $("#modalEnvio").modal("hide");
    });
});

$(".listBinnacle").click(function(e) {
    e.preventDefault();
    $.get($(this).attr("href"), function(response) {
        $("#cuerpoListBit").html(response);
        $("#modalListVitacora").modal("show");
    });
});