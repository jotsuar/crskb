$("body").on( "click", ".classDeleteImport", function() {
    var request_id            = $(this).data('uid');
    swal({
        title: "¿Estas seguro que deseas rechazar la solicitud importación?",
        text: "¿Deseas continuar con la acción?",
        type: "input",
        customClass: "motivoEliminacion",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Motivo de rechazo de la solicitud"
    }, function (motivo) {
        if (motivo === false) return false;
        if (motivo === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a rechazar la solicitud","error");
            return false;
        }
        $.post(copy_js.base_url+'ImportRequests'+'/reject',{request_id,motivo}, function(result){
            location.reload();
        });
    });
});

$("body").on( "click", ".classAproveImport", function() {
    var request_id            = $(this).data('uid');
    swal({
        title: "¿Estas seguro que deseas aprobar la solicitud importación?",
        text: "¿Deseas continuar con la acción?",
        type: "input",
        customClass: "motivoEliminacion",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aprobar",
        closeOnConfirm: false,
        inputPlaceholder: "Nota adicional a la solicitud"
    }, function (motivo) {
        if (motivo === false) return false;
        $(".body-loading").show();
        $.post(copy_js.base_url+'ImportRequests'+'/approve',{request_id,motivo}, function(result){
            location.reload();
        });
    });
});

async function approveRejectAll(motivo,method){
    var total = $(".classAproveImport").length;
    var counter = 0;
    $(".body-loading").show();
    $(".classAproveImport").each(async function(index, el) {
        var formData = new FormData();
        var request_id = $(this).data("uid");
        formData.append('request_id',request_id);
        formData.append('motivo',motivo);
        var resultado = await fetch(copy_js.base_url+'ImportRequests'+'/'+method,{method: "POST",body: formData }).then( response => {
            counter++;
            if (counter == total) {
                location.reload()
            }
        } )
        
    });
}


$("body").on('click', '.classAproveImportAll ',  function(event) {
    event.preventDefault();
    swal({
        title: "¿Estas seguro que deseas aprobar todas las solicitudes de importación?",
        text: "¿Deseas continuar con la acción?",
        type: "input",
        customClass: "motivoEliminacion",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aprobar",
        closeOnConfirm: false,
        inputPlaceholder: "Nota adicional"
    }, function (motivo) {
        if (motivo === false) return false;
        approveRejectAll(motivo,'approve');        
    });
});

$("body").on('click', '.classDeleteImportAll', function(event) {
    event.preventDefault();
    swal({
        title: "¿Estas seguro que deseas rechazar las solicitudes importación?",
        text: "¿Deseas continuar con la acción?",
        type: "input",
        customClass: "motivoEliminacion",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Motivo de rechazo de la solicitud"
    }, function (motivo) {
        if (motivo === false) return false;
        if (motivo === "") {
            message_alert("Por favor ingresa el motivo por el cual vas a rechazar las solicitudes","error");
            return false;
        }
        approveRejectAll(motivo,'reject');
    });
});