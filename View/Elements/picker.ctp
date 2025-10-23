<?php 
    $this->start('AppScript'); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php 

    $whitelist = array(
            '127.0.0.1',
            '::1'
        );
 ?>
<script>

    var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
    var actual_url = "<?php echo !in_array($_SERVER['REMOTE_ADDR'], $whitelist) ? Router::url($this->here,true) : $this->here ?>?";

    function URLToArray(url) {
        var request = {};
       
        var pairs = url.substring(url.indexOf('?') + 1).split('&');
        if(pairs.length == 1){
            return request;
        }
        console.log(pairs.length)
        for (var i = 0; i < pairs.length; i++) {
            if(!pairs[i])
                continue;
            var pair = pairs[i].split('=');

            if(actual_url != decodeURIComponent(pair[0])+"?" && actual_url != decodeURIComponent(pair[0])){
                request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);         
            }
        }
        return request;

    }

    
    labelCancel = $(".fechaFiltroFlujos").length ? "Borrar" : "Cancelar";

	$('#fechasInicioFin').daterangepicker({
        "showDropdowns": false,
        "opens": "center",
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Último año': [moment().subtract(365, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment()],
            'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Dos meses atrás': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
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
         "startDate": "<?php echo isset($fechaInicioReporte) ? $fechaInicioReporte : date("Y-m-d"); ?>",
         "endDate": "<?php echo isset($fechaFinReporte) ? $fechaFinReporte : date("Y-m-d"); ?>",
        // "maxDate": "<?php echo date("Y-m-d") ?>"
    }, function(start, end, label) {

        if($(".fechaFiltroFlujos").length){
            var actual_query        =  URLToArray(actual_uri);
            actual_query["fechaInicioReporte"] = start.format('YYYY-MM-DD');
            actual_query["fechaFinReporte"] = end.format('YYYY-MM-DD');

            if($("#filterEtapa").length){
                actual_query["filterEtapa"] = $("#filterEtapa").val();
            }
            if($("#filterAsesores").length){
                actual_query["filterAsesores"] = $("#filterAsesores").val();
            }

            location.href = actual_url+$.param(actual_query);
        }
    	$("#input_date_inicio,#input_date_inicio_empresa").val(start.format('YYYY-MM-DD'));
    	$("#input_date_fin,#input_date_fin_empresa").val(end.format('YYYY-MM-DD'));

        if($("#btn_find_adviser").length){
            $("#btn_find_adviser").trigger('click')
        }

        if($("#btn_buscar_datos_empresa").length){
            $("#btn_buscar_datos_empresa").trigger('click')
        }

        if($(".rangofechas").find("#btn_buscar").length){
            $(".rangofechas").find("#btn_buscar").trigger('click');
        }

    });


    setAndDataPicker("#fechasInicioFin3","#btn_find_adviser_3","#input_date_inicio3","#input_date_fin3",copy_js.base_url+"pages/get_flujos",function(data){
        $("#dataFlujoCS").html(data)
        setDataFlowsCS()
    },true,{type:'flujo'});

    $("body").on('change', '#typeFlujoFilter', function(event) {
        $("#btn_find_adviser_3").trigger('click');
    });

    setAndDataPicker("#fechasInicioFin4","#btn_find_adviser_4","#input_date_inicio4","#input_date_fin4",copy_js.base_url+"pages/datos_geograficos",function(data){
        dataGeographic( data )
    });
    setAndDataPicker("#fechasInicioFin5","#btn_find_adviser_5","#input_date_inicio5","#input_date_fin5",copy_js.base_url+"pages/productosPanel",function(data){
        $("#dataProductsCS").html(data)
        setDataProductsCS()
    },false);
    if ($("#dataProductsCS").length) {
        setTimeout(function() {
            setDataProductsCS()
        }, 5000);
    }

    if($("#fechasInicioFin2").length){


        // ****************************
        var date = moment(); //Get the current date
        date.format("YYYY-MM-DD");
        $('#fechasInicioFin2').daterangepicker({
            "showDropdowns": false,
            "opens": "center",
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Último año': [moment().subtract(365, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment()],
                'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],                
                'Dos meses atrás': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
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
             "startDate": date,
             "endDate": date,
            "maxDate": date
        }, function(start, end, label) {

        
            $("#input_date_inicio2").val(start.format('YYYY-MM-DD'));
            $("#input_date_fin2").val(end.format('YYYY-MM-DD'));

            if($("#btn_find_adviser_2").length){
                $("#btn_find_adviser_2").trigger('click')
            }

        });

        // ****************************

        $("#btn_find_adviser_2").on('click', function(event) {
            event.preventDefault();
            var ini = $("#input_date_inicio2").val();
            var end = $("#input_date_fin2").val();
            var id = $(this).data("id");
            <?php if (AuthComponent::user("role") == "Asesor Externo") : ?>
                $.post(copy_js.base_url+"pages/data_count_ajax", {ini,end,user_id: <?php echo AuthComponent::user("id") ?> }, function(data) {
                    $("#dataCountChange").html(data)
                });
            <?php else: ?>
                $.post(copy_js.base_url+"pages/data_count_ajax", {ini,end}, function(data) {
                    $("#dataCountChange").html(data)
                });
            <?php endif; ?>
        });

        $("#btn_find_adviser_2").trigger("click");

    }

    if($("#fechasInicioFin25").length){

        var date = moment(); //Get the current date
        date.format("YYYY-MM-DD");
        // ****************************
        
        $('#fechasInicioFin25').daterangepicker({
            "showDropdowns": false,
            "opens": "center",
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Último año': [moment().subtract(365, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment()],
                'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Dos meses atrás': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
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
             "startDate": date,
             "endDate": date,
            "maxDate": date
        }, function(start, end, label) {

        
            $("#input_date_inicio_empresa_adviser_report").val(start.format('YYYY-MM-DD'));
            $("#input_date_fin_empresa_adviser_report").val(end.format('YYYY-MM-DD'));

            if($("#btn_buscar_datos_empresa_home_report").length){
                $("#btn_buscar_datos_empresa_home_report").trigger('click')
            }

            

        });

    }

if($(".fechaFiltroFlujos").length){
    
    console.log("si existe")
    $('#fechasInicioFin').on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancelar")
        var actual_query        =  URLToArray(actual_uri);
        delete actual_query.fechaInicioReporte;
        delete actual_query.fechaFinReporte;
        location.href = actual_url+$.param(actual_query);
    });

}

$("body").on('click', '.deleteFilters', function(event) {
    event.preventDefault();
    location.href = actual_url
});
    
</script>

<?php
    $this->end();
 ?>
