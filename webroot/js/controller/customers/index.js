$("body").on('change', '#CustomerType', function(event) {
	var tipoCliente = $(this).val();

	if(tipoCliente == "1"){
		$(".divCustomerEmail").show();
	}else{
		$(".divCustomerEmail").hide();	
		$("#CustomerEmail").val("");	
	}

});

function ValidateEmail(inputText)
{
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if(inputText.match(mailformat))
	{
		return true;
	}else
	{
		return false;
	}
}

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





$("body").on('click', '#btn_find_existencia_customr', function(event) {

	event.preventDefault();
	var tipoCliente 		= $("body").find("#CustomerType").val();
	var valueIdentification = $("body").find('#CustomerIdentification').val();
	var valueEmail 			= $("body").find('#CustomerEmail').val();
	var valueType 			= $("body").find('#CustomerNacional').val();
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
		$.post(copy_js.base_url+'ClientsLegals/validateCustomer',{type: tipoCliente, identification: valueIdentification, email: valueEmail}, function(result){
			if(result != 'null'){
				swal({
			        title: "¿Cliente ya existe?",
			        text: "¿El cliente ya existe deseas ir visualizar su información?",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonClass: "btn-danger",
			        cancelButtonText:"Cancelar",
			        confirmButtonText: "Aceptar",
			        closeOnConfirm: false
			    },
			    function(){			    		
			    	$response = JSON.parse(result);
			    	if($response.type == "legal"){
			    		location.href = copy_js.base_url+'ClientsLegals/view/'+$response.idEncrypt
			    	}else{
			    		location.href = copy_js.base_url+'ClientsNaturals/view/'+$response.idEncrypt
			    	}

			   });
			}else{
				if(tipoCliente == "2"){
					$.post(copy_js.base_url+'ClientsLegals/add_customer_legal',{ nit: valueIdentification, nacional: valueType}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoCliente').html(result);
						var input 			= document.getElementById('ContacsUserCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
						$("#documentoForm1").dropify(optionsDp); 
						$("#documentoForm2").dropify(optionsDp);

					    $('#ClientsLegalParentId').select2({
					    	'dropdownParent' : $('#modalNewCustomer')
					    })

					});
				}else{
					$.post(copy_js.base_url+'ClientsNaturals/add_customer',{ identification: valueIdentification, email: valueEmail, nacional: valueType}, function(result){
						$("body").find(".clientsLegalsForm").hide();
						$("body").find('#ingresoCliente').html(result);
						var input 			= document.getElementById('ClientsNaturalCity');
						var autocomplete 	= new google.maps.places.Autocomplete(input);
						$("#documentoForm1").dropify(optionsDp); 
						$("#documentoForm2").dropify(optionsDp); 
						$("#formCustomerNatural").parsley();
					});
				}
			}
		});
	}

});


$("body").on('click', '.regresarFormClient', function(event) {
	event.preventDefault();
	$('#ingresoCliente').html("");
	$(".clientsLegalsForm").show();
});


$("body").on('keypress', '#CustomerIdentification', function(event) {

	setTimeout(function() {
		var valueId = $("body").find("#CustomerIdentification").val();
		if ($("body").find("#CustomerType").val() == "1") {
			$("body").find("#CustomerIdentification").val(string_valdate_Number(valueId));
		}else{
			$("body").find("#CustomerIdentification").val(string_valdate_Number_nit(valueId));
		}
	}, 200);
	
}).on('change', '#CustomerIdentification', function(event) {
	setTimeout(function() {
		var valueId = $("body").find("#CustomerIdentification").val();
		$("body").find("#CustomerIdentification").val(string_valdate_Number_nit(valueId));
	}, 200);
});

$("body").on('submit', '#formGeneralCustomer', function(event) {
	event.preventDefault();
	/* Act on the event */
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
			location.href = copy_js.base_url+'ClientsLegals/view/'+result.uid
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
			location.href = copy_js.base_url+'ClientsNaturals/view/'+result.uid
        }
    });
});