$('#users').multiSelect({
	selectableHeader: "Seleccionar usuarios",
	selectionHeader: "Usuarios seleccionados"
});
$('#origin').multiSelect({
selectableHeader: "Seleccionar orígenes",
selectionHeader: "Orígenes seleccionados"
});
$("#btn_buscar").click(function(event) {
	changeUri($("#input_date_inicio").val(),$("#input_date_fin").val());
	var actual_query        =  URLToArray(actual_uri);

	var users           = $("#users").val();
  	var origin          = $("#origin").val();

	actual_query["ini"] = $("#input_date_inicio").val();
	actual_query["end"] = $("#input_date_fin").val();
	actual_query["users"] = users;
	actual_query["origin"] = origin;
	location.href = actual_url+$.param(actual_query);
});

$("#detalleVentasBtn").click(function(event) {
	$("#detalleVentas").toggle();
});


$("#showHideFilter").click(function(event) {
	event.preventDefault();
	if ($(".filtersFlujo").hasClass('d-none')) {
		$(".filtersFlujo").removeClass('d-none');
		$(this).children("span#ver").hide();
		$(this).children("span#noVer").show();
	}else{
		$(".filtersFlujo").addClass('d-none');
		$(this).children("span#ver").show();
		$(this).children("span#noVer").hide();
	}
});

Highcharts.chart('contentFunnel', {
    chart: {
        type: 'funnel'
    },
    title: {
        text: 'Funnel de flujos generados (estado actual)'
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y:,.0f})',
                softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '30%',
            neckHeight: '25%',
            width: '80%'
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Flujos actuales',
        data: funnelData
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                plotOptions: {
                    series: {
                        dataLabels: {
                            inside: true
                        },
                        center: ['50%', '50%'],
                        width: '100%'
                    }
                }
            }
        }]
    }
});

Highcharts.chart('contentMedios', {

   chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie',
            },
            title: {
                text: 'Origen del prospecto ('+totalProspectos+')'
            },
            // tooltip: {
            //     pointFormat: '<b>Cotizaciones</b>:{point.cotizados}<br><b>Ventas</b>:{point.ventas}<br><b>{series.name}</b>: {point.efectividad}%'
            // },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
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
                name: 'Total',
                colorByPoint: true,
                data: dataOgigin,
                 showInLegend: true
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

Highcharts.chart('contentClientes', {

   chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Tipos de clientes'
            },
            // tooltip: {
            //     pointFormat: '<b>Cotizaciones</b>:{point.cotizados}<br><b>Ventas</b>:{point.ventas}<br><b>{series.name}</b>: {point.efectividad}%'
            // },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
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
                name: 'Total',
                colorByPoint: true,
                data: dataClientes,
                 showInLegend: true
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

Highcharts.chart('contentAsignacion', {

   chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true,
                type: 'pie'
            },
            title: {
                text: 'Asignación de flujos por asesor'
            },
            // tooltip: {
            //     pointFormat: '<b>Cotizaciones</b>:{point.cotizados}<br><b>Ventas</b>:{point.ventas}<br><b>{series.name}</b>: {point.efectividad}%'
            // },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
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
                name: 'Total',
                colorByPoint: true,
                data: flujosAsesor,
                 showInLegend: true
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