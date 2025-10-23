$(".carpetaLi").click(function(event) {
	var url = $(this).children('a').attr("href");
	location.href = url;
});

if ( $("#DocumentFile").length ) {
	$("#DocumentFile").dropify(optionsDropify);
	$("#DocumentImage").dropify(optionsDropify);
}