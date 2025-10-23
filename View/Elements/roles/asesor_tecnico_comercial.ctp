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
		<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>$this->Utilities->last_1_month_date(), "end" => date("Y-m-d")  ))) ?>" class="minitem">
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
