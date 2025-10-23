$(".checkB").click(function(event) {
	var clase = $(this).data("class");
	var id_clase = $(this).data("id");
	calculateTotal(clase,id_clase,true)
});

function calculateTotal(clase, id_clase,show = false, btn = null){

	var class_check = "."+clase;

	var class_delete = "";

	totalCount = 0;
	totalValue = 0;
	quantity = 0;
	totalChecks = 0;
	datosSend = {
		motive : "",
		products: [],
		bloqueo: bloqueos ? 1 : 0,
	};

	$(class_check).each(function(index, el) {
		checkAllData = "."+$(this).data("check");
		class_delete = $(this).data("delete");

		if($(this).prop("checked")){
			totalCount+=1;
			var id_qt = "#qt_prod_"+$(this).data("product");
			datosSend.products.push({
				id_product: $(id_qt).data("id"),
				quantity  : $(id_qt).val(),
				brand	  : $(this).data("brand"),
			});

			quantity+=parseInt($(id_qt).val());

			totalValue += ($(id_qt).val() * parseFloat($(id_qt).data("cost")))
		}else{
			$("body").find(checkAllData).prop("checked",false);
		}
		totalChecks++;
	});
	if(totalCount == totalChecks){
		$("body").find(checkAllData).prop("checked",true);
	}

	if(totalCount > 0){
		if(class_delete != ""){
			$("body").find("span."+class_delete).html(totalCount);
			$("body").find("a."+class_delete).show();
		}
	}else{
		$("body").find("a."+class_delete).hide();
	}

	var idInter = "#internacional_"+id_clase;
	datosSend.internacional = $(idInter).val();


	if(show){
		var idTotal = "#totalProducts_"+id_clase;
		$(idTotal).html(totalCount);
		var idTotalCost = "#totalCost_"+id_clase;

		$(idTotalCost).html(totalValue);
		var idTotalQuantity = "#quantityTotal_"+id_clase;
		$(idTotalQuantity).html(quantity);
	}else{
		var idMotive = "#razon_"+id_clase;
		if($(idMotive).val() == "" || $.trim($(idMotive).val()) == ""){
			message_alert("Debes escribir la razón de porque deseas realizar la imporación","error");
			return false;
		} 
		if(totalCount == 0){
			message_alert("No se han seleccionado productos para la marca o el total es 0","error");
			return false;
		}else{
			datosSend.motive =  $.trim($(idMotive).val());
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

			swal({
		        title: "¿Estas seguro de esta solicitud?",
		        text: "¿Deseas añadir estos productos para reposición de inventario?",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonClass: "btn-danger",
		        cancelButtonText:"No, cancelar",
		        confirmButtonText: "Si, continuar",
		        closeOnConfirm: false
		    },
		    function(){
		    	$.post(copy_js.base_url+'FlowStages/addImportSales', datosSend, function(data, textStatus, xhr) {
		    		location.href = copy_js.base_url+'ProspectiveUsers/request_import_brands?brand_id='+dataBrand;
		    	});
		    });
		}
	}
	console.log(datosSend)
	

}

$(".quantityNumber").change(function(event) {
	var clase = $(this).data("class");
	var id_clase = $(this).data("brand");
	var id_product = $(this).data("id");

	var idTotal = "#total_prod_"+id_product;

	var precio = parseInt($(this).val()) * parseFloat($(this).data("cost"));

	precio = number_format(precio);

	calculateTotal(clase,id_clase,true)
});

$(".envioCotClass").click(function(event) {
	var clase = $(this).data("class");
	var id_clase = $(this).data("brand");
	calculateTotal(clase,id_clase,false,$(this))	
});

$("body").on('click', '.showBills', function(event) {
	event.preventDefault();
	id = $(this).data("id");
	$.post(copy_js.base_url+'ProspectiveUsers/get_ventas/'+id, function(data, textStatus, xhr) {
		$("#cuerpoModalViewVentas").html(data);
		$("#modalViewVentas").modal("show");
	});
});

$("body").on('click', '.modalFlujoClick', function(event) {
	event.preventDefault();
	$("#modalViewVentas").modal("hide");
});

$("body").on('click', '.cierreModal', function(event) {
	event.preventDefault();
	$("#modalViewVentas").modal("show");
});

$("body").on('click', '.deleteProduct', function(event) {
	event.preventDefault();
	id = $(this).data("id");

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
	swal({
        title: "¿Confirmar operación?",
        text: "¿Por favor confirme que desea eliminar este registro de venta?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
    	$.post(copy_js.base_url+'ProspectiveUsers/deleteInventoryVenta', {id}, function(data, textStatus, xhr) {
    		location.href = url;
    	});
    });
});

$(".checkAll").change(function(event) {
	
	claseCheck 	= $(this).data("class");
	checkAll 	= $(this).is(":checked") ? true : false;

	if(checkAll){
		$("body").find("."+claseCheck).prop("checked",true);
	}else{
		$("body").find("."+claseCheck).prop("checked",false);
	}

	var id_clase = $(this).data("id");
	calculateTotal(claseCheck,id_clase,true)

});


$("body").on('click', '.facturaData', function(event) {
	event.preventDefault();
	var number = $(this).data("numero");
	var prefijo = $(this).data("prefijo");
	$.post(copy_js.base_url+'ProspectiveUsers/get_document_new', {number,prefijo,no_show:true}, function(data, textStatus, xhr) {
		$("#modalBodyFactura").html(data);
		$("#modalFactura").modal("show");
		$("#modalViewVentas").modal("hide");

		$('#modalFactura').on('hidden.bs.modal', function (e) {
      		$("#modalViewVentas").modal("show");
  		})

	});
});

$(".deleteAll").click(function(event) {
	event.preventDefault();

	var ids = [];
	var parts = [];

	clase = "."+$(this).data("class");

	$("body").find(clase).each(function(index, el) {
		if($(this).is(":checked")){
			ids.push($(this).val());
			parts.push($(this).data("part"));
		}
	});

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

	swal({
        title: "¿Confirmar operación?",
        text: "¿Por favor confirme que desea eliminar esta(s) referencia(s): "+parts.toString()+" ?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
    	$.post(copy_js.base_url+'ProspectiveUsers/deleteInventoryVentaMasive', {ids:ids}, function(data, textStatus, xhr) {
    		location.href = url;
    	});
    });

	console.log(ids);
	console.log(parts);

});