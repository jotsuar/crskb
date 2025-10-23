$(".state_solicitud").click(function() {
	$.post(copy_js.base_url+'FlowStages/state_invalid',{}, function(result){
		$('#modal_big_information_body').html(result);
		$('#modal_big_information_label').text('Procesamiento a solicitud de importación');
		$('#modal_big_information').modal('show');
	});
});

$(".deleteOnlyProduct").click(function(event) {
	event.preventDefault();
	var id 			= $(this).data("id");
	var importId  	= $(this).data("import");

	swal({
        title: "¿Estas seguro de eliminar este producto?",
        text: "¿Por favor ingresa la razón por la cual eliminas el producto?",
        type: "input",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false,
        inputPlaceholder: "Descripción"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            message_alert("Por favor ingresa por la que eliminas el producto.","error");
            return false;
        }

        $.post(copy_js.base_url+'ProspectiveUsers/rejectOnlyProduct', {id,importId,razon:inputValue }, function(data, textStatus, xhr) {
	    		location.reload();
	    	});
       
    });
	return false;
});

$("body").on('click', '.viewMargenDetail', function(event) {
	event.preventDefault();
	$("#bodyMarge").html("");
	event.preventDefault();
	var idData = "#"+$(this).data("id");
	$("#bodyMarge").html($(idData).html());
	$("#detalleDeMargen").modal("show");
}).on('click', '.showHideDetail', function(event) {
	event.preventDefault();
	var id = "#"+$(this).data("id");
	$(this).children("i").toggleClass('fa-eye-slash');
	$(id).toggleClass('noShowDetail');
});


$(".cuadro_importacion_id").on('click',function(){
	var producto_currency 		= $(this).data('uid');
	var d              			= preload();
    $('.cuadro_importacion_'+producto_currency).append(d);
    $('.cuadro_importacion').addClass("dnone");
    $('.cuadro_importacion_'+producto_currency).removeClass("dnone");
    $.post(copy_js.base_url+'Products/data_importaciones',{producto_import_id:producto_currency}, function(result){
        $('.cuadro_importacion_'+producto_currency).empty();
	    $('.cuadro_importacion_'+producto_currency).html(result);
    });
});
$(".state_orden").click(function() {
	var producto_import_id 		= $(this).data('uid');
	var state 					= $(this).data('state');
	$.post(copy_js.base_url+'Products/orden_montada',{producto_import_id:producto_import_id,state:state}, function(result){
		if (copy_js.importacion_solicitud == state) {
			$('#modal_cotizado_body').html(result);
			$('#modal_cotizado_label').text('Procesamiento a despacho a orden montada');
			$('#modal_cotizado').modal('show')
		} else {
			$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a orden montada');
			$('#modal_big_information').modal('show');
		}
	});
});

$('.btn_guardar_cotizado').click(function(){
	var numero_orden 				= $('#ordenNumeroOrden').val();
	var orden_proveedor 			= $('#ordenProveedor').val();
	if (numero_orden != '' && orden_proveedor != '') {
		var formData            = new FormData($('#form_orden')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'Products/ordenMontada',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.reload();
	        }
	    });
	} else {
		message_alert("Los campos son requeridos","error");
	}
});



$(".state_despacho_proveedor").click(function() {
	var producto_import_id 			= $(this).data('uid');
	var state 						= $(this).data('state');
	var deadline 					= $(this).data('deadline');
	$.post(copy_js.base_url+'Products/despacho_proveedor_view',{producto_import_id:producto_import_id,state:state,deadline:deadline}, function(result){
		if (copy_js.importacion_orden == state) {
			$('#modal_contactado_body').html(result);
			$('#modal_contactado_label').text('Procesamiento a producto despachado por el proveedor');
			$('#modal_contactado').modal('show');
		} else {
			$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a producto despachado por el proveedor');
			$('#modal_big_information').modal('show');
		}
	});
});
$('.btn_guardar_contactado').click(function(){
	var link 				= $('#productLink').val();
	if (link != '') {
		var formData            = new FormData($('#form_despacho')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'Products/despachoProveedorSave',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.reload();
	        }
	    });
	} else {
		message_alert("El link es requerido","error");
	}
});

$("body").on('click', '.classInformeCliente', function(event) {
	event.preventDefault();
	$(".body-loading").show();
	$.get(copy_js.base_url+'import_requests/send_client_data/'+$(this).data("uid"), function(data) {
		$("#bodyDemora").html(data);
		$("#modalInforme").modal("show");
		$(".body-loading").hide();
	});
});

$("body").on('submit', '#formInformeCliente', function(event) {
	event.preventDefault();
	$(".body-loading").show();
	$.post($(this).attr("action"),$(this).serialize(), function(data) {
		$("#modalInforme").modal("hide");
		$(".body-loading").hide();
		message_alert("El texto cliente fue enviado correctamente","Bien");
	});
});

$('.state_llegada_miami').click(function(){
	var producto_import_id	 		= $(this).data('uid');
	var state 						= $(this).data('state');
	if (state != copy_js.importacion_despacho) {
		$.post(copy_js.base_url+'FlowStages/state_invalid',{}, function(result){
        	$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a llegada a miami');
			$('#modal_big_information').modal('show');
        });
	} else {
		swal({
	        title: "¿Estas seguro de pasar de etapa el producto a llegada a miami?",
	        text: "¿Deseas continuar con la acción?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"Cancelar",
	        confirmButtonText: "Aceptar",
	        closeOnConfirm: false,
	    },
	    function(val){
	    	if(val == true){
	    		var dataSend = {producto_import_id:producto_import_id};
	    		swal({
			        title: "¿Deseas aplicar la confirmación a todos los productos de la ordén?",
			        text: "¿Deseas continuar con la acción?",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonClass: "btn-danger",
			        cancelButtonText:"Cancelar",
			        confirmButtonText: "Aceptar",
			        closeOnConfirm: true,
			    },
			    function(val2){
			    	if(val2){
			    		dataSend.all = 1;
			    	}else{
			    		dataSend.all = 0;
			    	}
			        $.post(copy_js.base_url+'Products/llegadaMiami',dataSend, function(result){
			            location.reload();
			        });
			    });
	    	}
	    });
	}
});

$(".state_amerimpex").click(function() {
	var producto_import_id 		= $(this).data('uid');
	var state 						= $(this).data('state');
	$.post(copy_js.base_url+'Products/amerimpex',{producto_import_id:producto_import_id,state:state}, function(result){
		if (copy_js.importacion_miami == state) {
			$('#modal_pagado_body').html(result);
			$('#modal_pagado_label').text('Procesamiento a despacho a amerimpex');
			$('#modal_pagado').modal('show')
		} else {
			$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a despacho a amerimpex');
			$('#modal_big_information').modal('show');
		}
	});
});

$('.btn_guardar_pagado').click(function(){
	var numeroGuia 				= $('#productNumeroGuia').val();
	var transportadora 			= $('#productTransportadora').val();
	if (numeroGuia != '' && transportadora != '') {
		var formData            = new FormData($('#form_amerimpex')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'Products/amerimpexSave',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.reload();
	        }
	    });
	} else {
		message_alert("Los campos son requeridos","error");
	}
});

$('.state_nacionalizacion').click(function(){
	var producto_import_id 			= $(this).data('uid');
	var state 						= $(this).data('state');
	if (state != copy_js.importacion_amerimpex) {
		$.post(copy_js.base_url+'FlowStages/state_invalid',{}, function(result){
        	$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a nacionalización');
			$('#modal_big_information').modal('show');
        });
	} else {
		swal({
	        title: "¿Estas seguro de pasar de etapa el producto a nacionalización?",
	        text: "¿Deseas continuar con la acción?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"Cancelar",
	        confirmButtonText: "Aceptar",
	        closeOnConfirm: false
	    },
	    function(val){
	    	if(val){
		    	swal({
			        title: "¿Deseas aplicar esta configuración a todos los productos de la ordén actual?",
			        text: "¿Deseas continuar con la acción?",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonClass: "btn-danger",
			        cancelButtonText:"Cancelar",
			        confirmButtonText: "Aceptar",
			        closeOnConfirm: true
			    },
			    function(val2){
			    	var dataSend = {producto_import_id:producto_import_id};

			    	if(val2){
			    		dataSend.all = 1;
			    	}else{
			    		dataSend.all = 0;
			    	}
			    	$(".body-loading").show();
			        $.post(copy_js.base_url+'Products/nacionalizacion',dataSend, function(result){
			            location.reload();
			        });
			    });
	    	}
	    });
	}
});

$("body").on('submit',"#productoEmpresaForm",function(event) {
	event.preventDefault();
	formData = $(this).serialize();
	swal({
        title: "¿Estas seguro de pasar de etapa el producto a producto en la empresa?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(val){
    	$(".body-loading").show();
    	$.post(copy_js.base_url+'Products/productoEmpresaParts',formData, function(result){
            location.reload();
        });
    });
});

$('.product_empresa').click(function(){
	var producto_import_id	 		= $(this).data('uid');
	var state 						= $(this).data('state');
	var nacional 					= $(this).data("nacional");
	console.log(nacional)
	if (state != copy_js.importacion_nacionalizacion && typeof nacional === "undefined") {
		$.post(copy_js.base_url+'FlowStages/state_invalid',{}, function(result){
        	$('#modal_big_information_body').html(result);
			$('#modal_big_information_label').text('Procesamiento a producto en la empresa');
			$('#modal_big_information').modal('show');
        });
	} else {
		$.post(copy_js.base_url+'Products/getEmpresaProducto', {id: producto_import_id}, function(data, textStatus, xhr) {
			$("#modalEmpresaProducto").html(data);
			$("#empresaFake").modal("show");
		});
		return false;
		swal({
	        title: "¿Estas seguro de pasar de etapa el producto a producto en la empresa?",
	        text: "¿Deseas continuar con la acción?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"Cancelar",
	        confirmButtonText: "Aceptar",
	        closeOnConfirm: false
	    },
	    function(val){
	    	console.log(val)
	    	if(val){
		    	swal({
			        title: "¿Deseas aplicar esta configuración a todos los productos de la ordén actual?",
			        text: "¿Deseas continuar con la acción?",
			        type: "warning",
			        showCancelButton: true,
			        confirmButtonClass: "btn-danger",
			        cancelButtonText:"Cancelar",
			        confirmButtonText: "Aceptar",
			        closeOnConfirm: true
			    },
			    function(val2){
			    	var dataSend = {producto_import_id:producto_import_id};

			    	if(val2){
			    		dataSend.all = 1;
			    	}else{
			    		dataSend.all = 0;
			    	}
			    	$(".body-loading").show();
			        $.post(copy_js.base_url+'Products/productoEmpresa',dataSend, function(result){
			           $(".body-loading").hide();
			            location.reload();
			        });
			    });
	    	}

	    });
	}
});

$('.btn_novedad').click(function(){
	var producto_import_id 		= $(this).data('uid');
	$.post(copy_js.base_url+'Products/novedades_importacion',{producto_import_id:producto_import_id}, function(result){
        $('#modal_big_big_information_body').html(result);
		$('#modal_big_big_information_label').text('Novedades');
		$('#modal_big_big_information').modal('show');
    });
});

$('.btn_reportar_novedad').click(function(){
	var producto_import_id 		= $(this).data('uid');
	$.post(copy_js.base_url+'Products/save_novedad',{producto_import_id:producto_import_id}, function(result){
		$('#modal_administrar_despachado_body').html(result);
		$('#modal_administrar_despachado_label').text('Guardar una novedad');
		$('#modal_administrar_despachado').modal('show');
	});
});
$('.btn_guardar_administrar').click(function(){
	var description 		= $('#productDescription').val();
	if (description != '') {
		var formData            = new FormData($('#form_novedad')[0]);
		$.ajax({
	        type: 'POST',
	        url: copy_js.base_url+'Products/saveNovedad',
	        data: formData,
	        contentType: false,
	        cache: false,
	        processData:false,
	        success: function(result){
	        	location.reload();
	        }
	    });
	} else {
		message_alert("El campos es requerido","error");
	}
});

$("#EnviarEmail").click(function(event) {
	var id = $(this).data("uid");
	var emails = $('#emailCopy').val();
	var enviarInfo = $('#enviarInfo').val();
	console.log(emails)
	swal({
	    title: "¿Estas seguro de enviar la solicitud de importación al vendedor y seguir con el flujo?",
	    text: "¿Deseas continuar con la acción?",
	    type: "warning",
	    showCancelButton: true,
	    confirmButtonClass: "btn-danger",
	    cancelButtonText:"Cancelar",
	    confirmButtonText: "Aceptar",
	    closeOnConfirm: true
	},
	function(){
		$(".body-loading").show();
		$.post(copy_js.base_url+'ImportRequests/sendInfoProvider/'+id,{
			id,emails, enviar: enviarInfo
		}, function(result){
	        location.href = copy_js.base_url+"ProspectiveUsers/imports_revisions/";
	    });
		
	    console.log(id)
	});
});

$("#sendProviderInfo").click(function(event) {
	var id = $(this).data("uid");
	swal({
	    title: "¿Estas seguro de no enviar la solicitud de importación al vendedor y seguir con el flujo?",
	    text: "¿Deseas continuar con la acción?",
	    type: "warning",
	    showCancelButton: true,
	    confirmButtonClass: "btn-danger",
	    cancelButtonText:"Cancelar",
	    confirmButtonText: "Aceptar",
	    closeOnConfirm: false
	},
	function(){

		swal({
		    title: "¿Deseas informar al cliente?",
		    text: "¿Deseas continuar con la acción?",
		    type: "warning",
		    showCancelButton: true,
		    confirmButtonClass: "btn-primary",
		    cancelButtonClass: "btn-info",
		    cancelButtonText:"No, no informar al cliente",
		    confirmButtonText: "Si Informar al cliente",
		    closeOnConfirm: true
		},
		function(value){
			var enviar = value ? 1: 0;
			$(".body-loading").show();
			$.post(copy_js.base_url+'ImportRequests/noSendInfoProvider/'+id,{enviar}, function(result){
				location.href = copy_js.base_url+"ProspectiveUsers/imports_revisions/";
		    });
			
		    // console.log(id)
		});

	});

});

$('#FlowStageCopiasEmail').tagsinput();




$("#dataImportProvider").click(function(event) {
	var id = $(this).data("uid");
	$.get(copy_js.base_url+'import_requests/view/'+id, function(data, textStatus, xhr) {
		$("#cuerpoProvider").html(data);
	});
});

$("body").on('click', '.btnViewImage', function(event) {
	event.preventDefault();
	$(this).next("img").click();
});


if( typeof currencyProduct != "undefined" ){
	$("#costoFinal").html(valorTotal+ " " +  currencyProduct.toUpperCase() );
}

if( typeof currencyProduct != "undefined" ){
	$("#ventasFinal").html(ventasFinal+ " " +  currencyProduct.toUpperCase() );
}


$('#emailCopy').tagsinput();

$('#emailCopy').on('beforeItemAdd', function(event) {
	const regex = /^((?!\.)[\w-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
	if(!regex.test(event.item)){
		event.cancel = true;
	}
});

$("#pdfGenerate").click(function(event) {
	event.preventDefault();
	uid = $(this).data("uid");

	var urlPdf = copy_js.base_url+'ImportRequests/generatePdfImport/'+uid;
	$(".body-loading").show();

	$.post(urlPdf, {}, function(response, textStatus, xhr) {
		window.open(response);
		$(".body-loading").hide();
	});

	
});


$("#pdfGenerateDetail").click(function(event) {
	event.preventDefault();
	uid = $(this).data("uid");

	var urlPdf = copy_js.base_url+'ImportRequests/generatePdfImportDetail/'+uid;
	$(".body-loading").show();

	$.post(urlPdf, {}, function(response, textStatus, xhr) {
		window.open(response);
		$(".body-loading").hide();
	});

	
});

if (copy_js.user_role == "Gerente General" && copy_js.action == "products_import") {

        setTimeout(function() {
            $("body").find(".col .stylegeneralbox2").each(function(index, el) {
                $(this).removeClass('stylegeneralbox2')
            });
        }, 1000);
    }


$("body").on('click', '.maxCost', function(event) {
	event.preventDefault();
	var claseForm = "#"+$(this).data("clase");
	$(claseForm).toggleClass('formCostos');
});

$("body").on('click', '.selectOtherProduct', function(event) {
	event.preventDefault();
	id 			= $(this).data("id");
	request 	= $(this).data("request");
	import_id 	= $(this).data("import");
	currency 	= $(this).data("currency");

	$(".body-loading").show();

	$.post(copy_js.base_url+'products/add_reference/',{id,request,import_id, currency}, function(response, textStatus, xhr) {
		$(".body-loading").hide();
		$("#bodyAdd").html(response)
		$("#modalAddProduct").modal("hide");
		$("#modalAdd").modal("show");

		$('#modalAdd').on('hidden.bs.modal', function (e) {
		  $("#modalAddProduct").modal("show");
		})

	});

});

$('#modalAdd').on('hidden.bs.modal', function (e) {
  	$("#modalAddProduct").modal("show");
})


$("body").on('submit', '#addReferenceForm', function(event) {
	event.preventDefault();
	$(".body-loading").show();
	$.post(copy_js.base_url+'products/add_reference_final/',$(this).serialize(), function(response, textStatus, xhr) {
		location.reload();
	});
});


if($("#margenFinal").length){
	setTimeout(function() {
		var margen = parseFloat($("#margenFinal").html());

		if(margen >= 30){
			$("#finalVisible").removeClass('bg-danger');
			$(".mpf").addClass('bg-success');
		}else{
			$(".mpf").addClass('bg-danger');
		}

		$("#finalVisible").html("( "+ margen + " )");
	}, 1000);


	$("#viewDetialMargen").click(function(event) {
		event.preventDefault();
		$("#modalMargenView").modal("show");
	});
}




$("body").on('click', '#btnAddProduct', function(event) {
	event.preventDefault();
	$("#numbreParte").val("");
	$("#modalOtrosDatos").html("");
	$("#modalAddProduct").modal("show");	
});


$("body").on('click', '#buscarProducto', function(event) {
	event.preventDefault();

	var search = $("#numbreParte").val();


	if($.trim(search) == "" || search.length < 3){
		message_alert("El término de búsqueda es requerido y debe contener mínimo 3 caracteres","error");
	}else{
		$("#modalOtrosDatos").html("");
		$(".body-loading").show();
		$.post(copy_js.base_url+'products/others_references/',{productsIds,brandId,currency,importId,requestId,search}, function(response, textStatus, xhr) {
			$("#modalOtrosDatos").html(response)

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

$(".showHide").click(function(event) {
	$(".fijascroll").toggleClass('noShowDetail');
});

$("body").on('click', '.viewInventory', function(event) {
	event.preventDefault();
	var brand_id = $(this).data("id");
	$(".body-loading").show();
	$("#bodyInventario").html("");
	$.get(copy_js.base_url+'products/get_inventory_brand/'+brand_id, {import_active:1, brandId,currency,importId,requestId}, function(response, textStatus, xhr) {
		$("#modalInventario").modal("show");
		$("#bodyInventario").html(response);
		$("#form_productModal").parsley();

		setDataTableInfo();
		$(".body-loading").hide();
	});
	
});


function setDataTableInfo(clone = true){

	if ($("#noProductsInformation").length) {
		return true;
	}

  $('.tblProcesoSolicitud2').DataTable( {
  	'iDisplayLength': 5,
  	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
  	"ordering": false,
  	paging: false,
  	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
  } );
}

$("body").on('click', '.selectOtherProductImport', function(event) {
	event.preventDefault();
	var id = $(this).data("id")
	var idTr = "input#quantity"+id;

	if ($("body").find(idTr).attr("disabled")) {
		$("body").find(idTr).attr("min",1)
		$("body").find(idTr).removeAttr("disabled");
	}else{
		$("body").find(idTr).attr("min",0)
		$("body").find(idTr).attr("disabled","disabled")
	}
		validateTable();


	if ($(this).hasClass("btn-danger")) {
		$(this).removeClass("btn-danger");
		$(this).addClass("btn-success");
		$(this).children("i").removeClass("fa-times");
		$(this).children("i").addClass("fa-check");
	}else{
		$(this).removeClass("btn-success");
		$(this).addClass("btn-danger");
		$(this).children("i").removeClass("fa-check");
		$(this).children("i").addClass("fa-times");
	}

});

function validateTable(){
	var total = 0;

	$(".productsImportModal").each(function(index, el) {
		if (!$(this).attr("disabled")) {
			total++;
		}
	});

	if (total > 0) {
		$("#solicitaBtnModal").show();
	}else{
		$("#solicitaBtnModal").hide();
	}

}