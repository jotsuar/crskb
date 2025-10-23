$("#newFeature").click(function(event) {
	event.preventDefault();

	var product_id = $("#ProductId").val();

	$.post(copy_js.base_url+'features/feature_row',{product_id}, function(result){
        $("#features_list").prepend(result)
        $(".select22").select2();
    });
});

$("#newBullet").click(function(event) {
	event.preventDefault();
	var product_id = $("#ProductId").val();

	$.get(copy_js.base_url+'bullets/add/'+product_id,{}, function(result){
        $("#bullets_list").append(result)
    });
});

$("body").on('click', '.delete_row ', function(event) {
	event.preventDefault();
	var rowID = "#row_"+$(this).data('id');
	$(rowID).remove();
});

$("body").on('click', '.deleteBull ', function(event) {
	event.preventDefault();
	var rowID = "#"+$(this).data('id');
	$(rowID).parent('div').parent('div').parent('div').remove();
});

$("body").on('change', '.features_ids', function(event) {
	var feature_id = $(this).val();
	var id_values  = "#features_value_id_"+$(this).data("id");
	var valor = $(this).data("valor");
	$.post(copy_js.base_url+'features/feature_values_row',{feature_id}, function(result){
        $(id_values).html(result)
        if(valor != 0){
        	$(id_values).val(valor)
        	$(id_values).trigger('change')
        }
    });
});

$("body").on('submit', '#form_product', function(event) {
	

	var total = $(".features_ids").length;

	var puede = true;

	if(total == 0){
		message_alert("Se debe agregar una caracteristica","error");
		return false;
	}

	$(".features_ids").each(function(index, el) {
		
		var idRow = $(this).data("id");
		var nameNewID = "#features_name_id_"+idRow;
		var features_valueID = "#features_value_id_"+idRow;
		var features_valueNameID = "#features_value_name_id_"+idRow;

		if($(this).val() == "" && $.trim($(nameNewID).val()) == ''){
			message_alert("Se debe seleccionar o agregar una caracteristica nueva","error");
			 puede = false;
		}else if ($(this).val() != '' && $.trim($(nameNewID).val()) != '') {
			message_alert("No se puede seleccionar y agregar una caracteristica al tiempo","error");
			 puede = false;
		}else if ($(this).val() != '' && $.trim($(nameNewID).val()) == '' && $(features_valueID).val() == "" && $.trim($(features_valueNameID).val()) == '' ) {
			message_alert("Se debe seleccionar o crear un nuevo valor para todas las características","error");
			 puede = false;
		}else if ($(this).val() != '' && $.trim($(nameNewID).val()) == '' && $(features_valueID).val() != "" && $.trim($(features_valueNameID).val()) != '' ) {
			message_alert("No se puede seleccionar y agregar una caracteristica al tiempo","error");
			 puede = false;
		}else if ($(this).val() == '' && $.trim($(nameNewID).val()) != '' && $(features_valueID).val() != "" && $.trim($(features_valueNameID).val()) != '' ) {
			message_alert("No se puede seleccionar y agregar una caracteristica al tiempo","error");
			 puede = false;
		}

	});

	if(!puede){
		return false;
	}

});


setTimeout(function() {
	if($(".features_ids").length){
		$(".features_ids").each(function(index, el) {
			var feature_id = $(this).val();
			var id_values  = "#features_value_id_"+$(this).data("id");
			var valor = $(this).data("valor");
			console.log(valor);
			$.post(copy_js.base_url+'features/feature_values_row',{feature_id}, function(result){
		        $(id_values).html(result)
		        if(valor != 0){
		        	$(id_values).val(valor)
		        }
		    });
		});
	}
}, 1000);