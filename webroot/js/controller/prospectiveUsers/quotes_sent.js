$('body').bind('keypress', function(e) {
    if(e.keyCode == 13){
    	buscadorFiltro();
    }
});

$(".btn_buscar").click(function() {
	buscadorFiltro();
});

function buscadorFiltro(){
	var texto 							= $('#txt_buscador').val();
	if (texto != '') {
		var hrefURL 					= copy_js.base_url+copy_js.controller+'/'+copy_js.action;
		var hrefFinal 					= hrefURL+"?q="+texto;
		location.href 					= hrefFinal;
	}
}

$("#texto_busqueda").click(function() {
	location.href = copy_js.base_url+copy_js.controller+'/'+copy_js.action;
});