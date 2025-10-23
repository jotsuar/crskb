$(document).ready(function () {
    $('.btn_guardar_cantidad').hide();
});

$("body").on( "click", ".btn_aprobar", function() {
    var import_id            = $(this).data('uid');
    swal({
        title: "¿Estas seguro que deseas aprobar esta solicitud de importación?",
        text: "Luego tendrás que hacer el envío de la orden al proveedor",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+'ProspectiveUsers'+'/updateStateApproved',{import_id:import_id}, function(result){
            location.reload();
        });
    });
});



$("body").on( "click", ".btn_rechazar", function(event) {
    event.preventDefault();
    var import_id            = $(this).data('uid');
    swal({
        title: "¿Estas seguro que deseas rechazar la importación?",
        text: "¿Deseas continuar con la acción?",
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
            message_alert("Por favor ingresa el motivo por el cual vas a rechazar la importación","error");
            return false;
        }
        $.post(copy_js.base_url+'ProspectiveUsers'+'/updateStateRejectec',{import_id:import_id,motivo:inputValue}, function(result){
            location.href =copy_js.base_url+'ProspectiveUsers'+'/imports_revisions';
        });
    });
});


$("body").on( "click", ".newOrder", function(event) {
    event.preventDefault();
    var import_id            = $(this).data('id');
    swal({
        title: "Adjuntar orden generada en el sistema de GRACO",
        text: "Por favor ingrese el número de órden generada en el sistema de graco para esta solicitud de importación",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputType: "number",
        inputPlaceholder: "Número de orden en GEDI",
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingrese el número de orden","error");
            return false;
        }
        $.post(copy_js.base_url+'ProspectiveUsers'+'/update_orden_import',{id:import_id,orden_proveedor:inputValue}, function(result){
            location.reload();
        });
    });
});

$("body").on( "click", ".btn_editar_cantidad", function() {
    var id_fila                 = $(this).data('uid');
    var id_product              = $(this).data('product_id');
    var clase                   = "."+$(this).data('class');
    var claseForm               = ".divFormCantidades_"+$(this).data('product_id');
    $(clase).removeAttr('readonly'); 
    $(clase).show(); 
    $(clase).focus(); 
    $("#bodyCantidades").html("");
    $("#bodyCantidades").html($(claseForm).html());
    $("#cantidadesModal").modal("show");
});

$("body").on( "click", ".btn_guardar_cantidad", function() {
    var id_fila                 = $(this).data('uid');
    var id_product              = $(this).data('product_id');
    var cantidad                = $('#cantidad_'+id_product).val();
    $.post(copy_js.base_url+'Products'+'/editQuantityProductImport',{id_fila:id_fila,cantidad:cantidad}, function(result){
        location.reload();
    });
});

$("#pdfGenerate,.pdfGenerate").click(function(event) {
    event.preventDefault();
    uid = $(this).data("uid");

    var urlPdf = copy_js.base_url+'ImportRequests/generatePdfImport/'+uid;
    $(".body-loading").show();

    $.post(urlPdf, {}, function(response, textStatus, xhr) {
        window.open(response);
        $(".body-loading").hide();
    });

    
});


$("#pdfGenerateDetail,.pdfGenerateDetail").click(function(event) {
    event.preventDefault();
    uid = $(this).data("uid");

    var urlPdf = copy_js.base_url+'ImportRequests/generatePdfImportDetail/'+uid;
    $(".body-loading").show();

    $.post(urlPdf, {}, function(response, textStatus, xhr) {
        window.open(response);
        $(".body-loading").hide();
    });    
});


$("body").on('click', '.viewInModal', function(event) {
    event.preventDefault();
    var url = $(this).attr("href");
    $("#cuerpoViewImport").html("");
    $.post(url, {modal: 2}, function(data, textStatus, xhr) {
        $("#cuerpoViewImport").html(data);
        $("#modalViewImport").modal("show");
    });
}); 

$("body").on('click', '.viewInventory', function(event) {
    event.preventDefault();
    var brand_id = $(this).data("id");
    $(".body-loading").show();
    $("#bodyInventario").html("");
    $.get(copy_js.base_url+'products/get_inventory_brand/'+brand_id, {}, function(response, textStatus, xhr) {
        $("#modalInventario").modal("show");
        $("#bodyInventario").html(response);
        $("#form_productModal").parsley();

        setDataTableInfo();
        $(".body-loading").hide();
    });
    
});

function setDataTableInfo(clone = true){

    if ($("#noProductsInformation").length) {
        return true;
    }

  $('.tblProcesoSolicitud2').DataTable( {
    'iDisplayLength': 5,
    "lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
    "ordering": false,
    paging: false,
    "language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
  } );
}

$("body").on('click', '.mostradDatos', function(event) {
    event.preventDefault();
    var idMostrar = "#"+$(this).data("id");
    $(idMostrar).toggle();
});

$(".mostradDatosFact").click(function(event) {
    event.preventDefault();
    $("#bodyDetalleFactura").html("");

    var newid = "#"+$(this).data("id");
    $("#bodyDetalleFactura").html($(newid).html());
    $("#modalFacturaDetalle").modal("show");
});


$('.boton-actualizar').on('click', function() {

        // Obtenemos datos del botón que fue presionado
        var boton = $(this); // Guardamos referencia al botón
        var registroId = boton.data('id');
        var importId = boton.data('import');
        var valorActual = boton.data('actual');

        // Mostramos el diálogo de SweetAlert v1 para ingresar el número
        swal({
            title: "Actualizar Número", // Título del diálogo
            text: `Introduce el nuevo número para la importación ID: ${valorActual}`, // Mensaje
            type: "input",             // Tipo input para pedir un valor
            inputType: "number",       // Sugiere un input de tipo numérico (visual, no valida por sí solo)
            showCancelButton: true,    // Muestra el botón de cancelar
            closeOnConfirm: false,     // No cierra al confirmar, para manejar AJAX
            animation: "slide-from-top", // Animación (opcional)
            inputValue: valorActual,   // Pre-rellena el campo con el valor actual
            confirmButtonText: "Actualizar", // Texto del botón de confirmar
            cancelButtonText: "Cancelar",   // Texto del botón de cancelar
            showLoaderOnConfirm: true // Muestra un spinner mientras se ejecuta el AJAX

        }, function(inputValue) { // Función callback que se ejecuta al confirmar o cancelar

            // 1. Manejo si el usuario cancela
            if (inputValue === false) {
                return false; // No hace nada si cancela
            }

            // 2. Manejo si el usuario no ingresa nada
            if (inputValue === "") {
                swal.showInputError("¡Debes ingresar un valor!"); // Muestra error de input vacío
                return false;
            }

            // 3. Validación (opcional pero recomendada): Verificar si es realmente un número
            if (isNaN(inputValue)) {
                swal.showInputError("¡Debes ingresar un número válido!");
                return false;
            }

            // Si todo está bien, procedemos con AJAX
            var nuevoNumero = inputValue;

            // 4. Petición AJAX con jQuery
            $.ajax({
                url: copy_js.base_url+'import_requests/update_field/', // <-- ¡IMPORTANTE! Cambia esto a la URL de tu backend
                method: 'POST', // O 'PUT', según tu API REST
                data: {
                    id: registroId,         // El ID del registro a actualizar
                    nuevo_valor: nuevoNumero // El nuevo número ingresado
                },
                success: function(response) {
                    // 5. Éxito de AJAX: El servidor respondió bien
                    swal({
                         title: "¡Éxito!",
                         text: "El número se actualizó correctamente.",
                         type: "success",
                         timer: 2000, // Cierra automáticamente después de 2 segundos
                         showConfirmButton: false // Oculta el botón de confirmación en el mensaje de éxito
                     });

                    // Opcional: Actualizar el valor en el botón o en la UI directamente
                    boton.data('valor-actual', nuevoNumero);
                    boton.html(nuevoNumero);
                    // Podrías también actualizar algún texto en la página:
                    // $('#valor-display-' + registroId).text(nuevoNumero);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // 6. Error de AJAX: Hubo un problema
                    var mensajeError = "No se pudo actualizar el número. Intenta de nuevo.";
                    // Intenta obtener un mensaje de error más específico del servidor si lo envía
                     if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        mensajeError = jqXHR.responseJSON.message;
                    } else if (jqXHR.responseText) {
                         try {
                           const parsedError = JSON.parse(jqXHR.responseText);
                           if(parsedError && parsedError.message) {
                             mensajeError = parsedError.message;
                           }
                         } catch(e) {
                            // Si no es JSON, usa el texto genérico o el status
                            console.error("Error AJAX:", textStatus, errorThrown);
                         }
                    }

                    swal("Error", mensajeError, "error");
                }
            }); // Fin de $.ajax
        }); // Fin de la función callback de swal
    }); // Fin del .on('click')