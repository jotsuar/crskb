PRODUCTS_ADD = [];

function load_data_search(type_move){
    $(".body-loading").show();
    $.post(copy_js.base_url+'Products/paintDataTwo',{type_move}, function(result){
        $(".body-loading").hide();
        var dataController = $.parseJSON(result);
        $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            maxItem: 10,
            order: "asc",
            hint: true,
            maxItemPerGroup: 5,
            backdrop: {
                "background-color": "#fff"
            },
            group: {
                template: "{{group}}"
            },
            emptyTemplate: 'No se encuentran resultados: "{{query}}"',
            source: {
                Nombre: {
                    display: "name",
                    data: dataController
                },
                Parte: {
                    display: "part_number",
                    data: dataController
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    createMovimiento(item.id);
                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                }
            },
            debug: true
        });
    });
}

if(typeof type_move != "undefined"){
    load_data_search(type_move);
    if(type_move == "RM"){
        $("#rmData").show();

        $("body").on('change', '#razonAll', function(event) {
            var valorRazon = $(this).val();

            if(valorRazon != ""){
                $("#panelAllNoneMoves,#panelAllNone").show();
                PRODUCTS_ADD = [];
                get_tableData(PRODUCTS_ADD)
                $("#razonGeneral").val("");
                if(valorRazon == "SI"){
                    $("#razonGeneralDiv").show();
                }else{
                    $("#razonGeneralDiv").hide();
                }

            } else{
                $("#panelAllNoneMoves,#panelAllNone").hide();
                $("body").find("#productosMovimiento").html('')
            }
        });

    }
}

function createMovimiento(id){

    position = -1;

    var razonGeneral = null;

    if(PRODUCTS_ADD.length > 0 ){
        for (i in PRODUCTS_ADD){
            if(PRODUCTS_ADD[i].productoId == id){
                position = i;
                break;
            }
        }
    }
    
    if (position != -1) {
        var mensaje         = "El producto ya fue ingresado";
        var tipo            = "error";
        message_alert(mensaje,tipo);
        return false;
    }

    if($("#razonAll").length){
       if($("#razonAll").val() == "SI" && $("#razonGeneral").val() == "" ){
          message_alert("La razón general es necesaría",'error');
          return false;
       }else{
          razonGeneral = $("#razonGeneral").val();
       }
       
    }

    $.post(copy_js.base_url+'Inventories/create_movement',{id, type_move,razonGeneral}, function(result){
        $("#cuerpoMovimiento").html(result);
        $("#modalMovimiento").modal("show");
        $("#type_movement").trigger('click');
    });
}

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


$("body").on('click', '.radioOptions', function(event) {
    location.href = copy_js.base_url+'Inventories/movements?type='+$(this).val();
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

get_tableData(PRODUCTS_ADD)

function get_tableData(data = []){
    $.post(copy_js.base_url+'Inventories/table_information',{productos:data, type_move: type_move}, function(result){
        $("body").find("#productosMovimiento").html(result)
        $("#modalMovimiento").modal("hide");
    });
}

$("body").on('submit', '#formMovivimientoProducto', function(event) {
    event.preventDefault();
    data = $(this).serializeArray();
    producto = {};
    for (i in data){
        producto[data[i].name] = data[i].value;
    }
    PRODUCTS_ADD.push(producto)
    get_tableData(PRODUCTS_ADD)
    message_alert("Producto agregado con éxito",'Bien');
});

$("body").on('click', '.eliminarPanel', function(event) {
    event.preventDefault();
    
    position        = -1;
    id              = $(this).data("id");

    for (i in PRODUCTS_ADD){
        if(PRODUCTS_ADD[i].productoId == id){
            position = i;
            break;
        }
    }

    if(position != -1){
        PRODUCTS_ADD.splice(position, 1);
    }

    get_tableData(PRODUCTS_ADD);
    message_alert("Producto eliminado con éxito",'Bien');

});


$("body").on('click', '.btnGuardarMovimientos', function(event) {
    event.preventDefault();

    var empaque = undefined;

    if($("#empaque").length){
        empaque = $("#empaque").val();
    }

    if(PRODUCTS_ADD.length === 0){
        message_alert("Se debe agregar mínimo un producto para movimiento",'error');
        return false;
    }else{
        swal({
            title: "¿Estas seguro que desea realizar el/los movimiento(s)?",
            text: "¿Deseas los movimientos de inventario creados?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: false
        },
        function(){
            $(".body-loading").show();
            $.post(copy_js.base_url+'Inventories/saveMovements', {productos:PRODUCTS_ADD,empaque}, function(data, textStatus, xhr) {
                location.reload();
            });
        });
    }

    
});