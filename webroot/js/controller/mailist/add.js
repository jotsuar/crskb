$("body").find('#MailingListNumbers').tagsinput();

$("body").on('change', '#MailingListArchivo', function(event) {
	if(this.files.length > 0){  
    	var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
        if (regex.test($("#MailingListArchivo").val().toLowerCase())) {
            if (typeof (FileReader) != "undefined") {
            	var numeros = [];
                var reader = new FileReader();
                reader.onload = function (e) {
                    var table = $("<table />");
                    var rows = e.target.result.split("\n");
                    for (var i = 0; i < rows.length; i++) {
                        var row = $("<tr />");
                        var cells = rows[i].split(",");
                        celssData = $.trim(cells[0].replace('"',""));
                        celssData = $.trim(celssData.replace('"',""));
                        celssData = $.trim(celssData.replace('+',""));
                        if(celssData != ""){
                        	$('#MailingListNumbers').tagsinput('add', celssData);
                        	// numeros.push(celssData);
                        }
                    }
                    // var actualData = $("#MailingListNumbers").val();
                    // $("#MailingListNumbers").val(actualData+numeros.toString())
                    // destroytags();
                }
                reader.readAsText($("#MailingListArchivo")[0].files[0]);
            } else {
            	 message_alert("El navegador no soporta esta función","error");
            }
        } else {
           message_alert("El archivo no es un CSV","error");
        }
    }
});
