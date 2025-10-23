$('#flujoTiendaCliente').select2();
const formatter = new Intl.NumberFormat('en-US', {
  // style: 'currency',
  // currency: 'USD',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2,
})
load_data_search();

function getDaysByNum($num){
	var num = 0;
	switch ($num) {
		case "1":
			num = 0;
			break;
		case "2":
			num = 20;
			break;
		case "3":
			num = 30;
			break;
		case "4":
			num = 60;
			break;
		case "5":
			num = 90;
			break;
		case "6":
			num = 20;
			break;
		case "7":
			num = 0;
			break;
	}
	return num;
}

$("#showNewChange").click(function(event) {
	event.preventDefault();
	$("#nuevoClienteDiv").toggle();
});

$("#changeCustomerOk").click(function(event) {
	event.preventDefault();
	var cliente_nuevo = $("#flujoTiendaCliente").val();
	if (cliente_nuevo != "") {
		var id = $("#OrderProspectiveUserId").val();
		var contact = $("#contac_id").val();
		$(".body-loading").show();
		$.post(copy_js.base_url+copy_js.controller+'/change_customer', {id: id, cliente:cliente_nuevo,contact:contact}, function(data, textStatus, xhr) {
			location.reload();
		});
	}else{
		 message_alert("Debe seleccionar el cliente.","error");
	}
});


function setDateDeadline(){
	var days 	= getDaysByNum($("#OrderPaymentType").val());
	var dateNow = new Date();

	if (days > 0) {
		dateNow.setDate(dateNow.getDate() + days);
	}

	var year 	= dateNow.getFullYear();
	var month 	= dateNow.getMonth()+1;
    month 		= month < 10 ? '0' + month :  month;
    var day 	= dateNow.getDate() < 10 ? '0' + dateNow.getDate() :  dateNow.getDate();

	$("#OrderDeadline").val(year+"-"+month+"-"+day);
}

setDateDeadline();


$("#OrderPaymentType").change(function(event) {
	setDateDeadline();
});

$("#searchFlowOrder").click(function(event) {
	event.preventDefault();
	var flow = $("#OrderProspectiveUserId").val();
	if (flow != "") {

		location.href = copy_js.base_url+copy_js.controller+'/add'+"?flow="+flow;
	}
});

$("body").on('change', '#flujoTiendaCliente', function(event) {
	var empresa_id = $(this).val();

	if(empresa_id.indexOf("_LEGAL") != -1){
		empresa_id = empresa_id.replace("_LEGAL","");
		var servicio_id = 0;
		$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
			$('.selectContactTienda').html(result);
	        $('#contac_id').select2();
	    });
	}else{
		$('.selectContactTienda').html("");
	}
	
});


$("body").on('click', '.btnChangeRef', function(event) {
    event.preventDefault();
    var href = $(this).data("href");

    $.get(href, function(data) {

        $("#cuerpoModalChangeRef").html(data);
        $("#modalChangeRef").modal("show");

        $("#OrdersProductProductId").select2({dropdownParent: $('#modalChangeRef')});
        /*optional stuff to do after success */
    });
    

    // cuerpoModalChangeRef
    /* Act on the event */
});

$("body").on('click', '.requestCreateClient', function(event) {
    event.preventDefault();

    var id      = $(this).data("uid");
    var type    = $(this).data("type");
    var flujo   = $(this).data("flujo");

    var href = copy_js.base_url+'customer_requests/add?type='+type+'&id='+id+'&flujo='+flujo;
    $.get(href, function(data) {
        $("#cuerpoRequest").html(data);
        $("#CustomerRequestRut").dropify(optionsDp); 
        $("#requestCreate").modal("show");
        var input           = document.getElementById('CustomerRequestCity');
        var autocomplete    = new google.maps.places.Autocomplete(input);
        $("#CustomerRequestAddForm").parsley();
        /*optional stuff to do after success */
    });
    

    // cuerpoModalChangeRef
    /* Act on the event */
});


$("body").on('click', '#changeCustomerDatos', function(event) {
	event.preventDefault();
	type = $(this).data("type");
	$(".cuerpoContactoClienteModal,#ingresoClienteModal").html("");
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type}, function(result){
		$(".cuerpoContactoClienteModal").html(result);
		$("#modalNewOrderTienda").modal("show");	
	});
	$("body").find('#flujoTiendaCliente').val("");
    $("body").find('#flujoTiendaCliente').select2();
	$("body").find('.selectContactTienda').html("");
});


$("body").on('click', '#btn_find_existencia_customr', function(event) {

	event.preventDefault();
	var tipoCliente 		= $("body").find("#CustomerType").val();
	var valueIdentification = $("body").find('#CustomerIdentification').val();
	var valueEmail 			= $("body").find('#CustomerEmail').val();
    var valueType           = $("body").find('#CustomerNacional').val();
	var continueSearch	    = true;

	if(tipoCliente == "2" ){
        if(valueIdentification.length != 9 ){
            message_alert("El NIT  es requerido y debe contener 9 digitos","error");
            continueSearch = false;
            return false;
        }else{
            if(!validateNitNumber($.trim(valueIdentification))){
                message_alert("El nit debe ser un número válido.","error");
                continueSearch = false;
                return false;
            }else if (!/^\d{1,9}$/.test(valueIdentification)) {
              message_alert("El nit debe ser un número válido.","error");
              continueSearch = false;
              return false;
            }
        }
    }else{
        if(  ( $.trim(valueIdentification) == "" || (valueIdentification.length > 0 && valueIdentification.length < 6 ) )  && valueType == "1" ){
            message_alert("La identificación es requerida debe contener mínimo 6 digitos","error");
            continueSearch = false;
            return false;
        }else{
            if(valueIdentification.length > 0 && !validateIdeintificationNumber($.trim(valueIdentification))){
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
		$.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail,nacional:valueType}, function(result){
			if(result != 'null'){
				message_alert("El cliente ya existe","error");
			}else{
				if(tipoCliente == "2"){
					$.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoClienteModal').html(result);
						$("body").find('#ingresoClienteModal').show();
                        $("#documentoForm1").dropify(optionsDp); 
                        $("#documentoForm2").dropify(optionsDp); 
						var input 			= document.getElementById('ContacsUserCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}else{
					$.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail,nacional:valueType}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoClienteModal').html(result);
						$("body").find('#ingresoClienteModal').show();
                        $("#documentoForm1").dropify(optionsDp); 
                        $("#documentoForm2").dropify(optionsDp); 
						var input 			= document.getElementById('ClientsNaturalCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}
			}
		});
	}

});


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


$("body").on('click', '.regresarFormClient', function(event) {
	event.preventDefault();
	$("body").find('#ingresoClienteModal').html("");
	$(".clientsLegalsForm").show();
});

$("body").on('submit', '#formCrearCustomerLegal', function(event) {
	event.preventDefault();

    $(".body-loading").show();
    var formData            = new FormData($('#formCrearCustomerLegal')[0]);
    $.ajax({
        type: 'POST',
        url:  copy_js.base_url+'ClientsLegals/add_customer_legal_post',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            result = $.parseJSON(result);
            $(".body-loading").hide();
            list_clients_legal(result.id)
        }
    });
}).on('submit', '#formCustomerNatural', function(event) {
	event.preventDefault();
    var formData            = new FormData($('#formCustomerNatural')[0]);
    $(".body-loading").show();
    $.ajax({
        type: 'POST',
        url:  copy_js.base_url+'ClientsNaturals/add_customer_post',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            result = $.parseJSON(result);
            $(".body-loading").hide();
            list_clients_natural(result.id)
        }
    });
});

$("body").on( "click", ".icon_add_legal_contac_prospective", function() {
    $('#cityForm').show();
    $('label[for="cityForm"]').show();
    var empresa_id      = $('#flujoTiendaCliente').val();
    empresa_id = empresa_id.replace("_LEGAL","");
    $.post(copy_js.base_url+'ContacsUsers/add_contact_prospective',{empresa_id:empresa_id,modal:1}, function(result){
    	$(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
    	$(".cuerpoContactoClienteModal").show();
        $(".cuerpoContactoClienteModal").html(result);
        $("#modalNewCustomerTienda").modal("show");

        var input 			= document.getElementById('CityContactProspective');
		var autocomplete 	= new google.maps.places.Autocomplete(input);
    });
});

$("body").on( "click", "#btn_find_existencia_contacto", function() {
	var email 			= $('#ContacsUserEmail').val();
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

$("body").on('submit', '#contactoProspectiveUserNew', function(event) {
	event.preventDefault();
	var empresa_id      = $('#ContacsUserEmpresaId').val();
    var name            = $('#ContacsUserName').val();
    var telephone       = $('#ContacsUserTelephone').val();
    var cell_phone      = $('#ContacsUserCellPhone').val();
    var email           = $('#ContacsUserEmail').val();
    var cityForm        = $('#CityContactProspective').val();
	 $.post(copy_js.base_url+'ContacsUsers/addUser',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm}, function(result){
        if (result == 0) {
            $('.btn_guardar_form').show();
            message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
        } else {
            list_contacs_bussines(result);
            message_alert("Contacto creado satisfactoriamente","Bien");
        }
    });
})


function list_clients_natural(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#flujoTiendaCliente').append(result);
    	$("body").find('#flujoTiendaCliente').val(nuevo_id+"_NATURAL");
        $("body").find('#flujoTiendaCliente').select2();
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").hide();
		$("#modalNewCustomerTienda").modal("hide");	
    });
}
function list_clients_legal(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#flujoTiendaCliente').append(result);
    	$("body").find('#flujoTiendaCliente').val(nuevo_id+"_LEGAL");
        $("body").find('#flujoTiendaCliente').select2();
		$("body").find('#flujoTiendaCliente').trigger('change');
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").hide();
		$("#modalNewCustomerTienda").modal("hide");	

    });
}

function list_contacs_bussines(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#contac_id').append(result);
        $('#contac_id').val(nuevo_id);
        $('#contac_id').select2();
        $(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").hide();
		$("#modalNewCustomerTienda").modal("hide");	
    });
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

PRODUCTOS_QUOTATION = [];
PRODUCTO_IDS = [];

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

function calcular_total(){
    showMargen(true);
}

function find_product(name){
    
    $.post(copy_js.base_url+'Products/findName',{name:name}, function(id){
        console.log(id)
        var currency = "cop";
        $.post(copy_js.base_url+'Products/get_data_quotation',{noValidate: 1,id:id,header:$("#OrderHeaderId").val(), products: PRODUCTOS_QUOTATION, money: "0",other:1}, function(result){
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
                    }
                }
            }

            
            calcular_total();
        });
    });

}


$("#cargaCot").click(function(event) {
    event.preventDefault();
    cargaCotizacion();
});

$(".borraDocument").click(function(event) {
    event.preventDefault();
    $(".js-typeahead").show();
    $('#milista').empty();
    $("#OrderFactura").val(0);
    $("#OrderBillCode").removeAttr("readonly");
    $("#OrderBillPrefijo").removeAttr("readonly");
    $("#OrderBillCode").val("");
    $("#OrderBillPrefijo").val("");
    $(".borraDocument").hide();
    $(".cargaDocument").show();
});

$(".cargaDocument").click(function(event) {
    event.preventDefault();

    var number = $("#OrderBillCode").val();
    var prefijo = $("#OrderBillPrefijo").val();

    if (number == "" || prefijo == "") {
        message_alert("El código y prefijo de la factura son obligatorios","error");
    }else{
        $.ajax({
            url: copy_js.base_url+"Orders/get_document",
            type: 'post',
            data: {number,prefijo},
            beforeSend: function(){
                $(".body-loading").show();
            }
        })
        .done(function(response) {
            $('#milista').empty();
            $('#milista').html(response);
            $("#OrderFactura").val(1);
            $(".js-typeahead").hide();
            $(".cargaDocument").hide();
            $("#OrderBillCode").attr("readonly","readonly");
            $("#OrderBillPrefijo").attr("readonly","readonly");
            $(".borraDocument").show();
        })
        .fail(function() {
            $(".js-typeahead").show();
            $('#milista').empty();
            $("#OrderFactura").val(0);
            $("#OrderBillCode").removeAttr("readonly");
            $("#OrderBillPrefijo").removeAttr("readonly");
            $(".borraDocument").hide();
            $(".cargaDocument").show();
            message_alert("Error al consultar","error");
        })
        .always(function() {
            $(".body-loading").hide();
        });
    }

});

function cargaCotizacion(){

    var cotizacion_id           = $('#OrderQuotationId').val();
    if (cotizacion_id != '') {
        $.post(copy_js.base_url+'Quotations/find_products_cotizacion_order',{cotizacion_id:cotizacion_id, header: $("#OrderHeaderId").val(), money: 0, order: 1}, function(result){
            $('#milista').empty();
            $('#milista').html(result);
            showMargen(true);
        });
    }
}

$("body").on('change','.monedasChanges',function(event) {
    var claseData = "."+$(this).data("clase");
    var valor = $(this).val().toLowerCase();
    var costo = $(claseData).attr("data-price-"+valor);
    console.log(costo);
    $(claseData).attr("data-currency",valor);
    $(claseData).attr("data-price",costo);
    $(claseData).trigger('change');
    setTimeout(function() {
        showMargen();
    }, 1000);
    // $(claseData).trigger('keydown');
});

cargaCotizacion();

$("body").on('change', '.valoresCotizacionProductos ', function(event) {
    showMargen();
});

function showMargen(validate = null){
    return true;
    var mayor = 0;
    $("body").find(".valoresCotizacionProductos").each(function(){
        var idTr            = $(this).data("trdata");

        var valorCotizacion =  parseFloat($(this).val());
        var productoId      = $(this).data("id");
        var category        = $(this).data("category");
        var factorImport    = $(this).data("factor");
        var categoryName    = $(this).data("categoryname");
        var cost            = $(this).attr("data-price");
        var min             = $(this).data("min");
        var header          = $(this).data("header");
        var clasetr         = $(this).data("clasetr");
        var type            = $(this).data("type");
        var currency        = $("#OrderHeaderId").val() == "3" ? "usd" : $(this).attr("data-currency");

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
            factorImport
        }
        
        if(currency == "usd" && valorCotizacion >50000){
            mayor = 1;
        }

        $.post(copy_js.base_url+'quotations/show_data', datosSend, function(data, textStatus, xhr) {
            $("body").find("#"+idTr).html(data);
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('[data-toggle="tooltip"]').tooltip()
    });

    if(validate == null){        
        setTimeout(function() {
            if($(".fondo-rojo").length){
                $(".errorMargenReason").show();
                $("#razonMenor").val("");
                $("#razonMenor").attr("required","required")
            }else {
                $(".errorMargenReason").hide();        
                $("#razonMenor").val("");
                $("#razonMenor").removeAttr('required');
                console.log("no existe")
            }
        }, 3000);
    }

    if(mayor == 1){
        $(".errorUsd").show();
    }else{
        $(".errorUsd").hide();        
    }

}


$('#details-country tbody').on( "click", ".deleteProduct", function() {
    var product_id              = $(this).data('uid');

    if ($("#OrderHeaderId").length) {
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

    $(".tooltip").each(function(index, el) {
        $(this).remove();
    });
});


function delete_product_session(product_id){
    
    $("tr.listado_tabla_ordenada").each(function(){
        if($(this).data("producto") == product_id){
            var claseRemove         = $(this).data("clase");
            $("body").find("."+claseRemove).remove();
            $(this).remove();
        }
    });
    productsQuotation()
    
}

$("#OrderDocument").dropify({
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


$("#validateBtnForm").click(function(event) {
    
    var vidFileLength = $("#OrderDocument")[0].files.length;
    var noteVal = $("#OrderNote").val();
    var OrderQuotationId = $("#OrderQuotationId").val();

    var number = $("#OrderBillCode").val();
    var prefijo = $("#OrderBillPrefijo").val();

    var optionsProspective = {
        "bill_code" : number,
        "bill_prefijo" : prefijo,
        "id" : $("#OrderProspectiveUserId").val(),
        "bill_user": $("#OrderUserId").val()
    };
    var optionsFinal = { "ProspectiveUser": optionsProspective };

    console.log(vidFileLength)
    if (OrderQuotationId == "") {
        message_alert("Debes seleccionar una cotización para continuar.","error");
    }else if (vidFileLength == 0 && noteVal == "") {
        message_alert("Se debe agregar la orden de compra en archivo o como nota.","error");
    }else{
        swal({
            title: "¿Estas seguro de guardar el pedido?",
            text: "¿El pedido se guardará automáticamente y no podrá ser modificado ni el flujo se podrá retroceder, está seguro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"No, seguir editando",
            confirmButtonText: "Si, continuar",
            closeOnConfirm: false
        },
        function(value){
            if (value) {
                $(".body-loading").show();

                if ($("#OrderFactura").val() == "1") {
                    $.post(copy_js.base_url+"ProspectiveUsers/save_document", optionsFinal, function(data, textStatus, xhr) {
                        $("#OrderAddForm").trigger('submit');
                    });
                }else{
                    $("#OrderAddForm").trigger('submit');                    
                }

            }else{
                event.preventDefault();
            }
        });
    }

});


$("#OrderAddForm").parsley();


$("body").on( "click", "#editLegalCustomer", function(event) {
    event.preventDefault()
    $('#cityForm2').hide();
    var contact_id          = $(this).data('id');
    var flujo_id            = $('#OrderProspectiveUserId').val();
    $.post(copy_js.base_url+'ContacsUsers/edit_user_form',{contact_id:contact_id,flujo_id:flujo_id,order:"data"}, function(result){
       $('#modal_form_body_edit_contacto').html(result);
        $('#modal_form_label_edit_contacto').text('Editar contacto');
        $('#modal_form_edit_contacto').modal('show');
    });
});
$("body").on( "click", ".btn_editar_ciudad_1", function() {
    $('#cityForm2').show();
});
$("body").on( "click", ".btn_guardar_form_edit_contacto", function() {
    var flujo_id            = $('#ContacsUserFlujoId').val();
    var contac_id           = $('#ContacsUserIdContact').val();
    var name                = $('#ContacsUserName').val();
    var telephone           = $('#ContacsUserTelephone').val();
    var cell_phone          = $('#ContacsUserCellPhone').val();
    var email               = $('#ContacsUserEmail').val();
    var city                = $("#cityForm2").length ? $('#cityForm2').val() : $("#ProspectiveUserCityForm2").val();
    if (city == '') {
        city                = $('#ContacsUserCity').val();
    }
    if (name != '' && city != '' && email != '') {
        $('.btn_guardar_form').hide();
        $.post(copy_js.base_url+'ContacsUsers/editSave',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,contac_id:contac_id},function(result){
            location.reload();
        });
    } else {
        message_alert("Todos los campos son requeridos","error");
    }
});





$("body").on( "click", "#editCustomer", function(event) {
    event.preventDefault();
    $('#cityForm1').hide();
    var cliente_id          = $(this).data('id');
    var flujo_id            = $('#OrderProspectiveUserId').val();

    swal({
        title: "¿Actualización de datos del cliente?",
        text: "Por favor ingresa la identificación del cliente",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "identificación"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la identificación","error");
            return false;
        }
        var identification = inputValue;
        $.post(copy_js.base_url+'ClientsNaturals/edit_flujos',{cliente_id:cliente_id,flujo_id:flujo_id,identification}, function(result){
            $('#modal_form_body_edit_natural').html(result);
            $('#modal_form_label_edit_natural').text('Editar cliente');
            $('#modal_form_edit_natural').modal('show');
            $("#documentoForm1").dropify(optionsDp); 
            $("#documentoForm2").dropify(optionsDp); 
            $("#ClientsNaturalEditFlujosForm").parsley();
            $("#OrderCityForm1").val($("ClientsNaturalCity").val());
        });
    });

    
});
$("body").on( "click", ".btn_editar_ciudad", function() {
    $('#cityForm1').show();
});
$("body").on( "click", ".btn_guardar_form_edit_natural", function() {
    var flujo_id            = $('#ClientsNaturalFlujoId').val();
    var cliente_id          = $('#ClientsNaturalId').val();
    var name                = $('#ClientsNaturalName').val();
    var telephone           = $('#ClientsNaturalTelephone').val();
    var cell_phone          = $('#ClientsNaturalCellPhone').val();
    var email               = $('#ClientsNaturalEmail').val();
    var identification      = $('#ClientsNaturalIdentification').val();
    var city                = $('#cityForm1').length ? $('#cityForm1').val() : $("#ProspectiveUserCityForm1").val();
    var view                = $('#ClientsNaturalAction').val();
    if (city == '') {
        city                = $('#ClientsNaturalCity').val();
    }
    if (name != '' && city != '' && email != '') {
        $('.btn_guardar_form').hide();

        var formData            = new FormData($('#ClientsNaturalEditFlujosForm')[0]);
        $.ajax({
            type: 'POST',
            url:  copy_js.base_url+'ClientsNaturals/addSaveFlujos',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(result){
                location.href = copy_js.base_url+copy_js.controller+'/index'+'?q='+flujo_id;
            }
        });
    } else {
        message_alert("Todos los campos son requeridos","error");
    }
});

$("body").on('click', '.approve', function(event) {
    event.preventDefault();
    $("#modalAprove").modal("show");
});

$("body").on('click', '.reject', function(event) {
    event.preventDefault();
    $("#modalReject").modal("show");
});

$("body").on('submit', '#formAprovee', function(event) {
    event.preventDefault();
    var description = $("#comentarioCotizacionAprobar").val();
    if ( description == '' ) {
        message_alert("Es requerido el mensaje");
        return;
    }else{
        $(".body-loading").show();
        $.post(copy_js.base_url+copy_js.controller+"/change_action", $(this).serialize(), function(data, textStatus, xhr) {
            location.reload();
        });
    }
});

$("body").on('submit', '#formReject', function(event) {
    event.preventDefault();
    var description = $("#comentarioCotizacionReject").val();
    if ( description == '' ) {
        message_alert("Es requerido el mensaje");
        return;
    }else{
        $(".body-loading").show();
        $.post(copy_js.base_url+copy_js.controller+"/change_action", $(this).serialize(), function(data, textStatus, xhr) {
            location.reload();
        });
    }
});

$("body").on('click', '.envioCliente', function(event) {
    event.preventDefault();
    var id = $(this).data("id");
    $(".body-loading").show();
    $.post(copy_js.base_url+copy_js.controller+"/send_order", {id}, function(data, textStatus, xhr) {
        location.reload();
        $(".body-loading").hide();
    });
});


$("body").on('click', '.btn_change_juridico_cliente', function(event) {
    event.preventDefault();
    var cliente = $(this).data("uid");
    var flujo   = $(this).data("flujo");
    $.post(copy_js.base_url+"ProspectiveUsers/change_customer", {flujo,cliente}, function(data, textStatus, xhr) {
        $("#cuerpoCambia").html(data);
        $("#modalChangeNatural").modal("show");
        $("body").find("#changeFormClientData").parsley();
        $("body").on("change","#cuerpoCambia #ClientsNaturalNacional",function(event) {
            if ($(this).val() == "1") {
                $("#cuerpoCambia #ClientsNaturalNit").attr("required","required");
            }else{
                $("#cuerpoCambia #ClientsNaturalNit").removeAttr("required");               
            }
        });
    });
});

$("body").on('submit', '#changeFormClientData', function(event) {
    event.preventDefault();
    var flujoID = $("#ClientsNaturalFlujoId").val();
    $.post(copy_js.base_url+copy_js.controller+"/change_post_customer", $(this).serialize(), function(data, textStatus, xhr) {
        location.reload();
    });
});


$('body').on("keyup mouseup change", ".cantidadProduct", function() {
    var product_id      = $(this).data('uid');
    var cantidad        = $('#tr_'+product_id+' .cantidad .cantidadProduct').val();
    if (cantidad == '') {
        cantidad = 0;
    } else {
        cantidad        = parseFloat(cantidad);
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
    var total_producto  = cantidad*precio;
    // total_producto      = number_format(total_producto);
    $('#tr_'+product_id+' .subtotal').text(formatter.format(total_producto));
    // calcular_total();
    // productsQuotation();
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
    // total_producto      = number_format(total_producto);
    // if (precio_db !=0) {
    //     $('#tr_'+product_id+' .precio #precio_item').val(number_format(precio_db));
    // }
    $('#tr_'+product_id+' .subtotal').text(formatter.format(total_producto));
});