IDS_PRODUCTS =  [];

function find_product(id){
    if(IDS_PRODUCTS.indexOf(id) === -1){
        $.post(copy_js.base_url+'Products/get_product_tr', {id}, function(data, textStatus, xhr) {
            $("#TBodyProducto").append(data);
             showBtns();
        }); 
       IDS_PRODUCTS.push(id)
       reloadHtml(); 
    }   
}

function searchByData(productsIDs = undefined){
    var marcasData = $("#marcasData").val();
    var category_1 = $("#category_1").val();
    var category_2 = $("#category_2").val();
    var category_3 = $("#category_3").val();
    var category_4 = $("#category_4").val();

    if (marcasData != "" && category_1 != "") {
        $("#TBodyProducto").html("");
        $.post(copy_js.base_url+'Products/search_params', {marcasData, category_1, category_2,category_3,category_4,productsIDs }, function(data, textStatus, xhr) {
            $("#TBodyProducto").append(data);
        });
    }else{
        message_alert("Se debe seleccionar una marca y una categoría para la búsqueda de productos","error");
    }
}


$("body").on('click', '#buscaProductos', function(event) {
    searchByData();
});


if ( typeof productsIdsSelect != "undefined"  ) {
    setTimeout(function() {
        searchByData(productsIdsSelect)
    }, 1500);
}


$("body").on('click', '.btnDelete', function(event) {
    event.preventDefault();
    var id          = $(this).data("id");
    var position    = -1;
    var idTr        = "tr#trID_"+id;

    for (i in IDS_PRODUCTS){
        if(IDS_PRODUCTS[i] == id){
            position = i;
            break;
        }
    }

    position = 0;
    if(position != -1){
        IDS_PRODUCTS.splice(position, 1);
    }
    $(this).parent("td").parent("tr").remove();
    reloadHtml();

});

$("body").on('submit', '#GarantiaAddForm', function(event) {
    if ( !$(".product_select_inp").length ) {
        message_alert("Se debe seleccionar mínimo un producto","error");
        return false;
    }
});