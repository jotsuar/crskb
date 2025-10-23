PRODUCTO_IDS = [];

CALCULOS = [];



var INSTANCE_ADD;
var INSTANCE_EDIT;

PRODUCTOS_QUOTATION = [];

$(document).ready(function() {
    load_data_search();
    header_option();
    if (copy_js.controller == 'Quotations' || copy_js.controller == 'quotations') {
        textoInicialNota();
        // validateCampos();
        load_data_borrador();
        $( "#milista" ).sortable({
            placeholder: "ui-state-highlight",
        });
        $( "#milista" ).disableSelection();
    }
});

$(".configotherdataquote i").click(function() {
    $(".opcionesadicionales").toggleClass("muestra");
});

const formatter = new Intl.NumberFormat('en-US', {
  // style: 'currency',
  // currency: 'USD',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2,
})

function textoInicialNota(){
    $('#cotizaciones_flujo').prop( "disabled", true );
    $("#introtext").css("display", "block");
    $("#introtext2").css("display", "block");
}

$(".limpiarIntro").click(function(event) {
    var id = "#"+$(this).data("intro");
    $(id).html("");
});

function validateCampos(){
    $('label[for="totalCalculado"]').hide();
    $('#totalCalculado').hide();
    $('input[type=submit]').hide();
}


$("#money_format").change(function(event) {
    var valor = $("#money_format").val();
    if(valor != "0"){
        $('#QuotationCurrencyData').val("money_"+valor);
    }else{
        $('#QuotationCurrencyData').val("");
    }
    saveBorradorQuotation(function(){
        if(valor == "0" ){
            location.href = actual_url2;
        }else{
            location.href = actual_url2+"money="+valor;
        }
    });
    
});

$('#btn_total_cotizacion').click(function() {
    calcular_total();
});

$("#PaisFlujo").change(function(event) {
    flujo = $(this).data("flujo");
     $.post(copy_js.base_url+copy_js.controller+'/changeCountry', {flujo,country: $(this).val()}, function(data, textStatus, xhr) {
        location.reload();
    });
    console.log(flujo)
});

$("body").on('change', '#totalDescuento', function(event) {
    calcular_total();
}).on('keyup', '#totalDescuento', function(event) {
    calcular_total();
});;

function calcular_total(validate = null){
    var resultado = 0;
    $("body").find(".valoresCotizacionProductos").each(function(){
        resultado += ( parseFloat($(this).val()) * parseFloat( $(this).parent("td").next("td").find('.cantidadProduct').val() ) ) ;
    });
    var descuento = $("#totalDescuento").val();
    if (descuento != "" && parseInt(descuento) != 0 && parseInt(descuento) >= 1 ) {
        var valorDescuento = resultado * (descuento/100);
        resultado = resultado-valorDescuento;
    }
    var original = resultado;
    resultado = formatter.format(resultado);


    $('#totalCalculado').val(resultado);    
    visualizarComentario();
    setTimeout(function() {
        visualizarComentario();
    }, 2000);
    // showMargen(validate,original)
}

$("body").on('change', '#currencyQuotationAdd', function(event) {
   console.log("click en curr")
   $("#QuotationCurrencyData").val($("#currencyQuotationAdd").is(':checked') ? "usd" : "cop");
   showMargen();
});

function productsQuotation(){
    PRODUCTOS_QUOTATION = [];
    $("tr.listado_tabla_ordenada").each(function(){
        var productoInfo = {
            id                  : $(this).data("producto"),
            currency            : $(this).data("currency"),
            disponible          : $(this).data("disponible"),
            cantidadSolicitada  : parseInt($("body").find("."+$(this).data("cantidad")).val())
        }
        PRODUCTOS_QUOTATION.push(productoInfo);
    });
}

function showMargen(validate = null, valor_total = 0, product_id = null){
    var mayor = 0;
    var PRODUCTOS_IDS_ACTUALES = [];


    $("body").find(".valoresCotizacionProductos").each(function(){

        

        var idTr            = $(this).data("trdata");
        var idCantidad      = "."+$(this).data("cantidad");

        const newIdTr       = idTr.replace('calculo_', '');

        var valorCotizacion =  parseFloat($(this).val());
        var productoId      = $(this).data("id");
        var category        = $(this).data("category");
        var factorImport    = $(this).data("factor");
        var categoryName    = $(this).data("categoryname");
        var cost            = $(this).data("price");
        var min             = $(this).data("min");
        var header          = $(this).data("header");
        var discount        = $("#totalDescuento").val();
        var clasetr         = $(this).data("clasetr");
        var typeCost        = $(this).data("typecost");
        var realCost        = $(this).data("realcost");
        var type            = $(this).data("type");
        var cantidad        = $(idCantidad).val();
        var currency        = $("#form_header select#headers").val() == "3" ? "usd" : $(this).data("currency");

        var datosSend = {
            valorCotizacion,
            productoId,
            category,
            categoryName,
            cost,
            min,
            currency,
            header,
            type,
            factorImport,
            typeCost,
            realCost,
            country,
            discount,
            cantidad,
            idTr: newIdTr,
        }
        
        if(currency == "usd" && valorCotizacion >50000){
            mayor = 1;
        }

        PRODUCTOS_IDS_ACTUALES.push(productoId);
        if(product_id != null && product_id == $(this).data("id")){
            $.post(copy_js.base_url+copy_js.controller+'/show_data', datosSend, function(data, textStatus, xhr) {
                $("body").find("#"+idTr).html(data);
                $('[data-toggle="tooltip"]').tooltip()
            });
        }else if (product_id == null) {
            console.log("no llego nada")
            $.post(copy_js.base_url+copy_js.controller+'/show_data', datosSend, function(data, textStatus, xhr) {
                $("body").find("#"+idTr).html(data);
                $('[data-toggle="tooltip"]').tooltip()
            });
        }

        var idQuantity = $(".Cantidad-"+newIdTr+"-"+productoId);

        var modify = $(idQuantity).data('modify');

        if(modify == 1 ){
            var margen = $("body").find("#"+idTr).find("#Margen-"+newIdTr).attr('data-margen');

            if(typeof margen != 'undefined'){
                margen = parseFloat(margen);
                idEnvio = "#"+$(idQuantity).data("entrega")+" option";
                if(margen >= 50){
                    $(idEnvio).each(function(index, el) {
                        if($(this).data('dis') == "1"){
                            $(this).removeAttr('disabled');
                        }
                    });
                }else{
                    $(idEnvio).each(function(index, el) {
                        if($(this).data('dis') == "1"){
                            $(this).attr('disabled','disabled');
                        }
                    });
                }
            }
        }

        console.log(modify)

        // var idEnvio = "#"+$(this).data("entrega")+" option";
            
        // if( cantidad >= min ){
        //     
        // }else{
        //     $(idEnvio).each(function(index, el) {
        //         if($(this).data('dis') == "1"){
        //             $(this).attr('disabled','disabled');
        //         }
        //     });
        // }

        $('[data-toggle="tooltip"]').tooltip()
    });

    if(validate == null){        
        setTimeout(function() {
            if($(".fondo-rojo").length || valor_total > 10000000 || $("#QuotationCurrencyData").val() == "money_COP" ){
                $(".errorMargenReason").show();

                if ($(".fondo-rojo").length || $("#QuotationCurrencyData").val() == "money_COP" ) {
                    $("#razonMenorLb").show();
                }else{
                    $("#razonMenorLb").hide();                    
                }
                // if (valor_total > 10000000) {
                //     $("#razonrazonBoquilla").show();
                //     $(".notas10").each(function(index, el) {
                //         $(this).attr("required", "required");
                //         $(this).show();
                //         $(this).prev("label").show();
                //         $(this).parent("div").addClass('col-md-5');
                //         $(this).val("");
                //     });
                // }else{
                //     $("#razonrazonBoquilla").hide();
                //     $(".notas10").each(function(index, el) {
                //         $(this).removeAttr("required");
                //         $(this).hide();
                //         $(this).prev("label").hide();
                //         $(this).parent("div").removeClass("col-md-5");
                //         $(this).val("");
                //     });                    
                // }

                $("#razonMenor").val("");
                $("#razonMenor").attr("required","required")
            }else {
                $(".errorMargenReason").hide();        
                $("#razonMenor").val("");
                $("#razonMenor").removeAttr('required');
                $(".notas10").each(function(index, el) {
                    $(this).removeAttr("required");
                    $(this).hide();
                    $(this).prev("label").hide();
                    $(this).parent("div").removeClass("col-md-5");
                    $(this).val("");
                });
            }
        }, 3000);
    }

    if(mayor == 1){
        $(".errorUsd").show();
    }else{
        $(".errorUsd").hide();        
    } 
    var id = $("#prospective_users_id").val();
    var type = $("#cliente_cotiza_type").val();
    var cliente = $("#cliente_cotiza_id").val();
    var prospective_users_id = $("#prospective_users_id").val();
    var user = $("#user_cotiza").val();
    $("#htmlOtrasCotizaciones").html("");
    $.post(copy_js.base_url+copy_js.controller+'/get_quotations', {products:PRODUCTOS_IDS_ACTUALES,id,type,cliente,user,prospective_users_id}, function(data, textStatus, xhr) {
        if(data == "COPIA_COTIZACION"){
            alert('Otro asesor está cotizando a este cliente uno o varios productos que intentas cotizar.');
            location.href = copy_js.base_url + 'prospective_users/adviser';
        }else if(data == "IMPORTACION_DUPLICADA"){
            alert('Se encontró uno o más productos del mismo cliente con una importación sin finalizar en otro flujo.');
            location.href = copy_js.base_url + 'prospective_users/adviser';
        }else{
            $("#htmlOtrasCotizaciones").html(data);
        }
    });
}

/*
<p></p>
 */

$("body").on( "click", ".cambioTRM", function() {
    swal({
        title: "¿Estas seguro de solicitar el cambio?",
        text: "¿Por favor ingresa la razón de solicitar el cambio de TRM?",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Descripción"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la razón de solicitar el cambio de TRM","error");
            return false;
        }
        $.post(copy_js.base_url+'Quotations/changeTrm',{razon:inputValue}, function(result){
            message_alert("Solicitud realizada","Bien");
        });
    });
});

$("body").on( "click", ".cambioCosto", function() {
    element = $(this);
    var id        = $(this).data('id');
    swal({
        title: "¿Estas seguro de solicitar el cambio de costo?",
        text: "Por favor ingresa la razón de solicitar el cambio de costo para el producto",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "Descripción"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la razón de solicitar el cambio de costo para el producto","error");
            return false;
        }
        var razon = inputValue;

        var position = -1;

        if (PRODUCTO_IDS.length > 0) {
            for (i in PRODUCTO_IDS){
                if(PRODUCTO_IDS[i] == id){
                    position = i;
                    break;
                }
            }
        }

        if(position == -1){
            PRODUCTO_IDS.push(id);
        }
        element.remove();
        
        $.post(copy_js.base_url+'Quotations/changeCost',{id,razon}, function(result){
            message_alert("Solicitud realizada","Bien");
            element.remove();
        });
    });
});

function sleep(milliseconds) {
 var start = new Date().getTime();
 for (var i = 0; i < 1e7; i++) {
  if ((new Date().getTime() - start) > milliseconds) {
   break;
  }
 }
}

function validateShipping(){
    var existsShip = false;
    $(".tds-references").each(function(index, el) {
        if($(this).attr('data-ref') == 'S-003' || $(this).attr('data-ref') == 'SER-AE'){
            existsShip = true;
        }
    });
    return existsShip;
}

function saveQuotation(){
    sleep(1000);
    var array_ids                   = [];
    var ABRASIVOS_REFS              = ['M-8','M-25','M-60'];

    var gracoClass = 0;

    if($(".graco-class").length){
        $(".body-loading").hide();
        message_alert("Por favor valida, No es posible cotizar productos graco que no tengan costo actualizado del último mes, por favor solicita el cambio de costo.","error");
        return false;
    }

    $("tr.listado_tabla_ordenada").each(function(){
        var saludo                  = this.id;
        var saludoArray             = saludo.split('_');
        var id                      = saludoArray[saludoArray.length - 1];
        array_ids.push(id);
    });


    var continueData = true;

    $(".tds-references").each(function(index, el) {

        var idRefQt = "."+$(this).data("qty");
        if( ABRASIVOS_REFS.indexOf($(this).data('ref')) != -1 && parseInt($(idRefQt).val()) >= 1000 && validateShipping() == false  ){
            
            continueData = false;
        }
    });

    var rolesPemiso = [ 'Gerente General2','Logística' ];

    if(validateShipping() == false && rolesPemiso.indexOf(copy_js.user_role) == -1 ){
        $(".body-loading").hide();
        message_alert("Por favor valida, agrega y valida el servicio de flete a la cotización puede ser con valor $ 0.0 según sea el caso .","error");
        return false;
    }

    if(!continueData){
        $(".body-loading").hide();
        message_alert("Por favor valida, estas cotizando una o más toneladas de silicato y no agregaste el servicio de flete a la cotización.","error");
        return false;
    }

    $(".margendatablock").each(function(index, el) {
        var product     = $(this).data("product");
        var currency    = $(this).data("currency");
        var margen      = $(this).data("margen");
        var idHidden    = "#Margen_"+product+"_"+currency;
        $("body").find(idHidden).val(margen);
    });
    if (array_ids.length == 0) {
        $("tr.listado_tabla_ordenada").each(function(){
            var saludo                  = this.id;
            var saludoArray             = saludo.split('_');
            var id                      = saludoArray[saludoArray.length - 1];
            array_ids.push(id);
        });
    }
    $.post(copy_js.base_url+copy_js.controller+'/orderArray',{array_ids:array_ids}, function(result){});
    calcular_total(true);
    var total                       = $('#totalCalculado').val();
    if (total == 0) {
        $(".body-loading").hide();
        message_alert("Por favor valida, el precio total es igual a 0 pesos","error");
        return false;
    }
    var totalData = 0;
    $("body").find(".valoresCotizacionProductos").each(function(){
        totalData += ( parseFloat($(this).val()) * parseFloat( $(this).parent("td").next("td").find('.cantidadProduct').val() ) ) ;
    });
    if(totalData >= 200000 && $("#form_header select#headers").val() == "3" ){
        $(".body-loading").hide();
        message_alert("Por favor valida, la cotización es en dolares y supera los $200.000 USD","error");
        return false;
    }

    var asunto                  = $('#QuotationName').val();
    if (asunto == '') {
        $(".body-loading").hide();
        message_alert("Por favor valida, No has ingresado el asunto","error");
        return false;
    }
    var conditions              = $('.condiciones_negociacion').html();
    if (conditions == '') {
        $(".body-loading").hide();
        message_alert("Por favor valida, ya que la cotización actual no tiene una condición de negociación","error");
        event.preventDefault();
        return false;
    } else {
        $('#QuotationConditions').val(conditions);
    }

    if( ($(".fondo-rojo").length || $(".boquillaNoImpo").length) && $("#razonMenor").val() == ""){
        $(".body-loading").hide();
        message_alert("Por favor valida, es necesario escribir la razón del porque algunos prodcutos se están cotizando debajo del margen requerido o cotizas boquillas no permitidas","error");
        event.preventDefault();
        return false;
    }

    var prospective_users_id    = $('#prospective_users_id').val();
    var flujo_id                = $('#etapa_id').val();
    var notes                   = $('#introtext').html();
    var notes_descriptiva       = $('#introtext2').html();
    var user_cotiza             = $('#user_cotiza').val();
    var header_option           = $("#form_header select#headers").val();
    var radio_option            = $("#form_quotations input#inlineRadio1").is(":checked") ? $("#form_quotations input#inlineRadio1").val() : $("#form_quotations input#inlineRadio2").val();
    var radio_option_iva        = $("#form_quotations input#inlineRadioIva1").is(":checked") ? $("#form_quotations input#inlineRadioIva1").val() : $("#form_quotations input#inlineRadioIva2").val();
    var customer_note           = $('#QuotationCustomerNote').val();
    var design                  = $('#QuotationDesign').val();
    var datos_producto = new Object();

    for (x=0;x<array_ids.length;x++){
        datos_producto["Precio-"+array_ids[x]]          = $(".Precio-"+array_ids[x]).length ? $(".Precio-"+array_ids[x]).val() : 0;
        datos_producto["Cantidad-"+array_ids[x]]        = $(".Cantidad-"+array_ids[x]).val();
        datos_producto["Entrega-"+array_ids[x]]         = $(".Entrega-"+array_ids[x]).val();
        datos_producto["Moneda-"+array_ids[x]]          = $(".Moneda-"+array_ids[x]).val();
        datos_producto["IVA-"+array_ids[x]]             = $(".IVA-"+array_ids[x]).val();
        var claseNotaInput = ".Nota-"+array_ids[x];
        datos_producto["Nota-"+array_ids[x]]            = $(claseNotaInput).val();
    }

    $('#QuotationHeaderId').val(header_option);
    $('#QuotationNotesDescription').val(notes_descriptiva);
    $('#QuotationNotes').val(notes);

    if (header_option == '') {
        $(".body-loading").hide();
        message_alert("Por favor valida, no se ha seleccionado un banner","error");
        event.preventDefault();
        return false;
    }
    console.log(datos_producto)

    var otroArray = jQuery.makeArray(datos_producto);

    if($(".costo-cero").length){
        $(".body-loading").hide();
        message_alert("Existen productos que el costo es cero, no se podrán cotizar con este costo, por favor solicita cambio de costo y espera que se aplique para poder continuar.","error");
        event.preventDefault();
        return false;
    }

    if($(".error_wo").length){
        $(".body-loading").hide();
        message_alert("Por favor valida, existen productos cotizados en pesos que no cumplen el margen necesario y no es posible continuar.","error");
        event.preventDefault();
        return false;
    }

    if(bloqueo == 1 && $(".fondo-rojo").length){
        $(".body-loading").hide();
        message_alert("Por favor valida que todos los productos tengan un margen mínimo necesario","error");
        event.preventDefault();
        return false;
    }

    // if(bloqueo == 0 && $(".fondo-rojo").length && (PRODUCTO_IDS.length != $(".fondo-rojo").length || PRODUCTO_IDS.length == 0) && requestChange == 0){
    //     $(".body-loading").hide();
    //    message_alert("Por favor valida, a uno o más productos se les debe solicitar cambio de costo ya que, no cumplen con el margen mínimo, luego de solicitar el cambio pordrás continuar con tu cotización.","error");
    //    event.preventDefault();
    //    return false; 
    // }
    $(".body-loading").show();

    var descuento = $("#totalDescuento").val();

    var datosSend = {
        header_option:header_option,
        notes_descriptiva:notes_descriptiva,
        customer_note: customer_note,
        notes:notes,
        user_cotiza:user_cotiza,
        descuento: descuento,
        conditions:conditions,
        prospective_users_id:prospective_users_id,
        flujo_id:flujo_id,
        asunto:asunto,
        total:total,
        design:design,
        radio_option:radio_option,
        radio_option_iva:radio_option_iva,
        array_ids:array_ids,
        currency:$("#currencyQuotationAdd").is(':checked') ? "usd" : "cop",
        datos_producto:JSON.stringify(otroArray)
    };

    // $("#QuotationCurrencyData").val($("#currencyQuotationAdd").is(':checked') ? "usd" : "cop");

    if(otroArray.length <= 0 || array_ids.length <= 0){
      $(".body-loading").hide();
       message_alert("Por favor valida, existen errores en la cotización.","error");
       event.preventDefault();
       return false; 
    }

    $.post(copy_js.base_url+copy_js.controller+'/preview_quotation',datosSend, function(result){
        $('#modal_previsualizar_body').html(result);
        $('#modal_previsualizar_label').text('Previsualización de la cotización');
        $('#modal_previsualizar').modal('show');
        $(".body-loading").hide();
    }).fail(function() {
       $(".body-loading").hide();
       message_alert("Por favor valida, existen errores en la cotización.","error");
       event.preventDefault();
       return false; 
    });

}

$("#form_quotations").submit(function( event ) {

    $(".body-loading").show();
    var arrBoquillas = [];

    if ($(".boquillaNoImpo").length) {
        $(".boquillaNoImpo").each(function(index, el) {
            arrBoquillas.push($(this).data("part"));
        });
    }

    if (arrBoquillas.length > 0) {
        $(".body-loading").hide();

        swal({

            "title":"Advertencia",
            "text":"La(s) boquilla(s) "+arrBoquillas.join(',')+" no se encuentra(n) en el listado de permitidas para la venta, debes informarle al cliente el precio full ya que no se tendrá ningún descuento o sugerirle comprar otra. ",
            "icon":"warning"
            }, function (value){
                saveQuotation();
            }
        )
    }else{
        saveQuotation();
    }

    
    event.preventDefault();
});

$("#btn_guardar_previsualizar").click(function() {
    document.formulario1.submit()
});

$("#btn_borrador").click(function() {

    var notes                   = $('#introtext').html();
    var notes_descriptiva       = $('#introtext2').html();
    var header_option           = $("#form_header select#headers").val();
    var conditions              = $('.condiciones_negociacion').html();
    var currency                = $('#money_format').val();

    $('#QuotationHeaderId').val(header_option);
    $('#QuotationNotesDescription').val(notes_descriptiva);
    $('#QuotationNotes').val(notes);
    $('#QuotationConditions').val(conditions);
    $('#QuotationHeaderId').val(header_option);
    $('#QuotationCurrencyData').val(currency);

    $("#btn_borrador").hide();
    var formData            = new FormData($('#form_quotations')[0]);
    $.ajax({
        type: 'POST',
        url: copy_js.base_url+copy_js.controller+'/saveBorradorQuotation',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            if (result != 0) {
                swal("Borrador guardado con exito");
            } else {
                message_alert("No se pudo guardar el borrador, por favor intentalo mas tarde","error");
            }
            $("#btn_borrador").show();
        }
    });
});

function saveBorradorQuotation(callback){
    var notes                   = $('#introtext').html();
    var notes_descriptiva       = $('#introtext2').html();
    var header_option           = $("#form_header select#headers").val();
    var conditions              = $('.condiciones_negociacion').html();
    var currency                = $('#money_format').val();

    $('#QuotationHeaderId').val(header_option);
    $('#QuotationNotesDescription').val(notes_descriptiva);
    $('#QuotationNotes').val(notes);
    $('#QuotationConditions').val(conditions);
    $('#QuotationHeaderId').val(header_option);
    if($('#QuotationCurrencyData').val() == ''){
        $('#QuotationCurrencyData').val( currency == "0" ? "" : "money_"+currency);
    }
    var formData            = new FormData($('#form_quotations')[0]);

    var array_ids                   = [];
    $("tr.listado_tabla_ordenada").each(function(){
        var saludo                  = this.id;
        var saludoArray             = saludo.split('_');
        var id                      = saludoArray[saludoArray.length - 1];
        array_ids.push(id);
    });
    $.post(copy_js.base_url+copy_js.controller+'/orderArray',{array_ids:array_ids, noSleep: 1}, function(result){
        $.ajax({
            type: 'POST',
            url: copy_js.base_url+copy_js.controller+'/saveBorradorQuotation',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(result){
                if(callback){
                    callback()
                }
            }
        });
    });

    
}



$("#template").change(function() {
    var template_id = $('#template').val();
    if (template_id != '') {
        $.post(copy_js.base_url+'Templates/find_products_template',{template_id:template_id, header: $("#form_header select#headers").val(),money: $("#form_header select#money_format").val()}, function(result){
            $('#milista').empty();
            $('#milista').html(result);
            message_alert("La plantilla se ha cargado satisfactoriamente","Bien");
            showMargen();
            calcular_total();
        });
    }
});

$('#btn_buscar_flujo').click(function(e) {
    var id_flujo_buscar     = $('#txt_buscar_flujo').val();
    if (id_flujo_buscar != '') {
        id_flujo_buscar         = parseInt(id_flujo_buscar);
        if (!isNaN(id_flujo_buscar)) {
            calcular_total();
            $.post(copy_js.base_url+copy_js.controller+'/find_cotizaciones_option_flujo',{id_flujo_buscar:id_flujo_buscar}, function(result){
                if (result != 0) {
                    $('#cotizaciones_flujo').append(result);
                    $('#cotizaciones_flujo').prop( "disabled",false);
                    message_alert("Una cotización o más se han encontrado satisfactoriamente","Bien");
                } else {
                    message_alert("No se encontraron datos para el ID del flujo introducido","error");
                }
            });
        } else {
            message_alert("Por favor valida, el texto ingresado no es númerico","error");
        }
    }
});



$('#QuotationCustomerNote').summernote(
    {
      height: 70,
      toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['misc', ['undo', 'redo','codeview']],
        ['link', ['linkDialogShow', 'unlink']]
      ],
      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
      focus: false,
      disableResizeEditor: true,
      disableDragAndDrop: true
    }
);

$("body").on('paste', '#form_quotations .note-editable,#form_quotations .note-editor', function(event) {
    event.preventDefault();
});

$("#cotizaciones_flujo").change(function() {
    var cotizacion_id           = $('#cotizaciones_flujo').val();
    if (cotizacion_id != '') {
        $.post(copy_js.base_url+'Quotations/getInformationQuotation',{cotizacion_id:cotizacion_id}, function(resultInf){
            var resultado = $.parseJSON(resultInf);
            $('#QuotationName').val(resultado.Quotation.name);
            $("#form_header select#headers").val(resultado.Quotation.header_id);
            if (resultado.Quotation.notes == '') {
                $('#introtext').html('<br/>');
            } else {
                $('#introtext').html(resultado.Quotation.notes);
            }
            if (resultado.Quotation.notes_description == '') {
                $('#introtext2').html('<br/>');
            } else {
                $('#introtext2').html(resultado.Quotation.notes_description);
            }
            $("#introtext").css("display", "block");
            $("#introtext2").css("display", "block");
            $('.condiciones_negociacion').html(resultado.Quotation.conditions);
            $.post(copy_js.base_url+'Quotations/find_products_cotizacion',{cotizacion_id:cotizacion_id, header: $("#form_header select#headers").val(), money: $("#form_header select#money_format").val(),type: $("#QuotationType").val()}, function(result){             
                $('#milista').empty();
                $('#milista').html(result);
                message_alert("La cotización se ha cargado satisfactoriamente","Bien");
                calcular_total();

                setTimeout(function() {
                    console.log("SE ejecuto")
                    showMargen();
                    saveBorradorQuotation();
                }, 5000);

            });
        });
    }
});

$("#cotizaciones_creadas").change(function() {
    var cotizacion_id           = $('#cotizaciones_creadas').val();
    if (cotizacion_id != '') {
        $.post(copy_js.base_url+'Quotations/getInformationQuotation',{cotizacion_id:cotizacion_id}, function(resultInf){
            var resultado = $.parseJSON(resultInf);
            $('#QuotationName').val(resultado.Quotation.name);
            $("#form_header select#headers").val(resultado.Quotation.header_id);
            if (resultado.Quotation.notes == '') {
                $('#introtext').html('<br/>');
            } else {
                $('#introtext').html(resultado.Quotation.notes);
            }
            if (resultado.Quotation.notes_description == '') {
                $('#introtext2').html('<br/>');
            } else {
                $('#introtext2').html(resultado.Quotation.notes_description);
            }
            $("#introtext").css("display", "block");
            $("#introtext2").css("display", "block");
            $('.condiciones_negociacion').html(resultado.Quotation.conditions);
            $.post(copy_js.base_url+'Quotations/find_products_cotizacion',{cotizacion_id:cotizacion_id, header: $("#form_header select#headers").val(), money: $("#form_header select#money_format").val(), type: $("#QuotationType").val()}, function(result){             
                $('#milista').empty();
                $('#milista').html(result);
                message_alert("La cotización se ha cargado satisfactoriamente","Bien");
                calcular_total();

                setTimeout(function() {
                    console.log("SE ejecuto")
                    showMargen();
                    saveBorradorQuotation();
                }, 5000);

            });
        });
    }
});

$('#btn_delete_cache').click(function(e) {
    $.post(copy_js.base_url+copy_js.controller+'/deleteCacheProducts',{}, function(result){
        location.reload(true);
    });
});

$('#details-country tbody').on( "click", ".deleteProduct", function() {
    var product_id              = $(this).data('uid');

    if ($("#form_header select#headers").length) {
        position        = -1;
        var elemento = $(this);
        for (i in PRODUCTO_IDS){
            if(PRODUCTO_IDS[i] == product_id){
                position = i;
                break;
            }
        }

        position = 0;
        if(position != -1){
            PRODUCTO_IDS.splice(position, 1);
        }

        var claseRemove             = $(this).data("clase");
        elemento.parent("td").parent("tr").next("tr").remove();
        elemento.parent("td").parent("tr");
        $("body").find("."+claseRemove).remove();
        $('#totalCalculado').val('Cargando');
        productsQuotation();
        setTimeout(function(){
            calcular_total();
        }, 2000);
    }else{
        var trId = "#tr_"+product_id;
        $(trId).remove();
        delete_product_session(product_id);
    }

    showMargen();

    $(".tooltip").each(function(index, el) {
        $(this).remove();
    });
});

$("body").on('click', '.viewSugesstions', function(event) {
    event.preventDefault();
    $("#bodySuggestion").html("");
    var idProduct = $(this).data("uid");
    var claseHtml = ".suggestions_"+idProduct;
    $("#bodySuggestion").html($(claseHtml).html());
    $("#modalSugestions").modal("show");
});

$("body").on('click', '.addToQt', function(event) {
    event.preventDefault();
    var id = $(this).data("id");

    var currency = $("#currencyQuotationAdd").is(':checked') ? "usd" : "cop";
    $.post(copy_js.base_url+'Products/get_data_quotation',{id:id,header:$("#form_header select#headers").val(), products: PRODUCTOS_QUOTATION, money: $("#form_header select#money_format").val(), type: $("#QuotationType").val() }, function(result){
        if (result == 'NOT_QT') {
            message_alert("No es posible cotizar esta referencia, no estas autorizado por favor comunícate con gerencia para pedir autorización.","error");
            return false;
        }
        if (result == 'bloqueo') {
            message_alert("No es posible agregar esta referencia, el producto se encuentra bloqueado por favor informar al área encargada.","error");
            return false;
        } if(result == "TOTAL_PRODUCTS"){
            message_alert("No es posible agregar más de 2 veces esta referencia, ya que está cotizada en pesos y dolares","error");
        }else if (result == "TOTAL_USD") {
            message_alert("No es posible agregar esta referencia, no tiene posee inventario y ya fue cotizada en dolares","error");
        }else{
            var num = parseInt(result);
            if (num > 0) {
                update_cantidad_tr(num);
            } else {
                if (result == 'editar') {
                    if(!editProduct){
                        message_alert("No es posible agregar esta referencia, debe ser editada y no tienes permiso de editar productos. Solicitalo al área encargada por favor.","error");
                        swal({
                            title: "¿Estas seguro de solicitar la edición de este producto?",
                            text: "Por favor ingresa la razón de solicitar la edición para el producto",
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
                                message_alert("Por favor ingresa la razón de solicitar el cambio de costo para el producto","error");
                                return false;
                            }
                            var razon = inputValue;
                            
                            $.post(copy_js.base_url+'Products/changeProduct',{id,razon}, function(result){
                                message_alert("Solicitud realizada correctamente a las áreas encargadas","Bien");
                            });
                        });
                    }else{
                        edit_product_maxDescripcion(id);
                    }
                } else {
                    $('#milista').append(result);
                    productsQuotation();
                    $("#modalSugestions").modal("hide");
                    calcular_total();
                    saveBorradorQuotation();
                }
            }
        }

        
        calcular_total();
    });

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

$('#details-country tbody').on( "click", ".editarProduct", function() {
    var product_id        = $(this).data('uid');
    $(".body-loading").show();
    $.post(copy_js.base_url+'Products/form_quotation',{product_id:product_id,action:'edit'}, function(result){
        $('#modal_form_products_body').html(result);
        $('#modal_form_products_label').text('Editar producto');
        $('#modal_form_products').modal('show');
        $('#btn_action_products').text('Editar producto');
        inputPrecioEditAdd();
        $("body").find('#ProductDescriptionForm').summernote({
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
                    var NUMEROCARACTERES     = strip_tags( e.currentTarget.innerText ).length;
                    $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
                    $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
                }
            }
        });
        var NUMEROCARACTERES       = strip_tags($('.note-editable').text()).length;
        $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
        $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
        
        $("#category_1").val($("#category1Select").val());
        $("#category_1").trigger('change');
        $(".body-loading").hide();
        $("#ProductBrand").select2({
            dropdownParent: $("#modal_form_products")
        });
        // $('#ProductPartNumber').flexdatalist({
        //      minLength: 1,
        //      noResultsText : 'El número de parte no existe y es posible ingresarlo',
        //      maxShownResults: 20
        // });
    });
});

$("#btn_registrar_products").click(function() {
    $(".body-loading").show();
    $.post(copy_js.base_url+'Products/form_quotation',{action:'add'}, function(result){
        $('#modal_form_products_body').html(result);
        $('#modal_form_products_label').text('Registrar producto');
        $('#modal_form_products').modal('show');
        $("body").find('#ProductDescriptionForm').summernote({
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
                    var NUMEROCARACTERES     = strip_tags( e.currentTarget.innerText ).length;
                    $('#lbl_caracteres_faltantes').text(500 - NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
                    $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
                    color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
                }
            }
        });

        $('#ProductPartNumber').flexdatalist({
             minLength: 1,
             noResultsText : 'El número de parte no existe y es posible ingresarlo',
             maxShownResults: 20
        });

        $(".body-loading").hide();
        $("#ProductBrand").select2({
            dropdownParent: $("#modal_form_products")
        });
    });
});

$("#btn_action_products").click(function(event) {
    $(".body-loading").show();

    var formData            = new FormData($('#formuploadajax')[0]);
    var product_id          = $('#ProductId').val();
    var part_number         = $('#ProductPartNumber').val();
    var name                = $('#ProductName').val();
    var description         = $('#ProductDescriptionForm').val();
    var list_price_usd      = $('#ProductListPriceUsd').val();
    var link                = $('#ProductLink').val();
    var bran                = $('#ProductBrand').val();
    var category_1          = $('#category_1').val();
    var categoryOne         = $( "#category_1 option:selected" ).text();
    var category_2          = $('#category_2').val();
    var category_3          = $('#category_3').val();
    var category_4          = $('#category_4').val();
    var NUMEROCARACTERES    = strip_tags($('#formuploadajax .note-editable').text()).length;
    if (name != '' && part_number != '' && list_price_usd != '') {
        var r                   = limit_characters(NUMEROCARACTERES,500);

        var numero_parte              = part_number;

        $.post(copy_js.base_url+'Products/validExistencia',{numero_parte:$.trim(numero_parte), id: product_id}, function(result){
          if (result == 0) {            
            if (r == false) {
                $(".body-loading").hide();
                message_alert("Por favor valida, has excedido el máximo número de caracteres en la descripción","error");
            }else if(bran == "1"){
                $(".body-loading").hide();
                message_alert("Por favor selecciona una marca diferente a N/A","error");
            }else if(bran == "13" && categoryOne.toLowerCase() != "servicio" ){
                $(".body-loading").hide();
                message_alert("Por favor selecciona una marca diferente a Kebco","error");
            }else if(category_1 == ""){
                $(".body-loading").hide();
                message_alert("Debe seleccionar una categoría.","error");
            }else if((category_1 == "" || category_2 == "" || category_3 == ""  || category_4 == "") && copy_js.user_role != "Gerente General"){
                $(".body-loading").hide();
                message_alert("Todas las categorías son requeridas.","error");
            } else {
                $(".body-loading").show();
                $("#btn_action_products").hide();
                $.ajax({
                    type: 'POST',
                    url: copy_js.base_url+'Products/saveFormQuotation',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(result){
                        result = $.trim(result);
                        $("#btn_action_products").show();
                        if (result != "5" && result != "4" && result != "3") {
                            if (copy_js.controller_menu == 'TEMPLATES') {
                                if (copy_js.action == 'edit') {
                                    edit_product(product_id);
                                } else {
                                    add_product(product_id);
                                }
                                message_alert("Producto actualizado satisfactoriamente","Bien");
                                calcular_total();
                                $('#modal_form_products').modal('hide');
                                $("#btn_action_products").show();
                            }else{
                                $("#btn_action_products").show();
                                if (product_id != '') {
                                    delete_product_session(product_id);
                                    productsQuotation();
                                    setTimeout(function(){
                                        if(editProduct){
                                            var currency = $("#currencyQuotationAdd").is(':checked') ? "usd" : "cop";
                                            $.post(copy_js.base_url+'Products/get_data_quotation',{id:product_id,header:$("#form_header select#headers").val(), products: PRODUCTOS_QUOTATION,type: $("#QuotationType").val()}, function(result){
                                                $('#milista').append(result);
                                                $("#btn_action_products").show();
                                            });
                                            message_alert("Producto actualizado satisfactoriamente","Bien");
                                        }else{
                                            message_alert("Producto actualizado satisfactoriamente, pero no podrá ser usado en esta cotización, solicita que sea desbloqueado al área encargada","error");
                                            load_data_search();
                                        }
                                        $('#modal_form_products').modal('hide');
                                        $(".body-loading").hide();
                                        productsQuotation();
                                    }, 4000);
                                    calcular_total();
                                } else {
                                    setTimeout(function(){
                                        if(addProduct){
                                            var currency = $("#currencyQuotationAdd").is(':checked') ? "usd" : "cop";
                                            $.post(copy_js.base_url+'Products/get_data_quotation',{id:result,header:$("#form_header select#headers").val(), products: PRODUCTOS_QUOTATION,type: $("#QuotationType").val()}, function(result){
                                                $('#milista').append(result);
                                                $("#btn_action_products").show();
                                            });
                                            message_alert("Producto creado satisfactoriamente","Bien");
                                        }else{
                                            message_alert("Producto creado satisfactoriamente, pero no podrá ser usado en esta cotización, solicita que sea desbloqueado al área encargada","error");
                                            load_data_search();
                                        }
                                        
                                        $('#modal_form_products').modal('hide');
                                        $(".body-loading").hide();
                                        productsQuotation();
                                    }, 4000);
                                }
                                load_data_search();
                                calcular_total();
                            }

                            
                        } else {
                            validate_image_message(result);
                            $("#btn_action_products").show();
                            $(".body-loading").hide();
                            calcular_total();
                        }
                    }
                });
            }

          } else {
            $(".body-loading").hide();
            message_alert("El número de parte ya esta registrado","error");
          }
        });
        

        
    } else {
        $(".body-loading").hide();
        message_alert("Los campos son requeridos","error");
    }
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


$('body').on("change", ".cantidadProduct", function() {
    var product_id      = $(this).data('uid');

    var is_ref          = $(this).data('ref');
    var reftype          = $(this).data('reftype');
    var idCons          = '#tr_'+product_id+' .cantidad .cantidadProduct';
    const cantidad      = $(idCons).val();

    if(is_ref == 1){        

        let value = parseInt($(idCons).val(), 10);

        if (value <= 0) {
            // Si el valor es 0 o menor, se ajusta a 25
            $(idCons).val(25);
            cantidad = 25;
        } else if (value % 25 !== 0) {
            // Si no es múltiplo de 25, redondear al múltiplo más cercano
            let closestValue = Math.round(value / 25) * 25;
            $(idCons).val(closestValue);
            cantidad = closestValue;
        }

        var cantidadValida = cantidad;

        if(cantidadValida > 1400){ cantidadValida = 1400; }
        if(cantidadValida < 1){  cantidadValida = 1;  }

        var indexVal = pricesAbrasivo.findIndex( abrasivo => abrasivo.Abrasivo.kgs == cantidadValida && abrasivo.Abrasivo.type == reftype );

        if(indexVal != -1 && typeof indexVal != 'undefined'){
            $('#tr_'+product_id+' .precio #precio_item').val(pricesAbrasivo[indexVal].Abrasivo.unit_price / 25);
        }

    }

    console.log(is_ref);

    if (cantidad == '') {
       var cantidadFinal = 0;
    } else {
       var cantidadFinal = parseFloat(cantidad);
    }
    var precio_db       = $('#tr_'+product_id+' .precio #precio_item').val();
    if (precio_db == '') {
        precio_db           = 0;
        var precio          = 0;
    } else {
        // precio_db           = precio_db.replace(/\,/g, "");
        // precio_db           = precio_db.replace(/\./g, "");
        var precio          = precio_db;
    }
    var total_producto  = cantidadFinal*precio;
    // total_producto      = number_format(total_producto);
    $('#tr_'+product_id+' .subtotal').text(formatter.format(total_producto));
    showMargen(null,0,$(this).parent('td').parent('tr').attr("data-producto"));
    calcular_total();
    productsQuotation();

    var min = $(this).data('min');
    var modify = $(this).data('modify');

    if(min > -1 && modify == 1){
        var idEnvio = "#"+$(this).data("entrega")+" option";
            
        // if( cantidad >= min ){
        //     $(idEnvio).each(function(index, el) {
        //         if($(this).data('dis') == "1"){
        //             $(this).removeAttr('disabled');
        //         }
        //     });
        // }else{
        //     $(idEnvio).each(function(index, el) {
        //         if($(this).data('dis') == "1"){
        //             $(this).attr('disabled','disabled');
        //         }
        //     });
        // }

    }

});

$('body').on("keyup", "#ProductListPriceUsd", function(event) {
    var precio              = $('#ProductListPriceUsd').val();
    var precio_final        = number_format(precio);
    $('#ProductListPriceUsd').val(precio_final);
});


$('body').on("keyup change", "#precio_item", function(event) {
    var product_id      = $(this).data('uid');
    var cantidad        = $('#tr_'+product_id+' .cantidad .cantidadProduct').val();
    if (cantidad == '') {
        cantidad = 0;
    } else {
        cantidad                = parseFloat(cantidad);
    }
    var precio_db              = $('#tr_'+product_id+' .precio #precio_item').val();

    if(!validateDecimal(precio_db)){
        event.preventDefault();
        $('#tr_'+product_id+' .precio #precio_item').val(string_valdate_Number(precio_db,"."))
        
        return false;
    }

    if (precio_db == '') {
        precio_db               = 0;
        var precio              = 0;
    } else {
        // precio_db               = precio_db.replace(/\,/g, "");
        // precio_db               = precio_db.replace(/\./g, "");
        var precio              = precio_db;
    }
    var total_producto  = cantidad*precio;
    console.log(total_producto);
    showMargen(null,0,$(this).attr('data-id'));
    console.log('se llama el product_id '+$(this).attr('data-id'))
    // total_producto      = number_format(total_producto);
    // if (precio_db !=0) {
    //     $('#tr_'+product_id+' .precio #precio_item').val(number_format(precio_db));
    // }
    $('#tr_'+product_id+' .subtotal').text(formatter.format(total_producto));
    calcular_total();
});

function inputPrecioEditAdd(){
    var precio              = $('#ProductListPriceUsd').val();
    var precio_final        = number_format(precio);
    $('#ProductListPriceUsd').val(precio_final);
}

function find_product(name){
    if (copy_js.controller_menu == 'TEMPLATES') {
         $.post(copy_js.base_url+'Products/findName',{name:name, quotationView:1}, function(id){
            var currency = $("#currencyQuotationAdd").is(':checked') ? "usd" : "cop";
            $.post(copy_js.base_url+'Products/get_data_quotation',{id:id, currency:currency,type: $("#QuotationType").val()}, function(result){
                console.log(result);
                if (result == 'bloqueo') {
                    message_alert("No es posible agregar esta referencia, el producto se encuentra bloqueado por favor informar al área encargada.","error");
                }else if (result == 'editar') {
                    edit_product_maxDescripcion(id);
                }else{
                    if (copy_js.action == 'edit') {
                        edit_product(id);
                    } else {
                        add_product(id);
                    }
                }
            });
            
        });
    } else {
        $.post(copy_js.base_url+'Products/findName',{name:name,quotationView:1}, function(id){

            if ($.trim(id) == "REQUIERE_GARANTIA") {
                message_alert("No es posible agregar esta referencia, el producto requiere tener una garantia asignada al proveedor, comunícate con Logística o Dirección comercial","error");
                return false;
            }

            var currency = $("#currencyQuotationAdd").is(':checked') ? "usd" : "cop";
            $.post(copy_js.base_url+'Products/get_data_quotation',{id:id,header:$("#form_header select#headers").val(), products: PRODUCTOS_QUOTATION, money: $("#form_header select#money_format").val(), type: $("#QuotationType").val()}, function(result){
                if (result == 'bloqueo') {
                    message_alert("No es posible agregar esta referencia, el producto se encuentra bloqueado por favor informar al área encargada.","error");
                    return false;
                } if(result == "TOTAL_PRODUCTS"){
                    message_alert("No es posible agregar más de 2 veces esta referencia, ya que está cotizada en pesos y dolares","error");
                }else if (result == "TOTAL_USD") {
                    message_alert("No es posible agregar esta referencia, no tiene posee inventario y ya fue cotizada en dolares","error");
                }else{
                    var num = parseInt(result);
                    if (num > 0) {
                        update_cantidad_tr(num);
                    } else {
                        if (result == 'editar') {
                            if(!editProduct){
                                message_alert("No es posible agregar esta referencia, debe ser editada y no tienes permiso de editar productos. Solicitalo al área encargada por favor.","error");
                                swal({
                                    title: "¿Estas seguro de solicitar la edición de este producto?",
                                    text: "Por favor ingresa la razón de solicitar la edición para el producto",
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
                                        message_alert("Por favor ingresa la razón de solicitar el cambio de costo para el producto","error");
                                        return false;
                                    }
                                    var razon = inputValue;
                                    
                                    $.post(copy_js.base_url+'Products/changeProduct',{id,razon}, function(result){
                                        message_alert("Solicitud realizada correctamente a las áreas encargadas","Bien");
                                    });
                                });
                            }else{
                                edit_product_maxDescripcion(id);
                            }
                        } else {
                            $('#milista').append(result);
                            productsQuotation();
                            $(".cantidadProduct").trigger('change')
                        }
                    }
                }

                
                calcular_total();
                showMargen(null,0,id);
                saveBorradorQuotation();
            });
        });
    }
}

function getCountProducts(){

}

function edit_product_maxDescripcion(idProduct){
    message_alert("Para poder agregar el producto a la cotización, por favor edita la descripción si excede el máximo de caracteres o la marca ya que, no puede ser N/A o Kebco","error");
    var product_id        = idProduct; 
    $.post(copy_js.base_url+'Products/form_quotation',{product_id:product_id,action:'edit'}, function(result){
        $('#modal_form_products_body').html(result);
        $('#modal_form_products_label').text('Editar producto');
        $('#modal_form_products').modal('show');
        $('#btn_action_products').text('Editar producto');
        inputPrecioEditAdd();
        $('#ProductDescriptionForm').summernote({
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
}

function update_cantidad_tr(product_id){
    var valorCantidad   = parseInt($('#tr_'+product_id+' .cantidad .cantidadProduct').val());
    var cantidadFinal   = valorCantidad + 1;
    var precio_db       = $('#tr_'+product_id+' .precio #precio_item').val();
    if (precio_db == 'undefined') {
        var mensaje         = "Por favor borra los datos de caché del pedido de la cotización";
        var tipo            = "error";
        message_alert(mensaje,tipo);
    } else {
        precio_db           = precio_db.replace(/\,/g, "");
        precio_db           = precio_db.replace(/\./g, "");
        var precio          = parseFloat(precio_db);
        var total_producto  = cantidadFinal*precio;
        total_producto      = number_format(total_producto);
        $('#tr_'+product_id+' .cantidad .cantidadProduct').val(cantidadFinal);
        $('#tr_'+product_id+' .subtotal').text(total_producto);

    }
}

function delete_product_session(product_id){
     if (copy_js.controller_menu == 'TEMPLATES') {
        $.post(copy_js.base_url+'Products/deleteProductTemplate',{product_id:product_id}, function(result){
            $('#tr_'+product_id).remove();
        });
    } else {
        $("tr.listado_tabla_ordenada").each(function(){
            console.log($(this).data("producto"), product_id)
            if($(this).data("producto") == product_id){
                var claseRemove         = $(this).data("clase");
                $("body").find("."+claseRemove).remove();
                $(this).remove();
            }
        });
        saveBorradorQuotation();
        // $.post(copy_js.base_url+'Products/deleteProductCotizacion',{product_id:product_id}, function(result){
        //     $('#tr_'+product_id).remove();
        // });
    }
}

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

function load_data_borrador(){
    var flujo_id = $('#prospective_users_id').val();
    var etapa_id = $('#etapa_id').val();
    $.post(copy_js.base_url+copy_js.controller+'/find_products_borrador',{etapa_id:etapa_id, header: $("#form_header select#headers").val(),money: $("#form_header select#money_format").val(), type: $("#QuotationType").val()}, function(result){
        $.post(copy_js.base_url+copy_js.controller+'/getDataBorrador',{flujo_id:flujo_id}, function(resultInf){
            if (resultInf != false) {
                var resultado = $.parseJSON(resultInf);

                if(resultado.DraftInformation.currency != '' && resultado.DraftInformation.currency != null && $("#QuotationCurrencyData").val() == ''){
                    location.href = actual_uri+"?money="+resultado.DraftInformation.currency.replace("money_","");
                }

                $('#QuotationName').val(resultado.DraftInformation.name);
                $('#QuotationShow').val(resultado.DraftInformation.show);
                $('#user_cotiza').val(resultado.DraftInformation.user_id);
                $('#QuotationShowShip').val(resultado.DraftInformation.show_ship);


                if (resultado.DraftInformation.notes == '') {
                    $('#introtext').html('<br/>');
                } else {
                    $('#introtext').html(resultado.DraftInformation.notes);
                }
                if (resultado.DraftInformation.notes_description == '') {
                    $('#introtext2').html('<br/>');
                } else {
                    $('#introtext2').html(resultado.DraftInformation.notes_description);
                }
                 if (resultado.DraftInformation.header_id != '0' && resultado.DraftInformation.header_id != '') {
                    $("#form_header select#headers").val(resultado.DraftInformation.header_id);
                } else {
                    $("#form_header select#headers").val("1");
                }

                if (resultado.DraftInformation.customer_note == '') {
                    $('#QuotationCustomerNote').html('<br/>');
                } else {
                    $("#QuotationCustomerNote").summernote("code",resultado.DraftInformation.customer_note);
                }

                if (resultado.DraftInformation.show_iva == '1') {
                    $('#inlineRadioIva1').attr('checked',true);
                    $('#inlineRadioIva2').removeAttr('checked');
                } else {
                    $('#inlineRadioIva2').attr('checked',true);
                    $('#inlineRadioIva1').removeAttr('checked');
                }

                if (resultado.DraftInformation.total_visible == '1') {
                    $('#inlineRadio1').attr('checked',true);
                    $('#inlineRadio2').removeAttr('checked');
                } else {
                    $('#inlineRadio2').attr('checked',true);
                    $('#inlineRadio1').removeAttr('checked');
                }
                
                $("#introtext").css("display", "block");
                $("#introtext2").css("display", "block");
                $('.condiciones_negociacion').html(resultado.DraftInformation.conditions);
                message_alert("Hemos encontrado un borrador activo para la cotización de este negocio","Bien");
                showMargen();
                calcular_total();
                setInterval(saveBorradorQuotation, 60000);
            }
        });
        if (result != 0) {
            $('#milista').empty();
            $('#milista').html(result);
        }
    });
}

function header_option(){
    var radio_option    = $("#form_header select#headers").val();
    if(radio_option == ""){
        $(".bannequotation").html("");
        $(".bannequotationfooter").html("");
        showMargen()
        return false;
    }
    $.post(copy_js.base_url+'Headers/data_header',{radio_option:radio_option}, function(result){
        result = $.parseJSON(result);
        console.log(result)
        $(".bannequotation").html(result.imgHeader);
        $(".bannequotationfooter").html(result.urlfooter);
        showMargen()

        if(radio_option == "3"){           

             $("tr.listado_tabla_ordenada").each(function(){
                currency = $(this).data("currency");
                referencia = $(this).data("reference");

                if(currency != "usd"){
                    var claseRemove         = $(this).data("clase");
                    $("body").find("."+claseRemove).remove();
                    $(this).remove();
                    find_product(referencia);
                }

            });
        }

    });
    
}

$("#form_header select#headers").change(function () {
    header_option();
});

// $('#form_quotations').bind("keypress", function(e) {
//   if (e.keyCode == 13) {               
//     e.preventDefault();
//     return false;
//   }
// });
// 
// 


$("body").on('change', 'select#notasPreviasInput', function(event) {
    if(!$(this).val() == ""){
        id = $(this).val();
        $.post(copy_js.base_url+'Notes/getNote', {id}, function(data, textStatus, xhr) {
            $("p#introtext").html(data.Note.description);
            $("p#introtext").show();
            saveBorradorQuotation();
        },"json");
    }
});

$("body").on('change', 'select#notasDescriptivasInput', function(event) {
    if(!$(this).val() == ""){
        id = $(this).val();
        $.post(copy_js.base_url+'Notes/getNote', {id}, function(data, textStatus, xhr) {
            $("p#introtext2").html(data.Note.description);
            $("p#introtext2").show();
            saveBorradorQuotation();
        },"json");
    }
});

$("body").on('change', 'select#condicionesInput', function(event) {
    if(!$(this).val() == ""){
        id = $(this).val();
        $.post(copy_js.base_url+'Notes/getNote', {id}, function(data, textStatus, xhr) {
            $(".condiciones_negociacion").html(data.Note.description);
            $(".condiciones_negociacion").show();
            saveBorradorQuotation();
        },"json");
    }
});

$("body").on('click', '.addNotice', function(event) {
    event.preventDefault();
    $.get(copy_js.base_url+'notices/add', {}, function(response) {
        $("#modalNotices").modal("show");
        $("#bodyNotices").html(response);
        $("#NoticeDescription").summernote({
            height: 70,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
            ],
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
            focus: false,
            disableResizeEditor: true,
            disableDragAndDrop: true
        });
    });
});

function listNotices(){
    $.get(copy_js.base_url+'notices/list_data/', {}, function(response) {
        $("#noticeSelectData").html(response);
    });
}

    
$("body").on('submit', '#NoticeAddForm', function(event) {
    event.preventDefault();
    $.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
        $("#bodyNotices").html("");
        $("#modalNotices").modal("hide");
        listNotices();
    });
});

$("#noticeSelectData").change(function(event) {
    if ($(this).val() != "") {
        $.post(copy_js.base_url+'notices/view/'+$(this).val(), {}, function(data) {
            data = $.parseJSON(data);
            $("#QuotationCustomerNote").summernote('code',data.Notice.description);
        });
    }
});

listNotices();


setTimeout(function() {

    $("#form_quotations").removeAttr("novalidate");
    $("#form_quotations").parsley();
}, 3000);