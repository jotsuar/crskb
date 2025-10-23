$(document).ready(function () {
  var fecha_inicio    = $('#input_date_inicio').val();
  var fecha_fin       = $('#input_date_fin').val();
  var usuario         = $('#usuario').val();
  var fecha_inicio_com      = $('#input_date_inicio2').val();
  var fecha_fin_com         = $('#input_date_fin2').val();


  if (usuario != "") {
    find_date_data(fecha_inicio,fecha_fin,usuario,fecha_inicio_com,fecha_fin_com);
  }
  var multipleCancelButton = new Choices('#usuario', {
    removeItemButton: true,
    itemSelectText: "Seleccione asesor",
    searchEnabled: true,
    placeholder: true,
    placeholderValue: 'Busque y/o seleccione uno o más usuarios',
    noChoicesText: 'No hay más usuarios para mostrar'
  }); 
});

$("#btn_find_adviser_443").click(function() {
  var fecha_inicio          = $('#input_date_inicio').val();
  var fecha_fin             = $('#input_date_fin').val();
  var fecha_inicio_com      = $('#input_date_inicio2').val();
  var fecha_fin_com         = $('#input_date_fin2').val();
  var usuario               = $('#usuario').val();
  find_date_data(fecha_inicio,fecha_fin,usuario,fecha_inicio_com,fecha_fin_com);
  // grafica(fecha_inicio,fecha_fin,usuario);
});

$("#btn_send_adviser_informe").click(function() {
  var fecha_inicio    = $('#input_date_inicio').val();
  var fecha_fin       = $('#input_date_fin').val();
  changeUri(fecha_inicio,fecha_fin);
  $.post(copy_js.base_url+'ProspectiveUsers/data_send_informe_adviser',{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin}, function(result){
    $('#modal_small_body').html(result);
    $('#modal_small_label').text('Enviar informe');
    $('#btn_guardar_modal_cliente').text('Enviar');
    $('#modal_small').modal('show');
  });
});

$("#btn_guardar_modal_cliente").click(function() {
  var fecha_inicio    = $('#input_date_inicio').val();
  var fecha_fin       = $('#input_date_fin').val();
  var user_id         = $('#usuario').val();
  var observation     = $('#observation').val();
  $.post(copy_js.base_url+'ProspectiveUsers/sendInformeAdviser',{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,user_id:user_id,observation:observation}, function(result){
    location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
  });
});


function find_date_data(fecha_inicio,fecha_fin,user_id,fecha_inicio_com,fecha_fin_com){
  var d               = preload();

  if (user_id == "") {
    return false;
  }

  $('.resultInformation').append(d);
  changeUri(fecha_inicio,fecha_fin);
  
  $.post(copy_js.base_url+'ProspectiveUsers/consult_information_user_flujos',{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,user_id:user_id,fecha_inicio_com,fecha_fin_com}, function(result){
    $('.resultInformation').html(result);

    var getTotalCotizados = $("#cotizadosNum").html();
    var promedio          = $("#cotizadosNum").attr("data-promedio");
    var days              = $("#cotizadosNum").attr("data-days");

    setTimeout(function() {

        var myDateFormat = '%y/%m/%e/';

        Highcharts.chart('graficoCountVentas', {
            data: {
                table: 'countData'
            },
            chart: {
                type: 'line',
                backgroundColor: '#f7fcff',
            },
            title: {
                text: 'Número de cotizaciones realizadas al día por el/los asesor(es), total realizadas: '+getTotalCotizados+ " con un promedio de: "+promedio + " por día, teniendo "+days+ " días para cotizar"
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Total'
                },
            },
            plotOptions: {
                line: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
        });
    }, 500);

  });
}

function grafica(fecha_inicio,fecha_fin,user_id){
  $('#container2').empty();
  var fecha1                  = moment(fecha_inicio);
  var fecha2                  = moment(fecha_fin);
  var dias                    = fecha2.diff(fecha1, 'days');
  var diasTranscurridos       = parseInt(dias) + parseInt(1);
  var arrayDias               = [];
  var prospectosArrayDays     = [];
  var contactadosArrayDays    = [];
  var ventasArrayDays         = [];

  var d                       = preload();
  $('#container2').append(d);

  $.post(copy_js.base_url+'ProspectiveUsers/dateDaysGraficaRangeFechas',{fecha_inicio:fecha_inicio,dias:dias}, function(result){
    arrayDias = JSON.parse(result);
  });

  $.post(copy_js.base_url+'ProspectiveUsers/consultProspectosContactadosDaysUser',{fecha_inicio:fecha_inicio,
    fecha_fin:fecha_fin,user_id:user_id,dias:diasTranscurridos,state:copy_js.nombre_flujo_asignado}, function(result){
    prospectosArrayDays = JSON.parse(result);
  });

  $.post(copy_js.base_url+'ProspectiveUsers/consultProspectosContactadosDaysUser',{fecha_inicio:fecha_inicio,
    fecha_fin:fecha_fin,user_id:user_id,dias:diasTranscurridos,state:copy_js.nombre_flujo_contactado}, function(result){
    contactadosArrayDays = JSON.parse(result);
  });
  
  $.post(copy_js.base_url+'ProspectiveUsers/consultNumberVentasDayUser',{fecha_inicio:fecha_inicio,
    fecha_fin:fecha_fin,user_id:user_id,dias:diasTranscurridos}, function(result){
    ventasArrayDays = JSON.parse(result);
  });

  dataChartProsp = [];
  dataChartCont = [];
  dataChartVent = [];

    
  setTimeout(function(){
   for (var i = 0; i < arrayDias.length; i++) {
        var fecha = arrayDias[i].split("-");
        console.log(fecha)
        dataChartProsp.push([Date.UTC(fecha[0],fecha[1],fecha[2]),prospectosArrayDays[i]]);
        dataChartCont.push([Date.UTC(fecha[0],fecha[1],fecha[2]),contactadosArrayDays[i]]);
        dataChartVent.push([Date.UTC(fecha[0],fecha[1],fecha[2]),ventasArrayDays[i]]);
      }
      console.log(dataChartProsp)
      console.log(dataChartCont)
      console.log(dataChartVent)
    $('#container2').empty();
    Highcharts.chart('container2', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Control de ventas y prospectos '
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Fecha'
            }
        },
        yAxis: {
            title: {
                text: 'Número de prospectos'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e. %b}: {point.y} '
        },

        plotOptions: {
            series: {
                marker: {
                    enabled: true
                }
            }
        },

        colors: ['#FF5733', '#33FF6E', '#06C'],
        credits: {
            enabled: false
        },
        exporting: {
            buttons: {
                contextButton: {
                    menuItems: ['downloadPNG','downloadPDF','downloadXLS']
                }
            }
        },

        series: [{
            name: "Prospectos Asignados",
            data: dataChartProsp
        }, {
            name: "Prospectos Contactados",
            data: dataChartCont
        }, {
            name: "Ventas",
            data: dataChartVent
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    plotOptions: {
                        series: {
                            marker: {
                                radius: 2.5
                            }
                        }
                    }
                }
            }]
        }
    });

  }, 10000);

  
}