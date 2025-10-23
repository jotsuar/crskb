$(document).ready(function () {
    data_date($('#input_date_inicio').val(),$('#input_date_fin').val());
});

$('#btn_find_adviser').click(function(){
    $('.div_resultado').empty();
    data_date($('#input_date_inicio').val(),$('#input_date_fin').val());
});

function data_date(fecha_ini,fecha_fin){
	var fechaActual 		= dateDay();
	if (new Date(fecha_fin).getTime() > new Date(fechaActual).getTime()) {
    message_alert("Por favor valida, La fecha fin es mayor a la actual","error");
  } else {
    if (new Date(fecha_fin).getTime() < new Date(fecha_ini).getTime()) {
        message_alert("Por favor valida, La fecha inicio es mayor a la fecha final","error");
    } else {
  		var d             = preload();
  		$('.div_resultado').append(d);
      changeUri(fecha_ini,fecha_fin);
  		$.post(copy_js.base_url+copy_js.controller+'/report_customer_new_data',{fecha_ini:fecha_ini,fecha_fin:fecha_fin}, function(result){
  			$('.div_resultado').empty();
  			$('.div_resultado').html(result);
  		});
    }
	}
}

$("body").on( "click", ".findInformationFlujos", function() {
  var data_cuadro     = $(this).data('cuadro');
  var date_inicio     = $('#input_date_inicio').val();
  var date_fin        = $('#input_date_fin').val();
  switch (data_cuadro) {
    case 1:
        var url = copy_js.base_url+copy_js.controller+'/report_customer_new?find=flujos_totales&fecha_ini='+date_inicio+'&fecha_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 2:
        var url = copy_js.base_url+copy_js.controller+'/report_customer_new?find=flujos_vendidos&fecha_ini='+date_inicio+'&fecha_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;;
      break;
    case 3:
    	var url = copy_js.base_url+copy_js.controller+'/report_customer_new?find=flujos_vendidos&fecha_ini='+date_inicio+'&fecha_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;;
      break;
  }
  location.href = url;
});