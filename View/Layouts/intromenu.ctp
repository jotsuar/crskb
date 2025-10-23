<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
      <?php echo Configure::read('Application.name') ?>
    </title>
    <?php
      echo $this->Html->css(array('styleApp.css','media.css','printpdf.css','bootstrap.css','lib/sweetalert.css','font-awesome/css/font-awesome.css','font/flaticon.css','font2/flaticon3.css'));
      echo $this->fetch('AppCss');
      echo $this->Html->meta('favicon.ico','img/favicon.png',array('type' => 'icon'));
      echo $this->fetch('jqueryApp');
    ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.17/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
  </head>
  <?php if (AuthComponent::user('id')) { ?>
    <body class="bg-blue <?php echo AuthComponent::user('role'); ?>" id="page-top">
  <?php } else { ?>
    <body class=" <?php echo AuthComponent::user('role'); ?>" id="page-top">
  <?php } ?>
    <?php 
      echo $this->element('modal');
      echo $this->element('variables_js');
      echo $this->Html->script(array('app.js?'.rand()));
      echo $this->fetch('variablesAppScript');
    ?>


    <div id="message_alert">
        <?php echo $this->Flash->render(); ?>
    </div>

          <?php echo $this->fetch('content'); ?>
 

    <?php
      echo $this->fetch('AppScript');
    ?>
  </body>
</html>