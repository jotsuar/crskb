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

$("#nuevoFlujoCrmData").click(function() {
	$("body").find(".cuerpoFlujoAdd").html("");
	$("body").find(".cuerpoContactoCliente").html("");
	$("body").find(".cuerpoContactoCliente").html("");
	$("body").find("#agregarFlujoModal #ingresoCliente").html("");
	var url 	= copy_js.base_url+'ProspectiveUsers/add_prospective';
	$.post(url, {}, function(response, textStatus, xhr) {
		$(".cuerpoFlujoAdd").html(response);
		$(".cuerpoFlujoAdd").show();
		$(".cuerpoContactoCliente").hide();
		setTimeout(function() {
			setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");
		}, 1000);
		$("#ProspectiveUserImage").dropify(optionsDp); 
	});
	$("#agregarFlujoModal").modal("show");	
});


$("body").on('change', '#tipoClienteProspective', function(event) {
	if($(this).val() == "2"){
		$("body").find(".clienteLegalProspective").show();
		$("body").find(".clienteNaturalProspective").hide();
		$("body").find("#ProspectiveUserClientsNaturalId").removeAttr('required');
		$("body").find("#ProspectiveUserClientsLegalId").attr('required','required');
		$("body").find("#ProspectiveUserClientsNaturalId").val("");	
		setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");	
	}else{
		$("body").find(".clienteNaturalProspective").show();
		$("body").find("#ProspectiveUserClientsNaturalId").attr('required','required');
		$("body").find("#ProspectiveUserClientsLegalId").removeAttr('required');
		$("body").find(".clienteLegalProspective").hide();
		$("body").find("#ProspectiveUserClientsLegalId").val("");
		setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");	
	}
}).on('change', '#agregarFlujoModal #ProspectiveUserClientsNaturalId', function(event) {
	var empresa_id = $(this).val();

	if(empresa_id.indexOf("_LEGAL") != -1){
		empresa_id = empresa_id.replace("_LEGAL","");
		var servicio_id = 0;
		$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_select',{empresa_id:empresa_id,servicio_id:servicio_id},function(result){
			$('.selectContact').html(result);
	        $('#contac_id').select2({
		        dropdownParent: $("#agregarFlujoModal")
			});
	    });
	}else{
		$('.selectContact').html("");
	}

	
}).on( "click", "#cuerpoFlujoNuevo .icon_add_legal_contac_prospective", function() {
    $('#cityForm').show();
    $('label[for="cityForm"]').show();
    var empresa_id      = $('#ProspectiveUserClientsNaturalId').val();
    empresa_id = empresa_id.replace("_LEGAL","");
    $.post(copy_js.base_url+'ContacsUsers/add_contact_prospective',{empresa_id:empresa_id}, function(result){
    	$(".cuerpoFlujoAdd").hide();
    	$(".cuerpoContactoCliente").show();
        $(".cuerpoContactoCliente").html(result);

        var input 			= document.getElementById('CityContactProspective');
		var autocomplete 	= new google.maps.places.Autocomplete(input);
    });
}).on('click', '.returnContactProspective', function(event) {
	$(".cuerpoContactoCliente").html("");
	$(".cuerpoContactoCliente").hide();
	$(".cuerpoFlujoAdd").show();
	$("body").find('#agregarFlujoModal #ingresoCliente').html("");
}).on('submit', '#agregarFlujoModal #contactoProspectiveUserNew', function(event) {
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
}).on('click', '.addNewCustomerProspective ', function(event) {
	event.preventDefault();
	type = $(this).data("type");
	$(".cuerpoContactoCliente").html("");
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:type, flujo: 1}, function(result){
		$(".cuerpoContactoCliente").html(result);
		$(".cuerpoFlujoAdd").hide();
		$(".cuerpoContactoCliente").show();
	});
	$("body").find('#ProspectiveUserClientsNaturalId').val("");
    setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");
	$("body").find('#ProspectiveUserClientsLegalId').val("");
    setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");
	$("body").find('.selectContact').html("");
});

function list_contacs_bussines(nuevo_id){
    $.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines_option',{nuevo_id:nuevo_id}, function(result){
        $('#contac_id').append(result);
        $('#contac_id').val(nuevo_id);
         $('#contac_id').select2({
	        dropdownParent: $("#agregarFlujoModal")
		});
        $(".cuerpoContactoCliente").html("");
		$(".cuerpoContactoCliente").hide();
		$(".cuerpoFlujoAdd").show();
    });
}




$("body").on('click', '#agregarFlujoModal #btn_validate_exist_customer', function(event) {

	event.preventDefault();
	var tipoCliente 		= $("body").find("#agregarFlujoModal #CustomerType").val();
	var valueIdentification = $("body").find('#agregarFlujoModal #CustomerIdentification').val();
	var valueEmail 			= $("body").find('#agregarFlujoModal #CustomerEmail').val();
	var valueType           = $("body").find('#agregarFlujoModal #CustomerNacional').val();
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
		$.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail}, function(result){
			if(result != 'null'){
				message_alert("El cliente ya existe","error");
			}else{
				if(tipoCliente == "2"){
					$.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification, flujo: 1,nacional:valueType}, function(result){
						$("body").find("#agregarFlujoModal .clientsLegalsForm").hide();
						$("body").find('#agregarFlujoModal #ingresoCliente').html(result);
						$("body").find('#agregarFlujoModal #ingresoCliente').show();
						var input 			= document.getElementById('ContacsUserCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
						$("body").find('#agregarFlujoModal #documentoForm1').dropify(optionsDp); 
						$("body").find('#agregarFlujoModal #documentoForm2').dropify(optionsDp); 
					});
				}else{
					$.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail, flujo: 1,nacional:valueType}, function(result){
						$("body").find("#agregarFlujoModal .clientsLegalsForm").hide();
						$("body").find('#agregarFlujoModal #ingresoCliente').html(result);
						$("body").find('#agregarFlujoModal #ingresoCliente').show();
						$("body").find('#agregarFlujoModal #documentoForm1').dropify(optionsDp); 
						$("body").find('#agregarFlujoModal #documentoForm2').dropify(optionsDp); 
						var input 			= document.getElementById('ClientsNaturalCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
					});
				}
			}
		});
	}

});

$("body").on('click', '#agregarFlujoModal .regresarFormClient', function(event) {
	event.preventDefault();
	$("body").find('#agregarFlujoModal #ingresoCliente').html("");
	$(".clientsLegalsForm").show();
});


$("body").on('submit', '#formFlujoCrearCustomerLegal', function(event) {
	event.preventDefault();

	$(".body-loading").show();
	var formData            = new FormData($('#formFlujoCrearCustomerLegal')[0]);
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

}).on('submit', '#formFlujoCustomerNatural', function(event) {
	event.preventDefault();
	
	var formData = new FormData($('#formFlujoCustomerNatural')[0]);
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
			list_clients_natural(result.id)
            $(".body-loading").hide();
        }
    });
});


function list_clients_natural(nuevo_id){
    $.post(copy_js.base_url+'ClientsNaturals/list_clients_natural_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#ProspectiveUserClientsNaturalId').append(result);
    	$("body").find('#ProspectiveUserClientsNaturalId').val(nuevo_id+"_NATURAL");
        setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");
		$(".cuerpoContactoCliente, #ingresoCliente").html("");
		$(".cuerpoContactoCliente, #ingresoCliente").hide();
		$(".cuerpoFlujoAdd").show();
    });
}
function list_clients_legal(nuevo_id){
    $.post(copy_js.base_url+'ClientsLegals/list_clients_legal_option',{nuevo_id:nuevo_id,modal: 1}, function(result){
        $("body").find('#ProspectiveUserClientsNaturalId').append(result);
    	$("body").find('#ProspectiveUserClientsNaturalId').val(nuevo_id+"_LEGAL");
        setCustomerSelect2("#ProspectiveUserClientsNaturalId","#agregarFlujoModal");
		$("body").find('#agregarFlujoModal #ProspectiveUserClientsNaturalId').trigger('change');
		$(".cuerpoContactoCliente, #ingresoCliente").html("");
		$(".cuerpoContactoCliente, #ingresoCliente").hide();
		$(".cuerpoFlujoAdd").show();

    });
}


$("body").on('submit', '#formGeneralCustomer', function(event) {
	event.preventDefault();
	var formData            = new FormData($('#formGeneralCustomer')[0]);
	$(".body-loading").show();
	$.ajax({
        type: 'POST',
        url:  copy_js.base_url+'ProspectiveUsers/add_flujo_post',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            console.log(result)
			location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
        }
    });
	 
	// $.post(copy_js.base_url+'ProspectiveUsers/add_flujo_post',$(this).serialize(), function(result){
	// 	console.log(result)
	// 	location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
	// });
});


$("body").on('paste', '#ProspectiveUserImagePaste', function(event) {
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
            $("body").find('#ProspectiveUserImagePaste').val(urlImage);
            $("body").find('#ProspectiveUserImagePaste').hide();
            $("body").find('#previewData').attr('src',urlImage);
            $("body").find(".imagenPreview").show();
          };
          reader.readAsDataURL(blob);
        }        
      }
    }
});

$("body").on('click', '.deleteImg', function(event) {
	event.preventDefault();
	$("body").find('#ProspectiveUserImagePaste').val('');
	$("body").find('#previewData').attr('src',null);
	$("body").find('#ProspectiveUserImagePaste').show();
	$("body").find(".imagenPreview").hide();
});