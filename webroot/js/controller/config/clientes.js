function dataForViewInitial(anio){

    valoresAnio = [...ventasNuevosArr[anio].data];
    totalVenta  = 0;

    for (i in valoresAnio){
        totalVenta+= valoresAnio[i].y;
    }

    var chart = Highcharts.chart('datosAnio', {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Ventas totales clientes nuevos: $'+ totalVenta.toLocaleString() +' COP y total de clientes creados: '+clientesNuevosArr[anio].data.reduce((a, b) => a + b, 0)+', para el año '+anio
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: [{
            categories: labelsMes,
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            },
            title: {
                text: 'Total clientes creados',
                style: {
                    color: Highcharts.getOptions().colors[3]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Ventas clientes nuevos',
                style: {
                    color: Highcharts.getOptions().colors[4]
                }
            },
            labels: {
                format: '${value} COP',
                style: {
                    color: Highcharts.getOptions().colors[4]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 30,
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },
        series: [{
            name: 'Ventas Clientes nuevos',
            type: 'column',
            yAxis: 1,
            data: ventasNuevosArr[anio].data,
            tooltip: {
                valueSuffix: ' $'
            },
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '14px'
                }
            }],
            tooltip: {
                valueSuffix: '',
                pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>Utilidad de venta clientes nuevos: <b>{point.utilidad:,.0f}$</b></td></tr><br>',
            },

        }, {
            name: 'Total clientes nuevos creados',
            type: 'spline',
            data: clientesNuevosArr[anio].data,
            tooltip: {
                valueSuffix: ''
            },
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '14px'
                }
            }],
        }]
    });
    const copyCompare = {...ventasNuevosArr};
    const copyCompareNumber = {...clientesNuevosArr};

    anios.forEach(function (year) {
        var btn = document.getElementById('datos_anio_'+year);
        // Object.freeze(dataDataLayer);
        btn.addEventListener('click', function () {

            document.querySelectorAll('.buttonsDataAnio').forEach(function (active) {
                active.classList.remove('btn-primary');
                active.classList.add('btn-outline-primary');
            });
            btn.classList.add('btn-primary');
            btn.classList.remove('btn-outline-primary');

            
            var mostrarCon        = year == "2022" ? ventasNuevosArrActual : copyCompare[year];
            var mostrarConNumber  = year == "2022" ? clientesNuevosArrActual : copyCompareNumber[year];
            
            totalVenta  = 0;

            for (i in mostrarCon.data){
                totalVenta+= mostrarCon.data[i].y;
            }

            chart.update({
                title: {
                    text: 'Ventas totales clientes nuevos: $'+ totalVenta.toLocaleString() +' COP y total de clientes creados: '+mostrarConNumber.data.reduce((a, b) => a + b, 0)+', para el año '+year
                },
                subtitle: {
                    text: ''
                },
                series: [{
                    // name: 'Vendido en el mes',
                    data: mostrarCon.data
                }, {
                    // name: 'Vendido a clientes nuevos',
                    data: mostrarConNumber.data
                }]
            }, true, false, {
                duration: 800
            });

        });
    });
}


function dataForView(anio){
    const dataDataLayer      = dataCompare[anio];
    const dataDataLayerViejo = dataCompareViejos[anio];
    const dataDataLayer2     = dataCompareNumber[anio];

    var chart = Highcharts.chart('containerMetas', {
        chart: {
            type: 'column',
            margin: 50,
        },
        title: {
            text: 'Dinero recaudado para clientes nuevos del año '+anio+ ", total recaudado: $"+valoresClientesNuevos[anio]+", promedio: %"+promediosByAnioNuevos[anio],
            align: 'center'
        },
        subtitle: {
            text: '',
            align: 'left'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 1
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b> $ {point.y}</b><br/>'
        },
        xAxis: {
            categories: labelsMes
        },
        yAxis: [{
            title: {
                text: 'Ventas totales'
            },
        }],
        series: [{
            color: 'rgb(158, 159, 163)',
            pointPlacement: -0.2,
            linkedTo: 'main',
            data: dataPrev[anio].slice(),
            name: 'Vendido en el mes',
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '16px'
                }
            }],
        }, {
            name: 'Vendido a clientes nuevos',
            id: 'main',
            dataSorting: {
                // enabled: true,
                matchByName: true
            },
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '16px'
                }
            }],
            tooltip: {
                valueSuffix: '',
                pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>% de venta clientes nuevos: <b>{point.cumplimiento}%</b></td></tr>',
            },
            data: dataDataLayer.slice()
        }],
        exporting: {
            allowHTML: true
        }
    });

    var chart3 = Highcharts.chart('containerMetas3', {
        chart: {
            type: 'column',
            margin: 50,
        },
        title: {
            text: 'Dinero recaudado para clientes existentes del año '+anio+ ", total recaudado: $"+valoresClientesViejos[anio]+", promedio: %"+promediosByAnioViejos[anio] ,
            align: 'center'
        },
        subtitle: {
            text: '',
            align: 'left'
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 1
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            shared: true,
            headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b> $ {point.y}</b><br/>'
        },
        xAxis: {
            categories: labelsMes
        },
        yAxis: [{
            title: {
                text: 'Ventas totales'
            },
        }],
        series: [{
            color: 'rgb(158, 159, 163)',
            pointPlacement: -0.2,
            linkedTo: 'main',
            data: dataPrev[anio].slice(),
            name: 'Vendido en el mes',
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '16px'
                }
            }],
        }, {
            name: 'Vendido a clientes existentes',
            id: 'main',
            dataSorting: {
                // enabled: true,
                matchByName: true
            },
            dataLabels: [{
                enabled: true,
                inside: true,
                style: {
                    fontSize: '16px'
                }
            }],
            tooltip: {
                valueSuffix: '',
                pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>% de venta clientes existentes: <b>{point.cumplimiento}%</b></td></tr>',
            },
            data: dataDataLayerViejo.slice()
        }],
        exporting: {
            allowHTML: true
        }
    });

    // var chart2 = Highcharts.chart('containerMetas2', {
    //     chart: {
    //         type: 'column',
    //         margin: 50,
    //     },
    //     title: {
    //         text: 'Número de ventas clientes nuevos del año '+anio,
    //         align: 'center'
    //     },
    //     subtitle: {
    //         text: '',
    //         align: 'left'
    //     },
    //     plotOptions: {
    //         series: {
    //             grouping: false,
    //             borderWidth: 1
    //         }
    //     },
    //     legend: {
    //         enabled: false
    //     },
    //     tooltip: {
    //         shared: true,
    //         headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
    //         pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b> {point.y}</b><br/>'
    //     },
    //     xAxis: {
    //         categories: labelsMes
    //     },
    //     yAxis: [{
    //         title: {
    //             text: 'Número total de ventas'
    //         },
    //     }],
    //     series: [{
    //         color: 'rgb(158, 159, 163)',
    //         pointPlacement: -0.2,
    //         linkedTo: 'main',
    //         data: dataPrevNumber[anio].slice(),
    //         name: 'Número de ventas del mes',
    //         dataLabels: [{
    //             enabled: true,
    //             inside: true,
    //             style: {
    //                 fontSize: '16px'
    //             }
    //         }],
    //     }, {
    //         name: 'Número de ventas a clientes nuevos',
    //         id: 'main',
    //         dataSorting: {
    //             // enabled: true,
    //             matchByName: true
    //         },
    //         dataLabels: [{
    //             enabled: true,
    //             inside: true,
    //             style: {
    //                 fontSize: '16px'
    //             }
    //         }],
    //         tooltip: {
    //             valueSuffix: '',
    //             pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
    //             '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>% N° venta clientes nuevos: <b>{point.cumplimiento}%</b></td></tr>',
    //         },
    //         data: dataDataLayer2.slice()
    //     }],
    //     exporting: {
    //         allowHTML: true
    //     }
    // });



    const copyCompare = {...dataCompare};
    const copyCompareViejos = {...dataCompareViejos};
    const copyCompareNumber = {...dataCompareNumber};

    anios.forEach(function (year) {
        var btn = document.getElementById('compara_ventas_'+year);
        // Object.freeze(dataDataLayer);
        btn.addEventListener('click', function () {

            document.querySelectorAll('.buttonsData').forEach(function (active) {
                active.classList.remove('btn-primary');
                active.classList.add('btn-outline-primary');
            });
            btn.classList.add('btn-primary');
            btn.classList.remove('btn-outline-primary');

            
            var mostrarCon        = year == "2022" ? dataCompareActual.slice() : copyCompare[year].slice();
            var mostrarConViejos  = year == "2022" ? dataCompareActualViejos.slice() : copyCompareViejos[year].slice();
            var mostrarConNumber  = year == "2022" ? dataCompareActualNumber.slice() : copyCompareNumber[year].slice();
            console.log(year)
            console.log(mostrarCon)

            chart.update({
                title: {
                    text: 'Dinero recaudado para clientes nuevos del año '+year+ ", total recaudado: $"+valoresClientesNuevos[year]+", promedio: %"+promediosByAnioNuevos[year]
                },
                subtitle: {
                    text: ''
                },
                series: [{
                    name: 'Vendido en el mes',
                    data: dataPrev[year].slice()
                }, {
                    name: 'Vendido a clientes nuevos',
                    data: mostrarCon
                }]
            }, true, false, {
                duration: 800
            });
            chart3.update({
                title: {
                    text: 'Dinero recaudado para clientes existentes del año '+year+ ", total recaudado: $"+valoresClientesViejos[year]+", promedio: %"+promediosByAnioViejos[year] ,
                },
                subtitle: {
                    text: ''
                },
                series: [{
                    name: 'Vendido en el mes',
                    data: dataPrev[year].slice()
                }, {
                    name: 'Vendido a clientes existentes',
                    data: mostrarConViejos
                }]
            }, true, false, {
                duration: 800
            });

            // chart2.update({
            //     title: {
            //         text: 'Número de ventas clientes nuevos del año '+year
            //     },
            //     subtitle: {
            //         text: ''
            //     },
            //     series: [{
            //         name: 'Número de ventas a clientes nuevos',
            //         data: dataPrevNumber[year].slice()
            //     }, {
            //         name: '% N° ventas clientes nuevos',
            //         data: mostrarConNumber
            //     }]
            // }, true, false, {
            //     duration: 800
            // });
        });
    });
}

dataForView(anioActual)
dataForViewInitial(anioActual)