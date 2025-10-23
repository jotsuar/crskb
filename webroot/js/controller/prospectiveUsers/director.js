if (postFilter == 1) {
    var end = $("#input_date_fin").val();
    $('#serviciosTbl').DataTable({
        ajax: rootUrl + 'prospective_users/get_data_services?end='+end,
        'iDisplayLength': 20,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ],
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [20,50, 100, -1], [20,50, 100, "Todos"] ]
    });      
}
