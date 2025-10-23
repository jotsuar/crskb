
typeProduct();
function setOrder(){
  $( "#productosCotizacionData" ).sortable();
  $( "#productosCotizacionData" ).disableSelection();

  $( "#productosCotizacionData" ).on( "sortchange", function( event, ui ) {
    console.log(event);
    console.log(ui);
    setTimeout(function() {
      var orderProducts = $( "#productosCotizacionData" ).sortable('toArray');
      $.post(copy_js.base_url+"quotations_marketings/order_products",{order:orderProducts}, function(data) {

      });
    }, 1500);
  } );

  // $( "#productosCotizacionData" ).sortable('serialize',{ key: "sort" });
}

function typeProduct(){
  $.get(copy_js.base_url+"quotations_marketings/list_productos",{id:$("#ProductIdProduct").val(),"action": $("#ProductAction").val()}, function(data) {
    $(".compuestoData").html(data);
    $("#cuerpoAdd").html("");
    $("#modalProductoData").modal("hide");
    setOrder()
  });
}

$("body").on('click', '#addProductToCompuesto', function(event) {
  event.preventDefault();
  $("#cuerpoAdd").html("");
  $(".body-loading").show();
  $.get(copy_js.base_url+"quotations_marketings/add_producto", function(data) {
    $("#cuerpoAdd").html(data)
    $('#ProductoProductId').select2({
        placeholder: "Seleccione una opción",
        dropdownParent: $("#modalProductoData")
    });
    $("#ProductoAddProductoForm").parsley();
    $("#modalProductoData").modal("show");
    $(".body-loading").hide();
  });
});

$("body").on('change', '#ProductoProductId', function(event) {
  event.preventDefault();
  if($(this).val() != "" && $("#ProductoPrice").length ){
    const costo = $("#ProductoProductId option:selected").attr("data-cost");
    $("#ProductoPrice").val( Math.round(costo/0.65) )
  }
});

$("body").on('submit', '#ProductoAddProductoForm', function(event) {
  event.preventDefault();
  $.post($(this).attr("action"),$(this).serialize(), function(response, textStatus, xhr) {
    $( "#productosCotizacionData" ).sortable( "destroy" );
    typeProduct();
  });
});

$("body").on('click', '.deleteComposition', function(event) {
  event.preventDefault();
  var id = $(this).data("id");
  $.post(copy_js.base_url+"quotations_marketings/delete_producto", {id: id}, function(data, textStatus, xhr) {
    $( "#productosCotizacionData" ).sortable( "destroy" );
    typeProduct();
  });
});

$("body").on('click', '.editProducto', function(event) {
  event.preventDefault();
  var id = $(this).data("id")
  $("#cuerpoAdd").html("");
  $(".body-loading").show();
  $.get(copy_js.base_url+"quotations_marketings/add_producto", {id:id}, function(data) {
    $("#cuerpoAdd").html(data)
    $('#ProductoProductId').select2({
        placeholder: "Seleccione una opción",
        dropdownParent: $("#modalProductoData")
    });
    $("#ProductoAddProductoForm").parsley();
    $("#modalProductoData").modal("show");
    $(".body-loading").hide();
  });
});