const formatter = new Intl.NumberFormat('en-US', {
  // style: 'currency',
  // currency: 'USD',
  minimumFractionDigits: 2,
  maximumFractionDigits: 2,
})

const formatterSimulador = new Intl.NumberFormat('en-US', {
  // style: 'currency',
  // currency: 'USD',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
})
$(document).ready(function() {

			function showNewVal(){
				setTimeout(function() {
					var total = 0;
					$(".valorData").each(function(index, el) {
						total += parseFloat($(this).data("value"));
					});
					$(".deudaGeneral").html("$ "+number_format(total))
				}, 1000);
			}

			$("body").on('click', '.btnEmp', function(event) {
				event.preventDefault();
				var dataName = $(this).data("name");
				if (dataName == "0") {
					$(".select_0").val("");
				}else{
					$(".select_0").val(dataName);
				}
				$(".select_0").trigger('change');
			});

			setDataFlowsCS();

			$('#debtsData thead tr')
		        .clone(true)
		        .addClass('filters')
		        .appendTo('#debtsData thead');

		    

		    $('#debtsData').DataTable( {
		    	'iDisplayLength': 5,
		    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
		    	"ordering": false,
		    	paging: false,
		    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
		        initComplete: function () {
		            this.api().columns().every( function () {
		                var column = this;
		                if (column[0] != 4 && column[0] != 5 && column[0] != 6) {

		                	var select = $('<select class="buscaSelect select_'+column[0]+'"><option value="">Seleccionar</option></select>')
			                    .appendTo( $( column[0] == 0 ? ".empleadoBtnDat" : column.header()).empty() )
			                    .on( 'change', function () {
			                        var val = $.fn.dataTable.util.escapeRegex(
			                            $(this).val()
			                        );
			                        if (column[0] == 0) {
			                        	if(val.length == 0){
			                        		$(".empleado").show();
			                        		setTimeout(function() {
			                        			$(".empleado").show();
			                        		}, 1000);
			                        	}else{
			                        		$(".empleado").hide();			                        		
			                        	}
			                        }
			 						showNewVal()
			                        column
			                            .search( val ? '^'+val+'$' : '', true, false )
			                            .draw();
			                    } );
			 
			                column.data().unique().sort().each( function ( d, j ) {
			                    select.append( '<option value="'+d+'">'+d+'</option>' )
			                } );
		                } 		                
		            } );
		            showNewVal()
		            $(".buscaSelect").select2();
		            $(".filters .esconder").html("");
		            $(".filters th.empleado").html("");
		        }
		    } );



} );

function setDataFlowsCS(){
	$('#flujosData thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#flujosData thead');

    $('#flujosData').DataTable( {
	    	'iDisplayLength': 5,
	    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
	    	"ordering": false,
	    	paging: false,
	    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
	        initComplete: function () {
	            this.api().columns().every( function () {
	                var column = this;
	                if (column[0] == 4 || column[0] == 2) {

	                	var select = $('<select class="buscaSelect select_'+column[0]+'"><option value="">Seleccionar</option></select>')
		                    .appendTo( $( column.header()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
	                } 		                
	            } );
            	$(".filters .noMostrar").html("");
	            $(".buscaSelect").select2();
	        }
	    } );
}

function setDataProductsCS(){
	$('#productosData thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#productosData thead');

    $('#productosData').DataTable( {
	    	'iDisplayLength': 5,
	    	"lengthMenu": [ [5,10,20,50, 100, -1], [5,10,20,50, 100, "Todos"] ],
	    	"ordering": false,
	    	paging: false,
	    	"language": {"url": "<?php echo Router::url("/",true) ?>Spanish.json",},
	        initComplete: function () {
	            this.api().columns().every( function () {
	                var column = this;
	                // if (column[0] == 4) {

	                	var select = $('<select class="buscaSelect2 select_'+column[0]+'"><option value="">Seleccionar</option></select>')
		                    .appendTo( $( column.header()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
	                // } 		                
	            } );
            	$(".filters .noMostrar2").html("");
	            $(".buscaSelect2").select2();
	        }
	    } );
}

function dataGeographic(datos){
	$("#datosGeograficos").html("");
	datos = JSON.parse(datos);

	const { dataCiudades,dataBlock } = datos;

	Highcharts.chart('datosGeograficos', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Ventas por departamento y ciudad'
	    },
	    subtitle: {
	        text: 'Clic en el departamento deseado para ver detalle por ciudad'
	    },
	    accessibility: {
	        announceNewData: {
	            enabled: true
	        }
	    },
	    xAxis: {
	        type: 'category'
	    },
	    yAxis: {
	        title: {
	            text: 'Total Ventas'
	        }

	    },
	    legend: {
	        enabled: false
	    },
	    plotOptions: {
	        series: {
	            borderWidth: 0,
	            dataLabels: {
	                enabled: true,
	                format: '${point.y:,.2f}'
	            }
	        }
	    },

	    tooltip: {
	        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b> $ {point.y}</b><br/>'
	    },

	    series: [
	        {
	            name: "Departamentos",
	            colorByPoint: true,
	            dataSorting: {
					        enabled: true,
					        // sortKey: ''
					    },
	            data: dataBlock
	         }
	    ],
	    drilldown: {
	        breadcrumbs: {
	            position: {
	                align: 'right'
	            }
	        },
	        series: dataCiudades
	    }
	});
}

setTimeout(function() {

	

	Highcharts.chart('mesesCliente', {
	    chart: {
	        type: 'column',
	         backgroundColor: '#f7fcff',
	    },
	    title: {
	        text: 'Creación de clientes por mes'
	    },
	    subtitle: {
	        text: ''
	    },
	    xAxis: {
	        categories: catsClientes,
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Total clientes'
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
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            stacking: 'normal',
	            dataLabels: {
	                enabled: true
	            }
	        }
	    },
	    series: datosClientes
	});

	Highcharts.chart('flujosTotalDiv', {
	    chart: {
	        // zoomType: 'xy'
	        backgroundColor : '#f7fcff'
	    },
	    title: {
	        text: 'Flujos por mes y efectividad en pagos'
	    },
	    subtitle: {
	        text: ''
	    },
	    xAxis: [{
	        categories: catsFlujo,
	        crosshair: true
	    }],
	    yAxis: [{
	    	stackLabels: {
	            enabled: true,
	            style: {
	                fontWeight: 'bold',
	                color: ( // theme
	                    Highcharts.defaultOptions.title.style &&
	                    Highcharts.defaultOptions.title.style.color
	                ) || 'gray'
	            }
	        },
	        labels: {
	            format: '{value}',
	            style: {
	                color: Highcharts.getOptions().colors[1]
	            }
	        },
	        title: {
	            text: 'Flujos totales',
	            style: {
	                color: Highcharts.getOptions().colors[1]
	            }
	        }
	    }, { // Secondary yAxis
	        title: {
	            text: 'Flujos pagados',
	            style: {
	                color: Highcharts.getOptions().colors[0]
	            }
	        },
	        labels: {
	            format: '{value}',
	            style: {
	                color: Highcharts.getOptions().colors[0]
	            }
	        },
	        opposite: true
	    }],
	    tooltip: {
	        shared: true
	    },
	    plotOptions: {
	        column: {
	            stacking: 'normal',
	            dataLabels: {
	                enabled: true
	            },

	        },
	        spline: {
	            stacking: 'normal',
	            dataLabels: {
	                enabled: true
	            }
	        }
	    },
	    legend: {
	        layout: 'vertical',
	        align: 'left',
	        // x: 120,
	        verticalAlign: 'top',
	        // y: 100,
	        floating: true,
	        backgroundColor:
	            Highcharts.defaultOptions.legend.backgroundColor || // theme
	            'rgba(255,255,255,0.25)'
	    },
	    series: [{
	        name: 'Flujos totales',
	        type: 'column',
	        yAxis: 1,
	        data: dataAllFlujo,
	        pointPadding: 0.3,
	        tooltip: {
	            valueSuffix: '',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b><br></td></tr>',
	        }

	    }, {
	        name: 'Flujos pagados',
	        type: 'spline',
	        data: dataAllPayment,
	        tooltip: {
	            valueSuffix: '',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b> <br> Efectividad: <b>{point.avg}%</b>  </td></tr>',
	        },
	    }]
	});

	Highcharts.chart('quoatationsTotalDiv', {
	    chart: {
	        type: 'column',
	        backgroundColor:'#f7fcff'
	    },
	    title: {
	        text: 'Creación y envió de cotizaciones'
	    },
	    subtitle: {
	        text: ''
	    },
	    xAxis: {
	        categories: catsQuotations,
	        crosshair: true
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Total cotizaciones'
	        }
	    },
	    tooltip: {
	        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            pointPadding: 0.2,
	            borderWidth: 0,
	            dataLabels: {
                    enabled: true
                }
	        }
	    },
	    series: datosQuoatiations
	});


	Highcharts.chart('clientesPagos',{
	    chart: {
	        type: 'column',
	        backgroundColor: '#f7fcff'
	    },
	    title: {
	        text: 'Efectividad de compra para clientes nuevos y viejos'
	    },
	    xAxis: {
	        categories: catsClientesPayments
	    },
	    yAxis: [{
	        min: 0,
	        title: {
	            text: 'Clientes'
	        }
	    }, {
	        title: {
	            text: 'Clientes pagos'
	        },
	        // opposite: true
	    }],
	    credits: {
            enabled: false
        },
	    legend: {
	        shadow: true
	    },
	     tooltip: {
	        shared: true
	    },
	    plotOptions: {
	        column: {
	            grouping: false,
	            shadow: false,
	            borderWidth: 0,
	            dataLabels: {
                    enabled: true
                }
	        }
	    },
	    series: [{
	        name: 'Total clientes creados',
	        color: 'rgb(124,181,236,1)',
	        data: totalClients,
	        pointPadding: 0.3,
	        showInLegend: true,
	        pointPlacement: -0.2,
	        tooltip: {
	            valueSuffix: '',
	            pointFormat: '<tr><td style="text-align:center"> <span style="text-align: center"> <b>Clientes nuevos</b></span> <hr></td></tr><br><tr><td style="color:rgb(67,67,72,0.9) !important;padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b></td></tr>',
	        },
	    }, {
	        name: 'Clientes nuevos que realizaron pago',
	        color: 'rgb(67,67,72,0.9)',
	        data: totalClientsPayment,
	        pointPadding: 0.4,
	        showInLegend: true,
	        tooltip: {
	            valueSuffix: '',
	            pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b> <br> <span style="color:{point.color}">\u25CF</span>Efectividad clientes nuevos: <b>{point.avg}%</b> <br> <span style="color:{point.color}">\u25CF</span> Pagos realizados: <b>$ {point.payment}</b> </td></tr><br><br> <hr>',
	        },
	        pointPlacement: -0.2
	    }, {
	        name: 'Total flujos creados',
	        color: 'rgb(144,237,125,1)',
	        data: flujosPagadosClientesAll,
	        tooltip: {
	            pointFormat: '<br><tr><td style="text-align:center"> <span style="text-align: center"> <b>Clientes existentes</b></span> <hr></td></tr><br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b> </tr>',
	        },
	        pointPadding: 0.3,
	        pointPlacement: 0.2,
	        yAxis: 1
	    },{
	        name: 'Clientes ya existentes que realizaron pago',
	        color: 'rgb(255,188,117,0.9)',
	        data: pagadosViejosClientes,
	        tooltip: {
	            valueSuffix: '',
	            pointFormat: '<br><tr><td style="color:{series.color};padding:0"><span style="color:{point.color}">\u25CF</span>{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>Efectividad Clientes existentes: <b>{point.avg}%</b> <br> <span style="color:{point.color}">\u25CF</span> Pagos realizados: <b>$ {point.payment}</b>  </td></tr>',
	        },
	        pointPadding: 0.4,
	        pointPlacement: 0.2,
	        yAxis: 1
	    }]
	});

	anioActual = $("#anio").val();
	dataForView(anioActual);

	$("#anio").change(function(event) {
		$("#containerMetas").html("");
		$(".buttonsData").removeClass('btn-primary');
		$(".buttonsData").addClass('btn-outline-primary');
		$("#TODOS").removeClass('btn-outline-primary');
		$("#TODOS").addClass('btn-primary');
		anioActual = $("#anio").val();
		dataForView(anioActual);
	});
	
}, 5000);

function dataForView(anio){
	const dataDataLayer = totalByCompany[anio];

	var chart = Highcharts.chart('containerMetas', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Meta de ventas empresarial del año '+anio+' ($'+metaAnual[anio]+') - Total vendido: $'+totalAnio[anio]+' - Cumplimiento: '+cumplimientoTodos[anio]+'%',
	        align: 'center'
	    },
	    subtitle: {
	        text: '',
	        align: 'left'
	    },
	    plotOptions: {
	        series: {
	            grouping: false,
	            borderWidth: 0
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
	        categories: mesesCats
	    },
	    yAxis: [{
	        title: {
	            text: 'Meta de ventas'
	        },
	    }],
	    series: [{
	        color: 'rgb(158, 159, 163)',
	        pointPlacement: -0.2,
	        linkedTo: 'main',
	        data: dataPrev[anio]['TODOS'].slice(),
	        name: 'Meta'
	    }, {
	        name: 'Vendido',
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
	            '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}">\u25CF</span>Cumplimiento: <b>{point.cumplimiento}%</b></td></tr>',
	        },
	        data: dataDataLayer['TODOS'].slice()
	    }],
	    exporting: {
	        allowHTML: true
	    }
	});



	var years = dataBtnDt;

	years.forEach(function (year) {
	    var btn = document.getElementById(year);
	    Object.freeze(dataDataLayer);
	    btn.addEventListener('click', function () {

	        document.querySelectorAll('.buttonsData').forEach(function (active) {
	            active.classList.remove('btn-primary');
	            active.classList.add('btn-outline-primary');
	        });
	        btn.classList.add('btn-primary');
	        btn.classList.remove('btn-outline-primary');
	        var nameYear = year.replace('_', ' ');
	        
	        var totalVendido = 0;
	        var mostrarCon  = nameYear == "TODOS" ? datosTodos[anio][nameYear] : dataDataLayer[nameYear].slice();
	        var metaUsuario = metasAnio[anio][nameYear];
	        var cumplimientoUsuario = cumplimientoAnual[anio][nameYear];
	        if (mostrarCon.length > 0) {
	        	for (var i = 0; i < mostrarCon.length; i++) {
	        		totalVendido+=mostrarCon[i].y;
	        	}
	        }
	        chart.update({
	            title: {
	                text: 'Meta de '+ nameYear +' del año '+anio+' ($'+formatter.format(metaUsuario)+') - Total Vendido: $'+formatter.format(totalVendido)+' - Cumplimiento: '+cumplimientoUsuario+'%'
	            },
	            subtitle: {
	                text: ''
	            },
	            series: [{
	                name: "Meta",
	                data: dataPrev[anio][nameYear].slice()
	            }, {
	                name: "Vendido",
	                data: mostrarCon
	            }]
	        }, true, false, {
	            duration: 800
	        });
	    });
	});
}

$(".detalleComisionUsuario").click(function(event) {
	event.preventDefault();
	$("#modalDetalleComisionesUsuario").modal("show");

	$('#fechasInicioFinComisiones').daterangepicker({
        "showDropdowns": false,
        "opens": "left",
        "parentEl": "#modalDetalleComisionesUsuario .modal-body",  
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Último año': [moment().subtract(365, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment()],
            'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        },
        "locale": {
            "format": "YYYY-MM-DD",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": labelCancel,
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Definir rango",
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        "alwaysShowCalendars": true,
        //  "startDate": "<?php echo isset($fechaInicioReporte) ? $fechaInicioReporte : date("Y-m-d"); ?>",
        //  "endDate": "<?php echo isset($fechaFinReporte) ? $fechaFinReporte : date("Y-m-d"); ?>",
        // "maxDate": "<?php echo date("Y-m-d") ?>"
    }, function(start, end, label) {


    	$("#input_date_inicio_comision").val(start.format('YYYY-MM-DD'));
    	$("#input_date_fin_comision").val(end.format('YYYY-MM-DD'));

        $("body").find("#btn_find_adviser_comisiones").trigger('click');

    });
    $("body").find("#btn_find_adviser_comisiones").trigger('click');

});




$("#btn_find_adviser_comisiones").click(function(event) {
	event.preventDefault();

	$("#datosComisiones").html('');
	$("#totalesYReferencia").html('');
	var fechaIni =  $("#input_date_inicio_comision").val();
	var fechaEnd =  $("#input_date_fin_comision").val();
	var user 		 = $("#user_id_simulates").val();

	$.post(copy_js.base_url+"prospective_users/comisiones",{fechaIni,fechaEnd,user}, function(response) {
	    $("#datosComisiones").html(response);
	    $(".dataFinal").clone(true).appendTo("#totalesYReferencia");
	    $(".dataFinal:last").remove();
	    $(".detalleFinalComisionesUser").hide();
	    simuladorComisiones();
	});

	console.log(fechaIni);
	console.log(fechaEnd);
	console.log(user);

});

$("body").on('click', '#btnIconR', function(event) {
	event.preventDefault();
	const elemIco = $(this).children("#detailIcon");
	if (elemIco.hasClass("fa-eye")) {
		$(".detalleFinalComisionesUser").show();
		elemIco.removeClass('fa-eye');
		elemIco.addClass("fa-times");
	}else{
		$(".detalleFinalComisionesUser").hide();
		elemIco.addClass('fa-eye');
		elemIco.removeClass("fa-times");
	}
});


function simuladorComisiones(){

	$("#graficaSimulador").html("");

	var META_VENTAS    			= 	$("#metaVentasInput").val();
	var META_RECAUDO   			= 	$("#metaRecaudoInput").val();
	var ValorRecaudado 		  = 	parseInt($("#ValorRecaudado").val());
	var TotalVentas 		  	= 	parseInt($("#TotalVentas").val());
	var EfectividadPromedio = $("#EfectividadPromedio").val();

	$("#totalRecaudado").html("$"+formatterSimulador.format(ValorRecaudado));
	$("#porcentajeEfectividad").html("% "+EfectividadPromedio);
	$("#totalVentas").html("$"+formatterSimulador.format(TotalVentas));

	var cumple = false;

	var totalComisionesRecaudo = parseInt(ValorRecaudado)*0.02;

	if (META_RECAUDO <= ValorRecaudado) {
		$("#totalComisionesRecaudo").removeClass("text-danger");
		$("#totalComisionesRecaudo").addClass("text-success");
		$("#totalComisionesRecaudo").html("$"+formatterSimulador.format(totalComisionesRecaudo));
		cumple = true;
	}else{
	  $("#totalComisionesRecaudo").removeClass("text-success");
		$("#totalComisionesRecaudo").addClass("text-danger");
		$("#totalComisionesRecaudo").html("No cumple la meta mínima de recaudo");
	}

	var MargenPromedio 	 = parseFloat($("#MargenPromedio").val());

	var finalValueMargen = 1;

	var cienMargen 			 = null;

	if (typeof PERCENTAJES != 'undefined') {
		var finalMargen = PERCENTAJES.filter(function(porcentaje) {
			return porcentaje.min <= MargenPromedio && porcentaje.max > MargenPromedio;
		});
		finalValueMargen = finalMargen.length > 0 ? ( parseInt(finalMargen[0].value) / 100 ) : 1;

		var cienMargen = PERCENTAJES.filter(function(porcentaje) {
			return porcentaje.value == 100;
		});

		if (cienMargen.length > 0) {
			cienMargen = cienMargen[0].min;
		}
	}

	console.log(cienMargen)

	var totalComisionesMargen = totalComisionesRecaudo*finalValueMargen;
	$("#totalComisionesMargen").html( cumple? "$"+formatterSimulador.format(totalComisionesMargen) : 'No cumple la meta mínima de recaudo' );

	var diferenciaComisionesRecaudoMargen = Math.abs(totalComisionesMargen-totalComisionesRecaudo);

	if (!cumple) {
		$("#diferenciaComisionesRecaudoMargen").removeClass("text-success");
		$("#diferenciaComisionesRecaudoMargen").addClass("text-danger");
		$("#diferenciaComisionesRecaudoMargen").html("No cumple la meta mínima de recaudo");
	}else{
		if (totalComisionesMargen >= totalComisionesRecaudo) {
			$("#diferenciaComisionesRecaudoMargen").removeClass("text-danger");
			$("#diferenciaComisionesRecaudoMargen").addClass("text-success");
		}else{
		  $("#diferenciaComisionesRecaudoMargen").removeClass("text-success");
			$("#diferenciaComisionesRecaudoMargen").addClass("text-danger");
		}
		$("#diferenciaComisionesRecaudoMargen").html("$"+formatterSimulador.format(diferenciaComisionesRecaudoMargen));
	}

	var TotalEfetividad = totalComisionesMargen;

	var finalEfectivityPercentaje = 1;
	var EfectividadPromedio 		  = parseFloat($("#EfectividadPromedio").val());
	var efectidadValor 						= 0;

	var cienEfectividad 					= null;

	if (typeof EFFECTIVITY != 'undefined') {
		var finalMargenEffectivity = EFFECTIVITY.filter(function(porcentaje) {
			return porcentaje.min <= EfectividadPromedio && porcentaje.max > EfectividadPromedio;
		});
		finalEfectivityPercentaje = finalMargenEffectivity.length > 0 ? ( parseInt(finalMargenEffectivity[0].value) / 100 ) : 1;

		cienEfectividad						= EFFECTIVITY.filter(function(porcentaje) {
			return porcentaje.value == 100;
		});

		if (cienEfectividad.length > 0) {
			cienEfectividad = cienEfectividad[0].min;
		}

		efectidadValor 						= parseInt(finalMargenEffectivity[0].value);
		TotalEfetividad 					*=finalEfectivityPercentaje;					
	}

	$("#efectidadValor").html("% "+efectidadValor);
	$("#totalComisionesEfectividad").html(cumple ? "$"+formatterSimulador.format(TotalEfetividad) : 'No cumple la meta mínima de recaudo');

	var diferenciaComisionesEfectividadMargen = Math.abs(TotalEfetividad-totalComisionesMargen);

	if (!cumple) {
		$("#diferenciaComisionesEfectividadMargen").removeClass("text-success");
		$("#diferenciaComisionesEfectividadMargen").addClass("text-danger");
		$("#diferenciaComisionesEfectividadMargen").html("No cumple la meta mínima de recaudo");
	}else{
		if (TotalEfetividad >= totalComisionesMargen) {
			$("#diferenciaComisionesEfectividadMargen").removeClass("text-danger");
			$("#diferenciaComisionesEfectividadMargen").addClass("text-success");
		}else{
		  $("#diferenciaComisionesEfectividadMargen").removeClass("text-success");
			$("#diferenciaComisionesEfectividadMargen").addClass("text-danger");
		}
		$("#diferenciaComisionesEfectividadMargen").html("$"+formatterSimulador.format(diferenciaComisionesEfectividadMargen));
	}

	var adicionalPorVentas = 0;

	if (TotalVentas > META_VENTAS) {
		adicionalPorVentas =  totalComisionesRecaudo*0.3;
		TotalEfetividad		 += adicionalPorVentas;

		$("#adicionalPorVentas").removeClass("text-danger");
		$("#adicionalPorVentas").addClass("text-success");
		$("#adicionalPorVentas").html(cumple ? "$"+formatterSimulador.format(adicionalPorVentas) : 'No cumple la meta mínima de recaudo');
	}else{
		$("#adicionalPorVentas").removeClass("text-success");
		$("#adicionalPorVentas").addClass("text-danger");
		$("#adicionalPorVentas").html(cumple ? "No cumple la meta mínima de ventas" : 'No cumple la meta mínima de recaudo');
	}

	$("#totalPagoFinal").addClass("text-success");
	$("#totalPagoFinal").html("$"+formatterSimulador.format(TotalEfetividad));
}

$("body").on('click', '#simular', function(event) {
	event.preventDefault();
	simuladorComisiones();
});