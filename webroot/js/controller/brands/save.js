$('body').on("keyup", "#BrandMinPriceImporter", function(event) {
  var precio              = $('#BrandMinPriceImporter').val();
  var precio_final        = number_format(precio);
  $('#BrandMinPriceImporter').val(precio_final);
});

// $("#BrandDni,#BrandPhone").change(function(event) {
// 	return validarSiNumero($(this).val());
// });

$("#BrandDni,#BrandPhone").keypress(function(event) {
	var key = event.which ? event.which : event.keyCode;
	if (key < 48 || key > 57) {
        event.preventDefault();
    }
    var texto = $(this).val();
    setTimeout(function() {
    	if(!validarSiNumero(texto)){
			event.preventDefault();
		}
    }, 1000);
});

function validarSiNumero(numero){
	if (!/^([0-9])*$/.test(numero)){
		return false;
	}else{
		return true;
	}
}

if ($("#BrandAddForm").length) {

	document.getElementById("BrandAddForm").onkeypress = function(e) {

	  var key = e.charCode || e.keyCode || 0;     
	  if (key == 13) {
	    e.preventDefault();
	  }
	} 
}

if ($("#BrandEditForm").length) {
	
  document.getElementById("BrandEditForm").onkeypress = function(e) {
    var key = e.charCode || e.keyCode || 0;     
    if (key == 13) {
      e.preventDefault();
    }
  } 
}

$('#BrandCopyEmails').tagsinput();

