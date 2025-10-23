
$("body").on('change', '#viewMargen', function(event) {
	if ($(this).is(':checked')) {
		$(".valoresTRM").show();
	}else{
		$(".valoresTRM").hide();		
	}
});


$("body").on( "click", ".cambioCosto", function() {
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
        PRODUCTO_IDS.push(id);
        $.post(copy_js.base_url+'Quotations/changeCost',{id,razon}, function(result){
            message_alert("Solicitud realizada","Bien");
        });
    });
});
if($("#textClient").length){
    var phone = prompt("Por favor los 4 últimos números del celular al que fue enviada esta cotización");
    if (phone === null){
        location.reload();
    }else{
        if(btoa("@@@---"+phone+"---@") != $("#textClient").data("text") ){
            alert("Acceso no permitido");
            location.reload();
        }else{
            $("#cotizacionview").show();
        }
    }
}


$("body").on('click', '.reenviarCot', function(event) {
    event.preventDefault();     
    var id          = $(this).data("uid");
    var flowstages  = $(this).data("flowstages");
    $(".body-loading").show();

    $.post(copy_js.base_url+'flow_stages/reenviar',{id,flowstages},function(result){
        location.reload();
    });

})


$('.gallery').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'iframe',
        gallery: {
          enabled:true
        },
         removalDelay: 500,

          // Class that is added to popup wrapper and background
          // make it unique to apply your CSS animations just to this exact popup
        mainClass: 'mfp-with-zoom',
        zoom: {
            enabled: true, // By default it's false, so don't forget to enable it

            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function

            // The "opener" function should return the element from which popup will be zoomed in
            // and to which popup will be scaled down
            // By defailt it looks for an image tag:
            opener: function(openerElement) {
              // openerElement is the element on which popup was initialized, in this case its <a> tag
              // you don't need to add "opener" option if this code matches your needs, it's defailt one.
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        },
        callbacks: {
            open: function() {
                setTimeout(function() {
                    var iframe = $("body").find("#iframe1")[0];
                    var cssLink = document.createElement("link");
                    cssLink.href = rootUrl+"css/styleIframe.css"; 
                    cssLink.rel = "stylesheet"; 
                    cssLink.type = "text/css"; 
                    iframe.contentWindow.document.head.appendChild(cssLink);

                }, 300);
            },
            change: function() {
                setTimeout(function() {
                    var iframe = $("body").find("#iframe1")[0];
                    var cssLink = document.createElement("link");
                    cssLink.href = rootUrl+"css/styleIframe.css"; 
                    cssLink.rel = "stylesheet"; 
                    cssLink.type = "text/css"; 
                    iframe.contentWindow.document.head.appendChild(cssLink);

                }, 300);
            },
            // e.t.c.
          }

    });
});


$(".buttonsMore").click(function(event) {
    $(this).next("div.gallery").children('a#firstImg').trigger('click');

});


$("body").on('click', '.datosProforma', function(event) {
    event.preventDefault();
    var id = $(this).data("id");
    $.get(copy_js.base_url+'quotations/data_proforma/'+id, function(data) {
        $("#cuerpoFacturacionProforma").html(data);
        $("#modalNewFactura").modal("show");
    });
});


$("body").on('click', '.duplicate', function(event) {
    event.preventDefault();
    var url  = $(this).attr("href");
    $(".body-loading").show();
    $.get(url, function(data) {
        $("#cuerpoDuplicate").html(data);
        $("#cuerpoDuplicate").show();
        $("#modalDuplicate").modal("show");
        $(".body-loading").hide();
        $("#ingresoCliente").hide();
        $("#cuerpoContactoCliente").hide();

        $("#modalDuplicate .cuerpoContactoCliente").hide();
        setTimeout(function() {
            setCustomerSelect2("#ProspectiveUserClientsNaturalId","#modalDuplicate");            
        }, 1000);
        $("#modalDuplicate #ProspectiveUserImage").dropify({
            messages: {
                'default': 'Seleccione o arrastre el archivo',
                'replace': 'Seleccione o arrastre el archivo',
                'remove':  'Remover',
                'error':   'Error al cargar el archivo'
            },
            error: {
                'fileSize': 'El archivo supera el tamaño permitido de: ({{ value }}).',
                'fileExtension': 'El formato del archivo seleccionado no es permitido (Permitidos: {{ value }} ).'
            }
        }); 

    });
});




$("body").on('change', '#modalDuplicate #tipoClienteProspective', function(event) {
    if($(this).val() == "2"){
        $("body").find("#modalDuplicate .clienteLegalProspective").show();
        $("body").find("#modalDuplicate .clienteNaturalProspective").hide();
        $("body").find("#modalDuplicate #ProspectiveUserClientsNaturalId").removeAttr('required');
        $("body").find("#modalDuplicate #ProspectiveUserClientsLegalId").attr('required','required');
        $("body").find("#modalDuplicate #ProspectiveUserClientsNaturalId").val(""); 
        $('#modalDuplicate #ProspectiveUserClientsNaturalId').select2({
            dropdownParent: $("#modalDuplicate")
        }); 
    }else{
        $("body").find("#modalDuplicate .clienteNaturalProspective").show();
        $("body").find("#modalDuplicate #ProspectiveUserClientsNaturalId").attr('required','required');
        $("body").find("#modalDuplicate #ProspectiveUserClientsLegalId").removeAttr('required');
        $("body").find("#modalDuplicate .clienteLegalProspective").hide();
        $("body").find("#modalDuplicate #ProspectiveUserClientsLegalId").val("");
        $('#modalDuplicate #ProspectiveUserClientsLegalId').select2({
                dropdownParent: $("#modalDuplicate")
        });     
    }
}).on('change', '#modalDuplicate #ProspectiveUserClientsNaturalId', function(event) {
    var empresa_id = $(this).val();

    if(empresa_id.indexOf("_LEGAL") != -1){
        empresa_id = empresa_id.replace("_LEGAL","");
        var servicio_id = 0;
        $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
            $('#modalDuplicate.selectContact').html(result);
            $('#modalDuplicate #contac_id').select2({
                dropdownParent: $("#modalDuplicate")
            });
        });
    }else{
        $('#modalDuplicate .selectContact').html("");
    }

    
}).on("click", "#modalDuplicate .icon_add_legal_contac_prospective", function() {
    $('#modalDuplicate #cityForm').show();
    $('#modalDuplicate label[for="cityForm"]').show();
    var empresa_id      = $('#modalDuplicate #ProspectiveUserClientsNaturalId').val();
    empresa_id = empresa_id.replace("_LEGAL","");
    $.post(copy_js.base_url+'ContacsUsers/add_contact_prospective',{empresa_id:empresa_id}, function(result){
        $("#modalDuplicate #cuerpoDuplicate").hide();
        $("#modalDuplicate .cuerpoContactoCliente").show();
        $("#modalDuplicate .cuerpoContactoCliente").html(result);

        var input           = document.getElementById('CityContactProspective');
        var autocomplete    = new google.maps.places.Autocomplete(input);
    });
}).on('click', '#modalDuplicate .returnContactProspective', function(event) {
    $("#modalDuplicate .cuerpoContactoCliente").html("");
    $("#modalDuplicate .cuerpoContactoCliente").hide();
    $("#modalDuplicate #cuerpoDuplicate").show();
    $("body").find('#modalDuplicate #ingresoCliente').html("");
}).on('submit', '#modalDuplicate #contactoProspectiveUserNew', function(event) {
    event.preventDefault();
    var empresa_id      = $('#modalDuplicate #ContacsUserEmpresaId').val();
    var name            = $('#modalDuplicate #ContacsUserName').val();
    var telephone       = $('#modalDuplicate #ContacsUserTelephone').val();
    var cell_phone      = $('#modalDuplicate #ContacsUserCellPhone').val();
    var email           = $('#modalDuplicate #ContacsUserEmail').val();
    var cityForm        = $('#modalDuplicate #CityContactProspective').val();
     $.post(copy_js.base_url+'ContacsUsers/addUser',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm}, function(result){
        if (result == 0) {
            $('.btn_guardar_form').show();
            message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
        } else {
            list_contacs_bussines_qt(result);
            message_alert("Contacto creado satisfactoriamente","Bien");
        }
    });
}).on('click', '#modalDuplicate .addNewCustomerProspectiveDuplicate', function(event) {
    event.preventDefault();
    type = $(this).data("type");
    $("#modalDuplicate .cuerpoContactoCliente").html("");
    $.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type, flujo: 1}, function(result){
        $("#modalDuplicate .cuerpoContactoCliente").html(result);
        $("#modalDuplicate #cuerpoDuplicate").hide();
        $("#modalDuplicate .cuerpoContactoCliente").show();
    });
    $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').val("");
    $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').select2({
        dropdownParent: $("#modalDuplicate")
    });
    $("body").find('#modalDuplicate #ProspectiveUserClientsLegalId').val("");
    $("body").find('#modalDuplicate #ProspectiveUserClientsLegalId').select2({
        dropdownParent: $("#modalDuplicate")
    });
    $("body").find('#modalDuplicate .selectContact').html("");
});

function list_contacs_bussines_qt(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#modalDuplicate #contac_id').append(result);
        $('#modalDuplicate #contac_id').val(nuevo_id);
         $('#modalDuplicate #contac_id').select2({
            dropdownParent: $("#modalDuplicate")
        });
        $("#modalDuplicate .cuerpoContactoCliente").html("");
        $("#modalDuplicate .cuerpoContactoCliente").hide();
        $("#modalDuplicate #cuerpoDuplicate").show();
    });
}




$("body").on('click', '#modalDuplicate #btn_validate_exist_customer', function(event) {

    event.preventDefault();
    var tipoCliente         = $("body").find("#modalDuplicate #CustomerType").val();
    var nacionalidad        = $("body").find("#modalDuplicate #CustomerNacional").val();
    var valueIdentification = $("body").find('#modalDuplicate #CustomerIdentification').val();
    var valueEmail          = $("body").find('#modalDuplicate #CustomerEmail').val();
    var valueType           = $("body").find('#modalDuplicate #CustomerNacional').val();
    var continueSearch      = true;

    if(tipoCliente == "2" ){
        if(valueIdentification.length < 9 ){
            message_alert("El NIT  es requerido y debe contener mínimo 9 digitos","error");
            continueSearch = false;
            return false;
        }else{
            if(!validateNitNumber($.trim(valueIdentification))){
                message_alert("El nit debe ser un número válido.","error");
                continueSearch = false;
                return false;
            }
        }
    }else{
        if( ( $.trim(valueIdentification) == "" || (valueIdentification.length > 0 && valueIdentification.length < 6 ) )  && valueType == "1" ){
            message_alert("La identificación y es requerida debe contener mínimo 6 digitos","error");
            continueSearch = false;
            return false;
        }else{
            if( valueIdentification.length > 0 && !validateIdeintificationNumber($.trim(valueIdentification))){
                message_alert("La identificación debe ser un número válido.","error");
                continueSearch = false;
                return false;
            }
        }
        if(valueEmail.length > 0){
            if(!ValidateEmail(valueEmail)){
                message_alert("El correo ingresado no es válido","error");
                continueSearch = false;
            }
        }
    } 

    if(tipoCliente == "1" && $.trim(valueIdentification) == "" && $.trim(valueEmail) == ""){
        message_alert("Se debe ingresar información en mínimo un campo","error");
        continueSearch = false;
    }

    if (continueSearch){
        $.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail,nacional:nacionalidad}, function(result){
            if(result != 'null'){
                message_alert("El cliente ya existe","error");
            }else{
                if(tipoCliente == "2"){
                    $.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification, flujo: 1, nacional: nacionalidad}, function(result){
                        $("body").find("#modalDuplicate .clientsLegalsForm").hide();
                        $("body").find('#modalDuplicate #ingresoCliente').html(result);
                        $("body").find('#modalDuplicate #ingresoCliente').show();
                        var input           = document.getElementById('ContacsUserCity');
                        var autocomplete    = new google.maps.places.Autocomplete(input);
                    });
                }else{
                    $.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail, flujo: 1, nacional: nacionalidad}, function(result){
                        $("body").find("#modalDuplicate .clientsLegalsForm").hide();
                        $("body").find('#modalDuplicate #ingresoCliente').html(result);
                        $("body").find('#modalDuplicate #ingresoCliente').show();
                        var input           = document.getElementById('ClientsNaturalCity');
                        var autocomplete    = new google.maps.places.Autocomplete(input);
                    });
                }
            }
        });
    }

});

$("body").on('click', '#modalDuplicate .regresarFormClient', function(event) {
    event.preventDefault();
    $("body").find('#modalDuplicate #ingresoCliente').html("");
    $(".clientsLegalsForm").show();
});


$("body").on('submit', '#modalDuplicate #formFlujoCrearCustomerLegal', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post(copy_js.base_url+'ClientsLegals/add_customer_legal_post',$(this).serialize(), function(result){
        result = $.parseJSON(result);
        list_clients_legal_qt(result.id)
        $(".body-loading").hide();
    });
}).on('submit', '#modalDuplicate #formFlujoCustomerNatural', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post(copy_js.base_url+'ClientsNaturals/add_customer_post',$(this).serialize(), function(result){
        result = $.parseJSON(result);
        list_clients_natural_qt(result.id)
        $(".body-loading").hide();
    });
});


function list_clients_natural_qt(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').append(result);
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').val(nuevo_id+"_NATURAL");
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').select2({
            dropdownParent: $("#modalDuplicate")
        });
        $(".cuerpoContactoCliente, #ingresoCliente").html("");
        $(".cuerpoContactoCliente, #ingresoCliente").hide();
        $("#cuerpoDuplicate").show();
    });
}
function list_clients_legal_qt(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').append(result);
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').val(nuevo_id+"_LEGAL");
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').select2({
            dropdownParent: $("#modalDuplicate")
        });
        $("body").find('#modalDuplicate #ProspectiveUserClientsNaturalId').trigger('change');
        $("#modalDuplicate .cuerpoContactoCliente,#modalDuplicate #ingresoCliente").html("");
        $("#modalDuplicate .cuerpoContactoCliente,#modalDuplicate #ingresoCliente").hide();
        $("#cuerpoDuplicate").show();

    });
}


// $("body").on('submit', '#formGeneralCustomer', function(event) {
//     event.preventDefault();
//     var formData            = new FormData($('#formGeneralCustomer')[0]);
//     $(".body-loading").show();
//      $.ajax({
//         type: 'POST',
//         url:  copy_js.base_url+'ProspectiveUsers/add_flujo_post',
//         data: formData,
//         contentType: false,
//         cache: false,
//         processData:false,
//         success: function(result){
//             console.log(result)
//             location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
//         }
//     });
     
//     // $.post(copy_js.base_url+'ProspectiveUsers/add_flujo_post',$(this).serialize(), function(result){
//     //  console.log(result)
//     //  location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
//     // });
// });


$("body").on('paste', '#modalDuplicate #ProspectiveUserImagePaste', function(event) {
    var items = (event.clipboardData || event.originalEvent.clipboardData).items;
    for (let index in items){
      let item = items[index];
      
      if (item.kind === 'file') {
        if(item.type.indexOf('image') !== -1){
          var blob = item.getAsFile();
          var reader = new FileReader();
          let app = this;
          reader.onload = function(event){
            var urlImage = event.target.result;
            $("body").find('#modalDuplicate #ProspectiveUserImagePaste').val(urlImage);
            $("body").find('#modalDuplicate #ProspectiveUserImagePaste').hide();
            $("body").find('#modalDuplicate #previewData').attr('src',urlImage);
            $("body").find("#modalDuplicate .imagenPreview").show();
          };
          reader.readAsDataURL(blob);
        }        
      }
    }
});

$("body").on('click', '#modalDuplicate .deleteImg', function(event) {
    event.preventDefault();
    $("body").find('#modalDuplicate #ProspectiveUserImagePaste').val('');
    $("body").find('#modalDuplicate #previewData').attr('src',null);
    $("body").find('#modalDuplicate #ProspectiveUserImagePaste').show();
    $("body").find("#modalDuplicate .imagenPreview").hide();
});


$("body").on('click', '.detail_product', function(event) {
    event.preventDefault();
    const URL = $(this).attr("href");
    $(".body-loading").show();
    $("#body_modal_datos_product").load(URL,{},
        function(data){
            $(".body-loading").hide();
            $("#modal_datos_product").modal("show");

            $('img#ppalImg')
            .wrap('<span style="display:inline-block"></span>')
            .css('display', 'block')
            .parent()
            .zoom();
        /* Stuff to do after the page is loaded */
    });
    
});


$("body").on("click",".imglink",function(event) {
    event.preventDefault();
    $("#ppalImg").attr("src",$(this).attr("href"));
    $('#ppalImg').trigger('zoom.destroy'); // remove zoom
    $('#ppalImg').parent().trigger('zoom.destroy'); // remove zoom
    $('img#ppalImg')
    .wrap('<span style="display:inline-block"></span>')
    .css('display', 'block')
    .parent()
    .zoom();
});

