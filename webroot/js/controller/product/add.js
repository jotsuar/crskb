var NUMEROCARACTERES        = 0;

$(document).ready(function () {
  NUMEROCARACTERES       = strip_tags($('#ProductDescription').text()).length;
  $('#lbl_caracteres_faltantes').text(CHAR_NUM - NUMEROCARACTERES);
  color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
  $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
  color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
  $('.cuadroDescripcion').hide();
});

$('#btn_find_existencia').click(function() {
  var numero_parte = $.trim($('#ProductPartNumber').val());
  if (numero_parte != '') {
    $.post(copy_js.base_url+copy_js.controller+'/validExistencia',{numero_parte:numero_parte}, function(result){
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

if(!editProduct){
  $('#ProductPartNumber').flexdatalist({
       minLength: 1,
       noResultsText : 'El número de parte no existe y es posible ingresarlo',
       maxShownResults: 20
  });
}


if(editProduct){
  $('#ProductDescription').summernote({
    fontNames: ['Raleway'],
    focus: true,
    height: 200,
    // height: 100,
    disableResizeEditor: false,
    callbacks: {
      onKeyup: function(e) {
        NUMEROCARACTERES     = strip_tags( e.currentTarget.innerText ).length;
        $('#lbl_caracteres_faltantes').text(CHAR_NUM - NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
        $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
      }
    }
  });
}else{
  $('#ProductDescription').summernote({
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['misc', ['undo', 'redo','codeview']],
      ['link', ['linkDialogShow', 'unlink']]
    ],
    fontNames: ['Raleway'],
    focus: true,
    // height: 100,
    disableResizeEditor: true,
    callbacks: {
      onKeyup: function(e) {
        NUMEROCARACTERES     = strip_tags( e.currentTarget.innerText ).length;
        $('#lbl_caracteres_faltantes').text(CHAR_NUM - NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
        $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
        color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
      }
    }
  });
}

$("body").on('click', '#generateDescription', function(event) {
  event.preventDefault();
  const marca = $("#ProductBrandId option:selected").text();
  const nombre = $("#ProductName").val();
  const referencia = $("#ProductPartNumber").val();
  const description = $('#ProductDescription').val();

  $("#loaderKebco").show();
  $.post(copy_js.base_url+"products/generateDescriptionProduct", {marca,nombre,referencia,description}, function(data, textStatus, xhr) {

    if(data){
      response = JSON.parse(data);
      $("#ProductDescription").summernote("code",response.descripcion); 
    }

    console.log(data);
  $("#loaderKebco").hide();
  });

  console.log(marca,nombre,referencia,description)
});



$('body').find("#ProductLongDescription").summernote(
    {
      height: 200,
      disableResizeEditor: false,
    }
);

$('body').on("keyup", "#ProductListPriceUsd", function(event) {
  var precio              = $('#ProductListPriceUsd').val();
  var precio_final        = number_format(precio);
  $('#ProductListPriceUsd').val(precio_final);
});

$('body').on("keyup", "#ProductPurchasePriceCop", function(event) {
  var precio              = $('#ProductPurchasePriceCop').val();
  var precio_final        = number_format(precio);
  $('#ProductPurchasePriceCop').val(precio_final);
});

$('body').on("keypress", "#ProductPurchasePriceUsd", function(event) {
  var charC = (event.which) ? event.which : event.keyCode; 
  if (charC == 46) { 
      if ($(this).val().indexOf('.') === -1) { 
          return true; 
      } else { 
          return false; 
      } 
  } else { 
      if (charC > 31 && (charC < 48 || charC > 57)) 
          return false; 
  } 
  return true; 
});


// var instance = $('#ProductCategoryId').comboTree(optionsCategory);

$("#form_product").submit(function( event ) {
    var bran                = $('#ProductBrandId').val();
    var category_1          = $('#category_1').val();
    var categoryOne         = $( "#category_1 option:selected" ).text();

    var r = limit_characters(NUMEROCARACTERES,CHAR_NUM);
    if (r == false) {
      event.preventDefault();
      message_alert("Por favor valida, has excedido el máximo número de caracteres en la descripción","error");
      return false;
    }else if(bran == "13" && categoryOne.toLowerCase() != "servicio" ){
      event.preventDefault();
      message_alert("Por favor selecciona una marca diferente a Kebco","error");
      return false;
    }
});

$("#form_product input[type='radio']").change(function () {
  $('.cuadroDescripcion').show();
  var radio_option    = $("#form_product input[type='radio']:checked").val();
  if (radio_option == 0) {
    $('.note-editable').text(copy_js.copy_descripcion_productos);
  } else {
    $('.note-editable').text('');
  }
  NUMEROCARACTERES    = strip_tags($('.note-editable').text()).length;
  $('#lbl_caracteres_faltantes').text(CHAR_NUM - NUMEROCARACTERES);
  color_lbl_caracteres('lbl_caracteres_faltantes',NUMEROCARACTERES);
  $('#lbl_caracteres_utilizados').text(NUMEROCARACTERES);
  color_lbl_caracteres('lbl_caracteres_utilizados',NUMEROCARACTERES);
});

$("#ProductBrand,#ProductBrandId").select2();

typeProduct();

$("#ProductNormal").change(function(event) {
  typeProduct();
});

function typeProduct(){
  var dataType = $("#ProductNormal").val();

  if (dataType == "1") {
    $(".compuestoData").html("");
    if ($("#ProductAction").val() == "add") {
      $.get(copy_js.base_url+"products/delete_compuestos",{}, function(data) {
        
      });
    }
  }else{
    $.get(copy_js.base_url+"products/list_compuestos",{id:$("#ProductIdProduct").val(),"action": $("#ProductAction").val()}, function(data) {
      $(".compuestoData").html(data);
    });
  }
}

$("body").on('click', '#addProductToCompuesto', function(event) {
  event.preventDefault();
  var id = $(this).data("id");
  var action = $(this).data("action");
  $("#cuerpoAdd").html("");
  $(".body-loading").show();
  $.get(copy_js.base_url+"products/add_compuesto/"+id+"/"+action, function(data) {
    $("#cuerpoAdd").html(data)
    $('#CompositionProductId').select2({
        placeholder: "Seleccione una opción",
        dropdownParent: $("#modalIngrediente")
    });
    $("#CompositionAddCompuestoForm").parsley();
    $("#modalIngrediente").modal("show");
    $(".body-loading").hide();
  });
});

$("body").on('submit', '#CompositionAddCompuestoForm', function(event) {
  event.preventDefault();
  $.post($(this).attr("action"),$(this).serialize(), function(response, textStatus, xhr) {
    $.get(copy_js.base_url+"products/list_compuestos",{id:$("#ProductIdProduct").val(),"action": $("#ProductAction").val()}, function(data) {
      $(".compuestoData").html(data);
      $("#cuerpoAdd").html("");
      $("#modalIngrediente").modal("hide");
    });
  });
});

$("body").on('click', '.deleteComposition', function(event) {
  event.preventDefault();
  var id = $(this).data("id");
  $.post(copy_js.base_url+"products/delete_compuesto", {id: id}, function(data, textStatus, xhr) {
    $.get(copy_js.base_url+"products/list_compuestos",{id:$("#ProductIdProduct").val(),"action": $("#ProductAction").val()}, function(data) {
      $(".compuestoData").html(data);
      $("#cuerpoAdd").html("");
      $("#modalIngrediente").modal("hide");
    });
  });
});