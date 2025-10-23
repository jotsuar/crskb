$('body').on( "click", ".editPrductEdit", function() {
    var product_id        = $(this).data('uid');
    $.post(copy_js.base_url+copy_js.controller+'/deleteProductTemplateEdit',{product_id:product_id}, function(result){
        $('#tr_'+product_id).remove();
    });
});

function edit_product(id){
	$.post(copy_js.base_url+'Products/get_data_edit_template',{id:id}, function(result){
        var num = parseInt(result);
        if (num > 0) {
            message_alert("El producto ya está agregado","error");
        } else {
            $('#tbody').append(result);
        }
    });
}
