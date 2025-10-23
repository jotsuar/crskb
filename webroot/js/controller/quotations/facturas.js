$("body").on('click', '.proformaBtn', function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	var type = $(this).data("type");
	var client = $(this).data("client");
	var url = $(this).attr("href")
	$.post(url,{id,type,client},function(response) {
		$("#cuerpoFacturacionProforma").html(response)
		$("#modalNewFactura").modal("show");
		console.log(response)
	});
});