TOTAL_NUMBER = 0;


$("body").on("click",".approveAndSend",function(event) {
    var id = $(this).data("id");

    var tab = 0;
    $(".navLinkData").each(function(index, el) {
        if($(this).hasClass('active')){
            tab = $(this).data("tab")
        }
    });
    var url = actual_url2+"tab="+tab;

    if(roleGerente == "1"){
        $("#modalFlujoCotizacion").modal("hide");
        swal({
            title: "¿Deseas aprobar la cancelación y cerrar el flujo?",
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
                $.post(copy_js.base_url+'flow_stages/approve_cancel',{id:id}, function(result){
                    location.href = url;
                });
            }        
        });
    }else{

        $(".body-loading").show(); 
        $.post(copy_js.base_url+'flow_stages/approve_cancel',{id:id}, function(result){

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
            $.post(copy_js.base_url+'flow_stages/approve_cancel',{id:idQt}, function(result){TOTAL_NUMBER++;});
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
        title: "¿Estas seguro de rechazar la cancelación?",
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
                $.post(copy_js.base_url+'flow_stages/reject_cancel',{id:idQt,razon}, function(result){ TOTAL_NUMBER++; });
            }
            message_alert("Rechazo enviado correctamente","Bien");
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
        title: "¿Estas seguro de rechazar la cancelación?",
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
        $.post(copy_js.base_url+'flow_stages/reject_cancel',{id,razon}, function(result){
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