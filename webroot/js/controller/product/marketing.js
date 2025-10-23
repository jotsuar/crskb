$('body').find("#cuerpocorreo").summernote(
    {
      height: 400,
    }
);
$("body").find('#correosEnvio').tagsinput();
$("body").find('#celularesEnvio').tagsinput();
$('html, body').scrollTop( $(document).height() );
setTimeout(function() {
    $('body').find("#cuerpocorreo").summernote(
        {
          height: 400,
        }
    );
    $("body").find('#correosEnvio').tagsinput();
    $("body").find('#celularesEnvio').tagsinput();
    $('html, body').scrollTop( $(document).height() );
}, 1000);


$("body").on('submit', '#form_product_envio', function(event) {
    event.preventDefault();
    var mensaje = $("body").find("#cuerpocorreo").val();
    var correosEnvio = $("#correosEnvio").val();
    var celularesEnvio = $("#celularesEnvio").val();

    if( ( $.trim(mensaje) == "" || correosEnvio == "") && $('#envioMetodo').val() == "EMAIL"  ){
        message_alert("El cuerpo del correo es requerido y el listado de correos es necesario","error");
    }else if( celularesEnvio == "" && $('#envioMetodo').val() == "WHATSAPP"  ){
        message_alert("Se debe escribir los números de teléfono a enviar","error");
    }else{
        $(".body-loading").show();
        var formData            = new FormData($('#form_product_envio')[0]);
         $.ajax({
            type: 'POST',
            url:  copy_js.base_url+'Products/send_remarketing',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(result){

                var resultado = JSON.parse(result);               
                var total = 0;

                if(resultado.emails.length > 0){
                    for (var i = 0; i < resultado.emails.length; i++) {
                        sendAjaxRequest(
                            copy_js.base_url+'Products/sendMessageOneEmail', {
                            campaignId : resultado.campaignId,
                            email: resultado.emails[i],
                            name: resultado.nombre,
                            cuerpo: resultado.cuerpo
                        }, function(data) {
                            console.log(data)
                        })
                    }
                    total+=resultado.emails.length;

                    if(total > 0){
                        total = total*100;
                    }

                    setTimeout(function() {
                        location.reload();
                    }, total );
                }

                if(resultado.numbers.length > 0){
                    total+=resultado.numbers.length;
                    location.reload();
                }

            }
        });
    }
}); 

$("#fileWp").dropify({
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

$("body").on('change', '#envioMetodo', function(event) {
    var seleccion = $(this).val();
    if(seleccion == "WHATSAPP"){
        $("#cuepoCorreoDiv").hide();
        $("#asuntocorreo").val("");
        $("#asuntocorreo").hide();
        $("#asuntocorreo").prev("label").hide();
        $('body').find("#cuerpocorreo").summernote('destroy');
        $('body').find("#cuerpocorreo").val('&nbsp;&nbsp;');
        $("#msgWp").attr("required","required");
        $("#whatsappMsgData").show();
        $("#msgWp").val('');
        $("body").find(".dropify-clear").click();
        $("#fileWp").dropify({
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
        $("#asuntocorreo").removeAttr("required");
    }else if(seleccion == "EMAIL"){
        $('body').find("#cuerpocorreo").val('');
        $('body').find("#cuerpocorreo").summernote(
            {
              height: 400,
            }
        );
        $("#cuepoCorreoDiv").show();
        $("#asuntocorreo").val("");
        $("#asuntocorreo").show();
        $("#asuntocorreo").prev("label").show();
        $("#msgWp").removeAttr("required");
        $("#fileWp").removeAttr("required");
        $("#asuntocorreo").attr("required","required");
    }else{
        $('body').find("#cuerpocorreo").summernote('destroy');
        $('body').find("#cuerpocorreo").val('');
        $('body').find("#cuerpocorreo").summernote(
            {
              height: 400,
            }
        );
        $("#msgWp").attr("required","required");
        $("#asuntocorreo").attr("required","required");
        $("#cuepoCorreoDiv").show();
        $("#msgWp").val('');
        $("#asuntocorreo").val("");
        $("#asuntocorreo").show();
        $("#asuntocorreo").prev("label").show();
        $("#whatsappMsgData").show();
        $("body").find(".dropify-clear").click();
        $("#fileWp").dropify({
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
    }
});

$("body").on('change', '#fileWp', function(event) {
    if(this.files.length > 0){        
        var f = event.target.files[0]; 
        var reader = new FileReader();
        reader.onload = (function(theFile) {
          return function(e) {
            var binaryData = e.target.result;
            $("#fileWpMsg").val(binaryData)
          };
        })(f);
        reader.readAsDataURL(f);
    }else{
        $("#fileWpMsg").val('')
    }
});

$("body").on('click', '#addList', function(event) {
    event.preventDefault();
    $("#listadoWhatsapp").show();
    $("#listadoCorreos").hide();
    $("#typeSendList").val("1")
    $("#listadoCompleto").val("");
    $("#listModalAdd").modal("show");
});

$("body").on('click', '#addListCorreo', function(event) {
    event.preventDefault();
    $("#listadoWhatsapp").hide();
    $("#listadoCorreos").show();
    $("#typeSendList").val("2")
    $("#listadoCompleto").val("");
    $("#listModalAdd").modal("show");
});


$("body").on('change', '.listadoName', function(event) {
   var valor = $(this).val();
   if(valor == ""){
     $("#listadoCompleto").val("");
     $(".selectList").hide();
   }else{
     if($(this).attr("id") == "listadoWhatsapp"){
        $("#listadoCompleto").val(lists[valor].numbers);    
     }else{
        $("#listadoCompleto").val(listEmails[valor].numbers);   
     }
     $(".selectList").show();
   }
});

$("body").on('click', '.selectList', function(event) {
    event.preventDefault();
    
    let numbers = $("#listadoCompleto").val();

    let arrNymbers = numbers.split(",");

    if($("#typeSendList").val() == "1"){        
        for (var i = 0; i < arrNymbers.length; i++) {
            $('#celularesEnvio').tagsinput('add', arrNymbers[i]);
        }
    }else{
        for (var i = 0; i < arrNymbers.length; i++) {
            $('#correosEnvio').tagsinput('add', arrNymbers[i]);
        }
    }

    $("#listadoName").val("");
    $("#listadoName").trigger('change');
    $("#listModalAdd").modal("hide");

});

function sendAjaxRequest(url,params, response){
    $.ajax({
        url: url,
        type: 'POST',
        data: params,
    })
    .done(function(datos) {
        response(datos)
    });
}