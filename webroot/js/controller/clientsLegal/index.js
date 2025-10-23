$(".btn_editar").click(function() {
	var empresa_id = $(this).data('uid');
	$.post(copy_js.base_url+copy_js.controller+'/edit',{empresa_id:empresa_id}, function(result){
		$('#modal_small_body').html(result);
		$('#modal_small_label').text('Editar cliente juridico');
		$('#modal_small').modal('show');

		$("#documentoForm1").dropify(optionsDp); 
		$("#documentoForm2").dropify(optionsDp); 
		$("#ClientsLegalEditForm").parsley();
		$('#ClientsLegalParentId').select2({
	    	'dropdownParent' : $('#modal_small')
	    })
	});
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

$("#btn_guardar_modal_cliente").click(function() {
	var name 		= $('#ClientsLegalName').val();
	var nit 		= $('#ClientsLegalNit').val();
	var client_id	= $('#ClientsLegalClientId').val();
	if (name != '' && nit != '') {
		$('.btn_guardar_modal_cliente').hide();

		$(".body-loading").show();
		var formData            = new FormData($('#ClientsLegalEditForm')[0]);
		$.ajax({
	        type: 'POST',
	        url:  copy_js.base_url+copy_js.controller+'/saveEdit',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	if (result == 0) {
					$('.btn_guardar_modal_cliente').show();
	                message_alert("Por favor valida, el NIT ingresado ya se encuentra registrado","error");
				} else {
					if (copy_js.action == 'index') {
						location.href =copy_js.base_url+copy_js.controller+'/view/'+result;
					} else {
						location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action+'/'+client_id;
					}
					location.reload();
				}
	        }
	    });
	} else {
		message_alert("Los campos son requeridos","error");
	}
});

$("body").on( "click", "#btn_find_existencia_juridico", function() {
	var nit 		= $('#ClientsLegalNit').val();
	if (nit != '') {
		$.post(copy_js.base_url+'ClientsLegals/validExistencia',{nit:nit}, function(result){
			if (result == 0) {
	            message_alert("El NIT esta disponible","Bien");
	        } else {
	            message_alert("El NIT ya esta registrado","error");
	        }
		});
	} else {
        message_alert("Por favor ingresa el NIT de la empresa","error");
	}
});

$("#btn_registrar").click(function() {
	$.post(copy_js.base_url+'ClientsLegals/add_customer_general',{type:2}, function(result){
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#validacionCliente').html(result);
		$("#ingresoCliente").html("");
		$('#modalNewCustomer').modal('show');
	});
});

$(".agregar_contacto").click(function() {
	var empresa_id 		= $(this).data('uid');
	$.post(copy_js.base_url+'ContacsUsers/add_user_form',{empresa_id:empresa_id}, function(result){
		$('#modal_form_body').html(result);
		$('#modal_form_label').text('Registrar contacto');
		$('#modal_form').modal('show');
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

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

$("body").on( "click", ".btn_guardar_form", function() {
	var empresa_id 		= $('#ContacsUserEmpresaId').val();
	var name 			= $('#ContacsUserName').val();
	var telephone 		= $('#ContacsUserTelephone').val();
	var cell_phone 		= $('#ContacsUserCellPhone').val();
	var email 			= $('#ContacsUserEmail').val();
	var concession_id 	= $('#ContacsUserConcessionId').val();
	var cityForm 		= $('#cityForm').val();

	if (name != '' && email != '' && cityForm != '') {

		if(!validateEmail(email)){
			message_alert("El correo eléctronico ingresado no es válido.","error");
			return false;
		}
		$('.btn_guardar_form').hide();
		$.post(copy_js.base_url+'ContacsUsers/addUserClientsLegal',{empresa_id:empresa_id,name:name,telephone:telephone,cell_phone:cell_phone,email:email,cityForm:cityForm,concession_id:concession_id}, function(result){
			if (result == 0) {
				$('.btn_guardar_form').show();
                message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
			} else {
				location.href = copy_js.base_url+copy_js.controller+'/view/'+(result);
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
		$.post(copy_js.base_url+'ProspectiveUsers/addLegalProspective',{flujo_no_seleccionado:flujo_no_seleccionado,contact:contact,origin:origin,reason:reason,description:description,user_id:user_id},function(result){
			if (result != false) {
				location.href = copy_js.base_url+'ProspectiveUsers/index?q='+result;
			} else {
				location.href = copy_js.base_url+'ProspectiveUsers?filterEtapa=9';
			}
		});
	}
});

$("body").on( "click", ".btn_editar_contacto", function() {
	$('#cityForm2').hide();
	var contact_id 			= $(this).data('uid');
	var flujo_id 			= $(this).data('flujo');
	$.post(copy_js.base_url+'ContacsUsers/edit_user_form',{contact_id:contact_id,flujo_id:flujo_id}, function(result){
       $('#modal_form_body_edit_contacto').html(result);
		$('#modal_form_label_edit_contacto').text('Editar contacto');
		$('#modal_form_edit_contacto').modal('show');
    });
});
$("body").on( "click", ".btn_editar_ciudad_1", function() {
	$('#cityForm2').show();
});
$("body").on( "click", ".btn_guardar_form_edit_contacto", function() {
	var flujo_id			= $('#ContacsUserFlujoId').val();
	var contac_id			= $('#ContacsUserIdContact').val();
	var name 				= $('#ContacsUserName').val();
	var telephone 			= $('#ContacsUserTelephone').val();
	var cell_phone			= $('#ContacsUserCellPhone').val();
	var email				= $('#ContacsUserEmail').val();
	var city 				= $('#cityForm2').val();
	var concession_id 		= $('#ContacsUserConcessionId').val();

	if (city == '') {
		city 				= $('#ContacsUserCity').val();
	}
	if (name != '' && city != '' && email != '') {
		if(!validateEmail(email)){
			message_alert("El correo eléctronico ingresado no es válido.","error");
			return false;
		}
		$('.btn_guardar_form').hide();
		$.post(copy_js.base_url+'ContacsUsers/editSave',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,contac_id:contac_id,concession_id:concession_id},function(result){
			location.href = copy_js.base_url+copy_js.controller+'/view/'+result;
		});
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});

function initialize() {
	var input = document.getElementById('cityForm');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);

function initialize2() {
	var input = document.getElementById('cityForm2');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize2);

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

$(".btn_buscar2").click(function() {
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