function initialize() {
	var input = document.getElementById('PqrCity');
	var autocomplete = new google.maps.places.Autocomplete(input);
}
google.maps.event.addDomListener(window, 'load', initialize);


window.Parsley.addValidator('fileextension', function (value, requirement) {
    var fileExtension = value.split('.').pop();  
    fileExtension = fileExtension.toLowerCase();          
    var extenciones_archivos = ["pdf","png","jpg","jpeg"];
    console.log(extenciones_archivos.indexOf(fileExtension))
    return extenciones_archivos.indexOf(fileExtension) == -1 ? false : true;

}, 32)
.addMessage('es', 'fileextension', 'La extención del archivo debe ser PDF, PNG o JPG');