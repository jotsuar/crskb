var optionsCategory = {
    source : structure,
    isMultiple: false,
    collapse:true,
    selectableLastNode:true
};

if(categorySelect != null){

    optionsCategory.selected = [categorySelect];
    optionsCategory.collapse = false;
}

var instance = $('#CategoryCategoryId').comboTree(optionsCategory);

function showLabels(){
	var categoryId = $('#CategoryCategoryId').val();

	if(categoryId == ""){
		$(".otros").hide();
	}else{
		$(".otros").show();
		categoryId = instance.getSelectedIds()[0];
	}

	var texto = categoryId == "0" ? " categoría" : " subcategoría";

	var textoName 	= $("#CategoryName").prev("label").html();
	textoName 		= textoName.replace("subcategoría", "");
	textoName 		= textoName.replace("categoría", "");
	$("#CategoryName").prev("label").html(textoName+texto)

	var textoDescription 	= $("#descriptionCategory").prev("label").html();
	textoDescription 		= textoDescription.replace("subcategoría", "");
	textoDescription 		= textoDescription.replace("categoría", "");
	$("#descriptionCategory").prev("label").html(textoDescription+texto)

	var textoMargen 	= $("#CategoryMargen").prev("label").html();
	textoMargen 		= textoMargen.replace("subcategoría", "");
	textoMargen 		= textoMargen.replace("categoría", "");
	$("#CategoryMargen").prev("label").html(textoMargen+texto)

	var textoMargen 	= $("#CategoryMargenWo").prev("label").html();
	textoMargen 		= textoMargen.replace("subcategoría", "");
	textoMargen 		= textoMargen.replace("categoría", "");
	$("#CategoryMargenWo").prev("label").html(textoMargen+texto)

}

$("body").on('change', '#CategoryCategoryId', function(event) {
	showLabels();
});

showLabels();

$("body").on('submit', '#CategoryAddForm,#CategoryEditForm', function(event) {
	$(".body-loading").show();
	$('#CategoryCategoryId').val(instance.getSelectedIds()[0]);
	$(this).submit();
});