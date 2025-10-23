const IDS_PRODUCTS =  [];


$("#SuggestedProductProductPpal").select2({
    placeholder: "Seleccionar producto",
    allowClear: true,
    scrollAfterSelect: true,
});

function validate_products_suggested(){
	var product_val = $("#SuggestedProductProductPpal").val();
	if (product_val == "") {
		$("#TBodyProducto").html("");
		$("#tableProducts").hide();
	}else{
		$("#TBodyProducto").html("");
		$.post(copy_js.base_url+copy_js.controller+"/products_related", {product: product_val}, function(data, textStatus, xhr) {
			$("#TBodyProducto").html(data);
			if (data.length > 100) {
				$(".tr_remarket").each(function(index, el) {
					IDS_PRODUCTS.push( parseInt( $(this).data("id") ) )
				});
				showBtns();
			}
		});
		$("#tableProducts").show();
		load_data_search();
	}
	deleteProductsIDs();
	showBtns();
}

$("#SuggestedProductProductPpal").change(function(event) {
	validate_products_suggested();
});

validate_products_suggested();

function deleteProductsIDs(){
	for (i in IDS_PRODUCTS){
        IDS_PRODUCTS.splice(i, 1);
    }
}

deleteProductsIDs();

function load_data_search(){
	var products_ids = [];

	if ($("#SuggestedProductProductPpal").val() != "") {
		products_ids.push($("#SuggestedProductProductPpal").val());
	}

    $.post(copy_js.base_url+'Products/paintData',{remarketing: 1,products_ids}, function(result){
        var dataController = $.parseJSON(result);
        $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            maxItem: 10,
            order: "asc",
            hint: true,
            maxItemPerGroup: 5,
            backdrop: {
                "background-color": "#fff"
            },
            group: {
                template: "{{group}}"
            },
            emptyTemplate: 'No se encuentran resultados: "{{query}}"',
            source: {
                Nombre: {
                    display: "name",
                    data: dataController
                },
                Parte: {
                    display:"part_number",
                    data: dataController
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    find_product(item.id);
                    $('.js-typeahead').val('');
                    $(".typeahead__container").removeClass('cancel backdrop result hint');
                }
            },
            debug: true
        });
    });
}

function find_product(id){
    if(IDS_PRODUCTS.indexOf(id) === -1){
        $.post(copy_js.base_url+copy_js.controller+'/get_product_tr', {id}, function(data, textStatus, xhr) {
            $("#TBodyProducto").append(data);
        }); 
        IDS_PRODUCTS.push(id);
        showBtns();
    }   
}


$("body").on('click', '.btnDelete', function(event) {
    event.preventDefault();
    var id          = $(this).data("id");
    var position    = -1;
    var idTr        = "tr#trID_"+id;

    for (i in IDS_PRODUCTS){
        if(IDS_PRODUCTS[i] == id){
            position = i;
            break;
        }
    }

    position = 0;
    if(position != -1){
        IDS_PRODUCTS.splice(position, 1);
    }
    $(this).parent("td").parent("tr").remove();
    showBtns();

});

function showBtns(){
	if (IDS_PRODUCTS.length > 0) {
		$("#guardaBtn").show();
	}else{
		$("#guardaBtn").hide();
	}
}


$("#SuggestedProductAddForm").on('submit', function(event) {

	if (IDS_PRODUCTS.length  == 0 || $("#SuggestedProductProductPpal").val() == "" ) {
		message_alert("Debe seleccionar un producto principal y como mínimo uno relacionado.","error");
		event.preventDefault();
		return false;
	}

	
});