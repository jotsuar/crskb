$("body").on('submit', '#formComent', function(event) {
	$(".body-loading").show();
	event.preventDefault();
	$.post(copy_js.base_url+'Quotations/comment_quotation', $(this).serialize(), function(data, textStatus, xhr) {
		location.reload();
	});
});
$("body").on('submit', '#formReenvio', function(event) {
	$(".body-loading").show();
	event.preventDefault();
	$.post(copy_js.base_url+'Quotations/resend_quotation', $(this).serialize(), function(data, textStatus, xhr) {
		location.reload();
	});
});

$('#comentarioCotizacion').summernote({
 toolbar: [
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['misc', ['undo', 'redo','codeview']],
    ['link', ['linkDialogShow', 'unlink']]
  ],
  fontNames: ['Raleway'],
  focus: true,
  disableResizeEditor: true,
  height: 200,
});

$("body").on('submit', '#formAprovee', function(event) {
	event.preventDefault();
	var description = $('#comentarioCotizacion').val();
	$(".body-loading").show();
	if(description == ""){
		$(".body-loading").hide();
		message_alert("El comentario es requerido.","error");
	}else{
		var formData            = new FormData($('#formAprovee')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'Quotations/approve_quotation',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	if (result > 10) {
	        		location.reload();
	        	} else {
	        		$(".body-loading").hide();
					$('.btn_guardar_negociado').show();
	        		validate_documento_pdf_message(result);
	        	}
	        }
	    });
	}
});