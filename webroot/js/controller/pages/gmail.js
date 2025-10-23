var apiKey              = copy_js.api_key_cliente_google;
var clientId            = copy_js.codigo_aplicacion_google;
var scopes              = ['https://mail.google.com/',
							'https://www.googleapis.com/auth/gmail.send',
							'https://www.googleapis.com/auth/gmail.readonly',
							'https://www.googleapis.com/auth/userinfo.profile'];
var DATOSCONSULTA 		= 0;
var DATOSPOSITION 		= 0;
var GETPARAMETERS 		= "";
var USERNAME 			= "";
var VALIDCAMPO 			= false;

$(document).ready(function() {
	GETPARAMETERS = window.location.search.substring(1);
	$('.table').hide();
	$('#btn_from_cliente').hide();
	$('#btn_to_cliente').hide();
	$('#btn_cerrar').hide();
	$('#name_usuario_sesion').hide();
	$('#btn_from_cliente_filter').hide();
	$('#btn_to_cliente_filter').hide();
	if (copy_js.action == 'gmail') {
		$('#compose-button').hide();
		$('#btn_bandeja').hide();
		$('#btn_enviados').hide();
		$('#btn_destacados').hide();
		$('#btn_no_leidos').hide();
		$('#btn_leidos').hide();
		$('#btn_spam').hide();
		$('.filterclientgmail').hide();
		$('.usersesion').hide();
		document.getElementById('cliente_filtrar').style.display = "none";
	} else {
		if (GETPARAMETERS == '') {
			location.href = copy_js.base_url+copy_js.controller+'/gmail';
		}
	}
	setTimeout(function(){ authUsuario(); }, 1000);
});

$("body").on("keyup", "#compose-subject", function() {
    var cadena = $('#compose-subject').val();
    VALIDCAMPO = limit_characters(cadena,100);
});

$("body").on( "click", ".send-button", function() {
	sendEmail();
});

$("body").on( "click", "#btn_autorizar", function() {
	checkAuth();
});

$("body").on( "click", "#btn_bandeja", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessage();
});

$("body").on( "click", "#btn_no_leidos", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessageNotLeidos();
});

$("body").on( "click", "#btn_leidos", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessageLeidos();
});

$("body").on( "click", "#btn_spam", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessageCarpetas();
});

$("body").on( "click", "#btn_destacados", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessageDestacado();
});

$("body").on( "click", "#btn_enviados", function() {
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	listMessageEnviados();
});

$("body").on( "click", "#btn_to_cliente", function() {
	var cliente 		= $('#cliente_filtrar').val();
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	if (cliente != '') {
		listMessagesClientFrom(cliente);
	} else {
		actionDatatable();
		message_alert('Por favor selecciona un cliente o un contacto','error');
	}
});

$("body").on( "click", "#btn_from_cliente", function() {
	var cliente 		= $('#cliente_filtrar').val();
	actionDatatableDestroy();
	$('.table-inbox tbody').empty();
	if (cliente != '') {
		listMessagesClientTo(cliente);
	} else {
		actionDatatable();
		message_alert('Por favor selecciona un cliente o un contacto','error');
	}
});

function authUsuario(){
	gapi.auth.authorize({
		client_id: clientId,
		scope: scopes,
		immediate: true
	}, handleAuthResult);
}

function checkAuth() {
	gapi.auth.authorize({
		client_id: clientId,
		scope: scopes,
	}, handleAuthResult);
}

function handleAuthResult(authResult) {
	if(authResult && !authResult.error) {
		loadGmailApi();
		$('#btn_autorizar').hide();
		$('.table').show();
		$('#btn_cerrar').show();
		$('#name_usuario_sesion').show();
		if (copy_js.action == 'gmail') {
			$('#btn_bandeja').show();
			$('#compose-button').show();
			$('#btn_from_cliente').show();
			$('#btn_to_cliente').show();
			$('#cliente_filtrar').select2();
			document.getElementById('cliente_filtrar').style.display = "block";
			$('#btn_enviados').show();
			$('#btn_destacados').show();
			$('#btn_spam').show();
			$('#btn_no_leidos').show();
			$('#btn_leidos').show();
			$('.filterclientgmail').show();
			$('.usersesion').show();
		} else {
			$('#btn_from_cliente_filter').show();
			$('#btn_to_cliente_filter').show();
		}
	}
}

function loadGmailApi() {
    gapi.client.load('gmail', 'v1').then(function() {
		var request = gapi.client.gmail.users.getProfile({
			'userId': 'me'
		});
		request.then(function(resp) {
			USERNAME = resp.result.emailAddress;
			$('#name_usuario_sesion').text(USERNAME);
		}, function(reason) {});
		if (copy_js.action == 'gmail') {
			listMessage();
		}
    });
}

function sendEmail() {
	var para 			= $('#compose-to').val();
	var asunto 			= $('#compose-subject').val();
	var mensaje 		= $('#compose-message').val();
	if (para != '' && asunto != '' && mensaje != '') {
		if (VALIDCAMPO) {
			sendMessage(
			{
				'To': $('#compose-to').val(),
				'Subject': $('#compose-subject').val()
			},
			$('#compose-message').val(),
			composeTidy
			);
			return false;
		} else {
			message_alert('Solo se permiten máximo 100 caracteres en el asunto','error');
		}
	} else {
		message_alert('Todos los campos son obligatorios','error');
	}
}

function sendMessage(headers_obj, message, callback){
	var email = '';
	for(var header in headers_obj){
		email += header += ": "+headers_obj[header]+"\r\n";
	}
	email += "\r\n" + message;
	var sendRequest = gapi.client.gmail.users.messages.send({
		'userId': 'me',
		'resource': {
			'raw': window.btoa(email).replace(/\+/g, '-').replace(/\//g, '_')
		}
	});
	return sendRequest.execute(callback);
}

function listMessage() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var options = {
		userId: 'me'
    };
	var request = gapi.client.gmail.users.messages.list(options);
	request.execute(function(response) {
		$.each(response.messages, function() {
			DATOSCONSULTA = DATOSCONSULTA + 1;
			var messageRequest = gapi.client.gmail.users.messages.get({
				'userId': 'me',
				'id': this.id
			});
			messageRequest.execute(appendMessageRow);
		});
		datosNull(DATOSCONSULTA);
	});
	setTimeout(function(){ actionDatatable(); }, 1500);
}

function listMessageEnviados() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query = 'in:sent';
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

function listMessageLeidos() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query 			= 'is:read';
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
	setTimeout(function(){ $('#countMensajesLeidos').text(DATOSCONSULTA) }, 1500);
}

function listMessageNotLeidos() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query 			= 'is:unread';
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
	setTimeout(function(){ $('#countMensajesNotLeidos').text(DATOSCONSULTA); }, 1500);

}

function listMessageDestacado() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query = 'is:starred';
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

function listMessageCarpetas() {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query = 'in:anywhere';
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

function listMessagesClientTo(cliente) {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query = 'to:'+cliente;
	var options = {
		'userId': 'me',
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

function listMessagesClientFrom(cliente) {
	DATOSCONSULTA 		= 0;
	DATOSPOSITION 		= 0;
	var query = 'from:'+cliente;
	var options = {
		'userId': 'me',
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

function actionDatatable(){
	$('.table_resultados').DataTable({
		'iDisplayLength': 10,
        "language": {
		    "emptyTable":     "No hay información disponible",
		    "info":           " _START_ - _END_ de _TOTAL_ ",
		    "infoEmpty":      "Mostrando 0 - 0 de 0 registros",
		    "infoFiltered":   "(filtrado de _MAX_ registros)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Show _MENU_ entries",
		    "loadingRecords": "Cargando...",
		    "processing":     "Cargando...",
		    "search":         "Buscar:",
		    "zeroRecords":    "Tu búsqueda no generó resultados",
		    "paginate": {
		        "first":      "First",
		        "last":       "Last",
		        "next":       ">",
		        "previous":   "<"
		    },
		    "aria": {
		        "sortAscending":  ": activate to sort column ascending",
		        "sortDescending": ": activate to sort column descending"
		    }
		},
        "dom" : "<'contentsdt'<'buscador'f>>" +
				"<'contentsdt'<'cantidadrg'i><'paginador'p>>",
    	});
}

function actionDatatableDestroy(){
	var tabla = $('.table_resultados').DataTable({
		'iDisplayLength': 10,
        "language": {
		    "emptyTable":     "No hay información disponible",
		    "info":           " _START_ - _END_ de _TOTAL_ ",
		    "infoEmpty":      "Mostrando 0 - 0 de 0 registros",
		    "infoFiltered":   "(filtrado de _MAX_ registros)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Show _MENU_ entries",
		    "loadingRecords": "Cargando...",
		    "processing":     "Cargando...",
		    "search":         "Buscar:",
		    "zeroRecords":    "Tu búsqueda no generó resultados",
		    "paginate": {
		        "first":      "First",
		        "last":       "Last",
		        "next":       ">",
		        "previous":   "<"
		    },
		    "aria": {
		        "sortAscending":  ": activate to sort column ascending",
		        "sortDescending": ": activate to sort column descending"
		    }
		},
        "dom" : "<'contentsdt'<'buscador'f>>" +
				"<'contentsdt'<'cantidadrg'i><'paginador'p>>",
    	});
	tabla.destroy();
}

function datosNull(datos){
	if (!datos > 0) {
		actionDatatable();
	}
}

function appendMessageRow(message) {
	DATOSPOSITION = DATOSPOSITION + 1;
	if (getHeader(message.payload.headers, 'Subject') != '') {
		$('.table-inbox tbody').append(
			'<tr>\
				<td class="orderc">'+DATOSPOSITION+'</td>\
				<td class="de">'+getHeader(message.payload.headers, 'From')+'</td>\
				<td class="asunto">\
					<a href="#message-modal-' + message.id + '" data-toggle="modal" id="message-link-' + message.id+'">'+truncate_texto(getHeader(message.payload.headers, 'Subject'),80,'...')+
					'</a>\
				</td>\
				<td class="para">'+truncate_texto(getHeader(message.payload.headers, 'To'),30,'...')+'</td>\
				<td class="fecha">'+format_date(getHeader(message.payload.headers, 'Date'))+'</td>\
			</tr>'
		);
		$('body').append(
			'<div class="modal fade specialgmail" id="message-modal-' + message.id +
				'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
				<div class="modal-dialog modal-lg2">\
					<div class="modal-content">\
						<div class="modal-header">\
							<h2 class="modal-title" id="myModalgmail">' + getHeader(message.payload.headers, 'Subject') +'</h2>\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
							<span aria-hidden="true">&times;</span></button>\
						</div>\
						<div class="modal-body">\
							<iframe class="ifmessagegmail" id="message-iframe-'+message.id+'"></iframe>\
						</div>\
					</div>\
				</div>\
			</div>'
		);
	    $('#message-link-'+message.id).on('click', function(){
			var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
			$('body', ifrm).html(getBody(message.payload));
	    });
	} else {
		DATOSCONSULTA = DATOSCONSULTA - 1;
	}
}

function getHeader(headers, index) {
	var header = '';
	$.each(headers, function(){
		if(this.name === index){
			header = this.value;
		}
	});
	return header;
}

function getBody(message) {
	var encodedBody = '';
	if(typeof message.parts === 'undefined'){
		encodedBody = message.body.data;
	} else {
		encodedBody = getHTMLPart(message.parts);
	}
	encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
	return decodeURIComponent(escape(window.atob(encodedBody)));
}

function getHTMLPart(arr) {
	for(var x = 0; x <= arr.length; x++){
		if(typeof arr[x].parts === 'undefined'){
			if(arr[x].mimeType === 'text/html'){
				return arr[x].body.data;
			}
		} else {
			return getHTMLPart(arr[x].parts);
		}
	}
	return '';
}

$("body").on( "click", "#btn_cerrar", function() {
	SignOut();
});

function SignOut() {
	if (copy_js.action != 'gmail') {
		location.href = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost"+copy_js.base_url+copy_js.controller+'/'+copy_js.action+'?'+GETPARAMETERS;
	} else {
		location.href = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost"+copy_js.base_url+copy_js.controller+'/'+copy_js.action;
	}
}

function composeTidy(){
	$('#compose-modal').modal('hide');
	$('#compose-to').val('');
	$('#compose-subject').val('');
	$('#compose-message').val('');
}