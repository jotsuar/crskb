<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php

      $ccsFile = AuthComponent::user("id") ? '/vendors/custom.css?'.time() : 'newStyle.css';
      echo $this->Html->css(array('/vendors/bootstrap/dist/css/bootstrap.min.css',$ccsFile,'styleApp.css?'.time(),'media.css?'.time(),'printpdf.css','lib/sweetalert.css','/vendors/font-awesome/css/font-awesome.css','font/flaticon.css','font2/flaticon3.css','lib/summernote.min.css','lib/select2.min.css','lib/bootstrap-tagsinput.css','lib/bootstrap-datepicker.css','lib/slick.css','/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css','dropify.min'));
      if ($movileAccess) {
         echo $this->Html->css(array('slinky.min'));
      }
      echo $this->fetch('AppCss');
      echo $this->Html->meta('favicon.ico','img/favicon.png',array('type' => 'icon'));
      echo $this->fetch('jqueryApp');
    ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600,700" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.17/css/dataTables.bootstrap4.min.css">
    <script>
      var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
       var actual_url = "<?php echo Router::url($this->here, true) ?>?";
       var movileAccess = "<?php echo $movileAccess? 1 : 0; ?>";
       var rootUrl = "<?php echo Router::url("/",true) ?>";
    </script>
    <?php
      echo $this->fetch('AppCss');
      echo $this->fetch('jqueryApp');
      echo $this->fetch('fullCalendar');
    ?>
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900" rel="stylesheet">
  </head>
  <body id="gestiones-dashboard">
    <?php
      echo $this->element('variables_js');
      echo $this->fetch('variablesAppScript');
      echo $this->Html->script(array('app.js?'.rand()));
      echo $this->Html->script(array('bootstrap-notify.min.js?'.rand()));
    ?>
    <div class="p-2 content-all" >
      <?php echo $this->fetch('content'); ?>
    </div>
    <?php echo $this->Html->script(array('lib/parsley/parsley-2.8.1.min.js','lib/parsley/es.js','lib/sweetalert.js','/vendors/bootstrap/dist/js/bootstrap.bundle.js','jquery.easing.min.js','sb-admin.js','lib/summernote.js','lib/select2/select2.min.js','lib/select2/i18n/es.js','lib/jquery.dataTables.min.js','lib/bootstrap-tagsinput.js','lib/date_picker/bootstrap-datepicker.js','lib/date_picker/locales/bootstrap-datepicker.es.min.js','dropify.min','/vendors/custom.js','/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')); ?>
    <script>
      var OPTIONSDP = {
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
    </script>
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8InukWqbwLrQkSlc4-jnCJj04HXHMzYM&libraries=places"
    type="text/javascript"></script>
   
    <script src="https://cdn.datatables.net/1.10.17/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://demos.codexworld.com/print-specific-area-of-web-page-using-jquery/jquery.PrintArea.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
     <?php

      echo $this->fetch('AppScript');
    ?>
    </div>
  </body>
</html>