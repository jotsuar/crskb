$("body").on('click', '.notesProduct', function(event) {
	event.preventDefault();
	id = $(this).data("id");
	$("#productoIdNotas").val(id);
	$("#notasDelProductoId").summernote(
		{
	      height: 120,
	      toolbar: [
	        ['style', ['bold', 'italic', 'underline', 'clear']],
	        ['para', ['ul', 'ol', 'paragraph']],
	        ['misc', ['undo', 'redo','codeview']],
	        ['link', ['linkDialogShow', 'unlink']]
	      ],
	      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
	      focus: true,
	      disableResizeEditor: true,
	      disableDragAndDrop: true
	    }
	);
	$.post(copy_js.base_url+'Products/dataProduct', {id}, function(data, textStatus, xhr) {
		$("#notasDelProductoId").summernote("code",data.Product.notes);	
		$("#modalComentario").modal("show");
	},'json');
});


$("body").on('change', '#garantiaId', function(event) {
	if ($(this).val() != "") {
        $.post(copy_js.base_url+'garantias/view/'+$(this).val(), {}, function(data) {
            $("#notasDelProductoIdCotizacion").summernote('code',data);
        });
    }
});

$("body").on('click', '.notaPrductCotizacion', function(event) {
	event.preventDefault();
	id = $(this).data("id");

	if (name_controller() == "QUOTATIONS") {
		$("#modalComentarioCotizacion #cuerpoModalViewVentas").html("");
		$.post(copy_js.base_url+'quotations/notes_form', {id}, function(data, textStatus, xhr) {
			$("#modalComentarioCotizacion #cuerpoModalViewVentas").html(data);
			$("#notasDelProductoIdCotizacion").summernote(
				{
			      height: 120,
			      toolbar: [
			        ['style', ['bold', 'italic', 'underline', 'clear']],
			        ['para', ['ul', 'ol', 'paragraph']],
			        ['color', ['color']],
			        ['misc', ['undo', 'redo','codeview']],
			        ['link', ['linkDialogShow', 'unlink']]
			      ],
			      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
			      focus: true,
			      disableResizeEditor: true,
			      disableDragAndDrop: true
			    }
			);
			$("#modalComentarioCotizacion").modal("show");
			console.log(data)
		});
		console.log("datos")
		
	}else{


		$("#productoIdNotasCotiza").val(id);
		$("#notasDelProductoIdCotizacion").summernote(
			{
		      height: 120,
		      toolbar: [
		        ['style', ['bold', 'italic', 'underline', 'clear']],
		        ['para', ['ul', 'ol', 'paragraph']],
		        ['misc', ['undo', 'redo','codeview']],
		        ['link', ['linkDialogShow', 'unlink']]
		      ],
		      fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
		      focus: true,
		      disableResizeEditor: true,
		      disableDragAndDrop: true
		    }
		);
		$("#modalComentarioCotizacion").modal("show");
	}
});

$("body").on('submit', '#formComentary', function(event) {
	event.preventDefault();
	var notaProducto = $("#notasDelProductoId").val();
	if(notaProducto == null || notaProducto == "" || $.trim(notaProducto) == ""){
		message_alert("La nota es requerida.","error");
	}else{
		var dataBrand = "";

		$(".linkTab").each(function(index, el) {
			if($(this).hasClass('active')){
				dataBrand = $(this).data("brand");
			}
		});

		var url = actual_url2;

		if(dataBrand != ""){
			if(url.indexOf("?") != -1){
				url = url+"brand="+dataBrand;
			}else{
				url = url+"?brand="+dataBrand;
			}
		}
		$.post(copy_js.base_url+'Products/saveComentary', $(this).serialize(), function(data, textStatus, xhr) {
			
			if(copy_js.controller == "Quotations" && copy_js.action == "add"){
				message_alert("Nota agregada correctamente.","Bien");
				$("#modalComentario").modal("hide");
			}else{
				location.href = url;
			}
				
		});	
	}
});

$("body").on('submit', '#formComentaryProducto', function(event) {
	event.preventDefault();
	var notaProducto = $("#notasDelProductoIdCotizacion").val();
	var idProducto = $("#productoIdNotasCotiza").val();
	if(notaProducto == null || notaProducto == "" || $.trim(notaProducto) == ""){
		message_alert("La nota es requerida.","error");
	}else{
		var clase = ".notaPCtz_"+idProducto;
		var claseInput = ".Nota-"+idProducto;
		$(clase).html("");
		$(clase).html(notaProducto);
		$(claseInput).val(notaProducto);
		$("#notasDelProductoIdCotizacion").val("");
		$("#notasDelProductoIdCotizacion").summernote('destroy');
		$("#modalComentarioCotizacion").modal("hide");
	}
});

jQuery(document).ready(function($) {
	 $('[data-toggle="popover"]').popover()
});

$("body").on('click', '.commentValue', function(event) {
	event.preventDefault();
	/* Act on the event */
});