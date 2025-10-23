$('#txt_buscador').on('keypress', function(e) {
    if(e.keyCode == 13){
        buscadorFiltro();
    }
});


$(".btn_buscar").click(function() {
	buscadorFiltro();
});

function buscadorFiltro(){
	var texto 						= $('#txt_buscador').val();
	var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	var hrefFinal 					= hrefURL+"?q="+texto;
	location.href 					= hrefFinal;
}

$("body").on('click', '.btnEditarProductoRef', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$.get(copy_js.base_url+'import_requests/editar_cantidad/'+id, function(data) {
		$("#bodyCantidad").html(data);
		$("#modalCantidades").modal("show");
		$("#formDatos").parsley();
	});
});

$("body").on( "click", ".btnEliminarProductoRef", function(event) {
	event.preventDefault();
	var id = $(this).data("id");
    swal({
        title: "¿Estas seguro de eliminar el producto?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+'import_requests/delete_part/',{id}, function(result){
            location.reload();
        });
    });
});



$("body").on('click', '.classAddProducts', function(event) {
    console.log("asasas")
    event.preventDefault();
    var id = $(this).data("uid");
    $("#numbreParte").val("");
    $("#actualID").val("");
    $("#modalOtrosDatosRef").html("");
    $("#modalAddProductRef").modal("show");  
    $("#actualID").val(id);
});


$("body").on('click', '#buscarProducto', function(event) {
    event.preventDefault();

    var search = $("#numbreParte").val();
    var actualID = $("#actualID").val();


    if($.trim(search) == "" || search.length < 3){
        message_alert("El término de búsqueda es requerido y debe contener mínimo 3 caracteres","error");
    }else{
        $("#modalOtrosDatosRef").html("");
        $(".body-loading").show();
        $.post(copy_js.base_url+'import_requests/others_references/',{search,id:actualID}, function(response, textStatus, xhr) {
            $("#modalOtrosDatosRef").html(response)

            if($("body").find(".tblProcesoSolicitud").length){
                $('.tblProcesoSolicitud').DataTable({
                    'iDisplayLength': 9,
                    "language": {"url": "//crm.kebco.co/Spanish.json",},
                    "order": [[ 0, "desc" ]],
                    "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
                });
                $(".body-loading").hide();
            }else{
                $(".body-loading").hide();          
            }
        });
    }

    return false;
    
});

$("body").on('click', '.selectOtherProduct', function(event) {
    event.preventDefault();
    id          = $(this).data("id");
    request     = $(this).data("request");
    import_id   = $(this).data("import");
    currency    = $(this).data("currency");

    $(".body-loading").show();

    $.post(copy_js.base_url+'import_requests/add_reference/',{id,request,}, function(response, textStatus, xhr) {
        $(".body-loading").hide();
        $("#bodyAdd").html(response)
        $("#modalAddProductRef").modal("hide");
        $("#modalAdd").modal("show");

        $('#modalAdd').on('hidden.bs.modal', function (e) {
          $("#modalAddProductRef").modal("show");
        })

    });

});

$('#modalAdd').on('hidden.bs.modal', function (e) {
    $("#modalAddProductRef").modal("show");
})


$("body").on('submit', '#addReferenceForm', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post(copy_js.base_url+'import_requests/add_reference_final/',$(this).serialize(), function(response, textStatus, xhr) {
        location.reload();
    });
});
