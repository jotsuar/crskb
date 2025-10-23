$("body").on('click', '.infoInventory ', function(event) {
	event.preventDefault();
	var htmlInfo = $(this).next("#infoProductInventory").html();
	swal({
        title: "Detalle de inventario",
        text: htmlInfo,
        type: "info",
        html: true,
        containerClass: "containerDetailUnLock"
    },
    function(){
        
    });

});

$("body").on('click', '.lockInventory ', function(event) {
	event.preventDefault();
	var htmlInfo = $(this).next("#bloqueoProductosInfo").html();
	swal({
        title: "Detalle de bloqueo",
        text: htmlInfo,
        type: "info",
        html: true,
        containerClass: "containerDetailLock"
    },
    function(){
        
    });

});


$(".verDetalleRechazo").click(function(event) {
    event.preventDefault();
    $.post($(this).attr("href"), {}, function(data, textStatus, xhr) {
        $("#bodyRechazo").html(data);
        $("#modalDetalleReject").modal("show");
    });
});