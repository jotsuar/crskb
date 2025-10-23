<div class="importProducts form">
<?php echo $this->Form->create('ImportProduct'); ?>
	<fieldset>
		<legend><?php echo __('Add Import Product'); ?></legend>
	<?php
		echo $this->Form->input('import_id');
		echo $this->Form->input('product_id');
		echo $this->Form->input('quotations_products_id');
		echo $this->Form->input('prospective_user_id');
		echo $this->Form->input('quantity');
		echo $this->Form->input('quantity_final');
		echo $this->Form->input('quantity_back');
		echo $this->Form->input('quantity_back_total');
		echo $this->Form->input('currency');
		echo $this->Form->input('price');
		echo $this->Form->input('numero_orden');
		echo $this->Form->input('proveedor');
		echo $this->Form->input('fecha_orden');
		echo $this->Form->input('link');
		echo $this->Form->input('fecha_estimada');
		echo $this->Form->input('fecha_miami');
		echo $this->Form->input('numero_guia');
		echo $this->Form->input('transportadora');
		echo $this->Form->input('fecha_nacionalizacion');
		echo $this->Form->input('fecha_producto_empresa');
		echo $this->Form->input('state_import');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Import Products'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Imports'), array('controller' => 'imports', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Import'), array('controller' => 'imports', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Quotations Products'), array('controller' => 'quotations_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Quotations Products'), array('controller' => 'quotations_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prospective Users'), array('controller' => 'prospective_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prospective User'), array('controller' => 'prospective_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
