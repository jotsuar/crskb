<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A photo editing application based on the Cropper.js">
    <meta name="author" content="Chen Fengyuan">
    <title>Editor de imágnes</title>
    <link rel="stylesheet" href="https://unpkg.com/font-awesome@4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <script>
      var rootUrl = "<?php echo $root_url != "/" ? $root_url : Router::url("/",true) ?>";
    </script>
  </head>
  <body>
    <div id="app"></div>
    <script src="https://fengyuanchen.github.io/shared/google-analytics.js" crossorigin="anonymous"></script>
    <?php echo $this->Html->script("main_editor.js?".rand()); ?>
</html>
