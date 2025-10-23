$(document).ready(function() {
	$('#cityForm').hide();
	$('label[for="cityForm"]').hide();
});

$(".btn_editar").click(function() {
	var user_id = $(this).data('uid');
	$.post(copy_js.base_url+copy_js.controller+'/edit',{user_id:user_id}, function(result){
		$('#modalBodyEdit').html(result);
		$('#modal_form_editUser').modal('show');
	});
});

$("body").on('submit', '#newEditForm', function(event) {
	event.preventDefault();
	var formData            	= new FormData($('#newEditForm')[0]);
	$.ajax({
        type: 'POST',
        url: copy_js.base_url+copy_js.controller+'/editSave',
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
        	if(result == "0"){
        		message_alert("El archivo debe ser una imagen","error");
        	}else{
        		location.reload();
        	}
        	// console.log(result);
        }
    });
});

$("body").on( "click", ".btn_editar_ciudad", function() {
	copy_js.btn_action			= false;
	$('#cityForm').show();
	$('label[for="cityForm"]').show();
});
$("body").on( "click", ".btn_guardar_form", function() {
	var user_id				= $('#modal_form_body #user_id').val();
	var nombre 				= $('#name').val();
	var telephone 			= $('#telephone').val();
	var cell_phone			= $('#cell_phone').val();
	var role 				= $('#role').val();
	if (copy_js.btn_action) {
		var city 			= $('#city').val();
	} else {
		var city 			= $('#cityForm').val();
		if (city == '') {
			city 			= $('#city').val();
		}
	}
	if (nombre != '' && telephone != '' && cell_phone != '') {
		$('.btn_guardar_form').hide();
		$.post(copy_js.base_url+copy_js.controller+'/editSave',{nombre:nombre,telephone:telephone,cell_phone:cell_phone,role:role,city:city,user_id:user_id}, function(result){
			location.href =copy_js.base_url+copy_js.controller;
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

$(".commisionUser").click(function(event) {
	event.preventDefault()
	var id = $(this).data("uid");
	$.post(copy_js.base_url+"commisions"+'/edit',{user_id:id}, function(result){
		console.log(result)
		$('#cuerpoComision').html(result);
		$('#modalCommisions').modal('show');
	});
});

$(".assignUsers").click(function(event) {
	event.preventDefault();
	var id = $(this).data("uid");
	$.get(copy_js.base_url+"users"+'/permissions',{user_id:id}, function(result){
		console.log(result)
		$('#cuerpoPermisos').html(result);
		$('#UserUsers').multiSelect();
		$('#modalPermisos').modal('show');
	});
});

$(".copyUsers").click(function(event) {
	event.preventDefault();
	var id = $(this).data("uid");
	$.get(copy_js.base_url+"users"+'/copias',{user_id:id}, function(result){
		console.log(result)
		$('#cuerpoCopias').html(result);
		$('#UserCopias').multiSelect();
		$('#modalCopias').modal('show');
	});
});


$("body").on('change', '#CommisionRangeOneInit', function(event) {
	$("body").find("#CommisionRangeOneEnd").attr("min", parseInt($(this).val())+1);

	if($("body").find("#CommisionRangeOneEnd").val() == ""){
		$("body").find("#CommisionRangeOneEnd").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeOneEnd").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeOneEnd").val(parseInt($(this).val())+1);
		}
	}
	if($("body").find("#CommisionRangeOnePercentage").val() == ""){
		$("body").find("#CommisionRangeOnePercentage").val("1");
	}
	$("body").find("#CommisionRangeOneEnd,#CommisionRangeOnePercentage").removeAttr('readonly');
	$("body").find("#CommisionRangeOneEnd").trigger("change");
});

$("body").on('change', '#CommisionRangeOneEnd', function(event) {
	$("body").find("#CommisionRangeTwoInit").attr("min", parseInt($(this).val())+1);
	if($("body").find("#CommisionRangeTwoInit").val() == ""){
		$("body").find("#CommisionRangeTwoInit").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeTwoInit").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeTwoInit").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeTwoInit,#CommisionRangeTwoEnd").removeAttr('readonly');
	$("body").find("#CommisionRangeTwoInit").trigger("change");
});

$("body").on('change', '#CommisionRangeTwoInit', function(event) {
	$("body").find("#CommisionRangeTwoEnd").attr("min", parseInt($(this).val())+1);

	if($("body").find("#CommisionRangeTwoEnd").val() == ""){
		$("body").find("#CommisionRangeTwoEnd").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeTwoEnd").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeTwoEnd").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeTwoEnd,#CommisionRangeTwoPercentage").removeAttr('readonly');
	$("body").find("#CommisionRangeTwoEnd").trigger("change");
});

$("body").on('change', '#CommisionRangeTwoEnd', function(event) {
	$("body").find("#CommisionRangeThreeInit").attr("min", parseInt($(this).val())+1);
	if($("body").find("#CommisionRangeThreeInit").val() == ""){
		$("body").find("#CommisionRangeThreeInit").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeThreeInit").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeThreeInit").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeThreeInit,#CommisionRangeThreeEnd").removeAttr('readonly');
	$("body").find("#CommisionRangeThreeInit").trigger("change");
});

$("body").on('change', '#CommisionRangeThreeInit', function(event) {
	$("body").find("#CommisionRangeThreeEnd").attr("min", parseInt($(this).val())+1);

	if($("body").find("#CommisionRangeThreeEnd").val() == ""){
		$("body").find("#CommisionRangeThreeEnd").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeThreeEnd").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeThreeEnd").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeThreeEnd,#CommisionRangeThreePercentage").removeAttr('readonly');
	$("body").find("#CommisionRangeThreeEnd").trigger("change");
});

$("body").on('change', '#CommisionRangeThreeEnd', function(event) {
	$("body").find("#CommisionRangeFourInit").attr("min", parseInt($(this).val())+1);
	if($("body").find("#CommisionRangeFourInit").val() == ""){
		$("body").find("#CommisionRangeFourInit").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeFourInit").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeFourInit").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeFourInit,#CommisionRangeFourEnd").removeAttr('readonly');
	$("body").find("#CommisionRangeFourInit").trigger("change");
});

$("body").on('change', '#CommisionRangeFourInit', function(event) {
	$("body").find("#CommisionRangeFourEnd").attr("min", parseInt($(this).val())+1);

	if($("body").find("#CommisionRangeFourEnd").val() == ""){
		$("body").find("#CommisionRangeFourEnd").val(parseInt($(this).val())+1);
	}else{
		if(parseInt($("body").find("#CommisionRangeFourEnd").val()) < parseInt($(this).val()) ){
			$("body").find("#CommisionRangeFourEnd").val(parseInt($(this).val())+1);
		}
	}
	$("body").find("#CommisionRangeFourEnd,#CommisionRangeFourPercentage").removeAttr('readonly');
	$("body").find("#CommisionRangeFourEnd").trigger("change");
});


$("body").on('change', '#CommisionRangeOnePercentage', function(event) {
	var total = parseFloat($(this).val())-0.1;
	$("body").find("#CommisionRangeTwoPercentage").attr("max", total.toFixed(2));
	$("body").find("#CommisionRangeTwoPercentage").attr("value", total.toFixed(2));
	$("body").find("#CommisionRangeTwoPercentage").trigger("change");
});

$("body").on('change', '#CommisionRangeTwoPercentage', function(event) {
	var total = parseFloat($(this).val())-0.1;
	$("body").find("#CommisionRangeThreePercentage").attr("max", total.toFixed(2));
	$("body").find("#CommisionRangeThreePercentage").attr("value", total.toFixed(2));
	$("body").find("#CommisionRangeThreePercentage").trigger("change");
});

$("body").on('change', '#CommisionRangeThreePercentage', function(event) {
	var total = parseFloat($(this).val())-0.1;
	$("body").find("#CommisionRangeFourPercentage").attr("max", total.toFixed(2));
	$("body").find("#CommisionRangeFourPercentage").attr("value", total.toFixed(2));
	$("body").find("#CommisionRangeFourPercentage").trigger("change");
});


$(".aprobarUser").click(function(event) {
	var id = $(this).data("id")
	event.preventDefault();
	swal({
            title: "¿Deseas aprobar este usuario para que comienze a laborar como asesor externo?",
            text: "¿Deseas continuar con la acción?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonText:"Cancelar",
            confirmButtonText: "Aceptar",
            closeOnConfirm: true,
            focusConfirm: true,
            useRejections: true
        },
        function(val2){
            if(val2){
                $(".body-loading").show(); 
                $.post(copy_js.base_url+'users/approve',{id:id}, function(result){
                    location.reload();
                });
            }        
        });
});

$(".rechazarUser").click(function(event) {
	var id = $(this).data("id")
	event.preventDefault();
	 swal({
        title: "¿Estas seguro de rechazar este usuario?",
        text: "Por favor ingresa la razón del rechazo",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "Descripción de la razón"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la razón del rechazo","error");
            return false;
        }
        var razon = inputValue;
        $(".body-loading").show();        

        $.post(copy_js.base_url+'users/reject',{id,razon}, function(result){
            location.reload();
        });
    });
});

$(".btnConfigCustomer").click(function(event) {
	event.preventDefault();
	var url = $(this).attr("href");
	$.get(url, function(response) {
		$("#cuerpoClientes").html(response);
		$("#modalClientes").modal("show");
		setCustomerSelect2("#UserClienteId",'#modalClientes',true)
	});
});

$("body").on('submit', '#FormCategoriesUser', function(event) {
	event.preventDefault();
	$.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
		console.log(data);
	});
});

$(".btnConfigureCats").click(function(event) {
	event.preventDefault();
	var url = $(this).attr("href");
	$("#UserCategories").val("");
	$("#UserUserId").val($(this).data("user"));
	$.get(url, function(response) {

		response = $.parseJSON(response);
		var resource = response.categories;
		var actual 	 = response.actual;

		$("#modalCategorias").modal("show");
		var instance = $("#UserCategories").comboTree({
		    source : resource,
		    isMultiple:true,
		    selected: actual,
		    cascadeSelect:true,
		    collapse:true
		});

		$(".comboTreeArrowBtn").trigger('click');

		$("#btnGuardaCategoria").click(function(event) {
			event.preventDefault();
			var user_id = $("#UserUserId").val();
			var ids = instance.getSelectedIds();
			$(".body-loading").show();
			
			$.post(url, {ids,user_id}, function(data, textStatus, xhr) {
				$(".body-loading").hide();
				location.reload();
			});
			
		});

	});
});        