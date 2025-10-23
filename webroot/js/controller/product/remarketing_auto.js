IDS_PRODUCTS =  [];

$(document).ready(function() {
    load_data_search();
});

function load_data_search(){
    $.post(copy_js.base_url+'Products/paintData',{remarketing: 1}, function(result){
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
                    display:"part_number",
                    data: dataController
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    find_product(item.id);
                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                }
            },
            debug: true
        });
    });

    $('body').find("#cuerpocorreo").summernote(
        {
          height: 1000,
        }
    );
}

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
    showBtns();
    reloadHtml();

});


function showBtns(){
    if($("body").find('.tr_remarket').length){
        $("#botones").show();
    }else{
        $("#botones").hide();  
        reloadHtml();      
    }
}

$(".btnSearchData").click(function(event) {
    event.preventDefault();
    reloadHtml();
    var type = $(this).data("type");
    $(".body-loading").show();
    var ht = $("#paso1").height() < 400 ? 400 : $("#paso1").height();
    $("#resultado").html("");
    $.post(copy_js.base_url+'Products/getDataRemarket', {type, ht, products: IDS_PRODUCTS}, function(data, textStatus, xhr) {
        $("#resultado").html(data);
        $(".body-loading").hide();
    });
});


$("body").on('click', '.seleccionTotal', function(event) {
    if($(this).is(":checked")){
        $("body").find('.seleccionBasica').prop("checked",true);
    }else{
        $("body").find('.seleccionBasica').prop("checked",false);
    }
    validateChecksContinue();
});


function validateChecksContinue(){
    var total = 0;
    $("body").find('.seleccionBasica').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
        }
    });
    if(total > 0){
        $("body").find("#btnContinue").show();
    }else{
        $("body").find("#btnContinue").hide();
        $("#resultado3").hide();
        $("#resultado3").html("");       
    }
}

$("body").on('click', '.seleccionBasica', function(event) {
    validateChecksContinue();
});

function searchByData(){
    var marcasData = $("#marcasData").val();
    var category_1 = $("#category_1").val();
    var category_2 = $("#category_2").val();
    var category_3 = $("#category_3").val();
    var category_4 = $("#category_4").val();
    $("#TBodyProducto").html("");
    $.post(copy_js.base_url+'Products/search_params', {marcasData, category_1, category_2,category_3,category_4}, function(data, textStatus, xhr) {
        $("#TBodyProducto").append(data);
        showBtns();
    });
}


$("body").on('change', '#marcasData,#category_1,#category_2,#category_3,#category_4', function(event) {
    searchByData();
});

$("body").on('submit', '#form_campaign', function(event) {
    event.preventDefault();
    var mensaje = $("body").find("#cuerpocorreo").val();

    if(!$("body").find('.tr_remarket').length ){
        message_alert("Se debe seleccionar mínimo un producto","error");
    }else if($.trim(mensaje) == ""){
        message_alert("El cuerpo del correo es requerido","error");
    }else{
        var productosData = [];
        $(".tr_remarket").each(function(index, el) {
            productosData.push($(this).data("id"));
        });
        $("#productosData").val(productosData.toString());
        $(".body-loading").show();
        $.post(copy_js.base_url+'Products/send_remarketing', $('#form_campaign').serialize(), function(data, textStatus, xhr) {
            $(".body-loading").hide();
            // location.href = copy_js.base_url+'campaigns/index';
        });
    }
});     

function reloadHtml(){
    // $("#resultado").html("");
    // $("#resultado3").html("");
    // $("#resultado3").hide();
    // $("#paso3").hide();
}

