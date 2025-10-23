var optionsDp = {
    messages: {
        'default': 'Seleccione o arrastre el archivo',
        'replace': 'Seleccione o arrastre el archivo',
        'remove':  'Remover',
        'error':   'Error al cargar el archivo'
    },
    error: {
        'fileSize': 'El archivo supera el tamaño permitido de: ({{ value }}).',
        'fileExtension': 'El formato del archivo seleccionado no es permitido (Permitidos: {{ value }} ).'
    }
};

if ($("#ShippingDocument").length) {
    $("#ShippingDocument").dropify(optionsDp); 
}
$("#ShippingRequestType").change(function(event) {
    validateTypeShipping();
});

function validateTypeShipping(){
    var typeShipping = $("#ShippingRequestType").val();

    console.log(typeShipping)

    if (typeShipping == "" || typeShipping == "2" || typeShipping == "0") {
       
        $("#ShippingConveyorId").attr('required','required');
        $("#ShippingDocument").attr('required','required');
        $(".direccionesCliente").attr('required','required');
        $("#ShippingRut").removeAttr('required');
    }else {

        if (typeShipping == "3") {
            $("#ShippingEmailEnvoice").removeAttr('required');
            $("#ShippingRut").removeAttr('required');
        }else{
             $("#ShippingRut").removeAttr('required');
            $("#ShippingEmailEnvoice").attr('required','required');
        }

        $("#ShippingConveyorId").removeAttr('required');
        $("#ShippingDocument").removeAttr('required');
        $("#ShippingGuide").removeAttr('required');
        $(".direccionesCliente").removeAttr('required');
    }
}
$('#ShippingCopiasEmail').tagsinput();

validateTypeShipping();

$("#ShippingAddForm").submit(function(event) {

    var typeShipping = $("#ShippingRequestType").val();
    if ( (typeShipping == "" || typeShipping == "2" || typeShipping == "0") && !$(".direccionesCliente").length ) {
        message_alert("Se debe crear una dirección asociada al cliente.","error");
        event.preventDefault();
        return false;
    }
});


$(".btnChangeState").click(function(event) {
    event.preventDefault();

    $.get($(this).attr("href"), {}, function(data, textStatus, xhr) {
        $("#cuerpoDespachoNuevo").html(data);
        $("#cambioEstado").modal("show");
        if ($("#ShippingDocument").length) {
            $("#ShippingDocument").dropify(optionsDp); 
        }
        if ($("#ShippingBillFile").length) {
            $("#ShippingBillFile").dropify(optionsDp); 
            $("#ShippingBillValid").val("");
        }
        if ($("#ShippingRemision").length) {
            $("#ShippingRemision").dropify(optionsDp); 
        }
        $("#ShippingChangeForm").parsley();
        if ($("body").find("#ShippingRequestShipping").length) {

        }else{
            validateTypeShipping(); 
        }
    });

});



$("body").on('click', '.validateWOInfo', function(event) {
    event.preventDefault();
    var number = $("#ShippingBillCode").val();
    var prefijo = $("#ShippingBillPrefijo").val();

    if (number == "" || prefijo == "") {
         message_alert("El código y el prefijo son requeridos","error");
    }else{
        $("#ShippingBillValid").val("");
        $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/get_document",
            type: 'post',
            data: {number,prefijo,nuevo: 1},
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            $("#valdiateWoFact").html(response);

            if ($("#existeInfo").length) {
                $("#ShippingBillValid").val("1");
                $("#stateValidate").html("Validada");
                $("#stateValidate").addClass("text-success");
            }else{
                $("#stateValidate").removeClass("text-success");
                $("#stateValidate").html("Sin validar");
                $("#ShippingBillValid").val("");
            }

        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
        
    }

});

$(".crearDespachoBtn").click(function(event) {
    event.preventDefault();
    $("#flowsForShipping").val('');
    var options = {
        dropdownParent: $("#flujoModalAddNuevoDespacho"),
        placeholder: "Buscar flujo",
        minimumInputLength: 4,
        multiple: false,
        allowClear: true,
        language: "es",
        ajax: {
            async: false,
            // url: copy_js.base_url+"prospective_users/get_user",
            url: copy_js.base_url+"prospective_users/get_flujo/1",
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
              };
            },
        }
    };

    var $elementData = $("body").find("#flowsForShipping");

    $elementData.select2(options)

    $("#flujoModalAddNuevoDespacho").modal("show");
});

$("body").on('click', '#btnCrearSolicitud', function(event) {
    event.preventDefault();
    var flow = $("#flowsForShipping").val();
    if (flow == "") {
        message_alert("Por favor busque y seleccione un flujo",'error');
    }else{
        location.href = copy_js.base_url + 'shippings/add/' + flow
    }
});

$("#ShippingRequestType").change(function(event) {
    validateRequestTypeShipping();
});

function validateRequestTypeShipping(){
    var valor = $("#ShippingRequestType").val();
    var valorForShipping = 'dataShippingproductos';
    var valorForEnvoice  = 'dataShippingproductos_factura';

    if (valor == "0") {
        $(".productsEnvioCheck").attr('required','required');
        $(".productsEnvioCheck").attr('data-parsley-mincheck',1);
        $(".productsEnvioCheck").attr('data-parsley-multiple',valorForShipping);
        $(".direccionesCliente").attr('required','required');

        $(".productsFacturaCheck").removeAttr('required');
        $("#ShippingEmailEnvoice").removeAttr('required');
        $(".productsFacturaCheck").removeAttr('data-parsley-mincheck');
        $(".productsFacturaCheck").removeAttr('data-parsley-multiple');
    }else if(valor == "1"){
        $("#ShippingEmailEnvoice").attr('required','required');
        $(".productsFacturaCheck").attr('required','required');
        $(".productsFacturaCheck").attr('data-parsley-mincheck',1);
        $(".productsFacturaCheck").attr('data-parsley-multiple',valorForEnvoice);

        $(".direccionesCliente").removeAttr('required');
        $(".productsEnvioCheck").removeAttr('required');
        $("#ShippingDocument").removeAttr('required');
        $(".productsEnvioCheck").removeAttr('data-parsley-mincheck');
        $(".productsEnvioCheck").removeAttr('data-parsley-multiple');
    }else {

        if (valor == "3") {
            $("#ShippingEmailEnvoice").removeAttr('required');
            $("#ShippingRut").removeAttr('required');
            $(".direccionesCliente").removeAttr('required');
            $(".productsFacturaCheck").removeAttr('required');
            $("#ShippingEmailEnvoice").removeAttr('required');
            $(".productsFacturaCheck").removeAttr('data-parsley-mincheck');
            $(".productsFacturaCheck").removeAttr('data-parsley-multiple');
        }else{
            $("#ShippingRut").removeAttr('required');
            // $("#ShippingRut").attr('required','required');
            $("#ShippingEmailEnvoice").attr('required','required');
            $(".direccionesCliente").attr('required','required');
            $(".productsFacturaCheck").attr('required','required');
            $(".productsFacturaCheck").attr('data-parsley-mincheck',1);
            $(".productsFacturaCheck").attr('data-parsley-multiple',valorForEnvoice);
        }

        $(".productsEnvioCheck").attr('required','required');
        $(".productsEnvioCheck").attr('data-parsley-mincheck',1);
        $(".productsEnvioCheck").attr('data-parsley-multiple',valorForShipping);
       
    }
}

if ($("#ShippingRut").length) {
    $("#ShippingRut").dropify(optionsDp); 
}
if ($("#ShippingOrden").length) {
    $("#ShippingOrden").dropify(optionsDp); 
}

$(".editNote").click(function(event) {
    event.preventDefault();
    
    $.get($(this).attr("href"), function(data) {
        $("#bodyNotices").html(data);
        $("#modalNotices").modal("show");
    });
});


$("body").on('submit', '#ShippingNoteLogisticForm', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
        location.reload();
    });
});

$("body").on('click', '.request_envoice', function(event) {
    event.preventDefault();

    const url_env = $(this).attr("href");

    $.get(url_env, function(data) {
        $("#bodyFacturacion").html(data);
        $("#ShippingDocumentAdd").dropify(optionsDp)
        $("#modalFacturacion").modal("show");
    });

    // swal({
    //     title: "¿Estas seguro procesar el cambio?",
    //     text: "¿Estás seguro de solicitar facturación de esta orden que ya fue despachada?",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonClass: "btn-danger",
    //     cancelButtonText:"No, cancelar",
    //     confirmButtonText: "Si, solicitar facturación",
    //     closeOnConfirm: false
    // },
    // function(){
    //     $(".body-loading").show();
    //     $.post(url_env, {}, function(data, textStatus, xhr) {
    //         $(".body-loading").hide();
    //         location.reload();
    //     });
    // });
});


validateRequestTypeShipping();

$("body").on('click', '#btnProcesarCambioDolar', function(event) {
    event.preventDefault();
    var trm         = $("#trmDiaCustom").val() != '' ? $("#trmDiaCustom").val() : $("#trmDia").val();
    var flujo       = $(this).data("flujo");
    var quotation   = $(this).data("quotation");
    if(trm == ""){
        message_alert("Por favor selecciona el TRM del cambio.","error");
    }else{
        $(".body-loading").show();
        $.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
            trm,flujo,quotation, calculate: 1,new_calculate: 2,noSave: 1
        }, function(data, textStatus, xhr) {
            $(".body-loading").hide();
            swal({
                title: "¿Estas seguro procesar el cambio?",
                text: "¿El costo final de la cotización será: $"+ data +" COP, deseas continuar ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonText:"No, corregir TRM",
                confirmButtonText: "Si, realizar cambio",
                closeOnConfirm: false
            },
            function(){
                $(".body-loading").show();
                $.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
                    trm,flujo,quotation, calculate: 0,new_calculate:1,warehose:1
                }, function(data, textStatus, xhr) {
                    $(".body-loading").hide();
                    location.reload();
                });
            });
        });
    }
});

if ( $("#cliente_id").length ) {
    setCustomerSelect2("#cliente_id");
}