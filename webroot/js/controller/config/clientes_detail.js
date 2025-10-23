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

$("#descargaInfo").click(function(){

  $("#naturalpersontable2").table2excel({

    // exclude CSS class

    exclude:".noExl",

    name:"Datos cliente",

    filename:"datos_clientes_",//do not include extension

    fileext:".xlsx" // file extension

  });

});


$("body").on('click', '.detailCliente', function(event) {
	event.preventDefault();
	var anio =  $(this).data("anio");
	var id   =  $(this).data("id");
	$(".body-loading").show();
	$.post(copy_js.base_url+'pages/get_info_detail',{anio,id}, function(result){
		$("#detalleVentas").html("");
		$('#detalleVentas').html(result);
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#modalFacturaDetalle').modal('show');
		$(".body-loading").hide();
		$("#divDetalle").hide();

	});
});


$("#asignaFlujos").on('click', function(event) {
	event.preventDefault();

	$("#modalAsignarFlujos").modal("show");
});

$("#creaFlujo").click(function(event) {
	var user_id = $("#user_id_flow").val();
	if (user_id == "") {
		message_alert("Debe seleccionar un asesor para asignar los flujos","error");
	}else{
		var idsClientes = [];

		$(".checkB").each(function(index, el) {
			if ($(this).prop("checked")) {
				idsClientes.push($(this).val());
			}
		});

		if (idsClientes.length > 0) {
			$("#modalAsignarFlujos").hide()
			$("#loaderKebco").show();
			var clientesFinal = DATA_CLIENTS.filter( cliente => idsClientes.indexOf(cliente.Identificacion) != -1 );
			var dataSend = {
				user_id,
				anio: ANIO_CONSULTA,
				clientes: clientesFinal
			};
			$.post(rootUrl+'prospective_users/create_flow_retoma', dataSend, function(data, textStatus, xhr) {
				$("#loaderKebco").hide();
				if ($.trim(data) != "1") {
					message_alert("Se creron los siguientes flujos "+ data ,"Bien");
					setTimeout(function() {
					   location.reload();
					}, 5000);
				}else{
					message_alert("Error al crear los flujos","error");
				}
			});
			console.log(dataSend);
		}
	}
});


$("body").on('click', '.createFlow', function(event) {
	event.preventDefault();
	var anio =  $(this).data("anio");
	var id   =  $(this).data("id");
	$(".body-loading").show();
	$.post(copy_js.base_url+'pages/create_flow_client',{anio,id}, function(result){
		$("#bodyCrearFlujoCliente").html("");
		$('#bodyCrearFlujoCliente').html(result);
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#modalCrearFlujoCliente').modal('show');
		$(".body-loading").hide();
		setTimeout(function() {
			$("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').trigger('change');
			$("#ProspectiveUserImage").dropify(optionsDp); 
		}, 1000);
	});
});

$("body").on('click', '.addNewCustomerProspectiveWo', function(event) {
	event.preventDefault();
	type = $(this).data("type");
	$(".cuerpoContactoClienteWO").html("");
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type, flujo: 1}, function(result){
		$(".cuerpoContactoClienteWO").html(result);
		$(".cuerpoFlujoAddWO").hide();
		$(".cuerpoContactoClienteWO").show();
	});
	// $("body").find('#ProspectiveUserClientsNaturalId').val("");
	$("body").find('.selectContact').html("");

});

$(".cuerpoContactoClienteWO").on('click', 'a.returnContactProspective', function(event) {
	event.preventDefault();
	$(".cuerpoContactoClienteWO").html("");
	$(".cuerpoContactoClienteWO").hide();
	$(".cuerpoFlujoAddWO").show();
	$("body").find('.cuerpoContactoClienteWO .cuerpoContactoClienteWO').html("");
}).on('click', 'a#btn_validate_exist_customer', function(event) {
	event.preventDefault();
	var tipoCliente 		= $("body").find("#modalCrearFlujoCliente #CustomerType").val();
	var valueIdentification = $("body").find('#modalCrearFlujoCliente #CustomerIdentification').val();
	var valueEmail 			= $("body").find('#modalCrearFlujoCliente #CustomerEmail').val();
	var valueType           = $("body").find('#modalCrearFlujoCliente #CustomerNacional').val();
	var continueSearch	    = true;

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

	console.log(continueSearch)

	if (continueSearch){
		$.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail}, function(result){
			if(result != 'null'){
				message_alert("El cliente ya existe","error");
			}else{
				if(tipoCliente == "2"){
					$.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification, flujo: 1,nacional:valueType}, function(result){
						$("body").find("#modalCrearFlujoCliente .clientsLegalsForm").hide();
						$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').html(result);
						$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').show();
						var input 			= document.getElementById('ContacsUserCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
						$("body").find('#modalCrearFlujoCliente #documentoForm1').dropify(optionsDp); 
						$("body").find('#modalCrearFlujoCliente #documentoForm2').dropify(optionsDp); 
					});
				}else{
					$.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail, flujo: 1,nacional:valueType}, function(result){
						$("body").find("#modalCrearFlujoCliente .clientsLegalsForm").hide();
						$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').html(result);
						$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').show();
						$("body").find('#modalCrearFlujoCliente #documentoForm1').dropify(optionsDp); 
						$("body").find('#modalCrearFlujoCliente #documentoForm2').dropify(optionsDp); 
						var input 			= document.getElementById('ClientsNaturalCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}
			}
		});
	}
});

$("body").on('click', '#modalCrearFlujoCliente .regresarFormClient', function(event) {
	event.preventDefault();
	$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').html("");
	$(".clientsLegalsForm").show();
});

$("body").on('submit', '#modalCrearFlujoCliente #formFlujoCrearCustomerLegal', function(event) {
	event.preventDefault();

	$(".body-loading").show();
	var formData            = new FormData($('#modalCrearFlujoCliente #formFlujoCrearCustomerLegal')[0]);
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
			list_clients_legal_wo(result.id)
        }
    });

}).on('submit', '#modalCrearFlujoCliente #formFlujoCustomerNatural', function(event) {
	event.preventDefault();
	
	var formData = new FormData($('#modalCrearFlujoCliente #formFlujoCustomerNatural')[0]);
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
			list_clients_natural_wo(result.id)
            $(".body-loading").hide();
        }
    });
});


function list_clients_natural_wo(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').append(result);
    	$("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').val(nuevo_id+"_NATURAL");
		$(".cuerpoContactoClienteWO, #ingresoClienteWO").html("");
		$(".cuerpoContactoClienteWO, #ingresoClienteWO").hide();
		$(".cuerpoFlujoAddWO").show();
    });
}
function list_clients_legal_wo(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').append(result);
    	$("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').val(nuevo_id+"_LEGAL");
		$("body").find('#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId').trigger('change');
		$(".cuerpoContactoClienteWO, #ingresoClienteWO").html("");
		$(".cuerpoContactoClienteWO, #ingresoClienteWO").hide();
		$(".cuerpoFlujoAddWO").show();

    });
}


$("body").on('change', '#modalCrearFlujoCliente #ProspectiveUserClientsNaturalId', function(event) {
	var empresa_id = $(this).val();

	if(empresa_id.indexOf("_LEGAL") != -1){
		empresa_id = empresa_id.replace("_LEGAL","");
		var servicio_id = 0;
		$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
			$('.selectContact').html(result);
	    });
	}else{
		$('.selectContact').html("");
	}	
}).on( "click", "#modalCrearFlujoCliente .icon_add_legal_contac_prospective", function() {
    $('#cityForm').show();
    $('label[for="cityForm"]').show();
    var empresa_id      = $('#ProspectiveUserClientsNaturalId').val();
    empresa_id = empresa_id.replace("_LEGAL","");
    $.post(copy_js.base_url+'ContacsUsers/add_contact_prospective',{empresa_id:empresa_id}, function(result){
    	$(".cuerpoFlujoAddWO").hide();
    	$(".cuerpoContactoClienteWO").show();
        $(".cuerpoContactoClienteWO").html(result);

        var input 			= document.getElementById('CityContactProspective');
		var autocomplete 	= new google.maps.places.Autocomplete(input);
    });
}).on('click', '.returnContactProspective', function(event) {
	$(".cuerpoContactoClienteWO").html("");
	$(".cuerpoContactoClienteWO").hide();
	$(".cuerpoFlujoAddWO").show();
	$("body").find('#modalCrearFlujoCliente #ingresoClienteWO').html("");
}).on('submit', '#modalCrearFlujoCliente #contactoProspectiveUserNew', function(event) {
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
            list_contacs_bussines_wo(result);
            message_alert("Contacto creado satisfactoriamente","Bien");
        }
    });
})


function list_contacs_bussines_wo(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#contac_id').append(result);
        $('#contac_id').val(nuevo_id);

        $(".cuerpoContactoClienteWO").html("");
		$(".cuerpoContactoClienteWO").hide();
		$(".cuerpoFlujoAddWO").show();
    });
}


$("body").on('click', '.vewInfoCode', function(event) {
	event.preventDefault();
	var factura = $(this).data("code");
	$("#divListado").hide();
	$("#divDetalle").show();
	$("#facturaDetalleWO").html("");

	const arrText = factura.split(" ");

	var prefijo   = arrText[0];
	var number    = arrText[1];

	$.ajax({
        url: copy_js.base_url+"ProspectiveUsers/get_document",
        type: 'post',
        data: {number,prefijo,nuevo: 1},
        beforeSend: function(){
            $(".body-loading").show();
        }
    })
    .done(function(response) {
        $("#facturaDetalleWO").html(response)
    })
    .fail(function() {
        message_alert("Error al consultar","error");
    })
    .always(function() {
        $(".body-loading").hide();
    });

});

$("body").on('click', '#regresaInfoDetalle', function(event) {
	event.preventDefault();
	$("#divListado").show();
	$("#divDetalle").hide();
});


function validateChecks(){
	var totalChecks   = 0;
	var totalSelected = 0;

	$(".checkB").each(function(index, el) {
		totalChecks++;
		if($(this).prop("checked")){
			totalSelected++;
		}
	});

	if (totalSelected > 0) {
		$("#asignaFlujos").show();
	}else{
		$("#asignaFlujos").hide();		
	}
}

validateChecks();

$(".checkB").change(function(event) {

	var totalSelected = 0;

	$(".checkB").each(function(index, el) {
		if($(this).prop("checked")){
			totalSelected++;
		}
	});

	if (totalSelected > 5) {
		$(this).prop("checked",false);
		event.preventDefault();
		return false; 
	}else{
		validateChecks();
	}

});