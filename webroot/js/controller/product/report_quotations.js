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
    var controlProductos = $("#controlProductos").val();
    var ht = $("#paso1").height() < 400 ? 400 : $("#paso1").height();
    $("#resultado").html("");

    var productosData = [];

    $(".tr_remarket").each(function(index, el) {
        productosData.push($(this).data("id"));
    });

    var ini = $("#input_date_inicio").val();
    var end = $("#input_date_fin").val();

    $.post(copy_js.base_url+'Products/getDataRemarket', {type, ht, products: productosData, controlProductos,ini,end,is_report:1}, function(data, textStatus, xhr) {
        $("#resultado").html(data);
        $(".body-loading").hide();

        $("#tablaProd").DataTable({
            'iDisplayLength': 20,
            "language": {"url": "https://crm.kebco.co/Spanish.json",},
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel'
            ],
            "order": [[ 0, "desc" ]],
            "lengthMenu": [ [20,50, 100, -1], [20,50, 100, "Todos"] ]
            });
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

$("body").on('click', '#continuarEnvio', function(event) {
    var total = 0;
    var datosEnvio = [];
    $("body").find('.seleccionBasica').each(function(index, el) {
        if($(this).is(":checked")){
            total++;
        }
    });
    $("#resultado3").html("");
    $("#paso3").hide();
    if(total > 0){
        $("body").find('.seleccionBasica').each(function(index, el) {
            if($(this).is(":checked")){
                var data = {
                    id: $(this).data("id"),
                    type: $(this).data("type")
                }
                datosEnvio.push(data);
            }
        });
        $(".body-loading").show();
        $.post(copy_js.base_url+'Products/getDataFinalSend', {datos: datosEnvio}, function(data, textStatus, xhr) {
            $("#paso3").show();
            $("#resultado3").show();
            $("#resultado3").html(data);
            $(".body-loading").hide();
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
        });
    }else{
        $("#resultado3").html("");
    }
});

$("body").on('click', '#descargarCelulares', function(event) {
    event.preventDefault();
    exportTableToCSV('celulares.csv')
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



$("body").on('submit', '#form_product_envio', function(event) {
    event.preventDefault();
    var mensaje = $("body").find("#cuerpocorreo").val();

    if($.trim(mensaje) == ""){
        message_alert("El cuerpo del correo es requerido","error");
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

$("body").on('click', '#crearLista', function(event) {
    event.preventDefault();
    var celulares = $("body").find("#celularesEnvio").val();
    if($.trim(celulares) != ""){        
        $("#WhatsappListName").val("");
        $("#WhatsappListEmails").val(celulares);
        $("#typeEnvio").val("1");
        $("#listModal").modal("show");
    }
});   

$("body").on('click', '#crearListaCorreos', function(event) {
    event.preventDefault();
    var correos = $("body").find("#correosEnvio").val();
    if($.trim(correos) != ""){        
        $("#WhatsappListName").val("");
        $("#WhatsappListEmails").val(correos);
        $("#typeEnvio").val("2");
        $("#listModal").modal("show");
    }
});   

function reloadHtml(){
    $("#resultado").html("");
    $("#resultado3").html("");
    $("#resultado3").hide();
    $("#paso3").hide();
}


function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table#tablaCelulares tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join(""), filename);
}

function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

$("body").on('submit', '#form_product_difusion', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post(copy_js.base_url+'MailingLists/saveFromRemarketing', $(this).serialize(), function(data, textStatus, xhr) {
        $(".body-loading").hide();
        if(data == "1"){
            message_alert("La lista creada correctamente","Bien");
            $("#listModal").modal("hide");
            $("#MailingListName").val("");
        }else{
            message_alert("La lista no pudo ser creada correctamente","error");
        }
    });
    console.log("submit")
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