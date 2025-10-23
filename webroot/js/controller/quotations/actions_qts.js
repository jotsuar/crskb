TOTAL_NUMBER = 0;


$("body").on('click', '.getQuotationId2', function(event) {
    event.preventDefault();

    userId             = $(this).data("user");
    var claseAproveAll = ".approveAndSendUser"+userId;
    var claseRejectAll = ".rejectUser"+userId;
    $("#botonesAprueba").html("");

    quotation = $(this).data("quotation");
    $.post(copy_js.base_url+'Quotations/view/'+quotation, {modal:1}, function(data, textStatus, xhr) {
        $("#cuerpoModalFlujoCotizacion").html(data);
        $("#modalFlujoCotizacion").modal("show");
        $(claseAproveAll).removeClass('d-none');
        $(claseRejectAll).removeClass('d-none');
        $(claseRejectAll).clone().appendTo('#botonesAprueba');
        $(claseAproveAll).clone().appendTo('#botonesAprueba');
        var claseReject2 = "#botonesAprueba "+claseRejectAll;
        $(claseReject2).addClass('mr-3');
    });
});

$("body").on("click",".approveAndSend",function(event) {
    var id = $(this).data("id");

    var tab = 0;
    $(".navLinkData").each(function(index, el) {
        if($(this).hasClass('active')){
            tab = $(this).data("tab")
        }
    });
    var url = actual_url2+"tab="+tab;

    if(roleGerente == "0"){
        $("#modalFlujoCotizacion").modal("hide");
        swal({
            title: "¿Deseas aprobar la cotización y continuar el flujo?",
            text: "¿Deseas continuar con la acción?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            focusConfirm: true,
            useRejections: true
        },
        function(val2){
            if(val2){
                $(".body-loading").show(); 
                $.post(copy_js.base_url+'flow_stages/approve_qt',{id:id}, function(result){
                    location.href = url;
                });
            }        
        });
    }else{

        $(".body-loading").show(); 
        $.post(copy_js.base_url+'flow_stages/approve_qt',{id:id}, function(result){

            setTimeout(function() {
                location.href = url;
            }, 2000);

        });        
    }
});

$("body").on('click', '.approveAndSendAll', function(event) {
    event.preventDefault();
    var userId = $(this).data("user");
    var claseAproveAll = ".approveAllP"+userId;

    var totalAp = 0;

    $(claseAproveAll).each(function(index, el) {
        if (!$(this).hasClass('d-none')) {            
            var idQt = $(this).data("id");
            $(".body-loading").show(); 
            totalAp++;
            $.post(copy_js.base_url+'flow_stages/approve_qt',{id:idQt}, function(result){TOTAL_NUMBER++;});
        }
    });
    if(TOTAL_NUMBER <= 0){
        TOTAL_NUMBER = totalAp*1000;
    }else{
        TOTAL_NUMBER = TOTAL_NUMBER+1500;
    }

    console.log(TOTAL_NUMBER);

    setTimeout(function() {
        location.reload();
    }, TOTAL_NUMBER);
});

$("body").on('click', '.rejectAll', function(event) {
    event.preventDefault();
    var userId = $(this).data("user");
    var claseAproveAll = ".rejectAllP"+userId;

    swal({
        title: "¿Estas seguro de rechazar la cotización?",
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
        var totalAp = 0;

         $(claseAproveAll).each(function(index, el) {
            if (!$(this).hasClass('d-none')) {               
                var idQt = $(this).data("id");
                $(".body-loading").show(); 
                totalAp++;
                $.post(copy_js.base_url+'flow_stages/reject_qt',{id:idQt,razon}, function(result){ TOTAL_NUMBER++; });
            }
            message_alert("Solicitud rechazo enviado correctamente","Bien");
        });

        if(TOTAL_NUMBER <= 0){
            TOTAL_NUMBER = totalAp+1000;
        }else{
            TOTAL_NUMBER = TOTAL_NUMBER+1500;
        }

        console.log(TOTAL_NUMBER);

        setTimeout(function() {
            location.reload();
        }, TOTAL_NUMBER);

    });

   
});


$("body").on( "click", ".reject", function(event) {
    $("#modalFlujoCotizacion").modal("hide");
    event.preventDefault();
    var id        = $(this).data('id');
    swal({
        title: "¿Estas seguro de rechazar la cotización?",
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
        $(".body-loading").show();         
        var tab = 0;
        $(".navLinkData").each(function(index, el) {
            if($(this).hasClass('active')){
                tab = $(this).data("tab")
            }
        });
        var url = actual_url2+"tab="+tab;
        $.post(copy_js.base_url+'flow_stages/reject_qt',{id,razon}, function(result){
            message_alert("Solicitud rechazo enviado correctamente","Bien");
            setTimeout(function() {
                location.href = url;
            }, 2000);
        });
    });

    
});

$(".trInfoApprove").on('click', function(event) {
    $(".trInfoApprove").each(function(index, el) {
        $(this).removeClass('bgInfo');
    });
    $(this).addClass('bgInfo')
});

$('[data-toggle="popover"]').popover({html:true});


var submitdata = {}
$('.cambioCostoDataUsd').editable(copy_js.base_url+copy_js.controller+'/change_comision', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 0,
    max: 200,
    onedit : function() { console.log('If I return false edition will be canceled'); return true;},
    before : function() {
        var element = this;        
        setTimeout(function() {
            element.submitdata.type = $("body").find("#formularioQueCambia").parent("div").data("type");
            element.submitdata.id = $("body").find("#formularioQueCambia").parent("div").data("id");
            element.submitdata.currency = $("body").find("#formularioQueCambia").parent("div").data("currency");
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
    label : 'Cambio de porcentaje',
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
    placeholder : "Ingrese el valor",
    width : 160,
    formid: 'formularioQueCambia'
});
