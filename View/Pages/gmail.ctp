<?php
  echo $this->Html->css(array('controller/pages/gmail.css'),          array('block' => 'AppCss'));
?>

<div class="body-loading-mail">
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
<div class="row emailstyle">
  <div class="col-md-2 dataloginheader">
    <div class="blockempty">
        <div class="usersesion">
          Iniciaste sesión con
          <p id="name_usuario_sesion"></p>
        </div>
        <button id="btn_autorizar" class="btn btn-primary hidden">Logueate con gmail</button>
        <div class="menuemail">
            <a href="#compose-modal" data-toggle="modal" id="compose-button" class="hidden"> Redactar</a>
            <a href="javascript:void(0)" id="btn_bandeja" class="hidden activeitems"><i class="fa fa-envelope"></i>Recibidos</a>
            <a href="javascript:void(0)" id="btn_leidos"><i class="fa fa-envelope"></i> Mensajes leidos (<span id="countMensajesLeidos"></span>)</a>
            <a href="javascript:void(0)" id="btn_no_leidos"><i class="fa fa-envelope"></i> Mensajes no leidos (<span id="countMensajesNotLeidos"></span>)</a>
            <a href="javascript:void(0)" id="btn_enviados"><i class="fa fa-share-square-o"></i> Enviados</a>
            <a href="javascript:void(0)" id="btn_destacados"><i class="fa fa-star"></i> Destacados</a>
            <a href="javascript:void(0)" id="btn_spam"><i class="fa fa-exclamation-circle"></i> Eliminados y Spam</a>
            <a href="javascript:void(0)" id="btn_cerrar" class="hidden"><i class="fa fa-power-off"></i> Cerrar sesion</a>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
              <h3 class="filterclientgmail">Filtro por Cliente</h3>
              <?php echo $this->Form->input('cliente_filtrar',array('label' => false,'options' => $clientes,'empty' => 'Selecciona un cliente')); ?>
          </div>
          <div class="col-md-12 filterbtns">
            <a id="btn_from_cliente">Enviados </a>
            <a id="btn_to_cliente"> Recibidos </a>
          </div>
        </div>    

        <hr>
        <div class="row">
          <div class="col-md-12">
              <h3 class="filterclientgmail">Accesos</h3>
          </div>
          <div class="col-md-12 filterbtns">
            <a id="Gmail">Gmail </a>
            <a id="Drive">Drive </a>
          </div>
        </div>          
    </div>
  </div>

  <div class="col-md-5">
    <div class="blockwhite">
      <table cellpadding="0" cellspacing="0" class="table table-hover table_resultados table-inbox hidden">
        <thead>
          <tr>
            <th class="orderc">#</th>
            <th class="de">De</th>
            <th class="asunto">Asunto</th>
            <th class="para">Para</th>
            <th class="fecha">Fecha - hora</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

   <div class="col-md-5">
    <div class="blockwhite">

    </div>
  </div> 
</div>
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title" id="modal_compose-modal">Redactar correo electrónico</h2>
      </div>
      <div class="modal-body">
        <form>
          <?php 
          echo $this->Form->input('compose-to',array('placeholder'  => 'Para:','id' => 'compose-to','label' => false));
          echo $this->Form->input('compose-subject',array('placeholder'  => 'Asunto','id' => 'compose-subject','label' => false));
          echo $this->Form->input('compose-message',array('type' => 'textarea','placeholder'  => 'Mensaje:','id' => 'compose-message','label' => false));
          ?>
        </form>
      </div>
      <div class="modal-footer">
        <a class="cancelmodal" data-dismiss="modal">Cancelar</a>
        <a class="savedata send-button">Enviar</a>
      </div>
    </div>
  </div>
</div>

<?php
  echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),          array('block' => 'jqueryApp'));
  echo $this->Html->script("controller/pages/google_client.js?".rand(),   array('block' => 'fullCalendar'));
  echo $this->Html->script("controller/pages/gmail.js?".rand(),		        array('block' => 'gmailScript'));
?>