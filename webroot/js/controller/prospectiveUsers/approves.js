$(document).ready(function () {
    var d                       = preload();
    $('div#solicitudes').append(d);
    $('#aprobadas').append(d);
    $('#rechazadas').append(d);
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
            printSolicitudes(fecha_ini,fecha_fin);
            printAprobadas(fecha_ini,fecha_fin);
            printRechazadas(fecha_ini,fecha_fin);
            prntDataTable(fecha_ini,fecha_fin);
        }
    }
}

function printSolicitudes($fecha_ini,$fecha_end){
	$('div#solicitudes').empty();
    var d = preload();
    $('div#solicitudes').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoAproves', {fecha_ini:$fecha_ini,fecha_end:$fecha_end,state:[1,2,3]}, function(data, textStatus, xhr) {
    	$('#solicitudes').empty();
        Highcharts.chart('solicitudes', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Total de solicitudes de aprobación: '+data.total
            },
            tooltip: {
                pointFormat: '<b>Solicitudes de aprobación</b>: {point.y}'
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

function printAprobadas($fecha_ini,$fecha_end){
	$('div#aprobadas').empty();
    var d = preload();
    $('div#aprobadas').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoAproves', {fecha_ini:$fecha_ini,fecha_end:$fecha_end,state:[1]}, function(data, textStatus, xhr) {
    	$('#aprobadas').empty();
        Highcharts.chart('aprobadas', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Solicitudes aprobadas '+data.total
            },
            tooltip: {
                pointFormat: '<b>Solicitudes aprobadas</b>: {point.y}'
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

function printRechazadas($fecha_ini,$fecha_end){
	$('div#rechazadas').empty();
    var d = preload();
    $('div#rechazadas').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoAproves', {fecha_ini:$fecha_ini,fecha_end:$fecha_end,state:[2]}, function(data, textStatus, xhr) {
    	$('#rechazadas').empty();
        Highcharts.chart('rechazadas', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Total rechazos '+data.total
            },
            tooltip: {
                pointFormat: '<b>Solicitudes rechazadas</b>: {point.y}'
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

function prntDataTable($fecha_ini,$fecha_end){
	$('div#tableData').empty();
    var d = preload();
    $('div#tableData').append(d);
    $.post(copy_js.base_url+'ProspectiveUsers/getInfoAproves', {fecha_ini:$fecha_ini,fecha_end:$fecha_end,state:[0,1,2],qt: true}, function(data, textStatus, xhr) {
    	$('#tableData').empty();
    	var DATOS = data;
		$('#example').DataTable( {
		    data: DATOS,
		    columns: [
		        { data: "nro" },
		        { data: "nombre"},
		        { data: "valor"},
		        { data: "código" },             
		        { data: "flujo" },             
		        { data: "cliente" },             
		        { data: "vendedor" },             
		        { data: "fecha_envio" },             
		        { data: "estado_cotización" },             
		    ]
		} );
    },"json");
}

$('#btn_find_adviser').click(function(){
    
    $('#example').DataTable().destroy();
    data_users_grafica($('#input_date_inicio').val(),$('#input_date_fin').val());
});