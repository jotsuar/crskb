$("body").on( "click", "#btn_from_cliente_filter", function() {
	var correo 				= get_parameter_name('correo');
	var fechaInicial 		= get_parameter_name('fechaInicial');
	var fechaFin 			= get_parameter_name('fechaFin');
	if (fechaFin == '') {
		fechaFin = '';
	}
	listMessagesFilterFrom(correo,fechaInicial,fechaFin);
});

$("body").on( "click", "#btn_to_cliente_filter", function() {
	var correo 				= get_parameter_name('correo');
	var fechaInicial 		= get_parameter_name('fechaInicial');
	var fechaFin 			= get_parameter_name('fechaFin');
	if (fechaFin == '') {
		fechaFin = '';
	}
	listMessagesFilterTo(correo,fechaInicial,fechaFin);
});

function listMessagesFilterTo(correo,fechaInicial,fechaFin) {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	if (fechaFin == '') {
		var query = 'to:'+correo+' after:'+fechaInicial;
	} else {
		var query = 'to:'+correo+' after:'+fechaInicial+' before:'+fechaFin;
	}
	var options = {
		userId: 'me',
		'q': query
    };
	var request = gapi.client.gmail.users.messages.list(options);
	request.execute(function(response) {
		$.each(response.messages, function() {
			DATOSCONSULTA = DATOSCONSULTA + 1;
			var messageRequest = gapi.client.gmail.users.messages.get({
				'userId': 'me',
				'id': this.id,
				'q': query
			});
			messageRequest.execute(appendMessageRow);
		});
		datosNull(DATOSCONSULTA);
	});
	setTimeout(function(){ actionDatatable(); }, 1500);
}

function listMessagesFilterFrom(correo,fechaInicial,fechaFin) {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	if (fechaFin == '') {
		var query = 'from:'+correo+' after:'+fechaInicial;
	} else {
		var query = 'from:'+correo+' after:'+fechaInicial+' before:'+fechaFin;
	}
	var options = {
		userId: 'me',
		'q': query
    };
	var request = gapi.client.gmail.users.messages.list(options);
	request.execute(function(response) {
		$.each(response.messages, function() {
			DATOSCONSULTA = DATOSCONSULTA + 1;
			var messageRequest = gapi.client.gmail.users.messages.get({
				'userId': 'me',
				'id': this.id,
				'q': query
			});
			messageRequest.execute(appendMessageRow);
		});
		datosNull(DATOSCONSULTA);
	});
	setTimeout(function(){ actionDatatable(); }, 1500);
}