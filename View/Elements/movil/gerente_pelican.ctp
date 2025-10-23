<div id="menuData">
  <ul class="p-0">
  	<li>
  		<a href="#">
  			<h5>Creación/Aprobación de flujos</h5>
  		</a>
  		<ul class="p-0">
  			<li class="nav-item">
				<a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_especial")) ?>" id="simpleflow">
					<i class="fa fa-x fa-plus-circle"></i> 
					Crear flujo express
				</a>
			</li>
			<li class="nav-item">
				<a href="<?php echo $this->Html->url(array("controller" => "ProspectiveUsers", "action" => "flujo_tienda")) ?>" id="nuevoFlujoTienda">
					<i class="fa fa-x fa-plus-circle"></i> 
					Crear venta en tienda
				</a>
			</li>
			<li class="nav-item newoport">
				<a href="#" id="nuevoFlujoCrmData">
					<i class="fa fa-x fa-plus-circle"></i> 
					Crear  oportunidad
				</a>
			</li>
  		</ul>
  	</li>
    <li class="bg-naranja">
      <a href="<?php echo $this->Html->url(array('controller'=>'Pages','action'=> $this->Utilities->paint_home_menu_user(AuthComponent::user('role')))) ?>">
      	<h4 class="m-0 text-white">Dashboard</h4>
      </a>
    </li>
    <li class="bg-azulclaro">
      <a href="#">
      	<h4 class="m-0 ">Gestión CRM</h4>
      </a>
      <ul class="p-0 bg-azulclaro">
      	<li>
      		<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'index')) ?>" >
				Flujos de Negocio
			</a>
      	</li>
        <li>
          <a href="<?php echo $this->Html->url(array('controller'=>'Products','action'=>'index')) ?>">Productos creados</a>
        </li>
        <li>
        	<a href="<?php echo $this->Html->url(array('controller'=>'ClientsLegals','action'=>'index')) ?>" >
				Clientes registrados
			</a>
        </li>
        <li>
          <a href="#">Cotizaciones</a>
          <ul class="p-0 bg-azulclaro">
            <li>
              	<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'quotes_sent')) ?>" >
					Cotizaciones generadas <b><?php echo $this->Utilities->count_cotizaciones_enviadas(); ?> </b>
				</a>
            </li>
            <li>
            	<a href="<?php echo $this->Html->url(array('controller'=>'Quotations','action'=>'tracking')) ?>" >
					Cotizaciones sin leer/click </b>
				</a>
            </li>
            <li>
            	<a href="<?php echo $this->Html->url(array('controller'=>'Templates','action'=>'index')) ?>" >
					Plantillas de Cotizaciones
				</a>
            </li>
            <li>
            	<a href="<?php echo $this->Html->url(array('controller'=>'Notes','action'=>'index')) ?>" >
					Notas de Cotizaciones
				</a>
            </li>		
          </ul>
        </li>
        <li>
        	<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'aprovee')) ?>" >
				Aprobar cotizaciones <b> <span class="counterAprove"><?php echo $this->Utilities->count_cotizaciones_aprobar(); ?></span>  </b>
			</a>
        </li>
        <li>
        	<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
				Buscador de despachos
			</a>
        </li>
      </ul>
    </li>
    <li class="bg-morado">
      <a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'imports_revisions')) ?>">
      	<h4 class="m-0 text-white">Compras</h4>
      </a>
    </li>
    <li class="bg-rojo">
      <a href="#">
      	<h4 class="m-0 text-white">Servicio Técnico</h4>
      </a>
      <ul class="p-0 bg-rojo">
        <li>
			<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'index')) ?>" >
				Órdenes en Proceso
			</a>
		</li>
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'add')) ?>" >
				Ingreso de Equipos
			</a>
		</li>	
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'TechnicalServices','action'=>'flujos')) ?>" >
				Negocios desde ST
			</a>
		</li>
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'reporte_tecnico')) ?>" >
				Reporte de ventas
			</a>
		</li>	
      </ul>
    </li>
    <li class="bg-cafe">
      <a href="#"><h4 class="m-0 text-black">Informes</h4></a>
      <ul class="p-0 bg-cafe">
        <li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_date_flujos',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Informe de Prospectos
			</a>
		</li>
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_adviser',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Atención de Flujos
			</a>
		</li>	
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_management',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Gestión Comercial
			</a>
		</li>	

		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_advisers',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Informe de Asesores
			</a>
		</li>	
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'report_customer_new',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Nuevos clientes
			</a>
		</li>
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'Receipts','action'=>'report',"?"=>array("ini" =>date("Y-m-d",strtotime("- 1 day")), "end" => date("Y-m-d")  ))) ?>" >
				Recibos de caja
			</a>
		</li>																								
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'ProspectiveUsers','action'=>'despachos')) ?>" >
				Buscador de despachos
			</a>
		</li>	
      </ul>
    </li>
    <li class="bg-azul">
      <a href="#"><h4 class="m-0 text-black">Configuraciones</h4></a>
      <ul class="p-0 bg-azul">
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'Headers','action'=>'index')) ?>" >
				Banners de Cotizaciones
			</a>
		</li>																										
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'Manages','action'=>'diary')) ?>" >
				Mi Agenda
			</a>
		</li>
      </ul>
    </li>
    <li class="bg-dark">
    	<a href="<?php echo $this->Html->url(array('controller'=>'Users','action'=>'logout')) ?>">
			Cerrar sesión <i class="fa fa-1x fa-power-off"></i>
		</a>		
    </li>
  </ul>
</div>