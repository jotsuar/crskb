function equalizeArrays(array1, array2) {
    // Obtener todos los nombres únicos de ambos arrays
    const allNames = new Set([...array1.map(item => item.name), ...array2.map(item => item.name)]);

    // Función para asegurarse de que un array tiene todos los nombres
    const fillMissing = (arr, allNames) => {
        const map = new Map(arr.map(item => [item.name, item]));
        return Array.from(allNames).map(name => map.get(name) || { y: 0, name });
    };

    // Normalizar ambos arrays
    const normalizedArray1 = fillMissing(array1, allNames);
    const normalizedArray2 = fillMissing(array2, allNames);

    return [normalizedArray1, normalizedArray2];
}


function equalizeArraysCats(array1, array2) {
    // Obtener todos los nombres únicos de ambos arrays
    const allNames = new Set([...array1.map(item => item.name), ...array2.map(item => item.name)]);

    // Función para asegurarse de que un array tiene todos los nombres
    const fillMissing2 = (arr, allNames) => {
        const map = new Map(arr.map(item => [item.name, item]));
        return Array.from(allNames).map(name => map.get(name) || { id: 0, name });
    };

    // Normalizar ambos arrays
    const normalizedArray1 = fillMissing2(array1, allNames);
    const normalizedArray2 = fillMissing2(array2, allNames);

    return [normalizedArray1, normalizedArray2];
}

function contieneTodos(empleados, referencia) {
    return referencia.every(id => empleados.includes(id));
}

$("#empleado").select2();
$("#category_1").select2();

function getDataForChart($parent_id , $level = 1){

	const categories = [];
	const empleadosids = [];
	var empleados  = $("#empleado").val() || '';

	const dataCatMin = [{id:0,name:'Seleccionar'}];
	const dataCatMax = [{id:0,name:'Seleccionar'}];
	const idSelect  = "#category_"+$level;
	const final_labels = [];

	dataPrev 		= [];
	dataNext 		= [];

	let totalMostrar2023 = 0;
	let totalMostrar2024 = 0;

	datosTabla2023 	= [];
	datosTabla2024 	= [];

	// const final_group  = equalizeArraysCats(dataCatMin, dataCatMax);
	const final_group  = [{id:0,name:'Seleccionar'}];


	datos2023.forEach( function(element, index) {
		if(element.parent == $parent_id ){
			if(element.name == null){
				element.name = 'OTROS';
			}

			dataCatMin.push({id:element.id_data,name: element.name});
			var totalGen = 0;

			if(final_labels.indexOf(element.name) == -1){
				final_labels.push(element.name);
				final_group.push({id:element.id_data,name:element.name});
			}

			element.details.forEach( function(elementData, indexData) {
				if( empleados === "0" || empleados == (elementData.IdEmpleado) ){					
					totalGen+=(elementData.total);

					var indexTable = _.findIndex(datosTabla2023, function(data) { return data.part_number == elementData.Codigo; });

					if(indexTable == -1){
						datosTabla2023.push({
							name: elementData.Descripcion,
							part_number: elementData.Codigo,
							total_conteo: elementData.total_conteo,
							total: elementData.total,
							total_conteo_2024:0,
							total_2024:0,
							id_user: elementData.Identificacion
						});
					}else{
						datosTabla2023[indexTable].total+=elementData.total;
						datosTabla2023[indexTable].total_conteo+=elementData.total_conteo;
					}

					
				}
			});

			dataPrev.push({y:totalGen,name: element.name});
			totalMostrar2023+=totalGen;

		}
	});


	datos2024.forEach( function(element, index) {
		if(element.parent == $parent_id ){
			if(element.name == null){
				element.name = 'OTROS';
			}
			dataCatMax.push({id:element.id_data,name: element.name});

			var totalGen = 0;

			if(final_labels.indexOf(element.name) == -1){
				final_labels.push(element.name);
				final_group.push({id:element.id_data,name:element.name});
			}

			element.details.forEach( function(elementData, indexData) {
				if( empleados === "0" || empleados == (elementData.IdEmpleado)){					
					totalGen+=(elementData.total)
					var indexTable = _.findIndex(datosTabla2023, function(data) { return data.part_number == elementData.Codigo; });

					if(indexTable == -1){
						datosTabla2023.push({
							name: elementData.Descripcion,
							part_number: elementData.Codigo,
							total_conteo_2024: elementData.total_conteo,
							total_2024: elementData.total,
							total_conteo:0,
							total:0,
							id_user: elementData.Identificacion
						});
					}else{
						datosTabla2023[indexTable].total_2024+=elementData.total;
						datosTabla2023[indexTable].total_conteo_2024+=elementData.total_conteo;
					}
				}
			});


			dataNext.push({y:totalGen,name: element.name});
			totalMostrar2024+=totalGen;
		}
	});



	$(idSelect).html('');
	final_group.forEach( function(element, index) {
		$(idSelect).append('<option value="'+element.id+'"> '+element.name+' </option> ');
	});
	$(idSelect).attr('data-parent_id',$parent_id);

	if($level == 1){
		$("#category_2").html('');
		$("#category_3").html('');
		$("#category_4").html('');
	}else if($level == 2){
		$("#category_3").html('');
		$("#category_4").html('');
	}else if($level == 3){
		$("#category_4").html('');
	}

	[dataPrev,dataNext] = equalizeArrays(dataPrev,dataNext);

	dataPrev = (_.orderBy(dataPrev,'name','asc') );
	dataNext = (_.orderBy(dataNext,'name','asc') );

	table2023 = $("#detalleProductos2023").DataTable();
	table2023.clear().destroy();

	$('#detalleProductos2023').DataTable( {
	    data: datosTabla2023,
	    columns: [
	        { data: 'name' },
	        { data: 'part_number' },
	        { data: 'total', render: function (data, type, row) {
                // Convertir el número en formato moneda (ejemplo: USD)
                return new Intl.NumberFormat('en-US', {
				  minimumFractionDigits: 0,
				  maximumFractionDigits: 0,
				}).format(data);
            } },
	        { data: 'total_conteo' },
	        { data: 'total_2024', render: function (data, type, row) {
                // Convertir el número en formato moneda (ejemplo: USD)
                return new Intl.NumberFormat('en-US', {
				  minimumFractionDigits: 0,
				  maximumFractionDigits: 0,
				}).format(data);
            } },
	        { data: 'total_conteo_2024' },
	        {data: null,
	            render: function (data, type, row) {
	                return `<a href="${url_details}/${row.part_number}/${row.total_conteo}/${row.total}/${row.total_conteo_2024}/${row.total_2024}/${ empleados === '0' ? '0' : row.id_user}/" class="btn btn-sm btn-info viewDetailProduct"> Ver detalle </a>`;
	            }
	        },
	    ]
	} );

	var chart = Highcharts.chart('datosProductos2024', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Venta total por líneas de producto | Total 2023: '+number_format(totalMostrar2023) + ' - Total 2024: '+number_format(totalMostrar2024) ,
	        align: 'center'
	    },
	    accessibility:{
	    	enabled: false
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
	        categories: final_labels.sort()
	    },
	    yAxis: [{
	        title: {
	            text: 'Total Vendido'
	        },
	    }],
	    series: [{
	        color: 'rgb(158, 159, 163)',
	        pointPlacement: -0.2,
	        linkedTo: 'main',
	        data: dataPrev.slice(),
	        name: 'Vendido 2023',
	        // dataSorting: {
		    //     enabled: true,
		    //     sortKey: 'name'
		    //     // matchByName: true
		    // },
	    }, {
	        name: 'Vendido 2024',
	        id: 'main',
	        // dataSorting: {
	        //     // enabled: true,
	        //     enabled: true,
		    //     sortKey: 'name'
	        //     // matchByName: true
	        // },
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
	            '<td style="padding:0"><b>{point.y}</b> <br><span style="color:{point.color}"></td></tr>',
	        },
	        data: dataNext.slice()
	    }],
	    exporting: {
	        allowHTML: true
	    }
	});
}

getDataForChart('0',1);


$("#empleado").change(function(event) {
	var idData = "#"+$(this).attr("data-idcat");
	$(idData).trigger('change');
});


$(".categories_select").change(function(event) {

	level		= $(this).data('level');
	nextlevel	= $(this).data('nextlevel');
	parent_id 	= $(this).data('parent_id');
	parentId 	= $(this).data('parentid');


	if ($(this).val() == '0') {
		if(level == 1){
			getDataForChart('0',1);
		}else{
			console.log(parent_id);
			$("#"+parentId ).trigger('change');
		}
	}else{
		getDataForChart( $(this).val(),nextlevel );
	}

	$("#empleado").attr('data-idcat',$(this).attr('id'))
});


$("body").on('click', '.viewDetailProduct', function(event) {
	event.preventDefault();
	const URL = $(this).attr('href');

	$.get(URL, function(response) {
		$("#bodyDetalleCotizaciones").html(response);
		$("#detalleProducto").modal('show');
	});
});




	// Highcharts.chart('datosProductos2024', {
	//     chart: {
	//         type: 'pie'
	//     },
	//     title: {
	//         text: 'Ventas por Líniea de producto año 2024'
	//     },
	//     subtitle: {
	//         text: 'Clic en la categoría deseada para ver detalle'
	//     },
	//     accessibility: {
	//         announceNewData: {
	//             enabled: true
	//         }
	//     },
	//     xAxis: {
	//         type: 'category'
	//     },
	//     yAxis: {
	//         title: {
	//             text: 'Total Ventas'
	//         }

	//     },
	//     legend: {
	//         enabled: false
	//     },
	//     plotOptions: {
    //     series: {
    //         borderRadius: 5,
    //         dataLabels: [{
    //             enabled: true,
    //             distance: 15,
    //             format: '{point.name}'
    //         }, {
    //             enabled: true,
    //             distance: '-30%',
    //             filter: {
    //                 property: 'percentage',
    //                 operator: '>',
    //                 value: 5
    //             },
    //             format: '{point.y:.1f}%',
    //             style: {
    //                 fontSize: '0.9em',
    //                 textOutline: 'none'
    //             }
    //         }]
    //     }
    // },

	//     tooltip: {
	//         headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	//         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b> $ {point.y}</b><br/>'
	//     },

	//     series: [
	//         {
	//             name: "Líneas",
	//             colorByPoint: true,
	//             dataSorting: {
	// 				        enabled: true,
	// 				        // sortKey: ''
	// 				    },
	//             data: datos2024['datos_series_grupo1']
	//          }
	//     ],
	//     drilldown: {
	//         breadcrumbs: {
	//             position: {
	//                 align: 'right'
	//             }
	//         },
	//         series: datos2024['datos_series_grupos_gen']
	//     }
	// });

	// Highcharts.chart('datosProductos2023', {
	//     chart: {
	//         type: 'column'
	//     },
	//     title: {
	//         text: 'Ventas por Líniea de producto año 2023'
	//     },
	//     subtitle: {
	//         text: 'Clic en la categoría deseada para ver detalle'
	//     },
	//     accessibility: {
	//         announceNewData: {
	//             enabled: true
	//         }
	//     },
	//     xAxis: {
	//         type: 'category'
	//     },
	//     yAxis: {
	//         title: {
	//             text: 'Total Ventas'
	//         }

	//     },
	//     legend: {
	//         enabled: false
	//     },
	//     plotOptions: {
	//         series: {
	//             borderWidth: 0,
	//             dataLabels: {
	//                 enabled: true,
	//                 format: '${point.y:,.2f}'
	//             }
	//         }
	//     },

	//     tooltip: {
	//         headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	//         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b> $ {point.y}</b><br/>'
	//     },

	//     series: [
	//         {
	//             name: "Líneas",
	//             colorByPoint: true,
	//             dataSorting: {
	// 				        enabled: true,
	// 				        // sortKey: ''
	// 				    },
	//             data: datos2023['datos_series_grupo1']
	//          }
	//     ],
	//     drilldown: {
	//         breadcrumbs: {
	//             position: {
	//                 align: 'right'
	//             }
	//         },
	//         series: datos2023['datos_series_grupos_gen']
	//     }
	// });