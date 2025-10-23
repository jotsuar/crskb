var USUARIOS                = [];
var ARRAYDIAS               = [];
var PROSPECTOSARRAYDAYS     = [];
var CONTACTADOSARRAYSDAYS   = [];
var VENTASARRAYDAYS         = [];

$(document).ready(function () {
    var d                       = preload();
    $('#efectivity').append(d);
    $('#ventas').append(d);
    $('#prospectos').append(d);
    $('#demoras').append(d);
    data_users_grafica($('#input_date_inicio').val(),$('#input_date_fin').val());
   
    //setTimeout(function(){ data_users_grafica1($('#input_date_inicio').val(),$('#input_date_fin').val()); }, 6000);
});

function data_users_grafica(fecha_ini,fecha_fin){
    var fechaActual         = dateDay();
    if (new Date(fecha_fin).getTime() > new Date(fechaActual).getTime()) {
        message_alert("Por favor valida, La fecha fin es mayor a la actual","error");
    } else {
        if (new Date(fecha_fin).getTime() < new Date(fecha_ini).getTime()) {
            message_alert("Por favor valida, La fecha inicio es mayor a la fecha fin","error");
        } else {
            changeUri(fecha_ini,fecha_fin);
            printEfectivityData(fecha_ini,fecha_fin);
            printVentas(fecha_ini,fecha_fin);
            printProspectos(fecha_ini,fecha_fin);
            printDemoras(fecha_ini,fecha_fin);
            printCotizaciones(fecha_ini,fecha_fin);
        }
    }
}

function printEfectivityData($fecha_ini,$fecha_end){
    $('#efectivity').empty();
    var d                       = preload();
    $('#efectivity').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoEfectivity', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
        $('#efectivity').empty();
        Highcharts.chart('efectivity', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Efectividad en base a '+data.total+' venta(s)'
            },
            tooltip: {
                pointFormat: '<b>Cotizaciones</b>:{point.cotizados}<br><b>Ventas</b>:{point.ventas}<br><b>{series.name}</b>: {point.efectividad}%'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
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
                name: 'Efectividad',
                colorByPoint: true,
                data: data.datos
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
    },"json");
}

function printVentas($fecha_ini,$fecha_end){
    $('#ventas').empty();
    var d                       = preload();
    $('#ventas').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoVentas', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
        $('#ventas').empty();
        Highcharts.chart('ventas', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Reporte en base a ventas totales ($'+number_format(data.total)+')'
            },
            tooltip: {
                pointFormat: '<b>Ventas</b>: ${point.y}'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
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
                name: 'Ventas',
                colorByPoint: true,
                data: data.datos
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
    },"json");
}

function printProspectos($fecha_ini,$fecha_end){
    $('#prospectos').empty();
    var d                       = preload();
    $('#prospectos').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoProspect', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
        $('#prospectos').empty();
        Highcharts.chart('prospectos', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Reporte de asignación de prospectos base a '+data.total+' asignados'
            },
            tooltip: {
                pointFormat: '<b>Asignados</b>: {point.y} <br> <b>Contactados</b>: {point.contactados}'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
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
                name: 'Asignados',
                colorByPoint: true,
                data: data.datos
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
    },"json");
}

function printCotizaciones($fecha_ini,$fecha_end){
    $('#cotizaciones').empty();
    var d                       = preload();
    $('#cotizaciones').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoQuotation', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
        console.log(data)
        $('#cotizaciones').empty();
        $('#cotizaciones').highcharts({
            chart: {
                type: 'column'
            },
             title: {
                text: 'Cotizaciones realizadas: '+data.total
            },
            subtitle: {
                text: 'Cotizaciones realizadas por asesor en un rango de fechas, por dia y cliente (Solo se muestra 1 al día)'
            },
            
            xAxis: {
                type: 'category'
            },
            yAxis: {
              title: {
                text: 'Total de cotizaciones'
              }
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            var total =   this.series.yData.reduce((a, b) => a + b, 0);
                            if(this.y == 0){
                                var percentage = 0;
                            }else{
                                var percentage = (this.y / total) * 100
                            }
                            return percentage.toFixed(2)+'</b>%';
                        },
                    }
                }
            },
            tooltip: {
                formatter: function() {
                      return ' <span style="font-size:11px">'+this.series.name+'</span><br><span style="color:'+this.point.color+'">'+this.point.name+': </span> <b>'+this.y+'</b> cotizaciones.<br/>';
                   },
            },
             
            legend: {
              enabled: false
            },
            series: [{
                name: "Cotizaciones",
                colorByPoint: true,
                data: data.series
            }],
            drilldown: {
                series: data.drilldown
            }
        });
    },"json");
}

function printDemoras($fecha_ini,$fecha_end){
    $('#demoras').empty();
    var d                       = preload();
    $('#demoras').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoDemoras', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
        $('#demoras').empty();
        console.log(data)
        Highcharts.chart('demoras', {
            chart: {
                type: 'column',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
            },
            title: {
                text: 'Reporte de demoras en base a '+data.total+ ' horas no contactadas'
            },
            xAxis: {
                categories: data.categories
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total horas sin contactar'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: ( // theme
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total horas demora: {point.stackTotal}'
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ['downloadPNG','downloadPDF','downloadXLS']
                    }
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: data.series,
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
    },"json");
}

$('#btn_find_adviser').click(function(){
    
    data_users_grafica($('#input_date_inicio').val(),$('#input_date_fin').val());
});