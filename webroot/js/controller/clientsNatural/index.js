$(document).ready(function() {
	$('#cityForm').hide();
	if ($("#ClientsNaturalOtherId").length) {
		$("#ClientsNaturalOtherId").select2();
	}
});

$("#btn_registrar").click(function() {
	$('#cityForm').show();
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:1}, function(result){
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#validacionCliente').html(result);
		$("#ingresoCliente").html("");
		$('#modalNewCustomer').modal('show');
	});
});

$("body").on( "click", "#btn_find_existencia", function() {
	var email				= $('#ClientsNaturalEmail').val();
	if (email != '') {
		$.post(copy_js.base_url+copy_js.controller+'/validExistencia',{email:email},function(result){
			if (result == 0) {
	    		message_alert("El correo electrónico esta disponible","Bien");
	    	} else {
	        	message_alert("El correo electrónico ya esta registrado","error");
	    	}
		});
	} else {
	    message_alert("Por favor ingresa el correo electrónico","error");
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

$('.btn_editar').click(function(){
	$('#cityForm').hide();
	var cliente_id = $(this).data('uid');
	$.post(copy_js.base_url+copy_js.controller+'/edit',{cliente_id:cliente_id}, function(result){
		$('#modal_form_body').html(result);
		$('#modal_form_label').text('Editar cliente');
		$('#modal_form').modal('show');
		$("#documentoForm1").dropify(optionsDp); 
		$("#documentoForm2").dropify(optionsDp); 
		$("#ClientsNaturalEditForm").parsley();
	});
});
$("body").on( "click", ".btn_editar_ciudad", function() {
	$('#cityForm').show();
});

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

$("body").on( "click", ".btn_guardar_form", function() {
	var cliente_id			= $('#ClientsNaturalId').val();
	var name 				= $('#ClientsNaturalName').val();
	var telephone 			= $('#ClientsNaturalTelephone').val();
	var cell_phone			= $('#ClientsNaturalCellPhone').val();
	var email				= $('#ClientsNaturalEmail').val();
	var identification		= $('#ClientsNaturalIdentification').val();
	var city 				= $('#cityForm').val();
	var view 				= $('#ClientsNaturalAction').val();
	
	console.log(city);

	if (city == '' && $('#ClientsNaturalCity').length) {
		city 		= $('#ClientsNaturalCity').val();
	}
	console.log(city);

	if (name != '' && city != '' && email != '' && city != '') {
		if(!validateEmail(email)){
			message_alert("El correo eléctronico ingresado no es válido.","error");
			return false;
		}
		$('.btn_guardar_form').hide();

		$(".body-loading").show();
		var formData            = new FormData($('#ClientsNaturalEditForm')[0]);
		$.ajax({
	        type: 'POST',
	        url:  copy_js.base_url+copy_js.controller+'/addSave',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	            if (result == 0) {
					$('.btn_guardar_form').show();
	                    message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
				} else {
					location.href = copy_js.base_url+copy_js.controller+'/view/'+(result);
				}
	        }
	    });

	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});

$("body").on( "click", "#btn_agregar", function() {
	var contact_id = $(this).data('uid');
	$("#contact").val(contact_id);
	$('#agregarTareaModal').modal('show');
});
$("body").on( "change", "#form_new_requerimiento input[type='checkbox']", function() {
	if( $('#flujo_no_valido').prop('checked') ) {
	    $('#user_asignado_div').hide();
	} else {
	    $('#user_asignado_div').show();
	}
});

$("#btn_registrar_flujo").click(function() {
	var contact 			= $("#contact").val();
	if( $('#flujo_no_valido').prop('checked') ) {
	    var flujo_no_seleccionado = 1;
	} else {
	    var flujo_no_seleccionado = 0;
	}
	var origin 				= $("#origin").val();
	var reason 				= $("#reason").val();
	var description 		= $("#description").val();
	var user_id 			= $("#user_id").val();
	if (reason != '' && description != '') {
		$("#btn_registrar_flujo").hide();
		$.post(copy_js.base_url+'ProspectiveUsers/addNaturalProspective',{flujo_no_seleccionado:flujo_no_seleccionado,contact:contact,origin:origin,reason:reason,description:description,user_id:user_id},function(result){
			if (result != false) {
				location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
			} else {
				location.href = copy_js.base_url+'ProspectiveUsers?filterEtapa=9';
			}
		});
	}
});

function initialize() {
	var input = document.getElementById('cityForm');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);

$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
        if( $("#txt_buscador").val().length >= 3 || isRole == 1){
			buscadorFiltro();
		}else {
			message_alert("Se deben escribir mínimo 3 carácteres.","error");
		}
    }
});


$(".btn_buscar").click(function() {
	if( $("#txt_buscador").val().length >= 3 || isRole == 1){
		buscadorFiltro();
	}else {
		message_alert("Se deben escribir mínimo 3 carácteres.","error");
	}
});

function buscadorFiltro(){
	var texto 						= $('#txt_buscador').val();
	var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	var hrefFinal 					= hrefURL+"?q="+texto;
	location.href 					= hrefFinal;
	
}

$("#texto_busqueda").click(function() {
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});