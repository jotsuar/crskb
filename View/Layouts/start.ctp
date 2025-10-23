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
      echo $this->Html->css(array('styleApp.css','media.css','bootstrap.css'));
      echo $this->fetch('AppCss');
      echo $this->Html->meta('favicon.ico','img/favicon.png',array('type' => 'icon'));
      echo $this->fetch('jqueryApp');
      echo $this->Html->script(array('lib/parsley/parsley-2.8.1.min.js','lib/parsley/es.js'));
    ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.17/css/dataTables.bootstrap4.min.css">
    <script>
      var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
       var actual_url = "<?php echo Router::url($this->here, true) ?>?";
    </script>
  </head>
  <?php if (AuthComponent::user('id')) { ?>
    <body class="<?php echo $this->request->controller?> <?php echo AuthComponent::user('role'); ?>">
  <?php } else { ?>
    <body class="<?php echo $this->request->controller?><?php echo $this->request->action ?> userslogin offline <?php echo AuthComponent::user('role'); ?>" id="page-top">
  <?php } ?>
  <div class="body-loading" id="loaderKebco">
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
    <?php 
      echo $this->fetch('appBlock');
      echo $this->element('variables_js');
      echo $this->fetch('variablesAppScript');
    ?>

    <div id="message_alert"><?php echo $this->Flash->render(); ?></div>

    <div class="content-wrapper">
      <div class="container-fluid pd30">
        <div class="content-all">
          <?php echo $this->fetch('content'); ?>
        </div>
      </div>
      <?php echo $this->element('footer')?>
    </div>
    <?php echo $this->Html->script('app.js?'.rand()) ?>
    <?php
      echo $this->fetch('AppScript');
    ?>
    <script>
      movileAccess = 0;
      $(document).ready(function() {
        $("#loaderKebco").hide(2000);
      });
    </script>
  </body>
</html>