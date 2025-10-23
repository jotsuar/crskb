$("body").on('click', '#createMovementData', function(event) {
    var id = $(this).data("id")
    event.preventDefault();
    $.post(copy_js.base_url+'Inventories/create_movement',{id}, function(result){
        $("#cuerpoMovimiento").html(result);
        $("#modalMovimiento").modal("show");
    });
});

$("body").on('click', '#type_movement', function(event) {
    if($(this).val() == ""){
        $("body").find(".entrada,.salida,.traslado").hide();
        $("body").find(".entradaProducto").removeAttr("required");
        $("body").find(".trasladoProducto").removeAttr("required");
        $("body").find(".salidaProducto").removeAttr("required");
    }else if ($(this).val() == "ADD") {
        $("body").find(".entrada").show();
        $("body").find(".salida,.traslado").hide();
        $("body").find(".entradaProducto").attr("required","required");
        $("body").find(".salidaProducto").removeAttr("required");
        $("body").find(".trasladoProducto").removeAttr("required");
    }else if ($(this).val() == "RM") {
        $("body").find(".salida").show();
        $("body").find(".entrada,.traslado").hide();
        $("body").find(".salidaProducto").attr("required","required");
        $("body").find(".entradaProducto").removeAttr("required");
        $("body").find(".trasladoProducto").removeAttr("required");
    }else if ($(this).val() == "TR") {
        $("body").find(".traslado").show();
        $("body").find(".entrada,.salida").hide();
        $("body").find(".trasladoProducto").attr("required","required");
        $("body").find(".salidaProducto").removeAttr("required");
        $("body").find(".entradaProducto").removeAttr("required");
    }
});

$("body").on('change', '#bodegaSalida', function(event) {
    var cantidad = $(this).children("option:selected").data("quantity");
    $("body").find('#CantidadSalida').attr("max",cantidad);
    $("body").find('#CantidadSalida').val(cantidad);
});

$("body").on('change', '#bodegaSalidaTraslado', function(event) {
    if($(this).val() == ""){
        $("body").find("#bodegaEntradaTraslado>option").each(function(index, el) {
        $(this).show();
        $("#bodegaEntradaTraslado").val("");
        });
    }else{
        var cantidad = $(this).children("option:selected").data("quantity");
        var bodega = $(this).children("option:selected").data("id");
        $("body").find('#CantidadSalidaTraslado').attr("max",cantidad);
        $("body").find('#CantidadSalidaTraslado').val(cantidad);

        $("body").find("#bodegaEntradaTraslado>option").each(function(index, el) {
            if ($(this).data("id") === bodega) {
                $(this).hide();
            }else{
                $(this).show();
            }
            $("#bodegaEntradaTraslado").val("");
        });
    }    
});

$("body").on('submit', '#formMovivimientoProducto', function(event) {
    event.preventDefault();
    data = $(this).serializeArray();
    PRODUCTS_ADD = [];
    producto = {};
    for (i in data){
        producto[data[i].name] = data[i].value;
    }
    PRODUCTS_ADD.push(producto);
    $(".body-loading").show();
    $.post(copy_js.base_url+'Inventories/saveMovements', {productos:PRODUCTS_ADD}, function(data, textStatus, xhr) {
        location.reload();
    });
});