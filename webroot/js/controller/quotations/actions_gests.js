TOTAL_NUMBER = 0;


$("body").on("click",".approveAndSend",function(event) {
    var id = $(this).data("id");
    console.log(id)

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
            title: "¿Deseas devolverle el flujo al asesor?",
            text:  "¿Deseas continuar con la acción?",
            type:  "warning",
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
                $.post(copy_js.base_url+'prospective_users/reloadFlujo',{id:id, restore: 1}, function(result){
                    location.href = url;
                });
            }        
        });
    }else{

        $(".body-loading").show(); 
        $.post(copy_js.base_url+'prospective_users/reloadFlujo',{id:id, restore: 1}, function(result){

            setTimeout(function() {
                location.href = url;
            }, 2000);

        });        
    }
});

$("body").on("click",".reasign",function(event) {
    var id = $(this).data("id");

    $("#userIdReasign").val("");

    $("#flowReasigna").val(id);

    $("#modaReasigna").modal("show");   

});

$("body").on('click', '.reasignAll', function(event) {
    event.preventDefault();
    var userId = $(this).data("user");
    var claseAproveAll = ".reasignUser"+userId;

    var totalAp = 0;

    var FLOWS_REASIGN = [];

    console.log(claseAproveAll);

    $(claseAproveAll).each(function(index, el) {
        if (!$(this).hasClass('d-none')) {            
            var idQt = $(this).data("id");
            FLOWS_REASIGN.push(idQt);
        }
    });

     console.log(FLOWS_REASIGN);

    $("#flowReasigna").val(FLOWS_REASIGN.toString());
      $("#modaReasigna").modal("show"); 

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
            console.log(idQt)
            totalAp++;
            $.post(copy_js.base_url+'prospective_users/reloadFlujo',{id:idQt, restore: 1}, function(result){TOTAL_NUMBER++;});
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

    $(".body-loading").show();        
    var totalAp = 0;

     $(claseAproveAll).each(function(index, el) {
        if (!$(this).hasClass('d-none')) {               
            var idQt = $(this).data("id");
            console.log(idQt)
            $(".body-loading").show(); 
            totalAp++;
            $.post(copy_js.base_url+'prospective_users/cancel_finish',{id:idQt,razon}, function(result){ TOTAL_NUMBER++; });
        }
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




$("body").on( "click", ".reject", function(event) {
    $("#modalFlujoCotizacion").modal("hide");
    event.preventDefault();
    var id        = $(this).data('id');
    console.log(id)

    $(".body-loading").show();         
    var tab = 0;
    $(".navLinkData").each(function(index, el) {
        if($(this).hasClass('active')){
            tab = $(this).data("tab")
        }
    });
    var url = actual_url2+"tab="+tab;
    $.post(copy_js.base_url+'prospective_users/cancel_finish',{id}, function(result){
        setTimeout(function() {
            location.href = url;
        }, 2000);
    });


    
});

$(".trInfoApprove").on('click', function(event) {
    $(".trInfoApprove").each(function(index, el) {
        $(this).removeClass('bgInfo');
    });
    $(this).addClass('bgInfo')
});

$('[data-toggle="popover"]').popover({html:true});