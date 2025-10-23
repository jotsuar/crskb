<div class="sobreback">
    <div class="container text-center">
        <img class="img-fluid animated fadeIn logointro" src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>">
    </div>

    <div class="banner-text">
        <h1 id="title-behind">SISTEMA DE GESTIÓN</h1>
        <h1 id="title-lens">EMPRESARIAL</h1>
        <h2 id="subtitle">Kebco ERP</h2>
    </div>

    <div class="container animated fadeIn">
        <div class="row">
            <?php if (AuthComponent::user("role") != "Publicidad"): ?>
            <div class="col-xl-3 col-md-6">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="text-white">
                    <div class="widget-panel widget-style-2 bg-azulclaro zoom">
                        <i class="fa fa-1x flaticon-growth"></i>
                        <h2 class="m-0 ">Gestión CRM</h2>
                        <div>Ventas, cliente y cotizaciones</div>
                    </div>
                </a>
            </div>
            <?php endif ?>

        <?php if (in_array(AuthComponent::user("role"), array(Configure::read('variables.roles_usuarios.Contabilidad'),Configure::read('variables.roles_usuarios.Gerente General') ))): ?>
        <div class="col-xl-3 col-md-6">
            <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>" class="text-white">
                <div class="widget-panel widget-style-2 bg-verde zoom">
                    <i class="fa fa-1x flaticon-money"></i>
                    <h2 class="m-0 text-white"><a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>" class="text-white">Tesorería</h2>
                        <div>Aprobación de pagos y créditos</div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <?php if (in_array( AuthComponent::user("role"),array( Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),
            Configure::read('variables.roles_usuarios.Gerente General'),
            Configure::read('variables.roles_usuarios.Logística'),
            Configure::read('variables.roles_usuarios.Servicio al Cliente') ) )): ?>

            <div class="col-xl-3 col-md-6">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches')) ?>" class="text-white">
                    <div class="widget-panel widget-style-2 bg-aguamarina zoom">
                        <i class="fa fa-1x flaticon-logistics-delivery-truck-and-clock"></i>
                        <h2 class="m-0 text-white" >Despachos</h2>
                        <div>Guías y despachos nacionales</div>
                    </div>
                </a>
            </div>
        <?php endif ?>  

        <?php if (AuthComponent::user("role") != "Publicidad"): ?>
            
            <?php if (AuthComponent::user("role") != "Asesor Externo"): ?>
            
                <div class="col-xl-3 col-md-6">
                    <a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" class="text-white">
                        <div class="widget-panel widget-style-2 bg-rojo zoom">
                            <i class="fa fa-1x flaticon-settings-1"></i>
                            <h2 class="m-0 text-white">Servicio Técnico</h2>
                            <div>Gestión de equipos ingresados</div>
                        </div>
                    </a>
                </div>
              
            <?php endif ?>

          <div class="col-xl-3 col-md-6">
                     <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>" class="text-white">
                        <div class="widget-panel widget-style-2 bg-naranja zoom">
                            <i class="fa fa-1x flaticon-menu-1"></i>
                            <h2 class="m-0 text-white">Dashboard</h2>
                            <div>Agenda, últimos flujos y ventas</div>
                        </div>
                    </a>
                    </div>

            <div class="col-xl-3 col-md-6">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>AuthComponent::user("role") != "Asesor Externo" ? 'report_management' : 'ventas_report',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="text-white">
                    <div class="widget-panel widget-style-2 bg-cafe zoom">
                        <i class="fa fa-1x flaticon-report-1 text-black"></i>
                        <h2 class="m-0 text-black">Informes</h2>
                        <div class="text-black">Gerenciales y productividad</div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>">
                    <div class="widget-panel widget-style-2 bg-azul zoom">
                        <i class="fa fa-1x flaticon-settings"></i>
                        <h2 class="m-0 text-white" >Configuraciones</h2>
                        <div>Roles, permisos y privilegios</div>
                    </div>
                </a>
            </div>      
        <?php else: ?>
            <div class="col-xl-3 col-md-6">
                     <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>" class="text-white">
                        <div class="widget-panel widget-style-2 bg-naranja zoom">
                            <i class="fa fa-1x flaticon-menu-1"></i>
                            <h2 class="m-0 text-white">Dashboard</h2>
                            <div>Agenda, últimos flujos y ventas</div>
                        </div>
                    </a>
                    </div>

            <div class="col-xl-3 col-md-6">
                <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>"marketing","?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="text-white">
                    <div class="widget-panel widget-style-2 bg-cafe zoom">
                        <i class="fa fa-1x flaticon-report-1 text-black"></i>
                        <h2 class="m-0 text-black">Informes</h2>
                        <div class="text-black">Gerenciales y productividad</div>
                    </div>
                </a>
            </div>    
        <?php endif ?>
        
        
</div>
<div class="text-center closesesion">    
    <a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout')) ?>" class="">
        <i class="fa fa-1x fa-power-off"></i> Cerrar sesión
    </a>
</div>
</div>
</div>
<p class="copyright">Derechos reservados KEBCO S.A.S. ® <?php echo date ("Y"); ?></p>
<?php
echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),					array('block' => 'jqueryApp'));
?>