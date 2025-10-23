

$(".btn_aprobar").click(function(event) {
	var id = $(this).data("uid");

	swal({
        title: "¿Deseas aprobar la salida de inventario?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
    },
    function(val2){
    	if(val2){
    		$.post(copy_js.base_url+'inventories/approved',{id:id}, function(result){
	            location.reload();
	        });
    	}        
    });
});

$(".btn_aprobarlistado").click(function(event) {
    var id = $(this).data("uid");

    swal({
        title: "¿Deseas aprobar la salida de inventario?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
    },
    function(val2){
        if(val2){
            $(".body-loading").show(); 
            $.post(copy_js.base_url+'inventories/approved',{id:id}, function(result){
                var idTr = "tr#trMove_"+id;
                $(idTr).remove();
                message_alert("Salida aprobada correctamente.","Bien");
                $(".body-loading").hide(); 
            });
        }        
    });
});



$("body").on( "click", ".rechazo", function(event) {
    event.preventDefault();
    var id        = $(this).data('id');
    swal({
        title: "¿Estas seguro de rechazar la salida de este producto?",
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
        
        $.post(copy_js.base_url+'inventories/noAprovee',{id,razon}, function(result){
            message_alert("Solicitud rechazo enviado correctamente","Bien");
            location.reload()
        });
    });
});

$("body").on( "click", ".rechazoListado", function(event) {
    event.preventDefault();
    var id        = $(this).data('id');
    swal({
        title: "¿Estas seguro de rechazar la salida de este producto?",
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
        $.post(copy_js.base_url+'inventories/noAprovee',{id,razon}, function(result){
            var idTr = "tr#trMove_"+id;
            $(idTr).remove();
            message_alert("Salida rechazada correctamente.","Bien");
            $(".body-loading").hide(); 
        });
    });
});

$("body").on('click', '.checkallProducts', function(event) {
    if($(this).is(":checked")){
        $("body").find('.checkOneProducts').prop("checked",true);
    }else{
        $("body").find('.checkOneProducts').prop("checked",false);
    }
    validateChecksContinue();
});


function validateChecksContinue(){
    var total = 0;
    var totalChecks = 0;
    $("body").find('.checkOneProducts').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
        }
        totalChecks++;
    });
    if(total > 0){
        $("body").find(".dropdownMenuAccionesProductos").show();
    }else{
        $("body").find(".dropdownMenuAccionesProductos").hide();     
    }

    if(total != totalChecks){
        $("body").find('.checkallProducts').prop("checked",false);
    }else{
        $("body").find('.checkallProducts').prop("checked",true);
    }
}

$("body").on('click', '.checkOneProducts', function(event) {
    validateChecksContinue();
});

$("body").on( "click", "#rejectMassive", function(event) {
    event.preventDefault();

    var productIds = [];
    var total      = 0;
    $("body").find('.checkOneProducts').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
            productIds.push($(this).val());
        }
    });

    if(total > 0){

    swal({
        title: "¿Estas seguro de rechazar la salida de este(os) producto(s)?",
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

        for (var i = 0; i < productIds.length; i++) {
            var idTr = "tr#trMove_"+productIds[i];
            $(idTr).remove();
            console.log(idTr);
            $.post(copy_js.base_url+'inventories/noAprovee',{id:productIds[i],razon}, function(result){});
        }

        message_alert("Salida rechazada correctamente.","Bien");
        $(".body-loading").hide(); 
        
    });

    }

    
});

$("body").on( "click", "#aproveMassive", function(event) {
    event.preventDefault();

    var productIds = [];
    var total      = 0;
    $("body").find('.checkOneProducts').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
            productIds.push($(this).val());
        }
    });

    if(total > 0){
        swal({
            title: "¿Deseas aprobar la salidas de inventario?",
            text: "¿Deseas continuar con la acción?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
        }, function (inputValue) {

            $(".body-loading").show(); 

            for (var i = 0; i < productIds.length; i++) {
                var idTr = "tr#trMove_"+productIds[i];
                $(idTr).remove();
                console.log(idTr);
                $.post(copy_js.base_url+'inventories/approved',{id:productIds[i]}, function(result){});
            }
            message_alert("Salida aprobada correctamente.","Bien");
            $(".body-loading").hide(); 
        });

    }

    
});


$(".btn_view_group").click(function(event) {
    event.preventDefault();
    var id = $(this).data("uid");
    $.post(copy_js.base_url+'inventories/get_group_list',{id}, function(result){
        $("#cuerpoMovimiento").html(result)
    });
    $("#modalMovimiento").modal("show");
});


$("body").on( "click", ".btn_aprobarlistado_grupo", function(event) {
    event.preventDefault();

    var productIds = $(this).data("inventories");

    var productIds = productIds.toString();

    var idTr = "tr#trMoveGroup_"+$(this).data("uid");

    if(productIds.indexOf(",") != -1 ){
        productIds = productIds.split(",");
    }else{
        productIds = [productIds];
    }

        swal({
            title: "¿Deseas aprobar la salidas de inventario?",
            text: "¿Deseas continuar con la acción?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
        }, function (inputValue) {

            $(".body-loading").show(); 

            for (var i = 0; i < productIds.length; i++) {
                
                $(idTr).remove();
                $.post(copy_js.base_url+'inventories/approved',{id:productIds[i]}, function(result){});
            }
            message_alert("Salida aprobada correctamente.","Bien");
            $(".body-loading").hide(); 
        });


    
});

$("body").on( "click", ".rechazoListado_grupo", function(event) {
    event.preventDefault();

    var productIds = $(this).data("inventories");

    var productIds = productIds.toString();

    var idTr = "tr#trMoveGroup_"+$(this).data("id");

    if(productIds.indexOf(",") != -1 ){
        productIds = productIds.split(",");
    }else{
        productIds = [productIds];
    }

    swal({
        title: "¿Estas seguro de rechazar la salida de este(os) producto(s)?",
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

        for (var i = 0; i < productIds.length; i++) {
            $(idTr).remove();
            console.log(idTr);
            $.post(copy_js.base_url+'inventories/noAprovee',{id:productIds[i],razon}, function(result){});
        }

        message_alert("Salida rechazada correctamente.","Bien");
        $(".body-loading").hide(); 
        
    });
    
});