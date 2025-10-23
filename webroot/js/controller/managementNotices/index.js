$("#btn_registrar").click(function() {
	$.post(copy_js.base_url+copy_js.controller+'/add_new',{}, function(result){
		$('#modal_small_body').html(result);
		$('#modal_small_label').text('Registrar aviso gerencial');
		$('#modal_small').modal('show');
	});
});

$('#btn_guardar_modal_cliente').click(function() {
    var formData            = new FormData($('#formuploadajax')[0]);
	var title 				= $('#ManagementNoticeTitle').val();
	var description 		= $('#ManagementNoticeDescription').val();
	var fecha_ini 			= $('#ManagementNoticeFechaIni').val();
	var fecha_fin 			= $('#ManagementNoticeFechaFin').val();
	if (title != '' && description != '') {
		var fechaActual     = dateDay();
		if (new Date(fecha_ini).getTime() > new Date(fechaActual).getTime()) {
		    message_alert("Por favor valida, La fecha de inicio es mayor a la actual","error");
		} else {
		    if (new Date(fecha_fin).getTime() > new Date(fecha_ini).getTime()) {
		        message_alert("Por favor valida, La fecha fin es mayor a la fecha inicial","error");
		    } else {
				$('#btn_guardar_modal_cliente').hide();
				$.ajax({
		            type: 'POST',
		            url: copy_js.base_url+copy_js.controller+'/add',
		            data: formData,
		            contentType: false,
		            cache: false,
		            processData:false,
		            success: function(result){
		                location.href = copy_js.base_url+copy_js.controller;
		            }
		        });
			}
		}
	} else {
		message_alert("Los campos son requeridos","error");
	}
});

$('.btnSendReminder').click(function() {
	var date 			= $(this).data('date');
	var state 			= $(this).data('state');
	$.post(copy_js.base_url+copy_js.controller+'/activeNotice',{date:date,state:state}, function(result){
		if (result == true) {
			message_alert("Se envió la recordacción satisfactoriamente","Bien");
		} else {
			message_alert("La fecha de inicio debe de ser menor o igual a la fecha del día de hoy, y el estado debe ser activo","error");
		}
	});
});

$('body').on("keyup", "#ManagementNoticePrice", function(event) {
 	var precio              = $('#ManagementNoticePrice').val();
	var precio_final        = number_format(precio);
	$('#ManagementNoticePrice').val(precio_final);
});

$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
        buscadorFiltro();
    }
});


$(".btn_buscar").click(function() {
	buscadorFiltro();
});

function buscadorFiltro(){
	var texto 							= $('#txt_buscador').val();
	var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	var hrefFinal 					= hrefURL+"?q="+texto;
	location.href 					= hrefFinal;
	
}

$("#texto_busqueda").click(function() {
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});