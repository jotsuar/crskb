$("body").on('click', '#validarInput', function(event) {
	event.preventDefault();

	$("#especialCliente,.otros-campos").hide();
	continueSearch 		= true;
	var emailCliente 	= $("#emailCliente").val(); 
	var phoneCustomer 	= $("#phoneCustomer").val();

	if(emailCliente == "" && phoneCustomer == ""){
		message_alert("Se debe ingresar uno de los dos campos para proceder a la validación.","error");
		continueSearch = false;
	}else{
		if(phoneCustomer.length > 0 && phoneCustomer.length < 10 ){
			message_alert("El teléfono debe tener  mínimo 10 digitos","error");
			continueSearch = false;
		} else if(emailCliente.length > 0){
			if(!ValidateEmail(emailCliente)){
				message_alert("El correo ingresado no es válido","error");
				continueSearch = false;
			}
		}
	}
	if(continueSearch){
		$.post(copy_js.base_url+'ProspectiveUsers/validate_customer_especial',{ 
			emailCliente,phoneCustomer
		}, function(result){
			if(typeof result.type != "undefined"){
				$("#especialCliente").show();
				if(result.type == "natural"){
					$(".otros-campos").show();
					$(".clienteNaturalProspective").show();
					$(".clienteLegalProspective,#camposValidacion").hide();
					$("#flujoClienteNombre").val(result.name)
					$("#flujoEspecialClienteNatural").val(result.id)
				}else {
					$(".otros-campos").show();
					$(".clienteNaturalProspective,#camposValidacion").hide();
					$(".clienteLegalProspective").show();
					$("#flujoClienteLegalNombre").val(result.name);
					$("#flujoClienteLegalContactoNombre").val(result.name_contacto);
					$("#flujoEspecialClienteJuridico").val(result.id);
				}
			}else{
				$(".otros-campos").show();
				$('#validarInput').hide();
			}
		},"json");
	}
});

$("#phoneCustomer").on('keyup', function(event) {
	$(this).val(string_valdate_Number($(this).val()))
});

$("#ProspectiveUserImage").dropify({
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