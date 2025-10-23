
$("body").on('click', '.btnAddressClient', function(event) {
	event.preventDefault();
	var id 		= $(this).data("id");
	var client 	= $(this).data("client");
	var type 	= $(this).data("type");

	var dataSend = {id,client,type };

	var url 	= copy_js.base_url+'adresses/admin';

	$.post(url, dataSend, function(data, textStatus, xhr) {
		$("#cuerpoAdrress").html(data);
		$("#modalAdrressInformation").modal("show");
		var input 			= document.getElementById('AdressCity');
		var autocomplete 	= new google.maps.places.Autocomplete(input);

	});
	$('#despachoDeProductos,#modalNewFactura').removeClass('show');
	$('#modalAdrressInformation').modal({backdrop: 'static', keyboard: false})
});

$("body").on('click', '.btn-secondary', function(event) {
	$('#despachoDeProductos,#modalNewFactura').addClass('show');
});
$("body").on('click', '.close', function(event) {
	$('#despachoDeProductos,#modalNewFactura').addClass('show');
});

$("body").on('submit', '#formAdminAddress', function(event) {
	$('#despachoDeProductos').addClass('show');
	event.preventDefault();
	var datosForm = $(this).serialize();
	var url 	= copy_js.base_url+'adresses/store';


	$.post(url, datosForm, function(data, textStatus, xhr) {

		if( $("body").find("#btn_informacion_despacho").length ){
			$("#modalAdrressInformation").modal("hide");
			if(typeof elementButton != "undefined"){
				elementButton.click();
			}else{
				if ($("#ShippingAddForm").length ) {
					location.reload();
				}
				$("body").find("#btn_informacion_despacho").click();
			}
		}else{
			location.reload();
		}

	});
	console.log(datosForm);
});