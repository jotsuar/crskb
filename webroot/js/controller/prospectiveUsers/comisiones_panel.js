var submitdata = {}
$('.cambioCostoDataUsd').editable(copy_js.base_url+copy_js.controller+'/change_percentaje', {
    indicator : "<img src='img/spinner.svg' />",
    type : "number",
    min: 0.1,
    max: 100,
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
    label : 'Cambio de porcentaje',
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
    placeholder : "Ingrese el valor",
    width : 160,
    formid: 'formularioQueCambia'
});


$("#total_bono_gerencia").change(function(e){

    var bono = $(this).val();
    var total_sin_gerencia = $("#total_sin_gerencia").val();
    var totalFinal = parseInt(bono) + parseInt(total_sin_gerencia);

    console.log(bono,(totalFinal),total_sin_gerencia)

    $("#totalizado").html(number_format(totalFinal));

});