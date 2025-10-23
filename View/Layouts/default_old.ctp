<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; utf-8">


    <title>
      <?php echo Configure::read('Application.name') ?>
    </title>
    <?php
      echo $this->Html->css(array('styleApp.css?'.time(),'media.css?'.time(),'printpdf.css','bootstrap.css','lib/sweetalert.css','font-awesome/css/font-awesome.css','font/flaticon.css','font2/flaticon3.css','lib/summernote.min.css','lib/select2.min.css','lib/bootstrap-tagsinput.css','lib/bootstrap-datepicker.css','lib/slick.css','dropify.min'));
      if ($movileAccess) {
         echo $this->Html->css(array('slinky.min'));
      }
      echo $this->fetch('AppCss');
      echo $this->Html->meta('favicon.ico','img/favicon.png',array('type' => 'icon'));
      echo $this->fetch('jqueryApp');
    ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.17/css/dataTables.bootstrap4.min.css">
    <script>
      var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
       var actual_url = "<?php echo Router::url($this->here, true) ?>?";
       var movileAccess = "<?php echo $movileAccess? 1 : 0; ?>";
    </script>

    <?php if ($movileAccess): ?>
      
      <style>
        #menuData{
          height: auto !important;
        }
        .slinky-theme-default a:not(.back) {
            color: #fff;
            padding: 1em;
        }
        #menuData li {
            border: 0.5px solid #ddd;
        }
        #menuData a.back {
            font-size: 20px;
            color: #fff;
        }
        .slinky-theme-default {
            background: #004990;
            color: #fff;
        }
        .slinky-theme-default .title {
            color: #fff;
            padding: 1em;
            font-size: 16px;
        }

        .slinky-theme-default .next::after, .slinky-theme-default .back::before {
            background: none;
            background-size: 1em;
            content: '>>';
            height: auto;
            opacity: 1;
            transition: 200ms;
            width: 1em;
            color: #fff;
            font-size: 20px;
            margin-left: 5px;
        }
            
      </style>

    <?php endif ?>
  </head>
  <?php if (AuthComponent::user('id')) { ?>
    <body class="<?php echo $this->request->controller?> fixed-nav sticky-footer bg-dark <?php echo $this->request->controller?><?php echo $this->request->action ?>  <?php echo AuthComponent::user('role'); ?> <?php echo $movileAccess ? "movilDataRespnisive" : "" ?>" id="page-top">
  <?php } else { ?>
    <body class="<?php echo $this->request->controller?><?php echo $this->request->action ?> offline <?php echo AuthComponent::user('role'); ?> <?php echo $movileAccess ? "movilDataRespnisive" : "" ?>" id="page-top">
  <?php } ?>
    <?php 
      echo $this->element('modal');
      echo $this->element('variables_js');
      echo $this->fetch('variablesAppScript');
      echo $this->Html->script(array('app.js?'.rand()));
      echo $this->Html->script(array('bootstrap-notify.min.js?'.rand()));
      if (AuthComponent::user('id')) {
        echo $this->fetch('fullCalendar');
        echo $this->fetch('gmailScript');
      }
    ?>
    <div class="body-loading">
      <div class="blobs">
        <img src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>" class="">
        <div class="blob-center"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
      </div>
      <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
          <filter id="goo">
            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
            <feBlend in="SourceGraphic" in2="goo" />
          </filter>
        </defs>
      </svg>
    </div>

    <div id="message_alert">
        <?php echo $this->Flash->render(); ?>
    </div>

    <?php if (AuthComponent::user('id')):
      echo $this->element('nav');
    endif; ?>

    <div id="search-modal" class="overlay">
      <div class="overlay-content searchoverlay">
        <div class="type-wrap">
          <div id="typed-strings" style="display: none;">
            <!-- <span>¿Qué estas buscando?</span>
            <p>¿Un cliente, un producto o una cotización?</p> -->
            <p>¡Busca ahora!</p>
          </div>
          <span id="typed"></span>
        </div>
        <input type="text" id="txt_buscar" class="form-control search-menu cpo fullsearch validafocus" placeholder="¿Qué estás buscando?">
        <div class="actions-search" id="close-search">
            <i class="fa fa-lg fa-times"></i>
        </div>
        <div id="resultadoBuscador">
          <div class="cantresult"></div>
          <div class="list-group heightresults" id="resultadosBusqueda"></div>
        </div>
      </div>
    </div>
    <div class="content-wrapper">
      <div class="container-fluid pd30">
        <div class="content-all">
          <?php echo $this->fetch('content'); ?>
        </div>
      </div>
      <?php echo $this->element("flujo") ?>
      <?php echo $this->element('footer')?>
    </div>
    <?php
      if ($movileAccess) {
         echo $this->Html->script(array('slinky.min'));
      }
      echo $this->Html->script(array('lib/parsley/parsley-2.8.1.min.js','lib/parsley/es.js','lib/sweetalert.js','bootstrap.bundle.js','jquery.easing.min.js','sb-admin.js','lib/summernote.js','lib/select2/select2.min.js','lib/select2/i18n/es.js','lib/jquery.dataTables.min.js','lib/bootstrap-tagsinput.js','lib/date_picker/bootstrap-datepicker.js','lib/date_picker/locales/bootstrap-datepicker.es.min.js','dropify.min'));
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8InukWqbwLrQkSlc4-jnCJj04HXHMzYM&libraries=places" type="text/javascript"></script>
   
    <script src="https://cdn.datatables.net/1.10.17/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://demos.codexworld.com/print-specific-area-of-web-page-using-jquery/jquery.PrintArea.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
     <?php

      echo $this->fetch('AppScript');
    ?>
    <!-- <?php // echo $this->element('sql_dump'); ?> -->
  </body>
</html>