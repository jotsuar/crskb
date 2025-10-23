$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
        buscadorFiltro();
    }
});

$("body").on('click', '.btn_buscar', function(event) {
    event.preventDefault();
    buscadorFiltro();
});


var optionsCategory = {
    source : structure,
    isMultiple: false,
    collapse:true,
    selectableLastNode:true
};

if(categorySelect != null){

    optionsCategory.selected = [categorySelect];
    optionsCategory.collapse = false;
}

// var instance = $('#categoryData').comboTree(optionsCategory);

$(".btn_buscar").click(function() {
	buscadorFiltro();
});

function buscadorFiltro(){

    if (actual_uri.indexOf("page:") != -1) {
        var pages       = actual_uri.indexOf("page:");
        var pages2       = actual_url2.indexOf("page:");
        var get_param   = actual_uri.indexOf("?") === -1 ? actual_uri.length : actual_uri.indexOf("?");
        var get_param2   = actual_url2.indexOf("?") === -1 ? actual_url2.length : actual_url2.indexOf("?");
        var total = get_param - pages;
        var total2 = get_param2 - pages2;

        actual_uri = actual_uri.replace(actual_uri.substr(pages,(total)),"");
        actual_url2 = actual_url2.replace(actual_url2.substr(pages2,(total2)),"");      
    }

    if(actual_uri.indexOf("?") === -1){
        actual_uri+="?";
    }

    var actual_query        =  URLToArray(actual_uri);

    var texto               = $('#txt_buscador').val();
    var brand               = $('#marcasData').val();
    var precio              = $('#precioCop').val();
    var costo               = $('#costoUSD').val();
    var inventario          = $('#inventoryData').val();
    var category1           = $("#category_1").val();
    var category2           = $("#category_2").val();
    var category3           = $("#category_3").val();
    var category4           = $("#category_4").val();


    if(texto === "" && brand === "" && typeof precio != "undefined" && precio === "0,0" && typeof costo != "undefined" && costo === "0,0" && inventario == "" && category1 == "" && category2 == "" && category3 == "" && category4 == ""){
        delete actual_query.q;
        delete actual_query.brand;
        delete actual_query.category1;
        delete actual_query.category2;
        delete actual_query.category3;
        delete actual_query.category4;
        delete actual_query.precio;
        delete actual_query.costo;
        delete actual_query.inventario;
    }else{
        if (texto != "" && texto !== null) {
            actual_query["q"] = texto;
        }
        else{
            delete actual_query.q;
        }

        if (brand != "" && brand !== null) {
            actual_query["brand"] = brand;
        }
        else{
            delete actual_query.brand;
        }

        if (category1 != "" ) {
            actual_query["category1"] = category1;
        }
        else{
            delete actual_query.category1;
        }

        if (category2 != "" ) {
            actual_query["category2"] = category2;
        }
        else{
            delete actual_query.category2;
        }

        if (category3 != "" ) {
            actual_query["category3"] = category3;
        }
        else{
            delete actual_query.category3;
        }

        if (category4 != "" ) {
            actual_query["category4"] = category4;
        }
        else{
            delete actual_query.category4;
        }

        if (inventario != "" && inventario !== null) {
            actual_query["inventario"] = inventario;
        }
        else{
            delete actual_query.inventario;
        }

        if (typeof precio != "undefined" && precio != "0,0" && precio !== null) {
            actual_query["precio"] = precio;
        }
        else{
            delete actual_query.precio;
        }

        if (typeof costo != "undefined" && costo != "0,0" && costo !== null) {
            actual_query["costo"] = costo;
        }
        else{
            delete actual_query.costo;
        }
    }
    var remplazo = /%2B/g;
    var remplazo2 = /%2C/g;
    var urlEnvio = actual_url2+$.param(actual_query);
    urlEnvio = urlEnvio.replace(remplazo,"+");
    urlEnvio = urlEnvio.replace(remplazo2,",");

	var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	var hrefFinal 					= hrefURL+"?q="+texto;
	location.href 					= urlEnvio;
}

$("#texto_busqueda").click(function() {
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});

$(".masiveProducts").click(function(event) {
	event.preventDefault();
	type = $(this).data("type");
	$.post(copy_js.base_url+'Products/admin_masive', {type}, function(data, textStatus, xhr) {
		$("#cuerpoMasivo").html(data)
		$("#modalMasiva").modal("show");
	});
});

$(".getCosts").click(function(event) {
    event.preventDefault();
    $.post($(this).attr("href"), {}, function(data, textStatus, xhr) {
        $("#cuerpoCosto").html(data)
        $("#modalCosto").modal("show");
    });
});

// $(".price-purchase_price_usd,.price-purchase_price_wo").click(function(event) {
//     var id      = $(this).children('.cambioCosto').data("id");
//     var type    = $(this).children('.cambioCosto').data("type");
//     console.log(id);
//     console.log(type);



    

//     $(this).children('span.cambioCosto').trigger('focus');

// });

var submitdata = {}

$('.cambioCostoDataUsd').editable(copy_js.base_url+copy_js.controller+'/changeCostData', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 0.3,
    max: 1000000,
    onedit : function() { console.log('If I return false edition will be canceled'); return true;},
    before : function() {
        var element = this;        
        setTimeout(function() {
            element.submitdata.type = $("body").find("#formularioQueCambia").parent("div").data("type");
            element.submitdata.id = $("body").find("#formularioQueCambia").parent("div").data("id");
            element.submitdata.currency = $("body").find("#formularioQueCambia").parent("div").data("currency");
            $("body").find("#formularioQueCambia").children('input').val($("body").find("#formularioQueCambia").parent("div").data("price"));
            $("body").find("#formularioQueCambia").children('input').attr("step", "any")
        }, 100);

    },
    callback : function(result, settings, submitdata) {
        location.reload();
    },
    cancel : 'Cancelar',
    cssclass : 'custom-class form-control',
    cancelcssclass : 'btn btn-danger',
    submitcssclass : 'btn btn-success',
    maxlength : 200,
    // select all text
    select : false,
    label : 'Cambio de costo USD',
    onreset : function() { console.log('Triggered before reset') },
    onsubmit : function() { 
        var value = $(this[0]).children('input').val();
        if(value != ""){
            $(".body-loading").show();
        }else{
            return false;
        }
    },
    showfn : function(elem) { elem.fadeIn('slow') },
    submit : 'Guardar',
    submitdata : submitdata,
    tooltip : "Click para editar",
    placeholder : "Ingrese el costo",
    width : 160,
    formid: 'formularioQueCambia'
});

$('.cambioCostoDataCop').editable(copy_js.base_url+copy_js.controller+'/changeCostData', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 500,
    max: 1000000000,
    onedit : function() { console.log('If I return false edition will be canceled'); return true;},
    before : function() {
        var element = this;        
        setTimeout(function() {
            element.submitdata.type = $("body").find("#formularioQueCambia").parent("div").data("type");
            element.submitdata.id = $("body").find("#formularioQueCambia").parent("div").data("id");
            element.submitdata.currency = $("body").find("#formularioQueCambia").parent("div").data("currency");
            $("body").find("#formularioQueCambia").children('input').val($("body").find("#formularioQueCambia").parent("div").data("price"));
            $("body").find("#formularioQueCambia").children('input').attr("required", "required")
            $("body").find("#formularioQueCambia").children('input').attr("step", "any")
        }, 100);

    },
    callback : function(result, settings, submitdata) {
        location.reload();
    },
    cancel : 'Cancelar',
    cssclass : 'custom-class form-control',
    cancelcssclass : 'btn btn-danger',
    submitcssclass : 'btn btn-success',
    maxlength : 200,
    // select all text
    select : false,
    label : 'Cambio de costo WO',
    onreset : function() { console.log('Triggered before reset') },
    onsubmit : function() { 
        var value = $(this[0]).children('input').val();
        if(value != ""){
            $(".body-loading").show();
        }else{
            return false;
        }
    },
    showfn : function(elem) { elem.fadeIn('slow') },
    submit : 'Guardar',
    submitdata : submitdata,
    tooltip : "Click para editar",
    placeholder : "Ingrese el costo",
    width : 160,
    formid: 'formularioQueCambia'
});

$('.cambioCostoDataCop2').editable(copy_js.base_url+copy_js.controller+'/changeCostData', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 50,
    max: 1000000000,
    onedit : function() { console.log('If I return false edition will be canceled'); return true;},
    before : function() {
        var element = this;        
        setTimeout(function() {
            element.submitdata.type = $("body").find("#formularioQueCambia").parent("div").data("type");
            element.submitdata.id = $("body").find("#formularioQueCambia").parent("div").data("id");
            element.submitdata.currency = $("body").find("#formularioQueCambia").parent("div").data("currency");
            $("body").find("#formularioQueCambia").children('input').val($("body").find("#formularioQueCambia").parent("div").data("price"));
            $("body").find("#formularioQueCambia").children('input').attr("required", "required")
            $("body").find("#formularioQueCambia").children('input').attr("step", "any")
        }, 100);

    },
    callback : function(result, settings, submitdata) {
        location.reload();
    },
    cancel : 'Cancelar',
    cssclass : 'custom-class form-control',
    cancelcssclass : 'btn btn-danger',
    submitcssclass : 'btn btn-success',
    maxlength : 200,
    // select all text
    select : false,
    label : 'Cambio de costo de compra COP',
    onreset : function() { console.log('Triggered before reset') },
    onsubmit : function() { 
        var value = $(this[0]).children('input').val();
        if(value != ""){
            $(".body-loading").show();
        }else{
            return false;
        }
    },
    showfn : function(elem) { elem.fadeIn('slow') },
    submit : 'Guardar',
    submitdata : submitdata,
    tooltip : "Click para editar",
    placeholder : "Ingrese el costo",
    width : 160,
    formid: 'formularioQueCambia'
});





$("body").on('submit', '#form_masive', function(event) {
	event.preventDefault();
	var formData            = new FormData($('#form_masive')[0]);
    $(".body-loading").show();
	$.ajax({
        type: 'POST',
        url: copy_js.base_url+'Products/importProducts',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            $(".body-loading").hide();
        	if(result == "0"){
        		message_alert("El formato del archivo es incorrecto",'error');
        	}else if (result == "1") {
        		message_alert("Las columnas del archivo no coinciden con la plantilla requerida",'error');
        	}else if (result == "2") {
        		message_alert("No se ingresaron productos",'error');
        	}else{
        		message_alert(result,'Bien');
        		setTimeout(function() {
        			location.reload();
        		}, 5000);
        	}
        }
    });
});


$("body").on('click', '.categoriasSelect', function(event) {
    var parent = $(this).data("parent");
    var id     = $(this).data("id");



}); 


$('#precioCop').jRange({
    from: precioMin,
    to: precioMax,
    step: 10000,
    format: '%s COP',
    width: 250,
    showLabels: true,
    isRange : true
});


$('#costoUSD').jRange({
    from: costoMin,
    to: costoMax,
    step: 10,
    format: '%s USD',
    width: 250,
    showLabels: true,
    isRange : true
});

if(costoMaxSelect != null && costoMinSelect != null){
    $('#costoUSD').jRange('setValue', costoMinSelect+','+costoMaxSelect);
}

if(precioMaxSelect != null && precioMinSelect != null){
    $('#precioCop').jRange('setValue', precioMinSelect+','+precioMaxSelect);
}


$("body").on('click', '#borrarTodo', function(event) {
    event.preventDefault();
    location.href = actual_url2;
});


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

$("body").on('click', '.importerProduct', function(event) {
    event.preventDefault();
    var url = $(this).data("url");
    swal({
        title: "¿Estas seguro de esta cambio?",
        text: "¿Deseas cambiar este producto?",
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
            title: "¿Estas seguro de eliminar "+ productIds.length +" producto?",
            text: "¿Deseas eliminar "+ productIds.length +" producto(s) definitivamente, solo se verán en cotizaciones antiguas ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"No, cancelar",
            confirmButtonText: "Si, continuar",
            closeOnConfirm: false
        },
        function(){
            $.post(copy_js.base_url+'Products/deleteMasive', {products: productIds}, function(data, textStatus, xhr) {
                console.log(data)
                location.reload();
            });
        });

    }
});