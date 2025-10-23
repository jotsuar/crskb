


$("body").on('click', '.actionBill', function(event) {
	event.preventDefault();
	var type 	= $(this).data("type");
	var invoice = $(this).data("invoice");
	var id 		= $(this).data("id");
	var message = "";

	if ( type == "2" || type == "3" ) {
		$("#modalFacturaDetalle").modal("hide");
		swal({
	        title: "¿Estas seguro de continuar con la acción?",
	        text: "Por favor ingresa la razón del rechazo o el mensaje al usuario",
	        type: "input",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"Cancelar",
	        confirmButtonText: "Aceptar",
	        closeOnConfirm: true,
	        inputPlaceholder: "Descripción de la razón o mensaje"
	    }, function (inputValue) {
	        if (inputValue === false) return false;
	        if (inputValue === "") {
	            message_alert("Por favor ingresa la razón del rechazo o el mensaje al usuario","error");
	            return false;
	        }
	        var message = inputValue;
	        $(".body-loading").show();
	        $.post(copy_js.base_url+'prospective_users/action_bill_gr',{id,message,type,invoice}, function(result){
	            message_alert("Acción ejecutada correctamente","Bien");
	            setTimeout(function() {
	                location.reload();
	            }, 5000);
	        });
	        location.reload();
    	});
	}else{
		swal({
	        title: "¿Estas seguro de aprobar la factura?",
	        text: "¿Deseas continuar con la acción?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"Cancelar",
	        confirmButtonText: "Aceptar",
	        closeOnConfirm: false
	    },
	    function(){
	        $(".body-loading").show();
	        $.post(copy_js.base_url+'prospective_users/action_bill_gr',{id,message,type,invoice}, function(result){
	            message_alert("Acción ejecutada correctamente","Bien");
	            setTimeout(function() {
	                location.reload();
	            }, 5000);
	        });
	        location.reload();
	    });
		
	}
});

setTimeout(function() {
	$('.datosPendientesDespachoDn').DataTable({
        'iDisplayLength': 18,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });
}, 1000);

	$(".mostradDatosFact").click( async function(event) {
		event.preventDefault();

		var quotation = $(this).data("quotation");
		var id 		  = $(this).data("uid");
		var invoice   = $(this).data("invoice");
		$("#detalleCotizacionDiv").html("");

		$(".aprobeModal,.approbeMesageModal,.RejectMessageModal").attr("data-id",id);
		$(".aprobeModal,.approbeMesageModal,.RejectMessageModal").attr("data-invoice",invoice);

		$(".body-loading").show();
		$("#bodyDetalleFactura").html("");

		var newid = "#"+$(this).data("id");
		$("#bodyDetalleFactura").html($(newid).html());

		quotation = quotation.split(",");

		const dataForm = new FormData();
		dataForm.append('modal', '1');
		dataForm.append('doce', '1');
		dataForm.append('biiled', '1');

		for (var i in quotation){
			var response = await fetch(copy_js.base_url+'Quotations/view/'+quotation[i], {
			   method: 'POST',
			   body: dataForm
			});
			$(".body-loading").hide();
			response.text().then( html => {$("#detalleCotizacionDiv").append(html);} )
		}

		$(".body-loading").hide();
		$("#modalFacturaDetalle").modal("show");
	});


$(".btnApproveAll").on('click', async function(event) {
	event.preventDefault();
	$(".body-loading").show();

	var datosApprove = [];
	$(".approbeBtn").each(function(index, el) {
		var type 	= $(this).data("type");
		var invoice = $(this).data("invoice");
		var id 		= $(this).data("id");
		datosApprove.push({type,id,invoice});
	});

	for (var i in datosApprove){
		const dataFormAction = new FormData();
		dataFormAction.append('type', datosApprove[i].type);
		dataFormAction.append('invoice', datosApprove[i].invoice);
		dataFormAction.append('id', datosApprove[i].id);

		var response = await fetch(copy_js.base_url+'prospective_users/action_bill_gr', {
		   method: 'POST',
		   body: dataFormAction
		});
		response.text().then( (data) => { console.log(data) } )
	}
	setTimeout(function() {
        location.reload();
    }, 2000);
	$(".body-loading").hide();
});

$(".btnRejectAll").on('click', async function(event) {
	event.preventDefault();
	var message = "";
	swal({
        title: "¿Estas seguro de continuar con la acción?",
        text: "Por favor ingresa la razón del rechazo o el mensaje al usuario",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: true,
        inputPlaceholder: "Descripción de la razón o mensaje"
    }, async function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa la razón del rechazo o el mensaje al usuario","error");
            return false;
        }
        var message = inputValue;
        $(".body-loading").show();
       	
        $(".body-loading").show();

		var datosApprove = [];
		$(".rejectBtn").each(function(index, el) {
			var type 	= $(this).data("type");
			var invoice = $(this).data("invoice");
			var id 		= $(this).data("id");
			datosApprove.push({type,id,invoice});
		});

		for (var i in datosApprove){
			const dataFormAction = new FormData();
			dataFormAction.append('type', datosApprove[i].type);
			dataFormAction.append('invoice', datosApprove[i].invoice);
			dataFormAction.append('id', datosApprove[i].id);
			dataFormAction.append('message', message);

			var response = await fetch(copy_js.base_url+'prospective_users/action_bill_gr', {
			   method: 'POST',
			   body: dataFormAction
			});
			response.text().then( (data) => { console.log(data) } )
		}
		setTimeout(function() {
	        location.reload();
	    }, 2000);
		$(".body-loading").hide();

	});

	
});


$("body").on('click', '.mostradDatos', function(event) {
    event.preventDefault();
    $("#bodyDetalleFacturaBloqueada").html("");
    var idMostrar = "#"+$(this).data("id");
    const copyHtml = $(idMostrar).html();
    console.log(copyHtml)
    $("#bodyDetalleFacturaBloqueada").html(copyHtml);
    $("#modalFactura").modal("show");
});