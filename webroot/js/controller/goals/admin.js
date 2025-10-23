function calculateGoal(){
	var totalGoal = 0;
	$(".goalClass").each(function(index, el) {
		totalGoal += parseFloat($(this).val());
	});
	$("#totalGoal").html(new Intl.NumberFormat().format(totalGoal));
}

calculateGoal();

$(".goalClass").change(function(event) {
	calculateGoal();
});

$(".goalClass").keypress(function(event) {
	calculateGoal();
});