$(document).ready(function() {
    console.log(typeof $.typeahead);
    if(typeof $.typeahead != "undefined"){
        load_data_search();
    }
});


// var campo = $('.validaempty').val();
// if(campo.length == 0){
//     $(".validaempty").css("padding", "0px");
// } else {
//     $(".validaempty").css("padding", "25px");
// }


$('#details-country tbody').on( "click", ".editarProduct", function() {
    $(".body-loading").show();
    var product_id        = $(this).data('uid');
    $.post(copy_js.base_url+'Products/form_quotation',{product_id:product_id,action:'edit'}, function(result){
        $('#modal_form_products_body').html(result);
        $('#modal_form_products_label').text('Editar producto');
        $('#modal_form_products').modal('show');
        $('#btn_action_products').text('Editar producto');
        inputPrecioEditAdd();
        $('#ProductDescription').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
            ],
            fontNames: ['Raleway'],
            focus: true,
            disableResizeEditor: true,
            callbacks: {
                onKeyup: function(e) {
                    var NUMEROCARACTERES     = e.currentTarget.innerText.length;
                    $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
                    $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
                }
            }
        });
        var NUMEROCARACTERES       = $('.note-editable').text().length;
        $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
        $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
        $("#category_1").val($("#category1Select").val());
        $("#category_1").trigger('change');
        $(".body-loading").hide();
    });
});
$("body").on('click', '#btn_registrar_products', function(event) {
    $(".body-loading").show();
    $.post(copy_js.base_url+'Products/form_quotation',{action:'add'}, function(result){
        $("body").find('#modal_form_products_body').html(result);
        $("body").find('#modal_form_products_label').text('Registrar producto');
        $("body").find('#modal_form_products').modal('show');
        $("body").find('#ProductDescription').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
              ],
            fontNames: ['Raleway'],
            focus: true,
            disableResizeEditor: true,
            callbacks: {
                onKeyup: function(e) {
                    var NUMEROCARACTERES     = e.currentTarget.innerText.length;
                    $("body").find('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
                    $("body").find('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
                }
            }
        });

        $(".body-loading").hide();
    });
});

$("#btn_action_products").click(function(event) {
    $(".body-loading").show();
    categoryID = 1;

    if(typeof INSTANCE_ADD != "undefined" ){
         categoryID = INSTANCE_ADD.getSelectedIds()[0];
    }else if (typeof INSTANCE_EDIT != "undefined" ) {
        categoryID = INSTANCE_EDIT.getSelectedIds()[0];
    }

    $("body").find("#ProductCategoryId").val(categoryID);

    var formData            = new FormData($('#formuploadajax')[0]);
    var product_id          = $('#ProductId').val();
    var part_number         = $('#ProductPartNumber').val();
    var name                = $('#ProductName').val();
    var description         = $('#ProductDescription').val();
    var list_price_usd      = $('#ProductListPriceUsd').val();
    var link                = $('#ProductLink').val();
    var bran                = $('#ProductBrand').val();
    var NUMEROCARACTERES    = $('.note-editable').text().length;
    if (name != '' && part_number != '' && list_price_usd != '') {
        var r                   = limit_characters(NUMEROCARACTERES,500);
        if (r == false) {
            message_alert("Por favor valida, has excedido el máximo número de caracteres en la descripción","error");
        } else if(bran == "1"){
            message_alert("Por favor selecciona una marca diferente a N/A","error");
            return;
        }else {
            $("#btn_action_products").hide();
            $.ajax({
                type: 'POST',
                url: copy_js.base_url+'Products/saveFormQuotation',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(result){
                    if (result != 5) {
                        if (product_id != '') {
                            delete_product(product_id);
                            setTimeout(function(){
                                $.post(copy_js.base_url+'Products/get_data_importacion',{id:product_id}, function(result){
                                    $('#milista').append(result);
                                    $("#btn_action_products").show();
                                });
                                message_alert("Producto actualizado satisfactoriamente, por favor vuelve agregarlo a la cotización","Bien");
                                $('#modal_form_products').modal('hide');
                            }, 4000);
                            load_data_search();
                        } else {
                            if(addProducts){
                                message_alert("Producto creado satisfactoriamente, si lo deseas en la cotización por favor agregalo","Bien");
                                load_data_search();
                            }else{
                                message_alert("Producto creado satisfactoriamente, no se podrá usar en esta solicitud hasta que sea desbloqueado por el área encargada","error");
                            }
                            $('#modal_form_products').modal('hide');
                        }
                        
                    } else {
                        validate_image_message(result);
                        $("#btn_action_products").show();
                    }
                    visualizarComentario();
                    $(".body-loading").hide();
                }
            });
        }
    } else {
        message_alert("Los campos son requeridos","error");
    }
});

$("body").on( "click", "#btn_find_existencia", function() {
    var numero_parte              = $('#ProductPartNumber').val();
    if (numero_parte != '') {
    $.post(copy_js.base_url+'Products/validExistencia',{numero_parte:numero_parte}, function(result){
        if (result == 0) {
            message_alert("El número de parte esta disponible","Bien");
        } else {
            message_alert("El número de parte ya esta registrado","error");
        }
    });
    } else {
        message_alert("Por favor ingresa el número de parte","error");
    }
});

$('#details-country tbody').on( "click", ".deleteProduct", function() {
    var product_id              = $(this).data('uid');
    delete_product(product_id);
    visualizarComentario();
});

$("body").on('click', '#details-country .deleteProduct', function(event) {
    event.preventDefault();
     var product_id              = $(this).data('uid');
    delete_product(product_id);
    visualizarComentario();
});

$('#btn_delete_cache').click(function(e) {
    $.post(copy_js.base_url+'Products/deleteCacheImportaciones',{}, function(result){
        location.reload(true);
    });
});

$("body").on( "click", "#btn_guardar", function() {
    var array_ids                   = [];
    var strMesage                   = "";
    $("tr.listado_tabla").each(function(){
        var saludo                  = this.id;
        var saludoArray             = saludo.split('_');
        var id                      = saludoArray[saludoArray.length - 1];
        array_ids.push(id);

        cantidad    = parseInt( $(this).data("quantity") );
        referencia  =  $(this).data("reference") ;

        if(cantidad > 0){
            strMesage+= "\n Referencia: "+referencia+", Cantidad actual: "+cantidad;
        }
    });

    var return_url = $("#ImportReturnUrl").length ? $("#ImportReturnUrl").val() : copy_js.base_url+copy_js.controller+'/my_import';

    if (array_ids.length > 0) {
        var formData            = new FormData($('#form_quotations')[0]);
        $('.btn_guardar').hide();
        if(strMesage != ""){
            swal({
                title: "¿Deseas importar los productos con las cantidades y precios seleccionados teniendo en cuenta que los siguientes productos están en inventario? ",
                text: strMesage,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonText:"Cancelar",
                confirmButtonText: "Aceptar",
                closeOnConfirm: false
            },
            function(){
                
                $.ajax({
                    type: 'POST',
                    url: copy_js.base_url+'FlowStages/addImport',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(result){
                        location.href = return_url
                    }
                });
            });
        }else{
            $.ajax({
                type: 'POST',
                url: copy_js.base_url+'FlowStages/addImport',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(result){
                    location.href = return_url
                }
            });
        }

        
    } else {
        message_alert("Debes agregar al menos un producto a la importación","error");
        event.preventDefault();
    }
});

function load_data_search(){
    $.post(copy_js.base_url+'Products/paintData',{}, function(result){
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
                    data: dataController.name
                },
                Parte: {
                    data: dataController.numero_parte
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    find_product(item.display);
                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                    visualizarComentario()
                }
            },
            debug: true
        });
    });
}
$("body").on( "click", ".minprods", function() {
    var minprod = $(this).attr("minprod");
    $(".img-quote").attr('src',minprod);
    $(".fondo").fadeIn();
    $(".popup3").fadeIn();
});

function find_product(name){
    $.post(copy_js.base_url+'Products/findName',{name:name}, function(id){
        $.post(copy_js.base_url+'Products/get_data_importacion',{id:id}, function(result){
            if (result == 'bloqueo') {
                    message_alert("No es posible agregar esta referencia, el producto se encuentra bloqueado por favor informar al área encargada.","error");
                    return false;
            }else if (result == 'editar') {
                if(!editProducts){
                    message_alert("El producto no puede ser agregado ya que es necesario editarlo y no posees permisos, por favor solicita el cambio al área encargada.","error");
                }else{
                    edit_product_maxDescripcion(id);
                }
                    
            }else{
                var num = parseInt(result);
                if (num < 1) {
                    message_alert("El producto ya se encuentra agregado","error");
                } else {
                    $('#milista').append(result);
                    visualizarComentario();
                    setTimeout(function() {
                        visualizarComentario();
                    }, 2000);
                }
            }    
            visualizarComentario();        
        });
        visualizarComentario()
    });
}

function edit_product_maxDescripcion(idProduct){
    message_alert("Para poder agregar el producto a la plantilla, por favor edita la descripción si excede el máximo de caracteres o la marca ya que, no puede ser N/A","error");
    var product_id        = idProduct;
    $.post(copy_js.base_url+'Products/form_quotation',{product_id:product_id,action:'edit'}, function(result){
        $('#modal_form_products_body').html(result);
        $('#modal_form_products_label').text('Editar producto');
        $('#modal_form_products').modal('show');
        $('#btn_action_products').text('Editar producto');
        inputPrecioEditAdd();
        $('#ProductDescription').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
            ],
            fontNames: ['Raleway'],
            focus: true,
            disableResizeEditor: true,
            callbacks: {
                onKeyup: function(e) {
                    var NUMEROCARACTERES     = e.currentTarget.innerText.length;
                    $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
                    $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
                }
            }
        });
        var NUMEROCARACTERES       = $('.note-editable').text().length;
        $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
        $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
        visualizarComentario()
        $("#category_1").val($("#category1Select").val());
        $("#category_1").trigger('change');
        $(".body-loading").hide();
    });
}

function inputPrecioEditAdd(){
    var precio              = $('#ProductListPriceUsd').val();
    var precio_final        = number_format(precio);
    $('#ProductListPriceUsd').val(precio_final);
}

function delete_product(product_id){
    $.post(copy_js.base_url+'Products/deleteProductImportacion',{product_id:product_id}, function(result){
        $('#tr_'+product_id).remove();
    });
}

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

$("body").on('change', '#category_1', function(event) {
    if($(this).val() != ""){
        inicializar("1");
        createOptions($(this).val(),"1")
    }else{
        inicializar("1");
    }
});

$("body").on('change', '#category_2', function(event) {
    if($(this).val() != ""){
        inicializar("2");
        createOptions($(this).val(),"2")
    }else{
        inicializar("2");
    }
});

$("body").on('change', '#category_3', function(event) {
    if($(this).val() != ""){
        inicializar("3");
        createOptions($(this).val(),"3")
    }else{
        inicializar("3");
    }
});

function inicializar($num){

    if($num == "1"){        
        $("#category_2").html('<option value="">Seleccionar</option>');
        $("#category_3").html('<option value="">Seleccionar</option>');
        $("#category_4").html('<option value="">Seleccionar</option>');
    }else if($num == "2"){      
        $("#category_3").html('<option value="">Seleccionar</option>');
        $("#category_4").html('<option value="">Seleccionar</option>');
    }else if($num == "3"){      
        $("#category_4").html('<option value="">Seleccionar</option>');
    }
}

function createOptions($value,$num){
    var values = categoriesInfoFinal[$value];

    if($num == "1"){        
        $("#category_2").html('<option value="">Seleccionar</option>');
        for (i in values){
            var selected = "";
            var category2Select = $("#category2Select").length &&  $("#category2Select").val() != "" ? $("#category2Select").val() : null;
            if(category2Select != null && category2Select == values[i].id ){
                selected = "selected"
            }
            $("#category_2").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
        }
        if(category2Select != null){
            $("#category_2").trigger('change');
        }
    }else if($num == "2"){      
        $("#category_3").html('<option value="">Seleccionar</option>');
        for (i in values){
            var selected = "";
            var category3Select = $("#category3Select").length &&  $("#category3Select").val() != "" ? $("#category3Select").val() : null;
            if(category3Select != null && category3Select == values[i].id ){
                selected = "selected"
            }
            $("#category_3").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
        }
        if(category3Select != null){
            $("#category_3").trigger('change');
        }
    }else if($num == "3"){      
        $("#category_4").html('<option value="">Seleccionar</option>');
        for (i in values){
            var selected = "";
            var category4Select = $("#category4Select").length &&  $("#category4Select").val() != "" ? $("#category4Select").val() : null;
            if(category4Select != null && category4Select == values[i].id ){
                selected = "selected"
            }
            $("#category_4").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
        }
        if(category4Select != null){
            $("#category_4").trigger('change');
        }
    }
}