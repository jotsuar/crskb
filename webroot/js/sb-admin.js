(function($) {
  "use strict";
  
  $('.navbar-sidenav [data-toggle="tooltip"]').tooltip({
    template: '<div class="tooltip navbar-sidenav-tooltip" role="tooltip" style="pointer-events: none;"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
  })

  //$("#sidenavToggler").click(function(e) {
    //e.preventDefault();
    //$("body").toggleClass("sidenav-toggled");
    //$(".navbar-sidenav .nav-link-collapse").addClass("collapsed");
    //$(".navbar-sidenav .sidenav-second-level, .navbar-sidenav .sidenav-third-level").removeClass("show");
    //$('.brandmin').toggleClass("muestra");
    //$('.brandbig').toggleClass("oculta");
    //$('.objetct').toggleClass("ml");
    //$('.databuy').toggleClass("mindatabuy");
 // });

  if ($("body").hasClass("Quotationsview")) {
    $("body").removeClass("bg-dark");
  }

  $('.resetfont').find('p br').remove();
  $('.bordespan').find('p br').remove();

  $('.resetfont p').each(function(){
    $(this).html($(this).html().replace(/&nbsp;/gi,''));
  });

  if ($(window).width() < 1580) {
    $('li.nav-item.dashboard').hover(function () {$(".hoverdashboard").show();},function () {$(".hoverdashboard").hide();});
    $('li.nav-item.miperfil').hover(function () {$(".hoverperfil").show();},function () {$(".hoverperfil").hide();});
    $('li.nav-item.templates.minmenu').hover(function () {$(".hoverplantillas").show();},function () {$(".hoverplantillas").hide();});
    $('li.nav-item.pagos.minmenu').hover(function () {$(".hoverpagos").show();},function () {$(".hoverpagos").hide();});
    $('li.nav-item.productos.minmenu').hover(function () {$(".hoverproductos").show();},function () {$(".hoverproductos").hide();});
    $('li.nav-item.cotizaciones.minmenu').hover(function () {$(".hovercotizacionesenviadas").show();},function () {$(".hovercotizacionesenviadas").hide();});
    $('li.nav-item.flujos.minmenu').hover(function () {$(".hovermisflujos").show();},function () {$(".hovermisflujos").hide();});
    $('li.nav-item.personasnaturales.minmenu').hover(function () {$(".hovernaturales").show();},function () {$(".hovernaturales").hide();});
    $('li.nav-item.personasjuridicas.minmenu').hover(function () {$(".hoverjuridicas").show();},function () {$(".hoverjuridicas").hide();});
    $('li.nav-item.usuarios.minmenu').hover(function () {$(".hoverusuarios").show();},function () {$(".hoverusuarios").hide();});
    $('li.nav-item.manages').hover(function () {$(".hoveragenda").show();},function () {$(".hoveragenda").hide();});
    $('li.nav-item.gmail').hover(function () {$(".hovergmail").show();},function () {$(".hovergmail").hide();});
    $('li.nav-item.serviciotecnico.minmenu').hover(function () {$(".hoverserviciotecnico").show();},function () {$(".hoverserviciotecnico").hide();});
    $('li.nav-item.despachos.minmenu').hover(function () {$(".hoverdespachos").show();},function () {$(".hoverdespachos").hide();});
    $('li.nav-item.informes.minmenu').hover(function () {$(".hoverinformes").show();},function () {$(".hoverinformes").hide();});
  }


  $(".navbar-sidenav .nav-link-collapse").click(function(e) {
    e.preventDefault();
    $("body").removeClass("sidenav-toggled");
  });

  $('body.fixed-nav .navbar-sidenav, body.fixed-nav .sidenav-toggler, body.fixed-nav .navbar-collapse').on('mousewheel DOMMouseScroll', function(e) {
    var e0 = e.originalEvent,
    delta = e0.wheelDelta || -e0.detail;
    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
    e.preventDefault();
  });

  // $(document).scroll(function() {
  //   var fixed = $(".dataaproba");
  //   var scrollDistance = $(this).scrollTop();
  //   if (scrollDistance > 100) {
  //     $('.scroll-to-top').fadeIn();
  //      fixed.fadeIn("slow").addClass("fijascroll");
  //   } else {
  //     $('.scroll-to-top').fadeOut();
  //      fixed.fadeIn("slow").removeClass("fijascroll");
  //   }
  // });

  $(".close_message").click(function(){
    $(this).parent().parent().remove();
  });

  $('[data-toggle="tooltip"]').tooltip()

  $(document).on('click', 'a.scroll-to-top', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    event.preventDefault();
  });

})(jQuery);

window.onload = function() {
  $(".body-loading").delay(100).fadeOut("slow");
  $(".body-loading-mail").delay(4000).fadeOut("slow");
}

$(window).resize(function() {

  if ($(this).width() < 1024) {

    $('.loopindex').hide();

  } 
});