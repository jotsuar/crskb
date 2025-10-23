function setClient(element){
	$("body").find(".gestionCliente").show();
	$("body").find(".gestionFlujoEspecial").hide();

	setCustomerSelect2(".flujoTiendaClienteEspecial");

	// $('.flujoTiendaClienteEspecial').select2();

	$("body").on('change', '.flujoTiendaClienteEspecial', function(event) {
		var idContactoDiv = "#"+$(this).data("contacto")
		var empresa_id = $(this).val();

		if(empresa_id.indexOf("_LEGAL") != -1){
			empresa_id = empresa_id.replace("_LEGAL","");
			var servicio_id = 0;
			$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
				$("body").find(idContactoDiv).html(result);
		    });
		}else{
			$("body").find(idContactoDiv).html("");
		}
		
	});	
}


$("body").on('click', '.gestionFlujoEspecial', function(event) {
	event.preventDefault();
	setClient($(this));
});

$("body").on('click', '.addNewCustomerProspectiveClienteEspecial', function(event) {
	var idContactoDiv = "#"+$(this).data("contacto")
	event.preventDefault();
	type = $(this).data("type");
	$(".cuerpoContactoClienteModalEspecial,#ingresoClienteModalEspecial").html("");
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type}, function(result){
		$(".cuerpoContactoClienteModalEspecial").html(result);
		$("#modalNewCustomerEspecial").modal("show");	
	});
	$("body").find('.flujoTiendaClienteEspecial').val("");
    setCustomerSelect2(".flujoTiendaClienteEspecial");
	$("body").find(idContactoDiv).html("");
});

$("body").on('click', '#btn_find_existencia_customr', function(event) {

	event.preventDefault();
	var tipoCliente 		= $("body").find("#CustomerType").val();
	var valueIdentification = $("body").find('#CustomerIdentification').val();
	var valueEmail 			= $("body").find('#CustomerEmail').val();
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
		if(valueIdentification.length > 0 && valueIdentification.length <= 6 ){
			message_alert("La identificación debe contener mínimo 6 digitos","error");
			continueSearch = false;
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
		$.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail}, function(result){
			if(result != 'null'){
				message_alert("El cliente ya existe","error");
			}else{
				if(tipoCliente == "2"){
					$.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoClienteModalEspecial').html(result);
						$("body").find('#ingresoClienteModalEspecial').show();
						var input 			= document.getElementById('ContacsUserCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}else{
					$.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoClienteModalEspecial').html(result);
						$("body").find('#ingresoClienteModalEspecial').show();
						var input 			= document.getElementById('ClientsNaturalCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}
			}
		});
	}

});

$("body").on('submit', '#formCrearCustomerLegal', function(event) {
	event.preventDefault();
	$.post(copy_js.base_url+'ClientsLegals/add_customer_legal_post?especial_api=1',$(this).serialize(), function(result){
		result = $.parseJSON(result);
		if(result.error == 1){
			message_alert("El número de celular ya está registrado","error");
		}else{
			list_clients_legalEspecial(result.id)
		}	
	});
}).on('submit', '#formCustomerNatural', function(event) {
	event.preventDefault();
	$.post(copy_js.base_url+'ClientsNaturals/add_customer_post?especial_api=1',$(this).serialize(), function(result){
		result = $.parseJSON(result);
		if(result.error == 1){
			message_alert("El número de celular ya está registrado","error");
		}else{
			list_clients_naturalEspecial(result.id)
		}
	});
});

$("body").on('click', '.regresarFormClient', function(event) {
	event.preventDefault();
	$("body").find('#ingresoClienteModalEspecial').html("");
	$(".clientsLegalsForm").show();
});

function list_clients_naturalEspecial(nuevo_id){
	console.log(nuevo_id)
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('.flujoTiendaClienteEspecial').append(result);
    	$("body").find('.flujoTiendaClienteEspecial').val(nuevo_id+"_NATURAL");
        
		$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").html("");
		$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").hide();
		$("#modalNewCustomerEspecial").modal("hide");	
		if($(".newDataCustom").length){
			$("#modalNewCustomerEspecialAsingacion").modal("show");
		}
    });
}
function list_clients_legalEspecial(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('.flujoTiendaClienteEspecial').append(result);
    	$("body").find('.flujoTiendaClienteEspecial').val(nuevo_id+"_LEGAL");
        
		$("body").find('.flujoTiendaClienteEspecial').trigger('change');
		$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").html("");
		$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").hide();
		$("#modalNewCustomerEspecial").modal("hide");	
		if($(".newDataCustom").length){
			$("#modalNewCustomerEspecialAsingacion").modal("show");
		}
    });
}

function list_contacs_bussinesEspecial(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#contac_id').append(result);
        $('#contac_id').val(nuevo_id);
        $('#contac_id').select2();
        $(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").html("");
		$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").hide();
		$("#modalNewCustomerEspecial").modal("hide");	
		if($(".newDataCustom").length){
			$("#modalNewCustomerEspecialAsingacion").modal("show");
		}
    });
}


$("body").on('submit', '#contactoProspectiveUserNew', function(event) {
	event.preventDefault();
	var empresa_id      = $('#ContacsUserEmpresaId').val();
    var name            = $('#ContacsUserName').val();
    var telephone       = $('#ContacsUserTelephone').val();
    var cell_phone      = $('#ContacsUserCellPhone').val();
    var email           = $('#ContacsUserEmail').val();
    var cityForm        = $('#CityContactProspective').val();
    var concession_id 		= $('#ContacsUserConcessionId').val();

	 $.post(copy_js.base_url+'ContacsUsers/addUser',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm,concession_id:concession_id}, function(result){
        if (result == 0) {
            $('.btn_guardar_form').show();
            message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
        } else {
            list_contacs_bussinesEspecial(result);
            message_alert("Contacto creado satisfactoriamente","Bien");
        }
    });
})


$("body").on( "click", "#gestionCliente .icon_add_legal_contac_prospective", function() {
    $('#cityForm').show();
    $('label[for="cityForm"]').show();
    var empresa_id      = $('.flujoTiendaClienteEspecial').val();
    empresa_id = empresa_id.replace("_LEGAL","");
    $.post(copy_js.base_url+'ContacsUsers/add_contact_prospective',{empresa_id:empresa_id,modal:1}, function(result){
    	$(".cuerpoContactoClienteModalEspecial, #ingresoClienteModalEspecial").html("");
    	$(".cuerpoContactoClienteModalEspecial").show();
        $(".cuerpoContactoClienteModalEspecial").html(result);
        $("#modalNewCustomerEspecial").modal("show");

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


$("body").on('click', '.gestionarCliente', function(event) {
	event.preventDefault();
	var id = $(this).data("flujo");
	var cliente = $(this).parent('div').parent("div").parent('div').parent('div#gestionCliente').find("#flujoTiendaClienteEspecial").val() ;
	//$("#flujoTiendaClienteEspecial").val()
	var contacto = 0;

	if(cliente == ""){
		message_alert("Por favor selecciona un cliente","error");
	}else{
		if(cliente.indexOf("_LEGAL") != -1 ){
			cliente = cliente.replace("_LEGAL","");
			contacto = $(".selectContactEspecial").find('#contac_id').val();

			if(typeof contacto == "undefined"){
				message_alert("Por favor selecciona un contacto","error");
				contacto = null;
			}

		}else{
			cliente = cliente.replace("_NATURAL","");
		}

		if(contacto == null){
				message_alert("Por favor selecciona un contacto","error");
				contacto = null;
		}else{
			$.post(copy_js.base_url+'ProspectiveUsers/asign_customer_flujo_especial', {id,contacto,cliente}, function(data, textStatus, xhr) {
				location.href = copy_js.base_url+'ProspectiveUsers/index?q='+id;
			});
		}

	}

});