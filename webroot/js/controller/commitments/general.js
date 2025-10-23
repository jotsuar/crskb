function validateCatsForUser(){
	$("#CommitmentCatId").html("")
	var user_id = $("#CommitmentUserId").val();
	$.post(copy_js.base_url+'cats/get_cats_user',{user_id}, function(result){
        $("#CommitmentCatId").html(result)
    });
}

if ($("#CommitmentUserId").length) {
	validateCatsForUser();
}

$("#CommitmentUserId").change(function(event) {
	validateCatsForUser();
});

if ($("#CommitmentDeadline").length) {

	$('#CommitmentDeadline').datetimepicker({
        "allowInputToggle": true,
        "timeZone" : "America/Bogota",
        "collapse": false,
        "inline": true,
        "showClose": true,
        "showClear": false,
        "showTodayButton": true,
        "format": "MM/DD/YYYY HH:mm:ss",
    });

    setTimeout(function() {
    	$(".glyphicon-remove").click()
    }, 1000);

}