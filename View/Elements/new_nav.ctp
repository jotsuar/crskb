  <?php if (!$movileAccess): ?>
  
  <!-- menu profile quick info -->
    <div class="clearfix mt-3 profile text-center text-white">
      <?php $roles = array(Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Servicio Técnico'),Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente')) ?>
      <?php if (in_array(AuthComponent::user('role'), $roles)): ?>
        <p class="mb-0">Mis ventas de hoy</p>
        <h2>$ <span id="countMetasDayUser"></span></h2>
        <p>Mis ventas del mes $<span id="countSalesUserMonth"></span></p>
      <?php endif ?>
      <!-- <p>Ventas del mes $<span id="countSalesMonth"></span></p> -->
    </div>
    <!-- /menu profile quick info -->
    <?php endif; ?>
  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>Menú principal</h3>
      <?php 
        echo $this->element("new/gerente_general");
      ?>
    </div>

  </div>
  <!-- /sidebar menu -->