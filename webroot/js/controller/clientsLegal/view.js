$(document).ready(function() {
	$('#btn_actualizar').hide();
	listarContactosJuridico();
	$('#btn_crear').hide();

	if ($("#ClientsLegalOtherId").length) {
		$("#ClientsLegalOtherId").select2();


		

	}

	$.get(rootUrl+copy_js.controller+'/getFlowsClient/'+IDS_CUSTOMER, function(data) {
		$("#requerimientos").html(data)
	});

	$.get(rootUrl+copy_js.controller+'/getFlowsQuotations/'+IDS_CUSTOMER, function(data) {
		$("#cotizacioness").html(data)
	});

	$.get(rootUrl+copy_js.controller+'/getTechnicals/'+IDS_CUSTOMER, function(data) {
		$("#serviciosTecnicos").html(data)
	});

	$.get(rootUrl+copy_js.controller+'/getSells/'+IDS_CUSTOMER, function(data) {
		$("#ventas").html(data)
	});
});



$("body").on( "click", "#btn_find_existencia_contacto_view", function() {
	var email 			= $('#email').val();
	if (email != '') {
		$.post(copy_js.base_url+'ContacsUsers'+'/validExistencia',{email:email}, function(result){
			if (result == 0) {
	            message_alert("El correo electrónico  esta disponible","Bien");
	        } else {
	            message_alert("El correo electrónico  ya esta registrado","error");
	        }
		});
	} else {
		message_alert("Por favor ingresa el correo electrónico de la empresa","error");
	}
});

$("#btn_guardar").click(function() {
	var name		= $("#name").val();
	var telephone	= $("#telephone").val();
	var cell_phone	= $("#cell_phone").val();
	var city		= $("#city").val();
	var email		= $("#email").val();
	var empresa_id	= $("#empresa_id").val();
	var concession_id 		= $('#ContacsUserConcessionId').val();
	if (name != '' && city != '' && email != '') {
		$.post(copy_js.base_url+'ContacsUsers/add',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,empresa_id:empresa_id,concession_id:concession_id},
		function(result){
			if (result == 0) {
				$('.btn_guardar_form').show();
                message_alert("Por favor valida, el email ingresado ya se encuentra registrado","error");
			} else {
				nullCampos();
	       		listarContactosJuridico();
			}
    	});
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});

$("body").on( "click", "#btn_crear", function() {
	enabledCampos();
	$('#title_action').text('Añadir contacto');
	$('#btn_actualizar').hide();
	$('#btn_crear').hide();
	$('#btn_guardar').show();
	$("#name").val('');
	$("#telephone").val('');
	$("#cell_phone").val('');
	$("#city").val('');
	$("#email").val('');
	$("#id_contact").val('');
});

$("body").on( "click", "#btn_editar", function() {
	var contact_id = $(this).data('uid');
	$.post(copy_js.base_url+'ContacsUsers/getData',{contact_id:contact_id},function(result){
		var obj = JSON.parse(result);
		enabledCampos();
		$('#title_action').text('Editar contacto');
		$('#btn_actualizar').show();
		$('#btn_crear').show();
		$('#btn_guardar').hide();
		$("#name").val(obj.name);
		$("#telephone").val(obj.telephone);
		$("#cell_phone").val(obj.cell_phone);
		$("#city").val(obj.city);
		$("#email").val(obj.email);
		$("#id_contact").val(obj.id);
	});
});
$("#btn_actualizar").click(function() {
	var name		= $("#name").val();
	var telephone	= $("#telephone").val();
	var cell_phone	= $("#cell_phone").val();
	var city		= $("#city").val();
	var email		= $("#email").val();
	var id_contact	= $("#id_contact").val();
	if (name != '' && city != '' && email != '') {
		$.post(copy_js.base_url+'ContacsUsers/edit',{name:name,telephone:telephone,cell_phone:cell_phone,city:city,email:email,id_contact:id_contact},
		function(result){
			nullCampos();
			$("#id_contact").val('');
       		listarContactosJuridico();
    	});
	} else {
		message_alert("Todos los campos son requeridos","error");
	}
});


$("body").on( "click", "#btn_vista", function() {
	var contact_id = $(this).data('uid');
	$.post(copy_js.base_url+'ContacsUsers/getData',{contact_id:contact_id},function(result){
		var obj = JSON.parse(result);
		desabledCampos();
		$('#title_action').text('Ver contacto');
		$('#btn_actualizar').hide();
		$('#btn_guardar').hide();
		$('#btn_crear').show();
		$("#name").val(obj.name);
		$("#telephone").val(obj.telephone);
		$("#cell_phone").val(obj.cell_phone);
		$("#city").val(obj.city);
		$("#email").val(obj.email);
	});
});

function nullCampos(){
	$("#name").val('');
	$("#telephone").val('');
	$("#cell_phone").val('');
	$("#city").val('');
	$("#email").val('');
}

function desabledCampos(){
	$("#name").prop( "disabled", true );
	$("#telephone").prop( "disabled", true );
	$("#cell_phone").prop( "disabled", true );
	$("#city").prop( "disabled", true );
	$("#email").prop( "disabled", true );
}

function enabledCampos(){
	$("#name").prop( "disabled", false );
	$("#telephone").prop( "disabled", false );
	$("#cell_phone").prop( "disabled", false );
	$("#city").prop( "disabled", false );
	$("#email").prop( "disabled", false );
}

$("body").on('click', '.btn_changeState', function(event) {
	event.preventDefault();
	var id = $(this).data("uid");
	var url = copy_js.base_url+'ContacsUsers/change_state' 
	swal({
        title: "Estas seguro de esta cambio?",
        text: "Deseas cambiar este contacto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No, cancelar",
        confirmButtonText: "Si, continuar",
        closeOnConfirm: false
    },
    function(){
        $.post(url, {id: id}, function(data, textStatus, xhr) {
            console.log(data)
            location.reload();
        });
    });});

function listarContactosJuridico(){
	var empresa_id	= $("#empresa_id").val();
	$.post(copy_js.base_url+'ContacsUsers/list_contacts_bussines',{empresa_id:empresa_id},function(result){
		$('.listarContactos').html(result);
    });
}

function initialize() {
	var input = document.getElementById('city');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);


$("body").on('click', '.verifyWo', function(event) {
    event.preventDefault();
    var nit = $(this).data("nit");
    $("#nitCliente").val(nit);
    $("#bodyClienteWo").html("");
    $("#modalClienteWo").modal("show");
});

$("body").on('click', '.btnSearchCustomer', function(event) {
    event.preventDefault();
    var dni = $("#nitCliente").val();
    if (dni == "") {
        message_alert("No es posible procesar la información, el Nit es requerido.","error");
    }else{
        $.post(copy_js.base_url+'ProspectiveUsers'+'/verify_wo', {dni, flujo: 0, flow: 0, user: 0}, function(data, textStatus, xhr) {
            $("#bodyClienteWo").html(data);
        });
    }
});