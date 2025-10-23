PRODUCTS_ADD = [];
// $('#flujoTiendaCliente').select2();

setCustomerSelect2("#flujoTiendaCliente",null);

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


$("body").on('click', '.deleteProduct', function(event) {
    event.preventDefault();
    var id = $(this).data("uid");

    position        = -1;

    for (i in PRODUCTS_ADD){
        if(PRODUCTS_ADD[i].productoId == id){
            position = i;
            break;
        }
    }

    if(position != -1){
        PRODUCTS_ADD.splice(position, 1);
    }

    $(this).parent("td").parent("tr").remove();
    calcular_total();

});

$("body").on('click', '.addNewCustomerProspectiveCliente ', function(event) {
	event.preventDefault();
	type = $(this).data("type");
	$(".cuerpoContactoClienteModal,#ingresoClienteModal").html("");
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type}, function(result){
		$(".cuerpoContactoClienteModal").html(result);
		$("#modalNewCustomerTienda").modal("show");	
	});
	$("body").find('#flujoTiendaCliente').val("");
    setCustomerSelect2("#flujoTiendaCliente",null);
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
            message_alert("La identificación y es requerida debe contener mínimo 6 digitos","error");
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
            list_clients_legal_tienda(result.id)
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
            list_clients_natural_tienda_tienda(result.id)
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
            list_contacs_bussines_tienda(result);
            message_alert("Contacto creado satisfactoriamente","Bien");
        }
    });
})


function list_clients_natural_tienda(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#flujoTiendaCliente').append(result);
    	$("body").find('#flujoTiendaCliente').val(nuevo_id+"_NATURAL");
        setCustomerSelect2("#flujoTiendaCliente",null);
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").hide();
		$("#modalNewCustomerTienda").modal("hide");	
    });
}
function list_clients_legal_tienda(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#flujoTiendaCliente').append(result);
    	$("body").find('#flujoTiendaCliente').val(nuevo_id+"_LEGAL");
        setCustomerSelect2("#flujoTiendaCliente",null);
		$("body").find('#flujoTiendaCliente').trigger('change');
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").html("");
		$(".cuerpoContactoClienteModal, #ingresoClienteModal").hide();
		$("#modalNewCustomerTienda").modal("hide");	

    });
}

function list_contacs_bussines_tienda(nuevo_id){
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
    $.post(copy_js.base_url+'Products/paintDataTwo',{tienda: 1}, function(result){
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
                    display: "part_number",
                    data: dataController
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    findName(item.id,item.part_number);
                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                }
            },
            debug: true
        });
    });
}

$("body").on('submit', '#formCreateTienda', function(event) {
	if(PRODUCTS_ADD.length < 1){
		var mensaje         = "Se debe ingresar un producto";
        var tipo            = "error";
        message_alert(mensaje,tipo);
        return false;
	}
});

function findName(id,name){
    console.log(id)
	position = -1;

    if(PRODUCTS_ADD.length > 0 ){
        for (i in PRODUCTS_ADD){
            if(PRODUCTS_ADD[i].productoId == id){
                position = i;
                break;
            }
        }
    }
    
    if (position != -1) {
        var mensaje         = "El producto ya fue ingresado";
        var tipo            = "error";
        message_alert(mensaje,tipo);
        return false;
    }
    producto = {productoId: id};
    PRODUCTS_ADD.push(producto)
	$.post(copy_js.base_url+'Products/findName',{name:name,tienda: 1}, function(id){
        $.post(copy_js.base_url+'Products/get_data_tienda',{id:id,other:1}, function(result){
            var num = parseInt(result);
            if (num > 0) {
                update_cantidad_tr(num);
            } else {
                if (result == 'editar') {
                    edit_product_maxDescripcion(id);
                } else {
                    $('#milista').append(result);
                }
            }
            calcular_total();
        });
    });
}

$("body").on('change', ".valoresCotizacionProductos", function(event) {
    var precio = parseFloat($(this).val());
    var margenMin = parseInt($(this).data("min"));
    var costo  = parseFloat($(this).data("price"));

    var idMargen = "#"+$(this).data("margen");

    margen = 0;

    if (precio > 0) {
        margen = ((precio-costo) /precio )* 100;
        margen = Math.round(margen);
    }

    if (parseInt(margen) < margenMin ) {
        $(idMargen).removeClass('bg-success');
        $(idMargen).addClass('bg-danger');
    }else{
        $(idMargen).removeClass('bg-danger');
        $(idMargen).addClass('bg-success');
    }

    $(idMargen).html(margen.toString() + "%")
    
}); 

function calcular_total(){
    var resultado = 0;
    $("body").find(".valoresCotizacionProductos").each(function(){
        resultado += ( parseFloat($(this).val()) * parseFloat( $(this).parent("td").next("td").find('.cantidadProduct').val() ) ) ;
    });
    console.log(resultado);
    resultado = formatter.format(resultado);
    $('#totalCalculado').val(resultado);    
}

$('body').on("keyup mouseup", ".cantidadProduct", function() {
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
    calcular_total();
});

$('body').on("keyup", "#ProductListPriceUsd", function(event) {
    var precio              = $('#ProductListPriceUsd').val();
    var precio_final        = number_format(precio);
    $('#ProductListPriceUsd').val(precio_final);
});


$('body').on("keyup", "#precio_item", function(event) {
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
        var precio              = precio_db;
    }
    var total_producto  = cantidad*precio;

    $('#tr_'+product_id+' .subtotal').text(formatter.format(total_producto));
    calcular_total();
});

const formatter = new Intl.NumberFormat('en-US', {
  // style: 'currency',
  // currency: 'USD',
  minimumFractionDigits: 2
})
function createMovimiento(id){

    position = -1;

    if(PRODUCTS_ADD.length > 0 ){
        for (i in PRODUCTS_ADD){
            if(PRODUCTS_ADD[i].productoId == id){
                position = i;
                break;
            }
        }
    }
    
    if (position != -1) {
        var mensaje         = "El producto ya fue ingresado";
        var tipo            = "error";
        message_alert(mensaje,tipo);
        return false;
    }

    $.post(copy_js.base_url+'Inventories/create_movement',{id}, function(result){
        $("#cuerpoMovimiento").html(result);
        $("#modalMovimiento").modal("show");
    });
}

load_data_search();