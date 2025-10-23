DATOS_GENERALES = [];

$("#dataImport").click(function(event) {
	
	$.get(copy_js.base_url+copy_js.controller+'/add_import/2', function(data, textStatus, xhr) {
		$("#cuerpoOtro").html(data);
		setTimeout(function() {
			$("body").prepend('<script src="'+copy_js.base_url+'js/lib/jquery-2.1.0.min.js"></script>');
			$("body").append('<script src="'+copy_js.base_url+'js/lib/jquery.typeahead.js"></script>');
			load_data_search();
			// $("body").append('<script src="'+copy_js.base_url+'js/controller/prospectiveUsers/add_import.js"></script>');
		}, 1000);
	});

});


const convertArrayToObject = (array, key) => {
  const initialValue = {};
  return array.reduce((obj, item) => {
    return {
      ...obj,
      [item[key]]: item,
    };
  }, initialValue);
};


function showHideCurrency(){
	$("input.changeCurrency").each(function(index, el) {
		if($(this).prop('checked')){
			var brand = $(this).data("brand");
			var currency = $(this).val();

			if(currency == "COP"){
				var clasIdHide = ".usd_brand_"+brand;
				var clasIdSHow = ".cop_brand_"+brand+",.iva_"+brand;
			}else{
				var clasIdHide = ".cop_brand_"+brand+",.iva_"+brand;
				var clasIdSHow = ".usd_brand_"+brand;
			}
			$(clasIdHide).hide();
			$(clasIdSHow).show();
		}
		var claseBrand = $(this).data("class");
		var idBrand = $(this).data("brand");
		requestFinal(claseBrand,idBrand)
	});
} 
showHideCurrency();

$(".changeCurrency").click(function(event) {
	showHideCurrency();
});

$(".changeIva").click(function(event) {
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(claseBrand,idBrand)
});

function updatePriceAllProducts(element){
	var currency = element.data("currency");
	var id = element.data("id")
	var brand = element.data("brand")
	var classInput = ".price_"+currency+"_brand_"+brand+"_"+id;
	$(classInput).val(element.val())
}

$(".priceUsd,.priceCop").on('keypress', function(event) {
	updatePriceAllProducts($(this))	
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(claseBrand,idBrand)
}).on('change', function(event) {
	updatePriceAllProducts($(this))
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(claseBrand,idBrand)
});

function requestFinal($class,$brandId,$show = true){

	var class_check = "."+$class;
	totalCount = 0;
	totalValue = 0;
	quantity = 0;
	datosSend = {
		motive 	 : "",
		textBrand 	 : "",
		currency : "",
		request_id: "",
		type: "",
		products: [],
		flujos:[],
		after:[],
		details:[]
	};

	var continueSend = true;

	var tipoEnvio = "";

	number = 0;

	var classWishDetail = ".wish_"+$class;
	var classComment 	= "#comentario_"+$brandId;
	var classTypeCrea	= "#tipoCrea_"+$brandId;

	$(class_check).each(function(index, el) {
		if($(this).prop("checked")){
			totalCount+=1;			
			var idProduct 		= $(this).data("id");
			var productFlujo	= $(this).data("flujo");
			var flujoEnvio 		= $(this).data("alternative");
			var idBrand 		= $(this).data("brand");
			var typeRequest		= $(this).data("type");
			var marca 			= $(this).data("marca");
			tipoEnvio 			= $(this).data("envio");
			var idCalculate 	= $(this).data("calculate");
			var idMargen 		= $(this).data("margen");
			var idUUid 			= $(this).data("uniq");
			var classQuantity 	= ".quantity_brand_"+$(this).data("detail")+"_"+$(this).data("product");
			var classCurrency   = ".currency_brand_"+idBrand;
			var classIva   		= ".iva_brand_"+idBrand;
			var currencyBrand   = $(classCurrency+":checked").val().toLowerCase();
			var ivaBrand   		= $(classIva+":checked").val();
			var classCost   	= ".cost_"+currencyBrand+"_product_"+$(this).data("detail")+"_"+$(this).data("product");
			var classCostTotal	= ".total_cost_"+currencyBrand+"_product_"+$(this).data("detail")+"_"+$(this).data("product");
			var classPaymentMethod = ".forma_pago_"+currencyBrand+"_brand_"+idBrand;
			var classComment = "#comentario_"+idBrand;
			var classCotNumb = "#cotnumb_"+idBrand;
			var classAddress = "#direccion_"+idBrand;
			datosSend.details.push($(this).data("detalle"));

			if($.trim($(classQuantity).val()) == "0" || $.trim($(classQuantity).val()) == ""){
				message_alert("La cantidad de todos los productos seleccionados es requerida.","error");
				continueSend = false;
				return false;
			}

			if($.trim($(classCost).val()) == ""){
				message_alert("El costo de todos los productos seleccionados es requerido.","error");
				continueSend = false;
				return false;
			}

			if(currencyBrand == "cop" && ivaBrand == "1"){
				$(classCostTotal).val( ($(classQuantity).val() * parseFloat($(classCost).val()) ) * 1.19);
			}else{
				$(classCostTotal).val(($(classQuantity).val() * parseFloat($(classCost).val())));
			}
			datosSend.products.push({
				id_product: idProduct,
				id: idProduct,
				brand: $brandId,
				quantity  : $(classQuantity).val(),
				cost  	  : $(classCost).val(),
				flujo     : $(this).data("flujo"),				
				delivery  : $(this).data("delivery"),				
				detalle  : $(this).data("detalle"),	
				number 	: number,
				type: typeRequest,
			});

			datosSend.currency 	 = currencyBrand
			datosSend.iva 	 	 = ivaBrand
			datosSend.brand_id 	 = marca
			datosSend.request_id = $(this).data("request")			
			datosSend.payment 	 =  $(classPaymentMethod).val()	
			datosSend.comment 	 =  $(classComment).val()	
			datosSend.address 	 =  $(classAddress).val()	
			datosSend.cotnumb 	 =  $(classCotNumb).val()

			quantity 			 +=	parseInt($(classQuantity).val());

			if(currencyBrand == "cop" && ivaBrand == "1"){
				totalValue += ( $(classQuantity).val() * parseFloat($(classCost).val()) ) * 1.19;
			}else{
				totalValue += ( $(classQuantity).val() * parseFloat($(classCost).val()) );
			}

			
			datosSend.flujos.push($(this).data("flujo"));
			number++;


			$.post(copy_js.base_url+'prospectiveUsers/calculate_cost', {product:idProduct, flujo: flujoEnvio, costo: $(classCost).val(), cantidadProducto: $(classQuantity).val(), currency: currencyBrand,uuid:idUUid }, function(data, textStatus, xhr) {
	    		$(idCalculate).html(data);
	    		var idDat = "#"+idUUid;
	    		$("body").find(idMargen).html( $("body").find(idDat).html() );

	    		setTimeout(function() {
	    			if ($("body").find(idDat).length) {
		    			$("body").find(idMargen).parent(".viewdetailc").trigger('click');
		    		}
	    		}, 500);

	    	});
		}
	});

	$(classWishDetail).each(function(index, el) {
		if ($(this).prop("checked") ) {
			datosSend.after.push(parseInt($(this).val()));
		}
	});

	if(quantity === 0 && !$show){
		message_alert("Se debe seleccionar mínimo un producto.","error");
		continueSend = false;
	}

	if($show){
		if(!continueSend) return false;

		var classTotalQuantity  = ".total_quantity_brand_"+$brandId;
		var classTotalProducts  = ".total_product_brand_"+$brandId;
		var classTotalValue 	= ".total_"+datosSend.currency+"_brand_"+$brandId;
		$(classTotalQuantity).html(quantity);
		$(classTotalProducts).html(totalCount);
		$(classTotalValue).html(totalValue + " " + datosSend.currency.toUpperCase());
		sendProductsToOrder(convertArrayToObject(datosSend.products,"number"),$brandId)
	}else{

		if(!continueSend) return false;

		var idRazon = "#razon_"+$brandId;
		var idNota  = "#nota_"+$brandId;
		var idTexto = "#text_brand_"+$brandId;
		var brand_data = "#brandData_"+$brandId;

		var tipoGuada  = $(classTypeCrea).val();
		var inter_data = "#internacional_"+$brandId;

		datosSend.motive 		= $(idRazon).val();
		datosSend.nota 	 		= $(idNota).val();
		datosSend.textBrand 	= $(idTexto).val();
		datosSend.brand_data 	= $(brand_data).val();
		datosSend.internacional = $(inter_data).val();
		datosSend.type 			= tipoGuada;

		var valueEnvio    = tipoEnvio != "" ?  $("#"+tipoEnvio).val() : null;
		var IdenvioEmails = tipoEnvio != "" ?  $("#"+tipoEnvio).data("emails") : null;

		if (datosSend.brand_id == 13 || datosSend.brand_data == "13") {
			message_alert("Para la marca Kebco no es posible realizar órdenes ya que no es un proveedor oficial.","error");
		}else if(valueEnvio == "" || valueEnvio == null){
			message_alert("Se debe seleccionar un tipo de guardado.","error");
		}else if(tipoGuada == "" || tipoGuada == null){
			message_alert("Se debe seleccionar un tipo de creción de solicitd.","error");
		}else if($.trim(datosSend.motive) == ""){
    		message_alert("El motivo de la solicitud es requerido","error");
    	}else if($.trim(datosSend.textBrand) == ""){
    		message_alert("El texto al proveedor es requerido","error");
    	}else{
    		var orders = [];
    		var productosOrden = datosSend.products;
    		$("#total_products_"+$brandId+ " tr").each(function(index, el) {
    			for (var i in productosOrden){
    				if(productosOrden[i].id == parseInt($(this).attr("id"))){
    					orders.push(productosOrden[i]);
    				}
    			}
    			
    		});
    		datosSend.products 			= orders;
    		datosSend.guardado_envio 	= valueEnvio;

    		if(IdenvioEmails != null){
    			emailsEnvio 		= $("div#"+IdenvioEmails+">input").val();
    			datosSend.emails 	= emailsEnvio;
    		}

    		var titleButton = "Si, generar orden";

    		titleButton 	= typesSend[valueEnvio];

    		DATOS_GENERALES = datosSend; 

    		swal({
		        title: "¿Estas seguro de generar esta órden?",
		        text: "¿Confirmaste que hayas seleccionado todos los productos y tengan las cantidades y precios correctos?",
		        type: "warning",
		        showCancelButton: true,
		        confirmButtonClass: "btn-danger",
		        cancelButtonText:"No, corregir",
		        confirmButtonText: titleButton,
		        closeOnConfirm: false
		    },
		    function(){
		    	$(".body-loading").show();
		    	$.post(copy_js.base_url+'FlowStages/addImportFinal', datosSend, function(data, textStatus, xhr) {
		    		message_alert("Orden generada correctamente","bien");
		    		setTimeout(function() {
		    			$(".body-loading").hide();
			    		var request_id = datosSend.request_id;
		    			if(datosSend.guardado_envio == "1"){
			    			var emails = datosSend.emails;
			    			$.post(copy_js.base_url+'ImportRequests/sendInfoProvider/'+request_id,{
								request_id,emails
							}, function(result){
						        location.reload();
						    });
			    		}else if (datosSend.guardado_envio == "2") {
			    			$.post(copy_js.base_url+'ImportRequests/noSendInfoProvider/'+request_id,{}, function(result){
								location.reload();
						    });
			    		}else{
			    			location.reload();
			    		}
		    		}, 2000);
		    	});
		    });
    	}
		
	}
}

$("body").on('change', '.formaEnvio', function(event) {
	var idEmails = $(this).data("emails");
	if($(this).val() == "1"){
		$("div#"+idEmails).show();
	}else{
		$("div#"+idEmails).hide();
	}
});

$('input#emailCopy').tagsinput();

$("body").on('click', '.btnReload', function(event) {
	event.preventDefault();
	id = $(this).data("id");

	swal({
        title: "¿Devolver solicitud?",
        text: "¿Estas seguro de devolver esta solicitud al panel de reposición de inventario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"No",
        confirmButtonText: "Si, devolver solicitud",
        closeOnConfirm: false
    },
    function(){
    	$(".body-loading").show();
    	$.post(copy_js.base_url+'ImportRequests/reloadData', {id}, function(data, textStatus, xhr) {
    		$(".body-loading").hide();
    		location.reload(); 
    		console.log(data);
    	});
    });
});




$(".checkB").click(function(event) {
	var clase = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(clase,idBrand)
});

$(".checkB").change(function(event) {
	var clase = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(clase,idBrand)
});

$(".quantityNumber").change(function(event) {
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(claseBrand,idBrand)
});

$('body').on("keypress", ".priceUsd", function(event) {
  var charC = (event.which) ? event.which : event.keyCode; 
  if (charC == 46) { 
      if ($(this).val().indexOf('.') === -1) { 
          return true; 
      } else { 
          return false; 
      } 
  } else { 
      if (charC > 31 && (charC < 48 || charC > 57)) 
          return false; 
  } 
  return true; 
});

jQuery.curCSS = function(element, prop, val) {
    return jQuery(element).css(prop, val);
};

$('body').on("keypress", ".priceCop", function(event) {
  	var key = window.Event ? event.which : event.keyCode
	return (key >= 48 && key <= 57)
});

$(".envioImportClass").click(function(event) {
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");
	requestFinal(claseBrand,idBrand,false)
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

$('.textProvee').summernote(
	{
	  height: 100,
	  toolbar: [
	    ['style', ['bold', 'italic', 'underline', 'clear']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['misc', ['undo', 'redo','codeview']],
	    ['link', ['linkDialogShow', 'unlink']]
	  ],
	  fontNames: ['Raleway'],
	  focus: false,
	  disableResizeEditor: true
	}
);


$(".brandsLink").each(function(index, el) {
	var brand = $(this).data("brand");
	if(productsTable.hasOwnProperty(brand) && productsTable[brand] != null){
		var productsByBrand = productsTable[brand];
		sendProductsToOrder(productsByBrand, brand);
	}else{
		idTableDiv = "#tableOrder_"+brand;
		$("body").find(idTableDiv).html("");
	}
});

$(".brandsLink").click(function(event) {
	url = $(this).data("url");
	$(".body-loading").show();
	location.href = url;
});


function sendProductsToOrder(products,brand){
	$.post(copy_js.base_url+'FlowStages/get_table_to_order', products, function(data, textStatus, xhr) {
		// location.reload();
		idTableDiv = "#tableOrder_"+brand;
		$("body").find(idTableDiv).html(data);
		visualizarComentario();
        setTimeout(function() {
            visualizarComentario();
        }, 2000);
		sortTableData(brand);

	});
}

function sortTableData($brandId){

	var updateIndex = function(e, ui) {
		$('td.index', ui.item.parent()).each(function (i) {
			$(this).html(i+1);
		});

	};

	$("#total_products_"+$brandId).sortable({
		// helper: fixHelperModified,
		distance: 5,
		delay: 100,
		opacity: 0.6,
		cursor: 'move',
		stop: updateIndex
	}).disableSelection();

	visualizarComentario();
    setTimeout(function() {
        visualizarComentario();
    }, 2000);


}


$(".wishList").click(function(event) {
	var otraClase = "."+$(this).data("check");
	var claseBrand = $(this).data("class");
	var idBrand = $(this).data("brand");

	if ($(this).prop("checked")) {
		$(otraClase).each(function(index, el) {
			$(this).prop('checked', false);
		});
	}else{
		$(otraClase).each(function(index, el) {
			$(this).prop('checked', true);
		});
	}
	requestFinal(claseBrand,idBrand, true)
});

$("body").on('click', '.viewRelations', function(event) {
	event.preventDefault();
	id 		= $(this).data("id");
	request = $(this).data("request");

	$.post(copy_js.base_url+'ImportRequests/get_relations', {id,request}, function(data, textStatus, xhr) {
		$("body").find("#modalRelationBody").html(data);
	});

	$("#modalRelation").modal("show");

});


$("body").on('change', '.checkAllProducts', function(event) {
	clase = $(this).data("class");
	var totalProductos = 0;
	var seleccionaos = 0;
	var elemento = null;
	if($(this).is(':checked')){
		$("."+clase).each(function(index, el) {
			if($(this).data("type")  != "1"){
				if(!$(this).is(':checked')){
					$(this).prop("checked",true);
					if(elemento == null ){
						elemento = $(this);
					}
				}
			}
		});
	}else{
		$("."+clase).each(function(index, el) {
			if($(this).data("type")  != "1"){
				$(this).prop("checked",false);
				if(elemento == null ){
					elemento = $(this);
				}
			}
		});
	}
	
	if(elemento != null){
		elemento.trigger("change");
	}

});

$("body").on('change', '#PlantillaTexto', function(event) {
	idArea = "#"+$(this).data("area");
	if(!$(this).val() == ""){
		id = $(this).val();
		$.post(copy_js.base_url+'Notes/getNote', {id}, function(data, textStatus, xhr) {
			$(idArea).val(data.Note.description);
			$(idArea).summernote("code",data.Note.description);
		},"json");
	}
});


$("body").on('click', '.viewInventory', function(event) {
	event.preventDefault();
	var brand_id = $(this).data("id");
	$(".body-loading").show();
	$("#bodyInventario").html("");
	$.get(copy_js.base_url+'products/get_inventory_brand/'+brand_id, {}, function(response, textStatus, xhr) {
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

$("body").on('click', '.selectOtherProduct', function(event) {
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

$(".checkB").on('click', function(event) {
	var clase 		   = $(this).data("class");
	var delete_class = $(this).data("delete");
	delete_btn_validate(clase,delete_class);
});

$(".checkAllProducts").on('click', function(event) {
	var clase 		   = $(this).data("class");
	var delete_class = $(this).data("delete");
	setTimeout(function() {
		delete_btn_validate(clase,delete_class);
	}, 2000);
});


function delete_btn_validate($classInput, $deleteClass){

	var classFinal 			 = "."+$classInput;
	var classFinalDelete = "."+$deleteClass;
	var total 		 		   = 0;

	$(classFinal).each(function(index, el) {
		
		if ($(this).prop("checked")) {
			total = total + 1;
		}

	});

	if (total > 0) {
		$(classFinalDelete).show();
	}else{
		$(classFinalDelete).hide();
	}

}

$(".delete_requests").click(function(event) {
	event.preventDefault();
	var data_prod_class = "."+$(this).data("class");
	var ids_delete_req  = [];

	$(data_prod_class).each(function(index, el) {
		if ($(this).prop("checked")) {
			ids_delete_req.push($(this).data("detalle"));
		}
	});

	if (ids_delete_req.length > 0) {
		swal({
	        title: "¿Estas seguro de eliminar estas solicitudes?",
	        text: "¿Confirmaste que no sean necesarias estas solicitudes que vas a eliminar?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn-danger",
	        cancelButtonText:"No, corregir",
	        confirmButtonText: 'Si, Eliminar',
	        closeOnConfirm: false
	    },
	    function(){
	    	$(".body-loading").show();
	    	$.post(copy_js.base_url+'products/delete_details', { details: ids_delete_req }, function(data, textStatus, xhr) {
	    		location.reload();
	    	});
	    });
	}

	console.log(ids_delete_req);

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

