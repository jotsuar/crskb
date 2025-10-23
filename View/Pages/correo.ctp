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
            <a href="javascript:void(0)" id="btn_cerrar" class="hidden"><i class="fa fa-power-off"></i> Cerrar sesion</a>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 filterbtns">
            <a id="btn_from_cliente_filter">Enviados </a>
            <a id="btn_to_cliente_filter"> Recibidos </a>
          </div>
        </div>    

          
    </div>
  </div>

  <div class="col-md-10">
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
</div>

<?php
  echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),             array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/pages/google_client.js?".rand(),      array('block' => 'fullCalendar'));
	echo $this->Html->script("controller/pages/gmail.js?".rand(),		           array('block' => 'gmailScript'));
	echo $this->Html->script("controller/pages/correo.js?".rand(),		         array('block' => 'gmailScript'));
?>