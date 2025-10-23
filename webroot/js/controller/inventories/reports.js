$(document).ready(function () {
    var d                       = preload();
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
            printData(fecha_ini,fecha_fin);
        }
    }
}

function printData($fecha_ini,$fecha_end){
	
    $.post(copy_js.base_url+'Inventories/inventory_report', {fecha_ini:$fecha_ini,fecha_end:$fecha_end}, function(data, textStatus, xhr) {
    	printDataGeneral(data)
        printTiposEntrada(data)
        printTiposEntradaPorBodega(data)
        printTiposSalida(data)
        printTiposSalidaPorBodega(data)
        printTable($fecha_ini,$fecha_end)
    },"json");
}

function printDataGeneral(datos){
    var d = preload();
    $('div#general').empty();
    $('div#general').append(d);
    $('#general').empty();

    var total = 0;

    for (let i in datos.general){
        total+= datos.general[i].y;
    }

    Highcharts.chart('general', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Movimientos de inventario: '+total
        },
        tooltip: {
            pointFormat: '<b>Movimientos</b>: {point.y}'
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
            name: 'Movimientos',
            colorByPoint: true,
            data: datos.general
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
}

function printTiposEntrada(datos){
    var d = preload();
    $('div#tipos_entrada').empty();
    $('div#tipos_entrada').append(d);
    $('#tipos_entrada').empty();

    var total = 0;

    for (let i in datos.tipos_entrada){
        total+= datos.tipos_entrada[i].y;
    }

    Highcharts.chart('tipos_entrada', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Movimientos de inventario para entrada: '+total
        },
        tooltip: {
            pointFormat: '<b>Movimientos</b>: {point.y}'
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
            name: 'Movimientos',
            colorByPoint: true,
            data: datos.tipos_entrada
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
}

function printTiposEntradaPorBodega(datos){
    var d = preload();
    $('div#bodegas_entrada').empty();
    $('div#bodegas_entrada').append(d);
    $('#bodegas_entrada').empty();

    var total = 0;

    for (let i in datos.bodegas_entrada){
        total+= datos.bodegas_entrada[i].y;
    }

    Highcharts.chart('bodegas_entrada', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Movimientos de inventario tipo entrada por bodega: '+total
        },
        tooltip: {
            pointFormat: '<b>Movimientos</b>: {point.y}'
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
            name: 'Movimientos',
            colorByPoint: true,
            data: datos.bodegas_entrada
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
}

function printTiposSalidaPorBodega(datos){
    var d = preload();
    $('div#bodegas_salida').empty();
    $('div#bodegas_salida').append(d);
    $('#bodegas_salida').empty();

    Highcharts.chart('bodegas_salida', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Movimientos de inventario tipo salida por bodega:'
        },
        tooltip: {
            pointFormat: '<b>Movimientos</b>: {point.y}'
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
            name: 'Movimientos',
            colorByPoint: true,
            data: datos.bodegas_salida
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
}

function printTiposSalida(datos){
    var d = preload();
    $('div#tipos_salida').empty();
    $('div#tipos_salida').append(d);
    $('#tipos_salida').empty();

    Highcharts.chart('tipos_salida', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie'
        },
        title: {
            text: 'Movimientos de inventario para salida:'
        },
        tooltip: {
            pointFormat: '<b>Movimientos</b>: {point.y}'
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
            name: 'Movimientos',
            colorByPoint: true,
            data: datos.tipos_salida
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
}

function printTable($fecha_ini,$fecha_end){
	$('div#tableData').empty();
    var d = preload();
    $('div#tableData').append(d);
    $.post(copy_js.base_url+'Inventories/inventory_report', {fecha_ini:$fecha_ini,fecha_end:$fecha_end,product: true}, function(data, textStatus, xhr) {
       	$('#tableData').empty();
    	var DATOS = data;
		$('#example').DataTable( {
		    data: DATOS,
            paging: false,
		    columns: [
		        { data: "número_de_parte" },
		        { data: "nombre" },
		        { data: "total_vendido" },
		    ]
		} );
    },"json");
}

$('#btn_find_adviser').click(function(){
    
    $('#example').DataTable().destroy();
    data_users_grafica($('#input_date_inicio').val(),$('#input_date_fin').val());
});