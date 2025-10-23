$("body").on('change', '#category_1', function(event) {
	if($(this).val() != "" && $(this).val() != "0"){
		inicializar("1");
		createOptions($(this).val(),"1")
	}else{
		inicializar("1");
	}
});

$("body").on('change', '#category_2', function(event) {
	if($(this).val() != ""){
		inicializar("2");
		createOptions($(this).val(),"2")
	}else{
		inicializar("2");
	}
});

$("body").on('change', '#category_3', function(event) {
	if($(this).val() != ""){
		inicializar("3");
		createOptions($(this).val(),"3")
	}else{
		inicializar("3");
	}
});

function inicializar($num){

	if($num == "1"){		
		$("#category_2").html('<option value="">Seleccionar</option>');
		$("#category_3").html('<option value="">Seleccionar</option>');
		$("#category_4").html('<option value="">Seleccionar</option>');
	}else if($num == "2"){		
		$("#category_3").html('<option value="">Seleccionar</option>');
		$("#category_4").html('<option value="">Seleccionar</option>');
	}else if($num == "3"){		
		$("#category_4").html('<option value="">Seleccionar</option>');
	}
}

function createOptions($value,$num){
	var values = categoriesInfoFinal[$value];

	if($num == "1"){		
		$("#category_2").html('<option value="">Seleccionar</option>');
		for (i in values){
			var selected = "";
			if(category2Select != null && category2Select == values[i].id ){
				selected = "selected"
			}
			$("#category_2").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
		}
		if(category2Select != null){
			$("#category_2").trigger('change');
		}
	}else if($num == "2"){		
		$("#category_3").html('<option value="">Seleccionar</option>');
		for (i in values){
			var selected = "";
			if(category3Select != null && category3Select == values[i].id ){
				selected = "selected"
			}
			$("#category_3").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
		}
		if(category3Select != null){
			$("#category_3").trigger('change');
		}
	}else if($num == "3"){		
		$("#category_4").html('<option value="">Seleccionar</option>');
		for (i in values){
			var selected = "";
			if(category4Select != null && category4Select == values[i].id ){
				selected = "selected"
			}
			$("#category_4").append('<option value="'+values[i].id+'" '+selected+'>'+values[i].name+'</option>');
		}
		if(category4Select != null){
			$("#category_4").trigger('change');
		}
	}
}

if(category1Select != null){
	$("#category_1").val(category1Select);
	$("#category_1").trigger('change');
} 