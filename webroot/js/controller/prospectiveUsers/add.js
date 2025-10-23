$("#btn_guardar").click(function() {
	var name		= $("#name").val();
	var nit			= $("#nit").val();
	if (name != '') {
		$.post(copy_js.base_url+'ClientsLegals/add',{name:name,nit:nit}, function(result){
			swal({
				title: "Cliente creado",
				text: "No olvides registrar el contacto",
				type: "info",
				showCancelButton: false,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Aceptar",
				closeOnConfirm: false
			},
			function(){
				$("#name").val('');
				$("#nit").val('');
				location.href =copy_js.base_url+'clientslegals/view/'+result;
			});
    	});
	} else {
		message_alert("El nombre es requerido","error");
	}
});

function initialize() {
	var input = document.getElementById('ProspectiveUserCity');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);