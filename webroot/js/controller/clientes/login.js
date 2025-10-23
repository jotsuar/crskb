$("#UserLoginForm").submit(function(event) {
	event.preventDefault();
});

$("#envioCodigo").click(function(event) {
	event.preventDefault();

	var email = $("#UserEmail").val();

	if (email == "") {
		message_alert("El correo es requerido","error")
	}else{
	    $("#UserCode").val("");
	    $(".body-loading").show();
		$.post(copy_js.base_url+copy_js.controller+'/valid_existencia',{email}, function(result){
		  $(".body-loading").hide();
	      if (result == 1) {
	        message_alert("Se ha enviado un código de acceso al correo electrónico registrado.","Bien");
	        $("#envioCodigo").hide();
	        $(".codeEmail").show();
	        $("#validarCodigo").show();
	        $("#validarCodigo").addClass('d-block');
	      } else {
	        message_alert("Lo sentimos el correo electrónico no existe en nuestra base de datos.","error");
	        $("#envioCodigo").show();
	        $(".codeEmail").hide();
	        $("#validarCodigo").hide();
	        $("#validarCodigo").removeClass('d-block');
	      }
	    });
	}

});

$("#UserLoginForm").parsley();


$("#validarCodigo").click(function(event) {
	event.preventDefault();
	var email = $("#UserEmail").val();
	var code  = $("#UserCode").val();

	if (email == "" || code == "") {
		message_alert("El correo y el código son requeridos","error");
	}else{
		$.post(copy_js.base_url+copy_js.controller+'/valid_code',{email,code}, function(result){
			if (result == 2) {
				message_alert("Lo sentimos el tiempo límite de ingreso fue superado, se ha enviado un nuevo código.","error");
			}else if(result == 4){
				message_alert("Lo sentimos el correo electrónico no existe en nuestra base de datos.","error");
			}else if(result == 0){
				message_alert("Lo sentimos el código no coincide.","error");
			}else{
				location.href = copy_js.base_url+copy_js.controller;
			}
		});
	}

});

$(".contactoNormal").click(function(event) {
	event.preventDefault();

	var cliente = clienteData;
	var cliente_type = clienteType;
	var flujo = $(this).data("flujo");
	var order = $(this).data("order");

	swal({
        title: "¿Estas seguro de solicitar contacto?",
        text: "¿Un especialista KEBCO se contactará con usted muy pronto, está de acuerdo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, cancelar",
        confirmButtonText: "Si, proceder",
        closeOnConfirm: true
    },
    function(){
        $(".body-loading").show();
        $.post(copy_js.base_url+'ProspectiveUsers/flujo_cliente', {
            cliente, cliente_type, flujo,order
        }, function(data, textStatus, xhr) {
            $(".body-loading").hide(); 

            var response = JSON.parse(data);

            if (response.status) {
            	message_alert("Muy pronto lo contactarán.","Bien");
            }else{
            	message_alert("Hubo un error.","error");
            }

        });
    });

});

$("body").on('click', '#sendCommentPago', function(event) {
	event.preventDefault();
	$("#formAprovee").parsley();
	$("#modalAprove").modal("show");
	$("#archivoOrden").dropify({
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

});	

$("body").on('submit', '#formAprovee', function(event) {
	event.preventDefault();
	$(".body-loading").show();
	var formData            = new FormData($('#formAprovee')[0]);
    $.ajax({
        type: 'POST',
        url:  copy_js.base_url+'clientes/sendMessagePayment',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            $(".body-loading").hide();
            location.reload();
        },error: function(){
        	$(".body-loading").hide();
        	message_alert("Hubo un error en el sistema.","error");
        }
    });
});