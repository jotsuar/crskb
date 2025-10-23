$("body").on('click', '.blockProduct', function(event) {
    event.preventDefault();
    var url = $(this).data("url");
    var state = $(this).data("state");
    var mensaje = state == "1" ? "bloquear" : "desbloquear";
    swal({
        title: "¿Estas seguro de esta cambio?",
        text: "¿Deseas "+mensaje+" este producto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, cancelar",
        confirmButtonText: "Si, continuar",
        closeOnConfirm: false
    },
    function(){
        $.post(url, {}, function(data, textStatus, xhr) {
            console.log(data)
            location.reload();
        });
    });
});


$("body").on('click', '.delete_product', function(event) {
    event.preventDefault();
    var url = $(this).data("url");  
    swal({
        title: "¿Estas seguro de elimianr este producto?",
        text: "¿Deseas eliminar este producto definitivamente, este solo se verá en cotizaciones antiguas ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, cancelar",
        confirmButtonText: "Si, continuar",
        closeOnConfirm: false
    },
    function(){
        $.post(url, {}, function(data, textStatus, xhr) {
            console.log(data)
            location.reload();
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
        $("body").find("#dropdownMenuAccionesProductos").show();
    }else{
        $("body").find("#dropdownMenuAccionesProductos").hide();     
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

$("#dropdownMenuAccionesProductos").click(function(event) {
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
        var url = 
        swal({
            title: "¿Estas seguro de desbloquear "+ productIds.length +" producto(s)?",
            text: "¿Deseas desbloquear "+ productIds.length +" producto(s) definitivamente, se habilitarán para ser cotizados ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"No, cancelar",
            confirmButtonText: "Si, continuar",
            closeOnConfirm: false
        },
        function(){
            $.post(copy_js.base_url+'Products/unlockMasive', {products: productIds}, function(data, textStatus, xhr) {
                console.log(data)
                location.reload();
            });
        });

    }
});