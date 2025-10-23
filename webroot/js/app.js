var ModalNotify = {
    blockReload:function(){
        document.addEventListener("keydown", (e) => {
          if (e.keyCode === 116) {
            e.preventDefault();
          }
        });
    },
    prepareEvents: function(){
        $("body").on('click', "#notifiModalContent a.cancel, #notifiModalContent button.cancel", function(event) {
            event.preventDefault();
            ModalNotify.destroy();
            ModalNotify.hide();
        });
    },
    init: function(){
        ModalNotify.modal('show');
    },
    show: function(title = '', content = '', showButtons = null){
        if (title != '') {
            ModalNotify.title(title);
        }
        if (content != '') {
            ModalNotify.content(content);
        }
        ModalNotify.modal('show');
        if (showButtons != null) {
            ModalNotify.showButtons(showButtons);
            ModalNotify.prepareEvents();
        }
    },
    hide: function(){
        ModalNotify.modal('hide')
    },
    destroy: function(){
        ModalNotify.title();
        ModalNotify.content();
        ModalNotify.footer();
    },
    footer: function(footerText = ''){
        $("#buttonsAdd").html(footerText);
    },
    showButtons: function(show = true){
        if(show){
            $("#notifiModalContent a.cancel").show();
            $("#notifiModalContent button.cancel").show();
        }else{
            $("#notifiModalContent a.cancel").hide();
            $("#notifiModalContent button.cancel").hide();
        }
        ModalNotify.prepareEvents();
    },
    title: function(titleText = ''){
        $("#titleNotify").html(titleText);
    },
    content: function(htmlContent = ''){
        $("#contentNotify").html(htmlContent)
    },
    modal: function(action = 'show', showButtons = true, callback = null) {
        if(action == 'show' || action == 'showLg'){
            $("#notifyModalBackround").css("display","block");
            $("#notifiModalContent").css("display","block");
            if (action == 'showLg') {
                $("#notifiModalContent").addClass("modal-lg4");
            }else{
                $("#notifiModalContent").removeClass("modal-lg4");
            }
            ModalNotify.showButtons(showButtons)
            ModalNotify.blockReload();
        }
        if(action == 'hide'){
            $("#notifyModalBackround").fadeOut();
            $("#notifiModalContent").fadeOut();
        }
        if (callback != null) {
            callback();
        }
    }
};

const optionsDropify = {
    messages: {
        'default': 'Seleccione o arrastre el archivo',
        'replace': 'Seleccione o arrastre el archivo',
        'remove':  'Remover',
        'error':   'Error al cargar el archivo'
    },
    error: {
        'fileSize': 'El archivo supera el tamaño permitido de: ({{ value }}).',
        'fileExtension': 'El formato del archivo seleccionado no es permitido (Permitidos: {{ value }} ).'
    }
};


const broadcastChannel = new BroadcastChannel('miCanal');

window.broadcastChannel = broadcastChannel;

$("body").on('click', '.changeState, .delete-action', function(event) {
    var element = $(this);
    event.preventDefault();
    swal({
        title: "¿Desea continuar con la acción?",
        text: "¿Desea cambiar el estado?",
        type: "warning",
        input: "text",
        showCancelButton: true,
        content: "input",
        confirmButtonColor: "#5d5386",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            var url = element.attr("href");
            if(element.attr('data-controller') != undefined && element.attr('data-action') != undefined) {
                url+="?controller="+element.attr('data-controller')+"&action="+element.attr('data-action');
            }
            if (element.attr('data-id')) {
                url+="&id="+element.attr("data-id");
            }
            location.href = url;
        }
    });
});

if (typeof $ == "undefined") {
    document.write('<script type="text/javascript" src="'+copy_js.base_url+'js/lib/jquery-3.0.0.js">')
    document.write('</script>')
}

window.onload = function () {
     document.getElementById("loaderKebco").style.display  = "none !important"; 
     $('.fc-toolbar.fc-header-toolbar').addClass('row col-lg-12');
};

// add the responsive classes when navigating with calendar buttons
$(document).on('click', '.fc-button', function(e) {
    $('.fc-toolbar.fc-header-toolbar').addClass('row col-lg-12');
});

function visualizarComentario(){
    $("body").find(".nota").hover(function(){
          $(this).find(".text_nota").fadeIn("fast");
          $(this).find(".flechagiro").fadeIn("fast");
      }, function(){
          $(this).find(".text_nota").fadeOut("fast");
          $(this).find(".flechagiro").fadeOut("fast");
    });
}

function strip_tags(str) {
    str = str.toString();
    str = str.replace(/<\/?[^>]+>/gi, '');
    return str.trim();
}


$(document).ready(function () {
    $('[data-toggle="popover"]').popover()
    $("body").find(".nota").hover(function(){
          $(this).find(".text_nota").fadeIn("fast");
          $(this).find(".flechagiro").fadeIn("fast");
      }, function(){
          $(this).find(".text_nota").fadeOut("fast");
          $(this).find(".flechagiro").fadeOut("fast");
    });    

    $("#toggleMenu2").click(function(event) {
        event.preventDefault();
        $(".body-loading").show();
        $("#menuData").toggle("fast");
        setTimeout(function() {
            $(".body-loading").hide();
        }, 1000);
    });

    if (copy_js.user_role == "Asesor Externo" && (copy_js.controller.toLowerCase() == "clientslegals" || copy_js.controller.toLowerCase() == "clientsnaturals" || copy_js.controller.toLowerCase() == "products" ) ) {
        console.log("Eres asesor");
        $( "body" ).on( "copy cut paste drop", function() {
            return false;
        });
    }

    if ($("body").hasClass('nav-md') && $(window).width() >= 1024 ) {
        $("body").removeClass('nav-md');
        $("body").addClass('nav-sm')
        setTimeout(function() {
            $(".child_menu").hide();
        }, 1000);
    }

if ($(window).width() >= 1024) {

    $("#navleft").hover(function(){
          $(this).stop().removeClass("itemsopen");
          $(".verticalinfo").stop().fadeOut(100);
          $(".reduce").stop().fadeIn(100);
          $(".widget-panel-menu h2").stop().fadeIn(100);
          $(".widget-panel-menu div").stop().fadeIn(100);
          $(".widget-panel-menu i").stop().css("background","transparent");
          $(".widget-panel-menu i").stop().css("display","initial");
          $("#navleft").stop().animate({ width:'250px'}, 100);
          $("#mainNav.fixed-top .sidenav-toggler > .nav-item").stop().animate({ width:'250px'}, "fast");
          $("footer.sticky-footer").stop().css("width","calc(100% - 250px)");
          $(".databuy").stop().animate({ width:'250px'}, 100);
          $(".brandbar img").stop().animate({ width:'250px'}, "fast");
          $(".widget-panel-menu i:before").stop().css("margin-left", "20px;");
      }, function(){
          $(this).stop().addClass("itemsopen");
          $(".verticalinfo").stop().fadeIn(100);
          $(".reduce").stop().fadeOut(100);
          $(".widget-panel-menu h2").stop().fadeOut(100);
          $(".widget-panel-menu div").stop().fadeOut(100);
          $(".widget-panel-menu i").stop().css("background","rgba(255, 255, 255, 0.2)");
          $(".widget-panel-menu i").stop().css("display","contents");
          $("#navleft").stop().animate({ width:'70px'}, "fast");
          $("#mainNav.fixed-top .sidenav-toggler > .nav-item").stop().animate({ width:'70px'}, "fast");
          $("footer.sticky-footer").stop().css("width","calc(100% - 70px)");
          $(".databuy").stop().animate({ width:'70px'}, 100);
          $(".brandbar img").stop().animate({ width:'70px'}, "fast");
          $(".widget-panel-menu i:before").stop().css("margin-left", "-3px;");
    });
    if(movileAccess == "1"){

      $("#navleft").click(function(){
            $(this).stop().removeClass("itemsopen");
            $(".verticalinfo").stop().fadeOut(100);
            $(".reduce").stop().fadeIn(100);
            $(".widget-panel-menu h2").stop().fadeIn(100);
            $(".widget-panel-menu div").stop().fadeIn(100);
            $(".widget-panel-menu i").stop().css("background","transparent");
            $(".widget-panel-menu i").stop().css("display","initial");
            $("#navleft").stop().animate({ width:'250px'}, 100);
            $("#mainNav.fixed-top .sidenav-toggler > .nav-item").stop().animate({ width:'250px'}, "fast");
            $("footer.sticky-footer").stop().css("width","calc(100% - 250px)");
            $(".databuy").stop().animate({ width:'250px'}, 100);
            $(".brandbar img").stop().animate({ width:'250px'}, "fast");
            $(".widget-panel-menu i:before").stop().css("margin-left", "20px;");
        }, function(){
            $(this).stop().addClass("itemsopen");
            $(".verticalinfo").stop().fadeIn(100);
            $(".reduce").stop().fadeOut(100);
            $(".widget-panel-menu h2").stop().fadeOut(100);
            $(".widget-panel-menu div").stop().fadeOut(100);
            $(".widget-panel-menu i").stop().css("background","rgba(255, 255, 255, 0.2)");
            $(".widget-panel-menu i").stop().css("display","contents");
            $("#navleft").stop().animate({ width:'70px'}, "fast");
            $("#mainNav.fixed-top .sidenav-toggler > .nav-item").stop().animate({ width:'70px'}, "fast");
            $("footer.sticky-footer").stop().css("width","calc(100% - 70px)");
            $(".databuy").stop().animate({ width:'70px'}, 100);
            $(".brandbar img").stop().animate({ width:'70px'}, "fast");
            $(".widget-panel-menu i:before").stop().css("margin-left", "-3px;");
      });

    }
}

    $("#message_alert").click(function() {
         $(this).find("#flashMessage").hide();
         $("#flashMessage").hide();
         $(this).find("#message_alert").hide();
    });

    if (copy_js.user_role == "Gerente General" && copy_js.action == "products_import") {

        setTimeout(function() {
            $("body").find("tr.os2>td>div.stylegeneralbox2").each(function(index, el) {
                $(this).removeClass('stylegeneralbox2')
            });
        }, 1000);
    }

    $("body").on( "click", ".viewdetailc", function() {
            $(this).find(".vtc").toggleClass('fa-eye');
            $(this).find(".vtc").toggleClass('fa-eye-slash');
        if ($('.stylegeneralbox').hasClass('muestra-flex')) {
            $(".stylegeneralbox").toggleClass('switche');
        }       
        if ($('.stylegeneralbox2').hasClass('muestra-flex')) {
            $(".stylegeneralbox2").toggleClass('switche');
        }   
        $("body").find("div.stylegeneralbox").removeClass('muestra-flex');
        $(this).parent("td").parent("tr").next("tr").find('.stylegeneralbox').addClass("muestra-flex");
        $("body").find("div.stylegeneralbox2").removeClass('muestra-flex');
        $(this).parent("td").parent("tr").next("tr").find('.stylegeneralbox2').addClass("muestra-flex");        
        $(this).parent("td").parent("tr").addClass("bordefull");
    });


    if (copy_js.modal_aviso) {
        $('#modal_big_information_aviso').modal('show');
    }
    $('[data-toggle="tooltip"]').tooltip(); 

    $('#naturalpersontable').DataTable( {
        'iDisplayLength': 18,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });

    $('.datosPendientesDespacho').DataTable({
        'iDisplayLength': 18,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });

    $('.tblProcesoCotizacion').DataTable({
        'iDisplayLength': 20,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel'
        ],
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [20,50, 100, -1], [20,50, 100, "Todos"] ]
    });

    $('.tblProcesoSolicitud').DataTable({
        'iDisplayLength': 9,
        "language": {"url": "https://crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });

    $('.tblFinalizadoCotizacion').DataTable({
        'iDisplayLength': 9,
        "language": {"url": "//crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });

    $('.tblFinalizadoSolicitud').DataTable({
        'iDisplayLength': 9,
        "language": {"url": "//crm.kebco.co/Spanish.json",},
        "order": [[ 0, "desc" ]],
        "lengthMenu": [ [21,50, 100, -1], [21,50, 100, "Todos"] ]
    });

    $(".fc-event").each(function(){
        var colors = ['#00b307', '#ff8b01', '#004794','#002690', '#80a3c8', '#0b840f','#008cff', '#00c583', '#00c5e2','#9e9e9e', '#000bff', '#c70000'];
        var random_color = colors[Math.floor(Math.random() * colors.length)];
        $(this).css('background-color', random_color);
    });

    $(".countdata").hide();
    if ($('body').hasClass('userslogin')) {
        $("#page-top").removeClass('bg-dark');
        $('.userslogin > .content-wrapper').removeClass("content-wrapper");
        $('footer').remove();
    }

    $('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 3000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });


///////////////////////INICIO CONDICIONES MÓDULO DE GESTIÓN CRM///////////////////////////////
if (    copy_js.action == 'adviser' 
    ||  copy_js.action == 'flujo_tienda' 
    ||  copy_js.action == 'quotes_sent' 
    ||  copy_js.action == 'despachos' 
    ||  copy_js.action == 'salidas' 
    ||  copy_js.action == 'facturas' 
    ||  copy_js.action == 'panel_movimientos' 
    ||  copy_js.action == 'aprovee' 
    ||  copy_js.action == 'tracking' 
    ||  copy_js.action == 'movements' ) {
    $('.grupo-gestioncrm').find("#gestion-crm").addClass("show");
}

if (   copy_js.controller_menu == 'NOTES'
    || copy_js.controller_menu == 'TEMPLATES'
    || copy_js.controller_menu == 'CLIENTSLEGALS'
    || copy_js.controller_menu == 'CLIENTSNATURALS'
    || copy_js.controller_menu == 'PROSPECTIVEUSERS'
    || copy_js.controller_menu == 'BRANDS'
    || copy_js.controller_menu == 'INVENTORIES'
    || copy_js.controller_menu == 'PRODUCTS') {
    if (    copy_js.action == 'index' ) {
        $('.grupo-gestioncrm').find("#gestion-crm").addClass("show");
    }
}

if (    copy_js.controller_menu == 'PRODUCTS' 
     || copy_js.controller_menu == 'BRANDS' 
     || copy_js.controller_menu == 'CLIENTSLEGALS' 
     || copy_js.controller_menu == 'QUOTATIONS' 
     || copy_js.controller_menu == "CATEGORIES" 
     || copy_js.controller_menu == 'TEMPLATES' 
     || copy_js.controller_menu == 'NOTES' 
     || copy_js.controller_menu == 'CLIENTSNATURALS') {
    if (    copy_js.action == 'add'
         || copy_js.action == 'view'
         || copy_js.action == 'edit') {
        $('.grupo-gestioncrm').find("#gestion-crm").addClass("show");
    }
}

/* active a cada item de gestion-crm*/
if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
    if (    copy_js.action == 'index') {
        $('.grupo-gestioncrm').find(".ProspectiveUsers-index-item").addClass("liactive");
    }
    if (    copy_js.action == 'flujo_tienda') {
        $('.grupo-gestioncrm').find(".ProspectiveUsers-index-item").addClass("liactive");
    }    
    if (    copy_js.action == 'adviser') {
        $('.grupo-gestioncrm').find(".ProspectiveUsers-index-item").addClass("liactive");
    }    
    if (    copy_js.action == 'quotes_sent') {
        $('.grupo-gestioncrm').find(".quotes_sent-item").addClass("liactive");
    }
    if (    copy_js.action == 'despachos') {
        $('.grupo-gestioncrm').find(".despachos-index-item").addClass("liactive");
    }  
    if (    copy_js.action == 'facturas') {
        $('.grupo-gestioncrm').find(".facturas-index-item").addClass("liactive");
    }   
    if (    copy_js.action == 'aprovee') {
        $('.grupo-gestioncrm').find(".quotes_approve-item").addClass("liactive");
    }        
}
if (copy_js.controller_menu == 'PRODUCTS' || copy_js.controller_menu == 'BRANDS') {
    if (    copy_js.action == 'index') {
        $('.grupo-gestioncrm').find(".Products-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'QUOTATIONS') {
    if (    copy_js.action == 'view') {
        $('.grupo-gestioncrm').find(".quotes_sent-item").addClass("liactive");
    }
    if (    copy_js.action == 'tracking') {
        $('.grupo-gestioncrm').find(".quotes_read-item").addClass("liactive");
    }

}
if (copy_js.controller_menu == 'CATEGORIES') {
    if ( copy_js.action == 'view' || copy_js.action == 'index' || copy_js.action == 'add' || copy_js.action == 'edit') {
         $('.grupo-gestioncrm').find("#gestion-crm").addClass("show");
        $('.grupo-gestioncrm').find(".Categories-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'BRANDS' 
    || copy_js.controller_menu == 'PRODUCTS' 
    || copy_js.controller_menu == 'CLIENTSLEGALS' 
    || copy_js.controller_menu == 'CLIENTSNATURALS') {
    if (    copy_js.action == 'add' || copy_js.action == 'view' || copy_js.action == 'edit') {
        $('.grupo-gestioncrm').find(".Products-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'CLIENTSLEGALS') {
    if (    copy_js.action == 'index') {
        $('.grupo-gestioncrm').find(".ClientsLegals-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'CLIENTSNATURALS') {
    if (    copy_js.action == 'index') {
        $('.grupo-gestioncrm').find(".ClientsLegals-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'TEMPLATES' ) {
    if (    copy_js.action == 'index' || copy_js.action == 'view' || copy_js.action == 'edit') {
        $('.grupo-gestioncrm').find(".templates-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'NOTES') {
    if (    copy_js.action == 'index' || copy_js.action == 'edit' || copy_js.action == 'view') {
        $('.grupo-gestioncrm').find(".notes-index-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'INVENTORIES') {
    if (    copy_js.action == 'movements') {
        $('.grupo-gestioncrm').find(".movements-item").addClass("liactive");
    }
    if (    copy_js.action == 'salidas') {
        $('.grupo-gestioncrm').find(".salidas-index-item").addClass("liactive");
    }
    if (    copy_js.action == 'panel_movimientos') {
        $('.grupo-gestioncrm').find(".movements_list-item").addClass("liactive");
    }
}
///////////////////////FINAL DE CONDICIONES MÓDULO DE GESTIÓN CRM////////////////////////





///////////////////////INICIO CONDICIONES MÓDULO DE IMPORTACIONES///////////////////////////////
if (copy_js.controller_menu == 'PRODUCTS') {
    if (    copy_js.action == 'products_import') {
        $('.grupo-importaciones').find("#importaciones").addClass("show");
        $('.grupo-importaciones').find(".imports_revisions-item").addClass("liactive");
    }
}
if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
    if (    copy_js.action == 'imports_approved'
        ||   copy_js.action == 'imports_rejected'
        ||   copy_js.action == 'order_import') {
        $('.grupo-importaciones').find("#importaciones").addClass("show");
        $('.grupo-importaciones').find(".imports_revisions-item").addClass("liactive");
    }
}
if (    copy_js.action == 'request_import_brands' 
    ||  copy_js.action == 'import_ventas' 
    ||  copy_js.action == 'imports_revisions' 
    ||  copy_js.action == 'import_finalizadas') {
    $('.grupo-importaciones').find("#importaciones").addClass("show");
}

/* active a cada item de importaciones*/
if (    copy_js.action == 'request_import_brands') {
    $('.grupo-importaciones').find(".request_import_brands-item").addClass("liactive");
}
if (    copy_js.action == 'import_ventas') {
    $('.grupo-importaciones').find(".import_ventas-item").addClass("liactive");
}
if (    copy_js.action == 'imports_revisions') {
    $('.grupo-importaciones').find(".imports_revisions-item").addClass("liactive");
}
if (    copy_js.action == 'import_finalizadas') {
    $('.grupo-importaciones').find(".import_finalizadas-item").addClass("liactive");
}
///////////////////////FIN DE CONDICIONES MÓDULO DE IMPORTACIONES///////////////////////////////





///////////////////////INICIO CONDICIONES MÓDULO DE TESORERIA///////////////////////////////

if (    copy_js.action == 'verify_payment' 
    ||  copy_js.action == 'verify_payment_tienda' 
    ||  copy_js.action == 'verify_payment_credito' 
    ||  copy_js.action == 'verify_payments_payments' 
    ||  copy_js.action == 'informe_ventas_tienda' 
    ||  copy_js.action == 'payment_true' 
    ||  copy_js.action == 'payment_false' 
    ||  copy_js.action == 'informe_ventas' 
    ||  copy_js.action == 'informe_comisiones') {
    $('.grupo-tesoreria').find("#tesoreria").addClass("show");
}

/* active a cada item de tesoreria*/
if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
    if (    copy_js.action == 'verify_payment') {
        $('.grupo-tesoreria').find(".verify_payment-item").addClass("liactive");
    }
    if (    copy_js.action == 'verify_payment_credito') {
        $('.grupo-tesoreria').find(".verify_payment_credito-item").addClass("liactive");
    }
    if (    copy_js.action == 'verify_payments_payments') {
        $('.grupo-tesoreria').find(".verify_payments_payments-item").addClass("liactive");
    }
    if (    copy_js.action == 'payment_true') {
        $('.grupo-tesoreria').find(".payment_true-item").addClass("liactive");
    }
    if (    copy_js.action == 'payment_false') {
        $('.grupo-tesoreria').find(".payment_false-item").addClass("liactive");
    }
    if (    copy_js.action == 'informe_ventas') {
        $('.grupo-tesoreria').find(".informe_ventas-item").addClass("liactive");
    }
    if (    copy_js.action == 'informe_comisiones') {
        $('.grupo-tesoreria').find(".informe_comisiones-item").addClass("liactive");
    }
    if (    copy_js.action == 'verify_payment_tienda') {
        $('.grupo-tesoreria').find(".verify_payment-tienda").addClass("liactive");
    }
    if (    copy_js.action == 'informe_ventas_tienda') {
        $('.grupo-tesoreria').find(".informe_comisiones-informeVentasTienda").addClass("liactive");
    }
}
///////////////////////FIN CONDICIONES MÓDULO DE TESORERIA///////////////////////////////





///////////////////////INICIO CONDICIONES MÓDULO DE DESPACHOS///////////////////////////////

if (    copy_js.action == 'information_dispatches' 
    ||  copy_js.action == 'pending_dispatches' 
    ||  copy_js.action == 'status_dispatches' 
    ||  copy_js.action == 'status_dispatches_finish' 
    ||  copy_js.controller_menu == 'CONVEYORS') {
    $('.grupo-despachos').find("#despachos").addClass("show");
}
/* active a cada item de despachos*/
if (    copy_js.action == 'information_dispatches') {
    $('.grupo-despachos').find(".information_dispatches-item").addClass("liactive");
}
if (    copy_js.action == 'pending_dispatches') {
    $('.grupo-despachos').find(".pending_dispatches-item").addClass("liactive");
}
if (    copy_js.action == 'status_dispatches') {
    $('.grupo-despachos').find(".status_dispatches-item").addClass("liactive");
}
if (    copy_js.action == 'status_dispatches_finish') {
    $('.grupo-despachos').find(".status_dispatches_finish-item").addClass("liactive");
}

if (    copy_js.action == 'index' && copy_js.controller_menu == 'CONVEYORS') {
    $('.grupo-despachos').find(".conveyors-item").addClass("liactive");
}
///////////////////////FIN CONDICIONES MÓDULO DE DESPACHOS///////////////////////////////





///////////////////////INICIO CONDICIONES MÓDULO DE SERVICIO TÉCNICO///////////////////////////////
if (copy_js.controller_menu == 'TECHNICALSERVICES') {
    if (    copy_js.action == 'index'
        ||  copy_js.action == 'add'
        ||  copy_js.action == 'edit'
        ||  copy_js.action == 'identificadores'
        ||  copy_js.action == 'view'
        ||  copy_js.action == 'flujos') {
        $('.grupo-serviciotecnico').find("#serviciot").addClass("show");
    }
    if (    copy_js.action == 'index' || copy_js.action == 'view' || copy_js.action == 'edit'
        || copy_js.action == 'identificadores') {
        $('.grupo-serviciotecnico').find(".TechnicalServices-index-item").addClass("liactive");
    }
    if (    copy_js.action == 'add') {
        $('.grupo-serviciotecnico').find(".TechnicalServices-add-item").addClass("liactive");
    }   
    if (    copy_js.action == 'flujos') {
        $('.grupo-serviciotecnico').find(".TechnicalServices-flujos-item").addClass("liactive");
    }        
}
if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
    if (    copy_js.action == 'reporte_tecnico') {
        $('.grupo-serviciotecnico').find("#serviciot").addClass("show");
        $('.grupo-serviciotecnico').find(".ProspectiveUsers-reporte_tecnico-item").addClass("liactive");

    }
}
///////////////////////FIN CONDICIONES MÓDULO DE SERVICIO TÉCNICO///////////////////////////////




///////////////////////INICIO CONDICIONES MÓDULO DE INFORMES///////////////////////////////

if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
    if (    copy_js.action == 'report_date_flujos'
        ||  copy_js.action == 'report_adviser'
        ||  copy_js.action == 'report_customer_new'
        ||  copy_js.action == 'report_advisers'
        ||  copy_js.action == 'report_management') {
        $('.grupo-informes').find("#informes").addClass("show");
    }
    if (    copy_js.action == 'report_date_flujos') {
        $('.grupo-informes').find(".report_date_flujos-item").addClass("liactive");
    } 
    if (    copy_js.action == 'report_adviser') {
        $('.grupo-informes').find(".report_adviser-item").addClass("liactive");
    }
    if (    copy_js.action == 'report_management' || copy_js.action == 'report_management2') {
        $('.grupo-informes').find(".report_management-item").addClass("liactive");
    }
    if (    copy_js.action == 'report_advisers') {
        $('.grupo-informes').find(".report_advisers-item").addClass("liactive");
    } 
    if (    copy_js.action == 'report_customer_new') {
        $('.grupo-informes').find(".report_management2-item").addClass("liactive");
    }    
}
if (copy_js.controller_menu == 'RECEIPTS') {
    if (    copy_js.action == 'report') {
        $('.grupo-informes').find("#informes").addClass("show");
        $('.grupo-informes').find(".Receipts-report-item").addClass("liactive");
    }   
}
///////////////////////FIN CONDICIONES MÓDULO DE INFORMES///////////////////////////////






///////////////////////INICIO CONDICIONES MÓDULO DE CONFIGURACIONES///////////////////////////////

if (copy_js.controller_menu == 'MANAGEMENTNOTICES') {
     if (    copy_js.action == 'index'
          || copy_js.action == 'add'
          || copy_js.action == 'edit'
          || copy_js.action == 'view') {
            $('.grupo-configuracion').find("#config").addClass("show");
        }
     if (    copy_js.action == 'index' || copy_js.action == 'view') {
        $('.grupo-configuracion').find(".ManagementNotices-index-item").addClass("liactive");
     }  
}

if (copy_js.controller_menu == 'IMPORTREQUESTS') {
     if (copy_js.action == 'config') {
        $('.grupo-configuracion').find("#config").addClass("show");
        $('.grupo-configuracion').find(".ImportRequests-config-item").addClass("liactive");  
    }
}
if (copy_js.controller_menu == 'HEADERS') {
     if (    copy_js.action == 'index'
          || copy_js.action == 'add'
          || copy_js.action == 'edit'
          || copy_js.action == 'view') {
            $('.grupo-configuracion').find("#config").addClass("show");
        $('.grupo-configuracion').find(".Headers-index-item").addClass("liactive");
        } 
}
if (copy_js.controller_menu == 'USERS') {
     if (    copy_js.action == 'index'
          || copy_js.action == 'add'
          || copy_js.action == 'edit'
          || copy_js.action == 'view') {
            $('.grupo-configuracion').find("#config").addClass("show");
        $('.grupo-configuracion').find(".Users-index-item").addClass("liactive");
        } 
}

if (copy_js.controller_menu == 'IMPORTREQUEST') {
        $('.grupo-configuracion').find("#config").addClass("show");
}

if (copy_js.controller_menu == 'MANAGES') {
     if (    copy_js.action == 'diary') {
            $('.grupo-configuracion').find("#config").addClass("show");
        $('.grupo-configuracion').find(".Manages-diary-item").addClass("liactive");
        } 
}

if (copy_js.controller_menu == 'PROSPECTIVEUSERS') {
     if (    copy_js.action == 'flujo_masivo') {
            $('.grupo-configuracion').find("#config").addClass("show");
        $('.grupo-configuracion').find(".masivo-index-item").addClass("liactive");
    } 
}
///////////////////////FIN CONDICIONES MÓDULO DE CONFIGURACIONES///////////////////////////////
 });


    if (copy_js.user_id > 0) {
        if(movileAccess == "0"){
            cargar_notificaciones();
        }
        var arrayRoles = [copy_js.role_gerencia,copy_js.role_asesor_comercial,copy_js.role_asesor_tecnico_comercial,copy_js.role_gerencia_pelican,
                            copy_js.role_servicio_tecnico,copy_js.role_asesor_logitico_comercial,copy_js.role_servicio_al_cliente];
        if (arrayRoles.indexOf(copy_js.user_role) != -1) {
            if(movileAccess == "0"){
                // payment_verification_sales_day_user();
                // payment_verification_sales_month_user();
            }
        }
        if (movileAccess == "0") {
            // payment_verification_sales_month();
        }
        // window.setTimeout(sesion_false, copy_js.duration_sesion_false); 
        // 30 minutos = 1800000 milisegundos
        // if (copy_js.controller_menu != 'MANAGES') {
        //     validate_notifications_user();
        // }
    }



$("body").on( "click", ".nota_p1", function() {
    datanota = $("#nota_p1 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p2", function() {
    datanota = $("#nota_p2 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p3", function() {
    datanota = $("#nota_p3 > .card-body").html();
    $("#introtext").html(datanota); $("#introtext").show();
});
$("body").on( "click", ".nota_p4", function() {
    datanota = $("#nota_p4 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p5", function() {
    datanota = $("#nota_p5 > .card-body").html();
    $("#introtext").html(datanota); $("#introtext").show();
});
$("body").on( "click", ".nota_p6", function() {
    datanota = $("#nota_p6 > .card-body").html();
    $("#introtext").html(datanota); $("#introtext").show();
});
$("body").on( "click", ".nota_p7", function() {
    datanota = $("#nota_p7 > .card-body").html();
    $("#introtext").html(datanota); $("#introtext").show();
});
$("body").on( "click", ".nota_p8", function() {
    datanota = $("#nota_p8 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p9", function() {
    datanota = $("#nota_p9 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p10", function() {
    datanota = $("#nota_p10 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});
$("body").on( "click", ".nota_p11", function() {
    datanota = $("#nota_p11 > .card-body").html();
    $("#introtext").html(datanota);
    $("#introtext").show();
});


$("body").on( "click", ".nota_p12", function() {datanota = $("#nota_p12 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p13", function() {datanota = $("#nota_p13 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p14", function() {datanota = $("#nota_p14 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p15", function() {datanota = $("#nota_p15 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p16", function() {datanota = $("#nota_p16 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p17", function() {datanota = $("#nota_p17 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p18", function() {datanota = $("#nota_p18 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p19", function() {datanota = $("#nota_p19 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p20", function() {datanota = $("#nota_p20 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p21", function() {datanota = $("#nota_p21 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p22", function() {datanota = $("#nota_p22 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p23", function() {datanota = $("#nota_p23 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p24", function() {datanota = $("#nota_p24 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p25", function() {datanota = $("#nota_p25 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p26", function() {datanota = $("#nota_p26 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p27", function() {datanota = $("#nota_p27 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p28", function() {datanota = $("#nota_p28 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p29", function() {datanota = $("#nota_p29 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});
$("body").on( "click", ".nota_p30", function() {datanota = $("#nota_p30 > .card-body").html();$("#introtext").html(datanota);$("#introtext").show();});



$("body").on( "click", ".nota_d1", function() {
    datanota = $("#nota_d1 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d2", function() {
    datanota = $("#nota_d2 > .card-body").html();
    $("#introtext2").html(datanota); $("#introtext2").show();
});
$("body").on( "click", ".nota_d3", function() {
    datanota = $("#nota_d3 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d4", function() {
    datanota = $("#nota_d4 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d5", function() {
    datanota = $("#nota_d5 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d6", function() {
    datanota = $("#nota_d6 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d7", function() {
    datanota = $("#nota_d7 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d8", function() {
    datanota = $("#nota_d8 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d9", function() {
    datanota = $("#nota_d9 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});
$("body").on( "click", ".nota_d10", function() {
    datanota = $("#nota_d10 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});


$("body").on( "click", ".nota_d11", function() {
    datanota = $("#nota_d11 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d12", function() {
    datanota = $("#nota_d12 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d13", function() {
    datanota = $("#nota_d13 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d14", function() {
    datanota = $("#nota_d14 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});


$("body").on( "click", ".nota_d15", function() {
    datanota = $("#nota_d15 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d16", function() {
    datanota = $("#nota_d16 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d17", function() {
    datanota = $("#nota_d17 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d18", function() {
    datanota = $("#nota_d18 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d19", function() {
    datanota = $("#nota_d19 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".nota_d20", function() {
    datanota = $("#nota_d20 > .card-body").html();
    $("#introtext2").html(datanota);
    $("#introtext2").show();
});

$("body").on( "click", ".pago_1", function() {
    datanota = $("#pago_1 > .card-body").html();
    $(".condiciones_negociacion").html(datanota);
});
$("body").on( "click", ".pago_2", function() {
    datanota = $("#pago_2 > .card-body").html();
    $(".condiciones_negociacion").html(datanota);
});
$("body").on( "click", ".pago_3", function() {
    datanota = $("#pago_3 > .card-body").html();
    $(".condiciones_negociacion").html(datanota);
});
$("body").on( "click", ".pago_4", function() {
    datanota = $("#pago_4 > .card-body").html();
    $(".condiciones_negociacion").html(datanota);
});
$("body").on( "click", ".pago_5", function() {
    datanota = $("#pago_5 > .card-body").html();
    $(".condiciones_negociacion").html(datanota);
});

$("body").on( "click", ".pago_6", function() { datanota = $("#pago_6 > .card-body").html(); $(".condiciones_negociacion").html(datanota);});
$("body").on( "click", ".pago_7", function() { datanota = $("#pago_7 > .card-body").html(); $(".condiciones_negociacion").html(datanota);});
$("body").on( "click", ".pago_8", function() { datanota = $("#pago_8 > .card-body").html(); $(".condiciones_negociacion").html(datanota);});
$("body").on( "click", ".pago_9", function() { datanota = $("#pago_9 > .card-body").html(); $(".condiciones_negociacion").html(datanota);});
$("body").on( "click", ".pago_10", function() { datanota = $("#pago_10 > .card-body").html(); $(".condiciones_negociacion").html(datanota);});

var NOTAS_VIEW = 0;

$("body").on( "click", ".imgmin-product", function() {
    var imagen = $(this).attr("dataimg");
    var contenido = $(this).attr('dataname');
    $("#img-product").attr('src',imagen);
    $("#contenido").append("<p>"+contenido+"</p>");
    $(".fondo").fadeIn();
    $(".popup").fadeIn();
    $("#modal_nota_flujo").modal("hide");
    NOTAS_VIEW = 1;
});

$("body").on( "click", ".imgmin-pp", function() {
    var imgpp = $(this).attr("dataimgpp");
    $(".img-productpp").attr('src',imgpp);
    $(".fondo").fadeIn();
    $(".popup").fadeIn();
});

$("body").on( "click", "#print_button", function() {
    var mode = 'iframe';
    var close = mode == "popup";
    var options = { mode : mode, popClose : close};
    $("#cotizacionview").printArea( options );
});


$("body").on( "click", ".cierra", function() {
    $("#img-muestra").attr('src','');
    $("#contenido").html('');
    $(".fondo").fadeOut();
    $(".popup").fadeOut();
    $(".popup2").fadeOut();
    $(".popup3").fadeOut();
    if (NOTAS_VIEW == 1) {
        $("#modal_nota_flujo").modal("show");
        NOTAS_VIEW = 0; 
    }
});

$("body").on("click", ".btn_entendido_aviso", function() {
    $.post(copy_js.base_url+'Users/updateNoticeView',{}, function(result){
        $('#modal_big_information_aviso').modal('hide');
    });
});

$("body").on("click", ".searchobject", function() {
    $(".searchobject").addClass('level-opacity');
    $('#search-modal').addClass("sh");
    $('#txt_buscar').focus();
});

$("body").on("click", "input[type=submit]", function() {
    $('input[type=submit]').hide();
    setTimeout(function() {
        $('input[type=submit]').show();
    },3000);
});

$("body").on( "click", "i.fa.fa-lg.fa-times", function() {
    $('.searchobject').removeClass('level-opacity');
    $('#search-modal').removeClass("sh");
    $('.fullsearch').val('');
    $(' #resultadoBuscador').addClass("op");

   document.getElementById("txt_buscar").placeholder="¿Qué estás buscando?";
});

$("body").on( "click", ".control_flujo", function() {
    $(this).addClass("activeflow");
    $(".registerprospective").not(this).removeClass("activeflow");
});

$("body").on( "click", ".linealign>.btnAsignData", function(event) {
    event.preventDefault();
    $(this).parent('.linealign').find(".assigned").toggleClass("muestra");
    $(document).keyup(function(event){
        if(event.which==27){$(".assigned").removeClass("muestra");}
    });
});

$('#toggleNavPosition').click(function() {
    $('body').toggleClass('fixed-nav');
    $('nav').toggleClass('fixed-top static-top');
});

$('#toggleNavColor').click(function() {
    $('nav').toggleClass('navbar-dark navbar-light');
    $('nav').toggleClass('bg-dark bg-light');
    $('body').toggleClass('bg-dark bg-light');
});

$("body").on( "click", ".stateNotificacion", function(e) {
    e.preventDefault();
    var notificacion_id         = $(this).data('uid');
    var state                   = $(this).data('state');
    $.post(copy_js.base_url+'manages/changestate',{notificacion_id:notificacion_id,state:state}, function(result){
        location.href = result;
    });
});

$("body").on( "click", ".btn_deshabilitar", function() {
    var model_id            = $(this).data('uid');
    var state               = "1";
    var controlador         = name_controller();
    swal({
        title: "¿Estas seguro de habilitar el "+controlador+"?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+copy_js.controller+'/changeStateModel',{model_id:model_id,state:state}, function(result){
            location.href =copy_js.base_url+copy_js.controller;
        });
    });
});

$("body").on( "click", ".btn_habilitar", function() {
    var model_id        = $(this).data('uid');
    var state           = "0";
    var controlador     = name_controller();
    swal({
        title: "¿Estas seguro de inhabilitar el "+controlador+"?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+copy_js.controller+'/changeStateModel',{model_id:model_id,state:state}, function(result){
            location.href =copy_js.base_url+copy_js.controller;
        });
    });
});

$("body").on('click', '#sendMessajeAll', function(event) {
    event.preventDefault();
    $.get($(this).attr("href"), function(result) {
        $("#cuerpoMensajeImportante").html(result);
        $("#modalMensajeImportante").modal("show");
    });
});

$("body").on('submit', '#ManageMessageForm', function(event) {
    event.preventDefault();
    $(".body-loading").show();
    $.post($(this).attr("action"), $(this).serialize(), function(data, textStatus, xhr) {
        location.reload();
    });
});

$("body").on("click", "#notificaciones_leidas", function() {
    swal({
        title: "¿Estas seguro de marcar todas las notificaciones en leidas?",
        text: "¿Deseas continuar con la acción?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        cancelButtonText:"Cancelar",
        confirmButtonText: "Aceptar",
        closeOnConfirm: false
    },
    function(){
        $.post(copy_js.base_url+'Manages/marcarNotificacionesLeidas',{}, function(result){
            location.href =copy_js.base_url+copy_js.controller+'/'+copy_js.action;
        });
    });
});


$("body").on("keyup", "#txt_buscar", function(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla!=13){
        return false;
    }
    $(' #resultadoBuscador').removeClass("op");
    $('#resultadosBusqueda').empty();
    var texto   = $('#txt_buscar').val();
    if (texto.length > 1) {
        var d               = preload();
        $('#resultadosBusqueda').append(d);
        $.post(copy_js.base_url+'Pages/find_string_seeker',{texto:texto}, function(result){
            $.post(copy_js.base_url+'Pages/countFindStringSeeker',{texto:texto}, function(resultCount){
                $('#resultadosBusqueda').empty();
                if (result.trim() == '') {
                    var texto = '<a class="list-group-item intro-results"><p>No se encuentran resultados</p></a>';
                    $('#resultadosBusqueda').html(texto);
                    $('.cantresult').html(" ");
                } else {
                    $('#resultadosBusqueda').html(result);
                    $('.cantresult').html('Hemos encontrado<b>' +resultCount+ '</b>resultados para tu búsqueda');
                }
            });

        });
    } else {
        $('#resultadosBusqueda').empty();
        $('.cantresult').html(" ");
    }
});

$(document).keyup(function(event){
    if(event.which==13){
        event.preventDefault();
        return false;
    }
    if(event.which==27){
        $('.searchobject').removeClass('level-opacity');
        $('#search-modal').removeClass("sh");
        $('.fullsearch').val('');
        $(' #resultadoBuscador').addClass("op");
        //$("#typed").typed( {
            //stringsElement: $('#typed-strings'), typeSpeed:0, backDelay:200, loop:false, contentType:'html',
        //});
       document.getElementById("txt_buscar").placeholder="¿Qué estás buscando?";
    }

    $("body").on('click', '.closess', function(event) {
        event.preventDefault();
        $("body").find("#message_alert").hide();
    });
});

if(  typeof REJECTED_ID != 'undefined' ){
    swal({
      title: "Cartera vencida",
      text: `El FLUJO: ${REJECTED_ID} fue rechazado por la siguiente razón: ${REJECTED_REASON}`,
      type: "warning",
      showCancelButton: false,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Enterado",
      closeOnConfirm: false
    },
    function(){
      $.post(rootUrl+'prospective_users/change_reject', {id: REJECTED_ID}, function(data, textStatus, xhr) {
          location.reload();
      });
    });
}

function sesion_false(){
    $('#modal_contactado').modal('hide');
    $('#modal_cotizado').modal('hide');
    $('#modal_big').modal('hide');
    $('#modal_small_information').modal('hide');
    $('#modal_big_information').modal('hide');
    $('#modal_small').modal('hide');
    $('#modal_form_products').modal('hide');
    $('#modal_form').modal('hide');
    $('#agregarTareaModal').modal('hide');
    $('#modal_negociado').modal('hide');
    $('#modal_pagado').modal('hide');
    $('#modal_despachado').modal('hide');
    $('#cambiarContrasenaModal').modal('hide');
    $('#compose-modal').modal('hide');
    $.post(copy_js.base_url+'users/session_off',{}, function(result){
        $('#modal_small_information_body').html(result);
        $('#modal_small_information').modal('show');
    });
}

function cargar_notificaciones(){
    $.post(copy_js.base_url+'Manages/notificaciones',{}, function(result){
        $('#paint_notificaciones').html(result);
    });
}

function name_controller(){
    switch (copy_js.controller_menu) {
        case "USERS":
        controlador         = 'asesor';
        break;
        case "PROSPECTIVEUSERS":
        controlador         = 'cotización';
        break;
        case "TECHNICALSERVICES":
        controlador         = 'servicio técnico';
        break;
        case "ADITIONALS":
        controlador         = 'accesorio';
        break;

        default:
        controlador        = copy_js.controller_menu;
        break;
    }
    return controlador;
}

function message_alert(mensaje,type){

  if(movileAccess == "1"){

    var typeMsg = type.toLowerCase() != "bien" ? "danger" : "success";
    $.notify({message: mensaje },
      {
        type: typeMsg,
        placement: {
          from: "top",
          align: "right"
        },
        offset: 0,
        delay: 9000,
        timer: 1000,
        showProgressbar: false,
        z_index: 10310
      });
  }else{
    $("#message_alert").css("display", "block");
    var alert = '<div class="banneralert '+type+'"><i class="fa fa-4 fa-exclamation-triangle"></i><i class="fa fa-times closess"></i><div class="copiealertmin">'+type+'</div><div>'+mensaje+'</div></div>';
    $("#message_alert").html(alert);
    setTimeout(function() {$("#message_alert").fadeOut("slow");},9000);
  }
}
setTimeout(function() {$("#flashMessage").fadeOut("slow");},9000);

function validate_image_message(estado){
    estado                      = parseInt(estado);
    var tipo                    = "error";
    switch (estado) {
        case 2:
            var mensaje         = "El archivo se encuentra dañado, no se ha podido subir al servidor";
            break;
        case 3:
            var mensaje         = "La imagen es necesaria";
            break;
        case 4:
            var mensaje         = "El archivo debe de ser una imagen, formato .PNG o .JPG";
            break;
        case 5:
            var mensaje         = "No se a podido ejecutar la acción, por favor inténtalo después";
            break;
        case 6:
            var mensaje         = "Por favor revisa, este registro ya existe en la base de datos";
            break;
        case 7:
            var mensaje         = "No seleccionó ningún producto para ser enviado";
            break;
    }
    message_alert(mensaje,tipo);
}

function validate_documento_pdf_message(estado){
    estado                      = parseInt(estado);
    var tipo                    = "error";
    switch (estado) {
        case 2:
            var mensaje         = "El archivo se encuentra dañado, no se ha podido subir al servidor";
            break;
        case 3:
            var mensaje         = "El documento es necesario";
            break;
        case 4:
            var mensaje         = "Solo se aceptan archivos con extensión .PDF";
            break;
        case 5:
            var mensaje         = "No se a podido ejecutar la acción, por favor inténtalo después";
            break;
        case 6:
            var mensaje         = "Por favor revisa, el registro ya existe en la base de datos";
            break;
    }
    message_alert(mensaje,tipo);
}

function validarNumero(numero){
    if (!/^([0-9])*$/.test(numero)){
        return false;
    } else {
        return true;
    }
}

function string_valdate_Number(string, punto = null){
    var out = '';
    var filtro = '1234567890';

    if(punto != null){
        filtro+=".";
    }

    for (var i=0; i<string.length; i++){
        if (filtro.indexOf(string.charAt(i)) != -1) {
            out += string.charAt(i);
        }
    }
    return out;
}

function string_valdate_Number_nit(string){
    var out = '';
    var filtro = '1234567890 ';
    for (var i=0; i<string.length; i++){
        if (filtro.indexOf(string.charAt(i)) != -1) {
            out += string.charAt(i);
        }
    }
    return $.trim(out);
}

function number_format(numero){
    numero                  = String(numero);
    numero                  = string_valdate_Number(numero);
    numero                  = numero.replace(/\,/g, "");
    numero                  = numero.replace(/\./g, "");
    resultado               = "";
    for (var j, i = numero.length - 1, j = 0; i >= 0; i--, j++){
        resultado = numero.charAt(i) + ((j > 0) && (j % 3 == 0)? ".": "") + resultado;
    }
    return resultado;
}

function format_number(numero,suma){
    numero              = String(numero);
    var res             = numero.replace(/\./g, "");
    res                 = res.replace(/\,/g, "");
    var precio          = parseFloat(res);
    var sum             = suma + precio;
    return sum;
}

function payment_verification_sales_day_user(){
    $.post(copy_js.base_url+'FlowStages/paymentVerificationSalesDayUser',{}, function(result){
        $('#countMetasDayUser').html(result);
    });
}

function payment_verification_sales_month_user(){
    $.post(copy_js.base_url+'FlowStages/paymentVerificationSalesMonthUser',{}, function(result){
        $('#countSalesUserMonth').html(result);
    });
}

function payment_verification_sales_month(){
    $.post(copy_js.base_url+'FlowStages/paymentVerificationSalesMonth',{}, function(result){
        $('#countSalesMonth').html(result);
    });
}

function validate_notifications_user(){
    $.post(copy_js.base_url+'Manages/countManagesUser',{}, function(result){
        if (result > 5) {
            $('#modal_information_notificaciones').modal('show');
        }
    });
}

function get_parameter_name(name) {
    name            = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex       = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results         = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function get_page_url(){
    var URLactual       = String(window.location);
    var array           = URLactual.split("/");
    var URLfinal        = String(array[array.length - 1]);
    var cadenaFinal     = URLfinal.split(":");
    if (cadenaFinal[0] == 'page') {
        return true;
    } else {
        return false;
    }
}

function format_date(date){
    if(date.indexOf(',') != -1){
        var arrayCadena         = date.split(',');
        var arrayCadena1        = arrayCadena[1].split('-');
    } else {
        var arrayCadena1        = date.split('-');
    }
    var StringDate          = arrayCadena1[0].trim();
    var arrayDate           = StringDate.split(' ');
    var dia                 = parseInt(arrayDate[0]);
    if (dia < 10) {
        dia = '0'+dia;
    }
    var MesesInitIngles = [
    'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'
    ];
    var Meses = [
    '01','02','03','04','05','06','07','08','09','10','11','12'
    ];
    for (var i = 0; i < MesesInitIngles.length; i++) {
        if (MesesInitIngles[i] == arrayDate[1]) {
            var position = i;
        }
    }
    return dia+'/'+Meses[position]+'/'+arrayDate[2]+' - '+arrayDate[3];
}

function truncate_texto(texto,limit,final){
    var content = texto.trim();
    if (texto.length > limit) {
        content = content.substring(0, limit);
        return content +' '+ (final ? final : '');
    } else {
        return content;
    }
}

function limit_characters(numeroCaracteres,limit){
    if(numeroCaracteres > limit){
        return false;
    } else {
        return true;
    }
}

function color_lbl_caracteres(id,numeroCaracteres){
    if (numeroCaracteres > 500) {
        document.getElementById(id).style.color = '#FF0000';
    } else {
        document.getElementById(id).style.color = '#04B431';
    }
}

function dateDay(){
    var f = new Date();
    if ((f.getMonth() +1) > 9) {
        var mesInicio       = (f.getMonth() + 1);
        if (f.getDate() > 9) {
            var dia         = f.getDate();
            var mesFinal    = mesInicio;
        } else {
            var dia         = '0'+f.getDate();
            var mesFinal    = '0'+mesInicio;
        }
    } else {
        var mesInicio       = (f.getMonth() + 1);
        if (f.getDate() > 9) {
            var dia         = f.getDate();
            var mesFinal    = mesInicio;
        } else {
            var dia         = '0'+f.getDate();
            var mesFinal    = '0'+mesInicio;
        }
    }
    return f.getFullYear() + '-' + mesFinal + "-" + dia;
}

function preload(){
    var d           = document.createElement('div');
    d.setAttribute('class','preloader');
    return d;
}

function URLToArray(url,actual_urlData = null) {
    var request = {};

    var actual_urlVar = actual_urlData === null ? actual_url : actual_urlData;
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
        if(!pairs[i])
            continue;
        var pair = pairs[i].split('=');
        if(actual_urlVar != decodeURIComponent(pair[0])+"?" && actual_urlVar != decodeURIComponent(pair[0]) && typeof pair[1] != "undefined"){
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);         
        }
    }
    return request;

}

function changeUri(fecha_ini,fecha_fin){
    $(".informeWeb").each(function(index, el) {
        var url     = $(this).attr("href");
        var toUrl   = $(this).data("url");
        var actual_query        =  URLToArray(url);
        actual_query.ini        = fecha_ini;
        actual_query.end        = fecha_fin;
        var urlFinal = toUrl+"?"+$.param(actual_query);
        $(this).attr('href', urlFinal);
    });
}

$("body").on('click', '.closess', function(event) {
    event.preventDefault();
    $(this).parent(".message").hide();
    $(this).parent("#message_alert").hide();
});

function ValidateEmail(inputText)
{
    var mailformat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(inputText.match(mailformat))
    {
        return true;
    }else
    {
        return false;
    }
}


function validateDecimal(valor) {
    var RE = /^\d*(\.\d{1})?\d{0,1}$/;
    if (RE.test(valor)) {
        return true;
    } else {
        return false;
    }
}


function cargar_notificaciones_aprovar(){
    setTimeout(function() {
        $.post(copy_js.base_url+'Quotations/aprovee_number',{}, function(result){
            $('.counterAprove').html(result);
            cargar_notificaciones_aprovar();
        });        
    }, 300000);
}

cargar_notificaciones_aprovar();

function cotizaciones_pendientes(){

    if (typeof FROM_GEST != 'undefined') {
        return false;
    }

    setTimeout(function() {
        if ($("#notifyGestData").length) {
            cotizaciones_pendientes();
        }else{
            $.get(copy_js.base_url+'Quotations/quoataions_gests',{}, function(result){
                if (result != "0" && result != "1") {
                    ModalNotify.show("Flujo pendiente de gestión en estado cotizado",result,true);
                    $("#ProgresNoteImage").dropify(optionsDropify); 
                    $("#ProgresNoteUpdateFlowGestForm").parsley();
                }else{
                    setTimeout(function() {
                        cotizaciones_pendientes();
                    }, 120000);
                }
            });
        }
    }, 120000);
}

cotizaciones_pendientes();

function gestionChats(tiempo = 60000){

    const URL_CHAT              = copy_js.URL_CHAT;
    let TOKEN_CHAT            = copy_js.TOKEN_CHAT;
    const URL_CHAT_CONVERSATION = copy_js.URL_CHAT_CONVERSATION;

    const broadcastChannelInt = new BroadcastChannel('miCanal');

    window.broadcastChannelInt = broadcastChannel;

    // if(copy_js.user_id == 105){
    //     $.post('https://crm.kebco.co/Products/update_price_batch', {param1: 'value1'}, function(data, textStatus, xhr) {
    //         $.post('https://crm.kebco.co/Products/generate_seo_data', {param1: 'value1'}, function(data, textStatus, xhr) {
    //             /*optional stuff to do after success */
    //         });
    //     });
        
    // }

    broadcastChannel.onmessage = function(event) {
        if (event.data === 'SALIDA') {
            TOKEN_CHAT = null;
        }
    }





    setTimeout(function() {
        $.ajax({
            url: URL_CHAT,
            type: 'POST',
            dataType: 'json',
            data: {
                email : copy_js.user_email,
                token : TOKEN_CHAT,
                function : 'get_user_assingment',
            },
        })
        .done(function(resp) {

            if (resp.success && copy_js.controller != 'assists') {
                $("#numberConversations").html('')
                const conversaciones = resp.response;
                const action         = copy_js.action;
                const chat_times     = conversaciones.chat_times;
                const status         = conversaciones.status;
                const ids_conv       = [];

                if(TIME_USER.active == 1 && status == 0 && parseInt(TIME_USER.time_user) <= parseInt(chat_times.inactive) ){
                    $("#chatMessage").removeClass("hidden");
                    $("#txtGestion").hide();
                    $("#txtCierre").hide();
                    $("#txtCierreRobot").hide();
                    $("#txtInactivo").show();
                    broadcastChannel.postMessage("INACTIVO");
                     setTimeout(function() {
                        gestionChats();
                    }, 60000);
                }else{
                    if (conversaciones.last_othes.length >= 1 || conversaciones.user_message.length >= 1  || conversaciones.quotes.length >= 1) {

                        let TOTAL_DEMORA = 0;
                        let TOTAL_DEMORA_USER = 0;
                        let TOTAL_NO_CONTACT = 0;
                        let FLUJOS_COTIZACION = [];

                        conversaciones.last_othes.forEach(function (conversation) {
                            if (conversation.minutos >= 10) {
                                TOTAL_DEMORA = TOTAL_DEMORA + 1;
                                ids_conv.push(conversation.id)
                            }
                        } );
                        conversaciones.user_message.forEach(function (conversation) {
                            if (conversation.minutos >= 10) {
                                TOTAL_DEMORA_USER = TOTAL_DEMORA_USER + 1;
                                ids_conv.push(conversation.id)
                            }
                        } );
                        conversaciones.archived.forEach(function (conversation) {
                            if (conversation.minutos >= 10) {
                                TOTAL_NO_CONTACT = TOTAL_NO_CONTACT + 1;
                                ids_conv.push(conversation.id)
                            }
                        } );


                        conversaciones.quotes.forEach(function (conversation) {
                            if (conversation.minutos <= 20 && conversation.flujos.length > 0) {
                                for (let i in conversation.flujos){
                                    FLUJOS_COTIZACION.push(conversation.flujos[i]);
                                }
                                TOTAL_DEMORA++;
                                ids_conv.push(conversation.id)
                            }else{
                                TOTAL_DEMORA++;
                                ids_conv.push(conversation.id)
                            }
                        } );

                        if(FLUJOS_COTIZACION.length > 0){
                            localStorage.setItem('FLUJOS_COTIZACION', FLUJOS_COTIZACION.toString());
                        }else{
                            localStorage.setItem('FLUJOS_COTIZACION','');
                        }

                        if(typeof FLUJO_ACTUAL != 'undefined' && TOTAL_DEMORA >= 1 && FLUJOS_COTIZACION.length > 0 && FLUJOS_COTIZACION.indexOf(FLUJO_ACTUAL) != -1){
                            var existArr = true;
                        }else{
                            var existArr = false;
                        }


                        if ( (existArr == false && TOTAL_DEMORA >= 1)  || TOTAL_DEMORA_USER >= 1 || TOTAL_NO_CONTACT >= 1) {

                            if(copy_js.action.toLowerCase() == 'add' && copy_js.controller.toLowerCase() == "quotations"){
                                $("#closeBtnModal").removeClass("hidden");
                            }

                            localStorage.setItem('ids_conv', ids_conv.toString());
                            $("#numberConversations").html(ids_conv.toString());
                            $("#chatMessage").removeClass("hidden");

                            if (TOTAL_NO_CONTACT >= 1) {
                                $("#txtCierre").hide();
                                $("#txtGestion").hide();
                                $("#txtCierreRobot").show();
                                $("#txtInactivo").hide();
                                broadcastChannel.postMessage("TOTAL_DEMORA_USER_ARCHIVED");
                            }else if(TOTAL_DEMORA >= 1){
                                $("#txtGestion").show();
                                $("#txtCierre").hide();
                                $("#txtCierreRobot").hide();
                                $("#txtInactivo").hide();
                                broadcastChannel.postMessage("TOTAL_DEMORA");
                            }else if (TOTAL_DEMORA_USER >= 1) {
                                $("#txtCierre").show();
                                $("#txtGestion").hide();
                                $("#txtCierreRobot").hide();
                                $("#txtInactivo").hide();
                                broadcastChannel.postMessage("TOTAL_DEMORA_USER");
                            } 

                        }else{
                            $("#txtGestion").hide();
                            $("#txtCierre").hide();
                            $("#txtCierreRobot").hide();
                            $("#txtInactivo").hide();
                            $("#chatMessage").addClass("hidden");
                            broadcastChannel.postMessage("NO_DEMORA");
                        }
                        gestionChats();
                    }else{
                        $("#chatMessage").addClass("hidden");
                        $("#txtGestion").hide();
                        $("#txtCierre").hide();
                        $("#txtInactivo").hide();
                        $("#txtCierreRobot").hide();
                        broadcastChannel.postMessage("SIN DEMORA");
                         setTimeout(function() {
                            gestionChats();
                        }, 60000);
                    }
                }
            }
        })
        .fail(function() {
            console.log("error");
        });

    },tiempo);
    
}



// Verifica si la función ya se está ejecutando en otra pestaña
function verificarEjecucionPrevia2() {

    let storeDay = localStorage.getItem('storeDay');

    if(storeDay != null && storeDay != 'undefined' && storeDay == storeYesterday){
      localStorage.removeItem("storeDay");  
      localStorage.setItem('storeDay', storeDayAssign);
    }else if(storeDay != null && storeDay != 'undefined' && storeDay == storeDayAssign){
        return true;
    }else{
        localStorage.setItem('storeDay', storeDayAssign);
    }

    return false;

}

window.addEventListener('beforeunload', function (e) {
    broadcastChannel.postMessage("SALIDA");
    localStorage.removeItem("storeDay");  
});


// Función que deseas ejecutar constantemente
function ejecutarFuncionConstante2() {
    // Verifica si la función ya se está ejecutando en otra pestaña
    if (verificarEjecucionPrevia2()) {
        return;
    }else{
        gestionChats(1);
    }
}

broadcastChannel.onmessage = function(event) {

    if(copy_js.action == 'add' && copy_js.controller == "assists"){
        return '';
    }

    var ids_conv = localStorage.getItem('ids_conv');

    if(ids_conv != null && typeof ids_conv != 'undefined'){
        $("#numberConversations").html(ids_conv);
    } 

    let FLUJOS_COTIZACION = localStorage.getItem('FLUJOS_COTIZACION');

    if(copy_js.action == 'add' && copy_js.controller.toLowerCase() == "quotations"){
        $("#closeBtnModal").removeClass("hidden");
    }

    if(event.data === 'INACTIVO'){
        $("#chatMessage").removeClass("hidden");
        $("#txtCierre").hide();
        $("#txtCierreRobot").hide();
        $("#txtGestion").hide();
        $("#txtInactivo").show();
    }else if (event.data === 'TOTAL_DEMORA') {

        FLUJOS_COTIZACION = FLUJOS_COTIZACION.split(',');

        if(typeof FLUJO_ACTUAL != 'undefined' && FLUJOS_COTIZACION.length > 0 && FLUJOS_COTIZACION.indexOf(FLUJO_ACTUAL) != -1){
            var existArr = true;
        }else{
            var existArr = false;
        }

        if(!existArr){
            $("#chatMessage").removeClass("hidden");
            $("#txtGestion").show();
            $("#txtCierre").hide();
            $("#txtCierreRobot").hide();
            $("#txtInactivo").hide();
        }
    }else if(event.data === 'TOTAL_DEMORA_USER'){
        $("#chatMessage").removeClass("hidden");
        $("#txtCierre").show();
        $("#txtGestion").hide();
        $("#txtCierreRobot").hide();
        $("#txtInactivo").hide();
    }else if(event.data === 'TOTAL_DEMORA_USER_ARCHIVED'){
        $("#chatMessage").removeClass("hidden");
        $("#txtCierre").show();
        $("#txtCierreRobot").show();
        $("#txtGestion").hide();
        $("#txtInactivo").hide();
    }else if(event.data === 'NO_DEMORA'){
        $("#txtGestion").hide();
        $("#txtCierre").hide();
        $("#txtCierreRobot").hide();
        $("#txtInactivo").hide();
        $("#chatMessage").addClass("hidden");
    }else{
        $("#chatMessage").addClass("hidden");
        $("#closeBtnModal").addClass("hidden");
        $("#txtGestion").hide();
        $("#txtCierre").hide();
        $("#txtCierreRobot").hide();
        $("#txtInactivo").hide();
    }
};




ejecutarFuncionConstante2(); 


$("#closeBtnModal").click(function(event) {
    event.preventDefault();
    $("#chatMessage").addClass("hidden");
    $("#closeBtnModal").addClass("hidden");
    $("#txtGestion").hide();
    $("#txtCierre").hide();
    $("#txtInactivo").hide();
    $("#txtCierreRobot").hide();
    gestionChats(30000);
});


// Llama a tu función


setTimeout(function() {    
    if($("#menuData").length){
        $("#menuData").slinky({
            title: true,
            speed: 500
        })
    }
}, 1000);


function validateIdeintificationNumber(number){
    var valuesIdentification = ["000000","123456","0123456","111111","222222","333333","444444","555555","666666","777777","888888","999999","111222"];

    for(var i = 0, length1 = valuesIdentification.length; i < length1; i++){
        if(valuesIdentification[i].indexOf(number) != -1){
            return false;
        }
    }

    const regex = /^[1-9]\d*$/;
    const str = number;

    var m = regex.exec(str);

    if(m == null){
        return false;
    }

    return true;

}


function validateNitNumber(number){
    var valuesIdentification = ["000000000","123456789","1234567890","012345678","111111111","222222222","333333333","444444444","555555555","666666666","777777777","888888888","999999999","1234567890123456789"];

    for(var i = 0, length1 = valuesIdentification.length; i < length1; i++){
        if(valuesIdentification[i].indexOf(number) != -1){
            return false;
        }
    }

    const regex = /^[1-9]\d*$/;
    const str = number;

    var m = regex.exec(str);

    if(m == null){
        return false;
    }


    return true;

}

function valideKey(evt){
    
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}


function setCustomerSelect2(id,dropDown = null,multiple = false){
    var options = {
        placeholder: "Buscar cédula",
        minimumInputLength: 3,
        multiple: multiple,
        language: "es",
        ajax: {
            url: copy_js.base_url+"prospective_users/get_user",
            dataType: 'json',
             data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              params.page = params.page || 1;

              return {
                results: data.items,
                // pagination: {
                //   more: (params.page * 30) < data.total_count
                // }
              };
            },
        }
    };

    if (dropDown != null) {
        options["dropdownParent"] = $(dropDown);
    }

    $("body").find(id).select2(options);
}


setTimeout(function() {
    if($(".selectTo2").length){
        $(".selectTo2").select2();
    }
}, 2000);

function setAndDataPicker($idFieldPpal, $idBtn, $idFieldIni, $idFieldEnd, $urlFunction, functionResponse, click = true, otherArgs = {}){
    var date = moment(); //Get the current date
    date.format("YYYY-MM-DD");
    if($($idFieldPpal).length){

        
        $($idFieldPpal).daterangepicker({
            "showDropdowns": false,
            "opens": "center",
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Último año': [moment().subtract(365, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment()],
                'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            "locale": {
                "format": "YYYY-MM-DD",
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": labelCancel,
                "fromLabel": "Desde",
                "toLabel": "Hasta",
                "customRangeLabel": "Definir rango",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            },
            "alwaysShowCalendars": true,
             "startDate": date,
             "endDate": date,
            "maxDate": date
        }, function(start, end, label) {

        
            $($idFieldIni).val(start.format('YYYY-MM-DD'));
            $($idFieldEnd).val(end.format('YYYY-MM-DD'));

            if($($idBtn).length){
                $($idBtn).trigger('click')
            }

        });

        // ****************************

        $($idBtn).on('click', function(event) {
            event.preventDefault();
            var ini = $($idFieldIni).val();
            var end = $($idFieldEnd).val();


            if(Object.keys(otherArgs).length > 0 ){
                otherArgs.type = $("#typeFlujoFilter").val();
            }

            $.post( $urlFunction, {ini,end,...otherArgs}, function(data) {
                functionResponse(data);
            });
        });

        if (click) {
            $($idBtn).trigger("click");
        }


    }
}