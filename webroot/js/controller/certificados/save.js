$("body").on('change', '#CertificadoName, #CertificadoIdentification, #CertificadoCourse,#CertificadoCityDate', function(event) {
	event.preventDefault();
	changeImgData();
});

function changeImgData(){
	var URL_CERT = $("#imgCert").attr("data-url");
	var nombre = $("#CertificadoName").val();
	var identification = $("#CertificadoIdentification").val();
	var course = $("#CertificadoCourse").val();
	var city_date = $("#CertificadoCityDate").val();


	var myData = {nombre,identification,course,city_date};
	var URLFINAL = URL_CERT+"?" + $.param(myData);
	$("#imgCert").attr('src',URLFINAL);
}