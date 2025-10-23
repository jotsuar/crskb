if ($(".timeRemainingTxt").length) {
	$(".timeRemainingTxt").each(function(index, el) {
		let prevID = $(this).data("id");
		let deadline = $(this).data("deadline");
		setTimeClock(deadline,prevID);
		setInterval(setTimeClock, 1000,deadline,prevID);
	});
}else{
	console.log("no existe")
}

function setTimeClock(deadline, idDisplay){
	let DATE_TARGET = new Date(deadline);
	// DOM for render
	let SPAN_DAYS = document.querySelector('span#'+idDisplay+'_days');
	let SPAN_HOURS = document.querySelector('span#'+idDisplay+'_hours');
	let SPAN_MINUTES = document.querySelector('span#'+idDisplay+'_minutes');
	let SPAN_SECONDS = document.querySelector('span#'+idDisplay+'_seconds');

	// Milliseconds for the calculations
	let MILLISECONDS_OF_A_SECOND = 1000;
	let MILLISECONDS_OF_A_MINUTE = MILLISECONDS_OF_A_SECOND * 60;
	let MILLISECONDS_OF_A_HOUR = MILLISECONDS_OF_A_MINUTE * 60;
	let MILLISECONDS_OF_A_DAY = MILLISECONDS_OF_A_HOUR * 24;

	let NOW = new Date()
    let DURATION = DATE_TARGET - NOW;
    let REMAINING_DAYS = Math.floor(DURATION / MILLISECONDS_OF_A_DAY);
    let REMAINING_HOURS = Math.floor((DURATION % MILLISECONDS_OF_A_DAY) / MILLISECONDS_OF_A_HOUR);
    let REMAINING_MINUTES = Math.floor((DURATION % MILLISECONDS_OF_A_HOUR) / MILLISECONDS_OF_A_MINUTE);
    let REMAINING_SECONDS = Math.floor((DURATION % MILLISECONDS_OF_A_MINUTE) / MILLISECONDS_OF_A_SECOND);
    // Thanks Pablo Monteserín (https://pablomonteserin.com/cuenta-regresiva/)

    // Render
    SPAN_DAYS.textContent = REMAINING_DAYS;
    SPAN_HOURS.textContent = REMAINING_HOURS;
    SPAN_MINUTES.textContent = REMAINING_MINUTES;
    SPAN_SECONDS.textContent = REMAINING_SECONDS;

}


$(".gestionBtn").click(function(event) {
	event.preventDefault();
	var id = $(this).data("id");
	$(".body-loading").show();
	$.post(copy_js.base_url+'prospective_users/gestionar_flujo',{flujo_id: id }, function(result){
		$("#cuerpoGestion").html(result);
		$("#ProgresNoteImage").dropify(optionsDropify); 
        $("#ProgresNoteUpdateFlowGestForm").parsley();
		$("#modalGestion").modal("show");
		$(".body-loading").hide();
    });
});

$(".viewQuotationData").click(function(event) {
	var clase = "."+$(this).data("clase");
	setTimeout(function() {$(clase).removeClass('hidden');}, 10000);
});

$(".sendEmail").click(function(event) {
	event.preventDefault();
	const id 		= $(this).data("id");
	const quotation = $(this).data("quotation");

	$.get(copy_js.base_url+'prospective_users/gest_mail', {id,quotation}, function(data, textStatus, xhr) {
		$("#cuerpoCorreo").html(data);
		$("#modalCorreo").modal('show');
		$("#sendMailGest").parsley();


		$("#contenidoGestMail").summernote(
			{
				height: 150,
				toolbar: [
				['style', ['bold', 'italic', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['misc', ['undo', 'redo','codeview']],
				['link', ['linkDialogShow', 'unlink']]
				],
				fontNames: ['Arial', 'Arial Black', 'Comic Sans MS'],
				focus: false,
				disableResizeEditor: true,
				disableDragAndDrop: true
			}
			);

	});

});