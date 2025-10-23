let FLUJOS = [];

$("body").on('click', '.validarFacturaWoCa', function(event) {
    event.preventDefault();
    var number = $("#ProspectiveUserBillCodeCargaFactura").val();
    var prefijo = $("#ProspectiveUserBillPrefijoCargaFactura").val();
    $("#datosBuscaSd,.borraDatos").hide();
    $(".validarFacturaWoCa").hide();
    $("#ProspectiveUserBillCodeCargaFactura").removeAttr("readonly");
    $("#ProspectiveUserBillPrefijoCargaFactura").removeAttr("readonly");
    if (number == "" || prefijo == "") {
         message_alert("El código y el prefijo son requeridos","error");
    }else{
        $.ajax({
            url: copy_js.base_url+"ProspectiveUsers/get_document",
            type: 'post',
            data: {number,prefijo,nuevo: 1},
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            $(".datosWoCarga").html(response)
            if ($("body").find('#existeInfo').length) {
                $("#datosBuscaSd, .borraDatos").show();
                $("#ProspectiveUserBillCodeCargaFactura").attr("readonly","readonly");
                $("#ProspectiveUserBillPrefijoCargaFactura").attr("readonly","readonly");
                $(".validarFacturaWoCa").hide();
            }else{
                $("#datosBuscaSd, .borraDatos").hide();
                $("#ProspectiveUserBillCodeCargaFactura").removeAttr("readonly");
                $("#ProspectiveUserBillPrefijoCargaFactura").removeAttr("readonly");
                $(".validarFacturaWoCa").show();
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



$("body").on('click', '.borraDatos', function(event) {
    event.preventDefault();
    $("#BuscaCliente").val(null).trigger("change"); 
    $("#ProspectiveUserBillCodeCargaFactura").removeAttr("readonly");
    $("#ProspectiveUserBillPrefijoCargaFactura").removeAttr("readonly");
    $(".datosWoCarga").html("")
    $("#datosBuscaSd, .borraDatos").hide();
    $(".validarFacturaWoCa").show();
});

// if (dropDown != null) {
//     options["dropdownParent"] = $(dropDown);
// }
// 
$("#form_bill_carga_factura").parsley();


var options = {
    placeholder: "Buscar flujo",
    minimumInputLength: 4,
    multiple: true,
    language: "es",
    ajax: {
        async: false,
        // url: copy_js.base_url+"prospective_users/get_user",
        url: copy_js.base_url+"prospective_users/get_flujo",
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
            // pagination: {
            //   more: (params.page * 30) < data.total_count
            // }
          };
        },
    }
};

var $elementData = $("body").find("#BuscaCliente");

$elementData.select2(options).on('select2:select', function (e) {
    var data = e.params.data;
    $(".body-loading").show();
    $("#cuerpoSeleccion").html("")
    $.post(copy_js.base_url+"flow_stages/productos_factura", {flujo_id: data.id}, function(response, textStatus, xhr) {
        $("#cuerpoSeleccion").html(response)
        $("#modalSeleccionProductos").modal({
          keyboard: false,
          show: true,
          backdrop: 'static'
        });
        $(".body-loading").hide();
        $(".cancelmodal").attr("data-id",data.id);
    });
    

}).on('select2:unselect', function (e) {
  var data = e.params.data;
  var idFlujo = data.id;
    FLUJOS = FLUJOS.filter(function(flujo) {
        return flujo.id != idFlujo
    });
    setData(FLUJOS);
});


$("body").on('click', '#terminarSeleccion', function(event) {
    event.preventDefault();
    var totalSelected = 0;
    var selectedIds   = [];

    $(".productsEnvioCheck").each(function(index, el) {
        if ($(this).is(":checked")) {
            totalSelected++;
            selectedIds.push($(this).val())
        }
    });

    if (totalSelected > 0) {

        FLUJOS.push( { id: $("#cargaFacturaIDFlujo").val(), productos: selectedIds } );
    }else{
        message_alert("Debe seleccionar mínimo 1 producto a facturar","error");
    }

    $("#modalSeleccionProductos").modal("hide");

    setData(FLUJOS);

});

$('#modalSeleccionProductos').on('hidden.bs.modal', function (e) {
  $("#cuerpoSeleccion").html("")
})

$(".cancelmodal").click(function(event) {
    var idFlujo = $(this).attr("data-id");
    FLUJOS = FLUJOS.filter(function(flujo) {
        return flujo.id != idFlujo
    });
    setData(FLUJOS);
    $("#BuscaCliente option[value='" + idFlujo+ "']").remove();
    $("#BuscaCliente").trigger("change");
});

function setData(FLUJOS_DATA){
    $("#products_data").val( JSON.stringify(FLUJOS_DATA) )
}

//$("#BuscaCliente option[value='" + $('#inventoryR01').val()+ "']").remove();
