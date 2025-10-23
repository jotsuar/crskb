function add_product(id){
	$.post(copy_js.base_url+'Products/get_data_add_template',{id:id}, function(result){
        var num = parseInt(result);
        if (num > 0) {
            message_alert("El producto ya está en la plantilla o debes borrar la caché","error");
        } else {
            $('#details-country tbody').append(result);
        }
    });
}