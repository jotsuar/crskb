$('body').find("#BlogDescription").summernote(
    {
      height: 440,
      disableResizeEditor: false,
    }
);

if ( $("#BlogAddForm .dropify").length ) {
  $("#BlogAddForm .dropify").dropify(optionsDropify)
}
if ( $("#BlogEditForm .dropify").length ) {
  $("#BlogEditForm .dropify").dropify(optionsDropify)
}

if ( $(".gallery").length ) {
  $('.gallery').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'iframe',
        gallery: {
          enabled:true
        },
         removalDelay: 500,

          // Class that is added to popup wrapper and background
          // make it unique to apply your CSS animations just to this exact popup
        mainClass: 'mfp-with-zoom',
        zoom: {
            enabled: true, // By default it's false, so don't forget to enable it

            duration: 300, // duration of the effect, in milliseconds
            easing: 'mfp-newspaper', // CSS transition easing function

            // The "opener" function should return the element from which popup will be zoomed in
            // and to which popup will be scaled down
            // By defailt it looks for an image tag:
            opener: function(openerElement) {
              // openerElement is the element on which popup was initialized, in this case its <a> tag
              // you don't need to add "opener" option if this code matches your needs, it's defailt one.
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        },
        callbacks: {
            open: function() {
                setTimeout(function() {
                    var iframe = $("body").find("#iframe1")[0];
                    var cssLink = document.createElement("link");
                    cssLink.href = rootUrl+"css/styleIframe.css"; 
                    cssLink.rel = "stylesheet"; 
                    cssLink.type = "text/css"; 
                    iframe.contentWindow.document.head.appendChild(cssLink);

                }, 300);
            },
            change: function() {
                setTimeout(function() {
                    var iframe = $("body").find("#iframe1")[0];
                    var cssLink = document.createElement("link");
                    cssLink.href = rootUrl+"css/styleIframe.css"; 
                    cssLink.rel = "stylesheet"; 
                    cssLink.type = "text/css"; 
                    iframe.contentWindow.document.head.appendChild(cssLink);

                }, 300);
            },
            // e.t.c.
          }

    });
});
}