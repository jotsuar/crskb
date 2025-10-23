function initResponsive(){
	var showMenuOrder = localStorage.getItem("showMenuOrder");
	if(showMenuOrder == null ){
		localStorage.setItem("showMenuOrder", 1);
		showMenuOrder = 1;
	}
	console.log(showMenuOrder);
	if(showMenuOrder == 1){
		$(".showMenuOpt").hide();
		$(".hideMenuOpt").show();
		$(".subpmenu").show();
	}else{
		$(".showMenuOpt").show();
		$(".hideMenuOpt").hide();
		$(".subpmenu").hide();
	}
}

initResponsive();

$("#showHideMenuOrder").click(function(event) {
	event.preventDefault();
	var showMenuOrder = localStorage.getItem("showMenuOrder");
	localStorage.setItem("showMenuOrder", showMenuOrder == 1 ? 0 : 1);
	initResponsive();
});