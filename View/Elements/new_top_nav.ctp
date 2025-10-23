<div class="top_nav fixed-top">
  <div class="nav_menu">
      <div class="nav toggle">
        <a id="menu_toggle" class="pt-2"><i class="fa fa-bars"></i></a>
      </div>
      
      <nav class="nav navbar-nav">

        <ul class=" navbar-right">
          
          <li class="nav-item dropdown open" style="padding-left: 15px;">
            <a href="javascript:;" class="dropdown-toggle text-white user-profile" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
              <img src="<?php echo $this->Html->url('/img/users/'.AuthComponent::user('img')); ?>" alt="">
              <?php
                $nombreusuario = AuthComponent::user('name');$solonombre = explode(" ",$nombreusuario);echo $solonombre[0];
              ?>
            </a>
            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item py-2"  href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'profile')) ?>"> Perfil</a>
                <?php if ($movileAccess): ?>              
                  <a href="#" class="dropdown-item py-2"id="nuevoFlujoCrmData">
                    <i class="fa fa-x fa-plus-circle"></i> 
                    Crear oportunidad
                  </a>                  
                  <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_tienda")) ?>" class="dropdown-item py-2" id="nuevoFlujoTienda">
                    <i class="fa fa-x fa-plus-circle"></i> 
                    Venta en tienda
                  </a>
                  <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_especial")) ?>" class="dropdown-item py-2"  id="simpleflow">
                    <i class="fa fa-x fa-plus-circle"></i> 
                    Flujo expresss
                  </a>
                  <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "pendientes_gestion")) ?>" class="bg-aguamarina dropdown-item py-2 text-white">
                    <i class="fa fa-x fa fa-x fa-warning" style="    animation: spinner-grow 1s ease-in-out infinite;"></i> 
                    Pendientes gestión
                  </a>
                    <?php if (in_array(AuthComponent::user("id"), $usersApprovePermission)): ?>            
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>" class="dropdown-item py-2 counterAprove bg-danger text-center" style="    padding: 0 10px !important; font-size: 20px !important; color: #fff">0</a> 
                    <?php endif ?>
                    <?php if (in_array(AuthComponent::user("role"),["Gerente General", "Logística"])): ?>
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'facturas_bloqueadas')) ?>" class="dropdown-item bg-blue py-2"> <i class="fa fa-check vtc"></i> Aprobar facturas </a>

                    <?php endif ?>
                    <?php if (in_array(AuthComponent::user("role"),  ["Gerente General2"] )): ?>            
                        
                        <a href="<?php echo $this->Html->url(array('controller'=>'manages','action'=>'message')) ?>" class="dropdown-item bg-azul py-2" id="sendMessajeAll"> <i class="fa fa-plus vtc"></i> <i class="fa fa-mail vtc"></i> Enviar mensaje a todos </a>  
                    <?php endif ?>
                    <?php if (AuthComponent::user("role") == "Gerente General" ): ?>            
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee_cancel')) ?>" class="dropdown-item py-2 bg-azul text-center" style="color: #fff">
                        Solicitudes de cancelación
                      </a> 
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprobar_creditos')) ?>" class="dropdown-item bg-blue py-2"> Aprobar creditos </a>
                      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprobar_rechazos')) ?>" class="dropdown-item bg-azul py-2"> Aprobar Rechazos </a>
                    <?php endif ?>
                <?php endif ?>
                <a class="dropdown-item py-2"  href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout')) ?>"><i class="fa fa-sign-out pull-right"></i> Cerrar sesión</a>
            </div>
          </li>
          
          <?php if (AuthComponent::user("role") != "Publicidad"): ?>
            

            <li role="presentation" class="nav-item dropdown open d-xs-none">
            <a href="javascript:;" class="dropdown-toggle info-number text-white" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell-o"></i>
              <span class="indicator badge bg-green" id="count_notificaciones">
                <?php echo $this->Utilities->count_notificaciones_user(); ?>
              </span>
            </a>
            <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1" id="paint_notificaciones">
              
            </ul>
          </li>
            
          <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>    
             <li role="presentation" class="nav-item dropdown open d-xs-none">
            <a href="#" class="nav-link h28 p-1 br0" data-toggle="tooltip" data-placement="right" title="Crear flujo" id="nuevoFlujoCrmData">
              <i class="fa fa-x fa-plus-circle"></i> 
              Crear oportunidad
            </a>
          </li>  

             <li role="presentation" class="nav-item dropdown  open d-xs-none">
             <div class="dropdown mr-1 styledrop">
              <button type="button" class="btn bg-success text-white h28 dropdown-toggle p-1 br0" style="padding: 0.2rem !important;" data-toggle="dropdown" aria-expanded="false" data-offset="-90,10">
                 <i class="fa fa-check vtc"></i>  Aprobaciones
              </button>
              <div class="dropdown-menu">
                <?php if (in_array(AuthComponent::user("id"), $usersApprovePermission)): ?>            
                  <a class="dropdown-item" style="background-color: #c9d1db !important;" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>">Cotizaciones <span class="counterAprove bg-red text-white p-1">0</span></a>
                <?php endif ?>

                <?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("email") == 'logistica@kebco.co' ): ?>
                  <a class="dropdown-item bg-gris" href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "aprovee_cancel")) ?>">
                    Cancelaciones de flujos
                  </a>
                  <a class="dropdown-item" style="background-color: #c9d1db !important;" href="<?php echo $this->Html->url(array('controller'=>'autorizations','action'=>'index')) ?>">
                    Autorización de Abonos
                  </a> 
                
                  <?php if (AuthComponent::user("role") == "Gerente General"): ?>
                    <a  href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprobar_creditos')) ?>" class="dropdown-item bg-gris">  Aprobar creditos </a>
                    <a  href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprobar_rechazos')) ?>" class="dropdown-item" style="background-color: #c9d1db !important;"> Aprobar rechazos </a>
                  <?php endif ?>
                <?php endif ?>
                <?php if (in_array(AuthComponent::user("role"),["Gerente General", "Logística"])): ?>
                  <a class="dropdown-item bg-gris" href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'facturas_bloqueadas')) ?>">
                    Facturas
                  </a>
                <?php endif ?>              
                
                
              </div>
            </div>
            </li> 

            <li role="presentation" class="nav-item dropdown  open d-xs-none">
             <div class="dropdown mr-1 styledrop">
              <button type="button" class="btn bg-cafe text-white h28 dropdown-toggle p-1 br0" data-toggle="dropdown" aria-expanded="false" data-offset="-140,10">
                <i class="fa fa-archive vtc"></i> Gestión
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" id="nuevoFlujoCrmData" style="background-color: #c9d1db !important; color: #000 !important; padding-left: 25px !important;" href="#">Crear oportunidad</a>
                <a href="<?php echo $this->Html->url(array("controller" => "assists", "action" => "add")) ?>" class="dropdown-item">
                    Registrar ingreso o salida
                  </a>
                <a class="dropdown-item bg-gris" href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "carga_factura")) ?>">
                    Subir factura
                  </a>
                <?php if (AuthComponent::user("role") == "Gerente General"): ?>
                  <a href="<?php echo $this->Html->url(array("controller" => "excludes", "action" => "add")) ?>" class="dropdown-item">
                    Registrar dia excluido
                  </a>
                  <a class="dropdown-item" id="sendMessajeAll" style="background-color: #c9d1db !important;" href="<?php echo $this->Html->url(array('controller'=>'manages','action'=>'message')) ?>">
                    Nuevo mensaje
                  </a> 

                  <a class="dropdown-item bg-gris" ef="<?php echo $this->Html->url(array('controller'=>'times','action'=>'index')) ?>">
                    Configuración tiempos chat
                  </a> 
                <?php endif ?>
                
              </div>
            </div>
            </li>  
           
            
          <li role="presentation" class="nav-item d-xs-none">
              <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_especial")) ?>" class="nav-link br0 p-1 h28"  id="simpleflow">
                <i class="fa fa-x fa-plus-circle"></i> 
                Flujo Express
              </a>
            </li>
           <li role="presentation" class="nav-item d-xs-none">
              <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "pendientes_gestion")) ?>" class="nav-link br0 p-1 bg-red h28" id="simpleflow">
                <i class="fa fa-x fa-warning" style="    animation: spinner-grow 1s ease-in-out infinite;"></i> 
                Gestión pendiente
              </a>
            </li>
            <li role="presentation" class="nav-item d-xs-none">
              <a  href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_tienda")) ?>" class="nav-link br0 p-1 bg-warning h28" id="simpleflow">
                <i class="fa fa-x fa-plus-circle"></i> 
                Venta en tienda
              </a>
            </li>
            <?php if (AuthComponent::user("role") == "Gerente General" || AuthComponent::user("role") == "Logística"): ?>
              <li role="presentation" class="nav-item d-xs-none">
                <a  href="<?php echo $this->Html->url(array("controller" => "sb_users", "action" => "racing_ventas",time())) ?>" class="nav-link br0 p-1 h28 bg-green mr-2" target="_blank" >
                  <i class="fa fa-x fa-plus-circle"></i> 
                  Inventario Ventas Racing
                </a>
              </li>
            <?php endif ?>

          <?php else: ?>
            <li role="presentation" class="nav-item d-xs-none">
              <a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "send_message")) ?>" class="nav-link br0 btn btn-warning p-1" id="messageBoss" style="padding-bottom: 0px !important;">
                <i class="fa fa-x fa-plus-circle"></i> 
                Enviar mensaje al gerente
              </a>
            </li>
            <li role="presentation" class="nav-item d-xs-none">
            <a href="#" class="nav-link p-1" data-toggle="tooltip" data-placement="right" title="Crear flujo" id="nuevoFlujoCrmData">
              <i class="fa fa-x fa-plus-circle"></i> 
              Crear oportunidad
            </a>
          </li>
          <?php endif ?>
          


          <li class=" float-lg-left float-md-left nav-item" style="margin-top: 1px">
            <div class="input-group searchobject" style="margin-top: -5px;">
              <button class="buscar" type="button">
                <i class="fa fa-search sizemax vtc"></i>
              </button>
              <input class="form-control hiddenspecial" type="text" placeholder="¿Qué necesitas buscar?">
              <span class="input-group-append"></span>
            </div>
          </li>

          <?php endif ?>
          
          
        </ul>
    </nav>
  </div>
</div>