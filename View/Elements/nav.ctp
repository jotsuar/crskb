<nav class="navbar navbar-expand-lg navbar-dark fixed-top " id="mainNav">
	<div class="barmin">
		<button class="navbar-toggler navbar-toggler-right" id="toggleMenu" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="objetct">
			<form class="form-inline">
				<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'login')) ?>">
					<img src="<?php echo $this->Html->url('/img/assets/brand2.png'); ?>" class="brandcrm">
				</a>
				<button class="navbar-toggler navbar-toggler-left" id="toggleMenu2" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="input-group searchobject">
					<button class="buscar" type="button">
						<i class="fa fa-search sizemax vtc"></i>
					</button>
					<input class="form-control hiddenspecial" type="text" placeholder="¿Qué necesitas buscar?">
					<span class="input-group-append"></span>
				</div>
			</form>
		</div>
	</div>

	<div class="collapse navbar-collapse" id="navbarResponsive" >
		<?php if ($movileAccess): ?>
			<?php 
				switch (AuthComponent::user("role")) {
					case 'Gerente General':
						echo $this->element("movil/gerente_general");
						break;
					case 'Asesor Comercial':
						echo $this->element("movil/asesor_comercial");
						break;
					case 'Contabilidad':
						echo $this->element("movil/contabilidad");
						break;
					case 'Logística':
						echo $this->element("movil/logistica");
						break;
					case 'Asesor Técnico Comercial':
						echo $this->element("movil/asesor_tecnico_comercial");
						break;
					case 'Gerente línea Productos Pelican':
						echo $this->element("movil/gerente_pelican");
						break;
					case 'Asesor Logístico Comercial':
						echo $this->element("movil/asesor_logistico_comercial");
						break;
					case 'Servicio al Cliente':
						echo $this->element("movil/servicio_cliente");
						break;
				}
			?>
        <?php else: ?>
		
		<ul class="navbar-nav navbar-sidenav navActive itemsopen" id="navleft">
			<div class="databuy ">
				<div class="verticalinfo">
					<p>Mis ventas hoy</p>
					<h2>$ <span id="countMetasDayUser"></span></h2>
				</div>
				<div class="reduce">
					<?php $roles = array(Configure::read('variables.roles_usuarios.Asesor Comercial'),Configure::read('variables.roles_usuarios.Gerente General'),Configure::read('variables.roles_usuarios.Servicio Técnico'),Configure::read('variables.roles_usuarios.Asesor Técnico Comercial'),Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican'),Configure::read('variables.roles_usuarios.Asesor Logístico Comercial'),Configure::read('variables.roles_usuarios.Servicio al Cliente')) ?>
					<?php if (in_array(AuthComponent::user('role'), $roles)): ?>
						<p>Mis ventas de hoy</p>
						<h2>$ <span id="countMetasDayUser"></span></h2>
						<p>Mis ventas del mes $<span id="countSalesUserMonth"></span></p>
					<?php endif ?>
					<p>Ventas del mes $<span id="countSalesMonth"></span></p>
					<br>
				</div>
			</div> 

			<?php if (AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Asesor Comercial')) { ?>
				<div class="itemnewmenu grupo-dashboard dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>				
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>	
						<li class="quotes_approve-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>" >
								Aprobar cotizaciones <b><span class="counterAprove"><?php echo $this->Utilities->count_cotizaciones_aprobar(); ?></span> </b>
							</a>
						</li>						
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-importaciones" >
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
						<div class="widget-panel-menu bg-morado">
							<i class="fa flaticon-tourism"></i>
							<h2 class="m-0 text-white">Compras</h2>
							<div>Solicitudes y estado de pedidos</div>
						</div>
					</a>
				</div>	
				<div class="itemnewmenu grupo-informes" data-toggle="collapse" href="#informes">
					<div class="widget-panel-menu bg-cafe">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="minitem">
							<i class="fa flaticon-report-1"></i>
						</a>						
						<i class="fa flaticon-report-1 text-black maxitem"></i>
						<h2 class="m-0 text-black">Informes</h2>
						<div class="text-black">Gerenciales y productividad</div>
					</div>
					<ul class="sidenav-second-level collapse" id="informes">
						<li class="report_date_flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
								Informe de Prospectos
							</a>
						</li>
					</ul>					
				</div>
				<div class="itemnewmenu grupo-configuracion" data-toggle="collapse" href="#config">
					<div class="widget-panel-menu bg-azul">
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings"></i>
						</a>						
						<i class="fa flaticon-settings maxitem"></i>
						<h2 class="m-0 text-white">Configuraciones</h2>
						<div>Roles, permisos y privilegios</div>
					</div>
					<ul class="sidenav-second-level collapse" id="config">

						<li class="Headers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
								Banners de Cotizaciones
							</a>
						</li>																										
						<li class="Manages-diary-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
								Mi Agenda
							</a>
						</li>												
					</ul>					
				</div>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Contabilidad')) { ?>
				<div class="itemnewmenu dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>	
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>		
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>						
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-tesoreria" data-toggle="collapse" href="#tesoreria">
					<div class="widget-panel-menu bg-verde">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>" class="minitem">
							<i class="fa flaticon-money"></i>
						</a>
						<i class="fa flaticon-money maxitem"></i>
						<h2 class="m-0 text-white">Tesorería</h2>
						<div>Aprobación de pagos y créditos</div>
					</div>
					<ul class="sidenav-second-level collapse" id="tesoreria">
						<li class="verify_payment-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment')) ?>" >
								Verificación de Pagos
							</a>
						</li>
						<li class="verify_payment-tienda">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_tienda')) ?>" >
								Verificación de pagos en tienda
							</a>
						</li>
						<li class="verify_payment_credito-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payment_credito')) ?>" >
								Verificación de Créditos
							</a>
						</li>	
						<li class="verify_payments_payments-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'verify_payments_payments')) ?>" >
								Verificar abonos totales
							</a>
						</li>																										
						<li class="payment_true-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_true')) ?>" >
								Pagos verificados
							</a>
						</li>
						<li class="payment_false-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'payment_false')) ?>" >
								Pagos rechazados
							</a>
						</li>
						<li class="informe_ventas-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas')) ?>" >
								Informe de ventas
							</a>
						</li>
						<li class="informe_comisiones-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_comisiones')) ?>" >
								Informe de comisiones
							</a>
						</li>
						<li class="informe_comisiones-informeVentasTienda">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'informe_ventas_tienda')) ?>" >
								Informe de ventas en tienda
							</a>
						</li>
					</ul>					
				</div>  		
				<div class="itemnewmenu grupo-informes" data-toggle="collapse" href="#informes">
					<div class="widget-panel-menu bg-cafe">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="minitem">
							<i class="fa flaticon-report-1"></i>
						</a>						
						<i class="fa flaticon-report-1 text-black maxitem"></i>
						<h2 class="m-0 text-black">Informes</h2>
						<div class="text-black">Gerenciales y productividad</div>
					</div>
					<ul class="sidenav-second-level collapse" id="informes">
						<li class="report_date_flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
								Informe de Prospectos
							</a>
						</li>
					</ul>					
				</div>
				<div class="itemnewmenu grupo-serviciotecnico" data-toggle="collapse" href="#serviciot">
					<div class="widget-panel-menu bg-rojo">
						<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings-1"></i>
						</a>
						<i class="fa flaticon-settings-1 maxitem"></i>
						<h2 class="m-0 text-white">Servicio Técnico</h2>
						<div>Gestión de equipos ingresados</div>
					</div>
					<ul class="sidenav-second-level collapse" id="serviciot">
						<li class="TechnicalServices-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" >
								Órdenes en Proceso
							</a>
						</li>
	
						<li class="TechnicalServices-flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" >
								Negocios desde ST
							</a>
						</li>
																									
					</ul>
				</div>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Logística')) { ?>
				<div class="itemnewmenu dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>	
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Categories','action'=>'index')) ?>" >
								Categorías de productos
							</a>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>								
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="movements-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Inventories','action'=>'movements')) ?>" >
								Movimientos de inventario
							</a>
						</li>
						<li class="movements_list-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Inventories','action'=>'panel_movimientos')) ?>" >
								Listado de movimientos de inventario
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
						<li class="facturas-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'facturas')) ?>" >
								Buscador de facturas
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-importaciones" >
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'request_import_brands')) ?>">
						<div class="widget-panel-menu bg-morado">
							<i class="fa flaticon-tourism"></i>
							<h2 class="m-0 text-white">Compras</h2>
							<div>Solicitudes y estado de pedidos</div>
						</div>
					</a>
				</div>			
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Gerente General')) { ?>
				<?php echo $this->element("roles/admin_general") ?>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Asesor Técnico Comercial')) { ?>
				<?php echo $this->element("roles/asesor_tecnico_comercial") ?>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Gerente línea Productos Pelican')) {  ?>
				<div class="itemnewmenu grupo-dashboard dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>				
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>								
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-importaciones" >
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
						<div class="widget-panel-menu bg-morado">
							<i class="fa flaticon-tourism"></i>
							<h2 class="m-0 text-white">Compras</h2>
							<div>Solicitudes y estado de pedidos</div>
						</div>
					</a>
				</div>			
				<div class="itemnewmenu grupo-serviciotecnico" data-toggle="collapse" href="#serviciot">
					<div class="widget-panel-menu bg-rojo">
						<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings-1"></i>
						</a>
						<i class="fa flaticon-settings-1 maxitem"></i>
						<h2 class="m-0 text-white">Servicio Técnico</h2>
						<div>Gestión de equipos ingresados</div>
					</div>
					<ul class="sidenav-second-level collapse" id="serviciot">
						<li class="TechnicalServices-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" >
								Órdenes en Proceso
							</a>
						</li>
						<li class="TechnicalServices-add-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" >
								Ingreso de Equipos
							</a>
						</li>	
						<li class="TechnicalServices-flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" >
								Negocios desde ST
							</a>
						</li>
						<li class="ProspectiveUsers-reporte_tecnico-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'reporte_tecnico')) ?>" >
								Reporte de ventas
							</a>
						</li>																										
					</ul>
				</div>
				<div class="itemnewmenu grupo-informes" data-toggle="collapse" href="#informes">
					<div class="widget-panel-menu bg-cafe">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="minitem">
							<i class="fa flaticon-report-1"></i>
						</a>						
						<i class="fa flaticon-report-1 text-black maxitem"></i>
						<h2 class="m-0 text-black">Informes</h2>
						<div class="text-black">Gerenciales y productividad</div>
					</div>
					<ul class="sidenav-second-level collapse" id="informes">
						<li class="report_date_flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
								Informe de Prospectos
							</a>
						</li>
					</ul>					
				</div>
				<div class="itemnewmenu grupo-configuracion" data-toggle="collapse" href="#config">
					<div class="widget-panel-menu bg-azul">
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings"></i>
						</a>						
						<i class="fa flaticon-settings maxitem"></i>
						<h2 class="m-0 text-white">Configuraciones</h2>
						<div>Roles, permisos y privilegios</div>
					</div>
					<ul class="sidenav-second-level collapse" id="config">

						<li class="Headers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
								Banners de Cotizaciones
							</a>
						</li>																											
						<li class="Manages-diary-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
								Mi Agenda
							</a>
						</li>												
					</ul>					
				</div>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Asesor Logístico Comercial')) { ?>

				<div class="itemnewmenu grupo-dashboard dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>				
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>								
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-importaciones" >
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
						<div class="widget-panel-menu bg-morado">
							<i class="fa flaticon-tourism"></i>
							<h2 class="m-0 text-white">Compras</h2>
							<div>Solicitudes y estado de pedidos</div>
						</div>
					</a>
				</div>
				<div class="itemnewmenu grupo-despachos" data-toggle="collapse" href="#despachos">
					<div class="widget-panel-menu bg-aguamarina">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>" class="minitem">
							<i class="fa flaticon-logistics-delivery-truck-and-clock"></i>
						</a>						
						<i class="fa flaticon-logistics-delivery-truck-and-clock maxitem"></i>
						<h2 class="m-0 text-white">Despachos</h2>
						<div>Guías y envíos nacionales</div>
					</div>
					<ul class="sidenav-second-level collapse" id="despachos">
						<li class="information_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>" >
								Datos para despachar
							</a>
						</li>
						<li class="pending_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches')) ?>" >
								Despachos por enviar
							</a>
						</li>	
						<li class="status_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>" >
								Despachos por confirmar
							</a>
						</li>																										
						<li class="status_dispatches_finish-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches_finish')) ?>" >
								Despachos Finalizados
							</a>
						</li>
						<li class="conveyors-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" >
								Gestión de transportadoras
							</a>
						</li>
					</ul>						
				</div> 				
				<div class="itemnewmenu grupo-informes" data-toggle="collapse" href="#informes">
					<div class="widget-panel-menu bg-cafe">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="minitem">
							<i class="fa flaticon-report-1"></i>
						</a>						
						<i class="fa flaticon-report-1 text-black maxitem"></i>
						<h2 class="m-0 text-black">Informes</h2>
						<div class="text-black">Gerenciales y productividad</div>
					</div>
					<ul class="sidenav-second-level collapse" id="informes">
						<li class="report_date_flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
								Informe de Prospectos
							</a>
						</li>
					</ul>					
				</div>
				<div class="itemnewmenu grupo-configuracion" data-toggle="collapse" href="#config">
					<div class="widget-panel-menu bg-azul">
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings"></i>
						</a>						
						<i class="fa flaticon-settings maxitem"></i>
						<h2 class="m-0 text-white">Configuraciones</h2>
						<div>Roles, permisos y privilegios</div>
					</div>
					<ul class="sidenav-second-level collapse" id="config">

						<li class="Headers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
								Banners de Cotizaciones
							</a>
						</li>																											
						<li class="Manages-diary-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
								Mi Agenda
							</a>
						</li>												
					</ul>					
				</div>
			<?php } else if(AuthComponent::user('role') == Configure::read('variables.roles_usuarios.Servicio al Cliente')) { ?>

				<div class="itemnewmenu grupo-dashboard dashboard">
					<a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
					<div class="widget-panel-menu bg-naranja">
						<i class="fa flaticon-menu-1"></i>
						<h2 class="m-0 text-white">Dashboard</h2>
						<div>Agenda, últimos flujos y ventas</div>
					</div>
					</a>
				</div>				
				<div class="itemnewmenu grupo-gestioncrm" data-toggle="collapse" href="#gestion-crm">
					<div class="widget-panel-menu bg-azulclaro">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-growth"></i>
						</a>
						<i class="fa flaticon-growth maxitem"></i>
						<h2 class="m-0 ">Gestión CRM</h2>
						<div>Ventas, cliente y cotizaciones</div>
					</div>
					<ul class="sidenav-second-level collapse" id="gestion-crm">
						<li class="ProspectiveUsers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
								Flujos de Negocio
							</a>
						</li>
						<li class="Products-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>" >
								Productos creados
							</a>
						</li>	
						<li class="ClientsLegals-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
								Clientes registrados
							</a>
						</li>
						<li class="quotes_sent-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
								Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
							</a>
						</li>
						<li class="quotes_read-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
								Cotizaciones sin leer/click </b>
							</a>
						</li>								
						<li class="templates-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
								Plantillas de Cotizaciones
							</a>
						</li>
						<li class="notes-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
								Notas de Cotizaciones
							</a>
						</li>
						<li class="despachos-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
								Buscador de despachos
							</a>
						</li>
						<li class="Products-index-remarketing">
							<a href="<?php echo $this->Html->url(array('controller'=>'campaigns','action'=>'index')) ?>" >
								Remarketing de productos
							</a>
						</li>
						<li class="facturas-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'facturas')) ?>" >
								Buscador de facturas
							</a>
						</li>
					</ul>
				</div>
				<div class="itemnewmenu grupo-importaciones" >
					<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
						<div class="widget-panel-menu bg-morado">
							<i class="fa flaticon-tourism"></i>
							<h2 class="m-0 text-white">Compras</h2>
							<div>Solicitudes y estado de pedidos</div>
						</div>
					</a>
				</div>
				<div class="itemnewmenu grupo-despachos" data-toggle="collapse" href="#despachos">
					<div class="widget-panel-menu bg-aguamarina">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>" class="minitem">
							<i class="fa flaticon-logistics-delivery-truck-and-clock"></i>
						</a>						
						<i class="fa flaticon-logistics-delivery-truck-and-clock maxitem"></i>
						<h2 class="m-0 text-white">Despachos</h2>
						<div>Guías y envíos nacionales</div>
					</div>
					<ul class="sidenav-second-level collapse" id="despachos">
						<li class="information_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'information_dispatches')) ?>" >
								Datos para despachar
							</a>
						</li>
						<li class="pending_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'pending_dispatches')) ?>" >
								Despachos por enviar
							</a>
						</li>	
						<li class="status_dispatches-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches')) ?>" >
								Despachos por confirmar
							</a>
						</li>																										
						<li class="status_dispatches_finish-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'status_dispatches_finish')) ?>" >
								Despachos Finalizados
							</a>
						</li>
						<li class="conveyors-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'conveyors','action'=>'index')) ?>" >
								Gestión de transportadoras
							</a>
						</li>
					</ul>						
				</div> 				
				<div class="itemnewmenu grupo-serviciotecnico" data-toggle="collapse" href="#serviciot">
					<div class="widget-panel-menu bg-rojo">
						<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings-1"></i>
						</a>
						<i class="fa flaticon-settings-1 maxitem"></i>
						<h2 class="m-0 text-white">Servicio Técnico</h2>
						<div>Gestión de equipos ingresados</div>
					</div>
					<ul class="sidenav-second-level collapse" id="serviciot">
						<li class="TechnicalServices-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" >
								Órdenes en Proceso
							</a>
						</li>
						<li class="TechnicalServices-add-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" >
								Ingreso de Equipos
							</a>
						</li>	
						<li class="TechnicalServices-flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" >
								Negocios desde ST
							</a>
						</li>
						<li class="ProspectiveUsers-reporte_tecnico-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'reporte_tecnico')) ?>" >
								Reporte de ventas
							</a>
						</li>																										
					</ul>
				</div>
				<div class="itemnewmenu grupo-informes" data-toggle="collapse" href="#informes">
					<div class="widget-panel-menu bg-cafe">
						<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" class="minitem">
							<i class="fa flaticon-report-1"></i>
						</a>						
						<i class="fa flaticon-report-1 text-black maxitem"></i>
						<h2 class="m-0 text-black">Informes</h2>
						<div class="text-black">Gerenciales y productividad</div>
					</div>
					<ul class="sidenav-second-level collapse" id="informes">
						<li class="report_date_flujos-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
								Informe de Prospectos
							</a>
						</li>
					</ul>					
				</div>
				<div class="itemnewmenu grupo-configuracion" data-toggle="collapse" href="#config">
					<div class="widget-panel-menu bg-azul">
						<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" class="minitem">
							<i class="fa flaticon-settings"></i>
						</a>						
						<i class="fa flaticon-settings maxitem"></i>
						<h2 class="m-0 text-white">Configuraciones</h2>
						<div>Roles, permisos y privilegios</div>
					</div>
					<ul class="sidenav-second-level collapse" id="config">

						<li class="Headers-index-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
								Banners de Cotizaciones
							</a>
						</li>																											
						<li class="Manages-diary-item">
							<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
								Mi Agenda
							</a>
						</li>												
					</ul>					
				</div>
			<?php } ?>

		</ul>
		<?php endif ?>
		<?php echo $this->element('nav_above')?>
	</div>
</nav>