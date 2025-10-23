<div class="bg-white col-md-12 my-4 pl-0">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link <?php echo $action == "index" ? "active" : "" ?>" href="<?php echo $this->Html->url( ["action" => "index", "controller" => "clientes"] ) ?>">Ordenes generadas</a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link <?php echo $action == "shippings" ? "active" : "" ?>" href="<?php echo $this->Html->url( ["action" => "shippings", "controller" => "clientes"] ) ?>">Despachos generados</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo $action == "pqrs" ? "active" : "" ?>" href="<?php echo $this->Html->url( ["action" => "add", "controller" => "pqrs"] ) ?>"  >PQRS</a>
    </li>
     <li class="nav-item">
      <a class="bg-blue nav-link text-white contactoNormal" data-type="normal" href="<?php echo $this->Html->url( ["action" => "add", "controller" => "contacto"] ) ?>"  >Quiero que me contacten</a>
    </li>
    <li class="nav-item dropdown dropdown nav-item position-absolute" style="right: 0">
      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo $cliente["name"] ?> </a>
      <div class="dropdown-menu">
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?php echo $this->Html->url( ["action" => "logout", "controller" => "clientes"] ) ?>">Cerar sesión</a>
      </div>
    </li>
  </ul>
</div>

<style>
  .container.body {
    max-width: 80%;
  }
</style>

<script>
    
    var clienteData = "<?php echo $this->Utilities->encryptString($cliente["id"]) ?>";
    var clienteType = "<?php echo $this->Utilities->encryptString($cliente["type"]) ?>";

</script>

<?php 
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),          array('block' => 'jqueryApp'));
echo $this->Html->script("controller/clientes/login.js?".rand(),          array('block' => 'AppScript'));
?>