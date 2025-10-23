<?php 
	$readonly = "";
	if(AuthComponent::user("role") != Configure::read('variables.roles_usuarios.Gerente General')){
		// $readonly = "readonly";
	}
 ?>

<div class="col-md-12">
	<div class=" widget-panel widget-style-2 bg-azulclaro big">
		<i class="fa fa-1x flaticon-growth"></i>
		<h2 class="m-0 text-white bannerbig">Módulo de Gestión CRM </h2>
	</div>
	<div class="blockwhite spacebtn20">
		<h2 class="titleviewer">Editar marca</h2>
	</div>	
	<div class="blockwhite spacebtn20">
		<div class="brands form">
			<?php echo $this->Form->create('Brand'); ?>
			<?php
			echo $this->Form->input('id');
			echo $this->Form->input('name',array("label" => "Nombre Marca","required"));
			echo $this->Form->input('provider',array("label" => "Nombre Proveedor","required"));
			echo $this->Form->input('social_reason',array("label" => "Razón social","required"));
			echo $this->Form->input('address',array("label" => "Dirección","required"));
			echo $this->Form->input('dni',array("label" => "Identificación","required"));
			echo $this->Form->input('city',array("label" => "Ciudad","required"));
			echo $this->Form->input('phone',array("label" => "Teléfono","required"));		
			echo $this->Form->input('email',array("label" => "Correo eléctronico principal", $readonly,"required"));
			echo $this->Form->input('copy_emails',array("label" => "Correo eléctronicos adicionales", $readonly,"required", "value" => $this->request->data["Brand"]["copy_emails"],"type" => "text" ));
			echo $this->Form->input('contact_name',array("label" => "Nombre contacto","required"));
			echo $this->Form->input('min_price_importer',array("label" => "Costo mínimo de importación","type" => "text","value" => number_format($brand['Brand']['min_price_importer'],0,",","."),"required"));
			echo $this->Form->input('brand_id',array("label" => "Marca principal","options" => $brands));
			echo $this->Form->input('id_llc',array("label" => "Marca asociada en Kebco LLC","options" => $id_llc,"empty" => "Seleccionar"));
			?>
			<?php echo $this->Form->end(__('Actualizar')); ?>
		</div>
	</div>
	</div>


	<?php
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()),						array('block' => 'jqueryApp'));
	echo $this->Html->script("controller/brands/save.js?".rand(),						array('block' => 'AppScript'));
	?>