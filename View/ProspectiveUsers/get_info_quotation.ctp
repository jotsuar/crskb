<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


<script>
	$(function () {
    // Create the chart
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
        text: 'Cotizaciones realizadas por asesor'
    },
    subtitle: {
        text: 'Cotizaciones realizadas en un rango de fechas, por dia y cliente (solo se muestra 1 por día)'
    },
        xAxis: {
            type: 'category'
        },
        yAxis: {
          title: {
              text: 'Total de cotizaciones'
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
                format: '{point.y}%'
            }
        }
    },

        series: [{
            data: [
{
"name": "Osnaider",
"y": 15,
"drilldown": "c4ca4238a0b923820dcc509a6f75849b"
},
{
"name": "Alejandra",
"y": 38,
"drilldown": "c81e728d9d4c2f636f067f89cc14862c"
},
{
"name": "Daniel",
"y": 103,
"drilldown": "eccbc87e4b5ce2fe28308fd9f2a7baf3"
},
{
"name": "Leonardo",
"y": 69,
"drilldown": "e4da3b7fbbce2345d7772b0674a318d5"
},
{
"name": "Jaime",
"y": 36,
"drilldown": "8f14e45fceea167a5a36dedd4bea2543"
},
{
"name": "Blanca",
"y": 68,
"drilldown": "6512bd43d9caa6e02c990b0a82652dca"
},
{
"name": "Kenneth",
"y": 7,
"drilldown": "c51ce410c124a10e0db5e4b97fc2af39"
},
{
"name": "Felipe",
"y": 13,
"drilldown": "642e92efb79421734881b53e1e1b18b6"
},
{
"name": "Margarita",
"y": 21,
"drilldown": "f457c545a9ded88f18ecee47145a72c0"
}
]
        }],
        drilldown: {
            series: [
    {
        "name": "Osnaider",
        "id": "c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "2019-10-04",
                "y": 1,
                "drilldown": "2019-10-04-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-08",
                "y": 2,
                "drilldown": "2019-10-08-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-09",
                "y": 4,
                "drilldown": "2019-10-09-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-10",
                "y": 1,
                "drilldown": "2019-10-10-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-16",
                "y": 3,
                "drilldown": "2019-10-16-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-19",
                "y": 1,
                "drilldown": "2019-10-19-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-23",
                "y": 2,
                "drilldown": "2019-10-23-c4ca4238a0b923820dcc509a6f75849b"
            },
            {
                "name": "2019-10-24",
                "y": 1,
                "drilldown": "2019-10-24-c4ca4238a0b923820dcc509a6f75849b"
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "Jorge Valderrama Rodriguez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "PUBLICIDAD RAPIARTE",
                "y": 1
            },
            {
                "name": "MEDINISTROS S A S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-09",
        "id": "2019-10-09-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "JOINT AND WELDING INGENIEROS S.A.S.",
                "y": 1
            },
            {
                "name": "SOLTRECH S.A.S",
                "y": 1
            },
            {
                "name": "HI-TRACK MACHINES S.A.S.",
                "y": 1
            },
            {
                "name": "REACONDICIONAR S. EN C",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-10",
        "id": "2019-10-10-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "LINEPLAST",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "Miguel Puentes",
                "y": 1
            },
            {
                "name": "Johann londoño",
                "y": 1
            },
            {
                "name": "Libardo ortiz cruz",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-19",
        "id": "2019-10-19-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "M H MANTENIMIENTOS HIDRAULICOS S.A.S. ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "CONCRETOS Y MEZCLAS S.A.",
                "y": 1
            },
            {
                "name": "Johan Torres",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-c4ca4238a0b923820dcc509a6f75849b",
        "data": [
            {
                "name": "Paola Franco",
                "y": 1
            }
        ]
    },
    {
        "name": "Alejandra",
        "id": "c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "2019-10-01",
                "y": 3,
                "drilldown": "2019-10-01-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-02",
                "y": 1,
                "drilldown": "2019-10-02-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-03",
                "y": 1,
                "drilldown": "2019-10-03-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-04",
                "y": 1,
                "drilldown": "2019-10-04-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-16",
                "y": 2,
                "drilldown": "2019-10-16-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-17",
                "y": 1,
                "drilldown": "2019-10-17-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-18",
                "y": 2,
                "drilldown": "2019-10-18-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-19",
                "y": 3,
                "drilldown": "2019-10-19-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-21",
                "y": 4,
                "drilldown": "2019-10-21-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-23",
                "y": 3,
                "drilldown": "2019-10-23-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-25",
                "y": 2,
                "drilldown": "2019-10-25-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-28",
                "y": 3,
                "drilldown": "2019-10-28-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-29",
                "y": 5,
                "drilldown": "2019-10-29-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-30",
                "y": 5,
                "drilldown": "2019-10-30-c81e728d9d4c2f636f067f89cc14862c"
            },
            {
                "name": "2019-10-31",
                "y": 2,
                "drilldown": "2019-10-31-c81e728d9d4c2f636f067f89cc14862c"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "MEKON E INGENIERIA S.A.S",
                "y": 1
            },
            {
                "name": "METROLOGIA INSTRUMENTACION Y CONTROL MIC S.A.S",
                "y": 1
            },
            {
                "name": " Nelson Buitrago",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-02",
        "id": "2019-10-02-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "METALORIENTE S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "HOBEROL SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "STARK INDUSTRIAL EQUIPMENT AND SUPPLIES S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "ISMOCOL S.A.",
                "y": 1
            },
            {
                "name": "EPROQ MECANICA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-17",
        "id": "2019-10-17-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "INDUSTRIAS DE ALUMINIO ARQUITECTONICO Y VENTANERIA S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "COTECMAR",
                "y": 1
            },
            {
                "name": "Keydis Milena Viloria Herrera",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-19",
        "id": "2019-10-19-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "COTECMAR",
                "y": 1
            },
            {
                "name": "AGROVIC LTDA",
                "y": 1
            },
            {
                "name": "CONSTRUCCIONES EL CONDOR S A",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-21",
        "id": "2019-10-21-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "COTECMAR",
                "y": 1
            },
            {
                "name": "ZH INGENIEROS S.A.S.",
                "y": 1
            },
            {
                "name": "APROVAR S.A.S",
                "y": 1
            },
            {
                "name": "SFI INDUSTRIAL S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "C.I. POWERSEAL S.A.",
                "y": 1
            },
            {
                "name": "SAMUEL MORALES Y CIA S EN C.S",
                "y": 1
            },
            {
                "name": "EQUIPOS INTERNACIONALES LTDA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-25",
        "id": "2019-10-25-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "DINACOL S.A.S",
                "y": 1
            },
            {
                "name": "JOSE VALDERRAMA ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "COTECMAR",
                "y": 1
            },
            {
                "name": "AMERICANTOOLS IMPORTADORA Y COMERCIALIZADORA S.A.S.",
                "y": 1
            },
            {
                "name": "SEIMAR SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "VIAS Y SEÑALES S.A.S",
                "y": 1
            },
            {
                "name": "AMERICANTOOLS IMPORTADORA Y COMERCIALIZADORA S.A.S.",
                "y": 1
            },
            {
                "name": "EQUIPOS INTERNACIONALES LTDA",
                "y": 1
            },
            {
                "name": "COREMAR COMPAÑÍA DE SERVICIOS PORTUARIOS S.A.S.",
                "y": 1
            },
            {
                "name": "Jorge Romero ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-30",
        "id": "2019-10-30-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "CONSTRUCCIONES METALICAS DEL CARIBE S.A.S",
                "y": 1
            },
            {
                "name": "SUMINISTROS Y SOLUCIONES INGENIERILES S.A.S",
                "y": 1
            },
            {
                "name": "DIMECAR S.A.S. & INGENIEROS ASOCIADOS",
                "y": 1
            },
            {
                "name": "SERSUFACOL S.A.S",
                "y": 1
            },
            {
                "name": "Jonathan Gutiérrez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-31",
        "id": "2019-10-31-c81e728d9d4c2f636f067f89cc14862c",
        "data": [
            {
                "name": "ISMOCOL S.A.",
                "y": 1
            },
            {
                "name": "ECO DESARROLLOS PARA LA CONSTRUCCION S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "Daniel",
        "id": "eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "2019-10-01",
                "y": 5,
                "drilldown": "2019-10-01-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-02",
                "y": 4,
                "drilldown": "2019-10-02-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-03",
                "y": 4,
                "drilldown": "2019-10-03-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-04",
                "y": 4,
                "drilldown": "2019-10-04-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-07",
                "y": 4,
                "drilldown": "2019-10-07-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-08",
                "y": 5,
                "drilldown": "2019-10-08-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-09",
                "y": 10,
                "drilldown": "2019-10-09-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-10",
                "y": 2,
                "drilldown": "2019-10-10-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-11",
                "y": 5,
                "drilldown": "2019-10-11-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-12",
                "y": 4,
                "drilldown": "2019-10-12-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-15",
                "y": 5,
                "drilldown": "2019-10-15-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-16",
                "y": 3,
                "drilldown": "2019-10-16-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-18",
                "y": 1,
                "drilldown": "2019-10-18-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-19",
                "y": 3,
                "drilldown": "2019-10-19-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-21",
                "y": 3,
                "drilldown": "2019-10-21-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-22",
                "y": 8,
                "drilldown": "2019-10-22-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-23",
                "y": 3,
                "drilldown": "2019-10-23-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-24",
                "y": 3,
                "drilldown": "2019-10-24-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-25",
                "y": 9,
                "drilldown": "2019-10-25-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-26",
                "y": 2,
                "drilldown": "2019-10-26-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-28",
                "y": 5,
                "drilldown": "2019-10-28-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-29",
                "y": 3,
                "drilldown": "2019-10-29-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-30",
                "y": 6,
                "drilldown": "2019-10-30-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            },
            {
                "name": "2019-10-31",
                "y": 2,
                "drilldown": "2019-10-31-eccbc87e4b5ce2fe28308fd9f2a7baf3"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "NESTOR BRAVO S.A.",
                "y": 1
            },
            {
                "name": "SOLIMET S.A.S.",
                "y": 1
            },
            {
                "name": "ISMOCOL S.A.",
                "y": 2
            },
            {
                "name": "CARLOS VALENCIA GUARIN",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-02",
        "id": "2019-10-02-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "PINTURAS IDEA S.A.",
                "y": 1
            },
            {
                "name": "SOLIMET S.A.S.",
                "y": 1
            },
            {
                "name": "HEOLELCO SUMINISTROS INDUSTRIALES S.A.S",
                "y": 1
            },
            {
                "name": "CAMILO ANDRES FIGUEREDO LLANO INGENIERIA SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "PROYECTOS CON INGENIERIA S.A.S.",
                "y": 1
            },
            {
                "name": "INVERSIONES Y LOGISTICA TITAN S.A.S",
                "y": 1
            },
            {
                "name": "Jhon Jairo del Rio Caro",
                "y": 1
            },
            {
                "name": "León Londoño",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "ISMOCOL S.A.",
                "y": 1
            },
            {
                "name": "CONTENEDORES DE ANTIOQUIA S.A.S",
                "y": 1
            },
            {
                "name": "Talleres de Limpieza profesional automotriz Don Luis..",
                "y": 1
            },
            {
                "name": "Luis Angel Marrugo",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-07",
        "id": "2019-10-07-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "BOCCARD PIPING COLOMBIA SAS",
                "y": 1
            },
            {
                "name": "TU VENTANA CALI S.A.S",
                "y": 1
            },
            {
                "name": "Jairo Carballo",
                "y": 1
            },
            {
                "name": "Manuel Acabal",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "RYMEL INGENIERIA ELECTRICA S.A.S.",
                "y": 1
            },
            {
                "name": "MANTOTAL S.A.S",
                "y": 1
            },
            {
                "name": "Claudia Sierra",
                "y": 1
            },
            {
                "name": "Carlos",
                "y": 1
            },
            {
                "name": "Jhonny Hincapie",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-09",
        "id": "2019-10-09-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "JM3 SOLUCIONES S.A.S",
                "y": 1
            },
            {
                "name": "ATB RIVA CALZONI COLOMBIA",
                "y": 1
            },
            {
                "name": "BOCCARD PIPING COLOMBIA SAS",
                "y": 1
            },
            {
                "name": "AMOBLAMIENTO URBANO DE COLOMBIA S.A.S",
                "y": 1
            },
            {
                "name": "REACONDICIONAR S. EN C",
                "y": 1
            },
            {
                "name": "FE CREACIONES Y MONTAJES S.A.S.",
                "y": 1
            },
            {
                "name": "INVESAKK LIMITADA",
                "y": 1
            },
            {
                "name": "ABCDE MARPAV S.A.S.",
                "y": 1
            },
            {
                "name": "Ramirez Mauricio",
                "y": 1
            },
            {
                "name": "Jorge Ignacio Ocampo Marín ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-10",
        "id": "2019-10-10-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "OTACC S.A.",
                "y": 1
            },
            {
                "name": "MANGUERAS Y SUMINISTROS LTDA.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-11",
        "id": "2019-10-11-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "MANGUERAS CORREAS Y REPARACIONES S.A.S.",
                "y": 1
            },
            {
                "name": "INTERNACIONAL FERRETERA S.A.S",
                "y": 1
            },
            {
                "name": "SUPRA PINTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "ESTRUCTURAS ACERO S.A.S.",
                "y": 1
            },
            {
                "name": "Juan Restrepo",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-12",
        "id": "2019-10-12-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "INGENIERIA CONTROL DE CORROSIONES Y REVESTIMIENTO SAS",
                "y": 1
            },
            {
                "name": "HERRAMIENTAS UNIDAS S.A.",
                "y": 1
            },
            {
                "name": "ALUMINIO NACIONAL S A",
                "y": 1
            },
            {
                "name": "Edier Ávila",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "SCALA BHM S.A.S.",
                "y": 1
            },
            {
                "name": "PALMERA JUNIOR S.A.S.",
                "y": 1
            },
            {
                "name": "FUNDACION CARVAJAL",
                "y": 1
            },
            {
                "name": "Henry Largacha Cardenas",
                "y": 1
            },
            {
                "name": "Jack electricista ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "INVISEÑALES S.A.S",
                "y": 1
            },
            {
                "name": "INGENIERIA CONTROL DE CORROSIONES Y REVESTIMIENTO SAS",
                "y": 1
            },
            {
                "name": "Jhon Jairo del Rio Caro",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "WILSON MANTILLA GIL LTDA.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-19",
        "id": "2019-10-19-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "VYS comercial S.A.S",
                "y": 1
            },
            {
                "name": "Adalberto Perdomo",
                "y": 1
            },
            {
                "name": "Yolima",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-21",
        "id": "2019-10-21-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "ESECO LTDA",
                "y": 1
            },
            {
                "name": "MYM MANEJO DE MATERIALES S.A.S.",
                "y": 1
            },
            {
                "name": "ECO DESARROLLOS PARA LA CONSTRUCCION S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-22",
        "id": "2019-10-22-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "INVISEÑALES S.A.S",
                "y": 1
            },
            {
                "name": "TECNI-VALVULAS TUBERIAS VALVULAS Y ACCESORIOS S.A.S.",
                "y": 1
            },
            {
                "name": "INDUSTRIAS METALICAS VISBAL S.A.S.",
                "y": 1
            },
            {
                "name": "FIMACA COLOMBIA S.A.S.",
                "y": 1
            },
            {
                "name": "PARTEQUIPOS S.A.S.",
                "y": 1
            },
            {
                "name": "Jimmy Marín Cuevas",
                "y": 1
            },
            {
                "name": "Marinela Rios",
                "y": 1
            },
            {
                "name": "Carlos Arboleda",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "RASAL INVERSIONISTAS S.A.S.",
                "y": 1
            },
            {
                "name": "NICCA S.A.S.",
                "y": 1
            },
            {
                "name": "Jorge Rodríguez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "INVERSIONES DEL NORDESTE S A",
                "y": 1
            },
            {
                "name": "SERVICIOS Y MONTAJES SOMOS SAS",
                "y": 1
            },
            {
                "name": "Andres Felipe",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-25",
        "id": "2019-10-25-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "INVISEÑALES S.A.S",
                "y": 1
            },
            {
                "name": "FORMADERAS DE COLOMBIA S.A.S.",
                "y": 1
            },
            {
                "name": "CENTRO DE DIAGNOSTICO AUTOMOTOR DEL VALLE LIMITADA",
                "y": 1
            },
            {
                "name": "CONTENEDORES DE ANTIOQUIA S.A.S",
                "y": 1
            },
            {
                "name": "INDSERCOM S.A.S",
                "y": 1
            },
            {
                "name": "ACEROS FABRICACION & ESTRUCTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "TECNIEQUIGAS INGENIERIA S.A.S.",
                "y": 1
            },
            {
                "name": "Carlos Mauricio Hernandez Rincon",
                "y": 1
            },
            {
                "name": "Alvaro Martinez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-26",
        "id": "2019-10-26-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "Fabio Imitola",
                "y": 1
            },
            {
                "name": "Daniela Samaniego",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "CROIL SERVICIOS E INGENIERIA S.A.S ",
                "y": 1
            },
            {
                "name": "HOSTERIA RIO ESCONDIDO",
                "y": 1
            },
            {
                "name": "Zoraida cedeño",
                "y": 1
            },
            {
                "name": "Fernando Orozco",
                "y": 1
            },
            {
                "name": "MIGUEL ANGEL GOMEZ BUSTAMANTE",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "SITTCA CONSTRUCCIONES S.A.S",
                "y": 1
            },
            {
                "name": "ARMAMETAL S.A.S.",
                "y": 1
            },
            {
                "name": "Edwin Gonzalez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-30",
        "id": "2019-10-30-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "APB ACABADOS CONSTRUCCIONES S.A.S.",
                "y": 1
            },
            {
                "name": "3S INGENIERIA ESPECIALIZADA S.A.S.",
                "y": 1
            },
            {
                "name": "DEO GRATIAS S.A.S.",
                "y": 1
            },
            {
                "name": "ACEROS FABRICACION & ESTRUCTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "PINTURAS INDUSTRIALES LTDA",
                "y": 1
            },
            {
                "name": "TURBOCOSTA S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-31",
        "id": "2019-10-31-eccbc87e4b5ce2fe28308fd9f2a7baf3",
        "data": [
            {
                "name": "CAMCRETO S.A.S",
                "y": 1
            },
            {
                "name": "Manuela Arias",
                "y": 1
            }
        ]
    },
    {
        "name": "Leonardo",
        "id": "e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "2019-10-01",
                "y": 3,
                "drilldown": "2019-10-01-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-02",
                "y": 2,
                "drilldown": "2019-10-02-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-03",
                "y": 2,
                "drilldown": "2019-10-03-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-04",
                "y": 6,
                "drilldown": "2019-10-04-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-07",
                "y": 3,
                "drilldown": "2019-10-07-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-08",
                "y": 1,
                "drilldown": "2019-10-08-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-09",
                "y": 3,
                "drilldown": "2019-10-09-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-10",
                "y": 2,
                "drilldown": "2019-10-10-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-11",
                "y": 4,
                "drilldown": "2019-10-11-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-15",
                "y": 6,
                "drilldown": "2019-10-15-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-16",
                "y": 3,
                "drilldown": "2019-10-16-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-17",
                "y": 1,
                "drilldown": "2019-10-17-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-18",
                "y": 2,
                "drilldown": "2019-10-18-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-21",
                "y": 2,
                "drilldown": "2019-10-21-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-22",
                "y": 4,
                "drilldown": "2019-10-22-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-23",
                "y": 5,
                "drilldown": "2019-10-23-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-24",
                "y": 2,
                "drilldown": "2019-10-24-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-28",
                "y": 6,
                "drilldown": "2019-10-28-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-29",
                "y": 4,
                "drilldown": "2019-10-29-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-30",
                "y": 3,
                "drilldown": "2019-10-30-e4da3b7fbbce2345d7772b0674a318d5"
            },
            {
                "name": "2019-10-31",
                "y": 5,
                "drilldown": "2019-10-31-e4da3b7fbbce2345d7772b0674a318d5"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "SOLUCIONES VIALES A&J S.A.S - SOLVIAL S.A.S.",
                "y": 1
            },
            {
                "name": "FICO FERRETERIAS S A S",
                "y": 1
            },
            {
                "name": "HITOOLS SERVICES SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-02",
        "id": "2019-10-02-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "BIO GRAY CLEANING SAS",
                "y": 1
            },
            {
                "name": "METALPAR S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "WIMAN SOLICIONES S.A.S",
                "y": 1
            },
            {
                "name": "CIPAVI LTDA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "TECNO INDUSTRIALES SAS\t",
                "y": 1
            },
            {
                "name": "INDUSTRIA DE ELECTRODOMESTICOS S.A.S INDUSEL S.A.S",
                "y": 1
            },
            {
                "name": "OBCIPOL LTDA",
                "y": 1
            },
            {
                "name": "TÉCNICOS CALIFICADOS DE COLOMBIA S.A.S.",
                "y": 1
            },
            {
                "name": "DISMEM EQUIPOS LTDA",
                "y": 1
            },
            {
                "name": "Tomás Verbik",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-07",
        "id": "2019-10-07-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "ETERNIT COLOMBIANA S.A.",
                "y": 1
            },
            {
                "name": "Chilco Distribuidora de gas y Energía",
                "y": 1
            },
            {
                "name": "Rafael Vargas ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "SUPERPOLO S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-09",
        "id": "2019-10-09-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "DISTRIBUCIONES TORRES INGENIERÍA SAS",
                "y": 1
            },
            {
                "name": "SUPERPOLO S.A.S.",
                "y": 1
            },
            {
                "name": "OTACC S.A.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-10",
        "id": "2019-10-10-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "DEO GRATIAS S.A.S.",
                "y": 1
            },
            {
                "name": "OME S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-11",
        "id": "2019-10-11-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "WALSOM SAS",
                "y": 1
            },
            {
                "name": "THERMOCHILL S A",
                "y": 1
            },
            {
                "name": "OPENUEVO INVERSIONES S.A.S.",
                "y": 1
            },
            {
                "name": "Pedro Nausa",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "INDUSTRIAL CONCONCRETO S.A.S.",
                "y": 1
            },
            {
                "name": "MANTENIMIENTO Y SEGURIDAD VIAL S A S",
                "y": 1
            },
            {
                "name": "LA FERRETERIA HERRAMIENTAS Y SUMINISTROS S.A.S.",
                "y": 1
            },
            {
                "name": "ALDREZ SAS",
                "y": 1
            },
            {
                "name": "MUNDO COLOR PINTURA",
                "y": 1
            },
            {
                "name": "FARINCO INGENIERÍA S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "VR INGENIERIA Y MERCADEO S.A.S",
                "y": 1
            },
            {
                "name": "SUPERPOLO S.A.S.",
                "y": 1
            },
            {
                "name": "LANDFORT SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-17",
        "id": "2019-10-17-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "LA FERRETERIA HERRAMIENTAS Y SUMINISTROS S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "VR INGENIERIA Y MERCADEO S.A.S",
                "y": 1
            },
            {
                "name": "HIDROSFERA S.A.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-21",
        "id": "2019-10-21-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "EMPRESA DE LICORES DE CUNDINAMARCA",
                "y": 1
            },
            {
                "name": "Mario Rafel Rayon",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-22",
        "id": "2019-10-22-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "ISMOCOL S.A.",
                "y": 1
            },
            {
                "name": "C I UNION DE BANANEROS DE URABA S A. UNIBAN",
                "y": 1
            },
            {
                "name": "S & H IMPORTADORES S A S",
                "y": 1
            },
            {
                "name": "Carlos Alberto Hernandez Bermudez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "SUPERPOLO S.A.S.",
                "y": 1
            },
            {
                "name": "GEOCIVILES S.A.S.",
                "y": 1
            },
            {
                "name": "Leonardo Narvaez Velasco",
                "y": 1
            },
            {
                "name": "Lida Rocio Cruz",
                "y": 1
            },
            {
                "name": "Fredy Villada",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "RAMPINT S.A.S.",
                "y": 1
            },
            {
                "name": "EQUIPMASTER STORE S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "IMPERSINTETICOS LTDA",
                "y": 1
            },
            {
                "name": "WALSOM SAS",
                "y": 1
            },
            {
                "name": "SUMINISTROS INDUSTRIALES DE FERRETERIA SAS",
                "y": 1
            },
            {
                "name": "HANDY EXPRESS SAS",
                "y": 1
            },
            {
                "name": "Carlos davian cuellar Chavez",
                "y": 1
            },
            {
                "name": "Yonathan esteban moreno legro",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "MORKEN COLOMBIA S A S",
                "y": 1
            },
            {
                "name": "CG GRUPO EMPRESARIAL INGENIERIA Y DISEÑOS SAS",
                "y": 1
            },
            {
                "name": "INPAME SAS",
                "y": 1
            },
            {
                "name": "Albeiro Rueda",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-30",
        "id": "2019-10-30-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "TECNO INDUSTRIALES SAS\t",
                "y": 1
            },
            {
                "name": "IMPERSINTETICOS LTDA",
                "y": 1
            },
            {
                "name": "Wendy Lizcano",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-31",
        "id": "2019-10-31-e4da3b7fbbce2345d7772b0674a318d5",
        "data": [
            {
                "name": "ESTAHL INGENIERIA LTDA",
                "y": 1
            },
            {
                "name": "SUPERPOLO S.A.S.",
                "y": 1
            },
            {
                "name": "Concesión Via 40 Express",
                "y": 1
            },
            {
                "name": "TEC-CONS INGENIERIA  S.A.S",
                "y": 1
            },
            {
                "name": "OSCAR FABIAN POLANIA DUSSAN",
                "y": 1
            }
        ]
    },
    {
        "name": "Jaime",
        "id": "8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "2019-10-01",
                "y": 1,
                "drilldown": "2019-10-01-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-02",
                "y": 3,
                "drilldown": "2019-10-02-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-04",
                "y": 2,
                "drilldown": "2019-10-04-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-07",
                "y": 3,
                "drilldown": "2019-10-07-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-08",
                "y": 3,
                "drilldown": "2019-10-08-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-11",
                "y": 6,
                "drilldown": "2019-10-11-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-12",
                "y": 2,
                "drilldown": "2019-10-12-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-15",
                "y": 3,
                "drilldown": "2019-10-15-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-16",
                "y": 1,
                "drilldown": "2019-10-16-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-17",
                "y": 1,
                "drilldown": "2019-10-17-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-18",
                "y": 1,
                "drilldown": "2019-10-18-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-19",
                "y": 1,
                "drilldown": "2019-10-19-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-21",
                "y": 1,
                "drilldown": "2019-10-21-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-24",
                "y": 1,
                "drilldown": "2019-10-24-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-26",
                "y": 1,
                "drilldown": "2019-10-26-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-28",
                "y": 2,
                "drilldown": "2019-10-28-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-29",
                "y": 3,
                "drilldown": "2019-10-29-8f14e45fceea167a5a36dedd4bea2543"
            },
            {
                "name": "2019-10-31",
                "y": 1,
                "drilldown": "2019-10-31-8f14e45fceea167a5a36dedd4bea2543"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "julio cesar piraquive",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-02",
        "id": "2019-10-02-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "TECMO SOCIEDAD ANONIMA - TECMO S.A.",
                "y": 1
            },
            {
                "name": "ARCOMAT S.A.S",
                "y": 1
            },
            {
                "name": "CEMAP S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ARCOMAT S.A.S.",
                "y": 1
            },
            {
                "name": "RCM CONSTRUCCIONES S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-07",
        "id": "2019-10-07-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "Carlos Jorge Ramirez",
                "y": 1
            },
            {
                "name": "Harold Enrique",
                "y": 1
            },
            {
                "name": "BRAYAN CEPEDA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "DRYACUSTIC S.A.S.",
                "y": 1
            },
            {
                "name": "MENZIES AVIATION COLOMBIA S.A.S",
                "y": 1
            },
            {
                "name": "BUGEL STUDIO DISEÑO S.A.S. - BSD S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-11",
        "id": "2019-10-11-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "PROMOAMBIENTAL DISTRITO S.A.S. ESP",
                "y": 1
            },
            {
                "name": "AIRPORT SOLUTIONS  S.A.S",
                "y": 1
            },
            {
                "name": "GVS CONSTRUCCIONES SAS",
                "y": 1
            },
            {
                "name": "GREINSER S.A.S.",
                "y": 1
            },
            {
                "name": "COMPAÑIA 7R S.A.S.",
                "y": 1
            },
            {
                "name": "TUBODRILLING INSPECTION COMPANY S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-12",
        "id": "2019-10-12-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "CONSTRUCCIÓN Y MANTENIMIENTO HOBRE S.A.S",
                "y": 1
            },
            {
                "name": "Hugo",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "CONSTRUTORA PROARQ DAS",
                "y": 1
            },
            {
                "name": "TRAZOS & SEÑALES S.A.S",
                "y": 1
            },
            {
                "name": "JEC MULTIHERRAJES SA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": " Rafael Molina",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-17",
        "id": "2019-10-17-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "COLOMBIA CENTRO DE NEGOCIOS SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "JS SERVIPETROL SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-19",
        "id": "2019-10-19-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "DISTRIBUIDORA DE ARTICULOS TECNICOS JG DISARTEC JG SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-21",
        "id": "2019-10-21-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": " Wilson Rincón",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ARCOMAT S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-26",
        "id": "2019-10-26-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ETALUM S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ARCOMAT S.A.S.",
                "y": 1
            },
            {
                "name": "Angel correa",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ARCOMAT S.A.S.",
                "y": 1
            },
            {
                "name": "PEMSER SOLUTIONS S.A.S",
                "y": 1
            },
            {
                "name": "RENTAK S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-31",
        "id": "2019-10-31-8f14e45fceea167a5a36dedd4bea2543",
        "data": [
            {
                "name": "ETALUM S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "Blanca",
        "id": "6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "2019-10-01",
                "y": 4,
                "drilldown": "2019-10-01-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-02",
                "y": 2,
                "drilldown": "2019-10-02-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-03",
                "y": 4,
                "drilldown": "2019-10-03-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-04",
                "y": 3,
                "drilldown": "2019-10-04-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-05",
                "y": 2,
                "drilldown": "2019-10-05-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-07",
                "y": 2,
                "drilldown": "2019-10-07-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-08",
                "y": 2,
                "drilldown": "2019-10-08-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-09",
                "y": 2,
                "drilldown": "2019-10-09-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-10",
                "y": 5,
                "drilldown": "2019-10-10-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-11",
                "y": 1,
                "drilldown": "2019-10-11-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-12",
                "y": 1,
                "drilldown": "2019-10-12-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-15",
                "y": 5,
                "drilldown": "2019-10-15-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-16",
                "y": 2,
                "drilldown": "2019-10-16-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-17",
                "y": 5,
                "drilldown": "2019-10-17-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-18",
                "y": 2,
                "drilldown": "2019-10-18-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-21",
                "y": 2,
                "drilldown": "2019-10-21-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-22",
                "y": 2,
                "drilldown": "2019-10-22-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-23",
                "y": 5,
                "drilldown": "2019-10-23-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-24",
                "y": 3,
                "drilldown": "2019-10-24-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-25",
                "y": 3,
                "drilldown": "2019-10-25-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-28",
                "y": 1,
                "drilldown": "2019-10-28-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-29",
                "y": 5,
                "drilldown": "2019-10-29-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-30",
                "y": 4,
                "drilldown": "2019-10-30-6512bd43d9caa6e02c990b0a82652dca"
            },
            {
                "name": "2019-10-31",
                "y": 1,
                "drilldown": "2019-10-31-6512bd43d9caa6e02c990b0a82652dca"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "INGEVIAL PYM SAS",
                "y": 1
            },
            {
                "name": " Industrias Mt ",
                "y": 1
            },
            {
                "name": "Carlos Francisco Ordoñez Paris",
                "y": 1
            },
            {
                "name": "MIGUEL ANGEL CORTES GRANADOS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-02",
        "id": "2019-10-02-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "BRAND CENTER SAS",
                "y": 1
            },
            {
                "name": "COMERCIALIZADORA DIDAFER SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "HERRAMIENTAS Y REPUESTOS H&R SAS",
                "y": 1
            },
            {
                "name": "INGENIERIA EN AISLAMIENTO E IMPERMEABILIZACION INGENIA SAS",
                "y": 1
            },
            {
                "name": "STAR PARK DE OCCIDENTE S.A.S",
                "y": 1
            },
            {
                "name": "SEÑALIZACION GEOVIAS SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "IN NOVA PINTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "INGENIERIA EN AISLAMIENTO E IMPERMEABILIZACION INGENIA SAS",
                "y": 1
            },
            {
                "name": "VIDRIOS Y ALUMINIOS VIDRIALUM S.H.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-05",
        "id": "2019-10-05-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SPI SANDBLASTING Y PINTURAS INDUSTRIALES S.A.S.",
                "y": 1
            },
            {
                "name": "SPI SANDBLASTING Y PINTURAS INDUSTRIALES SAS ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-07",
        "id": "2019-10-07-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "AJH MONTAJES S.A.S",
                "y": 1
            },
            {
                "name": "CRISTALERIA DE OCCIDENTE",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "TECNINTEGRAL S A S",
                "y": 1
            },
            {
                "name": "Carlos Eduardo Garzon",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-09",
        "id": "2019-10-09-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "PINTU REALDESA S.A.S",
                "y": 1
            },
            {
                "name": "Cesar Augusto Jerez Berrio",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-10",
        "id": "2019-10-10-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "IGLESIA CRISTIANA DE LOS TESTIGOS DE JEHOVA",
                "y": 1
            },
            {
                "name": "ISMOCOL S.A.",
                "y": 1
            },
            {
                "name": "DIBEL & CIA SA",
                "y": 1
            },
            {
                "name": "ESTRUCTURAS METALICAS PROACERO LTDA",
                "y": 1
            },
            {
                "name": "SOLUCIONES METALURGICAS SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-11",
        "id": "2019-10-11-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "EQUIPING LTDA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-12",
        "id": "2019-10-12-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "CONSTRUCCIONES ORTIZ SEGOVIA SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "GESTION VIAL INTEGRAL S.A.S.",
                "y": 1
            },
            {
                "name": "TWM S.A.S",
                "y": 1
            },
            {
                "name": "TEC MONTAJES S.A.S",
                "y": 1
            },
            {
                "name": "MEDCA URBANISMO S A S",
                "y": 1
            },
            {
                "name": "Cesar Augusto Jerez Berrio",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "INGENIERIA INTEGRIDAD & PROTECCION S.A. IIP",
                "y": 1
            },
            {
                "name": "TECNO INDUSTRIALES ZONA NORTE S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-17",
        "id": "2019-10-17-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "C.P.J. MANTENIMIENTAOS Y REOARACIONES S.A.S",
                "y": 1
            },
            {
                "name": "DLC SOLUCIONES S.A.S.",
                "y": 1
            },
            {
                "name": "HARD ENGINEERING S A S",
                "y": 1
            },
            {
                "name": "EDUARDO CORTES CAICEDO",
                "y": 1
            },
            {
                "name": "JULIAN NIÑO",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "FERRETERIA DICAFER LTDA",
                "y": 1
            },
            {
                "name": "MONTINPETROL S A",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-21",
        "id": "2019-10-21-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SALAMANDRA TEXTIL SAS",
                "y": 1
            },
            {
                "name": "OMAIRA MORENO OSORNO",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-22",
        "id": "2019-10-22-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "TWM S.A.S",
                "y": 1
            },
            {
                "name": "HARD ENGINEERING S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SURAMERICANA DE IMPLEMENTOS INDUSTRIALES LTDA",
                "y": 1
            },
            {
                "name": "OBCIPOL LTDA",
                "y": 1
            },
            {
                "name": "SM INGENIEROS S A S",
                "y": 1
            },
            {
                "name": "ESTUDIO ARQUITECTURA 929 S.A.S",
                "y": 1
            },
            {
                "name": "OMAIRA MORENO OSORNO",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SURAMERICANA DE IMPLEMENTOS INDUSTRIALES LTDA",
                "y": 1
            },
            {
                "name": "SPI SANDBLASTING Y PINTURAS INDUSTRIALES S.A.S.",
                "y": 1
            },
            {
                "name": "Cesar Augusto Jerez Berrio",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-25",
        "id": "2019-10-25-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SURAMERICANA DE IMPLEMENTOS INDUSTRIALES LTDA",
                "y": 1
            },
            {
                "name": "CODCOM SAS",
                "y": 1
            },
            {
                "name": "ASPRO SAS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "Castellanos John Jairo",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "GARCIA VARGAS INGENIEROS LTDA",
                "y": 1
            },
            {
                "name": "IN NOVA PINTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "ERAZO VALENCIA S.A.S.",
                "y": 1
            },
            {
                "name": "PRACTICAL SOLUTIONS COLOMBIA SAS",
                "y": 1
            },
            {
                "name": "SODIMAC COLOMBIA S.A",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-30",
        "id": "2019-10-30-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "SATENA",
                "y": 1
            },
            {
                "name": "COMERCIALIZADORA DE GASES INDUSTRIALES CRYOLIMER S.A.S.",
                "y": 1
            },
            {
                "name": "VISION EXPRESS PUBLICIDAD S A S",
                "y": 1
            },
            {
                "name": "Saavedra Jose",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-31",
        "id": "2019-10-31-6512bd43d9caa6e02c990b0a82652dca",
        "data": [
            {
                "name": "TERMOTECNICA COINDUSTRIAL S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "Kenneth",
        "id": "c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "2019-10-01",
                "y": 1,
                "drilldown": "2019-10-01-c51ce410c124a10e0db5e4b97fc2af39"
            },
            {
                "name": "2019-10-03",
                "y": 1,
                "drilldown": "2019-10-03-c51ce410c124a10e0db5e4b97fc2af39"
            },
            {
                "name": "2019-10-09",
                "y": 1,
                "drilldown": "2019-10-09-c51ce410c124a10e0db5e4b97fc2af39"
            },
            {
                "name": "2019-10-15",
                "y": 1,
                "drilldown": "2019-10-15-c51ce410c124a10e0db5e4b97fc2af39"
            },
            {
                "name": "2019-10-29",
                "y": 3,
                "drilldown": "2019-10-29-c51ce410c124a10e0db5e4b97fc2af39"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "PATRIMONIOS AUTONOMOS FIDUCIARIA BANCOLOMBIA S A SOCIEDAD FIDUCIARIA - DEVIMAR",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "Grupo Aeroportuario del Caribe S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-09",
        "id": "2019-10-09-c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "MOVICON S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "C7R S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-c51ce410c124a10e0db5e4b97fc2af39",
        "data": [
            {
                "name": "COMETALICAS CONSTRUCCIONES METALICAS S,A,S,..",
                "y": 1
            },
            {
                "name": "INVERSIONES ULTRAMAR S.A.I. S.A.S.",
                "y": 1
            },
            {
                "name": "Mauricio Ramirez",
                "y": 1
            }
        ]
    },
    {
        "name": "Felipe",
        "id": "642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "2019-10-04",
                "y": 2,
                "drilldown": "2019-10-04-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-08",
                "y": 1,
                "drilldown": "2019-10-08-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-10",
                "y": 2,
                "drilldown": "2019-10-10-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-16",
                "y": 2,
                "drilldown": "2019-10-16-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-18",
                "y": 2,
                "drilldown": "2019-10-18-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-19",
                "y": 1,
                "drilldown": "2019-10-19-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-24",
                "y": 1,
                "drilldown": "2019-10-24-642e92efb79421734881b53e1e1b18b6"
            },
            {
                "name": "2019-10-28",
                "y": 2,
                "drilldown": "2019-10-28-642e92efb79421734881b53e1e1b18b6"
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "RA CONTRUDRYWALL SAS.",
                "y": 1
            },
            {
                "name": "INNOVACIÓN PROTECCIÓN Y COLOR S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-08",
        "id": "2019-10-08-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "SOLIMET S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-10",
        "id": "2019-10-10-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "CONCRE-ACERO S.A.S.",
                "y": 1
            },
            {
                "name": "FESMET S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "VALSATEX S.A",
                "y": 1
            },
            {
                "name": "Santiago Muñoz",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "TECNOPINTURAS S.A.S.",
                "y": 1
            },
            {
                "name": "Flexco S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-19",
        "id": "2019-10-19-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "USUGA OBRA BLANCA S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "EDGAR ALONSO ZAPATA ARANGO S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-642e92efb79421734881b53e1e1b18b6",
        "data": [
            {
                "name": "CIELOTEK IMPERMEABILIZACION S.A.S.",
                "y": 1
            },
            {
                "name": "CONSTRUCTORA ECAPICON S.A.S.",
                "y": 1
            }
        ]
    },
    {
        "name": "Margarita",
        "id": "f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "2019-10-01",
                "y": 1,
                "drilldown": "2019-10-01-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-03",
                "y": 2,
                "drilldown": "2019-10-03-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-04",
                "y": 1,
                "drilldown": "2019-10-04-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-15",
                "y": 2,
                "drilldown": "2019-10-15-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-16",
                "y": 1,
                "drilldown": "2019-10-16-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-17",
                "y": 4,
                "drilldown": "2019-10-17-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-18",
                "y": 1,
                "drilldown": "2019-10-18-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-22",
                "y": 1,
                "drilldown": "2019-10-22-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-23",
                "y": 1,
                "drilldown": "2019-10-23-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-24",
                "y": 1,
                "drilldown": "2019-10-24-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-25",
                "y": 1,
                "drilldown": "2019-10-25-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-28",
                "y": 2,
                "drilldown": "2019-10-28-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-29",
                "y": 2,
                "drilldown": "2019-10-29-f457c545a9ded88f18ecee47145a72c0"
            },
            {
                "name": "2019-10-30",
                "y": 1,
                "drilldown": "2019-10-30-f457c545a9ded88f18ecee47145a72c0"
            }
        ]
    },
    {
        "name": "2019-10-01",
        "id": "2019-10-01-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "Felipe Almanza",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-03",
        "id": "2019-10-03-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "DISTRIELECTRICOS JM Y CIA LTDA",
                "y": 1
            },
            {
                "name": "Julian Andres  Portillo",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-04",
        "id": "2019-10-04-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "OLGA LUCÍA ESPINOSA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-15",
        "id": "2019-10-15-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "JESUS HERNANDEZ",
                "y": 1
            },
            {
                "name": "KETTY JOHANNA CORONADO",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-16",
        "id": "2019-10-16-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "CONSERVACIÓN INTERNACIONAL COLOMBIA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-17",
        "id": "2019-10-17-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "María Catalina ocampo Barreto",
                "y": 1
            },
            {
                "name": "MARCO HERNÁNDEZ",
                "y": 1
            },
            {
                "name": "OSCAR MAURICIO TOBAR ",
                "y": 1
            },
            {
                "name": "LUZ MARINA GRANADOS",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-18",
        "id": "2019-10-18-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "ELIANA WITTINGHAM",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-22",
        "id": "2019-10-22-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "ASESORES PROFESIONALES EN INSTRUMENTACION Y CONTROL LTDA",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-23",
        "id": "2019-10-23-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "DIANA HERNÁNDEZ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-24",
        "id": "2019-10-24-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "SANDRA GOMEZ",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-25",
        "id": "2019-10-25-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "YUDI GARZON",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-28",
        "id": "2019-10-28-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "DYALCO CNC LTDA",
                "y": 1
            },
            {
                "name": "INTECOL S.A.S",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-29",
        "id": "2019-10-29-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "SIRIORED SAS",
                "y": 1
            },
            {
                "name": "Adolfredo Rodriguez",
                "y": 1
            }
        ]
    },
    {
        "name": "2019-10-30",
        "id": "2019-10-30-f457c545a9ded88f18ecee47145a72c0",
        "data": [
            {
                "name": "ANDINA DE TRANSMISIONES SAS",
                "y": 1
            }
        ]
    }
]
        }
    });
});
</script>