$(document).ready(function () {
  switch (copy_js.action) {
    case 'verify_payments_payments':
      break;
    case 'report_date_flujos':
      datos_flujos_fecha();
      datos_flujos_origen_fecha();
      data_users_grafica();

      $('#users').multiSelect({
        selectableHeader: "Seleccionar usuarios",
        selectionHeader: "Usuarios seleccionados"
      });
      $('#origin').multiSelect({
        selectableHeader: "Seleccionar orígenes",
        selectionHeader: "Orígenes seleccionados"
      });

      break;
    case 'report_adviser':
    case 'home_adviser':
      datos_empresa_rango_fechas();
      break;
    case 'report_advisers':
      break;
  }
});

$("body").on( "click", "#btn_find_adviser", function() {
	var user_id         = $('#usuario').val();
  var date_inicio     = $('#input_date_inicio_empresa_adviser_report').val();
  var date_fin        = $('#input_date_fin_empresa_adviser_report').val();
  changeUri(date_inicio,date_fin);
  var d               = preload();
  $('.cuadroInformacion').append(d);
	$.post(copy_js.base_url+copy_js.controller+'/report_user',{user_id:user_id,date_inicio:date_inicio,date_fin:date_fin}, function(result){
    $('.resultadoFiltro').empty();
		$('.resultadoFiltro').html(result);
    $('.table_resultados').DataTable({
        "language": {
            "url": "//crm.kebco.co/Spanish.json",
        },
    });
   	$('[data-toggle="tooltip"]').tooltip(); 
	});
});

$("body").on( "click", ".btn_find_data", function() {
	var flujo_id 			= $(this).data('uid');
	var etapa 				= $(this).data('etapa');
	var atendido 			= $(this).data('atendido');
	$.post(copy_js.base_url+copy_js.controller+'/find_data_etapa_flujo',{flujo_id:flujo_id,etapa:etapa,atendido:atendido}, function(result){
		$('#modal_big_information_body').html(result);
		$('#modal_big_information_label').text('Datos de la atención en la etapa por parte del asesor');
		$('#modal_big_information').modal('show');
	});
});

$('#btn_buscar_datos_empresa_home_report').click(function(){
  datos_empresa_rango_fechas();
});

function datos_empresa_rango_fechas(){
  $('.div_info_empresa').empty();
  var date_inicio     = $('#input_date_inicio_empresa_adviser_report').val();
  var date_fin        = $('#input_date_fin_empresa_adviser_report').val();
  changeUri(date_inicio,date_fin);
  var fechaActual     = dateDay();
  if (new Date(date_inicio).getTime() > new Date(fechaActual).getTime()) {
    message_alert("Por favor valida, La fecha fin es mayor a la actual","error");
  } else {
    if (new Date(date_inicio).getTime() > new Date(date_fin).getTime()) {
        message_alert("Por favor valida, La fecha inicio es mayor a la fecha fin","error");
    } else {
      var d             = preload();
      $('.div_info_empresa').append(d);
      $.post(copy_js.base_url+'prospective_users/data_date_company',{date_inicio:date_inicio,date_fin:date_fin}, function(result){
        $('.div_info_empresa').html(result);
        setTimeout(function() {
          $('.table_resultados').DataTable({
              "language": {
                  "url": "//crm.kebco.co/Spanish.json",
              },
          });
        }, 1000);
      });
    }
  }
}

$("body").on('click', '.findInformationFlujos', function(event) {
  event.preventDefault();
  var type            = $(this).data("cuadro");
  var date_inicio     = $('#input_date_inicio_empresa_adviser_report').val();
  var date_fin        = $('#input_date_fin_empresa_adviser_report').val();

  $.post(copy_js.base_url+'prospective_users/get_flows',{date_inicio,date_fin,type}, function(result){
    
    $("#bodyFlujosEstado").html(result)
    $("#modalFlujosEstado").modal("show");

  });

});

$("body").on( "click", ".findInformationFlujos22", function() {
  var data_cuadro     = $(this).data('cuadro');
  var date_inicio     = $('#input_date_inicio_empresa').val();
  var date_fin        = $('#input_date_fin_empresa').val();
  switch (data_cuadro) {
    case 1:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_asignados&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 2:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_proceso&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 3:
      var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_cotizados&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 4:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_cancelados&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 5:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_completados&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 6:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_totales&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 7:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_demorados&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;
    case 8:
        var urlData = copy_js.base_url+copy_js.controller+'/report_adviser?find=flujos_no_validos&date_ini='+date_inicio+'&date_fin='+date_fin+'&ini='+date_inicio+'&end='+date_fin;
      break;

  }
  location.href = urlData;
});

$('#btn_buscar').click(function(){
	datos_flujos_fecha();
  datos_flujos_origen_fecha();
});

function datos_flujos_fecha(){
  var date_inicio     = $('#input_date_inicio').val();
  var date_fin        = $('#input_date_fin').val();
  var users           = $("#users").val();
  var origin          = $("#origin").val();
  var d               = preload();
  $('.resultadoConsulta').append(d);
  changeUri(date_inicio,date_fin);
  $.post(copy_js.base_url+copy_js.controller+'/report_date_flujos_data',{date_inicio,date_fin,users,origin}, function(result){
    $('.resultadoConsulta').empty();
    $('.resultadoConsulta').html(result);
    $('.table_resultados').DataTable({
        'iDisplayLength': 10,
        "language": {
            "url": copy_js.base_url+"Spanish.json",
        },
    });
    $('[data-toggle="tooltip"]').tooltip(); 
  });
}

function datos_flujos_origen_fecha(){
  var date_inicio     = $('#input_date_inicio').val();
  var date_fin        = $('#input_date_fin').val();
  var users           = $("#users").val();
  var origin          = $("#origin").val();
  var d               = preload();
  $('.resultadoConsultaNumero').append(d);
  $.post(copy_js.base_url+copy_js.controller+'/report_date_advisers_flujos_data',{date_inicio,date_fin,users,origin}, function(result){
    $('.resultadoConsultaNumero').empty();
    $('.resultadoConsultaNumero').html(result);
    $('[data-toggle="tooltip"]').tooltip();
    calcular_total_chat_vertical();
    calcular_total_st_vertical();
    calcular_total_whatsapp_vertical();
    calcular_total_email_vertical();
    calcular_total_llamada_vertical();
    calcular_total_presencial_vertical();
    calcular_total_redes_vertical();
    calcular_total_referido_vertical();
    calcular_total_chat_pelican_vertical();
    calcular_total_chat_usa_vertical();
    calcular_total_wpp_usa_vertical();
    calcular_total_email_usa_vertical();
    calcular_total_tienda_vertical();
    calcular_total_landing();
    calcular_total_marketing();
    calcular_total_horizontal_usuario(date_inicio,date_fin);
  });
}

function calcular_total_chat_pelican_vertical(){
    var resultado = 0;
    $("td.pelicanSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.chatpelicanTotal').text(resultado);
}

function calcular_total_tienda_vertical(){
    var resultado = 0;
    $("td.tiendaSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.tiendaTotal').text(resultado);
}

function calcular_total_landing(){
    var resultado = 0;
    $("td.landingSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.landingTotal').text(resultado);
}

function calcular_total_marketing(){
    var resultado = 0;
    $("td.marketingSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.marketingTotal').text(resultado);
}

function calcular_total_chat_usa_vertical(){
    var resultado = 0;
    $("td.chatUsaSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.chatUsaTotal').text(resultado);
}

function calcular_total_wpp_usa_vertical(){
    var resultado = 0;
    $("td.wppUsaSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.wppUsaTotal').text(resultado);
}

function calcular_total_email_usa_vertical(){
    var resultado = 0;
    $("td.emailUsaSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.emailUsaTotal').text(resultado);
}

function calcular_total_chat_vertical(){
    var resultado = 0;
    $("td.chatSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.chatTotal').text(resultado);
}

function calcular_total_st_vertical(){
    var resultado = 0;
    $("td.stSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.stTotal').text(resultado);
}

function calcular_total_whatsapp_vertical(){
    var resultado = 0;
    $("td.whatsappSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.whatsappTotal').text(resultado);
}

function calcular_total_email_vertical(){
    var resultado = 0;
    $("td.emailSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.emailTotal').text(resultado);
}

function calcular_total_llamada_vertical(){
    var resultado = 0;
    $("td.llamadaSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.llamadaTotal').text(resultado);
}

function calcular_total_presencial_vertical(){
    var resultado = 0;
    $("td.presencialSuma").each(function(){
        resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.presencialTotal').text(resultado);
}

function calcular_total_redes_vertical(){
    var resultado = 0;
    $("td.redesSuma").each(function(){
      resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.redesTotal').text(resultado);
}

function calcular_total_referido_vertical(){
    var resultado = 0;
    $("td.referidosSuma").each(function(){
      resultado = format_number($(this).text(),resultado);
    });
    resultado = number_format(resultado);
    $('td.referidosTotal').text(resultado);
}

function calcular_total_horizontal(id){
  var resultado           = 0;
  var resultadoFinal      = 0;
  $("td.horizontal_"+id).each(function(){
      resultado = format_number($(this).text(),resultado);
  });
  resultado = number_format(resultado);
  $('td.usuarioTotal_'+id).text(resultado);
  var resultadoFinal = parseInt(resultadoFinal) + parseInt(resultado);
  return resultadoFinal;
}

function  calcular_total_horizontal_usuario(date_inicio,date_fin){
  var resultado     = 0;
  $.post(copy_js.base_url+copy_js.controller+'/report_date_advisers_flujos_count',{date_inicio:date_inicio,date_fin:date_fin}, function(result){
    for (var i = 1; i <= result; i++) {
      resultado += calcular_total_horizontal(i);
    }
    calcular_total_flujos(resultado);
  })
;}

function calcular_total_flujos(result){
  $('td.totalTotal').text(result);
}


// Exportar tabla
  $("body").on( "click", ".btnExportar", function() {
    var date_inicio     = $('#input_date_inicio').val();
    var date_fin        = $('#input_date_fin').val();
    var reporte_flujo   = $('#flujo_reporte').val();
    var d             = preload();
    $('.preloadExportar').append(d);
    $.post(copy_js.base_url+copy_js.controller+'/export_file_data_report_flujos',{date_inicio:date_inicio,date_fin:date_fin,reporte_flujo:reporte_flujo}, function(result){
      $('.preloadExportar').empty();
      $('#modal_information_body').html(result);
      $('#modal_information').modal('show');
    })
  });

  $("body").on( "click", ".botonExcel", function() {
    $("#datos_a_enviar").val( $("<div>").append( $("#exportar_archivo").eq(0).clone()).html());
    $('#tipo_exportacion').val('1');
    $("#FormularioExportacion").submit();
  });
//


function data_users_grafica(){
  console.log("No se ejecuta")
  return false;
  $.post(copy_js.base_url+'users/usersAdviserStateTrueAll',{}, function(result){
    var usuarios = JSON.parse(result);
    for (i = 0; i < usuarios.length; i++) {
      var POSITION            = i;
      $.post(copy_js.base_url+copy_js.controller+'/consultProspectosWeekUser',{POSITION:POSITION,user_id:usuarios[i]['User']['id']}, function(result){
        var datos             = JSON.parse(result);
        var postionArray      = datos[5];
        var arrayFinal        = datos.splice(0,5);
        var ctx               = document.getElementById('myChart'+postionArray);
        var chart             = new Chart(ctx, {
          type: 'line',
          data: {
              labels: ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"],
              datasets: [{
                  label: usuarios[postionArray]['User']['name'],
                  backgroundColor: 'rgba(255, 99, 132, 0.01)',
                  borderColor: 'rgb(206, 33, 129)',
                  data: arrayFinal,
              }]
          },
          options: {}
        });
      });
    }
  })
}

if (copy_js.action == 'report_date_flujos') {
  $('.dataslick').slick({
    dots: true,
    infinite: false,
    speed: 300,
    slidesToShow: 3,
    slidesToScroll: 3,
    responsive: [
      {
        breakpoint: 1300,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 999,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
}