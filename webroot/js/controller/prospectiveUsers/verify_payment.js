$("body").on( "click", ".comprobanteimgTd a", function() {
    var paratesoreria = $(this).attr("datacomprobantet");
    $(".img-product").attr('src',paratesoreria);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

$("body").on( "click", ".comprobanteimgTd img", function() {
    var paratesoreria = $(this).attr("datacomprobantet");
    $(".img-product").attr('src',paratesoreria);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

$("body").on('click', '.cierra2', function(event) {
    event.preventDefault();
    $(".popup2,.fondo").hide()
});

$("body").on( "click", ".confirm_payment_flujo", function() {
	var flowStages_id        = $(this).data('uid');
    var user_id              = $(this).data('user_id');
    var flujo_id             = $(this).data('flujo_id');
    var discount             = $(this).data('discount');
    var classDatfono         = $(this).hasClass('datafonoClass');
	swal({
        title: "¿Estas seguro de aprobar el pago?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){

        if(!classDatfono){
            $.post(copy_js.base_url+'FlowStages/changeStatePagadoTrue',{flowStages_id:flowStages_id,user_id:user_id,flujo_id:flujo_id,discount:0}, function(result){
                location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
            });
        }else{
            // //////////////

            swal({
                title: "Dinero a descontar de comisión",
                text: "Por favor ingresa el monto de dinero a descontar de la comisión del asesor por no cobrar al cliente la comisión bancaria",
                type: "input",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonText:"Cancelar",
                confirmButtonText: "Aceptar",
                closeOnConfirm: false,
                inputPlaceholder: "Monto a descontar",
                inputType: 'number',
                inputValue: discount
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    message_alert("Por favor el valor a descontar","error");
                    return false;
                }
                if (inputValue < 0) {
                    message_alert("El valor minimo a descontar es 0","error");
                    return false;
                }
                $(".body-loading").show();
                $.post(copy_js.base_url+'FlowStages/changeStatePagadoTrue',{flowStages_id:flowStages_id,user_id:user_id,flujo_id:flujo_id,discount:inputValue}, function(result){
                    location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
                });
            });

            // //////////////
        }

        
    });
});

$(".confirm_reject").click(function(event) {
    event.preventDefault();
    const type = $(this).data("state");
    const id = $(this).data("uid");

    if(type == "1"){
        swal({
            title: "¿Estas seguro de aprobar la cancelación?",
            text: "¿Deseas continuar con la acción?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: false
        },
        function(){
            $(".body-loading").show();
             $.post(copy_js.base_url+'prospective_users/aprove_reject_flow',{id,type,note:null}, function(result){
                location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
            });
        });
    }else{
        swal({
            title: "¿Estas seguro de no aprobar el rechazo?",
            text: "¿Por favor ingresa la descripción del motivo por el cual no apruebas el rechazo?",
            type: "input",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: false,
            inputPlaceholder: "Descripción"
        }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                message_alert("Por favor ingresa el motivo por el cual no apruebas el rechazo","error");
                return false;
            }
            $(".body-loading").show();
            $.post(copy_js.base_url+'prospective_users/aprove_reject_flow',{id,type,note:inputValue}, function(result){
                location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
            });
        });
    }

});

$("body").on( "click", ".not_confirm_payment_flujo", function() {
    $("#modalClienteWo").modal("hide");
	var flowStages_id        = $(this).data('uid');
    var user_id              = $(this).data('user_id');
    var flujo_id             = $(this).data('flujo_id');
    


	swal({
        title: "¿Estas seguro de no aprobar el pago?",
        text: "¿Por favor ingresa la descripción del motivo por el cual no apruebas el pago?",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Descripción"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a cancelar el flujo","error");
            return false;
        }
        

        $.post(copy_js.base_url+'FlowStages/changeStatePagadoFalse',{flowStages_id:flowStages_id,user_id:user_id,flujo_id:flujo_id,rason:inputValue}, function(result){
            location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
        });
    });
});


$("body").on('click', '.validarFacturaWo', function(event) {
    event.preventDefault();
    var number = $("#ProspectiveUserBillCode").val();
    var prefijo = $("#ProspectiveUserBillPrefijo").val();

    if (number == "" || prefijo == "") {
         message_alert("El código y el prefijo son requeridos","error");
    }else{
        $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/get_document",
            type: 'post',
            data: {number,prefijo},
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            $(".datosWo").html(response)
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
        
    }

});


$("body").on('click', '.validateFinal', function(event) {
    event.preventDefault();
    $("#ProspectiveUserBillCode").attr("readonly","readonly");
    $("#ProspectiveUserBillPrefijo").attr("readonly","readonly");
    var number = $("#ProspectiveUserBillCode").val();
    var prefijo = $("#ProspectiveUserBillPrefijo").val();
    $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/save_document",
            type: 'post',
            data: $("#form_bill").serialize(),
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            location.reload();
            // $(".datosWo").html(response)
        })
        .fail(function() {
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
});

$('body').on("click", ".btn_confirm_entrega", function(event) {
    var flujo_id        = $(this).data('uid');
    swal({
        title: "¿Estas seguro de confirmar la entrega del pedido en su totalidad?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true
    },
    function(){
        $.post(copy_js.base_url+copy_js.controller+'/updateStateFinishProspective',{flujo_id:flujo_id}, function(result){

            if (result == true) {
                location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
            } else {
                message_alert("Por favor valida si  todos los productos del pedido han sido enviados","error");
            }
        });
    });
});

$(".find_payments_flujo").click(function() {
    var flujo_id            = $(this).data('uid');
    $.post(copy_js.base_url+'Payments/payment_history',{flujo_id:flujo_id}, function(result){
        $('#modal_information_body').html(result);
        $('#modal_information_label').text('Datos de los pagos realizados');
        $('#modal_information').modal('show');
    });
});

$(".confirm_payment_abono").click(function() {
    var etapa_id        = $(this).data('uid');
    var flujo_id        = $(this).data('flujo');
    var user_id         = $(this).data('user');
    swal({
        title: "¿Estas seguro que el cliente ya finalizo los pagos?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+'FlowStages/updatePaymentAbonoForTotalTrue',{etapa_id:etapa_id,flujo_id:flujo_id,user_id:user_id}, function(result){
            location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
        });
    });
});

$('.not_confirm_payment_abono').click(function(){
    var etapa_id        = $(this).data('uid');
    var flujo_id        = $(this).data('flujo');
    var user_id         = $(this).data('user');
    swal({
        title: "¿Estas seguro que el cliente no a terminado de realizar todos los pagos?",
        text: "¿Por favor ingresa la descripción del motivo por el cual no apruebas el pago?",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a cancelar el flujo","error");
            return false;
        }
        $.post(copy_js.base_url+'FlowStages/updatePaymentAbonoForTotalFalse',{etapa_id:etapa_id,flujo_id:flujo_id,user_id:user_id,rason:inputValue}, function(result){
            location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
        });
    });
});

$(".btn_buscar").click(function() {
    buscadorFiltro();
});

// $('body').bind('keypress', function(e) {
//     if(e.keyCode == 13){
//         buscadorFiltro();
//     }
// });

function buscadorFiltro(){
    var texto                       = $('#txt_buscador').val();
    var hrefURL                     = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
    var hrefFinal                   = hrefURL+"?q="+texto;
    location.href                   = hrefFinal;
}

$("#texto_busqueda").click(function() {
    location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});


$("body").on('click', '.info_bill', function(event) {
    event.preventDefault();
    id = $(this).data("uid");    
    bill = $(this).data("bill");
    view =  typeof $(this).data("view") != "undefined" ? 1 : null;
    $.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information', {id, view, bill}, function(data, textStatus, xhr) {
        $("#cuerpoBill").html(data);
        $("#modalBillInformation").modal("show")
    });
});


$("body").on('submit', '#form_bill', function(event) {
    event.preventDefault();
    return false;
    $(".body-loading").show();
    var formData            = new FormData($('#form_bill')[0]);

    marcadoMarcar = 0;
    totalProductos = 0;

    $("body").find('.bodega_inventario').each(function(index, el) {
        if($(this).prop('checked')){
            marcadoMarcar++;
        }
    });

    $("body").find('.cantidadProducto').each(function(index, el) {
        totalProductos++;
    });

    if(marcadoMarcar == 0 && $(".contentchecksend").length){
        message_alert("No es posible procesar la información, ya que existen productos sin inventario total para descontar.","error");
        $(".body-loading").hide();
        return false;
    }

    $.ajax({
        type: 'POST',
        url: copy_js.base_url+'ProspectiveUsers/saveBillInformation',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            if (result > 10) {
                location.reload();
                // window.open(copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?q='+result);
            } else {
                $(".body-loading").hide();
                $("#btnFormSubmit").show();
                validate_documento_pdf_message(result);
            }
        }
    });
});

/******** Descuento factura *****/

$("body").on('click', '.bodega_inventario', function(event) {
    id = $(this).data("id");
    if($(this).hasClass('bodega_med')){
        var divId = ".deliveryDataBodega_"+id+" .bodega_bog";
        $("body").find(divId).prop('checked', false);
    }else{
        var divId = ".deliveryDataBodega_"+id+" .bodega_med";
        $("body").find(divId).prop('checked', false);
    }

});


$("body").on('click', '.selectBog', function(event) {
    $("body").find(".bodega_bog").each(function(index, el) {
        if (!$(this).attr("disabled")) {
            $(this).trigger('click')
        }
    });
});

$("body").on('click', '.selectMed', function(event) {
    $("body").find(".bodega_med").each(function(index, el) {
        if (!$(this).attr("disabled")) {
            $(this).trigger('click')
        }
    });
});

$("body").on('click', '.verifyWo', function(event) {
    event.preventDefault();
    var nit = $(this).data("nit");
    $("#nitCliente").val(nit);
    $("#bodyClienteWo").html("");
    $(".btnSearchCustomer").attr("data-flujo",$(this).data("flujo"));
    $(".btnSearchCustomer").attr("data-uid",$(this).data("uid"));
    $(".btnSearchCustomer").attr("data-user_id",$(this).data("user_id"));
    $("#modalClienteWo").modal("show");

    setTimeout(function() {
        $(".btnSearchCustomer").trigger('click')
    }, 500);
});

$("body").on('click', '.btnSearchCustomer', function(event) {
    event.preventDefault();
    var dni = $("#nitCliente").val();
    var flujo = $(this).attr("data-flujo");
    var flow = $(this).attr("data-uid");
    var user = $(this).attr("data-user_id");
    var is_validate_adm = $("#is_validate_adm").length ? 1 : 0;
    if (dni == "") {
        message_alert("No es posible procesar la información, el Nit es requerido.","error");
    }else{
        $.post(copy_js.base_url+'ProspectiveUsers'+'/verify_wo', {dni,flujo,flow,user,is_validate_adm}, function(data, textStatus, xhr) {
            $("#bodyClienteWo").html(data);
        });
    }
});

$("body").on( "click", ".Comprobanteacep", function() {
    var comprobante = $(this).find("img").attr("datacomprobante");
    $("#modalFlujo").modal("hide");
    $(".img-product").attr('src',comprobante);
    $(".fondo").fadeIn();
    $(".popup2").fadeIn();
});

$("body").on('click', '.listFact', function(event) {
    event.preventDefault();
    var id = $(this).data("id");
    $.post(copy_js.base_url+'ProspectiveUsers'+'/bill_information_list', {id}, function(data, textStatus, xhr) {
        $("#cuerpoBillList").html(data);
        $("#modalBillList").modal("show")
    });
});

$("body").on('click', '.mostradDatos', function(event) {
    event.preventDefault();
    var idMostrar = "#"+$(this).data("id");
    $(idMostrar).toggle();
});

$("body").on('click', '.ingresoPagoTienda', function(event) {
    event.preventDefault();
    $("#ProspectiveUserFlujoId").val($(this).data("id"))
    $("#modalIngresoPago").modal("show")
});
/*****Cambio trm ***/

$("body").on('change', '#ProspectiveUserChangeTrm', function(event) {
    var valorTrm = $(this).val();
    if(valorTrm == "0"){
        $(".cambioTrm").hide();
    }else{
        $(".cambioTrm").show();        
    }
});

$("body").on('click', '#btnProcesarCambioDolarTrm', function(event) {
    event.preventDefault();
    var trm         = $("#trmDiaCambio").val();
    var flujo       = $(this).data("flujo");
    var quotation   = $(this).data("quotation");
    if(trm == ""){
        message_alert("Por favor selecciona el TRM del cambio.","error");
    }else{
        $(".body-loading").show();
        $.post(copy_js.base_url+'ProspectiveUsers/change_trm_quotation', {
            trm,flujo,quotation, calculate: 2
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
                    trm,flujo,quotation, calculate: 0, new_calculate: 1
                }, function(data, textStatus, xhr) {
                    $(".body-loading").hide();
                    
                });
            });
        });
    }
});


$("body").on('click', '.factLater', function(event) {
    var valor = $(this).val();
    var checkValue = "input.checkedData_"+valor;
    console.log(valor)
    console.log(checkValue)
    if($(this).is(":checked")){
        $("body").find(checkValue).prop('checked', false)
        $("body").find(checkValue).attr('disabled',"disabled");
        console.log("chekc")
    }else{
        console.log("noCheck")
        $("body").find(checkValue).prop('checked', true);
        $("body").find(checkValue).removeAttr('disabled');
    }
});

var submitdata = {}

$('.cambioCostoDataUsd').editable(copy_js.base_url+copy_js.controller+'/change_valor', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 0,
    max: 100,
    onedit : function() { console.log('If I return false edition will be canceled'); return true;},
    before : function() {
        var element = this;        
        setTimeout(function() {
            element.submitdata.id = $("body").find("#formularioQueCambia").parent("div").data("id");
            $("body").find("#formularioQueCambia").children('input').val($("body").find("#formularioQueCambia").parent("div").data("price"));
            $("body").find("#formularioQueCambia").children('input').attr("step", "any")
        }, 100);

    },
    callback : function(result, settings, submitdata) {
        location.reload();
    },
    cancel : 'Cancelar',
    cssclass : 'custom-class form-control',
    cancelcssclass : 'btn btn-danger',
    submitcssclass : 'btn btn-success',
    maxlength : 200,
    // select all text
    select : false,
    label : 'Quitar porcentaje de comisión',
    onreset : function() { console.log('Triggered before reset') },
    onsubmit : function() { 
        var value = $(this[0]).children('input').val();
        if(value != ""){
            $(".body-loading").show();
        }else{
            return false;
        }
    },
    showfn : function(elem) { elem.fadeIn('slow') },
    submit : 'Guardar',
    submitdata : submitdata,
    tooltip : "Click para editar",
    placeholder : "Valor",
    width : 160,
    formid: 'formularioQueCambia'
});