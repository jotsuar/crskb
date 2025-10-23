
var optionsDp = {
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
};   

$(".imagenesProducto").dropify(optionsDp); 
var equipos_ingresados = 0;
$(document).ready(function () {
    if (copy_js.action != 'index') {
        initElement();
    }
});

$(".btn_update_user").click(function(e) {
    var service_id      = $(this).data('uid');
    var asesor          = $("#user_asignado_"+service_id).val();
    if (asesor != "") {
        $(".btn_update_user").hide();
        $.post(copy_js.base_url+copy_js.controller+'/updateuserAsignado',{service_id:service_id,asesor:asesor}, function(result){
            location.href = copy_js.base_url+copy_js.controller;
        });
    }
});

$(".btnAddBinnacle").click(function(e) {
    e.preventDefault();
    $.get($(this).attr("href"), function(response) {
        $("#cuerpoBit").html(response);
        $("#modalVitacora").modal("show");
        $("#BinnacleAddForm").parsley();

    });
});

$(".cargaDocumento").click(function(e) {
    e.preventDefault();
    $.get($(this).attr("href"), function(response) {
        $("#cuerpoDocument").html(response);
        $("#TechnicalServiceUploadDocumentForm").parsley();
        $("#modalDocumento").modal("show");
        $("#TechnicalServiceDocument").dropify(optionsDp); 
    });
});

$(".listBinnacle").click(function(e) {
    e.preventDefault();
    $.get($(this).attr("href"), function(response) {
        $("#cuerpoListBit").html(response);
        $("#modalListVitacora").modal("show");
    });
});

$("body").on('submit', '#BinnacleAddForm', function(event) {
    event.preventDefault();
    $("#loaderKebco").show();
    $.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
        location.reload();
    });
});

$("body").on( "click", "#btn_find_existencia", function() {
    var email               = $('#ClientsNaturalEmail').val();
    if (email != '') {
        $.post(copy_js.base_url+'ClientsNaturals/validExistencia',{email:email},function(result){
            if (result == 0) {
                message_alert("El correo electrónico esta disponible","Bien");
            } else {
                message_alert("El correo electrónico ya esta registrado","error");
            }
        });
    } else {
        message_alert("Por favor ingresa el correo electrónico","error");
    }
});

$("body").on( "click", "#btn_find_existencia_juridico", function() {
    var nit         = $('#ClientsLegalNit').val();
    if (nit != '') {
        $.post(copy_js.base_url+'ClientsLegals/validExistencia',{nit:nit}, function(result){
            if (result == 0) {
                message_alert("El nit esta disponible","Bien");
            } else {
                message_alert("El nit ya esta registrado","error");
            }
        });
    } else {
        message_alert("Por favor ingresa el Nit de la empresa","error");
    }
});

function setCustomerByTypeSelect2(id,dropDown = null,type = 'natural'){
    var options = {
        placeholder: "Buscar cliente",
        minimumInputLength: 3,
        multiple: false,
        language: "es",
        ajax: {
            url: copy_js.base_url+"TechnicalServices/get_user",
            dataType: 'json',
             data: function (params) {
              return {
                q: params.term, // search term
                page: params.page,
                type: type
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;

              return {
                results: data.items,
                // pagination: {
                //   more: (params.page * 30) < data.total_count
                // }
              };
            },
        }
    };

    if (dropDown != null) {
        options["dropdownParent"] = $(dropDown);
    }

    $("body").find(id).select2(options);
}

function initElement(){
    $('#cityForm').hide();
    $('label[for="cityForm"]').hide();
    $('.formJuridico').hide();
    $('.formNatural').hide();
    setCustomerByTypeSelect2("#TechnicalServiceClientsNaturalId",null,'natural');
    setCustomerByTypeSelect2("#TechnicalServiceClientsLegalId",null,'legal');

    // $('#TechnicalServiceClientsLegalId').select2();
    // $('#TechnicalServiceClientsNaturalId').select2();
    equipos_ingresados = $('#TechnicalServiceNumero').val();
    for (var i = 1; i <= parseInt(equipos_ingresados); i++) {
        if ($("#TechnicalService"+i+"AccessoriesOtros").is(':checked') ) {
            $('#input_otros_div_'+i).show();
        } else {
            $('#TechnicalService'+i+'OtrosInput').val('');
            $('#input_otros_div_'+i).hide();
        }
        $('#TechnicalService'+i+'Observations').summernote({
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['misc', ['undo', 'redo','codeview']],
            ['link', ['linkDialogShow', 'unlink']]
          ],
          fontNames: ['Raleway'],
          focus: true,
          disableResizeEditor: true,
          height: 200
        });
        $('#TechnicalService'+i+'PossibleFailures').summernote({
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['misc', ['undo', 'redo','codeview']],
            ['link', ['linkDialogShow', 'unlink']]
          ],
          fontNames: ['Raleway'],
          focus: true,
          disableResizeEditor: true,
          height: 200
        });
    }

    if (copy_js.action == 'edit') {
        var radio_option    = $("#form_service input[type='radio']:checked").val();
        if (radio_option == 'Juridico') {
            $('.formNatural').hide();
            $('.formJuridico').show();
            $('.selectContact').empty();
            dataContacssBusiness($('#TechnicalServiceClientsLegalId').val());
        } else {
            $('.formJuridico').hide();
            $('.formNatural').show();
        }   
    } else {
        $('.divImagenes').hide();
    }
}

$('#agregar_new_service').click(function(){
    equipos_ingresados = parseInt(equipos_ingresados) + parseInt(1);
    $.post(copy_js.base_url+copy_js.controller+'/equipo_nuevo',{equipos_ingresados:equipos_ingresados},function(result){
        $.post(copy_js.base_url+copy_js.controller+'/btn_equipo_nuevo',{equipos_ingresados:equipos_ingresados},function(resultButton){
            $('.dataequipo').append(result);
            $('.divButton_'+equipos_ingresados).html(resultButton);
            $('#input_otros_div_'+equipos_ingresados).hide();
            $('#TechnicalService'+equipos_ingresados+'Observations').summernote({
              toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
              ],
              fontNames: ['Raleway'],
              focus: true,
              disableResizeEditor: true
            });
            $('#TechnicalService'+equipos_ingresados+'PossibleFailures').summernote({
              toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['misc', ['undo', 'redo','codeview']],
                ['link', ['linkDialogShow', 'unlink']]
              ],
              fontNames: ['Raleway'],
              focus: true,
              disableResizeEditor: true
            });

        });
    });
});

$("body").on( "change", "input[type=checkbox]", function(e) {
    var stringCheckend      = e.target.id;
    var stringArray         = stringCheckend.split(['Accessories']);
    var uidArray            = stringArray[0].split(['Service']);
    var uid                 = uidArray[1];
    if (e.target.id == 'TechnicalService'+uid+'AccessoriesOtros') {
        if ($("#TechnicalService"+uid+"AccessoriesOtros").is(':checked') ) {
            $('#input_otros_div_'+uid).show();
        } else {
            $('#TechnicalService'+uid+'OtrosInput').val('');
            $('#input_otros_div_'+uid).hide();
        }
    }
});

$("#form_service input[type='radio']").change(function () {
    var radio_option    = $("#form_service input[type='radio']:checked").val();
    if (radio_option == 'Juridico') {
        $('.formNatural').hide();
        $('.formJuridico').show();
        $('.selectContact').empty();
        $("body").find("#contac_id").attr('required','required');
        dataContacssBusiness($('#TechnicalServiceClientsLegalId').val());
        $("#TechnicalServiceClientsNaturalId").removeAttr('required');
        $("#TechnicalServiceClientsLegalId").attr('required','required');
        $("#btnContac").hide();
    } else {
        $("#btnContac").show();
        $('.formJuridico').hide();
        $('.formNatural').show();
        $('.selectContact').empty();
        $("body").find("#contac_id").removeAttr('required');
        $("#TechnicalServiceClientsLegalId").removeAttr('required');
        $("#TechnicalServiceClientsNaturalId").attr('required','required')
    }
});

$("#btnContac").click(function(event) {
    event.preventDefault();
    var customer_id = $("#TechnicalServiceClientsNaturalId").val();
    if (customer_id != "" && customer_id != "0") {
        $.post(copy_js.base_url+'ClientsNaturals/get_data_client',{customer_id}, function(result){
            $("#TechnicalService1ContactName").val(result.name);
            $("#TechnicalService1ContactPhone").val(result.cell_phone+ " "+result.telephone);
            $("#TechnicalService1ContactIdentification").val(result.identification);
        },'json');
    }
});

$("body").on( "click", ".btn_delete_equipo", function() {
    var equipo         = $(this).data('uid');
    $('#equipo_'+equipo).remove();
    $('.divButton_'+equipo).remove();
});

$('#btn_borrador').click(function() {
   var radio_option         = $("#form_service input[type='radio']:checked").val();
   if (radio_option == 'Juridico') {
        var client_id       = $('#TechnicalServiceClientsLegalId').val();
        var contac_id       = $('#contac_id').val();
        var natural_id      = 0;
    } else {
        var client_id       = 0;
        var natural_id      = $('#TechnicalServiceClientsNaturalId').val();
        var contac_id       = 0;
    }
    var equipo              = $('#TechnicalService1Equipment').val();
    var rason               = $('#TechnicalService1Reason').val();
    var numero_parte        = $('#TechnicalService1PartNumber').val();
    var numero_serie        = $('#TechnicalService1SerialNumber').val();
    var observacion         = $('#TechnicalService1Observations').val();
    var fallas              = $('#TechnicalService1PossibleFailures').val();
    if (radio_option != undefined){
        if (equipo != '' && rason != '' && numero_parte != '' && numero_serie != '' && observacion != '' && fallas != '') {
            var formData            = new FormData($('#form_service')[0]);
            $('#btn_borrador').hide();
            $.ajax({
                type: 'POST',
                url: copy_js.base_url+copy_js.controller+'/saveDataAjax',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(result){
                    message_alert("El servicio técnico se ha guardado","Bien");
                    $('#TechnicalService1Equipment').val('');
                    $('#TechnicalService1Reason').val('');
                    $('#TechnicalService1PartNumber').val('');
                    $('#TechnicalService1SerialNumber').val('');
                    $('#TechnicalService1Observations').summernote("reset");
                    $('#TechnicalService1PossibleFailures').summernote("reset");
                    $('#btn_borrador').show();
                }
            });
        } else {
            message_alert("Todos los campos son requeridos","error");
        }
    } else {
        message_alert("Por favor selecciona si el cliente es natural o juridico","error");
    }
});

$("#form_service").submit(function(event) {
    setTimeout(function() {
        $("#loaderKebco").hide();
    }, 1000);
    var radio_option 	= $("#form_service input[type='radio']:checked").val();
    if (radio_option == undefined){
    	message_alert("Por favor selecciona si el cliente es natural o juridico","error");
    	event.preventDefault();
    } else {
        if (radio_option == 'Juridico') {
            if ($('#contac_id').val() == 'null' || $('#contac_id').val() == 'undefined') {
                message_alert("Por favor agrega o selecciona el contacto para el servicio","error");
                event.preventDefault();
            }else{
                $("#loaderKebco").hide();
            }
        }else{
            $("#loaderKebco").hide();
        }
    }
});

$("body").on( "click", "#icon_add_imagenes", function() {
    $('.divImagenes').show();
    $("#icon_add_imagenes").hide();
});

$('#TechnicalServiceClientsLegalId').on('select2:select', function (e) {
    dataContacssBusiness($('#TechnicalServiceClientsLegalId').val());
});

$("#icon_add_natural_cliente").click(function() {
    $('#cityForm').addClass('form-control');
    $('#cityForm').show();
    $('label[for="cityForm"]').show();
    $.post(copy_js.base_url+'ClientsNaturals/add',{}, function(result){
        $('#modal_form_body').html(result);
        $('#modal_form_label').text('Registrar cliente natural');
        $('#modal_form').modal('show');
    });
});

$("#icon_add_legal_cliente").click(function() {
    $.post(copy_js.base_url+'ClientsLegals/add_new',{}, function(result){
        $('#modal_small_body').html(result);
        $('#modal_small_label').text('Registrar cliente jurídico');
        $('#modal_small').modal('show');
    });
});

$("body").on( "click", "#icon_add_legal_contac", function() {
    $('#cityForm').show();
    $('#cityForm').addClass('form-control');
    $('label[for="cityForm"]').show();
    var empresa_id      = $('#TechnicalServiceClientsLegalId').val();
    $.post(copy_js.base_url+'ContacsUsers/add_user_form',{empresa_id:empresa_id}, function(result){
        $('#modal_form_body').html(result);
        $('#modal_form_label').text('Registrar contacto');
        $('#modal_form').modal('show');
    });
});

$("body").on( "click", "#btn_find_existencia_contacto", function() {
    var email           = $('#ContacsUserEmail').val();
    if (email != '') {
        $.post(copy_js.base_url+'ContacsUsers/validExistencia',{email:email}, function(result){
            if (result == 0) {
                message_alert("El correo electrónico esta disponible","Bien");
            } else {
                message_alert("El correo electrónico ya esta registrado","error");
            }
        });
    } else {
        message_alert("Por favor ingresa el correo electrónico de la empresa","error");
    }
});

//CLIENTE NATURAL
$("body").on( "click", ".btn_guardar_form", function() {
    if (!document.getElementById('ContacsUserEmpresaId')){
        var servicio_id         = $(this).data('uid');
        var cliente_id          = $('#ClientsNaturalId').val();
        var name                = $('#ClientsNaturalName').val();
        var telephone           = $('#ClientsNaturalTelephone').val();
        var cell_phone          = $('#ClientsNaturalCellPhone').val();
        var email               = $('#ClientsNaturalEmail').val();
        var view                = $('#ClientsNaturalAction').val();
        var identification      = $('#ClientsNaturalIdentification').val();
        var city                = $('#cityForm').val();
        if (name != '' && telephone != '' && cell_phone != '' && city != '' && email != '') {
            $('.btn_guardar_form').hide();
            $.post(copy_js.base_url+'ClientsNaturals/addSave',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,cliente_id:cliente_id,view:view},function(result){
                if (result == 0) {
                    $('.btn_guardar_form').show();
                    message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
                } else {
                    $('.btn_guardar_form').show();
                    list_clients_natural_techinical(result);
                    $('#modal_form').modal('hide');
                    message_alert("Cliente creado satisfactoriamente","Bien");
                }
            });
        } else {
            message_alert("Todos los campos son requeridos","error");
        }
    } else {
        var empresa_id      = $('#ContacsUserEmpresaId').val();
        var name            = $('#ContacsUserName').val();
        var telephone       = $('#ContacsUserTelephone').val();
        var cell_phone      = $('#ContacsUserCellPhone').val();
        var email           = $('#ContacsUserEmail').val();
        var cityForm        = $('#cityForm').val();
        if (name != '' && telephone != '' && cell_phone != '' && email != '' && cityForm != '') {
            $('.btn_guardar_form').hide();
            $.post(copy_js.base_url+'ContacsUsers/addUser',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm}, function(result){
                if (result == 0) {
                    $('.btn_guardar_form').show();
                    message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
                } else {
                    list_contacs_bussines_technical(result);
                    $('#modal_form').modal('hide');
                    $('.btn_guardar_form').show();
                    message_alert("Contacto creado satisfactoriamente","Bien");
                }
            });
        } else {
            message_alert("Todos los campos son requeridos","error");
        }
    }
});
function list_clients_natural_techinical(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id}, function(result){
        $("#TechnicalServiceClientsNaturalId").select2('destroy');
        $('#TechnicalServiceClientsNaturalId').append(result);
        $('#TechnicalServiceClientsNaturalId').val(nuevo_id);
        setCustomerByTypeSelect2("#TechnicalServiceClientsNaturalId",null,'natural');
        // $('#TechnicalServiceClientsNaturalId').select2();
    });
}
function list_contacs_bussines_technical(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#contac_id').append(result);
        $('#contac_id').val(nuevo_id);
        $('#contac_id').select2();
    });
}

//CLIENTE JURIDICO
$("#btn_guardar_modal_cliente").click(function() {
    var name        = $('#ClientsLegalName').val();
    var nit         = $('#ClientsLegalNit').val();
    var client_id   = $('#ClientsLegalClientId').val();
    if (name != '' && nit != '') {
        $('.btn_guardar_modal_cliente').hide();
        $.post(copy_js.base_url+'ClientsLegals/saveEdit',{name:name,nit:nit,client_id:client_id}, function(result){
            if (result == 0) {
                $('.btn_guardar_modal_cliente').show();
                message_alert("Por favor valida, el NIT ingresado ya se encuentra registrado","error");
            } else {
                $('.btn_guardar_modal_cliente').show();
                list_clients_legal_thecnical(result);
                $('#modal_small').modal('hide');
                message_alert("Cliente creado satisfactoriamente","Bien");
            }
        });
    } else {
        message_alert("Los campos son requerido","error");
    }
});
function list_clients_legal_thecnical(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id}, function(result){
        $("#TechnicalServiceClientsLegalId").select2('destroy');
        $('#TechnicalServiceClientsLegalId').append(result);
        $('#TechnicalServiceClientsLegalId').val(nuevo_id);
        var myVal = $('#TechnicalServiceClientsLegalId option:last').val();
        $('#TechnicalServiceClientsLegalId').val(myVal);

        // $('#TechnicalServiceClientsLegalId').select2();
        setCustomerByTypeSelect2("#TechnicalServiceClientsLegalId",null,'legal');

        dataContacssBusiness(myVal)

        // $("#TechnicalServiceClientsLegalId").trigger('change');
    });
}


$("body").on( "click", ".delete_image", function() {
    var servicio_id         = $(this).data('uid');
    var product_tecnico     = $(this).data('product');
    var image_num           = $(this).data('image');
    var image_name          = $(this).data('texto');
    swal({
        title: "¿Estas seguro que deseas eliminar la foto?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+copy_js.controller+'/deleteImageService',{product_tecnico:product_tecnico,image_num:image_num,image_name:image_name},function(result){
            location.href = copy_js.base_url+copy_js.controller+'/edit/'+servicio_id;
        });
    });
});

$("body").on( "click", ".btn_finalizar", function() {
    var modelo_id           = $(this).data('uid');
    var controlador         = name_controller();
    $.post(copy_js.base_url+copy_js.controller+'/change_state_finalizado',{modelo_id:modelo_id}, function(result){
        $('#modal_servicio_finalizado_body').html(result);
        $('#modal_servicio_finalizado_label').text("¿Estas seguro de cambiar el estado del "+controlador+" a finalizado?");
        $('#modal_servicio_finalizado').modal('show');

        $('#TechnicalServiceReport').summernote({
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['misc', ['undo', 'redo','codeview']],
            ['link', ['linkDialogShow', 'unlink']]
          ],
          fontNames: ['Raleway'],
          focus: true,
          disableResizeEditor: true,
          height: 280,
          placeholder: "Por favor detalla los trabajos realizados en esta orden de servicio"
        });
        $('#TechnicalServiceObservation').summernote({
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['misc', ['undo', 'redo','codeview']],
            ['link', ['linkDialogShow', 'unlink']]
          ],
          fontNames: ['Raleway'],
          focus: true,
          disableResizeEditor: true,
          height: 150,
          placeholder: "Ingresa las observaciones adicionales de este servicio técnico"
        });
        $('.divImagenes').hide();
    });
});

$("body").on( "click", "#btn_servicio_tecnico_cotizacion_true", function() {
    var formData            = new FormData($('#formFinishService')[0]);
    var report              = $('#TechnicalServiceReport').val();
    var observation         = $('#TechnicalServiceObservation').val();
    if (report != '' && observation != '') {
        $("#btn_servicio_tecnico_cotizacion_true").hide();
        $("#btn_servicio_tecnico_cotizacion_false").hide();
        $.ajax({
            type: 'POST',
            url: copy_js.base_url+copy_js.controller+'/changeStateFinalizadoCotizacionTrue',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(result){
                location.href = copy_js.base_url+copy_js.controller+'/flujos?q='+result;
            }
        });
    } else {
        message_alert("Todos los campos son requeridos","error");
    }
});

$("body").on( "click", "#btn_servicio_tecnico_cotizacion_false", function() {
    var formData            = new FormData($('#formFinishService')[0]);
    var report              = $('#TechnicalServiceReport').val();
    var observation         = $('#TechnicalServiceObservation').val();
    if (report != '' && observation != '') {

        swal({
            title: "Alerta",
            text: "Recuerda que debes entregar el equipo al cliente y no puede permanecer en las instalaciones de la empresa, evítate molestias.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: false
        },
        function(value){
            if (value) {
                $("#btn_servicio_tecnico_cotizacion_true").hide();
                $("#btn_servicio_tecnico_cotizacion_false").hide();
                $("#loaderKebco").show();
                $.ajax({
                    type: 'POST',
                    url: copy_js.base_url+copy_js.controller+'/changeStateFinalizadoCotizacionFalse',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(result){
                        location.href = copy_js.base_url+copy_js.controller+'/technical';
                    }
                }); 
            }
        });
        
    } else {
        message_alert("Todos los campos son requeridos","error");
    }
});

function dataContacssBusiness(empresa_id){
    var servicio_id         = 0;
    if (copy_js.action == 'edit') {
        var servicio_id         = $('#TechnicalServiceId').val();
    }
	$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
		$('.selectContact').html(result);
        $('#contac_id').select2();
    });
}

function initialize() {
    var input = document.getElementById('cityForm');
    var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);

$('body').bind('keypress', function(e) {
   
});

$("#txt_buscador").on('keypress', function(e) {
     if(e.keyCode == 13){
        buscadorFiltro();
    }
});

$(".btn_buscar").click(function() {
    buscadorFiltro();
});


function buscadorFiltro(){
    var texto                       = $('#txt_buscador').val();
    var hrefURL                     = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
    var hrefFinal                   = hrefURL+"?q="+texto;
    location.href                   = hrefFinal;
}

$("#texto_busqueda").click(function() {
    location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});

$("#clientes").select2();
$("#brand").select2();
$("#asesor").select2();

$("#muestra").click(function(event) {
    validateShowFields();
});

function validateShowFields(){
    var mod = $("#muestra").data("mod");
    if (mod == "0") {
        $("#muestra").children('.fa-plus').addClass('d-none');
        $("#muestra").children('.fa-minus').removeClass('d-none');
        $("#muestra").data('mod', '1');
        $("#principalBusqueda").show();
    }else{
        $("#muestra").children('.fa-plus').removeClass('d-none');
        $("#muestra").children('.fa-minus').addClass('d-none');
        $("#muestra").data('mod', '0');
        $("#principalBusqueda").hide();
    }
}


$("#form_service").on('submit', function(event) {
    $("#loaderKebco").show();
    setTimeout(function() {
        $("#loaderKebco").hide();
    }, 5000);
});

$("body").on('click', '.classInformeCliente', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.get(copy_js.base_url+'TechnicalServices/send_client_data/'+$(this).data("uid"), function(data) {
        $("#bodyDemora").html(data);
        $("#modalInforme").modal("show");
        $(".body-loading").hide();
        $("#formInformeCliente").parsley();
    });
});