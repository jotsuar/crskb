<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; utf-8">


    <title>
      <?php if (isset($datosQuation) && !empty($datosQuation)): ?>
        <?php echo $datosQuation["Quotation"]["name"]." - ".$datosQuation["Quotation"]["codigo"] ?>
      <?php else: ?>
        <?php echo Configure::read('Application.name') ?>
      <?php endif ?>
    </title>
    <?php
      $varsActuals = get_defined_vars();
      if (isset($varsActuals["dataForView"]["error"])) {
        echo $this->Html->script(array('lib/jquery-3.0.0.js'));
      }

      $ccsFile = AuthComponent::user("id") ? '/vendors/custom.css' : 'newStyle.css';
      echo $this->Html->css(array('/vendors/bootstrap/dist/css/bootstrap.min.css',$ccsFile,'styleApp.css','media.css','printpdf.css','lib/sweetalert.css',
        '/vendors/font-awesome/css/font-awesome.css','font/flaticon.css','font2/flaticon3.css','lib/summernote.min.css','lib/select2.min.css','lib/bootstrap-tagsinput.css','lib/bootstrap-datepicker.css','lib/slick.css','/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css','dropify.min'));
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script>
       var actual_uri = "<?php echo Router::reverse($this->request, true) ?>";
       var actual_url = "<?php echo Router::url($this->here, true) ?>?";
       var movileAccess = "<?php echo $movileAccess? 1 : 0; ?>";
       var rootUrl = "<?php echo Router::url("/",true) ?>";
       var storeDayAssign = '<?php echo date("Ymd") ?>';
       var storeYesterday = '<?php echo date("Ymd",strtotime('-1 day')); ?>';
       var TIME_USER = <?php echo json_encode($this->Utilities->times_out_chat(AuthComponent::user("id"))); ?>;

       <?php if (isset($rejected_flow)): ?>
         var REJECTED_ID = <?php echo $rejected_flow["id"] ?>;
         var REJECTED_REASON = '<?php echo $rejected_flow["rejected_reason"] ?>';
       <?php endif ?>

    </script>


  </head>
  <?php if (AuthComponent::user('id')) { ?>
    <body class="<?php echo $this->request->controller?> nav-md footer_fixed <?php echo $this->request->controller?><?php echo $this->request->action ?>  <?php echo AuthComponent::user('role'); ?> <?php echo $movileAccess ? "movilDataRespnisive" : "" ?>" style="background-color: #004990" id="page-top">
  <?php } else { ?>
    <body style="word-break: break-word;" class="<?php echo $this->request->controller?><?php echo $this->request->action ?> offline <?php echo AuthComponent::user('role'); ?> <?php echo $movileAccess ? "movilDataRespnisive" : "" ?>" id="page-top">
  <?php } ?>
    <div class="loading hidden" id="chatMessage">
      <button type="button" class="btn btn-danger float-right hidden" id="closeBtnModal">
        <i class="fa fa-close vtc"></i>
      </button>
      <div class='uil-ring-css' style='transform:scale(0.79);'>
         <h1 class="text-white" id="txtGestion">
           Tienes un chat pendiente de gestión
         </h1>
         <h1 class="text-white" id="txtCierre">
           Tienes uno o varios chats  pendientes de cierre (archivar)
         </h1>
         <h1 class="text-white" id="txtCierreRobot">
           Tienes uno o varios chats archivados sin contacto con un cliente que generó una cotización del robot
         </h1>
         <h1 class="text-white" id="txtInactivo">
           Superaste el límite de tiempo permitido inactivo en el chat.
         </h1>
         <h1 class="text-white" id="numberConversations"></h1>
        <div>
         
        </div>
      </div>
    </div>
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
    <div class=" <?php echo $this->request->params["action"] == "reports" && $this->request->params["controller"] == "pages" && !AuthComponent::user("id") ? 'container-fluid' : 'container' ?>  body" style="background: #edefef url('<?php echo $this->Html->url("/img/assets/pattern.svg") ?>') 0 0 repeat; <?php echo $this->request->params["action"] == "reports" && $this->request->params["controller"] == "pages" && !AuthComponent::user("id") ? '' : '' ?> ">
      <div class="main_container">
        <?php 
          
          echo $this->element('variables_js');
          echo $this->fetch('variablesAppScript');
          echo $this->Html->script(array('lib/sweetalert.js','app.js?'.time()));
          echo $this->Html->script(array('bootstrap-notify.min.js'));
          if (AuthComponent::user('id')) {
            echo $this->fetch('fullCalendar');
            echo $this->fetch('gmailScript');
          }
        ?>


        <div id="message_alert">
            <?php echo $this->Flash->render(); ?>
        </div>

        <?php if (AuthComponent::user('id')): ?>
          <div class="col-md-3 left_col menu_fixed mCustomScrollbar _mCS_1 mCS-autoHide classnoprint">
            <div class="left_col scroll-view">
              <div class="navbar nav_title" style="border: 0;">
                <a href="<?php echo $this->Html->url("/") ?>" class="site_title p-2">
                  <img src="<?php echo $this->Html->url("/img/assets/brand2.png") ?>" alt="KEBCO SAS" class="img-fluid">
                </a>
              </div>

              <div class="clearfix"></div>

              <?php echo $this->element("new_nav"); ?>
              
            </div>
          </div>
          <?php echo $this->element("new_top_nav") ?>
          <?php //echo $this->element('nav'); ?>
        <?php endif; ?>
        <div class="right_col pt-5 mt-xs-50 mb-5 pb-5" role="main">
          <!-- top tiles -->
          <div class="p-2 content-all" >
            <?php echo $this->fetch('content'); ?>
            
              <div class="modal fade " id="modalIngresoFact" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión de ingreso de facturas</h5>
                    </div>
                    <div class="modal-body">
                      
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="flujoFactBusca" placeholder="Escriba el número de flujo" aria-label="Escriba el número de flujo" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="buttonBuscaFact">Buscar</button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div id="listadoFacturasActuales"></div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade " id="modalMensajeImportante" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document" >
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h5 class="modal-title" id="exampleModalScrollableTitle">Envió de notificación general</h5>
                    </div>
                    <div class="modal-body" id="cuerpoMensajeImportante">

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php if (AuthComponent::user("id") && AuthComponent::user("role") != "Asesor Externo"): ?>
              <div class="modal fade " id="modalIngrsoRC" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document" >
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h5 class="modal-title" id="exampleModalScrollableTitle">Gestión de ingreso de RC</h5>
                    </div>
                    <div class="modal-body">
                      
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="flujoFactBuscaRC" placeholder="Escriba el número de flujo" aria-label="Escriba el número de flujo" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="buttonBuscaRC">Buscar</button>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div id="rcAct"></div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif ?>
            <!-- Modal -->
            <div class="modal fade " id="modalMensajeBoss" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg3" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Envío de mensaje directo al Gerente General</h5>
                  </div>
                  <div class="modal-body" id="cuerpoBoss">
                    <?php echo $this->Form->create(false,array('enctype'=>"multipart/form-data",'data-parsley-validate'=>true,"autocomplete"=>"off","id"=>"formMessageBoss")); ?>
                      <?php echo $this->Form->input('subject',array("label"=>"Asunto del mensaje",'value' => "", "required" => true, "id" => "subjectMessage" ,"options" => Configure::read("Subjects"), "empty" => "Seleccionar" )); ?>
                      <?php echo $this->Form->input('message',array("label"=>"Cuerpo del mensaje, por favor detalle el mensaje a enviar.",'value' => "", "required" => false, "id" => "bodyMessage" )); ?>
                      <div class="form-group">
                        <input type="submit" class="btn btn-success float-right" value="Enviar mensaje">
                      </div>
                  <?php echo $this->Form->end(); ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <?php echo $this->Html->script("controller/facturas/admin.js?".rand(),    array('block' => 'AppScript')); ?>
          </div>
          <?php echo $this->element("flujo") ?>
          <?php echo $this->element('footer')?>
          <?php echo $this->element("notify_modal") ?>
        </div>

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



      </div>
    </div>

    
    

    <!-- <div class="content-wrapper"> <div class="container-fluid pd30"> <div
    class="content-all"> <?php //echo $this->fetch('content'); ?> </div>
    </div> <?php //echo $this->element("flujo") ?> 
    <?php //echo $this->element('footer')?> </div> --> 
    <?php 
      echo $this->element('modal');
      if ($movileAccess) { //   
        //echo $this->Html->script(array('slinky.min')); // 
      } 
      echo $this->Html->script(array('lib/parsley/parsley-2.8.1.min.js','lib/parsley/es.js','/vendors/bootstrap/dist/js/bootstrap.bundle.js','jquery.easing.min.js','sb-admin.js','lib/summernote.js','lib/select2/select2.min.js','lib/select2/i18n/es.js','lib/jquery.dataTables.min.js','lib/bootstrap-tagsinput.js','lib/date_picker/bootstrap-datepicker.js','lib/date_picker/locales/bootstrap-datepicker.es.min.js','dropify.min','/vendors/custom.js','/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'));
    ?> 
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
      $("body").find(".select22").select2();
    </script>
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8InukWqbwLrQkSlc4-jnCJj04HXHMzYM&libraries=places"
    type="text/javascript"></script>
   
    <script src="https://cdn.datatables.net/1.10.17/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://demos.codexworld.com/print-specific-area-of-web-page-using-jquery/jquery.PrintArea.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <?php $whitelist = array('127.0.0.1','::1');  ?>

      <?php if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)): ?>
        

      <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js" type="text/javascript"></script>
      <?php if (AuthComponent::user("id")): ?>
        <script>
            
            const beamsClient = new PusherPushNotifications.Client({
              instanceId: 'c40fa57b-967c-42e6-8ee6-80084a1aef4c',
            });

            beamsClient.start()
              .then(() => beamsClient.addDeviceInterest('inter<?php echo AuthComponent::user("id") ?>'))
              .then(() => console.log('Successfully registered and subscribed!'))
              .catch(console.error);

        </script>
      <?php else: ?>
        <script>
          const beamsClient = new PusherPushNotifications.Client({
              instanceId: 'c40fa57b-967c-42e6-8ee6-80084a1aef4c',
          });
          const.beamsClient.stop().then( () => {
            console.log("aaaaaa")
          } )
        </script>
      <?php endif ?>

    <?php endif ?>
     <?php

      echo $this->fetch('AppScript');
    ?>
    <?php if (AuthComponent::user("role") == "Gerente General"): ?>


        <script>
          var nIntervId;

           function actualizarInformacion() {
            $("#inicioSz").attr("disabled","disabled");
            $("#inicioSz").hide();
            $("#detieneSz").show();
              nIntervId = setInterval(getDashboard, 60000);
           }

           function getDashboard() {
            $(".body-loading").show();
              $.get("<?php echo Router::url("/",true) ?>pages/home_adviser/layout", function(data) {
                $(".content-all").html(data);
              $(".body-loading").hide();
              });
           }

           function detenerCambio() {
            $("#inicioSz").removeAttr("disabled");
            $("#inicioSz").show();
            $("#detieneSz").hide();
              clearInterval(nIntervId);
           }
        </script>

    <?php endif ?>
    <!-- <?php // echo $this->element('sql_dump'); ?> -->
  </body>
</html>