$("#btn_password").click(function() {
	$('#cambiarContrasenaModal').modal('show');
});

$("#btn_cambiar").click(function() {
	var actual = $('#actual').val();
	var nueva = $('#nueva').val();
	var r_nueva = $('#r_nueva').val();

	if (actual != '' && nueva != '' && r_nueva != '') {
		if (nueva == r_nueva) {
			$.post(copy_js.base_url+copy_js.controller+'/changePasswordUser',{actual:actual,nueva:nueva,r_nueva:r_nueva}, function(result){
				if (result == '2') {
					$('#validacion_texto').text("La contraseña es diferente a la guardada en base de datos");
				} else if(result == '1'){
					$('#cambiarContrasenaModal').modal('hide');
					message_alert("Se ha actualizado tu contraseña satisfactoriamente","Bien");
				} else {
					$('#validacion_texto').text("No hemos podido guardar tu contraseña, por favor inténtalo mas tarde");
				}
	    	});
		} else {
			$('#validacion_texto').text("Las contraseñas ingresadas no coinciden");
		}
	}
});

function initialize() {
	var input = document.getElementById('UserCity');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);


$("body").on('click', '#testConfiguration', function(event) {
	event.preventDefault();
	var email 	= $("#UserEmail").val();
	var password = $("#UserPasswordEmail").val();
	$(".body-loading").show();
	if(password == "" || password == null){
		message_alert("Se debe ingresar una contraseña.","error");
	}else{
		$.post(copy_js.base_url+copy_js.controller+'/test_email',{email,password}, function(result){
			$(".body-loading").hide();
			if(result == "0"){
				message_alert("Por favor verifica la contraseña y/o la configuración de tu cuenta","error");
			}else{
				$("#UserProfileForm").submit();
			}
    	});
	}
});

$(".dropify").dropify(optionsDropify);